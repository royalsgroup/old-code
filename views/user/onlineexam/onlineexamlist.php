<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-slideshare"></i><small> <?php echo $this->lang->line('online'). " ".$this->lang->line('exam'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
			 <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('exam');?></th>
                                        <th><?php echo $this->lang->line('date')." ".$this->lang->line('from')?></th>
                                        <th><?php echo $this->lang->line('date')." ".$this->lang->line('to');?></th>
                                        <th><?php echo $this->lang->line('duration');?></th>
                                        <th><?php echo $this->lang->line('total')." ".$this->lang->line('attempt');?></th>
                                        <th><?php echo $this->lang->line('attempted');?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>
                                      
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($onlineexam)) {
                                        
                                        $count = 1;

                                        foreach ($onlineexam as $exam) {
                                                                               
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $exam->exam;?></td>
                                       <td class="mailbox-name"> <?php echo date('d-M-Y',strtotime($exam->exam_from)); ?> </td>

                                            <td class="mailbox-name"> <?php echo date('d-M-Y',strtotime($exam->exam_to)); ?> </td>
                                                <td class="mailbox-name"><?php echo $exam->duration;?></td>
                                                <td class="mailbox-name"><?php echo $exam->attempt;?></td>
                                                <td class="mailbox-name"><?php echo $exam->counter;?></td>

                                                  <td class="mailbox-name">
                                                    <?php if($exam->publish_result){
echo $this->lang->line('result')." ".$this->lang->line('published');
                                                    }else{

echo $this->lang->line('available');
                                                    }
                                                    ?>
                                                        
                                                    </td>
                                                <td class="mailbox-name">
												  <a href="<?php echo site_url('user/onlineexam/view/'.$exam->id); ?>"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>                                                   

                                                </td>
                                              
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
			</div>
		</div>
	</div>
</div>