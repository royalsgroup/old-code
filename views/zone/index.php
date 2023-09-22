<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('zone'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>         
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
            
             <a href="<?php echo site_url('zone'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('zone'); ?></a>|
             <a href="<?php echo site_url('subzone'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('subzone'); ?></a>|
             <a href="<?php echo site_url('district'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('district'); ?></a>|
             <a href="<?php echo site_url('block'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('block'); ?></a>|
             <a href="<?php echo site_url('sankul'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('sankul'); ?></a> 
            </div>    
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">                       
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('zone'); ?> <?php echo $this->lang->line('list'); ?></a> </li>                       
                       
                      
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('zone/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('zone'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add" role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('zone'); ?></a> </li>                          
                             <?php } ?>
                                        
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('zone'); ?></a> </li>                          
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
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($zone) && !empty($zone)){ ?>
                                        <?php foreach($zone as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>										
                                            <td><?php echo $obj->name; ?></td>
											<td><?php echo $obj->state; ?></td>
                                            <td>                                                 
                                               
                                                    <a href="<?php echo site_url('zone/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>                                               
                                                    <a href="<?php echo site_url('zone/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
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
                               <?php echo form_open_multipart(site_url('zone/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>         
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>                                                                      		<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('state'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="add_state_id" name="state_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($states as $s){ ?>
											<option value="<?php print $s->id; ?>"><?php print $s->name; ?></option>
										<?php }?>
										</select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div>	
                                                                 					
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('zone/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
                               <?php echo form_open_multipart(site_url('zone/edit/'.$detail->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>  
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($detail) ? $detail->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>     <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('state'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                             <select autofocus="" id="edit_state_id" name="state_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($states as $s){ ?>
											<option value="<?php print $s->id; ?>" <?php echo($detail->state_id == $s->id)? "selected='selected'" : ""; ?>><?php print $s->name; ?></option>
										<?php }?>
										</select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div>                                                                            
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($detail) ? $detail->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('zone/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
              
</script>