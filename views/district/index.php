<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('district'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>             
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">                       
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('subzone'); ?> <?php echo $this->lang->line('list'); ?></a> </li>                       
                       
                      
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('zone/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('district'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add" role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('district'); ?></a> </li>                          
                             <?php } ?>
                                        
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('district'); ?></a> </li>                          
                        <?php } ?> 					
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('state'); ?></th>
										<th><?php echo $this->lang->line('zone'); ?></th>
										<th><?php echo $this->lang->line('subzone'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($district) && !empty($district)){ ?>
                                        <?php foreach($district as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>										
                                            <td><?php echo $obj->name; ?></td>
											<td><?php echo $obj->state; ?></td>
											<td><?php echo $obj->zone; ?></td>
											<td><?php echo $obj->subzone; ?></td>
                                            <td>                                                 
                                               
                                                    <a href="<?php echo site_url('district/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>                                               
                                                    <a href="<?php echo site_url('district/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('district/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>         
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>                                                                      		<div class="item form-group">                        
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('state'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="add_state_id" name="state_id" class="form-control fn_state_id" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($states as $s){ ?>
											<option value="<?php print $s->id; ?>"><?php print $s->name; ?></option>
										<?php }?>
										</select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div>	
									<div class="item form-group">                        
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('zone'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="add_zone_id" name="zone_id" class="form-control fn_zone_id" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('zone'); ?></div> 
                                        </div>
                                    </div>	
									<div class="item form-group">                        
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('subzone'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="add_subzone_id" name="subzone_id" class="form-control fn_subzone_id" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('subzone'); ?></div> 
                                        </div>
                                    </div>	
                                                                 					
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('district/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
</div>
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('district/edit/'.$detail->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>  
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($detail) ? $detail->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>     <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('state'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                             <select autofocus="" id="edit_state_id" name="state_id" class="form-control fn_state_id" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($states as $s){ ?>
											<option value="<?php print $s->id; ?>" <?php echo($detail->state_id == $s->id)? "selected='selected'" : ""; ?>><?php print $s->name; ?></option>
										<?php }?>
										</select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div><div class="item form-group">                        
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('zone'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="edit_zone_id" name="zone_id" class="form-control fn_zone_id" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('zone'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('subzone'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="edit_subzone_id" name="subzone_id" class="form-control fn_subzone_id" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('subzone'); ?></div> 
                                        </div>
                                    </div>	
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($detail) ? $detail->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('district/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
var edit = false;
    $(document).ready(function() {
		 <?php if(isset($edit) && !empty($edit)){ ?>
			edit=true;
           $("#edit_state_id").trigger('change');         
         <?php } ?>	
	});	
$('.fn_state_id').on('change', function(){
      
        var state_id = $(this).val();
		var district_id = '';
		var zone_id='';
		<?php if(isset($edit) && !empty($edit)){ ?>
            district_id =  '<?php echo $detail->id; ?>';           
			zone_id =  '<?php echo $detail->zone_id; ?>';           
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
					   $("#edit_zone_id").trigger('change'); 
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
            subzone_id =  '<?php echo $detail->subzone_id; ?>';           
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
                   }else{
                       $('#add_subzone_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});
</script>
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
              
</script>