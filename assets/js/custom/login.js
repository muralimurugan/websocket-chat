$( document ).ready(function() {
    $('#login').click(function(event){
    console.log('clicked');
        event.preventDefault();
        var username = $('#username').val();
        var password = $('#password').val();
        
        $.ajax({
                type: 'POST',
                url: '/login/submit/',
                dataType: "json", 
                data: 'username=' + username + '&password=' + password, 
                success: function(data, textStatus, jqXHR){
                    console.log(data);
                    if(data.status == "success"){
                        window.location.replace("/");
                    }
                }
         });
    });
});