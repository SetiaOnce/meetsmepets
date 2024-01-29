"use strict";
// Class Definition
// FORM CLASS LOGIN
var KTLogin = function() {
	//SignIn Handle 1
	var _handleSignInForm = function() {
		$('#email').focus();
		//Handle Enter Submit
		$("#form-first #email").keyup(function(event) {
			event.preventDefault();
			if (event.keyCode == 13 || event.key === 'Enter') {
				$("#btn-login1").click();
			}
		});
		// Handle submit button
		$('#btn-login1').on('click', function (e) {
			e.preventDefault();
			$('#btn-login1').html('Please Wait...').attr('disabled', true);
			var email = $('#email');
			if (email.val() == '') {
				toastr.error('Email or phone number is mandatory..', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				email.focus();
				$('#btn-login1').html('Next').attr('disabled', false);
				return false;
			}
			var formData = new FormData($('#form-first')[0]);
			$.ajax({
				url: base_url+ "api/auth/first_step",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-login1').html('Next').attr('disabled', false);
					if (data.status==true){
						$('[name="hideMail"]').val(data.row.email),
                        $('#firstStep').hide(),
                        $('#secondStep').addClass('loginAnimated-fadeInRight').show(),
                        $('#digit_2').focus();
					}else{
						toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
					}
				}, error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login1').html('Next').attr('disabled', false);
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}
	//SignIn Handle 2
	var _handleSignIn2Form = function() {
		//Handle Enter Submit
		$("#password").keyup(function(event) {
			if (event.keyCode == 13 || event.key === 'Enter') {
				$("#btn-login2").click();
			}
		});
		// Handle submit button
        $('#btn-login2').on('click', function (e) {
			e.preventDefault();
			$('#btn-login2').html('Please Wait...').attr('disabled', true);
			var digit_2 = $('#digit_2'), digit_3 = $('#digit_3'), digit_4 = $('#digit_4'), digit_5 = $('#digit_5');
			if (digit_2.val() == '') {
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_2.focus();
				$('#btn-login2').html('Next').attr('disabled', false);
				return false;
			}if (digit_3.val() == '') {
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_3.focus();
				$('#btn-login2').html('Next').attr('disabled', false);
				return false;
			}if (digit_4.val() == '') {
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_4.focus();
				$('#btn-login2').html('Next').attr('disabled', false);
				return false;
			}if (digit_5.val() == '') {
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_5.focus();
				$('#btn-login2').html('Next').attr('disabled', false);
				return false;
			}

			var formData = new FormData($('#form-second')[0]);
			$.ajax({
				url: base_url+ "api/auth/second_step",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-login2').html('Next').attr('disabled', false);
					if (data.status==true){
						let nextUrl = data.row.last_visited_url ? data.row.last_visited_url : base_url+ 'home';
						window.location = nextUrl;
					}else{
						if(data.row.error_code=='PASSWORD_NOT_VALID') {
							toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
						}else{
							Swal.fire({title: "Ooops!", text: data.message, icon: "error", allowOutsideClick: false}).then(function (result) {
								location.reload(true);
							});
						}
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login2').html('Next').attr('disabled', false);
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}
    // Public Functions
    return {
        // public functions
        init: function() {
            _handleSignInForm();
            _handleSignIn2Form();
        }
    };
}();
const _closeSecondStep = () => {
	$('#secondStep').hide(),
	$('#firstStep').addClass('loginAnimated-fadeInRight').show();
}
// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
});