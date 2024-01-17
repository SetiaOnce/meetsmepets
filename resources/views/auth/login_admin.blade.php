<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('dist/img/site-img/logo-direktorat.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('dist/img/site-img/logo-direktorat.png') }}">
    <title>Login Aplikasi - Sistem Informasi Sarana Perkeretaapian</title>
    <!-- CSS Files -->
    <link href="{{ asset('dist/plugins/global/plugins.bundle.css') }}">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- bootstrap selectpicker -->
    <link rel="stylesheet" href="{{ asset('dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/plugins/select2/css/select2.min.css') }}">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700">
    <!-- Custom plugins -->
    <link id="pagestyle" href="{{ asset('dist/frontend/css/material-kit.css?v=3.0.4') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('dist/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Base route -->
    <script>var BASE_URL = "{{url('/')}}";</script> 
</head>
<body>
    <div class="page-header align-items-start min-vh-100" id="bg-login" loading="lazy">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-white shadow-dark border-radius-lg py-1 pe-1 text-center" id="hLogo-login">
                                <h5 class="placeholder-glow">
                                    <span class="placeholder col-10 rounded" style="height: 52px;"></span>
                                    <span class="placeholder col-8 rounded"></span>
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form role="form" id="dt-formLogin" class="text-start" onsubmit="return false">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Email/NIK</label>
                                    <input type="text" name="email_nik" id="email_nik" class="form-control">
                                </div>
                                <div class="input-group input-group-outline  mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="form-check form-switch d-flex align-items-center" id="fg-showHidePassword">
                                    <input class="form-check-input" type="checkbox" id="show_hide_password">
                                    <label class="form-check-label mb-0 ms-3" for="show_hide_password">Password Disembunyikan</label>
                                </div>
                                <div class="text-center">
                                    <h5 class="placeholder-glow my-4 mb-2" id="loader-action">
                                        <span class="placeholder col-12 rounded" style="height: 40px;"></span>
                                    </h5>
                                    <button type="button" class="btn bg-gradient-info w-100 my-4 mb-2" id="btn-login-submit" style="display: none;"><i class="fas fa-sign-in-alt me-1"></i>Log in</button>
                                </div>
                            </form>
                            <div class="d-flex justify-content-between btnBottom">
                                <span class="placeholder col-5 rounded"></span>
                                <span class="placeholder col-5 rounded"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer position-absolute bottom-2 py-2 w-100" id="kt_footer">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 col-md-6 my-auto">
                        <div class="copyright text-center text-sm text-white copyRight">
                            <span class="placeholder col-8 rounded" style="height: 18px"></span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
  
    <!--   Core JS Files   -->
    <script src="{{ asset('dist/frontend/js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dist/frontend/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dist/frontend/js/plugins/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('dist/frontend/js/plugins/countup.min.js') }}"></script>
    <script src="{{ asset('dist/frontend/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('dist/frontend/js/plugins/prism.min.js') }}"></script>
    <script src="{{ asset('dist/frontend/js/plugins/highlight.min.js') }}"></script>
    <script src="{{ asset('dist/frontend/js/plugins/rellax.min.js') }}"></script>
    <script src="{{ asset('dist/frontend/js/plugins/tilt.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
    <!-- jquery -->
    <script src="{{ asset('dist/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/global/plugins.bundle.js') }}"></script>
    <!-- bootstrap selectpicker -->
    <script src="{{ asset('dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('dist/frontend/js/material-kit.min.js?v=3.0.4') }}" type="text/javascript"></script>
    <!-- custom javascript-->
    <script src="{{ asset('dist/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('scripts/auth/login_admin.js') }}"></script>
</body>
</html>