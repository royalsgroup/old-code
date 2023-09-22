<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Bulk.php**********************************
 * @product name    : Global School Management System Pro
 * @type            : Class
 * @class name      : Bulk
 * @description     : Manage bulk students imformation of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class CSV extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();   		
		$this->load->model('Administrator/Year_Model', 'year', true);
		$this->load->model('academic/Classes_Model', 'classes', true);     
		$this->load->model('academic/Section_Model', 'section', true); 
		$this->load->model('Disciplines_Model', 'discipline', true); 
				
		$this->load->model('Accounting/Feetype_Model', 'feetype', true);  		
		$this->load->model('Accountledgers_Model', 'accountledgers', true);  		
		$this->load->model('Voucher_Model', 'voucher', true);  	
		$this->load->model('Teacher/Teacher_Model', 'teacher', true);		
		$this->load->model('hrm/Employee_Model', 'employee', true);  
		$this->load->model('Payroll/Payscalecategory_Model', 'grade', true);            			
		$this->load->model('Student/Student_Model', 'student', true);  
		$this->load->model('Accounttransactions_Model', 'transactions', true);			
    }  
    public function index() {	
	
        if ($_POST) {			
			$school_id  = $this->input->post('school_id');  						
			$import_arr=array('financial_year','account_groups','account_ledgers','account_vouchers','ledger_entries');
			if($school_id != ''){
				if(in_array($_POST['type'],$import_arr)){
					$school_detail=$this->year->get_single('schools',array("id"=>$school_id));
					if($school_detail->data != 'Import'){
						error('You can not import data for this school.');
						redirect('import/csv');
					}
				}
			}
				
				$status = $this->_get_posted_data();
				if ($status) {                   
				   // success($this->lang->line('insert_success'));
					//redirect('vouchers/index/'.$this->input->post('school_id'));
				} else {
					error($this->lang->line('insert_failed'));
					redirect('import/csv');
				} 
				
        }                    
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
             $school_id = $this->session->userdata('school_id');       
        }        
        $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('csv') . ' | ' . SMS);        
        $this->layout->view('import/csv', $this->data);
    }
    
    private function _get_posted_data() {
        $this->_upload_file();
        //$destination = 'assets/csv/bulk_uploaded_data.csv';
		if($_POST['type']== 'academic_year'){
			$status=$this->_academic_year();
		}
		else if($_POST['type']== 'financial_year'){
			$status=$this->_financial_year();
		}
		else if($_POST['type']== 'academic_discipline'){
			$status=$this->_academic_discipline();
		}
		else if($_POST['type']== 'academic_standard'){
			$status=$this->_academic_standard();
		}
		else if($_POST['type']== 'academic_subjects'){
			$status=$this->_academic_subjects();
		}
		else if($_POST['type']== 'account_groups'){
			$status=$this->_account_groups();
		}
		else if($_POST['type']== 'account_ledgers'){
			$status=$this->_account_ledgers();
		}
		else if($_POST['type']== 'account_vouchers'){			
			$status=$this->_account_vouchers();
		}
		else if($_POST['type']== 'ledger_entries'){
			$status=$this->_ledger_entries();
		}
		else if($_POST['type']== 'fee_type'){
			$status=$this->_fee_type();
		}
		else if($_POST['type']== 'pay_groups'){
			$status=$this->_pay_groups();
		}
		else if($_POST['type']== 'payment_modes'){
			$status=$this->_payment_modes();
		}
		else if($_POST['type']== 'payscale_categories'){
			$status=$this->_payscale_categories();
		}
		else if($_POST['type']== 'employment_type'){
			$status=$this->_employment_type();
		}
		else if($_POST['type']== 'class'){
			$status=$this->_class();
		}
		else if($_POST['type']== 'teacher'){
			$status=$this->_teacher(0);
		}
		else if($_POST['type']== 'alumni_teacher'){
			$status=$this->_teacher(1);
		}
		else if($_POST['type']== 'employee'){
			$status=$this->_employee(0);
		}
		else if($_POST['type']== 'alumni_employee'){
			$status=$this->_employee(1);
		}
		else if($_POST['type']== 'student'){
			$status=$this->_student();
		}
		else if($_POST['type']== 'alumni_student'){
			$status=$this->_student();
		}
		return $status;        
    } 
	public function school(){
		
		 if ($_POST) { 		 	
            $this->_upload_file();
			$status=$this->_school();
            if ($status) {                   
                success($this->lang->line('insert_success'));
                //redirect('vouchers/index/'.$this->input->post('school_id'));
            } else {
                error($this->lang->line('insert_failed'));
                redirect('import/school');
            }            
        }    
		 $this->layout->title($this->lang->line('import') . ' ' . $this->lang->line('csv') . ' | ' . SMS);        
        $this->layout->view('import/school', $this->data);
	}
	private function _school(){		
		$file = $_FILES['bulk_data']['name'];

       
           $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {
				
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[7] != '') {					                				
                    $data = array();                  
                    $data['state_id'] =$arr[1];
					$data['zone_id'] =$arr[2];
					$data['subzone_id'] =$arr[3];
					$data['district_id'] =$arr[4];
					$data['block_id'] =$arr[5];
					$data['sankul_id'] =$arr[6];
					$data['school_name'] =$arr[7];
					$data['school_code'] =$arr[8];
					$data['registration_date'] =$arr[9];					
					$data['address'] =$arr[10];
					$data['phone'] =$arr[11];
					$data['email'] =$arr[12];
					$data['currency'] =$arr[13];
					$data['currency_symbol'] =$arr[14];
					
					$data['footer'] =$arr[15];
					$data['academic_year_id'] =$arr[18];
					$data['academic_year'] =$arr[19];
					$data['school_fax'] =$arr[20];
					$data['school_lat'] =$arr[21];
					$data['school_lng'] =$arr[22];
					$data['map_api_key'] =$arr[23];
					$data['zoom_api_key'] =$arr[24];
					$data['zoom_secret'] =$arr[25];					
					$data['enable_frontend'] =$arr[26];
					$data['enable_online_admission'] =$arr[27];
					$data['final_result_type'] =$arr[28];
					$data['language'] =$arr[29];
					$data['theme_name'] =$arr[30];
					
					$data['about_text'] =$arr[31];
					$data['facebook_url'] =$arr[33];
					$data['twitter_url'] =$arr[34];
					$data['linkedin_url'] =$arr[35];
					$data['google_plus_url'] =$arr[36];
					$data['youtube_url'] =$arr[37];
					$data['instagram_url'] =$arr[38];
					$data['pinterest_url'] =$arr[39];
					$data['status'] =$arr[42];					
					$data['school_category'] =$arr[43];					
					$data['education_type'] =$arr[44];					
					$data['created_at'] =date('Y-m-d H:i:s');	
					$data['modified_at'] =date('Y-m-d H:i:s');	
					$data['created_by'] =logged_in_user_id();
					$data['modified_by'] =logged_in_user_id();
					$school_id = $this->year->insert('schools', $data);
					
					// create default user as principal
					$user_arr=array();
					 $user_arr['role_id']    = 2;
					$user_arr['school_id']    = $school_id;
					$user_arr['password']   = md5('welcome');
					$user_arr['temp_password'] = base64_encode('welcome');		
					$user_arr['username']      = $arr[8];
					$user_arr['created_at'] = date('Y-m-d H:i:s');
					$user_arr['created_by'] = logged_in_user_id();
					$user_arr['status']     = 1; // by default would not be able to login
					$this->employee->insert('users', $user_arr);
					$user_id=$this->db->insert_id();
				}
			}
		}
		return true;
	}
	private function _academic_year(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$session_year=date('F Y',strtotime($arr[1]))."-".date('F Y',strtotime($arr[2]));
					$data['session_year']=$session_year;
					$start_year=date('Y',strtotime($arr[1]));
					$data['start_year']=$start_year;
					$end_year=date('Y',strtotime($arr[2]));
					$data['end_year']=$end_year;
					$data['status']=1;						
					$data['created_at'] = date('Y-m-d H:i:s');					
                    $data['created_by'] = logged_in_user_id();
					// check for duplicate record
					$year=$this->year->get_single('academic_years',array("school_id"=>$school_id,'start_year'=>$start_year,"end_year"=>$end_year));
					if(empty($year)){						
						$y_id = $this->year->insert('academic_years', $data);                  
					}
				
				
                }
            }
        }

        return TRUE;
	}
	private function _financial_year(){
		$file = $_FILES['bulk_data']['name'];

       
           $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
			
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     
			$success_count=0;
			$duplicate_count=0;
            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$session_year=date('F Y',strtotime($arr[2]))."-".date('F Y',strtotime($arr[1]));
					$data['session_year']=$session_year;
					$start_year=date('Y',strtotime($arr[2]));
					$data['start_year']=$start_year;
					$end_year=date('Y',strtotime($arr[1]));
					$data['end_year']=$end_year;
					$data['status']=1;
					$data['is_running']=1;
					$data['created_at'] = date('Y-m-d H:i:s');					
                    $data['created_by'] = logged_in_user_id();
					// check for duplicate record
					$year=$this->year->get_single('financial_years',array("school_id"=>$school_id,'start_year'=>$start_year,"end_year"=>$end_year));
					if(empty($year)){	
						$success_count++;
						$y_id = $this->year->insert('financial_years', $data);                  
					}
					else{
						$duplicate_count++;
					}
				
				
                }
            }
			$success_msg='';
			if($success_count > 0){
				$success_msg .= $success_count." data inserted successfully. ";
			}
			if($duplicate_count >0){
				$success_msg .= $duplicate_count. " duplicate record found.";
			}
			success($success_msg);
        }

        return TRUE;
	}
	private function _academic_discipline(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;    
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                
				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name']=$arr[0];
					$check=$this->year->get_single('academic_disciplines',array("school_id"=>$school_id,'name'=>$arr[0]));
					if(empty($check)){	
	
						$y_id = $this->year->insert('academic_disciplines', $data);                  
					}
				}
			}
		}
		return TRUE;
	}
	private function _academic_standard(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                
				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['level']=$arr[0];
					$data['board']=$arr[1];
					$data['stream']=$arr[2];
					$check=$this->year->get_single('academic_standards',array("school_id"=>$school_id,'level'=>$arr[0],'stream'=>$arr[2]));
					if(empty($check)){	
						$y_id = $this->year->insert('academic_standards', $data);                  
					}
				}
			}
		}
		return TRUE;
	}
	private function _academic_subjects(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                
				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name']=$arr[0];					
					$check=$this->year->get_single('academic_subjects',array("school_id"=>$school_id,'name'=>$arr[0]));
					if(empty($check)){	
						$discipline=$this->year->get_single('academic_disciplines',array("school_id"=>$school_id,'name'=>$arr[1]));
						if(!empty($discipline)){
							$data['academic_discipline_id']=$discipline->id;
						}
						$y_id = $this->year->insert('academic_subjects', $data);                  
					}
				}
			}
		}
		return TRUE;
	}
	private function _account_groups(){
		$file = $_FILES['bulk_data']['name'];

       
           $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;    
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');  
			
			$success_count=0;
			$duplicate_count=0;
            while (($arr = fgetcsv($handle)) !== false) {
 		
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                
				if ($arr[4] != '') {
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name']=$arr[0];	
					if(strtolower($arr[1])=='true'){
						$data['is_primary']=1;
					}
					else{
						$data['is_primary']=0;
					}
					$data['group_code']=$arr[4];
					
					$check=$this->year->get_single('account_groups',array("school_id"=>$school_id,'group_code'=>$arr[4]));
					
					if(empty($check)){	
						$type=$this->year->get_single('account_types',array('name'=>$arr[2]));
						if(!empty($type)){
							$data['type_id']=$type->id;
						}
						$base=$this->year->get_single('account_base',array('name'=>$arr[3]));
						if(!empty($base)){
							$data['base_id']=$base->id;
						}
						
						$y_id = $this->year->insert('account_groups', $data);                  
						$success_count++;
					}
					else{
						$duplicate_count++;
					}
				}
				}
			}
			
			$success_msg='';
			if($success_count > 0){
				$success_msg .= $success_count." data inserted successfully. ";
			}
			if($duplicate_count >0){
				$success_msg .= $duplicate_count. " duplicate record found.";
			}
			success($success_msg);
		}
		
		return TRUE;
	}
	private function _account_ledgers(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;    
			
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     
			$success_count=0;
			$duplicate_count=0;
            while (($arr = fgetcsv($handle)) !== false) {			
				if ($count == 1) {
                    $count++;
                    continue;
                }					
				if ($arr[0] != '') {					                		
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name'] =trim($arr[0]);	
					$data['dr_cr']=$arr[6];
					$data['ledger_uid']=trim($arr[12]);
					$year=$arr[14];
					$y_arr=explode("-",$year);
					if(isset($y_arr[0]) && isset($y_arr[1])){
						$year_data=$this->year->get_single('financial_years', array('start_year' => $y_arr[0],'end_year'=>$y_arr[1],'school_id'=>$school_id));
					}
					$check=$this->year->get_single('account_ledgers',array("school_id"=>$school_id,'name'=>trim($arr[0])));
					if(empty($check)){
						$ch="";
						$ledger=1;
					}  else {
					$check1=$this->year->get_single('account_ledger_details',array("ledger_id"=>$check->id,'financial_year_id'=>$year_data->id));
					
						if(empty($check1)){
							$ch="";
							
						} else {
							$ch=1;
						}
					}
					
					if(empty($ch)){										
						$group=$this->year->get_single('account_groups',array('school_id'=>$school_id,'group_code'=>$arr[15]));	
						
						$data['created'] = date('Y-m-d H:i:s');
						//$data['created_by'] = logged_in_user_id();
						if(!empty($group)){
							
							$data['account_group_id']=$group->id;
							if($ledger==1){
								$ledger_id = $this->year->insert('account_ledgers', $data);
							} else {
								
								$ledger_id = $check->id;
							
							}
							
							$success_count++;
							// academic year
							$year=$arr[14];
							$y_arr=explode("-",$year);
							if(isset($y_arr[0]) && isset($y_arr[1])){
								$year_data=$this->year->get_single('financial_years', array('start_year' => $y_arr[0],'end_year'=>$y_arr[1],'school_id'=>$school_id)); 							
								if(!empty($year_data)){								
									// detail
									$detail=array();
									$detail['ledger_id']=$ledger_id;
									$detail['financial_year_id']=$year_data->id;
									$detail['opening_cr_dr']=$arr[8];
									$detail['opening_balance']=$arr[9];
									$detail['budget']=$arr[11];
									$detail['budget_cr_dr']=$arr[10];
									
									if(trim($arr[2])!= ''){
										$detail['current_balance']=trim($arr[2]);
										$detail['current_balance_cr_dr']='DR';
									}
									else if(trim($arr[3])!= ''){
										$detail['current_balance']=trim($arr[3]);
										$detail['current_balance_cr_dr']='CR';
									}
									$ledgerdetail_id = $this->year->insert('account_ledger_details', $detail);
									
									// 
									if($y_arr[0]== '2020' && $y_arr[1] == '2021'){
										// insert current balance as opening balance
										// finanacial year id of 2021-2022
										$year_data=$this->year->get_single('financial_years', array('start_year' => '2021','end_year'=>'2022','school_id'=>$school_id));
										if(!empty($year_data)){
											$financial_year_id=$year_data->id;
										}
										else{
											// insert
											$fdata = array();                  
											$fdata['school_id'] =$school_id;
											$session_year='April 2021 - March 2022';
											$fdata['session_year']=$session_year;
											$start_year='2021';
											$fdata['start_year']=$start_year;
											$end_year='2022';
											$fdata['end_year']=$end_year;
											$fdata['status']=1;						
											$fdata['created_at'] = date('Y-m-d H:i:s');					
											$fdata['created_by'] = logged_in_user_id();	
											$financial_year_id=$this->year->insert('financial_years',$fdata);
										}
										$detail=array();
										$detail['ledger_id']=$ledger_id;
										$detail['financial_year_id']=$financial_year_id;
										$detail['opening_cr_dr']=$arr[6];
										$detail['opening_balance']=$arr[7];										
										$ledgerdetail_id = $this->year->insert('account_ledger_details', $detail);
									}
								}
							}
							// insert current balace- add transaction
						/*	$voucher=$this->year->get_single('vouchers',array('school_id'=>$school_id,'name'=>'old_data'));	
							if(empty($voucher)){
								// create voucher
								$voucher_data=array();
								$voucher_data['school_id']=$school_id;
								$voucher_data['name']='old_data';
								$voucher_data['type_id']=1;
								$voucher_data['created'] = date('Y-m-d H:i:s');
								$voucher_id = $this->year->insert('vouchers', $voucher_data);
							}	
							else{
								$voucher_id=$voucher->id;
							}
							// cal amount
							$current_balance=$arr[7];
							if($arr[6]=='DR'){
								$current_balance=(-$current_balance);
							}
							$opening_balance=$arr[9];
							if($arr[8]=='DR'){
								$opening_balance=(-$opening_balance);
							}
							$transaction_amount=($current_balance)-($opening_balance);
							if($transaction_amount <0){
								$transaction_cr_dr='DR';
							}
							else{
								$transaction_cr_dr='CR';
							}
							// generate transaction for each ledger
							if(abs($transaction_amount) >0){
								$tran=array();
								$tran['transaction_no']=$this->transactions->generate_transaction_no($voucher_id);
								$tran['voucher_id']=$voucher_id;
								$tran['ledger_id']=$ledger_id;
								$tran['head_cr_dr']=$transaction_cr_dr;
								$tran['date']=date('Y-m-d H:i:s');							
								$tran['created']=date('Y-m-d H:i:s');
							   // if ($this->form_validation->run() === TRUE) {	
								if(isset($voucher_id)){
									//$data = $this->_get_posted_transaction_data();
									
									$transaction_id = $this->transactions->insert('account_transactions', $tran);
									if ($transaction_id) {
										// add details to transaction details									
												$detail=array();
												$detail['transaction_id']=$transaction_id;
												$detail['ledger_id']=$ledger_id;
												$detail['amount']=abs($transaction_amount);
												$detail['remark']='Old data';
												$detail['created']=date('Y-m-d H:i:s');
												$detail_id=$this->transactions->insert('account_transaction_details', $detail);
																		
									}
								}
							}*/
						}
					}
					else{
						$duplicate_count++;
					}
				}
			}
			$success_msg='';
			if($success_count > 0){
				$success_msg .= $success_count." data inserted successfully. ";
			}
			if($duplicate_count >0){
				$success_msg .= $duplicate_count. " duplicate record found.";
			}
			success($success_msg);
			
		}
		
		return TRUE;
	}
	private function _ledger_entries(){
		$file = $_FILES['bulk_data']['name'];
		$edited_ledger_ids = [];
       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     
			$success_count=0;
			$duplicate_count=0;
			
            while (($arr = fgetcsv($handle)) !== false) {				
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '' && $arr[10] != 'TRUE') {
//print_r($arr);					
                    $data = array();                  
                   // $data['school_id'] =$school_id;
					// get voucher
					$voucher=$this->transactions->get_single("vouchers",array('school_id'=>$school_id,'name'=>trim($arr[8])));
					if(!empty($voucher)){					
						$data['voucher_id']=$voucher->id;
						$transaction_no=trim($arr[9]);
						$data['transaction_no']=$transaction_no;
						$data['head_cr_dr']='DR';
						$data['date']=date('Y-m-d H:i:s',strtotime($arr[0]));
						$data['created']=date('Y-m-d H:i:s');
						$transaction=$this->transactions->get_single("account_transactions",array('transaction_no'=>$transaction_no));
						$ledger=$this->transactions->get_single("account_ledgers",array('school_id'=>$school_id,'name'=>trim($arr[1])));
						if(!empty($ledger)){
							if(empty($transaction)){																			
								if($arr[11] == 'DR'){																
									$data['ledger_id']=$ledger->id;
									$edited_ledger_ids[] = $ledger->id;
									$data['narration']=$arr[5];
									//print_r($data);
									$transaction_id=$this->transactions->insert('account_transactions',$data);
									//print_r($transaction_id); exit;
								}							
								else if($arr[11] == 'CR'){
								
									$transaction_id=$this->transactions->insert('account_transactions',$data);
									if($transaction_id >0){
									// insert into ledger detail
									$detail=array();
									$detail['transaction_id']=$transaction_id;
									$detail['ledger_id']=$ledger->id;
									$edited_ledger_ids[] = $ledger->id;
									$detail['amount']=$arr[12];
									$detail['remark']=$arr[5];
									$detail['created']=date('Y-m-d H:i:s');
									$detail_id=$this->transactions->insert('account_transaction_details',$detail);
									}
								}
							
							}
							else{					
								if($arr[11] == 'CR'){
									$transaction_id=$transaction->id;
										// insert into ledger detail
										$detail=array();
										$detail['transaction_id']=$transaction_id;
										$detail['ledger_id']=$ledger->id;
										$edited_ledger_ids[] = $ledger->id;

										$detail['amount']=$arr[12];
										$detail['remark']=$arr[5];
										$detail['created']=date('Y-m-d H:i:s');
										$detail_id=$this->transactions->insert('account_transaction_details',$detail);
								}
								else if($arr[11] == 'DR'){
									// update ledger id and narration
									$update_arr=array();
									$update_arr['ledger_id']=$ledger->id;
									$edited_ledger_ids[] = $ledger->id;
									$update_arr['narration']=$arr[5];
									//$transaction_id=$this->transactions->update('account_transactions',$update_arr,array('id'=>$transaction->id));
									
									$transaction_id=$this->transactions->insert('account_transactions',$update_arr);
								}
							}
						}
					}
				}
			}
			$success_msg='';
			if($success_count > 0){
				$success_msg .= $success_count." data inserted successfully. ";
			}
			if($duplicate_count >0){
				$success_msg .= $duplicate_count. " duplicate record found.";
			}
			success($success_msg);
		}
		if(!empty($edited_ledger_ids))
		{
			update_ledger_opening_balance($edited_ledger_ids, $school_id);
		}
		return TRUE;
	}
	private function _account_vouchers(){
		if(isset($_POST['voucher_category']) && $_POST['voucher_category']!=''){
			
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     
			$success_count=0;
			$duplicate_count=0;
            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name'] =$arr[0];											
					// check for duplicate 
					$check=$this->year->get_single('vouchers',array('school_id'=>$school_id,'name'=>$arr[0],'category'=>$_POST['voucher_category']));
					if(empty($check)){	
						$type_name= trim($arr[2]);							
						$type=$this->year->get_single('voucher_types',array('name'=>$type_name));			
						if(!empty($type)){
							$data['type_id']=$type->id;
						}
						$data['category']=$_POST['voucher_category'];
						$data['created'] = date('Y-m-d H:i:s');
						$voucher_id = $this->year->insert('vouchers', $data);
						$success_count++;
					}
					else{
						$duplicate_count++;
					}
				}
			}
			$success_msg='';
			if($success_count > 0){
				$success_msg .= $success_count." data inserted successfully. ";
			}
			if($duplicate_count >0){
				$success_msg .= $duplicate_count. " duplicate record found.";
			}
			success($success_msg);
		}
		}
		else{
			error("Please select voucher category.");
		}
		return TRUE;
	}
	private function _fee_type(){
		$file = $_FILES['bulk_data']['name'];

       
           $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['head_type'] ='fee';					
					$data['title'] =$arr[0];
					$data['status']=1;
					$check=$this->year->get_single('income_heads',array('school_id'=>$school_id,'title'=>$arr[0]));
					if(empty($check)){	
						// get credit ledger id from ledger name
						$credit_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[1]);
						if(isset($credit_ledger->id)){
							$data['credit_ledger_id']=$credit_ledger->id;
						}	

						$refund_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[3]);
						if(isset($refund_ledger->id)){
							$data['refund_ledger_id']=$refund_ledger->id;
						}

						$voucher=$this->voucher->get_voucher_by_name($school_id,$arr[2]);
						if(isset($voucher->id)){
							$data['voucher_id']=$voucher->id;
						}					
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['created_by'] = logged_in_user_id();
											
						$feetype['id'] = $this->feetype->insert('income_heads', $data);   
					}					
				}
			}
		}
		return TRUE;
	}
	private function _payment_modes(){
		$file = $_FILES['bulk_data']['name'];

       
           $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;	
					$data['name']=$arr[0];					
					$check=$this->year->get_single('payment_modes',array('school_id'=>$school_id,'name'=>$arr[0]));
					if(empty($check)){	
						// get credit ledger id from ledger name
						$ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[1]);
						if(isset($ledger->id)){
							$data['ledger_id']=$ledger->id;
						}	
					
						$data['created'] = date('Y-m-d H:i:s');
						//$data['created_by'] = logged_in_user_id();
											
						$id = $this->feetype->insert('payment_modes', $data);   
					}					
				}
			}
		}
		return TRUE;
	}
	private function _pay_groups(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name'] =$arr[0];										
					$data['group_code'] =$arr[1];										
					$check=$this->year->get_single('pay_groups',array('school_id'=>$school_id,'name'=>$arr[0]));
					if(empty($check)){													
						$id = $this->year->insert('pay_groups', $data);   
					}					
				}
			}
		}
		return TRUE;
	}
	private function _payscale_categories(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
			
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					
					$data['school_id'] =$school_id;
					$data['name'] =trim($arr[0]);					
					$data['is_deduction_type'] =$arr[2];
					$data['category_type']=$arr[3];
					$data['percentage']=$arr[4];
					$data['amount']=$arr[5];
					$check=$this->year->get_single('payscale_category',array('school_id'=>$school_id,'name'=>$arr[0]));
					if(empty($check)){	
						// paygroup
						$data['round_of_method']=$arr[10];
						$data['remove_dependancy_from_attendance']=$arr[11];
						$data['change_default_debit_ledger']=$arr[12];
						$data['unbound_payscale_category']=$arr[13];
						$data['archive']=$arr[14];
						// get credit ledger id from ledger name
						$credit_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[6]);
						if(isset($credit_ledger->id)){
							$data['credit_ledger_id']=$credit_ledger->id;
						}	
						$debit_ledger=$this->accountledgers->get_ledger_by_name($school_id,$arr[7]);
						if(isset($debit_ledger->id)){
							$data['debit_ledger_id']=$debit_ledger->id;
						}					
						$data['created'] = date('Y-m-d H:i:s');						
						$cat['id'] = $this->grade->insert('payscale_category', $data);  
					}                   				
				}
			}
		}
		return TRUE;
	}
	private function _employment_type(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name'] =$arr[0];															
					$check=$this->year->get_single('employment_types',array('school_id'=>$school_id,'name'=>$arr[0]));
					if(empty($check)){													
						$id = $this->year->insert('employment_types', $data);   
					}					
				}
			}
		}
		return TRUE;
	}
	private function _class(){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;    
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                			
				if ($count == 1) {
                    $count++;
                    continue;
                }					
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name'] =$arr[0];					
					$data['is_primary']=$arr[2];
					$check=$this->year->get_single('classes',array('school_id'=>$school_id,'name'=>$arr[0]));
					if(empty($check)){			
						
						preg_match_all('/\[(.*?)\]/', $arr[1],$matches);	
						if(isset($matches[1][0])){
							$board=$matches[1][0];							
						}
						$stream='None';
						$standard=trim(preg_replace('/\[(.*?)\]/','', $arr[1]));
						$std_arr=explode(" - ",$standard);
						if(isset($std_arr[1])){
							$stream=trim($std_arr[1]);
						}
						$level=$std_arr[0];
						if(isset($board)){
							$academic_standard=$this->year->get_single('academic_standards',array('school_id'=>$school_id,'level'=>$level,'stream'=>$stream,'board'=>$board));
						}
						else{
							$academic_standard=$this->year->get_single('academic_standards',array('school_id'=>$school_id,'level'=>$level,'stream'=>$stream));
						}
						$data['academic_standard_id']=$academic_standard->id;
						$data['pass_credit_subjects']=$arr[7];
						$data['supplementary_credits_subjects']=$arr[8];
						$data['supplementary_marks_points_percent']=$arr[9];
						$data['grace_credits_subjects']=$arr[10];
						$data['grace_marks_points_percent']=$arr[11];
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['created_by'] = logged_in_user_id();
						$data['status']=1;
						$data['required_subjects']=$arr[4];
						$data['optional_subjects']=$arr[5];
						$class_id = $this->year->insert('academic_classes', $data);   
						
						// required subjects
						/*$list_arr=explode(",",$arr[4]);						
						foreach($list_arr as $l){
							$sub=trim(preg_replace('/\<br\>/','', $l));
							if(isset($sub) && $sub!= ''){
								$sub_arr=explode("Class",$sub);
								if(isset($sub_arr[0])){
									$subject_name=trim($sub_arr[0]);
									$sub_check=$this->year->get_single('subjects',array('school_id'=>$school_id,'name'=>$subject_name,'class_id'=>$class_id));
									if(empty($sub_check)){
										// insert subject
										$sub_data=array();
										$sub_data['school_id']=$school_id;
										$sub_data['class_id']=$class_id;
										$sub_data['type']='mandatory';
										$sub_data['name']=$subject_name;
										$sub_data['created_at'] = date('Y-m-d H:i:s');
										$sub_data['created_by'] = logged_in_user_id();
										$sub_data['status']=1;
										$subject_id = $this->year->insert('subjects', $sub_data);   
									}
								}
							}							
						}
						// optional subject
						$optional_subject=str_replace("<strong>Optional 1 : </strong>","",$arr[5]);
						$list_arr=explode(",",$optional_subject);						
						foreach($list_arr as $l){
							$sub=trim(preg_replace('/\<br\>/','', $l));
							if(isset($sub) && $sub!= ''){
								$sub_arr=explode("Class",$sub);
								if(isset($sub_arr[0])){
									$subject_name=trim($sub_arr[0]);
									$sub_check=$this->year->get_single('subjects',array('school_id'=>$school_id,'name'=>$subject_name,'class_id'=>$class_id));
									if(empty($sub_check)){
										// insert subject
										$sub_data=array();
										$sub_data['school_id']=$school_id;
										$sub_data['class_id']=$class_id;
										$sub_data['type']='optional';
										$sub_data['name']=$subject_name;
										$sub_data['created_at'] = date('Y-m-d H:i:s');
										$sub_data['created_by'] = logged_in_user_id();
										$sub_data['status']=1;
										$subject_id = $this->year->insert('subjects', $sub_data);   
									}
								}
							}						
						}*/
						
					}					
				}
			}			
		}
		return TRUE;
	}
	private function _teacher($alumni=0){
		$file = $_FILES['bulk_data']['name'];

       
           $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	                			
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$data['name'] =$arr[1]." ".$arr[2];															
					$data['father_name']=$arr[3];
					$data['email']=$arr[4];
					$data['alternate_name']=$arr[6];
					$data['dob']=date('Y-m-d',strtotime($arr[7]));
					$data['gender']=strtolower($arr[8]);
					$data['reservation_category']=$arr[9];
					$data['reg_no']=$arr[10];
					$data['other_info']=$arr[5];
					$data['salary_type']='monthly';
					$data['created_at'] = date('Y-m-d H:i:s');
					$data['created_by'] = logged_in_user_id();
					$data['status']=1;
					$data['alumni']=$alumni;
					// generate teacher code
					$data['teacher_code']=$this->teacher->generate_teacher_code($school_id);
					$check=$this->year->get_single('teachers',array('school_id'=>$school_id,'reg_no'=>$arr[10]));
					
					if(empty($check)){		
						// create user	
						$user=array();
						$user['school_id']=$school_id;
						$firstname_arr=explode(" ",$arr[1]);
						$firstname=strtolower($firstname_arr[0]);
						$username=$firstname.substr($arr[10],strlen($arr[10])-3);
						$user['username']=$username;					
						$user['role_id']=TEACHER;		
						$data['user_id'] = $this->_create_user($user);						
						$teacher_id = $this->year->insert('teachers', $data); 
						if($arr[12]!=''){
											
						// teaching subjects
						$teaching_subjects=$arr[12];
						$sub_arr=explode(",",$teaching_subjects);						
						foreach($sub_arr as $a){
							
							$sub=trim(preg_replace('/\[(.*?)\]/', '', $a));	
							$s_arr=explode(" Class ",$sub);
							if(isset($s_arr[0]) && isset($s_arr[1])){
								$subject_name=trim($s_arr[0]);
								$class_name=trim($s_arr[1]);	
								if($class_name != 11 && $class_name !=12){								
									$class=$this->employee->get_single("classes",array("school_id"=>$school_id,"name"=>$class_name));
									if(!empty($class)){
										$class_id=$class->id;
									}
									else{
										// create class
										$class_insert_data=array();
										$class_insert_data['school_id']=$school_id;
										$class_insert_data['name']=$class_name;
										$class_insert_data['created_at'] = date('Y-m-d H:i:s');
										$class_insert_data['created_by'] = logged_in_user_id();
										$class_insert_data['status'] = 1;
										$class_id=$this->employee->insert('classes', $class_insert_data);
									}
									// check for subject
									$detail=$this->employee->get_single("subjects",array("school_id"=>$school_id,"name"=>$subject_name,"class_id"=>$class_id));
									if(!empty($detail)){
										// update teacher id
										$subject=array();
										$subject['teacher_id']=$teacher_id;
										$subject_id=$this->employee->update('subjects', $subject,array("id"=>$detail->id));
									}
									else{
										// insert subject
										$subject=array();
										$subject['school_id']=$school_id;
										$subject['class_id']=$class_id;
										$subject['teacher_id']=$teacher_id;
										$subject['name']=$subject_name;
										$subject['created_at'] = date('Y-m-d H:i:s');
										$subject['created_by'] = logged_in_user_id();
										$subject['status'] = 1;
										$subject_id=$this->employee->insert('subjects', $subject);
									}
								}
							}
							
						}						
					}						
					}					
				}
			}
		}
		$success_msg='';
			
				$success_msg .= "Data inserted successfully. ";
			
			
			success($success_msg);
		
		return TRUE;
	}	
	private function _create_user($user){
        
		$password=substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 6 );
        $data = array();
        $data['role_id']    = $user['role_id'];
        $data['school_id']    = $user['school_id'];
        $data['password']   = md5($password);
        $data['temp_password'] = base64_encode($password);		
        $data['username']      = $user['username'];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['status']     = 1; // by default would not be able to login
        $this->employee->insert('users', $data);
        return $this->db->insert_id();
    }	
	private function _employee($alumni=0){
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	
			
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$check=$this->year->get_single('employees',array('school_id'=>$school_id,'reg_no'=>$arr[9]));
					if(empty($check)){	
						// check if teacher
						$teacher_check=$this->year->get_single('teachers',array('school_id'=>$school_id,'reg_no'=>$arr[9]));
						if(!empty($teacher_check)){	
							// update its payscale
							$teacher=array();
							$teacher['basic_salary']=$arr[15];
							$updated = $this->employee->update('teachers', $teacher, array('id' => $teacher_check->id));
							$user_id=$teacher_check->user_id;
						}
						else{
							$data['name'] =$arr[1]." ".$arr[2];					
							$data['dob']=date('Y-m-d',strtotime($arr[6]));
							$data['gender']=strtolower($arr[7]);
							$data['email']=$arr[10];
							$data['phone']=$arr[11];
							$data['salary_type']='monthly';	
							$data['alumni']=$alumni;
							$data['basic_salary']=$arr[15];											
							$data['reg_no']=$arr[9];
							$data['father_name']=$arr[3];
							$data['alternate_name']=$arr[5];
							$data['reservation_category']=$arr[8];
							$data['qualification']=$arr[12];
							$data['adhar_no']=$arr[14];
							$data['pf_no']=$arr[17];
							$data['uan_no']=$arr[18];
							$data['employee_code']=$this->employee->generate_employee_code($school_id);
							
							// create user						
							$user['school_id']=$school_id;
							$firstname_arr=explode(" ",$arr[1]);
							$firstname=strtolower($firstname_arr[0]);
							$username=$firstname.substr($arr[9],strlen($arr[9])-3);
							$user['username']=$username;		
							$user['role_id']=STAFF;
							$data['user_id'] = $this->_create_user($user);  
													
							$data['status']=1;						
							$data['created_at'] = date('Y-m-d H:i:s');
							$data['created_by'] = logged_in_user_id();
							
							$employee_id = $this->employee->insert('employees', $data); 
							// employment type
							$type_arr=explode(",",$arr[4]);
							foreach($type_arr as $t){
								$type_name=trim($t);
								$emp_type=$this->year->get_single('employment_types',array('school_id'=>$school_id,'name'=>$type_name));
								if(!empty($emp_type)){
									$emp_type_arr=array();
									$emp_type_arr['employee_id']=$employee_id;
									$emp_type_arr['employment_type_id']=$emp_type->id;
									$employee_type_id = $this->employee->insert('employee_employment_types', $emp_type_arr); 
								}
							}
							$user_id=$data['user_id'];
						}
						// payscale category detail
						// salary grade
						if($arr[16]!=''){
							$cats=explode(",",$arr[16]);							
							foreach($cats as $c){
								$name=trim($c);
								$name = trim(preg_replace('/\[(.*?)\]/', '', $name));								
								$grade=$this->grade->get_single_grade_by_name($school_id,$name);
								if(!empty($grade)){
									$in_arr=array();
									$in_arr['user_id']=$user_id;
									$in_arr['payscalecategory_id']=$grade->id;
									$this->employee->insert('user_payscalecategories', $in_arr);
								}
							}					
						}
					}					
				}
			}
		}
		$success_msg='';
			
				$success_msg .= "Data inserted successfully. ";
			
			
			success($success_msg);
		return TRUE;
	}
	/*private function _employee($alumni=0){   // FROM GOVERMENT SITE
		$destination = 'assets/csv/bulk_uploaded_data.csv';
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {			
				if ($count == 1 || $count == 2) {
                    $count++;
                    continue;
                }					
				if ($arr[0] != '') {					                				
                    $data = array();                  
                    $data['school_id'] =$school_id;
					$check=$this->year->get_single('employees',array('school_id'=>$school_id,'phone'=>$arr[15]));
					if(empty($check)){	
						// check if teacher
						$teacher_check=$this->year->get_single('teachers',array('school_id'=>$school_id,'phone'=>$arr[15]));
						if(!empty($teacher_check)){	
							// update its payscale
							/*$teacher=array();
							$teacher['basic_salary']=$arr[15];
							$updated = $this->employee->update('teachers', $teacher, array('id' => $teacher_check->id));
							$user_id=$teacher_check->user_id;*/
	/*					}
						else{
							if($arr[1]== 'Teaching'){
								// insert into teacher
								$data['responsibility']=trim($arr[2]);
								$data['adhar_no']=trim($arr[3]);
								$data['name'] =trim($arr[4]);	
								$data['father_name']=trim($arr[5]);	
								$data['dob']=date('Y-m-d',strtotime(trim($arr[6])));								
								$data['gender']=strtolower(trim($arr[7]));
								$data['reservation_category']=trim($arr[8]);
								$data['qualification']=trim($arr[9]); //
								$data['pacific_ability']=$arr[10]; 
								$data['rtet_qualified']=$arr[11]; 
								$data['joining_date']=date('Y-m-d',strtotime(trim($arr[12])));
								$data['secondary_roll_no']=$arr[13];
								$data['secondary_year']=$arr[14]; 
								$data['phone']=$arr[15]; 
								$data['current_subject']=$arr[16]; 
								$data['salary_type']='monthly';	
								$data['teacher_code']=$this->teacher->generate_teacher_code($school_id);
								//$data['employee_code']=$this->employee->generate_employee_code($school_id);
								
								// create user						
								$user['school_id']=$school_id;
								$firstname_arr=explode(" ",$arr[4]);
								$firstname=strtolower($firstname_arr[0]);
								$username=$firstname.substr($arr[15],strlen($arr[15])-3)."_".substr(uniqid(),9);
								$user['username']=$username;		
								$user['role_id']=TEACHER;
								$data['user_id'] = $this->_create_user($user);  
														
								$data['status']=1;						
								$data['created_at'] = date('Y-m-d H:i:s');
								$data['created_by'] = logged_in_user_id();
								
								$teacher_id = $this->employee->insert('teachers', $data); 
							}
							else{
								// // insert into employee
									// EMployment types
									$emp_type=trim($arr[2]);
									$etype_check=$this->year->get_single('employment_types',array('school_id'=>$school_id,'name'=>$emp_type));
									if(empty($etype_check)){
										$tarr=array();
										$tarr['school_id']=$school_id;
										$tarr['name']=$emp_type;
										$employment_type_id = $this->employee->insert('employment_types', $tarr); 
										
									}
									else{
										$employment_type_id=$etype_check->id;
									}
																										
								$data['adhar_no']=trim($arr[3]);
								$data['name'] =trim($arr[4]);	
								$data['father_name']=trim($arr[5]);	
								$data['dob']=date('Y-m-d',strtotime(trim($arr[6])));								
								$data['gender']=strtolower(trim($arr[7]));
								$data['reservation_category']=trim($arr[8]);
								$data['qualification']=trim($arr[9]);
								$data['pacific_ability']=$arr[10]; 
								$data['rtet_qualified']=$arr[11]; 
								$data['joining_date']=date('Y-m-d',strtotime(trim($arr[12])));
								$data['secondary_roll_no']=$arr[13];
								$data['secondary_year']=$arr[14]; 
								$data['phone']=$arr[15]; 
								$data['current_subject']=$arr[16]; 
								$data['salary_type']='monthly';	
								$data['employee_code']=$this->employee->generate_employee_code($school_id);
								
								// create user						
								$user['school_id']=$school_id;
								$firstname_arr=explode(" ",$arr[4]);
								$firstname=strtolower($firstname_arr[0]);
								$username=$firstname.substr($arr[15],strlen($arr[15])-3)."_".substr(uniqid(),9);
								$user['username']=$username;		
								$user['role_id']=STAFF;
								$data['user_id'] = $this->_create_user($user);  
														
								$data['status']=1;						
								$data['created_at'] = date('Y-m-d H:i:s');
								$data['created_by'] = logged_in_user_id();
								
								$employee_id = $this->employee->insert('employees', $data); 
								
								//insert into employee-employment types
										$type_arr=array();
										$type_arr['employee_id']=$employee_id;
										$type_arr['employment_type_id']=$employment_type_id;
										$employee_emp_type_id = $this->employee->insert('employee_employment_types', $type_arr); 
										
							}							
							
						}
						
					}					
				}
			}
		}
		return TRUE;
	}*/
	/*private function _student(){
		$destination = 'assets/csv/bulk_uploaded_data.csv';
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     

            while (($arr = fgetcsv($handle)) !== false) {	  	
				if ($count == 1) {
                    $count++;
                    continue;
                }	
				
				if ($arr[1] != '') {					                				
					$data = array();
                    $enroll = array();
                    $user = array();
					$guardian_user=array();
					
					

                    $data['school_id'] =$school_id;
					 $data['admission_date'] = date('Y-m-d',strtotime($arr[19]));
					$name=$arr[1];
					if(isset($arr[2])){
						$name.=" ".$arr[2];
					}
                    $data['name'] = $name;
					$data['admission_no'] = isset($arr[13]) ? $arr[13] : '';
					$data['registration_no'] = isset($arr[13]) ? $arr[13] : '';
					// generate teacher code
					
					$check=$this->year->get_single('students',array('school_id'=>$school_id,'registration_no'=>$arr[13]));
					if(empty($check)){	
						$data['student_code']=$this->student->generate_student_code($school_id);					
						$data['father_name']=$arr[3];
						$data['mother_name']=$arr[4];
						$data['father_profession']=$arr[24];
						$data['alternate_name']=$arr[7];
						$data['dob'] = isset($arr[8]) ? date('Y-m-d', strtotime($arr[8])) : '';
						$data['gender'] = isset($arr[9]) ? strtolower($arr[9]) : '';
						$data['phone'] = isset($arr[18]) ? $arr[18] : '';
						$data['email'] = '';                
					//    $data['group'] = isset($arr[13]) ? $arr[13] : '';
						$data['blood_group'] = '';
						$data['religion'] = isset($arr[21]) ? $arr[21] : '';
						$data['caste'] = isset($arr[23]) ? $arr[23] : '';
						$data['health_condition'] = isset($arr[10]) ? $arr[10] : '';
						$data['reservation_category'] = isset($arr[11]) ? $arr[11] : '';
						$data['bpl'] = isset($arr[12]) ? $arr[12] : '';
						$data['adhar_no'] = isset($arr[14]) ? $arr[14] : '';
						$data['sm_id'] = isset($arr[15]) ? $arr[15] : '';
						$data['family_id'] = isset($arr[16]) ? $arr[16] : '';
						$data['previous_school'] = isset($arr[20]) ? $arr[20] : '';
						$data['reason_of_seperation']=$arr[25];
						$data['date_of_seperation']=($arr[26]!= '') ? date('Y-m-d', strtotime($arr[26])) : '';
						$present_address= $arr[27];
						if(isset($arr[28]) && $arr[28]!= ''){
								$present_address.= ", ".$arr[28];
						}
						if(isset($arr[29]) && $arr[29]!= ''){
								$present_address.= ", ".$arr[29];
						}
						if(isset($arr[30]) && $arr[30]!= ''){
								$present_address.= ", ".$arr[30];
						}
						if(isset($arr[31]) && $arr[31]!= ''){
								$present_address.= ", ".$arr[31];
						}
						if(isset($arr[32]) && $arr[32]!= ''){
								$present_address.= ", ".$arr[32];
						}
						if(isset($arr[33]) && $arr[33]!= ''){
								$present_address.= "- ".$arr[33];
						}
						if(isset($arr[34]) && $arr[34]!= ''){
								$present_address.= ",Phone: ".$arr[34];
						}
						// PERMANENT ADDRESS
						$permanent_address= $arr[35];
						if(isset($arr[36]) && $arr[36]!= ''){
								$permanent_address.= ", ".$arr[36];
						}
						if(isset($arr[37]) && $arr[37]!= ''){
								$permanent_address.= ", ".$arr[37];
						}
						if(isset($arr[38]) && $arr[38]!= ''){
								$permanent_address.= ", ".$arr[38];
						}
						if(isset($arr[39]) && $arr[39]!= ''){
								$permanent_address.= ", ".$arr[39];
						}
						if(isset($arr[40]) && $arr[40]!= ''){
								$permanent_address.= ", ".$arr[40];
						}
						if(isset($arr[41]) && $arr[41]!= ''){
								$permanent_address.= "- ".$arr[41];
						}
						if(isset($arr[42]) && $arr[42]!= ''){
								$permanent_address.= ",Phone: ".$arr[42];
						}
						$data['present_address']=$present_address;
						$data['permanent_address']=$permanent_address;            
						$data['second_language'] = '';
						$data['bank_details']=$arr[43];
						$data['other_info']=$arr[44];
					
						// add to guardian
						$guardian_user['school_id']=$school_id;
						$firstname_arr=explode(" ",$arr[3]);
						$firstname=strtolower($firstname_arr[0]);
						$username=$firstname.substr($arr[13],strlen($arr[13])-3);
						$guardian_user['username']=$username;
						$guardian_user['role_id']=GUARDIAN;
						
						$guardian_data['user_id'] = $this->_create_user($guardian_user);                
						$guardian_data['school_id']=$school_id;
						$guardian_data['name']=$arr[3];
						$guardian_data['phone']=$arr[18];
						$guardian_data['profession']=$arr[24];
						 $guardian_data['created_at'] = date('Y-m-d H:i:s');
						$guardian_data['created_by'] = logged_in_user_id();
						$guardian_data['status'] = 1;
						$data['guardian_id']=$this->student->insert('guardians', $guardian_data);
						if(isset($data['guardian_id'])){
						$data['relation_with'] = 'father';}
						$data['national_id'] = isset($arr[14]) ? $arr[14] : '';
						$data['age'] = $data['dob'] ? floor((time() - strtotime($data['dob'])) / 31556926) : 0;
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['created_by'] = logged_in_user_id();
						$data['status'] = 1;
						
						// user for student
						$user['school_id'] = $school_id;

						// generate username
						$firstname_arr=explode(" ",$arr[1]);
						$firstname=strtolower($firstname_arr[0]);
						$username=$firstname.substr($arr[13],strlen($arr[13])-3);					
						$user['username']=$username;
						$user['role_id']=STUDENT;
						// first need to create user
						$data['user_id'] = $this->_create_user($user);
					
						$enroll['roll_no'] = isset($arr[6]) ? $arr[6] : '';
							// now need to create enroll								
						$s_arr=explode(' - ',$arr[5]);
						$first=trim($s_arr[0]);
						$second=trim($s_arr[1]);
						preg_match_all('/\[(.*?)\]/',$first,$matches);					
						$stream='';
						if(isset($matches[1][0])){
							//$data['group']
							$stream=trim($matches[1][0]);
						}
						// now need to create student
						$enroll['student_id'] = $this->student->insert('students', $data);
					$first_part=trim(preg_replace('/\[(.*?)\]/', '', $first));					
					if(isset($first_part)){
						$s1_arr=explode("_",$first_part);
						if(isset($s1_arr[0])){
							$class=trim($s1_arr[0]);							
							// check if class present - otherwise add
							if(($class == 11 || $class==12) && $stream != ''){
								$class_name=$class."-".$stream;
							}
							else{
								$class_name=$class;
							}
							$class_data=$this->classes->get_class_id($school_id,$class_name);
							if(!empty($class_data)){
								$enroll['class_id']=$class_data->id;
							}
							else{
								$class_insert_data=array();
								$class_insert_data['school_id']=$school_id;
								$class_insert_data['name']=$class_name;								
								$class_insert_data['stream']=$stream;
								$class_insert_data['created_at'] = date('Y-m-d H:i:s');
								$class_insert_data['created_by'] = logged_in_user_id();
								$class_insert_data['status'] = 1;
								$this->student->insert('classes', $class_insert_data);
								$enroll['class_id']=$this->db->insert_id();
							}
							
						}
						if(isset($s1_arr[1])){
							$section=trim($s1_arr[1]);							
							// check if class present - otherwise add
							$section_data=$this->section->get_section_id($school_id,$enroll['class_id'],$section);
							if(!empty($section_data)){
								$enroll['section_id']=$section_data->id;
							}
							else{
								$section_insert_data=array();
								$section_insert_data['school_id']=$school_id;
								$section_insert_data['class_id']=$enroll['class_id'];
								$section_insert_data['name']=$section;
								$section_insert_data['created_at'] = date('Y-m-d H:i:s');
								$section_insert_data['created_by'] = logged_in_user_id();
								$section_insert_data['status'] = 1;
								$this->student->insert('sections', $section_insert_data);
								$enroll['section_id']=$this->db->insert_id();
							}
						}
					}
					/*$arr1=$s_arr;					
					unset($arr1[0]);					
					$mystring=implode(" ",$arr1);*/					
	/*				preg_match_all("/\\[(.*?)\\]/", $second, $matches); 					
					//preg_match_all("/\\[(.*?)\\]/", $second, $matches); 					
					$index=count($matches)-1;
					$ay_year=$matches[$index][0];										
					$a_year="";
					$start_year="";
					$end_year="";
					if(isset($ay_year) && $ay_year!= ''){
						//$a_year=str_replace("[","",$s_arr[3]);
						//$a_year=str_replace("]","",$a_year);
						$year=explode("-",$ay_year);
						if(isset($year[0])){
							$start_year=$year[0];
						}
						if(isset($year[1])){
							$end_year=$year[1];
						}
					}	
		
					if($start_year!='' && $end_year!=''){
						$session_year=$start_year."-".$end_year;
						$year_data=$this->year->get_year_id($school_id,$start_year,$end_year);
							if(!empty($year_data)){
								$enroll['academic_year_id']=$year_data->id;
							}
							else{
								$year_insert_data=array();
								$year_insert_data['school_id']=$school_id;
								$year_insert_data['session_year']=$session_year;
								$year_insert_data['start_year']=$start_year;
								$year_insert_data['end_year']=$end_year;
								$year_insert_data['is_running']=0;
								$year_insert_data['created_at'] = date('Y-m-d H:i:s');
								$year_insert_data['created_by'] = logged_in_user_id();
								$year_insert_data['status'] = 1;
								$this->student->insert('academic_years', $year_insert_data);
								$enroll['academic_year_id']=$this->db->insert_id();
							}
                   // $enroll['academic_year_id'] = $school->academic_year_id;
						$this->_insert_enrollment($enroll);
					}
					}					
				}
			}
		}
		return TRUE;
	}*/
	private function _student(){
		$success_count=0;
		$duplicate_count=0;
		$file = $_FILES['bulk_data']['name'];

       
            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
		if (($handle = fopen($destination, "r")) !== FALSE) {

            $count = 1;            
            $school_id  = $this->input->post('school_id');                     
			$school = $this->student->get_school_by_id($school_id);
			if($school->academic_year_id ==''){
				error('Please set academic year for the school');
				 redirect('import/csv');
			}
			else{
            while (($arr = fgetcsv($handle)) !== false) {				
				if ($count == 1) {
                    $count++;
                    continue;
                }


				if ($arr[1] != '') {					                				
					$data = array();
                    $enroll = array();
                    $user = array();
					$guardian_user=array();
					
                    $data['school_id'] =$school_id;
					
					$name=$arr[1];					
                    $data['name'] = $name;										
					
					$check=$this->year->get_single('students',array('school_id'=>$school_id,'admission_no'=>$arr[12]));
				    if(empty($check)){	
                        $data['student_code']=$this->student->generate_student_code($school_id);					
						$data['father_name']=$arr[2];
						$data['mother_name']=$arr[3];	
// 						$dt_arr=explode('-',trim($arr[4]));
// $d=$dt_arr[0];
// $m=$dt_arr[1];
// $y=$dt_arr[2];
// $date_str= $m."/".$d."/".$y;

						$dob_formatted	= isset($arr[4]) ? date_format_converter($arr[4]) : "";
						$data['dob'] = $dob_formatted ? date('Y-m-d', strtotime($dob_formatted)) : '';						
						$data['gender'] = isset($arr[5]) ? strtolower($arr[5]) : '';
						$data['phone'] = isset($arr[38]) ? $arr[38] : '';
						$data['email'] = isset($arr[39]) ? $arr[39] : '';                					
						$data['religion'] = isset($arr[7]) ? $arr[7] : '';
						$data['caste'] = isset($arr[6]) ? $arr[6] : '';
						$data['second_language'] = isset($arr[8]) ? $arr[8] : '';
						$data['rural_urban'] = isset($arr[9]) ? $arr[9] : '';
						$data['present_address']=isset($arr[10]) ? $arr[10] : '';
						if(trim($arr[11])!= ''){
							$dt_arr=explode('-',trim($arr[11]));
// $d=$dt_arr[0];
// $m=$dt_arr[1];
// $y=$dt_arr[2];
// $date_str= $d."/".$m."/".$y;
						$admission_date_formatted = isset($arr[11]) ? date_format_converter($arr[11]) : "";
						//$admission_date_formatted = $arr[11];
						$data['admission_date'] = $arr[11] ? date('Y-m-d',strtotime($admission_date_formatted)) : "";
						}
						$data['admission_no']=isset($arr[12]) ? $arr[12] : '';
						$data['bpl'] = isset($arr[13]) ? $arr[13] : '';
						$data['physical_disability']=isset($arr[14]) ? $arr[14] : '';
						$data['free_education']=isset($arr[15]) ? $arr[15] : '';
						
						$data['previous_class']=isset($arr[17]) ? $arr[17] : '';
						$data['previous_school']=isset($arr[18]) ? $arr[18] : '';
						$data['previous_year_attended_days']=isset($arr[19]) ? $arr[19] : '';
						$data['medium_of_instruction']=isset($arr[20]) ? $arr[20] : '';
						$data['health_condition'] = isset($arr[21]) ? $arr[21] : '';
						$data['facilities_by_cwsn'] = isset($arr[22]) ? $arr[22] : '';
						$data['no_of_uniform_sets'] = isset($arr[23]) ? $arr[23] : '';
						$data['free_text_books'] = isset($arr[24]) ? $arr[24] : '';
						$data['free_transport'] = isset($arr[25]) ? $arr[25] : '';
						$data['free_escort'] = isset($arr[26]) ? $arr[26] : '';
						$data['mdm_benificiary'] = isset($arr[27]) ? $arr[27] : '';
						$data['free_hostel'] = isset($arr[28]) ? $arr[28] : '';
						$data['special_training'] = isset($arr[29]) ? $arr[29] : '';
						$data['appeared_in_last_exam'] = isset($arr[30]) ? $arr[30] : '';
						$data['last_exam_passed'] = isset($arr[31]) ? $arr[31] : '';
						$data['last_exam_marks'] = isset($arr[32]) ? $arr[32] : '';						
						$data['trade_sector'] = isset($arr[34]) ? $arr[34] : '';
						$data['iron_folic_acid_tablets'] = isset($arr[35]) ? $arr[35] : '';
						$data['deworming_tablets'] = isset($arr[36]) ? $arr[36] : '';
						$data['vitamin_a_supplement'] = isset($arr[37]) ? $arr[37] : '';											
						// add to guardian
						$guardian_user['school_id']=$school_id;
						$firstname_arr=explode(" ",$arr[2]);
						$firstname=strtolower($firstname_arr[0]);
						$username=$firstname.substr($arr[12],strlen($arr[12])-3)."_".substr(uniqid(),9);
						$guardian_user['username']=$username;
						$guardian_user['role_id']=GUARDIAN;
						
						$guardian_data['user_id'] = $this->_create_user($guardian_user);                
						$guardian_data['school_id']=$school_id;
						$guardian_data['name']=$arr[2];						
						 $guardian_data['created_at'] = date('Y-m-d H:i:s');
						$guardian_data['created_by'] = logged_in_user_id();
						$guardian_data['status'] = 1;
						$data['guardian_id']=$this->student->insert('guardians', $guardian_data);
						if(isset($data['guardian_id'])){
						$data['relation_with'] = 'father';}					
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['created_by'] = logged_in_user_id();
						$data['status'] = 1;
						
						// user for student
						$user['school_id'] = $school_id;

						// generate username
						$firstname_arr=explode(" ",$arr[1]);
						$firstname=strtolower($firstname_arr[0]);
						$username=$firstname.substr($arr[12],strlen($arr[12])-3)."_".substr(uniqid(),9);					
						$user['username']=$username;
						$user['role_id']=STUDENT;
						// first need to create user
						$data['user_id'] = $this->_create_user($user);
						// now need to create student
						$enroll['student_id'] = $this->student->insert('students', $data);
						$class=trim($arr[16]);
						$stream='';
						$discipline_id=NULL;
						$stream_rec=trim($arr[33]);
						if($stream_rec!='' && $stream_rec!='Not Applicable'){
							$stream=$stream_rec;
							$class_name=$class." ".$arr[33];
							//$class_name=$class;
							// add discipline
							$discipline_data=$this->discipline->get_discipline_by_name($school_id,$stream);
							if(!empty($discipline_data)){
								$discipline_id=$discipline_data->id;
							}
							
						}
						else{
							$class_name=$class;
						}
						if((strtolower($class) == 'eleventh' || strtolower($class)== 'twelth') && $stream==''){
								// make it arts
								$class_name=$class." Arts";
						}
						if(strtolower($class) == strtolower('PP.3+')){
							$class_name = 'Arun';
						}
						if(strtolower($class) == strtolower('PP.4+')){
							$class_name = 'Uday';
						}
						if(strtolower($class) == strtolower('PP.5+')){
							$class_name = 'Prabhat';
						}
						
						$class_data=$this->classes->get_class_id($school_id,$class_name);
							if(!empty($class_data)){
								$enroll['class_id']=$class_data->id;
								// get default section of this class
								$section_rec=$this->year->get_single('sections',array('class_id'=>$enroll['class_id'],'name'=>'A'));
								if(!empty($section_rec)){
									$enroll['section_id']=$section_rec->id;
								}
							}
							else{
								$class_insert_data=array();
								$class_insert_data['school_id']=$school_id;
								$class_insert_data['name']=$class_name;								
								//$class_insert_data['stream']=$stream;
								$class_insert_data['disciplines']=$discipline_id;								
								$class_insert_data['created_at'] = date('Y-m-d H:i:s');
								$class_insert_data['created_by'] = logged_in_user_id();
								$class_insert_data['status'] = 1;
								$this->student->insert('classes', $class_insert_data);
								$enroll['class_id']=$this->db->insert_id();
								
								// create default section also
								$section_data=array();
								$section_data['school_id']=$school_id;
								$section_data['class_id']=$enroll['class_id'];
								$section_data['name']='A';
								$section_data['note']='Default Section';
								$section_data['created_at'] = date('Y-m-d H:i:s');
								$section_data['created_by'] = logged_in_user_id();
								$section_data['status'] = 1;
								$this->student->insert('sections', $section_data);
								$enroll['section_id']=$this->db->insert_id();								
							}							
							$enroll['academic_year_id'] = $school->academic_year_id;
							$this->_insert_enrollment($enroll);	
							$success_count++;
					}
else{
$duplicate_count++;	
}					
				}
			}
			}
		}
		if($success_count>0){
			$success_msg=$success_count." data inserted successfully. ";
			if($duplicate_count >0){
				$success_msg .= $duplicate_count." duplicate records found.";
			}
			success($success_count." data inserted successfully. ");
		}
		else{
			error("Insert failed.");
		}
		
		return TRUE;
	}
    private function _upload_file() {

        $file = $_FILES['bulk_data']['name'];

        if ($file != "") {

            $destination = $_SERVER['DOCUMENT_ROOT'].'\assets\csv\/'.$file;   
				
            $ext = strtolower(end(explode('.', $file)));
            if ($ext == 'csv') {                 
                if(!move_uploaded_file($_FILES['bulk_data']['tmp_name'], $destination)){	
					
					error('Problem in uploading file.');
					redirect('import/csv');
				}
					
            }
			else{
				error('Upload CSV file only');
				redirect('import/csv');
			}
        } else {
            error($this->lang->line('insert_failed'));
            redirect('import/csv');
        }       
    }
	 private function _insert_enrollment($enroll) {
        
        $data = array();
        $data['student_id'] = $enroll['student_id'];
        $data['school_id']   = $this->input->post('school_id');
        $data['class_id']   = $enroll['class_id'];
        $data['section_id'] = $enroll['section_id'];        
        if($data['student_id'])
		{
			$this->__invoice_creation($data) ;
		}
        $data['academic_year_id'] = $enroll['academic_year_id'];
       
        
        $data['roll_no'] = $enroll['roll_no'];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['status'] = 1;
        $this->student->insert('enrollments', $data);
    }
   
     /*****************Function __invoice_creation**********************************
    * @type            : Function
    * @function name   : __invoice_creation
    * @description     : Invoice creation for student                  
    * @param           : $data array value
    * @return          : null 
    * ********************************************************** */
    private function __invoice_creation($data) {
        $school = $this->student->get_school_by_id($data['school_id']);
        if(!empty($school ))
        {
            $school_id      = $school->id;
            $student_id     = $data['student_id'];
            $financial_year = $school->financial_year_id;
			$class_id       = $data['class_id'];
            $income_head    = $this->student->check_general_fee($school_id ,$financial_year); 
            $fee_amount 	= $this->student->get_single_amount($school_id,$class_id, $income_head->id); 
			$discount_id	= isset($data['discount_id']) && $data['discount_id'] ? $data['discount_id'] : 0;
            if(!empty($income_head) && !empty($fee_amount))
            {
                $fee_amount = $fee_amount->fee_amount;
                $invoice_no = $this->student->generate_invoice_no($school_id); 
                $invoice_data = array("invoice_type"=>'fee',"school_id" =>$school_id,"class_id" => $class_id ,"student_id" =>$student_id,"month" => date("d-m-Y"),'paid_status' =>'unpaid','date'=>date('Y-m-d')
                                ,'status'=>1,'created_at'=>date('Y-m-d H:i:s'),'academic_year_id' =>$school->academic_year_id,'created_by' => logged_in_user_id());
                $invoice_data['income_head_id'] = $income_head->id;
                $invoice_data['invoice_type']   = $income_head->head_type;
                $invoice_data['custom_invoice_id'] = $this->student->get_custom_id('invoices', 'INV');
                $discount = $this->student->get_discount($discount_id);
                $invoice_data['discount'] = 0.00;
                $invoice_data['is_applicable_discount'] =0;
                $invoice_data['note'] = "admission";
                $fee_amount_after_discount  = $fee_amount;
                if ($discount_id && !empty($discount)) {
                    if($discount->type != "amount")
                    {
                        $invoice_data['discount']  = $discount->amount * $fee_amount / 100;
                    }
                    else $invoice_data['discount']  = $discount->amount;
                    $invoice_data['discount'] = $invoice_data['discount'];
                    $invoice_data['is_applicable_discount'] =1;
                    $fee_amount_after_discount = $fee_amount - $invoice_data['discount'];
                }
                //class_fee = 10000
                //discount  = 1000
                //fee_amount_after_discount = 900
                // net_amount = 9000
                //   gross_amount = 10000
                // due_amount = 900
                $invoice_data['due_amount']     = $fee_amount_after_discount;	
                $invoice_data['net_amount']     = $fee_amount_after_discount;
                $invoice_data['gross_amount']   =  $fee_amount;

                $insert_id = $this->student->insert('invoices', $invoice_data);

                $sql = "SELECT * FROM  emi_fee  WHERE emi_fee.income_heads_id = '{$income_head->id}'";
                $data1 =  $this->db->query($sql)->result();
                if (!empty($data1)) {
                    unset($invoice_data['discount'] );
                    foreach ($data1 as $obj) {
                        $invoice_data['emi_type']     = "";
                        if(strtotime($obj->emi_start_date) < time())
                        {
                            $invoice_data['custom_invoice_id'] = $this->student->get_custom_id('invoices', 'INV');
                            $invoice_data['emi_type']       = $obj->id;
                            $installment = $fee_amount*$obj->emi_per/100;
                            $invoice_data['gross_amount']   =  $installment;
                            $invoice_data['net_amount']     =  $installment;					
                            $invoice_data['due_amount']     = $installment;	
                            $insert_id = $this->student->insert('invoices', $invoice_data);

                        }
                       
                    }
                }
            }
        }

    }
    
  
}