<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('daily'); ?> <?php echo $this->lang->line('statement'); ?> <?php echo $this->lang->line('report'); ?></small></h3>                
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <?php $this->load->view('quick_report'); ?>   
            
             <div class="x_content filter-box no-print"> 
                <?php echo form_open_multipart(site_url('report/statement'), array('name' => 'actbalance', 'id' => 'actbalance', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">                    
                   
                    <?php $this->load->view('layout/school_list_filter'); ?>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <?php echo $this->lang->line('from_date'); ?>
                            <input  class="form-control col-md-7 col-xs-12"  name="date_from"  id="date_from" value="<?php echo isset($date_from) && $date_from != '' ?  date('d-m-Y', strtotime($date_from)) : ''; ?>" placeholder="<?php echo $this->lang->line('from_date'); ?>" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                           <?php echo $this->lang->line('to_date'); ?>
                            <input  class="form-control col-md-7 col-xs-12"  name="date_to"  id="date_to" value="<?php echo isset($date_to) && $date_to != '' ?  date('d-m-Y', strtotime($date_to)) : ''; ?>" placeholder="<?php echo $this->lang->line('to_date'); ?>" type="text" autocomplete="off">
                        </div>
                    </div>
                
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>
                </div>

                       <!-- ak -->
                 <div class="container">
                            <div>
                                <strong>Checked the Checkbox for Hide column</strong>
                                    <input type="checkbox" class="hidecol"  id="col_2" />&nbsp;<?php echo $this->lang->line('date'); ?>&nbsp;
                                    <input type="checkbox" class="hidecol"  id="col_3" />&nbsp;<?php echo $this->lang->line('title'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_4" />&nbsp;<?php echo $this->lang->line('note'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_5" />&nbsp;<?php echo $this->lang->line('debit'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_6" />&nbsp;<?php echo $this->lang->line('credit'); ?>
                                    <input type="checkbox" class="hidecol"  id="col_7" />&nbsp;<?php echo $this->lang->line('balance'); ?>
                            </div>
                        </div>
                <!-- ak -->


                <?php echo form_close(); ?>
            </div>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                 <h5> <?php echo "Date : ". date("d/m/Y") ?> 
                   <?php if(isset($school) && !empty($school)){ ?>
                                    <div class="x_content">             
                                        <div class="row">
                                            <div class="col-sm-3  col-xs-3">&nbsp;</div>
                                            <div class="col-12 layout-box">
                                                <div>
                                                    <?php if($school->logo){ ?>
                                                        <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" style="height: 50px; margin-right: 200px;" /> 
                                                    <?php }else if($school->frontend_logo){ ?>
                                                        <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" style="height: 50px; margin-right: 200px;" /> 
                                                    <?php }else{ ?>                                                        
                                                        <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->brand_logo; ?>" alt="" style="height: 50px; margin-right: 200px;" />
                                                    <?php } ?>
                                                    <h4><?php echo $school->school_name; ?></h4>
                                                    <p><?php echo $school->address; ?></p>
                                                    <h3 class="head-title ptint-title" style="width: 100%;"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?>  <?php echo $this->lang->line('report'); ?></small></h3>                
                                                    <div class="clearfix">&nbsp;</div>
                                                </div>
                                            </div>
                                                <div class="col-sm-3  col-xs-3"></div>
                                        </div>            
                                    </div>
                    <?php } ?>    
                    
                     <ul  class="nav nav-tabs bordered no-print">
                        <li class="active"><a href="#tab_tabular"   role="tab" data-toggle="tab"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('tabular'); ?> <?php echo $this->lang->line('report'); ?></a> </li>
                    </ul>
                    <br/>                    
                    
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in active" id="tab_tabular" >
                            <div class="x_content">
                            <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('debit'); ?></th>
                                        <th><?php echo $this->lang->line('credit'); ?></th>
                                        <th><?php echo $this->lang->line('balance'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php 
                                    $total_income = 0;
                                    $total_expenditure = 0;
                                    $total_balance = 0;                                  
                                    
                                    $count = 1; if(isset($statement) && !empty($statement)){ ?>
                                        <?php foreach($statement as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>  
                                            <td><?php echo date($this->global_setting->date_format, strtotime($obj['date'])); ?></td>                                           
                                            <td><?php echo $obj['head']; ?></td>                                           
                                            <td><?php echo $obj['note']; ?></td> 
                                            <td><?php echo $obj['debit']; $total_expenditure += $obj['debit'];  ?></td>                                           
                                            <td><?php echo $obj['credit']; $total_income += $obj['credit'];  ?></td>                                           
                                            <td><?php echo $total_balance = $total_income - $total_expenditure;   ?></td>                                           
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="4"><strong><?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('amount'); ?></strong></td>
                                            <td><strong><?php echo number_format($total_expenditure,2); ?></strong></td>                                           
                                            <td><strong><?php echo number_format($total_income,2); ?></strong></td>                                           
                                            <td><strong><?php echo number_format(($total_income - $total_expenditure),2); ?></strong></td>                                           
                                        </tr>
                                    <?php }else{ ?>
                                        <tr><td colspan="8" class="text-center"><?php echo $this->lang->line('no_data_found'); ?></td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>  
                        <h4>Signature: __________ </h4>                         
                    </div>
                </div>
            </div>
            
            <div class="row no-print">
                <div class="col-xs-12 text-right">
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
            
        </div>
    </div>
</div>
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 <script type="text/javascript">
     
    $('#date_from').datepicker();
    $('#date_to').datepicker();
    $("#actbalance").validate();  

    // ak
   $(document).ready(function(){

            // Checkbox click
            $(".hidecol").click(function(){

                var id = this.id;
                var splitid = id.split("_");
                var colno = splitid[1];
                var checked = true;
                 
                // Checking Checkbox state
                if($(this).is(":checked")){
                    checked = true;
                }else{
                    checked = false;
                }
                setTimeout(function(){
                    if(checked){
                        $('#datatable-keytable td:nth-child('+colno+')').hide();
                        $('#datatable-keytable th:nth-child('+colno+')').hide();
                    } else{
                        $('#datatable-keytable td:nth-child('+colno+')').show();
                        $('#datatable-keytable th:nth-child('+colno+')').show();
                    }

                }, 1500);

            });
        });
        // ak
       
</script>
