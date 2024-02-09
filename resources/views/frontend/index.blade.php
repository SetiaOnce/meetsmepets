@extends('frontend.layouts', ['activeMenu' => 'HOME', 'activeSubMenu' => ''])
@section('content')
<!-- Page Content Start -->
<div class="page-content space-top p-b65">
    <div class="container fixed-full-area">
        <div class="dzSwipe_card-cont dz-gallery-slider" id="sectionNearbyowners">
            
        </div>
    </div>
</div>
<!-- Page Content End -->
<input type="hidden" name="lat" id="lat">
<input type="hidden" name="lng" id="lng">
@endsection