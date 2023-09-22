<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="18%"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?></th>
            <td width="32%"><?php echo $grade->school_name; ?></td>       
            <th width="18%"><?php echo $this->lang->line('grade_name'); ?></th>
            <td width="32%"><?php echo $grade->name; ?></td>
        </tr>
		 <tr>
            <th width="18%">Is deduction Type?</th>
            <td width="32%"><?php echo $grade->is_deduction_type; ?></td>       
            <th width="18%"><?php echo $this->lang->line('category'); ?> <?php echo $this->lang->line('type'); ?></th>
            <td width="32%"><?php echo $grade->category_type; ?></td>
        </tr>
        <tr>
            <th width="18%"><?php echo $this->lang->line('amount'); ?> </th>
            <td width="32%"><?php echo $grade->amount; ?></td>       
            <th width="18%"><?php echo $this->lang->line('percentage'); ?> </th>
            <td width="32%"><?php echo $grade->percentage; ?></td>
        </tr>   
<tr>
            <th width="18%"><?php echo $this->lang->line('credit_ledger'); ?> </th>
            <td width="32%"><?php echo $grade->credit_ledger_name; ?></td>       
            <th width="18%"><?php echo $this->lang->line('debit_ledger'); ?> </th>
            <td width="32%"><?php echo $grade->debit_ledger_name; ?></td>
        </tr>		
                                   
    </tbody>
</table>
