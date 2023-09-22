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
	  table{
		fofnt-size:10px;
	}
 }
</style>
 <?php $voucher_category = getVoucherCategory();?>
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
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('income_statement'); ?></small></h3>
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
                        <?php if(has_permission(VIEW, 'accounting', 'incomestatement')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_incomestatement"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('income_statement'); ?></a> </li>
                       <?php } ?>
                                           
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_incomestatement" >
                            <div class="x_content">
							<div class="row no-print">
    <div class="col-md-12 col-sm-12 col-xs-12">		
			<form method='post' action=''>
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
												$category =  $_POST['category'];
                                                echo "selected = selected";
                                            }
											else  if (isset($school_info->category) &&  $school_info->category == $key) 
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
			<?php if($financial_year->start_year >= '2021') { ?>
				<div class='col-md-3 col-sm-3 col-xs-12'>
			<input type='text' name='filter_start_date' class='form-control' id='filter_start_date' value='<?php echo $filter_start_date;?>' autocomplete=off placeholder='Start Date'>
			</div>
			<div class='col-md-3 col-sm-3 col-xs-12'>
			<input type='text' name='filter_end_date' class='form-control' id='filter_end_date' value='<?php echo $filter_end_date;?>' autocomplete=off placeholder='End Date'>
			</div>
			<?php } ?>
				<div class='col-md-3 col-sm-3 col-xs-12'>
			<input type='submit' name='submit' value='Filter' class='btn btn-default' />
			</div>
			</form>		
		</div>
		</div>	
							<?php if(isset($expenses) && isset($incomes)){  ?>					
		<div class="row no-print">
                <div class="col-xs-12 text-right">
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
			<div class="row print-only">
			<div style=""><span style=""><img width="65px" src="<?php print $schoollogo; ?>" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;font-weight:bold;"><?php print $school_info->school_name; ?></span></div>
			<div style="font-size:14px;font-weight:bold;">Income Statement
				<?php if($filter_start_date == '' && $filter_end_date == ''){
					print " as on till ".date('d-M-Y');
					 } else {
					print "  from ".date('d-M-Y',strtotime($filter_start_date))." to ".date('d-M-Y',strtotime($filter_end_date));
					 } ?>
					 	<?php if( isset($category) && $category)
									{
										echo $category;
									}
									?>
				</div>				
			</div>
		<div id="printContent">
							<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
	<table id="table2" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
		<thead>
			<?php if( isset($category) && $category)
									{?>
									<tr>                                       
                                        <th colspan="2" style="text-align:center"><?php  echo $category ?></th>
                                    </tr>
									<?php } ?>
			<tr>
				<th style="width:50%;">Expenses</th>
				<th style="width:50%;">Income</th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td style="width:50%;padding:0;">		
		 <table id="datatable-responsive" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>									
                                    <tr>                                       
                                        <th><?php echo $this->lang->line('accounts'); ?></th>
										<th style='text-align:right;width:25%'><?php echo $this->lang->line('balance'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>  
								<?php $index=1; foreach($expenses as $l){ if($l['group_total'] == 0) continue; ?>
									<tr> 
										<td>
											<a class='groupHead' data-toggle="collapse" href=".group<?php print $index; ?>" aria-expanded="true" aria-controls="group<?php print $index; ?>"  style="width:90%; float:left;"><?php echo $l['account_group_name']; ?><span style="float:right;"><i class="fa fa-chevron-up" style='color:red;'></i></span></a>											
										</td>
										<td class='account_amt' style="width:25%;"><?php 
											print number_format($l['group_total'],2);
										?></td>										
									</tr>
										<!-- ledgers -->
										<?php foreach($l['ledgers'] as $ledger){ 
										if($ledger->effective_balance ==0){ $trCls="zeroamtLedger";} else{$trCls='';}
										?>
										<tr class='group<?php print $index;?> <?php print $trCls; ?> collapse in' aria-expanded="true">
										<td><a href="javascript:void(0);"><?php echo $ledger->name; ?></a></td>
										<td align='right' style="width:25%;"><?php 
										
										print number_format($ledger->effective_balance,2); 
										?></td>
										</tr>
										<?php }
										$index++;
									}
										?>
								</tbody>
								<?php if($expence_difference >0 ){ ?>
								<tfoot>
									<tr>
										<th>Net Surplus</th>
										<th style='text-align:right;'><?php print number_format($expence_difference,2);?></th>
									</tr>
								</tfoot>
								<?php  } else{ ?>
							<!--	<tfoot>
									<tr>
										<th>Net Surplus</th>
										<th style='text-align:right;'><?php print number_format($incomes['group_total']-$expenses['group_total'],2);?></th>
									</tr>
								</tfoot>-->
								<?php }?>
							</table>
		</td>
		<td style="width:50%;padding:0;">
		<table id="datatable-responsive" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>									
                                    <tr>                                       
                                        <th><?php echo $this->lang->line('accounts'); ?></th>
										<th style='text-align:right;width:25%;'><?php echo $this->lang->line('balance'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>   									
									<?php $index=1; foreach($incomes as $l){ if($l['group_total'] == 0) continue; ?>
									<tr> 
										<td>
											<a class='groupHead' data-toggle="collapse" href=".group<?php print $index; ?>" aria-expanded="true" aria-controls="group<?php print $index; ?>"  style="width:90%; float:left;"><?php echo $l['account_group_name']; ?><span style="float:right;"><i class="fa fa-chevron-up" style='color:red;'></i></span></a>											
										</td>
										<td class='account_amt'style='width:25%;'><?php 
											print number_format($l['group_total'],2);
										?></td>										
									</tr>
										<!-- ledgers -->
										<?php foreach($l['ledgers'] as $ledger){
											if($ledger->effective_balance ==0){ $trCls="zeroamtLedger";} else{$trCls='';}
											?>
										<tr class='group<?php print $index;?> <?php print $trCls; ?> collapse in' aria-expanded="true">
										<td><a href="javascript:void(0);"><?php echo $ledger->name; ?></a></td>
										<td style='text-align:right;'><?php print 										
										number_format($ledger->effective_balance,2); ?></td>
										</tr>
										<?php }
										$index++;
									}
										?>
								</tbody>
								<?php if($income_difference >0 ){ ?>
								<tfoot>
									<tr>
										<th>Net Deficit</th>
										<th style='text-align:right;width:25%;'><?php print number_format($income_difference,2);?></th>
									</tr>
								</tfoot>
								<?php  } ?>	
							</table>
	</td>
		</tr>
		</tbody>
		<tfoot>
			<th>
				Total
				<strong style="float:right;"><?php print number_format($final_amount,2); ?></strong>
			</th>
			<th>
				Total
				<strong style="float:right;"><?php print number_format($final_amount,2); ?></strong>
			</th>
		</tfoot>
		</table>
	</div>
	</div>
	<div class='row print-only'>
	<div>Generated by : <?php echo $this->session->userdata('username'); ?></div><div>Date : <?php echo date('d/m/Y'); ?></div>
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
		 $(document).ready(function() {
			  var table =$('#table2').DataTable( {
              dom: 'Bfrtip',
			  bAutoWidth: false,
			  "info": false,
			  iDisplayLength: -1,
				paging: false,								
              buttons: [ 			  							
				{
				text: 'Hide All Ledgers',
					action: function ( e, dt, node, config ) {
						$(".groupHead").trigger("click");
					}
			  },
				{
				text: 'Hide Empty Ledgers',
					action: function ( e, dt, node, config ) {
						if($(".zeroamtLedger").is(":hidden")){
							$(".zeroamtLedger").show();
						}
						else{
							$(".zeroamtLedger").hide();
						}
					}
			  }
					
              ],			  
              searching: false, 
			  ordering: false,			  
              responsive: true
          });
		   });  		
	   function get_trialbalance_by_school(url){          
			if(url){
				window.location.href = url; 
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
		$('#filter_start_date').datepicker({
			startDate: '<?php print $f_start_date; ?>',
				endDate:'<?php print $f_end_date; ?>',
		});
		$('#filter_end_date').datepicker({
			startDate: '<?php print $f_start_date; ?>',
				endDate:'<?php print $f_end_date; ?>',
		});
</script>