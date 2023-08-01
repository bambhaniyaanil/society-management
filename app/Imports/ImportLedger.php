<?php

namespace App\Imports;

use App\Ledger;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
use App\GroupCreations;
use App\Rules\UnderGroupRule;
use App\Rules\LedgerNameRule;

class ImportLedger implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return[
            'under_group' => [
                'required', new UnderGroupRule()
            ],
            'name' => [
                'required', new LedgerNameRule('ledger', '')
            ]
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $group_creation = GroupCreations::where('name', $row['under_group'])->first();                
        $names = explode(' - ', $row['name']);
        if(count($names) == 2)
        {
            $name = preg_replace('/(\s?\&\s?)/', "&", $names[1]);
            $name = str_replace('&',',',$name);
        }         
        else{
            $name = preg_replace('/(\s?\&\s?)/', "&", $names[0]);
            $name = str_replace('&',',',$name);
        }
        return new Ledger([
            'user_id' => Auth::user()->id,
            'society_id' => Auth::user()->society_id,
            'under_group' => $group_creation->id,
            'name' => $name,
            'wing_flat_no' => $row['wing_flat_no'],
            'area_sq_mtr' => $row['area_sq_mtr'],
            'area_sq_ft' => $row['area_sq_ft'],
            'contact_number' => $row['contact_number'],
            'whats_app_number' => $row['whats_app_number'],
            'email_id' => $row['email_id'],
            'pancard_number' => $row['pan_card_number'],
            'adhar_number' => $row['adhar_number'],
            'gst_number' => $row['gst_number'],
            'reside_address' => $row['reside_address'],
            'correspondence_address' => $row['correspondence_address'],
            'area_locality' => $row['arealocality'],
            'city_district' => $row['citydistrict'],
            'state' => $row['state'],
            'pin_code' => $row['pin_code'],
            'country' => $row['country'],
            'registration_date' => date('Y-m-d', strtotime($row['registartion_date'])),
            'opning_balance_debit' => $row['opening_balance_debit'],
            'opning_balance_credit' => $row['opning_balance_creadit']
        ]);
    }
}
