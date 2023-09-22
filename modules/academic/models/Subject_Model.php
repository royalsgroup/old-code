<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subject_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_subject_list($class_id = null , $school_id = null){
       
        if($class_id== null){
           $class_id = $this->session->userdata('class_id');
        }
        
        $this->db->select('S.*, SC.school_name, C.name AS class_name, T.name AS teacher');
        $this->db->from('subjects AS S');
        $this->db->join('teachers AS T', 'T.id = S.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->group_start();
            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
            $this->db->or_where('C.teacher_id', $this->session->userdata('profile_id'));
            $this->db->group_end();

        }
        
        if($class_id > 0){

            $this->db->where("C.id=$class_id ");
        }
        $this->db->group_start();
        if ($this->session->userdata('default_data') ==1)
        {
            $this->db->where('S.school_id', '0');
        }
        else
        {
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
                $this->db->where('S.school_id', $this->session->userdata('school_id'));
                // $this->db->where('S.school_id', 0);
                //$this->db->or_where('S.school_id','0');
            }
            if($school_id!=null && $this->session->userdata('role_id') == SUPER_ADMIN){
                $this->db->where('S.school_id', $school_id); 
                // $this->db->or_where('S.school_id', 0);
                //$this->db->or_where('S.school_id','0');
            } 
            if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id!= null){
                $this->db->where('S.school_id', $school_id);
                // $this->db->or_where('S.school_id', 0);

                //$this->db->or_where('S.school_id','0');
            }
        }
            if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
                $this->db->where('SC.district_id', $this->session->userdata('district_id'));
            }			
        $this->db->group_end();

        $this->db->order_by('S.id', 'DESC');
        
        $result =  $this->db->get();
        return $result->result();
        
    }
    
    public function get_single_subject($id){
        
        $this->db->select('S.*, SC.school_name, C.name AS class_name, T.name AS teacher');
        $this->db->from('subjects AS S');
        $this->db->join('teachers AS T', 'T.id = S.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        $this->db->where('S.id', $id);
        return $this->db->get()->row();
        
    }
    
    function duplicate_check($school_id, $class_id, $name, $id = null ){   
        
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('class_id', $class_id);
        $this->db->where('name', $name);
        $this->db->where('school_id', $school_id);
        return $this->db->get('subjects')->num_rows();        
    } 
	public function get($id = null) {

        $subject_condition=0;
        $userdata = $this->session->userdata();;       
        $role_id = $userdata["role_id"];
       
       
      /* if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
          if($userdata["class_teacher"] == 'yes') {

           
               
               $my_classes=$this->teacher_model->my_classes($userdata['id']);
             
           
             if(!empty($my_classes)){
                    $subject_condition=0;
              }else{
                $subject_condition=1;
               $my_subjects=$this->teacher_model->get_examsubjects($userdata['id']);
               
               


  
                }
             }
           } */
        $this->db->select()->from('subjects');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            if($subject_condition==1){
             $this->db->where_in('subjects.id',$my_subjects);
        }
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }	

         public function get_class_by_session($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
        $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }
	public function get_list_by_school($school_id){
		 $this->db->select('S.*');
        $this->db->from('subjects AS S');
		$this->db->where('S.school_id', $school_id);
		$this->db->or_where('S.school_id', 0);
		 return $this->db->get()->result();
	}
	public function insert_default($school_id){
		$this->db->select('S.*,');
        $this->db->from('subjects AS S');
		$this->db->where('S.school_id', 0); 			
		$subjects=$this->db->get()->result(); 	
		foreach($subjects as $l){
			$larr=array();
			$larr['school_id']=$school_id;			
			$larr['type']=$l->type;
			$larr['name']=$l->name;
			$larr['code']=$l->code;
			$larr['author']=$l->author;
			$larr['note']=$l->note;
			$larr['status']=$l->status;
			$larr['disciplines']=$l->disciplines;
			// get class id
			$class=$this->get_single('classes',array("id"=>$l->class_id));
			if(!empty($class)){
				$class_new=$this->get_single('classes',array("name"=>$class->name,'school_id'=>$school_id));
				if(!empty($class_new)){
					$larr['class_id']=$class_new->id;
				}
			}	
			$larr['created_at']= date('Y-m-d H:i:s');
			$larr['modified_at']= date('Y-m-d H:i:s');
			$data['created_by'] = logged_in_user_id();
			$this->db->insert('subjects',$larr);
			$subject_id=$this->db->insert_id();
						
		}
	}
	public function tmp($school_id){
		$this->db->select('C.*,');
        $this->db->from('classes AS C');
		$this->db->where('C.school_id', 0); 			
		$subjects=$this->db->get()->result(); 	
		foreach($subjects as $l){
			// default sextion
			// create default section for each class
			 $data = array();
			$data['school_id']  = $school_id;
			$data['class_id']    = $l->id;		
			$data['name']       = 'A';
			$data['note']       = 'Default Section';
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['created_by'] = logged_in_user_id();
			$data['status']     = 1; 
			$section_id=$this->db->insert('sections', $data);
		}
	}
}
