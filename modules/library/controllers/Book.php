<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Book.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Book
 * @description     : Manage library books information.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Book extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Book_Model', 'book', true);        
    }

    
       
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Book List" user interface                 
    *                      
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {

        check_permission(VIEW);
      
        
        //$this->data['books'] = $this->book->get_book_list($school_id);  
        
        $this->data['roles'] = $this->book->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');        
        $this->data['custom_id'] = $this->book->get_custom_id('books', 'BK');
         $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_book') . ' | ' . SMS);
        $this->layout->view('book/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Book" user interface                 
    *                    and process to store "Book" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_book_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_book_data();

                $insert_id = $this->book->insert('books', $data);
                if ($insert_id) {
                    
                    create_log('Has been added a Book : '.$data['title']);
                    
                    success($this->lang->line('insert_success'));
                    redirect('library/book/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('library/book/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['books'] = $this->book->get_book_list();         
        $this->data['roles'] = $this->book->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');        
        $this->data['custom_id'] = $this->book->get_custom_id('books', 'BK');
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('book') . ' | ' . SMS);
        $this->layout->view('book/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Book" user interface                 
    *                    with populate "Book" value 
    *                    and process to update "Book" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('library/book/index');
        }
        
        if ($_POST) {
            $this->_prepare_book_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_book_data();
                $updated = $this->book->update('books', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a Book : '.$data['title']);
                    
                    success($this->lang->line('update_success'));
                    redirect('library/book/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('library/book/edit/' . $this->input->post('id'));
                }
            } else {               
                $this->data['book'] = $this->book->get_single('books', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['book'] = $this->book->get_single('books', array('id' => $id));

            if (!$this->data['book']) {
                redirect('library/book/index');
            }
        }        
          
        $this->data['books'] = $this->book->get_book_list($this->data['book']->school_id);         
        $this->data['roles'] = $this->book->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');        
        $this->data['custom_id'] = $this->book->get_custom_id('books', 'BK');
        $this->data['filter_school_id'] = $this->data['book']->school_id;
        $this->data['school_id'] = $this->data['book']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('book') . ' | ' . SMS);
        $this->layout->view('book/index', $this->data);
    }

        
    public function get_list(){	
      
        // for super admin 
       $school_id = '';
       $start=null;
       $limit=null;
       $search_text='';
       if($_POST){            
           $school_id = $this->input->post('school_id');
           $start = $this->input->post('start');
           $limit  = $this->input->post('length');   
           $draw = $this->input->post('draw');	
           if(isset($_POST['search']['value']) && $_POST['search']['value']!= ''){
               $search_text=$_POST['search']['value'];
           }
       }		
             
       if(!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN){
           $school_id = $this->session->userdata('school_id');
       }
       if($this->session->userdata('role_id') == STUDENT){
        $class_id =  $this->session->userdata('class_id');
      }     
       
       $school = $this->book->get_school_by_id($school_id);
       
                
       
      // if($school_id){
         $totalRecords = $this->book->get_book_list_ajax_total($school_id, $search_text);
          $books = $this->book->get_book_list_ajax($school_id, $search_text,$start,$limit);
       $count = 1; 
       $data = array();

       if(isset($books) && !empty($books)){
           if($this->session->userdata('role_id') != GUARDIAN || $this->session->userdata('role_id') != TEACHER){
               foreach($books as $obj){
                   $action='';
                    $temp_data =  array();
                    $temp_data[] = $count; 
                   if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1){ 
                    $temp_data[] =  $obj->school_name; 
                     } 
                    $obj->title; 
                   $obj->custom_id;
                   if($obj->cover != ''){
                        $cover_image =' <img src="'.UPLOAD_PATH.'/book-cover/'.$obj->cover.'" alt="" width="70" /> ';
                    }
                    else
                    {
                        $cover_image = "";
                    }
                    if(has_permission(EDIT, 'library', 'book')){ 
                        $action .= ' <a href="'.site_url('library/book/edit/'.$obj->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> '.$this->lang->line('edit').'</a>';
                    }
                    if(has_permission(VIEW, 'library', 'book')){
                        $action .=' <a  onclick="get_book_modal('.$obj->id.')"  data-toggle="modal" data-target=".bs-book-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> '.$this->lang->line('view').'</a>';
                     }
                    if(has_permission(DELETE, 'library', 'book')){
                        $action .=' <a href="'.site_url('library/book/delete/'.$obj->id).'" onclick="javascript: return confirm("'.$this->lang->line('confirm_alert').'");" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>'.$this->lang->line('delete').'</a>';
                    }		 
                    $temp_data[] = $obj->title;
                    $temp_data[] = $obj->custom_id;
                    $temp_data[] = $obj->isbn_no;
                    $temp_data[] = $obj->author;
                    $temp_data[] = $cover_image;
                    $temp_data[] =  $this->session->userdata('currency_symbol').$obj->price;
                    $temp_data[] = $obj->qty;;
                    $temp_data[] =  $action;
                    $data[] = $temp_data;
                      $count++;
               }
           }
       }
       else{
           $data=array();
       }
       //print_r($data); exit;
       $response = array(
 "draw" => intval($draw),
 "iTotalRecords" => $totalRecords,
 "iTotalDisplayRecords" => $totalRecords,
 "aaData" => $data
);
echo json_encode($response);
exit;
   }
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific book data                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('library/book/index');
        }
        
        $this->data['books'] = $this->book->get_book_list();        
        $this->data['roles'] = $this->book->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');        
        $this->data['custom_id'] = $this->book->get_custom_id('books', 'BK');
        
        $this->data['book'] = $this->book->get_single('books', array('id' => $id));
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('book') . ' | ' . SMS);
        $this->layout->view('book/index', $this->data);
    }
    
    
               
    /*****************Function get_single_book**********************************
     * @type            : Function
     * @function name   : get_single_book
     * @description     : "Load single book information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_book(){
        
        $book_id = $this->input->post('book_id');
       
        $this->data['book'] = $this->book->get_single_book($book_id);
        echo $this->load->view('book/get-single-book', $this->data);
    }

    
    
    /*****************Function _prepare_book_validation**********************************
    * @type            : Function
    * @function name   : _prepare_book_validation
    * @description     : Process "book" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_book_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
        $this->form_validation->set_rules('custom_id', $this->lang->line('book_id'), 'trim|required');
        $this->form_validation->set_rules('title', $this->lang->line('book') . ' ' . $this->lang->line('title'), 'trim|required');
        $this->form_validation->set_rules('isbn_no', $this->lang->line('isbn_no'), 'trim');
        $this->form_validation->set_rules('edition', $this->lang->line('edition'), 'trim');
        $this->form_validation->set_rules('author', $this->lang->line('author'), 'trim');
        $this->form_validation->set_rules('language', $this->lang->line('language'), 'trim');
        $this->form_validation->set_rules('price', $this->lang->line('price'), 'trim');
        $this->form_validation->set_rules('cover', $this->lang->line('book_cover'), 'trim|callback_cover');
        $this->form_validation->set_rules('qty', $this->lang->line('quantity'), 'trim|required');
        $this->form_validation->set_rules('rack_no', $this->lang->line('almira_rack'), 'trim');
    }

    
        
    /*****************Function cover**********************************
    * @type            : Function
    * @function name   : cover
    * @description     : validate book cover image format/image type                 
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function cover() {
        if ($_FILES['cover']['name']) {
            $name = $_FILES['cover']['name'];
            $arr = explode('.', $name);
            $ext = end($arr);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('cover', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

       
    /*****************Function _get_posted_book_data**********************************
    * @type            : Function
    * @function name   : _get_posted_book_data
    * @description     : Prepare "Book" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_book_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'custom_id';
        $items[] = 'title';
        $items[] = 'isbn_no';
        $items[] = 'edition';
        $items[] = 'author';
        $items[] = 'language';
        $items[] = 'price';
        $items[] = 'qty';
        $items[] = 'rack_no';

        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }

        if (isset($_FILES['cover']['name'])) {
            $data['cover'] = $this->_upload_cover();
        }

        return $data;
    }

    
           
    /*****************Function _upload_cover**********************************
    * @type            : Function
    * @function name   : _upload_cover
    * @description     : Process to upload book cover image to server                 
    *                     and return cover name  
    * @param           : null
    * @return          : $return_cover string value 
    * ********************************************************** */
    private function _upload_cover() {

        $prev_cover = $this->input->post('prev_cover');
        $cover = $_FILES['cover']['name'];
        $cover_type = $_FILES['cover']['type'];
        $return_cover = '';
        if ($cover != "") {
            if ($cover_type == 'image/jpeg' || $cover_type == 'image/pjpeg' ||
                    $cover_type == 'image/jpg' || $cover_type == 'image/png' ||
                    $cover_type == 'image/x-png' || $cover_type == 'image/gif') {

                $destination = 'assets/uploads/book-cover/';

                $file_type = explode(".", $cover);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $cover_path = 'book-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['cover']['tmp_name'], $destination . $cover_path);
                if($converted_file = webpConverter($destination . $cover_path,null, 415,515))
                {
                    $cover_path = get_filename($converted_file);
                }
                // need to unlink previous cover
                if ($prev_cover != "") {
                    if (file_exists($destination . $prev_cover)) {
                        @unlink($destination . $prev_cover);
                    }
                }

                $return_cover = $cover_path;
            }
        } else {
            $return_cover = $prev_cover;
        }

        return $return_cover;
    }
    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "book" data from database                  
    *                    and unlink book cover image from server   
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('library/book/index');
        }
        
        $book = $this->book->get_single('books', array('id' => $id));
        if ($this->book->delete('books', array('id' => $id))) {

            // delete teacher resume and cover
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/book-cover/' . $book->cover)) {
                @unlink($destination . '/book-cover/' . $book->cover);
            }
            
            create_log('Has been deleted a Book : '.$book->title);

            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('library/book/index/'.$book->school_id);
    }

}
