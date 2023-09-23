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
        $calculated = 0;
        $this->data['todayDate']= $todayDate = date("d-m-Y");
        $school_id  = $this->input->post('school_id');

         if ($_POST) {
            
            
           
            $payment_to  = $this->input->post('payment_to');
            $user_id  = $this->input->post('user_id');
            $work  = $this->input->post('working_days');
            $calculate  = $this->input->post('calculated');
			$leave_days  = $this->input->post('leave_days');
            $debit_ledger_id  = $this->input->post('debit_ledger_id');
            $credit_ledger_id  = $this->input->post('credit_ledger_id');
			$salary_month=$this->input->post('salary_month');
            $payment_date = date("Y-m-d"); //$this->input->post('payment_date');
            $absent_days =$this->input->post('absent_days');
            $this->_prepare__bulk_payment_validation();
            if (!$this->form_validation->run()) {
                error("Invalid Payment date");
                redirect('payroll/payment/index/');
            }
            if(!$payment_date)
            {
                $payment_date = date("Y-m-d",time());
            }
            
            $this->data['payment_date'] = $payment_date;
            $this->data['school_id'] = $school_id;
            $this->data['payment_to'] = $payment_to;			
            $this->data['user_id'] = $user_id;
            $this->data['absent_days'] = $absent_days;
            $school = $this->payment->get_school_by_id($school_id);
            $date_array = explode("-",$salary_month);
            $year = $date_array['1'];
            $month = $date_array['0'];
            $condition = array(              
                'school_id'=>$school_id,
                 'month'=>$month,
                 'year'=>$year,
                 'academic_year_id'=>$school->academic_year_id
            );
            
           
			$this->data['payment_status'] = $this->input->post('payment_status');
                $this->data['salary_month']  = $this->input->post('salary_month');
                $this->data['work'] = $work;
				$this->data['leave_days'] = $leave_days;
                $this->data['debit_ledger_id'] = $debit_ledger_id;
                $this->data['credit_ledger_id'] =  $credit_ledger_id;
            
            
            if($user_id == ""){  
               
                $this->data['bulk'] = $this->payment->get_all_payment_user($this->input->post('payment_to'),$school_id );
                $tot_earnings=array();
				$tot_deduction=array();
                if (isset($this->data['bulk']) && count($this->data['bulk']) > 0) {  
                    foreach($this->data['bulk'] as $value){
                       
						
                        $check_month = $this->payment->check_month1($value->user_id);
                                            
                        if (isset($check_month) && count($check_month) > 0) {
                            foreach($check_month as $value){
                            
                            $date =  $value->salary_month ;  
                           
                            if($date ==  $this->input->post('salary_month')){
                                $ak1[] = $value->user_id;
                                $this->data['ak'] =$ak1;
                                
                            }  
                            }
                        }
						$this->data['user']    = $this->payment->get_single('users', array('id' =>$value->user_id ));
						
						//$this->data['user']    = $this->payment->get_single('users', array('id' => $user_id));      
                            
                            
                          
                            $this->data['payment'] = $this->payment->get_single_payment_user($this->data['user']->role_id, $value->user_id); 
                        
                            if($this->data['user']->role_id == TEACHER)
                            {
                                $condition['teacher_id'] = $this->data['payment']->id;
                                $condition_teacher = $condition;
                                unset($condition_teacher['employee_id']);
                                $attendance_month = (array)$this->payment->get_single('teacher_attendances', $condition_teacher);
                                //$employee_attendance = get_employee_attendance($obj->id, $school_id, $school->academic_year_id, $year, $month, $day);
                            }
                            else 
                            {
                                $condition['employee_id'] = $this->data['payment']->id;
                                $condition_employee = $condition;
                                unset($condition_employee['teacher_id']);
                                $attendance_month = (array)$this->payment->get_single('employee_attendances', $condition_employee);
                            }
                          
                            $approved_leaves = $this->payment->get_approved_paid_leaves($school_id,$value->user_id, $this->data['user']->role_id, $school->academic_year_id,$salary_month);
                           
                            $paid_leaves = 0;
                            foreach($approved_leaves  as $approved_leave)
                            {
                                $leave_start = $approved_leave->leave_from;
                                $leave_end  = $approved_leave->leave_to;
                                $begin = new DateTime($leave_start);
                                $end = new DateTime($leave_end);

                                $interval = DateInterval::createFromDateString('1 day');
                                $period = new DatePeriod($begin, $interval, $end);
                                while(strtotime($leave_start) <= strtotime($leave_end))
                                {
                                    $paid_leaves++;
                                    $leave_start= date("Y-m-d",strtotime("+1 day",strtotime($leave_start)));
                                }
                            } 
                           
                            $leave_days_array = !empty($attendance_month) ? array_keys($attendance_month,"L") : array();
                            $abcence_days_array = !empty($attendance_month) ? array_keys($attendance_month,"A") : array();
                            $present_days_array = !empty($attendance_month) ? array_keys($attendance_month,"P") : array();

                            $abcence_days_count = count($abcence_days_array);
                            $leave_days_count = count($leave_days_array);
                            $present_days_count = count($present_days_array);
                           
                            $leave_days= ($abcence_days_count+$leave_days_count)-$paid_leaves;
                            // var_dump($leave_days_count,$abcence_days_count,$paid_leaves,$leave_days);
                            // die();
                            $work =  $present_days_count;
                            if($work<$leave_days) $leave_days=$work;
                            $user_work_days = $work+$paid_leaves;
                            
                        $payscale_cat=$this->grade->get_payscale_data_by_user($value->user_id);
						
                        $earnings=array();
                        $expenditure=array();
						
                        $basic_salary=$this->data['payment']->basic_salary;
                        
                        $total_earnings=0;
                        $total_deduction=0;
                        $real_basic_salary =  $basic_salary;
                   $month_exploded = $date = explode("-",$salary_month);
                   $month_value = $month_exploded[0];
                   $year_value = $month_exploded[1];
                   $days_in_month = cal_days_in_month(CAL_GREGORIAN,$month_value,$year_value );
                  
                   $per_day_salary = $basic_salary / $days_in_month;
                 
                    $cal_salary = $user_work_days * $per_day_salary;
                   
                  $basic_salary = (int)$cal_salary ;
                        foreach($payscale_cat as $pc){
			   $cat=$this->grade->get_single_grade_with_group($pc->id);
						   if($cat->group_code ==1){
							   continue;
						   }
			   $pc->cal_amount=0;
			    if($pc->category_type == 'FixedPay'){
                    if($pc->remove_dependancy_from_attendance ==1 )
                    {
                        $cal_all=$real_basic_salary;
                    }
                    else
                    {
                        $per_day_salary=$pc->amount/$days_in_month;
                        $cal_all=$user_work_days*$per_day_salary;
                    }
                    
                   
				   if($pc->round_of_method == 'round_up'){
					   $pc->cal_amount=round($cal_all);
				   }
                   else if($pc->round_of_method =='round_up_plus')
                   {
                       $pc->cal_amount=ceil($cal_all);
                   }
				   else if($pc->round_of_method == 'round_half'){
					   $pc->cal_amount=round($cal_all,0,PHP_ROUND_HALF_UP);
				   }
                   else{
                    $pc->cal_amount=round($cal_all,2);
                }
               
				  
			   }
			   else if($pc->category_type== 'DependsOnGP'){
                    $calculation_salary = 0 ;
                    if($pc->remove_dependancy_from_attendance ==1 )
                    {
                        $calculation_salary=$real_basic_salary;
                    }
                    else
                    {
                        $calculation_salary=$basic_salary;
                    }
                
				   if($calculation_salary>0 && $pc->percentage >0){
					   if($pc->round_of_method == 'round_up'){
						$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100);
					   }
                       else if($pc->round_of_method =='round_up_plus')
                       {
                           $pc->cal_amount=ceil(($calculation_salary*$pc->percentage)/100);
                       }
					    else if($pc->round_of_method == 'round_half'){
							$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						} else{
							$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,2);
                        }

				   }
                  
				  
			   }			  
			   else if($pc->category_type== 'DependsOnGPandPS'){
				   // this is pending
				   $main_amount=$basic_salary;
                  
                 // $basic_salary = $main_amount ;
                    
				   if($pc->dependant_payscale_categories != ''){
					   $t=explode(",",$pc->dependant_payscale_categories);
					   
					   foreach($t as $val){
                     
						   $cat=$this->grade->get_single_grade_with_group($val);
						   //var_dump($cat);
                           
						   if($cat->group_code ==1){
							   continue;
						   }
                          
						    $this->db->select('EP.*');
							$this->db->from('user_payscalecategories AS EP');
							$this->db->where('EP.user_id', $value->user_id);
							$this->db->where('EP.payscalecategory_id', $cat->id);
							$payscale_cat1=$this->db->get()->result();
							if(!empty($payscale_cat1)){
							if($cat->category_type == 'FixedPay'){
							   if($cat->round_of_method == 'round_up'){
								   $amt=round($cat->amount);
							   }
                               else if($cat->round_of_method == 'round_up_plus')
                               {
                                    $amt = ceil($cat->amount);
                               }
							   else if($cat->round_of_method == 'round_half'){
								   $amt=round($cat->amount,0,PHP_ROUND_HALF_UP);
							   }
                               else
                               {
                                $amt=round($cat->amount,2);

                               }
							   
						   }
						   else if($cat->category_type== 'DependsOnGP'){
                            $calculation_salary = 0 ;
                            if($cat->remove_dependancy_from_attendance ==1 )
                            {
                                $calculation_salary=$real_basic_salary;
                            }
                            else
                            {
                                $calculation_salary=$basic_salary;
                            }
                                

							   if($calculation_salary>0 && $cat->percentage >0){
								   if($cat->round_of_method == 'round_up'){
									$amt=round(($calculation_salary*$cat->percentage)/100);
								   }
									else if($cat->round_of_method == 'round_half'){
										$amt=round(($calculation_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
									}
                                    else if($cat->round_of_method == 'round_up_plus')
                                    {
                                         $amt = ceil(($calculation_salary*$cat->percentage)/100);
                                    }
                                    else

                                    {
                                        $amt=round(($calculation_salary*$cat->percentage)/100,2);

                                    }
							   }
						   }
						    else if($cat->category_type== 'DependsOnGPandPS'){
                                $calculation_salary = 0 ;
                                if($cat->remove_dependancy_from_attendance ==1 )
                                {
                                    $calculation_salary=$real_basic_salary;
                                }
                                else
                                {
                                    $calculation_salary=$basic_salary;
                                }
							   if($calculation_salary>0 && $cat->percentage >0){
								   if($cat->round_of_method == 'round_up'){
									$amt=round(($calculation_salary*$cat->percentage)/100);
								   }
                                   else if($cat->round_of_method == 'round_up_plus')
                                   {
                                        $amt = ceil(($calculation_salary*$cat->percentage)/100);
                                   }
									else if($cat->round_of_method == 'round_half'){
                                      
										$amt=round(($calculation_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
									}
                                    else
                                    {
                                        $amt=round(($calculation_salary*$cat->percentage)/100,2);

                                    }
							   }
						   }
                         
						     if($cat->is_deduction_type=='TRUE'){

								$main_amount = $main_amount - $amt;
							}
							else{

								$main_amount = $main_amount + $amt;
							} 
                            
							/* if($cat->is_deduction_type=='TRUE'){
								$tot_earnings = $tot_earnings - $amt;
							}
							else{
								$tot_earnings = $tot_earnings + $amt;
							} */
					   }
					   }
                      
				   }
				   if($pc->percentage >0){
                    $calculation_salary = 0 ;
                    if($pc->remove_dependancy_from_attendance ==1 )
                    {
                        $calculation_salary=$real_basic_salary;
                    }
                    else
                    {
                        $calculation_salary=$basic_salary;
                    }
					    if($pc->round_of_method == 'round_up'){
							$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100);
					   }
					    else if($pc->round_of_method == 'round_half'){
                          
							$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						}
                        else if($cat->round_of_method == 'round_up_plus')
                        {
                            $pc->cal_amount=ceil(($calculation_salary*$pc->percentage)/100);
                        }
                        else
                        {
                            $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,2);

                        }
                       
				   }

			   }
			   //echo $tot_earnings;
               if($pc->set_max_amount_limit && $pc->cal_amount > $pc->max_amount_possible) {
                   $pc->cal_amount = $pc->max_amount_possible;
                  
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
        //    if($value->user_id == 1018508)
        //                     {
        //                         var_dump($total_earnings,$basic_salary);
        //                         die();
        //                     }
                        $net_salary=$basic_salary+$total_earnings-$total_deduction;
                                                $work_days[]= $user_work_days;
                                                 $net[]= $net_salary;
												 $tot_earnings[]= $total_earnings+$basic_salary;
												 $tot_deduction[]= $total_deduction; 
                                                $earnings1[]=$earnings;
												$expenditure1[]=$expenditure;
								

                    } 
				

					    $this->data['work_days']=$work_days;
						$this->data['net_salary']=$net;
						$this->data['earnings']=$earnings1;
						$this->data['expenditures']=$expenditure1;
                        $this->data['total_earnings']=$tot_earnings;
                        $this->data['total_deduction']=$tot_deduction;
                } 

                
                $this->data['payment_to'] = $payment_to;
               
                $this->data['user_id'] = $user_id;
                $this->data['school_id'] =  $school_id;

                $ledgers = $this->payment->get_list('account_ledgers', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
                $this->data['account_ledgers']=$ledgers;

                $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$this->data['school_id']));
                 //echo"<pre>"; print_r($this->data['bulk']);exit;
              
               
            }
            else{
                    
         
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
                                else if($pc->round_of_method == 'round_up_plus')
                                {
                                    $pc->cal_amount=ceil($pc->amount);
                                }
                                else if($pc->round_of_method == 'round_half'){
                                    $pc->cal_amount=round($pc->amount,0,PHP_ROUND_HALF_UP);
                                }
                                else
                                {
                                    $pc->cal_amount=round($pc->amount,2);

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
                                        else if($pc->round_of_method == 'round_up_plus')
                                        {

                                            $pc->cal_amount=ceil(($basic_salary*$pc->percentage)/100);

                                        }
                                        else
                                        {
                                            $pc->cal_amount=round(($basic_salary*$pc->percentage)/100,2);

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
                                            else if($cat->round_of_method == 'round_up_plus')
                                            {
                                                $amt=ceil($cat->amount);
                                            }
                                            else
                                            {
                                                $amt=round($cat->amount,2);

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
                                                    else if($cat->round_of_method == 'round_up_plus')
                                                    {
                                                        $amt=ceil(($basic_salary*$cat->percentage)/100);
                                                    }
                                                    else
                                                    {
                                                        $amt=round(($basic_salary*$cat->percentage)/100,2);

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
                                        else if($cat->round_of_method == 'round_up_plus')
                                        {
                                            $pc->cal_amount=ceil(($main_amount*$pc->percentage)/100);
                                        }
                                        else
                                        {
                                            $pc->cal_amount=round(($main_amount*$pc->percentage)/100,2);

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
                        
//                         var_dump( $basic_salary,$total_earnings,$total_deduction,$net_salary);
// die();
                        $this->data['net_salary']=$net_salary;
                        $this->data['total_earnings']=$total_earnings;
                        $this->data['total_deduction']=$total_deduction;
                        $this->data['earnings']=$earnings;
                        $this->data['expenditure']=$expenditure;
                            $this->data['add'] = TRUE; 
                            $this->data['payments'] = $this->payment->get_payment_list($school_id, $user_id, $payment_to);            
                        // $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$school_id));
                            $ledgers = $this->payment->get_list('account_ledgers', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
                            $this->data['account_ledgers']=$ledgers;
                }
            
         }else{
             
            if($user_id){
                
                $this->data['user']    = $this->payment->get_single('users', array('id' => $user_id)); 
                
                $payment_to  = $this->data['user']->role_id == TEACHER ? 'teacher' : 'employee';
               
                $this->data['payment_to'] = $payment_to;
                $this->data['user_id'] = $user_id;
                $this->data['school_id'] = $this->data['user']->school_id;

                $this->data['payment'] = $this->payment->get_single_payment_user($this->data['user']->role_id, $user_id); 
                $this->data['payments'] = array();//$this->payment->get_payment_list($this->data['school_id'], $user_id, $payment_to);
                $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$this->data['school_id']));
            }
            
           // $this->data['list'] = FALSE;
        }  
        if(!($school_id) && $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)
        {
               $school_id = $this->session->userdata('school_id');
        }
        $this->data['f_start_date']="";
    
         $this->data['f_end_date']="";
         $this->data['a_start_date']="";
    
         $this->data['a_end_date']="";
        if($school_id)
        {
            $financial_year=$this->payment->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));	
            $previous_financial_year=$this->payment->get_single('financial_years',array('previous_financial_year_id'=>$financial_year->id,'school_id'=>$school_id));	
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
            $startDate=date('Y-m-d',strtotime($f_start));
            $endDate=date('Y-m-d',strtotime($f_end));
            $this->data['f_start_date']=$startDate;
    
            $this->data['f_end_date']=$endDate;
            if(!empty($previous_financial_year))
            {
                $this->data['f_start_date']=$endDate;
            }
            $academic_years=$this->payment->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
            // debug_a([$academic_years,"",2]);

            if(strpos($academic_years->session_year,"->"))	
            {
                $arr=explode("->",$academic_years->session_year);
                $f_start=date("Y-m-d",strtotime($arr[0]));		
                $f_end=date("Y-m-d",strtotime($arr[1]));	
            }
            else
            {
                $arr=explode("-",$academic_years->session_year);
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
            $startDate=date('m-Y',strtotime($f_start));
            $endDate=date('m-Y',strtotime($f_end));
            $this->data['a_start_date']=$startDate;
    
            $this->data['a_end_date']=$endDate;
            // debug_a(  [ $startDate,$endDate]);

        }  
        
        
        $this->layout->title( $this->lang->line('manage_payment'). ' | ' . SMS);
        $this->layout->view('payment/index', $this->data);            
       
    }
    public function get_payment_details()
    {
     

        $response = array();
        $school_id  = $this->input->post('school_id');
        $payment_to  = $this->input->post('payment_to');
        $user_id  = $this->input->post('user_id');
        $working_days  = $this->input->post('working_days');
        $debit_ledger_id  = $this->input->post('debit_ledger_id');
        $credit_ledger_id  = $this->input->post('credit_ledger_id');
        $salary_month=$this->input->post('salary_month');
        $payment_date =$this->input->post('payment_date');

        $this->data['payment_status'] = "paid";
        $this->data['salary_month']  = $this->input->post('salary_month');
        $this->data['debit_ledger_id'] = $debit_ledger_id;
        $this->data['credit_ledger_id'] =  $credit_ledger_id;
        $this->data['user']    = $this->payment->get_single('users', array('id' => $user_id));      
        $this->data['payment'] = $this->payment->get_single_payment_user($this->data['user']->role_id, $user_id);        
        
        $basic_salary=$this->data['payment']->basic_salary;  
        $real_basic_salary = $basic_salary;   
        $month_exploded = $date = explode("-",$salary_month);
        $month_value = $month_exploded[0];
        $year_value = $month_exploded[1];
        $days= cal_days_in_month(CAL_GREGORIAN,$month_value,$year_value );
        
        $per_day_salary = $basic_salary / $days;
        
        $cal_salary = $working_days * $per_day_salary;
        
       $basic_salary = (int)$cal_salary ;
       $payscale_cat=$this->payment->get_payscale_data_by_user($user_id);	
//var_dump($payscale_cat);		   
            $earnings=array();
            $expenditure=array();
            //$basic_salary=$this->data['payment']->basic_salary;
            
            //echo $this->db->last_query();
            $total_earnings=0;
            $total_deduction=0;	
            //var_dump($payscale_cat);
            foreach($payscale_cat as $pc){
               
                $tot_earnings=$basic_salary;
                $cat=$this->payment->get_single_grade_with_group($pc->id);
                            if($cat->group_code ==1){
                                continue;
                            }
                $pc->cal_amount=0;
                if($pc->category_type == 'FixedPay'){
                   
                    if($pc->remove_dependancy_from_attendance ==1 )
                    {
                        $cal_all=$pc->amount;
                    }
                    else
                    {
                        $per_day_salary=$pc->amount/$days;
                        $cal_all=$working_days*$per_day_salary;
                    }
                   
                    
                    if($pc->round_of_method == 'round_up'){
                        $pc->cal_amount=round($cal_all);
                    }
                    else if($pc->round_of_method == 'round_up_plus')
                    {
                        $pc->cal_amount=ceil($cal_all);
                    }
                    else if($pc->round_of_method == 'round_half'){
                        $pc->cal_amount=round($cal_all,0,PHP_ROUND_HALF_UP);
                    }
                    else
                    {
                    $pc->cal_amount=round($cal_all,2);

                    }
                    
                    
                }
                else if($pc->category_type== 'DependsOnGP'){
                    $calculation_salary=0;
                    if($pc->remove_dependancy_from_attendance ==1 )
                    {
                        $calculation_salary=$real_basic_salary;
                    }
                    else
                    {
                        $calculation_salary=$basic_salary;
                    }
                    if($calculation_salary>0 && $pc->percentage >0){
                        if($pc->round_of_method == 'round_up'){
                        $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100);
                        }
                        else if($pc->round_of_method == 'round_up_plus')
                        {
                            $pc->cal_amount=ceil(($calculation_salary*$pc->percentage)/100);
                        }
                        else if($pc->round_of_method == 'round_half'){
                            $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
                        }
                        else
                        {
                            $pc->cal_amount=round(($basic_salary*$pc->percentage)/100,2);

                        }
                    }
                    
                }			  
                else if($pc->category_type== 'DependsOnGPandPS'){
                    // this is pending
                    $main_amount=$basic_salary;
                    if($pc->dependant_payscale_categories != ''){
                        $t=explode(",",$pc->dependant_payscale_categories);
                        
                        foreach($t as $val){
                        
                            $cat=$this->payment->get_single_grade_with_group($val);
                            //var_dump($cat);
                            if($cat->group_code ==1){
                                continue;
                            }
                            $this->db->select('EP.*');
                            $this->db->from('user_payscalecategories AS EP');
                            $this->db->where('EP.user_id', $user_id);
                            $this->db->where('EP.payscalecategory_id', $cat->id);
                            $payscale_cat1=$this->db->get()->result();
                            if(!empty($payscale_cat1)){
                            if($cat->category_type == 'FixedPay'){
                                $cat_per_day_salary=$cat->amount/$days;
                   
                                if($cat->remove_dependancy_from_attendance ==1)
                                {
                                    $cat_cal_all=$cat->amount;
                                }
                                else
                                {
                                    $cat_cal_all= $working_days*$cat_per_day_salary;
                                }
                                if($cat->round_of_method == 'round_up'){
                                    $amt=round( $cat_cal_all);
                                }
                                else if($cat->round_of_method == 'round_half'){
                                    $amt=round( $cat_cal_all,0,PHP_ROUND_HALF_UP);
                                }
                                else if($cat->round_of_method == 'round_up_plus')
                                {
                                    $amt=ceil( $cat_cal_all);
                                }
                                else
                                {
                                $amt=round( $cat_cal_all,2);

                                }
                                
                            }
                            else if($cat->category_type== 'DependsOnGP'){
                                $calculation_salary=0;
                                if($cat->remove_dependancy_from_attendance ==1 )
                                {
                                    $calculation_salary=$real_basic_salary;
                                }
                                else
                                {
                                    $calculation_salary=$basic_salary;
                                }
                                if($calculation_salary>0 && $cat->percentage >0){
                                    if($cat->round_of_method == 'round_up'){
                                    $amt=round(($calculation_salary*$cat->percentage)/100);
                                    }
                                    else if($cat->round_of_method == 'round_half'){
                                        $amt=round(($calculation_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
                                    }
                                    else if($cat->round_of_method == 'round_up_plus')
                                    {
                                        $amt=ceil(($calculation_salary*$cat->percentage)/100);
                                    }
                                    else
                                    {
                                        $amt=round(($calculation_salary*$cat->percentage)/100,2);

                                    }
                                }
                            }
                            else if($cat->category_type== 'DependsOnGPandPS'){
                                $calculation_salary=0;
                                if($cat->remove_dependancy_from_attendance ==1 )
                                {
                                    $calculation_salary=$real_basic_salary;
                                }
                                else
                                {
                                    $calculation_salary=$basic_salary;
                                }
                                if($calculation_salary>0 && $cat->percentage >0){
                                    if($cat->round_of_method == 'round_up'){
                                    $amt=round(($calculation_salary*$cat->percentage)/100);
                                    }
                                    else if($cat->round_of_method == 'round_half'){
                                        $amt=round(($calculation_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
                                    }
                                    else if($cat->round_of_method == 'round_up_plus')
                                    {
                                        $amt=ceil(($calculation_salary*$cat->percentage)/100);
                                    }
                                    else
                                    {
                                        $amt=round(($calculation_salary*$cat->percentage)/100,2);

                                    }
                                }
                            }
                            
                            if($cat->is_deduction_type=='TRUE'){
                            
                                $main_amount = $main_amount - $amt;
                            }
                            else{
                                
                                $main_amount = $main_amount + $amt;
                            } 
                            /* if($cat->is_deduction_type=='TRUE'){
                                $tot_earnings = $tot_earnings - $amt;
                            }
                            else{
                                $tot_earnings = $tot_earnings + $amt;
                            } */
                            
                        }
                        }
                        
                                
                    }
                    if($pc->percentage >0){
                        $calculation_salary=0;

                        if($pc->remove_dependancy_from_attendance ==1 )
                        {
                            $calculation_salary=$real_basic_salary;
                        }
                        else
                        {
                            $calculation_salary=$main_amount;
                        }
                      
                        if($pc->round_of_method == 'round_up'){
                            $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100);
                        }
                        else if($pc->round_of_method == 'round_half'){
                            
                            $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
                        }
                        else if($pc->round_of_method == 'round_up_plus')
                        {
                            $pc->cal_amount=ceil(($calculation_salary*$pc->percentage)/100);
                        }
                        else
                        {
                            $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,2);

                        }
                    }
                    
                }
                if($pc->set_max_amount_limit && $pc->cal_amount > $pc->max_amount_possible) $pc->cal_amount = $pc->max_amount_possible;
                //echo $tot_earnings;
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
            $response['net_salary']=$net_salary;
            $response['cal_salary']=$cal_salary;
            $response ['total_earnings']=$total_earnings+$basic_salary;
            $response ['total_deduction']=$total_deduction;
            $response ['earnings']=$earnings;
            $response ['expenditure']=$expenditure;
            
            
            echo json_encode($response );
            exit();
    }

    function bulk(){
        $edited_ledger_ids = [];

        $this->_prepare__bulk_payment_validation();
        if (!$this->form_validation->run()) {
            error("Invalid Payment date");
            redirect('payroll/payment/index/');
        }
        $data = $_POST;
        if(!$data['payment_date'])
        {
            $data['payment_date'] = date('Y-m-d H:i:s');
        }
        $school=$this->payment->get_single('schools', array('id' => $data['school_id']));
      
                        $school1 = $this->payment->get_school_by_id($data['school_id']);
                         //echo "<pre>"; print_r($school1->academic_year_id); exit;
						 
                        if(!$school1->academic_year_id){
                            error($this->lang->line('set_academic_year_for_school'));
                            redirect('payroll/payment/index');
                        }
                    
                        $found_sallary_paid = 0;
                       
        if($school->default_voucher_Id_for_salary_payment > 0){
          
                        if (isset($data['total_allowance']) && count($data['total_allowance']) > 0) {
                         
                                        $i = 0;
                                     
                                    foreach($data['employees_selected'] as $key => $value){
                                            $invoice_no= $this->payment->generate_invoice_no($data['school_id']); 
                                            $i =  $key;
                                            $user = $this->payment->get_single('users', array('id' => $data['user_id'][$i]));
                                            if($user->role_id == TEACHER)
                                            {
                                                $payment_to = "teacher";
                                            }
                                            else
                                            {
                                                $payment_to = "employee";
                                            }
                                            $data1 = [
                                                'invoice_no' =>$invoice_no,
                                                'total_allowance' =>$data['total_allowance'][$key],
                                                'salary_type'=> $data['salary_type'][$i],
                                                'salary_month'=> $data['salary_month'][$i],
                                                'basic_salary'=> $data['basic_salary'][$i],
                                                'working_days'=> $data['working_days'][$i],
                                                'cal_basic_salary'=> $data['cal_basic_salary'][$i],
                                                'total_deduction'=> $data['total_deduction'][$i],

                                                'net_salary'=> $data['net_salary'][$i],
                                                'payment_method'=> $data['payment_method'][$i],

                                                'bank_name'=> $data['bank_name'][$i],
                                                'cheque_no'=> $data['cheque_no'][$i],
                                                'debit_ledger_id'=> $data['debit_ledger_id'][$i],
                                                'credit_ledger_id'=> $data['credit_ledger_id'][$i],
                                                'note'=> $data['note'][$i],
                                                'school_id'=> $data['school_id'],
                                                'payment_to'=>  $payment_to,
                                                'payment_date'=> $data['payment_date'],
                                                'user_id'=> $data['user_id'][$i],
                                                'academic_year_id' => $school->academic_year_id,   
                        
                                                'status' => 1,
                                                'created_at'=> date('Y-m-d H:i:s'),
                                                'created_by' => logged_in_user_id(), 
                                                'payment_status' => $data['payment_status'][$i],
                                            
                                                // 'salary_grade_id'=> $data['salary_grade_id'][$i],

                                            ];
                                            
                                            if($data['payment_status'][$i] == "unpaid")
                                            {
                                              $payment = $this->payment->duplicate_check_all($data['salary_month'][$i], $data['user_id'][$i]); 
                                            }
                                            else $payment = $this->payment->duplicate_check($data['salary_month'][$i], $data['user_id'][$i]); 
                                            if ($payment > 0) {
                                               
                                                continue;
                                            }
                                            if($data['payment_status'][$i] == "paid"){

                                                $check_unpaid = $this->payment->check_unpaid($data['salary_month'][$i], $data['user_id'][$i]);
                                                $update_status = 0;
                                                if($check_unpaid)
                                                {
                                                    $update_status =1;
                                                    $insert_id = $check_unpaid->id;
                                                }
                                                else{
                                                   // $insert_id = $this->payment->insert('salary_payments', $data);
                                                }
                                             
                                               $insert_id = $this->payment->insert('salary_payments', $data1);
                                            
												// echo $this->db->last_query();
												// echo $insert_id;
												// exit(0); 
                                           //$insert_id=74;
                                           
                                            if ($insert_id ) {
                                                    // Account entry for total salary
                                                    $voucher_id=$school->default_voucher_Id_for_salary_payment;
                                                    $user_data=$this->payment->get_single('users', array('id' => $data1['user_id']));
                                                    $user_data = get_user_by_role(@$user_data->role_id,@$user_data->id);
                                                    if($data1['debit_ledger_id']>0 && $data1['credit_ledger_id']>0){
                                                        $transaction=array();
                                                        $transaction['transaction_no']=$this->transactions->generate_transaction_no($voucher_id);
                                                        $transaction['voucher_id']=$voucher_id;									
                                                        $transaction['ledger_id']=$data1['debit_ledger_id'];
                                                        $edited_ledger_ids[] =$data1['debit_ledger_id'];

                                                        $transaction['head_cr_dr']='DR';
                                                        $transaction['salary_payment_id']=$insert_id;
                                                        $transaction['school_id']=$school->id;									
                                                        $transaction['date']= $data['payment_date'];					
                                                        $transaction['created']=date('Y-m-d H:i:s');		
                                                        $transaction['created_by'] = logged_in_user_id();										
                                                        $transaction['narration']="Salary Payment of ".$user_data->name." for ".$data['salary_month'][$i]." month";
                                                        if(isset($transaction['voucher_id'])){		
                                                           // die("4");	
                                                           
                                                                $transaction_id = $this->transactions->insert('account_transactions', $transaction);
                                                        							
                                                            
                                                      
                                                            if ($transaction_id) {								
                                                                        $detail=array();
                                                                        $detail['transaction_id']=$transaction_id;
                                                                        $detail['ledger_id']=$data1['credit_ledger_id'];
                                                                        $edited_ledger_ids[] =$data1['credit_ledger_id'];

                                                                        $detail['amount']=$data1['net_salary'];
                                                                        $detail['created']=date('Y-m-d H:i:s');
                                                                        $detail_id=$this->transactions->insert('account_transaction_details', $detail);
                                                                        foreach($data['earn_name'][$i] as $earn_key=> $earn){
                                                                            $category=$this->payment->get_single('payscale_category', array('id' => $earn));
                                                                            
                                                                            $in_arr['salary_payment_id']=$insert_id;
                                                                            $in_arr['payscalecategory_id']=$earn;
                                                                            $in_arr['amount']=$data['earn_amount'][$i][$earn_key];
                                                                            
                                                                            $in_id=$this->payment->insert('salary_payment_details', $in_arr);                                          
                                                                            if($category->is_deduction_type == "TRUE" && ($category->credit_ledger_id >0)){
                                                                                            // add details to transaction details									
                                                                                        $detail=array();
                                                                                        $detail['transaction_id']=$transaction_id;
                                                                                        $detail['ledger_id']=$category->credit_ledger_id;
                                                                                        $detail['ledger_id']=$category->credit_ledger_id;
                                                                                        $edited_ledger_ids[] =$category->credit_ledger_id;

                                                                                        //$detail['ledger_id']=$data['debit_ledger_id'];
                                                                                        $detail['amount']=$data['earn_amount'][$i][$earn_key];											
                                                                                        $detail['created']=date('Y-m-d H:i:s');
                                                                                        $detail_id=$this->transactions->insert('account_transaction_details', $detail);
                                                                                    
                                                                                
                                                                            }
                                                                        }
                                                                        foreach($data['exp_name'][$i] as $exp_key => $exp){
                                                                                
                                                                            $in_arr['salary_payment_id']=$insert_id;
                                                                            $in_arr['payscalecategory_id']=$exp;
                                                                            $in_arr['amount']=$data['exp_amount'][$i][$exp_key];
                                                                            
                                                                            $in_id=$this->payment->insert('salary_payment_details', $in_arr);
                                                                            //in_id = 988;
                                                                           
                                                                            // insert details in ledgers
                                                                            $category=$this->payment->get_single('payscale_category', array('id' => $exp));
                                                                                                        
                                                                            if($category->is_deduction_type == "TRUE"  && ($category->credit_ledger_id >0))
                                                                            {
                                                                                            // add details to transaction details									
                                                                                        $detail=array();
                                                                                        $detail['transaction_id']=$transaction_id;
                                                                                        $detail['ledger_id']=$category->credit_ledger_id;
                                                                                        $edited_ledger_ids[] = $category->credit_ledger_id;
                                                                                        //$detail['ledger_id']=$data1['debit_ledger_id'];
                                                                                        $detail['amount']=$data['exp_amount'][$i][$exp_key];											
                                                                                        $detail['created']=date('Y-m-d H:i:s');
                                                                                        $detail_id=$this->transactions->insert('account_transaction_details', $detail);
                                                                                        
                                                                                    
                                                                                
                                                                            }
                                                                            $k++;
                                                                        }
                                                                       
                                                                    } 
                                                                }
                                                        // Update salary paymenmt with transaction id
                                                        $salary_update_params = array("transaction_id"=>$transaction_id);
                                                        if($update_status) $salary_update_params['payment_status'] = "paid";
                                                        $updated = $this->payment->update('salary_payments', $salary_update_params , array('id' => $insert_id));
                                                    }
                                                   // Account entry for each payscale category insert salary details						                        
                                                }
                                                
                                            }    
                                                    
                        
                                                
                                                
                                            else{
                                   
                                            $insert_id = $this->payment->insert('salary_payments', $data1);
                                            }
                                        
                                         
                                         
                                      } 
                                     
                        }
                                              
                        if(!empty($edited_ledger_ids))
                        {
                            update_ledger_opening_balance($edited_ledger_ids, $data['school_id']);
                        }
                                                    success($this->lang->line('insert_success'));
                                                redirect('payroll/payment/index/');
        } else{
          
                    error('Please update default voucher for salary payment by editing school details.');
                    redirect('payroll/payment/index/');
                }           

    }
    public function delete_payslip($id){	
        check_permission(DELETE);
        

        if($id)
        {
            $this->payment->delete_salary_payment($id);
            success($this->lang->line('delete_success'));
        }
        else{
            error($this->lang->line('delete_failed'));

        }
        redirect('payroll/history/index/');
    }
    public function revert($id){	
        check_permission(DELETE);
        if($id)
        {
            $updated = $this->payment->update('salary_payments', array('reverted'=>1), array('id' => $id));
            $updated = $this->payment->update('account_transactions', array('cancelled'=>1), array('salary_payment_id' => $id));
            success($this->lang->line('delete_success'));
        }
        else{
            error($this->lang->line('delete_failed'));

        }
        redirect('payroll/history/index/');
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
//var_dump($payscale_cat);		   
		   $earnings=array();
		   $expenditure=array();
		   //$basic_salary=$this->data['payment']->basic_salary;
           $real_basic_salary =$params['basic_salary'];
		   $basic_salary=$params['cal_basic_salary'];
		   $working_days=$params['working_days'];
		    $month=explode("-",$params['salary_month']);
			
		   $days= cal_days_in_month(CAL_GREGORIAN, $month[0], $month[1]);
		   
		   $total_earnings=0;
		   $total_deduction=0;	
		   
		   foreach($payscale_cat as $pc){
			   $tot_earnings=$basic_salary;
			   $cat=$this->grade->get_single_grade_with_group($pc->id);
						   if($cat->group_code ==1){
							   continue;
						   }
			   $pc->cal_amount=0;
			    if($pc->category_type == 'FixedPay'){
					$per_day_salary=$pc->amount/$days;
                    if($pc->remove_dependancy_from_attendance ==1)
                    {
                        $cal_all=$pc->amount;
                    }
                    else
                    {
                        $cal_all=$working_days*$per_day_salary;
                    }
              
				   if($pc->round_of_method == 'round_up'){
					   $pc->cal_amount=round($cal_all);
				   }
                   else if($pc->round_of_method =='round_up_plus')
                   {
                       $pc->cal_amount=ceil($cal_all);
                   }
				   else if($pc->round_of_method == 'round_half'){
					   $pc->cal_amount=round($cal_all,0,PHP_ROUND_HALF_UP);
				   }
                   else
                   {
                    $pc->cal_amount=round($cal_all,2);

                   }
			   }
			   else if($pc->category_type== 'DependsOnGP'){
                    if($pc->remove_dependancy_from_attendance ==1)
                    {
                        $calculation_salary=$real_basic_salary;
                    }
                    else
                    {
                        $calculation_salary=$basic_salary;
                    }
				   if($calculation_salary>0 && $pc->percentage >0){
					   if($pc->round_of_method == 'round_up'){
						$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100);
					   }
					    else if($pc->round_of_method == 'round_half'){
							$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						}
                        else if($pc->round_of_method =='round_up_plus')
                        {
                            $pc->cal_amount=ceil(($calculation_salary*$pc->percentage)/100);
                        }
                        else
                        {
                            $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,2);

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
						   //var_dump($cat);
						   if($cat->group_code ==1){
							   continue;
						   }
						    $this->db->select('EP.*');
							$this->db->from('user_payscalecategories AS EP');
							$this->db->where('EP.user_id', $user_id);
							$this->db->where('EP.payscalecategory_id', $cat->id);
							$payscale_cat1=$this->db->get()->result();
							if(!empty($payscale_cat1)){
							if($cat->category_type == 'FixedPay'){
                                $cat_per_day_salary=$cat->amount/$days;
                   
                                if($cat->remove_dependancy_from_attendance ==1)
                                {
                                    $cat_cal_all=$cat->amount;
                                }
                                else
                                {
                                    $cat_cal_all= $working_days*$cat_per_day_salary;
                                }

							   if($cat->round_of_method == 'round_up'){
								   $amt=round( $cat_cal_all);
							   }
							   else if($cat->round_of_method == 'round_half'){
								   $amt=round( $cat_cal_all,0,PHP_ROUND_HALF_UP);
							   }
                               else if($cat->round_of_method =='round_up_plus')
                               {
                                   $amt=ceil( $cat_cal_all);
                               }
                               else
                               {
                                $amt=round( $cat_cal_all,2);

                               }
							   
						   }
						   else if($cat->category_type== 'DependsOnGP'){
                               $calculation_salary =0;
                            if($cat->remove_dependancy_from_attendance ==1)
                            {
                                $calculation_salary=$real_basic_salary;
                            }
                            else
                            {
                                $calculation_salary=$basic_salary;
                            }
							   if($calculation_salary>0 && $cat->percentage >0){
								   if($cat->round_of_method == 'round_up'){
									$amt=round(($calculation_salary*$cat->percentage)/100);
								   }
									else if($cat->round_of_method == 'round_half'){
										$amt=round(($calculation_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
									}
                                    else if($cat->round_of_method =='round_up_plus')
                                    {
                                        $amt=ceil(($calculation_salary*$cat->percentage)/100);
                                    }
                                    else
                                    {
                                        $amt=round(($calculation_salary*$cat->percentage)/100,2);

                                    }
							   }
						   }
						    else if($cat->category_type== 'DependsOnGPandPS'){
                                $calculation_salary =0;

                                if($cat->remove_dependancy_from_attendance ==1)
                                {
                                    $calculation_salary=$real_basic_salary;
                                }
                                else
                                {
                                    $calculation_salary=$basic_salary;
                                }
							   if($calculation_salary>0 && $cat->percentage >0){
								   if($cat->round_of_method == 'round_up'){
									$amt=round(($calculation_salary*$cat->percentage)/100);
								   }
									else if($cat->round_of_method == 'round_half'){
										$amt=round(($calculation_salary*$cat->percentage)/100,0,PHP_ROUND_HALF_UP);
									}
                                    else if($cat->round_of_method =='round_up_plus')
                                    {
                                        $amt=ceil(($calculation_salary*$cat->percentage)/100);
                                    }
                                    else
                                    {
                                        $amt=round(($calculation_salary*$cat->percentage)/100,2);

                                    }
							   }
						   }
                          
						     if($cat->is_deduction_type=='TRUE'){
                             
								$main_amount = $main_amount - $amt;
							}
							else{
                               
								$main_amount = $main_amount + $amt;
							} 
							/* if($cat->is_deduction_type=='TRUE'){
								$tot_earnings = $tot_earnings - $amt;
							}
							else{
								$tot_earnings = $tot_earnings + $amt;
							} */
                          
					   }
					   }
                       
                              
				   }
				   if($pc->percentage >0){
                    $calculation_salary= 0;
                    if($cat->remove_dependancy_from_attendance ==1)
                    {
                        $calculation_salary=$real_basic_salary;
                    }
                    else
                    {
                        $calculation_salary=$main_amount;
                    }
					    if($pc->round_of_method == 'round_up'){
							$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100);
					   }
					    else if($pc->round_of_method == 'round_half'){
                           
							$pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,0,PHP_ROUND_HALF_UP);
						}
                        else if($pc->round_of_method =='round_up_plus')
                        {
                            $pc->cal_amount=ceil(($calculation_salary*$pc->percentage)/100);
                        }
                        else
                        {
                            $pc->cal_amount=round(($calculation_salary*$pc->percentage)/100,2);

                        }
				   }
                   
			   }
               if($pc->set_max_amount_limit && $pc->cal_amount > $pc->max_amount_possible) $pc->cal_amount = $pc->max_amount_possible;
			   //echo $tot_earnings;
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
		   $this->data['total_earnings']=$total_earnings+$basic_salary;
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
            $edited_ledger_ids = [];
            //echo "<pre>"; print_r($_POST); exit;
            $this->_prepare__bulk_payment_validation();
            if (!$this->form_validation->run()) {
                error("Invalid Payment date");
                redirect('payroll/payment/index/');
            }
            $this->_prepare_payment_validation();
            if ($this->form_validation->run() === TRUE) {
				// check if voucher for salary payment is set for school
				$school=$this->payment->get_single('schools', array('id' => $_POST['school_id']));
				if($school->default_voucher_Id_for_salary_payment > 0){					
					$data = $this->_get_posted_payment_data();
                    if(!$data['payment_date'])
                    {
                        $data['payment_date'] = date('Y-m-d H:i:s');
                    }
                    $check_unpaid = $this->payment->check_unpaid($data['salary_month'], $data['user_id']);
                    $update_status = 0;
                    $user_data=$this->payment->get_single('users', array('id' => $data['user_id']));
                    if($user_data->role_id == TEACHER)
                    {
                        $data['payment_to']  = "teacher";
                    }
                    else
                    {
                        $data['payment_to'] = "employee";
                    }
                    if($check_unpaid && $data['payment_status'] == "paid")
                    {
                       
                        $update_status =1;
                        $insert_id = $check_unpaid->id;
                    }
                    else{
                       
                        $insert_id = $this->payment->insert('salary_payments', $data);
                    } 
                    if ($insert_id) {
						
                        if($data['payment_status'] == "paid")
                        {
                            // Account entry for total salary
                            $voucher_id=$school->default_voucher_Id_for_salary_payment;
                            $user_data = get_user_by_role(@$user_data->role_id,@$user_data->id);
                            if($data['debit_ledger_id']>0 && $data['credit_ledger_id']>0 ){
                                $transaction=array();
                                $transaction['transaction_no']=$this->transactions->generate_transaction_no($voucher_id);
                                $transaction['voucher_id']=$voucher_id;									
                                $transaction['ledger_id']=$data['debit_ledger_id'];
                                $edited_ledger_ids[] = $data['debit_ledger_id'];
                                $transaction['salary_payment_id']=$insert_id;
                                $transaction['school_id']=$school->id;
                                $transaction['created_by'] = logged_in_user_id();										
                                $transaction['head_cr_dr']='DR';									
                                $transaction['date']=$data['payment_date'];							
                                $transaction['created']=date('Y-m-d H:i:s');								
                                $transaction['narration']="Salary Payment of ".$user_data->name." for ".$data['salary_month']." month";
                                if(isset($transaction['voucher_id'])){			
                                								
                                    $transaction_id = $this->transactions->insert('account_transactions', $transaction);
                                    if ($transaction_id) 
                                    {					
                                                $detail=array();
                                                $detail['transaction_id']=$transaction_id;
                                                $detail['ledger_id']=$data['credit_ledger_id'];
                                                $edited_ledger_ids[] = $data['credit_ledger_id'];
                                                $detail['amount']=$data['net_salary'];
                                                $detail['created']=date('Y-m-d H:i:s');
                                                $detail_id=$this->transactions->insert('account_transaction_details', $detail);			
                                                if(!empty($_POST['cat'])){
                                                    foreach($_POST['cat'] as $cat_id=>$amt){
                                                        $category=$this->payment->get_single('payscale_category', array('id' => $cat_id));

                                                        $in_arr['salary_payment_id']=$insert_id;
                                                        $in_arr['payscalecategory_id']=$cat_id;
                                                        $in_arr['amount']=$amt;
                                                        
                                                        $in_id=$this->payment->insert('salary_payment_details', $in_arr);                                                                                                        
                                                        if($category->is_deduction_type == "TRUE"  ){
                                                        
                                                               								
                                                                    $detail=array();
                                                                    $detail['transaction_id']=$transaction_id;
                                                                    $detail['ledger_id']=$category->credit_ledger_id;
                                                                    $edited_ledger_ids[] = $category->credit_ledger_id;

                                                                    //$detail['ledger_id']=$data['debit_ledger_id'];
                                                                    $detail['amount']=$amt;											
                                                                    $detail['created']=date('Y-m-d H:i:s');
                                                                    $detail_id=$this->transactions->insert('account_transaction_details', $detail);
                                                                
                                                            
                                                        }
                                                    }
                                                }
                                            } 
                                        }
                                // Update salary paymenmt with transaction id
                            
                                $salary_update_params = array("transaction_id"=>$transaction_id);
                                if($update_status) $salary_update_params['payment_status'] = "paid";
                                $updated = $this->payment->update('salary_payments', $salary_update_params, array('id' => $insert_id));
                            }
                            // Account entry for each payscale category insert salary details						
                            
                        }
                        if(!empty($edited_ledger_ids))
                        {
                            update_ledger_opening_balance($edited_ledger_ids,  $_POST['school_id']);
                        }
						success($this->lang->line('insert_success'));
						redirect('payroll/payment/index/');
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
                $this->data['payment'] = array();//$this->payment->get_single_payment_user($this->data['user']->role_id, $user_id); 
                $this->data['payments'] = $this->payment->get_payment_list($school_id, $user_id, $payment_to);
                $this->data['exp_heads'] = $this->payment->get_list('expenditure_heads', array('status'=> 1, 'school_id'=>$school_id));
                
                $this->data['post'] = $_POST;
            }
        }

                
         
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('payment'). ' | ' . SMS);
        $this->layout->view('payment/index', $this->data);
    }
    private function _prepare__bulk_payment_validation()
    {
   
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'trim|callback_check_valid_date');
    }
    function check_valid_date()
    {
        $month = $this->input->post('payment_date');
       
        if(!checkIsAValidDate($month))
        {
            $this->form_validation->set_message('check_valid_date','Invalid date');
            return FALSE;
        }
        $school_id = $this->input->post('school_id');
        $month_str = strtotime($month);
        $financial_year=$this->payment->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));		
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
            return FALSE;
        }
        return TRUE;
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
    
    public function get_leave_days()
    {
        $response = array();
        $school_id = $this->input->post('school_id');
        $user_id = $this->input->post('user_id');
        $role = $this->input->post('role_id');
        $salary_month  = $this->input->post('month');
        $school = $this->payment->get_school_by_id($school_id);
        $user    = $this->payment->get_single('users', array('id' => $user_id));   
        $role_id = $user->role_id;
        $payment_user = $this->payment->get_single_payment_user($role_id, $user_id); 
        $date_array = explode("-",$salary_month);
        $year = $date_array['1'];
        $month = $date_array['0'];
        $condition = array(              
            'school_id'=>$school_id,
             'month'=>$month,
             'year'=>$year,
             'academic_year_id'=>$school->academic_year_id
        );
        if($role_id == "TEACHER")
        {
            $condition['teacher_id'] = $payment_user->id;
            $attendance_month = (array)$this->payment->get_single('teacher_attendances', $condition);
            //$employee_attendance = get_employee_attendance($obj->id, $school_id, $school->academic_year_id, $year, $month, $day);
        }
        else 
        {
            $condition['employee_id'] = $payment_user->id;
            $attendance_month = (array)$this->payment->get_single('employee_attendances', $condition);
        }
        
        $approved_leaves = $this->payment->get_approved_paid_leaves($school_id,$user_id, $role_id, $school->academic_year_id,$salary_month);
       
        $paid_leaves = 0;
        foreach($approved_leaves  as $approved_leave)
        {
            $leave_start = $approved_leave->leave_from;
            $leave_end  = $approved_leave->leave_to;
            $begin = new DateTime($leave_start);
            $end = new DateTime($leave_end);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            while(strtotime($leave_start) <= strtotime($leave_end))
            {
                $paid_leaves++;
                $leave_start= date("Y-m-d",strtotime("+1 day",strtotime($leave_start)));
            }
        } 
    
        $leave_days_array = !empty($attendance_month) ? array_keys($attendance_month,"L") : array();
        $abcence_days_array = !empty($attendance_month) ? array_keys($attendance_month,"A") : array();
        $present_days_array = !empty($attendance_month) ? array_keys($attendance_month,"P") : array();

        $abcence_days_count = count($abcence_days_array);
        $leave_days_count = count($leave_days_array);
        $present_days_count = count($present_days_array);
        
        $leave_days= $leave_days_count-$paid_leaves;
        // var_dump($leave_days_count,$abcence_days_count,$paid_leaves,$leave_days);
        // die();
        $response['abscense_days'] =  $abcence_days_count+$leave_days;
        $response['paid_leave'] =  $paid_leaves;
        $response['present_days'] = $present_days_count;
        echo json_encode($response);
                        
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
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'trim|callback_check_valid_date');
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
          if($this->input->post('payment_status') == "unpaid")
          {
            $payment = $this->payment->duplicate_check_all($this->input->post('salary_month'), $this->input->post('user_id')); 
          }
          else $payment = $this->payment->duplicate_check($this->input->post('salary_month'), $this->input->post('user_id')); 
          if($payment){
                $this->form_validation->set_message('salary_month',  $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
        if($this->input->post('payment_status') == "unpaid")
        {
          $payment = $this->payment->duplicate_check_all($this->input->post('salary_month'), $this->input->post('user_id')); 
        }
        else $payment = $this->payment->duplicate_check($this->input->post('salary_month'), $this->input->post('user_id')); 
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
        $items[] = 'payment_date';
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
		$items[] = 'payment_status';
        
        $items[] = 'bank_name';
        $items[] = 'cheque_no';
        
        $items[] = 'note';
        
        $data = elements($items, $_POST);  
        $invoice_no = $this->payment->generate_invoice_no($data['school_id']); 
      
        $data['invoice_no'] =   $invoice_no;
        if($this->input->post('payment_method') == 'cash'){
            $data['bank_name'] = ''; 
            $data['cheque_no'] = ''; 
        }
    
        if(!$this->input->post('payment_date'))
        {
            $data['payment_date'] = date("Y-m-d",time());
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
        
       /// $this->data['list'] = TRUE;       
        $this->layout->title( $this->lang->line('manage_payment'). ' | ' . SMS);
        $this->layout->view('payment/history', $this->data);            
       
    }
/* 	public function payslip($id = null){
		 if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/payment/index');
        }
        
        $this->data['payment'] = $this->payment->get_single('salary_payments', array('id' => $id));
		$this->data['school'] = $this->payment->get_school_by_id($this->data['payment']->school_id); 		
		$this->data['detail']=$this->payment->get_salary_payment_detail($id);
		$this->data['transaction']=$this->transactions->get_transactions_by_id($this->data['payment']->transaction_id);		
		$this->data['transaction_detail']=$this->transactions->get_transaction_detail($this->data['payment']->transaction_id);				
        // echo "<pre>"; print_r($this->data);exit;
		$this->layout->title( $this->lang->line('payslip'). ' | ' . SMS);
        $this->layout->view('payment/payslip', $this->data);     
	} */
	public function payslip($id = null){
		 if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/payment/index');
        }
        $earnings=array();
		$expenditures=array();
		$total_earnings=0;
		$total_expenditure=0;
	
        $payment = $this->payment->get_single('salary_payments', array('id' => $id));
       
		$user_id=$payment->user_id;
        $user = $this->payment->get_single('users', array('id' => $user_id));

        $this->data['payment']=$payment;

        if($user->role_id ==TEACHER) 
        {
            $this->db->select('*');
            $this->db->from('teachers AS E');
            $this->db->where('E.user_id', $user_id);
            $employee=$this->db->get()->row();
        }
        else
        {
            $this->db->select('*');
            $this->db->from('employees AS E');
            $this->db->where('E.user_id', $user_id);
            $employee=$this->db->get()->row();
        }
		
		$this->data['employee']=$employee;
		$this->data['school'] = $this->payment->get_school_by_id($this->data['payment']->school_id); 		
		$details=$this->payment->get_salary_payment_detail($id);
		foreach($details as $detail){
			if($detail->type=="FALSE"){
                $earnings[]=array(
                    'cat_name'=>$detail->cat_name,
			'amount'=>$detail->amount
			);
			$total_earnings=$total_earnings+$detail->amount;
			} else {
			$expenditures[]=array(
			'cat_name'=>$detail->cat_name,
			'amount'=>$detail->amount
			);
			$total_expenditure=$total_expenditure+$detail->amount;
			}
		}
		$this->data['earnings']=$earnings;
		$this->data['expenditures']=$expenditures;
		$this->data['total_earnings']=$total_earnings;
		$this->data['total_expenditure']=$total_expenditure;
		$this->data['detail']=$details;
		$this->data['transaction']=$this->transactions->get_transactions_by_id($this->data['payment']->transaction_id);		
		$this->data['transaction_detail']=$this->transactions->get_transaction_detail($this->data['payment']->transaction_id);				
        // echo "<pre>"; print_r($this->data);exit;
		$this->layout->title( $this->lang->line('payslip'). ' | ' . SMS);
        $this->layout->view('payment/payslip', $this->data);     
	}

   
}
