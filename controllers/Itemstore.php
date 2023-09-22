<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ItemStore extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Itemstore_Model', 'itemstore', true);			
    }

     public function index($school_id = null) {
       
        //check_permission(VIEW);  
$this->data['itemstores'] = $this->itemstore->get_itemstore_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;		
        //$this->data['itemstores'] = $this->itemstore->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemstore->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_item_store'). ' | ' . SMS);
        $this->layout->view('itemstore/index', $this->data);            
       
    }
  
    public function add() {

        //check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_store_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_store_data();
				
                $insert_id = $this->itemstore->insert('item_store', $data);
               
                if ($insert_id) {
                    
                    //create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('itemstore');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('itemstore/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
$this->data['itemstores'] = $this->itemstore->get_itemstore_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
        //$this->data['itemstores'] = $this->itemstore->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemstore->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('store'). ' | ' . SMS);
        $this->layout->view('itemstore/index', $this->data);
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
        
        //check_permission(EDIT);
       
        if ($_POST) {
            $this->_prepare_store_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_store_data();
                $updated = $this->itemstore->update('item_store', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('itemstore');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('itemstore/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['itemstore'] = $this->itemstore->get_single('item_store', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['itemstore'] = $this->itemstore->get_single('item_store', array('id' => $id));
				
                if (!$this->data['itemstore']) {
                     redirect('itemstore');
                }
            }
        }
		$this->data['itemstores'] = $this->itemstore->get_itemstore_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
         $this->data['school_id'] = $this->data['itemstore']->school_id;
        $this->data['filter_school_id'] = $this->data['itemstore']->school_id;   
		$this->data['schools'] = $this->schools;	
        //$this->data['itemstores'] = $this->itemstore->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemstore->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('store'). ' | ' . SMS);
        $this->layout->view('itemstore/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_store_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('item_store', $this->lang->line('name'), 'trim|required'); 
		//$this->form_validation->set_rules('code', $this->lang->line('code'), 'trim|required|callback_store_code');    		
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function store_code() {
        if ($this->input->post('id') == '') {
            $itemstore = $this->itemstore->duplicate_check($this->input->post('code'));
            if ($itemstore) {
                $this->form_validation->set_message('store_code', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $itemstore = $this->itemstore->duplicate_check($this->input->post('code'), $this->input->post('id'));
            if ($itemstore) {
                $this->form_validation->set_message('store_code', $this->lang->line('already_exist'));
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
    private function _get_posted_store_data() {

        $items = array();
         $items[] = 'school_id';    
        $items[] = 'item_store'; 
		$items[] = 'code'; 
		$items[] = 'description'; 		
        $data = elements($items, $_POST); 		        

        return $data;
    }
	public function delete($id = null) {        
        
       // check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('itemstore/index');              
        }
        
        // need to find all child data from database 
       // $skips = array('schools');
        //$tables = $this->db->list_tables();
		$skips=array();
        $tables=array('schools');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->itemstore->get_list($table, array('district_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('itemstore/index');
            }
        }    
     
        
        $itemstore = $this->itemstore->get_single('districts', array('id' => $id));
        
        if ($this->itemstore->delete('districts', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('itemstore/index');
    }

}
