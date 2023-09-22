<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Itemstock extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Itemstock_Model', 'itemstock', true);
		$this->load->model('Item_Model', 'item', true);		
		$this->load->model('Accounttransactions_Model', 'transactions', true);			 
    }

     public function index($school_id = null) {
       
        //check_permission(VIEW);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){  
			$school_id=$this->session->userdata('school_id');                    
            $condition['school_id'] = $school_id;                    
			$condition['is_active']='yes';
			$this->data['itemcategories'] = $this->itemstock->get_list('item_category', $condition, '','', '', 'id', 'ASC');
			$this->data['itemsuppliers'] = $this->itemstock->get_list('item_supplier', array('school_id'=>$condition['school_id']), '','', '', 'id', 'ASC');
			$this->data['itemstores'] = $this->itemstock->get_list('item_store', array('school_id'=>$condition['school_id']), '','', '', 'id', 'ASC');
        }   
        if($school_id)
		{
			$this->data['items'] = $this->item->getItemBySchool($school_id);  
		}
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
		$financial_start_date = $f_start;
		$financial_end_date = $f_end;
		$this->data['itemstocks'] = $this->itemstock->get(null,$school_id, null,$financial_start_date, $financial_end_date);

		$f_start=date('d/m/Y',strtotime($f_start));
		if ($check_financial_year)
        {
            $f_start=date('d/m/Y',strtotime($f_end));
        }
		$f_end=date('d/m/Y',strtotime($f_end));
		$this->data['f_start'] = $f_start;    
		$this->data['f_end'] = $f_end;    
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	        
        $this->data['themes'] = $this->itemstock->get_list('themes', array(), '','', '', 'id', 'ASC');		
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_item')." ".$this->lang->line('stock') . ' | ' . SMS);
        $this->layout->view('itemstock/index', $this->data);            
       
    }

    public function addCommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 1;

      //  $commissionList = $this->common_model->get_list($service, $baseRole);

// $this->layout->view('issueitem/index', $this->data);    
        echo $this->load->view('issueitem/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
  public function view_old($id)
  {
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
	  $itemstock=  $this->itemstock->get($id);
	//   var_dump($itemstock);
	//   die();

	if(!isset($itemstock['invoice_id']))
	{
		error('error');
		redirect('itemstock/index');
	}
	$invoice  = $this->itemstock->get_invoice($itemstock['invoice_id']);
	if(empty( $invoice))
	{
		error('error');
		redirect('itemstock/index');
	}
	$invoice->supplier = $itemstock['item_supplier'];
	$this->data['invoice'] = $invoice;
	  $this->data['itemstock']  =  $itemstock;
	  $this->data['school']    = $this->itemstock->get_school_by_id($itemstock['school_id']);
	  $this->data['items']  = $this->itemstock->get(null,$itemstock['school_id'], $itemstock['invoice_id']);

	
	  $this->layout->title($this->lang->line('manage_item')." ".$this->lang->line('issue') . ' | ' . SMS);
	  $this->layout->view('itemstock/view', $this->data);  
  }
  public function view($id)
  {
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
	
	$invoice  = $this->itemstock->get_invoice($id);
	if(empty( $invoice))
	{
		error('error');
		redirect('itemstock/index');
	}
	$school_id              = $invoice->school_id;

	$this->data['invoice'] = $invoice;
	  $this->data['school']    = $this->itemstock->get_school_by_id($school_id);
	  $this->data['items']  = $this->itemstock->get(null,$invoice->school_id, $id);
	  $this->data['itemstock']  =  $this->data['items']['0'] ?? array();
	  $invoice->supplier = $this->data['itemstock']['item_supplier'];

	
	  $this->layout->title($this->lang->line('manage_item')." ".$this->lang->line('issue') . ' | ' . SMS);
	  $this->layout->view('itemstock/view', $this->data);  
  }
  public function delete($id = null) {        
	
   // check_permission(DELETE);  

	// echo "string"; 
	$itemstock=  $this->itemstock->get($id);
	if(empty( $itemstock))
	{
		error("Error occured");
		redirect('issueitem');
	}
	$invoice_id = $itemstock['invoice_id'];
	$account_transaction_id = $itemstock['account_transaction_id'];
	if(!$invoice_id || !$account_transaction_id)
	{
		error("Error occured");
		redirect('issueitem');
	}
	 $delete = $this->itemstock->delete_invoice_data( $invoice_id,$account_transaction_id );
	// echo $result;exit;
	if ($delete) {
		success($this->lang->line('delete_success'));
		// echo "1";exit;
		
	} else {
		error($this->lang->line('delete_failed'));
		// echo "2";exit;
	}

  
	redirect('itemstock');
}

  
    public function add() {
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		
        //check_permission(ADD);
        
        if ($_POST) {	
			$edited_ledger_ids = [];

            $this->_prepare_itemstock_validation();
			$school_id=$this->input->post('school_id');   
			$stock_adjustment =$this->input->post('stock_adjustment');       
			if($school_id)
			{
				$this->data['items'] = $this->item->getItemBySchool($school_id);  
			}
		
            if ($this->form_validation->run() === TRUE && !empty($_POST['item_id'])) {
				$school=$this->itemstock->get_single('schools', array('id' => $_POST['school_id']));
				if($school->default_voucher_Id_for_inventory > 0 || $stock_adjustment){	
					$invoice_data = $this->_get_posted_invoice_data();		


				   // echo "<pre>";
					// print_r($data); echo "<pre>";
					if (!$stock_adjustment)
						$invoice_insert_id = $this->itemstock->insert('item_invoices', $invoice_data);
					$data = $this->_get_posted_itemstock_data();

				   
					if($invoice_insert_id || $stock_adjustment)
					{			
						if($invoice_insert_id)
						{
								$transaction_id = 0;
								$voucher_id=$school->default_voucher_Id_for_inventory;
								//$item_data=$this->itemstock->get_single('item', array('id' => $data['item_id']));
								if($invoice_data['debit_ledger_id']>0 && $invoice_data['credit_ledger_id']>0){

									$transaction=array();
									$transaction['transaction_no']=$this->transactions->generate_transaction_no($voucher_id);
									$transaction['voucher_id']=$voucher_id;
									$transaction['ledger_id']=$invoice_data['debit_ledger_id'];
									$transaction['head_cr_dr']='DR';							
									$edited_ledger_ids[] =  $invoice_data['debit_ledger_id'];
		
									
									$transaction['date']= date("Y-m-d H:i:s", strtotime($data['date']));				
									$transaction['created']=date('Y-m-d H:i:s');	
									$transaction['financial_year_id']=$school->financial_year_id;
									$transaction['created_by']=$this->session->userdata('id');		   
									$transaction['narration']= "Purchase Items - ".$data['note'];		   
									$transaction['inventory_id']=$invoice_insert_id;

									
									if(isset($transaction['voucher_id'])){		
																				
										$transaction_id = $this->transactions->insert('account_transactions', $transaction);
										
										if ($transaction_id) {								
													$detail=array();
													$detail['transaction_id']=$transaction_id;
													$detail['ledger_id']=$invoice_data['credit_ledger_id'];
													$edited_ledger_ids[] =  $invoice_data['credit_ledger_id'];

													$amt=$data1['purchase_price']*$data1['quantity'];
													$detail['amount']=($invoice_data['grand_total']-$invoice_data['charges']);								
													$detail['created']=date('Y-m-d H:i:s');
													$detail_id=$this->transactions->insert('account_transaction_details', $detail);
													if( $invoice_data['charges'])
													{
														$detail=array();
														$detail['transaction_id']=$transaction_id;
														$detail['ledger_id']=$this->input->post('charges_credit_ledger_id');
														$edited_ledger_ids[] =  $this->input->post('charges_credit_ledger_id');

														$detail['amount']=$invoice_data['charges'];												
														$detail['created']= date('Y-m-d H:i:s');
														
														$detail_id=$this->transactions->insert('account_transaction_details', $detail);
													}
												} 
											}
									// Update salary paymenmt with transaction id
									$updated = $this->itemstock->update('item_invoices', array("account_transaction_id"=>$transaction_id), array('id' => $invoice_insert_id));

								}
							}

						if($transaction_id || $stock_adjustment)
						{

							foreach($data['item_id'] as $key => $value){
								$data1 = $data;
								$data1['item_id'] = $value;
								$data1['quantity'] =  $data['quantity'][$key];
								$data1['purchase_price'] = $data['purchase_price'][$key];
								$data1['mrp'] = $data['mrp'][$key];

								$data1['invoice_id'] = $invoice_insert_id ;
								$data1['account_transaction_id'] = $transaction_id;
	
								$insert_id = $this->itemstock->insert('item_stock', $data1);

								if ($insert_id) {
									if (isset($_FILES["item_photo"]) && !empty($_FILES['item_photo']['name'])) 
									{
										$fileInfo = pathinfo($_FILES["item_photo"]["name"]);
										$img_name = $insert_id . '.' . $fileInfo['extension'];
										$destination  = "./assets/uploads/inventory_items/";
										move_uploaded_file($_FILES["item_photo"]["tmp_name"], $destination . $img_name);
										if(is_image( $destination . $img_name))
										{
											if($converted_file = webpConverter($destination . $img_name))
											{
												$img_name = get_filename($converted_file);
											}
										}
										$data_img = array('id' => $insert_id, 'attachment' => $destination . $img_name);
										//$this->itemstock->add($data_img);
										

										$updated = $this->itemstock->update('item_stock', $data_img, array('id' => $insert_id));
									}
									$updated = $this->itemstock->update('item', array("last_purchase_value"=>$data1['purchase_price'], 'mrp'=>$data1['mrp']), array('id' =>  $value));
									
								} else {
									error($this->lang->line('insert_failed'));
									redirect('itemstock/add');
								}
							}
						}
						if(!empty($edited_ledger_ids))
						{
							update_ledger_opening_balance($edited_ledger_ids,$data['school_id']);
						}
						// echo "end";
						// die();
							success($this->lang->line('insert_success'));
							redirect('itemstock');
					}
					else
					{
						error('insert_faild');
						redirect('itemstock/add');
					}
				}
				else{
					error('Please update default voucher for inventory by editing school details.');
					redirect('itemstock/add');
				}
            } else {
				
                $this->data = $_POST;
            }
        }

		$this->data['itemstocks'] = $this->itemstock->get(null,$school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		//$this->data['itemcategories'] = $this->itemstock->get_list('item_category', array(), '','', '', 'id', 'ASC');
		//$this->data['itemsuppliers'] = $this->itemstock->get_list('item_supplier', array(), '','', '', 'id', 'ASC');
		//$this->data['itemstores'] = $this->itemstock->get_list('item_store', array(), '','', '', 'id', 'ASC');
        //$this->data['itemstores'] = $this->item->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemstock->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('item')." ".$this->lang->line('stock'). ' | ' . SMS);
        $this->layout->view('itemstock/index', $this->data);
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
            $this->_prepare_itemstock_validation();
		
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_itemstock_data();				
				$update_id=$this->input->post('id');								               
				$updated = $this->itemstock->update('item_stock', $data, array('id' => $this->input->post('id')));
				
                if ($updated) {
                     if (isset($_FILES["item_photo"]) && !empty($_FILES['item_photo']['name'])) {
						 
						$fileInfo = pathinfo($_FILES["item_photo"]["name"]);
						$img_name = $update_id . '.' . $fileInfo['extension'];
						$path='assets/uploads/inventory_items/'.$img_name;
						if (file_exists($path)) {
							unlink($path);
						}
						move_uploaded_file($_FILES["item_photo"]["tmp_name"], "./assets/uploads/inventory_items/" . $img_name);
						$data_img = array('attachment' => 'assets/uploads/inventory_items/' . $img_name);
						//$this->itemstock->add($data_img);
						$upd = $this->itemstock->update('item_stock', $data_img, array('id' => $update_id));
					 }
                   // create_log('Has been updated a school : '.$data['name']);
				   // edit transaction detail also
				   $istock=$this->itemstock->get_single('item_stock', array('id' => $update_id));
				   $transaction_id=$istock->account_transaction_id;				   
				   $new_amount=$_POST['purchase_price']*$_POST['quantity'];
				   
				   
				   $update_arr=array();
					$update_arr['amount']=$new_amount;
					$updated = $this->itemstock->update('account_transaction_details', $update_arr, array('transaction_id' => $transaction_id));				   
                    success($this->lang->line('update_success'));
                    redirect('itemstock');    
                    
                } else {                    
                    error($this->lang->line('update_failed'));
                    redirect('itemstock/edit/' . $this->input->post('id'));
                    
                }
            } else {
				die();
                 $this->data['itemstock'] = $this->itemstock->get($this->input->post('id'));
            }
        } else {
            if ($id) {
                $this->data['itemstock'] =  $this->itemstock->get($id);;					
                if (!$this->data['itemstock']) {
                     redirect('itemstock');
                }
            }
        }		
		$this->data['itemstocks'] = $this->itemstock->get(null,$school_id);		
		if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }           
        $this->data['school_id'] = $this->data['itemstock']['school_id'];
        $this->data['filter_school_id'] = $this->data['itemstock']['school_id'];   
		$this->data['schools'] = $this->schools;
	//	$this->data['itemcategories'] = $this->itemstock->get_list('item_category', array(), '','', '', 'id', 'ASC');
	//	$this->data['itemsuppliers'] = $this->itemstock->get_list('item_supplier', array(), '','', '', 'id', 'ASC');
	//	$this->data['itemstores'] = $this->itemstock->get_list('item_store', array(), '','', '', 'id', 'ASC');
        //$this->data['itemstores'] = $this->item->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemstock->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('item'). ' | ' . SMS);
        $this->layout->view('itemstock/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_itemstock_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      $this->form_validation->set_rules('school_id', $this->lang->line('category'), 'trim|required'); 
        $this->form_validation->set_rules('item_category_id', $this->lang->line('category'), 'trim'); 
		$this->form_validation->set_rules('item_id', $this->lang->line('item'), 'trim');    		
		$this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required');    		
		$this->form_validation->set_rules('store_id', $this->lang->line('store'), 'trim|required');    		
		$this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'trim');
		$this->form_validation->set_rules('purchase_price', $this->lang->line('purchase_price'), 'trim');
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
   
    
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_itemstock_data() {

        $items = array();     
		$items[] = 'school_id'; 		
		$items[] = 'supplier_id';
		$items[] = 'store_id'; 
		$items[] = 'item_id'; 		
		$items[] = 'quantity';
        $items[] = 'purchase_price'; 
		$items[] = 'mrp'; 
		$items[] = 'debit_ledger_id';		
		$items[] = 'credit_ledger_id';
		$items[] = 'voucher_id';		
		$items[] = 'description'; 		
        $data = elements($items, $_POST);
		$data['quantity']=$_POST['quantity'];
		// $school = $this->itemstock->get_school_by_id($data['school_id']);

		// $data['financial_year_id'] = @$school->financial_year_id;

		if(isset($_POST['date']) && $_POST['date']!=''){
			$_POST['date'] = str_replace('/', '-', $_POST['date']);

			$data['date']=date('Y-m-d H:i:s',strtotime($_POST['date']));
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
        $items[] = 'total';		
		$items[] = 'grand_total';			
        $items[] = 'discount';		
		$items[] = 'charges';				
		$items[] = 'voucher_id';

        $data = elements($items, $_POST);	
        if( $data['discount'])
        {
            $data['discount']  =  $data['total'] * ( $data['discount']/100);
        }
        $data['invoice_type']="item_stock";	
        $data['invoice_no'] = $this->itemstock->generate_invoice_no($data['school_id']); 
		$data['created_by']=$this->session->userdata('id');		   
        $data['created_at'] = date('Y-m-d H:i:s');
       
        return $data;
    }

	function getItemByCategory() {
        $item_category_id = $this->input->get('item_category_id');
        $data = $this->item->getItemByCategory($item_category_id);
        echo json_encode($data);
    }
	function getItemBySchool() {
        $school_id = $this->input->post('school_id');
        $data = $this->item->getItemBySchool($school_id);
        echo json_encode($data);
    }
	
     function getItemunit() {
        $id = $this->input->get('id');
        $data = $this->item->getItemunit($id);
        echo json_encode($data);
    }

}
