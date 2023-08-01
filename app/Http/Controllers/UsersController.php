<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Society;
use App\User;
use Auth;
use File;
use App\Roles;

class UsersController extends Controller
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
            if(in_array(2, $permissions))
            {
                $data = array();
                $data['page_title'] = "Users"; 
                $data['sub_title'] = "Users List";
                $users = User::where('society_id',Auth::user()->society_id)->get();
                $data['users'] = $users;
                return view('society-user/index',$data);
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

        if(in_array(3, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Users"; 
            $data['roles'] = Roles::where('role_status', 1)->get();
            return view('society-user.create', $data);
        }
        else{
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            's_user_name' => 'required',
            's_user_email' => 'required|email',
            's_user_mobile_number' => 'required|numeric',
            's_user_wp_number' => 'required|numeric',
            'password' => 'required',
            'image' => 'required',
            's_user__status' => 'required',
            'role' => 'required'
        ]);

        $imageName = time().'.'.$request->image->extension();       
        $request->image->move(public_path('users'), $imageName);

        $user = new User();
        $user->society_id = Auth::user()->society_id;
        $user->s_user_name = $request->s_user_name;
        $user->s_user_email = $request->s_user_email;
        $user->s_user_mobile_number = $request->s_user_mobile_number;
        $user->s_user_wp_number = $request->s_user_wp_number;
        $user->password = $request->password;
        $user->password_md5 = md5($request->password);
        $user->user_photo = $imageName;
        $user->s_user__status = $request->s_user__status;
        $user->role = $request->role;
        $user->created_by = Auth::user()->id;
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_by = Auth::user()->id;
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();
        return redirect()->route('users')->with('success','User Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(4, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit User"; 
            $data['socitys'] = Society::where('status', 1)->get();
            $data['roles'] = Roles::where('role_status', 1)->get();
            $data['user'] = User::find($id);
            return view('society-user.edit', $data);
        }
        else{
            return redirect()->back();
        } 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'society_id' => 'required',
            's_user_name' => 'required',
            's_user_email' => 'required|email',
            's_user_mobile_number' => 'required|numeric',
            's_user_wp_number' => 'required|numeric',
            'password' => 'required',
            's_user__status' => 'required',
            'role' => 'required'
        ]);

        $user = User::find($id);
        if(isset($request->image))
        {
            if($user->user_photo != '')
            {
                $image_path = public_path('users/').$user->user_photo;  // Value is not URL but directory file path
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $imageName = time().'.'.$request->image->extension();       
            $request->image->move(public_path('users'), $imageName);
            $user->user_photo = $imageName;
        }

        $user->society_id = Auth::user()->society_id;
        $user->s_user_name = $request->s_user_name;
        $user->s_user_email = $request->s_user_email;
        $user->s_user_mobile_number = $request->s_user_mobile_number;
        $user->s_user_wp_number = $request->s_user_wp_number;
        $user->password = $request->password;
        $user->password_md5 = md5($request->password);
        $user->s_user__status = $request->s_user__status;
        $user->role = $request->role;
        $user->created_by = Auth::user()->id;
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_by = Auth::user()->id;
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();

        return redirect()->route('users')->with('success','User Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(5, $permissions))
        {
            $user = User::find($id);
            if($user->user_photo != '')
            {
                $image_path = public_path('users/').$user->user_photo;  // Value is not URL but directory file path
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $user->delete();
            return redirect()->route('users')->with('success','User Successfully deleted.');
        }
        else{
            return redirect()->back();
        }
    }
}
