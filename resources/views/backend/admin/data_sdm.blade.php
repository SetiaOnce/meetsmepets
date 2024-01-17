@extends('backend.admin.layouts', ['activeMenu' => 'DATA_SDM', 'activeSubMenu' => 'management'])
@section('content')
<!--begin::List table sik data sdm-->
<div class="card shadow" id="card-data">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> List SDM
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="border border-gray-300 border-dashed rounded  py-3 px-4 me-6 mb-3">
                <!--begin::Number-->
                <div class="d-flex align-items-center justify-content-center">
                    <div class="text-primary fw-bold fs-1" id="countPegawaiOnline">{{ $data['totalOnline'] }}</div>
                </div>
                <!--end::Number-->
                <!--begin::Label-->
                <div class="fw-bolder fs-7">Pegawai Online</div>
                <!--end::Label-->
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded  table-hover align-middle table-row-bordered border" id="dt-sdm">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light fs-8">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Pegawai</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nik</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nip</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Status Pegawai</th>
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
<!--end::List table sik data sdm-->
<!--begin::modal detail sdm-->
<div class="modal fade" tabindex="-1" id="modalDetailSdm">
    <div class="modal-dialog  modal-lg">
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
                <div id="detailPegawaiDesc">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::modal detail sdm-->
@endsection