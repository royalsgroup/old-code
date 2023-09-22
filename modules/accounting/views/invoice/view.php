<?php //echo "<pre>"; print_r($invoice);exit; 

?>
<style>
      .invoice-info
        {
            font-size : 12px !important;
        }
        .custom_col_10
    {
        width: 10% !important;
    }
    .custom_col_25
    {
        width: 20% !important;
    }
    .custom_col_24
    {
        width: 24% !important;
    }
    .custom_col_20
    {
        width: 20% !important;
    }
    address{
        margin-bottom:0px;
    }
     table{
        margin-bottom:0px !important;
    }
    @media print {
        body{
            margin-left:60px; ;

        }
       
    .pagebreak { page-break-after:auto; } /* page-break-after works, as well */
}
    </style>
<div class="row" id="printarea">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-calculator"></i><small> <?php echo $this->lang->line('manage_invoice'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link no-print"">
    <span><?php echo $this->lang->line('quick_link'); ?>:</span>
    <?php if(has_permission(VIEW, 'accounting', 'discount')){ ?>
        <a href="<?php echo site_url('accounting/discount/index'); ?>"><?php echo $this->lang->line('discount'); ?></a>                  
    <?php } ?> 
    
    <?php if(has_permission(VIEW, 'accounting', 'feetype')){ ?>
        | <a href="<?php echo site_url('accounting/feetype/index'); ?>"><?php echo $this->lang->line('fee_type'); ?></a>                  
    <?php } ?> 
    
    <?php if(has_permission(VIEW, 'accounting', 'invoice')){ ?>
        
        <?php if($this->session->userdata('role_id') == STUDENT || $this->session->userdata('role_id') == GUARDIAN){ ?>
            <!-- | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>                     -->
        <?php }else{ ?>
            | <a href="<?php echo site_url('accounting/invoice/add'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?></a>
            | <a href="<?php echo site_url('accounting/invoice/index'); ?>"><?php echo $this->lang->line('manage_invoice'); ?></a>
            <!-- | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>                     -->
        <?php } ?> 
    <?php } ?> 
        
    <?php if(has_permission(VIEW, 'accounting', 'duefeeemail')){ ?>
        | <a href="<?php echo site_url('accounting/duefeeemail/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('email'); ?></a>                  
    <?php } ?>
        <?php if(has_permission(VIEW, 'accounting', 'duefeesms')){ ?>
        | <a href="<?php echo site_url('accounting/duefeesms/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('sms'); ?></a>                  
    <?php } ?>         
            
    
</div> 
            
            <div class="x_content reciept" style="min-height:50%">
                <section class="content invoice ">
                   <div class="col-md-12 col-sm-12">
                         <!-- title row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header">
                                <h4><?php echo "Parent's Reciept" ?></h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header text-center">
                              
                            </div>
                        </div>
                         
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-md-3 col-sm-3 col-xs-3 invoice-col text-left custom_col_20">
                            <strong><?php echo $this->lang->line('student'); ?>:</strong>
                                <address>
                                    <?php echo $invoice->name." ($invoice->admission_no)";?>
                                    <br><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('name'); ?> : <?php echo $invoice->father_name; ?>
                                    <br><?php echo $invoice->present_address; ?>
                                    <br><?php echo $this->lang->line('class'); ?>: <?php echo $invoice->class_name; ?>
                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $invoice->phone; ?>
                                </address>
                            </div>
                           
                            <!-- /.col -->
                            <div class="col-md-1 col-sm-1 col-xs-1 invoice-col text-left custom_col_10" >
                                <div style="" class="">
                                <?php if($school->logo){ ?>
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="50" /> 
                                 <?php }else if($school->frontend_logo){ ?>
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="50"  /> 
                                 <?php }else{ ?>                                                        
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" width="50"   />
                                 <?php } ?>
                                 </div> 
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2 col-sm-2 col-xs-2 invoice-col text-left  custom_col_24">
                                <strong><?php echo $this->lang->line('school'); ?>:</strong>
                                <address>
                                    <?php echo $school->school_name; ?>
                                    <br><?php echo $school->address; ?>

                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $school->phone; ?>
                                    <br><?php echo $this->lang->line('email'); ?>: <?php echo $school->email; ?>
                                    
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2 col-sm-2 col-xs-2 invoice-col text-left custom_col_24">
                                 <br>Society Name : <?php echo $school->society_name; ?>
                                    <br>Society PAN No. : <?php echo $school->society_pan_no; ?>
                                    <br>80G Reg. No : <?php echo $school->school_80g_registration_no; ?>
                                 
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2 col-sm-2 col-xs-2 invoice-col text-left custom_col_20">
                               
                                <b> Fees Receipt <?php echo $invoice->custom_invoice_id;  ?></b>&nbsp;&nbsp;<b>
                                <br>
                                <b><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('status'); ?>:</b> <span class="btn-success"><?php echo get_paid_status($invoice->paid_status); ?></span>
                                <br><?php echo $this->lang->line('date'); ?>:</b> <?php echo strtotime($invoice->month) ?  date($this->global_setting->date_format, strtotime($invoice->month)) :$invoice->month  ; ?>                   
                                <br><b><?php echo $this->lang->line('transaction_id'); ?> :</b> <a href='<?php echo site_url('transactions/view/'.$invoice->account_transaction_id); ?>'><?php echo $invoice->transaction_no;  ?>  </a>
                                
                                <br><b><?php echo $this->lang->line('payment'); ?>  Mode  :</b> <?php echo $invoice->payment_method == 'upi_online' ? "UPI/Online" : $invoice->payment_method; ;?>   
                                <br><b>Account Ledger :</b> <?php echo $invoice->ledger;  /*?> 

                                <b><?php echo $this->lang->line('invoice');  ?> <?php echo $this->lang->line('number'); ?> :</b> <?php echo $invoice->transaction_no;  */?>        
                                <?php if($invoice->cheque_no &&  $invoice->payment_method == 'upi_online') { ?><br><b>UPI Refenece No : </b> <?php echo $invoice->cheque_no; ?> 
                                    <br><b>Bank Name : </b> <?php echo $invoice->bank_name; ?>
                                    <?}
                                     else if($invoice->cheque_no) { ?><br><b>Cheque No : </b> <?php echo $invoice->cheque_no; ?> 
                                    <br><b>Bank Name : </b> <?php echo $invoice->bank_name; ?>
                                    <?}?>        
                            </div>
                            <!-- /.col -->
                        </div>
                       
                        <!-- /.row -->
                    </div>
                    
                </section>
                <section class="content invoice">
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 ">
                            <table class="table table-striped">
                                <thead>
                                <?php $colspan =  6?>
                                <?php $firstcolspan = 4 ?>
                           <?php $secondcolspan = 3 ?>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                        <th><?php echo $this->lang->line('fee'). " ".$this->lang->line('amount'); ?></th>
                                        <th><?php echo $this->lang->line('emi'). " ".$this->lang->line('amount'); ?></th>
                                        <th >Recieved <?php echo $this->lang->line('amount'); ?></th>
                                        <th><?php echo $this->lang->line('due_amount') ?></th>
                                        <th><?php echo "Installment Due" ?></th>
                                    </tr>
                                </thead>
                                <tbody>   
									<?php if(isset($invoice->detail)){ 
									$subtotal=0;
                                    $total =0;
									$discount=0;
                                    $emi_due=0;
                                    $paid_amount_detail = 0;
									$index=1;
                                    $income_head_id = 0;
										foreach($invoice->detail as $obj){ 
                                            if($obj->emi_type)
                                            {
                                                if($obj->emi_type1 == "amount")
                                                {
                                                    $emi_amount = $obj->emi_per;
                                                }
                                                else
                                                {
                                                    $emi_amount = (float)$obj->gross_amount*($obj->emi_per/100);
                                                }

                                                $emi_paid = $emi_data[$obj->emi_type] ?? $obj->net_amount;

                                                $emi_due = $emi_amount- $emi_paid ;
                                            }
                                            else
                                            {
                                                $emi_amount = 0;
                                            }
                                            if($income_head_id != $obj->income_head_id )
                                            {
                                                $paid_amount_detail =0;
                                                $income_head_id = $obj->income_head_id;
                                                $gross_amount = $obj->gross_amount -  $obj->paid_amount + $obj->net_amount;
                                            }
                                            $due_amount = $obj->gross_amount  - $obj->net_amount - $obj->discount -   $obj->paid_amount - $paid_amount_detail ;;
                                            $due_amount = $due_amount < 0 ? 0 : $due_amount ;
                                            // $due_amount .= " ( $obj->gross_amount   - $obj->discount -   $obj->paid_amount - $paid_amount_detail ;;)";
                                            $discount = $discount+ $obj->discount;
                                            $paid_amount_detail =  $paid_amount_detail  + $obj->net_amount + $obj->discount;
                                            //$due_amount = $invoice->gross_amount - $invoice->paid_amount;;
                                         //echo "<pre>"; print_r($invoice);exit; 
        
                                            ?>
										 <tr>
                                        <td  style="width:4%"><?php echo $index?></td>
                                        <td  style="width:35%"> <?php echo $obj->head;echo $obj->emi_type && $obj->emi_name ? " ".$obj->emi_name : ""; ?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo  $obj->gross_amount ?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo   $emi_amount; ?></td>
                                        <td ><?php echo $school->currency_symbol; ?><?php echo $obj->net_amount; ?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $due_amount  ?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo   $emi_due; ?></td>
                                    </tr> 
										<?php 
										$subtotal += $obj->net_amount;
                                        $total = $subtotal+ $discount;
										$index++; } 
									} else {
                                        $emi_amount = 0;
                                        $emi_due = 0 ;
                                        if($invoice->paid_status == "paid" || $invoice->paid_status == "partial")
                                        {
                                            if($invoice->emi_type)
                                            {
                                               
                                                if($invoice->emi_type1 == "amount")
                                                {
                                                    $emi_amount = $invoice->emi_per;
                                                }
                                                else
                                                {
                                                    $emi_amount = (float)$invoice->gross_amount*($invoice->emi_per/100);
                                                }
                                                $emi_due = $emi_amount  - $invoice->net_amount;
                                            }
                                            else
                                            {
                                                $emi_amount = 0;
                                            }
                                            $subtotal= $invoice->net_amount;
                                            $total = $subtotal+$invoice->inv_discount;
                                        }
                                        else
                                        {
                                            $subtotal= $invoice->gross_amount;
                                            $total = $invoice->net_amount;
                                        }
                                     
                                        $due_amount = $invoice->gross_amount- $invoice->paid_amount;;
                                        $due_amount = $due_amount < 0 ? 0 : $due_amount ;

										$discount=  (int)$invoice->inv_discount > 0 ? $invoice->inv_discount : 0;

                                        if($invoice->previous_due_amount) $total=$total + $invoice->previous_due_amount;
										?>
                                  
                                    <tr>
                                        <td  style="width:4%"><?php echo 1; ?></td>
                                        <td  style="width:35%"> <?php  echo $invoice->head; echo $invoice->emi_type && $invoice->emi_name ? " ".$invoice->emi_name : ""; ?></td>
                                        <td  style="width:10%"><?php echo $school->currency_symbol; ?> <?php echo $invoice->gross_amount; ?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $emi_amount  ?></td>
                                        <td ><?php echo $school->currency_symbol; ?><?php echo $invoice->paid_status == "paid" || $invoice->paid_status == "partial" ? $invoice->net_amount : $invoice->gross_amount; ?></td>

                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $due_amount  ?></td>
                                        <td  style="width:10%"><?php echo $school->currency_symbol; ?> <?php echo $emi_due; ?></td>
                                    </tr> 
									<?php } ?>
                                </tbody>
								<tfoot>
                                    <tr>
										<th colspan=<?php echo  $firstcolspan; ?>>Discount</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($discount); ?></th>
									</tr>
									<tr>
										<th colspan=<?php echo  $firstcolspan;  ?>>Paid Amount</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($subtotal); ?></th>
									</tr>
                                    <?php if($invoice->previous_due_amount) {?>
                                        <tr>
										<th colspan=<?php echo  $firstcolspan; ?>>Previous Due</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($invoice->previous_due_amount); ?></th>
									</tr>
                                     <?php }?>
									<!-- <tr>
										<th colspan=<?php echo  $firstcolspan; ?>>Total</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($total); ?></th>
									</tr> -->
								</tfoot>
                            </table>
                        </div>
                        <!-- /.col -->

                    </div>
                    <!-- /.row -->

                    <div class="row">
     
                        <!-- /.col -->
                       <!-- <div class="col-xs-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:50%"><?php echo $this->lang->line('subtotal'); ?>:</th>
                                            <td><?php echo $school->currency_symbol; ?><?php echo $invoice->gross_amount; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('discount'); ?></th>
                                            <td><?php echo $school->currency_symbol; ?><?php  echo $invoice->inv_discount; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('total'); ?>:</th>
                                            <td><?php echo $school->currency_symbol; ?><?php echo $invoice->net_amount + $invoice->inv_discount; ?></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        
                                        <tr>
                                            <th><?php echo $this->lang->line('paid'); ?> <?php echo $this->lang->line('amount'); ?>:</th>
                                            <!-- <td><?php echo $school->currency_symbol; ?><?php echo $paid_amount ? $paid_amount : 0.00; ?></td> -->
                     <!--                        <td><?php echo $school->currency_symbol; ?><?php echo $paid_amount ? $paid_amount : 0.00; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('due_amount'); ?>:</th>
                                            <td><span class="btn-danger" style="padding: 5px;"><?php echo $school->currency_symbol; ?><?php echo $invoice->due_amount;?></span></td>
                                        </tr>
                                        <?php if($invoice->paid_status == 'paid'){ ?>
                                            <tr>
                                                <th><?php echo $this->lang->line('paid'); ?> <?php echo $this->lang->line('date'); ?>:</th>
                                                <td><?php echo date($this->global_setting->date_format, strtotime($invoice->date)); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>-->
                        <div class="content row">
                            <div class=" col-xs-6 ">
                        &nbsp;&nbsp;<b>Remark : </b> <?php echo $invoice->note;  ?>
                                            </div>
                                            <div class=" col-xs-6 " style="text-align:right;">
                        <!-- /.col --> <b> &nbsp;&nbsp;Signature of Cashier ________________ </b>
                        </div>
                    </div>
                     
                    </div>
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            
                            <?php if($invoice->paid_status != 'paid'){ ?>
                                <a href="<?php echo site_url('accounting/payment/index/'.$invoice->inv_id); ?>"><button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> <?php echo $this->lang->line('submit'); ?> <?php echo $this->lang->line('payment'); ?></button></a>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
            <div class="clearfix"></div>
            <hr>
            <?php if(isset($invoice->detail) && count($invoice->detail) > 4){  ?>
                <div class="pagebreak"> </div>

                <?php }  ?>
              <div class="x_content  reciept">
                <section class="content invoice">
                   <div class="col-md-12 col-sm-12">
                         <!-- title row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header">
                               <h4><?php echo "School's Reciept" ?></h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header text-center">
                             
                            </div>
                        </div>
                         
                        <!-- info row -->
                        <div class="row invoice-info">
                        <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left custom_col_20">
                                <strong><?php echo $this->lang->line('student'); ?>:</strong>
                                <address>
                                    <?php echo $invoice->name." ($invoice->admission_no)"; ?>

                                     <br><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('name'); ?> : <?php echo $invoice->father_name; ?>
                                    <br><?php echo $invoice->present_address; ?>
                                    <br><?php echo $this->lang->line('class'); ?>: <?php echo $invoice->class_name; ?>
                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $invoice->phone; ?>
                                </address>
                            </div>
                         
                            <div class="col-md-1 col-sm-1 col-xs-1 invoice-col text-left custom_col_10" >
                                <div style="" class="">
                                <?php if($school->logo){ ?>
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="50" /> 
                                 <?php }else if($school->frontend_logo){ ?>
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="50"  /> 
                                 <?php }else{ ?>                                                        
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" width="50"   />
                                 <?php } ?>
                                 </div> 
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2 col-sm-2 col-xs-2 invoice-col text-left custom_col_24">
                                <strong><?php echo $this->lang->line('school'); ?>:</strong>
                                <address>
                                    <?php echo $school->school_name; ?>
                                    <br><?php echo $school->address; ?>

                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $school->phone; ?>
                                    <br><?php echo $this->lang->line('email'); ?>: <?php echo $school->email; ?>
                                    
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2 col-sm-2 col-xs-2 invoice-col text-left custom_col_24">
                                 <br>Society Name : <?php echo $school->society_name; ?>
                                    <br>Society PAN No. : <?php echo $school->society_pan_no; ?>
                                    <br>80G Reg. No : <?php echo $school->school_80g_registration_no; ?>
                                 
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2 col-sm-2 col-xs-2 invoice-col text-left custom_col_20">
                               
                                <b> Fees Receipt <?php echo $invoice->custom_invoice_id;  ?></b>&nbsp;&nbsp;<b>
                                <br>
                                <b><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('status'); ?>:</b> <span class="btn-success"><?php echo get_paid_status($invoice->paid_status); ?></span>
                                <br><?php echo $this->lang->line('date'); ?>:</b> <?php echo strtotime($invoice->month) ?  date($this->global_setting->date_format, strtotime($invoice->month)) :$invoice->month  ; ?>                   
                                <br><b><?php echo $this->lang->line('transaction_id'); ?> :</b> <a href='<?php echo site_url('transactions/view/'.$invoice->account_transaction_id); ?>'><?php echo $invoice->transaction_no;  ?></a>
                                <br><b><?php echo $this->lang->line('payment'); ?>  Mode  :</b> <?php echo $invoice->payment_method == 'upi_online' ? "UPI/Online" : $invoice->payment_method; ;?>   
                                <br><b>Account Ledger :</b> <?php echo $invoice->ledger;  ?>

                                <?php if($invoice->cheque_no &&  $invoice->payment_method == 'upi_online') { ?><br><b>Refenece No : </b> <?php echo $invoice->cheque_no; ?> 
                                    <br><b>Bank Name : </b> <?php echo $invoice->bank_name; ?>
                                    <?}
                                     else if($invoice->cheque_no) { ?><br><b>Cheque No : </b> <?php echo $invoice->cheque_no; ?> 
                                    <br><b>Bank Name : </b> <?php echo $invoice->bank_name; ?>
                                    <?}?>       
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- <div class="row">
                            <div class="col-xs-12 table">
                            <?php// echo $invoice->note;  ?>
                            </div>
                        </div> -->
                        <!-- /.row -->
                    </div>
                    
                </section>
                <section class="content invoice">
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 ">
                           <table class="table table-striped">
                           <?php $colspan = 6 ?>
                          
                        
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                        <th><?php echo $this->lang->line('fee'). " ".$this->lang->line('amount'); ?></th>
                                        <th><?php echo $this->lang->line('emi'). " ".$this->lang->line('amount'); ?></th>

                                        <th >Recieved <?php echo $this->lang->line('amount'); ?></th>

                                        <th><?php echo $this->lang->line('due_amount') ?></th>
                                        <th><?php echo "Installment Due" ?></th>
                                    </tr>
                                </thead>
                                <tbody>   
									<?php if(isset($invoice->detail)){ 
									$subtotal=0;
									$discount=0;
                                    $paid_amount_detail = 0;
                                    $emi_due = 0;
									$index=1;
                                    $income_head_id = 0;

										foreach($invoice->detail as $obj){ 
                                            if($obj->emi_type)
                                            {
                                               
                                                if($obj->emi_type1 == "amount")
                                                {
                                                    $emi_amount = $obj->emi_per;
                                                }
                                                else
                                                {
                                                    $emi_amount = (float)$obj->gross_amount*($obj->emi_per/100);
                                                }
                                                $emi_paid = $emi_data[$obj->emi_type] ?? $obj->net_amount;

                                                $emi_due = $emi_amount- $emi_paid ;
                                            }
                                            else
                                            {
                                                $emi_amount = 0;
                                            }
                                            if($income_head_id != $obj->income_head_id )
                                            {
                                                $paid_amount_detail =0;
                                                $income_head_id = $obj->income_head_id;
                                                $gross_amount = $obj->gross_amount -  $obj->paid_amount + $obj->net_amount;
                                            }
                                            $due_amount = $obj->gross_amount  - $obj->net_amount - $obj->discount -   $obj->paid_amount - $paid_amount_detail ;;
                                            $due_amount = $due_amount < 0 ? 0 : $due_amount ;

                                            $discount = $discount+ $obj->discount;
                                            $paid_amount_detail =  $paid_amount_detail  + $obj->net_amount + $obj->discount;
                                            ?>
                                        
										 <tr>
                                        <td  style="width:5%"><?php echo $index; ?></td>
                                        <td  style="width:35%"> <?php echo $obj->head; echo $obj->emi_type && $obj->emi_name ? " ".$obj->emi_name : "";?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $obj->gross_amount;  ?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $emi_amount; ?></td>
                                        <td ><?php echo $school->currency_symbol; ?><?php echo $obj->net_amount; ?></td>


                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $due_amount  ?></td>
                                        <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $emi_due; ?></td>
                                    </tr> 
										<?php 
										$subtotal += $obj->net_amount;
                                        $total = $subtotal+ $discount;
										$index++; } 
									} else {
                                        $emi_due = 0;
                                        $emi_amount = 0 ;
                                        $discount=  (int)$invoice->inv_discount >0 ? $invoice->inv_discount : 0;
                                        if($invoice->paid_status == "paid" || $invoice->paid_status == "partial")
                                        {
                                            if($invoice->emi_type)
                                            {
                                                if($invoice->emi_type1 == "amount")
                                                {
                                                    $emi_amount = $invoice->emi_per;
                                                }
                                                else
                                                {
                                                    $emi_amount = (float)$invoice->gross_amount*($invoice->emi_per/100);
                                                }
                                                $emi_due = $emi_amount -$invoice->net_amount;
                                            }
                                            else
                                            {
                                                $emi_amount = 0;
                                            }
                                            $subtotal= $invoice->net_amount;
                                            $total = $subtotal+$invoice->inv_discount;
                                        }
                                        else
                                        {
                                            $subtotal= $invoice->gross_amount;
                                            $total = $invoice->net_amount;
                                        }
                                       
                                        $due_amount = $invoice->gross_amount- $invoice->paid_amount;;
                                        $due_amount = $due_amount < 0 ? 0 : $due_amount ;

                                        if($invoice->previous_due_amount) $total=$total + $invoice->previous_due_amount;
										?>
                                    <tr>
                                        <td  style="width:5%"><?php echo 1; ?></td>
                                        <td  style="width:35%"> <?php echo $invoice->head; echo $invoice->emi_type && $invoice->emi_name ? " ".$invoice->emi_name : "";?></td>
                                        <td  style="width:10%"><?php echo $school->currency_symbol; ?> <?php echo $invoice->gross_amount; ?></td>
                                        <td  style="width:10%"><?php echo $school->currency_symbol; ?> <?php echo $emi_amount; ?></td>


                                       <td ><?php echo $school->currency_symbol; ?><?php echo $invoice->paid_status == "paid" || $invoice->paid_status == "partial" ? $invoice->net_amount : $invoice->gross_amount; ?></td>
                                       <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $due_amount; ?></td>
                                       <td  style="width:10%"> <?php echo $school->currency_symbol; ?><?php echo $emi_due; ?></td>

                                    </tr> 
									<?php } ?>
                                </tbody>
								<tfoot>
                                    <tr>
										<th colspan=<?php echo  $firstcolspan; ?>>Discount</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($discount); ?></th>
									</tr>
                                    <tr>
										<th colspan=<?php echo  $firstcolspan;  ?>>Paid Amount</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($subtotal); ?></th>
									</tr>
                                    <?php if($invoice->previous_due_amount) {?>
                                        <tr>
										<th colspan=<?php echo  $firstcolspan; ?>>Previous Due</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($invoice->previous_due_amount); ?></th>
									</tr>
                                     <?php }?>
									<!-- <tr>
										<th colspan=<?php echo  $firstcolspan; ?>>Total</th>
										<th colspan=<?php echo  $secondcolspan; ?> ><?php echo numberToCurrency($total); ?></th>
									</tr> -->
								</tfoot>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="content row">
                            <div class=" col-xs-6 ">
                        &nbsp;&nbsp;<b>Remark : </b> <?php echo $invoice->note;  ?>
                                            </div>
                                            <div class=" col-xs-6 " style="text-align:right;">
                        <!-- /.col --> <b> &nbsp;&nbsp;Signature of Cashier ________________ </b>
                        </div>
                    </div>
                    <!-- /.row -->
                    <footer>
                        <span class="footer_text_custom">
                    Generated by : <?php 
                    $prin_uname = $this->db->select('username')->from('users')->where('id', $invoice->inv_created_by)->get()->row();
                    echo $prin_uname->username ;
                    //echo $this->session->userdata('username'); ?>  
                    Date  :  <?php echo $invoice->inv_created_at; //echo   date('Y-m-d'); ?></span>
                    </footer>

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" id="printBtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                            <?php if($invoice->paid_status != 'paid'){ ?>
                                <a href="<?php echo site_url('accounting/payment/index/'.$invoice->inv_id); ?>"><button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> <?php echo $this->lang->line('submit'); ?> <?php echo $this->lang->line('payment'); ?></button></a>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#printBtn").click(function () {
    $("#printarea").printThis({ importCSS: true,importStyle: true
});
 

});
$('head').append(`<style>
     @media print 
{
    footer {
    float:right;
    padding:0px;
    margin:0px;
    position: fixed;
    bottom: 0;
    right:0;
  }
  .footer_text_custom
  {
      font-size:80%;
      right:0;
      text-align:right;
      float:right;
      }

      .reciept {
            height : 50%;
        }


}
            </style>`);

</script>
