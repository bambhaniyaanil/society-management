<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\RolePermission;
use App\Menus;
use App\Society;
use App\Roles;

class RolePermissionController extends Controller
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

            if(in_array(72, $permissions))
            {
                $data = array();
                $data['page_title'] = "Role Permission"; 
                $data['sub_title'] = "Role Permission List";
                $role_permissions = RolePermission::where('society_id', Auth::user()->society_id)->get();
                $data['role_permissions'] = $role_permissions;
                return view('role-permission/index',$data);
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

        if(in_array(73, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Role Permission"; 
            $data['pmenus'] = Menus::where('pmenu', 0)->get();
            $data['socitys'] = Society::where('status', 1)->get();
            $data['roles'] = Roles::where('role_status', 1)->get();
            return view('role-permission.create', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
        ]);

        $permission_count = RolePermission::where('role_id', $request->role_id)->where('society_id', Auth::user()->society_id)->count();
        if($permission_count != 0)
        {
            return redirect()->back()->withInput()->with('error', 'This role permission alredy added so plese update this role permission');   
        }        
        if(empty($request->pid))
        {
            return redirect()->back()->withInput()->with('error', 'Plese select any one permission');
        }

        $role_permission = new RolePermission();
        $role_permission->society_id = Auth::user()->society_id;
        $role_permission->role_id = $request->role_id;
        $role_permission->permission_id = implode(',',$request->pid);
        $role_permission->save();
        return redirect()->route('role-permission')->with('success','Role Permission Successfully Added.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(74, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Role Permission"; 
            $data['role_permission'] = RolePermission::find($id);
            $data['pmenus'] = Menus::where('pmenu', 0)->get();
            $data['socitys'] = Society::where('status', 1)->get();
            $data['roles'] = Roles::where('role_status', 1)->get();
            return view('role-permission.edit', $data);  
        }
        else
        {
            return redirect()->back();
        } 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required',
        ]);

        if(empty($request->pid))
        {
            return redirect()->back()->withInput()->with('error', 'Plese select any one permission');
        }

        $role_permission = RolePermission::find($id);
        if($role_permission->role_id != $request->role_id)
        {
            $permission_count = RolePermission::where('role_id', $request->role_id)->where('society_id', Auth::user()->society_id)->count();
            if($permission_count != 0)
            {
                return redirect()->back()->withInput()->with('error', 'This role permission alredy added so plese update this role permission');   
            }
        }

        $role_permission->society_id = Auth::user()->society_id;
        $role_permission->role_id = $request->role_id;
        $role_permission->permission_id = implode(',',$request->pid);
        $role_permission->save();
        return redirect()->route('role-permission')->with('success','Role Permission Successfully Updated.');
    }
    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(75, $permissions))
        {
            $roles = RolePermission::find($id);
            $roles->delete();
            return redirect()->route('role-permission')->with('success','Role Permission Successfully deleted.');
        }
        else
        {
            return redirect()->back();
        }
    }
}
