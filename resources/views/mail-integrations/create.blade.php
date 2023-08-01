@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('mail-integrations')}}">Mail Integrations</a></li>
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
          <form method="POST" action="{{url('mail-integrations/add')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">SMTP User Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="SMTP User Name" name="smtp_user_name" value="{{old('smtp_user_name')}}" required>
                    </div>
                </div><!-- Col -->  
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">SMTP Password <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="SMTP Password" name="smtp_password" value="{{old('smtp_password')}}"  required>
                    </div>
                </div><!-- Col --> 

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">SMTP Host <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="SMTP Host" name="smtp_host" value="{{old('smtp_host')}}"  required>
                    </div>
                </div><!-- Col -->
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">SMTP Port <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="SMTP Port" name="smtp_port" value="{{old('smtp_port')}}" required>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Use SMTP Simple <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Use SMTP Simple" name="use_smtp_simple" value="{{old('use_smtp_simple')}}"  required>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">From Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="From Email" name="from_email" value="{{old('from_email')}}" required>
                    </div>
                </div><!-- Col -->

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Replay Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Replay Email" name="replay_email" value="{{old('replay_email')}}" required>
                    </div>
                </div><!-- Col -->
                <div class="col-sm-12 mb-3">
                  <h4>Note</h4>
                  <p class="ml-3">1).kindly check in <a href="https://myaccount.google.com/lesssecureapps" target="_blanck">less security</a> is on. if less security is off please less security on</p>
                  <p class="ml-3">2).kindly check in <a href="https://accounts.google.com/signin/v2/challenge/pwd?continue=https%3A%2F%2Fmyaccount.google.com%2Fsigninoptions%2Ftwo-step-verification&service=accountsettings&osid=1&rart=ANgoxcegFx98RvGt_NECGYmvu61s5pjEXsb5hpudVJFRF449LSzGVRdq3Lf_AT9VszfnT0Kpy3npuY13fiw295ZEDfhB32u7UA&TL=AM3QAYYBft5CplevsG284pQThZd_8nKMRqjR-M-zwHKxTjrviY2iKFB-n5pvH78q&flowName=GlifWebSignIn&cid=1&flowEntry=ServiceLogin" target="_blanck">2-Step Verification</a> is off. if 2-Step Verification is on please 2-Step Verification off</p>
                </div>
            </div><!-- Row -->
            <button type="submit" class="btn btn-primary submit">Subimt</button>
          </form>          
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
        $('#flat_rental_start_date').datepicker({
            format: "dd-mm-yyyy"
        });

        $('#flat_rental_end_date').datepicker({
            format: "dd-mm-yyyy"
        });
    });
</script>
@endsection
@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
  <script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
@endpush