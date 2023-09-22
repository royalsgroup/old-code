<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Standards extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Standards_Model', 'standards', true);			
    }

     public function index($school_id = null) {
              
		$this->data['standards'] = $this->standards->get_standard_list($school_id);
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		
        $this->data['themes'] = $this->standards->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage')." ".$this->lang->line('standards'). ' | ' . SMS);
        $this->layout->view('standards/index', $this->data);            
       
    }    

}
