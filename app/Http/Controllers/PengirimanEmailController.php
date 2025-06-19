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

        // 1. Ambil penjualan yang sudah bayar & belum dikirim email
        $data = DB::table('penjualan')
            ->leftJoin('pelanggan', 'penjualan.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            // ->leftJoin('users', 'pelanggan.user_id', '=', 'users.id') // DIHAPUS, email langsung dari pelanggan
            ->where('status', 'bayar')
            ->whereNotIn('penjualan.id', function ($query) {
                $query->select('penjualan_id')->from('pengirimanemail');
            })
            ->select('penjualan.id', 'penjualan.no_faktur', 'pelanggan.email', 'penjualan.id_pelanggan') // PAKAI email dari pelanggan
            ->get();

        if ($data->isEmpty()) {
            \Log::info('Tidak ada data untuk dikirim.');
            return 'Tidak ada email dikirim.';
        }

        foreach ($data as $p) {
            $id = $p->id;
            $email = $p->email;

            // Jika email kosong/null, skip kirim email
            if (empty($email)) {
                \Log::info("Penjualan ID $id: Email kosong, skip kirim email.");
                continue;
            }

            $produk = DB::table('penjualan')
                ->join('detail_penjualan', 'penjualan.id', '=', 'detail_penjualan.id_penjualan')
                ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.id_penjualan')
                ->join('produks', 'detail_penjualan.kode_produk', '=', 'produks.kode_produk')
                ->join('pelanggan', 'penjualan.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->select(
                    'penjualan.id',
                    'penjualan.no_faktur',
                    'pelanggan.nama_pelanggan',
                    'detail_penjualan.kode_produk',
                    'produks.nama_produk',
                    'detail_penjualan.harga_satuan',
                    DB::raw('SUM(detail_penjualan.jumlah) as total_produk'),
                    DB::raw('SUM(detail_penjualan.harga_satuan * detail_penjualan.jumlah) as total_belanja')
                )
                ->where('penjualan.id', $id)
                ->groupBy(
                    'penjualan.id',
                    'penjualan.no_faktur',
                    'pelanggan.nama_pelanggan',
                    'detail_penjualan.kode_produk',
                    'produks.nama_produk',
                    'detail_penjualan.harga_satuan'
                )
                ->get();

            if ($produk->isEmpty()) {
                continue;
            }

            $totalBelanja = $produk[0]->total_belanja ?? 0;

            $pdf = Pdf::loadView('pdf.invoice', [
                'no_faktur' => $p->no_faktur,
                'nama_pembeli' => $produk[0]->nama_pelanggan ?? '-',
                'items' => $produk,
                'total' => $totalBelanja,
                'tanggal' => now()->format('d-M-Y'),
            ]);

            $dataEmail = [
                'customer_name' => $produk[0]->nama_pelanggan,
                'invoice_number' => $p->no_faktur,
            ];

            try {
                Mail::to($email)->send(new InvoiceMail($dataEmail, $pdf->output()));

                Pengirimanemail::create([
                    'penjualan_id' => $id,
                    'status' => 'sudah terkirim',
                    'tgl_pengiriman_pesan' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error("Gagal kirim ke $email: " . $e->getMessage());
                continue;
            }

            sleep(5); // jeda kirim email
        }

        return view('autorefresh_email');
        // return 'Pengiriman email selesai.';
    }
}
