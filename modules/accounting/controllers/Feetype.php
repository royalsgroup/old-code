<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Feetype.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Feetype
 * @description     : Manage all Feetype as per accounting term.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Feetype extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Feetype_Model', 'feetype', true);  
    }

    
    
     /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Income Head List" user interface                 
     *                     
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index($school_id = null) {
        
        check_permission(VIEW);
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['classes'] = $this->feetype->get_list('classes', $condition, '', '', '', 'id', 'ASC');  
			 $school_id = $this->session->userdata('school_id');   
        }   
        
        $school = $this->feetype->get_school_by_id($school_id);
        $academic_year = $this->feetype->get_single('academic_years', array('school_id'=>$school_id, 'is_running'=>1,'status'=>1)); 

        $this->data['feetypes'] = $this->feetype->get_fee_type($school_id,@ $academic_year->id);  
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        $this->data['school_id'] = $school_id;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_fee_type'). ' | ' . SMS);
        $this->layout->view('fee_type/index', $this->data);            
       
    }

    
     /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Add new Income Head" user interface                 
     *                    and store "Income Head" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {       
      
        check_permission(EDIT);
        
          
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/feetype/index');   
        }
        
        if ($_POST) {
            $this->_prepare_feetype_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_feetype_data();
                // print_r($data);exit;
                
                $updated = $this->feetype->update('income_heads', $data, array('id' => $this->input->post('id')));

                if($updated){    
                    if (isset($_POST['emi_name']) && count($_POST['emi_name']) > 0) {

            
                            $i = 0;
                            $j = 0;
                            foreach($_POST['emi_name'] as $key => $value){
                                $id =  $_POST['emi_id'][$i];
                                $data1 = [

                                        
                                    'emi_name' =>$value,
                                    'school_id'=> $_POST['school_id'],
                                    'emi_per'=> $_POST['emi_per'][$i],
                                    'income_heads_id'=> $this->input->post('id'),
                                    'emi_start_date'=> $_POST['emi_start_date'][$i],
                                    'emi_end_date'=> $_POST['emi_end_date'][$i],
                                    // 'value' => $data1['value'][$key]
            
                                ];
                                if($id)
                                {
                                    // $this->feetype->insert('emi_fee', $data1);
                                    $this->feetype->update('emi_fee', $data1, array('id' =>$id));
                                }
                                else
                                {
                                    $this->feetype->insert('emi_fee', $data1);
                                }
                                   
                                   
                                    $i++;
                            }
                    } 
                }      


                if ($updated) {
                    
                    create_log('Has been updated a fee type : '. $data['title']);                    
                    $this->_save_fee_amount($this->input->post('id'));
                    success($this->lang->line('update_success'));
                    redirect('accounting/feetype/index/'.$data['school_id']);  
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('accounting/feetype/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['feetype'] = $this->feetype->get_single('income_heads', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['feetype'] = $this->feetype->get_single('income_heads', array('id' => $id));

            if (!$this->data['feetype']) {
                 redirect('accounting/feetype/index');
            }
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['classes'] = $this->feetype->get_list('classes', $condition, '', '', '', 'id', 'ASC');  
        } 

        $this->data['feetypes'] = $this->feetype->get_fee_type($this->data['feetype']->school_id); 
        $this->data['emitypes'] = $this->feetype->get_emi_type($this->data['feetype']->id,'emi_fee');
        // echo "<pre>"; print_r($this->data['emitypes']);exit;  
        $this->data['school_id'] = $this->data['feetype']->school_id;
        $this->data['filter_school_id'] = $this->data['feetype']->school_id;
        $this->data['schools'] = $this->schools;
        
        // echo "<pre>";print_r($this->data);exit;

        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('fee_type'). ' | ' . SMS);
        $this->layout->view('fee_type/index', $this->data);
    }
	// public function add() {

 //        check_permission(ADD);
        
 //        if ($_POST) {
 //            $this->_prepare_feetype_validation();
 //            if ($this->form_validation->run() === TRUE) {
 //                $data = $this->_get_posted_feetype_data();
 //                                    $i = 0;
 //                                    $j = 0;
 //                                    foreach($_POST['emi_name'] as $key => $value){
 //                                        $data2 = [
 //                                             'emi_per'=> $_POST['emi_per'][$i],
                    
 //                                        ];
 //                                        $j = $_POST['emi_per'][$i] + $j;
 //                                        $i++;
                                    
 //                                    }
 //            if($j<=100) {                        
 //                $insert_id = $this->feetype->insert('income_heads', $data);
 //                        if($insert_id){    
 //                                    if (isset($_POST['emi_name']) && count($_POST['emi_name']) > 0) {
        
                            
 //                                            $i = 0;
 //                                            $j = 0;
 //                                            foreach($_POST['emi_name'] as $key => $value){
 //                                                    $data1 = [

 //                                                        'emi_name' =>$value,
 //                                                        'school_id'=> $_POST['school_id'],
 //                                                        'emi_per'=> $_POST['emi_per'][$i],
 //                                                        'income_heads_id'=> $insert_id,
 //                                                        'emi_start_date'=> $_POST['emi_start_date'][$i],
 //                                                        'emi_end_date'=> $_POST['emi_end_date'][$i],
 //                                                        // 'value' => $data1['value'][$key]
                                
 //                                                    ];
 //                                                    $this->feetype->insert('emi_fee', $data1);
 //                                                    $j = $_POST['emi_per'][$i] + $j;
 //                                                    $i++;
 //                                            }
 //                                    } 
 //                            }                           

 //                                if ($insert_id) {
                                    
 //                                    create_log('Has been created a fee type : '. $data['title']);                  
                                
 //                                    $this->_save_fee_amount($insert_id);                    
 //                                    success($this->lang->line('insert_success'));
 //                                    redirect('accounting/feetype/index/'.$data['school_id']);
                                    
 //                                } else {
 //                                    error($this->lang->line('insert_failed'));
 //                                    redirect('accounting/feetype/add');
 //                                }
 //            }else{
 //                error("Please Insert Valid EMI Amount ");
 //                redirect('accounting/feetype/add');
 //                exit;
 //            }                    
                            
                                
 //            } else {
 //                $this->data['post'] = $_POST;
 //            }
 //        }
        
 //        $condition = array();
 //        $condition['status'] = 1;        
 //        if($this->session->userdata('role_id') != SUPER_ADMIN){            
 //            $condition['school_id'] = $this->session->userdata('school_id');        
 //            $this->data['classes'] = $this->feetype->get_list('classes', $condition, '', '', '', 'id', 'ASC');  
 //        } 

 //        $this->data['feetypes'] = $this->feetype->get_fee_type();  
 //        $this->data['schools'] = $this->schools;
        
 //        $this->data['add'] = TRUE;
 //        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('fee_type'). ' | ' . SMS);
 //        $this->layout->view('fee_type/index', $this->data);
 //    }

// AK
public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_feetype_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_feetype_data();
                // print_r($data);exit;
                                    $i = 0;
                                    $j = 0;
                                    foreach($_POST['emi_name'] as $key => $value){
                                        $data2 = [
                                             'emi_per'=> $_POST['emi_per'][$i],
                    
                                        ];
                                        $j = $_POST['emi_per'][$i] + $j;
                                        $i++;
                                    
                                    }
            if($j<=100) {   
                $school = $this->feetype->get_school_by_id($data['school_id']);
                $academic_year = $this->feetype->get_single('academic_years', array('school_id'=>$data['school_id'], 'is_running'=>1,'status'=>1)); 
                $checkgeneralfee =""; 
                $data['financial_year_id'] = $school->financial_year_id;
                $data['academic_year_id'] =  $academic_year->id;
                // var_dump($data);
                // die();
                if(!empty($school) && $data['head_type'] != "other") {
                   
                    $checkgeneralfee = $this->feetype->check_general_fee($data['school_id'],$data['academic_year_id'],$data['head_type']);
                    if($checkgeneralfee)
                    {
                        error("You already have a fee type for current academic year");
                        redirect('accounting/feetype/add');
                        exit;
                    }
                }
              
                $insert_id = $this->feetype->insert('income_heads', $data);
                        if($insert_id){    
                                    if (isset($_POST['emi_name']) && count($_POST['emi_name']) > 0) {
        
                            
                                            $i = 0;
                                            $j = 0;
                                            foreach($_POST['emi_name'] as $key => $value){
                                                    $data1 = [

                                                        'emi_name' =>$value,
                                                        'school_id'=> $_POST['school_id'],
                                                        'emi_per'=> $_POST['emi_per'][$i],
                                                        'income_heads_id'=> $insert_id,
                                                        'emi_start_date'=> $_POST['emi_start_date'][$i],
                                                        'emi_end_date'=> $_POST['emi_end_date'][$i],
                                                        // 'value' => $data1['value'][$key]
                                
                                                    ];
                                                    $this->feetype->insert('emi_fee', $data1);
                                                    $j = $_POST['emi_per'][$i] + $j;
                                                    $i++;
                                            }
                                    } 
                            }                           

                                if ($insert_id) {
                                    
                                    create_log('Has been created a fee type : '. $data['title']);                  
                                
                                    $this->_save_fee_amount($insert_id);                    
                                    success($this->lang->line('insert_success'));
                                    redirect('accounting/feetype/index/'.$data['school_id']);
                                    
                                } else {
                                    error($this->lang->line('insert_failed'));
                                    redirect('accounting/feetype/add');
                                }
            }else{
                error("Please Insert Valid EMI Amount ");
                redirect('accounting/feetype/add');
                exit;
            }                    
                            
                                
            } else {
                $this->data['post'] = $_POST;
            }
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');        
            $this->data['classes'] = $this->feetype->get_list('classes', $condition, '', '', '', 'id', 'ASC');
            $school_id = $this->session->userdata('school_id');   
  
        } 

        $this->data['feetypes'] = $this->feetype->get_fee_type( $school_id);  
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('fee_type'). ' | ' . SMS);
        $this->layout->view('fee_type/index', $this->data);
    }

    // AK
               
     /*****************Function get_single_feetype**********************************
     * @type            : Function
     * @function name   : get_single_feetype
     * @description     : "Load single assignment information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_feetype(){
        
       $feetype_id = $this->input->post('feetype_id');       
        
       $this->data['feetype'] = $this->feetype->get_single_feetype($feetype_id);      
       
       $this->data['classes'] = $this->feetype->get_list('classes', array('school_id'=>$this->data['feetype']->school_id), '', '', '', 'id', 'ASC');  
       echo $this->load->view('fee_type/get-single-feetype', $this->data);
    }
    
    
    /*****************Function _prepare_feetype_validation**********************************
     * @type            : Function
     * @function name   : _prepare_feetype_validation
     * @description     : Process "Incoem Head" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_feetype_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('head_type', $this->lang->line('fee_type'), 'trim|required');   
        $this->form_validation->set_rules('title', $this->lang->line('fee') .' '. $this->lang->line('title'), 'trim|required|callback_title');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
    }
    
    
    
        
    /*****************Function title**********************************
     * @type            : Function
     * @function name   : title
     * @description     : Unique check for "Income head title" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */ 
   public function title()
   {             
      if($this->input->post('id') == '')
      {   
          $feetype = $this->feetype->duplicate_check($this->input->post('school_id'), $this->input->post('title')); 
          if($feetype){
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $feetype = $this->feetype->duplicate_check($this->input->post('school_id'), $this->input->post('title'), $this->input->post('id')); 
          if($feetype){
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }   
   }

   public function delete_emi()
   {
        $emi_id = $this->input->post('emi_id');
        if( $emi_id)
        {
            if ($this->feetype->delete('emi_fee', array('id' => $emi_id))) {
                return true;
            }
        }
       
        return false;

   }
     /*****************Function _get_posted_feetype_data**********************************
     * @type            : Function
     * @function name   : _get_posted_feetype_data
     * @description     : Prepare "Income Head" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_feetype_data() {

        $items = array();
        $items[] = 'school_id';
		$items[] = 'credit_ledger_id';
		$items[] = 'refund_ledger_id';
		$items[] = 'voucher_id';
        $items[] = 'emi_type';
        $items[] = 'title';
        $items[] = 'head_type';
        $items[] = 'note';
        $data = elements($items, $_POST);  
		if(isset($_POST['refundable']) && $_POST['refundable']==1){
			$data['refundable']=1;
		}
		else{
			$data['refundable']=0;
		}
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
         
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();                       
        }

        return $data;
    }

    
    
    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Income head" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/feetype/index');   
        }
        
        $fee_type = $this->feetype->get_single('income_heads', array('id' => $id));
        
        if ($this->feetype->delete('income_heads', array('id' => $id))) {
            
            $this->feetype->delete('fees_amount', array('income_head_id' => $id));            
            create_log('Has been deleted a fee type : '. $fee_type->title);            
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('accounting/feetype/index/'.$fee_type->school_id);
    }
    
    
    
    private function _save_fee_amount($income_head_id){
        
        if($this->input->post('head_type') == 'fee' || $this->input->post('head_type') == 'other'){
        
            foreach($this->input->post('class_id') as $key=>$value){

                $data = array();
                $exist = '';
                //$amount_id = @$this->input->post('amount_id')[$key];
                $amount_id = @$_POST['amount_id'][$key];

                if($amount_id){
                   $exist = $this->feetype->get_single('fees_amount', array('class_id'=>$key, 'id'=>$amount_id)); 

                } 

                //$data['fee_amount'] = $this->input->post('fee_amount')[$key];
                $data['fee_amount'] = @$_POST['fee_amount'][$key];
                $data['school_id'] = $this->input->post('school_id');

                if ($this->input->post('id') && $exist) {                

                    $data['modified_at'] = date('Y-m-d H:i:s');
                    $data['modified_by'] = logged_in_user_id();                
                    $this->feetype->update('fees_amount', $data, array('id'=>$exist->id));

                } else {

                    $data['income_head_id'] = $income_head_id;
                    $data['class_id'] = $key;                
                    $data['status'] = 1;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = logged_in_user_id(); 
                    $this->feetype->insert('fees_amount', $data);
                }
            }

        }else{ 
            
             $this->feetype->delete('fees_amount', array('income_head_id'=>$income_head_id));
        }
    }
    
    
    /* Ajax */
    public function get_fee_head_by_school(){
       $school_id = $this->input->post('school_id');
       $this->data['fee_type_id'] = $this->input->post('fee_type_id');
       
       $this->data['classes'] = $this->feetype->get_list_new('classes', array('school_id'=>$school_id), '', '', '', 'id', 'ASC');  
       echo $this->load->view('fee_type/get-classes', $this->data);
    }
	

}
