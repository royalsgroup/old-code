<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Balancesheet extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Accountgroups_Model', 'accountgroups', true);			
		$this->load->model('Accountledgers_Model', 'accountledgers', true);			
    }

     public function index_bkp($school_id = null) {
       // for super admin 
	   $category=null;
	   $start_date='';
	   $end_date='';
       if (!empty($_POST)) {	
		   
			if(isset($_POST['filter_start_date']) && $_POST['filter_start_date']!=''){
				$start_date=$_POST['filter_start_date'];
			}
			if(isset($_POST['filter_end_date']) && $_POST['filter_end_date']!=''){
				$end_date=$_POST['filter_end_date'];
			}
		   if($this->input->post('school_id')){
			   $school_id=$this->input->post('school_id');
		   }
		   if($this->input->post('category')){
			   $category=$this->input->post('category');			   			   
		   }	
		}
		
		$this->data['filter_start_date']=$start_date;
		$this->data['filter_end_date']=$end_date;
        if(!$school_id && ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)){
            $school_id = $this->session->userdata('school_id');
        }               
       
        $school = $this->accountledgers->get_school_by_id($school_id);
		$financial_year=$this->accountledgers->get_single("financial_years",array("school_id"=>$school_id,'is_running'=>1));
		$this->data['financial_year']=$financial_year;
		$this->data['school_info'] = $school;
          
        $this->data['filter_school_id'] = $school_id;
       
		$i=0;
        if($school_id){
			 $liabilities=array();
			 $final_liabilities=0;						 
			 $lgroup=$this->accountgroups->get_list_new('account_groups', array('school_id'=>$school_id,'type_id'=>4), '','', '', 'id', 'ASC');				 
			 foreach($lgroup as $ag){			
				// get ledgers
				$liabilities[$i]['account_group_id']=$ag->id;
				$liabilities[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$group_id,$category);
				
				$j=0;				
				foreach($ledgers as $l){
					// get current balance
					if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date'])){
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,$start_date,$end_date,$category);	
								
					}
					else{
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,null,null,$category);
					}							
					
					$cb=$cbalance;					
					$ledgers[$j]->effective_balance=$cbalance;					
					$group_total+= $ledgers[$j]->effective_balance; 
					
					$j++;
				}
				$liabilities[$i]['ledgers']=$ledgers;
				$liabilities[$i]['group_total']=$group_total;	
				$final_liabilities += $group_total;
			    $i++;
			 }
				
			 $assets=array();
			 $final_assets=0;			  
			  $agroup=$this->accountgroups->get_list_new('account_groups', array('school_id'=>$school_id,'type_id'=>3), '','', '', 'id', 'ASC');	
			 foreach($agroup as $ag){				
				// get ledgers
				$assets[$i]['account_group_id']=$ag->id;
				$assets[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$group_id,$category);
				$j=0;
				//print_r($ledgers); exit;
				foreach($ledgers as $l){
					// get current balance
					if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date'])){
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,$start_date,$end_date,$category);	
										
					}
					else{
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,null,null,$category);
						
					}					
					$cb=$cbalance;					
					if($cb>0){
						$ledgers[$j]->effective_balance=(-$cb);
					}
					else if($cb <0){
						$ledgers[$j]->effective_balance=abs($cb);
					}					
					$group_total+= $ledgers[$j]->effective_balance; 					
					$j++;
				}
				$assets[$i]['ledgers']=$ledgers;
				$assets[$i]['group_total']=$group_total;			
				$final_assets += $group_total;				
			     $i++;
			 }
			 // get reserve fund/ retained from income and expense statement
			$retained=$this->get_retained_balance($school_id,$category);
			$this->data['expence_difference']=$retained['expence_difference'];
			$this->data['income_difference']=$retained['income_difference'];
			$final_assets +=$retained['income_difference'];
			 $final_liabilities+=$retained['expence_difference'];
			 $this->data['assets']=$assets;	
			 $this->data['liabilities']=$liabilities;
			 $liability_difference=0;
			$asset_difference=0;
			if($final_assets > $final_liabilities){
				if($final_liabilities <0){
					$liability_difference=$final_assets -abs($final_liabilities);
				}
				else{
					$liability_difference=$final_assets -$final_liabilities;
				}
				$this->data['final_amount']=$final_assets;				
			}
			else{	
				if($final_assets <0 ){
					$asset_difference = $final_liabilities -abs($final_assets);	
				}	
				else{				
				$asset_difference = $final_liabilities -$final_assets;				
				}
				$this->data['final_amount']=$final_liabilities;	
			}
			
			$this->data['liability_difference']=$liability_difference;
			$this->data['asset_difference']=$asset_difference;
			
			
			//print_r($liabilities);
        }
		$this->data['schools'] = $this->schools;        
        $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('balancesheet'). ' | ' . SMS);
        $this->layout->view('balancesheet/index', $this->data);            
       
    }  
	public function index($school_id = null) {
		// for super admin 
		$category=null;
		$start_date='';
		$end_date='';
		if(!$school_id && $this->input->post('school_id')){
			$school_id=$this->input->post('school_id');
		}
		if(!$school_id && ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)){
			$school_id = $this->session->userdata('school_id');
		}    
		if($school_id)
		{
			$financial_year=$this->accountledgers->get_single("financial_years",array("school_id"=>$school_id,'is_running'=>1));
			if(strpos($financial_year->session_year,"->"))	
		   {
			   $arr=explode("->",$financial_year->session_year);
			   $f_start=date("Y-m-d",strtotime($arr[0]));		
			   $f_end=date("Y-m-d",strtotime($arr[1]));	
		   }
		   else
		   {
			   $arr=explode("-",$financial_year->session_year);
			   $date_exploded = explode(" ",$arr[0]);
			   if(count($date_exploded)>2)
			   {
				   $f_start=date("Y-m-d",strtotime($arr[0]));		
				   $f_end=date("Y-m-d",strtotime($arr[1]));	
			   }
			   else
			   {
				   $f_start=date("Y-m-d",strtotime("1 ".$arr[0]));		
				   $f_end=date("Y-m-d",strtotime("31 ".$arr[1]));	
			   }
		   }	
		   $startDate=date('d/m/Y',strtotime($f_start));
		   $endDate=date('d/m/Y',strtotime($f_end));
		   $this->data['f_start_date'] =$startDate;
		   $this->data['f_end_date'] =$endDate;
		}
		
		if (!empty($_POST)) {	
			
			 if(isset($_POST['filter_start_date']) && $_POST['filter_start_date']!=''){
				 $start_date=$_POST['filter_start_date'];
			 }
			 if(isset($_POST['filter_end_date']) && $_POST['filter_end_date']!=''){
				 $end_date=$_POST['filter_end_date'];
			 }
			
			if($this->input->post('category')){
				$category=$this->input->post('category');			   			   
			}	
		 }
		 
		 $this->data['filter_start_date']=$start_date;
		 $this->data['filter_end_date']=$end_date;
		           
		
		 $school = $this->accountledgers->get_school_by_id($school_id);
  		 $this->data['financial_year']=$financial_year;
		 $this->data['school_info'] = $school;
		   
		 $this->data['filter_school_id'] = $school_id;
		
		 $i=0;
		 if($school_id){
			$result= $lgroups= $agroups = array();
			$final_debit=0;
			$final_credit=0;
			$liabilities=array();
			$final_liabilities=0;
			$final_assets = 0;
			$assets = array();	
			$ledger_ids1 = [];		
			// if($school_id ==175)
			// {
			// 	$ledger_ids1 =array(14669);
			// }
			$ledgers_list = $this->accountledgers->get_accountledger_list($school_id,$financial_year->id,$category,null,null,array("4","3"),null,null,null,null,$ledger_ids1 );
		
			if(!empty($ledgers_list))
			{
				$ledger_ids = array();
				$ledgers_list_updated = [];
				foreach($ledgers_list as $ledger)
				{
					if(!isset($account_groups[$ledger->group_id]))
					{
						$account_groups[$ledger->group_id] = array('id'=>$ledger->group_id,'group_type'=>$ledger->group_type, 'name'=>$ledger->group_name,'type_name'=> $ledger->type_name,"ledgers" => array(),"transactions"=>array(),"other_transactions"=>array(),"tr_ids"=>array());
					}

					$account_groups[$ledger->group_id]['ledgers'][$ledger->id] = $ledger;
					$ledger_ids[] = $ledger->id;
				}
				
				$transaction_start_date = $start_date ? $start_date : $f_start;
				$transaction_end_date =  $end_date ?  $end_date : $f_end;
				
				// debug_a([$transaction_start_date,$transaction_end_date]);
				$ledger_transactions = $this->accountledgers->get_ledger_with_amount_list_with_balance_with_group($ledger_ids,  $transaction_start_date,  $transaction_end_date,$category);
			
				 $ledgers = array();	
				$transations_list_updated = [];
				$tr_ids = [];
				$ledger_tr_ids = [];
				$ledger_group_id  = array();
				foreach($ledger_transactions as $ledger_transaction)
				{
					if(!isset($account_groups[$ledger_transaction->group_id]['transactions'][$ledger_transaction->ledger_id]))
					{
						$account_groups[$ledger_transaction->group_id]['transactions'][$ledger_transaction->ledger_id] =  array();
						$account_groups[$ledger_transaction->group_id]['tr_ids'][$ledger_transaction->ledger_id] =  array();
					}
					$ledgers_transactions_updated [$ledger_transaction->ledger_id][] =  $ledger_transaction;
					$ledger_group_id[$ledger_transaction->ledger_id] = $ledger_transaction->group_id;
					if(!in_array($ledger_transaction->id,$tr_ids) )
					{
						$tr_ids[] = $ledger_transaction->id;
					}
				}
				$other_transations_list_updated = [];

				$other_transactions=$this->accountledgers->get_ledger_with_amount_list_with_balance_excluded_with_group($ledger_ids,null, $transaction_start_date,$transaction_end_date,$category);
			
				foreach($other_transactions as $other_transaction)
				{
					$other_transaction->group_id = $ledger_group_id[$other_transaction->ledger_id] ;
					if(!isset($account_groups[$other_transaction->group_id]['other_transactions'][$other_transaction->ledger_id]))
					{
						$account_groups[$other_transaction->group_id]['other_transactions'][$other_transaction->ledger_id] =  array();
					}
					if (!isset($account_groups[$other_transaction->group_id]['tr_ids'][$other_transaction->ledger_id]))
					{
						$account_groups[$other_transaction->group_id]['tr_ids'][$other_transaction->ledger_id] = array();
					}
					
					if( !in_array($other_transaction->transaction_id,$account_groups[$other_transaction->group_id]['tr_ids'][$other_transaction->ledger_id]))
					{
						$other_transations_list_updated [$other_transaction->ledger_id][] =  $other_transaction;
					}
				}
				
				foreach($account_groups as $ledger_group){
					// get ledgers
					if(!$ledger_group['id'])
					{
						continue;
					}
					
					if($ledger_group['group_type'] == 4)
					{
						$liabilities[$ledger_group['id']]['account_group_id']=$ledger_group['id'];
						$liabilities[$ledger_group['id']]['account_group_name']=$ledger_group['name'];
					}
					else
					{
						$assets[$ledger_group['id']]['account_group_id']=$ledger_group['id'];
						$assets[$ledger_group['id']]['account_group_name']=$ledger_group['name'];
					}

					$ledgers_list_updated =  $ledger_group['ledgers'];
					//$ledgers_transactions_updated =  $ledger_group['transactions'];
				// $other_transations_list_updated =  $ledger_group['other_transactions'];
					$group_total_debit=0;
					$group_total_credit=0;
					$group_total = 0;
					$ledgers =  array();
					//530 ,14103
				
					
					foreach($ledgers_list_updated as $ledger_id => $ledger)
					{
						if(!isset($ledgers[$ledger_id]))
						{
							$ledgers[$ledger_id] = $ledger;
							$ledgers[$ledger_id]->effective_balance =0;
						}
						$ledger_transactions = isset($ledgers_transactions_updated[$ledger_id]) && $ledgers_transactions_updated[$ledger_id] ? $ledgers_transactions_updated[$ledger_id] :  array();
					
						$grand_total = 0;
						foreach($ledger_transactions as $ledger_transaction)
						{  
							if($ledger_transaction->head_cr_dr == "DR")
							{
								$grand_total= $grand_total-($ledger_transaction->total_amount);
							}
							else
							{
								$grand_total= $grand_total+($ledger_transaction->total_amount);
							}
						}
						
						$other_transactions = isset($other_transations_list_updated[$ledger_id]) && $other_transations_list_updated[$ledger_id] ? $other_transations_list_updated[$ledger_id] :  array();
						foreach($other_transactions as $other_transaction){
							if($other_transaction->head_cr_dr == "DR")
							{
								$grand_total= $grand_total+($other_transaction->amount);
							}
							else
							{
								$grand_total= $grand_total-($other_transaction->amount);
							}
						}
					
			
						if($ledgers[$ledger_id]->opening_cr_dr =='DR'){
							$opening_balance = -($ledger->opening_balance);
							$ledgers[$ledger_id]->effective_balance_cr_dr='DR';
						}
						else
						{
							$opening_balance = $ledger->opening_balance;
							$ledgers[$ledger_id]->effective_balance_cr_dr='CR';
						}
				
					
						$cbalance=$opening_balance+$grand_total;
					
						$ledgers[$ledger_id]->effective_balance =  $cbalance;
						$cb=$cbalance;
						if($ledger_group['group_type'] == 4)
						{
							$ledgers[$ledger_id]->effective_balance = 	$cb;
						}	
						else
						{			
							if($cb>0){
								$ledgers[$ledger_id]->effective_balance=(-$cb);
							}
							else if($cb <0){
								$ledgers[$ledger_id]->effective_balance=abs($cb);
							}	
						}				
						$group_total+= $ledgers[$ledger_id]->effective_balance; 
							if($cbalance != 0){
								$result['groups'][$ledger_group['id']]['group_class']='nonZeroAmtGroup';
							}

					}
				
					if($ledger_group['group_type'] == 4)
					{
					
						$liabilities[$ledger_group['id']]['ledgers']=$ledgers;
						$liabilities[$ledger_group['id']]['group_total']=$group_total;	
						$final_liabilities += $group_total;
					}
					else
					{
						$assets[$ledger_group['id']]['ledgers']=$ledgers;
						$assets[$ledger_group['id']]['group_total']=$group_total;			
						$final_assets += $group_total;	
					}
				

				}
			}
			else
            {
                $ledgers = array();
            }

			// if($school_id ==175)
			// {
			// 	debug_a($ledgers);
			// }


			
			  // get reserve fund/ retained from income and expense statement
			 $retained=$this->get_retained_balance($school_id,$category);
			 $this->data['expence_difference']=$retained['expence_difference'];
			 $this->data['income_difference']=$retained['income_difference'];
			
			 $final_assets +=$retained['income_difference'];
			  $final_liabilities+=$retained['expence_difference'];
			  $this->data['assets']=$assets;	
			  $this->data['liabilities']=$liabilities;
			  $liability_difference=0;
			 $asset_difference=0;
			 if($final_assets > $final_liabilities){
				 if($final_liabilities <0){
					 $liability_difference=$final_assets -abs($final_liabilities);
				 }
				 else{
					 $liability_difference=$final_assets -$final_liabilities;
				 }
				 $this->data['final_amount']=$final_assets;				
			 }
			 else{	
				 if($final_assets <0 ){
					 $asset_difference = $final_liabilities -abs($final_assets);	
				 }	
				 else{				
				 $asset_difference = $final_liabilities -$final_assets;				
				 }
				 $this->data['final_amount']=$final_liabilities;	
			 }
			 
			 $this->data['liability_difference']=$liability_difference;
			 $this->data['asset_difference']=$asset_difference;
			 
			 
			 //print_r($liabilities);
		 }
		 $this->data['schools'] = $this->schools;        
		 $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
		 $this->data['list'] = TRUE;
		 $this->layout->title($this->lang->line('balancesheet'). ' | ' . SMS);
		 $this->layout->view('balancesheet/index', $this->data);            
		
	 }  
	private	function get_retained_balance($school_id=null,$category=null){
		
		$i=0;
		if($school_id != NULL){
			$school = $this->accountledgers->get_school_by_id($school_id);
			$result=array();
			 $final_expence=0;						
			 $egroup=$this->accountgroups->get_list('account_groups', array('school_id'=>$school_id,'type_id'=>1), '','', '', 'id', 'ASC');	
			  foreach($egroup as $ag){
				// get ledgers
				$result[$i]['account_group_id']=$ag->id;
				$result[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->academic_year_id,$group_id);
				$j=0;				
				foreach($ledgers as $l){
					// get current balance					
					if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date'])){
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,$_POST['filter_start_date'],$_POST['filter_end_date'],$category);	
					}
					else{
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,$_POST['filter_start_date'],$_POST['filter_end_date'],$category);
					}				
					$cb=$cbalance;				
					if($cb>0){
						$ledgers[$j]->effective_balance=(-$cb);
					}
					else{
						$ledgers[$j]->effective_balance=abs($cb);
					}
					//$ledgers[$j]->effective_balance=$cb;
					$group_total+= $ledgers[$j]->effective_balance; 									
					
					$j++;
				}
				$result[$i]['ledgers']=$ledgers;
				$result[$i]['group_total']=$group_total;
				$final_expence += $group_total;
				$i++;				
			}  
			
				

				// DIRECT Incomestatement
				 $result=array();
				  $final_income=0;
				 $i=0;				
				 $egroup=$this->accountgroups->get_list('account_groups', array('school_id'=>$school_id,'type_id'=>2), '','', '', 'id', 'ASC');	
			  foreach($egroup as $ag){
				// get ledgers
				$result[$i]['account_group_id']=$ag->id;
				$result[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->academic_year_id,$group_id);
				$j=0;
				//print_r($ledgers); exit;
				foreach($ledgers as $l){
					// get current balance
					if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date'])){
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,$start_date,$end_date);					
					}
					else{
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
					}					
					$ledgers[$j]->effective_balance=$cbalance;
					$group_total+= $ledgers[$j]->effective_balance; 					
					$j++;
				}
				$result[$i]['ledgers']=$ledgers;
				$result[$i]['group_total']=$group_total;	
				$final_income += $group_total;				
					$i++;
			  }
			$this->data['incomes']=$result;	
			$expence_difference=0;
			$income_difference=0;
			if($final_income > $final_expence){
				$expence_difference=$final_income -$final_expence;						
			}
			else{				
				$income_difference = $final_expence -$final_income;								
			}
			$output=array();
			$output['expence_difference']=$expence_difference;
			$output['income_difference']=$income_difference;
			
	}
	return $output;
	}

}
