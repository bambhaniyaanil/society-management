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
            @if(in_array(16, $permissions))
              <a href="{{url('payment-voucher/create')}}" role="button" class="btn btn-success float-right">Add Payment Voucher</a>  
            @endif
            @if(in_array(20, $permissions))
              <a type="button" href="{{route('payment-voucher.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Export
              </a>
            @endif
            @if(in_array(19, $permissions))
              <a type="button" class="btn btn-outline-info btn-icon-text mr-2 d-none d-md-block float-right" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="btn-icon-prepend" data-feather="upload"></i>
                Import
              </a>
            @endif
            <a type="button" href="{{route('payment-voucher.demo-file')}}" class="btn float-right btn-outline-warning btn-icon-text mr-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Demo File
            </a>
            @if(in_array(18, $permissions))
              <a type="button" href="{{route('payment-voucher.remove')}}" class="btn float-right btn-outline-danger btn-icon-text mr-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('remove-all').submit();">
                  <i class="btn-icon-prepend" data-feather="trash"></i>
                  Remove All
                </a>
              <form method="post" id="remove-all" action="{{route('payment-voucher.remove')}}">
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
                <select name="dataTableExample_length" id="payment_limit" aria-controls="dataTableExample" class="custom-select custom-select-sm form-control">
                  <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                  <option value="30" <?php if($limit == 30) echo 'selected'; ?>>30</option>
                  <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                  <option value="all" <?php if($limit == 'all') echo 'selected'; ?>>All</option>
                </select> entries
              </label>
            </div>
          </div>
          <form method="post" action="{{route('payment-voucher.search')}}">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <?php 
                    $form_date = '';
                    if(Session::get('from_date'))
                    {
                        $form_date = date('d-m-Y', strtotime(Session::get('from_date')));
                    }
                    $to_date = '';
                    if(Session::get('to_date'))
                    {
                        $to_date = date('d-m-Y', strtotime(Session::get('to_date')));
                    }
                ?>
                <div class="dataTables_length" id="dataTableExample_length">
                  <label>From Date
                    <div class="input-group date datepicker" id="from_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="from_date" value="{{ $form_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                    <!-- <input type="date" class="form-control" name="from_date" value="{{ Session::get('from_date') }}"> -->
                  </label>
                  <label>To Date
                    <div class="input-group date datepicker" id="to_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="to_date" value="{{ $to_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                    <!-- <input type="date" class="form-control" name="to_date" value="{{ Session::get('to_date') }}"> -->
                  </label>
                </div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div id="dataTableExample_filter" class="dataTables_filter float-right">
                  <label><br>
                    <input type="search" class="form-control" placeholder="Search" name="search" value="{{ Session::get('pvsearch') }}" aria-controls="dataTableExample">
                  </label>
                  <input type="submit" class="btn btn-info" value="Go">
                  <!-- <a href="javascrip:;" class="btn-info btn-sm" role="submit">Go</a> -->
                </div>
              </div>
              
            </div>
          </form>
          <table class="table">
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
                <th>BY LEDGER (DEBIT)</th>
                <th>TO LEDGER (CREDIT)</th>
                <th>AMOUNT</th>
                <th>REGISTRATION DATE</th>
                <th>STATUS</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
            <?php $total = 0; ?>
              @if(!empty($payment_voucher))
                @foreach($payment_voucher as $pv)
                    <?php 
                      $total += $pv->amount; 
                      $fromledgers = explode(',', $pv->fromLedger->name);
                      $cfromledger = count($fromledgers);
                      $toledgers = explode(',', $pv->toLedger->name);
                      $ctoledger = count($toledgers);
                    ?>
                    <tr>
                        <td width="2%">
                          <div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" onclick="check()" value="{{$pv->id}}">
                              </label>
                            </div>
                          </div>
                        </td>
                        <td>
                          @foreach($fromledgers as $k => $fromledger)
                            <p>
                              @if(!empty($pv->fromLedger->wing_flat_no) && $k == 0)
                                {{$pv->fromLedger->wing_flat_no}} -
                              @endif
                              {{$fromledger}}
                              @if($cfromledger > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>
                          @foreach($toledgers as $k => $toledger)
                            <p>
                              @if(!empty($pv->toLedger->wing_flat_no) && $k == 0)
                                {{$pv->toLedger->wing_flat_no}} -
                              @endif
                              {{$toledger}}
                              @if($ctoledger > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>{{$pv->amount}}</td>
                        <td>{{date('d-m-Y', strtotime($pv->submit_date))}}</td>
                        <td>
                          @if($pv->status == 1)
                            Active
                          @else
                            Deactive
                          @endif
                        </td>
                        <td> 
                          @if(in_array(78, $permissions))   
                            <a href="{{url('payment-voucher/download/'.$pv->id)}}" class="btn btn-info btn-icon pt-1">
                              <i data-feather="download"></i>
                            </a>   
                          @endif
                          @if(in_array(77, $permissions))   
                            <a href="{{url('payment-voucher/view/'.$pv->id)}}" class="btn btn-success btn-icon pt-1">
                              <i data-feather="eye"></i>
                            </a>   
                          @endif     
                          @if(in_array(17, $permissions))                
                            <a href="{{url('payment-voucher/edit/'.$pv->id)}}" class="btn btn-primary btn-icon pt-1">
                              <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(18, $permissions))
                            <a href="{{url('payment-voucher/delete/'.$pv->id)}}" class="btn btn-danger btn-icon pt-1">
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
            <tfooter>
              <tr>
                <th></th>
                <th>Total</th>
                <th>{{$total}}</th>
                <th colspan="2"></th>
              </tr>
            </tfooter>
          </table>
          <div class="row">
              <div class="col-sm-12">
                <div class="float-right">
                  @if($limit != 'all')
                    {{ $payment_voucher->onEachSide(1)->appends(['limit' => $limit])->links('pagination::bootstrap-4') }}
                  @endif
                </div>
              </div>
          </div>
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
                <form action="{{route('payment-voucher.import')}}" method="POST" enctype="multipart/form-data">
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
    $(document).ready(function() {
        $('#from_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
        $('#to_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
    });

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

    $('#payment_limit').on('change', function(){
      if($(this).val() == 'all')
      {
        window.location.assign('{{config("app.url")}}/payment-voucher?limit='+$(this).val());
      }
      else{
        window.location.assign('{{config("app.url")}}/payment-voucher?limit='+$(this).val()+'&page=1');
      }
    })
</script>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
