<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ParkingRegister;
use Auth;
use App\Exports\ExportParkingRegister;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ParkingRegisterController extends Controller
{
    public function index(Request $request)
    {
        if(\Auth::check()){
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(83, $permissions))
            {
                $data = array();
                $data['page_title'] = "Tenant Register"; 
                $data['sub_title'] = "Tenant Register List";

                $sticker_no = Session::get('sticker_no');
                $vehicle_type = Session::get('vehicle_type');
                $vehicle_number = Session::get('vehicle_number');
                $prflat_no = Session::get('prflat_no');
                $prtenantowner = Session::get('prtenantowner');
                $prcontact_number = Session::get('prcontact_number');

                if((isset($sticker_no) && !empty($sticker_no)) || (isset($vehicle_type) && !empty($vehicle_type)) || (isset($vehicle_number) && !empty($vehicle_number)) || (isset($prflat_no) && !empty($prflat_no)) || (isset($prtenantowner) && !empty($prtenantowner)) || (isset($prcontact_number) && !empty($prcontact_number)))
                {
                    $data['parkings'] = ParkingRegister::where('society_id', Auth::user()->society_id)
                                        ->where(function($q) use ($sticker_no)
                                        {
                                            if($sticker_no != '')
                                            {
                                                $q->where('sticker_no', $sticker_no);
                                            }
                                        })
                                        ->where(function($q) use ($vehicle_type)
                                        {
                                            if($vehicle_type != '')
                                            {
                                                $q->where('vehicle_type', 'LIKE', '%'.$vehicle_type.'%');
                                            }
                                        })
                                        ->where(function($q) use ($vehicle_number)
                                        {
                                            if($vehicle_number != '')
                                            {
                                                $q->where('vehicle_number', 'LIKE', '%'.$vehicle_number.'%');
                                            }
                                        })
                                        ->where(function($q) use ($prflat_no)
                                        {
                                            if($prflat_no != '')
                                            {
                                                $q->where('flat_no', 'LIKE', '%'.$prflat_no.'%');
                                            }
                                        })
                                        ->where(function($q) use ($prtenantowner)
                                        {
                                            if($prtenantowner != '')
                                            {
                                                $q->where('tenat_name', 'LIKE', '%'.$prtenantowner.'%')
                                                    ->orWhere('owner_name', 'LIKE', '%'.$prtenantowner.'%');
                                            }
                                        })
                                        ->where(function($q) use ($prcontact_number)
                                        {
                                            if($prcontact_number != '')
                                            {
                                                $q->where('contact_number', 'LIKE', '%'.$prcontact_number.'%');
                                            }
                                        });
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }   
                    if($limit == 'all')
                    {                     
                        $data['parkings'] = $data['parkings']->get();
                    }
                    else{
                        $data['parkings'] = $data['parkings']->paginate($limit);
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
                        $data['parkings'] = ParkingRegister::where('society_id',Auth::user()->society_id)->get();
                    }
                    else{
                        $data['parkings'] = ParkingRegister::where('society_id',Auth::user()->society_id)->paginate($limit);
                    }
                }
                return view('parking-register/index',$data);
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

        if(in_array(84, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Parking Register";
            return view('parking-register.create', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'sticker_no' => 'required',
            'vehicle_type' => 'required',
            'vehicle_number' => 'required',
            'flat_no' => 'required',
            'tenant_owner' => 'required',
            'contact_number' => 'required|numeric',
            'status' => 'required'
        ]);

        $parking = new ParkingRegister();
        $parking->society_id = Auth::user()->society_id;
        $parking->sticker_no = $request->sticker_no;
        $parking->vehicle_type = $request->vehicle_type;
        $parking->vehicle_number = $request->vehicle_number;
        $parking->flat_no = $request->flat_no;
        if($request->tenant_owner_select == 'Tenant')
        {
            $parking->tenat_name = $request->tenant_owner;
        }
        else
        {
            $parking->owner_name = $request->tenant_owner;
        }
        $parking->contact_number = $request->contact_number;
        $parking->status = $request->status;
        $parking->save();
        return redirect()->route('parking-register')->with('success','Parking Register Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(85, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Parking Register"; 
            $data['parking'] = ParkingRegister::find($id);
            return view('parking-register.edit', $data); 
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sticker_no' => 'required',
            'vehicle_type' => 'required',
            'vehicle_number' => 'required',
            'flat_no' => 'required',
            'tenant_owner' => 'required',
            'contact_number' => 'required|numeric',
            'status' => 'required'
        ]);

        $parking = ParkingRegister::find($id);
        $parking->society_id = Auth::user()->society_id;
        $parking->sticker_no = $request->sticker_no;
        $parking->vehicle_type = $request->vehicle_type;
        $parking->vehicle_number = $request->vehicle_number;
        $parking->flat_no = $request->flat_no;
        if($request->tenant_owner_select == 'Tenant')
        {
            $parking->tenat_name = $request->tenant_owner;
            $parking->owner_name = '';
        }
        else
        {
            $parking->owner_name = $request->tenant_owner;
            $parking->tenat_name = '';
        }
        $parking->contact_number = $request->contact_number;
        $parking->status = $request->status;
        $parking->save();
        return redirect()->route('parking-register')->with('success','Parking Register Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(86, $permissions))
        {
            $parking = ParkingRegister::find($id);
            $parking->delete();
            return redirect()->route('parking-register')->with('success','Parking Register Successfully deleted.');
        }
        else{
            return redirect()->back();
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new ExportParkingRegister, 'parking-register.xlsx');
    }

    public function search(Request $request)
    {
        $data = array();
        $data['page_title'] = "Parking Register"; 
        $data['sub_title'] = "Parking Register List";
        
        $sticker_no = $request->sticker_no;
        $vehicle_type = $request->vehicle_type;
        $vehicle_number = $request->vehicle_number;
        $prflat_no = $request->prflat_no;
        $prtenantowner = $request->prtenantowner;
        $prcontact_number = $request->prcontact_number;
        // echo $from_date.'  '.$to_date; exit;
        Session::put('sticker_no', $sticker_no);
        Session::put('vehicle_type', $vehicle_type);
        Session::put('vehicle_number', $vehicle_number);
        Session::put('prflat_no', $prflat_no);
        Session::put('prtenantowner', $prtenantowner);
        Session::put('prcontact_number', $prcontact_number);

        $data['parkings'] = ParkingRegister::where('society_id', Auth::user()->society_id)
                                    ->where(function($q) use ($sticker_no)
                                    {
                                        if($sticker_no != '')
                                        {
                                            $q->where('sticker_no', $sticker_no);
                                        }
                                    })
                                    ->where(function($q) use ($vehicle_type)
                                    {
                                        if($vehicle_type != '')
                                        {
                                            $q->where('vehicle_type', 'LIKE', '%'.$vehicle_type.'%');
                                        }
                                    })
                                    ->where(function($q) use ($vehicle_number)
                                    {
                                        if($vehicle_number != '')
                                        {
                                            $q->where('vehicle_number', 'LIKE', '%'.$vehicle_number.'%');
                                        }
                                    })
                                    ->where(function($q) use ($prflat_no)
                                    {
                                        if($prflat_no != '')
                                        {
                                            $q->where('flat_no', 'LIKE', '%'.$prflat_no.'%');
                                        }
                                    })
                                    ->where(function($q) use ($prtenantowner)
                                    {
                                        if($prtenantowner != '')
                                        {
                                            $q->where('tenat_name', 'LIKE', '%'.$prtenantowner.'%')
                                                ->orWhere('owner_name', 'LIKE', '%'.$prtenantowner.'%');
                                        }
                                    })
                                    ->where(function($q) use ($prcontact_number)
                                    {
                                        if($prcontact_number != '')
                                        {
                                            $q->where('contact_number', 'LIKE', '%'.$prcontact_number.'%');
                                        }
                                    });
            $limit = $request->limit;
            if($limit == '')
            {
                $limit = 10;
            }   
            if($limit == 'all')
            {                     
                $data['parkings'] = $data['parkings']->get();
            }
            else{
                $data['parkings'] = $data['parkings']->paginate($limit);
            }
            // echo '<pre>'; print_r($data['tenants']->toArray()); exit;
        return view('parking-register/index', $data);
    }

    public function reset()
    {
        Session::forget('sticker_no');
        Session::forget('vehicle_type');
        Session::forget('vehicle_number');
        Session::forget('prflat_no');
        Session::forget('prtenantowner');
        Session::forget('prcontact_number');
        return response()->json();
    }
}
