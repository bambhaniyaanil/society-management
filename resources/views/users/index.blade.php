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
    <li class="breadcrumb-item"><a href="{{url('user')}}">{{$page_title}}</a></li>    
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title">{{$sub_title}}
          @if(in_array(3, $permissions))
            <a href="{{url('user/create')}}" role="button" class="btn btn-success float-right">Add User</a>   
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
                <th>Name</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>WhatsApp Number</th>
                <th>Status</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($users))
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->s_user_name }}</td>
                        <td>{{ $user->s_user_email }}</td>
                        <td>{{ $user->s_user_mobile_number }}</td>
                        <td>{{ $user->s_user_wp_number }}</td>
                        <td>
                            @if($user->s_user__status == 1)
                                Active
                            @else
                                Deactive
                            @endif
                        </td>
                        <td> 
                          @if(in_array(4, $permissions))                         
                              <a href="{{url('user/edit/'.$user->id)}}" class="btn btn-primary btn-icon pt-1">
                              <i data-feather="edit"></i>
                              </a>
                          @endif
                          @if(in_array(5, $permissions))
                            <a href="{{url('user/delete/'.$user->id)}}" class="btn btn-danger btn-icon pt-1">
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