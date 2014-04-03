<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends CI_Controller {

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
