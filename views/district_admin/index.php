
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-user-md"></i><small> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('district_admin'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'administrator', 'setting')){ ?>
                    <a href="<?php echo site_url('administrator/setting'); ?>"><?php echo $this->lang->line('general'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'school')){ ?>
                   | <a href="<?php echo site_url('administrator/school'); ?>"><?php echo $this->lang->line('manage_school'); ?></a>
                <?php } ?>
                <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ||  has_permission(VIEW, 'administrator', 'import_csv') ){ ?>
                   |  <a href="<?php echo site_url('import/csv'); ?>"><?php echo $this->lang->line('import'); ?> <?php echo $this->lang->line('csv'); ?></a>
                <?php } ?>		
                <?php if(has_permission(VIEW, 'administrator', 'payment')){ ?>
                    | <a href="<?php echo site_url('administrator/payment'); ?>"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>                    
                <?php if(has_permission(VIEW, 'administrator', 'sms')){ ?>
                    | <a href="<?php echo site_url('administrator/sms'); ?>"><?php echo $this->lang->line('sms'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>      
                <?php if(has_permission(VIEW, 'administrator', 'emailsetting')){ ?>
                    | <a href="<?php echo site_url('administrator/emailsetting'); ?>"><?php echo $this->lang->line('email'); ?> <?php echo $this->lang->line('setting'); ?></a>
                <?php } ?>    
                <?php if(has_permission(VIEW, 'administrator', 'year')){ ?>
                    | <a href="<?php echo site_url('administrator/year'); ?>"><?php echo $this->lang->line('academic_year'); ?></a>
                <?php } ?>                  
                <?php if(has_permission(VIEW, 'administrator', 'role')){ ?>
                   | <a href="<?php echo site_url('administrator/role'); ?>"><?php echo $this->lang->line('user_role'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'permission')){ ?>
                   | <a href="<?php echo site_url('administrator/permission'); ?>"><?php echo $this->lang->line('role_permission'); ?></a>                   
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'superadmin')){ ?>
                   | <a href="<?php echo site_url('administrator/superadmin'); ?>"><?php echo $this->lang->line('super_admin'); ?></a>                
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'user')){ ?>
                   | <a href="<?php echo site_url('administrator/user'); ?>"><?php echo $this->lang->line('manage_user'); ?></a>                
                <?php } ?>
                <?php if(has_permission(EDIT, 'administrator', 'password')){ ?>
                   | <a href="<?php echo site_url('administrator/password'); ?>"><?php echo $this->lang->line('reset_user_password'); ?></a>                   
                <?php } ?>                
                <?php if(has_permission(VIEW, 'administrator', 'usercredential')){ ?>
                   | <a href="<?php echo site_url('administrator/usercredential/index'); ?>"> <?php echo $this->lang->line('user'); ?> <?php echo $this->lang->line('credential'); ?></a>                   
                <?php } ?>                
                <?php if(has_permission(VIEW, 'administrator', 'activitylog')){ ?>
                   | <a href="<?php echo site_url('administrator/activitylog'); ?>"><?php echo $this->lang->line('activity_log'); ?></a>                  
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'feedback')){ ?>
                   | <a href="<?php echo site_url('administrator/feedback'); ?>"><?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('feedback'); ?></a>                  
                <?php } ?>
                <?php if(has_permission(VIEW, 'administrator', 'backup')){ ?>
                   | <a href="<?php echo site_url('administrator/backup'); ?>"><?php echo $this->lang->line('backup'); ?> <?php echo $this->lang->line('database'); ?></a>                  
                <?php } ?>
            </div>
            
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_district_admin_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('district_admin'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        
                        <?php if(has_permission(ADD, 'hrm', 'employee')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('districtadmin/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('district_admin'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_district_admin"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('district_admin'); ?></a> </li>                          
                             <?php } ?>                         
                        <?php } ?>  
                                
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_district_admin"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('district_admin'); ?></a> </li>                          
                        <?php } ?>                            
                        
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_district_admin_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>                                        
                                        <th><?php echo $this->lang->line('photo'); ?></th>
                                        <th><?php echo $this->lang->line('username'); ?></th>
                                        <th><?php echo $this->lang->line('role'); ?></th>                                        
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php  $count = 1; if(isset($districtadmins) && !empty($districtadmins)){ ?>
                                        <?php foreach($districtadmins as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>                                            
                                            <td>
                                                <?php  if($obj->photo != ''){ ?>
                                                <img src="<?php echo UPLOAD_PATH; ?>/employee-photo/<?php echo $obj->photo; ?>" alt="" width="70" /> 
                                                <?php }else{ ?>
                                                <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="70" /> 
                                                <?php } ?>
                                            </td>
                                            <td><?php echo ucfirst($obj->username); ?></td>
                                            <td><?php echo $obj->role; ?></td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'administrator', 'districtadmin')){ ?> 
                                                    <a href="<?php echo site_url('districtadmin/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a><br/>
                                                <?php } ?> 
                                                <?php /*if(has_permission(VIEW, 'administrator', 'districtadmin')){ ?>
                                                    <a  onclick="get_district_admin_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-district_admin-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a><br/>
                                                <?php } */?>
                                                <?php if(has_permission(DELETE, 'administrator', 'districtadmin')){ ?> 
                                                    <?php if(!$obj->is_default){ ?> 
                                                        <a href="<?php echo site_url('districtadmin/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_district_admin">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('districtadmin/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
								<!-- district list -->
								<div class="row">
								<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('state'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 fn_state_id" id="add_state_id"  name="state_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($states AS $obj){ ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($post) && $post['state_id'] == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div> 
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('zone'); ?></label>
                                            <select autofocus="" id="add_zone_id" name="zone_id" class="form-control fn_zone_id" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('zone'); ?></div> 
                                        </div>
                                    </div> 	
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('subzone'); ?> </label>
                                            <select autofocus="" id="add_subzone_id" name="subzone_id" class="form-control fn_subzone_id" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('subzone'); ?></div> 
                                        </div>
                                    </div> 										
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('district'); ?> </label>
                                            <select autofocus="" id="add_district_id" name="district_id" class="form-control fn_district_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('district'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('block'); ?> </label>
                                            <select autofocus="" id="add_block_id" name="block_id" class="form-control fn_block_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('block'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('sankul'); ?> </label>
                                            <select autofocus="" id="add_sankul_id" name="sankul_id" class="form-control fn_sankul_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('sankul'); ?></div> 
                                        </div>
                                    </div>
								</div>
								<div class="row">
								<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('employee'); ?>  <span class="required">*</span></label>
                                            <select autofocus="" id="add_employee_id" name="user_id" class="form-control fn_employee_id" required="required"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('employee_id'); ?></div> 
                                        </div>
                                    </div>
                               </div>
                                
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('districtadmin'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>                             
                            </div>
                        </div>  
                        <?php if(isset($edit)){ ?>
                        <div  class="tab-pane fade in <?php if(isset($edit)){ echo 'active'; }?>" id="tab_edit_district_admin">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('districtadmin/edit/'. $districtadmin->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
								<!-- district list -->
                                <input type="hidden" name="id" value="<?php echo $districtadmin->id ?>" >
                                <input type="hidden" name="user_id" value="<?php echo $districtadmin->user_id ?>" >
                                

								<div class="row">
								<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('state'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 fn_state_id" id="edit_state_id"  name="state_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($states AS $obj){ ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($districtadmin) && $districtadmin->state_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('state'); ?></div> 
                                        </div>
                                    </div> 
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">          

                                            <label for="district"><?php echo $this->lang->line('zone'); ?></label>
                                            <select autofocus="" id="edit_zone_id" name="zone_id" class="form-control fn_zone_id" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                     
										</select>
                                            <div class="help-block"><?php echo form_error('zone'); ?></div> 
                                        </div>
                                    </div> 	
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('subzone'); ?> </label>
                                            <select autofocus="" id="edit_subzone_id" name="subzone_id" class="form-control fn_subzone_id" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        
										</select>
                                            <div class="help-block"><?php echo form_error('subzone'); ?></div> 
                                        </div>
                                    </div> 										
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('district'); ?> </label>
                                            <select autofocus="" id="edit_district_id" name="district_id" class="form-control fn_district_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        
										</select>
                                            <div class="help-block"><?php echo form_error('district'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('block'); ?> </label>
                                            <select autofocus="" id="edit_block_id" name="block_id" class="form-control fn_block_id"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                               
										</select>
                                            <div class="help-block"><?php echo form_error('block'); ?></div> 
                                        </div>
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="district"><?php echo $this->lang->line('sankul'); ?> </label>
                                            <select autofocus="" id="edit_sankul_id" name="sankul_id" class="form-control fn_sankul_id"  >
                                            <option value="">Select</option>
                                               
                                           
										</select>
                                            <div class="help-block"><?php echo form_error('sankul'); ?></div> 
                                        </div>
                                    </div>
								</div>
								<div class="row">
								<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">                                            
                                            <label for="add_employee_id"><?php echo $this->lang->line('employee'); ?>  <span class="required">*</span></label>
                                            <select autofocus="" id="edit_employee_id" name="user_id" class="form-control fn_employee_id" required="required" disabled="disabled"  >
                                                <option value="<?php echo $districtadmin->user_id  ?>"><?php echo $districtadmin->username  ?></option>
										</select>
                                            <div class="help-block"><?php echo form_error('employee_id'); ?></div> 
                                        </div>
                                    </div>
                               </div>
                                
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('districtadmin'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
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


<div class="modal fade bs-district_admin-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('district_admin'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_super_admin_data">            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_district_admin_modal(district_admin_id){
         
        $('.fn_employee_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('districtadmin/get_single_district_admin'); ?>",
          data   : {district_admin_id : district_admin_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_super_admin_data').html(response);
             }
          }
       });
    }
</script>
  



<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 
  
  <!-- datatable with buttons -->
  <script type="text/javascript">
 <?php if(isset($edit)){ ?>
<?php } ?>
    $('#add_dob').datepicker();
    $('#edit_dob').datepicker();
  
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
</script>
<script type="text/javascript"> 
function get_employees(){
    <?php if(isset($edit)){ ?>
        return 1;
    <?php } ?>
	<?php if(isset($edit) && !empty($edit)){ ?>	
	var prefix="edit_";	
	<?php } else {?>
	var prefix="add_";
	<?php } ?>
	var state_id=$("#"+prefix+"state_id").val();
	var zone_id=$("#"+prefix+"zone_id").val();
	var subzone_id=$("#"+prefix+"subzone_id").val();
	var district_id=$("#"+prefix+"district_id").val();
	var block_id=$("#"+prefix+"block_id").val();
	var sankul_id=$("#"+prefix+"sankul_id").val();
	 $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_employees'); ?>",
            data   : { state_id:state_id, zone_id:zone_id,subzone_id:subzone_id,district_id:district_id,block_id:block_id,sankul_id:sankul_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_employee_id').html(response);   					   
                   }else{
                       $('#add_employee_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
}
var edit = false;
    $(document).ready(function() {
		 <?php if(isset($edit) && !empty($edit)){ ?>	
edit=true;		 
           $("#edit_state_id").trigger('change');         
         <?php } ?>	
   
	});	
    $('.fn_zone_id').on('change', function(){
      
      var zone_id = $(this).val();
      var subzone_id = '';
      <?php if(isset($edit) && !empty($edit)){ ?>
          subzone_id =  '<?php echo $districtadmin->subzone_id; ?>';                       
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
                     $('#edit_subzone_id').trigger('change');
                 }else{
                     $('#add_subzone_id').html(response);   
                 }
                                  
               
             }
          }
      });		
      get_employees();
  });
  $('.fn_subzone_id').on('change', function(){
    
      var subzone_id = $(this).val();
      var district_id = '';
      <?php if(isset($edit) && !empty($edit)){ ?>
      district_id =  '<?php echo $districtadmin->district_id; ?>';                       
       <?php } ?> 		
     $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('ajax/get_district_by_subzone'); ?>",
          data   : { subzone_id:subzone_id, district_id:district_id},               
          async  : false,
          success: function(response){                                                   
             if(response)
             {  
                 if(edit){
                     $('#edit_district_id').html(response);  
$('#edit_district_id').trigger('change');					   					   
                 }else{
                     $('#add_district_id').html(response);   
                 }
                                  
               
             }
          }
      });		
      get_employees();
  });
  $('.fn_district_id').on('change', function(){
    
      var district_id = $(this).val();
      var block_id = '';
      <?php if(isset($edit) && !empty($edit)){ ?>
          block_id =  '<?php echo $districtadmin->block_id; ?>';                     
       <?php } ?> 		
     $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('ajax/get_block_by_district'); ?>",
          data   : { district_id:district_id, block_id:block_id},               
          async  : false,
          success: function(response){                                                   
             if(response)
             {  
                 if(edit){
                     $('#edit_block_id').html(response); 
                      $('#edit_block_id').trigger('change');					   					   
                 }else{
                     $('#add_block_id').html(response);   
                 }
                                  
               
             }
          }
      });		
      get_employees();
  });
      $('.fn_block_id').on('change', function(){
    
      var block_id = $(this).val();
      var sankul_id = '';
      <?php if(isset($edit) && !empty($edit)){ ?>
          sankul_id =  '<?php echo $districtadmin->sankul_id; ?>';           
       <?php } ?> 		
     $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('ajax/get_sankul_by_block'); ?>",
          data   : { block_id:block_id, sankul_id:sankul_id},               
          async  : false,
          success: function(response){                                                   
             if(response)
             {  
                 if(edit){
                     $('#edit_sankul_id').html(response);   
                 }else{
                     $('#add_sankul_id').html(response);   
                 }
                                  
               
             }
          }
      });		
      get_employees();		
  });
  $('.fn_state_id').on('change', function(){
      console.log('jhuh');
      var state_id = $(this).val();
      var zone_id = '';
       <?php if(isset($edit) && !empty($edit)){ ?>
        zone_id =  '<?php echo $districtadmin->zone_id; ?>'; 
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
                     $('#edit_zone_id').trigger('change');
                 }else{
                     $('#add_zone_id').html(response);   
                 }
                                  
               
             }
          }
      });		
      
      get_employees();
      
  });	

	$('.fn_sankul_id').on('change', function(){
		get_employees();		
	});
</script>