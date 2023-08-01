<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $table = 'sm_society_flats';

    public function society()
    {
        return $this->belongsTo('App\Society', 'society_id', 'id');
    }
}
