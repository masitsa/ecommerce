<?php
require_once "./application/modules/site/controllers/site.php";
class gallery extends Site
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site_model');
		$this->load->model('admin/gallery_model');
	}
	
	function index()
	{
		// Add stylesheets dynamically
		$this->template->stylesheet->add(base_url().'css/misty/prettyPhoto.css');
		
		// Add javascript dynamically
		$this->template->javascript->add(base_url().'js/misty/misty-jquery.quicksand.js');
		
		// Set the title of the page
        $this->template->title->set('Gallery');
		
		// Get the details to be displayed on the page
		$data['details'] = $this->site_model->get_page_details('gallery');
		$this->template->set_template('templates/gallery_page');
		$this->template->content->view('gallery/gallery',$data);
		$this->template->publish();
		
		// Register a new page view
		$this->site_model->increment_page_view($data['details']->page_uuid);
	}
	
	function project($gallery_id)
	{
		$gallery['details'] = $this->gallery_model->get_gallery_details($gallery_id);
		
		// Set the title of the page
        $this->template->title->set($gallery['details']->name);
		
		$this->template->set_template('templates/gallery_page');
		$this->template->content->view('gallery/project',$gallery);
		$this->template->publish();
	}
}