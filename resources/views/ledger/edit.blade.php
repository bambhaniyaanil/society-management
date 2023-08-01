@extends('layout.master')
<link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('ledger')}}">Ledger</a></li>
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
            @if ($message = Session::get('error'))
              <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    <li>{{ $message }}</li>
                </ul>
              </div>
            @endif 
          <form method="POST" action="{{route('ledger.update', $ledger->id)}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">UNDER GROUP <span class="text-danger">*</span></label>
                  <!-- <input type="text" class="form-control" placeholder="Under Group" name="under_group" value="{{$ledger->under_group}}"> -->
                  <select class="form-control w-100" id="mySelect2" name="under_group" required>
                    <option value="">Select Under Group</option>
                    @foreach($group_creations as $group_creation)
                      <option value="{{$group_creation->id}}" <?php if($ledger->under_group == $group_creation->id) echo 'selected'; ?>>{{$group_creation->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div><!-- Col -->
        
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="name" value="{{$ledger->name}}" data-role="tagsinput" required>
                </div>
              </div><!-- Col -->              

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">UNIT-WING-FLAT-NO</label>
                  <input type="text" class="form-control" placeholder="Wing-Flat-No" name="wing_flat_no" value="{{$ledger->wing_flat_no}}">
                </div>
              </div><!-- Col -->
              @if(!empty($ledger->area_sq_mtr))
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">AREA</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <select class="form-control" name="area_sq">
                            <option value="mtr" selected="selected">SQ MTR</option>
                            <option value="ft">SQ FT</option>
                        </select>
                      </div>
                      <input type="text" class="form-control" placeholder="Area" name="area_sq_value" value="{{$ledger->area_sq_mtr}}">
                    </div>
                  </div>
                </div><!-- Col -->
              @else
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">AREA</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <select class="form-control" name="area_sq">
                            <option value="mtr">SQ MTR</option>
                            <option value="ft" selected="selected">SQ FT</option>
                        </select>
                      </div>
                      <input type="text" class="form-control" placeholder="Area" name="area_sq_value" value="{{$ledger->area_sq_ft}}">
                    </div>
                  </div>
                </div><!-- Col -->
              @endif
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">CONTACT NUMBER <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" autocomplete="off" name="contact_number" value="{{$ledger->contact_number}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->

              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">WHATS APP NUMBER <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" autocomplete="off" name="whats_app_number" value="{{$ledger->whats_app_number}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">EMAIL ID</label>
                    <input type="text" class="form-control" autocomplete="off" name="email_id" value="{{$ledger->email_id}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">PANCARD NUMBER</label>
                    <input type="text" class="form-control" autocomplete="off" name="pancard_number" value="{{$ledger->pancard_number}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6 example">
                <div class="form-group bs-example">
                    <label class="control-label">ADHAR NUMBER</label>
                    <input type="text" class="form-control" value="{{$ledger->adhar_number}}"  autocomplete="off" name="adhar_number" data-role="tagsinput" />
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">GST NUMBER</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="GST Number" name="gst_number" value="{{$ledger->gst_number}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">RESIDE ADDRESS</label>
                    <textarea class="form-control" name="reside_address" rows="5"> {{$ledger->reside_address}} </textarea>
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">CORRESPONDENCE ADDRESS</label>
                    <textarea class="form-control" name="correspondence_address"  rows="5">{{$ledger->correspondence_address}}</textarea>
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">AREA/LOCALITY</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Area/Locality" name="area_locality" value="{{$ledger->area_locality}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">CITY/DISTRICT</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="City/District" name="city_district" value="{{$ledger->city_district}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">STATE</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="State" name="state" value="{{$ledger->state}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">PIN CODE</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Pincode" name="pin_code" value="{{$ledger->pin_code}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Country</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Country" name="country" value="{{$ledger->country}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">REGISTRATION DATE</label>
                    <!-- <input type="date" class="form-control" autocomplete="off" placeholder="Registration Date" name="registration_date" value="{{$ledger->registration_date}}"> -->
                    <div class="input-group date datepicker" id="registration_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="registration_date" value="{{date('d-m-Y', strtotime($ledger->registration_date))}}"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <!-- <div class="form-group">
                    <label class="control-label">OPNING BALANCE</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Opning Balance" name="opning_balance" value="{{$ledger->opning_balance}}">
                </div> -->
                @if($ledger->opning_balance_debit != 0 || $ledger->opning_balance_debit != '')
                  <div class="form-group">
                      <label class="control-label">OPNING BALANCE</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <select class="form-control" name="balance_type">
                              <option value="credit">Credit</option>
                              <option value="debit" selected="selected">Debit</option>
                          </select>
                        </div>
                        <input type="text" class="form-control" autocomplete="off" placeholder="Opning Balance" name="opning_balance" value="{{$ledger->opning_balance_debit}}">
                      </div>
                  </div>
                @else
                  <div class="form-group">
                      <label class="control-label">OPNING BALANCE</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <select class="form-control" name="balance_type">
                              <option value="credit" selected="selected">Credit</option>
                              <option value="debit">Debit</option>
                          </select>
                        </div>
                        <input type="text" class="form-control" autocomplete="off" placeholder="Opning Balance" name="opning_balance" value="{{$ledger->opning_balance_credit}}">
                      </div>
                  </div>
                @endif
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">STATUS</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="status">
                        <option value="1" <?php if($ledger->status == 1) echo 'selected'; ?>>Active</option>
                        <option value="0" <?php if($ledger->status == 0) echo 'selected'; ?>>Deactive</option>
                    </select>
                </div>
              </div><!-- Col -->

              
            </div><!-- Row -->
            <a href="{{route('ledger')}}" type="button" class="btn btn-danger">Back</a>
            <button type="submit" class="btn btn-primary submit">Subimt</button>
          </form>          
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        $('#mySelect2').select2();
        $('#registration_date').datepicker({
            format: "dd-mm-yyyy"
        });
    });
</script>
@endsection

@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
  <script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
@endpush