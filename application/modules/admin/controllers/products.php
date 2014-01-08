<?php
require_once "./application/modules/admin/controllers/admin.php";
class Products extends Admin
{
	var $gallery_path;
	var $gallery_path2;
	var $gallery_path3;
	var $gallery_path_url;
	var $gallery_path_url2;
	
	private $product_image_data = array(
		'dir' => array('original'=>'./assets/product_images/original/','thumb'=>'./assets/product_images/thumbs/'),
		'total' => 0,
		'images' => array(),
		'error' => ''
    );
	
	private $feature_values;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('products_model');
		
        $this->load->library('image_lib');
		
		$this->gallery_path = realpath(APPPATH . '../assets/products');
		$this->gallery_path2 = realpath(APPPATH . '../assets/categories');
		$this->gallery_path3 = realpath(APPPATH . '../assets/features');
	}

	/**
	 * Default action for the controller. Show a list of products
	 * 
	 */
	function index()
	{
		// Set the template to use for this page
		
		$data['search'] = 0;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/products/index';
		$config['total_rows'] = $this->db->count_all('product');
		$config['uri_segment'] = 4;
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
		$this->table->set_heading('','Code', 'Brand', 'Name','Selling Price','Buying Price','Stock Level', '% off', $axns);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['page'] = $page;
		if($products  = $this->products_model->fetch_products($config["per_page"],$page)) {
			foreach($products as $product) {
				if($product->product_status == 1)
					$status = anchor("admin/products/deactivate_product/$product->product_id/".$page,'Deactivate');
				else
					$status = anchor("admin/products/activate_product/$product->product_id/".$page,'Activate');
					
				if($product->product_recommended == 1)
					$recommend = anchor("admin/products/unrecommend_product/$product->product_id/".$page,'Unrecommend');
				else
					$recommend = anchor("admin/products/recommend_product/$product->product_id/".$page,'Recommend');
					
				if($product->product_offer == 1)
					$offer = anchor("admin/products/remove_offer/$product->product_id/".$page,'Unoffer');
				else
					$offer = anchor("admin/products/add_offer/$product->product_id/".$page,'Offer');
				
				$this->table->add_row(
					form_checkbox('product[]',$product->product_id),
					$product->product_code,
					$product->brand_name,
					$product->product_name,
					$product->product_selling_price,
					$product->product_buying_price,
					$product->product_balance,
					$product->product_offer_amount,
					$offer,
					anchor("admin/products/view_product/$product->product_id",'View',array('rel'=>'facebox')),
					anchor("admin/products/update_product/$product->product_id/".$page,'Edit'),
					anchor("admin/products/delete_product/$product->product_id/".$page,'Delete'),
					$status,
					$recommend,
					img(array('src'=>base_url().'assets/products/thumbs/'.$product->product_image_name,'alt'=>$product->product_name))
				);
			}
		} else {
			$this->table->add_row('There are no products to show here :-|');
		}
		//$this->load->view("products/product_list",$data);
		$this->template->content->view('products/product_list',$data);
		$this->template->publish();
	}
	
	/**
	 * View the details of a products
	 * 
	 * @param	int	$product_id
	 * 
	 */	
	function view_product($product_id)
	{
		// Set the template to use for this page
		//
		
		$data['details'] = $this->products_model->get_product_details($product_id);
		$data['features'] = $this->products_model->get_product_features($product_id);
		$this->load->view("products/view_product", $data);
		//$this->template->publish();
	}
	
	/**
	 * Add a new product to the system
	 * 
	 */
	function add_product()
	{
		// Set the template to use for this page
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('product_name','Product Name','trim|required|xss_clean');
		$this->form_validation->set_rules('product_selling_price','Selling Price','trim|is_numeric|required|xss_clean');
		$this->form_validation->set_rules('product_buying_price','Buying Price','trim|is_numeric|required|xss_clean');
		$this->form_validation->set_rules('product_description','Description','trim|required|xss_clean');
		$this->form_validation->set_rules('product_balance','Product Balance','trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('product_code','Product Code','trim|required|xss_clean');
		$this->form_validation->set_rules('category_id','Category','trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('brand_id','Category','trim|required|xss_clean');
		$this->form_validation->set_rules('feature[]','Feature','required|xss_clean');
		
		if($this->form_validation->run()) {
			if(is_uploaded_file($_FILES['userfile']['tmp_name']))
			{
				$gallery_path = $this->gallery_path;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$image_data = $this->do_upload($gallery_path);
			
				if($image_data == "FALSE"){
					$this->feature_values = $this->input->post('feature');
					$data['error'] = $this->upload->display_errors();
					$this->template->content->view("products/add_product_form",$data);
					$this->template->publish();
				}
				else{
					$path = $image_data['full_path'];
					$file_path = $image_data['file_path'];
					$file_name = $image_data['file_name'];
					$file_type = $image_data['file_type'];
			
					/*
						-----------------------------------------------------------------------------------------
						Resize image
						-----------------------------------------------------------------------------------------
					*/
					$create = $this->resize_image($path, $gallery_path, $file_name);
			
					/*
						-----------------------------------------------------------------------------------------
						Create thumbnail
						-----------------------------------------------------------------------------------------
					*/
					$create = $this->create_thumb($path, $gallery_path, $file_name);
				}
			}
			
			else{
				$file_name = '';
			}
			
			if($image_data == "FALSE"){
				$this->feature_values = $this->input->post('feature');
				$data['error'] = $this->upload->display_errors();
				$this->template->content->view("products/add_product_form",$data);
				$this->template->publish();
			}
			
			else{
				$product_id = $this->products_model->add_product($file_name);
			
				if($product_id > 0){
					$this->upload_gallery($product_id);
				}
				redirect('admin/products');
			}
		} else {
			//var_dump($this->input->post('feature'));
			$this->feature_values = $this->input->post('feature');
			//var_dump($this->feature_values);
			$this->template->content->view("products/add_product_form");
			$this->template->publish();
		}
	}
	
	/**
	 * Build a product code for a new product
	 * 
	 * Get the category prefix and highest product ID in that category
	 * Called when adding a new product so that it can be assigned a product code
	 *
	 * @param int category_id
	 *
	 */
	function build_new_product_code($category_id)
	{
		$new_product_code = $this->products_model->build_new_product_code($category_id);
		echo $new_product_code;
	}
	
	/**
	 * Get all the features of a category
	 * Called when adding a new product
	 *
	 * @param int category_id
	 *
	 * @return object
	 *
	 */
	function get_category_features($category_id)
	{
		//var_dump($this->feature_values);
		if($features = $this->products_model->get_category_features($category_id)) {
			foreach($features as $feature) {
				$field = array('name'=>'feature['.$feature->feature_id.']','id'=>'feature['.$feature->feature_id.']','class'=>'feature','value'=>set_value($this->feature_values[$feature->feature_id]));
				echo('<div class="control-group">');
					echo('<label  class="control-label" for="feature[]">'.$feature->feature_name.'</label>');
					echo('<div class="controls">');
						echo('<div class="input-append">');
							echo form_input($field);
							echo('<span class="add-on">'.$feature->feature_units.'</span>');
						echo('</div>');
					echo('</div>');
				echo('</div>');
			}
		} else {
			echo json_encode($features);
		}
	}
	
	/**
	 * Edit the details of a product
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function update_product($product_id, $page)
	{
		$data['details'] = $this->products_model->get_product_details($product_id);
		$data['features'] = $this->products_model->get_product_features($product_id);
		$data['product_images'] = $this->products_model->select_product_images($product_id);
		$data['page'] = $page;
		
		// Set the template to use for this page
		
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('product_name','Product Name','trim|required|xss_clean');
		$this->form_validation->set_rules('product_selling_price','Selling Price','trim|is_numeric|required|xss_clean');
		$this->form_validation->set_rules('product_buying_price','Buying Price','trim|is_numeric|required|xss_clean');
		$this->form_validation->set_rules('product_description','Description','trim|required|xss_clean');
		$this->form_validation->set_rules('product_balance','Product Balance','trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('product_code','Product Code','trim|required|xss_clean');
		$this->form_validation->set_rules('category_id','Category','trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('feature[]','Feature','xss_clean');
		
		if($this->form_validation->run()) {
			if(is_uploaded_file($_FILES['userfile']['tmp_name']))
			{
				$gallery_path = $this->gallery_path;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$image_data = $this->do_upload($gallery_path);
			
				if($image_data == "FALSE"){
					$this->feature_values = $this->input->post('feature');
					$data['error'] = $this->upload->display_errors();
					$this->template->content->view("products/add_product_form",$data);
					$this->template->publish();
				}
				else{
					$path = $image_data['full_path'];
					$file_path = $image_data['file_path'];
					$file_name = $image_data['file_name'];
					$file_type = $image_data['file_type'];
			
					/*
						-----------------------------------------------------------------------------------------
						Resize image
						-----------------------------------------------------------------------------------------
					*/
					$create = $this->resize_image($path, $gallery_path, $file_name);
			
					/*
						-----------------------------------------------------------------------------------------
						Create thumbnail
						-----------------------------------------------------------------------------------------
					*/
					$create = $this->create_thumb($path, $gallery_path, $file_name);
				}
			}
			
			else{
				$file_name = $this->input->post("image");
			}
			$this->products_model->update_product($product_id,$file_name);
			
			if($product_id > 0){
				$this->upload_gallery($product_id);
			}
			
			redirect('admin/products/index/'.$page);
		} else {
			//var_dump($this->input->post('feature'));
			$this->feature_values = $this->input->post('feature');
			$data['product_id'] = $product_id;
			//var_dump($this->feature_values);
			$this->template->content->view("products/edit_product_form",$data);
			$this->template->publish();
		}
	}
	
	/**
	 * Activate a specific product so it appears in the front-end
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function activate_product($product_id, $page)
	{
		$this->products_model->activate_product($product_id);
		redirect('admin/products/index/'.$page);
	}
	
	function bulk_enable_product($page)
	{
		$total_products = sizeof($_POST['product']);
		
		//check if any checkboxes have been ticked
		if($total_products > 0){
			
			for($r = 0; $r < $total_products; $r++){
				
				$product = $_POST['product'];
				$product_id = $product[$r]; 
				
				$this->products_model->activate_product($product_id); 
			}
		}
		
		redirect('admin/products/index/'.$page);
	}
	
	/**
	 * Deactivate a specific product so that it doesn't appear in the front-end
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function deactivate_product($product_id, $page)
	{
		$this->products_model->deactivate_product($product_id);
		redirect('admin/products/index/'.$page);
	}
	
	function bulk_disable_product($page)
	{
		$total_products = sizeof($_POST['product']);
		
		//check if any checkboxes have been ticked
		if($total_products > 0){
			
			for($r = 0; $r < $total_products; $r++){
				
				$product = $_POST['product'];
				$product_id = $product[$r];
				
				$this->products_model->deactivate_product($product_id); 
			}
		}
		
		redirect('admin/products/index/'.$page);
	}
	
	/**
	 * Permanently delete a product from the system
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function delete_product($product_id, $page)
	{
		$this->products_model->delete_product($product_id);
		redirect('admin/products/index/'.$page);
	}
	
	function bulk_delete_product($page)
	{
		$total_products = sizeof($_POST['product']);
		
		//check if any checkboxes have been ticked
		if($total_products > 0){
			
			for($r = 0; $r < $total_products; $r++){
				
				$product = $_POST['product'];
				$product_id = $product[$r];
				
				$this->products_model->delete_product($product_id); 
			}
		}
		redirect('admin/products/index/'.$page);
	}
	
	/**
	 * Bulk action on products
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function bulk_products($page)
	{
		$action = $this->input->post('options');
		
		if($action == 1){
			$this->bulk_disable_product();
		}
		
		else if($action == 2){
			$this->bulk_delete_product();
		}
		
		else if($action == 3){
			$this->bulk_enable_product();
		}
		else{
			redirect('admin/products/index/'.$page);
		}
	}
	
	/**
	 * View a list of all product categories
	 *
	 */
	function categories()
	{
		// Set the template to use for this page
		
		
		$data['search'] = 0;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/products/categories';
		$config['total_rows'] = $this->db->count_all('category');
		$config['uri_segment'] = 4;
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
		$this->table->set_heading('','Category','Prefix', 'Parent',$axns);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['page'] = $page;
		if($categories = $this->products_model->fetch_categories($config["per_page"],$page)) {
			foreach($categories as $category) {
				if($category->category_status == 1)
					$status = anchor("admin/products/deactivate_category/$category->category_id/".$page,'Deactivate',array('title'=>'Deactivate'));
				else
					$status = anchor("admin/products/activate_category/$category->category_id/".$page,'Activate',array('title'=>'Activate'));
					
				$this->table->add_row(form_checkbox('category[]',$category->category_id),$category->category_name,$category->category_preffix,$category->category_parent,
					anchor("admin/products/update_category/$category->category_id/".$page,'Edit',array('title'=>'Edit')),
					anchor("admin/products/delete_category/$category->category_id/".$page,'Delete',array('title'=>'Delete')),
					$status,
					img(array('src'=>base_url()."assets/categories/thumbs/".$category->category_image_name,'alt'=>$category->category_name))
				);	
			}
		} else {
			$this->table->clear();
			$this->table->add_row('There are no categories added currently. Would you like to add some?');
		}
		$this->template->content->view("products/categories", $data);
		$this->template->publish();
	}
	
	/**
	 * Add a new product category to the system
	 *
	 */
	function add_category()
	{
		// Set the template to use for this page
		
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_rules('category_name','Category Name','trim|is_unique[category.category_name]|required|xss_clean');
		$this->form_validation->set_rules('category_preffix','Category Preffix','trim|required|is_unique[category.category_preffix]|xss_clean');
		$this->form_validation->set_rules('category_parent','Parent','trim|xss_clean');
		$this->form_validation->set_rules('category_status','Active','trim|required|xss_clean');
		$this->form_validation->set_message('is_unique', 'This item already exists.');
		
		if($this->form_validation->run()) {
			if(is_uploaded_file($_FILES['userfile']['tmp_name']))
			{
				$gallery_path = $this->gallery_path2;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$image_data = $this->do_upload($gallery_path);
			
				if($image_data == "FALSE"){
					echo $this->upload->display_errors('<p>', '</p>');
					$file_name = "";
				}
				else{
					$path = $image_data['full_path'];
					$file_path = $image_data['file_path'];
					$file_name = $image_data['file_name'];
					$file_type = $image_data['file_type'];
			
					/*
						-----------------------------------------------------------------------------------------
						Resize image
						-----------------------------------------------------------------------------------------
					*/
					$create = $this->resize_image($path, $gallery_path, $file_name);
			
					/*
						-----------------------------------------------------------------------------------------
						Create thumbnail
						-----------------------------------------------------------------------------------------
					*/
					$create = $this->create_thumb($path, $gallery_path, $file_name);
				}
			}
			
			else{
				$file_name = '';
			}
			$this->products_model->add_category($file_name);
			redirect('admin/products/categories');
		} else {
			$this->template->content->view('products/add_category_form');
			$this->template->publish();
		}
	}
	
	/**
	 * Callback to check if the prefix entered is unique
	 *
	 * @param string prefix
	 * @return bool
	 *
	 */
	function unique_prefix($category_preffix)
	{
		$prefixes = $this->products_model->get_all_category_preffixes();
		foreach($prefixes as $str)
			$prefix_array[] = $str->category_preffix;
		if(in_array($category_preffix,$prefix_array))
		{
			$this->form_validation->set_message('unique_prefix', 'This prefix is already in use. Please change the category name.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	/**
	 * Callback to check if the prefix entered is unique
	 * This one is for the ajax request
	 *
	 * @param string prefix
	 * @return bool
	 *
	 */
	function ajax_unique_prefix($category_preffix)
	{
		//$category_preffix = $this->input->post('category_preffix');
		
		$prefixes = $this->products_model->get_all_category_preffixes();
		foreach($prefixes as $str)
			$prefix_array[] = $str->category_preffix;
		if(in_array($category_preffix,$prefix_array))
		{
			$this->form_validation->set_message('unique_prefix', 'This preffix is already in use. Please change the category name.');
			echo 0;
		} else {
			echo 1;
		}
	}
	
	/**
	 * Update the deatails of a category
	 *
	 * @param int category_id
	 *
	 */
	function update_category($category_id, $page)
	{
		// Set the template to use for this page
		
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$data['details'] = $this->products_model->get_category_details($category_id);
		
		$this->form_validation->set_rules('category_parent','Parent','trim|xss_clean');
		
		if($this->form_validation->run()) {
			$this->products_model->update_category($category_id);
			redirect('admin/products/categories/'.$page);
		} else {
			$this->template->content->view('products/edit_category_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	 * Activate a product category
	 *
	 * @param int category_id
	 *
	 */
	function activate_category($category_id, $page)
	{
		$this->products_model->activate_category($category_id);
		redirect('admin/products/categories/'.$page);
	}
	
	function bulk_enable_category($page)
	{
		$total_categorys = sizeof($_POST['category']);
		
		//check if any checkboxes have been ticked
		if($total_categorys > 0){
			
			for($r = 0; $r < $total_categorys; $r++){
				
				$category = $_POST['category'];
				$category_id = $category[$r]; 
			
				//actovate categorys
				$this->products_model->activate_category($category_id);
			}
		}
		redirect('admin/products/categories/'.$page);
	}
	
	/**
	 * Deactivate a product category
	 *
	 * @param int category_id
	 *
	 */
	function deactivate_category($category_id, $page)
	{
		$this->products_model->deactivate_category($category_id);
		redirect('admin/products/categories/'.$page);
	}
	
	function bulk_disable_category($page)
	{
		$total_categorys = sizeof($_POST['category']);
		
		//check if any checkboxes have been ticked
		if($total_categorys > 0){
			
			for($r = 0; $r < $total_categorys; $r++){
				
				$category = $_POST['category'];
				$category_id = $category[$r];
			
				//deactivate categorys
				$this->products_model->deactivate_category($category_id);
			}
		}
		redirect('admin/products/categories/'.$page);
	}
	
	/**
	 * Delete a product category
	 * Careful Mr. Anderson, this cannot be undone
	 * I've seen this, this is the end
	 *
	 * @param int category_id
	 *
	 */
	function delete_category($category_id, $page)
	{
		$this->products_model->delete_category($category_id);
		redirect('admin/products/categories/'.$page);
	}
	
	function bulk_delete_category($page)
	{
		$total_categorys = sizeof($_POST['category']);
		
		//check if any checkboxes have been ticked
		if($total_categorys > 0){
			
			for($r = 0; $r < $total_categorys; $r++){
				
				$category = $_POST['category'];
				$category_id = $category[$r];
			
				//delete category
				$this->products_model->delete_category($category_id);
			}
		}
		redirect('admin/products/categories/'.$page);
	}
	
	/**
	 * Bulk action on categories
	 *
	 */
	
	function bulk_categories($page)
	{
		$action = $this->input->post('options');
		
		if($action == 1){
			$this->bulk_disable_category($page);
		}
		
		else if($action == 2){
			$this->bulk_delete_category($page);
		}
		
		else if($action == 3){
			$this->bulk_enable_category($page);
		}
		else{
			redirect('admin/products/categories/'.$page);
		}
	}
	
	/**
	 * View a list of all product category features
	 *
	 */
	function features()
	{
		// Set the template to use for this page
		
		
		$data['search'] = 0;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/products/features';
		$config['total_rows'] = $this->db->count_all('category');
		$config['uri_segment'] = 4;
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
		$data['categories'] = $this->products_model->fetch_categories($config["per_page"],$this->uri->segment(4));
		
		$this->template->content->view('products/features',$data);
		$this->template->publish();
	}
	
	/**
	 * Add a new feature to a product category
	 *
	 */
	function add_feature()
	{
		// Set the template to use for this page
		
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('category_id','Category','trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('feature_name','Feature Name','trim|required|xss_clean');
		$this->form_validation->set_rules('feature_units','Feature Units','trim|required|xss_clean');
		if($this->form_validation->run()) {
			$this->products_model->add_feature();
			redirect('admin/products/features');
		} else {
			$this->template->content->view('products/add_feature_form');
			$this->template->publish();
		}
	}
	
	/**
	 * Update the details of a product category feature
	 *
	 * @param int feature_id
	 *
	 */
	function update_feature($feature_id)
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
	 * Delete a product category feature
	 *
	 * @param int feature_id
	 *
	 */
	function delete_feature($feature_id)
	{
		
	}
	
	function resize_image($path, $gallery_path, $file_name)
	{
		$resize_conf = array(
			'source_image'  => $path,
			'new_image'     => $path.'images/'.$file_name,
			'create_thumb'  => FALSE,
			'width' => 460,
			'height' => 345,
			'maintain_ratio' => true,
		);
		
		$this->image_lib->initialize($resize_conf);
		 
		 if ( ! $this->image_lib->resize())
		{
		 	return $this->image_lib->display_errors();
		}
		
		else
		{
			return TRUE;
		}
	}
	
	function resize_image2($path)
	{
		/*
			-----------------------------------------------------------------------------------------
			Resize the image
			-----------------------------------------------------------------------------------------
		*/
		$config2 = array(
			'source_image' => $path,
			'maintain_ratio' => true,
			'height' => 345,
			'width' => 460
		);
		
		$this->load->library('image_lib', $config2);
		
		if ( ! $this->image_lib->resize())
		{
   		 	return $this->image_lib->display_errors();
		}
		
		else{
			return "True";
		}
	}
	
	function create_thumb($path, $gallery_path, $file_name)
	{
		/*
			-----------------------------------------------------------------------------------------
			Create a thumbnail
			-----------------------------------------------------------------------------------------
		*/
		$resize_conf = array(
			'source_image'  => $path,
			'new_image'     => $path.'thumbs/'.$file_name,
			'create_thumb'  => FALSE,
			'width'         => 80,
			'height'        => 60,
			'maintain_ratio' => true,
		);
		
		 $this->image_lib->initialize($resize_conf);
		 
		if ( ! $this->image_lib->resize())
		{
			return $this->image_lib->display_errors();
		}
		
		else
		{
			return TRUE;
		}
	}
	
	function create_thumb2($path, $gallery_path, $file_name)
	{
		/*
			-----------------------------------------------------------------------------------------
			Create a thumbnail
			-----------------------------------------------------------------------------------------
		*/
		$resize_conf = array(
			'source_image'  => $path,
			'new_image'     => $gallery_path.'thumbs/'.$file_name,
			'create_thumb'  => FALSE,
			'width'         => 80,
			'height'        => 60,
			'maintain_ratio' => true,
		);
		
		 $this->image_lib->initialize($resize_conf);
		 
		if ( ! $this->image_lib->resize())
		{
			return $this->image_lib->display_errors();
		}
		
		else
		{
			return TRUE;
		}
	}
	
	function get_path()
	{
		return $this->gallery_path;
	}
	
	function do_upload($gallery_path) 
	{
		/*
			-----------------------------------------------------------------------------------------
			Upload an image
			-----------------------------------------------------------------------------------------
		*/
		$config = array(
			'allowed_types' => 'JPG|JPEG|jpg|jpeg|gif|png',
			'upload_path' => $gallery_path,
			'quality' => "100%",
			'file_name' => "image_".date("Y")."_".date("m")."_".date("d")."_".date("H")."_".date("i")."_".date("s"),
			'max_size' => 2000
		);
		
		$this->load->library('upload', $config);
		if($this->upload->do_upload() == FALSE)
		{
			return "FALSE";
		}
		else{
			$image_data = $this->upload->data();
			return $image_data;
		}
	}
	
	function do_upload2($gallery_path, $file) 
	{
		/*
			-----------------------------------------------------------------------------------------
			Upload an image
			-----------------------------------------------------------------------------------------
		*/
		$config = array(
			'allowed_types' => 'JPG|JPEG|jpg|jpeg|gif|png',
			'upload_path' => $gallery_path,
			'quality' => "100%",
			'file_name' => "image_".date("Y")."_".date("m")."_".date("d")."_".date("H")."_".date("i")."_".date("s"),
			'max_size' => 2000
		);
		
		$this->load->library('upload', $config);
		if($this->upload->do_upload($file) == FALSE)
		{
			return "FALSE";
		}
		else{
			$image_data = $this->upload->data();
			return $image_data;
		}
	}
	
	/**
	 * Upload multiple files for a gallery
	 *
	 * @param int product_id
	 *
	 */
    function upload_gallery($product_id)
    {
		$result = $this->products_model->do_upload2($product_id); 
		return TRUE;
    }
	
	/**
	 * Delete gallery image
	 *
	 * @param int product_image_id
	 *
	 */
	function delete_gallery_image($product_image_id, $product_id, $page){
		
		$data['product_images'] = $this->products_model->delete_product_image($product_image_id);
		$this->update_product($product_id, $page);
	}

	/**
	 * List the product brands
	 * 
	 */
	function brands()
	{
		// Set the template to use for this page
		
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/products/brands';
		$config['total_rows'] = $this->db->count_all('brand');
		$config['uri_segment'] = 4;
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
		$this->table->set_heading('','Category Name', 'Brand Name','Products',$axns);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['page'] = $page;
		if($products  = $this->products_model->fetch_brands($config["per_page"],($page))) {
			foreach($products as $product) {
				$total_products = $this->products_model->count_all_brands($product->brand_id);
				if($product->brand_status == 1)
					$status = anchor("admin/products/deactivate_brand/$product->brand_id/".$page,'Deactivate');
				else
					$status = anchor("admin/products/activate_brand/$product->brand_id/".$page,'Activate');
					$this->table->add_row(form_checkbox('brand[]',$product->brand_id),
					$product->category_name,
					$product->brand_name,
					$total_products,
					anchor("admin/products/update_brand/$product->brand_id/".$page,'Edit'),
					anchor("admin/products/delete_brand/$product->brand_id/".$page,'Delete',array('onClick'=>'return confirm(\'Do you really want to delete this brand?\');','title'=>'Delete')),
					$status
				);
			}
		} else {
			$this->table->add_row('There are no brands to show here :-|');
		}
		//$this->load->view("products/product_list",$data);
		$this->template->content->view('products/brand_list',$data);
		$this->template->publish();
	}
	
	/**
	 * Add a new brand to the system
	 * 
	 */
	function add_brands()
	{
		// Set the template to use for this page
		
		
		$this->form_validation->set_rules('brand_name','Brand Name','trim|required|xss_clean');
		
		if($this->form_validation->run()) {
			$product_id = $this->products_model->add_brand();
			redirect('admin/products/brands');
		} 
		
		else {
			$this->template->content->view("products/add_brand_form");
			$this->template->publish();
		}
	}
	
	function delete_brand($brand_id, $page)
	{
		$this->products_model->delete_brand($brand_id);
		redirect('admin/products/brands/'.$page);
	}
	
	function deactivate_brand($brand_id, $page)
	{
		$this->products_model->deactivate_brand($brand_id);
		redirect('admin/products/brands/'.$page);
	}
	
	function activate_brand($brand_id, $page)
	{
		$this->products_model->activate_brand($brand_id);
		redirect('admin/products/brands/'.$page);
	}
	
	/**
	 * Edit the details of a product
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function update_brand($brand_id, $page)
	{
		$data['details'] = $this->products_model->get_brand_details($brand_id);
		$data['page'] = $page;
		
		// Set the template to use for this page
		
		
		$this->form_validation->set_rules('brand_name','Brand Name','trim|required|xss_clean');
		
		if($this->form_validation->run()) {
			$product_id = $this->products_model->update_brand($brand_id);
			redirect('admin/products/brands/'.$page);
		} 
		
		else {
			$this->template->content->view("products/edit_brand_form", $data);
			$this->template->publish();
		}
	}
	
	/**
	 * Retrieve the brands of a particular category
	 * 
	 * @param	int	$category_id
	 * 
	 */
	function get_category_brands($category_id)
	{
		//var_dump($this->feature_values);
		if($brands = $this->products_model->get_category_brands($category_id)) {
		
			$brands_array = array();
			$brands_array[0] = 'None';
			foreach($brands as $brand){
				$brands_array[$brand->brand_id] = $brand->brand_name;
			}
			
			echo 
			'
				<div class="control-group">
					<label  class="control-label" for="brand_id">Product Brand</label>
						<div class="controls">
							'.form_dropdown("brand_id",$brands_array, $this->input->post("brand_id"), "id = 'brand_id'").'
							<span class="help-block">
							'.form_error('brand_id').'
							</span>
						</div>
					</div>
				
			';
		} else {
			echo json_encode($brands);
		}
	}
	
	// View all rules tied to user roles
	function category_features()
	{
		// Set the template to use for this page
		
		
		$this->load->library('table');
		$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr class="heading">',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th>',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'row_alt_start'       => '<tr class="alt">',
		  'row_alt_end'         => '</tr>',
		  'cell_alt_start'      => '<td>',
		  'cell_alt_end'        => '</td>',
		  'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl); 
		//$data['category_features'] = $this->products_model->get_all_category_features($category_id);
		$data['categories'] = $this->products_model->get_all_categories();
		$this->template->content->view('products/category_features',$data);
		$this->template->publish();
	}
	
	/**
	 * Add a new feature to a product category
	 *
	 */
	function add_category_feature()
	{
		// Set the template to use for this page
		
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('category_id','Category','trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('category_feature_name','Category Feature Name','trim|required|xss_clean');
		
		if($this->form_validation->run()) {
			$category_feature_id = $this->products_model->add_category_feature();
			
			$total_category_features = 10;
			
			if($total_category_features > 0){
			
				for($r = 1; $r < $total_category_features; $r++){
					/*
						-----------------------------------------------------------------------------------------
						Retrieve the feature value
						-----------------------------------------------------------------------------------------
					*/
					$feature_value = $this->input->post('category_feature_value'.$r);
					$feature_price = $this->input->post('category_feature_value_price'.$r);
					/*
						-----------------------------------------------------------------------------------------
						Upload the feature image
						-----------------------------------------------------------------------------------------
					*/
					if(is_uploaded_file($_FILES['userfile'.$r]['tmp_name'])){
						
						$gallery_path = $this->gallery_path3;
						$image_data = $this->do_upload2($gallery_path, 'userfile'.$r);
			
						if($image_data == "FALSE"){
							$file_name = "";
							/*$data['error'.$r] = $this->upload->display_errors();
							$this->template->content->view("products/add_category_feature_form", $data);
							$this->template->publish();*/
						}
						else{
							$path = $image_data['full_path'];
							$file_path = $image_data['file_path'];
							$file_name = $image_data['file_name'];
							$file_type = $image_data['file_type'];
							echo $path."<br/>".$file_name."<br/>".$gallery_path;
							/*
								-----------------------------------------------------------------------------------------
								Create thumbnail
								-----------------------------------------------------------------------------------------
							*/
							$create = $this->create_thumb($path, $gallery_path, $file_name);
			
							/*
								-----------------------------------------------------------------------------------------
								Delete the larger files
								-----------------------------------------------------------------------------------------
							*/
							//
						}
					}
					
					else{
						$file_name = "";
					}
					
					/*
						-----------------------------------------------------------------------------------------
						Save the feature details
						-----------------------------------------------------------------------------------------
					*/
					if(!empty($feature_value)){
						$this->products_model->add_category_feature_value($category_feature_id, $feature_value, $file_name, $feature_price);
						//delete_files($gallery_path);
					}
				}
			}
			
			redirect('admin/products/category_features');
		} 
		
		else {
			$this->template->content->view('products/add_category_feature_form');
			$this->template->publish();
		}
	}
	
	/**
	 * Update the details of a category feature
	 *
	 * @param int category_feature_id
	 *
	 */
	function update_category_feature($category_feature_id)
	{
		// Set the template to use for this page
		
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$data['category_features'] = $this->products_model->get_category_feature_details($category_feature_id);
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('category_id','Category','trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('category_feature_name','Category Feature Name','trim|required|xss_clean');
		
		if($this->form_validation->run()) {
			$this->products_model->update_category_feature($category_feature_id);
			$total_category_features = 5;
			
			if($total_category_features > 0){
			
				for($r = 1; $r < $total_category_features; $r++){
					/*
						-----------------------------------------------------------------------------------------
						Retrieve the feature value
						-----------------------------------------------------------------------------------------
					*/
					$feature_value = $this->input->post('category_feature_value'.$r);
					/*
						-----------------------------------------------------------------------------------------
						Upload the feature image
						-----------------------------------------------------------------------------------------
					*/
					if(is_uploaded_file($_FILES['userfile'.$r]['tmp_name'])){
						
						$gallery_path = $this->gallery_path3;
						$image_data = $this->do_upload2($gallery_path, 'userfile'.$r);
			
						if($image_data == "FALSE"){
							$file_name = "";
						}
						else{
							$path = $image_data['full_path'];
							$file_path = $image_data['file_path'];
							$file_name = $image_data['file_name'];
							$file_type = $image_data['file_type'];
							echo $path."<br/>".$file_name."<br/>".$gallery_path;
							/*
								-----------------------------------------------------------------------------------------
								Create thumbnail
								-----------------------------------------------------------------------------------------
							*/
							$create = $this->create_thumb($path, $gallery_path, $file_name);
						}
					}
					
					else{
						$file_name = "";
					}
					
					/*
						-----------------------------------------------------------------------------------------
						Save the feature details
						-----------------------------------------------------------------------------------------
					*/
					if(!empty($feature_value)){
						$this->products_model->add_category_feature_value($category_feature_id, $feature_value, $file_name);
					}
				}
			}
			redirect('admin/products/category_features');
		} 
		
		else {
			$this->template->content->view('products/edit_category_feature_form', $data);
			$this->template->publish();
		}
	}
	
	/**
	 * Delete a product category feature
	 *
	 * @param int feature_id
	 *
	 */
	function delete_category_feature($category_feature_id)
	{
		$this->products_model->delete_category_feature($category_feature_id);
		redirect('admin/products/category_features');
	}
	
	/**
	 * Activate a specific product so it appears in the front-end
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function activate_category_feature($category_feature_id)
	{
		$this->products_model->activate_category_feature($category_feature_id);
		redirect('admin/products/category_features');
	}
	
	/**
	 * Deactivate a specific product so that it doesn't appear in the front-end
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function deactivate_category_feature($category_feature_id)
	{
		$this->products_model->deactivate_category_feature($category_feature_id);
		redirect('admin/products/category_features');
	}
	
	/**
	 * View the category_feature_value
	 * 
	 * @param int $category_feature_id
	 * 
	 */	
	function view_category_feature_value($category_feature_id)
	{
		// Set the template to use for this page
		
		
		$this->load->library('table');
		$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr class="heading">',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th>',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'row_alt_start'       => '<tr class="alt">',
		  'row_alt_end'         => '</tr>',
		  'cell_alt_start'      => '<td>',
		  'cell_alt_end'        => '</td>',
		  'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);
		$data['details'] = $this->products_model->get_category_feature_values($category_feature_id);
		$data['category_feature_id'] = $category_feature_id;
		$this->template->content->view("products/view_category_feature", $data);
		$this->template->publish();
	}
	
	function update_category_feature_value($category_feature_id)
	{
		$details = $this->products_model->get_category_feature_values($category_feature_id);
		if(count($details) > 0) {
			foreach($details as $feature) {
				$id = $feature->category_feature_value_id;
				
				$name = $this->input->post("feature_name".$id);
				$price = $this->input->post("feature_price".$id);
				
				$this->products_model->update_category_feature_value($name, $price, $id);
			}
		}
		
		redirect("admin/products/view_category_feature_value/".$category_feature_id);
	}
	
	/**
	 * Delete a product category feature value
	 *
	 * @param int feature_id
	 *
	 */
	function delete_category_feature_value($category_feature_value_id, $category_feature_id)
	{
		$this->products_model->delete_category_feature_value($category_feature_value_id);
		redirect('admin/products/view_category_feature_value/'.$category_feature_id);
	}
	
	function save_feature_price($category_feature_value_id, $price)
	{
		$items = array(
					"category_feature_value_price" => $price
				);
		$this->products_model->save_feature_price($category_feature_value_id, $items);
	}
	
	/**
	 * Recommend a product to appear on the home page recommended
	 * 
	 * @param int $product_id
	 * 
	 */
	function recommend_product($product_id, $page)
	{
		$this->products_model->recommend_product($product_id);
		redirect('admin/products/index/'.$page);
	}
	
	/**
	 * Unrecommend a product to not appear on the home page recommended
	 * 
	 * @param int $product_id
	 * 
	 */
	function unrecommend_product($product_id, $page)
	{
		$this->products_model->unrecommend_product($product_id);
		redirect('admin/products/index/'.$page);
	}
	
	/**
	 * Add a new product to the system
	 * 
	 */
	function add_offer($product_id, $page)
	{
		// Set the template to use for this page
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_rules('product_offer_amount','% off','trim|required|is_numeric|xss_clean');
		$this->form_validation->set_rules('end_date','End Date','trim|required|xss_clean');
		
		if($this->form_validation->run()) {
			$this->products_model->add_offer($product_id);
			redirect('admin/products/index/'.$page);
		} 
		
		else {
			$data['product_id'] = $product_id;
			$this->feature_values = $this->input->post('feature');
			$this->template->content->view("products/add_offer_form", $data);
			$this->template->publish();
		}
	}
	function remove_offer($product_id, $page)
	{
		$this->products_model->remove_offer($product_id);
		redirect('admin/products/index/'.$page);
	}
}