<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Student.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Student
 * @description     : Manage students imformation of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Student extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();      
        
        $this->load->model('Student_Model', 'student', true);   
        $this->load->model('academic/Classes_Model', 'classes', true);     
         
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Student List" user interface                 
    *                    with class wise listing    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($class_id = null) {

        check_permission(VIEW);
        
        if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/classes/index');
        }
        
        // for super admin 
        $school_id = '';
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');           
        }
              
        if((!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN) && (!$school_id && $this->session->userdata('dadmin') != 1)){
            $school_id = $this->session->userdata('school_id');
        }
        
        if($class_id && !$school_id){
            $class = $this->student->get_single('classes', array('id'=>$class_id));
            $school_id = $class->school_id;
        }
        
        $school = $this->student->get_school_by_id($school_id);
        
        if($this->session->userdata('role_id') == STUDENT){
          $class_id =  $this->session->userdata('class_id');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        
       // if($school_id){
          //  $this->data['students'] = $this->student->get_student_list($class_id, $school_id, $school->academic_year_id);
       // }
                
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
            //$this->data['classes'] = $this->student->get_list('classes', $condition, '','', '', 'id', 'ASC');


            // $this->data['guardians'] = $this->student->get_list('guardians', $condition, '','', '', 'id', 'ASC');
            $this->data['class_list'] =$this->classes->get_class_list($condition['school_id'] );
			$this->data['classes'] =$this->data['class_list'];
              // $this->data['class_list'] = $this->student->get_class_by_session('classes', $condition['school_id']);
             // echo "<pre>"; print_r( $this->data['class_list']);exit;


            $this->data['types']      = $this->student->get_list('student_types', $condition, '','', '', 'id', 'ASC'); 
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['list'] = TRUE;

        // print_r($this->data);exit;

        $this->layout->title($this->lang->line('manage_student') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }
    
	public function get_list(){	
        error_on();	
		 // for super admin 
        $school_id = '';
		$start=null;
		$limit=null;
		$search_text='';
        if($_POST){            
            $order_cols =  array();
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');  
			$start = $this->input->post('start');
            $limit  = $this->input->post('length');   
            $order_cols  = $this->input->post('order');   
			$draw = $this->input->post('draw');	
			if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
				$search_text=$_POST['search']['value'];
			}
        }		
              
        if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
            $school_id = $this->session->userdata('school_id');
        }
        
        if($class_id && !$school_id){
            $class = $this->student->get_single('classes', array('id'=>$class_id));
            $school_id = $class->school_id;
        }
        if(!$school_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
              );
              echo json_encode($response);
              exit;
        }
        
        $school = $this->student->get_school_by_id($school_id);
        
        if($this->session->userdata('role_id') == STUDENT){
          $class_id =  $this->session->userdata('class_id');
        }              
        $sort_coloumn = "";
        if(!empty($order_cols))
        {
            foreach($order_cols as $order)
            {
                if($order['column'] == 1)
                {
                    $sort_coloumn = "school_name";
                }
                elseif($order['column'] == 2)
                {
                    $sort_coloumn = "name";
                }
                elseif($order['column'] == 3)
                {
                    $sort_coloumn = "father_name";
                }
                elseif($order['column'] == 4)
                {
                    $sort_coloumn = "group";
                }
                elseif($order['column'] == 5)
                {
                    $sort_coloumn = "C.name";
                }
                elseif($order['column'] == 6)
                {
                    $sort_coloumn = "SE.name";
                }
                elseif($order['column'] == 7)
                {
                    $sort_coloumn = "admission_no";
                }
                elseif($order['column'] == 8)
                {
                    $sort_coloumn = "email";
                }
                elseif($order['column'] == 9)
                {
                    $sort_coloumn = "roll_no";
                }
                elseif($order['column'] == 14)
                {
                    $sort_coloumn = "admission_date";
                }
                elseif($order['column'] == 15)
                {
                    $sort_coloumn = "gender";
                }
                elseif($order['column'] == 16)
                {
                    $sort_coloumn = "caste";
                }
                $sort_sort = $order['dir'];
 
              
            }
            }
        
            
       // if($school_id){
		  $totalRecords = $this->student->get_student_list_total($class_id, $school_id, $school->academic_year_id,$search_text);
         
           $students = $this->student->get_student_list($class_id, $school_id, $school->academic_year_id,$start,$limit,$search_text, $sort_coloumn,$sort_sort);
           $condition = array();
           $sQuery = $this->db->last_query();
           $condition['school_id'] = $school_id;   
           $classes = $this->student->get_list('classes', $condition);
           $class_list = [];
           foreach($classes as $class) {
               $class_list[$class->id] = $class->name;
           }
           if($class_id) $condition['class_id'] = $class_id;   
           $sections = $this->student->get_list('sections', $condition);
           $section_list = [];
           foreach($sections as $section) {
               $section_list[$section->id] = $section->name;
           }
		$count = 1; 
		$data = array();

		if(isset($students) && !empty($students)){
			if($this->session->userdata('role_id') != GUARDIAN || $this->session->userdata('role_id') != TEACHER){
				foreach($students as $obj){
					$action='';
					if(has_permission(EDIT, 'student', 'student') && $obj->status_type == 'regular'){
						$action.= '<a href="'.site_url('student/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line("edit").' </a>';
                                                 } 
                                               if(has_permission(VIEW, 'student', 'student')){
                         $action.='<a href="javascript:void(0);" onclick="get_student_modal('. $obj->id.');"  data-toggle="modal" data-target=".bs-student-modal-lg" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').' </a>';
                                                    }
											if(has_permission(DELETE, 'student', 'student') && $obj->status_type == 'regular'){ 
                         $action .= '<a href="'.site_url('student/delete/'.$obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> '.$this->lang->line('delete').' </a>';
                                                }
                           if(has_permission(EDIT, 'student', 'student')){
							   $selected='';
							   if($obj->status_type == 'regular'){
								   $selected="selected='selected'";
							   }
								$dropselected='';
							   if($obj->status_type == 'drop'){
								   $dropselected="selected='selected'";
							   } 
							$passedselected='';
							   if($obj->status_type == 'passed'){
								   $passedselected="selected='selected'";
							   } 
$transferselected='';
							   if($obj->status_type == 'transfer'){
								   $transferselected="selected='selected'";
							   } 							   
                          $action .= '<select  class="form-control col-md-7 col-xs-12 status-type"  name="status_type"  id="status_type" onchange="update_status_type('.$obj->id.', this.value);"><option value="regular" '.$selected.'>'.$this->lang->line('regular').'</option><option value="drop" '.$dropselected.'>'.$this->lang->line('drop').'</option>';
						  $action.='<option value="transfer" '.$transferselected.'>'. $this->lang->line('transfer').'</option>                  <option value="passed" '.$passedselected.'>'.$this->lang->line('passed').'</option> </select>';
                                                }   
                     if(has_permission(EDIT, 'student', 'student')){
						 $sr='<input type="checkbox" class="delete_check" name="checkId[]" value="'. $obj->id.'"  />';
					 }   
else{
	$sr=$count;
}					
					 $data[] = array( 
					 
						  0=>$sr,
						  1=>$school->school_name,
						  2=>$obj->name,
						  3=>$obj->father_name,
						  4=>$obj->group,
						  5=> $class_list[$obj->class_id] ?? "",
						  6=>  $section_list[$obj->section_id] ?? "",
						  7=>$obj->admission_no,
						  8=>$obj->email,
                          9=>$obj->roll_no,
                          10=>$obj->mother_name,
                          11=>$obj->dob,
                          12=>$obj->phone,
                          13=>$obj->present_address,
                          14=>$obj->admission_date,
                          15=>$obj->gender,
                          16=>$obj->caste,
						  17=>$action
					   );

					   $count++;
				}
			}
		}
		else{
			$data=array();
		}
       
		//print_r($data); exit;
		$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data,
  "QUERY=> $sQuery"
);
echo json_encode($response);
exit;
	}
	 public function alumni($class_id = null) {

        check_permission(VIEW);
        
        if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/classes/index');
        }
        
        // for super admin 
        $school_id = '';
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');           
        }
		 
              
        if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
            $school_id = $this->session->userdata('school_id');
        }
        
        if($class_id && !$school_id){
            $class = $this->student->get_single('classes', array('id'=>$class_id));
            $school_id = $class->school_id;
        }
        
        $school = $this->student->get_school_by_id($school_id);
        
        if($this->session->userdata('role_id') == STUDENT){
          $class_id =  $this->session->userdata('class_id');
        }
       
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        
       if($school_id){
          //  $this->data['students'] = $this->student->getAllAlumniStudentList($class_id, $school_id, $school->academic_year_id);
        } else {
            //$this->data['students'] = $this->student->getAllAlumniStudentList($class_id, $school_id, $school->academic_year_id);
        }

                
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['class_list'] =$this->classes->get_class_list($condition['school_id'] );

            $this->data['classes'] =  $this->data['class_list'] ;
            $this->data['guardians'] = $this->student->get_list('guardians', $condition, '','', '', 'id', 'ASC');


            $this->data['types']      = $this->student->get_list('student_types', $condition, '','', '', 'id', 'ASC'); 
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_student') . ' | ' . SMS);
        $this->layout->view('student/alumni', $this->data);
    }
    public function unalumni($student_id){
        $dropped_student = $this->student->get_single('students', array('id'=>$student_id,'status_type'=>'drop'));
        if(!empty( $dropped_student ))
        {
            if($this->student->unalumni_student($student_id))
            {
                success($this->lang->line('update_success'));
                redirect('/student/alumni');
            }
            else
            {
                error($this->lang->line('update_faild'));
                redirect('/student/alumni');
            }
        }
        else
        {
            error('Only droped students can be updated');
            redirect('/student/alumni');
        }
        
        
        
    }
	public function get_list_alumni(){
		         
        error_on();
        // for super admin        
		$school_id = '';
		$start=null;
		$limit=null;
        if($_POST){
            
            $school_id = $this->input->post('school_id');
            $class_id  = $this->input->post('class_id');  
			$start = $this->input->post('start');
            $limit  = $this->input->post('length');   
			$draw = $this->input->post('draw');		
            $order_cols  = $this->input->post('order');   	
        }				$search_text='';

        if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
                        $search_text=$_POST['search']['value'];
                    }
		 
              
         if((!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN) && (!$school_id && $this->session->userdata('dadmin') != 1)){
            $school_id = $this->session->userdata('school_id');
        }
        
        if($class_id && !$school_id){
            $class = $this->student->get_single('classes', array('id'=>$class_id));
            $school_id = $class->school_id;
        }
        
        $school = $this->student->get_school_by_id($school_id);
        
        if($this->session->userdata('role_id') == STUDENT){
          $class_id =  $this->session->userdata('class_id');
        }

        $sort_coloumn = "";
        if(!empty($order_cols))
        {
            foreach($order_cols as $order)
            {
                if($order['column'] == 1)
                {
                    $sort_coloumn = "school_name";
                }
                elseif($order['column'] == 2)
                {
                    $sort_coloumn = "name";
                }
                elseif($order['column'] == 3)
                {
                    $sort_coloumn = "group";
                }
                elseif($order['column'] == 4)
                {
                    $sort_coloumn = "father_name";
                }
                elseif($order['column'] == 5)
                {
                    $sort_coloumn = "class_name";
                }
                elseif($order['column'] == 6)
                {
                    $sort_coloumn = "section_name";
                }
                elseif($order['column'] == 7)
                {
                    $sort_coloumn = "roll_no";
                }
                elseif($order['column'] == 8)
                {
                    $sort_coloumn = "email";
                }
                elseif($order['column'] == 9)
                {
                    $sort_coloumn = "admission_no";
                }
                elseif($order['column'] == 14)
                {
                    $sort_coloumn = "status_type";
                }
               
                elseif($order['column'] == 16)
                {
                    $sort_coloumn = "alumni_reason";
                }
                elseif($order['column'] == 17)
                {
                    $sort_coloumn = "academic_year";
                }
                
                $sort_sort = $order['dir'];
 
              
            }
        }
       
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_school_id'] = $school_id;
        
      // if($school_id){       
       
	     $totalRecords = $this->student->getAllAlumniStudentListTotal($class_id, $school_id, $school->academic_year_id, $search_text, $sort_coloumn, $sort_sort);
           $students = $this->student->getAllAlumniStudentList($class_id, $school_id, $school->academic_year_id,$start,$limit, $search_text, $sort_coloumn, $sort_sort);
		$count = 1; 
		$data = array();

		if(isset($students) && !empty($students)){
			if($this->session->userdata('role_id') != GUARDIAN || $this->session->userdata('role_id') != TEACHER){
				foreach($students as $obj){
					$action='';
					if(has_permission(EDIT, 'student', 'student') ){
						$action.= '<a href="'.site_url('student/edit/'.$obj->id."?alumni").'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line("edit").' </a>';
                                                 } 
                    if(has_permission(VIEW, 'student', 'student')){
                         $action.='<a href="javascript:void(0);" onclick="get_student_modal('. $obj->id.');"  data-toggle="modal" data-target=".bs-student-modal-lg" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').' </a>';
                                                    }
						if(has_permission(DELETE, 'student', 'student') ){ 
                         $action .= '<a href="'.site_url('student/delete/'.$obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> '.$this->lang->line('delete').' </a>';
                                                }   
                        if(has_permission(EDIT, 'student', 'student')  && $obj->status_type == "drop"){ 
                            $action .= '<a href="'.site_url('student/unalumni/'.$obj->id).'" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Unalumni </a>';
                                                    }      
                    switch($obj->status_type) {
                        case "drop" : 
                            $alumni_reason = $obj->drop_reason;
                            $alumni_date =     $obj->drop_date;
                            break;
                        case "transfer" : 
                            $alumni_reason = $obj->transfer_reason;
                            $alumni_date = $obj->transfer_date;
                            break;
                        default : 
                        $alumni_reason = $obj->date_of_seperation;
                        $alumni_date = $obj->date_of_seperation;

                    }                     
                                                                           
					 $data[] = array( 
						  0=>$count,
						  1=>$obj->school_name,
						  2=>$obj->name,
						  3=>$obj->group,
                          4=>$obj->father_name,
						  5=>$obj->class_name,
						  6=>$obj->section_name,
						  7=>$obj->roll_no,
						  8=>$obj->email,
                         
                          9=>$obj->admission_no,
                          10=>$obj->mother_name,
                          11=>$obj->dob,
                          12=>$obj->phone,
                          13=>$obj->present_address,
                          14=> $obj->status_type,
                          15=> $alumni_date,
                          16=> $alumni_reason,
                          17=>$obj->academic_year,
						  18=>$action
                          
					   );
					   $count++;
				}
			}
		}
		else{
			$data=array();
		}
		//print_r($data); exit;
		$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
exit;
	}

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Student" user interface                 
    *                    and process to store "Student" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            
            if(!isset($_POST['name']))
            {
                $_POST['name'] = $_POST['fname'].' '.$_POST['lname'];
            }
            $this->_prepare_student_validation();
            
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_student_data();
                $insert_id = $this->student->insert('students', $data);
                if ($insert_id) {
                    $data['student_id'] = $insert_id;
                    //$this->__invoice_creation($data);
                    $enrollment_id = $this->__insert_enrollment($insert_id);
                    $data['student_id'] = $insert_id;
                    $data['enrollment_id'] = $enrollment_id;
                   
                    create_log('Has been added a srtudent studdent : '. $data['name']);    
                    success($this->lang->line('insert_success'));
                    redirect('student/index/'.$this->input->post('class_id'));
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('student/add/'.$this->input->post('class_id'));
                }
            } else {

                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
                $this->data['school_id'] = $_POST['school_id'];
            }
        }
        
        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }

        $this->data['class_id'] = $class_id;
        $this->data['students'] = $this->student->get_student_list($class_id);
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
         
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){  
            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['classes'] = $this->student->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['discounts'] = $this->student->get_list('discounts', $condition, '','', '', 'id', 'ASC');
            $this->data['guardians'] = $this->student->get_list('guardians', $condition, '','', '', 'id', 'ASC');
            $this->data['class_list'] = $this->student->get_list('classes', $condition, '','', '', 'id', 'ASC');
            $this->data['types']      = $this->student->get_list('student_types', $condition, '','', '', 'id', 'ASC');
        }
        
        $this->data['schools'] = $this->schools;
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Student" user interface                 
    *                    with populate "Student" value 
    *                    and process to update "Student" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/index');     
        }
        
        $student = $this->student->get_single('students', array('id'=>$id));        
        $school = $this->student->get_school_by_id($student->school_id);
       
        if ($_POST) {
            //$data['student_id'] = $id;
            //$this->__invoice_creation($data);
            if(!isset($_POST['name']))
            {
                $_POST['name'] = $_POST['fname'].' '.$_POST['lname'];
            }
            $this->_prepare_student_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_student_data();
                 //$data['student_id'] = $id;
                //$this->__invoice_creation($data);
                $updated = $this->student->update('students', $data, array('id' =>$id));
              
                if ($updated) {
                    $this->__update_enrollment();
                    create_log('Has been updated a srtudent studdent : '. $data['name']);  
                    success($this->lang->line('update_success'));
                    redirect('student/index/'.$this->input->post('class_id'));
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('student/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['student'] = $this->student->get_single_student($this->input->post('id'), $school->academic_year_id);
            }
        }

        if ($id) {            
            
            $this->data['student'] = $this->student->get_single_student($id, $school->academic_year_id);

            if (!$this->data['student']) {
                redirect('student/index');
            }
        }
        
        $class_id = $this->data['student']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 

        $school = $this->student->get_school_by_id($this->data['student']->school_id);
        
        $this->data['class_id'] = $class_id;
       // $this->data['students'] = $this->student->get_student_list($class_id, $school->id, $school->academic_year_id);
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
          
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }else{
            $condition['school_id'] = $this->data['student']->school_id;
            
        }
        
        $this->data['discounts'] = $this->student->get_list('discounts', $condition, '','', '', 'id', 'ASC');
        $this->data['classes'] = $this->student->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['guardians'] = $this->student->get_list('guardians', $condition, '','', '', 'id', 'ASC');
        $this->data['class_list'] = $this->student->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['types']      = $this->student->get_list('student_types', $condition, '','', '', 'id', 'ASC');
        
        $this->data['school_id'] = $this->data['student']->school_id;
        $this->data['filter_school_id'] = $this->data['student']->school_id;
        
        $this->data['schools'] = $this->schools;
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }
    public function  bulk_pass()
    {
        $data['error'] = true;
        $data['response'] = "";
        if ($_POST) {
            if(isset($_POST['student_ids']))
            {
                $student_ids = $_POST['student_ids'];
                if(!empty($student_ids))
                {
                    $this->student->bulk_pass_student($student_ids);
                    $data['error'] = false;
                }
            }
        }
        echo json_encode($data); die();
    }

    
     /*****************Function get_single_student**********************************
     * @type            : Function
     * @function name   : get_single_student
     * @description     : "Load single student information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_student(){
        
        // echo "<pre>";
        // print_r(($_POST));exit;

        $this->load->helper('report');
        $student_id = $this->input->post('student_id');
        
        $student = $this->student->get_single('students', array('id'=>$student_id));
               
        $school = $this->student->get_school_by_id($student->school_id);
        
        $this->data['student'] = $this->student->get_single_student($student_id, $school->academic_year_id);                       
        $this->data['guardian'] = $this->student->get_single_guardian($this->data['student']->guardian_id);
        
        $this->data['days'] = 31;
        $this->data['academic_year_id'] = $school->academic_year_id;
        $this->data['class_id'] = $this->data['student']->class_id;
        $this->data['section_id'] = $this->data['student']->section_id;
        $this->data['student_id'] = $student_id;
        $this->data['school_id'] = $student->school_id;
        
        $this->data['exams'] = $this->student->get_list('exams', array('status' => 1, 'school_id'=>$student->school_id, 'academic_year_id' => $school->academic_year_id), '', '', '', 'id', 'ASC');
        $this->data['invoices'] = $this->student->get_invoice_list($student->school_id, $student_id);  
        $this->data['activity'] = $this->student->get_activity_list($student_id);  
        
        echo $this->load->view('get-single-student', $this->data);
    }
    
    
        
    /*****************Function _prepare_student_validation**********************************
    * @type            : Function
    * @function name   : _prepare_student_validation
    * @description     : Process "Student" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_student_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        if (!$this->input->post('id')) {
            $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|callback_username');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|min_length[5]|max_length[30]');
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
            $this->form_validation->set_rules('roll_no', $this->lang->line('roll_no'), 'trim|required|callback_roll_no');          
        }

        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email');
        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');
        $this->form_validation->set_rules('type_id', $this->lang->line('student_type'), 'trim');
        
        $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), 'trim|required');
        $this->form_validation->set_rules('admission_date', $this->lang->line('admission_date'), 'trim|required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');

    
        $this->form_validation->set_rules('registration_no', $this->lang->line('registration_no'), 'trim');
        $this->form_validation->set_rules('group', $this->lang->line('group'), 'trim');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required');
        $this->form_validation->set_rules('dob', $this->lang->line('birth_date'), 'trim|required');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood_group'), 'trim');
        $this->form_validation->set_rules('present_address', $this->lang->line('present_address'), 'trim');
        $this->form_validation->set_rules('permanent_address', $this->lang->line('permanent_address'), 'trim');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim');
        $this->form_validation->set_rules('other_info', $this->lang->line('other_info'), 'trim');
        
		$this->form_validation->set_rules('father_name', $this->lang->line('father_name'), 'trim|required');
		$this->form_validation->set_rules('mother_name', $this->lang->line('mother_name'), 'trim|required');
        
        if ($this->input->post('is_guardian') == 'exist_guardian') {
            $this->form_validation->set_rules('guardian_id',  $this->lang->line('guardian_name'), 'trim|required');
        }
        
        if ($this->input->post('is_guardian') != 'exist_guardian' && !$this->input->post('id')) {
            $this->form_validation->set_rules('gud_username',   $this->lang->line('username'), 'trim|required');
            $this->form_validation->set_rules('gud_name',   $this->lang->line('name'), 'trim|required');
            $this->form_validation->set_rules('gud_phone',  $this->lang->line('phone'), 'trim|required');
        }
        
        $this->form_validation->set_rules('photo', $this->lang->line('photo'), 'trim|callback_photo');
        $this->form_validation->set_rules('transfer_certificate', $this->lang->line('transfer_certificate'), 'trim|callback_transfer_certificate');
        $this->form_validation->set_rules('father_photo', $this->lang->line('father_photo'), 'trim|callback_father_photo');
        $this->form_validation->set_rules('mother_photo', $this->lang->line('mother_photo'), 'trim|callback_mother_photo');
        
        
    }
                        
    /*****************Function username**********************************
    * @type            : Function
    * @function name   : username
    * @description     : Unique check for "Student username" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function username() {
        if ($this->input->post('id') == '') {
            $username = $this->student->duplicate_check($this->input->post('username'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $username = $this->student->duplicate_check($this->input->post('username'), $this->input->post('id'));
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
	public function roll_no() {	
		$school_id=$this->input->post('school_id');
		$class_id=$this->input->post('class_id');
		$section_id=$this->input->post('section_id');
        if ($this->input->post('id') == '') {
            $roll_no = $this->student->duplicate_check_roll_no($this->input->post('roll_no'),null,$school_id,$class_id,$section_id);
            if ($roll_no) {
                $this->form_validation->set_message('roll_no', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $roll_no = $this->student->duplicate_check_roll_no($this->input->post('roll_no'), $this->input->post('id'),$school_id,$class_id,$section_id);
            if ($roll_no) {
                $this->form_validation->set_message('roll_no', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
       
    
    /*****************Function photo**********************************
    * @type            : Function
    * @function name   : photo
    * @description     : validate student profile photo                 
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function photo() {
        if ($_FILES['photo']['name']) {
            $name = $_FILES['photo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
    
    /*****************Function transfer_certificate**********************************
    * @type            : Function
    * @function name   : transfer_certificate
    * @description     : validate student transfer_certificate                
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function transfer_certificate() {
        if ($_FILES['transfer_certificate']['name']) {
            $name = $_FILES['transfer_certificate']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('transfer_certificate', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
    
    /*****************Function father_photo**********************************
    * @type            : Function
    * @function name   : father_photo
    * @description     : validate student father_photo               
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function father_photo() {
        if ($_FILES['father_photo']['name']) {
            $name = $_FILES['father_photo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('father_photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
    
    /*****************Function mother_photo**********************************
    * @type            : Function
    * @function name   : mother_photo
    * @description     : validate student mother_photo               
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function mother_photo() {
        if ($_FILES['mother_photo']['name']) {
            $name = $_FILES['mother_photo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('mother_photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

       
    /*****************Function _get_posted_student_data**********************************
    * @type            : Function
    * @function name   : _get_posted_student_data
    * @description     : Prepare "Student" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_student_data() {

        $items = array();

        $items[] = 'school_id';
        $items[] = 'type_id';
        $items[] = 'admission_no';       
        //$items[] = 'national_id';
		// $items[] = 'rte';
        $items[] = 'registration_no';
        $items[] = 'group';
        $items[] = 'name';
        $items[] = 'phone';
        $items[] = 'email';
        $items[] = 'gender';
        $items[] = 'blood_group';        
        $items[] = 'religion';
        $items[] = 'caste';
        $items[] = 'discount_id';
        
        $items[] = 'present_address';
        $items[] = 'permanent_address';
        
        $items[] = 'second_language';
        $items[] = 'previous_school';
        $items[] = 'previous_class';
        
        $items[] = 'father_name';
        $items[] = 'father_phone';
        $items[] = 'father_education';
        $items[] = 'father_profession';
        $items[] = 'father_designation';
        
        $items[] = 'mother_name';
        $items[] = 'mother_phone';
        $items[] = 'mother_education';
        $items[] = 'mother_profession';
        $items[] = 'mother_designation';
        
        $items[] = 'health_condition';
        $items[] = 'other_info';
		$items[] = 'alternate_name';
		$items[] = 'reservation_category';
		$items[] = 'bpl';
		$items[] = 'adhar_no';
        $items[] = 'sm_id';
		$items[] = 'family_id';
		$items[] = 'reason_of_seperation';
		$items[] = 'bank_details';	
		
		$items[] = 'rural_urban';	
		$items[] = 'physical_disability';	
		$items[] = 'medium_of_instruction';	
		$items[] = 'free_education';	
		$items[] = 'previous_year_attended_days';	
		$items[] = 'facilities_by_cwsn';	
		$items[] = 'no_of_uniform_sets';	
		$items[] = 'free_text_books';	
		$items[] = 'free_transport';	
		$items[] = 'free_escort';	
		$items[] = 'mdm_benificiary';	
		$items[] = 'free_hostel';	
		$items[] = 'special_training';	
		$items[] = 'appeared_in_last_exam';	
		$items[] = 'last_exam_passed';	
		$items[] = 'last_exam_marks';	
		$items[] = 'trade_sector';	
		$items[] = 'iron_folic_acid_tablets';	
		$items[] = 'deworming_tablets';	
		$items[] = 'vitamin_a_supplement';	
		
        //$items[] = 'is_guardian';
        $items[] = 'relation_with';

        $data = elements($items, $_POST);

		$facilities='';
		if(isset($_POST['facilities']) && !empty($_POST['facilities'])){
			$facilities=implode(",",$_POST['facilities']);
		}
		$data['facilities']=$facilities;
		
        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
        $data['admission_date'] = date('Y-m-d', strtotime($this->input->post('admission_date')));
		if($this->input->post('date_of_seperation')!=''){
			$data['date_of_seperation'] = date('Y-m-d', strtotime($this->input->post('date_of_seperation')));
		}
		else{
			$data['date_of_seperation'] =NULL;
		}
        $data['age'] = floor((time() - strtotime($data['dob'])) / 31556926);
        
        $school = $this->student->get_school_by_id($data['school_id']);

        if(!$school->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('student/index');
        }

        if ($this->input->post('id')) {
            
            $data['guardian_id'] = $this->input->post('guardian_id');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            $data['status_type'] = $this->input->post('status_type');
            
        } else {    
            
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            $data['status'] = 1;
            $data['status_type'] = 'regular';
            
             // create guardian and guardian user if not exist
            if($this->input->post('is_guardian') == 'exist_guardian'){
                
                $data['guardian_id'] = $this->input->post('guardian_id');
                
            }else{

                $info = array();
                $guardian = array();    

                $info['role_id']  = GUARDIAN;
                $info['name']     =  $this->input->post('gud_name');
                $info['phone'] = $this->input->post('gud_phone'); 
                $info['email'] = $this->input->post('gud_email'); 
                $info['username'] = $school->school_code."-".$data['admission_no']."_P"; 
                $info['password'] = "welcome";
                
                // now creating guardian user
                $guardian['user_id'] = $this->student->create_custom_user($info);     

                // create guardian....                
                $guardian['school_id']    = $data['school_id'];
                $guardian['name']    = $this->input->post('gud_name');
                $guardian['phone']   = $this->input->post('gud_phone');
                $guardian['email']   = $this->input->post('gud_email');
                $guardian['profession']   = $this->input->post('gud_profession');
                $guardian['religion']   = $this->input->post('gud_religion');
                $guardian['national_id'] = $this->input->post('gud_national_id');
                $guardian['present_address']   = $this->input->post('gud_present_address');
                $guardian['permanent_address']   = $this->input->post('gud_permanent_address');
                $guardian['other_info']   = $this->input->post('gud_other_info');
				$guardian['id_type']   = $this->input->post('gud_id_type');
				$guardian['id_no']   = $this->input->post('gud_id_no');
				
				if ($_FILES['gud_id_photo']['name']) {
					$guardian['id_photo'] = $this->_upload_gud_id_photo();
				}
		
                $guardian['created_at'] = date('Y-m-d H:i:s');
                $guardian['created_by'] = logged_in_user_id();
                $guardian['modified_at'] = date('Y-m-d H:i:s');
                $guardian['modified_by'] = logged_in_user_id();
                $guardian['status'] = 1;
                
                $data['guardian_id'] = $this->student->insert('guardians', $guardian);
            }
            
            
            // create user 
            $data['user_id'] = $this->student->create_user($school->school_code."-".$data['admission_no']);
        }

        if ($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_photo();
        }
        if ($_FILES['transfer_certificate']['name']) {
            $data['transfer_certificate'] = $this->_upload_transfer_certificate();
        }
        if ($_FILES['father_photo']['name']) {
            $data['father_photo'] = $this->_upload_father_photo();
        }
        if ($_FILES['mother_photo']['name']) {
            $data['mother_photo'] = $this->_upload_mother_photo();
        }

        return $data;
    }

           
    /*****************Function _upload_photo**********************************
    * @type            : Function
    * @function name   : _upload_photo
    * @description     : process to upload student profile photo in the server                  
    *                     and return photo file name  
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

                $destination = 'assets/uploads/student-photo/';

                $file_type = explode(".", $photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $photo_path);
                if($converted_file = webpConverter($destination . $photo_path,null, 415,515))
                {
                    $photo_path = get_filename($converted_file);
                }
                // need to unlink previous photo
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
    
        /*****************Function _upload_transfer_certificate**********************************
    * @type            : Function
    * @function name   : _upload_transfer_certificate
    * @description     : process to upload student transfer_certificate in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_photo string value 
    * ********************************************************** */
    private function _upload_transfer_certificate() {

        $prev_transfer_certificate = $this->input->post('prev_transfer_certificate');
        $transfer_certificate = $_FILES['transfer_certificate']['name'];
        $transfer_certificate_type = $_FILES['transfer_certificate']['type'];
        $return_transfer_certificate = '';
        if ($transfer_certificate != "") {
            if ($transfer_certificate_type == 'image/jpeg' || $transfer_certificate_type == 'image/pjpeg' ||
                    $transfer_certificate_type == 'image/jpg' || $transfer_certificate_type == 'image/png' ||
                    $transfer_certificate_type == 'image/x-png' || $transfer_certificate_type == 'image/gif') {

                $destination = 'assets/uploads/transfer-certificate/';

                $file_type = explode(".", $transfer_certificate);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $transfer_certificate_path = 'tc-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['transfer_certificate']['tmp_name'], $destination . $transfer_certificate_path);
                if($converted_file = webpConverter($destination . $transfer_certificate_path,null, 415,515))
                {
                    $transfer_certificate_path = get_filename($converted_file);
                }
                // need to unlink previous transfer_certificate
                if ($prev_transfer_certificate != "") {
                    if (file_exists($destination . $prev_transfer_certificate)) {
                        @unlink($destination . $prev_transfer_certificate);
                    }
                }

                $return_transfer_certificate = $transfer_certificate_path;
            }
        } else {
            $return_transfer_certificate = $prev_transfer_certificate;
        }

        return $return_transfer_certificate;
    }

    
               
    /*****************Function _upload_father_photo**********************************
    * @type            : Function
    * @function name   : _upload_father_photo
    * @description     : process to upload student profile photo in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_father_photo string value 
    * ********************************************************** */
    private function _upload_father_photo() {

        $prev_father_photo = $this->input->post('prev_father_photo');
        $father_photo = $_FILES['father_photo']['name'];
        $father_photo_type = $_FILES['father_photo']['type'];
        $return_father_photo = '';
        if ($father_photo != "") {
            if ($father_photo_type == 'image/jpeg' || $father_photo_type == 'image/pjpeg' ||
                    $father_photo_type == 'image/jpg' || $father_photo_type == 'image/png' ||
                    $father_photo_type == 'image/x-png' || $father_photo_type == 'image/gif') {

                $destination = 'assets/uploads/father-photo/';

                $file_type = explode(".", $father_photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $father_photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['father_photo']['tmp_name'], $destination . $father_photo_path);
                if($converted_file = webpConverter($destination . $father_photo_path,null, 415,515))
                {
                    $father_photo_path = get_filename($converted_file);
                }
                // need to unlink previous father_photo
                if ($prev_father_photo != "") {
                    if (file_exists($destination . $prev_father_photo)) {
                        @unlink($destination . $prev_father_photo);
                    }
                }

                $return_father_photo = $father_photo_path;
            }
        } else {
            $return_father_photo = $prev_father_photo;
        }

        return $return_father_photo;
    }
    
    
    
               
    /*****************Function _upload_mother_photo**********************************
    * @type            : Function
    * @function name   : _upload_mother_photo
    * @description     : process to upload mother profile photo in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_mother_photo string value 
    * ********************************************************** */
    private function _upload_mother_photo() {

        $prev_mother_photo = $this->input->post('prev_mother_photo');
        $mother_photo = $_FILES['mother_photo']['name'];
        $mother_photo_type = $_FILES['mother_photo']['type'];
        $return_mother_photo = '';
        if ($mother_photo != "") {
            if ($mother_photo_type == 'image/jpeg' || $mother_photo_type == 'image/pjpeg' ||
                    $mother_photo_type == 'image/jpg' || $mother_photo_type == 'image/png' ||
                    $mother_photo_type == 'image/x-png' || $mother_photo_type == 'image/gif') {

                $destination = 'assets/uploads/mother-photo/';

                $file_type = explode(".", $mother_photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $mother_photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['mother_photo']['tmp_name'], $destination . $mother_photo_path);
                if($converted_file = webpConverter($destination . $mother_photo_path,null, 415,515))
                {
                    $mother_photo_path = get_filename($converted_file);
                }
                // need to unlink previous mother_photo
                if ($prev_mother_photo != "") {
                    if (file_exists($destination . $prev_mother_photo)) {
                        @unlink($destination . $prev_mother_photo);
                    }
                }

                $return_mother_photo = $mother_photo_path;
            }
        } else {
            $return_mother_photo = $prev_mother_photo;
        }

        return $return_mother_photo;
    }
	private function _upload_gud_id_photo() {

        $prev_gud_id_photo = $this->input->post('prev_gud_id_photo');
        $gud_id_photo = $_FILES['gud_id_photo']['name'];
        $gud_id_photo_type = $_FILES['gud_id_photo']['type'];
        $return_gud_id_photo = '';
        if ($gud_id_photo != "") {
            if ($gud_id_photo_type == 'image/jpeg' || $gud_id_photo_type == 'image/pjpeg' ||
                    $gud_id_photo_type == 'image/jpg' || $gud_id_photo_type == 'image/png' ||
                    $gud_id_photo_type == 'image/x-png' || $gud_id_photo_type == 'image/gif') {

                $destination = 'assets/uploads/gud-id-photo/';

                $file_type = explode(".", $gud_id_photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $gud_id_photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['gud_id_photo']['tmp_name'], $destination . $gud_id_photo_path);
                if($converted_file = webpConverter($destination . $gud_id_photo_path))
                {
                    $gud_id_photo_path = get_filename($converted_file);
                }
                // need to unlink previous mother_photo
                if ($prev_gud_id_photo != "") {
                    if (file_exists($destination . $prev_gud_id_photo)) {
                        @unlink($destination . $prev_gud_id_photo);
                    }
                }

                $return_gud_id_photo = $gud_id_photo_path;
            }
        } else {
            $return_gud_id_photo = $prev_gud_id_photo;
        }

        return $return_gud_id_photo;
    }
    

        
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Student" data from database                  
    *                     also delete all relational data
    *                     and unlink student photo from server   
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/index');
        }
        
        $student = $this->student->get_single('students', array('id' => $id));
        
        // get invoices
        $invoices = $this->student->get_invoice_list($student->school_id, $id); 
        if(!empty($invoices)){
            error($this->lang->line('this_student_have_unpaid_invoice'));
            redirect('student/index');
        }        
        
        $school = $this->student->get_school_by_id($student->school_id);
        $enroll = $this->student->get_single('enrollments', array('student_id' => $student->id, 'academic_year_id'=>$school->academic_year_id));
               
            // delete student data
        if ($this->student->delete('students', array('id' => $id))) {

            // delete student login data
            $this->student->delete('users', array('id' => $student->user_id));

            // delete student enrollments
            $this->student->delete('enrollments', array('student_id' => $student->id));

            // delete student hostel_members
            $this->student->delete('hostel_members', array('user_id' => $student->user_id));

            // delete student transport_members
            $this->student->delete('transport_members', array('user_id' => $student->user_id));

            // delete student library_members
            $this->student->delete('library_members', array('user_id' => $student->user_id));

            // delete student resume and photo
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/student-photo/' . $student->photo)) {
                @unlink($destination . '/student-photo/' . $student->photo);
            }

            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('student/index/'.$enroll->class_id);
    }

        
    /*****************Function __insert_enrollment**********************************
    * @type            : Function
    * @function name   : __insert_enrollment
    * @description     : save student info to enrollment while create a new student                  
    * @param           : $insert_id integer value
    * @return          : intiger 
    * ********************************************************** */
    private function __insert_enrollment($insert_id) {
        $data = array();
        
        $school = $this->student->get_school_by_id($this->input->post('school_id'));
        
        $data['student_id'] = $insert_id;
        $data['school_id'] = $this->input->post('school_id');
        $data['class_id'] = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['academic_year_id'] = $school->academic_year_id;
        $data['roll_no'] = $this->input->post('roll_no');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['status'] = 1;
        $data['rte'] = $this->input->post('rte');
        

        $insert_id = $this->db->insert('enrollments', $data);
    }
     /*****************Function __invoice_creation**********************************
    * @type            : Function
    * @function name   : __invoice_creation
    * @description     : Invoice creation for student                  
    * @param           : $data array value
    * @return          : null 
    * ********************************************************** */
    private function __invoice_creation($data) {
        $school = $this->student->get_school_by_id($this->input->post('school_id'));
        if(!empty($school ))
        {
            $school_id      = $school->id;
            $student_id     = $data['student_id'];
            $financial_year = $school->financial_year_id;
            $class_id       = $this->input->post('class_id');
            $income_head    = $this->student->check_general_fee($school_id ,$financial_year); 
            $fee_amount = $this->student->get_single_amount($school_id,$class_id, $income_head->id); 
           
            if(!empty($income_head) && !empty($fee_amount))
            {
                $fee_amount = $fee_amount->fee_amount;
                $invoice_no = $this->student->generate_invoice_no($school_id); 
                $invoice_data = array("invoice_type"=>'fee',"school_id" =>$school_id,"class_id" => $class_id ,"student_id" =>$student_id,"month" => date("d-m-Y"),'paid_status' =>'unpaid','date'=>date('Y-m-d')
                                ,'status'=>1,'created_at'=>date('Y-m-d H:i:s'),'academic_year_id' =>$school->academic_year_id,'created_by' => logged_in_user_id());
                $invoice_data['income_head_id'] = $income_head->id;
                $invoice_data['invoice_type']   = $income_head->head_type;
                $invoice_data['custom_invoice_id'] = $this->student->get_custom_id('invoices', 'INV');
                $discount = $this->student->get_discount($data['discount_id']);
                $invoice_data['discount'] = 0.00;
                $invoice_data['is_applicable_discount'] =0;
                $invoice_data['note'] = "admission";
                $fee_amount_after_discount  = $fee_amount;
                if ($data['discount_id'] && !empty($discount)) {
                    if($discount->type != "amount")
                    {
                        $invoice_data['discount']  = $discount->amount * $fee_amount / 100;
                    }
                    else $invoice_data['discount']  = $discount->amount;
                    $invoice_data['discount'] = $invoice_data['discount'];
                    $invoice_data['is_applicable_discount'] =1;
                    $fee_amount_after_discount = $fee_amount - $invoice_data['discount'];
                }
                //class_fee = 10000
                //discount  = 1000
                //fee_amount_after_discount = 900
                // net_amount = 9000
                //   gross_amount = 10000
                // due_amount = 900
                $invoice_data['due_amount']     = $fee_amount_after_discount;	
                $invoice_data['net_amount']     = $fee_amount_after_discount;
                $invoice_data['gross_amount']   =  $fee_amount;

                $insert_id = $this->student->insert('invoices', $invoice_data);

                $sql = "SELECT * FROM  emi_fee  WHERE emi_fee.income_heads_id = '{$income_head->id}'";
                $data1 =  $this->db->query($sql)->result();
                if (!empty($data1)) {
                    unset($invoice_data['discount'] );
                    foreach ($data1 as $obj) {
                        $invoice_data['emi_type']     = "";
                        if(strtotime($obj->emi_start_date) < time())
                        {
                            $invoice_data['custom_invoice_id'] = $this->student->get_custom_id('invoices', 'INV');
                            $invoice_data['emi_type']       = $obj->id;
                            $installment = $fee_amount*$obj->emi_per/100;
                            $invoice_data['gross_amount']   =  $installment;
                            $invoice_data['net_amount']     =  $installment;					
                            $invoice_data['due_amount']     = $installment;	
                            $insert_id = $this->student->insert('invoices', $invoice_data);

                        }
                       
                    }
                }
            }
        }

    }
    
    
    /*****************Function __update_enrollment**********************************
    * @type            : Function
    * @function name   : __update_enrollment
    * @description     : update student info to enrollment while update a student                  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function __update_enrollment() {

        $school = $this->student->get_school_by_id($this->input->post('school_id'));
         
        $data = array();
        $data['school_id'] = $this->input->post('school_id');
        $data['class_id'] = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['roll_no'] = $this->input->post('roll_no');
        $data['modified_at'] = date('Y-m-d H:i:s');

        $data['rte'] = $this->input->post('rte');
        $data['modified_by'] = logged_in_user_id();

        //$this->db->where('student_id', $this->input->post('id'));
        //$this->db->where('academic_year_id', $school->academic_year_id);
        
        $condition = array();
        $condition['student_id'] = $this->input->post('id');
        $condition['academic_year_id'] = $school->academic_year_id;
        $this->db->update('enrollments', $data, $condition);
               
    }
    
    
    
        
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Student view" user interface                 
    *                    with class wise listing    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($id = null) {

        check_permission(VIEW);
        
       
        $this->load->helper('report');
        
        $student = $this->student->get_single('students', array('id'=>$id));
               
        $school = $this->student->get_school_by_id($student->school_id);
        
        $this->data['student'] = $this->student->get_single_student($id, $school->academic_year_id);                       
        $this->data['guardian'] = $this->student->get_single_guardian($this->data['student']->guardian_id);
        
        $this->data['days'] = 31;
        $this->data['academic_year_id'] = $school->academic_year_id;
        $this->data['class_id'] = $this->data['student']->class_id;
        $this->data['section_id'] = $this->data['student']->section_id;
        $this->data['student_id'] = $id;
        $this->data['school_id'] = $student->school_id;
        
        $this->data['exams'] = $this->student->get_list('exams', array('status' => 1, 'school_id'=>$student->school_id, 'academic_year_id' => $school->academic_year_id), '', '', '', 'id', 'ASC');
        $this->data['invoices'] = $this->student->get_invoice_list($student->school_id, $id);  
        $this->data['activity'] = $this->student->get_activity_list($id);
        
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }else{
            $condition['school_id'] = $this->data['student']->school_id;            
        }
        $this->data['classes'] = $this->student->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        $this->data['class_id'] = $this->data['student']->class_id;
        $this->data['filter_class_id'] = $this->data['student']->class_id;
        $this->data['filter_school_id'] = $student->school_id;
        
        
        $this->data['schools'] = $this->schools;
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('manage_student') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }


    
    public function dropStudentForm() {
        $student_id = $this->input->post('student_id');
        $this->data['student_id'] = $student_id;
        echo $this->load->view('drop-student', $this->data);
    }

    public function dropStudent() {
        if($this->input->post('submit')) {
            $studentId = $this->input->post('student_id');
            $dropDate = $this->input->post('drop-date');
            $dropReason = $this->input->post('drop-reason');
            $update = $this->student->dropStudent($studentId, $dropDate, $dropReason);
            redirect('student/index'); 
        }   
    }

    // ak

     public function  pass_student() {
        $data = $this->input->post('student_id');
        if($data) {
            $studentId = $this->input->post('student_id');
            $update = $this->student->pass_student($studentId);
            redirect('student/index'); 
        }   
    }
   
    // ak

    public function transferStudentForm() {
        $student_id = $this->input->post('student_id');
        $this->data['student_id'] = $student_id;
        echo $this->load->view('transfer-student', $this->data);
    }

    public function transferStudent() {
        if($this->input->post('submit')) {
            $studentId = $this->input->post('student_id');
            $transferDate = $this->input->post('transfer-date');
            $transferReason = $this->input->post('transfer-reason');
            $update = $this->student->transferStudent($studentId, $transferDate, $transferReason);
            //$this->index();
            redirect('student/index'); 
        }   
    }
	public function move(){		
		$student_data = array();
		$detail=array();
		parse_str($_POST['student_data'], $student_data);
		parse_str($_POST['detail'], $detail);		
		if(!empty($student_data['checkId'])){
			foreach($student_data['checkId'] as $student_id){
				// update to enrollemnt
                $school = $this->student->get_school_by_id($detail['move_school_id']);
				$update_arr=array();
				$update_arr['school_id']=$detail['move_school_id'];
				$update_arr['class_id']=$detail['class_id'];
				$update_arr['section_id']=$detail['section_id'];
				$updated = $this->student->update('enrollments', $update_arr, array('student_id' => $student_id,"academic_year_id"=>@$school->academic_year_id));
			}
		}
		print 1;
		exit;
	}
}   
