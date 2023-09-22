<?php  $iForceAllow =1 ?>
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
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('voucher_books'); ?></small></h3>
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
                        <?php if(has_permission(VIEW, 'accounting', 'vouchers')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_voucher_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('voucher'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                       
                       <?php if(has_permission(ADD, 'accounting', 'vouchers')){ ?> 
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('vouchers/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('voucher'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_voucher" role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('voucher'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                       
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_voucher"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('voucher'); ?></a> </li>                          
                        <?php } ?>
 <!--<li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_accountvouchers_by_school(this.value);">
                                    <option value="<?php echo site_url('vouchers/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('vouchers/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 	-->					
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_voucher_list" >
                            <div class="x_content">
							<div class='row'>
							<div class="col-md-12 col-sm-12 col-xs-12">
							<?php echo form_open_multipart(site_url('vouchers/index'), array('name' => 'filterList', 'id' => 'filterList', 'class'=>'form-horizontal form-label-left'), ''); ?>
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
							   <?php } ?>  
							    <div class="col-md-3 col-sm-3 col-xs-12">
							<div class="item form-group">                        
                                           
                                           <select autofocus="" id="filter_category" name="category" class="form-control" required="required">
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
                                        </div></div>
										<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="item form-group">   
 <button id="filter" type="submit" class="btn btn-success">Filter</button>							
							</div>
							</div>
							<?php echo form_close(); ?>
							</div>
							</div>
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>                                        
										<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
										<th><?php echo $this->lang->line('school'); ?></th>
										<?php } ?>
										<th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('type'); ?></th>
										<th><?php echo $this->lang->line('no_of_entries'); ?></th>
										<th><?php echo $this->lang->line('last_entry_date'); ?></th>
                                        <th class='noPrint'><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
									<tr>
										<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
										<td><input type="text" placeholder="Search" style="width:100%;"  class="column_search" /></td>
										<?php } ?>
										 <td><input type="text" placeholder="Search" style="width:100%;"  class="column_search" /></td>
                                        <td><input type="text" placeholder="Search" style="width:100%;"  class="column_search" /></td>
										<td><input type="text" placeholder="Search" style="width:100%;"  class="column_search" /></td>
										<td></td>
										<td></td>
                                        <td></td>
										
									</tr>
                                </thead>
                                <tbody>   
                                   
                                </tbody>
                            </table>                                
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_voucher">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('vouchers/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); 
							   $default_cr='CR';
							   if($post['budget_cr_dr']=='DR'){
								   $default_cr='DR';
							   }
							   
							   ?>                                                                                        <?php $this->load->view('layout/school_list_form'); ?>       
                               <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off" required='required'>
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('type'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="add_type_id" name="type_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($voucher_types as $type) {
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
                                            <div class="help-block"><?php echo form_error('type_id'); ?></div> 
                                        </div>
                                    </div>   <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('is_readonly'); ?>?<span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											
                                            <input  class=""  name="is_readonly"  value="1" <?php echo ($post['is_readonly']==1) ?  "checked='chekced'" : ''; ?>  type="radio" autocomplete="off" required='required'> Yes
											<input  class=""  name="is_readonly"  value="0" <?php echo ($post['is_readonly']==0) ?  "checked='chekced'" : ''; ?>  type="radio" autocomplete="off" required='required'> No
                                            <div class="help-block"><?php echo form_error('is_readonly'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="budget"  id="budget" value="<?php echo isset($post['budget']) ?  $post['budget'] : ''; ?>" placeholder="<?php echo $this->lang->line('budget'); ?> "  type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('budget'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget_cr_dr'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="CR" type="radio" <?php echo ($default_cr=='CR') ?  "checked='checked'" : ''; ?>"  autocomplete="off">CR
											<input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="DR" type="radio" <?php echo ($default_cr=='DR') ?  "checked='checked'" : ''; ?>"  autocomplete="off" >DR
                                            <div class="help-block"><?php echo form_error('budget_cr_dr'); ?></div> 
                                        </div>
                                    </div>   
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company <span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="add_category" name="category" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
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
                                        </div></div>                                                          		                                                                 					
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('vouchers/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php  echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
						<?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_voucher">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('vouchers/edit/'.$voucher->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>  
								<?php $this->load->view('layout/school_list_edit_form'); ?> 
                                <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($voucher) ? $voucher->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>  
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('type'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="edit_type_id" name="type_id" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($voucher_types as $type) {
                                            ?>
                                            <option value="<?php echo $type->id ?>"<?php
                                            if (isset($voucher->type_id) && $voucher->type_id == $type->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $type->name; ?></option>

                                            <?php
                                        }
                                        ?>
										</select>
                                            <div class="help-block"><?php echo form_error('type_id'); ?></div> 
                                        </div>
                                    </div>   <div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('is_readonly'); ?>?<span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											
                                            <input  class=""  name="is_readonly"  value="1" <?php echo ($voucher->is_readonly==1) ?  "checked='chekced'" : ''; ?>  type="radio" autocomplete="off" required='required'> Yes
											<input  class=""  name="is_readonly"  value="0" <?php echo ($voucher->is_readonly==0) ?  "checked='chekced'" : ''; ?>  type="radio" autocomplete="off" required='required'> No
                                            <div class="help-block"><?php echo form_error('is_readonly'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="budget"  id="budget" value="<?php echo isset($voucher->budget) ?  $voucher->budget : ''; ?>" placeholder="<?php echo $this->lang->line('budget'); ?> "  type="text" autocomplete="off" >
                                            <div class="help-block"><?php echo form_error('budget'); ?></div> 
                                        </div>
                                    </div>
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('budget_cr_dr'); ?></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="CR" type="radio" <?php echo ($voucher->budget_cr_dr=='CR') ?  "checked='checked'" : ''; ?>"  autocomplete="off" >CR
											<input  class=""  name="budget_cr_dr"  id="budget_cr_dr" value="DR" type="radio" <?php echo ($voucher->budget_cr_dr=='DR') ?  "checked='checked'" : ''; ?>"  autocomplete="off"  >DR
                                            <div class="help-block"><?php echo form_error('budget_cr_dr'); ?></div> 
                                        </div>
                                    </div>  
									<div class="item form-group">                        
                                    
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('category'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
                                           <select autofocus="" id="edit_category" name="category" class="form-control" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($voucher_category as $key=>$value) {
                                            ?>
                                            <option value="<?php echo $key ?>"<?php
                                            if (isset($voucher->category) && $voucher->category == $key) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $value; ?></option>

                                            <?php
                                        }
                                        ?>
										</select>
                                            <div class="help-block"><?php echo form_error('category'); ?></div> 
                                        </div></div>
									 <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($voucher) ? $voucher->id : '' ?>" name="id" />
                                        <a href="<?php echo site_url('vouchers/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
  <!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>

	<script type="text/javascript">   
<?php /* if(isset($school_info->frontend_logo) && $school_info->frontend_logo!= ''){ ?>
				var schoollogo= '<?php print UPLOAD_PATH."/logo/".$school_info->frontend_logo; ?>';
	<?PHP } else*/ if($this->global_setting->brand_logo){ ?> 
			var schoollogo=  '<?php echo UPLOAD_PATH.'logo/'.$this->global_setting->brand_logo; ?>';
	<?php } else {  ?>
	var schoollogo=  "<?php echo IMG_URL. '/sms-logo-50.png'; ?>";
	<?php } 
 if(isset($financial_year->session_year)){ 
		
		$message="Voucher Book ( ".$financial_year->session_year.")";
		$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 } else { 
	 $message="Voucher Book";
	$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 } ?>			
    $(document).ready(function() {
        <?php
        if (isset($_POST['category']) && $_POST['category']) {
            $category= $_POST['category'];
                                            }
                                            else  if (isset($school_info->category) &&  $school_info->category ) 
                                            {
                                                $category= $school_info->category ;
                                            }
                                            ?>
        var sch_id='<?php print $filter_school_id; ?>';
        var category='<?php print $category; ?>';
		$( "#datepicker" ).datepicker();
          var table =$('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
			  destroy: true,
			  orderCellsTop: true,
				fixedHeader: true,
                'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'<?php echo site_url("vouchers/get_list"); ?>',
                'data': {'school_id': sch_id,'category': category}
            },
              iDisplayLength: 15,
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength',
				   'colvis',
				  {					
					'extend': 'print',
					//title: '<div style=""><span style=""><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>',	
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
				}
              ],			 
              search: true,  
			ordering: false,				  
              responsive: true
          });
		  
		  // Apply the search
    $( '#datatable-responsive thead .column_search'  ).bind( 'keyup change', function () {   
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    } );
		
        });
        
       $("#add").validate();  
       $("#edit").validate();  
	   function get_accountvouchers_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 	
        $(document).on('change','#school_id', function(){
            var school_id=this.value;
            $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('ajax/get_default_category'); ?>",
                data   : { school_id:school_id},               
                async  : false,
                success: function(response){                                                   
                    if(response)
                    {  
                        $('#filter_category').html(response);      
                    }
                }
            });

         })	


		
</script>