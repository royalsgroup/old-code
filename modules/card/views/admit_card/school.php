<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-barcode"></i><small> <?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
           
            <div class="x_content quick-link">
                <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                
                <?php if(has_permission(VIEW, 'card', 'idsetting')){ ?>
                     <a href="<?php echo site_url('card/idsetting/index'); ?>"><?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>    
                 <?php if(has_permission(VIEW, 'card', 'admitsetting')){ ?>
                      |  <a href="<?php echo site_url('card/admitsetting/index'); ?>"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>  
                      
                <?php if(has_permission(VIEW, 'card', 'schoolidsetting')){ ?>
                    <a href="<?php echo site_url('card/schoolidsetting/index'); ?>"><?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>       
                <?php if(has_permission(VIEW, 'card', 'schooladmitsetting')){ ?>
                      | <a href="<?php echo site_url('card/schooladmitsetting/index'); ?>"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>

                <?php if(has_permission(VIEW, 'card', 'teacher')){ ?>
                    | <a href="<?php echo site_url('card/teacher/index'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'card', 'employee')){ ?>
                   | <a href="<?php echo site_url('card/employee/index'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'card', 'student')){ ?>  
                   | <a href="<?php echo site_url('card/student/index'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?></a>
                <?php } ?>  
                <?php if(has_permission(VIEW, 'card', 'admit')){ ?>  
                   | <a href="<?php echo site_url('card/admit/index'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?></a>
                <?php } ?>  
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="active"><a href="#tab_setting_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-gears"></i> <?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></a> </li>
                    </ul>
                    <br/>
                    
                    <div class="tab-content">                    

                        <div  class="tab-pane fade in <?php if(empty($setting)){ echo 'active'; }?>" id="tab_add_setting">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('card/schooladmitsetting/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                              
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="border_color"><?php echo $this->lang->line('border'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="border_color"  id="border_color" value="e01ab5" placeholder="<?php echo $this->lang->line('border'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                             <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('border_color'); ?></div>
                                    </div>
                                </div>
                                 
                                    
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="top_bg"><?php echo $this->lang->line('top'); ?> <?php echo $this->lang->line('background'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="top_bg"  id="top_bg" value="e01ab5" placeholder="<?php echo $this->lang->line('top'); ?> <?php echo $this->lang->line('background'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('top_bg'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name"><?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_name"  id="school_name" value="" placeholder="<?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('school_name'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name_font_size"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_name_font_size"  id="school_name_font_size" max="28" value="" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('font_size'); ?>" type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('school_name_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name_color"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_name_color"  id="school_name_color" readonly="readonly" value="e01ab5" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                         <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('school_name_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_address"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_address"  id="school_address" value="" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('school_address'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_address_color"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('color'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_address_color"  id="school_address_color" readonly="readonly"  value="e01ab5" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                        <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('school_address_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admit_font_size"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="admit_font_size"  id="admit_font_size" max="20" value="" placeholder="<?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('admit_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admit_color"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="admit_color"  id="admit_color" readonly="readonly" value="e01ab5" placeholder="<?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('admit_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admit_bg"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('background'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="admit_bg"  id="admit_bg" readonly="readonly" value="e01ab5" placeholder="<?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('background'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('admit_bg'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_font_size"><?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title_font_size"  id="title_font_size" max="12" value="" placeholder="<?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_color"><?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="title_color"  id="title_color" readonly="readonly" value="e01ab5" placeholder="<?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('title_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="value_font_size"><?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="value_font_size"  id="value_font_size" max="13" value="" placeholder="<?php echo $this->lang->line('body'); ?> <?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('value_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="value_color"><?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="value_color"  id="value_color" readonly="readonly" value="e01ab5" placeholder="<?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('value_color'); ?></div>
                                    </div>
                                </div>   
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_font_size"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('font_size'); ?>  </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="exam_font_size"  id="exam_font_size"  placeholder="<?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('exam_font_size'); ?></div>
                                    </div>
                                </div>                                
                                                                    
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_color"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('colot'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="exam_color"  id="exam_color" value="e01ab5" placeholder="<?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('exam_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_font_size"><?php echo $this->lang->line('subject'); ?> <?php echo $this->lang->line('font_size'); ?>  </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="subject_font_size"  id="subject_font_size"  placeholder="<?php echo $this->lang->line('subject'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('subject_font_size'); ?></div>
                                    </div>
                                </div>                                
                                                                    
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_color"><?php echo $this->lang->line('subject'); ?> <?php echo $this->lang->line('colot'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="subject_color"  id="subject_color" value="e01ab5" placeholder="<?php echo $this->lang->line('subject'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('subject_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_bg"><?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('background'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="bottom_bg"  id="bottom_bg" value="e01ab5" placeholder="<?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('background'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('bottom_bg'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_text"><?php echo $this->lang->line('bottom'); ?> <?php echo $this->lang->line('signature'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="bottom_text"  id="bottom_text" value="" required="required" placeholder="<?php echo $this->lang->line('signature'); ?> " type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('bottom_text'); ?></div>
                                    </div>
                                </div>    
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_text_color"><?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('color'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                        <input  class="form-control col-md-7 col-xs-12"  name="bottom_text_color"  id="bottom_text_color" readonly="readonly"  value="e01ab5" placeholder="<?php echo $this->lang->line('bottom_text'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                        <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('bottom_text_color'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_text_align"><?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('align'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php $aligns = get_card_bottom_text_align(); ?>
                                        <select  class="form-control col-md-12 col-xs-12"  name="bottom_text_align"  id="bottom_text_align">
                                            <option value="">--<?php echo $this->lang->line('select'); ?> --</option> 
                                            <?php foreach($aligns as $key=>$value ){ ?>
                                                <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('bottom_text_align'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_logo"><?php echo $this->lang->line('admit'); ?>  <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('logo'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">                                         
                                        <div class="btn btn-default btn-file"><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="logo" id="logo" type="file">
                                        </div>
                                        <div class="help-block"><?php echo form_error('logo'); ?></div>
                                    </div>
                                </div>
                                  
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" id="school_id" name="school_id" value="<?php echo $this->session->userdata('school_id'); ?>"/>
                                        <a href="<?php echo site_url('card/admitsetting/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
                        
                        
                        <div  class="tab-pane fade in <?php if(!empty($setting)){ echo 'active'; }?>" id="tab_edit_setting">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('card/schooladmitsetting/edit/'.$setting->id), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                             
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="border_color"><?php echo $this->lang->line('border'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="border_color"  id="border_color" value="<?php echo isset($setting->border_color) ?  $setting->border_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('border'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                             <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('border_color'); ?></div>
                                    </div>
                                </div>      
                                    
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="top_bg"><?php echo $this->lang->line('top'); ?> <?php echo $this->lang->line('background'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="top_bg"  id="top_bg" value="<?php echo isset($setting->top_bg) ?  $setting->top_bg : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('top'); ?> <?php echo $this->lang->line('background'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('top_bg'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name"><?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_name"  id="school_name" value="<?php echo isset($setting->school_name) ?  $setting->school_name : ''; ?>" placeholder="<?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('school_name'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name_font_size"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_name_font_size" max="28"  id="school_name_font_size" value="<?php echo isset($setting->school_name_font_size) ?  $setting->school_name_font_size : ''; ?>" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('font_size'); ?>" type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('school_name_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name_color"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_name_color"  id="school_name_color" readonly="readonly" value="<?php echo isset($setting->school_name_color) ?  $setting->school_name_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                         <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('school_name_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_address"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_address"  id="school_address" value="<?php echo isset($setting->school_address) ?  $setting->school_address : ''; ?>" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('school_address'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_address_color"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('color'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                        <input  class="form-control col-md-7 col-xs-12"  name="school_address_color"  id="school_address_color" readonly="readonly"  value="<?php echo isset($setting->school_address_color) ?  $setting->school_address_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                        <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('school_address_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admit_font_size"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="admit_font_size"  id="admit_font_size" max="20" value="<?php echo isset($setting->admit_font_size) ?  $setting->admit_font_size : ''; ?>" placeholder="<?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('admit_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admit_color"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="admit_color"  id="admit_color" readonly="readonly" value="<?php echo isset($setting->admit_color) ?  $setting->admit_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('admit_color'); ?></div>
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admit_bg"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('background'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="admit_bg"  id="admit_bg" readonly="readonly" value="<?php echo isset($setting->admit_bg) ?  $setting->admit_bg : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('background'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('admit_bg'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_font_size"><?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title_font_size"  id="title_font_size" max="12" value="<?php echo isset($setting->title_font_size) ?  $setting->title_font_size : ''; ?>" placeholder="<?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_color"><?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="title_color"  id="title_color" readonly="readonly" value="<?php echo isset($setting->title_color) ?  $setting->title_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('title'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('title_color'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="value_font_size"><?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="value_font_size"  id="value_font_size" max="13" value="<?php echo isset($setting->value_font_size) ?  $setting->value_font_size : ''; ?>" placeholder="<?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('value_font_size'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="value_color"><?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="value_color"  id="value_color" readonly="readonly" value="<?php echo isset($setting->value_color) ?  $setting->value_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('value'); ?> <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('value_color'); ?></div>
                                    </div>
                                </div>  
                                
                                 <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_font_size"><?php echo $this->lang->line('exam'); ?>  <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="exam_font_size"  id="exam_font_size" max="15" value="<?php echo isset($setting->exam_font_size) ?  $setting->exam_font_size : ''; ?>" placeholder="<?php echo $this->lang->line('exam'); ?>  <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('exam_font_size'); ?></div>
                                    </div>
                                </div>
                                 <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_color"><?php echo $this->lang->line('exam'); ?>  <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="exam_color"  id="exam_color" readonly="readonly" value="<?php echo isset($setting->exam_color) ?  $setting->exam_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('exam'); ?>  <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('exam_color'); ?></div>
                                    </div>
                                </div>  
                                      
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_font_size"><?php echo $this->lang->line('subject'); ?>  <?php echo $this->lang->line('font_size'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="subject_font_size"  id="subject_font_size" max="13" value="<?php echo isset($setting->subject_font_size) ?  $setting->subject_font_size : ''; ?>" placeholder="<?php echo $this->lang->line('subject'); ?>  <?php echo $this->lang->line('font_size'); ?> " type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('subject_font_size'); ?></div>
                                    </div>
                                </div>
                                 <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_color"><?php echo $this->lang->line('subject'); ?>  <?php echo $this->lang->line('color'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="subject_color"  id="subject_color" readonly="readonly" value="<?php echo isset($setting->subject_color) ?  $setting->subject_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('subject'); ?>  <?php echo $this->lang->line('color'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('subject_color'); ?></div>
                                    </div>
                                </div>  
                                      
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_bg"><?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('background'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12 " readonly="readonly"  name="bottom_bg"  id="bottom_bg" readonly="readonly"  value="<?php echo isset($setting->bottom_bg) ?  $setting->bottom_bg : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('bottom'); ?> <?php echo $this->lang->line('background'); ?> " type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('bottom_bg'); ?></div>
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_text"><?php echo $this->lang->line('bottom'); ?> <?php echo $this->lang->line('signature'); ?><span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="bottom_text"  id="bottom_text" required="required" value="<?php echo isset($setting->bottom_text) ?  $setting->bottom_text : ''; ?>" placeholder="<?php echo $this->lang->line('bottom'); ?> <?php echo $this->lang->line('signature'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('bottom_text'); ?></div>
                                    </div>
                                </div> 
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_text_color"><?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('color'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group fn_colorpicker">
                                            <input  class="form-control col-md-7 col-xs-12"  name="bottom_text_color"  id="bottom_text_color" readonly="readonly"  value="<?php echo isset($setting->bottom_text_color) ?  $setting->bottom_text_color : 'e01ab5'; ?>" placeholder="<?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('color'); ?>" type="text" autocomplete="off">
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                        <div class="help-block"><?php echo form_error('bottom_text_color'); ?></div>
                                    </div>
                                </div>    
                                
                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bottom_text_align"><?php echo $this->lang->line('signature'); ?> <?php echo $this->lang->line('align'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php $aligns = get_card_bottom_text_align(); ?>
                                        <select  class="form-control col-md-12 col-xs-12"  name="bottom_text_align"  id="bottom_text_align">
                                            <option value="">--<?php echo $this->lang->line('select'); ?> --</option> 
                                            <?php foreach($aligns as $key=>$value ){ ?>
                                                <option value="<?php echo $key; ?>" <?php echo isset($setting->bottom_text_align) && $setting->bottom_text_align == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('bottom_text_align'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_logo"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('logo'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">  
                                           
                                            <?php if($setting->school_logo){ ?>
                                             <input type="hidden" name="prev_logo" id="prev_logo" value="<?php echo $setting->school_logo; ?>" />
                                            <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $setting->school_logo; ?>" alt="" width="70" /><br/><br/>
                                            <?php } ?>
                                            
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?> 
                                            <input  class="form-control col-md-7 col-xs-12"  name="logo" id="logo" type="file" />
                                        </div>
                                        <div class="help-block"><?php echo form_error('school_logo'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" id="school_id" name="school_id" value="<?php echo $this->session->userdata('school_id'); ?>"/>
                                        <input type="hidden" id="id" name="id" value="<?php if(isset($setting)){ echo $setting->id;} ?>"/>
                                        <a href="<?php echo site_url('card/schooladmitsetting/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
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



<div class="modal fade bs-setting-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_setting_data">            
        </div>       
      </div>
    </div>
</div>

<script type="text/javascript">
         
    function get_setting_modal(setting_id){
         
        $('.fn_setting_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('card/admitsetting/get_single_admit_setting'); ?>",
          data   : {setting_id : setting_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_setting_data').html(response);
             }
          }
       });
    }
</script>

<link href="<?php echo VENDOR_URL; ?>colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>colorpicker/bootstrap-colorpicker.min.js"></script> 

 <script type="text/javascript">
     
    $('.fn_colorpicker').colorpicker();
   
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
      
    $("#add").validate();   
    $("#edit").validate();
    
</script> 
