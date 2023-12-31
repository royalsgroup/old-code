<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>     
        <tr>
            <th><?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $districtadmin->name; ?></td>        
            <th><?php echo $this->lang->line('national_id'); ?></th>
            <td><?php echo $districtadmin->national_id; ?></td>
        </tr>
        <tr>
           <th><?php echo $this->lang->line('role'); ?></th>
            <td><?php echo $districtadmin->role; ?></td>     
            <th><?php echo $this->lang->line('phone'); ?></th>
            <td><?php echo $districtadmin->phone; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?></th>
            <td><?php echo $districtadmin->present_address; ?></td>        
            <th><?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?></th>
            <td><?php echo $districtadmin->permanent_address; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('gender'); ?></th>
            <td><?php echo $this->lang->line($superadmin->gender); ?></td>       
            <th><?php echo $this->lang->line('blood_group'); ?></th>
            <td><?php echo $this->lang->line($superadmin->blood_group); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('religion'); ?></th>
            <td><?php echo $districtadmin->religion; ?></td>       
            <th><?php echo $this->lang->line('birth_date'); ?></th>
            <td><?php echo date($this->global_setting->date_format, strtotime($superadmin->dob)); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('email'); ?></th>
            <td><?php echo $districtadmin->email; ?></td>        
            <th><?php echo $this->lang->line('other_info'); ?></th>
            <td><?php echo $districtadmin->other_info; ?></td>
        </tr>

        <tr>
            <th><?php echo $this->lang->line('photo'); ?></th>
            <td>
                
                <?php  if($superadmin->photo != ''){ ?>
                     <img src="<?php echo UPLOAD_PATH; ?>/employee-photo/<?php echo $districtadmin->photo; ?>" alt="" width="70" /> 
                 <?php }else{ ?>
                     <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="70" /> 
                 <?php } ?>
                   
            </td>
            <th><?php echo $this->lang->line('resume'); ?></th>
            <td>
                <?php if($superadmin->resume){ ?>
                   <a target="_blank" href="<?php echo UPLOAD_PATH; ?>/employee-resume/<?php echo $districtadmin->resume; ?>" class="btn btn-success btn-xs"><i class="fa fa-download"></i> <?php echo $this->lang->line('download'); ?></a>
                <?php } ?>
            </td>
        </tr>  
		<tr>
            <th><?php echo $this->lang->line('district'); ?></th>
            <td><?php echo $districtadmin->district_name; ?></td>                    
        </tr>


    </tbody>
</table>