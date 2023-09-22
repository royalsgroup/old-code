
<?php
$categories = array("GENERAL","OBC","SC","ST","SBC","Minority");
$colspan = 2+(3*count($categories));
?>
<style>
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
               <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> Category <?php echo $this->lang->line('report'); ?></small></h3>                
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
           
                    echo form_open_multipart(site_url('report/category_report'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), '');
              
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
                   <?php $this->load->view('layout/school_list_filter'); ?>

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

           <div class="x_content" id="main-content">
               <div class="" data-example-id="togglable-tabs">
                                     
                   <ul  class="nav nav-tabs bordered no-print">
                       <li class=""><a href="#tab_tabular"   role="tab" data-toggle="tab"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('tabular'); ?> <?php echo $this->lang->line('report'); ?></a> </li>
                   </ul>
                   <br/>
                   
                   <div class="tab-content">
                       <div  class="tab-pane fade in active" id="tab_tabular" >
                           <div class="x_content" style="overflow-x:scroll">
                              <?php  if(!empty($categories))
                                   { ?>
                                    <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0"  width="100%">
                                        <thead>
                                        <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="<?php echo 5+(3*count($categories)) ?>" style="text-align : center">
                                                    Category Report |
                                                   <?php if(isset($school))  echo " ".$school->school_name." |"  ?> 
                                                </th>
                                            </tr>
                                            <?php }?>
                                            <tr>
                                                <th rowspan="2">Sr</th>
                                                <th rowspan="2" style="width:350px !important;"> <?php echo $filter_heading; ?></th>
                                              <?php foreach($categories as  $category) {?>
                                                        <th colspan="3"><?php echo  $category; ?></th>
                                              <?php }?>
                                              <th colspan="3">Grand Total</th>
                                            </tr>
                                            <tr>
                                            
                                              <?php foreach($categories as  $category) {?>
                                                        <th >B</th>
                                                        <th>G</th>
                                                        <th >T</th>
                                              <?php }?>
                                              <th >B</th>
                                                <th>G</th>
                                                <th >T</th>
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
                                   $total_students_count = 0;
                                   $boys_total_counts = array();
                                   $girls_total_counts = array();

                                   $grand_total_boys = 0;
                                   $grand_total_girls = 0;

                                if($filter_type == "kshetra")
                                {
                                    $iCount =1;
                                    foreach($zones as   $zone)
                                    {
                                        $iCount++;
                                        ?>
                                            <tr>
                                                <td><?php echo $iCount ?></td>   
                                                <td><?php echo $zone->name?></td>
                                <?php
                                        $category_total =0;
                                        $category_boys_total = 0;
                                        $category_girls_total = 0;
                                        foreach($categories as  $category) {
                                           $boys_count =  $student_data[ $zone->id][$category]['boys'];
                                           $girls_count =  $student_data[$zone->id][$category]['girls'];
                                           $total_count =  $boys_count+ $girls_count ; 
                                           $category_boys_total = $category_boys_total +$boys_count;
                                           $category_girls_total =   $category_girls_total  +$girls_count;
                                            $category_total = $category_total + $total_count;
                                            if(!isset( $boys_total_counts[$category] )) $boys_total_counts[$category] = 0;
                                            if(!isset( $girls_total_counts[$category] )) $girls_total_counts[$category] = 0;
                                            $boys_total_counts[$category] = $boys_total_counts[$category] +  $boys_count ;
                                            $girls_total_counts[$category] = $girls_total_counts[$category] +  $girls_count ;


                                           ?>
                                           
                                           <td><?php echo  $boys_count ?></td>
                                                 <td><?php echo  $girls_count ?></td>
                                                        <td><?php echo  $total_count ?></td>

                                       <?php } ?>
                                            <td><?php echo  $category_boys_total ?></td>
                                              <td><?php echo  $category_girls_total ?></td>
                                             <td><?php echo  $category_total ?></td>
                                       </tr>  


                                <?php 
                                 $grand_total_boys   = $grand_total_boys + $category_boys_total;
                                 $grand_total_girls = $grand_total_girls +  $category_girls_total;
                                     }
                                }
                                else
                                {
                                
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
                                           $category_boys_total = 0;
                                           $category_girls_total = 0;
                                           foreach($categories as  $category) {
                                               
                                               
                                            $category = strtoupper($category);

                                            if(isset($obj[$category ]['boys']))
                                            {
                                                $boys_array = array_unique($obj[$category]['boys']);
                                                $boys_count = count($boys_array);
                                                $boys_total_counts[$category] = $boys_total_counts[$category] +  $boys_count ;
                                                $category_boys_total = $category_boys_total +$boys_count;

                                            }
                                            else{
                                                $boys_count = 0;
                                            }
                                            if(isset($obj[$category ]['girls']))
                                            {
                                                $girls_array = array_unique($obj[$category]['girls']);
                                               
                                                $girls_count = count($girls_array);
                                                $girls_total_counts[$category] = $girls_total_counts[$category] +  $girls_count ;
                                                $category_girls_total =   $category_girls_total  +$girls_count;
                                            }
                                            else{
                                                $girls_count = 0;
                                            }
                                               if(isset($obj[$category ]['students']))
                                               {
                                               
                                              
                                                $stuents_array = array_unique($obj[$category]['students']);
                                               
                                                $students_count = count($stuents_array);
                                                if(!isset( $faculty_total_counts[$category] )) $faculty_total_counts[$category] = 0;
                                                $faculty_total_counts[$category] = $faculty_total_counts[$category] +  $students_count ;

                                               }
                                               else
                                               {
                                                $students_count = 0;
                                               }
                                               $total =  $total+ $students_count;
                                               ?>
                                                 <td><?php echo  $boys_count ?></td>
                                                 <td><?php echo  $girls_count ?></td>
                                                        <td><?php echo  $students_count ?></td>
                                              <?php }?>
                                              <td><?php echo  $category_boys_total ?></td>
                                              <td><?php echo  $category_girls_total ?></td>
                                             <td><?php echo  $total ?></td>
                                       </tr>
                                       <?php
                                            $grand_total_boys   = $grand_total_boys + $category_boys_total;
                                            $grand_total_girls = $grand_total_girls +  $category_girls_total;
                                            
   
                                    }          
                                }
                                      
                                       ?>                                        </tbody>

                            <tfoot>

                                       <tr>
                                           <td colspan="2">Total</td>   
                                           <?php foreach($categories as  $category) { ?>
                                            <td><?php echo isset($boys_total_counts[$category]) ? $boys_total_counts[$category] : 0 ?></td>
                                            <td><?php echo isset($girls_total_counts[$category]) ? $girls_total_counts[$category] : 0 ?></td>
                                                        <td><?php echo $boys_total_counts[$category]+$girls_total_counts[$category] ?></td>
                                           <?php } ?>
                                           <td><?php echo  $grand_total_boys ?></td>
                                              <td><?php echo  $grand_total_girls ?></td>
                                             <td><?php echo  $grand_total_boys+$grand_total_girls ?></td>
                                       </tr>
                                       </tfoot>
                                    </table>
                                    <?php } else{   ?>
                                        <center>No Data Found </center>
                                        <?php      }?>
                           
                          
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
		XLSX.writeFile(wb, fn || ('category_report.' + (type || 'xlsx')));
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

       function printDiv() {
            $("#main-content").printThis({
                debug: false,               // show the iframe for debugging
                importCSS: true,            // import parent page css
                importStyle: true,          // import style tags
               
            });
        }
  // ak
   
</script>   

