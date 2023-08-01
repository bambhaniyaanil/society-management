<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupType extends Model
{
    use HasFactory;
    protected $table = 'sm_group_type';

    protected $fillable = [ 
        'society_id',
        'name',
        'status'
    ];
}
