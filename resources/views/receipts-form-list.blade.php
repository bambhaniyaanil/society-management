<?php 
    $permissions = permission(); 
    $permissions = explode(',', $permissions);
?>
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />  
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
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
  .select2-container
  {
    width: 100% !important;
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
        <h6 class="card-title">{{$sub_title}}</h6>          
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
          <table class="table">
            <thead>
              <tr>
                <th>LEDGER (CREDIT)</th>
                <th>EMAIL</th>
                <th>MOBILE NUMBER</th>
                <th>BANK NAME</th>
                <th>Chack No / Transaction No</th>
                <th>AMOUNT</th>
                <th>DATE</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
            <?php $total = 0; ?>
              @if(!empty($receipt_forms))
                @foreach($receipt_forms as $rf)
                    <?php 
                      $total += $rf->amount; 
                      $toledgers = explode(',', $rf->toLedger->name);
                      $ctoledger = count($toledgers);
                    ?>
                    <tr>
                        <td>
                          @foreach($toledgers as $k => $toledger)
                            <p>
                              @if(!empty($rf->toLedger->wing_flat_no) && $k == 0)
                                {{$rf->toLedger->wing_flat_no}} -
                              @endif
                              {{$toledger}}
                              @if($ctoledger > $k+1)
                                &
                              @endif
                            </p>
                          @endforeach
                        </td>
                        <td>{{$rf->email}}</td>
                        <td>{{$rf->mobile_number}}</td>
                        <td>{{$rf->bank_name}}</td>
                        <td>{{$rf->check_transaction_no}}</td>
                        <td>{{$rf->amount}}</td>
                        <td>{{date('d-m-Y', strtotime($rf->submit_date))}}</td>
                        <td> 
                            <a href="javascript:void(0);" class="btn btn-success px-2" onclick="formAccept({{$rf->id}})">
                              Accept
                            </a>             
                            <a href="{{url('receipts-voucher/edit-form/'.$rf->id)}}" class="btn btn-primary btn-icon pt-1">
                              <i data-feather="edit"></i>
                            </a>
                            <a href="{{url('receipts-voucher/delete-form/'.$rf->id)}}" class="btn btn-danger btn-icon pt-1">
                              <i data-feather="trash"></i>
                            </a>
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
                <th colspan="4"></th>
                <th>Total</th>
                <th>{{$total}}</th>
                <th colspan="4"></th>
              </tr>
            </tfooter>
          </table>
          <div class="row">
              <div class="col-sm-12">
                <div class="float-right">
                    {{ $receipt_forms->links() }}
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
                <h5 class="modal-title" id="exampleModalLabel">Receipt Accept</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" action="{{route('receipts-voucher.receipt-form-accept')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="form_id" value="0" id="form_id">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="control-label">Select Ledger ( BY LEDGER - BANK / CASH )</label>
                                <select class="form-control w-100" id="mySelect4" name="ledger" required>
                                    <option value="">Select Ledger</option>
                                    @foreach($ledgers as $ledger)
                                        <option value="{{$ledger->id}}" <?php if(old('ledger') == $ledger->id) echo 'selected'; ?>>
                                            @if(!empty($ledger->wing_flat_no))
                                                {{$ledger->wing_flat_no}} - 
                                            @endif
                                            {{$ledger->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                          <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
     $(document).ready(function() {
        $('#mySelect4').select2({
          dropdownParent: $('#exampleModal')
        });
    });
    function formAccept(id)
    {
      $('#form_id').val(id);
      $('#exampleModal').modal('show');
    }
</script>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush

