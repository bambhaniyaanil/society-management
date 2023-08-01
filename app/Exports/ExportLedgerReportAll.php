<?php

namespace App\Exports;

use App\SociatyAccountEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Session;
use App\Ledger;

class ExportLedgerReportAll implements FromCollection, WithCustomCsvSettings, WithHeadings, WithStyles
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headings(): array
    {
        return ["Date", "Particulars", "Narration", "Voucher Type", "Bank Date", "Debit", "Credit"];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(SociatyAccountEntry::getLedgerReportAll());
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]]
        ];
    }
}
