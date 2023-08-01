<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $table = 'sm_role_has_permission';
    public $timestamps = false;
    public function society()
    {
        return $this->belongsTo('App\Society', 'society_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo('App\Roles', 'role_id', 'id');
    }
}
