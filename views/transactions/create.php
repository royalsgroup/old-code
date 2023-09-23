
<?php $iAllowBackDate =0; ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('add')." ".$this->lang->line('entry')." - ".$voucher->name." (".$voucher->category.")"; ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>            
			  <div class="x_content quick-link no-print">
				<?php $this->load->view('quicklinks/account'); ?>
			</div>

            
            <div class="x_content">
                 <?php echo form_open_multipart(site_url('transactions/create'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
				 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">								
                                    	

                                    	<?php if(isset($previous_financial_year_activate) && $previous_financial_year_activate>0){?>
										   <input type="text" class="form-control date col-md-7 col-xs-12" id="date" name="date" required='required' autocomplete="off">
										<?php }else{ ?>
											<input type="text" class="form-control col-md-7 col-xs-12" id="date_new" name="date" value="<?php echo $todayDate?>" required='required' autocomplete="off" readonly>  

										<?php } ?> 
                                        <div class="help-block"><?php echo form_error('date'); ?></div>
                                    </div>
                                </div> 
								<?php if($voucher->type_id ==1){ // journal voucher ?>
								<div class="item form-group">                        
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('ledger'); ?><span class="required">*</span></label>
											 <div class="col-md-9 col-sm-9 col-xs-12">
											<table class='table headLedgers'>
											<thead>
												<tr>
													<td><?php echo $this->lang->line('ledger'); ?></td>
													<!-- <td><?php echo $this->lang->line('amount'); ?></td>
													<td><?php echo $this->lang->line('remark'); ?></td> -->
													<td></td>
												</tr>
											</thead>
											<tbody>
											</tbody>
											</table>
											</div>
										</div>
										<?php if($voucher->type_id !=1 ) {?>
										<div class="item form-group">
									 		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
									  		<div class="col-md-6 col-sm-6 col-xs-12">
												<a href="javascript:void(0);" class='btn btn-default' onclick="add_head()"> ADD</a>
											</div>
										</div>

									<?php } ?>
								<?php } else { ?>
									<div class="item form-group">                        
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('ledger'); ?><span class="required">*</span></label>
											 <div class="col-md-6 col-sm-6 col-xs-12">
											 <?php 
												$ledger_input_name="ledger_id"; 
											 ?>
                                           <select autofocus="" id="ledger_id" name="<?php print $ledger_input_name; ?>" class="form-control select2 ledger_dropdown" required='required' >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($ledgers as $ledger) {
                                            if(in_array($voucher->type_id,array(3,5,4))){
												if($ledger->group_name != 'Cash-in-hand' && $ledger->base_id!=5){
													continue;
												}
											}
											if($voucher->type_id== 1 && ($ledger->group_name == 'Cash-in-hand' ||  $ledger->base_id==5) ){
												continue;
											}
											?>
											
                                            <option class="ledger_<?php echo $ledger->id ?>" value="<?php echo $ledger->id ?>"<?php
                                            if (isset($_POST['ledger_id']) && $_POST['ledger_id'] == $ledger->id) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php 
											
											// echo $ledger->name." [".$ledger->effective_balance_cr_dr." : ".$ledger->effective_balance."]"." (".$ledger->category.")"; 
											echo $ledger->name." (".$ledger->category.") $ledger->group_name"; 

											?></option>

                                            <?php
                                        }
                                        ?>
										</select>
                                            <div class="help-block"><?php echo form_error('ledger_id'); ?></div> 
                                        </div>
                                    </div>
								<?php } ?>
								
									<?php 
									/*if($voucher->type_id ==1){ // journal voucher ?>
									<div class="row" id='multipleHead'>
									</div>
									<div class="item form-group">
									 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
									  <div class="col-md-6 col-sm-6 col-xs-12">
									<a href="javascript:void(0);" class='btn btn-default' onclick="add_head()"> ADD</a>
									</div>
									</div>
									<?php } */?>
									<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('head_cr_dr'); ?><span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
									<select required='required' name='head_cr_dr' class="form-control col-md-7 col-xs-12">
										<?php if($voucher->type_id ==1 || $voucher->type_id == 4){  ?>
										<option value='CR'>CR</option>
										<?php } 
										if($voucher->type_id != 4){ 
										?>
										<option value='DR'>DR</option>
										<?php } ?>
									</select>
									</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('narration'); ?>
										</label>
                                    	<div class="col-md-6 col-sm-6 col-xs-12">
											<textarea class="form-control col-md-7 col-xs-12" id="narration" name="narration" autocomplete="off"></textarea>                                        
                                        	<div class="help-block"><?php echo form_error('narration'); ?></div>
                                   		 </div>
									
                                	</div>                 	

								<input class="form-control col-md-7 col-xs-12" name="reciptid" id='reciptid' type="hidden"  value="<?php echo $recipt?>"      >                                 

									<?php if($recipt == 'yes') { ?>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Reciever name
										</label>
                                    	
											<div class="col-md-6 col-sm-6 col-xs-12">
									<input class="form-control col-md-7 col-xs-12" name="reciever_name" type="text"       >                                 
                                    </div>
                                	</div>
									<?php } else{?>

										<input class="form-control col-md-7 col-xs-12" name="reciever_name" type="hidden"  value=""     >     

									<?php } ?>

								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('particulars'); ?>
                                    </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
										<table class='table particulars'>
											<thead>
												<tr>
													<td><?php echo $this->lang->line('ledger'); ?></td>
													<td><?php echo $this->lang->line('amount'); ?></td>
													<td><?php echo $this->lang->line('remark'); ?></td>
													<td></td>
												</tr>
											</thead>
											<tbody>
											</tbody>
											<tfoot>
												<tr>
													<td><a href="javascript:void(0);" class='btn btn-default' onclick="add_particulars()"> ADD</a></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('total'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<input type='text' id="total_amount" name='total_amount' disabled value='' />
									</div>
								</div>
								 <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
										<input type='hidden' name='voucher_id' value='<?php print $voucher->id; ?>' />
                                        <a href="<?php echo site_url('vouchers/view/'.$voucher->id); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
   <!-- bootstrap-datetimepicker -->
   <script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/datepicker/css/bootstrap-datetimepicker.css">
 <script src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datetimepicker.js"></script>
 <script type="text/javascript">
 var count=0; 
 var count1=0;
 <?php  if($this->session->userdata('role_id') == SUPER_ADMIN) { ?>
    $(document).ready(function () {
		 var date_format = '<?php echo $result    = strtr('d-m-Y', ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
       var date_format_js = '<?php echo $result = strtr('d-m-Y', ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';        
		$('.date').datepicker({
            format: date_format,
             autoclose: true,
                language: '<?php echo $language_name ?>'
        }); 
<?php } else 
{ ?>
    $(document).ready(function () 
	{
		 var date_format = '<?php echo $result    = strtr('d-m-Y', ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
       var date_format_js = '<?php echo $result = strtr('d-m-Y', ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>'; 
<?php 
	if($allow_till_current_date)
	{		?>
		$('.date').datepicker({
			format: date_format,
			startDate: '<?php print $voucher_start_date; ?>',
			endDate:'<?php print date("d/m/Y"); ?>',
			autoclose: true,
				language: '<?php echo $language_name ?>'
		}); 
	<?php
	}
	else if($iOnlyAllowCurrentDate)
	{?>
		$('.date').datepicker({
            format: date_format,
			startDate: '<?php print date("d/m/Y"); ?>',
			endDate:'<?php print date("d/m/Y"); ?>',
             autoclose: true,
                language: '<?php echo $language_name ?>'
        }); 
<?php
	}
	else if($iAllowBackDate)
	{?>
		$('.date').datepicker({
            format: date_format,
			endDate:'<?php print $voucher_end_date; ?>',
             autoclose: true,
                language: '<?php echo $language_name ?>'
        }); 
<?php }
		else
		{?>
		$('.date').datepicker({
			format: date_format,
			startDate: '<?php print $voucher_start_date; ?>',
			endDate:'<?php print $voucher_end_date; ?>',
			autoclose: true,
			language: '<?php echo $language_name ?>'
        }); 

		<?php } 
}?>
		
		add_particulars();	
<?php if($voucher->type_id ==1){ // journal voucher ?>
add_head();
<?php } ?>
	});
	$(document).on('change','.ledger_dropdown',function(){
		var ledger_id = $(this).val();		
		var select_box = this;		
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_ledger_balance'); ?>",
            data   : { ledger_id:ledger_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
				//    console.log($('.ledger_'+ledger_id))
				//    $(this).find('option[value="'+ledger_id+'"]').html(response);

               		let newstr = response.toLowerCase();
               		if(newstr.indexOf('cash') != -1){
					    let balance = newstr.match(/\d+/);
					    if($('#reciptid').val() !='yes'){
						    if(balance[0] <=0){
						    	alert("Your balance is 0 kindly update it ");
								$('#select2-ledger_id-container').text('Select');
								$("#ledger_id option:first").attr('selected','selected');
						    	return false;
						    }
						}
					}

					$('.ledger_'+ledger_id).html(response);
					console.log(select_box)
					$(select_box).select2();
                 
               }
            }
        });		
	})
	function cal_total_amount(){
			var total_amount=0;
		$('table.particulars td .pamount').each(function() {
				if($(this).val()!= ''){
					total_amount= parseFloat(total_amount) + parseFloat($(this).val());			
				}
		});
		$("input#total_amount").val(total_amount);
	}
	<?php
	function escape_qoutes_custom($string)
	{
		return str_replace("'","",$string);
	}
	?>
	function add_particulars(){
		var html='';
		html += '<tr id="row'+count+'">';
		html += "<td><select class='form-control select21 ledger_dropdown' name='pledger["+count+"]'><option value=''>--Select--</option>";
		<?php
		$options = "";
	
		 foreach ($ledgers as $ledger) { 
		 if(in_array($voucher->type_id,array(3))){
			if($ledger->group_name != 'Cash-in-hand' && $ledger->base_id!=5){
				continue;
			}
											}
			if($voucher->type_id == 1 && ($ledger->group_name == 'Cash-in-hand' ) ){
				continue;
			}
			// $options .= '<option value="'.$ledger->id.'">'.escape_qoutes_custom($ledger->name).' ['.escape_qoutes_custom($ledger->effective_balance_cr_dr).' : '.escape_qoutes_custom($ledger->effective_balance).']'.' ('.escape_qoutes_custom($ledger->category).')</option>';
			$options .= '<option class="ledger_'.$ledger->id.'" value="'.$ledger->id.'">'.escape_qoutes_custom($ledger->name).' ('.escape_qoutes_custom($ledger->category).') '.$voucher->type_id.'</option>';

		} 
		
		?>
		html += '<?php echo $options; ?>';
		html += "</select></td>";
		html += "<td><input type='text' class='form-control pamount' name='pamount["+count+"]'  onkeyup='cal_total_amount();' /></td>";
		html += "<td><input type='text' class='form-control' name='premark["+count+"]' /></td>";
		html += "<td><a href='javascript:void(0);' class='btn btn-danger' onclick='remove_particular("+count+");'>Remove</a></td>";
		html += '</tr>';		
		$("table.particulars tbody").append(html);
		count++;
		$('.select21').select2();
	}
	function remove_particular(index){
		var rowCount = $('table.particulars tbody >tr').length;		
		if(rowCount >1){
			$("table.particulars tr#row"+index).remove();
			cal_total_amount();
		}
	}
	function remove_head(index){
		var rowCount = $('table.headLedgers tbody >tr').length;		
		if(rowCount >1){
			$("table.headLedgers tr#row"+index).remove();			
		}
	}
	function add_head(){
		//var ledger_data=$("#ledger_id").html();		
		var html='';
		html += '<tr id="row'+count1+'">';
		html += "<td><select class='form-control select22' name='ledger_id["+count1+"]'><option value=''>--Select--</option>";
		<?php  
		$options = "";
		foreach ($ledgers as $ledger) {  
			if($voucher->type_id== 1 && ($ledger->group_name == 'Cash-in-hand' ||  $ledger->base_id==5) ){
				continue;
			}
			$options .= '<option class="ledger_'.$ledger->id.'" value="'.$ledger->id.'">'.escape_qoutes_custom($ledger->name).' ('.escape_qoutes_custom($ledger->category).')'.$voucher->type_id.'</option>';
		} 
		?>
		html += '<?php echo $options; ?>';
		html += "</select></td>";
		// html += "<td><input type='text' class='form-control headamount' name='headamount["+count1+"]'  onkeyup='cal_total_amount();' /></td>";
		// html += "<td><input type='text' class='form-control' name='headremark["+count1+"]' /></td>";
		html += "<td><a href='javascript:void(0);' class='btn btn-danger' onclick='remove_head("+count1+");'>Remove</a></td>";
		html += '</tr>';		
		$("table.headLedgers tbody").append(html);
		count1++;		
		$('.select22').select2();
	}
	
	</script>
	