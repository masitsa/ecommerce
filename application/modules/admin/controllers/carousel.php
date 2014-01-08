<?php
require_once "./application/modules/admin/controllers/admin.php";
class Carousel extends Admin
{
	private $data = array(
		'dir' => array('original'=>'./assets/carousel/original/','thumb'=>'./assets/carousel/thumbs/'),
		'total' => 0,
		'images' => array(),
		'error' => ''
    );
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('carousel_model');
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}
	
	/**
	* View the carousel manager
	* A table of all pictures uploaded to the carousel already plus actions
	* 
	*/
	function index()
	{
		$pics = $this->carousel_model->get_carousel_pictures();
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
		$this->table->set_template($tmpl);
		$axns = array('data'=>'Actions','colspan'=>3);     
		
		$this->table->set_heading('Picture','Caption',$axns);
		if(!empty($pics)){
			foreach($pics as $pic){
				$this->table->add_row(img(array('src'=>base_url().'assets/carousel/thumbs/thumb_'.$pic->pic_name)),
				$pic->pic_caption,anchor("admin/carousel/edit_carousel_pic/$pic->pic_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
				anchor("admin/carousel/delete_carousel_pic/$pic->pic_id",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete this picture?\');','title'=>'Delete')));
			}
		}
		$this->template->content->view('carousel/carousel_manager');
		$this->template->publish();
	}
	
	/**
	* Add a carousel picture and attached caption
	*  
	*/
	function add_carousel_pic()
	{
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->form_validation->set_rules('caption','Caption','trim|required|xss_clean');
		$c_upload['upload_path']    = $this->data['dir']['original'];
       	$c_upload['allowed_types']  = 'gif|jpg|png|jpeg|x-png';
        $c_upload['max_size']       = 1024 * 8;
		$c_upload['max_width']      = '5000';
		$c_upload['max_height']     = '5000';
		$c_upload['encrypt_name'] = TRUE;
       	$this->load->library('upload', $c_upload);
        if($this->form_validation->run()) 
		{
			if ($this->upload->do_upload()) {
				$this->load->library('image_lib');
	           	$img = $this->upload->data();
				// Create thumbnail
	        	$new_image = $this->data['dir']['thumb'].'thumb_'.$img['file_name'];
	        	$c_img_lib = array(
		            'image_library'     => 'gd2',
		            'source_image'      => $img['full_path'],
		            'maintain_ratio'    => TRUE,
		            'width'             => 100,
		            'height'            => 100,
		            'new_image'         => $new_image
	        	);
				$this->image_lib->initialize($c_img_lib);
	        	$this->image_lib->resize();
				$this->image_lib->clear();
				$this->carousel_model->save_pic_details($img['file_name']);
				redirect('admin/carousel');
	    	} else {
	        	$this->data['error'] = $this->upload->display_errors();
				$this->template->content->view('carousel/upload_carousel_pic',$this->data);
				$this->template->publish();
	    	}
		} else {
			$this->template->content->view('carousel/upload_carousel_pic',$this->data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit the pictures that appear on the homepage carousel
	*  
	* @param int $pic_id
	* 
	*/
	function edit_carousel_pic($pic_id)
	{
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$data['details'] = $this->carousel_model->get_picture_details($pic_id);
		$this->form_validation->set_rules('caption','Caption','trim|required|xss_clean');		
		$c_upload['upload_path']    = $this->data['dir']['original'];
       	$c_upload['allowed_types']  = 'gif|jpg|png|jpeg|x-png';
        $c_upload['max_size']       = 1024 * 8;
		$c_upload['max_width']      = '5000';
		$c_upload['max_height']     = '5000';
		$c_upload['encrypt_name'] = TRUE;
       	$this->load->library('upload', $c_upload);
        if($this->form_validation->run()) 
		{
			if ($this->upload->do_upload('userfile')) {
				$this->load->library('image_lib');
	           	$img = $this->upload->data();
				// Create thumbnail
	        	$new_image = $this->data['dir']['thumb'].'thumb_'.$img['file_name'];
	        	$c_img_lib = array(
		            'image_library'     => 'gd2',
		            'source_image'      => $img['full_path'],
		            'maintain_ratio'    => TRUE,
		            'width'             => 100,
		            'height'            => 100,
		            'new_image'         => $new_image
	        	);
				$this->image_lib->initialize($c_img_lib);
	        	$this->image_lib->resize();
				$this->image_lib->clear();
				$details = array('pic_name'=>$img['file_name'],'pic_caption'=>$this->input->post('caption'));
				unlink($this->data['dir']['original'].$data['details']->pic_name);
				unlink($this->data['dir']['thumb'].'thumb_'.$data['details']->pic_name);
	    	} else {
	        	$details = array('pic_caption'=>$this->input->post('caption'));
	    	}
			$this->carousel_model->edit_carousel_pic($pic_id,$details);
			redirect('admin/carousel');
		} else {
			$this->template->content->view('carousel/edit_carousel_pic_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Delete a carousel pic and caption
	*
	* @param int $pic_id
	* 
	*/
	function delete_carousel_pic($pic_id)
	{
		$details = $this->carousel_model->get_picture_details($pic_id);
		if($confirm = $this->carousel_model->delete_carousel_picture($pic_id)){
			unlink($this->data['dir']['original'].$details->pic_name);
	        unlink($this->data['dir']['thumb'].'thumb_'.$details->pic_name);
	        redirect('admin/carousel');
		}
	}
}