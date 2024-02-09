const loadNearOwner = () => {
    let lat = $('#lat'), lng = $('#lng');
    lat = lat.val(), lng = lng.val()
	$.ajax({
        url: base_url+ "api/near_owner",
        type: "GET",
        dataType: "JSON",
        data: {
            lat, lng
        },success: function (data) {
            let dataNearby = data.row;
            let contentNearby = '';
            if(dataNearby.length == 0){
                // contentNearby += `
                //     <div class="alert alert-primary solid alert-dismissible fade show">
                //         <strong>Hallo!</strong> Your pet is still empty.
                //     </div>
                // `;
            }else{
                $.each(dataNearby, function(key, row) {
                    var statusLike;
                    if(row.has_like == 'Y'){
                        statusLike = '<a href="javascript:void(0);" onclick="updateStatusLike('+"'"+row.id+"'"+', '+"'N'"+')" class="dz-icon dz-sp-like" title="Dislike"><i class="fa fa-check"></i></a>';
                    }else{
                        statusLike = '<a href="javascript:void(0);" onclick="updateStatusLike('+"'"+row.id+"'"+', '+"'Y'"+')" class="dz-icon dz-sp-like" title="Like"><i class="flaticon flaticon-star-1"></i></a>';
                    }
                    contentNearby += `<div class="dzSwipe_card">
                        <div class="dz-media">
                            <img src="`+row.thumb_url+`" alt="user-thumb">
                        </div>
                        <div class="dz-content">
                            <div class="left-content">
                                <span class="badge badge-primary d-inline-flex gap-1 mb-2"><i class="icon feather icon-map-pin"></i>Nearby</span>
                                <h4 class="title"><a href="profile-detail.html">`+row.name+`</a></h4>
                                <p class="mb-0"><i class="icon feather icon-map-pin"></i> `+row.distance+` kilometers away</p>
                            </div>
                            <div class="statusLikeIcon`+row.id+`">
                            `+statusLike+`
                            </div>
                        </div>
                    </div>`;
                });
            }
            $('#sectionNearbyowners').html(contentNearby);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
getLocation(function () {
    
});
//Geolocation
function getLocation(callback) {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(getPosition);
    } else {
        Swal.fire({
            title: "Maaf!",
            text: "Geolocation tidak support dengan aplikasi browser anda. Gunakan Chrome/ Firefox versi terbaru.",
            allowOutsideClick: false,
        });
    }
    callback();
}
// get position
function getPosition(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    $('[name="lat"]').val(lat);
    $('[name="lng"]').val(lng);
    loadNearOwner();
}
// update status like 
const updateStatusLike = (idp_owner, like_status) => {
	$.ajax({
        url: base_url+ "api/like_owner",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        data: {
            idp_owner, like_status
        },success: function (data) {
            if(like_status == 'Y'){
                $('.statusLikeIcon'+idp_owner+'').html('<a href="javascript:void(0);" onclick="updateStatusLike('+"'"+idp_owner+"'"+', '+"'N'"+')" class="dz-icon dz-sp-like" title="Dislike"><i class="fa fa-check"></i></a>');
            }else{
                $('.statusLikeIcon'+idp_owner+'').html('<a href="javascript:void(0);" onclick="updateStatusLike('+"'"+idp_owner+"'"+', '+"'Y'"+')" class="dz-icon dz-sp-like" title="Like"><i class="flaticon flaticon-star-1"></i></a>');
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Like is error');
        }
    });
}