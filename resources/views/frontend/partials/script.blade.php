<!--   Core JS Files   -->
<script src="{{ asset('dist/frontend/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('dist/frontend/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('dist/frontend/js/plugins/perfect-scrollbar.min.js') }}"></script>
<!--  Plugin for TypedJS, full documentation here: https://github.com/inorganik/CountUp.js -->
<script src="{{ asset('dist/frontend/js/plugins/countup.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/plugins/prism.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/plugins/highlight.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/plugins/rellax.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/plugins/tilt.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
<!-- jquery -->
<script src="{{ asset('dist/plugins/jquery/jquery.min.js') }}"></script>
<!-- bootstrap selectpicker -->
<script src="{{ asset('dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('dist/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/material-kit.min.js?v=3.0.4') }}" type="text/javascript"></script>
<link rel="stylesheet" href="{{ asset('dist/plugins/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('scripts/frontend/site_info.init.js') }}" type="text/javascript"></script>
@yield('js')