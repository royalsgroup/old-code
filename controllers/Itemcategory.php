<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ItemCategory extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Itemcategory_Model', 'itemcategory', true);			
        $this->load->model('Itemgroup_Model', 'itemgroup', true);			

    }

     public function index($school_id = null) {
       
        //check_permission(VIEW);         
        //$this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $school_id= $this->session->userdata('school_id');                    
			$condition['school_id'] =$school_id;
			//$this->data['ledgers'] = $this->itemcategory->get_list('account_ledgers', $condition, '', '', '', 'id', 'ASC');
        }   
        if($school_id != null)
        {
            $this->data['itemgroups'] = $this->itemgroup->get_itemgroup_list($school_id);
        }
       
		$this->data['itemcategories'] = $this->itemcategory->get_itemcategory_list($school_id);

        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
        $this->data['themes'] = $this->itemcategory->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_item_category'). ' | ' . SMS);
        $this->layout->view('itemcategory/index', $this->data);            
       
    }
  
    public function add() {

        //check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_category_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_category_data();
				
                $insert_id = $this->itemcategory->insert('item_category', $data);
                if ($insert_id) {
                    
                    //create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('itemcategory');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('itemcategory/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
        if($school_id != null)
        {
            $this->data['itemgroups'] = $this->itemgroup->get_itemgroup_list($school_id);
        }
        $this->data['itemcategories'] = $this->itemcategory->get_itemcategory_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;
        //$this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemcategory->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('category'). ' | ' . SMS);
        $this->layout->view('itemcategory/index', $this->data);
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
            $this->_prepare_category_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_category_data();
                $updated = $this->itemcategory->update('item_category', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('itemcategory');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('itemcategory/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['itemcategory'] = $this->itemcategory->get_single('item_category', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['itemcategory'] = $this->itemcategory->get_single('item_category', array('id' => $id));
				
                if (!$this->data['itemcategory']) {
                     redirect('itemcategory');
                }
            }
        }
$this->data['itemcategories'] = $this->itemcategory->get_itemcategory_list($school_id);

if($this->data['itemcategory']->school_id != null)
{
   
    $this->data['itemgroups'] = $this->itemgroup->get_itemgroup_list($this->data['itemcategory']->school_id);
}
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['school_id'] = $this->data['itemcategory']->school_id;
        $this->data['filter_school_id'] = $this->data['itemcategory']->school_id;   
		$this->data['schools'] = $this->schools;
       // $this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemcategory->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('category'). ' | ' . SMS);
        $this->layout->view('itemcategory/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_category_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      
	  $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('item_category', $this->lang->line('name'), 'trim|required|callback_category_name');      
        $this->form_validation->set_rules('group_id', $this->lang->line('group'), 'trim|required');       
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function category_name() {
        if ($this->input->post('id') == '') {
            $itemcategory = $this->itemcategory->duplicate_check($this->input->post('item_category'));
            if ($itemcategory) {
                $this->form_validation->set_message('item_category', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $itemcategory = $this->itemcategory->duplicate_check($this->input->post('item_category'), $this->input->post('id'));
            if ($itemcategory) {
                $this->form_validation->set_message('item_category', $this->lang->line('already_exist'));
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
    private function _get_posted_category_data() {

        $items = array();
               
		$items[] = 'school_id';
        $items[] = 'item_category'; 
		$items[] = 'description'; 
        $items[] = 'group_id'; 
		//$items[] = 'purchase_ledger_id';
		//$items[] = 'sales_ledger_id';
        $data = elements($items, $_POST); 
		$data['is_active'] ='yes';
		if(isset($_POST['is_fixed_asset_type']) && $_POST['is_fixed_asset_type']=='Yes'){
			$data['is_fixed_asset_type']='Yes';
		}
		else{
			$data['is_fixed_asset_type']='No';
		}
          if ($this->input->post('id')) {
            $data['updated_at'] = date('Y-m-d H:i:s');            
        } else {           
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
                       
        }   

        return $data;
    }
	public function delete($id = null) {        
        
       // check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('itemcategory/index');              
        }
        
        // need to find all child data from database 
       // $skips = array('schools');
        //$tables = $this->db->list_tables();
		$skips=array();
        $tables=array('schools');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->itemcategory->get_list($table, array('district_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('itemcategory/index');
            }
        }    
     
        
        $itemcategory = $this->itemcategory->get_single('districts', array('id' => $id));
        
        if ($this->itemcategory->delete('districts', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('itemcategory/index');
    }

}
