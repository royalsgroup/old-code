<form action="<?php echo base_url(); ?>/student/dropStudent" method="POST">
	<input type="hidden" name="student_id" value="<?php echo $student_id; ?>" />
	<select class="" name="drop-reason">
		<option value="Wrong Entry">Wrong Entry</option>
		<option value="Continue Absent">Continue Absent</option>
		<option value="Death">Death</option>
	</select>
	<input type="date" name="drop-date" min="2020-01-01" /><input type="submit" name="submit" value="submit" />
</form>