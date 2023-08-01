<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCategory extends Model
{
    use HasFactory;
    protected $table = 'sm_group_catagory';

    protected $fillable = [ 
        'society_id',
        'name',
        'group_type_id',
        'status'
    ];

    public function groupType()
    {
        return $this->belongsTo('App\GroupType', 'group_type_id', 'id');
    }
}
