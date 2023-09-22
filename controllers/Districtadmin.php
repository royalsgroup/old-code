<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Superadmin.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Superadmin
 * @description     : Manage superadmin information of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Districtadmin extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Districtadmin_Model', 'districtadmin', true);       
        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ 
            error($this->lang->line('permission_denied'));
            redirect('dashboard');
        }
        
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Superadmin List" user interface                 
    *                      
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function index() {

        //check_permission(VIEW);
        
        $this->data['districtadmins'] = $this->districtadmin->get_districtadmin_list();
      
		//$this->data['districts'] = $this->districtadmin->get_list('districts', array(), '','', '', 'id', 'ASC');		
		$this->data['states'] = $this->districtadmin->get_list('states', array(), '','', '', 'id', 'ASC');		
        $this->data['roles'] = $this->districtadmin->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
              
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage') . ' ' . $this->lang->line('district_admin') . ' | ' . SMS);
        $this->layout->view('district_admin/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Super admin" user interface                 
    *                    and process to store "Super admin" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        //check_permission(ADD);

        if ($_POST) {
            $this->_prepare_districtadmin_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_districtadmin_data();
                $insert_id = $this->districtadmin->insert('district_admin', $data);
                if ($insert_id) {
                    
                    //create_log('Has been created a super admin : '.$data['name']);  
                    
                    success($this->lang->line('insert_success'));
                    redirect('districtadmin/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('districtadmin/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['districtadmins'] = $this->districtadmin->get_districtadmin_list();
		$this->data['states'] = $this->districtadmin->get_list('states', array(), '','', '', 'id', 'ASC');
        $this->data['roles'] = $this->districtadmin->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
             
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('district_admin') . ' | ' . SMS);
        $this->layout->view('district_admin/index', $this->data);
    }

    
    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Super Admin" user interface                 
    *                    with populate "Super Admin" value 
    *                    and process to update "Super Admin" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        //check_permission(EDIT);
        if ($_POST) {
            $this->_prepare_districtadmin_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_districtadmin_data();
                $updated = $this->districtadmin->update('district_admin', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                   //create_log('Has been updated a super admin : '.$data['name']); 
                    
                    success($this->lang->line('update_success'));
                    redirect('districtadmin/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('districtadmin/edit/' . $this->input->post('id'));
                }
            } else {                
                $this->data['districtadmin'] = $this->districtadmin->get_single_districtadmin($this->input->post('id'));
            }
        } else {
            if ($id) {
                $this->data['districtadmin'] = $this->districtadmin->get_single_districtadmin($id);

                if (!$this->data['districtadmin']) {
                    redirect('districtadmin/index');
                }
            }
        }

        $this->data['districtadmins'] = $this->districtadmin->get_districtadmin_list();
		$this->data['states'] = $this->districtadmin->get_list('states', array(), '','', '', 'id', 'ASC');
      
        $this->data['edit'] = TRUE;

        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('district_admin') . ' | ' . SMS);
        $this->layout->view('district_admin/index', $this->data);
    }

    
     /*****************Function get_single_superadmin**********************************
     * @type            : Function
     * @function name   : get_single_superadmin
     * @description     : "Load single superadmin information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_district_admin(){
        
       $district_admin_id = $this->input->post('district_admin_id');
       
       $this->data['districtadmin'] = $this->districtadmin->get_single_districtadmin($district_admin_id);
       echo $this->load->view('district_admin/get-single-district-admin', $this->data);
    }
    
    /*****************Function _prepare_superadmin_validation**********************************
    * @type            : Function
    * @function name   : _prepare_superadmin_validation
    * @description     : Process "Super Admin" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_districtadmin_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('user_id', $this->lang->line('user'), 'trim|required');
        $this->form_validation->set_rules('state_id', $this->lang->line('state'), 'trim|required');
      
    }
   
    
                    
    /*****************Function email**********************************
    * @type            : Function
    * @function name   : email
    * @description     : Unique check for "Super Admin Email" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function username() {
        if ($this->input->post('id') == '') {
            $username = $this->districtadmin->duplicate_check($this->input->post('username'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $username = $this->districtadmin->duplicate_check($this->input->post('username'), $this->input->post('id'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
        
   
    /*****************Function _get_posted_superadmin_data**********************************
    * @type            : Function
    * @function name   : _get_posted_superadmin_data
    * @description     : Prepare "Super Admin" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */ 
    private function _get_posted_districtadmin_data() {

        $items = array();
		$items[] = 'state_id';
		$items[] = 'zone_id';
		$items[] = 'subzone_id';
		$items[] = 'district_id';
		$items[] = 'block_id';
		$items[] = 'sankul_id';
        
        $items[] = 'user_id';            
        
        $data = elements($items, $_POST);

       

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
            // create user 
           // $data['user_id'] = $this->districtadmin->create_user();
        }
      
        return $data;
    }

    
       
    /*****************Function _upload_photo**********************************
    * @type            : Function
    * @function name   : _upload_photo
    * @description     : Process to upload superadmin photo into server                  
    *                     and return photo name  
    * @param           : null
    * @return          : $return_photo string value 
    * ********************************************************** */ 
    private function _upload_photo() {

        $prev_photo = $this->input->post('prev_photo');
        $photo = $_FILES['photo']['name'];
        $photo_type = $_FILES['photo']['type'];
        $return_photo = '';
        if ($photo != "") {
            if ($photo_type == 'image/jpeg' || $photo_type == 'image/pjpeg' ||
                    $photo_type == 'image/jpg' || $photo_type == 'image/png' ||
                    $photo_type == 'image/x-png' || $photo_type == 'image/gif') {

                // super admin photo folder is same as employee
                $destination = 'assets/uploads/employee-photo/';

                $file_type = explode(".", $photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $photo_path);

                if($converted_file = webpConverter($destination . $photo_path,null, 415,515))
                {
                    $photo_path = get_filename($converted_file);
                }                // need to unlink previous photo
                if ($prev_photo != "") {
                    if (file_exists($destination . $prev_photo)) {
                        @unlink($destination . $prev_photo);
                    }
                }

                $return_photo = $photo_path;
            }
        } else {
            $return_photo = $prev_photo;
        }

        return $return_photo;
    }

           
    /*****************Function _upload_resume**********************************
    * @type            : Function
    * @function name   : _upload_resume
    * @description     : Process to upload superadmin resume into server                  
    *                     and return resume file name  
    * @param           : null
    * @return          : $return_resume string value 
    * ********************************************************** */ 
    private function _upload_resume() {
        
        $prev_resume = $this->input->post('prev_resume');
        $resume = $_FILES['resume']['name'];
        $resume_type = $_FILES['resume']['type'];
        $return_resume = '';

        if ($resume != "") {
            if ($resume_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                    $resume_type == 'application/msword' || $resume_type == 'text/plain' ||
                    $resume_type == 'application/vnd.ms-office' || $resume_type == 'application/pdf') {

                // super admin resume folder is same as employee
                $destination = 'assets/uploads/employee-resume/';

                $file_type = explode(".", $resume);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $resume_path = 'resume-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['resume']['tmp_name'], $destination . $resume_path);

                // need to unlink previous photo
                if ($prev_resume != "") {
                    if (file_exists($destination . $prev_resume)) {
                        @unlink($destination . $prev_resume);
                    }
                }

                $return_resume = $resume_path;
            }
        } else {
            $return_resume = $prev_resume;
        }

        return $return_resume;
    }

        
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Employee" data from database                  
    *                     and unlink superadmin photo and Resume from server  
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        //check_permission(DELETE);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('districtadmin');       
        }
        
        $districtadmin = $this->districtadmin->get_single('district_admin', array('id' => $id));
        if (!empty($districtadmin)) {
            
            //create_log('Has been deleted a super admin : '.$superadmin->name); 

            // delete superadmin data
            $this->districtadmin->delete('district_admin', array('id' => $id));
            // delete superadmin login data
            // $this->districtadmin->delete('users', array('id' => $districtadmin->user_id));

            // // delete superadmin resume and photo
            // $destination = 'assets/uploads/';
            // if (file_exists($destination . '/employee-resume/' . $districtadmin->resume)) {
            //     @unlink($destination . '/employee-resume/' . $districtadmin->resume);
            // }
            // if (file_exists($destination . '/employee-photo/' . $districtadmin->photo)) {
            //     @unlink($destination . '/employee-photo/' . $districtadmin->photo);
            // }

            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('districtadmin/index');
    }
	function tmp(){
		$schools= $this->districtadmin->get_list('schools', array(), '','', '', 'id', 'ASC');
		
		foreach($schools as $s){			
			$sankul_id=$s->sankul_id;
			$r = $this->districtadmin->tmp($sankul_id,$s->id);
			
				$update=array();
			$update['state_id']=$r->state_id1;
			$update['zone_id']=$r->zone_id1;
			$update['subzone_id']=$r->subzone_id1;
			$update['district_id']=$r->district_id1;
			$update['block_id']= $r->block_id1;			
			 
			$updated = $this->districtadmin->update('schools', $update, array('id' => $r->id));
		
			// update other details with sankul id
		}
		exit;
	}

}
