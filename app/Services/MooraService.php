<?php

namespace App\Services;

use App\Models\Data\Alternatif;
use App\Models\Data\Kriteria;

class MooraService
{
    public function hitungMoora()
    {
        $alternatifs = Alternatif::with('penilaians.kriteria')->get();
        $kriterias = Kriteria::all();

        $alternatifCount = $alternatifs->count();
        $kriteriaCount = $kriterias->count();

        // 1. Matriks Keputusan
        $matriks = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $nilai = $alt->penilaians->firstWhere('id_kriteria', $krit->id_kriteria)->nilai ?? 0;
                $matriks[$alt->id_alternatif][$krit->id_kriteria] = $nilai;
            }
        }

        // 2. Normalisasi (Ratio System)
        $pembagi = [];
        foreach ($kriterias as $krit) {
            $sumKuadrat = 0;
            foreach ($alternatifs as $alt) {
                $sumKuadrat += pow($matriks[$alt->id_alternatif][$krit->id_kriteria], 2);
            }
            $pembagi[$krit->id_kriteria] = sqrt($sumKuadrat);
        }

        $normalisasi = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $normalisasi[$alt->id_alternatif][$krit->id_kriteria] =
                    $matriks[$alt->id_alternatif][$krit->id_kriteria] / $pembagi[$krit->id_kriteria];
            }
        }

        // 3. Normalisasi Terbobot (Weighted Normalized)
        $terbobot = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $terbobot[$alt->id_alternatif][$krit->id_kriteria] =
                    $normalisasi[$alt->id_alternatif][$krit->id_kriteria] * $krit->bobot;
            }
        }

        // 4. Perhitungan Nilai Akhir (Benefit - Cost)
        $hasil = [];
        foreach ($alternatifs as $alt) {
            $sumBenefit = 0;
            $sumCost = 0;
            foreach ($kriterias as $krit) {
                if ($krit->tipe == 'benefit') {
                    $sumBenefit += $terbobot[$alt->id_alternatif][$krit->id_kriteria];
                } else {
                    $sumCost += $terbobot[$alt->id_alternatif][$krit->id_kriteria];
                }
            }
            $hasil[$alt->id_alternatif] = $sumBenefit - $sumCost;
        }

        arsort($hasil);

        $alternatifNames = [];
        $hasilValues = [];

        foreach ($hasil as $id_alt => $nilai) {
            $alternatif = $alternatifs->firstWhere('id_alternatif', $id_alt);
            $alternatifNames[] = $alternatif->nama;
            $hasilValues[] = number_format($nilai, 3);
        }

        return [
            'alternatifCount' => $alternatifCount,
            'kriteriaCount' => $kriteriaCount,
            'alternatifs' => $alternatifs,
            'kriterias' => $kriterias,
            'matriks' => $matriks,
            'normalisasi' => $normalisasi,
            'terbobot' => $terbobot,
            'hasil' => $hasil,
            'alternatifNames' => $alternatifNames,
            'hasilValues' => $hasilValues
        ];
    }
}
