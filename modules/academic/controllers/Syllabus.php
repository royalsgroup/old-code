<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Syllabus.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Syllabus
 * @description     : Manage academic syllabus for each class as per school course curriculam.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Syllabus extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();     
        $this->load->model('Syllabus_Model', 'syllabus', true);  
    }

    
    
    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Syllabus List" user interface                 
     *                    with class wise listing    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function index($class_id = null) {
       		
        check_permission(VIEW);
        
         if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index');
        }
        
        // for super admin 
        $school_id = '';
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');           
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
       // $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id, $school_id);            
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $school_id = $this->session->userdata('school_id');
            $this->data['classes'] = $this->syllabus->get_list('classes', $condition, '','', '', 'id', 'ASC');
            //$this->data['classes'] = $this->syllabus->get_class_by_session('classes', $condition['school_id']);
        }
        if(!$school_id)
       {
            error($this->lang->line('select_school'));
       }
       
        $this->data['schools'] = $this->schools;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_syllabus'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);            
       
    }

    
    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Add new Syllabus" user interface                 
     *                    and store "Syllabus" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add() {
        
        check_permission(ADD);
        if ($_POST) {
            // print_r($_POST);exit;
            $this->_prepare_syllabus_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_syllabus_data();

                $insert_id = $this->syllabus->insert('syllabuses', $data);
                if ($insert_id) {
                    
                       
                    $class = $this->syllabus->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been added syllabus : '. $data['title'].' for class : '. $class->name);
                    
                    
                    success($this->lang->line('insert_success'));
                    redirect('academic/syllabus/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/syllabus/add/'.$data['class_id']);
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
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id);            

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->syllabus->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('syllabus'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);
    }

    
    /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Syllabus" user interface                 
     *                    with populated "Syllabus" value 
     *                    and update "Syllabus" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {       
       
        check_permission(EDIT);
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index'); 
        }
        
        if ($_POST) {
            $this->_prepare_syllabus_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_syllabus_data();
                // print_r($data);exit;
                $updated = $this->syllabus->update('syllabuses', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->syllabus->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been updated syllabus : '. $data['title'].' for class : '. $class->name);                    
                    
                    success($this->lang->line('update_success'));
                    redirect('academic/syllabus/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('academic/syllabus/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['syllabus'] = $this->syllabus->get_single('syllabuses', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['syllabus'] = $this->syllabus->get_single('syllabuses', array('id' => $id));

            if (!$this->data['syllabus']) {
               redirect('academic/syllabus/index');
            }
        }
        
        $class_id = $this->data['syllabus']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id, $this->data['syllabus']->school_id);
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id, $this->data['syllabus']->school_id);            
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
           
        }
        
        $this->data['school_id'] = $this->data['syllabus']->school_id;
        $this->data['filter_school_id'] = $this->data['syllabus']->school_id;
        $this->data['schools'] = $this->schools;
         $this->data['disciplines1'] = $this->syllabus->get_disciplines($id);
        
        $this->data['edit'] = TRUE;       
        // print_r( $this->data['disciplines']);exit;
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('syllabus'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);
        
    }
    
    
    /*****************Function view**********************************
     * @type            : Function
     * @function name   : view
     * @description     : Load user interface with specific syllabus data                 
     *                       
     * @param           : $syllabus_id integer value
     * @return          : null 
     * ********************************************************** */
    public function view( $syllabus_id = null ){
        check_permission(VIEW);
        
        if(!is_numeric($syllabus_id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index');
        }
        
        $this->data['syllabus'] = $this->syllabus->get_single_syllabus( $syllabus_id);
        $class_id = $this->data['syllabus']->class_id;
        
        $this->data['syllabuses'] = $this->syllabus->get_syllabus_list($class_id);    

         
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }
        $this->data['classes'] = $this->syllabus->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['class_id'] = $class_id;
        
        $this->data['schools'] = $this->schools;
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('syllabus'). ' | ' . SMS);
        $this->layout->view('syllabus/index', $this->data);
    }

    /*****************Function get_list**********************************
    * @type            : Function
    * @function name   : get_list
    * @description     : Ajax list page                 
    *                       
    * @param           : null
    * @return          : boolean json/
    * ********************************************************** */
	public function get_list(){		
        // for super admin 
       $school_id = '';
       $class_id = "";
       $start=null;
       $limit=null;
       $search_text='';
       if($_POST){            
           $school_id = $this->input->post('school_id');
           $class_id = $this->input->post('class_id');
           $start = $this->input->post('start');
           $limit  = $this->input->post('length');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
       }
       
        
       
       if($school_id ){
         $totalRecords = $this->syllabus->get_syllabus_list_total($class_id, $school_id,null,$search_text);
         
        $syllabuses = $this->syllabus->get_syllabus_list($class_id, $school_id,$start,$limit ,$search_text);
       }
       else
       {
            $totalRecords =0;
            $syllabuses =  array();
       }
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($syllabuses) && !empty($syllabuses)){
               foreach($syllabuses as $obj){
                $guardian_class_data = get_guardian_access_data('class');
                $row_data = array();
                $action = "";
                if($this->session->userdata('role_id') == GUARDIAN){
                    if (!in_array($obj->class_id, $guardian_class_data)) { continue; }
                }elseif($this->session->userdata('role_id') == STUDENT){
                    if ($obj->class_id != $this->session->userdata('class_id')){ continue; }
                }
                if(has_permission(EDIT, 'academic', 'syllabus')){ 
                   $action .='<a href="'.site_url('academic/syllabus/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line('edit').' </a>';
                } 
                if(has_permission(VIEW, 'academic', 'syllabus')){
                    if($obj->syllabus){
                        $action .='<a href="'.UPLOAD_PATH.'syllabus/'.$obj->syllabus.'" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-download"></i> '.$this->lang->line('download').' </a>';
                    }
                        $action .='<a  onclick="get_syllabus_modal('.$obj->id.')"  data-toggle="modal" data-target=".bs-syllabus-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i>'.$this->lang->line('view').' </a>';
                    }
                    if(has_permission(DELETE, 'academic', 'syllabus')){
                        $action .='<a href="'.site_url('academic/syllabus/delete/'.$obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> '.$this->lang->line('delete').' </a>';
                    } 
                    $row_data[] = $count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                   $row_data[] = $obj->title;
                   $row_data[] = $obj->class_name;
                   $row_data[] = $obj->subject;
                   $row_data[] = $obj->session_year;
                   $row_data[] = $action;
                    $data[] = $row_data;
                    $count++;
               }
       }
       else{
           $data=array();
       }
       //print_r($data); exit;
       $response = array(
 "draw" => intval($draw),
 "iTotalRecords" => $totalRecords,
 "iTotalDisplayRecords" => $totalRecords,
 "aaData" => $data
);
echo json_encode($response);
exit;
   }
     /*****************Function get_single_syllabus**********************************
     * @type            : Function
     * @function name   : get_single_syllabus
     * @description     : "Load single syllabus information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_syllabus(){
        
       $syllabus_id = $this->input->post('syllabus_id');
       
       $this->data['syllabus'] = $this->syllabus->get_single_syllabus( $syllabus_id);
       echo $this->load->view('syllabus/get-single-syllabus', $this->data);
    }
    
    /*****************Function _prepare_syllabus_validation**********************************
     * @type            : Function
     * @function name   : _prepare_syllabus_validation
     * @description     : Process "syllabus" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_syllabus_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required');   
        $this->form_validation->set_rules('title', $this->lang->line('syllabus') . ' ' . $this->lang->line('title'), 'trim|required');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
        $this->form_validation->set_rules('syllabus', $this->lang->line('syllabus'), 'trim|callback_syllabus');   
    }
    
    
    /*****************Function syllabus**********************************
     * @type            : Function
     * @function name   : syllabus
     * @description     : Unique check for "syllabus title" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */  
   public function syllabus()
   {  
       
    if($this->input->post('id')){
        
        
        if(!empty($_FILES) && $_FILES['syllabus']['name']){
            $name = $_FILES['syllabus']['name'];
            $arr = explode('.', $name);
            $ext = end($arr); 
            if($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt'){
                return TRUE;
            } else {
                $this->form_validation->set_message('syllabus', $this->lang->line('select_valid_file_format'));         
                return FALSE; 
            }
        }
        return TRUE;

        
    }else{
       
        if(!$_FILES['syllabus']['name'])
        {             
          $this->form_validation->set_message('syllabus', $this->lang->line('select_a_file'));         
          return FALSE; 

        }else{
          $name = $_FILES['syllabus']['name'];
           $arr = explode('.', $name);
           $ext = end($arr); 
           if($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt'){
               return TRUE;
           } else {
               $this->form_validation->set_message('syllabus', $this->lang->line('select_valid_file_format'));         
               return FALSE; 
           }         
        }
    }
       
   }

   
    /*****************Function _get_posted_syllabus_data**********************************
     * @type            : Function
     * @function name   : _get_posted_syllabus_data
     * @description     : Prepare "Syllabus" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_syllabus_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'subject_id';
        $items[] = 'title';
        $items[] = 'note';
        if($_POST['disciplines']){
        $items[] = 'disciplines'; 
        }
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            
            $school = $this->syllabus->get_school_by_id($data['school_id']);
            
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('academic/syllabus/index');
            }
            
            $data['academic_year_id'] = $school->academic_year_id;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
        }
        
        
        if(!empty($_FILES)){  
           $data['syllabus'] = $this->_upload_syllabus();
        }
        
        return $data;
    }

    
    /*****************Function _upload_syllabus**********************************
     * @type            : Function
     * @function name   : _upload_syllabus
     * @description     : Process "Syllabus" file upload to server and 
     *                      return file to store into database                  
     * @param           : null
     * @return          : $return_syllabus string value 
     * ********************************************************** */
    private function _upload_syllabus(){
           
        $prev_syllabus     = $this->input->post('prev_syllabus');
        $syllabus          = $_FILES['syllabus']['name'];
        $syllabus_type     = $_FILES['syllabus']['type'];
		$file_size= $_FILES['syllabus']['size'];
        $return_syllabus   = '';

        if ($syllabus != "") {			
            if ($syllabus_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || 
                    $syllabus_type == 'application/msword' || $syllabus_type == 'text/plain' ||
                    $syllabus_type == 'application/vnd.ms-office' || $syllabus_type == 'application/pdf') {

                $destination = 'assets/uploads/syllabus/';               
				if($file_size <= 5000000){	
					$file_type  = explode(".", $syllabus);
					$extension  = strtolower($file_type[count($file_type) - 1]);
					$syllabus_path = 'syllabus-'.time() . '-gsms.' . $extension;

					move_uploaded_file($_FILES['syllabus']['tmp_name'], $destination . $syllabus_path);

					// need to unlink previous syllabus
					if ($prev_syllabus != "") {
						if (file_exists($destination . $prev_syllabus)) {
							@unlink($destination . $prev_syllabus);
						}
					}

					$return_syllabus = $syllabus_path;
				}
				else{					
					error('File size exceeds 5MB');
					redirect('academic/syllabus/index');
					$return_syllabus = $prev_syllabus;
				}
            }
        } else {
            $return_syllabus = $prev_syllabus;
        }

        return $return_syllabus;
    }

    
    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Syllabus" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/syllabus/index');
        }
        
        $syllabus = $this->syllabus->get_single('syllabuses', array('id' => $id));
        if ($this->syllabus->delete('syllabuses', array('id' => $id))) {   
            
               
            $class = $this->syllabus->get_single('classes', array('id' => $syllabus->class_id, 'school_id'=>$syllabus->school_id));
            create_log('Has been deleted a syllabus : '. $syllabus->title.' for class:'. $class->name);
            
            
            // delete syllabus file
            $destination = 'assets/uploads/';
            if (file_exists( $destination.'/syllabus/'.$syllabus->syllabus)) {
                @unlink($destination.'/syllabus/'.$syllabus->syllabus);
            }
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('academic/syllabus/index/'.$syllabus->class_id);
    }
    
    
    

}
