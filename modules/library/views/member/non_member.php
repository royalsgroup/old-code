<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-book"></i><small> <?php echo $this->lang->line('library'); ?> <?php echo $this->lang->line('member'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'library', 'book')){ ?>
                    <a href="<?php echo site_url('library/book/index/'); ?>"><?php echo $this->lang->line('manage_book'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'library', 'member')){ ?>
                   | <a href="<?php echo site_url('library/member/index/'); ?>"><?php echo $this->lang->line('library'); ?> <?php echo $this->lang->line('member'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'library', 'issue')){ ?>
                   | <a href="<?php echo site_url('library/issue/index'); ?>"><?php echo $this->lang->line('issue_and_return'); ?></a>                    
                <?php } ?>
               <?php if(has_permission(VIEW, 'library', 'ebook')){ ?>
                   | <a href="<?php echo site_url('library/ebook/index'); ?>"><?php echo $this->lang->line('e_book'); ?></a>                    
                <?php } ?>    
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('library/member/index/'); ?>"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('member'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'library', 'member')){ ?>
                            <li  class="<?php if(isset($non_list)){ echo 'active'; }?>"><a href="<?php echo site_url('library/member/add/'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('non_member'); ?> <?php echo $this->lang->line('list'); ?></a> </li>                          
                        <?php } ?>
                            
                        <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_non_member_by_school(this.value);">
                                        <option value="<?php echo site_url('library/member/add'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('library/member/add/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                    <?php } ?>   
                                </select>
                            <?php } ?>  
                        </li>     
                            
                    </ul>
                    <br/>
                    
                    <div class="tab-content">                   
                        <div  class="tab-pane fade in <?php if(isset($non_list)){ echo 'active'; }?>" id="tab_non_member_list" >
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
                                        <th><?php echo $this->lang->line('action');  if(has_permission(ADD, 'library', 'member')){?> <button class="btn btn-xs btn-success " type="button" id="bulk_add_button" >Bulk Add</button><?php } ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    
                                </tbody>
                            </table>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <script type="text/javascript">
        
     
</script>

 <script type="text/javascript">
 	$('#checkall').click(function(){
      if($(this).is(':checked')){
         $('.library_non_member').prop('checked', true);
      }else{
         $('.library_non_member').prop('checked', false);
      }
    })
  $('#bulk_add_button').on('click', function(){
        var selectedID = [];
        $(".library_non_member[type='checkbox']").each (function () {
            if($(this).is(":checked")){
                selectedID.push($(this).val());
            }
        });
        var error = false;
        if(!selectedID.length )
        {
            alert('<?php echo $this->lang->line('select_student_alert');?>')
            error = true;
        }
        else
        {
            var confirm_bulk_pass = confirm('<?php echo $this->lang->line('bulk_library_add_confirm');?>');
            if(selectedID.length && confirm_bulk_pass && !error)
            {
                $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('library/member/add_to_library_bulk'); ?>",
                data   : { user_ids : selectedID },  
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
                        toastr.error('Couldnt add all selected users'); 
                    }
                                                        
                }
                });
            }

        }
        
    });
        $(document).ready(function() {
            $(document).on('click','.fn_add_to_library',function(e){
                var obj = $(e.target);
           var user_id  = $(e.target).attr('id');         
           $.ajax({       
             type   : "POST",
             url    : "<?php echo site_url('library/member/add_to_library'); ?>",
             data   : { user_id : user_id},               
             async  : false,
             success: function(response){ 
                 if(response){
                     toastr.success('<?php echo $this->lang->line('update_success'); ?>');
                    obj.parents('tr').remove();
                 }else{
                     toastr.error('Error'); 
                 }
             }
         }); 
                       
        });    
            var sch_id='<?php print $filter_school_id; ?>';

          $('#datatable-responsive, .datatable-responsive').DataTable( {
              dom: 'Bfrtip',
              'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("library/member/get_non_members_list"); ?>',
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
              "ordering": false,        
              responsive: true
          });
        });
        
    function get_non_member_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    } 
     
</script>