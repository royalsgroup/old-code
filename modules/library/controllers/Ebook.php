<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Ebook.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Ebook
 * @description     : Manage ebook for each class as per school course curriculam.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Ebook extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();     
        $this->load->model('Ebook_Model', 'ebook', true);  
		$this->load->model('academic/Classes_Model', 'classes', true); 
        $this->load->library('message/message');
    }

    
    
    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Ebook List" user interface                 
     *                    with class wise listing    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function index($class_id = null, $school_id = null) {
        
        check_permission(VIEW);
        
         if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
             redirect('library/ebook/index');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['ebooks'] = $this->ebook->get_ebook_list($class_id, $school_id);            
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
			//$this->data['classes'] = $this->classes->get_list_by_school($condition['school_id']);
            $this->data['classes'] = $this->classes->get_class_list($condition['school_id'] );
        }
        $this->data['class_list'] = $this->classes->get_class_list($condition['school_id'] );
        
        $this->data['schools'] = $this->schools;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage') .' ' .$this->lang->line('e_book'). ' | ' . SMS);
        $this->layout->view('ebook/index', $this->data);            
       
    }

    
    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Add new Ebook" user interface                 
     *                    and store "Ebook" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add() {
        
        check_permission(ADD);
        if ($_POST) {
            $this->_prepare_ebook_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_ebook_data();

                $insert_id = $this->ebook->insert('ebooks', $data);
                if ($insert_id) {                    
                    $class = $this->ebook->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    $data['class']  = $class;
                    $data['id']     = $insert_id;
                    $this->_send_message_notification($data);
                    create_log('Has been added ebook : '. $data['name'].' for class : '. $class->name);
                                        
                    success($this->lang->line('insert_success'));
                    redirect('library/ebook/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('library/ebook/add/'.$data['class_id']);
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['ebooks'] = $this->ebook->get_ebook_list($class_id);            

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->ebook->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        $this->data['class_list'] = $this->ebook->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('e_book'). ' | ' . SMS);
        $this->layout->view('ebook/index', $this->data);
    }

    
    /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Ebook" user interface                 
     *                    with populated "Ebook" value 
     *                    and update "Ebook" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {       
       
        check_permission(EDIT);
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('library/ebook/index'); 
        }
        
        if ($_POST) {
            $this->_prepare_ebook_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_ebook_data();
                $updated = $this->ebook->update('ebooks', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->ebook->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been updated ebook : '. $data['name'].' for class : '. $class->name);                    
                    
                    success($this->lang->line('update_success'));
                    redirect('library/ebook/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('library/ebook/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['ebook'] = $this->ebook->get_single('ebooks', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['ebook'] = $this->ebook->get_single('ebooks', array('id' => $id));

            if (!$this->data['ebook']) {
               redirect('library/ebook/index');
            }
        }
        
        $class_id = $this->data['ebook']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;
        $this->data['ebooks'] = $this->ebook->get_ebook_list($class_id);            
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->ebook->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        $this->data['class_list'] = $this->ebook->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['school_id'] = $this->data['ebook']->school_id;
        $this->data['schools'] = $this->schools;
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('e_book'). ' | ' . SMS);
        $this->layout->view('ebook/index', $this->data);
        
    }
    
    
    /*****************Function view**********************************
     * @type            : Function
     * @function name   : view
     * @description     : Load user interface with specific ebook data                 
     *                       
     * @param           : $ebook_id integer value
     * @return          : null 
     * ********************************************************** */
    public function view( $ebook_id = null ){
        check_permission(VIEW);
        
        if(!is_numeric($ebook_id)){
             error($this->lang->line('unexpected_error'));
             redirect('library/ebook/index');
        }
        
        $this->data['ebook'] = $this->ebook->get_single_ebook( $ebook_id);
        $class_id = $this->data['ebook']->class_id;
        
        $this->data['ebooks'] = $this->ebook->get_ebook_list($class_id);    

         
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }
        $this->data['classes'] = $this->ebook->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['class_id'] = $class_id;
        
        $this->data['schools'] = $this->schools;
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('e_book'). ' | ' . SMS);
        $this->layout->view('ebook/index', $this->data);
    }

        
     /*****************Function get_single_ebook**********************************
     * @type            : Function
     * @function name   : get_single_ebook
     * @description     : "Load single ebook information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_ebook(){
        
       $ebook_id = $this->input->post('ebook_id');
       
       $this->data['ebook'] = $this->ebook->get_single_ebook($ebook_id);
       echo $this->load->view('ebook/get-single-ebook', $this->data);
    }
        
     /*****************Function get_single_read_ebook**********************************
     * @type            : Function
     * @function name   : get_single_read_ebook
     * @description     : "Load single ebook information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_read_ebook(){
        
       $ebook_id = $this->input->post('ebook_id');
       
       $this->data['ebook'] = $this->ebook->get_single_ebook($ebook_id);
       echo $this->load->view('ebook/get-single-read-ebook', $this->data);
    }
    
    /*****************Function _prepare_ebook_validation**********************************
     * @type            : Function
     * @function name   : _prepare_ebook_validation
     * @description     : Process "ebook" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_ebook_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('name', $this->lang->line('book') . ' ' . $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('edition', $this->lang->line('edition'), 'trim');
        $this->form_validation->set_rules('author', $this->lang->line('author'), 'trim');
        $this->form_validation->set_rules('language', $this->lang->line('language'), 'trim');
        $this->form_validation->set_rules('cover_image', $this->lang->line('book_cover'), 'trim|callback_cover_image');
        $this->form_validation->set_rules('file_name', $this->lang->line('book_cover'), 'trim|callback_file_name');
        
    }
    
    
    /*****************Function cover_image**********************************
     * @type            : Function
     * @function name   : cover_image
     * @description     : Unique check for "ebook file cover_image" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */  
   public function cover_image()
   {  
       
        if ($this->input->post('id')) {
            if (!empty($_FILES['cover_image']['name'])) {
                $name = $_FILES['cover_image']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('cover_image', $this->lang->line('select_valid_file_format'));
                    return FALSE;
                }
            }
        }else{
            if (!empty($_FILES['cover_image']['name'])) {
                $name = $_FILES['cover_image']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('cover_image', $this->lang->line('select_valid_file_format'));
                    return FALSE;
                }
            } 
        }       
    }

       
    
    /*****************Function file_name**********************************
     * @type            : Function
     * @function name   : file_name
     * @description     : Unique check for "ebook file content" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */  
   public function file_name()
   {  
       
        if($this->input->post('id')){
        
            if(!empty($_FILES['file_name']['name'])){
                $name = $_FILES['file_name']['name'];
                $arr = explode('.', $name);
                $ext = end($arr); 
                if($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt'){
                    return TRUE;
                } else {
                    $this->form_validation->set_message('file_name', $this->lang->line('select_valid_file_format'));         
                    return FALSE; 
                }
            }
        
        }else{

            if(!empty($_FILES['file_name']['name']))
            { 
               $name = $_FILES['file_name']['name'];
               $arr = explode('.', $name);
               $ext = end($arr); 
               if($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt'){
                   return TRUE;
               } else {
                   $this->form_validation->set_message('file_name', $this->lang->line('select_valid_file_format'));         
                   return FALSE; 
               }         
            }
        }       
   }

   
    /*****************Function _get_posted_ebook_data**********************************
     * @type            : Function
     * @function name   : _get_posted_ebook_data
     * @description     : Prepare "Ebook" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_ebook_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'subject_id';
        $items[] = 'name';
        $items[] = 'edition';
        $items[] = 'author';
        $items[] = 'language';
        
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
        }
                
        if(!empty($_FILES['cover_image'])){  
           $data['cover_image'] = $this->_upload_cover_image();
        }
        if(!empty($_FILES['file_name'])){  
           $data['file_name'] = $this->_upload_file_name();
        }
        
        return $data;
    }

    
    /*****************Function file_name**********************************
     * @type            : Function
     * @function name   : file_name
     * @description     : Process "Ebook" file upload to server and 
     *                      return file to store into database                  
     * @param           : null
     * @return          : $return_ebook string value 
     * ********************************************************** */
    private function _upload_file_name(){
           
        $prev_ebook     = $this->input->post('prev_file_name');
        $ebook          = $_FILES['file_name']['name'];
        $ebook_type     = $_FILES['file_name']['type'];
        $return_ebook   = '';

        if ($ebook != "") {
            if ($ebook_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || 
                    $ebook_type == 'application/msword' || $ebook_type == 'text/plain' ||
                    $ebook_type == 'application/vnd.ms-office' || $ebook_type == 'application/pdf') {

                $destination = 'assets/uploads/ebook/';               

                $file_type  = explode(".", $ebook);
                $extension  = strtolower($file_type[count($file_type) - 1]);
                $ebook_path = 'ebook-'.time() . '-gsms.' . $extension;

                move_uploaded_file($_FILES['file_name']['tmp_name'], $destination . $ebook_path);

                // need to unlink previous ebook
                if ($prev_ebook != "") {
                    if (file_exists($destination . $prev_ebook)) {
                        @unlink($destination . $prev_ebook);
                    }
                }

                $return_ebook = $ebook_path;
            }
        } else {
            $return_ebook = $prev_ebook;
        }

        return $return_ebook;
    }

    
    
    
    
    /*****************Function _upload_cover_image**********************************
    * @type            : Function
    * @function name   : _upload_cover_image
    * @description     : Process to to upload _upload_cove image in the server
    *                    and return image name                   
    *                       
    * @param           : null
    * @return          : $return_image string value 
    * ********************************************************** */
    private function _upload_cover_image() {

        $prev_image = $this->input->post('prev_cover_image');
        $image = $_FILES['cover_image']['name'];
        $image_type = $_FILES['cover_image']['type'];
        $return_image = '';
        if ($image != "") {
            if ($image_type == 'image/jpeg' || $image_type == 'image/pjpeg' ||
                    $image_type == 'image/jpg' || $image_type == 'image/png' ||
                    $image_type == 'image/x-png' || $image_type == 'image/gif') {

                $destination = 'assets/uploads/ebook/';

                $file_type = explode(".", $image);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $image_path = 'cover-image-' . time() . '-gsms.' . $extension;

                move_uploaded_file($_FILES['cover_image']['tmp_name'], $destination . $image_path);
                if($converted_file = webpConverter($destination . $image_path,null, 415,515))
                {
                    $image_path = get_filename($converted_file);
                }
                // need to unlink previous image
                if ($prev_image != "") {
                    if (file_exists($destination . $prev_image)) {
                        @unlink($destination . $prev_image);
                    }
                }

                $return_image = $image_path;
            }
        } else {
            $return_image = $prev_image;
        }

        return $return_image;
    }

      
    
    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Ebook" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('library/ebook/index');
        }
        
        $ebook = $this->ebook->get_single('ebooks', array('id' => $id));
        if ($this->ebook->delete('ebooks', array('id' => $id))) {
               
            $class = $this->ebook->get_single('classes', array('id' => $ebook->class_id, 'school_id'=>$ebook->school_id));
            create_log('Has been deleted a ebook : '. $ebook->name.' for class:'. $class->name);
                        
            // delete ebook file
            $destination = 'assets/uploads/';
            if (file_exists( $destination.'/ebook/'.$ebook->file_name)) {
                @unlink($destination.'/ebook/'.$ebook->file_name);
            }
            if (file_exists( $destination.'/ebook/'.$ebook->cover_image)) {
                @unlink($destination.'/ebook/'.$ebook->cover_image);
            }
            
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('library/ebook/index/'.$ebook->class_id);
    }
    
      /*****************Function _send_message_notification**********************************
    * @type            : Function
    * @function name   : _send_sms_notification
    * @description     : Process to send SMS to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send_message_notification($message_data = null) {
 
        $data                       = array("");
        $data['school_id']          = $message_data['school_id'];
        $school                     = $this->ebook->get_school_by_id($message_data['school_id']);
        $class                      = $message_data['class'];
        $students                   = $this->ebook->get_student_list($message_data['school_id'], $message_data['class_id'], null, $school->academic_year_id);
        $ebook                     = $this->ebook->get_single_ebook($message_data['id']);  
        $data['academic_year_id']   = $school->academic_year_id;

        $data['subject']            = "New ".$ebook->subject." ".$this->lang->line('e_book');   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
        foreach ($students as $obj) {

            // student message
            if($obj->user_id != ''){                    
                $message = $this->lang->line('hi'). ' '. $obj->name.',';
                $message .= '<br/>';
                $message .= $this->lang->line('following_is_ebook_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('class').': ' . $ebook->class_name;
                $message .= '<br/>';
                $message .= $this->lang->line('subject'). ': ' . $ebook->subject;
                $message .= '<br/>';

                $message .= $this->lang->line('e_book').': ' . $ebook->name;
                $message .= '<br/>';
                $message .= $this->lang->line('author').': ' . $ebook->author;
 
      
                $message .= '<br/><br/>';

                $message .= $this->lang->line('thank_you').'<br/>';
              
                $data['body'] = $message;
                $data['receiver_id'] = $obj->user_id;
                $data['receiver_role_id'] = STUDENT;
                $this->message->send_message($data);
            }
        }
    } 
    

}
