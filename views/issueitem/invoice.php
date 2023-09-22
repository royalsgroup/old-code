<?php //echo "<pre>"; print_r($invoice);exit; ?>
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
    @media print {
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
            <?php if(has_permission(VIEW, 'inventory', 'itemcategory')){ ?>    
                     
                     <?php if(has_permission(VIEW, 'inventory', 'issueitem')){ ?>
                         <a href="<?php echo site_url('issueitem/'); ?>"><?php echo $this->lang->line('issueitem') ?> </a> |                      
                         <?php } ?>
                     <?php if(has_permission(VIEW, 'inventory', 'itemstock')){ ?>
                             <a href="<?php echo site_url('itemstock/'); ?>"><?php echo $this->lang->line('item')." ".$this->lang->line('stock'); ?> </a>  |                                           
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'item')){ ?>
                           <a href="<?php echo site_url('item/'); ?>"><?php echo $this->lang->line('item'); ?> </a> |                                               
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemcategory')){ ?>
                           <a href="<?php echo site_url('itemcategory/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('category'); ?></a> |                                         
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemstore')){ ?>
                            <a href="<?php echo site_url('itemstore/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('store'); ?></a>  |                                          
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemsupplier')){ ?>
                      <a href="<?php echo site_url('itemsupplier/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('supplier'); ?></a>  |                                           
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemgroup')){ ?>
                      <a href="<?php echo site_url('itemgroup/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('group'); ?></a>  |                                           
                         <?php } ?>
             <?php } ?>
            
    
</div> 
            
            <div class="x_content">
                <section class="content invoice ">
                   <div class="col-md-12 col-sm-12">
                         <!-- title row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header">
                                <h4><?php echo "Sale Invoice" ?></h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header text-center">
                              
                            </div>
                        </div>
                         
                        <!-- info row -->
                        <div class="row invoice-info">
                           
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
                                 <br>Voucher Name : <?php echo $invoice->voucher." (".$invoice->voucher_category.")"; ?>
                                    <br>Debit Ledger : <?php echo $invoice->debit_ledger." (".$invoice->debit_ledger_category.")"; ?>
                                    <br>Buyer/CR : <?php echo $invoice->credit_ledger." (".$invoice->credit_ledger_category.")"; ?>
                                 
                            </div>
                            <!-- /.col -->
                            <div class="col-md-2 col-sm-2 col-xs-2 invoice-col text-left custom_col_20">
                               
                                <br>
                                <b><?php echo $this->lang->line('invoice'); ?> #<?php echo $invoice->invoice_no;  ?></b>&nbsp;&nbsp;<b><br>
                                <b> <?php echo $this->lang->line('status'); ?>:</b> <span class="btn-success"><?php echo $issue_item['issue_type']  == 1 || $issue_item['is_returned'] ==1 ? "Returned" :  "Sell" ;  ?></span>
                                <br><b><?php echo $this->lang->line('date'); ?>:</b> <?php echo date("d-m-Y",strtotime($issue_item['issue_date'])) ; ?>                   
                                <br><b><?php echo $this->lang->line('transaction_id'); ?> :</b>                                <a href='<?php echo site_url('transactions/view/'.$issue_item['transaction_id']); ?>'><?php echo $issue_item['transaction_no']; ?></a>
   
                                <?php if($invoice->cheque_no) { ?><br><b>Cheque No : </b> <?php echo $invoice->cheque_no; ?> 
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
                        <div class="col-xs-12 table">
                            <table class="table table-striped">
                                <thead>
                                <?php $colspan =  5?>

                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('item'); ?></th>
                                        <th><?php echo $this->lang->line('sell_price') ?></th>
                                        <th><?php echo $this->lang->line('quantity') ?></th>
                                        <th style="text-align:right;"><?php echo $this->lang->line('total'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>   
									<?php if(isset($items)){ 
									$subtotal=0;
                                    $grand_total =0;
									$discount=0;
                                    $iCount= 1;
										foreach($items as $obj){ 
                                           
                                            $total =  $obj['quantity'] * $obj['issue_price'];
                                            $subtotal = $subtotal + $total
                                            ?>
										 <tr>
                                         <td  style="width:5%"><?php echo $iCount; ?></td>
                                         <td  style="width:25%"><?php echo $obj['item_category']; ?></td>
                                        <td  style="width:30%"><?php echo $obj['item_name']; ?></td>
                                        <td  style="width:10%"><?php echo $school->currency_symbol; ?><?php echo $obj['issue_price']; ?></td>
                                        <td  style="width:5%"><?php echo  $obj['quantity']?></td>
                                        <td style="text-align:right;"><?php echo $school->currency_symbol; ?><?php echo $total; ?></td>
                                    </tr> 
										<?php 
										$subtotal += $obj->net_amount;
                                        $total = $subtotal+ $discount;
                                        $iCount++;
										$index++; } 
									} ?>
                                </tbody>
								<tfoot>
                               
                                     <tr>
										<th >Remark</th>
                                        <td colspan=<?php echo  $colspan; ?> ><?php echo $obj['note']; ?></td>
									</tr>
									<tr>
										<th colspan=<?php echo  $colspan; ?>>Subtotal</th>
										<th style="text-align:right;"><?php echo numberToCurrency($subtotal); ?></th>
									</tr>
									<tr>
										<th colspan=<?php echo  $colspan; ?>>Discount</th>
										<th style="text-align:right;"><?php echo numberToCurrency($invoice->discount); ?></th>
									</tr>
                                    <tr>
										<th colspan=<?php echo  $colspan; ?>>Charges</th>
										<th style="text-align:right;"><?php echo numberToCurrency($invoice->charges); ?></th>
									</tr>
									<tr>
										<th colspan=<?php echo  $colspan; ?>>Total</th>
										<th style="text-align:right;"><?php echo numberToCurrency($invoice->grand_total); ?></th>
									</tr>
                                  

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
                        <!-- /.col --> <b> &nbsp;Signature of Cashier ________________ </b>
                     
                    </div>
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                   
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" id="printBtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                           
                        </div>
                    </div>
                </section>
            </div>
            <div class="clearfix"></div>
            <hr>
           

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

  


}
            </style>`);

</script>
