
<?php

?>
  <?php if(isset($school_id) && !empty($school_id)){ 
   }
   
   ?>
   
<style>
@media print { 
  
}
@media print { 
    
    html {
        margin-left:30px;
    }
}
</style>
<div class="row">
   <div class="col-md-12 col-sm-12 col-xs-12">
       <div class="x_panel">
           <div class="x_title">
               <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('report'); ?></small></h3>                
               <ul class="nav navbar-right panel_toolbox">
                   <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
               </ul>
               <div class="clearfix"></div>
           </div>
           <div class="x_content quick-link no-print">
           <?php $this->load->view('quick-link.php'); ?>  

           </div>            
            <div class="x_content filter-box no-print">
            <?php
           
                    echo form_open_multipart(site_url('report/fee_report'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), '');
              
                ?>
               <div class="row">   
                    <div class="col-md-3 col-sm-3 col-xs-12">

                       <div class="form-group item">
                            <div>Filter Type <span class="required"> *</span></div>

                          <select name="filter_type" class="form-control col-md-7 col-xs-12" id="report_type" required="required">
                          <option value="">-- Select Report Type --</option>
                          <option value="school"  <?php echo isset($filter_type) && $filter_type == "school" ? "selected='selected'" : "" ; ?>>School Wise</option>

                          </select>
                       </div>
                   </div>
                   <div class="col-md-3 col-sm-3 col-xs-12" id="district_col">

                       <div class="form-group item">
                       <div>District <span class="required"> *</span> </div>

                          <select name="district_id" class="form-control col-md-7 col-xs-12" id="district_select" required="required">
                                <option value="">-- Select District --</option>
                                <?php foreach($districts as $district) { ?>
                                    <option value="<?php echo  $district->id  ?>" <?php echo isset($district_id) && $district_id == $district->id ? "selected='selected'" : "" ; ?> ><?php echo  $district->name  ?></option>
                                <?php } ?>
                             
                          </select>
                       </div>
                   </div>
                   <div class="col-md-3 col-sm-3 col-xs-12" id="zone_col">

                       <div class="form-group item">
                       <div>Prant <span class="required"> *</span> </div>

                          <select name="zone_id" class="form-control col-md-7 col-xs-12" id="zone_id" required="required">
                                <option value="">-- Select Prant --</option>
                                <?php foreach($zones as $zone) { ?>
                                    <option value="<?php echo  $zone->id  ?>" <?php echo isset($zone_id) && $zone_id == $zone->id ? "selected='selected'" : "" ; ?> ><?php echo  $zone->name  ?></option>
                                <?php } ?>
                             
                          </select>
                       </div>
                   </div>
                   <?php $this->load->view('layout/school_list_filter'); ?>
                   <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group item">
                        <div>Academic Year <span class="required"> *</span></div>

                        <select name="academic_year_id_filter" class="form-control col-md-7 col-xs-12" id="academic_year_id" required="required">
                        <option value="">-- Select Academic Year --</option>
                        <?php foreach($academic_years as $academic_year) {
                            ?>
                                    <option value="<?php echo  $academic_year->id  ?>" <?php echo isset($academic_year_id) && $academic_year_id == $academic_year->id ? 'selected="selected"' : "" ; ?> ><?php echo  $academic_year->session_year  ?></option>
                                <?php } ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group item">
                        <div>Fee Type <span class="required"> *</span> </div>

                        <select name="income_head_id" class="form-control col-md-7 col-xs-12" id="income_head_id" required="required">
                                <option value="">-- Select Fee Type --</option>
                        </select>
                        </div>
                    </div>
                   <div class="col-md-3 col-sm-3 col-xs-12">
                       <div class="form-group"><br/>
                           <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                       </div>
                   </div>
               </div>


               <?php echo form_close(); ?>
           </div>

           <div class="x_content" id="main-content">
               <div class="" data-example-id="togglable-tabs">
                   
                   <h5> <?php echo "Date : ". date("d/m/Y") ?> 
                   
                   <ul  class="nav nav-tabs bordered no-print">
                       <li class=""><a href="#tab_tabular"   role="tab" data-toggle="tab"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('tabular'); ?> <?php echo $this->lang->line('report'); ?></a> </li>
                   </ul>
                   <br/>
                   
                   <div class="tab-content">
                       <div  class="tab-pane fade in active" id="tab_tabular" >
                           <div class="x_content" style="overflow-x:scroll">
                              
                                    <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0"  width="100%">
                                        <thead>
                                        <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="14" style="text-align : center">
                                                   <?php if(isset($school))  echo " ".$school->school_name." "  ?> | 
                                                   <?php  echo  $fee_type_name   ?> 
                                                </th>
                                            </tr>
                                            <?php }?>
                                            <tr>
                                                <th rowspan="3">S.No</th>
                                                <th rowspan="3">Class</th>
                                                <th rowspan="3">Section</th>
                                                <th rowspan="3">Total Students</th>
                                                <th rowspan="3">Annual Fees Amount</th>
                                                <th rowspan="3">Total Annual Fees Amount</th>
                                                <th colspan="4">Discount</th>
                                                <th rowspan="3">Net Fees Amount</th>
                                                <th rowspan="3">Recieved Amount</th>
                                                <th rowspan="2"  colspan="4">Overdue_amount</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">RTE Student</th>
                                                <th colspan="2">Other</th>
                                            </tr>
                                            <tr>
                                                <th > Student</th>
                                                <th >Amont</th>
                                                <th > Other</th>
                                                <th >Amont</th>
                                                <th > Student</th>
                                                <th >Amountt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $icount = 0;
                                            foreach($fee_data as $data) { 
                                                if ($data['total_students'] == 0) continue;
                                                $total_annual_fees = $data['fee_amount']*$data['total_students'];
                                                $data['fee_amount'] = $data['total_students'] == 0 ? 0 : $data['fee_amount'];
                                                $total_rte_annual_fees = $data['fee_amount']*$data['rte_students'];
                                                $total_net_amount =  $total_annual_fees -  $total_rte_annual_fees - $data['total_discount_amount'];
                                                $icount++;
                                                ?>
                                            <tr>
                                                <td><?php echo  $icount; ?></td>
                                                <td><?php echo  $data['class_name'] ?></td>
                                                <td><?php echo  $data['section_name'] ?></td>
                                                <td><?php echo  $data['total_students']; ?></td>
                                                <td><?php echo  $data['fee_amount']; ?></td>
                                                <td><?php echo  round($total_annual_fees,2) ?></td>
                                                <td ><?php echo  $data['rte_students']; ?></td>
                                                <td ><?php echo  round($total_rte_annual_fees,2)  ?></td>
                                                <td ><?php echo  $data['discount_students']; ?></td>
                                                <td ><?php echo  $data['total_discount_amount']; ?></td>
                                                <td><?php echo  round(  $total_net_amount,2) ?></td>
                                                <td><?php echo  $data['total_fee_amount'] ?></td>
                                                <td><?php echo  ($data['total_students']-($data['p_paid_students']+ $data['rte_students'])); ?></td>
                                                <td><?php echo  $total_net_amount - $data['total_fee_amount']; ?></td>
                                            </tr>
                                            <?php } ?>
                                       </tbody>
                                       <tfoot>
                                            <tr>
                                                <td colspan="3">Total</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo  round(  $total_net_amount,2) ?></td>
                                                <td><?php echo  $data['total_fee_amount']; ?></td>
                                                <td><?php echo  ($data['total_students']-$data['paid_students'])+$data['p_paid_students'] ?></td>
                                                <td><?php echo  $total_net_amount - $data['total_fee_amount']; ?></td>
                                            </tr>
                                       </tfoot>
                                    </table>
                           
                          
                           </div>
                       </div>
                    

                           <h4>Signature: __________ </h4>  
                   </div>
               </div>
           </div>
           
           <div class="row no-print">
               <div class="col-xs-12 text-right">
                   <button class="btn btn-default " onclick="printDiv()"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
               </div>
           </div>
           
       </div>
   </div>
</div>
<script type="text/javascript">

function doit(type, fn, dl) {
	var elt = document.getElementById('datatable-keytable');
	var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
	return dl ?
		XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
		XLSX.writeFile(wb, fn || ('tution_fee_report.' + (type || 'xlsx')));
}
$(document).ready(function() {
            
            $('.datatable-responsive').DataTable({
                dom: 'Bfrtip',
                "ordering": false,
                "searching": false,
                "paging" : false,
                buttons: [
                    {
                        text: 'Export',
                        action: function ( ) {
                            doit('xlsx')
                        }
                    }
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
        
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    total_students = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_annual_fee_amount = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_annual_fee = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_student_re = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_amount_re = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_other_discount = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_amount_discount = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_net = api
                    .column( 10 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    // Total over all pages
                    total_recieved = api
                        .column( 11 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                        total_due_student = api
                    .column( 12 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    total_due = api
                    .column( 13 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
        
                    // Total over this page
                   
        
                    // Update footer
                    $( api.column( 3 ).footer() ).html(
                        total_students 
                    );
                    $( api.column( 4 ).footer() ).html(
                        total_annual_fee_amount 
                    );
                    $( api.column( 5 ).footer() ).html(
                        total_annual_fee 
                    );
                    $( api.column( 6 ).footer() ).html(
                        total_student_re 
                    );
                    $( api.column( 7 ).footer() ).html(
                        total_amount_re 
                    );
                    $( api.column( 8 ).footer() ).html(
                        total_other_discount 
                    );
                    $( api.column( 9 ).footer() ).html(
                        total_amount_discount 
                    );
                   


                    $( api.column( 11 ).footer() ).html(
                        total_recieved 
                    );
                    $( api.column( 10 ).footer() ).html(
                        total_net 
                    );
                    $( api.column( 12 ).footer() ).html(
                        total_due_student 
                    );
                    $( api.column( 13 ).footer() ).html(
                        total_due 
                    );
                }
            });          
          });
</script>
<script type="text/javascript">
   
   $("#student").validate();  

   $("document").ready(function() {
        <?php if(isset($school_id) && !empty($school_id)){ ?>
           $(".fn_school_id").trigger('change');
           var academic_year_id = <?php echo isset($academic_year_id) && !empty($academic_year_id) ? $academic_year_id : 0; ?>;
           get_academic_year_by_school( <?php echo $school_id; ?>,academic_year_id)
        <?php } ?>
        <?php if(isset($academic_year_id) && !empty($academic_year_id)){ ?>
           $("#academic_year_id").trigger('change');
        <?php } ?>
   });
    
   
   function get_fee_type_by_year(school_id, academic_year_id, fee_type_id){       
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_fee_type_by_year'); ?>",
            data   : { school_id:school_id, academic_year_id :academic_year_id,fee_type_id : fee_type_id },               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
                console.log('fee_type')

                    $('#income_head_id').html(response); 
               }
            }
        });
   }  
   
   function get_academic_year_by_school(school_id, academic_year_id)
   {
       $.ajax({       
           type   : "POST",
           url    : "<?php echo site_url('ajax/get_academic_year_by_school'); ?>",
           data   : { school_id:school_id, academic_year_id :academic_year_id},               
           async  : false,
           success: function(response){                                                   
              if(response)
              {

                   $('#academic_year_id').html(response); 
                   var fee_type_id = <?php echo isset($income_head_id) && $income_head_id ?  $income_head_id : 0 ?>;
                    get_fee_type_by_year(school_id, academic_year_id, fee_type_id);
              }
           }
       });
  }

  // ak

    $(document).ready(function(){
            $('#district_col').hide();   
            $('#zone_col').hide();   
            $('#school_filter_col').hide();  
            $(".fn_school_id").change(function(){
                get_academic_year_by_school(this.value)
                console.log('school fercg')

            })
            $('#academic_year_id').change(function(){
                var school_id = $('#school_id').val();
                var income_head_id = $('#income_head_id').val();
                var fee_type_id = <?php echo isset($income_head_id) && $income_head_id ?  $income_head_id : 0 ?>;
                get_fee_type_by_year(school_id, this.value, fee_type_id)
                console.log('changed academic')
            })
        <?php
        if((isset($filter_type))){  ?>
            <?php if($filter_type == "district")
            { ?>
                $('#district_col').show();   

            <?php }
            else if($filter_type == "prant") {?>
                $('#zone_col').show();  
            <?php } 
            else if($filter_type == "school") {?>
               $('#school_filter_col').show();  
            <?php } }
            ?>
            
           
           // Checkbox click
           $('#report_type').on('change',function(){
            $('#district_col').hide();   
            $('#zone_col').hide();   
            $('#school_filter_col').hide();  
            if(this.value == "school")
            {
                $('#school_filter_col').show();  
            }
               if(this.value =="district")
               {
                    $('#district_col').show();   
               }
               else if(this.value =="prant")
               {
                $('#zone_col').show();  
               }
               
               
           })
           $(".hidecol").click(function(){

               var id = this.id;
               var splitid = id.split("_");
               var colno = splitid[1];
               var checked = true;
                
               // Checking Checkbox state
               if($(this).is(":checked")){
                   checked = true;
               }else{
                   checked = false;
               }
               setTimeout(function(){
                   if(checked){
                       $('#datatable-keytable td:nth-child('+colno+')').hide();
                       $('#datatable-keytable th:nth-child('+colno+')').hide();
                   } else{
                       $('#datatable-keytable td:nth-child('+colno+')').show();
                       $('#datatable-keytable th:nth-child('+colno+')').show();
                   }

               }, 1500);

           });
       });

        function printDiv() {
            $("#main-content").printThis({
                debug: false,               // show the iframe for debugging
                importCSS: true,            // import parent page css
                importStyle: true,          // import style tags
               
            });
        }
        
  // ak
   
</script>   

