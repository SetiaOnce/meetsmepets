@extends('frontend.layouts', ['activeMenu' => 'PROFILE', 'activeSubMenu' => ''])
@section('content')
<!-- Page Content Start -->
<div class="page-content space-top p-b60" id="widgetOwner">
    <div class="container pt-0"> 
        <div class="profile-area">
            <div class="main-profile">
                <div class="about-profile">
                    <a href="{{ url('profile/setting') }}" class="setting dz-icon">
                        <i class="flaticon flaticon-setting"></i>
                    </a>
                    <div class="media rounded-circle avatar-user">
                        <img src="{{ asset('dist/img/image-placeholder.png') }}" alt="loader-img">
                    </div>
                    <a href="{{ url('profile/edit') }}" class="edit-profile dz-icon">
                        <i class="flaticon flaticon-pencil-2"></i>
                    </a>
                </div>
                <div class="profile-detail">
                </div>
            </div>
            <div class="row g-2 mb-5">
                <div class="col-4">
                    <div class="card style-2">
                        <div class="card-body">
                            <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">	
                                <div class="card-icon">
                                    <i class="flaticon flaticon-star-1"></i>
                                </div>
                                <div class="card-content">
                                    <p>0 Super Likes</p>
                                </div>
                                <i class="icon feather icon-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card style-2">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="flaticon flaticon-shuttle"></i>
                            </div>
                            <div class="card-content">
                                <p>My Boosts</p>
                            </div>
                            <i class="icon feather icon-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card style-2">
                        <div class="card-body">
                            <a href="javascript:void(0);">
                                <div class="card-icon">
                                    <i class="flaticon flaticon-bell"></i>
                                </div>
                                <div class="card-content">
                                    <p>Subscriptions</p>
                                </div>
                                <i class="icon feather icon-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
<!-- Page Content End -->
@endsection