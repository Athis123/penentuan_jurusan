<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterPromo;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class MasterPromoController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = MasterPromo::query();
            
            return Datatables::of($data)
                ->addColumn('aksi', function (MasterPromo $item) {
                    $button = '<a href="'.route('admin.master.promo.edit', $item->id).'" class="btn btn-xs btn-warning mr-1"><i class="far fa-edit"></i></a>';

                    $button .= '<button
                            class="btn btn-xs btn-danger btn-delete confirm_delete" 
                            data-id="'.$item->id.'"  
                            data-url="' . route('admin.master.promo.destroy', $item->id) . '" 
                            title="Hapus"><i class="fas fa-trash-alt"></i>
                        </button>';
                    return $button;
                })
            ->rawColumns(['color','aksi'])
            ->make();
        }

        $title = 'Master Kode Promo';

        return view('master.promo.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Master Kode Promo';

        return view('master.promo.create',compact('title'));
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
        MasterPromo::create([
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Data Master Kode Promo berhasil disimpan');
        return redirect()->route('admin.master.promo.index');

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
        $title = 'Edit Master Kode Promo';
        $promo = MasterPromo::findOrFail($id);

        return view('master.promo.edit',compact('title','promo'));
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
        $promo = MasterPromo::findOrFail($id);
        $promo->update([
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Berhasil', 'Data Master Kode Promo berhasil diperbarui');
        return redirect()->route('admin.master.promo.index');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = MasterPromo::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
