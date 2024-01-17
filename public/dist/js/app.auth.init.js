"use strict";
// Class Definition
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border spinner-border-sm align-middle text-primary"></span> Mohon tunggu ...</div>';
//System INFO
const _loadSystemInfo = () => {
	$.ajax({
        url: base_url+ "api/site_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let siteInfo = data.row;
            $('#kt_body').attr('style', `background-size: cover; background-position: unset; background-image: linear-gradient(0deg, rgb(7 0 40), #16246dcc), url(` +siteInfo.login_bg_url+ `);`),
			$('#aside-auth').html(`<div class="d-flex flex-center flex-lg-start flex-column">
				<h1 class="text-white fs-2qx fw-bolder text-center mb-0 mb-lg-3">
					Sistem Informasi Monitoring Kinerja Terminal
				</h1>
				<h2 class="text-white fw-bold fs-3qx m-0"> 
					BPTD Kelas II Banten
				</h2>
			</div>`),
            $('#hLogo-login').html(`<a href="` +base_url+ `auth" title="LOGIN - ` +siteInfo.short_name+ `">
                <img src="` +siteInfo.login_logo_url+ `" class="mb-5 theme-light-show" height="52" alt="` +siteInfo.login_logo+ `">
                <img src="` +siteInfo.headbackend_logo_dark_url+ `" class="mb-5 theme-dark-show" height="52" alt="` +siteInfo.headbackend_logo_dark+ `">
            </a>`);
            $('#copyRight').html(siteInfo.copyright);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// FORM CLASS LOGIN
var KTLogin = function() {
	//SignIn Handle 1
	var _handleSignInForm = function() {
		$('#username').focus();
		//Handle Enter Submit
		$("#username").keyup(function(event) {
			if (event.keyCode == 13 || event.key === 'Enter') {
				$("#btn-login1").click();
			}
		});
		// Handle submit button
		$('#btn-login1').on('click', function (e) {
			e.preventDefault();
			$('#btn-login1').attr('data-kt-indicator', 'on').attr('disabled', true);
			var username = $('#username');
			if (username.val() == '') {
				toastr.error('Username atau Email masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				username.focus();
				$('#btn-login1').removeAttr('data-kt-indicator').attr('disabled', false);
				return false;
			}

			var target = document.querySelector('#kt_sign_in'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
			blockUi.block(), blockUi.destroy();
			var formData = new FormData($('#kt_sign_in_form')[0]);
			$.ajax({
				url: base_url+ "api/auth/first_login",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-login1').removeAttr('data-kt-indicator').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					if (data.status==true){
						var tUserInfo=`<!--begin::Title-->
						<h4 class="text-dark  fw-500 mb-2">` +data.row.name+ `</h4>
						<!--end::Title-->
						<div class="btn-group">
							<button class="btn btn-bg-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ki-duotone ki-profile-circle fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>` +data.row.email+ `
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="` +base_url+ `auth/logout"><i class="bi bi-person-x me-2 text-dark fs-5"></i> Gunakan akun lain</a></li>
							</ul>
						</div>`;
						$('[name="hideMail"]').val(data.row.email),
                        $('#hT-login2').html(tUserInfo),
                        $('#fBody-login1').hide(),
                        $('#fBody-login2').addClass('loginAnimated-fadeInRight').show(),
                        $('#password').focus();
					}else{
						Swal.fire({title: "Ooops!", text: data.message, icon: "error", allowOutsideClick: false}).then(function (result) {
							location.reload(true);
						});
					}
				}, error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login1').removeAttr('data-kt-indicator').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}
	//SignIn Handle 2
	var _handleSignIn2Form = function() {
		/* Show Hide Password */
		$('#showPass_checkbox').change(function (e) {
			e.preventDefault();
			if ($('#showPass_checkbox').is(":checked")){
				$('#password').attr('type', 'text');
			}else{
				$('#password').attr('type', 'password');
			}
		});
		//Handle Enter Submit
		$("#password").keyup(function(event) {
			if (event.keyCode == 13 || event.key === 'Enter') {
				$("#btn-login2").click();
			}
		});
		// Handle submit button
        $('#btn-login2').on('click', function (e) {
			e.preventDefault();
            $('#btn-login2').attr('data-kt-indicator', 'on').attr('disabled', true);
			var password = $('#password');
			if (password.val() == '') {
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				password.focus();
				$('#btn-login2').removeAttr('data-kt-indicator').attr('disabled', false);
				return false;
			}

			var target = document.querySelector('#kt_sign_in'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
			blockUi.block(), blockUi.destroy();
			var formData = new FormData($('#kt_sign_in_form')[0]);
			$.ajax({
				url: base_url+ "api/auth/second_login",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-login2').removeAttr('data-kt-indicator').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					if (data.status==true){
						Swal.fire({
							title: "Success!",
							text: "Login berhasil, sistem akan mengarahkan anda ke halaman dashboard dalam beberapa detik...",
							icon: "success",
							timer: 3000,
							timerProgressBar: true,
							showConfirmButton: false,
							allowOutsideClick: false
						}).then(function (result) {
							$('#kt_sign_in').hide();
							let nextUrl = data.row.last_visited_url ? data.row.last_visited_url : base_url+ 'app_admin';
							window.location = nextUrl;
						});
					}else{
						Swal.fire({title: "Ooops!", text: data.message, icon: "error", allowOutsideClick: false}).then(function (result) {
							if(data.row.error_code=='PASSWORD_NOT_VALID') {
								password.val('').focus();
							}else{
								location.reload(true);
							}
						});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login2').removeAttr('data-kt-indicator').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
		/*/ Handle forgot button
		$('#kt_login_forgot').on('click', function (e) {
			e.preventDefault();
			//_showForm('forgot');
			Swal.fire({
				title: "Warning!",
				html: 'Mohon maaf fitur ini sedang tahap pengembangan. Agar dapat login ke sistem, silahkan lakukan konfirmasi akun user ke pihak Admin PIC Aplikasi.',
				icon: "warning",
				allowOutsideClick: false
			})
		});*/
	}
	/*var _handleForgotForm = function(e) {
        // Handle submit button
        $('#kt_login_forgot_submit').on('click', function (e) {
            e.preventDefault();
        });
        // Handle cancel button
        $('#kt_login_forgot_cancel').on('click', function (e) {
            e.preventDefault();
            _showForm('signin2');
        });
    }*/
    // Public Functions
    return {
        // public functions
        init: function() {
            _handleSignInForm();
            _handleSignIn2Form();
            //_handleForgotForm();
        }
    };
}();
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo(), KTLogin.init();
});