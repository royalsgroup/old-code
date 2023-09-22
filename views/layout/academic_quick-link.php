<div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
               
                    <?php if(has_permission(VIEW, 'academic', 'discipline')){ ?>   
                     <a href="<?php echo site_url('disciplines'); ?>"> <?php echo $this->lang->line('discipline'); ?></a>
                    <?php } ?> 

                    <?php if(has_permission(VIEW, 'academic', 'classes')){ ?>
                    | <a href="<?php echo site_url('academic/classes/index'); ?>"> <?php echo $this->lang->line('class'); ?></a>
                    <?php } ?>
                    <?php if(has_permission(VIEW, 'academic', 'section')){ ?>
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php }else{ ?>                         
                            | <a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a>
                        <?php } ?> 
                    <?php } ?>   
                    <?php if(has_permission(VIEW, 'academic', 'routine')){ ?>
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php }else{ ?>    
                           | <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                        <?php } ?>
                        <?php } ?>
                    <?php if(has_permission(VIEW, 'academic', 'liveclass')){ ?>
                     | <a  href="<?php echo site_url('academic/liveclass/index'); ?>"><?php echo $this->lang->line('live_class'); ?></a> 
                    <?php } ?>

                    <?php if(has_permission(VIEW, 'teacher', 'lecture')){ ?>
                    | <a  href="<?php echo site_url('teacher/lecture/index/'); ?>"><?php echo $this->lang->line('class_lecture'); ?></a> 
                    <?php } ?>   
                   
                    <?php if(has_permission(VIEW, 'academic', 'subject')){ ?>                            
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php }else{ ?>      
                            | <a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a>
                        <?php } ?>
                    <?php } ?>
                    <?php if(has_permission(VIEW, 'academic', 'syllabus')){ ?>                        
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"><?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>
                        <?php }else{ ?>      
                            | <a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a>

                        <?php } ?>
                    <?php } ?>     
                    <?php if(has_permission(VIEW, 'academic', 'material')){ ?>                        
                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>   
                          | <a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a>
                        <?php }else{ ?>      
                          | <a href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?> </a>        
                        <?php } ?>
                    <?php } ?>
                   
            </div>