  <div class="item form-group">          
         <div class="item form-group">                        
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('item'); ?> <span class="required">*</span></label>
        				<div class="col-md-6 col-sm-6 col-xs-12">
                             <select  id="item_id" name="item_id[]" class="form-control item_id"  required='required'>

                             </select>
                              <span class="text-danger"><?php echo form_error('item_id'); ?></span>
        				</div>
        </div>   

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"
                for="name"><?php echo $this->lang->line('price'); ?></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="">
                    <input name="price[]" placeholder="" type="text" class="form-control " />
                </div>
            </div>
        </div> 

        <div class="item form-group">                        
                                            
             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span></label>
        				<div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="">                                     
                                <input id="quantity" name="quantity[]" placeholder="" type="text" class="form-control "  value="<?php echo isset($_POST['quantity']) ?  $_POST['quantity'] : ''; ?>" required="required" />
        							<div class="div_avail">
        	                            <span>Available Quantity : </span>
        	                             <span class="item_available_quantity">0</span>
                                     </div>
                            </div>
                             <div class="help-block"><?php echo form_error('quantity'); ?></div> 
                        </div>
        </div> 
        <button type="button" class="btn btn_primary" id="harry1">Add More</button>
  </div>     


       