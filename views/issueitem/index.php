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
.list2 li span {
    width: 70%;
    float: right;
    padding-left: 10px;    
    font-weight: normal;

}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_item')." ".$this->lang->line('issue'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>     
            
            <?php  $this->load->view('layout/item-quicklinks');   ?>
  
            
            <div class="x_content">
            <?php
            if ($school_id)
            {
           echo form_open_multipart(site_url('/issueitem/index'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), '');
           $class_name ="";
           $fee_type_name ="";
       ?>
       
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="hidden" name="school_id" value="<?php echo $school_id ?? 0 ?>"> 
                                <div class="form-group item">
                                    <div>Voucher</div>

                                    <select  class="form-control col-md-7 col-xs-12" name="voucher_id" id="voucher_id_filter" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php 
                                            foreach ($vouchers as $obj)
                                            {
                                                $selected = $voucher_id == $obj->id ? "selected" : '';
                                                echo '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name."(".$obj->category.")" . '</option>';
                                            }
                                            ?>
										</select>	
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group"><br/>
                                     <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                                 </div>
                             </div>
                        
                        <?php echo form_close();
                        } ?>
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'inventory', 'issueitem')){ ?>
                            <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('issueitem'); ?>"  ><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('issueitem'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'inventory', 'issueitem')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('issueitem/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('issueitem'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('issueitem/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('issueitem'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_itemissue"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('item'); ?></a> </li>                          
                        <?php } ?> 
						 <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_itemissue_by_school(this.value);">
                                    <option value="<?php echo site_url('issueitem/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('issueitem/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_itemissue_list" >
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
                                        <!-- <th>Issue Item</th> -->
                                        <!-- <th><?php echo $this->lang->line('debit_ledger'); ?></th>  
                                        <th><?php echo $this->lang->line('credit_ledger'); ?></th>   -->
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('item'); ?></th> 
                                        <th><?php echo $this->lang->line('item_code'); ?></th>  
                                        <th><?php echo $this->lang->line('mrp'); ?></th>
                                        <th><?php echo $this->lang->line('sell_price'); ?></th>
                                        <th><?php echo $this->lang->line('quantity'); ?></th>										

                                        <th><?php echo $this->lang->line('total'); ?></th>										
                                        <!-- <th><?php echo $this->lang->line('issue_return'); ?></th> -->
										<th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($itemissue) && !empty($itemissue)){ ?>
                                        <?php foreach($itemissue as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){ ?>
											<td><?php echo $obj['school_name']; ?></td>
											<?php } ?>
                                            <td><?php echo date( 'Y-m-d',strtotime($obj['issue_date'])); ?></td>
                                            <td><?php echo $obj['invoice_no'] ?></td>
                                            <!-- <td><?php echo $obj['issue_type']  == 1 ? "Return" : ($obj['issue_type']  == 2 ?  "Sell": ""  );  ?></td> -->
                                            <!-- <td><?php echo $obj['debit_ledger'] ?></td>
                                            <td><?php echo $obj['credit_ledger'] ?></td> -->
                                            <td><?php echo $obj['item_category']; ?></td>
                                            <td><?php echo $obj['item_name']; ?></td>
                                            <td><?php echo $obj['item_code']; ?></td>
                                            <td><?php echo $obj['mrp']; ?></td>
                                            <td><?php echo $obj['issue_price']; ?></td>

                                            <td><?php echo $obj['quantity']; ?></td>
                                            <td><?php echo $obj['issue_price']*$obj['quantity']; ?></td>

											<!-- <td><?php 
											if($obj['return_date'] != null){
											echo date('d-m-Y',strtotime($obj['return_date'])); } 
                                            ?></td> -->
											<td>	<?php if ($obj['is_returned'] == 0) {
                                                        ?>


                                                        <span class="label label-danger item_remove" data-item="<?php echo $obj['id'] ?>" data-category="<?php echo $obj['item_category'] ?>" data-item_name="<?php echo $obj['item_name'] ?>" data-quantity="<?php echo $obj['quantity'] ?>" data-toggle="modal" data-target="#confirm-delete"><?php echo $this->lang->line('click_to_return'); ?></span>

                                                    <?php  } else if($obj['is_returned'] == 2) { ?>

                                                  <span class="label label-danger" 
                                                 <?php echo $this->lang->line('Sell'); ?>>Sell</span> 

                                                   <?php }else { ?>

                                                        <span class="label label-success"><?php echo $this->lang->line('returned'); ?></span>

                                                        <?php
                                                    }
                                                    ?></td>
											<td>
										
										                                                                                           
                                                <?php if(has_permission(DELETE, 'inventory', 'issueitem')){ ?>
                                                    <a href="<?php echo site_url('/issueitem/delete/'.$obj['id']); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(VIEW, 'inventory', 'issueitem')){ ?>
                                                    <a href="<?php echo site_url('/issueitem/view/'.$obj['invoice_id']); ?>"  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" style="text-align:right">Total:</th>
                                       
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_itemissue">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('issueitem/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>                                                                                              <?php $this->load->view('layout/school_list_form'); ?>     
                                                                                           
								 
								 <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('issue_date'); ?> <span class="required">*</span>
                                    </label>
                                   <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control date col-md-7 col-xs-12" id="date" name="issue_date" autocomplete="off" value="<?php echo isset($itemissue['issue_date']) ?  date('d/m/Y',strtotime($itemissue['issue_date'])) : ''; ?>" required="requires">										     
                                        <div class="help-block"><?php echo form_error('issue_date'); ?></div>
                                   
                                </div>
                                    </div>    

                                         <div class="item form-group">                        
                                    
                                           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('issue_type'); ?>Issue Type <span class="required">*</span></label>  
                                             </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  id="issue_type" name="issue_type" class="form-control" required="requires">
                                        <option value="0"><?php echo $this->lang->line('select'); ?></option>
                                        <option value="2"><?php echo $this->lang->line('sell'); ?>Sell</option>
                                        <option value="1"><?php echo $this->lang->line('return'); ?></option>
                                    </select>
                                         <div class="help-block"><?php echo form_error('issue_to'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('voucher'); ?> <span class="required">*</span>
                                    </label> 
									<div class="col-md-6 col-sm-6 col-xs-12">									
                                        <select  class="form-control col-md-7 col-xs-12" name="voucher_id" id="voucher_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											
										</select>									
									</div>									
									</div>			     
                                    <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('debit_ledger'); ?>  <span class="required">*</span>
                                    </label> 
									<div class="col-md-6 col-sm-6 col-xs-12">									
                                        <select  class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="add_debit_ledger_id" required="requires">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php
											foreach($account_ledgers as $ledger){ ?>
											<option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
											<?php  } ?>
										</select>									
									</div>									
									</div>									
                                    <div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('credit_ledger'); ?>  <span class="required">*</span>
                                    </label>	
									<div class="col-md-6 col-sm-6 col-xs-12">										
                                        <select  class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="add_credit_ledger_id" required="requires">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
											<?php
											foreach($account_ledgers as $ledger){ ?>
											<option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
											<?php  } ?>
										</select>
									
								</div>
								</div>	
								
								                                                            
								
                               
                               
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
                                <div class="item form-group">
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
                                    
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('remark'); ?> :</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name='note' class="form-control col-md-7 col-xs-12"><?php echo isset($itemissue['note']) ?  $itemissue['note'] : ''; ?></textarea>                                          
                                    <div class="help-block"><?php echo form_error('note'); ?></div> 
                                </div>
                            </div>      
                                                                                      
								
 <!-- <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('category'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="item_category_id" name="item_category_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                         <?php
                                        // foreach ($itemcategories as $item_category) {
                                        //     ?>
                                        //     <option value="<?php echo $item_category->id ?>"<?php
                                        //     if (isset($_POST['item_category_id']) && $_POST['item_category_id'] == $item_category->id) {
                                        //         echo "selected = selected";
                                        //     }
                                        //     ?>><?php echo $item_category->item_category ?></option>

                                            <?php
                                        // }
                                        ?>
                                    </select>
                                            <div class="help-block"><?php echo form_error('item_category_id'); ?></div> 
                                        </div>
                                    </div>                                                                       -->
								
								<!--   <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('item'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                    <select  id="item_id" name="item_id" class="form-control"  required='required'  size="5" multiple >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                    </select>
                                    <span class="text-danger"><?php echo form_error('item_id'); ?></span>
										</div>
                                    </div>   -->                                                                    
										
                    <!-- <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="">                                     
                                        <input id="quantity" name="quantity" placeholder="" type="text" class="form-control "  value="<?php echo isset($_POST['quantity']) ?  $_POST['quantity'] : ''; ?>" required="required" />
										<div id="div_avail">
                                                <span>Available Quantity : </span>
                                                <span id="item_available_quantity">0</span>
                                            </div>
                                    </div>
                                            <div class="help-block"><?php echo form_error('quantity'); ?></div> 
                                        </div>
                                    </div>   -->



                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('itemissue/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
</div>
                    
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirm_return'); ?></h4>
            </div>

            <div class="modal-body">
                <input type="hidden" id="item_issue_id" name="item_issue_id" value="">
                <p>Are you sure to return this item !</p>

                <ul class="list2">
                    <li><?php echo $this->lang->line('item'); ?><span id="modal_item"></span></li>
                    <li><?php echo $this->lang->line('item_category'); ?><span id="modal_item_cat"></span></li>
                    <li><?php echo $this->lang->line('quantity'); ?><span id="modal_item_quantity"></span></li>
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-success cfees btn-ok" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('return'); ?></a>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
    var __item_id_no =0;

$(document).ready(function () {
        $('#confirm-delete').on('show.bs.modal', function (e) {
            $('#item_issue_id').val("");
            $('.debug-url').html('');
            $('#modal_item_quantity,#modal_item,#modal_item_cat').text("");
            var item_issue_id = $(e.relatedTarget).data('item');
            var item_category = $(e.relatedTarget).data('category');
            var quantity = $(e.relatedTarget).data('quantity');
            var item_name = $(e.relatedTarget).data('item_name');

            var itemdropdown = '';
            $('#item_issue_id').val(item_issue_id);
            $('#modal_item_cat').text(item_category);
            $('#modal_item').text(item_name);
            $('#modal_item_quantity').text(quantity);

        });
        $("#confirm-delete").modal({
            backdrop: false,
            show: false

        });
		 });
		 $(document).on('click', '.btn-ok', function () {
        var $this = $('.btn-ok');
        $this.button('loading');
        var item_issue_id = $('#item_issue_id').val();
        $.ajax(
                {
                    url: "<?php echo site_url('issueitem/returnItem') ?>",
                    type: "POST",
                    data: {'item_issue_id': item_issue_id},
                    dataType: 'Json',
                    success: function (data, textStatus, jqXHR)
                    {
                        if (data.status == "fail") {

                            errorMsg(data.message);
                        } else {
                            successMsg(data.message);
                            //  $("span[data-item='" + item_issue_id + "']").removeClass("label-danger").addClass("label-success").text("Returned");

                            $("#confirm-delete").modal('hide');
                            location.reload();
                        }

                        $this.button('reset');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        $this.button('reset');
                    }
                });

    });
	function successMsg(msg) {
     toastr.success(msg);
 }
    
 function errorMsg(msg) {
     toastr.error(msg);
 }
</script>
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 <script type="text/javascript">   
var base_url = '<?php echo base_url() ?>';
    $(document).ready(function() {
		 
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
       // populateItem(item_id_post, item_category_id_post);
		<?php if(isset($add)){
			$form_id="form#add ";
		}
		else if(isset($edit)){
			$form_id="form#edit ";
		}
		?>
		var form_id='<?php echo $form_id; ?>';
        function populateItem1(item_id_post, item_category_id_post) {
            if (item_category_id_post != "") {
                $(form_id+'#item_id').html("");

               var $addCommissionForm = $('#addCommissionForm');
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: base_url + "itemstock/getItemByCategory",
                    data: {'item_category_id': item_category_id_post},
                    dataType: "json",
                    async  : true,
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var select = "";
                            if (item_id_post == obj.id) {
                                var select = "selected=selected";
                            }
                            div_data += "<option value=" + obj.id + " " + select + ">" + obj.name + "</option>";
                             
                               $.ajax({
                              url: base_url + "itemstock/addCommissionForm",
                               type: 'POST',
                               async  : true,
                              data: {"aepsCommissionForm": onload, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
                              beforeSend: function(){
                              //  $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
                              },
                              success: function(data) {
                                $addCommissionForm.append(data);

                                $(document).on('click', '#harry1', function (e) {

                                              var element = ' <div class="item form-group"> ';
                                              element += ' <div class="item form-group"> ';
                                              element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('item'); ?><span class="required">*</span></label>';
                                              element += '<div class="col-md-6 col-sm-6 col-xs-12">';
                                              element += '<select  id="item_id" name="item_id[]" class="form-control item_id"  required="required">';
                                              element += div_data;
                                              element += '</select>';
                                              element += ' <span class="text-danger"><?php echo form_error('item_id'); ?></span>';
                                              element += '</div>';
                                              element += ' </div>';



                                              element += ' <div class="item form-group"> ';
                                              element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('price'); ?></label>';
                                              element += '<div class="col-md-6 col-sm-6 col-xs-12 ">';
                                              element += ' <div class=""> ';
                                              element += '  <input  name="price[]" placeholder="" type="text" class="form-control price_input" />';
                                              element += '</div>';
                                              element += '</div>';
                                              element += '</div>';



                                              element += ' <div class="item form-group"> ';
                                              element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span></label>';
                                              element += '<div class="col-md-6 col-sm-6 col-xs-12">';
                                              element += ' <div class=""> ';
                                              element += '  <input id="quantity" name="quantity[]" placeholder="" type="text" class="form-control quantity_input"  value="<?php echo isset($_POST['quantity']) ?  $_POST['quantity'] : ''; ?>" required="required" />';
                                              element += '<div class="div_avail">';
                                              element += ' <span>Available Quantity : </span>';
                                              element += ' <span class="item_available_quantity">0</span>';
                                              element += '</div>';
                                              element += '</div>';
                                              element += ' <div class="help-block"><?php echo form_error('quantity'); ?></div>';
                                              element += '</div>';
                                              element += '</div>';


                                          
                                              // element += '<button class="btn btn-primary remove" >-</button>'
                                              element += '</div>';


                                              $('#addCommissionForm').append(element);


                                              

                                    });


                                $(form_id+'.item_id').append(div_data);
                              },
                            })


                        });


                         


                    }

                });
            }
        }
		$(document).on('change', '#item_category_id', function (e) {
            $(form_id+'#item_id').html("");
            var item_category_id = $(this).val();
            //populateItem(0, item_category_id);
        });


         
          $(document).on('click', '.remove', function() {
              $(this).parent().parent().remove();
            });



        $(document).on('change', '#item_id', function (e) {
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
                    if(data.last_mrp >0)
                    {
                        $('#price_'+div_id).val(data.last_mrp);
                        
                    }
                    if(data.last_mrp >0)
                    {
                        $('#price_mrp_'+div_id).val(data.last_mrp);
                        
                    }
                   
                }

            });
        }
    }
	
    var date_format = '<?php echo $result    = strtr('d/m/Y', ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
       var date_format_js = '<?php echo $result = strtr('d/m/Y', ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';        
		$('.date').datepicker({
            format: date_format,
            startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
             autoclose: true,
                language: '<?php echo $language_name ?>'
        });
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
              "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 9)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            total_page = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( 9 ).footer() ).html(
                total_page +' ('+ total +')'
            );
            // Update footer
           
        },
              search: true,              
              responsive: true
          });
        });
		$('.fn_school_id').on('change', function(){
			 var school_id = $(this).val();
			var user_type=$("#issue_type").val();
			var category_id = '';
       
			getIssueUser(user_type);
			if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_itemcategory_by_school'); ?>",
            data   : { school_id:school_id, category_id:category_id},               
            async  : true,
            success: function(response){                                                   
               if(response)
               {                     
                       $('#item_category_id').html(response);   
                   
               }
            }
        });
			
		});
    function getIssueUser(usertype) {
        $('#issue_to').html("");
		var school_id=$("#add_school_id").val();
        var div_data = "";
		if(school_id!='' && usertype!= ''){	
        $.ajax({
            type: "POST",
            url: base_url + "issueitem/getUser",
            data: {'usertype': usertype,'school_id':school_id},
            dataType: "json",
            async  : true,
            success: function (data) {

                $.each(data.result, function (i, obj)
                {
                    //if (data.usertype == "admin") {
                        name = obj.username;
                    //} else {
                     //   name = obj.name+" "+obj.surname+" ("+obj.employee_id+")";

                    //}
                    div_data += "<option class='item_option' value=" + obj.id + ">" + name + "</option>";
                });
                $('#issue_to').append(div_data);
            }

        });
		}
    }
	   function get_itemissue_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    } 
</script>
<script type="text/javascript">
var edit = false;
$('#add_more_btn').hide();
    $("document").ready(function() {
		<?php if(isset($filter_school_id) && $filter_school_id>=0 && !isset($list_items)){ ?>	
            $('#add_more_btn').show();	 
		 if($("#edit_school_id").length == 0) {			 
             $(".fn_school_id").trigger('change');			 
		 }
		 else{			 
			 $(".fn_school_id").trigger('change');	
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
       var grand_total = (total - discount_value +charges).toFixed(2)
       
       
       $('#grand_total').val(grand_total)
    }
    $('.fn_school_id').on('change', function(){      
        var school_id = $(this).val();                
	   var debit_ledger_id='';		
		var credit_ledger_id='';
        
       
        
        if(!school_id){
            $('#add_more_btn').hide();
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
           
        }
        __item_id_no =0;

        $('#add_more_btn').show();
        $('#addCommissionForm').html('');
        calculate_total();
        populateItem(school_id);
        
		// $.ajax({       
        //     type   : "POST",
        //     url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
        //     data   : { school_id:school_id, ledger_id:debit_ledger_id},               
        //     async  : false,
        //     success: function(response){                                                   
        //        if(response)
        //        {  
        //            if(debit_ledger_id){
        //                $('#edit_debit_ledger_id').html(response);   
        //            }else{
        //                $('#add_debit_ledger_id').html(response);   
        //            }                                    
        //        }
        //     }
        // });	
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
                           
                            div_data += '<option data-unit="'+obj.unit+'" value="'+obj.id  +'" >'+ obj.name+ ( obj.item_code ? ' ['+obj.item_code +']' : '')+'</option>';
                        });
                        itemdropdown = div_data;
                        addItemGroup()
                        $('#item_id').html(div_data);
                    }

                });
            }
        }
        function addItemGroup()
        {
            
            __item_id_no++;
            var element = '<div id="item_id_'+__item_id_no+'"> <div class="item form-group"> ';
            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('item'); ?><span class="required">*</span></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += '<select  id="item_id" data-id="'+__item_id_no+'" name="item_id[]" class="form-control item_id select22 col-md-7 col-xs-12"  required="required">';
            element += itemdropdown;
            element += '</select>';
            element += ' <span class="text-danger"><?php echo form_error('item_id'); ?></span>';
            element += '</div>';
            element += ' </div>';

            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('mrp'); ?> <span class="required">*</span></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += ' <div class=""> ';
            element += '  <input  name="mrp[]" placeholder=""  id="price_mrp_'+__item_id_no+'" data-id="'+__item_id_no+'"  value="0" type="text" class="form-control " required="required" readonly/>';
            element += '</div>';
            element += '</div>';
            element += '</div>';
            
            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('sell_price'); ?> <span class="required">*</span></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += ' <div class=""> ';
            element += '  <input  name="price[]" placeholder="" id="price_'+__item_id_no+'" data-id="'+__item_id_no+'"  value="0" type="text" class="form-control price_input" required="required" readonly/>';
            element += '</div>';
            element += '</div>';
            element += '</div>';



            element += ' <div class="item form-group"> ';
            element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span></label>';
            element += '<div class="col-md-6 col-sm-6 col-xs-12">';
            element += ' <div class=""> ';
            element += '  <input  name="quantity[]" placeholder="" data-id="'+__item_id_no+'" id="quantity_'+__item_id_no+'"  type="text" class="form-control quantity_input"  value="0" required="required" />';
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
            $('.select22').select2();

        }
        function deleteItem(id)
        {
            
            $('#item_id_'+id).remove();
            calculate_total();
        }
</script>