@extends('backend.admin.layouts', ['activeMenu' => 'DATA_KANTOR', 'activeSubMenu' => 'sik'])
@section('content')
<!--begin::List table sik data kantor-->
<div class="card shadow" >
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Daftar List Kantor
            </h3>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded  table-hover align-middle table-row-bordered border" id="dt-kantor">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light fs-8">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Kode Kantor</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nama Kantor</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Kode Kantor</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Eselon</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Level Struktur</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nama Struktur</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nama Pejabat</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Nip Pejabat</th>
                    </tr>
                </thead>
                <tbody class="fs-8"></tbody>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List table sik data kantor-->
@endsection