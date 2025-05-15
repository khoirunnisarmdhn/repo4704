<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// tambahan untuk akses ke model
use App\Models\Pengirimanemail; //untuk akses kelas model Pengirimanemail
use Illuminate\Support\Facades\DB; //untuk menggunakan db
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;

class PengirimanEmailController extends Controller
{
    
    public static function proses_kirim_email_pembayaran(){
        date_default_timezone_set('Asia/Jakarta');
        // 1. Query data penjualan dgn status sudah bayar yang belum dikirim
        $data = DB::table('penjualan')
                ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
                ->join('users', 'pembeli.user_id', '=', 'users.id')
                ->where('status', 'bayar') // hanya ambil penjualan yang sudah bayar
                ->whereNotIn('penjualan.id', function ($query) {
                    $query->select('penjualan_id')
                        ->from('pengirimanemail');
                })
                ->select('penjualan.id','penjualan.no_faktur', 'users.email', 'penjualan.pembeli_id')
                ->get();
        // var_dump($data);
        // 2. Untuk setiap data penjualan, cari item barang detailnya
        // inisialisasi array kosong
        foreach($data as $p){
            $id = $p->id;
            $no_faktur = $p->no_faktur;
            $email = $p->email;
            $pembeli_id = $p->pembeli_id;
            // query data barang detailnya
            $barang = DB::table('penjualan')
                        ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
                        ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.penjualan_id')
                        ->join('barang2', 'penjualan_barang.barang_id', '=', 'barang2.id')
                        ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
                        ->select('penjualan.id','penjualan.no_faktur','pembeli.nama_pembeli', 'penjualan_barang.barang_id', 'barang2.nama_barang','penjualan_barang.harga_jual', 
                                 'barang2.foto',
                                  DB::raw('SUM(penjualan_barang.jml) as total_barang'),
                                  DB::raw('SUM(penjualan_barang.harga_jual * penjualan_barang.jml) as total_belanja'))
                        ->where('penjualan.pembeli_id', '=',$pembeli_id) 
                        ->where('penjualan.id', '=',$id) 
                        ->groupBy('penjualan.id','penjualan.no_faktur','pembeli.nama_pembeli','penjualan_barang.barang_id', 'barang2.nama_barang','penjualan_barang.harga_jual',
                                  'barang2.foto',
                                 )
                        ->get();

            $pdf = Pdf::loadView('pdf.invoice', [
                'no_faktur' => $p->no_faktur,
                'nama_pembeli' => $barang[0]->nama_pembeli ?? '-',
                'items' => $barang,
                'total' => $barang->sum('total_belanja'),
                'tanggal' => now()->format('d-M-Y'),
            ]);

            // data 
            $dataAtributPelanggan = [
                'customer_name' => $barang[0]->nama_pembeli,
                'invoice_number' => $p->no_faktur
            ];

             // Kirim email menggunakan Mailable
             Mail::to($email)->send(new InvoiceMail($dataAtributPelanggan,$pdf->output()));

             // Delay 5 detik sebelum lanjut ke email berikutnya
            sleep(5);

             // Catat pengiriman email
            Pengirimanemail::create([
                'penjualan_id' => $id,
                'status' => 'sudah terkirim',
                'tgl_pengiriman_pesan' => now(),
            ]);

            // echo "<hr>";
            // var_dump($data);
            // echo "<hr>";
            
        }

        // dibungkus autorefresh
        return view('autorefresh_email');
    }
}