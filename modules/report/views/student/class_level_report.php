
<?php
$colspan = 0;
  foreach($heading_cols as $faculty_id => $faculty_data) {
    foreach($faculty_data as $class_name ) {
        $colspan++;
    }
}
$rowspan = !empty($heading_cols) ? 'rowspan="2"' : '';
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
               <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> Faculty <?php echo $this->lang->line('report'); ?></small></h3>                
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
           
                    echo form_open_multipart(site_url('report/faculty_report'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), '');
              
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
                                <?php if($this->session->userdata('role_id') == SUPER_ADMIN ) {?>
                              <option value="prant"  <?php echo isset($filter_type) && $filter_type == "prant" ? "selected='selected'" : "" ; ?>>Prant Wise</option>
                              <option value="kshetra"  <?php echo isset($filter_type) && $filter_type == "kshetra" ? "selected='selected'" : "" ; ?>>Kshetra Wise</option>
                              <?php }?>
                              <?php }?>
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
                           <div class="x_content" style="overflow-x:scroll">
                              
                                    <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0"  width="100%">
                                        <thead>
                                        <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="<?php echo 3+$colspan ?>" style="text-align : center">
                                                    Faculty Report |  
                                                   <?php if(isset($school))  echo " ".$school->school_name." "  ?> | 
                                                   <?php  echo  date("d/m/Y")   ?> 
                                                </th>
                                            </tr>
                                            <?php }?>
                                            <tr>
                                                <th <?php echo $rowspan ?>>Sr</th>
                                                <th <?php echo $rowspan ?> > <?php echo $filter_heading; ?></th>
                                              <?php 
                                               if($filter_type == "kshetra" || $filter_type == "prant" || $filter_type == "district"  || 1==1)
                                               {

                                                foreach($heading_cols as $faculty_id => $faculty_data) { ?>
                                                          <th colspan="<?php echo count($faculty_data); ?>"><?php echo  $faculties[$faculty_id]; ?></th>
                                                <?php }
                                               } else
                                               {
                                                foreach($faculties as $key => $faculty) { ?>
                                                          <th><?php echo  $faculty; ?></th>
                                                <?php }
                                               } ?>
                                            
                                              <th <?php echo $rowspan ?>>Total</th>
                                            </tr>
                                            <?php if(!empty($heading_cols)) { ?>
                                            <tr>
                                              <?php 
                                               if($filter_type == "kshetra" || $filter_type == "prant" || $filter_type == "district" || 1==1)
                                               {

                                                foreach($heading_cols as $faculty_id => $faculty_data) { 
                                                    foreach( $faculty_data as $class_name){ ?>
                                                            <th><?php echo  $class_name; ?></th>
                                                       <?php }
                                                 }
                                               } else
                                               {
                                               
                                               } ?>
                                            
                                            </tr>
                                            <?php } ?>

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
                                   $total_students_count = 0;
                                   $grand_total = 0;
                                   $faculty_total_counts = array();
                                  
                                foreach($student_data as $key => $obj){ 
                                   
                                   if($obj['filter_name'] == '')
                                   {
                                      //continue;
                                   }
                                   $iCount++;
                                   
                                ?>
                                       <tr>
                                            <td><?php echo $iCount ?></td>   
                                           <td><?php echo $obj['filter_name' ]?></td>           
                                           <?php 
                                           $total =0;
                                           if($filter_type == "kshetra" || $filter_type == "prant" || $filter_type == "district" || 1==1)
                                           {
                                            foreach($heading_cols as $faculty_id => $faculty_data) {

                                                foreach($faculty_data as $class_name ) {
                                                    if($filter_type == "kshetra" || $filter_type == "prant" || $filter_type == "district")
                                                    {
                                                        if(isset($obj[$faculty_id][$class_name]['students']))
                                                        {
                                                            $students_count = $obj[$faculty_id][$class_name]['students'];
                                                        }
                                                        else
                                                        {
                                                            $students_count = 0;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        if(isset($obj[$faculty_id][$class_name]['students']))
                                                        {
                                                            $stuents_array = array_unique($obj[$faculty_id][$class_name]['students']);
                                                            $students_count =  count($stuents_array);;
                                                        }
                                                        else
                                                        {
                                                            $students_count = 0;
                                                        }


                                                    }
                                                
                                                   
                                                    if(!isset( $faculty_total_counts[$faculty_id][$class_name] )) $faculty_total_counts[$faculty_id][$class_name] = 0;
                                                    $faculty_total_counts[$faculty_id][$class_name]  = $faculty_total_counts[$faculty_id][$class_name] +  $students_count ;
                                                    $total =  $total+ $students_count;
                                                    ?>
                                                    <td><?php echo  $students_count ?></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td><?php echo  $total ?></td>
                                            <?php
                                           }
                                           else
                                           {
                                            foreach($faculties as $faculty_id => $faculty) {
                                                if(isset($obj[$faculty_id]['students']))
                                                {

                                                    if($filter_type == "kshetra" || $filter_type == "prant" || $filter_type == "district")
                                                    {
                                                        $students_count = $obj[$faculty_id]['students'];
                                                    }
                                                    else
                                                    {
                                                        $stuents_array = array_unique($obj[$faculty_id]['students']);
                                                        $students_count = count($stuents_array);
                                                    }
                                                
                                                    if(!isset( $faculty_total_counts[$faculty_id] )) $faculty_total_counts[$faculty_id] = 0;
                                                    $faculty_total_counts[$faculty_id] = $faculty_total_counts[$faculty_id] +  $students_count ;

                                                }
                                                else
                                                {
                                                    $students_count = 0;
                                                }
                                                $total =  $total+ $students_count;
                                                ?>
                                                            <td><?php echo  $students_count ?></td>
                                                <?php }?>
                                                <td><?php echo  $total ?></td>
                                        
                                        <?php
                                        }   
                                    }  
                                    ?>
                                    </tr>
                                       
                                       
                                      
                                    </tbody>
                                <tfoot>
                                       <tr>
                                           <td colspan="2">Total</td>   
                                           <?php
                                             if($filter_type == "kshetra" || $filter_type == "prant" || $filter_type == "district" || 1==1)
                                             {
                                              foreach($heading_cols as $faculty_id => $faculty_data) {
                                                  foreach($faculty_data as $class_name ) {
                                                    $total_value = isset($faculty_total_counts[$faculty_id][$class_name]) ? $faculty_total_counts[$faculty_id][$class_name] : 0;
                                                    $grand_total = $grand_total +  $total_value ;
                                                    ?>
                                                    <td><?php echo $total_value; ?></td>
                                                <?php 
                                                  }
                                                }
                                            } 
                                           else{
                                           foreach($faculties as $faculty_id => $faculty) { 
                                               $total_value = isset($faculty_total_counts[$faculty_id]) ? $faculty_total_counts[$faculty_id] : 0;

                                               $grand_total = $grand_total +  $total_value ;
                                               ?>
                                                        <td><?php echo $total_value; ?></td>
                                           <?php } 
                                           
                                           }?>
                                           <td><?php echo  $grand_total ?></td>
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
		XLSX.writeFile(wb, fn || ('payroll_report.' + (type || 'xlsx')));
}
$(document).ready(function() {
            
            $('.datatable-responsive').DataTable({
                dom: 'Bfrtip',
                "ordering": false,
                "searching": false,
                "paging" : false,
                "responsive" : false,
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

