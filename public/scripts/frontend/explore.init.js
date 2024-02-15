"use strict";
// variable Definition
var page = 1;
// Class Initialization
jQuery(document).ready(function() {
    _loadExplorePets(page);
});
const viewMore = () => {
    page++;
    _loadExplorePets(page);
}
// for handle load more pets
function _loadExplorePets(page) {
    $.ajax({
        url: base_url + "api/explore_pets?page=" + page,
        datatype: "html",
        type: "get",
        beforeSend: function () {
            $('#loaderPlaceholder').show();
        }
    })
    .done(function (response) {
        let dataRow = response.row, bodyContent = '';
        if(dataRow.length == 0){
            $('.btn-viewmore').hide();
        }if (dataRow == '') {
            $('#loaderPlaceholder').hide();
            $('.btn-viewmore').hide();
            return;
        }
        $('.btn-viewmore').show();
        $('#loaderPlaceholder').hide();

        $.each(dataRow, function(key, row) {
            bodyContent += `<div class="col-6">
            <div class="dz-media-card">
                <a href="`+base_url+`viewpets/`+row.id+`">
                    <div class="dz-media">
                        <img src="`+row.image_url+`" alt="">
                    </div>
                    <div class="dz-content">
                        <h6 class="title">`+row.category+`</h6>	
                        <span class="about">`+row.breed+`</span>	
                    </div>
                </a>
            </div>
        </div>`;
        });
        $("#section-explore").append(bodyContent);
        window.scrollTo(0, document.body.scrollHeight);
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        console.log('Load data error');
    });
}