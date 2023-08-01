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
            </div>
              <form class="forms-sample" method="POST" action="{{ route('search-receipts') }}">
              {{ csrf_field() }}
                <div class="form-group">
                  <label for="control-label">Select Your Society</label>
                    <select class="form-control w-100" name="society" required>
                        <option value="">Select Your Society</option>
                        @foreach($societys as $society)
                            <option value="{{$society->id}}" <?php if(old('society') == $society->id) echo 'selected'; ?>>{{$society->society_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                  <label for="control-label">Name</label>
                  <input type="text" class="form-control" placeholder="Enter Your Name" name="name" required>  
                </div>
                <div class="form-group">
                  <label for="control-label">Wing-Unit-No</label>
                  <input type="text" class="form-control" placeholder="Enter Your Wing-Unit-No" name="wing_flat_no" required>  
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0">
                      {{ __('Search') }}
                  </button>                  
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                <li>{{ $message }}</li>
                            </ul>
                        </div>
                    @endif 
                </div>
                <!-- <a href="{{ url('/register') }}" class="d-block mt-3 text-muted">Not a user? Sign up</a> -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection