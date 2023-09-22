<style>
table{
	table-layout: fixed;
}
table td{
	word-wrap: break-word;
}
</style>
<?php $voucher_category = getVoucherCategory();?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_account_ledgers'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>             
            <div class="x_content quick-link">
				<?php $this->load->view('quicklinks/account'); ?>
			</div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'accounting', 'accountledgers')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_ledger_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('ledger'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'accounting', 'accountledgers')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('accountgroups/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('ledger'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_ledger" role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('ledger'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_ledger"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('ledger'); ?></a> </li>                          
                        <?php } ?>
 <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_accountledgers_by_school(this.value);">
                                    <option value="<?php echo site_url('accountledgers/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('accountledgers/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
						  
                    </li> 						
                    </ul>
                    <br/>
					<?php echo form_open_multipart(site_url('accountledgers/index'), array('name' => 'filterList', 'id' => 'filterList', 'class'=>'form-horizontal form-label-left'), ''); ?>
							 <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>  
							 <div class="col-md-3 col-sm-3 col-xs-12">
							 <div class="item form-group">  
 <select  name='school_id' class="form-control col-md-7 col-xs-12" id="school_id">
                                    <option value="">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php print $obj->id; ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>	
</div>
</div>
							   <?php } ?>  
					<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="item form-group">                        
                                           
                                           <select autofocus="" id="filter_category" name="category" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
                                        foreach ($voucher_category as $key=>$value) {
                                            ?>
                                            <option value="<?php echo $key ?>"<?php
                                            if (isset($_POST['category']) && $_POST['category'] == $key) {
                                                echo "selected = selected";
                                            }
                                            else  if (empty($_POST) && isset($school_info->category) &&  $school_info->category == $key) 
                                            {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $value; ?></option>

                                            <?php
                                        }
                                        ?>
										</select>                                         
                                        </div></div>
										<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="item form-group">   
 <button id="filter" type="submit" class="btn btn-success">Filter</button>							
							</div>
							</div>
							<?php echo form_close(); ?>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_ledger_list" >
                            <div class="x_content">
							<?php echo form_open_multipart(site_url('accountledgers/delete_multiple'), array('name' => 'delete_multiple', 'id' => 'delete_multiple', 'class'=>'form-horizontal form-label-left'), ''); ?>
							<input type="hidden" name='sc_id' value="<?php print $filter_school_id; ?>" />
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>    
<?php if(has_permission(DELETE, 'accounting', 'accountledgers')){ ?>                                    
										<th class='noPrint'><input type="checkbox" name="checkAll" id="checkall" value='1' /></th>	
<?php } else { ?>
										<th class='noPrint'><?php echo $this->lang->line('sl_no'); ?></th>
									<?php  } ?>
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('group'); ?></th>
										<th><?php echo $this->lang->line('debit'); ?></th>
										<th><?php echo $this->lang->line('credit'); ?></th>
									<!--	<th><?php echo $this->lang->line('available_budget'); ?></th>
										<th><?php echo $this->lang->line('remaining_budget'); ?></th>-->
										<th><?php echo $this->lang->line('dr_cr'); ?></th>
										<th><?php echo $this->lang->line('current_balance'); ?></th>
										<th><?php echo $this->lang->line('opening_cr_dr'); ?></th>
										<th><?php echo $this->lang->line('opening_balance'); ?></th>
										<!--<th><?php echo $this->lang->line('budget_cr_dr'); ?></th>-->
										<th><?php echo $this->lang->line('budget'); ?></th>
										<th><?php echo $this->lang->line('ledger_uid'); ?></th>
										<th><?php echo $this->lang->line('school'); ?></th>
										<th><?php echo $this->lang->line('academic_year'); ?></th>
										<th><?php echo $this->lang->line('group_code'); ?></th>
                                        <th class='noPrint'><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($accountledgers) && !empty($accountledgers)){ ?>
                                        <?php $total_debit=0; $total_credit=0; $sr=1;
										foreach($accountledgers as $obj){										
										?>
                                        <tr>   
<?php if(has_permission(DELETE, 'accounting', 'accountledgers')){ ?>										
											<td class='noPrint'><input type="checkbox" class='delete_check' name="checkId[]" value='<?php print $obj->id; ?>' /></td>
<?php } else{?>
<td class='noPrint'><?php print $sr; ?></td>
<?php } ?>
                                            <td><?php echo $obj->name; ?></td>											
											<td><?php echo $obj->group_name; ?></td>
                                            <td class='debit_amt' align='right'>
											<?php 											
												if($obj->effective_balance < 0){
													$total_debit += $obj->effective_balance;
													print abs($obj->effective_balance); 
												}
												?>
											</td>											
											<td class='credit_amt' align='right'><?php												
													if($obj->effective_balance > 0){
													//	if($obj->dr_cr == 'CR'){
														$total_credit += $obj->effective_balance;
														print abs($obj->effective_balance); 
													}												
											?></td>
										<!--	<td><?php 
											if($obj->budget != 0){ 
											print $obj->budget." [".$obj->budget_cr_dr. "]"; } ?></td>
											<td><?php if($obj->budget != 0){ 
											print $obj->budget." [".$obj->budget_cr_dr. "]"; } ?></td>-->
											<td><?php 
											if($obj->effective_balance < 0){
												print " DR";
												}
												else if($obj->effective_balance > 0){
												print " CR";	
												} 
											/*if($obj->effective_balance == 0){
												if($obj->dr_cr == 'DR'){ print "DR"; } else { print "CR"; }
											}else{
											if($obj->effective_balance < 0){ print "DR"; } else { print "CR"; } } 
											*/ 
											?>
											</td>
											<td><?php 											
												if($obj->effective_balance < 0){
													$total_debit += $obj->effective_balance;
													print abs($obj->effective_balance); 
												}												
												else if($obj->effective_balance > 0){
													//	if($obj->dr_cr == 'CR'){
														$total_credit += $obj->effective_balance;
														print abs($obj->effective_balance); 
													}
													
											//print abs($obj->effective_balance); 
											?></td>
											<td><?php print $obj->opening_cr_dr; ?></td>
											<td><?php 
											if($obj->opening_balance != 0){ 
											print abs($obj->opening_balance)." [".$obj->opening_cr_dr. "]"; } ?></td>
										<!--	<td><?php if($obj->budget < 0){ print "DR"; } else { print "CR"; } ?></td>-->
											<td><?php print abs($obj->budget)." [".$obj->budget_cr_dr. "]"; ?></td>
											
											<td><?php echo $obj->ledger_uid; ?></td>
											<td><?php echo $obj->school_name; ?></td>
											<td><?php echo $obj->session_year; ?></td>
											<td><?php echo $obj->group_code; ?></td>
                                            <td>   
<?php if(has_permission(VIEW, 'accounting', 'accountledgers')){ ?>
                                                        <a href="<?php echo site_url('accountledgers/view/'.$obj->id); ?>"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                    <?php } ?>											
                                                <?php if(has_permission(EDIT, 'accounting', 'accountledgers')){ ?>
                                                    <a href="<?php echo site_url('accountledgers/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'accounting', 'accountledgers')){ ?>
                                                    <a href="<?php echo site_url('accountledgers/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php }  ?>
                                            </td>
                                        </tr>
                                        <?php
									$sr++; 
									} ?>
                                    <?php } ?>
                                </tbody>
								<tfoot>
								<tr class="noPrint">	
								<?php if(has_permission(DELETE, 'accounting', 'accountledgers')){ ?>
									<th class='noPrint'></th>
								<?php } else{ ?>
								<th></th>
								<?php } ?>
									<th></th>
									<th>Balance</th>
									<th class="debitTotal" style='text-align:right;'></th>
									<th class="creditTotal" style='text-align:right;'></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>	
									<th></th>
									<th></th>
									<th></th>
									<!--<th></th>
									<th></th>
									<th></th>-->
									<th></th>
									<th></th>
									<th></th>
								</tr>
								
								<tfoot>								
                            </table>
					<?php echo form_close(); ?>							
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_ledger">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('accountledgers/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>                                                                                        <?php $this->load->view('layout/school_list_form'); ?>       
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('group'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="add_group_id" name="account_group_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                         <?php foreach($accountgroups as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['account_group_id']) && $post['account_group_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name." ($obj->category)"; ?></option>
                                            <?php } ?>  
										</select>
                                            <div class="help-block"><?php echo form_error('account_group_id'); ?></div> 
                                        </div>
                                    </div> <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('opening_cr_dr'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="opening_cr_dr"  id="opening_cr_dr" value="CR" type="radio" <?php echo ($post['opening_cr_dr']=='CR') ?  "checked='checked'" : ''; ?>"  autocomplete="off" required='required'>CR
											<input  class=""  name="opening_cr_dr"  id="opening_cr_dr" value="DR" type="radio" <?php echo ($post['opening_cr_dr']=='DR') ?  "checked='checked'" : ''; ?>"  autocomplete="off"  required='required'>DR
                                            <div class="help-block"><?php echo form_error('opening_cr_dr'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('opening_balance'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="opening_balance"  id="opening_balance" value="<?php echo isset($post['opening_balance']) ?  $post['opening_balance'] : ''; ?>" placeholder="<?php echo $this->lang->line('opening_balance'); ?> "  type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('opening_balance'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="budget"  id="budget" value="<?php echo isset($post['budget']) ?  $post['budget'] : ''; ?>" placeholder="<?php echo $this->lang->line('budget'); ?> "  type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('budget'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget_cr_dr'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="CR" type="radio" <?php echo ($post['budget_cr_dr']=='CR') ?  "checked='checked'" : ''; ?>"  autocomplete="off" required='required'>CR
											<input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="DR" type="radio" <?php echo ($post['budget_cr_dr']=='DR') ?  "checked='checked'" : ''; ?>"  autocomplete="off"  required='required'>DR
                                            <div class="help-block"><?php echo form_error('budget_cr_dr'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company <span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                             <select autofocus="" id="add_category" name="category" class="form-control" required="required" >
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
                                        foreach ($voucher_category as $key=>$value) {
                                            ?>
                                            <option value="<?php echo $key ?>"<?php
                                            if (isset($_POST['category']) && $_POST['category'] == $key) {
                                                echo "selected = selected";
                                            }
											else  if (isset($school_info->category) &&  $school_info->category == $key) 
                                            {
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
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('accountledgers/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_ledger">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('accountledgers/edit/'.$accountledger->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>  
<?php $this->load->view('layout/school_list_edit_form'); ?> 
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($accountledger) ? $accountledger->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>     <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('group'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="edit_group_id" name="account_group_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        
										</select>
                                            <div class="help-block"><?php echo form_error('account_group_id'); ?></div> 
                                        </div>
                                    </div> <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('opening_cr_dr'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="opening_cr_dr"  id="opening_cr_dr" value="CR" type="radio" <?php echo ($accountledger->dr_cr=='CR') ?  "checked='checked'" : ''; ?>"  autocomplete="off" required='required'>CR
											<input  class=""  name="opening_cr_dr"  id="opening_cr_dr" value="DR" type="radio" <?php echo ($accountledger->dr_cr=='DR') ?  "checked='checked'" : ''; ?>"  autocomplete="off"  required='required'>DR
                                            <div class="help-block"><?php echo form_error('opening_cr_dr'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('opening_balance'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="opening_balance"  id="opening_balance" value="<?php echo isset($accountledger->opening_balance) ?  $accountledger->opening_balance : ''; ?>" placeholder="<?php echo $this->lang->line('opening_balance'); ?> "  type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('opening_balance'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="budget"  id="budget" value="<?php echo isset($accountledger->budget) ?  $accountledger->budget : ''; ?>" placeholder="<?php echo $this->lang->line('budget'); ?> "  type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('budget'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget_cr_dr'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="CR" type="radio" <?php echo ($accountledger->budget_cr_dr=='CR') ?  "checked='checked'" : ''; ?>"  autocomplete="off" required='required'>CR
											<input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="DR" type="radio" <?php echo ($accountledger->budget_cr_dr=='DR') ?  "checked='checked'" : ''; ?>"  autocomplete="off"  required='required'>DR
                                            <div class="help-block"><?php echo form_error('budget_cr_dr'); ?></div> 
                                        </div>
                                    </div>                                                                        
									<div class="item form-group">                        
                                    
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<select autofocus="" id="edit_category" name="category" class="form-control" required="required" >
										<option value=""><?php echo $this->lang->line('all'); ?></option>
										<?php
										foreach ($voucher_category as $key=>$value) {
											?>
											<option value="<?php echo $key ?>"<?php
											if (isset($accountledger->category) && $accountledger->category == $key) {
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
																
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($accountledger) ? $accountledger->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('accountledgers/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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

 <script type="text/javascript">  
<?php /* if(isset($school_info->frontend_logo) && $school_info->frontend_logo!= ''){ ?>
				var schoollogo= '<?php print UPLOAD_PATH."/logo/".$school_info->frontend_logo; ?>';
	<?PHP } else*/ if($this->global_setting->brand_logo){ ?> 
			var schoollogo=  '<?php echo UPLOAD_PATH."logo/".$this->global_setting->brand_logo; ?>';
	<?php } else {  ?>
	var schoollogo=  "<?php echo IMG_URL. '/sms-logo-50.png'; ?>";
	<?php } 
	 if(isset($financial_year->session_year)){ 
		
		$message="Account Ledgers ( ".$financial_year->session_year.")";
		$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 } else { 
	 $message="Account Ledgers";
	$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 } ?>				
var edit = false;
    $(document).ready(function() {
		 <?php if(isset($edit) && !empty($edit)){ ?>		 
           $("#edit_school_id").trigger('change');         
         <?php } ?>	
	});		 
       <?php if(isset($accountledger) && !empty($accountledger)){ ?>
          edit = true; 
    <?php } ?>
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();
		var group_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
            group_id =  '<?php echo $accountledger->account_group_id; ?>';           
         <?php } ?> 
		if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountgroup_by_school'); ?>",
            data   : { school_id:school_id, group_id:group_id,company:1},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_group_id').html(response);   
                   }else{
                       $('#add_group_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});
       
</script>
	<script type="text/javascript">   	    

    $(document).ready(function() {
		$('#datatable-responsive thead tr').clone(true).appendTo( '#datatable-responsive thead' );
		var c=0;
$('#datatable-responsive thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
			if(title == 'Action' || c==0){
				$(this).html( '');
			}
			else{
			$(this).html( '<input type="text" placeholder="Search" style="width:100%;"  class="column_search" />' );	
			}
		     c++;    
    } );
	 /*jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
            if ( this.context.length ) {
                var jsonResult = $.ajax({
                    url: '<?php echo site_url("accountledgers/get_list"); ?>',
					'type':'post',
                    'data': {'school_id': sch_id,'page':'all'},
                    success: function (result) {
                        //Do nothing
                    },
                    async: false
                });

                return {body: jsonResult.responseJSON.data, header: $("#datatable-responsive thead tr th").map(function() { return this.innerHTML; }).get()};
            }
        } );*/

	jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }
 
        return a + b;
    }, 0 );
} );
var sch_id='<?php print $filter_school_id; ?>';
          var table =$('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
			  orderCellsTop: true,
				fixedHeader: true,
              iDisplayLength: 15,	
 /*'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("accountledgers/get_list"); ?>',
		  'data': {'school_id': sch_id}
      },			  */
	/*		  drawCallback: function () {
				  var api = this.api();
				  var totalDebit=0;
				  var totalCredit=0;
				  var amt;
		  $(".debit_amt:visible").each(function( i, val ) {			  
			  amt= $.trim($(this).text());
			  if(amt > 0){
				totalDebit = totalDebit + parseFloat($(this).text());
			  }			 
		  });	
$(".credit_amt:visible").each(function( i, val ) {			  
			  amt= $.trim($(this).text());
			  if(amt > 0){
				totalCredit = totalCredit + parseFloat($(this).text());
			  }			 
		  });			  
				  $("th.debitTotal" ).html(totalDebit);
				  $("th.creditTotal" ).html(totalCredit);  
				/*  $("th.debitTotal" ).html(
					api.column( 3, {page:'current'} ).data().sum()
				 );
				  $("th.creditTotal" ).html(
					api.column( 4, {page:'current'} ).data().sum()
				  );*/
				  			   
	/*			},  */
				footerCallback: function( tfoot, data, start, end, display ) {
				var api = this.api();
				var intVal = function (i) {					
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :					
						typeof i === 'number' ?
						i : 0;
				};				
				totalDebit = api
					.column(3)
					.data()
					.reduce(function (a, b) {						
							return intVal(a) + intVal(b);						
					}, 0)

							pageTotalDebit = api
					.column(3, { page: 'current' })
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0);
					total_debit_amount = totalDebit.toFixed(2);
				$(api.column(3).footer()).html(
						'<span class="page-total">'+pageTotalDebit + '</span> / '+ total_debit_amount+''
					)
					totalCredit = api
					.column(4)
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0)

							pageTotalCredit = api
					.column(4, { page: 'current' })
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0);
					total_credit_amount = totalCredit.toFixed(2);
				$(api.column(4).footer()).html(
					'<span class="page-total">'+pageTotalCredit + '</span> / '+ total_credit_amount+''
						
					)
					},
              buttons: [
				/*{
                  'extend': 'copyHtml5',
				  footer: true,
				},*/
				{
                  'extend': 'excelHtml5',
				  footer: false,
				  exportOptions: {
                    columns: 'thead th:not(.noPrint)',
					 format: {
                            header: function ( data, columnIdx ) {                                
                                 if(columnIdx==1){
                                return '<?php print $message; ?>';
                                }
                                else{
                                return '';
                                }
                                
                            }
                        }
					},
				},
				{
                  'extend': 'csvHtml5',
				  footer: false,
				  exportOptions: {
                    columns: 'thead th:not(.noPrint)'
					},
					customize: function (csv) {
							return '<?php print $message; ?> \n'+csv;
						}
				},
				  {
                   'extend': 'pdfHtml5',  
				   footer: false,
				   message: '<?php print $message; ?>',
				   exportOptions: {
                    columns: 'thead th:not(.noPrint)'
					},
				  },
				 'pageLength',
				  'colvis',
				  {
					'extend': 'print',
					//title: '<div style=""><span style=""><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>',	
					title : '',					
					footer: true,
					exportOptions: {
						columns: 'thead th:not(.noPrint)',
/*modifier: {
                order : 'current',  // 'current', 'applied', 'index',  'original'
                        page : 'all',      // 'all',     'current'
                        search : 'applied'   
                }						*/
					},
					customize: function ( win ) {
						//$("table tbody").append(addRow);						
						//$table=$(win.document.body).getElementById("datatable-responsive");
						$(win.document.body).css( 'margin', '20px' );	
						if($(win.document).find('table').length)
						{
							//$(win.document).find('h1').css("font-size",'180%')
							$(win.document).find('table').css("font-size",'10px');
							$(win.document).find('table').before('<div><span><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>');
							$(win.document).find('table').find('thead th').css({"width" : "60px","max-width": "60px;"})
							// $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('voucher'); ?>")').css({"width" : "60px","max-width": "60px;"})
							//  $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('transaction_no'); ?>")').css({"width" : "60px","max-width": "60px;"})
						//	$(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('narration'); ?>")').css({"width" : "30%","min-width": "30%"})
					
							var debitIndex  = $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('debit'); ?>")').index();
							var creditIndex = $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('credit'); ?>")').index();
							var debitIndex  = debitIndex ? debitIndex+1 : debitIndex;
							var creditIndex  = creditIndex ? creditIndex+1 : creditIndex;
							$(win.document).find('table').after("<div>Generated by : <?php echo $this->session->userdata('username'); ?></div><div>Date : <?php echo date('d/m/Y'); ?></div>");							
							$(win.document).find('table').find('tfoot th:nth-child('+debitIndex+')').html(''+total_debit_amount)
							$(win.document).find('table').find('tfoot th:nth-child('+creditIndex+')').html(''+total_credit_amount)
						}
							
					}
				  },
				  <?php if(has_permission(DELETE, 'accounting', 'accountledgers')){ ?>                                    
				  {
					  text: 'Delete Selected',
					  className : 'deleteButton',
						action: function ( e, dt, node, config ) {
							var checked = $("#delete_multiple input.delete_check:checked").length > 0;
							if (!checked){
								alert("Please check at least one checkbox");
								return false;
							}
							else{
								if(confirm("Are you sure you want to delete selected records?")){
									$("#delete_multiple").submit();
								}
							}
						}
				  }
				  <?php } ?>
              ],
			  "columnDefs": [
					{						
						"class": 'noPrint', 
						"targets": [ 0 ],						
						"searchable": false,
						"orderable": false
					},
					{						
						"class": 'debit_amt', 
						"targets": [ 3 ],												
					},
					{						
						"class": 'credit_amt', 
						"targets": [ 4 ],												
					},
					{
						"targets": [ 6 ],
						"visible": true,
						"searchable": false
					},
					{
						"targets": [ 7 ],
						"visible": false
					},
					{
						"targets": [ 8 ],
						"visible": false,
						"searchable": false
					},
					{
						"targets": [ 9 ],
						"visible": true,
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
						"targets": [ 12 ],
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
		   $('#checkall').click(function(){
      if($(this).is(':checked')){
         $('.delete_check').prop('checked', true);
      }else{
         $('.delete_check').prop('checked', false);
      }
   });
		  //table.column( 2 ).data().sum();
		  $( '#datatable-responsive thead .column_search'  ).bind( 'keyup', function () {   
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    } );
        });
        
       $("#add").validate();  
       $("#edit").validate();  
	   function get_accountledgers_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 	
		$(document).on('change','#add_school_id', function(){
            var school_id=this.value;
            $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('ajax/get_default_category'); ?>",
                data   : { school_id:school_id},               
                async  : false,
                success: function(response){                                                   
                    if(response)
                    {  
                        $('#add_category').html(response);      
                    }
                }
            });

         })	
		
</script>