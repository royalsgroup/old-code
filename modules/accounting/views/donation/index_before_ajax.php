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

            <div class="x_content quick-link no-print">
                <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if (has_permission(VIEW, 'accounting', 'discount')) { ?>
                    <a href="<?php echo site_url('accounting/discount/index'); ?>"><?php echo $this->lang->line('discount'); ?></a>
                <?php } ?>

                <?php if (has_permission(VIEW, 'accounting', 'feetype')) { ?>
                    | <a href="<?php echo site_url('accounting/feetype/index'); ?>"><?php echo $this->lang->line('fee_type'); ?></a>
                <?php } ?>

                <?php if (has_permission(VIEW, 'accounting', 'invoice')) { ?>

                    <?php if ($this->session->userdata('role_id') == STUDENT || $this->session->userdata('role_id') == GUARDIAN) { ?>
                        | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>
                    <?php } else { ?>
                        | <a href="<?php echo site_url('accounting/invoice/add'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?></a>
                        | <a href="<?php echo site_url('accounting/invoice/index'); ?>"><?php echo $this->lang->line('manage_invoice'); ?></a>
                        | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if (has_permission(VIEW, 'accounting', 'duefeeemail')) { ?>
                    | <a href="<?php echo site_url('accounting/duefeeemail/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('email'); ?></a>
                <?php } ?>
                <?php if (has_permission(VIEW, 'accounting', 'duefeesms')) { ?>
                    | <a href="<?php echo site_url('accounting/duefeesms/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('sms'); ?></a>
                <?php } ?>

            </div>


            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs bordered">
                        <li class="<?php if (isset($list)) {
                                        echo 'active';
                                    } ?>"><a href="#tab_invoice_list" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('list'); ?></a> </li>

                        <li class="<?php if (isset($single)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/invoice/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> <?php echo $this->lang->line('invoice'); ?></a> </li>
						<li class="<?php if (isset($multitype)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/invoice/multitype'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> Mutitype <?php echo $this->lang->line('invoice'); ?></a> </li>
                        <li class="<?php if (isset($bulk)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/invoice/bulk'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> <?php echo $this->lang->line('bulk'); ?> <?php echo $this->lang->line('invoice'); ?></a> </li>
                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_invoice" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('invoice'); ?></a> </li>
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
                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_invoice_list">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <th> Sr# </th> 
                                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) { ?>
                                                <th><?php echo $this->lang->line('school'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('number'); ?></th>
                                            <th><?php echo $this->lang->line('student'); ?></th>
                                            <th><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('type'); ?></th>
                                            <th><?php echo $this->lang->line('gross_amount'); ?></th>
                                            <th><?php echo $this->lang->line('discount'); ?></th>
                                            <th><?php echo $this->lang->line('net_amount'); ?></th>
                                            <th><?php echo $this->lang->line('due_amount'); ?></th>
                                            <th>EMI Name</th>
                                            <th><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1;
                                        if (isset($invoices) && !empty($invoices)) {
                                            $b = 0; ?>
                                            <?php foreach ($invoices as $obj) {  ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <td><?php echo $obj->id; ?></td>
                                                    <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) { ?>
                                                        <td><?php echo $obj->school_name; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $obj->custom_invoice_id; ?></td>
                                                    <td><?php echo $obj->student_name; ?></td>
                                                    <td><?php echo $obj->father_name; ?></td>
                                                    <td><?php echo $obj->class_name; ?></td>
                                                    <td><?php echo $obj->head; ?></td>
                                                    <td><?php echo $obj->gross_amount; ?></td>
                                                    <td><?php echo $obj->discount; ?></td>
                                                    <td><?php echo $obj->net_amount;
                                                        if ($obj->emi_type) {
                                                            echo "(EMI)";
                                                        } ?></td>
                                                    <td><?php if ($obj->due_amount == 0 && $obj->paid_status == "paid") {
                                                            echo "Paid";
                                                        } else {
                                                            echo $obj->due_amount;
                                                        } ?></td>
                                                     <td> <?php if($obj->emi_name){
                                                            echo $obj->emi_name; 
                                                            } else { echo "NO";  } ?> </td>   
                                                    <td><?php echo get_paid_status($obj->paid_status); ?></td>
                                                    <td>
                                                        <?php if (has_permission(VIEW, 'accounting', 'invoice')) { ?>
                                                            <a href="<?php echo site_url('accounting/invoice/view/' . $obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(DELETE, 'accounting', 'invoice')) { ?>
                                                            <?php if ($obj->paid_status == 'unpaid') { ?>
                                                                <a href="<?php echo site_url('accounting/invoice/delete/' . $obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
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

                        <?php if (isset($single)) { ?>
                            <div class="tab-pane fade in <?php if (isset($single)) {
                                                                echo 'active';
                                                            } ?>" id="tab_single_invoice">
                                <div class="x_content">
                                    <?php echo form_open_multipart(site_url('accounting/invoice/add'), array('name' => 'single', 'id' => 'single', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_form'); ?>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="income_head_id?"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('type'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="income_head_id" id="add_income_head_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php if (isset($income_heads) && !empty($income_heads)) { ?>
                                                    <?php foreach ($income_heads as $obj) { ?>
                                                        <option value="<?php echo $obj->id; ?>"><?php echo $obj->title; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('income_head_id'); ?></div>
                                        </div>
                                    </div>
									<div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month"><?php echo $this->lang->line('month'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="month" id="add_single_month" value="<?php echo isset($post['month']) ?  $post['month'] : ''; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('month'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi"> EMI<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="emi" id="emi" required="required">
                                                <option value="0">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="0">--<?php echo $this->lang->line('yes'); ?>--</option>
                                                <option value="1">--<?php echo $this->lang->line('no'); ?>--</option>
                                                <!-- <?php if (isset($income_heads) && !empty($income_heads)) { ?>
                                                            <?php foreach ($income_heads as $obj) { ?>
                                                            <option value="<?php echo $obj->id; ?>" ><?php echo $obj->title; ?></option>
                                                            <?php } ?> 
                                                        <?php } ?>  -->
                                            </select>

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
                                        </div>
                                    </div>

                                    <div id="emidata"></div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" readonly="readonly" name="amount" id="single_amount" value="<?php echo isset($post['amount']) ?  $post['amount'] : ''; ?>" placeholder="<?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
                                        </div>
                                    </div>



                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">EMI Amount </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" readonly="readonly" id="emi_amount" value="" placeholder="" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
                                        </div>
                                    </div>

                                    <!-- ak27-02-21 -->

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Due Amount <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="due_amount" id="due_amount" value="" readonly="">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_applicable_discount"><?php echo $this->lang->line('is_applicable_discount'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_applicable_discount" id="is_applicable_discount" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1"><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0"><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <span id="akd1"> </span>
                                            <div class="help-block"><?php echo form_error('is_applicable_discount'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group Ak">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="show_discount"> Your Coupon <?php echo $this->lang->line('show_discount'); ?> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="show_discount" id="show_discount">

                                            </select>
                                            <span> <b>If you have discounted time of admission, then do not select this option.</b></span>
                                            <span id="akd"> </span>
                                        </div>
                                    </div>


                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pay Amount <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="pay_amount" id="pay_amount" value="" placeholder="Pay Amount" required="required" type="text" autocomplete="off">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <!-- ak27-02-21 -->


                                   




                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paid_status"><?php echo $this->lang->line('paid'); ?> <?php echo $this->lang->line('status'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="paid_status" id="paid_status" required="required" onchange="check_paid_status(this.value,'single');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="paid"><?php echo $this->lang->line('paid'); ?></option>
                                                <option value="unpaid"><?php echo $this->lang->line('unpaid'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('paid_status'); ?></div>
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
                                                        <option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
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
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name"><?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" name="bank_name" id="single_bank_name" value="" placeholder="<?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
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
                        <?php } ?>
 <?php if (isset($multitype)) { ?>
                            <div class="tab-pane fade in <?php if (isset($multitype)) {
                                                                echo 'active';
                                                            } ?>" id="tab_multitype_invoice">
                                <div class="x_content">
                                    <?php echo form_open_multipart(site_url('accounting/invoice/multitype'), array('name' => 'single', 'id' => 'single', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_form'); ?>
                                                                     

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
                                            <select class="form-control col-md-7 col-xs-12" name="student_id" id="single_student_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class=" help-block"><?php echo form_error('student_id'); ?>
                                            </div>
                                        </div>
                                    </div>
									 <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month"><?php echo $this->lang->line('month'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="month" id="add_single_month" value="<?php echo isset($post['month']) ?  $post['month'] : ''; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('month'); ?></div>
                                        </div>
                                    </div>
									<div id='block-1' class='col-md-12 col-xs-12 feeBlock'>
 <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="income_head_id?"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('type'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12 noSelect2 multitype_income_head_id" name="income_head_id[]" required="required" onchange="get_fee_amount(this.value, 'single',this);">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php if (isset($income_heads) && !empty($income_heads)) { ?>
                                                    <?php foreach ($income_heads as $obj) { ?>
                                                        <option value="<?php echo $obj->id; ?>"><?php echo $obj->title; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('income_head_id'); ?></div>
                                        </div>
                                    </div>
                                    <div id="emidata"></div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12 multitype_amount" readonly="readonly" name="amount[]" value="<?php echo isset($post['amount']) ?  $post['amount'] : ''; ?>" placeholder="<?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
                                        </div>
                                    </div>
                                 
                                    <!-- ak27-02-21 -->

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Due Amount <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12 multitype_due_amount" name="due_amount[]" value="" readonly="">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
									<div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pay Amount <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12 multitype_pay_amount" name="pay_amount[]" value="" placeholder="Pay Amount" required="required" type="text" autocomplete="off">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
								  <div class="item form-group addMoreBtn">
										<input class='btn btn-default' type="button" value="Add More" onclick="collectMoreFees();" />
									  </div>

</div>
<!--
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_applicable_discount"><?php echo $this->lang->line('is_applicable_discount'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12 noSelect2" name="is_applicable_discount" id="is_applicable_discount" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1"><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0"><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <span id="akd1"> </span>
                                            <div class="help-block"><?php echo form_error('is_applicable_discount'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group Ak">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="show_discount"> Your Coupon <?php echo $this->lang->line('show_discount'); ?> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="show_discount" id="show_discount">

                                            </select>
                                            <span> <b>If you have discounted time of admission, then do not select this option.</b></span>
                                            <span id="akd"> </span>
                                        </div>
                                    </div>

0-->
                                    

                                    <!-- ak27-02-21 -->
	
                                   




                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paid_status"><?php echo $this->lang->line('paid'); ?> <?php echo $this->lang->line('status'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="paid_status" id="paid_status" required="required" onchange="check_paid_status(this.value,'single');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="paid"><?php echo $this->lang->line('paid'); ?></option>
                                                <option value="unpaid"><?php echo $this->lang->line('unpaid'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('paid_status'); ?></div>
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
                                                        <option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
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
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name"><?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" name="bank_name" id="single_bank_name" value="" placeholder="<?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
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
                                            <input type="hidden" value="multitype" name="type" />
                                            <a href="<?php echo site_url('accounting/invoice/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($bulk)) { ?>
                            <div class="tab-pane fade in <?php if (isset($bulk)) {
                                                                echo 'active';
                                                            } ?>" id="tab_bulk_invoice">
                                <div class="x_content">
                                    <?php echo form_open_multipart(site_url('accounting/invoice/bulk'), array('name' => 'bulk', 'id' => 'bulk', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_form'); ?>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="income_head_id?"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('type'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="income_head_id" id="bulk_income_head_id" required="required" onchange="get_student_and_fee_amount();">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php if (isset($income_heads) && !empty($income_heads)) { ?>
                                                    <?php foreach ($income_heads as $obj) { ?>
                                                        <option value="<?php echo $obj->id; ?>"><?php echo $obj->title; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('income_head_id'); ?></div>
                                        </div>
                                    </div>
									<div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month"><?php echo $this->lang->line('month'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="month" id="add_bulk_month" value="<?php echo isset($post['month']) ?  $post['month'] : ''; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('month'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi"> EMI<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="emi" id="emi" required="required">
                                                <option value="0">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="0">--<?php echo $this->lang->line('yes'); ?>--</option>
                                                <option value="1">--<?php echo $this->lang->line('no'); ?>--</option>
                                                <!-- <?php if (isset($income_heads) && !empty($income_heads)) { ?>
                                                            <?php foreach ($income_heads as $obj) { ?>
                                                            <option value="<?php echo $obj->id; ?>" ><?php echo $obj->title; ?></option>
                                                            <?php } ?> 
                                                        <?php } ?>  -->
                                            </select>

                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="class_id" id="bulk_class_id" onchange="get_student_and_fee_amount();" required="required">
                                                <!-- <select class="form-control col-md-7 col-xs-12" name="class_id" id="add_class_id" required="required" onchange="reset_fee_type('add');"> -->
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

                                    <div id="emidata"></div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_id"><?php echo $this->lang->line('student'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div id="student_container">
                                            </div>
                                            <div class="help-block fn_check_button display">
                                                <button id="check_all" type="button" class="btn btn-success btn-xs"><?php echo $this->lang->line('check_all'); ?></button>
                                                <button id="uncheck_all" type="button" class="btn btn-success btn-xs"><?php echo $this->lang->line('uncheck_all'); ?></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_applicable_discount?"><?php echo $this->lang->line('is_applicable_discount'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_applicable_discount" id="is_applicable_discount" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1"><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0"><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_applicable_discount'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group Ak">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="show_discount"> Your Coupon <?php echo $this->lang->line('show_discount'); ?> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="show_discount" id="show_discount">

                                            </select>
                                            <span> <b>If you have discounted time of admission, then do not select this option.</b></span>
                                            <span id="akd"> </span>
                                        </div>
                                    </div>



                                    

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paid_status"><?php echo $this->lang->line('paid'); ?> <?php echo $this->lang->line('status'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="paid_status" id="paid_status" required="required" onchange="check_paid_status(this.value, 'bulk');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="paid"><?php echo $this->lang->line('paid'); ?></option>
                                                <option value="unpaid"><?php echo $this->lang->line('unpaid'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('paid_status'); ?></div>
                                        </div>
                                    </div>

                                    <!-- For cheque Start-->
                                    <div class="display fn_bulk_paid_status" style="<?php if (isset($post) && $post['paid_status'] == 'paid') {
                                                                                        echo 'display:block;';
                                                                                    } ?>">
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('debit_ledger'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="add_debit_ledger_id" required="required">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php
                                                    foreach ($account_ledgers as $ledger) { ?>
                                                        <option value='<?php print $ledger->id; ?>'><?php print $ledger->name; ?></option>
                                                    <?php  } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_method"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('method'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control col-md-7 col-xs-12" name="payment_method" id="bulk_payment_method" onchange="check_payment_method(this.value, 'bulk');">
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
                                    <div class="display fn_bulk_cheque" style="<?php if (isset($post) && $post['payment_method'] == 'cheque') {
                                                                                    echo 'display:block;';
                                                                                } ?>">

                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name"><?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" name="bank_name" id="bulk_bank_name" value="" placeholder="<?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('bank_name'); ?></div>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cheque_no"><?php echo $this->lang->line('cheque'); ?> <?php echo $this->lang->line('number'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" name="cheque_no" id="bulk_cheque_no" value="" placeholder="<?php echo $this->lang->line('cheque'); ?> <?php echo $this->lang->line('number'); ?>" type="text" autocomplete="off">
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
                                            <input type="hidden" value="bulk" name="type" />
                                            <a href="<?php echo site_url('accounting/invoice'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (isset($edit)) { ?>
                            <div class="tab-pane fade in active" id="tab_edit_tag">
                                <div class="x_content">
                                    <?php echo form_open(site_url('accounting/invoice/edit/' . $invoice->id), array('name' => 'edit', 'id' => 'edit', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_edit_form'); ?>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="class_id" id="edit_class_id" required="required" onchange="get_student_by_class(this.value, '','');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php if (isset($classes) && !empty($classes)) { ?>
                                                    <?php foreach ($classes as $obj) { ?>
                                                        <option value="<?php echo $obj->id; ?>" <?php if ($invoice->class_id == $obj->id) {
                                                                                                    echo 'selected="selected"';
                                                                                                } ?>><?php echo $obj->name; ?></option>
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
                                            <select class="form-control col-md-7 col-xs-12" name="student_id" id="edit_student_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('student_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="income_head_id"><?php echo $this->lang->line('income_head'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="income_head_id" id="edit_income_head_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach ($income_heads as $obj) { ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if ($invoice->income_head_id == $obj->id) {
                                                                                                echo 'selected="selected"';
                                                                                            } ?>><?php echo $obj->title; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('income_head_id'); ?></div>
                                        </div>
                                    </div>


                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount"><?php echo $this->lang->line('amount'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="amount" id="amount" value="<?php echo isset($invoice->amount) ?  $invoice->amount : ''; ?>" placeholder="<?php echo $this->lang->line('amount'); ?>" required="required" type="number" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount"><?php echo $this->lang->line('discount'); ?>(%)
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="discount" id="discount" value="<?php echo isset($invoice->discount) ?  $invoice->discount : ''; ?>" placeholder="<?php echo $this->lang->line('discount'); ?>" type="number" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('discount'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control col-md-7 col-xs-12" name="note" id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($invoice->note) ?  $invoice->note : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('note'); ?></div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="<?php echo isset($invoice) ? $invoice->id : $id; ?>" name="id" />
                                            <a href="<?php echo site_url('accounting/invoice'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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

<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>

<script type="text/javascript">
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
                        success: function(response) {

                            $('#emi_type').html(response);
                            $('#emi_type').on('change', function() {
                                console.log("helllo");
                                var amount = $('#single_amount').val();
                                var emi_amount = $(this).val();
                                var emi_total = emi_amount * amount / 100;
                                console.log(emi_total);
                                $('#emi_amount').val(emi_total);
                            });



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
        $('#single_student_id option:eq(0)').prop('selected', true);
        $('#add_class_id option:eq(0)').prop('selected', true);
        // console.log($('#add_class_id').val());
        $('#select2-add_class_id-container').html('--Select--');
        $('#select2-single_student_id-container').html('--Select--');
        // $('#add_class_id').html('option: eq(0)');
        // $('#add_class_id').prop('selectedIndex', 0);
        // $('#add_class_id').val('selectedIndex', 1);
        // $('#single_student_id').prop('selectedIndex', 0);
        // $('#single_student_id').val('selectedIndex', 1);
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

    $("#add_single_month").datepicker({
        format: "dd-mm-yyyy",
    });

    $("#add_bulk_month").datepicker({
        format: "dd-mm-yyyy",
    });

    $("#edit_month").datepicker({
        format: "dd-mm-yyyy",
    });

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


    $('.fn_school_id').on('change', function() {
	
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
            url: "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
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

    function get_student_by_class(class_id, student_id, type) {

        var school_id = $('.fn_school_id').val();
        var fee_type = $('#add_income_head_id').val();
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
                type: type
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
            async: false,
            success: function(response) {
                if (response) {
					if(multitype == 'true'){						
						 $('#'+blockId+' .multitype_amount').val(response);
						 // get due fee amount // Nirali
						 get_due_fee_amount(type,income_head_id,response,blockId);
					}
					else{	
						get_due_fee_amount(type,income_head_id,response);
                    $('#' + type + '_amount').val(response);
					}
                }
            }
        });
    }
	function get_due_fee_amount(type,income_head_id,fee_amount,blockId){		
		 var school_id = $('.fn_school_id').val();
        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }		
        var class_id = $('#add_class_id').val();
        var student_id = $('#' + type + '_student_id').val();
		var month= $('#add_' + type + '_month').val();		
		$.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/invoice/get_paid_fee_amount'); ?>",
            data: {
                //school_id: school_id,
                //class_id: class_id,
                student_id: student_id,
				month: month,
                income_head_id: income_head_id
            },
            async: false,
            success: function(response) {
                if (response) {
					var due_amount = fee_amount - response;
					if(multitype == 'true'){						
						 $('#'+blockId+' .multitype_due_amount').val(due_amount);						
					}
					else{
						$('#due_amount').val(due_amount);
					}
                }
            }
        });
	}

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

    $('#show_discount').on('change', function() {
        var value = $(this).val();

        var amount = $('#single_amount').val();
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            url: "<?php echo site_url('accounting/invoice/find_discount'); ?>",
            type: "GET",
            data: {
                id: value
            },
            dataType: "json",
            success: function(data) {

                discount = data.amount * amount / 100;
                amount = amount - discount;
                if (amount) {
                    $('#akd').html('<br><b> After Discount Pay Amount : ' + amount + '  </b>');
                }
            }
        })

    })

    $('#is_applicable_discount').on('change', function() {
        var value = $('#single_student_id').val();
        var check = $(this).val();
        if (check == 1) {
            $.ajax({
                url: "<?php echo site_url('accounting/invoice/check_discount'); ?>",
                type: "GET",
                data: {
                    student_id: value
                },
                dataType: "json",
                success: function(data) {

                    if (data.discount != 0.00) {
                        $(".Ak").remove();
                        $('#akd1').html('<br><b> You Give Already Discount Plaese Select Discount Option No  </b>');
                    }
                }
            })
        } else {
            $("#akd1").remove();
        }

    })
	function collectMoreFees(){
		var index=blockCount;
		blockCount++;
		var blockHtml="<div id='block-"+blockCount+"'  class='col-md-12 col-xs-12 feeBlock'>"+blockContent+"</div>"; 
		$("#block-"+index).after(blockHtml);
		$("#block-"+index+" .addMoreBtn").remove();
		
	}
</script>