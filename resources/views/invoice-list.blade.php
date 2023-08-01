<!DOCTYPE html>
<html>
<head>
  <title>DGtalSocietyHelper</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.png') }}">

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

  <!-- end plugin css -->

  @stack('plugin-styles')
      <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <!-- end common css -->

  @stack('style')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    .btn-close {
      box-sizing: content-box;
      width: 1em;
      height: 1em;
      padding: 0.25em 0.25em;
      color: #000;
      background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
      border: 0;
      border-radius: 0.25rem;
      opacity: .5;
    }
  </style>
</head>
<body data-base-url="{{url('/')}}">

  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <div id="app">
    <div style="padding: 100px 30px 0px 30px">
      <div class="page-content">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    @if(!empty($ledger) && !empty($invoices))
                        <div class="card-body">        
                            <h6 class="card-title">  
                                {{$ledger->wing_flat_no}} - {{str_replace(',', ' & ', $ledger->name)}}
                            </h6>     
                                <div class="alert alert-success" style="display:none" id="msg">
                                </div>           
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
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
                                @if(!empty($invoices) && count($invoices) > 0)
                                    @foreach($invoices as $invoice)
                                    <?php 
                                        $byledgers = explode(',', $invoice->byLedger->name);
                                        $cbyledger = count($byledgers);
                                    ?>
                                    <tr>
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
                                        <a href="{{url('search-invoice/download/'.$invoice->id)}}" class="btn btn-info btn-icon pt-1">
                                            <i data-feather="download"></i>
                                        </a>     
                                        <a href="{{url('search-invoice/view/'.$invoice->id)}}" class="btn btn-success btn-icon pt-1">
                                            <i data-feather="eye"></i>
                                        </a> 
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
                            </div>
                        </div>
                    @else
                        <div class="card-body">        
                            <h6 class="card-title">  
                                @if(Session::get('invoice_ledger_name') && Session::get('invoice_wing_flat_no'))
                                    {{Session::get('invoice_wing_flat_no')}} - {{Session::get('invoice_ledger_name')}}
                                @endif
                                <a type="button" href="{{ route('search-invoice-create-zip', [0, 0]) }}" class="btn float-right btn-outline-secondary btn-icon-text mr-2 d-none d-md-block">
                                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                                All Invoice Download
                                </a>
                            </h6>     
                                <div class="alert alert-success" style="display:none" id="msg">
                                </div>           
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
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
                                    <tr>
                                        <td colspan="10" align="center">Record not found</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
    <script>
        function sendMail(id)
        {
            var url = '{{config("app.url")}}'+'/search-invoice/send/'+id;
            $.ajax({
               type:'GET',
               url:url,
               success:function(data) {
                  if(data.status == 'true' || data.status == true)
                  {
                    $('#msg').html('<p>'+data.msg+'</p>');
                    $('#msg').css('display', 'block');
                    
                  }
               }
            });
        }
    </script>
    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
</body>
</html>