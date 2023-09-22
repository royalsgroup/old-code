<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
 
    public function get_single_payment_user($role_id, $user_id) {
        
        if ($role_id == TEACHER) {
            
            $this->db->select('T.*, T.responsibility AS designation, U.username, U.role_id, U.status AS login_status ');
            $this->db->from('teachers AS T');
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');  
            //$this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left'); 
            $this->db->where('T.user_id', $user_id);
            return $this->db->get()->row();
            
        } else { 
            
            $this->db->select('E.*, U.username, U.role_id, D.name AS designation, U.status AS login_status ');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->join('designations AS D', 'D.id = E.designation_id', 'left'); 
            //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left'); 
            $this->db->where('E.user_id', $user_id);
            return $this->db->get()->row();
            
        } 
    }
    public function get_payscale_data_by_user($user_id){
        $this->db->select('PC.*');
       $this->db->from('user_payscalecategories AS EP');
       $this->db->join('payscale_category AS PC', 'PC.id = EP.payscalecategory_id', 'left');
       $this->db->where('EP.user_id', $user_id);
       return $this->db->get()->result();
   }
   public function get_single_grade_with_group($id){
        
    $this->db->select('G.*,PG.group_code');
    $this->db->from('payscale_category AS G'); 
    $this->db->join('pay_groups AS PG', 'PG.id = G.pay_group_id', 'left');		        
     $this->db->where('G.id', $id);
     return $this->db->get()->row();  
    
}
    public function delete_salary_payment($salary_payment_id) {

        if($salary_payment_id)
        {
            $this->db->query("delete from account_transactions_details where transaction_id in (select id from account_transactions where salary_payment_id= $salary_payment_id)");
            $this->db->where('salary_payment_id', $salary_payment_id);
            $this->db->delete('account_transactions');

            $this->db->where('salary_payment_id', $salary_payment_id);
            $this->db->delete('salary_payment_details');

            $this->db->where('id', $salary_payment_id);
            $this->db->delete('salary_payments');

        }
       
    }
    public function get_approved_paid_leaves($school_id , $user_id, $role_id, $academic_year_id,$month){
        $month_start = "01-".$month;
        $month_start = date("Y-m-1",strtotime($month_start));
        $month_end = date("Y-m-t",strtotime($month_start));
        $this->db->select('A.*,T.paid_leave');
        $this->db->from('leave_applications AS A');
        $this->db->join('leave_types AS T', 'T.id = A.type_id', 'left'); 
        $this->db->where('A.leave_status', 2);
        $this->db->where('A.user_id', $user_id);
        $this->db->where('A.role_id', $role_id);
        $this->db->where('A.school_id', $school_id);
        $this->db->where('T.paid_leave', 1);
        $this->db->where("((date(A.leave_from) >= date('$month_start') && date(A.leave_from) <= date('$month_end') ) or (date(A.leave_to) >= date('$month_start') && date(A.leave_to) <= date('$month_end') ) ) ");

    
        $result= $this->db->get();
		return $result->result();
        
    }
    function duplicate_check($salary_month, $user_id, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('salary_month', $salary_month);
         $this->db->where('payment_status', "paid");
        $this->db->where('user_id', $user_id);
        $this->db->where('reverted !=1');
        return $this->db->get('salary_payments')->num_rows();            
    }
    function duplicate_check_all($salary_month, $user_id, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('salary_month', $salary_month);
        $this->db->where('user_id', $user_id);
        $this->db->where('reverted !=1');
        return $this->db->get('salary_payments')->num_rows();            
    }
    
    function check_unpaid($salary_month, $user_id ){           
        $this->db->where('salary_month', $salary_month);
         $this->db->where('payment_status', "unpaid");
        $this->db->where('user_id', $user_id);
        return $this->db->get('salary_payments')->row();            
    }
    
    public function get_payment_list($school_id, $user_id, $payment_to,$academic_year_id = null){
        $teacher =  $employees=  array();

         if ($payment_to == 'employee' || $payment_to == 'all') {
             
            $this->db->select('SP.*,  E.name, E.photo,  U.username, U.role_id, D.name AS designation, U.status AS login_status ');
            $this->db->from('salary_payments AS SP');
            $this->db->join('employees AS E', 'E.user_id = SP.user_id', 'left'); 
            //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left');            
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->join('designations AS D', 'D.id = E.designation_id', 'left'); 
            if($user_id){
                $this->db->where('SP.user_id', $user_id);
            }
            $this->db->where('E.id >0');
            $this->db->where('SP.school_id', $school_id);
            if($academic_year_id)
            {
                $this->db->where('SP.academic_year_id', $academic_year_id);
            }
            $this->db->order_by('SP.salary_month', 'ASC');
            $result =  $this->db->get();
            $employees= $result ->result();
            
         }
         if ($payment_to == 'teacher'|| $payment_to == 'all'){
           
            $this->db->select('SP.*,  T.name, T.photo,  T.responsibility AS designation, U.username, U.role_id, U.status AS login_status ');
            $this->db->from('salary_payments AS SP');
            $this->db->join('teachers AS T', 'T.user_id = SP.user_id', 'left'); 
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');  
            //$this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left'); 
             if($user_id){
                $this->db->where('SP.user_id', $user_id);
             }
             $this->db->where('T.id >0');

             $this->db->where('SP.school_id', $school_id);

            //  $this->db->where('SP.payment_to', 'teacher'); 
             $this->db->order_by('SP.salary_month', 'ASC');
             $result =  $this->db->get();
            
             $teacher=  $result->result();
             
         }
         if(empty($teacher) && empty($employees)) {
            return array();
        }
        else
        {
           // var_dump($teacher,$employees);
            return array_merge($teacher,$employees);
        }
    }
    
    public function get_single_payment($payment_id, $payment_to) {
        
        if ($payment_to == 'employee') {
             
            $this->db->select('SP.*, S.school_name, E.name, E.photo,  U.username, U.role_id, D.name AS designation, U.status AS login_status ');
            $this->db->from('salary_payments AS SP');
            $this->db->join('employees AS E', 'E.user_id = SP.user_id', 'left'); 
            //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left');            
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->join('designations AS D', 'D.id = E.designation_id', 'left');  
            $this->db->join('schools AS S', 'S.id = SP.school_id', 'left');
            $this->db->where('SP.id', $payment_id);
            // $this->db->where('SP.payment_to', 'employee');
           
            return $this->db->get()->row();
            
         }else{
           
            $this->db->select('SP.*, S.school_name, SG.grade_name, T.name, T.photo,  T.responsibility AS designation, U.username, U.role_id, U.status AS login_status ');
            $this->db->from('salary_payments AS SP');
            $this->db->join('teachers AS T', 'T.user_id = SP.user_id', 'left'); 
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');  
            $this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left'); 
            $this->db->join('schools AS S', 'S.id = SP.school_id', 'left');
            $this->db->where('SP.id', $payment_id);             
            // $this->db->where('SP.payment_to', 'teacher'); 
            return $this->db->get()->row();
             
         }
    }
	public function get_salary_payment_detail($salary_payment_id){
		$this->db->select('SPD.*,PC.name as cat_name,PC.is_deduction_type as type');
		$this->db->from('salary_payment_details AS SPD');
        $this->db->join('payscale_category AS PC', 'PC.id = SPD.payscalecategory_id', 'left');
		$this->db->where('SPD.salary_payment_id', $salary_payment_id);	
		return $this->db->get()->result();		
	}

    public function get_all_payment_user($role_id,$school_id) {
        // echo $role_id.$school_id;
        
        if ($role_id == "teacher"  ||  $role_id == 'all') {
            
            $this->db->select('T.*, T.responsibility AS designation, U.username, U.role_id, U.status AS login_status ');
            $this->db->from('teachers AS T');
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');  
            $this->db->where('T.alumni', '0');
            //$this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left'); 
            $this->db->where('T.school_id', $school_id);
            $teacher= $this->db->get()->result();
            // echo $this->db->last_query();exit; 
            
        } 
        if($role_id == "employee"   ||  $role_id == 'all') { 
            
            $this->db->select('E.*, U.username, U.role_id, D.name AS designation, U.status AS login_status ');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            
            $this->db->join('designations AS D', 'D.id = E.designation_id', 'left'); 
            //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left'); 
            // $this->db->where('E.user_id', $user_id);
            $this->db->where('E.alumni', '0');

            $this->db->where('E.school_id', $school_id);
            $employees=  $this->db->get()->result();
            
            
        } 
       
       
        if(empty($teacher) && empty($employees)) {
            return array();
        }
        else
        {                
            if(!empty($teacher) && !empty($employees))
            {
                return array_merge($employees,$teacher);
            }
            else if(!empty($teacher))
            {
                 return $teacher;
            }
            else if(!empty($employees))
            {
                return $employees;
            }
           // var_dump($teacher,$employees);
            
        }
    }

        function check_month($id){
            $this->db->select('salary_month');
            $this->db->from('salary_payments');
            $this->db->where('user_id', $id);
            return  $this->db->get()->result();


        }
        function check_month1($id){
            $this->db->select('salary_month,user_id');
            $this->db->from('salary_payments');
            $this->db->where('user_id', $id);
            return  $this->db->get()->result();


        }
        public function generate_invoice_no($school_id){		
            $this->db->select('I.*,S.school_code');
            $this->db->from('salary_payments AS I');
            $this->db->join('schools AS S', 'S.id = I.school_id', 'left');               
            $this->db->limit(1);
            $this->db->where("I.school_id",$school_id);      
            $this->db->order_by('I.id', 'desc');
            
            $row= $this->db->get()->row();	
            
            if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
                $arr=explode("/",$row->invoice_no);
                $invoice_no="PAY".$row->school_code."/".($arr[1] +1);
            }
            else{
                $invoice_no="PAY".$row->school_code."/".'1000001';
            }	
            return $invoice_no;	
        }

}
