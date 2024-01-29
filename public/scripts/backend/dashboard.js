"use strict";
// Class Definition
//Load Content Widget01
const _loadWidget01 = () => {
    $.ajax({
        url: base_url+ "api/dashboard/first_widget",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        data: {
            is_widget: true,
            widget_number: 1
        },
        dataType: 'JSON',
        success: function (data) {
            let userInfo = data.userProfle,
                // counter = data.counter,
                userInfoContent = `<div class="d-flex flex-center flex-column py-5 mb-3">
                    <a href="` +userInfo.thumb_url+ `" class="image-popup" title="` +userInfo.name+ `">
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <img src="` +userInfo.thumb_url+ `" alt="user-image" />
                        </div>
                    </a>
                    <a href="javascript:void(0);" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3 text-center"> ` +userInfo.name+ ` </a>
                    <div class="mb-5">
                        <div class="badge badge-lg badge-light-primary d-inline">Administrator</div>
                    </div>
                    <div class="m-0">
                        <a href="` +base_url+ `app_admin/` +userInfo.username+ `" class="btn btn-sm btn-light btn-active-light-primary">
                            <i class="bi bi-person-gear fs-3 me-1"></i>Profil Saya
                        </a>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="fs-6">
                    <div class="mt-5">
                        <div class="fw-bold">Email</div>
                        <div class="text-gray-600"><a href="javascript:void(0);" class="text-gray-600 text-hover-primary">` +userInfo.email+ `</a></div>
                    </div>
                    <div class="mt-5">
                        <div class="fw-bold">Username</div>
                        <div class="text-gray-600">` +userInfo.username+ `</div>
                    </div>
                </div>`;
            $('#card-userInfo .card-body').html(userInfoContent);
            // let optionsCount = {
            //     useEasing: false,
            //     useGrouping: false,
            // };
            // let countPegawaiAsn = new countUp.CountUp('countPegawaiAsn', counter.pegawai_asn, optionsCount);
            // countPegawaiAsn.start();
            // let countPegawaiHonorer = new countUp.CountUp('countPegawaiHonorer', counter.pegawai_honorer, optionsCount);
            // countPegawaiHonorer.start();
            // let countPegawaiPengelola = new countUp.CountUp('countPegawaiPengelola', counter.pegawai_pengelola, optionsCount);
            // countPegawaiPengelola.start();
        }, complete: function(data) {
            $('.image-popup').magnificPopup({
                type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
                image: {
                    verticalFit: true
                }
            });
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadWidget01();
});