<?php
define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

class Auth_model extends CI_Model
{
	private $table_name	= 'users';	// user accounts
	private $error = array();		// Array for holding errors
	private $auth_settings_table = 'auth_settings';	// Table that holds the auth module settings
	
	function __construct()
	{
		parent::__construct();
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
	* Select user from DB by user key
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
	* Select user from DB by email address
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
	* Select user from DB by login (either the username or the email address)
	* 
	* @param string $email
	* @return object
	*/
	function get_user_by_login($login)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('LOWER(username)', strtolower($login));
		$this->db->or_where('LOWER(email)', strtolower($login));
		$query = $this->db->get();
		if ($query->num_rows() !=0 ) 
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Check if a username is available when registering a new user
	* 
	* @param string $username
	* @return bool
	* 
	*/
	function is_username_available($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)',strtolower($username));
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
	 * Log a user into the system
	 *
	 * @param array $data The credentials of the user trying to log in
	 * @return bool
	 * 
	 */
	function login($data)
	{
		/*
		* Which function to use to login (based on config)
		* Always falls back to using the username even when that option is disabled
		* This prevents a situation where the admin could accidentally lock out everybody
		*/
		if($this->get_auth_setting_value('login_by_username') AND $this->get_auth_setting_value('login_by_email')) 
			$get_user_func = 'get_user_by_login';
		elseif($this->get_auth_setting_value('login_by_email')) 
			$get_user_func = 'get_user_by_email';
		else 
			$get_user_func = 'get_user_by_username';
		if($login_info = $this->$get_user_func($data['login'])) {
			$hasher = new PasswordHash($this->get_auth_setting_value('phpass_hash_strength'),$this->get_auth_setting_value('phpass_hash_portable'));
			if ($hasher->CheckPassword($data['password'],$login_info->password))
				return $login_info;
			else
				return FALSE;
		} else {
			return FALSE; 	
		}
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
		$user_group = json_encode($group_array);
		
		// Load the hashing class
		$hasher = new PasswordHash($this->get_auth_setting_value('phpass_hash_strength'),$this->get_auth_setting_value('phpass_hash_portable'));
		// Hash the given password
		$data['password'] = $hasher->HashPassword($this->input->post('password'));
		
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
		$data['user_key'] = md5(rand().microtime());
		$data = array('u_level_id' => $this->input->post('u_level_id'),'u_group_id' => $user_group,
			'username' => $this->input->post('username'),'email'=>$this->input->post('email'),'fullname'=>$this->input->post('fullname'));
		if ($this->db->insert($this->table_name, $data)){
			redirect('admin/show_profile');
			return array('new_id'=> $this->db->insert_id(),'create_time'=>$data['created'],'user_key'=>$data['user_key']);
		}
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
		// Load the hashing class
		$hasher = new PasswordHash($this->get_auth_setting_value('phpass_hash_strength'),$this->get_auth_setting_value('phpass_hash_portable'));
		// Hash the given password
		$data['password'] = $hasher->HashPassword($data['password']);
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
		$data['user_key'] = md5(rand().microtime());
		if ($this->db->insert($this->table_name, $data))
			return array('new_id'=> $this->db->insert_id(),'user_key'=>$data['user_key'],'fullname'=>$data['fullname'],'email'=>$data['email']);
		else
			return NULL;
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
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->where('user_id',$user_id);
		$this->db->where('u_level_id',1);
		if($this->db->count_all_results() > 0)
			return TRUE;
		return FALSE;
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
	* Activate a user using their user key
	* Change the user key so that they cannot use the same key to activate again
	* 
	* @param string key
	* 
	*/
	function activate_user_by_key($key)
	{
		$this->db->where('user_key', $key);
		$this->db->update($this->table_name, array('activated'=>1,'user_key'=>md5(rand().microtime())));
	}
	
	/**
	* Replace the user password with a new one they enter
	* Also changes the user_key so that the same one can't be used to replace the password again
	* 
	* @param string key
	* @param string password
	* 
	*/
	function update_user_password($key,$password)
	{
		// Load the hashing class
		$hasher = new PasswordHash($this->get_auth_setting_value('phpass_hash_strength'),$this->get_auth_setting_value('phpass_hash_portable'));
		// Hash the given password
		$password = $hasher->HashPassword($password);
		
		$this->db->where('user_key', $key);
		$this->db->update($this->table_name,array('password'=>$password,'user_key'=>md5(rand().microtime())));
	}
	
	/**
	* Get the level id of a particular user level 
	* 
	* @param string level
	* 
	*/
	function get_level_id($level)
	{
		$this->db->where('user_level', $level);
		$query = $this->db->get('user_level');
		if ($query->num_rows() == 1)
			return $query->row('u_level_id');
		return 0;
	}
	
	/**
	* Get the status of a user group
	* 
	* @param int $u_group_id
	* @return int
	* 
	*/
	function get_group_status($u_group_id)
	{
		$this->db->select('activate');
		$this->db->from('user_group');
		$this->db->where('u_group_id',$u_group_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('activate');
		else
			return NULL;
	}
	
	/**
	* Get the status of a user level
	* 
	* @param int $u_level_id
	* @return int
	* 
	*/
	function get_level_status($u_level_id)
	{
		$this->db->select('activate');
		$this->db->from('user_level');
		$this->db->where('u_level_id',$u_level_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('activate');
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
		return $this->get_auth_setting_value('website_name');
	}
	
	/**
	* Update user login info, such as IP-address or login time
	*
	* @param int user_id
	* @param bool Whether to record the IP address
	* @param bool Whether to record the login time
	* 
	*/
	function update_login_info($user_id,$record_ip,$record_time)
	{
		if($record_ip)
			$this->db->set('last_ip', $this->input->ip_address());
		if($record_time)
			$this->db->set('last_login', date('Y-m-d H:i:s'));
		$this->db->where('user_id', $user_id);
		$this->db->update($this->table_name);
	}
	
	/**
	* Check if login attempts exceeded max login attempts (specified in settings table)
	*
	* @param string login
	* @return bool
	* 
	*/
	function is_max_login_attempts_exceeded($login)
	{
		if ($this->get_auth_setting_value('login_count_attempts'))
			return $this->login_attempts->get_attempts_num($this->input->ip_address(), $login) >= $this->get_auth_setting_value('login_max_attempts');
		else
			return FALSE;
	}
	
	/**
	* Increase number of attempts for given IP-address and login
	* (if attempts to login is being counted)
	*
	* @param string login
	* 
	*/
	function increase_login_attempts($login)
	{
		if ($this->get_auth_setting_value('login_count_attempts'))
			if (!$this->is_max_login_attempts_exceeded($login))
				$this->login_attempts->increase_attempt($this->input->ip_address(), $login);
	}

	/**
	* Clear all attempt records for given IP-address and login
	* (if attempts to login is being counted)
	*
	* @param string login
	* @return void
	* 
	*/
	function clear_login_attempts($login)
	{
		if ($this->get_auth_setting_value('login_count_attempts'))
			$this->login_attempts->clear_attempts($this->input->ip_address(),$login,$this->get_auth_setting_value('login_attempt_expire'));
	}
	
	/**
	 * Get an array of all auth module settings from the DB
	 *
	 * @return array
	 *
	 */
	function get_all_auth_settings()
	{
		$this->db->select('*');
		$this->db->from($this->auth_settings_table);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result_array();
		else
			return NULL;
	}
	
	/**
	 * Get the value of a setting given the short_name
	 *
	 * @param string short_name
	 * @return string setting_value
	 *
	 */
	function get_auth_setting_value($short_name)
	{
		$this->db->select('setting_value');
		$this->db->from($this->auth_settings_table);
		$this->db->where('short_name',$short_name);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('setting_value');
		else
			return NULL;
	}
	
	/**
	 * Check if a user has logged in using Facebook before
	 *
	 * @param facebook_user_id The user's Facebook ID returned by the Facebook API
	 * @return bool
	 *
	 */
	function facebook_user_exists($facebook_user_id)
	{
		$this->db->select('id')->from('fb_user')->where('facebook_id',$facebook_user_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Save the details of a new Facebook user
	 * Saves everything returned by the Facebook API
	 * Also save the user in the users table
	 *
	 * @param array
	 *
	 */
	function save_new_facebook_user($user_details)
	{
		$data = array('facebook_id'=>$user_details['id'],'name'=>$user_details['name'],'first_name'=>$user_details['first_name'],
			'middle_name'=>$user_details['middle_name'],'last_name'=>$user_details['last_name'],'link'=>$user_details['link'],
			'username'=>$user_details['username'],'gender'=>$user_details['gender'],'email'=>$user_details['email'],
			'timezone'=>$user_details['timezone'],'locale'=>$user_details['locale']);
		$this->db->insert('fb_user',$data);
		$data = array('fb_user_id'=>$user_details['id'],'fullname'=>$user_details['name'],'username'=>$user_details['username'],
			'u_level_id'=>3,'email'=>$user_details['email'],'activated'=>1,'created'=>date('Y-m-d H:i:s'));
		$this->db->insert($this->table_name,$data);
	}
}
/* End of file auth_model.php */
/* Location: ./application/modules/auth/models/auth_model.php */