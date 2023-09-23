<style>
table{
	table-layout: fixed;
}
table td{
	word-wrap: break-word;
}
h5{ font-weight: 600; text-decoration: underline;}
 @media print {
	 table{
		fofnt-size:10px;
	}
 }
</style>
<?php 
$voucher_category = getVoucherCategory();?>
 <?php if($this->global_setting->brand_logo){ 
			$schoollogo=  UPLOAD_PATH.'logo/'.$this->global_setting->brand_logo; 
	} else { 
	$schoollogo=  IMG_URL. '/sms-logo-50.png';
	 } 		
	?> 
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('daybook'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>   
			<div class="x_content quick-link no-print">
				<?php $this->load->view('quicklinks/account'); ?>
			</div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">                    
                    <ul  class="nav nav-tabs bordered no-print">
                        <?php if(has_permission(VIEW, 'accounting', 'daybook')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_daybook"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('daybook'); ?></a> </li>
                       <?php } ?>                                          									
                    </ul>
                    <br/>                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_daybook" >
                            <div class="x_content">
							<div class="row no-print">
    <div class="col-md-12 col-sm-12 col-xs-12">		
			<form method='post' action='' id="daybook_search">
			<input type="hidden" name="order_by" value="<?php echo $order_by; ?>" id="order_by">
			<input type="hidden" name="order_dir" value="<?php echo  $order_dir ?>" id="order_dir">
			<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>     
			<div class='col-md-3 col-sm-3 col-xs-12'>
			  <select name='school_id' id="school_id" class="form-control col-md-7 col-xs-12">
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
                                        foreach ($voucher_category as $key=>$value) {
                                            ?>
                                            <option value="<?php echo $key ?>"<?php
                                            if (isset($_POST['category']) && $_POST['category'] == $key) {
												$category = $_POST['category'];
                                                echo "selected = selected";
                                            }
											else  if (empty($_POST['category']) && isset($school_info->category) &&  $school_info->category == $key) 
                                            {
												$category = $school_info->category;
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $value; ?></option>

                                            <?php
                                        }
                                        ?>
										</select> 
			</div>
				<div class='col-md-2 col-sm-2 col-xs-12'>
			<input type='text' name='start_date' class='form-control filter_date' id='start_date' autocomplete="off" value='<?php echo $start_date;?>'>
			</div>		
			<div class='col-md-2 col-sm-2 col-xs-12'>
			<input type='text' name='end_date' class='form-control filter_date' id='end_date' autocomplete="off" value='<?php echo $end_date;?>'>
			</div>			
				<div class='col-md-2 col-sm-2 col-xs-12'>
			<input type='submit' name='submit' value='Show Daybook' class='btn btn-default' id="form-submit-button"/>
			</div>
			</form>		
		</div>
		</div>	
							<?php if(isset($transactions) && isset($transactions)){  ?>							
			<div class="row no-print">
                <div class="col-xs-12 text-right">
				<?php if($filter_school_id) {?>	<a class="btn btn-success btn-sm" style="float:left" href="/daybookdev/download_yearbook/<?php echo $filter_school_id."/".$category; ?>" target="_blank">Download Year Book</a>	
					<? } ?>
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
			<div class="row print-only">
				
			<div style=""><span style=""><img width="65px" src="<?php print $schoollogo; ?>" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;font-weight:bold;"><?php print $school_info->school_name; ?></span></div><div style="font-size:14px;font-weight:bold;">Day Book Of: <?php print $start_date; echo isset($end_date) && $end_date ? " - ".$end_date : "" ?> - <?php print $category; ?></div>
			</div>
		<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">		
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr class=" no-print">
                                        <?php if(isset($category) && $category) {?>
										<th colspan="7" style="text-align:center">
											<?php echo $category ?>
											
										</th>
                                        <? } ?>
										
									</tr>      
									<tr>
										<th class="sorting" data-coloumn="AT.date" data-dir="<?php echo $order_by == "AT.date" ?  $order_dir : ""; ?>">Date</th>
										<th style="width:30%;">Ledger Entries</th>
										<th class="sorting" data-coloumn="V.name" data-dir="<?php echo $order_by == "V.name" ?  $order_dir : ""; ?>">Voucher</th>										
										<th class="sorting" data-coloumn="AT.transaction_no" data-dir="<?php echo $order_by == "AT.transaction_no" ?  $order_dir : ""; ?>">Transaction ID/Voucher No.</th>
										<th>Invoice/Reciept No.</th>
										<th>Debit Amount</th>
										<th>Credit Amount</th>
									</tr>                                   
                                </thead>
                                <tbody id="data-container"> 
									<?php 
									$total_debit=0;
										$total_credit=0;
										
									
									if(!empty($transactions)){
										foreach($transactions as $t){
										
											
											if($t->total_amount > 0){
											if($t->head_cr_dr == 'DR') {
												$total_debit += $t->total_amount;
											}
											else if($t->head_cr_dr == 'CR'){ 
												$total_credit += $t->total_amount;
											}
											?>
										<tr>
											<td><?php print date('d-m-Y',strtotime($t->date)); ?></td>
											<td style="width:30%;"><strong><?php print $t->ledger_name; ?></strong></td>
											<td><?php print $t->voucher_name." (".$t->voucher_category.")"; ?></td>	


											<?php $link =  isset($invoice_numbers[$t->invoice_id]) ? '<a href="'.site_url('accounting/invoice/view/'.$t->invoice_id).'" target="_blank">'.$invoice_numbers[$t->invoice_id].'</a>' : (isset($reciept_numbers[$t->donation_id]) ? '<a href="'.site_url('accounting/donation/view/'.$t->invoice_id).'" target="_blank" >'.$reciept_numbers[$t->donation_id].'</a>' : (isset($salary_payments[$t->salary_payment_id]) ? '<a href="'.site_url('payroll/payment/payslip/'.$t->salary_payment_id).'" target="_blank" >'.$salary_payments[$t->salary_payment_id].'</a>' : (isset($inventory_invoices[$t->inventory_id]) ? '<a href="'.site_url($inventory_url[$t->inventory_id].'/view/'.$t->inventory_id).'" target="_blank" >'.$inventory_invoices[$t->inventory_id].'</a>' : ""))); ?>


											<td>
												<?php if(!$link){?>
												<a href='<?php echo site_url('transactions/view/'.$t->id); ?>'>

													<?php print $t->transaction_no; ?></a>

												<?php } else{ ?>

													<?php print $t->transaction_no; ?>
												<?php } ?>
													</td>
											<td align='right'>
												<?php echo isset($invoice_numbers[$t->invoice_id]) ? '<a href="'.site_url('accounting/invoice/view/'.$t->invoice_id).'" target="_blank">'.$invoice_numbers[$t->invoice_id].'</a>' : (isset($reciept_numbers[$t->donation_id]) ? '<a href="'.site_url('accounting/donation/view/'.$t->invoice_id).'" target="_blank" >'.$reciept_numbers[$t->donation_id].'</a>' : (isset($salary_payments[$t->salary_payment_id]) ? '<a href="'.site_url('payroll/payment/payslip/'.$t->salary_payment_id).'" target="_blank" >'.$salary_payments[$t->salary_payment_id].'</a>' : (isset($inventory_invoices[$t->inventory_id]) ? '<a href="'.site_url($inventory_url[$t->inventory_id].'/view/'.$t->inventory_id).'" target="_blank" >'.$inventory_invoices[$t->inventory_id].'</a>' : ""))); ?>
											</td>
											<td align='right'><strong><?php if($t->head_cr_dr == 'DR') print number_format($t->total_amount,2); else print "&nbsp;"; ?></strong></td>
											<td align='right'><strong><?php if($t->head_cr_dr == 'CR') print number_format($t->total_amount,2); else print "&nbsp;"; ?></strong></td>
										</tr>
										<?php foreach($t->detail as $d){ 
										if($t->head_cr_dr == 'CR'){
											$total_debit += $d->amount;
										}
										else if($t->head_cr_dr == 'DR'){
											$total_credit += $d->amount;
										}
										?>
										<tr>
											<td>&nbsp;</td>
											<td style="width:30%;">  &nbsp;&nbsp;&nbsp; - <?php print $d->ledger_name; ?><br />
											 <?php if($d->remark) {?> &nbsp;&nbsp;&nbsp; <strong>Remark: </strong><?php print $d->remark; ?> <?php }?>
											</td>
											<td>&nbsp;</td>											
											<td>&nbsp;</td>
											<td ></td>
											<td align='right'><?php if($t->head_cr_dr == 'CR') print number_format($d->amount,2); else print "&nbsp;"; ?></td>
											<td align='right'><?php if($t->head_cr_dr == 'DR') print number_format($d->amount,2); else print "&nbsp;"; ?></td>
										</tr>
										<?php  }?>
										<tr>
											<td>&nbsp;</td>
											<td style="width:30%;"><strong>Narration : </strong><?php print $t->narration; ?></td>
											<td>&nbsp;</td>											
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td ></td>

										</tr>
									<?php } } }?>
								
									</tbody>
									<tfoot>
									<?php if( isset($transactions_count) && $transactions_count > $limit ) {?>
									<tr id="loadmore-div" class="no-print" style="text-align:center">
										<td colspan="6"><button class="btn btn-success" onclick="loadMore()">Load More</button></td>
									</tr>
									<?php }?>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th>Total</th>
										<td align='right'><strong><span id="total_debit"><?php print numberToCurrency($total_debit);  ?></span></strong></td>
										<td align='right'><strong><span id="total_credit"><?php print numberToCurrency($total_credit);  ?></span></strong></td>
									</tfoot>
									</table>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12" id="result">
									<button class="btn btn-sm btn-success" id="loadBalanceBt" onclick="loadBalance()" >Display Balance</button>	
                                    <div id="loading_div" style="display :none">	
																					<img  src=" <?php echo base_url() . 'assets/gif/loading.gif' ?> " id="loading_spinner" ></dib>
	</div>
	</div>	
<div class="row signature-bottom print-only">
<div>Generated by : <?php echo $this->session->userdata('username'); ?></div><div>Date : <?php echo date('d/m/Y'); ?></div>
<!--  <div class="col-md-12 col-sm-12 col-xs-12">	
<h4>Signature: __________ </h4>  
  </div>-->
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
		var __offset  		=  '<?php echo $offset ?>';
		var __transactions_count  =  <?php echo isset($transactions_count) ? $transactions_count : 0 ?>;
		var __school_id  	=  <?php echo isset($filter_school_id) ? $filter_school_id : 0 ?>;
		var __category  	=  '<?php echo isset($category) ? $category : 0 ?>';
		var __start_date  	=  '<?php echo isset($start_date) ? $start_date : 0 ?>';
		var __end_date  	=  '<?php echo isset($end_date) ? $end_date : 0 ?>';
		var __completed  	=  __transactions_count > 50 ? false : true;
		var __exeeded  		=  __transactions_count > 50 ? false : true;
		var __total_debit  	= '<?php echo isset($total_debit) ? $total_debit : 0 ?>';
		var __total_credit  = '<?php echo isset($total_credit) ? $total_credit : 0 ?>';
		var order_by		= "";
		var order_dir 		= "";
		$(document).ready(function(){
			console.log('loaded')

			$('.sorting').on('click',function(){
				order_by = $(this).attr('data-coloumn');
				if(order_by != '') {
					order_dir = $(this).attr('data-dir');
					console.log(order_dir)
					if(!order_dir) {
						order_dir = 'desc';
					} else if(order_dir == 'desc') {
						order_dir = 'asc';
					} else {
						order_dir = 'desc';
					}
					$('#order_by').val(order_by);
					$('#order_dir').val(order_dir);
					$('#form-submit-button').click();
				}
				
			})
		})
       function loadBalance(){
		   $('#loadBalanceBt').hide();
		   
			if(__school_id && __start_date && __category)
			{
				$('#loading_div').show();
				$.ajax({       
					type   : "POST",
					url    : "<?php echo site_url('daybookdev/get_summery'); ?>",
					data   : { school_id:__school_id,category : __category, start_date : __start_date,end_date : __end_date},               
					async  : true,
					dataType: "json",
					success: function(response){                                                   
						if(response)
						{  
							$('#result').html(response.html);
							$('#loading_div').hide();
						}
					}
					});

			}
		}
	   function get_daybook_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 	
		function loadMore()
		{
			if(!__completed && !__exeeded)
			{
				var order_by = $('#order_by').val();
				var order_dir = $('#order_dir').val();
				$('#order_by').val(order_dir);
				$.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('daybookdev/get_json_data'); ?>",
                data   : { school_id:__school_id,
					transcation_count : __transactions_count,
					offset : __offset,
					category : __category,
					 start_date : __start_date,
					 end_date : __end_date,
					 order_by : order_by,
					 order_dir : order_dir
					},               
                async  : false,
				dataType: "json",
                success: function(response){                                                   
                    if(response)
                    {  
						
						if(!response.exceeded)
						{
							if(response.total_debit)
							{
								__total_debit =  parseInt(__total_debit) +  parseInt(response.total_debit)
							}
							if(response.total_credit)
							{
								__total_credit =   parseInt(__total_debit) +  parseInt(response.total_debit)
							}
							$('#total_credit').html('₹ '+formatMoney(__total_credit))
							$('#total_debit').html('₹ '+formatMoney(__total_debit))
							$('#data-container').append(response.html)
					   		__offset = response.offset;
							   if(response.loading_completed)
							   {
								__completed = 0;
							   __exeeded = 0;
							   $('#loadmore-div').remove();
							   }
							   

							
						}
                      
                    }
                }
            	});
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
        
         
		 $('.filter_date').datepicker({
			startDate: '<?php print $f_start_date; ?>',
				endDate:'<?php print $f_end_date; ?>',
		 });
		 function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;

    return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}
</script>