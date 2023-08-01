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
  .hiddenRow {
    padding: 0 !important;
  }
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
    <li class="breadcrumb-item"><a href="{{url('invoice')}}">{{$page_title}}</a></li>    
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title">{{$sub_title}}
            <br>
            @if(in_array(50, $permissions))
              <a href="{{url('invoice/create')}}" role="button" class="btn btn-success float-right">Add Invoice</a>   
            @endif
            @if(in_array(57, $permissions))
              <a type="button" href="{{ route('create-zip') }}" class="btn float-right btn-outline-secondary btn-icon-text mr-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('download-zip').submit();">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                All Invoice Download
              </a> 
            @endif
            @if(in_array(54, $permissions))
              <a type="button" href="{{route('invoice.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Export
              </a> 
            @endif
            @if(in_array(53, $permissions))
              <a type="button" class="btn btn-outline-info btn-icon-text mr-2 d-none d-md-block float-right" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="btn-icon-prepend" data-feather="upload"></i>
                Import
              </a>  
            @endif
            <a type="button" href="{{route('invoice.demo-file')}}" class="btn float-right btn-outline-warning btn-icon-text mr-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Demo File
              </a> 

            <a type="button" href="{{route('invoice.bulk-send-mail')}}" class="btn float-right btn-outline-success btn-icon-text mr-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('send-all').submit();">
                <i class="btn-icon-prepend" data-feather="send"></i>
                Bulk Send Mail
            </a> 
            @if(in_array(52, $permissions))
              <a type="button" href="{{route('invoice.remove')}}" class="btn float-right btn-outline-danger btn-icon-text mr-2 d-none d-md-block" onclick="event.preventDefault();document.getElementById('remove-all').submit();">
                  <i class="btn-icon-prepend" data-feather="trash"></i>
                  Remove All
                </a>
            @endif  
            <form method="post" id="send-all" action="{{route('invoice.bulk-send-mail')}}">
              {{ csrf_field() }}
              <input type="hidden" name="send_ids" id="send_ids">
            </form>
            <form method="post" id="remove-all" action="{{route('invoice.remove')}}">
              {{ csrf_field() }}
              <input type="hidden" name="ids" id="ids"> 
            </form>
            <form method="post" id="download-zip" action="{{route('create-zip')}}">
              {{ csrf_field() }}
              <input type="hidden" name="zip_ids" id="zip_ids">
            </form>
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
                <select name="dataTableExample_length" id="invoice_limit" aria-controls="dataTableExample" class="custom-select custom-select-sm form-control">
                  <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                  <option value="30" <?php if($limit == 30) echo 'selected'; ?>>30</option>
                  <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                  <option value="all" <?php if($limit == 'all') echo 'selected'; ?>>All</option>
                </select> entries
              </label>
            </div>
          </div>
          <form method="post" action="{{route('invoice.search')}}">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <?php 
                    $form_date = '';
                    if(Session::get('ifrom_date'))
                    {
                        $form_date = date('d-m-Y', strtotime(Session::get('ifrom_date')));
                    }
                    $to_date = '';
                    if(Session::get('ito_date'))
                    {
                        $to_date = date('d-m-Y', strtotime(Session::get('ito_date')));
                    }
                ?>
                <div class="dataTables_length" id="dataTableExample_length">
                  <label>From Bill Date
                    <div class="input-group date datepicker" id="ifrom_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="ifrom_date" value="{{ $form_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                    <!-- <input type="date" class="form-control" name="from_date" value="{{ Session::get('from_date') }}"> -->
                  </label>
                  <label>To Bill Date
                    <div class="input-group date datepicker" id="ito_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="ito_date" value="{{ $to_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                    <!-- <input type="date" class="form-control" name="to_date" value="{{ Session::get('to_date') }}"> -->
                  </label>
                </div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div id="dataTableExample_filter" class="dataTables_filter float-right">
                  <label><br>
                    <input type="search" class="form-control" placeholder="Search" name="isearch" value="{{ Session::get('isearch') }}" aria-controls="dataTableExample">
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
                <th>To Ledger(Credit)</th>
                <th>By Ledger(Debit)</th>
                <th>By Ledger Amount</th>
                <th>Bill NO</th>
                <th>Bill Period</th>
                <th>Bill Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($invoices))
                @foreach($invoices as $invoice)
                  <?php 
                    $byledgers = explode(',', $invoice->byLedger->name);
                    $cbyledger = count($byledgers);
                  ?>
                  <tr>
                    <td width="2%">
                      <div class="form-group">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" onclick="check()" value="{{$invoice->id}}">
                          </label>
                        </div>
                      </div>
                    </td>
                    <td>
                      <button class="btn btn-info btn-icon pt-1 accordion-toggle" data-toggle="collapse" data-target="#target_{{$invoice->id}}">
                        <i data-feather="plus"></i>
                      </button>
                    </td>
                    <td>
                      @foreach($byledgers as $k => $byledger)
                        <p>
                          @if(!empty($invoice->byLedger->wing_flat_no) && $k == 0)
                            {{$invoice->byLedger->wing_flat_no}} -
                          @endif
                          {{$byledger}}
                          @if($cbyledger > $k+1)
                            &
                          @endif
                        </p>
                      @endforeach
                    </td>
                    <td>{{$invoice->by_ledger_amount}}</td>
                    <td>{{$invoice->bill_no}}</td>
                    <td>{{$invoice->bill_period}}</td>
                    <td>{{date('d-m-Y', strtotime($invoice->bill_date))}}</td>
                    <td>{{date('d-m-Y', strtotime($invoice->due_date))}}</td>
                    @if($invoice->status == 1)
                      <td>Active</td>
                    @else
                      <td>Deactive</td>
                    @endif
                    <td>
                      @if(in_array(56, $permissions))
                        <a onclick="whatsAapp('{{$invoice->id}}')" class="btn btn-secondary btn-icon pt-1">
                          <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                        </a> 
                      @endif
                      @if(in_array(57, $permissions)) 
                        <a href="{{url('invoice/download/'.$invoice->id)}}" class="btn btn-info btn-icon pt-1">
                          <i data-feather="download"></i>
                        </a>  
                      @endif
                      @if(in_array(55, $permissions))   
                        <a href="{{url('invoice/send/'.$invoice->id)}}" class="btn btn-warning btn-icon pt-1">
                          <i data-feather="send"></i>
                        </a>
                      @endif
                      @if(in_array(58, $permissions))     
                        <a href="{{url('invoice/view/'.$invoice->id)}}" class="btn btn-success btn-icon pt-1">
                          <i data-feather="eye"></i>
                        </a> 
                      @endif
                      @if(in_array(51, $permissions))                     
                        <a href="{{url('invoice/edit/'.$invoice->id)}}" class="btn btn-primary btn-icon pt-1">
                          <i data-feather="edit"></i>
                        </a>
                      @endif
                      @if(in_array(52, $permissions))
                        <a href="{{url('invoice/delete/'.$invoice->id)}}" class="btn btn-danger btn-icon pt-1">
                          <i data-feather="trash"></i>
                        </a>
                      @endif
                    </td>
                  </tr>
                  <?php 
                    $toledgers = json_decode($invoice->to_ledger, true);
                  ?>
                  <tr>
                    <td colspan="12" class="hiddenRow">
                      <div class="accordian-body collapse" id="target_{{$invoice->id}}">
                        <table class="table">
                          <thead>
                            <tr class="info">
                              <th>To Ledger(Credit)</th>
                              <th>Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(!empty($toledgers))
                              @foreach($toledgers as $toledger)
                                <?php 
                                  $ledger = App\Ledger::where('id', $toledger['to_ledger_id'])->first()
                                ?>
                                <tr>
                                  <?php 
                                    $etoledgers = explode(',', $ledger->name);
                                    $cetoledger = count($etoledgers);
                                  ?>
                                  <td>
                                    @foreach($etoledgers as $k1 => $etoledger)
                                      <p>
                                        @if(!empty($ledger->wing_flat_no) && $k1 == 0)
                                          {{$ledger->wing_flat_no}} -
                                        @endif
                                        {{$etoledger}}
                                        @if($cetoledger > $k1+1)
                                          &
                                        @endif
                                      </p>
                                    @endforeach
                                  </td>
                                  <td>{{$toledger['amount']}}</td>
                                </tr>
                              @endforeach
                            @else
                              <tr class="info">
                                <td colspan="5">No Record Found!</td>
                              </tr>
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="10" align="center">Record not found</td>
                </tr>
              @endif
            </tbody>
          </table>
          <div class="row">
              <div class="col-sm-12">
                <div class="float-right">
                  @if($limit != 'all')
                    {{ $invoices->onEachSide(1)->appends(['limit' => $limit])->links('pagination::bootstrap-4') }}
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
                <form action="{{route('invoice.import')}}" method="POST" enctype="multipart/form-data">
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
        $('#ifrom_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
        $('#ito_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
    });

    function whatsAapp(id)
    {
      var url = '{{config("app.url")}}'+'/invoice/whatsapp/'+id;
      $.ajax({
               type:'GET',
               url:url,
               success:function(data) {
                  if(data.status == 'true' || data.status == true)
                  {
                    var msg = 'Dear, \r\n\r\n '+data.name+' \r\n\r\n Your Unit No. '+data.wing_flat_no+' \r\n\r\n Maintenance Due: Rs.'+data.total_due_amount+' \r\n\r\n Bill Date : '+data.bill_date+' \r\n\r\n Due Date : '+data.due_date+' \r\n\r\n If the amount is not paid within the due date, interest will be charged. \r\n\r\n https://jtechnoholic.com/MySocietyAssistant/search-invoice \r\n\r\n Thanks and Regards \r\n\r\n '+data.society_name;
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

    $('#invoice_limit').on('change', function(){
      if($(this).val() == 'all')
      {
        window.location.assign('{{config("app.url")}}/invoice?limit='+$(this).val());
      }
      else{
        window.location.assign('{{config("app.url")}}/invoice?limit='+$(this).val()+'&page=1');
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