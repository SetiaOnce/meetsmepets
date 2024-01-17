"use strict";
//Class Definition
var table;
//Load Datatables List SDM
const _loadDataSdm = () => {
    table = $('#dt-sdm').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: false,
        ajax: {
            url: base_url+ 'app_admin/ajax/load_data_sdm',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'POST',
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        LengthChange: true,
        paginate: true,
        pageResize: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'pegawai', name: 'pegawai'},
            { data: 'nik', name: 'nik'},
            { data: 'nip', name: 'nip'},
            { data: 'status_pegawai', name: 'status_pegawai'},
            { data: 'foto', name: 'foto'},
            { data: 'status', name: 'status'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        "columnDefs": [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "25%", "targets": 1, "className": "align-top" },
            { "width": "10%", "targets": 2, "className": "align-top text-center" },
            { "width": "10%", "targets": 3, "className": "align-top text-center" },
            { "width": "10%", "targets": 4, "className": "align-top text-center", "orderable": false },
            { "width": "10%", "targets": 5, "className": "align-top text-center", "orderable": false },
            { "width": "10%", "targets": 6, "className": "align-top text-center", "orderable": false },
            { "width": "10%", "targets": 7, "className": "align-top text-center p-1", "orderable": false },
        ],
        oLanguage: {
            sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
            sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
            sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
            sInfoFiltered: "",
            sProcessing: `<div class="d-flex justify-content-center align-items-center"><span class="spinner-border align-middle me-3"></span> Mohon Tunggu...</div>`,
            sZeroRecords: "Tidak ada Data yang dapat ditampilkan..",
            sLengthMenu: `<select class="mb-2 show-tick form-select-solid" data-width="fit" data-style="btn-sm btn-secondary" data-container="body">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="-1">Semua</option>
            </select>`,
            oPaginate: {
                sPrevious: "Sebelumnya",
                sNext: "Selanjutnya",
            },
        },
        "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
            //Custom Table
            $("#dt-sdm_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
            $('.image-popup').magnificPopup({
                type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
                image: {
                    verticalFit: true
                }
            });
        },
    });
    $("#dt-sdm").css("width", "100%");
}
//Detail Data SDM
const _detailSdm = (idp) => {
    let target = document.querySelector('#card-data'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    // Load Ajax
    $.ajax({
        url: base_url+ 'app_admin/ajax/load_detail_sdm',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        dataType: "JSON",
        data: {
            idp
        }, success: function (data) {
            blockUi.release(), blockUi.destroy();
            let row = data.row[0];
            var status = '<a href="javascript:void(0);"><i class="ki-duotone ki-verify fs-1 text-success"><span class="path1"></span><span class="path2"></span></i></a>';
            if (row.status == 0) {
                var status = '<a href="javascript:void(0);"><i class="ki-duotone ki-cross-circle fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i></a>';
            }
            $('#detailPegawaiDesc').html(`<div class="d-flex flex-wrap flex-sm-nowrap">
                <!--begin: Pic-->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative" title="`+row.nama_pegawai+`">
                        <img src="`+row.foto+`" alt="`+row.nama_pegawai+`">
                    </div>
                </div>
                <!--end::Pic-->
                <!--begin::Info-->
                <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <!--begin::User-->
                        <div class="d-flex flex-column">
                            <!--begin::Name-->
                            <div class="d-flex align-items-center mb-2">
                                <a href="javascript:void(0);" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">`+row.nama_pegawai+`</a>
                                `+status+`
                            </div>
                            <!--end::Name-->
                            <!--begin::Info-->                        
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="javascript:void(0);" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>`+row.email+`
                                </a>
                            </div>
                            <!--end::Info-->
                            <!--begin::Detail-->            
                            <div class="row justify-content-between">
                                <div class="col-md-6">
                                    <div class="kt_detail_nik mt-1">
                                        <div class="fs-6 fw-bold mb-1">NIK :</div>
                                        <div class="fw-semibold text-gray-600">`+row.nik+`</div>
                                    </div>
                                    <div class="kt_detail_statuspegawai mt-1">
                                        <div class="fs-6 fw-bold mb-1">Status Pegawai :</div>
                                        <div class="fw-semibold text-gray-600">`+row.status_kepegawaian+`</div>
                                    </div>
                                    <div class="kt_detail_telepon mt-1">
                                        <div class="fs-6 fw-bold mb-1">Telepon :</div>
                                        <div class="fw-semibold text-gray-600">`+row.telp+`</div>
                                    </div>
                                    <div class="kt_detail_kantor mt-1">
                                        <div class="fs-6 fw-bold mb-1">Kantor :</div>
                                        <div class="fw-semibold text-gray-600">`+row.kantor+`</div>
                                    </div>
                                    <div class="kt_detail_abatanstrukural mt-1">
                                        <div class="fs-6 fw-bold mb-1">Jabatan Struktural :</div>
                                        <div class="fw-semibold text-gray-600">`+row.jabatan_struktural+`</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="kt_detail_nip mt-1">
                                        <div class="fs-6 fw-bold mb-1">NIP :</div>
                                        <div class="fw-semibold text-gray-600">`+row.nip+`</div>
                                    </div>
                                    <div class="kt_detail_tanggallahir mt-1">
                                        <div class="fs-6 fw-bold mb-1">Tanggal Lahir :</div>
                                        <div class="fw-semibold text-gray-600">`+row.tanggal_lahir+`</div>
                                    </div>
                                    <div class="kt_detail_tempatlahir mt-1">
                                        <div class="fs-6 fw-bold mb-1">Tempat Lahir :</div>
                                        <div class="fw-semibold text-gray-600">`+row.tempat_lahir+`</div>
                                    </div>
                                    <div class="kt_detail_subkantor mt-1">
                                        <div class="fs-6 fw-bold mb-1">Sub Kantor :</div>
                                        <div class="fw-semibold text-gray-600">`+row.sub_kantor+`</div>
                                    </div>
                                    <div class="kt_detail_jabatanfungsional mt-1">
                                        <div class="fs-6 fw-bold mb-1">Jabatan Fungsional :</div>
                                        <div class="fw-semibold text-gray-600">`+row.jabatan_fungsional+`</div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="kt_detail_uni mt-1">
                                        <div class="fs-6 fw-bold mb-1">Unit :</div>
                                        <div class="fw-semibold text-gray-600">`+row.unit+`</div>
                                    </div>
                                    <div class="kt_detail_alamat mt-1">
                                        <div class="fs-6 fw-bold mb-1">Alamat :</div>
                                        <div class="fw-semibold text-gray-600">`+row.alamat+`</div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Detail-->                        
                        </div>
                        <!--end::User-->
                    </div>
                    <!--end::Title-->
        
                </div>
                <!--end::Info-->
            </div>`);
            $('#modalDetailSdm').modal('show'), $('#modalDetailSdm .modal-title').html('<i class="ki-outline ki-mouse-square fs-3 me-2 text-dark align-middle"></i>Detail Data Pegawai');
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                console.log("Load data is error!");
            });
        }
    });
}
//Update Status Data SDM
const _updateStatus = (idp, value) => {
    let textLbl = 'Nonaktifkan';
    if(value==1) {
        textLbl = 'Aktifkan';
    }
    let textSwal = textLbl+ ' pegawai sekarang ?';
    Swal.fire({
        title: "",
        html: textSwal,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-data'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ 'app_admin/ajax/data_sdm_update_status',
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp, value
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        table.ajax.reload( null, false );
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        table.ajax.reload( null, false );
                    });
                }
            });
        }
    });
}
//Reset password pegawai
const _resetPassword = (idp) => {
    Swal.fire({
        title: "Reset Password Pegawai?",
        html: "<i>Proses ini akan mengembalikan password pegawai ke default <strong class='text-info'>p@ssword</strong><i>",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-data'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ 'app_admin/ajax/data_sdm_reset_password',
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        table.ajax.reload( null, false );
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        table.ajax.reload( null, false );
                    });
                }
            });
        }
    });
}
// handle status online
const _statusOnline = (data) => {
    table.ajax.reload( null, false );
    let optionsCount = {
        useEasing: false,
        useGrouping: false,
    };
    let countPegawaiOnline = new countUp.CountUp('countPegawaiOnline', data.totalOnline, optionsCount);
    countPegawaiOnline.start();
}
// Class Initialization
jQuery(document).ready(function() {
    _loadDataSdm();
    // realtime online status
    Pusher.logToConsole = true;
    var pusher = new Pusher('99f6980c4b20d6c50787', {
        cluster: 'ap1'
    });
    var channel = pusher.subscribe('status-online');
    channel.bind('App\\Events\\StatusOnline', function(data) {
        _statusOnline(data);
    });
    // filter changer
    $('#filter-dtStatus').change(function(){
        console.log(this.val);
    });
});
