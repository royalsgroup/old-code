<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dispatch_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_dispatch($schol_id = null){        
        
        $this->db->select('D.*, S.school_name');
        $this->db->from('postal_dispatches AS D');
        $this->db->join('schools AS S', 'S.id = D.school_id', 'left');
         
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('D.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $schol_id){
            $this->db->where('D.school_id', $schol_id);
        }
        if($this->session->userdata('dadmin')==1){
            $this->db->where_in('D.school_id', $this->session->userdata('dadmin_school_ids'));
        }
        $this->db->where('(coalesce(D.academic_year_id,0)=S.academic_year_id or coalesce(D.academic_year_id,0)=0)');
        $this->db->order_by('D.id', 'DESC');
        
        return $this->db->get()->result();
    } 
    
    public function get_single_dispatch($dispatch_id){        
        
        $this->db->select('D.*, S.school_name');
        $this->db->from('postal_dispatches AS D');
        $this->db->join('schools AS S', 'S.id = D.school_id', 'left');      
        $this->db->where('D.id', $dispatch_id);  
        
        return $this->db->get()->row();
    } 
    public function generate_custom_id($school_id){		
       
            return $this->generate_custom_id_new($school_id);
        
		$this->db->select('D.*,S.school_code');
        $this->db->from('postal_dispatches AS D');
        $this->db->join('schools AS S', 'S.id = D.school_id', 'left');               
		$this->db->where("D.school_id",$school_id);      
        $this->db->where("D.custom_id is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('D.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/",$row->invoice_no);
           
			$invoice_no="DSP".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="DSP".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error");
                    redirect("/frontoffice/dispatch");
                    die();
                    break;
                }
            }

		}
		else{
            $icount = 0;
			$invoice_no="DSP".$row->school_code."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="DSP".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >30)
                {
                    error("Error");
                    redirect("/frontoffice/dispatch");
                    die();
                    break;
                }
            }
		}	
      
		return $invoice_no;
	}
    public function generate_custom_id_new($school_id){		
		$this->db->select('D.*,S.school_code, S.academic_year_id');
        $this->db->from('postal_dispatches AS D');
        $this->db->join('schools AS S', 'S.id = D.school_id', 'left');         
              
        $this->db->where("D.academic_year_id=S.academic_year_id");      
		$this->db->where("D.school_id",$school_id);      
        $this->db->where("D.custom_id is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('D.id', 'desc');
        
        $row= $this->db->get()->row();	

		if(!empty($row) && isset($row->custom_id) && $row->custom_id!= '')
        {
			$arr=explode("/",$row->custom_id);
           
			$invoice_no="DSP".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="DSP".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error hhh");
                    redirect("/frontoffice/dispatch");
                    die("123");
                    break;
                }
            }

		}
		else
        {
            $row = $this->get_single('schools', array('status'=>1, 'id'=>$school_id));
            $icount = 0;
			$invoice_no="DSP".$row->school_code.$row->academic_year_id."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="DSP".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >30)
                {
                    error("Error ddd");
                    redirect("/frontoffice/dispatch");
                    die("456");
                    break;
                }
            }
		}	
      
		return $invoice_no;
	}
    public function check_invoice_no($invoice_no,$school_id ="")
    {
        $this->db->select('D.*');
        $this->db->from('postal_dispatches AS D');
        $this->db->where("D.school_id",$school_id);   
        $this->db->limit(1);
        $this->db->where("D.custom_id",$invoice_no);     
        $row= $this->db->get()->row();
        if(empty($row))
        {
            return true;
        }
        else return false;
    }
}
