
									   <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong>Earnings :</strong></h5>
                                    </div>
                                </div>
								<div class='row'>
								<?php foreach($earnings as $e){ ?>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="total_allowance"><?php print $e->name; ?></label>
												<input class="form-control col-md-7 col-xs-12 incomeInput " type='number' name='cat[<?php print $e->id; ?>]' value='<?php print $e->cal_amount; ?>'  />
											</div>
								</div>
								<?php }?>
								</div>
								 <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong>Expenditures :</strong></h5>
                                    </div>
                                </div>
								<div class='row'>
								<?php foreach($expenditure as $e){ ?>
								<div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="total_allowance"><?php print $e->name; ?></label>
												<input class="form-control col-md-7 col-xs-12 expInput " type='number' name='cat[<?php print $e->id; ?>]' value='<?php print $e->cal_amount; ?>'   />
											</div>
								</div>
								<?php }?>
								</div>
								<div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong>Total :</strong></h5>
                                    </div>
                                </div>
                                   <div class='row'>    
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="total_allowance"><?php echo $this->lang->line('total'); ?> Earnings </label>
                                                <input  class="form-control col-md-7 col-xs-12 "  name="total_allowance"  id="add_total_allowance" value="<?php echo $total_earnings; ?>" placeholder="<?php echo $this->lang->line('total'); ?> Earnings" type="number" readonly="readonly" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('total_allowance'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="total_deduction"><?php echo $this->lang->line('total'); ?> Expenditures </label>
                                                <input  class="form-control col-md-7 col-xs-12 "  name="total_deduction"  id="add_total_deduction" value="<?php echo $total_deduction; ?>" placeholder="<?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('deduction'); ?>" type="number" readonly="readonly" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('total_deduction'); ?></div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="item form-group">
                                                <label for="net_salary"><?php echo $this->lang->line('net_salary'); ?></label>
                                                <input  class="form-control col-md-7 col-xs-12 "  name="net_salary"  id="add_net_salary" value="<?php echo $net_salary; ?>" placeholder="<?php echo $this->lang->line('net_salary'); ?>" type="number" readonly="readonly" autocomplete="off">
                                                <div class="help-block"><?php echo form_error('net_salary'); ?></div>
                                            </div>
                                        </div>
<script type="text/javascript">
$('.incomeInput, .expInput').on('keyup', function(){
		var incomeTotal=0;
		var expTotal=0;
			$(".incomeInput").each(function(){
				incomeTotal = incomeTotal + parseFloat($(this).val());
			});
			$("#add_total_allowance").val(incomeTotal);
			$(".expInput").each(function(){
				expTotal = expTotal + parseFloat($(this).val());
			})
			$("#add_total_deduction").val(expTotal);
			// net salary
			var cal_basic_salary=parseFloat($("#cal_basic_salary").val());
			var net_salary=(cal_basic_salary + incomeTotal) - expTotal;
			$("#add_net_salary").val(net_salary);
		});
</script>