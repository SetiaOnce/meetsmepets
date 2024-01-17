"use strict";
// Class Definition
// Statistik Login
function _loadStatistikLogin() {
    $.ajax({
        url: base_url+ "app_pegawai/dashboard/load_statistik_login",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            Highcharts.chart('statistik-login', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                },
                title: {
                    text: 'STATISTIK LOGIN APLIKASI'
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var value = dataset.data[tooltipItem.index];
                            return value.toFixed(0); // Show only the integer value
                        }
                    }
                },
                plotOptions: {
                    pie: {
                        innerSize: 90, // Adjust innerSize to create a donut
                        depth: 45,
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.y}'
                        }
                    },
                    series: {
                        point: {
                            events: {
                                click: function(event) {
                                    _viewDetailStatistikLogin(this.name)
                                }
                            }
                        }
                    }
                },
                series: [{
                    name: 'Jumlah',
                    data: data
                }]
            });
        },error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data error!');
        }
    });
};
//Load application
const _loadApplications = () => {
    let bodyContent = '';
    let i;
    for (i = 0; i < 3; i++) {
        bodyContent += `<h3 class="fs-3 text-center placeholder-glow w-100 mb-4">
        <span class="placeholder col-12 rounded" style="height: 90px"></span>
    </h3>`;
    }
    $('#card-application .card-body').append(bodyContent);
    $.ajax({
        url: base_url+ "app_pegawai/dashboard/aplication",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            let rows = data.row;
            bodyContent = '';
            if (rows.length == 0) {
                bodyContent += `<div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row mb-6 align-items-center">
                    <!--begin::Icon-->
                    <i class="ki-solid ki-information-5 fs-2hx text-light me-4 "></i>
                    <!--end::Icon-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                        <!--begin::Title-->
                        <h6 class="text-light"><i>Tidak ada aplikasi yang bisa anda akses ...</i></h6>
                        <!--end::Title-->
                    </div>
                    <!--end::Wrapper-->
                </div>`;
            }else{
                $.each(rows, function(key, row) {
                    var icon = 'bi bi-train-front';
                    bodyContent += `<a href="`+row.link_login+`" target="_blank" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" title="Klik untuk menuju ke aplikasi!">
                        <div class="d-flex rounded p-6 mb-4 alert alert-dismissible bg-primary d-flex flex-column flex-sm-row">
                            <!--begin::Block-->
                            <div class="d-flex align-items-center flex-grow-1 me-2 me-sm-5 ">
                                <!--begin::Symbol-->
                                <div class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light">
                                        <i class="`+icon+` fs-2qx"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Section-->
                                <div class="me-2">
                                    <span class="text-light fs-6 fw-bold">`+row.nama_aplikasi+`</span><br>
                                    <span class="badge badge-light">`+row.nama_level+`</span>
                                </div>
                                <!--end::Section-->  
                            </div>
                            <!--end::Block-->
                        </div>
                    </a>`;
                });
            }
            $('#card-application .card-body').html(bodyContent);
        }, complete: function(data) {
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadStatistikLogin();
    _loadApplications();
});