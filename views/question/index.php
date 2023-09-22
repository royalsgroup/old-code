<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-slideshare"></i><small> <?php echo $this->lang->line('question'); ?></small></h3>
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
                           | <a href="<?php echo site_url('question/'); ?>"><?php echo $this->lang->line('questions'); ?></a>
                         <?php } } else{  
                          if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>
                            | <a href="<?php echo site_url('user/onlineexam/'); ?>"><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('exam'); ?></a>                        
                          <?php } ?>                                
                    <?php }?>      
              </div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_class_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('question'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'onlineexam', 'question')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('question/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('question'); ?></a> </li>                          
                             <?php }else{ ?>
                                 <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_class"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('question'); ?></a> </li>                          
                             <?php } ?>
                           
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_class"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('question'); ?></a> </li>                          
                        <?php } ?> 

                        <li class="li-class-list">
                       <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_question_by_school(this.value);">
                                    <option value="<?php echo site_url('question/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                <?php foreach($schools as $obj ){ ?>
                                    <option value="<?php echo site_url('question/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
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
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('question'); ?></th>
                                        <th><?php echo $this->lang->line('answer'); ?></th>                                                               
                                        <th><?php echo $this->lang->line('action'); ?></th>  
                                    </tr>
                                </thead>
                                <tbody>                                      
                                    <?php $count = 1; if(isset($questions) && !empty($questions)){ ?>
                                        <?php foreach($questions as $obj){ ?>                                       
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                                <td><?php echo $obj->school_name; ?></td>
                                            <?php } ?>
                                            <td class="mailbox-name"> <?php echo $obj->class_name; ?></td>
                                           <td class="mailbox-name"> <?php echo $obj->subject_name; ?></td>
                                            <td class="mailbox-name"> <?php echo $obj->question; ?></td>
                                            <td class="mailbox-name"> <?php echo $obj->correct; ?> </td>                                                       
                                            <td class="text-right">
                                              <?php if(has_permission(EDIT, 'onlineexam', 'question')){ ?>
                                                    <a href="<?php echo site_url('question/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'onlineexam', 'question')){ ?>
                                                    <a href="<?php echo site_url('question/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
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
                               <?php echo form_open(site_url('question/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										 <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="add_class_id" required="required" onchange="get_subject_by_class(this.value);" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($class)){ foreach($class as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } }?>                                            
                                        </select>										                                    
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										 <select  class="form-control col-md-7 col-xs-12"  name="subject_id"  id="add_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($subject)){ foreach($subjects as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['subject_id']) && $post['subject_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } }?>                                            
                                        </select>										                                    
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('question'); ?>  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="question"  id="add_question" placeholder="<?php echo $this->lang->line('question'); ?> " required="required" autocomplete="off"><?php echo isset($post['question']) ?  $post['question'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('question'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> A  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_a"  id="add_opt_a" placeholder="<?php echo $this->lang->line('option'); ?> A " required="required" autocomplete="off"> <?php echo isset($post['opt_a']) ?  $post['opt_a'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_a'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> B  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_b"  id="add_opt_b" placeholder="<?php echo $this->lang->line('option'); ?> B " required="required" autocomplete="off"><?php echo isset($post['opt_b']) ?  $post['opt_b'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_b'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> C  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_c"  id="add_opt_c" placeholder="<?php echo $this->lang->line('option'); ?> C " required="required" autocomplete="off"><?php echo isset($post['opt_c']) ?  $post['opt_c'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_c'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> D  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_d"  id="add_opt_d" placeholder="<?php echo $this->lang->line('option'); ?> D " required="required" autocomplete="off"><?php echo isset($post['opt_d']) ?  $post['opt_d'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_d'); ?></div>
                                    </div>
                                </div>									
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> E
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_e"  id="add_opt_e" placeholder="<?php echo $this->lang->line('option'); ?> E "  autocomplete="off"><?php echo isset($post['opt_e']) ?  $post['opt_e'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_e'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('answer'); ?>   <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control  col-md-7 col-xs-12" name="correct" required="required" autocomplete="off">										
											<option value=""><?php echo $this->lang->line('select'); ?></option>
											<?php
													foreach ($questionOpt as $question_opt_key => $question_opt_value) {
												?>
												<option value="<?php echo $question_opt_key; ?>" <?php echo ($post['correct']== $question_opt_key) ?  "selected='selected'" : ''; ?>><?php echo $question_opt_value; ?></option>
												<?php
											}
											?>
										</select>                                        
                                        <div class="help-block"><?php echo form_error('correct'); ?></div>
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
                               <?php echo form_open(site_url('question/edit/'.$question->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?>
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										 <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="edit_class_id" required="required" onchange="get_subject_by_class(this.value);" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($class)){ foreach($class as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } }?>                                            
                                        </select>										                                    
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>								
                                 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										 <select  class="form-control col-md-7 col-xs-12"  name="subject_id"  id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($subjects)){ foreach($subjects as $obj ){ ?>
                                            <option value="<?php echo $obj->id; ?>" <?php echo ($question->subject_id == $obj->id) ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } }?>                                            
                                        </select>										                                    
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('question'); ?>  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="question"  id="edit_question" placeholder="<?php echo $this->lang->line('question'); ?> " required="required" autocomplete="off"><?php echo isset($question->question) ?  $question->question : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('question'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> A  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea class="form-control ckeditor col-md-7 col-xs-12"  name="opt_a"  id="edit_opt_a" placeholder="<?php echo $this->lang->line('option'); ?> A " required="required" autocomplete="off"><?php echo isset($question->opt_a) ?  $question->opt_a : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_a'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> B  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_b"  id="edit_opt_b" placeholder="<?php echo $this->lang->line('option'); ?> B " required="required" autocomplete="off"><?php echo isset($question->opt_b) ?  $question->opt_b : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_b'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> C  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_c"  id="edit_opt_c" placeholder="<?php echo $this->lang->line('option'); ?> C " required="required" autocomplete="off"><?php echo isset($question->opt_c) ?  $question->opt_c : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_c'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> D  <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_d"  id="edit_opt_d" placeholder="<?php echo $this->lang->line('option'); ?> D " required="required" autocomplete="off"><?php echo isset($question->opt_d) ?  $question->opt_d : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_d'); ?></div>
                                    </div>
                                </div>									
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('option'); ?> E
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control ckeditor col-md-7 col-xs-12"  name="opt_e"  id="edit_opt_e" placeholder="<?php echo $this->lang->line('option'); ?> E "  autocomplete="off"><?php echo isset($question->opt_e) ?  $question->opt_e : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('opt_e'); ?></div>
                                    </div>
                                </div>	
								<div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('answer'); ?>   <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control  col-md-7 col-xs-12" name="correct" required="required" autocomplete="off">										
											<option value=""><?php echo $this->lang->line('select'); ?></option>
											<?php
													foreach ($questionOpt as $question_opt_key => $question_opt_value) {
												?>
												<option value="<?php echo $question_opt_key; ?>" <?php echo ($question->correct == $question_opt_key) ?  "selected='selected'" : ''; ?>><?php echo $question_opt_value; ?></option>
												<?php
											}
											?>
										</select>                                        
                                        <div class="help-block"><?php echo form_error('correct'); ?></div>
                                    </div>
                                </div>	                               
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" name="id" id="id" value="<?php echo $question->id; ?>" />
                                        <a href="<?php echo site_url('question'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
<link href="<?php echo VENDOR_URL; ?>editor/jquery-te-1.4.0.css" rel="stylesheet">
 <script type="text/javascript" src="<?php echo VENDOR_URL; ?>editor/ckeditor5/ckeditor.js"></script>
 <script>
 jQuery.validator.setDefaults({
  // This will ignore all hidden elements alongside `contenteditable` elements
  // that have no `name` attribute
  ignore: ":hidden, [contenteditable='true']:not([name])"
});
 var allEditors = document.querySelectorAll('.ckeditor');
for (var i = 0; i < allEditors.length; ++i) {
  ClassicEditor.create(allEditors[i]);
}
   /* ClassicEditor
        .create( document.querySelector( '#add_opt_a' ) )
        .catch( error => {
            console.error( error );
        } );*/
</script>
<script type="text/javascript">

var edit = false;
    <?php if(isset($edit)){ ?>
        edit = true;
    <?php } ?>
	var class_id = '';
        <?php if(isset($question) && !empty($question)){ ?>         
          //  subject_id =  '<?php echo $question->subject_id; ?>';
			class_id =  '<?php echo $question->class_id; ?>';
         <?php } ?> 
     //$('#add_question').jqte(); 
//$('#edit_question').jqte(); 	 
    $("document").ready(function() {
         <?php if(isset($question) && !empty($question)){ ?>
            $("#edit_school_id").trigger('change');
			get_subject_by_class(class_id);
         <?php } else { ?>
		 $(".fn_school_id").trigger('change');
		 <?php  } ?>
    });
     
    $('.fn_school_id').on('change', function(){
      
        var school_id = $(this).val();       
        
		
        
        if(!school_id){
           toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
           return false;
        }
		$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data   : { school_id:school_id, class_id : class_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(class_id){
                       $('#edit_class_id').html(response);
                   }else{
                       $('#add_class_id').html(response); 
                   }
               }
            }
        });
        /*
         $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_school'); ?>",
            data   : { school_id:school_id, subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(subject_id){
                       $('#edit_subject_id').html(response);
                   }else{
                       $('#add_subject_id').html(response); 
                   }
               }
            }
        }); */      
     
    }); 

    function get_subject_by_class(class_id){
		if(edit){
            var school_id = $('#edit_school_id').val();
        }else{
            var school_id = $('#add_school_id').val();
        }
		var subject_id = '';
		<?php if(isset($question) && !empty($question)){ ?>         
            subject_id =  '<?php echo $question->subject_id; ?>';			
         <?php } ?> 
		 $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : { school_id:school_id,class_id:class_id ,subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {    
                   if(subject_id){
                       $('#edit_subject_id').html(response);
                   }else{
                       $('#add_subject_id').html(response); 
                   }
               }
            }
        });
	}
  </script>
  <!-- Super admin js end -->
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
        });
        
    $("#add").validate();     
    $("#edit").validate(); 
    
    function get_question_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    }  
    
</script>