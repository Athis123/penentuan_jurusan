<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Order;
use App\Models\Data\RepeatOrder;
use App\Models\Master\MasterPromo;
use App\Models\Master\MasterSku;
use App\Models\Master\MasterAdv;
use App\Models\Master\MasterStatusApproval;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use Carbon\Carbon;
use App\Exports\AllOrderMultiSheetExport;
use Maatwebsite\Excel\Facades\Excel;

class FinanceController extends Controller
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
                    $data->where('status_approval', $request->status_approval);
                }

                if ($request->filled('pembayaran')) {
                    $data->where('pembayaran', $request->pembayaran);
                }

                return DataTables::of($data)
                    ->addColumn('harga_produk', fn($item) => number_format($item->harga_produk, 0, ',', '.'))
                    ->addColumn('ongkir', fn($item) => number_format($item->ongkir, 0, ',', '.'))
                    ->addColumn('diskon_ongkir', fn($item) => number_format($item->diskon_ongkir, 0, ',', '.'))
                    ->addColumn('admin_cod', fn($item) => number_format($item->admin_cod, 0, ',', '.'))
                    ->addColumn('diskon_admin_cod', fn($item) => number_format($item->diskon_admin_cod, 0, ',', '.'))
                    ->addColumn('total_pembayaran', fn($item) => '<b>' . number_format($item->total_pembayaran, 0, ',', '.') . '</b>')
                    ->addColumn('bukti_tf', function ($item) {
                        if ($item->bukti_tf) {
                            $url = asset('storage/' . $item->bukti_tf);
                            return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>';
                        } else {
                            return '<span class="text-muted">-</span>';
                        }
                    })
                    ->addColumn('status_approval_id', function ($item) {
                        $status = $item->statusApproval->status ?? 'Tidak Ada';
                        $color = $item->statusApproval->color ?? '#6c757d'; // default abu-abu kalau tidak ada

                        return '<span class="badge text-white" style="background-color:' . $color . '">' . $status . '</span>';
                    })
                    ->addColumn('aksi', function ($item) {
                        $user = auth()->user();
                        $canApprove = $user->hasRole('admin') || 
                            (strtolower($user->tim) === 'finance' && strtolower($item->pembayaran) === 'transfer');

                        return view('layouts.partials.aksi-finance-repeat', [
                            'detailUrl' => route('admin.data.finance.showCrm', $item->id),
                            'repeatapproveUrl' => route('admin.data.finance.repeat_order.approve', $item->id),
                            'repeatunapproveUrl' => route('admin.data.finance.repeat_order.unapprove', $item->id),
                            'canApprove' => $canApprove,
                            'statusApproval' => $item->status_approval_id,
                        ])->render();
                    })

                    ->rawColumns(['total_pembayaran', 'bukti_tf', 'status_approval_id', 'aksi'])
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
                $data_cs->where('status_approval', $request->status_approval);
            }

            if ($request->filled('pembayaran')) {
                $data_cs->where('pembayaran', $request->pembayaran);
            }

            return DataTables::of($data_cs)
                ->addColumn('harga_produk', fn($item) => number_format($item->harga_produk, 0, ',', '.'))
                ->addColumn('ongkir', fn($item) => number_format($item->ongkir, 0, ',', '.'))
                ->addColumn('diskon_ongkir', fn($item) => number_format($item->diskon_ongkir, 0, ',', '.'))
                ->addColumn('admin_cod', fn($item) => number_format($item->admin_cod, 0, ',', '.'))
                ->addColumn('diskon_admin_cod', fn($item) => number_format($item->diskon_admin_cod, 0, ',', '.'))
                ->addColumn('total_pembayaran', fn($item) => '<b>' . number_format($item->total_pembayaran, 0, ',', '.') . '</b>')
                ->addColumn('bukti_tf', function ($item) {
                    if ($item->bukti_tf) {
                        $url = asset('storage/' . $item->bukti_tf);
                        return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>';
                    } else {
                        return '<span class="text-muted">-</span>';
                    }
                })
                ->addColumn('status_approval_id', function ($item) {
                    $status = $item->statusApproval->status ?? 'Tidak Ada';
                    $color = $item->statusApproval->color ?? '#6c757d'; // default abu-abu kalau tidak ada

                    return '<span class="badge text-white" style="background-color:' . $color . '">' . $status . '</span>';
                })
                ->addColumn('aksi', function ($item) {
                    $user = auth()->user();
                    $canApprove = $user->hasRole('admin') || 
                        (strtolower($user->tim) === 'finance' && strtolower($item->pembayaran) === 'transfer');

                    return view('layouts.partials.aksi-finance-order', [
                        'detailUrl' => route('admin.data.finance.showCs', $item->id),
                        'approveUrl' => route('admin.data.finance.approve', $item->id),
                        'unapproveUrl' => route('admin.data.finance.unapprove', $item->id),
                        'canApprove' => $canApprove,
                        'statusApproval' => $item->status_approval_id,
                    ])->render();
                })

                ->rawColumns(['total_pembayaran', 'bukti_tf', 'status_approval_id', 'aksi'])
                ->make();
        }

        $title = 'Finance';
        return view('data.finance.index', compact('title'));
    }


    public function approveOrder($id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && (!$user->tim || strtolower($user->tim) !== 'finance')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk menyetujui order ini.');
            return redirect()->back();
        }

        $order = Order::findOrFail($id);

        if (strtolower($order->pembayaran) !== 'transfer') {
            Alert::error('Ditolak', 'Finance hanya boleh menyetujui order dengan pembayaran Transfer.');
            return redirect()->back();
        }

        // jika sudah disetujui
        if ($order->status_approval_id == 2) {
            Alert::warning('Tidak Diubah', 'Order ini sudah di-approve sebelumnya.');
            return redirect()->back();
        }

        $order->status_approval_id = 2;
        $order->save();

        Alert::success('Berhasil', 'Status order diperbarui');
        return redirect()->back();
    }

    public function unapproveOrder($id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && (!$user->tim || strtolower($user->tim) !== 'finance')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk membatalkan approve order ini.');
            return redirect()->back();
        }

        $order = Order::findOrFail($id);

        if (strtolower($user->tim) === 'finance' && strtolower($order->pembayaran) !== 'transfer') {
            Alert::error('Ditolak', 'Finance hanya boleh unapprove order dengan pembayaran Transfer.');
            return redirect()->back();
        }

        if ($order->status_approval_id != 2) {
            Alert::error('Gagal', 'Order belum di-approve, tidak bisa dibatalkan.');
            return redirect()->back();
        }

        $order->status_approval_id = 1;
        $order->save();

        Alert::success('Berhasil', 'Status order berhasil dibatalkan ke Pending');
        return redirect()->back();
    }

    public function approveRepeatOrder($id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && (!$user->tim || strtolower($user->tim) !== 'finance')) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk menyetujui repeat order ini.');
            return redirect()->back();
        }

        $order = RepeatOrder::findOrFail($id);

        if (strtolower($order->pembayaran) !== 'transfer') {
            Alert::error('Ditolak', 'Finance hanya boleh menyetujui repeat order dengan pembayaran Transfer.');
            return redirect()->back();
        }

        // jika sudah disetujui
        if ($order->status_approval_id == 2) {
            Alert::warning('Tidak Diubah', 'Repeat order ini sudah di-approve sebelumnya.');
            return redirect()->back();
        }

        $order->status_approval_id = 2;
        $order->save();

        Alert::success('Berhasil', 'Status repeat order diperbarui ke Data Valid');
        return redirect()->back();
    }

    public function unapproveRepeatOrder($id)
    {
        $user = auth()->user();

        if (
            !$user->hasRole('admin') &&
            (!$user->tim || strtolower($user->tim) !== 'finance')
        ) {
            Alert::error('Ditolak', 'Anda tidak memiliki akses untuk membatalkan approve repeat order ini.');
            return redirect()->back();
        }

        $order = RepeatOrder::findOrFail($id);

        if (strtolower($user->tim) === 'finance' && strtolower($order->pembayaran) !== 'transfer') {
            Alert::error('Ditolak', 'Finance hanya boleh unapprove repeat order dengan pembayaran Transfer.');
            return redirect()->back();
        }

        if ($order->status_approval_id != 2) {
            Alert::error('Gagal', 'Repeat order belum di-approve, tidak bisa dibatalkan.');
            return redirect()->back();
        }

        $order->status_approval_id = 1;
        $order->save();

        Alert::success('Berhasil', 'Status repeat order berhasil dibatalkan');
        return redirect()->back();
    }

    public function showCs($id)
    {
        $order = Order::with('promo', 'sku')->findOrFail($id);
        $title = 'Detail Order CS';

        return view('data.finance.showCs', compact('order', 'title'));
    }

    public function showCrm($id)
    {
        $repeadorder = RepeatOrder::with('promo', 'sku')->findOrFail($id);
        $title = 'Detail Order CRM';

        return view('data.finance.showCrm', compact('repeadorder', 'title'));
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

}