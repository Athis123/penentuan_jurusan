<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Nilai;
use App\Models\Data\DataSiswa;
use App\Models\Data\Kriteria;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NilaiController extends Controller
{

    public function index()
    {
        $title = 'Data Penilaian Siswa';
        $kriteria = Kriteria::orderBy('id_kriteria')->get();
        $siswa = DataSiswa::with('penilaians')
            ->orderBy('nama', 'asc')
            ->get();

        return view('data.penilaian.index', compact('title', 'kriteria', 'siswa'));
    }


    public function bulkStore(Request $request)
    {
        // dd($request->all());
        $data = $request->input('nilai');
        DB::transaction(function () use ($data) {
            foreach ($data as $id_siswa => $nilaiPerKriteria) {
                foreach ($nilaiPerKriteria as $id_kriteria => $nilai) {
                    if ($nilai === null || $nilai === '') continue;

                    Nilai::updateOrCreate(
                        [
                            'id_siswa' => $id_siswa,
                            'id_kriteria' => $id_kriteria,
                        ],
                        [
                            'nilai' => $nilai,
                        ]
                    );
                }
            }
        });

        return response()->json(['message' => 'Data penilaian berhasil disimpan.']);
    }

    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}