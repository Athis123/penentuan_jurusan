@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Finance', 'url' => route('admin.data.finance.index')],
            ['label' => 'Detail Order CS']
        ]
    ])
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Data Order CS {{ $order->kode }}</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Tanggal Order</th>
                    <td>{{ \Carbon\Carbon::parse($order->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Nomor Resi</th>
                    <td>{{ $order->nomor_resi }}</td>
                </tr>
                <tr>
                    <th>Lokasi Gudang</th>
                    <td>{{ $order->lok_gudang }}</td>
                </tr>
                <tr>
                    <th>Nama CS</th>
                    <td>{{ $order->nama_cs }}</td>
                </tr>
                <tr>
                    <th>Advertiser</th>
                    <td>{{ $order->adv ? $order->adv->kode . '.' . $order->adv->deskripsi : '-' }}</td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td>{{ $order->customer }}</td>
                </tr>
                <tr>
                    <th>SKU Produk</th>
                    <td>{{ $order->sku ? $order->sku->kode . ' - ' . $order->sku->deskripsi : '-' }}</td>
                </tr>
                <tr>
                    <th>Produk</th>
                    <td>{{ $order->nama_produk }}</td>
                </tr>
                <tr>
                    <th>Qty</th>
                    <td>{{ $order->qty_produk }}</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>Rp{{ number_format($order->harga_produk, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>No HP</th>
                    <td>{{ $order->no_hp }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $order->alamat }}</td>
                </tr>
                <tr>
                    <th>Provinsi</th>
                    <td>{{ $order->provinsi }}</td>
                </tr>
                <tr>
                    <th>Kabupaten</th>
                    <td>{{ $order->kabupaten }}</td>
                </tr>
                <tr>
                    <th>Kecamatan</th>
                    <td>{{ $order->kecamatan }}</td>
                </tr>
                <tr>
                    <th>Kelurahan</th>
                    <td>{{ $order->kelurahan }}</td>
                </tr>
                <tr>
                    <th>Kode Pos</th>
                    <td>{{ $order->kode_pos }}</td>
                </tr>
                <tr>
                    <th>Kode Promo</th>
                    <td>
                        @if ($order->promo)
                            @if ($order->promo->kode && $order->promo->deskripsi)
                                {{ $order->promo->kode }} {{ $order->promo->deskripsi }}
                            @elseif ($order->promo->kode)
                                {{ $order->promo->kode }}
                            @elseif ($order->promo->deskripsi)
                                {{ $order->promo->deskripsi }}
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Pembayaran</th>
                    <td>{{ $order->pembayaran }}</td>
                </tr>
                <tr>
                    <th>Ongkir</th>
                    <td>Rp{{ number_format($order->ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Diskon Ongkir</th>
                    <td>Rp{{ number_format($order->diskon_ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Admin COD</th>
                    <td>Rp{{ number_format($order->admin_cod, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Diskon Admin COD</th>
                    <td>Rp{{ number_format($order->diskon_admin_cod, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Ekspedisi</th>
                    <td>{{ strtoupper($order->ekpedisi) }}</td>
                </tr>
                <tr>
                    <th>Total Pembayaran</th>
                    <td>Rp{{ number_format($order->total_pembayaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tanggal Tranfer</th>
                    <td>{{ \Carbon\Carbon::parse($order->tanggal_tf)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Bukti Transfer</th>
                    <td>
                        @if ($order->bukti_tf)
                            <a 
                                href="{{ asset('storage/' . $order->bukti_tf) }}" 
                                target="_blank" 
                                class="btn btn-sm btn-info"
                            >
                                <i class="fas fa-eye"></i> Lihat Bukti Transfer
                            </a>
                        @else
                            <span class="text-danger">Tidak ada file</span>
                        @endif
                    </td>
                </tr>
            </table>
            <a href="{{ route('admin.data.finance.index') }}" class="btn btn-secondary mt-2">Kembali</a>
        </div>
    </div>
</div>
@endsection