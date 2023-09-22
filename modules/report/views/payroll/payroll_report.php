
<?php
?>
<style>
@media print { 
    .nav-sm .container.body .right_col {
        margin-left:0px;

    }
}
</style>
<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>

<div class="row">
   <div class="col-md-12 col-sm-12 col-xs-12">
       <div class="x_panel">
           <div class="x_title">
               <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> Payroll  <?php echo $this->lang->line('report'); ?></small></h3>                
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
           
                    echo form_open_multipart(site_url('report/payroll_report'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), '');
              
                ?>
               <div class="row">   
               
               
                  
               <?php $this->load->view('layout/school_list_filter'); ?>

                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="item form-group">
                        <div><?php echo $this->lang->line('role'); ?> <?php echo $this->lang->line('type'); ?> <span class="required"> *</span></div>
                        <select class="form-control col-md-7 col-xs-12" name="employee_type" id="payment_to" required="required" >
                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            <option value="all" <?php if (isset($employee_type) && $employee_type == 'all') {
                                                        echo 'selected="selected"';
                                                    } ?>>

                                <?php echo $this->lang->line('all'); ?></option>
                            <option value="employee" <?php if (isset($employee_type) && $employee_type == 'employee') {
                                                            echo 'selected="selected"';
                                                        } ?>>

                                <?php echo $this->lang->line('employee'); ?></option>
                            <option value="teacher" <?php if (isset($employee_type) && $employee_type == 'teacher') {
                                                        echo 'selected="selected"';
                                                    } ?>>

                                <?php echo $this->lang->line('teacher'); ?></option>
                        </select>
                        <div class="help-block"><?php echo form_error('type'); ?></div>
                    </div>
                </div>


                <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label for="salary_month"><?php echo $this->lang->line('month'); ?> <span class="required">*</span></label>
                            <input class="form-control col-md-7 col-xs-12 " name="salary_month" id="add_salary_month" value="<?php print (isset($salary_month)) ? $salary_month : ''; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                            <div class="help-block"><?php echo form_error('salary_month'); ?></div>
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

           <div class="x_content">
               <div class="" data-example-id="togglable-tabs">
                   
                 
                   <ul  class="nav nav-tabs bordered no-print">
                       <li class=""><a href="#tab_tabular"   role="tab" data-toggle="tab"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('tabular'); ?> <?php echo $this->lang->line('report'); ?></a> </li>
                   </ul>
                   <br/>
                   
                   <div class="tab-content">
                       <div  class="tab-pane fade in active" id="tab_tabular" >
                           <div class="x_content" style="overflow-x:scroll">
                             
                                    <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0"  width="100%">
                                        <thead>
                                            <?php $rowspan = 'rowspan="2"' ?>

                                            <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="<?php echo (count($all_earns)+count($all_exps))+10 ?>" style="text-align : center">
                                                    Payroll Report |
                                                   <?php if(isset($school))  echo " ".$school->school_name." "  ?> | 
                                                   <?php  echo " Month : ".$salary_month  ?>
                                                </th>
                                            </tr>
                                            <?php }?>
                                            <tr>
                                                <th <?php echo $rowspan ?>>S.No</th>
                                                <th <?php echo $rowspan ?>>Employee/ Teacher Name</th>
                                                <th <?php echo $rowspan ?>>PF No:</th>
                                                <th <?php echo $rowspan ?>>ESI No:</th>
                                                <th <?php echo $rowspan ?>>Basic Salarey</th>
                                                <th <?php echo $rowspan ?>>Working Days</th>
                                                <th <?php echo $rowspan ?>>Calculated Basic Salary</th>
                                                <th colspan="<?php echo $_POST ? count($all_earns) : 0 ?>">Earnings</th>
                                                <th colspan="<?php echo $_POST ? count($all_exps) : 0 ?>">Expendeture</th>
                                                <th <?php echo $rowspan ?>>Total Earnings</th>
                                                <th <?php echo $rowspan ?>>Total Expenditure</th>
                                                <th <?php echo $rowspan ?>>Total </th>


                                            </tr>
                                            <tr>
                                            <?php foreach( $all_earns as $earning) {  ?>
                                                    <th ><?php echo $earning ?></th>
                                                <?php }?>
                                                <?php foreach( $all_exps as $earning) {  ?>
                                                    <th ><?php echo $earning ?></th>
                                                <?php }?>

                                            </tr>
                                           
                                        </thead>
                                        <tbody>
                                           
                                                <?php 
                                                 $iCount = 0;
                                                 $g_total_earn =  0;
                                                 $g_total_exp=  0;
                                                 $g_total_net =  0;
                                                foreach( $payment_users as $user) { 
                                                    $iCount++;
                                                    ?>
                                                     <tr>
                                                <td ><?php echo $iCount ?></td>
                                                <td ><?php echo $user->name ?></td>
                                                <td ><?php echo $user->pf_no ?></td>
                                                <td ><?php echo $user->esi ?></td>
                                                <td ><?php echo $user->basic_salary ?></td>
                                                <td ><?php echo $user->working_days ?></td>
                                                <td ><?php echo $user->calc_basic_salary ?></td>
                                                <?php foreach( $all_earns as $earning_id => $earning) {  
                                                    $earning_amount = isset($user->earnings[$earning_id]) ? $user->earnings[$earning_id] : 0;                      
                                                    ?>
                                                    <td ><?php echo $earning_amount; ?></td>
                                                <?php }?>
                                                <?php foreach( $all_exps as  $expenditure_id => $expenditure) {  
                                                      $expenditure_amount = isset($user->expenditure[$expenditure_id]) ? $user->expenditure[$expenditure_id] : 0;
                                                    ?>
                                                    <td ><?php echo $expenditure_amount ?></td>
                                                <?php }?>
                                                <td ><?php echo $user->tot_earn ?></td>
                                                <td ><?php echo $user->tot_exp ?></td>
                                                <td ><?php echo $user->net_salary ?></td>

                                                </tr>
                                                <?php 
                                            $g_total_earn =  $g_total_earn + $user->tot_earn;
                                            $g_total_exp =  $g_total_exp + $user->tot_exp;
                                            $g_total_net =  $g_total_net + $user->net_salary;
                                            }?>
                                         </tbody>
                                         <?php if($_POST && !empty($payment_users)) {?>
                                            <tfoot>
                                                <tr>
                                                <td colspan="<?php echo (7+count($all_earns)+count($all_exps)) ?>"> Grand Total </td>
                                               
                                                <td ><?php echo $g_total_earn ?></td>
                                                <td ><?php echo $g_total_exp?></td>
                                                <td ><?php echo $g_total_net?> </td>
                                            </tr>
                                            </tfoot>
                                            <?php }?>

                                    </table>
                           
                          
                           </div>
                       </div>
                    

                           <h4>Signature: __________ </h4>  
                   </div>
               </div>
           </div>
           
           
           
       </div>
   </div>
</div>
<link href="<?php echo VENDOR_URL; ?>editor/jquery-te-1.4.0.css" rel="stylesheet">
 <script type="text/javascript" src="<?php echo VENDOR_URL; ?>editor/jquery-te-1.4.0.min.js"></script>

<script type="text/javascript">
   
   $("#student").validate();  
   $("#add_salary_month").datepicker({
        format: "mm-yyyy",
        startView: "months",
        minViewMode: "months"
    });
    // $(document).ready(function() {
    // $('.datatable-responsive').DataTable( {
    //     dom: 'Bfrtip',
    //           buttons: [
    //               'excelHtml5',
    //               'csvHtml5'
    //           ],
    //           search: true,              
    //           responsive: true
    //       });
    //     })
   
</script>   

<script type="text/javascript">

function doit(type, fn, dl) {
	var elt = document.getElementById('datatable-keytable');
	var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
	return dl ?
		XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
		XLSX.writeFile(wb, fn || ('payroll_report.' + (type || 'xlsx')));
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
                soring: false
            });          
          });
</script>