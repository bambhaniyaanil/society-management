<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ledger;
use Auth;
use App\SociatyAccountEntry;
use Session;
use App\GroupCategory;
use App\GroupCreations;
use App\Exports\ExportLedgerReport;
use App\Exports\ExportProfitLoss;
use App\Exports\ExportBalanceSheet;
use App\Exports\ExportClosingBalance;
use App\Exports\ExportLedgerReportAll;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth');
    }

    public function ledger(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(60, $permissions))
        {
            $data = array();
            $data['page_title'] = "Ledger Report"; 
            $data['sub_title'] = "Ledger Report";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get();

            $from_date = Session::get('lrfrom_date');
            $to_date = Session::get('lrto_date');
            $ledger_id = Session::get('rledger_id');
            $vch_type = Session::get('rlvch_type');
            if((!empty($from_date) && !empty($to_date)) || !empty($ledger_id) || !empty($vch_type))
            {
                $data['ledgers_data'] = SociatyAccountEntry::where('society_id', Auth::user()->society_id)
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
                    });
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }   
                    if($limit == 'all')
                    {                     
                        $data['ledgers_data'] = $data['ledgers_data']->orderBy('submit_date', 'asc')->get();
                    }
                    else{
                        $data['ledgers_data'] = $data['ledgers_data']->orderBy('submit_date', 'asc')->paginate($limit);
                    }
                    // ->paginate(15);
            }
            else{
                $data['ledgers_data'] = array();
            }
            return view('report-ledger', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function ledgerReport(Request $request)
    {
        $data = array();
        $data['page_title'] = "Ledger Report"; 
        $data['sub_title'] = "Ledger Report";
        $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get();
        
        $from_date = '';
        $to_date = '';
        if($request->from_date != '')
        {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if($request->to_date != '')
        {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        Session::put('lrfrom_date', $from_date);
        Session::put('lrto_date', $to_date);
        Session::put('rledger_id', $request->ledger_id);
        Session::put('rlvch_type', $request->vch_type);
        $vch_type = $request->vch_type;
        $ledger_id = $request->ledger_id;
        if($from_date != '')
        {
            if($to_date == '')
            {
                return redirect()->route('report.ledger')->with('error','The to date field is required');
            }
        }

        if($from_date == '')
        {
            if($to_date != '')
            {
                return redirect()->route('report.ledger')->with('error','First from date field select');
            }
        }

        if($from_date > $to_date)
        {
            return redirect()->route('report.ledger')->with('error','Date is invalid please select proper date!');
        }
        $data['ledgers_data'] = SociatyAccountEntry::where('society_id', Auth::user()->society_id)
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
        });
        $limit = $request->limit;
        if($limit == '')
        {
            $limit = 10;
        }   
        if($limit == 'all')
        {                     
            $data['ledgers_data'] = $data['ledgers_data']->orderBy('submit_date', 'asc')->get();
        }
        else{
            $data['ledgers_data'] = $data['ledgers_data']->orderBy('submit_date', 'asc')->paginate($limit);
        }
        // ->paginate(15);
        return view('report-ledger', $data);
    }

    public function submitDate(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(92, $permissions))
        {
            $date = date('Y-m-d', strtotime($request->date));
            $id =  $request->id;

            $bank_r = SociatyAccountEntry::find($id);
            $bank_r->bank_date = $date;
            $bank_r->save();

            return response()->json(['success'=>'Bank Date successfully added']);
        }
        else{
            return redirect()->back();
        }
    }

    public function reset()
    {
        Session::forget('lrfrom_date');
        Session::forget('lrto_date');
        Session::forget('rledger_id');
        Session::forget('rlvch_type');
        Session::forget('plrfrom_date');
        Session::forget('plrto_date');
        Session::forget('bsrfrom_date');
        Session::forget('bsrto_date');
        Session::forget('cbrfrom_date');
        Session::forget('cbrto_date');
        return response()->json();
    }

    public function profitLoss()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(62, $permissions))
        {
            $data = array();
            $data['page_title'] = "Profit Loss Report";
            $from_date = Session::get('plrfrom_date');
            $to_date = Session::get('plrto_date');
            $profitloassdata = array();
            $group_categorys = GroupCategory::where('group_type_id', 3)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
            // echo '<pre>'; 
            // print_r($group_categorys->toArray()); exit;
            
            $net_amounts = array();
            if(count($group_categorys) > 0)
            {
                foreach($group_categorys as $k => $g_category)
                {
                    $final_total = 0;
                    
                    $profitloassdata[$k]['title'] = $g_category->name; 
                    $profitloassdata[$k]['group_array'] = array();
                    $group_creations = GroupCreations::with('ledger')->where('group_category_id', $g_category->id)->get();
                    if(!empty($group_creations))
                    {
                        $group_array = array();
                        $total_amount = 0;
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
                                    if(!empty($ledger->wing_flat_no))
                                    { 
                                        $ledger_array[$k2]['name'] = $ledger->wing_flat_no.' - '.$ledger->name; 
                                    } 
                                    else 
                                    { 
                                        $ledger_array[$k2]['name'] = $ledger->name;
                                    }
                                    $ledger_array[$k2]['amount'] = abs($toledger - $byledger);
                                    $ledger_array[$k2]['debit'] = $byledger;
                                    $ledger_array[$k2]['credit'] = $toledger;
                                    $ledger_amount += abs($toledger - $byledger);
                                    
                                }
                            }
                            // $net_amounts['Expenses'] = 0;
                            // if($gc->name == 'Expenses')
                            // {
                            //     $net_amounts['Expenses'] = $ledger_amount;
                            // }
                            // $net_amounts['Income'] = 0;
                            // if($gc->name == 'Income')
                            // {
                            //     $net_amounts['Income'] = $ledger_amount;
                            // }
                            $group_array[$k1]['total_amount']= $ledger_amount;
                            $group_array[$k1]['ledger']= $ledger_array;
                            $total_amount += $ledger_amount;
                        }
                        $profitloassdata[$k]['group_array'] = $group_array;
                        $profitloassdata[$k]['total_amount'] = $total_amount;
                        $final_total += $total_amount;
                    }
                    $profitloassdata[$k]['final_amount'] = $final_total; 
                }
            } 
            // echo '<pre>'; print_r($profitloassdata); exit;
            if(!empty($profitloassdata))
            {
                $net_amount =  $profitloassdata[1]['total_amount'] - $profitloassdata[0]['total_amount'];
            }
            else
            {
                $net_amount = 0;
            }
            
            if($net_amount > 0)
            {
                $profitloassdata[0]['final_amount'] = abs($net_amount) + abs($profitloassdata[0]['total_amount']);
                $profitloassdata[1]['final_amount'] = $profitloassdata[1]['total_amount'];
            }
            else
            {
                $profitloassdata[0]['final_amount'] = $profitloassdata[0]['total_amount'];
                $profitloassdata[1]['final_amount'] = abs($net_amount) + abs($profitloassdata[1]['total_amount']);
            }
            // echo $profitloassdata[1]['total_amount']; exit;
            $data['profitloassdata'] = $profitloassdata;
            $data['net_amount'] = $net_amount;
            return view('profit-loss', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function profitLossReport(Request $request)
    {
        
        $from_date = '';
        $to_date = '';
        if($request->from_date != '')
        {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if($request->to_date != '')
        {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        Session::put('plrfrom_date', $from_date);
        Session::put('plrto_date', $to_date);
        if($from_date != '')
        {
            if($to_date == '')
            {
                return redirect()->route('report.profit-loss')->with('error','The to date field is required');
            }
        }

        if($from_date == '')
        {
            if($to_date != '')
            {
                return redirect()->route('report.profit-loss')->with('error','First from date field select');
            }
        }

        if($from_date > $to_date)
        {
            return redirect()->route('report.profit-loss')->with('error','Date is invalid please select proper date!');
        }

        $data = array();
        $data['page_title'] = "Profit Loss Report";

        $profitloassdata = array();
        $group_categorys = GroupCategory::where('group_type_id', 3)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
        // echo '<pre>'; 
        // print_r($group_categorys->toArray()); exit;
        $net_amounts = array();
        if(count($group_categorys) > 0)
        {
            foreach($group_categorys as $k => $g_category)
            {
                $final_total = 0;
                $profitloassdata[$k]['title'] = $g_category->name; 
                $profitloassdata[$k]['group_array'] = array();
                $group_creations = GroupCreations::with('ledger')->where('group_category_id', $g_category->id)->get();
                if(!empty($group_creations))
                {
                    $group_array = array();
                    $total_amount = 0;
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
                                if(!empty($ledger->wing_flat_no))
                                { 
                                    $ledger_array[$k2]['name'] = $ledger->wing_flat_no.' - '.$ledger->name; 
                                } 
                                else 
                                { 
                                    $ledger_array[$k2]['name'] = $ledger->name;
                                }
                                $ledger_array[$k2]['amount'] = abs($toledger - $byledger);
                                $ledger_array[$k2]['debit'] = $byledger;
                                $ledger_array[$k2]['credit'] = $toledger;
                                $ledger_amount += abs($toledger - $byledger);
                                
                            }
                        }
                        // $net_amounts['Expenses'] = 0;
                        // if($gc->name == 'Expenses')
                        // {
                        //     $net_amounts['Expenses'] = $ledger_amount;
                        // }
                        // $net_amounts['Income'] = 0;
                        // if($gc->name == 'Income')
                        // {
                        //     $net_amounts['Income'] = $ledger_amount;
                        // }
                        $group_array[$k1]['total_amount']= $ledger_amount;
                        $group_array[$k1]['ledger']= $ledger_array;
                        $total_amount += $ledger_amount;
                    }
                    $profitloassdata[$k]['group_array'] = $group_array;
                    $profitloassdata[$k]['total_amount'] = $total_amount;
                    $final_total += $total_amount;
                }
                $profitloassdata[$k]['final_amount'] = $final_total; 
            }
        } 

        if(!empty($profitloassdata))
        {
            $net_amount =  $profitloassdata[1]['total_amount'] - $profitloassdata[0]['total_amount'];
        }
        else
        {
            $net_amount = 0;
        }
        if($net_amount > 0)
        {
            $profitloassdata[0]['final_amount'] = abs($net_amount) + abs($profitloassdata[0]['total_amount']);
            $profitloassdata[1]['final_amount'] = $profitloassdata[1]['total_amount'];
        }
        else
        {
            $profitloassdata[0]['final_amount'] = $profitloassdata[0]['total_amount'];
            $profitloassdata[1]['final_amount'] = abs($net_amount) + abs($profitloassdata[1]['total_amount']);
        }
        
        
        $data['profitloassdata'] = $profitloassdata;
        $data['net_amount'] = $net_amount;
        return view('profit-loss', $data);
    }

    public function closingBalance(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(66, $permissions))
        {
            $data['page_title'] = "Closing Balance Report";
            $from_date = Session::get('cbrfrom_date');
            $to_date = Session::get('cbrto_date');
            $ledgers = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->orderBy('registration_date', 'asc')->get();
            $cloasingdatas = array();
            foreach($ledgers as $k => $ledger)
            {
                $cloasingdatas[$k]['ledger'] = $ledger->name;
                $cloasingdatas[$k]['wing_flat_no'] = $ledger->wing_flat_no;
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
                $cloasingdatas[$k]['credit_amount'] = $credit_amount;
                $cloasingdatas[$k]['debit_amount'] = $debit_amount;
            }
            $data['cloasingdatas'] = $cloasingdatas;
            return view('closing-balance', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function closingBalanceReport(Request $request)
    {
       
        $from_date = '';
        $to_date = '';
        if($request->from_date != '')
        {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if($request->to_date != '')
        {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        Session::put('cbrfrom_date', $from_date);
        Session::put('cbrto_date', $to_date);
        if($from_date != '')
        {
            if($to_date == '')
            {
                return redirect()->route('report.closing-balance')->with('error','The to date field is required');
            }
        }

        if($from_date == '')
        {
            if($to_date != '')
            {
                return redirect()->route('report.closing-balance')->with('error','First from date field select');
            }
        }

        if($from_date > $to_date)
        {
            return redirect()->route('report.closing-balance')->with('error','Date is invalid please select proper date!');
        }

        $data = array();
        $data['page_title'] = "Closing Balance Report";
        $ledgers = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->orderBy('registration_date', 'asc')->get();
        $cloasingdatas = array();
        foreach($ledgers as $k => $ledger)
        {
            $cloasingdatas[$k]['ledger'] = $ledger->name;
            $cloasingdatas[$k]['wing_flat_no'] = $ledger->wing_flat_no;
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
            $cloasingdatas[$k]['credit_amount'] = $credit_amount;
            $cloasingdatas[$k]['debit_amount'] = $debit_amount;
        }
        
        $data['cloasingdatas'] = $cloasingdatas;
        return view('closing-balance', $data);
    }


    public function balanceSheet(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(64, $permissions))
        {
            $data = array();
            $data['page_title'] = "Balance Sheet Report";
            $from_date = Session::get('bsrfrom_date');
            $to_date = Session::get('bsrto_date');
            $liabilitiesdata = array();
            $assetsdata = array();

            $liabilities = GroupCategory::where('group_type_id', 2)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
            $liabilities_total = 0;
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
                                else 
                                { 
                                    $ledger_array[$k2]['name'] = $ledger->name;
                                }
                                $ledger_array[$k2]['amount'] = abs($byledger - $toledger);
                                $ledger_array[$k2]['closing_amount'] = $byledger - $toledger;
                                $ledger_array[$k2]['debit'] = $byledger;
                                $ledger_array[$k2]['credit'] = $toledger;
                                $ledger_amount += abs($byledger - $toledger);
                            }
                            $group_array[$k1]['ledger']= $ledger_array;
                        }
                        $group_array[$k1]['total_amount']= $ledger_amount;
                        $liabilities_total += $ledger_amount;
                    }
                    $liabilitiesdata[$k]['group_array'] = $group_array;
                }
            }

            $assets = GroupCategory::where('group_type_id', 1)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
            $assets_total = 0;

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
                                else 
                                { 
                                    $ledger_array[$k2]['name'] = $ledger->name;
                                }
                                $ledger_array[$k2]['amount'] = abs($byledger - $toledger);
                                $ledger_array[$k2]['closing_amount'] = $byledger - $toledger;
                                $ledger_array[$k2]['debit'] = $byledger;
                                $ledger_array[$k2]['credit'] = $toledger;
                                $ledger_amount += abs($byledger - $toledger);
                            }
                            
                            $group_array[$k1]['ledger']= $ledger_array;
                        }
                        $group_array[$k1]['total_amount']= $ledger_amount;
                        $assets_total += $ledger_amount;
                    }
                    $assetsdata[$k]['group_array'] = $group_array;
                }
            }
            $data['liabilitiesdata'] = $liabilitiesdata;
            $data['assetsdata'] = $assetsdata;

            $data['liabilities_total'] = $liabilities_total;
            $data['assets_total'] = $assets_total;

            $group_categorys = GroupCategory::where('group_type_id', 3)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
            $Expenses = 0;
            $Income = 0;
            if(count($group_categorys) > 0)
            {
                foreach($group_categorys as $k => $g_category)
                {
                    $profitloassdata[$k]['group_array'] = array();
                    $group_creations = GroupCreations::with('ledger')->where('group_category_id', $g_category->id)->get();
                    if(!empty($group_creations))
                    {
                        $total_amount = 0;
                        foreach($group_creations as $k1 => $gc)
                        {
                            $ledger_amount = 0;
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

                                    $ledger_amount += abs($toledger - $byledger);
                                    
                                }
                            }
                            $total_amount += $ledger_amount;
                        }
                    }
                    if($k == 0)
                    {
                        $Expenses = $total_amount;
                    }
                    if($k == 1)
                    {
                        $Income = $total_amount;
                    }
                }
            } 
            $net_amount = $Income - $Expenses;

            if($net_amount > 0)
            {
                $data['profit'] = $net_amount;
                $data['loss'] = 0;
            }
            else{
                $data['profit'] = 0;
                $data['loss'] = $net_amount;
            }
            return view('balance-sheet', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function balanceSheetReport(Request $request)
    {
        
        $from_date = '';
        $to_date = '';
        if($request->from_date != '')
        {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if($request->to_date != '')
        {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        Session::put('bsrfrom_date', $from_date);
        Session::put('bsrto_date', $to_date);
        if($from_date != '')
        {
            if($to_date == '')
            {
                return redirect()->route('report.balance-sheet')->with('error','The to date field is required');
            }
        }

        if($from_date == '')
        {
            if($to_date != '')
            {
                return redirect()->route('report.balance-sheet')->with('error','First from date field select');
            }
        }

        if($from_date > $to_date)
        {
            return redirect()->route('report.balance-sheet')->with('error','Date is invalid please select proper date!');
        }

        $data = array();
        $data['page_title'] = "Balance Sheet Report";
        $liabilitiesdata = array();
        $assetsdata = array();

        $liabilities = GroupCategory::where('group_type_id', 2)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
        $liabilities_total = 0;
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
                            else 
                            { 
                                $ledger_array[$k2]['name'] = $ledger->name;
                            }
                            $ledger_array[$k2]['amount'] = $byledger - $toledger;
                            $ledger_array[$k2]['closing_amount'] = $byledger - $toledger;
                            $ledger_array[$k2]['debit'] = $byledger;
                            $ledger_array[$k2]['credit'] = $toledger;
                            $ledger_amount += $byledger - $toledger;
                        }
                        
                        $group_array[$k1]['ledger']= $ledger_array;
                    }
                    $group_array[$k1]['total_amount']= $ledger_amount;
                    $liabilities_total += $ledger_amount;
                }
                $liabilitiesdata[$k]['group_array'] = $group_array;
            }
        }

        $assets = GroupCategory::where('group_type_id', 1)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
        $assets_total = 0;

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
                            else 
                            { 
                                $ledger_array[$k2]['name'] = $ledger->name;
                            }
                            $ledger_array[$k2]['amount'] = $byledger - $toledger;
                            $ledger_array[$k2]['closing_amount'] = $byledger - $toledger;
                            $ledger_array[$k2]['debit'] = $byledger;
                            $ledger_array[$k2]['credit'] = $toledger;
                            $ledger_amount += $byledger - $toledger;
                        }
                        
                        $group_array[$k1]['ledger']= $ledger_array;
                    }
                    $group_array[$k1]['total_amount']= $ledger_amount;
                    $assets_total += $ledger_amount;
                }
                $assetsdata[$k]['group_array'] = $group_array;
            }
        }
        $data['liabilitiesdata'] = $liabilitiesdata;
        $data['assetsdata'] = $assetsdata;

        $data['liabilities_total'] = $liabilities_total;
        $data['assets_total'] = $assets_total;

        $group_categorys = GroupCategory::where('group_type_id', 3)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
        $Expenses = 0;
        $Income = 0;
        if(count($group_categorys) > 0)
        {
            foreach($group_categorys as $k => $g_category)
            {
                $profitloassdata[$k]['group_array'] = array();
                $group_creations = GroupCreations::with('ledger')->where('group_category_id', $g_category->id)->get();
                if(!empty($group_creations))
                {
                    $total_amount = 0;
                    foreach($group_creations as $k1 => $gc)
                    {
                        $ledger_amount = 0;
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

                                $ledger_amount += abs($toledger - $byledger);
                                
                            }
                        }
                        $total_amount += $ledger_amount;
                    }
                }
                if($k == 0)
                {
                    $Expenses = $total_amount;
                }
                if($k == 1)
                {
                    $Income = $total_amount;
                }
            }
        } 
        $net_amount = $Income - $Expenses;

        if($net_amount > 0)
        {
            $data['profit'] = $net_amount;
            $data['loss'] = 0;
        }
        else{
            $data['profit'] = 0;
            $data['loss'] = $net_amount;
        }
        return view('balance-sheet', $data);
    }

    public function ledgerExport()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(61, $permissions))
        {
            $ledger_id = Session::get('rledger_id');
            if(empty($ledger_id))
            {
                return redirect()->back()->with('error','Please search ledger report than after export data.');
            }
            return Excel::download(new ExportLedgerReport, 'ledger-report.xlsx');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function profitlossExport()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(63, $permissions))
        {
            return Excel::download(new ExportProfitLoss, 'profit-loss-report.xlsx');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function balancesheetExport()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(65, $permissions))
        {
            return Excel::download(new ExportBalanceSheet, 'balance-sheet-report.xlsx');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function closingbalanceExport()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(67, $permissions))
        {
            return Excel::download(new ExportClosingBalance, 'closing-balance-report.xlsx');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function ledgerExportAll()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(61, $permissions))
        {
            return Excel::download(new ExportLedgerReportAll, 'ledger-report.xlsx');
        }
        else
        {
            return redirect()->back();
        }
    }
}
