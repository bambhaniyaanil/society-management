@extends('layout.master')

@section('content')
<style>
    .profile-page .profile-header .cover .cover-body
    {
        bottom: 20px !important;
    }
</style>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
  </ol>
</nav>
<div class="profile-page tx-13">
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="profile-header">
                <div class="cover">
                <div class="gray-shade"></div>
                <figure>
                    <img src="{{ url('https://via.placeholder.com/1148x272') }}" class="img-fluid" alt="profile cover">
                </figure>
                <div class="cover-body d-flex justify-content-between align-items-center">
                    <div>
                    @if($user->user_photo != '' || $user->user_photo != null)
                        <img class="profile-pic" src="{{URL::asset('profile_images/'.$user->user_photo)}}">
                    @else
                        <img class="profile-pic" src="{{ url('https://via.placeholder.com/100x100') }}" alt="profile">
                    @endif
                    <span class="profile-name">{{$user->s_user_name}}</span>
                    </div>
                    <div class="d-none d-md-block">
                    <a href="{{ url('/edit_profile') }}" class="btn btn-primary btn-icon-text btn-edit-profile">
                        <i data-feather="edit" class="btn-icon-prepend"></i> Edit profile
                    </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Socity</label>
                    <p class="text-muted">{{$user->socity->society_name}}</p>
                </div>
            </div><!-- Col -->
            <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">User Name</label>
                <p class="text-muted">{{$user->s_user_name}}</p>
            </div>
            </div><!-- Col -->
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <p class="text-muted">{{$user->s_user_email}}</p>
                </div>
            </div><!-- Col -->
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Mobile</label>
                    <p class="text-muted">{{$user->s_user_mobile_number}}</p>
                </div>
            </div><!-- Col -->
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Whatsapp Number</label>
                    <p class="text-muted">{{$user->s_user_wp_number}}</p>
                </div>
            </div><!-- Col -->
        </div><!-- Row --> 
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush