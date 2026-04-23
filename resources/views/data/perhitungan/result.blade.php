@extends('layouts.stisla')

@section('title', $title)

@section('content')
@include('components.breadcrumbs', [
    'title' => $title,
    'breadcrumbs' => [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
        ['label' => 'Hasil Perhitungan']
    ]
])

<div class="section-body">
        <div class="card-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Hasil Akhir dan Perangkingan</h4>
                     <a href="{{route('admin.data.perhitungan.pdf')}}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-hasil">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Alternatif</th>
                                    <th>Nilai Akhir (Yi)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $peringkat = 1; @endphp
                                @foreach ($data['hasil'] as $id_alternatif => $nilai)
                                    <tr>
                                        <td>{{ $peringkat++ }}</td>
                                        <td>{{ $data['alternatifs']->firstWhere('id_alternatif', $id_alternatif)->nama }}</td>
                                        <td>{{ number_format($nilai, 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#table-hasil').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true
    });
});
</script>
@endpush