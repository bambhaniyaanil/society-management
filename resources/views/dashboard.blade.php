@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
  </div>
</div>

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body bg-gradient-danger rounded">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0 text-white font-weight-bold">INCOME</h6>
            </div>
            <div class="row">
              <div class="col-12 col-md-12 col-xl-12 text-white">
                <h3 class="mb-2">{{number_format($income, 2)}}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body bg-gradient-primary rounded">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0 text-white font-weight-bold">EXPENSE</h6>
            </div>
            <div class="row">
              <div class="col-12 col-md-12 col-xl-12 text-white">
                <h3 class="mb-2">{{ number_format($expenses, 2) }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body bg-gradient-success rounded">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0 text-white font-weight-bold">PROFIT/LOSS</h6>
            </div>
            <div class="row">
              <div class="col-12 col-md-12 col-xl-12 text-white">
                <h3 class="mb-2">{{ number_format($profit_loass, 2) }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body bg-gradient-warning rounded">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0 text-white font-weight-bold">DUES</h6>
            </div>
            <div class="row">
              <div class="col-12 col-md-12 col-xl-12">
                <h3 class="mb-2 text-white">{{ number_format($dues_amount, 2) }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body bg-gradient-info rounded">
                <div class="d-flex justify-content-between align-items-baseline">
                    <h6 class="card-title mb-0 text-white font-weight-bold">Advance</h6>
                </div>
                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 text-white">
                        <h3 class="mb-2">{{ number_format($advance_amount, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
  </div>
</div> <!-- row -->

<script>
    $(document).ready(function() {
        var msg = '{{$msg}}';
        var status = '{{Session::get("package-msg")}}';
        if(status == 1 && msg != '')
        {
          Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: msg,
            showConfirmButton: true,
          })
          <?php Session::put('package-msg', '0') ?>
        }
    });
</script>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/promise-polyfill/polyfill.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="{{ asset('assets/js/datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
@endpush