@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Alternatif', 'url' => route('admin.data.alternatif.index')],
            ['label' => 'Edit']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.data.alternatif.update', $alternatif->id_alternatif) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Kode</label>
                        <input type="text" name="kode" class="form-control" 
                            value="{{ old('kode', $alternatif->kode) }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nama Alternatif</label>
                        <input type="text" name="nama" class="form-control" 
                            value="{{ old('nama', $alternatif->nama) }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Golongan</label>
                        <select name="golongan" class="form-control" required>
                            <option value="">-- Pilih Golongan --</option>
                            <option value="low" {{ old('golongan', $alternatif->golongan) == 'low' ? 'selected' : '' }}>Low / Middle Down</option>
                            <option value="middle" {{ old('golongan', $alternatif->golongan) == 'middle' ? 'selected' : '' }}>Middle</option>
                            <option value="middle_up" {{ old('golongan', $alternatif->golongan) == 'middle_up' ? 'selected' : '' }}>Middle Up</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.data.alternatif.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">

</script>
@endpush