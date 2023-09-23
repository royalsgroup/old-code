<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
       // $this->current_session = $this->setting_model->getCurrentSession();
    }
public function get_list($school_id = null) {
		 $this->db->select('I.*, S.school_name,IC.item_category');
        $this->db->from('item AS I');       		
        $this->db->join('schools AS S', 'S.id = I.school_id', 'left');
		$this->db->join('item_category AS IC', 'IC.id = I.item_category_id', 'left');
		if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('dadmin') != 1){
            $this->db->where('I.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		if($this->session->userdata('dadmin') == 1 && $school_id){
            $this->db->where('I.school_id', $school_id);
        }
		else if($this->session->userdata('dadmin') == 1 && $school_id==null){
			$this->db->where_in('S.id', $this->session->userdata('dadmin_school_ids'));
		}
		$this->db->order_by('I.name','asc');
        $result=$this->db->get()->result();		
		$i=0;
		$output=array();		
		foreach($result as $r){			
			$r1=(array)$r;
			$output[$i]=$r1;
			// get available quantity			
			$available_qty =$this->getItemAvailable($r->id);
			
			$output[$i]['available_qty']=$available_qty;
			$i++;
		}
		
		return $output;
	}
    public function get_default_groups($school_id){
        $this->db->select("I.*
        ,(select id from item_groups IG2 where IG2.name=I.name and IG2.description=I.description and school_id=$school_id and imported=1)  as existing_id
        ");
       $this->db->from('item_groups AS I');       		
       $this->db->where('I.school_id', 0);

       $this->db->order_by('I.name','asc');
       $result=$this->db->get();
		  
       return $result->result_array();
   }
   public function get_default_categories($school_id,$group_id,$real_grpup_id){
       
        $this->db->select("IC.*
        ,(select id from item_category IC2 where IC2.item_category=IC.item_category and IC2.description=IC.description and IC2.group_id=$group_id and school_id=$school_id and imported=1)  as existing_id
        ");
        $this->db->from('item_category AS IC');       		
        $this->db->where('IC.school_id', 0);
        $this->db->where('IC.group_id', $real_grpup_id);

        $this->db->order_by('IC.item_category','asc');
        $result=$this->db->get();
        //echo $this->db->last_query();

        return $result->result_array();;
    }
    public function get_default_items($school_id,$category_id,$real_category_id){
       
        $this->db->select("I.*
        ,(select id from item I2 where I2.name=I.name and I2.description=I.description and I2.item_category_id=$category_id  and I2.item_code = I.item_code and school_id=$school_id and imported=1)  as existing_id");
        $this->db->from('item AS I');       		
        $this->db->where('I.school_id', 0);
        $this->db->where('I.item_category_id', $real_category_id);
        $this->db->order_by('I.name','asc');
        $result=$this->db->get();		
       
        return $result->result_array();
    }
    public function getItemByCategory($item_category_id) {
        $this->db->select('item.id,item.name,item.item_category_id,item_category.item_category,item_category.id as `item_category_id`');
        $this->db->from('item');
        $this->db->join('item_category', 'item_category.id = item.item_category_id');
        $this->db->where('item.item_category_id', $item_category_id);
        $this->db->order_by('item.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getItemBySchool($school_id) {
        $this->db->select('item.id,item.name,item.item_code,item.item_category_id,item.unit,item_category.item_category,item_category.id as `item_category_id`');
        $this->db->from('item');
        $this->db->join('item_category', 'item_category.id = item.item_category_id');
        $this->db->where('item.school_id', $school_id);
        $this->db->order_by('item.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getItemunit($id) {
        $this->db->select('item.id,item.name,item.unit,item.item_category_id');
        $this->db->from('item');
       // $this->db->join('item_category', 'item_category.id = item.item_category_id');
        $this->db->where('item.id', $id);
        //$this->db->order_by('item.id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function valid_check_exists($str) {
        $name = $this->input->post('name');
        $id = $this->input->post('id');
        $item_category_id = $this->input->post('item_category_id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($name, $item_category_id, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_data_exists($name, $item_category_id, $id) {
        $this->db->where('name', $name);
        $this->db->where('item_category_id', $item_category_id);
        $this->db->where('id !=', $id);
        $query = $this->db->get('item');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get($id = null,$school_id = null) {
       // $where = "";

        

        $query = "SELECT  schools.school_name, item.*,item_category.item_category,item_store.item_store,item_store.code,item_supplier.item_supplier,item_supplier.phone,item_supplier.email,item_supplier.address,IFNULL(item_issues.issued,0) as `issued`,IFNULL(item_issues.returned,0) as `returned`,IFNULL(item_stock.item_stock_quantity,0) added_stock FROM `item` left JOIN item_category on item.item_category_id=item_category.id left JOIN item_store on item.item_store_id=item_store.id left JOIN schools on item.school_id=schools.id  left JOIN item_supplier on item.item_supplier_id=item_supplier.id left JOIN (SELECT item_stock.item_id,sum(quantity) item_stock_quantity FROM `item_stock` group by item_stock.item_id) as item_stock on item_stock.item_id=item.id left JOIN (SELECT m.item_id as `issue_item_id`, IFNULL((SELECT SUM(quantity) FROM item_issue WHERE item_issue.item_id = m.item_id and item_issue.is_returned =1),0) as `issued` ,IFNULL((SELECT SUM(quantity) FROM item_issue WHERE item_issue.item_id = m.item_id and item_issue.is_returned =0),0) as `returned` FROM item_issue m GROUP BY item_id) as item_issues on item_issues.issue_item_id=item.id";
			$query= $query." where 1";
            if ($id != null) {
                $query =$query." and item.id =".$id;

            }
			if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != DISTRICT_ADMIN){
				
				$query =$query." and item.school_id=".$this->session->userdata('school_id');
            //$this->db->where('IC.school_id', $this->session->userdata('school_id'));
        }
        
        if($this->session->userdata('role_id') == SUPER_ADMIN && $school_id){
			$query =$query." and item.school_id=".$school_id;
            //$this->db->where('IC.school_id', $school_id);
        }
		if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id){
			$query =$query." and item.school_id=".$school_id;            
        }
		else if($this->session->userdata('role_id') == DISTRICT_ADMIN && $school_id==null){						
			$query =$query." and schools.district_id=".$this->session->userdata('district_id');			
		}
            $query = $this->db->query($query);
            if ($id != null) {
                return $query->row_array();
            }
            return $query->result_array();
    }
    public function getItemLastPrice($item_id = null,$school_id =null)
    {
        $this->db->select('IS.purchase_price,IS.mrp');
        $this->db->from('item_stock AS IS');   
        $this->db->where('IS.item_id', $item_id);
        $this->db->order_by('IS.id', 'desc');
        if($school_id)
        {
            $this->db->where('IS.school_id', $school_id);
        }
        return $this->db->get()->row();
    }
     public function getItemAvailable($item_id = null,$school_id =null) {
       /* $where = "";
        $query = "SELECT item.*,item_category.item_category,item_store.item_store,item_store.code,item_supplier.item_supplier,item_supplier.phone,item_supplier.email,item_supplier.address,IFNULL(item_issues.issued,0) as `issued`,IFNULL(item_issues.returned,0) as `returned`,IFNULL(item_stock.item_stock_quantity,0) added_stock FROM `item` left JOIN item_category on item.item_category_id=item_category.id left JOIN item_store on item.item_store_id=item_store.id left JOIN item_supplier on item.item_supplier_id=item_supplier.id left JOIN (SELECT item_stock.item_id,sum(quantity) item_stock_quantity FROM `item_stock` group by item_stock.item_id) as item_stock on item_stock.item_id=item.id left JOIN (SELECT m.item_id as `issue_item_id`, IFNULL((SELECT SUM(quantity) FROM item_issue WHERE item_issue.item_id = m.item_id and item_issue.is_returned =1),0) as `issued` ,IFNULL((SELECT SUM(quantity) FROM item_issue WHERE item_issue.item_id = m.item_id and item_issue.is_returned =0),0) as `returned` FROM item_issue m GROUP BY item_id) as item_issues on item_issues.issue_item_id=item.id where item.id= " . $item_id;

        $query = $this->db->query($query);
        if ($item_id != null) {
            return $query->row_array();
        }*/
		$unit=0;
		$available_qty=0;
		$this->db->select('I.*');
			$this->db->from('item AS I');   
			$this->db->where('I.id', $item_id);
			$item=$this->db->get()->row();
			
			if($item->unit >0){
				$unit=$item->unit;
				$available_qty +=$unit;
			}
		 $this->db->select('IS.*');
			$this->db->from('item_stock AS IS');   
			$this->db->where('IS.item_id', $item_id);
            if($school_id)
            {
                $this->db->where('IS.school_id', $school_id);
            }
			$stock=$this->db->get()->result();
			
			foreach($stock as $s){
				if($s->symbol == '-'){
					$available_qty=$available_qty - $s->quantity;
				}
				else{
					$available_qty=$available_qty + $s->quantity;
				}
			}
			// get issues items and deduct from available_qty
			 $this->db->select_sum('quantity');
			$this->db->from('item_issue AS IS');   
			$this->db->where('IS.item_id', $item_id);
			$this->db->where('IS.is_returned != ',1);
            if($school_id)
            {
                $this->db->where('IS.school_id', $school_id);
            }
			$issue_item=$this->db->get()->row();
			
			// deduct from stock
			$available_qty= $available_qty - $issue_item->quantity;
			
			$this->db->select_sum('quantity');
			$this->db->from('item_issue AS IS');   
			$this->db->where('IS.item_id', $item_id);
			$this->db->where('IS.is_returned', 1);
            if($school_id)
            {
                $this->db->where('IS.school_id', $school_id);
            }
			$return_item=$this->db->get()->row();
			$available_qty= $available_qty + $return_item->quantity;
			return $available_qty;
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('item');
		$message      = DELETE_RECORD_CONSTANT." On item id ".$id;
        $action       = "Delete";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
		//======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
        //return $return_value;
        }
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('item', $data);
			$message      = UPDATE_RECORD_CONSTANT." On  item id ".$data['id'];
			$action       = "Update";
			$record_id    = $data['id'];
			$this->log($message, $record_id, $action);
			//======================Code End==============================

			$this->db->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
        } else {
            $this->db->insert('item', $data);
            $insert_id = $this->db->insert_id();
			$message      = INSERT_RECORD_CONSTANT." On item id ".$insert_id;
			$action       = "Insert";
			$record_id    = $insert_id;
			$this->log($message, $record_id, $action);
			//echo $this->db->last_query();die;
			//======================Code End==============================

			$this->db->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
			return $insert_id;
        }
    }

}
