<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-users"></i><small> <?php echo $this->lang->line('import'). " ".$this->lang->line('csv'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>           
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">                        
                            <li  class="active"><a href="#tab_import"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('import'); ?> <?php echo $this->lang->line('csv'); ?></a> </li>                                                  
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                       
                        <div  class="tab-pane fade in active" id="tab_import">
                            <div class="x_content"> 
                            <?php if(isset($duplicate_schools)) {?>
                            <div class="row">                                      
                                                                   
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="item form-group" >
                                        Following schools  have errors :<br>
                                        <span style="color:red"> <?php foreach($duplicate_schools as $duplicate_school)
                                        {
                                            echo "  $duplicate_school <br>";
                                        }?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                               <?php echo form_open_multipart(site_url('import/import_school'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                       
                                <div class="row">                                      
                                                                   
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                         <div class="item form-group">
                                             <label ><?php echo $this->lang->line('csv_file'); ?>&nbsp;</label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="bulk_data"  id="bulk_data" type="file">
                                            </div>
                                         </div>
                                     </div>
                                </div>
                                
                                                            
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a  href="<?php echo site_url('import/import_school'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
										 <input  class="form-control col-md-7 col-xs-12"  name="school_import"  id="school" type="hidden" value='1'>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                                       
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            
                                            <div class="instructions">
                                            <div>Download sample file : <a target='_blank' href='<?php echo site_url('assets/files/school.csv'); ?>'>School</a>
                                            </div>
                                            
                                            </div>
                                        </div>           
                            </div>
                        </div>  
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
 <script type="text/javascript">      
    $("#add").validate();     
</script>