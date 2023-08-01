<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'sm_invoice';

    protected $fillable = [      
        'user_id',
        'society_id',
        'by_ledger',
        'to_ledger',
        'by_ledger_amount',
        'bill_no',
        'bill_period',
        'bill_date',
        'due_date',
        'narration',
        'arrears',
        'status',
        'serial_number'
    ];

    public function byLedger()
    {
        return $this->belongsTo('App\Ledger', 'by_ledger', 'id');
    }

    public static function getInvoice()
    {
        $recordes = DB::table('sm_invoice')
                    ->leftjoin('sm_ledger as by_ledger', 'sm_invoice.by_ledger', '=', 'by_ledger.id')
                    ->leftjoin('sm_society_register', 'sm_invoice.society_id', '=', 'sm_society_register.id')
                    ->leftjoin('sm_society_users', 'sm_invoice.user_id', '=', 'sm_society_users.id')
                    ->select('sm_society_users.s_user_name', 'sm_society_register.society_name', 'by_ledger.name as by_ledger_name', 'by_ledger.wing_flat_no as by_ledger_wing_flat_no', 'sm_invoice.by_ledger_amount', 'sm_invoice.bill_no', 'sm_invoice.bill_period', 'sm_invoice.bill_date', 'sm_invoice.due_date', 'sm_invoice.narration', 'sm_invoice.arrears')
                    ->where('sm_invoice.society_id', Auth::user()->society_id)
                    ->orderBy('bill_date', 'asc')
                    ->get();
        
        $data = array();
        foreach($recordes as $k => $value)
        {
            $data[$k]['s_user_name'] = $value->s_user_name;
            $data[$k]['society_name'] = $value->society_name;
            $data[$k]['by_ledger_name'] = (!empty($value->by_ledger_wing_flat_no)) ? $value->by_ledger_wing_flat_no.' - '.$value->by_ledger_name : $value->by_ledger_name;
            $data[$k]['by_ledger_amount'] = $value->by_ledger_amount;
            $data[$k]['bill_no'] = $value->bill_no;
            $data[$k]['bill_period'] = $value->bill_period;
            $data[$k]['bill_date'] = $value->bill_date;
            $data[$k]['due_date'] = $value->due_date;
            $data[$k]['narration'] = $value->narration;
            $data[$k]['arrears'] = $value->arrears;
        }
        $data1  = (object)$data;
        return $data1;
    }
}
