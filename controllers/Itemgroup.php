<?php


if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ItemGroup extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Itemgroup_Model', 'itemgroup', true);			
    }

     public function index($school_id = null) {
       
        check_permission(VIEW);         
        //$this->data['itemcategories'] = $this->itemgroup->get_list('item_group', array(), '','', '', 'id', 'ASC');
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $school_id= $this->session->userdata('school_id');                    
        }   
        $this->data['itemgroups'] = $this->itemgroup->get_itemgroup_list($school_id);
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
        $this->data['themes'] = $this->itemgroup->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_item_group'). ' | ' . SMS);
        $this->layout->view('itemgroup/index', $this->data);            
       
    }
  
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {

            $this->_prepare_group_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_group_data();
				
                $insert_id = $this->itemgroup->insert('item_groups', $data);
                if ($insert_id) {
                    
                    //create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('itemgroup');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('itemgroup/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
        $this->data['itemgroups'] = $this->itemgroup->get_itemgroup_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;
        //$this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemgroup->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('category'). ' | ' . SMS);
        $this->layout->view('itemgroup/index', $this->data);
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
            $this->_prepare_group_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_group_data();
                $updated = $this->itemgroup->update('item_groups', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('itemgroup');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('itemgroup/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['itemgroup'] = $this->itemgroup->get_single('item_groups', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['itemgroup'] = $this->itemgroup->get_single('item_groups', array('id' => $id));
				
                if (!$this->data['itemgroup']) {
                     redirect('itemgroup');
                }
            }
        }
        $this->data['itemgroups'] = $this->itemgroup->get_itemgroup_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['school_id'] = $this->data['itemgroup']->school_id;
        $this->data['filter_school_id'] = $this->data['itemgroup']->school_id;   
		$this->data['schools'] = $this->schools;
       // $this->data['itemcategories'] = $this->itemgroup->get_list('item_group', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->itemgroup->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('group'). ' | ' . SMS);
        $this->layout->view('itemgroup/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_group_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      
	  $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim');   
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_group_name');       
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function group_name() {
        if ($this->input->post('id') == '') {
            $itemgroup = $this->itemgroup->duplicate_check($this->input->post('name'),null,$this->input->post('school_id'));
            if ($itemgroup) {
                $this->form_validation->set_message('item_group', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $itemgroup = $this->itemgroup->duplicate_check($this->input->post('name'), $this->input->post('id'),$this->input->post('school_id') );
            if ($itemgroup) {
                $this->form_validation->set_message('item_group', $this->lang->line('already_exist'));
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
    private function _get_posted_group_data() {

        $items = array();
               
		$items[] = 'school_id';
        $items[] = 'name'; 
		$items[] = 'description'; 
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
             redirect('itemgroup/index');              
        }
        
        // need to find all child data from database 
       // $skips = array('schools');
        //$tables = $this->db->list_tables();
		$skips=array();
        $tables=array('item_category');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->itemgroup->get_list($table, array('group_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('itemgroup/index');
            }
        }    
     
        
        
        if ($this->itemgroup->delete('item_groups', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('itemgroup/index');
    }

}
