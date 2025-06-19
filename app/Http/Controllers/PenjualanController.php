<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pengirimanemail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf; // pastikan sudah install barryvdh/laravel-dompdf

class PenjualanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Simpan data penjualan
        $penjualan = Penjualan::create($request->all());

        // Contoh dummy data pelanggan dan produk (ganti sesuai kebutuhan nyata)
        $pelanggan = [
            'nama_pelanggan' => 'Rizal',
            'email' => 'tes@mailtrap.io',
        ];
        $produk = [
            ['nama_produk' => 'Produk A', 'qty' => 1, 'harga' => 10000],
            ['nama_produk' => 'Produk B', 'qty' => 2, 'harga' => 20000],
        ];

        // 2. Jika status bayar, kirim invoice
        if ($penjualan->status == 'bayar') {
            $total = collect($produk)->sum(function ($item) {
                return $item['qty'] * $item['harga'];
            });
            $pdf = Pdf::loadView('pdf.invoice', [
                'no_faktur' => $penjualan->no_faktur ?? 'INV-001',
                'nama_pembeli' => $pelanggan['nama_pelanggan'],
                'items' => $produk,
                'total' => $total,
                'tanggal' => now()->format('d-M-Y'),
            ]);

            $dataAtributPelanggan = [
                'customer_name' => $pelanggan['nama_pelanggan'],
                'invoice_number' => $penjualan->no_faktur ?? 'INV-001'
            ];

            Mail::to($pelanggan['email'])->send(new InvoiceMail($dataAtributPelanggan, $pdf->output()));

            // Simpan ke pengirimanemail
            Pengirimanemail::create([
                'penjualan_id' => $penjualan->id,
                'status' => 'sudah terkirim',
                'tgl_pengiriman_pesan' => now(),
            ]);
        }

        return response()->json($penjualan, 201);
    }
}

