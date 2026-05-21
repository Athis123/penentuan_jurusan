<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Data\DataSiswa;
use App\Models\Data\Kriteria;
use App\Models\Data\Penilaian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        $kriterias = Kriteria::orderBy('id_kriteria')->get();
        $siswas    = DataSiswa::with('penilaians')->orderBy('nama')->get();

        // ── Hitung MOORA untuk keperluan chart ──────────────────────────

        $matriks = [];
        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $matriks[$siswa->id_siswa][$krit->id_kriteria] =
                    $siswa->penilaians
                        ->firstWhere('id_kriteria', $krit->id_kriteria)
                        ->nilai ?? 0;
            }
        }

        $pembagi = [];
        foreach ($kriterias as $krit) {
            $total = 0;
            foreach ($siswas as $siswa) {
                $total += pow($matriks[$siswa->id_siswa][$krit->id_kriteria], 2);
            }
            $pembagi[$krit->id_kriteria] = sqrt($total);
        }

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

        $terbobot = [];
        foreach ($siswas as $siswa) {
            foreach ($kriterias as $krit) {
                $terbobot[$siswa->id_siswa][$krit->id_kriteria] =
                    $normalisasi[$siswa->id_siswa][$krit->id_kriteria] * $krit->bobot;
            }
        }

        $jurusanProfil = [
            'TKJ' => ['C1', 'C4'],
            'DKV' => ['C3', 'C4', 'C5'],
            'TG'  => ['C3', 'C2', 'C5'],
        ];

        $kodeToId    = $kriterias->pluck('id_kriteria', 'kode');
        $kodeToBobot = $kriterias->pluck('bobot', 'kode');

        $hasil = [];
        foreach ($siswas as $siswa) {
            $benefit = 0;
            $cost    = 0;

            foreach ($kriterias as $krit) {
                $nilaiTerbobot = $terbobot[$siswa->id_siswa][$krit->id_kriteria];
                $krit->tipe === 'benefit'
                    ? $benefit += $nilaiTerbobot
                    : $cost    += $nilaiTerbobot;
            }

            $yi = $benefit - $cost;

            $skorJurusan = [];
            foreach ($jurusanProfil as $namaJurusan => $kodeKriterias) {
                $skor = $totalBobot = 0;
                foreach ($kodeKriterias as $kode) {
                    $idKrit = $kodeToId[$kode] ?? null;
                    if ($idKrit !== null) {
                        $skor       += $terbobot[$siswa->id_siswa][$idKrit] ?? 0;
                        $totalBobot += $kodeToBobot[$kode] ?? 0;
                    }
                }
                $skorJurusan[$namaJurusan] = $totalBobot > 0 ? ($skor / $totalBobot) : 0;
            }

            arsort($skorJurusan);

            $hasil[] = [
                'nama'    => $siswa->nama,
                'yi'      => round($yi, 4),
                'jurusan' => array_key_first($skorJurusan),
            ];
        }

        usort($hasil, fn($a, $b) => $b['yi'] <=> $a['yi']);

        // ── Data untuk chart ─────────────────────────────────────────────

        // 1. Distribusi jurusan (Doughnut)
        $distribusiJurusan = collect($hasil)
            ->groupBy('jurusan')
            ->map->count();

        // 2. Top 10 siswa berdasarkan Yi (Bar)
        $top10 = array_slice($hasil, 0, 10);

        // 3. Rata-rata Yi per jurusan (Bar horizontal)
        $rataYiJurusan = collect($hasil)
            ->groupBy('jurusan')
            ->map(fn($group) => round($group->avg('yi'), 4));

        // ── Data card ────────────────────────────────────────────────────
        $data = [
            'siswaCount'       => $siswas->count(),
            'kriteriaCount'    => $kriterias->count(),
            'distribusiJurusan'=> $distribusiJurusan,
            'top10'            => $top10,
            'rataYiJurusan'    => $rataYiJurusan,
        ];

        return view('dashboard.index', compact('title', 'data'));
    }
}