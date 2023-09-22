<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }  
    
    public function get_employee_list($school_id = null,$academic_year_id = null,$start = null,$limit = null,$search_text = null){
        
        $this->db->select('E.*, S.school_name, U.username, U.role_id, D.name AS designation');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.alumni','0');
        if($academic_year_id){
			$this->db->where('S.academic_year_id ', $academic_year_id);
		}  
        if($search_text){
			$this->db->like('E.name ', $search_text);
		}  
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('E.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
        $this->db->order_by('E.id', 'DESC');
        
        return $this->db->get()->result();
        
    } 
    public function get_schools(){
        $this->db->select('S.id, S.school_name');
        $this->db->from('schools AS S');
       
        if($this->session->userdata('dadmin') == 1 ){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('S.school_name', 'ASC');
        
        return $this->db->get()->result();
        
    }
    function generate_employee_code1($school_id){
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
                $unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
                // check for duplicate code
                $employee=$this->get_single('employees',array('employee_code'=>$unique_code));
                if(!empty($employee)){
                    $this->generate_employee_code($s_id);
                }
                else{
                    return $unique_code;
                }
            }
            else {
                return "";
            }
        }
    public function get_employee_total($school_id = null,$academic_year_id = null,$search_text = null){
        
        $this->db->select('E.*, S.school_name, U.username, U.role_id, D.name AS designation');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.alumni','0');
        if($search_text){
			$this->db->like('E.name ', $search_text);
		}  
        if($academic_year_id){
			$this->db->where('S.academic_year_id ', $academic_year_id);
		}  
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('E.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
       
    $result=$this->db->get()->num_rows();
		
    return $result;
        
    }
    public function get_employee_teachers_list($school_id = null,$academic_year_id = null,$start = null,$limit = null,$search_text = null){
        
        $this->db->select('E.id,E.photo,E.phone,E.email, E.name,E.school_id, E.salary_type,E.basic_salary, E.father_name,E.present_address, E.qualification, E.gender, E.joining_date,E.dob,E.is_view_on_web, S.school_name, U.username, U.role_id, D.name AS designation');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.alumni','0');
        if($academic_year_id){
			$this->db->where('S.academic_year_id ', $academic_year_id);
		}  
        if($search_text){
			$this->db->like('E.name ', $search_text);
		}  
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('E.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $employee_query = $this->db->get_compiled_select();
        $this->db->select("T.id,T.photo,T.phone,T.email,T.name,T.school_id, T.salary_type, T.basic_salary, T.father_name,T.present_address, T.qualification, T.gender, T.joining_date, T.dob,T.is_view_on_web, S.school_name, U.username, U.role_id,'Teacher' AS designation");
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
        $teacher_query = $this->db->get_compiled_select();
        //var_dump('select * from (('.$employee_query . ') UNION (' . $teacher_query.')) as t1');
        $this->db->select('T1.*');
        $this->db->from("(($employee_query) UNION ($teacher_query)) as T1");
        $this->db->order_by('T1.name', 'DESC');
        //$final_query = $this->db->query('select * from (('.$employee_query . ') UNION (' . $teacher_query.')) as t1');
        
        if ($limit != null && $start != null) {
		  	$this->db->limit($limit, $start);
          }
        // $this->db->order_by('E.id', 'DESC');
        //echo $final_query;
       
        return $this->db->get()->result();
        
    }
    public function get_employee_teachers_list_total($school_id = null,$academic_year_id = null,$search_text = null){
        
        $this->db->select('E.id,E.photo,E.phone,E.email, E.name, E.school_id, E.salary_type,E.basic_salary, E.father_name,E.present_address, E.qualification, E.gender, E.joining_date, E.is_view_on_web, S.school_name, U.username, U.role_id, D.name AS designation');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.alumni','0');
        if($academic_year_id){
			$this->db->where('S.academic_year_id ', $academic_year_id);
		}  
        if($search_text){
			$this->db->like('E.name ', $search_text);
		}  
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('E.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $employee_query = $this->db->get_compiled_select();
        $this->db->select("T.id,T.photo,T.phone,T.email,T.name,T.school_id, T.salary_type, T.basic_salary, T.father_name,T.present_address, T.qualification, T.gender, T.joining_date, T.is_view_on_web, S.school_name, U.username, U.role_id,'Teacher' AS designation");
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
        $teacher_query = $this->db->get_compiled_select();
        
        $final_query = $this->db->query('select * from (('.$employee_query . ') UNION (' . $teacher_query.')) as t1');
       
         $result=$final_query->num_rows();
		
         return $result;
             
        
    }
	public function get_alumniemployee_list($school_id = null){
        
        $this->db->select('E.*, S.school_name, U.username, U.role_id, D.name AS designation');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');
		//$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        $this->db->where('E.alumni','1');
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('E.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('E.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('E.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_employee($id){
        
        $this->db->select('E.*, S.school_name, U.username, U.role_id, R.name AS role, D.name AS designation');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');
        $this->db->join('schools AS S', 'S.id = E.school_id', 'left');
        //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left');
        $this->db->where('E.id', $id);
        return $this->db->get()->row();
        
    }
	
    
     function duplicate_check($username, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('username', $username);
        return $this->db->get('users')->num_rows();            
    }
	function duplicate_check_emp_code($code, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('employee_code', $code);
        return $this->db->get('employees')->num_rows();            
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
}
