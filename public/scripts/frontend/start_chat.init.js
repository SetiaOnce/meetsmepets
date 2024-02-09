//Chat owner
const _loadPersonalChat = () => {
	$.ajax({
        url: base_url+ "api/personal_chat",
        type: "GET",
        dataType: "JSON",
        data: {
            idp_owner
        },success: function (data) {
            let dataChat = data.row; 
            let contenMessagePersonal = '';
            if(dataChat.length == 0){

            }else{
                $.each(dataChat, function(key, row) {
                    contenMessagePersonal += `<div class="chat-content `+row.is_me+`">
                        <div class="message-item mb-2">
                            <div class="bubble">`+row.message+`</div>    
                            <div class="message-time">08:40</div>    
                        </div>
                    </div>`;
                });
            }
            $('#sectionChat .chat-message').html(contenMessagePersonal);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// send message
$('#btn-send').on('click', function (e) {
    e.preventDefault();
    var message = $('#message');
    if (message.val() == '') {
        return false;
    }
    message = message.val()
    _handleChatBox(message, 'ME');
    $.ajax({
        url: base_url+ "api/send_message",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: {
            message,idp_owner
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
const _handleChatBox = (message, category) => {
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
    var ampm = current.getHours() >= 12 ? 'pm' : 'am';
    var actualTime = (current.getHours()% 12 +':'+current.getMinutes() +' '+ ampm);
    
    var messageEmojiHtml = '<div class="chat-content '+position+'">'+
        '<div class="message-item">'+
            '<div class="bubble">'+chatEmojiArea+'</div>'+
            '<div class="message-time">'+actualTime+'</div>'+
        '</div>'+
    '</div>';
        
    if(chatEmojiArea.length > 0){   
        $('.chat-box-area').append(messageEmojiHtml);
    }
    
    var messageHtml = '<div class="chat-content '+position+'">'+
        '<div class="message-item">'+
            '<div class="bubble">'+chatMessageValue+'</div>'+
            '<div class="message-time">'+actualTime+'</div>'+
        '</div>'+
    '</div>';
    
    if(chatMessageValue.length > 0){
        var appendMessage = $('.chat-box-area').append(messageHtml);
    }
    
    //console.log(document.body.scrollHeight)
    window.scrollTo(0, document.body.scrollHeight);
    var clearChatInputE = $('.append-media').empty();    
}
// Realtime chat
const realtimeChat = () => {
    // realtime online status
    Pusher.logToConsole = true;
    var pusher = new Pusher('776e717ca68fa5373ae0', {
        cluster: 'ap1'
    });
    var channel = pusher.subscribe('chat-personal');
    channel.bind('App\\Events\\ChatPersonal', function(data) {
        if(data.user != my_id){
            _handleChatBox(data.message);
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadPersonalChat(), realtimeChat();
});