<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-file-text-o"></i><small> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('exam_final_result'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            
              
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'exam', 'mark')){ ?>
                    <a href="<?php echo site_url('exam/mark/index'); ?>"><?php echo $this->lang->line('manage_mark'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'exam', 'examresult')){ ?>
                   | <a href="<?php echo site_url('exam/examresult/index'); ?>"><?php echo $this->lang->line('exam_term'); ?> <?php echo $this->lang->line('result'); ?></a>                 
                <?php } ?>
                <?php if(has_permission(VIEW, 'exam', 'finalresult')){ ?>
                   | <a href="<?php echo site_url('exam/finalresult/index'); ?>"><?php echo $this->lang->line('exam_final_result'); ?></a>                 
                <?php } ?>
                <?php if(has_permission(VIEW, 'exam', 'meritlist')){ ?>
                   | <a href="<?php echo site_url('exam/meritlist/index'); ?>"><?php echo $this->lang->line('merit_list'); ?></a>                 
                <?php } ?>   
                <?php if(has_permission(VIEW, 'exam', 'marksheet')){ ?>
                   | <a href="<?php echo site_url('exam/marksheet/index'); ?>"><?php echo $this->lang->line('mark_sheet'); ?></a>
                <?php } ?>
                 <?php if(has_permission(VIEW, 'exam', 'resultcard')){ ?>
                   | <a href="<?php echo site_url('exam/resultcard/index'); ?>"><?php echo $this->lang->line('result_card'); ?></a>
                <?php } ?>   
                <?php if(has_permission(VIEW, 'exam', 'resultcard')){ ?>
                   | <a href="<?php echo site_url('exam/resultcard/all'); ?>"><?php echo $this->lang->line('all'); ?> <?php echo $this->lang->line('result_card'); ?></a>
                <?php } ?>     
                <?php if(has_permission(VIEW, 'exam', 'mail')){ ?>
                   | <a href="<?php echo site_url('exam/mail/index'); ?>"><?php echo $this->lang->line('mark_send_by_email'); ?></a>                    
                <?php } ?>
                <?php if(has_permission(VIEW, 'exam', 'text')){ ?>
                   | <a href="<?php echo site_url('exam/text/index'); ?>"><?php echo $this->lang->line('mark_send_by_sms'); ?></a>                  
                <?php } ?>
                <?php if(has_permission(VIEW, 'exam', 'resultemail')){ ?>
                   | <a href="<?php echo site_url('exam/resultemail/index'); ?>"> <?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('email'); ?></a>                    
                <?php } ?>
                <?php if(has_permission(VIEW, 'exam', 'resultsms')){ ?>
                   | <a href="<?php echo site_url('exam/resultsms/index'); ?>"> <?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('sms'); ?></a>                  
                <?php } ?>
            </div>      
            
            <div class="x_content"> 
                <?php echo form_open_multipart(site_url('exam/finalresult/index'), array('name' => 'result', 'id' => 'result', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">  
                                      
                    <?php $this->load->view('layout/school_list_filter'); ?>
                        
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('class'); ?>  <span class="required">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12" name="class_id" id="class_id"  required="required" onchange="get_section_by_class(this.value,'');">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php foreach ($classes as $obj) { ?>
                                <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                <?php } ?>
                            </select>
                            <div class="help-block"><?php echo form_error('class_id'); ?></div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('section'); ?></div>
                            <select  class="form-control col-md-7 col-xs-12" name="section_id" id="section_id">                                
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            </select>
                            <div class="help-block"><?php echo form_error('section_id'); ?></div>
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

           <?php  if (isset($students) && !empty($students)) { ?>
            <div class="x_content">             
                <div class="row">
                    <div class="col-sm-4  col-sm-offset-4 layout-box">
                        <p>
                            <h4><?php echo $this->lang->line('exam_final_result'); ?></h4>                            
                        </p>
                    </div>
                </div>            
            </div>
             <?php }
            
             ?>
            
            <div class="x_content">
                 <?php echo form_open(site_url('exam/finalresult/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                
                <?php if (isset($students) && !empty($students)) { ?>
                
                 <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive datatable-responsive nowrap" cellspacing="0" width="5000px">
                    <thead>
                        <tr>
                            <th rowspan="4"><?php echo $this->lang->line('roll_no'); ?></th>
                            <th rowspan="4">Admission No.</th>

                            <th rowspan="4"><?php echo $this->lang->line('name'); ?></th>
                            <th rowspan="4">Father <?php echo $this->lang->line('name'); ?></th>
                            <th rowspan="4">Mother <?php echo $this->lang->line('name'); ?></th>
                            <th rowspan="4"><?php echo $this->lang->line('photo'); ?></th>       
                            <?php 
                            foreach($subjects as $subject) { 
                                $iSubjectID = $subject->subject_id;
                                if(!empty($subject_exams[$iSubjectID]))
                                {
                                    $colspan = 0;
                                    $exams = $subject_exams[$iSubjectID];
                                    foreach ($exams as $exam)
                                    {
                                        $colspan = $colspan+2;

                                        if ($exam->max_mark_p > 0)  $colspan = $colspan+2;
                                        if ($exam->max_mark_v > 0)  $colspan = $colspan+2;
                                    }
                                 }                                 
                                ?>
                                <th colspan="<?php echo $colspan ?>"><?php echo $subject->name ?></th>       
                            <?php
                         }
                          ?>
                            <th rowspan="4"> Optional Max Total</th>   
                            <th rowspan="4"> Optional Obtain Total</th>                                            
                            <th rowspan="4"> Score in %</th>   
                            <th rowspan="4"> All Max Total</th>   
                            <th rowspan="4"> All Obtain Total</th>                                            
                            <th rowspan="4"> Score in %</th>                                            
                            <th rowspan="4">Grade</th>                               
                            <th rowspan="4">Result</th>                                            
                            <th rowspan="4">Rank</th>    
                            <th rowspan="4"></th>                                                                                                          
                        </tr>
                        <?php  if(!empty($subjects)){ ?>
                        <tr>
                            <?php 
                            foreach($subjects as $subject) {
                                $iSubjectID = $subject->subject_id;
                                if(!empty($subject_exams[$iSubjectID]))
                                {
                                    $exams = $subject_exams[$iSubjectID];
                                   foreach ($exams as $exam)
                                   {
                                        $colspan = 2;
                                        if ($exam->max_mark_p > 0)  $colspan = $colspan+2;
                                        if ($exam->max_mark_v > 0)  $colspan = $colspan+2;
                                        ?>
                                        <th colspan="<?php echo $colspan ?>"><?php echo $exam->exam_name; ?></th>       
                                        <?php
                                   }
                                }    
                                if(!empty($optional_subject_exams[$iSubjectID]))
                                {
                                    $exams = $optional_subject_exams[$iSubjectID];
                                   foreach ($exams as $exam)
                                   {
                                        $colspan = 2;
                                        if ($exam->max_mark_p > 0)  $colspan = $colspan+2;
                                        if ($exam->max_mark_v > 0)  $colspan = $colspan+2;
                                        ?>
                                        <th colspan="<?php echo $colspan ?>">*<?php echo $exam->exam_name; ?></th>       
                                        <?php
                                   }
                                }     
                                ?>
                            <?php
                         }
                          ?>                                                                                            
                        </tr>

                        <tr>
                          
                          <?php 
                          foreach($subjects as $subject) { 
                              $iSubjectID = $subject->subject_id;
                              
                              if(!empty($subject_exams[$iSubjectID]))
                              {
                                  $exams = $subject_exams[$iSubjectID];
                                 foreach ($exams as $exam)
                                 { 
                                    ?>
                                    <th colspan="2">Theory</th>     
                                    <?php
                                      if ($exam->max_mark_p > 0) 
                                      { 
                                        ?>
                                            <th colspan="2">Practical</th>     
                                        <?php
                                      }
                                      if ($exam->max_mark_v > 0)
                                      { 
                                        ?>
                                        <th colspan="2">Viva</th>     
                                        <?php 
                                      }
                                 }
                              }   
                              if(!empty($optional_subject_exams[$iSubjectID]))
                              {
                                  $exams = $optional_subject_exams[$iSubjectID];
                                 foreach ($exams as $exam)
                                 { 
                                    ?>
                                    <th colspan="2">Theory</th>     
                                    <?php
                                      if ($exam->max_mark_p > 0) 
                                      { 
                                        ?>
                                            <th colspan="2">Practical</th>     
                                        <?php
                                      }
                                      if ($exam->max_mark_v > 0)
                                      { 
                                        ?>
                                        <th colspan="2">Viva</th>     
                                        <?php 
                                      }
                                 }
                              }   
                          }
                          ?>                                                                                            
                      </tr>
                        <tr>
                          
                            <?php 
                           
                                foreach($subjects as $subject) { 
                                    $iSubjectID = $subject->subject_id;
                                    
                                    if(!empty($subject_exams[$iSubjectID]))
                                    {
                                        $exams = $subject_exams[$iSubjectID];
                                       foreach ($exams as $exam)
                                       {
                                             ?>
                                            <th>Max. Mark</th>     
                                            <th>Obtain Mark</th> 
                                            <?php
                                            if ($exam->max_mark_p > 0) 
                                            {
                                                ?>
                                                <th>Max. Mark</th>     
                                                <th>Obtain Mark</th> 
                                                <?php
                                            }
                                            if ($exam->max_mark_v > 0)
                                            { 
                                                ?>
                                                <th>Max. Mark</th>     
                                                <th>Obtain Mark</th> 
                                                <?php 
                                            }
                                       }
                                    }   
                                    if(!empty($optional_subject_exams[$iSubjectID]))
                                    {
                                        $exams = $optional_subject_exams[$iSubjectID];
                                       foreach ($exams as $exam)
                                       {
                                             ?>
                                            <th>Max. Mark</th>     
                                            <th>Obtain Mark</th> 
                                            <?php
                                            if ($exam->max_mark_p > 0) 
                                            {
                                                ?>
                                                <th>Max. Mark</th>     
                                                <th>Obtain Mark</th> 
                                                <?php
                                            }
                                            if ($exam->max_mark_v > 0)
                                            { 
                                                ?>
                                                <th>Max. Mark</th>     
                                                <th>Obtain Mark</th> 
                                                <?php 
                                            }
                                       }
                                    }   
                                }
                            
                    
                            ?>                                                                                            
                        </tr>

                    </thead>
                    <tbody id="fn_result">   
                            <?php 
                            $old_percentage = 0;
                          
                            $count = 1; foreach ($students as $obj) { 
                                $iTotalMax = $iTotalOptional = 0;
                                $iTotalObtain =  $iOptionalObtain = 0;
                                ?>                           
                                <tr>
                                    <td><?php echo $obj->roll_no; ?></td>
                                    <td><?php echo $obj->admission_no; ?></td>
                                    <td><?php echo ucfirst($obj->name); ?></td>
                                    <td><?php echo ucfirst($obj->father_name); ?></td>
                                    <td><?php echo ucfirst($obj->mother_name); ?></td>
                                    <td>
                                        <?php if ($obj->photo != '') { ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/student-photo/<?php echo $obj->photo; ?>" alt="" width="45" /> 
                                        <?php } else { ?>
                                            <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="45" /> 
                                        <?php } ?>
                                        <input type="hidden" value="<?php echo $obj->id; ?>"  name="students[]" />       
                                    </td>                               
                                    <?php 
                            foreach($subjects as $key => $subject) {
                                $iSubjectID = $subject->subject_id;
                                if(!empty($subject_exams[$iSubjectID]))
                                {
                                    $exams = $subject_exams[$iSubjectID];

                                   foreach ($exams as $exam)
                                   {
                                  
                                        $written_obtain     = isset($obj->exams[$iSubjectID][$exam->exam_id]['written']) ?  $obj->exams[$iSubjectID][$exam->exam_id]['written'] : 0;
                                        $viva_obtain        =  isset($obj->exams[$iSubjectID][$exam->exam_id]['viva']) ?  $obj->exams[$iSubjectID][$exam->exam_id]['viva'] : 0;
                                        $practical_obtain   =  isset($obj->exams[$iSubjectID][$exam->exam_id]['practical']) ?  $obj->exams[$iSubjectID][$exam->exam_id]['practical'] : 0;
                                        $iTotalMax =  $iTotalMax + ($exam->max_mark ? $exam->max_mark : 0);
                                        $iTotalObtain = $iTotalObtain  + $written_obtain;
                                    ?>
                                    <td><?php echo $exam->max_mark ? $exam->max_mark : 0; ?></td>
                                    <td><?php echo $written_obtain; ?></td>
                                   <?php
                                        if ($exam->max_mark_p > 0)
                                        {
                                            $iTotalObtain = $iTotalObtain  + $practical_obtain;
                                            $iTotalMax =  $iTotalMax +$exam->max_mark_p ;
                                            ?>
                                            <td><?php echo $exam->max_mark_p ? $exam->max_mark_p : 0; ?></td>
                                            <td><?php echo $practical_obtain; ?></td>
                                        <?php } 
                                        if ($exam->max_mark_v > 0) 
                                        { 
                                            $iTotalObtain = $iTotalObtain  + $viva_obtain;
                                            $iTotalMax =  $iTotalMax +$exam->max_mark_v ;
                                            ?>
                                            <td><?php echo $exam->max_mark_v ? $exam->max_mark_v : 0; ?></td>
                                            <td><?php echo $viva_obtain; ?></td>
                                        <?php } 
                                         ?>
                                        <?php
                                    }
                                 }     
                                 if(!empty($optional_subject_exams[$iSubjectID]))
                                 {
                                     $exams = $optional_subject_exams[$iSubjectID];
 
                                    foreach ($exams as $exam)
                                    {
                                   
                                         $written_obtain     = isset($obj->exams[$iSubjectID][$exam->exam_id]['written']) ?  $obj->exams[$iSubjectID][$exam->exam_id]['written'] : 0;
                                         $viva_obtain        =  isset($obj->exams[$iSubjectID][$exam->exam_id]['viva']) ?  $obj->exams[$iSubjectID][$exam->exam_id]['viva'] : 0;
                                         $practical_obtain   =  isset($obj->exams[$iSubjectID][$exam->exam_id]['practical']) ?  $obj->exams[$iSubjectID][$exam->exam_id]['practical'] : 0;
                                         $iTotalOptional =  $iTotalOptional + ($exam->max_mark ? $exam->max_mark : 0);
                                         $iOptionalObtain = $iOptionalObtain  + $written_obtain;
                                     ?>
                                     <td><?php echo $exam->max_mark ? $exam->max_mark : 0; ?></td>
                                     <td><?php echo $written_obtain; ?></td>
                                    <?php
                                         if ($exam->max_mark_p > 0)
                                         {
                                             $iOptionalObtain = $iOptionalObtain  + $practical_obtain;
                                             $iTotalOptional =  $iTotalOptional +$exam->max_mark_p ;
                                             ?>
                                             <td><?php echo $exam->max_mark_p ? $exam->max_mark_p : 0; ?></td>
                                             <td><?php echo $practical_obtain; ?></td>
                                         <?php } 
                                         if ($exam->max_mark_v > 0) 
                                         { 
                                             $iOptionalObtain = $iOptionalObtain  + $viva_obtain;
                                             $iTotalOptional =  $iTotalOptional +$exam->max_mark_v ;
                                             ?>
                                             <td><?php echo $exam->max_mark_v ? $exam->max_mark_v : 0; ?></td>
                                             <td><?php echo $viva_obtain; ?></td>
                                         <?php } 
                                          ?>
                                         <?php
                                     }
                                  }     
                                ?>
                            <?php
                          }
                          ?>            
                                    
                                    <td>
                                        <?php echo $iTotalOptional; ?> 
                                    </td>   
                                    <td>
                                        <?php echo $iOptionalObtain; ?> 
                                    </td>    
                                    <td>
                                        <?php 
                                        $percentage = $iTotalOptional ? number_format(($iOptionalObtain/$iTotalOptional)*100,2) : 0;
                                        echo  $percentage ; ?>
                                    </td>  
                                    <td>
                                        <?php echo $iTotalMax; ?> 
                                    </td>   
                                    <td>
                                        <?php echo $iTotalObtain; ?> 
                                    </td>    
                                    <td>
                                        <?php 
                                        $percentage = $iTotalMax ? number_format(($iTotalObtain/$iTotalMax)*100,2) : 0;
                                        echo  $percentage ; ?>
                                    </td>  
                                    <td>
                                        <?php 
                                        $grade = find_grade($grades,$percentage);
                                        $grade = $grade ? $grade->name : "-";
                                        echo  $grade ?>
                                    </td>    
                                    <td>
                                    <?php 
                                        $lowest_grade = lowest_grade($grades);
                                        if(!$lowest_grade)
                                        {
                                            $result  = "-";
                                        } else 
                                        {
                                            $result  = $lowest_grade->mark_to <  $percentage ? "Passed" : "Failed" ;
                                        }
                                        echo  $result ?>
                                    </td> 
                                    <td>
                                       <?php echo $count; ?>
                                    </td>   
                                    <td>
                                       <button type="button" class="btn btn-success" onclick="location.href='<?php echo site_url('exam/finalresult/student_report/'.$obj->student_id.'/'.$obj->class_id.'/'.$obj->school_id) ?>'">Grade Report</button>
                                    </td>                            
                                </tr>
                            <?php 
                            if($old_percentage != $percentage)
                            {
                                $count++;
                            }
                            $old_percentage = $percentage;
                        } ?>        
                                                <?php   } ?>
               
                    </tbody>
                </table>
                
                <?php } ?>
                             
                <div class="ln_solid"></div>
                <!-- <div class="form-group">
                    <div class="col-md-6 col-md-offset-5">
                        <?php  if (isset($students) && !empty($students)) { ?>
                         <input type="hidden" value="<?php echo $school_id; ?>"  name="school_id" />
                         <input type="hidden" value="<?php echo $class_id; ?>"  name="class_id" />
                         <input type="hidden" value="<?php echo $section_id; ?>"  name="section_id" />
                         <a href="<?php echo site_url('exam/finalresult'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                         <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                        <?php } ?>
                    </div>
                </div> -->
                 <?php echo form_close(); ?>
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('exam_result_instruction'); ?></div>
                </div>
                
            </div> 
            
        </div>
    </div>
</div>



<!-- Super admin js START  -->
 <script type="text/javascript">
        
    $("document").ready(function() {
        $(document).ready(function() {
            
            $('.datatable-responsive').DataTable({
                dom: 'Bfrtip',
                "ordering": false,
                "searching": false,
                "paging" : false,
                responsive: false,
                buttons: [
                    {
                        text: 'Export',
                        action: function ( ) {
                            doit('xlsx')
                        }
                    }
                ]
            });          
          });
         <?php if(isset($school_id) && !empty($school_id)){ ?>               
            $(".fn_school_id").trigger('change');
         <?php } ?>
    });
    
    $('.fn_school_id').on('change', function(){
        
        var school_id = $(this).val();
        var exam_id = '';
        var class_id = '';
        
        <?php if(isset($school_id) && !empty($school_id)){ ?>
            exam_id =  '<?php echo $exam_id; ?>';           
            class_id =  '<?php echo $class_id; ?>';           
         <?php } ?> 
           
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
        
        // need to check  school result based on final exam or all exam        
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_school_info_by_id'); ?>",
            data   : { school_id:school_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(response == 1){ // final_exam
                       $('.display').show();
                       $('#exam_id').prop('required', true);
                       get_exam_by_school(school_id, exam_id);
                   }else{
                       $('#exam_id').prop('required', false);
                       $('.display').hide(); 
                   }                   
                   get_class_by_school(school_id,class_id); 
               }
            }
        });       
       
    }); 
    
    function get_exam_by_school(school_id, exam_id){
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_exam_by_school'); ?>",
            data   : { school_id:school_id, exam_id:exam_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
                   $('#exam_id').html(response); 
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
       
        var school_id = $('.fn_school_id').val();     
             
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
 $("#result").validate(); 
 $("#add").validate(); 
 function doit(type, fn, dl) {
	var elt = document.getElementById('datatable-responsive');
	var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
	return dl ?
		XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
		XLSX.writeFile(wb, fn || ('exam_result.' + (type || 'xlsx')));
}
</script>
<style>
#datatable-responsive label.error{display: none !important;}
</style>


