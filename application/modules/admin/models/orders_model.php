<?php
class Orders_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function count_all_orders($order_type)
	{
		$where = "(order_type = ".$order_type.") AND (order_id > 0)";
        $this->db->where($where);
		$this->db->from("order");
        return $this->db->count_all_results();
	}
	
	/**
	 * Get all orders from the DB
	 *
	 * @param int num
	 * @param int offset
	 * @return object
	 * 
	 */
	function select_orders($limit, $start, $order_type)
	{
		$this->db->select('o.order_id,o.order_instructions,o.order_date,u.fullname,os.status_name')->from('`order` as o')->where("(order_type = ".$order_type.") AND (order_id > 0)");
		$this->db->join('users as u','o.customer_id = u.user_id','INNER');
		$this->db->join('order_statuses as os','o.order_status = os.status_id','INNER');
		$this->db->order_by("order_date", "desc"); 
		$query = $this->db->get('',$limit,$start);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all the customers from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_customers()
    {
        
        $this->db->select("*");
		$this->db->from("users");
        $this->db->where("u_level_id = 3");
        $query = $this->db->get();
		return $query->result();
    }
	
	/**
	 * Get all orders from the DB
	 *
	 * @param int num
	 * @param int offset
	 * @return object
	 * 
	 */
	function fetch_orders()
	{
		$this->db->select('o.order_id,o.order_instructions,o.order_date,u.first_name,u.last_name,os.status_name')->from('`order` as o');
		$this->db->join('users as u','o.customer_id = u.user_id','INNER');
		$this->db->join('order_statuses as os','o.order_status = os.status_id','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all the useful details about an order for display
	 *
	 * @param int order_id
	 * @return object
	 *
	 */
	function get_order_details($order_id)
	{
		$this->db->select('o.order_id, o.coupon_id, o.order_discount, o.order_instructions, o.order_type, o.order_date, u.username, u.first_name, u.last_name, os.status_name, om.order_method_name')->from('`order` as o');
		$this->db->join('users as u','o.customer_id = u.user_id','INNER');
		$this->db->join('order_statuses as os','o.order_status = os.status_id','INNER');
		$this->db->join('order_methods as om','o.order_method_id = om.order_method_id','INNER');
		$this->db->where('o.order_id',$order_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Get all items that were added to an order
	 *
	 * @param int order_id
	 * @return object
	 *
	 */
	function get_order_items($order_id)
	{
		$this->db->select('oi.order_item_id, oi.order_item_quantity, oi.order_item_price, p.product_name, oi.order_item_customization')->from('order_item as oi');
		$this->db->join('product as p','oi.product_id = p.product_id','INNER');
		$this->db->where('oi.order_id',$order_id);
		$this->db->order_by("oi.order_item_id", "desc"); 
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Delete an order from the database
	 *
	 * @param int order_id
	 *
	 */
	function delete_order($order_id)
	{
		$this->db->delete('`order`',array('order_id'=>$order_id));
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all coupons from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_coupons()
    {
        $query = $this->db->get("coupon");
		return $query->result();
    }
	
	/**
	 *  Get a list of all ordered products
	 *  
	 * @param	int	$limit	The number of items to fetch
	 * @param	int	$start	Which position to start retrieving from
	 * @return	object
	 * 
	 */
	function fetch_order_products($order_id)
	{
		$this->db->select("order_item.order_item_id, order_item.order_item_quantity, order_item.order_item_price, order_item.order_item_customization, product.product_code, product.product_balance, product.product_name, product.product_image_name");
		$this->db->from("product, order_item");
        $this->db->where("product.product_id =order_item.product_id AND order_item.order_id = ".$order_id."");
		$this->db->order_by("order_item.order_item_id", "desc"); 
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Add coupons, discounts & instructions to the order
		-----------------------------------------------------------------------------------------
	*/
	 function add_order_description($order_id)
    {
		/*
			-----------------------------------------------------------------------------------------
			Check whether a coupon has been selected
			-----------------------------------------------------------------------------------------
		*/
		$coupon_id = $this->input->post('coupon_id');
		if($coupon_id == 0){
			$coupon_id = NULL;
		}
		$update_data = array(
			'coupon_id' => $coupon_id,
			'order_instructions' => $this->input->post('order_instructions'),
			'order_discount' => $this->input->post('order_discount')
		);
        $this->db->where("order_id = ".$order_id);
		$update = $this->db->update('order', $update_data);
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Save customization
		-----------------------------------------------------------------------------------------
	*/
	 function save_customization($order_item_id)
    {
		$customization = $this->input->post('customization');
		
		$update_data = array(
			'order_item_customization' => $customization
		);
        $this->db->where("order_item_id = ".$order_item_id);
		$update = $this->db->update('order_item', $update_data);
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Search an order
		-----------------------------------------------------------------------------------------
	*/
	public function search_order($customer_name, $order_date, $delivery_date, $product, $product2, $order_type)
	{
		$table = "order, users, order_statuses";
		$items = "order.order_instructions, order.order_id, order.order_status2, order.order_date, order.order_delivery_date, users.first_name, users.last_name, order_statuses.status_name";
		$where = "(order.order_type = ".$order_type.") AND (order.order_status = order_statuses.status_id) AND (order.order_type = 0) AND (`order`.customer_id = users.user_id) AND ((users.first_name = '".$customer_name."') OR (users.last_name = '".$customer_name."') OR (order.order_date ".$order_date.") OR (order.order_delivery_date = '".$delivery_date."'))";
		$order = "order_date";
		
		$this->db->select($items);
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
		return $query->result();
	} 
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all orders from the database
		-----------------------------------------------------------------------------------------
	*/
	 function search_orders($limit, $start, $customer_name, $order_date, $delivery_date, $product, $product2, $order_type)
    {
		$table = "order, users, order_statuses";
		
		$where = "(order.order_type = ".$order_type.") AND (order.order_status = order_statuses.status_id) AND (order.order_type = 0) AND (`order`.customer_id = users.user_id) AND ((users.first_name = '".$customer_name."') OR (users.last_name = '".$customer_name."') OR (order.order_date ".$order_date.") OR (order.order_delivery_date = '".$delivery_date."'))";
				
		$this->db->limit($limit, $start);
        
        $this->db->select("order.order_instructions, order.order_id, order.order_status2, order.order_date, order.order_delivery_date, users.first_name, users.last_name, order_statuses.status_name");
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by("order_date", "asc"); 
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
    }
}