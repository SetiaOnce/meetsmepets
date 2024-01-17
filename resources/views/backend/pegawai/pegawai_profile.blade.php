@extends('backend.pegawai.layouts', ['activeMenu' => '', 'activeSubMenu' => ''])
@section('content')
<!--begin::User Info-->
<div class="card mb-5 mb-xl-10" id="cardUserInfo">
    <!--begin::Details-->
    <div class="card-body" id="dtlUserInfo"></div>
    <!--end::Details-->
    <!--begin::Change Pass-->
    <div class="card-body" id="changePassUserInfo" style="display: none;">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-user-lock text-dark fs-2 me-3"></i>Ubah Password</h3>
            <a href="javascript:void(0);" class="btn btn-sm btn-bg-light btn-color-danger mb-3" onclick="_closeContentCard('changePass-userProfile')"><i class="las la-undo fs-3"></i> Kembali</a>
        </div>
        <!--begin::Form-->
        <form id="form-changePass" class="form" onsubmit="return false">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="pass_lama">Password Lama</label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <input type="password" class="form-control no-space password" name="pass_lama" id="pass_lama" minlength="6" placeholder="Isikan password lama ..." autocomplete="off"/>
                            <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6" data-kt-password-meter="true">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="pass_baru">Password Baru</label>
                    <div class="col-lg-8">
                        <div class="input-group mb-2">
                            <input type="password" class="form-control no-space password" name="pass_baru" id="pass_baru" minlength="8" placeholder="Isikan password baru ..." autocomplete="off"/>
                            <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                        </div>
                        <!--begin::Highlight meter-->
                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                        </div>
                        <!--end::Highlight meter-->
                        <div class="form-text">*) Tanpa spasi, Panjang karakter minimal 8 | contoh: <code>Admin.2024</code></div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="repass_baru">Ulangi Password Baru</label>
                    <div class="col-lg-8">
                        <div class="input-group mb-5">
                            <input type="password" class="form-control no-space password" name="repass_baru" id="repass_baru" minlength="8" placeholder="Ulangi password baru ..." autocomplete="off"/>
                            <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                        </div>
                        <div class="form-text">*) Harus sama dengan password baru</div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-resetPassUser"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
                <button type="button" class="btn btn-sm btn-primary" id="btn-savePassUser"><i class="las la-save fs-1 me-3"></i>Simpan</button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Change Pass-->
</div>
<!--end::User Info-->
@endsection