<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Society;
use Auth;
use File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(\Auth::check()){
            return redirect('/dashboard');
        }else{
            return redirect('/login');
        }
    }

    public function editProfile(Request $request)
    {
        if(\Auth::check())
        {
            $id = Auth::user()->id;
            $data = array();
            $data['page_title'] = "Update Profile";        
            $user = User::find($id);
            $data['user'] = $user;
            $data['socitys'] = Society::where('status', 1)->get();
            return view('users/edit-profile',$data); 
        }
        else
        {
            return redirect('/login');
        }       
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;
        
        $request->validate([
            's_user_email' => 'required|email',                        
        ]);

        $user = User::find($id);

        $user->society_id = $request->society_id;
        $user->s_user_name = $request->s_user_name;
        $user->s_user_email = $request->s_user_email;
        $user->s_user_mobile_number = $request->s_user_mobile_number;      
        $user->s_user_wp_number = $request->s_user_wp_number;
        $user->save();                
        return redirect('dashboard')->with('success','Update Pofile Successfully.');
    }

    public function profile(Request $request)
    {
        if(\Auth::check())
        {
            $id = Auth::user()->id;
            $data = array();
            $data['page_title'] = "Profile";        
            $user = User::where('id', $id)->first();
            $data['user'] = $user;
            return view('users/profile',$data); 
        }
        else
        {
            return redirect('/login');
        }
    }
}
