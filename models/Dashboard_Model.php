<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_message_list($type) {

        $this->db->select('MR.*, M.*');
        $this->db->from('message_relationships AS MR');
        $this->db->join('messages AS M', 'M.id = MR.message_id', 'left');

        if ($type == 'draft') {
            $this->db->where('MR.status', 1);
            $this->db->where('MR.is_draft', 1);
            $this->db->where('MR.owner_id', logged_in_user_id());
            $this->db->where('MR.sender_id', logged_in_user_id());
        }
        if ($type == 'inbox') {
            $this->db->where('MR.status', 1);
            $this->db->where('MR.owner_id', logged_in_user_id());
            $this->db->where('MR.receiver_id', logged_in_user_id());
        }
        if ($type == 'new') {
            $this->db->where('MR.status', 1);
            $this->db->where('MR.owner_id', logged_in_user_id());
            $this->db->where('MR.is_read', 0);
            $this->db->where('MR.receiver_id', logged_in_user_id());
        }
        if ($type == 'trash') {
            $this->db->where('MR.status', 1);
            $this->db->where('MR.is_trash', 1);
            $this->db->where('MR.owner_id', logged_in_user_id());
        }
        if ($type == 'sent') {
            $this->db->where('MR.status', 1);
            $this->db->where('MR.is_draft', 0);
            $this->db->where('MR.is_trash', 0);
            $this->db->where('MR.sender_id', logged_in_user_id());
            $this->db->where('MR.owner_id', logged_in_user_id());
        }

        return $this->db->get()->result();
    }

    public function get_user_by_role($school_id = null) {

        $this->db->select('COUNT(U.role_id) AS total_user, R.name');
        $this->db->from('users AS U');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->group_by('U.role_id');
        $this->db->where('U.status', 1);
        if ($school_id) {
            $this->db->where('U.school_id', $school_id);
        }
        return $this->db->get()->result();
    }

    public function get_student_by_class($school_id = null) {

        $this->db->select('COUNT(E.student_id) AS total_student, C.name AS class_name');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');

        $this->db->group_by('E.class_id');
        $this->db->where('E.status', 1);
        $this->db->where('E.academic_year_id = SC.academic_year_id');
        $this->db->where('S.status_type', 'regular');
        if ($school_id) {
            $this->db->where('E.school_id', $school_id);
        }
        return $this->db->get()->result();
    }

    public function get_drop_student_by_class($school_id = null) {

        $this->db->select('COUNT(E.student_id) AS total_student, C.name AS class_name');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');

        $this->db->group_by('E.class_id');
        $this->db->where('E.status', 1);
        $this->db->where('E.academic_year_id = SC.academic_year_id');
        $this->db->where('S.status_type !=', 'regular');
        if ($school_id) {
            $this->db->where('E.school_id', $school_id);
        }
        return $this->db->get()->result();
    }

    public function get_total_student($school_id = null) {
        $school = $this->get_single('schools', array('id' => $school_id));

        if ($this->session->userdata('role_id') == STUDENT) {
            
            $class_id = $this->session->userdata('class_id');
            
            $this->db->select('COUNT(E.id) AS total_student');
            $this->db->from('students AS S');
            $this->db->join('enrollments AS E','S.id = E.student_id','left');

            if ($school_id) {
                $this->db->where('E.school_id', $school_id);
            }
            if ($class_id) {
                $this->db->where('E.class_id', $class_id);
            }
            if ($school) {
                $this->db->where('E.academic_year_id', $school->academic_year_id);
            }

            

        }else if ($this->session->userdata('role_id') == GUARDIAN) {
            
            $this->db->select('COUNT(S.id) AS total_student');
            $this->db->from('students AS S');
            $this->db->join('enrollments AS E','S.id = E.student_id','left');
            $this->db->where('S.status', 1);
            if ($school_id) {
                $this->db->where('S.school_id', $school_id);
            }
            $this->db->where('S.guardian_id',  $this->session->userdata('profile_id'));
            if ($school) {
                $this->db->where('E.academic_year_id', $school->academic_year_id);
            }
            
        } else {            
          
            $this->db->select('COUNT(S.id) AS total_student');
            $this->db->from('students AS S');
            $this->db->join('enrollments AS E','S.id = E.student_id','left');
            $this->db->where('S.status', 1);
            if ($school_id) {
                $this->db->where('E.school_id', $school_id);
            }
            if ($school) {
                $this->db->where('E.academic_year_id', $school->academic_year_id);
            }
        }
        $this->db->where('S.status_type', 'regular');

        $result =  $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $result->row()->total_student;
    }

    public function get_total_guardian($school_id = null) {

        if ($this->session->userdata('role_id') == STUDENT) {

            $profile_id = $this->session->userdata('profile_id');
            $student = $this->get_single('students', array('id' => $profile_id));

            $this->db->select('COUNT(G.id) AS total_guardian');
            $this->db->from('guardians AS G');
            $this->db->where('G.id', $student->guardian_id);
           
        } else {

            $this->db->select('COUNT(G.id) AS total_guardian');
            $this->db->from('guardians AS G');
        }

         $this->db->where('G.status', 1);
        if ($school_id) {
            $this->db->where('G.school_id', $school_id);
        }
        
        return $this->db->get()->row()->total_guardian;
    }

    public function get_total_class($school_id = null) {

        $this->db->select('COUNT(C.id) AS total_class');
        $this->db->from('classes AS C');
        $this->db->where('C.status', 1);
        if ($school_id) {
            $this->db->where('C.school_id', $school_id);
        }
        return $this->db->get()->row()->total_class;
    }

    public function get_total_teacher($school_id = null) {

        $this->db->select('COUNT(T.id) AS total_teacher');
        $this->db->from('teachers AS T');
        $this->db->where('T.status', 1);
        if ($school_id) {
            $this->db->where('T.school_id', $school_id);
        }
        $this->db->where('T.alumni !=1');

        return $this->db->get()->row()->total_teacher;
    }
	


    public function get_total_employee($school_id = null) {

        $this->db->select('COUNT(E.id) AS total_employee');
        $this->db->from('employees AS E');
        $this->db->where('E.status', 1);
        if ($school_id) {
            $this->db->where('E.school_id', $school_id);
        }
        $this->db->where('E.alumni !=1');

        return $this->db->get()->row()->total_employee;
    }

    public function get_total_expenditure($school_id = null) {

        $this->db->select('SUM(E.amount) AS total_expenditure');
        $this->db->from('expenditures AS E');
        if ($school_id) {
            $this->db->where('E.school_id', $school_id);
        }
        return $this->db->get()->row()->total_expenditure;
    }

    public function get_total_income($school_id = null) {

        $this->db->select('SUM(T.amount) AS total_income');
        $this->db->from('transactions AS T');

        if ($school_id) {
            $this->db->where('T.school_id', $school_id);
        }
        return $this->db->get()->row()->total_income;
    }

public function get_total_attended_teacher($school_id = null) {

        $month	=	date("m");
	$year	=	date("Y");
	$day	=	date("j");
	$this->db->select('COUNT(TA.id) AS total_attended_teacher');
        $this->db->from('teacher_attendances AS TA');
	$this->db->where('TA.month', $month);
        $this->db->where('TA.year', $year);
	$this->db->where('TA.day_'.$day, 'P');
	 if ($school_id) {
            $this->db->where('TA.school_id', $school_id);
        }  
	
        return $this->db->get()->row()->total_attended_teacher;
	//return 10;
    }	

public function get_total_attended_student($school_id = null) {

        $month	=	date('m');
	$year	=	date('Y');
	$day	=	date("j");
	$this->db->select('COUNT(SA.id) AS total_attended_student');
        $this->db->from('student_attendances AS SA');
	$this->db->where('SA.month', $month);
        $this->db->where('SA.year', $year);
	$this->db->where('SA.day_'.$day, 'P');
	if ($school_id) {
            $this->db->where('SA.school_id', $school_id);
        }       
		//return 10;
        return $this->db->get()->row()->total_attended_student;
    }
	
public function get_total_attended_employee($school_id = null) {

        $month	=	date('m');
	$year	=	date('Y');
	$day	=	date("j");
	$this->db->select('COUNT(EA.id) AS total_attended_employee');
        $this->db->from('employee_attendances AS EA');
	$this->db->where('EA.month', $month);
        $this->db->where('EA.year', $year);
	$this->db->where('EA.day_'.$day, 'P');
	if ($school_id) {
            $this->db->where('EA.school_id', $school_id);
        }       
		//return 10;
        return $this->db->get()->row()->total_attended_employee;
    }

    /*function get_school_class_students(){
        $school_id = $this->session->userdata('school_id');  
        $school = $this->get_single('schools', array('id' => $school_id));
        $academic_year_id = $school->academic_year_id;
        $sql = "SELECT * FROM `classes` WHERE `school_id` = '$school_id' and status = '1'";
        $dataRe = $this->db->query($sql)->result();
        $saveData = array();
        foreach($dataRe as $key=>$class){
            //echo "<pre>";print_r($class);die("aaaqqqqq");
            $class_id = $class->id;
            $sql = "SELECT * FROM `enrollments` WHERE `school_id` = '$school_id' and class_id = '$class_id' and academic_year_id = '$academic_year_id'";
            $students = $this->db->query($sql)->num_rows();
            //echo "<pre>";print_r($students);die;
            $saveData[$key]['Class'] = $class->name;
            $saveData[$key]['Students'] = $students;
        }
        return $saveData;
    }*/
}

