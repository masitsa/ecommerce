<?php
require_once "./application/modules/admin/controllers/admin.php";
class gallery extends Admin
{
	private $data = array(
		'dir' => './assets/gallery/images/',
		'total' => 0,
		'images' => array(),
		'error' => ''
    );
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('route_lib'));
		$this->load->model('gallery_model');
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}
	
	/**
	*  Load the default view for the module
	* 
	*/
	function index()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/gallery/index';
		$config['total_rows'] = $this->db->count_all('gallery_photos');
		$config['uri_segment'] = 4;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
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
		$this->table->set_heading('Image','Description','Gallery',$axns);
		$gallerys = $this->gallery_model->get_all_gallery_list($config['per_page'],$this->uri->segment(4));
		if(!empty($gallerys)) {
			foreach($gallerys as $gallery) {
				$this->table->add_row(img(array('src'=>base_url().'assets/gallery/images/'.$gallery->image, 'class' => 'thumbnail', 'alt'=>'', 'style'=>'width: 200px; height: 200px;')),$gallery->description,$gallery->name,
				anchor("admin/gallery/edit_gallery/$gallery->photo_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
				anchor("admin/gallery/delete_gallery/$gallery->photo_id",'<i class="icon-trash butn butn-danger"></i>',array('title'=>'Delete','onClick'=>'return confirm(\'Do you really want to delete this image?\')')));
			}
		} else {
			$this->table->clear();
			$this->table->add_row('There are no photos added to the system. Would you like to add a new photo??');
		}
		$this->template->content->view('gallery/dashboard');
		$this->template->publish();
	}
	
	/**
	*  Add a gallery to the system
	* 
	*/
	function add_gallery()
	{
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->load->helper('MY_path_helper');
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('gallery','Gallery','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('caption','Caption','trim|required|xss_clean');
		if($this->form_validation->run())
		{
			$config['upload_path'] = absolute_path().'assets/gallery/images/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']  = 1024 * 8;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('userfile')) {
				$status = 'error';
				$data['file_error'] = $this->upload->display_errors('','');
				$this->template->content->view('gallery/add_gallery_form',$data);
				$this->template->publish();
			} else {
				$file_data = $this->upload->data();
				if($file_data) {
					$status = "success";
					chmod(absolute_path().'assets/gallery/images/'.$file_data['file_name'], 0777);
					$data = array('image' => $file_data['file_name'],'description' => $this->input->post('caption'),'gallery' => $this->input->post('gallery'));
					$this->gallery_model->save_gallery($data);
					$this->route_lib->redirect_with_message('admin/gallery','The image was successfully uploaded.');
				} else {
					unlink($file_data['full_path']);
					$status = "error";
					$this->route_lib->redirect_with_error('admin/add_gallery','Something went wrong when uploading the file, please try again.');
				}
			}
		} else {
			$data['file_error'] = '';
			$this->template->content->view('gallery/add_gallery_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	*  Edit the details of a gallery
	* 
	*  @param int gallery_uuid
	* 
	*/
	function edit_gallery($gallery_id)
	{
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->load->helper('MY_path_helper');
		$gallery['details'] = $this->gallery_model->get_gallery_details($gallery_id);	
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_rules('gallery','Gallery','trim|required|xss_clean');
		$this->form_validation->set_rules('caption','Caption','trim|required|xss_clean');
		if($this->form_validation->run())
		{
			$config['upload_path'] = absolute_path().'assets/gallery/images/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']  = 1024 * 8;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			
			$success = FALSE;
			if (!$this->upload->do_upload('userfile')) {
				$status = 'error';
				$success = FALSE;
				$msg = $this->upload->display_errors('', '');
			} else {
				$file_data = $this->upload->data();
				if($file_data) {
					$status = "success";
					$success = TRUE;
					$msg = "File successfully uploaded";
				} else {
					unlink($file_data['full_path']);
					$status = "error";
					$success = FALSE;
					$msg = "Something went wrong when saving the file, please try again.";
				}
			}
			if($success) {
				unlink($config['upload_path'].$gallery['details']->image);
				$data = array('image' => $file_data['file_name'],'description' => $this->input->post('caption'),'gallery' => $this->input->post('gallery'));
				$this->gallery_model->update_gallery($data);
				$this->route_lib->redirect_with_message('admin/gallery','The image was successfully updated.');
			} else {
				$data = array('description' => $this->input->post('caption'),'gallery' => $this->input->post('gallery'));
				$this->gallery_model->update_gallery($data);
				$this->route_lib->redirect_with_message('admin/gallery','The image was successfully updated.');
			}
		} else {
			$this->template->content->view('gallery/edit_gallery_form',$gallery);
			$this->template->publish();
		}
	}
	
	/**
	* Permanently delete a gallery from the system
	* Potentially dangerous. No way to recover from this.
	* Seriously, it's the end of the line for this gallery 
	* 
	* @param int gallery_id
	* 
	*/
	function delete_gallery($gallery_id)
	{
		$this->gallery_model->delete_gallery($gallery_id);
		$this->route_lib->redirect_with_message('admin/gallery','The image was successfully deleted.');
	}
	/**
	 * View all gallery categories
	 * 
	 * @return	null
	 * 
	 */
	function categories()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/gallery/categories';
		$config['total_rows'] = $this->db->count_all('gallery');
		$config['per_page'] = '10';
		$config['num_links'] = '10';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
		$this->load->library('table');
		$tmpl = array (
			'table_open'          => '<table class="table table-striped table-hover">',
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
		$this->table->set_heading('Gallery','','');
		$categorys = $this->gallery_model->get_type_list($config['per_page'],$this->uri->segment(3));
		$this->table->set_template($tmpl);
		
		if($this->uri->segment(2)=="show_category")
			$pageno = $this->uri->segment(3);
		foreach($categorys as $category )
		{
			if($this->gallery_model->gallery_has_photos($category->gallery_id))
				$delete = anchor("admin/gallery/delete_category/$category->gallery_id/",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'This gallery has photos attached. If you delete it, you will also delete these photos. Do you want to delete them all?\');', 'title'=>'Delete'));
			else
				$delete = anchor("admin/gallery/delete_category/$category->gallery_id/",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete the gallery?\');', 'title'=>'Delete'));
			$this->table->add_row(
				$category->name,
				anchor("admin/gallery/edit_category/$category->gallery_id",'<i class="icon-edit butn butn-success"></i>',array('rel'=>'facebox','title'=>'Edit')),
				$delete
			);
		}
		$this->template->content->view('gallery/manage_category');
		$this->template->publish();
    }
	
	/**
	 * Edit a gallery category
	 * Based on the category id
	 * 
	 * @return	null
	 * 
	 */
	function edit_category($typeid)
	{
		$data['details'] = $this->gallery_model->select_edit_type($typeid);
		$this->load->view('gallery/edit_category_form',$data);
	}
	
	function edit_gallery_type()
	{
		$this->form_validation->set_rules('category', 'Client Type', 'trim|required|xss_clean');
		if ($this->form_validation->run()) {
			$this->gallery_model->update_gallery_type($this->uri->segment(4));
			redirect('admin/gallery/categories'); 
		} else {
			$this->edit_category();
		}
	}
	
	/**
	 * Add a new gallery category to the system
	 * 
	 * @return	null
	 * 
	 */
	function add_gallery_category()
	{
		$this->form_validation->set_rules('gallery', 'Gallery', 'trim|required|xss_clean');
		if ($this->form_validation->run()) {
			$data_type = '';
			$name = $this->input->post('gallery');
			$class = explode(' ',$name);
			$cc = count($class);
			for($i=0;$i<$cc;$i++)
				$data_type .= $class[$i];
			$data = array('name'=>$name,'data_type'=>$data_type);
			$this->gallery_model->create_gallery_type($data);
			redirect('admin/gallery/categories'); 
		} else {
			$this->categories();
		}	
	}
	
	/**
	 * Delete  a gallery category
	 * This will also delete all photos assigned to the gallery which will help prevent
	 * orphaned files filling up the server
	 * 
	 * @return	null
	 * 
	 */
	function delete_category($typeid)
	{
		$this->load->helper('MY_path_helper');
		if($images = $this->gallery_model->delete_type($typeid)) {
			foreach($images as $image)
				unlink($this->data['dir'].$image->image);
		}
		redirect('admin/gallery/categories');
	}
}