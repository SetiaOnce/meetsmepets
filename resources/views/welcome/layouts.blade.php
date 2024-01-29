<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="robots" content="index, follow, noodp, noydir" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>
        {{ isset($data['title']) ? $data['title'] : 'Unknown' }} - {{ $data['app_name'] }}
    </title>
    <meta name="author" content="@YogaSetiawan" />
    <meta name="email" content="yogasetiawan1126@gmail.com" />
    <meta name="website" content="{{ $data['url'] }}" />
    <meta name="Version" content="{{ $data['app_version'] }}" />
    <meta name="docsearch:language" content="id">
    <meta name="docsearch:version" content="{{ $data['app_version'] }}" />
    <link rel="canonical" href="{{ $data['url'] }}" />
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('dist/img/site-img/meetsme-logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/dist/img/favicon/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/dist/img/favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/dist/img/favicon/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('/dist/img/favicon/site.webmanifest') }}" />
    <link rel="mask-icon" href="{{ asset('/dist/img/favicon/safari-pinned-tab.svg') }}" color="#26296e" />
    <meta name="msapplication-TileColor" content="#061824" />
    <meta name="theme-color" content="#26296e" />
    <meta name="application-name" content="{{ $data['app_name'] }}" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="HandheldFriendly" content="true" />
    @include('welcome.partials.style')
</head>   
@if (isset($bgGradient) && $bgGradient == 'Y')    
<body class="primary-gradient" data-theme-color="color-primary-2">
@else
<body class="bg-white">
@endif
    <div class="page-wrapper">
        @include('welcome.partials.loader')
        @yield('content')
    </div>
@include('welcome.partials.script')
</body>
</html>