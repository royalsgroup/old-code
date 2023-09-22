<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Member.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Member
 * @description     : Manage transport member of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Member extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Member_Model', 'member', true);  
        $this->load->library('message/message');
    }

        
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Transport Member List" user interface                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {
        
        check_permission(VIEW);
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
           $school_id = $this->session->userdata('school_id');  
        }
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        if (($_GET['debug_mode'] ?? "") || ($_SESSION['debug_mode'] ?? ""))
        {
            $_SESSION['debug_mode'] = true;
            $school->academic_year_id = 1468;
        }
       // $this->data['members'] = $this->member->get_transport_member_list($is_transport_member = 1, $school_id);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('transport') . ' ' . $this->lang->line('member') . ' | ' . SMS);
        $this->layout->view('member/member', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Transport Member" user interface                 
    *                     
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add($school_id = null) {

        check_permission(ADD);

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
			$school_id=$this->session->userdata('school_id');        
            $condition['school_id'] = $school_id; 
            $this->data['routes'] = $this->member->get_list('routes', $condition);     
        }
        
        $condition['school_id'] = $school_id;   
        $this->data['routes'] = $this->member->get_list('routes', $condition);
        $this->data['filter_school_id'] = $school_id;
        $this->data['school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        // $this->data['non_members'] = $this->member->get_transport_member_list($is_transport_member = 0, $school_id);
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('transport') . ' ' . $this->lang->line('non_member') . ' | ' . SMS);
        $this->layout->view('member/non_member', $this->data);
    }

        
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Transport Member" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        if(!is_numeric($id)){
           error($this->lang->line('unexpected_error'));
           redirect('transport/member/index');
        }
        
        $member = $this->member->get_transport_member($id);
        $school_id = @$member->school_id;
        $academic_year=$this->member->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
        $academic_year_id = $academic_year->previous_academic_year_id ?? 0; 
        $error =0;
        // if($academic_year_id)
        // {
        //     $amount = 0;
        //     $income_head =  $this->member->get_income_heads($school_id, $academic_year_id, 'transport'); 
        //     $fee = $this->member->get_transport_fee(@$member->student_id);
           
            
        //     if (!empty($fee)) {
        //         $yearly_stop_fares = $fee->yearly_stop_fare ? json_decode($fee->yearly_stop_fare,true) : array();
        //         if(isset( $yearly_stop_fares[$academic_year_id]))
        //         {
        //             $previous_invoices = $this->member->get_invoice_list_prev($school_id, @$member->student_id,$income_head->id);
        //             if(sizeof($previous_invoices)==0)
        //             {
        //                 $amount = 0;// $yearly_stop_fares[$academic_year_id];
        //             }
        //             else
        //             {
        //                 $amount = $yearly_stop_fares[$academic_year_id];
        //             }
        //         }
        //         else
        //         {
        //             $amount = $fee->stop_fare;
        //         }
        //     }
        //     $paid_amount=$this->member->get_paid_fee_amount( null,@$member->student_id,$income_head->id,$academic_year_id,null);
        //     if(($amount - $paid_amount) >0)
        //     {
        //        $error = 1;
        //     }
        // }

        if($error ==1)
        {
            error('Cant delete , Please clear due fees');
        }
        else if (!$member->academic_year_id) {
            $year_list = $this->member->get_list('academic_years', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
            $iUpdated = 0;
            $ainsertBatch = array();
            unset($member->student_id);
            foreach($year_list as $year) {
                if ( $year->id != $academic_year->id) {
                    if(!$iUpdated) {
                        $this->member->update('transport_members', array('academic_year_id' => $year->id, "modified_at"=>date('Y-m-d H:i:s')), array('id' => $member->id));
                        $iUpdated = 1;
                    } else {
                        $ainsertBatch[] = array("school_id"=>$school_id
                                                ,"user_id"=>$member->user_id
                                                ,"route_id"=>$member->route_id
                                                ,"route_stop_id"=>$member->route_stop_id
                                                ,"status"=>$member->status
                                                ,"academic_year_id"=>$year->id
                                                ,"created_at"=>date('Y-m-d H:i:s')
                                        );
                    }
                }
            }
            $this->db->insert_batch("transport_members",$ainsertBatch);
            success($this->lang->line('delete_success'));
        } else if($this->member->delete('transport_members', array('id' => $id))) {
            // $this->member->update('students', array('is_transport_member' => 0), array('user_id' => $member->user_id));
            success($this->lang->line('delete_success'));
        }else {
            error($this->lang->line('delete_failed'));
        }
            
        
        
        redirect('transport/member/index/'.$member->school_id);
    }
          
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete bulk
    * @description     : delete "Transport Member" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function bulk_delete() {

        error_on();
        $response =  array();
        $updated_members = array();
        $error_members = array();
        $member_ids = $this->input->post('member_ids');    
        $response['unpaid'] =  false;
    
        if( !(has_permission(DELETE, 'transport', 'member'))){
            $response['success'] =  false;
            $response['updated_members'] =  $updated_members;
            $response['error_members'] =  $error_members;
            echo json_encode($response);
            die();
        }
        $response['success'] =  true;

        if (!empty($member_ids)) {
            foreach($member_ids  as $member_id)
            {
                // $member = $this->member->get_single('transport_members', array('id' => $member_id));
                $member = $this->member->get_transport_member($member_id);
                $error =0;
                $school_id = @$member->school_id;
                $academic_year=$this->member->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
                $academic_year_id = $academic_year->previous_academic_year_id ?? 0; 
                // if($academic_year_id)
                // {
                //     $amount = 0;
                //     $income_head =  $this->member->get_income_heads($school->id, $academic_year_id, 'transport'); 
                //     $fee = $this->member->get_transport_fee(@$member->student_id);
                //     if (!empty($fee)) {
                //         $yearly_stop_fares = $fee->yearly_stop_fare ? json_decode($fee->yearly_stop_fare,true) : array();
                //         if(isset( $yearly_stop_fares[$academic_year_id]))
                //         {
                //             $amount = $yearly_stop_fares[$academic_year_id];
                //         }
                //         else
                //         {
                //             $amount = $fee->stop_fare;
                //         }
                //     }
                //     $paid_amount=$this->member->get_paid_fee_amount( null,@$member->student_id,$income_head->id,$academic_year_id,null);
                //     if(($amount - $paid_amount) >0)
                //     {
                //         $error =1;
                //     }
                // }
                
                if($error ==1)
                {
                    $response['unpaid'] =  true;
                    $error_members[] = $member_id;
                }
                else if (!$member->academic_year_id) {
                    $year_list = $this->member->get_list('academic_years', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
                    $iUpdated = 0;
                    $ainsertBatch = array();
                    unset($member->student_id);
                    foreach($year_list as $year) {
                        if ( $year->id != $academic_year->id) {
                            if(!$iUpdated) {
                                $this->member->update('transport_members', array('academic_year_id' => $year->id, "modified_at"=>date('Y-m-d H:i:s')), array('id' => $member->id));
                                $iUpdated = 1;
                            } else {
                                $ainsertBatch[] = array("school_id"=>$school_id
                                                        ,"user_id"=>$member->user_id
                                                        ,"route_id"=>$member->route_id
                                                        ,"route_stop_id"=>$member->route_stop_id
                                                        ,"status"=>$member->status
                                                        ,"academic_year_id"=>$year->id
                                                        ,"created_at"=>date('Y-m-d H:i:s')
                                                );
                            }
                        }
                    }
                    $this->db->insert_batch("transport_members",$ainsertBatch);
                    success($this->lang->line('delete_success'));
                } 
                else if ($this->member->delete('transport_members', array('id' => $member_id))) {
                    $members = $this->member->get_list('transport_members', array('user_id'=>$member->user_id), '','', '', 'id', 'ASC'); 
                    if (empty($members))
                    {
                        $this->member->update('students', array('is_transport_member' => 0), array('user_id' => $member->user_id));
                    }
                    $updated_members[] = $member_id;
                } else {
                    $error_members[] = $member_id;
                }
            }
        }
        $response['updated_members'] =  $updated_members;
        $response['error_members'] =  $error_members;
        echo json_encode($response);
        die();
    }
    /*****************Function get_list**********************************
    * @type            : Function
    * @function name   : get_list
    * @description     : Ajax list page                 
    *                       
    * @param           : null
    * @return          : boolean json/
    * ********************************************************** */
	public function get_members_list(){		
        // for super admin 
       $school_id = '';
       $start=null;
       $limit=null;
       $search_text='';
       if($_POST){            
           $school_id = $this->input->post('school_id');
           $start = $this->input->post('start');
           $limit  = $this->input->post('length');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
       }
       
        
       
      if($school_id){
        $school = $this->member->get_school_by_id($school_id);
        $academic_year=$this->member->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
        $iAcademicYearID = $academic_year->id ?? 0; 
        if (($_GET['debug_mode'] ?? "") || ($_SESSION['debug_mode'] ?? ""))
        {
            $_SESSION['debug_mode'] = true;
            $school->academic_year_id = 1468;
        }
         $totalRecords = $this->member->get_transport_member_list_total($is_transport_member = 1, $school_id,@$school->academic_year_id,$search_text);
        $non_members = $this->member->get_transport_member_list($is_transport_member = 1, $school_id, @$school->academic_year_id,$limit,$start,$search_text);
        $condition = array();
        $condition['school_id'] = $school_id;   
       
        $sections = $this->member->get_list('sections', $condition);
        $section_list = [];
        foreach($sections as $section) {
            $section_list[$section->id] = $section->name;
        }
        $route_stops = $this->member->get_list('route_stops', $condition);
        $stop_list = [];
        foreach($route_stops as $route_stop) {
            $stop_list[$route_stop->id] = $route_stop;
        }
        $condition['status'] = 1;        
        $routes = $this->member->get_list('routes', $condition);

        $route_list = [];
        foreach($routes as $route) {
            $route_list[$route->id] = $route->title;
        }
      }
      else
      {
        $totalRecords = 0;
        $non_members = array();
      }
       $count = 1; 
       $data = array();

       if(isset($non_members) && !empty($non_members)){
               foreach($non_members as $obj){
                $row_data = array();
                   if($obj->photo != ''){ 
                    $member_photo  = '<img src='.UPLOAD_PATH.'/student-photo/'.$obj->photo.'" alt="" width="70" /> ';
                    }else{ 
                        $member_photo  = '<img src="'.IMG_URL.'default-user.png" alt="" width="70" /> ';
                    } 
                    $action = "";
                    if(has_permission(DELETE, 'hostel', 'member')){
                        $action  = '<a href="'.site_url('transport/member/delete/'.$obj->tm_id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line('delete').'</a>';
                    }
                    $row_data[] = '<input type="checkbox" class="transport_member" name="members[]" value="'.$obj->tm_id.'"> '.$count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $school->school_name;
                    }	 
                    $stop_details = $stop_list[$obj->route_stop_id] ?? '';
                    $yearly_stop_fare = 0;
                    $stop_fare = 0;
                    if($stop_details) {
                        $yearly_stop_fare = $stop_details->yearly_stop_fare ? json_decode($stop_details->yearly_stop_fare,true) : array();
                        $stop_fare =  $stop_details->stop_fare;
                        if($iAcademicYearID && isset($yearly_stop_fare[$iAcademicYearID]))
                        {
                            $stop_fare = $yearly_stop_fare[$iAcademicYearID];
                        }
                        $obj->stop_name = $stop_details->stop_name;
                        $obj->stop_km = $stop_details->stop_km;
                    }
                   
                   $row_data[] = $member_photo;
                   $row_data[] = $obj->admission_no;
                   $row_data[] = $obj->name;;
                   $row_data[] = $obj->father_name;
                   $row_data[] = $obj->class_name." ".$obj->class_id;
                   $row_data[] = $section_list[$obj->section_id] ?? "";
                   $row_data[] = $obj->roll_no;
                   $row_data[] = $route_list[$obj->route_id] ?? "";
                   $row_data[] = $obj->stop_name ?? "";
                   $row_data[] = $obj->stop_km ?? "";
                   $row_data[] =  $stop_fare;
                   $row_data[] = $action;
                    $data[] = $row_data;
                    $count++;
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
    /*****************Function get_list**********************************
    * @type            : Function
    * @function name   : get_list
    * @description     : Ajax list page                 
    *                       
    * @param           : null
    * @return          : boolean json/
    * ********************************************************** */
	public function get_non_members_list(){		
        // for super admin 
       $school_id = '';
       $start=null;
       $limit=null;
       $search_text='';
       if($_POST){            
           $school_id = $this->input->post('school_id');
           $start = $this->input->post('start');
           $limit  = $this->input->post('length');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
       }
       
        
       
      if($school_id){
        $school = $this->member->get_school_by_id($school_id);

         $totalRecords = $this->member->get_transport_non_member_list_total($is_transport_member = 0, $school_id,@$school->academic_year_id,$search_text);
        $non_members = $this->member->get_transport_non_member_list($is_transport_member = 0, $school_id, @$school->academic_year_id,$limit,$start,$search_text);
    }
    else
    {
      $totalRecords = 0;
      $non_members = array();
    }
    // echo '<pre>'; var_dump($totalRecords ); 
    // echo '<pre>'; var_dump(count(   $non_members )); die(); 
       $count = 1; 
       $data = array();
       $condition = array();
       $condition['status'] = 1;        
       $condition['school_id'] = $school_id;   
       $routes = $this->member->get_list('routes', $condition);
       $schools = get_school_list();

       if(isset($non_members) && !empty($non_members)){
               foreach($non_members as $obj){
                $row_data = array();
                   if($obj->photo != ''){ 
                    $member_photo  = '<img src='.UPLOAD_PATH.'/student-photo/'.$obj->photo.'" alt="" width="70" /> ';
                    }else{ 
                        $member_photo  = '<img src="'.IMG_URL.'default-user.png" alt="" width="70" /> ';
                    } 
                    $select = "";
                    if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ 
                        $select .= '<div class="col-md-12 col-sm-12 col-xs-12">                                                       
                            <select  class="form-control  fn_school_id" itemid="'.$obj->user_id.'" name="school_id" id="school_id_'.$obj->user_id.'" required="required" style="width:200px">
                                <option value="">--'.$this->lang->line('select').' '.$this->lang->line('school').'--</option>';
                                foreach($schools as $sc){ 
                                    $selected = isset($school_id) && $school_id == $sc->id ? 'selected="selected"' : "";
                                    $select .= '<option value="'.$sc->id.'" '.$selected.'>'.$sc->school_name.'</option>';
                                } 
                                $select .= '</select></div>';
                    }else{
                        $select .= '<input type="hidden" name="school_id" id="school_id_'.$obj->user_id.'" value="'.$this->session->userdata('school_id').'" />';
                    }
                         
                    $select .= '<div class="col-md-12 col-sm-12 col-xs-12"> 
                        <select  class="form-control route_select_box" data-userid="'.$obj->user_id.'" name="route_id" id="route_id_'.$obj->user_id.'"   required="required">
                            <option value="">--'.$this->lang->line('select').' '.$this->lang->line('transport_route').'--</option>';
                            if(isset($routes) && !empty($routes)){ 
                                foreach($routes as $route){
                                    $select .= ' <option value="'.$route->id.'">'.$route->title.' ['.get_vehicle_by_ids($route->vehicle_ids).']</option>';
                                }
                            }
                            $select .= '</select>
                                        <select  class="form-control col-md-7 col-xs-12" name="stop_id" id="stop_id_'.$obj->user_id.'" required="required">
                                            <option value="">--'.$this->lang->line('select').' '.$this->lang->line('bus_stop').'--</option>                                                    
                                        </select>
                                    </div>';
                    $action = "";
                    if(has_permission(ADD, 'transport', 'member')){
                        $action  = '<a href="javascript:void(0);" id="'.$obj->user_id.'" class="btn btn-success btn-xs fn_add_to_transport"><i class="fa fa-reply"></i>'.$this->lang->line('add').' '.$this->lang->line('transport').' </a>';
                    }
                $row_data[] = '<input type="checkbox" class="transport_non_member" name="members[]" value="'.$obj->user_id.'"> '.$count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $school->school_name;
                    }	 
                   $row_data[] = $member_photo;
                   $row_data[] = $obj->admission_no;
                   $row_data[] = $obj->name;;   
                   $row_data[] = $obj->father_name;
                   $row_data[] = $obj->class_name;
                   $row_data[] = $obj->section;
                   $row_data[] = $obj->roll_no;
                   $row_data[] = $select;
                   $row_data[] = $action;
                   //var_dump($row_data);   
                    $data[] = $row_data;
                    $count++;
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
   
    /*****************Function add_to_transport**********************************
    * @type            : Function
    * @function name   : add_to_transport
    * @description     : Process to save Transport member info into database                  
    *                       
    * @param           : null
    * @return          : boolean true/flase 
    * ********************************************************** */
    public function add_to_transport() {

        $school_id = $this->input->post('school_id');
        $user_id = $this->input->post('user_id');
        $route_id = $this->input->post('route_id');
        $stop_id = $this->input->post('stop_id');

        if ($user_id) {
            $school = $this->member->get_school_by_id($school_id);
            $member = $this->member->get_transport_membership($user_id, @$school->academic_year_id, $school_id);
            if (empty($member)) {

                $data['school_id'] = $school_id;
                $data['user_id'] = $user_id;
                $data['route_id'] = $route_id;
                $data['route_stop_id'] = $stop_id;
                $data['academic_year_id'] = @$school->academic_year_id;

                $data['status'] = 1;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = logged_in_user_id();

                $insert_id = $this->member->insert('transport_members', $data);
                if($insert_id)
                {
                    $data['id'] = $insert_id;
                    $this->_send_message_notification($data);
                    $this->member->update('students', array('is_transport_member' => 1), array('user_id' => $user_id, 'school_id'=>$school_id));
                    echo TRUE;
                }
                else
                {
                    echo FALSE;
                }
                
            } else {
                echo FALSE;
            }
        } else {
            echo FALSE;
        }
    }
    
    
    public function add_transport_bulk() 
    {
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

        $response =  array();
        $updated_members = array();
        $error_members = array();
        $school_id = $this->input->post('school_id');
        $user_ids = $this->input->post('user_ids');
        $route_id = $this->input->post('route_id');
        $stop_id = $this->input->post('stop_id');
        if( !(has_permission(ADD, 'transport', 'member')) || empty($user_ids) || !$school_id  || !$route_id || !$stop_id){
            $response['success'] =  false;
            $response['updated_members'] =  $updated_members;
            $response['error_members'] =  $error_members;
            echo json_encode($response);
            die();
        }
        $response['success'] =  true;
        $school = $this->member->get_school_by_id($school_id);

        foreach($user_ids  as $user_id)
        {
            if ($user_id) {
                $member = $this->member->get_transport_membership($user_id, @$school->academic_year_id, $school_id);

                // $member = $this->member->get_single('transport_members', array('user_id' => $user_id, 'school_id'=>$school_id));
                if (empty($member)) {
                    $data = array();
                    $data['school_id'] = $school_id;
                    $data['user_id'] = $user_id;
                    $data['route_id'] = $route_id;
                    $data['route_stop_id'] = $stop_id;
                    $data['academic_year_id'] = @$school->academic_year_id;

                    $data['status'] = 1;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = logged_in_user_id();
                    $insert_id = $this->member->insert('transport_members', $data);
                    if($insert_id)
                    {
                        $data['id'] = $insert_id;
                        $this->member->update('students', array('is_transport_member' => 1), array('user_id' => $user_id, 'school_id'=>$school_id));
                        $updated_members[] = $user_id;
                    }
                    else
                    {
                        $error_members[] = $user_id;
                    }
                    
                } else {
                    $error_members[] = $user_id;
                }
            } else {
                $error_members[] = $user_id;
            }
        }
            $response['updated_members'] =  $updated_members;
            $response['error_members'] =  $error_members;
            echo json_encode($response);
            die();
    }
    /*****************Function get_route_by_school**********************************
     * @type            : Function
     * @function name   : get_route_by_school
     * @description     : Load "Route Listing" by ajax call                
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_route_by_school() {
        
        $school_id  = $this->input->post('school_id');
        $user_id  = $this->input->post('user_id');
         
        $routes = $this->member->get_list('routes', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if (!empty($routes)) {
            foreach ($routes as $obj) { 
                $vehicle = get_vehicle_by_ids($obj->vehicle_ids);
                $str .= '<option value="' . $obj->id . '" >' . $obj->title . '['.$vehicle.']</option>';                
            }
        }

        echo $str;
    }
    
              
    /*****************Function get_route_by_school**********************************
     * @type            : Function
     * @function name   : get_route_by_school
     * @description     : Load "Route Listing" by ajax call                
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    private function get_route_by_school_inside($user_id,$school_id) {
        
        $school_id  = $this->input->post('school_id');
        $user_id  = $this->input->post('user_id');
         
        $routes = $this->member->get_list('routes', array('status'=>1, 'school_id'=>$school_id), '','', '', 'id', 'ASC'); 
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if (!empty($routes)) {
            foreach ($routes as $obj) { 
                $vehicle = get_vehicle_by_ids($obj->vehicle_ids);
                $str .= '<option value="' . $obj->id . '" >' . $obj->title . '['.$vehicle.']</option>';                
            }
        }

        echo $str;
    }
    
    
        
    /**     * *************Function get_bus_stop_by_route**********************************
     * @type            : Function
     * @function name   : get_bus_stop_by_route
     * @description     : this function used to populate bus stop list by route  
      for user interface
     * @param           : null 
     * @return          : $str string value with room list 
     * ********************************************************** */
    public function get_bus_stop_by_route() {

        $school_id = $this->input->post('school_id');
        $route_id = $this->input->post('route_id');

         $school = $this->member->get_school_by_id($school_id);
         $academic_year=$this->member->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
         $iAcademicYearID = $academic_year->id ?? 0; 

        $stops = $this->member->get_list('route_stops', array('status' => 1, 'route_id' => $route_id, 'school_id'=>$school_id), '', '', '', 'id', 'ASC');
      //  echo $this->db->last_query();
        $str = '<option value="">-- ' . $this->lang->line('select') . ' ' . $this->lang->line('bus_stop') . ' --</option>';
        $selected = '';
        if (!empty($stops)) {
            foreach ($stops as $obj) {
                $yearly_stop_fare = $obj->yearly_stop_fare ? json_decode($obj->yearly_stop_fare,true) : array();
                $stop_fare =  $obj->stop_fare;
                if($iAcademicYearID && isset($yearly_stop_fare[$iAcademicYearID]))
                {
                    $stop_fare = $yearly_stop_fare[$iAcademicYearID];
                }
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->stop_name . ' [' . $school->currency_symbol .$stop_fare . ']</option>';
            }
        }

        echo $str;
    }
    /*****************Function _send_message_notification**********************************
    * @type            : Function
    * @function name   : _send_message_notification
    * @description     : Process to send in app message to the users                  
    *                    
    * @param           : $data array() value
    * @return          : null 
    * ********************************************************** */
    public function _send_message_notification($message_data = null) {
 
        $data                       = array("");
        $data['school_id']          = $message_data['school_id'];

        $route                      = $this->member->get_single_route_details($message_data['id']);  
        $vehicle                    = get_vehicle_by_ids($route->vehicle_ids);
        $school                     = $this->member->get_school_by_id($data['school_id']);
        $data['academic_year_id']   =  $school->academic_year_id;
        $data['subject']            = $this->lang->line('transport_assigned');   
        $data['sender_id']          = logged_in_user_id();
        $data['sender_role_id']     = $this->session->userdata('role_id');
            if($route->user_id != ''){                    
                $message = $this->lang->line('hi'). ' '. $route->name.',';
                $message .= '<br/>';
                $message .= $this->lang->line('following_is_your_transport_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('route_name').': ' .$route->route_name;
                $message .= '<br/>';
                $message .= $this->lang->line('vehicle').': ' .$vehicle;
                $message .= '<br/>';
                $message .= $this->lang->line('stop_name').': ' .$route->stop_name;
                $message .= '<br/>';
                $message .= $this->lang->line('stop_km').': ' .$route->stop_km;
                $message .= '<br/>';
                $message .= $this->lang->line('stop_fare').': ' .$route->stop_fare;
                $message .= '<br/>';
                $message .= $this->lang->line('thank_you').'<br/>';
                $data['body'] = $message;
                $data['receiver_id'] = $route->user_id;
                $data['receiver_role_id'] = STUDENT;
                $this->message->send_message($data);
            }
            // guardian messsage
            if($route->g_user_id != ''){ 
                $message = $this->lang->line('hi'). ' '. $route->g_name.',';
                $message .= '<br/>';
                $message .= $this->lang->line('following_is_your_child_transport_details');
                $message .= '<br/><br/>';
                $message .= $this->lang->line('student_name').': ' .$route->name;
                $message .= '<br/>';
                $message .= $this->lang->line('vehicle').': ' .$vehicle;
                $message .= '<br/>';
                $message .= $this->lang->line('route_name').': ' .$route->route_name;
                $message .= '<br/>';
                $message .= $this->lang->line('stop_name').': ' .$route->stop_name;
                $message .= '<br/>';
                $message .= $this->lang->line('stop_km').': ' .$route->stop_km;
                $message .= '<br/>';
                $message .= $this->lang->line('stop_fare').': ' .$route->stop_fare;
                $message .= '<br/>';
                $message .= $this->lang->line('thank_you').'<br/>';

                $data['body'] = $message;
                $data['receiver_id'] = $route->g_user_id;
                $data['receiver_role_id'] = GUARDIAN;
               $this->message->send_message($data);
            }
    }  
    

}
