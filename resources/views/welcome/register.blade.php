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
            <div class="account-area mb-3">
                <a href="{{ url('/') }}" class="back-btn dz-flex-box">
                    <i class="icon feather icon-chevron-left"></i>
                </a>	
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-pencil me-2 align-middle"></i>Register New Account</h5>
                    </div>
                    <div class="card-body">
                        <form class="form" id="form-first">
                            <div class="mb-2 input-group input-group-icon">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Full Name">								
                            </div>
                            <div class="mb-2 input-group input-group-icon">
                                <span class="input-group-text">
                                    <div class="input-icon">
                                        <i class="icon feather icon-user"></i>
                                    </div>
                                </span>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username">								
                            </div>
                            <div class="mb-2 input-group input-group-icon">
                                <span class="input-group-text">
                                    <div class="input-icon">
                                        <i class="icon feather icon-mail"></i>
                                    </div>
                                </span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
                            </div>
                            <div class="input-group input-group-icon">
                                <span class="input-group-text">
                                    <div class="input-icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                </span>
                                <input type="email" class="form-control" name="phone_number" id="phone_number" placeholder="Enter Phone Number">
                            </div>
                            <div class="radio style-2">
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <label class="radio-label">
                                            <input type="radio" name="gender" value="WOMEN">
                                            <span class="checkmark">						
                                                <span class="text">Women</span>
                                                <span class="check"></span>							
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-label">
                                            <input type="radio" name="gender" value="MEN">
                                            <span class="checkmark">
                                                <span class="text">Men</span>
                                                <span class="check"></span>							
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-label">
                                            <input type="radio" name="gender" checked value="OTHER">
                                            <span class="checkmark">
                                                <span class="text">Other</span>	
                                                <span class="check"></span>							
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content End -->
    <!-- Footer -->
    <div class="footer fixed bg-white">
        <div class="container">
            <a href="javascript:void(0);" id="btn-register1" class="btn btn-lg btn-gradient w-100 dz-flex-box btn-shadow rounded-xl">Next</a>
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
                    <h3>SetUp Your Pin</h3>
                </div>
                <form class="form" id="form-second">
                    <div id="otp" class="digit-group input-mini">
                        <input class="form-control" type="password" id="digit_2" name="digit_2" data-next="digit_3" data-previous="digit-1">
                        <input class="form-control" type="password" id="digit_3" name="digit_3" data-next="digit_4" data-previous="digit_2">
                        <input class="form-control" type="password" id="digit_4" name="digit_4" data-next="digit_5" data-previous="digit_3">
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
            <a href="javascript:void(0);" id="btn-register2" class="btn btn-lg btn-gradient w-100 dz-flex-box btn-shadow rounded-xl">Next</a>
        </div>
    </div>
    <!-- Footer -->
</div>
<!-- Second Step End-->
<!-- Third Step -->
<div class="third-step" id="thirdStep" style="display: none;">
    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
            <div class="account-area">
                <a href="javascript:void(0);" onclick="_closeThirStep()" class="back-btn dz-flex-box">
                    <i class="icon feather icon-chevron-left"></i>
                </a>
                <div class="section-head ps-0">
                    <h3>Confirm Your Pin</h3>
                </div>
                <form class="form" id="form-third">
                    <input type="hidden" name="hideName">
                    <input type="hidden" name="hideUsername">
                    <input type="hidden" name="hideMail">
                    <input type="hidden" name="hidePhoneNumber">
                    <input type="hidden" name="hideGener">
                    <div id="otp" class="digit-group input-mini">
                        <input class="form-control" type="password" id="confirm_2" name="confirm_2" data-next="confirm_3" data-previous="confirm-1">
                        <input class="form-control" type="password" id="confirm_3" name="confirm_3" data-next="confirm_4" data-previous="confirm_2">
                        <input class="form-control" type="password" id="confirm_4" name="confirm_4" data-next="confirm_5" data-previous="confirm_3">
                        <input class="form-control" type="password" id="confirm_5" name="confirm_5" data-next="digit_6" data-previous="confirm_4">
                    </div> 
                </form>
            </div>
        </div>
    </div>
    <!-- Page Content End -->
    <!-- Footer -->
    <div class="footer fixed bg-white">
        <div class="container">
            <a href="javascript:void(0);" id="btn-register3" class="btn btn-lg btn-gradient w-100 dz-flex-box btn-shadow rounded-xl">Next</a>
        </div>
    </div>
    <!-- Footer -->
</div>
<!-- Third Step End-->
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
<script src="{{ asset('scripts/welcome/register.init.js') }}"></script>
@stop
@endsection