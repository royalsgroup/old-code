<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Examresult.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Examresult
 * @description     : Manage exam term result and prepare promotion to next class.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Examresult extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Examresult_Model', 'result', true);        
        error_on();
    }

    
        
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Exam result sheet" user interface                 
    *                    with class/section wise filtering option    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);
        // error_on();
        if ($_POST) {

            $school_id = $this->input->post('school_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $school = $this->result->get_school_by_id($school_id);
            
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('exam/examresult');
            }
            
            $this->data['students'] = $this->result->get_student_list_with_result($school_id,$exam_id, $class_id, $section_id, $school->academic_year_id);
            // debug_a( $this->data['students'] );

            $condition = array(   
                'school_id' => $school_id,
                'exam_id' => $exam_id,
                'class_id' => $class_id,
                'academic_year_id' => $school->academic_year_id,
            );
            
            if($section_id){
                $condition['section_id'] = $section_id;
            }
            $schedules = $this->result->get_scheduled_subjects($school_id , $class_id , $school->academic_year_id,$exam_id ,  $section_id, 0);
            // $schedules = $this->result->get_list('exam_schedules', $condition);
            $scheduled_subjects = [];
            $sort_arrayq = array();

            foreach ($schedules as $obj) {
                $scheduled_subjects[$obj->subject_id][] =  $obj;
                $sort_array[$obj->subject_id]=  $obj->subject_id;

            }
            $Optionalschedules = $this->result->get_scheduled_subjects($school_id , $class_id ,$school->academic_year_id,$exam_id ,  $section_id, 1);

            // $Optionalschedules = $this->result->get_list('exam_schedules', $condition);
            $optional_scheduled_subjects = [];
            foreach ($Optionalschedules as $obj) {
                $optional_scheduled_subjects[$obj->subject_id][] =  $obj;
                $sort_array[$obj->subject_id]=  $obj->subject_id;
            }
            $students_processed = [];
            $subjects = $optional_subjects = [];
            $subject_exams = $optional_subject_exams = [];
            $data = $condition;
            if (!empty($this->data['students'])) {

                foreach ($this->data['students'] as $obj) {

                    $condition['student_id'] = $obj->id;
                    $result = $this->result->get_single('exam_results', $condition);
                    if(!isset($students_processed[$obj->student_id]))
                    {
                        $students_processed[$obj->student_id] = $obj;
                        $students_processed[$obj->student_id]->subjects = [];
                    }
                    if(isset($scheduled_subjects[$obj->subject_id]))
                    {
                        foreach($scheduled_subjects[$obj->subject_id] as $scheduled_subject)
                        {
                             $subject_exams[$obj->subject_id]['id'] = $scheduled_subject->subject_id;
                            if($scheduled_subject->max_mark>0) $subject_exams[$obj->subject_id]['written'] = $scheduled_subject->max_mark;
                            if($scheduled_subject->max_mark_p>0) $subject_exams[$obj->subject_id]['practical'] = $scheduled_subject->max_mark_p;
                            if($scheduled_subject->max_mark_v>0) $subject_exams[$obj->subject_id]['viva'] = $scheduled_subject->max_mark_v;;
                        }
                        $subjects[$obj->subject_id] = $obj->subject_name;

                    }
                    if(isset($optional_scheduled_subjects[$obj->subject_id]))
                    {
                        foreach($optional_scheduled_subjects[$obj->subject_id] as $scheduled_subject)
                        {
                            $optional_subject_exams[$obj->subject_id]['id'] = $scheduled_subject->subject_id;
                            if($scheduled_subject->max_mark>0) $optional_subject_exams[$obj->subject_id]['written'] = $scheduled_subject->max_mark;
                            if($scheduled_subject->max_mark_p>0) $optional_subject_exams[$obj->subject_id]['practical'] = $scheduled_subject->max_mark_p;
                            if($scheduled_subject->max_mark_v>0) $optional_subject_exams[$obj->subject_id]['viva'] = $scheduled_subject->max_mark_v;;
                        }
                        $optional_subjects[$obj->subject_id] = $obj->subject_name;
                    }

                    $students_processed[$obj->student_id]->subjects[$obj->subject_id] = $obj;
                    if (empty($result)) {
                        $data['school_id'] = $school_id;
                        $data['section_id'] = $obj->section_id;
                        $data['student_id'] = $obj->id;
                        $data['exam_id'] = $exam_id;
                        $data['class_id'] = $class_id;                       
                        $data['academic_year_id'] = $school->academic_year_id;
                        $data['status'] = 1;
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $data['created_by'] = logged_in_user_id();
                        $this->result->insert('exam_results', $data);
                    }
                }
            }
             $this->data['sort_array'] = $sort_array;

            $this->data['subject_exams'] = $subject_exams;
            $this->data['optional_subject_exams'] = $optional_subject_exams;

            $this->data['students'] = $students_processed;
            $this->data['subjects'] = $subjects;
            $this->data['optional_subjects'] = $optional_subjects;

            $this->data['grades'] = $this->result->get_list('grades', array('status' => 1, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
            $this->data['exam'] =  $this->result->get_single('exams', array('id'=>$exam_id, 'school_id'=>$school_id));
            
            $this->data['school_id'] = $school_id;
            $this->data['exam_id'] = $exam_id;
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['academic_year_id'] = $school->academic_year_id;
            
            $class = $this->result->get_single('classes', array('id'=>$class_id, 'school_id'=>$school_id));
            create_log('Has been process exam result for class: '. $class->name);
        }
        
        
        $condition = array();
        $condition['status'] = 1;  
        
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $school = $this->result->get_school_by_id($this->session->userdata('school_id'));
            $condition['school_id'] = $this->session->userdata('school_id');
            
            $this->data['classes'] = $this->result->get_list_new('classes', $condition, '','', '', 'id', 'ASC');
            $condition['academic_year_id'] = $school->academic_year_id;
            $this->data['exams'] = $this->result->get_list('exams', $condition, '', '', '', 'id', 'ASC');
        }

        $this->layout->title($this->lang->line('exam') . ' ' . $this->lang->line('result') . ' | ' . SMS);
        $this->layout->view('exam_result/index', $this->data);
    }

    
    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Process exam result and save into database                  
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            
            $school_id = $this->input->post('school_id');
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $school = $this->result->get_school_by_id($school_id);
            if(!$school->academic_year_id){
                error($this->lang->line('set_academic_year_for_school'));
                redirect('exam/examresult');
            }
            
            $condition = array(
                'school_id' => $school_id,
                'exam_id' => $exam_id,
                'class_id' => $class_id,
                'academic_year_id' => $school->academic_year_id,
            );
            
            if($section_id){
                $condition['section_id'] = $section_id;
            }

            $data = $condition;

            if (!empty($_POST['students'])) {

                foreach ($_POST['students'] as $key => $value) {

                    $condition['student_id'] = $value;
                    
                    $data['total_subject'] = $_POST['total_subject'][$value];
                    $data['total_mark'] = $_POST['total_mark'][$value];
                    $data['total_obtain_mark'] = $_POST['total_obtain_mark'][$value];
                    $data['avg_grade_point'] = $_POST['avg_grade_point'][$value];
                    $data['grade_id'] = $_POST['grade_id'][$value];
                    $data['remark'] = $_POST['remark'][$value];
                    $data['status'] = 1;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = logged_in_user_id();
                    
                    // process result status
                    $grade = $this->result->get_single('grades', array('id'=>$data['grade_id']));
                    if($grade->point <= 0){                        
                        $data['result_status'] = 'failed';
                    }else{
                        $data['result_status'] = 'passed'; 
                    }
                    
                    $this->result->update('exam_results', $data, $condition);
                }
            }
            
            $class = $this->result->get_single('classes', array('id'=>$class_id));
            create_log('Has been process and save exam result for class: '. $class->name);
            
            success($this->lang->line('insert_success'));
            redirect('exam/examresult');
        }

        $this->layout->title($this->lang->line('exam') . ' ' . $this->lang->line('result') . ' | ' . SMS);
        $this->layout->view('exam_result/index', $this->data);
    }

}
