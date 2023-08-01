<?php

namespace App\Exports;

use App\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportLedger implements FromCollection, WithCustomCsvSettings, WithHeadings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headings(): array
    {
        return ["Socity", "User Name", "Under Group", "Name", "Wing Flat No", "Area Sq Mtr", "Area Sq Ft", "Contact Number", "Whats App Number", "Email ID", "Pan Card Number", "Adhar Number", "GST Number", "Reside Address", "Correspondence Address", "Area/Locality", "City/District", "State", "Pin Code", "Country", "Registartion Date", "Opening Balance Debit", "Opning Balance Creadit", "Status"];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(Ledger::getLedger());
    }
}
