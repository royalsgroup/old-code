<?php
class Onlineexam_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
        //$this->current_session = $this->setting_model->getCurrentSession();
    }
    public function add($data)
    {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('onlineexam', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  online exam id ".$data['id'];
			$action       = "Update";
			$record_id    = $id = $data['id'];
			$this->log($message, $record_id, $action);
			
        } else {
            $this->db->insert('onlineexam', $data);
            $id=$this->db->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On  online exam id ".$id;
			$action       = "Insert";
			$record_id    = $id;
			$this->log($message, $record_id, $action);
			
			// return $id;
        }
		//echo $this->db->last_query();die;
			//======================Code End==============================

			$this->db->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;

			} else {
				return $id;
			}
    }

    public function get($id = null, $publish = null)
    {
        $this->db->select('onlineexam.*')->from('onlineexam');
        if ($id != null) {
            $this->db->where('onlineexam.id', $id);
           // $this->db->where('onlineexam.session_id', $this->current_session);
        } else {
            $this->db->order_by('onlineexam.id');
            //$this->db->where('onlineexam.session_id', $this->current_session);
        }
        if ($publish != null) {
            $this->db->where('is_active', ($publish == "publish") ? 1 : 0);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }
	public function get_exam_list($school_id = null){
        
        $this->db->select('O.*, S.school_name');
        $this->db->from('onlineexam AS O');       
        $this->db->join('schools AS S', 'S.id = O.school_id', 'left');
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('O.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('O.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('O.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        return $this->db->get()->result();
        
    }

    public function insertExamQuestion($insert_data)
    {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('question_id', $insert_data['question_id']);
        $this->db->where('onlineexam_id', $insert_data['onlineexam_id']);
        $q = $this->db->get('onlineexam_questions');

        if ($q->num_rows() > 0) {
            $result = $q->row();
            $this->db->where('id', $result->id);
            $this->db->delete('onlineexam_questions');
			$message      = DELETE_RECORD_CONSTANT." On  onlineexam questions id ".$result->id;
			$action       = "Delete";
			$record_id    = $result->id;
			//$this->log($message, $record_id, $action);
			
        } else {
            $this->db->insert('onlineexam_questions', $insert_data);
			$id=$this->db->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On  onlineexam questions id ".$id;
			$action       = "Insert";
			$record_id    = $id;
			//$this->log($message, $record_id, $action);
			
        }
		//echo $this->db->last_query();die;
			//======================Code End==============================
			$this->db->trans_complete(); # Completing transaction
			/*Optional*/
			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;
			} else {
				//return $return_value;
			}
    }

    public function remove($id)
    {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('onlineexam');
		$message      = DELETE_RECORD_CONSTANT." On  online exam id ".$id;
        $action       = "Delete";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
		//======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
        //return $return_value;
        }
    }

    public function searchOnlineExamStudents($class_id, $section_id, $onlineexam_id)
    {

        $this->db->select('classes.id AS `class_id`,students.*,classes.name as class_name,sections.id AS `section_id`,sections.name as section_name, student_types.type as student_type,IFNULL(onlineexam_students.student_id, 0) as onlineexam_student_id')->from('students');
        //$this->db->join('student_session', 'student_session.student_id = students.id');
		$this->db->join('enrollments', 'enrollments.student_id = students.id', 'left');
        $this->db->join('classes', 'enrollments.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = enrollments.section_id', 'left');
        $this->db->join('student_types', 'students.type_id = student_types.id', 'left');
		 $this->db->join('onlineexam_students', 'onlineexam_students.student_id = students.id and onlineexam_students.onlineexam_id=' . $onlineexam_id, 'left');
        //$this->db->join('onlineexam_students', 'onlineexam_students.student_session_id = student_session.id and onlineexam_students.onlineexam_id=' . $onlineexam_id, 'left');
       // $this->db->where('student_session.session_id', $this->current_session);

        $this->db->where('enrollments.class_id', $class_id);

        $this->db->where('enrollments.section_id', $section_id);

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();

    }
  
    public function get_student_list($class_id , $section_id , $school_id ,$academic_year_id){
            
        /*if(!$class_id){
            return;
        }*/
      
        $this->db->select('S.*, SC.school_name, E.roll_no, E.class_id, U.username, U.role_id,  C.name AS class_name,SE.name as section_name,E.academic_year_id');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('schools AS SC', 'SC.id = S.school_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id and AY.is_running=1', 'left');
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id); 
        }
        if($class_id){
            $this->db->where('E.class_id', $class_id);
        }
        if($class_id){
            $this->db->where('E.section_id', $section_id);
        }
        $this->db->where('S.school_id', $school_id); 
        $this->db->where('S.status_type', 'regular');
        $this->db->where('SC.status', 1);
        $this->db->order_by('E.roll_no', 'ASC');
      
        $result=$this->db->get()->result();	
        
		return $result;
        
    }
    public function addStudents($data_insert, $data_delete, $onlineexam_id)
    {

        $this->db->trans_begin();

        if (!empty($data_insert)) {

            $this->db->insert_batch('onlineexam_students', $data_insert);
        }
        if (!empty($data_delete)) {

            $this->db->where('onlineexam_id', $onlineexam_id);
            $this->db->where_in('student_id', $data_delete);
            $this->db->delete('onlineexam_students');
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }


  public function getStudentAttemts($onlineexam_student_id)
    {
    
        $this->db->where('onlineexam_student_id', $onlineexam_student_id);
        $total_rows = $this->db->count_all_results('onlineexam_attempts');       
        return $total_rows;
    }
  public function addStudentAttemts($data)
    {
    $this->db->insert('onlineexam_attempts', $data);
            return $this->db->insert_id();
    }

    public function examstudentsID($student_session_id, $onlineexam_id)
    {
        $this->db->from('onlineexam_students');
        $this->db->where('student_id', $student_session_id);
        $this->db->where('onlineexam_id', $onlineexam_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getStudentexam($student_id)
    {
        $query = "SELECT onlineexam.*,onlineexam_students.id as `onlineexam_student_id`,(select count(*) from onlineexam_attempts WHERE onlineexam_attempts.onlineexam_student_id = onlineexam_students.id) as counter FROM `onlineexam` INNER JOIN onlineexam_students on onlineexam_students.onlineexam_id=onlineexam.id WHERE onlineexam_students.student_id=" . $this->db->escape($student_id) . " and onlineexam.is_active=1 order by onlineexam.id asc";

        $query = $this->db->query($query);
        return $query->result();

    }

    public function getExamQuestions($id = null)
    {
        $this->db->select('onlineexam_questions.*,questions.subject_id,questions.question,questions.opt_a,questions.opt_b,questions.opt_c,questions.opt_d,questions.opt_e,questions.correct')->from('onlineexam_questions');
        $this->db->join('questions', 'questions.id = onlineexam_questions.question_id');
        $this->db->where('onlineexam_questions.onlineexam_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function onlineexamReport($condition){

        // $this->db->select('*')->from('onlineexam')->where($condition);
        // $query = $this->db->get();
        // return $query->result();
         $query = "SELECT onlineexam.*,(select count(*) from onlineexam_students WHERE onlineexam_students.onlineexam_id = onlineexam.id) as assign,(select count(*) from onlineexam_questions where onlineexam_questions.onlineexam_id=onlineexam.id) as questions FROM `onlineexam`  where ".$condition." order by onlineexam.id asc";

        $query = $this->db->query($query);
        return $query->result();

    }


    public function onlineexamatteptreport($condition){
       
        $query = "SELECT student_session.id,students.admission_no,CONCAT_WS(' ',firstname,lastname) as name,GROUP_CONCAT(onlineexam.id,'@',onlineexam.exam,'@',onlineexam.attempt,'@',onlineexam.exam_from,'@',onlineexam.exam_to,'@',onlineexam.duration,'@',onlineexam.passing_percentage,'@',onlineexam.is_active,'@',onlineexam.publish_result) as exams,GROUP_CONCAT(onlineexam_students.onlineexam_id) as attempt,`classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no` FROM `student_session` INNER JOIN onlineexam_students on onlineexam_students.student_session_id=student_session.id INNER JOIN students on students.id=student_session.student_id JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN onlineexam on onlineexam_students.onlineexam_id=onlineexam.id WHERE  student_session.session_id=" . $this->db->escape($this->current_session). " ".$condition." group by students.id";

        $query = $this->db->query($query);

        return $query->result_array();
    } 

    

}
