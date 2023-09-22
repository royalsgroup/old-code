<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }

    public function get_fee_type($school_id,$academic_year_id=null){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where("(IH.head_type = 'fee' OR IH.head_type = 'hostel'  OR IH.head_type = 'transport'  OR IH.head_type = 'other' ) "); 
        $this->db->where('IH.school_id', $school_id); 
      
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        return $this->db->get()->result(); 
      
    }

    public function get_income_heads($school_id,$academic_year_id =null, $fee_type= null){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where("(IH.head_type ='fee' or IH.head_type ='transport' or IH.head_type ='hostel' or IH.head_type ='other')");
        $this->db->where('IH.school_id', $school_id);
        $this->db->where('IH.status', 1);
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        if($fee_type)
        {
            $this->db->where('IH.head_type', $fee_type);
        }
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        $result = $this->db->get();
        return $result->row();  
    }
    public function get_income_all_heads($school_id,$academic_year_id =null, $fee_type= null){

        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where("(IH.head_type ='fee' or IH.head_type ='transport' or IH.head_type ='hostel' or IH.head_type ='other')");
        $this->db->where('IH.school_id', $school_id);
        $this->db->where('IH.status', 1);
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        if($fee_type)
        {
            $this->db->where('IH.head_type', $fee_type);
        }
        if($academic_year_id)
        {
            $this->db->where('IH.academic_year_id', $academic_year_id);
        }
        return $this->db->get()->result();      
    }

    public function fee_list($school_id,$income_head_ids,$class_id =null){
        
        $this->db->select('*');
        $this->db->from('fees_amount'); 
        $this->db->where_in('income_head_id', $income_head_ids);  
        
        $this->db->where('school_id', $school_id);
        if($class_id)
        {
            $this->db->where('class_id', $class_id);
        }
        $result = $this->db->get();
        return $result->row();
    }

    public function get_invoice_list_prev($school_id, $student_id, $income_head_id =null){

        $this->db->select('I.*, SC.school_name,  S.name AS student_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        $this->db->where('I.reverted !=1');         

        $this->db->where('I.invoice_type !=', 'income');         
        $this->db->where('I.student_id', $student_id);   
        $this->db->where('I.school_id', $school_id);
        if($income_head_id)
        {
            $this->db->where('I.income_head_id', $income_head_id);

        }
        $this->db->where('SC.status', 1);     
        $this->db->order_by('I.id', 'DESC');  
        return $this->db->get()->result();      
   
    }
   
    public function get_next_emi($income_head_id,$date,$emi_id){
        
        $this->db->select('EF.*,IH.emi_type');
        $this->db->from('emi_fee AS EF'); 
        $this->db->join('income_heads AS IH','EF.income_heads_id = IH.id','left'); 
        $this->db->where('EF.income_heads_id', $income_head_id); 
        $this->db->where("EF.id != $emi_id"); 
        $this->db->where("EF.emi_start_date > '$date'");
        $this->db->order_by('EF.emi_start_date', 'ASC'); 
        $result = $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $result->result(); 	
    }
     public function get_hostel_fee($student_id, $member_id = null){
        
        if($member_id) {
            $this->db->select('R.cost,R.yearly_room_rent');
            $this->db->from('hostel_members AS HM'); 
            $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
            $this->db->where('HM.id', $member_id); 
        } else {
            $this->db->select('R.cost,R.yearly_room_rent');
            $this->db->from('students AS S'); 
            $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
            $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
            $this->db->where('S.id', $student_id); 
            $this->db->where('S.is_hostel_member', 1);
        }
        return $this->db->get()->row(); 
    }
    
        public function get_transport_fee($student_id, $member_id = null){
        if($member_id) {
            $this->db->select('RS.stop_fare, RS.yearly_stop_fare');
            $this->db->from('transport_members AS TM'); 
            $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
            $this->db->where('TM.id', $member_id); 
        } else {
            $this->db->select('RS.stop_fare, RS.yearly_stop_fare');
            $this->db->from('students AS S'); 
            $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
            $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
            $this->db->where('S.id', $student_id); 
            $this->db->where('S.is_transport_member', 1);
        }
         return $this->db->get()->row(); 
         
     }

// ak
   /* public function get_transport_fee($income_head_id){
        
        $this->db->select('transport_amount');
        $this->db->from('income_heads'); 
        $this->db->where('id', $income_head_id); 
       return  $this->db->get()->row(); 
        // echo $this->db->last_query();exit;
    }*/
// ak

    public function get_student_discount($student_id){
        
        $this->db->select('D.*');
        $this->db->from('students AS S'); 
        $this->db->join('discounts AS D', 'D.id = S.discount_id', 'left');
        $this->db->where('S.id', $student_id);         
        return $this->db->get()->row();
    }
    
    public function get_invoice_list($school_id = null, $due = null, $academic_year_id = null){
        
        $this->db->select('I.*, SC.school_name, IH.title AS head,EF.emi_name , S.name AS student_name,S.father_name AS father_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('emi_fee AS EF', 'IH.id = EF.income_heads_id and I.emi_type=EF.id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        
        $this->db->where('I.invoice_type !=', 'income');  
        
        if($due){
            $this->db->where('I.paid_status !=', 'paid');  
            
        }  
        
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
           
        }   
        
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('I.student_id', $this->session->userdata('profile_id'));
       }  
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('I.school_id', $this->session->userdata('school_id'));
       } 
        
        if($academic_year_id){
            $this->db->where('I.academic_year_id', $academic_year_id); 
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}
       
        //$this->db->group_by('I.student_id');    
       //$this->db->order_by('I.modified_at', 'desc  ');
       
       $this->db->order_by('S.name', 'DESC');  
        return $this->db->get()->result(); 
        // print_r($this->db->last_query());exit       
    }
    public function get_invoice_list_total($school_id = null,$due =null,$academic_year_id = null,$search_text='',$start_date = "", $end_date = "", $student_id = ""){
        
        $this->db->select('I.*, SC.school_name, IH.title  AS head,EF.emi_name , S.name AS student_name,S.father_name AS father_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('emi_fee AS EF', 'IH.id = EF.income_heads_id and I.emi_type=EF.id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        $this->db->where('I.invoice_type !=', 'income');  
        
        if($student_id)
        {
            $this->db->where('S.user_id', $student_id);
        }
        if($academic_year_id){
			$this->db->where('I.academic_year_id', $academic_year_id);
		} 
        if($due){
            $this->db->where('I.paid_status !=', 'paid');  
            
        }  
        if($search_text){
            $this->db->group_start();
			$this->db->like('S.name', $search_text);
            $this->db->or_like('S.father_name', $search_text);
            $this->db->or_like('S.admission_no', $search_text);
            $this->db->or_like('I.custom_invoice_id', $search_text);
            $this->db->or_like('I.paid_status', $search_text);
            $this->db->or_like('I.payment_method', $search_text);
            $this->db->or_like('C.name', $search_text);
            $this->db->or_like('IH.title', $search_text);
            $this->db->group_end();
		}      
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
           
        }   
        
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('I.student_id', $this->session->userdata('profile_id'));
       }  
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('I.school_id', $this->session->userdata('school_id'));
       } 
        
        if($start_date)
        {
            $this->db->where("STR_TO_DATE(I.month, '%d-%m-%Y')  >= STR_TO_DATE('$start_date', '%d-%m-%Y') ");
        }
        if($end_date)
        {
            $this->db->where("STR_TO_DATE(I.month, '%d-%m-%Y')  <= STR_TO_DATE('$end_date', '%d-%m-%Y')");
        }
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}
       
       // $this->db->group_by('I.student_id');    
       $result=$this->db->get();

       return $result->num_rows();
        // print_r($this->db->last_query());exit       
    }
    
    public function get_invoice_list_ajax($school_id = null,$due =null,$academic_year_id = null,$start = null, $limit = null,$search_text='',$sort_cloumn=null,$sort_order =null,$start_date = "", $end_date = "", $student_id = ""){
        $this->db->select("I.*, SC.school_name, IH.title AS head,EF.emi_name , S.name AS student_name,S.father_name AS father_name, AY.session_year, C.name AS class_name,S.admission_no ,STR_TO_DATE(I.month,'%d-%m-%Y') as month_date,S.user_id
        ,(select concat(name,'[', category,']') from account_ledgers where id=I.debit_ledger_id) as ledger
        ");
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('emi_fee AS EF', 'IH.id = EF.income_heads_id and I.emi_type=EF.id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        $this->db->where('I.invoice_type !=', 'income');  
        if($student_id)
        {
            $this->db->where('S.user_id', $student_id);
        }
        
        if($academic_year_id){
			$this->db->where('I.academic_year_id', $academic_year_id);
		} 
        if($due){
            $this->db->where('I.paid_status !=', 'paid');  
            
        }  
        if($search_text){
            $this->db->group_start();
			$this->db->like('S.name', $search_text);
            $this->db->or_like('S.father_name', $search_text);
            $this->db->or_like('S.admission_no', $search_text);
            $this->db->or_like('I.custom_invoice_id', $search_text);
            $this->db->or_like('I.paid_status', $search_text);
            $this->db->or_like('I.payment_method', $search_text);
            $this->db->or_like('C.name', $search_text);
            $this->db->or_like('IH.title', $search_text);
            $this->db->group_end();
		}      
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
           
        }   
        if($start_date)
        {
            $this->db->where("STR_TO_DATE(I.month, '%d-%m-%Y')  >= STR_TO_DATE('$start_date', '%d-%m-%Y') ");
        }
        if($end_date)
        {
            $this->db->where("STR_TO_DATE(I.month, '%d-%m-%Y')  <= STR_TO_DATE('$end_date', '%d-%m-%Y')");
        }
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('I.student_id', $this->session->userdata('profile_id'));
       }  
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('I.school_id', $this->session->userdata('school_id'));
       } 
        
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('SC.id', $this->session->userdata('dadmin_school_ids'));
		}
        if ($limit != null && $start != null) {
			$this->db->limit($limit, $start);
    }
    if($sort_cloumn)
    {
        $sort_order  = $sort_order ? $sort_order : "DESC";
        if($sort_cloumn == "I.month")
        {
            $this->db->order_by("month_date",$sort_order );

        }
        else
        {
            $this->db->order_by($sort_cloumn, $sort_order );  
        }
    }
    
    $this->db->order_by("month_date", 'DESC');
       
   // $this->db->order_by('I.id', 'DESC');  
       // $this->db->group_by('I.student_id');    
       $result=$this->db->get();	
    // echo $this->db->last_query();exit ;      

		return $result->result();
         
    }
    public function get_transport_details($iUserID, $iAcademicYearID)
    {
        $this->db->select('route_stop_id,route_id');
        $this->db->from('transport_members AS TM');        
    
        $this->db->where('TM.user_id', $iUserID);       
        $this->db->where("(TM.academic_year_id=$iAcademicYearID or coalesce(TM.academic_year_id,0)=0)");
        $this->db->order_by('TM.academic_year_id', 'desc');       

        return $this->db->get()->row();     
    }
    public function get_hostel_details($iUserID, $iAcademicYearID)
    {
        $this->db->select('room_id, hostel_id');
        $this->db->from('hostel_members AS HM');        
    
        $this->db->where('HM.user_id', $iUserID);       
        $this->db->where("(HM.academic_year_id=$iAcademicYearID or coalesce(HM.academic_year_id,0)=0)");
        $this->db->order_by('HM.academic_year_id', 'desc');       
        $result =  $this->db->get();
        return $result->row();     
    }
    public function get_single_invoice($id){
        
        $this->db->select("I.*,I.created_by as inv_created_by,I.created_at as inv_created_at, AT.cheque_no, AT.transaction_no, AT.bank_name, IH.title AS head,EF.emi_name , I.discount AS inv_discount, I.id AS inv_id , S.*, AY.session_year, C.name AS class_name,S.admission_no, EF.emi_per, I.emi_type emi_type1
        ,(select concat(name,'[', category,']') from account_ledgers where id=I.debit_ledger_id) as ledger");
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('account_transactions AS AT', 'AT.id = I.account_transaction_id', 'left');
        $this->db->join('emi_fee AS EF', ' I.emi_type=EF.id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->where('I.invoice_type !=', 'income');  
        $this->db->where('I.id', $id);       
        $result=$this->db->get();
        // echo $this->db->last_query();
       return $result->row();
    }
	public function get_list_by_invoice_no($invoice_no,$school_id =null, $student_id =null){
		$this->db->select('I.*,IH.title AS head,EF.emi_name , EF.emi_per, I.emi_type emi_type1');
		$this->db->from('invoices AS I'); 
		$this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('emi_fee AS EF', 'I.emi_type=EF.id', 'left');
        if($school_id)
        {
            $this->db->where('I.school_id', $school_id);    
        }
		$this->db->where('I.invoice_no', $invoice_no);      
        if( $student_id) 
       {
        $this->db->where('I.student_id', $student_id);      
       }
       $result=$this->db->get();
    //    echo $this->db->last_query();
      return $result->result();
        // return $this->db->get()->result(); 			
	}
    public function delete_invoice($invoice_id,$invoice_no ="") {

        if($invoice_id)
        {
            $this->db->query("delete from account_transactions_details where transaction_id in (select id from account_transactions where invoice_id= $invoice_id)");
            $this->db->where('invoice_id', $invoice_id);
            $this->db->delete('account_transactions');

         
            if($invoice_no)
            {
                $this->db->where('invoice_no', $invoice_no);
                $this->db->delete('invoices');
            }
            
            $this->db->where('invoice_id', $invoice_id);
            $this->db->delete('transactions');
            $this->db->where('id', $invoice_id);
            $this->db->delete('invoices');

        }
       
    }
    
    public function get_student_list( $school_id, $academic_year_id, $class_id, $student_id = null){
        
        $this->db->select('E.roll_no,  S.id, S.user_id, S.name, S.is_hostel_member, S.is_transport_member');
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);       
        $this->db->where('E.class_id', $class_id);  
        $this->db->where('E.school_id', $school_id);
        $this->db->where('S.status', 1);  
        
        if($student_id > 0){
            $this->db->where('E.student_id', $student_id); 
        }
        
        
        return $this->db->get()->result();   
        //echo $this->db->last_query();
    }
    
    public function get_student_hostel_cost($user_id){
         $this->db->select('R.cost');
        $this->db->from('hostel_members AS HM');        
        $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
        $this->db->where('HM.user_id', $user_id);                  
        return $this->db->get()->row();
    }
    
    public function get_student_transport_fare($user_id){
        
        
        $this->db->select('R.fare');
        $this->db->from('transport_members AS TM');        
        $this->db->join('routes AS R', 'R.id = TM.route_id', 'left');
        $this->db->where('TM.user_id', $user_id);                  
        return $this->db->get()->row();
    }
    
   /* public function get_invoice_log_list($invoice_id){                
        $this->db->select('IL.*, IH.title');
        $this->db->from('invoice_logs AS IL');        
        $this->db->join('income_heads AS IH', 'IH.id = IL.income_head_id', 'left');
        $this->db->where('IL.invoice_id', $invoice_id);
        return $this->db->get()->result();
    }*/    


      public  function harry($school,$class){
   
        $this->db->select('I.*, SC.school_name, IH.title AS head, S.name AS student_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = I.school_id', 'left');
        
        $this->db->where('I.invoice_type !=', 'income');  

        $this->db->where('I.school_id', $school);
        $this->db->where('I.class_id', $class);
        
       //  if($due){
       //      $this->db->where('I.paid_status !=', 'paid');  
       //     ;
       //  }  
        
       //  if($this->session->userdata('role_id') == GUARDIAN){
       //      $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
       //      echo "hello1";
       //  }   
        
       //  if($this->session->userdata('role_id') == STUDENT){
       //      $this->db->where('I.student_id', $this->session->userdata('profile_id'));
       // }  
        
       //  if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
       //      $this->db->where('I.school_id', $this->session->userdata('school_id'));
       //  } 
        
       //  if($academic_year_id){
       //      $this->db->where('I.academic_year_id', $academic_year_id); 
       //  }
        
       //  if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
       //      $this->db->where('I.school_id', $school);
       //      $this->db->where('I.class_id', $class);
       //  }
            
       $this->db->order_by('I.id', 'DESC'); 

       return $this->db->get()->result(); 
        }

      function get_discount_model($id){
        $this->db->select();
        $this->db->from('discounts');
        $this->db->where('school_id',$id);
        $query =  $this->db->get();
        return $query->result_array();
       

      }  

      public function get_student_discount1($id){
        
        $this->db->select('*');
        $this->db->from('discounts'); 
        // $this->db->join('discounts AS D', 'D.id = S.discount_id', 'left');
        $this->db->where('id', $id);         
        return $this->db->get()->row();
    }
    
	public function get_paid_fee_amount($month,$student_id,$income_head_id,$academic_year_id,$class_id){
		$this->db->select('sum(net_amount) as paid_amount, sum(discount) as discount_amount');
        $this->db->from('invoices');
		//$this->db->where('school_id', $school_id);         
		// $this->db->where('class_id', $class_id); 
       // $this->db->where('month', $month);         
       $this->db->where('reverted!=1');         
		$this->db->where('student_id', $student_id);         
		$this->db->where('income_head_id', $income_head_id);  
        // $this->db->where('academic_year_id', $academic_year_id);       
		$this->db->where('paid_status', 'paid');         		
        $result= $this->db->get();
        //echo $this->db->last_query();
        $result = $result->row();
		if(isset($result->paid_amount)){
			return $result->paid_amount + $result->discount_amount;
		}
		else {
			return 0;
		}		
	}
    public function get_paid_emi_amount($student_id,$income_head_id,$academic_year_id,$class_id,$emi_id){
		$this->db->select('sum(net_amount) as paid_amount');
        $this->db->from('invoices');
		//$this->db->where('school_id', $school_id);         
		$this->db->where('class_id', $class_id); 
       // $this->db->where('month', $month);         
       $this->db->where('reverted!=1');         
		$this->db->where('student_id', $student_id);         
		$this->db->where('income_head_id', $income_head_id);  
        // $this->db->where('academic_year_id', $academic_year_id);  
        $this->db->where('emi_type', $emi_id);       
		$this->db->where('paid_status', 'paid');         		
        $result= $this->db->get();
        //echo $this->db->last_query();
        $result = $result->row();
		if(isset($result->paid_amount)){
			return $result->paid_amount;
		}
		else {
			return 0;
		}		
	}
    
    public function get_paid_fee_amount_year($student_id,$income_head_id,$academic_year_id = null,$before_date = ""){
		$this->db->select('sum(net_amount) as paid_amount, sum(discount) as discount_amount');
        $this->db->from('invoices');
		//$this->db->where('school_id', $school_id);         
		//$this->db->where('class_id', $class_id); 
        if($academic_year_id)
        {
             $this->db->where('academic_year_id', $academic_year_id);         
        }
		$this->db->where('student_id', $student_id);         
        $this->db->where('reverted!=1');         
		$this->db->where('income_head_id', $income_head_id);     
        if($before_date)  
        {
            $this->db->where("created_at < '$before_date'");     
        }  
		$this->db->where('paid_status', 'paid');         		
        $result= $this->db->get();
        $result = $result->row();
		if(isset($result->paid_amount)){
			return $result->paid_amount + $result->discount_amount;
		}
		else {
			return 0;
		}		
	}
    public function get_previous_emi_data($invoice_id,$student_id,$income_head_id,$academic_year_id = null){
		$this->db->select('id,emi_type,net_amount,discount');
        $this->db->from('invoices');
		//$this->db->where('school_id', $school_id);         
		//$this->db->where('class_id', $class_id); 
        if($academic_year_id)
        {
             $this->db->where('academic_year_id', $academic_year_id);         
        }
        $this->db->where('reverted!=1');         
		$this->db->where('student_id', $student_id);         
		$this->db->where('income_head_id', $income_head_id);     
            $this->db->where("id <= $invoice_id");     
		$this->db->where('paid_status', 'paid');    
        $this->db->where('coalesce(emi_type,0)!=0');         		
     		
        $result= $this->db->get();
        return $result->result();	
	}
    
	public function generate_invoice_no($school_id){		
		$this->db->select('I.*,S.school_code');
        $this->db->from('invoices AS I');
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');               
		$this->db->where("I.school_id",$school_id);      
        $this->db->where("I.invoice_no is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('I.id', 'desc');
        
        $row = $this->db->get()->row();	
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/",$row->invoice_no);
           
			$invoice_no="INV".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error");
                    redirect("/accounting/invoice");
                    die();
                    break;
                }
            }

		}
		else{
            $icount = 0;
			$invoice_no="INV".$row->school_code."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$row->school_code."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >30)
                {
                    error("Error");
                    redirect("/accounting/invoice");
                    die();
                    break;
                }
            }
		}	
      
		return $invoice_no;
	}
    public function generate_invoice_no_new($school_id){		
		$this->db->select('I.*,S.school_code');
        $this->db->from('invoices AS I');
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');               
		$this->db->where("I.school_id",$school_id);      
        $this->db->where("I.new_format",1);      
        $this->db->where("I.invoice_no is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('I.id', 'desc');
        
        $row = $this->db->get()->row();	
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/",$row->invoice_no);
           
			$invoice_no="INV".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error");
                    redirect("/accounting/invoice");
                    die();
                    break;
                }
            }

		}
		else{
            $icount = 0;
            $row = $this->get_single('schools', array('status'=>1, 'id'=>$school_id));
			$invoice_no="INV".$row->school_code.$row->academic_year_id."/".'1000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$row->school_code.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >30)
                {
                    error("Error");
                    redirect("/accounting/invoice");
                    die();
                    break;
                }
            }
		}	
      
		return $invoice_no;
	}
    public function generate_invoice_id($school_id){		
        return $this->generate_invoice_id_new($school_id);
		$this->db->select('I.*,S.school_code');
        $this->db->from('invoices AS I');
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');               
		$this->db->where("I.school_id",$school_id);      
        $this->db->where("I.custom_invoice_id is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('I.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->custom_invoice_id) && $row->custom_invoice_id!= ''){
			$arr=explode("/",$row->custom_invoice_id);
			$invoice_no="INV".$school_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$school_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error");
                    echo "Error generating; id 1";

                    // redirect("/accounting/invoice");
                    die();
                    break;
                }
            }

		}
		else{
			$invoice_no="INV".$school_id."/".'1000001';
            $icount = 0;

            $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$school_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
                $icount++;
                if($icount >10)
                {
                    error("Error");
                    echo "Error generating; id";

                    // redirect("/accounting/invoice");
                    die();
                    break;
                }
            }
		}		
       
		return $invoice_no;
	}
    public function generate_invoice_id_new($school_id){		
		$this->db->select('I.*,S.school_code,S.academic_year_id');
        $this->db->from('invoices AS I');
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');               
		$this->db->where("I.school_id",$school_id);      
        $this->db->where("I.new_format",1);      
        $this->db->where("I.custom_invoice_id is not null");   
        $this->db->limit(1);
   
        $this->db->order_by('I.id', 'desc');
        
        $row= $this->db->get()->row();	
		if(!empty($row) && isset($row->custom_invoice_id) && $row->custom_invoice_id!= ''){
			$arr=explode("/",$row->custom_invoice_id);
			$invoice_no="INV".$school_id.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
            $icount = 0;
            $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
            while(!$check_invoice)
            {
               
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$school_id.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
                $icount++;
                if($icount >20)
                {
                    error("Error");
                    echo "Error generating; no";
                    // redirect("/accounting/invoice");
                    die();
                    break;
                }
            }

		}
		else
        {
            $row = $this->get_single('schools', array('status'=>1, 'id'=>$school_id));

			$invoice_no="INV".$school_id.$row->academic_year_id."/".'1000001';
            $icount = 0;

            $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
            while(!$check_invoice)
            {

                // redirect("/accounting/invoice");
                $arr=explode("/",$invoice_no);
			    $invoice_no="INV".$school_id.$row->academic_year_id."/". str_pad(($arr[1] +1), 8, '0', STR_PAD_LEFT);
                $check_invoice = $this->check_invoice_id($invoice_no,$school_id);
                $icount++;
                if($icount >10)
                {
                    error("Error");
                    echo "Error generating; no 2";

                    // redirect("/accounting/invoice");
                    die();
                    break;
                }
            }
		}		
       
		return $invoice_no;
	}
    public function check_invoice_no($invoice_no,$school_id ="")
    {
        $this->db->select('I.*');
        $this->db->from('invoices AS I');
        $this->db->where("I.school_id",$school_id);   
        $this->db->limit(1);
        $this->db->where("I.invoice_no",$invoice_no);     
        $row= $this->db->get()->row();
        if(empty($row))
        {
            return true;
        }
        else return false;
    }
    public function check_invoice_id($invoice_no,$school_id ="")
    {
        $this->db->select('I.*');
        $this->db->from('invoices AS I');
        $this->db->where("I.school_id",$school_id);   
        $this->db->limit(1);
        $this->db->where("I.custom_invoice_id",$invoice_no);     
        $row= $this->db->get()->row();
        if(empty($row))
        {
            return true;
        }
        else return false;
    }
    public function get_transport_membership($student_id, $academic_year_id = null, $school_id = null){
        
        $this->db->select('TM.*,S.id as student_id');
        $this->db->from('students AS S'); 
        $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
        $this->db->where('S.id', $student_id); 
        if($school_id) {
            $this->db->where('S.school_id', $school_id); 
        }
        if($academic_year_id) {
            $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
        }
        $this->db->where('TM.id >0');
        return $this->db->get()->row(); 
    }
   
    public function get_hostel_membership($student_id, $academic_year_id = null, $school_id = null){
        
        $this->db->select('HM.*,S.id as student_id');
        $this->db->from('students AS S'); 
        $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
        $this->db->where('S.id', $student_id); 
        if($school_id) {
            $this->db->where('S.school_id', $school_id); 
        }
        if($academic_year_id) {
            $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
        }
        $this->db->where('S.is_hostel_member', 1);
        return $this->db->get()->row(); 
    }
    
    
}
