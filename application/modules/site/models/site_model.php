<?php
class Site_model extends CI_Model
{
	private $users_table = 'users';
	private $pages_table = 'pages';
	private $posts_table = 'page_posts';	// The pages table
	private $columns_table = 'post_columns';
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Get all pages from the DB 
	*/
	function get_pages()
	{
		$this->db->select('*');
		$this->db->from($this->pages_table);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get the URLs and titles of pages that are parents
	* Used when displaying the frontend main nav
	*
	*/
	function get_parent_page_details()
	{
		$this->db->select('page_id,page_path,page_url,page_title');
		$this->db->from($this->pages_table);
		$this->db->where('active',1);
		$this->db->where('parent',0);
		$this->db->order_by('page_order','asc');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get the details of a page for display on the frontend 
	* 
	* @param string page_url
	* @return object
	* 
	*/
	function get_page_details($page_url)
	{
		$this->db->select('p.page_uuid,p.page_title,p.page_content_left,p.page_content_middle,p.page_content_right,p.page_url,p.post_page,pl.layout_name');
		$this->db->from($this->pages_table.' as p ');
		$this->db->join('page_layouts as pl','p.page_layout = pl.layout_id','INNER');
		$this->db->where('p.page_url',$page_url);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Get the homepage to be loaded when the user opens the system
	* 
	* @return string
	*
	*/
	function get_homepage_url()
	{
		$this->db->select('s.value,p.page_path,p.page_url');
		$this->db->from('settings as s');
		$this->db->where('s.code','homepage');
		$this->db->join($this->pages_table.' as p ','s.value = p.page_url','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('page_path').'/'.$query->row('page_url');
		else
			return NULL;
	}
	
	/**
	* Get contact details for use in the frontend
	* 
	* @return string
	* 
	*/
	function get_contact_details()
	{
		$this->db->select('short_name,setting_value');
		$this->db->from('auth_settings');
		$this->db->where('short_name','street_address');
		$this->db->or_where('short_name','phone_number');
		$this->db->or_where('short_name','fax_number');
		$this->db->or_where('short_name','contact_email');
		$query = $this->db->get();
		if($query->num_rows() != 0) {
			foreach($query->result() as $row)
				$data[$row->short_name] = $row->setting_value;
			return $data;
		} else {
			return NULL;
		}
	}
	
	/**
	* Get the details of a post
	* For display etc.
	*  
	* @param int $post_id
	* 
	*/
	function get_post_details($post_url)
	{
		$this->db->select('p.post_id,p.post_uuid,p.post_title,p.post_url,p.post_content,p.submitted,u.fullname');
		$this->db->from('blog_posts as p');
		$this->db->join('users as u','p.submitted_by = u.user_id','INNER');
		$this->db->where('p.post_url',$post_url);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Confirm that a page is activated
	*
	* @param string page_url
	* 
	*/
	function is_page_active($page_url)
	{
		$this->db->select("1",FALSE);
		$this->db->from($this->pages_table);
		$this->db->where('page_url',$page_url);
		$this->db->where('active',1);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	* Get the contact email address
	* Can be used when sending mail from the contact form_button
	*
	* @return string
	*
	*/
	function get_contact_email()
	{
		$this->db->select('value');
		$this->db->from('settings');
		$this->db->where('code','contact_email');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('value');
		else
			return NULL;	
	}
	
	/**
	* Get the name of the site from the DB for display on pages
	* 
	* @return string 
	* 
	*/
	function get_site_name()
	{
		$this->db->select('setting_value');
		$this->db->from('auth_settings');
		$this->db->where('short_name','website_name');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('setting_value');
		else
			return NULL;
	}
	
	/**
	* Increment page view statistics
	* Called every time a page is loaded to give page view insights
	* 
	* @param int page_uuid
	* 
	*/
	function increment_page_view($page_uuid)
	{
		$this->db->select('views');
		$this->db->from('page_views');
		$this->db->where('page',$page_uuid);
		$views = $this->db->get()->row('views');
		$data = array('views'=>$views+1);
		$this->db->where('page',$page_uuid);
		$this->db->update('page_views',$data);
	}
	
	/**
	* Increment blog post view statistics
	* Called every time a post is loaded to give page view insights
	* 
	* @param int post_uuid
	* 
	*/
	function increment_post_view($post_uuid)
	{
		$this->db->select('views');
		$this->db->from('post_views');
		$this->db->where('post',$post_uuid);
		$views = $this->db->get()->row('views');
		$data = array('views'=>$views+1);
		$this->db->where('post',$post_uuid);
		$this->db->update('post_views',$data);
	}
	
	/**
	 * Get the ID of a particular page given its URL
	 * Used when constructing the frontend navigation
	 *
	 * @param string page_url
	 * @return int page_id
	 *
	 */
	function get_page_id($page_url)
	{
		$this->db->select('page_id');
		$this->db->from($this->pages_table);
		$this->db->where('page_url',$page_url);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('page_id');
		else
			return NULL;
	}
	
	/**
	 * Check if a page has children
	 * If so return some information about all the children
	 *
	 * @param string page_url
	 * @return array
	 *
	 */
	function page_has_children($page_url)
	{
		$page_id = $this->get_page_id($page_url);
		$this->db->select('page_title,page_url,page_path');
		$this->db->from($this->pages_table);
		$this->db->where('parent',$page_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Check if a page is a child of another page based on it URL and the ID of the parent page
	 *
	 * @param int parent_id The ID of the parent page
	 * @param string page_url The URL to be tested
	 * @return bool
	 *
	 */
	function is_my_child($parent_id,$child_url)
	{
		$child_id = $this->get_page_id($child_url);
		$this->db->select('parent');
		$this->db->from($this->pages_table);
		$this->db->where('page_id',$child_id);
		$this->db->where('parent',$parent_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Get the ID of a column given its name
	 *
	 * @param string column_name
	 * @return int column_id
	 *
	 */
	function get_column_id($column_name)
	{
		$this->db->select('column_id')->from($this->columns_table)->where('column_name',$column_name);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('column_id');
		else
			return NULL;
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
		$column_id = $this->get_column_id($column_name);
		$this->db->select('*')->from($this->posts_table)->where('page',$page_uuid)->where('column',$column_id)->where('active','1')->order_by('post_position','asc');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Record a new page visit for the current session
	 *
	 * @param int page_uuid
	 *
	 */
	function record_page_visit($page_uuid)
	{
		//var_dump($this->session->userdata('session_id'));
		//var_dump($this->config->item('analytics_table','analytics'));
		//var_dump($this->input->ip_address());
		//var_dump($this->input->user_agent());
		//var_dump($page_uuid);
		$this->db->select('pages_visited')->from($this->config->item('analytics_table','analytics'))->where('session_id',$this->session->userdata('session_id'))
			->where('ip_address',$this->input->ip_address())->where('user_agent',$this->input->user_agent());
		$query = $this->db->get();
		if($query->num_rows() != 0) {
			if(!is_null($pages_visited = $query->row('pages_visited'))) {
				$pages_visited = json_decode($pages_visited);
				//var_dump($pages_visited);
				array_push($pages_visited,$page_uuid);
				//var_dump($pages_visited);
				//$pages_visited[] = $page_uuid;
				$data['pages_visited'] = json_encode($pages_visited);
				//var_dump($data['pages_visited']);
				$this->db->where('session_id',$this->session->userdata('session_id'))->where('ip_address',$this->input->ip_address())
					->where('user_agent',$this->input->user_agent());
				$this->db->update($this->config->item('analytics_table','analytics'),$data);
				
			} else {
				$page_uuids[] = $page_uuid;
				$data['pages_visited'] = json_encode($page_uuids);
				//var_dump($data['pages_visited']);
				$this->db->where('session_id',$this->session->userdata('session_id'))->where('ip_address',$this->input->ip_address())
					->where('user_agent',$this->input->user_agent());
				$this->db->update($this->config->item('analytics_table','analytics'),$data);
			}
			return TRUE;
		}
	}
}