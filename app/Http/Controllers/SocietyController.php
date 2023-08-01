<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Society;
use Auth;

class SocietyController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth');
    }

    public function index()
    {
        if(\Auth::check()){
            $data = array();
            $data['page_title'] = "Society"; 
            $data['sub_title'] = "Society List";
            $societys = Society::where('status', 1)->get();
            $data['societys'] = $societys;
            return view('society/index',$data);

        }else{
            return redirect('/login');
        }
    }
}
