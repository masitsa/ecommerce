<?php
class Adverts_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *  Get a list of all adverts
	 *  
	 * @param	int	$limit	The number of items to fetch
	 * @param	int	$start	Which position to start retrieving from
	 * @return	object
	 * 
	 */
	function fetch_adverts($limit,$start)
	{
		$this->db->select('*')->from('advert, ad_position')->where("ad_position.ad_position_id = advert.ad_position_id")->order_by("ad_position_name, advert_name");
		$query = $this->db->get('',$limit,$start);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 *  Get a category of advert
	 *  
	 * @param	int	$category id
	 * 
	 * @return	row
	 * 
	 */
	function get_category($category_id)
	{
		$this->db->select('category_name')->from('category')->where("category_id = ".$category_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Get all active advert position from the DB
	 *
	 * @return object
	 *
	 */
	function get_all_active_positions()
	{
		$this->db->select('*')->from('ad_position')->order_by("ad_position_name");
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all active product categories from the DB
	 *
	 * @return object
	 *
	 */
	function get_all_active_categories()
	{
		$this->db->select('*')->from('category')->where('category_status = 1 AND category_parent = 0')->order_by("category_name");
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Add a new advert to the DB
	 *
	 */
	function add_advert($file_name)
	{
		$data = array('advert_name'=>$this->input->post('advert_name'),'ad_position_id'=>$this->input->post('ad_position_id'),'category_id'=>$this->input->post('category_id'), 'advert_poster'=>$file_name
		);
		$this->db->insert('advert',$data);
	}
	
	/**
	 * Deactivate a brand so that it is not avaialable in the front-end
	 *
	 * @param int brand_id
	 *
	 */
	function update_advert($advert_id, $file_name)
	{
		$data = array('advert_name'=>$this->input->post('advert_name'),'ad_position_id'=>$this->input->post('ad_position_id'),'category_id'=>$this->input->post('category_id'), 'advert_poster'=>$file_name
		);
		$this->db->where('advert_id',$advert_id)->update('advert',$data);
	}
	
	/**
	 * Activate a product category
	 *
	 * @param int category_id
	 *
	 */
	function activate_advert($advert_id)
	{
		$data = array('advert_status'=>1);
		$this->db->where('advert_id',$advert_id)->update('advert',$data);
	}
	
	/**
	 * Deactivate an advert
	 *
	 * @param int advert_id
	 *
	 */
	function deactivate_advert($advert_id)
	{
		$data = array('advert_status'=>0);
		$this->db->where('advert_id',$advert_id)->update('advert',$data);
	}
	
	/**
	 * Deactivate an advert
	 *
	 * @param int category_id
	 *
	 */
	function deactivate_advert2($category_id)
	{
		$data = array('advert_status'=>0);
		$this->db->where('category_id',$category_id)->update('advert',$data);
	}
	
	/**
	 * Completely delete a product category from the system
	 *
	 * @param int category_id
	 *
	 */
	function delete_advert($advert_id)
	{
		$this->db->delete('advert',array('advert_id'=>$advert_id));
	}
	
	/**
	 *  Get a single advert
	 *  
	 * @param	int	$advert_id
	 * 
	 * 
	 * 
	 */
	function get_advert_details($advert_id)
	{
		$this->db->select('*')->from('advert')->where("advert_id = ".$advert_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
}