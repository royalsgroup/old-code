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
<?php $voucher_category = getVoucherCategory();?>
 <?php 

 if($this->global_setting->brand_logo){ 
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
			<form method='post' action=''>
			<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>     
			<div class='col-md-3 col-sm-3 col-xs-12'>
			  <select name='school_id' class="form-control col-md-7 col-xs-12">
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
                                                echo "selected = selected";
                                            }
											else  if (empty($_POST) && isset($school_info->category) &&  $school_info->category == $key) 
                                            {
                                                echo "selected = selected";
                                            }
                                            ?>><?php echo $value; ?></option>

                                            <?php
                                        }
                                        ?>
										</select> 
			</div>
				<div class='col-md-3 col-sm-3 col-xs-12'>
			<input type='text' name='filter_date' class='form-control' id='filter_date' value='<?php echo $filter_date;?>'>
			</div>			
				<div class='col-md-3 col-sm-3 col-xs-12'>
			<input type='submit' name='submit' value='Show Daybook' class='btn btn-default' />
			</div>
			</form>		
		</div>
		</div>	
							<?php if(isset($result) && isset($result)){  ?>							
			<div class="row no-print">
                <div class="col-xs-12 text-right">
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
			<div class="row print-only">
			<div style=""><span style=""><img width="65px" src="<?php print $schoollogo; ?>" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;font-weight:bold;"><?php print $school_info->school_name; ?></span></div><div style="font-size:14px;font-weight:bold;">Day Book Of: <?php print $filter_date; ?></div>
			</div>
		<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">		
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
									<tr>
										<th>Date</th>
										<th style="width:30%;">Ledger Entries</th>
										<th>Voucher</th>										
										<th>Transaction ID/Voucher No.</th>
										<th>Debit Amount</th>
										<th>Credit Amount</th>
									</tr>                                   
                                </thead>
                                <tbody> 
									<?php 
									$total_debit=0;
										$total_credit=0;
									if(!empty($transactions)){
										foreach($transactions as $t){ if($t->total_amount > 0){
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
											<td><a href='<?php echo site_url('transactions/view/'.$t->id); ?>'><?php print $t->transaction_no; ?></a></td>
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
											  &nbsp;&nbsp;&nbsp; <strong>Remark: </strong><?php print $d->remark; ?>
											</td>
											<td>&nbsp;</td>											
											<td>&nbsp;</td>
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
											
										</tr>
									<?php } } }?>
									</tbody>
									<tfoot>
										<th></th>
										<th></th>
										<th></th>
										<th>Total</th>
										<td align='right'><strong><?php print numberToCurrency($total_debit);  ?></strong></td>
										<td align='right'><strong><?php print numberToCurrency($total_credit);  ?></strong></td>
									</tfoot>
									</table>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">		
											<h5>Cash</h5>
											<p>Opening Balance - <?php print number_format(abs($result['cash'][0]->opening_balance_date),2). " "; ?>
											<?php if($result['cash'][0]->opening_balance_date < 0 ){ print "DR"; } 
											else if($result['cash'][0]->opening_balance_date ==0){
												print '';
											}
											else { print "CR"; } ?> | Closing Balance - <?php print number_format(abs($result['cash'][0]->closing_balance_date),2). " "; ?>
											<?php if($result['cash'][0]->closing_balance_date < 0 ){ print "DR"; } 
											else if($result['cash'][0]->closing_balance_date ==0){
												print '';
											}
											else { print "CR"; } ?>
											</p>
											<?php foreach($result['bank'] as $bank){ 
											foreach($bank as $l){ ?>
											
											<h5><?php print $l->name; ?></h5>
											<p>Opening Balance - <?php print number_format(abs($l->opening_balance_date),2). " "; ?>
											<?php if($l->opening_balance_date < 0 ){ print "DR"; } 
											else if($l->opening_balance_date ==0){
												print '';
											}
											else { print "CR"; } ?> | Closing Balance - <?php print number_format(abs($l->closing_balance_date),2). " "; ?>
											<?php if($l->closing_balance_date < 0 ){ print "DR"; } 
											else if($l->closing_balance_date ==0){
												print '';
											}
											else { print "CR"; } ?>
											</p>
											<?php } } ?>														
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
	   function get_daybook_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 	
		 $('#filter_date').datepicker({
			startDate: '<?php print $f_start_date; ?>',
				endDate:'<?php print $f_end_date; ?>',
		 });
</script>