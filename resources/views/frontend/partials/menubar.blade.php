<!-- Menubar -->
<div class="menubar-area style-2 footer-fixed">
    <div class="toolbar-inner menubar-nav">
        <a href="{{ url('profile') }}" class="nav-link  {{ strtolower($activeMenu) == strtolower('PROFILE') ? 'active' : '' }}">
            <i class="icon feather icon-user"></i>
            <span>Profile</span>
        </a>
        <a href="media.html" class="nav-link  {{ strtolower($activeMenu) == strtolower('LOVE') ? 'active' : '' }}">
            <i class="icon feather icon-heart-on"></i>
            <span>Love</span>
        </a>
        <a href="{{ url('/home') }}" class="nav-link  {{ strtolower($activeMenu) == strtolower('HOME') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="pages.html" class="nav-link  {{ strtolower($activeMenu) == strtolower('EXPLORE') ? 'active' : '' }}">
            <i class="icon feather icon-grid"></i>
            <span>Explore</span>
        </a>
        <a href="components/components.html" class="nav-link  {{ strtolower($activeMenu) == strtolower('CHAT') ? 'active' : '' }}">
            <i class="fa fa-comments"></i>
            <span>Chat</span>
        </a>
    </div>
</div>
<!-- Menubar -->