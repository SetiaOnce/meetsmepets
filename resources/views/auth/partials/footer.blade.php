<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer d-flex flex-column py-5">
    <div class="container">
        {{-- <div class="d-flex flex-column flex-lg-row flex-stack"> --}}
        <div class="d-flex flex-column flex-lg-row flex-center">
            <div class="me-0 lg-md-10">
                <div class="text-white text-center" id="copyRight">
                    <h3 class="placeholder-glow">
                        <span class="placeholder rounded col-4"></span>
                    </h3>
                </div>
            </div>
            {{-- <div class="d-flex fw-semibold text-primary fs-base gap-5 justify-content-center">
                @php
                    $menus = footerAuthMenus();
                @endphp
                @foreach ($menus["menus"] as $menu)
                    <a href="{{ url($menu['route_name']) }}" class="text-white text-hover-primary">{{ $menu['menu'] }}</a>
                @endforeach
            </div> --}}
        </div>
    </div>
</div>
<!--end::Footer-->