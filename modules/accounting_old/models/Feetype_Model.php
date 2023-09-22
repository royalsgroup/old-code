<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feetype_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
     
    public function get_fee_type($school_id = null){
        
        $this->db->select('IH.*, S.school_name');
        $this->db->from('income_heads AS IH'); 
        $this->db->join('schools AS S', 'S.id = IH.school_id', 'left');
        $this->db->where('IH.head_type !=', 'income'); 
        //$this->db->or_where('IH.head_type', 'hostel'); 
        //$this->db->or_where('IH.head_type', 'transport'); 
       
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('IH.school_id', $this->session->userdata('school_id'));
        } 
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('IH.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('IH.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('IH.id', 'DESC');
        
        return $this->db->get()->result();  
    }
    
    public function get_single_feetype($feetype_id){
        
        $this->db->select('IH.*, S.school_name,IFNULL(AL1.name,"") as `credit_ledger_name`,IFNULL(AL2.name,"") as `refund_ledger_name`,IFNULL(V.name,"") as `voucher_name`');
        $this->db->from('income_heads AS IH'); 
        $this->db->join('schools AS S', 'S.id = IH.school_id', 'left');        
		$this->db->join('account_ledgers AS AL1', 'AL1.id = IH.credit_ledger_id', 'left');        
		$this->db->join('account_ledgers AS AL2', 'AL2.id = IH.refund_ledger_id', 'left');        
		$this->db->join('vouchers AS V', 'V.id = IH.voucher_id', 'left');        
        $this->db->where('IH.id', $feetype_id);
        return $this->db->get()->row();  
    }
    
            
    function duplicate_check($school_id, $title, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('school_id', $school_id);
        $this->db->where('title', $title);
        return $this->db->get('income_heads')->num_rows();            
    }

}
