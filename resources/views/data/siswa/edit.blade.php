@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Siswa', 'url' => route('admin.data.siswa.index')],
            ['label' => 'Edit']
        ]
    ])

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.data.siswa.update', $siswa->id_siswa) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="form-group col-md-4">
                            <label>NISN</label>
                            <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}"
                                required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Nama Siswa</label>
                            <input type="text" name="nama" class="form-control"
                                value="{{ old('nama', $siswa->nama) }}" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control">{{ old('alamat', $siswa->alamat) }}</textarea>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="laki-laki"
                                    {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="perempuan"
                                    {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="{{ old('no_hp', $siswa->no_hp) }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tmp_lahir" class="form-control"
                                value="{{ old('tmp_lahir', $siswa->tmp_lahir) }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control"
                                value="{{ old('tgl_lahir', $siswa->tgl_lahir) }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="form-control"
                                value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">

                            @if ($siswa->foto)
                                <small class="text-muted">Foto saat ini:</small><br>
                                <img src="{{ asset('storage/' . $siswa->foto) }}" width="80" class="mt-2">
                            @endif
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.data.siswa.index') }}" class="btn btn-secondary">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
