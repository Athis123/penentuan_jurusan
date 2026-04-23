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

    @foreach ($perhitungan as $golongan => $data)

        {{-- ===============================
            MATRKS NORMALISASI
        =============================== --}}
        <div class="card card-primary">
            <div class="card-header">
                <h4>
                    Golongan {{ ucwords(str_replace('_', ' ', $golongan)) }} – Matriks Normalisasi
                </h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Alternatif</th>
                            @foreach ($kriterias as $krit)
                                <th>{{ $krit->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['alternatifs'] as $alt)
                            <tr>
                                <td>{{ $alt->nama }}</td>
                                @foreach ($kriterias as $krit)
                                    <td>
                                        {{ number_format(
                                            $data['normalisasi'][$alt->id_alternatif][$krit->id_kriteria] ?? 0,
                                            4
                                        ) }}
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
                    Golongan {{ ucwords(str_replace('_', ' ', $golongan)) }} – Matriks Terbobot
                </h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Alternatif</th>
                            @foreach ($kriterias as $krit)
                                <th>{{ $krit->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['alternatifs'] as $alt)
                            <tr>
                                <td>{{ $alt->nama }}</td>
                                @foreach ($kriterias as $krit)
                                    <td>
                                        {{ number_format(
                                            $data['terbobot'][$alt->id_alternatif][$krit->id_kriteria] ?? 0,
                                            4
                                        ) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===============================
            HASIL AKHIR (Yi & RANKING)
        =============================== --}}
        <div class="card card-danger">
            <div class="card-header">
                <h4>
                    Golongan {{ ucwords(str_replace('_', ' ', $golongan)) }} – Hasil Akhir
                </h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">Rank</th>
                            <th>Alternatif</th>
                            <th width="20%">Yi</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endforeach

</div>
@endsection
