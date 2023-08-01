<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\SubscriptionSocietyPackages;
use DateTime;
use App\GroupCategory;
use App\GroupCreations;
use App\SociatyAccountEntry;
use App\Ledger;

class DashboardController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth');
    }
    public function index()
    {
        if(\Auth::check()){
            $group_categorys = GroupCategory::where('group_type_id', 3)->where('society_id', Auth::user()->society_id)->orderBy('id','desc')->get();
            $data = array();
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
                                                ->where('society_id', Auth::user()->society_id)->sum('amount');
                                    $toledger = SociatyAccountEntry::where('to_ledger_id', $ledger->id)
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


            $ledgers = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->orderBy('registration_date', 'asc')->get();
            $cloasingdatas = array();

            $closing_amount = 0;
            foreach($ledgers as $k => $ledger)
            {
                $credit_amount = SociatyAccountEntry::where('by_ledger_id', $ledger->id)
                                    ->where('society_id', Auth::user()->society_id)->sum('amount');
                $debit_amount = SociatyAccountEntry::where('to_ledger_id', $ledger->id)
                                    ->where('society_id', Auth::user()->society_id)->sum('amount');
                
                $closing_amount += $debit_amount - $credit_amount;
            }

            $package_detail = SubscriptionSocietyPackages::where('society_id', Auth::user()->society_id)->first();
            $today = date('Y-m-d');

            $date1 = new DateTime($package_detail->end_date);
            $date2 = new DateTime($today);
            $interval = $date1->diff($date2);
            $msg = '';
            if($interval->days == 0)
            {
                $msg = "Your plan/package is going to expire on today. Request you to renew your plan/package to use the services seamlessly.";
            }
            else{
                if($interval->days <= 15)
                {
                    $msg = 'Your plan/package is going to expire on '.$interval->days.' days.Request you to renew your plan/package to use the services seamlessly.';
                }
            }


            $asset = GroupCategory::where('group_type_id', 1)->where('society_id', Auth::user()->society_id)->where('name','LIKE', '%MEMBERS ACCOUNT%')->orderBy('id','desc')->first();
            $dues_amount = 0;
            $advance_amount = 0;
            if(!empty($asset))
            {
                $assetsdata[$k]['group_array'] = array();
                $group_creations = GroupCreations::with('ledger')->where('group_category_id', $asset->id)->get();
                if(!empty($group_creations))
                {
                    $group_array = array();
                    foreach($group_creations as $k1 => $gc)
                    {
                        $group_array[$k1]['ledger'] = array();
                        $ledger_amount = 0;
                        if(!empty($gc->ledger) && count($gc->ledger) > 0)
                        {
                            $ledger_array = array();
                            foreach($gc->ledger as $k2 => $ledger)
                            {
                                $byledger = SociatyAccountEntry::where('by_ledger_id', $ledger->id)
                                            ->where('society_id', Auth::user()->society_id)->sum('amount');
                                $toledger = SociatyAccountEntry::where('to_ledger_id', $ledger->id)
                                            ->where('society_id', Auth::user()->society_id)->sum('amount');

                                $amount = $byledger - $toledger;
                                if($amount < 0)
                                {
                                    $advance_amount += $amount;
                                }
                                else{
                                    $dues_amount += $amount;
                                }
                            }
                        }
                    }
                }
            }


            $data['msg'] = $msg;
            $data['expenses'] = $Expenses;
            $data['income'] = $Income;
            $data['profit_loass'] = $Income - $Expenses;
            $data['closing_amount'] = $closing_amount;
            $data['dues_amount'] = abs($dues_amount);
            $data['advance_amount'] = abs($advance_amount);
            return view('dashboard', $data);
        }
        else{
            return redirect('/login');
        }
    }
}
