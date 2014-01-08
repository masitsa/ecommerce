<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Portfolio_model extends CI_Model
{
	private $projects	= 'portfolio_projects';	// The projects table
	private $categories = 'project_categories';	// Project categories table
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Return a list of all details about all projects in the porfolio
	* 
	* @param int num
	* @param int offset
	* @return object
	*  
	*/
	function get_all_projects()
	{
		$this->db->select('*');
		$this->db->from($this->projects);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get the name of a project category
	* 
	* @param int category_id
	* @return string
	* 
	*/
	function get_category_name($category_id)
	{
		$this->db->select('name');
		$this->db->from($this->categories);
		$this->db->where('category_id',$category_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('name');
		else
			return NULL;
	}
	
	/**
	* Get the data_type of a project category
	* 
	* @param int category_id
	* @return string
	* 
	*/
	function get_category_datatype($category_id)
	{
		$this->db->select('data_type');
		$this->db->from($this->categories);
		$this->db->where('category_id',$category_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('data_type');
		else
			return NULL;
	}
	
	/**
	* Return a list of all projects and their authors
	* 
	* @param int num
	* @param int offset
	* @return object
	* 
	*/
	function get_all_projects_list($num,$offset)
	{
		$this->db->select('p.*,pc.name');
		$this->db->from($this->projects.' as p');
		$this->db->join($this->categories.' as pc','p.category = pc.category_id','INNER');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Return all project categories
	* 
	* @param int num
	* @param int offset
	* 
	*/
	function get_all_categories($num,$offset)
	{
		$this->db->select('*');
		$this->db->from($this->categories);
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Return all project categories unpaginated
	* 
	*/
	function get_all_categories_unpaginated()
	{
		$this->db->select('*');
		$this->db->from($this->categories);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get a list of all projects in a particular category 
	* 
	* @param int category_id
	* 
	*/
	function get_category_project_list($category_id)
	{
		$this->db->select('*');
		$this->db->from($this->projects);
		$this->db->where('category',$category_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get all details about a particular project
	* 
	* @param int project_id
	* @return object
	* 
	*/
	function get_portfolio_details($project_id)
	{
		$this->db->select('*');
		$this->db->from($this->projects.' as p');
		$this->db->join($this->categories.' as pc', 'p.category = pc.category_id');
		$this->db->where('p.project_id',$project_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Save the content of a new project to the database
	* 
	* @param string image_name
	* 
	*/
	function save_project($data)
	{
		$this->db->insert($this->projects, $data);
	}
	
	/**
	* Update the content of a particular project
	* 
	* @param int project_id
	* 
	*/
	function edit_project($project_id)
	{
		$this->db->select('*');
		$this->db->from($this->projects.' as p');
		$this->db->join($this->categories,' as pc', 'p.category = pc.category_id');
		$this->db->where('p.project_id',$project_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Edit the details of a particular project
	* 
	* @param array data
	* 
	*/
	function update_project($data)
	{
		$this->db->where('project_id',$this->uri->segment(4));
		$this->db->update($this->projects,$data);
	}
	
	/**
	* Delete a project from the DB
	* 
	* @param int project_id
	* @return bool image_name
	* 
	*/
	function delete_project($project_id)
	{
		$this->db->select('image');
		$this->db->from($this->projects);
		$this->db->where('project_id',$project_id);
		$query = $this->db->get();
		$image_name = $query->row('image');
		$this->db->delete($this->projects,array('project_id'=>$project_id));
		return $image_name;
	}
	
	/**
	* Select a category to edit by category_id 
	* 
	* @param int category_id
	* 
	*/
	function select_edit_category($category_id)
	{
		$this->db->select('*');
		$this->db->from($this->categories);
		$this->db->where('category_id',$category_id);
		$query = $this->db->get();
		return $query->row();
	}
	
	/**
	* Update the details of a particular project category
	* 
	* @param int category_id
	* 
	*/
	function update_category($category_id)
	{
		$data_type = '';
		$category = $this->input->post('category');
		$class = explode(' ',$category);
		$cc = count($class);
		for($i=0;$i<$cc;$i++)
			$data_type .= $class[$i];
		$data = array('name'=>$category,'data_type'=>$data_type);
		$this->db->where('category_id',$category_id);
		$this->db->update($this->categories,$data);
	}
	
	/**
	* Add a new project category to the DB 
	* 
	*/
	function create_category()
	{
		$data_type = '';
		$category = $this->input->post('category');
		$class = explode(' ',$category);
		$cc = count($class);
		for($i=0;$i<$cc;$i++)
			$data_type .= $class[$i];
		$data = array('name'=>$category,'data_type'=>$data_type);
		$this->db->insert($this->categories, $data);
	}
	
	/**
	* Delete a project categry from the DB by category_id 
	* 
	* @param int category_id
	* 
	*/
	function delete_category($category_id)
	{
		$this->db->where('category_id',$category_id);
		$this->db->delete($this->categories);
	}
}