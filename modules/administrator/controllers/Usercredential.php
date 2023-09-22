<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Credential.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Credential
 * @description     : Manage all type of systm users credential like student, employee, guardian and teacher.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Usercredential extends MY_Controller {

   public function __construct() {
        parent::__construct();
                
        $this->load->model('Administrator_Model', 'administrator', true);
        $this->data['roles'] = $this->administrator->get_list('roles', array('status' => 1, 'is_super_admin'=>0), '','', '', 'id', 'ASC');
        $this->data['classes'] = $this->administrator->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
    }

    public $data = array();

   

    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load user filtering interface                 
     *                      
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index(){
        
        
        check_permission(VIEW);
        
        $this->data['users'] = '';
        
         if ($_POST) {
             
            $role_id  = $this->input->post('role_id');
            $class_id = $this->input->post('class_id');            
            $user_id  = $this->input->post('user_id');  
            $school_id  = $this->input->post('school_id');  
            
            //$this->data['users'] = $this->administrator->get_user_list($school_id, $role_id, $class_id, $user_id);
            $this->data['role_id'] = $role_id;
            $this->data['class_id'] = $class_id;
            $this->data['user_id'] = $user_id;
            $this->data['school_id'] = $school_id;
         }         
        
        $this->layout->title($this->lang->line('user').' '.$this->lang->line('credential'). ' | ' . SMS);
        $this->layout->view('credential/index', $this->data); 
    }
     /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load user filtering interface                 
     *                      
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_list(){		
   
        // for super admin 
       $school_id = "";
       $user_id = "";
       $class_id = "";
       $role_id = "";
       $start=null;
       $limit=null;
       $search_text='';
       if($_POST){            
            $role_id  = $this->input->post('role_id');
            $class_id = $this->input->post('class_id');            
            $user_id  = $this->input->post('user_id');  
            $school_id  = $this->input->post('school_id');  
           $start = $this->input->post('start');
           $limit  = $this->input->post('length');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
     
       if($school_id && $role_id){
            $totalRecords = $this->administrator->get_user_list_total($school_id, $role_id, $class_id, $user_id,$search_text);
            $users = $this->administrator->get_user_list($school_id, $role_id, $class_id, $user_id,$start,$limit ,$search_text);
       }
       else
       {
            $totalRecords =0;
            $users =  array();
       }
       // echo $this->db->last_query();
       $count = 1; 
       $data = array();
       if(isset($users) && !empty($users)){
               foreach($users as $obj){

                $row_data = array();
                $path = '';
                $type = '';
                if($role_id == STUDENT){ $path = 'student'; $type = 'student'; }
                elseif($role_id == GUARDIAN){ $path = 'guardian'; $type = 'guardian';  }
                elseif($role_id == TEACHER){ $path = 'teacher'; $type = 'teacher';  }
                else{ $path = 'employee'; $type = 'employee';  }
                if ($obj->photo != '') {                                         
                  $user_photo =  '<img src="'.UPLOAD_PATH.$path.'-photo/'. $obj->photo.'" alt="" width="50" /> ';
                } else { 
                    $user_photo = ' <img src="'.IMG_URL.'default-user.png" alt="" width="50" /> ';
                } 
                //var_dump($user_photo);
                    $row_data[] = $count;
                    if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
                        $row_data[] = $obj->school_name;
                    }	 
                if($path == 'employee') { $path = 'hrm/employee';}
                $action = "<a  onclick=\"get_user_modal($obj->id,'$path', '$type')\"  data-toggle=\"modal\" data-target=\".bs-user-modal-lg\"  class=\"btn btn-success btn-xs\"><i class=\"fa fa-eye\"></i>".$this->lang->line('view')."</a><br/> ";                                                             
                
                   $row_data[] = $user_photo;
                   $row_data[] = ucfirst($obj->name);
                   $row_data[] = $obj->phone;
                   $row_data[] = $obj->email;

                   $row_data[] = $obj->username; 
                   $row_data[] = base64_decode($obj->temp_password);       
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

}
