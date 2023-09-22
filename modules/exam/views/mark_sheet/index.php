
<?php  $class_name = "" ?>
<?php  $section_name = "" ?>
<style>
.float-container {
    width: 100%;
    overflow: hidden;
    background: #f0f3f5 !important;
    text-align: center;
    background: #f0f3f5 !important;
}
.float-child img {
    background: #337ab7;
    padding: 3px;
    border-radius: 5px;
    /* float: right; */
}
.float-child {
    float: left;
}  
@media print {
  .footer-div {page-break-after: always;}
}
    </style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-file-text-o"></i><small> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('mark_sheet'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                  
                <div class="clearfix"></div>
               
            </div>
        
            
                
          
            
            <div class="x_content no-print"> 
                <?php echo form_open_multipart(site_url('exam/marksheet/index'), array('name' => 'marksheet', 'id' => 'marksheet', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row"> 
                        
                    <?php $this->load->view('layout/school_list_filter'); ?>  
                    
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('academic_year'); ?>  <span class="required">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12" name="academic_year_id" id="academic_year_id" required="required">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php foreach ($academic_years as $obj) { ?>
                                <option value="<?php echo $obj->id; ?>" <?php if(isset($academic_year_id) && $academic_year_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->session_year; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php if($this->session->userdata('role_id') != STUDENT ){ ?>    
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group"> 
                                <?php $teacher_student_data = get_teacher_access_data('student'); ?>
                                <?php $guardian_class_data = get_guardian_access_data('class'); ?>
                                <div><?php echo $this->lang->line('class'); ?> <span class="required">*</span></div>
                                <select  class="form-control col-md-7 col-xs-12" name="class_id" id="class_id"  required="required" onchange="get_section_by_class(this.value,'','');">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php foreach ($classes as $obj) { 
                                        if( $class_id == $obj->id)
                                        {
                                            $class_name = $obj->name;
                                        }
                                        ?>
                                        <?php if($this->session->userdata('role_id') == TEACHER && !in_array($obj->id, $teacher_student_data)){ continue;  ?>
                                        <?php }elseif($this->session->userdata('role_id') == GUARDIAN && !in_array($obj->id, $guardian_class_data)){ continue; } ?>
                                        <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                    <?php } ?>
                                </select>
                                <div class="help-block"><?php echo form_error('class_id'); ?></div>
                            </div>
                    </div>
                    
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('section'); ?>  <span class="required">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12" name="section_id" id="section_id" required="required" onchange="get_student_by_section(this.value,'');">                                
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            </select>
                            <div class="help-block"><?php echo form_error('section_id'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('student'); ?>  <span class="required">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12" name="student_id" id="student_id" required="required">                                
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            </select>
                            <div class="help-block"><?php echo form_error('student_id'); ?></div>
                        </div>
                    </div>
                    <?php } ?>    
                                   
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('exam'); ?>  <span class="required">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12" name="exam_id" id="exam_id"  >
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php foreach ($exams as $obj) { ?>
                                <option value="<?php echo $obj->id; ?>" <?php if(isset($exam_id) && $exam_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->title ?></option>
                                <?php } ?>
                            </select>
                            <div class="help-block"><?php echo form_error('exam_id'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

            <?php  if (isset($student) && !empty($student)) { ?>
            <div class="x_content">             
                            <?php if(isset($school)){ ?>
                                <div class="float-container">


                                    
                                    
                                </div>
                           
                           
                            <?php } ?>
                         
            </div>
             <?php } ?>
            
            <div class="x_content">
                <?php 
                // if ($selected_student_id)
                // {
                //     // echo '<pre>'; var_dump($reports); die(); 
                //     $loop_students = array($student->id=>$reports[$selected_student_id] ?? array());
                // }
                // else
                // {
                    $loop_students = $reports;
                // }
                $iRank = 0;
                $istudent_found= 0;
                foreach($loop_students as $student_id => $student)
                {
                    if(empty($student))
                        continue;
                    if(!$istudent_found)
                    {
                        $iRank++;
                    }
                    if(isset($selected_student_id) && $selected_student_id && $student->student_id != $selected_student_id)
                    {
                        continue;
                    }
                ?>
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" style="margin-bottom:0px" cellspacing="0" >
                   <thead>
                    <tr>
                            <th colspan="100%">
                                    <div class="float-child" style="width:25%;text-align:left;margin-top: 20px;margin-left: 20px;">
                                        <b>Sr# : <?php echo $student->admission_no; ?><br>
                                        <b> <?php echo $this->lang->line('roll_no'); ?> : <?php echo $student->roll_no; ?><br>
                                        <?php echo $this->lang->line('class'); ?> : <?php echo  $student->class_name;; ?> 
                                        <br><b>Student's <?php echo $this->lang->line('name'); ?> : <?php echo $student->name; ?><br/>
                                        Father's <?php echo $this->lang->line('name'); ?> : <?php echo $student->father_name; ?><br/>
                                        Mother's <?php echo $this->lang->line('name'); ?> : <?php echo $student->mother_name; ?><br/>
                                        Date of Birth : <?php echo date("d-m-Y", strtotime($student->dob)); ?></b>
                                    </div>
                                    <div class="float-child" style="width:60%!important;text-align:left;margin-top: 5px;margin-left: 20px;">
                                    <div style="float:left;text-align:center; " >
                                        <?php if($school->logo){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" style="height:80px; width:85px;"/> 
                                        <?php }else if($school->frontend_logo){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" style="height:80px; width:85px;" /> 
                                        <?php }else{ ?>                                                        
                                            <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" style="height:80px; width:85px;"  />
                                        <?php } ?>
                                        </div>
                                        <div style="float:left;text-align:center; width:55%;">
                                        <h3> <?php echo $school->school_name; ?></h3>
                                        <?php echo $school->address; ?>
                                        <br><?php echo $this->lang->line('phone'); ?>: <?php echo $school->phone; ?>
                                        <br><?php echo $this->lang->line('email'); ?>: <?php echo $school->email; ?>
                                        <br><b>Society Name :</b> <?php echo $school->society_name; ?>
                                        <h5><?php echo $this->lang->line('mark_sheet'); ?> <?php echo substr($academic_year->start_year ,-4)?> - <?php echo substr($academic_year->end_year,-4) ?></h5>
                                        </div>
                                        
                                    </div>
                                  
                            </th>                                    
                        </tr>
                        <tr>
                            <th rowspan="3">Subject</th>
                            <?php foreach($exams_n as  $exam_id_n => $exam){ 
                                $iColSpan = 2;
                                if(isset($check_exams[$exam_id_n]['viva_exist']) && $check_exams[$exam_id_n]['viva_exist']>0)
                                    $iColSpan =  $iColSpan + 2;
                                if(isset($check_exams[$exam_id_n]['practical_exist']) && $check_exams[$exam_id_n]['practical_exist']>0)
                                    $iColSpan =  $iColSpan + 2;

                                ?>
                                <th colspan="<?php echo $iColSpan; ?>"><?php echo $exam ?></th>
                            <?php } ?>    
                            <th colspan="3" rowspan="2">Grand</th>                                    
                        </tr>
                        <tr>          
                             <?php foreach($exams_n as $exam_id_n => $exam){ ?>
                                <th colspan="2">Written</th>     
                                <?php if(isset($check_exams[$exam_id_n]['viva_exist']) && $check_exams[$exam_id_n]['viva_exist']>0) { ?>                               
                                 <th colspan="2">Viva</th> 
                                 <?php 
                                } 
                                if(isset($check_exams[$exam_id_n]['practical_exist']) && $check_exams[$exam_id_n]['practical_exist']>0) {
                                ?>
                                 <th colspan="2">Practical</th> 
                            <?php 
                                }
                        
                            } ?>                                                      
                        </tr>
                        <tr>          
                             <?php foreach($exams_n as $exam_id_n => $exam){
                                
                                ?>
                                <th >Max Mark</th>                                    
                                 <th >Obtain Mark</th>
                                 <?php if(isset($check_exams[$exam_id_n]['viva_exist']) && $check_exams[$exam_id_n]['viva_exist']>0) { ?>
                                  <th >Max Mark</th>                                    
                                 <th >Obtain Mark</th> 
                                 <?php 
                                } 
                                if(isset($check_exams[$exam_id_n]['practical_exist']) && $check_exams[$exam_id_n]['practical_exist']>0) {
                                ?>
                                 <th >Max Mark</th>                                    
                                 <th >Obtain Mark</th> 
                            <?php 
                                }
                        } ?>       
                            <th >Max Mark</th>                                    
                                 <th >Obtain Mark</th>                                                
                        </tr>
                    </thead>
                    <tbody id="fn_mark">   
                        <?php
                        $count = 1;
                        $iGrandRowMax = 0;
                        $iGrandRowObtain = 0;
                        $iGrandRowMaxNon =  $iGrandRowMax;
                        $iGrandRowObtainNon =  $iGrandRowObtain;

                        // if (isset($reports) && !empty($reports))
                         {
                            ?>
                                <?php 
                             
                                // debug_a($reports);
                                // foreach($reports as $student_report)
                                { 
                                    
                                    $istudent_found =1;
                                     $marks    = $student->subjects;
                                     $iTotalRowMax = [];
                                     $iTotalRowObtain = [];
                                    ?>
                                    
                                    <?php 
                                    
                                    foreach($subject_exams as $subject_id => $subject){ 
                                        $subject = $subjects[$subject_id] ?? array();
                                        if(empty($subject))
                                        {
                                            continue;
                                        }
                                         $iTotalMark = 0;
                                         $iTotalObtain = 0;
                                        ?>
                                        <tr>
                                            <td><?php echo $subject->name ?> </td>
                                        <?php foreach($exams_n as $exam_id_n => $exam_name){ 
                                            $student_exam = $marks[$subject_id][$exam_id_n]  ?? array();
                                            $written_obtain     =  $viva_obtain        =$practical_obtain   =  $written_max =  $viva_max = $practical_max = 0;
                                            if(!isset($iTotalRowMax[$exam_id_n])) $iTotalRowMax[$exam_id_n] = array("viva"=>0,"written"=>0,"practical"=>0);
                                            if(!isset($iTotalRowObtain[$exam_id_n])) $iTotalRowObtain[$exam_id_n] = array("viva"=>0,"written"=>0,"practical"=>0);

                                            if($student_exam)
                                            {

                                                $written_obtain     = isset($student_exam['written']) ? $student_exam['written'] : 0;
                                                $viva_obtain        =  isset($student_exam['viva']) ?  $student_exam['viva'] : 0;
                                                $practical_obtain   =  isset($student_exam['practical']) ?  $student_exam['practical'] : 0;
                                            }
                                            if (isset($subject_exams[$subject_id][$exam_id_n]))
                                            {
                                                $written_max =$subject_exams[$subject_id][$exam_id_n]->max_mark;
                                                $viva_max = $subject_exams[$subject_id][$exam_id_n]->max_mark_v;
                                                $practical_max = $subject_exams[$subject_id][$exam_id_n]->max_mark_p;

                                                $iTotalRowMax[$exam_id_n]['written'] = $iTotalRowMax[$exam_id_n]['written']  + $written_max ;
                                                $iTotalRowMax[$exam_id_n]['viva']  = $iTotalRowMax[$exam_id_n]['viva']  +  $viva_max  ;
                                                $iTotalRowMax[$exam_id_n]['practical']  = $iTotalRowMax[$exam_id_n]['practical']  + $practical_max ;
                                               
                                                $iTotalRowObtain[$exam_id_n]['written'] =  $iTotalRowObtain[$exam_id_n]['written'] +$written_obtain ;
                                                $iTotalRowObtain[$exam_id_n]['viva'] =  $iTotalRowObtain[$exam_id_n]['viva']  + $viva_obtain  ;
                                                $iTotalRowObtain[$exam_id_n]['practical'] =  $iTotalRowObtain[$exam_id_n]['practical']  +$practical_obtain ;
                                                $iTotalMark = $iTotalMark + $written_max +$viva_max+$practical_max;
                                                $iTotalObtain =  $iTotalObtain + $written_obtain +  $viva_obtain + $practical_obtain ;
                                            }
                                           
                                          
                                            ?>
                                           <td><?php echo  $written_max; ?> </td>
                                            <td><?php echo  $written_obtain ; ?> </td>
                                            <?php if(isset($check_exams[$exam_id_n]['viva_exist']) && $check_exams[$exam_id_n]['viva_exist']>0) { ?>
                                            <td><?php echo  $viva_max; ?> </td>
                                            <td><?php echo  $viva_obtain; ?> </td>
                                            <?php 
                                            } 
                                            if(isset($check_exams[$exam_id_n]['practical_exist']) && $check_exams[$exam_id_n]['practical_exist']>0) {
                                            ?>
                                            <td><?php echo  $practical_max; ?> </td>
                                            <td><?php echo  $practical_obtain  ?> </td>
                                    <?php 
                                            }
                                
                                } ?>
                                            <td><?php echo  $iTotalMark ?> </td>
                                            <td><?php echo  $iTotalObtain ?> </td>
                                        </tr>
                                <?php 
                                    $iGrandRowMax = $iGrandRowMax + $iTotalMark;
                                    $iGrandRowObtain = $iGrandRowObtain + $iTotalObtain;
                            } ?>
                            <?php } 
                            $iGrandRowMaxNon = $iGrandRowMax;
                            $iGrandRowObtainNon =  $iGrandRowObtain;
                            ?>
                            <tr>
                            <th >Total</th>
                            <?php foreach($exams_n as $exam_id_n => $exam){ ?>
                                <th ><?php echo  $iTotalRowMax[$exam_id_n]['written'] ?? 0  ?></th>     
                                <th ><?php echo  $iTotalRowObtain[$exam_id_n] ['written'] ?? 0 ?></th>  
                                <?php if(isset($check_exams[$exam_id_n]['viva_exist']) && $check_exams[$exam_id_n]['viva_exist']>0) { ?>
                                <th ><?php echo  $iTotalRowMax[$exam_id_n]['viva']  ?? 0  ?></th>     
                                <th ><?php echo  $iTotalRowObtain[$exam_id_n]['viva']  ?? 0 ?></th>  
                                <?php 
                                }
                                if(isset($check_exams[$exam_id_n]['practical_exist']) && $check_exams[$exam_id_n]['practical_exist']>0) {
                                ?>
                                <th ><?php echo  $iTotalRowMax[$exam_id_n]['practical']  ?? 0  ?></th>     
                                <th ><?php echo  $iTotalRowObtain[$exam_id_n]['practical']  ?? 0 ?></th>  
                            <?php 
                                }
                        
                            } ?>    
                            <th ><?php echo  $iGrandRowMax ?></th>     
                            <th ><?php echo  $iGrandRowObtain ?></th>                                    
                        </tr>
                        <?php if(!empty($optional_subject_exams)){ ?>
                             <tr>
                            <th colspan="100%" style="text-align:center">Optional</th>
                             </tr>
                            <?php
                                $iGrandRowMax =   $iGrandRowObtain = 0;
                             $istudent_found= 0; 
                             $iTotalRowMax = $iTotalRowObtain= array();
                             // debug_a($reports);
                     ?>
                        <?php 
                                    $iGrandRowMax = $iGrandRowObtain = 0;
                                    foreach($optional_subject_exams as $subject_id => $subject){ 
                                        $subject = $subjects[$subject_id] ?? array();
                                        if(empty($subject))
                                        {
                                            continue;
                                        }
                                         $iTotalMark = 0;
                                         $iTotalObtain = 0;
                                        ?>
                                        <tr>
                                            <td><?php echo $subject->name ?> </td>
                                        <?php foreach($exams_n as $exam_id_n => $exam_name){ 
                                            $student_exam = $marks[$subject_id][$exam_id_n]  ?? array();
                                            $written_obtain     =  $viva_obtain        =$practical_obtain   =  $written_max =  $viva_max = $practical_max = 0;
                                            if(!isset($iTotalRowMax[$exam_id_n])) $iTotalRowMax[$exam_id_n] = array("viva"=>0,"written"=>0,"practical"=>0);
                                            if(!isset($iTotalRowObtain[$exam_id_n])) $iTotalRowObtain[$exam_id_n] = array("viva"=>0,"written"=>0,"practical"=>0);

                                            if($student_exam)
                                            {

                                                $written_obtain     = isset($student_exam['written']) ? $student_exam['written'] : 0;
                                                $viva_obtain        =  isset($student_exam['viva']) ?  $student_exam['viva'] : 0;
                                                $practical_obtain   =  isset($student_exam['practical']) ?  $student_exam['practical'] : 0;
                                            }
                                           
                                            if (isset($optional_subject_exams[$subject_id][$exam_id_n]))
                                            {
                                                $written_max = $optional_subject_exams[$subject_id][$exam_id_n]->max_mark >0 ? $optional_subject_exams[$subject_id][$exam_id_n]->max_mark :0;
                                                $viva_max = $optional_subject_exams[$subject_id][$exam_id_n]->max_mark_v>0 ? $optional_subject_exams[$subject_id][$exam_id_n]->max_mark_v : 0;
                                                $practical_max = $optional_subject_exams[$subject_id][$exam_id_n]->max_mark_v>0 ? $optional_subject_exams[$subject_id][$exam_id_n]->max_mark_p : 0;
                                              
                                            }
                                          

                                            $iTotalRowMax[$exam_id_n]['written'] = $iTotalRowMax[$exam_id_n]['written']  + $written_max ;
                                            $iTotalRowObtain[$exam_id_n]['written'] =  $iTotalRowObtain[$exam_id_n]['written'] +$written_obtain ;
                                            $iTotalMark = $iTotalMark + $written_max;
                                            $iTotalObtain =  $iTotalObtain + $written_obtain;

                                            // $iTotalMark = $iTotalMark + $written_max +$viva_max+$practical_max;
                                            // $iTotalObtain =  $iTotalObtain + $written_obtain +  $viva_obtain + $practical_obtain ;
                                            ?>
                                           <td><?php echo  $written_max; ?> </td>
                                            <td><?php echo  $written_obtain ; ?> </td>
                                            <?php if(isset($check_exams[$exam_id_n]['viva_exist']) && $check_exams[$exam_id_n]['viva_exist']>0) {
                                            $iTotalRowMax[$exam_id_n]['viva']  = $iTotalRowMax[$exam_id_n]['viva']  +  $viva_max  ;
                                            $iTotalRowObtain[$exam_id_n]['viva'] =  $iTotalRowObtain[$exam_id_n]['viva']  + $viva_obtain  ;
                                            $iTotalMark = $iTotalMark + $viva_max;
                                            $iTotalObtain = $iTotalObtain + $viva_obtain;
                                                ?>
                                            <td><?php echo  $viva_max; ?> </td>
                                            <td><?php echo  $viva_obtain; ?> </td>
                                            <?php 
                                            } 
                                            if(isset($check_exams[$exam_id_n]['practical_exist']) && $check_exams[$exam_id_n]['practical_exist']>0) {
                                                $iTotalRowMax[$exam_id_n]['practical']  = $iTotalRowMax[$exam_id_n]['practical']  + $practical_max ;
                                                $iTotalRowObtain[$exam_id_n]['practical'] =  $iTotalRowObtain[$exam_id_n]['practical']  +$practical_obtain ;
                                                $iTotalMark = $iTotalMark + $practical_max;
                                                $iTotalObtain = $iTotalObtain + $practical_obtain;
                                            ?>
                                            <td><?php echo  $practical_max; ?> </td>
                                            <td><?php echo  $practical_obtain  ?> </td>
                                    <?php 
                                            }
                                            } ?>
                                            <td><?php echo  $iTotalMark ?> </td>
                                            <td><?php echo  $iTotalObtain ?> </td>
                                        </tr>
                                <?php 
                                    $iGrandRowMax = $iGrandRowMax + $iTotalMark;
                                    $iGrandRowObtain = $iGrandRowObtain + $iTotalObtain;
                            } ?>
                         <tr>
                            <td >Total</td>
                            <?php foreach($exams_n as $exam_id_n => $exam)
                            { ?>
                                <td ><?php echo  $iTotalRowMax[$exam_id_n]['written'] ?? 0  ?></td>     
                                <td ><?php echo  $iTotalRowObtain[$exam_id_n] ['written'] ?? 0 ?></td>  
                                <?php if(isset($check_exams[$exam_id_n]['viva_exist']) && $check_exams[$exam_id_n]['viva_exist']>0) { ?>
                                <td ><?php echo  $iTotalRowMax[$exam_id_n]['viva']  ?? 0  ?></td>     
                                <td ><?php echo  $iTotalRowObtain[$exam_id_n]['viva']  ?? 0 ?></td>  
                                <?php 
                                } 
                                if(isset($check_exams[$exam_id_n]['practical_exist']) && $check_exams[$exam_id_n]['practical_exist']>0) {
                                ?>
                                <td ><?php echo  $iTotalRowMax[$exam_id_n]['practical']  ?? 0  ?></td>     
                                <td ><?php echo  $iTotalRowObtain[$exam_id_n]['practical']  ?? 0 ?></td>  
                            <?php 
                                }
                            } ?>    
                            <td ><?php echo  $iGrandRowMax ?></td>     
                            <td ><?php echo  $iGrandRowObtain ?></td>                                    
                        </tr>
                        <?php
                        }
                    }
                    // else{ 
                        ?>
                                <!-- <tr>
                                    <td colspan="17" align="center"><?php echo $this->lang->line('no_data_found'); ?></td>
                                </tr> -->
                        <?php 
                    // }
                     ?>
                    </tbody>
                </table>     
                <div class="float-container" style="background : unset !important">

                    <div class="float-child" style="width:70%;text-align:left;padding-left: 50px;">
                    <?php
                    $percentage = $iGrandRowMaxNon ? number_format( ($iGrandRowObtainNon /$iGrandRowMaxNon)*100,2 ) : 0;
                    ?>
                    <b>Percentage : <?php echo$percentage  ?> %</b><br>
                    <?php
                    $grade = find_grade($grades,$percentage);
                    $lowest_grade = lowest_grade($grades);
                    $result = "";
                    if($lowest_grade && count($reports))
                    {
                        $result  = $lowest_grade->mark_to <  $percentage ? "Passed" : "Failed" ;
                    }
                    if($grade) 
                    {
                        $grade  = $grade->name ;
                    }

                    if( $result=="Failed")
                    {
                        $grade  = $lowest_grade->name ;
                    }
                    ?>
                    <b>Result : <?php echo  $result ?></b>
                    </div>

                    <div class="float-child" style="width:30%;text-align:left">
                        <b>Division : <?php echo $grade  ?> </b><br>
                    <b>Rank in Class : <?php echo $iRank ?></b>
                    </div>
                </div>        
                <div class=" row <?php echo !$selected_student_id ? 'footer-div' : '';?> " style="">
           
                        <div class=" col-xs-4 " style="text-align:center;margin-top:30px">
                <!-- /.col --> <b> &nbsp;&nbsp;PRINCIPAL <br><br> ________________ </b></div>

                        <div class=" col-xs-4 " style="text-align:center;margin-top:30px">
                <!-- /.col --> <b> &nbsp;&nbsp;EXAM IN CHARGE <br> <br>________________ </b></div>

                        <div class=" col-xs-4 " style="text-align:center;margin-top:30px">
                <!-- /.col --> <b> &nbsp;&nbsp;TEACHER'S SIGNATURE <br><br>________________ </b> </div>

                </div>
                <?php } ?>
            </div> 
          
            <div class="row no-print" >
                <div class="col-xs-12 text-right">
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 no-print">
                <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('mark_sheet_instruction'); ?></div>
            </div>
        </div>
    </div>
</div>

 
<!-- Super admin js START  -->
 <script type="text/javascript">
        
     $("document").ready(function() {
         <?php if(isset($school_id) && !empty($school_id)){ ?>               
            $(".fn_school_id").trigger('change');
         <?php } ?>
    });
    
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();
        var exam_id = '';
        var class_id = '';
        var academic_year_id = '';
        
        <?php if(isset($school_id) && !empty($school_id)){ ?>
            exam_id =  '<?php echo $exam_id; ?>';           
            class_id =  '<?php echo $class_id; ?>';           
            academic_year_id =  '<?php echo $academic_year_id; ?>';           
         <?php } ?> 
           
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_exam_by_school'); ?>",
            data   : { school_id:school_id, exam_id:exam_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
                    $('#exam_id').html(response);  
                   get_class_by_school(school_id,class_id); 
                   get_academic_year_by_school(school_id, academic_year_id);
               }
            }
        });
    }); 

    function get_academic_year_by_school(school_id, academic_year_id){       
         
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_academic_year_by_school'); ?>",
            data   : { school_id:school_id, academic_year_id :academic_year_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
                    $('#academic_year_id').html(response); 
               }
            }
        });
   }
   function get_class_by_school(school_id, class_id){       
         
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id:school_id, class_id:class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                    $('#class_id').html(response); 
               }
            }
        }); 
   } 
   
  
   
  </script>
<!-- Super admin js end -->


 <script type="text/javascript">     
  
    <?php if(isset($class_id) && isset($section_id)){ ?>
        get_section_by_class('<?php echo $class_id; ?>', '<?php echo $section_id; ?>');
    <?php } ?>
    
    
    function get_section_by_class(class_id, section_id){       
       
       var school_id = $('#school_id').val();  
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        } 
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : {school_id:school_id, class_id : class_id , section_id: section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                  $('#section_id').html(response);
               }
            }
        });         
    }
 
    <?php if(isset($class_id) && isset($section_id)){ ?>
        get_student_by_section('<?php echo $section_id; ?>', '<?php echo $selected_student_id; ?>');
    <?php } ?>
    
    function get_student_by_section(section_id, student_id){       
        
        var school_id = $('#school_id').val();  
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        } 
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_student_by_section'); ?>",
            data   : {school_id:school_id,section_id: section_id, student_id: student_id, is_all : 1},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                  $('#student_id').html(response);
               }
            }
        });         
    }
 
  $("#marksheet").validate(); 
</script>
<style>
.table>thead>tr>th {
    padding: 4px;
}
</style>



