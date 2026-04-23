@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Master Kode Promo', 'url' => route('admin.master.promo.index')],
            ['label' => 'Edit']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.master.promo.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Kode</label>
                        <input type="text" name="kode" class="form-control border border-dark" 
                            value="{{ old('kode', $promo->kode) }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Deskripsi</label>
                        <input type="text" name="deskripsi" class="form-control border border-dark" 
                            value="{{ old('deskripsi', $promo->deskripsi) }}">
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.master.promo.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function () {

    });
</script>
@endpush