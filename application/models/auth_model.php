<?php

class Auth_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function generateSalt() {
        return md5(uniqid(rand(), true));
    }

    function hashPassword($password) {
        return md5($password);
    }

    function authenticate($user) {

        $email = strtolower($user['username']);

        //load codeigniter helper 'security'
        $this->load->helper('security');

        //convert password to md5 hash and assign to variable $password
        $password = $this->hashPassword($user['password']);

        //build query to check if user exists
        $this->db->where('email_address', $email);
        $this->db->or_where('username', $email);
        $this->db->where('password', $password);
        $query = $this->db->get('users');
        $userData = $query->result_array();
        if (empty($userData)) {
            return FALSE;
        }
        error_log("Auth user data: " . print_r($userData, true));

        $this->db->where('user_id', $userData[0]['user_id']);
        $query = $this->db->get('facebook_user_details');
        $facebookUserData = $query->result_array();


        //error_log(print_r($row));
        $userId = $userData[0]['user_id'];
        $firstname = $userData[0]['firstname'];
        $lastname = $userData[0]['lastname'];
        $username = $userData[0]['username'];
        $emailaddress = $userData[0]['email_address'];
        $salt = $userData[0]['salt'];
        $userHash = $userData[0]['password'];
        $isAdmin = $userData[0]['admin'];
        $debug = $userData[0]['debug'];
        $updated = $userData[0]['updated'];
        $avatar = $userData[0]['avatar'];
        $id = isset($facebookUserData[0]['facebook_user_details_id']) ? $facebookUserData[0]['facebook_user_details_id'] : NULL;
        $name = isset($facebookUserData[0]['name']) ? $facebookUserData[0]['name'] : NULL;
        $link = isset($facebookUserData[0]['link']) ? $facebookUserData[0]['link'] : NULL;
        $gender = isset($facebookUserData[0]['gender']) ? $facebookUserData[0]['gender'] : NULL;
        $timezone = isset($facebookUserData[0]['timezone']) ? $facebookUserData[0]['timezone'] : NULL;
        $locale = isset($facebookUserData[0]['locale']) ? $facebookUserData[0]['locale'] : NULL;
        $verified = isset($facebookUserData[0]['verified']) ? $facebookUserData[0]['verified'] : NULL;
        $updated_time = isset($facebookUserData[0]['updated_time']) ? $facebookUserData[0]['updated_time'] : NULL;

        //put the user data into an array for our session
        $session_data = array(
            'logged_in' => TRUE,
            'user_id' => $userId,
            'is_admin' => $isAdmin,
            'debug' => $debug,
            'username' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email_address' => $emailaddress,
            'id' => $id,
            'name' => $name,
            'link' => $link,
            'gender' => $gender,
            'timezone' => $timezone,
            'locale' => $locale,
            'verified' => $verified,
            'updated_time' => $updated_time,
            'avatar' => $avatar,
            'updated' => $updated
        );


        //set session data with the array of the users profile data
        $this->session->set_userdata($session_data);
        return TRUE;
    }

}

?>