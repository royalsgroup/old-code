<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-dollar"></i><small> <?php echo $this->lang->line('payslip'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
			<div class="x_content"> 
			<div>
				<input type="button" class='btn btn-default' onclick="printDiv('pageCnt')" value="Print" />
			</div>
				<div class='printContent' id='pageCnt'>
					<div class='col-md-12 col-sm-12 col-xs-12'>
						<table class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
							<tr>
								<td><?php 									
								if($this->global_setting->brand_logo){ ?>
                     <img src="<?php echo UPLOAD_PATH.'logo/'.$this->global_setting->brand_logo; ?>" width="65px" style="max-width: 65px;" alt="">
                <?php }else{ ?>
                     <img class="logo" src="<?php echo IMG_URL; ?>/sms-logo-50.png" alt="">
                <?php } ?></td>
								<td colspan='2' style='text-align:center;'><h3><?php print $school->school_name; ?></h3></td>
								<th>Salary Payment: #<?php print $payment->id; ?></th>
							</tr>
							<tr>
								<th>Transaction No.</th>
								<td><?php print $transaction->transaction_no; ?></td>
								<th>Date</th>
								<td><?php print date('d-m-Y',strtotime($payment->created_at)); ?></td>
							</tr>
							<tr>
								<th>Head Account</th>
								<td><?php print $transaction->ledger_name; ?></td>
								<th></th>
								<td><?php print $transaction->head_cr_dr; ?></td>
							</tr>
							<tr>
								<th>Narration</th>
								<td colspan='3'><?php print $transaction->narration; ?></td>								
							</tr>
							<tr>
								<td colspan='4'></td>
							</tr>
							<tr>
								<th colspan='4'>Particulars</th>
							</tr>
							<tr>
								<th>S No.</th>
								<th>Particular</th>
								<th>Remark</th>
								<th>Amount</th>
							</tr>
							<?php $count=1; foreach($transaction_detail as $td){ ?>
							<tr>
								<td><?php print $count; ?></td>
								<td><?php print $td->ledger_name; ?></td>
								<td></td>
								<td><?php print $school->currency_symbol. " ".number_format(abs($td->amount),2); ?></td>
							</tr>
							<?php $count++; } ?>
						</table>										
					</div>						
				</div>
			 </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function printDiv(divName) {
     //var printContents = "<div><h2><?php print $accountledger->school_name; ?></h2></div><div><h3><?php print $accountledger->name; ?> Entries</h3></div>";
	 printContents = document.getElementById(divName).innerHTML;	 
    var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;	  
}		
</script>