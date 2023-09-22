<style>
	.feeBlock{ border:1px solid #000000;padding:10px 0; }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-calculator"></i><small> <?php echo $this->lang->line('manage_invoice'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <?php $this->load->view('layout/fee_module_quick_link.php'); ?>  


            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs bordered">
                        <li class="<?php if (isset($list)) {
                                        echo 'active';
                                    } ?>"><a  href="<?php echo site_url('accounting/invoice/index'); ?>" ><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                    <?php if ($this->session->userdata('role_id') != STUDENT) { ?>
                        <li class="<?php if (isset($single)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/invoice/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> <?php echo $this->lang->line('invoice'); ?></a> </li>
						<li class="<?php if (isset($multitype)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/invoice/multitype'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> Mutitype <?php echo $this->lang->line('invoice'); ?></a> </li>
                        <!-- <li class="<?php if (isset($bulk)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/invoice/bulk'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> <?php echo $this->lang->line('bulk'); ?> <?php echo $this->lang->line('invoice'); ?></a> </li> -->
                         <li class="<?php if (isset($alumni)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/invoice/alumni'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> <?php echo $this->lang->line('invoice'); ?> Alumni</a> </li>
                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_invoice" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('invoice'); ?></a> </li>
                        <?php } ?>
                        <?php } ?>

                        <li class="li-class-list">
                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {  ?>
                                <select class="form-control col-md-7 col-xs-12" onchange="get_invoice_by_school(this.value);">
                                    <option value="<?php echo site_url('accounting/invoice/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option>
                                    <?php foreach ($schools as $obj) { ?>
                                        <option value="<?php echo site_url('accounting/invoice/index/' . $obj->id); ?>" <?php if (isset($filter_school_id) && $filter_school_id == $obj->id) {
                                                                                                                            echo 'selected="selected"';
                                                                                                                        } ?>> <?php echo $obj->school_name; ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </li>

                        <li class="li-class-list">
                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {  ?>
                                <select class="form-control col-md-7 col-xs-12" onchange="get_invoice_by_class(this.value);">
                                    <option value="<?php echo site_url('accounting/invoice/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('class'); ?>--</option>
                                    <?php foreach ($classes as $key => $value) { ?>
                                        <option value="<?php echo site_url('accounting/invoice/class_by/' . $akash . "/" . $value['id']); ?>" <?php if (isset($filter_class_id) && $filter_class_id == ($value['name'])) {
                                                                                                                                                    echo 'selected="selected"';
                                                                                                                                                } ?>> <?php echo ($value['name']); ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </li>

                    </ul>
                    <br />

                    <div class="tab-content">

                            <div class="tab-pane fade in active" id="tab_alumni_invoice">
                                <div class="x_content">
                                    <?php echo form_open_multipart(site_url('accounting/invoice/alumni'), array('name' => 'alumni', 'id' => 'alumni', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_form'); ?>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="academic_year_id">Academic Year<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="academic_year_id" id="academic_year_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php if (isset($academic_years) && !empty($academic_years)) { ?>
                                                    <?php foreach ($academic_years as $obj) { ?>
                                                        <option value="<?php echo $obj->id; ?>"><?php echo $obj->session_year; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('academic_year_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="income_head_id?"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('type'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="income_head_id" id="add_income_head_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                              
                                            </select>
                                            <div class="help-block"><?php echo form_error('income_head_id'); ?></div>
                                        </div>
                                    </div>
									<div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month"><?php echo $this->lang->line('date'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="month" id="add_single_month" value="<?php echo isset($post['month']) ?  $post['month'] : ''; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('month'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="class_id" id="add_class_id" required="required" onchange="get_student_by_class(this.value, '', 'single');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php if (isset($classes) && !empty($classes)) { ?>
                                                    <?php foreach ($classes as $obj) { ?>
                                                        <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_id"><?php echo $this->lang->line('student'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="student_id" id="single_student_id" required="required" onchange="get_fee_amount('', 'single');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class=" help-block"><?php echo form_error('student_id'); ?>
                                            </div>
                                            <div style="color:red" id="rte_information"></div>
                                        </div>
                                    </div>
                                    

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" readonly="readonly" name="amount" id="single_amount" value="<?php echo isset($post['amount']) ?  $post['amount'] : ''; ?>" placeholder="<?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
                                        </div>
                                    </div>
                                    <!-- ak27-02-21 -->

                                    <div class="item form-group" id="due-amount">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Due Amount <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="due_amount" id="due_amount" value="" readonly="">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div id="previous_due_col">

                                    </div>

                                    <div class="item form-group discount_amount_div" >
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_amount"><?php echo $this->lang->line('discount'); ?> <?php echo $this->lang->line('amount'); ?> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                           <input class="form-control col-md-7 col-xs-12" name="discount_amount" value="0" id="discount_amount" >
                                            <div class="help-block"><?php echo form_error('discount_amount'); ?></div>
                                        </div>
                                        
                                    </div>
                                    <div class="item form-group Ak" >
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_amount"></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <span id="akd"> </span>
                                        </div>
                                        
                                    </div>
                                    <input type="hidden" value="0" name="is_applicable_discount"  >

                                    <div class="item form-group" id="discount_select_div">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pay Amount <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="pay_amount" id="pay_amount" value="" placeholder="Pay Amount" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('pay_amount'); ?></div>
                                        </div>
                                    </div>

                                    <!-- ak27-02-21 -->


                                   




                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paid_status"><?php echo $this->lang->line('paid'); ?> <?php echo $this->lang->line('status'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="paid_status" id="paid_status" required="required" onchange="check_paid_status(this.value,'single');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="paid"><?php echo $this->lang->line('paid'); ?></option>
                                                <!-- <option value="unpaid"><?php echo $this->lang->line('unpaid'); ?></option> -->
                                            </select>
                                            <div class="help-block"><?php echo form_error('paid_status'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('credit_ledger'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="credit_ledger_id" required='required'>
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('voucher'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="voucher_id" id="voucher_id" required='required'>
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                            </select>
                                        </div>
                                    </div>
                                  
                                    <!-- For cheque Start-->
                                    <div class="display fn_single_paid_status" style="<?php if (isset($post) && $post['paid_status'] == 'paid') {
                                                                                            echo 'display:block;';
                                                                                        } ?>">
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('debit_ledger'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="add_debit_ledger_id">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php
                                                    foreach ($account_ledgers as $ledger) { ?>
                                                        <option value='<?php print $ledger->id; ?>'><?php print $ledger->name." [".$ledger->category."]" ?></option>
                                                    <?php  } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_method"><?php echo $this->lang->line('payment'); ?>  Mode <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control col-md-7 col-xs-12" name="payment_method" id="single_payment_method" onchange="check_payment_method(this.value, 'single');">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php $payments = get_payment_methods(); ?>
                                                    <?php foreach ($payments as $key => $value) { ?>
                                                        <?php if (!in_array($key, array('paypal', 'payumoney', 'ccavenue', 'paytm', 'stripe', 'paystack'))) { ?>
                                                            <option value="<?php echo $key; ?>" <?php if (isset($post) && $post['payment_method'] == $key) {
                                                                                                    echo 'selected="selected"';
                                                                                                } ?>><?php echo $value; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <div class="help-block"><?php echo form_error('payment_method'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- For cheque Start-->
                                    <div class="display fn_single_cheque" style="<?php if (isset($post) && $post['payment_method'] == 'cheque') {
                                                                                        echo 'display:block;';
                                                                                    } ?>">

                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name"><?php echo $this->lang->line('bank_name'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" name="bank_name" id="single_bank_name" value="" placeholder="<?php echo $this->lang->line('bank_name'); ?> " type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('bank_name'); ?></div>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cheque_no"><?php echo $this->lang->line('cheque'); ?> <?php echo $this->lang->line('number'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" name="cheque_no" id="single_cheque_no" value="" placeholder="<?php echo $this->lang->line('cheque'); ?> <?php echo $this->lang->line('number'); ?>" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('cheque_no'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- For cheque End-->

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control col-md-7 col-xs-12" name="note" id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('note'); ?></div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="single" name="type" />
                                            <a href="<?php echo site_url('accounting/invoice/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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

<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<script src="<?php echo ASSET_URL; ?>js/moment.js"></script>

<script type="text/javascript">
       $("document").ready(function() {
        $('#single').submit(function(){
            $('#send').attr('disabled', true);
            return true;
        })
        <?php if (isset($school_id) && !empty($school_id)) { ?>
            if ($("#edit_school_id").length == 0) {
                $(".fn_school_id").trigger('change');
            } else {
                $("#edit_school_id").trigger('change');
            }


            //$("#edit_school_id").trigger('change');
        <?php } ?>
    });
var __income_head_id = '';
var blockContent='';
var blockCount=1;
var bulk=false;
		<?php if(isset($bulk) && $bulk== TRUE) { ?>
		 bulk= true;
		<?php } ?>
	var multitype = '<?php print isset($multitype)? "true" : ""; ?>';
    $('#emi').on('change', function() {
        var value = $(this).val();
        var feetype = $('#add_income_head_id').val();
        var form_id = '<?php echo $form_id; ?>';
        var $emidata = $('#emidata');
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        if (value == 0) {
            $.ajax({
                url: "<?php echo base_url() ?>" + "accounting/invoice/emi",
                type: 'POST',
                async:false,
                data: {
                    "emidata": onload,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                },
                success: function(data) {
                    $emidata.append(data);
                    //    ak
                    $.ajax({
                        url: "<?php echo base_url() ?>" + "accounting/invoice/emi_find",
                        type: 'POST',
                        data: {
                            "feetype": feetype,
                            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                        },
                        async  : false,
                        success: function(response) {
                            var response = $.parseJSON(response);
                            var emi_type = response.emi_type
                            $('#emi_type').html(response.options);
                            

                            if(emi_type == "percentage")
                            {
                                $('#emi_type').on('change', function() {
                                var amount = $('#single_amount').val();
                                var emi_amount = $(this).find(':selected').attr('data-value')
                                var emi_total = emi_amount * amount / 100;
                                console.log(emi_total);
                                $('#emi_amount').val(emi_total);
                            });
                            }
                            else
                            {
                                $('#emi_type').on('change', function() {
                                var amount = $('#single_amount').val();
                                var emi_amount = $(this).find(':selected').attr('data-value')
                                var emi_total = emi_amount;
                                $('#emi_amount').val(emi_total);
                            });
                            }
                            



                        },
                    })
                    // ak

                },
            })
        } else {
            $('.item1').remove();
        }
    });





    $('.Ak').hide();

    // Ak


  /*  $('#single_student_id').on('change', function() {
        var id = $('select[name="student_id"]').val();
        // console.log("Hello");
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('accounting/invoice/find_fee/') ?>" + id,

            success: function(response) {
                response = JSON.parse(response);
                if (response != "") {

                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url('accounting/invoice/find_fee1/') ?>" + id,

                        success: function(data) {
                            data = JSON.parse(data);
                            // $('#due_amount').val(data.gross_amount);
                            // console.log(data);
                            if (data != null) {
                                var a = parseInt(data.discount);
                                var b = parseInt(response.total_fee);
                                var c = parseInt(data.gross_amount)
                                var total = c - (a + b);
                                if ((data.total_fee) != "null") {									
                                    $('#due_amount').val(data.due_amount);
                                }

                            } else {
                                var fee = $('#single_amount').val();
                                $('#due_amount').val(fee);
                            }
                        }
                    })
                }
            }

        })
    })*/
    $('#add_income_head_id').on('change', function() {
        $('#rte_information').html('');

        $('#single_student_id option:eq(0)').prop('selected', true);
        $('#add_class_id option:eq(0)').prop('selected', true);
        // console.log($('#add_class_id').val());
        $('#select2-add_class_id-container').html('--Select--');
        $('#select2-single_student_id-container').html('--Select--');

        $('#previous_due_col').html('');

        // $('#add_class_id').html('option: eq(0)');
        // $('#add_class_id').prop('selectedIndex', 0);
        // $('#add_class_id').val('selectedIndex', 1);
        // $('#single_student_id').prop('selectedIndex', 0);
        // $('#single_student_id').val('selectedIndex', 1);
        get_student_by_class('', '', 'single')
        $('#single_amount').val('');
        $('#due_amount').val('');
    })

    // $('#add_class_id').on('change', function() {
    //     // console.log($('#add_class_id').val());
    //     $('#single_amount').val('');
    //     $('#due_amount').val('');
    //     $('#add_income_head_id option:eq(0)').prop('selected', true);
    //     $('#select2-add_income_head_id-container').html('--Select--');
    // })

    // $('#single_student_id').on('change', function() {
    //     // console.log($('#add_class_id').val());
    //     // $('#single_amount').val('');
    //     // $('#due_amount').val('');
    //     // $('#add_income_head_id option:eq(0)').prop('selected', true);
    //     // $('#select2-add_income_head_id-container').html('--Select--');
    //     var type_index = $('#add_income_head_id')[0].selectedIndex;
    //     $('#add_income_head_id').prop('selectedIndex', 0);
    //     $('#add_income_head_id').prop('selectedIndex', type_index).click()
    //     // $('#add_income_head_id').prop('selectedIndex', type_index);
    // })

    // ak
    <?php 
    if($f_start && $f_end){ ?>
        $("#add_single_month").datepicker({
        format: "dd-mm-yyyy",
        startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
    });

    $("#add_bulk_month").datepicker({
        format: "dd-mm-yyyy",
        startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
    });

    $("#edit_month").datepicker({
        format: "dd-mm-yyyy",
        startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
    });
   <?php }else {?>
    $("#add_single_month").datepicker({
        format: "dd-mm-yyyy",
    });

    $("#add_bulk_month").datepicker({
        format: "dd-mm-yyyy",
    });

    $("#edit_month").datepicker({
        format: "dd-mm-yyyy",
    });
    <?php }?>
   

    $("document").ready(function() {
        is_edit = false;

        <?php if (isset($filter_school_id) && ($filter_school_id) >= 0) { ?>
            if ($("#edit_school_id").length == 0) {
                $(".fn_school_id").trigger('change');
            } else {
                $("#edit_school_id").trigger('change');
                is_edit = true;
            }
        <?php } ?>
    });

    function check_paid_status(paid_status, type) {

        if (paid_status == "paid") {

            $('.fn_' + type + '_paid_status').show();
            $('#' + type + '_payment_method').prop('required', true);

        } else {

            $('.fn_' + type + '_cheque').hide();
            $('.fn_' + type + '_paid_status').hide();
            $('#' + type + '_payment_method').prop('required', false);
        }

        $("select#" + type + "_payment_method").prop('selectedIndex', 0);
    }


    function check_payment_method(payment_method, type) {

        if (payment_method == "cheque") {

            $('.fn_' + type + '_cheque').show();
            $('#' + type + '_bank_name').prop('required', true);
            $('#' + type + '_cheque_no').prop('required', true);

        } else {

            $('.fn_' + type + '_cheque').hide();
            $('#' + type + '_bank_name').prop('required', false);
            $('#' + type + '_cheque_no').prop('required', false);
        }
    }

    
    $('#academic_year_id').on('change', function() {
        var school_id = $('.fn_school_id').val();
        var academic_year_id = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/invoice/get_fee_types'); ?>",
            data: {
                school_id: school_id,
                academic_year_id: academic_year_id
            },
            async: false,
            success: function(response) {
                if (response) {
                        $('#add_income_head_id').html(response);
                }
            }
        });
    })
    $('.fn_school_id').on('change', function() {
        $('#rte_information').html('');

        var school_id = $(this).val();
        var class_id = '';
        var debit_ledger_id = '';
		
        <?php if (isset($invoice) && !empty($invoice)) { ?>
            class_id = '<?php echo $invoice->class_id; ?>';
            debit_ledger_id = '<?php echo $invoice->class_id; ?>';
        <?php } ?>

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (is_edit) {
                        $('#edit_class_id').html(response);
                    }
					/*else if(mutitype == 'true'){
						$("#multitype_class_id").html(response);
					}*/
					else if(bulk == true){
						$('#bulk_class_id').html(response);
					}
					else {
                        $('#add_class_id').html(response);
                    }
                }

                get_fee_type_by_school(school_id, '');
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_bank_accountledger_by_school'); ?>",
            data: {
                school_id: school_id,
                ledger_id: debit_ledger_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (debit_ledger_id) {
                        $('#edit_debit_ledger_id').html(response);
                    } else {
                        $('#add_debit_ledger_id').html(response);
                    }
                }
            }
        });
    });



    <?php if (isset($edit)) { ?>
        get_student_by_class('<?php echo $invoice->class_id; ?>', '<?php echo $invoice->student_id; ?>', 'bulk');
    <?php } ?>

    function get_student_by_class(class_id = "", student_id, type) {
        $('#rte_information').html('');
        var school_id = $('.fn_school_id').val();
        
            var fee_type = $('#add_income_head_id').val();
        if(class_id =="")
        {
                class_id = $('#add_class_id').val();
        }
        academic_year_id = $('#academic_year_id').val();

        <?php if (isset($invoice) && !empty($invoice)) { ?>
            school_id = '<?php echo $invoice->school_id; ?>';
        <?php } ?>

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_student_by_class'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id,
                student_id: student_id,
                fee_type: fee_type,
                type: type,
                filter : 'fee_type',
                alumni : 1,
                fathername: 1,
                academic_year_id : academic_year_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    $('#' + type + '_student_id').html(response);
                }
            }
        });

    }

    // getting  
    function get_fee_type_by_school(school_id, fee_type_id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/invoice/get_fee_type_by_school'); ?>",
            data: {
                school_id: school_id,
                fee_type_id: fee_type_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (is_edit) {
                        $('#edit_income_head_id').html(response);
                    } 
					else if(multitype == 'true'){
						$(".multitype_income_head_id").html(response);
					}
					else if(bulk == true){
						$('#bulk_income_head_id').html(response);
					}
					else {
                        $('#add_income_head_id').html(response);
                    }
					blockContent=$("#block-1").html();
                }
            }
        });
    }

    function check_tution_fee()
    {
        var tution_fee = 0;
        $('.multitype_income_head_id').each(function(){

            var selectedOption = $(this).find('option[value="' + $(this).val() + '"]'); 

            var feeType = $(selectedOption).data("type"); 
            if(feeType == "fee")
            {
                tution_fee++;
            }
            
        })
       if(tution_fee > 0)
            {
                $('.discount_div').show();
            }
            else
            {
                $('.discount_div').hide();
            }
    }
    function get_tution_fee()
    {
        var tution_fee = 0;
        $('.multitype_income_head_id').each(function(){


            var selectedOption = $(this).find('option[value="' + $(this).val() + '"]'); 
            var feeType = $(selectedOption).data("type"); 
            if(feeType == "fee")
            {
                var parentblock = $(this).closest('.feeBlock'); 
                var fee = $(parentblock).find(".multitype_amount").val(); 
                tution_fee = fee;
            }
            
        })
      return tution_fee;
    }
    function get_tution_due_fee()
    {
        var tution_fee = 0;
        $('.multitype_income_head_id').each(function(){


            var selectedOption = $(this).find('option[value="' + $(this).val() + '"]'); 
            var feeType = $(selectedOption).data("type"); 
            if(feeType == "fee")
            {
                var parentblock = $(this).closest('.feeBlock'); 
                var fee = $(parentblock).find(".multitype_due_amount").val(); 
                tution_fee = fee;
            }
            
        })
      return tution_fee;
    }

    // get fee amount  
    function get_fee_amount(income_head_id, type,element) {
		if(income_head_id == '' && multitype != 'true'){
			income_head_id = $('#add_income_head_id').val();	
		}
        
  var blockId= $( element ).closest(".feeBlock").attr("id");
        if (!income_head_id) {
            $('#' + type + '_amount').val('');
            return false;
        }

        var school_id = $('.fn_school_id').val();
        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }

        var class_id = $('#add_class_id').val();
        var student_id = $('#' + type + '_student_id').val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/invoice/get_fee_amount'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id,
                student_id: student_id,
                income_head_id: income_head_id
            },
            dataType: "json",
            async: false,
            success: function(response) {
                if (response) {
                    var amount = response['amount'];
                    var rte = response['rte'];
                    $('.discount-col').show();
					if(multitype == 'true'){						
						 $('#'+blockId+' .multitype_amount').val(amount);
						 // get due fee amount // Nirali
                         $('#'+blockId+' .multitype_amount').val(amount);
                         $('#'+blockId+' .previous_due_multiple').html(`<input type="hidden" name="previous_fee_amount[]" value="">`);
                         if(response['previous_due'] == 1)
                        {
                            renderPreviousDue(response['previous_details'],blockId)
                        }
						 get_due_fee_amount(type,income_head_id,amount,blockId);
					}
					else{	
                        $('#previous_due_col').html(`<input type="hidden" name="previous_fee_amount" value="">`);
						get_due_fee_amount(type,income_head_id,amount);
                        $('#' + type + '_amount').val(amount);
                        if(rte ==1)
                        {
                            $('#rte_information').html('RTE Student');
                        }
                        else
                        {
                            $('#rte_information').html('');
                        }
                        
					}
                    
                }
                check_tution_fee()
            }
        });
    }
	function get_due_fee_amount(type,income_head_id,fee_amount,blockId){		
		 var school_id = $('.fn_school_id').val();
        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }		
        console.log("dsadasdasd")
        var class_id = $('#add_class_id').val();
        var emi_type = $('#emi_type').val();
        var emi = $('#emi').val();
        var student_id = $('#' + type + '_student_id').val();
		var month= $('#add_' + type + '_month').val();		
        var emi_amount =   $('#emi_amount').val();
		$.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/invoice/get_paid_fee_amount'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id,
                student_id: student_id,
				month: month,
                emi_type: emi_type,
                income_head_id: income_head_id,
                emi: emi
            },
            dataType: "json",
            async: false,

            success: function(response) {
                if (response) {
					var due_amount = fee_amount - response.paid_amount;
                    console.log(response)
					if(multitype == 'true'){						
						 $('#'+blockId+' .multitype_due_amount').val(due_amount);						
					}
					else{
                      if(emi_amount && response.paid_emi_amount)
                      {
                        emi_amount = emi_amount - response.paid_emi_amount;
                        $('#emi_amount').val(emi_amount);
                      }
						$('#due_amount').val(due_amount);
					}
                }
            }
        });
	}
    $(document).on('change','#emi_type', function() {
        var school_id = $('.fn_school_id').val();
        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }		
        var class_id = $('#add_class_id').val();
        var emi_type = $('#emi_type').val();
        var emi = $('#emi').val();
        var student_id = $('#single_student_id').val();
        var income_head_id = $('#add_income_head_id').val();
		var month= $('#add_single_month').val();		
        var emi_amount =   $('#emi_amount').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/invoice/get_paid_emi_amount'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id,
                student_id: student_id,
				month: month,
                emi_type: emi_type,
                income_head_id: income_head_id,
                emi: emi
            },
            dataType: "json",
            async: false,
            success: function(response) {
                if (response) {					
                      if(emi_amount && response.paid_emi_amount)
                      {
                        emi_amount = emi_amount - response.paid_emi_amount;
                        $('#emi_amount').val(emi_amount);
                      }
						
					}
            }
        });
    })
    /* Bulk invoice */

    function reset_fee_type(type) {
        $("select#" + type + "_income_head_id").prop('selectedIndex', 0);
    }

    function get_student_and_fee_amount() {
		income_head_id=$("#bulk_income_head_id").val();
        if (!income_head_id) {
            $('#student_container').html('');
            $('.fn_check_button').hide();
            return false;
        }

        var school_id = $('.fn_school_id').val();
        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }

        var class_id = $('#bulk_class_id').val();
		var month= $('#add_bulk_month').val();		
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/invoice/get_student_and_fee_amount'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id,
                income_head_id: income_head_id,
				month : month
            },
            async: false,
            success: function(response) {
                console.log(response)
                if (response == 'ay') {
                    toastr.error('<?php echo $this->lang->line('set_academic_year_for_school'); ?>');
                } else {
                    $('#student_container').html(response);
                    $('.fn_check_button').show();
                }
            }
        });
    }

    $('#check_all').on('click', function() {
        $('#student_container').children().find('input[type="checkbox"]').prop('checked', true);;
    });
    $('#uncheck_all').on('click', function() {
        $('#student_container').children().find('input[type="checkbox"]').prop('checked', false);;
    });
</script>



<!-- datatable with buttons -->
<script type="text/javascript">
    var __start_date = '';
        var __end_date = '';
    $(document).ready(function() {
    
        var sch_id='<?php print $filter_school_id; ?>';
        blockContent=$("#block-1").html();

        var table = $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            orderCellsTop: true,
				fixedHeader: true,
			  'processing': true,
      'serverSide': true,
      order: [[ 0, "desc" ]],
    "ordering" : false,
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("accounting/invoice/get_list"); ?>',
		  'data': {'school_id': sch_id,'start_date' : '',"end_date":''}
      },
            iDisplayLength: 15,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'pageLength'
            ],
            search: true,
            responsive: true,
        });
        $('#datatable-responsive thead').find('.datepicker').datepicker();	


        $('#min').change( function() {
           
                __start_date = $('#min').val();
                table.context[0].ajax.data.start_date = __start_date;
                table.draw();
            
        } );
    $('#max').change( function() { 
        console.log('dsds');
            __end_date = $('#max').val();
            table.context[0].ajax.data.end_date = __end_date;

            table.draw();
        
	 } );
    });

    $("#add").validate();

    $("#bulk").validate();

    $("#edit").validate();

    function get_invoice_by_school(url) {
        if (url) {
            window.location.href = url;
        }
    }

    function get_invoice_by_class(url) {
        if (url) {
            window.location.href = url;
        }
    }

    // $('#is_applicable_discount').onchange(function(){
    $('#is_applicable_discount').on('change', function() {
        var value = $(this).val();
        var school_id = $('.fn_school_id').val();
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        div_data += '<option value="manual">Manual</option>';
        if (value == 1) {
            $.ajax({
                url: "<?php echo site_url('accounting/invoice/get_discount'); ?>",
                type: "GET",
                data: {
                    school_id: school_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var select = "";
                        div_data += "<option value=" + obj.id + " " + select + ">" + obj.title + "</option>";
                    });
                    $('.Ak').show();
                    $('#show_discount').append(div_data);

                }
            })

        }
        if (value == 0) {
            $('.Ak').hide();
        }
    })
  
    $('#discount_amount').on('keyup', function() {
        <?php if(isset($multitype)) { ?>
            var amount = get_tution_due_fee();

        <?php }else{  ?>
            var amount = parseInt($('#due_amount').val());
            <?php }  ?>
        if (/\D/g.test(this.value))
        {
            
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
            $('#discount_amount').val(this.value);
        }

        var discount = parseInt(this.value);
        if(discount > amount)
        {
            alert('Discount cant be greater than the amount');
            $('#discount_amount').val(0);
        }
        amount = amount - discount;
        
        if (amount) {
                        $('#akd').html('<br><b> After Discount Pay Amount : ' + amount + '  </b>');
                    }
    })
    $('#show_discount').on('change', function() {
        var value = $(this).val();
        <?php if(isset($multitype)) { ?>
            var amount = get_tution_fee();

        <?php }else{  ?>
            var amount = $('#single_amount').val();
            <?php }  ?>
        console.log(value)
        if(value == "manual")
        {
            $('.discount_amount_div').show();
            amount = amount - $('#discount_amount').val();
                    if (amount) {
                        $('#akd').html('<br><b> After Discount Pay Amount : ' + amount + '  </b>');
                    }
        }
        else
        {
            $('.discount_amount_div').hide();

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                url: "<?php echo site_url('accounting/invoice/find_discount'); ?>",
                type: "GET",
                data: {
                    id: value
                },
                dataType: "json",
                success: function(data) {

                    if(data.type == "amount")
                    {
                        discount = data.amount;
                    }
                    else
                    {
                        discount = data.amount * amount / 100;
                    }
                   
                    amount = amount - discount;
                    if (amount) {
                        $('#akd').html('<br><b> After Discount Pay Amount : ' + amount + '  </b>');
                    }
                }
            })
        }
       
        

    })
    $('.fn_school_id').on('change', function() {
        var school_id = $(this).val();
        var fee_type_id = '';
        loadLedgers(school_id)
    })
    function loadLedgers(school_id)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data: {
                school_id: school_id,
                current: 1

            },
            async: false,
            success: function(response) {
                if (response) {
                    $('#credit_ledger_id').html(response);
                }
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_voucher_by_school'); ?>",
            data: {
                school_id: school_id,
                current: 1
            },
            async: false,
            success: function(response) {
                if (response) {
                    $('#voucher_id').html(response);
                }
            }
        });
    }

	function collectMoreFees(){
		var index=blockCount;
		blockCount++;
		var blockHtml="<div id='block-"+blockCount+"'  class='col-md-12 col-xs-12 feeBlock'>"+blockContent+"</div>"; 
		$("#block-"+index).after(blockHtml);
		$("#block-"+index+" .addMoreBtn").remove();
		
	}
    function isValidDate(date) {
        return date && Object.prototype.toString.call(date) === "[object Date]" && !isNaN(date);
     }
</script>