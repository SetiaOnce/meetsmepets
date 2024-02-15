// slectpicker category
function loadSelectpicker_category() {
    $.ajax({
        url: base_url+ "api/select/category",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var data = data.row;
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].text + '</option>';
            }
            $('#category').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// slectpicker breed
function loadSelectpicker_breed(category) {
    $.ajax({
        url: base_url+ "api/select/breed",
        type: "GET",
        dataType: "JSON",
        data: {
            category
        },success: function (data) {
            var data = data.row;
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].breed + '">' + data[i].breed + '</option>';
            }
            $('#breed').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// onchange category 
$('#category').change(function(){
    let category = $('#category').val();
    loadSelectpicker_breed(category);
});
// range slider
function rangeSlider(elementId, startValue) {
    jQuery("#"+elementId).parents('.card, .dz-slider').find('.slider-margin-value-min').html(startValue + "km");
    if($("#"+elementId).length > 0 ) {
        var tooltipSlider = document.getElementById(elementId);
        
        var formatForSlider = {
            from: function (formattedValue) {
                return Number(formattedValue);
            },
            to: function(numericValue) {
                return Math.round(numericValue);
            }
        };
        noUiSlider.create(tooltipSlider, {
            start: startValue,
            connect: [true, false],
            format: formatForSlider,
            range: {
                'min': 10,
                'max': 100
            }
        });
        tooltipSlider.noUiSlider.on('set', function (values, handle, unencoded) {
            jQuery("#"+elementId).parents('.card, .dz-slider').find('.slider-margin-value-min').html(values + "km");
            var distance = values[0];
            $.ajax({
                url: base_url+ "api/profile/save_distance",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: {
                    distance,
                },
                dataType: "JSON",
                success: function (data) {
                    // if (data.status==true){
                    //     _loadOwnerProfile();
                    // }else{
                    //     toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
                    // }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                        location.reload(true);
                    });
                }
            });
        });
    }
}
// apply filter
$('#btn-apllyfilter').on('click', function (e) {
    e.preventDefault();
    $('#btn-apllyfilter').html('Please Wait...').attr('disabled', true);
    var category = $('#category');
    var breed = $('#breed');
    if (category.val() == '' || category.val() == null) {
        toastr.error('Select category', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        category.focus();
        $('#btn-apllyfilter').html('Apply').attr('disabled', false);
        return false;
    }
    if (breed.val() == '' || breed.val() == null) {
        toastr.error('Select breed', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        breed.focus();
        $('#btn-apllyfilter').html('Apply').attr('disabled', false);
        return false;
    }
    category = category.val()
    breed = breed.val()
    $.ajax({
        url: base_url+ "api/save_filter_data",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            category,breed
        },
        dataType: "JSON",
        success: function (data) {
            $('#btn-apllyfilter').html('Apply').attr('disabled', false);
            if (data.status==true){
                _closeCardFilter(), _loadContentPopuler(), _loadAllContentPets();
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-apllyfilter').html('Apply').attr('disabled', false);
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// open card Filter
const _openCardFilter = () => {
    _loadDataFilter();
    $('#card-content').hide();
    $('#card-filter').show();
}
// close card Filter
const _closeCardFilter = () => {
    $('#card-content').show();
    $('#card-filter').hide();
}
// load data filters
const _loadDataFilter = () => {
    $.ajax({
        url: base_url+ "api/load_data_filter",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let filter = data.row;
            rangeSlider("distance-slider", filter.distance);
            // category
            var category = '';
            if(filter.fl_category){
                var category = filter.fl_category.split(", ");
            }
            $('#category').selectpicker('val', category);
            // breed                          
            var breed = '';
            if(filter.fl_breed){
                var breed = filter.fl_breed.split(", ");
            }
            $('#breed').selectpicker('val', breed);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// load populer pets
const _loadContentPopuler = () => {
    let swipperPopulerContent = '';
    for (let i = 0; i < 4; i++) {
        swipperPopulerContent += ` <div class="swiper-slide">
        <div class="dz-media-card style-4">
            <a href="javascript:void(0);">
                <div class="dz-media">
                    <svg class="bd-placeholder-img rounded w-100 h-275px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#868e96"></rect>
                    </svg>
                </div>
                <div class="dz-content">
                    <h5 class="placeholder-glow w-100 d-flex flex-row flex-column">
                        <span class="placeholder col-lg-10 rounded mb-1"></span>
                        <span class="placeholder col-lg-8 rounded"></span>
                    </h5>
                </div>
            </a>
        </div>
    </div>`
    }
    $('#card-content .swiper-wrapper').html(swipperPopulerContent);
    $.ajax({
        url: base_url+ "api/pets_populer",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            if (data.status==true){
                let dataPopuler = data.row;
                swipperPopulerContent = '';
                $.each(dataPopuler, function(key, row) {
                    var statusLike;
                    if(row.has_like == 'Y'){
                        statusLike = '<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+row.id+"'"+', '+"'N'"+')" class="dz-icon dz-sp-like" title="Dislike"><i class="fa fa-check"></i></a>';
                    }else{
                        statusLike = '<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+row.id+"'"+', '+"'Y'"+')" class="dz-icon dz-sp-like" title="Like"><i class="flaticon flaticon-star-1"></i></a>';
                    }
                    swipperPopulerContent += `<div class="swiper-slide">
                        <div class="dz-media-card style-4">
                            <a href="`+base_url+`viewpets/`+row.id+`">
                                <div class="dz-media">
                                    <img src="`+row.image_url+`" alt="pet-thumb">
                                </div>
                                <div class="dz-content">
                                    <div class="left-content">
                                        <h6 class="title">`+row.category+`</h6>	
                                        <span class="text-white">`+row.breed+`</span>
                                    </div>
                                    <div class="dz-icon ms-auto me-0 statusLikeIcon`+row.id+`">`+statusLike+`</div>
                                </div>
                            </a>
                        </div>
                    </div>`;
                });
                $('#card-content .swiper-wrapper').html(swipperPopulerContent);
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('load data error');
        }
    });
}
// load all pets
const _loadAllContentPets = () => {
    let contentDivisionAll = '';
    for (let i = 0; i < 4; i++) {
        contentDivisionAll += `<div class="col-6">
        <div class="dz-media-card">
            <a href="javascript:void(0);">
                <div class="dz-media">
                    <svg class="bd-placeholder-img rounded w-100 h-275px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#868e96"></rect>
                    </svg>
                </div>
                <div class="dz-content">
                    <h5 class="placeholder-glow w-100 d-flex flex-row flex-column">
                        <span class="placeholder col-lg-10 rounded mb-1"></span>
                        <span class="placeholder col-lg-8 rounded"></span>
                    </h5>
                </div>
            </a>
        </div>
    </div>`
    }
    $('#card-content .section-all').html(contentDivisionAll);
    $.ajax({
        url: base_url+ "api/pets_allcontent",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            if (data.status==true){
                let dataAll = data.row;
                contentDivisionAll = `<div class="title-bar">
                    <h6 class="title">All</h6>
                </div>`;
                $.each(dataAll, function(key, row) {
                    var statusLike;
                    if(row.has_like == 'Y'){
                        statusLike = '<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+row.id+"'"+', '+"'N'"+')" class="dz-icon dz-sp-like" title="Dislike"><i class="fa fa-check"></i></a>';
                    }else{
                        statusLike = '<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+row.id+"'"+', '+"'Y'"+')" class="dz-icon dz-sp-like" title="Like"><i class="flaticon flaticon-star-1"></i></a>';
                    }
                    contentDivisionAll += `<div class="col-6">
                        <div class="dz-media-card style-4">
                            <a href="`+base_url+`viewpets/`+row.id+`">
                                <div class="dz-media">
                                    <img src="`+row.image_url+`" alt="pet-thumb">
                                </div>
                                <div class="dz-content">
                                    <div class="left-content">
                                        <h6 class="title">`+row.category+`</h6>	
                                        <span class="text-white">`+row.breed+`</span>
                                    </div>
                                    <div class="dz-icon ms-auto me-0 statusLikeIcon`+row.id+`">`+statusLike+`</div>
                                </div>
                            </a>
                        </div>
                    </div>`;
                });
                $('#card-content .section-all').html(contentDivisionAll);
            }else{
                toastr.error(data.message, 'Ooops!', {"progressBar": true, "timeOut": 2500});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('load data error');
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
                $('.statusLikeIcon'+fid_pets+'').html('<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+fid_pets+"'"+', '+"'N'"+')" class="dz-icon dz-sp-like" title="Dislike"><i class="fa fa-check"></i></a>');
            }else{
                $('.statusLikeIcon'+fid_pets+'').html('<a href="javascript:void(0);" onclick="updateStatusLikePets('+"'"+fid_pets+"'"+', '+"'Y'"+')" class="dz-icon dz-sp-like" title="Like"><i class="flaticon flaticon-star-1"></i></a>');
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Like is error');
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    // category
    var category = [];
    if(fl_category){
        var category = fl_category.split(", ");
    }
    _loadContentPopuler(),_loadAllContentPets();
    loadSelectpicker_category(), loadSelectpicker_breed(category);
});