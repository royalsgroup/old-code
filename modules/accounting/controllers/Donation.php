<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* * *****************Invoice.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Invoice
 * @description     : Manage invoice for all type of student payment.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Donation extends MY_Controller
{

    public $data = array();

    function __construct()
    {
        

        parent::__construct();
        error_on();
        $this->load->model('Donation_Model', 'donation', true);

        $this->load->model('Payment_Model', 'payment', true);
        $this->load->model('Feetype_Model', 'feetype', true);
        $this->load->model('Accounttransactions_Model', 'transactions', true);
        $this->load->model('academic/Classes_Model', 'classes', true);

    }


    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Invoice List" user interface                 
     *                        
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index($school_id = null)
    {

        check_permission(VIEW);
        $condition = array();
        $condition['status'] = 1;
        if ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1) {
            $school_id =  $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->classes->get_list_by_school($condition['school_id']);
            $academic_year=$this->transactions->get_single('academic_years',array('school_id'=>$condition['school_id'],'is_running'=>1));	
        }

        $financial_year=$this->transactions->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));	
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
		$f_end=date('d/m/Y',strtotime($f_end));
		$this->data['f_start'] = $f_start;    
		$this->data['f_end'] = $f_end;    
      
       if(!$school_id)
       {
        error($this->lang->line('select_school'));
       }

        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_donation') . ' | ' . SMS);
        $this->layout->view('donation/index', $this->data);
    }



    /*****************Function view**********************************
     * @type            : Function
     * @function name   : view
     * @description     : Load user interface with specific invoice data                 
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function view($id = null)
    {
       
        check_permission(VIEW);

        if (!is_numeric($id)) {
            error($this->lang->line('unexpected_error'));
            redirect('accounting/invoice/index');
        }
        $reciept = $this->donation->get_donation($id);
		$this->data['reciept'] =$reciept;
        $school_id = $reciept->school_id;
        $this->data['school']   = $this->donation->get_school_by_id($school_id);
        
        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('donation') . ' | ' . SMS);
        $this->layout->view('donation/view', $this->data);
    }
	public function get_list(){		
        // for super admin 
        error_on();
       $school_id = '';
       $start=null;
       $due=null;
       $limit=null;
       $sort_coloumn = "";
       $sort_sort = "";
       $search_text='';
       $start_date = "";
       $end_date = "";
       $order_cols =  array();
       if($_POST){            
           $school_id = $this->input->post('school_id');
           $start = $this->input->post('start');
           $due = $this->input->post('due');
           $start_date = strtotime($this->input->post('start_date')) ? date("Y-m-d",strtotime($this->input->post('start_date'))): '' ;
           $end_date = strtotime($this->input->post('end_date')) ? date("Y-m-d",strtotime($this->input->post('end_date'))) : '';
           $limit  = $this->input->post('length');   
           $order_cols  = $this->input->post('order');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
       }
     
       
       $school = $this->donation->get_school_by_id($school_id);
      // var_dump($order_cols);
       if(!empty($order_cols))
       {
           foreach($order_cols as $order)
           {
               if($order['column'] == 1)
               {
                   $sort_coloumn = "D.reciept_no";
               }
               if($order['column'] == 2)
               {
                   $sort_coloumn = "D.donor_name";
               }
               if($order['column'] == 3)
               {
                   $sort_coloumn = "D.father_name";
               }
               if($order['column'] == 4)
               {
                   $sort_coloumn = "D.donor_phone";
               }
               if($order['column'] == 5)
               {
                   $sort_coloumn = "D.donor_pan";
               }
               if($order['column'] == 6)
               {
                   $sort_coloumn = "D.adhar_no";
               }
               if($order['column'] == 7)
               {
                   $sort_coloumn = "D.amount";
               }
               if($order['column'] == 8)
               {
                   $sort_coloumn = "D.payment_method";
               }
               if($order['column'] == 9)
               {
                   $sort_coloumn = "D.remark";
               }
               $sort_sort = $order['dir'];
           }
       }
       $student_id = "";
      if($this->session->userdata('role_id') == STUDENT)
      {
        $student_id  = $this->session->userdata('id') ;
      }
      $sTest = "";

       if($school_id){
          $totalRecords = $this->donation->get_donation_list_total($school_id, @$school->academic_year_id,$search_text,$start_date,$end_date, $student_id );
          $invoices = $this->donation->get_donation_list( $school_id, @$school->academic_year_id,$start,$limit,$search_text,$sort_coloumn ,$sort_sort,$start_date,$end_date, $student_id );
          $sTest =  $this->db->last_query();

          //   echo $this->db->last_query();
        //   die();
       }
       else
       {
        $totalRecords = 0;
        $invoices = array();
       }
       $condition = array();
        $condition['school_id'] = $school_id;   
       $count = 1; 
       $data = array();
       if(isset($invoices) && !empty($invoices)){
           if($this->session->userdata('role_id') != GUARDIAN || $this->session->userdata('role_id') != TEACHER){
               foreach($invoices as $obj){
                   
                   $action='';
                    if (has_permission(VIEW, 'accounting', 'invoice')) { 
                        $action.= ' <a href="'.site_url('accounting/donation/view/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-eye"></i>'.$this->lang->line("view"); '</a>';
                    } 
                    // if (has_permission(DELETE, 'accounting', 'invoice')) { 
                    //         $action.= ' <a href="'.site_url('accounting/donation/delete/' . $obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'")" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line("delete").' </a>';
                    //  }
                    //  if (has_permission(DELETE, 'accounting', 'invoice')) { 
                    //         $action.= ' <a href="'.site_url('accounting/donation/delete_invoice/' . $obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'")" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line("delete").' </a>';
                    //  }
                    $row_data = array();
                    $row_data[] = $obj->date;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                   $row_data[] = $obj->reciept_no;
                   $row_data[] = $obj->donor_name;
                   $row_data[] = $obj->father_name;
                   $row_data[] = $obj->donor_phone;
                   $row_data[] = $obj->donor_pan ? $obj->donor_pan  : '';
                   $row_data[] = $obj->adhar_no;
                   $row_data[] = $obj->amount;
                   $row_data[] = ucfirst($obj->payment_method);
                   $row_data[] =  $obj->remark;
                   $row_data[] = $obj->credit_ledger;
                   $row_data[] =  $obj->debit_ledger;
                   $row_data[] =  $obj->voucher;

                   $row_data[] =  $action;

                    $data[] = $row_data;
                      $count++;
               }
           }
       }
       else{
           $data=array();
       }
       //print_r($data); exit;
       $response = array(
        "test" => $sTest,
 "draw" => intval($draw),
 "iTotalRecords" => $totalRecords,
 "iTotalDisplayRecords" => $totalRecords,
 "aaData" => $data
);
echo json_encode($response);
exit;
   }

    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Create new Invoice" user interface                 
     *                    and store "Invoice" data into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add($school_id = null)
    {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
                $error = 0;
                $data = $this->_get_posted_reciept_data();         
                 
                $insert_id = $this->donation->insert('donations', $data);

                if ($insert_id) {
                    // create transaction with that voucher id
                    $transaction = array();
                    $transaction['transaction_no'] = $this->transactions->generate_transaction_no($data['voucher_id']);
                    $transaction['voucher_id'] = $data['voucher_id'];;
                    $transaction['donation_id'] = $insert_id;
                    $transaction['ledger_id'] = $data['credit_ledger_id'];
                    $edited_ledger_ids[] = $data['credit_ledger_id'];

                    $transaction['bank_name'] = $this->input->post('bank_name');
                    $transaction['cheque_no'] = $this->input->post('cheque_no');
                    $transaction['head_cr_dr'] = 'CR';
                    $transaction['school_id']=$data['school_id'];
                    $transaction['date'] = date("Y-m-d",strtotime($data['date']));
                    $transaction['created'] = date('Y-m-d H:i:s');
                    $transaction['created_by'] = logged_in_user_id();										
                    $transaction['narration'] = $data['remark'];										

                    // if ($this->form_validation->run() === TRUE) {	
                    if (isset($transaction['voucher_id'])) {
                        //$data = $this->_get_posted_transaction_data();
                        
                        $transaction_id = $this->transactions->insert('account_transactions', $transaction);
                        
                        if ($transaction_id) {
                            // add details to transaction details									
                            $detail = array();
                            $detail['transaction_id'] = $transaction_id;
                            $detail['ledger_id'] = $data['debit_ledger_id'];
                            $edited_ledger_ids[] = $data['debit_ledger_id'];

                            $detail['amount'] = $data['amount'];
                            $detail['created'] = date('Y-m-d H:i:s');
                            $detail['school_id']=$data['school_id'];
                            $detail['remark']= "Donation From :".$data['donor_name'];
                            $detail_id = $this->transactions->insert('account_transaction_details', $detail);

                            $updated = $this->donation->update('donations', array("account_transaction_id" => $transaction_id), array('id' => $insert_id));
                        }
                    }                  
                    create_log('Has been created a Donation : ' . $data['amount']);
                    if(!empty($edited_ledger_ids))
                    {
                        update_ledger_opening_balance($edited_ledger_ids,$data['school_id']);
                    }
                    // save transction table data
                    $data['reciept_id'] = $insert_id;
                    $this->_save_transaction($data);
                    success($this->lang->line('insert_success'));
                    redirect('accounting/donation/view/'.$insert_id);
                } else {
                   
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/donation/add');
                }
            } else {
               
                $this->data['post'] = $_POST;
            }

            $school_id = $this->input->post('school_id');
        }

        $condition = array();
        $condition['status'] = 1;
        if ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1) {
            $school_id = $this->session->userdata('school_id');
            $condition['school_id'] = $school_id;
            $this->data['classes'] = $this->classes->get_list_by_school($condition['school_id']);
            $academic_year=$this->transactions->get_single('academic_years',array('school_id'=>$condition['school_id'],'is_running'=>1));	

        }

     
        $financial_year=$this->transactions->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));	
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
		$f_end=date('d/m/Y',strtotime($f_end));
		$this->data['f_start'] = $f_start;    
		$this->data['f_end'] = $f_end;    
        $this->data['school_id'] = $school_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['single'] = TRUE;
        $this->layout->title($this->lang->line('create') . ' ' . $this->lang->line('donation') . ' | ' . SMS);
        $this->layout->view('donation/index', $this->data);
    }

    /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Invoice" user interface                 
     *                    with populated "Invoice" value 
     *                    and update "Invoice" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null)
    {

        check_permission(EDIT);

        if (!is_numeric($id)) {
            error($this->lang->line('unexpected_error'));
            redirect('accounting/invoice/index');
        }

        if ($_POST) {
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_invoice_data();
                $updated = $this->invoice->update('invoices', $data, array('id' => $this->input->post('id')));

                if ($updated) {

                    create_log('Has been updated a invoice : ' . $data['net_amount']);

                    success($this->lang->line('update_success'));
                    redirect('accounting/invoice/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('accounting/invoice/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['invoice'] = $this->invoice->get_single('invoices', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['invoice'] = $this->invoice->get_single('invoices', array('id' => $id));

            if (!$this->data['invoice']) {
                redirect('accounting/invoice/index');
            }
        }

        $condition = array();
        $condition['status'] = 1;
        if ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1) {
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->invoice->get_list('classes', $condition, '', '', '', 'id', 'ASC');
        }

        // default global income head
        $this->data['income_heads'] = $this->invoice->get_list('income_heads', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['invoices'] = $this->invoice->get_invoice_list();

        $this->data['school_id'] = $this->data['invoice']->school_id;

        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('invoice') . ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }


    /*****************Function _prepare_invoice_validation**********************************
     * @type            : Function
     * @function name   : _prepare_invoice_validation
     * @description     : Process "Invoice" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_invoice_validation()
    {
   
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');

        $this->form_validation->set_rules('donor_name', $this->lang->line('student_id'), 'trim|required');
        $this->form_validation->set_rules('donor_pan', $this->lang->line('amont'), 'trim|required');
        $this->form_validation->set_rules('amount', "amount", 'trim|required');
        $this->form_validation->set_rules('payment_method', "payment_method", 'trim|required');
        $this->form_validation->set_rules('debit_ledger_id', "debit_ledger_id", 'trim|required');
        $this->form_validation->set_rules('credit_ledger_id', "credit_ledger_id", 'trim|required');
        $this->form_validation->set_rules('voucher_id', "voucher_id", 'trim|required');

        
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required');
    }


    /*****************Function _get_posted_invoice_data**********************************
     * @type            : Function
     * @function name   : _get_posted_invoice_data
     * @description     : Prepare "Invoice" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_reciept_data()
    {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'donor_name';
        $items[] = 'donor_pan';
        $items[] = 'donor_address';
        $items[] = 'amount';
        $items[] = 'donor_phone';
        $items[] = 'father_name';
        $items[] = 'debit_ledger_id';
        $items[] = 'credit_ledger_id';
        $items[] = 'date';
        $items[] = 'adhar_no';
        $items[] = 'remark';
        $items[] = 'debit_ledger_id';
        $items[] = 'debit_ledger_id';
        $items[] = 'payment_method';
        $items[] = 'voucher_id';

        
        $data = elements($items, $_POST);
        $data['date'] = date('Y-m-d',strtotime($data['date']));

		$data['reciept_no']=$this->donation->generate_reciept_no($this->input->post('school_id')); 
        if ($this->input->post('id')) {

            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $school = $this->donation->get_school_by_id($data['school_id']);

            if (!$school->academic_year_id) {
                error($this->lang->line('set_academic_year_for_school'));
                redirect('accounting/invoice/index');
            }
            $data['academic_year_id'] = $school->academic_year_id;
            $data['financial_year_id'] = $school->financial_year_id;

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
           
        }
        return $data;
    }
    private function _save_transaction($data)
    {
            $txn = array();
            $txn['school_id'] = $data['school_id'];
            $txn['amount']    = $data['amount'];
            $txn['note'] = $data['remark'];
            $txn['payment_date'] = $data['date'];
            $txn['payment_method'] = $this->input->post('payment_method');
            $txn['bank_name'] = $this->input->post('bank_name');
            $txn['cheque_no'] = $this->input->post('cheque_no');

            if ($this->input->post('id')) {

                $txn['modified_at'] = date('Y-m-d H:i:s');
                $txn['modified_by'] = logged_in_user_id();
                $this->donation->update('transactions', $txn, array('reciept_id' => $this->input->post('id')));
            } else {

                $txn['reciept_id'] = $data['reciept_id'];
                $txn['status'] = 1;
                $txn['academic_year_id'] = $data['academic_year_id'];
                $txn['created_at'] = $data['created_at'];
                $txn['created_by'] = $data['created_by'];
                $this->donation->insert('transactions', $txn);
            }
    }
}
