<div class="x_content quick-link">
    <span><?php echo $this->lang->line('quick_link'); ?>:</span>
    <?php if(has_permission(VIEW, 'accounting', 'discount')){ ?>
        <a href="<?php echo site_url('accounting/discount/index'); ?>"><?php echo $this->lang->line('discount'); ?></a>                  
    <?php } ?> 
    
    <?php if(has_permission(VIEW, 'accounting', 'feetype')){ ?>
        | <a href="<?php echo site_url('accounting/feetype/index'); ?>"><?php echo $this->lang->line('fee_type'); ?></a>                  
    <?php } ?> 
    
    <?php if(has_permission(VIEW, 'accounting', 'invoice')){ ?>
        
        <?php if($this->session->userdata('role_id') == STUDENT || $this->session->userdata('role_id') == GUARDIAN){ ?>
            <!-- | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>                     -->
        <?php }else{ ?>
            | <a href="<?php echo site_url('accounting/invoice/add'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?></a>
            | <a href="<?php echo site_url('accounting/invoice/index'); ?>"><?php echo $this->lang->line('manage_invoice'); ?></a>
            <!-- | <a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a>                     -->
        <?php } ?> 
    <?php } ?> 
        
    <?php if(has_permission(VIEW, 'accounting', 'duefeeemail')){ ?>
        | <a href="<?php echo site_url('accounting/duefeeemail/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('email'); ?></a>                  
    <?php } ?>
        <?php if(has_permission(VIEW, 'accounting', 'duefeesms')){ ?>
        | <a href="<?php echo site_url('accounting/duefeesms/index'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('sms'); ?></a>                  
    <?php } ?>         
            
    
</div>