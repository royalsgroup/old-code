<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Disciplines extends MY_Controller
{	
    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Disciplines_Model', 'discipline', true);			
    }

     public function index($school_id = null) {
		check_permission(VIEW,false);

		$this->data['disciplines'] = $this->discipline->get_discipline_list($school_id);
		
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');                    
        }   
        
        $this->data['filter_school_id'] = $school_id;        
		$this->data['schools'] = $this->schools;	
		
        $this->data['themes'] = $this->discipline->get_list('themes', array(), '','', '', 'id', 'ASC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage')." ".$this->lang->line('discipline'). ' | ' . SMS);
        $this->layout->view('disciplines/index', $this->data);            
       
    }    


      	public function add() {
			check_permission(ADD);
		    if ($_POST) {

		      $data = $this->security->xss_clean($_POST);

		      $data1 = [

				      	'school_id' => $data['school_id'],
				      	'name' => $data['name']

		      		];

		  			$this->discipline->insert($data,'academic_disciplines');

		      		redirect('disciplines');


		  	}

    	} 


    	public function delete($id){
			check_permission(DELETE);

    		$this->discipline->delete($id,'academic_disciplines');

    		redirect('disciplines');
    	}   


    	public function edit($id){
			error_on();
			check_permission(EDIT);
			$school_id = 0;
			if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){            
				$school_id = $this->session->userdata('school_id');                    
			}   
			if ($_POST) {
				$this->_prepare_discipline_validation();
				if ($this->form_validation->run() === TRUE) {
					$data = $this->_get_posted_discipline_data();
					$updated = $this->discipline->update('academic_disciplines', $data, array('id' =>$id));
					if ($updated) {
												
						success($this->lang->line('update_success'));
						redirect('disciplines/index');
					} else {
						error($this->lang->line('update_failed'));
						redirect('disciplines/edit' .$id);
					}
				} else {               
					error($this->lang->line('update_failed'));
					redirect('disciplines/index');
				}
			}
    		 $discipline = $this->discipline->get_single('academic_disciplines', array("id"=>$id));
			 $this->data['discipline'] =$discipline;
			 $this->data['school_id'] = $discipline->school_id;
			 $this->data['edit'] = true;
			 $this->data['disciplines'] = $this->discipline->get_discipline_list($school_id);
			 $this->layout->title($this->lang->line('manage')." ".$this->lang->line('discipline'). ' | ' . SMS);
    		$this->layout->view('disciplines/index', $this->data); 
    	}   
		/*****************Function _prepare_book_validation**********************************
		* @type            : Function
		* @function name   : _prepare_book_validation
		* @description     : Process "book" user input data validation                 
		*                       
		* @param           : null
		* @return          : null 
		* ********************************************************** */
		private function _prepare_discipline_validation() {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

			$this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
			$this->form_validation->set_rules('name', $this->lang->line('book_id'), 'trim|required');
		}
		    /*****************Function _get_posted_discipline_data**********************************
		* @type            : Function
		* @function name   : _get_posted_discipline_data
		* @description     : Prepare "discipline" user input data to save into database                  
		*                       
		* @param           : null
		* @return          : $data array(); value 
		* ********************************************************** */
		private function _get_posted_discipline_data() {

			$items = array();
			$items[] = 'school_id';
			$items[] = 'name';		

			$data = elements($items, $_POST);

			if ($this->input->post('id')) {
				$data['modified_at'] = date('Y-m-d H:i:s');
				$data['modified_by'] = logged_in_user_id();
			} else {
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['created_by'] = logged_in_user_id();
			}
			return $data;
		}

}
