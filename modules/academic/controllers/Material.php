<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Material.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Material
 * @description     : Manage academic material for each class as per school course curriculam.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Material extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();     
        $this->load->model('Material_Model', 'material', true);  
        $this->load->library('message/message');

    }

    
    
    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Material List" user interface                 
     *                    with class wise listing    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function index($class_id = null) {
        
        check_permission(VIEW);
        
         if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
             redirect('academic/material/index');
        }
        
        // for super admin 
        $school_id = '';
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');           
        }
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $school_id = $this->session->userdata('school_id');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        
        //$this->data['materials'] = $this->material->get_material_list($class_id, $school_id);            
       if(!$school_id)
       {
            error($this->lang->line('select_school'));
       }
      
        $condition = array();
        $condition['status'] = 1;        
        if($school_id){            
            $condition['school_id'] = $school_id;
            $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['classes'] = $this->material->get_class_by_session('classes', $condition['school_id']);
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage') .' ' .$this->lang->line('material'). ' | ' . SMS);
        $this->layout->view('material/index', $this->data);            
       
    }

    
    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Add new Material" user interface                 
     *                    and store "Material" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add() {
        
        check_permission(ADD);
        if ($_POST) {
            $this->_prepare_material_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_material_data();

                $insert_id = $this->material->insert('study_materials', $data);
                if ($insert_id) {
                    
                    $data['id'] = $insert_id;
                    $this->_send_message_notification($data);
                    $class = $this->material->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been added material : '. $data['title'].' for class : '. $class->name);
                    
                    
                    success($this->lang->line('insert_success'));
                    redirect('academic/material/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/material/add/'.$data['class_id']);
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
        $this->data['materials'] = $this->material->get_material_list($class_id);            

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('material'). ' | ' . SMS);
        $this->layout->view('material/index', $this->data);
    }

    
    /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Material" user interface                 
     *                    with populated "Material" value 
     *                    and update "Material" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {       
       
        check_permission(EDIT);
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/material/index'); 
        }
        
        if ($_POST) {
            $this->_prepare_material_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_material_data();
                $updated = $this->material->update('study_materials', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->material->get_single('classes', array('id' => $data['class_id'], 'school_id'=>$data['school_id']));
                    create_log('Has been updated material : '. $data['title'].' for class : '. $class->name);                    
                    
                    success($this->lang->line('update_success'));
                    redirect('academic/material/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('academic/material/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['material'] = $this->material->get_single('study_materials', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['material'] = $this->material->get_single('study_materials', array('id' => $id));

            if (!$this->data['material']) {
               redirect('academic/material/index');
            }
        }
        
        $class_id = $this->data['material']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;
        $this->data['materials'] = $this->material->get_material_list($class_id, $this->data['material']->school_id);            
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['school_id'] = $this->data['material']->school_id;
        $this->data['filter_school_id'] = $this->data['material']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('material'). ' | ' . SMS);
        $this->layout->view('material/index', $this->data);
        
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
         $totalRecords = $this->material->get_material_list_total($class_id, $school_id,null,$search_text);
         
        $materials = $this->material->get_material_list($class_id, $school_id,$start,$limit ,$search_text);
       }
       else
       {
            $totalRecords =0;
            $materials =  array();
       }
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($materials) && !empty($materials)){
               foreach($materials as $obj){
                $guardian_class_data = get_guardian_access_data('class');
                $row_data = array();
                $action = "";
                if($this->session->userdata('role_id') == GUARDIAN){
                    if (!in_array($obj->class_id, $guardian_class_data)) { continue; }
                }elseif($this->session->userdata('role_id') == STUDENT){
                    if ($obj->class_id != $this->session->userdata('class_id')){ continue; }
                }
                if(has_permission(EDIT, 'academic', 'material')){
                    $action .=' <a href="'.site_url('academic/material/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line('edit').' </a>';
                }
                if(has_permission(VIEW, 'academic', 'material')){
                    if($obj->material){
                        $action .=' <a  href="'.UPLOAD_PATH.'material/'.$obj->material.'" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-download"></i> '.$this->lang->line('download').' </a>';
                    }
                    $action .=' <a  onclick="get_material_modal('.$obj->id.');"  data-toggle="modal" data-target=".bs-material-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').' </a>';
                }
                if(has_permission(DELETE, 'academic', 'material')){
                    $action .='  <a href="'.site_url('academic/material/delete/'.$obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> '.$this->lang->line('delete').' </a>';
                }
                    $row_data[] = $count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                   $row_data[] = $obj->title;
                   $row_data[] = $obj->class_name;
                   $row_data[] = $obj->subject;
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
    /*****************Function view**********************************
     * @type            : Function
     * @function name   : view
     * @description     : Load user interface with specific material data                 
     *                       
     * @param           : $material_id integer value
     * @return          : null 
     * ********************************************************** */
    public function view( $material_id = null ){
        check_permission(VIEW);
        
        if(!is_numeric($material_id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/material/index');
        }
        
        $this->data['material'] = $this->material->get_single_material( $material_id);
        $class_id = $this->data['material']->class_id;
        
        $this->data['materials'] = $this->material->get_material_list($class_id);    

         
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }
        $this->data['classes'] = $this->material->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['class_id'] = $class_id;
        
        $this->data['schools'] = $this->schools;
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('material'). ' | ' . SMS);
        $this->layout->view('material/index', $this->data);
    }

        
     /*****************Function get_single_material**********************************
     * @type            : Function
     * @function name   : get_single_material
     * @description     : "Load single material information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_material(){
        
       $material_id = $this->input->post('material_id');
       
       $this->data['material'] = $this->material->get_single_material( $material_id);
       echo $this->load->view('material/get-single-material', $this->data);
    }
    
    /*****************Function _prepare_material_validation**********************************
     * @type            : Function
     * @function name   : _prepare_material_validation
     * @description     : Process "material" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_material_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required');   
        $this->form_validation->set_rules('title', $this->lang->line('material') . ' ' . $this->lang->line('title'), 'trim|required');   
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim');   
        $this->form_validation->set_rules('material', $this->lang->line('material'), 'trim|callback_material');   
    }
    
    
    /*****************Function material**********************************
     * @type            : Function
     * @function name   : material
     * @description     : Unique check for "material file content" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */  
   public function material()
   {  
       
    if($this->input->post('id')){
        
        if(!empty($_FILES) &&  $_FILES['material']['name']){
            $name = $_FILES['material']['name'];
            $arr = explode('.', $name);
            $ext = end($arr); 
            if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('material', $this->lang->line('select_valid_file_format'));         
                return FALSE; 
            }
        }
        return true;
        
    }else{
       
        if($_FILES['material']['name'])
        { 
           $name = $_FILES['material']['name'];
           $arr = explode('.', $name);
           $ext = end($arr); 
           if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
               return TRUE;
           } else {
               $this->form_validation->set_message('material', $this->lang->line('select_valid_file_format'));         
               return FALSE; 
           }         
        }
    }
       
   }

   
    /*****************Function _get_posted_material_data**********************************
     * @type            : Function
     * @function name   : _get_posted_material_data
     * @description     : Prepare "Material" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_material_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'subject_id';
        $items[] = 'title';
        $items[] = 'description';
        
        $data = elements($items, $_POST);    
        $school_id = $this->session->userdata('school_id');
        $schhoolData = $this->material->get_school($school_id);          
        $data['academic_year_id'] = $schhoolData->academic_year_id;
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
        }
        
        
        if (isset($_FILES['material']['name'])) {
           $data['material'] = $this->_upload_material();
        }
        
        return $data;
    }

    
    /*****************Function _upload_material**********************************
     * @type            : Function
     * @function name   : _upload_material
     * @description     : Process "Material" file upload to server and 
     *                      return file to store into database                  
     * @param           : null
     * @return          : $return_material string value 
     * ********************************************************** */
    private function _upload_material(){
           
        $prev_material     = $this->input->post('prev_material');
        $material          = $_FILES['material']['name'];
        $material_type     = $_FILES['material']['type'];
        $return_material   = '';

        if ($material != "") {

                $destination = 'assets/uploads/material/';               

                $file_type  = explode(".", $material);
                $extension  = strtolower($file_type[count($file_type) - 1]);
                $material_path = 'material-'.time() . '-gsms.' . $extension;

                move_uploaded_file($_FILES['material']['tmp_name'], $destination . $material_path);

                // need to unlink previous material
                if ($prev_material != "") {
                    if (file_exists($destination . $prev_material)) {
                        @unlink($destination . $prev_material);
                    }
                }

                $return_material = $material_path;
            
        } else {
            $return_material = $prev_material;
        }

        return $return_material;
    }

    
    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Material" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('academic/material/index');
        }
        
        $material = $this->material->get_single('study_materials', array('id' => $id));
        if ($this->material->delete('study_materials', array('id' => $id))) {   
            
               
            $class = $this->material->get_single('classes', array('id' => $material->class_id, 'school_id'=>$material->school_id));
            create_log('Has been deleted a material : '. $material->title.' for class:'. $class->name);
            
            
            // delete material file
            $destination = 'assets/uploads/';
            if (file_exists( $destination.'/material/'.$material->material)) {
                @unlink($destination.'/material/'.$material->material);
            }
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('academic/material/index/'.$material->class_id);
    }
    
     /*****************Function _send_message_notification**********************************
    * @type            : Function
    * @function name   : _send_sms_notification
    * @description     : Process to send SMS to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send_message_notification($material_data = null) {
 
        $data                       = array("");
        $data['school_id']          = $material_data['school_id'];

        $school = $this->material->get_school_by_id($material_data['school_id']);     
        $students                   = $this->material->get_student_list($material_data['school_id'], $material_data['class_id'], null,@$school->academic_year_id );
        $material                    = $this->material->get_single_material($material_data['id']);  
        $data['academic_year_id']   = @$school->academic_year_id;

        $data['subject']            = "New ".$material->subject." ".$this->lang->line('study_material');   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
        
        foreach ($students as $obj) {

            // student message
            if($obj->user_id != ''){                    
                $message = $this->lang->line('hi'). ' '. $obj->name.',';
                $message .= '<br/>';
                $message .= $this->lang->line('study_material_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('class').': ' . $material->class_name;
                $message .= '<br/>';
            
                $message .= $this->lang->line('subject'). ': ' . $material->subject;
                $message .= '<br/>';
                if($material->description)
                {
                    $message .= $this->lang->line('note'). ': ' . $material->description;
                    $message .= '<br/>';
                }
                $message .= '<br/><br/>';

                $message .= $this->lang->line('thank_you').'<br/>';
                $data['body'] = $message;
                $data['receiver_id'] = $obj->user_id;
                $data['receiver_role_id'] = STUDENT;
                $this->message->send_message($data);
            }
            // guardian phone
            if($obj->g_user_id != ''){ 
                $message = $this->lang->line('hi'). ' '. $obj->name.',';
                $message .= '<br/>';
                $message .= $this->lang->line('study_material_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('class').': ' . $material->class_name;
                $message .= '<br/>';
            
                $message .= $this->lang->line('subject'). ': ' . $material->subject;
                $message .= '<br/>';
                if($lecture->note)
                {
                    $message .= $this->lang->line('note'). ': ' . $lecture->description;
                    $message .= '<br/>';
                }
                $message .= '<br/><br/>';

                $message .= $this->lang->line('thank_you').'<br/>';
                $data['body'] = $message;
                $data['receiver_id'] = $obj->g_user_id;
                $data['receiver_role_id'] = GUARDIAN;
                $this->message->send_message($data);
            }

        }
    }  
    

}
