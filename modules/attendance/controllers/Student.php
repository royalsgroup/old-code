<?php



/* * *****************Student.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Student
 * @description     : Manage student daily attendance.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Student extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        
        $this->load->helper('report');
        $this->load->model('Student_Model', 'student', true);
		$this->load->model('Academic/Classes_Model', 'classes', true);
        $this->load->library('message/message');
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Student Attendance" user interface                 
    *                    and Process to manage daily Student attendance    
    * @param           : null
    * @return          : null 
    * ********************************************************** */ 
    public function index() {
        
        check_permission(VIEW);
        $this->data['holidays'] = array();
        if ($_POST) {

            $school_id  = $this->input->post('school_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $date = $this->input->post('date');
            
            $month = date('m', strtotime($this->input->post('date')));
            $year = date('Y', strtotime($this->input->post('date')));
            
            $school = $this->student->get_school_by_id($school_id);
            $this->data['holidays'] = $this->process_holiday_dates($this->student->get_holiday_list($school_id)); 
        
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('attendance/student/index');
            }
            if($date && in_array(date( "d-m-Y",strtotime($date)),$this->data['holidays']))
            {
                error($this->lang->line('selected_date_is_holiday'));
                redirect('attendance/student/index');
            }
            //$this->data['students'] = $this->student->get_student_list($school_id, $class_id, $section_id, $school->academic_year_id);
            // $condition = array(
            //     'school_id' => $school_id,
            //     'class_id' => $class_id,
            //     'academic_year_id' => $school->academic_year_id,
            //     'month' => $month,
            //     'year' => $year
            // );
            
            // if($section_id){
            //     $condition['section_id'] = $section_id;
            // }

            // $data = $condition;
            // if (!empty($this->data['students'])) {

            //     foreach ($this->data['students'] as $obj) {

            //         $condition['student_id'] = $obj->id;
            //         $attendance = $this->student->get_single('student_attendances', $condition);

            //         if (empty($attendance)) {  
                        
            //             $data['section_id'] = $obj->section_id;
            //             $data['student_id'] = $obj->id;
            //             $data['status'] = 1;
            //             $data['created_at'] = date('Y-m-d H:i:s');
            //             $data['created_by'] = logged_in_user_id();
            //             $this->student->insert('student_attendances', $data);
            //         }
            //     }
            // }

            $this->data['academic_year_id'] = $school->academic_year_id;
            $this->data['day'] = date('d', strtotime($this->input->post('date')));
            $this->data['month'] = date('m', strtotime($this->input->post('date')));
            $this->data['year'] = date('Y', strtotime($this->input->post('date')));
            $this->data['school_id'] = $school_id;
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['date'] = $date;
            
            create_log('Has been process student attendance'); 
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['school_id'] = $this->session->userdata('school_id');

            if($condition['school_id'] && empty($this->data['holidays']))
            {
                $this->data['holidays'] = $this->process_holiday_dates($this->student->get_holiday_list($condition['school_id'])); 

            }
            $this->data['classes'] = $this->classes->get_list_by_school( $condition['school_id']);
        }
        if($this->session->userdata('role_id') == TEACHER)
        {
            $class_id_raw = $this->student->get_teacher_section_classes( );
            if(!empty( $class_id_raw))
            {
                $class_ids = array_column($class_id_raw, 'class_id');
                 $this->data['teacher_classes'] = $class_ids;
            }
           

          
        }
        $this->layout->title($this->lang->line('student') . ' ' . $this->lang->line('attendance') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }

   
        
    /*****************Function guardian**********************************
    * @type            : Function
    * @function name   : guardian
    * @description     : Load "Student Attendance for guardian" user interface                 
    *                    and Process to manage daily Student attendance    
    * @param           : null
    * @return          : null 
    * ********************************************************** */ 
    public function guardian() {

        check_permission(VIEW);

        $this->data['month_number'] = 1;
        $this->data['days'] = 31;
        
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $academic_year_id = $this->input->post('academic_year_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $month = $this->input->post('month');


            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $academic_year_id;
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['month'] = $month;
            $this->data['month_number'] = date('m', strtotime($this->data['month']));
            $session = $this->student->get_single('academic_years', array('id' => $academic_year_id));
            $this->data['students'] = $this->student->get_student_attendance_list($school_id, $academic_year_id, $class_id, $section_id);
           
            $this->data['year'] = substr($session->session_year, -4);
            //echo date('t', mktime(0, 0, 0, $month, 1, $year)); die();
            //$this->data['days'] = cal_days_in_month(CAL_GREGORIAN, $this->data['month_number'], $this->data['year']);
            $this->data['days'] =  date('t', mktime(0, 0, 0, $this->data['month_number'], 1, $this->data['year'])); 
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){  
            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->student->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['academic_years'] = $this->student->get_list('academic_years', $condition, '', '', '', 'id', 'ASC');
        }

        $this->layout->title($this->lang->line('student') . ' ' . $this->lang->line('attendance') . ' ' . $this->lang->line('report') . ' | ' . SMS);
        $this->layout->view('student/attendance', $this->data);
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

    /*****************Function update_single_attendance**********************************
    * @type            : Function
    * @function name   : update_single_attendance
    * @description     : Process to update single student attendance status               
    *                        
    * @param           : null
    * @return          : null 
    * ********************************************************** */ 
    public function update_single_attendance() {   
      
        $status = $this->input->post('status');
        $data                   = array();
        $data['school_id']      = $this->input->post('school_id');
        $data['date']           = $this->input->post('date');
        $data['student_id']     = $this->input->post('student_id');
        $data['class_id']       = $this->input->post('class_id');
        $data['section_id']     = $this->input->post('section_id');

        $condition['school_id']  = $data['school_id'];
        $condition['student_id'] = $data['student_id'];
        $condition['class_id']   = $data['class_id'];
        
        if($data['section_id']){
           $condition['section_id'] = $data['section_id'];
        }
        
        $condition['month'] = date('m', strtotime($data['date']));
        $condition['year'] = date('Y', strtotime($data['date']));
        
        $school = $this->student->get_school_by_id($condition['school_id']); 
        if(!$school->academic_year_id){
           echo 'ay';
           die();
        }
        
        $condition['academic_year_id'] = $school->academic_year_id;
        $data['academic_year_id'] = $school->academic_year_id;
        
        $field = 'day_' . abs(date('d', strtotime($data['date'])));
        $get_single_attendance = $this->student->get_single('student_attendances', $condition);
        $is_leave = !empty($get_single_attendance) && $get_single_attendance->$field && $get_single_attendance->$field == "L" ? TRUE : FALSE;
        if(!$is_leave)
        {
            if ($this->student->update('student_attendances', array($field => $status, 'modified_at'=>date('Y-m-d H:i:s')), $condition)) {
                if($status == "A")
                {
                    $data['student'] = $this->student->get_single_student($data['student_id']);
                    $this->_send_message_notification($data);
                }
                echo TRUE;
            } else {
                //echo $this->db->last_query();
                
                echo FALSE;
            }
        }
        else
        {
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
       $search_text='';
       if($_POST){           
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            $school_id = $this->session->userdata('school_id');
        }else   $school_id = $this->input->post('school_id');
          
           $class_id = $this->input->post('class_id');
           $section_id = $this->input->post('section_id');
           $month = date('m', strtotime($this->input->post('date')));
           $year = date('Y', strtotime($this->input->post('date')));
           $day = date('d', strtotime($this->input->post('date')));
           $date = $this->input->post('date');
           $start = $this->input->post('start');
           $limit  = $this->input->post('length');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
       $school = $this->student->get_school_by_id($school_id);
        $holidays = $this->process_holiday_dates($this->student->get_holiday_list($school_id)); 
        $condition = array(
           'school_id' => $school_id,
           'class_id' => $class_id,
           'academic_year_id' => $school->academic_year_id,
           'month' => $month,
           'year' => $year
       );
       
       if($section_id){
           $condition['section_id'] = $section_id;
       }
       if($school_id && $class_id && $date && $section_id){
        
        $totalRecords = $this->student->get_student_list_total($school_id, $class_id, $section_id, $school->academic_year_id);
        
       $students = $this->student->get_student_list($school_id, $class_id, $section_id, $school->academic_year_id);
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
       $data = $condition;
       if (!empty($students)) {

           foreach ($students as $obj) {

               $condition['student_id'] = $obj->id;
               $attendance = $this->student->get_single('student_attendances', $condition);

               if (empty($attendance)) {  
                   $data['section_id'] = $obj->section_id;
                   $data['student_id'] = $obj->id;
                   $data['status'] = 1;
                   $data['created_at'] = date('Y-m-d H:i:s');
                   $data['created_by'] = logged_in_user_id();
                   $this->student->insert('student_attendances', $data);
               }
           }
       }

       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
       }
       
        
       
      
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($students) && !empty($students)){
               foreach($students as $obj){
                
                $attendance = get_student_attendance($obj->id, $school_id,  $school->academic_year_id, $class_id, $section_id, $year, $month, $day); 
               if ($obj->photo != '') {
                    $student_photo = '<img src="'.UPLOAD_PATH.'/student-photo/'.$obj->photo.'" alt="" width="60" />';
               } else {
                    $student_photo = '<img src="'.IMG_URL.'/default-user.png" alt="" width="60" />';
               }
              
                $p_checked = $attendance == "P" ? 'checked="checked"' : "";
                $p_radio = '<input type="radio" value="P" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="present fn_single_attendnce" '.$p_checked.' />';
                $a_checked = $attendance == "A" ? 'checked="checked"' : "";
                $a_radio = '<input type="radio" value="A" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="absent fn_single_attendnce" '.$a_checked.' />';
                $l_checked = $attendance == "L" ? 'checked="checked" disabled="disabled"' : "";

                $l_radio = '<input type="radio" value="L" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="late fn_single_attendnce" '.$l_checked.' disabled="disabled"/>';
                $row_data = array();
                    $row_data[] = $count;
                    $row_data[] = $obj->admission_no;
                   $row_data[] = $student_photo;
                   $row_data[] = $obj->name;
                 //  var_dump($row_data);
                   $row_data[] = $obj->email;
                   $row_data[] = $obj->phone;
                   $row_data[] = $obj->roll_no;
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
    /*****************Function update_all_attendance**********************************
    * @type            : Function
    * @function name   : update_all_attendance
    * @description     : Process to update all student attendance status                 
    *                        
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function update_all_attendance() {

        $status = $this->input->post('status');
        $data                   = array();
        $data['school_id']      = $this->input->post('school_id');
        $data['date']           = $this->input->post('date');
        $data['student_id']     = $this->input->post('student_id');
        $data['class_id']       = $this->input->post('class_id');
        $data['section_id']     = $this->input->post('section_id');

        $condition['school_id'] = $this->input->post('school_id');
        $condition['class_id'] = $this->input->post('class_id');
        
        if($this->input->post('section_id')){
           $condition['section_id'] = $this->input->post('section_id');
        }
        
        $condition['month'] = date('m', strtotime($this->input->post('date')));
        $condition['year'] = date('Y', strtotime($this->input->post('date')));
        
        $school = $this->student->get_school_by_id($condition['school_id']);   
        if(!$school->academic_year_id){
           echo 'ay';
           die();
        }
        
        $condition['academic_year_id'] = $school->academic_year_id;
        $data['academic_year_id'] = $school->academic_year_id;

        $field = 'day_' . abs(date('d', strtotime($this->input->post('date'))));
       // $condition["$field !="] = "L";
        $result = $this->student->update_student_attendance(array($field => $status, 'modified_at'=>date('Y-m-d H:i:s')), $condition,$field);
    //    echo $this->db->last_query();
    //     var_dump("result",$result);
        if ($result) {
            if($status == "A")
            {
                $students =  $this->student->get_student_list($data['school_id'], $data['class_id'], $data['section_id'], $data['academic_year_id']);
                foreach($students as $student)
                {
                    $data['student'] = $student;
                    $this->_send_message_notification($data);
                }
            }
            echo TRUE;
        } else {
            echo FALSE;
        }
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
        $student                    = $message_data['student'];
        $data['subject']            = $this->lang->line('attandance_marked_abscent');;   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
        
                       
        $message = $this->lang->line('hi'). ' '. $student->name.',';
        $message .= '<br/>';
        $message .= $this->lang->line('your_attandance_marked_abscent');
        $message .= " on <b>".$message_data['date'].'</b> ';
        $message .= '. If you have any questions please contact class teacher';
        $message .= '<br/>';
        $message .= $this->lang->line('thank_you').'<br/>';
        $data['body'] = $message;
        $data['receiver_id'] = $student->s_user_id;
        $data['receiver_role_id'] = STUDENT;
        if($student->s_user_id) $this->message->send_message($data);
        if($student->g_user_id)
        {  
            $message = $this->lang->line('hi'). ' '. $student->g_name.',';
            $message .= '<br/>';
            $message .= "Your child <b>".$student->name."<b>'s attendance is marked as abscent";
            $message .= " on <b>".$message_data['date'].'</b> ';
            $message .= '. If you have any questions please contact class teacher';
            $message .= '<br/>';
            $message .= $this->lang->line('thank_you').'<br/>';
            $data['body'] = $message;
            $data['receiver_id'] = $student->g_user_id;
            $data['receiver_role_id'] = GUARDIAN;
            $this->message->send_message($data);

        }
    }

}
