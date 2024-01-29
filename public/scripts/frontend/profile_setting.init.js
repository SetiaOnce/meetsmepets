// class Initilisasion
//Owner INFO
const _loadOwnerProfile = () => {
	$.ajax({
        url: base_url+ "api/owner_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let userInfo = data.row;
            $('#ktSettingProfile .username').html(userInfo.username);
            $('#ktSettingProfile .phone_number').html(userInfo.phone_number);
            $('#ktSettingProfile .email').html(userInfo.email);
            $('#ktSettingProfile .location').html(userInfo.location);
            $('#ktSettingProfile .gender').html((userInfo.gender == null)? 'OTHER':userInfo.gender);
            var lookingFor = '';
            if(userInfo.looking_for){
                var lookingFor = userInfo.looking_for.split(", ");
            }
            $('#looking_for').selectpicker('val', lookingFor);
            rangeSlider("distance-slider", userInfo.distance);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// slectpicker looking for
function loadSelectpicker_lookingfor() {
    $.ajax({
        url: base_url+ "api/select/looking_for",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var data = data.row;
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].text + '</option>';
            }
            $('#looking_for').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// save username
$('#btn-username').on('click', function (e) {
    e.preventDefault();
    $('#btn-username').html('Please Wait...').attr('disabled', true);
    var username = $('#username');
    if (username.val() == '') {
        toastr.error('Enter your username', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        username.focus();
        $('#btn-username').html('Next').attr('disabled', false);
        return false;
    }
    username = username.val()
    $.ajax({
        url: base_url+ "api/profile/save_username",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            username,
        },
        dataType: "JSON",
        success: function (data) {
            $('#btn-username').html('Next').attr('disabled', false);
            if (data.status==true){
                _loadOwnerProfile();
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-username').html('Next').attr('disabled', false);
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// save phone number
$('#btn-phone').on('click', function (e) {
    e.preventDefault();
    $('#btn-phone').html('Please Wait...').attr('disabled', true);
    var phone_number = $('#phone_number');
    if (phone_number.val() == '') {
        toastr.error('Enter your phone number', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        phone_number.focus();
        $('#btn-phone').html('Next').attr('disabled', false);
        return false;
    }
    phone_number = phone_number.val()
    $.ajax({
        url: base_url+ "api/profile/save_phonenumber",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            phone_number,
        },
        dataType: "JSON",
        success: function (data) {
            $('#btn-phone').html('Next').attr('disabled', false);
            if (data.status==true){
                _loadOwnerProfile();
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-phone').html('Next').attr('disabled', false);
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// save email address
$('#btn-email').on('click', function (e) {
    e.preventDefault();
    $('#btn-email').html('Please Wait...').attr('disabled', true);
    var email = $('#email');
    if (email.val() == '') {
        toastr.error('Enter your email', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-email').html('Next').attr('disabled', false);
        return false;
    }if (!validateEmail(email.val())) {
        toastr.error('Email not valid..', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-email').html('Next').attr('disabled', false);
        return false;
    }
    email = email.val()
    $.ajax({
        url: base_url+ "api/profile/save_email",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            email,
        },
        dataType: "JSON",
        success: function (data) {
            $('#btn-email').html('Next').attr('disabled', false);
            if (data.status==true){
                _loadOwnerProfile();
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-email').html('Next').attr('disabled', false);
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// save location address
$('#btn-location').on('click', function (e) {
    e.preventDefault();
    $('#btn-location').html('Please Wait...').attr('disabled', true);
    var location = $('#location');
    if (location.val() == '') {
        toastr.error('Enter your location address', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        location.focus();
        $('#btn-location').html('Next').attr('disabled', false);
        return false;
    }
    location = location.val()
    $.ajax({
        url: base_url+ "api/profile/save_location",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            location,
        },
        dataType: "JSON",
        success: function (data) {
            $('#btn-location').html('Next').attr('disabled', false);
            if (data.status==true){
                _loadOwnerProfile();
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-location').html('Next').attr('disabled', false);
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// save gender
const container = document.querySelector('#offcanvasBottom4');
const checkboxes = container.querySelectorAll('[name="gender"]');
// Toggle delete selected toolbar
checkboxes.forEach(checkbox => {
    // Checkbox on click event
    checkbox.addEventListener('change', function () {
        var gender = checkbox.value;
        $.ajax({
            url: base_url+ "api/profile/save_gender",
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: "POST",
            data: {
                gender,
            },
            dataType: "JSON",
            success: function (data) {
                if (data.status==true){
                    _loadOwnerProfile();
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
});
//Validate Email
const validateEmail = (email) => {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
// range slider
function rangeSlider(elementId, startValue) {
    jQuery("#"+elementId).parents('.card, .dz-slider').find('.slider-margin-value-min').html(startValue + "km");
    if($("#"+elementId).length > 0 ) {
        var tooltipSlider = document.getElementById(elementId);
        
        var formatForSlider = {
            from: function (formattedValue) {
                return Number(formattedValue);
            },
            to: function(numericValue) {
                return Math.round(numericValue);
            }
        };
        noUiSlider.create(tooltipSlider, {
            start: startValue,
            connect: [true, false],
            format: formatForSlider,
            range: {
                'min': 10,
                'max': 100
            }
        });
        tooltipSlider.noUiSlider.on('set', function (values, handle, unencoded) {
            jQuery("#"+elementId).parents('.card, .dz-slider').find('.slider-margin-value-min').html(values + "km");
            var distance = values[0];
            $.ajax({
                url: base_url+ "api/profile/save_distance",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: {
                    distance,
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.status==true){
                        _loadOwnerProfile();
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
    }
}
// looking for
$('#looking_for').change(function(){
    $.ajax({
        url: base_url+ "api/profile/save_lookingfor",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            looking_for:$('#looking_for').val(),
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
    loadSelectpicker_lookingfor();
    _loadOwnerProfile();
});