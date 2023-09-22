<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Subjects extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Subjects_Model', 'subjects', true);			
    }

     public function index($school_id = null) {
              
		$this->data['subjects'] = $this->subjects->get_subject_list($school_id);
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		
        $this->data['themes'] = $this->subjects->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage')." ".$this->lang->line('subjects'). ' | ' . SMS);
        $this->layout->view('subjects/index', $this->data);            
       
    }    

}
