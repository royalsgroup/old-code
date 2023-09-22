<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> </th>
            <td><?php echo $vehicle->school_name; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('vehicle'); ?> <?php echo $this->lang->line('number'); ?> </th>
            <td><?php echo $vehicle->number; ?></td>
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('vehicle_model'); ?></th>
            <td><?php echo $vehicle->model; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('driver'); ?></th>
            <td><?php echo $vehicle->driver; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('vehicle_license'); ?></th>
            <td><?php echo $vehicle->license; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('vehicle_contact'); ?></th>
            <td><?php echo $vehicle->contact; ?></td>
        </tr>
		<tr>
            <th><?php echo $this->lang->line('insurance'); ?> <?php echo $this->lang->line('from'); ?></th>
            <td><?php if($vehicle->insurance_from != null)
			echo date($this->global_setting->date_format, strtotime($vehicle->insurance_from)); ?></td>                  
        </tr>    
		<tr>
            <th><?php echo $this->lang->line('insurance'); ?> <?php echo $this->lang->line('to'); ?></th>
            <td><?php if($vehicle->insurance_to != null)
			echo date($this->global_setting->date_format, strtotime($vehicle->insurance_to)); ?></td>                  
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('pollution'); ?> <?php echo $this->lang->line('from'); ?></th>
            <td><?php if($vehicle->pollution_from != null)
			echo date($this->global_setting->date_format, strtotime($vehicle->pollution_from)); ?></td>                  
        </tr> 
		<tr>
            <th><?php echo $this->lang->line('pollution'); ?> <?php echo $this->lang->line('from'); ?></th>
            <td><?php if($vehicle->pollution_to != null)
			echo date($this->global_setting->date_format, strtotime($vehicle->pollution_to)); ?></td>                  
        </tr> 
		 <tr>
            <th><?php echo $this->lang->line('seat_capacity'); ?></th>
            <td><?php echo $vehicle->seat_capacity; ?></td>
        </tr> 
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $vehicle->note; ?></td>
        </tr>       
        
        <tr>
            <th><?php echo $this->lang->line('created'); ?></th>
            <td><?php echo date($this->global_setting->date_format, strtotime($vehicle->created_at)); ?></td>
        </tr>       
    </tbody>
</table>