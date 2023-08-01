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
    <li class="breadcrumb-item"><a href="{{url('parking-owner')}}">{{$page_title}}</a></li>    
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
              <select name="dataTableExample_length" id="parking_limit" aria-controls="dataTableExample" class="custom-select custom-select-sm form-control">
                <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                <option value="30" <?php if($limit == 30) echo 'selected'; ?>>30</option>
                <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                <option value="all" <?php if($limit == 'all') echo 'selected'; ?>>All</option>
              </select> entries
            </label>
          </div>
        </div>
        <form method="post" action="{{route('parking-owner.search')}}">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-sm-12 col-md-3">
              <label>Flat No<br>
                <input type="search" class="form-control" placeholder="Flat No" name="proflat_no" value="{{ Session::get('proflat_no') }}" aria-controls="dataTableExample">
              </label>
            </div>
            <div class="col-sm-12 col-md-3">
              <label>Parking No<br>
                <input type="search" class="form-control" placeholder="Parking No" name="parking_no" value="{{ Session::get('parking_no') }}" aria-controls="dataTableExample">
              </label>
            </div>
            <div class="col-sm-12 col-md-3">
              <label>Owner Name<br>
                <input type="search" class="form-control" placeholder="Owner Name" name="proowner_name" value="{{ Session::get('proowner_name') }}" aria-controls="dataTableExample">
              </label>
            </div>
            <div class="col-sm-12 col-md-3">
              <label>Contact Number<br>
                <input type="search" class="form-control" placeholder="Contact Number" name="procontact_number" value="{{ Session::get('procontact_number') }}" aria-controls="dataTableExample">
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
          @if(in_array(80, $permissions))
            <a href="{{url('parking-owner/create')}}" role="button" class="btn btn-success float-right">Add Parking Owner Details</a>   
          @endif
          <a type="button" href="{{route('parking-owner.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
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
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>Flat NO</th>
                <th>Parking NO</th>
                <th>Owner Name</th>
                <th>Contact Number</th>
                <th>Status</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($parkingDetails))
                @foreach($parkingDetails as $parkingDetail)
                    <tr>
                        <td>{{$parkingDetail->flat_no}}</td>
                        <td>{{$parkingDetail->parking_no}}</td>
                        <td>{{$parkingDetail->owner_name}}</td>
                        <td>{{$parkingDetail->contact_number}}</td>
                        <td>
                          @if($parkingDetail->status == 1)
                            Active
                          @else
                            Deactive
                          @endif
                        </td>
                        <td>      
                          @if(in_array(81, $permissions))                  
                            <a href="{{url('parking-owner/edit/'.$parkingDetail->id)}}" class="btn btn-primary btn-icon pt-1">
                            <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(82, $permissions))
                            <a href="{{url('parking-owner/delete/'.$parkingDetail->id)}}" class="btn btn-danger btn-icon pt-1">
                            <i data-feather="trash"></i>
                            </a>
                          @endif
                        </td>
                    </tr>
                @endforeach
              @endif
            </tbody>
          </table>
          <div class="row">
              <div class="col-sm-12">
                <div class="float-right">
                  @if($limit != 'all')
                    {{ $parkingDetails->onEachSide(1)->appends(['limit' => $limit])->links('pagination::bootstrap-4') }}
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
  $('#parking_limit').on('change', function(){
      if($(this).val() == 'all')
      {
        window.location.assign('{{config("app.url")}}/parking-owner?limit='+$(this).val());
      }
      else{
        window.location.assign('{{config("app.url")}}/parking-owner?limit='+$(this).val()+'&page=1');
      }
    })

  $('#reset').on('click', function(){
      jQuery.ajax({
          url: "{{ url('/parking-owner/reset') }}",
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