@extends('welcome.layouts', ['bgGradient' => 'Y'])
@section('content')
@section('css')
<!-- Stylesheets -->
<link rel="stylesheet" class="main-css" type="text/css" href="{{ asset('dist/assets/css/style.css') }}">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
@stop
<!-- Welcome Start -->
<div class="content-body">
    <div class="welcome-area">
        <div class="welcome-inner flex-column">
            <div class="logo-area">
                <img class="logo" src="{{ asset('dist/img/site-img/'.$data['siteInfo']['login_logo']) }}" alt="">
                <p class="para-title">{{ $data['siteInfo']['login_desc'] }}</p>
            </div>
            <div class="social-area">
                <a href="{{ url('auth') }}" class="btn btn-icon icon-start w-100 btn-facebook">
                    <i class="fa fa-sign-in"></i>
                    <span>Log In</span>
                </a>
                <a href="{{ url('register') }}" class="btn btn-icon icon-start w-100 btn-tp">
                    <i class="fa fa-pencil text-white"></i>
                    <span>Register</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Welcome End -->
@section('js')
<script src="{{ asset('dist/assets/js/settings.js') }}"></script>
<script src="{{ asset('dist/assets/js/custom.js') }}"></script>
@stop
@endsection