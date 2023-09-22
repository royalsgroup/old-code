<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage')." ".$this->lang->line('discipline'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>     

            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
               
                    <?php if(has_permission(VIEW, 'academic', 'discipline')){ ?>   
                     <a href="<?php echo site_url('disciplines'); ?>"> <?php echo $this->lang->line('discipline'); ?></a>
                    <?php } ?> 

                    <?php if(has_permission(VIEW, 'academic', 'classes')){ ?>
                    | <a href="<?php echo site_url('academic/classes/index'); ?>"> <?php echo $this->lang->line('class'); ?></a>
                    <?php } ?>

                    <?php if(has_permission(VIEW, 'academic', 'liveclass')){ ?>
                     | <a  href="<?php echo site_url('academic/liveclass/index'); ?>"><?php echo $this->lang->line('live_class'); ?></a> 
                    <?php } ?>

                    <?php if(has_permission(VIEW, 'teacher', 'lecture')){ ?>
                    | <a  href="<?php echo site_url('teacher/lecture/index/'); ?>"><?php echo $this->lang->line('class_lecture'); ?></a> 
                    <?php } ?>   
                    <?php if(has_permission(VIEW, 'academic', 'section')){ ?>
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }else{ ?>                         
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php } ?> 
                    <?php } ?>   
                    <?php if(has_permission(VIEW, 'academic', 'subject')){ ?>                            
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }else{ ?>      
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php } ?>
                    <?php } ?>
                    <?php if(has_permission(VIEW, 'academic', 'syllabus')){ ?>                        
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"><?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }else{ ?>      
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>

                        <?php } ?>
                    <?php } ?>     
                    <?php if(has_permission(VIEW, 'academic', 'material')){ ?>                        
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>   
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }else{ ?>      
                          | <a href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?> </a>        
                        <?php } ?>
                    <?php } ?>
                    <?php if(has_permission(VIEW, 'academic', 'routine')){ ?>
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }else{ ?>    
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php } ?>
                        <?php } ?>
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'academic', 'discipline')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_discipline_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('discipline'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>


                        <?php if(has_permission(VIEW, 'academic', 'discipline')){ ?>
                        <li class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_discipline_add"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('discipline'); ?> <?php echo $this->lang->line('add'); ?></a> </li>
                       <?php } ?>
                       <?php  if(isset($edit)){ ?>
                        <li class="<?php if(isset($edit)){ echo 'active'; }?>"><a href="#tab_discipline_edit"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('discipline'); ?> <?php echo $this->lang->line('edit'); ?></a> </li>
                       <?php } ?>

                                              
                  <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_discipline_by_school(this.value);">
                                    <option value="<?php echo site_url('disciplines/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('disciplines/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 						
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_discipline_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                    										<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){ ?>
                    										<th><?php echo $this->lang->line('school'); ?></th>
                    										<?php } ?>

                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th> Discipline ID</th>

                  										  <th><?php echo $this->lang->line('action'); ?></th>                                         
                                    </tr>
                                 </thead>
                                       <tbody>   
                                              <?php $count = 1; if(isset($disciplines) && !empty($disciplines)){ ?>
                                              <?php foreach($disciplines as $obj){
                                                          $discipline_id = "DIS/". str_pad(($obj->id +1), 8, '0', STR_PAD_LEFT);

                                                ?>
                                                <tr>
                                                  <td><?php echo $count++; ?></td>
                            											<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == DISTRICT_ADMIN){ ?>
                            											<td><?php echo $obj->school_name; ?></td>
                            											<?php } ?>
                                                                        <td><?php echo $obj->name; ?></td>
                                                                        <td><?php echo $discipline_id; ?></td>
                                                  <td>
												  <?php if($obj->school_id == 0){
													if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
												  <a href="<?php echo site_url('disciplines/delete/'.$obj->id); ?>" onclick="javascript: return confirm('Are you sure you want to delete this');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                  <a href="<?php echo site_url('disciplines/edit/'.$obj->id); ?>" onclick="javascript: return confirm('Are you sure you want to edit this');" class="btn btn-info btn-xs "><i class="fa fa-edit-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
													<?php } 
                                                } else { ?>
													<a href="<?php echo site_url('disciplines/delete/'.$obj->id); ?>" onclick="javascript: return confirm('Are you sure you want to delete this');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
													<?php
                                                    if($this->session->userdata('role_id') == SUPER_ADMIN){ ?>
                                                        <a href="<?php echo site_url('disciplines/edit/'.$obj->id); ?>" onclick="javascript: return confirm('Are you sure you want to edit this');" class="btn btn-info btn-xs "><i class="fa fa-edit-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                          <?php } ?>
                                                    <?php } ?>
                                                  </td>   
                                                  </tr>
                                                  <?php } ?>
                                              <?php } ?>
                                      </tbody>
                            </table>                                
                            </div>
                        </div>

                        <!-- ak -->

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_discipline_add" >
                            <div class="x_content"> 
                               <?php echo form_open(site_url('disciplines/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('discipline'); ?> <?php echo $this->lang->line('name'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="name"  id="add_name" value="<?php echo isset($post['name']) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('discipline'); ?> <?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('name'); ?></div>
                                    </div>
                                </div>
                                
                                                            
                                
                               
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('disciplines'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                
                            </div>  
                        </div>


                    <!-- ak -->
                    <div  class="tab-pane fade in <?php if(isset($edit)){ echo 'active'; }?>" id="tab_discipline_edit">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('disciplines/edit/'.(isset($discipline->id) ?  $discipline->id :"" )), array('name' => 'add', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                               <input  name="id"  value="<?php echo isset($discipline->id) ?  $discipline->id : ''; ?>"  type="hidden">
                                <?php $this->load->view('layout/school_list_form'); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('discipline'); ?> <?php echo $this->lang->line('name'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="name"  id="edit_name" value="<?php echo isset($discipline->name) ?  $discipline->name : ''; ?>" placeholder="<?php echo $this->lang->line('discipline'); ?> <?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('name'); ?></div>
                                    </div>
                                </div>
                                
                                                            
                                
                               
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('disciplines'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                
                            </div>  
                        </div>
                        
                    </div>

                    






                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">   
var edit = false;
    $(document).ready(function() {
		 <?php if(isset($edit) && !empty($edit)){ ?>		 
           $("#edit_school_id").trigger('change');         
         <?php } ?>	
	});		 
       <?php if(isset($paymentmode) && !empty($paymentmode)){ ?>
          edit = true; 
    <?php } ?>
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();
		var ledger_id = '';
		<?php if(isset($edit) && !empty($edit)){ ?>
            ledger_id =  '<?php echo $paymentmode->ledger_id; ?>';           
         <?php } ?> 
		if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
       
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data   : { school_id:school_id, ledger_id:ledger_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(edit){
                       $('#edit_ledger_id').html(response);   
                   }else{
                       $('#add_ledger_id').html(response);   
                   }
                                    
                 
               }
            }
        });		
		
	});
       
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
	   function get_discipline_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 

</script>