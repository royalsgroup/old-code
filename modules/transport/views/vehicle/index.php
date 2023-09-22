<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-bus"></i><small> <?php echo $this->lang->line('manage_vehicle'); ?></small></h3>
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
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_vehicle_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'transport', 'vehicle')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('transport/vehicle/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('vehicle'); ?></a> </li>                          
                             <?php }else{ ?>
                                 <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_vehicle"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('vehicle'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_vehicle"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('vehicle'); ?></a> </li>                          
                        <?php } ?> 
                            
                         <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_vehicle_by_school(this.value);">
                                        <option value="<?php echo site_url('transport/vehicle/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('transport/vehicle/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                    <?php } ?>   
                                </select>
                            <?php } ?>  
                        </li>   
                            
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_vehicle_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('number'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle_model'); ?></th>
                                        <th><?php echo $this->lang->line('driver'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle_license'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle_contact'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($vehicles) && !empty($vehicles)){ ?>
                                        <?php foreach($vehicles as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                                <td><?php echo $obj->school_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo $obj->number; ?></td>
                                            <td><?php echo $obj->model; ?></td>
                                            <td><?php echo $obj->driver; ?></td>
                                            <td><?php echo $obj->license; ?></td>
                                            <td><?php echo $obj->contact; ?></td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'transport', 'vehicle')){ ?>
                                                    <a href="<?php echo site_url('transport/vehicle/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(VIEW, 'transport', 'vehicle')){ ?>
                                                    <a  onclick="get_vehicle_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-vehicle-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                <?php } ?>    
                                                <?php if(has_permission(DELETE, 'transport', 'vehicle')){ ?>
                                                    <a href="<?php echo site_url('transport/vehicle/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_vehicle">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('transport/vehicle/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number"><?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('number'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="number"  id="number" value="<?php echo isset($post['number']) ?  $post['number'] : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('number'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('number'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="model"><?php echo $this->lang->line('vehicle_model'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="model"  id="model" value="<?php echo isset($post['model']) ?  $post['model'] : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle_model'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('model'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="driver"><?php echo $this->lang->line('driver'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="driver"  id="driver" value="<?php echo isset($post['driver']) ?  $post['driver'] : ''; ?>" placeholder="<?php echo $this->lang->line('driver'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('driver'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="license"><?php echo $this->lang->line('vehicle_license'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="license"  id="driver" value="<?php echo isset($post['license']) ?  $post['license'] : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle_license'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('license'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact"><?php echo $this->lang->line('vehicle_contact'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="contact"  id="contact" value="<?php echo isset($post['contact']) ?  $post['contact'] : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle_contact'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('contact'); ?></div>
                                    </div>
                                </div>
                                 <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_from"><?php echo $this->lang->line('insurance'); ?> <?php echo $this->lang->line('from'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="insurance_from"  id="add_insurance_from" value="<?php echo isset($post['insurance_from']) ?  $post['insurance_from'] : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> <?php echo $this->lang->line('from'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('insurance_from'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_to"> <?php echo $this->lang->line('insurance'); ?> <?php echo $this->lang->line('to'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="insurance_to"  id="add_insurance_to" value="<?php echo isset($post['insurance_to']) ?  $post['insurance_to'] : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> "  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('insurance_to'); ?></div>
                                    </div>
                                </div>  
								<div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_from"><?php echo $this->lang->line('pollution'); ?> <?php echo $this->lang->line('from'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="pollution_from"  id="add_pollution_from" value="<?php echo isset($post['pollution_from']) ?  $post['pollution_from'] : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> <?php echo $this->lang->line('from'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('pollution_from'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_to"><?php echo $this->lang->line('pollution'); ?> <?php echo $this->lang->line('to'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="insurance_to"  id="add_pollution_to" value="<?php echo isset($post['pollution_to']) ?  $post['pollution_to'] : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> "  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('pollution_to'); ?></div>
                                    </div>
                                </div>  								
                                 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="license"><?php echo $this->lang->line('seat_capacity'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="seat_capacity"  id="add_seat_capacity" value="<?php echo isset($post['seat_capacity']) ?  $post['seat_capacity'] : ''; ?>" placeholder="<?php echo $this->lang->line('seat_capacity'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('seat_capacity'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                               
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('transport/vehicle'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_vehicle">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('transport/vehicle/edit/'.$vehicle->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number"><?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('number'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="number"  id="number" value="<?php echo isset($vehicle->number) ?  $vehicle->number : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('number'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('number'); ?></div>
                                    </div>
                                </div>                          
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="model"><?php echo $this->lang->line('vehicle_model'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="model"  id="model" value="<?php echo isset($vehicle->model) ?  $vehicle->model : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle_model'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('model'); ?></div>
                                    </div>
                                </div>                          
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="driver"><?php echo $this->lang->line('driver'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="driver"  id="driver" value="<?php echo isset($vehicle->driver) ?  $vehicle->driver : ''; ?>" placeholder="<?php echo $this->lang->line('driver'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('driver'); ?></div>
                                    </div>
                                </div>                          
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="license"><?php echo $this->lang->line('vehicle_license'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="license"  id="license" value="<?php echo isset($vehicle->license) ?  $vehicle->license : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle_license'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('license'); ?></div>
                                    </div>
                                </div>                          
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact"><?php echo $this->lang->line('vehicle_contact'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="contact"  id="contact" value="<?php echo isset($vehicle->contact) ?  $vehicle->contact : ''; ?>" placeholder="<?php echo $this->lang->line('vehicle_contact'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('contact'); ?></div>
                                    </div>
                                </div>                          
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_from"><?php echo $this->lang->line('insurance'); ?> <?php echo $this->lang->line('from'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="insurance_from"  id="edit_insurance_from" value="<?php echo isset($vehicle->insurance_from) ?  date('d-m-Y', strtotime($vehicle->insurance_from)) : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> <?php echo $this->lang->line('from'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('insurance_from'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_to"> <?php echo $this->lang->line('insurance'); ?> <?php echo $this->lang->line('to'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="insurance_to"  id="edit_insurance_to" value="<?php echo isset($vehicle->insurance_to) ?  date('d-m-Y', strtotime($vehicle->insurance_to)) : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> "  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('insurance_to'); ?></div>
                                    </div>
                                </div>  
								<div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_from"><?php echo $this->lang->line('pollution'); ?> <?php echo $this->lang->line('from'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="pollution_from"  id="edit_pollution_from" value="<?php echo isset($vehicle->pollution_from) ?  date('d-m-Y', strtotime($vehicle->pollution_from)) : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> <?php echo $this->lang->line('from'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('pollution_from'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_to"><?php echo $this->lang->line('pollution'); ?> <?php echo $this->lang->line('to'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="pollution_to"  id="edit_pollution_to" value="<?php echo isset($vehicle->pollution_to) ?  date('d-m-Y', strtotime($vehicle->pollution_to)) : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?> "  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('pollution_to'); ?></div>
                                    </div>
                                </div>  								
                                 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="license"><?php echo $this->lang->line('seat_capacity'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="seat_capacity"  id="edit_seat_capacity" value="<?php echo isset($vehicle->seat_capacity) ?  $vehicle->seat_capacity : ''; ?>" placeholder="<?php echo $this->lang->line('seat_capacity'); ?>" type="text">
                                        <div class="help-block"><?php echo form_error('seat_capacity'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($vehicle->note) ?  $vehicle->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($vehicle) ? $vehicle->id : $id; ?>" name="id" />
                                        <a href="<?php echo site_url('transport/vehicle'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


<div class="modal fade bs-vehicle-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_vehicle_data">            
        </div>       
      </div>
    </div>
</div>
<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<script type="text/javascript">
      $('#add_insurance_from').datepicker();  
    $('#edit_insurance_from').datepicker(); 
	$('#add_insurance_to').datepicker();  
    $('#edit_insurance_to').datepicker();    
$('#add_pollution_from').datepicker();  
    $('#edit_pollution_from').datepicker(); 
	$('#add_pollution_to').datepicker();  
    $('#edit_pollution_to').datepicker();    	
    function get_vehicle_modal(vehicle_id){
         
        $('.fn_vehicle_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('transport/vehicle/get_single_vehicle'); ?>",
          data   : {vehicle_id : vehicle_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_vehicle_data').html(response);
             }
          }
       });
    }
</script>



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
    
    function get_vehicle_by_school(url){          
        if(url){
            window.location.href = url; 
        }
     }  
    
</script>