<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Order;
use App\Models\Data\RepeatOrder;
use App\Models\Master\MasterPromo;
use App\Models\Master\MasterSku;
use App\Models\Master\MasterAdv;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Master\MasterStatusApproval;
use DataTables;
use Carbon\Carbon;
use App\Exports\AllOrderMultiSheetExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrderResiStatusImport;

class DataInputerController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // === Data Order CRM ===
            if ($request->has('type') && $request->type === 'crm') {
                $data = RepeatOrder::orderBy('tanggal', 'desc');

                if ($request->filled('daterange')) {
                    [$start, $end] = explode(' - ', $request->daterange);
                    $start = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
                    $end = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();
                    $data->whereBetween('created_at', [$start, $end]);
                }

                if ($request->filled('status_approval')) {
                    $data->where('status_approval_id', $request->status_approval);
                }

                if ($request->filled('pembayaran')) {
                    $data->where('pembayaran', $request->pembayaran);
                }

                return DataTables::of($data)
                    ->addColumn('tanggal', fn($item) => Carbon::parse($item->tanggal)->format('d-m-Y'))
                    ->addColumn('harga_produk', fn($item) => number_format($item->harga_produk, 0, ',', '.'))
                    ->addColumn('ongkir', fn($item) => number_format($item->ongkir, 0, ',', '.'))
                    ->addColumn('diskon_ongkir', fn($item) => number_format($item->diskon_ongkir, 0, ',', '.'))
                    ->addColumn('admin_cod', fn($item) => number_format($item->admin_cod, 0, ',', '.'))
                    ->addColumn('diskon_admin_cod', fn($item) => number_format($item->diskon_admin_cod, 0, ',', '.'))
                    ->addColumn('total_pembayaran', fn($item) => '<b>' . number_format($item->total_pembayaran, 0, ',', '.') . '</b>')
                    ->addColumn('status_approval_id', function ($item) {
                        $status = $item->statusApproval->status ?? 'Tidak Ada';
                        $color = $item->statusApproval->color ?? '#6c757d'; // default abu-abu kalau tidak ada

                        return '<span class="badge text-white" style="background-color:' . $color . '">' . $status . '</span>';
                    })
                    ->addColumn('aksi', function ($item) {
                        $user = auth()->user();
                        $canApprove = $user->hasRole('admin') || 
                            (strtolower($user->tim) === 'inputer');
                        $statusList = MasterStatusApproval::all();
                        return view('layouts.partials.aksi-inputer-repeat', [
                            'detailUrl' => route('admin.data.inputer.showCrm', $item->id),
                            'repeatapproveUrl' => route('admin.data.inputer.repeat_order.approve', $item->id),
                            'repeatunapproveUrl' => route('admin.data.inputer.repeat_order.unapprove', $item->id),
                            'canApprove' => $canApprove,
                            'statusApproval' => $item->status_approval_id,
                            'pembayaran' => $item->pembayaran,
                            'statusList' => $statusList,
                        ])->render();
                    })
                    ->rawColumns(['aksi', 'total_pembayaran', 'status_approval_id'])
                    ->make();
            }

            // === Data Order CS ===
            $data_cs = Order::orderBy('tanggal', 'desc');

            if ($request->filled('daterange')) {
                [$start, $end] = explode(' - ', $request->daterange);
                $start = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
                $end = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();
                $data_cs->whereBetween('created_at', [$start, $end]);
            }

            if ($request->filled('status_approval')) {
                $data_cs->where('status_approval_id', $request->status_approval);
            }

            if ($request->filled('pembayaran')) {
                $data_cs->where('pembayaran', $request->pembayaran);
            }

            return DataTables::of($data_cs)
                ->addColumn('tanggal', fn($item) => Carbon::parse($item->tanggal)->format('d-m-Y'))
                ->addColumn('harga_produk', fn($item) => number_format($item->harga_produk, 0, ',', '.'))
                ->addColumn('ongkir', fn($item) => number_format($item->ongkir, 0, ',', '.'))
                ->addColumn('diskon_ongkir', fn($item) => number_format($item->diskon_ongkir, 0, ',', '.'))
                ->addColumn('admin_cod', fn($item) => number_format($item->admin_cod, 0, ',', '.'))
                ->addColumn('diskon_admin_cod', fn($item) => number_format($item->diskon_admin_cod, 0, ',', '.'))
                ->addColumn('total_pembayaran', fn($item) => '<b>' . number_format($item->total_pembayaran, 0, ',', '.') . '</b>')
                ->addColumn('status_approval_id', function ($item) {
                    $status = $item->statusApproval->status ?? 'Tidak Ada';
                    $color = $item->statusApproval->color ?? '#6c757d'; // default abu-abu kalau tidak ada

                    return '<span class="badge text-white" style="background-color:' . $color . '">' . $status . '</span>';
                })
                ->addColumn('aksi', function ($item) {
                    $user = auth()->user();
                    $canApprove = $user->hasRole('admin') || 
                        (strtolower($user->tim) === 'inputer');
                    $statusList = MasterStatusApproval::all();

                        return view('layouts.partials.aksi-inputer-order', [
                            'detailUrl' => route('admin.data.inputer.showCs', $item->id),
                            'approveUrl' => route('admin.data.inputer.approve', $item->id),
                            'unapproveUrl' => route('admin.data.inputer.unapprove', $item->id),
                            'canApprove' => $canApprove,
                            'statusApproval' => $item->status_approval_id,
                            'statusList' => $statusList,
                            'pembayaran' => $item->pembayaran,
                        ])->render();
                })
                ->rawColumns(['aksi', 'total_pembayaran', 'status_approval_id'])
                ->make();
        }

        $title = 'Data Inputer';
        $statusList = MasterStatusApproval::all();
        return view('data.inputer.index', compact('title', 'statusList'));
    }

    public function approveOrder(Request $request, $id)
    {
        $request->validate([
            'status_approval_id' => 'required|exists:master_status_approval,id',
        ]);

        $user = auth()->user();
        if (!$user->hasRole('admin') && (!$user->tim || strtolower($user->tim) !== 'inputer')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk menyetujui order ini.');
            return redirect()->back();
        }

        $order = Order::findOrFail($id);
        $order->status_approval_id = $request->status_approval_id;
        $order->save();

        Alert::success('Berhasil', 'Status order diperbarui.');
        return redirect()->back();
    }

    public function unapproveOrder($id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && (!$user->tim || strtolower($user->tim) !== 'inputer')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk membatalkan approve order ini.');
            return redirect()->back();
        }

        $order = Order::findOrFail($id);
        $order->status_approval_id = 1;
        $order->save();

        Alert::success('Berhasil', 'Status order berhasil dibatalkan');
        return redirect()->back();
    }

    public function approveRepeatOrder(Request $request, $id)
    {
        $request->validate([
            'status_approval_id' => 'required|exists:master_status_approval,id',
        ]);

        $user = auth()->user();
        if (!$user->hasRole('admin') && (!$user->tim || strtolower($user->tim) !== 'inputer')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk menyetujui order ini.');
            return redirect()->back();
        }

        $order = RepeatOrder::findOrFail($id);
        $order->status_approval_id = $request->status_approval_id;
        $order->save();

        Alert::success('Berhasil', 'Status order diperbarui.');
        return redirect()->back();
    }

    public function unapproveRepeatOrder($id)
    {
        $user = auth()->user();

        if (
            !$user->hasRole('admin') &&
            (!$user->tim || strtolower($user->tim) !== 'inputer')
        ) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk membatalkan approve order ini.');
            return redirect()->back();
        }

        $order = RepeatOrder::findOrFail($id);
        $order->status_approval_id = 1;
        $order->save();

        Alert::success('Berhasil', 'Status order berhasil dibatalkan');
        return redirect()->back();
    }

        public function showCs($id)
    {
        $order = Order::with('promo', 'sku')->findOrFail($id);
        $title = 'Detail Order CS';

        return view('data.inputer.showCs', compact('order', 'title'));
    }

    public function showCrm($id)
    {
        $repeadorder = RepeatOrder::with('promo', 'sku')->findOrFail($id);
        $title = 'Detail Order CRM';

        return view('data.inputer.showCrm', compact('repeadorder', 'title'));
    }

    public function exportExcel(Request $request)
    {
        $daterange = $request->input('daterange');
        $type = $request->input('type', 'all');
        $start = $end = null;
        $statusApproval = $request->input('status_approval');
        $pembayaran = $request->input('pembayaran');

        if ($daterange) {
            [$start, $end] = explode(' - ', $daterange);
            $start = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
            $end = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();
        }

        switch ($type) {
            case 'cs':
                $export = new \App\Exports\OrderCSExport($start, $end, $statusApproval, $pembayaran);
                $filename = 'Data Order CS.xlsx';
                break;
            case 'crm':
                $export = new \App\Exports\OrderCRMExport($start, $end, $statusApproval, $pembayaran);
                $filename = 'Data Order CRM.xlsx';
                break;
            default:
                $export = new \App\Exports\AllOrderMultiSheetExport($start, $end, $statusApproval, $pembayaran);
                $filename = 'Data Order CS dan CRM.xlsx';
                break;
        }

        return Excel::download($export, $filename);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new OrderResiStatusImport, $request->file('file'));
            Alert::success('Sukses', 'File berhasil diimport dan data diperbarui.');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->back();
    }
}