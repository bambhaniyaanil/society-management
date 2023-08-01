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
        <li class="breadcrumb-item"><a href="{{url('/reports/profit-loss')}}">{{$page_title}}</a></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Search
                    @if(in_array(67, $permissions))
                    <a type="button" href="{{route('reports.closing-balance.export')}}"
                        class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
                        <i class="btn-icon-prepend" data-feather="download"></i>
                        Export
                    </a>
                    @endif
                </h6>
                @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <form method="post" action="{{route('reports.closing-balance-report')}}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <?php 
                    $form_date = '';
                    if(Session::get('cbrfrom_date'))
                    {
                        $form_date = date('d-m-Y', strtotime(Session::get('cbrfrom_date')));
                    }
                    $to_date = '';
                    if(Session::get('cbrto_date'))
                    {
                        $to_date = date('d-m-Y', strtotime(Session::get('cbrto_date')));
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
                            <br>
                            <input type="submit" class="btn btn-info" value="Go">
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
                <h6 class="card-title">Closing Balance Reports</h6>

                <div class="alert alert-success" style="display:none"></div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="25%">Particulars</th>
                                <th width="10%">Debit</th>
                                <th width="10%">Credit</th>
                                <th width="10%">Closing Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($cloasingdatas))
                            @foreach($cloasingdatas as $cld)
                            <?php 
                              $lnames = explode(',', $cld['ledger']);
                              $clname = count($lnames);
                            ?>
                            <tr>
                                <td>

                                    @foreach($lnames as $k1 => $lname)
                                    <p><b>
                                            @if(!empty($cld['wing_flat_no']) && $k1 == 0)
                                            {{$cld['wing_flat_no']}} -
                                            @endif
                                            {{$lname}}
                                            @if($clname > $k1+1)
                                            &
                                            @endif
                                        </b></p>
                                    @endforeach
                                </td>
                                <td>{{ number_format($cld['debit_amount'], 2) }}</td>
                                <td>{{ number_format($cld['credit_amount'], 2) }}</td>
                                <td>{{ number_format(abs($cld['credit_amount'] - $cld['debit_amount']), 2) }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="float-right">
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
    $('#mySelect2').select2();
    $('#from_date').datepicker({
        format: "dd-mm-yyyy",
        orientation: "left bottom",
    });
    $('#to_date').datepicker({
        format: "dd-mm-yyyy",
        orientation: "left bottom",
    });
});
$('#reset').on('click', function() {
        jQuery.ajax({
            url: "{{ url('/reports/reset') }}",
            method: 'get',
            success: function(result) {
                window.location = window.location.href;
            }
        });
    });
</script>
@endsection