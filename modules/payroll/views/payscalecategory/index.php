<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-dollar"></i><small> <?php echo $this->lang->line('manage_salary_grade'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
           
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_grade_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('salary_grade'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'payroll', 'grade')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('payroll/payscalecategory/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('salary_grade'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_grade"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('salary_grade'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_grade"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('salary_grade'); ?></a> </li>                          
                        <?php } ?>
                        
                        <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_grade_by_school(this.value);">
                                    <option value="<?php echo site_url('payroll/payscalecategory/index/'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('payroll/payscalecategory/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                        </li>       
                            
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_grade_list" >
                            <div class="x_content">
                                <?php if( $filter_school_id && count($grades) == 0 && has_permission(ADD, 'payroll', 'grade')) {?>
                                <a class="btn btn-success btn-sm" href="<?php echo site_url('payroll/payscalecategory/insert_defualt/'.$filter_school_id); ?>">Generate Default</a>
                                <?php } ?>
                                <?php if( $filter_school_id && has_permission(ADD, 'payroll', 'grade')) {?>
                                    <a class="btn btn-success btn-sm" href="<?php echo site_url('payroll/payscalecategory/fix_defualt/'.$filter_school_id); ?>">Fix Default</a>
                                    <?php } ?>
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('type'); ?></th>
										<th><?php echo $this->lang->line('amount'); ?></th>
										<th><?php echo $this->lang->line('percentage'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($grades) && !empty($grades)){ ?>
                                        <?php foreach($grades as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                                <td><?php echo $obj->school_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo $obj->name; ?></td>
											<td><?php echo $obj->category_type; ?></td>
											<td><?php echo $obj->amount; ?></td>
											<td><?php echo $obj->percentage; ?></td>
                                            
                                            <td>
                                                <?php if(has_permission(EDIT, 'payroll', 'payscalecategory')){ ?>
                                                    <a href="<?php echo site_url('payroll/payscalecategory/edit/'.$obj->id); ?>" class="btn btn-success btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(VIEW, 'payroll', 'payscalecategory')){ ?>
                                                    <a  onclick="get_grade_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-grade-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'payroll', 'payscalecategory')){ ?>
                                                    <a href="<?php echo site_url('payroll/payscalecategory/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_grade">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('payroll/payscalecategory/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                    <?php $this->load->view('layout/school_list_form'); ?>
							
                                    <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('debit_ledger'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">                                                 
                                        <select  class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="add_debit_ledger_id" disabled="disabled">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>									
									</div>									
									</div>
                                    <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('credit_ledger'); ?> <span class="required">*</span>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">                                    
                                        <select  class="form-control col-md-7 col-xs-12 " name="credit_ledger_id" id="add_credit_ledger_id" disabled="disabled">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									

								</div>
								</div>									                                    
                                        <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">   
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="add_grade_name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div>
                                        </div>
                                    </div>	
 <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('pay_group')." ".$this->lang->line('code'); ?> <span class="required">*</span>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">                                    
                                        <select  class="form-control col-md-7 col-xs-12" name="pay_group_id" id="add_pay_group_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									
								</div>
								</div>	
 <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">PayScale Category Type <span class="required">*</span>
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 									
                                        <select  class="form-control col-md-7 col-xs-12" name="category_type" id="add_category_type" required='required' onchange="changeCategory(this.value);">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<option value="FixedPay">FixedPay</option>
											<option value='DependsOnGPandPS'>Depends On Grade Pay and other such Pay Scale Categories</option>
											<option value='DependsOnGP'>Depends On Particular Pay Scale Categories</option>
										</select>
									
								</div>
								</div>   								
                                        <div class="item form-group amount">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('amount'); ?> 
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class="form-control col-md-7 col-xs-12"  name="amount"  id="add_amount" value="<?php echo isset($post['amount']) ?  $post['amount'] : ''; ?>" placeholder="<?php echo $this->lang->line('amount'); ?>" type="number" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
                                        </div>
                                    </div>	
 <div class="item form-group unbound_payscale_category" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Unbound Pay Scale Category
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class=""  name="unbound_payscale_category"  id="add_unbound_payscale_category" value="1" <?php echo isset($post['unbound_payscale_category']) ?  "checked='checked'" : ''; ?> type="checkbox" autocomplete="off" onchange="unboundPayScale('add_unbound_payscale_category');">
                                            <div class="help-block"><?php echo form_error('unbound_payscale_category'); ?></div>
                                        </div>
                                    </div>		
<div class="item form-group otherPayScaleCategory" style="display:none;">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">% of PayScale Category <span class="required">*</span>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">                                    
                                        <select  class="form-control col-md-7 col-xs-12 dependant_payscale_categories" name="dependant_payscale_categories[]" id="add_paycale_category" multiple >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									
									</div>
								</div>									
                                        <div class="item form-group percentage" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('percentage'); ?> 
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 
                                            <input  class="form-control col-md-7 col-xs-12"  name="percentage"  id="add_amount" value="<?php echo isset($post['percentage']) ?  $post['percentage'] : ''; ?>" placeholder="0" type="number" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('percentage'); ?></div>
                                        </div>
                                    </div>     
									<div class="item form-group set_max_amount_limit" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Is Set Maximum Amount Limit
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">  
                                            <input  class=""  name="set_max_amount_limit" id="add_set_max_amount_limit" value="1" <?php echo (isset($post['set_max_amount_limit']) && $post['set_max_amount_limit']=='1') ?  "checked='checked'" : ''; ?> type="checkbox" onchange='maxAmountLimit("add_set_max_amount_limit");'>
                                            <div class="help-block"><?php echo form_error('set_max_amount_limit'); ?></div>
                                        </div>
                                    </div>	
									 <div class="item form-group max_amount_possible" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Maximum Amount Possible <span class="required">*</span>
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class="form-control col-md-7 col-xs-12"  name="max_amount_possible"  id="add_max_amount_possible" value="<?php echo isset($post['max_amount_possible']) ?  $post['max_amount_possible'] : ''; ?>" placeholder="<?php echo $this->lang->line('amount'); ?>"type="number" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('max_amount_possible'); ?></div>
                                        </div>
                                    </div>	
                                <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Round of Method <span class="required">*</span>
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 									
                                        <select  class="form-control col-md-7 col-xs-12" name="round_of_method" id="add_round_of_method" required='required'>
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="exact_amount">Exact amount</option>
                                            <option value="round_up_plus">Round up(towards +1)</option>
											<option value="round_up">Round up(towards +)</option>
											<option value='round_half'>Round half(away from zero)</option>
										</select>
									
								</div>
								</div>
                              <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Is Deduction Type
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">  
                                            <input  class="deduction_type"  name="is_deduction_type" id="add_deduction_type" value="TRUE" <?php echo (isset($post['is_deduction_type']) && $post['is_deduction_type']=='TRUE') ?  "checked='checked'" : ''; ?>" type="checkbox">
                                            <div class="help-block"><?php echo form_error('is_deduction_type'); ?></div>
                                        </div>
                                    </div>		
                                <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Remove Dependency From Attendance ?
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class=""  name="remove_dependancy_from_attendance"  id="add_remove_dependancy_from_attendance" value="1" <?php echo isset($post['remove_dependancy_from_attendance']) ?  "checked='checked'" : ''; ?> type="checkbox" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('remove_dependancy_from_attendance'); ?></div>
                                        </div>
                                    </div>		
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('payroll/payscalecategory/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_grade">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('payroll/payscalecategory/edit/'.$grade->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                               
                                 
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
							
                                    <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('debit_ledger'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">                                                 
                                        <select  class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="edit_debit_ledger_id"  disabled="disabled">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>									
									</div>									
									</div>
                                    <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('credit_ledger'); ?>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">                                    
                                        <select  class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="edit_credit_ledger_id" <?php echo (isset($grade->is_deduction_type) && $grade->is_deduction_type=='TRUE') ?  "" : 'disabled="disabled"'; ?> >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									
								</div>
								</div>									                                    
                                        <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">   
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="edit_grade_name" value="<?php echo isset($grade) ?  $grade->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div>
                                        </div>
                                    </div>	
 <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('pay_group')." ".$this->lang->line('code'); ?> <span class="required">*</span>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">                                    
                                        <select  class="form-control col-md-7 col-xs-12" name="pay_group_id" id="edit_pay_group_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									
								</div>
								</div>	
 <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">PayScale Category Type <span class="required">*</span>
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 									
                                        <select  class="form-control col-md-7 col-xs-12" name="category_type" id="edit_category_type" required='required' onchange="changeCategory(this.value);">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<option value="FixedPay" <?php if(isset($grade) && $grade->category_type == 'FixedPay') print "selected='selected'"; ?>>FixedPay</option>
											<option value='DependsOnGPandPS' <?php if(isset($grade) && $grade->category_type == 'DependsOnGPandPS') print "selected='selected'"; ?>>Depends On Grade Pay and other such Pay Scale Categories</option>
											<option value='DependsOnGP' <?php if(isset($grade) && $grade->category_type == 'DependsOnGP') print "selected='selected'"; ?>>Depends On Particular Pay Scale Categories</option>
										</select>
									
								</div>
								</div>   								
                                        <div class="item form-group amount" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('amount'); ?> <span class="required">*</span>
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class="form-control col-md-7 col-xs-12"  name="amount"  id="edit_amount" value="<?php echo isset($grade->amount) ?  $grade->amount : ''; ?>" placeholder="<?php echo $this->lang->line('amount'); ?>" type="number" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
                                        </div>
                                    </div>	
 <div class="item form-group unbound_payscale_category" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Unbound Pay Scale Category
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class=""  name="unbound_payscale_category"  id="edit_unbound_payscale_category" value="1" <?php echo isset($grade->unbound_payscale_category)&& $grade->unbound_payscale_category==1 ?  "checked='checked'" : ''; ?> type="checkbox" autocomplete="off" onchange="unboundPayScale('edit_unbound_payscale_category');" >
                                            <div class="help-block"><?php echo form_error('unbound_payscale_category'); ?></div>
                                        </div>
                                    </div>		
<div class="item form-group otherPayScaleCategory" style="display:none;">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">% of PayScale Category <span class="required">*</span>
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">                                    
                                        <select  class="form-control col-md-7 col-xs-12 dependant_payscale_categories" name="dependant_payscale_categories[]" id="edit_paycale_category" multiple >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									
									</div>
								</div>									
                                        <div class="item form-group percentage" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('percentage'); ?> 
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 
                                            <input  class="form-control col-md-7 col-xs-12"  name="percentage"  id="edit_percentage" value="<?php echo isset($grade->percentage) ?  $grade->percentage : ''; ?>" placeholder="0" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('percentage'); ?></div>
                                        </div>
                                    </div>     
									<div class="item form-group set_max_amount_limit"  style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Is Set Maximum Amount Limit
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">  
                                            <input  class=""  name="set_max_amount_limit" id="edit_set_max_amount_limit" value="1" <?php echo (isset($grade->set_max_amount_limit) && $grade->set_max_amount_limit=='1') ?  "checked='checked'" : ''; ?> type="checkbox" onchange='maxAmountLimit("edit_set_max_amount_limit");'>
                                            <div class="help-block"><?php echo form_error('set_max_amount_limit'); ?></div>
                                        </div>
                                    </div>	
									 <div class="item form-group max_amount_possible" style="display:none;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Maximum Amount Possible <span class="required">*</span>
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class="form-control col-md-7 col-xs-12"  name="max_amount_possible"  id="edit_max_amount_possible" value="<?php echo isset($grade->max_amount_possible) ?  $grade->max_amount_possible : ''; ?>" placeholder="<?php echo $this->lang->line('amount'); ?>"type="number" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('max_amount_possible'); ?></div>
                                        </div>
                                    </div>	
                                <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Round of Method <span class="required">*</span>
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 									
                                        <select  class="form-control col-md-7 col-xs-12" name="round_of_method" id="edit_round_of_method" required='required'>
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="exact_amount" <?php if(isset($grade->round_of_method) && $grade->round_of_method == 'exact_amount') print "selected='selected'"; ?>>Exact amount</option>
                                            <option value="round_up_plus" <?php if(isset($grade->round_of_method) && $grade->round_of_method == 'round_up_plus') print "selected='selected'"; ?>>Round up(towards +1)</option>

                                            <option value="round_up" <?php if(isset($grade->round_of_method) && $grade->round_of_method == 'round_up') print "selected='selected'"; ?>>Round up(towards +)</option>
											<option value='round_half' <?php if(isset($grade->round_of_method) && $grade->round_of_method == 'round_half') print "selected='selected'"; ?>>Round half(away from zero)</option>
									
                                        </select>
									
								</div>
								</div>
                              <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Is Deduction Type
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">  
                                            <input  class="deduction_type"  name="is_deduction_type" id="edit_deduction_type" value="TRUE" <?php echo (isset($grade->is_deduction_type) && $grade->is_deduction_type=='TRUE') ?  "checked='checked'" : ''; ?> type="checkbox">
                                            <div class="help-block"><?php echo form_error('is_deduction_type'); ?></div>
                                        </div>
                                    </div>		
                                <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Remove Dependency From Attendance ?
                                    </label>
								<div class="col-md-6 col-sm-6 col-xs-12"> 	
                                            <input  class=""  name="remove_dependancy_from_attendance"  id="edit_remove_dependancy_from_attendance" value="1" <?php echo isset($grade->remove_dependancy_from_attendance)&& $grade->remove_dependancy_from_attendance ==1 ?  "checked='checked'" : ''; ?> type="checkbox" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('remove_dependancy_from_attendance'); ?></div>
                                        </div>
                                    </div>								
                              
                            
                                   
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($grade) ? $grade->id : $id; ?>" name="id" />
                                        <a  href="<?php echo site_url('payroll/payscalecategory/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


<div class="modal fade bs-grade-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('salary_grade'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_grade_data">
            
        </div>       
      </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <script type="text/javascript">
 $(document).on('change','.deduction_type', function(e){
        var that = e.target;
        var element='#add_credit_ledger_id';
		<?php if(isset($edit)){ ?>
			element="#edit_credit_ledger_id";
		<?php } ?>	
        if($(that).is(":checked"))
        {
            $(element).removeAttr('disabled')
            $(element).attr('required', 'required')
        }
        else
        {
            $(element).val(null).trigger("change");

            // $(element+" option").removeAttr("data-select2-id");
            // $(element+" option").removeAttr("selected");
            $(element).removeAttr('required')
            $(element).attr('disabled','disabled')
        }
 })
    $("document").ready(function() {		
         <?php if(isset($school_id) && $school_id>=0){ ?>		 
		 if($("#edit_school_id").length == 0) {			 
             $(".fn_school_id").trigger('change');			 
		 }
		 else{			 
			 $("#edit_school_id").trigger('change');	
		 }
				var category_type=		'<?php echo $grade->category_type; ?>';			
				changeCategory(category_type);
				maxAmountLimit("edit_set_max_amount_limit");
				unboundPayScale("edit_unbound_payscale_category",category_type);
         <?php } ?>		 
			$('.dependant_payscale_categories').select2();
    });

    $('.fn_school_id').on('change', function(){    

        var school_id = $(this).val();        
		var debit_ledger_id='';		
		var credit_ledger_id='';	
		var pay_group_id='';
        var is_deduction_type = '<?php echo (isset($post['is_deduction_type']) && $post['is_deduction_type']=='TRUE') ?  "true" : ""; ?>';
		var dependant_payscale_categories= '';
		var pid='';
        var edit= '<?php echo $edit; ?>';			
        <?php if(isset($school_id) && $school_id>=0){ ?> 
			pid='<?php echo $grade->id; ?>';			
			debit_ledger_id =  '<?php echo $grade->debit_ledger_id; ?>';			
			credit_ledger_id =  '<?php echo $grade->credit_ledger_id; ?>';			
			pay_group_id =  '<?php echo $grade->pay_group_id; ?>';
			dependant_payscale_categories=  '<?php echo $grade->dependant_payscale_categories; ?>';			
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }       
                   
		// $.ajax({       
        //     type   : "POST",
        //     url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
        //     data   : { school_id:school_id, ledger_id:debit_ledger_id},               
        //     async  : false,
        //     success: function(response){                                                   
        //        if(response)
        //        {  
        //            if(debit_ledger_id){
        //                //$('#edit_debit_ledger_id').html(response);   
        //            }else{
        //                $('#add_debit_ledger_id').html(response);   
        //            }                                    
        //        }
        //     }
        // });	
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data   : { school_id:school_id, ledger_id:credit_ledger_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_credit_ledger_id').html(response);   
                       if(!is_deduction_type)
                       {
                        ///$('#edit_credit_ledger_id').val(null).trigger("change");
                        //$('#edit_credit_ledger_id').val('');
                       }

                   }else{
                       $('#add_credit_ledger_id').html(response);   
                       
                   }                                    
               }
            }
        });	
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_pay_group_by_school'); ?>",
            data   : { school_id:school_id, pay_group_id:pay_group_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_pay_group_id').html(response);   
                   }else{
                       $('#add_pay_group_id').html(response);   
                   }                                    
               }
            }
        });		
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_payscale_categoriess_by_school'); ?>",
            data   : { school_id:school_id,pid:pid,dependant_payscale_categories:dependant_payscale_categories},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_paycale_category').html(response);   
                   }else{
                       $('#add_paycale_category').html(response);   
                   }                                    
               }
            }
        });			
		
    }); 
</script>

<script type="text/javascript">
         
    function get_grade_modal(grade_id){
         
        $('.fn_grade_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('payroll/payscalecategory/get_single_grade'); ?>",
          data   : {grade_id : grade_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_grade_data').html(response);
             }
          }
       });
    }
</script>


<!-- datatable with buttons -->
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
        
    $("#add").validate();     
    $("#edit").validate();   
    
    
    $('.fn_add_claculate').on('keyup', function(){
        
        var basic_salary = $('#add_basic_salary').val() ? parseFloat($('#add_basic_salary').val()) : 0;
        var house_rent = $('#add_house_rent').val() ? parseFloat($('#add_house_rent').val()) : 0;
        var transport = $('#add_transport').val() ? parseFloat($('#add_transport').val()): 0;
        var medical = $('#add_medical').val() ? parseFloat($('#add_medical').val()) : 0;
        var provident_fund = $('#add_provident_fund').val() ? parseFloat($('#add_provident_fund').val()) : 0;
        
       $('#add_total_allowance').val(house_rent+transport+medical);       
        var total_allowance = $('#add_total_allowance').val() ? parseFloat($('#add_total_allowance').val()) : 0;
        
        $('#add_total_deduction').val(provident_fund);
        var total_deduction = $('#add_total_deduction').val() ? parseFloat($('#add_total_deduction').val()) : 0;
        
        $('#add_gross_salary').val(basic_salary+total_allowance);
        $('#add_net_salary').val((basic_salary+total_allowance)-total_deduction);
        
    });
    
    $('.fn_edit_claculate').on('keyup', function(){
        
        var basic_salary = $('#edit_basic_salary').val() ? parseFloat($('#edit_basic_salary').val()) : 0;
        var house_rent = $('#edit_house_rent').val() ? parseFloat($('#edit_house_rent').val()) : 0;
        var transport = $('#edit_transport').val() ? parseFloat($('#edit_transport').val()): 0;
        var medical = $('#edit_medical').val() ? parseFloat($('#edit_medical').val()) : 0;
        var provident_fund = $('#edit_provident_fund').val() ? parseFloat($('#edit_provident_fund').val()) : 0;
        
       $('#edit_total_allowance').val(house_rent+transport+medical);       
        var total_allowance = $('#edit_total_allowance').val() ? parseFloat($('#edit_total_allowance').val()) : 0;
        
        $('#edit_total_deduction').val(provident_fund);
        var total_deduction = $('#edit_total_deduction').val() ? parseFloat($('#edit_total_deduction').val()) : 0;
        
        $('#edit_gross_salary').val(basic_salary+total_allowance);
        $('#edit_net_salary').val((basic_salary+total_allowance)-total_deduction);
        
    });
    
    function get_grade_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    }  	
    function changeCategory(type){
		var tab='tab_add_grade';
		<?php if(isset($edit)){ ?>
			tab="tab_edit_grade";
		<?php } ?>		
        $("#"+tab+" .amount").hide();
        console.log($("#"+tab+" .amount"))
		if(type== 'FixedPay'){
			$("#"+tab+" .otherPayScaleCategory").hide();
			$("#"+tab+" .percentage").hide();
            $("#"+tab+" .amount").show();
			$("#"+tab+" .set_max_amount_limit").hide();
			$("#"+tab+" .max_amount_possible").hide();
			$("#"+tab+" .unbound_payscale_category").show();
		}
		else if (type == 'DependsOnGPandPS'){
            
			$("#"+tab+" .amount").hide();
			$("#"+tab+" .unbound_payscale_category").hide();
			$("#"+tab+" .percentage").show();
			$("#"+tab+" .set_max_amount_limit").show();
			$("#"+tab+" .otherPayScaleCategory").show();
		}
		else if (type == 'DependsOnGP'){
			
		}
	}
	function maxAmountLimit(id){
	var tab='tab_add_grade';
		<?php if(isset($edit)){ ?>
			tab="tab_edit_grade";
		<?php } ?>				
		if($("#"+id).is(":checked")){
			$("#"+tab+" .max_amount_possible").show();
		}			
		else{
			$("#"+tab+" .max_amount_possible").hide();
		}
	}
	function unboundPayScale(id,type=null){
	var tab='tab_add_grade';
    var checkType = type ? ((type =="FixedPay") ? true : false) : true;
		<?php if(isset($edit)){ ?>
			tab="tab_edit_grade";
		<?php } ?>				
        console.log(checkType)
		if($("#"+id).is(":checked") ){			
			$("#"+tab+" .amount").hide();
		}			
		else{
            if(checkType)
            {
                $("#"+tab+" .amount").show();
            }
			
		}
	}
</script>