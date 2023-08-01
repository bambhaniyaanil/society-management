@extends('layout.master')
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('receipts-voucher')}}">Receipts Voucher</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
  </ol>
</nav>
<div style="font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif; display: flex; flex-wrap: wrap; margin-right: -0.75rem; margin-left: -0.75rem;">
    <div style="display: flex; align-items: stretch; justify-content: stretch; margin-bottom: 1.5rem; position: relative; width: 100%; padding-right: 0.75rem; padding-left: 0.75rem;">
        <div style="width: 100%; min-width: 100%; box-shadow: 0 0 10px 0 rgb(183 192 206 / 20%); position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: 1px solid #f2f4f9; border-radius: 0.25rem;">
            <div style="padding: 1.5rem 1.5rem 6rem 1.5rem; flex: 1 1 auto; min-height: 1px;">
                <table width="100%" border="2">
                    <tr>
                        <td width="75%">
                            <h1 style="font-weight: 700; text-align: center; text-transform: uppercase;">{{$socity->society_name}}</h1>
                            <h4 style="font-weight: 300; font-size: 26px; text-align:center; text-transform: uppercase;">{{$socity->society_name_number.' dt.'.date('m-d-Y', strtotime($socity->society_name_date))}}</h4>
                            <h4 style="font-weight: 300; font-size: 26px; text-align:center; text-transform: uppercase;">{{$socity->address}}</h4>
                        </td>
                        <td width="25%">
                            <h3 style="background-color: #FFA500; color: #fff; text-align: center; margin-top: -2px;">RECEIPTS</h3>
                            <label style="display: block; font-size: 24px;">No: <span>{{$receipt->serial_number}}</span></label>
                            <label style="display: block; font-size: 24px;">Date: <span>{{date('m-d-Y', strtotime($receipt->submit_date))}}</span></label>
                        </td>
                    </tr>
                    <tr>
                        <?php 
                            $str = $receipt->toLedger->name; 
                            $str_len = strlen($receipt->toLedger->name);
                        ?>
                        <?php 
                            $rs = NumberToWordConvert($receipt->amount); 
                            $rs_len = strlen($rs);
                        ?>
                        <td rowspan="2" style="padding: 25px 20px 20px 20px">
                            <div>
                                <p style="font-size: 18px; font-weight: 700;">RECEIVED WITH THANKS FROM SHRI / SMT : </p>
                                <p style="border-bottom: 2px solid black;"><span style="font-size: 16px; font-weight: 500">{{ $str }}</span></p>
                            </div>
                            <!-- <p style="flex: 0 0 100%; max-width: 100%; border-bottom: 2px solid black; padding-top: <?php if($str_len > 41) echo '10px'; else echo '30px'; ?>"><span style="font-size: 16px; font-weight: 500"><?= substr($str,41, 100); ?></span></p> -->
                            <div style="padding-top: 25px; ">
                                <p style="font-size: 18px; font-weight: 700;">THE SUM OF RUPEES </p>
                                <p style="border-bottom: 2px solid black;"><span style="font-size: 16px; font-weight: 500; text-transform: capitalize;"><?= substr($rs,0, 100); ?></span></p>
                            </div>
                            <!-- <p style="flex: 0 0 100%; max-width: 100%; border-bottom: 2px solid black; padding-top: <?php if($rs_len > 101) echo '10px'; else echo '30px'; ?>"><span style="font-size: 16px; font-weight: 500"><?= substr($rs,101, 200); ?></span></p> -->
                            <div style="padding-top: 30px;">
                                <table width="100%" border="2">
                                    <tr>
                                        <th style="font-size: 20px; text-align: center; font-weight: 700" width="20%">BY</th>
                                        <th style="font-size: 20px; text-align: center; font-weight: 700" width="80%">PARTICULARS</th>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 20px; text-align: center; font-weight: 700">CHEQUE / UPI / NEFT / RTGS</td>
                                        <td style="font-size: 16px; text-align: center; font-weight: 700">{{$receipt->narration}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div style="padding-top: 20px; display: flex;">
                                <div style="flex: 0 0 30%; max-width: 30%;">
                                    <table width="100%" border="2">
                                        <tr>
                                            <td style="font-size: 20px; text-align: center; font-weight: 700">UNIT NO.</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 20px; text-align: center; font-weight: 700; height: 50px;">{{$receipt->toLedger->wing_flat_no}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div style="flex: 0 0 70%; max-width: 70%; padding-left: 50px;">
                                    <p style="font-size: 12px; font-weight: 600;">Note: <span >1) Subject To Realizations of Cheque / Transactions.</span></p>
                                    <p style="font-size: 12px; font-weight: 600; padding-left: 30px;">2) Acknowledgment of This Receipt Being Passed by Allotte / Holders.</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding-top: 60px; padding-bottom: 50px;">
                            <div style="display: flex; margin-top: -25px;">
                                <p style="flex: 0 0 15%; max-width: 15%; font-size: 35px; font-weight: 700;">₹</p>
                                <div style="flex: 0 0 85%; max-width: 85%; border: 2px solid black; border-radius: 12px;">
                                    <h4 style="text-align: center; margin-top: 10px;">{{$receipt->amount}}</h4>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4 style="text-align: center;">FOR {{$socity->society_name}}</h4>
                            <div style="padding-top: 30px; text-align: center">
                                <div style="border: 2px dotted black; padding: 70px 0px 70px 0px; margin: 0px 70px 0px 70px"></div>
                            </div>
                            <h5 style="text-align: center; padding-top: 30px;">Authorized Signatory</h5>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush