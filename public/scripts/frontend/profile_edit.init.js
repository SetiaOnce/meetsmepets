//Owner INFO
const _loadOwnerProfile = () => {
	$.ajax({
        url: base_url+ "api/owner_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let userInfo = data.row;
            _loadDropifyFile(userInfo.thumb_url, '#foto_profiles');
            // interest
            var interest = '';
            if(userInfo.interest){
                var interest = userInfo.interest.split(", ");
            }
            $('#interest').selectpicker('val', interest);
            $('.full_name').html(userInfo.name);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// slectpicker interest
function loadSelectpicker_interest() {
    $.ajax({
        url: base_url+ "api/select/interest",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var data = data.row;
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].text + '</option>';
            }
            $('#interest').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
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
        'default': '<span class="btn btn-sm btn-secondary">Drag/ drop file or click here</span>',
        'replace': '<span class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Drag/ drop or click for override file</span>',
        'remove':  '<span class="btn btn-sm btn-danger"><i class="las la-trash-alt"></i> Reset</span>',
        'error':   'Ooops, An error occurred in the input file'
    }, error: {
        'fileSize': 'File size is too large, Max. ( {{ value }} )',
        'minWidth': 'Image width is too small, Min. ( {{ value }}}px )',
        'maxWidth': 'Image width is too large, Max. ( {{ value }}}px )',
        'minHeight': 'Image height is too small, Min. ( {{ value }}}px )',
        'maxHeight': 'Image height is too large, Max. ( {{ value }}px )',
        'imageFormat': 'File format not permitted, Only ( {{ value }} )'
    }
});
const _loadPetsAlbum = () => {
    $.ajax({
        url: base_url+ "api/profile/load_petsalbum",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            if(data.row != null){
                $('.imagePreview1').css('background-image', 'url('+data.row.image1_url+')');
                $('.imagePreview2').css('background-image', 'url('+data.row.image2_url+')');
                $('.imagePreview3').css('background-image', 'url('+data.row.image3_url+')');
                $('.imagePreview4').css('background-image', 'url('+data.row.image4_url+')');
                $('.imagePreview5').css('background-image', 'url('+data.row.image5_url+')');
                $('.imagePreview6').css('background-image', 'url('+data.row.image6_url+')');
                $('.remove-btn1').removeClass('d-none');
                $('.remove-btn2').removeClass('d-none');
                $('.remove-btn3').removeClass('d-none');
                $('.remove-btn4').removeClass('d-none');
                $('.remove-btn5').removeClass('d-none');
                $('.remove-btn6').removeClass('d-none');
            }else{
                $('.imagePreview').css('background-image', 'url('+base_url+'dist/img/drop-bx.png)');
                $('.remove-btn').addClass('d-none');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('log petsalbum is error');
        }
    });
};
// save full name
$('#btn-name').on('click', function (e) {
    e.preventDefault();
    $('#btn-name').html('Please Wait...').attr('disabled', true);
    var name = $('#full_name');
    if (name.val() == '') {
        toastr.error('Enter your phone number', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        name.focus();
        $('#btn-name').html('Next').attr('disabled', false);
        return false;
    }
    name = name.val()
    $.ajax({
        url: base_url+ "api/profile/save_fullname",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            name,
        },
        dataType: "JSON",
        success: function (data) {
            $('#btn-name').html('Next').attr('disabled', false);
            if (data.status==true){
                _loadOwnerProfile();
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-name').html('Next').attr('disabled', false);
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// foto profiles save
$('#foto_profiles').on('change', function() {
    var formData = new FormData($('#form-fotoProfiles')[0]);
    $.ajax({
        url: base_url+ "api/profile/save_fotoprofiles",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            if (data.status==true){
                
            }else{
                if(data.row[0]) {   
                    Swal.fire({title: "Ooops!", text: data.row[0], icon: "warning", allowOutsideClick: false});
                    _loadPetsAlbum();
                } else {
                    Swal.fire("Ooops!", "Failed to process the data, please check the fields again on the form provided.", "error");
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// Pet album save
$('.imageUpload').on('change', function() {
    var formData = new FormData($('#form-images')[0]);
    $.ajax({
        url: base_url+ "api/profile/save_petsimages",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            if (data.status==true){
                $('.imageUpload').val('');
            }else{
                if(data.row[0]) {   
                    Swal.fire({title: "Ooops!", text: data.row[0], icon: "warning", allowOutsideClick: false});
                    _loadPetsAlbum();
                } else {
                    Swal.fire("Ooops!", "Failed to process the data, please check the fields again on the form provided.", "error");
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// interest
$('#interest').change(function(){
    $.ajax({
        url: base_url+ "api/profile/save_interest",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            interest:$('#interest').val(),
        },
        dataType: "JSON",
        success: function (data) {
            if (data.status==true){
                // _loadOwnerProfile();
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// Class Initialization
jQuery(document).ready(function() {
    loadSelectpicker_interest();
    _loadDropifyFile('', '#foto_profiles');
    _loadOwnerProfile(), _loadPetsAlbum();
});