<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classes_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_class_list($school_id = null,$discipline_id = ""){
        // error_on();
		if($this->session->userdata('role_id') == TEACHER){
			$teacher = get_user_by_id($this->session->userdata('id'));
        }
        $this->db->select('C.*, S.school_name, T.name AS teacher,IFNULL(D.name,"") as `discipline_name`');
        $this->db->from('classes AS C');
        $this->db->join('teachers AS T', 'T.id = C.teacher_id', 'left');
        $this->db->join('schools AS S', 'S.id = C.school_id', 'left');
		$this->db->join('academic_disciplines AS D', 'C.disciplines = D.id', 'left');
        if ($this->session->userdata('default_data') ==1)
		{
			$this->db->or_where('C.school_id','0'); 
		}
		else
		{
			if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
				$this->db->where('C.school_id', $this->session->userdata('school_id'));
				
	
			}
			if($discipline_id)
			{
				$this->db->where('C.disciplines', $discipline_id);
			}
			if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!=null){
				$this->db->where('C.school_id', $school_id);
				//$this->db->or_where('C.school_id','0'); 
			}
			
			if($this->session->userdata('role_id') == TEACHER){
				$this->db->where('C.teacher_id',  $teacher->id);
			}
			if($this->session->userdata('dadmin') == 1 && $school_id!=null){
				$this->db->where('C.school_id', $school_id);
				//$this->db->or_where('C.school_id','0'); 
			}
		}
        if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where('S.district_id', $this->session->userdata('district_id'));
		}
		$this->db->order_by('numeric_name', 'ASC');
		$this->db->order_by('name', 'ASC');

        $result = $this->db->get();
		// echo $this->db->last_query();
		// die();
		return $result->result();
        
    }
    
	function check_numeric($school_id, $numeric_num, $discipline_id,$id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
		if ($discipline_id)
		{
			$this->db->where('disciplines', $discipline_id);
		}
        $this->db->where('numeric_name', $numeric_num);
        $this->db->where('school_id', $school_id);
        return $this->db->get('classes')->num_rows();            
    }
    
    function duplicate_check($school_id, $name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
        return $this->db->get('classes')->num_rows();            
    }
	function get_class_id($school_id, $name){
		$this->db->select('C.id');
        $this->db->from('classes AS C');
		//$this->db->where('disciplines', $discipline_id);		
		$this->db->where('LOWER(name)', strtolower($name));
		$this->db->where_in('school_id',array($school_id));
        //$this->db->where('school_id', $school_id);
		//$this->db->or_where('school_id',0);
		$query = $this->db->get();
		return $query->row();
		
	}
	 public function get_single_class($id){
        
        $this->db->select('C.*, SC.school_name, T.name AS teacher,A.level,A.stream,A.board');
        $this->db->from('classes AS C');
        $this->db->join('teachers AS T', 'T.id = C.teacher_id', 'left');        
        $this->db->join('schools AS SC', 'SC.id = C.school_id', 'left');
		$this->db->join('academic_standards AS A', 'A.id = C.academic_standard_id', 'left');
        $this->db->where('C.id', $id);
        return $this->db->get()->row();
        
    }
	
	
	public function get_list_by_school($school_id){
		 $this->db->select('C.*');
        $this->db->from('classes AS C');
		if($school_id)
		{
			$this->db->where('C.school_id', $school_id);
		}
		else
		{
			$this->db->where('C.school_id', 0);

		}
		 return $this->db->get()->result();
	}
	public function insert_default($school_id){
		$this->db->select('C.*,');
        $this->db->from('classes AS C');
		$this->db->where('C.school_id', 0); 			
		$classes=$this->db->get()->result(); 	
		foreach($classes as $l){
			$larr=array();
			$larr['school_id']=$school_id;			
			$larr['name']=$l->name;
			$larr['numeric_name']=$l->numeric_name;
			$larr['note']=$l->note;
			$larr['status']=$l->status;
			$larr['disciplines']=$l->disciplines;
						
			$larr['created_at']= date('Y-m-d H:i:s');
			$larr['modified_at']= date('Y-m-d H:i:s');
			$data['created_by'] = logged_in_user_id();
			$this->db->insert('classes',$larr);
			$class_id=$this->db->insert_id();
			// create default section for each class
			 $data = array();
			$data['school_id']  = $school_id;
			$data['class_id']    = $class_id;		
			$data['name']       = 'A';
			$data['note']       = 'Default Section';
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['created_by'] = logged_in_user_id();
			$data['status']     = 1; 
			$section_id=$this->db->insert('sections', $data);
			
		}
	}
}
