<?php

namespace App\Exports;

use App\TenantRegister;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTenantRegister implements FromCollection, WithCustomCsvSettings, WithHeadings, WithStyles
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headings(): array
    {
        return ["Flat No", "Tenant Name", "Permanent Address", "Native Address", "Kyc Detail", "Contact Number", "Period Start Date", "Period End Date"];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TenantRegister::all(['flat_no', 'tenant_name', 'permanent_address', 'native_address', 'kyc_detail', 'contact_number', 'period_start_date', 'period_end_date']);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]]
        ];
    }
}
