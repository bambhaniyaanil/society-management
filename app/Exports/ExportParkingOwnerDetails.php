<?php

namespace App\Exports;

use App\ParkingOwnerDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Auth;

class ExportParkingOwnerDetails implements FromCollection, WithCustomCsvSettings, WithHeadings, WithStyles
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headings(): array
    {
        return ["Flat No", "Parking No", "Owner Name", "Contact Number"];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ParkingOwnerDetails::where('society_id', Auth::user()->society_id)->where('status', 1)->get(['flat_no', 'parking_no', 'owner_name', 'contact_number']);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]]
        ];
    }
}
