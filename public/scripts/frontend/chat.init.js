//Owner love
const _loadOwnerLove = () => {
	$.ajax({
        url: base_url+ "api/chat_owner_love",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let dataOwner = data.row; 
            let contentOwnerLove = '';
            if(dataOwner.length == 0){

            }else{
                $.each(dataOwner, function(key, row) {
                    var statusActive = '';
                    if(row.is_active == 'Y'){
                        statusActive = 'active';
                    }else{
                        statusActive = '';
                    }
                    contentOwnerLove += `<div class="swiper-slide">
                        <a href="`+base_url+`chat/`+row.id+`" class="recent `+statusActive+`">								
                            <div class="media media-60 rounded-circle">
                                <img src="`+row.thumb_url+`" alt="">
                            </div>
                            <span>`+row.name+`</span>
                        </a>
                    </div>`;
                });
            }
            $('#sectionChat .owner-love').html(contentOwnerLove);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
//All message
const _loadAllMessage = () => {
	$.ajax({
        url: base_url+ "api/all_message",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            let dataMessage = data.row; 
            let contentAllMessage = '';
            if(dataMessage.length == 0){
                contentAllMessage += `<div class="alert alert-light solid alert-dismissible fade show">
                    <i><strong>Hallo!</strong> Your message is still empty.</i>
                </div>`;
            }else{
                $.each(dataMessage, function(key, row) {
                    // status active
                    var statusActive = '';
                    if(row.is_active == 'Y'){
                        statusActive = 'active';
                    }else{
                        statusActive = '';
                    }
                    // status read
                    var statusRead = '';
                    if(row.is_read == 'Y'){
                        statusRead = `<div class="seen-btn dz-flex-box ">
                            <i class="icon feather icon-check text-success"></i>
                        </div>`;
                    }else{
                        statusRead = '';
                    }
                    contentAllMessage += `<li class="`+statusActive+`">
                    <a href="`+base_url+`chat/`+row.id+`">
                        <div class="media media-60">
                            <img src="`+row.thumb_url+`" alt="user-image">
                        </div>
                        <div class="media-content">
                            <div>
                                <h6 class="name">`+row.name+`</h6>
                                <p class="last-msg">`+row.message+`</p>
                            </div>
                            <div class="right-content">
                                <span class="date">`+row.last_chat+`</span>
                                `+statusRead+`
                            </div>
                        </div>
                    </a>
                </li>`;
                });
            }
            $('#sectionChat .all-message').html(contentAllMessage);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// Realtime status online 
const _realtimeStatusOnline = () => {
    // realtime online status
    Pusher.logToConsole = true;
    var pusher = new Pusher('776e717ca68fa5373ae0', {
        cluster: 'ap1'
    });
    var channel = pusher.subscribe('status-online');
    channel.bind('App\\Events\\StatusOnline', function(data) {
        console.log(data);
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadOwnerLove(), _loadAllMessage(), _realtimeStatusOnline();
});