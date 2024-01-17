"use strict";
// Class Definition
//Load Content Widget01
const _loadWidget01 = () => {
    $.ajax({
        url: base_url+ "app_admin/dashboard/user_profile",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        data: {
            is_widget: true,
            widget_number: 1
        },
        dataType: 'JSON',
        success: function (data) {
            let userInfo = data.userProfle,
                counter = data.counter,
                userInfoContent = `<div class="d-flex flex-center flex-column py-5 mb-3">
                    <a href="` +userInfo.foto+ `" class="image-popup" title="` +userInfo.nama_pegawai+ `">
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <img src="` +userInfo.foto+ `" alt="user-image" />
                        </div>
                    </a>
                    <a href="javascript:void(0);" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3 text-center"> ` +userInfo.nama_pegawai+ ` </a>
                    <div class="mb-5">
                        <div class="badge badge-lg badge-light-primary d-inline">Pengelola</div>
                    </div>
                    <div class="m-0">
                        <a href="` +base_url+ `app_admin/` +userInfo.nama_pegawai+ `" class="btn btn-sm btn-light btn-active-light-primary">
                            <i class="bi bi-person-gear fs-3 me-1"></i>Profil Saya
                        </a>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="fs-6">
                    <div class="mt-5">
                        <div class="fw-bold">Nik</div>
                        <div class="text-gray-600">` +userInfo.nik+ `</div>
                    </div>
                    <div class="mt-5">
                        <div class="fw-bold">Email</div>
                        <div class="text-gray-600"><a href="javascript:void(0);" class="text-gray-600 text-hover-primary">` +userInfo.email+ `</a></div>
                    </div>
                    <div class="mt-5">
                        <div class="fw-bold">Hp/ Telp.</div>
                        <div class="text-gray-600">` +userInfo.telp+ `</div>
                    </div>
                </div>`;
            $('#card-userInfo .card-body').html(userInfoContent);
            let optionsCount = {
                useEasing: false,
                useGrouping: false,
            };
            let countPegawaiAsn = new countUp.CountUp('countPegawaiAsn', counter.pegawai_asn, optionsCount);
            countPegawaiAsn.start();
            let countPegawaiHonorer = new countUp.CountUp('countPegawaiHonorer', counter.pegawai_honorer, optionsCount);
            countPegawaiHonorer.start();
            let countPegawaiPengelola = new countUp.CountUp('countPegawaiPengelola', counter.pegawai_pengelola, optionsCount);
            countPegawaiPengelola.start();
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
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    var pusher = new Pusher('99f6980c4b20d6c50787', {
        cluster: 'ap1'
    });
    var channel = pusher.subscribe('sincronize-sik');
    channel.bind('App\\Events\\ApiSoapsik', function(data) {
        _sincronizeSoapSik();
    });
});

const _sincronizeSoapSik = () => {
    let target = document.querySelector('#card-userInfo'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: base_url+ "app_admin/ajax/sincronize_sik",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            console.log(data);
        },error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
