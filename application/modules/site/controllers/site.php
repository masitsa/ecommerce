<?php
class Site extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','html','url'));
		$this->load->config('analytics',TRUE);
		$this->load->library(array('form_validation','route_lib'));
		$this->load->model('site_model');
	}
	
	/**
	* Load the homepage of the system
	* Based on the settings. Any page can be loaded as the homepage
	* 
	*/
	function index()
	{
		redirect('shop');
		//redirect($this->site_model->get_homepage_url());
	}
	
	/**
	* Generate the main nav for the frontend of the system
	* 
	*/
	function show_main_nav()
	{
		$page = explode("/",uri_string());
		$page_urls = $this->site_model->get_parent_page_details();
		foreach($page_urls as $page_url) {
			if(isset($page[1]) && $page[1] == $page_url->page_url || isset($page[1]) && $this->site_model->is_my_child($page_url->page_id,$page[1]) ||
				$page[0] == $page_url->page_url ||  $this->site_model->is_my_child($page_url->page_id,$page[0])) {
				if($children = $this->site_model->page_has_children($page_url->page_url)) {
					echo("<li class='active hidden-desktop'>".anchor("$page_url->page_path/$page_url->page_url",$page_url->page_title));
					echo("<li class='active dropdown hidden-tablet hidden-phone'>".anchor("$page_url->page_path/$page_url->page_url",$page_url->page_title."<b class='caret'></b>",array('class'=>'active','data-toggle'=>''))."");
					echo("<ul class='dropdown-menu'>");
					foreach($children as $child)
						echo("<li>".anchor("$child->page_path/$child->page_url",$child->page_title)."</li>");
					echo("</ul></li>");
				} else {
					echo("<li class='active'>".anchor("$page_url->page_path/$page_url->page_url",$page_url->page_title)."</li>");
				}
			} else {
				if($children = $this->site_model->page_has_children($page_url->page_url)) {
					echo("<li class='hidden-desktop'>".anchor("$page_url->page_path/$page_url->page_url",$page_url->page_title));
					echo("<li class='dropdown hidden-tablet hidden-phone'>".anchor("$page_url->page_path/$page_url->page_url",$page_url->page_title."<b class='caret'></b>",array('class'=>'','data-toggle'=>''))."");
					echo("<ul class='dropdown-menu'>");
					foreach($children as $child)
						echo("<li>".anchor("$child->page_path/$child->page_url",$child->page_title)."</li>");
					echo("</ul></li>");
				} else {
					echo("<li>".anchor("$page_url->page_path/$page_url->page_url",$page_url->page_title)."</li>");
				}
			}
        }
	}
	
	/**
	 * Generate the subnav for a page
	 *
	 */
	function show_subnav()
	{
		$page = explode("/",uri_string());
		if(isset($page[2]))
			$page_url = $page[2];
		else
			$page_url = $page[1];
		if($children = $this->site_model->page_has_children($page_url)) {
			echo 'Submenu: ';
			foreach($children as $child)
				echo anchor("$child->page_path/$child->page_url",$child->page_title).' / ';
		}
	}
	
	/**
	* Load the homepage of the system
	* 
	*/
	function home()
	{
		// Set the template to use for this page
		$this->template->set_template('templates/home_page');
		
		// Set the title of the page
        $this->template->title->set('Home');
		
		// Add stylesheets dynamically
		$this->template->stylesheet->add(base_url().'css/layerslider.css');
		$this->template->stylesheet->add(base_url().'css/misty/skin.css');
		
		// Add javascript dynamically
		$this->template->javascript->add(base_url().'js/misty/layerslider.kreaturamedia.jquery.js');
		
		// Get the details to be displayed on the page	
		$data['details'] = $this->site_model->get_page_details('home');
		
		if(!is_null($data['details']) && $this->site_model->is_page_active('home')) {
			$this->template->content->view($data['details']->layout_name, $data);
			$this->template->publish();
			
			// Register a new page view
			$this->site_model->increment_page_view($data['details']->page_uuid);
		} else {
			$this->template->title->set('Error');
			$this->load->view('page_not_found');
		}
	}
	
	/**
	* Load the contact form
	*
	*/
	function contact()
	{
		if($this->site_model->is_page_active('contact')) {
			
			// Set the template to use for this page
			$this->template->set_template('templates/contact_page');
			
			// Set the title of the page
	        $this->template->title->set('Contact Us');
			
			// Add stylesheets dynamically
			$this->template->stylesheet->add(base_url().'css/map_as_background.css');
			
			// Add javascript dynamically
			$this->template->javascript->add('//maps.googleapis.com/maps/api/js?libraries=places&sensor=false');
			$this->template->javascript->add(base_url().'js/downloadxml.js');
			$this->template->javascript->add(base_url().'js/infobox.js');
			$this->template->javascript->add(base_url().'js/display_map.js');
			$this->template->javascript->add(base_url().'js/misty/misty-contact-form.js');
			
			$this->form_validation->set_rules('name','Name','trim|required|xss_clean');
			$this->form_validation->set_rules('email','Email','trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('subject','Subject','trim|required|xss_clean');
			$this->form_validation->set_rules('message','Message','trim|required|xss_clean');
			if($this->form_validation->run()) {
				//if($this->input->post('bot_honey') != '' || $this->input->post('bot_honey') != 'Leave Blank') {	// If a bot is trying to submit the contact form
				//	$this->route_lib->redirect_with_message('site/contact','Nice try, bot!!');
				//}
				$data = array('email'=>$this->input->post('email'),'name'=>$this->input->post('name'),'subject'=>$this->input->post('subject'),
					'message'=>$this->input->post('message'));
				$this->send_contact_mail($data);
				if(!$this->input->is_ajax_request())
					$this->route_lib->redirect_with_message('site/contact','Thank you, you enquiry has been sent');
			} else {
				// Get the details to be displayed on the page	
				$data['details'] = $this->site_model->get_page_details('contact');
				
				$this->template->content->view('contact',$data);
				$this->template->publish();
				
				// Register a new page view
				$this->site_model->increment_page_view($data['details']->page_uuid);
			}
		} else {
			$this->template->title->set('Error');
			$this->load->view('page_not_found');
		}	
	}
	
	/**
	* Load a particular page based on the URL field of the page
	* This is for pages generated in the backend not the default bundled pages
	* Those use the URL directly e.g. site/home
	* 
	* @param string page_url
	* 
	*/
	function page($page_url)
	{
		
		// Set the template to use for this page
		$this->template->set_template('templates/user_page');
		
		// Get the details to be displayed on the page
		$data['details'] = $this->site_model->get_page_details($page_url);
		
		// Set the title of the page
		$this->template->title->set($data['details']->page_title);
		
		if(!is_null($data['details']) && $this->site_model->is_page_active($page_url)) {
			$this->template->content->view($data['details']->layout_name, $data);
			$this->template->publish();
			
			// Register a new page view
			$this->site_model->increment_page_view($data['details']->page_uuid);
		} else {
			$this->template->title->set('Error');
			$this->load->view('page_not_found');
		}
	}
	
	/**
	* Send mail to the contact email address from the contact form
	* Redirect to the contact form page with either an error or success message
	*
	* @param string email
	* @param array data
	*  
	*/
	function send_contact_mail(&$data)
	{
		$data['message'] = $this->input->post('message');
		$this->load->library('email');
		$this->email->from($data['email'],$data['name']);
		$this->email->reply_to($data['email'],$data['name']);
		$this->email->to($this->site_model->get_contact_email());
		$this->email->subject('[CMS Contact Form]'.$data['subject']);
		$this->email->message($this->load->view('email/contact_email-html',$data, TRUE));
		$this->email->set_alt_message($this->load->view('email/contact_email-txt',$data, TRUE));
		if($this->email->send())
			$this->route_lib->redirect_with_message('site/contact','Thank you '.$this->input->post('name').', you enquiry has been sent');
		else
			$this->route_lib->redirect_with_error('site/contact','Sorry '.$this->input->post('name').', there was an error sending your enquiry. Try again later.');
	}
	
	/**
	 * Get the posts for a specific column of a specific page
	 *
	 * @param int page_uuid
	 * @param string column_name
	 * @param object
	 *
	 */
	function get_page_column_posts($page_uuid,$column_name)
	{
		if($posts = $this->site_model->get_page_column_posts($page_uuid,$column_name)) {
			foreach($posts as $post) {
				echo("<div class='span12 post-title-container'><span class='post-title'>".$post->post_title."</span></div><br/>");
				echo $post->post_content;
				//if(strlen($post->post_content) > 400)
				//	echo(substr($post->post_content,0,strpos($post->post_content, ' ', 400)).'...  '.anchor("post/$post->post_url",'Read More(+)'));
				//else
				//	echo($post->post_content.'...  '.anchor("post/$post->post_url",'Read More(+)'));
				echo('<hr/>');
			}
		}
	}
	
	/**
	 * Record a new page visit for the current session
	 *
	 * @param int page_uuid
	 *
	 */
	function record_page_visit()
	{
		$page_uuid = $this->input->post('page_uuid');
		if($this->site_model->record_page_visit($page_uuid))
			echo 'Page Visit Recorded';
		else
			echo 'Page Visit Record Failed';
		
	}
}