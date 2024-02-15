@extends('frontend.layouts', ['activeMenu' => 'LOVE', 'activeSubMenu' => ''])
@section('content')
<!-- Page Content Start -->
<div class="page-content space-top p-b65" id="card-content" >
    <div class="container">
        <div class="col-12 text-right">
            <a href="javascript:void(0);" onclick="_openCardFilter()" class="filter-icon fs-6">
                <i class="flaticon flaticon-settings-sliders"></i>
            </a>
        </div>
        <div class="tab-pane fade active show" role="tabpanel">
            <div class="title-bar">
                <h6 class="title">Populer</h6>
            </div>
            <div class="swiper-btn-center-lr">
                <div class="swiper spot-swiper1 mb-3">
                    <div class="swiper-wrapper"> 
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-2 mb-2 section-all">
            
        </div>
    </div>
</div>
<!-- Page Content End -->
<!-- Page Filter -->
<div class="page-content space-top p-b65" id="card-filter" style="display: none;">
	<div class="container mb-5">
        <div class="row col-12 mb-2">
            <a href="javascript:void(0);" onclick="_closeCardFilter()" class="filter-icon fs-6 text-danger">
                <i class="fa fa-chevron-left text-danger me-1"></i>Back
            </a>
        </div>
        <div class="card style-1">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Distance</h6>
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
        <div class="card style-1">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Category</h6>
            </div>
            <div class="card-body">
                <select class="form-control selectpicker form-control-solid mb-3 mb-lg-0" id="category" name="category[]" data-live-search="true" multiple data-placeholder="Filter category"></select>
            </div>
        </div>
        <div class="card style-1">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Breed</h6>
            </div>
            <div class="card-body">
                <select class="form-control selectpicker form-control-solid mb-3 mb-lg-0" id="breed" name="breed[]" data-live-search="true" multiple data-placeholder="Filter breed"></select>
            </div>
        </div>
    </div>
    <!-- Footer -->
	<div class="footer fixed" style="padding-bottom: 15%;">
		<div class="container">
			<a href="javascript:void(0);" class="btn btn-lg btn-gradient w-100 dz-flex-box rounded-xl" id="btn-apllyfilter">Apply</a>
		</div>
	</div>
	<!-- Footer -->
</div>
<!-- Page Filter -->
<script>let fl_category = "{{ $data['fl_category'] }}";</script>
@endsection