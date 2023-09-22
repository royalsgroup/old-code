<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage_payment_modes'); ?></small></h3>
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
                        <?php if(has_permission(VIEW, 'accounting', 'paymentmodes')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_paymentmode_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('payment_mode'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'accounting', 'paymentmodes')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('paymentmodes/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('category'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_paymentmode" role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('payment_mode'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_paymentmode"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('payment_mode'); ?></a> </li>                          
                        <?php } ?>
 <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_paymentmodes_by_school(this.value);">
                                    <option value="<?php echo site_url('paymentmodes/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('paymentmodes/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 						
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_paymentmode_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
										<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
										<th><?php echo $this->lang->line('school'); ?></th>
										<?php } ?>
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('is_readonly'); ?>?</th>
										<th><?php echo $this->lang->line('ledger'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($paymentmodes) && !empty($paymentmodes)){ ?>
                                        <?php foreach($paymentmodes as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
											<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
											<td><?php echo $obj->school_name; ?></td>
											<?php } ?>
                                            <td><?php echo $obj->name; ?></td>
											<td><?php 
											if($obj->is_readonly ==1){
												echo 'true';
											}
											else {
												echo 'false';
											}
											 ?></td>
											<td><?php echo $obj->ledger_name; ?></td>
                                            <td>                                                 
                                                <?php if(has_permission(EDIT, 'accounting', 'paymentmodes')){ ?>
                                                    <a href="<?php echo site_url('paymentmodes/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'accounting', 'paymentmodes')){ ?>
                                                    <a href="<?php echo site_url('paymentmodes/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_paymentmode">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('paymentmodes/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>                                                                                        <?php $this->load->view('layout/school_list_form'); ?>       
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
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
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('ledger'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="add_ledger_id" name="ledger_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($ledgers as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($post['ledger_id']) && $obj->id==$post['ledger_id']){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
										</select>
                                            <div class="help-block"><?php echo form_error('ledger_id'); ?></div> 
                                        </div>
                                    </div>                                                                 					
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('paymentmodes/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
</div>
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_paymentmode">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('paymentmodes/edit/'.$paymentmode->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>  
<?php $this->load->view('layout/school_list_edit_form'); ?> 
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($paymentmode) ? $paymentmode->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>    
<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('is_readonly'); ?>?</label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="is_readonly"  id="is_readonly" value="1" type="checkbox" <?php echo ($paymentmode->is_readonly==1) ?  "checked='checked'" : ''; ?>"  autocomplete="off">
                                            <div class="help-block"><?php echo form_error('is_readonly'); ?></div> 
                                        </div>
                                    </div>										
								     <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('ledger'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="edit_ledger_id" name="ledger_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        
										</select>
                                            <div class="help-block"><?php echo form_error('ledger_id'); ?></div> 
                                        </div>
                                    </div>                                                                             
								                                                                   
																
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($paymentmode) ? $paymentmode->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('paymentmodes/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
           $("#edit_school_id").trigger('change');         
         <?php } ?>	
	});		 
       <?php if(isset($paymentmode) && !empty($paymentmode)){ ?>
          edit = true; 
    <?php } ?>
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();
		var ledger_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
            ledger_id =  '<?php echo $paymentmode->ledger_id; ?>';           
         <?php } ?> 
		if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data   : { school_id:school_id, ledger_id:ledger_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_ledger_id').html(response);   
                   }else{
                       $('#add_ledger_id').html(response);   
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
        
       $("#add").validate();  
       $("#edit").validate();  
	   function get_paymentmodes_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 

</script>