@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Kriteria', 'url' => route('admin.data.kriteria.index')],
            ['label' => 'Tambah']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.data.kriteria.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>kode</label>
                        <input type="text" name="kode" class="form-control" required placeholder="Masukan kode">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Masukan nama kriteria">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Bobot</label>
                        <input type="number" name="bobot" class="form-control" step="0.01" min="0" max="1" required placeholder="Masukan bobot">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tipe</label>
                        <select name="tipe" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.data.kriteria.index') }}" class="btn btn-secondary">Batal</a>
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
    $(function () {

    });
</script>
@endpush