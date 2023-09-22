<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class District extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('District_Model', 'district', true);			
    }

     public function index() {
        if(!$this->session->userdata('role_id') == SUPER_ADMIN){
			//error($this->lang->line('insert_failed'));
			redirect('dashboard');
		}
        check_permission(VIEW);

		$this->data['district'] = $this->district->get_district_list();		
		$this->data['states'] = $this->district->get_list('states', array(), '','', '', 'id', 'ASC');		
        $this->data['themes'] = $this->district->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage')." ".$this->lang->line('district'). ' | ' . SMS);
        $this->layout->view('district/index', $this->data);            
       
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
				
                $insert_id = $this->district->insert('districts', $data);
                if ($insert_id) {
                    
                    success($this->lang->line('insert_success'));
                    redirect('district');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('district/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
		$this->data['district'] = $this->district->get_district_list();		
		$this->data['states'] = $this->district->get_list('states', array(), '','', '', 'id', 'ASC');	
        $this->data['themes'] = $this->district->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('district'). ' | ' . SMS);
        $this->layout->view('district/index', $this->data);
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
                $updated = $this->district->update('districts', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('district');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('district/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['detail'] = $this->district->get_single_district($this->input->post('id'));
            }
        } else {
            if ($id) {
                $this->data['detail'] = $this->district->get_single_district($id);
				
                if (!$this->data['detail']) {
                     redirect('district');
                }
            }
        }
		$this->data['district'] = $this->district->get_district_list();		
		$this->data['states'] = $this->district->get_list('states', array(), '','', '', 'id', 'ASC');	
        $this->data['themes'] = $this->district->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('district'). ' | ' . SMS);
        $this->layout->view('district/index', $this->data);
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
		$this->form_validation->set_rules('zone_id', $this->lang->line('zone'), 'trim|required');       
		$this->form_validation->set_rules('subzone_id', $this->lang->line('subzone'), 'trim|required');       
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
            $district = $this->district->duplicate_check($this->input->post('name'));
            if ($district) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $district = $this->district->duplicate_check($this->input->post('name'), $this->input->post('id'));
            if ($district) {
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
		$items[] = 'subzone_id'; 				
		
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
             redirect('district/index');              
        }
        
        // need to find all child data from database 
       // $skips = array('schools');
        //$tables = $this->db->list_tables();
		$skips=array();
        $tables=array('blocks');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->district->get_list($table, array('district_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('district/index');
            }
        }    
     
            
        
        if ($this->district->delete('districts', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('district/index');
    }

}
