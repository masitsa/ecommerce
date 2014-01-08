<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class gallery_model extends CI_Model
{
	private $gallerys_table = 'gallery_photos';	// The gallerys table
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Return a list of all details about all gallerys in the system
	* 
	* @param int num
	* @param int offset
	* @return object
	*  
	*/
	function get_all_gallerys()
	{
		$this->db->select('*');
		$this->db->from($this->gallerys_table);
		$query = $this->db->get();
			return $query->result();
	}
	
	function get_category_name($id)
	{
		$this->db->select('name');
		$this->db->from('gallery');
		$this->db->where('gallery_id = ',$id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('name');
		else
			return NULL;
	}
	
	function get_category_datatype($id)
	{
		$this->db->select('data_type');
		$this->db->from('gallery');
		$this->db->where('gallery_id = ',$id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('data_type');
		else
			return NULL;
	}
	
	/**
	* Return a list of all gallerys and their authors
	* 
	* @param int num
	* @param int offset
	* @return object
	* 
	*/
	function get_all_gallery_list($num,$offset)
	{
		$this->db->select('g.*,gallery.name');
		$this->db->from('gallery_photos as g');
		$this->db->join('gallery','g.gallery = gallery.gallery_id','INNER');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* 
	* Return gallery Categories
	* 
	*/
	function get_type_list()
	{
		$this->db->select('*');
		$this->db->from('gallery');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function get_category_project_list($id)
	{
		$this->db->select('*');
		$this->db->from('gallery');
		$this->db->where('category = ',$id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Get all details about a particular gallery
	 * 
	 * @param int gallery_uuid
	 * @return object
	 * 
	 */
	function get_gallery_details($gallery_id)
	{
		$this->db->select('gallery_photos.*,gallery.*');
		$this->db->from('gallery_photos');
		$this->db->join('gallery','gallery_photos.gallery = gallery.gallery_id','INNER');
		$this->db->where('gallery_photos.photo_id',$gallery_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Save the content of a new gallery to the database
	* 
	*/
	function save_gallery($data)
	{
		if ($this->db->insert($this->gallerys_table, $data));
	}
	
	/**
	* Update the content of a particular gallery 
	* 
	* @param int gallery_uuid
	* 
	*/
	function update_gallery($data)
	{
		$this->db->where('photo_id',$this->uri->segment(4));
		$this->db->update($this->gallerys_table,$data);
	}
	
	/**
	* Delete a gallery from the DB
	* 
	* @param int gallery_id
	* 
	*/
	function delete_gallery($gallery_id)
	{
		$this->db->delete($this->gallerys_table,array('photo_id'=>$gallery_id));
	}
	
	function select_edit_type($typeid)
	{
		$this->db->select('*');
		$this->db->from('gallery');
		$this->db->where('gallery_id',$typeid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	function update_gallery_type($gallery_id)
	{
		$name = $this->input->post('category');
		$class = explode(' ',$name);
		$cc = count($class);
		for($i=0;$i<$cc;$i++)
			$data_type .= $class[$i];
		$data = array('name'=>$name,'data_type'=>$data_type);
		$this->db->where('gallery_id',$gallery_id);
		$this->db->update('gallery',$data);
	}
	
	function create_gallery_type($data)
	{
		$this->db->insert('gallery', $data);
	}
	
	/**
	 * Delete a gallery
	 * Also delete all photos assigned to the gallery
	 *
	 * @param int $typeid The ID of the gallery to be deleted
	 *
	 */
	function delete_type($typeid)
	{
		$this->db->delete('gallery',array('gallery_id'=>$typeid));
		$this->db->delete('gallery_photos',array('gallery'=>$typeid));
		return $this->gallery_has_photos($typeid);
	}
	
	/**
	 * Check if a particular gallery has photos assigned
	 * Called when deleting a gallery
	 *
	 * @param int $typeid The id of the gallery to be checked
	 * @return array An array of image names attached to this gallery
	 *
	 */
	function gallery_has_photos($typeid)
	{
		$this->db->select('image');
		$this->db->from('gallery_photos');
		$this->db->where('gallery',$typeid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return FALSE;
	}
}