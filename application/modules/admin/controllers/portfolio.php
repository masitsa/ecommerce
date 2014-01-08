<?php
require_once "./application/modules/admin/controllers/admin.php";
class Portfolio extends Admin
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('route_lib'));
		$this->load->model('portfolio_model');
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}
	
	/**
	*  Load the default view for the module
	* 
	*/
	function index()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/portfolio/index';
		$config['total_rows'] = $this->db->count_all('portfolio_projects');
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
		$this->table->set_heading('Project Image','Project Title','Details','Client','Project Date',$axns);
		$projects = $this->portfolio_model->get_all_projects_list($config['per_page'],$this->uri->segment(4));
		if(!empty($projects)) {
			foreach($projects as $project) {
				$this->table->add_row(img(array('src'=>base_url().'assets/portfolio/images/'.$project->image, 'class' => 'thumbnail', 'alt'=>'',  'style'=>'width: 200px; height: 200px;')),$project->project,$project->description,$project->client,date("jS M Y",strtotime($project->date)),
				anchor("admin/portfolio/edit_project/$project->project_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
				anchor("admin/portfolio/delete_project/$project->project_id",'<i class="icon-trash butn butn-danger"></i>',array('title'=>'Delete','onClick'=>'return confirm(\'Do you really want to delete this project?\')')));
			}
		} else {
			$this->table->clear();
			$this->table->add_row('There are no projects in your portfolio. Would you like to add a new project??');
		}
		$this->template->content->view('portfolio/dashboard');
		$this->template->publish();
	}
	
	/**
	* Add a project to the portfolio
	* 
	*/
	function add_project()
	{
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->load->helper('MY_path_helper');
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('project_title','Project Title','trim|required|xss_clean');
		$this->form_validation->set_rules('client','Client','trim|required|xss_clean');
		$this->form_validation->set_rules('category','Project Category','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('description','Project Description','trim|required|xss_clean');
		$this->form_validation->set_rules('project_date','Project Date','trim|required|xss_clean');
		if($this->form_validation->run())
		{
			$config['upload_path'] = absolute_path().'assets/portfolio/images/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']  = 1024 * 8;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('userfile')) {
				$status = 'error';
				$data['file_error'] = $this->upload->display_errors('','');
				$this->template->content->view('portfolio/add_project_form',$data);
				$this->template->publish();
			} else {
				$file_data = $this->upload->data();
				if($file_data) {
					$status = "success";
					$data = array('project'=>$this->input->post('project_title'),'client' => $this->input->post('client'),'image' => $file_data['file_name'],
						'description' => $this->input->post('description'),'live_preview' => $this->input->post('live_preview'),'category' => $this->input->post('category'),
						'date' => $this->input->post('project_date'));
					$this->portfolio_model->save_project($data);
					$this->route_lib->redirect_with_message('admin/portfolio','The project was successfully added.');
				} else {
					unlink($file_data['full_path']);
					$status = "error";
					$this->route_lib->redirect_with_error('portfolio/add_project','Something went wrong when uploading the file, please try again.');
				}
			}
		} else {
			$data['file_error'] = '';
			$this->template->content->view('portfolio/add_project_form',$data);
			$this->template->publish();
		}
	}

	/**
	*  Edit the details of a project
	* 
	*  @param int portfolio_id
	* 
	*/
	function edit_project($project_id)
	{
		$this->template->stylesheet->add(base_url().'css/bootstrap-fileupload.min.css');
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		$this->template->javascript->add(base_url().'js/bootstrap-fileupload.min.js');
		
		$this->load->helper('MY_path_helper');
		$portfolio['details'] = $this->portfolio_model->get_portfolio_details($project_id);
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_rules('portfolio_title','Portfolio Title','trim|required|xss_clean');
		$this->form_validation->set_rules('client','Client','trim|required|xss_clean');
		$this->form_validation->set_rules('category','Category','trim|required|xss_clean');
		$this->form_validation->set_rules('description','Portfolio Content','trim|required|xss_clean');
		$this->form_validation->set_rules('project_date','Project Date','trim|required|xss_clean');
		if($this->form_validation->run())
		{
			$config['upload_path'] = absolute_path().'assets/portfolio/images/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']  = 1024 * 8;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			
			$success = FALSE;
			if ($this->upload->do_upload('userfile'))
			{
				$file_data = $this->upload->data();
				if($file_data)
				{
					$success = TRUE;
					$msg = "File successfully uploaded";
				} else {
					unlink($file_data['full_path']);
					$success = FALSE;
					$msg = "Something went wrong when saving the file, please try again.";
				}
			}
			if($success) {
				unlink($config['upload_path'].$portfolio['details']->image);
				$data = array( 'project' => $this->input->post('portfolio_title'),'client' => $this->input->post('client'),'description' => $this->input->post('description'),'image' => $file_data['file_name'],'live_preview' => $this->input->post('live_preview'),'category' => $this->input->post('category'),'date' => $this->input->post('project_date'));
				$this->portfolio_model->update_project($data);
				$this->route_lib->redirect_with_message('admin/portfolio','The project was successfully updated.');
			} else {
				$data = array( 'project' => $this->input->post('portfolio_title'),'client' => $this->input->post('client'),'description' => $this->input->post('description'),'live_preview' => $this->input->post('live_preview'),'category' => $this->input->post('category'),'date' => $this->input->post('project_date'));
				$this->portfolio_model->update_project($data);
				$this->route_lib->redirect_with_message('admin/portfolio','The project was successfully updated.');
			}
		} else {
			$this->template->content->view('portfolio/edit_project_form',$portfolio);
			$this->template->publish();
		}
	}
	
	/**
	* Permanently delete a project from the system
	* Potentially dangerous. No way to recover from this.
	* Seriously, it's the end of the line for this project 
	* 
	* @param int project_id
	* 
	*/
	function delete_project($project_id)
	{
		$this->load->helper('MY_path_helper');
		$image_name = $this->portfolio_model->delete_project($project_id);
		unlink(absolute_path().'assets/portfolio/images/'.$image_name);
		$this->route_lib->redirect_with_message('admin/portfolio','The project was successfully deleted.');
	}
	
	/**
	* View a list of all project categories 
	* 
	*/
	function categories()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/portfolio/categories';
		$config['total_rows'] = $this->db->count_all('project_categories');
		$config['uri_segment'] = 4;
		$config['per_page'] = 20;
		$config['num_links'] = 10;
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
		$this->table->set_heading('Project category','','');
		$categories = $this->portfolio_model->get_all_categories($config['per_page'],$this->uri->segment(4));
		$this->table->set_template($tmpl);      
		
		//-- Header Row
		if($this->uri->segment(2)=="show_category")
			$pageno = $this->uri->segment(3);
		foreach($categories as $category )
		{
			$this->table->add_row($category->name,
				anchor("admin/portfolio/edit_category/$category->category_id",'<i class="icon-edit butn butn-success"></i>', array('title'=>'Edit')),
				anchor("admin/portfolio/delete_category/$category->category_id/",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete this project category?\');', 'title'=>'Delete'))
			);
		}
		$this->template->content->view('portfolio/manage_categories');
		$this->template->publish();
    }
	
	/**
	* Add a new project category
	* 
	*/
	function add_category()
	{
		$this->form_validation->set_rules('category', 'Project Category', 'trim|required|xss_clean');
		if ($this->form_validation->run()){
			$this->portfolio_model->create_category();
			redirect('admin/portfolio/categories'); 
		} else {
			$this->categories();
		}
	}
	
	/**
	* Edit the details of a project category 
	* 
	* @param int category_id
	* 
	*/
	function edit_category($category_id)
	{
		$this->form_validation->set_rules('category', 'Project Category', 'trim|required|xss_clean');
		if ($this->form_validation->run()){
			$this->portfolio_model->update_category($category_id);
            redirect('admin/portfolio/categories'); 
		}else{
			$data['details'] = $this->portfolio_model->select_edit_category($category_id);
			$this->template->content->view('portfolio/edit_category_form',$data);
			$this->template->publish();
		}	
	}	
	
	/**
	* Delete a project category 
	* 
	* @param int category_id
	* 
	*/
	function delete_category($category_id)
	{
		$this->portfolio_model->delete_category($category_id);
		redirect('admin/portfolio/categories');
	}
}