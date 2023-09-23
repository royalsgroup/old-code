<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-tty"></i><small> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('postal'); ?> <?php echo $this->lang->line('receive'); ?></small></h3>
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
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_receive_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('receive'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'frontoffice', 'receive')){ ?>
                             <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('frontoffice/receive/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('receive'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_receive"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?>  <?php echo $this->lang->line('receive'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_receive"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('receive'); ?></a> </li>                          
                        <?php } ?>
                            
                        <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_receive_by_school(this.value);">
                                        <option value="<?php echo site_url('frontoffice/receive/index/'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('frontoffice/receive/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                    <?php } ?>   
                                </select>
                            <?php } ?>  
                        </li>     
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_receive_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th>Custom ID</th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('to'); ?> <?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('reference'); ?></th>
                                        <th><?php echo $this->lang->line('from'); ?> <?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('receive'); ?> <?php echo $this->lang->line('date'); ?></th>
                                        <th>Note</th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php  $count = 1; if(isset($receives) && !empty($receives)){ ?>
                                        <?php foreach($receives as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo $obj->custom_id; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                                <td><?php echo $obj->school_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo $obj->to_title; ?></td>
                                            <td><?php echo $obj->reference; ?></td>
                                            <td><?php echo $obj->from_title; ?></td>
                                            <td><?php echo date($this->global_setting->date_format, strtotime($obj->receive_date)); ?></td>
                                            <td><?php echo $obj->note; ?></td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'frontoffice', 'receive')){ ?>
                                                    <a href="<?php echo site_url('frontoffice/receive/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(VIEW, 'frontoffice', 'receive')){ ?>
                                                    <a  onclick="get_receive_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-receive-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                    <?php if($obj->attachment){ ?>
                                                        <a href="<?php echo UPLOAD_PATH; ?>/postal/<?php echo $obj->attachment; ?>"  target="_blank" class="btn btn-success btn-xs"><i class="fa fa-download"></i> <?php echo $this->lang->line('download'); ?></a> <br/><br/>
                                                    <?php } ?>
                                                <?php } ?>    
                                                <?php if(has_permission(DELETE, 'frontoffice', 'receive')){ ?>
                                                    <a href="<?php echo site_url('frontoffice/receive/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_receive">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('frontoffice/receive/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                               
                                <?php $this->load->view('layout/school_list_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_title">Custom ID <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php 
                                            $numberD = explode('/', $custom_id);
                                        ?>
                                        <span style="float: left;margin-top: 9px;">
                                            <?php echo $numberD['0'];?>/
                                            <input type="hidden" name="frontnumber" value="<?php echo $numberD['0'];?>">
                                        </span>
                                        <input  class="form-control col-md-7 col-xs-12"  name="custom_id_add"  id="custom_id_add" value="<?php echo $numberD['1'];?>" placeholder="Custom ID" required="required" type="text" autocomplete="off" style="float: left;width: 80%;">
                                        <div class="help-block"><?php echo form_error('custom_id'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_title"><?php echo $this->lang->line('to'); ?> <?php echo $this->lang->line('title'); ?><span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="to_title"  id="to_title" value="<?php echo isset($to_title) ?  $to_title : ''; ?>" placeholder="<?php echo $this->lang->line('to'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('to_title'); ?></div>
                                    </div>
                                </div>                                  
                             
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reference"><?php echo $this->lang->line('reference'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="reference"  id="reference" value="<?php echo isset($reference) ?  $reference : ''; ?>" placeholder="<?php echo $this->lang->line('reference'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('reference'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address"><?php echo $this->lang->line('address'); ?><span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="address"  id="address" value="<?php echo isset($address) ?  $address : ''; ?>" placeholder="<?php echo $this->lang->line('address'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('address'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_title"><?php echo $this->lang->line('from'); ?> <?php echo $this->lang->line('title'); ?><span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="from_title"  id="from_title" value="<?php echo isset($from_title) ?  $from_title : ''; ?>" placeholder="<?php echo $this->lang->line('from'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('from_title'); ?></div>
                                    </div>
                                </div>                               
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="receive_date"><?php echo $this->lang->line('receive'); ?> <?php echo $this->lang->line('date'); ?><span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="receive_date"  id="add_receive_date" value="<?php echo isset($receive_date) ?  date('d-m-Y', strtotime($receive_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('receive'); ?> <?php echo $this->lang->line('date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('receive_date'); ?></div>
                                    </div>
                                </div>  
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"> <?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note"  placeholder="<?php echo $this->lang->line('note'); ?>"> <?php echo isset($note) ?  $note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('postal'); ?> <?php echo $this->lang->line('attachment'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="attachment"  id="attachment" type="file" >
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('select_valid_file_format'); ?></div>
                                        <div class="help-block"><?php echo form_error('attachment'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a  href="<?php echo site_url('frontoffice/receive/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" purpose="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_receive">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('frontoffice/receive/edit/'.$receive->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_title">Custom ID <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php 
                                            $numberD = explode('/', $receive->custom_id);
                                        ?>
                                        <span style="float: left;margin-top: 9px;">
                                            <?php echo $numberD['0'];?>/
                                            <input type="hidden" name="frontnumberedit" value="<?php echo $numberD['0'];?>">
                                        </span>

                                        <input  class="form-control col-md-7 col-xs-12"  name="custom_id_edit"  id="custom_id_edit" value="<?php echo $numberD['1'] ?>" placeholder="Custom ID" required="required" type="text" autocomplete="off" style="float: left;width: 80%;">
                                        <div class="help-block"><?php echo form_error('custom_id'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_title"><?php echo $this->lang->line('to'); ?> <?php echo $this->lang->line('title'); ?><span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="to_title"  id="to_title" value="<?php echo isset($receive) ? $receive->to_title : ''; ?>" placeholder="<?php echo $this->lang->line('to'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('to_title'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reference"><?php echo $this->lang->line('reference'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="reference"  id="reference" value="<?php echo isset($receive) ? $receive->reference : ''; ?>" placeholder="<?php echo $this->lang->line('reference'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('reference'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address"><?php echo $this->lang->line('address'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="address"  id="address" value="<?php echo isset($receive) ? $receive->address : ''; ?>" placeholder="<?php echo $this->lang->line('address'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('address'); ?></div>
                                    </div>
                                </div>  
                                
                                 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_title"><?php echo $this->lang->line('from'); ?> <?php echo $this->lang->line('title'); ?><span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="from_title"  id="from_title" value="<?php echo isset($receive) ? $receive->from_title : ''; ?>" placeholder="<?php echo $this->lang->line('from'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('from_title'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="receive_date"><?php echo $this->lang->line('receive'); ?> <?php echo $this->lang->line('date'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="receive_date"  id="edit_receive_date" value="<?php echo isset($receive) ?  date('d-m-Y', strtotime($receive->receive_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('receive'); ?> <?php echo $this->lang->line('date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('receive_date'); ?></div>
                                    </div>
                                </div>  
                                
                                                               
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"> <?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note"  placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($receive) ? $receive->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>       
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('attachment'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" name="prev_attachment" id="prev_attachment" value="<?php echo $receive->attachment; ?>" />
                                        <?php if($receive->attachment){ ?>
                                            <a target="_blank" href="<?php echo UPLOAD_PATH; ?>/postal/<?php echo $receive->attachment; ?>"><?php echo $receive->attachment; ?></a> <br/><br/>
                                        <?php } ?>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="attachment"  id="attachment" type="file">
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('select_valid_file_format'); ?></div>
                                        <div class="help-block"><?php echo form_error('attachment'); ?></div>
                                    </div>
                                </div>
                                
                                                            
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($receive) ? $receive->id : ''; ?>" name="id" />
                                        <a href="<?php echo site_url('frontoffice/receive/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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




<div class="modal fade bs-receive-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('postal'); ?> <?php echo $this->lang->line('receive'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_receive_data">            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_receive_modal(receive_id){
         
        $('.fn_receive_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('frontoffice/receive/get_single_receive'); ?>",
          data   : {receive_id : receive_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_receive_data').html(response);
             }
          }
       });
    }
</script>



<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<script type="text/javascript">
     
  $('#add_receive_date').datepicker();
  $('#edit_receive_date').datepicker();
  
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
    function get_receive_by_school(url){          
        if(url){
            window.location.href = url; 
        }
     }  
</script>