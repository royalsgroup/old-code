<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EmploymentTypes extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('EmploymentTypes_Model', 'employment_types', true);	
    }

     public function index($school_id = null) {
              
		 $this->data['employment_types'] = $this->employment_types->get_employment_type_list($school_id);
        //$this->data['employment_types']= $this->employment_types->get_payscale_category_by_school('employment_types' , $school_id);
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){           $school_id= $this->session->userdata('school_id');                    
            $condition['school_id'] = $school_id;
        }  
		
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		
        $this->data['themes'] = $this->employment_types->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage')." ".$this->lang->line('employment_types'). ' | ' . SMS);
        $this->layout->view('employment_types/index', $this->data);            
       
    }   
	public function add() {

         //check_permission(ADD);
        if ($_POST) {
            $this->_prepare_employmenttype_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_employmenttype_data();
                $insert_id = $this->employment_types->insert('employment_types', $data);
                if ($insert_id) {                    
                    success($this->lang->line('insert_success'));
                    redirect('hrm/employmentTypes/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('hrm/employmentTypes/add');
                }
            } else {
                $this->data = $_POST;
            }
        }

        //$this->data['designations'] = $this->designation->get_designation(); 
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('employment_types'). ' | ' . SMS);
        $this->layout->view('employment_types/index', $this->data);
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
            $this->_prepare_employmenttype_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_employmenttype_data();
                $updated = $this->employment_types->update('employment_types', $data, array('id' => $this->input->post('id')));

                if ($updated) {                   
                    success($this->lang->line('update_success'));
                    redirect('hrm/employmentTypes/index/'.$data['school_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('hrm/employmentTypes/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['employment_type'] = $this->employment_types->get_single('employment_types', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['employment_type'] = $this->employment_types->get_single('employment_types', array('id' => $id));

                if (!$this->data['employment_type']) {
                     redirect('hrm/employmentTypes/index');
                }
            }
        }

        $this->data['school_id'] = $this->data['employment_type']->school_id;
        $this->data['filter_school_id'] = $this->data['employment_type']->school_id;
        $this->data['schools'] = $this->schools;
		$this->data['employment_types'] = $this->employment_types->get_employment_type_list($this->data['employment_type']->school_id);
		
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('employment_types'). ' | ' . SMS);
        $this->layout->view('employment_types/index', $this->data);
    }

        
    /*****************Function _prepare_designation_validation**********************************
    * @type            : Function
    * @function name   : _prepare_designation_validation
    * @description     : Process "Designation" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_employmenttype_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_name');        
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
            $employment_type = $this->employment_types->duplicate_check($this->input->post('school_id'), $this->input->post('name'));
            if ($paygroup) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $employment_type = $this->employment_types->duplicate_check($this->input->post('school_id'), $this->input->post('name'), $this->input->post('id'));
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
    /*****************Function _get_posted_designation_data**********************************
    * @type            : Function
    * @function name   : _get_posted_designation_data
    * @description     : Prepare "Designation" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_employmenttype_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'name';		
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
             redirect('hrm/employmentTypes/index');        
        }
        
        $employment_type = $this->employment_types->get_single('employment_types', array('id' => $id));
        
        if ($this->employment_types->delete('employment_types', array('id' => $id))) { 
            
            //create_log('Has been deleted a Designation : '.$designation->name);
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('hrm/employmentTypes/index/'.$employment_type->school_id);
    }	

}
