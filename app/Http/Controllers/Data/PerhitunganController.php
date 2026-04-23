<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Data\Alternatif;
use App\Models\Data\Kriteria;

class PerhitunganController extends Controller
{
    public function index()
    {
        $title = 'Data Perhitungan MOORA per Golongan';

        $kriterias = Kriteria::all();

        $alternatifs = Alternatif::with('penilaians.kriteria')
            ->orderByRaw("CAST(SUBSTRING(kode,2) AS UNSIGNED)")
            ->get()
            ->groupBy('golongan');

        $perhitungan = [];

        foreach ($alternatifs as $golongan => $alts) {

            /* =====================
             * 1. Matriks Keputusan
             * ===================== */
            $matriks = [];
            foreach ($alts as $alt) {
                foreach ($kriterias as $krit) {
                    $matriks[$alt->id_alternatif][$krit->id_kriteria] =
                        $alt->penilaians
                            ->firstWhere('id_kriteria', $krit->id_kriteria)
                            ->nilai ?? 0;
                }
            }

            /* =====================
             * 2. Pembagi
             * ===================== */
            $pembagi = [];
            foreach ($kriterias as $krit) {
                $sum = 0;
                foreach ($alts as $alt) {
                    $sum += pow($matriks[$alt->id_alternatif][$krit->id_kriteria], 2);
                }
                $pembagi[$krit->id_kriteria] = sqrt($sum);
            }

            /* =====================
             * 3. Normalisasi
             * ===================== */
            $normalisasi = [];
            foreach ($alts as $alt) {
                foreach ($kriterias as $krit) {
                    $normalisasi[$alt->id_alternatif][$krit->id_kriteria] =
                        $pembagi[$krit->id_kriteria] > 0
                            ? $matriks[$alt->id_alternatif][$krit->id_kriteria] / $pembagi[$krit->id_kriteria]
                            : 0;
                }
            }

            /* =====================
             * 4. Matriks Terbobot
             * ===================== */
            $terbobot = [];
            foreach ($alts as $alt) {
                foreach ($kriterias as $krit) {
                    $terbobot[$alt->id_alternatif][$krit->id_kriteria] =
                        $normalisasi[$alt->id_alternatif][$krit->id_kriteria] * $krit->bobot;
                }
            }

            /* =====================
             * 5. Hitung Yi
             * ===================== */
            $hasil = [];
            foreach ($alts as $alt) {
                $benefit = 0;
                $cost = 0;

                foreach ($kriterias as $krit) {
                    if ($krit->tipe === 'benefit') {
                        $benefit += $terbobot[$alt->id_alternatif][$krit->id_kriteria];
                    } else {
                        $cost += $terbobot[$alt->id_alternatif][$krit->id_kriteria];
                    }
                }

                $hasil[$alt->id_alternatif] = [
                    'nama' => $alt->nama,
                    'yi'   => $benefit - $cost
                ];
            }

            uasort($hasil, fn($a, $b) => $b['yi'] <=> $a['yi']);

            $perhitungan[$golongan] = [
                'alternatifs' => $alts,
                'matriks'     => $matriks,
                'normalisasi' => $normalisasi,
                'terbobot'    => $terbobot,
                'hasil'       => $hasil
            ];
        }

        return view('data.perhitungan.index', compact(
            'title',
            'kriterias',
            'perhitungan'
        ));
    }
}