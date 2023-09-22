<?php if(has_permission(VIEW, 'report', 'student_statics')){ ?>
                                            <a href="<?php echo site_url('/report/student_statics'); ?>"><?php echo $this->lang->line('student'); ?> Statics </a> |
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'report', 'fee_report')){ ?>
                                            <a href="<?php echo site_url('/report/fee_report'); ?>"><?php echo $this->lang->line('fee'); ?> Report </a> |
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'report', 'teacher_report')){ ?>

                                            <a href="<?php echo site_url('/report/teacher_report'); ?>"><?php echo $this->lang->line('teacher');?> <?php echo $this->lang->line('report'); ?></a> |
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'all_teacher_report')){ ?>

                                            <a href="<?php echo site_url('/report/all_teacher_report'); ?>">All <?php echo $this->lang->line('teacher');?> <?php echo $this->lang->line('report'); ?> </a> |
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'faculty_report')){ ?>

                                            <a href="<?php echo site_url('/report/faculty_report'); ?>">Faculty <?php echo $this->lang->line('report'); ?> </a> |
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'category_report')){ ?>

                                            <a href="<?php echo site_url('/report/category_report'); ?>">Category <?php echo $this->lang->line('report'); ?> </a> |
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'working_area')){ ?>

                                            <a href="<?php echo site_url('/report/working_area'); ?>">Working Area <?php echo $this->lang->line('report'); ?> </a> |
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'installment_wise')){ ?>

                                            <a href="<?php echo site_url('/report/installment_wise'); ?>">Installment wise <?php echo $this->lang->line('report'); ?> </a> |
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'payroll_report')){ ?>

                                            <a href="<?php echo site_url('/report/payroll_report'); ?>">Payroll <?php echo $this->lang->line('report'); ?> </a> |
                                            <?php } ?>