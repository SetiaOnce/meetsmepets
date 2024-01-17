@extends('backend.admin.layouts', ['activeMenu' => 'WEBSITE_INFORMATION', 'activeSubMenu' => 'content'])
@section('content')
<!--begin::System Info-->
<div class="card mb-5 mb-xl-10" id="cardSiteInfo">
    <!--begin::Edit-->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-10">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-dark fs-2 me-3"></i>Edit Informasi Situs Web</h3>
            <div class="align-items-center">
                <button class="btn btn-sm btn-info me-2 position-relative" onclick="_manageTautan()">
                    <i class="las la-link fs-3 me-1"></i>Kelola Tautan <span class="position-absolute top-0 start-0 translate-middle  badge badge-circle badge-danger" id="totalTautan">{{ $data['totalTautan'] }}</span>
                </button>
                <a href="javascript:history.back();" class="btn btn-sm btn-bg-light btn-color-danger btn-active-light-danger ms-3"><i class="las la-undo fs-3 me-1"></i>Kembali</a>
            </div>
        </div>
        <!--begin::Form-->
        <form id="form-editSiteInfo" class="form" onsubmit="return false">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                <div class="col-lg-8">
                    <input type="text" name="name" id="name" class="form-control form-control-solid mb-3 mb-lg-0" maxlength="255" placeholder="Isikan nama situs ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="short_name">Nama Alias/ Nama Pendek</label>
                <div class="col-lg-8">
                    <input type="text" name="short_name" id="short_name" class="form-control form-control-solid mb-3 mb-lg-0" maxlength="60" placeholder="Isikan nama alias / nama pendek situs ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="description">Deskripsi</label>
                <div class="col-lg-8">
                    <textarea name="description" id="description" class="form-control form-control-solid mb-3 mb-lg-0" rows="2" maxlength="160" placeholder="Isikan deskripsi singkat situs ..."></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="keyword">Keyword/ Kata Kunci</label>
                <div class="col-lg-8">
                    <select class="form-control form-control-solid mb-3 mb-lg-0" id="keyword" name="keyword[]" multiple></select>
                    <div class="form-text">*) Pisahkan keyword dengan tanda koma, contoh: <code>sisaka, perhubungan, sertifikasi</code></div>
                    <div class="form-text">*) Maksimal: <code>10</code> kata kunci</div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="about_portal">Tentang Portal</label>
                <div class="col-lg-8">
                    <textarea name="about_portal" id="about_portal" class="form-control form-control-solid mb-3 mb-lg-0" rows="6"  placeholder="Isikan tentang portal ..."></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="copyright">Copyright</label>
                <div class="col-lg-8">
                    <textarea name="copyright" id="copyright" class="form-control form-control-solid mb-3 mb-lg-0 summernote"></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Logo Header Publik</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-12">
                            <!--begin::Input group-->
                            <div class="mb-3" id="iGroup-headpublic_logo">
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="headpublic_logo" name="headpublic_logo" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Login Logo & Background</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="mb-3" id="iGroup-login_logo">
                                <label class="col-form-label required fw-bold fs-6" for="login_logo">Login Logo</label>
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="login_logo" name="login_logo" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="mb-3" id="iGroup-login_bg">
                                <label class="col-form-label required fw-bold fs-6" for="login_bg">Login Background</label>
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="login_bg" name="login_bg" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Logo Header Backend</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="mb-3" id="iGroup-headbackend_logo">
                                <label class="col-form-label required fw-bold fs-6" for="headbackend_logo">Light Mode</label>
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="headbackend_logo" name="headbackend_logo" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="mb-3 dropify-custom-dark" id="iGroup-headbackend_logo_dark">
                                <label class="col-form-label required fw-bold fs-6" for="headbackend_logo_dark">Dark Mode</label>
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="headbackend_logo_dark" name="headbackend_logo_dark" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Icon Header Backend</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="mb-3" id="iGroup-headbackend_icon">
                                <label class="col-form-label required fw-bold fs-6" for="headbackend_icon">Light Mode</label>
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="headbackend_icon" name="headbackend_icon" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="mb-3 dropify-custom-dark" id="iGroup-headbackend_icon_dark">
                                <label class="col-form-label required fw-bold fs-6" for="headbackend_icon_dark">Dark Mode</label>
                                <input type="file" class="dropify-upl mb-3 mb-lg-0" id="headbackend_icon_dark" name="headbackend_icon_dark" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <div class="row mt-5">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-resetFormSiteInfo"><i class="las la-redo-alt fs-3 me-1"></i>Batal</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-saveSiteInfo">
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
<!--begin::modal manage tautan-->
<div class="modal fade" tabindex="-1" id="modalManageTautan">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-primary mb-2" id="btn-openFormSrc" onclick="_openFormSrc();"><i class="las la-plus fs-3"></i> Tambah</button>
                </div>
                <div class="mb-3" id="div-formSrc" style="display: none;">
                    <!--begin::Form-->
                    <form id="form-tautan" class="form border-gray-300 border-dashed rounded py-4 px-4" onsubmit="return false">
                        <!--begin::Row-->
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="col-md-12">
                                <!--begin::Form Group-->
                                <div class="form-group mb-3">
                                    <label class="col-form-label required fw-bold fs-6" for="nama_tautan">Nama Tautan</label>
                                    <input type="text" name="nama_tautan" id="nama_tautan" class="form-control mb-lg-0" maxlength="120" placeholder="Isikan nama tautan ..." />
                                </div>
                                <!--end::Form Group-->
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label required fw-bold fs-6" for="link_tautan">Link Tautan</label>
                                <!--begin::Input Group-->
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="las la-external-link-alt fs-1"></i></span>
                                    <input type="text" class="form-control no-space" name="link_tautan" id="link_tautan" placeholder="Isikan link tautan ..." />
                                </div>
                                <!--end::Input Group-->
                            </div>
                        </div>
                        <!--end::Row-->
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end ">
                            <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-closeFormSrc" onclick="_closeFormSrc();"><i class="ki-solid ki-abstract-11 fs-1 me-3"></i>Batal</button>
                            <button type="button" class="btn btn-sm btn-primary" id="btn-saveSrc">
                                <span class="indicator-label">
                                    <i class="las la-save fs-3"></i> Simpan
                                </span>
                                <span class="indicator-progress">
                                    Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-rounded  table-hover align-middle table-row-bordered border" id="dt-tautan">
                        <thead>
                            <tr class="fw-bolder text-uppercase bg-light fs-8">
                                <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                                <th class="border-bottom-2 border-gray-200 align-middle px-2">Nama Tautan</th>
                                <th class="border-bottom-2 border-gray-200 align-middle px-2">Link Tautan</th>
                                <th class="border-bottom-2 border-gray-200 align-middle px-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fs-8"></tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::modal manage tautan-->
@endsection