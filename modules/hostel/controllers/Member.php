<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Member.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Member
 * @description     : Manage hostel member from the student whose are resident in the hostel.  
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
        
    }

    
       
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Hostel Hostel List" user interface                 
    *                      
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {
        check_permission(VIEW);
        if($school_id==null && $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
              $school_id = $this->session->userdata('school_id');
              
        }
        
       // $this->data['members'] = $this->member->get_hostel_member_list($is_hostel_member = 1, $school_id);       
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('hostel') . ' ' . $this->lang->line('member') . ' | ' . SMS);
        $this->layout->view('member/member', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Member" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add($school_id = null) {

        check_permission(ADD);        
      if($school_id==null && $this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
		  $school_id = $this->session->userdata('school_id');
		  
	  }
	  
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        //$this->data['non_members'] = $this->member->get_hostel_member_list($is_hostel_member = 0, $school_id);  
             
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('hostel') . ' ' . $this->lang->line('non_member') . ' | ' . SMS);
        $this->layout->view('member/non_member', $this->data);
    }

    
        
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Student" data from hostel member list                   
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {
        error_on();
        check_permission(DELETE);
        $error = 0;
        $member = $this->member->get_hostel_member($id);
        $school_id = @$member->school_id;
        $academic_year=$this->member->get_single('academic_years',array('school_id'=>$school_id,'is_running'=>1));	
        $academic_year_id = $academic_year->previous_academic_year_id ?? 0; 
        $error =0;
        // if($academic_year_id)
        // {
        //     $amount = 0;
        //     $income_head =  $this->member->get_income_heads($school_id, $academic_year_id, 'hostel'); 
        //     $fee = $this->member->get_hostel_fee(@$member->student_id);
        //     if (!empty($fee)) {
        //         $yearly_stop_fares = $fee->yearly_room_rent ? json_decode($fee->yearly_room_rent,true) : array();
        //         if(isset( $yearly_room_rent[$data['previous_academic_year_id']]))
        //         {
        //             $amount = $yearly_room_rent[$data['previous_academic_year_id']];
        //         }
        //         else
        //         {
        //             $amount = $fee->cost;
        //         }
        //     }
        //     $paid_amount=$this->member->get_paid_fee_amount( null,@$member->student_id,$income_head->id,$academic_year_id,null);
        //     if(($amount - $paid_amount) >0)
        //     {
        //        $error = 1;
        //     }
        // }
        if(empty($member))
        {
           $error = 1;

        }
        if($error ==1)
        {
            error('Cant delete , Please check');
        }
        else if (!$member->academic_year_id) {
            $year_list = $this->member->get_list('academic_years', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
            $iUpdated = 0;
            $ainsertBatch = array();
            unset($member->student_id);
            foreach($year_list as $year) {
                if ( $year->id != $academic_year->id) {
                    if(!$iUpdated) {
                        $this->member->update('hostel_members', array('academic_year_id' => $year->id, "modified_at"=>date('Y-m-d H:i:s')), array('id' => $member->id));
                        $iUpdated = 1;
                    } else {
                        $ainsertBatch[] = array("school_id"=>$school_id
                                                ,"user_id"=>$member->user_id
                                                ,"hostel_id"=>$member->hostel_id
                                                ,"room_id"=>$member->room_id
                                                ,"status"=>$member->status
                                                ,"academic_year_id"=>$year->id
                                                ,"created_at"=>date('Y-m-d H:i:s')
                                        );
                    }
                }
            }
            $this->db->insert_batch("hostel_members",$ainsertBatch);
            success($this->lang->line('delete_success'));
        }
        else if ($this->member->delete('hostel_members', array('id' => $id))) {

            // $this->member->update('students', array('is_hostel_member' => 0), array('user_id' => $member->user_id));

            $student = $this->member->get_single('students', array('user_id' => $member->user_id));
            create_log('Has been deleted a Hostel Member : '.$student->name);
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('hostel/member/index/'.$member->school_id);
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
         $totalRecords = $this->member->get_hostel_member_list_total($is_hostel_member = 1, $school_id,@$school->academic_year_id,$search_text);
         
        $members = $this->member->get_hostel_member_list($is_hostel_member = 1, $school_id, @$school->academic_year_id,$limit,$start,$search_text);
       }
       else
       {
            $totalRecords =0;
            $members =  array();
       }
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();

       if(isset($members) && !empty($members)){
               foreach($members as $obj){
                $row_data = array();
                   if($obj->photo != ''){ 
                    $member_photo  = '<img src='.UPLOAD_PATH.'/student-photo/'.$obj->photo.'" alt="" width="70" /> ';
                    }else{ 
                        $member_photo  = '<img src="'.IMG_URL.'default-user.png" alt="" width="70" /> ';
                    } 
                    $action = "";
                    if(has_permission(DELETE, 'hostel', 'member')){
                        $action  = ' <a href="'.site_url('hostel/member/delete/'.$obj->hm_id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line('delete').' </a>';
                    }
                    $row_data[] = '<input type="checkbox" class="hostel_member" name="members[]" value="'.$obj->hm_id.'"> '.$count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                    $yearly_room_rent = $obj->yearly_room_rent ? json_decode($obj->yearly_room_rent,true) : array();
                    $room_cost =  $obj->cost;
                    if($iAcademicYearID && isset($yearly_room_rent[$iAcademicYearID]))
                    {
                        $room_cost = $yearly_room_rent[$iAcademicYearID];
                    }
                    $row_data[] = $member_photo;
                    $row_data[] = $obj->admission_no;
                   $row_data[] = $obj->name;;
                   $row_data[] = $obj->father_name;

                   $row_data[] = $obj->class_name;
                   $row_data[] = $obj->section;
                   $row_data[] = $obj->roll_no;
                   $row_data[] = $obj->hostel_name;
                   $row_data[] = $obj->room_no." [".$this->lang->line($obj->room_type)."]";
                   $row_data[] = $room_cost;
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
       if($school_id)
       {
    // if($school_id){
        $school = $this->member->get_school_by_id($school_id);
        $is_hostel_member = 0;
        $totalRecords = $this->member->get_hostel_member_list_total($is_hostel_member, $school_id, @$school->academic_year_id,$search_text);
        $members = $this->member->get_hostel_member_list($is_hostel_member, $school_id, @$school->academic_year_id,$limit,$start,$search_text);
       }
       else
       {
         $members = array();
            $totalRecords = 0;
       }

       
     
   
       $count = 1; 
       $data = array();

       if(isset($members) && !empty($members)){
               foreach($members as $obj){
                $row_data = array();
                   if($obj->photo != ''){ 
                    $member_photo  = '<img src='.UPLOAD_PATH.'/student-photo/'.$obj->photo.'" alt="" width="70" /> ';
                    }else{ 
                        $member_photo  = '<img src="'.IMG_URL.'default-user.png" alt="" width="70" /> ';
                    } 
                    $action = "";
                    if(has_permission(ADD, 'hostel', 'member')){
                        $action  = ' <a href="javascript:void(0);" id="'.$obj->user_id.'" class="btn btn-success btn-xs fn_add_to_hostel"><i class="fa fa-reply"></i>'.$this->lang->line('add').' '.$this->lang->line('hostel').
                        '</a>';
                    }
                    $select = "";
                    $select .='<div class="col-md-12 col-xs-12" >                                                     
                            <input type="hidden" name="school_id" id="school_id_'.$obj->user_id.'" value="'.$obj->school_id.'" />
                            </div>
                            <div class="col-md-12 col-xs-12" >
                                <select  class="form-control col-md-7 col-xs-12 alignleft hostel_select_box" name="hostel_id" data-userid="'.$obj->user_id.'" id="hostel_id_'.$obj->user_id.'" required="required">
                                    <option value="">--'.$this->lang->line('select').' '.$this->lang->line('hostel').'-</option>';
                                        $hostels = get_hostel_by_school($obj->school_id);
                                        if(isset($hostels) && !empty($hostels)){
                                            foreach($hostels as $hostel){ 
                                                $select .=' <option value="'.$hostel->id.'">'.$hostel->name.' ['.$this->lang->line($hostel->type).']</option>';
                                            }
                                        }
                    $select .='</select>
                            </div>
                            <div class="col-md-12 col-xs-12" >
                                <select  class="form-control col-md-7 col-xs-12" name="room_id" id="room_id_'.$obj->user_id.'" required="required">
                                    <option value="">--'.$this->lang->line('select').' '.$this->lang->line('room_no').'--</option>                                                    
                                </select>
                            </div>';
                    $row_data[] =   '<input type="checkbox" class="hostel_non_member" name="members[]" value="'.$obj->user_id.'"> '.$count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
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
    /*****************Function add_to_hostel**********************************
    * @type            : Function
    * @function name   : add_to_hostel
    * @description     : Add student to Hostel via ajax call from user interface                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function add_to_hostel() {
       
        $school_id = $this->input->post('school_id');
        $user_id = $this->input->post('user_id');
        $hostel_id = $this->input->post('hostel_id');
        $room_id = $this->input->post('room_id');

        if ($user_id) {
            $school = $this->member->get_school_by_id($school_id);

            $member = $this->member->check_hostel_member($user_id, $school_id, @$school->academic_year_id);
            if (empty($member)) {

                $data['school_id'] = $school_id;
                $data['user_id'] = $user_id;
                $data['custom_member_id'] = $this->member->get_custom_id('hostel_members', 'HM');
                $data['hostel_id'] = $hostel_id;
                $data['room_id'] = $room_id;
                $data['status'] = 1;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = logged_in_user_id();
                $data['academic_year_id'] = @$school->academic_year_id;


                $insert_id = $this->member->insert('hostel_members', $data);
                $this->member->update('students', array('is_hostel_member' => 1), array('user_id' => $user_id, 'school_id'=>$school_id));
                echo TRUE;
            } else {

                echo FALSE;
            }
        } else {

            echo FALSE;
        }
    }
    
    public function add_to_hostel_bulk() 
    {
        $response =  array();
        $updated_members = array();
        $error_members = array();
        $school_id = $this->input->post('school_id');
        $user_ids = $this->input->post('user_ids');
        $hostel_id = $this->input->post('hostel_id');
        $room_id = $this->input->post('room_id');
        if( !(has_permission(ADD, 'hostel', 'member')) || empty($user_ids) || !$school_id  || !$hostel_id || !$room_id){
            $response['success'] =  false;
            $response['updated_members'] =  $updated_members;
            $response['error_members'] =  $error_members;
            echo json_encode($response);
            die();
        }
    
        $response['success'] =  true;

        foreach($user_ids  as $user_id)
        {
            if ($user_id) {
                $school = $this->member->get_school_by_id($school_id);

                $member = $this->member->get_hostel_membership($user_id, @$school->academic_year_id, $school_id);
                if (empty($member)) {

                    $data['school_id'] = $school_id;
                    $data['user_id'] = $user_id;
                    $data['custom_member_id'] = $this->member->get_custom_id('hostel_members', 'HM');
                    $data['hostel_id'] = $hostel_id;
                    $data['room_id'] = $room_id;
                    $data['status'] = 1;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = logged_in_user_id();
                    $data['academic_year_id'] = @$school->academic_year_id;

                    $insert_id = $this->member->insert('hostel_members', $data);
                    $this->member->update('students', array('is_hostel_member' => 1), array('user_id' => $user_id, 'school_id'=>$school_id));
                    $updated_members[] = $member_id;
                } else {

                    $error_members[] = $member_id;
                }
            } else {

                $response['success'] =  false;
            }
        }
            $response['updated_members'] =  $updated_members;
            $response['error_members'] =  $error_members;
            echo json_encode($response);
            die();
    }
    public function remove_bulk() {
        $response =  array();
        $updated_members = array();
        $error_members = array();
        $member_ids = $this->input->post('member_ids');        
        if( !(has_permission(DELETE, 'hostel', 'member'))){
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
                $member = $this->member->get_hostel_member($member_id);
                $school_id = @$member->school_id;
                if (!$member->academic_year_id) {
                    $year_list = $this->member->get_list('academic_years', array('school_id'=>$school_id), '','', '', 'id', 'ASC'); 
                    $iUpdated = 0;
                    $ainsertBatch = array();
                    unset($member->student_id);
                    foreach($year_list as $year) {
                        if ( $year->id != $academic_year->id) {
                            if(!$iUpdated) {
                                $this->member->update('hostel_members', array('academic_year_id' => $year->id, "modified_at"=>date('Y-m-d H:i:s')), array('id' => $member->id));
                                $iUpdated = 1;
                            } else {
                                $ainsertBatch[] = array("school_id"=>$school_id
                                                        ,"user_id"=>$member->user_id
                                                        ,"hostel_id"=>$member->hostel_id
                                                        ,"room_id"=>$member->room_id
                                                        ,"status"=>$member->status
                                                        ,"academic_year_id"=>$year->id
                                                        ,"created_at"=>date('Y-m-d H:i:s')
                                                );
                            }
                        }
                    }
                    $this->db->insert_batch("hostel_members",$ainsertBatch);
                    success($this->lang->line('delete_success'));
                }
                else if ($this->member->delete('hostel_members', array('id' => $member_id))) {
                    $this->member->update('students', array('is_hostel_member' => 0), array('user_id' => $member->user_id));
                    $student = $this->member->get_single('students', array('user_id' => $member->user_id));
                    create_log('Has been deleted a Hostel Member : '.$student->name);
                    $updated_members[] = $member_id;
                } else {
                    $error_members[] = $member_id;
                }
            }
           
        } else {
            $response['success'] =  false;
        }
        $response['updated_members'] =  $updated_members;
        $response['error_members'] =  $error_members;
        echo json_encode($response);
        die();
    }

}
