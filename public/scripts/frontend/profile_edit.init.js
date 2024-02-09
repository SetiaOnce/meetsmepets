"use strict";
//Class Definition
var save_method;
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
// slectpicker category
function loadSelectpicker_category() {
    $.ajax({
        url: base_url+ "api/select/category",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var data = data.row;
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].text + '</option>';
            }
            $('#category').html(output).selectpicker('refresh').selectpicker('val', '');
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
// load all pets
const _loadAllPets = () =>{
    $.ajax({
        url: base_url+ "api/profile/load_pets",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        success: function (data) {            
            let rows = data.row, petsContent = '';
            if(data.row.length == 0){
                petsContent += `
                    <div class="alert alert-primary solid alert-dismissible fade show">
                        <strong>Hallo!</strong> Your pet is still empty.
                    </div>
                `;
            }else{
                $.each(rows, function(key, row) {
                    petsContent += `<div class="col-6">
                        <div class="dz-media-card">
                            <a href="javascript:void(0);" onclick="optionPets(`+row.id+`)">
                                <div class="dz-media">
                                    <img src="`+row.image_url+`" alt="pet-image">
                                </div>
                                <div class="dz-content">
                                    <h6 class="title">`+row.category+`</h6>
                                    <span class="about">`+row.breed+`</span>	
                                </div>
                            </a>
                        </div>
                    </div>`;
                });
            }
            $('#data-pets').html(petsContent);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('log pets is error');
        }
    });
}
//Close Content Card by Open Method
const _closeModal = (card) => {
    if(card=='form_pets') {
        save_method = '';
        _clearFormPets(), $('#ModalPets .modal-title').html('');
    }
    $('#ModalPets').modal('hide');
}
//Clear Form Pets
const _clearFormPets = () => {
    if (save_method == "" || save_method == "add_pets") {
        $("#form-pets")[0].reset(), $('[name="id"]').val("");
        $('.imagePreview').css('background-image', 'url('+base_url+'dist/img/drop-bx.png)');
    } else {
        let idp = $('[name="id"]').val();
        _editPets(idp);
    }
}
// option pets
const optionPets = (idp) => {
    Swal.fire({
        title: 'What do you want to do?',
        html: `
        <button type="button" class="btn btn-sm me-2 btn-danger" onclick="_deletePet(`+idp+`)"><i class="fa fa-trash me-2"></i>Delete</button>
        <button type="button" class="btn btn-sm me-2 btn-info" onclick="_editPet(`+idp+`)"><i class="fa fa-pencil me-2"></i>Edit</button>`,
        position: 'top',
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Close',
    })
}
const _editPet = (idp) => {
    save_method = "update_pets";
    $('#form-pets')[0].reset();
    $.ajax({
        url: base_url+ "api/profile/edit_pet",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        data:{
            idp
        },success: function (data) {
            console.log(data);
            $('[name="id"]').val(data.row.id);
            $('#category').selectpicker('val', data.row.category);
            $('#breed').val(data.row.breed);
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
            $("#ModalPets .modal-title").html(
                `<h3 class="fw-bolder fs-6 text-gray-900"><i class="fa fa-pencil fs-2 text-gray-900 align-middle me-2"></i>Form Edit Pets</h3>`
            ),
            $('#ModalPets').modal('show');
            Swal.close();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('log petsalbum is error');
        }
    });
};
// Add Pets
const _addPets = () => {
    save_method = "add_pets";
    _clearFormPets(),
    $("#ModalPets .modal-title").html(
        `<h3 class="fw-bolder fs-6 text-gray-900"><i class="fa fa-plus fs-5 text-gray-900 align-middle me-2"></i>Form Add Pets</h3>`
    ),
    $('#ModalPets').modal('show');
}
//Save Pets Form
$("#btn-savePets").on("click", function (e) {
    e.preventDefault();
    $('#btn-savePets').html('Please Wait...').attr('disabled', true);
    let category = $("#category"), breed = $("#breed");

    if (category.val() == "") {
        toastr.error('Select the pets category', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        category.focus();
        $("#btn-savePets").html('<i class="las la-save align-center me-2"></i> Save').attr("disabled", false);
        return false;
    }if (breed.val() == "") {
        toastr.error('Enter the pets breed', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        breed.focus();
        $("#btn-savePets").html('<i class="las la-save align-center me-2"></i> Save').attr("disabled", false);
        return false;
    }

    let textConfirmSave = "Save pet changes now ?";
    if (save_method == "add_pets") {
        textConfirmSave = "Add pet now ?";
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
            let formData = new FormData($("#form-pets")[0]), ajax_url = base_url+ "api/profile/save_pets";
            if(save_method == 'update_pets') {
                ajax_url = base_url+ "api/profile/update_pets";
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
                    $("#btn-savePets").html('<i class="las la-save align-center me-2"></i> Save').attr("disabled", false);
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeModal('form_pets'), _loadAllPets();
                        });
                    } else {
                        if(data.row[0]) {   
                            Swal.fire({title: "Ooops!", text: data.row[0], icon: "warning", allowOutsideClick: false});
                        } else {
                            Swal.fire("Ooops!", "Failed to process the data, please check the fields again on the form provided.", "error");
                        }
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-savePets").html('<i class="las la-save align-center me-2"></i> Save').attr("disabled", false);
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $("#btn-savePets").html('<i class="las la-save align-center me-2"></i> Save').attr("disabled", false);
        }
    });
});
//Update Status Data User
const _deletePet = (idp) => {
    Swal.close();
    Swal.fire({
        title: "",
        html: 'Delete pet now?',
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Yes",
        cancelButtonText: "No!"
    }).then(result => {
        if (result.value) {
            // Push Ajax
            $.ajax({
                url: base_url+ "api/profile/delete_pet",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp
                }, success: function (data) {
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        _loadAllPets();
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        _loadAllPets();
                    });
                }
            });
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    loadSelectpicker_interest(), loadSelectpicker_category(), _loadDropifyFile('', '#foto_profiles');
    _loadOwnerProfile(), _loadAllPets(); //_loadPetsAlbum();
});