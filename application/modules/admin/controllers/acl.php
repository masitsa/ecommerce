<?php
require_once "./application/modules/admin/controllers/admin.php";
class Acl extends Admin
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','html'));
		$this->load->library(array('form_validation','email'));
		$this->load->model('acl_model');
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}
	
	function index()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/acl/index';
		$this->db->where('parent_id',0);
		$config['total_rows'] = $this->db->count_all_results('acl_resources');
		$config['per_page'] = 10;
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
		$data['parents'] = $this->acl_model->get_parent_resources($config['per_page'],$this->uri->segment(3));
		$this->template->content->view('acl/dashboard',$data);
		$this->template->publish();
	}
	
	// Generate the main menu
	function show_main_menu()
	{
		$data['url'] = explode("/",uri_string());
		$data['main_menu'] = $this->acl_model->get_main_menus();
		return $data;
	}
	
	// Generate the main menu
	function show_fancy_main_menu()
	{
		$data['url'] = explode("/",uri_string());
		$data['main_menu'] = $this->acl_model->get_main_menus();
		return $data;
	}
	
	// Generate the sub menu for a module
	function show_sub_menu()
	{
		$main_menu = $this->acl_model->get_main_menus();
		foreach($main_menu as $paths) {
			$path = explode("/",$paths->url);
			$modules[] = $path[1];
		}
		if(!in_array($this->uri->segment(2),$modules))
			$url = 'admin/index';
		else
			$url = $this->uri->segment(1).'/'.$this->uri->segment(2);
		$menu_id = $this->acl_model->get_menu_id($url);
		if($this->acl_model->has_children($menu_id)) {
			$submenu_items = $this->acl_model->get_submenus($menu_id);
			foreach($submenu_items as $submenu_item) {
				if($this->acl_model->has_access($submenu_item->url))
					echo("<li>".anchor($submenu_item->url,$submenu_item->resource_name)."</li>");                
			}
		}
	}
	
	// Add a resource to the system - controller or function
    function add_resource()
    {
        $this->form_validation->set_message('is_natural_no_zero','The %s field is required');
        $this->form_validation->set_rules('resource_name','Resource Name','trim|required|xss_clean');
        $this->form_validation->set_rules('resource_type','Resource Type','trim|required|xss_clean|is_natural_no_zero');
        $this->form_validation->set_rules('position','Position','trim|required|xss_clean|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('menu','Menu','trim|required|xss_clean');
        $this->form_validation->set_rules('url','URL','trim|required|xss_clean');
        if($this->form_validation->run()) {
			$this->acl_model->add_resource();
			redirect('admin/acl');
        } else {
			$this->template->content->view('acl/add_resource_form');
			$this->template->publish();
		}
    }
    
    // Edit the details of a resource
    function edit_resource($r_id)
    {
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
        $data['details'] = $this->acl_model->get_resource_details($r_id);
		$this->form_validation->set_rules('resource_name','Resource Name','trim|required|xss_clean');
        $this->form_validation->set_rules('position','Position','trim|required|xss_clean|numeric|is_natural_no_zero');
		$this->form_validation->set_rules('url','URL','trim|required|xss_clean');
        $this->form_validation->set_rules('menu','Menu','trim|required|xss_clean');
        if($this->form_validation->run()){
            $this->acl_model->edit_resource($r_id);
			redirect('admin/acl');
        } else {
            $this->template->content->view('acl/edit_resource_form',$data);
			$this->template->publish();
        }
    }
    
    // Delete a resource from the DB
    function delete_resource($r_id)
    {
        $this->acl_model->delete_resource($r_id);
		redirect('admin/acl');
    }
	
	// View all rules tied to user roles
	function role_rules()
	{
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
		$data['roles'] = $this->acl_model->get_all_rule_roles();
		$this->template->content->view('acl/role_rules',$data);
		$this->template->publish();
	}
	
	// View all rules tied to individual users
	function user_rules()
	{
		$this->load->library('table');
		$tmpl = array (
		  'table_open'          => '<table id="box-table-a">',
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
		$data['users'] = $this->acl_model->get_all_user_rules(); // Get all users that have rules tied to them
		$this->template->content->view('acl/user_rules',$data);
		$this->template->publish();
	}
	
	// Add a rule to the system based on the user role
	function add_role_rule()
	{
		$data['roles'] = $this->acl_model->get_all_roles();
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('role_id','Role','trim|required|xss_clean');
		$this->form_validation->set_rules('resource_id[]','Resource','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('rule','Rule','trim|required|xss_clean');
		if($this->form_validation->run()) {
			$this->acl_model->add_role_rule();
			redirect('admin/acl/role_rules');
		} else {
			$this->template->content->view('acl/add_role_rule_form',$data);
			$this->template->publish();
		}
	}
	
	// Add a rule to the system based on the user id
	function add_user_rule()
	{
		$data['users'] = $this->acl_model->get_all_users();
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('user_id','User','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('resource_id[]','Resource','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('rule','rule','trim|required|xss_clean');
		if($this->form_validation->run()) {
			$this->acl_model->add_user_rule();
			redirect('admin/acl/user_rules');
		} else {
			$this->template->content->view('acl/add_user_rule_form',$data);
			$this->template->publish();
		}
	}
	
	// Allow a user rule
	function allow_user_rule($r_id)
	{
		$this->acl_model->allow_user_rule($r_id);
		redirect('admin/acl/user_rules');
	}
	
	// Deny a user rule
	function deny_user_rule($r_id)
	{
		$this->acl_model->deny_user_rule($r_id);
		redirect('admin/acl/user_rules');
	}
	
	// Allow a role rule
	function allow_role_rule($r_id)
	{
		$this->acl_model->allow_role_rule($r_id);
		redirect('admin/acl/role_rules');
	}
	
	// Deny a role rule
	function deny_role_rule($r_id)
	{
		$this->acl_model->deny_role_rule($r_id);
		redirect('admin/acl/role_rules');
	}
	
	// Delete a rule
	function delete_rule($r_id)
	{
		$this->acl_model->delete_rule($r_id);
		redirect('admin/acl');
	}
	
	function unauthorised()
	{
		// Set the template to use for this page
		$this->template->set_template('themes/'.$this->theme.'/templates/admin_unauthorised_template');
		
		if(!$this->session->userdata('logged_in')) 
			redirect('auth/login');
		$this->template->content->view('acl/unauthorized');
		$this->template->publish();
	}
	
	/**
	* Activate a resource
	* 
	* @param int r_id
	* 
	*/
	function activate_resource($r_id)
	{
		$this->acl_model->activate_resource($r_id);
		redirect('admin/acl');
	}
	
	/**
	* Deactivate a resource
	* 
	* @param int r_id
	* 
	*/
	function deactivate_resource($r_id)
	{
		$this->acl_model->deactivate_resource($r_id);
		redirect('admin/acl');
	}
}