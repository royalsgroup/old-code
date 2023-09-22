<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-calculator"></i><small> <?php echo $this->lang->line('manage_fee_type'); ?></small></h3>
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
                                    } ?>"><a href="#tab_feetype_list" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('fee_type'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if (has_permission(ADD, 'accounting', 'feetype')) { ?>
                            <?php if (isset($edit)) { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="<?php echo site_url('accounting/feetype/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('fee_type'); ?></a> </li>
                            <?php } else { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="#tab_add_feetype" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('fee_type'); ?></a> </li>
                            <?php } ?>
                        <?php } ?>

                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_feetype" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('fee_type'); ?></a> </li>
                        <?php } ?>

                        <li class="li-class-list">
                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {  ?>
                                <select class="form-control col-md-7 col-xs-12" onchange="get_feetype_by_school(this.value);">
                                    <option value="<?php echo site_url('accounting/feetype/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option>
                                    <?php foreach ($schools as $obj) { ?>
                                        <option value="<?php echo site_url('accounting/feetype/index/' . $obj->id); ?>" <?php if (isset($filter_school_id) && $filter_school_id == $obj->id) {
                                                                                                                            echo 'selected="selected"';
                                                                                                                        } ?>> <?php echo $obj->school_name; ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </li>

                    </ul>
                    <br />

                    <div class="tab-content">
                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_feetype_list">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) { ?>
                                                <th><?php echo $this->lang->line('school'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('title'); ?></th>
                                            <th><?php echo $this->lang->line('note'); ?></th>
                                            <th>Type</th>
                                            <th><?php echo $this->lang->line('academic_year'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1;
                                        if (isset($feetypes) && !empty($feetypes)) { ?>
                                            <?php foreach ($feetypes as $obj) { 
                                                ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <?php if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) { ?>
                                                        <td><?php echo $obj->school_name; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $obj->title; ?></td>
                                                    <td><?php echo $obj->note; ?></td>
                                                    <td><?php echo ucfirst($obj->head_type); ?></td>

                                                    <td><?php echo  $obj->academic_year_id ?  " ".$obj->session_year : "" ;?></td>

                                                    <td>
                                                        <?php if (has_permission(VIEW, 'accounting', 'feetype')) { ?>
                                                            <a onclick="get_feetype_modal(<?php echo $obj->id; ?>);" data-toggle="modal" data-target=".bs-feetype-modal-lg" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                        <?php  } ?>
                                                        <?php if (has_permission(EDIT, 'accounting', 'feetype')) { ?>
                                                            <a href="<?php echo site_url('accounting/feetype/edit/' . $obj->id); ?>" class="btn btn-success btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(DELETE, 'accounting', 'feetype')) { ?>
                                                            <a href="<?php echo site_url('accounting/feetype/delete/' . $obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade in <?php if (isset($add)) {
                                                            echo 'active';
                                                        } ?>" id="tab_add_feetype">
                            <div class="x_content">
                                <?php echo form_open(site_url('accounting/feetype/add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                <?php $this->load->view('layout/school_list_form'); ?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('credit_ledger'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="add_credit_ledger_id" required='required'>
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('voucher'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="voucher_id" id="add_voucher_id" required='required'>
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('refund_ledger'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="refund_ledger_id" id="add_refund_ledger_id" required='required'>
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('refundable'); ?>? </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="checkbox" name="refundable" value='1' <?php echo (isset($post['refundable']) && $post['refundable'] == 1) ? "checked='checked'" : ''; ?> />
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('fee_type'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="head_type" id="fee_type" onchange="show_class_amount(this.value, false);">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="fee" <?php if (isset($post['head_type']) && $post['head_type'] == 'fee') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('general'); ?> <?php echo $this->lang->line('fee'); ?></option>
                                            <option value="hostel" <?php if (isset($post['head_type']) && $post['head_type'] == 'hostel') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('hostel'); ?> </option>
                                            <option value="transport" <?php if (isset($post['head_type']) && $post['head_type'] == 'transport') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php echo $this->lang->line('transport'); ?> </option>
                                             <option value="other" <?php if (isset($post['head_type']) && $post['head_type'] == 'other') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php echo $this->lang->line('other'); ?> </option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('head_type'); ?></div>
                                    </div>
                                </div>
                                <!-- ak -->


                                <!-- <div class="item form-group akash1">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('transport'); ?> <?php echo $this->lang->line('type'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="transport" id="transport">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="one_way" <?php if (isset($feetype->head_type) && $feetype->head_type == 'fee') {
                                                                        echo 'selected="selected"';
                                                                    } ?>>One Way</option>
                                            <option value="both_way" <?php if (isset($feetype->head_type) && $feetype->head_type == 'hostel') {
                                                                            echo 'selected="selected"';
                                                                        } ?>> Both Way </option>

                                        </select>
                                        <div class="help-block"><?php echo form_error('head_type'); ?></div>
                                    </div>
                                </div> -->

                                <!-- <div class="item form-group akash1">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('transport'); ?> Amount  <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="transport_amount"  id="transport_amount" value="<?php echo isset($post['transport_amount']) ?  $post['transport_amount'] : ''; ?>" placeholder="<?php echo $this->lang->line('transport'); ?> Amount" required="required" type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>  -->

                                <!-- ak -->

                                <div class="item form-group fn_instruction display">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type">&nbsp;</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="instructions" style="padding: 5px; margin:20px 0px 20px 0px;"><strong><?php echo $this->lang->line('instruction'); ?>: </strong>
                                            <ol>
                                                <li><?php echo $this->lang->line('fee_type_instruction_hostel_1'); ?></li>
                                                <li><?php echo $this->lang->line('fee_type_instruction_hostel_2'); ?></li>
                                                <li><?php echo $this->lang->line('fee_type_instruction_transport_1'); ?></li>
                                                <li><?php echo $this->lang->line('fee_type_instruction_transport_2'); ?></li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('title'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="title" id="title" value="<?php echo isset($post['title']) ?  $post['title'] : ''; ?>" placeholder="<?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_type"><?php echo $this->lang->line('emi'); ?> Type </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" name="emi_type" id="add_emi_type" >

                                            <option value="percentage" <?php if (!isset($post['emi_type']) || ( isset($post['emi_type']) && $post['emi_type'] == 'percentage') ){
                                                                            echo 'selected="selected"';
                                                                        } ?>>Percentage</option>
                                             <!-- <option value="amount" <?php if (isset($post['emi_type']) && $post['emi_type'] == 'amount') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>Amount</option>   -->
                                        </select>
                                    </div>
                                </div> 
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per"><?php echo $this->lang->line('emi'); ?> Name </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="emi_name[]" id="emi_name" value="" placeholder="EMI Name" type="text" autocomplete="off">

                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per"><?php echo $this->lang->line('emi'); ?>  (%) </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12 akash" name="emi_per[]" id="emi_per" value="" placeholder="EMI " type="text" autocomplete="off">

                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month">Start Month
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12 add_single_month" name="emi_start_date[]" id="" value="" placeholder="End Month" type="text" autocomplete="off">

                                    </div>

                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month">End Month
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12 add_single_month" name="emi_end_date[]" id="" value="" placeholder="End Month" type="text" autocomplete="off">

                                    </div>
                                    <button type="button" class="btn btn_primary" id="harry1">Add More</button>
                                </div>

                                <div class="emi_data"></div>


                                <div class="item form-group fn_amount_head display">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for=""><?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('name'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 6px;">
                                        <strong>: <?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?></strong>
                                    </div>
                                </div>

                                <div class="fn_add_classes_block display">
                                    <?php if (isset($classes) && !empty($classes)) { ?>
                                        <?php foreach ($classes as $obj) { ?>
                                            <div class="item form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="<?php $obj->name; ?>"><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?> <span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="hidden" name="class_id[<?php echo $obj->id; ?>]" id="<?php echo $obj->id; ?>" value="<?php echo $obj->id; ?>" />
                                                    <input type="text" class="form-control col-md-7 col-xs-12" name="fee_amount[<?php echo $obj->id; ?>]" id="<?php echo $obj->id; ?>" value="" required="required" />
                                                    <div class="help-block"><?php echo form_error($obj->name); ?></div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>

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
                                        <a href="<?php echo site_url('accounting/feetype'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>

                            </div>
                        </div>

                        <?php if (isset($edit)) { ?>
                            <div class="tab-pane fade in active" id="tab_edit_feetype">
                                <div class="x_content">
                                    <?php echo form_open(site_url('accounting/feetype/edit/' . $feetype->id), array('name' => 'edit', 'id' => 'edit', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_edit_form'); ?>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('credit_ledger'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="credit_ledger_id" id="edit_credit_ledger_id" required='required'>
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('voucher'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="voucher_id" id="edit_voucher_id" required='required'>
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('refund_ledger'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="refund_ledger_id" id="edit_refund_ledger_id" required='required'>
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('refundable'); ?>? </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="checkbox" name="refundable" value='1' <?php echo (isset($feetype->refundable) && $feetype->refundable == 1) ? "checked='checked'" : ''; ?> />
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type"><?php echo $this->lang->line('fee_type'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="head_type" id="fee_type" onchange="show_class_amount(this.value, true);">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="fee" <?php if (isset($feetype->head_type) && $feetype->head_type == 'fee') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('general'); ?> <?php echo $this->lang->line('fee'); ?></option>
                                                <option value="hostel" <?php if (isset($feetype->head_type) && $feetype->head_type == 'hostel') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php echo $this->lang->line('hostel'); ?> </option>
                                                <option value="transport" <?php if (isset($feetype->head_type) && $feetype->head_type == 'transport') {
                                                                                echo 'selected="selected"';
                                                                            } ?>><?php echo $this->lang->line('transport'); ?> </option>
                                                <option value="other" <?php if (isset($feetype->head_type) && $feetype->head_type == 'other') {
                                                                                echo 'selected="selected"';
                                                                            } ?>><?php echo $this->lang->line('other'); ?> </option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('head_type'); ?></div>
                                        </div>
                                    </div>


                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('title'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="title" id="title" value="<?php echo isset($feetype->title) ?  $feetype->title : ''; ?>" placeholder="<?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('title'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_type">Installment Type </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" name="emi_type" id="edit_emi_type" >

                                            <option value="percentage" <?php if (!isset($feetype->emi_type) || ( isset($feetype->emi_type) && $feetype->emi_type == 'percentage') ){
                                                                            echo 'selected="selected"';
                                                                        } ?>>Percentage</option>
                                             <option value="amount" <?php if (isset($feetype->emi_type) && $feetype->emi_type == 'amount') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>Amount</option>
                                        </select>
                                    </div>
                                    </div> 

                                <div class="item1 form-group">
                                    <?php if (isset($emitypes)) { ?>
                                        <?php foreach ($emitypes as $key => $value) { ?>
                                      <div class="item1 form-group">
                                            <div class="item1 form-group" data-id="<?php echo $value['id'] ?>">
                                                <div class="item1 form-group"> 
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per"><?php echo $this->lang->line('emi'); ?> Name </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control col-md-7 col-xs-12" name="emi_name[]" id="emi_name" value="<?php echo isset($value['emi_name']) ?  $value['emi_name'] : ''; ?>" placeholder="EMI Name" type="text" autocomplete="off">

                                                    </div>
                                                </div>
                                                <div class="item1 form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per"><?php echo $this->lang->line('emi'); ?>  (%)</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control col-md-7 col-xs-12 akash1" name="emi_per[]" id="emi_per" value="<?php echo isset($value['emi_per']) ?  $value['emi_per'] : ''; ?>" placeholder="EMI " type="text" autocomplete="off">

                                                    </div>
                                                </div>
                                                <div class="item1 form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month">Start Month
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control col-md-7 col-xs-12 add_single_month" name="emi_start_date[]" value="<?php echo isset($value['emi_start_date']) ?  $value['emi_start_date'] : ''; ?>" placeholder="End Month" type="text" autocomplete="off">

                                                    </div>

                                                </div>
                                                <div class="item1 form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_month">End Month
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control col-md-7 col-xs-12 add_single_month" name="emi_end_date[]" value="<?php echo isset($value['emi_end_date']) ?  $value['emi_end_date'] : ''; ?>" placeholder="End Month" type="text" autocomplete="off">

                                                    </div>
                                                </div>

                                                <input type="hidden" name="emi_id[]" value="<?php echo isset($value['emi_end_date']) ?  $value['id'] : ''; ?>">
                                                <button type="button"  class="btn btn-danger remove" >-</button>
                                            </div>
                                        </div>    
                                        <?php } ?>
                                        
                                    <?php } ?>
                                </div> 

                                      <button type="button" class="btn btn_primary" id="edit1">Add More</button>
                                <div class="emi_data_edit"></div>



                                    <div class="item form-group fn_amount_head <?php if (isset($feetype) && ($feetype->head_type != 'fee' && $feetype->head_type != 'other') ) {
                                                                                    echo 'display';
                                                                                } ?>">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for=""><?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('name'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 6px;">
                                            <strong>: <?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?></strong>
                                        </div>
                                    </div>

                                    <div class="fn_edit_classes_block <?php if (isset($feetype) && ($feetype->head_type != 'fee' && $feetype->head_type != 'other') ) {
                                                                            echo 'display';
                                                                        } ?>">
                                        <?php if (isset($classes) && !empty($classes)) { ?>
                                            <?php foreach ($classes as $obj) { ?>
                                                <div class="item form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="<?php $obj->name; ?>"><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?> </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <?php $fee_amount = get_fee_amount($feetype->id, $obj->id); ?>
                                                        <input type="hidden" name="amount_id[<?php echo $obj->id; ?>]" value="<?php echo @$fee_amount->id; ?>" />
                                                        <input type="hidden" name="class_id[<?php echo $obj->id; ?>]" value="<?php echo $obj->id; ?>" />
                                                        <input type="text" class="form-control col-md-7 col-xs-12" name="fee_amount[<?php echo $obj->id; ?>]" id="<?php echo $obj->id; ?>" value="<?php echo @$fee_amount->fee_amount; ?>" />
                                                        <div class="help-block"><?php echo form_error($obj->name); ?></div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>


                                    <div class="item form-group fn_instruction <?php if (isset($feetype) && $feetype->head_type != 'fee') {
                                                                                    echo 'display';
                                                                                } ?>">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_type">&nbsp;</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="instructions" style="padding: 5px; margin:20px 0px 20px 0px;"><strong><?php echo $this->lang->line('instruction'); ?>: </strong>
                                                <ol>
                                                    <li><?php echo $this->lang->line('fee_type_instruction_hostel_1'); ?></li>
                                                    <li><?php echo $this->lang->line('fee_type_instruction_hostel_2'); ?></li>
                                                    <li><?php echo $this->lang->line('fee_type_instruction_transport_1'); ?></li>
                                                    <li><?php echo $this->lang->line('fee_type_instruction_transport_2'); ?></li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control col-md-7 col-xs-12" name="note" id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($feetype->note) ?  $feetype->note : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('note'); ?></div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="<?php echo isset($feetype) ? $feetype->id : $id; ?>" name="id" />
                                            <a href="<?php echo site_url('accounting/feetype'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


<div class="modal fade bs-feetype-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('type'); ?> <?php echo $this->lang->line('information'); ?></h4>
            </div>
            <div class="modal-body fn_feetype_data">
            </div>
        </div>
    </div>
</div>

<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>


<script type="text/javascript">
    function get_feetype_modal(feetype_id) {

        $('.fn_feetype_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/feetype/get_single_feetype'); ?>",
            data: {
                feetype_id: feetype_id
            },
            success: function(response) {
                if (response) {
                    $('.fn_feetype_data').html(response);
                }
            }
        });
    }
</script>





<!-- Super admin JS -->
<script type="text/javascript">
    $("document").ready(function() {
        <?php if (isset($school_id) && !empty($school_id)) { ?>
            if ($("#edit_school_id").length == 0) {
                $(".fn_school_id").trigger('change');
            } else {
                $("#edit_school_id").trigger('change');
            }


            //$("#edit_school_id").trigger('change');
        <?php } ?>
    });

    $('.fn_school_id').on('change', function() {

        var school_id = $(this).val();
        var fee_type_id = '';
        var credit_ledger_id = '';
        var refund_ledger_id = '';
        var voucher_id = '';
        <?php if (isset($school_id) && !empty($school_id)) { ?>
            fee_type_id = '<?php echo $feetype->id; ?>';
            credit_ledger_id = '<?php echo $feetype->credit_ledger_id; ?>';
            refund_ledger_id = '<?php echo $feetype->refund_ledger_id; ?>';
            voucher_id = '<?php echo $feetype->voucher_id; ?>';
        <?php } ?>

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>');
            return false;
        }


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('accounting/feetype/get_fee_head_by_school'); ?>",
            data: {
                school_id: school_id,
                fee_type_id: fee_type_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (fee_type_id) {
                        $('.fn_edit_classes_block').html(response);
                    } else {
                        $('.fn_add_classes_block').html(response);
                    }
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
                    if (credit_ledger_id) {
                        $('#edit_credit_ledger_id').html(response);
                    } else {
                        $('#add_credit_ledger_id').html(response);
                    }
                }
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_accountledger_by_school'); ?>",
            data: {
                school_id: school_id,
                ledger_id: refund_ledger_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (refund_ledger_id) {
                        $('#edit_refund_ledger_id').html(response);
                    } else {
                        $('#add_refund_ledger_id').html(response);
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


    function show_class_amount(fee_type, is_edit) {

        if ((fee_type == 'fee' || fee_type == 'other') && is_edit == true) {

            $('.fn_amount_head').show();
            $('.fn_edit_classes_block').show();
            $('.fn_instruction').hide();
            $('.akash1').hide();

        } else if ((fee_type == 'fee' || fee_type == 'other') && is_edit == false) {

            $('.fn_amount_head').show();
            $('.fn_add_classes_block').show();
            $('.fn_instruction').hide();
            $('.akash1').hide();

        } else if (fee_type == 'hostel') {

            $('.fn_amount_head').hide();
            $('.fn_add_classes_block').hide();
            $('.fn_edit_classes_block').hide();
            $('.akash1').hide();
            $('.fn_instruction').show();
        } else if (fee_type == 'transport') {
            $('.fn_instruction').hide();
            $('.fn_amount_head').hide();
            $('.fn_add_classes_block').hide();
            $('.fn_edit_classes_block').hide();
            $('.akash1').show();
        }
    }
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
    $("#edit").validate();

    function get_feetype_by_school(url) {
        if (url) {
            window.location.href = url;
        }
    }

    $(".add_single_month").datepicker({
        format: "yyyy-mm-dd",
       
    });
    $(document).on('change', '#emi_type', function(e) {
    var that = e.target;
    if(that.value == "amount")
    {
        $('.percentage').hide();
    }
    else
    {
        $('.percentage').show();
    }
    })
    $(document).on('click', '#harry1', function(e) {

        var element = '<br> <div class="item1 form-group"> ';
        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per"><?php echo $this->lang->line('emi'); ?> Name </label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <input  class="form-control col-md-7 col-xs-12"  name="emi_name[]"   value="" placeholder="<?php echo $this->lang->line('emi'); ?> Name"  type="text" autocomplete="off">';
        element += '</div>';
        element += ' </div>';



        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per"><?php echo $this->lang->line('emi'); ?>  </label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <div class=""> ';
        element += ' <input  class="form-control col-md-7 col-xs-12 akash akash1"  name="emi_per[]"   value="" placeholder="<?php echo $this->lang->line('emi'); ?> (%)" type="text" autocomplete="off">';
        element += '</div>';
        element += '</div>';
        element += '</div>';


        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" >Start Month </label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <div class=""> ';
        element += '<input  class="form-control col-md-7 col-xs-12 add_single_month"  name="emi_start_date[]"   value="" placeholder="End Month" type="text" autocomplete="off">';
        element += '</div>';
        element += '</div>';
        element += '</div>';

        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" >End Month</label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <div class=""> ';
        element += '<input  class="form-control col-md-7 col-xs-12 add_single_month"  name="emi_end_date[]"   value="" placeholder="End Month"  type="text" autocomplete="off">';
        element += '</div>';
        element += '</div>';
        element += '<button class="btn btn-danger remove" >-</button>';
        element += '</div><br>';



        element += '</div>';

        $('.emi_data').append(element);

        $(".add_single_month").datepicker({
            format: "yyyy-mm-dd",
          
        });

    });
    $(document).on('click', '.remove', function() {
        <?php if (isset($edit)) { ?>
        var emi_id = $(this).parent().data('id');

        if(typeof emi_id != "undefined")
        {
            confirm_delete = confirm("Are you sure to delete this Installment ?");
            if(confirm_delete)
            {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('accounting/feetype/delete_emi'); ?>",
                    data: {
                        emi_id: emi_id
                    },
                    async: false,
                    success: function(response) {
                    
                    }
                });
            }
           
        }
       
        <?php } ?>
    $(this).parent().parent().remove();
    });

    $(document).on('change', '.akash', function() {
        var akash = $(".akash");

        var method = '<?php echo isset($edit)  ? 'edit' : 'add' ?>';
        console.log(method);

        var emi_type = $("#"+method+"_emi_type").val();
        if(emi_type == 'amount')
        {

        }
        else
        {
            var x = [];
            var sum = 0;

            for (var i = 0; i < akash.length; i++) {
                x[i] = $(akash[i]).val();
                x[i] = parseInt(x[i]);
                sum += x[i];

            }
            if (sum >= 101) {
                alert("Invalid EMI Amount");
                $('.akash').css('border', 'solid 1px red');
                $('button[type="submit"]').attr('disabled', 'true');
            } else {
                $('button[type="submit"]').removeAttr('disabled');
                $('.akash').css('border', 'solid 1px #d2d6de');
            }

        }
       


    });


     $(document).on('click', '#edit1', function(e) {

        var element = '<br> <div class="item1 form-group"> ';
        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per">EMI Name </label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <input  class="form-control col-md-7 col-xs-12"  name="emi_name[]"   value="" placeholder="EMI Name"  type="text" autocomplete="off">';
        element += '</div>';
        element += ' </div>';



        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="emi_per">EMI (%)</label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <div class=""> ';
        element += ' <input  class="form-control col-md-7 col-xs-12 akash1"  name="emi_per[]"   value="" placeholder="EMI (%)" type="text" autocomplete="off">';
        element += '</div>';
        element += '</div>';
        element += '</div>';


        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" >Start Month </label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <div class=""> ';
        element += '<input  class="form-control col-md-7 col-xs-12 add_single_month"  name="emi_start_date[]"   value="" placeholder="End Month" type="text" autocomplete="off">';
        element += '</div>';
        element += '</div>';
        element += '</div>';

        element += ' <div class="item1 form-group"> ';
        element += '<label class="control-label col-md-3 col-sm-3 col-xs-12" >End Month</label>';
        element += '<div class="col-md-6 col-sm-6 col-xs-12">';
        element += ' <div class=""> ';
        element += '<input  class="form-control col-md-7 col-xs-12 add_single_month"  name="emi_end_date[]"   value="" placeholder="End Month"  type="text" autocomplete="off">';
        element += '</div>';
        element += '</div>';
        element += '<button type="button" class="btn btn-danger remove" >-</button>';
        element += '</div><br>';



        element += '</div>';

        $('.emi_data_edit').append(element);

        $(".add_single_month").datepicker({
            format: "yyyy-mm-dd",
            
        });


    });

       $(document).on('change', '.akash1', function() {
        var method = '<?php echo isset($edit)  ? 'edit' : 'add' ?>';
        console.log(method);

        var emi_type = $("#"+method+"_emi_type").val();
        if(emi_type == 'amount')
        {

        }
        else
        {
            var akash = $(".akash1");
            var x = [];
            var sum = 0;

            for (var i = 0; i < akash.length; i++) {
                x[i] = $(akash[i]).val();
                x[i] = parseInt(x[i]);
                sum += x[i];

            }

            if (sum >= 101) {
                alert("Invalid EMI Amount");
                $('.akash1').css('border', 'solid 1px red');
                $('button[type="submit"]').attr('disabled', 'true');
            } else {
                $('button[type="submit"]').removeAttr('disabled');
                $('.akash1').css('border', 'solid 1px #d2d6de');
            }
        }



    });
</script>