<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Employee.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Employee
 * @description     : Manage employee daily attendance.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Employee extends MY_Controller {

    public $data = array();    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Employee_Model', 'employee', true);
         $this->load->library('message/message');
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Employee Attendance" user interface                 
    *                    and Process to manage daily Employee attendance    
    * @param           : null
    * @return          : null 
    * ********************************************************** */    
    public function index() { 
        
          check_permission(VIEW);
        
        if($_POST){         
			if($this->input->post('date')!=''){				
				$date       = $this->input->post('date');
				$month      = date('m', strtotime($this->input->post('date')));
				$year       = date('Y', strtotime($this->input->post('date')));
				$this->data['day'] = date('d', strtotime($this->input->post('date')));
				$this->data['month'] = date('m', strtotime($this->input->post('date')));
				$this->data['year'] = date('Y', strtotime($this->input->post('date')));
				$this->data['date'] = $date;  				
			}
			else if($this->input->post('month')!=''){
				$arr=explode("-",$this->input->post('month'));				
				$month      = $arr[0];								
				$year       = $arr[1];				
				$this->data['month'] = $month;
				$this->data['year'] = $year;
				$this->data['full_month'] = $this->input->post('month');  				
			}
                      
            $school_id  = $this->input->post('school_id');
            
            $school = $this->employee->get_school_by_id($school_id); 
            $this->data['holidays'] = []; 
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('attendance/employee/index');
            }
            if($date && in_array(date( "d-m-Y",strtotime($date)),$this->data['holidays']))
            {
                error($this->lang->line('selected_date_is_holiday'));
                redirect('attendance/employee/index');
            }
            // $this->data['employees'] = $this->employee->get_employee_list($school_id);            

            // $condition = array(              
            //     'school_id'=>$school_id,
            //     'month'=>$month,
            //     'year'=>$year
            // );
            
            // $data = $condition;
            // if(!empty($this->data['employees'])){
                
            //     foreach($this->data['employees'] as $obj){
                    
            //         $condition['employee_id'] = $obj->id;                    
            //         $attendance = $this->employee->get_single('employee_attendances', $condition);
                  
            //         if(empty($attendance)){
            //            $data['academic_year_id'] = $school->academic_year_id; 
            //            $data['employee_id'] = $obj->id; 
            //            $data['status'] = 1;
            //            $data['created_at'] = date('Y-m-d H:i:s');
            //            $data['created_by'] = logged_in_user_id();
            //            $this->employee->insert('employee_attendances', $data);
            //         }                    
            //     }
            // }
            
            $this->data['school_id'] = $school_id;
            $this->data['academic_year_id'] = $school->academic_year_id;
            
            
            create_log('Has been process employee attendance'); 
            
        }
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['school_id'] = $this->session->userdata('school_id');
            if($condition['school_id'] && empty($this->data['holidays']))
            {
                $this->data['holidays'] = $this->process_holiday_dates($this->employee->get_holiday_list($condition['school_id'])); 

            }
        }
        $this->layout->title($this->lang->line('employee'). ' ' . $this->lang->line('attendance'). ' | ' . SMS);
        $this->layout->view('employee/index', $this->data);  
    }

 

    /*****************Function update_single_attendance**********************************
    * @type            : Function
    * @function name   : update_single_attendance
    * @description     : Process to update single employee attendance status               
    *                        
    * @param           : null
    * @return          : null 
    * ********************************************************** */  
    public function update_single_attendance(){   
       
        $data                   = array();
        $data['school_id']      = $this->input->post('school_id');
        $data['date']           = $this->input->post('date');
        $data['month']          = $this->input->post('month');
        $data['employee_id']    = $this->input->post('employee_id');
      

        $status     = $this->input->post('status');
        $condition['school_id'] = $this->input->post('school_id');;        
        $condition['employee_id'] = $this->input->post('employee_id');  		       
        $school = $this->employee->get_school_by_id($condition['school_id']); 
        
        if(!$school->academic_year_id){
           echo 'ay';
           die();
        }       
        $condition['academic_year_id'] = $school->academic_year_id;
        $data['academic_year_id'] = $school->academic_year_id;

		 $column_arr=array();
		if($this->input->post('date') !=''){
			 $condition['month']      = date('m', strtotime($this->input->post('date')));
			$condition['year']       = date('Y', strtotime($this->input->post('date')));			
			 $field = 'day_'.abs(date('d', strtotime($this->input->post('date'))));		
			$column_arr[$field]=$status;	
            $get_single_attendance = $this->employee->get_single('employee_attendances', $condition);
            $is_leave = !empty($get_single_attendance) && $get_single_attendance->$field && $get_single_attendance->$field == "L" ? TRUE : FALSE;
		}
		else if($this->input->post('month') !=''){
            $is_leave   = false;
            $month = $this->input->post('month');
			$arr=explode("-",$month);
			$condition['month']=$arr[0];
			$condition['year']       = $arr[1];			
            $get_attendance = (array) $this->employee->get_single('employee_attendances', $condition);
            $holidays = $this->process_holiday_dates($this->employee->get_holiday_list($data['school_id'])); 
            $cols = array_keys($get_attendance, "L");
			for($i=1;$i<=31;$i++){
                $date= "$i-$month";
                $day = date("N",strtotime($date));
                if(!in_array('day_'.$i,$cols) && $day !=7 /*&& !in_array($date,$holidays)*/)
                {
                    $column_arr['day_'.$i]=$status;
                }
			}			
		} 
        
		$column_arr['modified_at']=date('Y-m-d H:i:s');
        

        
        if(!$is_leave)
        {
            if($this->employee->update('employee_attendances', $column_arr, $condition)){
                if($status == "A")
                {
                    $data['employee'] = $this->employee->get_single_employee($data['employee_id']);
                    $this->_send_message_notification($data);
                }
                echo TRUE;
            }else{
                $data = $this->employee->get_single('employee_attendances', $condition);
               
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
           $draw = $this->input->post('draw');	
           if($this->input->post('date')!=''){				
                $date       = $this->input->post('date');
                $month      = date('m', strtotime($this->input->post('date')));
                $year       = date('Y', strtotime($this->input->post('date')));
                $day = date('d', strtotime($this->input->post('date')));			
            }
            else if($this->input->post('month')!=''){
                $arr=explode("-",$this->input->post('month'));		
                $month      = $arr[0];								
                $year       = $arr[1];				
                $full_month = $this->input->post('month');  				
            }
          
       }		
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
        $school_id = $this->session->userdata('school_id');
    }
       $school = $this->employee->get_school_by_id($school_id);
        $holidays = []; 
       
       if($school_id && $month){
        
        $totalRecords = $this->employee->get_employee_list_total($school_id);
        
       $employees = $this->employee->get_employee_list($school_id);            
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
        'school_id'=>$school_id,
         'month'=>$month,
         'year'=>$year,
         'academic_year_id'=>$school->academic_year_id
        );
       $attendence_param = $condition;
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($employees) && !empty($employees)){
               foreach($employees as $obj){
                $condition['employee_id'] = $obj->id;          
                          
                $attendance_month = $this->employee->get_single('employee_attendances', $condition);
                
                if(empty($attendance_month)){
                   $attendence_param['academic_year_id'] = $school->academic_year_id; 
                   $attendence_param['employee_id'] = $obj->id; 
                   $attendence_param['status'] = 1;
                   $attendence_param['created_at'] = date('Y-m-d H:i:s');
                   $attendence_param['created_by'] = logged_in_user_id();
                   
                   $this->employee->insert('employee_attendances', $attendence_param);
                   $attendance_month = $this->employee->get_single('employee_attendances', $condition);
                }      
                if (isset($day)) {
                    $attendance = get_employee_attendance($obj->id, $school_id, $school->academic_year_id, $year, $month, $day);
                } 
                elseif(isset($full_month))
                {

                    $p_count= 0;
                    $l_count= 0;
                    $a_count= 0;
                    foreach ($attendance_month as $key => $value) {
                        
                        if (strpos($key, 'day_') !== false) {
                           if($value == "P")
                           {
                                $p_count++;
                           }
                           elseif($value == "A")
                           {
                            $a_count++;
                           }
                           elseif($value == "L")
                           {
                            $l_count++;
                           }
                        }
                    }
                    if($p_count ==31) $attendance = "P";
                    elseif($l_count ==31) $attendance = "L";
                    elseif($a_count ==31) $attendance = "A";
                    else   $attendance = '';
                }
                else {
                    $attendance = '';
                }
               if ($obj->photo != '') {
                    $employee_photo = '<img src="'.UPLOAD_PATH.'/employee-photo/'.$obj->photo.'" alt="" width="60" />';
               } else {
                    $employee_photo = '<img src="'.IMG_URL.'/default-user.png" alt="" width="60" />';
               }
              
                $p_checked = $attendance == "P" ? 'checked="checked"' : "";
                $p_radio = '<input type="radio" value="P" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="present fn_single_attendnce" '.$p_checked.' />';
                $a_checked = $attendance == "A" ? 'checked="checked"' : "";
                $a_radio = '<input type="radio" value="A" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="absent fn_single_attendnce" '.$a_checked.' />';
                $l_checked = $attendance == "L" ? 'checked="checked" disabled="disabled"' : "";
                $l_radio = '<input type="radio" value="L" itemid="'.$obj->id.'" name="student_'.$obj->id.'" class="late fn_single_attendnce" '.$l_checked.' disabled="disabled"/>';
                $row_data = array();
                    $row_data[] = $count;
                   $row_data[] = $employee_photo;
                   $row_data[] = $obj->name;
                 //  var_dump($row_data);
                   $row_data[] = $obj->designtion;
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
    /*****************Function update_all_attendance**********************************
    * @type            : Function
    * @function name   : update_all_attendance
    * @description     : Process to update all employee attendance status                 
    *                        
    * @param           : null
    * @return          : null 
    * ********************************************************** */ 
     public function update_all_attendance(){        
        $data                   = array();
        $data['school_id']      = $this->input->post('school_id');
        $data['date']           = $this->input->post('date');
        $data['month']          = $this->input->post('month');
        $data['employee_id']    = $this->input->post('employee_id');
        $status     = $this->input->post('status');               
        $condition['school_id'] = $this->input->post('school_id');
        $school = $this->employee->get_school_by_id($condition['school_id']); 
        
        if(!$school->academic_year_id){
           echo 'ay';
           die();
        }
        
        $condition['academic_year_id'] = $school->academic_year_id;
        $data['academic_year_id'] = $school->academic_year_id;
        $column_arr=array();
		if($this->input->post('date') !=''){
			 $condition['month']      = date('m', strtotime($this->input->post('date')));
			$condition['year']       = date('Y', strtotime($this->input->post('date')));			
			 $field = 'day_'.abs(date('d', strtotime($this->input->post('date'))));		
             $update_data = array();
			$update_data[$field]=$status;		
            $update_data['modified_at']=date('Y-m-d H:i:s');
            //$field = 'day_'.abs(date('d', strtotime($this->input->post('date'))));
            if($this->employee->update_employee_attendance( $update_data,$condition,$field )){
                // echo $this->db->last_query();
                // die();
                if($status == "A")
                {
                    $employees = $this->employee->get_employee_list($data['school_id']);
                    
                    foreach($employees as $employee)
                    {
                        $data['employee'] = $employee;
                        $this->_send_message_notification($data);
                    }
                }
                echo TRUE;
            }else{
                // echo $this->db->last_query();
                // die();
                echo FALSE;
            }       
		}
		else if($this->input->post('month') !=''){
            $month = $this->input->post('month');
			$arr=explode("-",$month);
			$condition['month'] =$arr[0];
			$condition['year']  = $arr[1];
            $employees_attendance = $this->employee->get_list('employee_attendances',$condition);
            $update_rows = array();
            $holidays = $this->process_holiday_dates($this->employee->get_holiday_list($data['school_id'])); 
            foreach($employees_attendance as $attendance)
            {
                $attendance = (array) $attendance;
                $leave_days = array_keys($attendance,"L");
                
                $column_arr=array();
                $column_arr['id'] = $attendance['id'];
                $column_arr['modified_at']=date('Y-m-d H:i:s');
                for($i=1;$i<=31;$i++){
                    $field = 'day_'.$i;
                    $date= "$i-$month";
                    $day = date("N",strtotime($date));
                    
                    if(!in_array($field,$leave_days) && $day !=7 /* && !in_array($date,$holidays)*/)
                    {
                        $column_arr[$field]=$status;
                    }
                }
                $update_rows[] = $column_arr;

            }
            if($this->employee->update_batch('employee_attendances', $update_rows, 'id')){
                if($status == "A")
                {
                    $employees = $this->employee->get_employee_list($data['school_id']);
                    
                    foreach($employees as $employee)
                    {
                        $data['employee'] = $employee;
                        $this->_send_message_notification($data);
                    }
                }
                echo TRUE;
            }else{
                echo FALSE;
            }      
	
           

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
 
        $data                       = array();
        $data['school_id']          = $message_data['school_id'];
        $data['academic_year_id']   = $message_data['academic_year_id'];
        $employee                   = $message_data['employee'];
        $data['subject']            = $this->lang->line('attandance_marked_abscent');;   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
        if($message_data['date']) {
            $date = " for the date <b>".$message_data['date']."</b>";
        }
        else
        {
            $date = " for the month <b>".$message_data['month']."</b>";
        }
                       
        $message = $this->lang->line('hi'). ' '. $employee->name.',';
        $message .= '<br/>';
        $message .= $this->lang->line('your_attandance_marked_abscent');
        $message .= $date;
        $message .= '. If you have any questions please contact office';
        $message .= '<br/>';
        $message .= $this->lang->line('thank_you').'<br/>';
        $data['body'] = $message;
        $data['receiver_id'] = $employee->e_user_id;
        $data['receiver_role_id'] = $employee->role_id;
        $this->message->send_message($data);
    }
    
}
