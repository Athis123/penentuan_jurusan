@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Finance', 'url' => route('admin.data.finance.index')],
            ['label' => 'Detail Order CRM']
        ]
    ])
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Data Order CRM {{ $repeadorder->kode }}</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Tanggal Order</th>
                    <td>{{ \Carbon\Carbon::parse($repeadorder->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Nomor Resi</th>
                    <td>{{ $repeadorder->nomor_resi }}</td>
                </tr>
                <tr>
                    <th>Lokasi Gudang</th>
                    <td>{{ $repeadorder->lok_gudang }}</td>
                </tr>
                <tr>
                    <th>Nama CRM</th>
                    <td>{{ $repeadorder->nama_crm }}</td>
                </tr>
                <tr>
                    <th>Advertiser</th>
                    <td>{{ $repeadorder->adv ? $repeadorder->adv->kode . '.' . $repeadorder->adv->deskripsi : '-' }}</td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td>{{ $repeadorder->customer }}</td>
                </tr>
                <tr>
                    <th>SKU Produk</th>
                    <td>{{ $repeadorder->sku ? $repeadorder->sku->kode . ' - ' . $repeadorder->sku->deskripsi : '-' }}</td>
                </tr>
                <tr>
                    <th>Produk</th>
                    <td>{{ $repeadorder->nama_produk }}</td>
                </tr>
                <tr>
                    <th>Qty</th>
                    <td>{{ $repeadorder->qty_produk }}</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>Rp{{ number_format($repeadorder->harga_produk, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>No HP</th>
                    <td>{{ $repeadorder->no_hp }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $repeadorder->alamat }}</td>
                </tr>
                <tr>
                    <th>Provinsi</th>
                    <td>{{ $repeadorder->provinsi }}</td>
                </tr>
                <tr>
                    <th>Kabupaten</th>
                    <td>{{ $repeadorder->kabupaten }}</td>
                </tr>
                <tr>
                    <th>Kecamatan</th>
                    <td>{{ $repeadorder->kecamatan }}</td>
                </tr>
                <tr>
                    <th>Kelurahan</th>
                    <td>{{ $repeadorder->kelurahan }}</td>
                </tr>
                <tr>
                    <th>Kode Pos</th>
                    <td>{{ $repeadorder->kode_pos }}</td>
                </tr>
                <tr>
                    <th>Kode Promo</th>
                    <td>
                        @if ($repeadorder->promo)
                            @if ($repeadorder->promo->kode && $repeadorder->promo->deskripsi)
                                {{ $repeadorder->promo->kode }} {{ $repeadorder->promo->deskripsi }}
                            @elseif ($repeadorder->promo->kode)
                                {{ $repeadorder->promo->kode }}
                            @elseif ($repeadorder->promo->deskripsi)
                                {{ $repeadorder->promo->deskripsi }}
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Pembayaran</th>
                    <td>{{ $repeadorder->pembayaran }}</td>
                </tr>
                <tr>
                    <th>Ongkir</th>
                    <td>Rp{{ number_format($repeadorder->ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Diskon Ongkir</th>
                    <td>Rp{{ number_format($repeadorder->diskon_ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Admin COD</th>
                    <td>Rp{{ number_format($repeadorder->admin_cod, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Diskon Admin COD</th>
                    <td>Rp{{ number_format($repeadorder->diskon_admin_cod, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Ekspedisi</th>
                    <td>{{ strtoupper($repeadorder->ekpedisi) }}</td>
                </tr>
                <tr>
                    <th>Total Pembayaran</th>
                    <td>Rp{{ number_format($repeadorder->total_pembayaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tanggal Tranfer</th>
                    <td>{{ \Carbon\Carbon::parse($repeadorder->tanggal_tf)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Bukti Transfer</th>
                    <td>
                        @if ($repeadorder->bukti_tf)
                            <a 
                                href="{{ asset('storage/' . $repeadorder->bukti_tf) }}" 
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