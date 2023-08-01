

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
          @if(in_array(63, $permissions))
            <a type="button" href="{{route('reports.profit-loss.export')}}" class="btn float-right btn-outline-primary btn-icon-text mr-2 d-none d-md-block">
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
        <form method="post" action="{{route('reports.profit-loss-report')}}">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
                <?php 
                    $form_date = '';
                    if(Session::get('plrfrom_date'))
                    {
                        $form_date = date('d-m-Y', strtotime(Session::get('plrfrom_date')));
                    }
                    $to_date = '';
                    if(Session::get('plrto_date'))
                    {
                        $to_date = date('d-m-Y', strtotime(Session::get('plrto_date')));
                    }
                ?>
              <label>From Date
                <!-- <input type="date" class="form-control" name="from_date" value="{{Session::get('lrfrom_date')}}"> -->
                <div class="input-group date datepicker" id="from_date">
                    <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="from_date" value="{{ $form_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                </div>
              </label>
              <label>To Date
                <div class="input-group date datepicker" id="to_date">
                    <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="to_date" value="{{ $to_date }}" autocomplete="off"><span class="input-group-addon"><i data-feather="calendar"></i></span>
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
@if(empty($profitloassdata))
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">        
        <h6 class="card-title"></h6>    
          
        <div class="alert alert-success" style="display:none"></div>            
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th width="25%">Particulars</th>
                <th width="10%">Debit</th>
                <th width="10%">Credit</th>
              </tr>
            </thead>
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
@else
<div class="row">
    @foreach($profitloassdata as $k => $value)
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">        
                      <h4 class="card-title">{{$value['title']}}</h4>
                      <div class="alert alert-success" style="display:none"></div>            
                      <div>
                          @if(!empty($value['group_array']))
                              <div class="row">
                                  <div class="col-sm-12">
                                      <table class="table table-borderless">
                                          @foreach($value['group_array'] as $k1 => $ga)
                                              <tr>
                                                  <th width="5%">
                                                      <button class="btn btn-info btn-icon accordion-toggle" data-toggle="collapse" data-target="#target_asset_{{$k}}_{{$k1}}">
                                                          <i data-feather="plus"></i>
                                                      </button></th>
                                                  <th>{{$ga['sub_title']}}</th>
                                                  <th width="10%">{{ number_format($ga['total_amount'], 2) }}</th>
                                              </tr>
                                              @if(!empty($ga['ledger']))
                                                  <tr>
                                                      <td colspan="12" class="hiddenRow">
                                                          <div class="accordian-body collapse" id="target_asset_{{$k}}_{{$k1}}">
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
                                                                              <td width="10%">{{ number_format($ledger['amount'], 2) }}</td>
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
                          <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-borderless">
                                    <tr>
                                        @if($net_amount > 0 && $k == 0)
                                          <th>{{ __('Net Profit') }}</th>
                                          <th width="5%">{{ number_format(abs($net_amount), 2) }}</th>
                                        @endif
                                        @if($net_amount < 0 && $k == 1)
                                          <th>{{ __('Net Loss') }}</th>
                                          <th width="5%">{{ number_format(abs($net_amount), 2) }}</th>
                                        @endif
                                    </tr>           
                                </table>
                            </div>
                          </div>
                      </div>
                      <hr>
                      <h4 class="text-right mr-3">{{ number_format($value['final_amount']) }}</h4>
                  </div>
                </div>
            </div>
    @endforeach
</div>
@endif
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