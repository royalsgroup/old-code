<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accountledgers_Model extends MY_Model {
    
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
	public function get_accountledger_list($school_id = null,$financial_year_id = null,$category =null,$group_id =null,$group_base_id = null,$group_types = null,$limit ="",$start = "",$search_text = "",$count =0,$ledger_ids = null){        
        $this->db->select('AL.*,ALD.opening_balance,ALD.opening_cr_dr,ALD.budget,AG.base_id,ALD.budget_cr_dr, S.school_name, AG.id as group_id,AG.type_id as group_type, AG.name as group_name,AG.group_code as group_code,AT.name as type_name,AY.session_year');
        $this->db->from('account_ledgers AS AL');       		
        $this->db->join('schools AS S', 'S.id = AL.school_id', 'left');
		$this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');
		$this->db->join('account_types AS AT', 'AT.id = AG.type_id', 'left');		
		$this->db->join('financial_years AS AY', 'AY.id = ALD.financial_year_id', 'left');	
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 		
		if($financial_year_id){
            $this->db->where('ALD.financial_year_id', $financial_year_id); 
        }	
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('AL.school_id', $this->session->userdata('school_id'));
            //$this->db->or_where('AL.school_id','0'); 
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id!=null){
            $this->db->where('AL.school_id', $school_id);
           // $this->db->or_where('AL.school_id','0'); 
        }
		if($category)
		{
			$this->db->where('AL.category', $category);
			
		}
		if($group_base_id )
		{
			$this->db->where('AG.base_id', $group_base_id); 
		}
		if($group_id){
            $this->db->where('AL.account_group_id', $group_id); 
        }
		if($group_types)
		{
			$this->db->where_in('AG.type_id', $group_types); 
		}
		if($ledger_ids)
		{
			$this->db->where_in('AL.id', $ledger_ids); 
		}
		if($this->session->userdata('dadmin') == 1 && $school_id!=null){
            $this->db->where('AL.school_id', $school_id);
           // $this->db->or_where('AL.school_id','0'); 
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
		// if($search_text!=''){
		// 	$this->db->like('AL.name ', $search_text);
		// }    
		if($search_text!=''){
            $this->db->group_start();
			$this->db->like('AL.name  ', $search_text);
            $this->db->or_like('AG.name ', $search_text);
            $this->db->group_end();
		}
		if(!$count)
		{
			if ($limit != null && $start != null) {
				$this->db->limit($limit, $start);
			}	
		}
		$this->db->order_by('AL.name','asc');
		if($count)
		{
			return $this->db->get()->num_rows();
		}
		else
		{
			$result =  $this->db->get();
			//echo $this->db->last_query();
			return $result->result();	
		}
		
        //eturn $this->db->get()->result();
        
    }	

	public function get_current_balance_by_ledger($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$start_date=null, $end_date=null){
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT');    
		$this->db->where('AT.ledger_id', $ledger_id); 
		$this->db->where('AT.cancelled', 0); 
		if($start_date !=null){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
		}
		if($end_date !=null){
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
		}
		$transactions=$this->db->get()->result();
		$grand_total=0;
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 
			$res=$this->db->get()->row();
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		// get ledger detail
		$ledger_detail = $this->db->select('AL.*')->from('account_ledgers AS AL')->where('id', $ledger_id)->limit(1)->get()->row();
		
		$total_amount=0;
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD');  
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('AT.cancelled', 0); 
		$this->db->where('ATD.ledger_id', $ledger_id); 
		if($start_date !=null){
			//$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
		}
		if($end_date !=null){
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
		}
		$res=$this->db->get()->result();
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}
		/*if(isset($res->total_amount)){
			$total_amount=$res->total_amount;		
			if($ledger_detail->dr_cr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}
		}*/
		if($opening_cr_dr== 'DR'){
			$opening_balance=(-$opening_balance);
		}
		else{
			$opening_balance=$opening_balance;
		}
		$final_amount=$opening_balance+$grand_total;
		return $final_amount;
	}
	/*public function get_effective_balance_by_ledger($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date=null,$start_date=null,$end_date=null){
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT'); 	
		//$this->db->join('account_transaction_details AS ATD', 'ATD.transaction_id = AT.id', 'left');		
		$this->db->where('AT.ledger_id', $ledger_id);
		if($date !=null){		
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		if($start_date!= null){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
		}
		if($end_date !=null){
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
		}
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();
		$grand_total=0;
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 
			//$this->db->where('ATD.ledger_id', $ledger_id); 
			$res=$this->db->get()->row();
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total+$total_amount;
			}
			else {
				$grand_total=$grand_total-$total_amount;
			}			
			
		}
		// get ledger detail
		$ledger_detail = $this->db->select('AL.*')->from('account_ledgers AS AL')->where('id', $ledger_id)->limit(1)->get()->row();
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('ATD.ledger_id', $ledger_id); 
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
		}	
		/*if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}*/
/*				$final_amount=$opening_balance+$grand_total;
		return $final_amount;
	}*/
	public function get_ledger_with_amount_list_with_balance1(){
		$this->db->select('AL.*,AG.name as group_name,AG.base_id,ALD.opening_balance,ALD.opening_cr_dr,ALD.budget,ALD.budget_cr_dr');
        $this->db->from('account_ledgers AS AL');       		        
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');
		$this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	
		if($financial_year_id){
           
		}
		if($school_id){
            $this->db->where('AL.school_id', $school_id); 
		}
		return $this->db->get()->result();
	}
	public function get_effective_balance_by_ledger1($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date=null,$start_date=null,$end_date=null,$voucher_category=null,$no_financial_year=null)
	{
	
		// first get financial year		
	
		
	if($start_date < '2021-04-01' && !$no_financial_year)
	{	
		// get current balance from db
		$ledger_d=$this->get_single("account_ledger_details",array("ledger_id"=>$ledger_id,'financial_year_id'=>$financial_year->id));
		$current_bal=$ledger_d->current_balance;
		if($ledger_d->current_balance_cr_dr == 'DR'){
			$current_bal=-$current_bal;
		}
		return $current_bal;
	}	
	else
	{	
		$tr_ids=array("@32323");
		$this->db->select('AT.id,AT.head_cr_dr,sum(ATD.amount) as total_amount');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
		$this->db->where('AT.ledger_id', $ledger_id);
		if($date !=null && $date != ''){		
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		
		if($start_date!= null && $start_date != ''){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
			if($end_date==null){
				$this->db->where('AT.date <= ', $f_end); 
			}
		}
		if($end_date !=null && $end_date != ''){			
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
			if($start_date==null){
				$this->db->where('AT.date >= ', $f_start); 
			}
		}
		if($start_date==null && $end_date==null){
			// financial year
			$this->db->where('AT.date >= ', $f_start); 
			$this->db->where('AT.date <= ', $f_end); 
		}
		if($voucher_category !=null){
			$this->db->where('V.category', $voucher_category); 
		}
		$this->db->where('AT.cancelled', 0); 
		$this->db->group_by('AT.id,AT.head_cr_dr'); 
		//$transactions=$this->db->get()->result();	
		//echo $this->db->last_query();
//print_r($transactions);print 'next';		
		$grand_total=0;
		// foreach($transactions as $t){
		// 	$tr_ids[]=$t->id;
		// 	// get transaction detial to get amount
		// 	$total_amount=0;
			
		// 	//$this->db->where('ATD.ledger_id', $ledger_id); 
		
		// 	if(isset($t->total_amount)){
		// 		$total_amount=$t->total_amount;
		// 	}
			
		// 	if($t->head_cr_dr == 'DR'){
		// 		$grand_total=$grand_total-$total_amount;
		// 	}
		// 	else {
		// 		$grand_total=$grand_total+$total_amount;
		// 	}			
			
		// }
		
		// get ledger detail
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
		$this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');				
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('ATD.ledger_id', $ledger_id); 
		if($date !=null && $date != ''){		
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		if($start_date && $start_date ){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
			if($end_date==null){
				$this->db->where('AT.date <= ', $f_end); 
			}
		}
		if($end_date && $end_date ){			
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
			if($start_date==null){
				$this->db->where('AT.date >= ', $f_start); 
			}
		}
		if($start_date==null && $end_date==null && !$no_financial_year){
			// financial year
			$this->db->where('AT.date >= ', $f_start); 
			$this->db->where('AT.date <= ', $f_end); 
		}		
		if($voucher_category !=null){
			$this->db->where('V.category', $voucher_category); 
		}		
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();
		
		//print_r($res); exit;
		foreach($res as $r){
			
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}	
		
		
	   
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
 		}
		return $final_amount;
	}
	public function get_effective_balance_by_ledger($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date=null,$start_date=null,$end_date=null,$voucher_category=null,$no_financial_year=null,$financial_year_id_cus=null){
		// first get financial year		
		$ledger=$this->get_single("account_ledgers",array("id"=>$ledger_id));
		if($financial_year_id_cus)
		{
			$financial_year=$this->get_single("financial_years",array("id"=>$financial_year_id_cus));		

		}else
		{
			$financial_year=$this->get_single("financial_years",array("school_id"=>$ledger->school_id,'is_running'=>1));		

		}
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
		
if($f_start < '2021-04-01' && !$no_financial_year){	
	// get current balance from db
	$ledger_d=$this->get_single("account_ledger_details",array("ledger_id"=>$ledger_id,'financial_year_id'=>$financial_year->id));
	$current_bal=$ledger_d->current_balance;
	if($ledger_d->current_balance_cr_dr == 'DR'){
		$current_bal=-$current_bal;
	}
	return $current_bal;
}	
else{	
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT');
		$this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');				
		$this->db->where('AT.ledger_id', $ledger_id);
		if($date !=null && $date != ''){		
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		if($start_date!= null && $start_date != ''){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
			if($end_date==null){
				$this->db->where('AT.date <= ', $f_end); 
			}
		}
		if($end_date !=null && $end_date != ''){			
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
			if($start_date==null){
				$this->db->where('AT.date >= ', $f_start); 
			}
		}
		if($start_date==null && $end_date==null){
			// financial year
			$this->db->where('AT.date >= ', $f_start); 
			$this->db->where('AT.date <= ', $f_end); 
		}
		if($voucher_category !=null){
			$this->db->where('V.category', $voucher_category); 
		}
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();	 
	
		
//print_r($transactions);print 'next';		
		$grand_total=0;
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 
			//$this->db->where('ATD.ledger_id', $ledger_id); 
			$res=$this->db->get()->row();
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		
		// get ledger detail
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
		$this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');				
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('ATD.ledger_id', $ledger_id); 
		if($date !=null && $date != ''){		
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		if($start_date && $start_date ){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
			if($end_date==null){
				$this->db->where('AT.date <= ', $f_end); 
			}
		}
		if($end_date && $end_date ){			
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
			if($start_date==null){
				$this->db->where('AT.date >= ', $f_start); 
			}
		}
		if($start_date==null && $end_date==null && !$no_financial_year){
			// financial year
			$this->db->where('AT.date >= ', $f_start); 
			$this->db->where('AT.date <= ', $f_end); 
		}		
		if($voucher_category !=null){
			$this->db->where('V.category', $voucher_category); 
		}		
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();
		
		foreach($res as $r){
			
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}	
		
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
 		}
		return $final_amount;
	}
	public function get_vouchers_with_ids($voucher_ids)
	{
		$this->db->select('V.name as name,V.id as id');
        $this->db->from('vouchers AS V'); 	
		
		$this->db->where_in('V.id', $voucher_ids); 	
		return $this->db->get()->result();
	}
	public function get_ledger_with_amount_list_with_balance($ledger_ids = array(),$start_date = null,$end_date = null,$get_cancelled = false){
		// first get financial year		

		$tr_ids=array();
		
		$this->db->select('AT.id,AT.ledger_id ,AT.head_cr_dr,ATD.amount as amount,ATD.amount as total_amount,AT.cancelled,AT.date,AT.narration,AT.voucher_id,AT.transaction_no,1 as type,AT.id as transaction_id, ATD.remark');
        $this->db->from('account_transaction_details  AS ATD');
		$this->db->join('account_transactions AS AT', 'ATD.transaction_id = AT.id', 'left');	
		$this->db->group_start();
		$this->db->where_in('AT.ledger_id', $ledger_ids); 
		$this->db->group_end();
		if(!$get_cancelled)
		{
			$this->db->where('AT.cancelled', 0); 
		}
		if($start_date!= null && $start_date != ''){
			$this->db->where('date(AT.date) >= ', date('Y-m-d',strtotime($start_date))); 
		}
		if($end_date !=null && $end_date != ''){			
			$this->db->where('date(AT.date) <= ', date('Y-m-d',strtotime($end_date))); 
			
		}
		// $this->db->group_by('AT.ledger_id,AT.head_cr_dr'); 
		return $this->db->get()->result();

	}
	public function get_ledger_with_amount_list_with_opening_balance($ledger_ids = array(),$start_date = null,$end_date = null,$f_start = null,$f_end = null){
		// first get financial year		

		$tr_ids=array();
		
		$this->db->select('"balance" as balance,AT.id,AT.ledger_id ,AT.head_cr_dr,ATD.amount as total_amount,AT.date');
        $this->db->from('account_transaction_details  AS ATD');
		$this->db->join('account_transactions AS AT', 'ATD.transaction_id = AT.id', 'left');	
		$this->db->group_start();
		$this->db->where_in('AT.ledger_id', $ledger_ids); 
		$this->db->group_end();
		$this->db->where('AT.cancelled', 0); 
	
		if($end_date !=null && $end_date != ''){			
			$this->db->where('AT.date < ', date('Y-m-d',strtotime($end_date))); 	
		}
		else
		{
			$this->db->where('AT.date > ', date('Y-m-d',strtotime($start_date))); 
		}
		if($f_end)
		{
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($f_end)));
		}
		if($f_start)
		{
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($f_start)));
		}
		// var_dump($f_end);
		// $this->db->group_by('AT.id,AT.ledger_id,AT.head_cr_dr'); 
		$result = $this->db->get();
		// echo $this->db->last_query();
		return $result->result();	
	}
	public function get_ledger_with_amount_list_with_opening__balance_excluded($ledger_ids = array(),$tr_ids = array(),$start_date = null,$end_date = null,$f_start = null,$f_end = null){
		// first get financial year		

		
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
	//	$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		$this->db->where('AT.cancelled', 0); 
		$this->db->where_in('ATD.ledger_id', $ledger_ids); 
		$this->db->where('ATD.transaction_id not in (select id from account_transactions where ledger_id = ATD.ledger_id)'); 
		if($end_date !=null && $end_date != ''){			
			$this->db->where('AT.date < ', date('Y-m-d',strtotime($end_date))); 	
		}
		else
		{
			$this->db->where('AT.date < ', date('Y-m-d',strtotime($start_date))); 
		}
		if($f_end)
		{
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($f_end)));
		}
		if($f_start)
		{
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($f_start)));
		}
		return $this->db->get()->result();	
	}
	public function get_ledger_with_amount_list_with_balance_with_group($ledger_ids = array(),$start_date = null,$end_date = null,$category = null){
		// first get financial year		

		$tr_ids=array();
		
		$this->db->select('AT.id,AT.ledger_id ,AT.head_cr_dr,AL.account_group_id as group_id,ATD.amount as total_amount,ATD.remark');
        $this->db->from('account_transaction_details  AS ATD');
		$this->db->join('account_transactions AS AT', 'ATD.transaction_id = AT.id', 'left');	
		$this->db->join('account_ledgers AS AL', 'AL.id = AT.ledger_id', 'left');	
		// if($category)
		// {
		// 	// $this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');				
		// 	$this->db->where('AL.category', $category); 
		// }
		$this->db->group_start();
		$this->db->where_in('AT.ledger_id', $ledger_ids); 
		$this->db->group_end();
		$this->db->where('AT.cancelled', 0); 
		if($start_date!= null && $start_date != ''){
			$this->db->where('date(AT.date) >= ', date('Y-m-d',strtotime($start_date))); 
		}
		if($end_date !=null && $end_date != ''){			
			$this->db->where('date(AT.date) <= ', date('Y-m-d',strtotime($end_date))); 
			
		}
		// $this->db->group_by('AT.id,AT.ledger_id,AT.head_cr_dr,AL.account_group_id'); 
		$result =  $this->db->get();
		//echo $this->db->last_query();
		return $result->result();	
	}
	public function get_ledger_with_amount_list_with_balance_excluded_with_group($ledger_ids = array(),$tr_ids = array(),$start_date = null,$end_date = null,$category = null){
		// first get financial year		

		
		$this->db->select('ATD.*,AT.head_cr_dr,AL.account_group_id as group_id,date(AT.date)');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');	
		$this->db->join('account_ledgers AS AL', 'AL.id = AT.ledger_id', 'left');	
		// if($category)
		// {
		// 	// $this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');				
		// 	$this->db->where('AL.category', $category); 
		// }
	//	$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		
		$this->db->where('AT.cancelled', 0); 
		
		$this->db->where_in('ATD.ledger_id', $ledger_ids); 
		$this->db->where('ATD.transaction_id not in (select id from account_transactions where ledger_id = ATD.ledger_id)'); 
		if($start_date!= null && $start_date != ''){
			$this->db->where('date(AT.date) >= ', date('Y-m-d',strtotime($start_date))); 
		}
		if($end_date !=null && $end_date != ''){			
			$this->db->where('date(AT.date) <= ', date('Y-m-d',strtotime($end_date))); 
		}
		return $this->db->get()->result();	
	}
	public function get_ledger_with_amount_list_with_balance_excluded($ledger_ids = array(),$tr_ids = array(),$start_date = null,$end_date = null,$get_cancelled = false){
		// first get financial year		

		
		$this->db->select('ATD.*,AT.head_cr_dr,AT.date,AT.cancelled,At.narration,AT.voucher_id,AT.transaction_no,2 as type, ATD.remark');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
	//	$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		if(!$get_cancelled)
		{
			$this->db->where('AT.cancelled', 0); 
		}
		$this->db->where_in('ATD.ledger_id', $ledger_ids); 
		$this->db->where('ATD.transaction_id not in (select id from account_transactions where ledger_id = ATD.ledger_id)'); 
		if($start_date!= null && $start_date != ''){
			$this->db->where('date(AT.date) >= ', date('Y-m-d',strtotime($start_date))); 
		}
		if($end_date !=null && $end_date != ''){			
			$this->db->where('date(AT.date) <= ', date('Y-m-d',strtotime($end_date))); 
		}
		
		$result =  $this->db->get();
		//echo $this->db->last_query();
		return $result->result();	
	}
	
	public function get_ledger_with_amount_list_with_balance33($school_id = null,$financial_year_id = null){
		// first get financial year		

		$tr_ids=array();
		
		$this->db->select('AT.id,AL.id as ledger_id,AT.head_cr_dr,sum(ATD.amount) as total_amount');
        $this->db->from('account_transaction_details  AS ATD');
		$this->db->join('account_transactions AS AT', 'ATD.transaction_id = AT.id', 'left');	
		$this->db->join('account_ledgers AS AL', 'AT.ledger_id = AL.id', 'left');		
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');		
		$this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');	
		$this->db->where('ALD.financial_year_id', $financial_year_id); 			
		$this->db->where('AL.school_id', $school_id); 

		$this->db->where('AT.cancelled', 0); 
		$this->db->group_by('AT.id,AL.id,AT.head_cr_dr'); 
		$transactions=$this->db->get()->result();	
		echo "<pre>";
		print_r($transactions);
		die();	 
//print_r($transactions);print 'next';		
		$grand_total=0;
		
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			if(isset($t->total_amount)){
				$total_amount=$t->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
	
		// get ledger detail
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');	
		$this->db->join('account_ledgers AS AL', 'AT.ledger_id = AL.id', 'left');					
		$this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');		
		$this->db->where('ALD.financial_year_id', $financial_year_id); 			
		$this->db->where('AL.school_id', $school_id); 

		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();
		//print_r($res); exit;
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}	
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;

		return $final_amount;
	}
	
	public function get_effective_balance_by_ledger_dev($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date=null,$start_date=null,$end_date=null,$voucher_category=null,$no_financial_year=null){
		// first get financial year		
		$ledger=$this->get_single("account_ledgers",array("id"=>$ledger_id));
		$financial_year=$this->get_single("financial_years",array("school_id"=>$ledger->school_id,'is_running'=>1));		
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
		
		if($f_start < '2021-04-01' && !$no_financial_year)
		{	
			// get current balance from db
			$ledger_d=$this->get_single("account_ledger_details",array("ledger_id"=>$ledger_id,'financial_year_id'=>$financial_year->id));
			$current_bal=$ledger_d->current_balance;
			if($ledger_d->current_balance_cr_dr == 'DR'){
				$current_bal=-$current_bal;
			}
			return $current_bal;
		}	
		else{	
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT');
		$this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');				
		$this->db->where('AT.ledger_id', $ledger_id);
		if($date !=null && $date != ''){		
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		if($start_date!= null && $start_date != ''){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
			if($end_date==null){
				$this->db->where('AT.date <= ', $f_end); 
			}
		}
		if($end_date !=null && $end_date != ''){			
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
			if($start_date==null){
				$this->db->where('AT.date >= ', $f_start); 
			}
		}
		if($start_date==null && $end_date==null){
			// financial year
			$this->db->where('AT.date >= ', $f_start); 
			$this->db->where('AT.date <= ', $f_end); 
		}
		if($voucher_category !=null){
			$this->db->where('V.category', $voucher_category); 
		}
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();	
//print_r($transactions);print 'next';		
		$grand_total=0;
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 
			//$this->db->where('ATD.ledger_id', $ledger_id); 
			$res=$this->db->get()->row();
			
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		// get ledger detail
		$ledger_detail = $this->db->select('AL.*')->from('account_ledgers AS AL')->where('id', $ledger_id)->limit(1)->get()->row();
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');		
		$this->db->join('vouchers AS V', 'AT.voucher_id = V.id', 'left');				
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('ATD.ledger_id', $ledger_id); 
		if($date !=null && $date != ''){		
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		if($start_date && $start_date ){
			$this->db->where('AT.date >= ', date('Y-m-d',strtotime($start_date))); 
			if($end_date==null){
				$this->db->where('AT.date <= ', $f_end); 
			}
		}
		if($end_date && $end_date ){			
			$this->db->where('AT.date <= ', date('Y-m-d',strtotime($end_date))); 
			if($start_date==null){
				$this->db->where('AT.date >= ', $f_start); 
			}
		}
		if($start_date==null && $end_date==null && !$no_financial_year){
			// financial year
			$this->db->where('AT.date >= ', $f_start); 
			$this->db->where('AT.date <= ', $f_end); 
		}		
		if($voucher_category !=null){
			$this->db->where('V.category', $voucher_category); 
		}		
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();
		//print_r($res); exit;
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}	
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
 }
		return $final_amount;
	}
	public function get_closing_balance_by_ledger_dev($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date,$category=null,$end_date =null){	
	
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT');
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}  		
		$this->db->where('AT.ledger_id', $ledger_id); 
		// if($end_date)
		// {
		// 	$this->db->where("DATE(AT.date) >= '$start_date' and DATE(AT.date) <= '$end_date' ");

		// }
		// else{
		// 	$this->db->where('DATE(AT.date) <= ', date('Y-m-d',strtotime($date))); 
		// }
		if($end_date)
		{		
			//$this->db->where("DATE(AT.date) >= '$date' and DATE(AT.date) <= '$end_date' ");
			$this->db->where('DATE(AT.date) <= ', date('Y-m-d',strtotime($end_date))); 
		}
		else{
			$this->db->where('DATE(AT.date) <= ', date('Y-m-d',strtotime($date))); 
		}
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();
		
		$grand_total=0;		
		foreach($transactions as $t){
			
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 			
			$res=$this->db->get()->row();
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		// get ledger detail
		$ledger_detail = $this->db->select('AL.*')->from('account_ledgers AS AL')->where('id', $ledger_id)->limit(1)->get()->row();
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD');
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}		
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('ATD.ledger_id', $ledger_id); 
		if($end_date)
		{		
			//$this->db->where("DATE(AT.date) >= '$date' and DATE(AT.date) <= '$end_date' ");
			$this->db->where('DATE(AT.date) <= ', date('Y-m-d',strtotime($end_date))); 
		}
		else{
			$this->db->where('DATE(AT.date) <= ', date('Y-m-d',strtotime($date))); 
		}
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}		
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
				
		return $final_amount;
	}
	public function get_opening_balance_by_ledger_dev($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date,$category=null,$end_date = null){
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT'); 	
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}
		//$this->db->join('account_transaction_details AS ATD', 'ATD.transaction_id = AT.id', 'left');		
		$this->db->where('AT.ledger_id', $ledger_id); 
		if($end_date)
		{
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($end_date))); 
		}
		else
		{
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();
		
		$grand_total=0;
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 
			//$this->db->where('ATD.ledger_id', $ledger_id); 
			$res=$this->db->get()->row();
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		// get ledger detail
		$ledger_detail = $this->db->select('AL.*')->from('account_ledgers AS AL')->where('id', $ledger_id)->limit(1)->get()->row();
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}		
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		if($end_date)
		{
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($end_date))); 
		}
		else
		{
			$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		}
		$this->db->where('ATD.ledger_id', $ledger_id); 
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();		
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}	
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
		return $final_amount;
	}
	public function get_opening_balance_by_ledger($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date,$category=null){
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT'); 	
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}
		//$this->db->join('account_transaction_details AS ATD', 'ATD.transaction_id = AT.id', 'left');		
		$this->db->where('AT.ledger_id', $ledger_id); 
		$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();
		
		$grand_total=0;
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 
			//$this->db->where('ATD.ledger_id', $ledger_id); 
			$res=$this->db->get()->row();
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		// get ledger detail
		$ledger_detail = $this->db->select('AL.*')->from('account_ledgers AS AL')->where('id', $ledger_id)->limit(1)->get()->row();
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}		
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		$this->db->where('ATD.ledger_id', $ledger_id); 
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();		
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}	
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
		return $final_amount;
	}
	public function get_accountledgers_by_group_optimized($school_id = null,$financial_year_id = null,$group_id){        
        $this->db->select('AL.*,ALD.opening_balance,ALD.opening_cr_dr,ALD.budget,ALD.budget_cr_dr, S.school_name, AG.name as group_name,AT.name as type_name');
        $this->db->from('account_ledgers AS AL');       		
        $this->db->join('schools AS S', 'S.id = AL.school_id', 'left');
		$this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');
		$this->db->join('account_types AS AT', 'AT.id = AG.type_id', 'left');
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	
		
		if($financial_year_id){
           $this->db->where('ALD.financial_year_id', $financial_year_id); 
        }
		if($group_id){
            $this->db->where('AL.account_group_id', $group_id); 
        }
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('AL.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('AL.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('AL.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        return $this->db->get()->result();
        
    }
	
	public function get_opening_balance_by_ledger_optimized($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date,$category=null){
		$tr_ids=array();
		$this->db->select('AT.*
		,(select sum(ATD.amount) as total_amount from account_transaction_details AS ATD where ATD.transaction_id=AT.id) as total_amount
		');
        $this->db->from('account_transactions AS AT'); 	
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}
		//$this->db->join('account_transaction_details AS ATD', 'ATD.transaction_id = AT.id', 'left');		
		$this->db->where('AT.ledger_id', $ledger_id); 
		$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();
		
		$grand_total=0;
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			
			if(isset($t->total_amount)){
				$total_amount=$t->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		// get ledger detail
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD'); 
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}		
	
		$this->db->where('DATE(AT.date) < ', date('Y-m-d',strtotime($date))); 
		$this->db->where('ATD.ledger_id', $ledger_id); 
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();	
		
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}	
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
		return $final_amount;
	}
	public function get_closing_balance_by_ledger($ledger_id,$opening_balance=0,$opening_cr_dr='DR',$date,$category=null){			
		$tr_ids=array();
		$this->db->select('AT.*');
        $this->db->from('account_transactions AS AT');
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}  		
		$this->db->where('AT.ledger_id', $ledger_id); 
		$this->db->where('DATE(AT.date) <= ', date('Y-m-d',strtotime($date))); 
		$this->db->where('AT.cancelled', 0); 
		$transactions=$this->db->get()->result();
		$grand_total=0;		
		foreach($transactions as $t){
			$tr_ids[]=$t->id;
			// get transaction detial to get amount
			$total_amount=0;
			$this->db->select('sum(ATD.amount) as total_amount');
			$this->db->from('account_transaction_details AS ATD');    
			$this->db->where('ATD.transaction_id', $t->id); 			
			$res=$this->db->get()->row();
			if(isset($res->total_amount)){
				$total_amount=$res->total_amount;
			}
			if($t->head_cr_dr == 'DR'){
				$grand_total=$grand_total-$total_amount;
			}
			else {
				$grand_total=$grand_total+$total_amount;
			}			
			
		}
		// get ledger detail
		$ledger_detail = $this->db->select('AL.*')->from('account_ledgers AS AL')->where('id', $ledger_id)->limit(1)->get()->row();
		
		$total_amount=0;
		//$this->db->select('sum(ATD.amount) as total_amount');
		$this->db->select('ATD.*,AT.head_cr_dr');
		$this->db->from('account_transaction_details AS ATD');
		$this->db->join('account_transactions AS AT', 'AT.id = ATD.transaction_id', 'left');
		$this->db->join('vouchers AS V', 'V.id = AT.voucher_id', 'left');
		if($category !=null){
			$this->db->where('V.category', $category);
		}		
		if(!empty($tr_ids)){
			$this->db->where_not_in('ATD.transaction_id', $tr_ids); 
		}
		$this->db->where('ATD.ledger_id', $ledger_id); 
		$this->db->where('DATE(AT.date) <= ', date('Y-m-d',strtotime($date))); 
		$this->db->where('AT.cancelled', 0); 
		$res=$this->db->get()->result();
		foreach($res as $r){
			if($r->head_cr_dr =='DR'){
				// credit amount - reverce of head ledger of transaction
				$grand_total=$grand_total+$r->amount;
			}
			else{
				// debit amount - reverce of head ledger of transaction
				$grand_total=$grand_total-$r->amount;
			}
		}		
		if($opening_cr_dr== 'DR'){
					$opening_balance=(-$opening_balance);
				}
				else{
					$opening_balance=$opening_balance;
				}
				$final_amount=$opening_balance+$grand_total;
		return $final_amount;
	}
	public function get_accountledgers_by_group($school_id = null,$financial_year_id = null,$group_id,$category =null){        
        $this->db->select('AL.*,ALD.opening_balance,ALD.opening_cr_dr,ALD.budget,ALD.budget_cr_dr, S.school_name, AG.name as group_name,AT.name as type_name');
        $this->db->from('account_ledgers AS AL');       		
        $this->db->join('schools AS S', 'S.id = AL.school_id', 'left');
		$this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');
		$this->db->join('account_types AS AT', 'AT.id = AG.type_id', 'left');
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	
		if($category)
		{
			$this->db->where("AL.category",$category); 	
		}
		if($financial_year_id){
           $this->db->where('ALD.financial_year_id', $financial_year_id); 
        }
		if($group_id){
            $this->db->where('AL.account_group_id', $group_id); 
        }
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('AL.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('AL.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('AL.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
		$this->db->order_by("AL.name",'ASC'); 	

        return $this->db->get()->result();
        
    }
	public function get_accountledgers_by_ledger_ids($ledger_ids,$financial_year_id = null){        
        $this->db->select('AL.*,ALD.opening_balance,ALD.opening_cr_dr,ALD.budget,ALD.budget_cr_dr,AG.type_id');
        $this->db->from('account_ledgers AS AL');       		
		$this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');
		$this->db->join('account_types AS AT', 'AT.id = AG.type_id', 'left');
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	
		
		$this->db->where_in("AL.id",$ledger_ids); 	
		$this->db->order_by("AL.name",'ASC'); 	
		if($financial_year_id){
			$this->db->where('ALD.financial_year_id', $financial_year_id); 
		 }
        return $this->db->get()->result();
        
    }
	
	public function get_ledger_with_amount_list($school_id = null,$financial_year_id = null){
		$this->db->select('AL.*,AG.name as group_name,AG.base_id,ALD.opening_balance,ALD.opening_cr_dr,ALD.budget,ALD.budget_cr_dr');
        $this->db->from('account_ledgers AS AL');       		        
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');
		$this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	
		if($financial_year_id){
            $this->db->where('ALD.financial_year_id', $financial_year_id); 
		}
		if($school_id){
            $this->db->where('AL.school_id', $school_id); 
		}
		return $this->db->get()->result();
	}
	public function get_ledger_by_name($school_id,$name){		
		$this->db->select('AL.*');
        $this->db->from('account_ledgers AS AL');    
		$this->db->where('AL.school_id', $school_id); 	
		$this->db->where('AL.name', $name);		
		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	
		return $this->db->get()->row(); 		
	}
	public function get_ledger_by_id($id){		
		$this->db->select('AL.*,ALD.opening_balance,ALD.opening_cr_dr,ALD.budget,ALD.budget_cr_dr,AG.name as group_name,S.school_name');
        $this->db->from('account_ledgers AS AL');
		$this->db->join('schools AS S', 'S.id = AL.school_id', 'left');
		$this->db->join('account_groups AS AG', 'AG.id = AL.account_group_id', 'left');	
		$this->db->join('account_ledger_details AS ALD', 'ALD.ledger_id = AL.id', 'left');		
		$this->db->join('financial_years AS AY', 'AY.id = ALD.financial_year_id', 'left');	
		$this->db->where('AY.is_running',1); 
		$this->db->where('AY.school_id=AL.school_id'); 

		$this->db->where('AL.category is not null'); 
		$this->db->where("AL.category != ''"); 	
		$this->db->where('AL.id', $id); 			
		return $this->db->get()->row(); 		
	}

	public function get_payscale_category_by_school($table,$school_id){
        $this->db->select('*');
        $this->db->from($table);             
        $this->db->where('school_id', $school_id);   
       $this->db->or_where('school_id','0');    
        return $this->db->get()->result();
        // print_r($this->db->last_query());exit; 
    }
	public function insert_default($school_id, $financial_year_id){
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
			$larr['category']=$l->category;
			$larr['created']= date('Y-m-d H:i:s');
			$larr['modified']= date('Y-m-d H:i:s');
			$this->db->insert('account_ledgers',$larr);
			$ledger_id=$this->db->insert_id();
			
			// insert into ledger detail
			$detail_arr=array();
			$detail_arr['ledger_id']=$ledger_id;
//			$detail_arr['academic_year_id']=$academic_year_id;
			$detail_arr['financial_year_id']=$financial_year_id;
			
			$detail_arr['opening_cr_dr']=$l->dr_cr;
			$ledger_detail_id=$this->db->insert('account_ledger_details',$detail_arr);
		}
	}
	public function financial_year_update($school_id, $financial_year_id){
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
			$larr['category']=$l->category;
			$check = $this->get_single("account_ledgers",  $larr);
			if(empty($check))
			{
				$larr['created']= date('Y-m-d H:i:s');
				$larr['modified']= date('Y-m-d H:i:s');
				$this->db->insert('account_ledgers',$larr);
				$ledger_id=$this->db->insert_id();
			}
			else
			{
				$ledger_id=$check->id;
			}
			
			
			// insert into ledger detail
			$detail_arr=array();
			$detail_arr['ledger_id']=$ledger_id;
//			$detail_arr['academic_year_id']=$academic_year_id;
			$detail_arr['financial_year_id']=$financial_year_id;
			
			$detail_arr['opening_cr_dr']=$l->dr_cr;
			$check = $this->get_single("account_ledger_details",  $detail_arr);
			if(empty($check))
			{
				$ledger_detail_id=$this->db->insert('account_ledger_details',$detail_arr);
			}
		}
	}
	
	public function fix_defualt($school_id, $financial_year_id){
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
			$larr['created']= date('Y-m-d H:i:s');
			$larr['modified']= date('Y-m-d H:i:s');
		}
	}
	public function delete_all($school_id){
		if($school_id)
		{	$this->db->query("delete from account_ledger_details where ledger_id in ( SELECT id FROM `account_ledgers` where school_id= $school_id ) and ledger_id not in (select ledger_id from account_transactions where school_id-$school_id) and ledger_id not in (select ledger_id from account_transactiondetails where school_id-$school_id)  ");	
			$this->db->query("delete  FROM `account_ledgers` where school_id= $school_id and ledger_id not in (select ledger_id from account_transactions where school_id-$school_id) and ledger_id not in (select ledger_id from account_transactiondetails where school_id-$school_id) ");	
		}
	}
	
}
