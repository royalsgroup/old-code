<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-tty"></i><small> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'frontoffice', 'purpose')){ ?>   
                   <a href="<?php echo site_url('frontoffice/purpose/index'); ?>"><?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'frontoffice', 'visitor')){ ?>   
                   | <a href="<?php echo site_url('frontoffice/visitor/index'); ?>"><?php echo $this->lang->line('visitor_info'); ?></a>                         
                <?php } ?>                                 
                <?php if(has_permission(VIEW, 'frontoffice', 'calllog')){ ?>   
                   | <a href="<?php echo site_url('frontoffice/calllog/index'); ?>"><?php echo $this->lang->line('call_log'); ?></a>                         
                <?php } ?>                                 
                <?php if(has_permission(VIEW, 'frontoffice', 'dispatch')){ ?>   
                    | <a href="<?php echo site_url('frontoffice/dispatch/index'); ?>"><?php echo $this->lang->line('postal_dispatch'); ?></a>                      
                <?php } ?>                                 
                <?php if(has_permission(VIEW, 'frontoffice', 'receive')){ ?>   
                    | <a href="<?php echo site_url('frontoffice/receive/index'); ?>"><?php echo $this->lang->line('postal_receive'); ?></a>                      
                <?php } ?>            
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_purpose_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('purpose'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'frontoffice', 'purpose')){ ?>
                             <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('frontoffice/purpose/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_purpose"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_purpose"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?></a> </li>                          
                        <?php } ?>
                            
                        <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_purpose_by_school(this.value);">
                                        <option value="<?php echo site_url('frontoffice/purpose/index/'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('frontoffice/purpose/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                    <?php } ?>   
                                </select>
                            <?php } ?>  
                        </li>     
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_purpose_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php  $count = 1; if(isset($purposes) && !empty($purposes)){ ?>
                                        <?php foreach($purposes as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                                <td><?php echo $obj->school_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo $obj->purpose; ?></td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'frontoffice', 'purpose')){ ?>
                                                    <a href="<?php echo site_url('frontoffice/purpose/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'frontoffice', 'purpose')){ ?>
                                                    <a href="<?php echo site_url('frontoffice/purpose/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_purpose">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('frontoffice/purpose/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                               
                                <?php $this->load->view('layout/school_list_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="purpose"><?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="purpose"  id="purpose" value="<?php echo isset($post['purpose']) ?  $post['purpose'] : ''; ?>" placeholder="<?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('purpose'); ?></div>
                                    </div>
                                </div>  
								<div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_from"><?php echo $this->lang->line('from'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="from"  id="add_from" value="<?php echo isset($post['from_date']) ?  $post['from_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> <?php echo $this->lang->line('from'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('from'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_to"> <?php echo $this->lang->line('to'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="to"  id="add_to" value="<?php echo isset($post['to_date']) ?  $post['to_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> "  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('leave_to'); ?></div>
                                    </div>
                                </div>								
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a  href="<?php echo site_url('frontoffice/purpose/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" purpose="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_purpose">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('frontoffice/purpose/edit/'.$purpose->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="purpose"><?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        
                                        <input  class="form-control col-md-7 col-xs-12"  name="purpose" value="<?php echo isset($purpose) ? $purpose->purpose : ''; ?>"  placeholder="<?php echo $this->lang->line('visitor'); ?> <?php echo $this->lang->line('purpose'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('purpose'); ?></div>
                                    </div>
                                </div> 
								<div class="item form-group"> 
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_from"><?php echo $this->lang->line('from'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="from"  id="edit_from" value="<?php echo isset($purpose->from_date) ?  date('d-m-Y', strtotime($purpose->from_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> "  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('from'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group"> 
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_to"><?php echo $this->lang->line('to'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  class="form-control col-md-7 col-xs-12"  name="to"  id="edit_to" value="<?php echo isset($purpose->to_date) ?  date('d-m-Y', strtotime($purpose->to_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('to'); ?></div>
                                        </div>
                                    </div>
								
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($purpose) ? $purpose->id : ''; ?>" name="id" />
                                        <a href="<?php echo site_url('frontoffice/purpose/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" purpose="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<!-- datatable with buttons -->
 <script type="text/javascript">
  $('#add_from').datepicker();  
    $('#edit_from').datepicker(); 
	$('#add_to').datepicker();  
    $('#edit_to').datepicker(); 
        $(document).ready(function() {
          $('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
              iDisplayLength: 15,
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength'
              ],
              search: true,              
              responsive: true
          });
        });
        
    $("#add").validate();     
    $("#edit").validate(); 
    function get_purpose_by_school(url){          
        if(url){
            window.location.href = url; 
        }
     }  
</script>