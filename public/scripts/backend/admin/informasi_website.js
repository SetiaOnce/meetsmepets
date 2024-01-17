"use strict";
// Class Definition
var table;
//Keyword Select2
$('#keyword').select2({
    dropdownAutoWidth: true,
    tags: true,
    maximumSelectionLength: 10,
    placeholder: 'Isi keyword/ kata kunci situs ...',
    tokenSeparators: [','],
    width: '100%',
    language: { noResults: () => 'Gunakan tanda koma (,) sebagai pemisah tag'}
});
//CopyRight Input
$('#copyright').summernote({
    placeholder: 'Isi copyright situs ...',
    toolbar: [
        ['style', ['bold', 'italic', 'underline']], ['insert', ['link']], ['view', ['codeview']]
    ],
    height: 100, minHeight: null, maxHeight: null, dialogsInBody: false, focus: false, popatmouse: false, lang: 'id-ID'
});
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
//end::Dropify
const _loadEditSiteInfo = () => {
    $("#form-editSiteInfo")[0].reset(),
    $("#keyword").html('').trigger('change'),
    $('#copyright').summernote('code', ''),
    _loadDropifyFile('', '#headpublic_logo'),
    _loadDropifyFile('', '#login_logo'),
    _loadDropifyFile('', '#login_bg'),
    _loadDropifyFile('', '#headbackend_logo'),
    _loadDropifyFile('', '#headbackend_logo_dark'),
    _loadDropifyFile('', '#headbackend_icon');
    _loadDropifyFile('', '#headbackend_icon_dark');
    let target = document.querySelector('#cardSiteInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: base_url+ "app_admin/content/ajax/load_website_information",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            $('#name').val(data.row.name),
            $('#short_name').val(data.row.short_name),
            $('#description').val(data.row.description);
            $('#about_portal').val(data.row.about_portal);
            //Keyword System
            let selected = '', i;
            for (i = 0; i < data.keyword_explode.length; i++) {
                selected += '<option value="' + data.keyword_explode[i] + '" selected>' + data. keyword_explode[i] + '</option>';
            }
            $("#keyword").html(selected).trigger('change');
            //Summernote CopyRight
            let copyright = data.row.copyright;
            $('#copyright').summernote('code', copyright);
            _loadDropifyFile(data.headpublic_logo_url, '#headpublic_logo'),
            _loadDropifyFile(data.login_logo_url, '#login_logo'),
            _loadDropifyFile(data.login_bg_url, '#login_bg'),
            _loadDropifyFile(data.headbackend_logo_url, '#headbackend_logo'),
            _loadDropifyFile(data.headbackend_logo_dark_url, '#headbackend_logo_dark'),
            _loadDropifyFile(data.headbackend_icon_url, '#headbackend_icon');
            _loadDropifyFile(data.headbackend_icon_dark_url, '#headbackend_icon_dark');
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
            blockUi.release(), blockUi.destroy();
        }
    });
}
// Handle Button Reset / Batal Form Site Info
$('#btn-resetFormSiteInfo').on('click', function (e) {
    e.preventDefault();
    _loadEditSiteInfo();
});
//Handle Enter Submit Form Edit Site Info
$("#form-editSiteInfo input").keyup(function(event) {
    if (event.keyCode == 13 || event.key === 'Enter') {
        $("#btn-saveSiteInfo").click();
    }
});
// Handle Button Save Form Site Info
$('#btn-saveSiteInfo').on('click', function (e) {
    e.preventDefault();
    $('#btn-saveSiteInfo').attr('data-kt-indicator', 'on').attr('disabled', true);
    let name = $('#name'),
        short_name = $('#short_name'),
        description = $('#description'),
        keyword = $('#keyword'),
        about_portal = $('#about_portal'),
        copyright = $('#copyright'),
        headpublic_logo = $('#headpublic_logo'), headpublic_logo_preview = $('#iGroup-headpublic_logo .dropify-preview .dropify-render').html(),
        login_logo = $('#login_logo'), login_logo_preview = $('#iGroup-login_logo .dropify-preview .dropify-render').html(),
        login_bg = $('#login_bg'), login_bg_preview = $('#iGroup-login_bg .dropify-preview .dropify-render').html(),
        headbackend_logo = $('#headbackend_logo'), headbackend_logo_preview = $('#iGroup-headbackend_logo .dropify-preview .dropify-render').html(),
        headbackend_logo_dark = $('#headbackend_logo_dark'), headbackend_logo_dark_preview = $('#iGroup-headbackend_logo_dark .dropify-preview .dropify-render').html(),
        headbackend_icon = $('#headbackend_icon'), headbackend_icon_preview = $('#iGroup-headbackend_icon .dropify-preview .dropify-render').html(),
        headbackend_icon_dark = $('#headbackend_icon_dark'), headbackend_icon_dark_preview = $('#iGroup-headbackend_icon_dark .dropify-preview .dropify-render').html();

    if (name.val() == '') {
        toastr.error('Nama situs masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        name.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (short_name.val() == '') {
        toastr.error('Nama alias/ nama pendek situs masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        short_name.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (description.val() == '') {
        toastr.error('Deskripsi situs masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        description.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (keyword.val() == '' || keyword.val() == null) {
        toastr.error('Keyword/ kata kunci situs masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        keyword.focus().select2('open');
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (about_portal.val() == '') {
        toastr.error('Tentang portal masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        about_portal.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (copyright.summernote('isEmpty')) {
        toastr.error('Copyright situs masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        copyright.summernote('focus');
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (headpublic_logo_preview == '') {
        toastr.error('Logo header publik light mode masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-headpublic_logo .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        headpublic_logo.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (login_logo_preview == '') {
        toastr.error('Logo login masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-login_logo .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        login_logo.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (login_bg_preview == '') {
        toastr.error('Gambar background login masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-login_bg .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        login_bg.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (headbackend_logo_preview == '') {
        toastr.error('Logo backend light mode masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-headbackend_logo .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        headbackend_logo.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (headbackend_logo_dark_preview == '') {
        toastr.error('Logo backend dark mode masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-headbackend_logo_dark .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        headbackend_logo_dark.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (headbackend_icon_preview == '') {
        toastr.error('Logo icon backend light mode masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-headbackend_icon .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        headbackend_icon.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (headbackend_icon_dark_preview == '') {
        toastr.error('Logo icon backend dark mode masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-headbackend_icon_dark .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        headbackend_icon_dark.focus();
        $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    }

    Swal.fire({
        title: "",
        text: "Simpan perubahan sekarang ?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#cardSiteInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi, zIndex: 9 });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($('#form-editSiteInfo')[0]),
                ajax_url= base_url+ "app_admin/content/ajax/website_information_update",
                get_copyright = formData.get('copyright');
            formData.set('copyright', encodeURIComponent(encodeURIComponent(get_copyright)));
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status==true){
                        Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false}).then(function (result) {
                            _loadEditSiteInfo();
                        });
                    } else {
                        Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false});
                }
            });
        }else{
            $('#btn-saveSiteInfo').removeAttr('data-kt-indicator').attr('disabled', false);
        }
    });
});
// handle manage tautan
const _manageTautan = () => {
    $('#modalManageTautan').modal('show'), $('#modalManageTautan .modal-title').html('<i class="las la-link fs-3 me-2 text-dark align-middle"></i>Tautan Aplikasi');
    _dataTauatan();
}
const _openFormSrc = () => {
    $('#div-formSrc').show(), $('#btn-openFormSrc').hide();
}
const _closeFormSrc = () => {
    $("#form-tautan")[0].reset();
    $('#div-formSrc').hide(), $('#btn-openFormSrc').show();
}
// datatable tautan
const _dataTauatan = () => {
    table = $('#dt-tautan').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: false,
        ajax: {
            url: base_url+ "app_admin/content/ajax/tautan_load",
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
            { data: 'nama_tautan', name: 'nama_tautan'},
            { data: 'link_tautan', name: 'link_tautan'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        "columnDefs": [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "40%", "targets": 1, "className": "align-top" },
            { "width": "40%", "targets": 2, "className": "align-top" },
            { "width": "15%", "targets": 3, "className": "align-top text-center", "orderable": false },
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
            $("#dt-tautan_length select").selectpicker(),
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
    $("#dt-tautan").css("width", "100%");
}
//Save Data Form Tautan
$("#btn-saveSrc").on("click", function (e) {
    e.preventDefault();
    $("#btn-saveSrc").attr('data-kt-indicator', 'on').attr('disabled', true);
    let nama_tautan = $('#nama_tautan'),
        link_tautan = $('#link_tautan');

    if (nama_tautan.val() == '') {
        toastr.error('Nama tautan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $(nama_tautan).addClass('is-invalid').stop().delay(1500).queue(function () {$(nama_tautan).removeClass('is-invalid');});
        // nama_tautan.focus();
        $('#btn-saveSrc').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (link_tautan.val() == '') {
        toastr.error('Link tautan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $(link_tautan).addClass('is-invalid').stop().delay(1500).queue(function () {$(link_tautan).removeClass('is-invalid');});
        // link_tautan.focus();
        $('#btn-saveSrc').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } 

    var textConfirmSave = "Tambahkan data sekarang ?";
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
            let formData = new FormData($("#form-tautan")[0]), ajax_url = base_url+ "app_admin/content/ajax/tautan_save";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $("#btn-saveSrc").removeAttr('data-kt-indicator').attr('disabled', false);
                    if (data.status == true) {
                        Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false,
                        }).then(function (result) {
                            $('#totalTautan').html(data.totalTautan);
                            _closeFormSrc(), table.ajax.reload( null, false );
                        });
                    } else {
                        if(data.pesan_code=='format_inputan') {   
                            Swal.fire({ title: "Ooops!", html: data.pesan_error[0], icon: "warning", allowOutsideClick: false});
                        } else {
                            Swal.fire("Ooops!", "Gagal melakukan proses data, mohon cek kembali isian pada form yang tersedia.", "error");
                        }
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-saveSrc").removeAttr('data-kt-indicator').attr('disabled', false);
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $("#btn-saveSrc").removeAttr('data-kt-indicator').attr('disabled', false);
        }
    });
});
//Delete tautan
const _deleteTautan = (idp) => {
    Swal.fire({
        title: "",
        html: "Hapus tautan sekarang?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            // Load Ajax
            $.ajax({
                url: base_url+ "app_admin/content/ajax/tautan_destroy",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp
                }, success: function (data) {
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        $('#totalTautan').html(data.totalTautan);
                        table.ajax.reload( null, false );
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
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
    _loadEditSiteInfo();
});