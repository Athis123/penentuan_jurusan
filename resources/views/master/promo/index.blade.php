@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Master Kode Promo']
        ]
    ])

<div class="section-body">
    <a href="{{ route('admin.master.promo.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah Data</a>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="table" width="100%">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

$(function() {
    $('#table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.master.promo.index') }}',
        columns: [
            { data: 'kode', name: 'kode', orderable: false, searchable: false },
            { data: 'deskripsi', name: 'deskripsi'},
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ]
    });
});
</script>
@endpush
