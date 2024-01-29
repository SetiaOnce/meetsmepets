<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<!-- Stylesheets -->
<link rel="stylesheet" class="main-css" type="text/css" href="{{ asset('dist/assets/css/style.css') }}">
<!-- Base route -->
<!--begin::Vendor Stylesheets(used for this page only)-->
@if (isset($data['css']))
    @foreach ($data['css'] as $dt)
        @php $uri = asset($dt); @endphp
        @if(str_contains($dt, 'https://'))
            @php $uri = $dt; @endphp
        @endif
        <link rel="stylesheet" href="{{ $uri }}">
    @endforeach
@endif
<!--end::Vendor Stylesheets-->
<script>
    const base_url = "{{url('/')}}/";
</script>
@yield('css')