@extends('frontend.layouts', ['activeMenu' => 'EXPLORE', 'activeSubMenu' => ''])
@section('content')
<!-- Page Content Start -->
<div class="page-content space-top p-b40">
	<div class="container">
		<div class="detail-area">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">About Pets</h5>
					</div>
					<div class="card-body">
						<div class="row g-2 mb-2 align-items-center">
							<div class="col-8">
								<div class="card card-bx">
									<div class="card-body">
										<div class="media">
											<div>
												<span class="heading font-15">{{ $data['getRow']['category'] }}</span>
												<h6 class="mb-0 title font-15">{{ $data['getRow']['breed'] }}</h6>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="card card-bx">
									<div class="card-body">
										<div class="media">
											<div>
												<h6 class="mb-0 title font-15 text-info totalLovePets"><i class="feather icon-heart-on me-1 text-info"></i>{{ count($data['likeStatus']) }} Love</h6>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>	
						<div class="dz-lightgallery row g-2" id="sectionImagePets">
							<div class="swiper-btn-center-lr">
								<div class="swiper spot-swiper1 mb-3">
									<div class="swiper-wrapper">
										@foreach ($data['getImage'] as $item)			
											<div class="swiper-slide">
												<div class="dz-media-card style-4">
													<a href="{{ $item }}" title="{{ $data['getRow']['category'] }}" subtitle="{{ $data['getRow']['breed'] }}">
														<div class="dz-media">
															<img src="{{ $item }}" alt="{{ $data['getRow']['category'] }}" >
														</div>
													</a>
												</div>
											</div>
										@endforeach

									</div>
								</div>
							</div>
						</div>
						<div class="meta-btn mt-2 btn-lovedislove">
							@if (!empty($data['likeStatus']) && count($data['likeStatus']) > 0)
								<a href="javascript:void(0);" onclick="updateStatusLikePets('{{ $data['getRow']['id'] }}', 'N')" class="btn w-100 font-16 p-2 btn-sm btn-danger light border-danger"><i class="feather icon-x"></i> Dislove</a>
							@else
								<a href="javascript:void(0);" onclick="updateStatusLikePets('{{ $data['getRow']['id'] }}', 'Y')" class="btn w-100 font-16 p-2 btn-sm btn-info light border-info"><i class="feather icon-heart-on"></i></a>
							@endif	
						</div>
					</div>
				</div>
			</div>
			<div class="detail-bottom-area">
				<div class="profile-area style-2">
					<div class="main-profile">
						<div class="about-profile">
							<div class="media rounded-circle">
								<img src="{{ $data['getRow']['thumb_url'] }}" alt="profile-image">
								<svg class="radial-progress m-b20" data-percentage="0" viewBox="0 0 80 80">
									<circle class="incomplete" cx="40" cy="40" r="35"></circle>
									<circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 131.947px;"></circle>
								</svg>
							</div>
						</div>
						<div class="profile-detail">
							<h6 class="name align-items-center">{{ $data['getRow']['name'] }} 
								@if (!empty($data['followStatus']))	
									<a href="javascript:void(0);" class="statusFollow" onclick="updateStatusLike('{{ $data['getRow']['fid_owner'] }}', 'N')"><span class="badge badge-lg badge-info me-1"><i class="fa fa-check"></i></span></a>
								@else
									<a href="javascript:void(0);" class="statusFollow" onclick="updateStatusLike('{{ $data['getRow']['fid_owner'] }}', 'Y')"><span class="badge badge-lg badge-info me-1"><i class="fa fa-plus"></i></span></a>
								@endif
							</h6>
							<a href="{{ url('/chat/'.$data['getRow']['fid_owner']) }}"><span class="badge badge-lg badge-success"><i class="fa fa-comments me-1"></i>Tap to chat</span></a>
						</div>
					</div>
				</div>
				<div class="about">
					<h6 class="title"><i class="icon feather icon-map-pin me-1"></i>Location</h6>
					<p class="para-text">{{ $data['getRow']['location'] }}</p>					
				</div>
				<div class="intrests mb-3">
					<h6 class="title">Intrests</h6>
					<ul class="dz-tag-list">
						@foreach ($data['getRow']['interest'] as $item)
							<li> 
								<div class="dz-tag">
									<span>{{ $item }}</span>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page Content End -->
@endsection