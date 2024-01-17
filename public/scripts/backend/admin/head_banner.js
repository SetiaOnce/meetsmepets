"use strict";
// Class Definition
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
const _loadHeadBanner = () => {
    $("#form-editHeadBanner")[0].reset(),
    _loadDropifyFile('', '#background_banner');
    let target = document.querySelector('#cardHeadBanner'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: base_url+ "app_admin/content/ajax/load_head_banner",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            $('#head_title').val(data.row.head_title),
            $('#description').val(data.row.description);
            _loadDropifyFile(data.headbanner_url, '#background_banner');
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
            blockUi.release(), blockUi.destroy();
        }
    });
}
// Handle Button Reset / Batal Form Form Banner
$('#btn-resetFormHeadBanner').on('click', function (e) {
    e.preventDefault();
    _loadHeadBanner();
});
//Handle Enter Submit Form Head Banner
$("#form-editHeadBanner input").keyup(function(event) {
    if (event.keyCode == 13 || event.key === 'Enter') {
        $("#btn-saveHeadBanner").click();
    }
});
// Handle Button Save Form Site Info
$('#btn-saveHeadBanner').on('click', function (e) {
    e.preventDefault();
    $('#btn-saveHeadBanner').attr('data-kt-indicator', 'on').attr('disabled', true);
    let head_title = $('#head_title'),
        description = $('#description'),
        background_banner = $('#background_banner'), background_banner_preview = $('#iGroup-background_banner .dropify-preview .dropify-render').html();

    if (head_title.val() == '') {
        toastr.error('Head title masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        head_title.focus();
        $('#btn-saveHeadBanner').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (description.val() == '') {
        toastr.error('Deskripsi pendek situs masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        description.focus();
        $('#btn-saveHeadBanner').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    }if (background_banner_preview == '') {
        toastr.error('Backgroun banner masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-background_banner .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        background_banner.focus();
        $('#btn-saveHeadBanner').removeAttr('data-kt-indicator').attr('disabled', false);
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
            let target = document.querySelector('#cardHeadBanner'), blockUi = new KTBlockUI(target, {message: messageBlockUi, zIndex: 9 });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($('#form-editHeadBanner')[0]),ajax_url= base_url+ "app_admin/content/ajax/head_banner_update";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-saveHeadBanner').removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status==true){
                        Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false}).then(function (result) {
                            _loadHeadBanner();
                        });
                    } else {
                        Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-saveHeadBanner').removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false});
                }
            });
        }else{
            $('#btn-saveHeadBanner').removeAttr('data-kt-indicator').attr('disabled', false);
        }
    });
});
// Class Initialization
jQuery(document).ready(function() {
    _loadHeadBanner();
});