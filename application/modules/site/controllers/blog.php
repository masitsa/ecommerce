<?php
require_once "./application/modules/site/controllers/site.php";
class Blog extends Site
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('blog_model');
	}
	
	/**
	* Load the default view for the blog module
	* A list of all posts with a "posts archive" on the right 
	* 
	*/
	function index()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/site/blog/index';
		$config['total_rows'] = $this->db->count_all('blog_posts');
		$config['per_page'] = 4;
		$config['num_links'] = 5;
		$config['uri_segment'] = 4;
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['pre_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		// Set the template to use for this page
		$this->template->set_template('templates/blog');
		
		// Set the title of the page
        $this->template->title->set('Blog');
		
		// Get the details of the page
		$data['details'] = $this->site_model->get_page_details('blog');
		
		// Get posts to be displayed
		$data['posts'] = $this->blog_model->get_blog_posts($config['per_page'],$this->uri->segment(4));
		
		if(!is_null($data['details']) && $this->site_model->is_page_active('blog')) {			
			$this->template->content->view('blog/blog', $data);
			$this->template->publish();
			
			// Register a new page view
			$this->site_model->increment_page_view($data['details']->page_uuid);
		} else {
			$this->template->title->set('Error');
			$this->load->view('page_not_found');
		}
	}
	
	/**
	* Load a particular post based on the URL field of the page
	* 
	* @param string post_url
	* 
	*/
	function post($post_url)
	{
		// Set the template to use for this post
		$this->template->set_template('templates/blog_post');
		
		// Get the contents of the blog post
		$data['details'] = $this->site_model->get_post_details($post_url);
		
		// Set the title of the page
        $this->template->title->set($data['details']->post_title);
		
		// Add javascript dynamically
		$this->template->javascript->add(base_url().'js/misty/misty-comment-form.js');
		
		if(!is_null($data['details'])) {			
			// Load comments into the data to be passed to the view
			$data['comments'] = $this->blog_model->get_post_comments($data['details']->post_uuid);
			
			$this->template->content->view('blog/post', $data);
			$this->template->publish();
			
			// Register a new post view
			$this->site_model->increment_post_view($data['details']->post_uuid);
		} else {
			$this->template->title->set('Error');
			$this->load->view('page_not_found');
		}
	}
	
	/**
	* Add a new comment to a particular post 
	* 
	*/
	function comment()
	{
		$post_url = $this->input->post('post_url');
		$post_uuid = $this->input->post('post_uuid');
		$bot_honey = $this->input->post('bot_honey');
		$this->form_validation->set_rules('name','Name','trim|required|xss_clean');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('message','Message','trim|required|xss_clean');
		if($this->form_validation->run() && $bot_honey == '' || $bot_honey == 'Leave Blank') {
			$this->blog_model->add_comment($post_uuid);
			if(!$this->input->is_ajax_request())
				redirect("site/blog/post/$post_url");
			else
				return FALSE;
		} else {
			if(!$this->input->is_ajax_request())
				$this->post($post_url);
			else
				return FALSE;
		}
	}
	
	/**
	* Load the comments for a particular blog post
	* Called when a new comment is posted 
	* 
	* @param int post_uuid
	* 
	*/
	function reload_comments($post_uuid)
	{
		$comments = $this->blog_model->get_post_comments($post_uuid);
		if($comments)	{
			foreach($comments as $comment) {
				echo('<div class="comment clearfix">');
	                echo('<img src="'.base_url().'img/misty/avatar.png'.'" alt="avatar" class="avatar"/>');
	                echo('<p class="meta">'.$comment->commenter_name.'<br/><small>'.date('jS M Y',strtotime($comment->date_submitted)).'</small></p>');
	                echo('<div class="textarea">');
	                    echo('<p>'.$comment->comment_text.'</p>');
	                echo('</div>');
	            echo('</div>');
			}
		}
	}
}