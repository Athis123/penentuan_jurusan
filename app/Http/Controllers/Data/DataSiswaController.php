<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataSiswa;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use Carbon\Carbon;

class DataSiswaController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = DataSiswa::orderBy('created_at','asc');

            return Datatables::of($data)
                ->editColumn('alamat', function ($row) {
                    return ucwords(str_replace('_', ' ', $row->alamat));
                })
                ->addColumn('aksi', function ($item) {
                    $dropdown = '<div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $item->id_siswa . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' . $item->id_siswa . '">';
                    $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.siswa.edit', $item->id_siswa) . '"><i class="far fa-edit mr-2"></i>Edit</a>';
                    $dropdown .= '<button class="dropdown-item btn-delete text-danger"
                            data-id="' . $item->id_siswa . '"
                            data-url="' . route('admin.data.siswa.destroy', $item->id_siswa) . '">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus</button>';
                    $dropdown .= '</div></div>';

                    return $dropdown;
                })
                ->rawColumns(['aksi'])
                ->make();
        }

        $title = 'Data Siswa';

        return view('data.siswa.index', compact('title'));
    }

    public function create()
    {
        $title = 'Create Data Siswa';
        $siswa = DataSiswa::all();

        return view('data.siswa.create', compact('title', 'siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn'      => 'required',
            'nama'      => 'required',
            'alamat'  => 'nullable',
            'jenis_kelamin'  => 'nullable',
            'no_hp' => 'nullable',
            'tmp_lahir' => 'nullable',
            'tgl_lahir' => 'nullable',
            'asal_sekolah'    => 'nullable',
            'foto' => 'nullable',
        ]);

        DB::transaction(function () use ($request) {

            $foto = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto')->store('siswa', 'public');
            }

            DataSiswa::create([
                'nisn' => $request->nisn,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'tmp_lahir' => $request->tmp_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'asal_sekolah' => $request->asal_sekolah,
                'foto' => $foto,
            ]);
        });

        Alert::success('Berhasil', 'Data Siswa berhasil disimpan');
        return redirect()->route('admin.data.siswa.index');
    }

    public function edit($id)
    {
        $title = 'Edit Data Siswa';
        $siswa = DataSiswa::findOrFail($id);

        return view('data.siswa.edit', compact('title', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nisn'      => 'required',
            'nama'      => 'required',
            'alamat'  => 'nullable',
            'jenis_kelamin'  => 'nullable',
            'no_hp' => 'nullable',
            'tmp_lahir' => 'nullable',
            'tgl_lahir' => 'nullable',
            'asal_sekolah'    => 'nullable',
            'foto' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $id) {

            $siswa = DataSiswa::findOrFail($id);

            // HANDLE FOTO
            if ($request->hasFile('foto')) {

                // hapus foto lama kalau ada
                if ($siswa->foto && \Storage::disk('public')->exists($siswa->foto)) {
                    \Storage::disk('public')->delete($siswa->foto);
                }

                // upload foto baru
                $foto = $request->file('foto')->store('siswa', 'public');

                $siswa->foto = $foto;
            }

            $siswa->update([
                'nisn' => $request->nisn,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'tmp_lahir' => $request->tmp_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'asal_sekolah' => $request->asal_sekolah,
            ]);
        });

        Alert::success('Berhasil', 'Data Siswa berhasil diperbarui');
        return redirect()->route('admin.data.siswa.index');
    }


    public function destroy($id)
    {
        $siswa = DataSiswa::findOrFail($id);
        $siswa->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}