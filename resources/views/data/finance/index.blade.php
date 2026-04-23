@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Finance']
        ]
    ])

<div class="section-body">
    <div class="row mb-3 align-items-center">
        <label for="filterTanggal" class="mb-1"><b>Filter Tanggal</b></label>
        <div class="col-md-auto mb-2">
            <div class="input-group" style="box-shadow: 0 1px 1px rgb(0 0 0 / 42%);">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="filterTanggal" class="form-control" placeholder="Pilih rentang tanggal">
            </div>
        </div>
        <label for="filterStatus"><b>Status</b></label>
        <div class="col-md-auto mb-2">
            <select id="filterStatus" class="form-control" style="box-shadow: 0 1px 1px rgb(0 0 0 / 42%);">
                <option value="">-- Semua --</option>
                <option value="Selesai Dicek">Selesai Dicek</option>
                <option value="Pending">Pending</option>
            </select>
        </div>

        <label for="filterPembayaran"><b>Pembayaran</b></label>
        <div class="col-md-auto mb-2">
            <select id="filterPembayaran" class="form-control" style="box-shadow: 0 1px 1px rgb(0 0 0 / 42%);">
                <option value="">-- Semua --</option>
                <option value="transfer">Transfer</option>
                <option value="cod">COD</option>
            </select>
        </div>
        <div class="col-md-auto mb-2">
            <div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item export-excel" data-type="all">All Data</a>
                    <a href="#" class="dropdown-item export-excel" data-type="cs">Data CS</a>
                    <a href="#" class="dropdown-item export-excel" data-type="crm">Data CRM</a>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header"><h4>Data Order CS</h4></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-cs" width="100%">
                    <thead>
                        <tr>
                            <th>Tanggal Order</th>
                            <th>Kode Order</th>
                            <th>Customer</th>
                            <th>No. Hp</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga Produk</th>
                            <th>Ongkir</th>
                            <th>Diskon Ongkir</th>
                            <th>Biaya Admin</th>
                            <th>Diskon Biaya Admin</th>
                            <th>Pembayaran</th>
                            <th>Bukti Transfer</th>
                            <th>No. Resi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h4>Data Order CRM</h4></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-crm" width="100%">
                    <thead>
                        <tr>
                            <th>Tanggal Order</th>
                            <th>Kode Order</th>
                            <th>Customer</th>
                            <th>No. Hp</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga Produk</th>
                            <th>Ongkir</th>
                            <th>Diskon Ongkir</th>
                            <th>Biaya Admin</th>
                            <th>Diskon Biaya Admin</th>
                            <th>Pembayaran</th>
                            <th>Bukti Transfer</th>
                            <th>No. Resi</th>
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
    let tableCS, tableCRM;

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
            tableCS.ajax.reload();
            tableCRM.ajax.reload();
        });

        // Reset date
        $('#filterTanggal').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            tableCS.ajax.reload();
            tableCRM.ajax.reload();
        });

                // Status Approval
        $('#filterStatus').on('change', function () {
            tableCS.ajax.reload();
            tableCRM.ajax.reload();
        });

        // Pembayaran
        $('#filterPembayaran').on('change', function () {
            tableCS.ajax.reload();
            tableCRM.ajax.reload();
        });


        // Inisialisasi DataTables
        tableCS = $('#table-cs').DataTable({
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
                url: '{{ route('admin.data.finance.index') }}',
                data: function (d) {
                    d.daterange = $('#filterTanggal').val();
                    d.status_approval = $('#filterStatus').val();
                    d.pembayaran = $('#filterPembayaran').val();
                }
            },
            columns: [
                { data: 'tanggal', name: 'tanggal', className: 'text-center'},
                { data: 'kode', name: 'kode', className: 'text-center'},
                { data: 'customer', name: 'customer'},
                { data: 'no_hp', name: 'no_hp', className: 'text-nowrap'},
                { data: 'nama_produk', name: 'nama_produk' },
                { data: 'qty_produk', name: 'qty_produk' },
                { data: 'harga_produk', name: 'harga_produk' },
                { data: 'ongkir', name: 'ongkir' },
                { data: 'diskon_ongkir', name: 'diskon_ongkir' },
                { data: 'admin_cod', name: 'admin_cod' },
                { data: 'diskon_admin_cod', name: 'diskon_admin_cod' },
                { data: 'pembayaran', name: 'pembayaran' },
                { data: 'bukti_tf', name: 'bukti_tf' },
                { data: 'nomor_resi', name: 'nomor_resi' },
                { data: 'status_approval_id', name: 'status_approval_id', className: 'text-center' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ]
        });

        // Inisialisasi DataTables CRM
        tableCRM = $('#table-crm').DataTable({
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
                url: '{{ route('admin.data.finance.index') }}',
                data: function (d) {
                    d.daterange = $('#filterTanggal').val();
                    d.type = 'crm';
                    d.status_approval = $('#filterStatus').val();
                    d.pembayaran = $('#filterPembayaran').val();
                }
            },
            columns: [
                { data: 'tanggal', name: 'tanggal', className: 'text-center'},
                { data: 'kode', name: 'kode', className: 'text-center'},
                { data: 'customer', name: 'customer'},
                { data: 'no_hp', name: 'no_hp', className: 'text-nowrap'},
                { data: 'nama_produk', name: 'nama_produk' },
                { data: 'qty_produk', name: 'qty_produk' },
                { data: 'harga_produk', name: 'harga_produk' },
                { data: 'ongkir', name: 'ongkir' },
                { data: 'diskon_ongkir', name: 'diskon_ongkir' },
                { data: 'admin_cod', name: 'admin_cod' },
                { data: 'diskon_admin_cod', name: 'diskon_admin_cod' },
                { data: 'pembayaran', name: 'pembayaran' },
                { data: 'bukti_tf', name: 'bukti_tf' },
                { data: 'nomor_resi', name: 'nomor_resi' },
                { data: 'status_approval_id', name: 'status_approval_id', className: 'text-center' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ]
        });
    });

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

    $(document).on('click', '.export-excel', function (e) {
        e.preventDefault();
        const type = $(this).data('type');
        const daterange = $('#filterTanggal').val();
        const statusApproval = $('#filterStatus').val();
        const pembayaran = $('#filterPembayaran').val();

        let url = '{{ route('admin.data.finance.export') }}' + '?type=' + type;
        if (daterange) {
            url += '&daterange=' + encodeURIComponent(daterange);
        }
        if (statusApproval) {
            url += '&status_approval=' + encodeURIComponent(statusApproval);
        }

        if (pembayaran) {
            url += '&pembayaran=' + encodeURIComponent(pembayaran);
        }

        window.location.href = url;
    });

</script>
@endpush
