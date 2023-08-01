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
                <th>SOCIETY REGSTRATION NO.</th>
                <th>MOBILE</th>
                <th>EMAIL</th>
                <th>CREATING DATE</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
                @foreach($societys as $society)
                    <tr>
                        <td>{{$society->society_name}}</td>
                        <td>{{$society->society_name_number}}</td>
                        <td>{{$society->mobile}}</td>
                        <td>{{$society->emailid}}</td>
                        <td>{{date('Y-m-d',strtotime($society->society_name_date))}}</td>
                        <td>                          
                          <a href="{{url('society/edit/'.$society->id)}}" class="btn btn-primary btn-icon pt-1">
                            <i data-feather="edit"></i>
                          </a>
                          <a href="{{url('society/delete/'.$society->id)}}" class="btn btn-danger btn-icon pt-1">
                            <i data-feather="trash"></i>
                          </a>
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