<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_item_category'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>         
            <?php  $this->load->view('layout/item-quicklinks');   ?>

            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'inventory', 'itemcategory')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_category_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('category'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'inventory', 'itemcategory')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('itemcategory/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('category'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_category"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('category'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_category"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('category'); ?></a> </li>                          
                        <?php } ?>
 <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_itemcategory_by_school(this.value);">
                                    <option value="<?php echo site_url('itemcategory/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('itemcategory/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 						
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_category_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
										<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
										<th><?php echo $this->lang->line('school'); ?></th>
										<?php } ?>
                                        <th><?php echo $this->lang->line('name'); ?></th>                                        
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($itemcategories) && !empty($itemcategories)){ ?>
                                        <?php foreach($itemcategories as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
											<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
											<td><?php echo $obj->school_name; ?></td>
											<?php } ?>
                                            <td><?php echo $obj->item_category; ?></td>                                            
                                            <td>                                                 
                                                <?php if(has_permission(EDIT, 'inventory', 'itemcategory')){ ?>
                                                    <a href="<?php echo site_url('itemcategory/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'inventory', 'itemcategory')){ ?>
                                                    <a href="<?php echo site_url('itemcategory/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_category">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('itemcategory/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>                                                                                        <?php $this->load->view('layout/school_list_form'); ?>       
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="item_category"  id="item_category" value="<?php echo isset($post['item_category']) ?  $post['item_category'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('item_category'); ?></div> 
                                        </div>
                                    </div>   
                                    <div class="item form-group">                        
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="add_group_id"><?php echo $this->lang->line('group'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select  class="form-control col-md-7 col-xs-12 group_id_select" name="group_id" id="add_group_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php foreach($itemgroups as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($obj->id) && $obj->id==$itemcategory->group_id ){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
								</select>     
                                            </div>
                                </div>	                                            								
								<div class="item form-group">                                   
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('description'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											<textarea name='description' class="form-control col-md-7 col-xs-12"><?php echo isset($post['description']) ?  $post['description'] : ''; ?></textarea>                                          
                                            <div class="help-block"><?php echo form_error('description'); ?></div> 
                                        </div>
                                    </div>									
                                    <!--<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('purchase_ledger'); ?></label>       <div class="col-md-6 col-sm-6 col-xs-12">                             
                                        <select  class="form-control col-md-7 col-xs-12" name="purchase_ledger_id" id="add_purchase_ledger_id">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php foreach($ledgers as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($post['purchase_ledger_id']) && $obj->id==$post['purchase_ledger_id']){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
										</select>
									
								</div>
								</div>		
                                    <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('sales_ledger'); ?> </label>
<div class="col-md-6 col-sm-6 col-xs-12">	                                    
                                        <select  class="form-control col-md-7 col-xs-12" name="sales_ledger_id" id="add_sales_ledger_id">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php foreach($ledgers as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($post['sales_ledger_id']) && $obj->id==$post['sales_ledger_id']){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
										</select>
									
								</div>
								</div>	-->
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('is_fixed_asset_type'); ?>? </label>
<div class="col-md-6 col-sm-6 col-xs-12">
	<input type='checkbox' name='is_fixed_asset_type' value='Yes' <?php echo ($post['is_fixed_asset_type']=='Yes') ?  "checked='checked'" : ''; ?> />
	                                
</div>
								</div>	                                      								
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('itemcategory/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
                               <?php echo form_open_multipart(site_url('itemcategory/edit/'.$itemcategory->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>  
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                <div class="item form-group">    
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_group_id"><?php echo $this->lang->line('group'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select  class="form-control col-md-7 col-xs-12 group_id_select" name="group_id" id="edit_group_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php foreach($itemgroups as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($obj->id) && $obj->id==$itemcategory->group_id ){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
								</select>   
                                </div>     
                                            </div>
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="item_category"  id="item_category" value="<?php echo isset($itemcategory) ? $itemcategory->item_category : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('item_category'); ?></div> 
                                        </div>
                                    </div>                                                                                  
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('description'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<textarea name='description' class="form-control col-md-7 col-xs-12"><?php echo isset($itemcategory) ? $itemcategory->description : ''; ?></textarea>                                          
                                            <div class="help-block"><?php echo form_error('description'); ?></div> 
                                        </div>
                                    </div>                                                                      
									<!--	 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('purchase_ledger'); ?></label>       <div class="col-md-6 col-sm-6 col-xs-12">                             
                                        <select  class="form-control col-md-7 col-xs-12" name="purchase_ledger_id" id="edit_purchase_ledger_id">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									
								</div>
								</div>		
                                    <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('sales_ledger'); ?> </label>
<div class="col-md-6 col-sm-6 col-xs-12">	                                    
                                        <select  class="form-control col-md-7 col-xs-12" name="sales_ledger_id" id="edit_sales_ledger_id">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>
									
								</div>
								</div>	-->
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('is_fixed_asset_type'); ?>? </label>
<div class="col-md-6 col-sm-6 col-xs-12">
	<input type='checkbox' name='is_fixed_asset_type' value='Yes' <?php echo ($itemcategory->is_fixed_asset_type=='Yes') ?  "checked='checked'" : ''; ?> />
	                                
</div>
								</div>	 						
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($itemcategory) ? $itemcategory->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('itemcategory/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
	   function get_itemcategory_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    } 
    $(document).on('change','.fn_school_id', function(){
            var school_id=this.value;
            $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('ajax/get_school_item_groups'); ?>",
                data   : { school_id:school_id},               
                async  : false,
                success: function(response){                                                   
                    if(response)
                    {  
                        <?php if(isset($edit)){ ?>
                        $('#edit_group_id').html(response);  
                        <?php }else{ ?>
                            $('#add_group_id').html(response);  
                    <?php } ?>
                    }
                }
            });

         })	
</script>