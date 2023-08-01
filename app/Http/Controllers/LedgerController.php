<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Ledger;
use App\GroupCreations;
use App\PaymentVoucher;
use App\JournalVoucher;
use App\ReceiptsVoucher;
use App\Invoice;
use Illuminate\Validation\Rule;
use App\Exports\ExportLedger;
use App\Imports\ImportLedger;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Response;

class LedgerController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth');
    }

    public function index()
    {
        if(\Auth::check()){
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(9, $permissions))
            {
                $data = array();
                $data['page_title'] = "Ledger"; 
                $data['sub_title'] = "Ledger List";
                $data['ledgers'] = Ledger::where('society_id', Auth::user()->society_id)->orderBy('registration_date', 'asc')->get();
                // $data['societys'] = $societys;
                return view('ledger/index', $data);
            }
            else{
                return redirect()->back();
            }
        }else{
            return redirect('/login');
        }
    }

    public function create()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(10, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Ledger";
            $data['group_creations'] = GroupCreations::where('status', 1)->where('society_id', Auth::user()->society_id)->get();
            return view('ledger/create', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'under_group' => 'required',
            'name' => 'required',
            'contact_number' => 'required',
            'whats_app_number' => 'required'
        ]);

        if($request->contact_number)
        {
            $contact_numbers = explode(',', $request->contact_number);
            foreach($contact_numbers as $contact_number)
            {
                if(!is_numeric($contact_number))
                {
                    return redirect()->back()->withInput()->with('error', 'Contact Number field is allow only numeric.');
                }
            }
        }

        if($request->whats_app_number)
        {
            $whats_app_numbers = explode(',', $request->whats_app_number);
            foreach($whats_app_numbers as $whats_app_number)
            {
                if(!is_numeric($whats_app_number))
                {
                    return redirect()->back()->withInput()->with('error', 'Whats App Number field is allow only numeric.');
                }
            }
        }

        if($request->email_id)
        {
            $email_ids = explode(',', $request->email_id);
            foreach($email_ids as $email_id)
            {
                if(!valid_email($email_id)){
                    return redirect()->back()->withInput()->with('error', 'This email "'.$email_id.'" is invalid email please add valid email');
                }
            }
        }

        if($request->adhar_number)
        {
            $adhar_numbers = explode(',', $request->adhar_number);
            foreach($adhar_numbers as $adhar_number)
            {
                if(!is_numeric($adhar_number))
                {
                    return redirect()->back()->withInput()->with('error', 'Adhar Number field is allow only numeric.');
                }
            }
        }

        $names = explode(',',$request->name);
        foreach($names as $name)
        {
            $ledger = Ledger::where('society_id', '=', Auth::user()->society_id)->whereRaw('FIND_IN_SET(?,name)', [$name])->get();
            if(count($ledger) != 0)
            {
                return redirect()->back()->withInput()->with('error', 'The '.$name.' name has already been taken.');
            }
        }
        
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['society_id'] = Auth::user()->society_id;
        if($data['area_sq'] == 'ft')
        {
            $data['area_sq_ft'] = $data['area_sq_value'];
        }
        else{
            $data['area_sq_mtr'] = $data['area_sq_value'];
        }
        if($data['balance_type'] == 'credit')
        {
            $data['opning_balance_credit'] = $data['opning_balance'];
        }
        else{
            $data['opning_balance_debit'] = $data['opning_balance'];
        }
        $data['registration_date'] = date('Y-m-d', strtotime($data['registration_date']));
        $save = Ledger::create($data);
        return redirect()->route('ledger')->with('success','Ledger Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(11, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Ledger";
            $data['ledger'] = Ledger::where('id', $id)->where('society_id', Auth::user()->society_id)->first();
            $data['group_creations'] = GroupCreations::where('status', 1)->where('society_id', Auth::user()->society_id)->get();
            return view('ledger/edit', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'under_group' => 'required',
            'name' => 'required',
            'contact_number' => 'required',
            'whats_app_number' => 'required'
        ]);
        if($request->contact_number)
        {
            $contact_numbers = explode(',', $request->contact_number);
            foreach($contact_numbers as $contact_number)
            {
                if(!is_numeric($contact_number))
                {
                    return redirect()->back()->withInput()->with('error', 'Contact Number field is allow only numeric.');
                }
            }
        }

        if($request->whats_app_number)
        {
            $whats_app_numbers = explode(',', $request->whats_app_number);
            foreach($whats_app_numbers as $whats_app_number)
            {
                if(!is_numeric($whats_app_number))
                {
                    return redirect()->back()->withInput()->with('error', 'Whats App Number field is allow only numeric.');
                }
            }
        }

        if($request->email_id)
        {
            $email_ids = explode(',', $request->email_id);
            foreach($email_ids as $email_id)
            {
                if(!valid_email($email_id)){
                    return redirect()->back()->withInput()->with('error', 'This email "'.$email_id.'" is invalid email please add valid email');
                }
            }
        }

        if($request->adhar_number)
        {
            $adhar_numbers = explode(',', $request->adhar_number);
            foreach($adhar_numbers as $adhar_number)
            {
                if(!is_numeric($adhar_number))
                {
                    return redirect()->back()->withInput()->with('error', 'Adhar Number field is allow only numeric.');
                }
            }
        }
        
        $ledger = Ledger::find($id);
        if($ledger['name'] != $request->name)
        {
            $names = explode(',',$request->name);
            foreach($names as $name)
            {
                $ledgern = Ledger::where('society_id', '=', Auth::user()->society_id)->whereRaw('FIND_IN_SET(?,name)', [$name])->where('wing_flat_no', $request->wing_flat_no)->get();
                // echo '<pre>'; print_r($ledgern->toArray()); 
                if(count($ledgern) != 0 && !empty($ledgern))
                {
                    foreach($ledgern as $l)
                    {
                        if($l->id != $id)
                        {
                            return redirect()->back()->withInput()->with('error', 'The '.$name.' name has already been taken.');
                        }
                    }
                }
            }
        }
        $ledger->under_group = $request->under_group;
        $ledger->name = $request->name;
        $ledger->wing_flat_no = $request->wing_flat_no;
        if($request->area_sq == 'ft')
        {
            $ledger->area_sq_ft = $request->area_sq_value; 
            $ledger->area_sq_mtr = '';  
        }
        else
        {
            $ledger->area_sq_mtr = $request->area_sq_value;
            $ledger->area_sq_ft = '';
        }
        $ledger->contact_number = $request->contact_number;
        $ledger->whats_app_number = $request->whats_app_number;
        $ledger->email_id = $request->email_id;
        $ledger->pancard_number = $request->pancard_number;
        $ledger->gst_number = $request->gst_number;
        $ledger->reside_address = $request->reside_address;
        $ledger->correspondence_address = $request->correspondence_address;
        $ledger->area_locality = $request->area_locality;
        $ledger->city_district = $request->city_district;
        $ledger->state = $request->state;
        $ledger->pin_code = $request->pin_code;
        $ledger->country = $request->country;
        $ledger->registration_date = date('Y-m-d', strtotime($request->registration_date));
        if($request->balance_type == 'debit')
        {
            $ledger->opning_balance_debit = $request->opning_balance; 
            $ledger->opning_balance_credit = 0;  
        }
        else
        {
            $ledger->opning_balance_credit = $request->opning_balance;
            $ledger->opning_balance_debit = 0;
        }
        $ledger->save();
        return redirect()->route('ledger')->with('success','Ledger Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(12, $permissions))
        {
            $payment_v = PaymentVoucher::where('buy_ledger_id', $id)->orWhere('to_ledger_id', $id)->where('society_id', '=', Auth::user()->society_id)->count();
            $receipts_v = ReceiptsVoucher::where('buy_ledger_id', $id)->orWhere('to_ledger_id', $id)->where('society_id', '=', Auth::user()->society_id)->count();
            $journal_v = JournalVoucher::where('buy_ledger_id', $id)->orWhere('to_ledger_id', $id)->where('society_id', '=', Auth::user()->society_id)->count();
            $invoice = Invoice::where('by_ledger', $id)->orWhere('to_ledger', 'like', '%"to_ledger_id":"'.$id.'"%')->where('society_id', '=', Auth::user()->society_id)->count();
            if($payment_v != 0)
            {
                return redirect()->route('ledger')->with('error','This ledger are use in payment voucher so remove first payment voucher befor remove ledger.');
            }

            if($receipts_v != 0)
            {
                return redirect()->route('ledger')->with('error','This ledger are use in receipts voucher so remove first receipts voucher befor remove ledger.');
            }

            if($journal_v != 0)
            {
                return redirect()->route('ledger')->with('error','This ledger are use in journal voucher so remove first journal voucher befor remove ledger.');
            }

            if($invoice != 0)
            {
                return redirect()->route('ledger')->with('error','This ledger are use in invoice so remove first invoice befor remove ledger.');
            }
            Ledger::where('id',$id)->delete();
            return redirect()->route('ledger')->with('success','Ledger deleted successfully');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function export(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(14, $permissions))
        {
            return Excel::download(new ExportLedger, 'ledger.xlsx');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function import(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(13, $permissions))
        {
            $import = Excel::import(new ImportLedger, request()->file('file'));
            return redirect()->back()->with('success','Data Imported Successfully');
        }
        else{
            return redirect()->back();
        }
    }

    public function deleteAll(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(12, $permissions))
        {
            if(!empty($request->ids))
            {
                $ids = json_decode($request->ids);
                foreach($ids as $id)
                {
                    $payment_v = PaymentVoucher::where('buy_ledger_id', $id)->orWhere('to_ledger_id', $id)->where('society_id', '=', Auth::user()->society_id)->count();
                    $receipts_v = ReceiptsVoucher::where('buy_ledger_id', $id)->orWhere('to_ledger_id', $id)->where('society_id', '=', Auth::user()->society_id)->count();
                    $journal_v = JournalVoucher::where('buy_ledger_id', $id)->orWhere('to_ledger_id', $id)->where('society_id', '=', Auth::user()->society_id)->count();
                    $invoice = Invoice::where('by_ledger', $id)->orWhere('to_ledger', 'like', '%"to_ledger_id":"'.$id.'"%')->where('society_id', '=', Auth::user()->society_id)->count();
                    $ledger = Ledger::where('id', $id)->first();
                    if($payment_v != 0)
                    {
                        return redirect()->route('ledger')->with('error', $ledger->name.' ledger are use in payment voucher so remove first payment voucher befor remove ledger.');
                    }

                    if($receipts_v != 0)
                    {
                        return redirect()->route('ledger')->with('error', $ledger->name.' ledger are use in receipts voucher so remove first receipts voucher befor remove ledger.');
                    }

                    if($journal_v != 0)
                    {
                        return redirect()->route('ledger')->with('error', $ledger->name.' ledger are use in journal voucher so remove first journal voucher befor remove ledger.');
                    }

                    if($invoice != 0)
                    {
                        return redirect()->route('ledger')->with('error', $ledger->name.' ledger are use in invoice so remove first invoice befor remove ledger.');
                    }
                    Ledger::where('id',$id)->delete();
                }
                return redirect()->route('ledger')->with('success','Ledger deleted successfully');
            }
            else
            {
                return redirect()->route('ledger')->with('error','Please select record');
            }
        }
        else
        {
            return redirect()->back();
        }
    }
    
    public function demoFile()
    {
        $path  = config('app.url'); 
        $path = $path.'/assets/demo/ledger-demo.csv';
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/stream',
            'Content-Disposition' => 'inline; filename="ledger-demo.csv"'
        ]);
    }
}
