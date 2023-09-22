
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-clipboard"></i><small> <?php echo $this->lang->line('manage_syllabus'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>


            <!--  -->
            <?php  $this->load->view('layout/academic_quick-link');   ?>

            <!--  -->
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_syllabus_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('syllabus'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'academic', 'syllabus')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('academic/syllabus/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('syllabus'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_syllabus"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('syllabus'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_syllabus"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('syllabus'); ?></a> </li>                          
                        <?php } ?>                
                        <?php if(isset($detail)){ ?>
                            <li  class="active"><a href="#tab_view_syllabus"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> <?php echo $this->lang->line('syllabus'); ?></a> </li>                          
                        <?php } ?>
                          
                            <li class="li-class-list">
                                <?php $teacher_access_data = get_teacher_access_data('student'); ?>    
                                <?php $guardian_class_data = get_guardian_access_data('class'); ?>
                                
                                 <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN || $this->session->userdata('dadmin') ){  ?> 
                                
                                    <?php echo form_open(site_url('academic/syllabus/index'), array('name' => 'filter', 'id' => 'filter', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                    <select  class="form-control col-md-7 col-xs-12" style="width:auto;" name="school_id"  onchange="get_class_by_school(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                        <?php foreach($schools as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                        <?php } ?>   
                                    </select>
                                    <select  class="form-control col-md-7 col-xs-12" id="filter_class_id" name="class_id"  style="width:auto;" onchange="this.form.submit();">
                                         <option value="">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('class'); ?>--</option> 
                                        <?php if(isset($class_list) && !empty($class_list)){ ?>
                                            <?php foreach($class_list as $obj ){ ?>
                                                <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                   <?php echo form_close(); ?>
                                
                                 <?php }else{  ?> 
                                    <select  class="form-control col-md-7 col-xs-12" onchange="get_subject_list_by_class(this.value);">
                                        <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                            <option value="<?php echo site_url('academic/syllabus/index/'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <option value="<?php echo site_url('academic/syllabus/index/0'); ?>">All</option> 
                                        <?php } ?> 
                                                                                        
                                        <?php foreach($classes as $obj ){ ?>
                                            <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                                <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?>
                                                <option value="<?php echo site_url('academic/syllabus/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>
                                                 <?php if (!in_array($obj->id, $guardian_class_data)) { continue; } ?>
                                                 <option value="<?php echo site_url('academic/syllabus/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                               <?php if (!in_array($obj->id, $teacher_access_data)) { continue; } ?>
                                                 <option value="<?php echo site_url('academic/syllabus/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php }else{ ?>
                                                 <option value="<?php echo site_url('academic/syllabus/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        <?php } ?>                                            
                                    </select>                                    
                                <?php } ?>
                            </li>
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_syllabus_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                         <?php if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('syllabus'); ?> <?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('session_year'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                   
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_syllabus">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('academic/syllabus/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?> 

                                 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id"><?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('discipline'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="disciplines"  id="disciplines" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($teachers as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['teacher_id']) && $post['teacher_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('disciplines'); ?></div>
                                    </div>
                                </div>
                                       
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('syllabus'); ?> <?php echo $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($post['title']) ?  $post['title'] : ''; ?>" placeholder="<?php echo $this->lang->line('syllabus'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>               
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="add_class_id" required="required" onchange="get_subject_by_class(this.value, '');" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <option value="0">All</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                                <?php
                                                if($this->session->userdata('role_id') == TEACHER){
                                                   if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                } 
                                                ?>
                                                <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 data_subject"  name="subject_id"  id="add_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>     
                                            <option value="0">All</option>                                                                                  
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                
                               <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('syllabus'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="syllabus" required="required"  id="syllabus" type="file">
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_doc'); ?></div>
                                        <div class="help-block"><?php echo form_error('syllabus'); ?></div>
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
                                        <a href="<?php echo site_url('academic/syllabus'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('add_syllabus_instruction'); ?></div>
                                </div>
                            </div>
                        </div>  

                        
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_syllabus">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('academic/syllabus/edit/'.$syllabus->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                <input type="hidden" name="id" value="<?php echo $syllabus->id ?>">
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('syllabus'); ?> <?php echo $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($syllabus->title) ?  $syllabus->title : ''; ?>" placeholder="<?php echo $this->lang->line('syllabus'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="edit_class_id" required="required" onchange="get_subject_by_class(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                                <?php
                                                if($this->session->userdata('role_id') == TEACHER){
                                                   if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                } 
                                                ?>
                                               <option value="<?php echo $obj->id; ?>" <?php if($syllabus->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                       

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id"><?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('discipline'); ?> 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select  class="form-control col-md-7 col-xs-12"  name="disciplines"  id="edit_disciplines"  >
                                        <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                                               
                                    </select>
                                    <div class="help-block"><?php echo form_error('disciplines'); ?></div>
                                </div>
                            </div>

                             



                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 data_subject"  name="subject_id"  id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>



                        
                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('syllabus'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" name="prev_syllabus" id="prev_syllabus" value="<?php echo $syllabus->syllabus; ?>" />
                                        <?php if($syllabus->syllabus){ ?>
                                        <a href="<?php echo UPLOAD_PATH; ?>/syllabus/<?php echo $syllabus->syllabus; ?>"><?php echo $syllabus->syllabus; ?></a> <br/>
                                        <?php } ?>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="syllabus"  id="syllabus" type="file">
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_doc'); ?></div>
                                        <div class="help-block"><?php echo form_error('syllabus'); ?></div>
                                    </div>
                                </div>
                             
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="edit_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($syllabus->note) ?  $syllabus->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($syllabus) ? $syllabus->id : $id; ?>" name="id" />
                                        <a href="<?php echo site_url('academic/syllabus'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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



<div class="modal fade bs-syllabus-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('syllabus'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_syllabus_data">            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_syllabus_modal(syllabus_id){
         
        $('.fn_syllabus_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('academic/syllabus/get_single_syllabus'); ?>",
          data   : {syllabus_id : syllabus_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_syllabus_data').html(response);
             }
          }
       });
    }
</script>


<!-- Super admin js START  -->
 <script type="text/javascript">
     
    $("document").ready(function() {
         <?php if(isset($edit) && !empty($edit) && $this->session->userdata('role_id') != TEACHER){ ?>
            $("#add_school_id").trigger('change');
         <?php } ?>
    });
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();        
        var class_id = '';
        
        <?php if(isset($edit) && !empty($edit)){ ?>
            class_id =  '<?php echo $syllabus->class_id; ?>';           
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
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
        get_subject_by_class('<?php echo $syllabus->class_id; ?>', '<?php echo $syllabus->subject_id; ?>');
    <?php } ?>
    
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
            data   : { school_id:school_id, class_id : class_id, subject_id : subject_id},               
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

 </script>
 
  <link href="<?php echo VENDOR_URL; ?>editor/jquery-te-1.4.0.css" rel="stylesheet">
 <script type="text/javascript" src="<?php echo VENDOR_URL; ?>editor/jquery-te-1.4.0.min.js"></script>
 <script type="text/javascript">     
  $('#add_note').jqte();
  $('#edit_note').jqte();
  
  </script>
 
 <!-- datatable with buttons -->
 <script type="text/javascript">
 $.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param * 1000000)
}, 'File size must be less than {0} MB');

$('#add').validate({
    rules: {       
        syllabus: {
            required: true,
            //extension: "jep | jpeg",
            filesize : 7, // here we are working with MB
        }
    }
});
<?php if(!isset( $syllabus->syllabus) || !$syllabus->syllabus){ ?>
$('#edit').validate({
    rules: {       
        syllabus: {
            required: true,
            //extension: "jep | jpeg",
            filesize : 7, // here we are working with MB
        }
    }
});
<?php }?>

        $(document).ready(function() {
            var sch_id='<?php print $filter_school_id; ?>';
			var cls_id = '<?php print $class_id; ?>';
          $('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
              'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("academic/syllabus/get_list"); ?>',
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
		  /*$('#add').validate({
				rules: { syllabus: { filesize: 1048576  }},
				messages: { syllabus: "File must be 1MB" }
			});*/
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
    
    function get_subject_list_by_class(url){          
        if(url){
            window.location.href = url; 
        }
    } 
    $("#add").validate();     
    $("#edit").validate();

      $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();       
        var teacher_id = '';
        
         $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/disciplines'); ?>",
            data   : { school_id:school_id, teacher_id : teacher_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(teacher_id){
                       $('#disciplines').html(response);
                   }else{
                       $('#disciplines').html(response); 
                   }
               }
            }
        });       
     
    });      


 




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
            url    : "<?php echo site_url('ajax/disciplines'); ?>",
            data   : { school_id:school_id, teacher_id : teacher_id},               
            async  : false,
            success: function(response){                                                   
                 if(response)
               {    
                   if(teacher_id){
                       $('#edit_disciplines').html(response);
                   }else{
                       $('#edit_disciplines').html(response); 
                   }
               }
            }
        });       
     
    }); 

 
       
</script>
 

</script>

<?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){ 
?>
e
 <script>
$("document").ready(function() {
      
        var school_id = "<?php echo $this->session->userdata('school_id') ?>";       
        var teacher_id = '';
        
         $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/disciplines'); ?>",
            data   : { school_id:school_id, teacher_id : teacher_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(teacher_id){
                       $('#disciplines').html(response);
                   }else{
                       $('#disciplines').html(response); 
                   }
               }
            }
        });       
     
    }); 

      $("document").ready(function() {
      
        var school_id = "<?php echo $this->session->userdata('school_id') ?>";       
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
            url    : "<?php echo site_url('ajax/disciplines'); ?>",
            data   : { school_id:school_id, teacher_id : teacher_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(teacher_id){
                       $('#edit_disciplines').html(response);
                   }else{
                       $('#edit_disciplines').html(response); 
                   }
               }
            }
        });       
     
    }); 
    $('#edit_disciplines').on('change', function() {
        var faculty = $(this).val();
        if (edit) {
            var class_id =  $('#edit_class_id').val();
        } else {
            var class_id =   $('#add_class_id').val();
        }
        get_class_by_faculty(faculty,class_id)
    });
    $('#disciplines').on('change', function() {
        var faculty = $(this).val();
        if (edit) {
            var class_id =  $('#edit_class_id').val();
        } else {
            var class_id =   $('#add_class_id').val();
        }
        get_class_by_faculty(faculty,class_id)
    });
    function get_class_by_faculty(faculty,class_id) {

        var school_id = '';

        <?php if (isset($edit)) { ?>
            school_id = $('#edit_school_id').val();
        <?php } else { ?>
            school_id = $('#add_school_id').val();
        <?php } ?>

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select_school'); ?>');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_classes_by_faculty'); ?>",
            data: {
                school_id: school_id,
                faculty: faculty,
                class_id : class_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (edit) {
                        $('#edit_class_id').html(response);
                    } else {
                        $('#add_class_id').html(response);
                    }
                }
            }
        });
        }

    
  </script>



<?php }?>