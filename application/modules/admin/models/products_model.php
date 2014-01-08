<?php
class Products_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *  Get a list of all products
	 *  
	 * @param	int	$limit	The number of items to fetch
	 * @param	int	$start	Which position to start retrieving from
	 * @return	object
	 * 
	 */
	function fetch_products($limit,$start)
	{
		$this->db->select('*')->from('product, brand')->where("product.brand_id = brand.brand_id")->order_by("product.product_name");
		$query = $this->db->get('',$limit,$start);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get the details of a particular product
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function get_product_details($product_id)
	{
		$this->db->select('*')->from('product')->where('product_id',$product_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Get a product's ID given its product code
	 *
	 * @param string product_code
	 * @return int product_id
	 *
	 */
	function get_product_id($product_code)
	{
		$this->db->select('product_id')->from('products')->where('product_code',$product_code);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('product_id');
		else
			return NULL;
	}
	
	/**
	 * Get all features of a product
	 * 
	 * @param	int	$product_id
	 * 
	 */
	function get_product_features($product_id)
	{
		$this->db->select('pf.feature_value,cf.feature_id,cf.feature_name,cf.feature_units')->from('product_features as pf')->where('pf.product_id',$product_id);
		$this->db->join('category_features as cf','pf.feature_id = cf.feature_id','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all active products from the DB
	 *
	 * @return object
	 *
	 */
	function get_all_active_products()
	{
		$this->db->select('*')->from('product')->where('product_status',1);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all active product categories from the DB
	 *
	 * @return object
	 *
	 */
	function get_all_active_categories()
	{
		$this->db->select('*')->from('category')->where('category_status',1)->order_by("category_name");
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all active product categories from the DB
	 *
	 * @return object
	 *
	 */
	function get_all_active_order_methods()
	{
		$this->db->select('*')->from('order_methods')->where('order_method_status',1);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Add a new product to the DB
	 *
	 * @param string product_image_name
	 *
	 */
	function add_product($product_image_name)
	{
		$features_array = $this->input->post('feature');
		
		$data = array('brand_id'=>$this->input->post('brand_id'), 'product_name'=>$this->input->post('product_name'),'product_selling_price'=>$this->input->post('product_selling_price'),
			'product_buying_price'=>$this->input->post('product_buying_price'),'product_description'=>$this->input->post('product_description'),
			'product_image_name'=>$product_image_name,'product_balance'=>$this->input->post('product_balance'),'category_id'=>$this->input->post('category_id'),
			'product_status'=>1,'product_code'=>$this->input->post('product_code')
		);
		
		if($this->db->insert('product',$data)) {
			$product_id = $this->db->insert_id();
			$this->save_product_features($features_array,$product_id);
		}
		else{
			$product_id = 0;
		}
		return $product_id;
	}
	
	/**
	 * Update the details of a product
	 *
	 * @param int product_id
	 * @param string product_image_name
	 *
	 */
	function update_product($product_id,$product_image_name)
	{
		$features_array = $this->input->post('feature');
		
		$data = array('brand_id'=>$this->input->post('brand_id'), 'product_name'=>$this->input->post('product_name'),'product_selling_price'=>$this->input->post('product_selling_price'),
			'product_buying_price'=>$this->input->post('product_buying_price'),'product_description'=>$this->input->post('product_description'),
			'product_balance'=>$this->input->post('product_balance'),'category_id'=>$this->input->post('category_id'),
			'product_status'=>1,'product_code'=>$this->input->post('product_code')
		);
		if($product_image_name)
			$data['product_image_name'] = $product_image_name;
		if($this->db->where('product_id',$product_id)->update('product',$data))
			$this->save_product_features($features_array,$product_id);
	}
	
	/**
	 * Activate a product so that it is available in the front-end
	 *
	 * @param int product_id
	 *
	 */
	function activate_product($product_id)
	{
		$data['product_status'] = 1;
		$this->db->where('product_id',$product_id)->update('product',$data);
	}
	
	/**
	 * Deactivate a product so that it is not avaialable in the front-end
	 *
	 * @param int product_id
	 *
	 */
	function deactivate_product($product_id)
	{
		$data['product_status'] = 0;
		$this->db->where('product_id',$product_id)->update('product',$data);
	}
	
	/**
	 * Recommend a product to appear on the home page recommended
	 * 
	 * @param int $product_id
	 * 
	 */
	function recommend_product($product_id, $page)
	{
		$data['product_recommended'] = 1;
		$this->db->where('product_id',$product_id)->update('product',$data);
	}
	
	/**
	 * Unrecommend a product to not appear on the home page recommended
	 * 
	 * @param int $product_id
	 * 
	 */
	function unrecommend_product($product_id, $page)
	{
		$data['product_recommended'] = 0;
		$this->db->where('product_id',$product_id)->update('product',$data);
	}
	
	/**
	 * Delete a product completely from the system
	 *
	 * @param int product_id
	 *
	 */
	function delete_product($product_id)
	{
		$this->db->delete('product',array('product_id'=>$product_id));
		$this->db->delete('product_features',array('product_id'=>$product_id));
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
		$this->db->select('category_preffix')->from('category')->where('category_id',$category_id);
		$prefix = $this->db->get()->row('category_preffix');
		$this->db->limit(1)->select('product_code')->from('product')->where('category_id',$category_id)->order_by('product_id','desc');
		if($product_code = $this->db->get()->row('product_code')) {
			preg_match('#[0-9]#',$product_code,$number);
			$next_product_number = $number[0] + 1;
		}
		else
			$next_product_number = 1;
		$new_product_code = $prefix.$next_product_number;
		return $new_product_code;
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
		$this->db->select('*')->from('category_features')->where('category_id',$category_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Save the features of a newly created product
	 *
	 * @param array features_array
	 * @param int product_id
	 *
	 */
	function save_product_features($features_array,$product_id)
	{
		foreach($features_array as $feature_id => $feature_value) {
			$features = array('feature_id'=>$feature_id,'feature_value'=>$feature_value,'product_id'=>$product_id);
			
			// First check if the feature exists for the product
			$this->db->select('product_feature_id')->from('product_features')->where('product_id',$product_id)->where('feature_id',$feature_id);
			$query = $this->db->get();
			if($query->num_rows() == 1)
				$this->db->where('product_id',$product_id)->where('feature_id',$feature_id)->update('product_features',$features); // Update if feature exists
			else
				$this->db->insert('product_features',$features); // Insert if feature doesn't exist
		}
	}
	
	/**
	 * Get a list of all product categories from the DB
	 *
	 * @return object
	 * 
	 */
	function fetch_categories($num,$offset)
	{
		$this->db->select('*')->from('category');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Add a new category to the DB
	 *
	 */
	function add_category($file_name)
	{
		$data = array('category_name'=>$this->input->post('category_name'),'category_preffix'=>$this->input->post('category_preffix'),
			'category_parent'=>$this->input->post('category_parent'),'category_status'=>$this->input->post('category_status'), 'category_image_name'=>$file_name
		);
		$this->db->insert('category',$data);
	}
	
	/**
	 * Update the deatails of a particular category
	 *
	 * @param int category_id
	 *
	 */
	function update_category($category_id)
	{
		$data = array('category_parent'=>$this->input->post('category_parent'));
		$this->db->where('category_id',$category_id)->update('category',$data);
	}
	
	/**
	 * Activate a product category
	 *
	 * @param int category_id
	 *
	 */
	function activate_category($category_id)
	{
		$data = array('category_status'=>1);
		$this->db->where('category_id',$category_id)->update('category',$data);
	}
	
	/**
	 * Deactivate a product category
	 *
	 * @param int category_id
	 *
	 */
	function deactivate_category($category_id)
	{
		$data = array('category_status'=>0);
		$this->db->where('category_id',$category_id)->update('category',$data);
	}
	
	/**
	 * Completely delete a product category from the system
	 *
	 * @param int category_id
	 *
	 */
	function delete_category($category_id)
	{
		$this->db->delete('category',array('category_id'=>$category_id));
	}
	
	/**
	 * Get a list of all product category prefixes
	 * Called when creating a new product category to avoid duplicate prefixes
	 *
	 * @return array
	 *
	 */
	function get_all_category_preffixes()
	{
		$this->db->select('category_preffix')->from('category');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get the details of a specific product category
	 *
	 * @param int category_id
	 *
	 */
	function get_category_details($category_id)
	{
		$this->db->select('*')->from('category')->where('category_id',$category_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Get the details of a product feature
	 *
	 * @param int feature_id
	 *
	 * @return object
	 *
	 */
	function get_product_feature_details($feature_id)
	{
		$this->db->select('*')->from('category_features')->where('feature_id',$feature_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Add a new product feature to the DB
	 *
	 */
	function add_feature()
	{
		$data = array('category_id'=>$this->input->post('category_id'),'feature_name'=>$this->input->post('feature_name'),
			'feature_units'=>$this->input->post('feature_units')
		);
		$this->db->insert('category_features',$data);
	}
	
	/**
	 * Add a new product feature to the DB
	 *
	 */
	function add_customer($order_type)
	{
		/*
			-----------------------------------------------------------------------------------------
			Retrieve the customer id
			-----------------------------------------------------------------------------------------
		*/
		$customer_name = $this->input->post('customer_name');
		$customer = $this->select_customer_id($customer_name);
		if(count($customer) > 0){
			foreach ($customer as $cust){
				$customer_id = $cust->user_id;
			}
		
			$new_order_insert_data = array(
				//'users_id' => $this->session->userdata('users_id'),
				'customer_id' => $customer_id,
				'order_delivery_date' => $this->input->post('delivery_date'),
				'order_method_id' => $this->input->post('order_method_id'),
				'order_type' => $order_type				
			);
		
			$insert = $this->db->insert('`order`', $new_order_insert_data);
			$order_id = $this->db->insert_id();
			return $order_id;
		}
		
		else{
			return 0;
		}
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve customer id
		-----------------------------------------------------------------------------------------
	*/
	 function select_customer_id($customer_name)
    {
		$this->db->select("user_id");
		$this->db->from("users");
        $this->db->where("username = '".$customer_name."'");
		$this->db->order_by("user_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve active customers
		-----------------------------------------------------------------------------------------
	*/
	 function get_all_active_customers()
    {
		$this->db->select("username");
		$this->db->from("users");
        $this->db->where("u_level_id = 3");
		$this->db->order_by("username", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/**
	 * Update the details of a product feature
	 *
	 * @param int feature_id
	 *
	 */
	function update_feature($feature_id)
	{
		$data = array('feature_name'=>$this->input->post('feature_name'),'feature_units'=>$this->input->post('feature_units')
		);
		$this->db->where('feature_id',$feature_id)->where('category_id',$this->input->post('category_id'))->update('category_features',$data);
	}
	
	/**
	 * Upload multiple files for a gallery
	 *
	 * @param int product_id
	 *
	 */
    function do_upload2($product_id)
    {
		//Libraries
        $this->load->library('upload');
        $this->load->library('image_lib');
    
        // Change $_FILES to new vars and loop them
        foreach($_FILES['gallery'] as $key=>$val)
        {
            $i = 1;
            foreach($val as $v)
            {
                $field_name = "file_".$i;
                $_FILES[$field_name][$key] = $v;
                $i++;   
            }
        }
        // Unset the useless one ;)
        unset($_FILES['gallery']);
    
        // Put each errors and upload data to an array
        $error = array();
        $success = array();
        
        // main action to upload each file
        foreach($_FILES as $field_name => $file)
        {
		
		$upload_conf = array(
			'allowed_types' => 'JPG|JPEG|jpg|jpeg|gif|png',
			'upload_path' => realpath('assets/products'),
			'quality' => "100%",
			'file_name' => "image_".date("Y")."_".date("m")."_".date("d")."_".date("H")."_".date("i")."_".date("s"),
			'max_size' => 20000,
			'maintain_ratio' => true,
			'height' => 345,
			'width' => 460
         );
    
        $this->upload->initialize( $upload_conf );
		
            if ( ! $this->upload->do_upload($field_name))
            {
                // if upload fail, grab error 
                $error['upload'][] = $this->upload->display_errors();
            }
            else
            {
                // otherwise, put the upload datas here.
                // if you want to use database, put insert query in this loop
                $upload_data = $this->upload->data();
                
                // set the resize config
                $resize_conf = array(
                    // it's something like "/full/path/to/the/image.jpg" maybe
                    'source_image'  => $upload_data['full_path'], 
                    'new_image'     => $upload_data['file_path'].'gallery/'.$upload_data['file_name'],
                    'create_thumb'     => FALSE,
					'width' => 460,
                    'height' => 345,
					'maintain_ratio' => true,
                    );

                // initializing
                $this->image_lib->initialize($resize_conf);

                // do it!
                if ( ! $this->image_lib->resize())
                {
                    // if got fail.
                    $error['resize'][] = $this->image_lib->display_errors();
                }
                else
                {
                    // otherwise, put each upload data to an array.
                    $success[] = $upload_data;
                }
				
				$data = array(//get the items from the form
					'product_id' => $product_id,
					'product_image_name' => $upload_data['file_name'],
					'product_image_thumb' => 'thumb_'.$upload_data['file_name']
				);
			
				$insert = $this->db->insert('product_image', $data);
                
                // set the resize config
                $resize_conf = array(
                    // it's something like "/full/path/to/the/image.jpg" maybe
                    'source_image'  => $upload_data['full_path'], 
                    // and it's "/full/path/to/the/" + "thumb_" + "image.jpg
                    // or you can use 'create_thumbs' => true option instead
                    'new_image'     => $upload_data['file_path'].'gallery/thumb_'.$upload_data['file_name'],
                    'width'         => 80,
                    'height'        => 60,
					'maintain_ratio' => true,
                    );

                // initializing
                $this->image_lib->initialize($resize_conf);

                // do it!
                if ( ! $this->image_lib->resize())
                {
                    // if got fail.
                    $error['resize'][] = $this->image_lib->display_errors();
                }
                else
                {
                    // otherwise, put each upload data to an array.
                    $success[] = $upload_data;
                }
				//delete_files($upload_data['full_path']);
            }
			
        }

        // see what we get
        if(count($error > 0))
        {
            $data['error'] = $error;
        }
        else
        {
            $data['success'] = $upload_data;
        }
		
		return TRUE;
    }
	
	/**
	 * Retrieve gallery images
	 *
	 * @param int product_id
	 *
	 */
	 function select_product_images($product_id)
    {
		$this->db->select("*");
		$this->db->from("product_image");
        $this->db->where("(product_id = ".$product_id.")");
		$this->db->order_by("product_id", "asc"); 
		
		$query = $this->db->get();
		return $query->result();
    }
	
	/**
	 * delete gallery images
	 *
	 * @param int product_image_id
	 *
	 */
	 function delete_product_image($product_image_id)
    {
        $this->db->where("(product_image_id = ".$product_image_id.")");
		
		$this->db->delete('product_image');
    }
	
	/**
	 *  Get a list of all products
	 *  
	 * @param	int	$limit	The number of items to fetch
	 * @param	int	$start	Which position to start retrieving from
	 * @return	object
	 * 
	 */
	function fetch_brands($limit,$start)
	{
		$this->db->select('brand.brand_id, brand.brand_name, category.category_name, brand.brand_status')
		->from('brand, category')
		->where("brand.category_id = category.category_id")
		->order_by("category_name, brand_name");
		$query = $this->db->get('',$limit,$start);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 *  Get a list of all products
	 *  
	 * @param	int	$limit	The number of items to fetch
	 * @param	int	$start	Which position to start retrieving from
	 * @return	object
	 * 
	 */
	function get_brand_details($brand_id)
	{
		$this->db->select('*')->from('brand')->where("brand_id = ".$brand_id)->order_by("brand_name");
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Add a new brand to the DB
	 *
	 * 
	 *
	 */
	function add_brand()
	{
		$data = array(
			'brand_name'=>$this->input->post('brand_name'),
			'category_id'=>$this->input->post('category_id')
		);
		
		if($this->db->insert('brand',$data)) {
			$brand_id = $this->db->insert_id();
		}
		else{
			$brand_id = 0;
		}
		return $brand_id;
	}
	
	function count_all_brands($brand_id)
	{
		$where = "(brand_id = ".$brand_id.")";
        $this->db->where($where);
		$this->db->from("product");
        return $this->db->count_all_results();
	}
	
	function get_all_active_brands()
	{
		$where = "(brand_status = 1)";
        $this->db->where($where);
		$this->db->from("brand");
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function get_active_category_brands($category)
	{
		$where = "(brand_status = 1) AND (brand.category_id = category.category_id) ".$category;
		$this->db->select('brand.brand_name, brand.category_id, brand.brand_id')->from('brand, category')->where($where);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Deactivate a brand so that it is not avaialable in the front-end
	 *
	 * @param int brand_id
	 *
	 */
	function update_brand($brand_id)
	{
		$data['brand_name'] = $this->input->post("brand_name");
		$data['category_id'] = $this->input->post("category_id");
		$this->db->where('brand_id',$brand_id)->update('brand',$data);
	}
	
	/**
	 * Deactivate a brand so that it is not avaialable in the front-end
	 *
	 * @param int brand_id
	 *
	 */
	function deactivate_brand($brand_id)
	{
		$data['brand_status'] = 0;
		$this->db->where('brand_id',$brand_id)->update('brand',$data);
	}
	
	/**
	 * Activate a brand so that it is avaialable in the front-end
	 *
	 * @param int brand_id
	 *
	 */
	function activate_brand($brand_id)
	{
		$data['brand_status'] = 1;
		$this->db->where('brand_id',$brand_id)->update('brand',$data);
	}
	
	/**
	 * Delete a product completely from the system
	 *
	 * @param int product_id
	 *
	 */
	function delete_brand($brand_id)
	{
		$this->db->delete('brand',array('brand_id'=>$brand_id));
	}
	
	/**
	 * Retrieve from the DB the brands of a particular category
	 *
	 * @param int category_id
	 *
	 */
	function get_category_brands($category_id)
	{
		$where = "(brand_status = 1) AND (category_id = ".$category_id.")";
		$this->db->select('*')->from('brand')->where($where);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Retrieve from the DB the all category_features
	 *
	 * @param category_id
	 *
	 */
	function get_all_category_features($category_id)
	{
		$where = "(category_feature.category_id = ".$category_id.")";
		
		$this->db->
		select('category_feature.category_feature_id AS id, category_feature.category_feature_name AS feature, category_feature.category_feature_status AS status')->
		from('category_feature')->
		where($where)->
		order_by("feature");
		
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Retrieve from the DB the all categories
	 *
	 * 
	 *
	 */
	function get_all_categories()
	{
		$where = "(category_status = 1)";
		$this->db->select('*')->from('category')->where($where)->order_by("category_name");
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Add a new category feature to the DB
	 *
	 */
	function add_category_feature()
	{
		$data = array('category_id'=>$this->input->post('category_id'),'category_feature_name'=>$this->input->post('category_feature_name')
		);
		$this->db->insert('category_feature',$data);
		return $this->db->insert_id();
	}
	
	/**
	 * Add a new category feature value to the DB
	 *
	 */
	function add_category_feature_value($category_feature_id, $feature_value, $feature_image, $feature_price)
	{
		$data = array(
					'category_feature_id'=>$category_feature_id, 
					'category_feature_value'=>$feature_value, 
					'category_feature_value_price'=>$feature_price, 
					'category_feature_value_image'=>$feature_image
		);
		$this->db->insert('category_feature_value',$data);
		return $this->db->insert_id();
	}
	
	/**
	 * Retrieve from the DB the all category_features
	 *
	 * @param category_feature_id
	 *
	 */
	function get_category_feature_details($category_feature_id)
	{
		$where = "(category_feature.category_feature_id = ".$category_feature_id.")";
		
		$this->db->
		select('*')->
		from('category_feature')->
		where($where);
		
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Update the deatails of a particular category feature
	 *
	 * @param int category_feature_id
	 *
	 */
	function update_category_feature($category_feature_id)
	{
		$data = array('category_feature_name'=>$this->input->post('category_feature_name'), 'category_id'=>$this->input->post('category_id'));
		$this->db->where('category_feature_id',$category_feature_id)->update('category_feature',$data);
	}
	
	/**
	 * Activate a category_feature so that it is available in the front-end
	 *
	 * @param int category_feature_id
	 *
	 */
	function activate_category_feature($category_feature_id)
	{
		$data['category_feature_status'] = 1;
		$this->db->where('category_feature_id',$category_feature_id)->update('category_feature',$data);
	}
	
	/**
	 * Deactivate a category_feature so that it is not avaialable in the front-end
	 *
	 * @param int category_feature_id
	 *
	 */
	function deactivate_category_feature($category_feature_id)
	{
		$data['category_feature_status'] = 0;
		$this->db->where('category_feature_id',$category_feature_id)->update('category_feature',$data);
	}
	
	/**
	 * Delete a category_feature completely from the system
	 *
	 * @param int category_feature_id
	 *
	 */
	function delete_category_feature($category_feature_id)
	{
		$this->db->delete('category_feature_value',array('category_feature_id'=>$category_feature_id));
		$this->db->delete('category_feature',array('category_feature_id'=>$category_feature_id));
	}
	
	/**
	 * Retrieve from the DB the all category_features
	 *
	 * @param category_id
	 *
	 */
	function get_category_feature_values($category_feature_id)
	{
		$where = "(category_feature_id = ".$category_feature_id.")";
		
		$this->db->
		select('*')->
		from('category_feature_value')->
		where($where)->
		order_by("category_feature_value");
		
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Delete a category_feature completely from the system
	 *
	 * @param int category_feature_id
	 *
	 */
	function delete_category_feature_value($category_feature_value_id)
	{
		$this->db->delete('category_feature_value',array('category_feature_value_id'=>$category_feature_value_id));
	}
	
	function save_feature_price($category_feature_value_id, $data)
	{
		$this->db->where('category_feature_value_id',$category_feature_value_id)->update('category_feature_value',$data);
	}
	
	/**
	 * Update a category feature value
	 *
	 * @param int id, name, price
	 *
	 */
	function update_category_feature_value($name, $price, $id)
	{
		$data['category_feature_value'] = $name;
		$data['category_feature_value_price'] = $price;
		$this->db->where('category_feature_value_id',$id)->update('category_feature_value',$data);
	}
	
	function add_offer($product_id)
	{
		$data['product_offer'] = 1;
		$data['product_offer_amount'] = $this->input->post("product_offer_amount");
		$data['product_offer_date'] = $this->input->post("end_date");
		$this->db->where('product_id',$product_id)->update('product',$data);
	}
	function remove_offer($product_id)
	{
		$data['product_offer'] = 0;
		$data['product_offer_amount'] = 0;
		$this->db->where('product_id',$product_id)->update('product',$data);
	}
}