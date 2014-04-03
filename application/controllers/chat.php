<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends CI_Controller {

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
                $this->load->model('user_model');
                $this->load->model('message_model');
                $limit = 10;
                $start = 0;
                $messageData = $this->message_model->getChatMessages($limit, $start);
                $newMessageDataArray = array();
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
                    
                    array_push($newMessageDataArray, $newMessage);
                }
                $returnData['messageData'] = array_reverse($newMessageDataArray);
                
		$this->load->view('chat_view', $returnData);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */