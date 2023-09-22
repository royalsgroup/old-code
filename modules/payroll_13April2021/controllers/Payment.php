<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Payment.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Payment
 * @description     : Manage Employee and Teacher Salary.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Payment extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Payment_Model', 'payment', true);      
		//$this->load->model('Grade_Model', 'grade', true);
		$this->load->model('Payscalecategory_Model', 'grade', true);            
		 $this->load->model('Accounttransactions_Model', 'transactions', true);			 
    }

    
    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Employee & Teacher Payment" user interface                 
     *                    
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function index($user_id = null) {
        
        check_permission(VIEW);
        
        $this->data['users'] = '';
        
         if ($_POST) {
             
            $school_id  = $this->input->post('school_id');
            $payment_to  = $this->input->post('payment_to');
            $user_id  = $this->input->post('user_id');
        
            $this->data['school_id'] = $school_id;
            $this->data['payment_to'] = $payment_to;
            $this->data['user_id'] = $user_id;
            
            $this->data['user']    = $this->payment->get_single('users', array('id' => $user_id));        
            $this->data['payment'] = $this->payment->get_single_payment_user($this->data['user']->role_id, $user_id); 
		   $payscale_cat=$this->grade->get_payscale_data_by_user($user_id);
		   $earnings=array();
		   $expenditure=array();
		   $basic_salary=$this->data['payment']->basic_salary;
		   $total_earnings=0;
		   $total_deduction=0;
		   foreach($payscale_cat as $pc){
			   $pc->cal_amount=0;
			   if($pc->category_type == 'FixedPay'){
				   if($pc->round_of_method == 'round_up'){
					   $pc->cal_amount=round($pc->amount);
				   }
				   else if($pc->round_of_method == 'round_half'){
					   $pc->cal_amount=round($pc->amount,0,PHP_ROUND_HALF_UP);
				   }
				   
			   }
			   else if($pc->category_type== 'DependsOnGP'){
				   if($basic_salary>0 && $pc->percentage >0){
					   if($pc->round_of_method == 'round_up'){
						$pc->cal_amount=round(($basic_salary*$pc->percentage)/100);
					   }
					    else if($pc->round_of_method == 'round_half'){
							$pc->cal_amount=round(($basic_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						}
				   }
			   }
			   else if($pc->category_type== 'DependsOnGPandPS'){
				   // this is pending
				   $main_amount=$basic_salary;
				   if($pc->dependant_payscale_categories != ''){
					   $t=explode(",",$pc->dependant_payscale_categories);
					   
					   foreach($t as $val){
						   $cat=$this->grade->get_single_grade_with_group($val);
						   if($cat->group_code ==1){
							   continue;
						   }
							if($cat->category_type == 'FixedPay'){
							   if($cat->round_of_method == 'round_up'){
								   $amt=round($cat->amount);
							   }
							   else if($cat->round_of_method == 'round_half'){
								   $amt=round($cat->amount,0,PHP_ROUND_HALF_UP);
							   }
							   
						   }
						   else if($cat->category_type== 'DependsOnGP'){
							   if($basic_salary>0 && $cat->percentage >0){
								   if($cat->round_of_method == 'round_up'){
									$amt=round(($basic_salary*$cat->percentage)/100);
								   }
									else if($cat->round_of_method == 'round_half'){
										$amt=round(($basic_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
									}
							   }
						   }
						    if($cat->is_deduction_type=='TRUE'){
								$main_amount = $main_amount - $amt;
							}
							else{
								$main_amount = $main_amount + $amt;
							}
							
					   }
				   }
				   if($pc->percentage >0){
					    if($pc->round_of_method == 'round_up'){
							$pc->cal_amount=round(($main_amount*$pc->percentage)/100);
					   }
					    else if($pc->round_of_method == 'round_half'){
							$pc->cal_amount=round(($main_amount*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						}
				   }
			   }
			   if($pc->is_deduction_type=='TRUE'){
				   
				   $expenditure[]=$pc;
				   $total_deduction += $pc->cal_amount;
			   }
			   else{
				   $total_earnings += $pc->cal_amount;
				   $earnings[]=$pc;
			   }
		   }
		   $net_salary=$basic_salary+$total_earnings-$total_deduction;
		   $this->data['net_salary']=$net_salary;
		   $this->data['total_earnings']=$total_earnings;
		   $this->data['total_deduction']=$total_deduction;
		   $this->data['earnings']=$earnings;
		   $this->data['expenditure']=$expenditure;
            $this->data['add'] = TRUE;   
            //print_r($earnings);
            $this->data['payments'] = $this->payment->get_payment_list($school_id, $user_id, $payment_to);            
           // $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$school_id));
		    $ledgers = $this->payment->get_list('account_ledgers', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
			 $this->data['account_ledgers']=$ledgers;
            
         }else{
             
            if($user_id){
                
                $this->data['user']    = $this->payment->get_single('users', array('id' => $user_id)); 
                
                $payment_to  = $this->data['user']->role_id == TEACHER ? 'teacher' : 'employee';
               
                $this->data['payment_to'] = $payment_to;
                $this->data['user_id'] = $user_id;
                $this->data['school_id'] = $this->data['user']->school_id;

                $this->data['payment'] = $this->payment->get_single_payment_user($this->data['user']->role_id, $user_id); 
                $this->data['payments'] = $this->payment->get_payment_list($this->data['school_id'], $user_id, $payment_to);
                $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$this->data['school_id']));
            }
            
            $this->data['list'] = TRUE;
        }        
       
        
        $this->layout->title( $this->lang->line('manage_payment'). ' | ' . SMS);
        $this->layout->view('payment/index', $this->data);            
       
    }
	public function detail(){	

		$params = array();
		parse_str($_POST['form_data'], $params);
		//print_r($params);
		$school_id  = $params['school_id'];
            $payment_to  = $params['payment_to'];
            $user_id  = $params['user_id'];
        
            $this->data['school_id'] = $school_id;
            $this->data['payment_to'] = $payment_to;
            $this->data['user_id'] = $user_id;
            
            $this->data['user']    = $this->payment->get_single('users', array('id' => $user_id));        
            $this->data['payment'] = $this->payment->get_single_payment_user($this->data['user']->role_id, $user_id); 
		   $payscale_cat=$this->grade->get_payscale_data_by_user($user_id);
		   $earnings=array();
		   $expenditure=array();
		   //$basic_salary=$this->data['payment']->basic_salary;
		   $basic_salary=$params['cal_basic_salary'];
		   $total_earnings=0;
		   $total_deduction=0;	
		   foreach($payscale_cat as $pc){
			   $pc->cal_amount=0;
			    if($pc->category_type == 'FixedPay'){
				   if($pc->round_of_method == 'round_up'){
					   $pc->cal_amount=round($pc->amount);
				   }
				   else if($pc->round_of_method == 'round_half'){
					   $pc->cal_amount=round($pc->amount,0,PHP_ROUND_HALF_UP);
				   }
				   
			   }
			   else if($pc->category_type== 'DependsOnGP'){
				   if($basic_salary>0 && $pc->percentage >0){
					   if($pc->round_of_method == 'round_up'){
						$pc->cal_amount=round(($basic_salary*$pc->percentage)/100);
					   }
					    else if($pc->round_of_method == 'round_half'){
							$pc->cal_amount=round(($basic_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						}
				   }
			   }			  
			   else if($pc->category_type== 'DependsOnGPandPS'){
				   // this is pending
				   $main_amount=$basic_salary;
				   if($pc->dependant_payscale_categories != ''){
					   $t=explode(",",$pc->dependant_payscale_categories);
					   
					   foreach($t as $val){
						   $cat=$this->grade->get_single_grade_with_group($val);
						   if($cat->group_code ==1){
							   continue;
						   }
							if($cat->category_type == 'FixedPay'){
							   if($cat->round_of_method == 'round_up'){
								   $amt=round($cat->amount);
							   }
							   else if($cat->round_of_method == 'round_half'){
								   $amt=round($cat->amount,0,PHP_ROUND_HALF_UP);
							   }
							   
						   }
						   else if($cat->category_type== 'DependsOnGP'){
							   if($basic_salary>0 && $cat->percentage >0){
								   if($cat->round_of_method == 'round_up'){
									$amt=round(($basic_salary*$cat->percentage)/100);
								   }
									else if($cat->round_of_method == 'round_half'){
										$amt=round(($basic_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
									}
							   }
						   }
						    if($cat->is_deduction_type=='TRUE'){
								$main_amount = $main_amount - $amt;
							}
							else{
								$main_amount = $main_amount + $amt;
							}
							
					   }
				   }
				   if($pc->percentage >0){
					    if($pc->round_of_method == 'round_up'){
							$pc->cal_amount=round(($main_amount*$pc->percentage)/100);
					   }
					    else if($pc->round_of_method == 'round_half'){
							$pc->cal_amount=round(($main_amount*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						}
				   }
			   }
			   if($pc->is_deduction_type=='TRUE'){
				   
				   $expenditure[]=$pc;
				   $total_deduction += $pc->cal_amount;
			   }
			   else{
				   $total_earnings += $pc->cal_amount;
				   $earnings[]=$pc;
			   }
		   }
		   $net_salary=$basic_salary+$total_earnings-$total_deduction;
		   $this->data['net_salary']=$net_salary;
		   $this->data['total_earnings']=$total_earnings;
		   $this->data['total_deduction']=$total_deduction;
		   $this->data['earnings']=$earnings;
		   $this->data['expenditure']=$expenditure;		
			echo $this->load->view('payment/detail', $this->data);		   		   
		
		
	}

    
     /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Add new Payment" user interface                 
     *                    and store "Payment" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add() {
        
        check_permission(ADD);
        
        if ($_POST) {
			
            $this->_prepare_payment_validation();
            if ($this->form_validation->run() === TRUE) {
				// check if voucher for salary payment is set for school
				$school=$this->payment->get_single('schools', array('id' => $_POST['school_id']));
				if($school->default_voucher_Id_for_salary_payment > 0){					
					$data = $this->_get_posted_payment_data();
					$insert_id = $this->payment->insert('salary_payments', $data);
					if ($insert_id) {
						// Account entry for total salary
						$voucher_id=$school->default_voucher_Id_for_salary_payment;
						$user_data=$this->payment->get_single('users', array('id' => $data['user_id']));
						if($data['debit_ledger_id']>0 && $data['credit_ledger_id']>0){
							$transaction=array();
							$transaction['transaction_no']=$this->transactions->generate_transaction_no($voucher_id);
							$transaction['voucher_id']=$voucher_id;									
							$transaction['ledger_id']=$data['debit_ledger_id'];
                            $edited_ledger_ids[] =$data['debit_ledger_id'];

							$transaction['head_cr_dr']='DR';									
							$transaction['date']=date('Y-m-d H:i:s');							
							$transaction['created']=date('Y-m-d H:i:s');								
							$transaction['narration']="Salary Payment of ".$user_data->username;
							if(isset($transaction['voucher_id'])){													
								$transaction_id = $this->transactions->insert('account_transactions', $transaction);
								if ($transaction_id) {								
											$detail=array();
											$detail['transaction_id']=$transaction_id;
											$detail['ledger_id']=$data['credit_ledger_id'];
                                            $edited_ledger_ids[] =$data['credit_ledger_id'];
											$detail['amount']=$data['net_salary'];
											$detail['created']=date('Y-m-d H:i:s');
											$detail_id=$this->transactions->insert('account_transaction_details', $detail);
										} 
									}
							// Update salary paymenmt with transaction id
							$updated = $this->payment->update('salary_payments', array("transaction_id"=>$transaction_id), array('id' => $insert_id));
						}
						// Account entry for each payscale category insert salary details						
						if(!empty($_POST['cat'])){
							foreach($_POST['cat'] as $cat_id=>$amt){
								$in_arr=array();
								$in_arr['salary_payment_id']=$insert_id;
								$in_arr['payscalecategory_id']=$cat_id;
								$in_arr['amount']=$amt;
								$in_id=$this->payment->insert('salary_payment_details', $in_arr);
								// insert details in ledgers
								$category=$this->payment->get_single('payscale_category', array('id' => $cat_id));
																				
								if($category->debit_ledger_id > 0 || $category->credit_ledger_id >0){
									
									$transaction=array();
									$transaction['transaction_no']=$this->transactions->generate_transaction_no($voucher_id);
									$transaction['voucher_id']=$voucher_id;	
									if($category->debit_ledger_id > 0){
										$transaction['ledger_id']=$category->debit_ledger_id;
										$transaction['head_cr_dr']='DR';
                                        $edited_ledger_ids[] = $category->debit_ledger_id;

									}
									else if($category->credit_ledger_id >0){
										$transaction['ledger_id']=$category->credit_ledger_id;
										$transaction['head_cr_dr']='CR';
                                        $edited_ledger_ids[] = $category->credit_ledger_id;
									}

									$transaction['date']=date('Y-m-d H:i:s');							
									$transaction['created']=date('Y-m-d H:i:s');									
									$transaction['narration']="Salary Payment of ".$user_data->username;
									if(isset($transaction['voucher_id'])){
										$transaction_id = $this->transactions->insert('account_transactions', $transaction);
										if ($transaction_id) {
												// add details to transaction details									
											$detail=array();
											$detail['transaction_id']=$transaction_id;
												//$detail['ledger_id']=$category->credit_ledger_id;
											$detail['ledger_id']=$data['credit_ledger_id'];
                                            $edited_ledger_ids[] = $data['credit_ledger_id'];
											$detail['amount']=$amt;											
											$detail['created']=date('Y-m-d H:i:s');
											$detail_id=$this->transactions->insert('account_transaction_details', $detail);
										} 
									}
								}
							}
						}
					
                        if(!empty($edited_ledger_ids))
                        {
                            update_ledger_opening_balance($edited_ledger_ids, $_POST['school_id']);
                        }
						success($this->lang->line('insert_success'));
						redirect('payroll/payment/index/'.$this->input->post('user_id'));
					} else {
						error($this->lang->line('insert_failed'));
						redirect('payroll/payment/index/'.$this->input->post('user_id'));
					}
				} 
				else{
					error('Please update default voucher for salary payment by editing school details.');
					redirect('payroll/payment/index/'.$this->input->post('user_id'));
				}
			}else {
                
                $school_id  = $this->input->post('school_id');
                $payment_to  = $this->input->post('payment_to');
                $user_id  = $this->input->post('user_id');
                
                $this->data['payment_to'] = $payment_to;
                $this->data['user_id'] = $user_id;

                $this->data['user']    = $this->payment->get_single('users', array('id' => $user_id));        
                $this->data['payment'] = $this->payment->get_single_payment_user($this->data['user']->role_id, $user_id); 
                $this->data['payments'] = $this->payment->get_payment_list($school_id, $user_id, $payment_to);
                $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$school_id));
                
                $this->data['post'] = $_POST;
            }
        }

                
         
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('payment'). ' | ' . SMS);
        $this->layout->view('payment/index', $this->data);
    }

    
        
    /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Payment" user interface                 
     *                    with populated "Payment" value 
     *                    and update "Payment" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {  
        
       check_permission(EDIT);
       
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/payment/index');
        }
        
        if ($_POST) {
            $this->_prepare_payment_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_payment_data();
                $updated = $this->payment->update('salary_payments', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    success($this->lang->line('update_success'));
                    redirect('payroll/payment/index/' . $this->input->post('user_id'));                
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('payroll/payment/edit/' . $this->input->post('id'));
                }
            } else {                
                error($this->lang->line('unexpected_error'));
                // redirect('payroll/payment/edit/' . $this->input->post('id'));
            }
        }
        
        if ($id) {
            
            
            $this->data['edit_payment'] = $this->payment->get_single('salary_payments', array('id' => $id));

            if (!$this->data['edit_payment']) {
                 redirect('payroll/payment/index');
            }
            
            $user         = $this->payment->get_single('users', array('id' => $this->data['edit_payment']->user_id));
            
           // $salary_grade = $this->payment->get_single('salary_grades', array('id' => $this->data['edit_payment']->salary_grade_id));
           // $this->data['expenditure'] = $this->payment->get_single('expenditures', array('id' => $this->data['edit_payment']->expenditure_id));
            $this->data['edit_payment']->grade_name = $salary_grade->grade_name;
            
            $payment_to  = $user->role_id == TEACHER ? 'teacher' : 'employee';
            $this->data['payment_to'] = $payment_to;
            $this->data['user_id'] = $user->id;
          
            
            $this->data['payment'] = $this->payment->get_single_payment_user($user->role_id, $user->id);
            
            $this->data['school_id'] = $this->data['payment']->school_id;
            
            $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$this->data['school_id']));
            $this->data['payments'] = $this->payment->get_payment_list($this->data['school_id'], $user->id, $payment_to);
			$payscale_cat=$this->grade->get_payscale_data_by_user($user->id);
		   $earnings=array();
		   $expenditure=array();
		   $basic_salary=$this->data['payment']->basic_salary;
		   $total_earnings=0;
		   $total_deduction=0;
		   foreach($payscale_cat as $pc){
			   $pc->cal_amount=0;
			   if($pc->category_type == 'FixedPay'){
				   $pc->cal_amount=$pc->amount;
			   }
			   else if($pc->category_type== 'DependsOnGP'){
				   if($basic_salary>0 && $pc->percentage >0){
						$pc->cal_amount=($basic_salary*$pc->percentage)/100;
				   }
			   }
			   if($pc->is_deduction_type=='TRUE'){
				   
				   $expenditure[]=$pc;
				   $total_deduction += $pc->cal_amount;
			   }
			   else{
				   $total_earnings += $pc->cal_amount;
				   $earnings[]=$pc;
			   }
		   }
		   $net_salary=$basic_salary+$total_earnings-$total_deduction;
		   $this->data['net_salary']=$net_salary;
		   $this->data['total_earnings']=$total_earnings;
		   $this->data['total_deduction']=$total_deduction;
		   $this->data['earnings']=$earnings;
		   $this->data['expenditure']=$expenditure;
        }
       
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('payment'). ' | ' . SMS);
        $this->layout->view('payment/index', $this->data);
    }
    
    
     /*****************Function _prepare_payment_validation**********************************
     * @type            : Function
     * @function name   : _prepare_payment_validation
     * @description     : Process "payment" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_payment_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('salary_type', $this->lang->line('salary_type'), 'trim|required');   
        $this->form_validation->set_rules('salary_month', $this->lang->line('month'), 'trim|required|callback_salary_month'); 
        //$this->form_validation->set_rules('gross_salary', $this->lang->line('gross_salary'), 'trim|required'); 
        $this->form_validation->set_rules('net_salary', $this->lang->line('net_salary'), 'trim|required'); 
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
    }
    
    
     /*****************Function salary_month**********************************
     * @type            : Function
     * @function name   : salary_month
     * @description     : Unique check for "Salary payment" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */  
   public function salary_month()
   {             
      if($this->input->post('id') == '')
      {   
          $payment = $this->payment->duplicate_check($this->input->post('salary_month'), $this->input->post('user_id')); 
          if($payment){
                $this->form_validation->set_message('salary_month',  $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $payment = $this->payment->duplicate_check($this->input->post('salary_month'), $this->input->post('user_id'), $this->input->post('id')); 
          if($payment){
                $this->form_validation->set_message('salary_month', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }   
   }


     /*****************Function _get_posted_payment_data**********************************
     * @type            : Function
     * @function name   : _get_posted_payment_data
     * @description     : Prepare "payment" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_payment_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'user_id';
       // $items[] = 'salary_grade_id';       
        $items[] = 'salary_type';
        $items[] = 'salary_month';
		$items[] = 'debit_ledger_id';
		$items[] = 'credit_ledger_id';
        
        if( strtolower($this->input->post('salary_type')) == 'monthly'){
            
            $items[] = 'basic_salary';   
			$items[] = 'working_days';
			$items[] = 'cal_basic_salary';      			
            
        }else{
            
        }
        
        //$items[] = 'expenditure_head_id';      
        $items[] = 'total_allowance';
        $items[] = 'total_deduction';
        $items[] = 'net_salary';
        $items[] = 'payment_method';
        $items[] = 'payment_to';
        
        $items[] = 'bank_name';
        $items[] = 'cheque_no';
        
        $items[] = 'note';
        
        $data = elements($items, $_POST);  
        
        if($this->input->post('payment_method') == 'cash'){
            $data['bank_name'] = ''; 
            $data['cheque_no'] = ''; 
        }
      
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            
            // Update data into Expenditure table
          /*  $exp_data = array();
            $exp_data['amount'] = $data['net_salary'];
            $exp_data['expenditure_via'] = $data['payment_method'];
            $exp_data['note'] = $data['note'];
            $exp_data['modified_at'] = $data['modified_at'];
            $exp_data['modified_by'] = $data['modified_by'];
            //$this->payment->update('expenditures', $exp_data, array('id' => $this->input->post('expenditure_id')));*/
            
        } else {
            
            $school = $this->payment->get_school_by_id($data['school_id']);
            
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('payroll/payment/index');
            }
            
            $data['academic_year_id'] = $school->academic_year_id;
            
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
            
            // Insert data into Expenditure table
          /*  $exp_data = array();
            
            $exp_data['school_id'] = $this->input->post('school_id');
            $exp_data['expenditure_head_id'] = $this->input->post('expenditure_head_id');
            $exp_data['status'] = 1;
            $exp_data['expenditure_type'] = 'salary';
            $exp_data['date'] = date('Y-m-d');
            $exp_data['amount'] = $data['net_salary'];
            $exp_data['expenditure_via'] = $data['payment_method'];
            $exp_data['note'] = $data['note'];
            $exp_data['academic_year_id'] = $data['academic_year_id'];
            $exp_data['created_at'] = $data['created_at'];
            $exp_data['created_by'] = $data['created_by'];
                    
            $data['expenditure_id'] = $this->payment->insert('expenditures', $exp_data);*/
        }

        return $data;
    }

    
    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Salary Payment and Expenditure amount as Salary" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/payment/index');
        }
        
        $payment = $this->payment->get_single('salary_payments', array('id' => $id));
        
        if ($this->payment->delete('salary_payments', array('id' => $id))) {

            $this->payment->delete('expenditures', array('id' => $payment->expenditure_id)); 
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('payroll/payment/index/'.$payment->user_id);
    } 
    
    
    
     /*****************Function get_single_payment**********************************
     * @type            : Function
     * @function name   : get_single_payment
     * @description     : "Load single salary payment information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_payment(){
        
       $payment_id = $this->input->post('payment_id');
       $payment_to = $this->input->post('payment_to');
       
       $this->data['payment'] = $this->payment->get_single_payment($payment_id, $payment_to);
	   $this->data['payment_detail']=$this->payment->get_salary_payment_detail($payment_id);
       echo $this->load->view('get-single-payment', $this->data);
    }
    
    
    /*****************Function history**********************************
     * @type            : Function
     * @function name   : history
     * @description     : Load "Employee & Teacher Payment History" user interface                 
     *                    
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function history_xx() {
        
        check_permission(VIEW);
        
        $this->data['users'] = '';
        
         if ($_POST) {
             
            $payment_to  = $this->input->post('payment_to');
            $user_id  = $this->input->post('user_id');
        
            $this->data['payment_to'] = $payment_to;
            $this->data['user_id'] = $user_id;
            
            $this->data['payments'] = $this->payment->get_payment_list($user_id, $payment_to);
            
         }
        
        $this->data['list'] = TRUE;       
        $this->layout->title( $this->lang->line('manage_payment'). ' | ' . SMS);
        $this->layout->view('payment/history', $this->data);            
       
    }
	public function payslip($id = null){
		 if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/payment/index');
        }
        
        $this->data['payment'] = $this->payment->get_single('salary_payments', array('id' => $id));
		$this->data['school'] = $this->payment->get_school_by_id($this->data['payment']->school_id); 		
		$this->data['detail']=$this->payment->get_salary_payment_detail($id);
		$this->data['transaction']=$this->transactions->get_transactions_by_id($this->data['payment']->transaction_id);		
		$this->data['transaction_detail']=$this->transactions->get_transaction_detail($this->data['payment']->transaction_id);				
		$this->layout->title( $this->lang->line('payslip'). ' | ' . SMS);
        $this->layout->view('payment/payslip', $this->data);     
	}

   
}
