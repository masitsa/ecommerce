<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('./application/libraries/phpass-0.3/PasswordHash.php');

class Auth_lib
{
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('session'); // Just in case it isn't loaded automatically
		$this->ci->load->config('auth',TRUE);
		$this->ci->lang->load('auth');
		$this->ci->load->model('auth/login_attempts');
		$this->ci->load->model('auth/user_autologin');
		$this->ci->load->model('auth/auth_model');
	}
	
	/**
	* Check if there is a user logged in
	*
	* @param	bool
	* @return	bool
	* 
	*/
	function is_logged_in()
	{
		if($this->ci->session->userdata('logged_in'))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	* Redirect from any controller to a view with a message to be displayed
	* 
	* @param string url
	* @param string message_type
	* 
	*/
	function redirect_with_message($url,$message_type)
	{
		$message = $this->ci->lang->line($message_type);
		$this->ci->session->set_flashdata('alert',$message);
		redirect($url);
	}
	
	/**
	* Redirect from any controller to a view with an error to be displayed
	* 
	* @param string url
	* @param string message_type
	* 
	*/
	function redirect_with_error($url,$message_type)
	{
		$message = $this->ci->lang->line($message_type);
		$this->ci->session->set_flashdata('error',$message);
		redirect($url);
	}
	
	/**
	* Login user automatically if he/she provides correct autologin verification
	*
	* @return	void
	* 
	*/
	function autologin()
	{
		if(!$this->is_logged_in()) {			// not logged in (as any user)
			if($cookie = get_cookie($this->ci->config->item('autologin_cookie_name','auth'), TRUE)) {
				$data = unserialize($cookie);
				if (isset($data['key']) AND isset($data['user_id'])) {
					if (!is_null($user = $this->ci->user_autologin->get($data['user_id'], md5($data['key'])))) {
						// Login user
						$this->ci->session->set_userdata(array(
							'username'  => $user->username,
							'fullname'  => $user->fullname,
							'user_level'  => $user->u_level_id,
							'user_id'     => $user->user_id,
							'user_group'  => $user->u_group_id,
							'logged_in' => TRUE,
							'logout' => 'auth/logout'
							));
						// Renew users cookie to prevent it from expiring
						set_cookie(array(
							'name' 		=> $this->ci->config->item('autologin_cookie_name','auth'),
							'value'		=> $cookie,
							'expire'	=> $this->ci->config->item('autologin_cookie_life','auth'),
						));
						$this->ci->auth_model->update_login_info(
							$user->user_id,
							$this->ci->config->item('login_record_ip','auth'),
							$this->ci->config->item('login_record_time','auth'));
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}
	
	/**
	* Save data for user's autologin
	*
	* @param int user_id
	* @return bool
	* 
	*/
	function create_autologin($user_id)
	{
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);
		$this->ci->user_autologin->purge($user_id);
		if ($this->ci->user_autologin->set($user_id, md5($key))) {
			set_cookie(array(
				'name' 		=> $this->ci->config->item('autologin_cookie_name','auth'),
				'value'		=> serialize(array('user_id' => $user_id, 'key' => $key)),
				'expire'	=> $this->ci->config->item('autologin_cookie_life','auth'),
			));
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	* Clear user's autologin data
	*
	* @return void
	* 
	*/
	function delete_autologin()
	{
		if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name','auth'),TRUE)) {
			$data = unserialize($cookie);
			if(isset($data['user_id']) && isset($data['key'])){
				$this->ci->user_autologin->delete($data['user_id'], md5($data['key']));
				delete_cookie($this->ci->config->item('autologin_cookie_name','auth'));
			}
		}
	}
	
	/**
	*  Send email message of given type (activate, new_password e.t.c.)
	* 
	*/ 
	function _send_email($type, $email, &$data)
	{
		$this->ci->load->library('email');
		$this->ci->email->from($this->ci->auth_model->get_auth_setting_value('webmaster_email'),'System');
		$this->ci->email->reply_to($this->ci->auth_model->get_auth_setting_value('webmaster_email'),'System');
		$this->ci->email->to($email);
		$this->ci->email->subject($data['subject']);
		$this->ci->email->message($this->ci->load->view('auth/email/'.$type.'-html', $data, TRUE));
		$this->ci->email->set_alt_message($this->ci->load->view('auth/email/'.$type.'-txt', $data, TRUE));
		$this->ci->email->send();
	}
}