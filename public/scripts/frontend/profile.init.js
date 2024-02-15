//User INFO
const _loadOwnerInfo = () => {
	$.ajax({
        url: base_url+ "api/owner_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let userInfo = data.row;
            let location = (userInfo.location == null) ? '-':userInfo.location;
            let AvatarUser = `<img src="` +userInfo.thumb_url+ `" alt="avatar-user">`;
            let profileDetail = `<h5 class="name">`+userInfo.name+`</h5>
            <p class="mb-0"><i class="icon feather icon-map-pin me-1"></i>`+location+`</p>`;
            $('#widgetOwner .avatar-user').html(AvatarUser);
            $('#widgetOwner .profile-detail').html(profileDetail);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// update subscribe
const _updateSubscribe = (sub_status) => {
	$.ajax({
        url: base_url+ "api/profile/update_subscribe",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        data: {
            sub_status
        },success: function (data) {
            if(sub_status == 'Y'){
                $('.statusSubscribe').html(`
                    <a href="javascript:void(0);" onclick="_updateSubscribe('N')">
                        <div class="card-icon">
                            <i class="flaticon flaticon-bell"></i>
                        </div>
                        <div class="card-content">
                            <p>Subscriptions</p>
                        </div>
                        <i class="icon fa fa-check text-success"></i>
                    </a>
                `);
            }else{
                $('.statusSubscribe').html(`
                    <a href="javascript:void(0);" onclick="_updateSubscribe('Y')">
                        <div class="card-icon">
                            <i class="flaticon flaticon-bell"></i>
                        </div>
                        <div class="card-content">
                            <p>Subscriptions</p>
                        </div>
                        <i class="icon feather icon-plus"></i>
                    </a>
                `);
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Like is error');
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadOwnerInfo();
});