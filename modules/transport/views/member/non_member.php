<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-bus"></i><small> <?php echo $this->lang->line('manage_transport_member'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
           <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'transport', 'vehicle')){ ?>
                    <a href="<?php echo site_url('transport/vehicle/index/'); ?>"><?php echo $this->lang->line('transport'); ?> <?php echo $this->lang->line('vehicle'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'transport', 'route')){ ?>
                    | <a href="<?php echo site_url('transport/route/index/'); ?>"> <?php echo $this->lang->line('transport_route'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'transport', 'member')){ ?>
                    | <a href="<?php echo site_url('transport/member/index/'); ?>"><?php echo $this->lang->line('transport'); ?> <?php echo $this->lang->line('member'); ?></a>                    
                <?php } ?>
            </div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('transport/member/index/'); ?>"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('member'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'transport', 'member')){ ?>
                            <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('transport/member/add/'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('non_member'); ?> <?php echo $this->lang->line('list'); ?></a> </li>                          
                        <?php } ?>
                            
                         <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_member_by_school(this.value);">
                                        <option value="<?php echo site_url('transport/member/add'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('transport/member/add/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                    <?php } ?>   
                                </select>
                            <?php } ?>  
                        </li>     
                            
                    </ul>
                    <br/>
                    
                    <div class="tab-content">                   
                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_non_member_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?> <input type="checkbox" name="checkAll" id="checkall" value='1' /></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('photo'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>

                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('father_name'); ?></th>

                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                        <th><?php echo $this->lang->line('select'); ?></th>                                            
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                  
                                </tbody>
                            </table>
                            <?php if($filter_school_id) {?>
                            <div class="col-md-4 col-xs-4" >
                                <select  class="form-control col-md-7 col-xs-12 alignleft route_select_box" data-userid="bulk" name="route_id" id="route_id_bulk"  >
                                    <option value="">--<?php echo $this->lang->line('select').' '.$this->lang->line('transport_route')?>--</option>
                                    <?php $hostels = get_hostel_by_school($filter_school_id); 
                                         if(isset($routes) && !empty($routes)){ 
                                            foreach($routes as $route){ ?>
                                                <option value="<?php echo $route->id ?>"><?php echo $route->title." ".get_vehicle_by_ids($route->vehicle_ids) ?></option>
                                                <?php   }
                                     } ?>
                            </select>
                            </div>
                            <div class="col-md-4 col-xs-4" >
                                <select  class="form-control col-md-7 col-xs-12" name="stop_id" id="stop_id_bulk">
                                    <option value="">--<?php echo  $this->lang->line('select').' '.$this->lang->line('bus_stop') ?>--</option>                                                    
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-4" >
                                <button type="button" onclick="bulk_add('<?php echo $filter_school_id ?>')" class="btn btn-sm btn-success">Add Bulk</button>
                            </div>
                            <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Super admin js START  -->
 <script type="text/javascript">
     
    $(document).on('change','.fn_school_id',function(){
              
        var school_id = $(this).val();        
        var user_id = $(this).attr('itemid');        
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('transport/member/get_route_by_school'); ?>",
            data   : { school_id:school_id, user_id:user_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               { 
                    $('#route_id_'+user_id).html(response);        
               }
            }
        });
    }); 

  </script>
<!-- Super admin js end -->


 <script type="text/javascript">
     
      $(document).ready(function(){
        $('#checkall').click(function(){
        if($(this).is(':checked')){
                $('.transport_non_member').prop('checked', true);
            }else{
                $('.transport_non_member').prop('checked', false);
            }
        })
     
   });
   $(document).on('click', '.fn_add_to_transport',function(e){
           
           var obj = $(e.target);  
           var user_id  = obj.attr('id');         
           var route_id  = $('#route_id_'+user_id).val();
           var stop_id  = $('#stop_id_'+user_id).val();
           var school_id  = $('#school_id_'+user_id).val();
          
           if(route_id == ''){
                toastr.error('<?php echo $this->lang->line('please_select_a_route'); ?>'); 
                return false;
           }
           if(stop_id == ''){
                toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('bus_stop'); ?>'); 
                return false;
           }
           
           $.ajax({       
             type   : "POST",
             url    : "<?php echo site_url('transport/member/add_to_transport'); ?>",
             data   : {school_id:school_id, user_id : user_id, route_id : route_id, stop_id:stop_id},               
             async  : false,
             success: function(response){ 
                 if(response){
                     toastr.success('<?php echo $this->lang->line('update_success'); ?>');
                     obj.parents('tr').remove();
                 }else{
                     toastr.error('<?php echo $this->lang->line('update_failed'); ?>'); 
                 }
             }
         }); 
                       
        });  
   function get_bus_stop_by_route(route_id, user_id){ 
   
       var school_id  = $('#school_id_'+user_id).val();
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('transport/member/get_bus_stop_by_route'); ?>",
            data   : { school_id:school_id, route_id : route_id },               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                  
                  $('#stop_id_'+user_id).html(response);
               }
            }
        });         
    } 
    function bulk_add(school_id)
       {
             var selectedID = [];
            var route_id  = $('#route_id_bulk').val();         
            var stop_id  = $('#stop_id_bulk').val();    
            if(!school_id)
            {
                toastr.error('No school selected'); 
            }
            $(".transport_non_member[type='checkbox']").each (function () {
                if($(this).is(":checked")){
                    selectedID.push($(this).val());
                }
            });
            var error = false;
            if(!selectedID.length )
            {
                toastr.error('<?php echo $this->lang->line('select_student_alert');?>'); 
                error = true;
            }
            if(!route_id || !stop_id )
            {
                toastr.error('Please select route and stop'); 
                error = true;
            }
            var confirm_bulk_pass = confirm('<?php echo $this->lang->line('bulk_transport_add_confirm');?>');
            if(selectedID.length && confirm_bulk_pass && !error)
            {
                $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('transport/member/add_transport_bulk'); ?>",
                data   : {school_id:school_id, user_ids : selectedID, route_id : route_id, stop_id : stop_id}, 
                dataType: "json",          
                async  : false,
                success: function(response){         
                    if(response.success)
                    {
                        toastr.success('<?php echo $this->lang->line('update_success'); ?>');
                        location.reload();
                    } 
                    else
                    {
                        toastr.error('Couldnt remove all selected members'); 
                    }                                       
                }
                });
            }
       }
    $(document).on('change','.route_select_box',function(e){
        var route_id = $(e.target).val();
        var user_id = $(e.target).data('userid');
        if(user_id == "bulk")
        {
            var school_id  = <?php echo $filter_school_id ?>
        }
        else
        {
            var school_id  = $('#school_id_'+user_id).val();
        }
       
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('transport/member/get_bus_stop_by_route'); ?>",
            data   : { school_id:school_id, route_id : route_id },               
            async  : false,
            success: function(response){                                                   
            if(response)
            {                  
                $('#stop_id_'+user_id).html(response);
            }
            }
        });       

    })

</script>
 <script type="text/javascript">
 	var sch_id='<?php print $filter_school_id; ?>';
    $(document).ready(function() {
      $('#datatable-responsive, .datatable-responsive').DataTable( {
          dom: 'Bfrtip',
          'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("/transport/member/get_non_members_list"); ?>',
		  'data': {'school_id': sch_id}
      },
          iDisplayLength: 15,
          buttons: [
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5',
              'pageLength'
          ],
          search: true,              
          responsive: true,
          "ordering": false
      });
    });
    
    function get_member_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    } 
</script>