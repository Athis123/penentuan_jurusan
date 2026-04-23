@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Alternatif', 'url' => route('admin.data.alternatif.index')],
            ['label' => 'Tambah']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.data.alternatif.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>kode</label>
                        <input type="text" name="kode" class="form-control" required placeholder="Masukan kode">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nama Alternatif</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Masukan nama alternatif">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Golongan</label>
                        <select name="golongan" class="form-control" required>
                            <option value="">-- Pilih Golongan --</option>
                            <option value="low">Low / Middle Down</option>
                            <option value="middle">Middle</option>
                            <option value="middle_up">Middle Up</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.data.alternatif.index') }}" class="btn btn-secondary">Batal</a>
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