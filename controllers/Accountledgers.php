<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class AccountLedgers extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Accountledgers_Model', 'accountledgers', true);			
		$this->load->model('Accountgroups_Model', 'accountgroups', true);			
    }

     public function index_bkp($school_id = null) {		 
       // for super admin 
	   
	   $category = null;
	   if(!empty($_POST)){		   	  
		if($this->input->post('school_id')>=0){			   
			$school_id=$this->input->post('school_id');
		}
		if($this->input->post('category')){
			$category=$this->input->post('category');			   
		}		   
	}
        $condition = array();
        if($school_id==null && $this->session->userdata('role_id') != SUPER_ADMIN){
            $school_id = $this->session->userdata('school_id');
			$condition['school_id']=$school_id;			
            // print_r($this->data['accountgroups']."hello1");exit;    
        }               
               
        if($school_id)
		{
			$this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list_by_school($school_id);
		}
        $this->data['filter_school_id'] = $school_id;
        //$school = $this->accountledgers->get_payscale_category_by_school('account_ledgers',$school_id); 
		$school = $this->accountledgers->get_school_by_id($school_id);
        // print_r($school);exit; 
		
		$this->data['school_info'] = $school; 
		$financial_year= $this->accountledgers->get_single('financial_years', array('school_id' => $school_id,'is_running'=>1));
		$this->data['financial_year']=$financial_year;
		if(empty($_POST) && !$category){		
            $category = $school->category;
        }
        if($school_id){
			if($school_id == 1398)
			{
				//$this->accountledgers->insert_default($school_id,$school->financial_year_id);
			}
            $ledgers = $this->accountledgers->get_accountledger_list( $school_id, $school->financial_year_id,$category);
            // print_r($ledgers);exit;			
			$i=0;			
			foreach($ledgers as $l){
				
				// get current balance
				//$cbalance=$this->accountledgers->get_current_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
				$ebalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
				/*if($l->opening_cr_dr== 'DR'){
					$opening_balance=(-$l->opening_balance);
				}
				else{
					$opening_balance=$l->opening_balance;
				}
				//print $opening_balance; exit;
				$ledgers[$i]->current_balance=$opening_balance+$cbalance;*/

				$ledgers[$i]->current_balance=$cbalance;
				$ledgers[$i]->effective_balance=$ebalance;
				$i++;
			}
//			print_r($ledgers); exit;
			$this->data['accountledgers']=$ledgers;
        }
          
        //$this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
                      
        
        $this->data['schools'] = $this->schools;
        		
		//$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		
        $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_account_ledgers'). ' | ' . SMS);
        $this->layout->view('accountledgers/index', $this->data);            
       
    }
	public function index_backup($school_id = null) {		 
		// for super admin 
		
		$category = null;
		if(!empty($_POST)){		   	  
		 if($this->input->post('school_id')>=0){			   
			 $school_id=$this->input->post('school_id');
		 }
		 if($this->input->post('category')){
			 $category=$this->input->post('category');			   
		 }		   
	 }
		 $condition = array();
		 if($school_id==null && $this->session->userdata('role_id') != SUPER_ADMIN){
			 $school_id = $this->session->userdata('school_id');
			 $condition['school_id']=$school_id;			
			 // print_r($this->data['accountgroups']."hello1");exit;    
		 }               
				
		 if($school_id)
		 {
			 $this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list_by_school($school_id);
		 }
		 $this->data['filter_school_id'] = $school_id;
		 //$school = $this->accountledgers->get_payscale_category_by_school('account_ledgers',$school_id); 
		 $school = $this->accountledgers->get_school_by_id($school_id);
		 // print_r($school);exit; 
		 
		 $this->data['school_info'] = $school; 
		 $financial_year= $this->accountledgers->get_single('financial_years', array('school_id' => $school_id,'is_running'=>1));
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
		 $this->data['financial_year']=$financial_year;
		 if(empty($_POST) && !$category){		
			 $category = $school->category;
		 }
		 if($school_id){
			 $ledgers_list = $this->accountledgers->get_accountledger_list($school_id,$financial_year->id,$category);
			 
			// echo $this->db->last_query();
			// die();
			 if(!empty($ledgers_list))
			 {

			 
			 $ledger_ids = array();
			 $ledgers_list_updated = [];
			 foreach($ledgers_list as $ledger)
			 {
				$ledgers_list_updated[$ledger->id] = $ledger;
				$ledger_ids[] = $ledger->id;
			 }
			 $ledger_transactions = $this->accountledgers->get_ledger_with_amount_list_with_balance($ledger_ids, $f_start,$f_end);
			 $ledgers = array();	
		 $transations_list_updated = [];
		 $tr_ids = [];
		 $ledger_tr_ids = [];
		 foreach($ledger_transactions as $ledger_transaction)
		 {
			if(!isset($ledgers_transactions_updated[$ledger_transaction->ledger_id]))
			{
				$ledgers_transactions_updated[$ledger_transaction->ledger_id] =  array();
				$ledger_tr_ids[$ledger_transaction->ledger_id] =  array();
			}
			$ledgers_transactions_updated[$ledger_transaction->ledger_id][] =  $ledger_transaction;
			
			if(!in_array($ledger_transaction->id,$tr_ids) )
			{
				$tr_ids[] = $ledger_transaction->id;
			}
		 }
		 
		 $other_transations_list_updated = [];

		 $other_transactions=$this->accountledgers->get_ledger_with_amount_list_with_balance_excluded($ledger_ids,null, $f_start,$f_end);
		
				
		 foreach($other_transactions as $other_transaction)
		 {
			if(!isset($other_transations_list_updated[$other_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id] =  array();
			}
			if (!isset($ledger_tr_ids[$ledger_transaction->ledger_id]))
			{
				$ledger_tr_ids[$ledger_transaction->ledger_id] = array();

			}

			if( !in_array($other_transaction->transaction_id,$ledger_tr_ids[$ledger_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id][] =  $other_transaction;

			}
			
		 }
		 foreach($ledgers_list_updated as $ledger_id => $ledger)
		 {
			if(!isset($ledgers[$ledger_id]))
			{
			
				$ledgers[$ledger_id] = $ledger;
				
				$ledgers[$ledger_id]->effective_balance =0;
			}
			$ledger_transactions = isset($ledgers_transactions_updated[$ledger_id]) && $ledgers_transactions_updated[$ledger_id] ? $ledgers_transactions_updated[$ledger_id] :  array();
			// echo "<pre>";
			
			$grand_total = 0;
			foreach($ledger_transactions as $ledger_transaction){
				
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
			// if($ledger_id == 209656)
			// {
			// 	echo "<pre>";
			// 	//echo $this->db->last_query();
			// 	var_dump($other_transactions);
			// 	die();
			// }
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
			// if($ledger_id == 209656)
			// {
			// 	echo "<pre>";
			// 	//echo $this->db->last_query();
			// 	var_dump($grand_total);
			// 	die();
			// }
			if($ledgers[$ledger_id]->opening_cr_dr =='DR'){
				$opening_balance = -($ledger->opening_balance);
				$ledgers[$ledger_id]->effective_balance_cr_dr='DR';
			}
			else{
				$opening_balance = $ledger->opening_balance;
				$ledgers[$ledger_id]->effective_balance_cr_dr='CR';
			}
	
		
			$final_amount=$opening_balance+$grand_total;
			if($ledger_id == 14669)
			{
				// echo "<pre>";
				// echo $this->db->last_query();
				// var_dump($other_transactions);
				// die();
			}
			$ledgers[$ledger_id]->effective_balance =  $final_amount;
		 }
		}
		else
		{
			$ledgers = array();
		}
			 // print_r($ledgers);exit;			
			 $i=0;			
			 
 //			print_r($ledgers); exit;
			 $this->data['accountledgers']=$ledgers;
		 }
		   
		 //$this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
					   
		 
		 $this->data['schools'] = $this->schools;
				 
		 //$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		
		 $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
		 $this->data['list'] = TRUE;
		 $this->layout->title($this->lang->line('manage_account_ledgers'). ' | ' . SMS);
		 $this->layout->view('accountledgers/index_w_ajax', $this->data);            
		
	 }
	 public function index($school_id = null) {		 
		// for super admin 
		
		$category = null;
		if(!empty($_POST)){		   	  
		 if($this->input->post('school_id')>=0){			   
			 $school_id=$this->input->post('school_id');
		 }
		 if($this->input->post('category')){
			 $category=$this->input->post('category');			   
		 }		   
	 }
		 $condition = array();
		 if($school_id==null && $this->session->userdata('role_id') != SUPER_ADMIN){
			 $school_id = $this->session->userdata('school_id');
			 $condition['school_id']=$school_id;			
			 // print_r($this->data['accountgroups']."hello1");exit;    
		 }               
				
		 if($school_id)
		 {
			 $this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list_by_school($school_id);
		 }
		 $this->data['filter_school_id'] = $school_id;
		 //$school = $this->accountledgers->get_payscale_category_by_school('account_ledgers',$school_id); 
		 $school = $this->accountledgers->get_school_by_id($school_id);
		 // print_r($school);exit; 
		 
		 $this->data['school_info'] = $school; 
		 if(empty($_POST) && !$category){		
			 $category = $school->category;
		 }
		 //$this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
					   
		 
		 $this->data['schools'] = $this->schools;
				 
		 //$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		
		 $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
		 $this->data['list'] = TRUE;
		 $this->layout->title($this->lang->line('manage_account_ledgers'). ' | ' . SMS);
		 $this->layout->view('accountledgers/index', $this->data);            
		
	 }
	public function get_list(){
		error_on();
		if($_POST){            
            $school_id = $this->input->post('school_id');  
			//if(isset($_POST['page']) && $_POST['page']== 'all'){
			$start = $this->input->post('start');
            $limit  = $this->input->post('length');   
			$draw = $this->input->post('draw');	
			$category = $this->input->post('category');	
			$search_text ="";
			if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
				$search_text=$_POST['search']['value'];
			}
			$data = [];
			if($school_id!= ''){
				$school = $this->accountledgers->get_school_by_id($school_id);
				// print_r($school);exit; 
				
				$this->data['school_info'] = $school; 
				$financial_year= $this->accountledgers->get_single('financial_years', array('school_id' => $school_id,'is_running'=>1));
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
				$this->data['financial_year']=$financial_year;
				if(empty($_POST) && !$category){		
					$category = $school->category;
				}
				if($school_id){
					$ledgers_list = $this->accountledgers->get_accountledger_list($school_id,$financial_year->id,$category,null.null,null,null,$limit,$start,$search_text);
					$totalRecords = $this->accountledgers->get_accountledger_list($school_id,$financial_year->id,$category,null.null,null,null,$limit,$start,$search_text,1);
// 					echo $this->db->last_query();
// debug_a($ledgers_list);
					if(!empty($ledgers_list))
					{
	   
					
					$ledger_ids = array();
					$ledgers_list_updated = [];
					foreach($ledgers_list as $ledger)
					{
					   $ledgers_list_updated[$ledger->id] = $ledger;
					   $ledger_ids[] = $ledger->id;
					}
					$ledger_transactions = $this->accountledgers->get_ledger_with_amount_list_with_balance($ledger_ids, $f_start,$f_end);

					$ledgers = array();	
				$transations_list_updated = [];
				$tr_ids = [];
				$ledger_tr_ids = [];
				foreach($ledger_transactions as $ledger_transaction)
				{
				   if(!isset($ledgers_transactions_updated[$ledger_transaction->ledger_id]))
				   {
					   $ledgers_transactions_updated[$ledger_transaction->ledger_id] =  array();
					   $ledger_tr_ids[$ledger_transaction->ledger_id] =  array();
				   }
				   $ledgers_transactions_updated[$ledger_transaction->ledger_id][] =  $ledger_transaction;
				   
				   if(!in_array($ledger_transaction->id,$tr_ids) )
				   {
					   $tr_ids[] = $ledger_transaction->id;
				   }
				}
				
				$other_transations_list_updated = [];
	   
				$other_transactions=$this->accountledgers->get_ledger_with_amount_list_with_balance_excluded($ledger_ids,null, $f_start,$f_end);
			   
					   
				foreach($other_transactions as $other_transaction)
				{
				   if(!isset($other_transations_list_updated[$other_transaction->ledger_id]))
				   {
					   $other_transations_list_updated[$other_transaction->ledger_id] =  array();
				   }
				   if (!isset($ledger_tr_ids[$other_transaction->ledger_id]))
				   {
					   $ledger_tr_ids[$other_transaction->ledger_id] = array();
	   
				   }
	   
				   if( !in_array($other_transaction->transaction_id,$ledger_tr_ids[$other_transaction->ledger_id]))
				   {
					   $other_transations_list_updated[$other_transaction->ledger_id][] =  $other_transaction;
	   
				   }
				   
				}
				$count =1;
				
				foreach($ledgers_list_updated as $ledger_id => $ledger)
				{
					$row_data = array();
				   if(!isset($ledgers[$ledger_id]))
				   {
				   
					   $ledgers[$ledger_id] = $ledger;
					   
					   $ledgers[$ledger_id]->effective_balance =0;
				   }
				   $ledger_transactions = isset($ledgers_transactions_updated[$ledger_id]) && $ledgers_transactions_updated[$ledger_id] ? $ledgers_transactions_updated[$ledger_id] :  array();
				
				   $grand_total = 0;
				   foreach($ledger_transactions as $ledger_transaction){
					   
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
				   $final_amount=$opening_balance+$grand_total;
				   $ledgers[$ledger_id]->effective_balance =  $final_amount;
				   if(has_permission(DELETE, 'accounting', 'accountledgers')){
					$row_data[]="<input type='checkbox' class='delete_check' name='checkId[]' value='<?php print $ledger->id; ?>' />";
				}
				else{
					$row_data[][]=$count;
				}
				$row_data[][]=$ledger->name;
				$row_data[][]=$ledger->group_name;
				if($ledger->effective_balance < 0){
					$row_data[][]=round(abs($ledger->effective_balance),2);
				}
				else{
					$row_data[][]='';
				}
				if($ledger->effective_balance > 0){
					$row_data[][]=round(abs($ledger->effective_balance),2);
				}
				else{
					$row_data[][]='';
				}
				if($ledger->effective_balance == 0){
											if($ledger->dr_cr == 'DR'){ 
											$row_data[][]= "DR"; 
											} else { 
											$row_data[][]= "CR"; 
											}
										}else{
										if($ledger->effective_balance < 0){
											$row_data[][]= "DR"; 
											} else { $row_data[][]= "CR"; 
											} }					
				$row_data[][]=(int)abs($ledger->effective_balance);
				$row_data[][]=$ledger->opening_cr_dr;
				if($ledger->opening_balance != 0){ 
					$row_data[][]= abs($ledger->opening_balance)." [".$ledger->opening_cr_dr. "]"; 
				}
				else{
					$row_data[][]='';
				}

				$row_data[][]=abs($ledger->budget)." [".$ledger->budget_cr_dr. "]";
				$row_data[][] = abs($ledger->budget) - (int)abs($ledger->effective_balance);
				$row_data[][]=$ledger->ledger_uid;
				$row_data[][]=$ledger->school_name;
				$row_data[][]=$ledger->session_year;
				$row_data[][]=$ledger->group_code;
				$action='';
				if(has_permission(VIEW, 'accounting', 'accountledgers')){
					 $action .= '<a href="'.site_url('accountledgers/view/'.$ledger->id).'"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').' </a>';
												 } 										
											if(has_permission(EDIT, 'accounting', 'accountledgers')){ 
											   $action.= '<a href="'.site_url('accountledgers/edit/'.$ledger->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i>'.$this->lang->line('edit').' </a>';
											} 
											 if(has_permission(DELETE, 'accounting', 'accountledgers')){ 
											   $action.= ' <a href="'. site_url('accountledgers/delete/'.$ledger->id).'" onclick="javascript: return confirm('.$this->lang->line('confirm_alert').');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> '. $this->lang->line('delete').' </a>';
											}
$row_data[][]=$action;												
$data[] = $row_data;
				$count++;
				}
			   }
			   else
			   {
				   $ledgers = array();
			   }
					// print_r($ledgers);exit;			
					$i=0;			
					
		//			print_r($ledgers); exit;
					$this->data['accountledgers']=$ledgers;
				}
				  
					$response = array(
			  "draw" => intval($draw),
			  "iTotalRecords" => $totalRecords,
			  "iTotalDisplayRecords" => $totalRecords,
			  "aaData" => $data
			);
			echo json_encode($response);
			exit;
//			print_r($ledgers); exit;
			//$this->data['accountledgers']=$ledgers;
        }
		else{
			$response = array(
			  "draw" => intval($draw),
			  "iTotalRecords" => 0,
			  "iTotalDisplayRecords" => 0,
			  "aaData" => array()
			);
			echo json_encode($response);
			exit;
		}
        }	
	}
  
    public function add() {

        //check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_ledger_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_ledger_data();
				
                $insert_id = $this->accountledgers->insert('account_ledgers', $data);
                if ($insert_id) {
                    // insert detail
					$school = $this->accountledgers->get_school_by_id($data['school_id']);            
        
					$in_arr=array();
					$in_arr['ledger_id']=$insert_id;
					$in_arr['financial_year_id']=$school->financial_year_id;
					$in_arr['opening_balance']=$_POST['opening_balance'];
					$in_arr['opening_cr_dr']=$_POST['opening_cr_dr'];
					
					$in_arr['budget']=$_POST['budget'];
					$in_arr['budget_cr_dr']=$_POST['budget_cr_dr'];
					$in_id = $this->accountledgers->insert('account_ledger_details', $in_arr);
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
              
  		$condition = array();
        if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
            $school_id = $this->session->userdata('school_id');
			$condition['school_id']=$school_id;
			$this->data['accountgroups'] = $this->accountledgers->get_list('account_groups', $condition, '','', '', 'id', 'ASC');
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
                    $in_arr=array();
					$financial_year=$this->accountledgers->get_single('financial_years',array('school_id'=>$data['school_id'],'is_running'=>1));		
					//$in_arr['ledger_id']=$insert_id;					
					$in_arr['opening_balance']=$_POST['opening_balance'];
					$in_arr['opening_cr_dr']=$_POST['opening_cr_dr'];					
					$in_arr['budget']=$_POST['budget'];
					$in_arr['budget_cr_dr']=$_POST['budget_cr_dr'];
					$in_id = $this->accountledgers->update('account_ledger_details', $in_arr, array('ledger_id' => $this->input->post('id'),'financial_year_id'=>$financial_year->id));
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
		 if($school_id!=null){
			 $school=$this->accountledgers->get_single('schools',array('id'=>$school_id));
            $this->data['accountledgers'] = $this->accountledgers->get_accountledger_list( $school_id, $school->academic_year_id);
        }
		if($this->data['accountledger']){
			$ledger_id=$this->data['accountledger']->id;
			$accountledgerdetail = $this->accountledgers->get_single('account_ledger_details', array('ledger_id' =>$ledger_id ,'financial_year_id'=> $school->financial_year_id));
			 $this->data['accountledger']->opening_balance=$accountledgerdetail->opening_balance;
				  $this->data['accountledger']->opening_cr_dr=$accountledgerdetail->opening_cr_dr;
				  $this->data['accountledger']->budget=$accountledgerdetail->budget;
				  $this->data['accountledger']->budget_cr_dr=$accountledgerdetail->budget_cr_dr;
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
		$this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required');   

		$this->form_validation->set_rules('account_group_id', $this->lang->line('account_group_id'), 'trim|required'); 
$this->form_validation->set_rules('opening_cr_dr', $this->lang->line('opening_cr_dr'), 'trim|required'); 		
//$this->form_validation->set_rules('budget_cr_dr', $this->lang->line('budget_cr_dr'), 'trim|required'); 		
    }

            
   
    public function delete_all($school_id)
	{
		$this->accountledgers->delete_all($school_id);
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
		$items[]= 'dr_cr';
		$items[] = 'category';

		
        $data = elements($items, $_POST); 
		$data['dr_cr']=$_POST['opening_cr_dr'];
		$school = $this->accountledgers->get_school_by_id($data['school_id']);            
       /* if(!$school->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('student/index');
        }*/
		//$data['academic_year_id'] = $school->academic_year_id;
		
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
             redirect('accountledgers/index');              
        }
         $ledger = $this->accountledgers->get_single('account_ledgers', array('id' => $id));
        // need to find all child data from database      
        $tables=array('account_transactions','account_transaction_details');
         foreach ($tables as $table) {
			 if($table== 'account_transactions'){
			 $child_exist =$this->accountledgers->get_list($table, array('ledger_id' =>$id), '','', '', 'id', 'ASC');
			 }
			 else{
				$child_exist =$this->accountledgers->get_list($table, array('ledger_id' =>$id), '','', '', 'id', 'ASC'); 
			 }
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('accountledgers/index/'.$ledger->school_id);
            }
                                
        }
		 foreach ($tables as $table) {
			  $this->accountledgers->delete($table, array('ledger_id' => $id));   
		 }
     
        
               
        if ($this->accountledgers->delete('account_ledgers', array('id' => $id))) {
           $this->accountledgers->delete('account_ledger_details', array('ledger_id' => $id));
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('accountledgers/index/'.$ledger->school_id);
    }
	public function view($id= null)
	{
			//$this->load->model('Accounttransactions_Model', 'accounttransactions', true);	
			$ledger = $this->accountledgers->get_ledger_by_id($id);
			// echo $this->db->last_query();
			// debug_a($ledger);

			$school = $this->accountledgers->get_school_by_id($ledger->school_id); 
		   $financial_year=$this->accountledgers->get_single('financial_years',array('school_id'=>$school->id,'is_running'=>1));		
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

		   $this->data['financial_session_year']=$financial_year->session_year;	
		   $this->data['financial_year']=$financial_year;
		  
		   $date=("d-m-Y");
		   $this->load->model('Accounttransactions_Model', 'accounttransactions', true);			
		//    $transactions=$this->accounttransactions->get_transactions_by_ledger_id($id,$f_start,$f_end);
		//    echo "<pre>";
		//    print_r($transactions);
		//    die();
		   $transactions=[];//$this->accounttransactions->get_transactions_by_ledger_id($id,$f_start,$f_end);

		   $voucher_ids=[];
		   $ledger_ids = array($id);
		$ledger_transactions=$this->accountledgers->get_ledger_with_amount_list_with_balance($ledger_ids, $f_start,$f_end,1);
		// echo $this->db->last_query();
		// die();
		 $ledgers = array();	

		 $transations_list_updated = [];
		 $tr_ids = [];
		 $ledger_tr_ids = [];
		 $tr_ids_raw = [];
		 $transaction_amounts = [];
		 $transaction_types = [];
		 $transaction_remarks = [];

		 foreach($ledger_transactions as $ledger_transaction)
		 {
			
			$transactions[] =  $ledger_transaction;
			$tr_ids_raw[] =  $ledger_transaction->transaction_id;
			$transaction_amounts[$ledger_transaction->transaction_id] = $transaction_amounts[$ledger_transaction->transaction_id] ?? 0;
			$transaction_amounts[$ledger_transaction->transaction_id] = $transaction_amounts[$ledger_transaction->transaction_id]+($ledger_transaction->total_amount);
			if(!in_array($ledger_transaction->voucher_id,$voucher_ids)) $voucher_ids[] = $ledger_transaction->voucher_id;
			if($ledger_transaction->cancelled ==1) continue;
			if(!isset($ledgers_transactions_updated[$ledger_transaction->ledger_id]))
			{
				$ledgers_transactions_updated[$ledger_transaction->ledger_id] =  array();
				$ledger_tr_ids[$ledger_transaction->ledger_id] =  array();
			}
			$ledgers_transactions_updated[$ledger_transaction->ledger_id][] =  $ledger_transaction;
			
			if(!in_array($ledger_transaction->id,$tr_ids) )
			{
				$tr_ids[] = $ledger_transaction->id;
			}
		 }
		 
		 $other_transations_list_updated = [];

		 $other_transactions=$this->accountledgers->get_ledger_with_amount_list_with_balance_excluded($ledger_ids,null, $f_start,$f_end,1);

		 foreach($other_transactions as $other_transaction)
		 {
			
			$transactions[] =  $other_transaction;
			$tr_ids_raw[] =  $other_transaction->transaction_id;
			$transaction_amounts[$other_transaction->transaction_id] = $transaction_amounts[$other_transaction->transaction_id] ?? 0;
			$transaction_amounts[$other_transaction->transaction_id] = $transaction_amounts[$other_transaction->transaction_id]+($other_transaction->amount);
			
			if(!in_array($other_transaction->voucher_id,$voucher_ids)) $voucher_ids[] = $other_transaction->voucher_id;
			if($other_transaction->cancelled ==1) continue;
			if(!isset($other_transations_list_updated[$other_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id] =  array();
			}
			if (!isset($ledger_tr_ids[$other_transaction->ledger_id]))
			{
				$ledger_tr_ids[$other_transaction->ledger_id] = array();

			}

			if( !in_array($other_transaction->transaction_id,$ledger_tr_ids[$other_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id][] =  $other_transaction;

			}
			
		 }
		 
		 $vouchers = [];
		 if(!empty($voucher_ids))
		 {
			$voucher_list =$this->accountledgers->get_vouchers_with_ids($voucher_ids);
			foreach( $voucher_list as  $voucher)
			{
				$vouchers[$voucher->id] = $voucher->name;
			}
		 }

		//  var_dump($other_transations_list_updated[$id]);
		//  die();
		 $ledger_id = $id;
				
		$ledger->effective_balance =0;
			$ledger_transactions = isset($ledgers_transactions_updated[$ledger_id]) && $ledgers_transactions_updated[$ledger_id] ? $ledgers_transactions_updated[$ledger_id] :  array();
		
			$grand_total = 0;
			foreach($ledger_transactions as $ledger_transaction){
				$transaction_types[$ledger_transaction->transaction_id] = $ledger_transaction->type;
				$transaction_remarks[$ledger_transaction->transaction_id] = $ledger_transaction->remark;
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
			// debug_a($other_transactions,null,1);
			foreach($other_transactions as $other_transaction){
				$transaction_types[$other_transaction->transaction_id] = $other_transaction->type;
				$transaction_remarks[$other_transaction->transaction_id] = $other_transaction->remark;
				if($other_transaction->transaction_id == 16735)
				{
					// debug_a( $other_transaction);
				}
				if($other_transaction->head_cr_dr == "DR")
				{
					$grand_total= $grand_total+($other_transaction->amount);
				}
				else
				{
					$grand_total= $grand_total-($other_transaction->amount);
				}
			}
			
			if($ledger->opening_cr_dr =='DR'){
				$opening_balance = -($ledger->opening_balance);
				$ledger->effective_balance_cr_dr='DR';
			}
			else{
				$opening_balance = $ledger->opening_balance;
				$ledger->effective_balance_cr_dr='CR';
			}
	
		
			$final_amount=$opening_balance+$grand_total;
		
			$ledger->effective_balance =  $final_amount;
			$transactions_updated = [];
			$transactions_raw = [];
		if(!empty($tr_ids_raw))
		{
			$transactions_raw=$this->accounttransactions->get_transactions_by_tr_ids($tr_ids_raw);
		}
		
		foreach($transactions_raw as $transaction_raw)
		{
			$transaction_raw->amount = $transaction_amounts[$transaction_raw->id] ?? 0;
			$transaction_raw->type = $transaction_types[$transaction_raw->id] ?? $transaction_raw->head_cr_dr ;
			$transaction_raw->remark = $transaction_remarks[$transaction_raw->id] ?? "";
			$transactions_updated[] = $transaction_raw;
		}
		// debug_a($transaction_cr_drs);
		
		 $this->data['transactions']=$transactions_updated;
		 $this->data['vouchers']= $vouchers;
		 //$obalance=$this->accountledgers->get_opening_balance_by_ledger($ledger->id,$ledger->opening_balance,$ledger->opening_cr_dr,$date);
		 //$ledger->opening_balance=$obalance;
		 /*$this->data['voucher'] = $this->voucher->get_voucher_by_id($id);
		 $transactions=$this->transactions->get_transactions_by_voucher_id($id);
		 $i=0;		
		 foreach($transactions as $t){
			 // get total amount of transaction
			 $total_amount=$this->transactions->get_total_amount_by_transaction_id($t->id);
			 $transactions[$i]->total_amount=$total_amount;
			 $i++;
		 }
		 $this->data['transactions']=$transactions;*/
		 $this->data['accountledger'] = $ledger;
		 
		 $this->data['school_info'] = $school; 
		 $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
		 $this->data['edit'] = TRUE;       		
		 $this->layout->title($this->lang->line('ledger')." " .$this->lang->line('detail'). ' | ' . SMS);
		 $this->layout->view('accountledgers/view', $this->data);

	}
	public function view_bkp($id= null){
		//$this->load->model('Accounttransactions_Model', 'accounttransactions', true);	
		$ledger = $this->accountledgers->get_ledger_by_id($id);
		 $school = $this->accountledgers->get_school_by_id($ledger->school_id); 
		$financial_year=$this->accountledgers->get_single('financial_years',array('school_id'=>$school->id,'is_running'=>1));		
		if(strpos($financial_year->session_year,"->"))	
        {
            $arr=explode("->",$financial_year->session_year);
            $f_start=date("Y-m-d",strtotime($arr[0]));		
            $f_end=date("Y-m-d",strtotime($arr[1]));	
        }
        else
        {
            $arr=explode("-",$financial_year->session_year);
            $f_start=date("Y-m-d",strtotime("1 ".$arr[0]));		
            $f_end=date("Y-m-d",strtotime("31 ".$arr[1]));	
        }	
		$this->data['financial_year_start'] =$f_start;
		$this->data['financial_year_end'] =$f_end;
		$this->data['financial_session_year']=$financial_year->session_year;	
		$this->data['financial_year']=$financial_year;
		$cbalance=$this->accountledgers->get_current_balance_by_ledger($ledger->id,$ledger->opening_balance,$ledger->opening_cr_dr);
		$ledger->current_balance=$cbalance;
		
		$ebalance=$this->accountledgers->get_effective_balance_by_ledger($ledger->id,$ledger->opening_balance,$ledger->opening_cr_dr);
		$ledger->effective_balance=$ebalance;
		$date=("d-m-Y");
		$this->load->model('Accounttransactions_Model', 'accounttransactions', true);			
		$transactions=$this->accounttransactions->get_transactions_by_ledger_id($id,$f_start,$f_end);
		
		$this->data['transactions']=$transactions;
		//$obalance=$this->accountledgers->get_opening_balance_by_ledger($ledger->id,$ledger->opening_balance,$ledger->opening_cr_dr,$date);
		//$ledger->opening_balance=$obalance;
		/*$this->data['voucher'] = $this->voucher->get_voucher_by_id($id);
		$transactions=$this->transactions->get_transactions_by_voucher_id($id);
		$i=0;		
		foreach($transactions as $t){
			// get total amount of transaction
			$total_amount=$this->transactions->get_total_amount_by_transaction_id($t->id);
			$transactions[$i]->total_amount=$total_amount;
			$i++;
		}
		$this->data['transactions']=$transactions;*/
		$this->data['accountledger'] = $ledger;
		
		$this->data['school_info'] = $school; 
		$this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('ledger')." " .$this->lang->line('detail'). ' | ' . SMS);
        $this->layout->view('accountledgers/view', $this->data);
	}
	public function delete_multiple(){
				
		if(!empty($_POST['checkId'])){
			foreach($_POST['checkId'] as $lid){
				$tables=array('account_transactions','account_transaction_details');
				 foreach ($tables as $table) {
					  if($table== 'account_transactions'){
			 $child_exist =$this->accountledgers->get_list($table, array('ledger_id' =>$id,'cancelled'=>0), '','', '', 'id', 'ASC');
			 }
			 else{
				$child_exist =$this->accountledgers->get_list($table, array('ledger_id' =>$id), '','', '', 'id', 'ASC'); 
			 }					
						if(!empty($child_exist)){
							 error($this->lang->line('pls_remove_child_data'));
							 redirect('accountledgers/index');
						}
					//$this->accountledgers->delete($table, array('ledger_id' => $lid));                        
				}
				foreach ($tables as $table) {
					$this->accountledgers->delete($table, array('ledger_id' => $lid));
									
				}					
				$this->accountledgers->delete('account_ledgers', array('id' => $lid));
				$this->accountledgers->delete('account_ledger_details', array('ledger_id' => $lid));				
			}
		}		
		 success($this->lang->line('delete_success'));
         redirect('accountledgers/index/'.$_POST['sc_id']);
		exit;
	}

	public function fix_defualt(){
				
		$this->db->select('AL.*,');
        $this->db->from('account_ledgers AS AL');
		
		$this->db->where('AL.school_id', 0); 
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	

		$ledgers=$this->db->get();
		
		$ledgers = $ledgers->result(); 	
		foreach($ledgers as $l){
			$larr=array();
			$larr['school_id']=$school_id;
			$larr['name']=$l->name;
			$larr['account_group_id']=$l->account_group_id;
			$larr['dr_cr']=$l->dr_cr;
			$larr['ledger_uid']=$l->ledger_uid;

			$updated = $this->accountledgers->update('account_ledgers', array("category" => $l->category),$larr);
			
		}
	}

}
