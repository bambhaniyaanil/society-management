<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ParkingRegister extends Model
{
    use HasFactory;
    protected $table = 'sm_parking_register';

    public static function getParkingRegisterAll()
    {
        $data = ParkingRegister::where('society_id', Auth::user()->society_id)->where('status', 1)->get(['sticker_no', 'vehicle_type', 'vehicle_number', 'flat_no', 'tenat_name', 'owner_name', 'contact_number']);
        return $data;
    }
}


