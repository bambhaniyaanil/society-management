<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantRegister extends Model
{
    use HasFactory;
    protected $table = 'sm_tenant_register';
}
