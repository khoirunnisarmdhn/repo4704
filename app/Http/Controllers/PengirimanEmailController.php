<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengirimanemail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;

class PengirimanEmailController extends Controller
{
    public static function proses_kirim_email_pembayaran()
    {
        date_default_timezone_set('Asia/Jakarta');

        // 1. Ambil penjualan yang sudah dibayar dan belum dikirim email
        $data = DB::table('penjualan')
            ->join('pelanggan', 'penjualan.pelanggan_id', '=', 'pelanggan.id')
            ->join('users', 'pelanggan.user_id', '=', 'users.id')
            ->where('penjualan.status', 'bayar')
            ->whereNotIn('penjualan.id', function ($query) {
                $query->select('penjualan_id')->from('pengirimanemail');
            })
            ->select('penjualan.id', 'penjualan.no_faktur', 'users.email', 'penjualan.pembeli_id')
            ->get();

        foreach ($data as $p) {
            $id = $p->id;
            $email = $p->email;

            // 2. Ambil detail penjualan
            $detail_penjualan = DB::table('detail_penjualan')
                ->join('detail_penjualan', 'detail_penjualan.id', '=', 'detail_penjualan.penjualan_id')
                ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.penjualan_id')
                ->join('produk', 'detail_penjualan.produk_id', '=', 'produk.id')
                ->join('pelanggan', 'penjualan.pelanggan_id', '=', 'pelanggan.id')
                ->select(
                    'penjualan.id',
                    'penjualan.no_faktur',
                    'pelanggan.nama_pelanggan',
                    'detail_penjualan.produk_id',
                    'produk.nama_produk',
                    'detail_penjualan.harga_satuan',
                    'produk.foto',
                    DB::raw('SUM(detail_penjualan.jml) as total'),
                    DB::raw('SUM(detail_penjualan.harga_satuan * detail_penjualan.jml) as total_belanja')
                )
                ->where('penjualan.id', $id)
                ->groupBy(
                    'penjualan.id',
                    'penjualan.no_faktur',
                    'pelanggan.nama_pelanggan',
                    'detail_penjualan.produk_id',
                    'produk.nama_produk',
                    'detail_penjualan.harga_satuan',
                    'produk.foto'
                )
                ->get();

            // Lewati jika tidak ada barang
            if ($barang->isEmpty()) {
                continue;
            }

            // Ambil total belanja dari salah satu baris (semua sama karena pakai SUM SQL)
            $totalBelanja = $produk[0]->total_belanja ?? 0;

            // Buat PDF invoice
            $pdf = Pdf::loadView('pdf.invoice', [
                'no_faktur' => $p->no_faktur,
                'nama_pembeli' => $produk[0]->nama_pelanggan ?? '-',
                'items' => $produk,
                'total' => $totalBelanja,
                'tanggal' => now()->format('d-M-Y'),
            ]);

            // Persiapkan data untuk email
            $dataAtributPelanggan = [
                'customer_name' => $produk[0]->nama_pelanggan,
                'invoice_number' => $p->no_faktur
            ];

            // Kirim email dengan lampiran invoice
            Mail::to($email)->send(new InvoiceMail($dataAtributPelanggan, $pdf->output()));

            // Tunggu 5 detik sebelum lanjut
            sleep(5);

            // Catat bahwa email sudah terkirim
            Pengirimanemail::create([
                'penjualan_id' => $id,
                'status' => 'sudah terkirim',
                'tgl_pengiriman_pesan' => now(),
            ]);
        }

        return view('autorefresh_email');
    }
}
