<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Message {

    public $type = "";
   
    function __construct() {
        $this->ci = & get_instance();
        $this->ci ->load->model('message/Message_Model', 'message1', true);
    }

    function send_message($message_data) {

        if ($message_data) {

            $data = array();
            
            $data['school_id']  = $message_data['school_id'];
            $data['subject']    = $message_data['subject'];
            $data['body']       = $message_data['body'];
            $data['academic_year_id'] = $message_data['academic_year_id'];
            $data['attachment'] = '';

            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $message_data['sender_id'];
           
            $insert_id = $this->ci->message1->insert('messages', $data);
            
            if($insert_id )
            {
                // default value for relation table
                $relation_data = array();
               
                $relation_data['school_id'] = $data['school_id'] ;
                $relation_data['sender_id'] = $message_data['sender_id'];
                $relation_data['receiver_id'] = $message_data['receiver_id'];
                $relation_data['is_trash'] = 0;
                $relation_data['is_draft'] = 0;
                $relation_data['is_favorite'] = 0;
                $relation_data['is_read'] = 0;
                $relation_data['status'] = 1;
                $relation_data['message_id'] = $insert_id;
                $relation_data['created_at'] = date('Y-m-d H:i:s');
                $relation_data['created_by'] = $message_data['sender_id'];
                $relation_data['is_draft'] = 0;
                // save message relationships  for sender
                //$relation_data['owner_id'] = $message_data['sender_id'];
                //$relation_data['role_id'] = $message_data['sender_role_id']; // opposite                 
                //$this->ci->message1->insert('message_relationships', $relation_data);
                // save message relationships  for receiver
                $relation_data['owner_id'] = $message_data['receiver_id'];
                $relation_data['role_id'] = $message_data['receiver_role_id']; // opposite  
                $insert = $this->ci->message1->insert('message_relationships', $relation_data);
                if($insert)
                {
                    log_message('debug', "Message sent ");
                }
                
            }
        }        
    }

}

?>