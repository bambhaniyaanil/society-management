@extends('layout.master')
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('users')}}">Users</a></li>
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
          <form method="POST" action="{{url('users/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">User Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="User Name" name="s_user_name" value="{{old('s_user_name')}}" required>
                    </div>
                </div><!-- Col -->  

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Email" name="s_user_email" value="{{old('s_user_email')}}"  required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Mobile" name="s_user_mobile_number" value="{{old('s_user_mobile_number')}}"  required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">WhatsApp Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="WhatsApp Mobile" name="s_user_wp_number" value="{{old('s_user_wp_number')}}"  required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" placeholder="Password" name="password" value="{{old('password')}}"  required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="file-upload-default">
                        <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                        <span class="input-group-append">
                            <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                        </span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Roles <span class="text-danger">*</span></label>
                        <select class="form-control" id="exampleFormControlSelect1" name="role" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->role_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="exampleFormControlSelect1" name="s_user__status">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                    </div>
                </div><!-- Col -->

              
            </div><!-- Row -->
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