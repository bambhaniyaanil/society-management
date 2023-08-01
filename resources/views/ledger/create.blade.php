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
          <form method="POST" action="{{url('ledger/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">UNDER GROUP <span class="text-danger">*</span></label>
                  <select class="form-control w-100" id="mySelect2"  name="under_group">
                    <option value="">Select Under Group</option>
                    @foreach($group_creations as $group_creation)
                      <option value="{{$group_creation->id}}">{{$group_creation->name}}</option>
                    @endforeach
                  </select>
                  <!-- <input type="text" class="form-control" placeholder="Under Group" name="under_group" value="{{old('under_group')}}"> -->
                </div>
              </div><!-- Col -->
        
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" placeholder="Name" name="name" value="{{old('name')}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->              

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">UNIT-WING-FLAT-NO</label>
                  <input type="text" class="form-control" placeholder="Wing-Flat-No" name="wing_flat_no" value="{{old('wing_flat_no')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <label class="control-label">AREA</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <select class="form-control" name="area_sq">
                        <option value="mtr">SQ MTR</option>
                        <option value="ft">SQ FT</option>
                    </select>
                  </div>
                  <input type="text" class="form-control" placeholder="Area" name="area_sq_value" value="{{old('area_sq_value')}}">
                </div>
              </div><!-- Col -->
                             
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">CONTACT NUMBER <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Contact Number" name="contact_number" value="{{old('contact_number')}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->

              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">WHATS APP NUMBER <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Whats App Number" name="whats_app_number" value="{{old('whats_app_number')}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">EMAIL ID</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Email ID" name="email_id" value="{{old('email_id')}}" data-role="tagsinput">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">PANCARD NUMBER</label>
                    <input type="text" class="form-control" value="{{old('pancard_number')}}"  autocomplete="off" placeholder="Pancard Number" name="pancard_number" data-role="tagsinput" />
                    <!-- <input type="text" class="form-control" autocomplete="off" placeholder="Pancard Number" name="pancard_number" value="{{old('pancard_number')}}"> -->
                </div>
              </div><!-- Col -->
              <div class="col-sm-6 example">
                <div class="form-group bs-example">
                    <label class="control-label">ADHAR NUMBER</label>
                    <input type="text" class="form-control" value="{{old('adhar_number')}}"  autocomplete="off" placeholder="Adhar Number" name="adhar_number" data-role="tagsinput" />
                    <!-- <input type="text" class="form-control" autocomplete="off" placeholder="Adhar Number" name="adhar_number" value="{{old('adhar_number')}}"> -->
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">GST NUMBER</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="GST Number" name="gst_number" value="{{old('gst_number')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">RESIDE ADDRESS</label>
                    <textarea class="form-control" name="reside_address" rows="5"> {{old('reside_address')}} </textarea>
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">CORRESPONDENCE ADDRESS</label>
                    <textarea class="form-control" name="correspondence_address"  rows="5">{{old('correspondence_address')}}</textarea>
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">AREA/LOCALITY</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Area/Locality" name="area_locality" value="{{old('area_locality')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">CITY/DISTRICT</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="City/District" name="city_district" value="{{old('city_district')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">STATE</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="State" name="state" value="{{old('state')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">PIN CODE</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Pincode" name="pin_code" value="{{old('pin_code')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Country</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Country" name="country" value="{{old('country')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">REGISTRATION DATE</label>
                    <!-- <input type="date" class="form-control" autocomplete="off" placeholder="dd-mm-yyyy" name="registration_date" value="{{old('registration_date')}}" > -->
                    <div class="input-group date datepicker" id="registration_date">
                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="registration_date" value="{{date('d-m-Y')}}"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">OPNING BALANCE</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <select class="form-control" name="balance_type">
                            <option value="credit">Credit</option>
                            <option value="debit">Debit</option>
                        </select>
                      </div>
                      <input type="text" class="form-control" autocomplete="off" placeholder="Opning Balance" name="opning_balance" value="{{old('opning_balance')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                    </div>
                    <!-- <input type="text" class="form-control" autocomplete="off" placeholder="Opning Balance" name="opning_balance" value="{{old('opning_balance')}}"> -->
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">STATUS</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="status">
                        <option value="1">Active</option>
                        <option value="0">Deactive</option>
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