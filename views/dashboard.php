
<style>
    .count_loading_div img
    {
        width: 50px !important;
    }
    </style>
<!-- top tiles -->


<div class="row ">
    <div class="tile_count">
     <?php if(has_permission(VIEW, 'student', 'student')){ ?>
    <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <div class="stats-count-inner">
            <span class="count_top"><i class="fa fa-group"></i> <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('student'); ?></span>
            <div class="count" id="total_student_count">
                <div class="count_loading_div" style="display :none">	
					<img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" >
                </div>
            </div>
               
        </div>
    </div>
     <?php } ?>
     <?php if(has_permission(VIEW, 'student', 'student')){ ?>
    <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <div class="stats-count-inner">
            <span class="count_top"><i class="fa fa-group"></i> <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('present'); ?></span>
            <div class="count" id="total_student_present">
                <div class="count_loading_div" style="display :none">	
					<img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" >
                </div>
            </div>
               
        </div>
    </div>
     <?php } ?>
     <?php if(has_permission(VIEW, 'guardian', 'guardian')){ ?>
    <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <div class="stats-count-inner">
            <span class="count_top"><i class="fa fa-paw"></i> <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('guardian'); ?></span>
            <div class="count" id="total_guardian_count">
            <div class="count_loading_div" style="display :none">	
					<img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" >
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
    <?php if(has_permission(VIEW, 'teacher', 'teacher')){ ?>
    <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <div class="stats-count-inner">
            <span class="count_top"><i class="fa fa-users"></i> <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('teacher'); ?> </span>
            <div class="count" id="total_teacher_count">
            <div class="count_loading_div" style="display :none">	
					<img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" >
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if(has_permission(VIEW, 'teacher', 'teacher')){ ?>
    <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <div class="stats-count-inner">
            <span class="count_top"><i class="fa fa-users"></i> <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('present'); ?></span>
            <div class="count" id="total_teacher_present">
            <div class="count_loading_div" style="display :none">	
					<img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" >
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if(has_permission(VIEW, 'hrm', 'employee')){ ?>
    <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <div class="stats-count-inner">
            <span class="count_top"><i class="fa fa-user-md"></i> <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('employee'); ?></span>
            <div class="count" id="total_employee_count">
            <div class="count_loading_div" style="display :none">	
					<img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" >
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if(has_permission(VIEW, 'accounting', 'income')){ /* ?>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <div class="stats-count-inner">
                <span class="count_top"> 
                    <?php echo isset($school_setting->currency_symbol) ? $school_setting->currency_symbol : '$';  ?> 
                    <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('income'); ?>
                </span>
                <div class="count green"><?php echo $total_income ? $total_income : '0.00'; ?></div>
            </div>
        </div>
     <?php */ } ?>
    <?php  if(has_permission(VIEW, 'accounting', 'expenditure')){ /* ?>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <div class="stats-count-inner">
            <span class="count_top">
                <?php echo isset($school_setting->currency_symbol) ? $school_setting->currency_symbol : '$';  ?> 
                <?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('expenditure'); ?>
            </span>
            <div class="count red"><?php echo $total_expenditure? $total_expenditure : '0.00'; ?></div>
        </div>
    </div>
     <?php */ } ?>  
    </div>
</div>  
<!-- /top tiles -->

<?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>

<div class="row">
           
    <div class="col-md-12 col-sm-12 col-xs-12">            
            <div class="x_panel tile overflow_hidden">
                <div class="x_title">
                    <h4 class="head-title"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('statistics'); ?></h4>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                                
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <div id="school-stats" style=" width: 99%; vertical-align: top; height:250px; ">
                    <div class="chart_loading_div" style="display :none">	
					<center><img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" ></center>
                </div>
                
                </div>
                    <script type="text/javascript">
                    $('.chart_loading_div').show();
                    
                   function schoolschart(schools_data) {
                       $('#school-stats').highcharts({
                                chart: {
                                        type: 'column'
                                    },
                                    title: {
                                        text: '<?php  if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ ?>
                                                    <?php echo $this->session->userdata('school_name'); ?>
                                                <?php }else{ ?>
                                                     <?php echo $this->global_setting->brand_name ? $this->global_setting->brand_name : SMS; ?>
                                                <?php } ?>'
                                    },
                                    xAxis: {
                                        categories: ['<strong><?php echo $this->lang->line('class'); ?></strong>', '<strong><?php echo $this->lang->line('student'); ?></strong>', '<strong><?php echo $this->lang->line('teacher'); ?></strong>', '<strong><?php echo $this->lang->line('employee'); ?></strong>', '<strong><?php echo $this->lang->line('income'); ?></strong>', '<strong><?php echo $this->lang->line('expenditure'); ?></strong>']
                                    },
                                    yAxis: {
                                        min: 0,
                                        title: {
                                            text: '<?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('all'); ?> <?php echo $this->lang->line('statistics'); ?>'
                                        }
                                    },
                                    tooltip: {
                                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                                        shared: true
                                    },
                                    plotOptions: {
                                        column: {
                                            stacking: 'percent'
                                        }
                                    },
                                    series: [
                                        schools_data
                                   ],
                                credits: {
                                    enabled: false
                                }
                                });
                        };
                        
               </script>

                    
                </div>
            </div>            
    </div>  
   
</div>
 
<?php } ?> 

<div class="row">
    <div class="col-md-8 col-sm-8 col-xs-12">   
        
    <?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ ?>
        <div class="row">
            
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel tile fixed_height_320 overflow_hidden">
                    <div class="x_title">
                        <h4 class="head-title"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('statistics'); ?></h4>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                                
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="student-stats" style=" width: 99%; vertical-align: top; height:250px; ">
                        <div class="student_loading_div" style="display :none">	
                        <center><img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" > </center>
                    </div>
                    </div>

                        <script type="text/javascript">
              
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>       

            <div class="col-md-6 col-sm-4 col-xs-12">
                <div class="x_panel tile overflow_hidden">
                    <div class="x_title">
                        <h4 class="head-title"><?php echo $this->lang->line('latest'); ?> <?php echo $this->lang->line('news'); ?></h4>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                                
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="new_loading_div" style="display :none">	
                        <center><img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" > </center>
                    </div>
                        <ul  class="list-unstyled msg_list" id="news_list">                        
                                                  
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel tile overflow_hidden">
                    <div class="x_title">
                        <h4 class="head-title"><?php echo $this->lang->line('latest'); ?> <?php echo $this->lang->line('notice'); ?></h4>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                                
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                    <div class="new_loading_div" style="display :none">	
                    <center><img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" > <c/enter>
                    </div>
                        <ul  class="list-unstyled msg_list" id="notice_list">

                  
                        </ul>
                    </div>
                </div>
            </div>
        
    </div>

    <div class="col-md-4 col-sm-4 col-xs-12">
    <div class="row">      
    <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel tile overflow_hidden">
                <div class="x_title">
                    <h3 class="head-title"><?php echo $this->lang->line('calendar'); ?></h3>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                                
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="calendar"></div>
                    <link rel='stylesheet' href='<?php echo VENDOR_URL; ?>fullcalendar/lib/cupertino/jquery-ui.min.css' />
                    <link rel='stylesheet' href='<?php echo VENDOR_URL; ?>fullcalendar/fullcalendar.css' />
                    <script type="text/javascript" src='<?php echo VENDOR_URL; ?>fullcalendar/lib/jquery-ui.min.js'></script>
                    <script type="text/javascript" src='<?php echo VENDOR_URL; ?>fullcalendar/lib/moment.min.js'></script>
                    <script type="text/javascript" src='<?php echo VENDOR_URL; ?>fullcalendar/fullcalendar.min.js'></script> 
                    <script type="text/javascript">
                        $(function () {
                            $('#calendar').fullCalendar({
                                header: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'month,agendaWeek,agendaDay'
                                },
                                buttonText: {
                                    today: 'today',
                                    month: 'month',
                                    week: 'week',
                                    day: 'day'
                                },

                                eventSources: [

                                    // your event source
                                    {
                                    url: "<?php echo site_url('dashboard/get_calendar_events'); ?>",
                                    method: 'POST',
                                   
                                    failure: function() {
                                        alert('there was an error while fetching events!');
                                    },
                                    color: 'yellow',   // a non-ajax option
                                    textColor: 'black' // a non-ajax option
                                    }

                                    // any other sources...

                                    ]
                            });
                        });
                    </script>

                </div>                
            </div>          
        </div>  
        </div>
        
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel tile fixed_height_320">
                    <div class="x_title">
                        <h4 class="head-title">Daybook</h4>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                                
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <script type="text/javascript">
                        
                        </script>
                        <!-- <div id="private-message" style=" width: 99%; vertical-align: top;height: 260px;">
                            <div class="message_loading_div" style="display :none">	
                            <center><img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" > </center>
                            </div>
                        </div> -->
                        <div class="col-md-12 col-sm-12 col-xs-12" id="result" style="overflow-y: scroll;height: 257px;">
									<button class="btn btn-sm btn-success" id="loadBalanceBt" onclick="loadBalance()" >Display Balance</button>	
                                    <div id="loading_div" style="display :none">	
                                    <img  src=" <?php echo base_url() . 'assets/gif/loading.gif' ?> " id="loading_spinner" ></dib>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="head-title"><?php echo $this->lang->line('user'); ?> <?php echo $this->lang->line('type'); ?></h4>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <script type="text/javascript">

                            
                        </script>
                        <div id="system-users" style=" width: 100%; vertical-align: top; height:260px; ">
                        <div class="users_loading_div" style="display :none">	
                        <center><img src=" <?php echo base_url() . 'assets/gif/circle.svg' ?> " id="loading_spinner" > </center>
                    </div></div>
                    </div>
                </div>
            </div>
        </div>   -->
    </div>
</div>

<script src="<?php echo VENDOR_URL; ?>/chart/js/highcharts.js"></script>
<script src="<?php echo VENDOR_URL; ?>/chart/js/highcharts-3d.js"></script>
<script src="<?php echo VENDOR_URL; ?>/chart/js/modules/exporting.js"></script>
<script>
     function loadBalance(){
		   $('#loadBalanceBt').hide();
				$('#loading_div').show();
				$.ajax({       
					type   : "POST",
					url    : "<?php echo site_url('daybookdev/get_summery_dashboard'); ?>",
					async  : true,
					dataType: "json",
					success: function(response){                                                   
						if(response)
						{  
							$('#result').html(response.html);
							$('#loading_div').hide();
						}
					}
					});
		}
    $( document ).ready(function() {
       
    $('.count_loading_div').show();
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('dashboard/get_dashboard_data'); ?>",
            dataType : "json",
            async : false,
            success: function(response){                                                   
            if(response)
            {  
                $('#total_student_count').html(response.total_student)
                $('#total_guardian_count').html(response.total_guardian)
                $('#total_teacher_count').html(response.total_teacher)
                $('#total_employee_count').html(response.total_employee)
                $('.count_loading_div').hide()
                $('#total_teacher_present').html(response.total_teacher_present)
                $('#total_student_present').html(response.total_student_present);
              
                // console.log(response)
                //  schoolschart();                            
            }
            }
        });	
        $('.count_loading_div').show();
        <?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ ?>
            $('.student_loading_div').show();
       $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('dashboard/get_school_stats'); ?>",
            dataType : "json",
            success: function(response){                                                   
            if(response)
            {   $('.student_loading_div').hide();
                school_stats(response.data)

                // schoolschart();                            
            }
            }
        });	
        <?php } ?>
        $('.users_loading_div').show();
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('dashboard/get_user_stats'); ?>",
            dataType : "json",
            success: function(response){                                                   
            if(response)
            {  
                $('.users_loading_div').hide();
                user_stats(response.data)
                // schoolschart();                            
            }
            }
        });	
        $('.message_loading_div').show();
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('dashboard/get_message_stats'); ?>",
            dataType : "json",
            success: function(response){                                                   
            if(response)
            {  
                $('.message_loading_div').hide();
                message_stats(response.data)
                // schoolschart();                            
            }
            }
        });	
        $('.new_loading_div').show();
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('dashboard/get_news_notices'); ?>",
            dataType : "json",
            success: function(response){                                                   
            if(response)
            {  
                $('.new_loading_div').hide();
                $('#notice_list').html(response.notices)
                $('#news_list').html(response.news)
                // schoolschart();          
              //  loaddashboard();                  
            }
            }
        });	
         function loaddashboard(){
            $.ajax({       
                type   : "POST",
                url    : "<?php echo site_url('dashboard/get_school_data'); ?>",
                dataType : "json",

                success: function(response){             

                if(response)
                {  
                    $('.chart_loading_div').hide();
                    schoolschart(response.stats);    

                }
                }
            });	
             
         }



                                  
                    function school_stats(schools_data) {
                                $('#student-stats').highcharts({
                                    chart: {
                                        type: 'pie',
                                        options3d: {
                                            enabled: true,
                                            alpha: 45,
                                            beta: 0
                                        }
                                    },
                                    title: {
                                        text: '<?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('statistics'); ?>'
                                    },
                                    tooltip: {
                                        pointFormat: '{series.name}s:  <b>{point.percentage:.1f}%</b> <br>count:{point.y}'
                                        //pointFormat: JS('function(){ return "1"}')
                                    },
                                    plotOptions: {
                                        pie: {
                                            allowPointSelect: true,
                                            cursor: 'pointer',
                                            depth: 35,
                                            dataLabels: {
                                                enabled: true,
                                                format: '{point.name}'
                                            }
                                        }
                                    },
                                    series: [{
                                            type: 'pie',
                                            name: '<?php echo $this->lang->line('student'); ?>',
                                            data: schools_data,
                                        }],
                                    credits: {
                                        enabled: false
                                    }
                                });
                            }
                            function user_stats(user_data) {
                                $('#system-users').highcharts({
                                    chart: {
                                        type: 'pie',
                                        options3d: {
                                            enabled: true,
                                            alpha: 45
                                        }
                                    },
                                    title: {
                                        text: ''
                                    },
                                    tooltip: {
                                        pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b> <br> count : {point.y}'
                                    },
                                    subtitle: {
                                        text: ''
                                    },
                                    plotOptions: {
                                        pie: {
                                            allowPointSelect: true,
                                            innerSize: 100,
                                            depth: 30,
                                            dataLabels: {
                                                format: '<b>{point.name}</b>'
                                            }
                                        }
                                    },
                                    credits: {
                                        enabled: false
                                    },
                                    series: [{
                                            name: '<?php echo $this->lang->line('user'); ?>',
                                            data: user_data
                                        }]
                                });
                            };
                            function message_stats(message_data) {
                                    $('#private-message').highcharts({
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: ''
                                        },
                                        xAxis: {
                                            type: 'category'
                                        },
                                        yAxis: {
                                            title: {
                                                text: '<?php echo $this->lang->line('private_messaging'); ?>'
                                            }
                                        },
                                        legend: {
                                            enabled: false
                                        },
                                        plotOptions: {
                                            series: {
                                                borderWidth: 0,
                                                dataLabels: {
                                                    enabled: true,
                                                    format: '{point.y:.1f}%'
                                                }
                                            }
                                        },
                                        tooltip: {
                                            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                                            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                                        },
                                        series: [{
                                                name: '<?php echo $this->lang->line('message'); ?>',
                                                colorByPoint: true,
                                                data: message_data,
                                        }],
                                        credits: {
                                            enabled: false
                                        }
                                    });
                                }
    })
                            
    </script>
<style type="text/css">

    .fc-time{display: none;}
</style>