@extends('backend.admin.layouts', ['activeMenu' => 'DASHBOARD', 'activeSubMenu' => ''])
@section('content')
<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xl-3">
        <!--begin::User widget 1-->
        <div class="card" id="card-userInfo">
            <div class="card-body">
                <div class="d-flex flex-center flex-column py-5 mb-3">
                    <div class="symbol symbol-100px symbol-circle mb-7 placeholder-glow">
                        <svg class="bd-placeholder-img placeholder rounded-circle h-125px w-125px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect></svg>
                    </div>
                    <h3 class="fs-3 text-center placeholder-glow w-100">
                        <span class="placeholder col-10 rounded"></span>
                    </h3>
                    <div class="mb-5 w-100">
                        <h3 class="fs-3 text-center placeholder-glow w-100">
                            <span class="placeholder col-8 rounded"></span>
                        </h3>
                    </div>
                    <div class="m-0 w-100">
                        <h1 class="fs-2x text-center placeholder-glow w-100">
                            <span class="placeholder col-6 rounded"></span>
                        </h1>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="fs-6">
                    <h3 class="fs-3 text-center placeholder-glow w-100 mt-5">
                        <span class="placeholder col-8 rounded"></span>
                    </h3>
                    <h3 class="fs-3 text-center placeholder-glow w-100 mt-5">
                        <span class="placeholder col-8 rounded"></span>
                    </h3>
                    <h3 class="fs-3 text-center placeholder-glow w-100 mt-5">
                        <span class="placeholder col-8 rounded"></span>
                    </h3>
                    <h3 class="fs-3 text-center placeholder-glow w-100 mt-5">
                        <span class="placeholder col-8 rounded"></span>
                    </h3>
                    <h3 class="fs-3 text-center placeholder-glow w-100 mt-5">
                        <span class="placeholder col-8 rounded"></span>
                    </h3>
                </div>
            </div>
        </div>
        <!--end::User widget 1-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xl-9">
        <!--begin::Row-->
        <div class="row g-5">
            <div class="col-xl-4">
                <div class="card bg-info">
                    <div class="card-body py-3 px-5">
                        <div class="d-flex flex-stack align-items-center mb-3">
                            <span class="ms-n1">
                                <i class="text-white ki-outline ki-profile-user fs-3x"></i>
                            </span>
                            <div class="text-white fw-bold fs-1" id="countPegawaiAsn">0</div>
                        </div>
                        <div class="fw-semibold text-white">
                            PEGAWAI ASN
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card bg-danger">
                    <div class="card-body py-3 px-5">
                        <div class="d-flex flex-stack align-items-center mb-3">
                            <span class="ms-n1">
                                <i class="text-white ki-outline ki-profile-user fs-3x"></i>
                            </span>        
                            <div class="text-white fw-bold fs-1" id="countPegawaiHonorer">0</div>
                        </div>
                        <div class="fw-semibold text-white">
                            PEGAWAI HONORER
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card bg-primary">
                    <div class="card-body py-3 px-5">
                        <div class="d-flex flex-stack align-items-center mb-3">
                            <span class="ms-n1">
                                <i class="text-white ki-duotone ki-security-user fs-3x"><span class="path1"></span><span class="path2"></span></i>
                            </span>        
                            <div class="text-white fw-bold fs-1" id="countPegawaiPengelola">0</div>
                        </div>
                        <div class="fw-semibold text-white">
                            PENGELOLA PORTAL
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->
@endsection