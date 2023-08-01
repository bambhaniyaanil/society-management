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
    <li class="breadcrumb-item"><a href="{{url('tenant-register')}}">{{$page_title}}</a></li>    
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Search</h6>
        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif 
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <?php 
              $limit = 10;
              if(isset($_GET['limit']))
              {
                $limit = $_GET['limit'];
              }
            ?>
            <label style="white-space: nowrap;">Show 
              <select name="dataTableExample_length" id="tenant_limit" aria-controls="dataTableExample" class="custom-select custom-select-sm form-control">
                <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                <option value="30" <?php if($limit == 30) echo 'selected'; ?>>30</option>
                <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                <option value="all" <?php if($limit == 'all') echo 'selected'; ?>>All</option>
              </select> entries
            </label>
          </div>
        </div>
        <form method="post" action="{{route('tenant-register.search')}}">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <?php 
                  $start_date = '';
                  if(Session::get('start_date'))
                  {
                      $start_date = date('d-m-Y', strtotime(Session::get('start_date')));
                  }
                  $end_date = '';
                  if(Session::get('end_date'))
                  {
                      $end_date = date('d-m-Y', strtotime(Session::get('end_date')));
                  }
              ?>
              <div class="dataTables_length" id="dataTableExample_length">
                <label>Period Start Date
                  <div class="input-group date datepicker" id="start_date">
                      <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="start_date" value="{{ $start_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                  </div>
                </label>
                <label>Period End Date
                  <div class="input-group date datepicker" id="end_date">
                      <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="end_date" value="{{ $end_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                  </div>
                </label>
              </div>
            </div>
            <div class="col-sm-12 col-md-3">
              <label>Flat No<br>
                <input type="search" class="form-control" placeholder="Flat No" name="flat_no" value="{{ Session::get('flat_no') }}" aria-controls="dataTableExample">
              </label>
            </div>
            <div class="col-sm-12 col-md-3">
              <label>Tenant Name<br>
                <input type="search" class="form-control" placeholder="Tenant Name" name="tenant_name" value="{{ Session::get('tenant_name') }}" aria-controls="dataTableExample">
              </label>
            </div>
            <div class="col-sm-12 col-md-3">
              <label>Contact Number<br>
                <input type="search" class="form-control" placeholder="Contact Number" name="contact_number" value="{{ Session::get('contact_number') }}" aria-controls="dataTableExample">
              </label>
            </div>
            <div class="col-sm-12 col-md-3">
              <?php 
                $agr_sub = '';
                if(Session::get('agreement_submitted'))
                {
                  $agr_sub = Session::get('agreement_submitted');
                }
              ?>
              <label>Agreement Submitted<br>
                <select name="agreement_submitted"  aria-controls="dataTableExample" class="custom-select custom-select-sm form-control">
                  <option value="" ></option>
                  <option value="Yes" <?php if($agr_sub == 'Yes') echo 'selected'; ?>>Yes</option>
                  <option value="No" <?php if($agr_sub == 'No') echo 'selected'; ?>>NO</option>
                </select>
              </label>
            </div>
            <div class="col-md-12">
              <input type="submit" class="btn btn-info" value="Search Details">
              <button type="button" id="reset" class="btn btn-secondary">Reset Search</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>  
</div>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title">{{$sub_title}}
          @if(in_array(88, $permissions))
            <a href="{{url('tenant-register/create')}}" role="button" class="btn btn-success float-right">Add Tenant Register</a>   
          @endif
          <a type="button" href="{{route('tenant-register.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
            <i class="btn-icon-prepend" data-feather="download"></i>
            Export
          </a>
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
        @foreach($errors->all() as $error)
          <div class="alert alert-danger">
            <p>{{ $error }}</p>
          </div>
        @endforeach            
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Flat No</th>
                <th>Tenant Name</th>
                <th>Contact Number</th>
                <th>Period Start Date</th>
                <th>Period End Date </th>
                <th>Agreement Submitted</th>
                <th>Status</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($tenants))
                @foreach($tenants as $tenant)
                    <tr>
                        <td>{{$tenant->flat_no}}</td>
                        <td>{{$tenant->tenant_name}}</td>
                        <td>{{$tenant->contact_number}}</td>
                        <td>{{date('d-m-Y',strtotime($tenant->period_start_date))}}</td>
                        <td>{{date('d-m-Y',strtotime($tenant->period_end_date))}}</td>
                        <td>{{$tenant->leave_licence_agreement_submitted}}</td>
                        <td>
                          @if($tenant->status == 1)
                            Active
                          @else
                            Deactive
                          @endif
                        </td>
                        <td>      
                          @if(in_array(89, $permissions))                  
                            <a href="{{url('tenant-register/edit/'.$tenant->id)}}" class="btn btn-primary btn-icon pt-1">
                            <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(90, $permissions))
                            <a href="{{url('tenant-register/delete/'.$tenant->id)}}" class="btn btn-danger btn-icon pt-1">
                            <i data-feather="trash"></i>
                            </a>
                          @endif
                        </td>
                    </tr>
                @endforeach
              @else
                  <tr>
                      <td rowspan="6" align="center">Recorde Not Found</td>
                  </tr>
              @endif
            </tbody>
          </table>
          <div class="row">
              <div class="col-sm-12">
                <div class="float-right">
                  @if($limit != 'all')
                    {{ $tenants->onEachSide(1)->appends(['limit' => $limit])->links('pagination::bootstrap-4') }}
                  @endif
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
   $(document).ready(function() {
        $('#start_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
        $('#end_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
    });

    $('#tenant_limit').on('change', function(){
      if($(this).val() == 'all')
      {
        window.location.assign('{{config("app.url")}}/tenant-register?limit='+$(this).val());
      }
      else{
        window.location.assign('{{config("app.url")}}/tenant-register?limit='+$(this).val()+'&page=1');
      }
    })

    $('#reset').on('click', function(){
        jQuery.ajax({
            url: "{{ url('/tenant-register/reset') }}",
            method: 'get',
            success: function(result){
              window.location = window.location.href;
            }
        });
    });
</script>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush