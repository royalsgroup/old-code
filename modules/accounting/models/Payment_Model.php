<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }

    public function get_single_invoice($id){
        
        $this->db->select('I.*,  IH.title AS head,EF.emi_name , I.discount AS inv_discount, I.id AS inv_id , S.*, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('emi_fee AS EF', 'IH.id = EF.income_heads_id and I.emi_type=EF.id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->where('I.invoice_type !=', 'income');  
        $this->db->where('I.id', $id);       
       
        return $this->db->get()->row();        
    }
    public function get_invoice_amount($invoice_id){
        $this->db->select('I.*, SUM(T.amount) AS paid_amount');
        $this->db->from('invoices AS I');        
        $this->db->join('transactions AS T', 'T.invoice_id = I.id', 'left');
        $this->db->where('I.id', $invoice_id);         
        return $this->db->get()->row(); 
    }
}
