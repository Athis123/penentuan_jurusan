<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllOrderMultiSheetExport implements WithMultipleSheets
{
    protected $start, $end;

    public function __construct($start = null, $end = null)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function sheets(): array
    {
        return [
            new OrderCSExport($this->start, $this->end),
            new OrderCRMExport($this->start, $this->end),
        ];
    }

}
