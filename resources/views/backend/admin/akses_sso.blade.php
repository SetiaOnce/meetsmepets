@extends('backend.admin.layouts', ['activeMenu' => 'AKSES_SSO', 'activeSubMenu' => ''])
@section('content')
<!--begin::Card Form-->
<div class="card" id="card-form" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger btn-active-light-danger" id="btn-closeForm" onclick="_closeCard('form_pengelola');"><i class="fas fa-times fs-3 me-1"></i>Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form id="form-data" class="form" onsubmit="return false">
        <input type="hidden" name="id" />
        <input type="hidden" name="fid_aplikasi" />
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row justify-content-start">
                <div class="col-md-8">
                    <!--begin::Input group-->
                    <div class="mb-3" id="iGroup-fid_pegawai">
                        <label class="col-form-label required fw-bold fs-6" for="fid_pegawai">Pegawai</label>
                        <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-secondary text-dark" name="fid_pegawai" id="fid_pegawai" data-container="body" data-live-search="true" title="Pilih Pegawai ..."></select>
                        <div class="form-text">*) Harus diisi</div>
                    </div>
                    <!--end::Input group-->
                </div>
                <div id="detailPegawaiDesc">
                </div>
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row d-flex justify-content-between">
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="mb-3" id="iGroup-fid_aplikasi">
                        <label class="col-form-label required fw-bold fs-6" for="fid_aplikasi">Aplikasi</label>
                        <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-secondary text-dark" name="fid_aplikasi" id="fid_aplikasi" data-container="body" data-live-search="true" title="Pilih Aplikasi ..."></select>
                        <div class="form-text">*) Harus diisi</div>
                    </div>
                    <!--end::Input group-->
                </div>
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="mb-3" id="iGroup-fid_level_aplikasi" style="display: none;">
                        <label class="col-form-label required fw-bold fs-6" for="fid_level_aplikasi">Level Aplikasi</label>
                        <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-secondary text-dark" name="fid_level_aplikasi" id="fid_level_aplikasi" data-container="body" data-live-search="true" title="Pilih Level Aplikasi ..."></select>
                        <div class="form-text">*) Harus diisi</div>
                    </div>
                    <!--end::Input group-->
                </div>
                <div class="col-md-12">
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-status" style="display: none">
                        <label class="col-form-label required fw-bold fs-6" for="status">Status</label>
                        <div class="form-check form-switch form-check-custom">
                            <input class="form-check-input cursor-pointer" type="checkbox" id="status" name="status" />
                            <label class="form-check-label" for="status"></label>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-reset" onclick="_clearForm();"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
            <button type="button" class="btn btn-sm btn-primary" id="btn-save">
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
<!--end::Card Form-->
<!--begin::Card Data-->
<div class="card shadow" id="card-data">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> List Akses SSO Aplikasi 
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary" id="btn-addData" onclick="_addData();"><i class="las la-plus fs-3"></i> Tambah</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded table-hover align-middle table-row-bordered border" id="dt-akses">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light fs-8">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2 d-none">Aplikasi</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Pegawai</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Email</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nik</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">level</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Foto</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Status</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-8"></tbody>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::Card Data-->
@endsection