<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transactions extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Accounttransactions_Model', 'transactions', true);			
		$this->load->model('Accountledgers_Model', 'ledgers', true);					
    }
	

     /*public function index($school_id = null) {
       
        //check_permission(VIEW);         
        //$this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');
		$this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list($school_id);
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		
        $this->data['themes'] = $this->accountgroups->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_account_groups'). ' | ' . SMS);
        $this->layout->view('accountgroups/index', $this->data);            
       
    }*/
  
  
	function create($voucher_id = "",$recipt='')
	{
		if ($_POST) {
			//print_r($_POST); exit;
			$edited_ledger_ids = [];
           // $this->_prepare_transaction_validation();
			// Generate transaction no
			$voucher = $this->transactions->get_single('vouchers', array('id' => $_POST['voucher_id']));
			$led_arr=array();
					
			if($voucher->type_id ==1 && count($_POST['ledger_id']) >1){				
				$led_arr=$_POST['ledger_id'];	
				
				$flag=0;	
				for($j=0;$j<count($led_arr); $j++){	
					$ledger_id=$led_arr[$j];

					$edited_ledger_ids[]	 = $ledger_id;
				//foreach($led_arr as $ledger_id){ 
				if($ledger_id != ''){
				$data=array();
				$school_id=$voucher->school_id;
				$data['transaction_no']=$this->transactions->generate_transaction_no($_POST['voucher_id']);
				$data['voucher_id']=$_POST['voucher_id'];
				$data['ledger_id']=$ledger_id;
				$data['head_cr_dr']=$_POST['head_cr_dr'];
				$data['reciever_name']=$_POST['reciever_name'];
				$data['date']=date('Y-m-d H:i:s',strtotime($_POST['date']));				
				$data['narration']=$_POST['narration'];
				$data['created']=date('Y-m-d H:i:s');
				$data['created_by'] = logged_in_user_id();				
			   // if ($this->form_validation->run() === TRUE) {	
				if(isset($data['voucher_id'])){
					//$data = $this->_get_posted_transaction_data();
					$insert_id = $this->transactions->insert('account_transactions', $data);
					if ($insert_id) {
						// add details to transaction details
						for($i=0;$i<count($_POST['pledger']); $i++){
							if($_POST['pledger'][$i] !='' && $_POST['pamount'][$i]!= ''){
								$detail=array();
								$detail['transaction_id']=$insert_id;
								$detail['ledger_id']=$_POST['pledger'][$i];
								$edited_ledger_ids[]	 = $detail['ledger_id'];
									$detail['amount']=$_POST['headamount'][$j];
									$detail['remark']=$_POST['headremark'][$j];
									$detail['created']=date('Y-m-d H:i:s');									
								$detail_id=$this->transactions->insert('account_transaction_details', $detail);
							}
						}                   
						$flag=1;
						
					} else {
						$flag=0;
					}
				} else {
					$this->data = $_POST;
				}
			   }
			   }
			}
			else{	

				if($voucher->type_id ==1 && is_array($_POST['ledger_id'])){				
					$ledger_id=$_POST['ledger_id'][0];
				}
				else{					
					$ledger_id=$_POST['ledger_id'];
				}				
				$data=array();
				$edited_ledger_ids[]	 = $ledger_id;

				$data['transaction_no']=$this->transactions->generate_transaction_no($_POST['voucher_id']);
				$data['voucher_id']=$_POST['voucher_id'];
				$data['ledger_id']=$ledger_id;
				$data['head_cr_dr']=$_POST['head_cr_dr'];
				$data['date']=date('Y-m-d H:i:s',strtotime($_POST['date']));
				$data['narration']=$_POST['narration'];
				$data['reciever_name']=$_POST['reciever_name'];
				$data['created']=date('Y-m-d H:i:s');				
			   // if ($this->form_validation->run() === TRUE) {	
				if(isset($data['voucher_id'])){
					//$data = $this->_get_posted_transaction_data();
					
					$insert_id = $this->transactions->insert('account_transactions', $data);
					if ($insert_id) {
						// add details to transaction details
						for($i=0;$i<count($_POST['pledger']); $i++){
							if($_POST['pledger'][$i] !='' && $_POST['pamount'][$i]!= ''){
								$detail=array();
								
								$detail['transaction_id']=$insert_id;
								$detail['ledger_id']=$_POST['pledger'][$i];
								$edited_ledger_ids[]	 = $detail['ledger_id'];

								$detail['amount']=$_POST['pamount'][$i];
								$detail['remark']=$_POST['premark'][$i];
								$detail['created']=date('Y-m-d H:i:s');								
								$detail_id=$this->transactions->insert('account_transaction_details', $detail);
							}
						}
						//create_log('Has been created a school : '.$data['school_name']);  
						if(!empty($edited_ledger_ids))
						{
							update_ledger_opening_balance($edited_ledger_ids,$voucher->school_id);
						}
						success($this->lang->line('insert_success'));
						redirect('transactions/view/'.$insert_id);
					} else {
						error($this->lang->line('insert_failed'));
						redirect('transactions/create/'.$_POST['voucher_id']);
					}
				} else {
					$this->data = $_POST;
				}
			}
			
			/*if($voucher->type_id ==1 && is_array($_POST['ledger_id'])){				
				$led_arr=$_POST['ledger_id'];				
			}
			else{				
				$led_arr[0]=$_POST['ledger_id'];
			}*/
			
		   if($flag==1){
			if(!empty($edited_ledger_ids))
			{
				update_ledger_opening_balance($edited_ledger_ids,$voucher->school_id);
			}
			   success($this->lang->line('insert_success'));
                   redirect('transactions/create/'.$_POST['voucher_id']);
		   }
		   else{
			  error($this->lang->line('insert_failed'));
                    redirect('transactions/create/'.$_POST['voucher_id']); 
		   }
        }

		$voucher = $this->transactions->get_single('vouchers', array('id' => $voucher_id));
		$res=$this->transactions->get_last_transaction_entry_by_voucher( $voucher_id);

		$financial_year=$this->transactions->get_single('financial_years',array('school_id'=>$voucher->school_id,'is_running'=>1));	
		$previous_financial_year=$this->transactions->get_single('financial_years',array('previous_financial_year_id'=>$financial_year->id,'school_id'=>$voucher->school_id));	
		
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
		if($voucher->is_readonly == 1){
			error('Read only voucher.');
                    redirect('vouchers/index/');
		}
		// check if financial year
		$currentDate = date('Y-m-d');
		// if (($currentDate >= $f_start) && ($currentDate <= $f_end)){ 

		// }
		// else{
		// 	error('Invalid financial year..');
        //     redirect('vouchers/index/');
		// }
		//$voucher_last_entry=$this->transactions->get_last_transaction_entry_by_voucher($voucher_id);
		/*if(empty($voucher_last_entry)){
			$startDate=date('m/d/Y',strtotime($f_start));
		}
		else{
			$startDate=date('m/d/Y',strtotime($voucher_last_entry->date));*/
			//if($f_start > date('Y-m-d',strtotime($startDate)) ){
				//$startDate=date('m/d/Y',strtotime($f_start));
		if(date('Y',strtotime($f_start))== 2023)
		{
			// $this->data['iOnlyAllowCurrentDate'] =1;
			$this->data['allow_till_current_date'] =1;

		}

		$this->data['previous_financial_year_activate'] = 0;
		if(date('Y',strtotime($f_start))< date('Y'))
		{
			$this->data['previous_financial_year_activate'] = 1;

		}
		
				$startDate=date('d/m/Y',strtotime($f_start));
				$endDate=date('d/m/Y',strtotime($f_end));
			// }
		//}
		if(!empty($res)){
			$last_entry_date=$res->date;
		}		
		else
		{
			$last_entry_date = "";
		}		
		// $this->data['voucher_start_date']= $last_entry_date ?	date('d/m/Y',strtotime($last_entry_date)) : $startDate;
		$this->data['voucher_start_date']= $startDate;

		if(!empty($previous_financial_year))
		{
			$this->data['voucher_start_date']=$endDate;
		}
		$this->data['voucher_end_date']=$endDate;
		$this->data['voucher']=$voucher;
		 if($this->data['voucher']->is_readonly == 1){
			 error('You can not add entry to readonly voucher.');
                    redirect('vouchers/view/'.$voucher_id);
		 }
		 $school_id=$this->data['voucher']->school_id;
		 $financial_year_id=$this->data['voucher']->financial_year_id;
		 $ledgers_list = $this->ledgers->get_accountledger_list($school_id,$financial_year->id);
		//  echo $this->db->last_query();
		//  debug_a($ledgers_list);

		 $ledger_ids = array();
		 $ledgers_list_updated = [];
		 foreach($ledgers_list as $ledger)
		 {
			$ledgers_list_updated[$ledger->id] = $ledger;
			$ledger_ids[] = $ledger->id;
		 }
		
		//  $ledger_transactions=$this->ledgers->get_ledger_with_amount_list_with_balance($ledger_ids, $f_start,$f_end);
	
		//  $ledgers = array();	
		//  $transations_list_updated = [];
		//  $tr_ids = [];
		//  $ledger_tr_ids = [];
		//  foreach($ledger_transactions as $ledger_transaction)
		//  {
		// 	if(!isset($ledgers_transactions_updated[$ledger_transaction->ledger_id]))
		// 	{
		// 		$ledgers_transactions_updated[$ledger_transaction->ledger_id] =  array();
		// 		$ledger_tr_ids[$ledger_transaction->ledger_id] =  array();
		// 	}
		// 	$ledgers_transactions_updated[$ledger_transaction->ledger_id][] =  $ledger_transaction;
			
		// 	if(!in_array($ledger_transaction->id,$tr_ids) )
		// 	{
		// 		$tr_ids[] = $ledger_transaction->id;
		// 	}
		//  }
		 
		//  $other_transations_list_updated = [];

		//  $other_transactions=$this->ledgers->get_ledger_with_amount_list_with_balance_excluded($ledger_ids,null, $f_start,$f_end);
		
				
		//  foreach($other_transactions as $other_transaction)
		//  {
		// 	if(!isset($other_transations_list_updated[$other_transaction->ledger_id]))
		// 	{
		// 		$other_transations_list_updated[$other_transaction->ledger_id] =  array();
		// 	}
		// 	if (!isset($ledger_tr_ids[$ledger_transaction->ledger_id]))
		// 	{
		// 		$other_transations_list_updated[$other_transaction->ledger_id] = array();

		// 	}

		// 	if( !in_array($other_transaction->transaction_id,$ledger_tr_ids[$ledger_transaction->ledger_id]))
		// 	{
		// 		$other_transations_list_updated[$other_transaction->ledger_id][] =  $other_transaction;

		// 	}
			
		//  }

		 
		 foreach($ledgers_list_updated as $ledger_id => $ledger)
		 {
			if(!isset($ledgers[$ledger_id]))
			{
			
				$ledgers[$ledger_id] = $ledger;
				
				$ledgers[$ledger_id]->effective_balance =0;
			}
			// $ledger_transactions = isset($ledgers_transactions_updated[$ledger_id]) && $ledgers_transactions_updated[$ledger_id] ? $ledgers_transactions_updated[$ledger_id] :  array();
			// // echo "<pre>";
			
			// $grand_total = 0;
			// foreach($ledger_transactions as $ledger_transaction){
				
			// 	if($ledger_transaction->head_cr_dr == "DR")
			// 	{
			// 		$grand_total= $grand_total-($ledger_transaction->total_amount);
			// 	}
			// 	else
			// 	{
			// 		$grand_total= $grand_total+($ledger_transaction->total_amount);
			// 	}
				
			// }
			
			// // if($ledger_id == 14669)
			// // {
			// // 	echo "<pre>";
			// // 	//echo $this->db->last_query();
			// // 	var_dump($grand_total);
			// // 	die();
			// // }
			// $other_transactions = isset($other_transations_list_updated[$ledger_id]) && $other_transations_list_updated[$ledger_id] ? $other_transations_list_updated[$ledger_id] :  array();
			
			// foreach($other_transactions as $other_transaction){
				
			// 	if($other_transaction->head_cr_dr == "DR")
			// 	{
			// 		$grand_total= $grand_total+($other_transaction->amount);
			// 	}
			// 	else
			// 	{
			// 		$grand_total= $grand_total-($other_transaction->amount);
			// 	}
			// }

			// if($ledgers[$ledger_id]->opening_cr_dr =='DR'){
			// 	$opening_balance = -($ledger->opening_balance);
			// 	$ledgers[$ledger_id]->effective_balance_cr_dr='DR';
			// }
			// else{
			// 	$opening_balance = $ledger->opening_balance;
				
			// }
	
		
			// $final_amount=$opening_balance+$grand_total;
			// if($ledger_id == 14669)
			// {
			// 	// echo "<pre>";
			// 	// echo $this->db->last_query();
			// 	// var_dump($other_transactions);
			// 	// die();
			// }
			// if($final_amount > 0)
			// {
			// 	$ledgers[$ledger_id]->effective_balance_cr_dr='CR';
			// }
			// else
			// {
			// 	$ledgers[$ledger_id]->effective_balance_cr_dr='DR';
			// }
			// $ledgers[$ledger_id]->effective_balance =  abs($final_amount);
		 }
		//  echo "<pre>";
		//  var_dump($ledgers);
			
		 
		//  debug_a($ledgers);
		$this->data['todayDate'] = date("d-m-Y");
		$this->data['ledgers'] = $ledgers;
        $this->data['themes'] = $this->transactions->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->data['recipt'] = $recipt;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('entry'). ' | ' . SMS);
        $this->layout->view('transactions/create', $this->data);
	}
	/*public function generate_transaction_no($voucher_id){
		$transaction_no=$voucher_id.uniqid();
		return $transaction_no;
	}*/
	// public  function get_ledger_with_amount()
	// {
	// 	$school_id  = $this->input->post('school_id');
	// 	$financial_year_id  = $this->input->post('financial_year_id');
	// 	$ledgers=$this->ledgers->get_ledger_with_amount_list($school_id,$financial_year_id);		 
	// 	 $i=0;
	// 	 $html = "";
	// 	 foreach($ledgers as $l){
	// 		if(in_array($voucher->type_id,array(3,5,4))){
	// 			if($ledger->group_name != 'Cash-in-hand' && $ledger->base_id!=5){
	// 				continue;
	// 			}
	// 		}
	// 		 $ebalance=$this->ledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
	// 		 if($l->dr_cr =='DR'){
	// 			 $ledgers[$i]->effective_balance_cr_dr='DR';
	// 			 $html =+ "<option value>"
	// 		 }
	// 		 else{
	// 			 $ledgers[$i]->effective_balance_cr_dr='CR';
	// 		 }
	// 		 $ledgers[$i]->effective_balance=abs($ebalance);
	// 		 $i++;
	// 	 }
	// 	 $this->data['ledgers'] = $ledgers;

	// }
	public function view($transaction_id=null){
	
		$transaction=$this->transactions->get_transactions_by_id($transaction_id);
		$financial_year=$this->transactions->get_single('financial_years',array('school_id'=>$transaction->v_school_id,'is_running'=>1));		
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
		$prev_transaction=$this->transactions->get_prev_transaction_id($transaction_id,$transaction->voucher_id,$f_start, $f_end);	
		$next_transaction=$this->transactions->get_next_transaction_id($transaction_id,$transaction->voucher_id,$f_start, $f_end);	
		// echo $this->db->last_query();
		// die();
		$transaction->next_id = isset($next_transaction->next_id) && $next_transaction->next_id ? $next_transaction->next_id : null;
		$transaction->prev_id = isset($prev_transaction->prev_id) && $prev_transaction->prev_id ? $prev_transaction->prev_id : null;
		$transaction->total_amount = $this->transactions->get_total_amount_by_transaction_id($transaction_id);	
		
		$this->data['transaction']=$transaction;
		$this->data['transaction_detail']=$this->transactions->get_transaction_detail($transaction_id);
		$school = $this->transactions->get_school_by_id($this->data['transaction']->school_id);
		$this->data['school_info'] = $school; 		
		 $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('entry'). ' | ' . SMS);		 
        $this->layout->view('transactions/view', $this->data);
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
            $this->_prepare_group_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_group_data();
                $updated = $this->accountgroups->update('account_groups', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('accountgroups');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('accountgroups/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['accountgroup'] = $this->accountgroups->get_single('account_groups', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['accountgroup'] = $this->accountgroups->get_single('account_groups', array('id' => $id));
				
                if (!$this->data['accountgroup']) {
                     redirect('accountgroups');
                }
            }
        }
		$this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['school_id'] = $this->data['accountgroup']->school_id;
        $this->data['filter_school_id'] = $this->data['accountgroup']->school_id;   
		$this->data['schools'] = $this->schools;
       // $this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');
	   $this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		        
        $this->data['themes'] = $this->accountgroups->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('category'). ' | ' . SMS);
        $this->layout->view('accountgroups/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_group_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      
	  $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_group_name');
		$this->form_validation->set_rules('type_id', $this->lang->line('type'), 'trim|required');       
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function group_name() {
        if ($this->input->post('id') == '') {
            $accountgroup = $this->accountgroups->duplicate_check($this->input->post('account_groups'));
            if ($accountgroup) {
                $this->form_validation->set_message('account_groups', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $accountgroup = $this->accountgroups->duplicate_check($this->input->post('account_groups'), $this->input->post('id'));
            if ($accountgroup) {
                $this->form_validation->set_message('account_groups', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
    
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_group_data() {

        $items = array();
               
		$items[] = 'school_id';
        $items[] = 'name'; 
		$items[] = 'type_id'; 		
		$items[] = 'base_id'; 		
		
        $data = elements($items, $_POST); 
		if(isset($_POST['is_primary'])){
			$data['is_primary']=$_POST['is_primary'];
		}
		else{
			$data['is_primary']=0;
		}	
		if(isset($_POST['is_readonly'])){
			$data['is_readonly']=$_POST['is_readonly'];
		}
		else{
			$data['is_readonly']=0;
		}		
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
	public function revert($id = null) {        
        
       // check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('dashboard');              
		}
		$this->transactions->revert_transaction($id);
		redirect('transactions/view/'.$id);
	}

}
