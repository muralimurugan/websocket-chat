$( document ).ready(function() {
    $('#registerButton').on('click', function(event){
        event.preventDefault();
        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var button = $('#registerButton');
        var messageContainer = $('#registerMessageContainer');
        var inputGroup;
        var errorMessage;
        
        if(!checkStringLength(username, 5, 15)){
            inputGroup = $('#registerUsernameInputGroup');
            errorMessage = 'Your username must be between 5 and 15 characters';
            showError(button, inputGroup, messageContainer, errorMessage);
            return false;
        }
        else if(!checkSpecialChars(username)){
            inputGroup = $('#registerUsernameInputGroup');
            errorMessage = 'Special characters not allowed in your username';
            showError(button, inputGroup, messageContainer, errorMessage);
            return false;
       }
       else if(!checkEmail(email)){
            inputGroup = $('#registerEmailInputGroup');
            errorMessage = 'Invalid email address';
            showError(button, inputGroup, messageContainer, errorMessage);
            return false;
       }
       else if(!checkStringLength(password1, 5, 100)){
            inputGroup = $('#registerPasswordInputGroup');
            errorMessage = 'Your password must be between 5 and 100 characters';
            showError(button, inputGroup, messageContainer, errorMessage);
            return false;
       }
        
        $.ajax({
                type: 'POST',
                url: '/user/submitNewUser/',
                dataType: "json", 
                data: 'username=' + username + '&email=' + email + '&password=' + password, 
                success: function(data, textStatus, jqXHR){
                    if(data.status == "success"){
                        window.location.replace("/");
                    }
                    else if(data.status == 'error'){
                        inputGroup = $('#registerPasswordInputGroup');
                        errorMessage = data.errormessage;
                        showError(button, inputGroup, messageContainer, errorMessage);
                    }
                }
         });
    });
});

function showError(button, inputGroup, container, message){
    button.removeClass('active');
    inputGroup.addClass('has-error');
    container.fadeOut('fast', function(){
        $(this).empty().append('<div class="alert alert-danger">' + message + '</div>').fadeIn('fast');
    });
}