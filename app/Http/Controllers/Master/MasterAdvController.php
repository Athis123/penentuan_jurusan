<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterAdv;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class MasterAdvController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = MasterAdv::query();
            
            return Datatables::of($data)
                ->addColumn('aksi', function (MasterAdv $item) {
                    $button = '<a href="'.route('admin.master.adv.edit', $item->id).'" class="btn btn-xs btn-warning mr-1"><i class="far fa-edit"></i></a>';

                    $button .= '<button
                            class="btn btn-xs btn-danger btn-delete confirm_delete" 
                            data-id="'.$item->id.'"  
                            data-url="' . route('admin.master.adv.destroy', $item->id) . '" 
                            title="Hapus"><i class="fas fa-trash-alt"></i>
                        </button>';
                    return $button;
                })
            ->rawColumns(['color','aksi'])
            ->make();
        }

        $title = 'Master Kode Adv';

        return view('master.adv.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Master Kode Adv';

        return view('master.adv.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'kode'     => 'nullable',
            'deskripsi' => 'nullable',
        ]);

        // Simpan ke DB
        MasterAdv::create([
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Data Master Kode Adv berhasil disimpan');
        return redirect()->route('admin.master.adv.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Edit Master Kode Adv';
        $adv = MasterAdv::findOrFail($id);

        return view('master.adv.edit',compact('title','adv'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi
        $validated = $request->validate([
            'kode'     => 'nullable',
            'deskripsi' => 'nullable',
        ]);

        // Update
        $adv = MasterAdv::findOrFail($id);
        $adv->update([
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Berhasil', 'Data Master Kode Adv berhasil diperbarui');
        return redirect()->route('admin.master.adv.index');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adv = MasterAdv::findOrFail($id);
        $adv->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
