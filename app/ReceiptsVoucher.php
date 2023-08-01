<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class ReceiptsVoucher extends Model
{
    use HasFactory;
    protected $table = 'sm_receipts_voucher';

    protected $fillable = [ 
        'buy_ledger_id',
        'to_ledger_id',
        'society_id',
        'added_user_id',
        'amount',
        'submit_date',
        'narration',
        'serial_number',
        'status'
    ];

    public function buyLedger()
    {
        return $this->belongsTo('App\Ledger', 'buy_ledger_id', 'id');
    }

    public function toLedger()
    {
        return $this->belongsTo('App\Ledger', 'to_ledger_id', 'id');
    }

    public static function getReceiptsVoucher()
    {
        $recordes = DB::table('sm_receipts_voucher')
                    ->leftjoin('sm_ledger as by_ledger', 'sm_receipts_voucher.buy_ledger_id', '=', 'by_ledger.id')
                    ->leftjoin('sm_ledger as to_ledger', 'sm_receipts_voucher.to_ledger_id', '=', 'to_ledger.id')
                    ->leftjoin('sm_society_register', 'sm_receipts_voucher.society_id', '=', 'sm_society_register.id')
                    ->leftjoin('sm_society_users', 'sm_receipts_voucher.added_user_id', '=', 'sm_society_users.id')
                    ->select('by_ledger.name as by_ledger_name', 'by_ledger.wing_flat_no as by_ledger_wing_flat_no', 'to_ledger.name as to_ledger_name', 'to_ledger.wing_flat_no as to_ledger_wing_flat_no', 'sm_society_register.society_name', 'sm_society_users.s_user_name', 'sm_receipts_voucher.amount', 'sm_receipts_voucher.submit_date', 'sm_receipts_voucher.narration', 'sm_receipts_voucher.serial_number', 'sm_receipts_voucher.status')
                    ->where('sm_receipts_voucher.society_id', Auth::user()->society_id)
                    ->orderBy('submit_date', 'asc')
                    ->get();

        $data = array();
        foreach($recordes as $k => $value)
        {
            $data[$k]['by_ledger_name'] = (!empty($value->by_ledger_wing_flat_no)) ? $value->by_ledger_wing_flat_no.' - '.$value->by_ledger_name : $value->by_ledger_name;
            $data[$k]['to_ledger_name'] =  (!empty($value->to_ledger_wing_flat_no)) ? $value->to_ledger_wing_flat_no.' - '.$value->to_ledger_name : $value->to_ledger_name;
            $data[$k]['society_name'] = $value->society_name;
            $data[$k]['s_user_name'] = $value->s_user_name;
            $data[$k]['amount'] = $value->amount;
            $data[$k]['submit_date'] = $value->submit_date;
            $data[$k]['narration'] = $value->narration;
            $data[$k]['serial_number'] = $value->serial_number;
            $data[$k]['status'] = $value->status;
        }
        $data1  = (object)$data;
        return $data1;
    } 
}
