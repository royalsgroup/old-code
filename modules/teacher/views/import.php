<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-users"></i><small> <?php echo $this->lang->line('import')." ".$this->lang->line('teacher'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>           
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(ADD, 'teacher', 'teacher')){ ?>
                            <li  class="active"><a href="#tab_add_bulk_teacher"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('import'); ?> <?php echo $this->lang->line('teacher'); ?></a> </li>                          
                        <?php } ?>                        
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                       
                        <div  class="tab-pane fade in active" id="tab_add_bulk_teacher">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('/teacher/import/teacher'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                       
                                <div class="row">                                      
                                
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <?php $schools = get_school_list(); ?>                                        
                                       <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="school_id"><?php echo $this->lang->line('school'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12 fn_school_id" name="school_id" id="school_id" required="required">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php foreach($schools as $obj){ ?>
                                                        <option value="<?php echo $obj->id; ?>" <?php if(isset($school_id) && $school_id == $obj->id){echo 'selected="selected"';} ?>><?php echo $obj->school_name; ?></option>
                                                    <?php } ?>
                                             </select>
                                            <div class="help-block"><?php echo form_error('school_id'); ?></div>
                                         </div>
                                     </div>
                                        <?php }else{ ?>                                       
                                            <input class="fn_school_id" type="hidden" name="school_id" id="school_id" value="<?php echo $this->session->userdata('school_id'); ?>" />
                                        <?php } ?>
                                    

                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                         <div class="item form-group">
                                             <label ><?php echo $this->lang->line('csv_file'); ?>&nbsp;</label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="bulk_teacher"  id="bulk_teacher" type="file">
                                            </div>
                                         </div>
                                     </div>
                                </div>
                                
                                                            
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a  href="<?php echo site_url('teacher/import/employee'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                                                
                                
                            </div>
                        </div>  
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
 <script type="text/javascript">      
    $("#add").validate();     
</script>