
<?php
                    $class_name ="";
                    $iDebug = $_REQUEST['debug'] ?? 0;

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
               <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('statics'); ?>  <?php echo $this->lang->line('report'); ?></small></h3>                
               <ul class="nav navbar-right panel_toolbox">
                   <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
               </ul>
               <div class="clearfix"></div>
           </div>
           <div class="x_content quick-link no-print">
           <?php $this->load->view('quick-link.php'); ?>  

           </div>            
            <div class="x_content filter-box no-print"> 
               <?php echo form_open_multipart(site_url('report/student_statics'), array('name' => 'student', 'id' => 'student', 'class' => 'form-horizontal form-label-left'), ''); ?>
               <div class="row">   
                    <div class="col-md-3 col-sm-3 col-xs-12">

                       <div class="form-group item">
                       <div>Filter Type <span class="required"> *</span></div>
                            <input type="hidden" name="debug" value="<?php echo $iDebug ?>">
                          <select name="filter_type" class="form-control col-md-7 col-xs-12" id="filter_type" required="required">
                          <option value="class">-- Select Filter Type --</option>
                              <option value="class" <?php echo isset($filter_type) && $filter_type == "class" ? "selected='selected'" : "" ; ?> >Class Wise</option>
                              <?php if(($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1)) {?>
                                <option value="school"  <?php echo isset($filter_type) && $filter_type == "school" ? "selected='selected'" : "" ; ?>>School Wise</option>
                                <option value="prant"  <?php echo isset($filter_type) && $filter_type == "prant" ? "selected='selected'" : "" ; ?>>Prant Wise</option>
                                <option value="district"  <?php echo isset($filter_type) && $filter_type == "district" ? "selected='selected'" : "" ; ?>>District Wise</option>
                              <?php }?>
                          </select>
                       </div>
                   </div>
                   <div class="col-md-3 col-sm-3 col-xs-12" id="district_col">

                       <div class="form-group item">
                       <div>District </div>

                          <select name="district_id" class="form-control col-md-7 col-xs-12" id="district_select">
                                <option value="">-- Select District --</option>
                                <?php foreach($districts as $district) { 
                                    if(isset($filter_type) && $filter_type == "school" && isset($district_id) && $district_id == $district->id)
                                    {
                                        $filter_head = $district->name ;
                                    }
                                    ?>
                                    <option value="<?php echo  $district->id  ?>" <?php echo isset($district_id) && $district_id == $district->id ? "selected='selected'" : "" ; ?> ><?php echo  $district->name  ?></option>
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

           <div class="x_content" id="main-content">
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
                           <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                               <thead>
                               <?php if($_POST) {?>
                                            <tr>
                                                <th colspan="15" style="text-align : center">
                                                    Studen stastitics Report |  
                                                   <?php if(isset($filter_head))  echo " ".$filter_head." "  ?> 
                                                </th>
                                            </tr>
                                            <?php }?>
                                   <tr>
                                        <th rowspan="2 " style="width:20px"></th>
                                       <th rowspan="2 " style="width:120px"><?php echo $this->lang->line('class'); ?></th>
                                       <th colspan="3">Last Year  <?php echo $prev_year ?? "" ?></th>
                                       <th rowspan="2 " style="width:120px">Promote</th>
                                       <th colspan="3">TC </th>
                                       <th colspan="3">Current Year <?php echo $current_year ?? "" ?> New Admission</th>
                                       <th colspan="3">Total Student</th>
                                       <th rowspan="2">Goal</th>
                                   </tr>
                                   <tr>
                                       <th>Boys</th>
                                       <th>Girls</th>
                                       <th>Total</th>
                                       <th>Boys</th>
                                       <th>Girls</th>
                                       <th>Total</th>
                                       <th>Boys</th>
                                       <th>Girls</th>
                                       <th>Total</th>
                                       <th>Boys</th>
                                       <th>Girls</th>
                                       <th>Total</th>
                                     
                                   </tr>
                               </thead>
                               <tbody>   
                                   <?php 
                                   $last_year_boys = 0;
                                   $last_year_girls = 0;
                                   $tc_boys = 0;
                                   $tc_girls = 0;
                                   $curr_year_boys = 0;
                                   $curr_year_girls = 0;
                                   $total_boys = 0;
                                   $total_girls = 0;
                                   $iCount = 0;
                                   $sHTMLData = "";
                                   $iPromotedTotal = 0;
                                foreach($student_data as $key => $obj){ 
                                   if($obj['filter_name'] == '')
                                   {
                                      //continue;
                                   }
                                   $iCount++;
                                    $prev_boys = array_unique($obj['prev']['boys']);
                                    $prev_girls = array_unique($obj['prev']['girls']);
                                    $curr_all_boys = array_unique($obj['curr']['boys']);
                                    $curr_all_girls = array_unique($obj['curr']['girls']);
                                    $tc_boys_data = array_unique($obj['curr']['tc_boys']);
                                    $tc_girls_data = array_unique($obj['curr']['tc_girls']);
                                
                                    $curr_boys = array_diff($curr_all_boys,$tc_boys_data);
                                    $curr_girls = array_diff($curr_all_girls,$tc_girls_data);
                        
                                   $new_boys = array_diff($curr_boys,$prev_boys);
                                   $new_girls = array_diff($curr_girls,$prev_girls);
                                   $left_boys = $tc_boys_data;//array_diff($prev_boys,$curr_boys);
                                   $left_girls =  $tc_girls_data;//array_diff($prev_girls,$curr_girls);
                                   if($filter_type != "class")
                                   {
                                        $previous_year_boys_students_data =  $previous_year_boys_students[$key];
                                        $previous_year_girls_students_data =  $previous_year_girls_students[$key];
                                        $current_year_boys_students_data =  $current_year_boys_students[$key];
                                        $current_year_girls_students_data =  $current_year_girls_students[$key];
                                        $aPromotedBoys = array_intersect($curr_all_boys,$prev_boys);
                                        $aPromotedGirls = array_intersect($curr_all_girls,$prev_girls);
                                        $iPromoted = 0;
                                        $iPromoted = (count($aPromotedBoys) + count($aPromotedGirls));
                                   }
                                   else
                                   {
                                    $iPreviousClassIDKey = array_search ( $key, $class_order);
                                    if($iDebug ==1) {
                                        $iPreviousClassID    = $class_order[((int)$iPreviousClassIDKey +1)] ?? "";
                                    } else {
                                        $iPreviousClassID    = $class_order[((int)$iPreviousClassIDKey -1)] ?? "";
                                    }
                                    $current_year_boys_students_data =  $current_year_boys_students[$key];
                                    $current_year_girls_students_data =  $current_year_girls_students[$key];
                                        // if($iPreviousClassID != "") {
                                        //     $next_year_boys_students_data =  $student_data[$iPreviousClassID];
                                        //     $next_year_girls_students_data =  $student_data[$iPreviousClassID];
                                        //     $next_boys_compare = array_unique($next_year_boys_students_data['curr']['boys']);
                                        //     $next_girls_compare = array_unique($next_year_girls_students_data['curr']['girls']);
                                        //     debug_a([$next_boys_compare,$prev_boys],"",1);

                                        // } else {
                                        //     $next_boys_compare =array();
                                        //     $next_girls_compare =array();
                                        // }
                                        if($iDebug ==1) {

                                        if($iPreviousClassID != "") {
                                                $previous_year_boys_students_data =  $student_data[$iPreviousClassID];
                                                $previous_year_girls_students_data =  $student_data[$iPreviousClassID];
                                                $prev_boys = array_unique($previous_year_boys_students_data['prev']['boys']);
                                                $prev_girls = array_unique($previous_year_girls_students_data['prev']['girls']);
                                            } else {
                                                $prev_boys =array();
                                                $prev_girls =array();
                                            }
                                            $new_boys = array_diff($curr_boys,$prev_boys);
                                            $new_girls = array_diff($curr_girls,$prev_girls);
                                            $left_boys = array_diff($prev_boys,$curr_boys);
                                            $left_girls = array_diff($prev_girls,$curr_girls);
                                            $iPromoted =0;

                                        }else {
                                            if($iPreviousClassID != "") {
                                                $previous_year_boys_students_data =  $student_data[$iPreviousClassID];
                                                $previous_year_girls_students_data =  $student_data[$iPreviousClassID];
                                                $prev_boys_compare = array_unique($previous_year_boys_students_data['prev']['boys']);
                                                $prev_girls_compare = array_unique($previous_year_girls_students_data['prev']['girls']);
                                            } else {
                                                $prev_boys_compare =array();
                                                $prev_girls_compare =array();
                                            }
                                           $new_boys = array_diff($curr_boys,$prev_boys_compare);
                                           $new_girls = array_diff($curr_girls,$prev_girls_compare);
                                        //    $left_boys = array_diff($prev_boys_compare,$curr_boys);
                                        //    $left_girls = array_diff($prev_girls_compare,$curr_girls);
                                           $aPromotedBoys = array_intersect($curr_all_boys,$prev_boys_compare);
                                          
                                           $aPromotedGirls = array_intersect($curr_all_girls,$prev_girls_compare);
                                           $iPromoted = $iCount == 1 ? 0 : (count($aPromotedBoys) + count($aPromotedGirls));
                                        //    $iPromoted = $iCount == 1 ? 0 : (count($curr_boys) + count($curr_girls)) - (count($prev_boys_compare)+count($prev_girls_compare));
                                        }
                                        // $new_boys = array_diff($curr_boys,$prev_boys);
                                        // $new_girls = array_diff($curr_girls,$prev_girls);
                                        // $left_boys = array_diff($prev_boys,$curr_boys);
                                        // $left_girls = array_diff($prev_girls,$curr_girls);
                                   }
                                
                                   $iPromotedTotal = $iPromotedTotal +  $iPromoted;
                                    
                                    $prev_boys_count = count($prev_boys);
                                    $prev_girls_count = count($prev_girls);
                                    $curr_boys_count = count($curr_boys);
                                    $curr_girls_count = count($curr_girls);
                                    
                                    $new_boys_count = count($new_boys);
                                    $new_girls_count = count($new_girls);

                                    $left_boys_count = count($left_boys);
                                    $left_girls_count = count($left_girls);

                                    $promoted_boys = $prev_boys_count - $left_boys_count ;
                                    $promoted_girls = $prev_girls_count - $left_girls_count ;

                                    $last_year_boys = $last_year_boys+$prev_boys_count;
                                    $last_year_girls = $last_year_girls +  $prev_girls_count;
                                    $tc_boys = $tc_boys+$left_boys_count  ;
                                    $tc_girls = $tc_girls+$left_girls_count ;
                                    $curr_year_boys = $curr_year_boys+$new_boys_count;
                                    $curr_year_girls = $curr_year_girls+ $new_girls_count;
                                    
                                    $total_boys = $total_boys+  $curr_boys_count;
                                    $total_girls = $total_girls +$curr_girls_count;
                                        $sHTMLData = "";
                                    ?>
                                       <tr>
                                            <td><?php echo $iCount ?></td>   
                                           <td><?php echo $obj['filter_name']?></td>                                            
                                           <td><?php echo $prev_boys_count ?></td> 
                                           <td><?php echo $prev_girls_count ?></td> 
                                           <td><?php echo ($prev_girls_count+$prev_boys_count) ?></td> 
                                           <td><?php echo $iPromoted ?></td> 
                                           <td><?php echo $left_boys_count ?></td> 
                                           <td><?php echo $left_girls_count ?></td> 
                                           <td><?php echo($left_boys_count+$left_girls_count) ?></td>
                                           <td><?php echo $new_boys_count ?></td> 
                                           <td><?php echo $new_girls_count ?></td> 
                                           <td><?php echo($new_boys_count+$new_girls_count) ?></td>
                                           <td><?php echo $curr_boys_count?></td> 
                                           <td><?php echo $curr_girls_count  ?></td> 
                                           <td><?php echo($curr_boys_count+$curr_girls_count) ?></td>
                                           <td></td>
                               
                                       </tr>
                                       <?php } 
                                      
                                       ?>
                                                                      </tbody>

                                       <tfoot>
                                       <tr>
                                           <td></td>
                                           <td>Total</td>                                            
                                           <td><?php echo $last_year_boys ?></td> 
                                           <td><?php echo $last_year_girls ?></td> 
                                           <td><?php echo ($last_year_boys+$last_year_girls) ?></td> 
                                           <td><?php echo $iPromotedTotal ?></td> 
                                           <td><?php echo $tc_boys ?></td> 
                                           <td><?php echo $tc_girls ?></td> 
                                           <td><?php echo($tc_boys+$tc_girls) ?></td>
                                           <td><?php echo $curr_year_boys ?></td> 
                                           <td><?php echo $curr_year_girls ?></td> 
                                           <td><?php echo($curr_year_boys+$curr_year_girls) ?></td>       
                                           <td><?php echo  $total_boys ?></td> 
                                           <td><?php echo  $total_girls ?></td> 
                                           <td><?php echo($total_boys+$total_girls) ?></td>       
                                           <td></td>                                  
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
var extra_value = <?php echo isset($extra_value) && $extra_value ?  $extra_value : 0 ?>;

function doit(type, fn, dl) {
	var elt = document.getElementById('datatable-keytable');
	var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
	return dl ?
		XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
		XLSX.writeFile(wb, fn || ('student_stasticts_report.' + (type || 'xlsx')));
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
  $('#fee_type').change(function(){
        getextra_dropwdown( $(this).val())
   })
  function getextra_dropwdown(fee_type)
   {
        $('#extra_dropdown').hide();
        var school_id = $('#school_id').val();
        var academic_year_id = <?php echo isset($academic_year_id) && $academic_year_id ?  $academic_year_id : 0 ?>;
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_extra_dropdowns'); ?>",
            data   : { school_id:school_id, academic_year_id :academic_year_id,  fee_type :fee_type, extra_value : extra_value},               
            async  : false,
            success: function(response){                                                   
                if(response)
                { 
                    if (response)
                    {
                        $('#extra_dropdown').show();
                        $('#extra_dropdown').html(response)
                    }
                    else
                    {
                        $('#extra_dropdown').hide();

                        $('#extra_dropdown').html('')

                    }
                }
            }
        });
   }
  // ak

    $(document).ready(function(){
        $('#school_filter_col').hide();
            $('#district_col').hide();   
        <?php
        if((isset($filter_type))){  ?>
            <?php if($filter_type == "class")
            { ?>
                $('#school_filter_col').show();   

            <?php } 
            else if($filter_type == "school")
            { ?>
                 $('#district_col').show();   
            <?php } ?>
           
       <?php } ?>
           // Checkbox click
           $('#filter_type').on('change',function(){
            $('#district_col').hide();   
            $('#school_filter_col').hide();
               if(this.value =="class")
               {
                   $('#school_filter_col').show();
               }
               else if(this.value =="school")
               {
                    $('#district_col').show();   
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

