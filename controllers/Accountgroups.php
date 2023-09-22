<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class AccountGroups extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Accountgroups_Model', 'accountgroups', true);			
    }

     public function index($school_id = null) {
        $category = null;
        if(!empty($_POST)){		   	  
            if($this->input->post('school_id')>=0){			   
                $school_id=$this->input->post('school_id');
            }
            if($this->input->post('category')){
                $category=$this->input->post('category');			   
            }		   
        }
       
        
        //check_permission(VIEW);         
        //$this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');		
		
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
			$school_id=$this->session->userdata('school_id'); 
            $condition['school_id'] = $school_id;                    
        }   
		
        $school = $this->accountgroups->get_school_by_id($school_id); 	
        if(empty($_POST) && !$category){		
            $category = $school->category;
        }
            $this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list($school_id,$category);	
		$financial_year= $this->accountgroups->get_single('financial_years', array('school_id' => $school_id,'is_running'=>1));
		$this->data['financial_year']=$financial_year;
		$this->data['school_info'] = $school; 
       
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;
	
		$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		
        $this->data['themes'] = $this->accountgroups->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_account_groups'). ' | ' . SMS);
        $this->layout->view('accountgroups/index', $this->data);            
       
    }
  
    public function add() {

        //check_permission(ADD);
        $school_id = '';

        if(!empty($_POST)){		   	  
            if($this->input->post('school_id')>=0){			   
                $school_id=$this->input->post('school_id');
            }
            if($this->input->post('category')){
                $category=$this->input->post('category');			   
            }		   
        }
       
        
        //check_permission(VIEW);         
        //$this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');		
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
			$school_id=$this->session->userdata('school_id'); 
            $condition['school_id'] = $school_id;                    
        }   
		
        if ($_POST) {
            $this->_prepare_group_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_group_data();
				
                $insert_id = $this->accountgroups->insert('account_groups', $data);
                if ($insert_id) {
                    
                    //create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('accountgroups');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('accountgroups/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
        $this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;
		$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		        
        $this->data['themes'] = $this->accountgroups->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('group'). ' | ' . SMS);
        $this->layout->view('accountgroups/index', $this->data);
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
                $updated = $this->accountgroups->update('account_groups', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('accountgroups');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('accountgroups/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['accountgroup'] = $this->accountgroups->get_single('account_groups', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['accountgroup'] = $this->accountgroups->get_single('account_groups', array('id' => $id));
				
                if (!$this->data['accountgroup']) {
                     redirect('accountgroups');
                }
            }
        }
		$this->data['accountgroups'] = $this->accountgroups->get_accountgroup_list($school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['school_id'] = $this->data['accountgroup']->school_id;
        $this->data['filter_school_id'] = $this->data['accountgroup']->school_id;   
		$this->data['schools'] = $this->schools;
       // $this->data['itemcategories'] = $this->itemcategory->get_list('item_category', array(), '','', '', 'id', 'ASC');
	   $this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');		        
        $this->data['themes'] = $this->accountgroups->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('category'). ' | ' . SMS);
        $this->layout->view('accountgroups/index', $this->data);
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
      
	  $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
      $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required');   
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_group_name');
		$this->form_validation->set_rules('type_id', $this->lang->line('type'), 'trim|required');       
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
            $accountgroup = $this->accountgroups->duplicate_check($this->input->post('account_groups'));
            if ($accountgroup) {
                $this->form_validation->set_message('account_groups', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $accountgroup = $this->accountgroups->duplicate_check($this->input->post('account_groups'), $this->input->post('id'));
            if ($accountgroup) {
                $this->form_validation->set_message('account_groups', $this->lang->line('already_exist'));
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
		$items[] = 'type_id'; 		
		$items[] = 'base_id'; 
		$items[] = 'group_code'; 		
        $items[] = 'category';

        $data = elements($items, $_POST); 
		if(isset($_POST['is_primary'])){
			$data['is_primary']=$_POST['is_primary'];
		}
		else{
			$data['is_primary']=0;
		}	
		if(isset($_POST['is_readonly'])){
			$data['is_readonly']=$_POST['is_readonly'];
		}
		else{
			$data['is_readonly']=0;
		}		
          if ($this->input->post('id')) {
            $data['modified'] = date('Y-m-d H:i:s');            
        } else {           
            $data['created'] = date('Y-m-d H:i:s');
            $data['modified'] = date('Y-m-d H:i:s');
                       
        }   

        return $data;
    }
	public function delete($id = null) {        
        
       // check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('accountgroups');              
        }
        
        // need to find all child data from database 
       // $skips = array('schools');
        //$tables = $this->db->list_tables();
		$skips=array();
        $tables=array('account_ledgers');
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
            
            $child_exist =$this->accountgroups->get_list($table, array('account_group_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('accountgroups/index');
            }
        }    
     
        
        //$itemcategory = $this->itemcategory->get_single('districts', array('id' => $id));
        
        if ($this->accountgroups->delete('account_groups', array('id' => $id))) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('accountgroups/index');
    }
	public function delete_multiple(){
				
		if(!empty($_POST['checkId'])){
			foreach($_POST['checkId'] as $lid){
				 $tables=array('account_ledgers');
				 foreach ($tables as $table) {
					 $child_exist =$this->accountgroups->get_list($table, array('account_group_id'=>$lid), '','', '', 'id', 'ASC');
						if(!empty($child_exist)){
							 error($this->lang->line('pls_remove_child_data'));
							 redirect('accountgroups/index');
						}
					//$this->accountgroups->delete($table, array('account_group_id' => $lid));                        
				} 
				foreach ($tables as $table) {
					$this->accountgroups->delete($table, array('account_group_id' => $lid));
				}	
				$this->accountgroups->delete('account_groups', array('id' => $lid));				
			}
		}		
		 success($this->lang->line('delete_success'));
         redirect('accountgroups/index/'.$_POST['sc_id']);
		exit;
	}

}
