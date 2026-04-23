<?php
namespace App\Exports;

use App\Models\Data\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
        public function collection()
    {
        return Order::with('sku')->get();
    }

    public function map($order): array
    {
        return [
            $order->tanggal,
            $order->nama_cs,
            $order->nama_adv,
            $order->customer,
            $order->no_hp,
            $order->nama_produk,
            $order->sku ? $order->sku->nama_sku : '-',
            $order->qty_produk,
            $order->harga_produk,
            $order->pembayaran,
            number_format($order->total_pembayaran, 0, ',', '.'),
            $order->ekpedisi,
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama CS',
            'Advertiser',
            'Customer',
            'No HP',
            'Produk',
            'SKU Produk',
            'Qty',
            'Harga Produk',
            'Pembayaran',
            'Total Pembayaran',
            'Ekpedisi'
        ];
    }
}
