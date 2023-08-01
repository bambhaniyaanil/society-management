@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('journal-voucher')}}">Journal Voucher</a></li>
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
          <form method="POST" action="{{url('journal-voucher/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">By Ledger (Debit)</label>
                        <select class="form-control" id="mySelect2" name="buy_ledger_id" required>
                            <option value="">Select By Ledger (Debit)</option>
                            @foreach($ledgers as $ledger)
                                <option value="{{$ledger->id}}">
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
                        <label class="control-label">To Ledger (Credit)</label>
                        <select class="form-control js-example-basic-single" id="exampleFormControlSelect1" name="to_ledger_id" required>
                            <option disebal value="">Select To Ledger (Credit)</option>
                            @foreach($ledgers as $ledger)
                                <option value="{{$ledger->id}}">
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
                        <label class="control-label">Amount</label>
                        <input type="text" class="form-control" placeholder="Amount" name="amount" value="{{old('amount')}}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                    </div>
                </div><!-- Col -->              

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Date</label>
                        <!-- <input type="date" class="form-control" name="submit_date" value="{{date('d-m-Y')}}" required> -->
                        <div class="input-group date datepicker" id="submit_date">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="submit_date" value="{{date('d-m-Y')}}"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Narration</label>
                        <textarea class="form-control" name="narration" rows="5"></textarea>
                    </div>
                </div><!-- Col --> 
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="status">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                    </div>
                </div><!-- Col -->

              
            </div><!-- Row -->
            <a href="{{route('journal-voucher')}}" type="button" class="btn btn-danger">Back</a>
            <button type="submit" class="btn btn-primary submit">Subimt</button>
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