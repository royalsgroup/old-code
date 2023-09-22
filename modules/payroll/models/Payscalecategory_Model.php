<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class payscalecategory_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
     
        
     public function get_cat_list($school_id = null){
        
        $this->db->select('G.*, S.school_name');
        $this->db->from('payscale_category AS G');
        $this->db->join('schools AS S', 'S.id = G.school_id', 'left');
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('G.school_id', $this->session->userdata('school_id'));
            //$this->db->or_where('school_id','0'); 
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!=null){
            $this->db->where('G.school_id', $school_id);
            //$this->db->or_where('school_id','0'); 
        }
		if($this->session->userdata('dadmin') == 1 && $school_id!=null){
            $this->db->where('G.school_id', $school_id);
           // $this->db->or_where('school_id','0'); 
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        
        $this->db->order_by('G.id', 'DESC');
        
        return $this->db->get()->result();        
    }
    
     public function get_single_grade($grade_id){
        
        $this->db->select('G.*, S.school_name,AL1.name as debit_ledger_name,AL2.name as credit_ledger_name');
        $this->db->from('payscale_category AS G');
        $this->db->join('schools AS S', 'S.id = G.school_id', 'left');
		$this->db->join('account_ledgers AS AL1', 'AL1.id = G.debit_ledger_id', 'left');
		$this->db->join('account_ledgers AS AL2', 'AL2.id = G.credit_ledger_id', 'left');
        $this->db->where('G.id', $grade_id);
         return $this->db->get()->row();  
        
    }
	public function get_single_grade_with_group($id){
        
        $this->db->select('G.*,PG.group_code');
        $this->db->from('payscale_category AS G'); 
		$this->db->join('pay_groups AS PG', 'PG.id = G.pay_group_id', 'left');		        
		 $this->db->where('G.id', $id);
         return $this->db->get()->row();  
        
    }
	public function get_single_grade_by_name($school_id,$grade_name){
        
        $this->db->select('G.*');
        $this->db->from('payscale_category AS G');        
        $this->db->where('G.school_id', $school_id);
		$this->db->where('G.name', $grade_name);
         return $this->db->get()->row();  
        
    }
	public function get_payscale_data_by_user($user_id){
		 $this->db->select('PC.*');
        $this->db->from('user_payscalecategories AS EP');
        $this->db->join('payscale_category AS PC', 'PC.id = EP.payscalecategory_id', 'left');
		$this->db->where('EP.user_id', $user_id);
		return $this->db->get()->result();
	}
    
    
     function duplicate_check($school_id, $grade_name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('school_id', $school_id);
        $this->db->where('name', $grade_name);
        return $this->db->get('payscale_category')->num_rows();            
    }
	public function insert_default($school_id){
		 $this->db->select('G.*');
        $this->db->from('payscale_category AS G');        
        $this->db->where('G.school_id', 0);		         		
		$cats=$this->db->get()->result(); 	
		foreach($cats as $l){
			
			$larr=array();
			$larr['school_id']=$school_id;
			$larr['name']=isset($l->name) ? $l->name : '';
			$larr['is_deduction_type']=$l->is_deduction_type;
			$larr['category_type']=$l->category_type;
			$larr['percentage']=$l->percentage;
			$larr['amount']=$l->amount;
			$larr['pay_group_id']=$l->pay_group_id;
			//$larr['dependant_payscale_categories']=$l->dependant_payscale_categories;
			$larr['set_max_amount_limit']=$l->set_max_amount_limit;
			$larr['max_amount_possible']=$l->max_amount_possible;
			$larr['round_of_method']=$l->round_of_method;
			$larr['remove_dependancy_from_attendance']=$l->remove_dependancy_from_attendance;
			$larr['change_default_debit_ledger']=$l->change_default_debit_ledger;
			$larr['unbound_payscale_category']= $l->unbound_payscale_category;
			
			$larr['created']= date('Y-m-d H:i:s');
			$larr['modified']= date('Y-m-d H:i:s');
			
			// debit ledger id
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');        
			$this->db->where('AL.id', $l->debit_ledger_id);		         		
			$debit_ledger=$this->db->get()->row();
			$debit_ledger_account_group_id = isset($debit_ledger->account_group_id) ? $debit_ledger->account_group_id : 0;
			$debit_ledger_name = isset($debit_ledger->name) ? $debit_ledger->name : 0;
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');        
			$this->db->where('AL.name', $debit_ledger_name );		         		
			$this->db->where('AL.account_group_id', $debit_ledger_account_group_id);
			$this->db->where('AL.school_id', $school_id);
			
			$debit_ledger_new=$this->db->get()->row();
			if(!empty($debit_ledger_new)){
				$larr['debit_ledger_id']=$debit_ledger_new->id;
			}
			// credit ledger
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');        
			$this->db->where('AL.id', $l->credit_ledger_id);		         		
			$credit_ledger=$this->db->get()->row();
			
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');      
			$credit_ledger_name = isset($credit_ledger->name) ? $credit_ledger->name : 0;  
			$this->db->where('AL.name', $credit_ledger_name);		    
			$credit_ledger_account_group_id = isset($credit_ledger->account_group_id) ? $credit_ledger->account_group_id : 0;     		
			$this->db->where('AL.account_group_id', $credit_ledger_account_group_id);
			$this->db->where('AL.school_id', $school_id);
			
			$credit_ledger_new=$this->db->get()->row();
			if(!empty($credit_ledger_new)){
				$larr['credit_ledger_id']=$credit_ledger_new->id;
			}
			// depends on new pay scale category
			$dependant_payscale_categories=array();
			$dpc='';
			if($l->dependant_payscale_categories != ''){
				$arr=array();
				$arr=explode(",",$l->dependant_payscale_categories);
				foreach($arr as $a){
					$cat1=$this->get_single('payscale_category',array("id"=>$a));
					if(!empty($cat1)){
						$cat2=$this->get_single('payscale_category',array("name"=>$cat1->name,'school_id'=>$school_id));
						if(!empty($cat2)){
							$dependant_payscale_categories[]=$cat2->id;
						}
					}
				}
				if(!empty($dependant_payscale_categories)){
					$dpc=implode(",",$dependant_payscale_categories);
				}
			}
			$larr['dependant_payscale_categories']=$dpc;
			$this->db->insert('payscale_category',$larr);
			$cat_id=$this->db->insert_id();					
		}
	}
		public function fix_default($school_id){
		 $this->db->select('G.*');
        $this->db->from('payscale_category AS G');        
        $this->db->where('G.school_id', 0);		         		
		$cats=$this->db->get()->result(); 	
		foreach($cats as $l){
			
			$larr=array();
			$larr['school_id']=$school_id;
			$larr['name']=isset($l->name) ? $l->name : '';
			$larr['is_deduction_type']=$l->is_deduction_type;
			$larr['category_type']=$l->category_type;
			$larr['percentage']=$l->percentage;
			$larr['amount']=$l->amount;
			$larr['pay_group_id']=$l->pay_group_id;
			//$larr['dependant_payscale_categories']=$l->dependant_payscale_categories;
			$larr['set_max_amount_limit']=$l->set_max_amount_limit;
			$larr['max_amount_possible']=$l->max_amount_possible;
			$larr['round_of_method']=$l->round_of_method;
			$larr['remove_dependancy_from_attendance']=$l->remove_dependancy_from_attendance;
			$larr['change_default_debit_ledger']=$l->change_default_debit_ledger;
			$larr['unbound_payscale_category']= $l->unbound_payscale_category;
			
			$larr['created']= date('Y-m-d H:i:s');
			$larr['modified']= date('Y-m-d H:i:s');
			
			// debit ledger id
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');        
			$this->db->where('AL.id', $l->debit_ledger_id);		         		
			$debit_ledger=$this->db->get()->row();
			$debit_ledger_account_group_id = isset($debit_ledger->account_group_id) ? $debit_ledger->account_group_id : 0;
			$debit_ledger_name = isset($debit_ledger->name) ? $debit_ledger->name : 0;
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');        
			$this->db->where('AL.name', $debit_ledger_name );		         		
			$this->db->where('AL.account_group_id', $debit_ledger_account_group_id);
			$this->db->where('AL.school_id', $school_id);
			
			$debit_ledger_new=$this->db->get()->row();
			if(!empty($debit_ledger_new)){
				$larr['debit_ledger_id']=$debit_ledger_new->id;
			}
			// credit ledger
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');        
			$this->db->where('AL.id', $l->credit_ledger_id);		         		
			$credit_ledger=$this->db->get()->row();
			
			$this->db->select('AL.*');
			$this->db->from('account_ledgers AS AL');      
			$credit_ledger_name = isset($credit_ledger->name) ? $credit_ledger->name : 0;  
			$this->db->where('AL.name', $credit_ledger_name);		    
			$credit_ledger_account_group_id = isset($credit_ledger->account_group_id) ? $credit_ledger->account_group_id : 0;     		
			$this->db->where('AL.account_group_id', $credit_ledger_account_group_id);
			$this->db->where('AL.school_id', $school_id);
			
			$credit_ledger_new=$this->db->get()->row();
			if(!empty($credit_ledger_new)){
				$larr['credit_ledger_id']=$credit_ledger_new->id;
			}
			// depends on new pay scale category
			$dependant_payscale_categories=array();
			$dpc='';
			if($l->dependant_payscale_categories != ''){
				$arr=array();
				$arr=explode(",",$l->dependant_payscale_categories);
				foreach($arr as $a){
					$cat1=$this->get_single('payscale_category',array("id"=>$a));
					if(!empty($cat1)){
						$cat2=$this->get_single('payscale_category',array("name"=>$cat1->name,'school_id'=>$school_id));
						if(!empty($cat2)){
							$dependant_payscale_categories[]=$cat2->id;
						}
					}
				}
				if(!empty($dependant_payscale_categories)){
					$dpc=implode(",",$dependant_payscale_categories);
				}
			}
			$larr['dependant_payscale_categories']=$dpc;
			$this->db->select('G.*');
			$this->db->from('payscale_category AS G');        
			$this->db->where('G.school_id', $school_id);	
			$this->db->where('G.is_deduction_type',$larr['is_deduction_type']);	
			$this->db->where('G.percentage',$larr['percentage']);	
			$this->db->where('G.name', $larr['name']);	
			$this->db->where('G.pay_group_id',$larr['pay_group_id']);	
			$this->db->where('G.category_type',$larr['category_type']);	
			$this->db->where('G.change_default_debit_ledger', $larr['change_default_debit_ledger']);	
			$this->db->where('G.unbound_payscale_category',$larr['unbound_payscale_category']);
			$this->db->where('G.amount', $larr['amount']);	
			$this->db->where('G.dependant_payscale_categories',$larr['dependant_payscale_categories']);
			
			
		   
			$exiting=$this->db->get()->result(); 	
			

			if(count( $exiting) >0)
			{
			
				continue;
			}
			else
			{
				$icount++;
			
			}
			$this->db->insert('payscale_category',$larr);
			$cat_id=$this->db->insert_id();					
		}
	}

}
