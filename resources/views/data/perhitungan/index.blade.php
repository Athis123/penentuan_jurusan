@extends('layouts.stisla')

@section('title', $title)

@section('content')

    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Perhitungan MOORA']
        ]
    ])

    <div class="section-body">

        {{-- 1. MATRIKS KEPUTUSAN --}}
        <div class="card card-secondary">
            <div class="card-header">
                <h4>Matriks Keputusan</h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            @foreach ($kriterias as $krit)
                                <th>{{ $krit->nama }}<br><small>({{ $krit->kode }})</small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['siswas'] as $alt)
                            <tr>
                                <td>{{ $alt->nama }}</td>
                                @foreach ($kriterias as $krit)
                                    <td class="text-right">
                                        {{ $data['matriks'][$alt->id_siswa][$krit->id_kriteria] ?? 0 }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 2. MATRIKS NORMALISASI --}}
        <div class="card card-primary">
            <div class="card-header">
                <h4>Matriks Normalisasi</h4>
                <small class="text-secondary">Rumus: r*ij = xij / √(Σ xij²)</small>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            @foreach ($kriterias as $krit)
                                <th>{{ $krit->nama }}<br><small>({{ $krit->kode }})</small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['siswas'] as $alt)
                            <tr>
                                <td>{{ $alt->nama }}</td>
                                @foreach ($kriterias as $krit)
                                    <td class="text-right">
                                        {{ number_format($data['normalisasi'][$alt->id_siswa][$krit->id_kriteria] ?? 0, 4) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3. MATRIKS TERBOBOT --}}
        <div class="card card-success">
            <div class="card-header">
                <h4>Matriks Terbobot</h4>
                <small class="text-secondary">Rumus: wij = wj × r*ij</small>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            @foreach ($kriterias as $krit)
                                <th>
                                    {{ $krit->nama }}<br>
                                    <small>({{ $krit->kode }}, bobot={{ $krit->bobot }}, {{ $krit->tipe }})</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['siswas'] as $alt)
                            <tr>
                                <td>{{ $alt->nama }}</td>
                                @foreach ($kriterias as $krit)
                                    <td class="text-right">
                                        {{ number_format($data['terbobot'][$alt->id_siswa][$krit->id_kriteria] ?? 0, 4) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. HASIL AKHIR --}}
        <div class="card card-danger">
            <div class="card-header d-flex justify-content-between">
                <h4>Hasil Akhir</h4>
                <small class="text-secondary">
                    Yi = Σ terbobot(benefit) − Σ terbobot(cost) | Jurusan = skor terbobot tertinggi per kriteria dominan
                </small>
                <a href="{{ route('admin.data.perhitungan.pdf') }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">Rank</th>
                            <th>Nama Siswa</th>
                            <th>Nilai Yi</th>
                            <th>Skor TKJ</th>
                            <th>Skor DKV</th>
                            <th>Skor TG</th>
                            <th>Rekomendasi Jurusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rank = 1; @endphp
                        @foreach ($data['hasil'] as $item)
                            <tr>
                                <td class="text-center">{{ $rank++ }}</td>
                                <td>{{ $item['nama'] }}</td>
                                <td class="text-right">{{ number_format($item['yi'], 4) }}</td>

                                {{-- Skor per jurusan — highlight tertinggi --}}
                                @php
                                    $skorTKJ = $item['skor_jurusan']['Teknik Komputer Jaringan (TKJ)'] ?? 0;
                                    $skorDKV = $item['skor_jurusan']['Desain Komunikasi Visual (DKV)'] ?? 0;
                                    $skorTG = $item['skor_jurusan']['Teknik Grafika (TG)'] ?? 0;
                                    $maxSkor = max($skorTKJ, $skorDKV, $skorTG);
                                @endphp

                                <td class="text-right {{ $skorTKJ == $maxSkor ? 'table-warning font-weight-bold' : '' }}">
                                    {{ number_format($skorTKJ, 4) }}
                                </td>
                                <td class="text-right {{ $skorDKV == $maxSkor ? 'table-warning font-weight-bold' : '' }}">
                                    {{ number_format($skorDKV, 4) }}
                                </td>
                                <td class="text-right {{ $skorTG == $maxSkor ? 'table-warning font-weight-bold' : '' }}">
                                    {{ number_format($skorTG, 4) }}
                                </td>

                                <td>
                                    <span class="badge badge-primary">
                                        {{ $item['jurusan'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
