<style>
@media only screen and (max-width: 760px) {
  #mobile_screen { display: none; }
}
</style>
<span id="mobile_screen"></span>
<div class="col-md-3 <?php echo $this->global_setting->enable_rtl ? 'right_col' : 'left_col'; ?>">
    <div class="<?php echo $this->global_setting->enable_rtl ? 'right_col' : 'left_col'; ?> scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo site_url('dashboard'); ?>">
                <?php if($this->global_setting->brand_name){ ?>
                    <span <?php if(str_word_count($this->global_setting->brand_name) == 1 ){ echo 'style="margin-top: 30px;"'; }  ?>>
                        <?php  echo $this->global_setting->brand_name; ?>
                    </span>
                <?php }else{ ?>
                     <span>Multi School</span>    
                <?php } ?>                
                
                <?php if($this->global_setting->brand_logo){ ?>
                     <img class="logo" src="<?php echo UPLOAD_PATH.'logo/'.$this->global_setting->brand_logo; ?>" style="max-width: 65px;" alt="">
                <?php }else{ ?>
                     <img class="logo" src="<?php echo IMG_URL; ?>/sms-logo-50.png" alt="">
                <?php } ?>
            </a>
        </div>
        <div class="clearfix" style=""></div>        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <?php 
                    if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){                  
                        $classes = get_classes($this->session->userdata('school_id'));
                    }               
                    if($this->session->userdata('role_id') == GUARDIAN){                  
                        $guardian_class_data = get_guardian_access_data('class'); 
                    }               
                ?>
                
                <ul class="nav side-menu">                    
                    
                    <?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ ?>
                        <?php if(has_permission(VIEW, 'setting', 'setting') || has_permission(VIEW, 'setting', 'payment')  || has_permission(VIEW, 'setting', 'sms')){ ?> 
                            <li><a><i class="fa fa-gears"></i> <?php echo $this->lang->line('setting'); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">                            
                                    <?php if(has_permission(VIEW, 'setting', 'setting')){ ?>
                                        <li><a href="<?php echo site_url('setting'); ?>"><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                    <?php } ?>									
									 
                                    <?php if(has_permission(VIEW, 'setting', 'payment')){ ?> 
                                        <li><a href="<?php echo site_url('setting/payment'); ?>"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'setting', 'sms')){ ?>
                                        <li><a href="<?php echo site_url('setting/sms'); ?>"><?php echo $this->lang->line('sms'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'setting', 'emailsetting')){ ?>
                                        <li><a href="<?php echo site_url('setting/emailsetting'); ?>"><?php echo $this->lang->line('email'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>                        
                        <?php } ?>                          
                    <?php } ?>
                                        
                    
                   
                    
                   
                      
   
                        <li>
                            <a><i class="fa fa-user"></i> <?php echo $this->lang->line('administrator'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="<?php echo site_url('dashboard'); ?>"><?php echo $this->lang->line('dashboard'); ?></a>  </li>
                                <?php if(has_permission(VIEW, 'theme', 'theme')){ ?>
                                        <li><a  href="<?php echo site_url('theme'); ?>"><?php echo $this->lang->line('theme'); ?></a></li> 
                                <?php } ?>  
                                <?php if(has_permission(VIEW, 'language', 'language')){ ?>
                                    <li><a  href="<?php echo site_url('language'); ?>"> <?php echo $this->lang->line('language'); ?></a></li>
                                <?php } ?>
                                <?php if($this->session->userdata('role_id') == SUPER_ADMIN ){ ?>			
                        <li>
                            <a> <?php echo $this->lang->line('access_levels'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">                                
                                    <li><a href="<?php echo site_url('zone'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('zone'); ?></a></li> 
									<li><a href="<?php echo site_url('subzone'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('subzone'); ?></a></li> 
									<li><a href="<?php echo site_url('district'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('district'); ?></a></li> 
									<li><a href="<?php echo site_url('block'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('block'); ?></a></li> 
									<li><a href="<?php echo site_url('sankul'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('sankul'); ?></a></li> 
							</ul>
						</li>
                    <?php } ?>
                    <?php 
                    if(has_permission(VIEW, 'administrator', 'setting') ||
                            has_permission(VIEW, 'administrator', 'school') || 
                            has_permission(VIEW, 'administrator', 'payment') || 
                            has_permission(VIEW, 'administrator', 'sms') || 
                            has_permission(VIEW, 'administrator', 'year') || 
                            has_permission(VIEW, 'administrator', 'role') || 
                            has_permission(VIEW, 'administrator', 'permission') || 
                            has_permission(VIEW, 'administrator', 'user') || 
                            has_permission(VIEW, 'administrator', 'usercredential') || 
                            has_permission(VIEW, 'administrator', 'superadmin') || 
                            has_permission(EDIT, 'administrator', 'password') || 
                            has_permission(VIEW, 'administrator', 'backup') ||                            
                            has_permission(VIEW, 'administrator', 'activitylog') ||
                            has_permission(VIEW, 'administrator', 'feedback')){ ?> 
                             <li>
                                 <a><?php echo $this->lang->line('administrator'); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">              
                                    <?php 
                                    if(has_permission(VIEW, 'administrator', 'setting') != "0"){ ?>   
                                        <li><a href="<?php echo site_url('administrator/setting'); ?>"> <?php echo $this->lang->line('general'); ?> <?php echo $this->lang->line('setting'); ?>fff</a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'administrator', 'districtadmin')){ ?>   
                                        <li><a href="<?php echo site_url('districtadmin'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('district_admin'); ?></a></li> 
                                    <?php } ?>
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 ||  has_permission(VIEW, 'administrator', 'import_csv') ){ ?>
                                            <li><a href="<?php echo site_url('import/csv'); ?>"><?php echo $this->lang->line('import'); ?> <?php echo $this->lang->line('csv'); ?></a></li>
                                        <?php } ?>								
                                    <?php if(has_permission(VIEW, 'administrator', 'school')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/school'); ?>"> <?php echo $this->lang->line('manage_school'); ?></a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'administrator', 'payment')){ ?> 
                                    <li><a href="<?php echo site_url('administrator/payment'); ?>"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'administrator', 'sms')){ ?>
                                        <li><a href="<?php echo site_url('administrator/sms'); ?>"><?php echo $this->lang->line('sms'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                    <?php } ?>    
                                    <?php if(has_permission(VIEW, 'administrator', 'emailsetting')){ ?>
                                        <li><a href="<?php echo site_url('administrator/emailsetting'); ?>"><?php echo $this->lang->line('email'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                    <?php } ?>    
                                    <?php if(has_permission(VIEW, 'administrator', 'year')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/year'); ?>"> <?php echo $this->lang->line('academic_year'); ?></a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'administrator', 'role')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/role'); ?>"> <?php echo $this->lang->line('user_role'); ?> (ACL)</a></li> 
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'administrator', 'permission')){ ?> 
                                        <li><a href="<?php echo site_url('administrator/permission'); ?>"><?php echo $this->lang->line('role_permission'); ?> (ACL)</a></li> 
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'administrator', 'superadmin')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/superadmin'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('super_admin'); ?></a></li> 
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'administrator', 'user')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/user/index'); ?>"><?php echo $this->lang->line('manage_user'); ?></a></li> 
                                    <?php } ?>
                                    <?php if(has_permission(EDIT, 'administrator', 'password')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/password'); ?>"><?php echo $this->lang->line('reset_user_password'); ?></a></li> 
                                    <?php } ?> 
                                    <?php if(has_permission(VIEW, 'administrator', 'usercredential')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/usercredential/index'); ?>"><?php echo $this->lang->line('user'); ?> <?php echo $this->lang->line('credential'); ?></a></li> 
                                    <?php } ?> 
                                    <?php if(has_permission(VIEW, 'administrator', 'activitylog')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/activitylog'); ?>"><?php echo $this->lang->line('activity_log'); ?></a></li>                         
                                    <?php } ?>      
                                    <?php if(has_permission(VIEW, 'administrator', 'feedback')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/feedback'); ?>"><?php echo $this->lang->line('publish'); ?> <?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('feedback'); ?></a></li>                         
                                    <?php } ?> 
                                    <?php if(has_permission(VIEW, 'administrator', 'backup')){ ?>   
                                        <li><a href="<?php echo site_url('administrator/backup'); ?>"><?php echo $this->lang->line('backup'); ?> <?php echo $this->lang->line('database'); ?></a></li>                         
                                    <?php } ?>    
                                </ul>
                            </li>
                            <?php if(has_permission(VIEW, 'administrator', 'emailtemplate') || has_permission(VIEW, 'administrator', 'smstemplate')){ ?> 
                                <li><a><?php echo $this->lang->line('template'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">                            
                                        <?php if(has_permission(VIEW, 'administrator', 'smstemplate')){ ?>   
                                            <li><a href="<?php echo site_url('administrator/smstemplate'); ?>"><?php echo $this->lang->line('sms'); ?> <?php echo $this->lang->line('template'); ?></a></li>                         
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'administrator', 'emailtemplate')){ ?>   
                                            <li><a href="<?php echo site_url('administrator/emailtemplate'); ?>"><?php echo $this->lang->line('email'); ?> <?php echo $this->lang->line('template'); ?></a></li>                         
                                        <?php } ?>     
                                    </ul>
                                </li>                        
                            <?php } ?> 
                            <?php if(has_permission(VIEW, 'frontoffice', 'purpose') ||
                             has_permission(VIEW, 'frontoffice', 'visitor') ||
                             has_permission(VIEW, 'frontoffice', 'calllog') ||
                             has_permission(VIEW, 'frontoffice', 'dispatch') ||
                             has_permission(VIEW, 'frontoffice', 'receive') ||
                             has_permission(VIEW, 'administrator', 'frontoffice')){ ?> 
                        <li><a> <?php echo $this->lang->line('front_office'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">                            
                                 <?php if(has_permission(VIEW, 'frontoffice', 'purpose')){ ?>   
                                    <li><a href="<?php echo site_url('frontoffice/purpose/index'); ?>"><?php echo $this->lang->line('visitor_purpose'); ?></a></li>                         
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'frontoffice', 'visitor')){ ?>   
                                    <li><a href="<?php echo site_url('frontoffice/visitor/index'); ?>"><?php echo $this->lang->line('visitor_info'); ?></a></li>                         
                                <?php } ?>                                 
                                <?php if(has_permission(VIEW, 'frontoffice', 'calllog')){ ?>   
                                    <li><a href="<?php echo site_url('frontoffice/calllog/index'); ?>"><?php echo $this->lang->line('call_log'); ?></a></li>                         
                                <?php } ?>                                 
                                <?php if(has_permission(VIEW, 'frontoffice', 'dispatch')){ ?>   
                                    <li><a href="<?php echo site_url('frontoffice/dispatch/index'); ?>"><?php echo $this->lang->line('postal_dispatch'); ?></a></li>                         
                                <?php } ?>                                 
                                <?php if(has_permission(VIEW, 'frontoffice', 'receive')){ ?>   
                                    <li><a href="<?php echo site_url('frontoffice/receive/index'); ?>"><?php echo $this->lang->line('postal_receive'); ?></a></li>                         
                                <?php } ?>                                 
                            </ul>
                        </li>                        
                    <?php } ?> 
                        </ul>
                      <?php /* if(has_permission(VIEW, 'academic', 'discipline') ||
                            has_permission(VIEW, 'academic', 'standards') || 
							has_permission(VIEW, 'academic', 'subject') 
                           ){ ?>    
                        <li><a><i class="fa fa-user"></i> <?php echo $this->lang->line('academic'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'academic', 'discipline')){ ?>   
                                    <li><a href="<?php echo site_url('disciplines'); ?>"> <?php echo $this->lang->line('discipline'); ?></a></li>
                                <?php } ?> 
								<?php if(has_permission(VIEW, 'academic', 'standards')){ ?>   
                                    <li><a href="<?php echo site_url('standards'); ?>"> <?php echo $this->lang->line('standards'); ?></a></li>
                                <?php } ?> 
								<?php if(has_permission(VIEW, 'academic', 'classes')){ ?>   
                                    <li><a href="<?php echo site_url('classes'); ?>"> <?php echo $this->lang->line('class'); ?></a></li>
                                <?php } ?> 
								<?php if(has_permission(VIEW, 'academic', 'subject')){ ?>   
                                    <li><a href="<?php echo site_url('subjects'); ?>"> <?php echo $this->lang->line('subjects'); ?></a></li>
                                <?php } ?> 
							</ul>
						</li>
							<?php } */?>

                    <?php } ?>

					 <?php if(has_permission(VIEW, 'teacher', 'lecture') || has_permission(VIEW, 'academic', 'liveclass') || has_permission(VIEW, 'academic', 'classes') || has_permission(VIEW, 'academic', 'section') || has_permission(VIEW, 'academic', 'subject') || has_permission(VIEW, 'academic', 'syllabus') || has_permission(VIEW, 'academic', 'material') || has_permission(VIEW, 'academic', 'routine') ){ ?>
                        <li>
						<a><i class="fa fa-headphones"></i> <?php echo $this->lang->line('academic'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'teacher', 'lecture') || has_permission(VIEW, 'academic', 'liveclass') || has_permission(VIEW, 'academic', 'classes') || has_permission(VIEW, 'academic', 'section') || has_permission(VIEW, 'academic', 'subject') || has_permission(VIEW, 'academic', 'syllabus') || has_permission(VIEW, 'academic', 'material') || has_permission(VIEW, 'academic', 'routine') ){ ?>
                                    <li>
                                    <a></i> <?php echo $this->lang->line('academic'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'academic', 'discipline')){ ?>   
                                                <li><a href="<?php echo site_url('disciplines'); ?>"><?php echo $this->lang->line('discipline'); ?></a></li>
                                            <?php } ?> 
                                        <?php if(has_permission(VIEW, 'academic', 'classes')){ ?>
                                    <li><a href="<?php echo site_url('academic/classes/index'); ?>"> <?php echo $this->lang->line('class'); ?></a> </li> 
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'academic', 'liveclass')){ ?>
                                    <li><a  href="<?php echo site_url('academic/liveclass/index'); ?>"><?php echo $this->lang->line('live_class'); ?></a> </li> 
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'teacher', 'lecture')){ ?>
                                    <li><a  href="<?php echo site_url('teacher/lecture/index/'); ?>"><?php echo $this->lang->line('class_lecture'); ?></a> </li>
                                <?php } ?>  
                                <?php if(has_permission(VIEW, 'academic', 'section')){ ?>
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li><a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a></li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li><a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a></li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li><a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a></li>
                                    <?php }else{ ?>                         
                                        <li><a  href="<?php echo site_url('academic/section/index/'); ?>"> <?php echo $this->lang->line('section'); ?> </a></li>
                                    <?php } ?> 
                                <?php } ?>
                                
                                <?php if(has_permission(VIEW, 'academic', 'subject')){ ?>                            
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li><a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a></li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li><a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a></li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li><a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a></li>
                                    <?php }else{ ?>      
                                        <li><a  href="<?php echo site_url('academic/subject/index/'); ?>"> <?php echo $this->lang->line('subject'); ?> </a></li>
                                    <?php } ?>
                                <?php } ?>
                                
                                        
                                <?php if(has_permission(VIEW, 'academic', 'syllabus')){ ?>                        
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li><a  href="<?php echo site_url('academic/syllabus/index/'); ?>"><?php echo $this->lang->line('syllabus'); ?></a> </li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li><a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a> </li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li><a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a> </li>
                                    <?php }else{ ?>      
                                        <li><a  href="<?php echo site_url('academic/syllabus/index/'); ?>"> <?php echo $this->lang->line('syllabus'); ?></a> </li>

                                    <?php } ?>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'academic', 'material')){ ?>                        
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li><a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a> </li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li><a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a> </li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>   
                                        <li><a  href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?></a> </li>
                                    <?php }else{ ?>      
                                        <li><a href="<?php echo site_url('academic/material/index/'); ?>"> <?php echo $this->lang->line('material'); ?> </a>        
                                        </li> 
                                    <?php } ?>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'academic', 'routine')){ ?>
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li> <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li> <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li> <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a></li>
                                    <?php }else{ ?>    
                                        <li> <a  href="<?php echo site_url('academic/routine/index/'); ?>">  <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('routine'); ?></a>
                                        
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                        </ul>
                                        </li>
                                <?php } ?>        
                                <?php if(has_permission(VIEW, 'assignment', 'assignment')){ ?>                        
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li>  <a href="<?php echo site_url('assignment/index/'); ?>"><?php echo $this->lang->line('assignment'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li>  <a href="<?php echo site_url('assignment/index/'); ?>"><?php echo $this->lang->line('assignment'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li>  <a href="<?php echo site_url('assignment/index/'); ?>"> <?php echo $this->lang->line('assignment'); ?></a></li>
                                    <?php }else{ ?>
                                        <li>  <a href="<?php echo site_url('assignment/index/'); ?>"> <?php echo $this->lang->line('assignment'); ?></a></li>
                                    <?php } ?>
                                <?php } ?>    
                                <?php if(has_permission(VIEW, 'academic', 'promotion')){ ?>
                                    <li><a href="<?php echo site_url('academic/promotion'); ?>"><?php echo $this->lang->line('promotion'); ?></a></li>                   
                                <?php } ?> 
                                <?php if(has_permission(VIEW, 'library', 'book') || 
                                        has_permission(VIEW, 'library', 'member') || 
                                        has_permission(VIEW, 'library', 'issue') ||   
                                        has_permission(VIEW, 'library', 'ebook')){ ?>    
                                    <li><a> <?php echo $this->lang->line('library'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php if(has_permission(VIEW, 'library', 'book')){ ?>
                                                <li><a href="<?php echo site_url('library/book/index/'); ?>"><?php echo $this->lang->line('book'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'library', 'member')){ ?>
                                                <li><a href="<?php echo site_url('library/member/index/'); ?>"><?php echo $this->lang->line('library'); ?> <?php echo $this->lang->line('member'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'library', 'issue')){ ?>
                                                <li><a href="<?php echo site_url('library/issue/index'); ?>"><?php echo $this->lang->line('issue_and_return'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'library', 'ebook')){ ?>
                                                <li><a href="<?php echo site_url('library/ebook/index'); ?>"><?php echo $this->lang->line('e_book'); ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </li> 
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'event', 'event')){ ?>    
                                    <li><a href="<?php echo site_url('event/index/'); ?>"><?php echo $this->lang->line('event'); ?></a></li>
                                <?php } ?>
                                              
                                <?php if(has_permission(VIEW, 'complain', 'complain') || has_permission(VIEW, 'complain', 'type')){ ?>
                                <li><a> <?php echo $this->lang->line('complain'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'complain', 'type')){ ?>  
                                            <li><a href="<?php echo site_url('complain/type/index/'); ?>"><?php echo $this->lang->line('complain'); ?> <?php echo $this->lang->line('type'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'complain', 'complain')){ ?>  
                                            <li><a href="<?php echo site_url('complain/index/'); ?>"><?php echo $this->lang->line('complain'); ?> </a></li>
                                        <?php } ?>
                                    </ul>
                                </li>   
                            <?php } ?>       
                             
                            
							</ul>
							</li>
                    <?php } ?>                   
                            
                    <?php if(has_permission(VIEW, 'student', 'type') || 
                            has_permission(ADD, 'student', 'student') ||
                            has_permission(ADD, 'student', 'bulk') ||
                            has_permission(ADD, 'student', 'student') ||
                            has_permission(ADD, 'student', 'admission') ||
                            has_permission(ADD, 'student', 'activity') ||
                            has_permission(VIEW, 'card', 'idsetting') || 
                            has_permission(VIEW, 'card', 'schoolidsetting') ||
                            has_permission(VIEW, 'card', 'admitsetting') ||
                            has_permission(VIEW, 'card', 'schooladmitsetting') ||
                            has_permission(VIEW, 'card', 'teacher') ||
                            has_permission(VIEW, 'card', 'employee') ||
                            has_permission(VIEW, 'card', 'student') ||
                            has_permission(VIEW, 'card', 'admit') ||
                            has_permission(VIEW, 'card', 'idsetting') || 
                            has_permission(VIEW, 'card', 'schoolidsetting') ||
                            has_permission(VIEW, 'card', 'admitsetting') ||
                            has_permission(VIEW, 'card', 'schooladmitsetting') ||
                            has_permission(VIEW, 'card', 'teacher') ||
                            has_permission(VIEW, 'card', 'employee') ||
                            has_permission(VIEW, 'card', 'student') ||
                            has_permission(VIEW, 'card', 'admit')
                            ){ ?> 
                    
                            <li><a ><i class="fa fa-group"></i> <?php echo $this->lang->line('student'); ?> </a>
                            <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'student', 'type') || 
                                        has_permission(ADD, 'student', 'student') ||
                                        has_permission(ADD, 'student', 'bulk') ||
                                        has_permission(ADD, 'student', 'student') ||
                                        has_permission(ADD, 'student', 'admission') ||
                                        has_permission(ADD, 'student', 'activity') ||
                                        has_permission(VIEW, 'card', 'idsetting') || 
                                        has_permission(VIEW, 'card', 'schoolidsetting') ||
                                        has_permission(VIEW, 'card', 'admitsetting') ||
                                        has_permission(VIEW, 'card', 'schooladmitsetting') ||
                                        has_permission(VIEW, 'card', 'teacher') ||
                                        has_permission(VIEW, 'card', 'employee') ||
                                        has_permission(VIEW, 'card', 'student') ||
                                        has_permission(VIEW, 'card', 'admit')
                                        ){ ?> 
                                    
                                    <?if($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li><a href="<?php echo site_url('student/index'); ?>"></i> <?php echo $this->lang->line('student'); ?> </a></li>
                                    <?php }elseif($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1 || $this->session->userdata('role_id') == TEACHER){ ?>
                                    
                                    <li><a> <?php echo $this->lang->line('student'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                                <?php if(has_permission(VIEW, 'student', 'type')){ ?>
                                                    <li><a href="<?php echo site_url('student/type/index'); ?>"> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('type'); ?></a></li>
                                                <?php } ?> 
                                                <?php if(has_permission(VIEW, 'student', 'student')){ ?>
                                                    <li><a href="<?php echo site_url('student/index'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('student'); ?></a></li>
                                                <?php } ?> 
                                                <?php if(has_permission(VIEW, 'student', 'student')){ ?>
                                                    <li><a href="<?php echo site_url('student/alumni'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('alumni_students'); ?></a></li>
                                                <?php } ?> 
                                                <?php if(has_permission(ADD, 'student', 'student')){ ?>
                                                    <li><a href="<?php echo site_url('student/add/'); ?>"> <?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('student'); ?></a></li>
                                                <?php } ?> 
                                                <?php /*if(has_permission(ADD, 'student', 'bulk')){ ?>
                                                    <li><a href="<?php echo site_url('student/bulk/add'); ?>"> <?php echo $this->lang->line('bulk'); ?> <?php echo $this->lang->line('admission'); ?></a></li>
                                                <?php } */?> 
                                                <?php if(has_permission(VIEW, 'student', 'admission')){ ?>
                                                    <li><a href="<?php echo site_url('student/admission/index'); ?>"> <?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('admission'); ?></a></li>
                                                <?php } ?> 
                                                <?php if(has_permission(VIEW, 'student', 'activity')){ ?>
                                                <li><a href="<?php echo site_url('student/activity/index'); ?>"> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('activity'); ?></a></li>
                                                <?php } ?> 
                                                <?php if(has_permission(VIEW, 'guardian', 'guardian')){ ?>    
                                    <li><a href="<?php echo site_url('guardian/index/'); ?>"><?php echo $this->lang->line('guardian'); ?></a> </li>
                                <?php } ?>
                                        </ul>
                                    </li>   
                                        
                                    <?php }else{ ?>    
                                        <li><a> <?php echo $this->lang->line('student'); ?> <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <?php if(has_permission(ADD, 'student', 'type')){ ?>
                                                    <li><a href="<?php echo site_url('student/type/index'); ?>"> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('type'); ?> </a></li>
                                                <?php } ?>
                                                <?php if(has_permission(ADD, 'student', 'student')){ ?>
                                                    <li><a href="<?php echo site_url('student/add/'); ?>"> <?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('student'); ?></a></li>
                                                <?php } ?>
                                                <?php if(has_permission(VIEW, 'student', 'student')){ ?>
                                                    <li><a href="<?php echo site_url('student/alumni'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('alumni_students'); ?></a></li>
                                                <?php } ?> 
                                                <?php if(has_permission(ADD, 'student', 'bulk')){ ?>
                                                    <li><a href="<?php echo site_url('student/bulk/add'); ?>"> <?php echo $this->lang->line('bulk'); ?> <?php echo $this->lang->line('admission'); ?></a></li>
                                                <?php } ?>     
                                                <?php if(has_permission(VIEW, 'student', 'admission')){ ?>
                                                    <li><a href="<?php echo site_url('student/admission/index'); ?>"> <?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('admission'); ?></a></li>
                                                <?php } ?>     
                                                <li><a href="<?php echo site_url('student/index/'); ?>"><?php echo $this->lang->line('manage_student'); ?>  </a></li>  
                                                <?php if(has_permission(ADD, 'student', 'activity')){ ?>
                                                <li><a href="<?php echo site_url('student/activity/index'); ?>"> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('activity'); ?></a></li>
                                                <?php } ?>       
                                                    
                                            </ul>
                                        </li> 
                                    <?php } ?>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'card', 'idsetting') || 
                                    has_permission(VIEW, 'card', 'schoolidsetting') ||
                                    has_permission(VIEW, 'card', 'admitsetting') ||
                                    has_permission(VIEW, 'card', 'schooladmitsetting') ||
                                    has_permission(VIEW, 'card', 'teacher') ||
                                    has_permission(VIEW, 'card', 'employee') ||
                                    has_permission(VIEW, 'card', 'student') ||
                                    has_permission(VIEW, 'card', 'admit')){ ?>
                                    
                                <li><a> <?php echo $this->lang->line('generate'); ?> <?php echo $this->lang->line('card'); ?><span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">                                
                                        <?php if(has_permission(VIEW, 'card', 'schoolidsetting')){ ?>
                                            <li><a href="<?php echo site_url('card/schoolidsetting/index'); ?>"><?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                        <?php } ?>     
                                        <?php if(has_permission(VIEW, 'card', 'schooladmitsetting')){ ?>
                                            <li><a href="<?php echo site_url('card/schooladmitsetting/index'); ?>"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('setting'); ?></a></li>
                                        <?php } ?>
                                                
                                        <?php if(has_permission(VIEW, 'card', 'teacher')){ ?>
                                            <li><a href="<?php echo site_url('card/teacher/index'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'card', 'employee')){ ?>
                                            <li><a href="<?php echo site_url('card/employee/index'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'card', 'student')){ ?>  
                                            <li><a href="<?php echo site_url('card/student/index'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('id'); ?> <?php echo $this->lang->line('card'); ?></a></li>
                                        <?php } ?>                                  
                                        <?php if(has_permission(VIEW, 'card', 'admit')){ ?>  
                                            <li><a href="<?php echo site_url('card/admit/index'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('card'); ?></a></li>
                                        <?php } ?>                                  
                                    </ul>
                                </li> 
                            <?php } ?>
                            </ul>
                        
                        
                        </li>
                    <?php } ?>
                                            
                    <?php if(has_permission(VIEW, 'exam', 'grade') || has_permission(VIEW, 'exam', 'exam') || has_permission(VIEW, 'exam', 'schedule') || has_permission(VIEW, 'exam', 'suggestion') || has_permission(VIEW, 'exam', 'attendance') ||has_permission(VIEW, 'exam', 'mark') || 
                               has_permission(VIEW, 'exam', 'examresult') || 
                               has_permission(VIEW, 'exam', 'finalresult') || 
                               has_permission(VIEW, 'exam', 'meritlist') || 
                               has_permission(VIEW, 'exam', 'marksheet') || 
                               has_permission(VIEW, 'exam', 'resultcard') || 
                               has_permission(VIEW, 'exam', 'text') || 
                               has_permission(VIEW, 'exam', 'mail') || 
                               has_permission(VIEW, 'exam', 'resultemail') || 
                               has_permission(VIEW, 'exam', 'resultsms' )){ ?>    
                        <li><a><i class="fa fa-graduation-cap"></i> <?php echo $this->lang->line('exam'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'exam', 'grade') || has_permission(VIEW, 'exam', 'exam') || has_permission(VIEW, 'exam', 'schedule') || has_permission(VIEW, 'exam', 'suggestion') || has_permission(VIEW, 'exam', 'attendance') ||has_permission(VIEW, 'exam', 'mark') || 
                                        has_permission(VIEW, 'exam', 'examresult') || 
                                        has_permission(VIEW, 'exam', 'finalresult') || 
                                        has_permission(VIEW, 'exam', 'meritlist') || 
                                        has_permission(VIEW, 'exam', 'marksheet') || 
                                        has_permission(VIEW, 'exam', 'resultcard') || 
                                        has_permission(VIEW, 'exam', 'text') || 
                                        has_permission(VIEW, 'exam', 'mail') || 
                                        has_permission(VIEW, 'exam', 'resultemail') || 
                                        has_permission(VIEW, 'exam', 'resultsms' )){ ?>    
                                    <li><a></i> <?php echo $this->lang->line('exam'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php if(has_permission(VIEW, 'exam', 'grade')){ ?>
                                                <li><a href="<?php echo site_url('exam/grade/'); ?>"><?php echo $this->lang->line('exam_grade'); ?></a></li>                         
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'exam')){ ?>
                                                <li><a href="<?php echo site_url('exam/index'); ?>"><?php echo $this->lang->line('exam_term'); ?></a></li>                         
                                            <?php } ?> 
                                            <?php if(has_permission(VIEW, 'exam', 'schedule')){ ?>                        
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li><a href="<?php echo site_url('exam/schedule/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('schedule'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li><a href="<?php echo site_url('exam/schedule/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('schedule'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li><a href="<?php echo site_url('exam/schedule/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('schedule'); ?></a></li>
                                    <?php }else{ ?>
                                        <li><a href="<?php echo site_url('exam/schedule/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('schedule'); ?></a></li>
                                    <?php } ?>   
                                <?php } ?> 
                                    <!-- <?php if(has_permission(VIEW, 'exam', 'suggestion')){ ?>                            
                                    <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                        <li><a href="<?php echo site_url('exam/suggestion/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('suggestion'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <li><a href="<?php echo site_url('exam/suggestion/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('suggestion'); ?></a></li>
                                    <?php }elseif($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li><a href="<?php echo site_url('exam/suggestion/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('suggestion'); ?></a></li>
                                    <?php }else{ ?>    
                                        <li><a href="<?php echo site_url('exam/suggestion/index/'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('suggestion'); ?></a></li>
                                    <?php } ?> 
                                <?php } ?>  -->
                                    
                                <!-- <?php if(has_permission(VIEW, 'exam', 'attendance')){ ?>
                                    <li><a  href="<?php echo site_url('exam/attendance/'); ?>"> <?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('attendance'); ?></a></li>
                                <?php } ?> 		 -->

                                <?php if(has_permission(VIEW, 'exam', 'mark') || 
                                        has_permission(VIEW, 'exam', 'examresult') || 
                                        has_permission(VIEW, 'exam', 'finalresult') || 
                                        has_permission(VIEW, 'exam', 'meritlist') || 
                                        has_permission(VIEW, 'exam', 'marksheet') || 
                                        has_permission(VIEW, 'exam', 'resultcard') || 
                                        has_permission(VIEW, 'exam', 'text') || 
                                        has_permission(VIEW, 'exam', 'mail') || 
                                        has_permission(VIEW, 'exam', 'resultemail') || 
                                        has_permission(VIEW, 'exam', 'resultsms')){ ?>    
                                    <li><a> <?php echo $this->lang->line('exam_mark'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php if(has_permission(VIEW, 'exam', 'mark')){ ?>
                                                <li><a href="<?php echo site_url('exam/mark/index'); ?>"><?php echo $this->lang->line('manage_mark'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'examresult')){ ?>
                                                <li><a href="<?php echo site_url('exam/examresult/index'); ?>"><?php echo $this->lang->line('exam_term'); ?> <?php echo $this->lang->line('result'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'finalresult')){ ?>
                                                <li><a href="<?php echo site_url('exam/finalresult/index'); ?>"><?php echo $this->lang->line('exam_final_result'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'meritlist')){ ?>
                                                <li><a href="<?php echo site_url('exam/meritlist/index'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('merit_list'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'marksheet')){ ?>
                                                <li><a href="<?php echo site_url('exam/marksheet/index'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('mark_sheet'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'resultcard')){ ?>
                                                <li><a href="<?php echo site_url('exam/resultcard/index'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('result_card'); ?></a></li>
                                            <?php } ?>                               
                                            <?php if(has_permission(VIEW, 'exam', 'mail')){ ?>
                                                <li><a href="<?php echo site_url('exam/mail/index'); ?>"><?php echo $this->lang->line('mark_send_by_email'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'text')){ ?>
                                                <li><a href="<?php echo site_url('exam/text/index'); ?>"><?php echo $this->lang->line('mark_send_by_sms'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'resultemail')){ ?>  
                                                <li><a href="<?php echo site_url('exam/resultemail/index'); ?>"><?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('email'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'exam', 'resultsms')){ ?>  
                                                <li><a href="<?php echo site_url('exam/resultsms/index'); ?>"><?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('sms'); ?></a></li>
                                            <?php } ?>    
                                        </ul>
                                    </li>
                                <?php } ?>
                                        </ul>
                                    </li> 
                                <?php } ?>


                                <?php if(has_permission(VIEW, 'onlineexam', 'onlineexam') || has_permission(VIEW, 'onlineexam', 'questions')){ ?>    
                                    <li><a> <?php echo $this->lang->line('online'); ?>  <?php echo $this->lang->line('exam'); ?><span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php if($this->session->userdata('role_id') != STUDENT){           
                                                    if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>
                                                <li><a href="<?php echo site_url('onlineexam/'); ?>"><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('exam'); ?></a></li>                         
                                            <?php } ?>
                                        
                                            <?php if(has_permission(VIEW, 'onlineexam', 'question')){ ?>
                                                <li><a href="<?php echo site_url('question/'); ?>"><?php echo $this->lang->line('questions'); ?></a></li>                         
                                            <?php } } else{  
                                            if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>
                                                <li><a href="<?php echo site_url('user/onlineexam/'); ?>"><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('exam'); ?></a></li>                         
                                            <?php } ?>                                
                                            <?php }?>
                                        </ul>
                                    </li> 
                                <?php } ?>



                            </ul>
                        </li>
                    
                        <?php } ?> 		
                  
               
                    
                            
                     
                    
                    
                    <li><a><i class="fa fa-user-md"></i>  <?php echo $this->lang->line('human_resource'); ?> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php /*if(has_permission(VIEW, 'hrm', 'designation')){ ?>   
                                <li><a href="<?php echo site_url('hrm/designation'); ?>"><?php echo $this->lang->line('manage_designation'); ?></a></li>
                            <?php } */?>
                            <li><a>  <?php echo $this->lang->line('human_resource'); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <?php /*if(has_permission(VIEW, 'hrm', 'designation')){ ?>   
                                        <li><a href="<?php echo site_url('hrm/designation'); ?>"><?php echo $this->lang->line('manage_designation'); ?></a></li>
                                    <?php } */?>
                                    <?php if(has_permission(VIEW, 'hrm', 'employment_types')){ ?>   
                                        <li><a href="<?php echo site_url('hrm/employmentTypes'); ?>"><?php echo $this->lang->line('manage')." ".$this->lang->line('employment_types'); ?></a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'hrm', 'employee')){ ?>   
                                        <li><a href="<?php echo site_url('hrm/employee'); ?>"><?php echo $this->lang->line('manage_employee'); ?></a></li>                            
                                    <?php } ?>							
                                    <?php if(has_permission(VIEW, 'hrm', 'alumniemployees')){ ?>   
                                        <li><a href="<?php echo site_url('hrm/employee/alumni'); ?>"><?php echo $this->lang->line('manage_alumni_employees'); ?></a></li>                            
                                    <?php } ?>
                                </ul>
                            </li> 
                            <?php if(has_permission(VIEW, 'leave', 'leave') || has_permission(VIEW, 'leave', 'type')){ ?>
                        <li><a> <?php echo $this->lang->line('manage_leave'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'leave', 'type')){ ?>  
                                    <li><a href="<?php echo site_url('leave/type/index'); ?>"><?php echo $this->lang->line('leave_type'); ?></a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'leave', 'application')){ ?>  
                                    <li><a href="<?php echo site_url('leave/application/index'); ?>"><?php echo $this->lang->line('leave_application'); ?> </a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'leave', 'waiting')){ ?>  
                                    <li><a href="<?php echo site_url('leave/waiting/index'); ?>"><?php echo $this->lang->line('waiting_application'); ?></a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'leave', 'approve')){ ?>  
                                    <li><a href="<?php echo site_url('leave/approve/index'); ?>"><?php echo $this->lang->line('approved_application'); ?></a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'leave', 'decline')){ ?>  
                                    <li><a href="<?php echo site_url('leave/decline/index'); ?>"><?php echo $this->lang->line('declined_application'); ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>   
                    <?php } ?>    
                    <?php if(has_permission(VIEW, 'teacher', 'teacher') || has_permission(VIEW, 'teacher', 'alumniteacher')){ ?>
                        <li>
						<a> <?php echo $this->lang->line('teacher'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
							<?php if(has_permission(VIEW, 'teacher', 'teacher')){ ?>
							<li><a href="<?php echo site_url('teacher'); ?>"><?php echo $this->lang->line('teacher'); ?></a> </li>  
							<?php } ?>												
							<?php if(has_permission(VIEW, 'teacher', 'alumniteachers')){ ?>
							<li><a href="<?php echo site_url('teacher/alumni'); ?>"><?php echo $this->lang->line('alumni_teachers'); ?></a> </li>  
							<?php } ?>
							</ul>
							</li>
                    <?php } /*?>
                            <li><a><?php echo $this->lang->line('profile'); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <!--<li><a href="<?php echo site_url('profile'); ?>"><?php echo $this->lang->line('my_profile'); ?></a></li>-->
                                    <li><a href="<?php echo site_url('profile/password'); ?>"><?php echo $this->lang->line('reset_password'); ?></a></li>
                                    <?php if($this->session->userdata('role_id') == GUARDIAN){ ?>
                                        <!-- <li><a href="<?php echo site_url('guardian/invoice'); ?>"><?php echo $this->lang->line('invoice'); ?></a></li> -->
                                        <li><a href="<?php echo site_url('guardian/feedback'); ?>"><?php echo $this->lang->line('feedback'); ?></a></li>
                                    <?php } ?>
                                    <!-- <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                        <li><a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('invoice'); ?></a></li>
                                    <?php } ?> -->
                                        
                                    <?php if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){ ?>  
                                        <?php if(has_permission(VIEW, 'usercomplain', 'usercomplain')){ ?>
                                            <li><a href="<?php echo site_url('usercomplain/index'); ?>"><?php echo $this->lang->line('complain'); ?></a></li>    
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'userleave', 'userleave')){ ?>
                                            <li><a href="<?php echo site_url('userleave/index'); ?>"><?php echo $this->lang->line('leave'); ?></a></li>    
                                        <?php } ?>
                                    <?php } ?>
                                        
                                    <li><a href="<?php echo site_url('auth/logout'); ?>"><?php echo $this->lang->line('logout'); ?></a></li>
                                </ul>
                            </li>  
                            <?php */ ?>
                                      
                            <?php if(has_permission(VIEW, 'attendance', 'student') || 
                                    has_permission(VIEW, 'attendance', 'teacher') || 
                                    has_permission(VIEW, 'attendance', 'employee') || 
                                    has_permission(VIEW, 'attendance', 'absentemail') || 
                                    has_permission(VIEW, 'attendance', 'absentsms')){ ?>
                                    
                                <li><a> <?php echo $this->lang->line('attendance'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'attendance', 'student')){ ?>                                    
                                            <?php if($this->session->userdata('role_id') == GUARDIAN){ ?>
                                                <li><a href="<?php echo site_url('attendance/student/guardian'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?></a></li>
                                            <?php }else{ ?>   
                                                <li><a href="<?php echo site_url('attendance/student'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?></a></li>
                                            <?php } ?>   
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'attendance', 'teacher')){ ?>
                                            <li><a href="<?php echo site_url('attendance/teacher'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('attendance'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'attendance', 'employee')){ ?>
                                            <li><a href="<?php echo site_url('attendance/employee'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('attendance'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'attendance', 'absentemail')){ ?>  
                                            <li><a href="<?php echo site_url('attendance/absentemail/index/'); ?>"><?php echo $this->lang->line('absent'); ?> <?php echo $this->lang->line('email'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'attendance', 'absentsms')){ ?>  
                                            <li><a href="<?php echo site_url('attendance/absentsms/index/'); ?>"><?php echo $this->lang->line('absent'); ?> <?php echo $this->lang->line('sms'); ?></a></li>
                                        <?php } ?>     
                                    </ul>
                                </li> 
                            <?php } ?>
                    
						
                        </ul>
                    </li> 
                    
                    
                     
                    
                   

                            
                   
                    
                    <!-- ak 06-03-2021 -->
               
                    <?php if(has_permission(VIEW, 'accounting', 'discount') || 
                            has_permission(VIEW, 'accounting', 'feetype') || 
                            has_permission(VIEW, 'accounting', 'invoice') || 
                            has_permission(VIEW, 'accounting', 'duefeeemail')  || 
                            has_permission(VIEW, 'accounting', 'duefeesms') || 
                            has_permission(VIEW, 'accounting', 'exphead') || 
                            has_permission(VIEW, 'accounting', 'expenditure') || 
                            has_permission(VIEW, 'accounting', 'incomehead') || 
                            has_permission(VIEW, 'accounting', 'income')||
                            has_permission(VIEW, 'inventory', 'itemcategory') ||
                            has_permission(VIEW, 'payroll', 'grade') ||
                             has_permission(VIEW, 'payroll', 'payment') ||
                             has_permission(VIEW, 'accounting', 'discount') || 
                            has_permission(VIEW, 'accounting', 'feetype') || 
                            has_permission(VIEW, 'accounting', 'invoice') || 
                            has_permission(VIEW, 'accounting', 'duefeeemail')  || 
                            has_permission(VIEW, 'accounting', 'duefeesms') || 
                            has_permission(VIEW, 'accounting', 'exphead') || 
                            has_permission(VIEW, 'accounting', 'expenditure') || 
                            has_permission(VIEW, 'accounting', 'incomehead') || 
                            has_permission(VIEW, 'accounting', 'income')
                            ){ ?>          
                    <li><a><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounting'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'accounting', 'discount') || 
                                        has_permission(VIEW, 'accounting', 'feetype') || 
                                        has_permission(VIEW, 'accounting', 'invoice') || 
                                        has_permission(VIEW, 'accounting', 'duefeeemail')  || 
                                        has_permission(VIEW, 'accounting', 'duefeesms') || 
                                        has_permission(VIEW, 'accounting', 'exphead') || 
                                        has_permission(VIEW, 'accounting', 'expenditure') || 
                                        has_permission(VIEW, 'accounting', 'incomehead') || 
                                        has_permission(VIEW, 'accounting', 'donation') || 
                                        has_permission(VIEW, 'accounting', 'income')){ ?>                
                                    <li><a><?php echo $this->lang->line('fee'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            
                                        <?php if(has_permission(VIEW, 'accounting', 'discount')){ ?>
                                                <li><a href="<?php echo site_url('accounting/discount'); ?>"><?php echo $this->lang->line('discount'); ?></a></li> 
                                            <?php } ?>
                                        <?php if(has_permission(VIEW, 'accounting', 'feetype')){ ?>
                                                <li><a href="<?php echo site_url('accounting/feetype'); ?>"> <?php echo $this->lang->line('fee_type'); ?></a></li> 
                                            <?php } ?>                              
                                        
                                            <?php if(has_permission(VIEW, 'accounting', 'invoice')){ ?>
                                                
                                                <?php if($this->session->userdata('role_id') == STUDENT || $this->session->userdata('role_id') == GUARDIAN){ ?>
                                                    <li><a href="<?php echo site_url('accounting/invoice/index'); ?>"><?php echo $this->lang->line('manage_invoice'); ?></a></li>                            
                                                    <li><a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a></li>
                                                <?php }else{ ?>
                                                    <li><a href="<?php echo site_url('accounting/invoice/add'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?></a></li>                            
                                                    <li><a href="<?php echo site_url('accounting/invoice/index'); ?>"><?php echo $this->lang->line('manage_invoice'); ?></a></li>                            
                                                    <!-- <li><a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_fee'); ?></a></li> -->
                                                <?php } ?>                                    
                                            <?php } ?>
                                                    
                                            <?php if(has_permission(VIEW, 'accounting', 'duefeeemail')){ ?>  
                                                <li><a href="<?php echo site_url('accounting/duefeeemail/index/'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('email'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'accounting', 'duefeesms')){ ?>  
                                                <li><a href="<?php echo site_url('accounting/duefeesms/index/'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('sms'); ?></a></li>
                                            <?php } ?>
                                                
                                                
                                                                        
                                        </ul>
                                    </li> 
                                    <?php if(has_permission(VIEW, 'accounting', 'donation')){ ?>
                                    <li><a><?php echo $this->lang->line('donation'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'accounting', 'donation')){ ?>
                                                    <li><a href="<?php echo site_url('accounting/donation/add'); ?>"><?php echo $this->lang->line('donation'); ?> <?php echo $this->lang->line('collection'); ?></a></li>                            
                                                    <li><a href="<?php echo site_url('accounting/donation/index'); ?>"><?php echo $this->lang->line('manage_donation'); ?></a></li>                            
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <?php } ?>

                                <?php } ?>
                                <?php if(has_permission(VIEW, 'inventory', 'itemcategory')){ ?>    
                                    <li><a><?php echo $this->lang->line('inventory'); ?>  <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'itemgroup', 'itemgroup')){ ?>
                                                <li><a href="<?php echo site_url('itemgroup/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('group'); ?></a></li>                         
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'inventory', 'itemcategory')){ ?>
                                                <li><a href="<?php echo site_url('itemcategory/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('category'); ?></a></li>                         
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'inventory', 'itemstore')){ ?>
                                                <li><a href="<?php echo site_url('itemstore/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('store'); ?></a></li>                         
                                            <?php } ?>
                                             
                                            <?php if(has_permission(VIEW, 'inventory', 'itemsupplier')){ ?>
                                                <li><a href="<?php echo site_url('itemsupplier/'); ?>"><?php echo $this->lang->line('item'). " ".$this->lang->line('supplier'); ?></a></li>                         
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'inventory', 'item')){ ?>
                                                <li><a href="<?php echo site_url('item/'); ?>"><?php echo $this->lang->line('item'); ?> </a></li>                         
                                            <?php } ?>
                                          
                                            <?php } ?>
                                        <?php if(has_permission(VIEW, 'inventory', 'itemstock')){ ?>
                                                <li><a href="<?php echo site_url('itemstock/'); ?>"><?php echo $this->lang->line('itemstock') ?> </a></li>                         
                                            <?php } ?>
                                         
                                        <?php if(has_permission(VIEW, 'inventory', 'issueitem')){ ?>
                                                <li><a href="<?php echo site_url('issueitem/'); ?>"><?php echo $this->lang->line('issueitem')?> </a></li>                         
                                      
                                          
                                        </ul>
                                    </li> 
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'payroll', 'grade') || has_permission(VIEW, 'payroll', 'payment')){ ?>
                                    <li><a> <?php echo $this->lang->line('payroll'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'payroll', 'paygroup')){ ?>  
                                                <li><a href="<?php echo site_url('payroll/paygroups/index'); ?>"><?php echo $this->lang->line('pay_group'); ?></a></li>
                                            <?php } ?>	
                                        <?php if(has_permission(VIEW, 'payroll', 'payscalecategory')){ ?>  
                                                <li><a href="<?php echo site_url('payroll/payscalecategory/index'); ?>"><?php echo $this->lang->line('salary_grade'); ?></a></li>
                                            <?php } ?>								
                                            <?php if(has_permission(VIEW, 'payroll', 'payment')){ ?>  
                                                <li><a href="<?php echo site_url('payroll/payment/index'); ?>"> <?php echo $this->lang->line('salary'); ?> <?php echo $this->lang->line('payment'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'payroll', 'payment')){ ?>  
                                                <li><a href="<?php echo site_url('payroll/history/index'); ?>"> <?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('history'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'report', 'report')){ ?>
                                            <li><a href="<?php echo site_url('report/payroll'); ?>"><?php echo $this->lang->line('payroll'); ?> <?php echo $this->lang->line('report'); ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>   
                                <?php } ?> 
                                                     
                                <?php if(has_permission(VIEW, 'accounting', 'discount') || 
                                        has_permission(VIEW, 'accounting', 'feetype') || 
                                        has_permission(VIEW, 'accounting', 'invoice') || 
                                        has_permission(VIEW, 'accounting', 'duefeeemail')  || 
                                        has_permission(VIEW, 'accounting', 'duefeesms') || 
                                        has_permission(VIEW, 'accounting', 'exphead') || 
                                        has_permission(VIEW, 'accounting', 'expenditure') || 
                                        has_permission(VIEW, 'accounting', 'incomehead') || 
                                        has_permission(VIEW, 'accounting', 'income')){ ?>                
                                    <li><a> <?php echo $this->lang->line('accounting'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'administrator', 'financialyear')){ ?>   
                                                <li><a href="<?php echo site_url('administrator/financialyear'); ?>"> <?php echo $this->lang->line('financial_year'); ?></a></li>
                                            <?php } ?>								 
                                        <?php if(has_permission(VIEW, 'accounting', 'accountgroups')){ ?>
                                                <li><a href="<?php echo site_url('accountgroups'); ?>"><?php echo $this->lang->line('account'). " ".$this->lang->line('group'); ?></a></li> 
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'accounting', 'accountledgers')){ ?>
                                                <li><a href="<?php echo site_url('accountledgers'); ?>"><?php echo $this->lang->line('account'). " ".$this->lang->line('ledger'); ?></a></li> 
                                            <?php } ?>
                                            
                                            <?php if(has_permission(VIEW, 'accounting', 'trialbalance')){ ?>
                                                <li><a href="<?php echo site_url('trialbalance'); ?>"><?php echo $this->lang->line('trial_balance'); ?></a></li> 
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'accounting', 'balancesheet')){ ?>
                                                <li><a href="<?php echo site_url('balancesheet'); ?>"><?php echo $this->lang->line('balancesheet'); ?></a></li> 
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'accounting', 'incomestatement')){ ?>
                                                <li><a href="<?php echo site_url('incomestatement'); ?>"><?php echo $this->lang->line('income_statement'); ?></a></li> 
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'accounting', 'daybook')){ ?>
                                                <li><a href="<?php echo site_url('daybook'); ?>"><?php echo $this->lang->line('daybook'); ?></a></li> 
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'accounting', 'vouchers')){ ?>
                                                <li><a href="<?php echo site_url('vouchers'); ?>"><?php echo $this->lang->line('voucher_books'); ?></a></li> 
                                            <?php } ?>								
                                            <?php if(has_permission(VIEW, 'accounting', 'paymentmodes')){ ?>
                                                <li><a href="<?php echo site_url('paymentmodes'); ?>"><?php echo $this->lang->line('payment_modes'); ?></a></li> 
                                            <?php } ?>
                                        <!--  <?php if(has_permission(VIEW, 'accounting', 'discount')){ ?>
                                                <li><a href="<?php echo site_url('accounting/discount'); ?>"><?php echo $this->lang->line('discount'); ?></a></li> 
                                            <?php } ?>
                                        <?php if(has_permission(VIEW, 'accounting', 'feetype')){ ?>
                                                <li><a href="<?php echo site_url('accounting/feetype'); ?>"> <?php echo $this->lang->line('fee_type'); ?></a></li> 
                                            <?php } ?>								
                                        
                                            <?php if(has_permission(VIEW, 'accounting', 'invoice')){ ?>
                                                
                                                <?php if($this->session->userdata('role_id') == STUDENT || $this->session->userdata('role_id') == GUARDIAN){ ?>
                                                    <li><a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_invoice'); ?></a></li>
                                                <?php }else{ ?>
                                                    <li><a href="<?php echo site_url('accounting/invoice/add'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?></a></li>                            
                                                    <li><a href="<?php echo site_url('accounting/invoice/index'); ?>"><?php echo $this->lang->line('manage_invoice'); ?></a></li>                            
                                                    <li><a href="<?php echo site_url('accounting/invoice/due'); ?>"><?php echo $this->lang->line('due_fee'); ?></a></li>
                                                <?php } ?>                                    
                                            <?php } ?>
                                                    
                                            <?php if(has_permission(VIEW, 'accounting', 'duefeeemail')){ ?>  
                                                <li><a href="<?php echo site_url('accounting/duefeeemail/index/'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('email'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'accounting', 'duefeesms')){ ?>  
                                                <li><a href="<?php echo site_url('accounting/duefeesms/index/'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('sms'); ?></a></li>
                                            <?php } ?>
                                                -->
                                            <?php  if(has_permission(VIEW, 'accounting', 'incomehead')){ ?>
                                                <li><a href="<?php echo site_url('accounting/incomehead'); ?>"><?php echo $this->lang->line('income_head'); ?></a></li> 
                                            <?php } ?>
                                            <?php /*if(has_permission(VIEW, 'accounting', 'income')){ ?>
                                                <li><a href="<?php echo site_url('accounting/income'); ?>"><?php echo $this->lang->line('income'); ?></a></li> 
                                            <?php }*/ ?>        
                                            <?php if(has_permission(VIEW, 'accounting', 'exphead')){ ?>
                                                <li><a href="<?php echo site_url('accounting/exphead'); ?>"><?php echo $this->lang->line('expenditure_head'); ?></a></li>
                                            <?php } ?>
                                            <?php /*if(has_permission(VIEW, 'accounting', 'expenditure')){ ?>
                                                <li><a href="<?php echo site_url('accounting/expenditure'); ?>"><?php echo $this->lang->line('expenditure'); ?></a></li>
                                            <?php } */ ?>                                
                                        </ul>
                                    </li> 
                                <?php } ?>
                            </ul>
                    </li>
                    <?php } ?> 
                    <?php if(has_permission(VIEW, 'hostel', 'hostel') || 
                            has_permission(VIEW, 'hostel', 'room') || 
                            has_permission(VIEW, 'hostel', 'member')){ ?>        
                        <li><a><i class="fa fa-hotel"></i> <?php echo $this->lang->line('hostel'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'hostel', 'hostel')){ ?>
                                    <li><a href="<?php echo site_url('hostel/index/'); ?>"><?php echo $this->lang->line('manage_hostel'); ?></a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'hostel', 'room')){ ?>
                                    <li><a href="<?php echo site_url('hostel/room/index/'); ?>"><?php echo $this->lang->line('manage_room'); ?></a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'hostel', 'member')){ ?>
                                    <li><a href="<?php echo site_url('hostel/member/index/'); ?>"><?php echo $this->lang->line('hostel'); ?> <?php echo $this->lang->line('member'); ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                   <?php } ?>
                 
                   <?php if(has_permission(VIEW, 'transport', 'vehicle') || 
                            has_permission(VIEW, 'transport', 'route') || 
                            has_permission(VIEW, 'transport', 'member')){ ?>        
                        <li><a><i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'transport', 'vehicle')){ ?>
                                    <li><a href="<?php echo site_url('transport/vehicle/index/'); ?>"><?php echo $this->lang->line('vehicle'); ?></a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'transport', 'route')){ ?>
                                    <li><a href="<?php echo site_url('transport/route/index/'); ?>"><?php echo $this->lang->line('manage_route'); ?></a></li>
                                <?php } ?>
                                <?php if(has_permission(VIEW, 'transport', 'member')){ ?>
                                    <li><a href="<?php echo site_url('transport/member/index/'); ?>"><?php echo $this->lang->line('transport'); ?> <?php echo $this->lang->line('member'); ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>  
                    <?php } ?>
                        
                  
                    <li><a ><i class="fa fa-comments-o"></i>Communication</a>
                        <ul class="nav child_menu">
                            <?php if(has_permission(VIEW, 'message', 'message')){ ?>    
                                <li><a href="<?php echo site_url('message/inbox'); ?>"><?php echo $this->lang->line('message'); ?></a></li>                   
                            <?php } ?>
                            <?php if(has_permission(VIEW, 'message', 'mail') || has_permission(VIEW, 'message', 'text')){ ?>
                                <li><a> <?php echo $this->lang->line('mail_and_sms'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'message', 'mail')){ ?>  
                                            <li><a href="<?php echo site_url('message/mail/index/'); ?>"><?php echo $this->lang->line('general'); ?> <?php echo $this->lang->line('email'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'message', 'text')){ ?>  
                                            <li><a href="<?php echo site_url('message/text/index/'); ?>"><?php echo $this->lang->line('general'); ?> <?php echo $this->lang->line('sms'); ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>   
                            <?php } ?>        
                            <li><a><?php echo $this->lang->line('help'); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a target="_blank" href="<?php echo site_url('documentation/user-manual.html'); ?>"><?php echo $this->lang->line('user_manual'); ?></a></li>
                                    <li><a target="_blank" href="<?php echo site_url('documentation/hindi-user-manual.pdf'); ?>"><?php echo $this->lang->line('user_manual_hindi'); ?></a></li>
                                </ul>
                            </li>  
                            <?php if(has_permission(VIEW, 'announcement', 'notice') || 
                                    has_permission(VIEW, 'announcement', 'news') || 
                                    has_permission(VIEW, 'announcement', 'holiday')){ ?>            
                                <li><a> <?php echo $this->lang->line('announcement'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'announcement', 'notice')){ ?>
                                            <li><a href="<?php echo site_url('announcement/notice/index/'); ?>"><?php echo $this->lang->line('notice'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'announcement', 'news')){ ?>
                                            <li><a href="<?php echo site_url('announcement/news/index/'); ?>"><?php echo $this->lang->line('news'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'announcement', 'holiday')){ ?>
                                            <li><a href="<?php echo site_url('announcement/holiday/index/'); ?>"><?php echo $this->lang->line('holiday'); ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>  
                            <?php } ?>
                        </ul>
                    </li>                   
                    <?php if(has_permission(VIEW, 'gallery', 'gallery') || has_permission(VIEW, 'gallery', 'image') || has_permission(VIEW, 'frontend', 'frontend') || has_permission(VIEW, 'frontend', 'slider')){ ?>     

                        <li><a><i class="fa fa-image"></i>Web Page<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if(has_permission(VIEW, 'gallery', 'gallery') || has_permission(VIEW, 'gallery', 'image')){ ?>     
                                <li><a><?php echo $this->lang->line('media_gallery'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'gallery', 'gallery')){ ?>
                                            <li><a href="<?php echo site_url('gallery/index'); ?>"><?php echo $this->lang->line('manage_gallery'); ?></a></li>
                                    <?php } ?>
                                    <?php if(has_permission(VIEW, 'gallery', 'image')){ ?>      
                                            <li><a href="<?php echo site_url('gallery/image/index'); ?>"><?php echo $this->lang->line('manage_gallery_image'); ?></a></li>
                                    <?php } ?>
                                    </ul>
                                </li> 
                                <?php } ?> 
                                
                                <?php if(has_permission(VIEW, 'frontend', 'frontend') || has_permission(VIEW, 'frontend', 'slider')){ ?>
                                <li><a><?php echo $this->lang->line('frontend'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <?php if(has_permission(VIEW, 'frontend', 'frontend')){ ?>
                                        <li><a href="<?php echo site_url('frontend/index'); ?>"> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('page'); ?></a></li>
                                        <?php } ?>
                                        <?php if(has_permission(VIEW, 'frontend', 'slider')){ ?>
                                            <li><a href="<?php echo site_url('frontend/slider/index'); ?>"> <?php echo $this->lang->line('manage_slider'); ?></a></li>
                                        <?php } ?>                            
                                        <?php if(has_permission(VIEW, 'frontend', 'about')){ ?>
                                            <li><a href="<?php echo site_url('frontend/about/index'); ?>"> <?php echo $this->lang->line('frontend'); ?> <?php echo $this->lang->line('about'); ?></a></li>
                                        <?php } ?>                            
                                    </ul>
                                </li>  
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                    
                  
	
                     
                        
                       
                        
                       
                    
                 
                        
                    
                    

                    
                   
                    
                   <?php if(has_permission(VIEW, 'report', 'report') || has_permission(VIEW, 'certificate', 'certificate') || has_permission(VIEW, 'certificate', 'type')){ ?>
                        <li><a> <i class="fa fa-bar-chart"></i> <?php echo $this->lang->line('report'); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <?php if(has_permission(VIEW, 'certificate', 'certificate') || has_permission(VIEW, 'certificate', 'type')){ ?>
                                    <li><a> <?php echo $this->lang->line('certificate'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php if(has_permission(VIEW, 'certificate', 'type')){ ?>
                                                <li><a href="<?php echo site_url('certificate/type'); ?>"><?php echo $this->lang->line('certificate'); ?> <?php echo $this->lang->line('type'); ?></a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'certificate', 'certificate')){ ?>
                                                <li><a href="<?php echo site_url('certificate/index'); ?>"><?php echo $this->lang->line('generate'); ?> <?php echo $this->lang->line('certificate'); ?></a></li>
                                            <?php } ?>                                
                                        </ul>
                                    </li>
                                    <?php } ?>
                                <?php if(has_permission(VIEW, 'report', 'report')){ ?>
                                    
                                    <li><a> <?php echo $this->lang->line('report'); ?> <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <!-- <li><a href="<?php echo site_url('report/income'); ?>"><?php echo $this->lang->line('income'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/expenditure'); ?>"><?php echo $this->lang->line('expenditure'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/invoice'); ?>"><?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/duefee'); ?>"><?php echo $this->lang->line('due_fee'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/feecollection'); ?>"><?php echo $this->lang->line('fee'); ?> <?php echo $this->lang->line('collection'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/balance'); ?>"><?php echo $this->lang->line('accounting'); ?> <?php echo $this->lang->line('balance'); ?> <?php echo $this->lang->line('report'); ?></a></li>  -->
                                            <!-- <li><a href="<?php echo site_url('report/library'); ?>"><?php echo $this->lang->line('library'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/sattendance'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/syattendance'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/tattendance'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('attendance'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/tyattendance'); ?>"><?php echo $this->lang->line('teacher'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/eattendance'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('attendance'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/eyattendance'); ?>"><?php echo $this->lang->line('employee'); ?> <?php echo $this->lang->line('yearly'); ?> <?php echo $this->lang->line('attendance'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/student'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <?php if(has_permission(VIEW, 'report', 'student_statics')){ ?>
                                            <li><a href="<?php echo site_url('/report/student_statics'); ?>"><?php echo $this->lang->line('student'); ?> Statics </a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'report', 'fee_report')){ ?>
                                            <li><a href="<?php echo site_url('/report/fee_report'); ?>"><?php echo $this->lang->line('fee'); ?> Report </a></li>
                                            <?php } ?>
                                            <?php if(has_permission(VIEW, 'report', 'teacher_report')){ ?>

                                            <li><a href="<?php echo site_url('/report/teacher_report'); ?>"><?php echo $this->lang->line('teacher');?> <?php echo $this->lang->line('report'); ?></a></li>
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'all_teacher_report')){ ?>

                                            <li><a href="<?php echo site_url('/report/all_teacher_report'); ?>">All <?php echo $this->lang->line('teacher');?> <?php echo $this->lang->line('report'); ?> </a></li>
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'faculty_report')){ ?>

                                            <li><a href="<?php echo site_url('/report/faculty_report'); ?>">Faculty <?php echo $this->lang->line('report'); ?> </a></li>
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'category_report')){ ?>

                                            <li><a href="<?php echo site_url('/report/category_report'); ?>">Category <?php echo $this->lang->line('report'); ?> </a></li>
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'working_area')){ ?>

                                            <li><a href="<?php echo site_url('/report/working_area'); ?>">Working Area <?php echo $this->lang->line('report'); ?> </a></li>
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'installment_wise')){ ?>

                                            <li><a href="<?php echo site_url('/report/installment_wise'); ?>">Installment wise <?php echo $this->lang->line('report'); ?> </a></li>
                                            <?php } ?>

                                            <?php if(has_permission(VIEW, 'report', 'payroll_report')){ ?>

                                            <li><a href="<?php echo site_url('/report/payroll_report'); ?>">Payroll <?php echo $this->lang->line('report'); ?> </a></li>
                                            <?php } ?>

                                            <!-- <li><a href="<?php echo site_url('report/sinvoice'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('invoice'); ?> <?php echo $this->lang->line('report'); ?></a></li>  -->
                                            <!-- <li><a href="<?php echo site_url('report/sactivity'); ?>"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('activity'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                        
                                            <!-- <li><a href="<?php echo site_url('report/transaction'); ?>"><?php echo $this->lang->line('daily'); ?> <?php echo $this->lang->line('transaction'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/statement'); ?>"><?php echo $this->lang->line('daily'); ?> <?php echo $this->lang->line('statement'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                            <!-- <li><a href="<?php echo site_url('report/examresult'); ?>"><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('result'); ?> <?php echo $this->lang->line('report'); ?></a></li> -->
                                        </ul>
                                    </li> 
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    
                    
                   

                   
                    
                    

                   
                      
        




                    <!-- ak 06-03-2021 -->
                    
                  
                   

                                   
                    
                </ul>
            </div>     
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
