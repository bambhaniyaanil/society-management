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
    <li class="breadcrumb-item"><a href="{{url('users')}}">{{$page_title}}</a></li>    
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title">{{$sub_title}}
          @if(in_array(38, $permissions))
            <!-- <a href="{{url('group-type/create')}}" role="button" class="btn btn-success float-right">Add Group Type</a>    -->
          @endif
        </h6>          
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif              
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>Group Type Name</th>
                <th>Status</th>
                <!-- <th>ACTIONS</th> -->
              </tr>
            </thead>
            <tbody>
              @if(!empty($group_types))
                @foreach($group_types as $group_type)
                    <tr>
                        <td>{{$group_type->name}}</td>
                        <td>
                            @if($group_type->status == 1)
                                {{ __('Active') }}
                            @else
                                {{ __('Deactive') }}
                            @endif
                        </td>
                        <!-- <td>    
                          @if(in_array(39, $permissions))                      
                            <a href="{{url('group-type/edit/'.$group_type->id)}}" class="btn btn-primary btn-icon pt-1">
                            <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(40, $permissions))
                            <a href="{{url('group-type/delete/'.$group_type->id)}}" class="btn btn-danger btn-icon pt-1">
                            <i data-feather="trash"></i>
                            </a>
                          @endif
                        </td> -->
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