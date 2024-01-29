<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar  py-7 pt-lg-15 pb-lg-5" 
    @if(Route::is('home'))
        style="display: none;"
    @endif
>
    <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex align-items-stretch ">
        <div class="app-toolbar-container d-flex flex-column flex-row-fluid">
            <div class="page-title gap-4 me-3 mb-3 mb-lg-5">
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 mb-2">
                    <li class="breadcrumb-item text-gray-600 fw-bold lh-1">
                        <a href="{{ url('/') }}" class="text-gray-700 text-hover-primary me-1">
                            <i class="ki-outline ki-home text-gray-700 fs-6"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                    </li>
                    <li class="breadcrumb-item text-gray-600 fw-bold lh-1">{{ $activeSubMenu ? $activeSubMenu : $activeMenu; }}</li>
                </ul>
                <h1 class="text-gray-900 fw-bolder m-0">{{ isset($data['title']) ? $data['title'] : 'Unknown'; }}</h1>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->