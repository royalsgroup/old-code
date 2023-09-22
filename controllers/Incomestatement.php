<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Incomestatement extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Accountgroups_Model', 'accountgroups', true);			
		$this->load->model('Accountledgers_Model', 'accountledgers', true);			
    }

     public function index_backup($school_id = null) {
       // for super admin 
         $category=null;
	   $start_date='';
	   $end_date='';
       if ($_POST) {			
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
			 $result=array();
			 $final_expence=0;		
			//$ag=$this->accountgroups->get_accountgroup_by_slug($school_id,'direct_expenses');			
			 $egroup=$this->accountgroups->get_list_new('account_groups', array('school_id'=>$school_id,'type_id'=>1), '','', '', 'id', 'ASC');	
			  foreach($egroup as $ag){
				// get ledgers
				$result[$i]['account_group_id']=$ag->id;
				$result[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$group_id);
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
					else{
						$ledgers[$j]->effective_balance=abs($cb);
					}
					
					$group_total+= $ledgers[$j]->effective_balance; 									
					
					$j++;
				}
				$result[$i]['ledgers']=$ledgers;
				$result[$i]['group_total']=$group_total;
				$final_expence += $group_total;
				$i++;				
			}  
			
			$this->data['expenses']=$result;	

         				// DIRECT Incomestatement
				 $result=array();
				  $final_income=0;
				 $i=0;				
				 $egroup=$this->accountgroups->get_list_new('account_groups', array('school_id'=>$school_id,'type_id'=>2), '','', '', 'name', 'ASC');	
			  foreach($egroup as $ag){
				// get ledgers
				$result[$i]['account_group_id']=$ag->id;
				$result[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$group_id);
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
				$this->data['final_amount']=$final_income;				
			}
			else{				
				$income_difference = $final_expence -$final_income;				
				$this->data['final_amount']=$final_expence;	
			}
			
			$this->data['expence_difference']=$expence_difference;
			$this->data['income_difference']=$income_difference;
        }
		$this->data['schools'] = $this->schools;        
        $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('income_statement'). ' | ' . SMS);
        $this->layout->view('incomestatement/index', $this->data);            
       
    }

    public function index($school_id = null) {
        // for super admin 
          $category=null;
        $start_date='';
        $end_date='';
        if($this->input->post('school_id')){
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
       
        if ($_POST) {			
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
              $result=array();
              $final_expence=0;		
              $final_income=0;
              $incomes = array();
              $expenses = array();
             //$ag=$this->accountgroups->get_accountgroup_by_slug($school_id,'direct_expenses');			
             $ledgers_list = $this->accountledgers->get_accountledger_list($school_id,$financial_year->id,$category,null,null,array("1","2"));
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
                     
                     if($ledger_group['group_type'] == 1)
                     {
                         $expenses[$ledger_group['id']]['account_group_id']=$ledger_group['id'];
                         $expenses[$ledger_group['id']]['account_group_name']=$ledger_group['name'];
                     }
                     else
                     {
                         $incomes[$ledger_group['id']]['account_group_id']=$ledger_group['id'];
                         $incomes[$ledger_group['id']]['account_group_name']=$ledger_group['name'];
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
                         else{
                             $opening_balance = $ledger->opening_balance;
                             $ledgers[$ledger_id]->effective_balance_cr_dr='CR';
                         }
                 
                     
                         $cbalance=$opening_balance+$grand_total;
                     
                         $ledgers[$ledger_id]->effective_balance =  $cbalance;
                         $cb=$cbalance;
                         if($ledger_group['group_type'] == 2)
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
               
                     if($ledger_group['group_type'] == 1)
                     {
                         $expenses[$ledger_group['id']]['ledgers']=$ledgers;
                         $expenses[$ledger_group['id']]['group_total']=$group_total;	
                         $final_expence += $group_total;
                    }
                     else
                     {
                         $incomes[$ledger_group['id']]['ledgers']=$ledgers;
                         $incomes[$ledger_group['id']]['group_total']=$group_total;			
                         $final_income += $group_total;				
                    }
                 
 
                 }
             }
             else
             {
                 $ledgers = array();
             }
       
             
             $this->data['expenses']=$expenses;	
             $this->data['incomes']=$incomes;	
            //  if($school_id == 1608)
			//  {
			// 	 debug_a([			 $final_income, $final_expence]);
			//  }
             $expence_difference=0;
             $income_difference=0;
             if($final_income > $final_expence){
                 $expence_difference=$final_income -$final_expence;
                 $this->data['final_amount']=$final_income;				
             }
             else{				
                 $income_difference = $final_expence -$final_income;				
                 $this->data['final_amount']=$final_expence;	
             }
             
             $this->data['expence_difference']=$expence_difference;
             $this->data['income_difference']=$income_difference;
         }
         $this->data['schools'] = $this->schools;        
         $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
         $this->data['list'] = TRUE;
         $this->layout->title($this->lang->line('income_statement'). ' | ' . SMS);
         $this->layout->view('incomestatement/index', $this->data);            
        
     }
  
    public function add() {

        //check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_ledger_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_ledger_data();
				
                $insert_id = $this->accountledgers->insert('account_ledgers', $data);
                if ($insert_id) {
                    
                    //create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('accountledgers/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('accountledgers/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
 $school_id = '';
        if($_POST){
            
            $school_id = $this->input->post('school_id');            
        }
              
        if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
            $school_id = $this->session->userdata('school_id');
        }               
        
        $school = $this->accountledgers->get_school_by_id($school_id);
               
        $this->data['filter_school_id'] = $school_id;
        
        if($school_id){
            $this->data['accountledgers'] = $this->accountledgers->get_accountledger_list( $school_id, $school->financial_year_id);
        }
                
        //$this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
                      
        
        $this->data['schools'] = $this->schools;		        
        $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('ledger'). ' | ' . SMS);
        $this->layout->view('accountledgers/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Academic School" user interface                 
    *                    with populated "Academic School" value 
    *                    and update "Academic School" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {   
        
        //check_permission(EDIT);
       
        if ($_POST) {
            $this->_prepare_ledger_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_ledger_data();				
                $updated = $this->accountledgers->update('account_ledgers', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('accountledgers/index/'.$data['school_id']);    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('accountledgers/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['accountledger'] = $this->accountledgers->get_single('account_ledgers', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['accountledger'] = $this->accountledgers->get_single('account_ledgers', array('id' => $id));
				
                if (!$this->data['accountledger']) {
                     redirect('accountledgers');
                }
            }
        }
		$school_id=$this->data['accountledger']->school_id;
		 if($school_id){
            $this->data['accountledgers'] = $this->accountledgers->get_accountledger_list( $school_id, $school->financial_year_id);
        }
           
		 
        
        $this->data['school_id'] = $school_id;
        $this->data['filter_school_id'] = $school_id;   
		$this->data['schools'] = $this->schools;
          
        $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('ledger'). ' | ' . SMS);
        $this->layout->view('accountledgers/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_ledger_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      
	  $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
		$this->form_validation->set_rules('account_group_id', $this->lang->line('account_group_id'), 'trim|required'); 
$this->form_validation->set_rules('opening_cr_dr', $this->lang->line('opening_cr_dr'), 'trim|required'); 		
$this->form_validation->set_rules('budget_cr_dr', $this->lang->line('budget_cr_dr'), 'trim|required'); 		
    }

            
   
    
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_ledger_data() {

        $items = array();
               
		$items[] = 'school_id';
        $items[] = 'name'; 
		$items[] = 'account_group_id'; 		
		$items[] = 'opening_cr_dr'; 		
		$items[] = 'opening_balance';
		$items[] = 'budget'; 		 		
		$items[] = 'budget_cr_dr'; 		
		
        $data = elements($items, $_POST); 
		$school = $this->accountledgers->get_school_by_id($data['school_id']);            
        if(!$school->financial_year_id){
            error('Please set financial year for the school');
            redirect('student/index');
        }
		$data['financial_year_id'] = $school->financial_year_id;
		
          if ($this->input->post('id')) {
            $data['modified'] = date('Y-m-d H:i:s');            
        } else {           
            $data['created'] = date('Y-m-d H:i:s');
            $data['modified'] = date('Y-m-d H:i:s');
                       
        }   

        return $data;
    }
	public function delete($id = null) {        
        
       // check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('itemcategory/index');              
        }
        
        // need to find all child data from database 
       // $skips = array('schools');
        //$tables = $this->db->list_tables();
		$skips=array();
        $tables=array('schools');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->itemcategory->get_list($table, array('district_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('itemcategory/index');
            }
        }    
     
        
        $itemcategory = $this->itemcategory->get_single('districts', array('id' => $id));
        
        if ($this->itemcategory->delete('districts', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('itemcategory/index');
    }

}
