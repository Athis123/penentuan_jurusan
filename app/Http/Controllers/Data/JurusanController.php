<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Jurusan;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use Carbon\Carbon;

class JurusanController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = Jurusan::orderBy('created_at','asc');

            return Datatables::of($data)
                ->addColumn('aksi', function ($item) {
                    $dropdown = '<div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $item->id_jurusan . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' . $item->id_jurusan . '">';
                    $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.jurusan.edit', $item->id_jurusan) . '"><i class="far fa-edit mr-2"></i>Edit</a>';
                    $dropdown .= '<button class="dropdown-item btn-delete text-danger"
                            data-id="' . $item->id_jurusan . '"
                            data-url="' . route('admin.data.jurusan.destroy', $item->id_jurusan) . '">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus</button>';
                    $dropdown .= '</div></div>';

                    return $dropdown;
                })
                ->rawColumns(['aksi'])
                ->make();
        }

        $title = 'Data Program Keahlian';

        return view('data.jurusan.index', compact('title'));
    }

    public function create()
    {
        $title = 'Create Data Program Keahlian';
        $jurusan = Jurusan::all();

        return view('data.jurusan.create', compact('title', 'jurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'      => 'required',
            'nama'      => 'required',
        ]);

        DB::transaction(function () use ($request) {
            Jurusan::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
            ]);
        });

        Alert::success('Berhasil', 'Data Jurusan berhasil disimpan');
        return redirect()->route('admin.data.jurusan.index');
    }

    public function edit($id)
    {
        $title = 'Edit Data Program Keahlian';
        $jurusan = Jurusan::where('id_jurusan', $id)->firstOrFail();

        return view('data.jurusan.edit', compact('title', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode'  => 'required',
            'nama'  => 'required',
        ]);

        DB::transaction(function () use ($request, $id) {
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->update([
                'kode'  => $request->kode,
                'nama'  => $request->nama,
                'bobot' => str_replace(',', '.', $request->bobot),
                'tipe'  => $request->tipe,
            ]);
        });

        Alert::success('Berhasil', 'Data Jurusan berhasil diperbarui');
        return redirect()->route('admin.data.jurusan.index');
    }


    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}