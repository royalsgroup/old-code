<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('_d')) {

    function _d($data, $exit = TRUE) {
        print '<pre>';
        print_r($data);
        print '</pre>';
        if ($exit)
            exit;
    }
}
if (!function_exists('getQuesOption')) {
		function getQuesOption()
    {
        $quesOption          = array();
        $quesOption['opt_a'] = 'A';
        $quesOption['opt_b'] = 'B';
        $quesOption['opt_c'] = 'C';
        $quesOption['opt_d'] = 'D';
        $quesOption['opt_e'] = 'E';
        return $quesOption;
    }
}
if (!function_exists('debug_a')) {
    function debug_a($array,$text = null,$no_die = false)
{
   
   echo "<pre>";
   if($text)
   {
    echo " <br> $text -----------------  <br>";
   }
   var_dump($array);
   if($text)
   {
    echo "  <br>------------ -----------------  <br>";
   }
   if(!$no_die)
   {
    die();
   }
}
}
if (!function_exists('escape_qoutes')) {
    function escape_qoutes($string)
    {
        return str_replace('`', '', $string); 

    }
}
if (!function_exists('readmorelink')) {

    function readmorelink($string, $link = false)
    {
        $string = strip_tags($string);
        if (strlen($string) > 150) {

            // truncate string
            $stringCut = substr($string, 0, 150);
            $endPoint  = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= ($link) ? "<a href='" . $link . "' target='_blank'>Read more...</a>" : "....";
        }

        return $string;
    }

    
}
if (!function_exists('date_format_converter')) {
    function date_format_converter($date)
    {
        $date_array = explode("-",$date);
        if(count($date_array)>1)
        {
            $two_digit_format = isset($date_array['2']) && strlen($date_array['0']) <=2 && strlen($date_array['1']) <=2 && strlen($date_array['2']) <=2 ? 1: 0;
            if( $two_digit_format  )
            {  
                $dateTime = DateTime::createFromFormat('d-m-y', $date);
                return $dateTime->format('d-m-Y');
            } 
            else {

                return $date;
            }
        }

    $date_array = explode("/",$date);
        if(count($date_array)>1)
        {
            $two_digit_format = isset($date_array['2']) && strlen($date_array['0']) <=2 && strlen($date_array['1']) <=2 && strlen($date_array['2']) <=2 ? 1: 0;
            if( $two_digit_format  )
            {
                $dateTime = DateTime::createFromFormat('d/m/y', $date);
                return $dateTime->format('d-m-Y');
            } 
            else {
                $date = str_replace('/', '-', $date);
                return $date;
            }
        }
        return $date;
    }
}
if (!function_exists('get_youtube_id_from_url')) {
    function get_youtube_id_from_url($url)
    {
        if (stristr($url,'youtu.be/'))
            {preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID); return $final_ID[4]; }
        else 
            {@preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD); return $IDD[5]; }
    }
}
if (!function_exists('get_vimeo_id_from_url')) {
    function get_vimeo_id_from_url($url = '') {
        
        $regs = array();

        $id = '';

        if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
            $id = $regs[3];
        }

        return $id;

    }
}
if (!function_exists('logged_in_user_id')) {

    function logged_in_user_id() {
        $logged_in_id = 0;
        $CI = & get_instance();
        if ($CI->session->userdata('id') && $CI->session->userdata('role_id')):
            $logged_in_id = $CI->session->userdata('id');
        endif;
        return $logged_in_id;
    }

}

if (!function_exists('logged_in_role_id')) {

    function logged_in_role_id() {
        $logged_in_role_id = 0;
        $CI = & get_instance();
        if ($CI->session->userdata('role_id')):
            $logged_in_role_id = $CI->session->userdata('role_id');
        endif;
        return $logged_in_role_id;
    }

}

if (!function_exists('logged_in_user_type')) {

    function logged_in_user_type() {
        $logged_in_type = 0;
        $CI = & get_instance();
        if ($CI->session->userdata('user_type')):
            $logged_in_id = $CI->session->userdata('user_type');
        endif;
        return $logged_in_type;
    }

}




if (!function_exists('success')) {

    function success($message) {
        $CI = & get_instance();
        $CI->session->set_userdata('success', $message);
        return true;
    }
}

if (!function_exists('error')) {

    function error($message) {
        $CI = & get_instance();
        $CI->session->set_userdata('error', $message);
        return true;
    }

}

if (!function_exists('warning')) {

    function warning($message) {
        $CI = & get_instance();
        $CI->session->set_userdata('warning', $message);
        return true;
    }

}

if (!function_exists('info')) {

    function info($message) {
        $CI = & get_instance();
        $CI->session->set_userdata('info', $message);
        return true;
    }

}

if (!function_exists('get_slug')) {

    function get_slug($slug) {
        if (!$slug) {
            return;
        }

        $char = array("!", "â€™", "'", ":", ",", "_", "`", "~", "@", "#", "$", "%", "^", "&", "*", "(", ")", "+", "=", "{", "}", "[", "]", "/", ";", '"', '<', '>', '?', "'\'",);
        $slug = str_replace($char, "", $slug);
        return $str = strtolower(str_replace(' ', "-", $slug));
    }

}

if (!function_exists('get_status')) {

    function get_status($status) {
        $ci = & get_instance();
        if ($status == 1) {
            return $ci->lang->line('active');
        } elseif ($status == 2) {
            return $ci->lang->line('in_active');
        } elseif ($status == 3) {
            return $ci->lang->line('trash');
        }
    }

}


if (!function_exists('verify_email_format')) {

    function verify_email_format($email) {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return '';
        } else {
            return $email;
        }
    }

}


if (!function_exists('get_classes')) {

    function get_classes($school_id = null) {
        $ci = & get_instance();
        $ci->db->select('C.*');
        $ci->db->from('classes AS C');
        if($school_id){
            $ci->db->where('C.school_id', $school_id);
             //$ci->db->or_where('C.school_id', '0');
        }
         return $ci->db->get()->result();
    }

}

if (!function_exists('get_vehicles')) {

    function get_vehicle_by_ids($ids) {
        $str = '';
        if ($ids) {
            $ci = & get_instance();
            $sql = "SELECT * FROM `vehicles` WHERE `id` IN($ids)";
            $result = $ci->db->query($sql)->result();
            if (!empty($result)) {
                foreach ($result as $obj) {
                    $str .= $obj->number . ',';
                }
            }
        }
        return rtrim($str, ',');
    }

}

if (!function_exists('get_routines_by_day')) {

    function get_routines_by_day($day, $class_id = null, $section_id =null) {
        $ci = & get_instance();
        
        $ci->db->select('R.*, S.name AS subject, T.name AS teacher, C.name AS class_name');
        $ci->db->from('routines AS R');
        $ci->db->join('subjects AS S', 'S.id = R.subject_id', 'left');
        $ci->db->join('teachers AS T', 'T.id = R.teacher_id', 'left');
        $ci->db->join('classes AS C', 'C.id = R.class_id', 'left');
        $ci->db->where('R.day', $day);
               
        if(logged_in_role_id() == TEACHER){
            $ci->db->where('R.teacher_id', $ci->session->userdata('profile_id'));
        }else{
            $ci->db->where('R.class_id', $class_id);
            $ci->db->where('R.section_id', $section_id);
        }
        
        $ci->db->order_by("R.id", "ASC");
       return $ci->db->get()->result();
       
        
    }

}

if (!function_exists('get_student_attendance')) {

    function get_student_attendance($student_id, $school_id, $academic_year_id, $class_id, $section_id, $year, $month, $day) {
        $ci = & get_instance();
        $field = 'day_' . abs($day);
        $ci->db->select('SA.' . $field);
        $ci->db->from('student_attendances AS SA');
        $ci->db->where('SA.student_id', $student_id);
        $ci->db->where('SA.academic_year_id', $academic_year_id);
        $ci->db->where('SA.school_id', $school_id);
        $ci->db->where('SA.class_id', $class_id);
        
        if($section_id){
            $ci->db->where('SA.section_id', $section_id);
        }
        
        $ci->db->where('SA.year', $year);
        $ci->db->where('SA.month', $month);
        return @$ci->db->get()->row()->$field;
    }

}

if (!function_exists('get_teacher_attendance')) {

    function get_teacher_attendance($teacher_id, $school_id, $academic_year_id, $year, $month, $day) {
        $ci = & get_instance();
        $field = 'day_' . abs($day);
        $ci->db->select('TA.' . $field);
        $ci->db->from('teacher_attendances AS TA');
        $ci->db->where('TA.teacher_id', $teacher_id);
        $ci->db->where('TA.school_id', $school_id);
        $ci->db->where('TA.academic_year_id', $academic_year_id);
        $ci->db->where('TA.year', $year);
        $ci->db->where('TA.month', $month);
       return $ci->db->get()->row()->$field;
      // echo $ci->db->last_query();
    }

}

if (!function_exists('get_employee_attendance')) {

    function get_employee_attendance($teacher_id, $school_id, $academic_year_id, $year, $month, $day) {
        $ci = & get_instance();
        $field = 'day_' . abs($day);
        $ci->db->select('EA.' . $field);
        $ci->db->from('employee_attendances AS EA');
        $ci->db->where('EA.school_id', $school_id);
        $ci->db->where('EA.employee_id', $teacher_id);
        $ci->db->where('EA.academic_year_id', $academic_year_id);
        $ci->db->where('EA.year', $year);
        $ci->db->where('EA.month', $month);
        return @$ci->db->get()->row()->$field;
    }

}

if (!function_exists('get_exam_attendance')) {

    function get_exam_attendance($school_id, $student_id, $academic_year_id, $exam_id, $class_id, $section_id, $subject_id) {
        $ci = & get_instance();
        $ci->db->select('EA.is_attend');
        $ci->db->from('exam_attendances AS EA');
        $ci->db->where('EA.school_id', $school_id);
        $ci->db->where('EA.academic_year_id', $academic_year_id);
        $ci->db->where('EA.exam_id', $exam_id);
        $ci->db->where('EA.class_id', $class_id);
        if($section_id){
            $ci->db->where('EA.section_id', $section_id);
        }
        $ci->db->where('EA.student_id', $student_id);
        $ci->db->where('EA.subject_id', $subject_id);
        return @$ci->db->get()->row()->is_attend;
        
    }

}

if (!function_exists('get_exam_mark')) {

    function get_exam_mark($school_id, $student_id, $academic_year_id, $exam_id, $class_id, $section_id, $subject_id) {
        $ci = & get_instance();
        $ci->db->select('M.*');
        $ci->db->from('marks AS M');
        $ci->db->where('M.academic_year_id', $academic_year_id);
        $ci->db->where('M.school_id', $school_id);
        $ci->db->where('M.exam_id', $exam_id);
        $ci->db->where('M.class_id', $class_id);
        if($section_id){
            $ci->db->where('M.section_id', $section_id);
        }
        $ci->db->where('M.student_id', $student_id);
        $ci->db->where('M.subject_id', $subject_id);
        return $ci->db->get()->row();
    }

}

if (!function_exists('get_years')) {

    function get_years($school_id = null) {
        if ($school_id) {
            $ci = & get_instance();
            $academic_year_value = $financial_year_value = $sYearInfo = "";
            
            // if(!isset( $_SESSION['academic_year'])) {
                $ci->db->select('session_year');
                $ci->db->from('academic_years');
                $ci->db->where('school_id', $school_id);
                $ci->db->where('is_running', 1);
                $academic_year =  $ci->db->get()->row();
                if (!empty($academic_year)) {
                    $academic_year_value = $academic_year->session_year;
                    // $_SESSION['academic_year']  = $academic_year->session_year;
                }
            // }
            // else {
            //     // $academic_year_value =   $_SESSION['academic_year'];
            // }
            // if(!isset( $_SESSION['financial_year'])) {
                $ci->db->select('session_year');
                $ci->db->from('financial_years');
                $ci->db->where('school_id', $school_id);
                $ci->db->where('is_running', 1);
                $financial_year =  $ci->db->get()->row();
                if (!empty($academic_year)) {
                    $financial_year_value = $financial_year->session_year;
                    // $_SESSION['financial_year']  = $academic_year->session_year;
                }
            // }
            // else {
            //     // $financial_year_value =   $_SESSION['academic_year'];
            // }
            // var_dump( $financial_year);
            if ($financial_year_value) {
                $sYearInfo = "FY : ".$financial_year_value;
            }
            if($academic_year_value) {
                if($financial_year_value)  $sYearInfo .= "<br>";
                $sYearInfo .= "AY : ".$academic_year_value;

            }
            return $sYearInfo;
        }
        return "";
    }
}

if (!function_exists('get_exam_total_mark')) {

    function get_exam_total_mark($school_id, $exam_id, $student_id, $academic_year_id, $class_id, $section_id = null) {
        
        $ci = & get_instance();
        $ci->db->select('COUNT(M.id) AS total_subject, SUM(G.point) AS total_point, SUM(M.exam_total_mark) AS exam_mark, SUM(M.obtain_total_mark) AS obtain_mark');
        $ci->db->from('marks AS M');
        $ci->db->join('grades AS G', 'G.id = M.grade_id', 'left');      
        $ci->db->where('M.school_id', $school_id);
        $ci->db->where('M.class_id', $class_id);
        $ci->db->where('M.exam_id', $exam_id);
        if ($section_id) {
            $ci->db->where('M.section_id', $section_id);
        }
        
        $ci->db->where('M.student_id', $student_id);
        $ci->db->where('M.academic_year_id', $academic_year_id);
        return $ci->db->get()->row();
    }
}
if (!function_exists('find_grade')) {

    function find_grade($grades, $percentage) {
            foreach($grades as $grade)
            {   
                if($percentage> $grade->mark_to && $percentage<= $grade->mark_from)
                {
                    return $grade;
                }
            }
            return null;
    }
}
if (!function_exists('lowest_grade')) {

    function lowest_grade($grades) {
            $lowest_grade = '';
            foreach($grades as $grade)
            {   
                if($lowest_grade == ''){
                    $lowest_grade  = $grade;

                    continue;
                } 
                if( $lowest_grade->mark_from >= $grade->mark_from)
                {
                    $lowest_grade  = $grade;
                }

            }

            return  $lowest_grade;
    }
}


if (!function_exists('get_exam_result')) {

    function get_exam_result($school_id, $exim_id, $student_id, $academic_year_id, $class_id, $section_id = null) {
        $ci = & get_instance();
        $ci->db->select('ER.*, G.name');
        $ci->db->from('exam_results AS ER');  
        $ci->db->join('grades AS G', 'G.id = ER.grade_id', 'left');  
        $ci->db->where('ER.school_id', $school_id);
        $ci->db->where('ER.exam_id', $exim_id);
        $ci->db->where('ER.class_id', $class_id);
        
        if ($section_id) {
            $ci->db->where('ER.section_id', $section_id);
        }
        
        $ci->db->where('ER.student_id', $student_id);
        $ci->db->where('ER.academic_year_id', $academic_year_id);
        return $ci->db->get()->row();
    }
}


if (!function_exists('get_exam_final_result')) {

    function get_exam_final_result($school_id, $student_id, $academic_year_id, $class_id, $section_id = null) {
        $ci = & get_instance();
        $ci->db->select('FR.*');
        $ci->db->from('final_results AS FR');
        $ci->db->where('FR.class_id', $class_id);
        if ($section_id) {
            $ci->db->where('FR.section_id', $section_id);
        }
        $ci->db->where('FR.school_id', $school_id);
        $ci->db->where('FR.student_id', $student_id);
        $ci->db->where('FR.academic_year_id', $academic_year_id);
        return $ci->db->get()->row();
    }
}



if (!function_exists('get_student_position')) {

    function get_student_position($school_id, $academic_year_id, $class_id, $student_id, $section_id = null) {
        
        $cond = "";
        $condition = " academic_year_id = $academic_year_id ";
        $condition .= " AND school_id = $school_id";
        $condition .= " AND class_id = $class_id";        
        if($section_id){
           $condition .= " AND section_id = $section_id";
        }
        
        $cond = $condition;
        $condition .= " AND student_id = $student_id";
        
       
        
        $ci = & get_instance();              
        $sql = "SELECT id, avg_grade_point, FIND_IN_SET( (avg_grade_point), 
                ( SELECT GROUP_CONCAT( (avg_grade_point) ORDER BY avg_grade_point DESC )
                FROM final_results WHERE $cond  )) AS rank 
                FROM final_results 
                WHERE $condition";
        
        $result =  $ci->db->query($sql)->row(); 
      
        $rank = '';
        if(!empty($result)){
            $rank = $result->rank;
        }
                       
        if($rank == 1){
            return $rank.'st';
        }elseif($rank == 2){
           return $rank.'nd'; 
        }elseif($rank == 3){
           return $rank.'rd'; 
        }elseif($rank > 3 ){
            return $rank.'th';         
        }else{
            return '--'; 
        }
    }

}



if (!function_exists('get_lowet_height_mark')) {

    function get_lowet_height_mark($school_id, $academic_year_id, $exam_id, $class_id, $section_id, $subject_id) {
        $ci = & get_instance();
        $ci->db->select('MIN(M.obtain_total_mark) AS lowest, MAX(M.obtain_total_mark) AS height');
        $ci->db->from('marks AS M');       
        $ci->db->where('M.school_id', $school_id);
        $ci->db->where('M.academic_year_id', $academic_year_id);
        $ci->db->where('M.exam_id', $exam_id);
        $ci->db->where('M.class_id', $class_id);
        $ci->db->where('M.section_id', $section_id);
        $ci->db->where('M.subject_id', $subject_id);  
        return  $ci->db->get()->row();
     // echo $ci->db->last_query();
    }
}


if (!function_exists('get_position_in_subject')) {

    function get_position_in_subject($school_id, $academic_year_id, $exam_id, $class_id, $section_id, $subject_id, $mark) {
        
        
        $ci = & get_instance();
        $sec = "";
        if($section_id){
           $sec = " AND section_id = $section_id"; 
        }
        
        $sql = "SELECT id, obtain_total_mark, FIND_IN_SET( obtain_total_mark,(
                SELECT GROUP_CONCAT( obtain_total_mark  ORDER BY obtain_total_mark DESC ) 
                FROM marks WHERE  
                school_id = $school_id
                AND academic_year_id = $academic_year_id
                AND exam_id = $exam_id
                AND class_id = $class_id
                $sec
                AND subject_id = $subject_id))
                AS rank 
                FROM marks
                WHERE school_id = $school_id
                   AND academic_year_id = $academic_year_id  
                   AND exam_id = $exam_id
                   AND class_id = $class_id 
                   $sec 
                   AND subject_id = $subject_id 
                   AND  obtain_total_mark = $mark"; 
        
        $rank =  $ci->db->query($sql)->row()->rank; 
        
        if($mark == 0){
            return '--'; 
        }
        
        if($rank == 1){
            return $rank.'st';
        }elseif($rank == 2){
           return $rank.'nd'; 
        }elseif($rank == 3){
           return $rank.'rd'; 
        }elseif($rank > 3 ){
            return $rank.'th';         
        }else{
            return '--'; 
        }
    }

}


if (!function_exists('get_subject_list')) {

    function get_subject_list($school_id, $academic_year_id, $exam_id, $class_id, $section_id = null, $student_id = null) {
        $ci = & get_instance();
        $ci->db->select('M.*,S.name AS subject, G.point, G.name');
        $ci->db->from('marks AS M');        
        $ci->db->join('subjects AS S', 'S.id = M.subject_id', 'left');
        $ci->db->join('grades AS G', 'G.id = M.grade_id', 'left');
        $ci->db->where('M.school_id', $school_id);
        $ci->db->where('M.academic_year_id', $academic_year_id);
        $ci->db->where('M.class_id', $class_id);
        if($section_id){
            $ci->db->where('M.section_id', $section_id);
        }
        $ci->db->where('M.student_id', $student_id);
        $ci->db->where('M.exam_id', $exam_id);
        return  $ci->db->get()->result();     
    }

}



if (!function_exists('get_exam_wise_markt')) {

    function get_exam_wise_markt($school_id, $academic_year_id, $exam_id, $class_id, $section_id = null, $student_id = null) {
        $ci = & get_instance();
        
        $select = 'SUM(M.written_mark) AS written_mark,
                   SUM(M.written_obtain) AS written_obtain,
                   SUM(M.tutorial_mark) AS tutorial_mark,
                   SUM(M.tutorial_obtain) AS tutorial_obtain,
                   SUM(M.practical_mark) AS practical_mark,
                   SUM(M.practical_obtain) AS practical_obtain,
                   SUM(M.viva_mark) AS viva_mark,
                   SUM(M.viva_obtain) AS viva_obtain,
                   COUNT(M.id) AS total_subject,
                   SUM(G.point) AS point               
                   ';
        
        $ci->db->select($select);
        $ci->db->from('marks AS M');        
        $ci->db->join('grades AS G', 'G.id = M.grade_id', 'left');          
        $ci->db->where('M.school_id', $school_id);
        $ci->db->where('M.academic_year_id', $academic_year_id);
        $ci->db->where('M.class_id', $class_id);
        if($section_id){
            $ci->db->where('M.section_id', $section_id);
        }
        $ci->db->where('M.student_id', $student_id);
        $ci->db->where('M.exam_id', $exam_id);        
        return $ci->db->get()->row();  
        // $ci->db->last_query();
    }
}


if (!function_exists('get_position_in_exam')) {

    function get_position_in_exam($school_id, $academic_year_id, $exam_id, $class_id, $section_id = null, $mark = null) {
                
        $ci = & get_instance();
        
        $sec = "";
        if($section_id){
           $sec = " AND section_id = $section_id"; 
        }
        
        $sql = "SELECT id, total_obtain_mark, FIND_IN_SET( total_obtain_mark,(
                SELECT GROUP_CONCAT( total_obtain_mark  ORDER BY total_obtain_mark DESC ) 
                FROM exam_results WHERE 
                school_id = $school_id 
                AND academic_year_id = $academic_year_id 
                AND exam_id = $exam_id 
                AND class_id = $class_id 
                $sec ))
                AS rank 
                FROM exam_results
                WHERE school_id = $school_id 
                AND academic_year_id = $academic_year_id
                AND exam_id = $exam_id 
                AND class_id = $class_id 
                $sec 
                AND total_obtain_mark = $mark"; 
        
        $rank =  @$ci->db->query($sql)->row()->rank; 
        
        if($mark == 0){
            return '--'; 
        }
        
        if($rank == 1){
            return $rank.'st';
        }elseif($rank == 2){
           return $rank.'nd'; 
        }elseif($rank == 3){
           return $rank.'rd'; 
        }elseif($rank > 3 ){
            return $rank.'th';         
        }else{
            return '--'; 
        }
    }

}


if (!function_exists('get_lowet_height_result')) {

    function get_lowet_height_result($school_id, $academic_year_id, $exam_id, $class_id, $section_id = null, $student_id = null) {
        $ci = & get_instance();
        $ci->db->select('MIN(ER.total_obtain_mark) AS lowest, MAX(ER.total_obtain_mark) AS height');
        $ci->db->from('exam_results AS ER');       
        $ci->db->where('ER.school_id', $school_id);
        $ci->db->where('ER.academic_year_id', $academic_year_id);
        $ci->db->where('ER.exam_id', $exam_id);
        $ci->db->where('ER.class_id', $class_id);
        if($section_id){
           $ci->db->where('ER.section_id', $section_id);
        }
        return  $ci->db->get()->row();
    }

}


if (!function_exists('get_final_result')) {
    
    function get_final_result($school_id, $academic_year_id, $class_id, $section_id = null, $student_id = null){
        
        $ci = & get_instance();
        $ci->db->select('FR.*, G.name AS grade');
        $ci->db->from('final_results AS FR');        
        $ci->db->join('grades AS G', 'G.id = FR.grade_id', 'left');
        $ci->db->where('FR.academic_year_id', $academic_year_id);
        $ci->db->where('FR.school_id', $school_id);
        $ci->db->where('FR.class_id', $class_id);
        if($section_id){
           $ci->db->where('FR.section_id', $section_id);
        }
        $ci->db->where('FR.student_id', $student_id);
        return $ci->db->get()->row();   
    }
}


if (!function_exists('get_position_in_class')) {

    function get_position_in_class($school_id, $academic_year_id, $class_id, $student_id) {
       
        
        $condition = " school_id = $school_id";
        $condition .= " AND academic_year_id = $academic_year_id";
        $condition .= " AND class_id = $class_id";
        $condition .= " AND student_id = $student_id";       
        
        $ci = & get_instance();              
        $sql = "SELECT id, avg_grade_point, FIND_IN_SET( (avg_grade_point+total_obtain_mark), 
                ( SELECT GROUP_CONCAT( (avg_grade_point+total_obtain_mark) ORDER BY avg_grade_point DESC )
                FROM final_results ) ) AS rank 
                FROM final_results 
                WHERE $condition";
        
        $rank =  @$ci->db->query($sql)->row()->rank; 
         
        if($rank == 1){
            return $rank.'st';
        }elseif($rank == 2){
           return $rank.'nd'; 
        }elseif($rank == 3){
           return $rank.'rd'; 
        }elseif($rank > 3 ){
            return $rank.'th';         
        }else{
            return '--'; 
        }
    }

}




if (!function_exists('get_enrollment')) {

    function get_enrollment($student_id, $academic_year_id, $school_id = null) {
        $ci = & get_instance();
        $ci->db->select('E.*');
        $ci->db->from('enrollments AS E');
        $ci->db->where('E.student_id', $student_id);
        $ci->db->where('E.academic_year_id', $academic_year_id);
        if($school_id){
            $ci->db->where('E.school_id', $school_id);
        }
        return $ci->db->get()->row();
    }

}

if (!function_exists('get_user_by_role')) {

    function get_user_by_role($role_id, $user_id) {

        $ci = & get_instance();

        if ($role_id == SUPER_ADMIN) {
            
            $ci->db->select('SA.*, U.username, U.role_id');
            $ci->db->from('system_admin AS SA');
            $ci->db->join('users AS U', 'U.id = SA.user_id', 'left');
            $ci->db->where('SA.user_id', $user_id);
            return $ci->db->get()->row();

        }elseif ($role_id == STUDENT) {

            $ci->db->select('S.*, T.type,  D.amount, D.title AS discount, G.name AS guardian,G.user_id AS g_user_id, E.roll_no, E.section_id, E.class_id, U.username, U.role_id,  C.name AS class_name, SE.name AS section');
            $ci->db->from('enrollments AS E');
            $ci->db->join('students AS S', 'S.id = E.student_id', 'left');
            $ci->db->join('student_types AS T', 'T.id = S.type_id', 'left');
            $ci->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
            $ci->db->join('schools AS SC', 'SC.id = E.school_id  ', 'left');
            $ci->db->join('users AS U', 'U.id = S.user_id', 'left');
            $ci->db->join('classes AS C', 'C.id = E.class_id', 'left');
            $ci->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
            $ci->db->join('discounts AS D', 'D.id = S.discount_id', 'left');
            $ci->db->where('S.user_id', $user_id);
            $ci->db->where('SC.academic_year_id=E.academic_year_id');
            $result = $ci->db->get();
           
            return $result->row();
            
        } elseif ($role_id == TEACHER) {
            
            $ci->db->select('T.*, U.username, U.role_id');
            $ci->db->from('teachers AS T');
            $ci->db->join('users AS U', 'U.id = T.user_id', 'left');
            $ci->db->where('T.user_id', $user_id);
            return $ci->db->get()->row();
            
        } elseif ($role_id == GUARDIAN) {
            
            $ci->db->select('G.*, U.username, U.role_id');
            $ci->db->from('guardians AS G');
            $ci->db->join('users AS U', 'U.id = G.user_id', 'left');
            $ci->db->where('G.user_id', $user_id);
            return $ci->db->get()->row();
                
        } else {
            // debug_a("tes");
            $ci->db->select('E.*, U.username, U.role_id');
            $ci->db->from('employees AS E');
            $ci->db->join('users AS U', 'U.id = E.user_id', 'left');
            //$ci->db->join('designations AS D', 'D.id = E.designation_id', 'left');
            //$ci->db->join('salary_grades AS SG', 'SG.id = E.salary_grade_id', 'left');
            $ci->db->where('E.user_id', $user_id);
            return $ci->db->get()->row();
        }

        $ci->db->last_query();
    }

}

if (!function_exists('replace_variables')) {
    function replace_variables($data,$body) {
        // debug_a($data);

        foreach($data as $key => $value)
        {        
            $body = str_replace("[$key]",$value,$body);

        }

        return $body;
    }
}

if (!function_exists('get_user_by_id')) {

    function get_user_by_id($user_id) {

        $ci = & get_instance();

        $ci->db->select('U.id, U.role_id');
        $ci->db->from('users AS U');
        $ci->db->where('U.id', $user_id);
        $user = $ci->db->get()->row();
        
        if(isset($user))
        {
            if ($user->role_id == SUPER_ADMIN) {
            
                $ci->db->select('SA.*, U.username, U.role_id');
                $ci->db->from('system_admin AS SA');
                $ci->db->join('users AS U', 'U.id = SA.user_id', 'left');
                $ci->db->where('SA.user_id', $user_id);
                return $ci->db->get()->row();
                
            }else if ($user->role_id == STUDENT) {
    
                $ci->db->select('S.*, E.roll_no, U.username, U.role_id,  C.name AS class_name, SE.name AS section');
                $ci->db->from('enrollments AS E');
                $ci->db->join('students AS S', 'S.id = E.student_id', 'left');
                $ci->db->join('users AS U', 'U.id = S.user_id', 'left');
                $ci->db->join('classes AS C', 'C.id = E.class_id', 'left');
                $ci->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
                $ci->db->where('S.user_id', $user_id);            
                return $ci->db->get()->row();
                
            } elseif ($user->role_id == TEACHER) {
                
                $ci->db->select('T.*, U.username, U.role_id');
                $ci->db->from('teachers AS T');
                $ci->db->join('users AS U', 'U.id = T.user_id', 'left');
                $ci->db->where('T.user_id', $user_id);
                return $ci->db->get()->row();
                
            } elseif ($user->role_id == GUARDIAN) {
                
                $ci->db->select('G.*, U.username, U.role_id');
                $ci->db->from('guardians AS G');
                $ci->db->join('users AS U', 'U.id = G.user_id', 'left');
                $ci->db->where('G.user_id', $user_id);
                return $ci->db->get()->row();        
                
            } else {
                
                $ci->db->select('E.*, U.username, U.role_id, D.name AS designation');
                $ci->db->from('employees AS E');
                $ci->db->join('users AS U', 'U.id = E.user_id', 'left');
                $ci->db->join('designations AS D', 'D.id = E.designation_id', 'left');
                $ci->db->where('E.user_id', $user_id);
                return  $ci->db->get()->row();
            }
    
        }
        else
        {
            return false;
        }
       
        // $ci->db->last_query();
    }

}


if (!function_exists('get_total_used_leave')) {

    function get_total_used_leave($role_id, $type_id, $user_id) {
        $ci = & get_instance();
        
        $ci->db->select('sum(A.leave_day) AS total');
        $ci->db->from('leave_applications AS A');
        $ci->db->where('A.role_id', $role_id);
        $ci->db->where('A.type_id', $type_id);
        $ci->db->where('A.user_id', $user_id);
        $ci->db->where('A.leave_status', 2);
        $result = $ci->db->get()->row();
      
        if(!empty($result)){
            return $result->total;
        }else{
            return 0;
        }
    }
}


if (!function_exists('get_blood_group')) {

    function get_blood_group() {
        $ci = & get_instance();
        return array(
            'a_positive' => $ci->lang->line('a_positive'),
            'a_negative' => $ci->lang->line('a_negative'),
            'b_positive' => $ci->lang->line('b_positive'),
            'b_negative' => $ci->lang->line('b_negative'),
            'o_positive' => $ci->lang->line('o_positive'),
            'o_negative' => $ci->lang->line('o_negative'),
            'ab_positive' => $ci->lang->line('ab_positive'),
            'ab_negative' => $ci->lang->line('ab_negative')
        );
    }

}

if (!function_exists('get_subject_type')) {

    function get_subject_type() {
        $ci = & get_instance();
        return array(
            'mandatory' => $ci->lang->line('mandatory'),
            'optional' => $ci->lang->line('optional')
        );
    }

}

if (!function_exists('get_groups')) {

    function get_groups() {
        $ci = & get_instance();
        return array(
            'science' => $ci->lang->line('science'),
            'arts' => $ci->lang->line('arts'),
            'commerce' => $ci->lang->line('commerce')
        );
    }

}


if (!function_exists('get_week_days')) {

    function get_week_days() {
        $ci = & get_instance();
        return array(
            
            'monday' => $ci->lang->line('monday'),
            'tuesday' => $ci->lang->line('tuesday'),
            'wednesday' => $ci->lang->line('wednesday'),
            'thursday' => $ci->lang->line('thursday'),
            'friday' => $ci->lang->line('friday'),
			'saturday' => $ci->lang->line('saturday')
            
        );
    }

}

if (!function_exists('get_months')) {

    function get_months() {
        $ci = & get_instance();
        return array(
            'january' => $ci->lang->line('january'),
            'february' => $ci->lang->line('february'),
            'march' => $ci->lang->line('march'),
            'april' => $ci->lang->line('april'),
            'may' => $ci->lang->line('may'),
            'june' => $ci->lang->line('june'),
            'july' => $ci->lang->line('july'),
            'august' => $ci->lang->line('august'),
            'september' => $ci->lang->line('september'),
            'october' => $ci->lang->line('october'),
            'november' => $ci->lang->line('november'),
            'december' => $ci->lang->line('december')
        );
    }

}

if (!function_exists('get_hostel_types')) {

    function get_hostel_types() {
        $ci = & get_instance();
        return array(
            'boys' => $ci->lang->line('boys'),
            'girls' => $ci->lang->line('girls'),
            'combine' => $ci->lang->line('combine')
        );
    }
}

if (!function_exists('get_page_location')) {

    function get_page_location() {
        $ci = & get_instance();
        return array(
            'header' => $ci->lang->line('header'),
            'footer' => $ci->lang->line('footer'),
        );
    }
}

if (!function_exists('get_room_types')) {

    function get_room_types() {
        $ci = & get_instance();
        return array(
            'ac' => $ci->lang->line('ac'),
            'non_ac' => $ci->lang->line('non_ac')
        );
    }

}


if (!function_exists('get_genders')) {

    function get_genders() {
        $ci = & get_instance();
        return array(
            'male' => $ci->lang->line('male'),
            'female' => $ci->lang->line('female')
        );
    }

}

if (!function_exists('get_paid_types')) {

    function get_paid_status($status) {
        $ci = & get_instance();
        $data = array(
            'paid' => $ci->lang->line('paid'),
            'unpaid' => $ci->lang->line('unpaid'),
            'partial' => $ci->lang->line('partial')
        );
        return $data[$status];
    }

}
if (!function_exists('m_mode_on')) {

    function m_mode_on() {
        $iMMode = isset($_SESSION['m_mode_on']) && $_SESSION['m_mode_on'] ==1 ? 1 : 0;
        if(!$iMMode)
        {
            echo "Maintenance going on, Please wait";
            die();
        }
    }

}
if (!function_exists('get_video_types')) {

    function get_video_types($type = null) {
        $ci = & get_instance();
        $types = array(
            'youtube' => $ci->lang->line('youtube'),
            'video' => 'video',
        );
        if($type == 'vimeo')
        {
            $types[] = 'vimeo';
        }
        if($type == 'ppt')
        {
            $types[] = 'ppt';
        }
        return $types;
    }
}


if (!function_exists('get_relation_types')) {

    function get_relation_types() {
        $ci = & get_instance();
        return array(
            'father' => $ci->lang->line('father'),
            'mother' => $ci->lang->line('mother'),
            'sister' => $ci->lang->line('sister'),
            'brother' => $ci->lang->line('brother'),
            'uncle' => $ci->lang->line('uncle'),
            'maternal_uncle' => $ci->lang->line('maternal_uncle'),
            'other_relative' => $ci->lang->line('other_relative')
        );
    }

}

if (!function_exists('get_payment_methods')) {

    function get_payment_methods($school_id = null) {
        $ci = & get_instance();

        $methods = array(
            'cash' => $ci->lang->line('cash'),
            'cheque' => $ci->lang->line('cheque'),
            'upi_online' => $ci->lang->line('upi_online')
        );
          
        if($ci->session->userdata('role_id') != SUPER_ADMIN){
            $school_id =  $ci->session->userdata('school_id');
        }
        
        $ci->db->select('PS.*');
        $ci->db->from('payment_settings AS PS');  
        if($school_id){
            $ci->db->where('PS.school_id', $school_id);
        }
        
        $data = $ci->db->get()->row();
        if(!empty($data)){ 
            if ($data->paypal_status) {
                $methods['paypal'] = $ci->lang->line('paypal');
            }
            if ($data->payumoney_status) {
                $methods['payumoney'] = $ci->lang->line('payumoney');
            }
            /*if ($data->stripe_status) {
                $methods['stripe'] = $ci->lang->line('stripe');
            }
            if ($data->ccavenue_status) {
                $methods['ccavenue'] = $ci->lang->line('ccavenue');
            }*/
            if ($data->paytm_status) {
                $methods['paytm'] = $ci->lang->line('paytm');
            }            
            if ($data->stack_status) {
                $methods['paystack'] = $ci->lang->line('pay_stack');
            }
        }

        return $methods;
    }

}

if (!function_exists('get_sms_gateways')) {

    function get_sms_gateways( $school_id ) {
        
        $ci = & get_instance();
        $gateways = array();
       
        $ci->db->select('SS.*');
        $ci->db->from('sms_settings AS SS');
        $ci->db->where('SS.school_id', $school_id);
        $ci->db->or_where('SS.school_id', 0);

        $data = $ci->db->get()->row();

        if(!empty($data)){
            
            if ($data->clickatell_status) {
                $gateways['clicktell'] = $ci->lang->line('clicktell');
            }
            if ($data->twilio_status) {
                $gateways['twilio'] = $ci->lang->line('twilio');
            }
            if ($data->bulk_status) {
                $gateways['bulk'] = $ci->lang->line('bulk');
            }
            if ($data->msg91_status) {
                $gateways['msg91'] = $ci->lang->line('msg91');
            }
            if ($data->plivo_status) {
                $gateways['plivo'] = $ci->lang->line('plivo');
            }
            if ($data->textlocal_status) {
                $gateways['text_local'] = $ci->lang->line('text_local');
            }
            if ($data->smscountry_status) {
                $gateways['sms_country'] = $ci->lang->line('sms_country');
            }
            if ($data->betasms_status) {
                $gateways['beta_sms'] = $ci->lang->line('beta_sms');
            }
         }
        return $gateways;
    }

}


if (!function_exists('get_group_by_type')) {

    function get_group_by_type() {
        $ci = & get_instance();
        return array(
            'daily' => $ci->lang->line('daily'),
            'monthly' => $ci->lang->line('monthly'),
            'yearly' => $ci->lang->line('yearly')
        );
    }

}


if (!function_exists('get_template_tags')) {

    function get_template_tags($role_id) {
        $tags = array();
        $tags[] = '[name]';
        $tags[] = '[email]';
        $tags[] = '[phone]';

        if($role_id == SUPER_ADMIN){
            
            $tags[] = '[designation]';
            $tags[] = '[gender]';
            $tags[] = '[blood_group]';
            $tags[] = '[religion]';
            $tags[] = '[dob]';            
        }elseif ($role_id == STUDENT) {

            $tags[] = '[class_name]';
            $tags[] = '[section]';
            $tags[] = '[roll_no]';
            $tags[] = '[dob]';
            $tags[] = '[gender]';
            $tags[] = '[religion]';
            $tags[] = '[blood_group]';
            $tags[] = '[registration_no]';
            $tags[] = '[group]';
            $tags[] = '[created_at]';
            $tags[] = '[guardian]';
            
        } else if ($role_id == GUARDIAN) {
            $tags[] = '[profession]';
        } else if ($role_id == TEACHER) {
            $tags[] = '[responsibility]';
            $tags[] = '[gender]';
            $tags[] = '[blood_group]';
            $tags[] = '[religion]';
            $tags[] = '[dob]';
            $tags[] = '[joining_date]';
        } else {
            $tags[] = '[designation]';
            $tags[] = '[gender]';
            $tags[] = '[blood_group]';
            $tags[] = '[religion]';
            $tags[] = '[dob]';
            $tags[] = '[joining_date]';
        }

        $tags[] = '[present_address]';
        $tags[] = '[permanent_address]';

        return $tags;
    }

}

if (!function_exists('get_formatted_body')) {

    function get_formatted_body($body, $role_id, $user_id) {

        $tags = get_template_tags($role_id);
        $user = get_user_by_role($role_id, $user_id);
             
        $arr = array('[', ']');
        foreach ($tags as $tag) {
            $field = str_replace($arr, '', $tag);
            
            if($field == 'created_at'){                
                $body = str_replace($tag, date('M-d-Y', strtotime($user->created_at)), $body);                
            }else{
                $body = str_replace($tag, $user->{$field}, $body);
            } 
        }

        return $body;
    }
}

if (!function_exists('get_formatted_certificate_text')) {

    function get_formatted_certificate_text($body, $role_id, $user_id) {

        $tags = get_template_tags($role_id);
        $user = get_user_by_role($role_id, $user_id);
              
        $arr = array('[', ']');
        foreach ($tags as $tag) {
            $field = str_replace($arr, '', $tag);
            
            if($field == 'created_at'){                
                $body = str_replace($tag, '<span>'.date('M-d-Y', strtotime($user->created_at)).'</span>', $body);                
            }else{
                $body = str_replace($tag, '<span>'.$user->{$field}.'</span>', $body);
            }            
        }

        return $body;
    }
}



if (!function_exists('get_nice_time')) {

    function get_nice_time($date) {

        $ci = & get_instance();
        if (empty($date)) {
            return "2 months ago"; //"No date provided";
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();
        $unix_date = strtotime($date);

        // check validity of date
        if (empty($unix_date)) {
            return "2 months ago"; // "Bad date";
        }

        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }

}



if (!function_exists('get_inbox_message')) {

    function get_inbox_message() {
        $ci = & get_instance();
        $ci->db->select('MR.*, M.*');
        $ci->db->from('message_relationships AS MR');
        $ci->db->join('messages AS M', 'M.id = MR.message_id', 'left');
        $ci->db->where('MR.status', 1);
        $ci->db->where('MR.is_read', 0);
        $ci->db->where('MR.owner_id', logged_in_user_id());
        $ci->db->where('MR.receiver_id', logged_in_user_id());
        $ci->db->order_by('MR.id', 'desc');
        return $ci->db->get()->result();
    }
}

if (!function_exists('get_hostel_by_school')) {

    function get_hostel_by_school($school_id) {
        $ci = & get_instance();
        $ci->db->select('H.*');
        $ci->db->from('hostels AS H');
        $ci->db->where('H.school_id', $school_id);
       return $ci->db->get()->result();
    }

}

if (!function_exists('get_gallery_images')) {

    function get_gallery_images($school_id, $gallery_id) {
        $ci = & get_instance();
        $ci->db->select('GI.*');
        $ci->db->from('gallery_images AS GI');
        $ci->db->where('GI.school_id', $school_id);
        $ci->db->where('GI.gallery_id', $gallery_id);
       return $ci->db->get()->result();
    }
}


if (!function_exists('get_fee_amount')) {

    function get_fee_amount($income_head_id, $class_id) {
        $ci = & get_instance();
        return $ci->db->get_where('fees_amount', array('class_id'=>$class_id, 'income_head_id'=>$income_head_id))->row();
    }
}

if (!function_exists('get_invoice_paid_amount')) {

    function get_invoice_paid_amount($invoice_id){
        $ci = & get_instance();
        $ci->db->select('I.*, SUM(T.amount) AS paid_amount');
        $ci->db->from('invoices AS I');        
        $ci->db->join('transactions AS T', 'T.invoice_id = I.id', 'left');
        $ci->db->where('I.id', $invoice_id);         
        return $ci->db->get()->row(); 
    }
}

if (!function_exists('get_operation_by_module')) {

    function get_operation_by_module($module_id) {
        $ci = & get_instance();
        $ci->db->select('O.*');
        $ci->db->from('operations AS O');
        $ci->db->where('O.module_id', $module_id);
        return $ci->db->get()->result();
    }

}

if (!function_exists('get_permission_by_operation')) {

    function get_permission_by_operation($role_id, $operation_id) {
        $ci = & get_instance();
        $ci->db->select('P.*');
        $ci->db->from('privileges AS P');
        $ci->db->where('P.role_id', $role_id);
        $ci->db->where('P.operation_id', $operation_id);
        return $ci->db->get()->row();
    }
}

if (!function_exists('get_school_list')) {

    function get_school_list($district_id=null) {
        $ci = & get_instance();
        $ci->db->select('S.*');
        $ci->db->from('schools AS S');
        $ci->db->where('S.status', 1);
        if($ci->session->userdata('dadmin')==1){
            
            if($ci->session->userdata('dadmin_school_ids'))
            {
                $ci->db->where_in('S.id',$ci->session->userdata('dadmin_school_ids'));
            }
            else if($ci->session->userdata('zone_id'))
            {
                $ci->db->where('S.zone_id',$ci->session->userdata('zone_id'));
            }
            else if($ci->session->userdata('district_id'))
            {
                $ci->db->where('S.district_id', $ci->session->userdata('district_id'));
            }
        }
		if($district_id!= null){
			$ci->db->where('S.district_id', $district_id);
		}
        return $ci->db->get()->result();
    }
}

if (!function_exists('error_on')) {

    function error_on() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

}
if (!function_exists('get_lang')) {

    function get_lang() {
        $ci = & get_instance();
        $ci->lang->line('dashboard');
    }

}

if (!function_exists('get_default_lang_list')) {

    function get_default_lang_list($lang) {
        $lang_lists = array();
        $lang_lists['english'] = 'english';
        $lang_lists['bengali'] = 'bengali';
        $lang_lists['spanish'] = 'spanish';
        $lang_lists['arabic'] = 'arabic';
        $lang_lists['hindi'] = 'hindi';
        $lang_lists['urdu'] = 'urdu';
        $lang_lists['chinese'] = 'chinese';
        $lang_lists['japanese'] = 'japanese';
        $lang_lists['portuguese'] = 'portuguese';
        $lang_lists['russian'] = 'russian';
        $lang_lists['french'] = 'french';
        $lang_lists['korean'] = 'korean';
        $lang_lists['german'] = 'german';
        $lang_lists['italian'] = 'italian';
        $lang_lists['thai'] = 'thai';
        $lang_lists['hungarian'] = 'hungarian';
        $lang_lists['dutch'] = 'dutch';
        $lang_lists['latin'] = 'latin';
        $lang_lists['indonesian'] = 'indonesian';
        $lang_lists['turkish'] = 'turkish';
        $lang_lists['greek'] = 'greek';
        $lang_lists['persian'] = 'persian';
        $lang_lists['malay'] = 'malay';
        $lang_lists['telugu'] = 'telugu';
        $lang_lists['tamil'] = 'tamil';
        $lang_lists['gujarati'] = 'gujarati';
        $lang_lists['polish'] = 'polish';
        $lang_lists['ukrainian'] = 'ukrainian';
        $lang_lists['panjabi'] = 'panjabi';
        $lang_lists['romanian'] = 'romanian';
        $lang_lists['burmese'] = 'burmese';
        $lang_lists['yoruba'] = 'yoruba';
        $lang_lists['hausa'] = 'hausa';

        if (isset($lang_lists[$lang]) && !empty($lang_lists[$lang])) {
            return true;
        } else {
            return FALSE;
        }
    }

}


if (!function_exists('get_timezones')) {
    function get_timezones() {
        $timezones = array(           
            'Pacific/Midway' => "(GMT-11:00) Midway Island",
            'US/Samoa' => "(GMT-11:00) Samoa",
            'US/Hawaii' => "(GMT-10:00) Hawaii",
            'US/Alaska' => "(GMT-09:00) Alaska",
            'US/Pacific' => "(GMT-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana' => "(GMT-08:00) Tijuana",
            'US/Arizona' => "(GMT-07:00) Arizona",
            'US/Mountain' => "(GMT-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua' => "(GMT-07:00) Chihuahua",
            'America/Mazatlan' => "(GMT-07:00) Mazatlan",
            'America/Mexico_City' => "(GMT-06:00) Mexico City",
            'America/Monterrey' => "(GMT-06:00) Monterrey",
            'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
            'US/Central' => "(GMT-06:00) Central Time (US &amp; Canada)",
            'US/Eastern' => "(GMT-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana' => "(GMT-05:00) Indiana (East)",
            'America/Bogota' => "(GMT-05:00) Bogota",
            'America/Lima' => "(GMT-05:00) Lima",
            'America/Caracas' => "(GMT-04:30) Caracas",
            'Canada/Atlantic' => "(GMT-04:00) Atlantic Time (Canada)",
            'America/La_Paz' => "(GMT-04:00) La Paz",
            'America/Santiago' => "(GMT-04:00) Santiago",
            'Canada/Newfoundland' => "(GMT-03:30) Newfoundland",
            'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
            'Greenland' => "(GMT-03:00) Greenland",
            'Atlantic/Stanley' => "(GMT-02:00) Stanley",
            'Atlantic/Azores' => "(GMT-01:00) Azores",
            'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
            'Africa/Casablanca' => "(GMT) Casablanca",
            'Europe/Dublin' => "(GMT) Dublin",
            'Europe/Lisbon' => "(GMT) Lisbon",
            'Europe/London' => "(GMT) London",
            'Africa/Monrovia' => "(GMT) Monrovia",
            'Europe/Amsterdam' => "(GMT+01:00) Amsterdam",
            'Europe/Belgrade' => "(GMT+01:00) Belgrade",
            'Europe/Berlin' => "(GMT+01:00) Berlin",
            'Europe/Bratislava' => "(GMT+01:00) Bratislava",
            'Europe/Brussels' => "(GMT+01:00) Brussels",
            'Europe/Budapest' => "(GMT+01:00) Budapest",
            'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
            'Europe/Ljubljana' => "(GMT+01:00) Ljubljana",
            'Europe/Madrid' => "(GMT+01:00) Madrid",
            'Europe/Paris' => "(GMT+01:00) Paris",
            'Europe/Prague' => "(GMT+01:00) Prague",
            'Europe/Rome' => "(GMT+01:00) Rome",
            'Europe/Sarajevo' => "(GMT+01:00) Sarajevo",
            'Europe/Skopje' => "(GMT+01:00) Skopje",
            'Europe/Stockholm' => "(GMT+01:00) Stockholm",
            'Europe/Vienna' => "(GMT+01:00) Vienna",
            'Europe/Warsaw' => "(GMT+01:00) Warsaw",
            'Europe/Zagreb' => "(GMT+01:00) Zagreb",
            'Europe/Athens' => "(GMT+02:00) Athens",
            'Europe/Bucharest' => "(GMT+02:00) Bucharest",
            'Africa/Cairo' => "(GMT+02:00) Cairo",
            'Africa/Harare' => "(GMT+02:00) Harare",
            'Europe/Helsinki' => "(GMT+02:00) Helsinki",
            'Europe/Istanbul' => "(GMT+02:00) Istanbul",
            'Asia/Jerusalem' => "(GMT+02:00) Jerusalem",
            'Europe/Kiev' => "(GMT+02:00) Kyiv",
            'Europe/Minsk' => "(GMT+02:00) Minsk",
            'Europe/Riga' => "(GMT+02:00) Riga",
            'Europe/Sofia' => "(GMT+02:00) Sofia",
            'Europe/Tallinn' => "(GMT+02:00) Tallinn",
            'Europe/Vilnius' => "(GMT+02:00) Vilnius",
            'Asia/Baghdad' => "(GMT+03:00) Baghdad",
            'Asia/Kuwait' => "(GMT+03:00) Kuwait",
            'Africa/Nairobi' => "(GMT+03:00) Nairobi",
            'Asia/Riyadh' => "(GMT+03:00) Riyadh",
            'Asia/Tehran' => "(GMT+03:30) Tehran",
            'Europe/Moscow' => "(GMT+04:00) Moscow",
            'Asia/Baku' => "(GMT+04:00) Baku",
            'Europe/Volgograd' => "(GMT+04:00) Volgograd",
            'Asia/Muscat' => "(GMT+04:00) Muscat",
            'Asia/Tbilisi' => "(GMT+04:00) Tbilisi",
            'Asia/Yerevan' => "(GMT+04:00) Yerevan",
            'Asia/Kabul' => "(GMT+04:30) Kabul",
            'Asia/Karachi' => "(GMT+05:00) Karachi",
            'Asia/Tashkent' => "(GMT+05:00) Tashkent",
            'Asia/Kolkata' => "(GMT+05:30) Kolkata",
            'Asia/Kathmandu' => "(GMT+05:45) Kathmandu",
            'Asia/Yekaterinburg' => "(GMT+06:00) Ekaterinburg",
            'Asia/Almaty' => "(GMT+06:00) Almaty",
            'Asia/Dhaka' => "(GMT+06:00) Dhaka",
            'Asia/Novosibirsk' => "(GMT+07:00) Novosibirsk",
            'Asia/Bangkok' => "(GMT+07:00) Bangkok",
            'Asia/Jakarta' => "(GMT+07:00) Jakarta",
            'Asia/Krasnoyarsk' => "(GMT+08:00) Krasnoyarsk",
            'Asia/Chongqing' => "(GMT+08:00) Chongqing",
            'Asia/Hong_Kong' => "(GMT+08:00) Hong Kong",
            'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
            'Australia/Perth' => "(GMT+08:00) Perth",
            'Asia/Singapore' => "(GMT+08:00) Singapore",
            'Asia/Taipei' => "(GMT+08:00) Taipei",
            'Asia/Ulaanbaatar' => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi' => "(GMT+08:00) Urumqi",
            'Asia/Irkutsk' => "(GMT+09:00) Irkutsk",
            'Asia/Seoul' => "(GMT+09:00) Seoul",
            'Asia/Tokyo' => "(GMT+09:00) Tokyo",
            'Australia/Adelaide' => "(GMT+09:30) Adelaide",
            'Australia/Darwin' => "(GMT+09:30) Darwin",
            'Asia/Yakutsk' => "(GMT+10:00) Yakutsk",
            'Australia/Brisbane' => "(GMT+10:00) Brisbane",
            'Australia/Canberra' => "(GMT+10:00) Canberra",
            'Pacific/Guam' => "(GMT+10:00) Guam",
            'Australia/Hobart' => "(GMT+10:00) Hobart",
            'Australia/Melbourne' => "(GMT+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
            'Australia/Sydney' => "(GMT+10:00) Sydney",
            'Asia/Vladivostok' => "(GMT+11:00) Vladivostok",
            'Asia/Magadan' => "(GMT+12:00) Magadan",
            'Pacific/Auckland' => "(GMT+12:00) Auckland",
            'Pacific/Fiji' => "(GMT+12:00) Fiji",
        );

        return $timezones;
    }
}


if (!function_exists('get_date_format')) {
    function get_date_format() {
        
        $date = array();
        $date['Y-m-d'] = '2001-03-15';
        $date['d-m-Y'] = '15-03-2018';
        $date['d/m/Y'] = '15/03/2018';
        $date['m/d/Y'] = '03/15/2018';
        $date['m.d.Y'] = '03.10.2018';
        $date['j, n, Y'] = '14, 7, 2018';
        $date['F j, Y'] = 'July 15, 2018';
        $date['M j, Y'] = 'Jul 13, 2018';
        $date['j M, Y'] = '13 Jul, 2018';
        
        return $date;
    }
}


if (!function_exists('get_mail_protocol')) {
    function get_mail_protocol() {
        
        $protocol = array();
        $protocol['mail'] = 'mail';
        $protocol['sendmail'] = 'sendmail';
        $protocol['smtp'] = 'smtp';        
        
        return $protocol;
    }
}

if (!function_exists('get_smtp_crypto')) {
    function get_smtp_crypto() {
        
        $smtp_crypto = array();
        $smtp_crypto['tls'] = 'tls';
        $smtp_crypto['ssl'] = 'ssl';
        
        return $smtp_crypto;
    }
}

if (!function_exists('get_mail_type')) {
    function get_mail_type() {
        
        $mail_type = array();
        $mail_type['text'] = 'text';
        $mail_type['html'] = 'html';
        
        return $mail_type;
    }
}

if (!function_exists('get_char_set')) {
    function get_char_set() {
        
        $char_set = array();
        $char_set['utf-8'] = 'utf-8';
        $char_set['iso-8859-1'] = 'iso-8859-1';
        
        return $char_set;
    }
}

if (!function_exists('get_email_priority')) {
    function get_email_priority() {
        
        $priority = array();
        $priority['1'] = 'highest';
        $priority['3'] = 'normal';
        $priority['5'] = 'lowest';       
        
        return $priority;
    }
}

if (!function_exists('get_card_bottom_text_align')) {
    function get_card_bottom_text_align() {
        
        $text_align = array();
        $text_align['center'] = 'center';
        $text_align['left'] = 'left';
        $text_align['right'] = 'right';       
        
        return $text_align;
    }
}
if (!function_exists('is_image')) {
    function is_image($imagefile)
    {
        if(@is_array(getimagesize($imagefile))){
            $image = true;
        } else {
            $image = false;
        }
    }
}


if (!function_exists('webpConverter')) {
    function webpConverter($file, $compression_quality = 80,$width = null, $height = null)
    {
        // check if file exists
        if (!file_exists($file)) {
            return false;
        }
        $file_type = exif_imagetype($file);
        //https://www.php.net/manual/en/function.exif-imagetype.php
        //exif_imagetype($file);
        // 1    IMAGETYPE_GIF
        // 2    IMAGETYPE_JPEG
        // 3    IMAGETYPE_PNG
        // 6    IMAGETYPE_BMP
        // 15   IMAGETYPE_WBMP
        // 16   IMAGETYPE_XBM
        $output_file =  $file . '.webp';
        if (file_exists($output_file)) {
            return $output_file;
        }
        if (function_exists('imagewebp')) {
            switch ($file_type) {
                case '1': //IMAGETYPE_GIF
                    $image = imagecreatefromgif($file);
                    break;
                case '2': //IMAGETYPE_JPEG
                    $image = imagecreatefromjpeg($file);
                    break;
                case '3': //IMAGETYPE_PNG
                        $image = imagecreatefrompng($file);
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                        break;
                case '6': // IMAGETYPE_BMP
                    $image = imagecreatefrombmp($file);
                    break;
                case '15': //IMAGETYPE_Webp
                return false;
                    break;
                case '16': //IMAGETYPE_XBM
                    $image = imagecreatefromxbm($file);
                    break;
                default:
                    return false;
            }
            // Save the image
            if($width && $height)
            {
                $image = imagescale($image , $width , $height); // width=500 and height = 400
            }

            $result = imagewebp($image, $output_file, $compression_quality);
            if (false === $result) {
                return false;
            }
            // Free up memory
            imagedestroy($image);
            @unlink($file);
            return $output_file;
        } 
        return false;
    }
}
if (!function_exists('get_filename')) {
    function get_filename($file,$extentsion=true) {
        $path_parts = pathinfo($file);
        $filename   = isset($path_parts['filename']) && $path_parts['filename'] ? $path_parts['filename']: "" ;
        $filenamewithextension   = isset($path_parts['basename'])  && $path_parts['basename'] ? $path_parts['basename'] : "";
        if($extentsion)
        {       
            return $filenamewithextension;
        }
        return  $filename;
    }

}
if (!function_exists('convert_to_webp')) {
    function convert_to_webp($file,$filename_only=false) {
        $path_parts = pathinfo($file);
        $directory =  $path_parts['dirname'];
        $filename   = $path_parts['filename'];
        if(!$directory || !$filename)
        {       
            return false;
        }
        $image=  imagecreatefromjpeg($file);
        pathinfo($path, PATHINFO_FILENAME);

        ob_start();
        imagejpeg($image,NULL,100);
        $cont=  ob_get_contents();
        ob_end_clean();
        imagedestroy($image);
        $content =  imagecreatefromstring($cont);
        $converted_file = "$directory/$filename.webp";
        if(!imagewebp($content,$converted_file))
        {
            return false;
        }
        unlink($file);

        imagedestroy($content);
        if($filename_only)
        {
            return "$filename.webp";
        }
        return $converted_file;
    }
}


if (!function_exists('check_permission')) {

    function check_permission($action, $methode_operation = false, $debug = false) {

        $ci = & get_instance();
        $role_id = $ci->session->userdata('role_id');
        if($methode_operation)
        {
            $operation_slug = $ci->router->fetch_method();
        }
        else
        {
            $operation_slug = $ci->router->fetch_class();
        }
        $module_slug = $ci->router->fetch_module();

        
        if ($module_slug == '') {
            $module_slug = $operation_slug;
        }
        $module_slug = 'my_' . $module_slug;
        if ($debug)
        {
            var_dump($operation_slug, $module_slug);
        }
        $data = $ci->config->item($operation_slug, $module_slug);
        if ($debug)
        {
            var_dump( $data);
            die();
        }
       
        $result = $data[$role_id] ?? array();
        if (!empty($result)) {
            $array = explode('|', $result);
           
            if (!$array[$action]) {
                error($ci->lang->line('permission_denied'));
                redirect('dashboard');
            }
        } else {
            error($ci->lang->line('permission_denied'));
            redirect('dashboard');
        }
        return TRUE;
    }

}

if (!function_exists('has_permission')) {

    function has_permission($action, $module_slug = null, $operation_slug = null) {

        $ci = & get_instance();
        $role_id = $ci->session->userdata('role_id');

        if ($module_slug == '') {
            $module_slug = $operation_slug;
        }

        $module_slug = 'my_' . $module_slug;

        $data = $ci->config->item($operation_slug, $module_slug);
        //var_dump( $data);

        $result = @$data[$role_id];

        if (!empty($result)) {
            $array = explode('|', $result);
            return $array[$action];
        } else {
            return FALSE;
        }
    }

}


if (!function_exists('create_log')) {

    function create_log($activity = null) {
        return false;
        $ci = & get_instance();
        $data = array();
        
        if($ci->session->userdata('role_id') == SUPER_ADMIN){
            $data['school_id'] = 0;
        }else{
            $data['school_id'] = $ci->session->userdata('school_id');            
        }
        
        $data['user_id'] = logged_in_user_id();
        $data['role_id'] = logged_in_role_id(); 
        $user = get_user_by_id($data['user_id']);
        
        $data['name'] = $user->name ?? 'Unknown';
        $data['phone'] = $user->phone ?? 'Unknown';
        $data['email'] = $user->email ?? 'Unknown';
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['activity'] = $activity;
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $ci->db->insert('activity_logs', $data);
    }
}
if (!function_exists('get_default_company')) {

    function get_default_company($school_id) {

        $ci = & get_instance();
        if($school_id)
        {
            $school =  $ci->db->get_school_by_id($school_id);
        }
        return isset($school->category)  && $school->category ? $school->category : "School Samiti";
    }
}

if (!function_exists('download_send_headers')) {

    function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
    
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
}
if (!function_exists('array2csv')) {

    function array2csv(array $array)
    {  

       if (count($array) == 0) {
         return null;
       }
       ob_start();
       $df = fopen("php://output", 'w');
       fputcsv($df, array_keys(reset($array)));
       foreach ($array as $row) {
          fputcsv($df, $row);
       }
       fclose($df);
       return ob_get_clean();
    }
}




/* STRICT DATA ACCESS START*/


if (!function_exists('get_guardian_access_data')) {
    
 function get_guardian_access_data($type = NULL){
       
        
        $ci = & get_instance();
        
         if($ci->session->userdata('role_id') != GUARDIAN){
             return FALSE;
         }
        
        $school_id = $ci->session->userdata('school_id'); 
        $guardian_id = $ci->session->userdata('profile_id'); 
        $school =  $ci->db->get_where('schools', array('id'=>$school_id))->row();
        
        $ci->db->select('E.student_id, E.roll_no, E.class_id, E.section_id, C.name AS class_name, SE.name AS section');
        $ci->db->from('enrollments AS E');
        $ci->db->join('students AS S', 'S.id = E.student_id', 'left');
        $ci->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $ci->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $ci->db->where('E.academic_year_id', $school->academic_year_id);
        $ci->db->where('S.guardian_id', $guardian_id);
        $ci->db->where('S.school_id', $school_id);
        $result = $ci->db->get()->result();   
        $data = array();
        
       if(!empty($result )){
            if($type == 'class'){

                foreach($result as $obj){
                    $data[] = $obj->class_id;
                }
                
            }elseif($type == 'section'){

                foreach($result as $obj){
                    $data[] = $obj->section_id;
                }
            
            }elseif($type == 'student'){

                foreach($result as $obj){
                    $data[] = $obj->student_id;
                }
            }elseif($type == 'subject'){

                foreach($result as $obj){
                    $data[] = $obj->class_id;
                }  
                
                // Getting subject ids by classes
                $ci->db->select('S.*');
                $ci->db->from('subjects AS S');            
                $ci->db->where_in('S.class_id', implode(',', $data)); 
                $ci->db->order_by("S.id", "ASC");
                $subjects= $ci->db->get()->result();

                foreach($subjects as $obj){
                    $data[] = $obj->teacher_id;
                } 
                
            }
       }
       
       return $data;        
   }   
}


if (!function_exists('get_teacher_access_data')) {
    
 function get_teacher_access_data($type = NULL){
       
        $ci = & get_instance();
        
         if($ci->session->userdata('role_id') != TEACHER){
             return FALSE;
         }
        
        $school_id = $ci->session->userdata('school_id'); 
        $teacher_id = $ci->session->userdata('profile_id'); 
        $school =  $ci->db->get_where('schools', array('id'=>$school_id))->row();
               
        $data = array();
        
        if($type == 'student'){                

            $ci->db->select('R.*, S.name AS subject, T.name AS teacher, C.name AS class_name');
            $ci->db->from('routines AS R');
            $ci->db->join('subjects AS S', 'S.id = R.subject_id', 'left');
            $ci->db->join('teachers AS T', 'T.id = R.teacher_id', 'left');
            $ci->db->join('classes AS C', 'C.id = R.class_id', 'left');
            $ci->db->where('R.teacher_id', $ci->session->userdata('profile_id')); 
            $ci->db->where('R.school_id', $school_id); 
            $ci->db->order_by("R.id", "ASC");
            $result = $ci->db->get()->result();
            
            foreach($result as $obj){
                $data[] = $obj->class_id;
            }
            
        }elseif($type == 'subject'){
            
            $ci->db->select('S.*');
            $ci->db->from('subjects AS S');            
            $ci->db->where('S.teacher_id', $ci->session->userdata('profile_id')); 
            $ci->db->order_by("S.id", "ASC");
            $result = $ci->db->get()->result();
            
            foreach($result as $obj){
                $data[] = $obj->id;
            }            
        }

       
       return $data;        
   }   
}


if (!function_exists('get_student_access_data')) {
    
 function get_student_access_data($type = NULL){
       
        $ci = & get_instance();
         if($ci->session->userdata('role_id') != STUDENT){
             return FALSE;
         }
        
        $school_id = $ci->session->userdata('school_id'); 
        $student_id = $ci->session->userdata('profile_id'); 
        $school =  $ci->db->get_where('schools', array('id'=>$school_id))->row();
               
        $data = array();
        
        if($type == 'student'){                

            $ci->db->select('R.*, S.name AS subject, T.name AS teacher, C.name AS class_name');
            $ci->db->from('routines AS R');
            $ci->db->join('subjects AS S', 'S.id = R.subject_id', 'left');
            $ci->db->join('teachers AS T', 'T.id = R.teacher_id', 'left');
            $ci->db->join('classes AS C', 'C.id = R.class_id', 'left');
            $ci->db->where('R.teacher_id', $ci->session->userdata('profile_id')); 
            $ci->db->order_by("R.id", "ASC");
            $result = $ci->db->get()->result();
            
            foreach($result as $obj){
                $data[] = $obj->class_id;
            }
        }elseif($type == 'subject'){
            
            $ci->db->select('S.*');
            $ci->db->from('subjects AS S');            
            $ci->db->where('S.class_id', $ci->session->userdata('class_id')); 
            $ci->db->order_by("S.id", "ASC");
            $result = $ci->db->get()->result();
            
            foreach($result as $obj){
                $data[] = $obj->teacher_id;
            }            
        }

       
       return $data;        
   }   
}
if (!function_exists('readmorelinkUser')) {

    function readmorelinkUser($string, $link = false)
    {
        $string = strip_tags($string);
        if (strlen($string) > 150) {

            // truncate string
            $stringCut = substr($string, 0, 150);
            $endPoint  = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);

            $string .= ($link) ? "<a href='#" . $link . "' data-toggle='collapse' aria-expanded='false' aria-controls='" . $link . "' >Read more...</a>" : "....";
        }

        return $string;
    }

}
if (!function_exists('getVoucherCategory')) {

    function getVoucherCategory()
    {
        $cat=array('Inventory'=>'Inventory','Hostel'=>'Hostel','School Samiti'=>'School Samiti','Jila Samiti'=>'Jila Samiti');		
        return $cat;
    }

}
if (!function_exists('get_religion')) {

    function get_religion() {        
        $cat=array('hindu'=>'HINDU','muslim'=>'MUSLIM','chirstion'=>'CHIRSTION','jain'=>'JAIN','sikhh'=>'SIKHH','parsi'=>'PARSI');		
        return $cat;
    }

}
if (!function_exists('get_qualification')) {

    function get_qualification() {        
        $cat=array('UG'=>'UG','PG'=>'PG','BED'=>'BED','MED'=>'MED','OTHER'=>'OTHER');		
        return $cat;
    }

}
if (!function_exists('get_student_facilities')) {

    function get_student_facilities() {        
        $cat=array('Hostel'=>'Hostel','Library'=>'Library','Transport'=>'Transport');		
        return $cat;
    }

}
if (!function_exists('get_laboratory_facilities')) {

    function get_laboratory_facilities() {        
        $cat=array('Computer'=>'Computer','Chemistry'=>'Chemistry','Physic'=>'Physic','Biology'=>'Biology','ATL'=>'ATL','Maths'=>'Maths','Home Science'=>'Home Science','Other'=>'Other');		
        return $cat;
    }

}
if (!function_exists('get_building_types')) {

    function get_building_types() {        
        $cat=array('Own'=>'Own','Rental'=>'Rental','Allotment By Government'=>'Allotment By Government','Donation'=>'Donation');		
        return $cat;
    }

}
if (!function_exists('get_school_level')) {

    function get_school_level() {        
        $cat=array('Shishu Vatika'=>'Shishu Vatika','Primary'=>'Primary','Upper Primary'=>'Upper Primary','Secondary'=>'Secondary','Sr. Secondary'=>'Sr. Secondary');		
        return $cat;
    }

}
if (!function_exists('get_hostel_facilities')) {

    function get_hostel_facilities() {        
        $cat=array('Play Ground'=>'Play Ground','Library'=>'Library','Mess'=>'Mess','Medical'=>'Medical','Transport'=>'Transport','Entertainment'=>'Entertainment','Coaching Class'=>'Coaching Class','Other'=>'Other');		
        return $cat;
    }

}
if (!function_exists('checkIsAValidDate')) {

function checkIsAValidDate($date){
    return (bool)strtotime($date);
}
}
function numberToCurrency($number)
{
    $negative_sign = "";
    if($number < 1)
    {
        $number = abs($number);
        $negative_sign = "-";
    }  
	$number=round($number,2);	
    if(setlocale(LC_MONETARY, 'en_IN'))
      return money_format('%.0n', $number);
    else {
      $explrestunits = "" ;
      $number = explode('.', $number);
      $num = $number[0];
      if(strlen($num)>3){
          $lastthree = substr($num, strlen($num)-3, strlen($num));
          $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
          $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
          $expunit = str_split($restunits, 2);
          for($i=0; $i<sizeof($expunit); $i++){
              // creates each of the 2's group and adds a comma to the end
              if($i==0)
              {
                  $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
              }else{
                  $explrestunits .= $expunit[$i].",";
              }
          }
          $thecash = $explrestunits.$lastthree;
      } else {
          $thecash = $num;
      }
      if(!empty($number[1])) {
      	if(strlen($number[1]) == 1) {
            return '₹ ' .$negative_sign.$thecash . '.' . $number[1] . '0';
        } else if(strlen($number[1]) == 2){
            return '₹ ' .$negative_sign.$thecash . '.' . $number[1];
        } else {
            return 'cannot handle decimal values more than two digits...';
        }
      } else {
        return '₹ ' .$negative_sign.$thecash.'.00';
    }
    }
}
if (!function_exists('update_ledger_opening_balance_old')) {

    function update_ledger_opening_balance_old($ledger_ids,$school_id) {    
      
        // return false;    
        $ci = & get_instance();
        $ledger_ids = array_unique($ledger_ids);
        $ledgers_processed =[];
         $ci->load->model('Accountledgers_Model', 'accountledgers', true);			
		 $ci->load->model('Accountgroups_Model', 'accountgroups', true);	
          $ci->load->model('administrator/Financialyear_Model', 'year', true);
       
        
       
        $financial_year= $ci->accountledgers->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));		
        $current_financial_year_id =  $financial_year->id;
        if(empty($financial_year))
        {
           return "Invalid current financial year";
        }
        $future_financial_year = $ci->year->get_future_financial_year( $current_financial_year_id );
        if(empty($future_financial_year))
        {
            return "No future financial year";
        }
        $future_financial_year_id =  $future_financial_year->id;
        if(strpos($financial_year->session_year,"->"))	
        {
            $arr=explode("->",$financial_year->session_year);
            $f_start=date("Y-m-d",strtotime($arr[0]));		
        }
        else
        {
            $arr=explode("-",$financial_year->session_year);
            $date_exploded = explode(" ",$arr[0]);
            if(count($date_exploded)>2)
            {
                $f_start=date("Y-m-d",strtotime($arr[0]));		
            }
            else
            {
                $f_start=date("Y-m-d",strtotime("1 ".$arr[0]));		
            }
        }		  
        $start_date =strtotime($f_start);

        $start_date= date("Y-m-d",strtotime('-1 day',$start_date));
        // debug_a($start_date);

        if(!$school_id  ||  !$start_date)
        {
            return "No school and or no start date";
        }

        $account_ledger_details = array();
        $account_ledger_details_ids = array();
        $account_ledger_details_raw = $ci->accountgroups->get_list('account_ledger_details', array('financial_year_id'=>$future_financial_year_id ));	
        // echo $ci->db->last_query();
        // die();
        foreach( $account_ledger_details_raw as  $account_ledger_details_single)
        {
            if(!isset($account_ledger_details_ids[$future_financial_year_id ][$account_ledger_details_single->ledger_id])) $account_ledger_details_ids[$future_financial_year_id ][$account_ledger_details_single->ledger_id] = $account_ledger_details_single->id;
        }
        // debug_a( $account_ledger_details_ids );
       
       
           $ledgers= $ci->accountledgers->get_accountledgers_by_ledger_ids($ledger_ids,$current_financial_year_id);;
           $j=0;

           //print_r($ledgers); exit;
           foreach($ledgers as $l){
               // get current balance	
               $in_arr=array();
               $in_arr['id']=$account_ledger_details_ids[$future_financial_year_id][$l->id] ?? null;
               $in_arr['ledger_id']=$l->id;
               $in_arr['financial_year_id']=$future_financial_year_id;
              
                  $cbalance= $ci->accountledgers->get_effective_balance_by_ledger($l->id,$l->opening_balance,$l->opening_cr_dr,null,null,null,null,null,$current_financial_year_id);
                  $cb=$cbalance;	
                  if($l->type_id == 3)
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

              
               if($l->type_id == 4)
               {
                    $in_arr['opening_cr_dr']= "CR";
               }
               elseif($l->type_id == 3)
               {
                    $in_arr['opening_cr_dr']= "DR";
               }
               else
               {
                    $in_arr['opening_cr_dr']=$l->dr_cr;
               }
               $account_ledger_details[] = $in_arr;
           }

        // 
        if(!empty($account_ledger_details))
        {
            
            $ledgers= $ci->accountledgers->update_batch("account_ledger_details",$account_ledger_details,'id');
        }
       return "succsess";
    }

}
if (!function_exists('update_ledger_opening_balance')) {

    function update_ledger_opening_balance($ledger_ids,$school_id) {    
        // return false;    
       
        $ci = & get_instance();
        $ledger_ids = array_unique($ledger_ids);
        $ledgers_processed =[];
         $ci->load->model('Accountledgers_Model', 'accountledgers', true);			
		 $ci->load->model('Accountgroups_Model', 'accountgroups', true);	
          $ci->load->model('administrator/Financialyear_Model', 'year', true);
       
        
       
        $financial_year= $ci->accountledgers->get_single('financial_years',array('school_id'=>$school_id,'is_running'=>1));		
        $current_financial_year_id =  $financial_year->id;
        if(empty($financial_year))
        {
           return "Invalid current financial year";
        }
        $future_financial_year = $ci->year->get_future_financial_year( $current_financial_year_id );
        if(empty($future_financial_year))
        {
            return "No future financial year";
        }
        $future_financial_year_id =  $future_financial_year->id;
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
        $start_date =strtotime($f_start);

        $start_date= date("Y-m-d",strtotime('-1 day',$start_date));
        // debug_a($start_date);

        if(!$school_id  ||  !$start_date)
        {
            return "No school and or no start date";
        }

        $account_ledger_details = array();
        $account_ledger_details_ids = array();
        $ledgers_list = $ci->accountledgers->get_accountledger_list($school_id,$financial_year->id,null,null,null,null,null,null,null,null,$ledger_ids);
        $ledgers_list_updated = [];
        foreach($ledgers_list as $ledger)
        {
            $ledgers_list_updated[$ledger->id] = $ledger;
            $ledger_ids[] = $ledger->id;
        }
        $account_ledger_details_raw = $ci->accountgroups->get_list('account_ledger_details', array('financial_year_id'=>$future_financial_year_id ));	
        // echo $ci->db->last_query();
        // die();
        foreach( $account_ledger_details_raw as  $account_ledger_details_single)
        {
            if(!isset($account_ledger_details_ids[$future_financial_year_id ][$account_ledger_details_single->ledger_id])) $account_ledger_details_ids[$future_financial_year_id ][$account_ledger_details_single->ledger_id] = $account_ledger_details_single->id;
        }
        // debug_a( $account_ledger_details_ids );
       
       
        $ledger_transactions=$ci->accountledgers->get_ledger_with_amount_list_with_balance($ledger_ids, $f_start,$f_end,1);
		// echo $ci->db->last_query();
		// die();
		 $ledgers = array();	

		 $transations_list_updated = [];
		 $tr_ids = [];
		 $ledger_tr_ids = [];
		 foreach($ledger_transactions as $ledger_transaction)
		 {
			$transactions[] =  $ledger_transaction;
			if($ledger_transaction->cancelled ==1) continue;
			if(!isset($ledgers_transactions_updated[$ledger_transaction->ledger_id]))
			{
				$ledgers_transactions_updated[$ledger_transaction->ledger_id] =  array();
				$ledger_tr_ids[$ledger_transaction->ledger_id] =  array();
			}
			$ledgers_transactions_updated[$ledger_transaction->ledger_id][] =  $ledger_transaction;
			
			if(!in_array($ledger_transaction->id,$tr_ids) )
			{
				$tr_ids[] = $ledger_transaction->id;
			}
		 }
		 
		 $other_transations_list_updated = [];

		 $other_transactions=$ci->accountledgers->get_ledger_with_amount_list_with_balance_excluded($ledger_ids,null, $f_start,$f_end,1);
			// echo "<pre>";
			 //echo $ci->db->last_query();
			//  var_dump($other_transactions);
			//  die();
			// debug_a($other_transactions,"Others");

		 foreach($other_transactions as $other_transaction)
		 {
			$transactions[] =  $other_transaction;
			if($other_transaction->cancelled ==1) continue;
			if(!isset($other_transations_list_updated[$other_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id] =  array();
			}
			if (!isset($ledger_tr_ids[$other_transaction->ledger_id]))
			{
				$ledger_tr_ids[$other_transaction->ledger_id] = array();

			}

			if( !in_array($other_transaction->transaction_id,$ledger_tr_ids[$other_transaction->ledger_id]))
			{
				$other_transations_list_updated[$other_transaction->ledger_id][] =  $other_transaction;

			}
			
		 }
           $j=0;

           //print_r($ledgers); exit;
           foreach($ledgers_list_updated as $ledger_id => $ledger){
               // get current balance	
               if(!isset($ledgers[$ledger_id]))
               {
               
                   $ledgers[$ledger_id] = $ledger;
                   
                   $ledgers[$ledger_id]->effective_balance =0;
               }
               $in_arr=array();
               $in_arr['id']= $account_ledger_details_ids[$future_financial_year_id][$ledger_id] ?? null;
               $in_arr['ledger_id']=$ledger_id;
               $in_arr['financial_year_id']=$future_financial_year_id;
              
                 

               $ledger_transactions = isset($ledgers_transactions_updated[$ledger_id]) && $ledgers_transactions_updated[$ledger_id] ? $ledgers_transactions_updated[$ledger_id] :  array();
				
				   $grand_total = 0;
				   foreach($ledger_transactions as $ledger_transaction){
					   
					   if($ledger_transaction->head_cr_dr == "DR")
					   {
						   $grand_total= $grand_total-($ledger_transaction->total_amount);
					   }
					   else
					   {
						   $grand_total= $grand_total+($ledger_transaction->total_amount);
					   }
					   
				   }
				   $other_transactions = isset($other_transations_list_updated[$ledger_id]) && $other_transations_list_updated[$ledger_id] ? $other_transations_list_updated[$ledger_id] :  array();
				   foreach($other_transactions as $other_transaction){
					   
					   if($other_transaction->head_cr_dr == "DR")
					   {
						   $grand_total= $grand_total+($other_transaction->amount);
					   }
					   else
					   {
						   $grand_total= $grand_total-($other_transaction->amount);
					   }
				   }
				   if($ledgers[$ledger_id]->opening_cr_dr =='DR'){
					   $opening_balance = -($ledger->opening_balance);
					   $ledgers[$ledger_id]->effective_balance_cr_dr='DR';
				   }
				   else{
					   $opening_balance = $ledger->opening_balance;
					   $ledgers[$ledger_id]->effective_balance_cr_dr='CR';
				   }
				   $final_amount=$opening_balance+$grand_total;
				   $ledgers[$ledger_id]->effective_balance =  $final_amount;
                   if( $ledgers[$ledger_id]->effective_balance < 0){
                     $ledgers[$ledger_id]->effective_balance_cr_dr= "DR";
                    }
                    else if($ledgers[$ledger_id]->effective_balance > 0){
                        $ledgers[$ledger_id]->effective_balance_cr_dr= "CR";	
                    }
                   
                   $in_arr['opening_balance']=$ledgers[$ledger_id]->effective_balance;
                   $in_arr['opening_balance']=abs($ledgers[$ledger_id]->effective_balance);
                   $in_arr['opening_cr_dr']=  $ledgers[$ledger_id]->effective_balance_cr_dr;
                    $account_ledger_details[] = $in_arr;
           }
          
           
        // 
        if(!empty($account_ledger_details))
        {
            
            $ledgers= $ci->accountledgers->update_batch("account_ledger_details",$account_ledger_details,'id');
        }
       return "succsess";
    }
    if (!function_exists('development_mode')) {
        function development_mode() {
            if ($_SESSION['development_mode'] ?? false)
            {

            }
            else
            {
                redirect('dashboard');
            }
        }    

    }
    if (!function_exists('is_dev')) {
        function is_dev() {
            return ($_SESSION['development_mode'] ?? false);
        }    

    }
}
/*STRICT DATA ACCESS END*/