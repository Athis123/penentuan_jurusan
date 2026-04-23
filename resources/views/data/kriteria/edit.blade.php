@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Kriteria', 'url' => route('admin.data.kriteria.index')],
            ['label' => 'Edit']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.data.kriteria.update', $kriteria->id_kriteria) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Kode</label>
                        <input type="text" name="kode" class="form-control" 
                            value="{{ old('kode', $kriteria->kode) }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" 
                            value="{{ old('nama', $kriteria->nama) }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Bobot</label>
                        <input type="number" name="bobot" class="form-control" step="0.01" min="0" max="1" 
                            value="{{ old('bobot', $kriteria->bobot) }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tipe</label>
                        <select name="tipe" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="benefit" {{ old('tipe', $kriteria->tipe) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                            <option value="cost" {{ old('tipe', $kriteria->tipe) == 'cost' ? 'selected' : '' }}>Cost</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.data.kriteria.index') }}" class="btn btn-secondary">Batal</a>
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