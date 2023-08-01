<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Flat;
use Auth;

class FlatController extends Controller
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
            if(in_array(6, $permissions))
            {
                $data = array();
                $data['page_title'] = "Flat"; 
                $data['sub_title'] = "Flat List";
                $flats = Flat::with('society')->where('society_id', Auth::user()->society_id)->get();
                $data['flats'] = $flats;
                return view('flat/index',$data);
            }
            else{
                return redirect()->back();
            }
        }else{
            return redirect('/login');
        }
    }
}
