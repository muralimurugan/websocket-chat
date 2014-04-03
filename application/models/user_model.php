<?php
class User_model extends CI_Model {
function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function addUser($userData){
        $this->db->insert('users', $userData); 
        return $this->db->insert_id();
    }
	
    function checkFacebookUserExists($facebookId){
        $this->db->where('facebook_user_details_id', $facebookId);
        $query = $this->db->get('facebook_user_details');
        $returnData = $query->result_array();
        return $returnData;
    }
    
    function checkUserExistsByEmail($email){
        $this->db->where('email_address', $email);
        $this->db->where('user_id !=', $this->session->userdata('user_id'));
        $query = $this->db->get('users');
        $returnData = $query->result_array();
        if($query->num_rows() == 0){
            return FALSE;
        }
        else{
            return $returnData;
        }
    }
    
    function checkUsernameInUse($username){
        $this->db->where('username', $username);
        $this->db->where('user_id !=', $this->session->userdata('user_id'));
        $query = $this->db->get('users');
        $returnData = $query->result_array();
        if($query->num_rows() == 0){
            return FALSE;
        }
        else{
            return $returnData;
        }
    }
    
    function updateUserDetails($updateData){
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->update('users', $updateData);
        return $this->db->affected_rows();
    }
    
    function resetUsersPassword($newPassword){
        $updateData = array(
            'password' => $newPassword
        );
        
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->update('users', $updateData);
        return $this->db->affected_rows();
    }
    
    function setNewAvatar($userId, $fileName){
        $updateData = array(
            'avatar' => './user_avatars/' . $fileName
        );
        $this->db->where('user_id', $userId);
        $this->db->update('users', $updateData); 
        return $this->db->affected_rows();
    }
    
    function setFaceBookAvatar($userId){
        $this->db->where('user_id', $userId);
        $query = $this->db->get('facebook_user_details');
        $userFacebookData = $query->result_array();
        if(count($userFacebookData) == 1){
            $facebookAvatar = 'http://graph.facebook.com/' . $userFacebookData[0]['username'] . '/picture?type=large';
            $updateData = array('avatar' => $facebookAvatar);
            $this->db->where('user_id', $userId);
            $this->db->update('users', $updateData);
            $numAffectedRows = $this->db->affected_rows();
            if($numAffectedRows == 1){
                $returnArray = array(
                    'status' => 'success',
                    'avatarlink' => $facebookAvatar
                );
                
                return $returnArray;
            }
            else{
                $errorMessage = "Facebook avatar image alraedy set!";
            }
        }
        else{
            $errorMessage = "No facebook details found, please connect your account first";
        }
        
        return $errorMessage;
    }
    
    function getUserDataById($userId){
        $this->db->where('user_id', $userId);
        $query = $this->db->get('users');
        return $query->result_array();
    }
    
    function getFacebookAvatar($userId){
        $this->db->where('user_id', $userId);
        $query = $this->db->get('facebook_user_details');
        $facebookDetails = $query->result_array();
        
        return $facebookDetails[0]['facebook_user_details_id'];
    }
    
    function insertFacebookUser($userDetails, $userId){
        $insertData = array(
            "facebook_user_details_id" => $userDetails->id,
            "user_id" => $userId,
            "name" => $userDetails->name,
            "first_name" => $userDetails->first_name,
            "last_name" => $userDetails->last_name,
            "link"  =>  $userDetails->link,
            "username" => $userDetails->username,
            "gender"    => $userDetails->gender,
            "email"     => $userDetails->email,
            "timezone" => $userDetails->timezone,
            "locale" => $userDetails->locale,
            "verified" => $userDetails->verified,
            "updated_time" => $userDetails->updated_time
        );
        $this->db->insert('facebook_user_details', $insertData); 
        return $this->db->insert_id();
    }
    
    function insertUser($userDetails){
        $this->db->insert('users', $userDetails); 
        return $this->db->insert_id();
    }
    
    public function sendPassordResetEmail($emailAddress, $userId)
        {
            $this->load->library('email');
            
            $hash = md5(uniqid(mt_rand(), true));
            $insertData = array(
                "user_id" => $userId,
                "hash" => $hash
            );
            
            $resetUrl = 'http://chat.burnsforcedevelopment.com/users/changepassword/' . $hash;
            $this->db->insert('forgot_password', $insertData); 
            error_log('sending email');
            $this->email->from('test@dev.whimming.com', 'Patrick Burns');
            $this->email->to($emailAddress); 

            $this->email->subject('Email Test');
            $this->email->message('To complete the password reset process please visit the following webpage: ' . $resetUrl);	

            $this->email->send();
            
        }
}