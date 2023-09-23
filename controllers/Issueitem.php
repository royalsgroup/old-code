<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Issueitem extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('itemissue_model', 'itemissue', true);
		$this->load->model('Accounttransactions_Model', 'transactions', true);			 		
    }

     public function index($school_id = null) {
       
        //check_permission(VIEW);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $school_id=$this->session->userdata('school_id');              
            $condition['school_id'] = $school_id;                    
			$condition['is_active']='yes';
			$this->data['itemcategories'] = $this->itemissue->get_list('item_category', $condition, '','', '', 'id', 'ASC');			
        }   
        
		//$this->data['issue_by']=$this->session->userdata('username'); 
		//$this->data['issue_by_id']=$this->session->userdata('id'); 
		$this->data['itemissue'] = $this->itemissue->get(null,$school_id);	
        $financial_year=$this->transactions->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));	
        $check_financial_year=$this->transactions->get_single('financial_years',array('school_id'=>$school_id,'previous_financial_year_id'=> $financial_year->id));	

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
		$f_start=date('d/m/Y',strtotime($f_start));
        if ($check_financial_year)
        {
            $f_start=date('d/m/Y',strtotime($f_end));
        }
		$f_end=date('d/m/Y',strtotime($f_end));
        $this->data['f_start'] = $f_start;    
		$this->data['f_end'] = $f_end;    
        // echo $this->db->last_query();
        // die();	
		  $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
        //$this->data['itemstores'] = $this->itemstore->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemissue->get_list('themes', array(), '','', '', 'id', 'ASC');
		$this->data['roles'] = $this->itemissue->get_list('roles', array(), '','', '', 'id', 'ASC');
		//$this->data['itemcategories'] = $this->itemissue->get_list('item_category', array(), '','', '', 'id', 'ASC');
		//$this->data['itemstores'] = $this->itemstock->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_item')." ".$this->lang->line('issue') . ' | ' . SMS);

        $this->data['todayDate'] = date("d-m-Y");
        
        $this->layout->view('issueitem/index', $this->data);            
       
    }
  
    public function add() {

        //check_permission(ADD);
        
        if ($_POST) {
            $edited_ledger_ids = [];
			$school=$this->itemissue->get_single('schools', array('id' => $_POST['school_id']));
             $this->_prepare_itemissue_validation();
			if($school->default_voucher_Id_for_inventory > 0){			
            if ($this->form_validation->run() === TRUE) {		
                $invoice_data = $this->_get_posted_invoice_data();		

                $data = $this->_get_posted_itemissue_data();
		
                $invoice_insert_id = $this->itemissue->insert('item_invoices', $invoice_data);
              
               
                if($invoice_insert_id)
                {
                    $voucher_id=$school->default_voucher_Id_for_inventory;
                            //$item_data=$this->itemstock->get_single('item', array('id' => $data['item_id']));
                            if($data['debit_ledger_id']>0 && $data['credit_ledger_id']>0){
                                $edited_ledger_ids[] =  $invoice_data['debit_ledger_id'];
                                $edited_ledger_ids[] =  $invoice_data['credit_ledger_id'];

                                $transaction=array();
                                $transaction['transaction_no']=$this->transactions->generate_transaction_no($voucher_id);
                                $transaction['voucher_id']=$voucher_id;							
                                $transaction['ledger_id']= $invoice_data['debit_ledger_id'];
                                $transaction['head_cr_dr']='DR';									
                                
                                $transaction['date']=date("Y-m-d H:i:s", strtotime($data['issue_date']));						
                                $transaction['created']=date('Y-m-d H:i:s');	
                                $transaction['created_by']=$this->session->userdata('id');		   
                                $transaction['narration']="Sell Items - ".$invoice_data['description'];	
                                
                                $transaction['financial_year_id']=$school->financial_year_id;
                                $transaction['inventory_id']=$invoice_insert_id;

                                //  $transaction['narration']=$data['note'];
                                
                                if(isset($transaction['voucher_id'])){													
                                    $transaction_id = $this->transactions->insert('account_transactions', $transaction);
                                    if ($transaction_id) {								
                                                $detail=array();
                                                $detail['transaction_id']=$transaction_id;
                                                $detail['ledger_id']=$invoice_data['credit_ledger_id'];
                                                $detail['amount']=($invoice_data['grand_total']-$invoice_data['charges']);												
                                                $detail['created']= date('Y-m-d H:i:s');
                                                $detail_id=$this->transactions->insert('account_transaction_details', $detail);
                                                if( $invoice_data['charges'])
                                                {
                                                    $detail=array();
                                                    $detail['transaction_id']=$transaction_id;
                                                    $detail['ledger_id']=$this->input->post('charges_credit_ledger_id');
                                                    $detail['amount']=$invoice_data['charges'];												
                                                    $detail['created']=date('Y-m-d H:i:s');
                                                    $edited_ledger_ids[] =  $this->input->post('charges_credit_ledger_id');
                                                    $detail_id=$this->transactions->insert('account_transaction_details', $detail);
                                                }
                                            } 
                                        }
                                // Update salary paymenmt with transaction id
                                $updated = $this->itemissue->update('item_invoices', array("account_transaction_id"=>$transaction_id), array('id' => $invoice_insert_id));
                            }
                          
                    if (isset($data['item_id']) && count($data['item_id']) > 0) {

                        // echo count($data['item_id']);
    
                        
                        $i = 0;
                        foreach($data['item_id'] as $key => $value){
                            $data1 = [
                                'item_id' =>$value,
                                'school_id'=> $data['school_id'],
                                 'is_returned'=> $data['issue_type'],
                                 'issue_type'=> $data['issue_type'],
                                'debit_ledger_id'=> $data['debit_ledger_id'],
                                'credit_ledger_id'=> $data['credit_ledger_id'],
    
                                'issue_to'=> $data['issue_to'],
                                 'item_category_id'=> $data['item_category_id'],
    
                                'note'=> $data['note'],
                                 'issue_by'=> $data['issue_by'],
                                 'voucher_id'=> $data['voucher_id'],

                                 'invoice_id'=> $invoice_insert_id,
    
                                'issue_date'=> $data['issue_date'],
                                'quantity'=> $data['quantity'][$i],
                                'issue_price'=> $data['price'][$i],
                                'mrp'=> $data['mrp'][$i],
                                'account_transaction_id' => $transaction_id
                                // 'value' => $data1['value'][$key]
    
                            ];
                            $edited_ledger_ids[] =  $data['debit_ledger_id'];
                            $edited_ledger_ids[] =  $data['credit_ledger_id'];
                            //  print_r($data1);
                            // die();
                            if($insert_id = $this->itemissue->insert('item_issue', $data1)){
                                
                            }
                            $i++;
                        }
                        // print_r($insert_id);exit;
                        if(!empty($edited_ledger_ids))
						{
							update_ledger_opening_balance($edited_ledger_ids,$data['school_id']);
						}
                         success($this->lang->line('insert_success'));
                        redirect('issueitem');
                    }      
                }
                else
                {
                    // print_r($insert_id);exit;
                    error($this->lang->line('insert_fail'));
                    redirect('issueitem');
                }
            }
            else
            {
                $this->data['post'] = $_POST;
            }
         
        }
		else{
					error('Please update default voucher for inventory by editing school details.');
					redirect('itemstock/add');
				}
		}

		$this->data['itemissues'] = $this->itemissue->get();
		
	
        //$this->data['itemstores'] = $this->item->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemissue->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('item')." ".$this->lang->line('issue'). ' | ' . SMS);
        $this->layout->view('itemissue/index', $this->data);
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
   
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_itemissue_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      $this->form_validation->set_rules('school_id', $this->lang->line('school_id'), 'trim|required'); 
		$this->form_validation->set_rules('issue_date', $this->lang->line('issue_date'), 'trim|callback_validate_date');    		
		//$this->form_validation->set_rules('item_category_id', $this->lang->line('item_category_id'), 'trim|required');    		
        $this->form_validation->set_rules('credit_ledger_id', $this->lang->line('credit_ledger_id'), 'trim|required');
        $this->form_validation->set_rules('debit_ledger_id', $this->lang->line('debit_ledger_id'), 'trim|required');
        $this->form_validation->set_rules('issue_date', $this->lang->line('issue_date'), 'trim|required');

    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    function validate_date()
    {

        $test_date = $this->input->post('issue_date');
        $test_arr  = explode('/', $test_date);
        if(count($test_arr) <3)
        {
            return false;
        }
        if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
            return true;
        }
        return false;
    }
    
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_itemissue_data() {

        $items = array();
		$items[] = 'school_id';
		$items[] = 'issue_type'; 
		$items[] = 'issue_to';		
		$items[] = 'item_category_id';  		
		$items[] = 'item_id'; 		
		$items[] = 'quantity';
        $items[] = 'mrp'; 
        $items[] = 'price'; 
		$items[] = 'debit_ledger_id';		
		$items[] = 'credit_ledger_id';			
        $items[] = 'voucher_id';	

		$items[] = 'note'; 			
        $data = elements($items, $_POST);	
		$data['issue_by']=$this->session->userdata('id');		

		if(isset($_POST['issue_date']) && $_POST['issue_date']!=''){
            $_POST['issue_date'] = str_replace('/', '-', $_POST['issue_date']);
			$data['issue_date']=date('Y-m-d H:i:s',strtotime($_POST['issue_date']));
		}
		if(isset($_POST['return_date']) && $_POST['return_date']!=''){
            $_POST['return_date'] = str_replace('/', '-', $_POST['return_date']);

			$data['return_date']=date('Y-m-d H:i:s',strtotime($_POST['return_date']));
		}
		if ($this->input->post('id')) {
           // $data['updated_at'] = date('Y-m-d H:i:s');            
        } else {           
            $data['created_at'] = date('Y-m-d H:i:s');
            //$data['updated_at'] = date('Y-m-d H:i:s');
                       
        }   

        return $data;
    }
    private function _get_posted_invoice_data() {

        $items = array();
		$items[] = 'school_id';

		$items[] = 'debit_ledger_id';		
		$items[] = 'credit_ledger_id';
        $items[] = 'voucher_id';

        $items[] = 'total';		
		$items[] = 'grand_total';			
        $items[] = 'discount';		
		$items[] = 'charges';				
       
        $data = elements($items, $_POST);	
        if( $data['discount'])
        {
            $data['discount']  =  $data['total'] * ( $data['discount']/100);
        }
        $data['invoice_type']="issue_item";	
        $data['invoice_no'] = $this->itemissue->generate_invoice_no($data['school_id']); 
		$data['created_by']=$this->session->userdata('id');		   
        $data['created_at'] = date('Y-m-d H:i:s');
       
        return $data;
    }
	public function delete($id = null) {        
      
       // check_permission(DELETE);  
        error_on();
        // echo "string"; 
        $issue_item=  $this->itemissue->get($id);
        if(empty( $issue_item))
        {
            error("Error occured");
            redirect('issueitem');
        }
        $invoice_id = $issue_item['invoice_id'];
        $account_transaction_id = $issue_item['account_transaction_id'];
        if(!$invoice_id || !$account_transaction_id)
        {
            error("Error occured");
            redirect('issueitem');
        }
         $delete = $this->itemissue->delete_invoice_data( $invoice_id,$account_transaction_id );
        // echo $result;exit;
        if ($delete) {
            success($this->lang->line('delete_success'));
            // echo "1";exit;
            
        } else {
            error($this->lang->line('delete_failed'));
            // echo "2";exit;
        }

      
        redirect('issueitem');
    }

    public function view($id)
    {
        $this->data['invoice']  = $this->itemissue->get_invoice($id);
        $school_id              = $this->data['invoice']->school_id;
        $this->data['school']    = $this->itemissue->get_school_by_id($school_id);
        $this->data['items']    = $this->itemissue->get(null,$school_id,$id);
        
        $issue_item=   $this->data['items']['0'] ?? array();
        $this->data['issue_item']  =  $issue_item;
        $this->layout->title($this->lang->line('manage_item')." ".$this->lang->line('issue') . ' | ' . SMS);
        $this->layout->view('issueitem/invoice', $this->data);  
    }

    public function view_old($id)
    {
        $issue_item=  $this->itemissue->get($id);
        $this->data['issue_item']  =  $issue_item;
        $this->data['school']    = $this->itemissue->get_school_by_id($issue_item['school_id']);
        $this->data['items']  = $this->itemissue->get(null,$issue_item['school_id'], $issue_item['invoice_id']);
        
        $this->data['invoice']  = $this->itemissue->get_invoice($issue_item['invoice_id']);
       
        $this->layout->title($this->lang->line('manage_item')." ".$this->lang->line('issue') . ' | ' . SMS);
        $this->layout->view('issueitem/invoice', $this->data);  
    }

	public function getUser() {

        $usertype = $this->input->post('usertype');
		$school_id=$this->input->post('school_id');

        $result_final = array();
        $result = array();
        if ($usertype != "") {
		if($usertype == 16 || $usertype == 1){
			$result=$this->itemissue->get_list('users', array('role_id'=>$usertype), 'id,username','', '', 'id', 'ASC');
		}
		else{
			$result=$this->itemissue->get_list('users', array('role_id'=>$usertype,'school_id'=>$school_id), 'id,username','', '', 'id', 'ASC');
		}
			//print_r($result); 
            //$result = $this->staff_model->getEmployeeByRoleID($usertype);
        }

        $result_final = array('usertype' => $usertype, 'result' => $result);
        echo json_encode($result_final);
    }
	public function returnItem() {

        $issue_id = $this->input->post('item_issue_id');

        if ($issue_id != "") {
            $data = array(
                'id' => $issue_id,
                'is_returned' => 1,
                'return_date' => date('Y-m-d')
            );
            $this->itemissue->add($data);
        }

        $result_final = array('status' => 'pass', 'message' => $this->lang->line('success_message'));
        echo json_encode($result_final);
    }
}
