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
        <div style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; display: flex; flex-wrap: wrap; margin-right: -0.75rem; margin-left: -0.75rem;">
            <div style="display: flex; align-items: stretch; justify-content: stretch; margin-bottom: 1.5rem; position: relative; width: 100%; padding-right: 0.75rem; padding-left: 0.75rem;">
                <div style="width: 100%; min-width: 100%; box-shadow: 0 0 10px 0 rgb(183 192 206 / 20%); position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: 1px solid #f2f4f9; border-radius: 0.25rem;">
                    <div style="padding: 1.5rem 1.5rem 6rem 1.5rem; flex: 1 1 auto; min-height: 1px;">
                        <h1 style="font-weight: 700; text-align: center; text-transform: uppercase;">{{$socity->society_name}}</h1>
                        <div style="padding-top: 5px;">
                            <h4 style="font-weight: 300; font-size: 26px; text-align:center; text-transform: uppercase;">{{$socity->society_name_number.' dt.'.date('m-d-Y', strtotime($socity->society_name_date))}}</h4>
                            <p style="border-top: 3px solid black;"></p>
                            <h4 style="font-weight: 300; font-size: 26px; text-align:center; text-transform: uppercase;">{{$socity->address}}</h4>
                            <p style="border-top: 3px solid black;"></p>
                        </div>
                        <div style="padding-top: 70px; padding-bottom: 40px;">
                            <h2 style="text-align: center; font-weight: 500;">MAINTENANCE INVOICE</h2>
                            <div style="border: 3px solid black; margin-top: 20px; padding: 15px 0px 15px 10px;">
                                <div style="display: flex; flex-wrap: wrap; margin-right: 0.75rem; margin-left: 0.75rem;">
                                    <div style="flex: 0 0 33.3333333333%; max-width: 33.3333333333%;">
                                        <div style="display: flex; flex-wrap: wrap;">
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <label style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:700; font-size: 24px;">Unit No:</label>
                                            </div>
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <p style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:500; font-size: 22px;">{{$invoice->byLedger->wing_flat_no}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex: 0 0 33.3333333333%; max-width: 33.3333333333%;">
                                        <div style="display: flex; flex-wrap: wrap;">
                                            <div style="flex: 0 0 25%; max-width: 25%;">
                                                <label style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:700; font-size: 24px;">Area:</label>
                                            </div>
                                            <div style="flex: 0 0 75%; max-width: 75%;">
                                                <p style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:500; font-size: 22px;">{{(!empty($invoice->byLedger->area_sq_mtr) ? $invoice->byLedger->area_sq_mtr.' Sq.Mtr' : $invoice->byLedger->area_sq_ft.' Sq.Ft' )}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex: 0 0 33.3333333333%; max-width: 33.3333333333%;">
                                        <div style="display: flex; flex-wrap: wrap;">
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <label style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:700; font-size: 24px;">Bill No.:</label>
                                            </div>
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <p style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:500; font-size: 22px;">{{$invoice->bill_no}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex: 0 0 66.6666666667%; max-width: 66.6666666667%;">
                                        <div style="display: flex; flex-wrap: wrap;">
                                            <div style="flex: 0 0 25%; max-width: 25%;">
                                                <label style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:700; font-size: 24px;">Name:</label>
                                            </div>
                                            <div style="flex: 0 0 75%; max-width: 75%;">
                                                <p style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:500; font-size: 22px;">{{ str_replace(',', ' & ', $invoice->byLedger->name)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex: 0 0 33.3333333333%; max-width: 33.3333333333%;">
                                        <div style="display: flex; flex-wrap: wrap;">
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <label style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:700; font-size: 24px;">Bill Date:</label>
                                            </div>
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <p style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:500; font-size: 22px;">{{date('d-m-Y', strtotime($invoice->bill_date))}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex: 0 0 66.6666666667%; max-width: 66.6666666667%;">
                                        <div style="display: flex; flex-wrap: wrap;">
                                            <div style="flex: 0 0 25%; max-width: 25%;">
                                                <label style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:700; font-size: 24px;">Bill Period:</label>
                                            </div>
                                            <div style="flex: 0 0 75%; max-width: 75%;">
                                                <p style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:500; font-size: 22px;">{{$invoice->bill_period}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex: 0 0 33.3333333333%; max-width: 33.3333333333%;">
                                        <div style="display: flex; flex-wrap: wrap;">
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <label style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:700; font-size: 24px;">Due Date:</label>
                                            </div>
                                            <div style="flex: 0 0 50%; max-width: 50%;">
                                                <p style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; font-weight:500; font-size: 22px;">{{date('d-m-Y', strtotime($invoice->due_date))}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table width="100%" border="2">
                            <thead style="font-size: 24px;">
                                <tr>
                                    <th width="10%" style="text-align: center">Sr No.</th>
                                    <th width="70%">&nbsp;PARTICULARS OF CHARGES</th>
                                    <th width="20%" style="text-align: center">Amount</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 20px;">
                                <?php 
                                    $toledgers = json_decode($invoice->to_ledger, true);
                                    $total_amount = 0;
                                    $intrest_amount = 0;
                                ?>
                                @if(!empty($toledgers))
                                    @foreach($toledgers as $k => $toledger)
                                        <?php 
                                            $k += 1;
                                            $ledger = App\Ledger::where('id', $toledger['to_ledger_id'])->first();
                                            $total_amount += $toledger['amount'];
                                        ?>
                                        @if($ledger->name != 'Interest Amount')
                                            <tr>
                                                <td style="text-align: center">{{$k}}</td>
                                                <td>&nbsp;&nbsp;{{$ledger->name}}</td>
                                                <td>&nbsp;&nbsp;&nbsp;Rs.{{number_format($toledger['amount'], 2)}}</td>
                                            </tr>
                                        @else
                                            <?php $intrest_amount +=  $toledger['amount']; ?>
                                        @endif
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="5">No Record Found!</td>
                                </tr>
                                @endif
                                <tr>
                                    <table width="100%" border="2">
                                        <tbody style="font-size: 20px;">
                                            <tr>
                                                <td width="50%" rowspan="4"></td>
                                                <td width="30%">&nbsp;Total</td>
                                                <td width="20%">&nbsp;&nbsp;&nbsp;Rs.{{number_format($total_amount, 2)}}</td>
                                            </tr>
                                            <tr>
                                                <td width="30%">&nbsp;Arrears</td>
                                                <td width="20%">&nbsp;&nbsp;&nbsp;Rs.{{number_format($invoice->arrears, 2)}}</td>
                                            </tr>
                                            <tr>
                                                <td width="30%">&nbsp;Interest Amount</td>
                                                <td width="20%">&nbsp;&nbsp;&nbsp;Rs.{{number_format($intrest_amount, 2)}}</td>
                                            </tr>
                                            <tr>
                                                <td width="30%">&nbsp;Total Due Amount Payable</td>
                                                <?php $total_due_amount = $total_amount + $invoice->arrears + $intrest_amount; ?>
                                                <td width="20%">&nbsp;&nbsp;&nbsp;Rs.{{number_format($total_due_amount, 2)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="padding:15px 20px 20px 20px;">
                                                {!! $socity->notice !!}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </tr>
                            </tbody>
                        </table>
                        <div style="text-align: right; padding-top: 10px;">
                            <h4>FOR {{$socity->society_name}}</h4>
                            <p style="padding-top: 50px; font-size: 16px;">Authorized Signatory</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
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