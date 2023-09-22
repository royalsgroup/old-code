<?php //echo ($invoice->net_amount).$paid_amount;exit; ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-calculator"></i><small> <?php echo $this->lang->line('manage_invoice'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
           <div class="x_content quick-link no-print">
                <span><?php echo $this->lang->line('quick_link'); ?>:</span>
               <?php if(has_permission(VIEW, 'accounting', 'discount')){ ?>
                    <a href="<?php echo site_url('accounting/discount/index'); ?>"><?php echo $this->lang->line('discount'); ?></a>                  
                <?php } ?> 
              
               <?php if(has_permission(VIEW, 'accounting', 'feetype')){ ?>
                  | <a href="<?php echo site_url('accounting/feetype/index'); ?>"><?php echo $this->lang->line('fee_type'); ?></a>                  
                <?php } ?> 
                
                <?php if(has_permission(VIEW, 'accounting', 'invoice')){ ?>
                   
                   <?php if($this->session->userdata('role_id') == STUDENT || $this->session->userdata('role_id') == GUARDIAN){ ?>
                        | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>                    
                   <?php }else{ ?>
                        | <a href="<?php echo site_url('accounting/invoice/add'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?></a>
                        | <a href="<?php echo site_url('accounting/invoice/index'); ?>"><?php echo $this->lang->line('manage_invoice'); ?></a>
                        | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>                    
                    <?php } ?> 
                <?php } ?> 
                  
                <?php if(has_permission(VIEW, 'accounting', 'duefeeemail')){ ?>
                   | <a href="<?php echo site_url('accounting/duefeeemail/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('email'); ?></a>                  
                <?php } ?>
                 <?php if(has_permission(VIEW, 'accounting', 'duefeesms')){ ?>
                   | <a href="<?php echo site_url('accounting/duefeesms/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('sms'); ?></a>                  
                <?php } ?>         
               
            </div>
            
            <div class="x_content">
                <section class="content invoice ">
                   <div class="col-md-12 col-sm-12">
                         <!-- title row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header">
                                <h4><?php echo "Parent's Reciept" ?></h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header text-center">
                                 <?php if($school->logo){ ?>
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="50" /> 
                                 <?php }else if($school->frontend_logo){ ?>
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="50"  /> 
                                 <?php }else{ ?>                                                        
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" width="50"   />
                                 <?php } ?> 
                            </div>
                        </div>
                         
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left">
                                <strong><?php echo $this->lang->line('school'); ?>:</strong>
                                <address>
                                    <?php echo $school->school_name; ?>
                                    <br><?php echo $school->address; ?>
                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $school->phone; ?>
                                    <br><?php echo $this->lang->line('email'); ?>: <?php echo $school->email; ?>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left">
                                <strong><?php echo $this->lang->line('student'); ?>:</strong>
                                <address>
                                    <?php echo $invoice->name; ?>
                                    <br><?php echo $invoice->present_address; ?>
                                    <br><?php echo $this->lang->line('class'); ?>: <?php echo $invoice->class_name; ?>
                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $invoice->phone; ?>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left">
                                <b><?php echo $this->lang->line('invoice'); ?> #<?php echo $invoice->custom_invoice_id; ?></b>                                                     
                                <br>
                                <b><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('status'); ?>:</b> <span class="btn-success"><?php echo get_paid_status($invoice->paid_status); ?></span>
                                <br>
                                <b><?php echo $this->lang->line('date'); ?>:</b> <?php echo date($this->global_setting->date_format, strtotime($invoice->date)); ?>
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
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                                    <tr>
                                        <td  style="width:15%"><?php echo 1; ?></td>
                                        <td  style="width:25%"> <?php echo $invoice->head; ?></td>
                                        <td  style="width:35%"> <?php echo $invoice->note; ?></td>
                                        <td><?php echo $school->currency_symbol; ?><?php echo $invoice->net_amount; ?></td>
                                    </tr>                                          
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
     
                        <!-- /.col -->
                        <div class="col-xs-6">
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
                                            <td><?php echo $school->currency_symbol; ?><?php echo $invoice->net_amount; ?>
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
                        </div>
                        <!-- /.col -->
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
              <div class="x_content">
                <section class="content invoice">
                   <div class="col-md-12 col-sm-12">
                         <!-- title row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header">
                               <h4><?php echo "School's Reciept" ?></h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header text-center">
                                 <?php if($school->logo){ ?>
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="50" /> 
                                 <?php }else if($school->frontend_logo){ ?>
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="50"  /> 
                                 <?php }else{ ?>                                                        
                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" width="50"   />
                                 <?php } ?> 
                            </div>
                        </div>
                         
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left">
                                <strong><?php echo $this->lang->line('school'); ?>:</strong>
                                <address>
                                    <?php echo $school->school_name; ?>
                                    <br><?php echo $school->address; ?>
                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $school->phone; ?>
                                    <br><?php echo $this->lang->line('email'); ?>: <?php echo $school->email; ?>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left">
                                <strong><?php echo $this->lang->line('student'); ?>:</strong>
                                <address>
                                    <?php echo $invoice->name; ?>
                                    <br><?php echo $invoice->present_address; ?>
                                    <br><?php echo $this->lang->line('class'); ?>: <?php echo $invoice->class_name; ?>
                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $invoice->phone; ?>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left">
                                <b><?php echo $this->lang->line('invoice'); ?> #<?php echo $invoice->custom_invoice_id; ?></b>                                                     
                                <br>
                                <b><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('status'); ?>:</b> <span class="btn-success"><?php echo get_paid_status($invoice->paid_status); ?></span>
                                <br>
                                <b><?php echo $this->lang->line('date'); ?>:</b> <?php echo date($this->global_setting->date_format, strtotime($invoice->date)); ?>
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
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                                    <tr>
                                        <td  style="width:15%"><?php echo 1; ?></td>
                                        <td  style="width:25%"> <?php echo $invoice->head; ?></td>
                                        <td  style="width:35%"> <?php echo $invoice->note; ?></td>
                                        <td><?php echo $school->currency_symbol; ?><?php echo $invoice->net_amount; ?></td>
                                    </tr>                                          
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-6">
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
                                            <td><?php echo $school->currency_symbol; ?><?php echo $invoice->net_amount; ?></td>
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
                                            <td><?php echo $school->currency_symbol; ?><?php echo $paid_amount ? $paid_amount : 0.00; ?></td>
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
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
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



</script>
