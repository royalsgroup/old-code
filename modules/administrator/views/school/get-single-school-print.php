<div class="" data-example-id="togglable-tabs">
    
    
    <ul  class="nav nav-tabs">
        <li><a href="#"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('basic_information'); ?></a> </li>
       
    </ul>
	
     <div class="tab-content">
        <div  class="tab-pane fade in active" id="tab_basic_info" > 
            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <tbody>
				
                    <tr>
                        <th><?php echo $this->lang->line('school_code'); ?></th>
                        <td><?php echo $school->school_code; ?></td>        
                        <th><?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('name'); ?></th>
                        <td><?php echo $school->school_name; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('address'); ?></th>
                        <td><?php echo $school->address; ?></td> 
						<th><?php echo $this->lang->line('pincode'); ?></th>
                        <td><?php echo $school->pincode; ?></td> 						
                       
                    </tr>
                    <tr>
					 <th><?php echo $this->lang->line('phone'); ?></th>
                        <td><?php echo $school->phone; ?></td>                            
                        <th><?php echo $this->lang->line('email'); ?></th>
                        <td><?php echo $school->email; ?></td>
                    </tr>
                    <tr>                         
                        <th><?php echo $this->lang->line('school_lat'); ?></th>
                        <td><?php echo $school->school_lat; ?></td> 
                        <th><?php echo $this->lang->line('school_lng'); ?></th>
                        <td><?php echo $school->school_lng; ?></td> 
                    </tr>
                    <tr>   
                        <th><?php echo $this->lang->line('school_fax'); ?></th>
                        <td><?php echo $school->school_fax; ?></td>      
                        <th><?php echo $this->lang->line('footer'); ?></th>
                        <td><?php echo $school->footer; ?></td>                           
                    </tr>
                    <tr>                           
                        <th><?php echo $this->lang->line('frontend_logo'); ?> </th>
                        <td>
                            <?php if($school->frontend_logo){ ?>
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->frontend_logo; ?>" alt="" width="70" style="background: #34347a; padding: 5px;" />
                            <?php } ?>        
                        </td> 
                        <th><?php echo $this->lang->line('admin_logo'); ?> </th>
                        <td>
                            <?php if($school->logo){ ?>
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="70" style="background: #34347a; padding: 5px;" />
                            <?php } ?>        
                        </td>  
                    </tr>
                    <tr>  
                        <th><?php echo $this->lang->line('theme'); ?></th>
                        <td ><?php echo $school->theme_name; ?></td>  
                        <th><?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('admission'); ?></th>
                        <td><?php echo $school->enable_online_admission ? $this->lang->line('yes') : $this->lang->line('no'); ?></td>  
                    </tr>
                     <tr>   
                        <th><?php echo $this->lang->line('school')." ".$this->lang->line('category'); ?></th>
                        <td><?php echo $school->school_category; ?></td>      
                        <th><?php echo $this->lang->line('education')." ".$this->lang->line('type'); ?></th>
                        <td><?php echo $school->education_type; ?></td>                           
                    </tr>
					<tr>
						 <th><?php echo $this->lang->line('opening'); ?> <?php echo $this->lang->line('date'); ?></th>
                        <td><?php echo $school->registration_date; ?></td>  
					</tr>
                </tbody>
            </table>
        </div>
         <ul  class="nav nav-tabs">
        <li class=""><a href="#"   role="tab" ><i class="fa fa-gear"></i> <?php echo $this->lang->line('setting_information'); ?> </a> </li>
    </ul>
	
        <div  class="tab-pane fade in active" id="tab_setting_info" > 
            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <tbody>           
                <tr>
                    <th><?php echo $this->lang->line('currency'); ?></th>
                    <td><?php echo $school->currency; ?></td>       
                    <th><?php echo $this->lang->line('currency_symbol'); ?></th>
                    <td><?php echo $school->currency_symbol; ?></td>
                </tr>            
                <tr>
                    <th><?php echo $this->lang->line('enable_frontend'); ?></th>
                    <td><?php echo $school->enable_frontend ? $this->lang->line('yes') : $this->lang->line('no'); ?></td>
                    <th><?php echo $this->lang->line('exam_final_result'); ?></th>
                    <td><?php echo $school->final_result_type ? $this->lang->line('only_of_fianl_exam') : $this->lang->line('avg_of_all_exam'); ?></td>        
                </tr> 
				 <tr>  
                    <th>Google <?php echo $this->lang->line('api_key'); ?></th>
                    <td><?php echo $school->map_api_key; ?></td>  
                    <th><?php echo $this->lang->line('language'); ?></th>
                    <td><?php echo $school->language; ?></td>  
                </tr>
				 <tr>  
                    <th><?php echo $this->lang->line('zoom_api_key'); ?></th>
                    <td><?php echo $school->zoom_api_key; ?></td>  
                    <th><?php echo $this->lang->line('zoom_secret'); ?></th>
                    <td><?php echo $school->zoom_secret; ?></td>  
                </tr>  				
            </tbody>
        </table>
        </div>
         <ul  class="nav nav-tabs">
        <li class=""><a href="#"   role="tab" ><i class="fa fa-share"></i> <?php echo $this->lang->line('profile')." ".$this->lang->line('information'); ?></a> </li>
    </ul>
	
        <div  class="tab-pane fade in active" id="tab_social_info" > 
            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <tbody>           
                <tr>
                    <th><?php echo $this->lang->line('affiliation'); ?></th>
                    <td><?php echo $school->affiliation; ?></td>       
                    <th><?php echo $this->lang->line('disc_code'); ?></th>
                    <td><?php echo $school->disc_code; ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('society_name'); ?></th>
                    <td><?php echo $school->society_name; ?></td>        
                    <th><?php echo $this->lang->line('society_pan_no'); ?></th>
                    <td><?php echo $school->society_pan_no; ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('80g_registration_no'); ?></th>
                    <td><?php echo $school->school_80g_registration_no; ?></td> 
					<th><?php echo $this->lang->line('building_type'); ?></th>
                    <td><?php echo $school->building_type; ?></td>            					
                </tr> 
				<tr>
                    <th><?php echo $this->lang->line('remote_id'); ?></th>
                    <td><?php echo $school->remote_id; ?></td>        
                    <th><?php echo $this->lang->line('skype_id'); ?></th>
                    <td><?php echo $school->skype_id; ?></td>
                </tr>	
				<tr>
                    <th><?php echo $this->lang->line('facilities'); ?></th>
                    <td><?php echo $school->facilities; ?></td>        
                    <th><?php echo $this->lang->line('laboratory')." ".$this->lang->line('facilities'); ?></th>
                    <td><?php echo $school->laboratory_facilities; ?></td>
                </tr>	
<tr>
                    <th><?php echo $this->lang->line('school_level'); ?></th>
                    <td><?php echo $school->school_levels; ?></td>        
                    
                </tr>					
            </tbody>
            </table>
        </div>
    </div>
</div>
