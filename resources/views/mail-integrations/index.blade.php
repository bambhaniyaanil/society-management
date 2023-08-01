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
    <li class="breadcrumb-item"><a href="{{url('mail-integrations')}}">{{$page_title}}</a></li>    
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title">{{$sub_title}}
          @if(in_array(69, $permissions))
            <a href="{{route('mail-integrations.create')}}" role="button" class="btn btn-success float-right">Add Email Integrations</a>  
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
                <th>SMTP USER NAME</th>
                <th>SMTP PASSWORD</th>
                <th>SMTP HOST</th>
                <th>SMTP PORT</th>
                <th>USE SMTP SIMPLE</th>
                <th>FROM EMAIL</th>
                <th>REPLAY EMAIL</th>
                @if(in_array(76, $permissions))
                  <th>Status</th>
                @endif
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
                @foreach($mailintegrations as $mailintegrations)
                    <tr>
                        <td>{{$mailintegrations->smtp_user_name}}</td>
                        <td>{{$mailintegrations->smtp_password}}</td>
                        <td>{{$mailintegrations->smtp_host}}</td>
                        <td>{{$mailintegrations->smtp_port}}</td>
                        <td>{{$mailintegrations->use_smtp_simple}}</td>
                        <td>{{$mailintegrations->from_email}}</td>
                        <td>{{$mailintegrations->replay_email}}</td>
                        @if(in_array(76, $permissions))
                          <td>
                            @if($mailintegrations->status == 1)
                              <a href="{{route('mail-integrations.status', [$mailintegrations->id, 0, $mailintegrations->society_id])}}" role="button" class="btn btn-success float-right">Active</a>   
                            @else
                              <a href="{{route('mail-integrations.status', [$mailintegrations->id, 1, $mailintegrations->society_id])}}" role="button" class="btn btn-danger float-right">Deactive</a>   
                            @endif
                          </td>
                        @endif
                        <td>   
                        @if(in_array(70, $permissions))                       
                          <a href="{{url('mail-integrations/edit/'.$mailintegrations->id)}}" class="btn btn-primary btn-icon pt-1">
                            <i data-feather="edit"></i>
                          </a>
                        @endif
                        @if(in_array(71, $permissions))
                          <a href="{{url('mail-integrations/delete/'.$mailintegrations->id)}}" class="btn btn-danger btn-icon pt-1">
                            <i data-feather="trash"></i>
                          </a>
                        @endif
                        </td>
                    </tr>              
                @endforeach              
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