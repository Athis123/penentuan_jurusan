<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Alternatif;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use Carbon\Carbon;

class AlternatifController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = Alternatif::orderBy('created_at','asc');

            return Datatables::of($data)
                ->editColumn('golongan', function ($row) {
                    return ucwords(str_replace('_', ' ', $row->golongan));
                })
                ->addColumn('aksi', function ($item) {
                    $dropdown = '<div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $item->id_alternatif . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' . $item->id_alternatif . '">';
                    $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.alternatif.edit', $item->id_alternatif) . '"><i class="far fa-edit mr-2"></i>Edit</a>';
                    $dropdown .= '<button class="dropdown-item btn-delete text-danger"
                            data-id="' . $item->id_alternatif . '"
                            data-url="' . route('admin.data.alternatif.destroy', $item->id_alternatif) . '">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus</button>';
                    $dropdown .= '</div></div>';

                    return $dropdown;
                })
                ->rawColumns(['aksi'])
                ->make();
        }

        $title = 'Data Alternatif';

        return view('data.alternatif.index', compact('title'));
    }

    public function create()
    {
        $title = 'Create Data Alternatif';
        $alternatif = Alternatif::all();

        return view('data.alternatif.create', compact('title', 'alternatif'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'      => 'required',
            'nama'      => 'required',
            'golongan'  => 'required|in:low,middle,middle_up',
        ]);

        DB::transaction(function () use ($request) {
            Alternatif::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'golongan'  => $request->golongan,
            ]);
        });

        Alert::success('Berhasil', 'Data Alternatif berhasil disimpan');
        return redirect()->route('admin.data.alternatif.index');
    }

    public function edit($id)
    {
        $title = 'Edit Data Alternatif';
        $alternatif = Alternatif::findOrFail($id);

        return view('data.alternatif.edit', compact('title', 'alternatif'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode'  => 'required',
            'nama'  => 'required',
            'golongan'  => 'required|in:low,middle,middle_up',
        ]);

        DB::transaction(function () use ($request, $id) {
            $alternatif = Alternatif::findOrFail($id);
            $alternatif->update([
                'kode'  => $request->kode,
                'nama'  => $request->nama,
                'golongan'  => $request->golongan,
            ]);
        });

        Alert::success('Berhasil', 'Data Alternatif berhasil diperbarui');
        return redirect()->route('admin.data.alternatif.index');
    }


    public function destroy($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $alternatif->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}