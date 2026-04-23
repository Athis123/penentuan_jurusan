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
        {{-- ===============================
            MATRKS NORMALISASI
        =============================== --}}
        <div class="card card-primary">
            <div class="card-header">
                <h4>
                    Matriks Normalisasi
                </h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            @foreach ($kriterias as $krit)
                                <th>{{ $krit->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['siswas'] as $alt)
                            <tr>
                                <td>{{ $alt->nama }}</td>
                                @foreach ($kriterias as $krit)
                                    <td>
                                        {{ number_format($data['normalisasi'][$alt->id_siswa][$krit->id_kriteria] ?? 0, 4) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===============================
            MATRKS TERBOBOT
        =============================== --}}
        <div class="card card-success">
            <div class="card-header">
                <h4>
                    Matriks Terbobot
                </h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            @foreach ($kriterias as $krit)
                                <th>{{ $krit->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['siswas'] as $alt)
                            <tr>
                                <td>{{ $alt->nama }}</td>
                                @foreach ($kriterias as $krit)
                                    <td>
                                        {{ number_format($data['terbobot'][$alt->id_siswa][$krit->id_kriteria] ?? 0, 4) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===============================
    HASIL AKHIR
=============================== --}}
        <div class="card card-danger">
            <div class="card-header">
                <h4>Hasil Akhir</h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">Rank</th>
                            <th>Nama Siswa</th>
                            <th>Yi</th>
                            <th>Rekomendasi Jurusan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $rank = 1; @endphp
                        @foreach ($data['hasil'] as $item)
                            <tr>
                                <td class="text-center">{{ $rank++ }}</td>
                                <td>{{ $item['nama'] }}</td>
                                <td class="text-right">
                                    {{ number_format($item['yi'], 4) }}
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
