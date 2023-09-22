<div class="x_content quick-link">
<span><?php echo $this->lang->line('quick_link'); ?>:</span>
               
<?php if(has_permission(VIEW, 'inventory', 'itemcategory')){ ?>    
                     <?php if(has_permission(VIEW, 'itemgroup', 'itemgroup')){ ?>
                      <a href="<?php echo site_url('itemgroup/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('group'); ?></a>  |                                           
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemcategory')){ ?>
                           <a href="<?php echo site_url('itemcategory/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('category'); ?></a> |                                         
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemstore')){ ?>
                            <a href="<?php echo site_url('itemstore/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('store'); ?></a>  |                                          
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemsupplier')){ ?>
                      <a href="<?php echo site_url('itemsupplier/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('supplier'); ?></a>  |                                           
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'item')){ ?>
                           <a href="<?php echo site_url('item/'); ?>"><?php echo $this->lang->line('item'); ?> </a> |                                               
                         <?php } ?>
                         <?php if(has_permission(VIEW, 'inventory', 'itemstock')){ ?>
                             <a href="<?php echo site_url('itemstock/'); ?>"><?php echo $this->lang->line('itemstock'); ?> </a>  |                                           
                         <?php } ?>
                     <?php if(has_permission(VIEW, 'inventory', 'issueitem')){ ?>
                         <a href="<?php echo site_url('issueitem/'); ?>"><?php echo $this->lang->line('issueitem'); ?> </a> |                      
                         <?php } ?>
                     
                        
                       
                     
                        
                      
             <?php } ?>
</div>
