<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Hasil Perhitungan MOORA</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <h1>Laporan Hasil Perhitungan Metode MOORA</h1>
    <table>
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
                    <td>{{ number_format($nilai, 3) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>