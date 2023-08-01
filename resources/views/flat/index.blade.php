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
        <h6 class="card-title">{{$sub_title}}</h6>          
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif              
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>SOCIETY NAME</th>
                <th>FALTE NUMBER</th>
                <th>FLATE OWNERS</th>
                <th>OWNER MOBILES</th>
                <th>OWNER WP</th>
                <th>REGISTER DATE</th>
                <th>Status</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
                @foreach($flats as $flat)
                    <tr>
                        <td>{{$flat->society->society_name}}</td>
                        <td>{{$flat->wings.'-'. $flat->flat_no}}</td>
                        <td>{{$flat->flat_owners}}</td>
                        <td>
                            <?php $o_mobile = explode(',', $flat->flat_owner_mobiles); ?>
                            {{$o_mobile[0]}}
                        </td>
                        <td>
                            <?php $o_wp_mobile = explode(',', $flat->flat_owner_wp_numbers); ?>
                            {{$o_wp_mobile[0]}}
                        </td>
                        <td>{{date('Y-m-d',strtotime($flat->created_at))}}</td>
                        <td>
                          @if($flat->status == 1)
                            Active
                          @else
                            Deactive
                          @endif
                        </td>
                        <td>    
                          @if(in_array(7, $permissions))                      
                            <a href="{{url('flat/edit/'.$flat->id)}}" class="btn btn-primary btn-icon pt-1">
                              <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(8, $permissions)) 
                            <a href="{{url('flat/delete/'.$flat->id)}}" class="btn btn-danger btn-icon pt-1">
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