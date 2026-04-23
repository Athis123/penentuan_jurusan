<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Data\Order;
use App\Models\Master\MasterPromo;
use App\Models\Master\MasterSku;
use App\Models\Master\MasterAdv;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use Carbon\Carbon;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = Order::orderBy('tanggal', 'desc');

            // Filter daterange
            if ($request->filled('daterange')) {
                [$start, $end] = explode(' - ', $request->daterange);

                // Convert ke format Y-m-d
                $start = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
                $end = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();

                $data->whereBetween('created_at', [$start, $end]);
            }


            return Datatables::of($data)
                ->addColumn('tanggal', function ($item) {
                    return \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'); // atau 'd F Y'
                })
                ->addColumn('harga_produk', function ($item) {
                    return number_format($item->harga_produk, 0, ',', '.');
                })
                ->addColumn('ongkir', function ($item) {
                    return number_format($item->ongkir, 0, ',', '.');
                })
                ->addColumn('diskon_ongkir', function ($item) {
                    return number_format($item->diskon_ongkir, 0, ',', '.');
                })
                ->addColumn('admin_cod', function ($item) {
                    return number_format($item->admin_cod, 0, ',', '.');
                })
                ->addColumn('diskon_admin_cod', function ($item) {
                    return number_format($item->diskon_admin_cod, 0, ',', '.');
                })
                ->addColumn('total_pembayaran', function ($item) {
                    return '<b>' . number_format($item->total_pembayaran, 0, ',', '.') . '</b>';
                })
                ->editColumn('pembayaran', function ($row) {
                    return ucfirst($row->pembayaran);
                })
                ->addColumn('status_approval', function ($item) {
                    $status = optional($item->statusApproval);
                    $badge = '<span class="badge" style="background-color: ' . ($status->color ?? '#ccc') . '; color: white;">' .
                        ($status->status ?? '-') . '</span>';
                    return $badge;
                })
                ->addColumn('aksi', function ($item) {
                    $user = auth()->user();
                    $canApprove = $user->hasRole('admin') || $user->hasRole('finance');

                    $dropdown = '<div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $item->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' . $item->id . '">';

                    // Show
                    $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.order.show', $item->id) . '"><i class="fas fa-eye mr-2"></i>Detail</a>';

                    // Edit dan Delete jika belum selesai dicek
                    if ( strcasecmp($item->status_approval, 'Selesai Dicek') !== 0 &&
                        ($user->hasRole('admin') || ($user->tim && strtolower($user->tim) === 'cs'))
                    ) {
                        $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.order.edit', $item->id) . '"><i class="far fa-edit mr-2"></i>Edit</a>';

                        $dropdown .= '<button class="dropdown-item btn-delete text-danger"
                            data-id="' . $item->id . '"
                            data-url="' . route('admin.data.order.destroy', $item->id) . '">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus</button>';
                    }

                    $dropdown .= '</div></div>';

                    return $dropdown;
                })
                ->rawColumns(['aksi', 'total_pembayaran', 'status_approval'])
                ->make();
        }

        $title = 'Data Order CS';
        return view('data.order.index', compact('title'));
    }

    public function create()
    {
        $title = 'Create Data Order';
        $kodePromo = MasterPromo::all();
        $sku = MasterSku::all();
        $adv = MasterAdv::all();

        return view('data.order.create', compact('title', 'kodePromo', 'sku', 'adv'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'           => 'required|date_format:d-m-Y H:i',
            'lok_gudang'        => 'required|string',
            'nama_cs'           => 'required|string',
            'adv_id'            => 'required',
            'sku_produk_id'     => 'required',
            'nama_produk'       => 'required',
            'qty_produk'        => 'required',
            'harga_produk'      => 'required',
            'customer'          => 'required',
            'no_hp'             => 'nullable',
            'alamat'            => 'nullable',
            'provinsi'          => 'required',
            'kabupaten'         => 'required',
            'kecamatan'         => 'required',
            'kelurahan'         => 'required',
            'kode_pos'          => 'required',
            'kode_promo_id'     => 'nullable',
            'pembayaran'        => 'required',
            'ongkir'            => 'nullable',
            'diskon_ongkir'     => 'nullable',
            'admin_cod'         => 'nullable',
            'diskon_admin_cod'  => 'nullable',
            'ekpedisi'          => 'required',
            'tanggal_tf'        => 'nullable|date_format:d-m-Y H:i',
            'total_pembayaran'  => 'required',
            'bukti_tf'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'no_resi'           => 'nullable'
        ]);

        DB::transaction(function () use ($request) {
            // Format angka
            $harga_produk      = str_replace('.', '', $request->harga_produk);
            $ongkir            = str_replace('.', '', $request->ongkir);
            $diskon_ongkir     = str_replace('.', '', $request->diskon_ongkir);
            $admin_cod         = str_replace('.', '', $request->admin_cod);
            $diskon_admin_cod  = str_replace('.', '', $request->diskon_admin_cod);
            $total_pembayaran  = str_replace('.', '', $request->total_pembayaran);

            // Generate kode_order
            $tanggal        = Carbon::createFromFormat('d-m-Y H:i', $request->tanggal);
            $tahun          = $tanggal->format('y');
            $bulan          = $tanggal->format('n');
            $bulan_romawi   = $this->convertToRoman($bulan);
            $kode_gudang    = $request->lok_gudang === 'jakarta' ? 'K' : 'S';
            $prefix         = "QKS/AKS/{$kode_gudang}/{$tahun}/{$bulan_romawi}";

            // Ambil urutan terakhir
            $lastOrder = Order::where('kode', 'like', "{$prefix}-%")
                ->orderBy('kode', 'desc')
                ->lockForUpdate()
                ->first();

            if ($lastOrder && preg_match('/-(\d{4})$/', $lastOrder->kode, $match)) {
                $lastNumber = (int)$match[1];
                $urutan = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $urutan = '0001';
            }

            $kode = "{$prefix}-{$urutan}";

            // Simpan ke DB
            Order::create([
                'tanggal'        => $request->tanggal
                        ? \Carbon\Carbon::createFromFormat('d-m-Y H:i', $request->tanggal)->format('Y-m-d H:i:s')
                        : null,
                'kode'              => $kode,
                'lok_gudang'        => $request->lok_gudang,
                'nama_cs'           => $request->nama_cs,
                'adv_id'            => $request->adv_id,
                'sku_produk_id'     => $request->sku_produk_id,
                'nama_produk'       => $request->nama_produk,
                'qty_produk'        => $request->qty_produk,
                'harga_produk'      => $harga_produk,
                'customer'          => $request->customer,
                'no_hp'             => $request->no_hp,
                'alamat'            => $request->alamat,
                'provinsi'          => $request->provinsi,
                'kabupaten'         => $request->kabupaten,
                'kecamatan'         => $request->kecamatan,
                'kelurahan'         => $request->kelurahan,
                'kode_pos'          => $request->kode_pos,
                'kode_promo_id'     => $request->kode_promo_id,
                'pembayaran'        => $request->pembayaran,
                'tanggal_tf'        => $request->tanggal_tf
                                        ? \Carbon\Carbon::createFromFormat('d-m-Y H:i', $request->tanggal_tf)->format('Y-m-d H:i:s')
                                        : null,
                'ongkir'            => $ongkir,
                'diskon_ongkir'     => $diskon_ongkir,
                'admin_cod'         => $admin_cod,
                'diskon_admin_cod'  => $diskon_admin_cod,
                'ekpedisi'          => $request->ekpedisi,
                'total_pembayaran'  => $total_pembayaran,
                'bukti_tf'          => $request->hasFile('bukti_tf') 
                                        ? $request->file('bukti_tf')->store('bukti_tf', 'public') 
                                        : null,
                'no_resi'           => $request->no_resi,
                'status_approval_id' => 1 // default status: Pending
            ]);
        });

        Alert::success('Berhasil', 'Data Order berhasil disimpan');
        return redirect()->route('admin.data.order.index');
    }


    private function convertToRoman($number)
    {
        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return $romawi[$number] ?? '';
    }


    public function edit($id)
    {
        $title = 'Edit Data Order';
        $order = Order::findOrFail($id);
        if ($order->status_approval_id !== 1) {
            Alert::error('Ditolak', 'Data tidak bisa diedit karena status bukan Pending');
            return redirect()->route('admin.data.order.index');
        }
        $kodePromo = MasterPromo::all();
        $sku = MasterSku::all();
        $adv = MasterAdv::all();
        return view('data.order.edit', compact('title', 'order', 'kodePromo', 'sku', 'adv'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date_format:d-m-Y',
            'lok_gudang' => 'required',
            'nama_cs' => 'required',
            'adv_id' => 'nullable',
            'sku_produk_id' => 'required',
            'nama_produk' => 'required',
            'qty_produk' => 'required|numeric',
            'harga_produk' => 'required',
            'customer' => 'required',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'kode_pos' => 'required',
            'kode_promo_id' => 'nullable',
            'pembayaran' => 'required',
            'ongkir' => 'nullable',
            'diskon_ongkir' => 'nullable',
            'admin_cod' => 'nullable',
            'diskon_admin_cod' => 'nullable',
            'ekpedisi' => 'required',
            'tanggal_tf' => 'nullable|date_format:d-m-Y',
            'total_pembayaran' => 'required',
            'bukti_tf' => 'nullable|file',
        ]);

        $order = Order::findOrFail($id);
        if (strcasecmp($order->status_approval, 'Selesai Dicek') === 0) {
            Alert::error('Ditolak', 'Data sudah dicek dan tidak bisa diubah');
            return redirect()->route('admin.data.order.index');
        }

        $order->update([
            'tanggal' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
            'lok_gudang' => $request->lok_gudang,
            'nama_cs' => $request->nama_cs,
            'adv_id' => $request->adv_id,
            'sku_produk_id' => $request->sku_produk_id,
            'nama_produk' => $request->nama_produk,
            'qty_produk' => $request->qty_produk,
            'harga_produk' => str_replace('.', '', $request->harga_produk),
            'customer' => $request->customer,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'kode_pos' => $request->kode_pos,
            'kode_promo_id' => $request->kode_promo_id,
            'pembayaran' => $request->pembayaran,
            'tanggal_tf' => $request->tanggal_tf 
                ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_tf)->format('Y-m-d') 
                : null,
            'ongkir' => str_replace('.', '', $request->ongkir),
            'diskon_ongkir' => str_replace('.', '', $request->diskon_ongkir),
            'admin_cod' => str_replace('.', '', $request->admin_cod),
            'diskon_admin_cod' => str_replace('.', '', $request->diskon_admin_cod),
            'ekpedisi' => $request->ekpedisi,
            'total_pembayaran' => str_replace('.', '', $request->total_pembayaran),
            'bukti_tf' => $request->hasFile('bukti_tf') 
                ? $request->file('bukti_tf')->store('bukti_tf', 'public') 
                : $order->bukti_tf,
            'no_resi'           => $request->no_resi,
        ]);

        Alert::success('Berhasil', 'Data Order berhasil diperbarui');
        return redirect()->route('admin.data.order.index');
    }

    public function show($id)
    {
        $order = Order::with('promo', 'sku')->findOrFail($id);
        $title = 'Detail Order';

        return view('data.order.show', compact('order', 'title'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        //pengecekan status_approval
        if (strcasecmp($order->status_approval, 'Selesai Dicek') === 0) {
            return response()->json(['success' => false, 'message' => 'Data sudah dicek dan tidak bisa dihapus']);
        }
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function exportExcel()
    {
        return Excel::download(new OrderExport, 'data-order.xlsx');
    }
}