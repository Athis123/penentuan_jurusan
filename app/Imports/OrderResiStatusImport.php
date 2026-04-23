<?php

namespace App\Imports;

use App\Models\Data\Order;
use App\Models\Data\RepeatOrder;
use App\Models\Master\MasterStatusApproval;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrderResiStatusImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // cari di Order
            $order = Order::where('kode', $row['kode'])->first();

            // Kalau tidak ada, cek di RepeatOrder
            if (!$order) {
                $order = RepeatOrder::where('kode', $row['kode'])->first();
            }

            if ($order) {
                $status = MasterStatusApproval::where('status', $row['status'])->first();

                $order->nomor_resi = $row['nomor_resi'];
                if ($status) {
                    $order->status_approval_id = $status->id;
                }

                $order->save();
            }
        }
    }
}
