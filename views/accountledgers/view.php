<style>

table td{
	word-wrap: break-word;
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
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $accountledger->name; ?></small></h3>
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
				<div><span><img width="65px" src="<?php print $schoollogo; ?>" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;font-size:25px;font-weight:bold;"><?php print $school_info->school_name; ?></span></div>
				 <?php if(isset($financial_year->session_year)){  ?>
				<div style="font-size:14px;font-weight:bold;"><?php print $accountledger->name; ?> Entries ( <?php print $financial_year->session_year; ?>)</div>		
				 <?php } else { ?>
				 <div style="font-size:14px;font-weight:bold;"><?php print $accountledger->name; ?> Entries</div>		
				 <?php } ?>
			</div>
				<div class='row'>
				<div class='col-md-12'>
				<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="table-layout: fixed;">                               
                                <tbody>                                     
                                        <tr>
											<th width="30%"><?php echo $this->lang->line('name'); ?></th>
											<td><?php echo $accountledger->name; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('group'); ?></th>
											<td><?php echo $accountledger->group_name; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('school'); ?></th>
											<td><?php echo $accountledger->school_name; ?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('opening_balance'); ?></th>
											<td><?php 
											echo abs($accountledger->opening_balance). " [".$accountledger->opening_cr_dr."]"; 
												
											?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('current_balance'); ?></th>
											<td><?php 
											echo abs($accountledger->effective_balance); 
											//if($accountledger->dr_cr =='DR'){
											if($accountledger->effective_balance < 0){
											print " [DR]";
											}
											else if($accountledger->effective_balance > 0){
											print " [CR]";	
											}
											else{
												
											}
											?></td>
                                        </tr>
										<tr>
											<th width="30%"><?php echo $this->lang->line('financial_year'); ?></th>
											<td><?php echo $financial_session_year; ?></td>
                                        </tr>
									<!--	<tr>
											<th width="30%"><?php echo $this->lang->line('available_budget'); ?></th>
											<td><?php 
											echo abs($accountledger->budget). " [".$accountledger->budget_cr_dr."]"; 
												
											?></td>
                                        </tr>-->
                                      
                                </tbody>
                            </table>  
			</div>
			</div>			
			<div class='row'>				
				<div class='col-md-12 col-sm-12 col-xs-12'>
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" cellspacing="0" style="max-width:100%" width="100%">                               
                                <thead> 
								 <tr>
                                        <th width="10% !important"><?php echo $this->lang->line('date'); ?></th>
										<th  width="20% !important" class="no-sort"><?php echo $this->lang->line('narration'); ?></th>
										<th width="10% !important" class="no-sort"><?php echo $this->lang->line('voucher'); ?></th>
                                        <th width="10% !important" class="no-sort"><?php echo $this->lang->line('transaction_no')."/".$this->lang->line('voucher')." No"; ?></th>
										<th width="3% !important" class="no-sort"><?php echo $this->lang->line('dr_cr'); ?></th>
										<th  width="10% !important" class="no-sort"><?php echo $this->lang->line('debit'); ?></th>
                                        <th width="10% !important" class="no-sort"><?php echo $this->lang->line('credit'); ?></th>
										<th width="10% !important" class="no-sort"><?php echo $this->lang->line('amount'); ?></th>
										<th width="5% !important" class="no-sort"><?php echo $this->lang->line('entry_reversed'); ?></th>
                                    </tr>									
									  </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($transactions) && !empty($transactions)){ ?>
                                        <?php $total_debit=0;
										$total_credit=0;
										foreach($transactions as $obj){  	
											if($obj->type == 1)
											{
																						
													if($obj->head_cr_dr == "DR"){ 
														$cr_dr = "DR";
														if($obj->cancelled==0) $total_debit += $obj->amount;
														
														 $obj->debit_amount =  $obj->amount;
													}
													else{			
														$cr_dr = "CR";									
														if($obj->cancelled==0) $total_credit += $obj->amount;
														$obj->credit_amount =  $obj->amount;
													}						
													
											}	
											else
											{
													if($obj->head_cr_dr == "CR"){ 
														$cr_dr = "DR";
														if($obj->cancelled==0) $total_debit += $obj->amount;
														$obj->debit_amount =  $obj->amount;
													}
													else{		
														$cr_dr = "CR";																			
														if($obj->cancelled==0) $total_credit += $obj->amount;
														$obj->credit_amount =  $obj->amount;
													}							
											}										
																				
										  ?>
                                        <tr <?php if($obj->cancelled != 0){ print " class='strikeRow'"; } else { print " class='dataRow'"; } ?>>
                                            <td data-sort="<?php echo strtotime($obj->date); ?>"><?php echo date('d-m-Y',strtotime($obj->date)); ?></td>
											<td>
												<?php print $obj->narration; ?>
												<?php print $obj->remark ? "<br><b>Remark :</b>". $obj->remark : ""; ?>
											</td>
											<td><?php print $vouchers[$obj->voucher_id]; ?></td>
											<td><a href='<?php echo site_url('transactions/view/'.$obj->id); ?>'><?php echo $obj->transaction_no; ?></a></td>
											<td>
											<?php echo $cr_dr; ?></td>
											<td class='debitAmount' align='right'>
											<?php 
											
												
											if(isset($obj->debit_amount)){ 
												if($obj->cancelled != 0){
												print "<span class='hidden'>R-</span>".($obj->debit_amount); } 
												else { print ($obj->debit_amount); }
												}
												?></td>
												<td class='creditAmount' align='right'>
												<?php if(isset($obj->credit_amount)){ 
												if($obj->cancelled != 0){
													print "<span class='hidden'>R-</span>".($obj->credit_amount);
												}
												else{
												print ($obj->credit_amount); }
												}
												?></td>
												<td class='amount'><?php if($obj->debit_amount>0){ print ($obj->debit_amount);} else{ print ($obj->credit_amount); } ?></td>
											<td><?php if($obj->cancelled != 0){ print "true";} else { print "false"; }?></td>
										</tr>
									<?php } } ?>
								</tbody>
								<tfoot>
								<tr>									
									<th></th>
									<th></th>									
									<th></th>
									<th></th>
									<th></th>									
									<th class='debitTotal' style='text-align:right;'><?php print $total_debit; ?></th>
									<th class='creditTotal' style='text-align:right;'><?php print $total_credit; ?></th>
									<th class='totalAmount' style='text-align:right;'></th>	
									<th></th>	
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
  <!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<script src="<?php echo ASSET_URL; ?>js/moment.js"></script>

<script type="text/javascript">   	
var total_debit_amount = 0;
var total_credit_amount = 0;
<?php /* if(isset($school_info->frontend_logo) && $school_info->frontend_logo!= ''){ ?>
				var schoollogo= '<?php print UPLOAD_PATH."/logo/".$school_info->frontend_logo; ?>';
	<?PHP } else*/ if($this->global_setting->brand_logo){ ?> 
			var schoollogo=  '<?php echo UPLOAD_PATH."logo/".$this->global_setting->brand_logo; ?>';
	<?php } else {  ?>
	var schoollogo=  '<?php echo IMG_URL. "/sms-logo-50.png"; ?>';
	<?php } 	
		$text_bottom="";
	
	?> 
    $(document).ready(function() {		
		$('#datatable-responsive thead tr').clone(true).appendTo( '#datatable-responsive thead' );
		$('#datatable-responsive thead tr:eq(1)').addClass("no-print");
		$('#datatable-responsive thead tr:eq(1) th').each( function (i) {
			var title = $(this).text();		
				if(title == 'Date'){				
					$(this).html( '<input type="text" placeholder="Start Date" class="datepicker" style="width:50%;"  id="min" /><input class="datepicker" type="text" placeholder="End Date" style="width:50%;"  id="max" />' );	

				}				
				else{
					$(this).html( '<input type="text" placeholder="Search" style="width:100%;"  class="column_search" />' );	
				}
		         
		} );
		$('#datatable-responsive thead').find('.datepicker').datepicker(
			{ 
				dateFormat: 'yy-mm-dd',
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
			  destroy: true,
			  "info": false,
			  orderCellsTop: true,
				fixedHeader: true,

              iDisplayLength: 15,
			//   drawCallback: function () {				  
			// 	  var api = this.api();	
			// 		var total_debit=0;
			// 		var total_credit=0;
			// 		var total_amount=0;
			// 	  var val=0;
			// 	  $("#datatable-responsive .dataRow .debitAmount").each(function(){
			// 		  val= $(this).html();					  
			// 		  total_debit = total_debit + parseInt(val);
			// 	  });
			// 	$("th.debitTotal" ).html(total_debit);
			// 	 $("#datatable-responsive .dataRow .creditAmount").each(function(){
			// 		  val= $(this).html();					  
			// 		  total_credit = total_credit + parseInt(val);
			// 	  });
			// 	$("th.creditTotal" ).html(total_credit);									
			//   },
			
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
					totalCredit = api
					.column(6)
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0)

							pageTotalCredit = api
					.column(6, { page: 'current' })
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0);
					total_credit_amount = totalCredit;
				$(api.column(6).footer()).html(
					'<span class="page-total no-print">'+pageTotalCredit + ' / </span>'+ totalCredit+''
						
					)
					},
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength',
				  'colvis',
				/* {
					'extend': 'print',
					title: '<div style=""><span style=""><img width="65px" src="'+schoollogo+'" style="max-width:25px;" /></span><span style="padding-top:40px;padding-left:20px;"><?php print $school_info->school_name; ?></span></div></h1>  <div ><div style="float:right"><b>Financial Year :</b> <?php echo $financial_session_year; ?><br> <b>Date :</b> 10/07/2021</div><div style="text-align:center"><h3>Cash Entries</h3></div></div><h1>',	
					footer: true,					
					exportOptions: {
                    columns: ':visible'
					},
					customize: function ( win ) {
						$(win.document.body).css( 'margin', '20px' );
						if($(win.document).find('table').length)
						{
							$(win.document).find('h1').css("font-size",'180%')
							$(win.document).find('table').css("font-size",'10px');							
							//$(win.document).find('table').find('tbody tr.strikeRow').css({"text-decoration": "line-through;"});
							/*$(win.document).find('tobdy').forEach(function(line, i){
								console.log(line[8].text());
							});*/
		/*					$(win.document).find('table').find('thead th').css({"width" : "60px","max-width": "60px;"})
							// $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('voucher'); ?>")').css({"width" : "60px","max-width": "60px;"})
							//  $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('transaction_no'); ?>")').css({"width" : "60px","max-width": "60px;"})
							$(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('narration'); ?>")').css({"width" : "30%","min-width": "30%"})
					
							var debitIndex  = $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('debit'); ?>")').index();
							var creditIndex = $(win.document).find('table').find('thead th:contains("<?php echo $this->lang->line('credit'); ?>")').index();
							var debitIndex  = debitIndex ? debitIndex+1 : debitIndex;
							var creditIndex  = creditIndex ? creditIndex+1 : creditIndex;
							$(win.document).find('table').after("Generated by : <?php echo $this->session->userdata('username'); ?>")
							$(win.document).find('table').find('tfoot th:nth-child('+debitIndex+')').html(''+total_debit_amount)
							$(win.document).find('table').find('tfoot th:nth-child('+creditIndex+')').html(''+total_credit_amount)
						}
					}
				}*/
              ],
			  "columnDefs": [
					{
						"targets": [ 0 ,3,4,5,6],
						"width": "80"
					},
					{
						"targets": [ 2],
						"width": "100"
					},
					{
						"targets": [ 7 ],
						"visible": false,
						"searchable": true
					},
					{
						"targets": [ 7 ],
						"visible": false,
						"searchable": true
					},
					{
						"targets": [ 7 ],
						"visible": false,
						"searchable": true
					},
					{
						"targets": [ 8 ],
						"visible": false,
						"searchable": true
					}
				],
              //searching: false, 
			  search:true,
			  "ordering": true,
    columnDefs: [{
      orderable: false,
      targets: "no-sort"
    }],
              responsive: true
          });
		  $('#min').change( function() { 
			  table.draw(); } 
			);
    $('#max').change( function() { 
		console.log("max")
		table.draw(); 
	} );
		  $.fn.dataTableExt.afnFiltering.push(
			function( oSettings, aData, iDataIndex ) {
				var iMin = $('#min').val();
				var iMax =$('#max').val();
				var iVersion = aData[0] == "-" ? 0 : aData[0];
				var d1 = new Date();
			
				iMin = moment(iMin,'DD-MM-YYYY').format('MM-DD-YYYY');
				iMax = moment(iMax,'DD-MM-YYYY').format('MM-DD-YYYY');
				iVersion = moment(iVersion,'DD-MM-YYYY').format('MM-DD-YYYY');


				iVersion = new Date(iVersion)
				iMin = new Date(iMin)
				iMax = new Date(iMax)
				var isMinValid = isValidDate(iMin)
				var isMaxValid = isValidDate(iMax)
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
		function isValidDate(date) {
			return date && Object.prototype.toString.call(date) === "[object Date]" && !isNaN(date);
		}
		
</script>
	