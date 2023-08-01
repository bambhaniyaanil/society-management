<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingOwnerDetails extends Model
{
    use HasFactory;
    protected $table = 'sm_parking_owner_details';
    // public function society()
    // {
    //     return $this->belongsTo('App\Society', 'society_id', 'id');
    // }
}
