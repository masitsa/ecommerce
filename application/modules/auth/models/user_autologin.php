<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* User_Autologin
* 
* This model deals with all things autologin
*/
class User_Autologin extends CI_Model
{
	private $table_name			= 'user_autologin';
	private $users_table_name	= 'users';

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name		= $this->table_name;
		$this->users_table_name	= $this->users_table_name;
	}

	/**
	* Get user data for auto-logged in user.
	* Return NULL if given key or user ID is invalid.
	*
	* @param	int
	* @param	string
	* @return	object
	*/
	function get($user_id, $key)
	{
		$this->db->select('utn.username,utn.fullname,utn.user_id,utn.email,utn.activated,utn.u_level_id,utn.u_group_id');
		$this->db->from($this->users_table_name.' as utn');
		$this->db->join($this->table_name.' as tn','tn.user_id = utn.user_id');
		$this->db->where('tn.user_id', $user_id);
		$this->db->where('tn.key_id', $key);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
			return $query->row();
		return NULL;
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set($user_id, $key)
	{
		return $this->db->insert($this->table_name, array(
			'user_id' 		=> $user_id,
			'key_id'	 	=> $key,
			'user_agent' 	=> substr($this->input->user_agent(), 0, 149),
			'last_ip' 		=> $this->input->ip_address(),
		));
	}

	/**
	 * Delete user's autologin data
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function delete($user_id, $key)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('key_id', $key);
		$this->db->delete($this->table_name);
	}

	/**
	 * Delete all autologin data for given user
	 *
	 * @param	int
	 * @return	void
	 */
	function clear($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->table_name);
	}

	/**
	 * Purge autologin data for given user and login conditions
	 *
	 * @param	int
	 * @return	void
	 */
	function purge($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('user_agent', substr($this->input->user_agent(), 0, 149));
		$this->db->where('last_ip', $this->input->ip_address());
		$this->db->delete($this->table_name);
	}
}

/* End of file user_autologin.php */
/* Location: ./application/modules/auth/models/user_autologin.php */