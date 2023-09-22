<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-file-video-o"></i><small> <?php echo $this->lang->line('manage_class_lecture'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
               
                    <?php if(has_permission(VIEW, 'academic', 'discipline')){ ?>   
                     <a href="<?php echo site_url('disciplines'); ?>"> <?php echo $this->lang->line('discipline'); ?></a>
                    <?php } ?> 

                    <?php if(has_permission(VIEW, 'academic', 'classes')){ ?>
                    | <a href="<?php echo site_url('academic/classes/index'); ?>"> <?php echo $this->lang->line('class'); ?></a>
                    <?php } ?>

                    <?php if(has_permission(VIEW, 'academic', 'liveclass')){ ?>
                     | <a  href="<?php echo site_url('academic/liveclass/index'); ?>"><?php echo $this->lang->line('live_class'); ?></a> 
                    <?php } ?>

                    <?php if(has_permission(VIEW, 'teacher', 'lecture')){ ?>
                    | <a  href="<?php echo site_url('teacher/lecture/index/'); ?>"><?php echo $this->lang->line('class_lecture'); ?></a> 
                    <?php } ?>   
                    <?php if(has_permission(VIEW, 'academic', 'section')){ ?>
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }else{ ?>                         
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php } ?> 
                    <?php } ?>   
                    <?php if(has_permission(VIEW, 'academic', 'subject')){ ?>                            
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }else{ ?>      
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php } ?>
                    <?php } ?>
                    <?php if(has_permission(VIEW, 'academic', 'syllabus')){ ?>                        
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"><?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }else{ ?>      
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>

                        <?php } ?>
                    <?php } ?>     
                    <?php if(has_permission(VIEW, 'academic', 'material')){ ?>                        
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>   
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }else{ ?>      
                          | <a href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?> </a>        
                        <?php } ?>
                    <?php } ?>
                    <?php if(has_permission(VIEW, 'academic', 'routine')){ ?>
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }else{ ?>    
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php } ?>
                        <?php } ?>
            </div>
           
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_lecture_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'teacher', 'lecture')){ ?>
                        
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('teacher/lecture/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                             <?php }else{ ?>
                                 <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_lecture"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>
                                 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_lecture"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>                          
                        <?php } ?>                
                       
                            <li class="li-class-list">
                            
                            <?php $teacher_access_data = get_teacher_access_data(); ?> 
                            <?php $guardian_access_data = get_guardian_access_data('class'); ?>   
                                
                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>
                            
                                <?php echo form_open(site_url('teacher/lecture/index'), array('name' => 'filter', 'id' => 'filter', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                    <select  class="form-control col-md-7 col-xs-12" style="width:auto;" name="school_id"  onchange="get_class_by_school(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select_school'); ?>--</option> 
                                        <?php foreach($schools as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                        <?php } ?>   
                                    </select>
                                    <select  class="form-control col-md-7 col-xs-12" id="filter_class_id" name="class_id"  style="width:auto;" onchange="this.form.submit();">
                                         <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                         
                                    </select>
                                   <?php echo form_close(); ?>
                                                        
                            <?php }else{  ?>
                                
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_lecture_by_class(this.value);">
                                    <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                    <option value="<?php echo site_url('teacher/lecture/index'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                     <?php } ?> 
                                    
                                    <?php foreach($class_list as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?> 
                                            <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>
                                            <?php if (!in_array($obj->id, $guardian_access_data)) { continue; } ?>
                                            <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                            <?php if (!in_array($obj->id, $teacher_access_data)) { continue; } ?>
                                            <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                            
                                    <?php } ?>                                            
                                </select>                               
                            
                            <?php } ?>
                        </li>    
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_lecture_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>                                        
                                            <th><?php echo $this->lang->line('school'); ?></th>                                        
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('teacher'); ?></th>
                                        <th><?php echo $this->lang->line('class_lecture'); ?></th>
                                        <th><?php echo $this->lang->line('academic_year'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>                                       
                                   
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_lecture">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('teacher/lecture/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                 <?php $this->load->view('layout/school_list_form'); ?>  
								
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id"><?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('discipline'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="disciplines"  id="disciplines" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($faculty as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['disciplines']) && $post['disciplines'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('disciplines'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_title"><?php echo $this->lang->line('title'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="lecture_title"  id="lecture_title" value="<?php echo isset($post['lecture_title']) ?  $post['lecture_title'] : ''; ?>" placeholder="<?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('lecture_title'); ?></div>
                                    </div>
                                </div>               
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="add_class_id" required="required" onchange="get_subject_by_class(this.value, ''); get_section_by_class(this.value, '');" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($classes) && !empty($classes) && $this->session->userdata('role_id') != SUPER_ADMIN){ ?>
                                                <?php foreach($classes as $obj ){ ?>
                                                   <?php
                                                    if($this->session->userdata('role_id') == TEACHER){
                                                       if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                    } 
                                                    ?>
                                                  <option value="<?php echo $obj->id; ?>" ><?php echo $obj->name; ?></option>
                                                <?php } ?>                                            
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="section_id"  id="add_section_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="subject_id"  id="add_subject_id" required="required" onchange="get_teacher_by_subject();">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
								
								 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id"><?php echo $this->lang->line('subject'); ?> <?php echo $this->lang->line('teacher'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="teacher_id"  id="add_teacher_id"  >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($teachers as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['teacher_id']) && $post['teacher_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('teacher_id'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_type"><?php echo $this->lang->line('lecture_type'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="lecture_type"  id="lecture_type" required="required" onchange="get_video_lecture_type(this.value, 'add');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php $types = get_video_types(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                                <option value="<?php echo $key; ?>" <?php echo isset($post['lecture_type']) && $post['lecture_type'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('lecture_type'); ?></div>
                                    </div>
                                </div>
                                
                                
                                <div class="item form-group fn_add_lecture_ppt <?php echo isset($post['lecture_type']) && $post['lecture_type'] == 'ppt' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_ppt"><?php echo $this->lang->line('lecture_ppt'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="lecture_ppt"  id="add_lecture_ppt" type="file" >
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_lecture'); ?></div>
                                        <div class="help-block"><?php echo form_error('lecture_ppt'); ?></div>
                                    </div>
                                </div>                                
                                <div class="item form-group fn_add_lecture_url <?php echo isset($post['lecture_type']) && $post['lecture_type'] != 'ppt' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="video_id"><?php echo $this->lang->line('video_id'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="video_id"  id="add_video_id" value="<?php echo isset($post['video_id']) ?  $post['video_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('video_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('video_id'); ?></div>
                                    </div>
                                </div> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="add_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                               
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('teacher/lecture/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('add_lecture_instruction'); ?></div>
                                </div>
                            </div>
                        </div>  

                        
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_lecture">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('teacher/lecture/edit/'.$lecture->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                 <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                
                                 

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_title"><?php echo $this->lang->line('title'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="lecture_title"  id="lecture_title" value="<?php echo isset($lecture->lecture_title) ?  $lecture->lecture_title : ''; ?>" placeholder="<?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('lecture_title'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="edit_class_id" required="required" onchange="get_subject_by_class(this.value, ''); get_section_by_class(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($classes) && !empty($classes) && $this->session->userdata('role_id') != SUPER_ADMIN){ ?>
                                                <?php foreach($classes as $obj ){ ?>
                                                    <?php
                                                    if($this->session->userdata('role_id') == TEACHER){
                                                       if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                    } 
                                                    ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($lecture->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>                                            
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>

                                    <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id"><?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('discipline'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="disciplines"  id="edit_disciplines" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($teachers as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['teacher_id']) && $post['teacher_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('disciplines'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="section_id"  id="edit_section_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
				                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="subject_id"  id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                                                                        
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_type"><?php echo $this->lang->line('lecture_type'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="lecture_type"  id="lecture_type" required="required" onchange="get_video_lecture_type(this.value, 'edit');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php $types = get_video_types(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                                <option value="<?php echo $key; ?>" <?php if($lecture->lecture_type == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('lecture_type'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group fn_edit_lecture_ppt <?php echo isset($lecture->lecture_type) && $lecture->lecture_type == 'ppt' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_ppt"><?php echo $this->lang->line('lecture_ppt'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                         <input type="hidden" name="prev_lecture_ppt" id="prev_lecture_ppt" value="<?php echo $lecture->lecture_ppt; ?>" />
                                        <?php if($lecture->lecture_ppt){ ?>
                                            <a target="_blank" href="<?php echo UPLOAD_PATH; ?>/video-lecture/<?php echo $lecture->lecture_ppt; ?>"><?php echo $lecture->lecture_ppt; ?></a> <br/><br/>
                                        <?php } ?>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="lecture_ppt"  id="add_lecture_ppt" type="file" >
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_lecture'); ?></div>
                                        <div class="help-block"><?php echo form_error('lecture_ppt'); ?></div>
                                    </div>
                                </div>                                
                                <div class="item form-group fn_edit_lecture_url <?php echo isset($lecture->lecture_type) && $lecture->lecture_type != 'ppt' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="video_id"><?php echo $this->lang->line('video_id'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="video_id"  id="edit_video_id" value="<?php echo isset($lecture->video_id) ?  $lecture->video_id : ''; ?>" placeholder="<?php echo $this->lang->line('video_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('video_id'); ?></div>
                                    </div>
                                </div> 
                                
                             
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="edit_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($lecture->note) ?  $lecture->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($lecture) ? $lecture->id : $id; ?>" name="id" />
                                        <a  href="<?php echo site_url('teacher/lecture/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
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


<div class="modal fade bs-lecture-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_lecture_data">            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_lecture_modal(lecture_id){
         
        $('.fn_lecture_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('teacher/lecture/get_single_lecture'); ?>",
          data   : {lecture_id : lecture_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_lecture_data').html(response);
             }
          }
       });
    }
    
    $(".modal .close").click(function(){
          jQuery(".modal iframe").attr("src", jQuery(".modal iframe").attr("src"));
    })
</script>

<!-- Super admin js START  -->
 <script type="text/javascript">
     
    function get_video_lecture_type(leture_type, form_type){
        if(leture_type == 'ppt'){
            $('.fn_'+form_type+'_lecture_ppt').show();
            $('.fn_'+form_type+'_lecture_url').hide();
        }else{
            $('.fn_'+form_type+'_lecture_url').show();
            $('.fn_'+form_type+'_lecture_ppt').hide();
        }
    }
     
    $("document").ready(function() {
         <?php if(isset($edit) && !empty($edit)  && $this->session->userdata('role_id') != TEACHER){ ?>
            $("#edit_school_id").trigger('change');
         <?php } elseif(isset($filter_school_id) && $filter_school_id>=0){ ?>
		 $(".fn_school_id").trigger('change');
		 <?php } ?>
    });
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();        
        var class_id = '';
        
        <?php if(isset($edit) && !empty($edit)){ ?>
            class_id =  '<?php echo $lecture->class_id; ?>';           
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select_school'); ?>');
           return false;
        }
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id:school_id, class_id:class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(class_id){
                       $('#edit_class_id').html(response);   
                   }else{
                       $('#add_class_id').html(response);   
                   }                  
               }
            }
        });
    }); 

  </script>
<!-- Super admin js end -->

 <script type="text/javascript">

    var edit = false;
    <?php if(isset($edit)){ ?>
        edit = true;
        get_subject_by_class('<?php echo $lecture->class_id; ?>', '<?php echo $lecture->subject_id; ?>');
        get_section_by_class('<?php echo $lecture->class_id; ?>', '<?php echo $lecture->section_id; ?>');
    <?php } ?>
    
    function get_subject_by_class(class_id, subject_id){       
        
        var school_id = '';
        
        <?php if(isset($edit)){ ?>                
            school_id = $('#edit_school_id').val();
         <?php }else{ ?> 
            school_id = $('#add_school_id').val();
         <?php } ?> 
             
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select_school'); ?>');
           return false;
        }
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : {school_id:school_id, class_id : class_id , subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                   if(edit){
                        $('#edit_subject_id').html(response);
                   }else{
                        $('#add_subject_id').html(response);
                   }
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
           toastr.error('<?php echo $this->lang->line('select_school'); ?>');
           return false;
        }
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : {school_id:school_id, class_id : class_id , section_id : section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                   if(edit){
                        $('#edit_section_id').html(response);
                   }else{
                        $('#add_section_id').html(response);
                   }
               }
            }
        });
    }
   
   
   
   /* Menu Filter Start */
    function get_lecture_by_class(url){          
        if(url){
            window.location.href = url; 
        }
    }
    
 </script>
  <script type="text/javascript">
        $(document).ready(function() {
			var sch_id='<?php print $filter_school_id; ?>';
			var cls_id = '<?php print $class_id; ?>';
          $('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
			   'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("teacher/lecture/get_list"); ?>',
		  'data': {'school_id': sch_id,class_id:cls_id}
      },
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
        
        
    <?php if(isset($filter_class_id)){ ?>
        get_class_by_school('<?php echo $filter_school_id; ?>', '<?php echo $filter_class_id; ?>');
    <?php } ?>
    
    function get_class_by_school(school_id, class_id){
        
        
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
	    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();       
        var teacher_id = '';
        <?php if(isset($class) && !empty($class)){ ?>         
            teacher_id =  '<?php echo $class->teacher_id; ?>';
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
        
         $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_teacher_by_school'); ?>",
            data   : { school_id:school_id, teacher_id : teacher_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(teacher_id){
                       $('#edit_teacher_id').html(response);
                   }else{
                       $('#add_teacher_id').html(response); 
                   }
               }
            }
        });       
     
    });       
         $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();       
        var discipline_id = '';
        <?php if(isset($class) && !empty($class)){ ?>         
            discipline_id =  '<?php echo $class->discipline_id; ?>';
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
        
         $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/disciplines'); ?>",
            data   : { school_id:school_id, discipline_id : discipline_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(discipline_id){
                       $('#edit_disciplines').html(response);
                   }else{
                       $('#disciplines').html(response); 
                   }
               }
            }
        });       
     
    }); 
	  
	  
</script>

<?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){ 
?>

 <script>
      $("document").ready(function() {
      
        var school_id = "<?php echo $this->session->userdata('school_id') ?>";       
        var discipline_id = '';
        
        <?php if(isset($class) && !empty($class)){ ?>         
            discipline_id =  '<?php echo $class->discipline_id; ?>';
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
        
         $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/disciplines'); ?>",
            data   : { school_id:school_id, discipline_id : discipline_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(discipline_id){
                       $('#edit_disciplines').html(response);
                   }else{
                       $('#edit_disciplines').html(response); 
                   }
               }
            }
        });       
     
    }); 

  </script>

<?php }?>