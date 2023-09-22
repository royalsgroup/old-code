<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ItemSupplier extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Itemsupplier_Model', 'itemsupplier', true);			
    }

     public function index($school_id = null) {
       
        //check_permission(VIEW);         
        $this->data['itemsuppliers'] = $this->itemsupplier->get_itemsupplier_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;
        $this->data['themes'] = $this->itemsupplier->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_item_supplier'). ' | ' . SMS);
        $this->layout->view('itemsupplier/index', $this->data);            
       
    }
  
    public function add() {

        //check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_supplier_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_supplier_data();
				
                $insert_id = $this->itemsupplier->insert('item_supplier', $data);
                if ($insert_id) {
                    
                    //create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('itemsupplier');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('itemsupplier/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
$this->data['itemsuppliers'] = $this->itemsupplier->get_itemsupplier_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;       
        $this->data['themes'] = $this->itemsupplier->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('supplier'). ' | ' . SMS);
        $this->layout->view('itemsupplier/index', $this->data);
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
            $this->_prepare_supplier_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_supplier_data();
                $updated = $this->itemsupplier->update('item_supplier', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('itemsupplier');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('itemsupplier/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['itemsupplier'] = $this->itemsupplier->get_single('item_supplier', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['itemsupplier'] = $this->itemsupplier->get_single('item_supplier', array('id' => $id));
				
                if (!$this->data['itemsupplier']) {
                     redirect('itemsupplier');
                }
            }
        }
$this->data['itemsuppliers'] = $this->itemsupplier->get_itemsupplier_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
       $this->data['school_id'] = $this->data['itemsupplier']->school_id;
        $this->data['filter_school_id'] = $this->data['itemsupplier']->school_id;      
		$this->data['schools'] = $this->schools;
        //$this->data['itemsuppliers'] = $this->itemsupplier->get_list('item_supplier', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemsupplier->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('store'). ' | ' . SMS);
        $this->layout->view('itemsupplier/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_supplier_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('item_supplier', $this->lang->line('name'), 'trim|required');     		
    }

               
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_supplier_data() {

        $items = array();
               
		$items[] = 'school_id';
        $items[] = 'item_supplier'; 
		$items[] = 'phone'; 
		$items[] = 'email'; 
		$items[] = 'address'; 
		$items[] = 'contact_person_name'; 
		$items[] = 'contact_person_phone'; 
		$items[] = 'contact_person_email'; 
		$items[] = 'description'; 		
        $data = elements($items, $_POST); 		        

        return $data;
    }
	public function delete($id = null) {        
        
       // check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('itemsupplier/index');              
        }
        
        // need to find all child data from database 
       // $skips = array('schools');
        //$tables = $this->db->list_tables();
		$skips=array();
        $tables=array('schools');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->itemsupplier->get_list($table, array('district_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('itemsupplier/index');
            }
        }    
     
        
        $itemsupplier = $this->itemsupplier->get_single('districts', array('id' => $id));
        
        if ($this->itemsupplier->delete('districts', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('itemsupplier/index');
    }

}
