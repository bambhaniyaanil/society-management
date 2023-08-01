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

class ExportLedgerReport implements FromCollection, WithCustomCsvSettings, WithHeadings, WithStyles
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headings(): array
    {
        $ledger_id = Session::get('rledger_id');
        $ledger = Ledger::where('id', $ledger_id)->first();
        $name = (!empty($ledger->wing_flat_no) ? $ledger->wing_flat_no.' - '.$ledger->name : $ledger->name);
        return [[$name],["Date", "Particulars", "Narration", "Voucher Type", "Bank Date", "Debit", "Credit"]];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(SociatyAccountEntry::getLedgerReport());
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
        ];
    }
}
