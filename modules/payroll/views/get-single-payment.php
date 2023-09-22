<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?></th>
            <td ><?php echo $payment->school_name; ?></td>        
            <th><?php echo $this->lang->line('month'); ?></th>
            <td ><?php echo date('M Y', strtotime('1-'.$payment->salary_month)); ?></td>
        </tr>
        
        <tr>          
            <th><?php echo $this->lang->line('salary_type'); ?></th>
            <td ><?php echo $this->lang->line(strtolower($payment->salary_type)); ?></td>
			<?php if(strtolower($payment->salary_type) == 'monthly'){ ?>
			<th><?php echo $this->lang->line('basic_salary'); ?></th>
                <td ><?php echo $payment->basic_salary; ?></td>   
			 <?php } ?>
        </tr>
		<?php if(strtolower($payment->salary_type) == 'monthly'){ ?>
		 <tr>          
            <th><?php echo $this->lang->line('working_days'); ?></th>
            <td ><?php echo $payment->working_days; ?></td>
			
			<th><?php echo $this->lang->line('calculated_basic_salary'); ?></th>
                <td ><?php echo $payment->cal_basic_salary; ?></td>   			 
        </tr>
		<?php } ?>
         <?php $i=0; foreach($payment_detail as $pd){
			 if($i%2 == 0){ ?> <tr><?php } ?>
				 <th><?php print $pd->cat_name; ?></th>
				 <td><?php print $pd->amount; ?></td>
			<?php $i++; if($i%2 == 0){ ?> </tr><?php } ?>
		 <?php   } ?>           
        <tr>
            <th><?php echo $this->lang->line('total_allowance'); ?></th>
            <td ><?php echo $payment->total_allowance; ?></td>        
            <th><?php echo $this->lang->line('total_deduction'); ?> </th>
            <td ><?php echo $payment->total_deduction; ?></td>
        </tr>
        
        <tr>    
			<th><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('method'); ?></th>
            <td><?php echo $this->lang->line($payment->payment_method); ?></td>		
            <th><?php echo $this->lang->line('net_salary'); ?></th>
            <td ><?php echo $payment->net_salary; ?></td>
        </tr>
                
        
        <?php if($payment->payment_method == 'cheque'){ ?>
            <tr>
                <th><?php echo $this->lang->line('bank'); ?> <?php echo $this->lang->line('name'); ?></th>
                <td ><?php echo $payment->bank_name; ?></td>            
                <th><?php echo $this->lang->line('cheque'); ?></th>
                <td ><?php echo $payment->cheque_no; ?></td>
            </tr>
        <?php } ?> 
            
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $payment->note; ?></td>
			<th><?php echo $this->lang->line('payment')." ".$this->lang->line('status'); ?></th>
			<td <?php print ($payment->payment_status == 'unpaid')? "style='color:red;font-weight:bold;'" : ''; ?>><?php print $payment->payment_status; ?></td>
        </tr>
		
            
    </tbody>
</table>