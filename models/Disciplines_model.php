<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Disciplines_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('payment_modes')->num_rows();            
    }
	public function get_discipline_list($school_id = null){        
        $this->db->select('D.*, S.school_name');
        $this->db->from('academic_disciplines AS D');       		
        $this->db->join('schools AS S', 'S.id = D.school_id', 'left');		
        if ($this->session->userdata('default_data') ==1)
		{
			$this->db->or_where('D.school_id','0'); 
		}
		else
		{
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
                $this->db->where('D.school_id', $this->session->userdata('school_id')); 
            }
            
            if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
                $this->db->where('D.school_id', $school_id); 
            }
            if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
                $this->db->where('D.school_id', $school_id); 
            }
            else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
                $this->db->where('S.district_id', $this->session->userdata('district_id'));
            }
            else if($this->session->userdata('dadmin') == 1 && $school_id==null){
                $this->db->where_in('D.school_id', $this->session->userdata('dadmin_school_ids'));
            }	
        }
        $this->db->order_by('id', 'DESC');

        return $this->db->get()->result();
        
    }

    public function insert($data,$table){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

     function delete($id,$table){   
        $this->db->where('id', $id);
       return $this->db->delete($table);

    }
	
     function edit($id,$table){ 

        $this->db->select('*');

        $this->db->from($table);

        $this->db->where('id', $id);
        
       return $this->db->get()->result();
    }
	public function get_discipline_by_name($school_id,$name){
		$this->db->select('D.*');
        $this->db->from('academic_disciplines AS D');
		$this->db->where('LOWER(name)', strtolower($name));
        $this->db->where('school_id', $school_id);
		$this->db->or_where('school_id',0);
		$query = $this->db->get();
		return $query->row();
	}
    public function generate_custom_id($school_id){		
		$this->db->select('D.*,S.school_code');
        $this->db->from('academic_disciplines AS D');
        $this->db->join('schools AS S', 'S.id = D.school_id', 'left');               
		$this->db->where("D.school_id",$school_id);      
        $this->db->where("D.custom_id is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('R.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/",$row->invoice_no);
           
			$invoice_no="DIS".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="DIS".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
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
			$invoice_no="DIS".$row->school_code."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="DIS".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
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
        $this->db->select('D.*');
        $this->db->from('academic_disciplines AS D');
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
