<?php
require_once "./application/modules/admin/controllers/admin.php";
class Blog extends Admin
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('blog_model');
		$this->load->helper('text');
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}
	
	/**
	* Load the dashboard of the Modules
	* Lists all news items with actions that can be performed on them
	* 
	*/
	function index()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/blog/index';
		$config['total_rows'] = $this->db->count_all('blog_posts');
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
		
		$items = $this->blog_model->get_all_posts($config['per_page'],$this->uri->segment(4));
		if(!empty($items)){
			$this->table->set_heading('Title','Submitted By','Submitted',$axns);
			foreach($items as $item){
				$this->table->add_row($item->post_title,$item->fullname,$item->submitted,
				anchor("admin/blog/edit_post/$item->post_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
				anchor("site/blog/post/$item->post_url",img(array('class'=>'icon-eye-open butn butn-info')),array('title'=>'Preview Post','target'=>'frontend')), 
				anchor("admin/blog/delete_post/$item->post_uuid",'<i class="icon-trash butn butn-danger"></i>',array('title'=>'Delete','onClick'=>'return confirm(\'Do you really want to delete this post?\')')));
			}
		}else{
			$this->table->add_row('There are no posts to display yet. Would you like to add a new post?');
		}
		$this->template->content->view('blog/dashboard');
		$this->template->publish();
	}
	
	/**
	* Add a new post to the DB
	*  
	*/ 
	function add_post()
	{
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		
		$user_id = $this->session->userdata('user_id');
		
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_rules('post_title','Post Title','trim|required|alpha_dash_space|xss_clean');
		$this->form_validation->set_rules('post_content','Post Content','required|min_length[11]');
		if($this->form_validation->run()) {
			if($this->blog_model->is_post_title_unique($this->input->post('post_title'))) {
				$this->blog_model->save_post($user_id);
				redirect('admin/blog');
			} else {
				$data['post_title_error'] = 'This post title is already taken. Please choose another title.';
				$this->template->content->view('blog/add_post_form',$data);
				$this->template->publish();
			}
		} else {
			$this->template->content->view('blog/add_post_form');
			$this->template->publish();
		}
	}
	
	/**
	* Edit the details of a post
	* 
	* @param int post_uuid
	* 
	*/
	function edit_post($post_uuid)
	{
		$this->template->javascript->add(base_url().'js/tinymce/tinymce.min.js');
		
		$data['details'] = $this->blog_model->get_post_details($post_uuid);
		$this->form_validation->set_message('min_length','The %s field is required.');
		$this->form_validation->set_rules('post_title','Post Title','trim|required|alpha_dash_space|xss_clean');
		$this->form_validation->set_rules('post_content','Post Content','required|min_length[11]');
		if($this->form_validation->run()) {
			$this->blog_model->edit_post($post_uuid);
			redirect('admin/blog');
		} else {
			$this->template->content->view('blog/edit_post_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Delete a post from the DB (permanently)
	* 
	* @param int post_uuid
	* 
	*/
	function delete_post($post_uuid)
	{
		$this->blog_model->delete_post($post_uuid);
		redirect('admin/blog');
	}
	
	/**
	* Build the recent posts widget and populate it with the latest posts
	* 
	*/
	function posts_widget()
	{
		$items = $this->blog_model->get_latest_posts();
		foreach($items as $item){
			$content = substr(preg_replace('/<([^>]+)>/','',$item->post_content),0,40);
			echo("<div class='widget-inner'><div class='media'><div class='media-body'>");
			echo("<h4 class='media-heading'>".anchor('site/show_post/'.$item->post_url,$item->post_title)."</h4>");
			echo("<small><em> By ".$item->fullname."</em> on ".date('jS M Y',strtotime($item->submitted))."</small><br\>");
			echo("<p>".$content."... ".anchor("site/show_post/$item->post_url",'read more')."</p>");
			echo("</div></div></div>");
		}
	}
	
	/**
	* Build the posts widget for display on the dashboard
	* 
	*/
	function dashboard_widget()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/blog/index';
		$config['total_rows'] = $this->db->count_all('blog_posts');
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
		
		$items = $this->blog_model->get_all_posts($config['per_page'],$this->uri->segment(4));
		if(!empty($items)){
			$this->table->set_heading('Title','Submitted By','Submitted',$axns);
			foreach($items as $item){
				$this->table->add_row($item->post_title,$item->fullname,$item->submitted,
				anchor("admin/blog/edit_post/$item->post_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
				anchor("site/blog/post/$item->post_url",img(array('class'=>'icon-eye-open butn butn-info')),array('title'=>'Preview Post','target'=>'frontend')),
				anchor("admin/blog/delete_post/$item->post_uuid",'<i class="icon-trash butn butn-danger"></i>',array('title'=>'Delete','onClick'=>'return confirm(\'Do you really want to delete this post?\')')));
			}
		}else{
			$this->table->add_row('There are no posts to display yet. Would you like to add a new posts?');
		}
		echo $this->table->generate();
		echo("<div id='pagination'>".$this->pagination->create_links()."</div>");
	}
	
	/**
	* Build the post archives widget
	* This will list all posts ever submitted grouped by month
	*  
	*/
	function post_archives_widget()
	{
		$months = $this->blog_model->get_post_months();
		$no_of_months = count($months);
		for($i=0;$i<$no_of_months;$i++) {
			$blog = $this->blog_model->get_posts_in_month($months[$i]);
			echo('<div class="accordion-group">');
			echo('<div class="accordion-heading">');
			echo('<a href="#collapse'.$i.'" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">');
			echo('<i class="icon-minus"></i>'.$months[$i].'</a></div>');
			echo('<div class="accordion-body collapse" id="collapse'.$i.'">');
			echo('<div class="accordion-inner">');
			echo('<ul class="icons">');
			foreach($posts as $post)
				echo('<li><i class="icon-chevron-right"></i>'.anchor("site/show_post/$post->post_url",$post->post_title).'</li>');
			echo('</ul></div></div></div>');
		}
	}
}