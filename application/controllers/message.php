<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		redirect('/');
	}
        
        public function submit(){
            if($this->session->userdata('logged_in') != "TRUE"){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'Please log in to submit chat messages')));
            }
            
            $message = $this->input->post('message');
            if($message == ''){
                exit(json_encode(array('status' => 'error', 'errormessage' => 'Please enter a message to submit')));
            }
            
            $this->load->model('message_model');
            
            $messageData = array(
                'user_id' => $this->session->userdata('user_id'),
                'message' => $message,
                'datetime' => date( 'Y-m-d H:i:s')
            );
            
            $returnMessageData = $this->message_model->insertMessage($messageData);
            $messageData['message_id'] = $returnMessageData;
            $messageData['avatar'] = $this->session->userdata('avatar');
            $messageData['username'] = $this->session->userdata('username');
            $messageHtml = $this->message_model->generateMessageHtml($messageData);
            $messageData['messageHtml'] = $messageHtml;
            $messageData['status'] = "success";
            exit(json_encode($messageData));
        }
        
        public function getPaginated(){
            $this->load->model('user_model');
            $this->load->model('message_model');
            $pageNum = $this->uri->segment(3);
            $limit = 10;
            $start = $pageNum * 10;
            $messageData = $this->message_model->getChatMessages($limit, $start);
            
            $returnMessageArray = array();
            foreach($messageData as $message){
                $userData = $this->user_model->getUserDataById($message['user_id']);
                $newMessage = array(
                        'user_id' => $message['user_id'],
                        'message' => $message['message'],
                        'datetime' => $message['datetime'],
                        'avatar' => $userData[0]['avatar'],
                        'username' => $userData[0]['username']
                    );
                    
                    $newMessage['messageHtml'] = $this->message_model->generateMessageHtml($newMessage);
                    
                    array_push($returnMessageArray, $newMessage);
            }
            echo json_encode(array('status' => 'success', 'messageData' => $returnMessageArray ));
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */