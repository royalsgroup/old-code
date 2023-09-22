<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Teacher.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Teacher
 * @description     : Manage teacher daily attendance.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Teacher extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Teacher_Model', 'teacher', true);
        $this->load->library('message/message');
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Teacher Attendance" user interface                 
    *                    and Process to manage daily Teacher attendance    
    * @param           : null
    * @return          : null 
    * ********************************************************** */ 
    public function index() {

        check_permission(VIEW);

        if ($_POST) {

            $date = $this->input->post('date');
            $month = date('m', strtotime($this->input->post('date')));
            $year = date('Y', strtotime($this->input->post('date')));
            $school_id = $this->input->post('school_id');
            
            $school = $this->teacher->get_school_by_id($school_id);
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('attendance/teacher/index');
            }
            $this->data['holidays'] = $this->process_holiday_dates($this->teacher->get_holiday_list($school_id)); 
            if($date && in_array(date( "d-m-Y",strtotime($date)),$this->data['holidays']))
            {
                error($this->lang->line('selected_date_is_holiday'));
                redirect('attendance/teacher/index');
            }
            $academic_year_id = $school->academic_year_id;            
            
            // $this->data['teachers'] = $this->teacher->get_teacher_list($school_id);

            // $condition = array(
            //     'school_id' => $school_id,
            //     'month' => $month,
            //     'year' => $year
            // );

            // $data = $condition;
            // if (!empty($this->data['teachers'])) {

            //     foreach ($this->data['teachers'] as $obj) {

            //         $condition['teacher_id'] = $obj->id;

            //         $attendance = $this->teacher->get_single('teacher_attendances', $condition);

            //         if (empty($attendance)) {                       
            //             $data['academic_year_id'] = $academic_year_id;
            //             $data['teacher_id'] = $obj->id;
            //             $data['status'] = 1;
            //             $data['created_at'] = date('Y-m-d H:i:s');
            //             $data['created_by'] = logged_in_user_id();
            //             $this->teacher->insert('teacher_attendances', $data);
            //         }
            //     }
            // }

            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['day'] = date('d', strtotime($this->input->post('date')));
            $this->data['month'] = date('m', strtotime($this->input->post('date')));
            $this->data['year'] = date('Y', strtotime($this->input->post('date')));

            $this->data['date'] = $date;
            create_log('Has been process Teacher Attendance'); 
        }

        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            $condition['school_id'] = $this->session->userdata('school_id');
            if($condition['school_id'] && empty($this->data['holidays']))
            {
                $this->data['holidays'] = $this->process_holiday_dates($this->teacher->get_holiday_list($condition['school_id'])); 

            }
        }
        
        $this->layout->title($this->lang->line('teacher') . ' ' . $this->lang->line('attendance') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }



    /*****************Function update_single_attendance**********************************
    * @type            : Function
    * @function name   : update_single_attendance
    * @description     : Process to update single teacher attendance status               
    *                        
    * @param           : null
    * @return          : null 
    * ********************************************************** */ 
    public function update_single_attendance() {

        $status = $this->input->post('status');
        $data                   = array();
        $data['school_id']      = $this->input->post('school_id');
        $data['date']           = $this->input->post('date');
        $data['teacher_id']     = $this->input->post('teacher_id');
        $condition['school_id'] = $data['school_id'];      
        $condition['teacher_id'] = $data['teacher_id'];      
        $condition['month'] = date('m', strtotime($data['date']));
        $condition['year'] = date('Y', strtotime($data['date']));
        
        $school = $this->teacher->get_school_by_id($condition['school_id']); 
        if(!$school->academic_year_id){
           echo 'ay';
           die();
        }
        $condition['academic_year_id'] = $school->academic_year_id;
        $data['academic_year_id']   = $school->academic_year_id;
        $field = 'day_' . abs(date('d', strtotime($this->input->post('date'))));
        $get_single_attendance = $this->teacher->get_single('teacher_attendances', $condition);
        $is_leave = !empty($get_single_attendance) && $get_single_attendance->$field && $get_single_attendance->$field == "L" ? TRUE : FALSE;
        if(!$is_leave)
        {
            if ($this->teacher->update('teacher_attendances', array($field => $status, 'modified_at'=>date('Y-m-d H:i:s')), $condition)) {
                if($status == "A")
                {
                    $data['teacher'] = $this->teacher->get_single_teacher($data['teacher_id']);
                    $this->_send_message_notification($data);
                }
               
                echo TRUE;
            } else {
                echo FALSE;
            }
        }
        else
        {
            echo FALSE;
        }
       
    }

    function process_holiday_dates($holidays)
    {
        $holiday_dates = array();
        foreach($holidays as $holiday)
        {
            if($holiday->date_from == $holiday->date_to)
            {
                $holiday_dates[] = date("d-m-Y",strtotime($holiday->date_from));
            }
            else if($holiday->date_from < $holiday->date_to)
            {
                $count = 0;
                $date_from = strtotime($holiday->date_from);
                $date_to   = strtotime($holiday->date_to);
                while($date_from <= $date_to && $count<100)
                {
                    $count++;
                    $holiday_dates[]   = date("d-m-Y",$date_from);
                    $date_from  = strtotime('+1 day', $date_from);
                }
            }
        }
        return $holiday_dates;
    }

    
    /*****************Function update_all_attendance**********************************
    * @type            : Function
    * @function name   : update_all_attendance
    * @description     : Process to update all teacher attendance status                 
    *                        
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function update_all_attendance() {

        $status = $this->input->post('status');
        $data       = array();
        $data['school_id']      = $this->input->post('school_id');
        $data['date']           = $this->input->post('date');

        $condition['school_id'] = $data['school_id'];
        $condition['month'] = date('m', strtotime($data['date']));
        $condition['year'] = date('Y', strtotime($data['date']));
        
        $school = $this->teacher->get_school_by_id($condition['school_id']);   
        if(!$school->academic_year_id){
           echo 'ay';
           die();
        }
        $condition['academic_year_id'] = $school->academic_year_id;
        $data['academic_year_id']      = $school->academic_year_id;
        $field = 'day_' . abs(date('d', strtotime($this->input->post('date'))));
        //$condition["$field !="] = "L";
        $update_data = array($field => $status, 'modified_at'=>date('Y-m-d H:i:s'));
        if ($this->teacher->update_teacher_attendance($update_data, $condition,$field)) {
            if($status == "A")
            {
                $teachers = $this->teacher->get_teacher_list($data['school_id']);
                foreach($teachers as $teacher)
                {
                    $data['teacher'] = $teacher;
                    $this->_send_message_notification($data);
                }
            }
            echo TRUE;
        } else {
            echo FALSE;
        }
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
       
       if($_POST){            
                $school_id = $this->input->post('school_id');
                $date       = $this->input->post('date');
                $month      = date('m', strtotime($this->input->post('date')));
                $year       = date('Y', strtotime($this->input->post('date')));
                $day = date('d', strtotime($this->input->post('date')));		
                $draw = $this->input->post('draw');		
       }		
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN)
        {
            $school_id = $this->session->userdata('school_id');
        }
        $school = $this->teacher->get_school_by_id($school_id);
        $holidays = $this->process_holiday_dates($this->teacher->get_holiday_list($school_id)); 
       
     if($school_id && $date){
        $totalRecords = $this->teacher->get_teacher_list_total($school_id);
        $teachers =  $this->teacher->get_teacher_list($school_id);     
      }
      else
      {
           $totalRecords =0;
           $data =  array();
           $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
           );
           echo json_encode($response);
           exit;
      }
      $condition = array(
        'school_id' => $school_id,
        'academic_year_id' => $school->academic_year_id,
        'month' => $month,
        'year' => $year
    );
    $attendence_param = $condition;
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($teachers) && !empty($teachers)){
               foreach($teachers as $obj){
                    $condition['teacher_id'] = $obj->id;
                    $month_attendance = $this->teacher->get_single('teacher_attendances', $condition);
                    if (empty($month_attendance)) {                       
                        $attendence_param['academic_year_id'] = $school->academic_year_id;
                        $attendence_param['teacher_id'] = $obj->id;
                        $attendence_param['status'] = 1;
                        $attendence_param['created_at'] = date('Y-m-d H:i:s');
                        $attendence_param['created_by'] = logged_in_user_id();
                        $this->teacher->insert('teacher_attendances', $attendence_param);
                    }  
                if (isset($day)) {
                   
                   $attendance = get_teacher_attendance($obj->id, $school_id, $school->academic_year_id, $year, $month, $day);
                } 
                else {
                    $attendance = '';
                }
               if ($obj->photo != '') {
                    $teacher_photo = '<img src="'.UPLOAD_PATH.'/teacher-photo/'.$obj->photo.'" alt="" width="60" />';
               } else {
                    $teacher_photo = '<img src="'.IMG_URL.'/default-user.png" alt="" width="60" />';
               }
              
                $p_checked = $attendance == "P" ? 'checked="checked"' : "";
                $p_radio = '<input type="radio" value="P" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="present fn_single_attendnce" '.$p_checked.' />';
                $a_checked = $attendance == "A" ? 'checked="checked"' : "";
                $a_radio = '<input type="radio" value="A" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="absent fn_single_attendnce" '.$a_checked.' />';
                $l_checked = $attendance == "L" ? 'checked="checked" disabled="disabled"' : "";
                $l_radio = '<input type="radio" value="L" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="late fn_single_attendnce" '.$l_checked.' disabled="disabled"/>';
                $row_data = array();
                    $row_data[] = $count;
                   $row_data[] = $teacher_photo;
                   $row_data[] = $obj->name;
                 //  var_dump($row_data);
                   $row_data[] = $obj->responsibility;
                   $row_data[] = $obj->phone;
                   $row_data[] = $obj->email;
                   $row_data[] = $p_radio;
                   $row_data[] = $l_radio;
                   $row_data[] = $a_radio;
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
    /*****************Function _send_message_notification**********************************
    * @type            : Function
    * @function name   : _send_message_notification
    * @description     : Process to send inapp message to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send_message_notification($message_data = null) {
 
        $data                       = array("");
        $data['school_id']          = $message_data['school_id'];
        $data['academic_year_id']   = $message_data['academic_year_id'];
        $teacher                    = $message_data['teacher'];
        $data['subject']            = $this->lang->line('attandance_marked_abscent');;   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
        
                       
        $message = $this->lang->line('hi'). ' '. $teacher->name.',';
        $message .= '<br/>';
        $message .= $this->lang->line('your_attandance_marked_abscent');
        $message .= " for the date <b>".$message_data['date'].'</b> ';
        $message .= '. If you have any questions please contact office';
        $message .= '<br/>';
        $message .= $this->lang->line('thank_you').'<br/>';
        $data['body'] = $message;
        $data['receiver_id'] = $teacher->t_user_id;
        $data['receiver_role_id'] = TEACHER;
        $this->message->send_message($data);
    }
}
