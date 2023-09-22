<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_employee_list($school_id = null){
        
        $this->db->select('E.*, U.username, U.role_id, D.name AS designtion,U.id as e_user_id');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');      
        $this->db->where('E.status', 1);       
        $this->db->where('E.school_id', $school_id);       
        $this->db->where('E.alumni', '0');

        return $this->db->get()->result();        
    } 
    public function update_employee_attendance($data,$where = null,$day_col){
        if($where['school_id'])
        {
            foreach($where as $coloumn => $value)
            {
                $this->db->where($coloumn, $value);  
            }
           
            $this->db->where("($day_col !='L' or $day_col is null)");       
            return  $this->db->update('employee_attendances',$data);
        }

    } 
    public function get_employee_list_total($school_id = null){
        
        $this->db->select('E.*, U.username, U.role_id, D.name AS designtion,U.id as e_user_id');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');      
        $this->db->where('E.status', 1);       
        $this->db->where('E.school_id', $school_id);       
        $this->db->where('E.alumni', '0');

        $result=$this->db->get()->num_rows();
		
		return $result;
             
    } 
    public function get_single_employee($employee_id ){
        $this->db->select('E.*, U.username, U.role_id,U.id as e_user_id');
        $this->db->from('employees AS E');
        $this->db->join('users AS U', 'U.id = E.user_id', 'left');
        $this->db->where('E.id', $employee_id);
        return $this->db->get()->row();       
    } 
    public function get_holiday_list($school_id = null){
        
        $this->db->select('H.*, S.school_name');
        $this->db->from('holidays AS H');
        $this->db->join('schools AS S', 'S.id = H.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('H.school_id', $this->session->userdata('school_id'));
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('H.school_id', $school_id);
        }
        if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('H.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $this->db->order_by('H.id', 'DESC');
        
        return $this->db->get()->result();
        
    }

}
