<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Voucher_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('account_ledgers')->num_rows();            
    }
	public function get_voucher_list($school_id = null,$category= null){        
        $this->db->select('V.*, S.school_name, VT.name as type_name');
        $this->db->from('vouchers AS V');       		
        $this->db->join('schools AS S', 'S.id = V.school_id', 'left');		
		$this->db->join('voucher_types AS VT', 'VT.id = V.type_id', 'left');
		//$this->db->join('account_transactions AS AT', 'AT.voucher_id = V.id', 'left');
		if($category!= null){
            $this->db->where('V.category', $category);
        }		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('V.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!=null){
            $this->db->where('V.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id != null){
            $this->db->where('V.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $result=$this->db->get()->result();
		return $result;
		
        
    }
	public function get_voucher_list_ajax($school_id = null,$category= null,$start = null, $limit = null,$search_text='',$search_cols=null,$sort_cloumn =null,$sort_order =null){        
        $this->db->select('V.*, S.school_name, VT.name as type_name
		,(select date from account_transactions where voucher_id=V.id and cancelled=0 order by date desc limit 1 ) as last_entry_date

		');
        $this->db->from('vouchers AS V');       		
        $this->db->join('schools AS S', 'S.id = V.school_id', 'left');		
		$this->db->join('voucher_types AS VT', 'VT.id = V.type_id', 'left');
		//$this->db->join('account_transactions AS AT', 'AT.voucher_id = V.id', 'left');
		if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
			$admin_val = 1;
		}
		else
		{
			$admin_val = 0;
		}
		if($category!= null){
            $this->db->where('V.category', $category);
        }		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('V.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!=null){
            $this->db->where('V.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id != null){
            $this->db->where('V.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
		if($search_text){
			
			$this->db->group_start();
			$this->db->like('V.name', $search_text);
			$this->db->or_where('VT.name', $search_text);
			$this->db->group_end();
		}    
		if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
	if($search_cols)
	{
		//$coloumns = array_column($search_cols,"value");
		$this->db->group_start();
	
		$icount = 0;
		foreach($search_cols as $coloumn_id => $search_col)
		{
			$search_value = $search_col['search']['value'];

			if( $search_value && $search_value != "false")
			{
				switch( $coloumn_id)
				{
					case -1+$admin_val: 
						$icount++;
						$this->db->like('S.name', $search_value,'both',false);
					case 0+$admin_val: 
						$icount++;
						$this->db->like('V.category', $search_value,'both',false);
					break;
					case 1+$admin_val: 
						$icount++;
						$this->db->like('V.name', $search_value,'both',false);
					break;
					case 2+$admin_val: 
						$icount++;
						$this->db->like('VT.name', $search_value,'both',false);
					break;
				
				}
			}
			
		}
		if(!$icount)
		{
			$this->db->where("1=1");
		}
		$this->db->group_end();
	}
            
    if($sort_cloumn)
    {
        $sort_order  = $sort_order ? $sort_order : "DESC";
        $this->db->order_by($sort_cloumn, $sort_order );  
    }
        $result=$this->db->get();
		//echo $this->db->last_query();
		return $result->result();
		
        
    }
	public function get_voucher_list_total($school_id = null,$category= null){        
        $this->db->select('V.*');
        $this->db->from('vouchers AS V');       		
		//$this->db->join('account_transactions AS AT', 'AT.voucher_id = V.id', 'left');
		if($category!= null){
            $this->db->where('V.category', $category);
        }		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('V.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!=null){
            $this->db->where('V.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id != null){
            $this->db->where('V.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $result=$this->db->get()->num_rows();
		return $result;
		
        
    }
	public function get_voucher_list_tr_count($school_id = null,$category= null, $f_start, $f_end){        
        $this->db->select("V.*, S.school_name, VT.name as type_name
		,(select name from voucher_types where id=V.type_id ) as type_name
		,(select count(*) from account_transactions where voucher_id=V.id and date >= $f_start and date <=  $f_end) as no_of_entries
		,(select date from account_transactions where voucher_id=V.id and cancelled=0 order by date desc limit 1 ) as last_entry_date
		");
        $this->db->from('vouchers AS V');       		
        $this->db->join('schools AS S', 'S.id = V.school_id', 'left');		
		$this->db->join('voucher_types AS VT', 'VT.id = V.type_id', 'left');
		//$this->db->join('account_transactions AS AT', 'AT.voucher_id = V.id', 'left');
		if($category!= null){
            $this->db->where('V.category', $category);
        }		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('V.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!=null){
            $this->db->where('V.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id != null){
            $this->db->where('V.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        $result=$this->db->get();
		//echo $this->db->last_query();
		return $result->result();
		
        
    }
	
	public function get_voucher_by_id($id =null){
		if($id !=null){
			$this->db->select('V.*, S.school_name, VT.name as type_name, AY.session_year');
			$this->db->from('vouchers AS V');       		
			$this->db->join('schools AS S', 'S.id = V.school_id', 'left');		
			$this->db->join('voucher_types AS VT', 'VT.id = V.type_id', 'left');
			$this->db->join('financial_years AS AY', 'AY.id = V.financial_year_id', 'left');
			$this->db->where('V.id', $id); 
			return $this->db->get()->row();     
		}
	}
	public function get_voucher_total_amount($voucher_id =null){
		$this->db->select('SUM(ATD.amount) as total_amount');
        $this->db->from('account_transaction_details AS ATD');    
		$this->db->join('account_transactions AS AT', 'ATD.transaction_id = AT.id', 'left');
		$this->db->where('AT.voucher_id', $voucher_id);		
		$res=$this->db->get()->row(); 
		if(isset($res->total_amount)){
			return $res->total_amount;
		}
		else {
			return 0;
		}	
	}
	public function get_voucher_by_name($school_id,$name){		
		$this->db->select('V.*');
        $this->db->from('vouchers AS V');    
		$this->db->where('V.school_id', $school_id); 	
		$this->db->where('V.name', $name);		
		return $this->db->get()->row(); 		
	}
	public function insert_default($school_id, $financial_year_id){
		$this->db->select('V.*,');
        $this->db->from('vouchers AS V');
		$this->db->where('V.school_id', 0); 			
		$vouchers=$this->db->get()->result(); 	
		foreach($vouchers as $l){
			$larr=array();
			$larr['school_id']=$school_id;
			$larr['financial_year_id']=$financial_year_id;
			$larr['type_id']=$l->type_id;
			$larr['name']=$l->name;
			$larr['is_readonly']=$l->is_readonly;
			$larr['budget']=$l->budget;
			$larr['budget_cr_dr']=$l->budget_cr_dr;
			$larr['category']=$l->category;			
			$check = $this->year->get_single("vouchers",  $larr);
			if (empty($check))
			{
				$larr['created']= date('Y-m-d H:i:s');
				$larr['modified']= date('Y-m-d H:i:s');
				$this->db->insert('vouchers',$larr);
				$voucher_id=$this->db->insert_id();
			}		
			
						
		}
	}
	
}
