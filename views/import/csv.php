<?php $voucher_category = getVoucherCategory();?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-users"></i><small> <?php echo $this->lang->line('import'). " ".$this->lang->line('csv'); ?></small></h3>
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
                            <li  class="active"><a href="#tab_import"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('import'); ?> <?php echo $this->lang->line('csv'); ?></a> </li>                                                  
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                       
                        <div  class="tab-pane fade in active" id="tab_import">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('import/csv'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                       
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
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="school_id"><?php echo $this->lang->line('type'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12 " name="type" id="type" required="required" onchange='changeType(this.value)'>
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
												<!--	<option value='financial_year'>Financial Year </option> -->
                                                <!--    <option value='academic_year'>Academic Year</option>
													<option value='academic_discipline'>Academic Discipline</option>
													<option value='academic_standard'>Academic Standard</option>
													<option value='academic_subjects'>Academic Subjects</option>
													<option value='class'>Academic Class</option>
													<option value='account_groups'>Account Groups</option>
													<option value='account_ledgers'>Account Ledgers</option>
													<option value='account_vouchers'>Account Vouchers</option>
													<option value='ledger_entries'>Ledger Entries</option> -->
												<!--	<option value='fee_type'>Fee Type</option>
													<option value='pay_groups'>Payroll Pay Groups</option>
													<option value='payscale_categories'>Payroll Payscale Categories</option>
													<option value='payment_modes'>Payment Modes</option>
													<option value='employment_type'>Employment Type</option>-->
													<!-- <option value='student'>Student</option> -->
                                                    <option value='student_new'>Student New</option>
                                                    <option value='final_result'>Final Result</option>

												<!--	<option value='student'>Alumni Student</option> -->
												<!--	<option value='teacher'>Teacher</option>  -->
													<!-- <option value='alumni_teacher'>Alumni Teacher</option> -->
													<!-- <option value='employee'>Employee</option> -->
													<!--<option value='alumni_employee'>Alumni Employee</option>-->
													
													
                                             </select>
                                            <div class="help-block"><?php echo form_error('type'); ?></div>
                                         </div>
                                     </div>
									  <div class="col-md-3 col-sm-3 col-xs-12" id='voucher_category' style='display:none;'>
                                         <div class="item form-group">
                                             <label for="school_id"><?php echo $this->lang->line('category'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12 " name="voucher_category" id="voucher_cat">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
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
                                            <div class="help-block"><?php echo form_error('type'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                         <div class="item form-group">
                                             <label ><?php echo $this->lang->line('csv_file'); ?>&nbsp;</label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="bulk_data"  id="bulk_data" type="file">
                                            </div>
                                         </div>
                                     </div>
                                </div>
                                
                                                            
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a  href="<?php echo site_url('import/csv'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                 <div class="col-md-12 col-sm-12 col-xs-12">
									
                                    <div class="instructions">
									<!-- <div>Download sample file : <a target='_blank' href='<?php echo site_url('assets/files/student.csv'); ?>'>Student</a> |  -->
                                    <a target='_blank' href='<?php echo site_url('assets/files/student_new.csv'); ?>'>Student New</a> 
                                   |  <a target='_blank' href='<?php echo site_url('assets/files/import_result.csv'); ?>'>Final Result</a> 
									<!-- <a target='_blank' href='<?php echo site_url('assets/files/employee.csv'); ?>'>Employee</a> -->
									</div>
									<div>
									<strong><?php echo $this->lang->line('instruction'); ?>: </strong> 
                                        
                                            <div>Please use below link to download file:</div>
											<ol>
                                            <li><a href="http://rajpsp.nic.in/PSP2/School/PSP_RptStudentEntry.aspx">http://rajpsp.nic.in/PSP2/School/PSP_RptStudentEntry.aspx</a></li>
											<li><a href="http://rajpsp.nic.in/PSP2/School/PSP_SchoolStaffEntry.aspx">http://rajpsp.nic.in/PSP2/School/PSP_SchoolStaffEntry.aspx<a></li>                                            
											
                                        </ol>
                                    </div>
									</div>
                                </div>                               
                                
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
		function changeType($type){
			if($type=='account_vouchers'){
				$("#voucher_category").show();
			}
			else{
				$("#voucher_category").hide();
			}
		}
</script>