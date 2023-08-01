@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('tenant-register')}}">Parking Register</a></li>
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
          <form method="POST" action="{{url('tenant-register/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Flat No</label>
                        <input type="text" class="form-control" placeholder="ENTER FLAT NO HERE" name="flat_no" value="{{old('flat_no')}}" required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Tenant Name</label>
                        <input type="text" class="form-control" placeholder="ENTER TENANT NAME HERE" name="tenant_name" value="{{old('tenant_name')}}" required>
                    </div>
                </div><!-- Col --> 

                <!-- <div class="col-sm-6">
                    <label class="control-label">Tenant Permanent / Native Address</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select class="form-control" name="tenant_owner_select">
                                <option value="Tenant">Tenant</option>
                                <option value="Owner">Owner</option>
                            </select>
                        </div>
                        <textarea class="form-control" name="address" rows="5"></textarea>
                    </div>
                </div> -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Tenant Permanent Address</label>
                        <textarea class="form-control" name="parmanent_address" rows="5"></textarea>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Tenant Native Address</label>
                        <textarea class="form-control" name="native_address" rows="5"></textarea>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">KYC Detail</label>
                        <textarea class="form-control" name="kyc_detail" rows="5" required></textarea>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Contact Number</label>
                        <input type="text" class="form-control" placeholder="ENTER CONTACT NUMBER HERE" name="contact_number" value="{{old('contact_number')}}" required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Leave & Licence Period Start Date</label>
                        <div class="input-group date datepicker" id="start_date">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="period_start_date" value="{{date('d-m-Y')}}" required><span class="input-group-addon"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Leave & Licence Period End Date</label>
                        <div class="input-group date datepicker" id="end_date">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="period_end_date" value="{{date('d-m-Y')}}" required><span class="input-group-addon"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Leave & Licence Agreement Submitted</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="leave_licence_agreement_submitted" id="leave_licence_agreement_submitted" value="Yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="leave_licence_agreement_submitted" id="leave_licence_agreement_submitted" value="No">
                                No
                            </label>
                        </div>
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
            <a href="{{route('tenant-register')}}" type="button" class="btn btn-danger">Back</a>
            <button type="submit" class="btn btn-primary submit">Subimt</button>
          </form>          
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        $('#mySelect2').select2();
        $('#start_date').datepicker({
            format: "dd-mm-yyyy"
        });

        $('#end_date').datepicker({
            format: "dd-mm-yyyy"
        });
    });
</script>
@endsection

@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush