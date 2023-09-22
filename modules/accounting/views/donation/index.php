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
            | <a href="<?php echo site_url('accounting/donation/add'); ?>"><?php echo $this->lang->line('donation'); ?> <?php echo $this->lang->line('collection'); ?></a>
            | <a href="<?php echo site_url('accounting/donation/index'); ?>"><?php echo $this->lang->line('manage_donation'); ?></a>     
            </div> 

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs bordered">
                        <li class="<?php if (isset($list)) {
                                        echo 'active';
                                    } ?>"><a  href="<?php echo site_url('accounting/donation/index'); ?>" ><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('donation'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                    <?php if ($this->session->userdata('role_id') != STUDENT) { ?>
                        <li class="<?php if (isset($single)) {
                                        echo 'active';
                                    } ?>"><a href="<?php echo site_url('accounting/donation/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('create'); ?> <?php echo $this->lang->line('donation'); ?></a> </li>
                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_invoice" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('invoice'); ?></a> </li>
                        <?php } ?>
                        <?php } ?>

                        <li class="li-class-list">
                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {  ?>
                                <select class="form-control col-md-7 col-xs-12" onchange="get_invoice_by_school(this.value);">
                                    <option value="<?php echo site_url('accounting/donation/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option>
                                    <?php foreach ($schools as $obj) { ?>
                                        <option value="<?php echo site_url('accounting/donation/index/' . $obj->id); ?>" <?php if (isset($filter_school_id) && $filter_school_id == $obj->id) {
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
                                            <th style="width:100px;">Date<br><input type="text" placeholder="Start Date" class="datepicker" style="width:50%;"  id="min" /><input class="datepicker" type="text" placeholder="End date" style="width:50%;"  id="max" /></th>
                                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) { ?>
                                                <th><?php echo $this->lang->line('school'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('reciept'); ?> <?php echo $this->lang->line('number'); ?></th>
                                            <th><?php echo $this->lang->line('donor'); ?> <?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('father'); ?>/<?php echo $this->lang->line('husband'); ?> <?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('donor'); ?> <?php echo $this->lang->line('phone'); ?></th>
                                            <th><?php echo $this->lang->line('donor'); ?> <?php echo $this->lang->line('pan'); ?></th>
                                            <th><?php echo $this->lang->line('adhar_no'); ?></th>
                                            <th><?php echo $this->lang->line('amount'); ?></th>
                                            <th>Payment Method</th>
                                            <th>Remark</th>
                                            <th>Credit Ledger</th>
                                            <th>Debit Ledger</th>
                                            <th>Voucher</th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if (isset($single)) { ?>
                            <div class="tab-pane fade in <?php if (isset($single)) {
                                                                echo 'active';
                                                            } ?>" id="tab_single_invoice">
                                <div class="x_content">
                                    <?php echo form_open_multipart(site_url('accounting/donation/add'), array('name' => 'single', 'id' => 'single', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_form'); ?>
									<div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date"><?php echo $this->lang->line('date'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="date" id="create_date" value="<?php echo isset($post['date']) ?  $post['date'] : ''; ?>" placeholder="<?php echo $this->lang->line('date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('month'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="donor_name"> <?php echo $this->lang->line('donor')." ".$this->lang->line('name'); ?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="donor_name" value="<?php echo isset($post['donor_name']) ?  $post['donor_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('donor')." ".$this->lang->line('name');; ?>" required="required" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="father_name"> <?php echo $this->lang->line('father')."/". $this->lang->line('husband')." ".$this->lang->line('name'); ?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="father_name" value="<?php echo isset($post['father_name']) ?  $post['father_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('father')."/". $this->lang->line('husband')." ".$this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="donor_phone"> <?php echo $this->lang->line('donor')." ".$this->lang->line('phone'); ?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="donor_phone" value="<?php echo isset($post['donor_phone']) ?  $post['donor_phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('donor')." ".$this->lang->line('phone');; ?>" required="required" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="donor_pan"> <?php echo $this->lang->line('donor')." ".$this->lang->line('pan'); ?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="donor_pan" value="<?php echo isset($post['donor_pan']) ?  $post['donor_pan'] : ''; ?>" placeholder="<?php echo $this->lang->line('donor')." ".$this->lang->line('pan');; ?>" required="required" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adhar_no"> <?php echo $this->lang->line('adhar_no'); ?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="adhar_no" value="<?php echo isset($post['adhar_no']) ?  $post['adhar_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('adhar_no'); ?>" required="required" type="text" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="donor_name"> <?php echo $this->lang->line('donor')." ".$this->lang->line('address'); ?><span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control col-md-7 col-xs-12" name="donor_address" id="donor_address" placeholder="<?php echo $this->lang->line('donor_address'); ?>"><?php echo isset($post['donor_address']) ?  $post['donor_address'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('remark'); ?></div>
                                        </div>
                                    </div>
                                   
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount"> <?php echo $this->lang->line('amount'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12"  name="amount" id="single_amount" value="<?php echo isset($post['amount']) ?  $post['amount'] : ''; ?>" placeholder="<?php echo $this->lang->line('amount'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('amount'); ?></div>
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
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('credit_ledger'); ?> <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="add_credit_ledger_id">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php
                                                    foreach ($account_ledgers as $ledger) { ?>
                                                        <option value='<?php print $ledger->id; ?>'><?php print $ledger->name." [".$ledger->category."]" ?></option>
                                                    <?php  } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="voucher_id"><?php echo $this->lang->line('voucher'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control col-md-7 col-xs-12" name="voucher_id" id="add_voucher_id" required='required'>
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                                </select>
                                            </div>
                                        </div>
                                      
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark"><?php echo $this->lang->line('remark'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control col-md-7 col-xs-12" name="remark" id="remark" placeholder="<?php echo $this->lang->line('remark'); ?>"><?php echo isset($post['remark']) ?  $post['remark'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('remark'); ?></div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="single" name="type" />
                                            <a href="<?php echo site_url('accounting/donation/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                            <button id="send" type="submit"  class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
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
                                    <?php echo form_open_multipart(site_url('accounting/donation/multitype'), array('name' => 'single', 'id' => 'single', 'class' => 'form-horizontal form-label-left'), ''); ?>

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
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month"><?php echo $this->lang->line('date'); ?> <span class="required">*</span>
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
                                                        <option value="<?php echo $obj->id; ?>" data-type="<?= $obj->head_type ?>"><?php echo $obj->title; ?></option>
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
                                    <div class="previous_due_multiple">
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
                                        <input class='btn btn-danger' style="float:right" type="button" value="-" onclick="removeBlock(this)" />


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
	
                                    <div class="discount_div"  style="display:none">


<div class="item form-group discount-col" >
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
        <!-- <span> <b>If you have discounted time of admission, then do not select this option.</b></span> -->
        
    </div>
</div>
<div class="item form-group discount_amount_div" style="display:none">
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
</div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total_amount">Total_amount  <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" id="total_amount" value="" type="text" autocomplete="off" disabled>

                                        </div>
                                    </div>
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
                                            <input type="hidden" value="multitype" name="type" />
                                            <a href="<?php echo site_url('accounting/donation/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
                                    <?php echo form_open_multipart(site_url('accounting/donation/bulk'), array('name' => 'bulk', 'id' => 'bulk', 'class' => 'form-horizontal form-label-left'), ''); ?>

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
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi"> <?php echo $this->lang->line('emi'); ?><span class="required">*</span></label>
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

                                    <div class="item form-group discount-col" >
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
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name"><?php echo $this->lang->line('bank_name'); ?> <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" name="bank_name" id="bulk_bank_name" value="" placeholder="<?php echo $this->lang->line('bank_name'); ?> " type="text" autocomplete="off">
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
                                            <a href="<?php echo site_url('accounting/donation'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
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
<script src="<?php echo ASSET_URL; ?>js/moment.js"></script>
<script type="text/javascript">
var __income_head_id = '';
var blockContent='';
var blockCount=1;
var bulk=false;
		<?php if(isset($bulk) && $bulk== TRUE) { ?>
		 bulk= true;
		<?php } ?>


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
        $("#create_date").datepicker({
        format: "dd-mm-yyyy",
        startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
    });

    $("#add_bulk_month").datepicker({
        format: "dd-mm-yyyy",
        startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
    });

    $("#date").datepicker({
        format: "dd-mm-yyyy",
        startDate: '<?php print $f_start; ?>',
			endDate:'<?php print $f_end; ?>',
    });
   <?php }else {?>
    $("#create_date").datepicker({
        format: "dd-mm-yyyy",
    });

    $("#min").datepicker({
        format: "dd-mm-yyyy",
    });

    $("#max").datepicker({
        format: "dd-mm-yyyy",
    });
    <?php }?>
   
</script>



<!-- datatable with buttons -->
<script type="text/javascript">
    var __start_date = '';
    var __end_date = '';
    var debit_ledger_id = '';
    var voucher_id = "";

    $(document).ready(function() {
    
        var sch_id='<?php print $filter_school_id; ?>';
        var school_id = sch_id;
        <?php if (isset($filter_school_id) && ($filter_school_id) >= 0) { ?>
            $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_accountledgers_json_by_school'); ?>",
            data: {
                school_id: school_id,
                ledger_id: debit_ledger_id
            },
            dataType: "json",
            async: false,
            success: function(response) {
                if (response) {
                    var cashandbank = response['bankcash'];
                    var all = response['all'];
                    if (debit_ledger_id) {
                        $('#edit_debit_ledger_id').html(cashandbank);
                        $('#edit_creditledger_id').html(all);

                    } else {
                        $('#add_debit_ledger_id').html(cashandbank);
                        $('#add_credit_ledger_id').html(all);
                    }
                }
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_voucher_by_school'); ?>",
            data: {
                school_id: school_id,
                voucher_id: voucher_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (voucher_id) {
                        $('#edit_voucher_id').html(response);
                    } else {
                        $('#add_voucher_id').html(response);
                    }
                }
            }
        });
            if ($("#edit_school_id").length == 0) {
                $(".fn_school_id").trigger('change');
            } else {
                $("#edit_school_id").trigger('change');
                is_edit = true;
            }
        <?php } ?>
        $('.fn_school_id').on('change', function() {
        $('#rte_information').html('');

        var school_id = $(this).val();
        var class_id = '';
        var debit_ledger_id = '';
		
        <?php if (isset($invoice) && !empty($invoice)) { ?>
            class_id = '<?php echo $invoice->class_id; ?>';
            debit_ledger_id = '<?php echo $invoice->class_id; ?>';
            debit_ledger_id = '<?php echo $invoice->class_id; ?>';

        <?php } ?>

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }
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
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_voucher_by_school'); ?>",
            data: {
                school_id: school_id,
                voucher_id: voucher_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (voucher_id) {
                        $('#edit_voucher_id').html(response);
                    } else {
                        $('#add_voucher_id').html(response);
                    }
                }
            }
        });
    });
        var table = $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            orderCellsTop: true,
				fixedHeader: true,
			  'processing': true,
      'serverSide': true,
      order: [[ 0, "desc" ]],
      'serverMethod': 'post',
      'ajax': {
          'url':'<?php echo site_url("accounting/donation/get_list"); ?>',
		  'data': {'school_id': sch_id,'start_date' : '',"end_date":''}
      },
            iDisplayLength: 15,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'pageLength',
                'colvis'
            ],
            "columnDefs": [
                {
                    "targets": [0,13] ,						
                    "searchable": false,
                    "orderable": false,						
                },
                {
                    "targets": [10,11,12] ,						
                    "searchable": false,
                    "orderable": false,	
                    "visible": false,				
                }
            ],
            search: true,
            responsive: false,
        });
        $('#datatable-responsive thead').find('.datepicker').datepicker();	
        $('#min').change( function() {
           __start_date = $('#min').val();
                table.context[0].ajax.data.start_date = __start_date;
                table.draw();
        } );
        $('#max').change( function() { 
            __end_date = $('#max').val();
            table.context[0].ajax.data.end_date = __end_date;

            table.draw();
        
        } );
    })
    
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

</script>
