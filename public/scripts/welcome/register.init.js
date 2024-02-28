"use strict";
// Class Definition
var SetUpPassword;
// FORM CLASS LOGIN
var KTRegister = function() {
	//Register Handle 1
	var _handleRegister1Form = function() {
		$('#name').focus();
		// Handle submit button
		$('#btn-register1').on('click', function (e) {
			e.preventDefault();
			$('#btn-register1').html('Please Wait...').attr('disabled', true);
			let name = $('#name'), username = $('#username'), email = $('#email'), phone_number = $('#phone_number');
			if (name.val() == '') {
				toastr.error('Full name is mandatory..', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				name.focus();
				$('#btn-register1').html('Next').attr('disabled', false);
				return false;
			}if (username.val() == '') {
				toastr.error('Username is mandatory..', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				username.focus();
				$('#btn-register1').html('Next').attr('disabled', false);
				return false;
			}if (email.val() == '') {
				toastr.error('Email is mandatory..', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				email.focus();
				$('#btn-register1').html('Next').attr('disabled', false);
				return false;
			} if (!validateEmail(email.val())) {
				toastr.error('Email not valid..', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				email.focus();
				$('#btn-register1').html('Next').attr('disabled', false);
				return false;
			}if (phone_number.val() == '') {
				toastr.error('Phone number is mandatory..', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				phone_number.focus();
				$('#btn-register1').html('Next').attr('disabled', false);
				return false;
			}
			var formData = new FormData($('#form-first')[0]);
			$.ajax({
				url: base_url+ "api/register/first_register",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-register1').html('Next').attr('disabled', false);
					if (data.status==true){
						$('[name="hideName"]').val(data.row.name),
						$('[name="hideUsername"]').val(data.row.username),
						$('[name="hideMail"]').val(data.row.email),
						$('[name="hidePhoneNumber"]').val(data.row.phone_number),
						$('[name="hideGender"]').val(data.row.gender),
                        $('#firstStep').hide(),
                        $('#secondStep').addClass('loginAnimated-fadeInRight').show(),
                        $('#digit_2').focus();
					}else{
						toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
					}
				}, error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-register1').html('Next').attr('disabled', false);
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}
	//Register Handle 2
	var _handleRegister2Form = function() {
		// Handle submit button
        $('#btn-register2').on('click', function (e) {
			e.preventDefault();
			$('#btn-register2').html('Please Wait...').attr('disabled', true);
			var digit_2 = $('#digit_2'), digit_3 = $('#digit_3'), digit_4 = $('#digit_4'), digit_5 = $('#digit_5');
			if (digit_2.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_2.focus();
				$('#btn-register2').html('Next').attr('disabled', false);
				return false;
			}if (digit_3.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_3.focus();
				$('#btn-register2').html('Next').attr('disabled', false);
				return false;
			}if (digit_4.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_4.focus();
				$('#btn-register2').html('Next').attr('disabled', false);
				return false;
			}if (digit_5.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_5.focus();
				$('#btn-register2').html('Next').attr('disabled', false);
				return false;
			}
			// set variable and close open form
			SetUpPassword = digit_2.val()+digit_3.val()+digit_4.val()+digit_5.val();
			$('#secondStep').hide(),
			$('#thirdStep').addClass('loginAnimated-fadeInRight').show(),
			$('#confirm_2').focus();
		});
	}
	//Register Handle 3
	var _handleRegister3Form = function() {
		// Handle submit button
        $('#btn-register3').on('click', function (e) {
			e.preventDefault();
			$('#btn-register3').html('Please Wait...').attr('disabled', true);
			var confirm_2 = $('#confirm_2'), confirm_3 = $('#confirm_3'), confirm_4 = $('#confirm_4'), confirm_5 = $('#confirm_5');
			if (confirm_2.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				confirm_2.focus();
				$('#btn-register3').html('Next').attr('disabled', false);
				return false;
			}if (confirm_3.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				confirm_3.focus();
				$('#btn-register3').html('Next').attr('disabled', false);
				return false;
			}if (confirm_4.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				confirm_4.focus();
				$('#btn-register3').html('Next').attr('disabled', false);
				return false;
			}if (confirm_5.val() == '') {
				toastr.error('Enter your PIN', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				confirm_5.focus();
				$('#btn-register3').html('Next').attr('disabled', false);
				return false;
			}
			var confirmPassword = confirm_2.val()+confirm_3.val()+confirm_4.val()+confirm_5.val();
			if (SetUpPassword != confirmPassword) {
				toastr.error('Your confirmation PIN is not same!', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				digit_5.focus();
				$('#btn-register3').html('Next').attr('disabled', false);
				return false;
			}
			
			var formData = new FormData($('#form-third')[0]);
			$.ajax({
				url: base_url+ "api/register/second_tegister",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-register3').html('Next').attr('disabled', false);
					if (data.status==true){
						Swal.fire({
							title: "Success!",
							text: "Registration successfully, System will redirect you to the dashboard page in a few seconds...",
							icon: "success",
							timer: 3000,
							timerProgressBar: true,
							showConfirmButton: false,
							allowOutsideClick: false
						}).then(function (result) {
							let nextUrl = data.row.last_visited_url ? data.row.last_visited_url : base_url+ 'home';
							window.location = nextUrl;
						});
					}else{
						Swal.fire({title: "Ooops!", text: data.message, icon: "error", allowOutsideClick: false}).then(function (result) {
							location.reload(true);
						});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-register3').html('Next').attr('disabled', false);
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
            _handleRegister1Form();
            _handleRegister2Form();
            _handleRegister3Form();
        }
    };
}();
// close second step
const _closeSecondStep = () => {
	$('#secondStep').hide(),
	$('#firstStep').addClass('loginAnimated-fadeInRight').show();
}
// close third step
const _closeThirStep = () => {
	$('#thirdStep').hide(),
	$('#secondStep').addClass('loginAnimated-fadeInRight').show();
}
//Validate Email
const validateEmail = (email) => {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
// Class Initialization
jQuery(document).ready(function() {
    KTRegister.init();
});