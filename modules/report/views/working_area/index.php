
<?php

?>
<style>
@media print { 
    .nav-sm .container.body .right_col {
        margin-left:0px;

    }
}
</style>
<div class="row">
   <div class="col-md-12 col-sm-12 col-xs-12">
       <div class="x_panel">
           <div class="x_title">
               <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> Working Area <?php echo $this->lang->line('report'); ?></small></h3>                
               <ul class="nav navbar-right panel_toolbox">
                   <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
               </ul>
               <div class="clearfix"></div>
           </div>
           <div class="x_content quick-link no-print">
           <!-- <?php if(has_permission(VIEW, 'report', 'report')){ ?>
                               <a href="<?php echo site_url('report/income'); ?>"><?php echo $this->lang->line('income'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/expenditure'); ?>"><?php echo $this->lang->line('expenditure'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/invoice'); ?>"><?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/duefee'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/feecollection'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/balance'); ?>"><?php echo $this->lang->line('accounting'); ?> <?php echo $this->lang->line('balance'); ?> <?php echo $this->lang->line('report'); ?></a> 
                               | <a href="<?php echo site_url('report/library'); ?>"><?php echo $this->lang->line('library'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/sattendance'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                               | <a href="<?php echo site_url('report/syattendance'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                               | <a href="<?php echo site_url('report/tattendance'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                               | <a href="<?php echo site_url('report/tyattendance'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                               | <a href="<?php echo site_url('report/eattendance'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                               | <a href="<?php echo site_url('report/eyattendance'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a>
                               | <a href="<?php echo site_url('report/student'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/sinvoice'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('report'); ?></a> 
                               | <a href="<?php echo site_url('report/sactivity'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('activity'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/working_area'); ?>">Working Area <?php echo $this->lang->line('report'); ?></a>

                               | <a href="<?php echo site_url('report/transaction'); ?>"><?php echo $this->lang->line('daily'); ?> <?php echo $this->lang->line('transaction'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/statement'); ?>"><?php echo $this->lang->line('daily'); ?> <?php echo $this->lang->line('statement'); ?> <?php echo $this->lang->line('report'); ?></a>
                               | <a href="<?php echo site_url('report/examresult'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('report'); ?></a>
                      
                   <?php } ?> -->
           </div>            
            <div class="x_content filter-box no-print"> 
               <?php echo form_open_multipart(site_url('report/working_area'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), ''); ?>
               <div class="row">   
                    <div class="col-md-3 col-sm-3 col-xs-12">

                       <div class="form-group item">
                       <div>Filter Type <span class="required"> *</span></div>

                          <select name="filter_type" class="form-control col-md-7 col-xs-12" id="report_type" required="required">
                          <option value="">-- Select Report Type -- <?php echo $this->session->userdata('district_id') ?></option>
                              <?php if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {?>
                                <option value="district" <?php echo isset($report_type) && $report_type == "district" ? "selected='selected'" : "" ; ?> >District Level</option>
                                <?php if( !$this->session->userdata('subzone_id')) {?>
                              <option value="prant"  <?php echo isset($report_type) && $report_type == "prant" ? "selected='selected'" : "" ; ?>>Prant Wise</option>
                              <?php if( !$this->session->userdata('zone_id')) {?>
                              <option value="kshetra"  <?php echo isset($report_type) && $report_type == "kshetra" ? "selected='selected'" : "" ; ?>>Kshetra Wise</option>
                              <?php }
                               }
                             }?>
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
                   
                   <h5> <?php echo "Date : ". date("d/m/Y") ?> 
                  <?php if(isset($school) && !empty($school)){ ?>
                                   <div class="x_content">             
                                       <div class="row">
                                           <div class="col-sm-3  col-xs-3">&nbsp;</div>
                                           <div class="col-12 layout-box">
                                               <div>
                                                   <?php if($school->logo){ ?>
                                                       <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" style="height: 50px; margin-right: 200px;" /> 
                                                   <?php }else if($school->frontend_logo){ ?>
                                                       <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" style="height: 50px; margin-right: 200px;" /> 
                                                   <?php }else{ ?>                                                        
                                                       <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" style="height: 50px; margin-right: 200px;" />
                                                   <?php } ?>
                                                   <h4><?php echo $school->school_name; ?></h4>
                                                   <p><?php echo $school->address; ?></p>
                                                   <h3 class="head-title ptint-title" style="width: 100%;"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('report'); ?></small></h3>                
                                                   <div class="clearfix">&nbsp;</div>
                                               </div>
                                           </div>
                                               <div class="col-sm-3  col-xs-3"></div>
                                       </div>            
                                   </div>
                   <?php } ?>    
                   <ul  class="nav nav-tabs bordered no-print">
                       <li class=""><a href="#tab_tabular"   role="tab" data-toggle="tab"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('tabular'); ?> <?php echo $this->lang->line('report'); ?></a> </li>
                   </ul>
                   <br/>
                   
                   <div class="tab-content">
                       <div  class="tab-pane fade in active" id="tab_tabular" >
                           <div class="x_content">
                               <?php if($report_type == "district"){?>
                                    <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                        <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="6" style="text-align : center">
                                                    Working Area Report |
                                                   <?php if(isset($report_heading))  echo " ".$report_heading  ?> 
                                                </th>
                                            </tr>
                                            <?php }?>
                                            <tr>
                                                <th >Sr</th>
                                                <th > Tehsil</th>
                                                <th > Working IN</th>
                                                <th >Sankul </th>
                                                <th >Working IN</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php 
                                            $iCount =0;
                                             $total_tehsil_count = 0;
                                             $total_sankul_count = 0;
                                             $working_tehsil_count = 0;
                                             $working_sankul_count = 0;
                                            foreach($processed_data as $data){ 
                                                $tehsil = $data['block'];
                                                $sankul = $data['sankul'];
                                                $iCount++;
                                                if(!empty($tehsil)) {
                                                    $total_tehsil_count++;
                                                    if($tehsil->school_count >0)  $working_tehsil_count++;
                                                }
                                                if(!empty($sankul)){
                                                    $total_sankul_count++;
                                                    if($sankul->school_count >0)  $working_sankul_count++;
                                                } 
                                                ?>
                                                <tr>
                                                <td ><?php echo $iCount ?></td>
                                                <td > <?php echo isset($tehsil->name) ? $tehsil->name : "" ?></td>
                                                <td > <?php echo isset($tehsil->school_count) && $tehsil->school_count >0 ?  $tehsil->name  : "" ?></td>
                                                <td ><?php echo  isset($sankul->name) ? $sankul->name : "" ?> </td>
                                                <td ><?php echo  isset($sankul->school_count) && $sankul->school_count >0? $sankul->name : "" ?> </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>

                                            <?php } ?>
                                            <tr>
                                                <th >Total</th>
                                                <th > <?php echo $total_tehsil_count ?></th>
                                                <th > <?php echo $working_tehsil_count ?></th>
                                                <th ><?php echo $total_sankul_count ?> </th>
                                                <th ><?php echo $working_sankul_count ?></th>
                                            </tr>
                                            </tfoot>

                                    </table>
                               <? } else { ?> 
                                <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                        <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="6" style="text-align : center">
                                                    Working Area Report |
                                                   <?php if(isset($report_heading))  echo " ".$report_heading  ?> 
                                                </th>
                                            </tr>
                                            <?php }?>
                                            <tr>
                                                <th >Sr</th>
                                                <th > District</th>
                                                <th > Total Tehsil</th>
                                                <th > Working IN</th>
                                                <th >Total Sankul </th>
                                                <th >Working IN</th>

                                            </tr>
                                        </thead>
                                        <tbody>   
                                        <?php 
                                            $iCount =0;
                                             $total_tehsil_count = 0;
                                             $total_sankul_count = 0;
                                             $working_tehsil_count = 0;
                                             $working_sankul_count = 0;
                                            foreach($processed_data as $data){ 
                                               
                                                $filter_name = $data['filter_name'];
                                                $tehsil = $data['block'];
                                                $working_sankul = $data['working_sankul'];
                                                $working_tehsil = $data['working_block'];
                                                $sankul = $data['sankul'];
                                                $iCount++;
                                               
                                                $total_tehsil_count = $total_tehsil_count +$tehsil;
                                                $working_tehsil_count = $working_tehsil_count +$working_tehsil;
                                                $total_sankul_count = $total_sankul_count +$sankul;
                                                $working_sankul_count = $working_sankul_count +$working_sankul;
                                                ?>
                                                <tr>
                                                <td ><?php echo $iCount ?></td>
                                                <td ><?php echo $filter_name  ?></td>
                                                <td > <?php echo $tehsil ?></td>
                                                <td > <?php echo  $working_tehsil ?></td>
                                                <td ><?php echo  $sankul ?> </td>
                                                <td ><?php echo   $working_sankul ?> </td>
                                            </tr>
                                            </tbody>
                                            <?php } ?>

                                            <?php if($_POST) {?>
                                            <tfoot>

                                            <tr>
                                                <td colspan="2">Total</td>
                                                <td > <?php echo $total_tehsil_count ?></td>
                                                <td > <?php echo $working_tehsil_count ?></td>
                                                <td ><?php echo $total_sankul_count ?> </td>
                                                <td ><?php echo $working_sankul_count ?></td>
                                            </tr>
                                            </tfoot>
                                            <?php } ?>

                                    </table>
                            <?php } ?>
                          
                           </div>
                       </div>
                    

                           <h4>Signature: __________ </h4>  
                   </div>
               </div>
           </div>
           
           <div class="row no-print">
               <div class="col-xs-12 text-right">
                   <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
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
		XLSX.writeFile(wb, fn || ('working_area_report.' + (type || 'xlsx')));
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
                ]
            });          
          });
</script>
<script type="text/javascript">
   
   $("#student").validate();  

   $("document").ready(function() {
        <?php if(isset($school_id) && !empty($school_id)){ ?>
           $(".fn_school_id").trigger('change');
        <?php } ?>
   });
    
   $('.fn_school_id').on('change', function(){
     
       var school_id = $(this).val();
       var academic_year_id = '';
       
       <?php if(isset($school_id) && !empty($school_id)){ ?>
           academic_year_id =  '<?php echo $academic_year_id; ?>'; 
        <?php } ?>          
       
       if(!school_id){
          toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
          return false;
       }
       
       get_academic_year_by_school(school_id, academic_year_id);
      
   });
   
           
   function get_academic_year_by_school(school_id, academic_year_id){       
        
       $.ajax({       
           type   : "POST",
           url    : "<?php echo site_url('ajax/get_academic_year_by_school'); ?>",
           data   : { school_id:school_id, academic_year_id :academic_year_id},               
           async  : false,
           success: function(response){                                                   
              if(response)
              { 
                   $('#academic_year_id').html(response); 
              }
           }
       });
  }  

  // ak

    $(document).ready(function(){
            $('#district_col').hide();   
            $('#zone_col').hide();   

        <?php
        if((isset($report_type))){  ?>
            <?php if($report_type == "district")
            { ?>
                $('#district_col').show();   

            <?php }else if($report_type == "prant") {?>
                $('#zone_col').show();  
            <?php } } ?>
            
           
           // Checkbox click
           $('#report_type').on('change',function(){
            $('#district_col').hide();   
            $('#zone_col').hide();   
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


  // ak
   
</script>   

