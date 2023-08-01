<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroupCreations;
use Auth;
use App\GroupCategory;
use Illuminate\Validation\Rule;

class GroupCreationsController extends Controller
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

            if(in_array(45, $permissions))
            {
                $data = array();
                $data['page_title'] = "Group Creations"; 
                $data['sub_title'] = "Group Creations List";
                $data['group_creations'] = GroupCreations::where('society_id', Auth::user()->society_id)->get();
                return view('group-creations/index', $data);
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

        if(in_array(46, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Group Creations";
            $data['group_categorys'] = GroupCategory::where('status', 1)->get();
            return view('group-creations/create', $data);
        }
        else
        {
            return redirect()->back();   
        }
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                        'required', Rule::unique('sm_group_creations', 'name')->where(function($query) {
                            $query->where('society_id', '=', Auth::user()->society_id);
                        }),
                    ]
        ]);

        if($validated)
        {
            $data = $request->all();
            $data['society_id'] = Auth::user()->society_id;
            $save = GroupCreations::create($data);
            return redirect()->route('group-creations')->with('success','Group Creations Successfully created.');
        }
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(47, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Group Creations";
            $data['group_categorys'] = GroupCategory::where('status', 1)->get();
            $data['group_creation'] = GroupCreations::where('id', $id)->first();
            return view('group-creations/edit', $data);
        }
        else
        {
            return redirect()->back();    
        }
    }

    public function update(Request $request, $id)
    {
        $group_creation = GroupCreations::find($id);
        if($group_creation->name != $request->name)
        {
            $validated = $request->validate([
                'name' => [
                            'required', Rule::unique('sm_group_creations', 'name')->where(function($query) {
                                $query->where('society_id', '=', Auth::user()->society_id);
                            }),
                        ]
            ]);
        }
        else{
            $validated = $request->validate([
                'name' => 'required',
            ]);
        }
        $group_creation->name = $request->name;
        $group_creation->group_category_id = $request->group_category_id;
        $group_creation->status = $request->status;
        $group_creation->save();
        return redirect()->route('group-creations')->with('success','Group Creations Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(48, $permissions))
        {
            GroupCreations::where('id',$id)->delete();
            return redirect()->route('group-creations')->with('success','Group Creations deleted successfully');
        }
        else
        {
            return redirect()->back();
        }
    }
}
