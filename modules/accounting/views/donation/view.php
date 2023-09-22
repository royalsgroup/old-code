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
                <h3 class="head-title"><i class="fa fa-calculator"></i><small> <?php echo $this->lang->line('manage_donation'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link no-print">
                <span><?php echo $this->lang->line('quick_link'); ?>:</span>
            | <a href="<?php echo site_url('accounting/donation/add'); ?>"><?php echo $this->lang->line('donation'); ?> <?php echo $this->lang->line('collection'); ?></a>
            | <a href="<?php echo site_url('accounting/donation/index'); ?>"><?php echo $this->lang->line('manage_donation'); ?></a>     
</div> 
            
            <div class="x_content" style="min-height:50%">
                <section class="content invoice ">
                   <div class="col-md-12 col-sm-12">
                         <!-- title row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header">
                                <h4><?php echo "Donor's Reciept" ?></h4>
                            </div>
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
                                    <tr>
                                        <td  style="width:33%">
                                            <div style="display:inline-block;" class="">
                                                <?php if($school->logo){ ?>
                                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="50" /> 
                                                <?php }else if($school->frontend_logo){ ?>
                                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="50"  /> 
                                                <?php }else{ ?>                                                        
                                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" width="50"   />
                                                <?php } ?>
                                            </div> 
                                            <div style="display:inline-block;">
                                                <br>Society Name : <?php echo $school->society_name; ?>
                                                <br>Society PAN No. : <?php echo $school->society_pan_no; ?>
                                                <br>80G Reg. No : <?php echo $school->school_80g_registration_no; ?>
                                            </div>
                                        </td>
                                        <td  style="width:33%">
                                            <address>
                                                <?php echo $school->school_name; ?>
                                                <br><?php echo $school->address; ?>

                                                <br><?php echo $this->lang->line('phone'); ?>: <?php echo $school->phone; ?>
                                                <br><?php echo $this->lang->line('email'); ?>: <?php echo $school->email; ?>
                                                
                                            </address>
                                               
                                        </td>
                                        <td  style="width:33%">
                                            <b>Receipt No : <?php echo $reciept->reciept_no;  ?></b>&nbsp;&nbsp;<b>
                                            <br><?php echo $this->lang->line('date'); ?>:</b> <?php echo strtotime($reciept->date) ?  date($this->global_setting->date_format, strtotime($reciept->date)) :$reciept->date  ; ?>                   
                                            <br><b><?php echo $this->lang->line('transaction_no'); ?> :</b> <a href='<?php echo site_url('transactions/view/'.$reciept->account_transaction_id); ?>'><?php echo $reciept->transaction_no;  ?>  </a>
                                            
                                            <br><b><?php echo $this->lang->line('payment'); ?>  Mode  :</b> <?php echo $reciept->payment_method; ?> 
                                            <br><b>Account Credit Ledger :</b> <?php echo $reciept->credit_ledger; ?>
                                            <br><b>Account Debit Ledger :</b> <?php echo $reciept->debit_ledger;  /*?> 
                                            

                                            <b><?php echo $this->lang->line('invoice');  ?> <?php echo $this->lang->line('number'); ?> :</b> <?php echo $invoice->transaction_no;  */?>        
                                            <?php if($reciept->cheque_no) { ?><br><b>Cheque No : </b> <?php echo $reciept->cheque_no; ?> 
                                                <br><b>Bank Name : </b> <?php echo $reciept->bank_name; ?>
                                                <?}?>        
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td  style="width:33%">
                                            <strong><?php echo $this->lang->line('donor') ?>:</strong>
                                            <address>
                                                <?php echo $reciept->donor_name?>
                                                <br><b><?php echo $this->lang->line('father')."/".$this->lang->line('husband'); ?></b> <?php echo $this->lang->line('name'); ?> : <?php echo $reciept->father_name; ?>
                                                <br><b><?php echo $this->lang->line('phone'); ?></b>: <?php echo $reciept->donor_phone; ?>
                                                <?php echo $reciept->donor_address ? "<br>".$reciept->donor_address  : ""; ?>
                                            </address>
                                        </td>
                                        <td  style="width:33%">
                                            <strong><?php echo $this->lang->line('adhar_no') ?>:</strong>
                                            <?php echo $reciept->adhar_no; ?>
                                        </td>
                                        <td  style="width:33%">
                                            <strong><?php echo $this->lang->line('pan')." ".$this->lang->line('number') ?>:</strong>
                                             <?php echo $reciept->donor_pan; ?>
                                        </td>
                                    </tr> 
                                </thead>
                                <tbody>   
                                    <tr>
                                        <td  style="width:33%;"><b><?php echo $this->lang->line('amount'); ?></b></td>
                                        <td  style="width:10%;" colspan="2"><?php echo $school->currency_symbol; ?> <?php echo $reciept->amount; ?></td>
                                    </tr> 
                                    <tr>
                                        <td  style="width:33%;padding-top:20px;">
                                            <b>Remark : </b> <?php echo $reciept->remark;  ?>
                                        </td>
                                        <td  style="width:33%;padding-top:20px;">
                                        <b> &nbsp;&nbsp;Signature of Reciever ________________ </b>
                                        </td>
                                        <td  style="width:33%;padding-top:20px;">
                                        <b> &nbsp;&nbsp;Signature of Cashier ________________ </b>
                                        </td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->

                    </div>
                    <!-- this row will not appear when printing -->
                    
                </section>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="x_content" style="min-height:50%">
                <section class="content invoice ">
                   <div class="col-md-12 col-sm-12">
                         <!-- title row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 invoice-header">
                                <h4><?php echo "School's Reciept" ?></h4>
                            </div>
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
                                    <tr>
                                        <td  style="width:33%">
                                            <div style="display:inline-block;" class="">
                                                <?php if($school->logo){ ?>
                                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="50" /> 
                                                <?php }else if($school->frontend_logo){ ?>
                                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="50"  /> 
                                                <?php }else{ ?>                                                        
                                                    <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" width="50"   />
                                                <?php } ?>
                                            </div> 
                                            <div style="display:inline-block;">
                                                <br>Society Name : <?php echo $school->society_name; ?>
                                                <br>Society PAN No. : <?php echo $school->society_pan_no; ?>
                                                <br>80G Reg. No : <?php echo $school->school_80g_registration_no; ?>
                                            </div>
                                        </td>
                                        <td  style="width:33%">
                                            <address>
                                                <?php echo $school->school_name; ?>
                                                <br><?php echo $school->address; ?>

                                                <br><?php echo $this->lang->line('phone'); ?>: <?php echo $school->phone; ?>
                                                <br><?php echo $this->lang->line('email'); ?>: <?php echo $school->email; ?>
                                                
                                            </address>
                                               
                                        </td>
                                        <td  style="width:33%">
                                            <b> Receipt No : <?php echo $reciept->reciept_no;  ?></b>&nbsp;&nbsp;<b>
                                            <br><?php echo $this->lang->line('date'); ?>:</b> <?php echo strtotime($reciept->date) ?  date($this->global_setting->date_format, strtotime($reciept->date)) :$reciept->date  ; ?>                   
                                            <br><b><?php echo $this->lang->line('transaction_no'); ?> :</b> <a href='<?php echo site_url('transactions/view/'.$reciept->account_transaction_id); ?>'><?php echo $reciept->transaction_no;  ?>  </a>
                                            
                                            <br><b><?php echo $this->lang->line('payment'); ?>  Mode  :</b> <?php echo $reciept->payment_method; ?> 
                                            <br><b>Account Credit Ledger :</b> <?php echo $reciept->credit_ledger; ?>
                                            <br><b>Account Debit Ledger :</b> <?php echo $reciept->debit_ledger;  /*?> 
                                            

                                            <b><?php echo $this->lang->line('invoice');  ?> <?php echo $this->lang->line('number'); ?> :</b> <?php echo $invoice->transaction_no;  */?>        
                                            <?php if($reciept->cheque_no) { ?><br><b>Cheque No : </b> <?php echo $reciept->cheque_no; ?> 
                                                <br><b>Bank Name : </b> <?php echo $reciept->bank_name; ?>
                                                <?}?>        
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td  style="width:33%">
                                            <strong><?php echo $this->lang->line('donor') ?>:</strong>
                                            <address>
                                                <?php echo $reciept->donor_name?>
                                                <br><b><?php echo $this->lang->line('father')."/".$this->lang->line('husband'); ?></b> <?php echo $this->lang->line('name'); ?> : <?php echo $reciept->father_name; ?>
                                                <br><b><?php echo $this->lang->line('phone'); ?></b>: <?php echo $reciept->donor_phone; ?>
                                                <?php echo $reciept->donor_address ? "<br>".$reciept->donor_address  : ""; ?>
                                            </address>
                                        </td>
                                        <td  style="width:33%">
                                            <strong><?php echo $this->lang->line('adhar_no') ?>:</strong>
                                            <?php echo $reciept->adhar_no; ?>
                                        </td>
                                        <td  style="width:33%">
                                            <strong><?php echo $this->lang->line('pan')." ".$this->lang->line('number') ?>:</strong>
                                             <?php echo $reciept->donor_pan; ?>
                                        </td>
                                    </tr> 
                                </thead>
                                <tbody>   
                                    <tr>
                                        <td  style="width:33%;padding-bottom:5px;"><b><?php echo $this->lang->line('amount'); ?></b></td>
                                        <td  style="width:10%;padding-bottom:5px;" colspan="2"><?php echo $school->currency_symbol; ?> <?php echo $reciept->amount; ?></td>
                                    </tr> 
                                    <tr>
                                        <td  style="width:33%;padding-top:20px;">
                                            <b>Remark : </b> <?php echo $reciept->remark;  ?>
                                        </td>
                                        <td  style="width:33%;padding-top:20px;">
                                        <b> &nbsp;&nbsp;Signature of Reciever ________________ </b>
                                        </td>
                                        <td  style="width:33%;padding-top:20px;">
                                        <b> &nbsp;&nbsp;Signature of Cashier ________________ </b>
                                        </td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->

                    </div>
                    <footer>
                        <span class="footer_text_custom">
                    Generated by : <?php 
                    $prin_uname = $this->db->select('username')->from('users')->where('id', $reciept->created_by)->get()->row();
                    echo $prin_uname->username ;
                    //echo $this->session->userdata('username'); ?>  
                    Date  :  <?php echo $reciept->created_at; //echo   date('Y-m-d'); ?></span>
                    </footer>
                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" id="printBtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
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

  


}
            </style>`);

</script>
