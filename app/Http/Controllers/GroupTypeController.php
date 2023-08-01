<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroupType;
use App\GroupCategory;
use Auth;
use Illuminate\Validation\Rule;

class GroupTypeController extends Controller
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

            if(in_array(37, $permissions))
            {
                $data = array();
                $data['page_title'] = "Group Type"; 
                $data['sub_title'] = "Group Type List";
                $data['group_types'] = GroupType::get();
                return view('group-type/index', $data);
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

        if(in_array(38, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Group Type";
            return view('group-type/create', $data);
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
                        'required', Rule::unique('sm_group_type', 'name')->where(function($query) {
                            $query->where('society_id', '=', Auth::user()->society_id);
                        }),
                    ]
        ]);

        if($validated) 
        {
            $data = $request->all();
            $data['society_id'] = Auth::user()->society_id;
            $save = GroupType::create($data);
            return redirect()->route('group-type')->with('success','Group Type Successfully created.');
        }
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(39, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Group Type";
            $data['group_type'] = GroupType::where('id', $id)->first();
            return view('group-type/edit', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $group_type = GroupType::find($id);
        if($group_type->name != $request->name)
        {
            $validated = $request->validate([
                'name' => [
                            'required', Rule::unique('sm_group_type', 'name')->where(function($query) {
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
        if($validated)
        {
            $group_type->name = $request->name;
            $group_type->status = $request->status;
            $group_type->save();
            return redirect()->route('group-type')->with('success','Group Type Successfully updated.');
        }
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(40, $permissions))
        {
            $group_category = GroupCategory::where('group_type_id', $id)->count();
            if($group_category == 0)
            {
                GroupType::where('id',$id)->delete();
                return redirect()->route('group-type')->with('success','Group Type deleted successfully');
            }
            else
            {
                return redirect()->route('group-type')->with('error', 'This Groupe Type are Use in Group Categor so remove first group category befor remove group type.');
            }
        }
        else
        {
            return redirect()->back();
        }
    }
}
