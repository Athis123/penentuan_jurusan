<?php

namespace App\Services;

use App\Models\Data\DataSiswa;
use App\Models\Data\Kriteria;

class MooraService
{
    public function hitungMoora()
    {
        $siswas = DataSiswa::with('penilaians.kriteria')->get();
        $kriterias = Kriteria::all();

        $siswaCount = $siswas->count();
        $kriteriaCount = $kriterias->count();

        // 1. Matriks Keputusan
        $matriks = [];
        foreach ($siswas as $alt) {
            foreach ($kriterias as $krit) {
                $nilai = $alt->penilaians->firstWhere('id_kriteria', $krit->id_kriteria)->nilai ?? 0;
                $matriks[$alt->id_siswa][$krit->id_kriteria] = $nilai;
            }
        }

        // 2. Normalisasi (Ratio System)
        $pembagi = [];
        foreach ($kriterias as $krit) {
            $sumKuadrat = 0;
            foreach ($siswas as $alt) {
                $sumKuadrat += pow($matriks[$alt->id_siswa][$krit->id_kriteria], 2);
            }
            $pembagi[$krit->id_kriteria] = sqrt($sumKuadrat);
        }

        $normalisasi = [];
        foreach ($siswas as $alt) {
            foreach ($kriterias as $krit) {
                $normalisasi[$alt->id_siswa][$krit->id_kriteria] =
                    $matriks[$alt->id_siswa][$krit->id_kriteria] / $pembagi[$krit->id_kriteria];
            }
        }

        // 3. Normalisasi Terbobot (Weighted Normalized)
        $terbobot = [];
        foreach ($siswas as $alt) {
            foreach ($kriterias as $krit) {
                $terbobot[$alt->id_siswa][$krit->id_kriteria] =
                    $normalisasi[$alt->id_siswa][$krit->id_kriteria] * $krit->bobot;
            }
        }

        // 4. Perhitungan Nilai Akhir (Benefit - Cost)
        $hasil = [];
        foreach ($siswas as $alt) {
            $sumBenefit = 0;
            $sumCost = 0;
            foreach ($kriterias as $krit) {
                if ($krit->tipe == 'benefit') {
                    $sumBenefit += $terbobot[$alt->id_siswa][$krit->id_kriteria];
                } else {
                    $sumCost += $terbobot[$alt->id_siswa][$krit->id_kriteria];
                }
            }
            $hasil[$alt->id_siswa] = $sumBenefit - $sumCost;
        }

        arsort($hasil);

        $siswaNames = [];
        $hasilValues = [];

        foreach ($hasil as $id_alt => $nilai) {
            $siswa = $siswas->firstWhere('id_siswa', $id_alt);
            $siswaNames[] = $siswa->nama;
            $hasilValues[] = number_format($nilai, 3);
        }

        return [
            'siswaCount' => $siswaCount,
            'kriteriaCount' => $kriteriaCount,
            'siswas' => $siswas,
            'kriterias' => $kriterias,
            'matriks' => $matriks,
            'normalisasi' => $normalisasi,
            'terbobot' => $terbobot,
            'hasil' => $hasil,
            'siswaNames' => $siswaNames,
            'hasilValues' => $hasilValues
        ];
    }
}
