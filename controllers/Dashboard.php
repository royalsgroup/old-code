<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ***************Dashboard.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Dashboard
 * @description     : This class used to showing basic statistics of whole application 
 *                    for logged in user.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers    
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Dashboard extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Dashboard_Model', 'dashboard', true);  
        $this->load->model('Administrator/Year_Model', 'year', true);
		$this->load->model('Voucher_Model', 'voucher', true);    
		$this->load->model('Accountledgers_Model', 'ledgers', true);
		$this->load->model('Payroll/Payscalecategory_Model', 'grade', true);            		
		$this->load->model('Academic/Classes_Model', 'classes', true);            		
		$this->load->model('Academic/Subject_Model', 'subject', true);  
        if ($_GET['error_on'] ?? false)
        {
        }
        
    }

    public $data = array();

    /*     * ***************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Default function, Load logged in user dashboard stattistics  
     * @param           : null 
     * @return          : null 
     * ********************************************************** */

    public function index() {
      
        $this->data['school'] = array();
        $school_id = $this->session->userdata('school_id');   
        $theme = $this->session->userdata('theme');
        
        $this->data['theme'] = $this->dashboard->get_single('themes', array('status' => 1, 'slug' => $theme));

        $studentData = $this->dashboard->get_student_by_class($school_id);
        $studentDataDroped = $this->dashboard->get_drop_student_by_class($school_id);
        $studentDataDropedAr = array();
        foreach ($studentDataDroped as $key => $value) {
            $class_name = $value->class_name;
            $total_student = $value->total_student;
            $studentDataDropedAr["$class_name"] = $total_student;
        }
          //echo "<pre>";print_r($studentDataDropedAr);die("aaaqqqqq");
        $this->data['studentData']   = $studentData;
        $this->data['studentDataDropedAr']   = $studentDataDropedAr;

        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $this->data['school']   = $this->dashboard->get_single('schools', array('status'=>1, 'id'=>$school_id));
            $school =  $this->data['school'] ;
            if ( $school->id == 71)
            {
                $_SESSION['debugmode'] =  true;        
            }
            // if(isset($school->create_academic_year) && $school->create_academic_year ==2)
            // {
            //     $data =array();
            //     $session_start      = Date("01-05-Y",time());
            //     $session_end        = Date("30-04-Y",strtotime('+1 year'));
            //     $session_start      = Date("d F Y",strtotime($session_start));
            //     $session_end        = Date("d F Y",strtotime( $session_end));
            //     $data['start_year'] = preg_replace('/\D/', '', $session_start);
            //     $data['end_year']   = preg_replace('/\D/', '', $session_end);
            //     $data['session_year'] = $session_start .' - '. $session_end;
                
            //     $data['school_id']   = $school_id;
            //     $check = $this->year->get_single("academic_years",  $data);
            //     if($check->id == $school->academic_year_id)
            //     {
            //         $this->session->set_userdata('default_data','1');
            //     }
            // }
            // else
            // {
            //     $this->session->set_userdata('default_data','0');
            // }

            if(isset($school->import_data) && $school->import_data ==1 )
            {
                    $school_id          = $school->id;
                    $session_start      = Date("01-04-Y",time());
                    $session_end        = Date("31-03-Y",strtotime('+1 year'));    
                    $financial_year_id = $this->create_financial_year($school_id, $school->financial_year_id);	
                    $this->ledgers->financial_year_update($school_id,$financial_year_id);
                    // //now insert default payscale category 
                    // $this->grade->insert_default($school_id);
                    // //now insert default to vouchers
                    $this->voucher->insert_default($school_id,$financial_year_id);
                    // $this->classes->insert_default($school_id);
                    // $this->subject->insert_default($school_id);
                    $data['financial_year_id']= $financial_year_id;
                    // $data['academic_year_id'] = $this->create_academic_year($school_id);	
					// $update_arr['academic_year']=preg_replace('/\D/', '', $$session_start)." - ".preg_replace('/\D/', '', $session_end );
                    $data['import_data']= 0;
                    $this->dashboard->update('schools', $data, array('id' => $school_id));
                    redirect("debugmode/update_ledger_balaces/0/$school_id");

            }
            if(isset($school->create_academic_year) && $school->create_academic_year ==1 )
            {
                $academic_year_id = $this->create_academic_year($school_id, $school->academic_year_id);	
                
                $data['academic_year_id']= $academic_year_id;

                $this->year->update('academic_years', array('is_running' => 0), array('school_id'=>$school_id));
                $this->year->update('academic_years', array('is_running' => 1), array('id'=>$academic_year_id));

                $this->dashboard->update('schools', $data, array('id' => $school_id));
                redirect("debugmode/create_new_ledgers/$school_id");
            }
            if(isset($school->update_data) && $school->update_data ==1 )
            {
                $data['update_data']= 0;
                $this->dashboard->update('schools', $data, array('id' => $school_id));
                redirect("debugmode/update_ledger_balaces/0/$school_id");
            }
            if(isset($school->copy_schedule) && $school->copy_schedule ==1 )
            {
                redirect("debugmode/copy_grades/$school_id");
            }
        }            
       
        
        $this->data['news'] = $this->dashboard->get_list('news', array('status' => 1, 'school_id'=>$school_id), '', '5', '', 'id', 'DESC');
        $this->data['notices'] = $this->dashboard->get_list('notices', array('status' => 1, 'school_id'=>$school_id), '', '5', '', 'id', 'DESC');
        // $this->data['events'] = $this->dashboard->get_list('events', array('status' => 1, 'school_id'=>$school_id), '', '', '10', 'id', 'DESC');
        // $this->data['holidays'] = $this->dashboard->get_list('holidays', array('status' => 1, 'school_id'=>$school_id), '', '10', '', 'id', 'DESC');
       
        
        // $this->data['users'] = $this->dashboard->get_user_by_role($school_id);
        // $this->data['students'] = $this->dashboard->get_student_by_class($school_id);

        // $this->data['total_student'] = $this->dashboard->get_total_student($school_id);
        // $this->data['total_guardian'] = $this->dashboard->get_total_guardian($school_id);
        // $this->data['total_teacher'] = $this->dashboard->get_total_teacher($school_id);
        // $this->data['total_employee'] = $this->dashboard->get_total_employee($school_id);
        // $this->data['total_expenditure'] = $this->dashboard->get_total_expenditure($school_id);
        // $this->data['total_income'] = $this->dashboard->get_total_income($school_id);
        
                 
        // $this->data['sents'] = $this->dashboard->get_message_list($type = 'sent');
        // $this->data['drafts'] = $this->dashboard->get_message_list($type = 'draft');
        // $this->data['trashs'] = $this->dashboard->get_message_list($type = 'trash');
        // $this->data['inboxs'] = $this->dashboard->get_message_list($type = 'inbox');
        // $this->data['new'] = $this->dashboard->get_message_list($type = 'new');
        
        // $this->data['school_setting'] = $this->school_setting;
        // $this->data['schools'] = $this->schools;
        
        // $stats = array();
        
        // foreach($this->data['schools'] as $obj){
            
        //     $arr = array();
            
        //     $total_class = $this->dashboard->get_total_class($obj->id);
        //     $total_student = $this->dashboard->get_total_student($obj->id);
        //     $total_teacher = $this->dashboard->get_total_teacher($obj->id);
        //     $total_employee = $this->dashboard->get_total_employee($obj->id);
        //     $total_income = $this->dashboard->get_total_income($obj->id);
        //     $total_expenditure = $this->dashboard->get_total_expenditure($obj->id);
            
        //     $arr[] = $total_class > 0 ? $total_class : 0;
        //     $arr[] = $total_student > 0 ? $total_student : 0;
        //     $arr[] = $total_teacher > 0 ? $total_teacher : 0;
        //     $arr[] = $total_employee > 0 ? $total_employee : 0;
        //     $arr[] = $total_income > 0 ? $total_income : 0;
        //     $arr[] = $total_expenditure > 0 ? $total_expenditure : 0;

        //     $stats[$obj->id] = $arr;
              
        // } 
        
        // $this->data['stats'] = $stats;
        
        $this->layout->title($this->lang->line('dashboard') . ' | ' . SMS);
        
        $this->layout->view('dashboard', $this->data);
        
    }
    public function get_school_stats()
    {
        $school_id = $this->session->userdata('school_id');   
        $students = $this->dashboard->get_student_by_class($school_id);
        $school_data = array();
        if(isset($students) && !empty($students)){ 
            foreach($students as $obj){ 
                $school_data[] =  array($this->lang->line('class')." ".$obj->class_name, (int)$obj->total_student);
            } 
         }     
         $response['data'] =   $school_data;
        echo json_encode( $response);
        die();
    }
    public function get_news_notices()
    {
        $school_id = $this->session->userdata('school_id');   
        $news = $this->dashboard->get_list('news', array('status' => 1, 'school_id'=>$school_id), '', '5', '', 'id', 'DESC');
        $notices = $this->dashboard->get_list('notices', array('status' => 1, 'school_id'=>$school_id), '', '5', '', 'id', 'DESC');
        $notice_list = "";
        if(isset($notices) && !empty($notices)){ 
             foreach($notices as $obj ){ 
                $notice_list .= '<li>
                    <a href="'.site_url('announcement/notice/view/'.$obj->id).'">                                       
                        <span>
                            <span>'.$obj->title.'</span>
                            <span>&nbsp;</span>
                            <span class="time">'.get_nice_time($obj->created_at).'</span>
                        </span>                                        
                    </a>
                </li>';
            }   
         }   
         $news_list = "";  
         if(isset($news) && !empty($news)){ 
         foreach($news as $obj ){
            $news_list .=  '<li>
                <a href="'.site_url('announcement/news/view/'.$obj->id).'">
                    <span class="image">';
                        if($obj->image != ''){ 
                            $news_list .=  '<img src="'.UPLOAD_PATH.'/news/'.$obj->image.'" alt="" width="70" /> ';
                            }else{
                                $news_list .=  ' <img src="'.IMG_URL.'default-user.png" alt="Profile Image" />';
                        }
                        $news_list .=  '  </span>
                    <span>
                        <span>'.$obj->title.'</span>
                        <span class="message"></span>
                        <span class="time">'.get_nice_time($obj->created_at).'</span>
                    </span>                                        
                </a>
            </li>';
            }   
        }    
        $events = $this->dashboard->get_list('events', array('status' => 1, 'school_id'=>$school_id), '', '5', '', 'id', 'DESC');
        $event_list = "";
        if(isset($events) && !empty($events)){ 
             foreach($events as $obj ){ 
                $event_list .= '<li>
                    <a href="'.site_url('event/index/0/'.$obj->id).'">                                       
                        <span>
                            <span>'.$obj->title.'</span>
                            <span>&nbsp;</span>
                            <span class="time">'.get_nice_time($obj->created_at).'</span>
                        </span>                                        
                    </a>
                </li>';
            }   
         }  
		 $response['events'] =   $event_list && $event_list != "" ? $event_list  : "No Events" ; 
         $response['news'] =   $news_list && $news_list != "" ? $news_list  : "No News" ;
         $response['notices'] =   $notice_list && $notice_list != "" ? $notice_list : "No Notices" ;
        echo json_encode( $response);
        die();
    }
    public function get_user_stats()
    {
        $school_id = $this->session->userdata('school_id');   
        $users = $this->dashboard->get_user_by_role($school_id);
        $user_data = array();
       if(isset($users) && !empty($users)){
            foreach($users as $obj){ 
                $user_data[] = array($obj->name,(int)$obj->total_user);
            }
        }
         $response['data'] =   $user_data;
        echo json_encode( $response);
        die();
    }
    public function get_calendar_events()
    {
        $school_id = $this->session->userdata('school_id');  
        $theme_name = $this->session->userdata('theme');
        $theme = $this->dashboard->get_single('themes', array('status' => 1, 'slug' => $theme_name));   
        $events = $this->dashboard->get_list('events', array('status' => 1, 'school_id'=>$school_id), '', '', '10', 'id', 'DESC');
        $holidays = $this->dashboard->get_list('holidays', array('status' => 1, 'school_id'=>$school_id), '', '10', '', 'id', 'DESC');
        $events =  array();
        foreach($events as $obj)
        {
            $events[] =  array(
                "title" => $obj->title,
                "start" => date('Y-m-d', strtotime($obj->event_from))."T".date('H:i:s', strtotime($obj->event_from)),
                "end" => date('Y-m-d', strtotime($obj->event_to))."T".date('H:i:s', strtotime($obj->event_to)),
                "backgroundColor" => $theme->color_code, //red
                "url" => site_url('event/index/0/'.$obj->id), //red
                "color" => '#ffffff'
                );
        }
        foreach($holidays as $obj)
        {
            
            $events[] =  array(
                "title" => $obj->title,
                "start" => date('Y-m-d', strtotime($obj->date_from))."T".date('H:i:s', strtotime($obj->date_from)),
                "end" => date('Y-m-d', strtotime($obj->date_to))."T".date('H:i:s', strtotime($obj->date_to)),
                "backgroundColor" => $theme->color_code, //red
                "url" =>  site_url('announcement/holiday/index/0/'.$obj->id), //red
                "color" => '#ffffff'
                );
        }
        echo json_encode( $events);
        die();
    }
    
    public function get_message_stats()
    {
         $sent = $this->dashboard->get_message_list($type = 'sent');
        $draft = $this->dashboard->get_message_list($type = 'draft');
        $trash= $this->dashboard->get_message_list($type = 'trash');
        $inbox = $this->dashboard->get_message_list($type = 'inbox');
        $new = $this->dashboard->get_message_list($type = 'new');
        $message_data = array(
        [
            "name" => $this->lang->line('new'),
            "y" => count($new),
            "drilldown" => null
        ],
        [
            "name"=> $this->lang->line('inbox'),
            "y"=>count($inbox),
            "drilldown" => null
        ],
        [
            "name"=> $this->lang->line('send'),
            "y"=>count($sent),
            "drilldown" => null
        ],
        [
            "name"=> $this->lang->line('draft'),
            "y"=>count($draft),
            "drilldown" => null
        ],
        [
            "name"=> $this->lang->line('trash'),
            "y"=>count($trash),
            "drilldown" => null
        ]);
         $response['data'] =    $message_data;
        echo json_encode( $response);
        die();
    }
    
    public function login_message()
    {
        if($this->session->userdata('role_id') == SUPER_ADMIN){    
            if ($_POST) {
                $data = $this->_get_posted_message_data();
                $updated = $this->dashboard->update('global_setting', array("login_notice" =>$data['login_message']), array('status' => 1));
                success($this->lang->line('update_success'));
            }
            $this->global_setting = $this->db->get_where('global_setting', array('status'=>1))->row();
            $this->data['login_message'] = $this->global_setting->login_notice ?? "";
            $this->layout->view('login_message', $this->data);
        }
    }
    private function _get_posted_message_data()
    {
        $items = array();
        $items[] = 'login_message';
        $data = elements($items, $_POST);
        return $data;
    }
    public function get_school_data($request='')
    {
        if($request =='yes'){ 
            foreach($this->schools as $obj){
            $data = array("name"=> $obj->school_name,"data"=>[]);
            $arr = array();
            
            $total_class = $this->dashboard->get_total_class($obj->id);
            $total_student = $this->dashboard->get_total_student($obj->id);
            $total_teacher = $this->dashboard->get_total_teacher($obj->id);
            $total_employee = $this->dashboard->get_total_employee($obj->id);
            $total_income = $this->dashboard->get_total_income($obj->id);
            $total_expenditure = $this->dashboard->get_total_expenditure($obj->id);
            
            $arr[] = $total_class > 0 ? $total_class : 0;
            $arr[] = $total_student > 0 ? $total_student : 0;
            $arr[] = $total_teacher > 0 ? $total_teacher : 0;
            $arr[] = $total_employee > 0 ? $total_employee : 0;
            $arr[] = $total_income > 0 ? $total_income : 0;
            $arr[] = $total_expenditure > 0 ? $total_expenditure : 0;
            $data['data'] = $arr;
            $stats[] = $data;
            
            }

            return $stats;

        }else{
          if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){     
            foreach($this->schools as $obj){
                $data = array("name"=> $obj->school_name,"data"=>[]);
                $arr = array();
                
                $total_class = $this->dashboard->get_total_class($obj->id);
                $total_student = $this->dashboard->get_total_student($obj->id);
                $total_teacher = $this->dashboard->get_total_teacher($obj->id);
                $total_employee = $this->dashboard->get_total_employee($obj->id);
                $total_income = $this->dashboard->get_total_income($obj->id);
                $total_expenditure = $this->dashboard->get_total_expenditure($obj->id);
                
                $arr[] = $total_class > 0 ? $total_class : 0;
                $arr[] = $total_student > 0 ? $total_student : 0;
                $arr[] = $total_teacher > 0 ? $total_teacher : 0;
                $arr[] = $total_employee > 0 ? $total_employee : 0;
                $arr[] = $total_income > 0 ? $total_income : 0;
                $arr[] = $total_expenditure > 0 ? $total_expenditure : 0;
                $data['data'] = $arr;
                $stats[] = $data;
            }                 
        }
            $this->data['stats'] = $stats;
            echo json_encode($this->data);
            die();
        }
    }
    public function get_dashboard_data()
    {
        $school_id = $this->session->userdata('school_id');   

	$this->data['total_student'] = $this->dashboard->get_total_student($school_id);
	$this->data['total_attended_student'] = $this->dashboard->get_total_attended_student($school_id);
	$this->data['total_guardian'] = $this->dashboard->get_total_guardian($school_id);
	$this->data['total_teacher'] = $this->dashboard->get_total_teacher($school_id);
	$this->data['total_attended_teacher'] = $this->dashboard->get_total_attended_teacher($school_id);
	$this->data['total_employee'] = $this->dashboard->get_total_employee($school_id);
	$this->data['total_attended_employee'] = $this->dashboard->get_total_attended_employee($school_id);
       
        echo json_encode($this->data);
        die();
    }
    private function create_financial_year($school_id, $iCurrentYearID ="") {
        $data =array();
        $session_start      = Date("01-04-Y",time());
        $session_end        = Date("31-03-Y",strtotime('+1 year'));
        $data['start_year'] = preg_replace('/\D/', '', $session_start);
        $data['end_year']   = preg_replace('/\D/', '', $session_end);
        $data['session_year'] = $session_start .' -> '. $session_end;
        $data['school_id']   = $school_id;
        $check = $this->year->get_single("financial_years",  $data);
        if (!empty($check) && ($check->previous_financial_year_id)) return $check->id;
        else if(!empty($check) && (!$check->previous_financial_year_id))
        {
            $previous_session_start        = Date("01-04-Y",strtotime('-1 year'));
            $previous_session_end        = Date("31-03-Y",time());
            $previous['start_year'] = preg_replace('/\D/', '', $previous_session_start);
            $previous['end_year']   = preg_replace('/\D/', '', $previous_session_end);
            $previous['session_year'] = $previous_session_start .' -> '. $previous_session_end;
            $previous['school_id']   = $school_id;
            $previous_year = $this->year->get_single("financial_years",  $data);
            if(!empty($previous_year))
            {
                $this->dashboard->update('financial_years', $previous, array('id' => $previous_year->id));
            }
            else
            {
                die("Invalid Financial Year settings, Please contact admin");
            }
            return $check->id;
        }
        else
        {
            $data['previous_financial_year_id']   = $iCurrentYearID;
            $data['is_running'] = 1;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $this->year->update("financial_years",  array("is_running"=>0), array('id' => $iCurrentYearID));
           return  $this->year->insert('financial_years', $data);
        }
    }
    private function create_academic_year($school_id,  $iCurrentYearID) {
        
        $data =array();
        $session_start      = Date("01-05-Y",time());
        $session_end        = Date("30-04-Y",strtotime('+1 year'));
        $session_start      = Date("d F Y",strtotime($session_start));
        $session_end        = Date("d F Y",strtotime( $session_end));
        $data['start_year'] = preg_replace('/\D/', '', $session_start);
        $data['end_year']   = preg_replace('/\D/', '', $session_end);
        $data['session_year'] = $session_start .' - '. $session_end;
        
        $data['school_id']   = $school_id;
        $check = $this->year->get_single("academic_years",  $data);
        if (!empty($check) && ($check->previous_academic_year_id)) return $check->id;
        else if(!empty($check) && (!$check->previous_academic_year_id))
        {
            $previous_session_start = Date("01-05-Y",strtotime('-1 year'));
            $previous_session_end   = Date("30-04-Y",time());
            $previous['start_year'] = preg_replace('/\D/', '', $previous_session_start);
            $previous['end_year']   = preg_replace('/\D/', '', $previous_session_end);
            $previous['session_year'] = $previous_session_start .' -> '. $previous_session_end;
            $previous['school_id']   = $school_id;
            $previous_year = $this->year->get_single("academic_years",  $data);
            if(!empty($previous_year))
            {
                $this->dashboard->update('academic_years', $previous, array('id' => $check->id));
            }
            else
            {
                die("Invalid Financial Year settings, Please contact admin");
            }
            return $check->id;
        }
        else
        {
            $data['previous_academic_year_id']   = $iCurrentYearID;
        //     $data['is_running'] = 1;
        //     $data['status'] = 1;
        //     $data['created_at'] = date('Y-m-d H:i:s');
        //     $data['created_by'] = logged_in_user_id();
        //     $this->year->update("financial_years",  array("is_running"=>0), array('id' => $iCurrentYearID));
        //    return  $this->year->insert('financial_years', $data);
            $data['is_running'] = 1;
            $data['status'] = 1;
            $data['note'] = 'auto created';

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            return $this->year->insert('academic_years', $data);
        }
    }
}
