<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-dollar"></i><small>
                        <?php echo $this->lang->line('manage_payment'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if (has_permission(VIEW, 'payroll', 'grade')) { ?>
                    <a href="<?php echo site_url('payroll/payscalecategory/index'); ?>"><?php echo $this->lang->line('salary_grade'); ?></a>
                <?php } ?>
                <?php if (has_permission(VIEW, 'payroll', 'payment')) { ?>
                    | <a href="<?php echo site_url('payroll/payment/index'); ?>"><?php echo $this->lang->line('salary'); ?>
                        <?php echo $this->lang->line('payment'); ?></a>
                <?php } ?>
                <?php if (has_permission(VIEW, 'payroll', 'history')) { ?>
                    | <a href="<?php echo site_url('payroll/history/index'); ?>"><?php echo $this->lang->line('payroll'); ?>
                        <?php echo $this->lang->line('history'); ?></a>
                <?php } ?>

            </div>

            <div class="x_content">
                <?php echo form_open_multipart(site_url('payroll/payment/index'), array('name' => 'payment', 'id' => 'payment', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">

                    <?php $this->load->view('layout/school_list_filter'); ?>

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <div><?php echo $this->lang->line('role'); ?> <?php echo $this->lang->line('type'); ?> <span class="required"> *</span></div>
                            <select class="form-control col-md-7 col-xs-12" name="payment_to" id="payment_to" required="required" onchange="get_user_list(this.value);">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <option value="all" <?php if (isset($payment_to) && $payment_to == 'all') {
                                                            echo 'selected="selected"';
                                                        } ?>>

                                    <?php echo $this->lang->line('all'); ?></option>
                                <option value="employee" <?php if (isset($payment_to) && $payment_to == 'employee') {
                                                                echo 'selected="selected"';
                                                            } ?>>

                                    <?php echo $this->lang->line('employee'); ?></option>
                                <option value="teacher" <?php if (isset($payment_to) && $payment_to == 'teacher') {
                                                            echo 'selected="selected"';
                                                        } ?>>

                                    <?php echo $this->lang->line('teacher'); ?></option>
                            </select>
                            <div class="help-block"><?php echo form_error('type'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <div><?php echo $this->lang->line('payment_to'); ?> <span class="required"> *</span></div>
                            <select class="form-control col-md-12 col-xs-12" name="user_id" id="user_id">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            </select>
                            <div class="help-block"><?php echo form_error('user_id'); ?></div>
                        </div>
                    </div>

                    <!-- ak -->

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label for="salary_month"><?php echo $this->lang->line('month'); ?> <span class="required">*</span></label>
                            <input class="form-control col-md-7 col-xs-12 " name="salary_month" id="add_salary_month" value="<?php print (isset($salary_month)) ? $salary_month : ''; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                            <div class="help-block"><?php echo form_error('salary_month'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label for="salary_month">Payment <?php echo $this->lang->line('date'); ?> </label>
                            <input class="form-control col-md-7 col-xs-12 " name="payment_date" id="payment_date" value="<?php print (isset($payment_date)) ? $payment_date : $todayDate; ?>" placeholder="<?php echo $this->lang->line('date'); ?>" type="text" autocomplete="off" readonly>
                            <div class="help-block"><?php echo form_error('payment_date'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label for="basic_salary"><?php echo $this->lang->line('working_days'); ?> <span class="required">*</span></label>
                            <input class="form-control col-md-7 col-xs-12" name="working_days" id="add_working_days" value="<?php echo $work ?>" placeholder="<?php echo $this->lang->line('working_days'); ?>" required="required" type="number" max='31' autocomplete="off">
                            <div class="help-block"><?php echo form_error('working_days'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label for="basic_salary">Absent days <span class="required"></span></label>
                            <input class="form-control col-md-7 col-xs-12" name="absent_days" id="add_absent_days" value="<?php echo $absent_days ?>" placeholder="Absent days" required="required" type="number" max='31' autocomplete="off">
                            <div class="help-block"><?php echo form_error('working_days'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label for="basic_salary"><?php echo $this->lang->line('leave_days'); ?> <span class="required">*</span></label>
                            <input class="form-control col-md-7 col-xs-12" name="leave_days" id="leave_days" value="<?php echo $leave_days ?>" placeholder="<?php echo $this->lang->line('leave_days'); ?>" required="required" type="number" max='31' autocomplete="off">
                            <div class="help-block"><?php echo form_error('leave_days'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label class="item form-group" for="name"><?php echo $this->lang->line('debit_ledger'); ?><span class="required">*</span></label>
                                
                            </label>

                            <select class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="add_debit_ledger_id" required="required">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label class="item form-group" for="name"><?php echo $this->lang->line('credit_ledger'); ?>
                               
                            </label>
                            <select class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="add_credit_ledger_id" >
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php
                            /*    foreach ($account_ledgers as $ledger) { ?>
                                    <option value='<?php print $ledger->id; ?>' <?php if (isset($credit_ledger_id) && $credit_ledger_id == $ledger->id) {
                                                                                    echo 'selected="selected"';
                                                                                } ?>><?php print $ledger->name; ?></option>
                                <?php  } */?>
                            </select>

                        </div>
                    </div>
					<input type="hidden" value="paid" name="payment_status"/>
                    <!--<div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="item form-group">
                            <label class="item form-group" for="name">Payment Status
                                <span class="required">*</span>
                            </label>
                            <select class="form-control col-md-7 col-xs-12" name="payment_status" id="payment_status" required="required" onchange='update_payment_status(this.value);'>
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <option value="paid" <?php print (isset($payment_status) && $payment_status =='paid')? 'selected="selected"' : ''; ?>><?php echo $this->lang->line('paid'); ?></option>
                                <!-- <option value="unpaid" <?php print (isset($payment_status) && $payment_status =='unpaid')? 'selected="selected"' : ''; ?>>unpaid</option>-->


                            <!--</select>

                        </div>
                    </div>-->

                    <!-- ak -->


                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><br />
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs bordered">
                      
                        <?php if (isset($earnings) && isset($expenditure)) { ?>
                            <?php if (has_permission(ADD, 'payroll', 'payment')) { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="#tab_add_payment" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i>
                                        <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('payment'); ?></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_payment" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i>
                                    <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('payment'); ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                    <br />

                    <div class="tab-content">
                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_payment_list">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <th><?php echo $this->lang->line('photo'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('month'); ?></th>
                                            <th><?php echo $this->lang->line('salary_type'); ?></th>
                                            <th><?php echo $this->lang->line('total'); ?>
                                                <?php echo $this->lang->line('allowance'); ?></th>
                                            <th><?php echo $this->lang->line('total'); ?>
                                                <?php echo $this->lang->line('deduction'); ?></th>
                                            <th><?php echo $this->lang->line('net_salary'); ?></th>
											<th><?php echo $this->lang->line('payment')." ".$this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1;
                                        if (isset($payments) && !empty($payments)) { ?>
                                            <?php foreach ($payments as $obj) { ?>
                                                <?php
                                                $path = '';
                                                if ($payment_to == 'teacher') {
                                                    $path = 'teacher';
                                                } else {
                                                    $path = 'employee';
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <td>
                                                        <?php if ($obj->photo != '') { ?>
                                                            <img src="<?php echo UPLOAD_PATH; ?>/<?php echo $path; ?>-photo/<?php echo $obj->photo; ?>" alt="" width="60" />
                                                        <?php } else { ?>
                                                            <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="60" />
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo ucfirst($obj->name); ?></td>
                                                    <td><?php echo date('M, Y', strtotime('1-' . $obj->salary_month)); ?></td>
                                                    <td><?php echo $obj->salary_type; ?></td>
                                                    <td><?php echo $obj->total_allowance; ?></td>
                                                    <td><?php echo $obj->total_deduction; ?></td>
                                                    <td><?php echo $obj->net_salary; ?></td>
													<td><?php echo $obj->payment_status; ?></td>
                                                    <td>
                                                        <?php /*if(has_permission(EDIT, 'payroll', 'payment')){ ?>
                                                <a href="<?php echo site_url('payroll/payment/edit/'.$obj->id); ?>"
                                                    class="btn btn-success btn-xs"><i class="fa fa-pencil-square-o"></i>
                                                    <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } */ ?>
                                                        <?php if (has_permission(VIEW, 'payroll', 'payment')) { ?>
                                                            <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="getPaymentModal(<?php echo $obj->id; ?>,'<?php echo $payment_to; ?>');" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(VIEW, 'payroll', 'payment') && $obj->payment_status == 'paid') { ?>
                                                            <a href="<?php echo site_url('payroll/payment/payslip/' . $obj->id); ?>" class="btn btn-success btn-xs"><i class="fa fa-eye"></i>
                                                                <?php echo $this->lang->line('payslip'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(DELETE, 'payroll', 'payment')) { ?>
                                                            <a href="<?php echo site_url('payroll/payment/delete/' . $obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>
                                                                <?php echo $this->lang->line('delete'); ?> </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if (isset($earnings) && isset($expenditure)) { ?>
                            <div class="tab-pane fade in <?php if (isset($add)) {
                                                                echo 'active';
                                                            } ?>" id="tab_add_payment">
                                <div class="x_content">
                                    <?php echo form_open(site_url('payroll/payment/add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>


                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="salary_type"><?php echo $this->lang->line('salary_type'); ?>
                                                    <span class="required">*</span></label>
                                                <input class="form-control col-md-7 col-xs-12" name="salary_type" id="add_salary_type" value="<?php echo $this->lang->line($payment->salary_type); ?>" placeholder="<?php echo $this->lang->line('salary_type'); ?>" required="required" readonly="readonly" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('salary_type'); ?></div>
                                            </div>
                                        </div>
                                        <?php if ($payment->salary_type == 'monthly') { ?>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="item form-group">
                                                    <label for="salary_month"><?php echo $this->lang->line('month'); ?> <span class="required">*</span></label>
                                                    <input class="form-control col-md-7 col-xs-12 " name="salary_month" id="add_salary_month" value="<?php print $salary_month; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" readonly='readonly' required="required" type="text" autocomplete="off">
                                                    <div class="help-block"><?php echo form_error('salary_month'); ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="item form-group">
                                                    <label for="basic_salary"><?php echo $this->lang->line('basic_salary'); ?>
                                                        <span class="required">*</span></label>
                                                    <input class="form-control col-md-7 col-xs-12" name="basic_salary" id="add_basic_salary" value="<?php echo $payment->basic_salary; ?>" placeholder="<?php echo $this->lang->line('basic_salary'); ?>" required="required" readonly="readonly" type="number" autocomplete="off">
                                                    <div class="help-block"><?php echo form_error('basic_salary'); ?></div>
                                                </div>
                                            </div>
                                         <!--   <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="item form-group">
                                                    <label for="basic_salary"><?php echo $this->lang->line('working_days'); ?>
                                                        <span class="required">*</span></label>
                                                    <input class="form-control col-md-7 col-xs-12" name="working_days" id="add_working_days" value="" placeholder="<?php echo $this->lang->line('working_days'); ?>" required="required" type="number" max='31' autocomplete="off">
                                                    <div class="help-block"><?php echo form_error('working_days'); ?></div>
                                                </div>
                                            </div>-->
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="item form-group">
                                                    <label for="basic_salary"><?php echo $this->lang->line('calculated_basic_salary'); ?>
                                                        <span class="required">*</span></label>
                                                    <input class="form-control col-md-7 col-xs-12" name="cal_basic_salary" id="cal_basic_salary" value="ddd<?php echo $payment->basic_salary; ?>" placeholder="<?php echo $this->lang->line('calculated_basic_salary'); ?>" required="required" readonly="readonly" type="number" autocomplete="off">
                                                    <div class="help-block"><?php echo form_error('calculated_basic_salary'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div id="add_detail_data">
                                    </div>
									<input type="hidden" value="cash" name="payment_method"/>
                                    
                                    <!--<div class='row' id='add_payment_method' <?php print(isset($payment_status) && $payment_status == 'unpaid') ? "style='display:none;'" : ''; ?>>

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="payment_method"><?php echo $this->lang->line('payment'); ?>
                                                    <?php echo $this->lang->line('method'); ?> <span class="required">*</span></label>
                                                <select class="form-control col-md-7 col-xs-12" name="payment_method" id="payment_method" required="required" onchange="check_payment_method(this.value);">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php $payments = get_payment_methods(); ?>
                                                    <?php foreach ($payments as $key => $value) { ?>
                                                        <?php if (in_array($key, array('cash', 'cheque'))) { ?>
                                                            <option value="<?php echo $key; ?>" <?php if (isset($post) && $post['payment_method'] == $key) {
                                                                                                    echo 'selected="selected"';
                                                                                                } ?>>
                                                                <?php echo $value; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <div class="help-block"><?php echo form_error('payment_method'); ?></div>
                                            </div>
                                        </div>

                                    </div>-->

                                    <div class="row display fn_cheque">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="bank_name"><?php echo $this->lang->line('bank'); ?>
                                                    <?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                                <input class="form-control col-md-7 col-xs-12" name="bank_name" id="bank_name" value="" placeholder="<?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('bank_name'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="cheque_no"><?php echo $this->lang->line('cheque'); ?>
                                                    <?php echo $this->lang->line('number'); ?> <span class="required">*</span></label>
                                                <input class="form-control col-md-7 col-xs-12" name="cheque_no" id="cheque_no" value="" placeholder="<?php echo $this->lang->line('cheque'); ?> <?php echo $this->lang->line('number'); ?>" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('cheque_no'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                      <!--  <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="name"><?php echo $this->lang->line('debit_ledger'); ?> <span class="required">*</span>
                                                </label>
                                                <select class="form-control col-md-7 col-xs-12" name="debit_ledger_id" id="add_debit_ledger_id" required="required">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php
                                                    foreach ($account_ledgers as $ledger) { ?>
                                                        <option value='<?php print $ledger->id; ?>'>
                                                            <?php print $ledger->name; ?></option>
                                                    <?php  } ?>
                                                </select>
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="name"><?php echo $this->lang->line('credit_ledger'); ?> <span class="required">*</span>
                                                </label>
                                                <select class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="add_credit_ledger_id" required="required">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php
                                                    foreach ($account_ledgers as $ledger) { ?>
                                                        <option value='<?php print $ledger->id; ?>'>
                                                            <?php print $ledger->name; ?></option>
                                                    <?php  } ?>
                                                </select>

                                            </div>
                                        </div>--> 
                                        <!-- <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="expenditure_head_id"><?php echo $this->lang->line('expenditure_head'); ?> <span class="required">*</span></label>
                                                <select  class="form-control col-md-7 col-xs-12" name="expenditure_head_id"  id="expenditure_head_id" required="required" >
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                    <?php foreach ($exp_heads as $obj) { ?>                                           
                                                         <option value="<?php echo $obj->id; ?>" <?php if (isset($post) && $post['expenditure_head_id'] == $obj->id) {
                                                                                                        echo 'selected="selected"';
                                                                                                    } ?>><?php echo $obj->title; ?></option>
                                                    <?php } ?>                                            
                                                </select>
                                                <div class="help-block"><?php echo form_error('expenditure_head_id'); ?></div>
                                            </div>
                                        </div>-->
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="item form-group">
                                                <label for="note"><?php echo $this->lang->line('note'); ?></label>
                                                <textarea class="form-control col-md-7 col-xs-12 textarea-4column" name="note" id="note" placeholder="<?php echo $this->lang->line('note'); ?>"></textarea>
                                                <div class="help-block"><?php echo form_error('note'); ?></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" id="add_school_id" name="school_id" value="<?php echo $school_id; ?>" />
                                            <input type="hidden" id="add_payment_to" name="payment_to" value="<?php echo $payment_to; ?>" />
                                            <input type="hidden" id="payment_date" name="payment_date" value="<?php echo $payment_date; ?>" />
                                            <input type="hidden" id="add_user_id" name="user_id" value="<?php echo $user_id; ?>" />
											<input type="hidden" id="" name="debit_ledger_id" value="<?php echo $debit_ledger_id; ?>" />
											<input type="hidden" id="" name="credit_ledger_id" value="<?php echo $credit_ledger_id; ?>" />
											<input type="hidden" id="" name="working_days" value="<?php echo $work; ?>" />
											<input type="hidden" id="" name="payment_status" value="<?php echo $payment_status; ?>" />
                                            <input type="hidden" id="add_salary_grade_id" name="salary_grade_id" value="<?php echo $payment->salary_grade_id; ?>" />
                                            <input type="hidden" id="add_hidden_salary_type" value="<?php echo strtolower($payment->salary_type); ?>" />
                                            <a href="<?php echo site_url('payroll/payment/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (isset($edit)) { ?>

                            <div class="tab-pane fade in active" id="tab_edit_payment">
                                <div class="x_content">
                                    <?php echo form_open(site_url('payroll/payment/edit/' . $edit_payment->id), array('name' => 'edit', 'id' => 'edit', 'class' => 'form-horizontal form-label-left'), ''); ?>


                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="salary_type"><?php echo $this->lang->line('salary_type'); ?>
                                                    <span class="required">*</span></label>
                                                <input class="form-control col-md-7 col-xs-12" name="salary_type" id="edit_salary_type" value="<?php echo $this->lang->line(strtolower($edit_payment->salary_type)); ?>" placeholder="<?php echo $this->lang->line('salary_type'); ?>" required="required" readonly="readonly" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('salary_type'); ?></div>
                                            </div>
                                        </div>
                                        <?php if ($payment->salary_type == 'monthly') { ?>

                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="item form-group">
                                                    <label for="basic_salary"><?php echo $this->lang->line('basic_salary'); ?>
                                                        <span class="required">*</span></label>
                                                    <input class="form-control col-md-7 col-xs-12" name="basic_salary" id="edit_basic_salary" value="<?php echo $edit_payment->basic_salary; ?>" placeholder="<?php echo $this->lang->line('basic_salary'); ?>" required="required" readonly="readonly" type="number" autocomplete="off">
                                                    <div class="help-block"><?php echo form_error('basic_salary'); ?></div>
                                                </div>
                                            </div>


                                        <?php } ?>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <h5 class="column-title"><strong>Earnings :</strong></h5>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <?php foreach ($earnings as $e) { ?>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="item form-group">
                                                    <label for="total_allowance"><?php print $e->name; ?></label>
                                                    <input class="form-control col-md-7 col-xs-12 " type='number' name='cat[<?php print $e->id; ?>]' value='<?php print $e->cal_amount; ?>' readonly='readonly' />
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <h5 class="column-title"><strong>Expenditures :</strong></h5>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <?php foreach ($expenditure as $e) { ?>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="item form-group">
                                                    <label for="total_allowance"><?php print $e->name; ?></label>
                                                    <input class="form-control col-md-7 col-xs-12 " type='number' name='cat[<?php print $e->id; ?>]' value='<?php print $e->cal_amount; ?>' readonly='readonly' />
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <h5 class="column-title"><strong>Total :</strong></h5>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="total_allowance"><?php echo $this->lang->line('total'); ?>
                                                    <?php echo $this->lang->line('allowance'); ?></label>
                                                <input class="form-control col-md-7 col-xs-12 " name="total_allowance" id="edit_total_allowance" value="<?php echo $edit_payment->total_allowance; ?>" placeholder="<?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('allowance'); ?>" type="number" readonly="readonly" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('total_allowance'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="total_deduction"><?php echo $this->lang->line('total'); ?>
                                                    <?php echo $this->lang->line('deduction'); ?></label>
                                                <input class="form-control col-md-7 col-xs-12 " name="total_deduction" id="edit_total_deduction" value="<?php echo $edit_payment->total_deduction; ?>" placeholder="<?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('deduction'); ?>" type="number" readonly="readonly" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('total_deduction'); ?></div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="net_salary"><?php echo $this->lang->line('net_salary'); ?>
                                                </label>
                                                <input class="form-control col-md-7 col-xs-12 " name="net_salary" id="edit_net_salary" value="<?php echo $edit_payment->net_salary; ?>" placeholder="<?php echo $this->lang->line('net_salary'); ?>" type="number" readonly="readonly" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('net_salary'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="salary_month"><?php echo $this->lang->line('month'); ?> <span class="required">*</span> </label>
                                                <input class="form-control col-md-7 col-xs-12 edit_salary_month" name="salary_month" id="edit_salary_month" value="<?php echo $edit_payment->salary_month; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('salary_month'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="payment_method"><?php echo $this->lang->line('payment'); ?>
                                                    <?php echo $this->lang->line('method'); ?><span class="required">*</span></label>
                                                <select class="form-control col-md-7 col-xs-12" name="payment_method" id="edit_payment_method" required="required" onchange="check_payment_method(this.value);">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php $payments = get_payment_methods(); ?>
                                                    <?php foreach ($payments as $key => $value) { ?>
                                                        <?php if (in_array($key, array('cash', 'cheque'))) { ?>
                                                            <option value="<?php echo $key; ?>" <?php if (isset($edit_payment) && $edit_payment->payment_method == $key) {
                                                                                                    echo 'selected="selected"';
                                                                                                } ?>>
                                                                <?php echo $value; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <div class="help-block"><?php echo form_error('payment_method'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row fn_cheque <?php if (isset($edit_payment) && $edit_payment->payment_method == 'cash') {
                                                                    echo 'display';
                                                                } ?>">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="bank_name"><?php echo $this->lang->line('bank'); ?>
                                                    <?php echo $this->lang->line('name'); ?><span class="required">*</span>
                                                </label>
                                                <input class="form-control col-md-7 col-xs-12" name="bank_name" id="edit_bank_name" value="<?php echo $edit_payment->bank_name; ?>" placeholder="<?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('bank_name'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="cheque_no"><?php echo $this->lang->line('bank'); ?>
                                                    <?php echo $this->lang->line('cheque'); ?>
                                                    <?php echo $this->lang->line('number'); ?> <span class="required">*</span> </label>
                                                <input class="form-control col-md-7 col-xs-12" name="cheque_no" id="edit_cheque_no" value="<?php echo $edit_payment->cheque_no; ?>" placeholder="<?php echo $this->lang->line('cheque'); ?> <?php echo $this->lang->line('number'); ?>" type="text" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('cheque_no'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="expenditure_head_id"><?php echo $this->lang->line('expenditure_head'); ?><span class="required">*</span> </label>
                                                <select class="form-control col-md-7 col-xs-12" name="expenditure_head_id" id="edit_expenditure_head_id" required="required">
                                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                    <?php foreach ($exp_heads as $obj) { ?>
                                                        <option value="<?php echo $obj->id; ?>" <?php if (isset($edit_payment) && $edit_payment->expenditure_head_id == $obj->id) {
                                                                                                    echo 'selected="selected"';
                                                                                                } ?>>
                                                            <?php echo $obj->title; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="help-block"><?php echo form_error('expenditure_head_id'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="item form-group">
                                                <label for="note"><?php echo $this->lang->line('note'); ?> </label>
                                                <textarea class="form-control col-md-7 col-xs-12" name="note" id="edit_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo $edit_payment->note ?></textarea>
                                                <div class="help-block"><?php echo form_error('note'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" id="edit_school_id" name="school_id" value="<?php echo $school_id; ?>" />
                                            <input type="hidden" id="edit_payment_to" name="payment_to" value="<?php echo $payment_to; ?>" />
                                            <input type="hidden" id="edit_user_id" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" id="edit_salary_grade_id" name="salary_grade_id" value="<?php echo $edit_payment->salary_grade_id; ?>" />
                                            <input type="hidden" id="edit_id" name="id" value="<?php echo $edit_payment->id; ?>" />
                                            <input type="hidden" id="edit_expenditure_id" name="expenditure_id" value="<?php echo $edit_payment->expenditure_id; ?>" />
                                            <input type="hidden" id="edit_hidden_salary_type" value="<?php echo strtolower($edit_payment->salary_type); ?>" />
                                            <a href="<?php echo site_url('payroll/payment/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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
<!-- ak -->
<?php if ($bulk) { ?>
    <?php echo form_open(site_url('payroll/payment/bulk/'), array('class' => 'form-horizontal form-label-left'), ''); ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="select_all_employee"></th>
                <th><?php echo $this->lang->line('sl_no'); ?></th>
                <th><?php echo $this->lang->line('photo'); ?></th>
                <th><?php echo $this->lang->line('name'); ?></th>
                <th><?php echo $this->lang->line('month'); ?></th>
                <th><?php echo $this->lang->line('working_days'); ?></th>
                <th><?php //echo $this->lang->line('total_earnings'); ?>Total Earnings</th>
                <th><?php //echo $this->lang->line('total_expenditures'); ?>Total Expenditures</th>
                <th><?php echo $this->lang->line('payment'); ?>

                <th><?php echo $this->lang->line('net_salary'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php $i = 1; $j=0;?>
                <?php foreach ($bulk as $obj) { ?>
                    <td> <input type="checkbox"  id="employee_select_<?php echo $i ?>" class="employee_select" value="<?php echo $j ?>" name="employees_selected[<?php echo $i ?>]"> </td>
                   
                    <td><?php echo $i ?> </td>

                    <td> <?php if ($obj->photo != '') { ?>
                            <img src="<?php echo UPLOAD_PATH; ?>/<?php echo $path; ?>-photo/<?php echo $obj->photo; ?>" alt="" width="60" />
                        <?php } else { ?>
                            <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="60" />
                        <?php } ?>
                    </td>
                    <td> <?php echo $obj->name ?> </td>

                    <td> <input type="hidden" id="salary_type_<?php echo $i ?>"  name="salary_type[<?php echo $i ?>]" value="<?php echo $obj->salary_type ?> ">
                        <input id="salary_month_<?php echo $i ?>" type="hidden" class="form-control col-md-7 col-xs-12 edit_salary_month" name="salary_month[<?php echo $i ?>]" id="edit_salary_month" value="<?php echo $salary_month; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off"><?php echo $salary_month; ?>
                    </td>


                            
                    <td id="inputs_col_<?php echo $i ?>"> <input name="working_days[<?php echo $i ?>]" class="working_days_bulk" data-id="<?php echo $i ?>" data-userid="<?php echo $obj->user_id ?>" value="<?php echo $work_days[$j]; ?> "> </td>
                    <input type="hidden" name="school_id" value="<?php echo $school_id ?> ">
                    <input type="hidden" id="cal_basic_salary_<?php echo $i ?>" value="<?php echo $obj->basic_salary ?> " name="cal_basic_salary[<?php echo $i ?>]">
                    <input type="hidden"  id="basic_sallary_<?php echo $i ?>" value="<?php echo $obj->basic_salary ?> " name="basic_salary[<?php echo $i ?>]">
                    <input type="hidden"  id="total_allowance_<?php echo $i ?>" value="<?php echo $total_earnings[$j]; ?>" name="total_allowance[<?php echo $i ?>]">
                    <input type="hidden"  id="total_deduction_<?php echo $i ?>" value="<?php echo $total_deduction[$j];  ?>" name="total_deduction[<?php echo $i ?>]">
                    <input type="hidden"  id="user_id_<?php echo $i ?>" value="<?php echo $obj->user_id ?>" name="user_id[<?php echo $i ?>]">
                    <input type="hidden"  id="bank_name_<?php echo $i ?>" name="bank_name[<?php echo $i ?>]">
                    <input type="hidden"  id="cheque_no_<?php echo $i ?>" name="cheque_no[<?php echo $i ?>]">
                    <input type="hidden"   name="payment_date" value="<?php echo $payment_date ?>">
                    <input type="hidden"  value="bulk" name="note[<?php echo $i ?>]">
                    <input type="hidden"  id="payment_to_<?php echo $i ?>" value="<?php echo $payment_to ?>" name="payment_to[<?php echo $i ?>]">

					<input type="hidden"   value="<?php echo $payment_status ?>" name="payment_status[<?php echo $i ?>]">
                    <!-- <input type="hidden"  name="salary_grade_id[]"> -->
					<?php
					
					foreach($earnings[$j] as $en){
                       
                        if($e->set_max_amount_limit && $e->cal_amount > $e->max_amount_possible) $e->cal_amount = $e->max_amount_possible;?>
							
					<input type="hidden"  class="paysclaleinputs_<?php echo $i ?>"  value="<?php echo $en->id;?>" name="earn_name[<?php echo $i;?>][]"/>
					<input type="hidden" class="paysclaleinputs_<?php echo $i ?>" value="<?php echo $en->cal_amount;?>" name="earn_amount[<?php echo $i;?>][]"/>
					<?php
						
					}
					?>

                   

					<?php
					
					foreach($expenditures[$j] as $exp){
						
					?>
					<input type="hidden" class="paysclaleinputs_<?php echo $i ?>"  value="<?php echo $exp->id;?>" name="exp_name[<?php echo $i;?>][]"/>
					<input type="hidden" class="paysclaleinputs_<?php echo $i ?>"  value="<?php echo $exp->cal_amount;?>" name="exp_amount[<?php echo $i;?>][]"/>
					<?php
						
					}
					?>
					<td>
                    <span id="total_earnings_show_<?php echo $i ?>"  ><?php echo $total_earnings[$j] ?></span>
					</td>
					<td>
                    <span id="total_deduction_show_<?php echo $i ?>"  ><?php echo $total_deduction[$j];  ?></span>
					</td>
					<input type="hidden" id="debit_ledger_id_<?php echo $i ?>"  name="debit_ledger_id[<?php echo $i ?>]" value="<?php print $debit_ledger_id; ?>"/>
					<input type="hidden" id="credit_ledger_id<?php echo $i ?>"  name="credit_ledger_id[<?php echo $i ?>]" value="<?php print $credit_ledger_id; ?>"/>
                    <!--<td> <select class="form-control col-md-7 col-xs-12" name="debit_ledger_id[]" id="bulk_edit_debit_ledger_id" required="required">
                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            <?php
                            foreach ($account_ledgers as $ledger) { ?>
                                <option value='<?php print $ledger->id; ?>' <?php if (isset($debit_ledger_id) && $debit_ledger_id == $ledger->id) {
                                                                                echo 'selected="selected"';
                                                                            } ?>> <?php print $ledger->name; ?> </option>
                            <?php  } ?>

                        </select> </td>

                    <td>
                        <select class="form-control col-md-7 col-xs-12" name="credit_ledger_id[]" id="bulk_edit_debit_ledger_id" required="required">
                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            <?php
                            foreach ($account_ledgers as $ledger) { ?>
                                <option value='<?php print $ledger->id; ?>' <?php if (isset($credit_ledger_id) && $credit_ledger_id == $ledger->id) {
                                                                                echo 'selected="selected"';
                                                                            } ?>><?php print $ledger->name; ?></option>
                            <?php  } ?>
                        </select>
                    </td> -->
                    <td> <input name="payment_method[<?php echo $i ?>]" value="cash" type="hidden"> Cash </td>
                    <td> <input id="net_salary_<?php echo $i ?>" name="net_salary[<?php echo $i ?>]" value="<?php echo $net_salary[$j] ?>" type="hidden">₹ 
                        <span id="net_salary_show_<?php echo $i ?>"  ><?php echo $net_salary[$j] ?></span>
            </tr>
        <?php $i++;
		$j++;
                } ?>
        

        </tbody>
    </table>
	<button type="submit">Submit</button>
    <?php echo form_close(); ?>
<?php } ?>




<!-- ak -->


<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('view'); ?>
                    <?php echo $this->lang->line('payment'); ?></h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>


<script>
    $('#user_id').change(function() {
        calculated_days()

    })
    $('#add_salary_month').change(function() {
        calculated_days()

    })
    function calculated_days()
    {
        var month = $('#add_salary_month').val();
        // console.log(month);
        var m = month.split('-')[0];
        var y = month.split('-')[1];
        // console.log(m);
        // console.log(y);
        var days = function(month, year) {
            return new Date(year, month, 0).getDate();
        };
        // console.log(days(m, y));

        function sundaysInMonth(m, y) {
            var days = new Date(y, m, 0).getDate();
            var sundays = [8 - (new Date(m + '/01/' + y).getDay())];
            for (var i = sundays[0] + 7; i < days; i += 7) {
                sundays.push(i);
            }
            return sundays;
        }
        // console.log(sundaysInMonth(m, y).length);

        var totalDays = days(m, y) - sundaysInMonth(m, y).length;
        if (m == 12) {
            totalDays -= 6;
        } else if (m == 4) {
            totalDays -= 1;
        }
        // console.log(totalDays);
        var leaveDays = days(m, y) - totalDays - sundaysInMonth(m, y).length;
        var user_id = $('#user_id').val();
        var role_id = $('#payment_to').val();
        var school_id = $('#school_id').val();
        if(user_id && month && role_id && school_id)
        {
            $.ajax({
            type: "POST",
            url: "<?php echo site_url('payroll/payment/get_leave_days'); ?>",
            dataType: "json",
            data: {
                school_id: school_id,
                user_id: user_id,
                role_id: role_id,
                month: month
            },
            success: function(response) {
                if (response) {
                    var abscence_days = response.abscense_days;
                    var present_days = response.present_days;
                    var paid_leaves = response.paid_leave;
                    totalDays = present_days - paid_leaves;
                    console.log(leaveDays,paid_leaves);
                    leaveDays = parseInt(leaveDays)+parseInt(paid_leaves);
                    
                    $("#add_working_days").val(totalDays);
                    $("#add_absent_days").val(abscence_days);
                    
                    $("#leave_days").val(leaveDays);
                }
            }
            });
        }
        else
        {
            //$("#add_working_days").val(totalDays);
            $("#leave_days").val(leaveDays);
        }
       
        
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#select_all_employee").click(function () {
            $('.employee_select:checkbox').not(this).prop('checked', this.checked);
        });
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

        <?php if ($add == 1) { ?>
            calculated_salary();
        <?php } ?>
    });


    function getPaymentModal(payment_id, payment_to) {

        $('.modal-body').html(
            '<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>'
        );
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('payroll/payment/get_single_payment'); ?>",
            data: {
                payment_id: payment_id,
                payment_to: payment_to
            },
            success: function(response) {
                if (response) {
                    $('.modal-body').html(response);
                }
            }
        });
    }
</script>

<!-- datatable with buttons -->
<script type="text/javascript">
    function daysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }

    function calculated_salary() {
        var bsalary = $("#add_basic_salary").val();
        var working_days = $("#add_working_days").val();
        var month = $("#add_salary_month").val();
        if (month != '' && working_days != '' && working_days < 31) {
            // calculate
            var t = month.split("-");

            var days_in_month = daysInMonth(t[0], t[1]);
            var per_day_salary = bsalary / days_in_month;
            var cal_salary = working_days * per_day_salary;
            $("#cal_basic_salary").val(Math.round(cal_salary));

            console.log(per_day_salary,bsalary,working_days,cal_salary)
        } else {
            $("#cal_basic_salary").val(bsalary);
        }
        // get other income and exp details
        var form_data = $("form#add").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('payroll/payment/detail'); ?>",
            data: {
                form_data
            },
            success: function(response) {
                if (response) {
                    $('#add_detail_data').html(response);
                }
            }
        });
    }
    $('#add_working_days').on('keyup', function() {
        calculated_salary();
    });
    $('#add_salary_month').on('change', function() {
        calculated_salary();
    });
    $("#add_salary_month").datepicker({
        format: "mm-yyyy",
        startView: "months",
        minViewMode: "months",
        startDate: '<?php print $a_start_date; ?>',
		endDate:'<?php print $a_end_date; ?>',
    });
    // $("#payment_date").datepicker({
    //     format: "yyyy-mm-dd",
    //     startDate: '<?php print $f_start_date; ?>',
	// 	endDate:'<?php print $f_end_date; ?>',
    // });

    
    $(".edit_salary_month").datepicker({
        format: "mm-yyyy",
        startView: "months",
        minViewMode: "months",
        startDate: '<?php print $a_start_date; ?>',
		endDate:'<?php print $a_end_date; ?>',
    });



    $("#add").validate();
    $("#edit").validate();
    $('#payment').validate();


    $('.fn_add_claculate').on('keyup', function() {

        var type = $('#add_hidden_salary_type').val();

        if (type === 'monthly') {

            var basic_salary = $('#add_basic_salary').val() ? parseFloat($('#add_basic_salary').val()) : 0;
            var house_rent = $('#add_house_rent').val() ? parseFloat($('#add_house_rent').val()) : 0;
            var transport = $('#add_transport').val() ? parseFloat($('#add_transport').val()) : 0;
            var medical = $('#add_medical').val() ? parseFloat($('#add_medical').val()) : 0;
            var bonus = $('#add_bonus').val() ? parseFloat($('#add_bonus').val()) : 0;

            var ot_hourly_rate = $('#add_over_time_hourly_rate').val() ? parseFloat($('#add_over_time_hourly_rate')
                .val()) : 0;
            var ot_total_hour = $('#add_over_time_total_hour').val() ? parseFloat($('#add_over_time_total_hour')
                .val()) : 0;
            $('#add_over_time_amount').val(ot_hourly_rate * ot_total_hour);
            var ot_total_amount = $('#add_over_time_amount').val() ? parseFloat($('#add_over_time_amount').val()) :
                0;


            var provident_fund = $('#add_provident_fund').val() ? parseFloat($('#add_provident_fund').val()) : 0;
            var penalty = $('#add_penalty').val() ? parseFloat($('#add_penalty').val()) : 0;

            $('#add_total_allowance').val(house_rent + transport + medical + bonus + ot_total_amount);
            var total_allowance = $('#add_total_allowance').val() ? parseFloat($('#add_total_allowance').val()) : 0;

            $('#add_total_deduction').val(provident_fund + penalty);
            var total_deduction = $('#add_total_deduction').val() ? parseFloat($('#add_total_deduction').val()) : 0;

            $('#add_gross_salary').val(basic_salary + total_allowance);
            $('#add_net_salary').val((basic_salary + total_allowance) - total_deduction);

        } else {

            var hourly_rate = $('#add_hourly_rate').val() ? parseFloat($('#add_hourly_rate').val()) : 0;
            var total_hour = $('#add_total_hour').val() ? parseFloat($('#add_total_hour').val()) : 0;

            var bonus = $('#add_bonus').val() ? parseFloat($('#add_bonus').val()) : 0;
            var penalty = $('#add_penalty').val() ? parseFloat($('#add_penalty').val()) : 0;

            $('#add_total_allowance').val(bonus);
            var total_allowance = $('#add_total_allowance').val() ? parseFloat($('#add_total_allowance').val()) : 0;

            $('#add_total_deduction').val(penalty);
            var total_deduction = $('#add_total_deduction').val() ? parseFloat($('#add_total_deduction').val()) : 0;

            $('#add_gross_salary').val((hourly_rate * total_hour) + total_allowance);
            $('#add_net_salary').val((hourly_rate * total_hour) + total_allowance - total_deduction);
        }

    });

    $('.fn_edit_claculate').on('keyup', function() {

        var type = $('#edit_hidden_salary_type').val();

        if (type === 'monthly') {

            var basic_salary = $('#edit_basic_salary').val() ? parseFloat($('#edit_basic_salary').val()) : 0;
            var house_rent = $('#edit_house_rent').val() ? parseFloat($('#edit_house_rent').val()) : 0;
            var transport = $('#edit_transport').val() ? parseFloat($('#edit_transport').val()) : 0;
            var medical = $('#edit_medical').val() ? parseFloat($('#edit_medical').val()) : 0;
            var bonus = $('#edit_bonus').val() ? parseFloat($('#edit_bonus').val()) : 0;

            var ot_hourly_rate = $('#edit_over_time_hourly_rate').val() ? parseFloat($(
                '#edit_over_time_hourly_rate').val()) : 0;
            var ot_total_hour = $('#edit_over_time_total_hour').val() ? parseFloat($('#edit_over_time_total_hour')
                .val()) : 0;
            $('#edit_over_time_amount').val(ot_hourly_rate * ot_total_hour);
            var ot_total_amount = $('#edit_over_time_amount').val() ? parseFloat($('#edit_over_time_amount')
                .val()) : 0;


            var provident_fund = $('#edit_provident_fund').val() ? parseFloat($('#edit_provident_fund').val()) : 0;
            var penalty = $('#edit_penalty').val() ? parseFloat($('#edit_penalty').val()) : 0;

            $('#edit_total_allowance').val(house_rent + transport + medical + bonus + ot_total_amount);
            var total_allowance = $('#edit_total_allowance').val() ? parseFloat($('#edit_total_allowance').val()) :
                0;

            $('#edit_total_deduction').val(provident_fund + penalty);
            var total_deduction = $('#edit_total_deduction').val() ? parseFloat($('#edit_total_deduction').val()) :
                0;

            $('#edit_gross_salary').val(basic_salary + total_allowance);
            $('#edit_net_salary').val((basic_salary + total_allowance) - total_deduction);

        } else {

            var hourly_rate = $('#edit_hourly_rate').val() ? parseFloat($('#edit_hourly_rate').val()) : 0;
            var total_hour = $('#edit_total_hour').val() ? parseFloat($('#edit_total_hour').val()) : 0;

            var bonus = $('#edit_bonus').val() ? parseFloat($('#edit_bonus').val()) : 0;
            var penalty = $('#edit_penalty').val() ? parseFloat($('#edit_penalty').val()) : 0;

            $('#edit_total_allowance').val(bonus);
            var total_allowance = $('#edit_total_allowance').val() ? parseFloat($('#edit_total_allowance').val()) :
                0;

            $('#edit_total_deduction').val(penalty);
            var total_deduction = $('#edit_total_deduction').val() ? parseFloat($('#edit_total_deduction').val()) :
                0;

            $('#edit_gross_salary').val((hourly_rate * total_hour) + total_allowance);
            $('#edit_net_salary').val((hourly_rate * total_hour) + total_allowance - total_deduction);
        }

    });

    function check_payment_method(payment_method) {

        if (payment_method == "cheque") {

            $('.fn_cheque').show();
            $('#bank_name').prop('required', true);
            $('#cheque_no').prop('required', true);

        } else {

            $('.fn_cheque').hide();
            $('#bank_name').prop('required', false);
            $('#cheque_no').prop('required', false);
        }
    }

    <?php if (isset($payment_to) && isset($user_id)) { ?>
        get_user_list('<?php echo $payment_to; ?>', <?php echo $user_id; ?>)
    <?php } ?>

    function get_user_list(payment_to, user_id) {

        var debit_ledger_id = '';
        var credit_ledger_id = '';
        var school_id = $('#school_id').val();
<?php if(isset($debit_ledger_id)){ ?>
debit_ledger_id='<?php print $debit_ledger_id; ?>';
<?php } ?>
<?php if(isset($credit_ledger_id)){ ?>
credit_ledger_id='<?php print $credit_ledger_id; ?>';
<?php } ?>
        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            $('#payment_to').prop('selectedIndex', 0);
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_user_list_by_type'); ?>",
            data: {
                school_id: school_id,
                payment_to: payment_to,
                user_id: user_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    $('#user_id').html(response);
                }
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
                   /* if (debit_ledger_id) {
                        $('#edit_debit_ledger_id').html(response);
                    } else {*/
                        $('#add_debit_ledger_id').html(response);
                    //}
                }
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data: {
                school_id: school_id,
                ledger_id: credit_ledger_id
            },
            async: false,
            success: function(response) {
                if (response) {
                   /* if (credit_ledger_id) {
                        $('#edit_credit_ledger_id').html(response);
                    } else {*/
                        $('#add_credit_ledger_id').html(response);
                    //}
                }
            }
        });
    }
	function update_payment_status(payment_status){
		if(payment_status == 'paid'){
			$("#add_payment_method").show();
		}
		else{
			$("#add_payment_method").hide();
		}
	}
    $(document).on('keyup','.working_days_bulk' ,function(){
        var working_days = $(this).val();
        var index_id = $(this).data('id');
        var payment_to = $('#payment_to_'+index_id).val();
        var user_id = $('#user_id_'+index_id).val();
        var debit_ledger_id = $('#debit_ledger_id_'+index_id).val();
        var credit_ledger_id = $('#credit_ledger_id_'+index_id).val();
        var salary_month = $('#salary_month_'+index_id).val();
        var school_id = $('#school_id').val();
        var data = {
            working_days,
            payment_to,
            user_id,
            school_id,
            debit_ledger_id,
            credit_ledger_id,
            salary_month,
        }
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('/payroll/payment/get_payment_details'); ?>",
            data   : data,       
            dataType: "json",        
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                $('#net_salary_show_'+index_id).html(response.net_salary);
                $('#net_salary_'+index_id).val(response.net_salary);
                
                $('.paysclaleinputs_'+index_id).remove();
                $('#total_earnings_show_'+index_id).html(response.total_earnings);
                var earnings = response.earnings;
                

                var html = '';
                Object.keys(earnings).forEach(function(key){
                    console.log(earnings[key]);

                     html += `<input type="hidden" class="paysclaleinputs_${index_id}"  value="${earnings[key].id}" name="earn_name[${index_id}][]"/>`;
                     html += `<input type="hidden" class="paysclaleinputs_${index_id}" value="${earnings[key].cal_amount}" name="earn_amount[${index_id}][]"/>`
                })
               // $('#net_salary_'+index_id).val(response.net_salary);

                $('#total_deduction_show_'+index_id).html(response.total_deduction);
                $('#cal_basic_salary_'+index_id).val(Math.round(response.cal_salary));
                var deductions = response.expenditure;

                Object.keys(deductions).forEach(function(key){

                    console.log(deductions[key]);

                     html += `<input type="hidden" class="paysclaleinputs_${index_id}"  value="${deductions[key].id}" name="exp_name[${index_id}][]"/>`;
                     html += `<input type="hidden" class="paysclaleinputs_${index_id}" value="${deductions[key].cal_amount}" name="exp_amount[${index_id}][]"/>`
                })
                $('#inputs_col_'+index_id).append(html);

                //$('#net_salary_'+index_id).val(response.net_salary);
                
                // $('#net_salary_show_'+index_id).html(response.net_salary);
                // $('#net_salary_'+index_id).val(response.net_salary);
                                 
               }
            }
        }); 
        
    })
    
</script>
<!-- <script>

    var debit_ledger_id='';     
    var credit_ledger_id='';
    var school_id = $('#school_id').val();

$.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data   : { school_id:school_id, ledger_id:debit_ledger_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(debit_ledger_id){
                       $('#bulk_edit_debit_ledger_id').html(response);   
                   }else{
                       $('#bulk_edit_debit_ledger_id').html(response);   
                   }                                    
               }
            }
        }); 
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data   : { school_id:school_id, ledger_id:credit_ledger_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {  
                   if(credit_ledger_id){
                       $('#edit_credit_ledger_id').html(response);   
                   }else{
                       $('#add_credit_ledger_id').html(response);   
                   }                                    
               }
            }
        }); 

</script> -->