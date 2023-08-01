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
          <form method="POST" action="{{route('parking-register.update', $parking->id)}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Sticker No</label>
                        <input type="text" class="form-control" placeholder="ENTER STICKER NO HERE" name="sticker_no" value="{{$parking->sticker_no}}" required>
                    </div>
                </div><!-- Col --> 
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Vehicle Type</label>
                        <input type="text" class="form-control" placeholder="ENTER VEHICLE TYPE HERE" name="vehicle_type" value="{{$parking->vehicle_type}}" required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Vehicle Number</label>
                        <input type="text" class="form-control" placeholder="ENTER VEHICLE NUMBER HERE" name="vehicle_number" value="{{$parking->vehicle_number}}" required>
                    </div>
                </div><!-- Col --> 


                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Flat No</label>
                        <input type="text" class="form-control" placeholder="ENTER FLAT NO HERE" name="flat_no" value="{{$parking->flat_no}}" required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <?php 
                        $value = '';
                        if($parking->tenat_name != '')
                        {
                            $value = $parking->tenat_name;
                        }
                        else{
                            $value = $parking->owner_name;
                        }
                    ?>
                    <label class="control-label">Tenant / Owner Name</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select class="form-control" name="tenant_owner_select">
                                <option value="Tenant" <?php if($parking->tenat_name != '') echo 'selected'; ?>>Tenant</option>
                                <option value="Owner" <?php if($parking->owner_name != '') echo 'selected'; ?>>Owner</option>
                            </select>
                        </div>
                        <input type="text" class="form-control" placeholder="ENTER TENANT / OWNER HERE" name="tenant_owner" value="{{$value}}">
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Contact Number</label>
                        <input type="text" class="form-control" placeholder="ENTER CONTACT NUMBER HERE" name="contact_number" value="{{$parking->contact_number}}" required>
                    </div>
                </div><!-- Col --> 

             
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="status">
                            <option value="1" <?php if($parking->status == 1) echo 'selected'; ?>>Active</option>
                            <option value="0" <?php if($parking->status == 0) echo 'selected'; ?>>Deactive</option>
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