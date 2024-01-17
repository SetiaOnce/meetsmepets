@extends('backend.pegawai.layouts', ['activeMenu' => 'DASHBOARD', 'activeSubMenu' => ''])
@section('content')
<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xl-6">
        <!--begin::card pie login-->
        <div class="card card-custom">
            <div class="card-body">
                <div id="statistik-login">
                    <h5 class="placeholder-glow text-center">
                        <span class="placeholder rounded col-8" style="height: 20px;"></span>
                    </h5>
                    <h5 class="placeholder-glow text-center mt-4">
                        <span class="placeholder col-10" style="height: 300px; border-radius: 50%;"></span>
                    </h5>
                </div>
            </div>
        </div>
        <!--begin::card pie login-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xl-6">
        <div class="card" id="card-application">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bolder fs-2 text-gray-900">
                        <i class="ki-outline ki-abstract-28 fs-2 text-gray-900 me-2"></i> Aplikasi Yang Dapat Diakses
                    </h3>
                </div>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->
@endsection