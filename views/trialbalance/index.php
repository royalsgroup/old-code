<style>
table{
	table-layout: fixed;
}
table td{
	word-wrap: break-word;
}
a.groupHead{
	font-weight:bold;
	color:#000000;
}
 @media print {
	a.groupHead{
	font-weight:bold;
	color:#000000;
}
 }
</style>
<?php 
// var_dump($category);
$voucher_category = getVoucherCategory();?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('trial_balance'); ?></small></h3>
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
                        <?php if(has_permission(VIEW, 'accounting', 'accountledgers')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_trailbalance"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('trial_balance'); ?></a> </li>
                       <?php } ?>
                                           
 <!--<li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_trialbalance_by_school(this.value);">
                                    <option value="<?php echo site_url('trialbalance/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('trialbalance/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 	-->					
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_trailbalance" >
                            <div class="x_content">
					
							
									<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">		
			<form method='post' action=''>
				<div class="row">
			<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>  
			<div class='col-md-3 col-sm-3 col-xs-12'>
			  <select name='school_id' class="form-control col-md-7 col-xs-12" id="school_id">
                                    <option value="">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
			</div>
			 <?php } ?> 
			 
			 <div class='col-md-3 col-sm-3 col-xs-12'>
			  <select autofocus="" id="filter_category" name="category" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
										$selected = 0;

                                        foreach ($voucher_category as $key=>$value) {
                                            ?>
                                            <option value="<?php echo $key ?>"<?php
                                            if (isset($category) && $category == $key) {
												$selected = 1;
                                                echo "selected = selected";
                                            }
											else if (!isset($category) && isset($school_info->category) &&  $school_info->category == $key ) 
                                            {
												$category =  $school_info->category;
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $value; ?></option>

                                            <?php
                                        }
                                        ?>
										</select> 
			</div>
			
			 
			<!-- <div class='col-md-3 col-sm-3 col-xs-12'>
			  <select autofocus="" id="filter_category" name="category" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
                                        foreach ($voucher_category as $key=>$value) {
                                            ?>
                                            <option value="<?php echo $key ?>"<?php
                                            if (isset($_POST['category']) && $_POST['category'] == $key) {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $value; ?></option>

                                            <?php
                                        }
                                        ?>
										</select> 
			</div>-->
			<?php if($financial_year->start_year >= '2021') { ?>
				</div>
				<br>
				<div class="row">
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<input type='text' name='filter_start_date' class='form-control' id='filter_start_date' value='<?php echo $filter_start_date;?>' autocomplete=off placeholder='Start Date'>
					</div>
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<input type='text' name='filter_end_date' class='form-control' id='filter_end_date' value='<?php echo $filter_end_date;?>' autocomplete=off placeholder='End Date'>
					</div>
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<input type='submit' name='submit' value='Filter' class='btn btn-default' />
					</div>
				</div>
			<?php } else { ?>
				<div class='col-md-3 col-sm-3 col-xs-12'>
			<input type='submit' name='submit' value='Filter' class='btn btn-default' />
			</div>
			</div>
			<?php } ?>
			</form>		
		</div>
		</div>
		<?php if(isset($result)){  ?>
		<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">	
							 <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
								<?php
									if( isset($category) && $category)
									{?>
									<tr>                                       
                                        <th colspan="3" style="text-align:center"><?php  echo $category ?></th>
                                    </tr>
									<?php } ?>
                                    <tr>                                       
                                        <th><?php echo $this->lang->line('accounts'); ?></th>
										<th style='text-align:right;'><?php echo $this->lang->line('dr'); ?></th>
										<th style='text-align:right;'><?php echo $this->lang->line('cr'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>   
									<?php  $index=1;
									foreach($result['groups'] as $obj){ ?>
									<tr class='printabled <?php print $obj['group_class']; ?>'> 
										<td>
											<a class='groupHead' data-toggle="collapse" href=".group<?php echo $index; ?>" aria-expanded="true" aria-controls="group<?php echo $index; ?>"  style="width:90%; float:left;font-weight:bold !important"><?php echo $obj['account_group_name']; ?><span style="float:right;"><i class="fa fa-chevron-up no-print" style='color:red;'></i></span></a>											
									</th>
										<td class='account_amt'><?php if($obj['group_total_debit']!=0){
											print number_format(abs($obj['group_total_debit']),2);
										}?></td>
										<td class='account_amt'><?php if($obj['group_total_credit']!=0){
											print number_format(abs($obj['group_total_credit']),2);
										}?></td>
									</tr>
										<!-- ledgers -->
										<?php foreach($obj['ledgers'] as $l){ 
										if($l->effective_balance ==0){ $trCls="zeroamtLedger";} else{$trCls='nonzeroamtLedger';}
										?>
										<tr class='printabled groupLedgers group<?php echo $index; ?> <?php print $trCls; ?> collapse in' aria-expanded="true">
										<td><a href="javascript:void(0);"><?php echo $l->name; ?></a></td>
										<td align='right'>
										<?php //if($l->dr_cr == 'DR'){
											if($l->effective_balance < 0){
												print number_format(abs($l->effective_balance),2); 
											} else if($l->dr_cr == 'DR' && $l->effective_balance == 0){
												print number_format(abs($l->effective_balance),2);
											}
												?></td>
										<td align='right'>
										<?php 
										//if($l->dr_cr == 'CR'){
											if($l->effective_balance > 0){
												print number_format($l->effective_balance,2); 
											} else if($l->dr_cr == 'CR' && $l->effective_balance == 0){
												print number_format(abs($l->effective_balance),2);
											}
											?>
										</td>
										</tr>
										<?php }
										$index++;
										} ?>
										<tr class='printabled'>
										<td>Difference in Opening Balances</td>
										<td align='right'>
											<?php if($result['debit_difference'] >0){
											print number_format($result['debit_difference'],2);
											 } ?>
										</td>
										<td align='right'>
											<?php if($result['credit_difference'] >0){
											print number_format($result['credit_difference'],2);
											 } ?>
										</td>
										</tr>
								</tbody>
								<tfoot>
									<th>Total</th>
									<th style='text-align:right;'><?php print number_format($result['final_amount'],2); ?></th>
									<th style='text-align:right;'><?php print number_format($result['final_amount'],2); ?></th>
								</tfoot>
							</table>
							</div>
							</div>
							<?php } ?>
							
							</div>
						</div>
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
	<?php /*if(isset($school_info->frontend_logo) && $school_info->frontend_logo!= ''){ ?>
				var schoollogo= '<?php print UPLOAD_PATH."/logo/".$school_info->frontend_logo; ?>';
	<?PHP } else*/if($this->global_setting->brand_logo){ ?> 
			var schoollogo=  '<?php echo UPLOAD_PATH."logo/".$this->global_setting->brand_logo; ?>';
	<?php } else {  ?>
	var schoollogo=  "<?php echo IMG_URL. '/sms-logo-50.png'; ?>";
	<?php } 
	$category_text =  isset($category) && $category ? " Company: $category" : "";
	if(isset($_POST['filter_start_date']) && $_POST['filter_start_date']!=''){
		
		$text_bottom='<div style="font-size:14px;font-weight:bold;">From: '.$_POST['filter_start_date'].' To: :'.$_POST['filter_end_date'].'</div><div style="font-size:14px;font-weight:bold;">Trial Balance '.$category_text.'</div>';
	}
	else{
		 if(isset($financial_year->session_year)){ 
		
		$message="Trial Balance ( ".$financial_year->session_year.") $category_text";
		$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 } else { 
	 $message="Trial Balance";
	$text_bottom='<div style="font-size:14px;font-weight:bold;">'.$message.'</div>';
	 }
		//$text_bottom="<div><h3>Trial Balance</h3></div>";
	}
	?>
		 $(document).ready(function() {
			 var toggle=1;
			  var table =$('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
			  "info": false,
			  iDisplayLength: -1,
				paging: false,								
              buttons: [ 			  
				  {					  
					'extend': 'print',
					//title: '<div style=""><span style=""><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>',	
					title: '',
					footer: true,
					exportOptions: {
                    rows: '.printabled',
					stripHtml: false
					},					
					customize: function ( win ) {
						$(win.document.body).css( 'margin', '20px' );
						if($(win.document).find('table').length)
						{
							$(win.document).find('h1').css("font-size",'180%')
							$(win.document).find('table').css("font-size",'10px');

							$(win.document).find('table').css("font-size",'10px');
							$(win.document).find('table').before('<div><span><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;"><?php print $school_info->school_name; ?></span></div><?php print $text_bottom; ?>');
							$(win.document).find('table').find('thead th').css({"width" : "60px","max-width": "60px;"})
							$(win.document).find('table').find('tbody td a').css("font-weight",'bold !important');
							$(win.document).find('.no-print').remove();
							
							// $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('voucher'); ?>")').css({"width" : "60px","max-width": "60px;"})
							//  $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('transaction_no'); ?>")').css({"width" : "60px","max-width": "60px;"})
							//$(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('narration'); ?>")').css({"width" : "30%","min-width": "30%"})
					
						/*	var debitIndex  = $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('debit'); ?>")').index();
							var creditIndex = $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('credit'); ?>")').index();
							var debitIndex  = debitIndex ? debitIndex+1 : debitIndex;
							var creditIndex  = creditIndex ? creditIndex+1 : creditIndex; */
							$(win.document).find('table').after("<div>Generated by : <?php echo $this->session->userdata('username'); ?></div><div>Date : <?php echo date('d/m/Y'); ?></div>");							
							/*$(win.document).find('table').find('tfoot th:nth-child('+debitIndex+')').html(''+total_debit_amount)
							$(win.document).find('table').find('tfoot th:nth-child('+creditIndex+')').html(''+total_credit_amount)*/
						}
					}
					
				},
				{
				text: 'Hide All Ledgers',			
					action: function ( e, dt, node, config ) {
						$(".groupHead").trigger("click");
						if(toggle ==1){
							toggle =0;
							$(".groupLedgers").removeClass("printabled");
							this.text("Show All Ledgers");
						}
						else{
							toggle =1;
							$(".groupLedgers").addClass("printabled");
							this.text("Hide All Ledgers");
						}						
						
						
					}
			  },
				{
				text: 'Hide Empty Ledgers',
					action: function ( e, dt, node, config ) {
						if($(".zeroamtLedger").is(":hidden")){
							$(".zeroamtLedger").addClass("printabled");
							$(".zeroamtLedger").show();
							
							$(".ZeroAmtGroup").addClass("printabled");
							$(".ZeroAmtGroup").show();
							this.text("Hide Empty Ledgers");
						}
						else{
							$(".zeroamtLedger").removeClass("printabled");
							$(".zeroamtLedger").hide();
							
							$(".ZeroAmtGroup").removeClass("printabled");
							$(".ZeroAmtGroup").hide();
							this.text("Show Empty Ledgers");
						}
					}
			  }
					
              ],			  
              searching: false, 
			  ordering: false,			  
              responsive: true
          });
		   });    
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
	   function get_trialbalance_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 	
		$('#filter_start_date').datepicker(
			{	startDate: '<?php print $f_start_date; ?>',
				endDate:'<?php print $f_end_date; ?>'}
		);
		$('#filter_end_date').datepicker({
			startDate: '<?php print $f_start_date; ?>',
				endDate:'<?php print $f_end_date; ?>'
		});
</script>