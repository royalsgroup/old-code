<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta charset="ISO-8859-15">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="cache-control" content="no-store" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="pragma" content="no-store" />
        

        <title><?php echo $title_for_layout; ?></title>
        <?php if($this->global_setting->favicon_icon){ ?>
            <link rel="icon" href="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->global_setting->favicon_icon; ?>" type="image/x-icon" />             
        <?php }else{ ?>
            <link rel="icon" href="<?php echo IMG_URL; ?>favicon.ico" type="image/x-icon" />
        <?php } ?>
        
        
        <!-- Bootstrap -->
        <link href="<?php echo VENDOR_URL; ?>bootstrap/bootstrap.min.css" rel="stylesheet" media='all'>
        
<!--    <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">-->
        <!-- Font Awesome -->
        <link href="<?php echo VENDOR_URL; ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" media='all'>
    
        <!-- Custom Theme Style -->
         <?php if($this->global_setting->enable_rtl){ ?>
            <link href="<?php echo CSS_URL; ?>rtl/custom-rtl.css" rel="stylesheet" media='all'>             
        <?php }else{ ?>
            <link href="<?php echo CSS_URL; ?>custom.css" rel="stylesheet" media='all'>
        <?php } ?>
        
        <?php if($this->session->userdata('theme')){ ?>
            <link href="<?php echo CSS_URL; ?>theme/<?php echo $this->session->userdata('theme'); ?>.css" rel="stylesheet" media='all'>
        <?php }else{ ?>
            <link href="<?php echo CSS_URL; ?>theme/jazzberry-jam.css" rel="stylesheet">
        <?php } ?>
        
        <!-- jQuery -->
        <script src="<?php echo JS_URL; ?>jquery-1.11.2.min.js"></script>
        <script src="<?php echo JS_URL; ?>jquery.validate.js"></script>
        <script src="<?php echo JS_URL; ?>/sheetjs/xlsx.full.min.js"></script>

         <script type="text/javascript" src="<?php echo VENDOR_URL; ?>toastr/toastr.min.js"></script>
        
        <?php if($this->global_setting->google_analytics){ ?>         
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->global_setting->google_analytics; ?>"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '<?php echo $this->global_setting->google_analytics; ?>');
            </script>
        <?php } ?>
  
        
    </head>

    <body class="nav-sm">
        <div id="preloader"></div>
        
        <div class="container body">
            <div class="main_container">
                 <?php $this->load->view('layout/left-side'); ?>   
                <!-- top navigation -->
                 <?php $this->load->view('layout/header'); ?>   
                <!-- /top navigation -->
                
                <div class="<?php echo $this->global_setting->enable_rtl ? 'left_col' : 'right_col'; ?>" role="main" >                  
                    <?php $this->load->view('layout/message'); ?>
                    <!-- page content -->
                    <?php echo $content_for_layout; ?>
                    <!-- /page content -->
                </div>
                <!-- footer content -->
                <?php $this->load->view('layout/footer'); ?>   
                <!-- /footer content -->
            </div>
        </div>
        
        <!-- Bootstrap -->
        <script src="<?php echo VENDOR_URL; ?>bootstrap/bootstrap.min.js"></script>    
        
        <!--   Start   -->   
        <link href="<?php echo VENDOR_URL; ?>datatables/rowReorder/rowReorder.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo VENDOR_URL; ?>datatables/rowReorder/responsive.dataTables.min.css" rel="stylesheet">
        
        <link href="<?php echo VENDOR_URL; ?>datatables/buttons.dataTables.min.css" rel="stylesheet"> 
        <link href="<?php echo VENDOR_URL; ?>datatables/dataTables.bootstrap.css" rel="stylesheet"> 
        <script src="<?php echo VENDOR_URL; ?>datatables/tools/jquery.dataTables.min.js"></script>
        <script src="<?php echo VENDOR_URL; ?>datatables/tools/dataTables.buttons.min.js"></script>
        <script src="<?php echo VENDOR_URL; ?>datatables/tools/pdfmake.min.js"></script>
        <script src="<?php echo VENDOR_URL; ?>datatables/tools/jszip.min.js"></script>
        <script src="<?php echo VENDOR_URL; ?>datatables/tools/vfs_fonts.js"></script>
        <script src="<?php echo VENDOR_URL; ?>datatables/tools/buttons.html5.min.js"></script> 
        <script src="<?php echo VENDOR_URL; ?>datatables/dataTables.bootstrap.js"></script> 

		<script src="<?php echo VENDOR_URL; ?>datatables/tools/buttons.colVis.min.js"></script> 
		<script src="<?php echo VENDOR_URL; ?>datatables/tools/buttons.print.min.js"></script> 
        
        <script src="<?php echo VENDOR_URL; ?>datatables/rowReorder/dataTables.rowReorder.min.js"></script> 
        <script src="<?php echo VENDOR_URL; ?>datatables/rowReorder/dataTables.responsive.min.js"></script> 
        <script src="<?php echo VENDOR_URL; ?>datatables/tools/buttons.html5.min.js"></script>

        <script src="<?php echo VENDOR_URL; ?>printThis/printThis.js"></script> 

       <!-- dataTable with buttons end -->       
        <link href="<?php echo VENDOR_URL; ?>toastr/toastr.min.css" rel="stylesheet">
       <!-- Custom Theme Scripts -->       
           
       <script src="<?php echo JS_URL; ?>custom.js"></script>  
       <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
       <script type="text/javascript">
       
       jQuery.extend(jQuery.validator.messages, {
                required: "<?php echo $this->lang->line('required_field'); ?>",
                email: "<?php echo $this->lang->line('enter_valid_email'); ?>",
                url: "<?php echo $this->lang->line('enter_valid_url'); ?>",
                date: "<?php echo $this->lang->line('enter_valid_date'); ?>",
                number: "<?php echo $this->lang->line('enter_valid_number'); ?>",
                digits: "<?php echo $this->lang->line('enter_only_digit'); ?>",
                equalTo: "<?php echo $this->lang->line('enter_same_value_again'); ?>",
                remote: "<?php echo $this->lang->line('pls_fix_this'); ?>",
                dateISO: "Please enter a valid date (ISO).",
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
                rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
                 range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
            });
            
            toastr.options = {
                "closeButton": true,               
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "300",
                "timeOut": "3000",              
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
              }


        $(window).on('load', function() {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });
		 $("document").ready(function() {
			 $('.right_col select:not(.noSelect2)').select2({
				 width: '100%'
			 });
    });

    var _fnGetHeaders = function(dt) {
var thRows = $(dt.header()[0]).children();
var numRows = thRows.length;
var matrix = [];

// Iterate over each row of the header and add information to matrix.
for ( var rowIdx = 0;  rowIdx < numRows;  rowIdx++ ) {
    var $row = $(thRows[rowIdx]);

    // Iterate over actual columns specified in this row.
    var $ths = $row.children("th");
    for ( var colIdx = 0;  colIdx < $ths.length;  colIdx++ )
    {
        var $th = $($ths.get(colIdx));
        var colspan = $th.attr("colspan") || 1;
        var rowspan = $th.attr("rowspan") || 1;
        var colCount = 0;

        // ----- add this cell's title to the matrix
        if (matrix[rowIdx] === undefined) {
            matrix[rowIdx] = [];  // create array for this row
        }
        // find 1st empty cell
        for ( var j = 0;  j < (matrix[rowIdx]).length;  j++, colCount++ ) {
            if ( matrix[rowIdx][j] === "PLACEHOLDER" ) {
                break;
            }
        }
        var myColCount = colCount;
        matrix[rowIdx][colCount++] = $th.text();

        // ----- If title cell has colspan, add empty titles for extra cell width.
        for ( var j = 1;  j < colspan;  j++ ) {
            matrix[rowIdx][colCount++] = "";
        }

        // ----- If title cell has rowspan, add empty titles for extra cell height.
        for ( var i = 1;  i < rowspan;  i++ ) {
            var thisRow = rowIdx+i;
            if ( matrix[thisRow] === undefined ) {
                matrix[thisRow] = [];
            }
            // First add placeholder text for any previous columns.                 
            for ( var j = (matrix[thisRow]).length;  j < myColCount;  j++ ) {
                matrix[thisRow][j] = "PLACEHOLDER";
            }
            for ( var j = 0;  j < colspan;  j++ ) {  // and empty for my columns
                matrix[thisRow][myColCount+j] = "";
            }
        }
    }
}

return matrix;
};
  
       </script>

</body>
</html>