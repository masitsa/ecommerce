<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Carousel_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Get details of pictures to be shown in the carousel
	* 
	*/
	function get_carousel_pictures()
	{
		$this->db->select('*');
		$this->db->from('carousel_pictures');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get the details of a picture from the DB e.g. for editing purposes
	* 
	* @param undefined $pic_id
	* 
	*/
	function get_picture_details($pic_id)
	{
		$this->db->select('*');
		$this->db->from('carousel_pictures');
		$this->db->where('pic_id',$pic_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Save the caption and other details for a newly uploaded carousel picture
	* 
	* @param string file_name
	* 
	*/
	function save_pic_details($file_name)
	{
		$data = array('pic_name'=>$file_name,'pic_caption'=>$this->input->post('caption'));
		$this->db->insert('carousel_pictures',$data);
	}
	
	/**
	* Edit the details of a carousel picture e.g. caption et al
	* 
	* @param int pic_id
	* @param array data
	* 
	*/
	function edit_carousel_pic($pic_id,$data)
	{
		$this->db->where('pic_id',$pic_id);
		$this->db->update('carousel_pictures',$data);
	}
	
	/**
	* Delete the details of a picture from the DB
	* 
	* @param int pic_id
	* @return bool
	* 
	*/
	function delete_carousel_picture($pic_id)
	{
		$this->db->where('pic_id',$pic_id);
		$this->db->delete('carousel_pictures');
		if($this->db->affected_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}
}