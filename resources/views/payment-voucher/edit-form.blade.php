@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('payment-form-list')}}">Payment Voucher Form Data</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">{{$page_title}}</h6>
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
          <form method="POST" action="{{route('payment-voucher.update-form', $data->id)}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Select Ledger</label>
                        <select class="form-control" id="mySelect2" name="ledger" required>
                            <option value="">Select Ledger</option>
                            @foreach($ledgers as $ledger)
                                <option value="{{$ledger->id}}" <?php if($data->ledger_id == $ledger->id) echo 'selected'; ?>>
                                    @if(!empty($ledger->wing_flat_no))
                                        {{$ledger->wing_flat_no}} - 
                                    @endif
                                    {{$ledger->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="control-label">Email</label>
                        <input type="text" class="form-control" placeholder="Enter Your Email" name="email" value="{{$data->email}}" required>  
                    </div>
                </div><!-- Col -->
        
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="control-label">Mobile Number</label>
                        <input type="text" class="form-control" placeholder="Enter Your Mobile Number" name="mobile_number" value="{{$data->mobile_number}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>  
                    </div>
                </div><!-- Col -->              

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="control-label">Bank Name</label>
                        <input type="text" class="form-control" placeholder="Enter Your Bank Name" name="bank_name" value="{{$data->bank_name}}" required>  
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="control-label">Cheque No / Transaction No</label>
                        <input type="text" class="form-control" placeholder="Enter Your Cheque No / Transaction No" name="check_transaction_no" value="{{$data->check_transaction_no}}" required>  
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Amount</label>
                        <input type="text" class="form-control" placeholder="Enter Your Amount" name="amount" value="{{$data->amount}}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <label class="control-label">Date</label>
                    <div class="input-group date datepicker" id="submit_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="submit_date" value="{{date('d-m-Y', strtotime($data->submit_date))}}" required><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Narration</label>
                        <textarea class="form-control" name="narration" rows="5"> {{$data->narration}} </textarea>
                    </div>
                </div><!-- Col --> 

              
            </div><!-- Row -->
            <div class="mt-3">
                <a href="{{url('payment-form-list')}}" type="button" class="btn btn-danger">Back</a>
                <button type="submit" class="btn btn-primary submit">Subimt</button>
            </div>
          </form>          
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        $('#mySelect2').select2();
        $('#submit_date').datepicker({
            format: "dd-mm-yyyy"
        });
    });
</script>
@endsection

@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush