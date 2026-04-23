@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Master Status Approval', 'url' => route('admin.master.status.index')],
            ['label' => 'Edit']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.master.status.update', $status->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <input type="text" name="status" class="form-control border border-dark" 
                            value="{{ old('status', $status->status) }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control border border-dark" 
                            value="{{ old('keterangan', $status->keterangan) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Warna</label>
                        <input type="color" name="color" class="form-control border border-dark" 
                            value="{{ old('color', $status->color) }}">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.master.status.index') }}" class="btn btn-secondary">Batal</a>
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