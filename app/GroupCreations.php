<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCreations extends Model
{
    use HasFactory;

    protected $table = 'sm_group_creations';

    protected $fillable = [ 
        'society_id',
        'name',
        'group_category_id',
        'status'
    ];

    public function groupCategory()
    {
        return $this->belongsTo('App\GroupCategory', 'group_category_id', 'id');
    }

    public function ledger()
    {
        return $this->hasMany('App\Ledger', 'under_group', 'id');
    }
}
