<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class School_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
    function duplicate_check($school_name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('school_name', $school_name);
        return $this->db->get('schools')->num_rows();            
    }
	function generate_school_code($sankul_id){
		$this->db->select('Sankul.*,S.name as state,Z.name as zone,SZ.name as subzone,D.name as district,B.name as block');
        $this->db->from('sankul AS Sankul'); 
		$this->db->join('blocks AS B', 'B.id = Sankul.block_id', 'left');			
		$this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');   
		$this->db->where('Sankul.id',$sankul_id);		
        $sankul=$this->db->get()->row();
		$state=strtoupper(substr($sankul->state,0,2));
		$zone=strtoupper(substr($sankul->zone,0,2));
		$subzone=strtoupper(substr($sankul->subzone,0,2));
		$district=strtoupper(substr($sankul->district,0,2));
		$block=strtoupper(substr($sankul->block,0,2));
		$sankul=strtoupper(substr($sankul->name,0,2));
		// get last 5 digit of school code and +1
		$this->db->select('S.*');
        $this->db->from('schools AS S'); 
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$school=$this->db->get()->row();
		//print_r($school); exit;
		if(!empty($school)){
			$code_no=(substr($school->school_code,strlen($school->school_code)-3))+1;
			$formated_code=sprintf("%03d", $code_no);
			$code=$state.$zone.$subzone.$district.$block.$sankul."-".$formated_code;
		}
		else{
			$code=$state.$zone.$subzone.$district.$block.$sankul."-001";
		}
		return $code;
	}
	/*function insert_default_data($school_id){
		if($school_id){
			//schooid - name - slug -is primary -typeid - base id -is readonly - created - modified			
			$date=date('Y-m-d H:i:s');
			$account_groups="insert into account_groups (school_id,name,slug,is_primary,type_id,base_id,is_readonly,created,modified) values";
			$account_groups.="('".$school_id."','Current Assets','current_assets','1','3','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Branch / Divisions','branch_divisions','1','4','0','1','".$date."','".$date."'),";			
			$account_groups.="('".$school_id."','Administrative Expenses','administrative_expenses','0','1','1','0','".$date."','".$date."'),";
			
			$account_groups.="('".$school_id."','Bank Accounts','bank_accounts','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Bank Accounts','bank_accounts','0','3','5','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Bank OD A/c','bank_od','0','4','6','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Capital Account','capital_account','1','4','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Cash-in-hand','cash_in_hand','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Current Liabilities','current_liabilities','1','4','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Deposits (Asset)','deposits','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Depriciation Expenses','depriciation_expenses','0','1','1','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Direct Expenses','direct_expenses','1','1','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Direct Incomes','direct_income','1','2','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Duties and Taxes','duties_and_taxes','0','4','4','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Employees Cost','employees_cost','0','1','1','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','FDR','fdr','0','3','5','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Current Assets','current_assets','1','3','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Financial Expenses','financial_expenses','0','1','1','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Fixed Assets','fixed_assets','1','3','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Indirect Expenses','indirect_expenses','1','1','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Indirect Incomes','indirect_incomes','1','2','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Investments','investments','1','3','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Karmchari Kalyan Kosh','karmchari_kalyan_kosh','0','4','4','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Land','land','0','3','8','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Loans (Liability)','loans','1','4','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Loans and Advances (Asset)','loans_and_advances','0','3','3','1','".$date."','".$date."'),";$account_groups.="('".$school_id."','Current Assets','current_assets','1','3','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Machinery','machinery','0','3','8','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Mess Expenses','mess_expenses','0','1','1','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Misc. Expenses (ASSET)','misc_expenses','1','3','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Promotional Expenses','promotional_expenses','0','1','1','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Provisions','provisions','0','4','4','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Purchase Accounts','purchase_accounts','1','1','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Reserves and Surplus','reserves_and_surplus','0','4','7','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Sales Accounts','sales_account','1','2','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Samiti Bank Accounts','samiti_bank_accounts','0','3','5','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','School Building','school_building','0','3','8','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','School Contra','school_Contra','0','4','9','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Secured Loans','secured_loans','0','4','6','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Security','security','0','4','4','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Security','security_assets','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Stock-in-hand','stock_in_hand','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Sundry Creditors','sundry_creditors','0','4','4','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Sundry Debtors','sundry_debtors','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Suspense A/c','suspense_ac','1','4','0','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','TDS','tds','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Unsecured Loans','unsecured_loans','0','4','6','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Vastu Bhandar','vastu_bhandar','0','3','3','1','".$date."','".$date."'),";
			$account_groups.="('".$school_id."','Vehicle','vehicle','0','3','8','1','".$date."','".$date."')";
			$this->db->query($account_groups);
			
			
			// add salary grade
			
			/*$salary_grade="insert into salary_grades (school_id,grade_name,status,created_at,modified_at,created_by,modified_by) values";
			$salary_grade.="('".$school_id."','Alumni','1','".$date."','".$date."',1,1)";
			$this->db->query($salary_grade);*/
			/*
		}
	}*/
	public function get_single_school($id){
		 $this->db->select('Schools.*');
        $this->db->from('schools AS Schools'); 
	/*	$this->db->join('sankul AS Sankul', 'Sankul.id = Schools.sankul_id', 'left');   
		$this->db->join('blocks AS B', 'B.id = Sankul.block_id', 'left');			
		$this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
		$this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');		
		$this->db->join('zone AS Z', 'Z.id = SZ.zone_id', 'left');
		$this->db->join('states AS S', 'S.id = Z.state_id', 'left');   
		*/
		$this->db->where('Schools.id',$id);	
		
        $res=$this->db->get()->row();		
		return $res;
	}
}
