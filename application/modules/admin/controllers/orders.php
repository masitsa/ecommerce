<?php session_start();
require_once "./application/modules/admin/controllers/admin.php";
class Orders extends Admin
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model('orders_model');
		$this->load->model('products_model');
	}
	
	function test()
	{
		$this->load->view("orders/test");
	}
	
	function order_list()
	{
		redirect("admin/orders/index/0");
	}
	
	function custom_orders()
	{
		redirect("admin/orders/index/1");
	}
	
	function new_order()
	{
		redirect("admin/orders/add_order/0");
	}
	
	function new_custom_order()
	{
		redirect("admin/orders/add_order/1");
	}
	
	/**
	 * Default action for the module
	 * View all orders placed on the system
	 *
	 */
	function index($order_type)
	{
		// Set the template to use for this page
		
		$data['search'] = 0;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/orders/index/'.$order_type;
		$config['total_rows'] = $this->db->count_all('`order`');
		$config['uri_segment'] = 5;
		$config['per_page'] = 10;
		$config['num_links'] = 5;
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
        $data["links"] = $this->pagination->create_links();
		
		$this->load->library('table');
		$tmpl = array (
			'table_open'          => '<table style="width:100%" class="table table-striped table-hover">',
			'heading_row_start'   => '<tr>',
			'heading_row_end'     => '</tr>',
			'heading_cell_start'  => '<th scope="col">',
			'heading_cell_end'    => '</th>',
			'row_start'           => '<tr>',
			'row_end'             => '</tr>',
			'cell_start'          => '<td>',
			'cell_end'            => '</td>',
			'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);
		$axns = array('data'=>'Actions','colspan'=>4);
		$this->table->set_heading('Placed By','Placed On','Status','Comments',$axns);
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		$data['page'] = $page;
		$data['order_type'] = $order_type;
		
		if($orders = $this->orders_model->select_orders($config["per_page"], $page, $order_type)) {
			
			foreach($orders as $order) {
				$this->table->add_row($order->first_name.' '.$order->last_name,date('jS M Y H:i a',strtotime($order->order_date)),$order->status_name,
					$order->order_instructions,
					anchor("admin/orders/details/$order->order_id",'Details',array('title'=>'More Info','rel'=>'facebox')),
					anchor("admin/orders/add_order_details/$order->order_id",'Edit',array('title'=>'Edit')),
					anchor("admin/orders/print_delivery_note/$order->order_id/$order_type",'Delivery Note',array('title'=>'Note')),
					anchor("admin/orders/delete_order/$order->order_id",'Delete',array('onClick'=>'return confirm(\'Do you really want to delete this order?\');','title'=>'Delete'))
				);
			}
		} else {
			$this->table->add_row('There are no orders to show here :-|');
		}
		$this->template->content->view('orders/order_list',$data);
		$this->template->publish();
	}
	
	public function search_order($order_type)
	{
		/*
			-----------------------------------------------------------------------------------------
			Retrieve customers and assign them to an array data
			-----------------------------------------------------------------------------------------
		*/
		$customers = $this->orders_model->select_customers();
		if(count($customers) > 0){
			$customer_array = "'";
			$count = 0;
			foreach ($customers as $cust){
				$count++;
				$customer_name = $cust->first_name;
				
				if($count == count($customers)){
					$customer_array .= $customer_name."'";
				}
				else{
					$customer_array .= $customer_name."', '";
				}
			}
		}
		else{
			$customer_array = NULL;
		}
		$data['customers'] = $customer_array;
		
		/*
			-----------------------------------------------------------------------------------------
			Search for the order
			-----------------------------------------------------------------------------------------
		*/
		$customer_name = $this->input->post('customer_name');
		$order_date = $this->input->post('order_date');
		$delivery_date = $this->input->post('delivery_date');
		$product = $this->input->post('product');
		$product2 = $this->input->post('product');
		
		if(empty($product)){
			$product = "= ''";
		}
		else{
			$product = "LIKE '".$product."%'";
		}
		
		if(empty($order_date)){
			$order_date = "= ''";
		}
		else{
			$order_date = "LIKE '".$order_date."%'";
		}
		// Set the template to use for this page
		
		$data['search'] = 0;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/orders/search_order/'.$order_type;
		$config['total_rows'] = count($this->orders_model->search_order($customer_name, $order_date, $delivery_date, $product, $product2, $order_type));
		$config['uri_segment'] = 5;
		$config['per_page'] = 10;
		$config['num_links'] = 5;
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
        $data["links"] = $this->pagination->create_links();
		
		$this->load->library('table');
		$tmpl = array (
			'table_open'          => '<table style="width:100%" class="table table-striped table-hover">',
			'heading_row_start'   => '<tr>',
			'heading_row_end'     => '</tr>',
			'heading_cell_start'  => '<th scope="col">',
			'heading_cell_end'    => '</th>',
			'row_start'           => '<tr>',
			'row_end'             => '</tr>',
			'cell_start'          => '<td>',
			'cell_end'            => '</td>',
			'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);
		$axns = array('data'=>'Actions','colspan'=>3);
		$this->table->set_heading('Placed By','Placed On','Status','Comments',$axns);
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		$data['page'] = $page;
		$data['order_type'] = $order_type;
		
		if($orders = $this->orders_model->search_orders($config['per_page'], $page, $customer_name, $order_date, $delivery_date, $product, $product2, $order_type)) {
			
			foreach($orders as $order) {
				$this->table->add_row($order->first_name.' '.$order->last_name,date('jS M Y H:i a',strtotime($order->order_date)),$order->status_name,
					$order->order_instructions,
					anchor("admin/orders/details/$order->order_id",'Details',array('title'=>'More Info','rel'=>'facebox')),
					//anchor("admin/orders/edit_order/$order->order_id",'Edit',array('title'=>'Edit')),
					anchor("admin/orders/print_delivery_note/$order->order_id/$order_type",'Delivery Note',array('title'=>'Note')),
					anchor("admin/orders/delete_order/$order->order_id",'Delete',array('onClick'=>'return confirm(\'Do you really want to delete this order?\');','title'=>'Delete'))
				);
			}
		} else {
			$this->table->add_row('There are no orders to show here :-|');
		}
		$this->template->content->view('orders/order_list',$data);
		$this->template->publish();
	}

	public function print_delivery_note($order_id, $order_type)
	{
		$_SESSION['base_url'] = base_url();
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."pdfs/delivery_note.php?order_id=".$order_id?>","Popup","height=500,width=1000,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				window.location.href = "<?php echo site_url()."admin/orders/index/".$order_type?>";
				
			</script>
        <?php
	}
	
	/**
	 * View the details of a specific order
	 *
	 * @param int order_id
	 *
	 */
	function details($order_id)
	{
		$data['details'] = $this->orders_model->get_order_details($order_id);
		$data['items'] = $this->orders_model->fetch_order_products($order_id);
		$this->load->view('orders/info',$data);
	}
	
	/**
	 * Add a custom order to the system
	 * This is (obviuosly) done it the back-end of
	 *
	 */
	function add_order($order_type)
	{
		// Set the template to use for this page
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('order_method_id','Order Method','trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('customer_name','Customer Name','trim|required|xss_clean');
		$this->form_validation->set_rules('delivery_date','Delivery Date','trim|required|xss_clean');
		if($this->form_validation->run()) {
			$order_id = $this->products_model->add_customer($order_type);
			
			if($order_id == 0){
				$this->template->content->view('orders/add_order_form');
				$this->template->publish();
			}
			else{
				redirect('admin/orders/add_order_details/'.$order_id);
			}
		} else {
			$this->template->content->view('orders/add_order_form');
			$this->template->publish();
		}
	}
	
	/**
	 * Add a custom order to the system
	 * This is (obviuosly) done it the back-end of
	 *
	 */
	function add_order_details($order_id)
	{
		// Set the template to use for this page
		
		$data['search'] = 0;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/products/index/'.$order_id;
		$config['total_rows'] = $this->db->count_all('product');
		$config['uri_segment'] = 5;
		$config['per_page'] = 10;
		$config['num_links'] = 5;
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
        $data["links"] = $this->pagination->create_links();
		
		$this->load->library('table');
		$tmpl = array (
			'table_open'          => '<table class="table table-striped table-hover table-responsive">',
			'heading_row_start'   => '<tr>',
			'heading_row_end'     => '</tr>',
			'heading_cell_start'  => '<th scope="col">',
			'heading_cell_end'    => '</th>',
			'row_start'           => '<tr>',
			'row_end'             => '</tr>',
			'cell_start'          => '<td>',
			'cell_end'            => '</td>',
			'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);
		$this->table->set_heading('','Code','Name','Selling Price','Stock Level');
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		$data['page'] = $page;
		$data['order_id'] = $order_id;
		
		if($products  = $this->products_model->fetch_products($config["per_page"],$page)) {
			
			foreach($products as $product) {
				$this->table->add_row(form_checkbox(array(
              		'name'        => 'product[]',
              		'class'       => 'form-control',
              		'onClick'       => 'save_order_item('.$product->product_id.')',
              		'value'       => $product->product_id
           		)), 
				$product->product_code, 
				$product->product_name, 
				$product->product_selling_price, 
				$product->product_balance,
				img(array('src'=>base_url().'products/thumbs/'.$product->product_image_name,'alt'=>$product->product_name, 'class' => 'img-responsive'))
				);
			}
		} else {
			$this->table->add_row('There are no products to show here :-|');
		}
		
		$this->template->content->view('orders/add_order_details_form',$data);
		$this->template->publish();
	}
	
	/**
	 * Edit the details of an order
	 *
	 * @param int order_id
	 *
	 */
	function edit_order($order_id)
	{
		// Set the template to use for this page
		
		$data['details'] = $this->products_model->get_product_feature_details($feature_id);
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('category_id','Category','trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('feature_name','Feature Name','trim|required|xss_clean');
		$this->form_validation->set_rules('feature_units','Feature Units','trim|required|xss_clean');
		if($this->form_validation->run()) {
			$this->products_model->update_feature($feature_id);
			redirect('admin/products/features');
		} else {
			$this->template->content->view('products/edit_feature_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	 * Completely delete an order from the system
	 * This can not be undone. Potentially dangerous.
	 * If you decide to take this path, may the force be with you young Padawan
	 *
	 * @param int order_id
	 *
	 */
	function delete_order($order_id)
	{
		$this->orders_model->delete_order($order_id);
		redirect('admin/orders/index/0');
	}
	
	function checkout($order_id)
	{
		// Set the template to use for this page
		$data['order_id'] = $order_id;
		$data['coupons'] = $this->orders_model->select_coupons();
		
		$this->form_validation->set_rules('order_instructions','Instructions','trim|xss_clean');
		$this->form_validation->set_rules('order_discount','Discount','trim|xss_clean');
		
		if($this->form_validation->run()) {
			$this->orders_model->add_order_description($order_id);
			$details = $this->orders_model->get_order_details($order_id);
			$order_type = $details->order_type;
			redirect('admin/orders/index/'.$order_type);
		} 
		else {
		
			$this->template->content->view("orders/checkout",$data);
			$this->template->publish();
		}
	}
	
	function save_customization($order_item_id)
	{
		$this->orders_model->save_customization($order_item_id);
	}
}