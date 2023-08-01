<?php 
    $permissions = permission(); 
    $permissions = explode(',', $permissions);
?>
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />  
  <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
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
            @if(in_array(22, $permissions))
              <a href="{{url('receipts-voucher/create')}}" role="button" class="btn btn-success mt-2 float-right">Add Receipts Voucher</a> 
            @endif
            @if(in_array(30, $permissions))
              <!-- <a type="button" href="{{ route('receipts-voucher.create-zip',['download'=>'zip']) }}" class="btn float-right btn-outline-secondary btn-icon-text mr-2 mt-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                All Receipts Download
              </a>   -->
              <a type="button" href="{{ route('receipts-voucher.create-zip') }}" class="btn float-right btn-outline-secondary btn-icon-text mr-2 mt-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('download-zip').submit();">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                All Receipts Download
              </a>
            @endif 
            @if(in_array(26, $permissions))
              <a type="button" href="{{route('receipts-voucher.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 mt-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Export
              </a> 
            @endif
            @if(in_array(25, $permissions))
              <a type="button" class="btn btn-outline-info btn-icon-text mr-2 mt-2 d-none d-md-block float-right" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="btn-icon-prepend" data-feather="upload"></i>
                Import
              </a>
            @endif
            <a type="button" href="{{route('receipts-voucher.demo-file')}}" class="btn float-right btn-outline-warning btn-icon-text mr-2 mt-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Demo File
            </a> 
            <a type="button" href="{{route('receipts-voucher.bulk-send-mail')}}" class="btn float-right btn-outline-success btn-icon-text mr-2 mt-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('send-all').submit();">
                <i class="btn-icon-prepend" data-feather="send"></i>
                Bulk Send Mail
            </a> 
            @if(in_array(24, $permissions))
              <a type="button" href="{{route('receipts-voucher.remove')}}" class="btn float-right btn-outline-danger btn-icon-text mr-2 mt-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('remove-all').submit();">
                  <i class="btn-icon-prepend" data-feather="trash"></i>
                  Remove All
                </a>
            @endif
            <form method="post" id="send-all" action="{{route('receipts-voucher.bulk-send-mail')}}">
              {{ csrf_field() }}
              <input type="hidden" name="send_ids" id="send_ids">
            </form>
            <form method="post" id="remove-all" action="{{route('receipts-voucher.remove')}}">
              {{ csrf_field() }}
              <input type="hidden" name="ids" id="ids">
            </form>
            <form method="post" id="download-zip" action="{{route('receipts-voucher.create-zip')}}">
              {{ csrf_field() }}
              <input type="hidden" name="zip_ids" id="zip_ids">
            </form>
        </h6>          
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif 
        @foreach($errors->all() as $error)
          <div class="alert alert-danger">
            <p>{{ $error }}</p>
          </div>
        @endforeach   
        @if ($message = Session::get('error'))
              <div class="alert alert-danger">
                <p>{{ $message }}</p>
              </div>
            @endif 
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
                <select name="dataTableExample_length" id="receipts_limit" aria-controls="dataTableExample" class="custom-select custom-select-sm form-control">
                  <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                  <option value="30" <?php if($limit == 30) echo 'selected'; ?>>30</option>
                  <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                  <option value="all" <?php if($limit == 'all') echo 'selected'; ?>>All</option>
                </select> entries
              </label>
            </div>
          </div>
          <form method="post" action="{{route('receipts-voucher.search')}}">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="dataTableExample_length">
                  <label>From Date
                    <!-- <input type="date" class="form-control" name="from_date" value="{{ Session::get('rvfrom_date') }}"> -->
                    <?php 
                        $form_date = '';
                        if(Session::get('rvfrom_date'))
                        {
                            $form_date = date('d-m-Y', strtotime(Session::get('rvfrom_date')));
                        }
                        $to_date = '';
                        if(Session::get('rvto_date'))
                        {
                            $to_date = date('d-m-Y', strtotime(Session::get('rvto_date')));
                        }
                    ?>
                    <div class="input-group date datepicker" id="from_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="from_date" value="{{ $form_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                  </label>
                  <label>To Date
                    <div class="input-group date datepicker" id="to_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="to_date" value="{{ $to_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                    <!-- <input type="date" class="form-control" name="to_date" value="{{ Session::get('rvto_date') }}"> -->
                  </label>
                </div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div id="dataTableExample_filter" class="dataTables_filter float-right">
                  <label><br>
                    <input type="search" class="form-control" placeholder="Search" name="search" value="{{ Session::get('rvsearch') }}" aria-controls="dataTableExample">
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
              @if(!empty($receipts_voucher))
                @foreach($receipts_voucher as $rv)
                    <?php 
                      $total += $rv->amount; 
                      $byledgers = explode(',', $rv->buyLedger->name);
                      $cbyledger = count($byledgers);
                      $toledgers = explode(',', $rv->toLedger->name);
                      $ctoledger = count($toledgers);
                    ?>
                    <tr>
                        <td width="2%">
                          <div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" onclick="check()" value="{{$rv->id}}">
                              </label>
                            </div>
                          </div>
                        </td>
                        <td>
                          @foreach($byledgers as $k => $byledger)
                            <p>
                              @if(!empty($rv->buyLedger->wing_flat_no) && $k == 0)
                                {{$rv->buyLedger->wing_flat_no}} -
                              @endif
                              {{$byledger}}
                              @if($cbyledger > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>
                          @foreach($toledgers as $k => $toledger)
                            <p>
                              @if(!empty($rv->toLedger->wing_flat_no) && $k == 0)
                                {{$rv->toLedger->wing_flat_no}} -
                              @endif
                              {{$toledger}}
                              @if($ctoledger > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>{{$rv->amount}}</td>
                        <td>{{date('d-m-Y', strtotime($rv->submit_date))}}</td>
                        <td>
                          @if($rv->status == 1)
                            Active
                          @else
                            Deactive
                          @endif
                        </td>
                        <td>  
                          @if(in_array(28, $permissions))
                            <a onclick="whatsAapp('{{$rv->id}}')" class="btn btn-secondary btn-icon pt-1">
                              <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                            </a>  
                          @endif
                          @if(in_array(30, $permissions))
                            <a href="{{url('receipts-voucher/download/'.$rv->id)}}" class="btn btn-info btn-icon pt-1">
                              <i data-feather="download"></i>
                            </a> 
                          @endif
                          @if(in_array(27, $permissions))    
                            <a href="{{url('receipts-voucher/send/'.$rv->id)}}" class="btn btn-warning btn-icon pt-1">
                              <i data-feather="send"></i>
                            </a> 
                          @endif
                          @if(in_array(29, $permissions))
                            <a href="{{url('receipts-voucher/view/'.$rv->id)}}" class="btn btn-success btn-icon pt-1">
                              <i data-feather="eye"></i>
                            </a>
                          @endif   
                          @if(in_array(23, $permissions))                     
                            <a href="{{url('receipts-voucher/edit/'.$rv->id)}}" class="btn btn-primary btn-icon pt-1">
                              <i data-feather="edit"></i>
                            </a>
                          @endif
                          @if(in_array(24, $permissions))
                            <a href="{{url('receipts-voucher/delete/'.$rv->id)}}" class="btn btn-danger btn-icon pt-1">
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
                    {{ $receipts_voucher->onEachSide(1)->appends(['limit' => $limit])->links('pagination::bootstrap-4') }}
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
                <form action="{{route('receipts-voucher.import')}}" method="POST" enctype="multipart/form-data">
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

    function whatsAapp(id)
    {
      var url = '{{config("app.url")}}'+'/receipts-voucher/whatsapp/'+id;
      $.ajax({
          type:'GET',
          url:url,
          success:function(data) {
            if(data.status == 'true' || data.status == true)
            {
              var msg = "Received From"+"\r\n"+data.name+"\r\n\r\n"+"Amount Rs."+data.amount+"\r\n\r\n"+"Against Your Unit No."+data.wing_flat_no+"\r\n\r\n"+"Receipt Date : "+data.date+"\r\n\r\n"+"Subject To Realizations of Cheque / Transactions."+"\r\n\r\n"+"https://jtechnoholic.com/MySocietyAssistant/search-receipts"+"\r\n\r\n"+"Thanks and Regards"+"\r\n"+data.society_name;
              var msg = window.encodeURIComponent(msg);
              var page = 'https://web.whatsapp.com/send?phone='+data.whatsapp_number+'&text='+msg;
              var myWindow = window.open(page, "_blank");

                myWindow.focus();
            }
          }
      });
    }

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
      $('#send_ids').val(ids);
      $('#zip_ids').val(ids);
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
      $('#send_ids').val(ids);
      $('#zip_ids').val(ids);
    }

    $('#receipts_limit').on('change', function(){
      if($(this).val() == 'all')
      {
        window.location.assign('{{config("app.url")}}/receipts-voucher?limit='+$(this).val());
      }
      else{
        window.location.assign('{{config("app.url")}}/receipts-voucher?limit='+$(this).val()+'&page=1');
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