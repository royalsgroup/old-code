<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $class->school_name; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $class->name; ?></td>
        </tr>       
        <tr>
            <th><?php echo $this->lang->line('teacher'); ?></th>
            <td><?php echo $class->teacher; ?>   </td>
        </tr>   
		<tr>
            <th><?php echo $this->lang->line('numeric'); ?> <?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $class->numeric_name; ?>   </td>
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('is_primary'); ?></th>
            <td><?php echo $class->is_primary; ?>   </td>
        </tr> 		
		<tr>
            <th><?php echo $this->lang->line('academic'); ?> <?php echo $this->lang->line('standards'); ?></th>
            <td><?php echo $class->level; ?>
			<?php if($class->stream != 'None'){ ?>
			 - <?php echo $class->stream; ?>
			<?php } ?>
			 [<?php echo $class->board; ?>]  </td>
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('pass_credit_subjects'); ?></th>
            <td><?php echo $class->pass_credit_subjects; ?>   </td>
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('supplementary_credits_subjects'); ?></th>
            <td><?php echo $class->supplementary_credits_subjects; ?>   </td>
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('supplementary_marks_points_percent'); ?>(%)</th>
            <td><?php echo $class->supplementary_marks_points_percent; ?>   </td>
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('grace_credits_subjects'); ?></th>
            <td><?php echo $class->grace_credits_subjects; ?>   </td>
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('grace_marks_points_percent'); ?>(%)</th>
            <td><?php echo $class->grace_marks_points_percent; ?>   </td>
        </tr> 
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $class->note; ?>   </td>
        </tr>
    </tbody>
</table>
