<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?> </th>
            <td><?php echo $material->school_name; ?></td>
        </tr>        
        <tr>
            <th><?php echo $this->lang->line('material'); ?> <?php echo $this->lang->line('title'); ?></th>
            <td><?php echo $material->title; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('class'); ?></th>
            <td><?php echo $material->class_name; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('subject'); ?></th>
            <td><?php echo $material->subject; ?></td>
        </tr>
        <?php if($material->material){ ?>
            <tr>
                <th><?php echo $this->lang->line('material'); ?></th>
                <td>
                    <a target="_blank" href="<?php echo UPLOAD_PATH; ?>/material/<?php echo $material->material; ?>" class="btn btn-success btn-xs"> <i class="fa fa-download"></i> <?php echo $this->lang->line('download'); ?></a> <br/>
                    <iframe 
                        src="http://docs.google.com/gview?url=<?php echo UPLOAD_PATH; ?>/material/<?php echo $material->material; ?>&embedded=true"
                        style="width:100%; height:500px;"
                        frameborder="0">
                    </iframe>
                </td>
            </tr>        
        <?php } ?> 
        <tr>
            <th><?php echo $this->lang->line('description'); ?></th>
            <td><?php echo $material->description; ?>   </td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('upload'); ?> <?php echo $this->lang->line('date'); ?></th>
            <td><?php echo date($this->global_setting->date_format, strtotime($material->created_at)); ?></td>
        </tr>
    </tbody>
</table>
