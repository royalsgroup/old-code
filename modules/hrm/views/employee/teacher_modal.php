<form action="<?php echo base_url(); ?>/employee/alumni_teacher" method="POST">
	<input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>" />
	<select class="" name="drop-reason">
    
    <option value="Transfer">Transfer</option>
		<option value="Wrong Entry">Wrong Entry</option>
		<option value="Death">Death</option>
		<option value="Retired">Retired</option>
        <option value="Terminate">Terminate</option>
        <option value="Others">Others</option>
	</select>
	<input type="date" name="drop-date" min="2020-01-01" /><input type="submit" name="submit" value="submit" />
</form>