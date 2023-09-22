
<?php
if(isset($_GET['showerror']) && $_GET['showerror'] == 1)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

}
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cron_job extends CI_Controller
{	
    
    public function financial_year_creation()
    {
        $this->load->model('Administrator/Financialyear_Model', 'financial_year', true);
        $financial_year_start = "01-04-";
        $financial_year_end = "31-03-";
        $current_year = date("Y");
        $current_month = date("m");
        if($current_month > 3)
        {
            $financial_year_start .= $current_year;
            $financial_year_end .= ($current_year+1);
        }
        else
        {
            $financial_year_start .= ($current_year-1);
            $financial_year_end .= $current_year;
        }
        $session_year   =  $financial_year_start .' -> '. $financial_year_end;
        $schools_with_financial_year = $this->financial_year->get_schools_with_financialyear($session_year);
        $existing_school_ids = array_column( $schools_with_financial_year ,'school_id');
        $schools = $this->financial_year->get_schools($existing_school_ids);
        $insert_data = [];
        if( !empty($schools))
        {
            foreach( $schools  as  $school)
            {
                $financial_year = array( 
                    'school_id'=>$school['id'],
                    'start_year'=> preg_replace('/\D/', '',$financial_year_start),
                    'end_year'=> preg_replace('/\D/', '',$financial_year_end),
                    'session_year'=>  $session_year,
                    "note"=>"auto generated",
                    "modified_at"=>date('Y-m-d H:i:s'),
                    "modified_by"=> logged_in_user_id(),
                    "is_running"=> 0,
                    "status"=> 1,
                    "created_at"=>date('Y-m-d H:i:s'),
                    "created_by"=> logged_in_user_id(),
                    "generated"=> 1,
                ); 
                $insert_data[] = $financial_year ;
            }
            if(!empty($insert_data))
            {
                $financial_years=$this->financial_year->insert_batch("financial_years",$insert_data);
                print_r($financial_years);
                echo "Data generated";
            }
            else
            {
                echo "No data to generate";
            }
        }
        else
        {
            echo "No data to generate";
        }
    }
}
