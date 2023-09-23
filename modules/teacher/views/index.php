<?php
$admin_dadmin =0;
 $adminonly = 'readonly';
 $admindisable = "disabled";
if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 )
{   
    $admin_dadmin =1;
    $adminonly = ' required="required" ';
    $admindisable = 'required="required"';
}
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-users"></i><small> <?php echo $this->lang->line('manage_teacher'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>    

              <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'teacher', 'teacher')){ ?>
                 <a href="<?php echo site_url('teacher'); ?>"><?php echo $this->lang->line('teacher'); ?></a> | 
                <?php } ?>                                              
                 <?php if(has_permission(VIEW, 'teacher', 'alumniteachers')){ ?>
                  <a href="<?php echo site_url('teacher/alumni'); ?>"><?php echo $this->lang->line('alumni_teachers'); ?></a>  
                 <?php } ?>            
            </div>
              
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_teacher_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        
                        <?php if(has_permission(ADD, 'teacher', 'teacher')){ ?>
                        
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('teacher/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('teacher'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class=""><a href="#tab_add_teacher"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('teacher'); ?></a> </li>                          
                             <?php } ?>
                        
                        <?php } ?>  
                        
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_teacher"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('teacher'); ?></a> </li>                          
                        <?php } ?>   
                            
                         <?php if(isset($detail)){ ?>
                            <li  class="active"><a href="#tab_view_teacher"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> <?php echo $this->lang->line('teacher'); ?></a> </li>                          
                        <?php } ?>   
                         
                        <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_teacher_by_school(this.value);">
                                        <option value="<?php echo site_url('teacher/index/'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('teacher/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                    <?php } ?>   
                                </select>
                            <?php } ?>  
                        </li>     
                            
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_teacher_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>                                        
                                            <th><?php echo $this->lang->line('school'); ?></th>                                        
                                        <!-- <th><?php echo $this->lang->line('photo'); ?></th> -->
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('responsibility'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('salary_type'); ?></th>
                                        <th><?php echo $this->lang->line('basic_salary'); ?></th>
                                        <th><?php echo $this->lang->line('teacher_code'); ?></th>
                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <th><?php echo $this->lang->line('present_address'); ?></th>
                                        <th><?php echo $this->lang->line('qualification'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('join_date'); ?></th>                                        
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                               
                                <tbody>   
                                  
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_teacher">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('teacher/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?>
                                
                         
                                 <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('basic'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('father_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_name"  id="father_name" value="<?php echo isset($post['father_name']) ?  $post['father_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('father_name'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_name'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="national_id"><?php echo $this->lang->line('national_id'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="adhar_no"  id="adhar_no" value="<?php echo isset($post['adhar_no']) ?  $post['adhar_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('national_id'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('adhar_no'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="responsibility"><?php echo $this->lang->line('responsibility'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="responsibility"  id="responsibility" value="<?php echo isset($post['responsibility']) ?  $post['responsibility'] : ''; ?>" placeholder="<?php echo $this->lang->line('responsibility'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('responsibility'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="phone" value="<?php echo isset($post['phone']) ?  $post['phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('phone'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="dob"><?php echo $this->lang->line('birth_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="dob"  id="edit_dob" value="<?php echo isset($teacher->dob) ?  date('d-m-Y', strtotime($teacher->dob)) : ''; ?>" placeholder="<?php echo $this->lang->line('birth_date'); ?>" required="required" type="text" autocomplete="off" data-date-end-date="0d">
                                            <div class="help-block"><?php echo form_error('dob'); ?></div>
                                        </div>
                                    </div>
                                    
                                
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="gender"><?php echo $this->lang->line('gender'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12"  name="gender"  id="gender" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $genders = get_genders(); ?>
                                                <?php foreach($genders as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if(isset($post['gender']) && $post['gender'] == $key){ echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                        <div class="help-block"><?php echo form_error('gender'); ?></div> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="blood_group"><?php echo $this->lang->line('blood_group'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12" name="blood_group" id="blood_group">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                <?php $bloods = get_blood_group(); ?>
                                                <?php foreach($bloods as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if(isset($post['blood_group']) && $post['blood_group'] == $key){ echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                        <div class="help-block"><?php echo form_error('blood_group'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="religion"><?php echo $this->lang->line('religion'); ?> </label>
											<select  class="form-control col-md-7 col-xs-12" name="religion" id="add_religion">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                <?php $religion = get_religion(); ?>
                                                <?php foreach($religion as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if(isset($post['religion']) && $post['religion'] == $key){ echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>                                          
                                            <div class="help-block"><?php echo form_error('religion'); ?></div>
                                        </div>
                                    </div>
                                 
                                    
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="present_address"><?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?> </label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="present_address"  id="present_address" placeholder="<?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($post['present_address']) ?  $post['present_address'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('present_address'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="permanent_address"><?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?></label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="permanent_address"  id="permanent_address"  placeholder="<?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($post['permanent_address']) ?  $post['permanent_address'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('permanent_address'); ?></div>
                                        </div>
                                    </div>

                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="smc">SMC</label>
                                            <select class="form-control col-md-7 col-xs-12 textarea-4column"  name="smc_add"  id="smc_add" onchange="hideanotherdiv('smc_add','payinfodivadd')">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        <div class="help-block"><?php echo form_error('smc_add'); ?></div>
                                        </div>
                                    </div>
                                    
                                </div>                                                      
                                                             
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('academic'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="email"><?php echo $this->lang->line('email'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"  id="email" value="<?php echo isset($post['email']) ?  $post['email'] : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="username"><?php echo $this->lang->line('username'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="username"  readonly="readonly"  id="username" value="<?php echo isset($teacher_code) && $teacher_code ? $teacher_code : "";  ?>" placeholder="<?php echo $this->lang->line('username'); ?>" type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('username'); ?></div>
                                        </div>
                                    </div>        
                                 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="password"><?php echo $this->lang->line('password'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="password"  id="password" value="" placeholder="<?php echo $this->lang->line('password'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('password'); ?></div>
                                        </div>
                                    </div>  
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="salary_type"><?php echo $this->lang->line('salary_type'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12" name="salary_type" id="salary_type" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                    
                                                <option value="monthly" <?php if(isset($post['salary_type']) && $post['salary_type'] == 'monthly'){ echo 'selected="selected"'; } ?>><?php echo $this->lang->line('monthly'); ?></option>                                           
                                                                                           
                                            </select>
                                            <div class="help-block"><?php echo form_error('salary_type'); ?></div>
                                        </div>
                                    </div>   
</div>
<div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="role_id"><?php echo $this->lang->line('role'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="role_id" id="role_id" required="required">                                                
                                                <?php foreach($roles as $obj){ ?>
                                                    <?php if(in_array($obj->id, array(TEACHER))){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($post['role_id']) && $post['role_id'] == $obj->id){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('role_id'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('qualification'); ?> </label>
											
                                                <?php $qualification = get_qualification(); ?>
                                                <?php foreach($qualification as $key=>$value){ ?><span>
													<input type="checkbox" name="qualification[]" value="<?php print $key; ?>"  <?php if(isset($post['qualification']) && in_array($_POST['qualification'],$key)){ echo 'checked="checked"'; } ?>><?php echo $value; ?></span>                                                   
                                                <?php } ?>
                                            <div class="help-block"><?php echo form_error('qualification'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="joining_date"><?php echo $this->lang->line('join_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="joining_date"  id="add_joining_date" value="<?php echo isset($post['joining_date']) ?  $post['joining_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('join_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('joining_date'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="resume"><?php echo $this->lang->line('qualification_document'); ?> </label>                                           
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="resume"  id="resume" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_doc'); ?></div>
                                            <div class="help-block"><?php echo form_error('resume'); ?></div>
                                        </div>
                                    </div>  
                                </div>

                            <div id='payinfodivadd' >
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('salary_grade'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="username"><?php echo $this->lang->line('basic_salary'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="basic_salary"  id="basic_salary" value="<?php echo isset($post['basic_salary']) ?  $post['basic_salary'] : ''; ?>" placeholder="<?php echo $this->lang->line('basic_salary'); ?>" required="required" type="number" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('basic_salary'); ?></div>
                                        </div>
                                    </div>  
									</div>
								</div>
                                <div id="add_grade_info" class="row"> 
                                   
								</div>

                            </div>
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('other'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="is_view_on_web"><?php echo $this->lang->line('is_view_on_web'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12" name="is_view_on_web" id="is_view_on_web">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                    
                                                <option value="1" <?php if(isset($post['is_view_on_web']) && $post['is_view_on_web'] == '1'){ echo 'selected="selected"'; } ?>><?php echo $this->lang->line('yes'); ?></option>                                           
                                                <option value="0" <?php if(isset($post['is_view_on_web']) && $post['is_view_on_web'] == '0'){ echo 'selected="selected"'; } ?>><?php echo $this->lang->line('no'); ?></option>                                           
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_view_on_web'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="facebook_url"><?php echo $this->lang->line('facebook_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="facebook_url"  id="facebook_url" value="<?php echo isset($post['facebook_url']) ?  $post['facebook_url'] : ''; ?>" placeholder="<?php echo $this->lang->line('facebook_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('facebook_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="linkedin_url"><?php echo $this->lang->line('linkedin_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="linkedin_url"  id="linkedin_url" value="<?php echo isset($post['linkedin_url']) ?  $post['linkedin_url'] : ''; ?>" placeholder="<?php echo $this->lang->line('linkedin_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('linkedin_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="twitter_url"><?php echo $this->lang->line('twitter_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="twitter_url"  id="twitter_url" value="<?php echo isset($post['twitter_url']) ?  $post['twitter_url'] : ''; ?>" placeholder="<?php echo $this->lang->line('twitter_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('twitter_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="google_plus_url"><?php echo $this->lang->line('google_plus_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="google_plus_url"  id="google_plus_url" value="<?php echo isset($post['google_plus_url']) ?  $post['google_plus_url'] : ''; ?>" placeholder="<?php echo $this->lang->line('google_plus_url'); ?>" type="text" autocomplete="">
                                            <div class="help-block"><?php echo form_error('google_plus_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="instagram_url"><?php echo $this->lang->line('instagram_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="instagram_url"  id="instagram_url" value="<?php echo isset($post['instagram_url']) ?  $post['instagram_url'] : ''; ?>" placeholder="<?php echo $this->lang->line('instagram_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('instagram_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="youtube_url"><?php echo $this->lang->line('youtube_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="youtube_url"  id="youtube_url" value="<?php echo isset($post['youtube_url']) ?  $post['youtube_url'] : ''; ?>" placeholder="<?php echo $this->lang->line('youtube_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('youtube_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('pinterest_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pinterest_url"  id="pinterest_url" value="<?php echo isset($post['pinterest_url']) ?  $post['pinterest_url'] : ''; ?>" placeholder="<?php echo $this->lang->line('pinterest_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pinterest_url'); ?></div>
                                        </div>
                                    </div>
								
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('alternate_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="alternate_name"  id="alternate_name" value="<?php echo isset($post['alternate_name']) ?  $post['alternate_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('alternate_name'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('alternate_name'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('reservation_category'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="reservation_category"  id="reservation_category" value="<?php echo isset($post['reservation_category']) ?  $post['reservation_category'] : ''; ?>" placeholder="<?php echo $this->lang->line('reservation_category'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('reservation_category'); ?></div>
                                        </div>
                                    </div>
									
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('pacific_ability'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pacific_ability"  id="pacific_ability" value="<?php echo isset($post['pacific_ability']) ?  $post['pacific_ability'] : ''; ?>" placeholder="<?php echo $this->lang->line('pacific_ability'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pacific_ability'); ?></div>
                                        </div>
                                    </div>
									
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('secondary_roll_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="secondary_roll_no"  id="secondary_roll_no" value="<?php echo isset($post['secondary_roll_no']) ?  $post['secondary_roll_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('secondary_roll_no'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('secondary_roll_no'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('secondary_year'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="secondary_year"  id="secondary_year" value="<?php echo isset($post['secondary_year']) ?  $post['secondary_year'] : ''; ?>" placeholder="<?php echo $this->lang->line('secondary_year'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('secondary_year'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('current_subject'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="current_subject"  id="current_subject" value="<?php echo isset($post['current_subject']) ?  $post['current_subject'] : ''; ?>" placeholder="<?php echo $this->lang->line('current_subject'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('current_subject'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('pf_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pf_no"  id="pf_no" value="<?php echo isset($post['pf_no']) ?  $post['pf_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('pf_no'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pf_no'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="esi"><?php echo $this->lang->line('esi'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="esi"  id="esi" value="<?php echo isset($post['esi']) ?  $post['esi'] : ''; ?>" placeholder="<?php echo $this->lang->line('esi'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pf_no'); ?></div>
                                        </div>
                                    </div>
                                   
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('rtet_qualified'); ?> </label>
											<select name="rtet_qualified" class="form-control col-md-7 col-xs-12">
												<option value=''></option>
												<option value='Yes' <?php echo isset($post['rtet_qualified']) && $post['rtet_qualified']=='Yes'  ?  "selected='selected'" : ''; ?>>Yes</option>
												<option value='No' <?php echo isset($post['rtet_qualified']) && $post['rtet_qualified']=='No'  ?  "selected='selected'" : ''; ?>>No</option>
											</select>                                           
                                            <div class="help-block"><?php echo form_error('rtet_qualified'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="other_info"><?php echo $this->lang->line('other_info'); ?> </label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="other_info"  id="other_info" placeholder="<?php echo $this->lang->line('other_info'); ?>"><?php echo isset($post['other_info']) ?  $post['other_info'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('other_info'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="photo"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('photo'); ?> </label>                                           
                                                <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="photo"  id="photo" value="" placeholder="email" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('photo'); ?></div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('teacher'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        
                        <div class="tab-pane fade in active" id="tab_edit_teacher">
                            <div class="x_content"> 
                            <?php echo form_open_multipart(site_url('teacher/edit/'. $teacher->id), array('name' => 'editteacher', 'id' => 'editteacher', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?>
                                
                                  <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('basic'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($teacher->name) ?  $teacher->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="father_name"><?php echo $this->lang->line('father_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_name"  id="father_name" value="<?php echo isset($teacher->father_name) ?  $teacher->father_name : ''; ?>" placeholder="<?php echo $this->lang->line('father_name'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_name'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="national_id"><?php echo $this->lang->line('teacher_code'); ?><span class="required">*</span> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="teacher_code"  id="edit_teacher_code" value="<?php echo isset($teacher->teacher_code) ?  $teacher->teacher_code : ''; ?>" placeholder="<?php echo $this->lang->line('employee_code'); ?>"  type="text" autocomplete="off" <?php echo $adminonly ?>>
                                            <div class="help-block"><?php echo form_error('teacher_code'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="national_id"><?php echo $this->lang->line('national_id'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="adhar_no"  id="adhar_no" value="<?php echo isset($teacher->adhar_no) ?  $teacher->adhar_no : ''; ?>" placeholder="<?php echo $this->lang->line('national_id'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('adhar_no'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="responsibility"><?php echo $this->lang->line('responsibility'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="responsibility"  id="responsibility" value="<?php echo isset($teacher->responsibility) ?  $teacher->responsibility : ''; ?>" placeholder="<?php echo $this->lang->line('responsibility'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('designation_id'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="phone" value="<?php echo isset($teacher->phone) ?  $teacher->phone : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('phone'); ?></div> 
                                        </div>
                                    </div>                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="dob"><?php echo $this->lang->line('birth_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="dob"  id="edit_dob" value="<?php echo isset($teacher->dob) ?  date('d-m-Y', strtotime($teacher->dob)) : ''; ?>" placeholder="<?php echo $this->lang->line('birth_date'); ?>" required="required" type="text" autocomplete="off" data-date-end-date="0d">
                                            <div class="help-block"><?php echo form_error('dob'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="gender"><?php echo $this->lang->line('gender'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12"  name="gender"  id="gender" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $genders = get_genders(); ?>
                                                <?php foreach($genders as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if($teacher->gender == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                        <div class="help-block"><?php echo form_error('gender'); ?></div> 
                                        </div>
                                    </div>
                                    </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="blood_group"><?php echo $this->lang->line('blood_group'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12" name="blood_group" id="blood_group">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                <?php $bloods = get_blood_group(); ?>
                                                <?php foreach($bloods as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if($teacher->blood_group == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                        <div class="help-block"><?php echo form_error('blood_group'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="religion"><?php echo $this->lang->line('religion'); ?> </label>
											<select  class="form-control col-md-7 col-xs-12" name="religion" id="edit_religion">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                <?php $religion = get_religion(); ?>
                                                <?php foreach($religion as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if(isset($teacher->religion) && $teacher->religion == $key){ echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>                                          
                                            <div class="help-block"><?php echo form_error('religion'); ?></div>
                                        </div>
                                    </div>
                                    
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="present_address"><?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?> </label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="present_address"  id="present_address" placeholder="<?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($teacher->present_address) ?  $teacher->present_address : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('present_address'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="permanent_address"><?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?></label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="permanent_address"  id="permanent_address"  placeholder="<?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($teacher->permanent_address) ?  $teacher->permanent_address : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('permanent_address'); ?></div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="smc">SMC</label>
                                            <select class="form-control col-md-7 col-xs-12 textarea-4column"  name="smc_edit"  id="smc_edit" onchange="hideanotherdiv('smc_edit','payinfodiv')">
                                                <option value="yes" <?php if($teacher->smc=='yes'){ echo 'selected';}?>>Yes</option>
                                                <option value="no" <?php if($teacher->smc=='no'){ echo 'selected';}?>>No</option>
                                            </select>
                                        <div class="help-block"><?php echo form_error('smc_edit'); ?></div>
                                        </div>
                                    </div>
                                    
                                </div>                                                      
                                                             
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('academic'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row"> 
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="email"><?php echo $this->lang->line('email'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"  id="email" value="<?php echo isset($teacher->email) ?  $teacher->email : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div>
                                        </div>
                                    </div> 
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="username"><?php echo $this->lang->line('username'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="username"  readonly="readonly"  id="username" value="<?php echo isset($teacher->username) ?  $teacher->username : ''; ?>" placeholder="<?php echo $this->lang->line('username'); ?>" type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('username'); ?></div>
                                        </div>
                                    </div>                                    
                                    
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="salary_type"><?php echo $this->lang->line('salary_type'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12" name="salary_type" id="edit_salary_type" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                    
                                                <option value="monthly" <?php if($teacher->salary_type == 'monthly'){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('monthly'); ?></option>                                           
                                                                                        
                                            </select>
                                            <div class="help-block"><?php echo form_error('salary_type'); ?></div>
                                        </div>
                                    </div>                                    
                                                                     
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="role_id"><?php echo $this->lang->line('role'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="role_id" id="role_id"  <?php echo $admindisable; ?>>
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($roles as $obj){ ?>
                                                    <?php if(in_array($obj->id, array(SUPER_ADMIN, GUARDIAN, STUDENT))){ continue;} ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($teacher->role_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>                                              
                                            </select>
                                            <div class="help-block"><?php echo form_error('role_id'); ?></div>
                                        </div>
                                    </div>
								</div>
								<div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="joining_date"><?php echo $this->lang->line('join_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="joining_date"  id="edit_joining_date" value="<?php echo isset($teacher->joining_date) ?  date('d-m-Y', strtotime($teacher->joining_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('join_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('joining_date'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('qualification'); ?> </label>
											
                                                <?php $qualification = get_qualification(); 
													$teacher_qualification=explode(",",$teacher->qualification);
												?>
                                                <?php foreach($qualification as $key=>$value){ ?><span>
													<input type="checkbox" name="qualification[]" value="<?php print $key; ?>"  <?php if(in_array($key,$teacher_qualification)){ echo 'checked="checked"'; } ?>><?php echo $value; ?></span>                                                   
                                                <?php } ?>                                            
                                            <div class="help-block"><?php echo form_error('qualification'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="resume"><?php echo $this->lang->line('qualification_document'); ?> </label>                                           
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="resume"  id="resume" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_doc'); ?></div>                                            <div class="help-block"><?php echo form_error('resume'); ?></div>
                                              
                                        </div>
                                    </div> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="resume">&nbsp;</label>   
                                            <input type="hidden" name="prev_resume" id="prev_resume" value="<?php echo $teacher->resume; ?>" />
                                             <?php if($teacher->resume){ ?>
                                            <a target="_blank" href="<?php echo UPLOAD_PATH; ?>/teacher-resume/<?php echo $teacher->resume; ?>"><?php echo $teacher->resume; ?></a> <br/>
                                             <?php } ?>  
                                        </div>
                                    </div> 
                                </div>
                              
                            <div id='payinfodiv' <?php if($teacher->smc=='yes'){ echo 'style="display:none"';}?>>

                                   <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('salary_grade'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>

								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="username"><?php echo $this->lang->line('basic_salary'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="basic_salary"  id="basic_salary" value="<?php echo isset($teacher->basic_salary) ?  $teacher->basic_salary : ''; ?>" placeholder="<?php echo $this->lang->line('basic_salary'); ?>" required="required" type="number" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('basic_salary'); ?></div>
                                        </div>
                                    </div>  
									</div>
								</div>
                                <div id="edit_grade_info" class="row"> 
                                   
								</div>
                            </div>
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('other'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="is_view_on_web"><?php echo $this->lang->line('is_view_on_web'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12" name="is_view_on_web" id="is_view_on_web">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                    
                                                <option value="1" <?php if($teacher->is_view_on_web == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('yes'); ?></option>                                           
                                                <option value="0" <?php if($teacher->is_view_on_web == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('no'); ?></option>                                           
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_view_on_web'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="facebook_url"><?php echo $this->lang->line('facebook_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="facebook_url"  id="facebook_url" value="<?php echo isset($teacher->facebook_url) ?  $teacher->facebook_url : ''; ?>" placeholder="<?php echo $this->lang->line('facebook_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('facebook_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="linkedin_url"><?php echo $this->lang->line('linkedin_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="linkedin_url"  id="linkedin_url" value="<?php echo isset($teacher->linkedin_url) ?  $teacher->linkedin_url : ''; ?>" placeholder="<?php echo $this->lang->line('linkedin_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('linkedin_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="twitter_url"><?php echo $this->lang->line('twitter_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="twitter_url"  id="twitter_url" value="<?php echo isset($teacher->twitter_url) ?  $teacher->twitter_url : ''; ?>" placeholder="<?php echo $this->lang->line('twitter_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('twitter_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="google_plus_url"><?php echo $this->lang->line('google_plus_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="google_plus_url"  id="google_plus_url" value="<?php echo isset($teacher->google_plus_url) ?  $teacher->google_plus_url : ''; ?>" placeholder="<?php echo $this->lang->line('google_plus_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('google_plus_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="instagram_url"><?php echo $this->lang->line('instagram_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="instagram_url"  id="instagram_url" value="<?php echo isset($teacher->instagram_url) ?  $teacher->instagram_url : ''; ?>" placeholder="<?php echo $this->lang->line('instagram_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('instagram_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="youtube_url"><?php echo $this->lang->line('youtube_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="youtube_url"  id="youtube_url" value="<?php echo isset($teacher->youtube_url) ?  $teacher->youtube_url : ''; ?>" placeholder="<?php echo $this->lang->line('youtube_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('youtube_url'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('pinterest_url'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pinterest_url"  id="pinterest_url" value="<?php echo isset($teacher->pinterest_url) ?  $teacher->pinterest_url : ''; ?>" placeholder="<?php echo $this->lang->line('pinterest_url'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pinterest_url'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('alternate_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="alternate_name"  id="alternate_name" value="<?php echo isset($teacher->alternate_name) ?  $teacher->alternate_name : ''; ?>" placeholder="<?php echo $this->lang->line('alternate_name'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('alternate_name'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="reservation_category"><?php echo $this->lang->line('reservation_category'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="reservation_category"  id="reservation_category" value="<?php echo isset($teacher->reservation_category) ?  $teacher->reservation_category : ''; ?>" placeholder="<?php echo $this->lang->line('reservation_category'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('reservation_category'); ?></div>
                                        </div>
                                    </div>									
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('pacific_ability'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pacific_ability"  id="pacific_ability" value="<?php echo isset($teacher->pacific_ability) ?  $teacher->pacific_ability : ''; ?>" placeholder="<?php echo $this->lang->line('pacific_ability'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pacific_ability'); ?></div>
                                        </div>
                                    </div>
									
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('secondary_roll_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="secondary_roll_no"  id="secondary_roll_no" value="<?php echo isset($teacher->secondary_roll_no) ?  $teacher->secondary_roll_no : ''; ?>" placeholder="<?php echo $this->lang->line('secondary_roll_no'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('secondary_roll_no'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('secondary_year'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="secondary_year"  id="secondary_year" value="<?php echo isset($teacher->secondary_year) ?  $teacher->secondary_year : ''; ?>" placeholder="<?php echo $this->lang->line('secondary_year'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('secondary_year'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('current_subject'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="current_subject"  id="current_subject" value="<?php echo isset($teacher->current_subject) ?  $teacher->current_subject : ''; ?>" placeholder="<?php echo $this->lang->line('current_subject'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('current_subject'); ?></div>
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('pf_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pf_no"  id="pf_no" value="<?php echo isset($teacher->pf_no) ? $teacher->pf_no : ''; ?>" placeholder="<?php echo $this->lang->line('pf_no'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pf_no'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="esi"><?php echo $this->lang->line('esi'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="esi"  id="esi" value="<?php  echo isset($teacher->esi) ?  $teacher->esi : ''; ?>" placeholder="<?php echo $this->lang->line('esi'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pf_no'); ?></div>
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pinterest_url"><?php echo $this->lang->line('rtet_qualified'); ?> </label>
											<select name="rtet_qualified" class="form-control col-md-7 col-xs-12">
												<option value=''></option>
												<option value='Yes' <?php echo isset($teacher->rtet_qualified) && $teacher->rtet_qualified=='Yes'  ?  "selected='selected'" : ''; ?>>Yes</option>
												<option value='No' <?php echo isset($teacher->rtet_qualified) && $teacher->rtet_qualified=='No'  ?  "selected='selected'" : ''; ?>>No</option>
											</select>                                           
                                            <div class="help-block"><?php echo form_error('rtet_qualified'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                            <label for="other_info"><?php echo $this->lang->line('other_info'); ?> </label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="other_info"  id="other_info" placeholder="<?php echo $this->lang->line('other_info'); ?>"><?php echo isset($teacher->other_info) ?  $teacher->other_info : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('other_info'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="photo"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('photo'); ?> </label>                                           
                                                <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="photo"  id="photo" value="" placeholder="<?php echo $this->lang->line('upload'); ?>" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('photo'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <input type="hidden" name="prev_photo" id="prev_photo" value="<?php echo $teacher->photo; ?>" />
                                            <?php if($teacher->photo){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/teacher-photo/<?php echo $teacher->photo; ?>" alt="" width="70" /><br/><br/>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" name="id" id="id" value="<?php echo $teacher->id; ?>" />
										<input type="hidden" name="user_id" id="user_id" value="<?php echo $teacher->user_id; ?>" />
                                        <a href="<?php echo site_url('teacher'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


<div class="modal fade bs-teacher-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_teacher_data">
            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_teacher_modal(teacher_id){
         
        $('.fn_teacher_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('teacher/get_single_teacher'); ?>",
          data   : {teacher_id : teacher_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_teacher_data').html(response);
             }
          }
       });
    }
</script>
  

<!-- datatable with buttons -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 
 
 
 
<!-- Super admin js START  -->
 <script type="text/javascript">
     
    $("document").ready(function() {		
		<?php if(isset($filter_school_id) && $filter_school_id >= 0){ ?>		
		 if($("#edit_school_id").length == 0) {			 
             $(".fn_school_id").trigger('change');			 
		 }
		 else{				 
			 $("#edit_school_id").trigger('change');	
		 }			
         <?php } ?>	        
    });
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();       
        var user_id='';
        <?php if(isset($edit) && !empty($edit)){ ?>         
            salary_grade_id =  '<?php echo $teacher->salary_grade_id; ?>';
			user_id= '<?php echo $teacher->user_id; ?>';     
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
        
         $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_payscale_category_by_school'); ?>",
            data   : { school_id:school_id, user_id:user_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(user_id){
                       $('#edit_grade_info').html(response);
                   }else{
                       $('#add_grade_info').html(response); 
                   }
               }
            }
        });  
// generate employye code if add
		 <?php if(!isset($edit)){ ?>
		 $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/generate_teacher_code'); ?>",
            data   : { school_id:school_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   
                       $('#add_teacher_code').val(response); 
                   
               }
            }
        });
		 <?php } ?>		
     
    }); 

    
  </script>
  <!-- Super admin js end -->
  
 
<!-- datatable with buttons -->
 <script type="text/javascript">
     
    $('#add_dob').datepicker();
    $('#add_joining_date').datepicker();
    $('#edit_dob').datepicker();
    $('#edit_joining_date').datepicker();
    
        $(document).ready(function() {
			var sch_id='<?php print $filter_school_id; ?>';
          $('#datatable-responsive').DataTable({
              dom: 'Bfrtip',
			   'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("teacher/get_list"); ?>',
		  'data': {'school_id': sch_id}
      },
              iDisplayLength: 15,
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength',
                  'colvis'
              ],
              "columnDefs": [
			  
					{
						"targets": [ 7 ],
						"visible": false,
						"searchable": false
					},
					{
						"targets": [ 12],
						"visible": false
					},
					{
						"targets": [ 8 ],
						"visible": false,
						"searchable": false
					},
					{
						"targets": [ 9 ],
						"visible": false,
						"searchable": false
					},
					{
						"targets": [ 10 ],
						"visible": false,
						"searchable": false
					},
					{
						"targets": [ 11 ],
						"visible": false,
						"searchable": false
					},
                    {
						"targets": [ 13 ],
						"visible": false,
						"searchable": false
					}
				],
              search: true,              
              responsive: true
          });
        });
        
    $("#add").validate();     
    $("#edit").validate();    
    function alumni_teacher(teacher_id)
      {
        $('.fn_teacher_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('teacher/teacher_modal'); ?>",
          data   : {teacher_id : teacher_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_teacher_data').html(response);
             }
          }
       });
      }
    
    function get_teacher_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    }  


    function hideanotherdiv(mainid, payinfodiv){
        var smcVal = $("#"+mainid).val();
        if(smcVal == 'no'){
            $("#"+payinfodiv).show();
        }else{
            $("#"+payinfodiv).hide();
        }
    }
    
</script>