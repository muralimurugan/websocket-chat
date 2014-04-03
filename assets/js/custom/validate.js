function checkEmpty(value){
    if (typeof(value) === 'undefined' || value === null || value === ""){
        return false;
    }
    else{
        return true;
    }
}

function checkStringLength(string, min, max){
    var stringLength = string.length;
    if((stringLength < min) || (stringLength > max)){
        return false;
    }
    else{
        return true;
    }
}

function checkPasswordsMatch(password1, password2){
    if(password1 !== password2){
        return false;
    }
    else{
        return true;
    }
}

function checkEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

function checkSpecialChars(string){
 return !/[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(string);
}