@extends('frontend.layouts', ['activeMenu' => 'PROFILE', 'activeSubMenu' => ''])
@section('content')
<!-- Page Content Start -->
<div class="page-content space-top">
    <div class="container"> 
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Full Name</h6>
            </div>
            <div class="card-body">
                <a href="javascript:void(0);" class="setting-input" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom1" aria-controls="offcanvasBottom">
                    <i class="icon dz-flex-box feather icon-phone-call"></i>
                    <span class="full_name">{{ $data['user_session']['name'] }}</span>
                </a>
            </div>
        </div>
        <div class="card style-3">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Your Foto Profiles <br><p class="text-muted">*) Click to update image | max : <code>2MB</code> | mimes : <code>jpg, jpeg, png</code>
                </p></h6>
            </div>
            <div class="card-body justify-content-center">
                <form class="form" id="form-fotoProfiles">
                    <!--begin::Input group-->
                    <div class="mb-3" id="iGroup-foto_profiles">
                        <input type="file" class="dropify-upl mb-3 mb-lg-0" id="foto_profiles" name="foto_profiles" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                    </div>
                    <!--end::Input group-->
                </form>
            </div>
        </div>
        <div class="card style-3 pb-5">
            <div class="card-header align-items-center">
                <h6 class="title mb-0 font-14 font-w500">Your Pets</h6>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm mb-2 me-2 btn-info" onclick="_addPets()"><i class="fa fa-plus me-2"></i>Add New</button>
                </div>
            </div>
            <div class="card-body">
            </div>
        </div>
        {{-- <form class="form" id="form-images">
            <div class="row g-3 mb-3"  data-masonry='{"percentPosition": true }'>
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Your Pets Album <br><p class="text-muted">*) Click to add or update image | max : <code>2MB</code> | mimes : <code>jpg, jpeg, png</code>
                </p></h6>
            </div>
                <div class="col-8">
                    <div class="dz-drop-box style-2">
                        <div class="drop-bx bx-lg">
                            <div class="imagePreview imagePreview1" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                <div  class="remove-img remove-btn d-none remove-btn1"><i class="icon feather icon-x"></i></div>
                                <input type='file' class="form-control d-none imageUpload" name="imageUpload" id="imageUpload" accept=".png, .jpg, .jpeg">
                                <label for="imageUpload"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="dz-drop-box style-2">
                                <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                                <div class="drop-bx">
                                    <div class="imagePreview imagePreview2" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                        <div  class="remove-img remove-btn d-none remove-btn2"><i class="icon feather icon-x"></i></div>
                                        <input type='file' class="form-control d-none imageUpload" name="imageUpload2"  id="imageUpload2" accept=".png, .jpg, .jpeg">
                                        <label for="imageUpload2"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="dz-drop-box style-2">
                                <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                                <div class="drop-bx">
                                    <div class="imagePreview imagePreview3" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                        <div  class="remove-img remove-btn d-none remove-btn3"><i class="icon feather icon-x"></i></div>
                                        <input type='file' class="form-control d-none imageUpload" name="imageUpload3"  id="imageUpload3" accept=".png, .jpg, .jpeg">
                                        <label for="imageUpload3"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="dz-drop-box style-2">
                        <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                        <div class="drop-bx">
                            <div class="imagePreview imagePreview4" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                <div  class="remove-img remove-btn d-none remove-btn4"><i class="icon feather icon-x"></i></div>
                                <input type='file' class="form-control d-none imageUpload" name="imageUpload4" id="imageUpload4" accept=".png, .jpg, .jpeg">
                                <label for="imageUpload4"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="dz-drop-box style-2">
                        <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                        <div class="drop-bx">
                            <div class="imagePreview imagePreview5" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                <div  class="remove-img remove-btn d-none remove-btn5"><i class="icon feather icon-x"></i></div>
                                <input type='file' class="form-control d-none imageUpload" name="imageUpload5" id="imageUpload5" accept=".png, .jpg, .jpeg">
                                <label for="imageUpload5"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="dz-drop-box style-2">
                        <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                        <div class="drop-bx">
                            <div class="imagePreview imagePreview6" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                <div  class="remove-img remove-btn d-none remove-btn6"><i class="icon feather icon-x"></i></div>
                                <input type='file' class="form-control d-none imageUpload"  id="imageUpload6" name="imageUpload6" accept=".png, .jpg, .jpeg">
                                <label for="imageUpload6"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form> --}}
        <div class="card style-3 pb-5">
            <div class="card-header">
                <h6 class="title mb-0 font-14 font-w500">Interest</h6>
            </div>
            <div class="card-body">
                <select class="form-control selectpicker form-control-solid mb-3 mb-lg-0" id="interest" name="interest[]" multiple data-placeholder="Select here"></select>
            </div>
        </div>
    </div> 
</div>
<!-- Full Name OffCanvas -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom1">
    <button type="button" class="btn-close drage-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <div class="offcanvas-header share-style m-0 pb-0">
        <h6 class="title">Your Full Name</h6>
    </div>
    <div class="offcanvas-body overflow-visible">
        <form>
            <div class="input-group dz-select">
                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ $data['user_session']['name'] }}">
            </div>
            <a href="javascript:void(0);" id="btn-name" class="btn btn-gradient w-100 dz-flex-box btn-shadow rounded-xl" data-bs-dismiss="offcanvas" aria-label="Close">Save</a>
        </form>		
    </div>
</div>
<!-- Full Name OffCanvas -->
<!-- Page Content End -->

<!-- Modal Pets Begin -->
<div class="modal fade" id="ModalPets" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form" id="form-pets">
                    <input type="hidden" name="id"/><input type="hidden" name="method_formdata"/>
                    <div class="form-group mb-2">
                        <select class="form-control selectpicker form-control-solid mb-3 mb-lg-0" id="category" name="category" data-placeholder="Select category ..."></select>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" name="breed" id="breed" class="form-control" placeholder="Breed ...">
                    </div>
                    <div class="row g-3 mb-3"  data-masonry='{"percentPosition": true }'>
                        <h6 class="title mb-0 font-14 font-w500">Pets Foto <br><p class="text-muted">*) Click to add or update image | max : <code>2MB</code> | mimes : <code>jpg, jpeg, png</code>
                        </p></h6>
                        <div class="col-8">
                            <div class="dz-drop-box style-2">
                                <div class="drop-bx bx-lg">
                                    <div class="imagePreview imagePreview1" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                        <div  class="remove-img remove-btn d-none remove-btn1"><i class="icon feather icon-x"></i></div>
                                        <input type='file' class="form-control d-none imageUpload" name="imageUpload" id="imageUpload" accept=".png, .jpg, .jpeg">
                                        <label for="imageUpload"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="dz-drop-box style-2">
                                        <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                                        <div class="drop-bx">
                                            <div class="imagePreview imagePreview2" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                                <div  class="remove-img remove-btn d-none remove-btn2"><i class="icon feather icon-x"></i></div>
                                                <input type='file' class="form-control d-none imageUpload" name="imageUpload2"  id="imageUpload2" accept=".png, .jpg, .jpeg">
                                                <label for="imageUpload2"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="dz-drop-box style-2">
                                        <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                                        <div class="drop-bx">
                                            <div class="imagePreview imagePreview3" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                                <div  class="remove-img remove-btn d-none remove-btn3"><i class="icon feather icon-x"></i></div>
                                                <input type='file' class="form-control d-none imageUpload" name="imageUpload3"  id="imageUpload3" accept=".png, .jpg, .jpeg">
                                                <label for="imageUpload3"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dz-drop-box style-2">
                                <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                                <div class="drop-bx">
                                    <div class="imagePreview imagePreview4" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                        <div  class="remove-img remove-btn d-none remove-btn4"><i class="icon feather icon-x"></i></div>
                                        <input type='file' class="form-control d-none imageUpload" name="imageUpload4" id="imageUpload4" accept=".png, .jpg, .jpeg">
                                        <label for="imageUpload4"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dz-drop-box style-2">
                                <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                                <div class="drop-bx">
                                    <div class="imagePreview imagePreview5" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                        <div  class="remove-img remove-btn d-none remove-btn5"><i class="icon feather icon-x"></i></div>
                                        <input type='file' class="form-control d-none imageUpload" name="imageUpload5" id="imageUpload5" accept=".png, .jpg, .jpeg">
                                        <label for="imageUpload5"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dz-drop-box style-2">
                                <img src="{{ asset('dist/assets/images/recent-pic/drop-bx.png') }}" alt="">
                                <div class="drop-bx">
                                    <div class="imagePreview imagePreview6" style="background-image: url({{ asset('dist/assets/images/recent-pic/drop-bx.png') }});">
                                        <div  class="remove-img remove-btn d-none remove-btn6"><i class="icon feather icon-x"></i></div>
                                        <input type='file' class="form-control d-none imageUpload"  id="imageUpload6" name="imageUpload6" accept=".png, .jpg, .jpeg">
                                        <label for="imageUpload6"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="btn-savePets"><i class="las la-save align-center me-2"></i> Save</button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="_closeModal()"><i class="fa fa-times align-center me-2"></i>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Pets End -->
@endsection