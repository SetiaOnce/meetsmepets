@extends('frontend.layouts', ['activeMenu' => 'BERANDA', 'activeSubMenu' => '', 'title' => 'Sistem Informasi Sarana Perkeretaapian'])
@section('content')
<!-- begin: Aplikasi -->
<section class="pt-3 pb-4" id="AplikasiSection">
    <div class="container">
        <div class="row">
            <div class="col-md-7 mx-auto text-center headTitle">
                <span class="placeholder col-6 rounded mt-5 mb-4" style="height: 25px"></span>
            </div>
            <div class="col-lg-12 mx-auto py-2">
                <div class="row bodyContent">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end: Aplikasi -->
<!-- begin: profile sisaka -->
<section class="pb-5 position-relative bg-gradient-dark mx-n3" id="LayananSection">
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-start mb-5 mt-5 headTitle">
                <span class="placeholder col-5 rounded" style="height: 45px"></span>
            </div>
        </div>
        <div class="row bodyContent">
        </div>
    </div>
</section>
<!-- end: profile sisaka -->
<!-- begin: monitoring -->
<section class="py-sm-7" id="card-monitoring">
    <div class="position-relative m-3 border-radius-xl" style="background: radial-gradient(100% 100% at 100% 0%, #A9E2E1 0%, rgba(169, 226, 225, 0) 100%), radial-gradient(100% 100% at 0% 0%, #D9ECCB 0%, rgba(217, 236, 203, 0) 100%), #F5F5F5 !important; overflow: hidden;">
        <img src="{{ asset('dist/img/waves-white.svg') }}" alt="pattern-lines" class="position-absolute start-0 top-md-0 w-100 opacity-2">
        <div class="container pb-lg-8 pb-7 pt-5 postion-relative z-index-2 position-relative">
            <div class="row">
                <div class="col-md-10 mx-auto text-left">
                    <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="fa fa-bullhorn align-middle fs-5 text-light me-2"></i></span>
                        <span class="alert-text justify-content-start text-light text-justify">Fitur ini digunakan untuk melakukan monitoring tehadap semua jenis pelayanan</span>
                    </div>
                </div>
                <div class="row bg-white shadow-lg border-radius-xl pb-4 p-3 position-relative w-85 mx-auto align-items-center">
                    <div class="col-lg-5 mt-2">
                        <select id="status" name="status" class="selectpicker required show-tick form-control" data-live-search="false"  title="-- Jenis Monitoring --" data-style="btn btn-light text-dark fw-bolder">
                            @foreach ($data['dtAplikasi'] as $row)
                                <option value="{{ $row->id }}">{{ $row->nama_aplikasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5 mt-2">
                        <div class="input-group input-group-dynamic">
                        <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input class="form-control" placeholder="Pencarian" type="text">
                        </div>
                    </div>
                    <div class="col-lg-2 d-flex align-items-center mt-lg-auto mt-2">
                        <button type="button" id="btn-search" class="btn bg-gradient-info w-100 mb-0">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end: monitoring -->
@section('js')
    <script src="{{ asset('scripts/frontend/main.init.js') }}" type="text/javascript"></script>
@endsection
@endsection