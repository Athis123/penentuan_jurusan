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

        /* =====================
         * 1. MATRKS KEPUTUSAN
         * ===================== */
        $matriks = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $nilai = $siswa->penilaians
                    ->firstWhere('id_kriteria', $krit->id_kriteria)
                    ->nilai ?? 0;

                $matriks[$siswa->id_siswa][$krit->id_kriteria] = $nilai;
            }
        }

        /* =====================
         * 2. PEMBAGI
         * ===================== */
        $pembagi = [];

        foreach ($kriterias as $krit) {
            $total = 0;

            foreach ($siswas as $siswa) {
                $nilai = $matriks[$siswa->id_siswa][$krit->id_kriteria];
                $total += pow($nilai, 2);
            }

            $pembagi[$krit->id_kriteria] = sqrt($total);
        }

        /* =====================
         * 3. NORMALISASI
         * ===================== */
        $normalisasi = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $nilai = $matriks[$siswa->id_siswa][$krit->id_kriteria];
                $pemb = $pembagi[$krit->id_kriteria];

                $normalisasi[$siswa->id_siswa][$krit->id_kriteria] =
                    $pemb != 0 ? $nilai / $pemb : 0;
            }
        }

        /* =====================
         * 4. TERBOBOT
         * ===================== */
        $terbobot = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $terbobot[$siswa->id_siswa][$krit->id_kriteria] =
                    $normalisasi[$siswa->id_siswa][$krit->id_kriteria] * $krit->bobot;
            }
        }

        /* =====================
         * 5. NILAI Yi + JURUSAN
         * ===================== */
        $hasil = [];

        foreach ($siswas as $siswa) {
            $benefit = 0;
            $cost = 0;

            foreach ($kriterias as $krit) {
                $nilai = $terbobot[$siswa->id_siswa][$krit->id_kriteria];

                if ($krit->tipe == 'benefit') {
                    $benefit += $nilai;
                } else {
                    $cost += $nilai;
                }
            }

            $yi = $benefit - $cost;

            /* =====================
             * PENENTUAN JURUSAN
             * ===================== */
            $jurusan = $this->tentukanJurusan($yi);

            $hasil[] = [
                'id_siswa' => $siswa->id_siswa,
                'nama'     => $siswa->nama,
                'yi'       => round($yi, 4),
                'jurusan'  => $jurusan
            ];
        }

        // Ranking
        usort($hasil, fn($a, $b) => $b['yi'] <=> $a['yi']);

        // Bungkus data biar rapi di view
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

    /* =====================
     * FUNCTION JURUSAN
     * ===================== */
    private function tentukanJurusan($yi)
    {
        if ($yi >= 0.6) {
            return 'Teknik Komputer Jaringan (TKJ)';
        } elseif ($yi >= 0.3) {
            return 'Desain Komunikasi Visual (DKV)';
        } else {
            return 'Teknik Grafika (TG)';
        }
    }
}