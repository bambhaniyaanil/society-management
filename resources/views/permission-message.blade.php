@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-12 pr-md-0">
            <div class="auth-left-wrapper text-center">
                <img width="100%" src="{{ URL::asset('assets/images/logo.png')}}" class="img-fluid" />
            </div>
          </div>
          <div class="col-md-12 pl-md-0">
            <div class="auth-form-wrapper px-4 py-5">
                <div class="text-center">
                    <h4>your permission not access so please contact to admin</h4>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection