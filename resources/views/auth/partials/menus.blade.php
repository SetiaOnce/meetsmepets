<!--begin::Menu wrapper-->
<div class="d-flex align-items-stretch me-auto" id="kt_app_header_menu_wrapper">
    <!--begin::Menu holder-->
    <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
        data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
        data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
        data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
        data-kt-swapper-mode="prepend"
        data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_menu_wrapper'}">
        <!--begin::Menu-->
        <div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-600 menu-state-primary menu-arrow-gray-400 fw-semibold fw-semibold fs-6 align-items-stretch my-5 my-lg-0 px-2 px-lg-0"
            id="#kt_app_header_menu"
            data-kt-menu="true">
            @php
                $menus = publicMenus();
            @endphp
            @foreach ($menus["menus"] as $menu)
                @if (isset($menu['children']) AND $menu['has_child'] == 'Y' )
                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                        data-kt-menu-offset="12,0"
                        class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                        <span class="menu-link {{ $activeMenu == $menu['menu'] ? 'active' : '' }}">
                            <span class="menu-icon"><i class="{{ $menu['icon'] }} fs-3"></i></span>
                            <span class="menu-title">{{ $menu['menu'] }}</span>
                            <span class="menu-arrow d-lg-none"></span>
                        </span>
                        @foreach ($menu['children']["submenu"] as $child)
                            @php $uri = url($child['route_name']); @endphp
                            @if(str_contains($child['route_name'], '#'))
                                @php $uri = $child['route_name']; @endphp
                            @endif
                            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeMenu == $menu['menu'] || $activeSubMenu == $child['sub_menu'] ? 'active' : '' }}" href="{{ $uri }}">
                                        <span class="menu-icon">
                                            <i class="{{ $child['icon'] }} fs-2"></i>
                                        </span>
                                        <span class="menu-title">{{ $child['sub_menu'] }}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    @php $uri = url($menu['route_name']); @endphp
                    @if(str_contains($menu['route_name'], '#'))
                        @php $uri = $menu['route_name']; @endphp
                    @endif
                    <div class="menu-item menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
                        <a class="menu-link {{ $activeMenu == $menu['menu'] ? 'active' : '' }}" href="{{ $uri }}">
                            <span class="menu-icon">
                                <i class="{{ $menu['icon'] }} fs-3"></i>
                            </span>
                            <span class="menu-title">{{ $menu['menu'] }}</span>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu holder-->
</div>
<!--end::Menu wrapper-->
