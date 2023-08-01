<?php 
    $permissions = permission(); 
    $permissions = explode(',', $permissions);
?>
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />  
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('group-creations')}}">{{$page_title}}</a></li>    
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title">{{$sub_title}}
          @if(in_array(46, $permissions))
            <a href="{{url('group-creations/create')}}" role="button" class="btn btn-success float-right">Add Group Creations</a>  
          @endif 
        </h6>          
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif              
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>Group Name</th>
                <th>Group Category Name</th>
                <th>Status</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($group_creations))
                @foreach($group_creations as $group_creation)
                    <tr>
                        <td>{{$group_creation->name}}</td>
                        <td>{{$group_creation->groupCategory->name}}</td>
                        <td>
                            @if($group_creation->status == 1)
                                {{ __('Active') }}
                            @else
                                {{ __('Deactive') }}
                            @endif
                        </td>
                        <td>      
                          @if(in_array(47, $permissions))                    
                            <a href="{{url('group-creations/edit/'.$group_creation->id)}}" class="btn btn-primary btn-icon pt-1">
                            <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(48, $permissions))
                            <a href="{{url('group-creations/delete/'.$group_creation->id)}}" class="btn btn-danger btn-icon pt-1">
                            <i data-feather="trash"></i>
                            </a>
                          @endif
                        </td>
                    </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush