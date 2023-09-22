<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Paygroups extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Paygroups_Model', 'paygroups', true);			
    }

     public function index($school_id = null) {
              
		$this->data['paygroups'] = $this->paygroups->get_paygroup_list($school_id);
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		
        $this->data['themes'] = $this->paygroups->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage')." ".$this->lang->line('pay_group'). ' | ' . SMS);
        $this->layout->view('paygroup/index', $this->data);            
       
    }    
	public function add() {

         //check_permission(ADD);
        if ($_POST) {
            $this->_prepare_paygroup_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_paygroup_data();
                $insert_id = $this->paygroups->insert('pay_groups', $data);
                if ($insert_id) {                    
                    success($this->lang->line('insert_success'));
                    redirect('payroll/paygroups/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('payroll/paygroups/add');
                }
            } else {
                $this->data = $_POST;
            }
        }

        //$this->data['designations'] = $this->designation->get_designation(); 
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('pay_group'). ' | ' . SMS);
        $this->layout->view('paygroup/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Designation" user interface                 
    *                    with populate "Designation" value 
    *                    and process to update "Designation" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {       

        // check_permission(EDIT);
        if ($_POST) {
            $this->_prepare_paygroup_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_paygroup_data();
                $updated = $this->paygroups->update('pay_groups', $data, array('id' => $this->input->post('id')));

                if ($updated) {                   
                    success($this->lang->line('update_success'));
                    redirect('payroll/paygroups/index/'.$data['school_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('payroll/paygroups/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['paygroup'] = $this->paygroups->get_single('pay_groups', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['paygroup'] = $this->paygroups->get_single('pay_groups', array('id' => $id));

                if (!$this->data['paygroup']) {
                     redirect('payroll/paygroups/index');
                }
            }
        }

        $this->data['school_id'] = $this->data['paygroup']->school_id;
        $this->data['filter_school_id'] = $this->data['paygroup']->school_id;
        $this->data['schools'] = $this->schools;
		$this->data['paygroups'] = $this->paygroups->get_paygroup_list($this->data['paygroup']->school_id);        
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('pay_group'). ' | ' . SMS);
        $this->layout->view('paygroup/index', $this->data);
    }

        
    /*****************Function _prepare_designation_validation**********************************
    * @type            : Function
    * @function name   : _prepare_designation_validation
    * @description     : Process "Designation" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_paygroup_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_name');
        $this->form_validation->set_rules('group_code', $this->lang->line('code'), 'trim|required|callback_code');
    }

                    
    /*****************Function name**********************************
    * @type            : Function
    * @function name   : name
    * @description     : Unique check for "Designation Name" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function name() {
        if ($this->input->post('id') == '') {
            $paygroup = $this->paygroups->duplicate_check($this->input->post('school_id'), $this->input->post('name'));
            if ($paygroup) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $paygroup = $this->paygroups->duplicate_check($this->input->post('school_id'), $this->input->post('name'), $this->input->post('id'));
            if ($paygroup) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
	public function code() {
        if ($this->input->post('id') == '') {
            $paygroup = $this->paygroups->duplicate_check_code($this->input->post('school_id'), $this->input->post('group_code'));
            if ($paygroup) {
                $this->form_validation->set_message('code', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $paygroup = $this->paygroups->duplicate_check_code($this->input->post('school_id'), $this->input->post('group_code'), $this->input->post('id'));
            if ($paygroup) {
                $this->form_validation->set_message('code', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
       
    /*****************Function _get_posted_designation_data**********************************
    * @type            : Function
    * @function name   : _get_posted_designation_data
    * @description     : Prepare "Designation" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_paygroup_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'name';
		$items[] = 'group_code';
        $data = elements($items, $_POST);                             

        return $data;
    }

    
        
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Designation" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {
        
       // check_permission(DELETE);
         
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('payroll/paygroups/index');        
        }
        
        $paygroup = $this->paygroups->get_single('pay_groups', array('id' => $id));
        
        if ($this->paygroups->delete('pay_groups', array('id' => $id))) { 
            
            //create_log('Has been deleted a Designation : '.$designation->name);
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('payroll/paygroups/index/'.$paygroup->school_id);
    }

}
