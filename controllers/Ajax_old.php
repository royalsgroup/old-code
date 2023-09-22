<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ***************Ajax.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Ajax
 * @description     : This class used to handle ajax call from view file 
 *                    of whole application.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Ajax extends My_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Ajax_Model', 'ajax', true);
    }

    /**     * *************Function get_user_by_role**********************************
     * @type            : Function
     * @function name   : get_user_by_role
     * @description     : this function used to manage user role list for user interface   
     * @param           : null 
     * @return          : $str string value with user role list 
     * ********************************************************** */
    public function get_user_by_role() {

        $role_id = $this->input->post('role_id');
        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $user_id = $this->input->post('user_id');
        $message = $this->input->post('message');

        $school = $this->ajax->get_school_by_id($school_id);
         
        $users = array();
        if ($role_id == SUPER_ADMIN) {
            $users = $this->ajax->get_list('system_admin', array('status' => 1), '', '', '', 'id', 'ASC');
        }elseif ($role_id == TEACHER) {
            $users = $this->ajax->get_list('teachers', array('status' => 1,'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        } elseif ($role_id == GUARDIAN) {
            $users = $this->ajax->get_list('guardians', array('status' => 1,'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        } elseif ($role_id == STUDENT) {
            
            if ($class_id) {
                $users = $this->ajax->get_student_list($class_id, $school_id, $school->academic_year_id);
            } else {
                $users = $this->ajax->get_list('students', array('status' => 1,'school_id'=>$school_id), '', '', '', 'id', 'ASC');
            }
            
        } else {

            $this->db->select('E.*');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->where('U.role_id', $role_id);
            $this->db->where('E.school_id', $school_id);
            $users = $this->db->get()->result();            
        }

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if (!$message && !empty($users)) {
            $str .= '<option value="0">' . $this->lang->line('all') . '</option>';
        }

        $select = 'selected="selected"';
        if (!empty($users)) {
            foreach ($users as $obj) {
                
                //if(logged_in_user_id() == $obj->user_id){continue;}
                
                $selected = $user_id == $obj->user_id ? $select : '';
                $str .= '<option value="' . $obj->user_id . '" ' . $selected . '>' . $obj->name . '(' . $obj->id . ')</option>';
            }
        }

        echo $str;
    }

    /*     * **************Function get_tag_by_role**********************************
     * @type            : Function
     * @function name   : get_tag_by_role
     * @description     : this function used to manage user role tag list for user interface   
     * @param           : null 
     * @return          : $str string value with user role tag list 
     * ********************************************************** */

    public function get_tag_by_role() {

        $role_id = $this->input->post('role_id');
        $tags = get_template_tags($role_id);
        $str = '';
        foreach ($tags as $value) {
            $str .= '<span> ' . $value . ' </span>';
        }

        echo $str;
    }

    /**     * *************Function update_user_status**********************************
     * @type            : Function
     * @function name   : update_user_status
     * @description     : this function used to update user status   
     * @param           : null 
     * @return          : boolean true/false 
     * ********************************************************** */
    public function update_user_status() {

        $user_id = $this->input->post('user_id');
        $status = $this->input->post('status');
        if ($this->ajax->update('users', array('status' => $status), array('id' => $user_id))) {
            echo TRUE;
        } else {
            echo FALSE;
        }
    }

    /**     * *************Function get_student_by_class**********************************
     * @type            : Function
     * @function name   : get_student_by_class
     * @description     : this function used to populate student list by class 
      for user interface
     * @param           : null 
     * @return          : $str string  value with student list
     * ********************************************************** */
    public function get_student_by_class() {

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $student_id = $this->input->post('student_id');
        $is_bulk = $this->input->post('is_bulk');
         
        $school = $this->ajax->get_school_by_id($school_id);
        $students = $this->ajax->get_student_list($class_id, $school_id, $school->academic_year_id);

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if($is_bulk){
             $str .= '<option value="all">' . $this->lang->line('all') . '</option>';
        }
        
        $select = 'selected="selected"';
        if (!empty($students)) {
            foreach ($students as $obj) {
                $selected = $student_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . ' [' . $obj->roll_no . ']</option>';
            }
        }

        echo $str;
    }

    
    
    /**     * *************Function get_section_by_class**********************************
     * @type            : Function
     * @function name   : get_section_by_class
     * @description     : this function used to populate section list by class 
      for user interface
     * @param           : null 
     * @return          : $str string  value with section list
     * ********************************************************** */
      public function get_section_by_class() {

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        
        $sections = $this->ajax->get_list('sections', array('status' => 1, 'school_id'=>$school_id, 'class_id' => $class_id), '', '', '', 'id', 'ASC');
        
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
    
        $guardian_section_data = get_guardian_access_data('section');
        
        $select = 'selected="selected"';
        if (!empty($sections)) {
            foreach ($sections as $obj) {
                
               if ($this->session->userdata('role_id') == GUARDIAN && !in_array($obj->id, $guardian_section_data)) { continue; } 
               elseif ($this->session->userdata('role_id') == TEACHER && $obj->teacher_id != $this->session->userdata('profile_id')) { continue; } 
               
                $selected = $section_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    /*     * **************Function get_student_by_section**********************************
     * @type            : Function
     * @function name   : get_student_by_section
     * @description     : this function used to populate student list by section 
      for user interface
     * @param           : null 
     * @return          : $str string  value with student list
     * ********************************************************** */

    public function get_student_by_section() {

        $student_id = $this->input->post('student_id');
        $section_id = $this->input->post('section_id');
        $school_id = $this->input->post('school_id');
        $is_all = $this->input->post('is_all');

        $students = $this->ajax->get_student_list_by_section($school_id, $section_id);
        
        if($is_all){
            $str = '<option value="0">' . $this->lang->line('all'). ' ' .$this->lang->line('student') . '</option>';    
        }else{
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        }
        
        $select = 'selected="selected"';
        if (!empty($students)) {
            foreach ($students as $obj) {
                $selected = $student_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . ' [' . $obj->roll_no . ']</option>';
            }
        }

        echo $str;
    }

    /**     * *************Function get_subject_by_class**********************************
     * @type            : Function
     * @function name   : get_subject_by_class
     * @description     : this function used to populate subject list by class 
      for user interface
     * @param           : null 
     * @return          : $str string  value with subject list
     * ********************************************************** */
    public function get_subject_by_class() {

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $subject_id = $this->input->post('subject_id');
       
        if($this->session->userdata('role_id') == TEACHER){
            $subjects = $this->ajax->get_list('subjects', array('status' => 1, 'class_id' => $class_id, 'school_id'=>$school_id,  'teacher_id'=>$this->session->userdata('profile_id')), '', '', '', 'id', 'ASC');
        }else{
            $subjects = $this->ajax->get_list('subjects', array('status' => 1, 'class_id' => $class_id, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        }
       
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
       
        $select = 'selected="selected"';
        if(!empty($subjects)) {
            foreach ($subjects as $obj) {
                $selected = $subject_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    /**     * *************Function get_assignment_by_subject**********************************
     * @type            : Function
     * @function name   : get_assignment_by_subject
     * @description     : this function used to populate assignment list by subject 
      for user interface
     * @param           : null 
     * @return          : $str string  value with assignment list
     * ********************************************************** */
    /*public function get_assignment_by_subject() {

        $subject_id = $this->input->post('subject_id');
        echo $assignment_id = $this->input->post('assignment_id');

        $assignments = $this->ajax->get_list('assignments', array('status' => 1, 'subject_id' => $subject_id, 'academic_year_id' => $this->academic_year_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($assignments)) {
            foreach ($assignments as $obj) {
                $selected = $assignment_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }*/

    /**     * *************Function get_guardian_by_id**********************************
     * @type            : Function
     * @function name   : get_guardian_by_id
     * @description     : this function used to populate guardian information/value by id 
      for user interface
     * @param           : null 
     * @return          : $guardina json  value
     * ********************************************************** */
    public function get_guardian_by_id() {

        header('Content-Type: application/json');
        $guardian_id = $this->input->post('guardian_id');

        $guardian = $this->ajax->get_single('guardians', array('id' => $guardian_id));
        echo json_encode($guardian);
        die();
    }

    /**     * *************Function get_room_by_hostel**********************************
     * @type            : Function
     * @function name   : get_room_by_hostel
     * @description     : this function used to populate room list by hostel  
      for user interface
     * @param           : null 
     * @return          : $str string value with room list 
     * ********************************************************** */
    public function get_room_by_hostel() {

        $hostel_id = $this->input->post('hostel_id');

        $hostels = $this->ajax->get_list('rooms', array('status' => 1, 'hostel_id' => $hostel_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">--.' . $this->lang->line('select') . ' ' . $this->lang->line('room_no') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($hostels)) {
            foreach ($hostels as $obj) {
                $selected = $subject_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->room_no . ' [' . $this->lang->line($obj->room_type) . '] [ ' . $obj->cost . ' ]</option>';
            }
        }

        echo $str;
    }
    
    
    /*****************Function get_user_list_by_type**********************************
     * @type            : Function
     * @function name   : get_user_list_by_type
     * @description     : Load "Employee or Teacher Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_user_list_by_type() {
        
         $school_id  = $this->input->post('school_id');
         $payment_to  = $this->input->post('payment_to');
         $user_id  = $this->input->post('user_id');
         
         $users = $this->ajax->get_user_list($school_id, $payment_to );
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($users)) {
            foreach ($users as $obj) {   
                $selected = $user_id == $obj->user_id ? $select : '';
                $str .= '<option value="' . $obj->user_id . '" ' . $selected . '>' . $obj->name .' [ '. $obj->designation . ' ]</option>';
            }
        }

        echo $str;
    }
    
  
    /*--------------START -------------------------*/
    
    /*****************Function get_designation_by_school**********************************
     * @type            : Function
     * @function name   : get_designation_by_school
     * @description     : Load "Designation Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_designation_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $designation_id  = $this->input->post('designation_id');
         
        $designations = $this->ajax->get_list('designations', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($designations)) {
            foreach ($designations as $obj) {   
                
                $selected = $designation_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .' </option>';
                
            }
        }

        echo $str;
    }
	public function get_employment_type_by_school() {
        
         $school_id  = $this->input->post('school_id');
         //$designation_id  = $this->input->post('designation_id');
		 $employee_id  = $this->input->post('employee_id');
         // get employees' employment types
		
			$emp_employment_types = $this->ajax->get_list('employee_employment_types', array('employee_id'=>$employee_id), '', '', '', 'id', 'ASC');
			$emp_type_arr=array();
			foreach($emp_employment_types as $e){
				$emp_type_arr[]=$e->employment_type_id;
			}
        $designations = $this->ajax->get_list('employment_types', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($designations)) {
            foreach ($designations as $obj) {   
                
                $selected = in_array($obj->id,$emp_type_arr) ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .' </option>';
                
            }
        }

        echo $str;
    }
    
    
    /*****************Function get_salary_grade_by_school**********************************
     * @type            : Function
     * @function name   : get_salary_grade_by_school
     * @description     : Load "Salary grade Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_salary_grade_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $salary_grade_id  = $this->input->post('salary_grade_id');
         
        $salary_grades = $this->ajax->get_list('salary_grades', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($salary_grades)) {
            foreach ($salary_grades as $obj) {   
                
                $selected = $salary_grade_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->grade_name .' </option>';
                
            }
        }

        echo $str;
    }
	public function get_payscale_category_by_school(){
		 $school_id  = $this->input->post('school_id');
		 $cat_arr=array();		
			$user_id  = $this->input->post('user_id');
			if($user_id!=null){
				
				$empcats=$this->ajax->get_list('user_payscalecategories', array('user_id'=>$user_id), '','', '', 'id', 'ASC');
				foreach($empcats as $ec){
					$cat_arr[]=$ec->payscalecategory_id;
				}
			}		 
		 $categories=$this->ajax->get_list('payscale_category', array('school_id'=>$school_id), '','', '', 'id', 'ASC');
		 $st='';
		 
		 if (!empty($categories)) {
            foreach ($categories as $obj) {   
				if(in_array($obj->id,$cat_arr)){
						$checked = 'checked="checked"';
				}
				else{
					$checked='';
				}
				$str .= "<div class='col-md-3 col-sm-3 col-xs-12'>";
				$str .= "<input type='checkbox' name='payscalecategory_id[]' value='".$obj->id."' ".$checked." />".$obj->name;
				$str .= "</div>";
			}
		 }
		 echo $str;
	}
	
    
    
    /*****************Function get_teacher_by_school**********************************
     * @type            : Function
     * @function name   : get_teacher_by_school
     * @description     : Load "Teacher Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_teacher_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $teacher_id  = $this->input->post('teacher_id');
         $is_all  = $this->input->post('is_all');
         
        $teachers = $this->ajax->get_list('teachers', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        if($is_all){
            $str = '<option value="0">' . $this->lang->line('all'). ' ' .$this->lang->line('teacher') . '</option>';    
        }else{
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        }
        
        $select = 'selected="selected"';
        if (!empty($teachers)) {
            foreach ($teachers as $obj) {   
                
                $selected = $teacher_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .' [ '. $obj->responsibility . ' ]</option>';
                
            }
        }

        echo $str;
    }
	 public function get_itemcategory_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $category_id  = $this->input->post('category_id');
         $is_all  = $this->input->post('is_all');
         
        $categories = $this->ajax->get_list('item_category', array('is_active'=>'yes', 'school_id'=>$school_id), '','', '', 'id', 'ASC');          
        if($is_all){
            $str = '<option value="0">' . $this->lang->line('all'). ' ' .$this->lang->line('category') . '</option>';    
        }else{
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        }
        
        $select = 'selected="selected"';
        if (!empty($categories)) {
            foreach ($categories as $obj) {   
                
                $selected = $category_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->item_category . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_itemsupplier_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $supplier_id  = $this->input->post('supplier_id');
         $is_all  = $this->input->post('is_all');
         
        $suppliers = $this->ajax->get_list('item_supplier', array('school_id'=>$school_id), '','', '', 'id', 'ASC');          
        if($is_all){
            $str = '<option value="0">' . $this->lang->line('all'). ' ' .$this->lang->line('supplier') . '</option>';    
        }else{
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        }
        
        $select = 'selected="selected"';
        if (!empty($suppliers)) {
            foreach ($suppliers as $obj) {   
                
                $selected = $supplier_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->item_supplier . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_accountgroup_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $group_id  = $this->input->post('group_id');
         
         
        $groups = $this->ajax->get_list('account_groups', array('school_id'=>$school_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($groups)) {
            foreach ($groups as $obj) {   
				$group_name=$obj->name;
                if($obj->type_id >0 ){
					$base = $this->ajax->get_single('account_types', array('id'=>$obj->type_id));
					if(!empty($base)){
						$group_name .= " [".$base->name."]";
					}
				}
                $selected = $group_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $group_name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_zone_by_state() {
        
         $state_id  = $this->input->post('state_id');
         $zone_id  = $this->input->post('zone_id');
         
         
        $zones = $this->ajax->get_list('zone', array('state_id'=>$state_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($zones)) {
            foreach ($zones as $obj) {   
               //$selected=''; 
                $selected = $zone_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_subzone_by_zone() {
        
         $zone_id  = $this->input->post('zone_id');
         $subzone_id  = $this->input->post('subzone_id');
         
         
        $subzones = $this->ajax->get_list('subzone', array('zone_id'=>$zone_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($subzones)) {
            foreach ($subzones as $obj) {                  
                $selected = $subzone_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_district_by_subzone() {
        
         $subzone_id  = $this->input->post('subzone_id');
         $district_id  = $this->input->post('district_id');
         
         
        $districts = $this->ajax->get_list('districts', array('subzone_id'=>$subzone_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($districts)) {
            foreach ($districts as $obj) {           
                $selected = $district_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_block_by_district() {
        
         $district_id  = $this->input->post('district_id');
         $block_id  = $this->input->post('block_id');
         
         
        $blocks = $this->ajax->get_list('blocks', array('district_id'=>$district_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($blocks)) {
            foreach ($blocks as $obj) {           
                $selected = $block_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_sankul_by_block() {
                 
         $block_id  = $this->input->post('block_id');
		 $sankul_id  = $this->input->post('sankul_id');
         
         
        $sankul = $this->ajax->get_list('sankul', array('block_id'=>$block_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($sankul)) {
            foreach ($sankul as $obj) {           
                $selected = $sankul_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }	
	public function get_employees(){		
		$this->load->model('Districtadmin_Model', 'districtadmin', true);       		
		$state_id  = $this->input->post('state_id');
         $zone_id  = $this->input->post('zone_id');
		 $subzone_id  = $this->input->post('subzone_id');
         $district_id  = $this->input->post('district_id');
		 $block_id  = $this->input->post('block_id');
		 $sankul_id  = $this->input->post('sankul_id');
		 $user_id=$this->input->post('user_id');		 
		  $employees = $this->districtadmin->get_employees($state_id,$zone_id,$subzone_id,$district_id,$block_id,$sankul_id);
		  $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($employees)) {
            foreach ($employees as $obj) {           
                $selected = $user_id == $obj->user_id ? $select : '';

                $str .= '<option value="' . $obj->user_id . '" ' . $selected . '>' . $obj->name. "[".$obj->username."]" . '</option>';
                
            }
        }

        echo $str;
	}
	public function generate_employee_code($school_id = null){
		if($school_id!= null){
			$s_id=$school_id;
		}
		else if($this->input->post('school_id')>0){
			$s_id=$this->input->post('school_id');
		}
		if(isset($s_id)){		
			$school=$this->ajax->get_single('schools',array('id'=>$s_id));			
			$district=$this->ajax->get_single('districts',array('id'=>$school->district_id));		
			$RandomDigit = rand(100000,999999);
			$unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
			// check for duplicate code
			$employee=$this->ajax->get_single('employees',array('employee_code'=>$unique_code));
			if(!empty($employee)){
				generate_employee_code($s_id);
			}
			else{
				print $unique_code;
			}
		}
		else {
			print "";
		}
	}
	public function generate_teacher_code($school_id = null){
		if($school_id!= null){
			$s_id=$school_id;
		}
		else if($this->input->post('school_id')>0){
			$s_id=$this->input->post('school_id');
		}
		if(isset($s_id)){		
			$school=$this->ajax->get_single('schools',array('id'=>$s_id));			
			$district=$this->ajax->get_single('districts',array('id'=>$school->district_id));		
			$RandomDigit = rand(100000,999999);
			$unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
			// check for duplicate code
			$employee=$this->ajax->get_single('teachers',array('teacher_code'=>$unique_code));
			if(!empty($employee)){
				generate_teacher_code($s_id);
			}
			else{
				print $unique_code;
			}
		}
		else {
			print "";
		}
	}
	public function generate_student_code($school_id = null){
		if($school_id!= null){
			$s_id=$school_id;
		}
		else if($this->input->post('school_id')>0){
			$s_id=$this->input->post('school_id');
		}
		if(isset($s_id)){		
			$school=$this->ajax->get_single('schools',array('id'=>$s_id));			
			$district=$this->ajax->get_single('districts',array('id'=>$school->district_id));		
			$RandomDigit = rand(100000,999999);
			$unique_code=strtoupper(substr($district->name,0,2)).substr($school->school_code,strlen($school->school_code)-3).$RandomDigit;
			// check for duplicate code
			$employee=$this->ajax->get_single('students',array('student_code'=>$unique_code));
			if(!empty($employee)){
				generate_student_code($s_id);
			}
			else{
				print $unique_code;
			}
		}
		else {
			print "";
		}
	}
	public function get_accountledger_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $ledger_id  = $this->input->post('ledger_id');
         
         
        $ledgers = $this->ajax->get_list('account_ledgers', array('school_id'=>$school_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($ledgers)) {
            foreach ($ledgers as $obj) {   
                
                $selected = $ledger_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_pay_group_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $pay_group_id  = $this->input->post('pay_group_id');
         
         
        $pay_groups = $this->ajax->get_list('pay_groups', array('school_id'=>$school_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($pay_groups)) {
            foreach ($pay_groups as $obj) {   
                
                $selected = $pay_group_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name ." [Group Code - ".$obj->group_code."]". '</option>';
                
            }
        }

        echo $str;
    }
	public function get_payscale_categoriess_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $dependant_payscale_categories  = $this->input->post('dependant_payscale_categories');
		 $pid  = $this->input->post('pid');
         if($dependant_payscale_categories !=''){
			 $arr=explode(",",$dependant_payscale_categories);
		 }		
         if($pid >0){
        $cats = $this->ajax->get_list('payscale_category', array('school_id'=>$school_id,'id != '=>$pid), '','', '', 'id', 'ASC');
		 }
else{
	$cats = $this->ajax->get_list('payscale_category', array('school_id'=>$school_id), '','', '', 'id', 'ASC');
}		 
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($cats)) {
            foreach ($cats as $obj) {   
                
					$selected = isset($arr) && in_array($obj->id,$arr) ? $select : '';				
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_voucher_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $voucher_id  = $this->input->post('voucher_id');
         
         
        $vouchers = $this->ajax->get_list('vouchers', array('school_id'=>$school_id), '','', '', 'id', 'ASC');          
        
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
       
        $select = 'selected="selected"';
        if (!empty($vouchers)) {
            foreach ($vouchers as $obj) {   
                
                $selected = $voucher_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_itemstore_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $store_id  = $this->input->post('store_id');
         $is_all  = $this->input->post('is_all');
         
        $stores = $this->ajax->get_list('item_store', array('school_id'=>$school_id), '','', '', 'id', 'ASC');          
        if($is_all){
            $str = '<option value="0">' . $this->lang->line('all'). ' ' .$this->lang->line('store') . '</option>';    
        }else{
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        }
        
        $select = 'selected="selected"';
        if (!empty($stores)) {
            foreach ($stores as $obj) {   
                
                $selected = $store_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->item_store . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_accountbase_by_type() {
        
         $type_id  = $this->input->post('type_id');         
       $base_id  = $this->input->post('base_id');         
        $base = $this->ajax->get_list('account_base', array('type_id'=>$type_id), '','', '', 'id', 'ASC');          
      
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        
        $select = 'selected="selected"';
        if (!empty($base)) {
            foreach ($base as $obj) {   
                
                $selected = $base_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
	public function get_subject_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $subject_id  = $this->input->post('subject_id');
         $is_all  = $this->input->post('is_all');
         
        $subjects = $this->ajax->get_list('subjects', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        if($is_all){
            $str = '<option value="0">' . $this->lang->line('all'). ' ' .$this->lang->line('subject') . '</option>';    
        }else{
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        }
        
        $select = 'selected="selected"';
        if (!empty($subjects)) {
            foreach ($subjects as $obj) {   
                
                $selected = $subject_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .'</option>';
                
            }
        }

        echo $str;
    }
    
    /*****************Function get_employee_by_school**********************************
     * @type            : Function
     * @function name   : get_employee_by_school
     * @description     : Load "Employee Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_employee_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $employee_id  = $this->input->post('employee_id');
         $is_all  = $this->input->post('is_all');
         
        $employees = $this->ajax->get_list('employees', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
         if($is_all){
            $str = '<option value="0">' . $this->lang->line('all'). ' ' .$this->lang->line('employee') . '</option>';    
        }else{
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        }
        
        $select = 'selected="selected"';
        if (!empty($employees)) {
            foreach ($employees as $obj) {   
                
                $selected = $employee_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .'</option>';
                
            }
        }

        echo $str;
    }
    
    
    /*****************Function get_guardian_by_school**********************************
     * @type            : Function
     * @function name   : get_guardian_by_school
     * @description     : Load "Guardian Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_guardian_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $guardian_id  = $this->input->post('guardian_id');
         
        $guardinas = $this->ajax->get_list('guardians', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($guardinas)) {
            foreach ($guardinas as $obj) {   
                
                $selected = $guardian_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    /*****************Function get_discount_by_school**********************************
     * @type            : Function
     * @function name   : get_discount_by_school
     * @description     : Load "Discount Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_discount_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $discount_id  = $this->input->post('discount_id');
         
        $discounts = $this->ajax->get_list('discounts', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($discounts)) {
            foreach ($discounts as $obj) {   
                
                $selected = $discount_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    
    /*****************Function get_student_type_by_school**********************************
     * @type            : Function
     * @function name   : get_student_type_by_school
     * @description     : Load "Student type Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_student_type_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $type_id  = $this->input->post('type_id');
         
        $types = $this->ajax->get_list('student_types', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($types)) {
            foreach ($types as $obj) {   
                
                $selected = $type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->type . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    /*****************Function get_class_by_school**********************************
     * @type            : Function
     * @function name   : get_class_by_school
     * @description     : Load "Class Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_class_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $class_id  = $this->input->post('class_id');
         
        $classes = $this->ajax->get_list('classes', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($classes)) {
            foreach ($classes as $obj) {   
                
                $selected = $class_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    
    /*****************Function get_exam_by_school**********************************
     * @type            : Function
     * @function name   : get_exam_by_school
     * @description     : Load "Exam Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_exam_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $exam_id  = $this->input->post('exam_id');
         
        $exams = $this->ajax->get_list('exams', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($exams)) {
            foreach ($exams as $obj) {   
                
                $selected = $exam_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    
    
    /*****************Function get_certificate_type_by_school**********************************
     * @type            : Function
     * @function name   : get_certificate_type_by_school
     * @description     : Load "Certificate Type Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_certificate_type_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $certificate_id  = $this->input->post('certificate_id');
         
        $certificates = $this->ajax->get_list('certificates', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($certificates)) {
            foreach ($certificates as $obj) {   
                
                $selected = $certificate_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                
            }
        }

        echo $str;
    }
    
    /*****************Function get_gallery_by_school**********************************
     * @type            : Function
     * @function name   : get_gallery_by_school
     * @description     : Load "Gallery Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_gallery_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $gallery_id  = $this->input->post('gallery_id');
         
        $galleries = $this->ajax->get_list('galleries', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($galleries)) {
            foreach ($galleries as $obj) {   
                
                $selected = $gallery_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
                
            }
        }

        echo $str;
    }
    
    /*****************Function get_leave_type_by_school**********************************
     * @type            : Function
     * @function name   : get_leave_type_by_school
     * @description     : Load "Leave type Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_leave_type_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $role_id  = $this->input->post('role_id');
         $type_id  = $this->input->post('type_id');
         
        $types = $this->ajax->get_list('leave_types', array('status'=>1, 'school_id'=>$school_id, 'role_id'=>$role_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($types)) {
            foreach ($types as $obj) {   
                
                $selected = $type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->type . '</option>';
                
            }
        }

        echo $str;
    }
    
    /*****************Function get_visitor_purpose_by_school**********************************
     * @type            : Function
     * @function name   : get_visitor_purpose_by_school
     * @description     : Load "Visitor purpose Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_visitor_purpose_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $purpose_id  = $this->input->post('purpose_id');
         
        $purposes = $this->ajax->get_list('visitor_purposes', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($purposes)) {
            foreach ($purposes as $obj) {   
                
                $selected = $purpose_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->purpose . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    /*****************Function get_complain_type_by_school**********************************
     * @type            : Function
     * @function name   : get_complain_type_by_school
     * @description     : Load "Complain type Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_complain_type_by_school() {
        
         $school_id  = $this->input->post('school_id');
         $type_id  = $this->input->post('type_id');
         
        $types = $this->ajax->get_list('complain_types', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($types)) {
            foreach ($types as $obj) {   
                
                $selected = $type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->type . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    /*****************Function get_user_single_payment**********************************
     * @type            : Function
     * @function name   : get_user_single_payment
     * @description     : validate the paymeny to user already paid for selected month               
     *                    
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_user_single_payment() {
        
         $payment_to  = $this->input->post('payment_to');
         $user_id  = $this->input->post('user_id');
         $salary_month  = $this->input->post('salary_month');
         
         $exist = $this->ajax->get_single('salary_payments',array('user_id'=>$user_id, 'salary_month'=>$salary_month, 'payment_to'=>$payment_to ));
         
         if($exist){
             echo 1;
         }else{
             echo 2;
         }         
    }
    
    /*****************Function get_school_info_by_id**********************************
     * @type            : Function
     * @function name   : get_school_info_by_id
     * @description     : validate the paymeny to user already paid for selected month               
     *                    
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_school_info_by_id() {
        
         $school_id  = $this->input->post('school_id');
         
         $school = $this->ajax->get_single('schools',array('id'=>$school_id));         
         echo $school->final_result_type;        
    }
    
    /*****************Function get_sms_gateways**********************************
     * @type            : Function
     * @function name   : get_sms_gateways
     * @description     : Load "SMS Settings" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_sms_gateways() {
        
        $school_id  = $this->input->post('school_id');
         
        $gateways = get_sms_gateways($school_id);
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if (!empty($gateways)) {
            foreach ($gateways as $key=>$value) {   
                
                $str .= '<option value="' . $key . '" >' . $value . '</option>';
                
            }
        }

        echo $str;
    }
    
    
    

    
    
    /*****************Function get_academic_year_by_school**********************************
     * @type            : Function
     * @function name   : get_academic_year_by_school
     * @description     : Load "SMS Settings" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_academic_year_by_school() {
        
        $school_id  = $this->input->post('school_id');
        $academic_year_id  = $this->input->post('academic_year_id');
         
        $academic_years = $this->ajax->get_list('academic_years', array('school_id'=>$school_id), '','', '', 'id', 'ASC');
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
         $select = 'selected="selected"';
         
        if (!empty($academic_years)) {
            foreach($academic_years as $obj ){   
           
                $selected = $academic_year_id == $obj->id ? $select : '';                
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->session_year . '</option>';
            }
        }

        echo $str;
    }
    
    
        
    /** * *************Function get_email_template_by_role**********************************
     * @type            : Function
     * @function name   : get_email_template_by_role
     * @description     : this function used to populate template by role  
      for user interface
     * @param           : null 
     * @return          : $str string value with room list 
     * ********************************************************** */
    public function get_email_template_by_role() {

        $role_id = $this->input->post('role_id');
        $school_id = $this->input->post('school_id');

        $templates = $this->ajax->get_list('email_templates', array('status' => 1, 'role_id' => $role_id,'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">-- ' . $this->lang->line('select') . ' ' . $this->lang->line('template') . ' --</option>';
        if (!empty($templates)) {
            foreach ($templates as $obj) {
                $str .= '<option itemid="'.$obj->id.'" value="' . $obj->template . '">' . $obj->title . '</option>';
            }
        }

        echo $str;
    }
   
    
        
    /** * *************Function get_sms_template_by_role**********************************
     * @type            : Function
     * @function name   : get_sms_template_by_role
     * @description     : this function used to populate template by role  
      for user interface
     * @param           : null 
     * @return          : $str string value with room list 
     * ********************************************************** */
    public function get_sms_template_by_role() {

        $role_id = $this->input->post('role_id');
        $school_id = $this->input->post('school_id');

        $templates = $this->ajax->get_list('sms_templates', array('status' => 1, 'role_id' => $role_id,'school_id'=>$school_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">-- ' . $this->lang->line('select') . ' ' . $this->lang->line('template') . ' --</option>';
        if (!empty($templates)) {
            foreach ($templates as $obj) {
                $str .= '<option itemid="'.$obj->id.'" value="' . $obj->template . '">' . $obj->title . '</option>';
            }
        }

        echo $str;
    }
    
    
    
        
    /** * *************Function get_current_session_by_school**********************************
     * @type            : Function
     * @function name   : get_current_session_by_school
     * @description     : this function used to populate template by role  
      for user interface
     * @param           : null 
     * @return          : $str string value with room list 
     * ********************************************************** */
    public function get_current_session_by_school() {

        $current_session_id = $this->input->post('current_session_id');
        $school_id = $this->input->post('school_id');
        
        $school = $this->ajax->get_school_by_id($school_id);
        
        $curr_session = $this->ajax->get_list('academic_years', array('id' => $school->academic_year_id, 'school_id'=>$school_id));
        $str = '<option value="">-- ' . $this->lang->line('select') . ' --</option>';
         $select = 'selected="selected"';
         
        if (!empty($curr_session)) {
            foreach ($curr_session as $obj) {
                $selected = $current_session_id == $obj->id ? $select : '';  
                $str .= '<option value="'.$obj->id.'" '.$selected.'>' . $obj->session_year . '</option>';
            }
        }

        echo $str;
    }
    
    
        
    /** * *************Function get_next_session_by_school**********************************
     * @type            : Function
     * @function name   : get_next_session_by_school
     * @description     : this function used to populate template by role  
      for user interface
     * @param           : null 
     * @return          : $str string value with room list 
     * ********************************************************** */
    public function get_next_session_by_school() {

        $academic_year_id = $this->input->post('academic_year_id');
        $school_id = $this->input->post('school_id');
        $school = $this->ajax->get_school_by_id($school_id);
        
        $next_session = $this->ajax->get_list('academic_years', array('id !=' => $school->academic_year_id, 'school_id'=>$school_id));
        $str = '<option value="">-- ' . $this->lang->line('select') . ' --</option>';
        $select = 'selected="selected"';        
        
        if (!empty($next_session)) {
            foreach ($next_session as $obj) {
                
                $selected = $academic_year_id == $obj->id ? $select : ''; 
                $str .= '<option value="'.$obj->id.'" ' . $selected . '>' . $obj->session_year . '</option>';
            }
        }

        echo $str;
    }
    
    public function update_student_status_type()
    {
        
    }
    

}
