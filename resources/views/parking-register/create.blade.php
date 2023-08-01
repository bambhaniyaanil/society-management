@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('parking-register')}}">Parking Register</a></li>
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
          <form method="POST" action="{{url('parking-register/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Sticker No</label>
                        <input type="text" class="form-control" placeholder="ENTER STICKER NO HERE" name="sticker_no" value="{{old('sticker_no')}}" required>
                    </div>
                </div><!-- Col --> 
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Vehicle Type</label>
                        <input type="text" class="form-control" placeholder="ENTER VEHICLE TYPE HERE" name="vehicle_type" value="{{old('vehicle_type')}}" required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Vehicle Number</label>
                        <input type="text" class="form-control" placeholder="ENTER VEHICLE NUMBER HERE" name="vehicle_number" value="{{old('vehicle_number')}}" required>
                    </div>
                </div><!-- Col --> 


                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Flat No</label>
                        <input type="text" class="form-control" placeholder="ENTER FLAT NO HERE" name="flat_no" value="{{old('flat_no')}}" required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <label class="control-label">Tenant / Owner Name</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select class="form-control" name="tenant_owner_select">
                                <option value="Tenant">Tenant</option>
                                <option value="Owner">Owner</option>
                            </select>
                        </div>
                        <input type="text" class="form-control" placeholder="ENTER TENANT / OWNER HERE" name="tenant_owner" value="{{old('tenant_owner')}}">
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
                        <label class="control-label">Status</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="status">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                    </div>
                </div><!-- Col -->

              
            </div><!-- Row -->
            <a href="{{route('parking-register')}}" type="button" class="btn btn-danger">Back</a>
            <button type="submit" class="btn btn-primary submit">Subimt</button>
          </form>          
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush