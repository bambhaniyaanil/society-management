<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'sm_ledger';

    protected $fillable = [      
        'user_id',
        'society_id',
        'under_group',
        'name',
        'wing_flat_no',
        'area_sq_mtr',
        'area_sq_ft',
        'contact_number',
        'whats_app_number',
        'email_id',
        'pancard_number',
        'adhar_number',
        'gst_number',
        'reside_address',
        'correspondence_address',
        'area_locality',
        'city_district',
        'state',
        'pin_code',
        'country',
        'registration_date',
        'opning_balance_debit',
        'opning_balance_credit',
        'status'
    ];

    public static function getLedger()
    {
        $recordes = DB::table('sm_ledger')
                    ->leftjoin('sm_group_creations', 'sm_ledger.under_group', '=', 'sm_group_creations.id')
                    ->leftjoin('sm_society_register', 'sm_ledger.society_id', '=', 'sm_society_register.id')
                    ->leftjoin('sm_society_users', 'sm_ledger.user_id', '=', 'sm_society_users.id')
                    ->select('sm_society_register.society_name', 'sm_society_users.s_user_name', 'sm_group_creations.name as group_name', 'sm_ledger.name', 'sm_ledger.wing_flat_no', 'sm_ledger.area_sq_mtr as area_sq_mtr', 'sm_ledger.area_sq_ft as area_sq_ft', 'sm_ledger.contact_number', 'sm_ledger.whats_app_number', 'sm_ledger.email_id', 'sm_ledger.pancard_number', 'sm_ledger.adhar_number', 'sm_ledger.gst_number', 'sm_ledger.reside_address', 'sm_ledger.correspondence_address', 'sm_ledger.area_locality', 'sm_ledger.city_district', 'sm_ledger.state', 'sm_ledger.pin_code', 'sm_ledger.country', 'sm_ledger.registration_date', 'sm_ledger.opning_balance_debit', 'sm_ledger.opning_balance_credit', 'sm_ledger.status')
                    ->where('sm_ledger.society_id', Auth::user()->society_id)
                    ->orderBy('registration_date', 'asc')
                    ->get();
        
        $data = array();
        foreach($recordes as $k => $value)
        {
            $data[$k]['society_name'] = $value->society_name;
            $data[$k]['s_user_name'] = $value->s_user_name;
            $data[$k]['group_name'] = $value->group_name;
            $data[$k]['name'] = (!empty($value->wing_flat_no)) ? $value->wing_flat_no.' - '.$value->name : $value->name;
            $data[$k]['wing_flat_no'] = $value->wing_flat_no;
            $data[$k]['area_sq_mtr'] = $value->area_sq_mtr;
            $data[$k]['area_sq_ft'] = $value->area_sq_ft;
            $data[$k]['contact_number'] = $value->contact_number;
            $data[$k]['whats_app_number'] = $value->whats_app_number;
            $data[$k]['email_id'] = $value->email_id;
            $data[$k]['pancard_number'] = $value->pancard_number;
            $data[$k]['adhar_number'] = $value->adhar_number;
            $data[$k]['gst_number'] = $value->gst_number;
            $data[$k]['reside_address'] = $value->reside_address;
            $data[$k]['correspondence_address'] = $value->correspondence_address;
            $data[$k]['area_locality'] = $value->area_locality;
            $data[$k]['city_district'] = $value->city_district;
            $data[$k]['state'] = $value->state;
            $data[$k]['pin_code'] = $value->pin_code;
            $data[$k]['country'] = $value->country;
            $data[$k]['registration_date'] = $value->registration_date;
            $data[$k]['opning_balance_debit'] = $value->opning_balance_debit;
            $data[$k]['opning_balance_credit'] = $value->opning_balance_credit;
            $data[$k]['status'] = $value->status;
        }
        $data1  = (object)$data;
        return $data1;
    }
}
