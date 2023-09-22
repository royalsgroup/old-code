<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {
    // CI Version: 3.1.5
    public $academic_year_id = '';
    public $schools = array();
    public $global_setting = array();
    public $school_setting = array();
    public $lang_path = 'application/language/english/sms_lang.php';
    public $config_path = 'application/config/custom.php';
    const  SMS = '';
    
    public function __construct() {
        parent::__construct();
        if (!logged_in_user_id()) {
            redirect('welcome');
            exit;
        }
        if(isset($_GET['showerror']) && $_GET['showerror'] == 1)
        {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

        }
        
        // Get all schools
		if($this->session->userdata('dadmin')==1){
			/*$state_id=$this->session->userdata('dadmin_state_id');
			$zone_id=$this->session->userdata('dadmin_zone_id');
			$subzone_id=$this->session->userdata('dadmin_subzone_id');
			$district_id=$this->session->userdata('dadmin_district_id');
			$block_id=$this->session->userdata('dadmin_block_id');
			$sankul_id=$this->session->userdata('dadmin_sankul_id');
			
			$this->db->select('S.*');
			$this->db->from('schools AS S');			
			$this->db->where('S.state_id', $state_id);
			if($zone_id >0){
			$this->db->where('S.zone_id', $zone_id);
			}
			if($subzone_id >0){
			$this->db->where('S.subzone_id', $subzone_id);
			}
			if($district_id >0){
			$this->db->where('S.district_id', $district_id);
			}
			if($block_id >0){
			$this->db->where('S.block_id', $block_id);
			}if($sankul_id >0){
			$this->db->where('S.sankul_id', $sankul_id);
			}
		 */

		 $school_ids=$this->session->userdata('dadmin_school_ids');		 
			$this->db->select('S.*');
			$this->db->from('schools AS S');			
			$this->db->where_in('S.id',$school_ids);
			$this->schools = $this->db->get()->result();			
			
		}
        else if($this->session->userdata('role_id') == DISTRICT_ADMIN){
					
			$this->schools = $this->db->get_where('schools', array('status'=>1,'district_id'=>$this->session->userdata('district_id')))->result();
		}
		else{
			$this->schools = $this->db->get_where('schools', array('status'=>1))->result();
		}		
        $this->global_setting = $this->db->get_where('global_setting', array('status'=>1))->row();       
        if($this->session->userdata('role_id') != SUPER_ADMIN){
            $this->school_setting = $this->db->get_where('schools', array('status'=>1, 'id'=>$this->session->userdata('school_id')))->row();
        }
        
        if($this->global_setting){           
            date_default_timezone_set($this->global_setting->time_zone);
        }
        
        $this->config->load('custom');
        
        header("HTTP/1.0 200 OK");
        header("HTTP/1.1 200 OK");
        header("Expires: Tue, 01 Jan 2020 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        clearstatcache();
        
       if($this->session->userdata('role_id') == SUPER_ADMIN && !empty($this->global_setting)){  
           
             $this->lang->load($this->global_setting->language);
             
        }else if($this->session->userdata('role_id') != SUPER_ADMIN && !empty($this->school_setting)){
            
            $this->lang->load($this->school_setting->language);
        }
               
        if($this->school_setting->enable_rtl){ 
            $this->enable_rtl = 1;
        }elseif($this->global_setting->enable_rtl){
            $this->enable_rtl = 1;
        }else{
            $this->enable_rtl = 0;
        } 
    }
    
    public function index(){
        
    }

        public function update_lang() {
        
        $data = array();
        $language = $this->db->get_where('global_setting', array('status'=>1))->row()->language; 
        $this->db->select("id, label, $language");
        $this->db->from('languages');        
        $this->db->order_by('id' , 'ASC');
        $languages = $this->db->get()->result(); 
        
        foreach($languages as $obj){
            $data[$obj->label] = $obj->$language;
        }        
        if (!is_array($data) && count($data) == 0) {
            return FALSE;
        }

        @chmod($this->lang_path, FILE_WRITE_MODE);

        // Is the config file writable?
        if (!is_really_writable($this->lang_path)) {
            show_error($this->lang_path . ' does not appear to have the proper file permissions.  Please make the file writeable.');
        } 
        // Read the config file as PHP
        require $this->lang_path;  

        // load the file helper
        $this->CI = & get_instance();
        $this->CI->load->helper('file');

        // Read the config data as a string
        //$lang_file = read_file($this->lang_path);
        // Trim it
        //$lang_file = trim($lang_file);

        $lang_file = '<?php ';

        // Do we need to add totally new items to the config file?
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                //$pattern = '/\$lang\[\\\'' . $key . '\\\'\]\s+=\s+[^\;]+/';  
                $lang_file .= "\n";
                //$lang_file .= "\$lang['$key'] = '".$val."';"; 
                $lang_file .= "\$lang['$key'] = ".'"'.$val.'";';    
                //$config_file = preg_replace($pattern, $replace, $config_file);
            }
        }
        
        if (!$fp = fopen($this->lang_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
            return FALSE;
        }
        
        flock($fp, LOCK_EX);
        fwrite($fp, $lang_file, strlen($lang_file));
        flock($fp, LOCK_UN);
        fclose($fp);

        
        @chmod($this->lang_path, FILE_READ_MODE);
  
        return TRUE;
    }
    
    public function update_config() {

        $data = array();

        $this->db->select('P.*, M.module_slug, O.operation_slug');
        $this->db->from('privileges AS P');
        $this->db->join('operations AS O', 'O.id = P.operation_id', 'left');
        $this->db->join('modules AS M', 'M.id = O.module_id', 'left');
        $results = $this->db->get()->result();


        foreach ($results as $obj) {
            // $data[][$obj->operation_slug][$obj->role_id] = $obj->is_add .'|'.$obj->is_edit.'|'.$obj->is_view.'|'.$obj->is_delete;
            $data[] = $obj;
        }
        if (!is_array($data) && count($data) == 0) {
            return FALSE;
        }

        @chmod($this->config_path, FILE_WRITE_MODE);

        // Is the config file writable?
        if (!is_really_writable($this->config_path)) {
            show_error($this->config_path . ' does not appear to have the proper file permissions.  Please make the file writeable.');
        }
        // Read the config file as PHP
        require $this->config_path;

        // load the file helper
        $this->CI = & get_instance();
        $this->CI->load->helper('file');

        // Read the config data as a string
        //$lang_file = read_file($this->lang_path);
        // Trim it
        //$lang_file = trim($lang_file);

        $config_file = '<?php ';

        // Do we need to add totally new items to the config file?
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                //$pattern = '/\$lang\[\\\'' . $key . '\\\'\]\s+=\s+[^\;]+/';  
                $config_file .= "\n";
                $config_file .= "\$config['my_$val->module_slug']['$val->operation_slug']['$val->role_id'] = '" . $val->is_add . "|" . $val->is_edit . "|" . $val->is_view . "|" . $val->is_delete . "';";
                //$config_file = preg_replace($pattern, $replace, $config_file);
            }
        }

        if (!$fp = fopen($this->config_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
            return FALSE;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $config_file, strlen($config_file));
        flock($fp, LOCK_UN);
        fclose($fp);


        @chmod($this->config_path, FILE_READ_MODE);

        return TRUE;
    }
 
    
}
