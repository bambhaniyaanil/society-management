<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptsForm extends Model
{
    use HasFactory;

    protected $table = 'sm_receipts_form';

    public function toLedger()
    {
        return $this->belongsTo('App\Ledger', 'ledger_id', 'id');
    }
}
