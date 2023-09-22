<style>
table{
	table-layout: fixed;
}
table td{
	word-wrap: break-word;
}
@media print {
	table{
		fofnt-size:10px;
	}	
}
</style>
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
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('voucher'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
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
			<div id='pageCnt'>
			<div class="row print-only">				
				<div style=""><span style=""><img width="65px" src="<?php print $schoollogo; ?>" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;font-weight:bold;"><?php print $school_info->school_name; ?></span></div><div style="font-size:14px;font-weight:bold;"><?php print $voucher->name; ?> Entries</div>		
			</div>
			<div class='row'>
			<div class='col-md-12'>
			<table id="table2" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">                               
                                <tbody>                                     
                                        <tr>
											<th width="30%"><?php echo $this->lang->line('name'); ?></th>
											<td><?php echo $voucher->name." (".$voucher->category.")"; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('school'); ?></th>
											<td><?php echo $voucher->school_name; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('type'); ?></th>
											<td><?php echo $voucher->type_name; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('is_readonly'); ?>?</th>
											<td><?php if($voucher->is_readonly == 1) echo 'Yes'; else echo 'No'; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('budget'); ?></th>
											<td><?php echo  number_format($voucher->budget,2,'.','')." [".$voucher->budget_cr_dr."]"; ?></td>
                                        </tr>		
										
										<!-- <tr>
											<th width="30%">Used Budget</th>
											<td><?php echo number_format($voucher->total_amount,2,'.','') ?></td>
                                        </tr>	
										<tr>
											<th width="30%">Remaining Budget</th>
											<td><?php echo number_format(($voucher->budget-$voucher->total_amount),2,'.','') ?></td>
                                        </tr>									 -->
										<tr>
											<th width="30%"><?php echo $this->lang->line('financial_year'); ?></th>
											<td><?php echo $financial_year->session_year; ?></td>
                                        </tr>
                                      
                                </tbody>
                            </table>  
			</div>
			</div>
			<div class='row'>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<h5 class="column-title"><strong>Entries:</strong>
					<?php if(has_permission(ADD, 'accounting', 'accounttransactions')){ ?>
					<?php if($obj->is_readonly == 0 &&  (!$is_previous_year ||  ($is_previous_year == true && $obj->type_id == 1) )){ 

					$currentDate = date('Y-m-d');
													if (($currentDate >= $financial_year_start) && ($currentDate <= $financial_year_end)){ ?>
					
					<span class='hidePrint no-print' style='text-align:right;float:RIGHT;'><a class="btn btn-primary" style="text-align:right;" href="<?php echo site_url('transactions/create/'.$voucher->id); ?>">New Entry</a></span>
					<?php }}} ?>
					</h5>
				</div>
				<div class='col-md-12 col-sm-12 col-xs-12'>
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">                               
                                <thead> 
								 <tr>
                                        <th style='width:5%;'><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('transaction_no'); ?></th>
										<th><?php echo $this->lang->line('date'); ?></th>
										<th><?php echo $this->lang->line('head_ledger'); ?></th>
										<th><?php echo $this->lang->line('dr_cr'); ?></th>
										<th><?php echo $this->lang->line('total_amount'); ?></th>
                                        <th class='no-print'><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
									  </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($transactions) && !empty($transactions)){ ?>
                                        <?php $total_amount=0;
										foreach($transactions as $obj){ 
										if($obj->cancelled==0){
										$total_amount += $obj->total_amount; } ?>
                                        <tr<?php if($obj->cancelled != 0){ print " class='strikeRow'"; } else { print " class='dataRow'"; } ?>>
                                            <td style='width:5%;'><?php echo $count++; ?></td>
											<td><?php echo $obj->transaction_no; ?></td>
											<td><?php echo date('d-m-Y',strtotime($obj->date)); ?></td>
											<td><a href="<?php echo site_url('/accountledgers/view/'.$obj->ledger_id); ?>"><?php echo $obj->ledger_name; ?></a></td>
											<td><?php echo $obj->head_cr_dr; ?></td>
											<td class='amount' align='right'>
											<?php if($obj->cancelled != 0){
												print "<span class='hidden'>R-</span>".number_format($obj->total_amount,2);
											}
											else{
											print number_format($obj->total_amount,2); }
											?>
											</td>
											<td class='no-print'>
											<?php if(has_permission(VIEW, 'accounting', 'accounttransactions')){ ?>
                                                        <a href="<?php echo site_url('transactions/view/'.$obj->id); ?>"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                    <?php } ?>
													</td>
										</tr>
									<?php } } ?>
								</tbody>
								<tfoot>
								<tr>
									<th style='width:5%;'></th>
									<th></th>
									<th></th>
									<th></th>
									<th>Total Amount</th>
									<th class="totalAmt" align="right" style="text-align:right;"><?php print $total_amount; ?></th>
									<th class='no-print'></th>
								</tr>
								</tfoot>
								</table>
				</div>
			</div>
			<div class="row print-only">
<div>Generated by : <?php echo $this->session->userdata('username'); ?></div><div>Date : <?php echo date('d/m/Y'); ?></div>
</div>	
</div>			
            </div>
        </div>
    </div>
</div>
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<script src="<?php echo ASSET_URL; ?>js/moment.js"></script>


<script type="text/javascript">
<?php /* if(isset($school_info->frontend_logo) && $school_info->frontend_logo!= ''){ ?>
				var schoollogo= '<?php print UPLOAD_PATH."/logo/".$school_info->frontend_logo; ?>';
	<?PHP } else*/ if($this->global_setting->brand_logo){ ?> 
			var schoollogo=  '<?php echo UPLOAD_PATH.'logo/'.$this->global_setting->brand_logo; ?>';
	<?php } else {  ?>
	var schoollogo=  "<?php echo IMG_URL. '/sms-logo-50.png'; ?>";
	<?php } 	
		$text_bottom="";
	
	?> 
    $(document).ready(function() {		
		$('#datatable-responsive thead tr').clone(true).appendTo( '#datatable-responsive thead' );
		$('#datatable-responsive thead tr:eq(1)').addClass("no-print");
		$('#datatable-responsive thead tr:eq(1) th').each( function (i) {
			var title = $(this).text();		
				if(title == 'Action'){				
					$(this).html( '' );	
				}
				else if(title == 'Date'){				
					$(this).html( '<input type="text" placeholder="Start Date" class="datepicker" style="width:50%;"  id="min" /><input class="datepicker" type="text" placeholder="End date" style="width:50%;"  id="max" />' );	

				}
				else{
					$(this).html( '<input type="text" placeholder="Search" style="width:100%;"  class="column_search" />' );	
				}
		         
		} );

    /* Add event listeners to the two range filtering inputs */
   
		//$('#datatable-responsive thead').find('input[id^=datepicker]').datepicker();	
		$('#datatable-responsive thead').find('.datepicker').datepicker({
			startDate: '<?php print $f_start_date; ?>',
				endDate:'<?php print $f_end_date; ?>',
		});	
		jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {		
			return this.flatten().reduce( function ( a, b ) {						
				if ( typeof a === 'string' ) {
					a = a.replace(/[^\d.-]/g, '') * 1;
				}
				if ( typeof b === 'string' ) {
					b = b.replace(/[^\d.-]/g, '') * 1;
				}  
				return a + b; 
			}, 0 );
			
		} );
          var table =$('#datatable-responsive').DataTable( {
              dom: 'Brtip',
			  bAutoWidth: false,
			  destroy: true,
			  "info": false,
			  orderCellsTop: true,
				fixedHeader: true,
              iDisplayLength: 15,
			  /*drawCallback: function () {				  
				  var api = this.api();	
				  var total_amount=0;
				  var val=0;
				  $("#datatable-responsive .dataRow .amount").each(function(){
					  val= $(this).html();
					  
					  total_amount = total_amount + parseInt(val);
				  });
				  $("th.totalAmt" ).html(total_amount);
				  /*$("th.totalAmt" ).html(				  
					api.column( 5, {page:'current'} ).data().sum()
			  ); },*/							
			 footerCallback: function( tfoot, data, start, end, display ) {
				var api = this.api();
				var intVal = function (i) {					
					if(typeof i === 'string'){
						if(i.indexOf('<span class="hidden">R-</span>') >=0){
							return 0;
						}
						else{
							return i.replace(/[\$,]/g, '') * 1;
						}
					}
					else if(typeof i === 'number'){
						return i;
					}
					else {
						return 0;
					}
					/*return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :					
						typeof i === 'number' ?
						i : 0;*/
				};				
				totalDebit = api
					.column(5)
					.data()
					.reduce(function (a, b) {						
							return intVal(a) + intVal(b);						
					}, 0)

							pageTotalDebit = api
					.column(5, { page: 'current' })
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0);
					total_debit_amount = totalDebit;
				$(api.column(5).footer()).html(
						'<span class="page-total no-print">'+pageTotalDebit + ' / </span>'+ totalDebit+''
					)
				
					},
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength',
				  'colvis',
				/*  {
					  
					'extend': 'print',
					title: '<div style=""><span style=""><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;"><?php print $school_info->school_name; ?></span></div><div><h3><?php print $voucher->name; ?> Entries</h3></div>',	
					footer: true,					
					exportOptions: {
                     columns: 'thead th:not(.no-print)'
					},
					customize: function ( win ) {
						$(win.document.body).css( 'margin', '20px' );						 
					}
					
				}*/
              ],
			 /* "columnDefs": [
					{
						"targets": [ 8 ],
						"visible": false,
						"searchable": true
					},
					{
						"targets": [ 9 ],
						"visible": false,
						"searchable": true
					}
				],*/
              //searching: false, 
			  search:true,
			  ordering: true,			  
              responsive: true
          });
		  $('#min').change( function() { 
			  table.draw(); } );
    $('#max').change( function() { 
		console.log("max")
		table.draw(); } );
		  /* Custom filtering function which will filter data in column four between two values */
$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iMin = $('#min').val();
        var iMax =$('#max').val();
        var iVersion = aData[2] == "-" ? 0 : aData[2];
		var d1 = new Date();
	


		iMin = moment(iMin,'DD-MM-YYYY').format('MM-DD-YYYY');
				iMax = moment(iMax,'DD-MM-YYYY').format('MM-DD-YYYY');
				iVersion = moment(iVersion,'DD-MM-YYYY').format('MM-DD-YYYY');

		iVersion = new Date(iVersion)
		iMin = new Date(iMin)
		iMax = new Date(iMax)
		var isMinValid = isValidDate(iMin)
		var isMaxValid = isValidDate(iMax)
		console.log(iMax)
        if ( !isMinValid && !isMaxValid )
        {
            return true;
        }
        else if ( !isMinValid && iVersion <= iMax  )
        {
            return true;
        }
        else if ( iVersion >= iMin && !isMaxValid )
        {
            return true;
        }
        else if ( iVersion >= iMin && iVersion <= iMax )
        {
            return true;
        }
        return false;
    }
);

		   $( '#datatable-responsive thead .column_search'  ).bind( 'keyup change', function () {   
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    } );		
        });
function printDiv(divName) {
     var printContents = "<div><h2><?php print $accountledger->school_name; ?></h2></div><div><h3><?php print $accountledger->name; ?> Entries</h3></div>";
	 printContents += document.getElementById(divName).innerHTML;
	 printContents += "<style>#datatable-responsive thead tr:nth-child(2), .dataTables_paginate,.hidePrint{display: none;}</style>";
    var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;	  
}		
function isValidDate(date) {
  return date && Object.prototype.toString.call(date) === "[object Date]" && !isNaN(date);
}
</script>	