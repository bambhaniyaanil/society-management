
<?php 
    $permissions = permission(); 
    $permissions = explode(',', $permissions);
?>
@extends('layout.master')
<style>
  .slider.slider-horizontal
  {
    width: 200px !important;
  }
  p.narration{
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
            @if(in_array(65, $permissions))
                <a type="button" href="{{route('reports.balance-sheet.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
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
        <form method="post" action="{{route('reports.balance-sheet-report')}}">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
                <?php 
                    $form_date = '';
                    if(Session::get('bsrfrom_date'))
                    {
                        $form_date = date('d-m-Y', strtotime(Session::get('bsrfrom_date')));
                    }
                    $to_date = '';
                    if(Session::get('bsrto_date'))
                    {
                        $to_date = date('d-m-Y', strtotime(Session::get('bsrto_date')));
                    }
                ?>
              <label>From Date
                <div class="input-group date datepicker" id="from_date">
                    <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="from_date" value="{{ $form_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                </div>
              </label>
              <label>To Date
                <div class="input-group date datepicker" id="to_date">
                    <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="to_date" value="{{ $to_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                </div>
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
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">        
            <h4>Liabilities</h4>   
            <div class="alert alert-success" style="display:none"></div>  
            @if(!empty($liabilitiesdata)) 
                @foreach($liabilitiesdata as $k => $lia)         
                    <div class="pt-3">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="float-left">
                                    <h5>{{$lia['title']}}</h5>
                                </div>
                            </div>
                        </div>
                        @if(!empty($lia['group_array']))
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-borderless">
                                        @foreach($lia['group_array'] as $k1 => $ga)
                                            <tr>
                                                <th width="5%">
                                                    <button class="btn btn-info btn-icon accordion-toggle" data-toggle="collapse" data-target="#target_{{$k}}_{{$k1}}">
                                                        <i data-feather="plus"></i>
                                                    </button></th>
                                                <th width="20%">{{$ga['sub_title']}}</th>
                                                <th width="10%">{{ number_format(abs($ga['total_amount']), 2)}}</th>
                                            </tr>
                                            @if(!empty($ga['ledger']))
                                                <tr>
                                                    <td colspan="12" class="hiddenRow">
                                                        <div class="accordian-body collapse" id="target_{{$k}}_{{$k1}}">
                                                            <table class="table">
                                                                <tbody>
                                                                    @foreach($ga['ledger'] as $ledger)
                                                                        <tr>
                                                                            <?php 
                                                                                $lnames = explode(',', $ledger['name']); 
                                                                                $clname = count($lnames);
                                                                            ?>
                                                                            <td width="5%"></td>
                                                                            <td width="20%">
                                                                                @foreach($lnames as $k1 => $lname)
                                                                                    <p>
                                                                                        {{$lname}}
                                                                                        @if($clname > $k1+1)
                                                                                        &
                                                                                        @endif
                                                                                    </p>
                                                                                @endforeach
                                                                            </td>
                                                                            @if($ledger['closing_amount'] == 0)
                                                                                <td width="10%">{{number_format(abs($ledger['amount']), 2)}}</td>
                                                                            @elseif($ledger['closing_amount'] > 0)
                                                                                <td width="10%"><span class="text-danger">{{number_format(abs($ledger['amount']), 2)}}</span></td>
                                                                            @else
                                                                                <td width="10%"><span class="text-success">{{number_format(abs($ledger['amount']), 2)}}</span></td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
                @if($profit != 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ __('Net Profit') }}</th>
                                    <th width="5%">{{ number_format(abs($profit), 2) }}</th>
                                </tr>           
                            </table>
                        </div>
                    </div>
                @endif
            @endif
            <hr>
            <h4 class="text-right mr-3">
                @if($profit != 0)
                    {{ number_format(($liabilities_total + abs($profit)), 2) }}
                @else
                    {{ number_format($liabilities_total, 2) }}
                @endif
            </h4>
        </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">        
            <h4>Assets</h4>   
            <div class="alert alert-success" style="display:none"></div>  
            @if(!empty($assetsdata)) 
                @foreach($assetsdata as $k => $asset)         
                    <div class="pt-3">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="float-left">
                                    <h5>{{$asset['title']}}</h5>
                                </div>
                            </div>
                        </div>
                        @if(!empty($asset['group_array']))
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-borderless">
                                        @foreach($asset['group_array'] as $k1 => $ga)
                                            <tr>
                                                <th width="2%">
                                                    <button class="btn btn-info btn-icon accordion-toggle" data-toggle="collapse" data-target="#target_asset_{{$k}}_{{$k1}}">
                                                        <i data-feather="plus"></i>
                                                    </button></th>
                                                <th width="20%">{{$ga['sub_title']}}</th>
                                                <th width="5%">{{ number_format(abs($ga['total_amount']), 2)}}</th>
                                            </tr>
                                            @if(!empty($ga['ledger']))
                                                <tr>
                                                    <td colspan="12" class="hiddenRow">
                                                        <div class="accordian-body collapse" id="target_asset_{{$k}}_{{$k1}}">
                                                            <table width="100%" class="table">
                                                                <tbody>
                                                                    @foreach($ga['ledger'] as $ledger)
                                                                        <tr>
                                                                            <?php 
                                                                                $lnames = explode(',', $ledger['name']); 
                                                                                $clname = count($lnames);
                                                                            ?>
                                                                            <td width="5%"></td>
                                                                            <td width="20%">
                                                                                @foreach($lnames as $k2 => $lname)
                                                                                    <p>
                                                                                        {{$lname}}
                                                                                        @if($clname > $k2+1)
                                                                                        &
                                                                                        @endif
                                                                                    </p>
                                                                                @endforeach
                                                                            </td>
                                                                            @if($ledger['closing_amount'] == 0)
                                                                                <td width="10%">{{number_format(abs($ledger['amount']), 2)}}</td>
                                                                            @elseif($ledger['closing_amount'] > 0)
                                                                                <td width="10%"><span class="text-danger">{{number_format(abs($ledger['amount']), 2)}}</span></td>
                                                                            @else
                                                                                <td width="10%"><span class="text-success">{{number_format(abs($ledger['amount']), 2)}}</span></td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
                @if($loss != 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ __('Net Loss') }}</th>
                                    <th width="5%">{{ number_format(abs($loss), 2) }}</th>
                                </tr>           
                            </table>
                        </div>
                    </div>
                @endif
            @endif
            <hr>
            <h4 class="text-right mr-3">
                @if($loss != 0)
                    {{ number_format(($assets_total + abs($loss)), 2) }}
                @else
                    {{ number_format($assets_total, 2) }}
                @endif
            </h4>
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