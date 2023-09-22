<?php
$sMarkDisabled = isset($check_mark) && $check_mark ? "readonly" : "";
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-thumb-tack"></i><small> <?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('schedule'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'exam', 'grade')){ ?>
                    <a href="<?php echo site_url('exam/grade/'); ?>"><?php echo $this->lang->line('exam_grade'); ?></a>
                <?php } ?> 
                <?php if(has_permission(VIEW, 'exam', 'exam')){ ?>
                   | <a href="<?php echo site_url('exam/index'); ?>"><?php echo $this->lang->line('exam_term'); ?></a>
                <?php } ?> 
                <?php if(has_permission(VIEW, 'exam', 'schedule')){ ?>
                   | <a href="<?php echo site_url('exam/schedule/index'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('schedule'); ?></a>
                <?php } ?> 
                <!-- <?php if(has_permission(VIEW, 'exam', 'suggestion')){ ?>
                   | <a href="<?php echo site_url('exam/suggestion/index'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('suggestion'); ?> </a>
                <?php } ?>  -->
                <!-- <?php if(has_permission(VIEW, 'exam', 'attendance')){ ?>
                   | <a href="<?php echo site_url('exam/attendance/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('attendance'); ?></a>                    
                <?php } ?>  -->
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('exam/schedule/index'); ?>"  ><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('schedule'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'exam', 'schedule')){ ?>
                        
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('exam/schedule/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('schedule'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_schedule"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('schedule'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_schedule"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('schedule'); ?></a> </li>                          
                        <?php } ?>               
                        
                         
                            <li class="li-class-list">
                                
                            <?php $teacher_access_data = get_teacher_access_data('student'); ?> 
                            <?php $guardian_access_data = get_guardian_access_data('class'); ?>    
                                
                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN){  ?>
                                
                                <?php echo form_open(site_url('exam/schedule/index'), array('name' => 'filter', 'id' => 'filter', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                    <select  class="form-control col-md-7 col-xs-12" style="width:auto;" name="school_id"  onchange="get_class_by_school_filter(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                        <?php foreach($schools as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                        <?php } ?>   
                                    </select>
                                    <select  class="form-control col-md-7 col-xs-12" id="filter_class_id" name="class_id"  style="width:auto;" onchange="this.form.submit();">
                                         <option value="">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('class'); ?>--</option> 
                                    </select>
                                   <?php echo form_close(); ?>
                                
                            <?php }else{  ?>
                                    <select  class="form-control col-md-7 col-xs-12" onchange="get_schedule_list_by_class(this.value);">
                                        <option value="<?php echo site_url('exam/schedule/index/'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                        <?php foreach($class_list as $obj ){ ?>
                                            <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                                <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?> 
                                                <option value="<?php echo site_url('exam/schedule/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>
                                                <?php if (!in_array($obj->id, $guardian_access_data)) { continue; } ?>
                                                <option value="<?php echo site_url('exam/schedule/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                                <?php if (!in_array($obj->id, $teacher_access_data)) { continue; } ?>
                                                <option value="<?php echo site_url('exam/schedule/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo site_url('exam/schedule/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php } ?> 

                                        <?php } ?>                                            
                                    </select>                                      
                                <?php } ?>
                            </li>    
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_schedule_list" >                            
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
                                                <th><?php echo $this->lang->line('school'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('title'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('section'); ?></th>                                                                              
                                            <th><?php echo $this->lang->line('subject'); ?></th>                                       
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('time'); ?></th>
                                            <th><?php echo $this->lang->line('room_no'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>   
                                                                                
                                        <?php $count = 1; if(isset($schedules) && !empty($schedules)){ ?>
                                            <?php foreach($schedules as $obj){ ?>
                                        
                                            <?php 
                                                if($this->session->userdata('role_id') == GUARDIAN){
                                                    if (!in_array($obj->class_id, $guardian_access_data)) { continue; }
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo  $obj->custom_id ? $obj->custom_id : $count ; ?></td>
                                                <?php if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
                                                    <td><?php echo $obj->school_name; ?></td>
                                                <?php } ?>
                                                <td><?php echo $obj->title; ?></td>
                                                <td><?php echo $obj->class_name; ?></td>
                                                <td><?php echo $obj->section_name; ?></td>
                                                <td><?php echo $obj->subject; ?></td>
                                                <td><?php echo date($this->global_setting->date_format, strtotime($obj->exam_date)); ?></td>
                                                 <td><?php echo $obj->start_time; ?> - <?php echo $obj->end_time; ?></td>
                                                <td><?php echo $obj->room_no; ?></td>
                                                <td>
                                                    <?php if(has_permission(EDIT, 'exam', 'schedule')){ ?>
                                                        <a href="<?php echo site_url('exam/schedule/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                    <?php } ?>
                                                    <?php if(has_permission(EDIT, 'exam', 'schedule')){ ?>
                                                        <a href="<?php echo site_url('exam/schedule/add/'.$obj->id.'?duplicate=1'); ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('duplicate'); ?> </a>
                                                    <?php } ?>
                                                    <?php if(has_permission(VIEW, 'exam', 'schedule')){ ?>
                                                        <a  onclick="get_schedule_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-schedule-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                    <?php } ?>
                                                    <?php if(has_permission(DELETE, 'exam', 'schedule')){ ?>
                                                        <a href="<?php echo site_url('exam/schedule/delete/'.$obj->id); ?>" onclick="javascript: return confirm('Are you sure to delete the data? *All Student Marks related to this schedule will be deleted');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php 
                                                    $count++;
                                                } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- End first tab -->
                        
                        

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_schedule">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('exam/schedule/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                 <?php $this->load->view('layout/school_list_form'); ?>  
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_id"><?php echo $this->lang->line('exam'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="exam_id" id="add_exam_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($exams as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['exam_id']) && $post['exam_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->title; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('exam_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id" id="add_class_id" required="required" onchange="get_subject_and_section(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($classes) && !empty($classes)){ ?>
                                                <?php foreach($classes as $obj ){ ?>
                                                   <?php
                                                    if($this->session->userdata('role_id') == TEACHER){
                                                       if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                    } 
                                                    ?>
                                                   <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>                                            
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 data_subject"  name="section_id" id="add_section_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>      
                                            <?php foreach($sections as $obj ){ ?>
                                                    <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option>
                                            <?php } ?>                                                                                       
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                                               
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 data_subject"  name="subject_id" id="add_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                         
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                
                                                               
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_date"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="exam_date"  id="add_exam_date" value="<?php echo isset($post['exam_date']) ?  $post['exam_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('exam_date'); ?></div>
                                    </div>
                                </div>
                                                               
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_time"><?php echo $this->lang->line('start_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="start_time"  id="add_start_time" value="<?php echo isset($post['start_time']) ?  $post['start_time'] : ''; ?>" placeholder="<?php echo $this->lang->line('start_time'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_time'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_time"><?php echo $this->lang->line('end_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="end_time"  id="add_end_time" value="<?php echo isset($post['end_time']) ?  $post['end_time'] : ''; ?>" placeholder="<?php echo $this->lang->line('end_time'); ?>" required="required" type="text"  autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_time'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="optional">Optional
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  style="float:left" name="optional" value="1" type="checkbox" <?php echo isset($post['optional']) &&  $post['optional'] ==1 ? "checked" : ''; ?>>
                                        <div class="help-block"><?php echo form_error('optional'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no"><?php echo $this->lang->line('room_no'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="room_no"  id="room_no" value="<?php echo isset($post['room_no']) ?  $post['room_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('room_no'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('room_no'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark"><?php echo $this->lang->line('max_mark');  ?> Theory
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark"  id="max_mark" value="<?php echo isset($post['max_mark']) ?  $post['max_mark'] : ''; ?>" placeholder="<?php echo $this->lang->line('max_mark'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('max_mark'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark_p"><?php echo $this->lang->line('max_mark'); ?> Practical
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark_p"  id="max_mark_p" value="<?php echo isset($post['max_mark_p']) ?  $post['max_mark_p'] : ''; ?>" placeholder="<?php echo $this->lang->line('max_mark_p'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('max_mark_p'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark_v"><?php echo $this->lang->line('max_mark');  ?> Viva
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark_v"  id="max_mark_v" value="<?php echo isset($post['max_mark_v']) ?  $post['max_mark_v'] : ''; ?>" placeholder="<?php echo $this->lang->line('max_mark_v'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('max_mark_v'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                               
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('exam/schedule/index/'.$class_id); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('add_exam_schedule_instruction'); ?></div>
                                </div>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_schedule">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('exam/schedule/edit/'.$schedule->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                 
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_id"><?php echo $this->lang->line('exam_term'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="exam_id" id="edit_exam_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($exams as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if($obj->id == $schedule->exam_id){ echo 'selected="selected"'; } ?>><?php echo $obj->title; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('exam_id'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id" id="edit_class_id" required="required" onchange="get_subject_and_section(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                                <?php
                                                if($this->session->userdata('role_id') == TEACHER){
                                                   if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                } 
                                                ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($obj->id == $schedule->class_id){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="section_id" id="edit_section_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($sections as $obj ){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($obj->id == $schedule->section_id){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                                               
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 data_subject"  name="subject_id" id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                         
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                
                                                          
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_date"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="exam_date"  id="edit_exam_date" value="<?php echo isset($schedule->exam_date) ?  date('d-m-Y', strtotime($schedule->exam_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('exam_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_time"><?php echo $this->lang->line('start_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="start_time"  id="edit_start_time" value="<?php echo isset($schedule->start_time) ?  $schedule->start_time : ''; ?>" placeholder="<?php echo $this->lang->line('start_time'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_time'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_time"><?php echo $this->lang->line('end_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="end_time"  id="edit_end_time" value="<?php echo isset($schedule->end_time) ?  $schedule->end_time : ''; ?>" placeholder="<?php echo $this->lang->line('end_time'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_time'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="optional">Optional
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input style="float:left"  name="optional" value="1" type="checkbox" <?php echo isset($schedule->optional) && $schedule->optional ==1 ? "checked" : ''; ?>>
                                        <div class="help-block"><?php echo form_error('optional'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no"><?php echo $this->lang->line('room_no'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="room_no"  id="room_no" value="<?php echo isset($schedule->room_no) ?  $schedule->room_no : ''; ?>" placeholder="<?php echo $this->lang->line('room_no'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('room_no'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark"><?php echo $this->lang->line('max_mark'); ?> Theory 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark"  id="max_mark" value="<?php echo isset($schedule->max_mark) ?  $schedule->max_mark : ''; ?>" placeholder="<?php echo $this->lang->line('max_mark'); ?>"  type="text" autocomplete="off" <?php echo $sMarkDisabled ?>>
                                        <div class="help-block"><?php echo form_error('max_mark'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark_p"><?php echo $this->lang->line('max_mark'); ?>  Practical
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark_p"  id="max_mark_p" value="<?php echo isset($schedule->max_mark_p) ?  $schedule->max_mark_p : ''; ?>"  type="text" autocomplete="off" <?php echo  $sMarkDisabled ?>>
                                        <div class="help-block"><?php echo form_error('max_mark_p'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark_v"><?php echo $this->lang->line('max_mark');  ?> Viva
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark_v"  id="max_mark_v" value="<?php echo isset($schedule->max_mark_v) ?  $schedule->max_mark_v : ''; ?>" placeholder="<?php echo $this->lang->line('max_mark_v'); ?>"  type="text" autocomplete="off" <?php echo $sMarkDisabled ?>>
                                        <div class="help-block"><?php echo form_error('max_mark_v'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($schedule->note) ?  $schedule->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($schedule) ? $schedule->id : $id; ?>" name="id" />
                                        <a  href="<?php echo site_url('exam/schedule/index/'.$class_id); ?>"  class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
                        <?php } ?>
                        <?php if(isset($copy)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_schedule">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('exam/schedule/add/'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                 
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_id"><?php echo $this->lang->line('exam_term'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="exam_id" id="edit_exam_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($exams as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if($obj->id == $schedule->exam_id){ echo 'selected="selected"'; } ?>><?php echo $obj->title; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('exam_id'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id" id="edit_class_id" required="required" onchange="get_subject_and_section(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                                <?php
                                                if($this->session->userdata('role_id') == TEACHER){
                                                   if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                } 
                                                ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($obj->id == $schedule->class_id){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="section_id" id="edit_section_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($sections as $obj ){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($obj->id == $schedule->section_id){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>                         
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 data_subject"  name="subject_id" id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                         
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                
                                                          
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_date"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="exam_date"  id="edit_exam_date" value="<?php echo isset($schedule->exam_date) ?  date('d-m-Y', strtotime($schedule->exam_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('exam_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_time"><?php echo $this->lang->line('start_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="start_time"  id="edit_start_time" value="<?php echo isset($schedule->start_time) ?  $schedule->start_time : ''; ?>" placeholder="<?php echo $this->lang->line('start_time'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_time'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_time"><?php echo $this->lang->line('end_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="end_time"  id="edit_end_time" value="<?php echo isset($schedule->end_time) ?  $schedule->end_time : ''; ?>" placeholder="<?php echo $this->lang->line('end_time'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_time'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no"><?php echo $this->lang->line('room_no'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="room_no"  id="room_no" value="<?php echo isset($schedule->room_no) ?  $schedule->room_no : ''; ?>" placeholder="<?php echo $this->lang->line('room_no'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('room_no'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark"><?php echo $this->lang->line('max_mark'); ?> Theory 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark"  id="max_mark" value="<?php echo isset($schedule->max_mark) ?  $schedule->max_mark : ''; ?>" placeholder="<?php echo $this->lang->line('max_mark'); ?>"  type="text" autocomplete="off" <?php echo $sMarkDisabled ?>>
                                        <div class="help-block"><?php echo form_error('max_mark'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark_p"><?php echo $this->lang->line('max_mark'); ?>  Practical
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark_p"  id="max_mark_p" value="<?php echo isset($schedule->max_mark_p) ?  $schedule->max_mark_p : ''; ?>"  type="text" autocomplete="off" <?php echo $sMarkDisabled ?>>
                                        <div class="help-block"><?php echo form_error('max_mark_p'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max_mark_v"><?php echo $this->lang->line('max_mark');  ?> Viva
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="max_mark_v"  id="max_mark_v" value="<?php echo isset($schedule->max_mark_v) ?  $schedule->max_mark_v : ''; ?>" placeholder="<?php echo $this->lang->line('max_mark_v'); ?>"  type="text" autocomplete="off" <?php echo $sMarkDisabled ?>>
                                        <div class="help-block"><?php echo form_error('max_mark_v'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($schedule->note) ?  $schedule->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a  href="<?php echo site_url('exam/schedule/index/'.$class_id); ?>"  class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('add'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade bs-schedule-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('schedule'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_schedule_data">            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_schedule_modal(schedule_id){
         
        $('.fn_schedule_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('exam/schedule/get_single_schedule'); ?>",
          data   : {schedule_id : schedule_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_schedule_data').html(response);
             }
          }
       });
    }
</script>



 <link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 <link href="<?php echo VENDOR_URL; ?>timepicker/timepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>timepicker/timepicker.js"></script>
 
 
<!-- Super admin js START  -->
 <script type="text/javascript">
     
    var edit = false;
    var copy = false;       

    $("document").ready(function() {
         <?php if(isset($edit) && !empty($edit) && $this->session->userdata('role_id') != TEACHER){ ?>
           edit = true;       
            $("#edit_school_id").trigger('change');
         <?php } ?>
         <?php if(isset($copy) && !empty($copy) && $this->session->userdata('role_id') != TEACHER){ ?>
           copy = true;       
            $("#edit_school_id").trigger('change');
         <?php } ?>
    });
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();        
        var exam_id = '';
        var class_id = '';
        
        <?php if((isset($edit) && !empty($edit)) || (isset($copy) && !empty($copy))){ ?>
            exam_id =  '<?php echo $schedule->exam_id; ?>';           
            class_id =  '<?php echo $schedule->class_id; ?>';           
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
                   if(edit || copy){
                       $('#edit_exam_id').html(response);   
                   }else{
                       $('#add_exam_id').html(response);   
                   }
                   
                   get_class_by_school(school_id, class_id); 
               }
            }
        });
    }); 

   function get_class_by_school(school_id, class_id){       
         
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id:school_id, class_id:class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                    if(edit || copy){
                       $('#edit_class_id').html(response);   
                   }else{
                       $('#add_class_id').html(response);   
                   }                  
               }
            }
        });                  
        
   }
  
   
  </script>
<!-- Super admin js end -->
 
 
 <script type="text/javascript">
     
  $('#add_exam_date').datepicker();  
  $('#add_start_time').timepicker();
  $('#add_end_time').timepicker();
  
  $('#edit_exam_date').datepicker();
  $('#edit_start_time').timepicker();
  $('#edit_end_time').timepicker();
  
    <?php if(isset($edit) || isset($copy)){ ?>
        <?php if(isset($edit)) { ?>
            edit = true;
        <? } ?>
        <?php if(isset($copy)) { ?>
            copy = true;
        <? } ?>
        get_subject_by_class('<?php echo $schedule->class_id; ?>', '<?php echo $schedule->subject_id; ?>');
        get_section_by_class('<?php echo $schedule->class_id; ?>', '<?php echo $schedule->section_id; ?>');
    <?php } ?>
    function get_subject_and_section(class_id, subject_id)
    {
        get_subject_by_class(class_id, '');
        get_section_by_class(class_id, '');
    }
    function get_subject_by_class(class_id, subject_id){       
         
        var school_id = '';
       
        <?php if(isset($edit)){ ?>                
            school_id = $('#edit_school_id').val();
         <?php }else{ ?> 
            school_id = $('#add_school_id').val();
         <?php } ?> 
             
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        } 
         
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : {school_id:school_id, class_id : class_id,  subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                console.log(copy)
                if(edit || copy){
                        $('#edit_subject_id').html(response);
                   }else{
                      $('#add_subject_id').html(response); 
                   }
                   console.log( $('#edit_subject_id').html())
               }
            }
        }); 
   }
   function get_section_by_class(class_id, section_id){       
         
         var school_id = '';
        
         <?php if(isset($edit)){ ?>                
             school_id = $('#edit_school_id').val();
          <?php }else{ ?> 
             school_id = $('#add_school_id').val();
          <?php } ?> 
              
         if(!school_id){
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
         } 
          
         $.ajax({       
             type   : "POST",
             url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
             data   : {school_id:school_id, class_id : class_id,  section_id : section_id},               
             async  : false,
             success: function(response){                                                   
                if(response)
                {
                    if(edit || copy){
                         $('#edit_section_id').html(response);
                    }else{
                       $('#add_section_id').html(response); 
                    }
                }
             }
         }); 
    }
  
</script>

 <script type="text/javascript">
        $(document).ready(function() {
          $('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
              iDisplayLength: 15,
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength'
              ],
              search: true,              
              responsive: true
          });
        }); 
        
    /* Menu Filter Start */ 
    
    function get_schedule_list_by_class(url){          
       if(url){
           window.location.href = url; 
       }
    }        
        
    <?php if(isset($filter_class_id)){ ?>
        get_class_by_school_filter('<?php echo $filter_school_id; ?>', '<?php echo $filter_class_id; ?>');
    <?php } ?>
    
    function get_class_by_school_filter(school_id, class_id){
                
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id : school_id, class_id : class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
                    $('#filter_class_id').html(response);                     
               }
            }
        });
    }    

        
    $("#add").validate();     
    $("#edit").validate(); 
</script>