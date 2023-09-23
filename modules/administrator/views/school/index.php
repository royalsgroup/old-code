<?php
$superadminonly = "";
if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
    $superadminonly = "readonly";
 }    
?>   
<?php $voucher_category = getVoucherCategory();?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_school'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'administrator', 'setting')){ ?>
                    <a href="<?php echo site_url('administrator/setting'); ?>"><?php echo $this->lang->line('general'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'school')){ ?>
                   | <a href="<?php echo site_url('administrator/school'); ?>"><?php echo $this->lang->line('manage_school'); ?></a>
                <?php } ?>
                <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ||  has_permission(VIEW, 'administrator', 'import_csv') ){ ?>
                   |  <a href="<?php echo site_url('import/csv'); ?>"><?php echo $this->lang->line('import'); ?> <?php echo $this->lang->line('csv'); ?></a>
                <?php } ?>		
                <?php if(has_permission(VIEW, 'administrator', 'payment')){ ?>
                    | <a href="<?php echo site_url('administrator/payment'); ?>"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>                    
                <?php if(has_permission(VIEW, 'administrator', 'sms')){ ?>
                    | <a href="<?php echo site_url('administrator/sms'); ?>"><?php echo $this->lang->line('sms'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>      
                <?php if(has_permission(VIEW, 'administrator', 'emailsetting')){ ?>
                    | <a href="<?php echo site_url('administrator/emailsetting'); ?>"><?php echo $this->lang->line('email'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>    
                <?php if(has_permission(VIEW, 'administrator', 'year')){ ?>
                    | <a href="<?php echo site_url('administrator/year'); ?>"><?php echo $this->lang->line('academic_year'); ?></a>
                <?php } ?>                  
                <?php if(has_permission(VIEW, 'administrator', 'role')){ ?>
                   | <a href="<?php echo site_url('administrator/role'); ?>"><?php echo $this->lang->line('user_role'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'permission')){ ?>
                   | <a href="<?php echo site_url('administrator/permission'); ?>"><?php echo $this->lang->line('role_permission'); ?></a>                   
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'superadmin')){ ?>
                   | <a href="<?php echo site_url('administrator/superadmin'); ?>"><?php echo $this->lang->line('super_admin'); ?></a>                
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'user')){ ?>
                   | <a href="<?php echo site_url('administrator/user'); ?>"><?php echo $this->lang->line('manage_user'); ?></a>                
                <?php } ?>
                <?php if(has_permission(EDIT, 'administrator', 'password')){ ?>
                   | <a href="<?php echo site_url('administrator/password'); ?>"><?php echo $this->lang->line('reset_user_password'); ?></a>                   
                <?php } ?>                
                <?php if(has_permission(VIEW, 'administrator', 'usercredential')){ ?>
                   | <a href="<?php echo site_url('administrator/usercredential/index'); ?>"> <?php echo $this->lang->line('user'); ?> <?php echo $this->lang->line('credential'); ?></a>                   
                <?php } ?>                
                <?php if(has_permission(VIEW, 'administrator', 'activitylog')){ ?>
                   | <a href="<?php echo site_url('administrator/activitylog'); ?>"><?php echo $this->lang->line('activity_log'); ?></a>                  
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'feedback')){ ?>
                   | <a href="<?php echo site_url('administrator/feedback'); ?>"><?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('feedback'); ?></a>                  
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'backup')){ ?>
                   | <a href="<?php echo site_url('administrator/backup'); ?>"><?php echo $this->lang->line('backup'); ?> <?php echo $this->lang->line('database'); ?></a>                  
                <?php } ?>
            </div>
            
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'administrator', 'school')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_school_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'administrator', 'school')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('administrator/school/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('school'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_school"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('school'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_school"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('school'); ?></a> </li>                          
                        <?php } ?> 
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_school_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('address'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('admin_logo'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($schools) && !empty($schools)){ ?>
                                        <?php foreach($schools as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo $obj->school_name; ?></td>
											<td><?php echo $obj->school_code; ?></td>
                                            <td><?php echo $obj->address; ?></td>
                                            <td><?php echo $obj->phone; ?></td>
                                            <td><?php echo $obj->email; ?></td>
                                            <td>
                                            <?php if($obj->logo){ ?>
                                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $obj->logo; ?>" alt="" width="80" style="background: #34347a; padding: 5px;" /><br/><br/>
                                            <?php } ?>
                                            </td>
                                            <td><?php echo $obj->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>
                                            <td>
                                                <?php if(has_permission(VIEW, 'administrator', 'school')){ ?>
                                                    <a  onclick="get_school_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-school-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a><br/>
													 <a  onclick="printPageArea(<?php echo $obj->id; ?>);"    class="btn btn-info btn-xs"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?> </a><br/>
                                                <?php } ?>    
                                                <?php if(has_permission(EDIT, 'administrator', 'school')){ ?>
                                                    <a href="<?php echo site_url('administrator/school/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'administrator', 'school')){ ?>
                                                    <a href="<?php echo site_url('administrator/school/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_school">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('administrator/school/add'), array('name' => 'add', 'id' => 'add', 'class'=>'school_frm form-horizontal form-label-left'), ''); ?>
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('basic_information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                 <div class="row">
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('state'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 fn_state_id" id="add_state_id"  name="state_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($states AS $obj){ ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($post) && $post['state_id'] == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div> 
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('zone'); ?></label>
                                            <select autofocus="" id="add_zone_id" name="zone_id" class="form-control fn_zone_id" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('zone'); ?></div> 
                                        </div>
                                    </div> 	
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('subzone'); ?> </label>
                                            <select autofocus="" id="add_subzone_id" name="subzone_id" class="form-control fn_subzone_id" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('subzone'); ?></div> 
                                        </div>
                                    </div> 										
                                    </div> 										
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('district'); ?> </label>
                                            <select autofocus="" id="add_district_id" name="district_id" class="form-control fn_district_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('district'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('block'); ?> </label>
                                            <select autofocus="" id="add_block_id" name="block_id" class="form-control fn_block_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('block'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('sankul'); ?> </label>
                                            <select autofocus="" id="add_sankul_id" name="sankul_id" class="form-control fn_sankul_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('sankul'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"> School Type :  </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="school_type">
												<option value=''>--Select School Type--</option>
                                                    <option value="1" <?php echo isset($post['school_type']) && $post['school_type'] == '1' ? "selected" : ''; ?>>School</option>
                                                    <option value="2" <?php echo isset($post['school_type']) && $post['school_type'] == '2' ? "selected" : ''; ?>>Sanskar Kendra</option>
                                                    <option value="3" <?php echo isset($post['school_type']) && $post['school_type'] == '3' ? "selected" : ''; ?>>Ekal Vidhyalya</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('school_type'); ?></div> 
                                        </div>
                                    </div>
								</div>
                                <div class="row">                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_code"><?php echo $this->lang->line('school_code'); ?><span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12" id="add_school_code"  name="school_code"  id="school_code" value="<?php echo isset($post['school_code']) ?  $post['school_code'] : ''; ?>" placeholder="<?php echo $this->lang->line('school_code'); ?> " required="required"  type="text" autocomplete="off" <?php echo $superadminonly ?>>
                                            <div class="help-block"><?php echo form_error('school_code'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_name"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_name"  id="school_name" value="<?php echo isset($post['school_name']) ?  $post['school_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_name'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="address"><?php echo $this->lang->line('address'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="address"  id="address" value="<?php echo isset($post['address']) ?  $post['address'] : ''; ?>" placeholder="<?php echo $this->lang->line('address'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('address'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="pincode"><?php echo $this->lang->line('pincode'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pincode"  id="add_pincode" value="<?php echo isset($post['pincode']) ?  $post['pincode'] : ''; ?>" placeholder="<?php echo $this->lang->line('pincode'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pincode'); ?></div> 
                                        </div>
                                    </div>
                                    
                                </div>
                                 <div class="row">
                                   <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="phone" value="<?php echo isset($post['phone']) ?  $post['phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('phone'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="email"><?php echo $this->lang->line('email'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"  id="email" value="<?php echo isset($post['email']) ?  $post['email'] : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?> " required="required" type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_fax"><?php echo $this->lang->line('school_fax'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_fax"  id="school_fax" value="<?php echo isset($post['school_fax']) ?  $post['school_fax'] : ''; ?>" placeholder="<?php echo $this->lang->line('school_fax'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_fax'); ?></div> 
                                        </div>
                                    </div>                                   
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="footer"><?php echo $this->lang->line('footer'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="footer"  id="footer" value="<?php echo isset($post['footer']) ?  $post['footer'] : ''; ?>" placeholder="<?php echo $this->lang->line('footer'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('footer'); ?></div> 
                                        </div>
                                    </div>                                   
                                                                 
                                    
                                </div>                    
                               <div class="row">   
								<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('school'). '  '.$this->lang->line('category'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="school_category">
												<option value=''>--Select--</option>
                                                <option value="Urban" <?php if(isset($post) && $post['school_category'] == 'Urban'){ echo 'selected="selected"';} ?>>Urban</option>
                                                <option value="Semiurban" <?php if(isset($post) && $post['school_category'] == 'Semiurban'){ echo 'selected="selected"';} ?>>Semiurban</option>
												<option value="Rural" <?php if(isset($post) && $post['school_category'] == 'Rural'){ echo 'selected="selected"';} ?>>Rural</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('enable_frontend'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('education'). '  '.$this->lang->line('type'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="education_type">
												<option value=''>--Select--</option>
                                                <option value="Boys" <?php if(isset($post) && $post['education_type'] == 'Boys'){ echo 'selected="selected"';} ?>>Boys</option>
                                                  <option value="Girls" <?php if(isset($post) && $post['education_type'] == 'Girls'){ echo 'selected="selected"';} ?>>Girls</option>
												    <option value="Coeducation" <?php if(isset($post) && $post['education_type'] == 'Coeducation'){ echo 'selected="selected"';} ?>>Coeducation</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('education_type'); ?></div> 
                                        </div>
                                    </div>
									 <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="registration_date"><?php echo $this->lang->line('opening'); ?> <?php echo $this->lang->line('date'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12 registration_date"  name="registration_date"  id="add_registration_date" value="<?php echo isset($post['registration_date']) ?  $post['registration_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('registration'); ?> <?php echo $this->lang->line('date'); ?>" type="text" autocomplete="off"  data-date-end-date="0d">
                                            <div class="help-block"><?php echo form_error('registration_date'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"> School Sub Branch of :  </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="parent_school_id">
												<option value=''>--Select Parent School--</option>
                                                <?php foreach($schools as $obj){ 
                                                    $sSelected = isset($post) && $post['parent_school_id'] && $post['parent_school_id'] == $obj->id ? "selected" : "";
                                                    ?>
                                                    <option value="<?php echo $obj->id ?>" <?php echo  $sSelected ?> ><?php echo $obj->school_name ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('parent_school_id'); ?></div> 
                                        </div>
                                    </div>
									
                               </div>  
							   <div class="row">
							   <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('academic_year'). ' - '.$this->lang->line('session_start'); ?> <span class="required">*</span> </label>
											 <input type="text"  class="form-control col-md-7 col-xs-12"  name="session_start"  id="add_session_start" value="<?php echo isset($session_start) ? $session_start : ''; ?>"  placeholder="<?php echo $this->lang->line('session_start'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_start'); ?></div>
										</div>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('academic_year'). ' - '.$this->lang->line('session_end'); ?> <span class="required">*</span> </label>
											<input type="text"  class="form-control col-md-7 col-xs-12"  name="session_end"  id="add_session_end" value="<?php echo isset($session_end) ? $session_end : ''; ?>"  placeholder="<?php echo $this->lang->line('session_end'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_end'); ?></div>
										</div>
									</div>
							   <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('financial_year'). ' - '.$this->lang->line('session_start'); ?> <span class="required">*</span> </label>
											 <input type="text"  class="form-control col-md-7 col-xs-12"  name="financial_session_start"  id="add_financial_session_start" value="<?php echo isset($post['financial_session_start']) ? $post['financial_session_start'] : ''; ?>"  placeholder="<?php echo $this->lang->line('session_start'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_start'); ?></div>
										</div>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('financial_year'). ' - '.$this->lang->line('session_end'); ?> <span class="required">*</span> </label>
											<input type="text"  class="form-control col-md-7 col-xs-12"  name="financial_session_end"  id="add_financial_session_end" value="<?php echo isset($post['financial_session_end']) ? $post['financial_session_end'] : ''; ?>"  placeholder="<?php echo $this->lang->line('session_end'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_end'); ?></div>
										</div>
									</div>
							   </div>
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('setting_information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="currency"><?php echo $this->lang->line('currency'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="currency"  id="currency" value="<?php echo isset($post['currency']) ?  $post['currency'] : 'INR'; ?>" placeholder="<?php echo $this->lang->line('currency'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('currency'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="currency_symbol"><?php echo $this->lang->line('currency_symbol'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="currency_symbol"  id="currency_symbol" value="<?php echo isset($post['currency_symbol']) ?  $post['currency_symbol'] : '&#8377;'; ?>" placeholder="<?php echo $this->lang->line('currency_symbol'); ?> " required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('currency_symbol'); ?></div> 
                                        </div>
                                    </div> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('enable_frontend'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="enable_frontend" required="required">
                                                <option value="1" <?php if(isset($post) && $post['enable_frontend'] == '1'){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if(isset($post) && $post['enable_frontend'] == '0'){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('enable_frontend'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="final_result_type"><?php echo $this->lang->line('exam_final_result'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="final_result_type" required="required">
                                                <option value="0" <?php if(isset($post) && $post['final_result_type'] == '0'){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('avg_of_all_exam'); ?> </option>
                                                <option value="1" <?php if(isset($post) && $post['final_result_type'] == '1'){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('only_of_fianl_exam'); ?> </option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('final_result_type'); ?></div> 
                                        </div>
                                    </div>
                                </div>
                                
                                 <div class="row">                                    
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_lat"><?php echo $this->lang->line('school_lat'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_lat"  id="school_lat" value="<?php echo isset($post['school_lat']) ?  $post['school_lat'] : ''; ?>" placeholder="<?php echo $this->lang->line('school_lat'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_lat'); ?></div> 
                                        </div>
                                    </div>      
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_lng"><?php echo $this->lang->line('school_lng'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_lng"  id="school_lng" value="<?php echo isset($post['school_lng']) ?  $post['school_lng'] : ''; ?>" placeholder="<?php echo $this->lang->line('school_lng'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_lng'); ?></div> 
                                        </div>
                                    </div>      
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="map_api_key">
                                                <?php echo $this->lang->line('api_key'); ?> 
                                                [ <a target="_blank" href="https://developers.google.com/maps/documentation/embed/get-api-key">Get Api Key</a>]
                                            </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="map_api_key"  id="map_api_key" value="<?php echo isset($post['map_api_key']) ?  $post['map_api_key'] : ''; ?>" placeholder="<?php echo $this->lang->line('api_key'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('map_api_key'); ?></div> 
                                        </div>
										
                                    </div> 
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="language"><?php echo $this->lang->line('language'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="language" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($fields as $field){ ?>
                                                    <?php  if($field == 'id' || $field == 'label'){ continue; } ?>
                                                <option value="<?php echo $field; ?>" ><?php echo ucfirst($field); ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('language'); ?></div> 
                                        </div>
                                    </div>  
</div>
<div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="theme_name"><?php echo $this->lang->line('theme'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="theme_name" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($themes AS $obj){ 
												$selected='';
												if(isset($post['theme_name'])){
													if($post['theme_name'] == $obj->slug){
														$selected="selected='selected'";
													}
												}
												else{
													if($obj->slug == 'trinidad'){
														$selected="selected='selected'";
													}
												}
												?>
                                                <option style="color: #FFF;background-color: <?php echo $obj->color_code; ?>;" value="<?php echo $obj->slug; ?>" <?php print $selected; ?>><?php echo $obj->name; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('theme_name'); ?></div> 
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_online_admission"><?php echo $this->lang->line('online_admission'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="enable_online_admission" id="enable_online_admission" required="required">
                                                <option value="" >--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="0" <?php if(isset($post) && $post['enable_online_admission'] == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('no'); ?></option>
                                                <option value="1" <?php if(isset($post) && $post['enable_online_admission'] == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('yes'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('enable_online_admission'); ?></div> 
                                        </div>
                                    </div>		
 <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="zoom_api_key"><?php echo $this->lang->line('zoom_api_key'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="zoom_api_key"  id="zoom_api_key" value="<?php echo isset($post['zoom_api_key']) ?  $post['zoom_api_key'] : ''; ?>" placeholder="<?php echo $this->lang->line('zoom_api_key'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('zoom_api_key'); ?></div> 
                                        </div>
                                    </div>      
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="zoom_secret"><?php echo $this->lang->line('zoom_secret'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="zoom_secret"  id="zoom_secret" value="<?php echo isset($post['zoom_secret']) ?  $post['zoom_secret'] : ''; ?>" placeholder="<?php echo $this->lang->line('zoom_secret'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('zoom_secret'); ?></div> 
                                        </div>
                                    </div> 		
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                        <label for="name">Default Company <span class="required">*</span></label>
                                            <select autofocus="" id="add_category" name="category" class="form-control col-md-7 col-xs-12" required="required">
                                            <option value=""><?php echo $this->lang->line('all'); ?></option>
                                            <?php
                                            foreach ($voucher_category as $key=>$value) {
                                                ?>
                                                <option value="<?php echo $key ?>"<?php
                                                if (isset($_POST['category']) && $_POST['category'] == $key) {
                                                    echo "selected = selected";
                                                }
                                                ?>><?php echo $value; ?></option>

                                                <?php
                                            }
                                            ?>
                                            </select>    
                                            <div class="help-block"><?php echo form_error('category'); ?></div> 
 
                                        </div>
                                    </div> 			                          
                                </div>
                                                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('profile')." ".$this->lang->line('information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="affiliation"><?php echo $this->lang->line('affiliation'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="affiliation"  id="add_affiliation" value="<?php echo isset($post['affiliation']) ?  $post['affiliation'] : ''; ?>" placeholder="<?php echo $this->lang->line('affiliation'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('affiliation'); ?></div> 
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="disc_code"><?php echo $this->lang->line('disc_code'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="disc_code"  id="add_disc_code" value="<?php echo isset($post['disc_code']) ?  $post['disc_code'] : ''; ?>" placeholder="<?php echo $this->lang->line('disc_code'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('disc_code'); ?></div> 
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="society_name"><?php echo $this->lang->line('society_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="society_name"  id="society_name" value="<?php echo isset($post['society_name']) ?  $post['society_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('society_name'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('society_name'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="society_pan_no"><?php echo $this->lang->line('society_pan_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="society_pan_no"  id="add_society_pan_no" value="<?php echo isset($post['society_pan_no']) ?  $post['society_pan_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('society_pan_no'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('society_pan_no'); ?></div> 
                                        </div>
                                    </div>
                                    </div>
								<div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="80g_registration_no"><?php echo $this->lang->line('80g_registration_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_80g_registration_no"  id="add_80g_registration_no" value="<?php echo isset($post['school_80g_registration_no']) ?  $post['school_80g_registration_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('80g_registration_no'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_80g_registration_no'); ?></div> 
                                        </div>
                                    </div>   
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="remote_id"><?php echo $this->lang->line('remote_id'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="remote_id"  id="add_remote_id" value="<?php echo isset($post['remote_id']) ?  $post['remote_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('remote_id'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('remote_id'); ?></div> 
                                        </div>
                                    </div>	
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="skype_id"><?php echo $this->lang->line('skype_id'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="skype_id"  id="add_skype_id" value="<?php echo isset($post['skype_id']) ?  $post['skype_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('skype_id'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('skype_id'); ?></div> 
                                        </div>
                                    </div>	
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="building_type"><?php echo $this->lang->line('building_type'); ?> </label>
											
                                            <select  class="form-control col-md-7 col-xs-12"  name="building_type"  id="add_building_type">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $building_types = get_building_types(); ?>
                                                <?php foreach($building_types as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php echo isset($post['building_type']) && $post['building_type'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('building_type'); ?></div> 
                                        </div>
                                    </div>										
                                </div>
								<div class="row">
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
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('laboratory')." ".$this->lang->line('facilities'); ?> </label>
										<?php $facilities = get_laboratory_facilities(); ?>
                                                <?php foreach($facilities as $key=>$value){ ?><span>
													<input type="checkbox" name="laboratory_facilities[]" value="<?php print $key; ?>"  <?php if(isset($post['laboratory_facilities']) && in_array($_POST['laboratory_facilities'],$key)){ echo 'checked="checked"'; } ?>> <?php echo $value; ?></span>                                                   
                                                <?php } ?>                                    
                                        <div class="help-block"><?php echo form_error('facilities'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('school_level'); ?> </label>
										<?php $level = get_school_level(); ?>
                                                <?php foreach($level as $key=>$value){ ?><span>
													<input type="checkbox" name="school_levels[]" value="<?php print $key; ?>"  <?php if(isset($post['school_levels']) && in_array($_POST['school_levels'],$key)){ echo 'checked="checked"'; } ?>> <?php echo $value; ?></span>                                                   
                                                <?php } ?>                                    
                                        <div class="help-block"><?php echo form_error('school_levels'); ?></div>
                                     </div>
                                 </div>
								</div>
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('other_information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="logo"><?php echo $this->lang->line('admin_logo'); ?>  </label>
                                            <div class="btn btn-default btn-file"><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="logo" id="logo"  type="file">
                                            </div>
                                            <div class="help-block"><?php echo form_error('logo'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="logo"><?php echo $this->lang->line('frontend_logo'); ?>   </label>
                                            <div class="btn btn-default btn-file"><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="frontend_logo" id="frontend_logo"  type="file">
                                            </div>
                                            <div class="help-block"><?php echo form_error('frontend_logo'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="logo"><?php echo $this->lang->line('data'); ?>   </label>
                                            <select name="data" class="form-control col-md-7 col-xs-12">
												<option value='Default'>Default</option>
												<option value='Import'>Import</option>
											</select>
                                            <div class="help-block"><?php echo form_error('frontend_logo'); ?></div> 
                                        </div>
                                    </div>
                                    
                               </div>   

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('administrator/school/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in  <?php if(isset($edit)){ echo 'active'; }?>" id="tab_edit_school">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('administrator/school/edit/'.$school->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'school_frm form-horizontal form-label-left'), ''); ?>
                               
                               
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('basic_information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                <div class="row">
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('state'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 fn_state_id" id="edit_state_id"  name="state_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($states AS $obj){ ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($school) && $school->state_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div> 
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('zone'); ?> </label>
                                            <select autofocus="" id="edit_zone_id" name="zone_id" class="form-control fn_zone_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('zone'); ?></div> 
                                        </div>
                                    </div> 	
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('subzone'); ?> </label>
                                            <select autofocus="" id="edit_subzone_id" name="subzone_id" class="form-control fn_subzone_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('subzone'); ?></div> 
                                        </div>
                                    </div> 										
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('district'); ?> </label>
                                            <select autofocus="" id="edit_district_id" name="district_id" class="form-control fn_district_id" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('district'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('block'); ?> </label>
                                            <select autofocus="" id="edit_block_id" name="block_id" class="form-control fn_block_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('block'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('sankul'); ?></label>
                                            <select autofocus="" id="edit_sankul_id" name="sankul_id" class="form-control fn_sankul_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('sankul'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"> School Type :  </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="school_type">
												<option value=''>--Select School Type--</option>
                                                    <option value="1" <?php echo isset($school->school_type) && $school->school_type == '1' ? "selected" : ''; ?>>School</option>
                                                    <option value="2" <?php echo isset($school->school_type) && $school->school_type == '2' ? "selected" : ''; ?>>Sanskar Kendra</option>
                                                    <option value="3" <?php echo isset($school->school_type) && $school->school_type == '3' ? "selected" : ''; ?>>Ekal Vidhyalya</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('school_type'); ?></div> 
                                        </div>
                                    </div>
								</div>
                                <div class="row">
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_code"><?php echo $this->lang->line('school_code'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_code"  id="school_code" value="<?php echo isset($school) ? $school->school_code : ''; ?>" placeholder="<?php echo $this->lang->line('school_code'); ?> "  type="text" autocomplete="off" <?php echo $superadminonly ?>>
                                            <div class="help-block"><?php echo form_error('school_code'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_name"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_name"  id="school_name" value="<?php echo isset($school) ? $school->school_name : ''; ?>" placeholder="<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_name'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="address"><?php echo $this->lang->line('address'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="address"  id="address" value="<?php echo isset($school) ? $school->address : ''; ?>" placeholder="<?php echo $this->lang->line('address'); ?> " required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('address'); ?></div> 
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="phone"><?php echo $this->lang->line('pincode'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="pincode"  id="edit_pincode" value="<?php echo isset($school) ? $school->pincode : ''; ?>" placeholder="<?php echo $this->lang->line('pincode'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pincode'); ?></div> 
                                        </div>
                                    </div>
                                   </div>
								   <div class="row">
                                    
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="phone" value="<?php echo isset($school) ? $school->phone : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('phone'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="email"><?php echo $this->lang->line('email'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"  id="email" value="<?php echo isset($school) ? $school->email : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?> " required="required" type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_fax"><?php echo $this->lang->line('school_fax'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_fax"  id="school_fax" value="<?php echo isset($school) ? $school->school_fax : ''; ?>" placeholder="<?php echo $this->lang->line('school_fax'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_fax'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="footer"><?php echo $this->lang->line('footer'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="footer"  id="footer" value="<?php echo isset($school) ? $school->footer : ''; ?>" placeholder="<?php echo $this->lang->line('footer'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('footer'); ?></div> 
                                        </div>
                                    </div>
                                    
                                                                    
                                   
                                </div>   
                                 <div class="row">   
								<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('school'). '  '.$this->lang->line('category'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="school_category">
												<option value=''>--Select--</option>
                                                <option value="Urban" <?php if($school->school_category == 'Urban'){ echo 'selected="selected"';} ?>>Urban</option>
                                                <option value="Semiurban" <?php if($school->school_category == 'Semiurban'){ echo 'selected="selected"';} ?>>Semiurban</option>
												<option value="Rural" <?php if($school->school_category == 'Rural'){ echo 'selected="selected"';} ?>>Rural</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('school_category'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('education'). '  '.$this->lang->line('type'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="education_type">
												<option value=''>--Select--</option>
                                                <option value="Boys" <?php if(isset($school) && $school->education_type == 'Boys'){ echo 'selected="selected"';} ?>>Boys</option>
                                                  <option value="Girls" <?php if(isset($school) && $school->education_type == 'Girls'){ echo 'selected="selected"';} ?>>Girls</option>
												    <option value="Coeducation" <?php if(isset($school) && $school->education_type == 'Coeducation'){ echo 'selected="selected"';} ?>>Coeducation</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('education_type'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="registration_date"><?php echo $this->lang->line('opening'); ?> <?php echo $this->lang->line('date'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="registration_date"  id="edit_registration_date" value="<?php echo isset($school) ? $school->registration_date : ''; ?>" placeholder="<?php echo $this->lang->line('registration'); ?> <?php echo $this->lang->line('date'); ?> " type="text" autocomplete="off" data-date-end-date="0d">
                                            <div class="help-block"><?php echo form_error('registration_date'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"> School Sub Branch of :  </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="parent_school_id">
												<option value=''>--Select Parent School--</option>
                                                <?php foreach($schools as $obj){ 
                                                    $sSelected = isset($school) && $school->parent_school_id && $school->parent_school_id  == $obj->id ? "selected" : "";
                                                    ?>
                                                    <option value="<?php echo $obj->id ?>" <?php echo  $sSelected ?> ><?php echo $obj->school_name ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('parent_school_id'); ?></div> 
                                        </div>
                                    </div>
                               </div> 
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('setting_information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="currency"><?php echo $this->lang->line('currency'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="currency"  id="currency" value="<?php echo isset($school) ? $school->currency : ''; ?>" placeholder="<?php echo $this->lang->line('currency'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('currency'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="currency_symbol"><?php echo $this->lang->line('currency_symbol'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="currency_symbol"  id="currency_symbol" value="<?php echo isset($school) ? $school->currency_symbol : ''; ?>" placeholder="<?php echo $this->lang->line('currency_symbol'); ?> " required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('currency_symbol'); ?></div> 
                                        </div>
                                    </div>
                                                                       
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_frontend"><?php echo $this->lang->line('enable_frontend'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="enable_frontend" required="required">
                                                <option value="1" <?php if(isset($school) && $school->enable_frontend == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if(isset($school) && $school->enable_frontend == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('enable_frontend'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="final_result_type"><?php echo $this->lang->line('exam_final_result'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="final_result_type" required="required">
                                                <option value="0" <?php if(isset($school) && $school->final_result_type == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('avg_of_all_exam'); ?> </option>
                                                <option value="1" <?php if(isset($school) && $school->final_result_type == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('only_of_fianl_exam'); ?> </option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('final_result_type'); ?></div> 
                                        </div>
                                    </div>
                               </div>
							   <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_lat"><?php echo $this->lang->line('school_lat'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_lat"  id="school_lat" value="<?php echo isset($school) ? $school->school_lat : ''; ?>" placeholder="<?php echo $this->lang->line('school_lat'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_lat'); ?></div> 
                                        </div>
                                    </div>    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="school_lng"><?php echo $this->lang->line('school_lng'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_lng"  id="school_lng" value="<?php echo isset($school) ? $school->school_lng : ''; ?>" placeholder="<?php echo $this->lang->line('school_lng'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_lng'); ?></div> 
                                        </div>
                                    </div>    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="map_api_key">
                                                <?php echo $this->lang->line('api_key'); ?> 
                                                [ <a target="_blank" href="https://developers.google.com/maps/documentation/embed/get-api-key">Get Api Key</a>]
                                            </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="map_api_key"  id="map_api_key" value="<?php echo isset($school) ? $school->map_api_key : ''; ?>" placeholder="<?php echo $this->lang->line('api_key'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('map_api_key'); ?></div> 
                                        </div>
                                    </div>  
									
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="language"><?php echo $this->lang->line('language'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="language" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($fields as $field){ ?>
                                                    <?php  if($field == 'id' || $field == 'label'){ continue; } ?>
                                                <option value="<?php echo $field; ?>" <?php if(isset($school) && $school->language == $field){ echo 'selected="selected"';} ?>><?php echo ucfirst($field); ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('language'); ?></div> 
                                        </div>
                                    </div>
								</div>
								<div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="theme_name"><?php echo $this->lang->line('theme'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="theme_name">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($themes AS $obj){ ?>
                                                    <option style="color: #FFF;background-color: <?php echo $obj->color_code; ?>;" value="<?php echo $obj->slug; ?>" <?php if(isset($school) && $school->theme_name == $obj->slug){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('theme_name'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_online_admission"><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('admission'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="enable_online_admission" id="enable_online_admission" required="required">
                                                <option value="0" <?php if(isset($school) && $school->enable_online_admission == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('no'); ?></option>
                                                <option value="1" <?php if(isset($school) && $school->enable_online_admission == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('yes'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('enable_online_admission'); ?></div> 
                                        </div>
                                    </div>
									  <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="zoom_api_key"><?php echo $this->lang->line('zoom_api_key'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="zoom_api_key"  id="zoom_api_key" value="<?php echo isset($school) ? $school->zoom_api_key : ''; ?>" placeholder="<?php echo $this->lang->line('zoom_api_key'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('zoom_api_key'); ?></div> 
                                        </div>
                                    </div>    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="zoom_secret"><?php echo $this->lang->line('zoom_secret'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="zoom_secret"  id="zoom_secret" value="<?php echo isset($school) ? $school->zoom_secret : ''; ?>" placeholder="<?php echo $this->lang->line('zoom_secret'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('zoom_secret'); ?></div> 
                                        </div>
                                    </div> 
								</div>
								<div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_online_admission">Default Voucher for Salary Payment </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="default_voucher_Id_for_salary_payment" id="default_voucher_Id_for_salary_payment">
											 <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($vouchers AS $obj){ ?>
                                                 <option value="<?php print $obj->id; ?>" <?php if(isset($school) && $school->default_voucher_Id_for_salary_payment == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name."(".$obj->category.")"; ?></option>
												<?php } ?>                                            
                                            </select>
                                            <div class="help-block"><?php echo form_error('default_voucher_Id_for_salary_payment'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="enable_online_admission">Default Voucher for Inventory </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="default_voucher_Id_for_inventory" id="default_voucher_Id_for_inventory" >
											 <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($vouchers AS $obj){ ?>
                                                 <option value="<?php print $obj->id; ?>" <?php if(isset($school) && $school->default_voucher_Id_for_inventory == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name."(".$obj->category.")"; ?></option>
												<?php } ?>                                            
                                            </select>
                                            <div class="help-block"><?php echo form_error('default_voucher_Id_for_inventory'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                        <label  for="name">Default Company <span class="required">*</span></label>
                                            <select autofocus="" id="add_category" name="category" class="form-control col-md-7 col-xs-12" required="required">
                                            <option value=""><?php echo $this->lang->line('all'); ?></option>
                                            <?php
                                            foreach ($voucher_category as $key=>$value) {
                                                ?>
                                                <option value="<?php echo $key ?>"<?php
                                               if(isset($school) && $school->category == $key ) {
                                                    echo "selected = selected";
                                                }
                                                ?>><?php echo $value; ?></option>

                                                <?php
                                            }
                                            ?>
                                            </select>    
                                            <div class="help-block"><?php echo form_error('category'); ?></div> 
 
                                        </div>
                                    </div> 		
                                </div>
                                                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('profile')." ".$this->lang->line('information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="affiliation"><?php echo $this->lang->line('affiliation'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="affiliation"  id="edit_affiliation" value="<?php echo isset($school) ? $school->affiliation : ''; ?>" placeholder="<?php echo $this->lang->line('affiliation'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('affiliation'); ?></div> 
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="disc_code"><?php echo $this->lang->line('disc_code'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="disc_code"  id="edit_disc_code" value="<?php echo isset($school) ? $school->disc_code : ''; ?>" placeholder="<?php echo $this->lang->line('disc_code'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('disc_code'); ?></div> 
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="society_name"><?php echo $this->lang->line('society_name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="society_name"  id="edit_society_name" value="<?php echo isset($school) ? $school->society_name : ''; ?>" placeholder="<?php echo $this->lang->line('society_name'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('society_name'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="society_pan_no"><?php echo $this->lang->line('society_pan_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="society_pan_no"  id="edit_society_pan_no" value="<?php echo isset($school) ? $school->society_pan_no : ''; ?>" placeholder="<?php echo $this->lang->line('society_pan_no'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('society_pan_no'); ?></div> 
                                        </div>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="80g_registration_no"><?php echo $this->lang->line('80g_registration_no'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="school_80g_registration_no"  id="edit_80g_registration_no" value="<?php echo isset($school) ? $school->school_80g_registration_no : ''; ?>" placeholder="<?php echo $this->lang->line('80g_registration_no'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('school_80g_registration_no'); ?></div> 
                                        </div>
                                    </div> 
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="society_pan_no"><?php echo $this->lang->line('remote_id'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="remote_id"  id="edit_remote_id" value="<?php echo isset($school) ? $school->remote_id : ''; ?>" placeholder="<?php echo $this->lang->line('remote_id'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('remote_id'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="skype_id"><?php echo $this->lang->line('skype_id'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="skype_id"  id="edit_skype_id" value="<?php echo isset($school) ? $school->skype_id : ''; ?>" placeholder="<?php echo $this->lang->line('skype_id'); ?> " type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('skype_id'); ?></div> 
                                        </div>
                                    </div>	
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="building_type"><?php echo $this->lang->line('building_type'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="building_type"  id="edit_building_type">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $building_types = get_building_types(); ?>
                                                <?php foreach($building_types as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if($school->building_type == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('building_type'); ?></div> 
                                        </div>
                                    </div>										
                                </div>
<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('facilities'); ?> </label>
										<?php 
										$farr=explode(",",$school->facilities);
										$facilities = get_student_facilities(); ?>
                                                <?php foreach($facilities as $key=>$value){ ?><span>
													<input type="checkbox" name="facilities[]" value="<?php print $key; ?>"  <?php if(in_array($key,$farr)){ echo 'checked="checked"'; } ?>> <?php echo $value; ?></span>                                                   
                                                <?php } ?>                                    
                                        <div class="help-block"><?php echo form_error('facilities'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('laboratory')." ".$this->lang->line('facilities'); ?> </label>
										<?php 
										$farr=explode(",",$school->laboratory_facilities);
										$facilities = get_laboratory_facilities(); ?>
                                                <?php foreach($facilities as $key=>$value){ ?><span>
													<input type="checkbox" name="laboratory_facilities[]" value="<?php print $key; ?>"  <?php if(isset($school->laboratory_facilities) && in_array($key,$farr)){ echo 'checked="checked"'; } ?>> <?php echo $value; ?></span>                                                   
                                                <?php } ?>                                    
                                        <div class="help-block"><?php echo form_error('laboratory_facilities'); ?></div>
                                     </div>
                                 </div>
								 <div class="col-md-3 col-sm-3 col-xs-12">
                                     <div class="item form-group">
                                        <label for="health_condition"><?php echo $this->lang->line('school_level'); ?> </label>
										<?php 
										$farr=explode(",",$school->school_levels);
										$level = get_school_level(); ?>
                                                <?php foreach($level as $key=>$value){ ?><span>
													<input type="checkbox" name="school_levels[]" value="<?php print $key; ?>"  <?php if(isset($school->school_levels) && in_array($key,$farr)){ echo 'checked="checked"'; } ?>> <?php echo $value; ?></span>                                                   
                                                <?php } ?>                                    
                                        <div class="help-block"><?php echo form_error('school_levels'); ?></div>
                                     </div>
                                 </div>
								</div>
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('other_information'); ?> :</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="logo"><?php echo $this->lang->line('frontend_logo'); ?> <?php echo $this->lang->line('logo'); ?> </label>
                                            <div class="btn btn-default btn-file"><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="frontend_logo" id="frontend_logo"  type="file">
                                            </div>
                                            <div class="help-block"><?php echo form_error('frontend_logo'); ?></div> 
                                            <?php if($school->frontend_logo){ ?>
                                                 <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="80" style="background: #34347a; padding: 5px;"/><br/><br/>
                                                 <input name="frontend_logo_prev" value="<?php echo isset($school) ? $school->frontend_logo : ''; ?>"  type="hidden">
                                            <?php } ?>
                                        </div>                                       
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="logo"><?php echo $this->lang->line('admin_logo'); ?> <?php echo $this->lang->line('logo'); ?> </label>
                                            <div class="btn btn-default btn-file"><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="logo" id="logo"  type="file">
                                            </div>
                                            <div class="help-block"><?php echo form_error('logo'); ?></div> 
                                            <?php if($school->logo){ ?>
                                                 <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="80" style="background: #34347a; padding: 5px;"/><br/><br/>
                                                 <input name="logo_prev" value="<?php echo isset($school) ? $school->logo : ''; ?>"  type="hidden">
                                            <?php } ?>
                                        </div>                                       
                                    </div>
                                                                        
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="status"><?php echo $this->lang->line('status'); ?></label>
                                            <select  class="form-control col-md-7 col-xs-12"  name="status" >
                                                <option value="1" <?php if(isset($school) && $school->status == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('active'); ?></option>
                                                <option value="0" <?php if(isset($school) && $school->status == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('in_active'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('status'); ?></div> 
                                        </div>
                                    </div>
                                    
                                    
                                </div>                                 

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($school) ? $school->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('administrator/school/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('update'); ?></button>
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


<div class="modal fade bs-school-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_school_data">            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_school_modal(school_id){
         
        $('.fn_school_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loader.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('administrator/school/get_single_school'); ?>",
          data   : {school_id : school_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_school_data').html(response);
             }
          }
       });
    }
	function printPageArea(school_id){
	//$('.fn_school_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loader.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('administrator/school/get_single_school_print'); ?>",
          data   : {school_id : school_id},  
          success: function(response){                                                   
             if(response)
             {
                //$('.fn_school_data').html(response);
				var printContent = response;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
             }
          }
       });
	
    
}
</script>
  <script type="text/javascript">   
var edit = false;
    $(document).ready(function() {
		 <?php if(isset($edit) && !empty($edit)){ ?>	
edit=true;		 
           $("#edit_state_id").trigger('change');         
         <?php } ?>	
	});	
$('.fn_state_id').on('change', function(){
      
        var state_id = $(this).val();
		var zone_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
            zone_id =  '<?php echo $school->zone_id; ?>'; 
         <?php } ?> 		
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_zone_by_state'); ?>",
            data   : { state_id:state_id, zone_id:zone_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_zone_id').html(response);   
					   $('#edit_zone_id').trigger('change');
                   }else{
                       $('#add_zone_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});	
	$('.fn_zone_id').on('change', function(){
      
        var zone_id = $(this).val();
		var subzone_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
			subzone_id =  '<?php echo $school->subzone_id; ?>';                       
         <?php } ?> 		
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subzone_by_zone'); ?>",
            data   : { zone_id:zone_id, subzone_id:subzone_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_subzone_id').html(response);   
					   $('#edit_subzone_id').trigger('change');
                   }else{
                       $('#add_subzone_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});
	$('.fn_subzone_id').on('change', function(){
      
        var subzone_id = $(this).val();
		var district_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
		district_id =  '<?php echo $school->district_id; ?>';                       
         <?php } ?> 		
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_district_by_subzone'); ?>",
            data   : { subzone_id:subzone_id, district_id:district_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_district_id').html(response);  
$('#edit_district_id').trigger('change');					   					   
                   }else{
                       $('#add_district_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});
	$('.fn_district_id').on('change', function(){
      
        var district_id = $(this).val();
		var block_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
            block_id =  '<?php echo $school->block_id; ?>';                     
         <?php } ?> 		
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_block_by_district'); ?>",
            data   : { district_id:district_id, block_id:block_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_block_id').html(response); 
						$('#edit_block_id').trigger('change');					   					   
                   }else{
                       $('#add_block_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});
		$('.fn_block_id').on('change', function(){
      
        var block_id = $(this).val();
		var sankul_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
            sankul_id =  '<?php echo $school->sankul_id; ?>';           
         <?php } ?> 		
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_sankul_by_block'); ?>",
            data   : { block_id:block_id, sankul_id:sankul_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_sankul_id').html(response);   
                   }else{
                       $('#add_sankul_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});
</script>


<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 <script type="text/javascript">   
    
     
  $('#add_session_start').datepicker({
      viewMode: 'years',
       startView: 'year', 
      format: 'dd MM yyyy'
  }); 
  
  $('#add_session_end').datepicker({
      viewMode: 'years',
       startView: 'year', 
      format: 'dd MM yyyy'
  });
      
  $('#add_financial_session_start').datepicker({}); 
  
  $('#add_financial_session_end').datepicker({  });
  $('#add_registration_date').datepicker();
  $('#edit_registration_date').datepicker();

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
	$('#add_sankul_id').on('change', function(){
		var sankul_id = $(this).val();        		
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('administrator/school/generate_school_code'); ?>",
            data   : { sankul_id:sankul_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                    
                    $('#add_school_code').val(response);   
                  
               }
            }
        });
	});
</script>
