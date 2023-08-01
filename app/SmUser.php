<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmUser extends Model
{
    use HasFactory;

    protected $table = 'sm_users';

    protected $fillable = [ 
        'society_id',
        's_user_name',
        's_user_email',
        's_user_mobile_number',
        's_user_wp_number',
        'password',
        'password_md5',
        'user_photo',
        's_user__status',
        'created_by',
        'updated_by'
    ];
}
