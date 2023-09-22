<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Schedule_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_schedule_list($class_id = null, $school_id = null, $academic_year_id = null ){
        
        if(!$class_id){
           $class_id = $this->session->userdata('class_id');
        } 
       
        $this->db->select('ES.*, SC.school_name, E.title, C.name AS class_name, S.name AS subject, AY.session_year, SCT.name as section_name');
        $this->db->from('exam_schedules AS ES');
        $this->db->join('classes AS C', 'C.id = ES.class_id', 'left');
        $this->db->join('sections AS SCT', 'SCT.id = ES.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = ES.subject_id', 'left');
        $this->db->join('exams AS E', 'E.id = ES.exam_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = ES.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ES.school_id', 'left');
        // $this->db->where('s.school_id', 0); 

        if($academic_year_id){
            $this->db->where('ES.academic_year_id', $academic_year_id);
        }        
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
        }        
        if($class_id > 0){
            $this->db->where('ES.class_id', $class_id);            
        }
        if($school_id && $this->session->userdata('role_id') == SUPER_ADMIN){
            $this->db->where('ES.school_id', $school_id); 
        }         
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $this->db->where('ES.school_id', $this->session->userdata('school_id'));
        }
        $this->db->order_by('ES.id', 'DESC');
        $result = $this->db->get();
       
        return $result->result();
        
    }
   
    public function check_mark($schedule){
        
        $this->db->select('sum(written_obtain+practical_obtain+practical_obtain) as total_mark');
        $this->db->from('marks AS M'); 
        $this->db->where('M.school_id', $schedule->school_id);
        $this->db->where('M.exam_id', $schedule->exam_id);
        $this->db->where('M.subject_id', $schedule->subject_id);
        $this->db->where('M.class_id', $schedule->class_id);
        $this->db->where('M.academic_year_id', $schedule->academic_year_id); 
        $result = $this->db->get();

        return $result->row();            
    }
    public function get_student_list($school_id, $class_id, $section_id, $academic_year_id = null ){
        
        $this->db->select('S.user_id,S.email, S.phone, S.name, G.name AS g_name, G.email AS g_email, G.user_id AS g_user_id, G.phone AS g_phone');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);
        $this->db->where('E.class_id', $class_id);
        $this->db->where('E.section_id', $class_id);
        $this->db->where('E.school_id', $school_id);
        $this->db->where('S.status_type', 'regular');        
        return $this->db->get()->result();
        
    }
    
     public function get_single_schedule($id){
         
        $this->db->select('ES.*,  SC.school_name, E.title, C.name AS class_name, S.name AS subject, AY.session_year');
        $this->db->from('exam_schedules AS ES');
        $this->db->join('classes AS C', 'C.id = ES.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = ES.subject_id', 'left');
        $this->db->join('exams AS E', 'E.id = ES.exam_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = ES.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = ES.school_id', 'left');
        $this->db->where('ES.id', $id);
        return $this->db->get()->row();
        
    }

    
     function duplicate_check($school_id, $academic_year_id, $exam_id, $class_id, $subject_id, $section_id, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        
        $this->db->where('school_id', $school_id);
        $this->db->where('exam_id', $exam_id);
        $this->db->where('class_id', $class_id);
        $this->db->where('subject_id', $subject_id);         
        $this->db->where('section_id', $section_id);              
        $this->db->where('academic_year_id', $academic_year_id);     
        
        return $this->db->get('exam_schedules')->num_rows();            
    }
    
     function duplicate_room_check($school_id, $academic_year_id, $room_no, $exam_date, $start_time, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        
        $this->db->where('school_id', $school_id);
        $this->db->where('room_no', $room_no);
        $this->db->where('exam_date', $exam_date);
        $this->db->where('start_time', $start_time);      
        $this->db->where('academic_year_id', $academic_year_id);           
        
        return $this->db->get('exam_schedules')->num_rows();            
    }

    public function generate_custom_id($school_id){		
		$this->db->select('E.*,S.school_code');
        $this->db->from('exam_schedules AS E');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
		$this->db->where("E.school_id", $school_id);
        $this->db->where("E.custom_id is not null");
        $this->db->limit(1);
        $this->db->order_by('E.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->custom_id) && $row->custom_id!= ''){
			$arr=explode("/",$row->custom_id);
           
			$invoice_no="EXM".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="EXM".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error Generating custom id");
                    redirect("/exam/shedule");
                    die("Errror id 2");
                    break;
                }
            }
		}
		else{
            $school = $this->get_school_by_id($school_id);
            $icount = 0;
			$invoice_no="EXM".$school->school_code."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="EXM".$school->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error Generating custom id");
                    redirect("/exam/shedule");
                    die("Errror id");
                    break;
                }
            }
		}	


		return $invoice_no;
	}
    public function check_invoice_no($invoice_no,$school_id ="")
    {
        $this->db->select('E.*');
        $this->db->from('exam_schedules AS E');
        $this->db->where("E.school_id",$school_id);   
        $this->db->limit(1);
        $this->db->where("E.custom_id",$invoice_no);     
        $row= $this->db->get()->row();
        if(empty($row))
        {
            return true;
        }
        else return false;
    }

}
