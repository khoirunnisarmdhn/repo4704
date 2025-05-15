@if($penjualans->isEmpty())
    <div class="text-gray-500">Tidak ada detail penjualan ditemukan.</div>
@else
    @php
        $detailList = $penjualans->first()->detailPenjualan;
        $totalHarga = $detailList->sum(fn($item) => $item->jumlah * $item->harga_satuan);
    @endphp

    <table class="w-full text-sm text-left border">
        <thead>
            <tr class="bg-gray-100 text-xs uppercase text-gray-700">
                <th class="px-4 py-2">Kode Produk</th>
                <th class="px-4 py-2">Nama Produk</th>
                <th class="px-4 py-2">Jumlah</th>
                <th class="px-4 py-2">Harga</th>
                <th class="px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detailList as $detail)
                @php
                    $product = \App\Models\Produk::where('kode_produk', $detail->kode_produk)->first();
                @endphp
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $product->kode_produk }}</td>
                    <td class="px-4 py-2">{{ $product->nama_produk }}</td>
                    <td class="px-4 py-2">{{ $detail->jumlah }}</td>
                    <td class="px-4 py-2">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ number_format($detail->harga_satuan * $detail->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-semibold">
                <td colspan="4"></td>
                <td class="px-4 py-2">
                    {{ number_format($totalHarga, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
@endif