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
    <li class="breadcrumb-item"><a href="{{url('role-permission')}}">{{$page_title}}</a></li>    
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title">{{$sub_title}}
          @if(in_array(73, $permissions))
            <a href="{{route('role-permission.create')}}" role="button" class="btn btn-success float-right">Add Role Permission</a>  
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
                <th>SOCIETY NAME</th>
                <th>ROLE</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
                @if(!empty($role_permissions))
                    @foreach($role_permissions as $rp)
                        <tr>
                            <td>{{$rp->society->society_name}}</td>
                            <td>{{$rp->role->role_name}}</td>
                            <td>            
                              @if(in_array(74, $permissions))              
                                <a href="{{url('role-permission/edit/'.$rp->id)}}" class="btn btn-primary btn-icon pt-1">
                                    <i data-feather="edit"></i>
                                </a>
                              @endif
                              @if(in_array(75, $permissions))
                                <a href="{{url('role-permission/delete/'.$rp->id)}}" class="btn btn-danger btn-icon pt-1">
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