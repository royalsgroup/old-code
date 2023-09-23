<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    public function get_academic_years($school_id)
    {
        $this->db->select("AY.id,AY.start_year curr_start,AY.session_year as curr_session_year ,AY.end_year as curr_end 
        ,(select id from academic_years where start_year<AY.start_year and id=AY.previous_academic_year_id order by start_year limit 1) as prev
        ,(select start_year from academic_years where start_year<AY.start_year and id=AY.previous_academic_year_id order by start_year limit 1) as prev_start_year
        ,(select session_year from academic_years where start_year<AY.start_year and id=AY.previous_academic_year_id order by start_year limit 1) as prev_session_year
        ,(select end_year from academic_years where start_year<AY.start_year and id=AY.previous_academic_year_id order by start_year limit 1) as prev_end_year
        ");
        $this->db->from(' schools AS S');
        $this->db->join('academic_years AS AY', 'AY.id = S.academic_year_id');        
        $this->db->where('S.id', $school_id);       
        return $this->db->get()->row();
    }
    public function get_faculties_used()
    {
        $this->db->select("AD.*");
        $this->db->from('academic_disciplines AS AD');
        $result =  $this->db->get();  
        
        return $result->result();  
    }
    public function get_account_types(){  
        $this->db->select('AT.id,AT.name');
        $this->db->from('account_types AS AT');       		
        return $this->db->get()->result();
        
    }
    public function get_section_data($filter_coloumn_filter = null,$filter_coloumn_value = null)
    {
        $filter_column_name = "E.class_id,E.section_id,(select name from classes where id=E.class_id) as class_name
        ,(select name from sections where id=E.section_id) as section_name
        ,(select fee_amount from fees_amount where class_id=E.class_id and school_id=E.school_id) as fee_amount," ;
    
        $this->db->select("$filter_column_name ");
        $this->db->from('invoices AS I');
        $this->db->join('students AS S', 'S.id = I.student_id');
        $this->db->join('enrollments AS E','S.id = E.student_id');        
        $this->db->join('classes AS C', 'C.id = E.class_id');

        $this->db->join('schools AS SC', 'SC.id = E.school_id');
        $this->db->join('fees_amount AS FA', 'FA.class_id = E.class_id');
        
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id');
      
        $this->db->where("(E.academic_year_id=SC.academic_year_id or AY.is_running=1)" ); 
        $this->db->where('I.reverted !=1');         

        $this->db->where('S.status_type', 'regular'); 
       if($filter_coloumn_filter && $filter_coloumn_value)
       {
           $this->db->where($filter_coloumn_filter, $filter_coloumn_value); 
       }
       $this->db->group_by("E.class_id,E.section_id"); 
       $this->db->order_by('E.academic_year_id');   

        $result =  $this->db->get();  
        echo $this->db->last_query();
        die();
        return $result->result();  
    }
    public function get_sections($school_id,$income_head_id , $type = "fee", $academic_year_id =0 )
    {
        if( $type == "fee")
        {
            $this->db->select("S.class_id,S.id,S.name as section_name,C.name as class_name,FA.fee_amount");
            $this->db->from('sections S');
            $this->db->join('classes AS C', 'C.id = S.class_id','left');
            $this->db->join('fees_amount AS FA', "FA.class_id = S.class_id ",'left');
            $this->db->where("C.status",1 ); 
            $this->db->where("S.school_id",$school_id ); 
            $this->db->where("C.school_id",$school_id ); 
           $this->db->where("FA.income_head_id",$income_head_id ); 
    
            $this->db->group_by("S.class_id,S.id"); 
            $result =  $this->db->get();  
            
            return $result->result();  
        }
        else if ( $type == "transport")
        {
            $this->db->select("S.class_id,S.id,S.name as section_name,C.name as class_name, RS.stop_fare as fee_amount, RS.yearly_stop_fare");
            $this->db->from('enrollments AS E');
            $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
            $this->db->join('sections AS S', 'C.id = S.class_id','left');
            $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
            $this->db->join('transport_members AS TM', 'TM.user_id = ST.user_id', 'left');
            $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');

            if($academic_year_id){
                $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
                $this->db->where('E.academic_year_id', $academic_year_id);
            }
            $this->db->where("TM.id >0");
            $this->db->where("C.school_id",$school_id ); 
            $this->db->group_by("S.class_id,S.id"); 
            $result =  $this->db->get();  
            return $result->result();  
        }
        else if ( $type == "hostel")
        {
            $this->db->select("S.class_id,S.id,S.name as section_name,C.name as class_name, R.cost as fee_amount, R.yearly_room_rent");
            $this->db->from('enrollments AS E');
            $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
            $this->db->join('sections AS S', 'C.id = S.class_id','left');
            $this->db->join('students AS ST', 'E.student_id = ST.id', 'left');
            $this->db->join('hostel_members AS HM', 'HM.user_id = ST.user_id', 'left');
            $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');

            if($academic_year_id){
                $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
                $this->db->where('E.academic_year_id', $academic_year_id);
            }
            $this->db->where("HM.id >0");
            $this->db->where("C.school_id",$school_id ); 
            $this->db->group_by("S.class_id,S.id"); 
            $result =  $this->db->get();  
            return $result->result();  
        }
     
          
     
      
    }
    public function get_section_students_count($section_ids,$class_ids,$school_id = "",$academic_year_id, $type = "fee")
    {
        $sRteQuery = "coalesce(E.rte,S.rte) as rte";
        if ( $type != "fee" )$sRteQuery = "'no' as rte";
        $this->db->select("count(*) as count,E.class_id,E.section_id,$sRteQuery");
        $this->db->from('enrollments E');
        $this->db->join('students AS S', 'S.id = E.student_id','left');
        $this->db->where("E.school_id",$school_id ); 
        $this->db->where_in("E.section_id",$section_ids ); 
        $this->db->where_in("E.class_id",$class_ids ); 
        if($academic_year_id)
        {
            $this->db->where_in("E.academic_year_id",$academic_year_id ); 
        }
        if ( $type == "transport")
        {
            $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
            $this->db->where("TM.id >0");
        }
        else if ( $type == "hostel")
        {
            $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
            $this->db->where("HM.id >0");
        }
        $this->db->where('S.status_type', 'regular'); 

        $this->db->group_by("E.class_id,E.section_id,coalesce(E.rte,S.rte)" ); 
        
        $result =  $this->db->get();  
       
        return $result->result();  
    }
    
    
    public function get_section_paid_students_count($section_ids,$class_ids,$school_id = "",$income_head_id, $academic_year_id =null)
    {
        $this->db->select("count(DISTINCT I.student_id) as count,E.class_id,E.section_id");
        $this->db->from('invoices I');
        $this->db->join('enrollments AS E', 'E.student_id = I.student_id','left');
        $this->db->where("E.school_id",$school_id ); 
        $this->db->where_in("E.section_id",$section_ids ); 
        $this->db->where_in("E.class_id",$class_ids ); 
        $this->db->where("I.school_id",$school_id ); 
        $this->db->where("I.paid_status","paid" ); 
        $this->db->where("I.income_head_id",$income_head_id ); 
        $this->db->where("I.net_amount>0"); 
        $this->db->where('I.reverted !=1');         
        $this->db->group_by("E.class_id,E.section_id" ); 
        if($academic_year_id)
        {
            $this->db->where_in("E.academic_year_id",$academic_year_id ); 
        }
        $result =  $this->db->get();  
      
        return $result->result();  
    }
    public function get_section_paid_students_count2($section_ids,$class_ids,$school_id = "",$income_head_id)
    {
        $this->db->select("count(DISTINCT I.student_id) as count,E.class_id,E.section_id,( select `fee_amount` from fees_amount where `class_id` = `E`.`class_id` AND school_id = $school_id 
        and fees_amount.income_head_id =$income_head_id) limit 1
        ) as fee_amount,(SUM(I.discount)+SUM(I.net_amount)) total_paid");
        $this->db->from('invoices I');
        $this->db->join('enrollments AS E', 'E.student_id = I.student_id','left');
        $this->db->where("E.school_id",$school_id ); 
        $this->db->where_in("E.section_id",$section_ids ); 
        $this->db->where_in("E.class_id",$class_ids ); 
        $this->db->where("I.school_id",$school_id ); 
        $this->db->where('I.reverted !=1');         

        $this->db->where("I.paid_status","paid" ); 
        $this->db->where("I.net_amount>0"); 
        $this->db->where("I.income_head_id = $income_head_id)" ); 
        $this->db->where(" I.student_id not in (select id from students where school_id=$school_id and (lower(rte)='yes' or status_type!='regular'))"); 
        $this->db->where(" total_paid < fee_amount"); 

        $this->db->group_by("E.class_id,E.section_id" ); 
        $result =  $this->db->get();  
    //    echo $this->db->last_query();
    //     die();
        return $result->result();  
    }
    public function get_section_paid_students_count1($section_ids,$class_ids,$school_id = "", $income_head_id, $academic_year_id =null)
    {
        $this->db->select("count(DISTINCT I.student_id) as count,E.class_id,E.section_id");
        $this->db->from('invoices I');
        $this->db->join('enrollments AS E', 'E.student_id = I.student_id','left');
        $this->db->where("E.school_id",$school_id ); 
        $this->db->where_in("E.section_id",$section_ids ); 
        $this->db->where_in("E.class_id",$class_ids ); 
        $this->db->where("I.school_id",$school_id ); 
        $this->db->where("I.school_id",$school_id ); 
        $this->db->where("I.paid_status","paid" ); 
        $this->db->where("I.net_amount>0"); 
        $this->db->where('I.reverted !=1');         

        $this->db->where("I.income_head_id", $income_head_id ); 
        $this->db->where(" I.student_id not in (select id from students where school_id=$school_id and (lower(rte)='yes' or status_type!='regular'))"); 

        $this->db->group_by("E.class_id,E.section_id, I.student_id" ); 
        if($academic_year_id)
        {
            $this->db->where_in("E.academic_year_id",$academic_year_id ); 
        }
        $this->db->having(" (SUM(I.discount)+SUM(I.net_amount)) >=( select `fee_amount` from fees_amount where `class_id` = `E`.`class_id` AND school_id = $school_id 
        and fees_amount.income_head_id =  $income_head_id limit 1
        )");
        $result =  $this->db->get();  
    //    echo $this->db->last_query();
    //     die();
        return $result->result();  
    }
    public function get_section_dicount_students_count($section_ids,$class_ids,$school_id = "", $income_head_id, $academic_year_id = null)
    {
        $this->db->select("count(DISTINCT I.student_id) as count,E.class_id,E.section_id");
        $this->db->from('invoices I');
        $this->db->join('enrollments AS E', 'E.student_id = I.student_id','left');
        $this->db->where("E.school_id",$school_id ); 
        $this->db->where_in("E.section_id",$section_ids ); 
        $this->db->where_in("E.class_id",$class_ids ); 
        $this->db->where("I.school_id",$school_id ); 
        $this->db->where("I.is_applicable_discount",1 ); 
        $this->db->where("I.discount is not null" ); 
        $this->db->where('I.reverted !=1');         
        $this->db->where("I.income_head_id", $income_head_id ); 
        $this->db->where(" I.student_id not in (select id from students where school_id=$school_id and (lower(rte)='yes' or status_type!='regular'))"); 
        $this->db->group_by("E.class_id,E.section_id" ); 
        if($academic_year_id)
        {
            $this->db->where_in("E.academic_year_id",$academic_year_id ); 
        }
        $result =  $this->db->get();  
      
        return $result->result();  
    }
    

    public function get_section_fee_amount($section_ids,$class_ids,$school_id = "",$income_head_id, $academic_year_id =null )
    {
        $this->db->select("sum(net_amount) as total_paid,sum(discount) as total_discount,E.class_id,E.section_id");
        $this->db->from('invoices I');
        $this->db->join('enrollments AS E', 'E.student_id = I.student_id','left');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->where("E.school_id",$school_id ); 
        $this->db->where_in("E.section_id",$section_ids ); 
        $this->db->where_in("E.class_id",$class_ids ); 
        $this->db->where("I.school_id",$school_id ); 
        $this->db->where("S.status_type ='regular'" ); 
        $this->db->where('I.reverted !=1');         
        $this->db->where("I.paid_status","paid" ); 
        $this->db->where("I.income_head_id",$income_head_id  ); 
        if($academic_year_id)
        {
            $this->db->where("E.academic_year_id",$academic_year_id ); 
        }
        
        $this->db->group_by("E.class_id,E.section_id" ); 
        $result =  $this->db->get();  
        //  echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    public function get_section_fee_amout_2($section_ids,$class_ids,$school_id = "",$income_head_id, $academic_year_id =null ){

        $this->db->select("sum(net_amount) as total_paid,sum(discount) as total_discount,E.class_id,E.section_id");
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('enrollments AS E', 'E.student_id = I.student_id','left');

        $this->db->where('I.invoice_type !=', 'income');         
        $this->db->where_in("E.section_id",$section_ids ); 
        $this->db->where_in("E.class_id",$class_ids );        
         $this->db->where('I.school_id', $school_id);
         $this->db->where('I.reverted !=1');         
        if($income_head_id)
        {
            $this->db->where('I.income_head_id', $income_head_id);

        }
        if($academic_year_id)
        {
            $this->db->where("E.academic_year_id",$academic_year_id ); 
        }
        $this->db->group_by("E.class_id,E.section_id" ); 
        $result =  $this->db->get();  
        //  echo $this->db->last_query();
        // die();
        return $result->result();  
   
    }
    public function get_section_fee_data($school_id ,$class_id ,$section_id = null)
    {
        $this->db->select("SUM(I.net_amount) as net_amount,SUM(I.discount)  as discount,count(S.id) as student_count, count(case when s.rte ='YES' then S.id end) as rte_count");
        $this->db->from('invoices AS I');
        $this->db->join('students AS S', 'S.id = I.student_id');
        $this->db->join('enrollments AS E','S.id = E.student_id');        

        $this->db->join('schools AS SC', 'SC.id = E.school_id');
     
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id');
      
        $this->db->where("(E.academic_year_id=SC.academic_year_id or AY.is_running=1)" ); 
        
        $this->db->where('S.status_type', 'regular'); 
        $this->db->where('E.school_id', $school_id); 
        $this->db->where('E.class_id', $class_id); 
        $this->db->where('E.section_id', $section_id); 
        $this->db->where('I.reverted !=1');         

        $this->db->order_by('E.academic_year_id');   

        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->row_array();  
    }
    
    public function get_fee_student_count($class_level_id = null ,$filter_coloumn= null,$filter_coloumn_filter = null,$filter_coloumn_value = null)
    {
        if($filter_coloumn == "E.class_id,E.section_id")
        {
            $filter_column_name = "$filter_coloumn,(select name from classes where id=E.class_id) as class_name
            ,(select name from sections where id=E.section_id) as section_name," ;
            $this->db->join('classes AS C', 'C.id = E.class_id');
        }
        else if($filter_coloumn == "SC.zone_id")
        {
            $filter_column_name = ",(select name from zone where id=SC.zone_id) as zone_name," ;
        }
        else if($filter_coloumn == "E.school_id")
        {
            $filter_column_name =  ",(select school_name from schools where id=SC.id) as school_name,";
        }
        else
        {
            $filter_column_name =  ",(select name from districts where id=SC.district_id) as district_name,";
        }
        $this->db->select("$filter_column_name SUM(I.net_amount) AS total_amount");
        $this->db->from('invoices AS I');
        $this->db->join('students AS S', 'S.id = I.student_id');
        $this->db->join('enrollments AS E','S.id = E.student_id');        

        $this->db->join('schools AS SC', 'SC.id = E.school_id');
     
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id');
      
        $this->db->where("(E.academic_year_id=SC.academic_year_id or AY.is_running=1)" ); 
        
        $this->db->where('S.status_type', 'regular'); 
        $this->db->where('I.reverted !=1');         
       if($filter_coloumn_filter && $filter_coloumn_value)
       {
           $this->db->where($filter_coloumn_filter, $filter_coloumn_value); 
       }
       $this->db->group_by("$filter_coloumn"); 
       $this->db->order_by('E.academic_year_id');   

        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    
    public function get_student_count($class_level_id = null ,$filter_coloumn= null,$filter_coloumn_filter = null,$filter_coloumn_value = null)
    {
        if($filter_coloumn == "E.class_id,E.section_id")
        {
            $filter_column_name = "$filter_coloumn,(select name from classes where id=E.class_id) as class_name
            ,(select name from sections where id=E.section_id) as section_name," ;
            $this->db->join('classes AS C', 'C.id = E.class_id');
        }
        else if($filter_coloumn == "SC.zone_id")
        {
            $filter_column_name = ",(select name from zone where id=SC.zone_id) as zone_name," ;
        }
        else if($filter_coloumn == "E.school_id")
        {
            $filter_column_name =  ",(select school_name from schools where id=SC.id) as school_name,";
        }
        else
        {
            $filter_column_name =  ",(select name from districts where id=SC.district_id) as district_name,";
        }
        $this->db->select("$filter_column_name ");
        $this->db->from('invoices AS I');
        $this->db->join('students AS S', 'S.id = I.student_id');
        $this->db->join('enrollments AS E','S.id = E.student_id');        

        $this->db->join('schools AS SC', 'SC.id = E.school_id');
     
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id');
      
        $this->db->where("(E.academic_year_id=SC.academic_year_id or AY.is_running=1)" ); 
        $this->db->where('I.reverted !=1');         
        $this->db->where('S.status_type', 'regular'); 
       if($filter_coloumn_filter && $filter_coloumn_value)
       {
           $this->db->where($filter_coloumn_filter, $filter_coloumn_value); 
       }
       $this->db->group_by("$filter_coloumn"); 
       $this->db->order_by('E.academic_year_id');   

        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    
    public function get_student_data($school_id ="",$academic_year_id,$prev_academic_year_id =null,$order_by,$faculty = null, $regular = 0)
    {
        $faculty_select = $order_by =="faculty_id" || $faculty ? ",(select count(*) from sections where school_id=E.school_id and class_id=E.class_id and status=1  ) as section_count" : "";

        $this->db->select("E.*,S.gender,SC.school_name as school_name,SC.district_id,SC.zone_id,C.disciplines as faculty_id,S.caste, S.rte,S.status_type
        ,(select name from classes where id=E.class_id) as class_name
        $faculty_select
        ,(select name from academic_disciplines where id=C.disciplines) as faculty_name
        ,(select name from districts where id=SC.district_id) as district_name
        ,(select name from zone where id=SC.zone_id) as zone_name");
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id');
        $this->db->join('schools AS SC', 'SC.id = E.school_id');
        $this->db->join('classes AS C', 'C.id = E.class_id');
        if($academic_year_id && $prev_academic_year_id)
        {
            $this->db->where("((E.academic_year_id = $academic_year_id) or (E.academic_year_id = $prev_academic_year_id))" ); 
        }
        else
        {
            $this->db->where("(E.academic_year_id = $academic_year_id)" ); 
        }
        if($school_id)
        {
            $this->db->where('E.school_id', $school_id);   
        }
        if($school_id)
        {
            $this->db->where('E.school_id', $school_id);   
        }
         if($order_by)
         {
            $this->db->order_by($order_by);   

         }  
         if ($regular ==1)
         {
            $this->db->where('S.status_type', 'regular');
         }

        $this->db->order_by('E.academic_year_id');   
        $result =  $this->db->get();  

        return $result->result();  
    }
    public function get_student_data1($school_ids ="",$academic_year_id =null,$order_by,$faculty = null)
    {
        $this->db->select("E.class_id,E.school_id,count(*) as count,S.gender,SC.district_id,SC.zone_id,C.disciplines as faculty_id,S.caste");
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id');
        $this->db->join('schools AS SC', 'SC.id = E.school_id');
        $this->db->join('classes AS C', 'C.id = E.class_id');
        $this->db->where("E.academic_year_id = SC.academic_year_id" ); 
        if($school_ids)
        {
            $this->db->where_in('E.school_id', $school_ids);   
        }
         $this->db->where('S.status_type', 'regular'); 
         if($order_by)
         {
            $this->db->group_by("$order_by,S.gender");   

         }  
       
        $this->db->order_by('E.academic_year_id');   
        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    public function get_classes($school_ids = array())
    {
        $this->db->select("*");
        $this->db->from('classes AS C');        
        $this->db->where_in('C.school_id', $school_ids);   
        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    public function get_section_count($school_ids = array())
    {
        $this->db->select(" count(DISTINCT S.id)  count,C.disciplines as faculty_id");
        $this->db->from('sections AS S');        
        $this->db->join('classes AS C', 'C.id = S.class_id');

        $this->db->where_in('S.school_id', $school_ids);   
        $this->db->group_by("faculty_id");  
        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    public function get_faculties($school_id = null,$zone_id =null, $district_id =null,$filter_coloumn = null)
    {
        $this->db->select("AD.id, AD.name
        , SC.school_name
        ,(select name from districts where id=SC.district_id) as district_name
        ,(select name from zone where id=SC.zone_id) as zone_name");
        $this->db->from('academic_disciplines AS AD');        
        $this->db->join('schools AS SC', 'SC.id = AD.school_id');
        if($school_id)
        {
            $this->db->where("(SC.id=$school_id )");   
        }
        if($zone_id)
        {
            $this->db->where('SC.zone_id', $zone_id);   
        }
        if($district_id)
        {
            $this->db->where('SC.district_id', $district_id);   
        }
        $this->db->or_where("SC.id=0");   

        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  

    }
   
     public function get_hostel_students($school_id = null,$class_id =null, $academic_year_id =null)
     {
         $this->db->select("S.*
         ,(select name from classes where id=E.class_id) as class
         ,(select name from sections where id=E.section_id) as section
         ,R.cost as fee_amount,R.yearly_room_rent as , 'hostel' as type");
         $this->db->from('students AS S');        
         $this->db->join('enrollments AS E', 'E.student_id = S.id');
   
         $this->db->where("S.school_id=$school_id ");   
     
         $this->db->where('E.class_id', $class_id);   
         $this->db->join('hostel_members AS HM', 'HM.user_id = S.user_id', 'left');
         $this->db->where('HM.id>0');
        $this->db->join('rooms AS R', 'R.id = HM.room_id', 'left');
        if($academic_year_id){
            $this->db->where("(HM.academic_year_id=$academic_year_id or coalesce(HM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
         $this->db->where('S.status_type', 'regular'); 

         $result =  $this->db->get();  
         // echo $this->db->last_query();
         // die();
         return $result->result();  
 
     }
     public function get_salary_payment_details($salary_payment_ids, $group_id = null){
		$this->db->select('SPD.*,PC.name as cat_name,PC.is_deduction_type as type, PC.pay_group_id
            ,(select name from pay_groups where id= PC.pay_group_id) as group_name');
		$this->db->from('salary_payment_details AS SPD');
        $this->db->join('payscale_category AS PC', 'PC.id = SPD.payscalecategory_id', 'left');
		$this->db->where_in('SPD.salary_payment_id', $salary_payment_ids);
        if($group_id)
        {
            $this->db->where_in('PC.pay_group_id', $group_id);
        }

		return $this->db->get()->result();		
	}
    public function get_payment_users($role_id,$school_id,$salary_month, $academic_year_id = null) {
        // echo $role_id.$school_id;
        if ($role_id == "teacher"  ||  $role_id == 'all') {
            
            $this->db->select('T.*, T.responsibility AS designation, U.username, U.role_id, U.status AS login_status,SP.net_salary,SP.working_days,SP.id as payment_id,SP.cal_basic_salary ');
            $this->db->from('teachers AS T');
            $this->db->join('users AS U', 'U.id = T.user_id', 'left');  
            $this->db->where('T.alumni', '0');
            $this->db->where('T.id>0');
            $this->db->where('SP.reverted!=1');
            $this->db->where('T.school_id', $school_id);
            $this->db->join('salary_payments AS SP', 'SP.user_id = U.id', 'left'); 
            if($academic_year_id){
                $this->db->where('SP.academic_year_id', $academic_year_id);
            }   
            $this->db->where('SP.salary_month', $salary_month);

            $teacher= $this->db->get()->result();
            // echo $this->db->last_query();exit; 
            
        } 
        if($role_id == "employee"   ||  $role_id == 'all') { 
            
            $this->db->select('E.*, U.username, U.role_id, D.name AS designation, U.status AS login_status,SP.net_salary,SP.working_days,SP.id as payment_id ,SP.cal_basic_salary  ');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            
            $this->db->join('designations AS D', 'D.id = E.designation_id', 'left'); 
            $this->db->join('salary_payments AS SP', 'SP.user_id = U.id', 'left'); 
            $this->db->where('E.id>0');
            if($academic_year_id){
                $this->db->where('SP.academic_year_id', $academic_year_id);
            }   
            $this->db->where('SP.salary_month', $salary_month);
            $this->db->where('SP.reverted!=1');

            //$this->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left'); 
            // $this->db->where('E.user_id', $user_id);
            $this->db->where('E.alumni', '0');

            $this->db->where('E.school_id', $school_id);
            $employees=  $this->db->get()->result();
            
            
        } 
       
       
        if(empty($teacher) && empty($employees)) {
            return array();
        }
        else
        {                
            if(!empty($teacher) && !empty($employees))
            {
                return array_merge($employees,$teacher);
            }
            else if(!empty($teacher))
            {
                 return $teacher;
            }
            else if(!empty($employees))
            {
                return $employees;
            }
           // var_dump($teacher,$employees);
            
        }
    }
    public function get_users_payscale_categories($user_ids){
      $this->db->select('PC.*,EP.user_id');
       $this->db->from('user_payscalecategories AS EP');
       $this->db->join('payscale_category AS PC', 'PC.id = EP.payscalecategory_id', 'left');
       $this->db->where_in('EP.user_id', $user_ids);
       return $this->db->get()->result();
   }
   public function get_transport_section_students_count($section_ids,$class_ids,$school_id = "",$academic_year_id)
   {
       $this->db->select("count(*) as count,E.class_id,E.section_id,coalesce(E.rte,S.rte) as rte");
       $this->db->from('enrollments E');
       $this->db->join('students AS S', 'S.id = E.student_id','left');
       $this->db->where("E.school_id",$school_id ); 
       $this->db->where_in("E.section_id",$section_ids ); 
       $this->db->where_in("E.class_id",$class_ids ); 
       if($academic_year_id)
       {
             $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
           $this->db->where_in("E.academic_year_id",$academic_year_id ); 
       }
       $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
       $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
       $this->db->where('S.status_type', 'regular'); 
       $this->db->where("TM.id >0");

       $this->db->group_by("E.class_id,E.section_id,coalesce(E.rte,S.rte)" ); 
       
       $result =  $this->db->get();  
       //  echo $this->db->last_query();
       // die();
       return $result->result();  
   }
   
     public function get_transport_students($school_id = null,$class_id =null, $academic_year_id =null)
     {
         $this->db->select("S.*, RS.stop_fare as fee_amount, RS.yearly_stop_fare as yearly_fee_amount, 'transport' as type
         ,(select name from classes where id=E.class_id) as class
         ,(select name from sections where id=E.section_id) as section");
         $this->db->from('students AS S');        
         $this->db->join('enrollments AS E', 'E.student_id = S.id');
   
         $this->db->where("S.school_id=$school_id ");   
     
         $this->db->where('E.class_id', $class_id);   
     
         $this->db->where('E.academic_year_id', $academic_year_id);   
         $this->db->join('transport_members AS TM', 'TM.user_id = S.user_id', 'left');
         $this->db->join('route_stops AS RS', 'RS.id = TM.route_stop_id', 'left');
         $this->db->where('S.status_type', 'regular'); 
         if($academic_year_id){
            $this->db->where("(TM.academic_year_id=$academic_year_id or coalesce(TM.academic_year_id,0)=0)");
            $this->db->where('E.academic_year_id', $academic_year_id);
        }
        $this->db->where("TM.id >0");
         $result =  $this->db->get();  
         // echo $this->db->last_query();
         // die();
         return $result->result();  
 
     }
     public function get_single_grade_with_group($id){
        
        $this->db->select('G.*,PG.group_code');
        $this->db->from('payscale_category AS G'); 
		$this->db->join('pay_groups AS PG', 'PG.id = G.pay_group_id', 'left');		        
		 $this->db->where('G.id', $id);
         return $this->db->get()->row();  
        
    }
    public function get_students($school_id,$class_id, $academic_year_id,$fee_amount,$section_id='')
    {
        $this->db->select("S.*, '$fee_amount' as fee_amount,  coalesce(E.rte,S.rte) as rte
        ,(select name from classes where id=E.class_id) as class
         ,(select name from sections where id=E.section_id) as section");
        $this->db->from(' enrollments AS E');        
        $this->db->join('students AS S', 'E.student_id = S.id');
  
        $this->db->where("S.school_id=$school_id ");   
    
        $this->db->where('E.class_id', $class_id);   
        if($section_id>0){
            $this->db->where('E.section_id', $section_id);   
        }
    
        $this->db->where('E.academic_year_id', $academic_year_id);   
        $this->db->where('S.status_type', 'regular'); 


        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  

    }
    public function get_paid_students_installment_data( $student_ids,$school_id,$class_id,$income_head_id, $academic_year_id)
    {
        $this->db->select("I.net_amount,I.discount,I.emi_type,E.student_id");
        $this->db->from('invoices I');
        $this->db->join('enrollments AS E', 'E.student_id = I.student_id','left');
        $this->db->where("E.school_id",$school_id );
        $this->db->where('I.reverted !=1');         
        $this->db->where("E.class_id",$class_id ); 
        $this->db->where("I.school_id",$school_id ); 
        $this->db->where_in("I.student_id",$student_ids ); 
        $this->db->where("I.paid_status","paid" ); 
        $this->db->where("I.income_head_id",$income_head_id ); 
        // $this->db->where("I.net_amount>0"); 
        $this->db->where("E.academic_year_id", $academic_year_id); 
       
        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    public function get_students_count($faculty_id,$filter_column_name =null)
    {

        $this->db->select("E.class_id,S.gender,count(*) as count,SC.school_name
        ,(select name from districts where id=SC.district_id) as district_name
        ,(select name from zone where id=SC.zone_id) as zone_name
        ");
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id');
        $this->db->join('schools AS SC', 'SC.id = E.school_id');
        $this->db->join('classes AS C', 'C.id = E.class_id');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id');
      
        $this->db->where("(E.academic_year_id=SC.academic_year_id or AY.is_running=1)" ); 
        
         $this->db->where('S.status_type', 'regular'); 
         $this->db->where('C.disciplines', $faculty_id); 
     
         $this->db->group_by('E.class_id,S.gender'.",$filter_column_name");   

        $result =  $this->db->get();  
       
        return $result->result();  
    }
    
    public function get_class_level_student_count($class_level_id = null ,$filter_coloumn= null,$filter_coloumn_filter = null,$filter_coloumn_value = null)
    {
        if($filter_coloumn == "SC.zone_id")
        {
            $filter_column_name = ",(select name from zone where id=SC.zone_id) as zone_name" ;
        }
        else if($filter_coloumn == "E.school_id")
        {
            $filter_column_name =  ",(select school_name from schools where id=SC.id) as school_name";
        }
        else
        {
            $filter_column_name =  ",(select name from districts where id=SC.district_id) as district_name";
        }
       
        $this->db->select("$filter_coloumn,E.class_id,AD.id,count(*) as count,(select name from classes where id=C.id) as class_name $filter_column_name 
        ");
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id');
        $this->db->join('schools AS SC', 'SC.id = E.school_id');
        $this->db->join('classes AS C', 'C.id = E.class_id');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id');
        $this->db->join('academic_disciplines AS AD','AD.id = C.disciplines');

        $this->db->where("(E.academic_year_id=SC.academic_year_id or AY.is_running=1)" ); 
        
         $this->db->where('S.status_type', 'regular'); 
         $this->db->where("$filter_coloumn != 0"); 
        if($filter_coloumn_filter && $filter_coloumn_value)
        {
            $this->db->where($filter_coloumn_filter, $filter_coloumn_value); 
        }
        $this->db->group_by("AD.id,E.class_id,$filter_coloumn"); 
        $this->db->order_by('E.academic_year_id');   
        $result =  $this->db->get();  
    //    echo $this->db->last_query();
    //     die();
        return $result->result();  
    }
    public function get_caste_student_count($caste,$filter_coloumn= null,$filter_value = null)
    {

        $this->db->select("S.gender,count(*) as count");
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id');
        $this->db->join('schools AS SC', 'SC.id = E.school_id');
        $this->db->join('classes AS C', 'C.id = E.class_id');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id');
      
        $this->db->where("(E.academic_year_id=SC.academic_year_id or AY.is_running=1)" ); 
        
         $this->db->where('S.status_type', 'regular'); 
         $this->db->where('S.caste', $caste); 
        if($filter_coloumn && $filter_value)
        {
            $this->db->where($filter_coloumn, $filter_value); 
        }
         $this->db->group_by('S.gender');   
        $this->db->order_by('E.academic_year_id');   
       
    }
    
    function get_school_list_teacher_count($school_id=null,$district_id=null,$zone_id = null) {
        $this->db->select("S.id,
        ,(select count(*) from teachers where gender = 'female'  and school_id=S.id and alumni='0' )  as female_teacher_count
        ,(select count(*) from teachers where gender = 'male'  and school_id=S.id and alumni='0')  as male_teacher_count
        ,(select count(*) from sections where school_id=S.id and status=1)  as section_count
        ");
        $this->db->from('schools AS S');
        // $this->db->where('S.status', 1);
        $this->db->join('districts AS D', 'D.id = S.district_id', 'left');	
        $this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');	
		
        if($school_id)
        {
            $this->db->where('S.id', $school_id);
        }
        else
        {
            if($zone_id)
            {
                $this->db->where('SZ.zone_id', $zone_id);   
            }
            if($district_id!= null){
                $this->db->where('S.district_id', $district_id);
            }
        }
      
   
        $result =  $this->db->get();  
        // echo $this->db->last_query();
        return $result->result();  
    }
    function get_school_list($school_id=null,$district_id=null,$zone_id = null) {
        $this->db->select("S.id");
        $this->db->from('schools AS S');
        $this->db->where('S.status', 1);
        $this->db->join('districts AS D', 'D.id = S.district_id', 'left');	
		if($district_id!= null){
			$this->db->where('S.district_id', $district_id);
		}
        if($school_id)
        {
            $this->db->where('S.id', $school_id);
        }
        if($zone_id)
        {
            $this->db->where('S.zone_id', $zone_id);   
        }
        return $this->db->get()->result();
    }
    function get_faculty_teacher_count($school_ids=null) {
        $this->db->select("C.id,count(*) as count,T.gender,C.disciplines as faculty_id");
        $this->db->from('teachers AS T');        
        $this->db->join('classes AS C', 'T.id = C.teacher_id','left');
        if($school_ids)
        {
            $this->db->where_in('T.school_id', $school_ids);   
        }
       
        $this->db->group_by("faculty_id,T.gender");   
       
        $result =  $this->db->get();  
        // echo $this->db->last_query();
        // die();
        return $result->result();  
    }
    public function get_block_list($district_id = "",$zone_id ="",$order_by = ""){        
        $this->db->select('B.*,D.id as district_id,D.name as district_name,SZ.zone_id,
        (select count(*) from schools where block_id=B.id) as school_count
        ,(select name from zone where id=SZ.zone_id) as zone_name

        ');
        $this->db->from('blocks AS B');  
        $this->db->join('districts AS D', 'D.id = B.district_id', 'left');	
        $this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');	
        if($district_id)
        {
            $this->db->where('B.district_id', $district_id);   
        }
        if($zone_id)
        {
            $this->db->where('SZ.zone_id', $zone_id);   
        }
        if($order_by)
         {
            $this->db->order_by($order_by);   
         } 
         $result =  $this->db->get();

        
        return $result->result();  
        
    }	
    public function get_sankul_list($district_id = "",$zone_id ="",$order_by = ""){        
        $this->db->select('Sankul.*,D.id as district_id,D.name as district_name,SZ.zone_id,
        (select count(*) from schools where sankul_id=Sankul.id) as school_count
        ,(select name from zone where id=SZ.zone_id) as zone_name

        ');
        $this->db->from('sankul AS Sankul');  
		$this->db->join('blocks AS B', 'B.id = Sankul.block_id', 'left');		
        $this->db->join('districts AS D', 'D.id = B.district_id', 'left');		
        $this->db->join('subzone AS SZ', 'SZ.id = D.subzone_id', 'left');	

        if($district_id)
        {
            $this->db->where('B.district_id', $district_id);   
        }
        if($zone_id)
        {
            $this->db->where('SZ.zone_id', $zone_id);   
        }
        if($order_by)
         {
            $this->db->order_by($order_by);   

         } 
        return $this->db->get()->result();
    }	
    
    
    public function get_income_report($school_id, $academic_year_id, $group_by, $date_from, $date_to){
        
        $group_by_sql = '';
        $group_by_field = '';
       if($group_by && $group_by == 'income_head'){           
           $group_by_sql .= " GROUP BY H.title ORDER BY H.title ASC";
           $group_by_field .= ", H.title AS group_by_field";
           
       }elseif($group_by && $group_by == 'daily'){           
           $group_by_sql .= " GROUP BY T.payment_date ORDER BY T.payment_date ASC";
           $group_by_field .= ", DATE_FORMAT(T.payment_date, '%b %d, %Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'monthly'){           
           $group_by_sql .= " GROUP BY MONTH(T.payment_date), YEAR(I.date) ORDER BY I.date ASC";
           $group_by_field .= ", DATE_FORMAT(T.payment_date, '%M, %Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'yearly'){           
           $group_by_sql .= " GROUP BY I.academic_year_id ORDER BY I.academic_year_id ASC";
           $group_by_field .= ", DATE_FORMAT(T.payment_date, '%Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'income_by'){           
           $group_by_sql .= " GROUP BY T.payment_method ORDER BY T.payment_method ASC";
           $group_by_field .= ", T.payment_method AS group_by_field";
           
       } 
       
        $sql = "SELECT I.*, SUM(T.amount) AS total_amount, T.payment_date, H.title AS head, AY.session_year $group_by_field 
                FROM invoices AS I                
                LEFT JOIN income_heads AS H ON H.id = I.income_head_id 
                LEFT JOIN transactions AS T ON T.invoice_id = I.id 
                LEFT JOIN academic_years AS AY ON AY.id = I.academic_year_id 
                WHERE I.status = 1 AND T.amount > 0 and I.reverted !=1";

       if($date_from != '' && $date_to != ''){
           $sql .= " AND I.date >= '$date_from' AND I.date <= '$date_to' ";
       }
       if($date_from != '' && $date_to == ''){
           $sql .= "I.date >= '$date_from'";
       }
       if($academic_year_id){
           $sql .= " AND I.academic_year_id = '$academic_year_id'";
       }
       if($school_id){
           $sql .= " AND I.school_id = '$school_id'";
       }
       
       
       $sql .= $group_by_sql;
        
       return $this->db->query($sql)->result();
    }
  
    public function get_expenditure_report($school_id, $academic_year_id, $group_by, $date_from, $date_to){
        
        $group_by_sql = '';
        $group_by_field = '';
       if($group_by && $group_by == 'expenditure_head'){           
           $group_by_sql .= " GROUP BY H.title ORDER BY H.title ASC";
           $group_by_field .= ", H.title AS group_by_field";
       }elseif($group_by && $group_by == 'daily'){           
           $group_by_sql .= " GROUP BY E.date ORDER BY E.date ASC";
           $group_by_field .= ", DATE_FORMAT(E.date, '%b %d, %Y') AS group_by_field";
       }elseif($group_by && $group_by == 'monthly'){           
           $group_by_sql .= " GROUP BY MONTH(E.date), YEAR(E.date) ORDER BY E.date ASC";
           $group_by_field .= ", DATE_FORMAT(E.date, '%M, %Y') AS group_by_field";
       }elseif($group_by && $group_by == 'yearly'){           
           $group_by_sql .= " GROUP BY E.academic_year_id ORDER BY E.academic_year_id ASC";
           $group_by_field .= ", DATE_FORMAT(E.date, '%Y') AS group_by_field";
       }elseif($group_by && $group_by == 'expenditure_by'){           
           $group_by_sql .= " GROUP BY E.expenditure_via ORDER BY E.expenditure_via ASC";
           $group_by_field .= ", E.expenditure_via AS group_by_field";
       } 
       
        $sql = "SELECT E.*, SUM(E.amount) AS total_amount, H.title AS head, AY.session_year $group_by_field 
                FROM expenditures AS E 
                LEFT JOIN expenditure_heads AS H ON H.id = E.expenditure_head_id 
                LEFT JOIN academic_years AS AY ON AY.id = E.academic_year_id 
                WHERE E.status = 1 ";
       if($date_from != '' && $date_to != ''){
           $sql .= " AND E.date >= '$date_from' AND E.date <= '$date_to' ";
       }
       if($date_from != '' && $date_to == ''){
           $sql .= "E.date >= '$date_from'";
       }
       if($academic_year_id){
           $sql .= " AND E.academic_year_id = '$academic_year_id'";
       }
       if($school_id){
           $sql .= " AND E.school_id = '$school_id'";
       }
       
       
       $sql .= $group_by_sql;
        
       return $this->db->query($sql)->result();
    }
    
    public function get_invoice_report($school_id, $academic_year_id, $group_by, $date_from, $date_to){
        
        $group_by_sql = '';
        $group_by_field = '';
        
       if($group_by && $group_by == 'fee_head'){           
           $group_by_sql .= " GROUP BY H.title ORDER BY H.title ASC";
           $group_by_field .= ", H.title AS group_by_field";
           
       }elseif($group_by && $group_by == 'daily'){           
           $group_by_sql .= " GROUP BY I.date ORDER BY I.date ASC";
           $group_by_field .= ", DATE_FORMAT(I.date, '%b %d, %Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'monthly'){           
           $group_by_sql .= " GROUP BY MONTH(I.date), YEAR(I.date) ORDER BY I.date ASC";
           $group_by_field .= ", DATE_FORMAT(I.date, '%b, %Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'yearly'){           
           $group_by_sql .= " GROUP BY I.academic_year_id ORDER BY I.academic_year_id ASC";
           $group_by_field .= ", DATE_FORMAT(I.date, '%Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'class'){           
           $group_by_sql .= " GROUP BY I.class_id ORDER BY I.class_id ASC";
           $group_by_field .= ", C.name AS group_by_field";
           
       }elseif($group_by && $group_by == 'paid_status'){           
           $group_by_sql .= " GROUP BY I.paid_status ORDER BY I.paid_status ASC";
           $group_by_field .= ", I.paid_status AS group_by_field";
       } 
       
        $sql = "SELECT I.*, SUM(I.net_amount) AS total_amount, SUM(I.discount) AS total_discount, H.title AS head, AY.session_year $group_by_field 
                FROM invoices AS I               
                LEFT JOIN income_heads AS H ON H.id = I.income_head_id 
                LEFT JOIN academic_years AS AY ON AY.id = I.academic_year_id 
                LEFT JOIN classes AS C ON C.id = I.class_id 
                WHERE I.status = 1 AND I.invoice_type != 'income' and emi_type is null and I.reverted !=1";

       if($date_from != '' && $date_to != ''){
           $sql .= " AND I.date >= '$date_from' AND I.date <= '$date_to' ";
       }
       if($date_from != '' && $date_to == ''){
           $sql .= "I.date >= '$date_from'";
       }
       if($academic_year_id){
           $sql .= " AND I.academic_year_id = '$academic_year_id'";
       }
       if($school_id){
           $sql .= " AND I.school_id = '$school_id'";
       }
       
       
       $sql .= $group_by_sql;
        
       return $this->db->query($sql)->result();
    }
    
    public function get_expenditure_by_date($school_id, $date){
        $sql = "SELECT  SUM(E.amount) AS total_amount
                FROM expenditures AS E                
                WHERE E.date = '$date' AND E.school_id = '$school_id' GROUP BY E.date ASC";
        
        $exp = $this->db->query($sql)->row();
        return isset($exp->total_amount) ? $exp->total_amount: 0;
    }
           
    public function get_income_by_date($school_id, $date){
        
        $sql = "SELECT  SUM(I.net_amount) AS total_amount
                FROM invoices AS I                
                WHERE I.date = '$date' AND I.school_id = '$school_id' and  I.reverted !=1 GROUP BY I.date ASC";

        $income= $this->db->query($sql)->row();
        return isset($income->total_amount) ? $income->total_amount: 0;
    }
    
    
      public function get_library_report($school_id, $academic_year_id, $group_by, $date_from, $date_to){
        
        $group_by_sql = '';
        $group_by_field = '';
       if($group_by && $group_by == 'daily'){           
           $group_by_sql .= " GROUP BY BI.issue_date ORDER BY BI.issue_date ASC";
           $group_by_field .= ", DATE_FORMAT(BI.issue_date, '%b %d, %Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'monthly'){           
           $group_by_sql .= " GROUP BY MONTH(BI.issue_date), YEAR(BI.issue_date) ORDER BY BI.issue_date ASC";
           $group_by_field .= ", DATE_FORMAT(BI.issue_date, '%M, %Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'yearly'){           
           $group_by_sql .= " GROUP BY BI.academic_year_id ORDER BY BI.academic_year_id ASC";
           $group_by_field .= ", DATE_FORMAT(BI.issue_date, '%Y') AS group_by_field";
           
       }elseif($group_by && $group_by == 'class'){           
           $group_by_sql .= " GROUP BY C.name ORDER BY C.name ASC";
           $group_by_field .= ", C.name AS group_by_field";
           
       } 
       
        $sql = "SELECT BI.*, COUNT(BI.id) AS total_issue, SUM(BI.is_returned) AS total_returned, AY.session_year $group_by_field 
                FROM book_issues AS BI 
                LEFT JOIN library_members AS LM ON LM.id = BI.library_member_id 
                LEFT JOIN students AS S ON S.user_id = LM.user_id 
                LEFT JOIN enrollments AS E ON E.student_id = S.id 
                LEFT JOIN classes AS C ON C.id = E.class_id 
                LEFT JOIN academic_years AS AY ON AY.id = BI.academic_year_id 
                WHERE BI.status = 1 ";
        
       if($date_from != '' && $date_to != ''){
           $sql .= " AND BI.issue_date >= '$date_from' AND BI.return_date <= '$date_to' ";
       }
       if($date_from != '' && $date_to == ''){
           $sql .= "BI.issue_date >= '$date_from'";
       }
       
       if($academic_year_id){
           $sql .= " AND BI.academic_year_id = '$academic_year_id'";
       }
       
       if($academic_year_id){           
                      
           $sql .= " AND E.academic_year_id = '$academic_year_id'";
       }
       
       if($school_id){
           $sql .= " AND BI.school_id = '$school_id'";
       }
       
       
       $sql .= $group_by_sql;
        
       return $this->db->query($sql)->result();
    }
    
  
     public function get_student_list($school_id, $academic_year_id, $class_id, $section_id){
         
        $this->db->select('E.roll_no,  S.id, S.name');
        $this->db->from('enrollments AS E');        
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->where('E.academic_year_id', $academic_year_id);       
        $this->db->where('E.class_id', $class_id);       
        $this->db->where('E.section_id', $section_id);       
        $this->db->where('E.school_id', $school_id);       
        return $this->db->get()->result();    
    } 
    
    
    public function get_student_report($school_id, $academic_year_id, $group_by){
        
        $group_by_sql = '';
        $group_by_field = '';
        $sql_plus = '';
        
       if($group_by && $group_by == 'gender'){           
           $group_by_sql .= " GROUP BY C.name ORDER BY C.id ASC";
           $group_by_field .= ", C.name AS group_by_field";
           
       }elseif($group_by && $group_by == 'vehicle'){           
           $group_by_sql .= " GROUP BY C.name ORDER BY C.id ASC";
           $group_by_field .= ", C.name AS group_by_field";
           $sql_plus .= " AND S.is_transport_member = '1'";
           
       }elseif($group_by && $group_by == 'library'){           
           $group_by_sql .= " GROUP BY C.name ORDER BY C.id ASC";
           $group_by_field .= ", C.name AS group_by_field";
           $sql_plus .= " AND S.is_library_member = '1'";
           
       }elseif($group_by && $group_by == 'hostel'){           
           $group_by_sql .= " GROUP BY C.name ORDER BY C.id ASC";
           $group_by_field .= ", C.name AS group_by_field";
           $sql_plus .= " AND S.is_hostel_member = '1'";
           
       }elseif($group_by && $group_by == 'class'){           
           $group_by_sql .= " GROUP BY C.name ORDER BY C.id ASC";
           $group_by_field .= ", C.name AS group_by_field";
       } 
       
        $sql = "SELECT S.id, COUNT(S.id) AS total, C.id as class_id, E.academic_year_id, AY.session_year $group_by_field 
                FROM students AS S 
                LEFT JOIN enrollments AS E ON E.student_id = S.id 
                LEFT JOIN classes AS C ON C.id = E.class_id 
                LEFT JOIN academic_years AS AY ON AY.id = E.academic_year_id 
                WHERE S.status = 1 ";
             
       if($academic_year_id){
           $sql_plus .= " AND E.academic_year_id = '$academic_year_id'";
       }
       if($school_id){
           $sql_plus .= " AND E.school_id = '$school_id'";
       }
       
       $sql .= $sql_plus;
       $sql .= $group_by_sql;
        
       return $this->db->query($sql)->result();
    }
    
    public function get_student_by_gender($school_id, $group_by, $class_id, $academic_year_id, $gender){
        
        $extra = '';
        if($group_by == 'vehicle'){
            $extra = "AND S.is_transport_member = '1'"; 
        }
        if($group_by == 'library'){
            $extra = "AND S.is_library_member = '1'"; 
        }
        if($group_by == 'hostel'){
            $extra = "AND S.is_hostel_member = '1'"; 
        }
        
        $sql = "SELECT COUNT(S.id) AS total
                FROM  enrollments AS E 
                LEFT JOIN students AS S ON E.student_id = S.id 
                LEFT JOIN classes AS C ON C.id = E.class_id 
                WHERE S.status = 1  AND S.gender = '$gender'
                AND E.class_id = '$class_id'
                AND E.school_id = '$school_id'       
                and S.status_type = 'regular'
          
                $extra
                AND E.academic_year_id = '$academic_year_id'";
         return $this->db->query($sql)->row()->total;
    }
    
    
    
    
    public function get_student_invoice_report($school_id, $academic_year_id, $class_id, $student_id){
        
        $this->db->select('I.*, SUM(T.amount) AS paid_amount, IH.title AS head, C.name AS class_name, ST.name AS student,  AY.session_year');
        $this->db->from('invoices AS I');   
        $this->db->join('transactions AS T', 'T.invoice_id = I.id', 'left');
        $this->db->join('students AS ST', 'ST.id = I.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->where('I.reverted !=1');         

        if($school_id != ''){
           $this->db->where('I.school_id', $school_id);
        }  
        
        if($class_id != ''){
           $this->db->where('I.class_id', $class_id);
        }      
        
        if($student_id != ''){
           $this->db->where('I.student_id', $student_id);
        }
       
        if($academic_year_id){
            $this->db->where('I.academic_year_id', $academic_year_id);
        }       
        
        $this->db->group_by('I.id', 'DESC'); 
              
        return $this->db->get()->result();  
       
    } 
    
    
        
    public function get_student_activity_report($school_id, $academic_year_id, $class_id, $student_id){
        
        $this->db->select('SA.*, C.name AS class_name, ST.name, S.name AS section, AY.session_year');
        $this->db->from('student_activities AS SA');   
        $this->db->join('students AS ST', 'ST.id = SA.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = SA.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = SA.section_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = SA.academic_year_id', 'left');
        
        if($school_id != ''){
           $this->db->where('SA.school_id', $school_id);
        } 
        
        if($class_id != ''){
           $this->db->where('SA.class_id', $class_id);
        }      
        
        if($student_id != ''){
           $this->db->where('SA.student_id', $student_id);
        }
       
        if($academic_year_id){
            $this->db->where('SA.academic_year_id', $academic_year_id);
        }    
              
        return $this->db->get()->result();   
       
    }    
   
  
    
    
    public function get_payroll_report($school_id,$academic_year_id, $group_by, $payment_to, $month){
        
        $group_by_sql = '';
        $group_by_field = '';
        
       if($group_by && $group_by == 'salary_type'){           
           $group_by_sql .= " GROUP BY SP.salary_type ORDER BY SP.salary_type ASC";
           $group_by_field .= ", SP.salary_type AS group_by_field";
       }elseif($group_by && $group_by == 'payment_to'){           
           $group_by_sql .= " GROUP BY SP.payment_to ORDER BY SP.payment_to ASC";
           $group_by_field .= ", SP.payment_to AS group_by_field";
           $group_by_field .= ", SP.payment_to AS group_by_field";
       }elseif($group_by && $group_by == 'month'){           
           $group_by_sql .= " GROUP BY SP.salary_month ORDER BY SP.salary_month ASC";
           $group_by_field .= ", SP.salary_month AS group_by_field";
       }elseif($group_by && $group_by == 'yearly'){           
           $group_by_sql .= " GROUP BY SP.academic_year_id ORDER BY SP.academic_year_id ASC";
           $group_by_field .= ", DATE_FORMAT(SP.salary_month, '%Y') AS group_by_field";
       }elseif($group_by && $group_by == 'expenditure_by'){           
           $group_by_sql .= " GROUP BY SP.payment_method ORDER BY SP.payment_method ASC";
           $group_by_field .= ", SP.payment_method AS group_by_field";
       } 
       
        $sql = "SELECT SP.id, SUM(SP.net_salary) AS total_amount, AY.session_year $group_by_field
                FROM salary_payments AS SP 
                LEFT JOIN academic_years AS AY ON AY.id = SP.academic_year_id 
                WHERE SP.status = 1 ";
                  
       if($academic_year_id){
           $sql .= " AND SP.academic_year_id = '$academic_year_id'";
       }       
       if($month){
           $sql .= " AND SP.salary_month = '$month'";
       }
       if($school_id){
           $sql .= " AND SP.school_id = '$school_id'";
       }
       
       
       $sql .= $group_by_sql;
       $result = $this->db->query($sql);
      
       return $result->result();
    }
    
        public function get_student_due_fee_report($school_id, $academic_year_id, $class_id, $student_id){
        
        $this->db->select("I.*,IH.head_type,FA.fee_amount
        ,(select SUM(net_amount) from invoices where paid_status='paid' and income_head_id=I.income_head_id and academic_year_id=I.academic_year_id and student_id=I.student_id and class_id=I.class_id) AS paid_amount,
        ,(select SUM(discount) from invoices where paid_status='paid' and income_head_id=I.income_head_id and academic_year_id=I.academic_year_id and student_id=I.student_id and class_id=I.class_id and is_applicable_discount=1) AS discount_amount,
         IH.title AS head, C.name AS class_name, ST.name AS student,  AY.session_year");
        $this->db->from('invoices AS I');   
        $this->db->join('transactions AS T', 'T.invoice_id = I.id', 'left');
        $this->db->join('students AS ST', 'ST.id = I.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->where('I.reverted !=1');         

        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('fees_amount AS FA', 'FA.class_id = I.class_id and FA.income_head_id=FA.income_head_id', 'left');
        
        if($school_id){
           $this->db->where('I.school_id', $school_id);
        } 
        
        if($class_id){
           $this->db->where('I.class_id', $class_id);
        }      
        
        if($student_id){
           $this->db->where('I.student_id', $student_id);
        }
       
        if($academic_year_id){
            $this->db->where('I.academic_year_id', $academic_year_id);
        }       
        
        $this->db->where('I.emi_type', null);
        //$this->db->where('I.paid_status !=', 'paid');
        $this->db->group_by('I.student_id', 'DESC'); 
              
        return $this->db->get()->result();  
       
    }  
    
    public function get_student_fee_collection_report($school_id, $academic_year_id, $class_id, $student_id, $fee_type, $date_from, $date_to){
        
        $this->db->select('T.*, T.note,ST.name AS student, C.name AS class_name, IH.title AS head, AY.session_year');
        $this->db->from('transactions AS T');   
        $this->db->join('invoices AS I', 'I.id = T.invoice_id', 'left');
        $this->db->join('students AS ST', 'ST.id = I.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = T.academic_year_id', 'left');
        $this->db->where('I.reverted !=1');         

        if($school_id){
            $this->db->where('T.school_id', $school_id);
        }  
        
        if($date_from != '' && $date_to != ''){
           $this->db->where('T.payment_date >=', $date_from);
           $this->db->where('T.payment_date <=', $date_to);
        }      
        
        if($date_from != '' && $date_to == ''){
           $this->db->where('T.payment_date >=', $date_from);
        }
       
        if($academic_year_id){
            $this->db->where('T.academic_year_id', $academic_year_id);
        }       
       
        if($class_id != ''){
           $this->db->where('I.class_id', $class_id);
        } 
        if($student_id != ''){
           $this->db->where('I.student_id', $student_id);
        }
        if($fee_type != ''){
           $this->db->where('I.income_head_id', $fee_type);
        }        
              
        return $this->db->get()->result();   
       
    }    
  
      
    public function get_transaction_report($school_id, $academic_year_id, $date_from, $date_to){
        
        $this->db->select('T.*, T.note, IH.title AS head, AY.session_year');
        $this->db->from('transactions AS T');   
        $this->db->join('invoices AS I', 'I.id = T.invoice_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = T.academic_year_id', 'left');
        $this->db->where('I.reverted !=1');         

        if($school_id){
            $this->db->where('T.school_id', $school_id);
        } 
        
        if($date_from != '' && $date_to != ''){
           $this->db->where('T.payment_date >=', $date_from);
           $this->db->where('T.payment_date <=', $date_to);
        }      
        
        if($date_from != '' && $date_to == ''){
           $this->db->where('T.payment_date >=', $date_from);
        }
       
        if($academic_year_id){
            $this->db->where('T.academic_year_id', $academic_year_id);
        }     
        
        $this->db->order_by('T.payment_date', 'ASC');
        return $this->db->get()->result();  
       
    } 
    
    
     
    public function get_debit_by_date($school_id, $date){
        
        $this->db->select('E.amount AS debit, E.note, E.note, H.title AS head');
        $this->db->from('expenditures AS E');        
        $this->db->join('expenditure_heads AS H', 'H.id = E.expenditure_head_id', 'left');
        $this->db->where('E.school_id', $school_id); 
        $this->db->where('E.date', $date); 
        return $this->db->get()->result();       
    }
           
    public function get_credit_by_date($school_id, $date){
        
        $this->db->select('T.amount as credit, T.note, IH.title AS head');
        $this->db->from('transactions AS T');   
        $this->db->join('invoices AS I', 'I.id = T.invoice_id', 'left');
        $this->db->join('income_heads AS IH', 'IH.id = I.income_head_id', 'left');
        $this->db->where('T.school_id', $school_id);              
        $this->db->where('T.payment_date', $date);          
        $this->db->where('I.reverted !=1');         
    
        return $this->db->get()->result();   
       
    }   
    
      
    public function get_student_examresult_report($school_id, $academic_year_id, $class_id, $section_id){
        
        $this->db->select('FR.*, G.name AS grade, E.roll_no, ST.name AS student, C.name AS class_name, S.name AS section, AY.session_year');
        $this->db->from('final_results AS FR');   
        $this->db->join('enrollments AS E', 'E.student_id = FR.student_id', 'left');
        $this->db->join('students AS ST', 'ST.id = E.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = E.section_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        $this->db->join('grades AS G', 'G.id = FR.grade_id', 'left');
              
       
        if($school_id){
            $this->db->where('E.school_id', $school_id);
        }  
        
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id);
        }       
        
        if($class_id != ''){
           $this->db->where('E.class_id', $class_id);
        } 
        if($section_id != ''){
           $this->db->where('E.section_id', $section_id);
        } 
        
         $this->db->order_by('FR.avg_grade_point', 'DESC');
              
        return $this->db->get()->result();   
       
    }    
    public function payroll_groups($school_id = null){        
        $this->db->select('PG.*, S.school_name');
        $this->db->from('pay_groups AS PG');       		
        $this->db->join('schools AS S', 'S.id = PG.school_id', 'left');		
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('PG.school_id', $this->session->userdata('school_id'));
            $this->db->or_where('school_id','0'); 
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('PG.school_id', $school_id);
            $this->db->or_where('school_id','0'); 
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('PG.school_id', $school_id);
            $this->db->or_where('school_id','0'); 
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
        return $this->db->get()->result();
        
    }
}
