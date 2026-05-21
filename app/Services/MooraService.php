<?php

namespace App\Services;

use App\Models\Data\DataSiswa;
use App\Models\Data\Kriteria;

class MooraService
{
    public function hitungMoora()
    {
        $kriterias = Kriteria::orderBy('id_kriteria')->get();
        $siswas    = DataSiswa::with('penilaians')->orderBy('nama')->get();

        // 1. MATRIKS KEPUTUSAN
        $matriks = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $matriks[$siswa->id_siswa][$krit->id_kriteria] =
                    $siswa->penilaians
                        ->firstWhere('id_kriteria', $krit->id_kriteria)
                        ->nilai ?? 0;
            }
        }

        // 2. PEMBAGI — √(Σ xij²) per kriteria
        $pembagi = [];

        foreach ($kriterias as $krit) {
            $total = 0;
            foreach ($siswas as $siswa) {
                $total += pow($matriks[$siswa->id_siswa][$krit->id_kriteria], 2);
            }
            $pembagi[$krit->id_kriteria] = sqrt($total);
        }

        // 3. NORMALISASI — rij = xij / √(Σ xij²)
        $normalisasi = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $pemb = $pembagi[$krit->id_kriteria];
                $normalisasi[$siswa->id_siswa][$krit->id_kriteria] =
                    $pemb != 0
                        ? $matriks[$siswa->id_siswa][$krit->id_kriteria] / $pemb
                        : 0;
            }
        }

        // 4. NILAI TERBOBOT — wij = wj × rij
        $terbobot = [];

        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $terbobot[$siswa->id_siswa][$krit->id_kriteria] =
                    $normalisasi[$siswa->id_siswa][$krit->id_kriteria] * $krit->bobot;
            }
        }

        $jurusanProfil = [
            'Teknik Komputer Jaringan (TKJ)' => ['C1', 'C4'],
            'Desain Komunikasi Visual (DKV)'  => ['C3', 'C4', 'C5'],
            'Teknik Grafika (TG)'             => ['C3', 'C2', 'C5'],
        ];

        $kodeToId = $kriterias->pluck('id_kriteria', 'kode');
        $kodeToBobot = $kriterias->pluck('bobot', 'kode');

        $hasil = [];

        foreach ($siswas as $siswa) {

            $benefit = 0;
            $cost    = 0;

            foreach ($kriterias as $krit) {
                $nilaiTerbobot = $terbobot[$siswa->id_siswa][$krit->id_kriteria];
                if ($krit->tipe === 'benefit') {
                    $benefit += $nilaiTerbobot;
                } else {
                    $cost += $nilaiTerbobot;
                }
            }

            $yi = $benefit - $cost;

            $skorJurusan = [];

            foreach ($jurusanProfil as $namaJurusan => $kodeKriterias) {
                $skor = 0;
                $totalBobot = 0;

                foreach ($kodeKriterias as $kode) {
                    $idKrit = $kodeToId[$kode] ?? null;
                    if ($idKrit !== null) {
                        $skor += $terbobot[$siswa->id_siswa][$idKrit] ?? 0;
                        $totalBobot += $kodeToBobot[$kode] ?? 0;
                    }
                }
                $skorJurusan[$namaJurusan] = $totalBobot > 0 ? ($skor / $totalBobot) : 0;
            }

            arsort($skorJurusan);
            $jurusanTerpilih = array_key_first($skorJurusan);

            $hasil[] = [
                'id_siswa'     => $siswa->id_siswa,
                'nama'         => $siswa->nama,
                'yi'           => $yi,
                'skor_jurusan' => $skorJurusan,
                'jurusan'      => $jurusanTerpilih,
            ];
        }

        // Ranking berdasarkan Yi tertinggi
        usort($hasil, fn($a, $b) => $b['yi'] <=> $a['yi']);

        foreach ($hasil as &$item) {
            $item['yi'] = round($item['yi'], 4);
            $item['skor_jurusan'] = array_map(
                fn($s) => round($s, 4),
                $item['skor_jurusan']
            );
        }
        unset($item);

        $data = [
            'siswas'      => $siswas,
            'matriks'     => $matriks,
            'normalisasi' => $normalisasi,
            'terbobot'    => $terbobot,
            'hasil'       => $hasil,
        ];

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
