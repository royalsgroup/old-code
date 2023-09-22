<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Examresult_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_student_list($school_id = null, $class_id = null, $section_id = null, $academic_year_id = null){
        
        $this->db->select('S.*, E.roll_no, E.class_id, E.section_id, C.name AS class_name, AY.session_year');
        $this->db->from('enrollments AS E');        
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);       
        $this->db->where('E.school_id', $school_id);       
        $this->db->where('E.class_id', $class_id);
        
        if($section_id){
            $this->db->where('E.section_id', $section_id);
        }        
        $this->db->order_by('E.roll_no', 'ASC');
       
        return $this->db->get()->result();        
    }
    public function get_scheduled_subjects($school_id , $class_id , $academic_year_id, $exam_id,  $section_id = null, $optional = 0)
    {
        $this->db->select('ES.id, S.id subject_id,S.name, ES.*, ES.id schedule_id, EX.title as exam_name, EX.id exam_id');
        $this->db->from('exam_schedules AS ES');     
        $this->db->join('subjects AS S', 'S.id = ES.subject_id', 'left');
        $this->db->join('exams AS EX', 'EX.id = ES.exam_id', 'left');
        $this->db->where('ES.school_id', $school_id);
        $this->db->where('ES.class_id',  $class_id);
        $this->db->where('S.class_id',  $class_id);
        if ($exam_id)
        {
            $this->db->where('ES.exam_id',  $exam_id);
        }
        if ($section_id)
        {
            $this->db->where('ES.section_id',  $section_id);
        }
        $this->db->where('coalesce(ES.optional,0)',  $optional  );

        $this->db->where('ES.academic_year_id',  $academic_year_id);

        $this->db->order_by('S.name', 'ASC');

        $result =  $this->db->get();     
        return $result->result();
    }
    public function get_student_list_with_result($school_id = null,$exam_id = null, $class_id = null, $section_id = null, $academic_year_id = null){
        
        $this->db->select('M.*,S.name,S.admission_no, S.father_name, S.mother_name,S.photo, E.roll_no, E.class_id, E.section_id, C.name AS class_name, AY.session_year,SB.name as subject_name');
        $this->db->from('marks AS M');     
        $this->db->join('students AS S', 'S.id = M.student_id', 'left');
        // debug_a($exam_id);
        $this->db->join('subjects AS SB', 'SB.id = M.subject_id', 'left');

        $this->db->join('enrollments AS E', 'E.student_id = S.id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('exams AS EX', 'EX.id = M.exam_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->where('M.school_id', $school_id);
        $this->db->where('M.exam_id', $exam_id);
        $this->db->where('M.class_id', $class_id);
        $this->db->where('E.class_id', $class_id);
        $this->db->where('coalesce(M.subject_id,0)!=0');
        $this->db->where('S.status_type', 'regular');

        
        $this->db->where('E.school_id', $school_id);       
        $this->db->where('E.academic_year_id', $academic_year_id);       

        if($section_id){
            $this->db->where('E.section_id', $section_id);
        } 
        return $this->db->get()->result();        
    }

}
