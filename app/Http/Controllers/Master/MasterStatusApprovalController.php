<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterStatusApproval;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class MasterStatusApprovalController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = MasterStatusApproval::query();
            
            return Datatables::of($data)
                ->addColumn('color', function (MasterStatusApproval $item) {
                    return '<span class="badge" style="background-color: '. $item->color .'; color: #fff;">'. $item->status .'</span>';
                })
                ->addColumn('aksi', function (MasterStatusApproval $item) {
                    $button = '<a href="'.route('admin.master.status.edit', $item->id).'" class="btn btn-xs btn-warning mr-1"><i class="far fa-edit"></i></a>';

                    $button .= '<button
                            class="btn btn-xs btn-danger btn-delete confirm_delete" 
                            data-id="'.$item->id.'"  
                            data-url="' . route('admin.master.status.destroy', $item->id) . '" 
                            title="Hapus"><i class="fas fa-trash-alt"></i>
                        </button>';
                    return $button;
                })
            ->rawColumns(['aksi', 'color'])
            ->make();
        }

        $title = 'Master Status Approval';

        return view('master.status.index',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Master Status Approval';

        return view('master.status.create',compact('title'));
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
            'status'     => 'nullable',
            'keterangan' => 'nullable',
            'color' => 'nullable',
        ]);

        // Simpan ke DB
        MasterStatusApproval::create([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'color' => $request->color,
        ]);

        Alert::success('Berhasil', 'Data Master Status Approval berhasil disimpan');
        return redirect()->route('admin.master.status.index');

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
        $title = 'Edit Master Status Approval';
        $status = MasterStatusApproval::findOrFail($id);

        return view('master.status.edit',compact('title','status'));
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
            'status'     => 'nullable',
            'keterangan' => 'nullable',
            'color' => 'nullable',
        ]);

        // Update
        $status = MasterStatusApproval::findOrFail($id);
        $status->update([
            'kode' => $request->kode,
            'keterangan' => $request->keterangan,
            'color' => $request->color,
        ]);

        Alert::success('Berhasil', 'Data Master Status Approval berhasil diperbarui');
        return redirect()->route('admin.master.status.index');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = MasterStatusApproval::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
