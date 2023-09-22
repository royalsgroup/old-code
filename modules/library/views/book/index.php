<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-book"></i><small> <?php echo $this->lang->line('manage_book'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                 <span><?php echo $this->lang->line('quick_link'); ?>:</span>
                <?php if(has_permission(VIEW, 'library', 'book')){ ?>
                    <a href="<?php echo site_url('library/book/index/'); ?>"><?php echo $this->lang->line('manage_book'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'library', 'member')){ ?>
                   | <a href="<?php echo site_url('library/member/index/'); ?>"><?php echo $this->lang->line('library'); ?> <?php echo $this->lang->line('member'); ?></a>
                <?php } ?>
                <?php if(has_permission(VIEW, 'library', 'issue')){ ?>
                   | <a href="<?php echo site_url('library/issue/index'); ?>"><?php echo $this->lang->line('issue_and_return'); ?></a>                    
                <?php } ?>
                <?php if(has_permission(VIEW, 'library', 'ebook')){ ?>
                   | <a href="<?php echo site_url('library/ebook/index'); ?>"><?php echo $this->lang->line('e_book'); ?></a>                    
                <?php } ?>   
            </div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_book_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('book'); ?> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'library', 'book')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('library/book/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('book'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_book"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('book'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_book"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('book'); ?></a> </li>                          
                        <?php } ?> 
                            
                        <li class="li-class-list">
                           <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){  ?>                                 
                                <select  class="form-control col-md-7 col-xs-12" onchange="get_book_by_school(this.value);">
                                        <option value="<?php echo site_url('library/book/index'); ?>">--<?php echo $this->lang->line('select'); ?> <?php echo $this->lang->line('school'); ?>--</option> 
                                    <?php foreach($schools as $obj ){ ?>
                                        <option value="<?php echo site_url('library/book/index/'.$obj->id); ?>" <?php if(isset($filter_school_id) && $filter_school_id == $obj->id){ echo 'selected="selected"';} ?> > <?php echo $obj->school_name; ?></option>
                                    <?php } ?>   
                                </select>
                            <?php } ?>  
                        </li>      
                            
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_book_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ ?>
                                            <th><?php echo $this->lang->line('school'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('book_id'); ?></th>
                                        <th><?php echo $this->lang->line('isbn_no'); ?></th>
                                        <th><?php echo $this->lang->line('author'); ?></th>
                                        <th><?php echo $this->lang->line('book_cover'); ?></th>
                                        <th><?php echo $this->lang->line('price'); ?></th>
                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_book">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('library/book/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('book'); ?> <?php echo $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($post['title']) ?  $post['title'] : ''; ?>" placeholder="<?php echo $this->lang->line('book'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="custom_id"><?php echo $this->lang->line('book_id'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="custom_id"  id="custom_id" value="<?php echo $custom_id; ?>" readonly="readonly" placeholder="<?php echo $this->lang->line('book_id'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('custom_id'); ?></div>
                                    </div>
                                </div>                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="isbn_no"><?php echo $this->lang->line('isbn_no'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="isbn_no"  id="isbn_no" value="<?php echo isset($post['isbn_no']) ?  $post['isbn_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('isbn_no'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('isbn_no'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edition"><?php echo $this->lang->line('edition'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="edition"  id="edition" value="<?php echo isset($post['edition']) ?  $post['edition'] : ''; ?>" placeholder="<?php echo $this->lang->line('edition'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('edition'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="author"><?php echo $this->lang->line('author'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="author"  id="author" value="<?php echo isset($post['author']) ?  $post['author'] : ''; ?>" placeholder="<?php echo $this->lang->line('author'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('author'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="language"><?php echo $this->lang->line('language'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="language"  id="language" value="<?php echo isset($post['language']) ?  $post['language'] : ''; ?>" placeholder="<?php echo $this->lang->line('language'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('language'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price"><?php echo $this->lang->line('price'); ?>   </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="price"  id="price" value="<?php echo isset($post['price']) ?  $post['price'] : ''; ?>" placeholder="<?php echo $this->lang->line('price'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('price'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qty"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="qty"  id="qty" value="<?php echo isset($post['qty']) ?  $post['qty'] : ''; ?>" placeholder="<?php echo $this->lang->line('quantity'); ?>" required="required"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('qty'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="rack_no"><?php echo $this->lang->line('almira_rack'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="rack_no"  id="rack_no" value="<?php echo isset($post['rack_no']) ?  $post['rack_no'] : ''; ?>" placeholder="<?php echo $this->lang->line('almira_rack'); ?>"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('rack_no'); ?></div>
                                    </div>
                                </div>
                                
                               <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('book_cover'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="cover"  id="cover" type="file">
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                        <div class="help-block"><?php echo form_error('cover'); ?></div>
                                    </div>
                               </div>
                                                          
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('library/book'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_book">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('library/book/edit/'.$book->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <?php $this->load->view('layout/school_list_edit_form'); ?> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('book'); ?> <?php echo $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($book->title) ?  $book->title : ''; ?>" placeholder="<?php echo $this->lang->line('book'); ?> <?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="custom_id"><?php echo $this->lang->line('book_id'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="custom_id"  id="custom_id" value="<?php echo isset($book->custom_id) ?  $book->custom_id : ''; ?>" placeholder="<?php echo $this->lang->line('book_id'); ?>" readonly="readonly" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('custom_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="isbn_no"><?php echo $this->lang->line('isbn_no'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="isbn_no"  id="isbn_no" value="<?php echo isset($book->isbn_no) ?  $book->isbn_no : ''; ?>" placeholder="<?php echo $this->lang->line('isbn_no'); ?>" type="text" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('isbn_no'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edition"><?php echo $this->lang->line('edition'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="edition"  id="edition" value="<?php echo isset($book->edition) ?  $book->edition : ''; ?>" placeholder="<?php echo $this->lang->line('edition'); ?>" type="text" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('edition'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="author"><?php echo $this->lang->line('author'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="author"  id="author" value="<?php echo isset($book->author) ?  $book->author : ''; ?>" placeholder="<?php echo $this->lang->line('author'); ?>" type="text" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('author'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="language"><?php echo $this->lang->line('language'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="language"  id="language" value="<?php echo isset($book->language) ?  $book->language : ''; ?>" placeholder="<?php echo $this->lang->line('language'); ?>" type="text" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('language'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price"><?php echo $this->lang->line('price'); ?>   </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="price"  id="price" value="<?php echo isset($book->price) ?  $book->price : ''; ?>" placeholder="<?php echo $this->lang->line('price'); ?>" type="text" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('price'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qty"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="qty"  id="qty" value="<?php echo isset($book->qty) ?  $book->qty : ''; ?>" placeholder="<?php echo $this->lang->line('quantity'); ?>" required="required" type="text" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('qty'); ?></div>
                                    </div>
                                </div>
                                
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="rack_no"><?php echo $this->lang->line('almira_rack'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="rack_no"  id="rack_no" value="<?php echo isset($book->rack_no) ?  $book->rack_no : ''; ?>" placeholder="<?php echo $this->lang->line('almira_rack'); ?>"  type="text" autocomplete="off"/>
                                        <div class="help-block"><?php echo form_error('rack_no'); ?></div>
                                    </div>
                                </div>                                
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('book_cover'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" name="prev_cover" id="prev_cover" value="<?php echo $book->cover; ?>" />
                                        <?php if($book->cover){ ?>
                                        <img src="<?php echo UPLOAD_PATH; ?>/book-cover/<?php echo $book->cover; ?>" alt="" width="70" /><br/><br/>
                                        <?php } ?>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="cover"  id="cover" type="file">
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                        <div class="help-block"><?php echo form_error('cover'); ?></div>
                                    </div>
                                </div>
                                                         
                                                                                           
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($book) ? $book->id : $id; ?>" name="id" />
                                        <a  href="<?php echo site_url('library/book'); ?>"  class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bs-book-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('book'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_book_data"></div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_book_modal(book_id){
         
        $('.fn_book_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('library/book/get_single_book'); ?>",
          data   : {book_id : book_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_book_data').html(response);
             }
          }
       });
    }
</script>


 <script type="text/javascript">
 			var sch_id='<?php print $filter_school_id; ?>';

        $(document).ready(function() {
    $('#datatable-responsive').DataTable( {
        dom: 'Bfrtip',
		'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
          'url':'<?php echo site_url("library/book/get_list"); ?>',
		  'data': {'school_id': sch_id}
         },
              iDisplayLength: 15,
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength'
              ],
              search: true,              
              responsive: true
          });
    });
        
    $("#add").validate();     
    $("#edit").validate();
    
    function get_book_by_school(url){          
        if(url){
            window.location.href = url; 
        }
    } 
    
</script>