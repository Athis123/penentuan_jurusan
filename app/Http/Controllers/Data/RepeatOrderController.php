<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\RepeatOrder;
use App\Models\Master\MasterPromo;
use App\Models\Master\MasterSku;
use App\Models\Master\MasterAdv;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class RepeatOrderController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = RepeatOrder::orderBy('tanggal', 'desc');

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
                    $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.repeat_order.show', $item->id) . '"><i class="fas fa-eye mr-2"></i>Detail</a>';

                    // Edit dan Delete jika belum selesai dicek
                    if ( strcasecmp($item->status_approval, 'Selesai Dicek') !== 0 &&
                        ($user->hasRole('admin') || ($user->tim && strtolower($user->tim) === 'crm'))
                    ) {
                        $dropdown .= '<a class="dropdown-item" href="' . route('admin.data.repeat_order.edit', $item->id) . '"><i class="far fa-edit mr-2"></i>Edit</a>';

                        $dropdown .= '<button class="dropdown-item btn-delete text-danger"
                            data-id="' . $item->id . '"
                            data-url="' . route('admin.data.repeat_order.destroy', $item->id) . '">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus</button>';
                    }

                    $dropdown .= '</div></div>';

                    return $dropdown;
                })
                ->rawColumns(['aksi', 'total_pembayaran', 'status_approval'])
                ->make();
        }

        $title = 'Data Order CRM';
        return view('data.repeat_order.index', compact('title'));
    }

    public function approve($id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && !$user->hasRole('finance')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk menyetujui order ini.');
            return redirect()->back();
        }

        $order = Order::findOrFail($id);
        $order->status_approval = 'Selesai Dicek';
        $order->save();

        Alert::success('Berhasil', 'Status order diperbarui');
        return redirect()->back();
    }

    public function unapprove($id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && (!$user->tim || strtolower($user->tim) !== 'finance')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk membatalkan approve order ini.');
            return redirect()->back();
        }

        $order = Order::findOrFail($id);

        if (strcasecmp($order->status_approval, 'Selesai Dicek') !== 0) {
            Alert::error('Gagal', 'Order belum di-approve, tidak bisa dibatalkan.');
            return redirect()->back();
        }

        $order->status_approval = 'Pending';
        $order->save();

        Alert::success('Berhasil', 'Status order berhasil dibatalkan');
        return redirect()->back();
    }

    public function create()
    {
        $title = 'Create Data Order CRM';
        $kodePromo = MasterPromo::all();
        $sku = MasterSku::all();
        $adv = MasterAdv::all();

        return view('data.repeat_order.create', compact('title', 'kodePromo', 'sku', 'adv'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'       => 'required|date_format:d-m-Y',
            'lok_gudang'    => 'required|string',
            'nama_crm'      => 'required|string',
            'adv_id'        => 'required',
            'sku_produk_id' => 'required',
            'nama_produk'   => 'required',
            'qty_produk'    => 'required',
            'harga_produk'  => 'required',
            'customer'      => 'required',
            'no_hp'         => 'nullable',
            'alamat'        => 'nullable',
            'provinsi'      => 'required',
            'kabupaten'     => 'required',
            'kecamatan'     => 'required',
            'kelurahan'     => 'required',
            'kode_pos'      => 'required',
            'kode_promo_id' => 'nullable',
            'pembayaran'    => 'required',
            'ongkir'        => 'nullable',
            'diskon_ongkir' => 'nullable',
            'admin_cod'     => 'nullable',
            'diskon_admin_cod' => 'nullable',
            'ekpedisi'         => 'required',
            'tanggal_tf'       => 'nullable|date_format:d-m-Y',
            'total_pembayaran' => 'required',
            'bukti_tf'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'no_resi'          => 'nullable'
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
            $tanggal        = Carbon::createFromFormat('d-m-Y', $request->tanggal);
            $tahun          = $tanggal->format('y');
            $bulan          = $tanggal->format('n');
            $bulan_romawi   = $this->convertToRoman($bulan);
            $kode_gudang    = $request->lok_gudang === 'jakarta' ? 'K' : 'S';
            $prefix         = "QKS/CRM/{$kode_gudang}/{$tahun}/{$bulan_romawi}";

            // Ambil urutan terakhir
            $lastOrder = RepeatOrder::where('kode', 'like', "{$prefix}-%")
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
            RepeatOrder::create([
                'tanggal'           => $tanggal->format('Y-m-d'),
                'kode'              => $kode,
                'lok_gudang'        => $request->lok_gudang,
                'nama_crm'           => $request->nama_crm,
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
                                        ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_tf)->format('Y-m-d')
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
                'status_approval_id' => 1
            ]);
        });

        Alert::success('Berhasil', 'Data Order berhasil disimpan');
        return redirect()->route('admin.data.repeat_order.index');
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
        $title = 'Edit Data Order CRM';
        $repeadorder = RepeatOrder::findOrFail($id);
        if ($order->status_approval_id !== 1) {
            Alert::error('Ditolak', 'Data tidak bisa diedit karena status bukan Pending');
            return redirect()->route('admin.data.repeat_order.index');
        }
        $kodePromo = MasterPromo::all();
        $sku = MasterSku::all();
        $adv = MasterAdv::all();
        return view('data.repeat_order.edit', compact('title', 'repeadorder', 'kodePromo', 'sku', 'adv'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal'       => 'required|date_format:d-m-Y',
            'lok_gudang'    => 'required',
            'nama_crm'      => 'required',
            'adv_id'        => 'nullable',
            'sku_produk_id' => 'required',
            'nama_produk'   => 'required',
            'qty_produk'    => 'required|numeric',
            'harga_produk'  => 'required',
            'customer'      => 'required',
            'no_hp'         => 'nullable',
            'alamat'        => 'nullable',
            'provinsi'      => 'required',
            'kabupaten'     => 'required',
            'kecamatan'     => 'required',
            'kelurahan'     => 'required',
            'kode_pos'      => 'required',
            'kode_promo_id' => 'nullable',
            'pembayaran'    => 'required',
            'ongkir'        => 'nullable',
            'diskon_ongkir' => 'nullable',
            'admin_cod'     => 'nullable',
            'diskon_admin_cod' => 'nullable',
            'ekpedisi'      => 'required',
            'tanggal_tf'    => 'nullable|date_format:d-m-Y',
            'total_pembayaran' => 'required',
            'bukti_tf'      => 'nullable|file',
            'no_resi'       => 'nullable',
        ]);

        $repeat_order = RepeatOrder::findOrFail($id);
        if (strcasecmp($repeat_order->status_approval, 'Selesai Dicek') === 0) {
            Alert::error('Ditolak', 'Data sudah dicek dan tidak bisa diubah');
            return redirect()->route('admin.data.repeat_order.index');
        }

        $repeat_order->update([
            'tanggal' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
            'lok_gudang' => $request->lok_gudang,
            'nama_crm' => $request->nama_crm,
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
                : $repeat_order->bukti_tf,
            'no_resi'           => $request->no_resi,
        ]);

        Alert::success('Berhasil', 'Data Order berhasil diperbarui');
        return redirect()->route('admin.data.repeat_order.index');
    }

    public function show($id)
    {
        $repeadorder = RepeatOrder::with('promo', 'sku')->findOrFail($id);
        $title = 'Detail Order';

        return view('data.repeat_order.show', compact('repeadorder', 'title'));
    }

    public function destroy($id)
    {
        $repeat_order = RepeatOrder::findOrFail($id);
        //pengecekan status_approval
        if (strcasecmp($repeat_order->status_approval, 'Selesai Dicek') === 0) {
            return response()->json(['success' => false, 'message' => 'Data sudah dicek dan tidak bisa dihapus']);
        }
        $repeat_order->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function exportExcel()
    {
        return Excel::download(new RepeatOrderExport, 'data-repeat_order.xlsx');
    }
}