<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_item_supplier'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>     
            <?php  $this->load->view('layout/item-quicklinks');   ?>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'inventory', 'itemsupplier')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_supplier_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('supplier'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'inventory', 'itemsupplier')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('itemsupplier/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('supplier'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_supplier"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('supplier'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_supplier"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('supplier'); ?></a> </li>                          
                        <?php } ?> 
						<li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_itemsupplier_by_school(this.value);">
                                    <option value="<?php echo site_url('itemsupplier/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('itemsupplier/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 	
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_supplier_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
										<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
										<th><?php echo $this->lang->line('school'); ?></th>
										<?php } ?>
                                        <th><?php echo $this->lang->line('name'); ?></th>  
										<th><?php echo $this->lang->line('contact_person'); ?></th>                                      
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($itemsuppliers) && !empty($itemsuppliers)){ ?>
                                        <?php foreach($itemsuppliers as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
											<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
											<td><?php echo $obj->school_name; ?></td>
											<?php } ?>
                                            <td><?php echo $obj->item_supplier; ?></td>
												<td><?php echo $obj->contact_person_name; ?></td>
                                            <td>                                                 
                                                <?php if(has_permission(EDIT, 'inventory', 'itemsupplier')){ ?>
                                                    <a href="<?php echo site_url('itemsupplier/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'inventory', 'itemsupplier')){ ?>
                                                    <a href="<?php echo site_url('itemsupplier/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_supplier">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('itemsupplier/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>                                                                                        <?php $this->load->view('layout/school_list_form'); ?>              
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="item_supplier"  id="item_supplier" value="<?php echo isset($post['item_supplier']) ?  $post['item_supplier'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required="required">
                                            <div class="help-block"><?php echo form_error('item_supplier'); ?></div> 
                                        </div>
                                    </div>									
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('phone'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="phone" value="<?php echo isset($post['phone']) ?  $post['phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('phone'); ?></div> 
                                        </div>
                                    </div>                                                                     								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('email'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"  id="email" value="<?php echo isset($post['email']) ?  $post['email'] : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?> "  type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div> 
                                        </div>
                                    </div>                                                                      								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('address'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											<textarea name='address' class="form-control col-md-7 col-xs-12"><?php echo isset($post['address']) ?  $post['address'] : ''; ?></textarea>                                          
                                            <div class="help-block"><?php echo form_error('address'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('contact_person_name'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="contact_person_name"  id="contact_person_name" value="<?php echo isset($post['contact_person_name']) ?  $post['contact_person_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('contact_person_name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('contact_person_name'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('contact_person_phone'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="contact_person_phone"  id="contact_person_phone" value="<?php echo isset($post['contact_person_phone']) ?  $post['contact_person_phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('contact_person_phone'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('contact_person_phone'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('contact_person_email'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="contact_person_email"  id="contact_person_email" value="<?php echo isset($post['contact_person_email']) ?  $post['contact_person_email'] : ''; ?>" placeholder="<?php echo $this->lang->line('contact_person_email'); ?> "  type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('contact_person_email'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('description'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											<textarea name='description' class="form-control col-md-7 col-xs-12"><?php echo isset($post['description']) ?  $post['description'] : ''; ?></textarea>                                          
                                            <div class="help-block"><?php echo form_error('description'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('itemsupplier/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
</div>
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_supplier">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('itemsupplier/edit/'.$itemsupplier->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
<?php $this->load->view('layout/school_list_edit_form'); ?> 							   
                                 <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="item_supplier"  id="item_supplier" value="<?php echo isset($itemsupplier) ?  $itemsupplier->item_supplier : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required="required">
                                            <div class="help-block"><?php echo form_error('item_supplier'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								 <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('phone'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="phone" value="<?php echo isset($itemsupplier) ?  $itemsupplier->phone : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('phone'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('email'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"  id="email" value="<?php echo isset($itemsupplier) ?  $itemsupplier->email : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?> "  type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('address'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											<textarea name='address' class="form-control col-md-7 col-xs-12"><?php echo isset($itemsupplier) ?  $itemsupplier->address : ''; ?></textarea>                                          
                                            <div class="help-block"><?php echo form_error('address'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('contact_person_name'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="contact_person_name"  id="contact_person_name" value="<?php echo isset($itemsupplier) ?  $itemsupplier->contact_person_name : ''; ?>" placeholder="<?php echo $this->lang->line('contact_person_name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('contact_person_name'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('contact_person_phone'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="contact_person_phone"  id="contact_person_phone" value="<?php echo isset($itemsupplier) ?  $itemsupplier->contact_person_phone : ''; ?>" placeholder="<?php echo $this->lang->line('contact_person_phone'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('contact_person_phone'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('contact_person_email'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="contact_person_email"  id="contact_person_email" value="<?php echo isset($itemsupplier) ?  $itemsupplier->contact_person_email : ''; ?>" placeholder="<?php echo $this->lang->line('contact_person_email'); ?> "  type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('contact_person_email'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('description'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											<textarea name='description' class="form-control col-md-7 col-xs-12"><?php echo isset($itemsupplier) ?  $itemsupplier->description : ''; ?></textarea>                                          
                                            <div class="help-block"><?php echo form_error('description'); ?></div> 
                                        </div>
                                    </div>                                                                      
														
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($itemsupplier) ? $itemsupplier->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('itemsupplier/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
function get_itemsupplier_by_school(url){          
        if(url){
            window.location.href = url; 
        }	
}		
</script>