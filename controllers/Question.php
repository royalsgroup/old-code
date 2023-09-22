<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Question extends MY_Controller
{
  public $sch_setting_detail = array();
    public function __construct()
    {
        parent::__construct();
		$this->load->model('question_model');
		$this->load->model('academic/Subject_Model', 'subject', true);              
    }

    public function index($school_id = null)
    {		
        
       // check_permission(VIEW);
       // $this->session->set_userdata('top_menu','Online_Examinations');
        //$this->session->set_userdata('sub_menu','Online_Examinations/Onlineexam');
		 $this->data['questions'] = $this->question_model->get_question_list($school_id);  
        
        $condition = array();             
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');  
			$this->data['subjects'] = $this->subject->get_list('subjects', $condition, '','', '', 'id', 'ASC');			
        }   
        $questionOpt          = getQuesOption();
        $this->data['questionOpt']  = $questionOpt;
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;		    
        $subject_result       = $this->subject->get();
        $this->data['subjectlist']  = $subject_result;      
		$this->data['list'] = TRUE;
		$this->layout->title($this->lang->line('question') . ' | ' . SMS);
        $this->layout->view('question/index', $this->data);
    }
  
    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('online_assign_view_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Online_Examinations');
        $this->session->set_userdata('sub_menu', 'Online_Examinations/Onlineexam');
        $data['id']         = $id;
        $data['title']      = 'student fees';
        $class              = $this->class_model->get();
        $data['classlist']  = $class;
        $onlineexam         = $this->onlineexam_model->get($id);
        $data['onlineexam'] = $onlineexam;
        $data['sch_setting']     = $this->sch_setting_detail;
        //echo "<pre>";print_r($data['sch_setting']);echo "<pre>";die;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['class_id']   = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');

            $data['onlineexam_id'] = $this->input->post('onlineexam_id');

            $resultlist = $this->onlineexam_model->searchOnlineExamStudents($data['class_id'], $data['section_id'], $data['onlineexam_id']);

            $data['resultlist'] = $resultlist;
        }
 
        $this->load->view('layout/header', $data);
        $this->load->view('admin/onlineexam/assign', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addstudent()
    {
        $this->form_validation->set_rules('onlineexam_id', $this->lang->line('exam')." ".$this->lang->line('id'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'onlineexam_id' => form_error('onlineexam_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $array_insert  = array();
            $array_delete  = array();
            $class_id      = $this->input->post('post_class_id');
            $section_id    = $this->input->post('post_section_id');
            $onlineexam_id = $this->input->post('onlineexam_id');
            $resultlist    = $this->onlineexam_model->searchOnlineExamStudents($class_id, $section_id, $onlineexam_id);
            $all_students  = array();
            if (!empty($resultlist)) {

                foreach ($resultlist as $each_student_key => $each_student_value) {
                    if ($each_student_value['onlineexam_student_session_id'] != 0) {
                        $all_students[] = $each_student_value['onlineexam_student_session_id'];
                    }

                }
            }

            $students_id = $this->input->post('students_id');
            $students    = array();
            if (!isset($students_id)) {
                $students_id = array();
            }
            if (!empty($all_students)) {
                $array_delete = array_diff($all_students, $students_id);

            }
            if (!empty($students_id)) {
                $student_session_array = array();
                foreach ($students_id as $student_key => $student_value) {
                    $student_session_array[] = $student_value;
                }

                $student_array = array_diff($student_session_array, $all_students);
                if (!empty($student_array)) {
                    foreach ($student_array as $insert_key => $insert_value) {
                        $array_insert[] = array(
                            'onlineexam_id'      => $onlineexam_id,
                            'student_session_id' => $insert_value,
                        );
                    }
                }
            }

            $this->onlineexam_model->addStudents($array_insert, $array_delete, $onlineexam_id);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function getOnlineExamByID()
    {
        $id = $this->input->post('recordid');

        $question_result = $this->onlineexam_model->get($id);

        echo json_encode(array('status' => 1, 'result' => $question_result));
    }

    public function searchQuestionByExamID()
    {
        $data           = array();
        $pag_content    = '';
        $pag_navigation = '';
        $page           = $this->input->post('page');
        $exam_id        = $this->input->post('exam_id');
        if (isset($page)) {
            $max      = 100;
            $cur_page = $page;
            $page -= 1;
            $per_page     = $max ? $max : 40;
            $previous_btn = true;
            $next_btn     = true;
            $first_btn    = true;
            $last_btn     = true;
            $start        = $page * $per_page;
            $where_search = array();

            /* Check if there is a string inputted on the search box */
            if (!empty($_POST['search'])) {
                $search = $this->input->post('search');

                $and_array = array('subjects.id' => $search);

                $where_search['and_array'] = $and_array;
            }
            $data['questionList'] = $this->onlineexamquestion_model->getByExamID($exam_id, $per_page, $start, $where_search);

            $count = $this->onlineexamquestion_model->getCountByExamID($exam_id, $where_search);

            /* Check if our query returns anything. */
            if ($count) {
                $pag_content = $this->load->view('admin/onlineexam/_searchQuestionByExamID', $data, true);
                /* If the query returns nothing, we throw an error message */
            }
 
            $no_of_paginations = ceil($count / $per_page);

            if ($cur_page >= 7) {
                $start_loop = $cur_page - 3;
                if ($no_of_paginations > $cur_page + 3) {
                    $end_loop = $cur_page + 3;
                } else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                    $start_loop = $no_of_paginations - 6;
                    $end_loop   = $no_of_paginations;
                } else {
                    $end_loop = $no_of_paginations;
                }
            } else {
                $start_loop = 1;
                if ($no_of_paginations > 7) {
                    $end_loop = 7;
                } else {
                    $end_loop = $no_of_paginations;
                }

            }

            $pag_navigation .= "<ul class='pagination'>";

            if ($first_btn && $cur_page > 1) {
                $pag_navigation .= "<li p='1' class='activee'><a href='#'>".$this->lang->line('first')."</a></li>";
            } else if ($first_btn) {

                $pag_navigation .= "<li p='1' class='disabled'><a href='#'>".$this->lang->line('first')."</a></li>";
            }

            if ($previous_btn && $cur_page > 1) {
                $pre = $cur_page - 1;
                $pag_navigation .= "<li p='$pre' class='activee'><a href='#'>".$this->lang->line('previous')."</a></li>";
            } else if ($previous_btn) {

                $pag_navigation .= "<li  class='disabled'><a href='#'>".$this->lang->line('previous')."</a></li>";
            }
            for ($i = $start_loop; $i <= $end_loop; $i++) {

                if ($cur_page == $i) {

                    $pag_navigation .= "<li p='$i' class='active'><a href='#'>{$i}</a></li>";
                } else {

                    $pag_navigation .= "<li p='$i'  class='activee'><a href='#'>{$i}</a></li>";
                }

            }

            if ($next_btn && $cur_page < $no_of_paginations) {
                $nex = $cur_page + 1;

                $pag_navigation .= "<li p='$nex' class='activee'><a href='#'>".$this->lang->line('next')."</a></li>";
            } else if ($next_btn) {
                $pag_navigation .= "<li class='disabled'><a href='#'>".$this->lang->line('next')."</a></li>";
            }

            if ($last_btn && $cur_page < $no_of_paginations) {
                $pag_navigation .= "<li p='$no_of_paginations'  class='activee'><a href='#'>".$this->lang->line('last')."</a></li>";
            } else if ($last_btn) {
                $pag_navigation .= "<li p='$no_of_paginations' class='disabled'><a href='#'>".$this->lang->line('last')."</a></li>";
            }

            $pag_navigation = $pag_navigation . "</ul>";
        }

        $response = array(
            'content'    => $pag_content,
            'navigation' => $pag_navigation,
        );

        echo json_encode($response);

       
    }

    public function add()
    {
	if ($_POST) {
		$this->_prepare_question_validation();	
            if ($this->form_validation->run() === TRUE) {							
                $data = $this->_get_posted_question_data();							
                $insert_id = $this->question_model->insert('questions', $data);
                if ($insert_id) {                                     
                    success($this->lang->line('insert_success'));                
                    redirect('question/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('question/add');
                }
            } else {				
                $this->data['post'] = $_POST;
            }
		
		
       
	}
	 $this->data['questions'] = $this->question_model->get_question_list($school_id);  
        
        $condition = array();
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');  
			$this->data['subjects'] = $this->subject->get_list('subject', $condition, '','', '', 'id', 'ASC');			
        }   
        $questionOpt          = getQuesOption();
        $this->data['questionOpt']  = $questionOpt;
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;		    
        $subject_result       = $this->subject->get();
        $this->data['subjectlist']  = $subject_result;      
		$this->data['list'] = TRUE;               
		$this->layout->title($this->lang->line('Question') . ' | ' . SMS);
        $this->layout->view('question/index', $this->data);
        //echo json_encode($array);
    }
	public function edit($id = null) {                     
		
		if ($_POST) {			
            $this->_prepare_question_validation();			
            if ($this->form_validation->run() === TRUE) {				
                $data = $this->_get_posted_question_data();						
                $updated = $this->question_model->update('questions', $data, array('id' => $this->input->post('id')));

                if ($updated) {                                                           
                    success($this->lang->line('update_success'));
                    redirect('question/index/'.$data['school_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('question/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['question'] = $this->question_model->get_single('questions', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['question'] = $this->question_model->get_single('questions', array('id' => $id));
			$subject=$this->question_model->get_single('subjects', array('id' => $this->data['question']->subject_id));
			$this->data['question']->class_id=$subject->class_id;
            if (!$this->data['question']) {
                 redirect('question/index');
            }
        }

		$this->data['questions'] = $this->question_model->get_question_list($school_id);  
        $condition = array();
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');  
			$this->data['subjects'] = $this->subject->get_list('subjects', $condition, '','', '', 'id', 'ASC');			
        }   
        $questionOpt          = getQuesOption();
        $this->data['questionOpt']  = $questionOpt;
        $this->data['filter_school_id'] = $this->data['question']->school_id;		   
		$this->data['schools'] = $this->schools;		    
        $subject_result       = $this->subject->get();
        $this->data['subjectlist']  = $subject_result;  
		$this->data['school_id'] = $this->data['question']->school_id;						             
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('question'). ' | ' . SMS);
        $this->layout->view('question/index', $this->data);
        
	}
	public function datetostrtotime($date)
    {
        //$format = $this->getSchoolDateFormat();
		$format = 'd-m-Y';
        if ($format == 'd-m-Y') {
            list($day, $month, $year) = explode('-', $date);
        }

        if ($format == 'd/m/Y') {
            list($day, $month, $year) = explode('/', $date);
        }

        if ($format == 'd-M-Y') {
            list($day, $month, $year) = explode('-', $date);
        }

        if ($format == 'd.m.Y') {
            list($day, $month, $year) = explode('.', $date);
        }

        if ($format == 'm-d-Y') {
            list($month, $day, $year) = explode('-', $date);
        }

        if ($format == 'm/d/Y') {
            list($month, $day, $year) = explode('/', $date);
        }

        if ($format == 'm.d.Y') {
            list($month, $day, $year) = explode('.', $date);
        }

        if ($format == 'Y/m/d') {
            list($year, $month, $day) = explode('/', $date);
        }

        $date = $year . "-" . $month . "-" . $day;

        return strtotime($date);
    }

    public function getRecord($id)
    {

        $result            = $this->onlineexam_model->get_result($id);
        $result['options'] = $this->onlineexam_model->get_option($id);
        $result['ans']     = $this->onlineexam_model->get_answer($id);
        echo json_encode($result);
    }
	 public function delete($id = null) {
               
        
        $question = $this->question_model->get_single('questions', array('id' => $id));
        
        if ($this->question_model->delete('questions', array('id' => $id))) {

            //create_log('Has been deleted a class : '. $class->name);            
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('question/index/'.$question->school_id);
    }   
    public function questionAdd()
    {

        $this->form_validation->set_rules('question_id', $this->lang->line('exam'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('onlineexam_id', $this->lang->line('attempt'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'question_id'   => form_error('question_id'),
                'onlineexam_id' => form_error('onlineexam_id'),

            );

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $insert_data = array(
                'question_id'   => $this->input->post('question_id'),
                'onlineexam_id' => $this->input->post('onlineexam_id'),
            );
            $this->onlineexam_model->insertExamQuestion($insert_data);
            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function report()
    {

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/online_examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/online_examinations/online_exam_report');

        $examList         = $this->onlineexam_model->get();
        $data['examList'] = $examList;
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');
         $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/onlineexam/report', $data);
            $this->load->view('layout/footer', $data);
            
        } else {

            if ($this->input->server('REQUEST_METHOD') == "POST") {

                $exam_id    = $this->input->post('exam_id');
                $class_id   = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $results    = $this->onlineexamresult_model->getStudentByExam($exam_id, $class_id, $section_id);
                $data['results'] = $results;
                

            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/onlineexam/report', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function getstudentresult()
    {
        $onlineexam_student_id = $this->input->post('recordid');
        $examid = $this->input->post('examid');
        $exam         = $this->onlineexam_model->get($examid);
        $data['exam'] = $exam;
        $data['question_result'] = $this->onlineexamresult_model->getResultByStudent($onlineexam_student_id,$examid);
      
          $query='';
         $question_result = $this->load->view('admin/onlineexam/_getstudentresult', $data, true);
        
        echo json_encode(array('status' => 1, 'result' => $question_result,'query'=>$query));
    }
	private function _get_posted_question_data() {

        $items = array();
        $items[] = 'school_id';
		$items[] = 'subject_id';
        $items[] = 'question';
        $items[] = 'opt_a';
		$items[] = 'opt_b';
		$items[] = 'opt_c';
		$items[] = 'opt_d';
		$items[] = 'opt_e';
        $items[] = 'correct';               	              
        $data = elements($items, $_POST);            
        if ($this->input->post('id')) {
            $data['updated_at'] = date('Y-m-d H:i:s');            
        } else {           
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
                       
        }

        return $data;
    }

	private function _prepare_question_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');   
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required');   
        $this->form_validation->set_rules('question', $this->lang->line('question'), 'trim|required');            
		$this->form_validation->set_rules('opt_a', $this->lang->line('option')." A", 'trim|required');            
		$this->form_validation->set_rules('opt_b', $this->lang->line('option')." B", 'trim|required');            
		$this->form_validation->set_rules('opt_c', $this->lang->line('option')." C", 'trim|required'); 
		$this->form_validation->set_rules('opt_d', $this->lang->line('option')." D", 'trim|required');            		
		$this->form_validation->set_rules('correct', $this->lang->line('answer'), 'trim|required');   		
    }


}
