//System INFO
const _loadSystemInfo = () => {
	$.ajax({
        url: base_url+ "api/site_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let headerLogo = `
                <a href="`+base_url+`"><img src="` +data.row.headpublic_logo_url+ `" alt="Logo"></a>
            `;
            $('#appHeader .header-logo').html(headerLogo);
            $('#appSideMenu .app-info').html(data.row.copyright);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
//User INFO
const _loadUserInfo = () => {
	$.ajax({
        url: base_url+ "api/owner_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let userInfo = data.row;
            let profileSide = `<div class="dz-media">
                    <img src="` +userInfo.thumb_url+ `" alt="avatar-user">
                </div>
                <div class="dz-info">
                    <h5 class="name">` +userInfo.name+ `</h5>
                    <span>` +userInfo.email+ `</span>
            </div>`;
            $('#appSideMenu .author-box').html(profileSide);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo(), _loadUserInfo();
});