"use strict";
//Class Definition
var save_method;
var table;
//Load Datatables List Pengelola
const _loadDataPengelola = () => {
    table = $('#dt-pengelola').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: false,
        ajax: {
            url: base_url+ 'app_admin/ajax/load_pengelola_portal',
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
            { data: 'nama_pegawai', name: 'nama_pegawai'},
            { data: 'email', name: 'email'},
            { data: 'nik', name: 'nik'},
            { data: 'foto', name: 'foto'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        "columnDefs": [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "25%", "targets": 1, "className": "align-top" },
            { "width": "10%", "targets": 2, "className": "align-top" },
            { "width": "10%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center", "orderable": false },
            { "width": "10%", "targets": 5, "className": "align-top text-center", "orderable": false },
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
            $("#dt-pengelola_length select").selectpicker(),
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
    $("#dt-pengelola").css("width", "100%");
}
//Get Pegawai Active
function loadSelectpicker_pegawai() {
    $.ajax({
        url: base_url+ "select/get_pegawai",
        type: "GET",
        dataType: "JSON",
        data:{
            is_admin:'Y'
        },success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].nama_pegawai + '</option>';
            }
            $('#fid_pegawai').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_pengelola') {
        save_method = '';
        _clearForm(), $('#card-form .card-header .card-title').html('');
    }
    $('#card-form').hide(), $('#card-data').show();
}
//Clear Form Data
const _clearForm = () => {
    if (save_method == "" || save_method == "add_data") {
        $("#form-data")[0].reset(), $('#fid_pegawai').selectpicker('val', ''), $('#detailPegawaiDesc').html('');
        $('[name="id"]').val("");
    } else {
        let idp = $('[name="id"]').val();
        _editData(idp);
    }
}
//Add Data
const _addData = () => {
    save_method = "add_data";
    _clearForm(),
    $("#card-form .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Pengelola</h3>`
    ),
    $("#card-data").hide(), $("#card-form").show();
}
//Save data by Enter
$("#form-data input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save Data Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").attr('data-kt-indicator', 'on').attr('disabled', true);
    let fid_pegawai = $('#fid_pegawai');

    if (fid_pegawai.val() == '') {
        toastr.error('Pegawai masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-fid_pegawai button').removeClass('btn-secondary text-dark').addClass('btn-danger text-light').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger text-light').addClass('btn-secondary text-dark');
		});
        fid_pegawai.focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_data") {
        textConfirmSave = "Tambahkan data sekarang ?";
    }

    Swal.fire({
        title: "",
        text: textConfirmSave,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi, zIndex: 9 });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-data")[0]), ajax_url = base_url+ "app_admin/ajax/pengelola_portal_save";
            if(save_method == 'update_data') {
                ajax_url = base_url+ "app_admin/ajax/pengelola_portal_save";
            }
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            loadSelectpicker_pegawai(),_closeCard('form_pengelola'), table.ajax.reload( null, false );
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            html: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
        }
    });
});
//Delete Data 
const _deleteData = (idp) => {
    Swal.fire({
        title: "",
        html: "Hapus pengelola sekarang?",
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
                url: base_url+ "app_admin/ajax/pengelola_portal_destroy",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        loadSelectpicker_pegawai(), table.ajax.reload( null, false );
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
// Class Initialization
jQuery(document).ready(function() {
    _loadDataPengelola(), loadSelectpicker_pegawai();
    //Load Selectpicker
    $(".selectpicker").selectpicker();
    $('#fid_pegawai').change(function(){
        _detailSdm(this.value)
    });
});
//detail data
const _detailSdm = (idp) => {
    // Load Ajax
    $.ajax({
        url: base_url+ 'app_admin/ajax/load_detail_sdm',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        dataType: "JSON",
        data: {
            idp
        }, success: function (data) {
            let row = data.row[0];
            $('#detailPegawaiDesc').html(`<div class="d-flex flex-wrap flex-sm-nowrap border border-dashed border-gray-300 rounded px-2 py-3 mb-3">
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
                            </div>
                            <!--end::Name-->
                            <!--begin::Info-->                        
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="javascript:void(0);" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>`+row.email+`
                                </a>
                                <a href="javascript:void(0);" class="d-flex align-items-center text-gray-500 text-hover-primary  mb-2">
                                    <i class="ki-duotone ki-profile-circle fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>`+row.jabatan_fungsional+`
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
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="kt_detail_subkantor mt-1">
                                        <div class="fs-6 fw-bold mb-1">Sub Kantor :</div>
                                        <div class="fw-semibold text-gray-600">`+row.sub_kantor+`</div>
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
        }
    });
}