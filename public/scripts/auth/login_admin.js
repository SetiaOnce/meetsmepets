"use strict";
// Class Definition
// FORM CLASS LOGIN
var KTLogin = function() {
	/* Show Hide Password */
    $('#show_hide_password').change(function() {
        if(this.checked) {
            $('#password').attr('type', 'text');
            $('#fg-showHidePassword .form-check-label').text('Password Ditampilkan');
        }else{
			$('#password').attr('type', 'password');
            $('#fg-showHidePassword .form-check-label').text('Password Disembunyikan');
        }
    });
	/* Handle enter auto login*/
    $("#dt-formLogin input").keyup(function(event) {
        if (event.keyCode == 13 || event.key === 'Enter') {
            $("#btn-login-submit").click();
        }
    });
	/* Handle sign in form login */
	var _handleSignInForm = function() {
		// Handle submit button
        $('#btn-login-submit').on('click', function (e) {
            e.preventDefault();
			$('#btn-login-submit').html('<i class="fas fa-spinner fa-spin me-1 align-middle"></i>Mohon tunggu...');
			$('#btn-login-submit').attr('disabled', true);
            
			let email_nik = $('#email_nik'), password = $('#password');
			if (email_nik.val() == '') {
				toastr.error('Email/NIK masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				email_nik.focus();
				$('#btn-login-submit').html('<i class="fas fa-sign-in-alt me-1"></i> Log in');
				$('#btn-login-submit').attr('disabled', false);
				return false;
			} if(password.val() == ''){
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				password.focus();
				$('#btn-login-submit').html('<i class="fas fa-sign-in-alt me-1"></i> Log in');
				$('#btn-login-submit').attr('disabled', false);
				return false;
			}
			let formData = new FormData($('#dt-formLogin')[0]), ajax_url = BASE_URL+ "/ajax/request_login_admin";
			$.ajax({
				url: ajax_url,
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-login-submit').html('<i class="fas fa-sign-in-alt me-1"></i> Log in');
					$('#btn-login-submit').attr('disabled', false);
					if (data.status==true){
						window.location.href = BASE_URL+ "/app_admin/dashboard"
					}else if(data.wrongAkses){
				        toastr.error('Email atau password tidak sesuai', 'Uuppss!', {"progressBar": true, "timeOut": 2500});
					}else{
						if(data.pesan_code=='format_inputan') { 
							Swal.fire({title: "Ooops!", text: data.pesan_error[0], icon: "warning", allowOutsideClick: false});  
						} else if(data.pesan_error) {
							Swal.fire({title: "Ooops!", text: data.pesan_error, icon: "warning", allowOutsideClick: false});  
						}else{
							Swal.fire({title: "Ooops!", text: "Gagal Login, Periksa koneksi jaringan internet lalu coba kembali.", icon: "error", allowOutsideClick: false});   
						}
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login-submit').html('<i class="fas fa-sign-in-alt me-1"></i> Log in');
					$('#btn-login-submit').attr('disabled', false);
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error"
					}).then(function (result) {
					    // location.reload(true);
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
        }
    };
}();
// SITE INFO
var loadSiteInfo = function() {
	$.ajax({
		url: BASE_URL+ "/ajax/load_login_info/",
		type: "GET",
		dataType: "JSON",
		success: function (data) {
			$('#bg-login').attr('style', 'background-image: url(' +data.row.login_bg_url+ ');');
			$('#hLogo-login').html(`<a href="` +BASE_URL+ `/" title="LOGIN - ` +data.row.short_name+ `">
				<img src="` +data.row.login_logo_url+ `" class="w-60" height="52" alt="` +data.row.login_logo+ `">
			</a>
			<p class="mt-1 text-sm text-center text-info"><strong>Login Pengelola</strong></p>`);
			$('.btnBottom').html(`<a href="` +BASE_URL+ `/login" class="form-check-label mb-0 ms-3 text-dark"><i class="fa fa-sign-in align-center me-1 text-dark"></i>Log In Pegawai</a>
			<a href="` +BASE_URL+ `/" class="form-check-label mb-0 ms-3 text-dark"><i class="fa fa-sign-in align-center me-1 text-dark"></i>Log In SSO</a>`);
			$('#loader-action').hide(),$('#btn-login-submit').show();
			$('#kt_footer .copyRight').html(data.row.copyright);
		},error: function (jqXHR, textStatus, errorThrown) {
			console.log('Load data is error');
		}
	});
};
// Class Initialization
jQuery(document).ready(function() {
	loadSiteInfo();
	KTLogin.init();
});
