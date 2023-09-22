<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Lecture.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Lecture
 * @description     : Manage Lecture by class teacher.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Lecture extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Lecture_Model', 'lecture', true);        
        $this->load->library('message/message');
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Lecture List" user interface                 
    *                    with class wise listing    
    * @param           : $class_id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($class_id = null) {

        check_permission(VIEW);
                
        // for super admin 
        $school_id = '';       
        $section_id = '';
        $condition = array();
        $condition['status'] = 1; 
        
        if ($this->session->userdata('role_id') == STUDENT) {
            
            $school_id = $this->session->userdata('school_id');    
            $class_id = $this->session->userdata('class_id');    
            $section_id = $this->session->userdata('section_id');  
            
        }else if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){  
            
			$school_id=$this->session->userdata('school_id'); 
            $condition['school_id'] =  $this->session->userdata('school_id');  
            $condition['alumni'] =0 ;
            $this->data['teachers'] = $this->lecture->get_list('teachers', $condition, '','', '', 'id', 'ASC');
            unset( $condition['alumni']);
            $condition['school_id'] = $school_id; 
			$this->data['faculty'] = $this->lecture->get_list_new('academic_disciplines', array('school_id'=>$school_id), '','', '', 'id', 'ASC');			
        }
        
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');
        }
        
        
        $this->data['classes'] = $this->lecture->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
		$this->data['class_list'] =$this->data['classes'];
       // $this->data['class_list'] = $this->lecture->get_list('classes', $condition, '','', '', 'id', 'ASC');

       // $this->data['classes'] = $this->lecture->get_class_by_session('classes', $condition['school_id']);
        //$this->data['class_list'] = $this->lecture->get_class_by_session('classes', $condition['school_id']);


                        
        $school = $this->lecture->get_school_by_id($school_id);         
        $this->data['lectures'] = $this->lecture->get_lecture_list($school_id, $school->academic_year_id, $class_id, $section_id );
                
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;

      
        $this->layout->title($this->lang->line('manage_class_lecture') . ' | ' . SMS);
        $this->layout->view('lecture/index', $this->data);
    }
	public function get_list(){		
		 // for super admin 
        $school_id = '';
		$start=null;
		$limit=null;
		$search_text='';
		
        $section_id = '';
       
        if ($this->session->userdata('role_id') == STUDENT) {
            
            $school_id = $this->session->userdata('school_id');    
            $class_id = $this->session->userdata('class_id');    
            $section_id = $this->session->userdata('section_id');  
            
        }else if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){  
            
			$school_id=$this->session->userdata('school_id'); 
            $condition['school_id'] = $school_id; 		
        }
        
         if($_POST){            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');  
			$start = $this->input->post('start');
            $limit  = $this->input->post('length');   
			$draw = $this->input->post('draw');	
			if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
				$search_text=$_POST['search']['value'];
			}
        }	                    

                        
        $school = $this->lecture->get_school_by_id($school_id);         	              
                
                    
        $data = array();
		$totalRecords=0;
        if($school_id){		   		  
		  $totalRecords = $this->lecture->get_lecture_list_total($school_id, $school->academic_year_id, $class_id, $section_id,$search_text );
		  $lectures = $this->lecture->get_lecture_list($school_id, $school->academic_year_id, $class_id, $section_id, $start,$limit,$search_text );           
		$count = 1; 
		

		if(isset($lectures) && !empty($lectures)){			
				foreach($lectures as $obj){
					$action='';
					 if(has_permission(EDIT, 'teacher', 'lecture')){ 
                             $action .='<a href="'.site_url('teacher/lecture/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line('edit').' </a>';
                                                 } 
                                                if(has_permission(VIEW, 'teacher', 'lecture')){ 
                                                    $action .= '<a  onclick="get_lecture_modal('.$obj->id.');"  data-toggle="modal" data-target=".bs-lecture-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').' </a>';
                                                     if($obj->lecture_ppt){ 
                                                        $action .= '<a target="_blank" href="'.UPLOAD_PATH.'video-lecture/'. $obj->lecture_ppt.'" class="btn btn-success btn-xs"><i class="fa fa-download"></i> '. $this->lang->line('download').'</a>';
                                                     } 
                                                 } 
                                                if(has_permission(DELETE, 'teacher', 'lecture')){ 
                                                 $action .=   '<a href="'.site_url('teacher/lecture/delete/'.$obj->id).'" onclick="javascript: return confirm('.$this->lang->line('confirm_alert').');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> '.$this->lang->line('delete').'</a>';
                                                  } 
					 
  if($obj->lecture_type == 'youtube'){ 
            $video_id = get_youtube_id_from_url($obj->video_id);
             $video = '<img src="https://img.youtube.com/vi/'.$video_id.'/mqdefault.jpg" width="130"/>';
                              }else if($obj->lecture_type == 'vimeo'){
                                                                                                  
                                $video_id = get_vimeo_id_from_url($obj->video_id);
                                                    $vimeo = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));
                                               
               $video = '<img src="'.$vimeo[0]['thumbnail_small'].'" width="130"/>';
                                                    
                                                }else if($obj->lecture_type == 'ppt'){ 
$video = '<img src="'. IMG_URL.'/ppt-default-image.jpg" width="130"/>';
                                                } 
					 $data[] = array( 
					 
						  0=>$count,
						  1=>$obj->school_name,
						  2=>$obj->lecture_title,
						  3=>$obj->class_name,						  
						  4=>$obj->section,
						  5=>$obj->subject,
						  6=>$obj->teacher,
						  7=>$video,
						  8=>$obj->session_year,						 
						  9=>$action
					   );
					   $count++;
				}
			
		}			
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

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Asignment" user interface                 
    *                    and process to store "Lecture" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_lecture_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_lecture_data();

                $insert_id = $this->lecture->insert('video_lectures', $data);
               
                if ($insert_id) {
                    $data['id'] = $insert_id;
                    $this->_send_message_notification($data); 
                    create_log('Has been uploaded an lecture : '.$data['lecture_title']);                     
                    success($this->lang->line('insert_success'));
                    redirect('teacher/lecture/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('teacher/lecture/add');
                }
            } else {
              
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }
               
        
        // for super admin 
        $school_id = '';
        $class_id = '';
        $section_id = '';
        $condition = array();
        $condition['status'] = 1; 
        
        if ($this->session->userdata('role_id') == STUDENT) {
            
            $school_id = $this->session->userdata('school_id');    
            $class_id = $this->session->userdata('class_id');    
            $section_id = $this->session->userdata('section_id');  
            
        }else if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){  
            
            $condition['school_id'] = $this->session->userdata('school_id');            
        }        
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');
            $section_id  = $this->input->post('section_id');
        }
        
        $this->data['classes'] = $this->lecture->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['class_list'] = $this->lecture->get_list('classes', $condition, '','', '', 'id', 'ASC');
                        
        $school = $this->lecture->get_school_by_id($school_id);         
        $this->data['lectures'] = $this->lecture->get_lecture_list($school_id, @$school->academic_year_id, $class_id, $section_id );
         
                
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('lecture/index', $this->data);
    }
    /*****************Function _send_message_notification**********************************
    * @type            : Function
    * @function name   : _send_message_notification
    * @description     : Process to send in app message to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send_message_notification($lecture_data = null) {
 
        $data                       = array("");
        $data['school_id']          = $lecture_data['school_id'];

        $students                   = $this->lecture->get_student_list($lecture_data['school_id'], $lecture_data['class_id'], $lecture_data['section_id'], $lecture_data['academic_year_id'] );
        $lecture                    = $this->lecture->get_single_lecture($lecture_data['id']);  
        $data['academic_year_id']   = $lecture_data['academic_year_id'];

        $data['subject']            = $lecture->subject." ".$this->lang->line('class_lecture');   
        $data['sender_id']          = $lecture->t_user_id;
        $data['sender_role_id']     = TEACHER;
        
        foreach ($students as $obj) {

            // student message
            if($obj->user_id != ''){                    
                $message = $this->lang->line('hi'). ' '. $obj->name.',';
                $message .= '<br/>';
                $message .= $this->lang->line('following_is_your_lecture_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('class').': ' . $lecture->class_name;
                $message .= '<br/>';
                $message .= $this->lang->line('section'). ': ' . $lecture->section;
                $message .= '<br/>';
                $message .= $this->lang->line('subject'). ': ' . $lecture->subject;
                $message .= '<br/>';
                $message .= $this->lang->line('teacher'). ': ' . $lecture->teacher;
                $message .= '<br/>';
                if($lecture->note)
                {
                    $message .= $this->lang->line('note'). ': ' . $lecture->note;
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
                $message .= $this->lang->line('following_is_your_child_live_class_schedule');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('class').': ' . $lecture->class_name;
                $message .= '<br/>';
                $message .= $this->lang->line('section'). ': ' . $lecture->section;
                $message .= '<br/>';
                $message .= $this->lang->line('subject'). ': ' . $lecture->subject;
                $message .= '<br/>';
                $message .= $this->lang->line('teacher'). ': ' . $lecture->teacher;
                $message .= '<br/>';
                if($lecture->note)
                {
                    $message .= $this->lang->line('note'). ': ' . $lecture->note;
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
    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Lecture" user interface                 
    *                    with populated "Lecture" value 
    *                    and process to update "Lecture" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('teacher/lecture/index');
        }
        
        if ($_POST) {
            $this->_prepare_lecture_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_lecture_data();
                $updated = $this->lecture->update('video_lectures', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a video lecture : '.$data['lecture_title']);                    
                    success($this->lang->line('update_success'));
                    redirect('teacher/lecture/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('teacher/lecture/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['lecture'] = $this->lecture->get_single('video_lectures', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['lecture'] = $this->lecture->get_single('video_lectures', array('id' => $id));

            if (!$this->data['lecture']) {
                redirect('teacher/lecture/index');
            }
        }
         
        // for super admin 
        $school_id = '';
        $class_id = '';
        $section_id = '';
        $condition = array();
        $condition['status'] = 1; 
        
        if ($this->session->userdata('role_id') == STUDENT) {
            
            $school_id = $this->session->userdata('school_id');    
            $class_id = $this->session->userdata('class_id');    
            $section_id = $this->session->userdata('section_id');  
            
        }else if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){  
            
            $condition['school_id'] = $this->session->userdata('school_id');            
        }        
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');
            $section_id  = $this->input->post('section_id');
        }
        
        $class_id = $class_id  ? $class_id : $this->data['lecture']->class_id;
        
        $this->data['classes'] = $this->lecture->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['class_list'] = $this->lecture->get_list('classes', $condition, '','', '', 'id', 'ASC');
                        
        $school = $this->lecture->get_school_by_id($school_id);         
        $this->data['lectures'] = $this->lecture->get_lecture_list($school_id, @$school->academic_year_id, $class_id, $section_id );
       
                
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
         $this->data['school_id'] = $this->data['lecture']->school_id;        
        $this->data['filter_school_id'] = $this->data['lecture']->school_id;  
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('lecture/index', $this->data);
    }

           
     /*****************Function get_single_lecture**********************************
     * @type            : Function
     * @function name   : get_single_lecture
     * @description     : "Load single lecture information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_lecture(){
        
       $lecture_id = $this->input->post('lecture_id');
       
       $this->data['lecture'] = $this->lecture->get_single_lecture($lecture_id);
       echo $this->load->view('lecture/get-single-lecture', $this->data);
       
    }

    
    /*****************Function _prepare_lecture_validation**********************************
    * @type            : Function
    * @function name   : _prepare_lecture_validation
    * @description     : Process "lecture" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_lecture_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');
        $this->form_validation->set_rules('lecture_title', $this->lang->line('lecture_title'), 'trim|required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim');
        $this->form_validation->set_rules('lecture_type', $this->lang->line('lecture_type'), 'trim|required');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
        $this->form_validation->set_rules('lecture_ppt', $this->lang->line('lecture_ppt'), 'trim|callback_lecture_ppt');
    }

    
    
    /*****************Function lecture_ppt**********************************
    * @type            : Function
    * @function name   : lecture_ppt
    * @description     : Process/check lecture_ppt document validation                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function lecture_ppt() {

        if ($_FILES['lecture_ppt']['name']) {                

            $name = $_FILES['lecture_ppt']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'ppt' || $ext == 'pptx') {
                return TRUE;
            } else {
                $this->form_validation->set_message('lecture_ppt', $this->lang->line('valid_file_format_lecture'));
                return FALSE;
            }
        }        
    }

    
    /*****************Function _get_posted_lecture_data**********************************
    * @type            : Function
    * @function name   : _get_posted_lecture_data
    * @description     : Prepare "Lecture" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_lecture_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'class_id';
        $items[] = 'section_id';
        $items[] = 'subject_id';
        $items[] = 'lecture_type';
        $items[] = 'teacher_id';
        $items[] = 'lecture_title';
        $items[] = 'video_id';
        $items[] = 'note';
          if($_POST['disciplines']){
        $items[] = 'disciplines'; 
        }

        $data = elements($items, $_POST);


        if($data['subject_id'] && !$data['teacher_id'])
        {
            $subject = $this->lecture->get_single('subjects', array('id' => $data['subject_id']));
        
            $data['teacher_id'] = $subject->teacher_id;
            
        }
        
        if(!$data['teacher_id'])
        {
            $data['teacher_id'] = 0;
        }
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            
            // need to remove old ppt file if post video id
            if($this->input->post('video_id') != '' && $this->input->post('prev_lecture_ppt') != ''){
                $destination = 'assets/uploads/video-lecture/';
                $prev_lecture_ppt = $this->input->post('prev_lecture_ppt');
                if (file_exists($destination . $prev_lecture_ppt)) {
                    @unlink($destination . $prev_lecture_ppt);
                }                
                $data['lecture_ppt'] = '';
            }
            
            
        } else {
            
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            
            $school = $this->lecture->get_school_by_id($data['school_id']);
            
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('teacher/lecture/index');
            }
            
            $data['academic_year_id'] = $school->academic_year_id;
            
        }


        if ($_FILES['lecture_ppt']['name']) {
             $data['video_id'] = '';
            $data['lecture_ppt'] = $this->_upload_lecture_ppt();
        }

        return $data;
    }

    
    
    /*****************Function _upload_lecture_ppt**********************************
    * @type            : Function
    * @function name   : _upload_lecture_ppt
    * @description     : Process upload lecture ppt document into server                  
    *                    and return document name   
    * @return          : null 
    * ********************************************************** */
    private function _upload_lecture_ppt() {

        $prev_lecture_ppt = $this->input->post('prev_lecture_ppt');
        $lecture_ppt = $_FILES['lecture_ppt']['name'];
        $lecture_ppt_type = $_FILES['lecture_ppt']['type'];
        $return_lecture_ppt = '';

        if ($lecture_ppt != "") {
            if ($lecture_ppt_type == 'application/powerpoint' ||
                    $lecture_ppt_type == 'application/vnd.ms-powerpoint' ||
                    $lecture_ppt_type == 'application/msword' ||
                    $lecture_ppt_type == 'application/vnd.ms-office' ||
                    $lecture_ppt_type == 'application/mspowerpoint' ||
                    $lecture_ppt_type == 'application/x-mspowerpoint' ||
                    $lecture_ppt_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                  
                    ) {

                $destination = 'assets/uploads/video-lecture/';

                $lecture_ppt_type = explode(".", $lecture_ppt);
                $extension = strtolower($lecture_ppt_type[count($lecture_ppt_type) - 1]);
                $lecture_ppt_path = 'lecture-ppt-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['lecture_ppt']['tmp_name'], $destination . $lecture_ppt_path);

                // need to unlink previous assignment
                if ($prev_lecture_ppt != "") {
                    if (file_exists($destination . $prev_lecture_ppt)) {
                        @unlink($destination . $prev_lecture_ppt);
                    }
                }

                $return_lecture_ppt = $lecture_ppt_path;
            }
        } else {
            $return_lecture_ppt = $prev_lecture_ppt;
        }

        return $return_lecture_ppt;
    }

    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Lecture" from database                  
    *                    and unlink assignment document from server   
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('teacher/lecture/index');
        }
        
        $lecture = $this->lecture->get_single('video_lectures', array('id' => $id));
        
        if ($this->lecture->delete('video_lectures', array('id' => $id))) {

            // delete assignment assignment
            $destination = 'assets/uploads/video-lecture/';
            if (file_exists($destination . '/' . $lecture->lecture_ppt)) {
                @unlink($destination . '/' . $lecture->lecture_ppt);
            }
            
            create_log('Has been deleted an lecture : '.$lecture->lecture_title);

            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('teacher/lecture/index');
    }
    
    
    
    
    public function get_teacher_by_subject() {
        
        $school_id  = $this->input->post('school_id');
        $class_id  = $this->input->post('class_id');
        $teacher_id  = $this->input->post('teacher_id');
                  
        $teachers = $this->lecture->get_teacher_by_subject($school_id, $class_id); 
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        
        
        $select = 'selected="selected"';
        if (!empty($teachers)) {
            foreach ($teachers as $obj) {   
                
                $selected = $teacher_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .' [ '. $obj->responsibility . ' ]</option>';
                
            }
        }

        echo $str;
    }   

}