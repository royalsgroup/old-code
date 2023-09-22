<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-hotel"></i><small> <?php echo $this->lang->line('manage_hostel_member'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'hostel', 'hostel')){ ?>
                    <a href="<?php echo site_url('hostel/index/'); ?>"><?php echo $this->lang->line('manage_hostel'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'hostel', 'room')){ ?>
                   | <a href="<?php echo site_url('hostel/room/index/'); ?>"><?php echo $this->lang->line('manage_room'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'hostel', 'member')){ ?>
                   | <a href="<?php echo site_url('hostel/member/index/'); ?>"><?php echo $this->lang->line('hostel'); ?> <?php echo $this->lang->line('member'); ?></a>                    
                <?php } ?>
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('hostel/member/index/'); ?>"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('member'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'hostel', 'member')){ ?>
                            <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('hostel/member/add/'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('non_member'); ?> <?php echo $this->lang->line('list'); ?></a> </li>                          
                        <?php } ?>
                            
                            
                         <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_member_by_school(this.value);">
                                        <option value="<?php echo site_url('hostel/member/add'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('hostel/member/add/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
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
                                        <th><?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('hostel'); ?> / <?php echo $this->lang->line('room_no'); ?> </th>                                            
                                        <th>Actions</th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    
                                </tbody>
                            </table>
                            <?php if($filter_school_id) {?>
                            <div class="col-md-4 col-xs-4" >
                                <select  class="form-control col-md-7 col-xs-12 alignleft hostel_select_box" name="hostel_id" data-userid="bulk"  id="hostel_id_bulk" >
                                    <option value="">--<?php echo $this->lang->line('select').' '.$this->lang->line('hostel')?>--</option>
                                    <?php $hostels = get_hostel_by_school($filter_school_id); 
                                        if(isset($hostels) && !empty($hostels)){
                                            foreach($hostels as $hostel){ ?>
                                                <option value="<?php echo $hostel->id ?>"><?php echo $hostel->name.' ['.$this->lang->line($hostel->type).']' ?></option>';
                                                <?php   }
                                     } ?>
                            </select>
                            </div>
                            <div class="col-md-4 col-xs-4" >
                                <select  class="form-control col-md-7 col-xs-12" name="room_id" id="room_id_bulk">
                                    <option value="">--<?php echo  $this->lang->line('select').' '.$this->lang->line('room_no') ?>--</option>                                                    
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

 <script type="text/javascript">
     
      $(document).ready(function(){
        $('#checkall').click(function(){
        if($(this).is(':checked')){
                $('.hostel_non_member').prop('checked', true);
            }else{
                $('.hostel_non_member').prop('checked', false);
            }
        })
    //     $('.fn_add_to_hostel').click(function(){
           
    //       var obj = $(this);  
    //       var user_id  = $(this).attr('id');         
    //       console.log(user_id);
    //       var school_id  = $('#school_id_'+user_id).val();         
    //       var hostel_id  = $('#hostel_id_'+user_id).val();         
    //       var room_id  = $('#room_id_'+user_id).val();         
    //       if(hostel_id == ''){
    //            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('hostel'); ?>'); 
    //            return false;
    //       }
    //       if(room_id == ''){
    //            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('room_no'); ?>'); 
    //            return false;
    //       }
          
    //       $.ajax({       
    //         type   : "POST",
    //         url    : "<?php echo site_url('hostel/member/add_to_hostel'); ?>",
    //         data   : {school_id:school_id, user_id : user_id, hostel_id : hostel_id, room_id : room_id},               
    //         async  : false,
    //         success: function(response){ 
    //             if(response){
    //                 toastr.success('<?php echo $this->lang->line('update_success'); ?>');
    //                 obj.parents('tr').remove();
    //             }else{
    //                 toastr.error('<?php echo $this->lang->line('update_failed'); ?>'); 
    //             }
    //         }
    //     }); 
                      
    //    });       
   });
   $(document).on('click','.fn_add_to_hostel',function(e){ 
        var obj = $(e.target);  
          var user_id  = $(e.target).attr('id');       
          
          var school_id  = $('#school_id_'+user_id).val();         
          var hostel_id  = $('#hostel_id_'+user_id).val();         
          var room_id  = $('#room_id_'+user_id).val();    
          if(hostel_id == ''){
               toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('hostel'); ?>'); 
               return false;
          }
          if(room_id == ''){
               toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('room_no'); ?>'); 
               return false;
          }
          
          $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('hostel/member/add_to_hostel'); ?>",
            data   : {school_id:school_id, user_id : user_id, hostel_id : hostel_id, room_id : room_id},               
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
       function bulk_add(school_id)
       {
             var selectedID = [];
            var hostel_id  = $('#hostel_id_bulk').val();         
            var room_id  = $('#room_id_bulk').val();    
            if(!school_id)
            {
                toastr.error('No school selected'); 
            }
            $(".hostel_non_member[type='checkbox']").each (function () {
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
            if(!hostel_id || !room_id )
            {
                toastr.error('Please select hostel and room'); 
                error = true;
            }
            var confirm_bulk_pass = confirm('<?php echo $this->lang->line('bulk_library_remove_confirm');?>');
            if(selectedID.length && confirm_bulk_pass && !error)
            {
                $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('hostel/member/add_to_hostel_bulk'); ?>",
                data   : {school_id:school_id, user_ids : selectedID, hostel_id : hostel_id, room_id : room_id}, 
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
   
   $(document).change('.hostel_select_box' ,function(e){   
           var hostel_id = $(e.target).val();
           var user_id = $(e.target).data('userid');
           $.ajax({       
               type   : "POST",
               url    : "<?php echo site_url('ajax/get_room_by_hostel'); ?>",
               data   : { hostel_id : hostel_id },               
               async  : false,
               success: function(response){                                                   
                  if(response)
                  {                  
                     $('#room_id_'+user_id).html(response);
                  }
               }
           });         
       } )
    
    function get_room_by_hostel(hostel_id, user_id){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_room_by_hostel'); ?>",
            data   : { hostel_id : hostel_id },               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                  
                  $('#room_id_'+user_id).html(response);
               }
            }
        });         
    } 
</script>
 <script type="text/javascript">
    $(document).ready(function() {
        var sch_id='<?php print $filter_school_id; ?>';

      $('#datatable-responsive, .datatable-responsive').DataTable( {
          dom: 'Bfrtip',
          iDisplayLength: 15,
          'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("hostel/member/get_non_members_list"); ?>',
		  'data': {'school_id': sch_id}
      },
          buttons: [
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5',
              'pageLength'
          ],
          search: true,              
          responsive: true,
          "ordering" : false,
      });
    });
    function get_member_by_school(url){          
        if(url){
          window.location.href = url; 
        }
    }
</script>