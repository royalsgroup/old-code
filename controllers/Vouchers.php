<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Vouchers extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('Voucher_Model', 'voucher', true);
    $this->load->model('Accounttransactions_Model', 'transactions', true);
  }

  public function index_bkp()
  {
    // for super admin
    $category  = null;
    $school_id = null;
    if (!empty($_POST)) {
      if ($this->input->post('school_id') >= 0) {
        $school_id = $this->input->post('school_id');
      }
      if ($this->input->post('category')) {
        $category = $this->input->post('category');
      }
    }
    if (($school_id == null && $this->session->userdata('role_id') != SUPER_ADMIN) && ($school_id == null && $this->session->userdata('dadmin') != 1)) {
      $school_id = $this->session->userdata('school_id');
    }

    $school         = $this->voucher->get_school_by_id($school_id);
    $financial_year = $this->voucher->get_single('financial_years', array('school_id' => $school_id, 'is_running' => 1));
    if (strpos($financial_year->session_year, "->")) {
      $arr     = explode("->", $financial_year->session_year);
      $f_start = date("Y-m-d", strtotime($arr[0]));
      $f_end   = date("Y-m-d", strtotime($arr[1]));
    } else {
      $arr = explode("-", $financial_year->session_year);

      $date_exploded = explode(" ", $arr[0]);
      if (count($date_exploded) > 2) {
        $f_start = date("Y-m-d", strtotime($arr[0]));
        $f_end   = date("Y-m-d", strtotime($arr[1]));
      } else {
        $f_start = date("Y-m-d", strtotime("1 " . $arr[0]));
        $f_end   = date("Y-m-d", strtotime("31 " . $arr[1]));
      }
    }
    // die();

    $this->data['financial_year_start'] = $f_start;
    $this->data['financial_year_end']   = $f_end;
    $this->data['school_info']          = $school;
    $this->data['financial_year']       = $financial_year;
    $this->data['filter_school_id']     = $school_id;

    if ($school_id != null) {
      $vouchers_list = $this->voucher->get_voucher_list($school_id, $category);
      $i             = 0;
      foreach ($vouchers_list as $v) {

        $transactions                     = $this->voucher->get_list('account_transactions', array('voucher_id' => $v->id, 'date >=' => $f_start, 'date <= ' => $f_end), '', '', '', 'id', 'ASC');
        $vouchers_list[$i]->no_of_entries = count($transactions);
        /* new */
        $res = $this->transactions->get_last_transaction_entry_by_voucher($v->id);
        if (!empty($res)) {
          $vouchers_list[$i]->last_entry_date = $res->date;
        }

        /*$transaction_id=array();
        foreach($transactions as $tr){
        $transaction_id[]=$tr->id;
        }
        // get last entry date
        if(!empty($transaction_id)){
        $res=$this->transactions->get_last_transaction_entry($transaction_id);
        if(!empty($res)){
        $vouchers_list[$i]->last_entry_date=$res->created;
        }
        }*/
        $i++;
      }
      $this->data['vouchers'] = $vouchers_list;
    }

    $this->data['voucher_types'] = $this->voucher->get_list('voucher_types', array(), '', '', '', 'id', 'ASC');

    $this->data['schools'] = $this->schools;

    //$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');
    $this->data['themes'] = $this->voucher->get_list('themes', array(), '', '', '', 'id', 'ASC');
    $this->data['list']   = true;
    $this->layout->title($this->lang->line('voucher_books') . ' | ' . SMS);
    $this->layout->view('vouchers/index', $this->data);

  }
  public function get_list()
  {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $iForceAllow  = 1;
    $school_id    = '';
    $start        = null;
    $due          = null;
    $limit        = null;
    $sort_coloumn = "";
    $sort_sort    = "";
    $search_text  = '';
    $search_cols  = array();
    $order_cols   = array();
    if ($_POST) {
      $start       = $this->input->post('start');
      $due         = $this->input->post('due');
      $limit       = $this->input->post('length');
      $order_cols  = $this->input->post('order');
      $search_cols = $this->input->post('columns');
      $draw        = $this->input->post('draw');
      $school_id   = $this->input->post('school_id');
      $category    = $this->input->post('category');
      if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search_text = $_POST['search']['value'];
      }
    }

    if ($school_id) {
      $financial_year = $this->voucher->get_single('financial_years', array('school_id' => $school_id, 'is_running' => 1));
      if (strpos($financial_year->session_year, "->")) {
        $arr     = explode("->", $financial_year->session_year);
        $f_start = date("Y-m-d", strtotime($arr[0]));
        $f_end   = date("Y-m-d", strtotime($arr[1]));
      } else {
        $arr = explode("-", $financial_year->session_year);

        $date_exploded = explode(" ", $arr[0]);
        if (count($date_exploded) > 2) {
          $f_start = date("Y-m-d", strtotime($arr[0]));
          $f_end   = date("Y-m-d", strtotime($arr[1]));
        } else {
          $f_start = date("Y-m-d", strtotime("1 " . $arr[0]));
          $f_end   = date("Y-m-d", strtotime("31 " . $arr[1]));
        }
      }
      $previous_financial_year = $this->transactions->get_single('financial_years', array('previous_financial_year_id' => $financial_year->id, 'school_id' => $school_id));
      $is_previous_year        = !empty($previous_financial_year) ? true : false;
      $totalRecords            = $this->voucher->get_voucher_list_total($school_id, $category);
      $vouchers_list           = $this->voucher->get_voucher_list_ajax($school_id, $category, $start, $limit, $search_text, $search_cols, $sort_coloumn, $sort_sort);
    } else {
      $totalRecords  = 0;
      $invoices      = array();
      $vouchers_list = [];
    }

    $count = 1;
    $data  = array();
    // echo "<pre>";
    // print_r($vouchers_list);die;
    foreach ($vouchers_list as $obj) {

      $transactions  = $this->voucher->get_list('account_transactions', array('voucher_id' => $obj->id, 'date >=' => $f_start, 'date <= ' => $f_end), '', '', '', 'id', 'ASC');
      $no_of_entries = count($transactions);
      /* new */
      $res = $this->transactions->get_last_transaction_entry_by_voucher($obj->id);
      if (!empty($res)) {
        $last_entry_date = $res->date;
      } else {
        $last_entry_date = "";
      }
      $row_data = array();

      if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
        $row_data[] = $obj->school_name;
      }
      $row_data[] = $obj->category;
      $row_data[] = $obj->name;
      $row_data[] = $obj->type_name;
      $row_data[] = $no_of_entries;
      $row_data[] = $obj->last_entry_date ? date('d-m-Y', strtotime($obj->last_entry_date)) : "";
      //$row_data[] = $obj->narration;
      

      $action = '';
      if (has_permission(EDIT, 'accounting', 'vouchers')) {
        $action .= ' <a href="' . site_url('vouchers/edit/' . $obj->id) . '" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> ' . $this->lang->line('edit') . ' </a>';
      }
      if (has_permission(DELETE, 'accounting', 'vouchers')) {
        $action .= '<a href="' . site_url('vouchers/delete/' . $obj->id) . '" onclick="javascript: return confirm(\'' . $this->lang->line('confirm_alert') . '\');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> ' . $this->lang->line('delete') . ' </a>';
      }
      if (has_permission(VIEW, 'accounting', 'vouchers')) {
        $action .= '  <a href="' . site_url('vouchers/view/' . $obj->id) . '"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> ' . $this->lang->line('view') . ' </a>';
      }
      if (has_permission(ADD, 'accounting', 'accounttransactions')) {
        if ($this->session->userdata('role_id') == SUPER_ADMIN) {
          $action .= ' <a class="btn btn-info btn-xs" href="' . site_url('transactions/create/' . $obj->id) . '">New Entry</a>';
        } else if ($obj->is_readonly == 0 && (!$is_previous_year || ($is_previous_year == true && $obj->type_id == 1) || $iForceAllow)) {
          // check if financial year
          $currentDate = date('Y-m-d');

          // if (($currentDate >= $f_start) && ($currentDate <= $f_end))
          if ($obj->name != 'Fees Receipt')
          {
            $recipt = '';
            if ($obj->type_name == 'Receipt'){
              $recipt = '/yes';
            }
            $action .= ' <a class="btn btn-info btn-xs" href="' . site_url('transactions/create/' . $obj->id.$recipt) . '">New Entry</a>';
          }
        }
      }
      $row_data[] = $action;
      $data[]     = $row_data;

    }
    $response = array(
      "draw"                 => intval($draw),
      "iTotalRecords"        => $totalRecords,
      "iTotalDisplayRecords" => $totalRecords,
      "aaData"               => $data,
    );
    echo json_encode($response);
    exit;

  }
  public function index()
  {

    // for super admin
    $category  = null;
    $school_id = null;
    if (!empty($_POST)) {
      if ($this->input->post('school_id') >= 0) {
        $school_id = $this->input->post('school_id');
      }
      if ($this->input->post('category')) {
        $category = $this->input->post('category');
      }
    }
    if (($school_id == null && $this->session->userdata('role_id') != SUPER_ADMIN) && ($school_id == null && $this->session->userdata('dadmin') != 1)) {
      $school_id = $this->session->userdata('school_id');
    }

    $school         = $this->voucher->get_school_by_id($school_id);
    $financial_year = $this->voucher->get_single('financial_years', array('school_id' => $school_id, 'is_running' => 1));
    if (strpos($financial_year->session_year, "->")) {
      $arr     = explode("->", $financial_year->session_year);
      $f_start = date("Y-m-d", strtotime($arr[0]));
      $f_end   = date("Y-m-d", strtotime($arr[1]));
    } else {
      $arr = explode("-", $financial_year->session_year);

      $date_exploded = explode(" ", $arr[0]);
      if (count($date_exploded) > 2) {
        $f_start = date("Y-m-d", strtotime($arr[0]));
        $f_end   = date("Y-m-d", strtotime($arr[1]));
      } else {
        $f_start = date("Y-m-d", strtotime("1 " . $arr[0]));
        $f_end   = date("Y-m-d", strtotime("31 " . $arr[1]));
      }
    }
    // die();
    $previous_financial_year            = $this->transactions->get_single('financial_years', array('previous_financial_year_id' => $financial_year->id, 'school_id' => $voucher->school_id));
    $this->data['is_previous_year']     = !empty($previous_financial_year) ? true : false;
    $this->data['financial_year_start'] = $f_start;
    $this->data['financial_year_end']   = $f_end;
    $this->data['school_info']          = $school;
    $this->data['financial_year']       = $financial_year;
    $this->data['filter_school_id']     = $school_id;
    $this->data['category']             = $category;

    $this->data['voucher_types'] = $this->voucher->get_list('voucher_types', array(), '', '', '', 'id', 'ASC');

    $this->data['schools'] = $this->schools;

    //$this->data['account_types'] = $this->accountgroups->get_list('account_types', array(), '','', '', 'id', 'ASC');
    $this->data['themes'] = $this->voucher->get_list('themes', array(), '', '', '', 'id', 'ASC');
    $this->data['list']   = true;
    $this->layout->title($this->lang->line('voucher_books') . ' | ' . SMS);
    $this->layout->view('vouchers/ajax', $this->data);

  }

  public function add()
  {

    //check_permission(ADD);

    if ($_POST) {
      $this->_prepare_voucher_validation();

      if ($this->form_validation->run() === true) {
        $data = $this->_get_posted_voucher_data();

        $insert_id = $this->voucher->insert('vouchers', $data);
        if ($insert_id) {

          //create_log('Has been created a school : '.$data['school_name']);

          success($this->lang->line('insert_success'));
          redirect('vouchers/index/' . $data['school_id']);
        } else {
          error($this->lang->line('insert_failed'));
          redirect('voucher/add');
        }
      } else {
        $this->data = $_POST;
      }
    }
    $school_id = '';
    if ($_POST) {

      $school_id = $this->input->post('school_id');
    }

    if (!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN) {
      $school_id = $this->session->userdata('school_id');
    }

    $school = $this->voucher->get_school_by_id($school_id);

    $this->data['filter_school_id'] = $school_id;

    if ($school_id) {
      $this->data['vouchers'] = $this->voucher->get_voucher_list($school_id, $school->financial_year_id);
    }
    $this->data['voucher_types'] = $this->voucher->get_list('voucher_types', array(), '', '', '', 'id', 'ASC');

    $this->data['schools'] = $this->schools;
    $this->data['themes']  = $this->voucher->get_list('themes', array(), '', '', '', 'id', 'ASC');
    $this->data['add']     = true;
    $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('voucher') . ' | ' . SMS);
    $this->layout->view('vouchers/index', $this->data);
  }

  public function edit($id = null)
  {

    //check_permission(EDIT);

    if ($_POST) {
      $this->_prepare_voucher_validation();
      if ($this->form_validation->run() === true) {
        $data    = $this->_get_posted_voucher_data();
        $updated = $this->voucher->update('vouchers', $data, array('id' => $this->input->post('id')));

        if ($updated) {

          // create_log('Has been updated a school : '.$data['name']);
          success($this->lang->line('update_success'));
          redirect('vouchers/index/' . $data['school_id']);

        } else {

          error($this->lang->line('update_failed'));
          redirect('vouchers/edit/' . $this->input->post('id'));

        }
      } else {
        $this->data['voucher'] = $this->voucher->get_single('vouchers', array('id' => $this->input->post('id')));
      }
    } else {
      if ($id) {
        $this->data['voucher'] = $this->voucher->get_single('vouchers', array('id' => $id));

        if (!$this->data['voucher']) {
          redirect('vouchers');
        }
      }
    }
    $school_id = $this->data['voucher']->school_id;
    if ($school_id) {
      $this->data['vouchers'] = $this->voucher->get_voucher_list($school_id, $school->financial_year_id);
    }
    $this->data['voucher_types'] = $this->voucher->get_list('voucher_types', array(), '', '', '', 'id', 'ASC');

    $this->data['school_id']        = $school_id;
    $this->data['filter_school_id'] = $school_id;
    $this->data['schools']          = $this->schools;

    $this->data['themes'] = $this->voucher->get_list('themes', array(), '', '', '', 'id', 'ASC');
    $this->data['edit']   = true;
    $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('voucher') . ' | ' . SMS);
    $this->layout->view('vouchers/index', $this->data);
  }
  public function view($id = null)
  {
    //$this->load->model('Accounttransactions_Model', 'accounttransactions', true);

    $this->data['voucher']               = $this->voucher->get_voucher_by_id($id);
    $this->data['voucher']->total_amount = $this->voucher->get_voucher_total_amount($id);
    $school_id                           = $this->data['voucher']->school_id;
    $school                              = $this->voucher->get_school_by_id($school_id);
    $financial_year                      = $this->voucher->get_single('financial_years', array('school_id' => $school_id, 'is_running' => 1));
    if (strpos($financial_year->session_year, "->")) {
      $arr     = explode("->", $financial_year->session_year);
      $f_start = date("Y-m-d", strtotime($arr[0]));
      $f_end   = date("Y-m-d", strtotime($arr[1]));
    } else {
      $arr = explode("-", $financial_year->session_year);

      $date_exploded = explode(" ", $arr[0]);
      if (count($date_exploded) > 2) {
        $f_start = date("Y-m-d", strtotime($arr[0]));
        $f_end   = date("Y-m-d", strtotime($arr[1]));
      } else {
        $f_start = date("Y-m-d", strtotime("1 " . $arr[0]));
        $f_end   = date("Y-m-d", strtotime("31 " . $arr[1]));
      }
    }
    $startDate                          = date('d/m/Y', strtotime($f_start));
    $endDate                            = date('d/m/Y', strtotime($f_end));
    $this->data['f_start_date']         = $startDate;
    $this->data['f_end_date']           = $endDate;
    $previous_financial_year            = $this->transactions->get_single('financial_years', array('previous_financial_year_id' => $financial_year->id, 'school_id' => $school_id));
    $this->data['is_previous_year']     = !empty($previous_financial_year) ? true : false;
    $this->data['financial_year_start'] = $f_start;
    $this->data['financial_year']       = $financial_year;
    $this->data['financial_year_end']   = $f_end;
    $this->data['school_info']          = $school;
    $transactions                       = $this->transactions->get_transactions_by_voucher_id($id, $f_start, $f_end);
    $i                                  = 0;
    $tr_ids                             = [];
    foreach ($transactions as $t) {
      // get total amount of transaction
      $tr_ids[] = $t->id;
    }
    $total_amounts     = [];
    $total_amounts_raw = $this->transactions->get_total_amount_by_transaction_ids($tr_ids);
    foreach ($total_amounts_raw as $total_amount_raw) {
      // get total amount of transaction
      $tr_ids[]                                         = $t->id;
      $total_amounts[$total_amount_raw->transaction_id] = $total_amount_raw->total_amount;
    }
    foreach ($transactions as $t) {
      // get total amount of transaction
      $total_amount                   = isset($total_amounts[$t->id]) ? $total_amounts[$t->id] : 0;
      $transactions[$i]->total_amount = $total_amount;
      $i++;
    }
    // debug_a($transactions);
    $this->data['transactions'] = $transactions;
    $this->data['themes']       = $this->voucher->get_list('themes', array(), '', '', '', 'id', 'ASC');
    $this->data['edit']         = true;
    $this->layout->title($this->lang->line('voucher') . " " . $this->lang->line('detail') . ' | ' . SMS);
    $this->layout->view('vouchers/view', $this->data);
  }

  public function get_voucher_list()
  {
    // for super admin
    $school_id    = '';
    $start        = null;
    $due          = null;
    $limit        = null;
    $sort_coloumn = "";
    $sort_sort    = "";
    $search_text  = '';
    $order_cols   = array();
    if ($_POST) {
      $school_id  = $this->input->post('school_id');
      $start      = $this->input->post('start');
      $due        = $this->input->post('due');
      $limit      = $this->input->post('length');
      $order_cols = $this->input->post('order');
      $draw       = $this->input->post('draw');
      if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search_text = $_POST['search']['value'];
      }
    }

    if (!$school_id && $this->session->userdata('role_id') != SUPER_ADMIN) {
      $school_id = $this->session->userdata('school_id');
    }

    $school = $this->invoice->get_school_by_id($school_id);
    // var_dump($order_cols);
    if (!empty($order_cols)) {
      foreach ($order_cols as $order) {
        if ($order['column'] == 13) {
          $sort_coloumn = "I.paid_status";
        } elseif ($order['column'] == 12) {
          $sort_coloumn = "EF.emi_name";
        } elseif ($order['column'] == 4) {
          $sort_coloumn = "S.name";
        } elseif ($order['column'] == 6) {
          $sort_coloumn = "C.name";
        } elseif ($order['column'] == 7) {
          $sort_coloumn = "IH.title";
        }
        $sort_sort = $order['dir'];

      }
    }

    if ($school_id) {

      $totalRecords = $this->invoice->get_invoice_list_total($school_id, $due, @$school->academic_year_id, $search_text);
      $invoices     = $this->invoice->get_invoice_list_ajax($school_id, $due, @$school->academic_year_id, $start, $limit, $search_text, $sort_coloumn, $sort_sort);
    } else {
      $totalRecords = 0;
      $invoices     = array();
    }
    $count = 1;
    $data  = array();

    if (isset($invoices) && !empty($invoices)) {
      if ($this->session->userdata('role_id') != GUARDIAN || $this->session->userdata('role_id') != TEACHER) {
        foreach ($invoices as $obj) {
          if ($obj->id == 15684) {
            //var_dump($obj);
          }
          $action = '';
          if (has_permission(VIEW, 'accounting', 'invoice')) {
            $action .= ' <a href="' . site_url('accounting/invoice/view/' . $obj->id) . '" class="btn btn-info btn-xs"><i class="fa fa-eye"></i>' . $this->lang->line("view");'</a>';
          }
          if (has_permission(DELETE, 'accounting', 'invoice')) {
            if ($obj->paid_status == 'unpaid') {
              $action .= ' <a href="' . site_url('accounting/invoice/delete/' . $obj->id) . '" onclick="javascript: return confirm("' . $this->lang->line('confirm_alert') . '")" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>' . $this->lang->line("delete") . ' </a>';
            }
          }
          $net_amount = $obj->net_amount;
          if ($obj->emi_type) {
            $net_amount .= "(EMI)";
          }

          if ($obj->due_amount == 0 && $obj->paid_status == "paid") {
            $due_amount = "Paid";
          } else {
            $due_amount = $obj->due_amount;
          }
          if ($obj->emi_name) {
            $emi_name = $obj->emi_name;
          } else {
            $emi_name = "NO";
          }
          $paid_status = get_paid_status($obj->paid_status);
          $row_data    = array();
          $row_data[]  = $count;
          $row_data[]  = $obj->admission_no;
          if ($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('dadmin') == 1) {
            $row_data[] = $obj->school_name;
          }
          $row_data[] = $obj->custom_invoice_id;
          $row_data[] = $obj->student_name;
          $row_data[] = $obj->father_name;
          $row_data[] = $obj->class_name;
          $row_data[] = $obj->head;
          $row_data[] = $obj->gross_amount;
          $row_data[] = $obj->discount;
          $row_data[] = $net_amount;
          $row_data[] = $due_amount;
          $row_data[] = $emi_name;
          $row_data[] = $paid_status;
          $row_data[] = $action;

          $data[] = $row_data;
          $count++;
        }
      }
    } else {
      $data = array();
    }
    //print_r($data); exit;
    $response = array(
      "draw"                 => intval($draw),
      "iTotalRecords"        => $totalRecords,
      "iTotalDisplayRecords" => $totalRecords,
      "aaData"               => $data,
    );
    echo json_encode($response);
    exit;
  }
  /*****************Function _prepare_school_validation**********************************
   * @type            : Function
   * @function name   : _prepare_school_validation
   * @description     : Process "Academic School" user input data validation
   *
   * @param           : null
   * @return          : null
   * ********************************************************** */
  private function _prepare_voucher_validation()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

    $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');
    $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
    $this->form_validation->set_rules('type_id', $this->lang->line('type_id'), 'trim|required');
    $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required');

  }

  /*****************Function _get_posted_school_data**********************************
   * @type            : Function
   * @function name   : _get_posted_school_data
   * @description     : Prepare "Academic School" user input data to save into database
   *
   * @param           : null
   * @return          : $data array(); value
   * ********************************************************** */
  private function _get_posted_voucher_data()
  {

    $items = array();

    $items[] = 'school_id';
    $items[] = 'name';
    $items[] = 'type_id';
    $items[] = 'is_readonly';
    $items[] = 'budget';
    $items[] = 'budget_cr_dr';
    $items[] = 'category';

    $data   = elements($items, $_POST);
    $school = $this->voucher->get_school_by_id($data['school_id']);
    if (!$school->financial_year_id) {
      error('Set financial year for the school.');
      redirect('vouchers/index');
    }
    $data['financial_year_id'] = $school->financial_year_id;

    if ($this->input->post('id')) {
      $data['modified'] = date('Y-m-d H:i:s');
    } else {
      $data['created']  = date('Y-m-d H:i:s');
      $data['modified'] = date('Y-m-d H:i:s');

    }

    return $data;
  }
  public function delete($id = null)
  {

    // check_permission(DELETE);
    if (!is_numeric($id)) {
      error($this->lang->line('unexpected_error'));
      redirect('vouchers/index');
    }

    // need to find all child data from database
    // $skips = array('schools');
    //$tables = $this->db->list_tables();
    $skips  = array();
    $tables = array('account_transactions');
    foreach ($tables as $table) {

      if (in_array($table, $skips)) {continue;}

      $child_exist = $this->voucher->get_list($table, array('voucher_id' => $id), '', '', '', 'id', 'ASC');
      if (!empty($child_exist)) {
        error($this->lang->line('pls_remove_child_data'));
        redirect('vouchers/index');
      }
    }

    $voucher = $this->voucher->get_single('vouchers', array('id' => $id));

    if ($this->voucher->delete('vouchers', array('id' => $id))) {

      success($this->lang->line('delete_success'));

    } else {
      error($this->lang->line('delete_failed'));
    }
    redirect('vouchers/index');
  }

}
