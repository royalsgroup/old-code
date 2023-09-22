<style>
.miplus {
    position: absolute;
    width: 60px;
}
.miplus select {
    height: 28px;
    padding-left: 10px;
}
#item_unit{
	font-weight:normal;
}
.miplusinput {
    padding-left: 70px;
}
</style>
<?php
if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   
    $total_colspan = '10';
    $total_col = '11';
 }else{ 
    $total_colspan = '11'; 
    $total_col = '12';
    } ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_item')." ".$this->lang->line('stock'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>             
            
            <?php  $this->load->view('layout/item-quicklinks');   ?>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'inventory', 'itemstock')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_itemstock_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('purchase') ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'inventory', 'itemstock')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('itemstock/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('itemstock'); ?> </a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_itemstock"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('itemstock'); ?> </a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_itemstock"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('item')." ".$this->lang->line('stock'); ?></a> </li>                          
                        <?php } ?> 
						 <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_itemstock_by_school(this.value);">
                                    <option value="<?php echo site_url('itemstock/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('itemstock/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_itemstock_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){ ?>
										<th><?php echo $this->lang->line('school'); ?></th>
										<?php } ?>
                                        <th><?php echo $this->lang->line('date'); ?></th>

                                        <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th><?php echo $this->lang->line('supplier'); ?></th>
                                        <th><?php echo $this->lang->line('debit_ledger'); ?></th>
                                        <th><?php echo $this->lang->line('credit_ledger'); ?></th>  
                                        <th><?php echo $this->lang->line('store'); ?></th>
                                        <th><?php echo $this->lang->line('item')." ".$this->lang->line('name'); ?></th>  
                                        <th><?php echo $this->lang->line('item_code'); ?></th>  

                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                        <th><?php echo $this->lang->line('mrp'); ?></th>

										<th><?php echo $this->lang->line('purchase_price'); ?></th>
                                        <th> Total</th>    

                                        <th><?php echo $this->lang->line('attachment'); ?></th>    
										
										<!-- <th><?php echo $this->lang->line('category'); ?></th> -->
									
										
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($itemstocks) && !empty($itemstocks)){
                                     
                                        ?>
                                        <?php foreach($itemstocks as $obj){ 
                                             ?>
                                            
                                        <tr>
                                            <td><?php echo $count++; ?></td>
											<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){ ?>
											<td><?php echo $obj['school_name']; ?></td>
                                       
											<?php } ?>
                                            <td><?php echo date('d-M-Y',strtotime($obj['date'])); ?></td>
                                            <td><?php echo $obj['invoice_no'] ?></td>
                                            <td><?php echo $obj['item_supplier']; ?></td>
                                            <td><?php echo $obj['debit_ledger']; ?></td>
                                            <td><?php echo $obj['credit_ledger']; ?></td>
                                            <td><?php echo $obj['item_store']; ?></td>

                                            <td><?php echo $obj['name']; ?></td>
                                            <td><?php echo $obj['item_code']; ?></td>

											<!-- <td><?php echo $obj['item_category']; ?></td> -->
										
											<td><?php 
																		   if($obj['symbol']=='-') { print "-"; }
																		   echo $obj['quantity']; ?></td>
                                        	<td><?php echo $obj['mrp']; ?></td>

											<td><?php echo $obj['purchase_price']; ?></td>
                                            <td><?php echo $obj['purchase_price']*$obj['quantity']; ?></td>

                                            <td><?php echo $obj['attachment'] ? '<a  target="_blank" class="btn btn-xs btn-primary" href="'.$obj['attachment'].'" >Download</a>': ""; ?></td>
										
                                            <td>                                                 
                                                <?php /*if(has_permission(EDIT, 'inventory', 'itemstock')){ ?>
                                                    <a href="<?php echo site_url('itemstock/edit/'.$obj['id']); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } */?>
                                                <?php if(has_permission(DELETE, 'inventory', 'itemstock')){ ?>
                                                    <a href="<?php echo site_url('itemstock/delete/'.$obj['id']); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(VIEW, 'inventory', 'itemstock')){ ?>
                                                    <a href="<?php echo site_url('/itemstock/view/'.$obj['invoice_id']); ?>"  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                    <!-- <a href="<?php echo site_url('/itemstock/view_old/'.$obj['id']); ?>"  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('view'); ?> Old </a> -->

                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                              
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_itemstock">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('itemstock/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>   
                               
                               <?php $this->load->view('layout/school_list_form'); ?>     
                               <div class="item form-group">                        
                                    
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('date'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control date col-md-7 col-xs-12" id="date" name="date" autocomplete="off">										     
                                        <div class="help-block"><?php echo form_error('date'); ?></div>
                                    </div>
                            </div>  
                            <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Item Update
                                    </label> 
									<div class="col-md-6 col-sm-6 col-xs-12">									
                                        <input type="checkbox" name="stock_adjustment" id="stock_adjustment" value="1">										     
			
									</div>									
									</div>		
                              <!-- <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('category'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="add_category_id" name="item_category_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($itemcategories as $item_category) {
                                            ?>
                                            <option value="<?php echo $item_category->id ?>"<?php
                                            if (isset($_POST['item_category_id']) && $_POST['item_category_id'] == $item_category->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $item_category->item_category ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                            <div class="help-block"><?php echo form_error('item_category_id'); ?></div> 
                                        </div>
                                    </div>                                                                       -->
								
								 <!-- <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('item'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                    <select  id="item_id" name="item_id" class="form-control"  required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($items as $item) {
                                            echo '<option data-id="'.$item->unit.'" value="'.$item->id.'">'.$item->name.'</option>';
                                        }?>
                                        
                                    </select>
                                    <span class="text-danger"><?php echo form_error('item_id'); ?></span>
										</div>
                                    </div>                                                                      								 -->
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('supplier'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <select  id="add_supplier_id" name="supplier_id" class="form-control"  required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($itemsuppliers as $itemsup) {
                                            ?>
                                            <option value="<?php echo $itemsup->id; ?>"<?php
                                            if (isset($_POST['supplier_id']) && $_POST['supplier_id'] == $itemsup->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $itemsup->item_supplier; ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                            <div class="help-block"><?php echo form_error('supplier_id'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="item form-group accountfields">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('voucher'); ?> 
                                    </label> 
									<div class="col-md-6 col-sm-6 col-xs-12">									
                                        <select  class="form-control col-md-7 col-xs-12" name="voucher_id" id="voucher_id" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>									
									</div>									
									</div>			                                                                  
                                    <div class="item form-group accountfields">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('debit_ledger'); ?> 
                                    </label> 
									<div class="col-md-6 col-sm-6 col-xs-12">									
                                        <select  class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="add_debit_ledger_id" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php
											foreach($account_ledgers as $ledger){ ?>
											<option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
											<?php  } ?>
										</select>									
									</div>									
									</div>									
                                    <div class="item form-group accountfields">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('credit_ledger'); ?> 
                                    </label>	
									<div class="col-md-6 col-sm-6 col-xs-12">										
                                        <select  class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="add_credit_ledger_id" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php
											foreach($account_ledgers as $ledger){ ?>
											<option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
											<?php  } ?>
										</select>
									
								</div>
								</div>	
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('store'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <select  id="add_store_id" name="store_id" class="form-control"  required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                       foreach ($itemstores as $itemstore) {
                                            ?>
                                            <option value="<?php echo $itemstore->id; ?>"<?php
                                            if (isset($_POST['store_id']) && $_POST['store_id'] == $itemstore->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $itemstore->item_store; ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                            <div class="help-block"><?php echo form_error('store_id'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								 <!-- <div class="item form-group">                        
                                    
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span> <span id="item_unit"></span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
                                           <div class="">
                                        <span class="miplus">
                                            <select class="form-control" name="symbol">
                                                <option value="+">+</option>
                                                <option value="-">-</option>
                                            </select>
                                        </span>
                                        
                                            <input id="quantity" name="quantity" placeholder="" type="text" class="form-control "  value="<?php echo isset($_POST['quantity']) ?  $_POST['quantity'] : ''; ?>" required="required" />
                                        </div> 
                                        <div class="help-block"><?php echo form_error('quantity'); ?></div> 
                                </div> -->
								
								<!-- <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('purchase_price'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="purchase_price"  id="purchase_price" value="<?php echo isset($post['purchase_price']) ?  $_POST['purchase_price'] : ''; ?>" placeholder="<?php echo $this->lang->line('purchase_price'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('purchase_price'); ?></div> 
                                        </div>
                                    </div>                                                                       -->
								
							
									
							                                                                 
                                    <div id="addCommissionForm"></div>                                                                    
										<button type="button" class="btn btn-xs btn-primary" onclick="addItemGroup()" id="add_more_btn" >Add More</button>		
                                    <div class="item form-group">                        
                                    
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total :  
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control  col-md-7 col-xs-12" id="total" name="total" autocomplete="off" value="" readonly>										     
                                        <div class="help-block"><?php echo form_error('issue_date'); ?></div>
                    
                                    </div>    
                                </div>
                                    <div class="item form-group">                        
                                    
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Discount (%):  
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control  col-md-7 col-xs-12" id="discount" name="discount" autocomplete="off" value="">										     
                                            <div class="help-block"><?php echo form_error('issue_date'); ?></div>
                           
                                    </div>    
                                    </div>
                                    <div class="item form-group">                        
                                    
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Packing Charges :  
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control  col-md-7 col-xs-12" id="charges" name="charges" autocomplete="off" value="">										     
                                        <div class="help-block"><?php echo form_error('issue_date'); ?></div>
                       
                                        </div>
                                </div>    
                                <div class="item form-group accountfields">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Packing <?php echo $this->lang->line('credit_ledger'); ?> 
                                    </label>	
									<div class="col-md-6 col-sm-6 col-xs-12">										
                                        <select  class="form-control col-md-7 col-xs-12" name="charges_credit_ledger_id" id="charges_credit_ledger_id" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php
											foreach($account_ledgers as $ledger){ ?>
											<option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
											<?php  } ?>
										</select>
									
								</div>
								</div>	    
                                <div class="item form-group">                        
                                    
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Grand Total :  
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control  col-md-7 col-xs-12" id="grand_total" name="grand_total" autocomplete="off" value="" readonly>										     
                                            <div class="help-block"><?php echo form_error('issue_date'); ?></div>
                        
                                        </div>    
                                    </div>  
                                    <div class="item form-group">                        
                                    
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('attach_document'); ?> 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="btn btn-default btn-file">
                                        <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                        <input  class="form-control col-md-7 col-xs-12"  name="item_photo"  id="item_photo" type="file">
                                    </div>									
                            <div class="help-block"><?php echo form_error('item_photo'); ?></div>
                            </div>
                             </div>                                                                      
                        
                        <div class="item form-group">                        
                            
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('description'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name='description' class="form-control col-md-7 col-xs-12"><?php echo isset($_POST['description']) ?  $_POST['description'] : ''; ?></textarea>                                          
                                    <div class="help-block"><?php echo form_error('description'); ?></div> 
                                </div>
                            </div>     
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('itemstock/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
</div>
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_itemstock">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('itemstock/edit/'.$itemstock['id']), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
<?php $this->load->view('layout/school_list_edit_form'); ?>							   
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('category'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="edit_category_id" name="item_category_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                       
                                    </select>
                                            <div class="help-block"><?php echo form_error('item_category_id'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								 <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('item'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                    <select  id="item_id" name="item_id" class="form-control"  required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                    </select>
                                    <span class="text-danger"><?php echo form_error('item_id'); ?></span>
										</div>
                                    </div>                                                                      
								
								 <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('supplier'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <select  id="edit_supplier_id" name="supplier_id" class="form-control"  required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                       /* foreach ($itemsuppliers as $itemsup) {
                                            ?>
                                            <option value="<?php echo $itemsup->id; ?>"<?php
                                            if (isset($itemstock['supplier_id']) && $itemstock['supplier_id'] == $itemsup->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $itemsup->item_supplier; ?></option>

                                            <?php
                                        }*/
                                        ?>
                                    </select>
                                            <div class="help-block"><?php echo form_error('supplier_id'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('store'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <select  id="edit_store_id" name="store_id" class="form-control"  required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                       /* foreach ($itemstores as $itemstore) {
                                            ?>
                                            <option value="<?php echo $itemstore->id; ?>"<?php
                                            if (isset($itemstock['store_id']) && $itemstock['store_id'] == $itemstore->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $itemstore->item_store; ?></option>

                                            <?php
                                        }*/
                                        ?>
                                    </select>
                                            <div class="help-block"><?php echo form_error('store_id'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span> <span id="item_unit"></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="">
                                        <span class="miplus">
                                            <select class="form-control" name="symbol">
                                                <option value="+"<?php
                                            if (isset($itemstock['symbol']) && $itemstock['symbol'] == '+') {
                                                echo "selected = selected";
                                            }
                                            ?>>+</option>
                                                <option value="-" <?php
                                            if (isset($itemstock['symbol']) && $itemstock['symbol'] == '-') {
                                                echo "selected = selected";
                                            }
                                            ?>>-</option>
                                            </select>
                                        </span>
                                        <input id="quantity" name="quantity" placeholder="" type="text" class="form-control miplusinput"  value="<?php echo isset($itemstock['quantity']) ?  $itemstock['quantity'] : ''; ?>" required="required" />
                                    </div>
                                            <div class="help-block"><?php echo form_error('quantity'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('purchase_price'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="purchase_price"  id="purchase_price" value="<?php echo isset($itemstock['purchase_price']) ?  $itemstock['purchase_price'] : ''; ?>" placeholder="<?php echo $this->lang->line('purchase_price'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('purchase_price'); ?></div> 
                                        </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('date'); ?> 
                                    </label>
                                   <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control date col-md-7 col-xs-12" id="date" name="date" autocomplete="off" value="<?php echo isset($itemstock['date']) ?  date('m/d/Y',strtotime($itemstock['date'])) : ''; ?>">										     
                                        <div class="help-block"><?php echo form_error('date'); ?></div>
                                   
                                </div>
                                    </div>                                                                      
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('attach_document'); ?> 
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="item_photo"  id="item_photo" type="file">
                                            </div>									
									<div class="help-block"><?php echo form_error('item_photo'); ?></div>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="resume">&nbsp;</label>                                              
                                             <?php if(isset($itemstock['attachment']) && $itemstock['attachment']!= NULL){ 
											 $arr=explode("/",$itemstock['attachment']);
											 $link_name="Itemstock-".$arr[count($arr)-1];
											 ?>
                                            <a target="_blank" href="<?php echo site_url($itemstock['attachment']); ?>"><?php echo $link_name; ?></a> <br/>
                                             <?php } ?>  
                                        </div>
                                    </div> 
									 </div> 
																		 
								
								<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">><?php echo $this->lang->line('description'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<textarea name='description' class="form-control col-md-7 col-xs-12"><?php echo isset($itemstock['description']) ?  $itemstock['description'] : ''; ?></textarea>                                          
                                            <div class="help-block"><?php echo form_error('description'); ?></div> 
                                        </div>
                                    </div>                                                                      
													
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($itemstock) ? $itemstock['id'] : '' ?>" name="id" />
                                        <a href="<?php echo site_url('itemstock/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 <script type="text/javascript">   
	 var itemdropdown = '';
         var __item_id_no = 0;
         var base_url = '<?php echo base_url() ?>';
         var total_colspan = <?php echo $total_colspan ?>;

var __total_col = <?php echo $total_col ?>;
    $(document).ready(function() {
  
        $('#stock_adjustment').change(function(){
            if ($(this).is(':checked'))
            {
                $('.accountfields').hide();
            }
            else
            {
                $('.accountfields').show();
            }
        })
		 <?php 
		if(isset($_POST['item_id']))
		{
			$item_id_post = $_POST['item_id'];
		}
		else if(isset($itemstock['item_id'])){
			$item_id_post =$itemstock['item_id'];
		}
		else{
			$item_id_post =0;
		}
		?>
		var item_id_post=<?php echo $item_id_post; ?>;
		 //var item_id_post = '<?php echo isset($_POST['item_id'])? $_POST['item_id']: 0; ?>';
        item_id_post = (item_id_post != "") ? item_id_post : 0;
		<?php 
		if(isset($_POST['item_category_id']))
		{
			$item_category_id_post =$_POST['item_category_id'];
		}
		else if(isset($itemstock['item_category_id'])){
			$item_category_id_post =$itemstock['item_category_id'];
		}
		else{
			$item_category_id_post =0;
		}
		?>
		var item_category_id_post = <?php echo $item_category_id_post; ?>;
        //var item_category_id_post = '<?php echo isset($_POST['item_category_id'])? $_POST['item_category_id']: 0; ?>';
        item_category_id_post = (item_category_id_post != "") ? item_category_id_post : 0;
		<?php if(isset($add)){
			$form_id="form#add ";
		}
		else if(isset($edit)){
			$form_id="form#edit ";
		}
		?>
		var form_id='<?php echo $form_id; ?>';
        
		// $(document).on('change', '#add_category_id,#edit_category_id', function (e) {
        //     $(form_id+'#item_id').html("");
        //     var item_category_id = $(this).val();
        //     populateItem(0, item_category_id);
        // });

       
			 var date_format = '<?php echo $result    = strtr('d/m/Y', ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
       var date_format_js = '<?php echo $result = strtr('d/m/Y', ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';        
		$('.date').datepicker({
            format: date_format,
            startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
             autoclose: true,
                language: '<?php echo $language_name ?>'
        });
        <?php
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){   ?>
            var total_colspan = '10';
        <?php }else{ ?>
            var total_colspan = '10';
        <?php } ?>

          $('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
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
						"targets": [4,5,6] ,
                        "visible": false,
					}
            ],
              search: true,              
              responsive: true,
              "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                console.log(i,typeof i)
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( total_colspan)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            total_page = api
                .column( total_colspan, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( total_colspan ).footer() ).html(
                total_page +' ('+ total +')'
            );
            // Update footer
           
        }
          });
        });

        
    //    $("#add").validate();  
    //    $("#edit").validate();  
</script>
<script type="text/javascript">
     $(document).on('change', '.item_id', function (e) {
        $('.div_avail').hide();
        var item_id = $(this).val();
        var div_id = $(this).data('id');
        availableQuantity(item_id,div_id);

    });
      function availableQuantity(item_id,div_id) {
        if (item_id != "") {
            $('.item_available_quantity').html("");
            var div_data = '';
            $.ajax({
                type: "GET",
                url: base_url + "item/getAvailQuantity",
                data: {'item_id': item_id},
                dataType: "json",
                async  : true,
                success: function (data) {
                    if(data.available >0)
                    {
                        $('#item_available_quantity_'+div_id).html(data.available);
                        $('.div_avail').show();
                    }
                    else 
                    {
                        $('.item_option').removeAttr('selected');
                        $('#item_id').val('')
                        alert('Item Not available');
                    }
                    if(data.last_price >0)
                    {
                        $('#price_'+div_id).val(data.last_price);
                        
                    }
                    if(data.last_mrp >0)
                    {
                        $('#price_mrp_'+div_id).val(data.last_mrp);
                        
                    }
                   
                }

            });
        }
    }
    var edit = false;
         
    $("document").ready(function() {
		<?php if(isset($filter_school_id) && $filter_school_id>=0){ ?>		 
		 if($("#edit_school_id").length == 0) {			 
             $(".fn_school_id").trigger('change');			 
		 }
		 else{			 
			 $("#edit_school_id").trigger('change');	
		 }
		<?php } ?>
       
    });
    $(document).on('keyup','.price_input', function(){ 
        if(this.value != "")
        {
            $(this).val( (this.value).replace(/[^\d.-]/g, ''))
        }
        else
        {
            $(this).val("");
        }
        calculate_total();
    })
    $(document).on('keyup','.quantity_input', function(){ 
        if(this.value != "")
        {
            $(this).val( (this.value).replace(/[^\d.-]/g, ''))
        }
        else
        {
            $(this).val("");
        }
        calculate_total();
    })
    $(document).on('keyup','#discount', function(){ 
        if(this.value != "")
        {
            $(this).val( (this.value).replace(/[^\d.-]/g, ''))
        }
        else
        {
            $(this).val("");
        }
        calculate_total();
    })
    $(document).on('keyup','#discount', function(){ 
        calculate_grandtotal()
    })
    $(document).on('keyup','#charges', function(){ 
        calculate_grandtotal()
    })
    
    function calculate_total()
    {
        var total = 0;
        $('.price_input').each(function(i, obj) {
             var  item_id = $(obj).data('id') ;
             var price = $(obj).val()   ;
             var  quantity = $('#quantity_'+item_id).val();
             var price =  isNaN(price)  ? 0 : parseFloat(price);
             var  quantity =  isNaN(quantity) ? 0  : parseFloat(quantity);
             var item_total = (quantity*price);
             if(isNaN(item_total))
             {
                item_total = 0;
             }
             total = total + item_total;
          

        });
        
       $('#total').val(total.toFixed(2))
       calculate_grandtotal(total)

    }
    function calculate_grandtotal()
    {
        var total = $('#total').val();
        var discount = $('#discount').val();
        var charges = $('#charges').val();
        var discount_value = 0;
        if(isNaN(discount) || discount == "")
        {
            discount = 0;
        }
        else
        {
            discount = parseFloat(discount);
        }
        if(discount > 0)
        {
           var  discount_value =  ( total *( discount/100 ))
        }
        if(isNaN(charges) || charges == "")
        {
            charges = 0;
        }
        else
        {
            charges = parseFloat(charges);
        }
        if(isNaN(total) || total == "")
        {
            total = 0;
        }
        else
        {
            total = parseFloat(total);
        }
       var grand_total = (total - discount_value +charges)
       
       grand_total = grand_total.toFixed(2);
       $('#grand_total').val(grand_total)
    }
    <?php if(isset($itemstock) && !empty($itemstock)){ ?>
          edit = true; 
    <?php } ?>
    $(document).on('change', '#item_id', function (e) {
        $('.div_avail').hide();
        var item_id = $(this).val();
        var div_id = $(this).data('id');
        availableQuantity(item_id,div_id);
        getItemUnit(item_id,div_id);

    });
    function getItemUnit(item_id,div_id)
          {
            $.ajax({
                    type: "GET",
                    url: base_url + "itemstock/getItemunit",
                    data: {'id': item_id},
                    dataType: "json",
                    async  : true,
                    success: function (data) {
                       $('#item_unit_'+div_id).html(data.unit); 
                    }

                });
          
        };
	function availableQuantity(item_id,div_id) {
        if (item_id != "") {
            $('.item_available_quantity').html("");
            var div_data = '';
            $.ajax({
                type: "GET",
                url: base_url + "item/getAvailQuantity",
                data: {'item_id': item_id},
                dataType: "json",
                async  : true,
                success: function (data) {

                    $('#item_available_quantity_'+div_id).html(data.available);
                    $('.div_avail').show();
                }

            });
        }
    }
    $('.fn_school_id').on('change', function(){      
        var school_id = $(this).val();        
        var category_id = '';
		var supplier_id = '';
       var store_id = '';
	   var debit_ledger_id='';		
		var credit_ledger_id='';
        
        <?php if(isset($edit) && !empty($edit)){ ?>
            supplier_id =  '<?php echo $itemstock['supplier_id']; ?>';  
			store_id =  '<?php echo $itemstock['store_id']; ?>';  
				category_id =  '<?php echo $itemstock['item_category_id']; ?>';  
				debit_ledger_id =  '<?php echo $itemstock['debit_ledger_id']; ?>';			
			credit_ledger_id =  '<?php echo $itemstock['credit_ledger_id']; ?>';
         <?php } ?> 
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
        __item_id_no =0;

                $('#addCommissionForm').html('');
        calculate_total();
        populateItem(school_id);
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_itemcategory_by_school'); ?>",
            data   : { school_id:school_id, category_id:category_id},               
            async  : true,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_category_id').html(response);   
                   }else{
                       $('#add_category_id').html(response);   
                   }                                                      
               }
            }
        });
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_itemsupplier_by_school'); ?>",
            data   : { school_id:school_id, supplier_id:supplier_id},               
            async  : true,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_supplier_id').html(response);   
                   }else{
                       $('#add_supplier_id').html(response);   
                   }                                                      
               }
            }
        });
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_itemstore_by_school'); ?>",
            data   : { school_id:school_id, store_id:store_id},               
            async  : true,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_store_id').html(response);   
                   }else{
                       $('#add_store_id').html(response);   
                   }                                                      
               }
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_voucher_by_school'); ?>",
            data: {
                school_id: school_id,
            },
            async: true,
            success: function(response) {
                if (response) {
                        $('#voucher_id').html(response);
                }
            }
        });
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data   : { school_id:school_id, ledger_id:credit_ledger_id},               
            async  : true,
            success: function(response){                                                   
               if(response)
               {  
                   if(credit_ledger_id){
                       $('#edit_credit_ledger_id').html(response);   
                       $('#edit_debit_ledger_id').html(response);  
                   }else{
                       $('#add_credit_ledger_id').html(response);  
                        $('#add_debit_ledger_id').html(response);  
                       $('#charges_credit_ledger_id').html(response);   
 
                   }                                    
               }
            }
        });	
    }); 
       function get_itemstock_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 
        function populateItem(school_id) {
            if (school_id != "") {

               
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "POST",
                    url:  '<?php echo site_url("itemstock/getItemBySchool"); ?>',
                    data: {'school_id': school_id},
                    dataType: "json",
                    async  : true,
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var select = "";
                           
                            div_data += '<option data-unit="'+obj.unit+'" value="'+obj.id  +'" >'+ obj.name+ ( obj.item_code ? '  ['+obj.item_code +']' : '')+'</option>';
                        });
                        itemdropdown = div_data;
                        addItemGroup()

                        $('#item_id').html(div_data);
                    }

                });
            }
        }
        function addItemGroup(){
            addItemGroup1()

        }

        function addItemGroup1()
        {
            __item_id_no++;
            var element = '<div id="item_id_'+__item_id_no+'"> <div class="item form-group"> ';
            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('item'); ?><span class="required">*</span></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += '<select  id="item_id'+__item_id_no+'" data-id="'+__item_id_no+'" name="item_id[]" class="item_id form-control dropdowns_'+__item_id_no+'"  required="required">';
            element += itemdropdown;
            element += '</select>';
            element += ' <span class="text-danger"><?php echo form_error('item_id'); ?></span>';
            element += '</div>';
            element += ' </div>';

            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12"  for="name"><?php echo $this->lang->line('mrp'); ?></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += ' <div class=""> ';
            element += '  <input  name="mrp[]" placeholder="" id="price_mrp_'+__item_id_no+'" data-id="'+__item_id_no+'"  type="text" class="form-control " />';
            element += '</div>';
            element += '</div>';
            element += '</div>';

            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12"  for="name"><?php echo $this->lang->line('purchase_price'); ?></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += ' <div class=""> ';
            element += '  <input  name="purchase_price[]" placeholder="" id="price_'+__item_id_no+'" data-id="'+__item_id_no+'"  type="text" class="form-control price_input" />';
            element += '</div>';
            element += '</div>';
            element += '</div>';



            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span>  <span id="item_unit_'+__item_id_no+'"></span></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += ' <div class=""> ';
            element += '  <input  name="quantity[]" data-id="'+__item_id_no+'" id="quantity_'+__item_id_no+'"  placeholder="" type="text" class="form-control quantity_input"  value="<?php echo isset($_POST['quantity']) ?  $_POST['quantity'] : ''; ?>" required="required" />';
            element += '<div class="div_avail">';
            element += ' <span>Available Quantity : </span>';
            element += ' <span id="item_available_quantity_'+__item_id_no+'">0</span>';
            element += '</div>';
            element += '</div>';
            element += ' <div class="help-block"><?php echo form_error('quantity'); ?></div>';
            element += '</div>';
            if(__item_id_no != 1) element += '<button type="button" class="btn btn-xs btn-danger" onclick="deleteItem('+__item_id_no+')"  >Remove</button>	';
            element += '</div></div>';

            $('#addCommissionForm').append(element);
            $('.dropdowns_'+__item_id_no).select2();

        }
        function deleteItem(id)
        {
            $('#item_id_'+id).remove();
            calculate_total();

        }

  </script>