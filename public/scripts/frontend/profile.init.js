//User INFO
const _loadOwnerInfo = () => {
	$.ajax({
        url: base_url+ "api/owner_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let userInfo = data.row;
            let AvatarUser = `<img src="` +userInfo.thumb_url+ `" alt="avatar-user">`;
            let profileDetail = `<h5 class="name">`+userInfo.name+`</h5>
            <p class="mb-0"><i class="icon feather icon-map-pin me-1"></i>`+(userInfo.location == null) ? '-':userInfo.location+`</p>`;
            $('#widgetOwner .avatar-user').html(AvatarUser);
            $('#widgetOwner .profile-detail').html(profileDetail);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// Class Initialization
jQuery(document).ready(function() {
    _loadOwnerInfo();
});