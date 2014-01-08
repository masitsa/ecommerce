<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends CI_Model
{
	private $table_name	= 'users';	// user accounts

	function __construct()
	{
		parent::__construct();
		$this->load->dbutil();
	}
	
	/**
	* Select user from DB by user_id
	* 
	* @param int $user_id
	* @return object
	* 
	*/
	function get_user_by_id($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		if ($query->num_rows() == 1) 
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Select user form DB by user key
	* 
	* @param undefined $key
	* @return array
	* 
	*/
	function get_user_by_user_key($key)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('user_key', $key);
		$query = $this->db->get();
		if ($query->num_rows() != 0) 
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Select user from DB by username 
	* 
	* @param string $username
	* @return object
	* 
	*/
	function get_user_by_username($username)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('LOWER(username)',strtolower($username));
		$query = $this->db->get();
		if ($query->num_rows() != 0) 
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Select user form DB by email address
	* 
	* @param string $email
	* @return object
	*/
	function get_user_by_email($email)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('LOWER(email)', strtolower($email));
		$query = $this->db->get();
		if ($query->num_rows() !=0 ) 
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Check if a username os available when registering a new user
	* 
	* @param string $username
	* @return bool
	* 
	*/
	function is_username_available($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)', strtolower($username));
		$query = $this->db->get($this->table_name);
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	* Check if an eamil address is available when registering a new user
	*
	* @param string $email
	* @return bool
	* 
	*/
	function is_email_available($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)', strtolower($email));
		$this->db->or_where('LOWER(new_email)', strtolower($email));
		$query = $this->db->get($this->table_name);
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	* Create a new user on the system and return some info about them
	* user_id,time created,user key
	* 
	* @param bool $activated
	* @return array
	* 
	*/
	function create_user($activated = FALSE)
	{
		$group_array[] = $this->input->post('u_group_id');
		$cg = count($group_array);
		for($i=0;$i<$cg;$i++)
			if($group_array[$i] == 0)
				unset($group_array[$i]);
		if(!empty($group_array))
			$user_group = json_encode(array_values(array_unique($group_array)));
		else
			$user_group = 0;
		$data = array('u_level_id' => $this->input->post('u_level_id'),'u_group_id' => $user_group,
			'username' => $this->input->post('username'),'email'=>$this->input->post('email'),'fullname'=>$this->input->post('fullname')
		);
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
		$data['user_key'] = md5($data['created'].$data['email']);

		// Load the hashing class
		$hasher = new PasswordHash($this->auth_model->get_auth_setting_value('phpass_hash_strength'),$this->auth_model->get_auth_setting_value('phpass_hash_portable'));
		// Hash the given password
		$data['password'] = $hasher->HashPassword($this->input->post('password'));
		
		if($this->db->insert($this->table_name, $data)) {
			return array('new_id'=> $this->db->insert_id(),'create_time'=>$data['created'],'user_key'=>$data['user_key']);
		}
	}
	function create_user2()
	{
		//$hasher = new PasswordHash($this->auth_model->get_auth_setting_value('phpass_hash_strength'),$this->auth_model->get_auth_setting_value('phpass_hash_portable'));
		
		$items = array(
			"fullname" => $this->input->post("customer_name")." ".$this->input->post("customer_lname"),
			"activated" => 1,
			"u_level_id" => 2,
			"email" => $this->input->post("customer_email"),
			"phone" => $this->input->post("customer_phone"),
			//"password" => $hasher->HashPassword($this->input->post('password')),
			"address" => $this->input->post("customer_address"),
			"post_code" => $this->input->post("customer_post_code"),
			"country_id" => $this->input->post("country_id"),
			"customer_type" => $this->input->post("customer_type"),
			"city" => $this->input->post("customer_city")
		);
		
		$this->db->insert("`users`", $items);
	}

	/**
	* Register a new user on the system and return some info about them
	* user_id,time created,user key
	*  
	* @param array $data
	* @param bool $activated
	* @return array
	* 
	*/
	function register_user($data,$activated)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
		$data['user_key'] = md5($data['created'].$data['email']);
		
		// Load the hashing class
		$hasher = new PasswordHash($this->auth_model->get_auth_setting_value('phpass_hash_strength'),$this->auth_model->get_auth_setting_value('phpass_hash_portable'));
		// Hash the given password
		$data['password'] = $hasher->HashPassword($this->input->post('password'));
		
		if($this->db->insert($this->table_name, $data))
			return array('new_id'=> $this->db->insert_id(),'create_time'=>$data['created'],'user_key'=>$data['user_key']);
	}
	
	/**
	* Create a new user level on the system
	* 
	*/
	function create_u_level()
	{
		$parent = $this->input->post('parent');
		$cp = count($parent);
		for($i=0;$i<$cp;$i++)
			if($parent[$i] == 0)
				unset($parent[$i]);
		if(!empty($parent))
			$parent = json_encode(array_values($parent));
		else
			$parent = 0;
		$data = array('user_level'=>$this->input->post('user_level'),'parent'=>$parent);
		$this->db->insert('user_level',$data);
	}
	
	/**
	* Create a new Facebook user on the system
	*  
	* @param array $data
	* 
	*/
	function create_fb_user($data)
	{
		if($this->db->insert('fb_user', $data))
			$user_level = $this->db->insert_id();
	}

	/**
	* Create a new user group on the system
	* 
	*/
	function create_u_group()
	{
		$parent = $this->input->post('parent');
		$cp = count($parent);
		for($i=0;$i<$cp;$i++)
			if($parent[$i] == 0)
				unset($parent[$i]);
		if(!empty($parent))
			$parent = json_encode(array_values(array_unique($parent)));
		else
			$parent = 0;
		$data = array('user_group'=>$this->input->post('user_group'),'parent'=>$parent);
		if($this->db->insert('user_group', $data))
			$user_group = $this->db->insert_id();
	}
	
	/**
	* Delete from the DB all non-activated users after the given activation period
	*
	* @param int expire_period
	* 
	*/
	function purge_na($expire_period = 172800)
	{
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$this->db->delete($this->table_name);
	}

	/**
	* Delete a user from the system
	*
	* @param int user_id
	* 
	*/
	function delete_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->table_name);
	}
	
	/**
	* Check if a user lavel has users assigned to it
	* 
	* @param int level_id
	* @return bool
	* 
	*/
	function level_has_users($level_id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('u_level_id',$level_id);
		if($this->db->count_all_results() > 0)
			return TRUE;
		return FALSE;
	}
	
	/**
	* Check if a user is an administrator of the system
	*  
	* @param int $user_id
	* @return bool
	* 
	*/
	function is_admin($user_id)
	{
		$this->db->select('u_level_id');
		$this->db->from('users');
		$this->db->where('user_id',$user_id);
		$this->db->where('u_level_id',1);
		if($this->db->count_all_results() > 0)
			return TRUE;
		return FALSE;
	}
	
	/**
	* Check if a user group has users assigned to it
	*
	* @param int $group_id
	* @return bool
	* 
	*/
	function group_has_users($group_id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('u_group_id',$group_id);
		if($this->db->count_all_results() > 0)
			return TRUE;
		return FALSE;
	}
	
	/**
	* Delete a user level form the DB
	* 
	* @param int $level_id
	* @return bool
	* 
	*/
	function delete_level($level_id)
	{
		if($this->level_has_users($level_id))
			return FALSE;
		$this->db->where('u_level_id', $level_id);
		$this->db->delete("user_level");
			return TRUE;
	}
	
	/**
	* Delete a user group from the DB
	* 
	* @param undefined $group_id
	* @return bool
	* 
	*/
	function delete_group($group_id)
	{
		if($this->group_has_users($group_id))
			return FALSE;
		$this->db->where('u_group_id', $group_id);
		$this->db->delete("user_group");
			return TRUE;
	}
	
	/**
	* Update the status of a user's activation
	* 
	* @param int $user_id
	* @param int $status
	* 
	*/
	function status($user_id,$status)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update($this->table_name, array('activated'=>$status));
	}
	
	/**
	* Update a user's group membership
	*
	* @param int $user_id
	* @param int $group
	* 
	*/
	function update_group($user_id,$group)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update($this->table_name,array('u_group_id'=>$group));
	}
	
	/**
	* Make a user an administrator of the system
	* 
	* @param int $user_id
	* 
	*/
	function make_admin($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('users',array('u_level_id'=> 1));
	}
	
	/**
	* Reemove the administrator status from a user
	* 
	* @param int $user_id
	* 
	*/
	function remove_admin($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('users', array('u_level_id'=> 2));
	}
	
	/**
	* Update the status of a user level
	* 
	* @param int $u_level_id
	* @param int $status
	* 
	*/
	function status_level($u_level_id,$status)
	{
		$this->db->where('u_level_id', $u_level_id);
		$this->db->update("user_level", array('activate'=>$status));
	}
	
	/**
	* Update the ststus of a user group
	* 
	* @param int $u_group_id
	* @param int $status
	* 
	*/
	function status_group($u_group_id,$status)
	{
		$this->db->where('u_group_id', $u_group_id);
		$this->db->update("user_group", array('activate'=>$status));
	}
	
	/**
	* Update a user's login time
	* 
	* @param undefined $user_id
	* 
	*/
	function login_time($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update($this->table_name, array('last_login'=>date('Y-m-d H:i:s')));
	}

	/**
	* Get a list of all users of the system
	*
	* @return object
	* 
	*/
	function get_user_list()
	{
		$this->db->select('*');
		$this->db->from('users');
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	* Get a paginated list of all user levels
	* 
	* @param int $num
	* @param int $offset
	* @return object
	* 
	*/
	function get_user_level_list($num, $offset)
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$query = $this->db->get('',$num, $offset);
		return $query->result();
	}
	
	/**
	* Get a paginated list of all user groups 
	* 
	* @param int $num
	* @param int $offset
	* @return object
	* 
	*/
	function get_user_group_list($num, $offset)
	{
		$this->db->select('*');
		$this->db->from('user_group');
		$query = $this->db->get('',$num, $offset);
		return $query->result();
	}
	
	/**
	* Get a paginated list of all users and their user levels
	* 
	* @param int $num
	* @param int $offset
	* @return object
	* 
	*/
	function get_display_list($num, $offset)
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','left');
		$query = $this->db->get('',$num, $offset);
		return $query->result();
	}
	
	/**
	* Get the details of the latest backup job
	* 
	* @return array
	* 
	*/
	function get_lastbackup()
	{
		$this->db->select('*');
		$this->db->from('backup');
		$query = $this->db->get();
		$results = $query->result();
		foreach($results as $result)
			$last_backup = array('time' => $result->last_backup, 'user' => $this->get_fullname($result->user_id));
		return $last_backup;	
	}

	/**
	* Backup the current state of the DB
	* 
	*/
	function backup()
	{
		$prefs = array(
			// Array of tables to backup.
			'tables'	=> array('users','user_group','user_level','auth_settings','acl_resources','acl_rules','acl_rule_values','acl_resource_types'),
			'ignore'	=> array(),                                             		// List of tables to omit from the backup
			'format'	=> 'txt',                                              			// gzip, zip, txt
			'add_drop'	=> TRUE,                                                		// Whether to add DROP TABLE statements to backup file
			'add_insert'=> TRUE,														// Whether to add INSERT data to backup file
			'newline'	=> "\n"															// Newline character used in backup file
		);
		$backup = $this->dbutil->backup($prefs);	
		$data = array('last_backup' => date('Y-m-d H:i:s'),'user_id'=>$this->session->userdata('user_id'));
		$this->db->insert('backup', $data);
		$this->load->helper('download');
		force_download('Backup of CMS DB As At '.date("Y-m-d H:i:s").'.sql', $backup);
	}
	
	/**
	* Get a non-paginated list of all users 
	* 
	* @param int $user_id
	* @return object
	* 
	*/
	function get_user_list_without($user_id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('user_id <>', $user_id);
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	* Get the fullnames of a particular user
	* 
	* @param int $user_id
	* @return object
	* 
	*/
	function get_fullname($user_id)
	{
		$this->db->select('fullname');
		$this->db->from('users');
		$this->db->where('user_id = ',$user_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('fullname');
		else
			return NULL;
	}
	
	/**
	* Get the username of a particular user
	*
	* @param int $user_id
	* @return object
	* 
	*/
	function get_username($user_id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('user_id = ',$user_id);
		$query = $this->db->get();
		$results = $query->result();
		foreach($results as $result)
			return $result->username;
	}
	
	/**
	* Select the details of a user group
	* 
	* @param int $id
	* @return string
	* 
	*/
	function get_group($id)
	{
		$this->db->select('*');
		$this->db->from('user_group');
		$this->db->where('u_group_id = ',$id);
		$query = $this->db->get();
		$results = $query->result();
		foreach($results as $result)
			return $result->user_group;
	}
	
	/**
	* Get the details of a user level
	* 
	* @param int $id
	* @return object
	* 
	*/
	function get_level($id)
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$this->db->where('u_level_id = ',$id);
		$query = $this->db->get();
		$results = $query->result();
		foreach($results as $result)
			return $result->user_level;
	}
	
	/**
	* Get the name of a status
	* 
	* @param int $val
	* @return string
	* 
	*/
	function get_status($val)
	{
		if($val==1)
			return "activate";
		return "deactivate";
	}
	
	/**
	* Get the status of a user level
	* 
	* @param int $id
	* @return int
	* 
	*/
	function get_level_status($id)
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$this->db->where('u_level_id',$id);
		$query = $this->db->get();
		$results = $query->result();
		foreach($results as $result)
			return $result->activate;
	}
	
	/**
	* Get the status of a user group
	* 
	* @param int $id
	* @return int
	* 
	*/
	function get_group_status($id)
	{
		$this->db->select('*');
		$this->db->from('user_group');
		$this->db->where('u_group_id',$id);
		$query = $this->db->get();
		$results = $query->result();
		foreach($results as $result)
			return $result->activate;
	}
	
	/**
	* Get all user levels
	* 
	* @return object
	*  
	*/
	function get_user_levels()
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get all user groups
	* 
	* @return object
	* 
	*/
	function get_user_groups()
	{
		$this->db->select('*');
		$this->db->from('user_group');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function get_no_of_groups()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('u_group_id','0');
		return $this->db->count_all_results();
	}
	
	function check_fb_user($fb_id)
	{
		$this->db->select('*');
		$this->db->from('fb_user');
		$this->db->where('facebook_id',$fb_id);
		return $this->db->count_all_results();
	}
	
	function get_no_of_deactivated_users()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('activated',0);
		return $this->db->count_all_results();
	}
	
	function get_no_of_users()
	{
		$this->db->select('*');
		$this->db->from('users');
		return $this->db->count_all_results();
	}

	function select_edit_user($userid)
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','LEFT');
		$this->db->where('user_id',$userid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	function select_edit_level($level_id)
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$this->db->where('u_level_id',$level_id);
		$query = $this->db->get();
		return $query->result();
	}

	function select_edit_group($group_id)
	{
		$this->db->select('*');
		$this->db->from('user_group');
		$this->db->where('u_group_id',$group_id);
		$query = $this->db->get();
		return $query->result();
	}

	function select_edit_settings($setting_id)
	{
		$this->db->select('*');
		$this->db->from('auth_settings');
		$this->db->where('setting_id',$setting_id);
		$query = $this->db->get();
		return $query->row();
	}
	
	/**
	* Update the details of a user account
	* Accessible to the administrator - Can change any detail of the user account 
	* 
	* @param int userid
	* 
	*/
	function update_user_profile($userid)
	{
		$group = $this->input->post('group');
		$c = count($group);
		for($i=0;$i<$c;$i++)
			if($group[$i] == 0)
				unset($group[$i]);
		if(!empty($group))
			$u_group_id = json_encode(array_values(array_unique($group)));
		else
			$u_group_id = 0;
		$data = array('u_level_id'=>$this->input->post('u_level_id'),'username'=>$this->input->post('username'),
			'email'=>$this->input->post('email'),'fullname'=>$this->input->post('fullname'),'u_group_id'=>$u_group_id);
		if($this->input->post('new_password') != '') {
			// Load the hashing class
			$hasher = new PasswordHash($this->auth_model->get_auth_setting_value('phpass_hash_strength'),$this->auth_model->get_auth_setting_value('phpass_hash_portable'));
			// Hash the given password
			$data['password'] = $hasher->HashPassword($this->input->post('new_password'));
		}
		$this->db->where('user_id',$userid);
		$this->db->update('users',$data);
	}
	
	/**
	* Allow a use to update their profile
	* Just the password for now
	* 
	* @param int userid
	* 
	*/
	function update_my_profile($user_id)
	{
		if($this->input->post('new_password') != '' && $this->input->post('confirm_new_password') != '') {
			// Load the hashing class
			$hasher = new PasswordHash($this->auth_model->get_auth_setting_value('phpass_hash_strength'),$this->auth_model->get_auth_setting_value('phpass_hash_portable'));
			// Hash the given password
			$data['password'] = $hasher->HashPassword($this->input->post('new_password'));
			
			$this->db->where('user_id',$user_id);
			$this->db->update('users',$data);
			redirect('admin/my_profile/1');
		} else {
			redirect('admin/my_profile');
		}
	}

	function update_u_group($u_group)
	{
		$data = array('user_group'=>$this->input->post('user_group'));
		$parent = $this->input->post('parent');
		$c = count($parent);
		for($i=0;$i<$c;$i++)
			if($parent[$i] == 0)
				unset($parent[$i]);
		if(!empty($parent))
			$data['parent'] = json_encode(array_values(array_unique($parent)));
		else
			$data['parent'] = 0;
		$this->db->where('u_group_id',$u_group);
		$this->db->update('user_group',$data);
	}
	
	function update_setting($setting_id)
	{
		$data = array('setting_value'=>$this->input->post('setting_value'));
		$this->db->where('setting_id',$setting_id);
		$this->db->update('auth_settings',$data);
	}
	
	function update_bool_setting($setting_id,$status)
	{
		$this->db->where('setting_id',$setting_id);
		$this->db->update('auth_settings', array('setting_value'=>$status));
	}
	
	function get_level_id($level)
	{
		$this->db->where('user_level', $level);
		$query = $this->db->get('user_level');
		if ($query->num_rows() == 1)
			return $query->row()->u_level_id;
		return 0;
	}
	
	function get_level_details($level_id)
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$this->db->where('u_level_id',$level_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function get_level_parent($level_id)
	{
		$this->db->select('user_level');
		$this->db->from('user_level');
		$this->db->where('u_level_id',$level_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('user_level');
		else
			return NULL;
	}
	
	function get_level_parents()
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function update_u_level($u_level)
	{
		$data = array('user_level'=>$this->input->post('user_level'));
		$parent = $this->input->post('parent');
		$cp = count($parent);
		for($i=0;$i<$cp;$i++)
			if($parent[$i] == 0)
				unset($parent[$i]);
		if(!empty($parent))
			$data['parent'] = json_encode(array_values(array_unique($parent)));
		else
			$data['parent'] = 0;
		$this->db->where('u_level_id',$u_level);
		$this->db->update('user_level',$data);
	}
	
	function get_group_details($group_id)
	{
		$this->db->select('*');
		$this->db->from('user_group');
		$this->db->where('u_group_id',$group_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function get_group_parent($group_id)
	{
		$this->db->select('user_group');
		$this->db->from('user_group');
		$this->db->where('u_group_id',$group_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('user_group');
		else
			return NULL;
	}
	
	function get_group_parents()
	{
		$this->db->select('*');
		$this->db->from('user_group');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function update_user_key($new_id , $new_key)
	{
		$this->db->where('user_id', $new_id);
		$this->db->update($this->table_name, array('user_key'=>$new_key));
	}

	/**
	* Activate a user using their user key
	* Change the user key so that they cannot use the same key to activate again
	* 
	* @param string key
	* 
	*/
	function activate_user_by_key($key)
	{
		$this->db->where('user_key', $key);
		$this->db->update($this->table_name, array('activated'=>1,'user_key'=>md5($key.time())));
	}
	
	/**
	* Replace the user password with a new one they enter
	* Also changes the user_key so that the same one can't be used to replace the password again
	* Password is made by concatenating the new password and email address of the user and hashing that value
	* User key is made by concatenating the current key and current UNIX timestamp and hashing that value 
	*  
	* 
	* @param string key
	* @param string password
	* @param string email
	* 
	*/
	function update_user_password($key , $password, $email)
	{
		$this->db->where('user_key', $key);
		$this->db->update($this->table_name, array('password'=>md5($password.$email),'user_key'=>md5($key.time())));
	}

	function search_user($num, $offset, $keyword)
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','LEFT');
		$this->db->where('LOWER(users.fullname) LIKE ',"%".strtolower($keyword)."%");
		$this->db->or_where('LOWER(users.username) LIKE ',"%".strtolower($keyword)."%");
		$query = $this->db->get('',$num,$offset);
		foreach ($query->result() as $row)
			$data[] = $row;
		if(isset($data))
			return $data;
		else	
			return FALSE;
		
	}

	function sort_tbl($num, $offset, $column ,$direction)
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','INNER');
		$this->db->order_by($column ,$direction);
		$query = $this->db->get('',$num,$offset);
		foreach ($query->result() as $row)
			$data[] = $row;
		if(!empty($data))
			return $data;
		else
			return NULL;
	}
	
	function get_settings_display_list()
	{
		$this->db->select('aus.*,st.type_id')->from('auth_settings as aus')->join('setting_types as st','aus.setting_type = st.type_id','INNER');
		$this->db->where('aus.user_editable',1);
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_ungrouped_list_dash()
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level','user_level.u_level_id = users.u_level_id','INNER');
		$this->db->where('users.u_group_id','0');
		$this->db->limit(5);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function get_ungrouped_list()
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','INNER');
		$this->db->where('u_group_id','0');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function ungrouped_list_num()
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','INNER');
		$this->db->where('u_group_id',0);
		return $this->db->count_all_results();
	}
	
	function no_of_admins()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('u_level_id',0);
		return $this->db->count_all_results();
	}
	
	function get_deactivated_list_dash()
	{
		$this->db->select('u.*,ul.user_level');
		$this->db->from('users as u');
		$this->db->join('user_level as ul', 'ul.u_level_id = u.u_level_id','INNER');
		$this->db->where('u.activated',0);
		$this->db->limit(5);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function deactivated_list_num()
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','INNER');
		$this->db->where('activated','0');
		return $this->db->count_all_results();
	}

	function get_deactivated_list()
	{
		$this->db->select('*,user_level.user_level');
		$this->db->from('users');
		$this->db->join('user_level', 'user_level.u_level_id = users.u_level_id','INNER');
		$this->db->where('activated','0');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function isadmin()
	{
		if($this->session->userdata('user_level') == 0)
			return TRUE;
		return FALSE;
	}
	
	/**
	* Get the date of registration for a particular user
	* 
	* @param int user_id
	* 
	*/
	function get_user_reg_date($user_id)
	{
		$this->db->select('created');
		$this->db->from('users');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('created');
		else	
			return NULL;
	}
	
	/**
	* Get the date and time that the user last logged in
	* 
	* @param int user_id
	* 
	*/
	function get_user_last_login($user_id)
	{
		$this->db->select('last_login');
		$this->db->from('users');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('last_login');
		else	
			return NULL;
	}
	
	/**
	* Get the number of events that a user has submitted
	*  
	* @param int user_id
	* 
	*/
	function get_no_of_user_events($user_id)
	{
		$this->db->select('id');
		$this->db->from('dates_entries');
		$this->db->where('user',$user_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->num_rows();
		else
			return 0;
	}
	
	/**
	* Select all pages from the DB
	* 
	*/
	function get_all_pages()
	{
		$this->db->select('page_id,page_title,page_url');
		$this->db->from('pages');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
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
		$this->db->select('value');
		$this->db->from('settings');
		$this->db->where('code','site_name');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('value');
		else
			return NULL;
	}
	
	/**
	* Get page view statistics from the database
	* 
	*/
	function get_page_view_stats()
	{
		$this->db->select('p.page_title,pv.views');
		$this->db->from('pages as p');
		$this->db->join('page_views as pv','p.page_uuid = pv.page','INNER');
		$this->db->order_by('pv.views','desc');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get post view statistics from the database
	* 
	*/
	function get_post_view_stats()
	{
		$this->db->select('p.post_title,pv.views');
		$this->db->from('blog_posts as p');
		$this->db->join('post_views as pv','p.post_uuid = pv.post','INNER');
		$this->db->order_by('pv.views','desc');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Get a list of 5 most recent logins to be showed on the dashboard
	* 
	*/
	function get_recent_logins()
	{
		$this->db->select('username,last_login');
		$this->db->from('users');
		$this->db->order_by('last_login','desc');
		$this->db->where('last_login >',0);
		$this->db->limit('5');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
}
/* End of file admin_model.php */
/* Location: ./application/modules/admin/models/admin_model.php */