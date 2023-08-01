@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('user')}}">Users</a></li>
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
          <form method="POST" action="{{url('user/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">User Name</label>
                        <input type="text" class="form-control" placeholder="User Name" name="s_user_name" value="{{old('s_user_name')}}">
                    </div>
                </div><!-- Col --> 
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="email" class="form-control" placeholder="Email" name="s_user_email" value="{{old('s_user_email')}}">
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Mobile Number</label>
                        <input type="text" class="form-control" placeholder="Mobile Number" name="s_user_mobile_number" value="{{old('s_user_mobile_number')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">WhatsApp Number</label>
                        <input type="text" class="form-control" placeholder="WhatsApp Number" name="s_user_wp_number" value="{{old('s_user_wp_number')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <input type="text" class="form-control" placeholder="Password" name="password" value="{{old('password')}}">
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                    <label class="control-label">Photo</label>
                    <input type="file" class="file-upload-default" name="photo">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Browse</button>
                            </span>
                        </div>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="s_user__status">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                    </div>
                </div><!-- Col -->

              
            </div><!-- Row -->
            <a href="{{route('user')}}" type="button" class="btn btn-danger">Back</a>
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