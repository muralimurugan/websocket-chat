<?php
class Validate_model extends CI_Model {
function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function validateStringLength($string, $min, $max){
        $strLength = strlen($string);
        if(($strLength < $min) || ($strLength > $max)){
            return false;
        }
        else{
            return true;
        }
    }
    
    function validateOnlyAlphaNumeric($string){
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string))
        {
            return false;
        }
        else {
            return true;
        }
    }
    
    function validateEmailAddress($emailAddress){
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
           return false;
        }
        else{
            return true;
        }
    }
}