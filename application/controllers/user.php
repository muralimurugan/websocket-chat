<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	public function index()
	{
		$this->load->view('profile_view');
	}
        
        public function create()
	{
		$this->load->view('create_user_view');
	}
        
        public function view()
        {
            $this->load->view('user_view');
        }
        public function updateDetails(){
            $this->load->model('user_model');
            $this->load->model('validate_model');
            
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $firstname = $this->input->post('firstname');
            $lastname = $this->input->post('lastname');
            
            if(!$this->validate_model->validateStringLength($username, 5, 15))
            {
                exit(json_encode(array('status' => 'error', 'errormessage' => 'username must be between 5 and 15 characters')));
            }
            if(!$this->validate_model->validateOnlyAlphaNumeric($username)){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'Special characters are not allowed in your username')));
            }
            if($this->user_model->checkUsernameInUse($username)){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'username address is already in use')));
            }
            if(!$this->validate_model->validateEmailAddress($email)){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'Invalid email address')));
            }
            if($this->user_model->checkUserExistsByEmail($email)){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'email address is already in use')));
            }
            if(!$this->validate_model->validateStringLength($firstname, 0, 50))
            {
                exit(json_encode(array('status' => 'error', 'errormessage' => 'First name can not be longer than 50 characters')));
            }
            if(!$this->validate_model->validateStringLength($lastname, 0, 50))
            {
                exit(json_encode(array('status' => 'error', 'errormessage' => 'Last name can not be longer than 50 characters')));
            }
            if(!$this->validate_model->validateOnlyAlphaNumeric($firstname)){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'Special characters are not allowed in your first name')));
            }
            if(!$this->validate_model->validateOnlyAlphaNumeric($lastname)){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'Special characters are not allowed in your last name')));
            }
            $updateData = array(
                'username' => $username,
                'email_address' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname
            );
            
            $updateStatus = $this->user_model->updateUserDetails($updateData);
            if(isset($updateStatus)){
                $this->session->set_userdata($updateData);
                echo json_encode(array('status' => 'success', 'userdata' => $updateData));
            }
            else{
                echo json_encode(array('status' => 'error', 'errormessage' => 'unable to update user'));
            }
        }
        
        public function resetPassword(){
            $this->load->model('auth_model');
            $this->load->model('user_model');
            
            $newPassword = $this->input->post('password');
            if(!$this->validate_model->validateStringLength($newPassword, 5, 100))
            {
                exit(json_encode(array('status' => 'error', 'errormessage' => 'password must be between 5 and 100 characters')));
            }
            $hashedPassword = $this->auth_model->hashPassword($newPassword);
            $updatePasswordStatus = $this->user_model->resetUsersPassword($hashedPassword);
            
            if(isset($updatePasswordStatus)){
                echo json_encode(array('status' => 'success'));
            }
            else{
                echo json_encode(array('status' => 'error', 'errormessage' => 'uanble to update your password'));
            }
        }
        
        public function uploadAvatar(){
            $this->load->library('upload');
            $this->load->model('user_model');
            $file_element_name = 'userfile'; 
            $config['upload_path'] = './user_avatars/';
            $config['allowed_types'] = 'jpg|png';
            $config['encrypt_name'] = TRUE;
            
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($file_element_name))
            {
               $msg = $this->upload->display_errors('', '');
            }
            else{
                $data = $this->upload->data($file_element_name);
                $newAvatarFileName = $data['file_name'];
                $newAvatarData = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'avatar' => $newAvatarFileName
                );
                $avatarUpdated = $this->user_model->setNewAvatar($this->session->userdata('user_id'),$newAvatarFileName);
                $this->session->set_userdata(array('avatar' => './user_avatars/' . $newAvatarFileName));
            }
            if(isset($msg)){
                exit(json_encode(array('status' => 'error', 'errormessage' => $msg)));
            }
            echo json_encode(array('status' => 'success', 'avatarlink' => './user_avatars/' . $newAvatarFileName));
        }
        
        public function setFacebookAvatar(){
            $this->load->model('user_model');
            $setFacebookAvatarStatus = $this->user_model->setFaceBookAvatar($this->session->userdata('user_id'));
            if($setFacebookAvatarStatus['status'] == 'success'){
                $this->session->set_userdata(array('avatar' => $setFacebookAvatarStatus['avatarlink']));
                echo json_encode(array('status' => 'success', 'avatarlink' => $setFacebookAvatarStatus['avatarlink']));
            }
            else{
                echo json_encode(array('status' => 'error', 'errormessage' => $setFacebookAvatarStatus));
            }
        }
        
        public function submitNewUser(){
            error_log('submitting user');
            $this->load->model('auth_model');
            $this->load->model('user_model');
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            $salt = $this->auth_model->generateSalt();
            $hash = $this->auth_model->hashPassword($password);
            
            $userData = array(
                "username" => $username,
                "email_address" => $email,
                "password" => $hash,
                "salt" => $salt,
                "ip_address" => $this->session->userdata('ip_address')
            );
            error_log(print_r($userData,true));
            $userExistsData = $this->user_model->checkUserExistsByEmail($email);
            error_log("user data: " . print_r($userExistsData,true));
            if($userExistsData != FALSE){
                error_log("users exists!!!!");
               echo json_encode(array("status" => "error", "errormessage" => "Email Address already in use"));
               die();
            }
            
            $userId = $this->user_model->addUser($userData); 
            $userData['password'] = $password;
            if(!empty($userId)){
                $this->auth_model->authenticate($userData);
                echo json_encode(array("status" => "success"));
                die();
            }
            else
                echo json_encode(array("status" => "error", "errormessage" => "Failed to add user"));

        }
}