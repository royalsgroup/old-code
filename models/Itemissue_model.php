<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Itemissue_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        //$this->current_session = $this->setting_model->getCurrentSession();
    }

    // public function get($id = null,$school_id = null) {
    //     $sql = "SELECT schools.school_name,to_user.username as to_user,by_user.username as by_user,item_issue.*,item.name as `item_name`,item.item_category_id,item_category.item_category FROM `item_issue` left JOIN schools on item_issue.school_id=schools.id INNER JOIN item on item.id=item_issue.item_id INNER JOIN item_category on item_category.id=item.item_category_id INNER JOIN users as to_user on to_user.id=item_issue.issue_to INNER JOIN users as by_user on by_user.id=item_issue.issue_by";
	// 	$query= $sql." where 1";
    //         if ($id != null) {
    //             $query =$query." and item_issue.id =".$id;

    //         }
	// 		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
				
	// 			$query =$query." and item_issue.school_id=".$this->session->userdata('school_id');
    //         //$this->db->where('IC.school_id', $this->session->userdata('school_id'));
    //     }
        
    //     if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
	// 		$query =$query." and item_issue.school_id=".$school_id;
    //         //$this->db->where('IC.school_id', $school_id);
    //     }
	// 	if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
	// 		$query =$query." and item_issue.school_id=".$school_id;            
    //     }
	// 	else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){						
	// 		$query =$query." and schools.district_id=".$this->session->userdata('district_id');			
	// 	}
    //     $query = $this->db->query($query);
	// 	if ($id != null) {
    //             return $query->row_array();
    //         }
    //         return $query->result_array();
    // }
    public function get($id = null,$school_id = null,$invoice_id = null, $voucher_id = null, $start_date = null , $end_date = null) {

        $schoolselect = $school_id ? " " : " schools.school_name,";
        if ($id )
        {
            $this->db->select( $schoolselect .'item_issue.*,item.name as `item_name`,`item`.`item_code`,item.item_category_id,item_category.item_category, AT.transaction_no, AT.id as transaction_id,IN.invoice_no
            ,AL1.name as debit_ledger
            ,AL2.name as credit_ledger')->from('item_issue');
        }
        else
        {
            // $this->db->select('schools.school_name,item_issue.*,item.name as `item_name`,`item`.`item_code`,item.item_category_id,item_category.item_category,IN.invoice_no
            // ,AL1.name as debit_ledger
            // ,AL2.name as credit_ledger')->from('item_issue');
            $this->db->select( $schoolselect .'item_issue.*,item.name as `item_name`,`item`.`item_code`,item.item_category_id,item_category.item_category,IN.invoice_no')->from('item_issue');
        }
        if (!$school_id)
        {
            $this->db->join('schools ', 'schools.id = item_issue.school_id');
        }
        $this->db->join('item ', 'item.id = item_issue.item_id');
        $this->db->join('item_category', 'item.item_category_id = item_category.id');
        $this->db->join('item_invoices IN', 'item_issue.invoice_id =  IN.id');
        if ($id )
        {
            $this->db->join('account_transactions AT', 'item_issue.account_transaction_id = AT.id');
            $this->db->join('account_ledgers AL1', 'item_issue.debit_ledger_id = AL1.id');        
            $this->db->join('account_ledgers AL2', 'item_issue.credit_ledger_id = AL2.id'); 
        }
         
		 if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
            $this->db->where('item_issue.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('item_issue.school_id', $school_id);
        }
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
            $this->db->where('item_issue.school_id', $school_id);
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){
			$this->db->where('schools.district_id', $this->session->userdata('district_id'));
		}
        if ($id != null) {
            $this->db->where('item_issue.id', $id);
        } else {
            $this->db->order_by('item_issue.id', 'DESC');
        }
        $this->db->where('item_issue.invoice_id is not null');
        if($invoice_id)
        {
            $this->db->where('item_issue.invoice_id', $invoice_id);
        }
        if($voucher_id)
        {
            $this->db->where('item_issue.voucher_id', $voucher_id);
        }
        if($start_date && $end_date)
        {
            $this->db->where("DATE(item_issue.created_at) BETWEEN '$start_date' AND '$end_date'");
        }
         $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    
    public function  get_invoice($id)
    {
        $this->db->select('I.*
        ,(select name from vouchers where id=AT.voucher_id) as voucher
        ,(select category from vouchers where id=AT.voucher_id) as voucher_category
        ,(select category from account_ledgers where id=I.debit_ledger_id) as debit_ledger_category
        ,(select category from account_ledgers where id=I.credit_ledger_id) as credit_ledger_category
        ,(select name from account_ledgers where id=I.debit_ledger_id) as debit_ledger
        ,(select name from account_ledgers where id=I.credit_ledger_id) as credit_ledger
        ')->from('item_invoices I');
        $this->db->join('account_transactions AT', 'I.account_transaction_id = AT.id');
        $this->db->where('I.id', $id);
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $query->row();
    }
    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('item_issue');
		$message      = DELETE_RECORD_CONSTANT." On item issue id ".$id;
        $action       = "Delete";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
		//======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
        //return $return_value;
        }
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('item_issue', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  item issue id ".$data['id'];
			$action       = "Update";
			$record_id    = $data['id'];
			//$this->log($message, $record_id, $action);
			//======================Code End==============================

			$this->db->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
        } else {
            $this->db->insert('item_issue', $data);
            $insert_id = $this->db->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On item issue id ".$insert_id;
			$action       = "Insert";
			$record_id    = $insert_id;
			$this->log($message, $record_id, $action);
			//echo $this->db->last_query();die;
			//======================Code End==============================

			$this->db->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
			return $insert_id;
        }
    }


      public function get_IssueInventoryReport($start_date,$end_date) {

        $condition=" and date_format(item_issue.issue_date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."'";

        $sql = "SELECT item_issue.*,item.name as `item_name`,item.item_category_id,item_category.item_category ,staff.employee_id,staff.name as staff_name,staff.surname,roles.name FROM `item_issue` INNER JOIN item on item.id=item_issue.item_id INNER JOIN item_category on item_category.id=item.item_category_id INNER JOIN staff on staff.id=item_issue.issue_to INNER JOIN staff_roles on staff_roles.staff_id =staff.id INNER JOIN roles on roles.id= staff_roles.role_id where 1 ".$condition;

        $query = $this->db->query($sql);
        return $query->result_array();

    }

    public function insert1($table,$data){
        // print_r($data);exit;
         $this->db->insert($table,$data);
         print_r($this->db->last_query());exit;
        return $this->db->insert_id();

    }

    function delete($data,$id){
        // print_r($table);exit;  
        $this->db->where('id', $id);
        $result = $this->db->delete('item_issue');
        // print_r($this->db->last_query());exit;
        return $result;
        
    }
    public function generate_invoice_no($school_id){	
		$this->db->select('I.*,S.school_code');
        $this->db->from('item_invoices AS I');
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');  
                     
        $this->db->limit(1);
		$this->db->where("I.school_id",$school_id);      
        $this->db->where("I.invoice_no is not null");  
        $this->db->group_start();      
        $this->db->where("I.invoice_type",'item_issue');      
        $this->db->or_where("I.invoice_type",'issue_item');      
        $this->db->group_end();      

        $this->db->order_by('I.id', 'desc');
        
        $row= $this->db->get()->row();	
       
		if(!empty($row) && isset($row->invoice_no) && $row->invoice_no!= ''){
			$arr=explode("/SI",$row->invoice_no);
			$invoice_no="INV".$row->school_code."/SI".sprintf('%07d', ($arr[1] +1));

            $icount = 0;
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            while(!$check_invoice)
            {
                $arr=explode("/SI",$invoice_no);
			    $invoice_no="INV".$row->school_code."/SI".sprintf('%07d', ($arr[1] +1));;
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                echo $icount;
                if($icount >30)
                {
                    break;
                }
            }

		}
        else{
            $school = $this->get_school_by_id($school_id);
			$invoice_no="INV".$school->school_code."/SI".'0000001';
            $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
            $icount = 0;
            while(!$check_invoice)
            {
                $arr=explode("/SI",$invoice_no);
			    $invoice_no="INV".$school->school_code."/SI".sprintf('%07d',($arr[1] +1));
                $check_invoice = $this->check_invoice_no($invoice_no,$school_id);
                $icount++;
                if($icount >30)
                {
                    break;
                }
            }
		}		
		return $invoice_no;
    }
    public function delete_invoice_data($invoice_id,$account_transaction_id)
    {
            $this->db->query("delete from account_transaction_details where transaction_id=$account_transaction_id");
            $this->db->query("delete from account_transactions where id=$account_transaction_id");
            $this->db->query("delete from item_issue where invoice_id=$invoice_id");
            $this->db->query("delete from item_invoices where id=$invoice_id");
            return true;
    }
    public function check_invoice_no($invoice_no,$school_id ="")
    {
        $this->db->select('I.*');
        $this->db->from('item_invoices AS I');
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
}
