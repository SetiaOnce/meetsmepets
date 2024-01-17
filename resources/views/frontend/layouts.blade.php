<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('dist/img/site-img/logo-direktorat.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('dist/img/site-img/logo-direktorat.png') }}">
  <title>Sistem Informasi Sarana Perkeretaapian</title>
  <!-- Nucleo Icons -->
  <link href="{{ asset('dist/frontend/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('dist/frontend/css/nucleo-svg.css') }}" rel="stylesheet" />
  @include('frontend.partials.style')
</head>
<body class="index-page bg-gray-200">
  <!-- begin: header -->
  @include('frontend.partials.header')
  <!-- end: header -->
  <!-- begin: card body -->
  <div class="card card-body  mx-3 mx-md-4 mt-n6">
    @yield('content')
  </div>
  <!-- end: card body -->
  <!-- begin: footer -->
  @include('frontend.partials.footer')
  <!-- end: footer -->
  @include('frontend.partials.script')
</body>
</html>
