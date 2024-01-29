<!-- Header -->
@if (strtolower($activeMenu) == strtolower('HOME'))    
<header class="header header-fixed border-0" id="appHeader">
    <div class="container">
        <div class="header-content">
            <div class="left-content">
                <a href="javascript:void(0);" class="menu-toggler">
                    <i class="icon feather icon-grid"></i>
                </a>
            </div>
            <div class="mid-content">
                {{-- <svg class="bd-placeholder-img rounded p-1" style="height: 50px; width:50px;"xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg> --}}
            </div>
            <div class="right-content header-logo">
                <svg class="bd-placeholder-img rounded p-1" style="height: 50px; width:50px;"xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg>
            </div>
        </div>
    </div>
</header>
@else
<header class="header header-fixed bg-white border-0" id="appHeader">
    <div class="container">
        <div class="header-content">
            <div class="left-content me-3">
                <a href="javascript:history.back()" class="back-btn">
                    <i class="icon feather icon-chevron-left"></i>
                </a>
                <h6 class="title">{{ isset($data['title']) ? $data['title'] : 'Unknown' }}</h6>
            </div>
            <div class="mid-content">
            </div>
            <div class="right-content header-logo">
                <svg class="bd-placeholder-img rounded p-1" style="height: 50px; width:50px;"xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg>
            </div>
        </div>
    </div>
</header>
@endif

<!-- Header -->