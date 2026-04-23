@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'User', 'url' => route('admin.personil.user.index')],
            ['label' => 'Tambah']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.personil.user.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="form-group col-md-4">
                        <label>Role</label>
                        <select name="role" class="form-control border border-dark" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $id => $name)
                                <option value="{{ $id }}">{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control border border-dark" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control border border-dark" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>No Hp</label>
                        <input type="text" name="no_hp" class="form-control border border-dark" required>
                    </div>

                     <div class="form-group col-md-4">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control border border-dark" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control border border-dark">
                            <div class="input-group-append">
                                <span class="input-group-text border border-dark" id="togglePassword" style="cursor: pointer;">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control border border-dark" rows="2"></textarea>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control-file border border-dark">
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.personil.user.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function () {
        $('#togglePassword').on('click', function () {
            const passwordField = $('#password');
            const icon = $(this).find('i');

            // Toggle type
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });

    });
</script>
@endpush