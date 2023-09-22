<style>
	button.btn-primary{
		background-color:#337ab7;
	}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-slideshare"></i><small> Assign Exam</small></h3>
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
				<div class="row">
					<div class="col-md-12">
					<div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong>Search Criteria:</strong></h5>
                                    </div>
                                </div>
					<form role="form" action="<?php echo site_url('onlineexam/assign/' . $id) ?>" method="post" class="row">

                            <?php echo $this->customlib->getCSRF(); ?>
                          
                            <input type="hidden" name="onlineexam_id" value="<?php echo $onlineexam->id; ?>">
                           
                                <div class="col-md-6">
                                     <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            ?>
                                            <option value="<?php echo $class->id; ?>" <?php
                                            if(set_value('class_id') == $class->id) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $class->name; ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                          

                          
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					 <form method="post" action="<?php echo site_url('onlineexam/addstudent') ?>" id="assign_form">


                    <?php
                    if (isset($resultlist)) {
                        ?>
						<div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('assign')." ".$this->lang->line('online')." ".$this->lang->line('exam');?>:</strong></h5>
                                    </div>
                                </div>
                      <div class="box-header ptbnull"></div>  
                        <div class="">                           
                            <div class="box-body">
                                <div class="row">
                                        <div class="col-md-4">
                                            <div class="table-responsive">

                                                <h4>
                                                    <input type="hidden" name="onlineexam_id" value="<?php echo $onlineexam->id; ?>">
                                                    <input type="hidden" name="post_class_id" value="<?php echo $class_id; ?>">
                                                    <input type="hidden" name="post_section_id" value="<?php echo $section_id; ?>">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $onlineexam->exam; ?></a>
                                                </h4>

                                             

                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class=" table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th><input style="vertical-align: text-top;" type="checkbox" id="select_all"/> <?php echo $this->lang->line('all'); ?></th>

                                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                            <th><?php echo $this->lang->line('student_name'); ?></th>

                                                            <th><?php echo $this->lang->line('class'); ?></th>
                                                            <?php if($sch_setting->father_name){ ?>
                                                            <th><?php echo $this->lang->line('father_name'); ?></th><?php }   if($sch_setting->category){ ?>
                                                            <th><?php echo $this->lang->line('category'); ?></th>
                                                        <?php } ?>
                                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                                      
                                                        </tr>
                                                        <?php
                                                        if (empty($resultlist)) {
                                                            ?>
                                                            <tr>
                                                                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            $count = 1;
                                                            foreach ($resultlist as $student) {
                                                     
                                                                ?>
                                                                <tr>

                                                                    <td>
                                                                        <?php
                                                                        if ($student['onlineexam_student_id'] != 0) {
                                                                            $sel = "checked='checked'";
                                                                        } else {
                                                                            $sel = "";
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" name="all_students[]" value="<?php echo $student['id']; ?>">

                                                                        <input class="checkbox" type="checkbox" name="students_id[]"  value="<?php echo $student['id']; ?>" <?php echo $sel; ?>/>


                                                                    </td>

                                                                    <td><?php echo $student['admission_no']; ?></td>

                                                                    <td><?php echo $student['name']?></td>
                                                                    <td><?php echo $student['class_name']." (".$student['section_name'].")"; ?></td><?php if($sch_setting->father_name){ ?>
                                                                    <td><?php echo $student['father_name']; ?></td>
                                                                <?php } if($sch_setting->category){ ?>
                                                                    <td><?php echo $student['category']; ?></td>
                                                                <?php } ?>
                                                                    <td><?php echo $student['gender']; ?></td>

                                                                </tr>
                                                                <?php
                                                            }
                                                            $count++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                            <?php if(has_permission(EDIT, 'onlineexam', 'onlineexam')){ ?>											
                                            <button type="submit" class="allot-fees btn btn-success btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                            </button>
                                        <?php } ?>
                                            <br/>
                                            <br/>
                                        </div>
                                   
                                </div>

                            </div>
                        </div>
                        <?php
                    }
                    ?>
                   
                </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    var date_format = '<?php echo $result = strtr('d-m-Y', ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id', 0) ?>';
    var section_id = '<?php echo set_value('section_id', 0) ?>';
    getSectionByClass(class_id, section_id);
    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });


    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';


            $.ajax({
                type: "GET",
                url: base_url + "academic/section/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                async  : false,
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }



//select all checkboxes
    $("#select_all").change(function () {  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });


    $('.checkbox').change(function () {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
       
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });
    $("#assign_form").submit(function (e) {
        if (confirm('Are you sure?')) {
            var $this = $('.allot-fees');
            $.ajax({
                type: "POST",
                dataType: 'Json',
                url: $("#assign_form").attr('action'),
                async  : false,
                data: $("#assign_form").serialize(), // serializes the form's elements.
                beforeSend: function () {
                    $this.button('loading');

                },
                success: function (data)
                {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                    }

                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });

        }
        e.preventDefault();

    });
function successMsg(msg) {
     toastr.success(msg);
 }
    
 function errorMsg(msg) {
     toastr.error(msg);
 }

</script>

