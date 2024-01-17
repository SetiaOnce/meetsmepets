"use strict";
//Class Definition
var save_method;
var table;
//Load Datatables List aplikasi
const _loadDataAplikasi = () => {
    table = $('#dt-aplikasi').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: false,
        ajax: {
            url: base_url+ 'app_admin/ajax/load_aplikasi',
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
            { data: 'nama_aplikasi', name: 'nama_aplikasi'},
            { data: 'access_key', name: 'access_key'},
            { data: 'icon', name: 'icon'},
            { data: 'status', name: 'status'},
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
            $("#dt-aplikasi_length select").selectpicker(),
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
    $("#dt-aplikasi").css("width", "100%");
}
//Load File Dropify
const _loadDropifyFile = (url_file, paramsId) => {
    if (url_file == "") {
        let drEvent1 = $(paramsId).dropify({
            defaultFile: '',
        });
        drEvent1 = drEvent1.data('dropify');
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = '';
        drEvent1.destroy();
        drEvent1.init();
    } else {
        let drEvent1 = $(paramsId).dropify({
            defaultFile: url_file,
        });
        drEvent1 = drEvent1.data('dropify');
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = url_file;
        drEvent1.destroy();
        drEvent1.init();
    }
}
//begin::Dropify
$('.dropify-upl').dropify({
    messages: {
        'default': '<span class="btn btn-sm btn-secondary">Drag/ drop file atau Klik disini</span>',
        'replace': '<span class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Drag/ drop atau Klik untuk menimpa file</span>',
        'remove':  '<span class="btn btn-sm btn-danger"><i class="las la-trash-alt"></i> Reset</span>',
        'error':   'Ooops, Terjadi kesalahan pada file input'
    }, error: {
        'fileSize': 'Ukuran file terlalu besar, Max. ( {{ value }} )',
        'minWidth': 'Lebar gambar terlalu kecil, Min. ( {{ value }}}px )',
        'maxWidth': 'Lebar gambar terlalu besar, Max. ( {{ value }}}px )',
        'minHeight': 'Tinggi gambar terlalu kecil, Min. ( {{ value }}}px )',
        'maxHeight': 'Tinggi gambar terlalu besar, Max. ( {{ value }}px )',
        'imageFormat': 'Format file tidak diizinkan, Hanya ( {{ value }} )'
    }
});
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_aplikasi') {
        save_method = '';
        _clearForm(), $('#card-form .card-header .card-title').html('');
    }
    $('#card-form').hide(), $('#card-data').show();
}
//Clear Form Data
const _clearForm = () => {
    if (save_method == "" || save_method == "add_data") {
        $("#form-data")[0].reset(),_loadDropifyFile('', '#icon');
        $('[name="id"]').val(""), $('#iGroup-status').hide();
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
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Aplikasi</h3>`
    ),
    $("#card-data").hide(), $("#card-form").show();
}
//Edit Data
const _editData = (idp) => {
    save_method = "update_data";
    $("#form-data")[0].reset(), _loadDropifyFile('', '#icon');
    $('#iGroup-status').show();
    let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ "app_admin/ajax/aplikasi_edit",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('[name="id"]').val(data.row.id),
                $('#nama_aplikasi').val(data.row.nama_aplikasi);
                $('#link_url').val(data.row.link_url);
                $('#link_login').val(data.row.link_login);
                $('#keterangan').val(data.row.keterangan);
                _loadDropifyFile(data.url_icon, '#icon');
                //status 
                if (data.row.status == 1) {
                    $('#status').prop('checked', true),
                    $('#iGroup-status .form-check-label').text('AKTIF');
                } else {
                    $('#status').prop('checked', false),
                    $('#iGroup-status .form-check-label').text('TIDAK AKTIF');
                }
                $("#card-form .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Aplikasi Layanan</h3>`
                ),
                $("#card-data").hide(), $("#card-form").show();
            } else {
                Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            console.log("load data is error!");
            Swal.fire({
                title: "Ooops!",
                text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                icon: "error",
                allowOutsideClick: false,
            });
        },
    });
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
    let nama_aplikasi = $('#nama_aplikasi'),
        link_url = $('#link_url'),
        link_login = $('#link_login'),
        icon = $('#icon'), icon_preview = $('#iGroup-icon .dropify-preview .dropify-render').html(),
        keterangan = $('#keterangan');

    if (nama_aplikasi.val() == '') {
        toastr.error('Nama aplikasi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $(nama_aplikasi).addClass('is-invalid').stop().delay(1500).queue(function () {$(nama_aplikasi).removeClass('is-invalid');});
        nama_aplikasi.focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (link_url.val() == '') {
        toastr.error('Link aplikasi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $(link_url).addClass('is-invalid').stop().delay(1500).queue(function () {$(link_url).removeClass('is-invalid');});
        link_url.focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (link_login.val() == '') {
        toastr.error('Link login aplikasi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $(link_login).addClass('is-invalid').stop().delay(1500).queue(function () {$(link_login).removeClass('is-invalid');});
        link_login.focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (keterangan.val() == '') {
        toastr.error('Keterangan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $(keterangan).addClass('is-invalid').stop().delay(1500).queue(function () {$(keterangan).removeClass('is-invalid');});
        keterangan.focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    }if (icon_preview == '') {
        toastr.error('Icon aplikasi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-icon .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        icon.focus();
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
            let formData = new FormData($("#form-data")[0]), ajax_url = base_url+ "app_admin/ajax/aplikasi_save";
            if(save_method == 'update_data') {
                ajax_url = base_url+ "app_admin/ajax/aplikasi_update";
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
                        Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_aplikasi'), table.ajax.reload( null, false );
                        });
                    } else {
                        if(data.pesan_code=='format_inputan') {   
                            Swal.fire({ title: "Ooops!", html: data.pesan_error[0], icon: "warning", allowOutsideClick: false});
                        } else {
                            Swal.fire("Ooops!", "Gagal melakukan proses data, mohon cek kembali isian pada form yang tersedia.", "error");
                        }
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
//Update Status Data
const _updateStatus = (idp, value) => {
    let textLbl = 'Nonaktifkan';
    if(value==1) {
        textLbl = 'Aktifkan';
    }
    let textSwal = textLbl+ ' aplikasi sekarang ?';
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
                url: base_url+ 'app_admin/ajax/aplikasi_update_status',
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
// Class Initialization
jQuery(document).ready(function() {
    _loadDataAplikasi();
    // status change
    $('#status').change(function() {
        if(this.checked) {
            $('#iGroup-status .form-check-label').text('AKTIF');
        }else{
            $('#iGroup-status .form-check-label').text('TIDAK AKTIF');
        }
    });
});