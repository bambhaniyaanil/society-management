<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
use App\Ledger;

class SociatyAccountEntry extends Model
{
    use HasFactory;
    protected $table = 'sociaty_account_entry';

    protected $fillable = [ 
        'by_ledger_id',
        'to_ledger_id',
        'society_id',
        'added_user_id',
        'amount',
        'submit_date',
        'serial_number',
        'refrance_voucher_id',
        'voucher_type',
        'bank_date',
        'narration',
        'status'
    ];

    public function byledger()
    {
        return $this->belongsTo('App\Ledger', 'by_ledger_id', 'id');
    }

    public function toledger()
    {
        return $this->belongsTo('App\Ledger', 'to_ledger_id', 'id');
    }

    public static function getLedgerReport()
    {
        $from_date = Session::get('lrfrom_date');
        $to_date = Session::get('lrto_date');
        $ledger_id = Session::get('rledger_id');
        $vch_type = Session::get('rlvch_type');
        $debit_amount = 0; 
        $creadit_amount = 0;
        if((!empty($from_date) && !empty($to_date)) || !empty($ledger_id) || !empty($vch_type))
        {
            $records = SociatyAccountEntry::where('society_id', Auth::user()->society_id)
                ->where(function($que) use ($ledger_id){
                    $que->where('by_ledger_id', $ledger_id)
                        ->orWhere('to_ledger_id', $ledger_id);
                })
                ->where(function($que) use ($from_date, $to_date){
                    if($from_date != '' && $to_date != '')
                    {
                        $que->whereBetween('submit_date', [$from_date, $to_date]);
                    }
                })
                ->where(function($q) use ($vch_type){
                    if($vch_type != '')
                    {
                        $q->where('voucher_type', $vch_type);
                    }
                })
                ->orderBy('submit_date', 'asc')
                ->get();
            $data = array();
            $i = 0;
            foreach($records as $k => $value)
            {
                $i = $k;
                $data[$k]['submit_date'] = date('d-m-Y', strtotime($value->submit_date));
                if($value->by_ledger_id != $ledger_id)
                {
                    $data[$k]['ledger'] = (!empty($value->byledger->wing_flat_no) ? $value->byledger->wing_flat_no.' - '.$value->byledger->name : $value->byledger->name);
                }
                elseif($value->to_ledger_id != $ledger_id)
                {
                    $data[$k]['ledger'] = (!empty($value->toledger->wing_flat_no) ? $value->toledger->wing_flat_no.' - '.$value->toledger->name : $value->toledger->name);
                }
                $data[$k]['narration'] = $value->narration;
                $data[$k]['voucher_type'] = $value->voucher_type;
                $data[$k]['bank_date'] = $value->bank_date;
                if($value->by_ledger_id != $ledger_id)
                {
                    $data[$k]['debit_amount'] = 0;
                }
                else
                {
                    $data[$k]['debit_amount'] = $value->amount; 
                    $debit_amount += $value->amount; 
                }
                if($value->to_ledger_id != $ledger_id)
                {
                    $data[$k]['creadit_amount'] = 0;
                }
                else
                {
                    $data[$k]['creadit_amount'] = $value->amount; 
                    $creadit_amount += $value->amount;
                }
                $i++;
            }
            $data[$i]['submit_date'] = '';
            $data[$i]['ledger'] = '';
            $data[$i]['narration'] = '';
            $data[$i]['voucher_type'] = '';
            $data[$i]['bank_date'] = 'Total';
            $data[$i]['debit_amount'] = $debit_amount;
            $data[$i]['creadit_amount'] = $creadit_amount;

            $closing_balance = $debit_amount - $creadit_amount;

            $data[$i+1]['submit_date'] = '';
            $data[$i+1]['ledger'] = '';
            $data[$i+1]['narration'] = '';
            $data[$i+1]['voucher_type'] = '';
            $data[$i+1]['bank_date'] = 'Closing Balance';
            if($closing_balance > 0)
            {
                $data[$i+1]['debit_amount'] = '';
                $data[$i+1]['creadit_amount'] = $closing_balance;
            }
            else
            {
                $data[$i+1]['debit_amount'] = $closing_balance;
                $data[$i+1]['creadit_amount'] = '';
            }
            
            $data1 = (object)array();
            $data1 = $data;
            return $data1;
            // echo '<pre>'; print_r($data1); exit;
        }
    }

    public static function getLedgerReportAll()
    {
        

        $ledgers = Ledger::where('society_id', Auth::user()->society_id)->orderBy('registration_date', 'asc')->get();
        $data = array();
        $i = 0;
        foreach($ledgers as $ledger)
        {
            $debit_amount = 0; 
            $creadit_amount = 0;
            $records = SociatyAccountEntry::where('society_id', Auth::user()->society_id)->where('by_ledger_id', $ledger->id)->orWhere('to_ledger_id', $ledger->id)->orderBy('submit_date', 'asc')->get();
            
            if(!empty($records))
            {
                if($i != 0)
                {
                    $data[$i]['submit_date'] = '';
                    $data[$i]['ledger'] = '';
                    $data[$i]['narration'] = '';
                    $data[$i]['voucher_type'] = '';
                    $data[$i]['bank_date'] = '';
                    $data[$i]['debit_amount'] = '';
                    $data[$i]['creadit_amount'] = '';
                    $i++;
                }
                $l_name = str_replace(',', ' & ', $ledger->name);
                $data[$i]['submit_date'] = '';
                $data[$i]['ledger'] = (!empty($ledger->wing_flat_no) ? $ledger->wing_flat_no.' - '.$l_name : $l_name);
                $data[$i]['narration'] = '';
                $data[$i]['voucher_type'] = '';
                $data[$i]['bank_date'] = '';
                $data[$i]['debit_amount'] = '';
                $data[$i]['creadit_amount'] = '';
                
                $i++;
                foreach($records as $k => $value)
                {
                    $k = $i;
                    // echo $k; exit;
                    $data[$k]['submit_date'] = date('d-m-Y', strtotime($value->submit_date));
                    if($value->by_ledger_id != $ledger->id)
                    {
                        $data[$k]['ledger'] = (!empty($value->byledger->wing_flat_no) ? $value->byledger->wing_flat_no.' - '.$value->byledger->name : $value->byledger->name);
                    }
                    elseif($value->to_ledger_id != $ledger->id)
                    {
                        $data[$k]['ledger'] = (!empty($value->toledger->wing_flat_no) ? $value->toledger->wing_flat_no.' - '.$value->toledger->name : $value->toledger->name);
                    }
                    $data[$k]['narration'] = $value->narration;
                    $data[$k]['voucher_type'] = $value->voucher_type;
                    $data[$k]['bank_date'] = $value->bank_date;
                    if($value->by_ledger_id != $ledger->id)
                    {
                        $data[$k]['debit_amount'] = 0;
                    }
                    else
                    {
                        $data[$k]['debit_amount'] = $value->amount; 
                        $debit_amount += $value->amount; 
                    }
                    if($value->to_ledger_id != $ledger->id)
                    {
                        $data[$k]['creadit_amount'] = 0;
                    }
                    else
                    {
                        $data[$k]['creadit_amount'] = $value->amount; 
                        $creadit_amount += $value->amount;
                    }
                     
                    $i++;
                }
                $data[$i]['submit_date'] = '';
                $data[$i]['ledger'] = '';
                $data[$i]['narration'] = '';
                $data[$i]['voucher_type'] = '';
                $data[$i]['bank_date'] = 'Total';
                $data[$i]['debit_amount'] = $debit_amount;
                $data[$i]['creadit_amount'] = $creadit_amount;

                $closing_balance = $debit_amount - $creadit_amount;

                $data[$i+1]['submit_date'] = '';
                $data[$i+1]['ledger'] = '';
                $data[$i+1]['narration'] = '';
                $data[$i+1]['voucher_type'] = '';
                $data[$i+1]['bank_date'] = 'Closing Balance';
                if($closing_balance > 0)
                {
                    $data[$i+1]['debit_amount'] = '';
                    $data[$i+1]['creadit_amount'] = $closing_balance;
                }
                else
                {
                    $data[$i+1]['debit_amount'] = $closing_balance;
                    $data[$i+1]['creadit_amount'] = '';
                }
                $i = $i+2;
            }
        }
        $data1 = (object)array();
        $data1 = $data;
        return $data1;
    }

    public static function getProfitLoss()
    {
        $from_date = Session::get('plrfrom_date');
        $to_date = Session::get('plrto_date');
        $profitloassdata = array();
        $group_categorys = GroupCategory::where('group_type_id', 3)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
        // echo '<pre>'; 
        // print_r($group_categorys->toArray()); exit;
        $net_amounts = array();
        foreach($group_categorys as $k => $g_category)
        {
            $profitloassdata[$k]['title'] = $g_category->name; 
            $profitloassdata[$k]['group_array'] = array();
            $group_creations = GroupCreations::with('ledger')->where('group_category_id', $g_category->id)->get();
            if(!empty($group_creations))
            {
                $group_array = array();
                foreach($group_creations as $k1 => $gc)
                {
                    $group_array[$k1]['sub_title'] = $gc->name;
                    $group_array[$k1]['ledger'] = array();
                    $ledger_amount = 0;
                    $ledger_array = array();
                    if(!empty($gc->ledger) && count($gc->ledger) > 0)
                    {
                        foreach($gc->ledger as $k2 => $ledger)
                        {
                            $byledger = SociatyAccountEntry::where('by_ledger_id', $ledger->id)
                                        ->where(function($que) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $que->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where('society_id', Auth::user()->society_id)->sum('amount');
                            $toledger = SociatyAccountEntry::where('to_ledger_id', $ledger->id)
                                        ->where(function($que) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $que->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where('society_id', Auth::user()->society_id)->sum('amount');
                            $ledger_array[$k2]['name'] = $ledger->name;
                            $ledger_array[$k2]['amount'] = $byledger - $toledger;
                            $ledger_array[$k2]['debit'] = $byledger;
                            $ledger_array[$k2]['credit'] = $toledger;
                            $ledger_amount += $byledger - $toledger;
                            
                        }
                    }
                    if($gc->name == 'Expenses')
                    {
                        $net_amounts['Expenses'] = $ledger_amount;
                    }
                    if($gc->name == 'Income')
                    {
                        $net_amounts['Income'] = $ledger_amount;
                    }
                    $group_array[$k1]['total_amount']= $ledger_amount;
                    $group_array[$k1]['ledger']= $ledger_array;
                }
                $profitloassdata[$k]['group_array'] = $group_array;
            }
        }

        $net_amount = $net_amounts['Income'] - $net_amounts['Expenses'];
        $data = array();
        $i = 1;
        // echo '<pre>'; print_r($profitloassdata); exit;
        $j = 2;
        foreach($profitloassdata[0]['group_array'] as $ga)
        {
            $data[0]['particular'] = 'Particulars (Debit)';
            $data[0]['amount'] = '';

            $data[$i]['particular'] = $ga['sub_title'];
            $data[$i]['amount'] = $ga['total_amount'];

            $total_debit = count($ga['ledger']);
            if(!empty($ga['ledger']))
            {
                foreach($ga['ledger'] as $ledger)
                {
                    $i++;
                    $data[$i]['particular'] = $ledger['name'];
                    $data[$i]['amount'] = abs($ledger['amount']);
                }
            }
            $i++;
        }
        if($net_amount > 0 && $k == 0)
        {
            $data[$i]['particulars'] = 'Net Profit';
            $data[$i]['amount'] = abs($net_amount);
            $i++;
        }
        $j = $i+3;
        $j1 = $i+1;
        $j2 = $i+2;
        foreach($profitloassdata[1]['group_array'] as $ga)
        {
            $data[$j1]['particular'] = '';
            $data[$j1]['amount'] = '';
            $data[$j2]['particular'] = 'Particulars (Credit)';
            $data[$j2]['amount'] = '';

            $data[$j]['particular'] = $ga['sub_title'];
            $data[$j]['amount'] = abs($ga['total_amount']);
            $total = count($ga['ledger']);
            if(!empty($ga['ledger']))
            {
                $j++;
                foreach($ga['ledger'] as $ledger)
                {
                    $data[$j]['particular'] = $ledger['name'];
                    $data[$j]['amount'] = abs($ledger['amount']);
                }
            }
            $j++;
        }
        
        if($net_amount < 0 && $k == 1)
        {
            $data[$j]['particulars'] = 'Net Loss';
            $data[$j]['amount'] = abs($net_amount);
        }
        $data1  = (object) $data;
        return $data1;
    }

    public static function getBalanceSheet()
    {
        $from_date = Session::get('bsrfrom_date');
        $to_date = Session::get('bsrto_date');
        $liabilitiesdata = array();
        $assetsdata = array();

        $liabilities = GroupCategory::where('group_type_id', 2)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
        
        foreach($liabilities as $k => $lia)
        {
            $liabilitiesdata[$k]['title'] = $lia->name; 
            $liabilitiesdata[$k]['group_array'] = array();
            $group_creations = GroupCreations::with('ledger')->where('group_category_id', $lia->id)->get();
            if(!empty($group_creations))
            {
                $group_array = array();
                foreach($group_creations as $k1 => $gc)
                {
                    $group_array[$k1]['sub_title'] = $gc->name;
                    $group_array[$k1]['ledger'] = array();
                    $ledger_amount = 0;
                    if(!empty($gc->ledger) && count($gc->ledger) > 0)
                    {
                        $ledger_array = array();
                        foreach($gc->ledger as $k2 => $ledger)
                        {
                            $byledger = SociatyAccountEntry::where('by_ledger_id', $ledger->id)
                                        ->where(function($que) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $que->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where('society_id', Auth::user()->society_id)->sum('amount');
                            $toledger = SociatyAccountEntry::where('to_ledger_id', $ledger->id)
                                        ->where(function($que) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $que->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where('society_id', Auth::user()->society_id)->sum('amount');
                            if(!empty($ledger->wing_flat_no))
                            {
                                $ledger_array[$k2]['name'] = $ledger->wing_flat_no.' - '.$ledger->name; 
                            }
                            else{
                                $ledger_array[$k2]['name'] = $ledger->name;
                            }
                            $ledger_array[$k2]['amount'] = $byledger - $toledger;
                            $ledger_array[$k2]['debit'] = $byledger;
                            $ledger_array[$k2]['credit'] = $toledger;
                            $ledger_amount += $byledger - $toledger;
                        }
                        $group_array[$k1]['total_amount']= $ledger_amount;
                        $group_array[$k1]['ledger']= $ledger_array;
                    }
                }
                $liabilitiesdata[$k]['group_array'] = $group_array;
            }
        }

        $assets = GroupCategory::where('group_type_id', 1)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
        foreach($assets as $k => $asset)
        {
            $assetsdata[$k]['title'] = $asset->name; 
            $assetsdata[$k]['group_array'] = array();
            $group_creations = GroupCreations::with('ledger')->where('group_category_id', $asset->id)->get();
            if(!empty($group_creations))
            {
                $group_array = array();
                foreach($group_creations as $k1 => $gc)
                {
                    $group_array[$k1]['sub_title'] = $gc->name;
                    $group_array[$k1]['ledger'] = array();
                    $ledger_amount = 0;
                    if(!empty($gc->ledger) && count($gc->ledger) > 0)
                    {
                        $ledger_array = array();
                        foreach($gc->ledger as $k2 => $ledger)
                        {
                            $byledger = SociatyAccountEntry::where('by_ledger_id', $ledger->id)
                                        ->where(function($que) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $que->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where('society_id', Auth::user()->society_id)->sum('amount');
                            $toledger = SociatyAccountEntry::where('to_ledger_id', $ledger->id)
                                        ->where(function($que) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $que->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where('society_id', Auth::user()->society_id)->sum('amount');
                            if(!empty($ledger->wing_flat_no))
                            {
                                $ledger_array[$k2]['name'] = $ledger->wing_flat_no.' - '.$ledger->name; 
                            }
                            else{
                                $ledger_array[$k2]['name'] = $ledger->name;
                            }
                            $ledger_array[$k2]['amount'] = $byledger - $toledger;
                            $ledger_array[$k2]['debit'] = $byledger;
                            $ledger_array[$k2]['credit'] = $toledger;
                            $ledger_amount += $byledger - $toledger;
                        }
                        $group_array[$k1]['total_amount']= $ledger_amount;
                        $group_array[$k1]['ledger']= $ledger_array;
                    }
                }
                $assetsdata[$k]['group_array'] = $group_array;
            }
        }

        $data = array();
        $i = 1;
        // echo '<pre>'; print_r($profitloassdata); exit;
        $j = 2;
        foreach($liabilitiesdata as $value)
        {
            $data[0]['particular'] = 'Liabilities';
            $data[0]['amount'] = '';

            $data[$i]['Repair Fund'] = $value['title'];
            $data[$i]['amount'] = '';
            foreach($value['group_array'] as $ga)
            {
                $i++;
                $data[$i]['particular'] = $ga['sub_title'];
                $data[$i]['amount'] = '';
                foreach($ga['ledger'] as $ledger)
                {
                    $i++;
                    $data[$i]['particular'] = $ledger['name'];
                    $data[$i]['amount'] = abs($ledger['amount']);
                }
            }
            $i++;
        }
        $j = $i+3;
        $j1 = $i+1;
        $j2 = $i+2;

        foreach($assetsdata as $value)
        {
            $data[$j1]['particular'] = '';
            $data[$j1]['amount'] = '';

            $data[$j2]['particular'] = 'Liabilities';
            $data[$j2]['amount'] = '';

            $data[$j]['Repair Fund'] = $value['title'];
            $data[$j]['amount'] = '';
            foreach($value['group_array'] as $ga)
            {
                $j++;
                $data[$j]['particular'] = $ga['sub_title'];
                $data[$j]['amount'] = '';
                foreach($ga['ledger'] as $ledger)
                {
                    $j++;
                    $data[$j]['particular'] = $ledger['name'];
                    $data[$j]['amount'] = abs($ledger['amount']);
                }
            }
            $j++;
        }

        return $data;
    }

    public static function getClosingBalance()
    {
        $from_date = Session::get('cbrfrom_date');
        $to_date = Session::get('cbrto_date');
        $ledgers = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get();
        $cloasingdatas = array();
        foreach($ledgers as $k => $ledger)
        {
            $cloasingdatas[$k]['ledger'] = (!empty($ledger->wing_flat_no) ? $ledger->wing_flat_no.' - '.$ledger->name : $ledger->name);
            // $cloasingdatas[$k]['wing_flat_no'] = $ledger->wing_flat_no;
            $credit_amount = SociatyAccountEntry::where('by_ledger_id', $ledger->id)
                                ->where(function($que) use ($from_date, $to_date){
                                    if($from_date != '' && $to_date != '')
                                    {
                                        $que->whereBetween('submit_date', [$from_date, $to_date]);
                                    }
                                })
                                ->where('society_id', Auth::user()->society_id)->sum('amount');
            $debit_amount = SociatyAccountEntry::where('to_ledger_id', $ledger->id)
                                ->where(function($que) use ($from_date, $to_date){
                                    if($from_date != '' && $to_date != '')
                                    {
                                        $que->whereBetween('submit_date', [$from_date, $to_date]);
                                    }
                                })
                            ->where('society_id', Auth::user()->society_id)->sum('amount');
            $cloasingdatas[$k]['debit_amount'] = $debit_amount;
            $cloasingdatas[$k]['credit_amount'] = $credit_amount;
            $cloasingdatas[$k]['closing_balance'] = $credit_amount - $debit_amount;
        }
        // echo '<pre>'; print_r($cloasingdatas); exit;
        $data = (object)$cloasingdatas;
        return $data;
    }
}
