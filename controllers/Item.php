<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Item extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Item_Model', 'item', true);	
        		
    }

     public function index($school_id = null) {
       
        //check_permission(VIEW);
        $category_id  = $this->input->post('category_id');
        $group_id  = $this->input->post('group_id');
		if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $school_id=$this->session->userdata('school_id');                    
			$condition['school_id'] = $school_id;
			$condition['is_active']='yes';
			$this->data['itemcategories'] = $this->item->get_list('item_category', $condition, '','', '', 'id', 'ASC');
        }   
        if($school_id != null)
        {
            $this->data['item_groups'] = $this->item->get_itemgroup_list($school_id);
        }
        $financial_year=$this->item->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));	
        $check_financial_year=$this->item->get_single('financial_years',array('school_id'=>$school_id,'previous_financial_year_id'=> $financial_year->id));	         
		if(strpos($financial_year->session_year,"->"))	
        {
            $arr=explode("->",$financial_year->session_year);
            $f_start=date("Y-m-d",strtotime($arr[0]));		
            $f_end=date("Y-m-d",strtotime($arr[1]));	
        }
        else
        {
			$arr=explode("-",$financial_year->session_year);
            $date_exploded = explode(" ",$arr[0]);
            if(count($date_exploded)>2)
            {
                $f_start=date("Y-m-d",strtotime($arr[0]));		
                $f_end=date("Y-m-d",strtotime($arr[1]));	
            }
            else
            {
                $f_start=date("Y-m-d",strtotime("1 ".$arr[0]));		
                $f_end=date("Y-m-d",strtotime("31 ".$arr[1]));	
            }
        }
        $financial_start_date = $f_start;
		$financial_end_date = $f_end;
        $this->data['items'] = $this->item->get_item_list($school_id, $category_id, $group_id, $financial_start_date, $financial_end_date );

        $this->data['category_id'] = $category_id;     
        $this->data['group_id'] = $group_id;     
        $this->data['filter_school_id'] = $school_id;     
		$this->data['schools'] = $this->schools;	
        //$this->data['itemstores'] = $this->itemstore->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->item->get_list('themes', array(), '','', '', 'id', 'ASC');
		//$this->data['itemcategories'] = $this->item->get_list('item_category', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_item'). ' | ' . SMS);
        $this->layout->view('item/index', $this->data);            
       
    }
  
    public function add() {

        //check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_item_validation();
			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_item_data();
				
                $insert_id = $this->item->insert('item', $data);
                if ($insert_id) {
                    
                    //create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('item');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('item/add');
                }
            } else {
                $this->data = $_POST;
            }
        }

		$this->data['items'] = $this->item->get(null,$school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		//$this->data['itemcategories'] = $this->item->get_list('item_category', array(), '','', '', 'id', 'ASC');
        //$this->data['itemstores'] = $this->item->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->item->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('item'). ' | ' . SMS);
        $this->layout->view('item/index', $this->data);
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
            $this->_prepare_item_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_item_data();
                $updated = $this->item->update('item', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   // create_log('Has been updated a school : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('item');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('item/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['item'] = $this->item->get_single('item', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['item'] = $this->item->get_single('item', array('id' => $id));
				
                if (!$this->data['item']) {
                     redirect('item');
                }
            }
        }
		$this->data['items'] = $this->item->get(null,$school_id);
		if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['school_id'] = $this->data['item']->school_id;
        $this->data['filter_school_id'] = $this->data['item']->school_id;   
		$this->data['schools'] = $this->schools;
		//$this->data['itemcategories'] = $this->item->get_list('item_category', array(), '','', '', 'id', 'ASC');
        //$this->data['itemstores'] = $this->item->get_list('item_store', array(), '','', '', 'id', 'ASC');
        $this->data['themes'] = $this->item->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       		
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('item'). ' | ' . SMS);
        $this->layout->view('item/index', $this->data);
    }
    
    
        
        
   

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_item_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
      $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required'); 
		//$this->form_validation->set_rules('unit', $this->lang->line('unit'), 'trim|required');    		
		$this->form_validation->set_rules('item_category_id', $this->lang->line('item_category_id'), 'trim|required');    		
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
   
    
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_item_data() {

        $items = array();
        $items[] = 'school_id';       
        $items[] = 'item_category_id'; 
		$items[] = 'name'; 
		$items[] = 'unit'; 
		$items[] = 'description'; 
        $items[] = 'item_code'; 		
        $data = elements($items, $_POST); 		        
		if ($this->input->post('id')) {
            $data['updated_at'] = date('Y-m-d H:i:s');            
        } else {           
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
                       
        }   

        return $data;
    }
    public function import_defualt($school_id) {
        
        $default_groups = $this->item->get_default_groups($school_id);
      
        $item_count = 0;
        if(!empty($default_groups))
        {
             foreach($default_groups as $default_group)
             {
                $default_group_id  = $default_group['id'];
                if($default_group['existing_id'])
                {
                    
                    $group_id = $default_group['existing_id'];
                }
                else
                {
                    $group_data = $default_group;
                    $group_data['school_id'] = $school_id;
                    $group_data['created_at'] =  date('Y-m-d H:i:s');
                    $group_data['updated_at'] =  date('Y-m-d H:i:s');
                    unset($group_data['Imported']);
                    $group_data['imported'] =  1;
                    unset($group_data['id']);
                    unset($group_data['existing_id']);
                    $group_id = $this->item->insert('item_groups', $group_data);
                }
               
                $default_categories = $this->item->get_default_categories($school_id,$group_id,$default_group_id);
                foreach( $default_categories  as  $default_category)
                {
                    $defualt_category_id = $default_category['id'];
                    if($default_category['existing_id'] )
                    {
                        $category_id = $default_category['existing_id'];
                    }
                    else
                    {
                        $category_data = $default_category;
                        $category_data['school_id'] = $school_id;
                        $category_data['group_id'] = $group_id;
                        $category_data['created_at'] =  date('Y-m-d H:i:s');
                        $category_data['updated_at'] =  date('Y-m-d H:i:s');
                        $category_data['imported'] =  1;
                        unset($category_data['id']);
                        unset($category_data['existing_id']);

                        
                        $category_id = $this->item->insert('item_category', $category_data);
                    }
                    $default_items = $this->item->get_default_items($school_id,$category_id,$defualt_category_id);
                    // //echo $this->db->last_query();
                    // var_dump($default_items);
                    // die();
                    foreach( $default_items  as  $default_item)
                    {
                        if($default_item['existing_id'] )
                        {
                            $item_id = $default_item['existing_id'];
                           
                        }
                        else
                        {
                            $item_data = $default_item;
                            $item_data['school_id'] = $school_id;
                            $item_data['item_category_id'] = $category_id;
                            $item_data['created_at'] =  date('Y-m-d H:i:s');
                            $item_data['updated_at'] =  date('Y-m-d H:i:s');
                            $item_data['imported'] =  1;
                            unset($item_data['id']);
                            unset($item_data['existing_id']);
    
                            
                            $item_id = $this->item->insert('item', $item_data);
                           
                            $item_count++;
                        }
                    }
                   

                }
                
              
             }
            
        }
        redirect('item/index');           
    }
	public function delete($id = null) {        
        
       // check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('item/index');              
        }
        $delete_params = array('id' => $id);
                
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $school_id=$this->session->userdata('school_id');    
            $delete_params['school_id'] =  $school_id;
        }

        if ($this->item->delete('item', $delete_params )) {
           
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('item/index');
    }
	function getAvailQuantity() {
        $item_id = $this->input->get('item_id');
        $school_id = $this->input->get('school_id');
         $data = $this->item->getItemAvailable($item_id,$school_id);
         $purchase_data = $this->item->getItemLastPrice($item_id,$school_id);
         $last_price = !empty($purchase_data) && $purchase_data->purchase_price ? $purchase_data->purchase_price  : "";
         $last_mrp = !empty($purchase_data) && $purchase_data->mrp ? $purchase_data->mrp  : "";
         
         $available =$data;
        
        //$available = ($data['added_stock'] - $data['issued']);
        if($available>=0){
             echo json_encode(array('available' => $available,'last_price'=>$last_price,'last_mrp'=>$last_mrp));
        }else{
             echo json_encode(array('available' => 0,'last_price'=>$last_price,'last_mrp'=>$last_mrp));
        }
       
    }

}
