<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-slideshare"></i><small> <?php echo $this->lang->line('online'). " ".$this->lang->line('exam'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>

                    <?php if($this->session->userdata('role_id') != STUDENT){           
                       if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>
                          <a href="<?php echo site_url('onlineexam/'); ?>"><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('exam'); ?></a>                         
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'onlineexam', 'question')){ ?>
                           <a href="<?php echo site_url('question/'); ?>"><?php echo $this->lang->line('questions'); ?></a>
                         <?php } } else{  
                          if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>
                            <a href="<?php echo site_url('user/onlineexam/'); ?>"><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('exam'); ?></a>                        
                          <?php } ?>                                
                    <?php }?>      
              </div>
           
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_class_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'onlineexam', 'onlineexam')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('onlineexam/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('online'). " ".$this->lang->line('exam'); ?></a> </li>                          
                             <?php }else{ ?>
                                 <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_class"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('exam'); ?></a> </li>                          
                             <?php } ?>
                           
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_class"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('exam'); ?></a> </li>                          
                        <?php } ?> 

                        <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_onlineexam_by_school(this.value);">
                                    <option value="<?php echo site_url('onlineexam/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('onlineexam/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 
                            
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_class_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('exam'); ?></th>
                                        <th><?php echo $this->lang->line('attempt'); ?></th>
                                        <th><?php echo $this->lang->line('exam') . " " . $this->lang->line('from') ?></th>
                                        <th><?php echo $this->lang->line('exam') . " " . $this->lang->line('to') ?></th>
                                        <th><?php echo $this->lang->line('duration') ?></th>

                                        <th class="text text-center"><?php echo $this->lang->line('exam') . " " . $this->lang->line('publish') ?></th>
                                        <th class="text text-center"><?php echo $this->lang->line('result') . " " . $this->lang->line('publish') ?></th>                          
                                        <th><?php echo $this->lang->line('action'); ?></th>  
                                    </tr>
                                </thead>
                                <tbody>                                      
                                    <?php $count = 1; if(isset($onlineexam) && !empty($onlineexam)){ ?>
                                        <?php foreach($onlineexam as $obj){ ?>                                       
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                                <td><?php echo $obj->school_name; ?></td>
                                            <?php } ?>
                                           <td class="mailbox-name"> <?php echo $obj->exam; ?></td>
                                            <td class="mailbox-name"> <?php echo $obj->attempt; ?></td>
                                            <td class="mailbox-name"> <?php echo date('d-m-Y',strtotime($obj->exam_from)); ?> </td>

                                            <td class="mailbox-name"> <?php echo date('d-m-Y',strtotime($obj->exam_to)); ?></td>

                                            <td class="mailbox-name"> <?php echo $obj->duration; ?></td>

                                      <td class="text text-center"><?php echo ($obj->is_active == 1) ? "<i class='fa fa-check-square-o'></i>" : "<i class='fa fa-exclamation-circle'></i>"; ?>
                                            <?php if ($obj->is_active == 1) {?>
                                                <span id=""><?php echo $this->lang->line('yes'); ?>
                                                </span>
                                            <?php }?>
                                      </td>
                                      <td class="text text-center">
                                <?php echo ($obj->publish_result == 1) ? "<i class='fa fa-check-square-o'></i><span>Yes</span>" : "<i class='fa fa-exclamation-circle'></i><span>No</span>"; ?></td>                                                                   

                                            <td class="text-right">
											<?php if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ 
     {
            if ((strtotime($obj->exam_to) >= strtotime(date('Y-m-d')))) {
                ?>
                                  <a href="<?php echo site_url('onlineexam/assign/'.$obj->id); ?>"
                                                   class="btn btn-warning btn-xs" data-toggle="tooltip" title="Assign / View">
                                                    <i class="fa fa-tag"></i>
                                                </a>
                                            <?php
}
        }

    }?>
											 <?php if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>
                                                <button type="button" onclick='get_class_by_school(<?php print $obj->school_id; ?>);' class="btn btn-warning btn-xs" data-recordid="<?php echo $obj->id; ?>" data-toggle="modal" data-target="#myQuestionModal" title="<?php echo $this->lang->line('add') . " " . $this->lang->line('question') ?>"><i class="fa fa-plus"></i></button>
                                                <?php
} ?>
                                              <?php if(has_permission(EDIT, 'onlineexam', 'onlineexam')){ ?>
                                                    <a href="<?php echo site_url('onlineexam/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'onlineexam', 'onlineexam')){ ?>
                                                    <a href="<?php echo site_url('onlineexam/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>



                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_class">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('onlineexam/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('exam') . " " . $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control col-md-7 col-xs-12" id="exam" name="exam" required='required'>										                                    
                                        <div class="help-block"><?php echo form_error('exam'); ?></div>
                                    </div>
                                </div>  
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('exam') . " " . $this->lang->line('from') ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control date col-md-7 col-xs-12" id="exam_from" name="exam_from" required='required' autocomplete="off">
										     
                                        <div class="help-block"><?php echo form_error('exam_from'); ?></div>
                                    </div>
                                </div> 	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('exam') . " " . $this->lang->line('to'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">										
										  <input type="text" class="form-control date col-md-7 col-xs-12" id="exam_to" name="exam_to" required='required' autocomplete="off">   
                                        <div class="help-block"><?php echo form_error('exam_to'); ?></div>
                                    </div>
                                </div> 
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('time') . " " . $this->lang->line('duration') ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control timepicker col-md-7 col-xs-12" id="duration" name="duration" autocomplete="off">										
                                        <div class="help-block"><?php echo form_error('duration'); ?></div>
                                    </div>
                                </div> 									
                               <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('attempt'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
<input type="number" min="1" class="form-control col-md-7 col-xs-12" id="attempt" name="attempt" value="1"  autocomplete="off">																			  
                                        <div class="help-block"><?php echo form_error('attempt'); ?></div>
                                    </div>
                                </div> 
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('passing') . " " . $this->lang->line('percentage') ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
									<input type="number" min="1" max="100" class="form-control col-md-7 col-xs-12" id="passing_percentage" name="passing_percentage" autocomplete="off">
																			
                                        <div class="help-block"><?php echo form_error('passing_percentage'); ?></div>
                                    </div>
                                </div> 	
								<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
								 <div class="checkbox">
                                        <label>
<input type="checkbox" class="is_active" name="is_active" value="1">
                                            <?php echo $this->lang->line('publish'); ?>
                                        </label>
                                    </div>
                                       <div class="checkbox">
                                        <label>
<input type="checkbox" class="publish_result" name="publish_result" value="1">
                                           <?php echo $this->lang->line('publish') . " " . $this->lang->line('result'); ?>
                                        </label>
                                    </div>
									</div>
                                    </div>
<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('description'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
									<textarea class="form-control col-md-7 col-xs-12" id="description" name="description" autocomplete="off"></textarea>                                        
                                        <div class="help-block"><?php echo form_error('description'); ?></div>
                                    </div>
                                </div>									
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('onlineexam'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>                                                              
                            </div>                           
                            
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_class">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('onlineexam/edit/'.$exam->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('exam') . " " . $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control col-md-7 col-xs-12" id="exam" name="exam" required='required' value="<?php echo isset($exam->exam) ?  $exam->exam : ''; ?>">										                                    
                                        <div class="help-block"><?php echo form_error('exam'); ?></div>
                                    </div>
                                </div>  
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('exam') . " " . $this->lang->line('from') ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control date col-md-7 col-xs-12" id="exam_from" name="exam_from" required='required' autocomplete="off" value="<?php echo isset($exam->exam_from) ?  date('m/d/Y',strtotime($exam->exam_from)) : ''; ?>">
										     
                                        <div class="help-block"><?php echo form_error('exam_from'); ?></div>
                                    </div>
                                </div> 	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('exam') . " " . $this->lang->line('to'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">										
										  <input type="text" class="form-control date col-md-7 col-xs-12" id="exam_to" name="exam_to" required='required' autocomplete="off" value="<?php echo isset($exam->exam_to) ?  date('m/d/Y',strtotime($exam->exam_to)) : ''; ?>">   
                                        <div class="help-block"><?php echo form_error('exam_to'); ?></div>
                                    </div>
                                </div> 
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('time') . " " . $this->lang->line('duration') ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control timepicker col-md-7 col-xs-12" id="duration" name="duration" autocomplete="off" value="<?php echo isset($exam->duration) ?  $exam->duration : ''; ?>">										
                                        <div class="help-block"><?php echo form_error('duration'); ?></div>
                                    </div>
                                </div> 									
                               <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('attempt'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
<input type="number" min="1" class="form-control col-md-7 col-xs-12" id="attempt" name="attempt" value="1"  autocomplete="off" value="<?php echo isset($exam->attempt) ?  $exam->attempt : ''; ?>">																			  
                                        <div class="help-block"><?php echo form_error('attempt'); ?></div>
                                    </div>
                                </div> 
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('passing') . " " . $this->lang->line('percentage') ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
									<input type="number" min="1" max="100" class="form-control col-md-7 col-xs-12" id="passing_percentage" name="passing_percentage" autocomplete="off" value="<?php echo isset($exam->passing_percentage) ?  $exam->passing_percentage : ''; ?>">
																			
                                        <div class="help-block"><?php echo form_error('passing_percentage'); ?></div>
                                    </div>
                                </div> 	
								<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                    </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
								 <div class="checkbox">
                                        <label>
<input type="checkbox" class="is_active" name="is_active" value="1" <?php echo ($exam->is_active==1) ?  "checked='checked'" : ''; ?>>
                                            <?php echo $this->lang->line('publish'); ?>
                                        </label>
                                    </div>
                                       <div class="checkbox">
                                        <label>
											<input type="checkbox" class="publish_result" name="publish_result" value="1" <?php echo ($exam->publish_result==1) ?  "checked='checked'" : ''; ?>>
                                           <?php echo $this->lang->line('publish') . " " . $this->lang->line('result'); ?>
                                        </label>
                                    </div>
									</div>
                                    </div>
<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('description'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
									<textarea class="form-control col-md-7 col-xs-12" id="description" name="description"  autocomplete="off"><?php echo isset($exam->description) ?  $exam->description : ''; ?></textarea>                                        
                                        <div class="help-block"><?php echo form_error('description'); ?></div>
                                    </div>
                                </div>
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" name="id" id="id" value="<?php echo $exam->id; ?>" />
                                        <a href="<?php echo site_url('onlineexam'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
<div id="myQuestionModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('select') . " " . $this->lang->line('questions') ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="modal_exam_id" value="0" id="modal_exam_id">
                <div class="row">
                    <div class="col-md-5 col-sm-5">
                        <div class="form-group">
                            <input type="hidden" value="" id="school_id">
                            <label><?php echo $this->lang->line('class') ?></label>
                            <select class="form-control" name="class_id" id="class_id" ">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                            
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('subject') ?></label>
                            <select class="form-control" name="subject_id" id="subject_id" >
                                <option value=""><?php echo $this->lang->line('select') ?></option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <label style="display: block; visibility:hidden;">Search</label>
                        <button type="button" class="btn btn-info btn-sm post_search_submit"><?php echo $this->lang->line('search'); ?></button>
                    </div>

                </div><!-- ./row -->
                <div class="search_box_result" style="max-height: 480px;
                     overflow-x: hidden;overflow-y: scroll;">

                </div>
                <div class="search_box_pagination">

                </div>

            </div>

        </div>

    </div>
</div>
<style>
.search_box_pagination .pagination li a{
	float:none;
}
</style>
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
   <!-- bootstrap-datetimepicker -->
   <script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/datepicker/css/bootstrap-datetimepicker.css">
 <script src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datetimepicker.js"></script>
 <script type="text/javascript">
    $(document).ready(function () {
		 var date_format = '<?php echo $result    = strtr('m/d/Y', ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
       var date_format_js = '<?php echo $result = strtr('m/d/Y', ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';        
		$('.date').datepicker({
            format: date_format,

             autoclose: true,
                language: '<?php echo $language_name ?>'
        });

        $(function () {
             var dateNow = new Date();
            $('.timepicker').datetimepicker({
                format: 'HH:mm:ss',

             //defaultDate:moment(dateNow).hours(0).minutes(0).seconds(0).milliseconds(0)
            });
        });
	});
	</script>
<!-- Super admin js START  -->
 <script type="text/javascript">
     
    $("document").ready(function() {
         <?php if(isset($exam) && !empty($exam)){ ?>
            $("#edit_school_id").trigger('change');
         <?php } ?>
    });
         

    
  </script>
  <!-- Super admin js end -->

<!-- datatable with buttons -->
 <script type="text/javascript">
        $(document).ready(function() {
            
          $('#datatable-responsive').DataTable({
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

        $('#myQuestionModal').on('show.bs.modal', function (e) {

            //get data-id attribute of the clicked element
            var exam_id = $(e.relatedTarget).data('recordid');
            $('#modal_exam_id').val(exam_id);

            //populate the textbox
            getQuestionByExam(1, exam_id);
        });
      $('#myQuestionModal').on('hidden.bs.modal', function (e) {

            $(this).find("input,textarea,select").val('');
                $('.search_box_result').html("");
                $('.search_box_pagination').html("");

        });          
        });
        
    $("#add").validate();     
    $("#edit").validate(); 
    
    function get_onlineexam_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    } 
	function getQuestionByExam(page, exam_id) {
        var search = $("#subject_id").val();
        $.ajax({
            type: "POST",
			url    : "<?php echo site_url('onlineexam/searchQuestionByExamID'); ?>",           
            data: {'page': page, 'exam_id': exam_id, 'search': search}, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            async  : false,
            beforeSend: function () {
                // $("[class$='_error']").html("");
                // submit_button.button('loading');
            },
            success: function (data)
            {

                $('.search_box_result').html(data.content);
                $('.search_box_pagination').html(data.navigation);

            },
            error: function (xhr) { // if error occured
                // submit_button.button('reset');
                alert("Error occured.please try again");

            },
            complete: function () {
                // submit_button.button('reset');
            }
        });

    }
	function get_class_by_school(school_id){
	$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id:school_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                       
                $('#school_id').val(school_id);
                       $('#class_id').html(response);                   
               }
            }
        });       
}
$(document).on('change','#class_id',function(e){
    var obj = e.target;
    var class_id = obj.value;
    var school_id = $('#school_id').val();
    $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : { school_id:school_id,class_id:class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                       
                       $('#subject_id').html(response);                   
               }
            }
        });      
})

$(document).on('keyup', '#search_box', function (e) {

        if (e.keyCode == 13) {
            var _exam_id = $('#modal_exam_id').val();
            getQuestionByExam(1, _exam_id);
        }
    });


    /* Pagination Clicks   */
    $(document).on('click', '.search_box_pagination li.activee', function (e) {
        var _exam_id = $('#modal_exam_id').val();
        var page = $(this).attr('p');

        getQuestionByExam(page, _exam_id);
    });

    $(document).on('click', '.post_search_submit', function (e) {

        var _exam_id = $('#modal_exam_id').val();
        getQuestionByExam(1, _exam_id);
    });




    $(document).on('change', '.question_chk', function () {
        var _exam_id = $('#modal_exam_id').val();

        updateCheckbox($(this).val(), _exam_id);

    });

    function updateCheckbox(question_id, exam_id) {
        $.ajax({
            type: 'POST',
			url    : "<?php echo site_url('onlineexam/questionAdd'); ?>",
            //url: base_url + 'onlineexam/questionAdd',
            dataType: 'JSON',
            async  : false,
            data: {'question_id': question_id, 'onlineexam_id': exam_id},
            beforeSend: function () {

            },
            success: function (data) {
                if (data.status) {
                    successMsg(data.message);
                }
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");

            },
            complete: function () {

            },

        });
    }
	 function successMsg(msg) {
     toastr.success(msg);
 }
    
 function errorMsg(msg) {
     toastr.error(msg);
 }
</script>