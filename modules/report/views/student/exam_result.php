<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('report'); ?></small></h3>                
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
            <?php if(has_permission(VIEW, 'report', 'report')){ ?>
                                <a href="<?php echo site_url('report/income'); ?>"><?php echo $this->lang->line('income'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/expenditure'); ?>"><?php echo $this->lang->line('expenditure'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/invoice'); ?>"><?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/duefee'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/feecollection'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/balance'); ?>"><?php echo $this->lang->line('accounting'); ?> <?php echo $this->lang->line('balance'); ?> <?php echo $this->lang->line('report'); ?></a> 
                                | <a href="<?php echo site_url('report/library'); ?>"><?php echo $this->lang->line('library'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/sattendance'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                                | <a href="<?php echo site_url('report/syattendance'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                                | <a href="<?php echo site_url('report/tattendance'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                                | <a href="<?php echo site_url('report/tyattendance'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                                | <a href="<?php echo site_url('report/eattendance'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                                | <a href="<?php echo site_url('report/eyattendance'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                                | <a href="<?php echo site_url('report/student'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/sinvoice'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('report'); ?></a> 
                                | <a href="<?php echo site_url('report/sactivity'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('activity'); ?> <?php echo $this->lang->line('report'); ?></a>
                               
                                | <a href="<?php echo site_url('report/transaction'); ?>"><?php echo $this->lang->line('daily'); ?> <?php echo $this->lang->line('transaction'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/statement'); ?>"><?php echo $this->lang->line('daily'); ?> <?php echo $this->lang->line('statement'); ?> <?php echo $this->lang->line('report'); ?></a>
                                | <a href="<?php echo site_url('report/examresult'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('report'); ?></a>
                       
                    <?php } ?>
            </div>
            <?php $this->load->view('quick_report'); ?>   
            
             <div class="x_content filter-box no-print"> 
                <?php echo form_open_multipart(site_url('report/examresult'), array('name' => 'examresult', 'id' => 'examresult', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">     
                    
                   <?php $this->load->view('layout/school_list_filter'); ?> 
                   <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('academic_year'); ?> <span class="red">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12" name="academic_year_id" required="required" id="academic_year_id">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php foreach ($academic_years as $obj) { ?>
                                <option value="<?php echo $obj->id; ?>" <?php if(isset($academic_year_id) && $academic_year_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->session_year; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                   <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('class'); ?> <span class="red">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12" name="class_id" id="class_id" required="required" onchange="get_section_by_class('',this.value, '');">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php foreach ($classes as $obj) { ?>
                                <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                   <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('section'); ?></div>
                            <select  class="form-control col-md-7 col-xs-12" name="section_id" id="section_id">                                
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            </select>
                        </div>
                    </div>                    
                
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>
                </div>



                       <!-- ak -->
                 <div class="container">
                           
                                <strong>Checked the Checkbox for Hide column</strong>
                                   <input type="checkbox" class="hidecol"  id="col_1" />&nbsp; <div><?php echo $this->lang->line('name'); ?>&nbsp;
                                    <input type="checkbox" class="hidecol"  id="col_2" />&nbsp;<?php echo $this->lang->line('roll_no'); ?>&nbsp;
                                    <input type="checkbox" class="hidecol"  id="col_3" />&nbsp;<?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('subject'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_4" />&nbsp;<?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('mark'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_5" />&nbsp;<?php echo $this->lang->line('obtain'); ?> <?php echo $this->lang->line('mark'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_6" />&nbsp;<?php echo $this->lang->line('percentage'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_7" />&nbsp; <?php echo $this->lang->line('average_grade_point'); ?>

                                    <input type="checkbox" class="hidecol"  id="col_9" />&nbsp;<?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('status'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_10" />&nbsp; <?php echo $this->lang->line('position_in_section'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_11" />&nbsp; <?php echo $this->lang->line('position_in_class'); ?>
                            </div>
                        </div>
                <!-- ak -->



                <?php echo form_close(); ?>
            </div>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                     <h5> <?php echo "Date : ". date("d/m/Y") ?> 
                   <?php if(isset($school) && !empty($school)){ ?>
                                    <div class="x_content">             
                                        <div class="row">
                                            <div class="col-sm-3  col-xs-3">&nbsp;</div>
                                            <div class="col-12 layout-box">
                                                <div>
                                                    <?php if($school->logo){ ?>
                                                        <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" style="height: 50px; margin-right: 200px;" /> 
                                                    <?php }else if($school->frontend_logo){ ?>
                                                        <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" style="height: 50px; margin-right: 200px;" /> 
                                                    <?php }else{ ?>                                                        
                                                        <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" style="height: 50px; margin-right: 200px;" />
                                                    <?php } ?>
                                                    <h4><?php echo $school->school_name; ?></h4>
                                                    <p><?php echo $school->address; ?></p>
                                                    <h3 class="head-title ptint-title" style="width: 100%;"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('report'); ?></small></h3>                
                                                    <div class="clearfix">&nbsp;</div>
                                                </div>
                                            </div>
                                                <div class="col-sm-3  col-xs-3"></div>
                                        </div>            
                                    </div>
                    <?php } ?>    
                    <ul  class="nav nav-tabs bordered no-print">
                        <li class="active"><a href="#tab_tabular"   role="tab" data-toggle="tab"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('tabular'); ?> <?php echo $this->lang->line('report'); ?></a> </li>
                    </ul>
                    <br/>
                   
                    <div class="tab-content">
                        <div  class="tab-pane fade in active" id="tab_tabular" >
                            <div class="x_content">
                            <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                        <th><?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('subject'); ?></th>                                            
                                        <th><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('mark'); ?></th>                                            
                                        <th><?php echo $this->lang->line('obtain'); ?> <?php echo $this->lang->line('mark'); ?></th> 
                                        <th ><?php echo $this->lang->line('percentage'); ?></th> 
                                        <th> <?php echo $this->lang->line('average_grade_point'); ?></th>                                            
                                        <th><?php echo $this->lang->line('grade'); ?></th>                                            
                                        <th><?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('status'); ?></th>                                            
                                        <th ><?php echo $this->lang->line('position_in_section'); ?></th>                                            
                                        <th ><?php echo $this->lang->line('position_in_class'); ?></th>   
                                        <th><?php echo $this->lang->line('remark'); ?></th>  
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php 
                                    
                                    $count = 1; if(isset($examresult) && !empty($examresult)){ ?>
                                        <?php foreach($examresult as $obj){ ?>
                                        <?php $class_position = get_student_position($school_id, $academic_year_id, $class_id, $obj->student_id); ?>    
                                        <?php $section_position = get_student_position($school_id, $academic_year_id, $class_id,$obj->student_id, $obj->section_id); ?> 
                                        <tr>
                                            <td><?php echo $obj->student; ?></td>
                                            <td><?php echo $obj->roll_no; ?></td>
                                            <td><?php echo $obj->total_subject; ?></td>
                                            <td><?php echo $obj->total_mark; ?></td>
                                            <td><?php echo $obj->total_obtain_mark; ?></td>
                                            <td><?php echo $obj->total_mark > 0 ? number_format(@$obj->total_obtain_mark/$obj->total_mark*100, 2) : 0; ?>%</td> 
                                            <td><?php echo $obj->avg_grade_point; ?></td>
                                            <td><?php echo $obj->grade; ?></td>
                                            <td><?php echo $this->lang->line($obj->result_status); ?></td>
                                            <td><?php echo $section_position; ?></td> 
                                            <td><?php echo $class_position; ?></td> 
                                            <td><?php echo $obj->remark; ?></td>
                                        </tr>
                                        <?php } ?>                                        
                                    <?php }else{ ?>
                                        <tr><td colspan="12" class="text-center"><?php echo $this->lang->line('no_data_found'); ?></td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            
             <div class="row no-print">
                <div class="col-xs-12 text-right">
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
            
        </div>
    </div>
</div>

 <script type="text/javascript">

    $("#examresult").validate(); 
    
        $("document").ready(function() {
         <?php if(isset($school_id) && !empty($school_id)){ ?>
            $(".fn_school_id").trigger('change');
         <?php } ?>
    });
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();
        var class_id = '';
        var section_id = '';
        var academic_year_id = '';
        
        <?php if(isset($school_id) && !empty($school_id)){ ?>
            class_id =  '<?php echo $class_id; ?>';
            section_id =  '<?php echo $section_id; ?>';
            academic_year_id =  '<?php echo $academic_year_id; ?>'; 
         <?php } ?>          
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
        
        get_academic_year_by_school(school_id, academic_year_id);
        get_class_by_school(school_id, class_id, section_id);
       
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
    
    
    
    function get_class_by_school(school_id, class_id, section_id){       
        
        if(!school_id){
            school_id = $('#school_id').val();
        }
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id:school_id, class_id:class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   $('#class_id').html(response);                                      
                   get_section_by_class(school_id, class_id, section_id);
               }
            }
        });         
    }
    
    function get_section_by_class(school_id, class_id, section_id){       
        
        if(!school_id){
            school_id = $('#school_id').val();
        }
               
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : { school_id:school_id, class_id : class_id , section_id: section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                  $('#section_id').html(response);
               }
            }
        });          
    }

      // ak
   $(document).ready(function(){

            // Checkbox click
            $(".hidecol").click(function(){

                var id = this.id;
                var splitid = id.split("_");
                var colno = splitid[1];
                var checked = true;
                 
                // Checking Checkbox state
                if($(this).is(":checked")){
                    checked = true;
                }else{
                    checked = false;
                }
                setTimeout(function(){
                    if(checked){
                        $('#datatable-keytable td:nth-child('+colno+')').hide();
                        $('#datatable-keytable th:nth-child('+colno+')').hide();
                    } else{
                        $('#datatable-keytable td:nth-child('+colno+')').show();
                        $('#datatable-keytable th:nth-child('+colno+')').show();
                    }

                }, 1500);

            });
        });
        // ak
        
       
</script>
