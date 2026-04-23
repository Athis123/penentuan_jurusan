<?php
namespace App\Exports;

use App\Models\Data\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class OrderCSExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithTitle
{
    protected $start, $end, $statusApproval, $pembayaran;

    public function __construct($start = null, $end = null, $statusApproval = null, $pembayaran = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->statusApproval = $statusApproval;
        $this->pembayaran = $pembayaran;
    }

    public function collection()
    {
        $query = Order::query();

        if ($this->start && $this->end) {
            $query->whereBetween('created_at', [$this->start, $this->end]);
        }
        if ($this->statusApproval) {
            $query->where('status_approval', $this->statusApproval);
        }
        if ($this->pembayaran) {
            $query->where('pembayaran', $this->pembayaran);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Order', 'Customer', 'No HP', 'Alamat', 'Produk', 'Qty',
            'Harga Produk', 'Jenis Pembayaran', 'Ongkir', 'Diskon Ongkir',
            'Biaya Admin', 'Diskon Biaya Admin', 'Total Pembayaran', 'Status'
        ];
    }

    public function map($item): array
    {
        $data = [
            $item->tanggal,
            $item->customer,
            $item->no_hp,
            $item->alamat,
            $item->nama_produk,
            $item->qty_produk,
            $item->harga_produk,
            $item->pembayaran,
            $item->ongkir,
            $item->diskon_ongkir,
            $item->admin_cod,
            $item->diskon_admin_cod,
            $item->total_pembayaran,
            $item->status_approval,
        ];

        // dd($data);

        return $data;
    }


    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER,                     // qty_produk (integer)
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,    // harga_produk
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,    // ongkir
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,    // diskon_ongkir
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,    // admin_cod
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,    // diskon_admin_cod
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,    // total_pembayaran
        ];
    }

    public function title(): string
    {
        return 'Data Order CS';
    }

}
