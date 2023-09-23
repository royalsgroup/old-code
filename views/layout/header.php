<?php $sYearText = get_years($this->session->userdata('front_school_id')); ?>
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="col-md-1">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="col-md-7 ">
                <div class="school-name">
                    <?php  if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ ?>
                        <?php echo $this->session->userdata('school_name'); ?>
                    <?php }else{ ?>
                         <?php echo $this->global_setting->brand_title ? $this->global_setting->brand_title : SMS; ?>
                    <?php } ?>

                </div>
            </div>
            <div class="col-md-4">
                <div style="display: inline-block;">
                <?php echo  $sYearText ?>
            </div>
            

                <ul class="nav navbar-nav <?php echo $this->global_setting->enable_rtl ? 'navbar-left' : 'navbar-right'; ?>">
                    <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <?php
                                $photo = $this->session->userdata('photo');
                                $role_id = $this->session->userdata('role_id');
                                $path = '';
                                if($role_id == STUDENT){ $path = 'student'; }
                                elseif($role_id == GUARDIAN){ $path = 'guardian'; }
                                elseif($role_id == TEACHER){ $path = 'teacher'; }
                                else{ $path = 'employee'; }
                            ?>
                            <?php if ($photo != '') { ?>                                        
                                <img src="<?php echo UPLOAD_PATH; ?>/<?php echo $path; ?>-photo/<?php echo $photo; ?>" alt="" width="60" /> 
                            <?php } else { ?>
                                <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="60" /> 
                            <?php } ?>                            
                            <?php echo $this->session->userdata('name'); ?>
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                            <!--<li><a href="<?php echo site_url('profile/index'); ?>"> <?php echo $this->lang->line('profile'); ?></a></li>-->
                            <li><a href="<?php echo site_url('profile/password'); ?>"><?php echo $this->lang->line('reset_password'); ?></a></li>
                            <li><a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> <?php echo $this->lang->line('logout'); ?></a></li>
                        </ul>
                    </li>
                    
                    <?php $messages = get_inbox_message(); ?>
                    <?php if(isset($messages) && !empty($messages)){ ?>
                    <li role="presentation" class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-green"><?php echo count($messages); ?></span>
                        </a>
                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                            
                           <?php foreach($messages as $obj){ ?> 
                            <li>
                                <?php $user = isset($obj->sender_id) ?  get_user_by_id($obj->sender_id) : null; ?>
                                <a href="<?php echo site_url('message/view/'.$obj->id); ?>">
                                    <span class="image"><img src="<?php echo IMG_URL; ?>default-user.png" alt="Profile Image" /></span>
                                    <span>
                                        <span><?php echo @$user->name; ?></span>
                                        <span class="time"><?php echo get_nice_time($obj->created_at); ?></span>
                                    </span>
                                    <span class="message">
                                        <?php echo $obj->subject; ?>
                                    </span>
                                </a>
                            </li>                    
                            <?php } ?>
                            <li>
                                <div class="text-center">
                                    <a href="<?php echo site_url('message/inbox'); ?>">
                                        <strong>See All</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>                     
                    <?php if($this->global_setting->enable_frontend){ ?>
                        <li>
                            <?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ ?>                            
                                    <?php if(isset($this->school_setting->enable_frontend) && $this->school_setting->enable_frontend){ ?>
                                        <a href="<?php echo site_url(); ?>"><i class="fa fa-globe"></i> Web</a>
                                    <?php } ?> 
                            <?php }else{ ?>  
                                <a href="<?php echo site_url(); ?>"><i class="fa fa-globe"></i> Web</a>
                            <?php } ?>  
                        </li>
                    <?php } ?>  
                    
                </ul>
            </div>
        </nav>
    </div>
</div>
