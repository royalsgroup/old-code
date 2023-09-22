<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
<?php 
if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){
$schools = get_school_list();
}
else if($this->session->userdata('dadmin') == 1){
	$schools = get_school_list($this->session->userdata('district_id'));
}
$required = $this->router->fetch_class() == "report" ? "" : 'required="required"';
 ?>
<div class="col-md-3 col-sm-3 col-xs-12" id="school_filter_col">
    <div class="item form-group"> 
        <div><?php echo $this->lang->line('school'); ?> <span class="required"> *</span></div>
        <select  class="form-control col-md-7 col-xs-12 fn_school_id" name="school_id" id="school_id" <?php echo $required; ?>>
            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
			<option value="">--<?php echo $this->lang->line('all'); ?>--</option>
            <?php foreach($schools as $obj){ ?>
                <option value="<?php echo $obj->id; ?>" <?php if(isset($school_id) && $school_id == $obj->id){echo 'selected="selected"';} ?>><?php echo $obj->school_name; ?></option>
            <?php } ?>
        </select>       
    </div>
</div>
<?php }else{ ?>  
<input type="hidden" class="fn_school_id" name="school_id" id="school_id" value="<?php echo $this->session->userdata('school_id'); ?>" />
<?php } ?>
