// Class Initialization
jQuery(document).ready(function() {
    $('#sectionImagePets').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Sedang memuat foto #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: false,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">Foto #%curr%</a> tidak dapat dimuat...',
            titleSrc: function(item) {
                return item.el.attr('title') + '<small>'+item.el.attr('subtitle')+'</small>';
            }
        }
    });
});
// update status follow
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
                $('.statusFollow').attr('onclick', "updateStatusLike('" + idp_owner + "', 'N')");
                $('.statusFollow').html('<span class="badge badge-lg badge-info me-1"><i class="fa fa-check"></i></span>');
            }else{
                $('.statusFollow').attr('onclick', "updateStatusLike('" + idp_owner + "', 'Y')");
                $('.statusFollow').html('<span class="badge badge-lg badge-info me-1"><i class="fa fa-plus"></i></span>')
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Like is error');
        }
    });
}
// update status like pets
const updateStatusLikePets = (fid_pets, like_status) => {
	$.ajax({
        url: base_url+ "api/like_pets",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        data: {
            fid_pets, like_status
        },success: function (data) {
            if(like_status == 'Y'){
                $('.btn-lovedislove').html('<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+fid_pets+"'"+', '+"'N'"+')" class="btn w-100 font-16 p-2 btn-sm btn-danger light border-danger"><i class="feather icon-x"></i> Dislove</a>');
            }else{
                $('.btn-lovedislove').html('<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+fid_pets+"'"+', '+"'Y'"+')" class="btn w-100 font-16 p-2 btn-sm btn-info light border-info"><i class="feather icon-heart-on"></i></a>');
            }
            $('.totalLovePets').html(`<i class="feather icon-heart-on me-1 text-info"></i>`+data.row+` Love`);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Like is error');
        }
    });
}