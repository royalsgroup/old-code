<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Promotion_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
      
     public function get_student_list($school_id = null, $class_id = null , $academic_year_id = null){
        
        $this->db->select('S.*, E.roll_no');
        $this->db->from('enrollments AS E');        
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);       
        $this->db->where('E.class_id', $class_id);
        $this->db->where('S.school_id', $school_id);        
        $this->db->order_by('E.roll_no', 'ASC');
        $this->db->where('S.status_type', 'regular');
       
        return $this->db->get()->result();        
    }
    public function get_student_discount($student_id){
        
        $this->db->select('D.*');
        $this->db->from('students AS S'); 
        $this->db->join('discounts AS D', 'D.id = S.discount_id', 'left');
        $this->db->where('S.id', $student_id);         
        return $this->db->get()->row();
    }
    public function fee_list($school_id,$income_head_ids){
        
        $this->db->select('*');
        $this->db->from('fees_amount'); 
        $this->db->where_in('income_head_id', $income_head_ids);  
        
        $this->db->where('school_id', $school_id);
        return $this->db->get()->result();
    }
    
    public function check_general_fee($school_id,$financial_year){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where('IH.head_type', "fee");
        $this->db->where('IH.school_id', $school_id);
        $this->db->where('IH.status', 1);
        $this->db->where('IH.financial_year_id', $financial_year);
        return $this->db->get()->row();  
    
        
    }
    public function get_income_heads($school_id,$academic_year_id =null){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where("(IH.head_type ='fee' or IH.head_type ='transport' or IH.head_type ='hostel')");
        $this->db->where('IH.school_id', $school_id);
        $this->db->where('IH.status', 1);
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        return $this->db->get()->result();  
    }
    public function get_discount($discount_id){
        $this->db->select('D.*');
        $this->db->from('discounts AS D'); 
        $this->db->where('D.id', $discount_id);  
        return $this->db->get()->row();
    }
    public function get_single_amount($school_id,$class_id,$income_heads_id){

        $this->db->select('FA.*');
        $this->db->from('fees_amount AS FA'); 
        $this->db->where('FA.income_head_id', $income_heads_id);
        $this->db->where('FA.class_id', $class_id);
        $this->db->where('FA.school_id', $school_id);
        $this->db->where('FA.status', 1);
        return $this->db->get()->row(); 
    }
    public function generate_invoice_no($school_id){		
		$this->db->select('I.*,S.school_code');
        $this->db->from('invoices AS I');
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');               
        $this->db->limit(1);
		$this->db->where("I.school_id",$school_id);      
        $this->db->order_by('I.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/",$row->invoice_no);
			$invoice_no="INV".$row->school_code."/".($arr[1] +1);
		}
		else{
			$invoice_no="INV".$row->school_code."/".'1000001';
		}		
		return $invoice_no;
	}
    public function get_invoice_list($school_id, $student_id, $income_head_ids =null){

        $this->db->select('I.*, SC.school_name,  S.name AS student_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        
        $this->db->where('I.invoice_type !=', 'income');         
        $this->db->where('I.student_id', $student_id);   
        $this->db->where('I.school_id', $school_id);
        if($income_head_ids)
        {
            $this->db->where_in('I.income_head_id', $income_head_ids);

        }
        $this->db->where('SC.status', 1);     
        $this->db->order_by('I.id', 'DESC');  
        return $this->db->get()->result();      
   
}
}
