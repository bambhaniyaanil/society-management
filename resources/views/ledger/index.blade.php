<?php 
    $permissions = permission(); 
    $permissions = explode(',', $permissions);
?>
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />  
@endpush
<style>
  .form-group {
    margin-bottom: 0px !important;
  }
  .form-check {
    margin-top: 0px !important;
    margin-bottom: 0px !important;
  }
  .form-group label
  {
    margin-bottom: 0px !important;
  }
</style>
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
            <br>
            @if(in_array(10, $permissions))
              <a href="{{url('ledger/create')}}" role="button" class="btn btn-success float-right">Add Ledger</a>
            @endif
            @if(in_array(14, $permissions))
              <a type="button" href="{{route('ledger.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Export
              </a> 
            @endif
            @if(in_array(13, $permissions))
              <a type="button" class="btn btn-outline-info btn-icon-text mr-2 d-none d-md-block float-right" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="btn-icon-prepend" data-feather="upload"></i>
                Import
              </a> 
            @endif  
            <a type="button" href="{{route('ledger.demo-file')}}" class="btn float-right btn-outline-warning btn-icon-text mr-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Demo File
            </a>
            @if(in_array(12, $permissions))
              <a type="button" href="{{route('ledger.remove')}}" class="btn float-right btn-outline-danger btn-icon-text mr-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('remove-all').submit();">
                  <i class="btn-icon-prepend" data-feather="trash"></i>
                  Remove All
                </a>
              <form method="post" id="remove-all" action="{{route('ledger.remove')}}">
                {{ csrf_field() }}
                <input type="hidden" name="ids" id="ids">
              </form>
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
        @foreach($errors->all() as $error)
          <div class="alert alert-danger">
            <p>{{ $error }}</p>
          </div>
        @endforeach               
        <div class="table-responsive" style="padding-top: 10px">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th width="2%">
                  <div class="form-group">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" id="checkAll">
                      </label>
                    </div>
                  </div>
                </th>
                <th>LEDGER NAME</th>
                <th>CONTACT NUMBER</th>
                <th>EMAIL</th>
                <th>REGISTRATION DATE</th>
                <th>OPNING BALANCE DEBIT</th>
                <th>OPNING BALANCE CREDIT</th>
                <th>STATUS</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($ledgers))
                @foreach($ledgers as $k => $ledger)
                    <tr>
                        <?php 
                          $names = explode(',', $ledger->name); 
                          $cname = count($names); 
                          $numbers = explode(',', $ledger->contact_number);
                          $cnumber = count($numbers);
                          $emails = explode(',', $ledger->email_id);
                          $cemail = count($emails);
                        ?>
                        <td width="2%">
                          <div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="check_{{$k}}" onclick="check()" value="{{$ledger->id}}">
                              </label>
                            </div>
                          </div>
                        </td>
                        <td>
                          @foreach($names as $k => $name)
                            <p>
                              @if(!empty($ledger->wing_flat_no) && $k == 0)
                                {{$ledger->wing_flat_no}} - 
                              @endif
                              {{$name}}
                              @if($cname > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>
                          @foreach($numbers as $k => $number)
                            <p>{{$number}}
                              @if($cnumber > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>
                          @foreach($emails as $k => $email)
                            <p>{{$email}}
                              @if($cemail > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>{{date('d-m-Y', strtotime($ledger->registration_date))}}</td>
                        @if($ledger->opning_balance_debit != 0 || $ledger->opning_balance_debit != '')
                          <td>{{$ledger->opning_balance_debit}}</td>
                        @else
                          <td></td>
                        @endif
                        @if($ledger->opning_balance_credit != 0 || $ledger->opning_balance_credit != '')
                          <td>{{$ledger->opning_balance_credit}}</td>
                        @else
                          <td></td>
                        @endif
                        <td>
                          @if($ledger->status == 1)
                            Active
                          @else
                            Deactive
                          @endif
                        </td>
                        <td>       
                          @if(in_array(11, $permissions))                   
                            <a href="{{url('ledger/edit/'.$ledger->id)}}" class="btn btn-primary btn-icon pt-1">
                              <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(12, $permissions))
                            <a href="{{url('ledger/delete/'.$ledger->id)}}" class="btn btn-danger btn-icon pt-1">
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
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('ledger.import')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="file" name="file" class="form-control">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
     checkAll();
    });

    function checkAll()
    {
      var ids = [];
      $('input[type=checkbox]').each(function(){
        if($(this).val() != 'on')
        {
          if (this.checked)
          { 
            ids.push($(this).val());
          }
        }
      })
      ids = JSON.stringify(ids);
      $('#ids').val(ids);
    }

    function check()
    {
      var ids = [];
      $('input[type=checkbox]').each(function(){
        if($(this).val() != 'on')
        {
          if (this.checked) 
          { 
            ids.push($(this).val());
          }
        }
      })
      ids = JSON.stringify(ids);
      $('#ids').val(ids);
    }
</script>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush