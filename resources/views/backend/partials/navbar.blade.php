<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0">
    <!--begin::Web View on Public-->
    <div class="app-navbar-item ms-1 ms-md-4">
        <div class="d-flex align-items-stretch">
            <div class="d-flex align-items-center">
                <a href="{{ url('/') }}" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="Lihat halaman publik" target="_blank">
                    <i class="ki-duotone ki-exit-right-corner fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
            </div>
        </div>
    </div>
    <!--end::Web View on Public-->
    <!--begin::Theme mode-->
    <div class="app-navbar-item ms-1 ms-md-4">
        <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
            data-kt-menu-trigger="{default:'click', lg: 'hover'}"
            data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            <i class="ki-outline ki-night-day theme-light-show fs-1"></i>
            <i class="ki-outline ki-moon theme-dark-show fs-1"></i>
        </div>
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
            data-kt-menu="true" data-kt-element="theme-mode-menu">
            <div class="menu-item px-3 my-0">
                <a href="javascript:void(0);" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-outline ki-night-day fs-2"></i>
                    </span>
                    <span class="menu-title">
                        Light
                    </span>
                </a>
            </div>
            <div class="menu-item px-3 my-0">
                <a href="javascript:void(0);" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-outline ki-moon fs-2"></i>
                    </span>
                    <span class="menu-title">
                        Dark
                    </span>
                </a>
            </div>
            <div class="menu-item px-3 my-0">
                <a href="javascript:void(0);" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-outline ki-screen fs-2"></i>
                    </span>
                    <span class="menu-title">
                        System
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!--end::Theme mode-->
    <!--begin::User menu-->
    <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <div class="cursor-pointer symbol symbol-35px avatar-header"
            data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
            data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            <svg class="bd-placeholder-img rounded-3 w-35px h-35px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="#868e96"></rect>
            </svg>
        </div>
        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
            data-kt-menu="true">
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <div class="symbol symbol-50px me-5 avatar-header">
                        <svg class="bd-placeholder-img rounded-3 w-50px h-50px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <rect width="100%" height="100%" fill="#868e96"></rect>
                        </svg>
                    </div>
                    <div class="d-flex flex-column w-100" id="navbarUserInfo">
                        <h5 class="placeholder-glow">
                            <span class="placeholder rounded col-10"></span>
                            <span class="placeholder rounded col-8"></span>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="separator my-2"></div>
            <!--begin::Menu item-->
            <div class="menu-item px-5">
                <a href="{{ url('app_admin/'.$data['user_session']->username) }}" class="menu-link px-5">
                    <i class="bi bi-person-gear fs-3 me-3"></i> My Profile
                </a>
            </div>
            <!--end::Menu item-->
            <div class="menu-item px-5">
                <a href="{{ url('auth/logout') }}" class="menu-link px-5 text-hover-danger">
                    <i class="bi bi-power fs-3 me-3"></i>Sign Out
                </a>
            </div>
        </div>
        <!--end::User account menu-->
    </div>
    <!--end::User menu-->
</div>
<!--end::Navbar-->