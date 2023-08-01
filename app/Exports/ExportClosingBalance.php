<?php

namespace App\Exports;

use App\SociatyAccountEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportClosingBalance implements FromCollection, WithCustomCsvSettings, WithHeadings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headings(): array
    {
        return ["Particulars", "Debit", "Credit", "Closing Balance"];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return SociatyAccountEntry::all();
        return collect(SociatyAccountEntry::getClosingBalance());
    }
}
