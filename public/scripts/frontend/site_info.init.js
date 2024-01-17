// SITE INFO
var loadSiteInfo = function() {
	$.ajax({
		url: BASE_URL+ "/ajax/load_login_info/",
		type: "GET",
		dataType: "JSON",
		success: function (data) {
      // section header navigation
			$('#navBarElement').html(`
        <a class="navbar-brand font-weight-bolder ms-sm-3  d-none d-md-block" href="` +BASE_URL+ `/" rel="tooltip" title="" data-placement="bottom">
          <img src="` +data.row.headpublic_logo_url+ `" class="w-15" alt="` +data.row.headpublic_logo+ `">
        </a>
        <a class="navbar-brand font-weight-bolder ms-sm-3  d-block d-md-none" href="` +BASE_URL+ `/" rel="tooltip" title="" data-placement="bottom">
          SISAKA 
        </a>
        <a href="` +BASE_URL+ `/login" class="btn btn-sm  bg-gradient-info  mb-0 ms-auto d-lg-none d-block"><i class="fa fa-sign-in me-2 fs-6 align-middle" aria-hidden="true"></i>Login</a>
        <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0 justify-content-end" id="navigation">
          <ul class="navbar-nav d-lg-block d-none">
            <li class="nav-item">
              <a href="` +BASE_URL+ `/login" class="btn bg-gradient-info  mb-0 me-1 mt-2 mt-md-0"><i class="fa fa-sign-in me-2 fs-6 align-middle" aria-hidden="true"></i>Login</a>
            </li>
          </ul>
      `);

      // section footer 01
      $('#sectionFooter .footer01').html(`<a href="`+BASE_URL+`/"><img src="` +data.row.headpublic_logo_url+ `" class="mb-3 footer-logo w-70" alt="` +data.row.headpublic_logo+ `"></a><p class="text-justify">` +data.row.description+ `</p>`);
      // section footer 02
      $('#sectionFooter .footer02').html(`<h6 class="text-sm">Tentang Kami</h6>
      <p class="text-justify">` +data.row.about_portal+ `</p>`);
      // section footer 03
      let tautans = data.tautan;
      tautanContent = '';
      $.each(tautans, function(key, row) {
          tautanContent += `<li class="nav-item">
            <a class="nav-link text-info" style="font-size:13px;" href="`+row.link_tautan+`" target="_blank">
                > `+row.nama_tautan+`
            </a>
          </li>`;
      });
      $('#sectionFooter .footer03').html(`
        <h6 class="text-sm">Link Instansi Terkait</h6>
        <ul class="flex-column ms-n3 nav">
            `+tautanContent+`
        </ul>
      `);
      // section footer copyright
      $('#sectionFooter .copyRight').html(data.row.copyright);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log('Load data is error');
		}
	});
};
// Class Initialization
jQuery(document).ready(function() {
	loadSiteInfo();
});
