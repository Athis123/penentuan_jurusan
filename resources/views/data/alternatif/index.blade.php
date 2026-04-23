@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Alternatif']
        ]
    ])

<div class="section-body">
    <div class="card card-primary">
        <div class="card-header">
            <div class="col-md-auto mb-2">
                <a href="{{ route('admin.data.alternatif.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Golongan</th>
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

        // // Apply date
        // $('#filterTanggal').on('apply.daterangepicker', function (ev, picker) {
        //     $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        //     table.ajax.reload();
        // });

        // // Reset date
        // $('#filterTanggal').on('cancel.daterangepicker', function (ev, picker) {
        //     $(this).val('');
        //     table.ajax.reload();
        // });

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
                url: '{{ route('admin.data.alternatif.index') }}',
                data: function (d) {
                    d.daterange = $('#filterTanggal').val();
                }
            },
            columns: [
                { data: 'kode', name: 'kode' },
                { data: 'nama', name: 'nama'},
                { data: 'golongan', name: 'golongan'},
                { data: 'aksi', name: 'aksi', className: 'text-center', orderable: false, searchable: false },
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
</script>
@endpush
