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
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_account_groups'); ?></small></h3>
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
                        <?php if(has_permission(VIEW, 'accounting', 'accountgroups')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_group_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('group'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'accounting', 'accountgroups')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('accountgroups/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('category'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_group" role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('group'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_group"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('group'); ?></a> </li>                          
                        <?php } ?>
 <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_accountgroups_by_school(this.value);">
                                    <option value="<?php echo site_url('accountgroups/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('accountgroups/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 						
                    </ul>
                    <br/>
                    <?php echo form_open_multipart(site_url('accountgroups/index'), array('name' => 'filterList', 'id' => 'filterList', 'class'=>'form-horizontal form-label-left'), ''); ?>
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
							   <?php } 
                             
                               ?>  
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
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_category_list" >
                            <div class="x_content">
							<?php echo form_open_multipart(site_url('accountgroups/delete_multiple'), array('name' => 'delete_multiple', 'id' => 'delete_multiple', 'class'=>'form-horizontal form-label-left'), ''); ?>
							<input type="hidden" name='sc_id' value="<?php print $filter_school_id; ?>" />
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
<?php if(has_permission(DELETE, 'accounting', 'accountgroups')){ ?>                                    
										<th class='noPrint'><input type="checkbox" name="checkAll" id="checkall" value='1' /></th>	
<?php } ?>									
<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>									
										<th><?php echo $this->lang->line('school'); ?></th>
<?php } ?>
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('is_primary'); ?>?</th>
										<th><?php echo $this->lang->line('type'); ?></th>
										<th><?php echo $this->lang->line('base'); ?></th>
										<th><?php echo $this->lang->line('group')." ".$this->lang->line('code'); ?></th>
                                        <th class="noPrint"><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($accountgroups) && !empty($accountgroups)){ ?>
                                        <?php foreach($accountgroups as $obj){ ?>
                                        <tr>  
<?php if(has_permission(DELETE, 'accounting', 'accountgroups')){ ?>										
											<td class='noPrint'>
											<?php if($obj->school_id == 0){
													if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
													
											<input type="checkbox" class='delete_check' name="checkId[]" value='<?php print $obj->id; ?>' />
											<?php } } else { ?>
											<input type="checkbox" class='delete_check' name="checkId[]" value='<?php print $obj->id; ?>' />
											<?php }?>
											</td>
<?php } ?>										
<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>										
											<td><?php echo $obj->school_name; ?></td>
<?php  } ?>
                                            <td><?php echo $obj->name; ?></td>
											<td><?php 
											if($obj->is_primary ==1){
												echo 'true';
											}
											else {
												echo 'false';
											}
											 ?></td>
											<td><?php echo $obj->type_name; ?></td>
                                            <td><?php echo $obj->base_name; ?></td>
											<td><?php echo $obj->group_code; ?></td>
                                            <td>                                                 
                                                <?php if(has_permission(EDIT, 'accounting', 'accountgroups')){ ?>
												 <?php if($obj->school_id == 0){
													if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
                                                    <a href="<?php echo site_url('accountgroups/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
												 <?php } } else { ?>
												  <a href="<?php echo site_url('accountgroups/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php }} ?>
                                                <?php if($obj->school_id == 0){
													if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
													 <a href="<?php echo site_url('accountgroups/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
													<?php }
												}
												else{
																								
												if(has_permission(DELETE, 'accounting', 'accountgroups')){ ?>
                                                    <a href="<?php echo site_url('accountgroups/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>  
<?php echo form_close(); ?>										
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_group">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('accountgroups/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>                                                                                        <?php $this->load->view('layout/school_list_form'); ?>       
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>                                                                      		<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('is_primary'); ?>?</label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="is_primary"  id="is_primary" value="1" type="checkbox" <?php echo isset($post['is_primary']) ?  "checked='checked'" : ''; ?>"  autocomplete="off">
                                            <div class="help-block"><?php echo form_error('is_primary'); ?></div> 
                                        </div>
                                    </div>	
<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('is_readonly'); ?>?</label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="is_readonly"  id="is_readonly" value="1" type="checkbox" <?php echo isset($post['is_readonly']) ?  "checked='checked'" : ''; ?>"  autocomplete="off">
                                            <div class="help-block"><?php echo form_error('is_readonly'); ?></div> 
                                        </div>
                                    </div>										
								     <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('type'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="add_type_id" name="type_id" class="form-control" required='required' onchange="get_account_base(this.value);" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($account_types as $type) {
                                            ?>
                                            <option value="<?php echo $type->id ?>"<?php
                                            if (isset($_POST['type_id']) && $_POST['type_id'] == $type->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $type->name; ?></option>

                                            <?php
                                        }
                                        ?>
										</select>
                                            <div class="help-block"><?php echo form_error('type'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('base'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="add_base_id" name="base_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        
										</select>
                                            <div class="help-block"><?php echo form_error('base'); ?></div> 
                                        </div>
                                    </div>  
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('group_code'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="group_code"  id="add_group_code" value="<?php echo isset($post['group_code']) ?  $post['group_code'] : ''; ?>" placeholder="<?php echo $this->lang->line('group_code'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('group_code'); ?></div> 
                                        </div>
                                    </div>    
                                    <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company <span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                             <select autofocus="" id="add_category" name="category" class="form-control" required="required">
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
                                        <a href="<?php echo site_url('accountgroups/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
</div>
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_category">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('accountgroups/edit/'.$accountgroup->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>  
<?php $this->load->view('layout/school_list_edit_form'); ?> 
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($accountgroup) ? $accountgroup->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>     <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('is_primary'); ?>?</label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="is_primary"  id="is_primary" value="1" type="checkbox" <?php echo ($accountgroup->is_primary == 1) ?  "checked='checked'" : ''; ?>"  autocomplete="off">
                                            <div class="help-block"><?php echo form_error('is_primary'); ?></div> 
                                        </div>
                                    </div>	
<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('is_readonly'); ?>?</label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="is_readonly"  id="is_readonly" value="1" type="checkbox" <?php echo ($accountgroup->is_readonly==1) ?  "checked='checked'" : ''; ?>"  autocomplete="off">
                                            <div class="help-block"><?php echo form_error('is_readonly'); ?></div> 
                                        </div>
                                    </div>										
								     <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('type'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="edit_type_id" name="type_id" class="form-control" required='required' onchange="get_account_base(this.value);" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($account_types as $type) {
                                            ?>
                                            <option value="<?php echo $type->id ?>"<?php
                                            if (isset($accountgroup->type_id) && $accountgroup->type_id == $type->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $type->name; ?></option>

                                            <?php
                                        }
                                        ?>
										</select>
                                            <div class="help-block"><?php echo form_error('type'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('base'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="edit_base_id" name="base_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        
										</select>
                                            <div class="help-block"><?php echo form_error('base'); ?></div> 
                                        </div>
                                    </div>  
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('group_code'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="group_code"  id="edit_group_code" value="<?php echo isset($accountgroup) ? $accountgroup->group_code : ''; ?>" placeholder="<?php echo $this->lang->line('group_code'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('group_code'); ?></div> 
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
                                    if (isset($accountgroup->category) && $accountgroup->category == $key) {
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
                                        <input type="hidden" value="<?php echo isset($accountgroup) ? $accountgroup->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('accountgroups/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
		
		$message="Account Group ( ".$financial_year->session_year.")";
		$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 } else { 
	 $message="Account Group";
	$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 } ?>
	
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
		 <?php if(isset($accountgroup) && !empty($accountgroup)){ ?>
            $("#edit_type_id").trigger('change');
         <?php } ?>		
       
       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
	   var target=5;
	   <?php } else { ?>
	   var target=4;
	   <?php } ?>
		 
          var table =$('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
              orderCellsTop: true,
				fixedHeader: true,
              iDisplayLength: 15,
              buttons: [               
				  {
					  'extend' : 'csvHtml5',					 					  
						exportOptions: {
							columns: 'thead th:not(.noPrint)'
						},
						customize: function (csv) {
							return '<?php print $message; ?> \n'+csv;
						}
				  },
				  {
					  'extend' : 'excelHtml5',					 
					  //footer: true,
//header:true,					  
						//messageTop: '<?php print $message; ?>',					  
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
					  'extend' : 'pdfHtml5',					 
					  //footer: true,
						message: '<?php print $message; ?>',
						exportOptions: {
							columns: 'thead th:not(.noPrint)'
						},
				  },                 
                  'pageLength',
				  'colvis',
				  {
					'extend': 'print',
					//title: '<div><span><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>',	
					title: '',
					footer: true,					
					exportOptions: {
                    columns: 'thead th:not(.noPrint)'
					},
					customize: function ( win ) {
						$(win.document.body).css( 'margin', '20px' );
						$(win.document).find('table').before('<div><span><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>');						
						//$(win.document.body).prepend('<div><span><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>');
						if($(win.document).find('table').length)
						{
							//$(win.document).find('h1').css("font-size",'150%')
							$(win.document).find('table').css("font-size",'10px');	
							$(win.document).find('table').after("<div>Generated by : <?php echo $this->session->userdata('username'); ?></div><div>Date : <?php echo date('d/m/Y'); ?></div>");							
						}
					}
				  },
				  <?php if(has_permission(DELETE, 'accounting', 'accountgroups')){ ?>                                    
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
						"targets": [ target ],
						"visible": false,						
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
		   $( '#datatable-responsive thead .column_search'  ).bind( 'keyup', function () {   
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    } );
        });
        
       $("#add").validate();  
       $("#edit").validate();  
	   function get_accountgroups_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 
	function get_account_base(type_id){		
		var base_id;
		base_id='';
		var edit=0;
		 <?php if(isset($accountgroup) && !empty($accountgroup)){ ?>
			edit=1;
			base_id =  '<?php echo $accountgroup->base_id; ?>';
		 <?php } ?>
			 $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountbase_by_type'); ?>",
            data   : { type_id:type_id,base_id:base_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit==1){
                       $('#edit_base_id').html(response);   
                   }else{
                       $('#add_base_id').html(response);   
                   }                                                      
               }
            }
        });
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