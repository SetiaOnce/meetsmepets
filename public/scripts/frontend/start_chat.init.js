//Chat owner
const _loadPersonalChat = () => {
	$.ajax({
        url: base_url+ "api/personal_chat",
        type: "GET",
        dataType: "JSON",
        data: {
            idp_owner
        },success: function (data) {
            console.log(data);
            let dataChat = data.row; 
            let contenMessagePersonal = '';
            if(dataChat.length == 0){

            }else{
                $.each(dataChat, function(key, row) {
                    contenMessagePersonal += `<div class="chat-content `+row.is_me+`">
                        <div class="message-item mb-2">
                            <div class="bubble">`+row.message+`</div>    
                            <div class="message-time">`+row.timechat+`</div>    
                        </div>
                    </div>`;
                });
            }
            $('#'+key_chat+'').html(contenMessagePersonal);
            window.scrollTo(0, document.body.scrollHeight);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// send message
$('#btn-send').on('click', function (e) {
    e.preventDefault();
    var message = $('#message').val();
    if (message == '') {return false;}
    _handleChatBox(message, key_chat, 'ME');
    $.ajax({
        url: base_url+ "api/send_message",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            message,idp_owner,key_chat
        },
        dataType: "JSON",
        success: function (data) {
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
                location.reload(true);
            });
        }
    });
});
// handle chatbox response
const _handleChatBox = (message, dt_keychat, category) => {
    let position = '';
    if(category == 'ME'){
        position = 'user';
        $('[name="message"]').val(''); 
    }else{
        position = '';
    }
    var chatMessageValue = message;
    var chatEmojiArea = $('.append-media').html();
            
    var current = new Date();
    var hours = current.getHours();
    var minutes = current.getMinutes();
    minutes = minutes < 10 ? '0' + minutes : minutes; // menambahkan 0 di depan menit jika kurang dari 10
    var actualTime = hours + ':' + minutes + ' ';
    
    var messageEmojiHtml = '<div class="chat-content '+position+'">'+
        '<div class="message-item">'+
            '<div class="bubble">'+chatEmojiArea+'</div>'+
            '<div class="message-time">'+actualTime+'</div>'+
        '</div>'+
    '</div>';
    if(chatEmojiArea.length > 0){   
        $('#'+dt_keychat+'').append(messageEmojiHtml);
    }
    var messageHtml = '<div class="chat-content '+position+'">'+
        '<div class="message-item">'+
            '<div class="bubble">'+chatMessageValue+'</div>'+
            '<div class="message-time">'+actualTime+'</div>'+
        '</div>'+
    '</div>';
    if(chatMessageValue.length > 0){
        var appendMessage = $('#'+dt_keychat+'').append(messageHtml);
    }
    window.scrollTo(0, document.body.scrollHeight);
    var clearChatInputE = $('.append-media').empty();    
}
// Realtime chat
const realtimeChat = () => {
    // realtime online status
    Pusher.logToConsole = false;
    var pusher = new Pusher('776e717ca68fa5373ae0', {
        cluster: 'ap1'
    });
    var channel = pusher.subscribe('chat-personal');
    channel.bind('App\\Events\\ChatPersonal', function(data) {
        if (data.from_user !== parseInt(my_id)) {
            _handleChatBox(data.message, data.key_chat, '');
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadPersonalChat(), realtimeChat();
});