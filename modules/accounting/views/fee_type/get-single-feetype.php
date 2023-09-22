<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $feetype->school_name; ?></td>
        </tr>
		<tr>
            <th><?php echo $this->lang->line('credit_ledger'); ?> </th>
            <td><?php echo $feetype->credit_ledger_name; ?></td>
        </tr>
		<tr>
            <th><?php echo $this->lang->line('refund_ledger'); ?></th>
            <td><?php echo $feetype->refund_ledger_name; ?></td>
        </tr>
		<tr>
            <th><?php echo $this->lang->line('voucher'); ?></th>
            <td><?php echo $feetype->voucher_name."(".$feetype->voucher_category.")"; ?></td>
        </tr>
		<tr>
            <th><?php echo $this->lang->line('refundable'); ?>?</th>
            <td><?php echo ($feetype->refundable==1)? 'Yes': 'No'; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('fee_type'); ?> <?php echo $this->lang->line('title'); ?></th>
            <td><?php echo $feetype->title; ?></td>
        </tr>
        <?php if($feetype->head_type == 'fee'){ ?>
        <tr style="background-color: lightgray;">
            <th colspan="2"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('amount'); ?></th>             
            </tr>
            <?php foreach($classes as $obj){ ?>
            <?php $fee_amount = get_fee_amount($feetype->id, $obj->id); ?>
                <tr>
                    <th><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></th>
                    <td><?php echo @$fee_amount->fee_amount; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
            
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $feetype->note; ?>   </td>
        </tr>
    </tbody>
</table>
