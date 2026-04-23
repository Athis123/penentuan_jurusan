@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Siswa', 'url' => route('admin.data.siswa.index')],
            ['label' => 'Tambah']
        ]
    ])

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.data.siswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>NISN</label>
                            <input type="text" name="nisn" class="form-control" required placeholder="Masukan NISN">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Masukan nama">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" placeholder="Masukan alamat"></textarea>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>No Hp</label>
                            <input type="text" name="no_hp" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tmp_lahir" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('admin.data.siswa.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(function() {

        });
    </script>
@endpush
