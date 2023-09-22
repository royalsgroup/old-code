<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Material.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Material
 * @description     : Manage academic material for each class as per school course curriculam.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Debugger extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();     
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            redirect('dashboard//index');
        }

    }
    public function index()
    {
        
        $date_formats = array("6-1-2017","06-1-2017","06-01-2017","06-1-17","06-01-17","2017-01-06","2017-1-06","2017-01-6", "06-1-17", "6-01-17" ,"06-01-17", "17-1-6", "17-01-6" ,"17-01-06");
        foreach($date_formats as $date)
        {
            echo $date;
            $date =  date_format_converter($date);
            echo " -> ";
            echo date('Y-m-d',strtotime($date));
            echo "<br><br><br>";
        }
        
       
        die("dsds");
    }
   
}