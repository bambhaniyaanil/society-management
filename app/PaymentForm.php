<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentForm extends Model
{
    use HasFactory;

    protected $table = 'sm_payment_form';

    public function fromLedger()
    {
        return $this->belongsTo('App\Ledger', 'ledger_id', 'id');
    }
}
