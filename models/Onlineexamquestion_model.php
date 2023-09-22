<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlineexamquestion_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        //$this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getByExamID($exam_id, $limit, $start,$where_search)
    {
       // get exam_id
	   $this->db->select('onlineexam.*')->from('onlineexam');
	   $this->db->where('id', $exam_id);
	   $query = $this->db->get();
	   $exam= $query->row();
	   
	   //print_r($query);
	   //print_r($exam); exit; 
        $this->db->select('questions.*,subjects.name as subject_name,C.name as class_name, IFNULL(onlineexam_questions.id,0) as `onlineexam_question_id`')->from('questions');
		$this->db->where('questions.school_id',$exam->school_id);
        $this->db->join('subjects', 'subjects.id = questions.subject_id');		
        $this->db->join('classes C', 'subjects.class_id = C.id');		
        $this->db->join('onlineexam_questions', '(onlineexam_questions.question_id = questions.id AND onlineexam_questions.onlineexam_id='.$this->db->escape($exam_id).')','LEFT');

        if(!empty($where_search)){


        $this->db->where($where_search['and_array']);
        }
        $this->db->order_by('questions.id');
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        return $query->result();

    }

    public function getCountByExamID($exam_id,$where_search)
    {
		 // get exam_id
	   $this->db->select('onlineexam.*')->from('onlineexam');
	   $this->db->where('id', $exam_id);
	   $query = $this->db->get();
	   $exam= $query->row();
        $this->db->select('questions.*,subjects.name as subject_name')->from('questions');
		$this->db->where('questions.school_id',$exam->school_id);
        $this->db->join('subjects', 'subjects.id = questions.subject_id');       
 if(!empty($where_search)){
            
     
        $this->db->where($where_search['and_array']);
        }
        $query = $this->db->get();
        return $query->num_rows();

    }

}
