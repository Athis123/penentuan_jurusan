@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Pegawal']
        ]
    ])

<div class="section-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <a href="{{ route('admin.personil.user.create') }}" class="btn btn-primary mb-2">
            <i class="fas fa-plus"></i> Tambah User
        </a>
        <div class="d-flex flex-wrap">
            <div class="form-inline mr-3 mb-2">
                <label for="filter-role" class="mr-2"><b>Role</b></label>
                <select id="filter-role" class="form-control" style="min-width: 180px;">
                    <option value="">Semua Role</option>
                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="table" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Role</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No Hp</th>
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
    let table = $('#table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.personil.user.index') }}',
            data: function (d) {
                d.role = $('#filter-role').val();
                d.tim = $('#tim').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'role', name: 'role', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'username', name: 'username' },
            { data: 'email', name: 'email' },
            { data: 'no_hp', name: 'no_hp' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ]
    });
    $('#filter-role').on('change', function () {
        table.ajax.reload();
    });
    $('#tim').on('change', function () {
        table.ajax.reload();
    });
});
</script>
@endpush
