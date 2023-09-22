 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'administrator', 'financialyear')){ ?>
                   <a href="<?php echo site_url('administrator/financialyear'); ?>"><?php echo $this->lang->line('financial_year'); ?></a> |
                <?php } ?>  
				<?php if(has_permission(VIEW, 'accounting', 'accountgroups')){ ?>
                    <a href="<?php echo site_url('accountgroups'); ?>"><?php echo $this->lang->line('account'). " ".$this->lang->line('group'); ?></a> |
                <?php } ?>
                  <?php if(has_permission(VIEW, 'accounting', 'accountledgers')){ ?>
                                    <a href="<?php echo site_url('accountledgers'); ?>"><?php echo $this->lang->line('account'). " ".$this->lang->line('ledger'); ?></a> |
                                <?php } ?>
								
								<?php if(has_permission(VIEW, 'accounting', 'trialbalance')){ ?>
                                    <a href="<?php echo site_url('trialbalance'); ?>"><?php echo $this->lang->line('trial_balance'); ?></a> |
                                <?php } ?>
								<?php if(has_permission(VIEW, 'accounting', 'balancesheet')){ ?>
                                    <a href="<?php echo site_url('balancesheet'); ?>"><?php echo $this->lang->line('balancesheet'); ?></a> |
                                <?php } ?>
								<?php if(has_permission(VIEW, 'accounting', 'incomestatement')){ ?>
                                    <a href="<?php echo site_url('incomestatement'); ?>"><?php echo $this->lang->line('income_statement'); ?></a> |
                                <?php } ?>
								<?php if(has_permission(VIEW, 'accounting', 'daybook')){ ?>
                                    <a href="<?php echo site_url('daybook'); ?>"><?php echo $this->lang->line('daybook'); ?></a> |
                                <?php } ?>
								<?php if(has_permission(VIEW, 'accounting', 'vouchers')){ ?>
                                    <a href="<?php echo site_url('vouchers'); ?>"><?php echo $this->lang->line('voucher_books'); ?></a> |
                                <?php } ?>								
								<?php if(has_permission(VIEW, 'accounting', 'paymentmodes')){ ?>
                                    <a href="<?php echo site_url('paymentmodes'); ?>"><?php echo $this->lang->line('payment_modes'); ?></a> |
                                <?php } ?>
                             
                                <?php  if(has_permission(VIEW, 'accounting', 'incomehead')){ ?>
                                    <a href="<?php echo site_url('accounting/incomehead'); ?>"><?php echo $this->lang->line('income_head'); ?></a> |
                                <?php } ?>                               
                                <?php if(has_permission(VIEW, 'accounting', 'exphead')){ ?>
                                    <a href="<?php echo site_url('accounting/exphead'); ?>"><?php echo $this->lang->line('expenditure_head'); ?></a>
                                <?php } ?> 