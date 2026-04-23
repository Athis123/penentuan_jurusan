<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Kriteria;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use Carbon\Carbon;

class KriteriaController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = Kriteria::orderBy('created_at','asc');

            return Datatables::of($data)
                ->editColumn('bobot', function ($item) {
                    return number_format($item->bobot, 2, '.', '');
                })
                ->editColumn('tipe', function ($row) {
                    return ucfirst($row->tipe);
                })
                ->addColumn('aksi', function ($item) {
                    $dropdown = '<div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $item->id_kriteria . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' . $item->id_kriteria . '">';
                    $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.kriteria.edit', $item->id_kriteria) . '"><i class="far fa-edit mr-2"></i>Edit</a>';
                    $dropdown .= '<button class="dropdown-item btn-delete text-danger"
                            data-id="' . $item->id_kriteria . '"
                            data-url="' . route('admin.data.kriteria.destroy', $item->id_kriteria) . '">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus</button>';
                    $dropdown .= '</div></div>';

                    return $dropdown;
                })
                ->rawColumns(['aksi'])
                ->make();
        }

        $title = 'Data Kriteria';

        return view('data.kriteria.index', compact('title'));
    }

    public function create()
    {
        $title = 'Create Data Kriteria';
        $kriteria = Kriteria::all();

        return view('data.kriteria.create', compact('title', 'kriteria'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'      => 'required',
            'nama'      => 'required',
            'bobot'     => 'required',
            'tipe'      => 'required'
        ]);

        DB::transaction(function () use ($request) {
            Kriteria::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'bobot' => str_replace(',', '.', $request->bobot),
                'tipe' => $request->tipe,
            ]);
        });

        Alert::success('Berhasil', 'Data Kriteria berhasil disimpan');
        return redirect()->route('admin.data.kriteria.index');
    }

    public function edit($id)
    {
        $title = 'Edit Data Kriteria';
        $kriteria = Kriteria::where('id_kriteria', $id)->firstOrFail();

        return view('data.kriteria.edit', compact('title', 'kriteria'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode'  => 'required',
            'nama'  => 'required',
            'bobot' => 'required|numeric',
            'tipe'  => 'required|in:benefit,cost',
        ]);

        DB::transaction(function () use ($request, $id) {
            $kriteria = Kriteria::findOrFail($id);
            $kriteria->update([
                'kode'  => $request->kode,
                'nama'  => $request->nama,
                'bobot' => str_replace(',', '.', $request->bobot),
                'tipe'  => $request->tipe,
            ]);
        });

        Alert::success('Berhasil', 'Data Kriteria berhasil diperbarui');
        return redirect()->route('admin.data.kriteria.index');
    }


    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}