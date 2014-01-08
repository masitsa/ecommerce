<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Blog_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Get a list of all posts from the DB
	* Pagination friendly
	*  
	* @param int $num
	* @param int $offset
	* @return object
	* 
	*/
	function get_all_posts($num,$offset)
	{
		$this->db->select('p.post_uuid,p.post_title,p.post_url,p.post_content,p.submitted,u.fullname');
		$this->db->from('blog_posts as p');
		$this->db->join('users as u','p.submitted_by = u.user_id','INNER');
		$this->db->order_by('p.submitted','desc');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get a list of all posts from the DB
	* No pagianation this time
	* 
	* @return object
	* 
	*/
	function get_archive_posts()
	{
		$this->db->select('p.post_id,p.post_title,p.post_url,p.post_content,p.submitted,u.fullname');
		$this->db->from('blog_posts as p');
		$this->db->join('users as u','p.submitted_by = u.user_id','INNER');
		$this->db->order_by('p.submitted','desc');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Save a new post to the DB
	* 
	* @param int $user_id
	* 
	*/
	function save_post($user_id)
	{
		do
			$uuid = $this->_generate_uuid(7);
		while(!$this->is_uuid_unique($uuid));
		$title = explode(" ",strtolower($this->input->post('post_title')));
		$ct = count($title);
		$url = '';
		for($i=0;$i<$ct;$i++)
			if($i == 0)
				$url .= $title[$i];
			else
				$url .= '-'.$title[$i];
		// Convert accented characters for standards compliance
		$url = convert_accented_characters($url);
		$data = array('post_uuid'=>$uuid,'post_title'=>$this->input->post('post_title'),'post_content'=>$this->input->post('post_content'),
			'post_url'=>$url,'submitted_by'=>$user_id,'submitted'=>date("Y-m-d H:i:s"));
		$this->db->insert('blog_posts',$data);
		$this->db->insert('post_views',array('post'=>$uuid));
	}
	
	/**
	* Edit the details of a post already added to the DB
	* 
	* @param int post_uuid
	* 
	*/
	function edit_post($post_uuid)
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
		$url = convert_accented_characters($url);
		$data = array('post_title'=>$this->input->post('post_title'),'post_content'=>$this->input->post('post_content'),
			'post_url'=>$url);
		$this->db->where('post_uuid',$post_uuid);
		$this->db->update('blog_posts',$data);
	}
	
	/**
	* Permanently delete a post from the DB
	*  
	* @param int post_uuid
	* 
	*/
	function delete_post($post_uuid)
	{
		$this->db->delete('blog_posts',array('post_uuid'=>$post_uuid));		// Delete the contents of the post
		$this->db->delete('post_views',array('post'=>$post_uuid));	// Delete the records of post views
	}
	
	/**
	* Get the details of a post
	* For display etc.
	*  
	* @param int post_uuid
	* 
	*/
	function get_post_details($post_uuid)
	{
		$this->db->select('*');
		$this->db->from('blog_posts');
		$this->db->where('post_uuid',$post_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
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
	* Check if a generated uuid is duplicated in the DB
	* Just to be safe
	* 
	* @param int uuid
	* @return bool
	* 
	*/
	function is_uuid_unique($post_uuid)
	{
		$this->db->select('1',FALSE);
		$this->db->from('blog_posts');
		$this->db->where('post_uuid',$post_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	 * Check if the post title of a new post is unique to avoid collisions in post titles
	 * Called when adding and editing a blog post
	 *
	 * @param string post_name
	 * @return bool
	 *
	 */
	function is_post_title_unique($post_title)
	{
		$this->db->limit(1)->select('post_title')->from('blog_posts')->where('post_title',$post_title);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
}