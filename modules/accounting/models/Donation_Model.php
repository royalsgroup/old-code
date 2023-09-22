<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Donation_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    public function get_donation_list_total($school_id = null, $academic_year_id = null,$search_text='',$start_date = "", $end_date = "", $user_id = ""){
        
        $this->db->select('D.*');
        $this->db->from('donations AS D');        
        if($user_id)
        {
            $this->db->where('D.donor_id', $user_id);
        }
        if($academic_year_id){
			$this->db->where('D.academic_year_id', $academic_year_id);
		} 
        if($search_text){
            $this->db->group_start();
			$this->db->like('D.donor_name', $search_text);
            $this->db->or_like('D.donor_address', $search_text);
            $this->db->or_like('D.adhar_no', $search_text);
            $this->db->or_like('D.remark', $search_text);
            $this->db->or_like('D.donor_phone', $search_text);
            $this->db->or_like('D.reciept_no', $search_text);
            $this->db->or_like('D.father_name', $search_text);
            $this->db->group_end();
		}    
       
        if($start_date)
        {
            $this->db->where("D.date>'$start_date'");
        }
        if($end_date)
        {
            $this->db->where("D.date<='$end_date'");
        }
        
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('D.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('D.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('D.school_id', $this->session->userdata('dadmin_school_ids'));
		}
        if($school_id)
        {
            $this->db->where('D.school_id', $school_id);
        }  
       
       // $this->db->group_by('I.student_id');    
       $result=$this->db->get();

       return $result->num_rows();
        // print_r($this->db->last_query());exit       
    }
    public function get_donation_list($school_id = null,$academic_year_id = null,$start = null, $limit = null,$search_text='',$sort_cloumn=null,$sort_order =null,$start_date = "", $end_date = "", $user_id = ""){
        $this->db->select("D.*
        ,(select concat(name,'[', category,']')  from account_ledgers where id=credit_ledger_id) as credit_ledger
        ,(select concat(name,'[', category,']')  from account_ledgers where id=debit_ledger_id) as debit_ledger
        ,(select concat(name,'[', category,']')  from vouchers where id=voucher_id) as voucher
        ");
        $this->db->from('donations AS D');        
        if($user_id)
        {
            $this->db->where('D.donor_id', $user_id);
        }
        
        if($academic_year_id){
			$this->db->where('D.academic_year_id', $academic_year_id);
		} 
       
        if($search_text){
            $this->db->group_start();
			$this->db->like('D.donor_name', $search_text);
            $this->db->or_like('D.donor_address', $search_text);
            $this->db->or_like('D.adhar_no', $search_text);
            $this->db->or_like('D.remark', $search_text);
            $this->db->or_like('D.donor_phone', $search_text);
            $this->db->or_like('D.reciept_no', $search_text);
            $this->db->or_like('D.father_name', $search_text);
            $this->db->group_end();
		}    
        if($start_date)
        {
            $this->db->where("D.date>'$start_date'");
        }
        if($end_date)
        {
            $this->db->where("D.date<='$end_date'");
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('D.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('D.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('D.school_id', $this->session->userdata('dadmin_school_ids'));
		}
        if($school_id)
        {
            $this->db->where('D.school_id', $school_id);
        }  
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
        }
        if($sort_cloumn)
        {
            $sort_order  = $sort_order ? $sort_order : "DESC";
            if($sort_cloumn == "D.date")
            {
                $this->db->order_by("D.date",$sort_order );

            }
            else
            {
                $this->db->order_by($sort_cloumn, $sort_order );  
            }
        }
        
        $this->db->order_by("D.date", 'DESC');
   
       $result=$this->db->get();	

		return $result->result();
         
    }
    public function generate_reciept_no($school_id){		
		$this->db->select('D.*,S.school_code');
        $this->db->from('donations AS D');
        $this->db->join('schools AS S', 'S.id = D.school_id', 'left');               
		$this->db->where("D.school_id",$school_id);      
        $this->db->where("D.reciept_no is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('D.id', 'desc');

        $row= $this->db->get()->row();	
        $school = $this->donation->get_school_by_id($school_id);

		if(!empty($row) && isset($row->reciept_no) && $row->reciept_no!= ''){
			$arr=explode("/",$row->reciept_no);
           
			$reciept_no="DN".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_reciept_no($reciept_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$reciept_no);
			    $reciept_no="DN".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_reciept_no($reciept_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error");
                    redirect("/accounting/invoice");
                    die();
                    break;
                }
            }

		}
		else{
            $icount = 0;
			$reciept_no="DN".$school->school_code."/".'1000001';
            $check_invoice = $this->check_reciept_no($reciept_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$reciept_no);
			    $reciept_no="DN".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_reciept_no($reciept_no,$school_id);
                $icount++;
                if($icount >30)
                {
                    error("Error");
                    redirect("/accounting/invoice");
                    die();
                    break;
                }
            }
		}	
      
		return $reciept_no;
	}
    
    public function get_donation($id,$school_id ="")
    {
        $this->db->select('D.*,AT.transaction_no,AT.cheque_no,, AT.bank_name
        ,(select name from account_ledgers where id=credit_ledger_id) as credit_ledger
        ,(select name from account_ledgers where id=debit_ledger_id) as debit_ledger');
        $this->db->from('donations AS D');
        $this->db->join('account_transactions AS AT', 'AT.id = D.account_transaction_id', 'left');
        if($school_id) {
            $this->db->where("D.school_id",$school_id);   
        }
        $this->db->limit(1);
        $this->db->where("D.id",$id);     
        $row= $this->db->get()->row();
       return $row;
    }
    
    public function check_reciept_no($reciept_no,$school_id ="")
    {
        $this->db->select('D.*');
        $this->db->from('donations AS D');
        $this->db->where("D.school_id",$school_id);   
        $this->db->limit(1);
        $this->db->where("D.reciept_no",$reciept_no);     
        $row= $this->db->get()->row();
        if(empty($row))
        {
            return true;
        }
        else return false;
    }
}