<form action="<?php echo base_url(); ?>hrm/employee/alumni_employee" method="POST">
	<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
	<select class="" name="drop-reason" id="drop_reason">
    <option value="Wrong Entry">Wrong Entry</option>
    <?php if(!empty($schools)) { ?> <option value="Transfer">Transfer</option> <?php } ?>
		<option value="Death">Death</option>
		<option value="Retired">Retired</option>
        <option value="Terminate">Terminate</option>
        <option value="Others">Others</option>
	</select>
    <select id="school_select" style="display:none" name="transfer_school_id">
    <?php foreach($schools as $school){  
        echo '<option value="'.$school->id.'">'.$school->school_name.'</option>';
    } ?>
	</select>
    
	<input type="date" name="drop-date" min="2020-01-01" /><input type="submit" name="submit" value="submit" />
</form>
<script>
$('#drop_reason').on('change',function(){
    if(this.value == "Transfer")
    {
        $('#school_select').show();
    }
    else
    {
        $('#school_select').hide();
    }
})
    </script>