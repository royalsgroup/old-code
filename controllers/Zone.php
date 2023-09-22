<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Zone extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Zone_Model', 'zone', true);			
    }

     public function index() {
        check_permission(VIEW);
        // if(!$this->session->userdata('role_id') == SUPER_ADMIN){
		// 	//error($this->lang->line('insert_failed'));
		// 	redirect('dashboard');
		// }
		$this->data['zone'] = $this->zone->get_zone_list();		
		$this->data['states'] = $this->zone->get_list('states', array(), '','', '', 'id', 'ASC');		
        $this->data['themes'] = $this->zone->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage')." ".$this->lang->line('zone'). ' | ' . SMS);
        $this->layout->view('zone/index', $this->data);            
       
    }
  
    public function add() {

        check_permission(ADD);
        // if(!$this->session->userdata('role_id') == SUPER_ADMIN){
		// 	//error($this->lang->line('insert_failed'));
		// 	redirect('dashboard');
		// }
        if ($_POST) {
            $this->_prepare_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_data();
				
                $insert_id = $this->zone->insert('zone', $data);
                if ($insert_id) {
                    
                    success($this->lang->line('insert_success'));
                    redirect('zone');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('zone/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
		$this->data['zone'] = $this->zone->get_zone_list();		
		$this->data['states'] = $this->zone->get_list('states', array(), '','', '', 'id', 'ASC');	
        $this->data['themes'] = $this->zone->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('zone'). ' | ' . SMS);
        $this->layout->view('zone/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Academic School" user interface                 
    *                    with populated "Academic School" value 
    *                    and update "Academic School" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {   
        
        // if(!$this->session->userdata('role_id') == SUPER_ADMIN){
		// 	//error($this->lang->line('insert_failed'));
		// 	redirect('dashboard');
		// }
        check_permission(EDIT);
       
        if ($_POST) {
            $this->_prepare_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_data();
                $updated = $this->zone->update('zone', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('zone');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('zone/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['detail'] = $this->zone->get_single('zone', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['detail'] = $this->zone->get_single('zone', array('id' => $id));
				
                if (!$this->data['detail']) {
                     redirect('zone');
                }
            }
        }
		$this->data['zone'] = $this->zone->get_zone_list();		
		$this->data['states'] = $this->zone->get_list('states', array(), '','', '', 'id', 'ASC');	
        $this->data['themes'] = $this->zone->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('zone'). ' | ' . SMS);
        $this->layout->view('zone/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      	  
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_name');
		$this->form_validation->set_rules('state_id', $this->lang->line('state'), 'trim|required');       
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function name() {
        if ($this->input->post('id') == '') {
            $zone = $this->zone->duplicate_check($this->input->post('name'));
            if ($zone) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $zone = $this->zone->duplicate_check($this->input->post('name'), $this->input->post('id'));
            if ($zone) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
    
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_data() {

        $items = array();               	
        $items[] = 'name'; 
		$items[] = 'state_id'; 				
		
        $data = elements($items, $_POST); 
		 

        return $data;
    }
	public function delete($id = null) {        
        // if(!$this->session->userdata('role_id') == SUPER_ADMIN){
		// 	//error($this->lang->line('insert_failed'));
		// 	redirect('dashboard');
		// }
       check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('zone/index');              
        }     
		$skips=array();
        $tables=array('subzone');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->zone->get_list($table, array('zone_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('zone/index');
            }
        }                
        
        if ($this->zone->delete('zone', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('zone/index');
    }

}
