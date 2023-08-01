<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ParkingOwnerDetails;
use Auth;
use App\Exports\ExportParkingOwnerDetails;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ParkingOwnerDetailsController extends Controller
{
    public function index(Request $request)
    {
        
        if(\Auth::check()){
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(79, $permissions))
            {
                $data = array();
                $data['page_title'] = "Parking Owner Details"; 
                $data['sub_title'] = "Parking Owner Details List";

                $proflat_no = Session::get('proflat_no');
                $parking_no = Session::get('parking_no');
                $proowner_name = Session::get('proowner_name');
                $procontact_number = Session::get('procontact_number');

                if((isset($proflat_no) && !empty($proflat_no)) || (isset($parking_no) && !empty($parking_no)) || (isset($proowner_name) && !empty($proowner_name)) || (isset($procontact_number) && !empty($procontact_number)))
                {
                    $data['parkingDetails'] = ParkingOwnerDetails::where('society_id', Auth::user()->society_id)
                                        ->where(function($q) use ($proflat_no)
                                        {
                                            if($proflat_no != '')
                                            {
                                                $q->where('flat_no', 'LIKE', '%'.$proflat_no.'%');
                                            }
                                        })
                                        ->where(function($q) use ($parking_no)
                                        {
                                            if($parking_no != '')
                                            {
                                                $q->where('parking_no', 'LIKE', '%'.$parking_no.'%');
                                            }
                                        })
                                        ->where(function($q) use ($proowner_name)
                                        {
                                            if($proowner_name != '')
                                            {
                                                $q->where('owner_name', 'LIKE', '%'.$proowner_name.'%');
                                            }
                                        })
                                        ->where(function($q) use ($procontact_number)
                                        {
                                            if($procontact_number != '')
                                            {
                                                $q->where('contact_number', 'LIKE', '%'.$procontact_number.'%');
                                            }
                                        });
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }   
                    if($limit == 'all')
                    {                     
                        $data['parkingDetails'] = $data['parkingDetails']->get();
                    }
                    else{
                        $data['parkingDetails'] = $data['parkingDetails']->paginate($limit);
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
                        $data['parkingDetails'] = ParkingOwnerDetails::where('society_id',Auth::user()->society_id)->get();
                    }
                    else{
                        $data['parkingDetails'] = ParkingOwnerDetails::where('society_id',Auth::user()->society_id)->paginate($limit);
                    }
                }

                return view('parking-owner-details/index',$data);
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

        if(in_array(80, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Parking Owner Details";
            return view('parking-owner-details.create', $data);
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
            'parking_no' => 'required',
            'owner_name' => 'required',
            'contact_number' => 'required|numeric',
            'status' => 'required'
        ]);

        $parkingDetails = new ParkingOwnerDetails();
        $parkingDetails->society_id = Auth::user()->society_id;
        $parkingDetails->flat_no = $request->flat_no;
        $parkingDetails->parking_no = $request->parking_no;
        $parkingDetails->owner_name = $request->owner_name;
        $parkingDetails->contact_number = $request->contact_number;
        $parkingDetails->status = $request->status;
        $parkingDetails->save();
        return redirect()->route('parking-owner')->with('success','Parking Owner Details Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(81, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Parking Owner Details"; 
            $data['parkingDetail'] = ParkingOwnerDetails::find($id);
            return view('parking-owner-details.edit', $data); 
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
            'parking_no' => 'required',
            'owner_name' => 'required',
            'contact_number' => 'required|numeric',
            'status' => 'required'
        ]);

        $parkingDetails = ParkingOwnerDetails::find($id);
        $parkingDetails->society_id = Auth::user()->society_id;
        $parkingDetails->flat_no = $request->flat_no;
        $parkingDetails->parking_no = $request->parking_no;
        $parkingDetails->owner_name = $request->owner_name;
        $parkingDetails->contact_number = $request->contact_number;
        $parkingDetails->status = $request->status;
        $parkingDetails->save();
        return redirect()->route('parking-owner')->with('success','Parking Owner Details Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(82, $permissions))
        {
            $parkingDetails = ParkingOwnerDetails::find($id);
            $parkingDetails->delete();
            return redirect()->route('parking-owner')->with('success','Parking Owner Details Successfully deleted.');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new ExportParkingOwnerDetails, 'parking-owner-details.xlsx');
    }

    public function search(Request $request)
    {
        $data = array();
        $data['page_title'] = "Parking Owner Details"; 
        $data['sub_title'] = "Parking Owner Details List";
        
        $proflat_no = $request->proflat_no;
        $parking_no = $request->parking_no;
        $proowner_name = $request->proowner_name;
        $procontact_number = $request->procontact_number;
        // echo $from_date.'  '.$to_date; exit;
        Session::put('proflat_no', $proflat_no);
        Session::put('parking_no', $parking_no);
        Session::put('proowner_name', $proowner_name);
        Session::put('procontact_number', $procontact_number);

        $data['parkingDetails'] = ParkingOwnerDetails::where('society_id', Auth::user()->society_id)
                                    ->where(function($q) use ($proflat_no)
                                    {
                                        if($proflat_no != '')
                                        {
                                            $q->where('flat_no', 'LIKE', '%'.$proflat_no.'%');
                                        }
                                    })
                                    ->where(function($q) use ($parking_no)
                                    {
                                        if($parking_no != '')
                                        {
                                            $q->where('parking_no', 'LIKE', '%'.$parking_no.'%');
                                        }
                                    })
                                    ->where(function($q) use ($proowner_name)
                                    {
                                        if($proowner_name != '')
                                        {
                                            $q->where('owner_name', 'LIKE', '%'.$proowner_name.'%');
                                        }
                                    })
                                    ->where(function($q) use ($procontact_number)
                                    {
                                        if($procontact_number != '')
                                        {
                                            $q->where('contact_number', 'LIKE', '%'.$procontact_number.'%');
                                        }
                                    });
            $limit = $request->limit;
            if($limit == '')
            {
                $limit = 10;
            }   
            if($limit == 'all')
            {                     
                $data['parkingDetails'] = $data['parkingDetails']->get();
            }
            else{
                $data['parkingDetails'] = $data['parkingDetails']->paginate($limit);
            }
            // echo '<pre>'; print_r($data['tenants']->toArray()); exit;
        return view('parking-owner-details/index', $data);
    }

    public function reset()
    {
        Session::forget('proflat_no');
        Session::forget('parking_no');
        Session::forget('proowner_name');
        Session::forget('procontact_number');
        return response()->json();
    }
}
