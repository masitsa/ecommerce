<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Session extends CI_Session
{
	var $analytics_table_name = '';
	var $analytics_data = array();
	
	/**
	 * Write the session data
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_write()
	{
		// Are we saving custom data to the DB?  If not, all we do is update the cookie
		if ($this->sess_use_database === FALSE)
		{
			$this->_set_cookie();
			return;
		}

		// set the custom userdata, the session data we will set in a second
		$custom_userdata = $this->userdata;
		$cookie_userdata = array();

		// Before continuing, we need to determine if there is any custom data to deal with.
		// Let's determine this by removing the default indexes to see if there's anything left in the array
		// and set the session data while we're at it
		foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
		{
			unset($custom_userdata[$val]);
			$cookie_userdata[$val] = $this->userdata[$val];
		}

		// Did we find any custom data?  If not, we turn the empty array into a string
		// since there's no reason to serialize and store an empty array in the DB
		if (count($custom_userdata) === 0)
		{
			$custom_userdata = '';
		}
		else
		{
			// Serialize the custom data array so we can store it
			$custom_userdata = $this->_serialize($custom_userdata);
		}

		// Run the update query
		$this->CI->db->where('session_id', $this->userdata['session_id']);
		$this->CI->db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata));

		// Write the cookie.  Notice that we manually pass the cookie data array to the
		// _set_cookie() function. Normally that function will store $this->userdata, but
		// in this case that array contains custom data, which we do not want in the cookie.
		$this->_set_cookie($cookie_userdata);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Create a new session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_create()
	{
		$this->CI->load->config('analytics',TRUE);
		$this->analytics_table_name = $this->CI->config->item('analytics_table','analytics');
		
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}
		
		// To make the session ID even more secure we'll combine it with the user's IP
		$sessid .= $this->CI->input->ip_address();
		
		$this->userdata = array(
							'session_id'	=> md5(uniqid($sessid, TRUE)),
							'ip_address'	=> $this->CI->input->ip_address(),
							'user_agent'	=> substr($this->CI->input->user_agent(), 0, 120),
							'last_activity'	=> $this->now,
							'user_data'		=> ''
							);
		
		// Save the data to the DB if needed
		if ($this->sess_use_database === TRUE)
		{
			$this->CI->db->query($this->CI->db->insert_string($this->sess_table_name, $this->userdata));
		}
		
		$this->analytics_data = array(
							'session_id'	=> $this->userdata['session_id'],
							'ip_address'	=> $this->CI->input->ip_address(),
							'user_agent'	=> substr($this->CI->input->user_agent(), 0, 120),
							'session_start'	=> $this->now,
							'last_activity' => $this->now,
							);
		
		// Save the session data to the analytics table
		$this->CI->db->query($this->CI->db->insert_string($this->analytics_table_name, $this->analytics_data));
		
		// Write the cookie
		$this->_set_cookie();
	}
	
	/**
	 * Update an existing session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_update()
	{
		$this->CI->load->config('analytics',TRUE);
		$this->analytics_table_name = $this->CI->config->item('analytics_table','analytics');
		
		// skip the session update if this is an AJAX call
		if(!$this->CI->input->is_ajax_request()) {
			
			// We only update the session every five minutes by default
			if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
			{
				return;
			}
			
			// Save the old session id so we know which record to
			// update in the database if we need it
			$old_sessid = $this->userdata['session_id'];
			$new_sessid = '';
			while (strlen($new_sessid) < 32)
			{
				$new_sessid .= mt_rand(0, mt_getrandmax());
			}
			
			// To make the session ID even more secure we'll combine it with the user's IP
			$new_sessid .= $this->CI->input->ip_address();
			
			// Turn it into a hash
			$new_sessid = md5(uniqid($new_sessid, TRUE));
			
			// Update the session data in the session data array
			$this->userdata['session_id'] = $new_sessid;
			$this->userdata['last_activity'] = $this->now;
			
			// _set_cookie() will handle this for us if we aren't using database sessions
			// by pushing all userdata to the cookie.
			$cookie_data = NULL;
			
			// Update the session ID and last_activity field in the DB if needed
			if ($this->sess_use_database === TRUE)
			{
				// set cookie explicitly to only have our session data
				$cookie_data = array();
				foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
				{
					$cookie_data[$val] = $this->userdata[$val];
				}
				
				$this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid), array('session_id' => $old_sessid)));
			}
			
			// Update the analytics table with the new session id
			$this->CI->db->query($this->CI->db->update_string($this->analytics_table_name, array('last_activity' => $this->now,'session_id' => $new_sessid), array('session_id' => $old_sessid)));
			
			// Write the cookie
			$this->_set_cookie($cookie_data);
		}
	}
	
	/**
	 * Destroy the current session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_destroy()
	{
		$this->CI->load->config('analytics',TRUE);
		$this->analytics_table_name = $this->CI->config->item('analytics_table','analytics');
		
		if (isset($this->userdata['session_id'])) {
			// Update the analytics table with the session end time
			$this->CI->db->query($this->CI->db->update_string($this->analytics_table_name, array('session_end' => $this->now), array('session_id' => $this->userdata['session_id'])));
		}
		
		// Kill the session DB row
		if ($this->sess_use_database === TRUE && isset($this->userdata['session_id']))
		{
			$this->CI->db->where('session_id', $this->userdata['session_id']);
			$this->CI->db->delete($this->sess_table_name);
		}		
		
		// Kill the cookie
		setcookie(
					$this->sess_cookie_name,
					addslashes(serialize(array())),
					($this->now - 31500000),
					$this->cookie_path,
					$this->cookie_domain,
					0
				);
		
		// Kill session data
		$this->userdata = array();
	}
	
	/**
	 * Garbage collection
	 * 
	 * Extended to also destroy analytics data that does not have any pages visited (Meaning it could be a back-end session)
	 * Exempts the current session (obviously)
	 *
	 * This deletes expired session rows from database
	 * if the probability percentage is met
	 *
	 * @access	public
	 * @return	void
	 * 
	 */
	function _sess_gc()
	{
		$this->CI->load->config('analytics',TRUE);
		$this->analytics_table_name = $this->CI->config->item('analytics_table','analytics');
		
		if ($this->sess_use_database != TRUE)
		{
			return;
		}
		
		srand(time());
		$expire = $this->now - $this->sess_expiration;
		
		if ((rand() % 100) < $this->gc_probability)
		{
			
			$this->CI->db->where("last_activity < {$expire}");
			$this->CI->db->delete($this->sess_table_name);
			
			$this->CI->db->where("last_activity < {$expire}");
			$this->CI->db->where('session_id !=',$this->userdata['session_id']);
			$this->CI->db->where('pages_visited IS NULL');
			$this->CI->db->delete($this->analytics_table_name);
			
			log_message('debug', 'Session garbage collection performed.');
		}
	}
}
/* End of file MY_Session.php */
/* Location: ./application/libraries/MY_Session.php */