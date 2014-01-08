<?php
require_once "./application/modules/admin/controllers/admin.php";
class adverts extends Admin
{
	var $gallery_path;
	
	private $feature_values;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('adverts_model');
		$this->load->model('products_model');
		
        $this->load->library('image_lib');
		
		$this->gallery_path = realpath(APPPATH . '../adverts');
	}

	/**
	 * Default action for the controller. Show a list of adverts
	 * 
	 */
	function index()
	{
		// Set the template to use for this page
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/adverts/index';
		$config['total_rows'] = $this->db->count_all('advert');
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
		$this->table->set_heading('','Poster', 'Position', 'Category','Advert Name', 'Date Added',$axns);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['page'] = $page;
		if($adverts  = $this->adverts_model->fetch_adverts($config["per_page"],$page)) {
			foreach($adverts as $advert) {
				if($advert->advert_status == 1)
					$status = anchor("admin/adverts/deactivate_advert/$advert->advert_id/".$page,'Deactivate');
				else
					$status = anchor("admin/adverts/activate_advert/$advert->advert_id/".$page."/".$advert->category_id,'Activate');
				
				if(!empty($advert->category_id)){
					if( $category = $this->adverts_model->get_category($advert->category_id)){
						$category_name = $category->category_name;
					}
					else{
						$category_name = "";
					}
				}
				else{
					$category_name = "";
				}
				$this->table->add_row(
					form_checkbox('advert[]',$advert->advert_id),
					img(array('src'=>base_url().'assets/adverts/thumbs/'.$advert->advert_poster,'alt'=>$advert->advert_name)),
					$advert->ad_position_name,
					$category_name,
					$advert->advert_name,
					date('jS M Y H:i a',strtotime($advert->advert_date)),
					anchor("admin/adverts/update_advert/$advert->advert_id/".$page,'Edit'),
					anchor("admin/adverts/delete_advert/$advert->advert_id/".$page,'Delete'),
					$status
				);
			}
		} else {
			$this->table->add_row('There are no adverts to show here :-|');
		}
		//$this->load->view("adverts/advert_list",$data);
		$this->template->content->view('adverts/advert_list',$data);
		$this->template->publish();
	}
	
	/**
	 * View the details of a adverts
	 * 
	 * @param	int	$advert_id
	 * 
	 */	
	function view_advert($advert_id)
	{
		// Set the template to use for this page
		//$this->template->set_template('templates/admin_template');
		
		$data['details'] = $this->adverts_model->get_advert_details($advert_id);
		$data['features'] = $this->adverts_model->get_advert_features($advert_id);
		$this->load->view("adverts/view_advert", $data);
		//$this->template->publish();
	}
	
	/**
	 * Add a new advert to the system
	 * 
	 */
	function add_advert()
	{
		// Set the template to use for this page
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('advert_name','advert Name','trim|required|xss_clean');
		$this->form_validation->set_rules('ad_position_id','Advert Position','trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('category_id','Advert Category','trim|xss_clean');
		
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
					$this->template->content->view("adverts/add_advert_form",$data);
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
				$this->template->content->view("adverts/add_advert_form",$data);
				$this->template->publish();
			}
			
			else{
				if($this->input->post("category_id") > 0){
					$this->adverts_model->deactivate_advert2($this->input->post("category_id"));
				}
				$advert_id = $this->adverts_model->add_advert($file_name);
				redirect('admin/adverts');
			}
		} 
		
		else {
			
			$this->template->content->view("adverts/add_advert_form");
			$this->template->publish();
		}
	}
	
	/**
	 * Edit the details of an advert
	 * 
	 * @param	int	$advert_id
	 * 
	 */
	function update_advert($advert_id, $page)
	{
		$data['details'] = $this->adverts_model->get_advert_details($advert_id);
		$data['page'] = $page;
		
		// Set the template to use for this page
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('advert_name','advert Name','trim|required|xss_clean');
		$this->form_validation->set_rules('ad_position_id','Advert Position','trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('category_id','Advert Category','trim|xss_clean');
		
		if($this->form_validation->run())
		{
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
					$this->template->content->view("adverts/add_advert_form",$data);
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
				$file_name = $this->input->post("advert_poster");
			}
			if($this->input->post("category_id") > 0){
				$this->adverts_model->deactivate_advert2($this->input->post("category_id"));
			}
			$product_id = $this->adverts_model->update_advert($advert_id, $file_name);
			redirect('admin/adverts/index/'.$page);
		} 
		
		else {
			$this->template->content->view("adverts/edit_advert_form", $data);
			$this->template->publish();
		}
	}
	
	function activate_advert($advert_id, $page, $category_id)
	{
		if($category_id > 0){
			$this->adverts_model->deactivate_advert2($category_id);
		}
		$this->adverts_model->activate_advert($advert_id);
		redirect("admin/adverts/index/".$page);
	}
	
	function deactivate_advert($advert_id, $page)
	{
		$this->adverts_model->deactivate_advert($advert_id);
		redirect("admin/adverts/index/".$page);
	}
	
	function delete_advert($advert_id, $page)
	{
		$this->adverts_model->delete_advert($advert_id);
		redirect("admin/adverts/index/".$page);
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
}