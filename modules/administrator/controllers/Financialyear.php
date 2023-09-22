<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Year.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Year
 * @description     : Manage academic year.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Financialyear extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Accountledgers_Model', 'accountledgers', true);			
		$this->load->model('Accountgroups_Model', 'accountgroups', true);	
         $this->load->model('Financialyear_Model', 'year', true);
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Academic Year List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {
        
        check_permission(VIEW);
        
        $this->data['years'] = $this->year->get_year_list($school_id);
        $financial_years = [];
        foreach($this->data['years'] as $year)
        {
            $financial_years[$year->id] = $year->session_year;
        }
        $this->data['financial_years'] = $financial_years;

        $this->data['schools'] = $this->schools;
        $this->data['filter_school_id'] = $school_id;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_financial_year'). ' | ' . SMS);
        $this->layout->view('financialyear/index', $this->data);            
       
    }

    
    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Academic Year" user interface                 
    *                    and store "Academic Year" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {
     
        
        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_year_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_year_data();
                // if($data['school_id'] !=71)
                // {
                //     error("Will be back soon");
                //     redirect('administrator/financialyear/add');
                // }
                $insert_id = $this->year->insert('financial_years', $data);
                
                if ($insert_id) {
                    $this->update_ledger_balaces( $insert_id,$data['start_year'],$data['school_id']);

                    create_log('Has been created a financial Year : '.$data['session_year']); 
                    success($this->lang->line('insert_success'));
                    redirect('administrator/financialyear/index/'.$data['school_id']);
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/financialyear/add');
                }
            } else {
                $this->data = $_POST;
            }
        }
        

        $this->data['years'] = $this->year->get_year_list();
        $financial_years = [];
        foreach($this->data['years'] as $year)
        {
            $financial_years[$year->id] = $year->session_year;
        }
        $this->data['financial_years'] = $financial_years;
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('financial_year'). ' | ' . SMS);
        $this->layout->view('financialyear/index', $this->data);
    }
    public function update_ledger_balaces($finacial_year_id,$start_date,$school_id)
    {
        $start_date = strtotime($this->input->post('session_start'));
        $start_date= date("Y-m-d",strtotime('-1 day',$start_date));
        $assets=array();
        $final_assets=0;			  
         $agroup=$this->accountgroups->get_accountgroup_by_types($school_id);	
         
         $account_ledger_details = array();
        
         $school = $this->accountledgers->get_school_by_id($school_id);
         $final_liabilities=0;						 
        $capital_ledger = [];
        foreach($agroup as $ag){				

           // get ledgers
          
           $group_total=0;				
           $group_id=$ag->id;
           $ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id,$school->financial_year_id,$group_id);
           $j=0;
           //print_r($ledgers); exit;
           foreach($ledgers as $l){
               // get current balance	
               $in_arr=array();
               $in_arr['ledger_id']=$l->id;
               $in_arr['financial_year_id']=$finacial_year_id;
               if(in_array($ag->type_id,array(4,3)))
               {
                  $cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
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
                  if($ag->type_id == 4 && $ag->base_id == 7 && empty($capital_ledger ))
                  {
                    $l->opening_balance = $cbalance;
                    $capital_ledger = $l;
                  }

               }
               else
               {
                 $in_arr['opening_balance']=0;
               }
               $in_arr['opening_cr_dr']=$l->dr_cr;
              
               $account_ledger_details[] = $in_arr;
               $group_total+= $cbalance; 
           }
           if($ag->type_id  == 4)
           {
               $final_liabilities += $group_total;
           }
           else if($ag->type_id  == 3)
           {

               $final_assets += $group_total;	
           }
        }
    	


        if(!empty($account_ledger_details))
        {
            
            $ledgers=$this->accountledgers->insert_batch("account_ledger_details",$account_ledger_details);
        }
        // $retained=$this->get_retained_balance($school_id);
       
        // $this->data['expence_difference']=$retained['expence_difference'];
        // $this->data['income_difference']=$retained['income_difference'];
        // $final_assets +=$retained['income_difference'];
        // $final_liabilities+=$retained['expence_difference'];
        // $this->data['assets']=$assets;	
        // $liability_difference=0;
        // $asset_difference=0;
        // if($final_assets > $final_liabilities){
        //     if($final_liabilities <0){
        //         $liability_difference=$final_assets -abs($final_liabilities);
        //     }
        //     else{
        //         $liability_difference=$final_assets -$final_liabilities;
        //     }
        //     $this->data['final_amount']=$final_assets;				
        // }
        // else{	
        //     if($final_assets <0 ){
        //         $asset_difference = $final_liabilities -abs($final_assets);	
        //     }	
        //     else{				
        //         $asset_difference = $final_liabilities -$final_assets;				
        //     }
        // }
        // if(!empty( $capital_ledger))
        // {
        //     $updated_opening_balance = $capital_ledger->opening_balance;
        //     if($liability_difference >0 ){ 
        //         $updated_opening_balance =  $updated_opening_balance + $liability_difference;
        //     }
        //     if($asset_difference >0 ){ 
        //         $updated_opening_balance =  $updated_opening_balance - $asset_difference;
        //     }
        //     $cbalance=$this->accountledgers->update("account_ledger_details",array('opening_balance'=> $updated_opening_balance) ,array("ledger_id"=> $capital_ledger->id,"financial_year_id"=>$finacial_year_id));
        // }
    }
    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Academic Year" user interface                 
    *                    with populated "Academic Year" value 
    *                    and update "Academic Year" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {   
        
        check_permission(EDIT);
       
        if ($_POST) {
            $this->_prepare_year_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_year_data();
                $updated = $this->year->update('financial_years', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a financial Year : '.$data['session_year']); 
                    success($this->lang->line('update_success'));
                    redirect('administrator/financialyear/index/'.$data['school_id']);    
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('administrator/financialyear/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['year'] = $this->year->get_single('financial_years', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['year'] = $this->year->get_single('financial_years', array('id' => $id));
               
                if (!$this->data['year']) {
                     redirect('administrator/financialyear/index');
                }
                if(substr_count($this->data['year']->session_year,"->") > 0)
                {
                    $arr = explode('->', $this->data['year']->session_year);
                    $this->data['session_start'] = $arr[0];
                    $this->data['session_end'] = $arr[1];
                }
                else
                {
                    $arr = explode('-', $this->data['year']->session_year);
                    $this->data['session_start'] = $arr[0];
                    $this->data['session_end'] = $arr[1];
                }
               
            }
        }
        

        $this->data['school_id'] = $this->data['year']->school_id;
        $this->data['previous_financial_year_id'] = $this->data['year']->previous_financial_year_id;

        $this->data['years'] = $this->year->get_year_list();
        $financial_years = [];
        foreach($this->data['years'] as $year)
        {
            $financial_years[$year->id] = $year->session_year;
        }
        $this->data['financial_years'] = $financial_years;
        $this->data['schools'] = $this->schools;
        $this->data['filter_school_id'] = $this->data['year']->school_id;
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('financial_year'). ' | ' . SMS);
        $this->layout->view('financialyear/index', $this->data);
    }

    
    /*****************Function _prepare_year_validation**********************************
    * @type            : Function
    * @function name   : _prepare_year_validation
    * @description     : Process "Academic Year" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_year_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('session_year', $this->lang->line('academic_year'), 'trim|callback_session_year');
        $this->form_validation->set_rules('session_start', $this->lang->line('session_start'), 'trim|required');
        $this->form_validation->set_rules('session_end', $this->lang->line('session_end'), 'trim|required');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }

            
    /*****************Function session_year**********************************
    * @type            : Function
    * @function name   : session_year
    * @description     : Unique check for "academic year" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function session_year() {
        
        $session_year = $this->input->post('session_start') .' - '. $this->input->post('session_end');
        
        if(strtotime($this->input->post('session_start')) > strtotime($this->input->post('session_end'))){
            $this->form_validation->set_message('session_year', 'Invalid '. $this->lang->line('academic_year'));
            return FALSE;
        }
        
        if ($this->input->post('id') == '') {
            $year = $this->year->duplicate_check($session_year, $this->input->post('school_id'));
            if ($year) {
                $this->form_validation->set_message('session_year', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $year = $this->year->duplicate_check($session_year, $this->input->post('school_id'), $this->input->post('id'));
            if ($year) {
                $this->form_validation->set_message('session_year', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
    
    /*****************Function _get_posted_year_data**********************************
     * @type            : Function
     * @function name   : _get_posted_year_data
     * @description     : Prepare "Academic Year" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_year_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'note';
        $data = elements($items, $_POST);     
             
        $arr = explode('-', $data['session_year']);
        $data['start_year'] = preg_replace('/\D/', '', $this->input->post('session_start'));
        $data['end_year']   = preg_replace('/\D/', '', $this->input->post('session_end'));
        $data['session_year']   = $this->input->post('session_start') .' -> '. $this->input->post('session_end');
        $data['previous_financial_year_id']   = $this->input->post('previous_financial_year_id');
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['is_running'] = 0;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }

        return $data;
    }

    
    
    /*****************Function delete**********************************
   * @type            : Function
   * @function name   : delete
   * @description     : delete "Academic Year" from database                  
   *                       
   * @param           : $id integer value
   * @return          : null 
   * ********************************************************** */
    public function delete($id = null) {
        
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('administrator/financialyear');              
        }
        
        $academic_year = $this->year->get_single('financial_years', array('id' => $id));
        
        if ($this->year->delete('financial_years', array('id' => $id))) {  
            
            create_log('Has been deleted a financial Year : '.$academic_year->session_year); 
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('administrator/financialyear/index/'.$academic_year->school_id);
    }
    
     /*     * **************Function activate**********************************
     * @type            : Function
     * @function name   : activate
     * @description     : this function used to activate current session       *                                
     * @param           : $id integer value; 
     * @return          : null 
     * ********************************************************** */

    public function activate($id = null, $school_id = null) {

        check_permission(EDIT);

        if ($id == '' || $school_id == '') {
            error($this->lang->line('update_failed'));
            redirect('administrator/financialyear');
        }

        
        $this->year->update('financial_years', array('is_running' => 0), array('school_id'=>$school_id));
        $this->year->update('financial_years', array('is_running' => 1), array('id' => $id, 'school_id'=>$school_id));       
        
        $ay = $this->year->get_single('financial_years', array('id' => $id));
        $academic_year = $ay->start_year . ' - ' . $ay->end_year;
        $this->year->update('schools', array('financial_year_id' => $id), array('id' => $school_id));       
        
        $school = $this->year->get_single('schools', array('id' => $school_id));
       // create_log('Has been activated a academic Year : '.$academic_year.' for: '. $school->school_name);   
        
        success($this->lang->line('update_success'));
        redirect('administrator/financialyear');
    }
    
    
        
    /*****************Function get_session_by_school**********************************
     * @type            : Function
     * @function name   : get_session_by_school
     * @description     : Load "get_session_by_school" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_session_by_school() {
        
        $school_id  = $this->input->post('school_id');
        $session  = $this->input->post('session');
         
        $school = $this->year->get_single('schools',array('id'=>$school_id));
         
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
         
        if (!empty($school)) {
            for($i = date('Y')-10; $i< date('Y')+20; $i++){   
                 
                $session_year = '';                                            
                $session_year = $this->lang->line($school->session_start_month). ' ' . $i; 
                $session_year .= ' - '; 
                //$session_year .= $this->lang->line($school->session_end_month) .' '. ($i+1); 
                $session_year .= $this->lang->line($school->session_end_month) .' '. ($i); 
                
                $selected = $session == $session_year ? $select : '';
                
                $str .= '<option value="' . $session_year . '" ' . $selected . '>' . $session_year . '</option>';
                
            }
        }

        echo $str;
    }
    private	function get_retained_balance($school_id=null,$category=null){
		
		$i=0;
        $financial_year= $this->accountledgers->get_single('financial_years', array('school_id' => $school_id,'is_running'=>1));
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
		if($school_id != NULL){
			$school = $this->accountledgers->get_school_by_id($school_id);
			$result=array();
			 $final_expence=0;						
			 $egroup=$this->accountgroups->get_list('account_groups', array('school_id'=>$school_id,'type_id'=>1), '','', '', 'id', 'ASC');	
			  foreach($egroup as $ag){
				// get ledgers
				$result[$i]['account_group_id']=$ag->id;
				$result[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->academic_year_id,$group_id);
				$j=0;				
				foreach($ledgers as $l){
					// get current balance					
					if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date'])){
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null, $f_start, $f_end,$category);	
					}
					else{
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
					}				
					$cb=$cbalance;				
					if($cb>0){
						$ledgers[$j]->effective_balance=(-$cb);
					}
					else{
						$ledgers[$j]->effective_balance=abs($cb);
					}
					//$ledgers[$j]->effective_balance=$cb;
					$group_total+= $ledgers[$j]->effective_balance; 									
					
					$j++;
				}
				$result[$i]['ledgers']=$ledgers;
				$result[$i]['group_total']=$group_total;
				$final_expence += $group_total;
				$i++;				
			}  
			
				

				// DIRECT Incomestatement
				 $result=array();
				  $final_income=0;
				 $i=0;				
				 $egroup=$this->accountgroups->get_list('account_groups', array('school_id'=>$school_id,'type_id'=>2), '','', '', 'id', 'ASC');	
			  foreach($egroup as $ag){
				// get ledgers
				$result[$i]['account_group_id']=$ag->id;
				$result[$i]['account_group_name']=$ag->name;
				$group_total=0;				
				$group_id=$ag->id;
				$ledgers=$this->accountledgers->get_accountledgers_by_group( $school_id, $school->academic_year_id,$group_id);
				$j=0;
				//print_r($ledgers); exit;
				foreach($ledgers as $l){
					// get current balance
					if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date'])){
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null, $f_start, $f_end);					
					}
					else{
						$cbalance=$this->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr);
					}					
					$ledgers[$j]->effective_balance=$cbalance;
					$group_total+= $ledgers[$j]->effective_balance; 					
					$j++;
				}
				$result[$i]['ledgers']=$ledgers;
				$result[$i]['group_total']=$group_total;	
				$final_income += $group_total;				
					$i++;
			  }
			$this->data['incomes']=$result;	
			$expence_difference=0;
			$income_difference=0;
			if($final_income > $final_expence){
				$expence_difference=$final_income -$final_expence;						
			}
			else{				
				$income_difference = $final_expence -$final_income;								
			}
			$output=array();
			$output['expence_difference']=$expence_difference;
			$output['income_difference']=$income_difference;
			
	}
	return $output;
	}

    
       

}
