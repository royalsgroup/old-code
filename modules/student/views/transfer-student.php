<form action="<?php echo base_url(); ?>/student/transferStudent" method="POST">
	<input type="hidden" name="student_id" value="<?php echo $student_id; ?>" />
	<select class="" name="transfer-reason">
		<option value="Due to study at other place/elsewhere">Due to study at other place/elsewhere</option>
		<option value="Due to higher education">Due to higher education</option>
		<option value="Due to lack of faculty in the school">Due to lack of faculty in the school</option>
		<option value="Due to more distance from school">Due to more distance from school</option>
		<option value="Due to regular/persistent absence">Due to regular/persistent absence</option>
		<option value="Due to transfer of parent">Due to transfer of parent</option>
		<option value="Disciplinary reason">Disciplinary reason</option>
		<option value="Due to less than eligibility criteria/prescribed qualification">Due to less than eligibility criteria/prescribed qualification</option>
	</select>
	<input type="date" name="transfer-date" min="2020-01-01" /><input type="submit" name="submit" value="submit" />
</form>