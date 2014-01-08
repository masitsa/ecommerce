<?php

class Order_model extends CI_Model
{	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve particular data from multiple tables in the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_entries_where($table, $where, $items, $order)
    {
        $this->db->select($items);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by($order, "asc");
       
        $query = $this->db->get();
       
        return $query->result();
    }
	 function select_entries_where2($table, $where, $items, $order)
    {
        $this->db->select($items);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by($order, "DESC");
       
        $query = $this->db->get();
       
        return $query->result();
    }
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all order items from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_order_items()
    {
        $query = $this->db->get("order_item");
		return $query->result();
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
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all the customers from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_customers()
    {
        $query = $this->db->get("customer");
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all order methods from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_order_methods()
    {
        $query = $this->db->get("order_method");
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all products from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_products()
    {
		$where = "(product_status = 1) AND (product_balance > 0)";
        $this->db->select("*");
		$this->db->from("product");
        $this->db->where($where);
		$this->db->order_by("product_name", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve an order from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_orders2($order_id)
    {
		$this->db->select("*");
		$this->db->from("order");
        $this->db->where("order_type = 0 AND order_id = ".$order_id);
		$this->db->order_by("order_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all the orders from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_orders()
    {
		$this->db->from("order");
        $this->db->where("order_type = 0");
        $query = $this->db->get();
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve a single order from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_order($order_id)
    {
		$this->db->select("*");
		$this->db->from("order");
        $this->db->where("order_type = 0 AND order_id = ".$order_id);
		$this->db->order_by("order_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve orders by delivery date from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_order_date($order_delivery_date)
    {
		$this->db->select("*");
		$this->db->from("order");
        $this->db->where("order_type = 0 AND order_delivery_date = '".$order_delivery_date."'");
		$this->db->order_by("order_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve customer id
		-----------------------------------------------------------------------------------------
	*/
	 function select_customer_id($customer_name)
    {
		$this->db->select("*");
		$this->db->from("customer");
        $this->db->where("customer_name = '".$customer_name."'");
		$this->db->order_by("customer_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Create a new order
		-----------------------------------------------------------------------------------------
	*/
	 function insert_order($customer_name)
    {
		/*
			-----------------------------------------------------------------------------------------
			Retrieve the customer id
			-----------------------------------------------------------------------------------------
		*/
		$customer = $this->select_customer_id($customer_name);
		if(count($customer) > 0){
			foreach ($customer as $cust){
				$customer_id = $cust->customer_id;
			}
		
			$new_order_insert_data = array(
				'users_id' => $this->session->userdata('users_id'),
				'customer_id' => $customer_id,
				'order_delivery_date' => $this->input->post('delivery_date'),
				'order_method_id' => $this->input->post('order_method_id')					
			);
		
			$insert = $this->db->insert('order', $new_order_insert_data);
			$order_id = $this->db->insert_id();
			return $order_id;
		}
		
		else{
			return 0;
		}
    }
	
	 function update_order($order_id)
    {
		
		$customer = $this->select_customer_id($this->input->post('customer_name'));
		if(count($customer) > 0){
			foreach ($customer as $cust){
				$customer_id = $cust->customer_id;
			}
			$update_data = array(//get the items from the form
				'customer_id' => $customer_id,
				'order_method_id' => $this->input->post('order_method_id'),	
				'order_delivery_date' => $this->input->post('order_delivery_date'),	
				'order_instructions' => $this->input->post('order_instructions'),	
				'coupon_id' => $this->input->post('coupon_id'),			
				'order_discount' => $this->input->post('order_discount')					
			);
        	$this->db->where("order_id = ".$order_id);
			$this->db->update('order', $update_data);
		
			//save session action
			$this->save_session(4, "order", $order_id);
		}
    }
	
	 function delete_order($order_id)//save new orders
    {
        $this->db->where("order_id = ".$order_id);
		$insert = $this->db->delete('order_item');//delete products linked to the order
		
        $this->db->where("order_id = ".$order_id);
		$insert = $this->db->delete('order');//save the form items into the order table
		
		//save session action
		$this->save_session(5, "order", $order_id);
		return $insert;
    }
	
	public function save_session($activity_id, $table, $table_id)
	{		
		//save session action
		$new_session_insert_data = array(//get the items from the form
			'users_id' => $this->session->userdata('users_id'),
			'activity_id' => $activity_id,
			'table' => $table,
			'table_id' => $table_id				
		);
		$insert = $this->db->insert('`session`', $new_session_insert_data);//save session action
		return $insert;
	}
	
	function select_last_order_session($table, $table_id, $activity_id)
	{
		$items = "MAX(session_entry_time) AS last_modified";
		$where = "`table` = '".$table."' AND table_id = ".$table_id." AND (activity_id = ".$activity_id." OR activity_id = 3)";
		$table = "`session`";
		
		$this->db->select($items);
		$this->db->from($table);
		$this->db->where($where);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function select_features_by_order($order_id)
	{
		$items = "*";
		$where = "`order_id` = ".$order_id;
		$table = "`category_features`";
		
		$this->db->select($items);
		$this->db->from($table);
		$this->db->where($where);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	 function enable_order($order_id)//save new orders
    {
		$update_data = array(//get the items from the form
			'order_status2' => 1				
		);
		$this->db->where("order_id = ".$order_id);
		$insert = $this->db->update('order', $update_data);
		
		//save session action
		$this->save_session(6, "order", $order_id);
		return $insert;
    }
	
	 function disable_order($order_id)//save new orders
    {
		$update_data = array(//get the items from the form
			'order_status2' => 0				
		);
		$this->db->where("order_id = ".$order_id);
		$insert = $this->db->update('order', $update_data);
		
		//save session action
		$this->save_session(7, "order", $order_id);
		return $insert;
    }
	
    public function fetch_products($limit, $start, $category, $order, $order_type, $brand) {
        
		$this->db->limit($limit, $start);
        
		$where = "(product.category_id = category.category_id) AND (product.product_status = 1) AND (product.product_balance > 0) ".$category." ".$brand;
        $this->db->select("*");
		$this->db->from("product, category");
        $this->db->where($where);
		$this->db->order_by($order, $order_type); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }  
	
    public function fetch_products_brand($limit, $start, $brand_id, $order, $order_type) {
        
		$this->db->limit($limit, $start);
        
		$where = "(product.category_id = category.category_id) AND (product.product_status = 1) AND (product.product_balance > 0) AND (product.brand_id = ".$brand_id.")";
        $this->db->select("*");
		$this->db->from("product, category");
        $this->db->where($where);
		$this->db->order_by($order, $order_type); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }  
    
	public function products_count($category, $brand) 
	{
		$where = "(product.category_id = category.category_id) AND (product.product_status = 1) AND (product.product_balance > 0) ".$category." ".$brand;
        $this->db->where($where);
		$this->db->from("product, category");
        return $this->db->count_all_results();
    } 
    
	public function products_count_brand($brand_id)
	{
		$where = "(product.category_id = category.category_id) AND (product.product_status = 1) AND (product.product_balance > 0)  AND (product.brand_id = ".$brand_id.")";
        $this->db->where($where);
		$this->db->from("product, category");
        return $this->db->count_all_results();
    }
	
	function select_last_product_session($table, $table_id, $activity_id)
	{
		$items = "MAX(session_entry_time) AS last_modified";
		$where = "`table` = '".$table."' AND table_id = ".$table_id." AND (activity_id = ".$activity_id." OR activity_id = 3)";
		$table = "`session`";
		
		$this->db->select($items);
		$this->db->from($table);
		$this->db->where($where);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	 function select_product($product_id)//retrieve a single product from the database
    {
		$this->db->select("*");
		$this->db->from("product, category");
        $this->db->where("(product.category_id = category.category_id) AND (product_id = ".$product_id.")");
		$this->db->order_by("product_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	 function select_product_features_by_product($product_id)//retrieves product features from the database by the product id
    {
		$this->db->select("product_features.feature_value, category_features.feature_units, product_features.feature_id, category_features.feature_name");
		$this->db->from("product_features, category_features");
        $this->db->where("(product_features.product_id = ".$product_id." AND category_features.feature_id = product_features.feature_id)");
		
		$query = $this->db->get();
		return $query->result();
    }
	
	 function select_order_customer($order_id)//retrieves product features from the database by the product id
    {
		$this->db->select("customer.customer_name, customer.customer_phone, customer.customer_address");
		$this->db->from("order, customer");
        $this->db->where("order.customer_id = customer.customer_id AND order.order_id = ".$order_id);
		
		$query = $this->db->get();
		return $query->result();
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
		Retrieve all orders from the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_all_orders($limit, $start)
    {
		$table = "order, customer";
		$where = "order_type = 0 AND order.customer_id = customer.customer_id";
		
		$this->db->limit($limit, $start);
        
        $this->db->select("order.order_id, order.order_status, order.order_status2, order.order_date, order.order_delivery_date, customer.customer_name");
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by("order_date", "asc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all orders from the database
		-----------------------------------------------------------------------------------------
	*/
	 function search_orders($limit, $start, $customer_name, $order_date, $delivery_date, $product, $product2)
    {
		$table = "order, customer";
		$where = "(order.order_type = 0) AND (`order`.customer_id = customer.customer_id) AND ((customer.customer_name = '".$customer_name."') OR (order.order_date ".$order_date.") OR (order.order_delivery_date = '".$delivery_date."'))";
				
		$this->db->limit($limit, $start);
        
        $this->db->select("order.order_id, order.order_status, order.order_status2, order.order_date, order.order_delivery_date, customer.customer_name");
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by("order_date", "asc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
	
	public function search_order($customer_name, $order_date, $delivery_date, $product, $product2)
	{
		$table = "order, customer, product, order_item";
		$items = "order.order_id, order.order_status, order.order_status2, order.order_date, order.order_delivery_date, customer.customer_name";
		$where = "(order.order_type = 0) AND (`order`.customer_id = customer.customer_id) AND (order_item.order_id = `order`.order_id) AND (order_item.product_id = product.product_id) AND ((customer.customer_name = '".$customer_name."') OR (order.order_date ".$order_date.") OR (order.order_delivery_date = '".$delivery_date."') OR (product.product_name ".$product.") OR (product.product_code = '".$product2."'))";
		$order = "order_date";
		
		$this->db->select($items);
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
		return $query->result();
	} 
    
	public function orders_count() {
		$where = "(order_type = 0) AND (order_id > 0)";
        $this->db->where($where);
		$this->db->from("order");
        return $this->db->count_all_results();
    }
    
	public function orders_count2() {
		$where = "(order_type = 0) AND (order_id > 0)";
        $this->db->where($where);
		$this->db->from("order");
        return $this->db->count_all_results();
    }
	
	 function increase_stock_balance($order_id)
    {
		//retrieve order items
		$order = $this->select_order_items();
		
		if(count($order) > 0){
			foreach ($order as $cust){
				$order_id2 = $cust->order_id;
				
				//for the particular order's products
				if($order_id2 == $order_id){
					$order_item_quantity = $cust->order_item_quantity;
					$product_id = $cust->product_id;
					
					//retrieve the product balance
					$product = $this->select_product($product_id);
					if(count($product) > 0){
						foreach ($product as $cust){
							$product_balance = $cust->product_balance;
						}
						$update_data = array(//get the items from the form
							'product_balance' => ($product_balance + $order_item_quantity)
						);
        				$this->db->where("product_id = ".$product_id);
						$this->db->update('product', $update_data);
					}
				}
				
				$product_id = $cust->product_id;
			}
		}
    }
	
	 function decrease_stock_balance($order_id)
    {
		//retrieve order items
		$order = $this->select_order_items();
		
		if(count($order) > 0){
			foreach ($order as $cust){
				$order_id2 = $cust->order_id;
				
				//for the particular order's products
				if($order_id2 == $order_id){
					$order_item_quantity = $cust->order_item_quantity;
					$product_id = $cust->product_id;
					
					//retrieve the product balance
					$product = $this->select_product($product_id);
					if(count($product) > 0){
						foreach ($product as $cust){
							$product_balance = $cust->product_balance;
						}
						$update_data = array(//get the items from the form
							'product_balance' => ($product_balance - $order_item_quantity)
						);
        				$this->db->where("product_id = ".$product_id);
						$this->db->update('product', $update_data);
					}
				}
				
				$product_id = $cust->product_id;
			}
		}
    }
	
	public function select_order_items2($order_id)
	{
		$table = "order, product, order_item";
		$items = "product.product_name, product.product_code, order_item.order_item_quantity, order_item.order_item_price, product.product_image_mime, product.product_image, product.product_id";
		$where = "(order.order_type = 0) AND (order_item.order_id = `order`.order_id) AND (order_item.product_id = product.product_id) AND (order.order_id = ".$order_id.")";
		$order = "product_name";
		
		$this->db->select($items);
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
		return $query->result();
	} 
	
	public function search_products($limit, $start, $search)
	{
		$this->db->limit($limit, $start);
        
		$where = "((product.product_code LIKE '%".$search."%') OR (product.product_name LIKE '%".$search."%') OR (category.category_name LIKE '%".$search."%')) AND (product.category_id = category.category_id) AND (product_balance > 0)";
        $this->db->select("product.product_code, product.product_name, product.product_selling_price, product.product_buying_price, product.product_id, product.product_status, product.product_balance, product.category_id, product.product_description");
		$this->db->from("product, category");
        $this->db->where($where);
		$this->db->order_by("product_name", "asc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	} 
    
	public function search_products_count($search) {
        $this->db->where("((product.product_code LIKE '%".$search."%') OR (product.product_name LIKE '%".$search."%') OR (category.category_name LIKE '%".$search."%')) AND (product.category_id = category.category_id) AND (product_balance > 0)");
		$this->db->from("product, category");
        return $this->db->count_all_results();
    }
	
	public function search_products2($limit, $start, $category, $order, $order_type, $brand, $search)
	{
		$this->db->limit($limit, $start);
        
		$where = "((product.product_code LIKE '%".$search."%') OR (product.product_name LIKE '%".$search."%') OR (category.category_name LIKE '%".$search."%')) AND (product.category_id = category.category_id) AND (product.product_status = 1) AND (product.product_balance > 0) ".$category." ".$brand;
		//$where = "((product.product_code LIKE '%".$search."%') OR (product.product_name LIKE '%".$search."%') OR (category.category_name LIKE '%".$search."%')) ".$category." AND (product.category_id = category.category_id) AND (product_balance > 0)";
        $this->db->select("*");
		$this->db->from("product, category");
        $this->db->where($where);
		$this->db->order_by("product_name", "asc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
    
	public function search_products_count2($category, $brand, $search) {
		$where = "((product.product_code LIKE '%".$search."%') OR (product.product_name LIKE '%".$search."%') OR (category.category_name LIKE '%".$search."%')) AND (product.category_id = category.category_id) AND (product.product_status = 1) AND (product.product_balance > 0) ".$category." ".$brand;
        $this->db->where($where);
		$this->db->from("product, category");
        return $this->db->count_all_results();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Save data to the database
		-----------------------------------------------------------------------------------------
	*/
	 function insert($table, $items)
    {
        $this->db->insert($table, $items);
		
		return $this->db->insert_id();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Updates data in the database
		-----------------------------------------------------------------------------------------
	*/
	 function update($table, $items, $field, $key)
    {
		$this->db->where($field, $key);
        $this->db->update($table, $items);
    }
	
    public function fetch_related_products($product_id) {
        
		$this->db->limit(3);
        
		$where = "(product_status = 1) AND (product_balance > 0) AND (category_id = (SELECT category_id FROM product WHERE product_id = ".$product_id."))";
        $this->db->select("*");
		$this->db->from("product");
        $this->db->where($where);
		$this->db->order_by("product_id", "DESC"); 
		
		$query = $this->db->get();
		return $query->result();
	}
	
    public function fetch_top_categories() {
        
		$this->db->limit(4);
        
		$where = "(category_id IN (2, 3, 8, 10))";
        $this->db->select("*");
		$this->db->from("category");
        $this->db->where($where);
		$this->db->order_by("category_id", "DESC"); 
		
		$query = $this->db->get();
		return $query->result();
	}
}