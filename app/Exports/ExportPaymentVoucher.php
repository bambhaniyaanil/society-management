<?php

namespace App\Exports;

use App\PaymentVoucher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPaymentVoucher implements FromCollection, WithCustomCsvSettings, WithHeadings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headings(): array
    {
        return ["By Ledger", "To Ledger", "Society", "Added User", "Amount", "Submit Date", "Narration", "Serial Number", "Status"];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(PaymentVoucher::getPaymentVoucher());
    }
}
