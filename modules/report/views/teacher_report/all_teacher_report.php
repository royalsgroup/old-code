
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
               <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('report'); ?></small></h3>                
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
                if(isset($all_teacher_report) && $all_teacher_report)
                {
                    echo form_open_multipart(site_url('report/all_teacher_report'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), ''); 

                }
                else
                {
                    echo form_open_multipart(site_url('report/teacher_report'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), '');
                }
                ?>
               <div class="row">   
                    <div class="col-md-3 col-sm-3 col-xs-12">

                       <div class="form-group item">
                       <div>Filter Type <span class="required"> *</span></div>

                          <select name="filter_type" class="form-control col-md-7 col-xs-12" id="report_type" required="required">
                          <option value="">-- Select Report Type --</option>
                          <option value="school"  <?php echo isset($filter_type) && $filter_type == "school" ? "selected='selected'" : "" ; ?>>School Wise</option>
                              <?php if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {?>
                                <option value="district" <?php echo isset($filter_type) && $filter_type == "district" ? "selected='selected'" : "" ; ?> >District Level</option>
                                <?php if( !$this->session->userdata('subzone_id')) {?>
                              <option value="prant"  <?php echo isset($filter_type) && $filter_type == "prant" ? "selected='selected'" : "" ; ?>>Prant Wise</option>
                              <?php if( !$this->session->userdata('zone_id')) {?>
                              <option value="kshetra"  <?php echo isset($filter_type) && $filter_type == "kshetra" ? "selected='selected'" : "" ; ?>>Kshetra Wise</option>
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
                   <?php $this->load->view('layout/school_list_filter'); ?>

                   
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
                           <div class="x_content">
                              
                                    <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="9" style="text-align : center">
                                                    Teacher Report | 
                                                   <?php if(isset($table_heading))  echo " ".$table_heading." | ". date("d/m/Y") ?>  
                                                </th>
                                            </tr>
                                            <?php }?>
                                            <tr>
                                                <th rowspan="2">Sr</th>
                                                <th rowspan="2" style="width:350px !important;"> <?php echo $filter_heading; ?></th>
                                                <th rowspan="2" style="width:120px !important;"> Total section</th>
                                                <th  colspan="3">Teacher </th>
                                                <th  colspan="3">Student</th>
                                            </tr>
                                            <tr>
                                                <th  > Male</th>
                                                <th  > Female</th>
                                                <th  > Total</th>
                                                <th  > Boys</th>
                                                <th  > Girls</th>
                                                <th  > Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                        <?php 
                                
                                   $curr_year_boys = 0;
                                   $curr_year_girls = 0;
                                   $total_boys = 0;
                                   $total_girls = 0;
                                   $total_female = 0;
                                   $total_male = 0;
                                   $iCount = 0;
                                   $total_section_count = 0;
                                   $all_sections = array();
                                foreach($student_data as $key => $obj){ 
                                   if($obj['filter_name'] == '')
                                   {
                                      //continue;
                                   }
                                   $iCount++;
                                  
                                    $section_count =  $obj['section_count'];
                                  

                                   $current_year_boys_students_data =  $current_year_boys_students[$key];
                                   $current_year_girls_students_data =  $current_year_girls_students[$key];
                               
                                   //$section_count          = count( $sections );
                                    $curr_boys_count = $obj['curr']['boys'];
                                    $curr_girls_count = $obj['curr']['girls'];

                                    $male_teachers = $obj['male_teacher_count'];
                                    $female_teachers = $obj['female_teacher_count'];
                                    $total_boys = $total_boys +  $curr_boys_count;
                                    $total_girls = $total_girls +$curr_girls_count;
                                    $total_female = $total_female + $female_teachers;
                                    $total_male = $total_male + $male_teachers;
                        

                                    $total_section_count =  $total_section_count +  $section_count;
                                    ?>
                                       <tr>
                                            <td><?php echo $iCount ?></td>   
                                           <td><?php echo $obj['filter_name' ]?></td>           
                                           <td> <?php echo $section_count ?></td>                                            
                                           <td><?php echo $male_teachers ?></td> 
                                           <td><?php echo $female_teachers  ?></td> 
                                           <td><?php echo($male_teachers+$female_teachers) ?></td>
                                           <td><?php echo $curr_boys_count?></td> 
                                           <td><?php echo $curr_girls_count  ?></td> 
                                           <td><?php echo($curr_boys_count+$curr_girls_count) ?></td>
                                       </tr>
                                       <?php }  
                                       
                                       
                                      
                                       ?></tbody>
                                       <tfoot>
                                       <tr>
                                           <td colspan="2">Total</td>   
                                           <td> <?php echo $total_section_count ?></td>     
                                           <td><?php echo $total_male ?></td> 
                                           <td><?php echo $total_female  ?></td> 
                                           <td><?php echo($total_male+$total_female) ?></td>
                                           <td><?php echo $total_boys?></td> 
                                           <td><?php echo $total_girls  ?></td> 
                                           <td><?php echo($total_boys+$total_girls) ?></td>
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
		XLSX.writeFile(wb, fn || ('teacher_report.' + (type || 'xlsx')));
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
            $('#school_filter_col').hide();  
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


  // ak
   
</script>   

