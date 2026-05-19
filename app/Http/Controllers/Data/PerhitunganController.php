<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Data\DataSiswa;
use App\Models\Data\Kriteria;

class PerhitunganController extends Controller
{
    public function index()
    {
        $title = 'Perhitungan MOORA Penentuan Jurusan';

        // Ambil data
        $kriterias = Kriteria::orderBy('id_kriteria')->get();
        $siswas = DataSiswa::with('penilaians')
            ->orderBy('nama')
            ->get();

        // 1. MATRKS KEPUTUSAN
        $matriks = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $nilai = $siswa->penilaians
                    ->firstWhere('id_kriteria', $krit->id_kriteria)
                    ->nilai ?? 0;

                $matriks[$siswa->id_siswa][$krit->id_kriteria] = $nilai;
            }
        }

        // 2. PEMBAGI
        $pembagi = [];

        foreach ($kriterias as $krit) {
            $total = 0;

            foreach ($siswas as $siswa) {
                $nilai = $matriks[$siswa->id_siswa][$krit->id_kriteria];
                $total += pow($nilai, 2);
            }

            $pembagi[$krit->id_kriteria] = sqrt($total);
        }

        // 3. NORMALISASI
        $normalisasi = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $nilai = $matriks[$siswa->id_siswa][$krit->id_kriteria];
                $pemb = $pembagi[$krit->id_kriteria];

                $normalisasi[$siswa->id_siswa][$krit->id_kriteria] =
                    $pemb != 0 ? ($nilai / $pemb) : 0;
            }
        }

        // 4. TERBOBOT
        $terbobot = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $terbobot[$siswa->id_siswa][$krit->id_kriteria] =
                    $normalisasi[$siswa->id_siswa][$krit->id_kriteria] * $krit->bobot;
            }
        }

        // Taruh SEBELUM foreach siswa
        $idMtk  = $kriterias->firstWhere('kode', 'C1')->id_kriteria ?? 1;
        $idIpa  = $kriterias->firstWhere('kode', 'C2')->id_kriteria ?? 2;
        $idSeni = $kriterias->firstWhere('kode', 'C3')->id_kriteria ?? 3;
        $idTik  = $kriterias->firstWhere('kode', 'C4')->id_kriteria ?? 4;
        $idIndo = $kriterias->firstWhere('kode', 'C5')->id_kriteria ?? 5;

        // 5. NILAI Yi + JURUSAN
        $hasil = [];

        foreach ($siswas as $siswa) {
            $benefit = 0;
            $cost    = 0;

            foreach ($kriterias as $krit) {
                $nilai = $terbobot[$siswa->id_siswa][$krit->id_kriteria];
                if ($krit->tipe == 'benefit') {
                    $benefit += $nilai;
                } else {
                    $cost += $nilai;
                }
            }

            $yi = $benefit - $cost;

            // Ambil nilai asli per kriteria
            $nilaiMtk  = $matriks[$siswa->id_siswa][$idMtk]  ?? 0;
            $nilaiIpa  = $matriks[$siswa->id_siswa][$idIpa]   ?? 0;
            $nilaiSeni = $matriks[$siswa->id_siswa][$idSeni]  ?? 0;
            $nilaiTik  = $matriks[$siswa->id_siswa][$idTik]   ?? 0;
            $nilaiIndo = $matriks[$siswa->id_siswa][$idIndo]  ?? 0;

            // Skor per jurusan
            $skorTKJ = ($nilaiMtk * 0.4)  + ($nilaiTik  * 0.6);
            $skorDKV = ($nilaiSeni * 0.5) + ($nilaiTik  * 0.3) + ($nilaiIndo * 0.2);
            $skorTG  = ($nilaiSeni * 0.6) + ($nilaiIpa  * 0.2) + ($nilaiIndo * 0.2);

            $skorMax = max($skorTKJ, $skorDKV, $skorTG);

            if ($skorMax == $skorTKJ) {
                $jurusan = 'Teknik Komputer Jaringan (TKJ)';
            } elseif ($skorMax == $skorDKV) {
                $jurusan = 'Desain Komunikasi Visual (DKV)';
            } else {
                $jurusan = 'Teknik Grafika (TG)';
            }

            $hasil[] = [
                'id_siswa' => $siswa->id_siswa,
                'nama'     => $siswa->nama,
                'yi'       => $yi,
                'jurusan'  => $jurusan,
            ];
        }

        usort($hasil, fn($a, $b) => $b['yi'] <=> $a['yi']);

        foreach ($hasil as &$item) {
            $item['yi'] = round($item['yi'], 4);
        }
        unset($item);

        $data = [
            'siswas'       => $siswas,
            'matriks'      => $matriks,
            'normalisasi'  => $normalisasi,
            'terbobot'     => $terbobot,
            'hasil'        => $hasil
        ];

        return view('data.perhitungan.index', compact(
            'title',
            'kriterias',
            'data'
        ));
    }
}