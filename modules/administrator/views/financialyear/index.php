<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-calendar"></i><small> <?php echo $this->lang->line('manage_financial_year'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
				<?php $this->load->view('quicklinks/account'); ?>
			</div>            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_year_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('financial_year'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        
                        <?php if(has_permission(ADD, 'administrator', 'financialyear')){ ?>
                                <?php if(isset($edit)){ ?>
                                    <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('administrator/financialyear/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('financial_year'); ?></a> </li>                          
                                <?php }else{ ?>
                                    <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_year"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('financial_year'); ?></a> </li>                          
                                <?php } ?> 
                        <?php } ?> 
                            
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_year"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('financial_year'); ?></a> </li>                          
                        <?php } ?>   
                            
                            
                        
                            <li class="li-class-list">
                                 <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?> 
                                    <select  class="form-control col-md-7 col-xs-12" onchange="get_year_by_school(this.value);">
                                        <option value="<?php echo site_url('administrator/financialyear/index/'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                        <?php foreach($schools as $obj ){ ?>
                                            <option value="<?php echo site_url('administrator/financialyear/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                        <?php } ?>   
                                    </select>                                                                                               
                                <?php } ?>
                            </li>    
                        
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_year_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('financial_year'); ?></th>
                                        <th><?php echo $this->lang->line('is_running'); ?></th>
                                        <th width="25%"><?php echo $this->lang->line('note'); ?></th>
                                        <th>Previous Financial year</th>  
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($years) && !empty($years)){ ?>
                                        <?php foreach($years as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                                <td><?php echo $obj->school_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo ucfirst($obj->session_year); ?></td>
                                            <td><?php echo $obj->is_running ? $this->lang->line('yes') : $this->lang->line('no'); ?></td>
                                            <td><?php echo $obj->note; ?></td>
                                            <td><?php
                                            if($obj->previous_financial_year_id )
                                            {
                                                echo $financial_years[$obj->previous_financial_year_id];
                                            }
                                            ?></td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'administrator', 'financialyear')){ ?>
                                                    <a href="<?php echo site_url('administrator/financialyear/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'administrator', 'financialyear')){ ?>
                                                    <?php if(!$obj->is_running){ ?>
                                                    <a href="<?php echo site_url('administrator/financialyear/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if(has_permission(EDIT, 'administrator', 'financialyear')){ ?>    
                                                    <a href="<?php echo site_url('administrator/financialyear/activate/'.$obj->id.'/'.$obj->school_id); ?>"><button class="btn btn-success  btn-xs">  <?php echo ($obj->is_running) ? '<i class="fa fa-check"></i> '. $this->lang->line('active') : $this->lang->line('activate'); ?></button></a>     
                                                <?php } ?>
                                                <!-- <?php if(has_permission(EDIT, 'administrator', 'financialyear') &&  !$obj->is_running &&  !$obj->data_updated && isset($_GET['copy']) && $_GET['copy']= 1){ ?>    
                                                    <a href="<?php echo site_url('debugmode/update_ledger_balaces/'.$obj->id.'/'.$obj->school_id); ?>"><button class="btn btn-success  btn-xs">Copy Opening balance</button></a>     
                                                <?php } ?> -->
                                                <?php if(has_permission(EDIT, 'administrator', 'financialyear') &&  $obj->is_running &&  isset($_GET['fix_all']) && $_GET['fix_all']= 1){ ?>    
                                                    <a href="<?php echo site_url('debugmode/update_ledger_balaces'.'/'.$obj->previous_financial_year_id.'/'.$obj->school_id); ?>"><button class="btn btn-success  btn-xs">Copy Opening balance</button></a>     
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>                               
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_year">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('administrator/financialyear/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="session_year">&nbsp;</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="help-block"><?php echo form_error('session_year'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="session_start"><?php echo $this->lang->line('session_start'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text"  class="form-control col-md-7 col-xs-12"  name="session_start"  id="add_session_start" value="<?php echo isset($session_start) ? $session_start : ''; ?>"  placeholder="<?php echo $this->lang->line('session_start'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_start'); ?></div>
                                    </div>
                                </div>
                                                              
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="session_end"><?php echo $this->lang->line('session_end'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text"  class="form-control col-md-7 col-xs-12"  name="session_end"  id="add_session_end" value="<?php echo isset($session_end) ? $session_end : ''; ?>"  placeholder="<?php echo $this->lang->line('session_end'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_end'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($note) ?  $note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id">Previous Financial year 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="previous_financial_year_id"  id="add_discipline" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($years as $year_obj ){ ?>
                                            <option value="<?php echo $year_obj->id; ?>" <?php echo isset($post['previous_financial_year_id']) && $post['previous_financial_year_id'] == $year_obj->id ?  'selected="selected"' : ''; ?>><?php echo $year_obj->session_year; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('previous_financial_year_id'); ?></div>
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('administrator/financialyear'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_year">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('administrator/financialyear/edit/'.$year->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                               
                                <?php $this->load->view('layout/school_list_edit_form'); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="session_year">&nbsp;</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="help-block"><?php echo form_error('session_year'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="session_start"><?php echo $this->lang->line('session_start'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text"  class="form-control col-md-7 col-xs-12"  name="session_start"  id="edit_session_start" value="<?php echo isset($session_start) ? $session_start : ''; ?>"  placeholder="<?php echo $this->lang->line('session_start'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_start'); ?></div>
                                    </div>
                                </div>
                                                              
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="session_end"><?php echo $this->lang->line('session_end'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text"  class="form-control col-md-7 col-xs-12"  name="session_end"  id="edit_session_end" value="<?php echo isset($session_end) ? $session_end : ''; ?>"  placeholder="<?php echo $this->lang->line('session_end'); ?>" required="required" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('session_end'); ?></div>
                                    </div>
                                </div>
                                                              
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($year) ? $year->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id">Previous Financial year  
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="previous_financial_year_id"  id="add_discipline" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($years as $obj ){ 
                                                if($year->id ==$obj->id) continue;

                                                ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($previous_financial_year_id) && $previous_financial_year_id == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->session_year; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('previous_financial_year_id'); ?></div>
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($year) ? $year->id : $id; ?>" name="id" />
                                        <a href="<?php echo site_url('administrator/financialyear'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
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

<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 <script type="text/javascript">     
     
  $('#add_session_start').datepicker({});
  $('#edit_session_start').datepicker({});
  
  $('#add_session_end').datepicker({});
  $('#edit_session_end').datepicker({});
  
  </script>

<!-- datatable with buttons -->
 <script type="text/javascript">
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
       
       
       function get_year_by_school(url){          
            if(url){
                window.location.href = url; 
            }
       } 
       
</script>