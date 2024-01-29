@extends('welcome.layouts')
@section('content')
@section('css')
<!-- Globle Stylesheets -->
<link href="{{ asset('dist/') }}/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<!-- Stylesheets -->
<link rel="stylesheet" class="main-css" type="text/css" href="{{ asset('dist/assets/css/style.css') }}">
<link rel="stylesheet" class="main-css" type="text/css" href="{{ asset('dist/plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" class="main-css" type="text/css" href="{{ asset('dist/plugins/sweetalert2/sweetalert2.min.css') }}">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
@stop
<!-- First Step -->
<div class="first-step" id="firstStep">
    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
            <div class="account-area">
                <a href="{{ url('/') }}" class="back-btn dz-flex-box">
                    <i class="icon feather icon-chevron-left"></i>
                </a>
                <div class="section-head ps-0">
                    <h3>Please Enter your Email or Phone Number</h3>
                </div>
                <form class="row" id="form-first">
                    <div class="input-group dz-select">
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email or Phone Number">
                    </div>
                </form>		
            </div>
        </div>
    </div>
    <!-- Page Content End -->
    <!-- Footer -->
    <div class="footer fixed bg-white">
        <div class="container">
            <a href="javascript:void(0);" id="btn-login1" class="btn btn-lg btn-gradient w-100 dz-flex-box btn-shadow rounded-xl">Next</a>
        </div>
    </div>
    <!-- Footer -->
</div>
<!-- First Step End-->
<!-- Second Step -->
<div class="second-step" id="secondStep" style="display: none;">
    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
            <div class="account-area">
                <a href="javascript:void(0);" onclick="_closeSecondStep()" class="back-btn dz-flex-box">
                    <i class="icon feather icon-chevron-left"></i>
                </a>
                <div class="section-head ps-0">
                    <h3>Enter Your Pin</h3>
                </div>
                <form class="form" id="form-second">
                    <input type="hidden" name="hideMail">
                    <div id="otp" class="digit-group input-mini">
                        <input class="form-control" type="password" id="digit_2" name="digit_2" data-next="digit_3" data-previous="digit-1">
                        <input class="form-control" type="password" id="digit_3" name="digit_3" data-next="digit_4" data-previous="digit_2">
                        <input class="form-control" type="password" id="digit_4" name="digit_4" data-next="digit_5" data-previous="digit-3">
                        <input class="form-control" type="password" id="digit_5" name="digit_5" data-next="digit-6" data-previous="digit_4">
                    </div> 
                </form>
            </div>
        </div>
    </div>
    <!-- Page Content End -->
    <!-- Footer -->
    <div class="footer fixed bg-white">
        <div class="container">
            <a href="javascript:void(0);" id="btn-login2" class="btn btn-lg btn-gradient w-100 dz-flex-box btn-shadow rounded-xl">Next</a>
        </div>
    </div>
    <!-- Footer -->
</div>
<!-- Second Step End-->
@section('js')
<script src="{{ asset('dist/assets/js/jquery.js') }}"></script>
<script src="{{ asset('dist/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('dist/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('dist/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('dist/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/dz.carousel.js') }}"></script>
<script src="{{ asset('dist/assets/js/settings.js') }}"></script>
<script src="{{ asset('dist/assets/js/custom.js') }}"></script>
<script src="{{ asset('scripts/welcome/login.init.js') }}"></script>
@stop
@endsection