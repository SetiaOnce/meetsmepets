<!-- Sidebar -->
<div class="dark-overlay"></div>
<div class="sidebar" id="appSideMenu">
    <div class="inner-sidebar">
        <a href="{{ url('profile') }}" class="author-box">
            <div class="dz-media">
                <svg class="bd-placeholder-img rounded w-100 h-275px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg>
            </div>
            <!--begin::Detail User-->
            <div class="d-flex flex-row flex-column w-100">
                <!--begin::Row-->
                <div class="row">
                    <h5 class="placeholder-glow w-100 d-flex flex-row flex-column">
                        <span class="placeholder col-lg-10 rounded mb-1"></span>
                        <span class="placeholder col-lg-8 rounded"></span>
                    </h5>
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Detail User-->
        </a>

        <ul class="nav navbar-nav">	
            <li>
                <a class="nav-link  {{ strtolower($activeMenu) == strtolower('HOME') ? 'active' : '' }}" href="home.html">
                    <span class="dz-icon">
                        <i class="icon feather icon-home"></i>
                    </span>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a class="nav-link  {{ strtolower($activeMenu) == strtolower('LOVE') ? 'active' : '' }}" href="../package.html">
                    <span class="dz-icon">
                        <i class="icon feather icon-heart-on"></i>
                    </span>
                    <span>Love</span>
                </a>
            </li>
            <li>
                <a class="nav-link  {{ strtolower($activeMenu) == strtolower('EXPLORE') ? 'active' : '' }}" href="../package.html">
                    <span class="dz-icon">
                        <i class="icon feather icon-grid"></i>
                    </span>
                    <span>Explore</span>
                </a>
            </li>
            <li>
                <a class="nav-link  {{ strtolower($activeMenu) == strtolower('CHAT') ? 'active' : '' }}" href="../package.html">
                    <span class="dz-icon">
                        <i class="fa fa-comments"></i>
                    </span>
                    <span>Chat</span>
                </a>
            </li>
            <li>
                <a class="nav-link  {{ strtolower($activeMenu) == strtolower('PROFILE') ? 'active' : '' }}" href="{{ url('/profile') }}">
                    <span class="dz-icon">
                        <i class="icon feather icon-user"></i>
                    </span>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a class="nav-link" href="{{ url('/logout') }}">
                    <span class="dz-icon">
                        <i class="icon feather icon-log-out"></i>
                    </span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-bottom">
            <ul class="app-setting">
                <li>
                    <div class="mode">
                        <span class="dz-icon">                        
                            <i class="icon feather icon-moon"></i>
                        </span>					
                        <span>Dark Mode</span>
                        <div class="custom-switch">
                            <input type="checkbox" class="switch-input theme-btn" id="toggle-dark-menu">
                            <label class="custom-switch-label" for="toggle-dark-menu"></label>
                        </div>					
                    </div>
                </li>
            </ul>
            <div class="app-info">
                <h3 class="placeholder-glow">
                    <span class="placeholder rounded col-12"></span>
                </h3>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar End -->