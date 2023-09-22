<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Debugmode extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Leave/Application_Model', 'application', true);        
        $this->load->model('Accounttransactions_Model', 'transactions', true);


    }
    public function copy_schedules($to_school_id, $from_school_id =71, $auto = 0)
    {
        die("off");

        if($from_school_id ==0) $from_school_id  = 71;
        $this->load->model('Exam/Schedule_Model', 'schedule', true);
        $from_school =  $this->schedule->get_school_by_id($from_school_id);
        $schedules =$this->schedule->get_list('exam_schedules', array('school_id'=>$from_school_id, "academic_year_id"=>$from_school->academic_year_id), '','', '', 'id', 'ASC');	
        $aInsertDatas = array();
        $school = $this->schedule->get_school_by_id($to_school_id);
        if(!$school->academic_year_id){
            error("Error auto creating schedules, Please contact admin");
            redirect('dashboard/index');   
        }
        $iCount =0;
        foreach ($schedules as $schdule)
        {

            $schdule =  (array)$schdule;
            unset($schdule['id']);
            $schdule['school_id'] = $to_school_id;
            $exam = $this->schedule->get_single('exams',array('id'=>$schdule['exam_id']));
            if(!empty($exam))
            {
                $to_exam = $this->schedule->get_single('exams',array('title'=>$exam->title, "school_id"=>$to_school_id, "academic_year_id"=> $school->academic_year_id));

                if(!empty($to_exam))
                {
                    $schdule['exam_id'] = $to_exam->id;
                }
                else
                {
                    $to_exam = (array)$exam;
                    unset( $to_exam['id']);
                    $to_exam['academic_year_id'] = $school->academic_year_id;
                    $to_exam['status'] = 1;
                    $to_exam['created_at'] = date('Y-m-d H:i:s');
                    $to_exam['created_by'] = logged_in_user_id();
                    $to_exam['school_id'] = $to_school_id;

                    $schdule['exam_id'] = $this->schedule->insert('exams', $to_exam);
                }
            }
         
            $class = $this->schedule->get_single('classes',array('id'=>$schdule['class_id']));
            if(!empty($class))
            {
                $to_class = $this->schedule->get_single('classes',array('school_id'=>$to_school_id, 'name'=>$class->name, "numeric_name"=>$class->numeric_name));
                if(!empty($to_class))
                {
                    $schdule['class_id'] = $to_class->id;
                }
                else
                {
                    $to_class = (array)$class;
                    $to_class['status'] = 1;
                    $to_class['school_id'] = $to_school_id;
                    unset( $to_class['id']);

                    $to_class['created_at'] = date('Y-m-d H:i:s');
                    $to_class['created_by'] = logged_in_user_id();
                    $schdule['class_id'] = $this->schedule->insert('classes', $to_class);
                }
            }

            if ($schdule['section_id'])
            {
                $section = $this->schedule->get_single('sections',array('id'=>$schdule['section_id']));
                if(!empty($section))
                {
                    $to_section = $this->schedule->get_single('sections',array('school_id'=>$to_school_id, 'name'=>$section->name,  "class_id"=> $schdule['class_id'] ));
                    
                    if(!empty($to_section))
                    {
                        $schdule['section_id'] = $to_section->id;
                    }
                    else
                    {
                        $to_section = (array)$section;
                        unset( $to_section['id']);

                        $to_section['status'] = 1;
                        $to_section['school_id'] = $to_school_id;
                        $to_section['created_at'] = date('Y-m-d H:i:s');
                        $to_section['created_by'] = logged_in_user_id();         
                        $schdule['section_id'] = $this->schedule->insert('sections', $to_section);
                    }
                }
            }
            // echo '<pre>'; var_/dump($schdule['section_id'] ); die(); 

            $subject = $this->schedule->get_single('subjects',array('id'=>$schdule['subject_id']));
            if(!empty($subject))
            {
                $to_subject = $this->schedule->get_single('subjects',array('school_id'=>$to_school_id, 'name'=>$subject->name, 'class_id'=>$schdule['class_id']));
                if(!empty($to_subject))
                {
                    $schdule['subject_id'] = $to_subject->id;
                }
                else
                {
                    $to_subject = (array)$subject;
                    unset( $to_subject['id']);

                    $to_subject['status'] = 1;
                    $to_subject['school_id'] = $to_school_id;
                    $to_subject['teacher_id'] = 0;
                    $to_subject['class_id'] = $schdule['class_id'];

                    $to_subject['created_at'] = date('Y-m-d H:i:s');
                    $to_subject['created_by'] = logged_in_user_id();
                    $schdule['subject_id'] = $this->schedule->insert('subjects', $to_subject);
                }
            }
            else
            {
                $schdule['subject_id'] = 0;
            }
            $schdule['academic_year_id'] = $school->academic_year_id;
            $schdule['custom_id'] = $this->schedule->generate_custom_id($to_school_id);
            $schdule['status'] = 1;
            $schdule['created_at'] = date('Y-m-d H:i:s');
            $schdule['copy_data'] = 1;
            $schdule['created_by'] = logged_in_user_id();
            $schedule = $this->schedule->get_single('exam_schedules',array('school_id'=>$to_school_id,'exam_id'=>$schdule['exam_id'] ,  'class_id'=>$schdule['class_id'],  'section_id'=>$schdule['section_id'],'subject_id'=>$schdule['subject_id'], 'start_time'=>$schdule['start_time'], 'end_time'=>$schdule['end_time']));

            if(empty($schedule))
            {
               $inserted_id = $this->schedule->insert("exam_schedules",$schdule);
               if($inserted_id )
                    $iCount++;
            }
        }
        $data['copy_schedule']= 0;
        $this->schedule->update('schools', $data, array('id' => $to_school_id));
        if ($auto ==1)
        {
            redirect("debugmode/update_schools_data");
        }
        else
        {
            error("Auto created schedules");
            redirect('dashboard/index');
        }

    }
    public function copy_grades($to_school_id, $from_school_id =71,  $auto =0) {
        die("off");

        if($from_school_id ==0) $from_school_id  = 71;
        $this->load->model('Exam/Schedule_Model', 'schedule', true);
        $from_school =  $this->schedule->get_school_by_id($from_school_id);
        $grades =$this->schedule->get_list('grades', array('school_id'=>$from_school_id), '','', '', 'id', 'ASC');
        $aInsertGrades = array();
        foreach ($grades as $grade)
        {
            $grade =  (array)$grade;
            $grade_check = $this->schedule->get_single('grades',array('school_id'=>$to_school_id,'name'=>$grade['name']));
            if (empty($grade_check ))
            {
                $grade['status'] = 1;
                unset($grade['id']);

                $grade['school_id'] = $to_school_id;
                $grade['created_at'] = date('Y-m-d H:i:s');
                $grade['created_by'] = logged_in_user_id();
                $this->schedule->insert("grades",$grade);
                // echo $this->db->last_query();
            }
        }
        redirect("debugmode/copy_schedules/$to_school_id/0/$auto");

    }
    public function update_schools_data() {
        die("off");

        $school = $this->application->get_single('schools',array("copy_schedule"=>2));
        if(empty( $school ))
        {
            echo "All schools completed";
            die();
        }
        else
        {
            redirect("debugmode/copy_grades/".$school->id."/0/1");
        }
    }
    public function disable_copy_schedule() {
        die("off");

        $this->application->update('schools',array("copy_schedule"=>0));
    }
    public function enable_copy_schedule() {
        die("off");

        $this->application->update('schools',array("copy_schedule"=>1), array('1'=>1));
    }
    public function create_new_ledgers($school_id)
    {
        die("off");

        error_on();
        $data = array();
        $data['school_id'] = $school_id;
        $data['dr_cr'] = "CR";
        $data['category'] = "School Samiti";

        $account_group = $this->application->get_single('account_groups',array('school_id'=>0,'name'=>'Direct Income', 'category'=>"School Samiti"));
        
        if(empty($account_group))
        {
            error('Auto creation of new account ledgers is faild, becouse couldnt find account group, Please contact admin');
            redirect('accountledgers/index'); 
        }
        else
        {
            $data['account_group_id'] = $account_group->id;
        }
        $school = $this->application->get_school_by_id($school_id);            

        $data['name'] = "Hostel Fee 23-24";
        $data1 = $data;

   
        $details_array = array();
        $data1['created'] = date('Y-m-d ;H:i:s');
        $data1['modified'] = date('Y-m-d H:i:s');
        $ledger = $this->application->get_single('account_ledgers', $data);
        if(empty( $ledger ))
        {
            $insert_id = $this->application->insert("account_ledgers",$data1);
            $in_arr['ledger_id']=$insert_id;
            $in_arr['financial_year_id']=$school->financial_year_id;
            $in_arr['opening_balance']=0;
            $in_arr['opening_cr_dr']="CR";
            
            $in_arr['budget']=0;
            $in_arr['budget_cr_dr']="CR";
            $in_id = $this->application->insert('account_ledger_details', $in_arr);

        }
        else
        {
            $details_array['ledger_id']=$ledger->id;
            $details_array['financial_year_id']=$school->financial_year_id;
            $ledger_details = $this->application->get_single('account_ledger_details', $details_array, null, 1);
            if(empty( $ledger_details ))
            {
                $insert_data = array();
                $insert_data['ledger_id']=$ledger->id;
                $insert_data['financial_year_id']=$school->financial_year_id;
                $insert_data['opening_balance']=0;
                $insert_data['opening_cr_dr']="CR";
                $insert_data['budget']=0;
                $insert_data['budget_cr_dr']="CR";
                $this->application->insert('account_ledger_details', $insert_data);
            }
        }
        $data['name'] = "Transportation Fee 23-24";
        $ledger = $this->application->get_single('account_ledgers', $data);
        if(empty( $ledger ))
        {
            $data1['name'] = $data['name'];

            $insert_id = $this->application->insert("account_ledgers",$data1);
            $in_arr['ledger_id']=$insert_id;
            $in_arr['financial_year_id']=$school->financial_year_id;
            $in_arr['opening_balance']=0;
            $in_arr['opening_cr_dr']="CR";
            
            $in_arr['budget']=0;
            $in_arr['budget_cr_dr']="CR";
            $in_id = $this->application->insert('account_ledger_details', $in_arr);
        }
        else
        {
            $details_array['ledger_id']=$ledger->id;
            $details_array['financial_year_id']=$school->financial_year_id;
            $ledger_details = $this->application->get_single('account_ledger_details', $details_array);
            if(empty( $ledger_details ))
            {
                $insert_data = array();
                $insert_data['ledger_id']=$ledger->id;
                $insert_data['financial_year_id']=$school->financial_year_id;
                $insert_data['opening_balance']=0;
                $insert_data['opening_cr_dr']="CR";
                $insert_data['budget']=0;
                $insert_data['budget_cr_dr']="CR";
                $this->application->insert('account_ledger_details', $insert_data);
            }
        }
        $data['name'] = "Tuition Fee 23-24";
        $ledger = $this->application->get_single('account_ledgers', $data);
        if(empty( $ledger ))
        {
            $data1['name'] = $data['name'];

            $insert_id = $this->application->insert("account_ledgers",$data1);
            $in_arr['ledger_id']=$insert_id;
            $in_arr['financial_year_id']=$school->financial_year_id;
            $in_arr['opening_balance']=0;
            $in_arr['opening_cr_dr']="CR";
            
            $in_arr['budget']=0;
            $in_arr['budget_cr_dr']="CR";
            $in_id = $this->application->insert('account_ledger_details', $in_arr);
        }
        else
        {
            $details_array['ledger_id']=$ledger->id;
            $details_array['financial_year_id']=$school->financial_year_id;
            $ledger_details = $this->application->get_single('account_ledger_details', $details_array);
            if(empty( $ledger_details ))
            {
                $insert_data = array();
                $insert_data['ledger_id']=$ledger->id;
                $insert_data['financial_year_id']=$school->financial_year_id;
                $insert_data['opening_balance']=0;
                $insert_data['opening_cr_dr']="CR";
                $insert_data['budget']=0;
                $insert_data['budget_cr_dr']="CR";
                $this->application->insert('account_ledger_details', $insert_data);
            }
        }
        $data['name'] = "Other Fee 23-24";
        $ledger = $this->application->get_single('account_ledgers', $data);
        if(empty( $ledger ))
        {
            $data1['name'] = $data['name'];

            $insert_id = $this->application->insert("account_ledgers",$data1);
            $in_arr['ledger_id']=$insert_id;
            $in_arr['financial_year_id']=$school->financial_year_id;
            $in_arr['opening_balance']=0;
            $in_arr['opening_cr_dr']="CR";
            
            $in_arr['budget']=0;
            $in_arr['budget_cr_dr']="CR";
            $in_id = $this->application->insert('account_ledger_details', $in_arr);
        }
        else
        {
            $details_array['ledger_id']=$ledger->id;
            $details_array['financial_year_id']=$school->financial_year_id;
            $ledger_details = $this->application->get_single('account_ledger_details', $details_array);
            if(empty( $ledger_details ))
            {
                $insert_data = array();
                $insert_data['ledger_id']=$ledger->id;
                $insert_data['financial_year_id']=$school->financial_year_id;
                $insert_data['opening_balance']=0;
                $insert_data['opening_cr_dr']="CR";
                $insert_data['budget']=0;
                $insert_data['budget_cr_dr']="CR";
                $this->application->insert('account_ledger_details', $insert_data);
            }
        }
        $data_school = array('create_academic_year'=> 2);
        $this->application->update('schools', $data_school, array('id' => $school_id));
        redirect("dashboard/index");
    }
    
    public function enable_copy_schedule_school($school_id) {
        die("off");

        $this->application->update('schools',array("copy_schedule"=>1), array('id'=>$school_id ));
    }
    public function enable_ayc_school($school_id) {
        die("off");

        $this->application->update('schools',array("create_academic_year"=>1), array('id'=>$school_id ));
        $school = $this->application->get_single('schools', array('status'=>1, 'id'=>$school_id));
        redirect("dashboard/index");
    }
    public function enable_ayc_all() {
        die("off");

        $this->application->update('schools',array("create_academic_year"=>1), array('1'=>1 ));
    }
    public function set_ayc_school($school_id) {
        die("off");

        $this->application->update('schools',array("create_academic_year"=>2), array('id'=>$school_id ));
    }
    public function check_ayc($school_id) {
    }
    public function enable_developement_mode()
    {
        $_SESSION['development_mode'] =  true;        
    }
    public function enable_debugmode()
    {
        $_SESSION['debugmode'] =  true;        
    }
    
    function has_permission_test($action, $module_slug = null, $operation_slug = null) {

        $role_id = $this->session->userdata('role_id');

        if ($module_slug == '') {
            $module_slug = $operation_slug;
        }

        $module_slug = 'my_' . $module_slug;

        $data = $this->config->item($operation_slug, $module_slug);
        var_dump( $role_id);

        $result = @$data[$role_id];
        debug_a( $result);

        if (!empty($result)) {
            $array = explode('|', $result);
            return $array[$action];
        } else {
            return FALSE;
        }
    }
    public function test1()
    {

        die("off");

        $school_id  = $_GET['shcool_id'] ?? 576;
        $admin_users = $this->application->get_admins($school_id);
        $school_id  = $_GET['shcool_id2'] ?? 202;

        $admin_users2 = $this->application->get_admins($school_id);
        // echo $this->db->last_query();
        echo"<br> <pre>";
        var_dump($admin_users);
        echo"<br>";
        var_dump($admin_users2);
        echo"<br> ";
        var_dump(array_merge($admin_users,$admin_users2));


        die();
    }

    public function index($school_id = null)
    {	
        die("off");

        $common_tables = array("account_base","account_ledger_details","account_transactions","account_transaction_details","account_types","blocks","districts","employee_employment_types");
        $tables=$this->db->query("SELECT t.TABLE_NAME AS table_name FROM INFORMATION_SCHEMA.TABLES AS t WHERE t.TABLE_SCHEMA = 'admin_vbr' ")->result_array();    
        foreach($tables as $key => $val) {
            $table_name = $val['table_name'];
           
            if(in_array($table_name,$common_tables))
            {
                continue;
            }

            if ($this->db->field_exists('school_id', $table_name ))
            {
               // echo $table_name."<br>";
            }
            else
            {
                if ($this->db->field_exists('user_id', $table_name ))
                {
                   // echo $table_name."-user<br>";
                }
                else
                {
                    echo $table_name."<br>";
                }
            }
        }
       // die("test");
    }

    public function checkebalance($school_id = 1545)
    {
        die("off");


        $this->load->model('Accountledgers_Model', 'accountledgers', true);			
		$this->load->model('Accountgroups_Model', 'accountgroups', true);	
        $start_date = strtotime($this->input->post('session_start'));
        $start_date= date("Y-m-d",strtotime('-1 day',$start_date));
        $assets=array();
        $final_assets=0;			  
         $agroup=$this->accountgroups->get_list_new('account_groups', array('school_id'=>$school_id), '','', '', 'id', 'ASC');	
         $account_ledger_details = array();
        
         $school = $this->accountledgers->get_school_by_id($school_id);

        foreach($agroup as $ag){				
           // get ledgers
          
           $group_total=0;				
           $group_id=$ag->id;
           $ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id,$school->financial_year_id,$group_id);
           $j=0;
           //print_r($ledgers); exit;
           foreach($ledgers as $l){
               // get current balance
             
                   $cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
		
             			
               $cb=$cbalance;					
               $in_arr=array();
               $in_arr['ledger_id']=$l->id;
            //    $in_arr['financial_year_id']=$finacial_year_id;
               $in_arr['opening_balance']=$cbalance;
               $in_arr['opening_cr_dr']=$l->dr_cr;
               $account_ledger_details[] = $in_arr;
           }
        }
        debug_a( $account_ledger_details);
    }
    public function uodate_financial_year_id($school_id = 1545)
    {
        die("off");

        $this->load->model('Accountledgers_Model', 'accountledgers', true);			
        $school = $this->accountledgers->get_school_by_id($school_id);
        $invalid_current = [];
        $invalid_previous = [];
        $financial_years=$this->accountledgers->get_list('financial_years', array(), '','', '', 'id', 'ASC');	
        $school_financial_years = [];	


        foreach($financial_years as $financial_year)
        {
           
            $school_financial_years[$financial_year->school_id] = $school_financial_years[$financial_year->school_id]  ?? [];
            $invalid_current[$financial_year->school_id] =  $invalid_current[$financial_year->school_id] ?? [];
            if(strpos($financial_year->session_year,"->"))	
            {
                $arr=explode("->",$financial_year->session_year);
                $f_start=date("Y",strtotime($arr[0]));		
            }
            else
            {
                $arr=explode("-",$financial_year->session_year);
                $date_exploded = explode(" ",$arr[0]);
                if(count($date_exploded)>2)
                {
                    $f_start=date("Y",strtotime($arr[0]));		
                }
                else
                {
                    $f_start=date("Y",strtotime("1 ".$arr[0]));		
                }
            }
            if($financial_year->start_year == "2022" ||  $f_start == "2022")
            {
                $school_financial_years[$financial_year->school_id]['current'] = $financial_year->id;
            }
            if( $financial_year->start_year == "2021" ||  $f_start == "2021")
            {
                $school_financial_years[$financial_year->school_id]['previous'] = $financial_year->id;
            }
            $invalid_current[$financial_year->school_id][] =  $financial_year;
            // if($financial_year->school_id ==71)
            // {
            //     debug_a( array($financial_year->start_year == "2022" ||  $f_start == "2022",$f_start,$financial_year,$school_financial_years[$financial_year->school_id]),"---",1);
            // }
            
        }

       foreach( $school_financial_years as $school_id =>  $school_financial_year)
       {
           
                if(isset( $school_financial_year['current']) && isset( $school_financial_year['previous']) )
                {
                    $bulk_update[] = array("id"=>$school_financial_year['current'],'previous_financial_year_id'=>$school_financial_year['previous']);
                }
                else

                {
                    $invalid_previous[$school_id] = $invalid_previous[$school_id] ?? [];
                    $invalid_previous[$school_id][] = array($school_financial_year,$invalid_current[$school_id]);
                }
       }
       if(!empty( $bulk_update))
       {
            $ledgers=$this->accountledgers->update_batch("financial_years",$bulk_update,'id');
       }

        // $previous_financial_year=$this->accountledgers->get_single('financial_years',array('school_id'=>$school->id,'is_running!=1',"id!=$financial_year->id"));	
        // debug_a( $bulk_update,"Currect",1);
        debug_a( $invalid_previous,"Invalid Previous",1);

       
    }
    public function update_ledger_balaces($finacial_year_id = null,$school_id = null)
    {
        $this->load->model('Accountledgers_Model', 'accountledgers', true);			
		$this->load->model('Accountgroups_Model', 'accountgroups', true);	
         $this->load->model('administrator/Financialyear_Model', 'year', true);
       
        if(!$school_id && ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)){
            $school_id = $this->session->userdata('school_id');
        }   
        $school = $this->accountledgers->get_school_by_id($school_id);
        // $current_financial_year_id = $school->financial_year_id;
        $financial_year=$this->accountledgers->get_single('financial_years',array('school_id'=>$school->id,'is_running'=>1));	
        if (!$finacial_year_id &&  $financial_year->previous_financial_year_id)	 $finacial_year_id  =  $financial_year->previous_financial_year_id;
        $current_financial_year_id = $financial_year->id;
        if(empty($financial_year) || !$finacial_year_id )
        {
            error($this->lang->line('update_failed'));
            redirect('administrator/financialyear');
        }
        if(strpos($financial_year->session_year,"->"))	
        {
            $arr=explode("->",$financial_year->session_year);
            $f_start=date("Y-m-d",strtotime($arr[0]));		
        }
        else
        {
            $arr=explode("-",$financial_year->session_year);
            $date_exploded = explode(" ",$arr[0]);
            if(count($date_exploded)>2)
            {
                $f_start=date("Y-m-d",strtotime($arr[0]));		
            }
            else
            {
                $f_start=date("Y-m-d",strtotime("1 ".$arr[0]));		
            }
        }		  
        $start_date =strtotime($f_start);

        $start_date= date("Y-m-d",strtotime('-1 day',$start_date));
        // debug_a($start_date);

        if(!$school_id || !$finacial_year_id ||  !$start_date)
        {
            error($this->lang->line('update_failed'));
            redirect('administrator/financialyear');
        }

        $agroup=$this->accountgroups->get_accountgroup_by_types($school_id);	
        $account_ledger_details = array();
        $account_ledger_details_ids = array();
        $account_ledger_details_raw =$this->accountgroups->get_list('account_ledger_details', array('financial_year_id'=> $current_financial_year_id ));	
        foreach( $account_ledger_details_raw as  $account_ledger_details_single)
        {
            if(!isset($account_ledger_details_ids[$current_financial_year_id][$account_ledger_details_single->ledger_id])) $account_ledger_details_ids[$current_financial_year_id][$account_ledger_details_single->ledger_id] = $account_ledger_details_single->id;
        }
        $ledgers_not_found = [];
        $ainsertLedgers = [];
        foreach($agroup as $ag){				

           // get ledgers
           $group_total=0;				
           $group_id=$ag->id;
           $ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id,$finacial_year_id,$group_id);
                
           $j=0;
           //print_r($ledgers); exit;
           foreach($ledgers as $l){
               $in_arr=array();
               $in_arr['id']=$account_ledger_details_ids[$current_financial_year_id][$l->id] ?? 0;
               $in_arr['ledger_id']=$l->id;
               $in_arr['financial_year_id']=$current_financial_year_id;
               if(in_array($ag->type_id,array(4,3)))
               {
                  $cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,null,null,null,null,$finacial_year_id);
                  $cb=$cbalance;	
                  if($ag->type_id == 3)
                  {
                        if($cb>0)
                        {
                                $cbalance=(-$cb);
                        }
                        else
                        {
                                $cbalance=abs($cb);
                        }
                  }
                 
                  $in_arr['opening_balance']=$cbalance;

               }
               else
               {
                 $in_arr['opening_balance']=0;
               }
               if ($l->id == 19819)
               {
                    echo '<pre>'; var_dump($in_arr['opening_balance']); die(); 
               }
               if($ag->type_id == 4)
               {
                    $in_arr['opening_cr_dr']= "CR";
               }
               elseif($ag->type_id == 3)
               {
                    $in_arr['opening_cr_dr']= "DR";
               }
               else
               {
                    $in_arr['opening_cr_dr']=$l->dr_cr;
               }
               if($in_arr['id']) {
                    $account_ledger_details[] = $in_arr;
               } else {
                    if(!in_array($l->id,  $ledgers_not_found)) {
                        $ledgers_not_found[] = $l->id;
                        unset($in_arr['id']);
                        $ainsertLedgers[] = $in_arr;
                    }
               }
           }
        }
        // echo "<pre>";
        // print_r($ledgers_not_found);
        // die();
        // debug_a($account_ledger_details);
        if(!empty($ainsertLedgers))
        {
            $ledgers=$this->accountledgers->insert_batch("account_ledger_details",$ainsertLedgers);
        }
        if(!empty($account_ledger_details))
        {
            
            $ledgers=$this->accountledgers->update_batch("account_ledger_details",$account_ledger_details,'id');
        }
        $this->year->update('financial_years', array('data_updated'=>1), array('id' => $finacial_year_id));
        // echo $this->db->last_query();
        // die();
       
            success($this->lang->line('update_success'));
        redirect("debugmode/copy_ledger_balances_for_all/$school_id/$financial_year->previous_financial_year_id");        
    }
    function m_mode_on()
    {
       $_SESSION['m_mode_on'] = 1;
       die("on");
    }
    function auto_financial_year()
    {
        $this->load->model('administrator/Financialyear_Model', 'year', true);
        $school_id = $this->session->userdata('school_id');   
        if($school_id && 1==0)
        {
            $this->year->update('schools', array("import_data"=>1), array('id' => $school_id));
        }
        redirect('dashboard/index');
    }
    function m_mode_off()
    {
       $_SESSION['m_mode_on'] = 0;
       die("off");

    }
    function test_function($school_id = null,$ledger_ids = null)
    {
        $ledger_ids = $ledger_ids ? array($ledger_ids) :array(247916) ;
        $school_id = $school_id ? $school_id : 89 ;

        error_on();
		echo update_ledger_opening_balance($ledger_ids,$school_id);
    }
    function copy_ledger_balances_for_all($school_id = null,$previous_finacial_year_id = null)
    {
        $this->load->model('Accountledgers_Model', 'accountledgers', true);			
		$this->load->model('Accountgroups_Model', 'accountgroups', true);	
         $this->load->model('administrator/Financialyear_Model', 'year', true);
         $account_ledger_details = [];
        if(!$school_id && ($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1)){
            $school_id = $this->session->userdata('school_id');
        }   
        $school = $this->accountledgers->get_school_by_id($school_id);
        $current_financial_year=$this->accountledgers->get_single('financial_years',array('school_id'=>$school->id,'is_running'=>1));
        if(empty($current_financial_year))
        {
            error($this->lang->line('update_failed'));
            redirect('administrator/financialyear');
        }
        $current_financial_year_id = $current_financial_year->id;
        if($previous_finacial_year_id)
        {
            $financial_year=$this->accountledgers->get_single('financial_years',array('id'=>$previous_finacial_year_id));		
        }
        else
        {
            $financial_year=$this->accountledgers->get_single('financial_years',array('id'=>$current_financial_year->previous_financial_year_id));		
        }

        if(empty($financial_year))
        {
            error($this->lang->line('update_failed'));
            redirect('administrator/financialyear');
        }
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
        $start_date =strtotime($f_start);

        $start_date= date("Y-m-d",strtotime('-1 day',$start_date));
        // debug_a($start_date);

        if(!$school_id ||  !$start_date)
        {
            error($this->lang->line('update_failed'));
            redirect('administrator/financialyear');
        }

        $ledgers_list = $this->accountledgers->get_accountledger_list($school_id,$financial_year->id,null,null,null,array("3","4"),null,null,null,null);
        $ledgers_list_updated = [];
        foreach($ledgers_list as $ledger)
        {
            $ledgers_list_updated[$ledger->id] = $ledger;
            $ledger_ids[] = $ledger->id;
        }	
        $account_ledger_details = array();
        $account_ledger_details_ids = array();
        $account_ledger_details_raw =$this->accountgroups->get_list('account_ledger_details', array('financial_year_id'=> $current_financial_year_id ));	
        foreach( $account_ledger_details_raw as  $account_ledger_details_single)
        {
            if(!isset($account_ledger_details_ids[$current_financial_year_id][$account_ledger_details_single->ledger_id])) $account_ledger_details_ids[$current_financial_year_id][$account_ledger_details_single->ledger_id] = $account_ledger_details_single->id;
        }
        $ledger_transactions=$this->accountledgers->get_ledger_with_amount_list_with_balance($ledger_ids, $f_start,$f_end,1);
		// echo $this->db->last_query();
		// die();
		 $ledgers = array();	

		 $transations_list_updated = [];
		 $tr_ids = [];
		 $ledger_tr_ids = [];
		 foreach($ledger_transactions as $ledger_transaction)
		 {
			$transactions[] =  $ledger_transaction;
			if($ledger_transaction->cancelled ==1) continue;
			if(!isset($ledgers_transactions_updated[$ledger_transaction->ledger_id]))
			{
				$ledgers_transactions_updated[$ledger_transaction->ledger_id] =  array();
				$ledger_tr_ids[$ledger_transaction->ledger_id] =  array();
			}
			$ledgers_transactions_updated[$ledger_transaction->ledger_id][] =  $ledger_transaction;
			
			if(!in_array($ledger_transaction->id,$tr_ids) )
			{
				$tr_ids[] = $ledger_transaction->id;
			}
		 }
		 
		 $other_transations_list_updated = [];

		 $other_transactions=$this->accountledgers->get_ledger_with_amount_list_with_balance_excluded($ledger_ids,null, $f_start,$f_end,1);
			// echo "<pre>";
			 //echo $this->db->last_query();
			//  var_dump($other_transactions);
			//  die();
			// debug_a($other_transactions,"Others");

		 foreach($other_transactions as $other_transaction)
		 {
			$transactions[] =  $other_transaction;
			if($other_transaction->cancelled ==1) continue;
			if(!isset($other_transations_list_updated[$other_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id] =  array();
			}
			if (!isset($ledger_tr_ids[$other_transaction->ledger_id]))
			{
				$ledger_tr_ids[$other_transaction->ledger_id] = array();

			}

			if( !in_array($other_transaction->transaction_id,$ledger_tr_ids[$other_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id][] =  $other_transaction;

			}
			
		 }
           $j=0;

           //print_r($ledgers); exit;
           foreach($ledgers_list_updated as $ledger_id => $ledger){
               // get current balance	
               if(!isset($ledgers[$ledger_id]))
               {
               
                   $ledgers[$ledger_id] = $ledger;
                   
                   $ledgers[$ledger_id]->effective_balance =0;
               }
               $in_arr=array();
               $in_arr['id']= $account_ledger_details_ids[$current_financial_year_id][$ledger_id] ?? null;
               $in_arr['ledger_id']=$ledger_id;
               $in_arr['financial_year_id']=$current_financial_year_id;
              
                 

               $ledger_transactions = isset($ledgers_transactions_updated[$ledger_id]) && $ledgers_transactions_updated[$ledger_id] ? $ledgers_transactions_updated[$ledger_id] :  array();
				
				   $grand_total = 0;
				   foreach($ledger_transactions as $ledger_transaction){
					   
					   if($ledger_transaction->head_cr_dr == "DR")
					   {
						   $grand_total= $grand_total-($ledger_transaction->total_amount);
					   }
					   else
					   {
						   $grand_total= $grand_total+($ledger_transaction->total_amount);
					   }
					   
				   }
				   $other_transactions = isset($other_transations_list_updated[$ledger_id]) && $other_transations_list_updated[$ledger_id] ? $other_transations_list_updated[$ledger_id] :  array();
				   foreach($other_transactions as $other_transaction){
					   
					   if($other_transaction->head_cr_dr == "DR")
					   {
						   $grand_total= $grand_total+($other_transaction->amount);
					   }
					   else
					   {
						   $grand_total= $grand_total-($other_transaction->amount);
					   }
				   }
				   if($ledgers[$ledger_id]->opening_cr_dr =='DR'){
					   $opening_balance = -($ledger->opening_balance);
					   $ledgers[$ledger_id]->effective_balance_cr_dr='DR';
				   }
				   else{
					   $opening_balance = $ledger->opening_balance;
					   $ledgers[$ledger_id]->effective_balance_cr_dr='CR';
				   }
				   $final_amount=$opening_balance+$grand_total;
				   $ledgers[$ledger_id]->effective_balance =  $final_amount;
                   if( $ledgers[$ledger_id]->effective_balance < 0){
                     $ledgers[$ledger_id]->effective_balance_cr_dr= "DR";
                    }
                    else if($ledgers[$ledger_id]->effective_balance > 0){
                        $ledgers[$ledger_id]->effective_balance_cr_dr= "CR";	
                    }
                   
                   $in_arr['opening_balance']=$ledgers[$ledger_id]->effective_balance;
                   $in_arr['opening_balance']=abs($ledgers[$ledger_id]->effective_balance);
                   $in_arr['opening_cr_dr']=  $ledgers[$ledger_id]->effective_balance_cr_dr;
                    $account_ledger_details[] = $in_arr;
           }
           if(!empty($account_ledger_details))
           {
               
               $ledgers= $this->accountledgers->update_batch("account_ledger_details",$account_ledger_details,'id');
           }
           redirect('dashboard/index');
    }
    public function test_new()
    {
        $this->load->model('Accounttransactions_Model', 'transactions', true);	
       $aTest =  $this->db->query("SELECT * FROM `classes` WHERE `disciplines` = '3' AND `numeric_name` = 'p6' AND `school_id` = '175'")->row();
       //$this->transactions->query('classes',  array());
       
       echo $this->db->last_query();
       echo "<pre>";
       var_dump($aTest);		
    }
}