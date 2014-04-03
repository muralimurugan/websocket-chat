$( document ).ready(function() {
    
    $('#userPasswordTab').on('click', function(){
        $('#password1').focus();
    });
    
    $('#userDetails').keypress(function(e) {
        if(e.which == 13) {
            updateUserDetails();
        }
    });
    
    $('#userPassword').keypress(function(e) {
        if(e.which == 13) {
            submitPasswordReset();
        }
    });
    
   $('#selectAvatarButton').on('change', function(){
         previewNewAvatar(this);
   });
   
   $('#updateUserButton').on('click',function(event){
       event.preventDefault();
       updateUserDetails();
   });
   
   $('#resetPasswordButton').on('click',function (event){
       event.preventDefault();
       submitPasswordReset();
   }); 
  
   $('#useFaceebokAvatarButton').on('click', function(event){
       event.preventDefault();
       var control = $('#selectAvatarButton');
       control.replaceWith( control = control.clone( true ) );
       $.ajax({
        type: 'POST',
        url: '/user/setFacebookAvatar/',
        dataType: "json", 
        success: function(data, textStatus, jqXHR){
            console.log(data);
            if(data.status === "success"){
                $('#avatarPreview').attr('src', data.avatarlink);
                $('#header-avatar').attr('src', data.avatarlink);
                $('#avatarMessageContainer').fadeOut('fast',function(){
                    $(this).empty().append('<div class="alert alert-success">Successfully changed avatar!</div>').fadeIn('fast');
                });
            }
            if(data.status === 'error'){
                 $('#avatarMessageContainer').fadeOut('fast', function(){
                     $(this).empty().append('<div class="alert alert-danger">'+ data.errormessage +'</div>').fadeIn('fast');
                 })
            }
        }
    });
   });
   
   $('#uploadNewAvatarButton').on('click', function(event){
       event.preventDefault();
       $('#uploadNewAvatarButton').toggleClass('active');
       $('#avatarMessageContainer').empty();
       var url = '/user/uploadAvatar';
       var file = $('#selectAvatarButton').prop('files');
                var fd = new FormData;
                    fd.append('userfile', file[0]);

                var xhr = new XMLHttpRequest();
                    xhr.file = file; // not necessary if you create scopes like this
                    xhr.onload = function (){
                        var data = JSON.parse(xhr.responseText);
                        if(data.status === 'error'){
                            $('#avatarMessageContainer').fadeOut('fast',function(){
                                $(this).empty().append('<div class="alert alert-danger">' + data.errormessage + '</div>').fadeIn('fast');
                            });
                        }
                        else if(data.status === 'success'){
                            control = $('#selectAvatar');
                            $('#avatarMessageContainer').fadeOut('fast', function(){
                               $(this).empty().append('<div class="alert alert-success">Successfully uploaded new avatar!</div>').fadeIn('fast');
                            });
                            $('#uploadNewAvatar').fadeOut('fast');
                            $('#header-avatar').attr('src', data.avatarlink);
                            control.replaceWith( control = control.clone( true ) );
                        }
                        
                        $('#uploadNewAvatarButton').removeClass('active');
                        
                    };
                    xhr.open('post', url, true);
                    xhr.send(fd); 
   });
});

function updateUserDetails(){
    //declare our variables
       var username = $('#username').val();
       var email = $('#email').val();
       var firstName = $('#firstname').val();
       var lastName = $('#lastname').val();
       var button = $('#updateUserButton');
       var messageContainer = $('#userMessageContainer');
       var inputGroup;
       var errorMessage;
       
       //reset our user details form back to normal state
       $('#updateUserButton').addClass('active');
       resetUpdateUserForm();
       
       //time to do some validation
       if(!checkStringLength(username, 5, 15)){
            inputGroup = $('#updateUsernameInputGroup');
            errorMessage = 'Your username must be between 5 and 15 characters';
            showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkSpecialChars(username)){
            inputGroup = $('#updateUsernameInputGroup');
            errorMessage = 'Special characters not allowed in your username';
            showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkEmail(email)){
            inputGroup = $('#updateEmailInputGroup');
            errorMessage = 'Invalid email address';
            showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkSpecialChars(firstName)){
            inputGroup = $('#updateFirstNameGroup');
            errorMessage = 'Special characters not allowed in your first name';
            showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkStringLength(firstName, 0, 50)){
            inputGroup = $('#updateFirstNameGroup');
            errorMessage = 'First name can not be longer than 50 characters';
            showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkSpecialChars(lastName)){
            inputGroup = $('#updateLastNameGroup');
            errorMessage = 'Special characters not allowed in your last name';
            showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkStringLength(lastName, 0, 50)){
            inputGroup = $('#updateLastNameGroup');
            errorMessage = 'Last name can not be longer than 50 characters';
            showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }else{
           
            $.ajax({
                 type: 'POST',
                 url: '/user/updateDetails/',
                 dataType: "json", 
                 data: 'username=' + username + '&email=' + email + '&firstname=' + firstName + '&lastname=' + lastName, 
                 success: function(data, textStatus, jqXHR){
                     console.log(data);
                     if(data.status === "success"){
                         $('#nav-username').fadeOut('fast', function(){
                             $(this).html(data.userdata['username']).fadeIn('fast');
                         });
                         $('#userMessageContainer').fadeOut('fast', function(){
                            $(this).empty().append('<div class="alert alert-success">Successfully updated your account details</div>').fadeIn('fast');
                         });
                     }
                     if(data.status === 'error'){
                         $('#userMessageContainer').fadeOut('fast', function(){
                             $(this).empty().append('<div class="alert alert-danger">' + data.errormessage + '</div>').fadeIn('fast');
                         });
                     }
                 }
             });
       }
       
       $('#updateUserButton').removeClass('active');
       return true;
}

function submitPasswordReset(){
    var password1 = $('#password1').val();
       var password2 = $('#password2').val();
       var button = $('#resetPasswordButton');
       var messageContainer = $('#passwordMessageContainer');
       var inputGroup;
       var errorMessage;
       
       $('#resetPasswordButton').addClass('active');
       resetResetPasswordForm();
       if(!checkEmpty(password1)){
           inputGroup = $('#resetPasswordInputGroup1');
           errorMessage = 'You must enter a password';
           showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkStringLength(password1, 5, 100)){
           inputGroup = $('#resetPasswordInputGroup1');
           errorMessage = 'Your password must be between 5 and 100 characters';
           showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkStringLength(password2, 5, 100)){
           inputGroup = $('#resetPasswordInputGroup2');
           errorMessage = 'Your password must be between 5 and 100 characters';
           showError(button, inputGroup, messageContainer, errorMessage);
           return false;
       }
       else if(!checkPasswordsMatch(password1, password2)){
           $('#resetPasswordButton').removeClass('active');
           $('#resetPasswordInputGroup1').addClass('has-error');
           $('#resetPasswordInputGroup2').addClass('has-error');
           $('#passwordMessageContainer').fadeOut('fast', function(){
               $(this).empty().append('<div class="alert alert-danger">Your passwords do not match</div>').fadeIn('fast');
           });
           return false;
       }
       else{
           
       $.ajax({
            type: 'POST',
            url: '/user/resetPassword/',
            dataType: "json", 
            data: "password=" + password1,
            success: function(data, textStatus, jqXHR){
                console.log(data);
                if(data.status === "success"){
                    $('#password1').val('');
                    $('#password2').val('');
                    $('#passwordMessageContainer').fadeOut('fast', function(){
                        $(this).empty().append('<div class="alert alert-success">Successfully reset password!</div>').fadeIn('fast');
                    })
                }
                if(data.status === 'error'){
                    $('#passwordMessageContainer').fadeOut('fast', function(){
                        $(this).empty().append('<div class="alert alert-danger">' + data.errormessage + '</div>').fadeIn('fast');
                    });
                }
            }
        });
       }
       
        $('#resetPasswordButton').removeClass('active');
        return true;
}

function showError(button, inputGroup, container, message){
    button.removeClass('active');
    inputGroup.addClass('has-error');
    container.fadeOut('fast', function(){
        $(this).empty().append('<div class="alert alert-danger">' + message + '</div>').fadeIn('fast');
    });
}

function resetUpdateUserForm(){
       $('#updateUsernameInputGroup').removeClass('has-error');
       $('#updateEmailInputGroup').removeClass('has-error');
       $('#updateFirstNameGroup').removeClass('has-error');
       $('#updateLastNameGroup').removeClass('has-error');
}

function resetResetPasswordForm(){
       $('#resetPasswordInputGroup1').removeClass('has-error');
       $('#resetPasswordInputGroup2').removeClass('has-error');
}

function previewNewAvatar(input){
   if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#avatarPreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
    $('#uploadNewAvatarButton').show();
}