<table>
    <thead>
        <tr>
            <th>Tanggal Order</th>
            <th>Customer</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Produk</th>
            <th>Jenis Pembayaran</th>
            <th>Ongkir</th>
            <th>Diskon Ongkir</th>
            <th>Biaya Admin</th>
            <th>Diskon Biaya Admin</th>
            <th>Total Pembayaran</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->customer }}</td>
            <td>{{ $item->no_hp }}</td>
            <td>{{ $item->alamat }}</td>
            <td>{{ $item->nama_produk }}</td>
            <td>{{ $item->qty_produk }}</td>
            <td>{{ number_format($item->harga_produk, 0, ',', '.') }}</td>
            <td>{{ $item->pembayaran }}</td>
            <td>{{ number_format($item->ongkir, 0, ',', '.') }}</td>
            <td>{{ number_format($item->diskon_ongkir, 0, ',', '.') }}</td>
            <td>{{ number_format($item->admin_cod, 0, ',', '.') }}</td>
            <td>{{ number_format($item->diskon_admin_cod, 0, ',', '.') }}</td>
            <td>{{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
            <td>{{ $item->status_approval }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
