<?php

namespace App\Exports;

use App\JournalVoucher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportJournalVoucher implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return collect(JournalVoucher::getJournalVoucher());
        // return JournalVoucher::all();
    }
}
