<?php
require_once "./application/modules/admin/controllers/admin.php";
class Pages extends Admin
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('route_lib'));
		$this->load->model('pages_model');
		$this->load->helper('text');
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}
	
	/**
	*  Load the default view for the module
	* 
	*/
	function index()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/pages/index';
		$config['total_rows'] = $this->db->count_all('pages');
		$config['uri_segment'] = 4;
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
		$axns = array('data'=>'Actions','colspan'=>3);
		$this->table->set_heading('Page Title','Author','Type','Created',$axns);
		$pages = $this->pages_model->get_all_pages_list($config['per_page'],$this->uri->segment(4));
		if(!empty($pages)) {
			foreach($pages as $page) {
				$type = $page->bundled == 1?'System':'User Defined';
				$status = $page->active == 1?anchor("admin/pages/deactivate_page/$page->page_uuid",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate')):
					anchor("admin/pages/activate_page/$page->page_uuid",'<i class="icon-unlock butn butn-info"></i>',array('title'=>'Activate'));
				if($page->bundled == 1) {
					$this->table->add_row($page->page_title,$page->fullname,$type,date("jS M Y",strtotime($page->created)),
					anchor("admin/pages/edit_$page->page_url/$page->page_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
					anchor("site/$page->page_url",'<i class="icon-eye-open butn butn-info"></i>',array('title'=>'Preview Page','target'=>'frontend')));
				} else {
					$this->table->add_row($page->page_title,$page->fullname,$type,date("jS M Y",strtotime($page->created)),
					anchor("admin/pages/edit_page/$page->page_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
					anchor("site/page/$page->page_url",'<i class="icon-eye-open butn butn-info"></i>',array('title'=>'Preview Page','target'=>'frontend')),$status,
					anchor("admin/pages/delete_page/$page->page_uuid",'<i class="icon-trash butn butn-danger"></i>',array('title'=>'Delete','onClick'=>'return confirm(\'Do you really want to delete this page?\')')));
				}
			}
		} else {
			$this->table->clear();
			$this->table->add_row('There are no pages added to the system. Would you like to add a new page??');
		}
		$this->template->content->view('pages/dashboard');
		$this->template->publish();
	}
	
	/**
	*  Add a page to the system
	* 
	*/
	function add_page()
	{
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_title','Page Title','trim|alpha_dash_space|min_length[4]|required|xss_clean');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_layout','Page Layout','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('parent','Page Parent','trim|required|xss_clean');
		$this->form_validation->set_rules('post_page','Post Page','trim|xss_clean');
		$this->form_validation->set_rules('page_content_left','Left Column Content','trim|xss_clean');
		$this->form_validation->set_rules('page_content_middle','Middle Column Content','trim|xss_clean');
		$this->form_validation->set_rules('page_content_right','Right Column Content','trim|xss_clean');
		if($this->form_validation->run()) {
			if($this->pages_model->is_page_title_unique($this->input->post('page_title'))) {
				$this->pages_model->save_page();
				redirect('admin/pages');
			} else {
				$data['page_title_error'] = 'This page title is already taken. Please choose another title.';
				$this->template->content->view('pages/add_page_form',$data);
				$this->template->publish();
			}
		} else {
			$this->template->content->view('pages/add_page_form');
			$this->template->publish();
		}
	}
	
	/**
	*  Edit the details of a page
	* 
	*  @param int page_uuid
	* 
	*/
	function edit_page($page_uuid)
	{
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		
		$data['details'] = $this->pages_model->get_page_details($page_uuid);
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_title','Page Title','trim|alpha_dash_space|min_length[4]|required|xss_clean');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_layout','Page Layout','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('parent','Page Parent','trim|required|xss_clean');
		$this->form_validation->set_rules('post_page','Post Page','trim|xss_clean');
		$this->form_validation->set_rules('page_content_left','Left Column Content','trim|xss_clean');
		$this->form_validation->set_rules('page_content_middle','Middle Column Content','trim|xss_clean');
		$this->form_validation->set_rules('page_content_right','Right Column Content','trim|xss_clean');
		if($this->form_validation->run()) {
			$this->pages_model->edit_page($page_uuid);
			redirect('admin/pages');
		} else {
			$this->template->content->view('pages/edit_page_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Permanently delete a page from the system
	* Potentially dangerous. No way to recover from this.
	* Seriously, it's the end of the line for this page 
	* 
	* @param int page_uuid
	* 
	*/
	function delete_page($page_uuid)
	{
		$this->pages_model->delete_page($page_uuid);
		redirect('admin/pages');
	}
	
	/**
	* Activate a page so that it is accessible to all
	*
	* @param int page_uuid
	* 
	*/
	function activate_page($page_uuid)
	{
		$this->pages_model->activate_page($page_uuid);
		redirect('admin/pages');
	}
	
	/**
	* Deactivate a page so that it is not accessible to anybody
	* 
	* @param int page_uuid
	* 
	*/
	function deactivate_page($page_uuid)
	{
		$this->pages_model->deactivate_page($page_uuid);
		redirect('admin/pages');
	}
	
	/**
	* Build the Pages widget for display on the dashboard
	* 
	*/
	function dashboard_widget()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/pages/index';
		$config['total_rows'] = $this->db->count_all('pages');
		$config['uri_segment'] = 4;
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
		$axns = array('data'=>'Actions','colspan'=>3);
		$this->table->set_heading('Page Title','Author','Type','Created',$axns);
		$pages = $this->pages_model->get_all_pages_list($config['per_page'],$this->uri->segment(4));
		if(!empty($pages)) {
			foreach($pages as $page) {
				$type = $page->bundled == 1?'System':'User Defined';
				$status = $page->active == 1?anchor("admin/pages/deactivate_page/$page->page_uuid",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate')):
					anchor("admin/pages/activate_page/$page->page_uuid",'<i class="icon-unlock butn butn-info"></i>',array('title'=>'Activate'));
				if($page->bundled == 1) {
					$this->table->add_row($page->page_title,$page->fullname,$type,date("jS M Y",strtotime($page->created)),
					anchor("admin/pages/edit_$page->page_url/$page->page_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
					anchor("site/$page->page_url",'<i class="icon-eye-open butn butn-info"></i>',array('title'=>'Preview Page','target'=>'frontend')));
				} else {
					$this->table->add_row($page->page_title,$page->fullname,$type,date("jS M Y",strtotime($page->created)),
					anchor("admin/pages/edit_page/$page->page_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
					anchor("site/page/$page->page_url",'<i class="icon-eye-open butn butn-info"></i>',array('title'=>'Preview Page','target'=>'frontend')),$status,
					anchor("admin/pages/delete_page/$page->page_uuid",'<i class="icon-trash butn butn-danger"></i>',array('title'=>'Delete','onClick'=>'return confirm(\'Do you really want to delete this page?\')')));
				}
			}
		} else {
			$this->table->clear();
			$this->table->add_row('There are no pages added to the system. Would you like to add a new page??');
		}
		echo $this->table->generate();
		echo("<div id='pagination'>".$this->pagination->create_links()."</div>");
	}
	
	/**
	* Edit the system homepage
	* 
	* @param int page_uuid
	* 
	*/
	function edit_home($page_uuid)
	{
		$this->template->javascript->add('//js.nicedit.com/nicEdit-latest.js');
		
		$data['details'] = $this->pages_model->get_page_details($page_uuid);
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_layout','Page Layout','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_content_left','Left Column Content','required|min_length[11]');
		$this->form_validation->set_rules('page_content_right','Right Column Content','required|min_length[11]');
		if($this->form_validation->run()) {
			$this->pages_model->edit_system_page($page_uuid);
			redirect('admin/pages');
		} else {
			$this->template->content->view('pages/edit_page_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit the system contact form
	*
	* @param int page_uuid
	*
	*/
	function edit_contact($page_uuid)
	{
		$this->template->javascript->add('//js.nicedit.com/nicEdit-latest.js');
		
		$data['details'] = $this->pages_model->get_page_details($page_uuid);
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_layout','Page Layout','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_content_left','Left Column Content','required|min_length[11]');
		$this->form_validation->set_rules('page_content_right','Right Column Content','required|min_length[11]');
		if($this->form_validation->run()) {
			$this->pages_model->edit_system_page($page_uuid);
			redirect('admin/pages');
		} else {
			$this->template->content->view('pages/edit_page_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit the dashboard of the blog page
	* 
	* @param int page_uuid
	* 
	*/
	function edit_blog($page_uuid)
	{
		$this->template->javascript->add('//js.nicedit.com/nicEdit-latest.js');
		
		$data['details'] = $this->pages_model->get_page_details($page_uuid);
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_layout','Page Layout','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_content_left','Left Column Content','required|min_length[11]');
		$this->form_validation->set_rules('page_content_right','Right Column Content','required|min_length[11]');
		if($this->form_validation->run()) {
			$this->pages_model->edit_system_page($page_uuid);
			redirect('admin/pages');
		} else {
			$this->template->content->view('pages/edit_page_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit the dashboard of the portfolio
	* 
	* @param int page_uuid
	* 
	*/
	function edit_portfolio($page_uuid)
	{
		$this->template->javascript->add('//js.nicedit.com/nicEdit-latest.js');
		
		$data['details'] = $this->pages_model->get_page_details($page_uuid);
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_layout','Page Layout','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_content_left','Left Column Content','required|min_length[11]');
		$this->form_validation->set_rules('page_content_right','Right Column Content','required|min_length[11]');
		if($this->form_validation->run()) {
			$this->pages_model->edit_system_page($page_uuid);
			redirect('admin/pages');
		} else {
			$this->template->content->view('pages/edit_page_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit the dashboard of the gallery
	* 
	* @param int page_uuid
	* 
	*/
	function edit_gallery($page_uuid)
	{
		$this->template->javascript->add('//js.nicedit.com/nicEdit-latest.js');
		
		$data['details'] = $this->pages_model->get_page_details($page_uuid);
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_layout','Page Layout','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('page_content_left','Left Column Content','required|min_length[11]');
		$this->form_validation->set_rules('page_content_right','Right Column Content','required|min_length[11]');
		if($this->form_validation->run()) {
			$this->pages_model->edit_system_page($page_uuid);
			redirect('admin/pages');
		} else {
			$this->template->content->view('pages/edit_page_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	 * List all page post
	 *
	 */
	function page_posts()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/pages/page_posts';
		$config['total_rows'] = $this->db->count_all('page_posts');
		$config['uri_segment'] = 4;
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
		$axns = array('data'=>'Actions','colspan'=>3);
		$this->table->set_heading('Post Title','Page','Author','Created',$axns);
		if($posts = $this->pages_model->get_all_posts($config['per_page'],$this->uri->segment(4))) {
			foreach($posts as $post) {
				if($post->active)
					$status = anchor("admin/pages/deactivate_post/$post->post_uuid",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate'));
				else
					$status = anchor("admin/pages/activate_post/$post->post_uuid",'<i class="icon-unlock butn butn-success"></i>',array('title'=>'Activate'));
				$this->table->add_row($post->post_title,$post->page_title,$post->fullname,date('jS M Y',strtotime($post->created)),
					anchor("admin/pages/edit_post/$post->post_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
					anchor("admin/pages/delete_post/$post->post_uuid/",'<i class="icon-trash butn butn-danger"></i>', array('onClick'=>'return confirm(\'Do you really want to delete the post?\');','title'=>'Delete')),
					$status
				);
			}
		} else {
			$this->table->clear();
			$this->table->add_row('There are no posts added yet :-|');
		}
		$this->template->content->view('pages/dashboard_posts');
		$this->template->publish();
	}
	
	/**
	 * Add a page post to the system
	 *
	 */
	function add_post()
	{
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('post_title','Post Title','trim|required|alpha_dash_space|xss_clean');
		$this->form_validation->set_rules('page','Page','trim|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('post_position','Post Position','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('column','Column','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('post_content','Post Content','trim|required|xss_clean');
		$this->form_validation->set_rules('active','Active','trim|xss_clean');
		
		if($this->form_validation->run()) {
			$this->pages_model->save_post();
			$this->route_lib->redirect_with_message('admin/pages/page_posts','Post successfully added.');
		} else {
			$this->template->content->view('pages/add_post_form');
			$this->template->publish();
		}
	}
	
	/**
	 * Edit a page post
	 *
	 * @param int post_uuid
	 *
	 */
	function edit_post($post_uuid)
	{
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('post_title','Post Title','trim|required|xss_clean');
		$this->form_validation->set_rules('page','Page','trim|xss_clean');
		$this->form_validation->set_rules('post_position','Post Position','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('column','Column','trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('post_content','Post Content','trim|required|xss_clean');
		$this->form_validation->set_rules('active','Active','trim|xss_clean');
		
		if($this->form_validation->run()) {
			$this->pages_model->update_post($post_uuid);
			$this->route_lib->redirect_with_message('admin/pages/page_posts','Post successfully updated.');
		} else {
			$data['details'] = $this->pages_model->get_post_details($post_uuid);
			$this->template->content->view('pages/edit_post_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	 * Delete a page post
	 *
	 * @param int page_uuid
	 *
	 */
	function delete_post($post_uuid)
	{
		$this->pages_model->delete_post($post_uuid);
		$this->route_lib->redirect_with_message('admin/pages/page_posts','Post successfully deleted.');
	}
	
	/**
	 * Activate a page post
	 *
	 * @param int post_uuid
	 *
	 */
	function activate_post($post_uuid)
	{
		$this->pages_model->activate_post($post_uuid);
		$this->route_lib->redirect_with_message('admin/pages/page_posts','Post successfully activated.');
	}
	
	/**
	 * Deactivate a page post
	 *
	 * @param int post_uuid
	 *
	 */
	function deactivate_post($post_uuid)
	{
		$this->pages_model->deactivate_post($post_uuid);
		$this->route_lib->redirect_with_message('admin/pages/page_posts','Post successfully deactivated.');
	}
}