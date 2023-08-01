@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
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
          <form method="POST" action="{{url('/update_profile')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Socity</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="society_id">
                      @foreach($socitys as $socity)
                        <option value="{{$socity->id}}" <?php if($socity->id == $user->society_id) echo 'selected'; ?>>{{$socity->society_name}}</option>
                      @endforeach
                    </select>
                </div>
              </div><!-- Col -->

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">User Name</label>
                  <input type="text" class="form-control" placeholder="User Name" name="s_user_name" value="{{$user->s_user_name}}" required>
                </div>
              </div><!-- Col -->
              
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">Email</label>
                  <input type="email" class="form-control" autocomplete="off" placeholder="Email" name="s_user_email" value="{{$user->s_user_email}}">
                </div>
              </div><!-- Col -->

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">Mobile</label>
                  <input type="text" class="form-control" placeholder="Mobile" name="s_user_mobile_number" value="{{$user->s_user_mobile_number}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">Whatsapp Number</label>
                  <input type="text" class="form-control" placeholder="Whatsapp Number" name="s_user_wp_number" value="{{$user->s_user_wp_number}}">
                </div>
              </div><!-- Col -->
            </div><!-- Row -->

            <button type="submit" class="btn btn-primary submit">Update</button>
          </form>          
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush