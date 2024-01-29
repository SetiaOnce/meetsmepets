<!--begin::Header-->
<div id="kt_app_header" class="app-header  align-items-stretch ">
    <div class="app-container container d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
        <div class="d-flex align-items-center justify-content-between flex-row-fluid" id="kt_app_header_wrapper">
            <!--begin::Header logo-->
            <div class="app-header-logo d-flex align-items-center">
                <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
                    <div class="btn btn-sm btn-icon btn-bg-light" id="kt_app_header_menu_toggle">
                        <i class="ki-outline ki-abstract-14 fs-2"></i>
                    </div>
                </div>
                <!--begin::Logo image-->
                <a href="{{ url('/') }}" class="me-5" id="headerLogo">
                    <svg class="bd-placeholder-img rounded w-50 h-20px h-lg-30px theme-light-show" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#868e96"></rect>
                    </svg>
                    <svg class="bd-placeholder-img rounded w-50 h-20px h-lg-30px theme-dark-show" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#868e96"></rect>
                    </svg>
                </a>
                <!--end::Logo image-->
            </div>
            <!--end::Header logo-->
            @include('frontend.partials.menus')
            @include('frontend.partials.navbar')
        </div>
        <!--end::Header-->
    </div>
    <!--end::Header container-->
</div>
<!--end::Header-->