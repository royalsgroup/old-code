<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Complain_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_complain_list($school_id = null){
         
        $this->db->select('C.*, T.type,  R.name AS role_name, S.school_name, AY.session_year');
        $this->db->from('complains AS C');
        $this->db->join('complain_types AS T', 'T.id = C.type_id', 'left'); 
        $this->db->join('roles AS R', 'R.id = C.role_id', 'left');
        $this->db->join('schools AS S', 'S.id = C.school_id', 'left'); 
        $this->db->join('academic_years AS AY', 'AY.id = C.academic_year_id', 'left');
         
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('C.school_id', $school_id);
        }       
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('C.school_id', $this->session->userdata('school_id'));
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('C.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('C.id','DESC');
        return $this->db->get()->result();
        
    }
    
    public function get_single_complain($id){
        
        $this->db->select('C.*, T.type,  R.name AS role_name, S.school_name, AY.session_year');
        $this->db->from('complains AS C');
        $this->db->join('complain_types AS T', 'T.id = C.type_id', 'left'); 
        $this->db->join('roles AS R', 'R.id = C.role_id', 'left');
        $this->db->join('schools AS S', 'S.id = C.school_id', 'left'); 
        $this->db->join('academic_years AS AY', 'AY.id = C.academic_year_id', 'left');
        $this->db->where('C.id', $id);
        return $this->db->get()->row();        
    } 
    public function get_reporting_users($school_id =null,$teacher_id=null,$role_id =null)
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
        if($role_id)
        {
            $this->db->where(' U.role_id=',$role_id);
        }
        if($school_id){
            $this->db->where('U.school_id', $school_id);
        }   
        return $this->db->get()->result();
    } 
	public function generate_complain_no($school_id){
		$this->db->select('C.*');
		 $this->db->from('complains AS C');		 
		 $this->db->where('C.school_id', $school_id);
		 $this->db->limit(1);
		 $this->db->order_by('id','desc');
		 $row= $this->db->get()->row();
		 if(!empty($row)){			
			$arr=explode("-",$row->complain_no);
			$no=sprintf('%04d', $arr[1]+1);
		 }
		 else{
			 $no="0001";
		 }
		 $complain_no=$school_id."-".$no;
		 return $complain_no;
	}
}
