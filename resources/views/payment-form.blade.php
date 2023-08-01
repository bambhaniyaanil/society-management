@extends('layout.master2')

@section('content')

<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-12 pr-md-0">
            <div class="auth-left-wrapper text-center">
                <img width="100%" src="{{ URL::asset('assets/images/logo.png')}}" class="img-fluid" />
            </div>
          </div>
          <div class="col-md-12 pl-md-0">
            <div class="auth-form-wrapper px-4 py-5">
            <div class="text-center">
            </div>
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            <li>{{ $message }}</li>
                        </ul>
                    </div>
                @endif 
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif 
              <form class="forms-sample" method="POST" action="{{ route('payment-form-save') }}">
              {{ csrf_field() }}
                <div class="form-group">
                  <label for="control-label">Select Your Society</label>
                    <select class="form-control w-100" name="society" id="society" required>
                        <option value="">Select Your Society</option>
                        @foreach($societys as $society)
                            <option value="{{$society->id}}" <?php if(old('society') == $society->id) echo 'selected'; ?>>{{$society->society_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                  <label for="control-label">Select Ledger</label>
                    <select class="form-control w-100" id="mySelect3" name="ledger" required>
                        <option value="">Select Ledger</option>
                        {{-- @foreach($ledgers as $ledger)
                            <option value="{{$ledger->id}}" <?php if(old('ledger') == $ledger->id) echo 'selected'; ?>>
                                @if(!empty($ledger->wing_flat_no))
                                    {{$ledger->wing_flat_no}} - 
                                @endif
                                {{$ledger->name}}
                            </option>
                        @endforeach --}}
                    </select>
                </div>
                <div class="form-group">
                  <label for="control-label">Email</label>
                  <input type="text" class="form-control" placeholder="Enter Your Email" name="email">  
                </div>
                <div class="form-group">
                  <label for="control-label">Mobile Number</label>
                  <input type="text" class="form-control" placeholder="Enter Your Mobile Number" name="mobile_number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">  
                </div>
                <div class="form-group">
                  <label for="control-label">Bank Name</label>
                  <input type="text" class="form-control" placeholder="Enter Your Bank Name" name="bank_name" required>  
                </div>
                <div class="form-group">
                  <label for="control-label">Cheque No / Transaction No</label>
                  <input type="text" class="form-control" placeholder="Enter Your Cheque No / Transaction No" name="check_transaction_no" required>  
                </div>
                <div class="form-group">
                    <label class="control-label">Amount</label>
                    <input type="text" class="form-control" placeholder="Enter Your Amount" name="amount" value="{{old('amount')}}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                </div>
                <div class="form-group">
                    <label class="control-label">Date</label>
                    <div class="input-group date datepicker" id="submit_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="submit_date" value="{{date('d-m-Y')}}" required><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Narration</label>
                    <textarea class="form-control" name="narration" rows="5"></textarea>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0">
                      {{ __('Submit') }}
                  </button>   
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<script>
    $(document).ready(function() {
        $('#mySelect3').select2();
        $('#submit_date').datepicker({
            format: "dd-mm-yyyy"
        });
    });

    $('#society').on('change', function(){
      var base_url = "{{url('')}}";
      $.ajax({
        url: base_url+"/receipt-form/ledger/"+$(this).val(),
        type: "GET",
        success: function (data) {
          data = JSON.parse(data);
          var html = '';
            html += '<option value="">Select Ledger</option>';
          $.each(data.ledgers, function(k, v){
            html += '<option value="'+v.id+'">';
                if(v.wing_flat_no != null || v.wing_flat_no != '')
                {
            html +=   v.wing_flat_no+' -';
                }
            html += v.name
            html += '</option>';
          })
          $('#mySelect3').html(html);
        }
      })
    })
</script>
@endsection