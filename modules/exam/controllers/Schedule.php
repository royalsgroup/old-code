<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Schedule.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Schedule
 * @description     : Manage exam time schedule.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Schedule extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Schedule_Model', 'schedule', true);
        $this->load->library('message/message');
        error_on();

    }

    
        
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Exam Schdule List" user interface                 
    *                       
    * @param           : $class_id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($class_id = null) {

        check_permission(VIEW);

        $school_id = '';        
        if ($this->session->userdata('role_id') == STUDENT) {
            $class_id = $this->session->userdata('class_id');    
        }
        
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $school_id = $this->session->userdata('school_id');    
        }
        
        // for super admin      
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');           
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;       
        
        $school = $this->schedule->get_school_by_id($school_id);                
        $this->data['schedules'] = $this->schedule->get_schedule_list($class_id, $school_id, @$school->academic_year_id);
       
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->schedule->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['class_list'] = $this->schedule->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
        if($school){
            $condition['academic_year_id'] = @$school->academic_year_id;  
            $this->data['exams'] = $this->schedule->get_list('exams', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools; 
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('exam') . ' ' . $this->lang->line('schedule') . ' | ' . SMS);
        $this->layout->view('schedule/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Exam Schedule" user interface                 
    *                    and process to store "Exam Schedule" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add($id = 0) {

        check_permission(ADD);
        if ($_POST) {
            $this->_prepare_schedule_validation();

            if ($this->form_validation->run() === TRUE) {

                $data = $this->_get_posted_schedule_data();

                $insert_id = $this->schedule->insert('exam_schedules', $data);
                if ($insert_id) {
                    $data['id'] = $insert_id;

                    $this->_send_message_notification($data); 

                     $class = $this->schedule->get_single('classes', array('id'=>$data['class_id']));
                     create_log('Has been created an exam schedule for class : '.$class->name);
                    success($this->lang->line('insert_success'));
                    redirect('exam/schedule/index/' . $data['class_id']);
                } else {

                    error($this->lang->line('insert_failed'));
                    // redirect('exam/schedule/add/' . $data['class_id']);
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }

        if ($id) {
            $this->data['schedule'] = $this->schedule->get_single('exam_schedules', array('id' => $id));
            $this->data['copy'] = TRUE;
        }
        else
        {
            $this->data['add'] = TRUE;
        }
        $this->data['schedules'] = $this->schedule->get_schedule_list($class_id);
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            
            $condition['school_id'] = $this->session->userdata('school_id');
            $school = $this->schedule->get_school_by_id($condition['school_id']); 
            
            $this->data['classes'] = $this->schedule->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
            // $condition['academic_year_id'] = $school->academic_year_id;   
            $this->data['exams'] = $this->schedule->get_list('exams', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['class_list'] = $this->schedule->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['class_id'] = $class_id;
        $this->data['schools'] = $this->schools; 
        
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('schedule') . ' | ' . SMS);
        $this->layout->view('schedule/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Exam Schedule" user interface                 
    *                    with populate "Exam Schedule" value 
    *                    and process to update "Exa Schedule" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
           redirect('exam/schedule/index');  
        }
        
        if ($_POST) {
            $this->_prepare_schedule_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_schedule_data();
                $updated = $this->schedule->update('exam_schedules', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                     $class = $this->schedule->get_single('classes', array('id'=>$data['class_id']));
                    create_log('Has been updated an exam schedule for class : '.$class->name);
                    
                    success($this->lang->line('update_success'));
                    redirect('exam/schedule/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('exam/schedule/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['post'] = $_POST;
                $this->data['schedule'] = $this->schedule->get_single('exam_schedules', array('id' => $this->input->post('id')));
            }
        }
        $check_mark = 0;
        if ($id) {
            $this->data['schedule'] = $this->schedule->get_single('exam_schedules', array('id' => $id));
            $acheck_mark = $this->schedule->check_mark($this->data['schedule']);
            // echo $this->db->last_query();
            // die();
            // echo '<pre>'; var_dump($acheck_mark); die(); 
            $check_mark = !empty( $acheck_mark->total_mark) &&  $acheck_mark->total_mark ? 1 : 0;
            if (!$this->data['schedule']) {
                redirect('exam/schedule/index');
            }
        }        
        $class_id = $this->data['schedule']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
        $this->data['check_mark'] = $check_mark;
        $this->data['schedules'] = $this->schedule->get_schedule_list($class_id);
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->schedule->get_list('classes', $condition, '','', '', 'id', 'ASC');
            
            $school = $this->schedule->get_school_by_id($condition['school_id']); 
            
            $condition['academic_year_id'] = $school->academic_year_id;   
            $this->data['exams'] = $this->schedule->get_list('exams', $condition, '', '', '', 'id', 'ASC');
        }
        unset($condition['academic_year_id']);
        $this->data['class_list'] = $this->schedule->get_list('classes', $condition, '','', '', 'id', 'ASC');
       
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $this->data['schedule']->school_id;
        $this->data['school_id'] = $this->data['schedule']->school_id;
        
        $this->data['schools'] = $this->schools; 
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('schedule') . ' | ' . SMS);
        $this->layout->view('schedule/index', $this->data);
    }

    
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific exam scedule data                 
    *                       
    * @param           : $schedule_id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($schedule_id = null) {

        check_permission(VIEW);

        if(!is_numeric($schedule_id)){
             error($this->lang->line('unexpected_error'));
             redirect('exam/schedule/index');  
        }
     
        
        $this->data['schedule'] = $this->schedule->get_single_schedule($schedule_id);       
        $class_id = $this->data['schedule']->class_id;
        
        $this->data['schedules'] = $this->schedule->get_schedule_list($class_id);
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->schedule->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['class_list'] = $this->schedule->get_list('classes', $condition, '','', '', 'id', 'ASC');
            
            $school = $this->schedule->get_school_by_id($condition['school_id']); 
            
            $condition['academic_year_id'] = $school->academic_year_id;   
            $this->data['exams'] = $this->schedule->get_list('exams', $condition, '', '', '', 'id', 'ASC');
        }
        
        $this->data['class_id'] = $class_id;
        
        $this->data['schools'] = $this->schools; 
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('schedule') . ' | ' . SMS);
        $this->layout->view('schedule/index', $this->data);        
    }

        
               
     /*****************Function get_single_schedule**********************************
     * @type            : Function
     * @function name   : get_single_schedule
     * @description     : "Load single schedule information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_schedule(){
        
       $schedule_id = $this->input->post('schedule_id');
       
      $this->data['schedule'] = $this->schedule->get_single_schedule($schedule_id);   
       echo $this->load->view('schedule/get-single-schedule', $this->data);
    }
    
    /*****************Function _prepare_schedule_validation**********************************
    * @type            : Function
    * @function name   : _prepare_schedule_validation
    * @description     : Process "Exam Schedule" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_schedule_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|callback_subject_id');
        $this->form_validation->set_rules('exam_date', $this->lang->line('exam_date'), 'trim|required');
        $this->form_validation->set_rules('start_time', $this->lang->line('start_time'), 'trim|required');
        $this->form_validation->set_rules('end_time', $this->lang->line('end_time'), 'trim|required');
        $this->form_validation->set_rules('room_no', $this->lang->line('room_no'), 'trim|callback_room_no');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }

    
    /*****************Function subject_id**********************************
    * @type            : Function
    * @function name   : subject_id
    * @description     : Unique check for "subject id" in exam schedule data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function subject_id() {

        $school_id = $this->input->post('school_id');
        $exam_id = $this->input->post('exam_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        if(!$section_id) $section_id = 0;
        $subject_id = $this->input->post('subject_id');
        
         $school = $this->schedule->get_school_by_id($school_id); 

        if ($this->input->post('id') == '') {
            $schedule = $this->schedule->duplicate_check($school_id, $school->academic_year_id, $exam_id, $class_id, $subject_id, $section_id);
            if ($schedule) {
                $this->form_validation->set_message('subject_id', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $schedule = $this->schedule->duplicate_check($school_id, $school->academic_year_id, $exam_id, $class_id, $subject_id, $section_id, $this->input->post('id'));
            if ($schedule) {
                $this->form_validation->set_message('subject_id', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    
    /*****************Function room_no**********************************
    * @type            : Function
    * @function name   : room_no
    * @description     : Unique check for "room_no" in exam schedule data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function room_no() {

        $school_id = $this->input->post('school_id');
        $room_no = $this->input->post('room_no');
		if($room_no != ''){
        $exam_date = date('Y-m-d', strtotime($this->input->post('exam_date')));
        $start_time = $this->input->post('start_time');
        $school = $this->schedule->get_school_by_id($school_id); 
         
        if ($this->input->post('id') == '') {
            $schedule = $this->schedule->duplicate_room_check($school_id, $school->academic_year_id, $room_no, $exam_date, $start_time);
            if ($schedule) {
                $this->form_validation->set_message('room_no', $this->lang->line('this_room_already_allocated'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $schedule = $this->schedule->duplicate_room_check($school_id, $school->academic_year_id, $room_no, $exam_date, $start_time, $this->input->post('id'));
            if ($schedule) {
                $this->form_validation->set_message('subject_id', $this->lang->line('this_room_already_allocated'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
		}
		else{
			return true;
		}
    }

    
    /*****************Function _get_posted_schedule_data**********************************
    * @type            : Function
    * @function name   : _get_posted_schedule_data
    * @description     : Prepare "Exam Schedule" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_schedule_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'exam_id';
        $items[] = 'class_id';
        $items[] = 'section_id';
        $items[] = 'subject_id';
        $items[] = 'start_time';
        $items[] = 'end_time';
        $items[] = 'optional';
        $items[] = 'room_no';
        $items[] = 'note';
        $items[] = 'max_mark';
        $items[] = 'max_mark_p';
        $items[] = 'max_mark_v';
        $data = elements($items, $_POST);
        if (!$data['section_id']) $data['section_id'] = 0;
        $data['exam_date'] = date('Y-m-d', strtotime($this->input->post('exam_date')));
        $data['optional'] = $data['optional'] ? $data['optional']  : 0;

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $school = $this->schedule->get_school_by_id($data['school_id']);
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('exam/schedule/index');   
            }
            $data['academic_year_id'] = $school->academic_year_id;
            $data['custom_id'] =$this->schedule->generate_custom_id($data['school_id']);
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }

        return $data;
    }

      /*****************Function _send_message_notification**********************************
    * @type            : Function
    * @function name   : _send_message_notification
    * @description     : Process to send in app messages to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send_message_notification($schedule_data = null) {
 
        $data                       = array("");
        $data['school_id']          = $schedule_data['school_id'];

        $students                   = $this->schedule->get_student_list($schedule_data['school_id'], $schedule_data['class_id'], null, $schedule_data['academic_year_id'] );
        $schedule                    = $this->schedule->get_single_schedule($schedule_data['id']);  
        $data['academic_year_id']   = $schedule_data['academic_year_id'];

        $data['subject']            = $this->lang->line('exam_scheduled')." for ".$schedule->subject;   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
        
        foreach ($students as $obj) {

            // student message
            if($obj->user_id != ''){                    
                $message = $this->lang->line('hi'). ' '. $obj->name.',';
                $message .= '<br/>';
                $message .= $this->lang->line('following_is_your_exam_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('exam').': ' . $schedule->title;
                $message .= '<br/>';
                $message .= $this->lang->line('subject'). ': ' . $schedule->subject;
                $message .= '<br/>';
                $message .= $this->lang->line('exam_date').': ' . $schedule->exam_date;
                $message .= '<br/>';
                $message .= $this->lang->line('start_time').': ' . $schedule->start_time;
                $message .= ' ';
                $message .= $this->lang->line('end_time').': ' . $schedule->end_time;
                $message .= '<br/>';
                $message .= $this->lang->line('class').': ' . $schedule->class_name;
                $message .= '<br/>';
                
                $message .= $this->lang->line('room_no'). ': ' . $schedule->room_no;
                $message .= '<br/>';
                if($schedule->note)
                {
                    $message .= $this->lang->line('note'). ': ' . $schedule->note;
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
                $message .= $this->lang->line('following_is_your_child_exam_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('exam').': ' . $schedule->title;
                $message .= '<br/>';
                $message .= $this->lang->line('subject'). ': ' . $schedule->subject;
                $message .= '<br/>';
                $message .= $this->lang->line('exam_date').': ' . $schedule->exam_date;
                $message .= '<br/>';
                $message .= $this->lang->line('start_time').': ' . $schedule->start_time;
                $message .= ' ';
                $message .= $this->lang->line('end_time').': ' . $schedule->end_time;
                $message .= '<br/>';
                $message .= $this->lang->line('class').': ' . $schedule->class_name;
                $message .= '<br/>';
                
                $message .= $this->lang->line('room_no'). ': ' . $schedule->room_no;
                $message .= '<br/>';
                if($schedule->note)
                {
                    $message .= $this->lang->line('note'). ': ' . $schedule->note;
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
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Exam Schedule" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('exam/schedule/index');    
        }
        
        $exam_schedule = $this->schedule->get_single('exam_schedules', array('id' => $id));
        $condition = array(
            'school_id' => $exam_schedule->school_id
            , 'class_id'=>$exam_schedule->class_id
            , 'exam_id'=>$exam_schedule->exam_id
            , 'subject_id'=>$exam_schedule->subject_id
            , 'academic_year_id'=>$exam_schedule->academic_year_id
        );
        
        if ($exam_schedule->section_id)
            $condition['section_id'] = $exam_schedule->section_id;

       
        if ($this->schedule->delete('exam_schedules', array('id' => $id))) {
            $this->schedule->delete('marks', $condition);
            $class = $this->schedule->get_single('classes', array('id' => $exam_schedule->class_id));
            create_log('Has been deleted an exam schedule for class : '.$class->name);
            
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('exam/schedule/index/'.$exam_schedule->class_id);
    }

}
