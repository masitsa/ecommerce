<?php session_start();
require_once "./application/modules/admin/controllers/admin.php";

class Cart extends MX_Controller
{
   private $_status = array('status' => NULL, 'message' => NULL);

	function Cart()
	{
        parent:: __construct();	
		$this->load->model('administration_model'); // Load our cart model for our entire class
		$this->load->model('cart_model'); // Load our cart model for our entire class
	}
	
	/********************************************************************************************
	*																							*
	*									ORDER CART FUNCTIONS									*
	*																							*
	********************************************************************************************/
	
	function add_bulk_cart_item($order_id){
		
		$total_products = count($this->input->post('product'));
		if(isset($total_products)){//check if any checkboxes have been ticked
			
			for($r = 0; $r < $total_products; $r++){
				
				$product = $_POST['product'];
				$product_id = $product[$r];
				$cty = 1; // Assign posted quantity to $cty
				$products_details = $this->cart_model->select_product($product_id);//select single product
				
				foreach ($products_details as $prod){
					$price = $prod->product_selling_price; // Assign posted quantity to $price
					$name = $prod->product_name; // Assign posted quantity to $name
					
					$items = array(
						"order_id" => $order_id,
						"product_id" => $product_id,
						"order_item_quantity" => $cty,
						"order_item_price" => $price
					);
					
					$this->administration_model->insert("order_item", $items);
				}
			}
			$msg = "true";
		}
		
		else{
			$msg = "false";
		}

     	echo $msg;
	}
	
	function add_order_item($order_id){
		
		$product_id = $this->input->post('product_id');
		$cty = $this->input->post('quantity');
		$products_details = $this->cart_model->select_product($product_id);
				
		foreach ($products_details as $prod){
			$price = $prod->product_selling_price;
					
			$items = array(
				"order_id" => $order_id,
				"product_id" => $product_id,
				"order_item_quantity" => $cty,
				"order_item_price" => $price
			);
					
			$this->administration_model->insert("order_item", $items);
		}
		echo "true";
	}
	
	function update_order_item($order_item_id){
		
		$cty = $this->input->post('quantity');
		$items = array(
				"order_item_quantity" => $cty
			);
					
		$this->administration_model-> update("order_item", $items, "order_item_id", $order_item_id);
		
		echo "true";
	}
	
	function delete_order_item($order_item_id){
		
		$this->administration_model->delete("order_item", "order_item_id", $order_item_id);
		
		echo "true";
	}
	
	function order_cart($order_id)
	{
		$data['order_items'] = $this->cart_model->select_order_items($order_id);
		$data['order_id'] = $order_id;
		$this->load->view("cart/items_in_cart4", $data);
	}
	
	function order_cart2($order_id)
	{
		$data['order_items'] = $this->cart_model->select_order_items($order_id);
		$data['order_id'] = $order_id;
		$this->load->view("cart/items_in_cart5", $data);
	}
	
	function add_cart_item($order_id){
		
		$id = $this->input->post('product_id');
		$cty = $this->input->post('quantity');
		$price = $this->input->post('price');
		$name = $this->input->post('product_name');
		
		$this->cart_model->validate_add_cart_item($id, $cty, $price, $name);
		header('Location: '.site_url("orders/open_products/".$order_id));
	}
	
	function update_cart($order_id){
		$this->cart_model->validate_update_cart();
		header('Location: '.site_url("orders/view_cart/".$order_id));
	}
	
	function delete_item($order_id, $row_id){
		$this->cart_model->delete_item($row_id);
		header('Location: '.site_url("orders/view_cart/".$order_id));
	}
	
	function empty_cart($order_id){
		$this->cart->destroy();
		header('Location: '.site_url("orders/open_products/".$order_id));
	}
	
	function checkout($order_id){
		$this->cart_model->checkout($order_id);
		$this->cart->destroy();//empty the cart
		header('Location: '.site_url("orders/list_orders/"));
	}
	
	/********************************************************************************************
	*																							*
	*									CUSTOM ORDER CART FUNCTIONS								*
	*																							*
	********************************************************************************************/
	
	function add_bulk_cart_item2($order_id){
		
		$total_products = count($this->input->post('product'));
		if(isset($total_products)){//check if any checkboxes have been ticked
			
			for($r = 0; $r < $total_products; $r++){
				
				$product = $_POST['product'];
				$product_id = $product[$r];
				$cty = 1; // Assign posted quantity to $cty
				$products_details = $this->cart_model->select_product($product_id);//select single product
				
				foreach ($products_details as $prod){
					$price = $prod->product_selling_price; // Assign posted quantity to $price
					$name = $prod->product_name; // Assign posted quantity to $name
					
					$this->cart_model->validate_add_cart_item($product_id, $cty, $price, $name);
				}
			}
			if($this->cart->total_items() > 0){
				$mes = "<a href=".site_url()."/custom_orders/checkout/".$order_id.">Checkout</a>";
            }
			else{
				$mes = NULL;
			}
       		$this->_status['status']  = "success";
       		$this->_status['message'] = '<a href="'.site_url()."/custom_orders/view_cart/".$order_id.'">'.$this->cart->total_items().' items in cart @ KES '.$this->cart->format_number($this->cart->total())."</a>  ".$mes;
		}
		
		else{
       		$this->_status['status']  = "error";
       		$this->_status['message'] = 'You have not selected any items';
		}

     echo json_encode($this->_status);
	}
	
	function add_cart_item2($order_id){
		
		$id = $this->input->post('product_id'); // Assign posted product_id to $id
		$cty = $this->input->post('quantity'); // Assign posted quantity to $cty
		$price = $this->input->post('price'); // Assign posted quantity to $price
		$name = $this->input->post('product_name'); // Assign posted quantity to $name
		
		$this->cart_model->validate_add_cart_item($id, $cty, $price, $name);
		header('Location: '.site_url("custom_orders/open_products/".$order_id));
	}
	
	function update_cart2($order_id){
		$this->cart_model->validate_update_cart2();
		header('Location: '.site_url("custom_orders/view_cart/".$order_id));
	}
	
	function delete_item2($order_id, $row_id){
		$this->cart_model->delete_item($row_id);
		header('Location: '.site_url("custom_orders/view_cart/".$order_id));
	}
	
	function empty_cart2($order_id){
		$this->cart->destroy();
		header('Location: '.site_url("custom_orders/open_products/".$order_id));
	}
	
	function checkout2($order_id){
		$this->cart_model->checkout($order_id);
		$this->cart->destroy();//empty the cart
		header('Location: '.site_url("custom_orders/list_orders/"));
	}
	
	/********************************************************************************************
	*																							*
	*									BROWSE CART FUNCTIONS									*
	*																							*
	********************************************************************************************/
	
	function add_cart_item3(){ 
		//session_unset($_SESSION['order_id']);
		$product_id = $this->input->post('product_id');
		$cty = $this->input->post('quantity');
		$ajax = $this->input->post('ajax');
		
		if(isset($_SESSION['order_id'])){
			$order_id = $_SESSION['order_id'];
		}
		else{
			$items = array(
						"order_status" => 3,//cart order
						"order_method_id" => 1,
						"order_status2" => 4
					);
			
			$table = "`order`";
			$order_id = $this->administration_model->insert($table, $items);
			$_SESSION['order_id'] = $order_id;
		}
		
		$cart_contents = $this->administration_model->select_entries_where("order_item", "product_id = ".$product_id." AND order_id = ".$order_id, "*", "order_item_id");
		
		/*
			-----------------------------------------------------------------------------------------
			If the product has already been added to cart
			-----------------------------------------------------------------------------------------
		*/
		
		if(count($cart_contents) > 0){
			foreach($cart_contents as $item):
				$order_item_id = $item->order_item_id;
				$order_item_quantity = $item->order_item_quantity;
				$cty += $order_item_quantity;
			
				$items = array(
						"order_item_quantity" => $cty
					);
			
				$this->administration_model->update("order_item", $items, "order_item_id", $order_item_id);
			endforeach;
		}
		
		/*
			-----------------------------------------------------------------------------------------
			if the product hasnt been added to cart
			-----------------------------------------------------------------------------------------
		*/
		
		else{
			/*
				-----------------------------------------------------------------------------------------
				Select product name & price
				-----------------------------------------------------------------------------------------
			*/
			$products_details = $this->cart_model->select_product($product_id);
			foreach ($products_details as $prod){
				$price = $prod->product_selling_price; // Assign posted quantity to $price
			}
			$items = array(
						"order_item_quantity" => $cty,
						"order_item_price" => $price,
						"product_id" => $product_id,
						"order_id" => $order_id
			);
			$this->administration_model->insert("order_item", $items);
		}
		
		if($ajax != '1'){
			redirect("shop/browse/");
		}
		else{
			echo 'true'; // If javascript is enabled, return true, so the cart gets updated
		}
	}
	
	function get_cart_items()
	{
		$count = 0;
		$id = "";
		$total_cart_items = $this->cart->total_items();
		if($total_cart_items > 0){
		foreach($this->cart->contents() as $items):
			$id2 = $items['id'];
			$count++;
			if($count > 6){
				break;
			}
			else{
				if(($count == 1) && ($count != $total_cart_items)){
					$id = $id2.", ";
				}
				if(($count == 1) && ($count == $total_cart_items)){
					$id = $id2;
				}
				elseif(($count > 1) && ($count == $total_cart_items)){
					$id .= $id2;
				}
				else{
					$id .= $id2.", ";
				}
			}
		endforeach;
		}
		else{
			$id = "0";
		}
		
		$this->load->model('browse/order_model');
		return $this->order_model->select_entries_where("product", "product_id IN (".$id.")", "*", "product_name");
	}
	
	function get_cart()
	{
		if(isset($_SESSION['order_id'])){
			$table = "order_item";
			$where = "order_id = ".$_SESSION['order_id'];
			
			$data['total_cart_items'] = $this->administration_model->items_count($table, $where);
			$cost = $this->administration_model->select_entries_where($table, $where, "sum(order_item_quantity*order_item_price) as total", "total");
			
			if(is_array($cost)){
				foreach ($cost as $c){
					$data['total_cost'] = $c->total;
				}
			}
			$data['cart_contents'] = $this->administration_model->select_entries_where2($table.", `order`, product",
			 "order_item.order_id = order.order_id AND order_item.product_id = product.product_id AND order.order_id = ".$_SESSION['order_id'], 
			 "product_name, order_item_quantity, order_item_price, product_image_name, order_item_id, order_item.product_id", 
			 "order_item_id");
		}
		
		else{
			$data['total_cart_items'] = 0;
			$data['total_cost'] = 0;
		}
		//$data['products'] = $this->get_cart_items();
		$this->load->view("cart/items_in_cart2", $data);
	}
	
	function get_cart2()
	{
		if(isset($_SESSION['order_id'])){
			$table = "order_item";
			$where = "order_id = ".$_SESSION['order_id'];
			
			$data['total_cart_items'] = $this->administration_model->items_count($table, $where);
			$cost = $this->administration_model->select_entries_where($table, $where, "sum(order_item_quantity*order_item_price) as total", "total");
			
			if(is_array($cost)){
				foreach ($cost as $c){
					$data['total_cost'] = $c->total;
				}
			}
			$data['cart_contents'] = $this->administration_model->select_entries_where($table.", `order`, product",
			 "order_item.order_id = order.order_id AND order_item.product_id = product.product_id AND order.order_id = ".$_SESSION['order_id'], 
			 "product_name, order_item_quantity, order_item_price, product_image_name, order_item_id", 
			 "order_item_id");
		}
		
		else{
			$data['total_cart_items'] = 0;
			$data['total_cost'] = 0;
		}
		$this->load->view("cart/items_in_cart3", $data);
	}
	
	function show_cart()
	{
		if(isset($_SESSION['order_id'])){
			$table = "order_item";
			$where = "order_id = ".$_SESSION['order_id'];
			
			$data['total_cart_items'] = $this->administration_model->items_count($table, $where);
			$data['currency'] = $this->administration_model->select_entries_where("currency, `order`", "`order`.currency_id = currency.currency_ID AND order.order_id = ".$_SESSION['order_id'], "acronym, symbol", "acronym");
			$cost = $this->administration_model->select_entries_where($table, $where, "sum(order_item_quantity*order_item_price) as total", "total");
			
			if(is_array($cost)){
				foreach ($cost as $c){
					$data['total_cost'] = $c->total;
				}
			}
		}
		
		else{
			$data['total_cart_items'] = 0;
			$data['total_cost'] = 0;
			$data['currency'] = $this->administration_model->select_entries_where("currency", "currency_ID = 1", "acronym, symbol", "acronym");
		}
		$this->load->view("cart/items_in_cart", $data);
	}
	
	function update_cart3()
	{
		// Retrieve the posted information
		$order_item = $this->input->post('order_item');
	    $qty = $this->input->post('quantity');
		
		$items = array(
					"order_item_quantity" => $qty
				);
			
		$this->administration_model->update("order_item", $items, "order_item_id", $order_item);
		
		$ajax = $this->input->post('ajax');
		
		if($ajax != '1'){
			redirect("shop/browse/view_cart/");
		}
		else{
			echo 'true';// If javascript is enabled, return true, so the cart gets updated
		}
	}
	
	function delete_item3($order_item_id){
		$this->administration_model->delete("order_item", "order_item_id", $order_item_id);
		echo 'true';
	}
	
	function delete_item4($row_id){
		$this->cart_model->delete_item($row_id);
		echo $this->order_cart();
	}
	
	function empty_cart3($order_id){
		$this->cart->destroy();
		header('Location: '.site_url("browse/open_products/"));
	}
	
	function checkout3($customer_id){
		//create 'order'
		/*if($this->session->userdata('login_state') == FALSE ) {
			echo "false";//redirect( "/login/login_user/1");
    	}
		else{
			echo "true";
		}*/
		$items = array(
					"customer_id" => $customer_id,
					"order_method_id" => 3,
					"order_type" => 3
		);
		$order_id = $this->administration_model->insert("order", $items);
		$this->cart_model->checkout($order_id);
		$this->cart->destroy();//empty the cart
		redirect("/browse/");
	}
}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */