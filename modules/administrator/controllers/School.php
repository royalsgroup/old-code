<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************School.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : School
 * @description     : Manage academic school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class School extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('School_Model', 'school', true);
		$this->load->model('Voucher_Model', 'voucher', true);    
		$this->load->model('Accountledgers_Model', 'ledgers', true);
		$this->load->model('Payroll/Payscalecategory_Model', 'grade', true);            		
		$this->load->model('Academic/Classes_Model', 'classes', true);            		
		$this->load->model('Academic/Subject_Model', 'subject', true);            		
      /*  if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
            error($this->lang->line('permission_denied'));
            redirect('dashboard');
        }*/
		$this->data['fields'] = $this->school->get_table_fields('languages');
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Academic School List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        
        check_permission(VIEW);
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
			$sc_id=$this->session->userdata('school_id');
			$this->data['schools'] = $this->school->get_list('schools', array('id'=>$sc_id), '','', '', 'id', 'ASC');
		}
		else{
			$this->data['schools'] = get_school_list();
		}
		$this->data['states'] = $this->school->get_list('states', array(), '','', '', 'id', 'ASC');		
        $this->data['themes'] = $this->school->get_list('themes', array(), '','', '', 'id', 'ASC');		
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_school'). ' | ' . SMS);
        $this->layout->view('school/index', $this->data);            
       
    }

    
    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Academic School" user interface                 
    *                    and store "Academic School" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);  		
        if ($_POST) {
            $this->_prepare_school_validation();
            if ($this->form_validation->run() === TRUE ) {
                $data = $this->_get_posted_school_data();
                // echo "<pre>";print_r($data); echo exit;

                $insert_id = $this->school->insert('schools', $data);
                if ($insert_id) {
                    // insert default data
					//$this->school->insert_default_data($insert_id);
                	$user_arr=array();
					$user_arr['role_id']    = 2;
					$user_arr['school_id']    = $insert_id;
					$user_arr['password']   = md5('welcome');
					$user_arr['temp_password'] = base64_encode('welcome');		
					$user_arr['username']      = $data['school_code'];
					$user_arr['created_at'] = date('Y-m-d H:i:s');
					$user_arr['created_by'] = logged_in_user_id();
					$user_arr['status']     = 1; // by default would 
					$this->school->insert('users', $user_arr);
					$user_id=$this->db->insert_id();
					
					// create academic years
					$arr=array();
					$arr['school_id']    = $insert_id;
					$arr['start_year'] = preg_replace('/\D/', '', $this->input->post('session_start'));
					$arr['end_year']   = preg_replace('/\D/', '', $this->input->post('session_end'));
					$arr['session_year']   = $this->input->post('session_start') .' - '. $this->input->post('session_end');
					$arr['is_running'] = 1;
					$arr['status'] = 1;
					$arr['created_at'] = date('Y-m-d H:i:s');
					$arr['created_by'] = logged_in_user_id();
					$arr['modified_at'] = date('Y-m-d H:i:s');
					$arr['modified_by'] = logged_in_user_id();
					$academic_year_id=$this->school->insert('academic_years', $arr);
					
					// create financial years
					$arr=array();
					$arr['school_id']    = $insert_id;
					$arr['start_year'] = preg_replace('/\D/', '', $this->input->post('financial_session_start'));
					$arr['end_year']   = preg_replace('/\D/', '', $this->input->post('financial_session_end'));
                    $arr['session_year']   = $this->input->post('financial_session_start') .' -> '. $this->input->post('financial_session_end');
					$arr['is_running'] = 1;
					$arr['status'] = 1;
					$arr['created_at'] = date('Y-m-d H:i:s');
					$arr['created_by'] = logged_in_user_id();
					$arr['modified_at'] = date('Y-m-d H:i:s');
					$arr['modified_by'] = logged_in_user_id();
					$financial_year_id=$this->school->insert('financial_years', $arr);
					if($data['data']== 'Default'){
					// now insert to ledger
					$this->ledgers->insert_default($insert_id,$financial_year_id);
					// now insert default payscale category 
					$this->grade->insert_default($insert_id);
					// now insert default to vouchers
					$this->voucher->insert_default($insert_id,$financial_year_id);
					}
					$this->classes->insert_default($insert_id);
					$this->subject->insert_default($insert_id);
					
					// update in school
					$update_arr=array();
					$update_arr['academic_year_id']=$academic_year_id;
					$update_arr['financial_year_id']=$financial_year_id;
					$update_arr['academic_year']=preg_replace('/\D/', '', $this->input->post('session_start'))." - ".preg_replace('/\D/', '', $this->input->post('session_end'));
					$updated = $this->school->update('schools', $update_arr, array('id' => $insert_id));
					
                    create_log('Has been created a school : '.$data['school_name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('administrator/school');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/school/add');
                }
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['schools'] = $this->school->get_list('schools', array('status' => 1), '','', '', 'id', 'ASC');
		$this->data['states'] = $this->school->get_list('states', array(), '','', '', 'id', 'ASC');		
        $this->data['themes'] = $this->school->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('school'). ' | ' . SMS);
        $this->layout->view('school/index', $this->data);
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
        
        check_permission(EDIT);
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
			$sc_id=$this->session->userdata('school_id');
			if($sc_id != $id){
				error($this->lang->line('permission_denied'));
				redirect('dashboard');
			}
		}
        if ($_POST) {
            $this->_prepare_school_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_school_data();
                $updated = $this->school->update('schools', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a school : '.$data['school_name']);
                    success($this->lang->line('update_success'));
                    redirect('administrator/school');    
                    
                } else {
                    
                    error($this->lang->line('update_failed'));
                    redirect('administrator/school/edit/' . $this->input->post('id'));
                    
                }
            } else {
                 $this->data['school'] = $this->school->get_single_school($this->input->post('id'));
            }
        } else {
            if ($id) {
                $this->data['school'] = $this->school->get_single_school($id);
 
                if (!$this->data['school']) {
                     redirect('administrator/school');
                }
            }
        }

        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
			$sc_id=$this->session->userdata('school_id');
			$this->data['schools'] = $this->school->get_list('schools', array('id'=>$sc_id), '','', '', 'id', 'ASC');
		}
		else{
			$this->data['schools'] = get_school_list();
		}
		$this->data['states'] = $this->school->get_list('states', array(), '','', '', 'id', 'ASC');	
        $this->data['themes'] = $this->school->get_list('themes', array(), '','', '', 'id', 'ASC');
		$this->data['vouchers'] = $this->school->get_list('vouchers', array('school_id' => $id), '','', '', 'id', 'ASC');
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('academic_school'). ' | ' . SMS);
        $this->layout->view('school/index', $this->data);
    }
    
    
        
        
    /*****************Function get_single_school**********************************
     * @type            : Function
     * @function name   : get_single_school
     * @description     : "Load single school information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_school(){
        
       $school_id = $this->input->post('school_id');
       
       $this->data['school'] = $this->school->get_single('schools', array('id' => $school_id));
	   $district_id=$this->data['school']->district_id;
	   $this->data['district'] = $this->school->get_single('districts', array('id' => $district_id));
       echo $this->load->view('school/get-single-school', $this->data);
    }

    
    /*****************Function _prepare_school_validation**********************************
    * @type            : Function
    * @function name   : _prepare_school_validation
    * @description     : Process "Academic School" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_school_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_message('validate_frontend_logo_size', "The Image file size shoud not exceed 1MB!");
        $this->form_validation->set_message('validate_logo_size', 'The Image file size shoud not exceed 1MB!');

        $this->form_validation->set_rules('school_name', $this->lang->line('school') . ' ' . $this->lang->line('name'), 'trim|required|callback_school_name');
		$this->form_validation->set_rules('state_id', $this->lang->line('state'), 'trim|required');
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');
        $this->form_validation->set_rules('currency', $this->lang->line('currency'), 'trim');

        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required');
        $this->form_validation->set_rules('currency_symbol', $this->lang->line('currency_symbol'), 'trim|required');
		$this->form_validation->set_rules('language', $this->lang->line('language'), 'trim|required');
        $this->form_validation->set_rules('theme_name', $this->lang->line('theme'), 'trim|required');
        $this->form_validation->set_rules('footer', $this->lang->line('footer'), 'trim');
        $this->form_validation->set_rules('logo','logo', 'callback_validate_logo_size');
        $this->form_validation->set_rules('frontend_logo', 'frontend_logo', 'callback_validate_frontend_logo_size');
    }

            
    /*****************Function session_school**********************************
    * @type            : Function
    * @function name   : session_school
    * @description     : Unique check for "academic school" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function school_name() {
        if ($this->input->post('id') == '') {
            $school = $this->school->duplicate_check($this->input->post('school_name'));
            if ($school) {
                $this->form_validation->set_message('school_name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $school = $this->school->duplicate_check($this->input->post('school_name'), $this->input->post('id'));
            if ($school) {
                $this->form_validation->set_message('school_name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
    
    public function validate_frontend_logo_size() {

    
        if ((!isset($_FILES['frontend_logo'])) || $_FILES['frontend_logo']['size'] == 0) 
        {
            return true;
        }
        else if (isset($_FILES['frontend_logo']) && $_FILES['frontend_logo']['size'] != 0) {
            if(filesize($_FILES['frontend_logo']['tmp_name']) >= 1048676) {
                return false;
            }
        }
        return true;
    }
    public function validate_logo_size() 
    {
        if ((!isset($_FILES['logo'])) || $_FILES['logo']['size'] == 0) {
            return true;
        }
        else if (isset($_FILES['logo']) && $_FILES['logo']['size'] != 0) {
            if(filesize($_FILES['logo']['tmp_name']) >= 1048676) {
                return false;
            }
        }
        return true;
    }
    /*****************Function _get_posted_school_data**********************************
     * @type            : Function
     * @function name   : _get_posted_school_data
     * @description     : Prepare "Academic School" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_school_data() {

        $items = array();
        $items[] = 'state_id';
		$items[] = 'zone_id';
		$items[] = 'subzone_id';
		$items[] = 'district_id';
		$items[] = 'block_id';
		
		$items[] = 'sankul_id';
        $items[] = 'school_code';
        $items[] = 'school_name';
        $items[] = 'address';
		$items[] = 'pincode';
        $items[] = 'phone';
        $items[] = 'email';
        $items[] = 'currency';
        $items[] = 'currency_symbol';
        $items[] = 'school_fax';
        $items[] = 'school_lat'; 
        $items[] = 'school_lng'; 
        $items[] = 'map_api_key'; 
		 $items[] = 'zoom_api_key'; 
        $items[] = 'zoom_secret'; 
        $items[] = 'enable_frontend';
        $items[] = 'final_result_type';
        $items[] = 'registration_date';
        $items[] = 'footer';
        $items[] = 'theme_name';
		$items[] = 'language';
        $items[] = 'enable_online_admission';
        $items[] = 'parent_school_id';
        $items[] = 'school_type';
		$items[] = 'affiliation';
        $items[] = 'disc_code';
        $items[] = 'society_name';
        $items[] = 'society_pan_no';
        $items[] = 'school_80g_registration_no';        
		$items[] = 'remote_id';
		$items[] = 'skype_id';
		$items[] = 'building_type';
		$items[] = 'data';

        $items[] = 'category';
		$items[] = 'school_category';
        $items[] = 'education_type';
        
        $data = elements($items, $_POST); 
        $facilities='';
		$laboratory_facilities='';
		$school_levels='';
		if(isset($_POST['facilities']) && !empty($_POST['facilities'])){
			$facilities=implode(",",$_POST['facilities']);
		}
		if(isset($_POST['laboratory_facilities']) && !empty($_POST['laboratory_facilities'])){
			$laboratory_facilities=implode(",",$_POST['laboratory_facilities']);
		}
		if(isset($_POST['school_levels']) && !empty($_POST['school_levels'])){
			$school_levels=implode(",",$_POST['school_levels']);
		}
		$data['facilities']=$facilities;
		$data['laboratory_facilities']=$laboratory_facilities;
		$data['school_levels']=$school_levels;
        if ($this->input->post('id')) {
			$data['default_voucher_Id_for_salary_payment']=$this->input->post('default_voucher_Id_for_salary_payment');
			$data['default_voucher_Id_for_inventory']=$this->input->post('default_voucher_Id_for_inventory');
            $data['status'] = $this->input->post('status');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
               unset($data['school_code']);
             }  
        } else {
            
            $data['about_text'] = 'Lorem ipsum dolor sit amet, consecte- tur adipisicing elit, We create Premium WordPress themes & plugins for more than three years. ';
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }
        
        if ($_FILES['logo']['name']) {
            $data['logo'] = $this->_upload_logo();
        }
        if ($_FILES['frontend_logo']['name']) {
            $data['frontend_logo'] = $this->_upload_frontend_logo();
        }

        return $data;
    }
    
    
            
    /*****************Function _upload_logo**********************************
    * @type            : Function
    * @function name   : _upload_logo
    * @description     : Process to upload institute logo in the server                  
    *                     and return logo name   
    * @param           : null
    * @return          : $logo string value 
    * ********************************************************** */
    private function _upload_logo() {

        $prevoius_logo = @$_POST['logo_prev'];
        $logo_name = $_FILES['logo']['name'];
        $logo_type = $_FILES['logo']['type'];
        $logo = '';


        if ($logo_name != "") {
            if ($logo_type == 'image/jpeg' || $logo_type == 'image/pjpeg' ||
                    $logo_type == 'image/jpg' || $logo_type == 'image/png' ||
                    $logo_type == 'image/x-png' || $logo_type == 'image/gif') {

                $destination = 'assets/uploads/logo/';

                $file_type = explode(".", $logo_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $logo_path = time().'-school-admin-logo.' . $extension;

                copy($_FILES['logo']['tmp_name'], $destination . $logo_path);
                if($converted_file = webpConverter($destination . $logo_path,null, 415,515))
                {
                    $logo_path = get_filename($converted_file);
                }
                if ($prevoius_logo != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_logo)) {
                        @unlink($destination . $prevoius_logo);
                    }
                }

                $logo = $logo_path;
            }
        } else {
            $logo = $prevoius_logo;
        }

        return $logo;
    }
            
    /*****************Function _upload_frontend_logo**********************************
    * @type            : Function
    * @function name   : _upload_frontend_logo
    * @description     : Process to upload school front end logo in the server                  
    *                     and return logo name   
    * @param           : null
    * @return          : $logo string value 
    * ********************************************************** */
    private function _upload_frontend_logo() {

        $prevoius_logo = @$_POST['frontend_logo_prev'];
        $logo_name = $_FILES['frontend_logo']['name'];
        $logo_type = $_FILES['frontend_logo']['type'];
        $logo = '';


        if ($logo_name != "") {
            if ($logo_type == 'image/jpeg' || $logo_type == 'image/pjpeg' ||
                    $logo_type == 'image/jpg' || $logo_type == 'image/png' ||
                    $logo_type == 'image/x-png' || $logo_type == 'image/gif') {

                $destination = 'assets/uploads/logo/';

                $file_type = explode(".", $logo_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $logo_path = time().'-school-front-logo.' . $extension;

                copy($_FILES['frontend_logo']['tmp_name'], $destination . $logo_path);
                if($converted_file = webpConverter($destination . $logo_path))
                {
                    $logo_path = get_filename($converted_file);
                }
                if ($prevoius_logo != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_logo)) {
                        @unlink($destination . $prevoius_logo);
                    }
                }

                $logo = $logo_path;
            }
        } else {
            $logo = $prevoius_logo;
        }

        return $logo;
    }

    
    
    /*****************Function delete**********************************
   * @type            : Function
   * @function name   : delete
   * @description     : delete "Academic School" from database                  
   *                       
   * @param           : $id integer value
   * @return          : null 
   * ********************************************************** */
    public function delete($id = null) {        
        
        check_permission(DELETE);        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('administrator/school/index');              
        }
        
        // need to find all child data from database 
        $skips = array('global_setting', 'gmsms_sessions', 'languages', 'modules', 'operations', 'privileges', 'purchase', 'replies', 'roles', 'schools', 'system_admin', 'themes');
        $tables = $this->db->list_tables();
        
         foreach ($tables as $table) {
             
            if(in_array($table, $skips)){continue;}             
				$this->school->delete($table, array('school_id' => $id));
			
           /* $child_exist =$this->school->get_list($table, array('school_id'=>$id), '','', '', 'id', 'ASC');
            if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('administrator/school/index');
            }*/
        }    
     
        
        $school = $this->school->get_single('schools', array('id' => $id));
        
        if ($this->school->delete('schools', array('id' => $id))) {

             // delete syllabus file
            $destination = 'assets/uploads/logo/';
            if (file_exists( $destination.$school->frontend_logo)) {
                @unlink($destination.$school->frontend_logo);
            }
            if (file_exists( $destination.$school->logo)) {
                @unlink($destination.$school->logo);
            }
            
            create_log('Has been deleted a school : '.$school->school_name);  
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('administrator/school/index');
    }
	public function generate_school_code(){
		$sankul_id=$this->input->post('sankul_id');
		if($sankul_id >0){
			print $this->school->generate_school_code($sankul_id);
		}
		print '';
		exit;
	}
    private function check_file_sizes()
    {

    }

}
