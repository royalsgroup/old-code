
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-users"></i><small> <?php echo $this->lang->line('manage_student'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
          
            <div class="x_content quick-link">
                <?php $this->load->view('quick-link'); ?> 
            </div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        
                        <?php if(isset($detail)){ ?>
                        
                        
                            <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('student/index'); ?>"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                            
                            <?php if(has_permission(ADD, 'student', 'student')){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('student/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                            <?php } ?>
                           
                            <li  class="active"><a href="#tab_view_student"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('view'); ?></a> </li>                          
                            
                          
                        <?php }else{ ?> 
                            <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_student_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                            <?php if(has_permission(ADD, 'student', 'student')){ ?>
                                <?php if(isset($edit)){ ?>
                                    <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('student/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                                 <?php }else{ ?>
                                     <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('student/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                                 <?php } ?>
                            <?php } ?>

                            <?php if(isset($edit)){ ?>
                                <li  class="active"><a href="#tab_edit_student"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>                          
                            <?php } ?>
                        <?php } ?>     
                            
                           </li>
                    </ul>
<div class="row" style="margin-top:20px;">
                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>
                            
                                <?php echo form_open(site_url('student/index'), array('name' => 'filter', 'id' => 'filter', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                   <div class='col-md-3 col-sm-3 col-xs-12'>
                                    <select  class="form-control col-md-7 col-xs-12" style="width:auto;" name="school_id"  onchange="get_class_by_school(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select_school'); ?>--</option> 
                                        <?php foreach($schools as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                        <?php } ?>   
                                    </select>
								</div>
								<div class='col-md-3 col-sm-3 col-xs-12'>
                                    <select  class="form-control col-md-7 col-xs-12" id="filter_class_id" name="class_id"  style="width:auto;">
                                         <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                        <?php if(isset($class_list) && !empty($class_list)){ ?>
                                            <?php foreach($class_list as $obj ){ ?>
                                                <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
								</div>
									<div class='col-md-3 col-sm-3 col-xs-12'>
									<input type='submit' name='submit' value='Filter' class="btn btn-primary" />
								</div>
                                   <?php echo form_close(); ?>
                            
                            <?php }else{  ?> 
                                <select id="filter_class_id" name="class_id" class="form-control col-md-7 col-xs-12" onchange="get_student_by_class(this.value);">
                                    <?php if($this->session->userdata('role_id') != STUDENT){ ?>    
                                        <option value="<?php echo site_url('student/index'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                        <option value="0">All</option> 

                                    <?php } ?>
                                    <?php $teacher_student_data = get_teacher_access_data('student'); ?>     
                                    <?php $guardian_class_data = get_guardian_access_data('class'); ?>      
                                    <?php foreach($classes as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?>
                                            <option value="<?php echo site_url('student/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>
                                            <?php if (!in_array($obj->id, $guardian_class_data)) { continue; } ?>
                                            <option value="<?php echo site_url('student/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                            <option value="<?php echo site_url('student/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo site_url('student/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>                                            
                                </select>                                
                            <?php } ?>
                      </div>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_student_list" >
                            <div class="x_content">
							<?php echo form_open_multipart(site_url('accountgroups/delete_multiple'), array('name' => 'delete_multiple', 'id' => 'delete_multiple', 'class'=>'form-horizontal form-label-left'), ''); ?>
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
									<?php if(has_permission(EDIT, 'student', 'student')){ ?>										
                                        <th class='noPrint'>
										<input type="checkbox" name="checkAll" id="checkall" value='1' />
										</th>
									<?php } else { ?>
										<th><?php echo $this->lang->line('sl_no'); ?></th>
									<?php  } ?>
                                         <?php //if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php //} ?>
                                        <!--<th><?php echo $this->lang->line('photo'); ?></th>-->
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('father_name'); ?></th>
                                        <th><?php echo $this->lang->line('group'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th ><?php echo $this->lang->line('roll_no'); ?></th>

                                        <th ><?php echo $this->lang->line('mother_name'); ?></th>
                                        <th><?php echo $this->lang->line('birth_date'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('present_address'); ?></th>
                                        <th><?php echo $this->lang->line('admission_date'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('caste'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?>
                                        <button class="btn btn-sm btn-primary " type="button" id="bulk_pass_button" >Bulk Alumni</button>
                                    </th>                                           
                                    </tr>
                                </thead>
                               
                            </table>	
							<div class="row no-print">
							<div class="col-md-3 col-sm-3 col-xs-12">
								<span>With Seleted: </span>
								<select name="multiAction" id="multiAction">
									<option value=''>--Select--</option>
									<option value='move'>Move</option>
								</select>
								</div>
							</div>
<?php echo form_close(); ?>		
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_student">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('student/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?>
                                
                               <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('basic_information'); ?>:</strong></h5>
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
                                            <label for="admission_no"><?php echo $this->lang->line('admission_no'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="admission_no"  id="admission_no" value="<?php echo isset($post['admission_no']) ?  $post['admission_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('admission_no'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('admission_no'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="admission_date"><?php echo $this->lang->line('admission_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="admission_date"  id="add_admission_date" value="<?php echo isset($post['admission_date']) ?  $post['admission_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('admission_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('admission_date'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label  for="dob"><?php echo $this->lang->line('birth_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="dob"  id="add_dob" value="<?php echo isset($post['dob']) ?  $post['dob'] : ''; ?>" placeholder="<?php echo $this->lang->line('birth_date'); ?>" required="required" type="text" autocomplete="off">
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
                                                    <option value="<?php echo $key; ?>" <?php echo isset($post['gender']) && $post['gender'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('gender'); ?></div>
                                         </div>
                                     </div>
                                    
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="blood_group"><?php echo $this->lang->line('blood_group'); ?></label>
                                              <select  class="form-control col-md-7 col-xs-12" name="blood_group" id="blood_group">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $bloods = get_blood_group(); ?>
                                                <?php foreach($bloods as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php echo isset($post['blood_group']) && $post['blood_group'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                                </select>
                                            <div class="help-block"><?php echo form_error('blood_group'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                              <label for="religion"><?php echo $this->lang->line('religion'); ?></label>
                                              <select  class="form-control col-md-7 col-xs-12"  name="religion"  id="add_religion">
                                              <option value=''>--Select--</option>
                                              <option value="Hinduism" <?php echo isset($post['religion']) && $post['religion']== 'Hinduism' ? 'selected="selected"'  : ''; ?>>Hinduism</option>
                                              <option value="Bahai" <?php echo isset($post['religion']) && $post['religion']== 'Bahai' ? 'selected="selected"'  : ''; ?>>Bahai</option>
                                              <option value="Buddhism" <?php echo isset($post['religion']) && $post['religion']== 'Buddhism' ? 'selected="selected"'  : ''; ?>>Buddhism</option>
                                              <option value="Christianity" <?php echo isset($post['religion']) && $post['religion']== 'Christianity' ? 'selected="selected"'  : ''; ?>>Christianity</option>
                                              <option value="Folk" <?php echo isset($post['religion']) && $post['religion']== 'Folk' ? 'selected="selected"'  : ''; ?>>Folk</option>
                                              <option value="Islam" <?php echo isset($post['religion']) && $post['religion']== 'Islam' ? 'selected="selected"'  : ''; ?>>Islam</option>
                                              <option value="Jainism" <?php echo isset($post['religion']) && $post['religion']== 'Jainism' ? 'selected="selected"'  : ''; ?>>Jainism</option>
                                              <option value="Shinto" <?php echo isset($post['religion']) && $post['religion']== 'Shinto' ? 'selected="selected"'  : ''; ?>>Shinto</option>
                                              <option value="Sikhism" <?php echo isset($post['religion']) && $post['religion']== 'Sikhism' ? 'selected="selected"'  : ''; ?>>Sikhism</option>
                                              <option value="Atheism" <?php echo isset($post['religion']) && $post['religion']== 'Atheism' ? 'selected="selected"'  : ''; ?>>Atheism</option>
                                              <option value="Other" <?php echo isset($post['religion']) && $post['religion']== 'Other' ? 'selected="selected"'  : ''; ?>>Other</option>
                                              </select>
                                               <div class="help-block"><?php echo form_error('religion'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                              <label for="caste"><?php echo $this->lang->line('caste'); ?></label>
											   <select  class="form-control col-md-7 col-xs-12"  name="caste"  id="add_caste">
                                              <option value=''>--Select--</option>
                                              <option value="General" <?php echo isset($post['caste']) && $post['caste']== 'General' ? 'selected="selected"'  : ''; ?>>General</option>
											  <option value="SC" <?php echo isset($post['caste']) && $post['caste']== 'SC' ? 'selected="selected"'  : ''; ?>>SC</option>
											  <option value="ST" <?php echo isset($post['caste']) && $post['caste']== 'ST' ? 'selected="selected"'  : ''; ?>>ST</option>
											  <option value="OBC" <?php echo isset($post['caste']) && $post['caste']== 'OBC' ? 'selected="selected"'  : ''; ?>>OBC</option>
											  <option value="EWC" <?php echo isset($post['caste']) && $post['caste']== 'EWC' ? 'selected="selected"'  : ''; ?>>EWC</option>
                                              <option value="SBC" <?php echo isset($post['caste']) && $post['caste']== 'SBC' ? 'selected="selected"'  : ''; ?>>SBC</option>

                                              <option value="Minority" <?php echo isset($post['caste']) && $post['caste']== 'Minority' ? 'selected="selected"'  : ''; ?>>Minority</option>
											  <option value="Others" <?php echo isset($post['caste']) && $post['caste']== 'Others' ? 'selected="selected"'  : ''; ?>>Others</option>
                                             </select>
                                               <div class="help-block"><?php echo form_error('caste'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                    
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="add_phone" value="<?php echo isset($post['phone']) ?  $post['phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('phone'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                           <label for="email"><?php echo $this->lang->line('email'); ?> </label>
                                           <input  class="form-control col-md-7 col-xs-12"  name="email"  id="email" value="<?php echo isset($post['email']) ?  $post['email'] : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" type="email" autocomplete="off">
                                           <div class="help-block"><?php echo form_error('email'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="national_id"><?php echo $this->lang->line('national_id'); ?> </label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="national_id"  id="national_id" value="<?php echo isset($post['national_id']) ?  $post['national_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('national_id'); ?>" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('national_id'); ?></div>
                                         </div>
                                     </div>-->
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="national_id"><?php echo $this->lang->line('rte'); ?> </label>
                                             <select class="form-control col-md-7 col-xs-12"  name="rte"  id="rte" >
                                             <?php $rte_yes =  isset($post['rte']) && strtolower($post['rte']) == "yes" ? "selected"  : ''; ?>
                                             <?php $rte_no =  isset($post['rte']) && strtolower($post['rte']) != "yes" ? "selected"  : ''; ?>
                                                  <option value=''>Select</option>
                                                  <option value="Yes"  <?php echo $rte_yes ?>>Yes</option>
                                                  <option value="No"  <?php echo $rte_no ?>>No</option>
                                            </select>
                                             <div class="help-block"><?php echo form_error('rte'); ?></div>
                                         </div>
                                     </div>
									 <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('physical_disability'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="physical_disability"  id="physical_disability" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($post['physical_disability']) && $post['physical_disability']=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($post['physical_disability']) && $post['physical_disability']=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('physical_disability'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                
                                  
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('academic_information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">  
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="type_id"><?php echo $this->lang->line('student_type'); ?></label>
                                                <select  class="form-control col-md-7 col-xs-12" name="type_id" id="add_type_id">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($types as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo isset($post['type_id']) && $post['type_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->type; ?></option>
                                                <?php } ?>
                                                </select>
                                             <div class="help-block"><?php echo form_error('type_id'); ?></div>
                                         </div>
                                     </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="class_id" id="add_class_id" required="required" onchange="get_section_by_class(this.value, '');">
                                               <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                               <?php foreach($classes as $obj){ ?>
                                                   <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                               <?php } ?>
                                           </select>
                                           <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                           <label for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span></label>
                                           <select  class="form-control col-md-7 col-xs-12 quick-field" name="section_id" id="add_section_id" required="required">
                                               <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                           </select>
                                           <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row"> 
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="roll_no"><?php echo $this->lang->line('roll_no'); ?> <span class="required">*</span></label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="roll_no"  id="roll_no" value="<?php echo isset($post['roll_no']) ?  $post['roll_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('roll_no'); ?>" required="required" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('roll_no'); ?></div>
                                         </div>
                                     </div>                               
                               
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('registration_no'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="registration_no"  id="registration_no" value="<?php echo isset($post['registration_no']) ?  $post['registration_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('registration_no'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('registration_no'); ?></div>
                                         </div>
                                     </div>
                                     
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="discount_id"><?php echo $this->lang->line('discount'); ?></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="discount_id" id="add_discount_id">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($discounts as $obj){ ?>                                                    
                                                    <option value="<?php echo $obj->id; ?>"><?php echo $obj->title; ?> [<?php echo $obj->amount; ?>%]</option>                                                   
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('discount_id'); ?></div>
                                         </div>
                                     </div>
                                   <!--  <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="second_language"><?php echo $this->lang->line('second_language'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="second_language"  id="second_language" value="<?php echo isset($post['second_language']) ?  $post['second_language'] : ''; ?>" placeholder="<?php echo $this->lang->line('second_language'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('second_language'); ?></div>
                                         </div>
                                     </div> -->
                                </div>
                                
                               
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('father_information'); ?>:</strong></h5>
                                    </div>
                                </div> 
                                <div class="row">  
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_name"><?php echo $this->lang->line('father_name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_name"  id="add_father_name" value="<?php echo isset($post['father_name']) ?  $post['father_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('father_name'); ?>" type="text" autocomplete="off" required="required">
                                            <div class="help-block"><?php echo form_error('father_name'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_phone"><?php echo $this->lang->line('father_phone'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_phone"  id="add_father_phone" value="<?php echo isset($post['father_phone']) ?  $post['father_phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('father_phone'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_phone'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_education"><?php echo $this->lang->line('father_education'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_education"  id="father_education" value="<?php echo isset($post['father_education']) ?  $post['father_education'] : ''; ?>" placeholder="<?php echo $this->lang->line('father_education'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_education'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_profession"><?php echo $this->lang->line('father_profession'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_profession"  id="add_father_profession" value="<?php echo isset($post['father_profession']) ?  $post['father_profession'] : ''; ?>" placeholder="<?php echo $this->lang->line('father_profession'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_profession'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_designation"><?php echo $this->lang->line('father_designation'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_designation"  id="father_designation" value="<?php echo isset($post['father_designation']) ?  $post['father_profession'] : ''; ?>" placeholder="<?php echo $this->lang->line('father_designation'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_designation'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label ><?php echo $this->lang->line('father_photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="father_photo"  id="father_photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('father_photo'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('mother_information'); ?>:</strong></h5>
                                    </div>
                                </div> 
                                <div class="row">  
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_name"><?php echo $this->lang->line('mother_name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_name"  id="add_mother_name" value="<?php echo isset($post['mother_name']) ?  $post['mother_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('mother_name'); ?>" type="text" autocomplete="off" required="required">
                                            <div class="help-block"><?php echo form_error('mother_name'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_phone"><?php echo $this->lang->line('mother_phone'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_phone"  id="add_mother_phone" value="<?php echo isset($post['mother_phone']) ?  $post['mother_phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('mother_phone'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_phone'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_education"><?php echo $this->lang->line('mother_education'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_education"  id="mother_education" value="<?php echo isset($post['mother_education']) ?  $post['mother_education'] : ''; ?>" placeholder="<?php echo $this->lang->line('mother_education'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_education'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_profession"><?php echo $this->lang->line('mother_profession'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_profession"  id="add_mother_profession" value="<?php echo isset($post['mother_profession']) ?  $post['mother_profession'] : ''; ?>" placeholder="<?php echo $this->lang->line('mother_profession'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_profession'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_designation"><?php echo $this->lang->line('mother_designation'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_designation"  id="mother_designation" value="<?php echo isset($post['mother_designation']) ?  $post['mother_designation'] : ''; ?>" placeholder="<?php echo $this->lang->line('mother_designation'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_designation'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label ><?php echo $this->lang->line('mother_photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="mother_photo"  id="mother_photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('mother_photo'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('guardian_information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="is_guardian"><?php echo $this->lang->line('is_guardian'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="is_guardian" id="is_guardian" required="required" onchange="check_guardian_type(this.value);">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="father" <?php echo isset($post['is_guardian']) && $post['is_guardian'] == 'father' ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('father'); ?></option>
                                                <option value="mother" <?php echo isset($post['is_guardian']) && $post['is_guardian'] == 'mother' ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('mother'); ?></option>
                                                <option value="other" <?php echo isset($post['is_guardian']) && $post['is_guardian'] == 'other' ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('other'); ?></option>
                                                <option value="exist_guardian" <?php echo isset($post['is_guardian']) && $post['is_guardian'] == 'exist_guardian' ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('guardian_exist'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_guardian'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="fn_existing_guardian <?php if(isset($post['is_guardian']) && $post['is_guardian'] == 'exist_guardian'){'';}else{ echo 'display'; } ?>">
                                        <div class="col-md-3 col-sm-3 col-xs-12"> 
                                            <div class="item form-group">
                                                <label for="guardian_id"><?php echo $this->lang->line('guardian'); ?> <span class="required">*</span></label>
                                                <select  class="form-control col-md-7 col-xs-12 quick-field" name="guardian_id" id="add_guardian_id" onchange="get_guardian_by_id(this.value);">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php foreach($guardians as $obj){ ?>
                                                        <option value="<?php echo $obj->id; ?>" <?php echo isset($post['guardian_id']) && $post['guardian_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="help-block"><?php echo form_error('guardian_id'); ?></div>
                                            </div>
                                        </div>                                  
                                    </div>
                                                                        
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="relation_with"><?php echo $this->lang->line('relation_with_guardian'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="relation_with"  id="add_relation_with" value="<?php echo isset($post['relation_with']) ?  $post['relation_with'] : ''; ?>" placeholder="<?php echo $this->lang->line('relation_with_guardian'); ?>" type="text">
                                            <div class="help-block"><?php echo form_error('relation_with'); ?></div>
                                        </div>
                                    </div> 
                                </div> 
                                   
                                
                                <div class="<?php echo ($post['is_guardian']) && $post['is_guardian'] != 'exist_guardian' ? '' :'display'; ?> fn_except_exist"> 
                                    <div class="row"> 

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_name"  id="add_gud_name" value="<?php echo isset($post['gud_name']) ?  $post['gud_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text">
                                                <div class="help-block"><?php echo form_error('gud_name'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_phone"  id="add_gud_phone" value="<?php echo isset($post['gud_phone']) ?  $post['gud_phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text">
                                                <div class="help-block"><?php echo form_error('phone'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_email"><?php echo $this->lang->line('email'); ?> <span class="required">*</span> </label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_email"  id="add_gud_email" value="<?php echo isset($post['gud_email']) ?  $post['gud_email'] : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" required="email" type="email" required="required" >
                                                <div class="help-block"><?php echo form_error('gud_email'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_profession"><?php echo $this->lang->line('profession'); ?></label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_profession"  id="add_gud_profession" value="<?php echo isset($post['gud_profession']) ?  $post['gud_profession'] : ''; ?>" placeholder="<?php echo $this->lang->line('profession'); ?>"  type="text">
                                                <div class="help-block"><?php echo form_error('gud_profession'); ?></div>
                                            </div>
                                        </div>    
                                                                            
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_religion"><?php echo $this->lang->line('religion'); ?> </label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_religion"  id="add_gud_religion" value="<?php echo isset($post['gud_religion']) ?  $post['gud_religion'] : ''; ?>" placeholder="<?php echo $this->lang->line('religion'); ?>" type="text">
                                                <div class="help-block"><?php echo form_error('gud_religion'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_national_id"><?php echo $this->lang->line('national_id'); ?></label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_national_id"  id="add_gud_national_id" value="<?php echo isset($post['gud_national_id']) ?  $post['gud_national_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('national_id'); ?>"  type="text">
                                                <div class="help-block"><?php echo form_error('gud_national_id'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_username"><?php echo $this->lang->line('username'); ?></label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_username" readonly="readonly"  id="add_gud_username" value="<?php echo isset($post['gud_username']) ?  $post['gud_username'] : ''; ?>" placeholder="<?php echo $this->lang->line('username'); ?>"  type="text" required="required">
                                                <div class="help-block"><?php echo form_error('gud_username'); ?></div>
                                            </div>
                                        </div> 
</div>
<div class="row">
<div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_profession"><?php echo $this->lang->line('id_type'); ?></label>
                                                <select id="add_gud_id_type" class="form-control col-md-7 col-xs-12" name="gud_id_type">
                                                <option value=''>--Select--</option>
                                                <option value='Adharcard' <?php echo isset($post['gud_id_type'])&& $post['gud_id_type']=='Adharcard' ? "selected='selected'"  : ''; ?>>Adhar Card</option>
                                                <option value='Pancard' <?php echo isset($post['gud_id_type'])&& $post['gud_id_type']=='Pancard' ? "selected='selected'"  : ''; ?>>PAN Card</option>
                                                <option value='Passport' <?php echo isset($post['gud_id_type'])&& $post['gud_id_type']=='Passport' ? "selected='selected'"  : ''; ?>>Passport</option>
                                                <option value='Others' <?php echo isset($post['gud_id_type'])&& $post['gud_id_type']=='Others' ? "selected='selected'"  : ''; ?>>Others</option>
                                                </select>                                               
                                                <div class="help-block"><?php echo form_error('gud_id_type'); ?></div>
                                            </div>
                                        </div>  
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_profession"><?php echo $this->lang->line('id_no'); ?></label>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_id_no"  id="add_gud_id_no" value="<?php echo isset($post['gud_id_no']) ?  $post['gud_id_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('id_no'); ?>"  type="text">
                                                <div class="help-block"><?php echo form_error('gud_id_no'); ?></div>
                                            </div>
                                        </div> 
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label ><?php echo $this->lang->line('id_photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="gud_id_photo"  id="gud_id_photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('id_photo'); ?></div>
                                         </div>
                                     </div>                                         

                                    </div>
                                    
                                    <div class="row">    
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_present_address"><?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?></label>
                                                <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="gud_present_address"  id="add_gud_present_address" placeholder="<?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($post['gud_present_address']) ?  $post['gud_present_address'] : ''; ?></textarea>
                                                <div class="help-block"><?php echo form_error('gud_present_address'); ?></div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="item form-group">
                                                <label for="gud_permanent_address"><?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?></label>
                                                <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="gud_permanent_address"  id="add_gud_permanent_address" placeholder="<?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($post['gud_permanent_address']) ?  $post['gud_permanent_address'] : ''; ?></textarea>
                                                <div class="help-block"><?php echo form_error('gud_permanent_address'); ?></div>
                                            </div>
                                        </div>  
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="item form-group">
                                                <label for="other_info"><?php echo $this->lang->line('other_info'); ?> </label>
                                                <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="gud_other_info"  id="add_gud_other_info" placeholder="<?php echo $this->lang->line('other_info'); ?>"><?php echo isset($post['gud_other_info']) ?  $post['gud_other_info'] : ''; ?></textarea>
                                                <div class="help-block"><?php echo form_error('gud_other_info'); ?></div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    
                                </div>
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title">
                                            <strong>
                                            <?php echo $this->lang->line('address_information'); ?>: 
                                            </strong>
                                            <?php echo $this->lang->line('same_as_guarduan_address'); ?> <input  class=""  name="same_as_guardian"  id="same_as_guardian" value="1"  type="checkbox" <?php echo isset($post['same_as_guardian']) ?  'checked="checked"' : ''; ?>>
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">   
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                             <label for="present_address"><?php echo $this->lang->line('present_address'); ?> </label>
                                              <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="present_address"  id="add_present_address"  placeholder="<?php echo $this->lang->line('present_address'); ?>"><?php echo isset($post['present_address']) ?  $post['present_address'] : ''; ?></textarea>
                                              <div class="help-block"><?php echo form_error('present_address'); ?></div>
                                         </div>
                                     </div>                                    
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                            <label for="permanent_address"><?php echo $this->lang->line('permanent_address'); ?></label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="permanent_address"  id="add_permanent_address"  placeholder="<?php echo $this->lang->line('permanent_address'); ?>"><?php echo isset($post['permanent_address']) ?  $post['permanent_address'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('permanent_address'); ?></div>
                                         </div>
                                     </div>
                                </div>
                           
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('previous_school'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="previous_school"><?php echo $this->lang->line('school_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="previous_school"  id="previous_school" value="<?php echo isset($post['previous_school']) ?  $post['previous_school'] : ''; ?>" placeholder="<?php echo $this->lang->line('school_name'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('previous_school'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="previous_class"><?php echo $this->lang->line('class'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="previous_class"  id="previous_class" value="<?php echo isset($post['previous_class']) ?  $post['previous_class'] : ''; ?>" placeholder="<?php echo $this->lang->line('previous_class'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('previous_class'); ?></div>
                                         </div>
                                     </div>
                                    
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label ><?php echo $this->lang->line('transfer_certificate'); ?> </label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="transfer_certificate"  id="transfer_certificate" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('transfer_certificate'); ?></div>
                                         </div>
                                     </div>
                                    
                                </div>
                                 <div class='row'>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('reason_of_seperation'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="reason_of_seperation"  id="reason_of_seperation" value="<?php echo isset($post['reason_of_seperation']) ?  $post['reason_of_seperation'] : ''; ?>" placeholder="<?php echo $this->lang->line('reason_of_seperation'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('reason_of_seperation'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('date_of_seperation'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="date_of_seperation"  id="add_date_of_seperation" value="<?php echo isset($post['date_of_seperation']) ?  date('d-m-Y', strtotime($post['date_of_seperation'])) : ''; ?>" placeholder="<?php echo $this->lang->line('date_of_seperation'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('date_of_seperation'); ?></div>
                                     </div>
                                 </div>
</div>                               
                                
                               <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong> <?php echo $this->lang->line('other_information'); ?>:</strong></h5>
                                    </div>
                                </div>    
                                <div class="row">
                                 
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="username"><?php echo $this->lang->line('username'); ?> <span class="required">*</span></label>
                                        <input  class="form-control col-md-7 col-xs-12 username_add" readonly="readonly"  name="username"  id="username" value="<?php echo isset($post['username']) ?  $post['username'] : ''; ?>" placeholder="<?php echo $this->lang->line('username'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('username'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="password"><?php echo $this->lang->line('password'); ?> <span class="required">*</span></label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="password"  id="password" readonly="readonly" value="welcome" placeholder="<?php echo $this->lang->line('password'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('password'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('alternate_name'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="alternate_name"  id="alternate_name" value="<?php echo isset($post['alternate_name']) ?  $post['alternate_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('alternate_name'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('alternate_name'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('facilities'); ?> </label>
										<?php $facilities = get_student_facilities(); ?>
                                                <?php foreach($facilities as $key=>$value){ ?><span>
													<input type="checkbox" name="facilities[]" value="<?php print $key; ?>"  <?php if(isset($post['facilities']) && in_array($_POST['facilities'],$key)){ echo 'checked="checked"'; } ?>> <?php echo $value; ?></span>                                                   
                                                <?php } ?>                                    
                                        <div class="help-block"><?php echo form_error('facilities'); ?></div>
                                     </div>
                                 </div>
                            </div>
                          
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('adhar_no'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="adhar_no"  id="adhar_no" value="<?php echo isset($post['adhar_no']) ?  $post['adhar_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('adhar_no'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('adhar_no'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('sm_id'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="sm_id"  id="sm_id" value="<?php echo isset($post['sm_id']) ?  $post['sm_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('sm_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('sm_id'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('family_id'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="family_id"  id="family_id" value="<?php echo isset($post['family_id']) ?  $post['family_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('family_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('family_id'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('rural_urban'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="rural_urban"  id="rural_urban" value="<?php echo isset($post['rural_urban']) ?  $post['rural_urban'] : ''; ?>" placeholder="<?php echo $this->lang->line('rural_urban'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('rural_urban'); ?></div>
                                     </div>
                                 </div>
                            </div>
                           <div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('midium_instruction'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="medium_of_instruction"  id="midium_instruction" value="<?php echo isset($post['medium_of_instruction']) ?  $post['medium_of_instruction'] : ''; ?>" placeholder="<?php echo $this->lang->line('midium_instruction'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('medium_of_instruction'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('free_education'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="free_education"  id="free_education" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($post['free_education']) && $post['free_education']=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($post['free_education']) && $post['free_education']=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('free_education'); ?></div>
                                         </div>
                                     </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('previous_year_attended_days'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="previous_year_attended_days"  id="previous_year_attended_days" value="<?php echo isset($post['previous_year_attended_days']) ?  $post['previous_year_attended_days'] : ''; ?>" placeholder="<?php echo $this->lang->line('previous_year_attended_days'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('previous_year_attended_days'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('facilities_by_cwsn'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="facilities_by_cwsn"  id="facilities_by_cwsn" value="<?php echo isset($post['facilities_by_cwsn']) ?  $post['facilities_by_cwsn'] : ''; ?>" placeholder="<?php echo $this->lang->line('facilities_by_cwsn'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('facilities_by_cwsn'); ?></div>
                                     </div>
                                 </div>								 
							</div>
							<div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('no_of_uniform_sets'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="no_of_uniform_sets"  id="no_of_uniform_sets" value="<?php echo isset($post['no_of_uniform_sets']) ?  $post['no_of_uniform_sets'] : ''; ?>" placeholder="<?php echo $this->lang->line('no_of_uniform_sets'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('no_of_uniform_sets'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_text_books'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_text_books"  id="free_text_books" value="<?php echo isset($post['free_text_books']) ?  $post['free_text_books'] : ''; ?>" placeholder="<?php echo $this->lang->line('free_text_books'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_text_books'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_transport'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_transport"  id="free_transport" value="<?php echo isset($post['free_transport']) ?  $post['free_transport'] : ''; ?>" placeholder="<?php echo $this->lang->line('free_transport'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_transport'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_escort'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_escort"  id="free_escort" value="<?php echo isset($post['free_escort']) ?  $post['free_escort'] : ''; ?>" placeholder="<?php echo $this->lang->line('free_escort'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_escort'); ?></div>
                                     </div>
                                 </div>
							</div>
							<div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('mdm_benificiary'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="mdm_benificiary"  id="mdm_benificiary" value="<?php echo isset($post['mdm_benificiary']) ?  $post['mdm_benificiary'] : ''; ?>" placeholder="<?php echo $this->lang->line('mdm_benificiary'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('mdm_benificiary'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_hostel'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_hostel"  id="free_hostel" value="<?php echo isset($post['free_hostel']) ?  $post['free_hostel'] : ''; ?>" placeholder="<?php echo $this->lang->line('free_hostel'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_hostel'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('special_training'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="special_training"  id="special_training" value="<?php echo isset($post['special_training']) ?  $post['special_training'] : ''; ?>" placeholder="<?php echo $this->lang->line('special_training'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('special_training'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('appeared_in_last_exam'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="appeared_in_last_exam"  id="appeared_in_last_exam" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($post['appeared_in_last_exam']) && $post['appeared_in_last_exam']=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($post['appeared_in_last_exam']) && $post['appeared_in_last_exam']=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('appeared_in_last_exam'); ?></div>
                                         </div>
                                     </div>
							</div>
							<div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('last_exam_passed'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="last_exam_passed"  id="last_exam_passed" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($post['last_exam_passed']) && $post['last_exam_passed']=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($post['last_exam_passed']) && $post['last_exam_passed']=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('last_exam_passed'); ?></div>
                                         </div>
                                     </div>
									 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('last_exam_marks'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="last_exam_marks"  id="last_exam_marks" value="<?php echo isset($post['last_exam_marks']) ?  $post['last_exam_marks'] : ''; ?>" placeholder="<?php echo $this->lang->line('last_exam_marks'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('last_exam_marks'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('trade_sector'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="trade_sector"  id="trade_sector" value="<?php echo isset($post['trade_sector']) ?  $post['trade_sector'] : ''; ?>" placeholder="<?php echo $this->lang->line('trade_sector'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('trade_sector'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('iron_folic_acid_tablets'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="iron_folic_acid_tablets"  id="iron_folic_acid_tablets" value="<?php echo isset($post['iron_folic_acid_tablets']) ?  $post['iron_folic_acid_tablets'] : ''; ?>" placeholder="<?php echo $this->lang->line('iron_folic_acid_tablets'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('iron_folic_acid_tablets'); ?></div>
                                     </div>
                                 </div>
							</div>
							<div class="row">
							 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('deworming_tablets'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="deworming_tablets"  id="deworming_tablets" value="<?php echo isset($post['deworming_tablets']) ?  $post['deworming_tablets'] : ''; ?>" placeholder="<?php echo $this->lang->line('deworming_tablets'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('deworming_tablets'); ?></div>
                                     </div>
                                 </div>
								  <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('vitamin_a_supplement'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="vitamin_a_supplement"  id="vitamin_a_supplement" value="<?php echo isset($post['vitamin_a_supplement']) ?  $post['vitamin_a_supplement'] : ''; ?>" placeholder="<?php echo $this->lang->line('vitamin_a_supplement'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('vitamin_a_supplement'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('bank_details'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="bank_details"  id="bank_details" value="<?php echo isset($post['bank_details']) ?  $post['bank_details'] : ''; ?>" placeholder="<?php echo $this->lang->line('bank_details'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('bank_details'); ?></div>
                                     </div>
                                 </div>
								  <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('reservation_category'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="reservation_category"  id="reservation_category" value="<?php echo isset($post['reservation_category']) ?  $post['reservation_category'] : ''; ?>" placeholder="<?php echo $this->lang->line('reservation_category'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('reservation_category'); ?></div>
                                     </div>
                                 </div>
                            </div>
							  <div class="row">
                                
                                
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('bpl'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="bpl"  id="bpl" value="<?php echo isset($post['bpl']) ?  $post['bpl'] : ''; ?>" placeholder="<?php echo $this->lang->line('bpl'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('bpl'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('health_condition'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="health_condition"  id="health_condition" value="<?php echo isset($post['health_condition']) ?  $post['health_condition'] : ''; ?>" placeholder="<?php echo $this->lang->line('health_condition'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('health_condition'); ?></div>
                                     </div>
                                 </div>
                            </div>
                             <div class="row">                                     
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                            <label for="other_info"><?php echo $this->lang->line('other_info'); ?></label> 
                                            <textarea  class="form-control col-md-6 col-xs-12 textarea-4column"  name="other_info"  id="other_info" placeholder="<?php echo $this->lang->line('other_info'); ?>"><?php echo isset($post['other_info']) ?  $post['other_info'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('other_info'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label ><?php echo $this->lang->line('photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="photo"  id="photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('photo'); ?></div>
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                         </div>
                                     </div>                                    
                                </div>
                                                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" id="role_id" name="role_id" value="<?php echo STUDENT; ?>" />
                                        <a  href="<?php echo site_url('student/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('add_student_instruction'); ?></div>
                                </div>
                                
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        
                        <div class="tab-pane fade in active" id="tab_edit_student">
                            <div class="x_content"> 
                            <?php echo form_open_multipart(site_url('student/edit/'. $student->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                               
                                <?php $this->load->view('layout/school_list_edit_form'); ?>
                                
                               <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('basic_information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">                  
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($student->name) ?  $student->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="admission_no"><?php echo $this->lang->line('admission_no'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="admission_no"  id="admission_no" value="<?php echo isset($student->admission_no) ?  $student->admission_no : ''; ?>" placeholder="<?php echo $this->lang->line('admission_no'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('admission_no'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="admission_date"><?php echo $this->lang->line('admission_date'); ?><span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="admission_date"  id="edit_admission_date" value="<?php echo isset($student->admission_date) ?   date('d-m-Y', strtotime($student->admission_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('admission_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('admission_date'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label  for="dob"><?php echo $this->lang->line('birth_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="dob"  id="edit_dob" value="<?php echo isset($student->dob) ?  date('d-m-Y', strtotime($student->dob)) : ''; ?>" placeholder="<?php echo $this->lang->line('birth_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('dob'); ?></div>
                                         </div>
                                    </div>
                                 </div>
                                    
                                <div class="row">    
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="gender"><?php echo $this->lang->line('gender'); ?> <span class="required">*</span></label>
                                              <select  class="form-control col-md-7 col-xs-12"  name="gender"  id="gender" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $genders = get_genders(); ?>
                                                <?php foreach($genders as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if($student->gender == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('gender'); ?></div>
                                         </div>
                                     </div>
                                    
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="blood_group"><?php echo $this->lang->line('blood_group'); ?></label>
                                              <select  class="form-control col-md-7 col-xs-12" name="blood_group" id="blood_group">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $bloods = get_blood_group(); ?>
                                                <?php foreach($bloods as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if($student->blood_group == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                                </select>
                                            <div class="help-block"><?php echo form_error('blood_group'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                              <label for="religion"><?php echo $this->lang->line('religion'); ?></label>
                                              <input  class="form-control col-md-7 col-xs-12"  name="religion"  id="add_religion" value="<?php echo isset($student->religion) ?  $student->religion : ''; ?>" placeholder="<?php echo $this->lang->line('religion'); ?>" type="text" autocomplete="off">
                                               <div class="help-block"><?php echo form_error('religion'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                              <label for="caste"><?php echo $this->lang->line('caste'); ?></label>
											    <select  class="form-control col-md-7 col-xs-12"  name="caste"  id="edit_caste">
                                              <option value=''>--Select--</option>
                                              <option value="General" <?php echo isset($student->caste) && $student->caste== 'General' ? 'selected="selected"'  : ''; ?>>General</option>
											  <option value="SC" <?php echo isset($student->caste) && $student->caste== 'SC' ? 'selected="selected"'  : ''; ?>>SC</option>
											  <option value="ST" <?php echo isset($student->caste) && $student->caste== 'ST' ? 'selected="selected"'  : ''; ?>>ST</option>
											  <option value="OBC" <?php echo isset($student->caste) && $student->caste== 'OBC' ? 'selected="selected"'  : ''; ?>>OBC</option>
											  <option value="EWC" <?php echo isset($student->caste) && $student->caste== 'EWC' ? 'selected="selected"'  : ''; ?>>EWC</option>
                                              <option value="SBC" <?php echo isset($student->caste) && $student->caste== 'SBC' ? 'selected="selected"'  : ''; ?>>SBC</option>
											  <option value="Minority" <?php echo isset($student->caste) && $student->caste== 'Minority' ? 'selected="selected"'  : ''; ?>>Minority</option>
                                            
											  <option value="Others" <?php echo isset($student->caste) && $student->caste== 'Others' ? 'selected="selected"'  : ''; ?>>Others</option>
                                             </select>
                                             
                                               <div class="help-block"><?php echo form_error('caste'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="add_phone" value="<?php echo isset($student->phone) ?  $student->phone : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('phone'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="email"><?php echo $this->lang->line('email'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"   id="email" value="<?php echo isset($student->email) ?  $student->email : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div>
                                         </div>
                                     </div>
                                   <!-- <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="national_id"><?php echo $this->lang->line('national_id'); ?> </label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="national_id"  id="national_id" value="<?php echo isset($student->national_id) ?  $student->national_id : ''; ?>" placeholder="<?php echo $this->lang->line('national_id'); ?>" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('national_id'); ?></div>
                                         </div>
                                     </div>       -->
                                     <!-- Edit the RTE Value -->
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="rte"><?php echo $this->lang->line('rte'); ?> </label>
                                             <select class="form-control col-md-7 col-xs-12"  name="rte"  id="rte" >
                                                <?php $rte_yes =  isset($student->rte) && strtolower($student->rte) == "yes" ? "selected"  : ''; ?>
                                                 <?php $rte_no =  isset($student->rte) && strtolower($student->rte) != "yes" ? "selected"  : ''; ?>
                                                  <option value=''>Select</option>
                                                  <option value="Yes"  <?php echo $rte_yes ?>>Yes</option>
                                                  <option value="No"  <?php echo $rte_no ?>>No</option>
                                            </select>
                                             <div class="help-block"><?php echo form_error('rte'); ?></div>
                                         </div>
                                     </div>      
								<div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('physical_disability'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="physical_disability"  id="physical_disability" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($student->physical_disability) && $student->physical_disability=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($student->physical_disability) && $student->physical_disability=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('physical_disability'); ?></div>
                                         </div>
                                     </div>									 
                                </div>
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('academic_information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="type_id"><?php echo $this->lang->line('student_type'); ?></label>
                                                <select  class="form-control col-md-7 col-xs-12" name="type_id" id="edit_type_id">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($types as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo isset($student) && $student->type_id == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->type; ?></option>
                                                <?php } ?>
                                                </select>
                                             <div class="help-block"><?php echo form_error('type_id'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="class_id" id="edit_class_id" required="required"  onchange="get_section_by_class(this.value, '');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($classes as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($student->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="section_id" id="edit_section_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                         </div>
                                     </div>
                                                            
                                </div>
                                    
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="roll_no"><?php echo $this->lang->line('roll_no'); ?> <span class="required">*</span></label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="roll_no"  id="roll_no"  value="<?php echo isset($student->roll_no) ?  $student->roll_no : ''; ?>" placeholder="<?php echo $this->lang->line('roll_no'); ?>" required="required" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('roll_no'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('registration_no'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="registration_no"  id="registration_no" value="<?php echo isset($student->registration_no) ?  $student->registration_no : ''; ?>" placeholder="<?php echo $this->lang->line('registration_no'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('registration_no'); ?></div>
                                         </div>
                                     </div>
                                     
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="discount_id"><?php echo $this->lang->line('discount'); ?></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="discount_id" id="edit_discount_id">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($discounts as $obj){ ?>                                                    
                                                    <option value="<?php echo $obj->id; ?>" <?php if($student->discount_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->title; ?> [<?php echo $obj->amount; ?>%]</option>                                                   
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('discount_id'); ?></div>
                                         </div>
                                     </div>
                                     <!--   <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="second_language"><?php echo $this->lang->line('second_language'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="second_language"  id="second_language" value="<?php echo isset($student->second_language) ?  $student->second_language : ''; ?>" placeholder="<?php echo $this->lang->line('second_language'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('second_language'); ?></div>
                                         </div>
                                     </div>-->
                                </div>
                                
                                  
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('father_information'); ?>:</strong></h5>
                                    </div>
                                </div> 
                                <div class="row">  
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_name"><?php echo $this->lang->line('father_name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_name"  id="edit_father_name" value="<?php echo isset($student->father_name) ?  $student->father_name : ''; ?>" placeholder="<?php echo $this->lang->line('father_name'); ?>" type="text" autocomplete="off" required="required">
                                            <div class="help-block"><?php echo form_error('father_name'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_phone"><?php echo $this->lang->line('father_phone'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_phone"  id="father_phone" value="<?php echo isset($student->father_phone) ?  $student->father_phone : ''; ?>" placeholder="<?php echo $this->lang->line('father_phone'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_phone'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_education"><?php echo $this->lang->line('father_education'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_education"  id="father_education" value="<?php echo isset($student->father_education) ?  $student->father_education : ''; ?>" placeholder="<?php echo $this->lang->line('father_education'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_education'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_profession"><?php echo $this->lang->line('father_profession'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_profession"  id="father_profession" value="<?php echo isset($student->father_profession) ?  $student->father_profession : ''; ?>" placeholder="<?php echo $this->lang->line('father_profession'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_profession'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_designation"><?php echo $this->lang->line('father_designation'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_designation"  id="father_designation" value="<?php echo isset($student->father_designation) ?  $student->father_designation : ''; ?>" placeholder="<?php echo $this->lang->line('father_designation'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_designation'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label ><?php echo $this->lang->line('father_photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="father_photo"  id="father_photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('father_photo'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <input type="hidden" name="prev_father_photo" id="prev_father_photo" value="<?php echo $student->father_photo; ?>" />
                                            <?php if($student->father_photo){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/father-photo/<?php echo $student->father_photo; ?>" alt="" width="70" /><br/><br/>
                                            <?php } ?>
                                         </div>
                                     </div> 
                                </div>
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('mother_information'); ?>:</strong></h5>
                                    </div>
                                </div> 
                                <div class="row">  
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_name"><?php echo $this->lang->line('mother_name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_name"  id="mother_name" value="<?php echo isset($student->mother_name) ?  $student->mother_name : ''; ?>" placeholder="<?php echo $this->lang->line('mother_name'); ?>" type="text" autocomplete="off" required="required">
                                            <div class="help-block"><?php echo form_error('mother_name'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_phone"><?php echo $this->lang->line('mother_phone'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_phone"  id="mother_phone" value="<?php echo isset($student->mother_phone) ?  $student->mother_phone : ''; ?>" placeholder="<?php echo $this->lang->line('mother_phone'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_phone'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_education"><?php echo $this->lang->line('mother_education'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_education"  id="mother_education" value="<?php echo isset($student->mother_education) ?  $student->mother_education : ''; ?>" placeholder="<?php echo $this->lang->line('mother_education'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_education'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_profession"><?php echo $this->lang->line('mother_profession'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_profession"  id="mother_profession" value="<?php echo isset($student->mother_profession) ?  $student->mother_profession : ''; ?>" placeholder="<?php echo $this->lang->line('mother_profession'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_profession'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_designation"><?php echo $this->lang->line('mother_designation'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_designation"  id="mother_designation" value="<?php echo isset($student->mother_designation) ?  $student->mother_designation : ''; ?>" placeholder="<?php echo $this->lang->line('mother_designation'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_designation'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label ><?php echo $this->lang->line('mother_photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="mother_photo"  id="mother_photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('mother_photo'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <input type="hidden" name="prev_mother_photo" id="prev_mother_photo" value="<?php echo $student->mother_photo; ?>" />
                                            <?php if($student->mother_photo){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/mother-photo/<?php echo $student->mother_photo; ?>" alt="" width="70" /><br/><br/>
                                            <?php } ?>
                                         </div>
                                     </div>
                                </div>
                                
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('guardian_information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                                               
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="guardian_id"><?php echo $this->lang->line('guardian_name'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="guardian_id" id="edit_guardian_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($guardians as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($student->guardian_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('guardian_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="relation_with"><?php echo $this->lang->line('relation_with_guardian'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="relation_with"  id="relation_with" value="<?php echo isset($student->relation_with) ?  $student->relation_with : ''; ?>" placeholder="<?php echo $this->lang->line('relation_with_guardian'); ?>"  type="text">
                                            <div class="help-block"><?php echo form_error('relation_with'); ?></div>
                                        </div>
                                    </div>                                       
                                </div>
                                
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('address_information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">    
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                             <label for="present_address"><?php echo $this->lang->line('present_address'); ?></label>
                                              <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="present_address"  id="add_present_address"  placeholder="<?php echo $this->lang->line('present_address'); ?>"><?php echo isset($student->present_address) ?  $student->present_address : ''; ?></textarea>
                                              <div class="help-block"><?php echo form_error('present_address'); ?></div>
                                         </div>
                                     </div>                                    
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                            <label for="permanent_address"><?php echo $this->lang->line('permanent_address'); ?></label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="permanent_address"  id="add_permanent_address"  placeholder="<?php echo $this->lang->line('permanent_address'); ?>"><?php echo isset($student->permanent_address) ?  $student->permanent_address : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('permanent_address'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                                                
                              
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('previous_school'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">                 
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="previous_school"><?php echo $this->lang->line('school_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="previous_school"  id="previous_school" value="<?php echo isset($student->previous_school) ?  $student->previous_school : ''; ?>" placeholder="<?php echo $this->lang->line('school_name'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('previous_school'); ?></div>
                                         </div>
                                     </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="previous_class"><?php echo $this->lang->line('previous_class'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="previous_class"  id="previous_class" value="<?php echo isset($student->previous_class) ?  $student->previous_class : ''; ?>" placeholder="<?php echo $this->lang->line('previous_class'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('previous_class'); ?></div>
                                         </div>
                                     </div>
                                   
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label ><?php echo $this->lang->line('transfer_certificate'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="transfer_certificate"  id="transfer_certificate" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('transfer_certificate'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <input type="hidden" name="prev_transfer_certificate" id="prev_transfer_certificate" value="<?php echo $student->transfer_certificate; ?>" />
                                            <?php if($student->transfer_certificate){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/transfer-certificate/<?php echo $student->transfer_certificate; ?>" alt="" width="70" /><br/><br/>
                                            <?php } ?>
                                         </div>
                                     </div>                                    
                                </div>
                                <div class='row'>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('reason_of_seperation'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="reason_of_seperation"  id="reason_of_seperation" value="<?php echo isset($student->reason_of_seperation) ?  $student->reason_of_seperation : ''; ?>" placeholder="<?php echo $this->lang->line('reason_of_seperation'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('reason_of_seperation'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('date_of_seperation'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="date_of_seperation"  id="edit_date_of_seperation" value="<?php echo isset($student->date_of_seperation) ?  date('d-m-Y', strtotime($student->date_of_seperation)) : ''; ?>" placeholder="<?php echo $this->lang->line('date_of_seperation'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('date_of_seperation'); ?></div>
                                     </div>
                                 </div>
                                </div>
                                                                
                               <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('other_information'); ?>:</strong></h5>
                                    </div>
                                </div>    
                                <div class="row">                  
                                                                         
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="username"><?php echo $this->lang->line('username'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="username"  id="username" readonly="readonly" value="<?php echo isset($student->username) ?  $student->username : ''; ?>" placeholder="<?php echo $this->lang->line('username'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('username'); ?></div>
                                         </div>
                                     </div>
									 <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="facilities"><?php echo $this->lang->line('facilities'); ?> </label>
											
                                                <?php $facilities = get_student_facilities(); 
													$student_facilities=explode(",",$student->facilities);
												?>
                                                <?php foreach($facilities as $key=>$value){ ?><span>
													<input type="checkbox" name="facilities[]" value="<?php print $key; ?>"  <?php if(in_array($key,$student_facilities)){ echo 'checked="checked"'; } ?>><?php echo $value; ?></span>                                                   
                                                <?php } ?>                                            
                                            <div class="help-block"><?php echo form_error('facilities'); ?></div>
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="health_condition"><?php echo $this->lang->line('health_condition'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="health_condition"  id="health_condition" value="<?php echo isset($student->health_condition) ?  $student->health_condition : ''; ?>" placeholder="<?php echo $this->lang->line('health_condition'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('health_condition'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="status_type"><?php echo $this->lang->line('status'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12" name="status_type"  id="status_type" >
                                                <option value="regular" <?php echo $student->status_type == 'regular' ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('regular'); ?></option>
                                                <option value="drop" <?php echo $student->status_type == 'drop' ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('drop'); ?></option>
                                                <option value="transfer" <?php echo $student->status_type == 'transfer' ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('transfer'); ?></option>
                                                <!-- <option value="passed" <?php //echo $student->status_type == 'passed' ?  'selected="selected"' : ''; ?>><?php //echo $this->lang->line('passed'); ?></option> -->
                                            </select>
                                            <div class="help-block"><?php echo form_error('status_type'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('alternate_name'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="alternate_name"  id="alternate_name" value="<?php echo isset($student->alternate_name) ?  $student->alternate_name : ''; ?>" placeholder="<?php echo $this->lang->line('alternate_name'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('alternate_name'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('reservation_category'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="reservation_category"  id="reservation_category" value="<?php echo isset($student->reservation_category) ?  $student->reservation_category : ''; ?>" placeholder="<?php echo $this->lang->line('reservation_category'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('reservation_category'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('bpl'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="bpl"  id="bpl" value="<?php echo isset($student->bpl) ?  $student->bpl : ''; ?>" placeholder="<?php echo $this->lang->line('bpl'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('bpl'); ?></div>
                                     </div>
                                 </div>
								  <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('bank_details'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="bank_details"  id="bank_details" value="<?php echo isset($student->bank_details) ?  $student->bank_details : ''; ?>" placeholder="<?php echo $this->lang->line('bank_details'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('bank_details'); ?></div>
                                     </div>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('adhar_no'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="adhar_no"  id="adhar_no" value="<?php echo isset($student->adhar_no) ?  $student->adhar_no : ''; ?>" placeholder="<?php echo $this->lang->line('adhar_no'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('adhar_no'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('sm_id'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="sm_id"  id="sm_id" value="<?php echo isset($student->sm_id) ?  $student->sm_id : ''; ?>" placeholder="<?php echo $this->lang->line('sm_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('sm_id'); ?></div>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('family_id'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="family_id"  id="family_id" value="<?php echo isset($student->family_id) ?  $student->family_id : ''; ?>" placeholder="<?php echo $this->lang->line('family_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('family_id'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('rural_urban'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="rural_urban"  id="rural_urban" value="<?php echo isset($student->rural_urban) ?  $student->rural_urban : ''; ?>" placeholder="<?php echo $this->lang->line('rural_urban'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('rural_urban'); ?></div>
                                     </div>
                                 </div>
                            </div>
                           <div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('midium_instruction'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="medium_of_instruction"  id="midium_instruction" value="<?php echo isset($student->medium_of_instruction) ?  $student->medium_of_instruction : ''; ?>" placeholder="<?php echo $this->lang->line('midium_instruction'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('medium_of_instruction'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('free_education'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="free_education"  id="free_education" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($student->free_education) && $student->free_education=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($student->free_education) && $student->free_education=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('free_education'); ?></div>
                                         </div>
                                     </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('previous_year_attended_days'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="previous_year_attended_days"  id="previous_year_attended_days" value="<?php echo isset($student->previous_year_attended_days) ?  $student->previous_year_attended_days : ''; ?>" placeholder="<?php echo $this->lang->line('previous_year_attended_days'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('previous_year_attended_days'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('facilities_by_cwsn'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="facilities_by_cwsn"  id="facilities_by_cwsn" value="<?php echo isset($student->facilities_by_cwsn) ?  $student->facilities_by_cwsn : ''; ?>" placeholder="<?php echo $this->lang->line('facilities_by_cwsn'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('facilities_by_cwsn'); ?></div>
                                     </div>
                                 </div>								 
							</div>
							<div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('no_of_uniform_sets'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="no_of_uniform_sets"  id="no_of_uniform_sets" value="<?php echo isset($student->no_of_uniform_sets) ?  $student->no_of_uniform_sets : ''; ?>" placeholder="<?php echo $this->lang->line('no_of_uniform_sets'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('no_of_uniform_sets'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_text_books'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_text_books"  id="free_text_books" value="<?php echo isset($student->free_text_books) ?  $student->free_text_books : ''; ?>" placeholder="<?php echo $this->lang->line('free_text_books'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_text_books'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_transport'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_transport"  id="free_transport" value="<?php echo isset($student->free_transport) ?  $student->free_transport : ''; ?>" placeholder="<?php echo $this->lang->line('free_transport'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_transport'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_escort'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_escort"  id="free_escort" value="<?php echo isset($student->free_escort) ?  $student->free_escort : ''; ?>" placeholder="<?php echo $this->lang->line('free_escort'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_escort'); ?></div>
                                     </div>
                                 </div>
							</div>
							<div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('mdm_benificiary'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="mdm_benificiary"  id="mdm_benificiary" value="<?php echo isset($student->mdm_benificiary) ?  $student->mdm_benificiary : ''; ?>" placeholder="<?php echo $this->lang->line('mdm_benificiary'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('mdm_benificiary'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('free_hostel'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="free_hostel"  id="free_hostel" value="<?php echo isset($student->free_hostel) ?  $student->free_hostel : ''; ?>" placeholder="<?php echo $this->lang->line('free_hostel'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('free_hostel'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('special_training'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="special_training"  id="special_training" value="<?php echo isset($student->special_training) ?  $student->special_training : ''; ?>" placeholder="<?php echo $this->lang->line('special_training'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('special_training'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('appeared_in_last_exam'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="appeared_in_last_exam"  id="appeared_in_last_exam" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($student->appeared_in_last_exam) && $student->appeared_in_last_exam=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($student->appeared_in_last_exam) && $student->appeared_in_last_exam=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('appeared_in_last_exam'); ?></div>
                                         </div>
                                     </div>
							</div>
							<div class='row'>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('last_exam_passed'); ?></label>
                                            <select class="form-control col-md-7 col-xs-12"  name="last_exam_passed"  id="last_exam_passed" >
                                                  <option value=''>Select</option>
                                                  <option value="Yes" <?php if(isset($student->last_exam_passed) && $student->last_exam_passed=='Yes')echo 'selected'; ?>>Yes</option>
                                                  <option value="No" <?php if(isset($student->last_exam_passed) && $student->last_exam_passed=='No')echo 'selected'; ?>>No</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('last_exam_passed'); ?></div>
                                         </div>
                                     </div>
									 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('last_exam_marks'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="last_exam_marks"  id="last_exam_marks" value="<?php echo isset($student->last_exam_marks) ?  $student->last_exam_marks : ''; ?>" placeholder="<?php echo $this->lang->line('last_exam_marks'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('last_exam_marks'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('trade_sector'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="trade_sector"  id="trade_sector" value="<?php echo isset($student->trade_sector) ?  $student->trade_sector : ''; ?>" placeholder="<?php echo $this->lang->line('trade_sector'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('trade_sector'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('iron_folic_acid_tablets'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="iron_folic_acid_tablets"  id="iron_folic_acid_tablets" value="<?php echo isset($student->iron_folic_acid_tablets) ?  $student->iron_folic_acid_tablets : ''; ?>" placeholder="<?php echo $this->lang->line('iron_folic_acid_tablets'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('iron_folic_acid_tablets'); ?></div>
                                     </div>
                                 </div>
							</div>
							<div class="row">
							 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('deworming_tablets'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="deworming_tablets"  id="deworming_tablets" value="<?php echo isset($student->deworming_tablets) ?  $student->deworming_tablets : ''; ?>" placeholder="<?php echo $this->lang->line('deworming_tablets'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('deworming_tablets'); ?></div>
                                     </div>
                                 </div>
								  <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="bpl"><?php echo $this->lang->line('vitamin_a_supplement'); ?> </label>
                                        <input  class="form-control col-md-7 col-xs-12"  name="vitamin_a_supplement"  id="vitamin_a_supplement" value="<?php echo isset($student->vitamin_a_supplement) ?  $student->vitamin_a_supplement : ''; ?>" placeholder="<?php echo $this->lang->line('vitamin_a_supplement'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('vitamin_a_supplement'); ?></div>
                                     </div>
                                 </div>
                                
                                
                            </div>
                                <div class="row">     
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                           <label for="other_info"><?php echo $this->lang->line('other_info'); ?></label> 
                                           <textarea  class="form-control col-md-6 col-xs-12 textarea-4column"  name="other_info"  id="other_info" placeholder="<?php echo $this->lang->line('other_info'); ?>"><?php echo isset($student->other_info) ?  $student->other_info : ''; ?></textarea>
                                           <div class="help-block"><?php echo form_error('other_info'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label ><?php echo $this->lang->line('photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="photo"  id="photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('photo'); ?></div>
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <input type="hidden" name="prev_photo" id="prev_photo" value="<?php echo $student->photo; ?>" />
                                            <?php if($student->photo){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/student-photo/<?php echo $student->photo; ?>" alt="" width="70" /><br/><br/>
                                            <?php } ?>
                                         </div>
                                     </div>                                    
                                </div>
                                
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" name="id" id="id" value="<?php echo $student->id; ?>" />
                                        <a href="<?php echo site_url('student/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div> 
                        <?php } ?>
                        
                         <?php if(isset($detail)){ ?>
                             <div class="tab-pane fade in active" id="tab_view_student">
                                <div class="x_content"> 
                                  <?php $this->load->view('get-single-student'); ?>
                                </div>
                             </div>
                        <?php } ?>
                        
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade bs-student-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_student_data">
            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_student_modal(student_id){
         
        $('.fn_student_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('student/get_single_student'); ?>",
          data   : {student_id : student_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_student_data').html(response);
             }
          }
       });
    }
</script>


<div class="modal fade bs-activity-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_activity_data">
            
        </div>       
      </div>
    </div>
</div>
<div class="modal fade bs-move-student-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_move-student_data" style="padding:50px;">
		<div class="row">
		<?php echo form_open_multipart(site_url('student/move'), array('name' => 'student', 'id' => 'moveStudent', 'class' => 'form-horizontal form-label-left'), ''); ?>
            <?php 
			if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
				// school dropdown
				?>
				 <div class='col-md-3 col-sm-3 col-xs-12'>
                                    <select id="move_school_id" class="form-control col-md-7 col-xs-12" style="width:auto;" name="move_school_id"  onchange="get_class_by_school(this.value, '','move_class_id');">
                                            <option value="">--<?php echo $this->lang->line('select_school'); ?>--</option> 
                                        <?php foreach($schools as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>"> <?php echo $obj->school_name; ?></option>
                                        <?php } ?>   
                                    </select>
								</div>
			<?php } else{ ?>
			<input type="hidden" value="<?php print $filter_school_id; ?>" name='move_school_id' id='move_school_id' />
			<?php } ?>
			<div class='col-md-3 col-sm-3 col-xs-12'>
                                    <select  class="form-control col-md-7 col-xs-12" id="move_class_id" name="class_id"  style="width:auto;" onchange="get_section_by_class(this.value,'','move_section_id')">
                                         <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                        <?php if(isset($class_list) && !empty($class_list)){ ?>
                                            <?php foreach($class_list as $obj ){ ?>
                                                <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
								</div>
								<div class='col-md-3 col-sm-3 col-xs-12'>
                                    <select  class="form-control col-md-7 col-xs-12" id="move_section_id" name="section_id"  style="width:auto;">
                                         <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                         
                                    </select>
								</div>
								  <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="submit" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                        </div>
                    </div>
			<?php echo form_close(); ?>
								</div>
        </div>       
      </div>
    </div>
</div>
<!-- ak -->

<div class="modal fade bs-update-status-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_student_data">
            
        </div>       
      </div>
    </div>
</div>

<!-- ak -->

<script type="text/javascript">
         
    function get_activity_modal(activity_id){
         
        $('.fn_activity_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('student/activity/get_single_activity'); ?>",
          data   : {activity_id : activity_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_activity_data').html(response);
             }
          }
       });
    }
</script>

  
  <!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>

 
<!-- Super admin js START  -->
 <script type="text/javascript">
     var __school_code = "";
    var edit = false;
  
    
    $("document").ready(function() {
        $('.buttons-columnVisibility').on('click', function (e) {
            $('.sorting_1').attr('colspan',0);
            // Get the column API object
            console.log("Test")
        });
    
         <?php if(isset($student) && !empty($student)){ ?>
            $("#edit_school_id").trigger('change');   
         <?php }elseif($post && !empty ($post)){ ?> 
             $("#add_school_id").trigger('change');  
         <?php } ?>
    });
    
     <?php if(isset($student) && !empty($student)){ ?>
          edit = true; 
     <?php } ?>
         
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();        
        var class_id = '';
        var guardian_id = '';       
        var discount_id = ''; 
        var type_id = ''; 
        
        <?php if(isset($edit) && !empty($edit)){ ?>
                class_id =  '<?php echo $student->class_id; ?>';
                guardian_id =  '<?php echo $student->guardian_id; ?>';
                discount_id =  '<?php echo $student->discount_id; ?>';
                type_id =  '<?php echo $student->type_id; ?>';
         <?php }elseif($post && !empty ($post)){ ?>
                class_id =  '<?php echo $post['class_id']; ?>';
                guardian_id =  '<?php echo $post['guardian_id']; ?>';
                discount_id =  '<?php echo $post['discount_id']; ?>';
                type_id =  '<?php echo $post['type_id']; ?>';
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
                   if(edit){
                       $('#edit_class_id').html(response);   
                   }else{
                       $('#add_class_id').html(response);   
                   }
                                    
                   get_guardian_by_school(school_id, guardian_id);
                   get_discount_by_school(school_id, discount_id);
                   get_student_type_by_school(school_id, type_id);
                
               }
            }
        });
        getschoolCode(school_id);
    }); 
    function getschoolCode(school_id)
    {
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_school_code'); ?>",
            data   : { school_id:school_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                    __school_code = response;
                
               }
            }
        });
    }
    
    function get_guardian_by_school(school_id, guardian_id){
    
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_guardian_by_school'); ?>",
            data   : { school_id:school_id, guardian_id: guardian_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(edit){
                       $('#edit_guardian_id').html(response);
                   }else{
                       $('#add_guardian_id').html(response); 
                   }
               }
            }
        });
    }
        
    function get_discount_by_school(school_id, discount_id){
    
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_discount_by_school'); ?>",
            data   : { school_id:school_id, discount_id: discount_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(edit){
                       $('#edit_discount_id').html(response);
                   }else{
                       $('#add_discount_id').html(response); 
                   }
               }
            }
        });
    }
    
    $('#admission_no').on('keyup',function(){
        
        $('.username_add').val(__school_code+'-'+this.value)
        $('#add_gud_username').val(__school_code+'-'+this.value+"_P")

    })
    function get_student_type_by_school(school_id, type_id){
    
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_student_type_by_school'); ?>",
            data   : { school_id:school_id, type_id: type_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(edit){
                       $('#edit_type_id').html(response);
                   }else{
                       $('#add_type_id').html(response); 
                   }
               }
            }
        });
    }
    
     
    $('#add_admission_date').datepicker();
    $('#edit_admission_date').datepicker();
    $('#add_dob').datepicker({ startView: 2 });
    $('#edit_dob').datepicker({ startView: 2 });
   $('#add_date_of_seperation').datepicker();
    $('#edit_date_of_seperation').datepicker();
    <?php if(isset($edit)){ ?>
        get_section_by_class('<?php echo $student->class_id; ?>', '<?php echo $student->section_id; ?>');
    <?php }elseif($post && !empty ($post)){ ?>  
        get_section_by_class('<?php echo $post['class_id']; ?>', '<?php echo $post['section_id']; ?>');
    <?php } ?>
    
    function get_section_by_class(class_id, section_id,id){               
        var school_id = '';
        <?php if(isset($edit)){ ?>                
            school_id = $('#edit_school_id').val();
         <?php }else{ ?> 
            school_id = $('#add_school_id').val();
         <?php } ?> 
          if(id){
			school_id=$("#move_school_id").val();
		}         
       if(!school_id){
           toastr.error('<?php echo $this->lang->line('select_school'); ?>');
           return false;
        }
       
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : { school_id:school_id, class_id : class_id , section_id: section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
				   if(id){
					   $("#"+id).html(response);
				   }
				   else{
					   if(edit){
						   $('#edit_section_id').html(response);
					   }else{
						   $('#add_section_id').html(response);
					   }
				   }
               }
            }
        });  
                     
        
   }
  </script>
  
  <!-- datatable with buttons -->
 <script type="text/javascript">
        $(document).ready(function() {
			var sch_id='<?php print $filter_school_id; ?>';
			var class_id='<?php print $class_id; ?>';
          $('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
			  orderCellsTop: true,
				fixedHeader: true,
			  'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("student/get_list"); ?>',
		  'data': {'school_id': sch_id, 'class_id': class_id}
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
						"targets": [0] ,
						"searchable": false,
						"orderable": false,						
					},

                    {
						"targets": [8,10,11,12,13] ,
						"searchable": false,
                        "visible": false,
						"orderable": false,						
					},
                    {
						"targets": [9] ,
						"searchable": false,
                        "visible": false,
						"orderable": true,						
					},
                   

					],
              search: true,              
              responsive: false
          });
		    $('#checkall').click(function(){
      if($(this).is(':checked')){
         $('.delete_check').prop('checked', true);
      }else{
         $('.delete_check').prop('checked', false);
      }
   });
   $("#multiAction").bind( 'change', function () {	  
	   if(this.value == 'move'){
		   var checked = $("#delete_multiple input.delete_check:checked").length > 0;
							if (!checked){
								alert("Please check at least one checkbox");
								return false;
							}
							else{
		   if(confirm("Are you sure you want to move selected student?")){
			   $(".bs-move-student-modal-lg").modal('show');
		   }
							}
	   }
   });
   $("#moveStudent").submit(function( event ) {
	   var student_data=$("#delete_multiple").serialize();
	   var detail=$("#moveStudent").serialize();
	   $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('student/move'); ?>",
            data   : { student_data,detail},               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
					location.reload();                   
               }
            }
        });
	   return false;
   });
        });
        
        
      
        function check_guardian_type(guardian_type){
           
            $('#add_relation_with').val('');  
            $('#add_gud_name').val('');  
            $('#add_gud_phone').val('');  
            $('#add_gud_present_address').val('');  
            $('#add_gud_permanent_address').val('');  
            $('#add_gud_religion').val(''); 
            $('#add_gud_profession').val(''); 
            $('#add_gud_national_id').val(''); 
            $('#add_gud_email').val(''); 
            $('#add_gud_other_info').val(''); 
                    
           if(guardian_type == 'father'){
               
               $('#add_relation_with').val('<?php echo $this->lang->line('father'); ?>'); 
               $('.fn_existing_guardian').hide();
               $('.fn_except_exist').show();
               $('#guardian_id').prop('required', false);               
               $('#add_gud_name').prop('required', true);               
               $('#add_gud_phone').prop('required', true);               
               $('#add_gud_email').prop('required', true);               
               
               var f_name = $('#add_father_name').val();
               var f_phone = $('#add_father_phone').val(); 
               var f_education = $('#add_father_education').val(); 
               var f_profession = $('#add_father_profession').val(); 
               var f_designation = $('#add_father_designation').val(); 
               
               $('#add_gud_name').val(f_name);  
               $('#add_gud_phone').val(f_phone); 
               $('#add_gud_profession').val(f_profession); 
               
           }else if(guardian_type == 'mother'){
               
               $('#add_relation_with').val('<?php echo $this->lang->line('mother'); ?>');   
               $('.fn_existing_guardian').hide();
               $('.fn_except_exist').show();
               $('#guardian_id').prop('required', false);
               $('#add_gud_name').prop('required', true);               
               $('#add_gud_phone').prop('required', true);               
               $('#add_gud_email').prop('required', true); 
               
               var m_name = $('#add_mother_name').val();
               var m_phone = $('#add_mother_phone').val(); 
               var m_education = $('#add_mother_education').val(); 
               var m_profession = $('#add_mother_profession').val(); 
               var m_designation = $('#add_mother_designation').val(); 
               
               $('#add_gud_name').val(m_name);  
               $('#add_gud_phone').val(m_phone); 
               $('#add_gud_profession').val(m_profession); 
               
           }else if(guardian_type == 'other'){
               $('#add_relation_with').val('<?php echo $this->lang->line('other'); ?>');    
               $('.fn_existing_guardian').hide();
               $('.fn_except_exist').show();
               $('#guardian_id').prop('required', false);
               $('#add_gud_name').prop('required', true);               
               $('#add_gud_phone').prop('required', true);               
               $('#add_gud_email').prop('required', true); 
                              
           }else if(guardian_type == 'exist_guardian'){
               $('.fn_existing_guardian').show();
               $('.fn_except_exist').hide();
               $('#guardian_id').prop('required', true);   
               $('#add_gud_name').prop('required', false);               
               $('#add_gud_phone').prop('required', false);               
               $('#add_gud_email').prop('required', false); 
               
           }else{
                $('#add_relation_with').val('');   
                $('.fn_existing_guardian').hide();
                $('.fn_except_exist').show();
                $('#guardian_id').prop('required', false);
                $('#add_gud_name').prop('required', true);               
                $('#add_gud_phone').prop('required', true);               
                $('#add_gud_email').prop('required', true); 
           }
        
        }
        
        function get_guardian_by_id(guardian_id){                       
            
            $.ajax({       
            type   : "POST",
            dataType: "json",
            url    : "<?php echo site_url('ajax/get_guardian_by_id'); ?>",
            data   : { guardian_id : guardian_id},               
            async  : true,
            success: function(response){ 
               if(response)
               {
                    $('#add_gud_name').val(response.name);  
                    $('#add_gud_phone').val(response.phone);  
                    $('#add_gud_present_address').val(response.present_address);  
                    $('#add_gud_permanent_address').val(response.permanent_address);  
                    $('#add_gud_religion').val(response.religion);  
                    $('#add_gud_profession').val(response.profession);  
                    $('#add_gud_national_id').val(response.national_id);  
                    $('#add_gud_email').val(response.email);  
                    $('#add_gud_other_info').val(response.other_info);  
               }
               else
               {
                    $('#add_relation_with').val('');  
                    $('#add_gud_name').val('');  
                    $('#add_gud_phone').val('');  
                    $('#add_gud_present_address').val('');  
                    $('#add_gud_permanent_address').val('');  
                    $('#add_gud_religion').val(''); 
                    $('#add_gud_profession').val(''); 
                    $('#add_gud_national_id').val(''); 
                    $('#add_gud_email').val(''); 
                    $('#add_gud_other_info').val(''); 
               }
            }
        });  
        
        }
        
        
    $('#bulk_pass_button').on('click', function(){
        var selectedID = [];
        $(".delete_check[type='checkbox']").each (function () {
            if($(this).is(":checked")){
                selectedID.push($(this).val());
            }
        });
        var error = false;
        if(!selectedID.length )
        {
            alert('<?php echo $this->lang->line('select_student_alert');?>')
            error = true;
        }
        else
        {
            var confirm_bulk_pass = confirm('<?php echo $this->lang->line('bulk_pass_confirm');?>');
            if(selectedID.length && confirm_bulk_pass && !error)
            {
                $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('student/bulk_pass'); ?>",
                data   : { student_ids : selectedID },  
                dataType: "json",          
                async  : false,
                success: function(response){                                                
                }
                });
            }

        }
        
    });
    $('#same_as_guardian').on('click', function(){
        
        if($(this).is(":checked")) {
            var present =  $('#add_gud_present_address').val();  
            var permanent = $('#add_gud_permanent_address').val();  
            $('#add_present_address').val(present);  
            $('#add_permanent_address').val(permanent);  
        }else{
             $('#add_present_address').val('');  
             $('#add_permanent_address').val(''); 
        }
    });
        
        
     /* Menu Filter Start */   
    function get_student_by_class(url){      
        if(url){
            location.href = url; 
        }
        else
        {
            location.href = ''; 
        }
    }         
       
        
    <?php if(isset($filter_class_id)){ ?>
        get_class_by_school('<?php echo $filter_school_id; ?>', '<?php echo $filter_class_id; ?>');
    <?php } ?>
    
    function get_class_by_school(school_id, class_id,id){        
      
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id : school_id, class_id : class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
					if(id){
						$("#"+id).html(response);
					}
					else{						
						$('#filter_class_id').html(response);                     
					}
               }
            }
        });
    } 
    
    function update_status_type(student_id, value){
        
          $('.fn_student_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');

        if(value == 'drop') {
            $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('student/dropStudentForm'); ?>",
                data   : {student_id : student_id},  
                success: function(response){                                                   
                    if(response)
                    {
                        $(".bs-update-status-modal-lg").modal('show');
                        $('.fn_student_data').html(response);
                    }
                }
            });
        } else if(value == 'transfer') {
            $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('student/transferStudentForm'); ?>",
                data   : {student_id : student_id},  
                success: function(response){                                                   
                    if(response)
                    {
                        $(".bs-update-status-modal-lg").modal('show');
                        $('.fn_student_data').html(response);
                    }
                }
            });
        } else if(value == 'passed'){
            $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('student/pass_student'); ?>",
                data   : {student_id : student_id},  
                success: function(response){                                                   
                 if(response)
                 {
                    $('.fn_student_data').html(response);
                 }
              }
            });
        }
        
    }
</script>



<?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){ 
?>

 <script>
      $(document).ready(function() {        
        var class_id   = "<?php echo $filter_class_id; ?>";
        var school_id = "<?php echo $this->session->userdata('school_id') ?>";
        getschoolCode( school_id)
		get_class_by_school(school_id,'','move_class_id');
        var type_id = '';
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_student_type_by_school'); ?>",
            data   : { school_id:school_id, type_id: type_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(edit){
                       $('#edit_type_id').html(response);
                   }else{
                       $('#add_type_id').html(response); 
                   }


                            $.ajax({       
                                type   : "POST",
                                url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
                                data   : { school_id : school_id, class_id:class_id},               
                                async  : false,
                                success: function(response){                                                   
                                   if(response)
                                   { 
                                        $('#add_class_id').html(response);                     
                                   }
                                }
                            });

               }
            }
        });
    });

        
  
    
  </script>
<?php } ?>



