@extends('backend.admin.layouts', ['activeMenu' => 'PEGAWAI_HONORER', 'activeSubMenu' => 'sik'])
@section('content')
<!--begin::List table sik data pegawai perkantor-->
<div class="card shadow" id="card-data">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> List Data Pegawai Honorer
            </h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Button-->
            <button type="button" class="btn btn-sm btn-info font-weight-bolder mr-1" id="btn-btnSincronice" onclick="_sinkronisasi();"><i class="ki-outline ki-chart"></i> Sinkronisasi</button>
            <!--end::Button-->
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded  table-hover align-middle table-row-bordered border" id="dt-pegawaiHonorer">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light fs-8">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nama</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nik</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Tanggal Lahir</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Jenis Kelamin</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Unit</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Kantor</th>
                    </tr>
                </thead>
                <tbody class="fs-8"></tbody>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List table sik data pegawai perkantor-->
@endsection