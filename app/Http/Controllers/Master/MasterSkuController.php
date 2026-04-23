<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterSku;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class MasterSkuController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = MasterSku::query();
            
            return Datatables::of($data)
                ->addColumn('aksi', function (MasterSku $item) {
                    $button = '<a href="'.route('admin.master.sku.edit', $item->id).'" class="btn btn-xs btn-warning mr-1"><i class="far fa-edit"></i></a>';

                    $button .= '<button
                            class="btn btn-xs btn-danger btn-delete confirm_delete" 
                            data-id="'.$item->id.'"  
                            data-url="' . route('admin.master.sku.destroy', $item->id) . '" 
                            title="Hapus"><i class="fas fa-trash-alt"></i>
                        </button>';
                    return $button;
                })
            ->rawColumns(['aksi'])
            ->make();
        }

        $title = 'Master Sku Produk';

        return view('master.sku.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Master SKU Produk';

        return view('master.sku.create',compact('title'));
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
        MasterSku::create([
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Berhasil', 'Data Master SKU Produk berhasil disimpan');
        return redirect()->route('admin.master.sku.index');

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
        $title = 'Edit Master Kode Sku';
        $sku = MasterSku::findOrFail($id);

        return view('master.sku.edit',compact('title','sku'));
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
        $sku = MasterSku::findOrFail($id);
        $sku->update([
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Berhasil', 'Data Master SKU Produk berhasil diperbarui');
        return redirect()->route('admin.master.sku.index');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = MasterSku::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
