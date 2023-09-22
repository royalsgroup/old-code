<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-home"></i><small> <?php echo $this->lang->line('manage')." ".$this->lang->line('subjects'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>             
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <?php if(has_permission(VIEW, 'academic', 'subject')){ ?>
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_subject_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('subject'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                       <?php } ?>
                                              
 <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_subjects_by_school(this.value);">
                                    <option value="<?php echo site_url('subjects/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('subjects/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                <?php } ?>   
                            </select>
                        <?php } ?>  
                    </li> 						
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_subject_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
										<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
										<th><?php echo $this->lang->line('school'); ?></th>
										<?php } ?>
                                        <th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('discipline'); ?></th>     
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($subjects) && !empty($subjects)){ ?>
                                        <?php foreach($subjects as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
											<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
											<td><?php echo $obj->school_name; ?></td>
											<?php } ?>
                                            <td><?php echo $obj->name; ?></td>
											<td><?php echo $obj->discipline_name; ?></td>	
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
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
	   function get_subject_by_school(url){          
			if(url){
				window.location.href = url; 
			}		
		} 

</script>