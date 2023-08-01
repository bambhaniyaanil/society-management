<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroupCategory;
use Auth;
use App\GroupType;
use App\GroupCreations;
use Illuminate\Validation\Rule;

class GroupCategoryController extends Controller
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

            if(in_array(41, $permissions))
            {
                $data = array();
                $data['page_title'] = "Group Category"; 
                $data['sub_title'] = "Group Category List";
                $data['group_categorys'] = GroupCategory::where('society_id', Auth::user()->society_id)->get();
                return view('group-category/index', $data);
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

        if(in_array(42, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Group Category";
            $data['group_types'] = GroupType::where('status', 1)->where('society_id', '=', Auth::user()->society_id)->get();
            return view('group-category/create', $data);
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
                        'required', Rule::unique('sm_group_catagory', 'name')->where(function($query) {
                            $query->where('society_id', '=', Auth::user()->society_id);
                        }),
                    ]
        ]);

        if($validated) 
        {
            $data = $request->all();
            $data['society_id'] = Auth::user()->society_id;
            $save = GroupCategory::create($data);
            return redirect()->route('group-category')->with('success','Group Category Successfully created.');
        }
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(43, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Group Category";
            $data['group_types'] = GroupType::where('status', 1)->where('society_id', Auth::user()->society_id)->get();
            $data['group_category'] = GroupCategory::where('id', $id)->first();
            return view('group-category/edit', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $group_category = GroupCategory::find($id);
        if($group_category->name != $request->name)
        {
            $validated = $request->validate([
                'name' => [
                            'required', Rule::unique('sm_group_catagory', 'name')->where(function($query) {
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
            $group_category->name = $request->name;
            $group_category->group_type_id = $request->group_type_id;
            $group_category->status = $request->status;
            $group_category->save();
            return redirect()->route('group-category')->with('success','Group Category Successfully updated.');
        }
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(44, $permissions))
        {
            $group_creation = GroupCreations::where('group_category_id', $id)->count();
            if($group_creation == 0)
            {
                GroupCategory::where('id',$id)->delete();
                return redirect()->route('group-category')->with('success','Group Category deleted successfully');
            }
            else{
                return redirect()->route('group-category')->with('error','This Groupe Category are Use in Group Creations so remove first group creations befor remove group category.');   
            }
        }
        else
        {
            return redirect()->back();
        }
    }
}
