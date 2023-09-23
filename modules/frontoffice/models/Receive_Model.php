<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receive_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_receive($schol_id = null){        
        
        $this->db->select('R.*, S.school_name');
        $this->db->from('postal_receives AS R');
        $this->db->join('schools AS S', 'S.id = R.school_id', 'left');
         
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('R.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $schol_id){
            $this->db->where('R.school_id', $schol_id);
        }
        else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('R.school_id', $this->session->userdata('dadmin_school_ids'));
		}	
        $this->db->where('(coalesce(R.academic_year_id,0)=S.academic_year_id or coalesce(R.academic_year_id,0)=0)');

        $this->db->order_by('R.id', 'DESC');
        
        return $this->db->get()->result();
    } 
    
    public function get_single_receive($receive_id){        
        
        $this->db->select('R.*, S.school_name');
        $this->db->from('postal_receives AS R');
        $this->db->join('schools AS S', 'S.id = R.school_id', 'left');      
        $this->db->where('R.id', $receive_id);  
        
        return $this->db->get()->row();
    } 
    public function generate_custom_id($school_id){		
       
            return $this->generate_custom_id_new($school_id);
        

		$this->db->select('R.*,S.school_code');
        $this->db->from('postal_receives AS R');
        $this->db->join('schools AS S', 'S.id = R.school_id', 'left');               
		$this->db->where("R.school_id",$school_id);      
        $this->db->where("R.custom_id is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('R.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/",$row->invoice_no);
           
			$invoice_no="RSV".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="RSV".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
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
			$invoice_no="RSV".$row->school_code."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="RSV".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
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
		$this->db->select('R.*,S.school_code');
        $this->db->from('postal_receives AS R');
        $this->db->join('schools AS S', 'S.id = R.school_id', 'left');    
        $this->db->where("R.academic_year_id=S.academic_year_id");                 
		$this->db->where("R.school_id",$school_id);      
        $this->db->where("R.custom_id is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('R.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->custom_id) && $row->custom_id!= ''){
			$arr=explode("/",$row->custom_id);
           
			$invoice_no="RSV".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="RSV".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
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
            $row = $this->get_single('schools', array('status'=>1, 'id'=>$school_id));

			$invoice_no="RSV".$row->school_code.$row->academic_year_id."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="RSV".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
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
    public function check_invoice_no($invoice_no,$school_id ="")
    {
        $this->db->select('R.*');
        $this->db->from('postal_receives AS R');
        $this->db->where("R.school_id",$school_id);   
        $this->db->limit(1);
        $this->db->where("R.custom_id",$invoice_no);     
        $row= $this->db->get()->row();
        if(empty($row))
        {
            return true;
        }
        else return false;
    }
}
