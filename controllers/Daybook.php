<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Daybook extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();		
		$this->load->model('Accountledgers_Model', 'accountledgers', true);		
		$this->load->model('Accounttransactions_Model', 'transactions', true);						
    }

     public function index($school_id = null) {
		if(!$school_id && $this->input->post('school_id')){
			$school_id=$this->input->post('school_id');
		}
		if(!$school_id && ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)){
            $school_id = $this->session->userdata('school_id');
        }               
       if($school_id)
	   {
			$financial_year=$this->accountledgers->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));		
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
       // for super admin 
	   $start_date=date('d-m-Y');
	   $end_date= "";
	   $order_by = "";
	   $order_dir = "";
	   $limit = 50;	
	   if ($_POST) {		
			$start = 0;
			if(isset($_POST['start_date']) && $_POST['start_date']!=''){
				$start_date=$_POST['start_date'];
			}
			if(isset($_POST['end_date']) && $_POST['end_date']!=''){
				$end_date=$_POST['end_date'];
			}
			
		   if($this->input->post('category')){
			   $category=$this->input->post('category');			   			   
		   }	
		   $order_by = $this->input->post('order_by');
		   $order_dir =  $this->input->post('order_dir');
		}
		
		$this->data['start_date']=$start_date;
		$this->data['end_date']=$end_date;
		$this->data['limit']=$limit;
		$this->data['order_by']=$order_by;
		$this->data['order_dir']=$order_dir;
      
        $school = $this->accountledgers->get_school_by_id($school_id);
		if(empty($_POST) && !$category){		
            $category = $school->category;
        }
          $this->data['school_info'] = $school;
		
        $this->data['filter_school_id'] = $school_id;
		$this->data['category'] = $category;
       
		$i=0;
        if($school_id){
			// get transactionsdate
			if($end_date)
			{
				$transactions_count=$this->transactions->get_transactions_by_range_count($school_id,$category,$start_date,$end_date);
				$transactions=$this->transactions->get_transactions_by_range($school_id,$category,$start_date,$end_date,$start,$limit,$order_by, $order_dir);
			}
			else
			{
				$transactions=$this->transactions->get_transactions_by_date($school_id,$start_date,$category,$start,$limit,$order_by, $order_dir);
				$transactions_count=$this->transactions->get_transactions_by_date_count($school_id,$start_date,$category);
			}
			error_on();
			//echo $this->db->last_query();
			
			$this->data['transactions_count'] = $transactions_count; 
			$invoice_ids = [];
			$invoice_numbers = [];
			$reciept_numbers = [];
			$salary_payments = [];
			$reciept_ids = [];
			$payroll_ids = [];
			$inventory_invoice_numbers = []; 
			$inventory_ids = []; 
			$inventory_url = [];

			$this->data['offset'] = 1;    
			if(!empty($transactions)){
				$index=0;		
				foreach($transactions as $t){
					// get total amount of transaction
					if($t->invoice_id ?? false) {
						$invoice_ids[] = $t->invoice_id;
					}
					if($t->donation_id ?? false) {
						$reciept_ids[] = $t->donation_id;
					}
					if($t->salary_payment_id ?? false) {
						$payroll_ids[] = $t->salary_payment_id;
					}
					if($t->inventory_id ?? false) {
						$inventory_ids[] = $t->inventory_id;
					}
					$tr_ids[] = $t->id;
				}	
				$total_amounts = [];
				if(!empty($invoice_ids)) {

					$invoice_number_raw = $this->transactions->get_invoice_numbers($invoice_ids);
					foreach ($invoice_number_raw as $invoice_number) {
						$invoice_numbers[$invoice_number->id] = $invoice_number->custom_invoice_id;
					}
				}
				if(!empty($payroll_ids)) {

					$invoice_number_raw = $this->transactions->get_payroll_invoice_numbers($payroll_ids);
					foreach ($invoice_number_raw as $invoice_number) {
						$salary_payments[$invoice_number->id] = $invoice_number->invoice_no;
					}
				}
				if(!empty($reciept_ids)) {
					$reciept_number_raw=$this->transactions->get_reciept_numbers($reciept_ids);
					foreach ($reciept_number_raw as $reciept_number) {
						$reciept_numbers[$reciept_number->id] = $reciept_number->reciept_no;
					}
				}
				if(!empty($inventory_ids)) {
					$inventory_invoices = $this->transactions->get_inventory_invoice_numbers($inventory_ids);
					foreach ($inventory_invoices as $inventory_invoice) {
						$inventory_invoice_numbers[$inventory_invoice->id] = $inventory_invoice->invoice_no;
						if($inventory_invoice->invoice_type == "item_issue") {
							$inventory_url[$inventory_invoice->id] = str_replace("_","",$inventory_invoice->invoice_type);//"issueitem";
						} else {
							$inventory_url[$inventory_invoice->id] = str_replace("_","",$inventory_invoice->invoice_type);//"issueitem";
						}
						
					}
				}
				
				$total_amounts_raw=$this->transactions->get_total_amount_by_transaction_ids($tr_ids);
				foreach($total_amounts_raw as $total_amount_raw){
					// get total amount of transaction
					$tr_ids[] = $t->id;
					$total_amounts[$total_amount_raw->transaction_id] = $total_amount_raw->total_amount;
				}	
				foreach($transactions as $t){
					// get transaction details
					$transactions[$index]->detail=$this->transactions->get_transaction_detail($t->id);
					// get total amount of transaction
					$total_amount=isset($total_amounts[$t->id]) ?  $total_amounts[$t->id] : 0;
					$transactions[$index]->total_amount=$total_amount;
					$index++;
				}
			}
			// var_dump($transactions);
			// var_dump($transactions_count);
			// die();
			$this->data['transactions']=$transactions;
			$this->data['invoice_numbers']=$invoice_numbers;
			$this->data['reciept_numbers']=$reciept_numbers;
			$this->data['salary_payments']=$salary_payments;
			$this->data['inventory_invoices']=$inventory_invoice_numbers;
			$this->data['inventory_url']=$inventory_url;
			// end				
        }
		// debug_a($transactions,"Others");

		$this->data['schools'] = $this->schools;        
        $this->data['themes'] = $this->accountledgers->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('daybook'). ' | ' . SMS);
        $this->layout->view('daybook/dev', $this->data);            
       
    }
	public function get_json_data() {
		// for super admin 
		$start_date=date('d-m-Y');
		$end_date= "";
		$this->data['exceeded']=false;	
		$this->data['loading_completed']=false;	
		$total_debit = $total_credit = 0;;
		 if ($_POST) {		
			 $start = 1;
			 $limit = 50;	
			 if(isset($_POST['start']) && $_POST['start']!=''){
				$start=$_POST['start'];
			}
			if(isset($_POST['offset']) && $_POST['offset']!=''){
				$offset=$_POST['offset'];
			}
			 if(isset($_POST['start_date']) && $_POST['start_date']!=''){
				 $start_date=$_POST['start_date'];
			 }
			 if(isset($_POST['end_date']) && $_POST['end_date']!=''){
				 $end_date=$_POST['end_date'];
			 }
			 if($this->input->post('school_id')){
				$school_id=$this->input->post('school_id');
			}
			if($this->input->post('category')){
				$category=$this->input->post('category');			   			   
			}	
			if($this->input->post('transcation_count')){
				$transcation_count=$this->input->post('transcation_count');			   			   
			}	
			$offset++;
			$this->data['offset'] = $offset;   
			$start = ($offset) *$limit;
			$loading_exceeded =  $start > $transcation_count ? true : false;
			$loading_completed = ( $start + $limit) >= $transcation_count ? true : false;
			
		 }
		 if($loading_exceeded)
		 {
			 echo json_encode(array("exceeded"=>true));
			 die();
		 }
		 if($loading_completed)
		 {
			$this->data['loading_completed'] = true;  
		 }
		 if(!$school_id && ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)){
			 $school_id = $this->session->userdata('school_id');
		 }               
		
		 $school = $this->accountledgers->get_school_by_id($school_id);
		
		 $i=0;
		 if($school_id){
			 // get transactionsdate
			 if($end_date)
			 {
				 $transactions=$this->transactions->get_transactions_by_range($school_id,$category,$start_date,$end_date,$start,$limit);
			 }
			 else
			 {
				 $transactions=$this->transactions->get_transactions_by_date($school_id,$start_date,$category,$start,$limit);
 			 }
			
			 if(!empty($transactions)){
				 $index=0;	
				 foreach($transactions as $t){
					// get total amount of transaction
					$tr_ids[] = $t->id;
				}	
				$total_amounts = [];
				$total_amounts_raw=$this->transactions->get_total_amount_by_transaction_ids($tr_ids);
				foreach($total_amounts_raw as $total_amount_raw){
					// get total amount of transaction
					$tr_ids[] = $t->id;
					$total_amounts[$total_amount_raw->transaction_id] = $total_amount_raw->total_amount;
				}		
				 foreach($transactions as $t){
					 // get transaction details
					 $transactions[$index]->detail=$this->transactions->get_transaction_detail($t->id);
					 // get total amount of transaction
					 $total_amount=isset($total_amounts[$t->id]) ?  $total_amounts[$t->id] : 0;
					 $transactions[$index]->total_amount=$total_amount;
					 $index++;
				 }
			 }
			 
			
			 if($loading_completed)
		 {
			  $result=array();
			  $agroup=$this->accountledgers->get_single_new('account_groups', array('school_id'=>$school_id,'name'=>'Cash-in-hand'), '','', '', 'id', 'ASC');			 
			  $ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$agroup->id);			
			  $j=0;
			  foreach($ledgers as $l){	
				if($end_date)
				{
					 $ledgers[$j]->opening_balance_date=$this->accountledgers->get_opening_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
				 	$ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
				}
				else
				{
					$ledgers[$j]->opening_balance_date=$this->accountledgers->get_opening_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
				 	$ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
				}
				 $j++;
			  }			 
			  $result['cash']=$ledgers;									    			 
			 $agroup=$this->accountledgers->get_list_new('account_groups', array('school_id'=>$school_id,'base_id'=>'5'), '','', '', 'id', 'ASC');			
			 foreach($agroup as $ag){			  
				 $ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$ag->id);
				 $j=0;			
				 foreach($ledgers as $l){	
					if($end_date)
					{		
						$ledgers[$j]->opening_balance_date=$this->accountledgers->get_opening_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
						$ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
					}
					else
					{
						$ledgers[$j]->opening_balance_date=$this->accountledgers->get_opening_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
						$ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
					}
					 $j++;
				 }
				 
				 $result['bank'][$i]=$ledgers;				
				 $i++;
			 }
			 $this->data['result']=$result;			
			}
			 $html = "";
			 if(!empty($transactions)){
				foreach($transactions as $t){
				
					
					if($t->total_amount > 0){
					if($t->head_cr_dr == 'DR') {
						$total_debit += $t->total_amount;
					}
					else if($t->head_cr_dr == 'CR'){ 
						$total_credit += $t->total_amount;
					}
				$html .= '<tr>
					<td>'.date('d-m-Y',strtotime($t->date)).'</td>
					<td style="width:30%;"><strong>'. $t->ledger_name.'</strong></td>
					<td>'.$t->voucher_name.' ('.$t->voucher_category.')</td>											
					<td><a href="'.site_url("transactions/view/".$t->id).'">'.$t->transaction_no.'</a></td>
					<td align="right"><strong>';
					if($t->head_cr_dr == 'DR') $html .= number_format($t->total_amount,2); 
					else $html .=  "&nbsp;";
					$html .= '</strong></td><td align="right"><strong>';
					 if($t->head_cr_dr == 'CR') $html .= number_format($t->total_amount,2); 
                     else
					 $html .= "&nbsp;";
					$html .= ' </strong></td></tr>';
				 foreach($t->detail as $d){ 
				if($t->head_cr_dr == 'CR'){
					$total_debit += $d->amount;
				}
				else if($t->head_cr_dr == 'DR'){
					$total_credit += $d->amount;
				}
				$html .= '
				<tr>
					<td>&nbsp;</td>
					<td style="width:30%;">  &nbsp;&nbsp;&nbsp; - < '.$d->ledger_name.'<br />';

					 if($d->remark) {
					 $html .= ' &nbsp;&nbsp;&nbsp; <strong>Remark: </strong>'.$d->remark ;
					}
					$html .= '</td>
					<td>&nbsp;</td>											
					<td>&nbsp;</td>
					<td align="right">';
					
					if($t->head_cr_dr == 'CR') $html .= number_format($d->amount,2); 
					else $html .= "&nbsp;";
					$html .= '
					</td>
					<td align="right">';
					if($t->head_cr_dr == 'DR') {
						$html .= "&nbsp;";
						$html .=  number_format($d->amount,2); 
					}
					else $html .= "&nbsp;";
					$html .=  "&nbsp;";
					$html .= '</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td style="width:30%;"><strong>Narration : </strong>'.$t->narration.'</td>
					<td>&nbsp;</td>											
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					
				</tr>';
			 } } }	
				 
		 }
		 $this->data['html'] = $html;
		 $this->data['total_debit'] = $total_debit;
		 $this->data['total_credit'] = $total_credit;
		 echo json_encode($this->data);
		 die();
		
	 }
	}
    public function get_summery() {

         if(isset($_POST['start_date']) && $_POST['start_date']!=''){
             $start_date=$_POST['start_date'];
         }
         if(isset($_POST['end_date']) && $_POST['end_date']!=''){
             $end_date=$_POST['end_date'];
         }
         if($this->input->post('school_id')){
            $school_id=$this->input->post('school_id');
        }
        if($this->input->post('category')){
            $category=$this->input->post('category');			   			   
        }	
        if($school_id && $start_date &&  $category)
        {
            $result=array();
			$school = $this->accountledgers->get_school_by_id($school_id);
			$agroup=$this->accountledgers->get_single_new('account_groups', array('school_id'=>$school_id,'name'=>'Cash-in-hand'), '','', '', 'id', 'ASC');			 
			$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$agroup->id);			
			$j=0;
			foreach($ledgers as $l){	
			  if($end_date)
			  {
				   $ledgers[$j]->opening_balance_date=$this->accountledgers->get_opening_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category,$end_date);
                 
				   $ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category,$end_date);
			  }
			  else
			  {
				  $ledgers[$j]->opening_balance_date=$this->accountledgers->get_opening_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
				   $ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
			  }
			   $j++;
			}			 
			$result['cash']=$ledgers;									    			 
		   $agroup=$this->accountledgers->get_list_new('account_groups', array('school_id'=>$school_id,'base_id'=>'5'), '','', '', 'id', 'ASC');			
		   $i = 0;  
		   foreach($agroup as $ag){			  
			   $ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->financial_year_id,$ag->id);
			   $j=0;			
			   foreach($ledgers as $l){	
				  if($end_date)
				  {		
					  $ledgers[$j]->opening_balance_date=$this->accountledgers-> get_opening_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category,$end_date);
                    
					  $ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category,$end_date);
				  }
				  else
				  {
					  $ledgers[$j]->opening_balance_date=$this->accountledgers->get_opening_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
					  $ledgers[$j]->closing_balance_date=$this->accountledgers->get_closing_balance_by_ledger_dev($l->id,$l->opening_balance,$l->opening_cr_dr,$start_date,$category);
				  }
				   $j++;
			   }
			   
			   $result['bank'][$i]=$ledgers;				
			   $i++;
		   }
		  	
        }
        $html ="";
        $html .= '<h5>Cash</h5><p>Opening Balance -'. number_format(abs($result['cash'][0]->opening_balance_date),2).' ';
        if($result['cash'][0]->opening_balance_date < 0 ){  $html .=  "DR"; } 
        else if($result['cash'][0]->opening_balance_date ==0){
            $html .= '';
        }
        else { $html .= "CR"; } 
        $html .= '| Closing Balance - '.number_format(abs($result['cash'][0]->closing_balance_date),2). ' ';
        if($result['cash'][0]->closing_balance_date < 0 ){ $html .= "DR"; } 
        else if($result['cash'][0]->closing_balance_date ==0){
            $html .=  '';
        }
        else {  $html .= "CR"; }
        $html .= ' </p>';
        foreach($result['bank'] as $bank){ 
        foreach($bank as $l){ 
        
        $html .= '<h5>'.$l->name.'</h5>
        <p>Opening Balance - '.number_format(abs($l->opening_balance_date),2).' ';
     if($l->opening_balance_date < 0 ){ $html .= "DR"; } 
        else if($l->opening_balance_date ==0){
            $html .= '';
        }
        else { $html .= "CR"; }
     $html .= '| Closing Balance - '. number_format(abs($l->closing_balance_date),2). ' ';
        if($l->closing_balance_date < 0 ){ $html .= "DR"; } 
        else if($l->closing_balance_date ==0){
            $html .= '';
        }
        else { $html .= "CR"; }
        $html .=' </p>';
         } } 
         $this->data['html'] = $html;
         echo json_encode( $this->data);

         die();
    }
    public function download_yearbook($school_id,$category)
    {
       
        $heading_cols = array('Date','Ledger Entries','Voucher','Transaction ID/Voucher No.','Debit Amount','Credit Amount');
        $transactions = array();
        $data = array();
        $financial_year=$this->transactions->get_single("financial_years",array("school_id"=>$school_id,'is_running'=>1));		
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
        $start_date =  $f_start;
        $end_date =  $f_end;
      
        if($school_id){
			// get transactionsdate
			
				$transactions=$this->transactions->get_transactions_by_range($school_id,$category,$start_date,$end_date);
			
			
			   
			
			
        }
       
        $total_debit = 0;
        $total_credit = 0;
        if(!empty($transactions)){
			foreach($transactions as $t){
				// get total amount of transaction
				$tr_ids[] = $t->id;
			}	
			$total_amounts = [];
			$total_amounts_raw=$this->transactions->get_total_amount_by_transaction_ids($tr_ids);
			foreach($total_amounts_raw as $total_amount_raw){
				// get total amount of transaction
				$tr_ids[] = $t->id;
				$total_amounts[$total_amount_raw->transaction_id] = $total_amount_raw->total_amount;
			}	
            $html = "<table>";
           foreach($transactions as $t){
           // get transaction details
                    $t->detail=$this->transactions->get_transaction_detail($t->id);
					// get total amount of transaction
					$total_amount=isset($total_amounts[$t->id]) ?  $total_amounts[$t->id] : 0;
					$t->total_amount=$total_amount;
				
               
               if($t->total_amount > 0){
               if($t->head_cr_dr == 'DR') {
                   $total_debit += $t->total_amount;
               }
               else if($t->head_cr_dr == 'CR'){ 
                   $total_credit += $t->total_amount;
               }
           $html .= '<tr>
               <td>'.date('d-m-Y',strtotime($t->date)).'</td>
               <td style="width:30%;"><strong>'. $t->ledger_name.'</strong></td>
               <td>'.$t->voucher_name.' ('.$t->voucher_category.')</td>											
               <td><a href="'.site_url("transactions/view/".$t->id).'">'.$t->transaction_no.'</a></td>
               <td align="right"><strong>';
               if($t->head_cr_dr == 'DR') $html .= number_format($t->total_amount,2); 
               else $html .=  "&nbsp;";
               $html .= '</strong></td><td align="right"><strong>';
                if($t->head_cr_dr == 'CR') $html .= number_format($t->total_amount,2); 
                else
                $html .= "&nbsp;";
               $html .= ' </strong></td></tr>';
            foreach($t->detail as $d){ 
           if($t->head_cr_dr == 'CR'){
               $total_debit += $d->amount;
           }
           else if($t->head_cr_dr == 'DR'){
               $total_credit += $d->amount;
           }
           $html .= '
           <tr>
               <td>&nbsp;</td>
               <td style="width:30%;">  &nbsp;&nbsp;&nbsp; -> '.$d->ledger_name.'<br />';

                if($d->remark) {
                $html .= ' &nbsp;&nbsp;&nbsp; <strong>Remark: </strong>'.$d->remark ;
               }
               $html .= '</td>
               <td>&nbsp;</td>											
               <td>&nbsp;</td>
               <td align="right">';
               
               if($t->head_cr_dr == 'CR') $html .= number_format($d->amount,2); 
               else $html .= "&nbsp;";
               $html .= '
               </td>
               <td align="right">';
               if($t->head_cr_dr == 'DR') {
                   $html .= "&nbsp;";
                   $html .=  number_format($d->amount,2); 
               }
               else $html .= "&nbsp;";
               $html .=  "&nbsp;";
               $html .= '</td></tr>
           <tr>
               <td>&nbsp;</td>
               <td style="width:30%;"><strong>Narration : </strong>'.$t->narration.'</td>
               <td>&nbsp;</td>											
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               
           </tr>';
        } } }	
        $html .="
        <tr>
        <th></th>
        <th></th>
        <th></th>
        <th>Total</th>
        <td align='right'><strong>".numberToCurrency($total_debit)."</strong></td>
        <td align='right'><strong>".numberToCurrency($total_credit)."</strong></td>
        </tr>";
        $html .= "</table>";
    }
 
    $DOM = new DOMDocument();

    $DOM->loadHTML($html);
    $Detail = $DOM->getElementsByTagName('td');
    $tableData = array();
    $icount = 0;
    $iSNo  =1;
    
    foreach($Detail as $NodeHeader) 
	{
        if($icount %6 ==0)
        {
            $heading_col =0;
            $iSNo++;
        }
		if(!isset($array_data[$iSNo])) $array_data[$iSNo] = array();
        $array_data[$iSNo][$heading_cols[$heading_col]] = $NodeHeader->textContent;
		
        $heading_col++;
        $icount++;
	}
    $tableData =  $array_data;
   
   
    download_send_headers("day_book_".$financial_year->session_year.".csv");
  
    echo array2csv($tableData);
 
 
     die();

    }
     
}
