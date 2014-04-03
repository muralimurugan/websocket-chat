<?php
class Message_model extends CI_Model {
function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function insertMessage($messageData){
        $this->db->insert('messages', $messageData); 
        return $this->db->insert_id();
    }
    
    function getChatMessages($limit, $start){
         $this->db->limit($limit, $start);
         $this->db->order_by('message_id', "desc"); 
         $query = $this->db->get('messages');
         return $query->result_array();
    }
    
    function generateMessageHtml($messageData){
        $datetime = new DateTime($messageData['datetime']);
        $isoDateTime = $datetime->format(DateTime::ISO8601);
        $messageHtml = '<li class="left clearfix">'
                            .'<span class="chat-img pull-left">'
                                .'<img src="'. $messageData['avatar'] . '" alt="User Avatar" class="img-circle" />'
                            .'</span>'
                            .'<div class="chat-body clearfix">'
                                .'<div class="header">'
                                    .'<strong class="primary-font">' . $messageData['username'] . '</strong>'
                                    .'<small class="pull-right  text-muted"><span class="glyphicon glyphicon-time"></span><time class="fancyTime" datetime="' . $isoDateTime . '"></time></small>'
                                .'</div>'
                                .'<p>'
                                    .htmlentities($messageData['message'])
                                .'</p>'
                            .'</div>'
                        .'</li>';
        
        return $messageHtml;
    }
}