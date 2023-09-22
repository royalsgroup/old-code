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
                           | <a href="<?php echo site_url('question/'); ?>"><?php echo $this->lang->line('questions'); ?></a>
                         <?php }
                          if(has_permission(VIEW, 'onlineexam', 'question')){ ?>
                            | <a href="<?php echo site_url('onlineexam/results'); ?>">Results</a>
                          <?php } 
                        } else{  
                          if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>
                            <a href="<?php echo site_url('user/onlineexam/'); ?>"><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('exam'); ?></a>                        
                          <?php } ?>                                
                    <?php }?>      
              </div>
           
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="active"><a href="#tab_class_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> Results</a> </li>     
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in active" id="tab_class_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('student'); ?></th>
                                        <th><?php echo $this->lang->line('attempt'); ?></th>
                                        <th class="text text-center">Result</th>
                                        <th><?php echo $this->lang->line('action'); ?></th>  
                                    </tr>
                                </thead>
                                <tbody>                                      
                                   
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