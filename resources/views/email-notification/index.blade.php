@extends('layout.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/simplemde/simplemde.min.css') }}" rel="stylesheet" />

@endpush
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('society')}}">Society</a></li>
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
                <form method="POST" action="{{route('email-notification.send')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Email <span class="text-danger">*</span></label>
                                <select class="js-example-basic-multiple w-100" multiple="multiple" name="emails[]">
                                    @foreach($emails as $email)
                                    <option value="{{$email}}">{{$email}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Subject" name="subject"
                                    value="{{old('subject')}}" required>
                            </div>
                        </div><!-- Col -->

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Notice <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="notice" id="tinymceExample" rows="10"></textarea>
                            </div>
                        </div><!-- Col -->
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>File upload</label>
                                <input type="file" name="file[]" class="file-upload-default" multiple>
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled=""
                                        placeholder="Upload Image">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->
                    <button type="submit" class="btn btn-primary submit">Subimt</button>
                </form>
            </div>
        </div>
    </div>
</div>
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