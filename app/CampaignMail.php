<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignMail extends Model
{
    use HasFactory;

    protected $table = 'sm_campaign_mail';

    protected $fillable = [ 
        'id',
        'type',
        'ids',
        'status',
        'created_at',
        'updated_at'
    ];
}
