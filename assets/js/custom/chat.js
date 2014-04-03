$( document ).ready(function() {
    $(".fancyTime").timeago();
    socket = io.connect('http://chat.burnsforcedevelopment.com:8082');
    
    socket.on('startup',function(users){
        sendJGrowlMessage('Connected to websocket server.', 'success');
        buildWhosOnlineHtml(users);
    });
    
    socket.on('newUserConnected', function(users){
        buildWhosOnlineHtml(users);
    });
        
    socket.on('updateChat', function (messageData) {
        $(messageData.messageHtml).appendTo('.chat');
        $(".fancyTime").timeago();
        $(".panel-body").animate({ scrollTop: $('.panel-body')[0].scrollHeight}, 1000);
    });
    
    socket.on('updateUsers', function(users){
       buildWhosOnlineHtml(users);
    });
        
    if($('#loggedIn').val() === 'true'){
        var data = new Object;
        data.username = $('#userName').val();
        data.avatar = $('#avatar').val();
        socket.emit('userConnected',  data);
    }
    
    $(".panel-body").animate({ scrollTop: $('.panel-body')[0].scrollHeight}, 1000);
    
    $('.panel-body').on('scroll', function(){
        if ($(this).scrollTop() === 0) {
            var pageNum = parseInt($('#pageNum').val());
            $('#loadingMessage').remove();
            $('.chat').prepend('<li id="loadingMessage" class="left clearfix">'
                                    +'<div class="chat-body clearfix">'
                                        +'<div class="header">'
                                            +'<h4 style="text-align:center;"><i class="fa fa-refresh fa-spin"></i> Loading More Messages</h4>'
                                        +'</div>'
                                    +'</div>'
                                +'</li>');
            $.ajax({
                type: 'POST',
                url: '/message/getPaginated/' + pageNum,
                dataType: "json", 
                data: 'message=' + message, 
                success: function(data, textStatus, jqXHR){
                    if(data.status === "success"){
                        $('#loadingMessage').fadeOut('slow', function(){
                            $(this).remove();
                            $.each(data.messageData, function(index, message) { 
                                $('.chat').prepend(message.messageHtml);
                                $(".fancyTime").timeago();
                                $('.panel-body').scrollTop(300);
                            }); 
                            $('#pageNum').val(pageNum +1);
                        });
                    }
                }
            });
        }
    });
    
    $("#message").keypress(function (e) {
        if (e.keyCode === 13){
            submitMessage();
        }
    });


    $('#submitNewMessage').click(function(event){
        event.preventDefault();
        submitMessage();
    });
});

function submitMessage(){ 
    var message = $('#message').val();
    var loggedIn = $('#loggedIn').val();
    if(($('#submitNewMessage').hasClass('active')) || ($('#submitNewMessage').hasClass('shake'))){
        return false;
    }
    if(loggedIn === 'false'){
        $('#messageInputGroup').addClass('shake');
        sendJGrowlMessage('Please log in to join the conversation', 'error');
        setTimeout(
            function(){
                $('#messageInputGroup').removeClass('shake');
            }, 1000
        );
       return false;
    }
    if(message === ''){
        $('#messageInputGroup').addClass('shake');
        sendJGrowlMessage(' Please enter a message to submit', 'error');
        setTimeout(
            function(){
                $('#messageInputGroup').removeClass('shake');
            }, 1000
        );
        return false;
    }
    $('#submitNewMessage').toggleClass('active');
    $.ajax({
        type: 'POST',
        url: '/message/submit/',
        dataType: "json", 
        data: 'message=' + message, 
        success: function(data, textStatus, jqXHR){
            $('#message').val('');
            if(data.status === "success"){
                socket.emit('newMessage',  data);
            }
            else{
                sendJGrowlMessage(data.errormessage, 'error');
            }
        }       
    });
    setTimeout(
        function(){
            $('#submitNewMessage').toggleClass('active');
        }, 500
    );
}

function buildWhosOnlineHtml(users){
       $('.user-list').empty();
       $.each(users, function(index, user) { 
           console.log(user);
           $('<li class="left clearfix">'
                +'<span class="chat-img pull-left">'
                    +'<img src="' + user.avatar + '" alt="User Avatar" class="img-circle"  style="margin-right:8px;"/>'
                +'</span>'
                +'<div class="chat-body clearfix">'
                    +'<div class="header">'
                        +'<strong class="primary-font">' + user.username + '</strong>'
                        +'<small class="pull-right  text-muted"><span class="glyphicon glyphicon-time"></span><time class="fancyTime" datetime="' + user.isoDateTime + '"></time></small>'
                    +'</div>'
                    +'<p>'
                        +'<i class="fa fa-circle" style="color:green;"></i> Online'
                    +'</p>'
                +'</div>'
            +'</li>').appendTo('.user-list');
       }); 
       $(".fancyTime").timeago();
}

function sendJGrowlMessage(message, type){
    if(type === "error"){
        $.jGrowl('<i class="glyphicon glyphicon-ban-circle" style="color:red;font-size:14px;padding-right:3px;"></i>' + message, { position: 'bottom-right' }); 
    }
    else if(type === "success"){
        $.jGrowl('<i class="fa fa-check-circle" style="color:green;font-size:14px;padding-right:3px;"></i>' + message, { position: 'bottom-right' });
    }
}