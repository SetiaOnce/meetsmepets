@extends('frontend.layouts', ['activeMenu' => 'PROFILE', 'activeSubMenu' => ''])
@section('content')
<!-- Page Content Start -->
<div class="page-content space-top" id="ktSettingProfile">
    <div class="container"> 
        <h6 class="title">Account Setting</h6>
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Username</h6>
            </div>
            <div class="card-body">
                <a href="javascript:void(0);" class="setting-input" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom5" aria-controls="offcanvasBottom5">
                    <i class="icon dz-flex-box icon feather icon-user"></i>
                    <span class="username">{{ $data['user_session']['username'] }}</span>
                </a>
            </div>
        </div>
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Phone Number</h6>
            </div>
            <div class="card-body">
                <a href="javascript:void(0);" class="setting-input" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom1" aria-controls="offcanvasBottom">
                    <i class="icon dz-flex-box feather icon-phone-call"></i>
                    <span class="phone_number">{{ $data['user_session']['phone_number'] }}</span>
                </a>
            </div>
        </div>
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Email Address</h6>
            </div>
            <div class="card-body">
                <a href="javascript:void(0);" class="setting-input" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom2" aria-controls="offcanvasBottom">
                    <i class="icon dz-flex-box feather icon-mail"></i>
                    <span class="email">{{ $data['user_session']['email'] }}</span>
                </a>
            </div>
        </div>
        <h6 class="title">Discovery Setting</h6>
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Your Location <br><small><code>*) click the maps to set your location</code></small></h6>
            </div>
            <div class="card-body">
                <div class="container" style="z-index: 1;">
                    <div id="mapsViewLocation" style="height: 400px;"></div>
                </div>
                <input type="hidden" name="address" id="address">
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
            </div>
        </div>
        <h6 class="title">Other</h6>
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Gender</h6>
            </div>
            <div class="card-body">
                <a href="javascript:void(0);" class="setting-input" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom4" aria-controls="offcanvasBottom">
                    <span class="gender">{{ ($data['user_session']['gender'] == null) ? 'OTHER' : $data['user_session']['gender'] }}</span>
                    <i class="icon feather dz-flex-box icon-chevron-right ms-auto me-0"></i>
                </a>
            </div>
        </div>
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Maximum Distance</h6>
                <div class="title font-w600 font-16">
                    <span class="example-val title slider-margin-value-min"></span>
                    <span class="example-val title slider-margin-value-max"></span>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="range-slider style-1 w-100">
                    <div id="distance-slider"></div>
                </div>
            </div>
        </div>
        <div class="card style-3  pb-5">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Looking For</h6>
            </div>
            <div class="card-body">
                <select class="form-control selectpicker form-control-solid mb-3 mb-lg-0" id="looking_for" name="looking_for[]" multiple data-placeholder="Select here"></select>
            </div>
        </div>
    </div> 
</div>
<!-- Page Content End -->

<!-- Username OffCanvas -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom5">
    <button type="button" class="btn-close drage-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <div class="offcanvas-header share-style m-0 pb-0">
        <h6 class="title">Username</h6>
    </div>
    <div class="offcanvas-body overflow-visible">
        <form>
            <div class="input-group dz-select">
                <input type="text" name="username" id="username" class="form-control" value="{{ $data['user_session']['username'] }}">
            </div>
            <a href="javascript:void(0);" id="btn-username" class="btn btn-gradient w-100 dz-flex-box btn-shadow rounded-xl" data-bs-dismiss="offcanvas" aria-label="Close">Save</a>
        </form>		
    </div>
</div>
<!-- Username OffCanvas -->
<!-- Phone Number OffCanvas -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom1">
    <button type="button" class="btn-close drage-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <div class="offcanvas-header share-style m-0 pb-0">
        <h6 class="title">Phone Number</h6>
    </div>
    <div class="offcanvas-body overflow-visible">
        <form>
            <div class="input-group dz-select">
                <div class="input-group-text"> 
                    <div>
                        <select class="form-control custom-image-select image-select">
                            <option data-thumbnail="{{ asset('dist/assets/images/flags/australia.png') }}">+62</option>
                        </select>
                    </div>
                </div>
                <input type="number" name="phone_number" id="phone_number" class="form-control" value="{{ $data['user_session']['phone_number'] }}">
            </div>
            <a href="javascript:void(0);" id="btn-phone" class="btn btn-gradient w-100 dz-flex-box btn-shadow rounded-xl" data-bs-dismiss="offcanvas" aria-label="Close">Save</a>
        </form>		
    </div>
</div>
<!-- Phone Number OffCanvas -->
<!--  Email OffCanvas -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom2">
    <button type="button" class="btn-close drage-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <div class="offcanvas-header share-style m-0 pb-0">
        <h6 class="title">Email Address</h6>
    </div>
    <div class="offcanvas-body">
        <form>
            <div class="input-group input-group-icon">
                <div class="input-group-text">
                    <div class="input-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                <input type="email" class="form-control"  name="email" id="email" value="{{ $data['user_session']['email'] }}">
            </div>
            <a href="javascript:void(0);" id="btn-email" class="btn btn-gradient w-100 dz-flex-box btn-shadow rounded-xl" data-bs-dismiss="offcanvas" aria-label="Close">Save</a>
        </form>
    </div>
</div>
<!--  Email OffCanvas -->
<!--  Gender OffCanvas -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom4">
    <button type="button" class="btn-close drage-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <div class="offcanvas-header share-style m-0 pb-0">
        <h6 class="title">Your Gender</h6>
    </div>
    <div class="offcanvas-body">
        <div class="radio style-2">
            <label class="radio-label">
                <input type="radio" {{ ($data['user_session']['gender'] == 'WOMEN') ? 'checked' : '' }} name="gender" value="WOMEN">
                <span class="checkmark">						
                    <span class="text">Women</span>
                    <span class="check"></span>							
                </span>
            </label>
            <label class="radio-label">
                <input type="radio" {{ ($data['user_session']['gender'] == 'MEN') ? 'checked' : '' }} name="gender" value="MEN">
                <span class="checkmark">
                    <span class="text">Men</span>
                    <span class="check"></span>							
                </span>
            </label>
            <label class="radio-label">
                <input type="radio" {{ ($data['user_session']['gender'] == null || $data['user_session']['gender'] == 'OTHER') ? 'checked' : '' }} name="gender" value="OTHER">
                <span class="checkmark">
                    <span class="text">Other</span>	
                    <span class="check"></span>							
                </span>
            </label>
        </div>
    </div>
</div>
<!--  Gender OffCanvas -->
<script>var mapbox_accessToken = "{{ env('MAPBOX_TOKEN') }}";</script>
@endsection