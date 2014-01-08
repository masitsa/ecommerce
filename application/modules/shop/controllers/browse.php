<?php session_start();

class Browse extends MX_Controller {   

	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
		$this->load->model('order_model');
		$this->load->model('cart_model');
		$this->load->model('admin/products_model');
		$this->load->model('admin/admin_model');
		$this->load->model('administration_model'); 
		$this->load->library('paypal');
		date_default_timezone_set('Africa/Nairobi'); 
    }
	/*
		-----------------------------------------------------------------------------------------
		Default controller action is to list all products
		-----------------------------------------------------------------------------------------
	*/
	function empty_cart()
	{
		$this->cart->destroy();
	}
	function index()
	{	//session_unset();
		$_SESSION['search'] = NULL;
		$_SESSION['category'] = NULL;
		$_SESSION['category2'] = NULL;
		
		redirect("shop/browse/home");
	}
	function test()
	{
		$_SESSION['crumb1'] = "All Products";
		$data['current_category_id'] = $_SESSION['category_id'];
		$data['categories'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = 0)", "*", "category_id, category_name");
		$data['category_children'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0)", "*", "category_parent, category_name");
		
		$this->head();
		$this->load->view("includes/test", $data);
		$this->foot();
	}
	function test2()
	{
		$this->load->view("includes/test2");
	}
	/*
		-----------------------------------------------------------------------------------------
		Load the page navigation
		-----------------------------------------------------------------------------------------
	*/
	function head()
	{
		//$data['cart_products'] = $this->get_cart_items();
		
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
			$data['cart_contents'] = $this->administration_model->select_entries_where("order_item, `order`, product", "order_item.order_id = order.order_id AND order_item.product_id = product.product_id AND order.order_id = ".$_SESSION['order_id'], "product_name, order_item_quantity, order_item_price, product_image_name, order_item_id, order_item.product_id", "order_item_id");
			
			$data['currency'] = $this->administration_model->select_entries_where("currency, `order`", "`order`.currency_id = currency.currency_ID AND order.order_id = ".$_SESSION['order_id'], "acronym, symbol", "acronym");
		}
		
		else{
			$data['total_cart_items'] = 0;
			$data['total_cost'] = 0;
			$data['cart_contents'] = "";
			$data['currency'] = $this->administration_model->select_entries_where("currency", "currency_ID = 1", "acronym, symbol", "acronym");
		}
		$data2['categories'] = $this->order_model->select_entries_where("category", "category_status = 1", "category_name, category_id", "category_name");
			
		$data3['currencies'] = $this->administration_model->select_entries_where("currency", "currency_ID > 0", "*", "currency");
		
		if(isset($_SESSION['category_id'])){
			$this->sort_crumbs($_SESSION['category_id']);
		}
		
		$this->load->view("includes/head", $data3);
		$this->load->view("includes/product_search", $data2);
		$this->load->view("includes/header", $data);
	}
	
	function sort_crumbs($category_id)
	{
	}
	
	function sort_crumbs2($category_id)
	{
		$this->sort_crumbs($category_id);
		$this->load->view("includes/crumbs");
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
		
		return $this->order_model->select_entries_where("product", "product_id IN (".$id.")", "*", "product_name");
	}
	
	function pull_left()
	{
		$data['products'] = $this->get_cart_items();
		$data['current_category_id'] = $_SESSION['category_id'];
		$data['categories'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = 0)", "*", "category_id, category_name");
		$data['category_children'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0)", "*", "category_parent, category_name");
		$data['brands'] = $this->order_model->select_entries_where("brand", "brand_status = 1 AND category_id = ".$_SESSION['category_id'], "*", "brand_name");
		
		$this->load->view("includes/pull_left", $data);
	}
	
	function pull_left_home()
	{
		$data['products'] = $this->get_cart_items();
		$data['current_category_id'] = $_SESSION['category_id'];
		$data['categories'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = 0)", "*", "category_id, category_name");
		$data['category_children'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0)", "*", "category_parent, category_name");
		//$data['brands'] = "";
		$this->load->view("includes/home_left", $data);
	}
	
	function get_children($category_id){
		$poster = $this->order_model->select_entries_where("category", "(category_id = ".$category_id.")", "category_image_name", "category_image_name");
		if(count($poster) > 0){
			foreach($poster as $pos)
			{
				$ster = $pos->category_image_name;
			}
		}
		else{
			$ster = "";
		}
		$data['poster'] = $ster;
		$data['category_id'] = $category_id;
		$data['categories'] = $this->order_model->select_entries_where("category", "(category_id = ".$category_id.") AND (category_parent = 0)", "*", "category_id, category_name");
		$data['category_children'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0)", "*", "category_parent, category_name");
		$this->load->view("includes/pull_left2", $data);
	}
	
	function foot()
	{
		$this->load->view("includes/footer");
	}
	
	function echostatus()
	{
		$user = $this->session->userdata('user');
		if( $user['logged_in']== FALSE ) {
			echo "false";//redirect( "/login/login_user/1");
    	}
		else{
			echo "true";
		}
	}
	
	function order_products($category_id, $sorting)
	{
		$_SESSION['order'] = $sorting;
		
		if(($sorting == "product_selling_price2") || ($sorting == "product_date") || ($sorting == "product_rating")){
			$_SESSION['order_type'] = "DESC";
		}
		
		else{
			$_SESSION['order_type'] = "ASC";
		}
		if($_SESSION['order'] == "product_selling_price2"){
			$_SESSION['order'] = "product_selling_price";
		}
		echo "true";
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Create a query to retrieve selected brands
		-----------------------------------------------------------------------------------------
	*/
	function set_brands()
	{//$_SESSION['brand_id'] = NULL;
		if(isset($_SESSION['brand_id'])){
			$total_brands = count($_SESSION['brand_id']);
			
			if($total_brands > 0){
			
				$brand = "AND (";
			
				for($r = 0; $r < $total_brands; $r++){
					
					if(!empty($_SESSION['brand_id'][$r])){
						if($r == 0){
							$brand .= "product.brand_id = ".$_SESSION['brand_id'][$r]."";
						}
						else{
							$brand .= " OR product.brand_id = ".$_SESSION['brand_id'][$r]."";
						}
					}
				}
				$brand .= ")";
			}
		}
		else{
			
			$brand = "";
		}
		
		return $brand;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Create a query to order the products
		-----------------------------------------------------------------------------------------
	*/
	function set_order()
	{
		//$_SESSION['order'] = NULL;
		if(isset($_SESSION['order'])){
			$order['order'] = $_SESSION['order'];
			$order['order_type'] = $_SESSION['order_type'];
		}
		else{
			$order['order'] = "product_name";
			$order['order_type'] = "ASC";
		}
		
		return $order;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Create a query to retrieve the products of a particular category
		-----------------------------------------------------------------------------------------
	*/
	function set_category($category)
	{
		if($category == 0){
			$category = "";
		}
			
		else{
			$category_children = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = ".$category.")", "category_id", "category_id");
			
			$first_child = "(";
			if(is_array($category_children)){
				
				$count = 0;
				$total_children = count($category_children);
				
				foreach($category_children as $child){
					$count++;
					if($total_children == $count){
						$first_child .= $child->category_id;
					}
					else{
						$first_child .= $child->category_id.", ";
					}
				}
			}
			$first_child .= ")";
			
			if($first_child == "()"){
				$category = " AND ((category.category_id = ".$category.") OR (category.category_parent = ".$category."))";
			}
			
			else{
				$category = " AND ((category.category_id = ".$category.") OR (category.category_parent = ".$category.") OR (category.category_parent IN ".$first_child."))";
			}
		}
		
		return $category;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Create pagination data
		-----------------------------------------------------------------------------------------
	*/
	function set_search_pagination($category, $category2, $brand, $search)
	{
		$config['base_url'] = site_url().'shop/browse/open_products/'.$category2;
		$config['total_rows'] = $this->order_model->search_products_count2($category, $brand, $search);
		$config['per_page'] = 1;
		$config['uri_segment'] = 5;
		$config['anchor_class'] = ' class="ajax_pagination" ';
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination CSS
			-----------------------------------------------------------------------------------------
		*/
		$config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
		$config['full_tag_close'] = '</ul></div>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="icon-double-angle-right"></i>';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="icon-double-angle-left"></i>';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a class="ajax_pagination" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		return $config;
	}
	
	function set_pagination($category, $category2, $brand)
	{
		$config['base_url'] = site_url().'shop/browse/open_products/'.$category2;
		$config['total_rows'] = $this->order_model->products_count($category, $brand);
		$config['per_page'] = 1;
		$config['uri_segment'] = 5;
		$config['anchor_class'] = ' class="ajax_pagination" ';
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination CSS
			-----------------------------------------------------------------------------------------
		*/
		$config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
		$config['full_tag_close'] = '</ul></div>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="icon-double-angle-right"></i>';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="icon-double-angle-left"></i>';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a class="ajax_pagination" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		return $config;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Create products page data
		-----------------------------------------------------------------------------------------
	*/
	function set_page_data($category, $category2, $brand, $config, $page, $category, $order, $order_type)
	{
		$data['search'] = 0;
		$data['products'] = $this->order_model->fetch_products($config["per_page"], $page, $category, $order, $order_type, $brand);
        $data["links"] = $this->pagination->create_links();
		$data['category'] = $category2;
		$data['first'] = $page + 1;
		$data['last'] = $config["per_page"] + ($data['first']-1);
		$data['total'] = $config['total_rows'];
		
		if($data['last'] > $data['total']){
			$data['last'] = $data['total'];
		}
		
		return $data;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Create products page search data
		-----------------------------------------------------------------------------------------
	*/
	function set_page_search_data($category, $category2, $brand, $config, $page, $category, $order, $order_type, $search)
	{
		$data['search'] = 0;
		$data['products'] = $this->order_model->search_products2($config["per_page"], $page, $category, $order, $order_type, $brand, $search);
        $data["links"] = $this->pagination->create_links();
		$data['category'] = $category2;
		$data['first'] = $page + 1;
		$data['last'] = $config["per_page"] + ($data['first']-1);
		$data['total'] = $config['total_rows'];
		
		if($data['last'] > $data['total']){
			$data['last'] = $data['total'];
		}
		
		return $data;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Open the home page
		-----------------------------------------------------------------------------------------
	*/
	function home()
	{
		$_SESSION['crumb1'] = "All Products";
		$data['current_category_id'] = $_SESSION['category_id'];
		$data['categories'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = 0)", "*", "category_id, category_name");
		$data['category_children'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0)", "*", "category_parent, category_name");
		$data['reviews'] = $this->order_model->select_entries_where2("review, product", "review.product_id = product.product_id", "*", "review_date");
		$data['recommended'] = $this->order_model->select_entries_where2("product", "product_recommended = 1 AND product_status = 1", "*", "product_id");
		$data['product_offer'] = $this->order_model->select_entries_where2("product", "product_offer = 1 AND product_status = 1 AND product_offer_date >= '".date('Y-m-d')."'", "MIN(product_id) AS offer, product_id, product_name, product_image_name, category_id", "offer");
		
		$this->head();
		$this->load->view("home", $data);
		$this->foot();
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Open the products page
		-----------------------------------------------------------------------------------------
	*/
	function open_products($category)
	{
		$_SESSION['category_id'] = $category;
	
		/*
			-----------------------------------------------------------------------------------------
			The requested brands
			-----------------------------------------------------------------------------------------
		*/
		$brand = $this->set_brands();
	
		/*
			-----------------------------------------------------------------------------------------
			Order the products
			-----------------------------------------------------------------------------------------
		*/
		$order2 = $this->set_order();
		$order = $order2['order'];
		$order_type = $order2['order_type'];
	
		/*
			-----------------------------------------------------------------------------------------
			Retrieve products of a particular category
			-----------------------------------------------------------------------------------------
		*/
		$category2 = $category;
		$category = $this->set_category($category);
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination data
			-----------------------------------------------------------------------------------------
		*/
		$config = $this->set_pagination($category, $category2, $brand);
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		//echo $page;
		
		/*
			-----------------------------------------------------------------------------------------
			Required variables to be passed to the interface
			-----------------------------------------------------------------------------------------
		*/
		$data2 = $this->set_page_data($category, $category2, $brand, $config, $page, $category, $order, $order_type);
		$data['search'] = $data2['search'];
		$data['products'] = $data2['products'];
        $data["links"] = $data2["links"];
		$data['category'] = $data2['category'];
		$data['first'] = $data2['first'];
		$data['last'] = $data2['last'];
		$data['total'] = $data2['total'];
		$data['page'] = $page;
		$_SESSION['crumb1'] = "All Products";
		$data['current_category_id'] = $_SESSION['category_id'];
		$data['categories'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = 0)", "*", "category_id, category_name");
		$data['category_children'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0)", "*", "category_parent, category_name");
		
		if(isset($_SESSION['ajax'])){
			echo "true";
		}
		
		else{
		
			$this->head();
			$this->load->view("products_list", $data);
			$this->pull_left();
			$this->foot();
		}
	}
	
	public function search_product()
	{
		$data['search'] = 1;
		$data['search_data'] = $this->input->post('search_data');
		$data['search_data2'] = $this->input->post('search_data2');
		$category2 = $this->input->post('category_id_session');
		
		if(!empty($data['search_data'])){
			$search = $data['search_data'];
			$category = $data['search_data2'];
		}
		else{
			$search = $this->input->post('search');
			$category = $this->input->post('category_id');
			$category2 = $category;
			
			if($category == 0){
				$category = "";
			}
			
			else{
				$category = " AND (category.category_id = ".$category.")";
			}
			
			$data['search_data'] = $search;
			$data['search_data2'] = $category;
		}
		$_SESSION['category_id'] = $category2;
	
		/*
			-----------------------------------------------------------------------------------------
			The requested brands
			-----------------------------------------------------------------------------------------
		*/
		$brand = $this->set_brands();
	
		/*
			-----------------------------------------------------------------------------------------
			Order the products
			-----------------------------------------------------------------------------------------
		*/
		$order2 = $this->set_order();
		$order = $order2['order'];
		$order_type = $order2['order_type'];
	
		/*
			-----------------------------------------------------------------------------------------
			Retrieve products of a particular category
			-----------------------------------------------------------------------------------------
		*/
		$category = $this->set_category($category);
		$_SESSION['search'] = $search;
		$_SESSION['category'] = $category;
		$_SESSION['category2'] = $category2;
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination data
			-----------------------------------------------------------------------------------------
		*/
		$config = $this->set_search_pagination($category, $category2, $brand, $search);
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		//echo $page;
		
		/*
			-----------------------------------------------------------------------------------------
			Required variables to be passed to the interface
			-----------------------------------------------------------------------------------------
		*/
		$data2 = $this->set_page_search_data($category, $category2, $brand, $config, $page, $category, $order, $order_type, $search);
		$data['search'] = $data2['search'];
		$data['products'] = $data2['products'];
        $data["links"] = $data2["links"];
		$data['category'] = $data2['category'];
		$data['first'] = $data2['first'];
		$data['last'] = $data2['last'];
		$data['total'] = $data2['total'];
		$data['page'] = $page;
		$data['current_category_id'] = $_SESSION['category_id'];
		$data['categories'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent = 0)", "*", "category_id, category_name");
		$data['category_children'] = $this->order_model->select_entries_where("category", "(category_status = 1) AND (category_parent > 0)", "*", "category_parent, category_name");
		
        /*$data["products"] = $this->order_model->search_products2($config["per_page"], $page, $search, $category);
        $data["links"] = $this->pagination->create_links();*/
		
		$this->head();
		$this->load->view("products_list", $data);
		$this->pull_left();
		$this->foot();
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Filter the products by brand
		-----------------------------------------------------------------------------------------
	*/
	function filter_brand($brand_id)
	{
		$total_brands = count($_SESSION['brand_id']);
		$total_brands++;
		if($total_brands > 0){
			$total_brands++;
		}
		
		$_SESSION['brand_id'][$total_brands] = $brand_id;
		$_SESSION['page'] = 1;
		$_SESSION['search'] = NULL;
		$_SESSION['category'] = NULL;
		$_SESSION['category2'] = NULL;
		echo "true";
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Remove the brand filter
		-----------------------------------------------------------------------------------------
	*/
	function remove_brand($category_id)
	{
		$_SESSION['brand_id'] = NULL;
		$_SESSION['page'] = 1;
		$_SESSION['search'] = NULL;
		$_SESSION['category'] = NULL;
		$_SESSION['category2'] = NULL;
		redirect("shop/browse/open_products/".$category_id);
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Remove the brand filter
		-----------------------------------------------------------------------------------------
	*/
	function remove_brand2()
	{
		$_SESSION['brand_id'] = NULL;
		$_SESSION['page'] = 1;
		$_SESSION['search'] = NULL;
		$_SESSION['category'] = NULL;
		$_SESSION['category2'] = NULL;
		echo "true";
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Set the current page
		-----------------------------------------------------------------------------------------
	*/
	function order_pages($page)
	{
		$_SESSION['page'] = $page;
		echo 'true';
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Unset the current page
		-----------------------------------------------------------------------------------------
	*/
	function remove_pages()
	{
		$_SESSION['page'] = 1;
		echo 'true';
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Sort products via ajax
		-----------------------------------------------------------------------------------------
	*/
	function sort_products($category, $page)
	{
		$_SESSION['category_id'] = $category;
		/*
			-----------------------------------------------------------------------------------------
			The requested brands
			-----------------------------------------------------------------------------------------
		*/
		$brand = $this->set_brands();
	
		/*
			-----------------------------------------------------------------------------------------
			Order the products
			-----------------------------------------------------------------------------------------
		*/
		$order2 = $this->set_order();
		$order = $order2['order'];
		$order_type = $order2['order_type'];
	
		/*
			-----------------------------------------------------------------------------------------
			Retrieve products of a particular category
			-----------------------------------------------------------------------------------------
		*/
		if(!isset($_SESSION['search']) && !isset($_SESSION['category2'])){
			$category2 = $category;
			$category = $this->set_category($category);
		}
		
		else{
			$category2 = $_SESSION['category2'];
			$category = $_SESSION['category'];
		}
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination data
			-----------------------------------------------------------------------------------------
		*/
		if(!isset($_SESSION['search']) && !isset($_SESSION['category2'])){
			$config = $this->set_pagination($category, $category2, $brand);
		}
		
		else{
			$config = $this->set_search_pagination($category, $category2, $brand, $_SESSION['search']);
		}
		$this->pagination->initialize($config);
		
		/*
			-----------------------------------------------------------------------------------------
			Required variables to be passed to the interface
			-----------------------------------------------------------------------------------------
		*/
		if(!isset($_SESSION['search']) && !isset($_SESSION['category2'])){
			$data2 = $this->set_page_data($category, $category2, $brand, $config, $page, $category, $order, $order_type);
		}
		
		else{
			$data2 = $this->set_page_search_data($category, $category2, $brand, $config, $page, $category, $order, $order_type, $_SESSION['search']);
		}
		$data['products'] = $data2['products'];
		
		$this->load->view("products", $data);
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Create pagination for the sorted products via ajax
		-----------------------------------------------------------------------------------------
	*/
	function new_pagination($category, $page)
	{
		/*
			-----------------------------------------------------------------------------------------
			The requested brands
			-----------------------------------------------------------------------------------------
		*/
		$brand = $this->set_brands();
	
		/*
			-----------------------------------------------------------------------------------------
			Order the products
			-----------------------------------------------------------------------------------------
		*/
		$order2 = $this->set_order();
		$order = $order2['order'];
		$order_type = $order2['order_type'];
	
		/*
			-----------------------------------------------------------------------------------------
			Retrieve products of a particular category
			-----------------------------------------------------------------------------------------
		*/
		if(!isset($_SESSION['search']) && !isset($_SESSION['category2'])){
			$category2 = $category;
			$category = $this->set_category($category);
		}
		
		else{
			$category2 = $_SESSION['category2'];
			$category = $_SESSION['category'];
		}
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination data
			-----------------------------------------------------------------------------------------
		*/
		if(!isset($_SESSION['search']) && !isset($_SESSION['category2'])){
			$config = $this->set_pagination($category, $category2, $brand);
		}
		
		else{
			$config = $this->set_search_pagination($category, $category2, $brand, $_SESSION['search']);
		}
		$this->pagination->initialize($config);
		
		/*
			-----------------------------------------------------------------------------------------
			Required variables to be passed to the interface
			-----------------------------------------------------------------------------------------
		*/
		if(!isset($_SESSION['search']) && !isset($_SESSION['category2'])){
			$data2 = $this->set_page_data($category, $category2, $brand, $config, $page, $category, $order, $order_type);
		}
		
		else{
			$data2 = $this->set_page_search_data($category, $category2, $brand, $config, $page, $category, $order, $order_type, $_SESSION['search']);
		}
        $data["links"] = $data2["links"];
		
		$this->load->view("pagination", $data);
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Show selected products statistics via ajax
		-----------------------------------------------------------------------------------------
	*/
	function products_count($category, $page)
	{
		/*
			-----------------------------------------------------------------------------------------
			The requested brands
			-----------------------------------------------------------------------------------------
		*/
		$brand = $this->set_brands();
	
		/*
			-----------------------------------------------------------------------------------------
			Order the products
			-----------------------------------------------------------------------------------------
		*/
		$order2 = $this->set_order();
		$order = $order2['order'];
		$order_type = $order2['order_type'];
	
		/*
			-----------------------------------------------------------------------------------------
			Retrieve products of a particular category
			-----------------------------------------------------------------------------------------
		*/
		$category2 = $category;
		$category = $this->set_category($category);
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination data
			-----------------------------------------------------------------------------------------
		*/
		$config = $this->set_pagination($category, $category2, $brand);
		$this->pagination->initialize($config);
		
		//$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		
		/*
			-----------------------------------------------------------------------------------------
			Required variables to be passed to the interface
			-----------------------------------------------------------------------------------------
		*/
		$data2 = $this->set_page_data($category, $category2, $brand, $config, $page, $category, $order, $order_type);
		$data['first'] = $data2['first'];
		$data['last'] = $data2['last'];
		$data['total'] = $data2['total'];
		
		$this->load->view("products_count", $data);
	}
	
	public function view_image($product_id)
	{
		$data['products'] = $this->select_order($product_id);//select products
		$this->load->view("view_image", $data);//send the selected products to be displayed in the product_list view
	}
	
	function view_product($product_id)
	{
		$data['related_products'] = $this->order_model->fetch_related_products($product_id);//select single product
		$data['products'] = $this->order_model->select_product($product_id);//select single product
		$data['product_features'] = $this->order_model->select_product_features_by_product($product_id);//select product features by product
		$data['reviews'] = $this->order_model->select_entries_where2("review", "product_id = ".$product_id, "*", "review_date");
		$data['product_images'] = $this->order_model->select_entries_where2("product_image", "product_id = ".$product_id, "*", "product_id");
		
		$total_rating = 0;
		$count = count($data['reviews']);
		
		if($count > 0){
			
			foreach($data['reviews'] as $rev){
				
				$total_rating += $rev->review_rating;
			}
		}
		else{
			$count = 1;
		}
		$data['review_average'] = $total_rating/$count;
		$items = array(
			"product_rating" => $data['review_average']
		);
		$this->order_model->update("product", $items, "product_id", $product_id);
		
		$this->load->view("view_product", $data);
	}
	
	function view_cart()
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
			 "discount_coupon, gift_coupon, product_name, order_item_quantity, order_item_price, product_image_name, order_item_id, order_item.product_id", 
			 "order_item_id");
		}
		
		else{
			$data['total_cart_items'] = 0;
			$data['total_cost'] = 0;
		}
		$this->load->view("view_cart", $data);
		$this->load->view("checkout_option");
	}
	
	function checkout()
	{
		$this->session->sess_destroy();
		/*
			-----------------------------------------------------------------------------------------
			Authenticate the user
			-----------------------------------------------------------------------------------------
		*/
		if($this->session->userdata('login_state') == FALSE ) {
			$data['countries'] = $this->administration_model->select("country");
			$data['error'] = "";
			
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
			 	"product_name, order_item_quantity, order_item_price, product_image_name, order_item_id, order_item.product_id", 
			 	"order_item_id");
			}
		
			else{
				$data['total_cart_items'] = 0;
				$data['total_cost'] = 0;
			}
			$this->load->view("login", $data);
    	}
		else{
		
			$items = array(
					"customer_id" => $this->session->userdata('user_id'),
					"order_type" => 3
				);
			$this->administration_model->update("`order`", $items, "order_id", $_SESSION['order_id']);
			redirect("shop/browse/payment");
			session_unset($_SESSION['order_id']);
			echo "true";
		}
	}
	
	public function register_account()
	{
		$this->admin_model->create_user2();
		
		echo "true";
	}
	
	public function payment()
	{
		if(isset($_SESSION['order_id'])){
			$table = "order_item";
			$where = "order_id = ".$_SESSION['order_id'];
			$cart_contents = $this->administration_model->select_entries_where($table.", `order`, product, currency",
				 "order.currency_id = currency.currency_ID AND order_item.order_id = order.order_id AND order_item.product_id = product.product_id AND order.order_id = ".$_SESSION['order_id'], 
					"currency.acronym, product_name, order_item_quantity, order_item_price, product_image_name, order_item_id, order_item.product_id", 
					"order_item_id");
			
			if(count($cart_contents) > 0){
				$total = 0;
				$package_name = "";
				
				foreach($cart_contents as $items){
					$id = $items->order_item_id;
					$quantity = $items->order_item_quantity;
					$price = $items->order_item_price;
					$product = $items->product_name;
					$product_image_name = $items->product_image_name;
					$product_id = $items->product_id;
					$discount_coupon = $items->discount_coupon;
					$gift_coupon = $items->gift_coupon;
					$currency_acronym = $items->acronym;
					$total2 = $quantity * $price;
					$total += $total2;
					$package_name .= $product.", ";
				}
				$price = $total;
				$this->paypal->doExpressCheckout($price, $package_name ,'', $currency_acronym);
			}
			
			else{
				$this->index();
			}
		}
		
		else{
			$this->index();
		}
	}
	
	function verify_login()
	{
		/*
			-----------------------------------------------------------------------------------------
			Validate the input 
			-----------------------------------------------------------------------------------------
		*/
		$this->form_validation->set_rules('email', 'Email', 'required');//First Name is required
		$this->form_validation->set_rules('pwd', 'Password', 'required');//Last Name is required

		if ($this->form_validation->run() == FALSE)//if there is an invalid item
		{
			/*
				-----------------------------------------------------------------------------------------
				Return the user to the login form to fix the invalid input
				-----------------------------------------------------------------------------------------
			*/
			echo "<div class='alert alert-danger'>".validation_errors()."</div>";
		}
		
		else//if the input is valid
		{
			/*
				-----------------------------------------------------------------------------------------
				Retrieve login details from input form
				-----------------------------------------------------------------------------------------
			*/
			$email = $this->input->post("email");
			$password = hash('sha512', $this->input->post("pwd"));
		
			/*
				-----------------------------------------------------------------------------------------
				Check to see if the user is registered
				-----------------------------------------------------------------------------------------
			*/
			$table = "customer";
        	$where = "(customer_status = 1) AND (customer_type = 1) AND (customer_email = '".$email."') AND (customer_password = '".$password."')";
        	$items = "*";
        	$order = "customer_name";
			
			$result = $this->administration_model->select_entries_where($table, $where, $items, $order);
		
			/*
				-----------------------------------------------------------------------------------------
				If customer exists, forward to requested destination page
				-----------------------------------------------------------------------------------------
			*/
			if(count($result) > 0){
				/*
					-----------------------------------------------------------------------------------------
					Retrieve the customer's details
					-----------------------------------------------------------------------------------------
				*/
				foreach ($result as $res){
					$first_name = $res->customer_name;
					$user_id = $res->customer_id;
				}
			
				/*
					-----------------------------------------------------------------------------------------
					Create the user's session
					-----------------------------------------------------------------------------------------
				*/
				$newdata = array(
					'user_id'  => $user_id,
					'user_type'  => 1,
					'user'  => $first_name,
					'login_state' => TRUE
				);
				$this->session->set_userdata($newdata);
				
				echo "true";
			}
		
			/*
				-----------------------------------------------------------------------------------------
				Otherwise ask them to login again
				-----------------------------------------------------------------------------------------
			*/
			else{
				echo "<div class='alert alert-danger'>Incorrect details. Please try again</div>";
			}
		}
	}
	
	function show_customer()
	{
		echo $this->session->userdata('user');
	}
	
	function add_review(){
		
		// Retrieve the posted information
		$product_id = $this->input->post('product_id');
	    $rating = $this->input->post('rating');
		$email = $this->input->post('email');
	    $review = $this->input->post('review');
		$reviewer = $this->input->post('reviewer');
		
		$items = array(
			"product_id" => $product_id,
	    	"review_rating" => $rating,
	    	"review_date" => date("Y-m-d"),
			"review_reviewer_email" => $email,
	    	"review_name" => $review,
			"review_reviewer" => $reviewer
		);
		
		$this->order_model->insert("review", $items);
		$ajax = $this->input->post('ajax');
		
		if($ajax != '1'){
			header('Location: '.site_url("browse/view_product/".$product_id));
		}
		else{
			echo 'true';// If javascript is enabled, return true, so the cart gets updated
		}
	}
	
	function get_reviews($product_id)
	{
		$data['reviews'] = $this->order_model->select_entries_where2("review", "product_id = ".$product_id, "*", "review_date");
		$data['products'] = $this->order_model->select_product($product_id);//select single product
		
		$total_rating = 0;
		$count = count($data['reviews']);
		
		if($count > 0){
			
			foreach($data['reviews'] as $rev){
				
				$total_rating += $rev->review_rating;
			}
		}
		else{
			$count = 1;
		}
		$data['review_average'] = $total_rating/$count;
		
		$this->load->view("get_reviews", $data);
	}
	
	function get_category_image(){
		
		// Retrieve the posted information
		$category_id = $this->input->post('category_id');
		$result = $this->order_model->select_entries_where2("category", "category_id = ".$category_id, "category_image_name", "category_image_name");
		
		if(is_array($result)){
			foreach($result as $res){
				$image = $res->category_image_name;
			}
		}
		echo $image;// If javascript is enabled, return true, so the cart gets updated
	}
	
	function select_brands($category_id){
		$data['brands'] = $this->order_model->select_entries_where("brand", "brand_status = 1 AND category_id = ".$category_id, "*", "brand_name");
		$data['current_category_id'] = $_SESSION['category_id'];
		$this->load->view("includes/load_brands", $data);
	}
	
	function save_category_feature_value($category_feature_value_id, $product_id)
	{
		/*
			-----------------------------------------------------------------------------------------
			Check if the product has already been added to cart
			-----------------------------------------------------------------------------------------
		*/
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
						"order_item_quantity" => 1,
						"order_item_price" => $price,
						"product_id" => $product_id,
						"order_id" => $order_id
			);
			$order_item_id = $this->administration_model->insert("order_item", $items);
		}
		
		/*
			-----------------------------------------------------------------------------------------
			Delete any previously added features for the particular product
			-----------------------------------------------------------------------------------------
		*/
		$table = "category_feature_value";
		$where = "category_feature_value.category_feature_value_id = ".$category_feature_value_id;
		$items = "category_feature_id";
		$order = "category_feature_id";
		
		$features = $this->administration_model->select_entries_where($table, $where, $items, $order);
		foreach ($features as $prod){
			$category_feature_id = $prod->category_feature_id;
		}
		
		$table = "category_feature_value";
		$where = "category_feature_id = ".$category_feature_id;
		$items = "category_feature_value_id";
		$order = "category_feature_value_id";
		
		$features = $this->administration_model->select_entries_where($table, $where, $items, $order);
		$values = "(";
		$count = 1;
		foreach ($features as $prod){
			if($count == count($features)){
				$values .= $prod->category_feature_value_id;
			}
			else{
				$values .= $prod->category_feature_value_id.", ";
			}
			$count++;
		}
		$values .= ")";
		
		$where = "order_item_id = ".$order_item_id." AND category_feature_value_id IN ".$values;
		$this->administration_model->delete2("order_item_feature", $where);
		
		/*
			-----------------------------------------------------------------------------------------
			Add new feature
			-----------------------------------------------------------------------------------------
		*/
		$items = array(
					"order_item_id" => $order_item_id,
					"category_feature_value_id" => $category_feature_value_id
				);
		$order_item_id = $this->administration_model->insert("order_item_feature", $items);
		
		$data['product_id'] = $product_id;
		$this->load->view("category_feature_value", $data);
	}
	
	function ordering()
	{
		if(isset($_SESSION['order_id'])){
			$table = "order_item";
			$where = "order_id = ".$_SESSION['order_id'];
			
			$total_cart_items = $this->administration_model->items_count($table, $where);
			$cost = $this->administration_model->select_entries_where($table, $where, "sum(order_item_quantity*order_item_price) as total", "total");
			
			if(is_array($cost)){
				foreach ($cost as $c){
					$data['total_cost'] = $c->total;
				}
			}
		}
		
		else{
			$total_cart_items = 0;
			$data['total_cost'] = 0;
		}
		echo '<h3 class="title" style="margin-bottom:0px;"><i class="icon-shopping-cart"></i> Items in your cart [<span class="color">'.$total_cart_items.'</span>]</h3>';
	}
	
	function get_ordering($category, $page)
	{
		/*
			-----------------------------------------------------------------------------------------
			The requested brands
			-----------------------------------------------------------------------------------------
		*/
		$brand = $this->set_brands();
	
		/*
			-----------------------------------------------------------------------------------------
			Order the products
			-----------------------------------------------------------------------------------------
		*/
		$order2 = $this->set_order();
		$order = $order2['order'];
		$order_type = $order2['order_type'];
	
		/*
			-----------------------------------------------------------------------------------------
			Retrieve products of a particular category
			-----------------------------------------------------------------------------------------
		*/
		$category2 = $category;
		$category = $this->set_category($category);
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination data
			-----------------------------------------------------------------------------------------
		*/
		$config = $this->set_pagination($category, $category2, $brand);
		$this->pagination->initialize($config);
		
		//$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		
		/*
			-----------------------------------------------------------------------------------------
			Required variables to be passed to the interface
			-----------------------------------------------------------------------------------------
		*/
		$data2 = $this->set_page_data($category, $category2, $brand, $config, $page, $category, $order, $order_type);
		
		$data['last'] = $data2['last'];
		
		$this->load->view("ordering", $data);
	}
	
	function get_top($category, $page)
	{
		/*
			-----------------------------------------------------------------------------------------
			The requested brands
			-----------------------------------------------------------------------------------------
		*/
		$brand = $this->set_brands();
	
		/*
			-----------------------------------------------------------------------------------------
			Order the products
			-----------------------------------------------------------------------------------------
		*/
		$order2 = $this->set_order();
		$order = $order2['order'];
		$order_type = $order2['order_type'];
	
		/*
			-----------------------------------------------------------------------------------------
			Retrieve products of a particular category
			-----------------------------------------------------------------------------------------
		*/
		$category2 = $category;
		$category = $this->set_category($category);
		
		/*
			-----------------------------------------------------------------------------------------
			Pagination data
			-----------------------------------------------------------------------------------------
		*/
		$config = $this->set_pagination($category, $category2, $brand);
		$this->pagination->initialize($config);
		
		//$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		
		/*
			-----------------------------------------------------------------------------------------
			Required variables to be passed to the interface
			-----------------------------------------------------------------------------------------
		*/
		$data2 = $this->set_page_data($category, $category2, $brand, $config, $page, $category, $order, $order_type);
		
		$data['first'] = $data2['first'];
		$data['last'] = $data2['last'];
		$data['total'] = $data2['total'];
		
		$this->load->view("top", $data);
	}
	
	function save_discount($discount)
	{
		$items = array(
					"discount_coupon" => $discount
				);
		$this->administration_model->update("`order`", $items, "order_id", $_SESSION['order_id']);
		
		echo "true";
	}
	
	function save_gift($gift)
	{
		$items = array(
					"gift_coupon" => $gift
				);
		$this->administration_model->update("`order`", $items, "order_id", $_SESSION['order_id']);
		
		echo "true";
	}
	
	function save_currency($currency_id)
	{
		if(isset($_SESSION['order_id'])){
			$items = array(
						"currency_id" => $currency_id
					);
			$this->administration_model->update("`order`", $items, "order_id", $_SESSION['order_id']);
		}
		
		else{
			$items = array(
				"currency_id" => $currency_id
			);
		
			$order_id = $this->order_model->insert("currency", $items);
			$_SESSION['order_id'] = $order_id;
		}
		
		echo "true";
	}
}