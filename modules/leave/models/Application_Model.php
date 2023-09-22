<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Application_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_application_list($school_id = null, $status = 0,$limit = null,$start = null, $search_text =null){
         
        $this->db->select('A.*, T.type, T.total_leave, R.name AS role_name, S.school_name, AY.session_year');
        $this->db->from('leave_applications AS A');
        $this->db->join('leave_types AS T', 'T.id = A.type_id', 'left'); 
        $this->db->join('roles AS R', 'R.id = A.role_id', 'left');
        $this->db->join('schools AS S', 'S.id = A.school_id', 'left'); 
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->where('A.leave_status', $status);
                
        if($school_id){
            $this->db->group_start();

             $this->db->where('A.school_id', $school_id);
              $this->db->or_where('A.school_id','0'); 
              $this->db->group_end();
        }      
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->group_start();
            $this->db->where('A.school_id', $this->session->userdata('school_id'));
            $this->db->or_where('A.school_id','0'); 
            $this->db->group_end();
        }
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
        $this->db->order_by('A.id', 'DESC'); 
        
        return $this->db->get()->result();
        
    }

    public function get_application_list_total($school_id = null, $status = 0, $search_text =null){
         
        $this->db->select('A.*, T.type, T.total_leave, R.name AS role_name, S.school_name, AY.session_year');
        $this->db->from('leave_applications AS A');
        $this->db->join('leave_types AS T', 'T.id = A.type_id', 'left'); 
        $this->db->join('roles AS R', 'R.id = A.role_id', 'left');
        $this->db->join('schools AS S', 'S.id = A.school_id', 'left'); 
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->where('A.leave_status', $status);
                
        if($school_id){
            $this->db->group_start();
             $this->db->where('A.school_id', $school_id);
              $this->db->or_where('A.school_id','0'); 
              $this->db->group_end();
        }      
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->group_start();
            $this->db->where('A.school_id', $this->session->userdata('school_id'));
            $this->db->or_where('A.school_id','0'); 
            $this->db->group_end();
        }
        
       
        $result=$this->db->get()->num_rows();
		
		return $result;
        
    }
    public function get_reporting_users($school_id,$teacher_id=null)
    {   
        $this->db->select("U.*");
        $this->db->from('users AS U');
        if($teacher_id)
        {
            $this->db->join('teachers AS T', 'T.user_id = U.id', 'left'); 
            if($teacher_id)
            {
                $this->db->where('T.id', $teacher_id);
            }
        }
        else
        {
            $this->db->where('(U.role_id='.ADMIN.' OR U.role_id='.SUPER_ADMIN.')');
        }
        if($school_id){
            $this->db->where('U.school_id', $school_id);
        }   
        return $this->db->get()->result();
    } 
    public function get_admins($school_id)
    {   
        $this->db->select('U.*');
        $this->db->from('district_admin AS DA');
        $this->db->join('users AS U', 'U.id = DA.user_id', 'left');
		$this->db->join('schools AS SC', '( 
            (SC.sankul_id=DA.sankul_id) or 
            (DA.sankul_id =0 and SC.block_id=DA.block_id)  or
            (DA.sankul_id =0 and DA.block_id=0 and SC.district_id=DA.district_id)  or
            (DA.sankul_id =0 and DA.block_id=0 and DA.district_id=0 and  SC.subzone_id=DA.subzone_id)  or
            (DA.sankul_id =0 and DA.block_id=0 and DA.district_id=0 and  DA.subzone_id=0 and  SC.zone_id=DA.zone_id )  or
            (DA.sankul_id =0 and DA.block_id=0 and DA.district_id=0 and  DA.subzone_id=0 and  DA.zone_id=0 and SC.state_id=DA.state_id ) 
        )', 'left');
      
        $this->db->where('SC.id', $school_id);
        $result =  $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $result->result();
        
      
    } 
    public function get_leaves_between($school_id, $user_id, $role_id, $leave_from, $leave_to){
        $this->db->select('A.*');
        $this->db->from('leave_applications AS A');
        $this->db->where('A.user_id', $user_id);
        $this->db->where('A.role_id', $role_id);
        $this->db->where('A.school_id', $school_id);
        $this->db->where("(
                            (A.leave_from <= '$leave_from' and A.leave_to>= '$leave_from')
                            or
                            (A.leave_from >= '$leave_to' and A.leave_to<= '$leave_to')
                        ) ");
        return $this->db->get()->row();     
    }

    public function get_single_application($id){
        
        $this->db->select('A.*, T.type, T.total_leave,  R.name AS role_name, S.school_name, AY.session_year');
        $this->db->from('leave_applications AS A');
        $this->db->join('leave_types AS T', 'T.id = A.type_id', 'left'); 
        $this->db->join('roles AS R', 'R.id = A.role_id', 'left');
        $this->db->join('schools AS S', 'S.id = A.school_id', 'left'); 
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->where('A.id', $id);
        return $this->db->get()->row();        
    } 
       public function get_class_by_session($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
        $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }

    public function get_by_a($table,$status,$school_id){
        $this->db->select('*');
        $this->db->from($table);        
        $this->db->where('status',$status);      
        $this->db->where('school_id', $school_id);   
       $this->db->or_where('school_id','0');    
        return $this->db->get()->result(); 
    }
}
