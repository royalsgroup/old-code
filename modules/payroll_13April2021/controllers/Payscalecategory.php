<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Grade.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Grade
 * @description     : Manage all Salary Grades as per payroll.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Payscalecategory extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Payscalecategory_Model', 'grade', true);            
    }

    
        
    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Salary Grades Listing" user interface                 
     *                        
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index($school_id = null) {
        
        check_permission(VIEW);
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $school_id = $this->session->userdata('school_id');                    
        }   
		  
        $this->data['grades'] = $this->grade->get_cat_list($school_id);   
        $this->data['filter_school_id'] = $school_id;
		$this->data['school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_salary_grade'). ' | ' . SMS);
        $this->layout->view('payscalecategory/index', $this->data);            
       
    }

    
    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Salary Grade" user interface                 
     *                    and store "Salary Grade" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_grade_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_grade_data();

                $insert_id = $this->grade->insert('payscale_category', $data);
                if ($insert_id) {
                    success($this->lang->line('insert_success'));
                    redirect('payroll/payscalecategory/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('payroll/payscalecategory/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['grades'] = $this->grade->get_cat_list();      
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('salary_grade'). ' | ' . SMS);
        $this->layout->view('payscalecategory/index', $this->data);
    }

    
     /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Salary Grade" user interface                 
     *                    with populated "Salary Grade" value 
     *                    and update "Salary Grade" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {       
       
        check_permission(EDIT);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/payscalecategory/index');
        }
                
        if ($_POST) {
            $this->_prepare_grade_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_grade_data();
                $updated = $this->grade->update('payscale_category', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    success($this->lang->line('update_success'));
                    redirect('payroll/payscalecategory/index/'.$data['school_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('payroll/payscalecategory/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['grade'] = $this->grade->get_single('payscale_category', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['grade'] = $this->grade->get_single('payscale_category', array('id' => $id));

            if (!$this->data['grade']) {
                 redirect('payroll/payscalecategory/index');
            }
        }

        $this->data['grades'] = $this->grade->get_cat_list($this->data['grade']->school_id);     
        $this->data['school_id'] = $this->data['grade']->school_id;
        $this->data['filter_school_id'] = $this->data['grade']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('salary_grade'). ' | ' . SMS);
        $this->layout->view('payscalecategory/index', $this->data);
    }

    
    /*****************Function view**********************************
     * @type            : Function
     * @function name   : view
     * @description     : Load user interface with specific Salary Grade data                 
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function view($id = null){
        
        check_permission(VIEW);        
         
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/grade/index');
        }
        
        $this->data['grade'] = $this->grade->get_single('salary_grades', array('id' => $id));   
        
        $this->data['grades'] = $this->grade->get_grade_list();    
        
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('salary_grade'). ' | ' . SMS);
        $this->layout->view('grade/index', $this->data); 
    }
    
    
    
            
           
     /*****************Function get_single_grade**********************************
     * @type            : Function
     * @function name   : get_single_grade
     * @description     : "Load single grade information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_grade(){
        
       $grade_id = $this->input->post('grade_id');
       
       $this->data['grade'] = $this->grade->get_single_grade($grade_id);
       echo $this->load->view('payroll/payscalecategory/get-single-grade', $this->data);
    }

    
     /*****************Function _prepare_grade_validation**********************************
     * @type            : Function
     * @function name   : _prepare_grade_validation
     * @description     : Process "Salary Grade" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_grade_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_grade_name');   
        //$this->form_validation->set_rules('debit_ledger_id', $this->lang->line('debit_ledger_id'), 'trim|required');  
//$this->form_validation->set_rules('credit_ledger_id', $this->lang->line('credit_ledger_id'), 'trim|required');
    }
    
    
     /*****************Function grade_name**********************************
     * @type            : Function
     * @function name   : grade_name
     * @description     : Unique check for "Grade Name" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */  
   public function grade_name(){       
       
      if($this->input->post('id') == '')
      {   
          $grade = $this->grade->duplicate_check($this->input->post('school_id'), $this->input->post('name')); 
          if($grade){
                $this->form_validation->set_message('name',  $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $grade = $this->grade->duplicate_check($this->input->post('school_id'), $this->input->post('name'), $this->input->post('id')); 
          if($grade){
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }   
   }

   
    /*****************Function _get_posted_grade_data**********************************
     * @type            : Function
     * @function name   : _get_posted_grade_data
     * @description     : Prepare "Grade Name" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_grade_data() {
        $items = array();
        $items[] = 'school_id';
		$items[] = 'debit_ledger_id';		
		$items[] = 'credit_ledger_id';				
        $items[] = 'name';
		$items[] = 'pay_group_id';
        $items[] = 'category_type';
        $items[] = 'amount';
        $items[] = 'percentage';
		$items[] = 'round_of_method';
		$items[] = 'max_amount_possible';
		
        
        $data = elements($items, $_POST);  
		if(isset($_POST['is_deduction_type']) && $_POST['is_deduction_type']!=''){
			$data['is_deduction_type']=$_POST['is_deduction_type'];			
		}
		else{
			$data['is_deduction_type']='FALSE';
		}
		if(isset($_POST['unbound_payscale_category']) && $_POST['unbound_payscale_category']!=''){
			$data['unbound_payscale_category']=$_POST['unbound_payscale_category'];			
		}
		else{
			$data['unbound_payscale_category']=0;
		}
		if(isset($_POST['remove_dependancy_from_attendance']) && $_POST['remove_dependancy_from_attendance']!=''){
			$data['remove_dependancy_from_attendance']=$_POST['remove_dependancy_from_attendance'];			
		}
		else{
			$data['remove_dependancy_from_attendance']=0;
		}
		if(isset($_POST['set_max_amount_limit']) && $_POST['set_max_amount_limit']!=''){
			$data['set_max_amount_limit']=$_POST['set_max_amount_limit'];			
		}
		else{
			$data['set_max_amount_limit']=0;
		}
		if(isset($_POST['dependant_payscale_categories']) && !empty($_POST['dependant_payscale_categories'])){
			$data['dependant_payscale_categories']=implode(",",$_POST['dependant_payscale_categories']);
		}
		else{
			$data['dependant_payscale_categories']='';
		}
        if ($this->input->post('id')) {
            
            $data['modified'] = date('Y-m-d H:i:s');
            //$data['modified_by'] = logged_in_user_id();
            
        } else {
            
            //$data['status'] = 1;
            $data['created'] = date('Y-m-d H:i:s');
            //$data['created_by'] = logged_in_user_id();                       
        }

        return $data;
    }

    
    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Salry Grade" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/grade/index');
        }
        
        $grade = $this->grade->get_single('payscale_category', array('id' => $id));
        
        if ($this->grade->delete('payscale_category', array('id' => $id))) {    
            
            create_log('Has been deleted a Salary Grade : '.$grade->grade_name);
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('payroll/payscalecategory/index/'.$grade->school_id);
    }

}
