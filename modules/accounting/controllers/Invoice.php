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

class Invoice extends MY_Controller
{

    public $data = array();

    function __construct()
    {
        
        parent::__construct();
        // m_mode_on();
        $this->load->model('Invoice_Model', 'invoice', true);
        $this->load->model('Payment_Model', 'payment', true);
        $this->load->model('Feetype_Model', 'feetype', true);
        $this->load->model('Accounttransactions_Model', 'transactions', true);
        $this->load->model('academic/Classes_Model', 'classes', true);
    }

    public function emi()
    {

        if ($_POST) {

            $data = $this->security->xss_clean($_POST);
            
            if (isset($data['emidata'])) {

                $baseRole = $data['emidata'];

                echo $this->load->view('invoice/add', $this->data, true);
            } else {
                echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
            }
        }
    }

    public function emi_find()
    {
        $response = array();
        if ($_POST) {

            $data = $this->security->xss_clean($_POST);

            if (isset($data['feetype'])) {

                $sql = "SELECT * FROM income_heads JOIN emi_fee ON income_heads.id = emi_fee.income_heads_id WHERE income_heads.id = '{$data['feetype']}'";

                $data1 =  $this->db->query($sql)->result();

                $str = '<option value="">--' . $this->lang->line('select') . '--</option>';

                $select = 'selected="selected"';
                if (!empty($data1)) {
                    foreach ($data1 as $obj) {
                        $response['emi_type'] = !$obj->emi_type || ($obj->emi_type && $obj->emi_type=="percentage") ? "percentage" : "amount";     
                        $percentage = !$obj->emi_type || ($obj->emi_type && $obj->emi_type=="percentage") ? "%" : ""; 
                        $selected = $data1 == $obj->id ? $select : '';
                        $str .= '<option data-value="'.$obj->emi_per.'" value="' . $obj->id . '" ' . $selected . '>' . $obj->emi_name . ' [' . $obj->emi_per . ''.$percentage.']</option>';
                    }
                };
                $response['options'] = $str;
                echo json_encode($response);
            } else {
                echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
            }
        }
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
            if (($_GET['debug_mode'] ?? "") || ($_SESSION['debug_mode'] ?? ""))
            {
                $_SESSION['debug_mode'] = true;
                $academic_year->id = 1468;
            }
            //  $this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['income_heads'] = $this->invoice->get_fee_type($condition['school_id'] ,$academic_year->id);
            // echo $this->db->last_query();
            // die();
        }

        // default global income head       
       // $this->data['invoices'] = $this->invoice->get_invoice_list($school_id);
       $this->data['f_start'] = false;    
       $this->data['f_end'] = false;   
       if(!$school_id)
       {
        error($this->lang->line('select_school'));
       }
       else
       {
        
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
       }
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_invoice') . ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
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

        $invoice = $this->payment->get_invoice_amount($id);
        $this->data['paid_amount'] = $invoice->paid_amount;
         $invoice= $this->invoice->get_single_invoice($id);
         $emi_data = array();
         $invoice->paid_amount = $this->invoice->get_paid_fee_amount_year($invoice->student_id,$invoice->income_head_id,$invoice->academic_year_id);
         if($invoice->emi_type)
         {
            $emi_datas_raw = $this->invoice->get_previous_emi_data($id ,$invoice->student_id,$invoice->income_head_id,$invoice->academic_year_id);
            foreach( $emi_datas_raw as  $emi_data_raw)
            {
                $emi_data[ $emi_data_raw->emi_type]  = $emi_data[ $emi_data_raw->emi_type] ?? 0;
                $emi_data[ $emi_data_raw->emi_type]  = $emi_data[ $emi_data_raw->emi_type]  + $emi_data_raw->net_amount;
            }
        }       
		 if($invoice->invoice_no != ''){
            $fee_detail = $this->invoice->get_list_by_invoice_no($invoice->invoice_no,$invoice->school_id,$invoice->student_id);
            $head_type= 0;
            $detail_paid_amount =0;
            $fee_details = array();
            foreach($fee_detail as $detail_invoice)
            {   
                if($head_type != $detail_invoice->income_head_id)
                {
                    $head_type = $detail_invoice->income_head_id;
                    $detail_paid_amount = $this->invoice->get_paid_fee_amount_year($detail_invoice->student_id,$detail_invoice->income_head_id,null,$detail_invoice->created_at);
                    $detail_invoice->paid_amount = $detail_paid_amount;
                }
                else 
                {
                    $detail_invoice->paid_amount =  $detail_paid_amount;
                }
                $fee_details[] = $detail_invoice;
            }
            $invoice->detail=$fee_details;
		 }
        //  debug_a($emi_data);

		$this->data['invoice'] =$invoice;
        $this->data['emi_data'] =$emi_data;
        $school_id = $this->data['invoice']->school_id;
        $this->data['school']   = $this->invoice->get_school_by_id($school_id);

        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('invoice') . ' | ' . SMS);
        if ($this->input->get('debug') ==1) {
            $this->layout->view('invoice/view_dev', $this->data);
        }
        else {
            $this->layout->view('invoice/view', $this->data);
        }
    }


    /*****************Function due**********************************
     * @type            : Function
     * @function name   : due
     * @description     : Load "Due Invoice List" user interface                 
     *                        
     * @param           : null
     * @return          : null 
     * ***********************************************************/
    public function due($school_id = null)
    {

        check_permission(VIEW);
        if(!$school_id)
       
        $school = $this->invoice->get_school_by_id($school_id);
        //$this->data['invoices'] = $this->invoice->get_invoice_list_ajax( $school_id,'due',@$school->academic_year_id);
        //die();
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        {
            error($this->lang->line('select_school'));
        }
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('due_invoice') . ' | ' . SMS);
        $this->layout->view('invoice/due', $this->data);
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
           $start_date = strtotime($this->input->post('start_date')) ? $this->input->post('start_date'): '' ;
           $end_date = strtotime($this->input->post('end_date')) ? $this->input->post('end_date') : '';
           $limit  = $this->input->post('length');   
           $order_cols  = $this->input->post('order');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN)
       {
           $school_id = $this->session->userdata('school_id');
       }
     
       
       $school = $this->invoice->get_school_by_id($school_id);
      // var_dump($order_cols);
       if(!empty($order_cols))
       {
           foreach($order_cols as $order)
           {
               if($order['column'] == 12)
               {
                //    $sort_coloumn = "I.paid_status";
                   $sort_coloumn = "I.payment_method";
               }
               if($order['column'] == 13)
               {
                //    $sort_coloumn = "I.paid_status";
                   $sort_coloumn = "I.discount_type";
               }
               elseif($order['column'] == 0)
               {
                   $sort_coloumn = "I.month";
               }
               elseif($order['column'] == 1)
               {
                   $sort_coloumn = "I.custom_invoice_id";
               }
               elseif($order['column'] == 11)
               {
                   $sort_coloumn = "EF.emi_name";
               }
               elseif($order['column'] == 3)
               {
                   $sort_coloumn = "S.name";
               }
               elseif($order['column'] == 4)
               {
                   $sort_coloumn = "S.father_name";
               }
               elseif($order['column'] == 2)
               {
                   $sort_coloumn = "I.id";
               }
               elseif($order['column'] == 5)
               {
                   $sort_coloumn = "C.name";
               }
               elseif($order['column'] == 6)
               {
                   $sort_coloumn = "IH.title";
               }
               elseif($order['column'] == 7)
               {
                   $sort_coloumn = "I.gross_amount";
               }    
               elseif($order['column'] == 8)
               {
                   $sort_coloumn = "I.discount";
               }  
               elseif($order['column'] == 9)
               {
                   $sort_coloumn = "I.net_amount";
               }  
               elseif($order['column'] == 10)
               {
                   $sort_coloumn = "I.due_amount";
               }   
               elseif($order['column'] == 16)
               {
                   $sort_coloumn = "account_ledger";
               }      
               $sort_sort = $order['dir'];

             
           }
       }
       $student_id = "";
      if($this->session->userdata('role_id') == STUDENT)
      {
        $student_id  = $this->session->userdata('id') ;
      }
       
       if($school_id){
            $totalRecords = $this->invoice->get_invoice_list_total($school_id,$due,@$school->academic_year_id,$search_text,$start_date,$end_date, $student_id );
            $invoices = $this->invoice->get_invoice_list_ajax( $school_id,$due,@$school->academic_year_id,$start,$limit,$search_text,$sort_coloumn ,$sort_sort,$start_date,$end_date, $student_id );
       }
       else
       {
            $totalRecords = 0;
            $invoices = array();
       }
       $condition = array();
        $condition['school_id'] = $school_id;   
        $hostel_rooms = $this->invoice->get_list('rooms', $condition);
        $hostel_rooms_list = [];
        foreach($hostel_rooms as $hostel_room) {
            $hostel_rooms_list[$hostel_room->id] = $hostel_room->room_no."[".$hostel_room->room_type."]";
        }
        $hostels = $this->invoice->get_list('hostels', $condition);
        $hostel_list = [];
        foreach($hostels as $hostel) {
            $hostel_list[$hostel->id] = $hostel->name;
        }
        $route_stops = $this->invoice->get_list('route_stops', $condition);
        $stop_list = [];
        foreach($route_stops as $route_stop) {
            $stop_list[$route_stop->id] = $route_stop->stop_name;
        }
        $condition['status'] = 1;        
        $routes = $this->invoice->get_list('routes', $condition);

        $route_list = [];
        foreach($routes as $route) {
            $route_list[$route->id] = $route->title;
        }
       $count = 1; 
       $data = array();
        $sTest = "";
       if(isset($invoices) && !empty($invoices)){
           if($this->session->userdata('role_id') != GUARDIAN || $this->session->userdata('role_id') != TEACHER){
               foreach($invoices as $obj){
                   
                   $action='';
                    if (has_permission(VIEW, 'accounting', 'invoice')) { 
                        $action.= ' <a href="'.site_url('accounting/invoice/view/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-eye"></i>'.$this->lang->line("view"); '</a>';
                    } 
                    if (has_permission(DELETE, 'accounting', 'invoice')) { 
                        if ($obj->paid_status == 'unpaid') { 
                            $action.= ' <a href="'.site_url('accounting/invoice/delete/' . $obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'")" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line("delete").' </a>';
                        } 
                     }
                     if (has_permission(DELETE, 'accounting', 'invoice')) { 
                        if ($obj->paid_status == 'paid') { 
                            $action.= ' <a href="'.site_url('accounting/invoice/revert/' . $obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'")" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>Revert </a>';
                        } 
                     }
                     if (is_dev()) { 
                        if ($obj->paid_status == 'paid') { 
                            $action.= ' <a href="'.site_url('accounting/invoice/revert/' . $obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'")" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>Revert </a>';
                        } 
                     }
                     $net_amount =  $obj->net_amount;
                     if ($obj->emi_type) {
                        $net_amount .= "(EMI)";
                    }	
                    
                    if ($obj->due_amount == 0 && $obj->paid_status == "paid") {
                        $due_amount = "Paid";
                    } else {
                        $due_amount = $obj->due_amount;
                    }
                    if($obj->emi_name){
                        $emi_name =  $obj->emi_name; 
                    } else {
                        $emi_name = "NO";  
                    }
                    $paid_status = get_paid_status($obj->paid_status);
                    $row_data = array();
                    $row_data[] = $obj->month;
                    $row_data[] = $obj->admission_no;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                    $sHostelRoom = $sBusRoute  = "";
                    // if($obj->hostel_details) {
                    //     $aExplode = explode('-',$obj->hostel_details);
                    //     $obj->hostel_id = $aExplode[0] ?? 0;
                    //     $obj->room_id = $aExplode[1] ?? 0;
                    //     $sHostelRoom = ($hostel_list[$obj->hostel_id] ?? "").(isset($hostel_rooms_list[$obj->room_id]) ?  " /" : "").($hostel_rooms_list[$obj->room_id] ?? "");
                    // }
                    // if($obj->transport_details) {
                    //     $aExplode = explode('-',$obj->transport_details);
                    //     $obj->route_id = $aExplode[0] ?? 0;
                    //     $obj->route_stop_id = $aExplode[1] ?? 0;
                    //     $sBusRoute  = ($route_list[$obj->route_id] ?? "").(isset($stop_list[$obj->route_stop_id]) ?  " /" : "").($stop_list[$obj->route_stop_id] ?? "");
                    // }
                    if($obj->invoice_type == "transport") {
                        $atranportmembership = $this->invoice->get_transport_details($obj->user_id, @$school->academic_year_id);
                        $sTest = $this->db->last_query();
                        if(!empty($atranportmembership->route_id) && !empty($atranportmembership->route_stop_id))
                        {
                            $sBusRoute  = ($route_list[$atranportmembership->route_id] ?? "").(isset($stop_list[$atranportmembership->route_stop_id]) ?  " /" : "").($stop_list[$atranportmembership->route_stop_id] ?? "");
                        }
                    }
                    if($obj->invoice_type == "hostel") {
                        $aMembership = $this->invoice->get_hostel_details($obj->user_id, @$school->academic_year_id);
                        if(!empty($aMembership->hostel_id) && !empty($aMembership->room_id))
                        {
                            $sHostelRoom = ($hostel_list[$aMembership->hostel_id] ?? "").(isset($hostel_rooms_list[$aMembership->room_id]) ?  " /" : "").($hostel_rooms_list[$aMembership->room_id] ?? "");
                        }
                    }
                   $row_data[] = $obj->custom_invoice_id;
                   $row_data[] = $obj->student_name;
                   $row_data[] = $obj->father_name;
                   $row_data[] = $obj->class_name;
                   $row_data[] = $obj->head;
                   $row_data[] = $obj->gross_amount;
                   $row_data[] = $obj->discount;
                   $row_data[] = $net_amount;
                   $row_data[] = $due_amount;
                   $row_data[] = $emi_name;
                //    $row_data[] = $paid_status;
                   $row_data[] = $obj->payment_method;
                   $row_data[] = $obj->discount_type;

                   $row_data[] = $sHostelRoom;
                   $row_data[] =  $sBusRoute;
                   $row_data[] =  $obj->ledger;
                   $row_data[] =  $obj->reverted == 1 ? "Yes" : "No";



                   $row_data[] = $action;
                   
                   
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
                $next_emi_datas = array();
                $data = $this->_get_posted_invoice_data();
                $next_data = array();
                 // echo "<pre>"; print_r($data);exit;
                $invoice_data = $data;

               if($invoice_data['pre_income_head_id'])
               {
                   $previous_invoice_data = $invoice_data;
                   $previous_invoice_data['is_applicable_discount'] = 0;
                   $previous_invoice_data['discount'] = 0.00;
                   $previous_invoice_data['due_amount']     = $previous_invoice_data['pre_due_amount'];
                   if($previous_invoice_data['prev_year_discount'])
                   {
                        $previous_invoice_data['discount'] = $previous_invoice_data['prev_year_discount'];
                        $previous_invoice_data['is_applicable_discount'] = 1;
                        $previous_invoice_data['due_amount']     = $previous_invoice_data['pre_due_amount'] - $previous_invoice_data['discount'] ;
                   }
                   $previous_invoice_data['income_head_id']   = $invoice_data['pre_income_head_id'];
                   $previous_invoice_data['net_amount']       = $previous_invoice_data['due_amount'] <= $invoice_data['net_amount'] ? $previous_invoice_data['due_amount'] : $invoice_data['net_amount'] ;
                   $invoice_data['net_amount']                = $invoice_data['net_amount'] - ($previous_invoice_data['due_amount']) ;
                   if( $invoice_data['net_amount'] <0) $invoice_data['net_amount']  = 0;
                   if($data['emi_type'])
                   {
                        $emi_amount = $this->input->post('emi_amount');
                        if($invoice_data['net_amount'] >$emi_amount)
                        {
                            $invoice_data['extra_amount'] = $invoice_data['net_amount'] - $emi_amount;
                            $data['extra_amount'] = $invoice_data['extra_amount'] ;
                            $data['due_amount'] = $this->input->post('due_amount') - ($invoice_data['net_amount']+ $invoice_data['discount']);
                            $invoice_data['extra_amount'] = $data['due_amount'];
                        }
                   }
                   
                   $previous_invoice_data['gross_amount']     = $invoice_data['pre_fee_amount'];
                   $previous_invoice_data['due_amount']     = $previous_invoice_data['due_amount'] - $previous_invoice_data['net_amount'];
                   
                   $previous_invoice_data['class_id']         = $invoice_data['prev_class_id'];
                   
                   $previous_invoice_data['emi_type']         = null;

                   unset($previous_invoice_data['prev_year_discount']);
                   unset($previous_invoice_data['pre_income_head_id']);
                   unset($previous_invoice_data['pre_due_amount']);
                   unset($previous_invoice_data['pre_fee_amount']);
                   unset($previous_invoice_data['pre_academic_year_id']);
                   unset($previous_invoice_data['prev_class_id']);   
                   unset($previous_invoice_data['extra_amount']);   
                   unset($previous_invoice_data['prev_due_amount']);   

                   $pre_insert_id = $this->invoice->insert('invoices', $previous_invoice_data);
                   
                   if ($pre_insert_id ) {
                        // insert data to voucher for feetype - if status is paid
                        if ($data['paid_status'] == 'paid') {
                            $feetype_id = $invoice_data['income_head_id'];
                            $feetype = $this->feetype->get_single_feetype($feetype_id);
                            // create transaction with that voucher id
                            $transaction = array();
                            $transaction['transaction_no'] = $this->transactions->generate_transaction_no($feetype->voucher_id);
                            $transaction['voucher_id'] = $feetype->voucher_id;
                            $transaction['invoice_id'] = $pre_insert_id;
                            $transaction['ledger_id'] = $feetype->credit_ledger_id;
                            $edited_ledger_ids[] = $feetype->credit_ledger_id;

                            $transaction['head_cr_dr'] = 'CR';
                            $transaction['bank_name'] = $this->input->post('bank_name');
                            $transaction['cheque_no'] = $this->input->post('cheque_no');
                            $transaction['date'] = date("Y-m-d",strtotime($previous_invoice_data['month']))." 11:00:00";
                            $transaction['created'] = date('Y-m-d H:i:s');
                            $transaction['created_by'] = logged_in_user_id();	
                            $transaction['narration'] = $data['note'];										
                            $transaction['school_id']=$data['school_id'];
                            // if ($this->form_validation->run() === TRUE) {	
                            if (isset($transaction['voucher_id'])) {
                                //$data = $this->_get_posted_transaction_data();

                                $transaction_id = $this->transactions->insert('account_transactions', $transaction);
                                if ($transaction_id) {
                                    // add details to transaction details									
                                    $detail = array();
                                    $detail['transaction_id'] = $transaction_id;
                                    $detail['ledger_id'] = $previous_invoice_data['debit_ledger_id'];
                                    $edited_ledger_ids[] =  $previous_invoice_data['debit_ledger_id'];
                                    $edited_ledger_ids[] = $detail['ledger_id'];
                                    $detail['amount'] = $previous_invoice_data['net_amount'];
                                    $detail['created'] = date('Y-m-d H:i:s');
                                    $invoice         = $this->invoice->get_single_invoice($pre_insert_id);  
                                    $detail['remark']= $invoice->name.($invoice->admission_no? "[".$invoice->admission_no."] " : "")."".$invoice->class_name." - ".$invoice->head;
                                    $detail_id = $this->transactions->insert('account_transaction_details', $detail);

                                    $updated = $this->invoice->update('invoices', array("account_transaction_id" => $transaction_id), array('id' => $pre_insert_id));
                                }
                            }
                        }
                        create_log('Has been created a invoice : ' . $previous_invoice_data['net_amount']);
                    } 
                   if($invoice_data['net_amount'] <1)
                   {
                        success($this->lang->line('insert_success'));
                        redirect('accounting/invoice/view/' .  $pre_insert_id);
                   }
               }
               unset($invoice_data['pre_income_head_id']);
               unset($invoice_data['pre_due_amount']);
               unset($invoice_data['pre_fee_amount']);
               unset($invoice_data['pre_academic_year_id']);
               unset($invoice_data['prev_class_id']);   
               unset($invoice_data['prev_due_amount']);   
                unset($invoice_data['extra_amount']);
                unset($invoice_data['prev_year_discount']);
          
                if($data['emi_type'] && $data['extra_amount'] >0)
                {
                    $extra_amount = $data['extra_amount'];

                    $emi_data = $this->invoice->get_single('emi_fee', array('id' => $data['emi_type']));
                    $start_date = $emi_data->emi_start_date;
                    $next_emis = $this->invoice->get_next_emi($data['income_head_id'],$start_date,$data['emi_type']);
                    $due_amount = $invoice_data['due_amount'] + $extra_amount;
                    if(!empty($next_emis))
                    {
                        foreach($next_emis as $next_emi)
                        {
                            if( $extra_amount <=0)
                            {
                                break;
                            }
                           
                            if($next_emi->emi_type ==  "amount")
                            {
                                $emi_amount = $next_emi->emi_per;
                            }
                            else
                            {
                                $emi_amount = $data['gross_amount']* ($next_emi->emi_per/100);
                            }
                            if( $extra_amount > $emi_amount)
                            {
                                $extra_amount =  $extra_amount -  $emi_amount;
                                $emi_pay_amount = $emi_amount;
                            }
                            else
                            {
                                $emi_pay_amount =  $extra_amount;
                                $extra_amount = 0;
                            }
                            $custom_invoice_id = $this->invoice->generate_invoice_id( $invoice_data['school_id']);
                            $next_data['custom_invoice_id']=$custom_invoice_id;
                            $next_data = $invoice_data;
                            $next_data['emi_type'] = $next_emi->id;
                            $next_data['net_amount'] = $emi_pay_amount;
                            $next_data['due_amount'] = $due_amount - $emi_pay_amount;
                            $due_amount = $due_amount - $emi_pay_amount;
                            $invoice_data['due_amount']  = $invoice_data['due_amount'] + $emi_pay_amount;
                            $invoice_data['net_amount']  = $invoice_data['net_amount'] - $emi_pay_amount;
                            $next_data['is_applicable_discount'] = 0;
                            $next_data['discount'] = 0.00;
                            $next_emi_datas[] =  $next_data;
                        }
                    }
                    if($extra_amount)
                    {
                        $this->load->library('form_validation');
                        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
                        $this->form_validation->set_rules('pay_amount', "Pay amount", 'trim|required|callback_check_due_emi_amount');

                        $this->form_validation->run();
                        $this->data['post'] = $_POST;
                        $error = 1;
                    }
                }
              
                if(!$error){
                    $insert_id = $this->invoice->insert('invoices', $invoice_data);
                    
                }else
                {
                    $insert_id = 0;
                }

                
                if ($insert_id) {
                    // insert data to voucher for feetype - if status is paid
                    if ($data['paid_status'] == 'paid') {
                        $feetype_id = $data['income_head_id'];
                        $feetype = $this->feetype->get_single_feetype($feetype_id);
                        // create transaction with that voucher id
                        $transaction = array();
                        $transaction['transaction_no'] = $this->transactions->generate_transaction_no($feetype->voucher_id);
                        $transaction['voucher_id'] = $feetype->voucher_id;
                        $transaction['invoice_id'] = $insert_id;
                        $transaction['ledger_id'] = $feetype->credit_ledger_id;
                        $edited_ledger_ids[] =  $feetype->credit_ledger_id;

                        $transaction['bank_name'] = $this->input->post('bank_name');
                        $transaction['cheque_no'] = $this->input->post('cheque_no');
                        $transaction['head_cr_dr'] = 'CR';
                        $transaction['school_id']=$data['school_id'];
                        $transaction['date'] = date("Y-m-d",strtotime($data['month']));
                        $transaction['created'] = date('Y-m-d H:i:s');
                        $transaction['created_by'] = logged_in_user_id();										
                        $transaction['narration'] = $data['note'];										

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

                                $detail['amount'] = $invoice_data['net_amount'];
                                $detail['created'] = date('Y-m-d H:i:s');
                                $detail['school_id']=$data['school_id'];
                                $invoice         = $this->invoice->get_single_invoice($insert_id);  
                                $detail['remark']= $invoice->name.($invoice->admission_no? "[".$invoice->admission_no."] " : "")."".$invoice->class_name." - ".$invoice->head;
                                $detail_id = $this->transactions->insert('account_transaction_details', $detail);

                                $updated = $this->invoice->update('invoices', array("account_transaction_id" => $transaction_id), array('id' => $insert_id));
                            }
                        }
                        foreach($next_emi_datas as $next_data)
                        {
                            $next_data['account_transaction_id'] =$transaction_id;
                            if($next_data)
                            {
                                $next_insert_id = $this->invoice->insert('invoices', $next_data);
                                if ($next_insert_id ) {
                                    // insert data to voucher for feetype - if status is paid
                                    if ($data['paid_status'] == 'paid') {
                                        $feetype_id = $next_data['income_head_id'];
                                        $feetype = $this->feetype->get_single_feetype($feetype_id);
                                        // create transaction with that voucher id
                                        $transaction = array();
                                        $transaction['transaction_no'] = $this->transactions->generate_transaction_no($feetype->voucher_id);
                                        $transaction['voucher_id'] = $feetype->voucher_id;
                                        $transaction['invoice_id'] = $next_insert_id;
                                        $transaction['ledger_id'] = $feetype->credit_ledger_id;
                                        $edited_ledger_ids[] = $feetype->credit_ledger_id;

                                        $transaction['head_cr_dr'] = 'CR';
                                        $transaction['bank_name'] = $this->input->post('bank_name');
                                         $transaction['cheque_no'] = $this->input->post('cheque_no');
                                        $transaction['date'] = date("Y-m-d",strtotime($next_data['month']))." 11:00:00";
                                        $transaction['created'] = date('Y-m-d H:i:s');
                                        $transaction['created_by'] = logged_in_user_id();										
                                        $transaction['school_id']=$data['school_id'];
                                        $transaction['narration'] = $data['note'];										
                                        // if ($this->form_validation->run() === TRUE) {	
                                        if (isset($transaction['voucher_id'])) {
                                            //$data = $this->_get_posted_transaction_data();
                
                                            $transaction_id = $this->transactions->insert('account_transactions', $transaction);
                                            if ($transaction_id) {
                                                // add details to transaction details									
                                                $detail = array();
                                                $detail['transaction_id'] = $transaction_id;
                                                $detail['ledger_id'] = $next_data['debit_ledger_id'];
                                                $edited_ledger_ids[] =  $next_data['debit_ledger_id'];
                                                $edited_ledger_ids[] = $detail['ledger_id'];
                                                $detail['amount'] = $next_data['net_amount'];
                                                $detail['created'] = date('Y-m-d H:i:s');
                                                $invoice         = $this->invoice->get_single_invoice($next_insert_id);  
                                                $detail['remark']= $invoice->name.($invoice->admission_no? "[".$invoice->admission_no."] " : "")."".$invoice->class_name." - ".$invoice->head;
                                                $detail_id = $this->transactions->insert('account_transaction_details', $detail);
                
                                                $updated = $this->invoice->update('invoices', array("account_transaction_id" => $transaction_id), array('id' => $next_insert_id));
                                            }
                                        }
                                    }
                                    create_log('Has been created a invoice : ' . $next_data['net_amount']);
                                } 
                            }
                        }
                      
                    }
                    create_log('Has been created a invoice : ' . $data['net_amount']);
                    if(!empty($edited_ledger_ids))
                    {
                        update_ledger_opening_balance($edited_ledger_ids,$invoice_data['school_id']);
                    }
                    // save transction table data
                    $data['invoice_id'] = $insert_id;
                    $this->_save_transaction($data);

                    success($this->lang->line('insert_success'));
                    redirect('accounting/invoice/view/' .  $insert_id);
                } else {
                   
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/invoice/add');
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
            //$this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['classes'] = $this->classes->get_list_by_school($condition['school_id']);
            $academic_year=$this->transactions->get_single('academic_years',array('school_id'=>$condition['school_id'],'is_running'=>1));	
            if (($_GET['debug_mode'] ?? "") || ($_SESSION['debug_mode'] ?? ""))
            {
                $_SESSION['debug_mode'] = true;
                $academic_year->id = 1468;
            }
            $this->data['income_heads'] = $this->invoice->get_fee_type($condition['school_id'],$academic_year->id);
        }

        // default global income head
       // $this->data['invoices'] = $this->invoice->get_invoice_list($school_id);
        // echo("<pre>");print_r($this->data['invoices']);exit;
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
        $this->data['school_id'] = $school_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['single'] = TRUE;
        $this->layout->title($this->lang->line('create') . ' ' . $this->lang->line('invoice') . ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

    
    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Create new Invoice" user interface                 
     *                    and store "Invoice" data into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function alumni($school_id = null)
    {
        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
                $error = 0;
                $data = $this->_get_alumni_posted_invoice_data();
                $aInvoiceData = $data ;
                unset($aInvoiceData['credit_ledger_id']);
                unset($aInvoiceData['voucher_id']);
                $insert_id = $this->invoice->insert('invoices', $aInvoiceData);
                if ($insert_id) {
                    // insert data to voucher for feetype - if status is paid
                    if ($data['paid_status'] == 'paid') {
                        $feetype_id = $data['income_head_id'];
                        $feetype = $this->feetype->get_single_feetype($feetype_id);
                        // create transaction with that voucher id
                        $transaction = array();
                        $transaction['transaction_no'] = $this->transactions->generate_transaction_no($feetype->voucher_id);
                        $transaction['voucher_id'] = $data['voucher_id'];
                        $transaction['invoice_id'] = $insert_id;
                        $transaction['ledger_id'] = $data['credit_ledger_id'];
                        $edited_ledger_ids[] =  $data['credit_ledger_id'];

                        $transaction['bank_name'] = $this->input->post('bank_name');
                        $transaction['cheque_no'] = $this->input->post('cheque_no');
                        $transaction['head_cr_dr'] = 'CR';
                        $transaction['school_id']=$data['school_id'];
                        $transaction['date'] = date("Y-m-d",strtotime($data['month']));
                        $transaction['created'] = date('Y-m-d H:i:s');
                        $transaction['created_by'] = logged_in_user_id();										
                        $transaction['narration'] = $data['note'];										

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

                                $detail['amount'] = $data['net_amount'];
                                $detail['created'] = date('Y-m-d H:i:s');
                                $detail['school_id']=$data['school_id'];
                                $invoice         = $this->invoice->get_single_invoice($insert_id);  
                                $detail['remark']= $invoice->name.($invoice->admission_no? "[".$invoice->admission_no."] " : "")."".$invoice->class_name." - ".$invoice->head;
                                $detail_id = $this->transactions->insert('account_transaction_details', $detail);

                                $updated = $this->invoice->update('invoices', array("account_transaction_id" => $transaction_id), array('id' => $insert_id));
                            }
                        }
                    }
                    create_log('Has been created a invoice : ' . $data['net_amount']);
                    if(!empty($edited_ledger_ids))
                    {
                        update_ledger_opening_balance($edited_ledger_ids,$invoice_data['school_id']);
                    }
                    // save transction table data
                    $data['invoice_id'] = $insert_id;
                    $this->_save_transaction($data);

                    success($this->lang->line('insert_success'));
                    redirect('accounting/invoice/view/' .  $insert_id);
                } else {
                   
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/invoice/alumni');
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
            //$this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['classes'] = $this->classes->get_list_by_school($condition['school_id']);
            $this->data['academic_years'] =$academic_year=$this->transactions->get_list('academic_years',array('school_id'=>$condition['school_id']));	
        }

        // default global income head
       // $this->data['invoices'] = $this->invoice->get_invoice_list($school_id);
        // echo("<pre>");print_r($this->data['invoices']);exit;
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

        $this->data['alumni'] = TRUE;
        $this->layout->title($this->lang->line('create') . ' ' . $this->lang->line('invoice') . ' | ' . SMS);
        $this->layout->view('invoice/alumni', $this->data);
    }
	
public function multitype($school_id = null)
    {
        check_permission(ADD);
		
        if ($_POST) {
            $edited_ledger_ids = [];
			$school_id = $this->input->post('school_id');
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
               
				//print "if"; exit;
               // $data = $this->_get_posted_invoice_data();
                 // echo "<pre>"; print_r($data);exit;
                 $invoice_no= $this->invoice->generate_invoice_no($school_id); 
                 if($_POST['previous_income_head_id'] ?? 0)
                 {
                    foreach($_POST['previous_income_head_id'] as $index=>$previous_income_head_id){

                        $data = $this->_get_posted_inv_data();	
                       $custom_invoice_id = $this->invoice->generate_invoice_id($school_id);
                       $income_head = $this->invoice->get_single('income_heads', array('id' => $previous_income_head_id));
                       $iPrevFeeAmount      = $_POST['previous_fee_amount'][$index] ?? 0;
                       $iPrevAcademicyearId = $_POST['previous_academic_year_id'][$index] ?? 0;
                       $iPrevClassId        = $_POST['prev_class_id'][$index] ?? 0;
                       $iPrevDueAmount      = $_POST['prev_due_amount'][$index] ?? 0;
                       $iPrevPayAmount      = $_POST['pay_amount'][$index] ?? 0;
                       $iPrevDiscount       = $_POST['previous_year_discount'][$index] ?? 0;
    
                       $previous_invoice_data =   $data ;
                       $previous_invoice_data['invoice_no']=$invoice_no;
                       $previous_invoice_data['custom_invoice_id']=$custom_invoice_id;
                       $previous_invoice_data['income_head_id']=$previous_income_head_id;
                       $previous_invoice_data['invoice_type'] = $income_head->head_type;
    
                       $previous_invoice_data['income_head_id']   = $previous_income_head_id;
                       $previous_invoice_data['net_amount']       = $iPrevDueAmount <= $iPrevPayAmount ? $iPrevDueAmount : $iPrevPayAmount ;
                       $_POST['pay_amount'][$index]               = $_POST['pay_amount'][$index] - $iPrevDueAmount ;
                       if( $_POST['pay_amount'][$index] <0) $_POST['pay_amount'][$index]  = 0;
                       $previous_invoice_data['gross_amount']     = $iPrevFeeAmount;
                       $previous_invoice_data['due_amount']     =$iPrevDueAmount  - $previous_invoice_data['net_amount'];
    
                    //    $previous_invoice_data['academic_year_id'] = $iPrevAcademicyearId;
                       $previous_invoice_data['class_id']         = $iPrevClassId;
                       $previous_invoice_data['is_applicable_discount'] = 0;
                       $previous_invoice_data['discount'] = 0.00;
                       if($iPrevDiscount)
                       {
                            $previous_invoice_data['discount'] = $iPrevDiscount;
                            $previous_invoice_data['is_applicable_discount'] = 1;
                            $previous_invoice_data['due_amount']     = $previous_invoice_data['due_amount'] - $iPrevDiscount ;
                       }
                       
                        unset($previous_invoice_data['show_discount']);
                       $pre_insert_id = $this->invoice->insert('invoices', $previous_invoice_data);
    
                        if ($pre_insert_id) {
                            // insert data to voucher for feetype - if status is paid
                            if ($data['paid_status'] == 'paid') {
                                $feetype_id = $previous_invoice_data['income_head_id'];
                                $feetype = $this->feetype->get_single_feetype($feetype_id);
                                // create transaction with that voucher id
                                $transaction = array();
                                $transaction['transaction_no'] = $this->transactions->generate_transaction_no($feetype->voucher_id);
                                $transaction['voucher_id'] = $feetype->voucher_id;
                                $transaction['invoice_id'] = $pre_insert_id;
                                $transaction['ledger_id'] = $feetype->credit_ledger_id;
                                $edited_ledger_ids[] = $feetype->credit_ledger_id;
    
                                $transaction['head_cr_dr'] = 'CR';
                                $transaction['bank_name'] = $this->input->post('bank_name');
                                $transaction['cheque_no'] = $this->input->post('cheque_no');
                                $transaction['date'] = date("Y-m-d",strtotime($previous_invoice_data['month']))." 11:00:00";
                                $transaction['created'] = date('Y-m-d H:i:s');
                                $transaction['created_by'] = logged_in_user_id();										
                                $transaction['school_id']=$data['school_id'];
                                $transaction['narration'] = $data['note'];										
                                // if ($this->form_validation->run() === TRUE) {	
                                if (isset($transaction['voucher_id'])) {
                                    //$data = $this->_get_posted_transaction_data();
    
                                    $transaction_id = $this->transactions->insert('account_transactions', $transaction);
                                    if ($transaction_id) {
                                        // add details to transaction details									
                                        $detail = array();
                                        $detail['transaction_id'] = $transaction_id;
                                        $detail['ledger_id'] = $previous_invoice_data['debit_ledger_id'];
                                        $edited_ledger_ids[] =  $previous_invoice_data['debit_ledger_id'];
                                        $edited_ledger_ids[] = $detail['ledger_id'];
                                        $detail['amount'] = $previous_invoice_data['net_amount'];
                                        $detail['created'] = date('Y-m-d H:i:s');
                                        $invoice         = $this->invoice->get_single_invoice($pre_insert_id);  
                                        $detail['remark']= $invoice->name.($invoice->admission_no? "[".$invoice->admission_no."] " : "")."".$invoice->class_name." - ".$invoice->head;
                                        $detail_id = $this->transactions->insert('account_transaction_details', $detail);
    
                                        $updated = $this->invoice->update('invoices', array("account_transaction_id" => $transaction_id), array('id' => $pre_insert_id));
                                    }
                                }
                            }
                            create_log('Has been created a invoice : ' . $previous_invoice_data['net_amount']);
                        } 
                    }

                 }

				foreach($_POST['income_head_id'] as $index=>$income_head_id){
					if(isset($income_head_id) && $income_head_id>0){
                        $custom_invoice_id = $this->invoice->generate_invoice_id($school_id);
						$income_head = $this->invoice->get_single('income_heads', array('id' => $income_head_id));
						$data=array();	
                        
						$data = $this->_get_posted_inv_data();	
                        $data['net_amount']=$_POST['pay_amount'][$index];	
                        if(!$data['net_amount']) continue;
						$data['invoice_no']=$invoice_no;
                        $data['custom_invoice_id']=$custom_invoice_id;
						$data['income_head_id']=$income_head_id;
						$data['invoice_type'] = $income_head->head_type;
						$data['gross_amount']=$_POST['amount'][$index];
                        $due_amount = $_POST['due_amount'][$index];					
						$data['due_amount']=$_POST['due_amount'][$index]-$_POST['pay_amount'][$index];		
                        if ($data['is_applicable_discount'] && $income_head->head_type=="fee") {
                            $discount_coupon = $_POST['show_discount'];
                           
                            if($discount_coupon ==  "manual")
                            {
                                $data['discount']   = isset($_POST['discount_amount']) && $_POST['discount_amount'] ? $_POST['discount_amount'] : 0;
                                $data['due_amount'] = $data['due_amount'] - $data['discount'];
                                $data['discount_type']  =  "Manual";

                            }
                            else
                            {
                                $ak = $this->invoice->get_student_discount($data['student_id']);
                                if (count($ak) > 0) {
        
                                    $discount = $this->invoice->get_student_discount1($_POST['show_discount']);
                                    $data['discount_type']  =  $discount->title;

                                    //if (!empty($discount)) 
                                    {
                                        if($discount->type != "amount")
                                        {
                                            $data['discount']   = $discount->amount * $data['gross_amount'] / 100;
                                        }
                                        else $data['discount']   = $discount->amount;
                                        
                                        // $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                                        $data['due_amount'] = $this->input->post('due_amount') - ($this->input->post('pay_amount') + $data['discount']);
                                    }
                                } else {
                                    $discount = $this->invoice->get_student_discount($data['student_id']);
                                    $data['discount_type']  =  $discount->title;

                                    //if (!empty($discount)) 
                                    {
                                        if($discount->type != "amount")
                                        {
                                            $data['discount']   = $discount->amount * $data['gross_amount'] / 100;
                                        }
                                        else $data['discount']   = $discount->amount;
                                        // $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                                        $data['due_amount'] = $this->input->post('due_amount') - ($this->input->post('pay_amount') + $data['discount']);
                                    }
                                }
                            }
                           
                        }
                       unset($data['show_discount']);
                      
						$insert_id = $this->invoice->insert('invoices', $data);
                    
						if ($insert_id) {
							// insert data to voucher for feetype - if status is paid
							if ($data['paid_status'] == 'paid') {
								$feetype_id = $data['income_head_id'];
								$feetype = $this->feetype->get_single_feetype($feetype_id);
								// create transaction with that voucher id
								$transaction = array();
								$transaction['transaction_no'] = $this->transactions->generate_transaction_no($feetype->voucher_id);
								$transaction['voucher_id'] = $feetype->voucher_id;
                                $transaction['invoice_id'] = $insert_id;
								$transaction['ledger_id'] = $feetype->credit_ledger_id;
                                $edited_ledger_ids[] = $feetype->credit_ledger_id;

								$transaction['head_cr_dr'] = 'CR';
                                $transaction['bank_name'] = $this->input->post('bank_name');
                                $transaction['cheque_no'] = $this->input->post('cheque_no');
                                $transaction['date'] = date("Y-m-d",strtotime($data['month']))." 11:00:00";
								$transaction['created'] = date('Y-m-d H:i:s');
                                $transaction['created_by'] = logged_in_user_id();										
                                $transaction['school_id']=$school_id;
                                $transaction['narration'] = $data['note'];										

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
										$detail['amount'] = $data['net_amount'];
										$detail['created'] = date('Y-m-d H:i:s');
                                        $invoice         = $this->invoice->get_single_invoice($insert_id);  
                                        $detail['remark']= $invoice->name.($invoice->admission_no? "[".$invoice->admission_no."] " : "")."".$invoice->class_name." - ".$invoice->head;
										$detail_id = $this->transactions->insert('account_transaction_details', $detail);

										$updated = $this->invoice->update('invoices', array("account_transaction_id" => $transaction_id), array('id' => $insert_id));
									}
								}
							}
							//create_log('Has been created a invoice : ' . $data['net_amount']);

							// save transction table data
							$data['invoice_id'] = $insert_id;
							$this->_save_transaction($data);

						   // success($this->lang->line('insert_success'));
							//redirect('accounting/invoice/index/' . $data['school_id']);
						} else {
						   // error($this->lang->line('insert_failed'));
						   // redirect('accounting/invoice/add');
						}
					}
				}
                if(!empty($edited_ledger_ids))
                {
                    update_ledger_opening_balance($edited_ledger_ids, $school_id);
                }
				success($this->lang->line('insert_success'));
				redirect('accounting/invoice/view/' .  $insert_id);
            } else {
                $this->data['post'] = $_POST;
            }

            
			 
        }

        $condition = array();
        $condition['status'] = 1;
        if ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1) {
            $school_id = $this->session->userdata('school_id');
            $condition['school_id'] = $school_id;
            //$this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['classes'] = $this->classes->get_list_by_school($condition['school_id']);
            $academic_year=$this->transactions->get_single('academic_years',array('school_id'=>$condition['school_id'],'is_running'=>1));	

            $this->data['income_heads'] = $this->invoice->get_fee_type($condition['school_id'],$academic_year->id);
        }

        // default global income head
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
        $this->data['invoices'] = $this->invoice->get_invoice_list($school_id);
        // echo("<pre>");print_r($this->data['invoices']);exit;
        $this->data['school_id'] = $school_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['multitype'] = TRUE;
        $this->layout->title($this->lang->line('create') . ' ' . $this->lang->line('invoice') . ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

    // ak


    function get_discount()
    {
        $id = $_GET['school_id'];
        if ($_GET['school_id']) {
            $data = $this->invoice->get_discount_model($id);
            echo json_encode($data);
        }
    }

    public function delete_invoice($id) {
      
        check_permission(DELETE);
        if (!is_numeric($id)) {
            error($this->lang->line('unexpected_error'));
            redirect('accounting/invoice/index');
        }
        $invoice= $this->invoice->get_single_invoice($id);
        if($id && !empty($invoice))
        {
                $this->invoice->delete_invoice($id,@$invoice->$invoice_no);
                success($this->lang->line('delete_success'));
        }
        else{
            error($this->lang->line('delete_failed'));

        }
        redirect('accounting/invoice/index/');
       
    }

    function find_discount()
    {
        $id = $_GET['id'];
        if ($_GET['id']) {
            $data = $this->invoice->get_student_discount1($id);

            echo json_encode($data);
        }
    }



    // ak

    /*****************Function bulk**********************************
     * @type            : Function
     * @function name   : bulk
     * @description     : Load "Create new bulk Invoice" user interface                 
     *                    and store "Invoice" data into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function bulk($school_id = null)
    {

        redirect('accounting/invoice/index');
        check_permission(ADD);

        if ($_POST) {

            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {

                $status = $this->_get_create_bulk_invoice();
                // print_r($status);exit;
                if ($status) {
                    success($this->lang->line('insert_success'));
                    redirect('accounting/invoice/index/' . $this->input->post('school_id'));
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/invoice/bulk');
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
            //$this->data['classes'] = $this->invoice->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['classes'] = $this->classes->get_list_by_school($condition['school_id']);
            $academic_year=$this->transactions->get_single('academic_years',array('school_id'=>$condition['school_id'],'is_running'=>1));	
            $this->data['income_heads'] = $this->invoice->get_fee_type($condition['school_id'],$academic_year->id);
        }

        // default global income head
        $this->data['invoices'] = $this->invoice->get_invoice_list($school_id);
        $this->data['school_id'] = $school_id;
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['bulk'] = TRUE;
        $this->layout->title($this->lang->line('create') . ' ' . $this->lang->line('invoice') . ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
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
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('paid_status', $this->lang->line('paid') . ' ' . $this->lang->line('status'), 'trim|required');

        if ($this->input->post('type') == 'single') {
            $this->form_validation->set_rules('student_id', $this->lang->line('student_id'), 'trim|required');
            $this->form_validation->set_rules('amount', $this->lang->line('amont'), 'trim|required');
            if ($this->input->post('paid_status') == 'paid') {
                $this->form_validation->set_rules('pay_amount', "Pay amount", 'callback_check_dueamount');
            } 
        }    
              
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|callback_check_valid_date');
		if($this->input->post('type') != 'multitype') {
			$this->form_validation->set_rules('is_applicable_discount', $this->lang->line('is_applicable_discount'), 'trim|required');
			$this->form_validation->set_rules('income_head_id', $this->lang->line('title'), 'trim|required');
		}
    }
     function check_dueamount()
    {
        $pay_amount = $this->input->post('pay_amount');
        $due_amount = $this->input->post('due_amount');
        $prev_due_amount = $this->input->post('prev_due_amount');

        if( $pay_amount >  ($due_amount +$prev_due_amount)  )
        {
            $this->form_validation->set_message('check_dueamount','Pay amount cant be greater than due amount');

            return false;
        }
        return true;
    }
    function check_valid_date()
    {
        $month = $this->input->post('month');
       
        if(!checkIsAValidDate($month))
        {
            $this->form_validation->set_message('check_valid_date','Invalid date');
            return false;
        }
        $school_id = $this->input->post('school_id');
        $month_str = strtotime($month);
        $financial_year=$this->invoice->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));		
        if(strpos($financial_year->session_year,"->"))	
        {
            $arr=explode("->",$financial_year->session_year);
            $f_start=strtotime($arr[0]);
            $f_end=strtotime($arr[1]);
        }
        else
        {
            $arr=explode("-",$financial_year->session_year);
            $f_start=strtotime("1 ".$arr[0]);		
            $f_end=strtotime("31 ".$arr[1]);	
        }	
       
        if($f_start > $month_str || $f_end < $month_str)
        {
            $this->form_validation->set_message('check_valid_date','date out of financial year');
            return false;
        }
        return true;
    }
    function check_due_emi_amount()
    {
        $this->form_validation->set_message('check_dueamount','Pay amount cant be greater than due amount');
        return false;
    }

    function _get_alumni_posted_invoice_data()
    {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'income_head_id';
        $items[] = 'class_id';
        $items[] = 'student_id';
        $items[] = 'month';
        $items[] = 'paid_status';
        $items[] = 'note';
        $items[] = 'emi_type';
        $items[] = 'debit_ledger_id';
        $items[] = 'debit_ledger_id';
        $items[] = 'credit_ledger_id';
        $items[] = 'voucher_id';
        // $items[] = 'show_discount';

        $data = elements($items, $_POST);
		$data['invoice_no']=$this->invoice->generate_invoice_no($this->input->post('school_id')); 
        $income_head = $this->invoice->get_single('income_heads', array('id' => $this->input->post('income_head_id')));
       
        $school = $this->invoice->get_school_by_id($data['school_id']);

        $data['academic_year_id'] = $school->academic_year_id;

        $data['discount'] = 0.00;
        $data['gross_amount'] = $this->input->post('amount');
        $data['net_amount'] = $this->input->post('pay_amount');
       
            $data['new_format']  =  1;
        if($data['paid_status'] == "paid"){

		        $data['due_amount'] = $this->input->post('due_amount') - $this->input->post('pay_amount');
		        // print_r($data['due_amount']);exit;
                $data['discount']   = isset($_POST['discount_amount']) && $_POST['discount_amount'] ? $_POST['discount_amount'] : 0;
                $data['is_applicable_discount'] = $data['discount'] ? 1 : 0;
                $data['due_amount'] = $this->input->post('due_amount') - ($this->input->post('pay_amount') + $data['discount']);
   		 } else{
   		  $ak =	$this->get_due($data['student_id']);
   		  	if($ak){
		   		  $data['net_amount'] = $ak[0]['due_amount'];
		   		  $data['due_amount'] =  $ak[0]['due_amount'];
		   		}else {
		   			$data['net_amount'] = $data['gross_amount'];
		   		    $data['due_amount'] =  $data['gross_amount'];
		   		}
   		 // echo"<pre>";	print_r($data);exit;
   		 }
        
        $data['date'] = date('Y-m-d');

        if ($this->input->post('id')) {

            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['custom_invoice_id'] = $this->invoice->generate_invoice_id($this->input->post('school_id'));
            $data['status'] = 1;
            $data['invoice_type'] = $income_head->head_type;

            $school = $this->invoice->get_school_by_id($data['school_id']);

            if (!$school->academic_year_id) {
                error($this->lang->line('set_academic_year_for_school'));
                redirect('accounting/invoice/index');
            }

            $data['academic_year_id'] = $school->academic_year_id;

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
           
        }
        return $data;
    }
    /*****************Function _get_posted_invoice_data**********************************
     * @type            : Function
     * @function name   : _get_posted_invoice_data
     * @description     : Prepare "Invoice" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_invoice_data()
    {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'income_head_id';
        $items[] = 'class_id';
        $items[] = 'student_id';
        $items[] = 'is_applicable_discount';
        $items[] = 'month';
        $items[] = 'paid_status';
        $items[] = 'note';
        $items[] = 'emi_type';
        $items[] = 'debit_ledger_id';
        $items[] = 'debit_ledger_id';
        $items[] = 'payment_method';

        // $items[] = 'show_discount';

        $data = elements($items, $_POST);
		$data['invoice_no']=$this->invoice->generate_invoice_no($this->input->post('school_id')); 
        $income_head = $this->invoice->get_single('income_heads', array('id' => $this->input->post('income_head_id')));
       

        $data['discount'] = 0.00;
        $data['gross_amount'] = $this->input->post('amount');
        $data['net_amount'] = $this->input->post('pay_amount');
        $data['pre_income_head_id']  = $this->input->post('previous_income_head_id');
        $data['pre_due_amount']  =  $this->input->post('previous_due_amount');
        $data['pre_academic_year_id']  =  $this->input->post('previous_academic_year_id');
        $data['pre_fee_amount']  =  $this->input->post('previous_fee_amount');
        $data['prev_class_id']  =  $this->input->post('prev_class_id');
        $data['prev_due_amount']  =  $this->input->post('prev_due_amount');
        $data['prev_year_discount']  =  $this->input->post('previous_year_discount');
      
        $data['new_format']  =  1;
        

        $data['extra_amount']  = 0;
        if($data['emi_type'])
        {
            $emi_amount = $this->input->post('emi_amount');
            if($data['net_amount'] >$emi_amount)
            {
                $data['extra_amount'] = $data['net_amount'] - $emi_amount;
            }
        }
       



        if($data['paid_status'] == "paid"){

		        $data['due_amount'] = $this->input->post('due_amount') - $this->input->post('pay_amount');
		        // print_r($data['due_amount']);exit;

		        if ($data['is_applicable_discount']) {
                    $discount_coupon = $_POST['show_discount'];
                   
                    if($discount_coupon ==  "manual")
                    {
                        $data['discount_type']  = "Manual";
                        $data['discount']   = isset($_POST['discount_amount']) && $_POST['discount_amount'] ? $_POST['discount_amount'] : 0;
                        $data['due_amount'] = $this->input->post('due_amount') - ($this->input->post('pay_amount') + $data['discount']);
                    }
                    else
                    {
                        $ak = $this->invoice->get_student_discount($data['student_id']);
                        if (count($ak) > 0) {

                            $discount = $this->invoice->get_student_discount1($_POST['show_discount']);
                            $data['discount_type']  =  $discount->title;

                            //if (!empty($discount)) 
                            {
                                if($discount->type != "amount")
                                {
                                    $data['discount']   = $discount->amount * $data['gross_amount'] / 100;
                                }
                                else $data['discount']   = $discount->amount;
                                
                                // $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                                $data['due_amount'] = $this->input->post('due_amount') - ($this->input->post('pay_amount') + $data['discount']);
                            }
                        } else {
                            $discount = $this->invoice->get_student_discount($data['student_id']);
                            $data['discount_type']  =  $discount->title;

                            //if (!empty($discount)) 
                            {
                                if($discount->type != "amount")
                                {
                                    $data['discount']   = $discount->amount * $data['gross_amount'] / 100;
                                }
                                else $data['discount']   = $discount->amount;
                                // $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                                $data['due_amount'] = $this->input->post('due_amount') - ($this->input->post('pay_amount') + $data['discount']);
                            }
                        }
                    }
		           
		        }

   		 } else{
   		  $ak =	$this->get_due($data['student_id']);
   		  	if($ak){
		   		  $data['net_amount'] = $ak[0]['due_amount'];
		   		  $data['due_amount'] =  $ak[0]['due_amount'];
		   		}else {
		   			$data['net_amount'] = $data['gross_amount'];
		   		    $data['due_amount'] =  $data['gross_amount'];
		   		}
   		 // echo"<pre>";	print_r($data);exit;
   		 }
        
        $data['date'] = date('Y-m-d');

        if ($this->input->post('id')) {

            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['custom_invoice_id'] = $this->invoice->generate_invoice_id($this->input->post('school_id'));
            $data['status'] = 1;
            $data['invoice_type'] = $income_head->head_type;

            $school = $this->invoice->get_school_by_id($data['school_id']);

            if (!$school->academic_year_id) {
                error($this->lang->line('set_academic_year_for_school'));
                redirect('accounting/invoice/index');
            }

            $data['academic_year_id'] = $school->academic_year_id;

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
           
        }
        return $data;
    }
private function _get_posted_inv_data()
    {

        $items = array();
        $items[] = 'school_id';
        //$items[] = 'income_head_id';
        $items[] = 'class_id';
        $items[] = 'student_id';
        $items[] = 'is_applicable_discount';
        $items[] = 'month';
        $items[] = 'paid_status';
        $items[] = 'note';
        $items[] = 'payment_method';

       // $items[] = 'emi_type';
        $items[] = 'debit_ledger_id';
         $items[] = 'show_discount';

        $data = elements($items, $_POST);
		$data['invoice_no']=$this->invoice->generate_invoice_no($this->input->post('school_id')); 
		//$data['is_applicable_discount']=0;
        $data['date'] = date('Y-m-d');
       
            $data['new_format']  =  1;
        
            //$data['custom_invoice_id'] = $this->invoice->get_custom_id('invoices', 'INV');
            $data['status'] = 1;
            //$data['invoice_type'] = $income_head->head_type;

            $school = $this->invoice->get_school_by_id($data['school_id']);

            if (!$school->academic_year_id) {
                error($this->lang->line('set_academic_year_for_school'));
                redirect('accounting/invoice/index');
            }

            $data['academic_year_id'] = $school->academic_year_id;

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();

          
        return $data;
    }

    /*****************Function _get_create_bulk_invoice**********************************
     * @type            : Function
     * @function name   : _get_create_bulk_invoice
     * @description     : Prepare "Invoice" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_create_bulk_invoice()
    {

        $data = array();
        $edited_ledger_ids= [];
        $items[] = 'school_id';
        $items[] = 'income_head_id';
        $items[] = 'class_id';
        $items[] = 'is_applicable_discount';
        $items[] = 'month';
        $items[] = 'paid_status';
        $items[] = 'note';
        $items[] = 'debit_ledger_id';

        $data = elements($items, $_POST);

        $income_head = $this->invoice->get_single('income_heads', array('id' => $this->input->post('income_head_id')));
		
        $data['date'] = date('Y-m-d');
        $data['discount'] = 0.00;
        $data['status'] = 1;

        $school = $this->invoice->get_school_by_id($data['school_id']);

        if (!$school->academic_year_id) {
            error($this->lang->line('set_academic_year_for_school'));
            redirect('accounting/invoice/index');
        }

        $data['academic_year_id'] = $school->academic_year_id;

        foreach ($this->input->post('students') as $key => $value) {

            $data['student_id'] = $key;
            $data['gross_amount'] = $value;
            $data['net_amount'] = $value;

            if ($data['is_applicable_discount']) {

                if ($data['is_applicable_discount']) {
                    $ak = $this->invoice->get_student_discount($data['student_id']);
                    if (count($ak) > 0) {

                        $discount = $this->invoice->get_student_discount1($_POST['show_discount']);
                        $data['discount_type']  =  $discount->title;

                        //if (!empty($discount)) 
                        {
                            if($discount->amount != "amount")
                            {
                                $data['discount']   = $discount->amount * $data['gross_amount'] / 100;
                            }
                            else $data['discount']   = $discount->amount;
                            $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                        }
                    } else {
                        $discount = $this->invoice->get_student_discount($data['student_id']);
                        $data['discount_type']  =  $discount->title;

                        //if (!empty($discount)) 
                        {
                            if($discount->amount != "amount")
                            {
                                $data['discount']   = $discount->amount * $data['gross_amount'] / 100;
                            }
                            else $data['discount']   = $discount->amount;
                            $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                        }
                    }
                }
            }

            $data['custom_invoice_id'] = $this->invoice->generate_invoice_id($data['school_id']);

            $data['invoice_type'] = $income_head->head_type;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();

            // echo "<pre>"; print_r($data);
            $insert_id = $this->invoice->insert('invoices', $data);
            if ($data['paid_status'] == 'paid') {
                $feetype_id = $data['income_head_id'];
                $feetype = $this->feetype->get_single_feetype($feetype_id);
                // create transaction with that voucher id
                $transaction = array();
                $transaction['transaction_no'] = $this->transactions->generate_transaction_no($feetype->voucher_id);
                $transaction['voucher_id'] = $feetype->voucher_id;
                $transaction['ledger_id'] = $feetype->credit_ledger_id;
                $edited_ledger_ids[] = $feetype->credit_ledger_id;
                $transaction['invoice_id'] = $insert_id;
                $transaction['head_cr_dr'] = 'CR';
                $transaction['date'] = date("Y-m-d",strtotime($data['month']))." 11:00:00";
                $transaction['created'] = date('Y-m-d H:i:s');
                $transaction['created_by'] = logged_in_user_id();	
                $transaction['school_id'] = $school->id;									
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

                        $detail['amount'] = $data['net_amount'];
                        $detail['created'] = date('Y-m-d H:i:s');
                        $invoice         = $this->invoice->get_single_invoice($insert_id);  
                        $detail['remark']= $invoice->name.($invoice->admission_no? "[".$invoice->admission_no."] " : "")."".$invoice->class_name." - ".$invoice->head;
                        $detail_id = $this->transactions->insert('account_transaction_details', $detail);

                        $updated = $this->invoice->update('invoices', array("account_transaction_id" => $transaction_id), array('id' => $insert_id));
                    }
                }
            }

            // save transction table data
            $txn = array();
            $txn = $data;
            $txn['invoice_id'] = $insert_id;
            $this->_save_transaction($txn);
        }
        // exit;
        if(!empty($edited_ledger_ids))
		{
			update_ledger_opening_balance($edited_ledger_ids, $data['school_id']);
		}
        create_log('Has been created a invoice : ' . $data['net_amount']);
        return TRUE;
    }


    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Invoice" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null)
    {

        check_permission(DELETE);
        if (!is_numeric($id)) {
            error($this->lang->line('unexpected_error'));
            redirect('accounting/invoice/index');
        }

        $invoice = $this->invoice->get_single('invoices', array('id' => $id));

        if ($this->invoice->delete('invoices', array('id' => $id))) {

            create_log('Has been deleted a invoice : ' . $invoice->net_amount);

            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }

        redirect('accounting/invoice/index');
    }



    /*****************Function _save_transaction**********************************
     * @type            : Function
     * @function name   : _save_transaction
     * @description     : transaction data save/update into database 
     *                    while add/update income data into database                
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    private function _save_transaction($data)
    {

        if ($data['paid_status'] == 'paid') {

            $txn = array();
            $txn['school_id'] = $data['school_id'];
            $txn['amount'] = $data['net_amount'];
            $txn['note'] = $data['note'];
            $txn['payment_date'] = $data['date'];
            $txn['payment_method'] = $this->input->post('payment_method');
            $txn['bank_name'] = $this->input->post('bank_name');
            $txn['cheque_no'] = $this->input->post('cheque_no');

            if ($this->input->post('id')) {

                $txn['modified_at'] = date('Y-m-d H:i:s');
                $txn['modified_by'] = logged_in_user_id();
                $this->invoice->update('transactions', $txn, array('invoice_id' => $this->input->post('id')));
            } else {

                $txn['invoice_id'] = $data['invoice_id'];
                $txn['status'] = 1;
                $txn['academic_year_id'] = $data['academic_year_id'];
                $txn['created_at'] = $data['created_at'];
                $txn['created_by'] = $data['created_by'];
                $this->invoice->insert('transactions', $txn);
            }
        }
    }



    /* Ajax */
    public function get_fee_type_by_school()
    {

        $school_id = $this->input->post('school_id');
        $fee_type_id = $this->input->post('fee_type_id');
        $school = $this->invoice->get_school_by_id($school_id);
        $academic_year=$this->transactions->get_single('academic_years',array('school_id'=>$condition['school_id'],'is_running'=>1));	
        $income_heads = $this->invoice->get_fee_type($school_id,$school->academic_year_id);
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($income_heads)) {
            foreach ($income_heads as $obj) {

                $selected = $fee_type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . ' data-type="'.$obj->head_type.'">' . $obj->title . ' </option>';
            }
        }

        echo $str;
    }
     /* Ajax */
     public function get_fee_types()
     {
         $school_id = $this->input->post('school_id');
         $academic_year_id = $this->input->post('academic_year_id');
         $income_heads = $this->invoice->get_fee_type($school_id, $academic_year_id);
 
         $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
         $select = 'selected="selected"';
         if (!empty($income_heads)) {
             foreach ($income_heads as $obj) {
 
                 $selected = $fee_type_id == $obj->id ? $select : '';
                 $str .= '<option value="' . $obj->id . '" ' . $selected . ' data-type="'.$obj->head_type.'">' . $obj->title . ' </option>';
             }
         }
         echo $str;
     }

    public function get_fee_amount()
    {
        $response       = array('previous_due'=>0);
        $school_id      = $this->input->post('school_id');
        $class_id       = $this->input->post('class_id');
        $student_id     = $this->input->post('student_id');
        $income_head_id = $this->input->post('income_head_id');
        $previous_pending_data = array();
        $rte_student    = 0;
        $income_head = $this->invoice->get_single('income_heads', array('id' => $income_head_id));
        if($income_head->academic_year_id )
        {
            $academic_year = $this->invoice->get_single('academic_years', array('id' => $income_head->academic_year_id));
            if($academic_year->previous_academic_year_id)
            {
                $previous_pending_data =  $this->__invoice_creation(array('student_id'=>$student_id,'school_id'=>$school_id,'previous_academic_year_id'=>$academic_year->previous_academic_year_id,'fee_type'=>$income_head->head_type));
                if(!empty($previous_pending_data)) $response['previous_due'] = 1;
            }
        }
        
        $amount = 0.00;
        if ($income_head->head_type == 'hostel') {

            $fee = $this->invoice->get_hostel_fee($student_id);
            if (!empty($fee)) {
                $yearly_room_rent = $fee->yearly_room_rent ? json_decode($fee->yearly_room_rent,true) : array();
                if(isset( $yearly_room_rent[@$academic_year->id]))
                {
                    $amount = $yearly_room_rent[@$academic_year->id];
                }
                else
                {
                    $amount = $fee->cost;
                }
            }
        } elseif ($income_head->head_type == 'transport') {
            // $fee = $this->invoice->get_hostel_fee($student_id);
            $membership = $this->invoice->get_transport_membership($student_id, @$academic_year->id, $school_id);
            
            if(empty($membership)) {
                $amount = 0;
            }
            else {
                $fee = $this->invoice->get_transport_fee($student_id, $membership->id);
                // print_R($fee);
                // die;
                if (!empty($fee)) {
                        $yearly_stop_fares = $fee->yearly_stop_fare ? json_decode($fee->yearly_stop_fare,true) : array();
                        if(isset( $yearly_stop_fares[@$academic_year->id]))
                        {
                            $amount = $yearly_stop_fares[@$academic_year->id];
                        }
                        else
                        {
                            $amount = $fee->stop_fare;
                        }
                    // $amount = '1000';
                    // $amount = $fee->cost;
                }
            }
        } else {
           
            $amount = 0;

            $student = $this->invoice->get_single('students', array('id' => $student_id));
            $enrollment = $this->invoice->get_single('enrollments', array('student_id' => $student_id, 'academic_year_id'=> @$income_head->academic_year_id));
            if(!empty($enrollment) && $enrollment->rte != "" && $enrollment->rte != null) {
                if(strtolower($enrollment->rte) == "yes")
                {
                    $amount = 0;
                    $rte_student    = 1;
                }
            }
            else if(!empty($student) && strtolower($student->rte) == "yes")
            {
                $amount = 0;
                $rte_student    = 1;
            }
            if( $rte_student == 0)
            {
                $fee = $this->invoice->get_single('fees_amount', array('class_id' => $class_id, 'income_head_id' => $income_head_id));
                if (!empty($fee)) {
                    $amount = $fee->fee_amount;
                }
            }
        }
        $response['rte'] = $rte_student;
        $response['previous_details'] = $previous_pending_data;
        $response['amount'] = $amount;
        echo json_encode($response);
        // echo $amount;
    }


    public function get_student_and_fee_amount()
    {


        $school_id = $this->input->post('school_id');
        $class_id       = $this->input->post('class_id');
        $income_head_id = $this->input->post('income_head_id');
		$month =$this->input->post('month');
		
        $income_head = $this->invoice->get_single('income_heads', array('id' => $income_head_id));
        $amount = 0.00;

        $school = $this->invoice->get_school_by_id($school_id);
        if (!$school->academic_year_id) {
            echo 'ay';
            die();
        }

        $students = $this->invoice->get_student_list($school_id, $school->academic_year_id, $class_id);
        
        // echo "<pre>"; print_r($_POST);exit;

        $str = '';

        if (!empty($students)) {
			if($income_head->head_type != 'transport' && $income_head->head_type != 'hostel'){
				$fee = $this->invoice->get_single('fees_amount', array('class_id' => $class_id, 'income_head_id' => $income_head_id));
				$fee_amount= $fee->fee_amount;
			}

            foreach ($students as $obj) {
				$due_amount=0;
				$student_id=$obj->id;
				if($income_head->head_type == 'hostel'){
					$fee = $this->invoice->get_hostel_fee($student_id);
					if (!empty($fee)) {
						$fee_amount = $fee->cost;
					}
				}
				else if($income_head->head_type == 'transport'){
					$fee = $this->invoice->get_transport_fee($student_id);					
					if (!empty($fee)) {
						$fee_amount = $fee->stop_fare;						
					}
				}
				// Get due amount
				$paid_amount=$this->invoice->get_paid_fee_amount($month,$student_id,$income_head_id);
				$due_fee=$fee_amount - $paid_amount;
               // $due = $this->find_fee_bulk($obj->id);

                // when fee is transport and hostel then need to check
                // that student is eligible for fee
                if ($income_head->head_type == 'hostel' && $obj->is_hostel_member == 0) {
                    continue;
                } elseif ($income_head->head_type == 'transport' && $obj->is_transport_member == 0) {
                    continue;
                }

              /*  if ($income_head->head_type == 'hostel') {

                    $hostel_fee = $this->invoice->get_hostel_fee($obj->id);
                    if (!empty($hostel_fee)) {
                        $amount = $hostel_fee->cost;
                    }
                } elseif ($income_head->head_type == 'transport') {

                    $transport_fee = $this->invoice->get_transport_fee($obj->id);
                    if (!empty($transport_fee)) {
                        $amount = $transport_fee->stop_fare;
                    }
                } else {

                    if (!empty($fee)) {
                        $amount = $fee->fee_amount;
                    }
                }
                foreach ($due as $value) {
                    if ($value != null)
                        $amount = $value['due_amount'];
                }*/

                // making student string....
                $str .= '<div class="multi-check"><input type="checkbox" name="students[' . $obj->id . ']" value="' . $fee_amount . '" /> ' . $obj->name . ' [' . $school->currency_symbol . $due_fee . ']</div>';
            }
        }


        echo $str;
    }



    public function find_fee($id)
    {


        // $query = "SELECT *, SUM(net_amount) AS total_fee  FROM `invoices` WHERE student_id = '{$id}' group by id DESC LIMIT 1";

        //     $sql = $this->db->query($query);

        //     $result = $sql->result_array();

        $query = "SELECT SUM(net_amount) AS total_fee FROM `invoices` WHERE student_id = '{$id}'";

        $sql = $this->db->query($query);

        $result = $sql->result_array();

        echo json_encode($result[0]);
    }

    public function find_fee1($id)
    {


        $query = "SELECT * FROM `invoices` WHERE student_id = '{$id}' ORDER BY id DESC LIMIT 1";

        $sql = $this->db->query($query);

        $result1 = $sql->result_array();


        echo json_encode($result1[0]);
    }
	public function get_paid_fee_amount(){ // Nirali
       $response    = array();
       $response['paid_emi_amount'] = 0;
		$school_id = $this->input->post('school_id');
        $class_id       = $this->input->post('class_id');
		$month       = $this->input->post('month');
        $school     = $this->invoice->get_school_by_id($school_id);
		$student_id       = $this->input->post('student_id');
        $income_head_id = $this->input->post('income_head_id');		
        $emi       = $this->input->post('emi');
        $emi_type = $this->input->post('emi_type');		
        if(!$emi && $emi_type)
        {
            $paid_emi_amount =$this->invoice->get_paid_emi_amount($student_id,$income_head_id,$school->academic_year_id,$class_id,$emi_type);
            $response['paid_emi_amount'] =$paid_emi_amount ;
        }
		$paid_amount=$this->invoice->get_paid_fee_amount($month,$student_id,$income_head_id,$school->academic_year_id,$class_id);
        // echo $this->db->last_query();
        $response['paid_amount'] =  $paid_amount;
		echo json_encode($response);
		exit;
	}
    public function get_paid_emi_amount(){ // Nirali
        $response    = array();
        $response['paid_emi_amount'] = 0;
         $school_id = $this->input->post('school_id');
         $class_id       = $this->input->post('class_id');
         $month       = $this->input->post('month');
         $school     = $this->invoice->get_school_by_id($school_id);
         $student_id       = $this->input->post('student_id');
         $income_head_id = $this->input->post('income_head_id');		
         $emi       = $this->input->post('emi');
         $emi_type = $this->input->post('emi_type');		
         if(!$emi && $emi_type)
         {
             $paid_emi_amount =$this->invoice->get_paid_emi_amount($student_id,$income_head_id,$school->academic_year_id,$class_id,$emi_type);
             $response['paid_emi_amount'] =$paid_emi_amount ;
         }
        
         echo json_encode($response);
         exit;
     }

       public function get_due($id)
    {


        $query = "SELECT * FROM `invoices` WHERE student_id = '{$id}' ORDER BY id DESC LIMIT 1";

        $sql = $this->db->query($query);

        $result1 = $sql->result_array();

		return $result1;
    }

    public function find_fee_bulk($id)
    {


        $query = "SELECT * FROM `invoices` WHERE student_id = '{$id}' ORDER BY id DESC LIMIT 1";

        $sql = $this->db->query($query);

        return $result1 = $sql->result_array();
    }
    public function revert($id)
    {
        check_permission(DELETE);
        if($id)
        {
            $data = array();
            $data['reverted'] = 1;
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            $updated = $this->payment->update('invoices',  $data, array('id' => $id));
            $updated = $this->payment->update('account_transactions', array('cancelled'=>1), array('invoice_id' => $id));
            success($this->lang->line('delete_success'));
        }
        else{
            error($this->lang->line('delete_failed'));

        }
        redirect('accounting/invoice/index');
    }

    public function check_discount()
    {

        $id = $_GET['student_id'];

        if ($id) {

            $query = "SELECT *, SUM(net_amount) AS total_fee  FROM `invoices` WHERE student_id = '{$id}' group by id DESC LIMIT 1";

            $sql = $this->db->query($query);

            $result = $sql->result_array();

            echo json_encode($result[0]);
        }
    }
        /*****************Function __invoice_creation**********************************
    * @type            : Function
    * @function name   : __invoice_creation
    * @description     : Invoice creation for student                  
    * @param           : $data array value
    * @return          : null 
    * ********************************************************** */
    private function __invoice_creation($data) {
        $school = $this->invoice->get_school_by_id($data['school_id']);
        if(!empty($school ))
        {
            $school_id      = $school->id;
            $student_id     = $data['student_id'];
            $academic_year_id = $school->academic_year_id;

            $class_id         = 0;
            $income_head   = $this->invoice->get_income_heads($school_id,$data['previous_academic_year_id'], $data['fee_type']); 
            $aIncomeheadIds   = [];
            $iFeeIncomeheadIds   = [];
            
            $fee_amount = 0;
            $previous_enrollment = $this->invoice->get_single('enrollments', array('student_id' => $student_id, 'academic_year_id'=>$data['previous_academic_year_id']));

            if(empty($previous_enrollment ) || empty($income_head))
            {
                return array();
            }

            $class_id            = $previous_enrollment->class_id ?? 0;
            if( $data['fee_type']== 'fee' )
            {
                $fee                 = $this->invoice->fee_list($school_id,$income_head->id,$class_id);
                $fee_amount          = $fee->fee_amount;
                $student = $this->invoice->get_single('students', array('id' => $student_id));
                $enrollment = $this->invoice->get_single('enrollments', array('student_id' => $student_id, 'academic_year_id'=> @$data['previous_academic_year_id']));
                if(!empty($enrollment) && $enrollment->rte != "" && $enrollment->rte != null)
                {
                    if(strtolower($enrollment->rte) == "yes")
                    {
                       return array();
                    }
                }
                else if(!empty($student) && strtolower($student->rte) == "yes")
                {
                   return array();
                }
            }
            else if( $data['fee_type']== 'transport')
            {
                $membership = $this->invoice->get_transport_membership($student_id, @$data['previous_academic_year_id'], $school_id);
                if(empty($membership)) {
                    $fee_amount = 0;
                }
                else {
                    $fee = $this->invoice->get_transport_fee($student_id, $membership->id);
                    if (!empty($fee)) {
                        $yearly_stop_fares = $fee->yearly_stop_fare ? json_decode($fee->yearly_stop_fare,true) : array();
                        if(isset( $yearly_stop_fares[$data['previous_academic_year_id']]))
                        {
                            $fee_amount = $yearly_stop_fares[$data['previous_academic_year_id']];
                        }
                        else
                        {
                            $fee_amount = $fee->stop_fare;
                        }
                    }
                }
            }
            else if( $data['fee_type']== 'hostel')
            { 
                $membership = $this->invoice->get_hostel_membership($student_id, @$data['previous_academic_year_id'], $school_id);
                if(empty($membership)) {
                    $fee_amount = 0;
                }
                else {
                    $fee = $this->invoice->get_hostel_fee($student_id, $membership->id);
                    if (!empty($fee)) {
                        $yearly_stop_fares = $fee->yearly_room_rent ? json_decode($fee->yearly_room_rent,true) : array();
                        if(isset( $yearly_room_rent[$data['previous_academic_year_id']]))
                        {
                            $fee_amount = $yearly_room_rent[$data['previous_academic_year_id']];
                        }
                        else
                        {
                            $fee_amount = $fee->cost;
                        }
                    }
                }
            }
            else if( $data['fee_type']== 'other')
            { 
               
                $fee                 = $this->invoice->fee_list($school_id,$income_head->id,$class_id);
                $fee_amount          =  $fee_amount +$fee->fee_amount;

                $student = $this->invoice->get_single('students', array('id' => $student_id));
                $enrollment = $this->invoice->get_single('enrollments', array('student_id' => $student_id, 'academic_year_id'=> @$data['previous_academic_year_id']));
                if(!empty($enrollment) && $enrollment->rte != "" && $enrollment->rte != null)
                {
                    if(strtolower($enrollment->rte) == "yes")
                    {
                        return array();
                    }
                }
                else if(!empty($student) && strtolower($student->rte) == "yes")
                {
                    return array();
                }
                
            }


            if(!empty($income_head) )
            {
                $previous_invoices = $this->invoice->get_invoice_list_prev($school_id, $student_id,$income_head->id);
                $previous_paid   = 0;
                if(!empty($previous_invoices))
                {
                    foreach($previous_invoices as $obj)
                    {
                            $previous_paid =  $previous_paid+$obj->net_amount;   
                            if($obj->is_applicable_discount ==1 && $obj->discount)
                            {
                                $previous_paid =  $previous_paid+$obj->discount;   
                            }                     
                    }
                }
                $fee_amount = $fee_amount;
                $invoice_data = array();
                $invoice_data['income_head_id'] = $income_head->id;
                $invoice_data['income_title'] = $income_head->title;
                $invoice_data['academic_year_id'] = $income_head->academic_year_id;
                $invoice_data['invoice_type']   = $income_head->head_type;                           
                $invoice_data['fee_amount']   =  $fee_amount;

                
                $invoice_data['due_amount']     = $fee_amount - $previous_paid;



                $invoice_data['prev_class_id']  = $class_id;
                return $invoice_data['due_amount'] >0 ? $invoice_data : array();    
            }
            return     array();
        }
        return     array();

    }
}
