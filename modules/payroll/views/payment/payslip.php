
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
				<div class='printContent custom_print_content' id='pageCnt'>
					<section class="content invoice ">
                   <div class="col-md-12 col-sm-12">
                       
                         
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
                                <strong><?php echo "Employee"; ?>:</strong>
                                <address>
                                     <?php echo $this->lang->line('name'); ?> : <?php echo $employee->name; ?>
                                    <br>PF No : <?php echo $employee->pf_no; ?>
                                    <br>ESI No : <?php echo $employee->pf_no; ?>
                                    <br>Basic : <?php echo $payment->basic_salary; ?>
                                    <br>Working Days : <?php echo $payment->working_days; ?>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4 col-sm-4 col-xs-4 invoice-col text-left">
                                <b><?php echo $this->lang->line('invoice_number'); ?> : <?php echo $payment->invoice_no; ?></b><br>
                                <b><?php echo $this->lang->line('payment_month'); ?> : <?php echo $payment->salary_month; ?></b><br>
                                <b><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('status'); ?>:</b> <span class="btn-success"><?php echo $payment->payment_status; ?></span>
                                <br>
                                <b><?php echo $this->lang->line('date'); ?>:</b> <?php echo isset($payment->payment_date) &&  $payment->payment_date ? $payment->payment_date : $payment->created_at ; ?>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </section>
                <section class="content invoice">
                    <!-- Table row -->
                    <div class="row">
                        <div class="col col-xs-6 col-xs-6 ">
						<h4>Earnings</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th>Name</th>
                                       
                                        <th style="text-align:center;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
									<tr>
											<td  style="width:15%">1</td>
                                            <td  style="width:40%"> Basic Pay Paid</td>
                                            <td style="text-align:center;"><?php echo $payment->cal_basic_salary; ?></td>
                                    </tr>
									<?php
									$i=2;
									foreach($earnings as $earning){
										
									?>
                                    <tr>
                                        <td  style="width:15%" class="table_listing_print"><?php echo $i; ?></td>
                                        <td  style="width:40%" class="table_listing_print"> <?php echo $earning['cat_name']; ?></td>
                                       
                                        <td style="text-align:center;"><?php echo $school->currency_symbol; ?><?php echo $earning['amount']; ?></td>
                                    </tr>   
									<?php
									$i++;
									}
									?>
									<tr>
									<td></td>
							<th>Total Earnings</th>
							<td style="text-align:center;"><h4><?php print $total_earnings+ $payment->cal_basic_salary;?></h4></td>
							</tr>
							<tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
						<div class="col col-xs-6 col-xs-6">
						<h4>Expenditures</h4>
                            <table class="table table-striped ">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th>Name</th>
                                       
                                        <th style="text-align:center;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									$i=1;
									foreach($expenditures as $expenditure){
										
									?>
                                    <tr>
                                        <td  style="width:15%" class="table_listing_print"><?php echo $i; ?></td>
                                        <td  style="width:40%" class="table_listing_print"> <?php echo $expenditure['cat_name']; ?></td>
                                       
                                        <td style="text-align:center;"><?php echo $school->currency_symbol; ?><?php echo $expenditure['amount']; ?></td>
                                    </tr>   
									<?php
									$i++;
									}
									?>
									<tr>
									<td></td>
							<th>Total Expenditures</th>
							<td style="text-align:center;"><h4><?php print $total_expenditure;?></h4></td>
							</tr>
							
                                </tbody>
                            </table>
                        </div>
                    </div>
                   

                    <div class="row">
     
                        <!-- /.col -->
                        <div class="col-xs-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:50%">Actual Basic Pay:</th>
                                            <td><?php echo $payment->basic_salary; ?></td>
                                        </tr>
                                        <!--<tr>
                                            <th>Basic Pay Paid</th>
                                            <td><?php echo $payment->cal_basic_salary; ?></td>
                                        </tr>-->
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        
                                       <!-- <tr>
                                            <th>Attendance:</th>
                                          
                                             <td><?php echo $payment->working_days;?></td>
                                        </tr>-->
                                        <tr>
                                            <th>Net Salary:</th>
                                            <td><?php echo $payment->net_salary; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.row -->
                    <footer class="footer_text_custom">
                    Generated by : <?php echo $this->session->userdata('username'); ?>  Date  :  <?php echo  date('Y-m-d'); ?>
                    </footer>
                    <!-- this row will not appear when printing -->
                    
                </section>			
				</div>
			 </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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
  
      .table_listing_print
      {
          font-size:80%;
          }

    body {
        padding-top:5mm;
        padding-left:5mm;
        padding-right:5mm;
    }
}
            </style>`);


function printDiv(divName) {
     //var printContents = "<div><h2><?php print $accountledger->school_name; ?></h2></div><div><h3><?php print $accountledger->name; ?> Entries</h3></div>";
    
     
	 printContents = document.getElementById(divName).innerHTML;	 
    var originalContents = document.body.innerHTML;
    
     document.body.innerHTML  = printContents+'<hr>'+printContents;
     console.log(document.innerHTML )
    
     window.print();
    
     window.close();
     document.body.innerHTML = originalContents;	  

    return true;
}		
</script>