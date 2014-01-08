<?php 

class Cart_model extends CI_Model {
	
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
	
	// Delete an item from the shopping cart
	function delete_item($row_id){
		
		$data = array(
               'rowid' => $row_id,
               'qty'   => 0
            );
            // Update the cart with the new information
			$this->cart->update($data);
	}
	
	// Updated the shopping cart
	function validate_update_cart2()
	{
		/*
			-----------------------------------------------------------------------------------------
			Retrieve the items from the update form
			-----------------------------------------------------------------------------------------
		*/
		$total = $this->cart->total_items();
		$row_id = $this->input->post('rowid');
	    $qty = $this->input->post('qty');
	    $customization = $this->input->post('order_item_customization');
		
		/*
			-----------------------------------------------------------------------------------------
			Create an array with the info to be updated
			-----------------------------------------------------------------------------------------
		*/
		for($i = 0; $i < $total; $i++)
		{
			$update['rowid'][$i] = $row_id[$i];
			$update['qty'][$i] = $qty[$i];
			$update['customization'][$i] = $customization[$i];
		}
		
		/*
			-----------------------------------------------------------------------------------------
			Retrieve the items from the cart then destroy it
			-----------------------------------------------------------------------------------------
		*/
		$r = 0;
		foreach($this->cart->contents() as $items):
			$old['item_name'][$r] = $items['name'];
			$old['item_price'][$r] = $items['price'];
			$old['item_id'][$r] = $items['id'];
			$old['row_id'][$r] = $items['rowid'];
			$r++;
		endforeach;
		$this->cart->destroy();
		
		/*
			-----------------------------------------------------------------------------------------
			Update the items with new quantity and customization options
			-----------------------------------------------------------------------------------------
		*/
		for($t = 0; $t < $r; $t++){
			for($i = 0; $i < $total; $i++){
				
				if($old['row_id'][$t] == $update['rowid'][$i]){
					
					$old['qty'][$t] = $update['qty'][$i];
					$old['customization'][$t] = $update['customization'][$i];
				}
			}
		}
		
		/*
			-----------------------------------------------------------------------------------------
			Create a new cart session
			-----------------------------------------------------------------------------------------
		*/
		for($t = 0; $t < $r; $t++){
			$data = array(
               		'id'      => $old['item_id'][$t],
               		'qty'     => $old['qty'][$t],
               		'price'   => $old['item_price'][$t],
               		'name'   => $old['item_name'][$t],
					'options' => array('customization' => $old['customization'][$t])
            	);

			$this->cart->insert($data); 
		}
	}
	
	// Updated the shopping cart
	function validate_update_cart(){
		
		// Get the total number of items in cart
		$total = $this->cart->total_items();
		
		// Retrieve the posted information
		$item = $this->input->post('rowid');
	    $qty = $this->input->post('qty');

		// Cycle true all items and update them
		for($i=0;$i < $total;$i++)
		{
			// Create an array with the products rowid's and quantities. 
			$data = array(
               'rowid' => $item[$i],
               'qty'   => $qty[$i]
            );
            // Update the cart with the new information
			$this->cart->update($data);
		}
	}
	
	// Updated the shopping cart
	function validate_update_cart3($rowid, $qty){

		$data = array(
               'rowid' => $rowid,
               'qty'   => $qty
            );
		
		$this->cart->update($data); 
	}
	
	// Add an item to the cart
	function validate_add_cart_item($id, $cty, $price, $name){
		
		$data = array(
               		'id'      => $id,
               		'qty'     => $cty,
               		'price'   => $price,
               		'name'   => $name
            	);

		$this->cart->insert($data); 
	}
	
	 function select_product($product_id)//retrieve a single product from the database
    {
		$this->db->select("*");
		$this->db->from("product");
        $this->db->where("product_id = ".$product_id."");
		$this->db->order_by("product_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	 function select_order_items($order_id)//retrieve a single product from the database
    {
		$this->db->select("order_item.order_item_id, order_item.order_item_quantity, order_item.order_item_price, order_item.order_item_customization, product.product_code, product.product_balance, product.product_name, product.product_image_name");
		$this->db->from("product, order_item");
        $this->db->where("product.product_id =order_item.product_id AND order_item.order_id = ".$order_id."");
		$this->db->order_by("order_item.order_item_id", "desc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	// Complete the transaction by checking out
	function checkout($order_id){
		
		foreach($this->cart->contents() as $items){
			
			if ($this->cart->has_options($items['rowid']) == TRUE){
				
				foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value):
					$data = array(
               			'product_id' => $items['id'],
               			'order_id' => $order_id,
               			'order_item_quantity'   => $items['qty'],
               			'order_item_customization'   => $option_value,
               			'order_item_price'   => $items['price']
            		);				
				endforeach; 
			}
			
			else{
			
				$data = array(
               		'product_id' => $items['id'],
               		'order_id' => $order_id,
               		'order_item_quantity'   => $items['qty'],
               		'order_item_price'   => $items['price']
            	);
			}
            // Save the items in the database
			$insert = $this->db->insert('order_item', $data);
		
			//retrieve the product_balance
			$product = $this->select_product($items['id']);
			if(count($product) > 0){
				foreach ($product as $cust){
					$product_balance = $cust->product_balance;
					$product_balance -= $items['qty'];
				}
		
				//Decrease the stock balance
				$update_data = array(
               						'product_balance' => $product_balance
            					);
        		$this->db->where("product_id = ".$items['id']);
				$update = $this->db->update('product', $update_data);
			}
		}
		//make the order active
		$update_data = array(
               'order_status' => 1
            );
        $this->db->where("order_id = ".$order_id);
		$update = $this->db->update('order', $update_data);
		
		//create order log
		$this->save_session(3, "order", $order_id);
	}
	
	public function save_session($activity_id, $table, $table_id)
	{		
		if($this->session->userdata('users_id') <= 0){
			$user = NULL;
		}
		else{
			$user = $this->session->userdata('users_id');
		}
		//save session action
		$new_session_insert_data = array(//get the items from the form
			'users_id' => $user,
			'activity_id' => $activity_id,
			'table' => $table,
			'table_id' => $table_id				
		);
		$insert = $this->db->insert('`session`', $new_session_insert_data);//save session action
		return $insert;
	}
}