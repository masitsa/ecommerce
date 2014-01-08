<?php
require_once "./application/modules/site/controllers/site.php";
class Portfolio extends Site
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site_model');
		$this->load->model('admin/portfolio_model');
	}
	
	/**
	 * View the default view for the portfolio
	 * Lists all projects
	 *
	 */
	function index()
	{
		// Add javascript dynamically
		$this->template->javascript->add(base_url().'js/misty/misty-jquery.quicksand.js');
		
		// Get the details to be displayed on the page
		$data['details'] = $this->site_model->get_page_details('portfolio');
		
		// Set the title of the page
        $this->template->title->set('Portfolio');
		
		$this->template->set_template('templates/portfolio_page');
		$this->template->content->view('portfolio/portfolio',$data);
		$this->template->publish();
		
		// Register a new page view
		$this->site_model->increment_page_view($data['details']->page_uuid);
	}
	
	/**
	 * View the details of a particular project based on project_id
	 *
	 * @param int project_id
	 *
	 */
	function project($project_id) 
	{
		// Get the details of a project
		$project['details'] = $this->portfolio_model->get_portfolio_details($project_id);
		
		// Get the details to be displayed on the page
		//$project['details'] = $this->site_model->get_page_details('portfolio');
		
		// Set the title of the page
        $this->template->title->set($project['details']->project);
		
		$this->template->set_template('templates/portfolio_page');
		$this->template->content->view('portfolio/project',$project);
		$this->template->publish();
	}
}