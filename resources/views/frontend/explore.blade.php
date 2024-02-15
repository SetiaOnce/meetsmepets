@extends('frontend.layouts', ['activeMenu' => 'EXPLORE', 'activeSubMenu' => ''])
@section('content')
	<!-- Page Content Start -->
	<div class="page-content space-top p-b65">
		<div class="container">
			<div class="row g-2 mb-2" id="section-explore">
			</div>
			<div class="row g-2" id="loaderPlaceholder">
				<div class="col-6">
					<div class="dz-media-card">
						<a href="javascript:void(0);">
							<div class="dz-media">
                                <svg class="bd-placeholder-img rounded w-100 h-275px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <rect width="100%" height="100%" fill="#868e96"></rect>
                                </svg>
							</div>
							<div class="dz-content">
                                <h5 class="placeholder-glow w-100 d-flex flex-row flex-column">
                                    <span class="placeholder col-lg-10 rounded mb-1"></span>
                                    <span class="placeholder col-lg-8 rounded"></span>
                                </h5>
							</div>
						</a>
					</div>
				</div>
				<div class="col-6">
					<div class="dz-media-card">
						<a href="javascript:void(0);">
							<div class="dz-media">
                                <svg class="bd-placeholder-img rounded w-100 h-275px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <rect width="100%" height="100%" fill="#868e96"></rect>
                                </svg>
							</div>
							<div class="dz-content">
                                <h5 class="placeholder-glow w-100 d-flex flex-row flex-column">
                                    <span class="placeholder col-lg-10 rounded mb-1"></span>
                                    <span class="placeholder col-lg-8 rounded"></span>
                                </h5>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Page Content End -->
    <div class="footer fixed" style="padding-bottom: 15%;">
		<div class="container text-right btn-viewmore" style="display: none;">
			<a href="javascript:void(0);" onclick="viewMore()" class="btn btn-sm btn-info w-50 dz-flex-box rounded-xl"><i class="fa fa-plus me-2 align-middle" aria-hidden="true"></i>View More</a>
		</div>
	</div>
@endsection