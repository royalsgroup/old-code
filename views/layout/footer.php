<footer class="footer no-print">
    <div class="pull-right">
        <p class="white">
             <?php if(isset($this->school_setting->footer) && $this->school_setting->footer != ''){ ?>
                <?php echo $this->school_setting->footer; ?>
             <?php }else{ ?>
                <?php echo $this->global_setting->brand_footer ? $this->global_setting->brand_footer : 'Copyright © '. date('Y').' <a target="_blank" href="https://codecanyon.net/user/codetroopers">Codetroopers Team.</a> All rights reserved.'; ?> 
             <?php } ?>
        </p>       
    </div>
    <div class="clearfix"></div>
</footer>
<div id="contactTitle" class="quick_enq">
<img id="contactImg" src="https://www.vivaindia.com/wp-content/uploads/2019/02/5_4.png" style="height: 80px!important;position:fixed;z-index:999;right: 1%;bottom: 2%;">
</div>
<div id="contact" class="quick_enq" style="display:none; bottom:0px; position:fixed; right:0px;  z-index:999; width:400px;background-color:#fff;">
<?php
if (function_exists("add_formcraft_form")) {
add_formcraft_form("[fc id='8'][/fc]");
}
?>
<iframe src="https://admin.vbrajasthan.in/form-view/1" width="400px" height="642" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
</div>
<div id="closeTitle" style="position:fixed; z-index:999; right:-2px; bottom:68%;height:auto; display:none;">
<img id="contactImg2" src="https://www.vivaindia.com.mx/wp-content/uploads/2018/12/close.png" style="height:auto!important;" />
</div>
<script type="text/javascript">
jQuery(document).ready(function () {
    setTimeout(function() {
      if (!jQuery("#contact").is(':visible'))
      {
        jQuery("#contactTitle").hide()
      }
    }, 5000);
 // <-- time in milliseconds
         jQuery("#contactTitle").click(function () {
             jQuery("#contact").show();
              jQuery("#contactTitle").hide();
 jQuery("#closeTitle").show();
         });
     });
     jQuery(document).ready(function () {
         jQuery("#closeTitle").click(function () {
             jQuery("#contact").hide();
         jQuery("#closeTitle").hide();
         jQuery("#contactTitle").show();
         });
     });
    </script>

<script type="text/javascript">
jQuery(document).ready(function(){
jQuery(".honeSliderContact").click(function() {
jQuery("#contact").show();
              jQuery("#contactTitle").hide();
 jQuery("#closeTitle").show();
});
});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("input[type='button']").click(function() {
jQuery(this).parent().removeAttr("href");
jQuery("#contact").show();
              jQuery("#contactTitle").hide();
 jQuery("#closeTitle").show();
});
});
</script>

<style type="text/css">	
.quick_enq {
  position: fixed;
  bottom: 0;
  right: 50px;
  z-index: 999;
  width: 310px;
 cursor:pointer;
}
</style>	


