@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/simplemde/simplemde.min.css') }}" rel="stylesheet" />

@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('role-permission')}}">Role Permission</a></li>
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
          <form method="POST" action="{{route('role-permission.update', $role_permission->id)}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Roles <span class="text-danger">*</span></label>
                        <select class="form-control" id="exampleFormControlSelect1" name="role_id" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}" <?php if($role->id == $role_permission->role_id) echo 'selected'; ?>>{{$role->role_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!-- Col -->

                @foreach($pmenus as $pmenu)
                    <?php 
                        $smenu = App\Menus::where('pmenu', $pmenu->mid)->where('mid', '!=', $pmenu->mid)->get(); 
                        $pids = explode(',', $role_permission->permission_id);
                    ?>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="pid[]" class="form-check-input" <?php if(in_array($pmenu->mid, $pids)) echo 'checked'; ?> value="{{$pmenu->mid}}">
                                        {{$pmenu->mname}}
                                </label>
                            </div>
                        </div>
                        @if(!empty($smenu))
                            <div class="row ml-3">
                                @foreach($smenu as $sm)
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="pid[]" class="form-check-input" <?php if(in_array($sm->mid, $pids)) echo 'checked'; ?> value="{{$sm->mid}}">
                                                        {{$sm->mname}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div><!-- Col -->
                @endforeach
            </div><!-- Row -->
            <button type="submit" class="btn btn-primary submit">Subimt</button>
          </form>          
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
        $('#society_name_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
        $('#start_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
        $('#end_date').datepicker({
            format: "dd-mm-yyyy",
            orientation: "left bottom",
        });
    });
</script>
@endsection
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/simplemde/simplemde.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/ace-builds/ace.js') }}"></script>
  <script src="{{ asset('assets/plugins/ace-builds/theme-chaos.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>
  <script src="{{ asset('assets/js/tinymce.js') }}"></script>
  <script src="{{ asset('assets/js/simplemde.js') }}"></script>
  <script src="{{ asset('assets/js/ace.js') }}"></script>
@endpush