<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailIntegrations extends Model
{
    use HasFactory;

    protected $table = 'sm_email_integrations';

    public function society()
    {
        return $this->belongsTo('App\Society', 'society_id', 'id');
    }
}
