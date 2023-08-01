<?php
$permissions = permission();
$permissions = explode(',', $permissions);
?>
@extends('layout.master')
<style>
.slider.slider-horizontal {
    width: 200px !important;
}

p.narration {
    display: none;
    color: #979797;
}
</style>
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/reports/ledger')}}">{{$page_title}}</a></li>
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
                        if (isset($_GET['limit'])) {
                            $limit = $_GET['limit'];
                        }
                        ?>
                        <label style="white-space: nowrap;">Show
                            <select name="dataTableExample_length" id="ledger_limit" aria-controls="dataTableExample"
                                class="custom-select custom-select-sm form-control">
                                <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                                <option value="30" <?php if ($limit == 30) echo 'selected'; ?>>30</option>
                                <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                                <option value="all" <?php if ($limit == 'all') echo 'selected'; ?>>All</option>
                            </select> entries
                        </label>
                    </div>
                </div>
                <form method="post" action="{{route('reports.ledger-report')}}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $form_date = '';
                            if (Session::get('lrfrom_date')) {
                                $form_date = date('d-m-Y', strtotime(Session::get('lrfrom_date')));
                            }
                            $to_date = '';
                            if (Session::get('lrto_date')) {
                                $to_date = date('d-m-Y', strtotime(Session::get('lrto_date')));
                            }
                            ?>
                            <label>From Date
                                <!-- <input type="date" class="form-control" name="from_date" value="{{Session::get('lrfrom_date')}}"> -->
                                <div class="input-group date datepicker" id="from_date">
                                    <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="from_date"
                                        value="{{ $form_date }}" autocomplete="off"><span class="input-group-addon"><i
                                            data-feather="calendar"></i></span>
                                </div>
                            </label>
                            <label>To Date
                                <div class="input-group date datepicker" id="to_date">
                                    <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="to_date"
                                        value="{{ $to_date }}" autocomplete="off"><span class="input-group-addon"><i
                                            data-feather="calendar"></i></span>
                                </div>
                                <!-- <input type="date" class="form-control" name="to_date" value="{{Session::get('lrto_date')}}"> -->
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" style="width: 100%">Ledger
                                    <select class="form-control w-100" id="mySelect2" name="ledger_id" required>
                                        <option value="">Select Ledger</option>
                                        @foreach($ledgers as $ledger)
                                        <option value="{{$ledger->id}}"
                                            <?php if (Session::get('rledger_id') == $ledger->id) echo 'selected'; ?>>
                                            @if(!empty($ledger->wing_flat_no))
                                            {{$ledger->wing_flat_no}} -
                                            @endif
                                            {{$ledger->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" style="width: 100%">VCH Type
                                    <select class="form-control w-100" name="vch_type">
                                        <option value="">Select VCH Type</option>
                                        <option value="payment"
                                            <?php if (Session::get('rlvch_type') == 'payment') echo 'selected'; ?>>
                                            Payment</option>
                                        <option value="receipts"
                                            <?php if (Session::get('rlvch_type') == 'receipts') echo 'selected'; ?>>
                                            Receipts</option>
                                        <option value="journal"
                                            <?php if (Session::get('rlvch_type') == 'journal') echo 'selected'; ?>>
                                            Journal</option>
                                        <option value="invoice"
                                            <?php if (Session::get('rlvch_type') == 'invoice') echo 'selected'; ?>>
                                            Invoice</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-info" value="Search Details">
                            <button type="button" id="reset" class="btn btn-secondary">Reset Search</button>
                            @if(in_array(61, $permissions))
                            <a type="button" href="{{route('reports.ledger-report.export')}}"
                                class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
                                <i class="btn-icon-prepend" data-feather="download"></i>
                                Export
                            </a>
                            <a type="button" href="{{route('reports.ledger-report.export-all')}}"
                                class="btn float-right btn-outline-info btn-icon-text mr-2 d-none d-md-block">
                                <i class="btn-icon-prepend" data-feather="download"></i>
                                Export All Ledger
                            </a>
                            @endif
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
                    <div class="form-check float-right" style="margin-top: -5px;">
                        <label class="form-check-label">
                            <input type="checkbox" id="show_narration" class="form-check-input">
                            Show Narration
                        </label>
                    </div>
                </h6>

                <div class="alert alert-success" style="display:none"></div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%">Date</th>
                                <th width="25%">Particulars</th>
                                <th width="5%">Vch Type</th>
                                @if(in_array(92, $permissions))
                                <th width="15%">Bank Date</th>
                                @endif
                                <th width="10%">Debit</th>
                                <th width="10%">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $debit_amount = 0;
                            $creadit_amount = 0;
                            $ledger_id = Session::get('rledger_id');
                            $tledgerdata = 0;
                            ?>
                            @if(!empty($ledgers_data))
                            <?php $tledgerdata = count($ledgers_data); ?>
                            @foreach($ledgers_data as $key => $ld)
                            <tr>
                                <td>{{date('d-m-Y', strtotime($ld->submit_date))}}</td>
                                @if($ld->by_ledger_id != $ledger_id)
                                <?php
                                $byledgers = explode(',', $ld->byledger->name);
                                $cbyledger = count($byledgers);
                                ?>
                                <td>
                                    @foreach($byledgers as $k => $byledger)
                                    <p><b>
                                            @if(!empty($ld->byledger->wing_flat_no) && $k == 0)
                                            {{$ld->byledger->wing_flat_no}} -
                                            @endif
                                            {{$byledger}}
                                            @if($cbyledger > $k+1)
                                            &
                                            @endif
                                        </b></p>
                                    @endforeach
                                    <p class="narration">{{$ld->narration}}</p>
                                </td>
                                @elseif($ld->to_ledger_id != $ledger_id)
                                <?php
                                $toledgers = explode(',', $ld->toledger->name);
                                $ctoledger = count($toledgers);
                                ?>
                                <td>
                                    @foreach($toledgers as $k1 => $toledger)
                                    <p><b>
                                            @if(!empty($ld->toledger->wing_flat_no) && $k1 == 0)
                                            {{$ld->toledger->wing_flat_no}} -
                                            @endif
                                            {{$toledger}}
                                            @if($ctoledger > $k1+1)
                                            &
                                            @endif
                                        </b></p>
                                    @endforeach
                                    <p class="narration">{{$ld->narration}}</p>
                                </td>
                                @endif
                                <td>{{$ld->voucher_type}}</td>
                                <?php
                                $date = date('m Y', strtotime($ld->submit_date));
                                $crdate = date('m Y');
                                ?>
                                @if(in_array(92, $permissions))
                                <td>
                                    <div class="input-group date datepicker" id="bank_date_{{$key}}">
                                        <input type="text" id="{{$ld->id}}" class="form-control"
                                            placeholder="dd-mm-yyyy" onchange="bankDate(event, {{$ld->id}});"
                                            value="{{$ld->bank_date}}" autocomplete="off"><span
                                            class="input-group-addon"><i data-feather="calendar"></i></span>
                                    </div>
                                    <!-- <input type="date" id="{{$ld->id}}" onchange="bankDate(event, {{$ld->id}});" value="{{$ld->bank_date}}"> -->
                                </td>
                                @endif
                                <!-- <td>{{$ld->bank_date}}</td> -->
                                @if($ld->by_ledger_id != $ledger_id)
                                <td></td>
                                @else
                                <td>
                                    {{number_format($ld->amount)}}
                                    <?php $debit_amount += $ld->amount; ?>
                                </td>
                                @endif

                                @if($ld->to_ledger_id != $ledger_id)
                                <td></td>
                                @else
                                <td>
                                    {{number_format($ld->amount)}}
                                    <?php $creadit_amount += $ld->amount; ?>
                                </td>
                                @endif

                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" align="center">Please Search Ledger Report</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfooter>
                            <tr>
                                @if(in_array(92, $permissions))
                                <th colspan="3"></th>
                                @else
                                <th colspan="2"></th>
                                @endif
                                <th>Total</th>
                                <th>{{number_format($debit_amount)}}</th>
                                <th>{{number_format($creadit_amount)}}</th>
                            </tr>
                            <tr>
                                <?php
                                $closing_balance = $debit_amount - $creadit_amount;
                                ?>
                                @if(in_array(92, $permissions))
                                <th colspan="3"></th>
                                @else
                                <th colspan="2"></th>
                                @endif
                                <th>Closing Balance</th>
                                @if($closing_balance > 0) <th>
                                </th>
                                <th>{{number_format(abs($closing_balance))}}</th>
                                @else
                                <th>{{number_format(abs($closing_balance))}}</th>
                                <th></th>
                                @endif
                            </tr>
                        </tfooter>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="float-right">
                                @if($limit != 'all' && !empty($ledgers_data))
                                {{ $ledgers_data->onEachSide(1)->appends(['limit' => $limit])->links('pagination::bootstrap-4') }}
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
var total = '<?php echo $tledgerdata; ?>';
$(document).ready(function() {
    $('#mySelect2').select2();
    $('#from_date').datepicker({
        format: "dd-mm-yyyy",
        orientation: "left bottom",
    });
    $('#to_date').datepicker({
        format: "dd-mm-yyyy",
        orientation: "left bottom",
    });
    for (var i = 0; i <= total; i++) {
        $('#bank_date_' + i).datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
    }

    $('#reset').on('click', function() {
        jQuery.ajax({
            url: "{{ url('/reports/reset') }}",
            method: 'get',
            success: function(result) {
                window.location = window.location.href;
            }
        });
    });
});

function bankDate(e, id) {
    var date = e.target.value;
    var id = id;
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    jQuery.ajax({
        url: "{{ url('/reports/submit-date') }}",
        method: 'post',
        data: {
            date: date,
            id: id
        },
        success: function(result) {
            jQuery('.alert').show();
            jQuery('.alert').html(result.success);
        }
    });
}

$('#show_narration').on('click', function() {
    if ($(this).is(':checked')) {
        $('p.narration').css('display', 'block');
    } else {
        $('p.narration').css('display', 'none');
    }
})

$('#ledger_limit').on('change', function() {
    if ($(this).val() == 'all') {
        window.location.assign('{{config("app.url")}}/reports/ledger?limit=' + $(this).val());
    } else {
        window.location.assign('{{config("app.url")}}/reports/ledger?limit=' + $(this).val() + '&page=1');
    }
})
</script>
@endsection