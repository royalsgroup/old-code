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
				<div class="row no-print" style="min-height:30px">
                	<h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('view')." ".$this->lang->line('entry'); ?></small></h3>
              
				</div>	 
                
				<div class="row no-print" >
					<div class="col-xs-6 ">		
						<?php if(isset($transaction->prev_id) && $transaction->prev_id) {?>
						<a class='btn btn-success' href="<?php echo site_url('transactions/view/'.$transaction->prev_id); ?>" >Previous Entry</a>		
						<?php }?>
					</div>
					<div class="col-xs-6 text-right" >	
						<a class='btn btn-info' href="<?php echo site_url('transactions/create/'.$transaction->voucher_id); ?>" >New Entry</a>		
						<?php if(isset($transaction->next_id) && $transaction->next_id) {?>
							<a class='btn btn-success' href="<?php echo site_url('transactions/view/'.$transaction->next_id); ?>" >Next Entry</a>		
						<?php }?>
						<ul class="nav navbar-right panel_toolbox">
							<li>
							<?php if($transaction->cancelled == 0){ ?>
							<a class='btn btn-danger btn-xs' href="<?php echo site_url('transactions/revert/'.$transaction->id); ?>" onclick="javascript: return confirm('Are you sure?');">Revert Entry</a>
							<?php } else{ ?>
							<span class='btn btn-success btn-xs'> Entry Cancelled </span>
							
							<?php	} ?>
							</li>
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                		</ul>
					</div>
				</div>	 
				<div class="clearfix"></div>
            </div>   
			        
             <div class="x_content quick-link no-print">
				<?php $this->load->view('quicklinks/account'); ?>
			</div>
            <div class="x_content">
			<div class="row no-print">
                <div class="col-xs-12 text-right">				
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>	
			
			<div class="row print-only">				
				<div style=""><span style=""><img width="65px" src="<?php print $schoollogo; ?>" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:28px;font-weight:bold;"><?php print $school_info->school_name; ?></span></div><div><h3>Transaction Entry</h3></div>		
			</div>
			<div class='row'>
			<div class='col-md-12'>
			<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">                               
                                <tbody> 
										<tr>
											<th width="30%"><?php echo $this->lang->line('voucher')." ".$this->lang->line('name'); ?></th>
											<td><?php echo $transaction->voucher_name; ?></td>
                                        </tr>
                                        <tr>
											<th width="30%">Transaction / Receipt No. #</th>
											<td><?php echo $transaction->transaction_no; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('head_ledger'); ?></th>
											<td><?php echo $transaction->ledger_name ." ( ".$transaction->head_cr_dr." )"; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('narration'); ?></th>
											<td><?php echo $transaction->narration; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('date'); ?></th>
											<td><?php echo date('d-m-Y',strtotime($transaction->date)); ?></td>
                                        </tr>
										<?php 
											if($transaction->cheque_no)
											{?>
										<tr>
											<th width="30%">cheque No :</th>
											<td><?php echo $transaction->cheque_no; ?></td>
                                        </tr>	
									
										<tr>
											<th width="30%">Bank Name :</th>
											<td><?php echo $transaction->bank_name; ?></td>
                                        </tr>	
										<?php } ?>
										<!-- <tr>
											<th width="30%">Voucher Budget</th>
											<td><?php echo number_format($transaction->budget,2,'.','') ?></td>
                                        </tr>
										<tr>
											<th width="30%">Used Budget</th>
											<td><?php echo number_format($transaction->total_amount,2,'.','') ?></td>
                                        </tr>	
										<tr>
											<th width="30%">Remaining Budget</th>
											<td><?php echo number_format(($transaction->budget-$transaction->total_amount),2,'.','') ?></td>
                                        </tr>	 -->
										
										<tr>
											<th width="30%">Reciever Name</th>
											<td><?php 
											if($transaction->created_by >0)
											{
												$user=get_user_by_id($transaction->created_by);
												print $user->name;
											}
											else
											{
												echo $transaction->reciever_name ;

											}
											
											 ?></td>
                                        </tr>
                                      
                                </tbody>
                            </table>  
			</div>
			</div>
			<div class='row'>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<h5 class="column-title"><strong>Particulars:</strong>					
					</h5>
				</div>
				<div class='col-md-12 col-sm-12 col-xs-12'>
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">                               
                                <thead> 
								 <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('ledger'); ?></th>
										<th><?php echo $this->lang->line('remark'); ?></th>
										<th><?php echo $this->lang->line('amount'); ?></th>
                                    </tr>
									  </thead>
                                <tbody>   
                                    <?php $count = 1; 
									if(isset($transaction_detail) && !empty($transaction_detail)){ ?>
                                        <?php 
										$total_ammount=0;
										foreach($transaction_detail as $obj){
											$total_ammount+=$obj->amount;

										?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
											<td><?php echo $obj->ledger_name; ?></td>
											<td><?php echo $obj->remark; ?></td>
											<td align="right"><?php echo number_format($obj->amount,2); ?></td>
										</tr>
									<?php } } ?>
								</tbody>
								<tfoot>
								<tr>
									<th colspan="3">Total Amount</th>
									<th align="right" style="text-align:right;"><?php print $total_ammount; ?></th>
								</tr>
								</tfoot>
								</table>
				</div>
			</div>			  
            </div>
        </div>
    </div>
</div>
	