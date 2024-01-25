"use strict";
// Class Definition
const loadApp = function() {
    //load Layanan
    const _headBanner = () => {
        $.ajax({
            url: BASE_URL+ "/front/banner/view",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#sectionBanner').attr('style', 'background-image: url(' +data.row.background_url+ ');');
                $('#sectionBanner').html(`<span class="mask" style="background: linear-gradient(360deg, rgba(26, 115, 232, 0.918) 0%, rgba(146, 145, 227, 0.959) 45%, rgba(241,250,238,0.24711134453781514) 100%);"></span>
                <div class="container">
                  <div class="row">
                    <div class="col-lg-12 text-center mx-auto">
                      <h1 class="text-white pt-3 mt-n5">`+data.row.head_title+`</h1>
                      <p class="lead text-white mt-3">`+data.row.description+`</p>
                    </div>
                  </div>
                </div>`);
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    }
    //load Aplikasi
    const _AplikasiContent = () => {
        let headerTitle = `<span class="placeholder col-6 rounded mt-5 mb-4" style="height: 25px"></span>`;
        $('#AplikasiSection .headTitle').html(headerTitle);
        let bodyContent = '';
        let i;
        for (i = 0; i < 3; i++) {
            bodyContent += `<div class="col-md-4 mb-2">
                <h5 class="placeholder-glow">
                    <span class="placeholder col-12 rounded" style="height: 72px;"></span>
                </h5>        
            </div>`;
        }
        $('#AplikasiSection .bodyContent').append(bodyContent);
        $.ajax({
            url: BASE_URL+ "/front/aplikasi/view",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#AplikasiSection .headTitle').html(`<h3 class="mt-5 mb-4">Aplikasi Layanan</h3>`);
                let rows = data.row;
                bodyContent = '';
                $.each(rows, function(key, row) {
                    bodyContent += `<div class="col-md-4 mb-2">
                    <a href="`+row.link_url+`" target="_blank" title="`+row.nama_aplikasi+`" class="card  card-background card-background-mask-dark align-items-center">
                        <div class="full-background cursor-pointer" style="background-image: url('`+row.icon_url+`')"></div>
                        <div class="card-body text-center">
                            <h5 class="text-white mb-0 fs-6">`+row.nama_aplikasi+`</h5>
                        </div>
                    </a>
                </div>`;
                });
                $('#AplikasiSection .bodyContent').html(bodyContent);
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    }
    //load Layanan
    const _LayananContent = () => {
        let headerTitle = `<span class="placeholder col-5 rounded" style="height: 45px"></span>`;
        $('#LayananSection .headTitle').html(headerTitle);
        let bodyContent = '';
        let i;
        for (i = 0; i < 4; i++) {
            bodyContent += `<div class="col-lg-6 col-12">
            <div class="card card-profile mt-4">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12 mt-n5">
                        <a href="javascript:;">
                            <div class="p-3 pe-md-0">
                                <h5 class="placeholder-glow">
                                    <span class="placeholder col-10 rounded" style="height: 125px;"></span>
                                </h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-8 col-md-6 col-12 my-auto">
                        <div class="card-body ps-lg-0">
                            <h5 class="placeholder-glow">
                                <span class="placeholder col-10 rounded" style="height: 20px;"></span>
                                <span class="placeholder col-8 rounded"></span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        }
        $('#LayananSection .bodyContent').append(bodyContent);
        $.ajax({
            url: BASE_URL+ "/front/layanan/view",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#LayananSection .headTitle').html(`<h3 class="text-white z-index-1 position-relative">SOP Layanan</h3>`);
                let rows = data.row;
                bodyContent = '';
                $.each(rows, function(key, row) {
                    bodyContent += `<div class="col-lg-6 col-12">
                    <div class="card card-profile mt-4">
                        <div class="row" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="`+row.description+`">
                            <div class="col-lg-4 col-md-6 col-12 mt-n5">
                                <a href="javascript:void(0);" >
                                    <div class="p-3 pe-md-0">
                                        <img class="w-100 border-radius-md shadow-lg" src="`+row.thumbnail_url+`" alt="`+row.thumbnail+`">
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-8 col-md-6 col-12 my-auto" >
                                <a href="javascript:void(0);">
                                    <div class="card-body ps-lg-0">
                                        <h5 class="mb-0">`+row.title+`</h5>
                                        <p class="mb-0 text-justify">`+row.description_short+`</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`;
                });
                $('#LayananSection .bodyContent').html(bodyContent);
            }, complete: function (data) {
                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                  return new bootstrap.Popover(popoverTriggerEl)
                })
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    }
    // Public Functions
    return {
        // public functions
        init: function() {
            _headBanner();
            _AplikasiContent();
            _LayananContent();
        }
    };
}();
// maintenance tur
$('#btn-search').click(function(){
    Swal.fire({title: "Maaf!", text: "Fitur ini sedang dalam proses pengembangan!", icon: "warning"
    });
});
// Class Initialization
jQuery(document).ready(function() {
    loadApp.init();
});