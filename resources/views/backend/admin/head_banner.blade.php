@extends('backend.admin.layouts', ['activeMenu' => 'HEAD_BANNER', 'activeSubMenu' => 'content'])
@section('content')
<!--begin::System Info-->
<div class="card mb-5 mb-xl-10" id="cardHeadBanner">
    <!--begin::Edit-->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-10">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-dark fs-2 me-3"></i>Edit Konten Head Banner</h3>
            {{-- <a href="javascript:history.back();" class="btn btn-sm btn-bg-light btn-color-danger btn-active-light-danger ms-3"><i class="las la-undo fs-3 me-1"></i>Kembali</a> --}}
        </div>
        <!--begin::Form-->
        <form id="form-editHeadBanner" class="form" onsubmit="return false">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="head_title">Head Title</label>
                <div class="col-lg-8">
                    <input type="text" name="head_title" id="head_title" class="form-control form-control-solid mb-3 mb-lg-0" maxlength="70" placeholder="Isikan head title ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="description">Deskripsi Singkat</label>
                <div class="col-lg-8">
                    <textarea name="description" id="description" class="form-control form-control-solid mb-3 mb-lg-0" rows="2" maxlength="160" placeholder="Isikan deskripsi singkat ..."></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Background Banner</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-12">
                            <!--begin::Input group-->
                            <div class="mb-3" id="iGroup-background_banner">
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="background_banner" name="background_banner" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--end::Input group-->
            <div class="row mt-5">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-resetFormHeadBanner"><i class="las la-redo-alt fs-3 me-1"></i>Batal</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-saveHeadBanner">
                        <span class="indicator-label">
                            <i class="las la-save fs-3 me-1"></i>Simpan
                        </span>
                        <span class="indicator-progress">
                            Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!--end::Edit-->
</div>
<!--end::System Info-->
@endsection