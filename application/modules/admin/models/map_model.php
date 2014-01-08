<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Map_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Get a list of all locations added to the DB 
	*  
	* @return array
	* 
	*/
	function get_all_locations()
	{
		$this->db->select('*');
		$this->db->from('locations');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result_array();
		else
			return NULL;
	}
	
	/**
	* Add a new location to the DB
	*  
	*/
	function save_location()
	{
		$latlng = explode(',',preg_replace('/[()]| /','',$this->input->post('latlng')));
		$lat = $latlng[0];
		$lng = $latlng[1];
		$data = array('location_name'=>$this->input->post('location_name'),'full_address'=>$this->input->post('address'),
			'lat'=>$lat,'lng'=>$lng,'zip_code'=>$this->input->post('zip'));
		$this->db->insert('locations',$data);
	}
	
	/**
	* Edit the details of a location
	* 
	* @param int id
	* 
	*/
	function edit_location($id)
	{
		$latlng = explode(',',preg_replace('/[()]| /','',$this->input->post('latlng')));
		$lat = $latlng[0];
		$lng = $latlng[1];
		$data = array('location_name'=>$this->input->post('location_name'),'full_address'=>$this->input->post('address'),
			'lat'=>$lat,'lng'=>$lng,'zip_code'=>$this->input->post('zip'));
		$this->db->where('id',$id);
		$this->db->update('locations',$data);
	}
	
	/**
	* Get all details about a location
	* 
	* @param float latitude
	* @param float longitude
	* 
	*/
	function get_location_details($lat,$lng)
	{
		$this->db->select('*');
		$this->db->from('locations');
		$this->db->where('lat',$lat);
		$this->db->where('lng',$lng);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Completely delete the record of a location from the DB
	* 
	* @param float latitude
	* @param float longitude
	* 
	*/
	function delete_location($lat,$lng)
	{
		$this->db->where('lat',$lat);
		$this->db->where('lng',$lng);
		$this->db->delete('locations');
		if($this->db->affected_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}
}