<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Teacher_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_teacher_list($school_id = null,$start=null,$limit=null,$search_text=''){
        
        $this->db->select('T.*, S.school_name, U.username, U.role_id');
        $this->db->from('teachers AS T');
        $this->db->join('users AS U', 'U.id = T.user_id', 'left');
        $this->db->join('schools AS S', 'S.id = T.school_id', 'left');
         if($search_text!=''){
			$this->db->like('T.name ', $search_text);
		} 
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('T.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('T.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('T.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
         $this->db->where('T.alumni', '0');
        $this->db->order_by('T.id', 'DESC');
         if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
        return $this->db->get()->result();
        
    }
    public function get_schools(){
        $this->db->select('S.id, S.school_name');
        $this->db->from('schools AS S');
       
        if($this->session->userdata('dadmin') == 1 ){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('S.school_name', 'DESC');
        
        return $this->db->get()->result();
        
    }
	 public function get_teacher_list_total($school_id = null,$search_text =''){
        
        $this->db->select('T.*, S.school_name, U.username, U.role_id');
        $this->db->from('teachers AS T');
        $this->db->join('users AS U', 'U.id = T.user_id', 'left');
        $this->db->join('schools AS S', 'S.id = T.school_id', 'left');
         if($search_text!=''){
			$this->db->like('T.name ', $search_text);
		} 
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('T.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('T.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('T.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
         $this->db->where('T.alumni', '0');
        $this->db->order_by('T.id', 'DESC');
        
        return $this->db->get()->num_rows();
        
    }
	public function get_alumniteacher_list($school_id = null){
        
        $this->db->select('T.*, S.school_name, U.username, U.role_id');
        $this->db->from('teachers AS T');
        $this->db->join('users AS U', 'U.id = T.user_id', 'left');
        $this->db->join('schools AS S', 'S.id = T.school_id', 'left');        
		 $this->db->where('T.alumni','1');
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('T.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('T.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('T.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('T.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_teacher($id){
        
        $this->db->select('T.*, S.school_name, U.username, U.role_id, R.name AS role');
        $this->db->from('teachers AS T');
        $this->db->join('users AS U', 'U.id = T.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        //$this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left');
        $this->db->join('schools AS S', 'S.id = T.school_id', 'left');
        $this->db->where('T.id', $id);
        return $this->db->get()->row();
        
    }
    
        
     function duplicate_check($username, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('username', $username);
        return $this->db->get('users')->num_rows();            
    }
	function duplicate_check_teacher_code($code, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('teacher_code', $code);
        return $this->db->get('teachers')->num_rows();            
    }
    function generate_employee_code($school_id){
		$this->db->select('School.*,Sankul.name as sankul,S.name as state,Z.name as zone,SZ.name as subzone,D.name as district,B.name as block,Sankul.block_id as block_id1');
        $this->db->from('schools AS School'); 
		$this->db->join('sankul AS Sankul', 'Sankul.id = School.sankul_id', 'left');			
		$this->db->join('blocks AS B', 'B.id = Sankul.block_id', 'left');			
		$this->db->join('districts AS D', 'D.id = School.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = School.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = School.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = School.state_id', 'left');   
		$this->db->where('School.id',$school_id);		
        $school=$this->db->get()->row();
       
		$state=strtoupper(substr($school->state,0,2));
		$zone=strtoupper(substr($school->zone,0,2));
		$subzone=strtoupper(substr($school->subzone,0,2));
		$district=strtoupper(substr($school->district,0,2));
		$block=strtoupper(substr($school->block,0,2));
		$sankul=strtoupper(substr($school->sankul,0,2));
		// get last 5 digit of school code and +1
		$this->db->select('E.*');
        $this->db->from('employees AS E'); 
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$employee=$this->db->get()->row();
		//print_r($school); exit;
		if(!empty($employee)){
			$code_no=$employee->id+1;
			$formated_code=sprintf("%03d", $code_no);
           
			$code=$state.$zone.$subzone.$district.$block.$sankul."-EM".$formated_code;
		}
		else{
			$code=$state.$zone.$subzone.$district.$block.$sankul."-EM001";
		}
        if($this->duplicate_check_emp_code($code))
        {
            $this->generate_employee_code($school_id);
        }
        else
        {
            return $code;
        }
	}
	function generate_teacher_code($school_id){
	/*	$this->db->select('School.*,Sankul.name as sankul,S.name as state,Z.name as zone,SZ.name as subzone,D.name as district,B.name as block');
        $this->db->from('schools AS School'); 
		$this->db->join('sankul AS Sankul', 'Sankul.id = School.sankul_id', 'left');			
		$this->db->join('blocks AS B', 'B.id = Sankul.block_id', 'left');			
		$this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');   
		$this->db->where('School.id',$school_id);		
        $school=$this->db->get()->row();
		$state=strtoupper(substr($school->state,0,2));
		$zone=strtoupper(substr($school->zone,0,2));
		$subzone=strtoupper(substr($school->subzone,0,2));
		$district=strtoupper(substr($school->district,0,2));
		$block=strtoupper(substr($school->block,0,2));
		$sankul=strtoupper(substr($school->sankul,0,2));
		// get last 5 digit of school code and +1
		$this->db->select('T.*');
        $this->db->from('teachers AS T'); 
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$teacher=$this->db->get()->row();
		//print_r($school); exit;
		if(!empty($teacher)){
			$code_no=(substr($teacher->teacher_code,strlen($teacher->teacher_code)-3))+1;
			$formated_code=sprintf("%03d", $code_no);
			$code=$state.$zone.$subzone.$district.$block.$sankul."-TE".$formated_code;
		}
		else{
			$code=$state.$zone.$subzone.$district.$block.$sankul."-TE001";
		}
		return $code;*/
		if($school_id!= null){
			$s_id=$school_id;
		}
		else if($this->input->post('school_id')>0){
			$s_id=$this->input->post('school_id');
		}
		if(isset($s_id)){		
			$school=$this->get_single('schools',array('id'=>$s_id));			
			$district=$this->get_single('districts',array('id'=>$school->district_id));		
			$RandomDigit = rand(100000,999999);
            $this->db->select('T.*');
            $this->db->from('teachers AS T'); 
            $this->db->order_by('id','desc');
            $this->db->limit(1);

            $teacher=$this->db->get()->row();
            if(!empty($teacher))
            {
                $code_no=$teacher->id+1;
                $RandomDigit=sprintf("%04d", $code_no);
            }
            else
            {
                $RandomDigit=sprintf("%04d", 1);
            }
			$unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
			// check for duplicate code
			$employee=$this->get_single('teachers',array('teacher_code'=>$unique_code));
			if(!empty($employee)){
				$this->generate_teacher_code($s_id);
			}
			else{
				return $unique_code;
			}
		}
		else {
			return "";
		}
	}


    // ak

    function update_data($action,$field, $id, $table){
        return $this->db->where($field,$id)->update($table,$action);
    }
    
    // ak


}
