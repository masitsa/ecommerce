<?php
class Blog_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Get a list of the latest posts
	* 
	* @return object
	*  
	*/
	function get_latest_posts()
	{
		$this->db->select('p.post_title,p.post_url,p.post_content,p.submitted_by,p.submitted,u.fullname');
		$this->db->from('blog_posts as p');
		$this->db->join('users as u','p.submitted_by = u.user_id','INNER');
		$this->db->order_by('p.submitted','desc');
		$this->db->limit(4);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get the months in which posts were submitted
	* Only picks a unique month e.g February 2013 once
	* 
	* @return array
	*  
	*/
	function get_post_months()
	{
		$months = array();
		$this->db->select('submitted');
		$this->db->from('blog_posts');
		$this->db->order_by('submitted','desc');
		$query = $this->db->get();
		if($query->num_rows() != 0) {
			foreach($query->result() as $row){
				if(!in_array(date('F Y',strtotime($row->submitted)),$months))
					array_push($months,date('F Y',strtotime($row->submitted)));
			}
			return $months;
		} else {
			return NULL;
		}
	}
	
	/**
	* Get all the posts submitted in a particular month of a particular year
	* 
	* @param string month
	* @return object
	* 
	*/
	function get_posts_in_month($month)
	{
		$date = explode(" ",$month);
		$month = $date[0];
		$year = $date[1];
		$this->db->select('*');
		$this->db->from('blog_posts');
		$this->db->where('MONTHNAME(submitted)',$month);
		$this->db->where('YEAR(submitted)',$year);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get the blog posts from the DB
	* To be displayed on the blog index page
	* 
	* @param int num - number of posts to retrieve
	* @param int offset - where to start
	*  
	*/
	function get_blog_posts($num,$offset)
	{
		$this->db->select('p.post_title,p.post_url,p.post_content,p.submitted,u.fullname');
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
	* Add a comment to a particular post 
	* 
	* @param int post_uuid
	* 
	*/
	function add_comment($post_uuid)
	{
		//var_dump($post_uuid);
		//die();
		do
			$comment_uuid = $this->_generate_uuid(7);
		while(!$this->is_uuid_unique($uuid));
		$data = array('commenter_name'=>$this->input->post('name'),'commenter_email'=>$this->input->post('email'),'comment_text'=>$this->input->post('message'),
			'post'=>$post_uuid,'date_submitted'=>date("Y-m-d H:i:s"),'comment_uuid'=>$comment_uuid);
		$this->db->insert('post_comments',$data);
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
	function is_uuid_unique($comment_uuid)
	{
		$this->db->select('1',FALSE);
		$this->db->from('post_comments');
		$this->db->where('comment_uuid',$comment_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	* Retrieve the comments for a particular blog post
	* 
	* @param int post_uuid
	* @return object
	* 
	*/
	function get_post_comments($post_uuid)
	{
		$this->db->select('*');
		$this->db->from('post_comments');
		$this->db->where('post',$post_uuid);
		$this->db->order_by('date_submitted','asc');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
}