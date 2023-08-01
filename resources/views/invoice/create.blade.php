@extends('layout.master')
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('invoice')}}">Invoice</a></li>
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
          <form method="POST" action="{{url('invoice/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">By Ledger (Debit)</label>
                        <select class="form-control w-100" id="mySelect2" name="by_ledger" required>
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
                        <label class="control-label">Amount</label>
                        <input type="text" class="form-control byamount" placeholder="Amount" name="by_ledger_amount" value="0" readonly required>
                    </div>
                </div><!-- Col -->  
            </div>
            <div class="row" id="rm_0">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">To Ledger (Credit)</label>
                        <select class="form-control js-example-basic-single w-100" id="exampleFormControlSelect1" name="ledger[0][to_ledger_id]" required>
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
        
                <div class="col-sm-5">
                    <div class="form-group">
                        <label class="control-label">Amount</label>
                        <input type="text" class="form-control toamount" placeholder="Amount" name="ledger[0][amount]" value="" onkeyup="amount()"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                    </div>
                </div><!-- Col -->  
                
                <div class="col-sm-1">
                    <br>
                    <a href="javascript:;" class="btn btn-success btn-icon pt-1" id="rmadd_0" onclick="addMore(0)">
                        <i data-feather="plus"></i>
                    </a>
                    <div id="addrmbtn_0"></div>
                </div>
            </div>
            <div id="addmore"></div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Bill No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Bill No." name="bill_no" value="{{old('bill_no')}}" required>
                    </div>
                </div><!-- Col --> 
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Bill Period <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Bill Period" name="bill_period" value="{{old('bill_period')}}" required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Bill Date <span class="text-danger">*</span></label>
                        <div class="input-group date datepicker" id="bill_date">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="bill_date" required><span class="input-group-addon"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                </div><!-- Col -->
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Due Date <span class="text-danger">*</span></label>
                        <div class="input-group date datepicker" id="due_date">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="due_date" required><span class="input-group-addon"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Arrears</label>
                        <input type="text" class="form-control" placeholder="Arrears" name="arrears" value="{{old('arrears')}}">
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="exampleFormControlSelect1" name="status">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Narration</label>
                        <textarea class="form-control" name="narration" rows="5"></textarea>
                    </div>
                </div><!-- Col --> 

              
            </div><!-- Row -->
            <a href="{{route('invoice')}}" type="button" class="btn btn-danger">Back</a>
            <button type="submit" class="btn btn-primary submit">Subimt</button>
          </form>          
      </div>
    </div>
  </div>
</div>
<script>

    var ledgers = <?php echo json_encode($ledgers); ?>;
    $(document).ready(function() {
        $('#mySelect2').select2();
        $('#bill_date').datepicker({
            format: "dd-mm-yyyy"
        });
        $('#due_date').datepicker({
            format: "dd-mm-yyyy"
        });
    });
    function addMore(index)
    {
        $('#rmadd_'+index).remove();
        var i = index + 1;
        var html = '';
            html += '<div class="row" id="rm_'+i+'">';
            html +=     '<div class="col-sm-6">';
            html +=         '<div class="form-group">';
            html +=             '<label class="control-label">To Ledger (Credit)</label>';
            html +=             '<select class="form-control js-example-basic-single w-100" id="select2_'+i+'" name="ledger['+i+'][to_ledger_id]" required>';
            html +=                 '<option disebal value="">Select To Ledger (Credit)</option>';
                                    $.each(ledgers, function(k,v){
            html +=                     '<option value="'+v.id+'">';
                                            if(v.wing_flat_no != null)
                                            {
            html +=                             v.wing_flat_no+' - ';
                                            }
            html +=                         v.name+'</option>';
                                    });
            html +=             '</select>';
            html +=         '</div>';
            html +=     '</div>';
        
            html +=     '<div class="col-sm-5">';
            html +=         '<div class="form-group">';
            html +=             '<label class="control-label">Amount</label>';
            html +=             '<input type="text" class="form-control toamount" placeholder="Amount" name="ledger['+i+'][amount]" value="" onkeyup="amount()" required>';
            html +=         '</div>';
            html +=     '</div>';
                
            html +=     '<div class="col-sm-1">';
            html +=         '<br>';
            html +=         '<a href="javascript:;" class="btn btn-success btn-icon pt-1" id="rmadd_'+i+'" onclick="addMore('+i+')">';
            html +=             '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
            html +=         '</a>';
            html +=         '<div id="addrmbtn_'+i+'"></div>';
            html +=     '</div>';
            html += '</div>';

            var htmlr = '<a href="javascript:;" class="btn btn-danger btn-icon pt-1" id="rm_btn_'+index+'" onclick="remove('+index+')">';
                htmlr +=   '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
                htmlr += '</a>'

            $('#addmore').append(html);
            $('#addrmbtn_'+index).html(htmlr);
            $('#select2_'+i).select2();
    }

    function remove(index)
    {
        $('#rm_'+index).remove();
        var byamount = 0;
        $('.toamount').each(function()
        {
            byamount =  parseInt(byamount) + parseInt($(this).val());
        })

        $('.byamount').val(byamount);
    }

    function amount()
    {
        var byamount = 0;
        $('.toamount').each(function()
        {
            byamount =  parseInt(byamount) + parseInt($(this).val());
        })

        $('.byamount').val(byamount);
    }

</script>
@endsection
@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush
