<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Perhitungan MOORA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            color: #1a1a2e;
            background: #fff;
        }

        /* ===== HEADER / KOP ===== */
        .kop {
            text-align: center;
            border-bottom: 3px solid #1a1a2e;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        .kop h1 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1a1a2e;
        }

        .kop p {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 9px;
            font-weight: 700;
            color: #fff;
            padding: 5px 8px;
            margin-bottom: 0;
            border-radius: 3px 3px 0 0;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        .bg-primary {
            background-color: #007bff;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .section-subtitle {
            font-size: 7px;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 2px;
        }

        /* ===== CARD WRAPPER ===== */
        .card {
            margin-bottom: 14px;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            overflow: hidden;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }

        thead tr th {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 4px 5px;
            text-align: center;
            font-weight: 700;
            color: #1a1a2e;
        }

        tbody tr td {
            border: 1px solid #ddd;
            padding: 3px 5px;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .highlight {
            background-color: #fff3cd !important;
            font-weight: 700;
        }

        /* ===== BADGE ===== */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: 700;
            color: #fff;
            background-color: #007bff;
        }

        /* ===== RANK ===== */
        .rank-cell {
            text-align: center;
            font-weight: 700;
        }

        .rank-1 {
            color: #c7a100;
        }

        .rank-2 {
            color: #888;
        }

        .rank-3 {
            color: #a0522d;
        }

        /* ===== PAGE BREAK ===== */
        .page-break {
            page-break-after: always;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            font-size: 7px;
            color: #888;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            text-align: left;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- ===== KOP SURAT ===== --}}
    <div class="kop">
        <h1>Hasil Perhitungan MOORA</h1>
        <p>Sistem Penentuan Jurusan Siswa &mdash; Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    {{-- ===== 1. MATRIKS KEPUTUSAN ===== --}}
    <div class="card">
        <div class="section-title bg-secondary">
            1. Matriks Keputusan
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    @foreach ($kriterias as $krit)
                        <th>{{ $krit->nama }}<br>({{ $krit->kode }})</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['siswas'] as $i => $alt)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
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

    {{-- ===== 2. MATRIKS NORMALISASI ===== --}}
    <div class="card">
        <div class="section-title bg-primary">
            2. Matriks Normalisasi
            <span class="section-subtitle">&nbsp;| Rumus: r*ij = xij / &#8730;(&Sigma; xij&sup2;)</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    @foreach ($kriterias as $krit)
                        <th>{{ $krit->nama }}<br>({{ $krit->kode }})</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['siswas'] as $i => $alt)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
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

    {{-- ===== 3. MATRIKS TERBOBOT ===== --}}
    <div class="card">
        <div class="section-title bg-success">
            3. Matriks Terbobot
            <span class="section-subtitle">&nbsp;| Rumus: wij = wj &times; r*ij</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    @foreach ($kriterias as $krit)
                        <th>
                            {{ $krit->nama }}<br>
                            ({{ $krit->kode }}, bobot={{ $krit->bobot }}, {{ $krit->tipe }})
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['siswas'] as $i => $alt)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
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

    {{-- ===== 4. HASIL AKHIR ===== --}}
    <div class="card">
        <div class="section-title bg-danger">
            4. Hasil Akhir &amp; Rekomendasi Jurusan
            <span class="section-subtitle">&nbsp;| Yi = &Sigma; terbobot(benefit) &minus; &Sigma; terbobot(cost)</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th width="4%">Rank</th>
                    <th>Nama Siswa</th>
                    <th>Nilai Yi</th>
                    <th>Skor TKJ</th>
                    <th>Skor DKV</th>
                    <th>Skor TG</th>
                    <th>Rekomendasi Jurusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['hasil'] as $index => $item)
                    @php
                        $rank = $index + 1;
                        $skorTKJ = $item['skor_jurusan']['Teknik Komputer Jaringan (TKJ)'] ?? 0;
                        $skorDKV = $item['skor_jurusan']['Desain Komunikasi Visual (DKV)'] ?? 0;
                        $skorTG = $item['skor_jurusan']['Teknik Grafika (TG)'] ?? 0;
                        $maxSkor = max($skorTKJ, $skorDKV, $skorTG);

                        $rankClass = match ($rank) {
                            1 => 'rank-1',
                            2 => 'rank-2',
                            3 => 'rank-3',
                            default => ''
                        };
                    @endphp
                    <tr>
                        <td class="rank-cell {{ $rankClass }}">
                            @if ($rank === 1)
                                🥇
                            @elseif ($rank === 2)
                                🥈
                            @elseif ($rank === 3)
                                🥉
                            @else
                                {{ $rank }}
                            @endif
                        </td>
                        <td>{{ $item['nama'] }}</td>
                        <td class="text-right">{{ number_format($item['yi'], 4) }}</td>

                        <td class="text-right {{ $skorTKJ == $maxSkor ? 'highlight' : '' }}">
                            {{ number_format($skorTKJ, 4) }}
                        </td>
                        <td class="text-right {{ $skorDKV == $maxSkor ? 'highlight' : '' }}">
                            {{ number_format($skorDKV, 4) }}
                        </td>
                        <td class="text-right {{ $skorTG == $maxSkor ? 'highlight' : '' }}">
                            {{ number_format($skorTG, 4) }}
                        </td>

                        <td class="text-center">
                            <span class="badge">{{ $item['jurusan'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        <div class="footer-left">Sistem Penentuan Jurusan &mdash; Metode MOORA</div>
        <div class="footer-right">Dicetak otomatis oleh sistem &mdash; {{ now()->translatedFormat('d F Y') }}</div>
    </div>

</body>

</html>
