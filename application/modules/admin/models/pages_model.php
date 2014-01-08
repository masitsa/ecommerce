<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pages_model extends CI_Model
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
	* Return a list of all details about all pages in the system
	* Excludes pages created by plugins
	* 
	* @param int num
	* @param int offset
	* @return object
	*  
	*/
	function get_all_pages($num,$offset)
	{
		$this->db->select('*');
		$this->db->from($this->pages_table);
		$this->db->where('plugin_id',0);
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get a list of all pages for the add/edit posts forms
	 *
	 * @return object
	 *
	 */
	function get_all_pages_for_posts()
	{
		$this->db->select('page_uuid,page_title')->from($this->pages_table);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Return a list of all pages and their authors
	* Excludes pages created by plugins
	* 
	* @param int num
	* @param int offset
	* @return object
	* 
	*/
	function get_all_pages_list($num,$offset)
	{
		$this->db->select('p.page_uuid,p.page_title,p.page_url,p.active,p.page_content_left,p.page_content_middle,p.page_content_right,p.bundled,p.created,p.modified,u.fullname');
		$this->db->from('pages as p');
		$this->db->where('p.plugin_id',0);
		$this->db->join('users as u','p.author = u.user_id','INNER');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get all details about a particular page
	* 
	* @param int page_uuid
	* @return object
	* 
	*/
	function get_page_details($page_uuid)
	{
		$this->db->select('*');
		$this->db->from($this->pages_table);
		$this->db->where('page_uuid',$page_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Save the content of a new page to the database
	* 
	*/
	function save_page()
	{
		do
			$uuid = $this->_generate_uuid(7);
		while(!$this->_is_page_uuid_unique($uuid));
		$title = explode(" ",strtolower($this->input->post('page_title')));
		$ct = count($title);
		$url = '';
		for($i=0;$i<$ct;$i++)
			if($i == 0)
				$url .= $title[$i];
			else
				$url .= '-'.$title[$i];
		// Convert accented characters for standards compliance
		$url = convert_accented_characters($url);
		$data = array('page_uuid'=>$uuid,'page_order'=>$this->input->post('page_order'),'page_layout'=>$this->input->post('page_layout'),
			'page_title'=>$this->input->post('page_title'),'page_content_left'=>$this->input->post('page_content_left'),
			'page_content_middle'=>$this->input->post('page_content_middle'),'page_content_right'=>$this->input->post('page_content_right'),
			'page_path'=>'page','page_url'=>$url,'parent'=>$this->input->post('parent'),'author'=>$this->session->userdata('user_id'),
			'created'=>date('Y-m-d H:i:s'),'post_page'=>$this->input->post('post_page')
		);
		$this->db->insert($this->pages_table,$data);// Insert page details into the DB
		$views = array('page'=>$uuid);
		$this->db->insert('page_views',$views);		// Create a page view record
	}
	
	/**
	* Activate a page so that it is accessible to all
	*
	* @param int page_uuid
	* 
	*/
	function activate_page($page_uuid)
	{
		$data = array('active'=>1);
		$this->db->where('page_uuid',$page_uuid);
		$this->db->update('pages',$data);
	}
	
	/**
	* Deactivate a page so that it is not accessible to anybody
	* 
	* @param int page_uuid
	* 
	*/
	function deactivate_page($page_uuid)
	{
		$data = array('active'=>0);
		$this->db->where('page_uuid',$page_uuid);
		$this->db->update('pages',$data);
	}
	
	/**
	* Update the content of a particular page 
	* 
	* @param int page_uuid
	* 
	*/
	function edit_page($page_uuid)
	{
		$title = explode(" ",strtolower($this->input->post('page_title')));
		$ct = count($title);
		$url = '';
		for($i=0;$i<$ct;$i++)
			if($i == 0)
				$url .= $title[$i];
			else
				$url .= '-'.$title[$i];
		// Convert accented characters for standards compliance
		$url = convert_accented_characters($url);
		$data = array('page_order'=>$this->input->post('page_order'),'page_layout'=>$this->input->post('page_layout'),
			'page_title'=>$this->input->post('page_title'),'page_content_left'=>$this->input->post('page_content_left'),
			'page_content_middle'=>$this->input->post('page_content_middle'),'page_content_right'=>$this->input->post('page_content_right'),
			'parent'=>$this->input->post('parent'),'post_page'=>$this->input->post('post_page'),'page_path'=>'page','page_url'=>$url);
		$this->db->where('page_uuid',$page_uuid);
		$this->db->update($this->pages_table,$data);
	}
	
	/**
	* Update the content of a system page 
	* 
	* @param int page_uuid
	* 
	*/
	function edit_system_page($page_uuid)
	{
		$data = array('page_order'=>$this->input->post('page_order'),'page_layout'=>$this->input->post('page_layout'),
			'page_content_left'=>$this->input->post('page_content_left'),'page_content_right'=>$this->input->post('page_content_right'));
		$this->db->where('page_uuid',$page_uuid);
		$this->db->update($this->pages_table,$data);
	}
	
	/**
	* Delete a page from the DB
	* 
	* @param int page_uuid
	* 
	*/
	function delete_page($page_uuid)
	{
		if($this->is_bundled_page($page_uuid)) {
			$this->route_lib->redirect_with_error('pages','Sorry. You can not delete this page.');
		} else {
			$this->db->delete($this->pages_table,array('page_uuid'=>$page_uuid)); 	// Delete page details
			$this->db->delete('page_views',array('page'=>$page_uuid)); 				// Delete page view records
		}
	}
	
	/**
	* Generate a random uuid of given length
	* 
	* @param int length (of the random number to be generated)
	* @return string 
	* 
	*/
	function _generate_uuid($length)
	{
		$random = '';
		for($i=0;$i<$length;$i++)
			$random .= mt_rand(0,9);
		return $random;
	}
	
	/**
	* Check if a generated page uuid is duplicated in the DB
	* Just to be safe
	* 
	* @param int uuid
	* @return bool
	* 
	*/
	function _is_page_uuid_unique($page_uuid)
	{
		$this->db->select('1',FALSE);
		$this->db->from($this->pages_table);
		$this->db->where('page_uuid',$page_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	* Check if a page is a bundled (system) page
	* 
	* @param int page_uuid
	* @return bool
	* 
	*/
	function is_bundled_page($page_uuid)
	{
		$this->db->select('1',FALSE);
		$this->db->from($this->pages_table);
		$this->db->where('page_uuid',$page_uuid);
		$this->db->where('bundled',1);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	* Get a list of all available page layouts
	* 
	* @return object 
	* 
	*/
	function get_page_layouts()
	{
		$this->db->select('*');
		$this->db->from('page_layouts');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all pages that have no parents
	 * These are pages that can be assigned children that will then appear in a dropdown
	 * Called when creating and editing a page
	 *
	 * @return array
	 *
	 */
	function get_parent_pages()
	{
		$this->db->select('page_id,page_title');
		$this->db->from('pages');
		$this->db->where('parent',0);
		$this->db->where('bundled',0);
		$this->db->where('plugin_id',0);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Check if the page title of a new page is unique to avoid collisions in page names
	 * Called when adding and editing a page
	 *
	 * @param string page_name
	 * @return bool
	 *
	 */
	function is_page_title_unique($page_title)
	{
		$this->db->limit(1)->select('page_title')->from('pages')->where('page_title',$page_title);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	 * Get a list of all post columns
	 *
	 * @return object
	 *
	 */
	function get_post_columns()
	{
		$this->db->select('*')->from($this->columns_table);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all posts from the DB
	 *
	 */
	function get_all_posts($num,$offset)
	{
		$this->db->select('po.*,u.fullname,pa.page_title')->from($this->posts_table.' as po');
		$this->db->join($this->users_table.' as u','po.author = u.user_id','INNER');
		$this->db->join($this->pages_table.' as pa','po.page = pa.page_uuid','INNER');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get the details of a particular post by post_uuid
	 *
	 * @param int post_uuid
	 * @return object
	 *
	 */
	function get_post_details($post_uuid)
	{
		$this->db->select('*')->from($this->posts_table)->where('post_uuid',$post_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Get the uuid of a post given its url
	 *
	 * @param string post_url
	 * @return int
	 *
	 */
	function get_post_uuid($post_url)
	{
		$this->db->select('post_uuid')->from($this->posts_table)->where('post_url',$post_url);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('post_uuid');
		else
			return NULL;
	}
	
	/**
	* Check if a generated post uuid is duplicated in the DB
	* Just to be safe.
	* 
	* @param int uuid
	* @return bool
	* 
	*/
	function _is_post_uuid_unique($post_uuid)
	{
		$this->db->select('1',FALSE);
		$this->db->from($this->posts_table);
		$this->db->where('post_uuid',$post_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	 * Save a new post to the DB
	 *
	 */
	function save_post()
	{
		do
			$uuid = $this->_generate_uuid(7);
		while(!$this->_is_post_uuid_unique($uuid));
		
		$title = explode(" ",strtolower($this->input->post('post_title')));
		$ct = count($title);
		$url = '';
		for($i=0;$i<$ct;$i++)
			if($i == 0)
				$url .= $title[$i];
			else
				$url .= '-'.$title[$i];
		// Convert accented characters for standards compliance
		$post_url = convert_accented_characters($url);
		
		$data = array('post_uuid'=>$uuid,'post_title'=>$this->input->post('post_title'),'page'=>$this->input->post('page'),
			'post_position'=>$this->input->post('post_position'),'column'=>$this->input->post('column'),'post_url'=>$post_url,
			'post_content'=>$this->input->post('post_content'),'active'=>$this->input->post('active'));
		$data['author'] = $this->session->userdata('user_id');
		$data['created'] = date('Y-m-d H:i:s');
		$this->db->insert($this->posts_table,$data);
	}
	
	/**
	 * Update the details of a post
	 *
	 * @param int post_uuid
	 *
	 */
	function update_post($post_uuid)
	{
		$title = explode(" ",strtolower($this->input->post('post_title')));
		$ct = count($title);
		$url = '';
		for($i=0;$i<$ct;$i++)
			if($i == 0)
				$url .= $title[$i];
			else
				$url .= '-'.$title[$i];
		// Convert accented characters for standards compliance
		$post_url = convert_accented_characters($url);
		
		$data = array('post_title'=>$this->input->post('post_title'),'page'=>$this->input->post('page'),
			'post_position'=>$this->input->post('post_position'),'column'=>$this->input->post('column'),'post_url'=>$post_url,
			'post_content'=>$this->input->post('post_content'),'active'=>$this->input->post('active'));
		$this->db->where('post_uuid',$post_uuid)->update($this->posts_table,$data);
	}
	
	/**
	 * Permanently delete a post
	 *
	 * @param int post_uuid
	 *
	 */
	function delete_post($post_uuid)
	{
		$this->db->delete($this->posts_table,array('post_uuid'=>$post_uuid));
	}
	
	/**
	 * Activate a post by post_uuid
	 *
	 * @param int post_uuid
	 *
	 */
	function activate_post($post_uuid)
	{
		$data = array('active'=>1);
		$this->db->where('post_uuid',$post_uuid)->update($this->posts_table,$data);
	}
	
	/**
	 * Deactivate a post by post_uuid
	 *
	 * @param int post_uuid
	 *
	 */
	function deactivate_post($post_uuid)
	{
		$data = array('active'=>0);
		$this->db->where('post_uuid',$post_uuid)->update($this->posts_table,$data);
	}
}