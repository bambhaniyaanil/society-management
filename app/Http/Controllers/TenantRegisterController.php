<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TenantRegister;
use Auth;
use Session;
use App\Exports\ExportTenantRegister;
use Maatwebsite\Excel\Facades\Excel;

class TenantRegisterController extends Controller
{
    public function index(Request $request)
    {
        if(\Auth::check()){
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(87, $permissions))
            {
                $data = array();
                $data['page_title'] = "Tenant Register"; 
                $data['sub_title'] = "Tenant Register List";
                $start_date = Session::get('start_date');
                $end_date = Session::get('end_date');
                $flat_no = Session::get('end_date');
                $tenant_name = Session::get('end_date');
                $contact_number = Session::get('end_date');
                $agreement_submitted = Session::get('end_date');
                if((isset($start_date) && !empty($start_date)) || (isset($end_date) && !empty($end_date)) || (isset($flat_no) && !empty($flat_no)) || (isset($tenant_name) && !empty($tenant_name)) || (isset($contact_number) && !empty($contact_number)) || (isset($agreement_submitted) && !empty($agreement_submitted)))
                {
                    $data['tenants'] = TenantRegister::where('society_id', Auth::user()->society_id)
                                        ->where(function($que) use ($start_date, $end_date){
                                            if($start_date != '' && $end_date != '')
                                            {
                                                $que->whereDate('period_start_date','>=', $start_date)
                                                    ->whereDate('period_end_date', '<=', $end_date);
                                            }
                                        })
                                        ->where(function($q) use ($flat_no)
                                        {
                                            if($flat_no != '')
                                            {
                                                $q->where('flat_no', 'LIKE', '%'.$flat_no.'%');
                                            }
                                        })
                                        ->where(function($q) use ($tenant_name)
                                        {
                                            if($tenant_name != '')
                                            {
                                                $q->where('tenant_name', 'LIKE', '%'.$tenant_name.'%');
                                            }
                                        })
                                        ->where(function($q) use ($contact_number)
                                        {
                                            if($contact_number != '')
                                            {
                                                $q->where('contact_number', 'LIKE', '%'.$contact_number.'%');
                                            }
                                        })
                                        ->where(function($q) use ($agreement_submitted)
                                        {
                                            if($agreement_submitted != '')
                                            {
                                                $q->where('leave_licence_agreement_submitted', $agreement_submitted);
                                            }
                                        });
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }   
                    if($limit == 'all')
                    {                     
                        $data['tenants'] = $data['tenants']->orderBy('period_start_date', 'asc')->get();
                    }
                    else{
                        $data['tenants'] = $data['tenants']->orderBy('period_start_date', 'asc')->paginate($limit);
                    }
                }
                else{
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }
                    if($limit == 'all')
                    {
                        $data['tenants'] = TenantRegister::where('society_id',Auth::user()->society_id)->get();
                    }
                    else{
                        $data['tenants'] = TenantRegister::where('society_id',Auth::user()->society_id)->paginate($limit);
                    }
                }
                return view('tenant-register/index',$data);
            }
            else
            {
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

        if(in_array(88, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Tenant Register";
            return view('tenant-register.create', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'flat_no' => 'required',
            'tenant_name' => 'required',
            'kyc_detail' => 'required',
            'period_start_date' => 'required',
            'period_end_date' => 'required',
            'leave_licence_agreement_submitted' => 'required',
            'contact_number' => 'required|numeric',
            'status' => 'required'
        ]);

        if($request->parmanent_address == '' && $request->native_address == '')
        {
            return redirect()->back()->withInput()->with('error', 'please any one fill address.');
        }
        $tenant = new TenantRegister();
        $tenant->society_id = Auth::user()->society_id;
        $tenant->flat_no = $request->flat_no;
        $tenant->tenant_name = $request->tenant_name;
        $tenant->permanent_address = $request->parmanent_address;
        $tenant->native_address = $request->native_address;
        $tenant->kyc_detail = $request->kyc_detail;
        $tenant->contact_number = $request->contact_number;
        $tenant->period_start_date = date('Y-m-d', strtotime($request->period_start_date));
        $tenant->period_end_date = date('Y-m-d', strtotime($request->period_end_date));
        $tenant->leave_licence_agreement_submitted = $request->leave_licence_agreement_submitted;
        $tenant->status = $request->status;
        $tenant->save();
        return redirect()->route('tenant-register')->with('success','Tenant Register Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(89, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Tenant Register"; 
            $data['tenant'] = TenantRegister::find($id);
            return view('tenant-register.edit', $data); 
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'flat_no' => 'required',
            'tenant_name' => 'required',
            'kyc_detail' => 'required',
            'period_start_date' => 'required',
            'period_end_date' => 'required',
            'leave_licence_agreement_submitted' => 'required',
            'contact_number' => 'required|numeric',
            'status' => 'required'
        ]);
        if($request->permanent_address == '' && $request->native_address == '')
        {
            return redirect()->back()->withInput()->with('error', 'please any one fill address.');
        }

        $tenant = TenantRegister::find($id);
        $tenant->society_id = Auth::user()->society_id;
        $tenant->flat_no = $request->flat_no;
        $tenant->tenant_name = $request->tenant_name;
        $tenant->permanent_address = $request->permanent_address;
        $tenant->native_address = $request->native_address;
        $tenant->kyc_detail = $request->kyc_detail;
        $tenant->contact_number = $request->contact_number;
        $tenant->period_start_date = date('Y-m-d', strtotime($request->period_start_date));
        $tenant->period_end_date = date('Y-m-d', strtotime($request->period_end_date));
        $tenant->leave_licence_agreement_submitted = $request->leave_licence_agreement_submitted;
        $tenant->status = $request->status;
        $tenant->save();
        return redirect()->route('tenant-register')->with('success','Tenant Register Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(90, $permissions))
        {
            $parking = TenantRegister::find($id);
            $parking->delete();
            return redirect()->route('tenant-register')->with('success','Tenant Register Successfully deleted.');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $data = array();
        $data['page_title'] = "Tenant Register"; 
        $data['sub_title'] = "Tenant Register List";
        if($request->start_date != '')
        {
            if($request->end_date == '')
            {
                return redirect()->route('tenant-register')->with('error','The period end date field is required');
            }
        }

        if($request->start_date == '')
        {
            if($request->end_date != '')
            {
                return redirect()->route('tenant-register')->with('error','First period start date field select');
            }
        }

        $start_date = '';
        $end_date = '';
        if($request->start_date != '')
        {
            $start_date = date('Y-m-d', strtotime($request->start_date));
        }
        if($request->end_date != '')
        {
            $end_date = date('Y-m-d', strtotime($request->end_date));
        }
        $flat_no = $request->flat_no;
        $tenant_name = $request->tenant_name;
        $contact_number = $request->contact_number;
        $agreement_submitted = $request->agreement_submitted;
        // echo $from_date.'  '.$to_date; exit;
        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);
        Session::put('flat_no', $flat_no);
        Session::put('tenant_name', $tenant_name);
        Session::put('contact_number', $contact_number);
        Session::put('agreement_submitted', $agreement_submitted);
        if($start_date > $end_date)
        {
            return redirect()->route('tenant-register')->with('error','Date is invalid please select proper date!');
        }
        $data['tenants'] = TenantRegister::where('society_id', Auth::user()->society_id)
                                    ->where(function($que) use ($start_date, $end_date){
                                        if($start_date != '' && $end_date != '')
                                        {
                                            $que->whereDate('period_start_date','>=', $start_date)
                                                ->whereDate('period_end_date', '<=', $end_date);
                                        }
                                    })
                                    ->where(function($q) use ($flat_no)
                                    {
                                        if($flat_no != '')
                                        {
                                            $q->where('flat_no', 'LIKE', '%'.$flat_no.'%');
                                        }
                                    })
                                    ->where(function($q) use ($tenant_name)
                                    {
                                        if($tenant_name != '')
                                        {
                                            $q->where('tenant_name', 'LIKE', '%'.$tenant_name.'%');
                                        }
                                    })
                                    ->where(function($q) use ($contact_number)
                                    {
                                        if($contact_number != '')
                                        {
                                            $q->where('contact_number', 'LIKE', '%'.$contact_number.'%');
                                        }
                                    })
                                    ->where(function($q) use ($agreement_submitted)
                                    {
                                        if($agreement_submitted != '')
                                        {
                                            $q->where('leave_licence_agreement_submitted', $agreement_submitted);
                                        }
                                    });
            $limit = $request->limit;
            if($limit == '')
            {
                $limit = 10;
            }   
            if($limit == 'all')
            {                     
                $data['tenants'] = $data['tenants']->orderBy('period_start_date', 'asc')->get();
            }
            else{
                $data['tenants'] = $data['tenants']->orderBy('period_start_date', 'asc')->paginate($limit);
            }
            // echo '<pre>'; print_r($data['tenants']->toArray()); exit;
        return view('tenant-register/index', $data);
    }

    public function export(Request $request)
    {
        return Excel::download(new ExportTenantRegister, 'parking-register.xlsx');
    }

    public function reset()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('flat_no');
        Session::forget('tenant_name');
        Session::forget('contact_number');
        Session::forget('agreement_submitted');
        return response()->json();
    }
}

