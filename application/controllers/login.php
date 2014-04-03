<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$this->load->view('login_view');
	}

        function submit()
            {
                $this->load->model('Auth_model');
                //put the post data into an array for validation
                $user_data = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password')
                );

                error_log(print_r($user_data,true));
                //send the post data through the 'authenticate' method to determine if they have an account
                if ($this->Auth_model->authenticate($user_data) === FALSE) {
                    echo json_encode(array('status' => 'error'));
                }
                else{
                    echo json_encode(array('status' => 'success'));
                }
            }
}