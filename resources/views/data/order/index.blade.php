@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Order CS']
        ]
    ])

<div class="section-body">
    <div class="row mb-3 align-items-center">
        <div class="col-md-auto mb-2">
            @php
                $user = auth()->user();
            @endphp
            @if ($user->hasRole('admin') || ($user->tim && strtolower($user->tim) === 'cs'))
            <a href="{{ route('admin.data.order.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
            @endif
        </div>
        {{-- <div class="col-md-auto mb-2">
            <a href="{{ route('admin.data.order.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div> --}}
        <label for="filterTanggal" class="mb-1"><b>Filter Tanggal</b></label>
        <div class="col-md-auto mb-2">
            <div class="input-group" style="box-shadow: 0 1px 1px rgb(0 0 0 / 42%);">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="filterTanggal" class="form-control" placeholder="Pilih rentang tanggal">
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Order</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga Produk</th>
                            <th>Ongkir</th>
                            <th>Diskon Ongkir</th>
                            <th>Biaya Admin</th>
                            <th>Diskon Biaya Admin</th>
                            <th>Total Harga</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        // Inisialisasi Daterangepicker
        $('#filterTanggal').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD-MM-YYYY'
            }
        });

        // Apply date
        $('#filterTanggal').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            table.ajax.reload();
        });

        // Reset date
        $('#filterTanggal').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            table.ajax.reload();
        });

        // Inisialisasi DataTables
        table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            order: [[0, 'desc']],
            ajax: {
                url: '{{ route('admin.data.order.index') }}',
                data: function (d) {
                    d.daterange = $('#filterTanggal').val();
                }
            },
            columns: [
                { data: 'tanggal', name: 'tanggal', className: 'text-nowrap', orderable: false, searchable: false },
                { data: 'kode', name: 'kode', className: 'text-nowrap'},
                { data: 'customer', name: 'customer'},
                { data: 'nama_produk', name: 'nama_produk' },
                { data: 'qty_produk', name: 'qty_produk' },
                { data: 'harga_produk', name: 'harga_produk' },
                { data: 'ongkir', name: 'ongkir' },
                { data: 'diskon_ongkir', name: 'diskon_ongkir' },
                { data: 'admin_cod', name: 'admin_cod' },
                { data: 'diskon_admin_cod', name: 'diskon_admin_cod' },
                { data: 'total_pembayaran', name: 'total_pembayaran' },
                { data: 'pembayaran', name: 'pembayaran' },
                { data: 'status_approval', name: 'status_approval', className: 'text-center' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ]
        });
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let btn = this;
        Swal.fire({
            title: 'Yakin ingin menghapus data ini?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteData(btn);
            }
        });
    });

    function deleteData(button) {
        const url = $(button).data('url');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    $('#table').DataTable().ajax.reload();

                    Swal.fire(
                        'Berhasil!',
                        'Data berhasil dihapus.',
                        'success'
                    );
                } else {
                    Swal.fire('Gagal', 'Gagal menghapus data.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    }

        $(document).on('submit', '.form-approve', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Approve Order?',
            text: 'Yakin ingin menyetujui order ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Approve',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $(document).on('submit', '.form-unapprove', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Batal Approve?',
            text: 'Yakin ingin membatalkan approval order ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

</script>
@endpush
