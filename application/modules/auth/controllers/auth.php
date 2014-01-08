<?php
class Auth extends MX_Controller
{
	function __construct()
	{
		parent:: __construct();
		$this->load->helper(array('cookie'));
		$this->load->library(array('form_validation','email','auth_lib'));
	}
	
	/**
	 * Default action for the module
	 *
	 */
	function index()
	{
		if($this->auth_lib->autologin() || $this->auth_lib->is_logged_in())
			redirect('admin/index');
		else
			redirect('auth/login');
	}
	
	/**
	* Log a user into the system
	* 
	*/	
	function login()
	{
		$data = array('password'=>$this->input->post('password'),'login'=>$this->input->post('login'));
		if($this->auth_model->get_auth_setting_value('login_by_username') AND $this->auth_model->get_auth_setting_value('login_by_email')) 
			$data['login_label'] = $this->lang->line('auth_login_label_both');
		elseif($this->auth_model->get_auth_setting_value('login_by_email')) 
			$data['login_label'] = $this->lang->line('auth_login_label_email');
		else 
			$data['login_label'] = $this->lang->line('auth_login_label_username');
		$this->form_validation->set_rules('login','Username','trim|required|xss_clean');
		$this->form_validation->set_rules('password','Password','trim|required|xss_clean');
		if ($this->form_validation->run()) {
			if($login_info = $this->auth_model->login($data)) {
				if($this->check_group_login($login_info)) {
					if($this->check_level_login($login_info)) {
						if($login_info->activated == 1) {
							if($this->input->post('remember_me'))
								$this->auth_lib->create_autologin($login_info->user_id);
							$this->login_info($login_info);
						} else {
							if($this->auth_model->get_auth_setting_value('admin_activate') == 1)
								$data['error'] = $this->lang->line('auth_message_account_not_activated_admin_activate');
							else
								$data['error'] = $this->lang->line('auth_message_account_not_activated_self_activate');
						}
					} else {
						$data['error'] = $this->lang->line('auth_message_level_not_activated');
					}
				} else {
					$data['error'] = $this->lang->line('auth_message_group_not_activated');
				}
				$this->load->view('login_form', $data);
			} else {
				$data['error'] = $this->lang->line('auth_incorrect_credentials');
				if($this->auth_model->get_auth_setting_value('login_count_attempts'))
					$this->auth_model->increase_login_attempts($this->input->post('login'));
				$this->load->view('login_form', $data);
			}
		} else {
			$this->load->view('login_form',$data);
		}
	}
	
	/**
	* Check if a user group is allowed to log in to the system
	* At the same time check if user groups are active on the system
	* 
	* @param object login_info
	* @return bool
	* 
	*/
	function check_group_login($login_info)
	{
		if($this->auth_model->get_auth_setting_value('user_groups_active')) {
			if($login_info->u_group_id > 0) {
				if($this->auth_model->get_group_status($login_info->u_group_id) == 1)
					return TRUE;
				else
					return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}
	
	/**
	* Check if a user level is allowed to log into the system
	* At the same time check if user levels are active on the system
	* 
	* @param object login_info
	* @return bool
	* 
	*/
	function check_level_login($login_info)
	{
		if($this->auth_model->get_auth_setting_value('user_levels_active')) {
			if($this->auth_model->get_level_status($login_info->u_level_id) == 1)
				return TRUE;
			elseif ($login_info->u_level_id == 0)
				return TRUE;
			else
				return FALSE;
		}
		return FALSE;
	}
	
	/**
	* Set the session data on user login
	*
	* @param object login_info
	* 
	*/
	function login_info($login_info)
	{
		$uman_session = array(
			'username'  => $login_info->username,
			'fullname'  => $login_info->fullname,
			'user_level'  => $login_info->u_level_id,
			'user_id'     => $login_info->user_id,
			'user_group'  => $login_info->u_group_id,
			'logged_in' => TRUE,
			'logout' => 'auth/logout'
		);
		$this->session->set_userdata($uman_session);
		$this->auth_model->clear_login_attempts($login_info->username);
		$this->auth_model->update_login_info($login_info->user_id,TRUE,TRUE);
		redirect('admin/index');
	}
	
	/**
	* Log a user out of the system
	* Manually unset the session data (just to be safe)
	* Destroy the session
	* 
	*/
	function logout()
	{
		$this->auth_lib->delete_autologin();
		$this->session->set_userdata(array('user_id' => '', 'username' => '', 'logged_in' => FALSE));
		$this->session->sess_destroy();
		redirect('auth/login');
	}
	
	/**
	* Resend the activation code to the user's email address 
	*  
	*/
	function resend_activate()
	{
		$this->form_validation->set_rules('email','Email Address','trim|required|valid_email');	
		$data['resend_activation'] = TRUE;
		if($this->form_validation->run()) {
			if(!$user_info = $this->auth_model->get_user_by_email($this->input->post('email'))) {
				$data['error'] = $this->lang->line('auth_email_not_found');
				$this->load->view('login_form',$data);
			} else {
				$mail_data['subject'] = "Account Activation";
				$mail_data['fullname'] = $user_info->fullname;
				$mail_data['user_key'] = $user_info->user_key;
				$this->_send_email('self_activate',$user_info->email,$mail_data);
				$data['message'] = $this->lang->line('auth_message_activation_email_sent');
				unset($data['resend_activation']);
				$this->load->view('login_form',$data);
			}
		} else {
			$this->load->view('login_form',$data);
		}
	}
	
	/**
	* Send the user the link that allows them to reset their password 
	* 
	*/
	function forgot_password()
	{
		$this->form_validation->set_rules('email','Email Address','trim|required|valid_email');
		$data['forgot_password'] = TRUE;
		if($this->form_validation->run()) {
			if(!$user_info = $this->auth_model->get_user_by_email($this->input->post('email'))) {
				$data['error'] = $this->lang->line('auth_email_not_found');
				$this->load->view('login_form',$data);
			} else {
				$mail_data['subject'] = "Password Recovery";
				$mail_data['fullname'] = $user_info->fullname;
				$mail_data['user_key'] = $user_info->user_key;
				$this->_send_email('password_reset',$user_info->email,$mail_data);
				$data['message'] = $this->lang->line('auth_message_new_password_sent');
				unset($data['forgot_password']);
				$this->load->view('login_form',$data);
			}
		} else{
			$this->load->view('login_form',$data);				
		}
	}
	
	/**
	* Replace a user's password with a new one they enter
	* 
	* @return string key
	* 
	*/
	function reset_password($key)
	{
		if($details = $this->auth_model->get_user_by_user_key($key)) {
			$data['new_password'] = TRUE;
			$this->form_validation->set_rules('password','New Password','trim|required|xss_clean
				|min_length['.$this->auth_model->get_auth_setting_value("password_min_length").']|max_length['.$this->auth_model->get_auth_setting_value("password_max_length").']');
			$this->form_validation->set_rules('confirm_password','Confirm Password','trim|required|xss_clean|matches[password]');
			if($this->form_validation->run()) {
				$this->auth_model->update_user_password($key,$this->input->post('password'));
				unset($data['new_password']);
				$data['message'] = $this->lang->line('auth_message_password_changed');
			}
			$this->load->view('login_form',$data);
		} else {
			$data['message'] = $this->lang->line('auth_message_new_password_failed');
			$this->load->view('login_form',$data); 
		}
	}
	
	/**
	* Register a new user on the system
	* 
	*/
	function register()
	{
		$data = array('u_level_id'=>$this->auth_model->get_level_id('user'),'username'=>$this->input->post('username'),
			'email'=>$this->input->post('email'),'fullname'=>$this->input->post('fullname'),'password'=>$this->input->post('password'));
		$this->form_validation->set_rules('email','Email','trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('username','Username','trim|required|xss_clean|alpha_dash
			|min_length['.$this->auth_model->get_auth_setting_value("username_min_length").']|max_length['.$this->auth_model->get_auth_setting_value("username_max_length").']');
		$this->form_validation->set_rules('fullname','Full Name','trim|required|xss_clean');
		$this->form_validation->set_rules('password','Password','trim|required|xss_clean|alpha_dash
			|min_length['.$this->auth_model->get_auth_setting_value("password_min_length").']|max_length['.$this->auth_model->get_auth_setting_value("password_max_length").']');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
		if ($this->form_validation->run()) {
			if(!$this->auth_model->is_username_available($this->input->post('username')))
				$data['username_error'] = $this->lang->line('auth_username_in_use');
			if(!$this->auth_model->is_email_available($this->input->post('email')))
				$data['email_error'] = $this->lang->line('auth_email_in_use');
			if($this->input->post('bot_honey') != '') // If a bot is trying to register
				$data['error'] = $this->lang->line('auth_bot_detected');
			else {
				if($new_user= $this->auth_model->register_user($data,FALSE)) {
					unset($data['password']);	// Destroy the password just in case
					unset($data['email']);
					if($this->auth_model->get_auth_setting_value('admin_activate')) {
						$new_user['subject'] = 'Account Registration';
						$this->_send_email('admin_activate',$this->auth_model->get_auth_setting_value('webmaster_email'),$new_user);
						$data['message'] = $this->lang->line('auth_message_registration_completed_3');
					} else {
						$new_user['subject'] = 'Account Registration';
						$this->_send_email('self_activate',$new_user['email'],$new_user);
						$data['message'] = $this->lang->line('auth_message_registration_completed_1');
					}
				}
			}
		}
		$this->load->view('signup_form',$data);
	}
	
	/**
	* Activate a user account through the email link 
	* 
	* @param string key
	* 
	*/
	function email_activate($key)
	{
		if(!$this->auth_model->get_auth_setting_value('admin_activate')) {
			if($this->auth_model->get_user_by_user_key($key)) {
				$this->auth_model->activate_user_by_key($key);
				$data['message'] = $this->lang->line('auth_message_activation_completed');
			} else {
				$data['message'] = $this->lang->line('auth_message_activation_failed');
			}
		} else {
			$data['message'] = $this->lang->line('auth_message_activation_disallowed');
		}
		$this->load->view('signup_form',$data); 
	}

	/**
	* Allow admin to activate an account through the link 
	* sent to their email address 
	* 
	* @param string key
	* 
	*/
	function admin_email_activate($key)
	{
		if($info = $this->auth_model->get_user_by_user_key($key)) {
			$this->auth_model->activate_user_by_key($key);
			$data['message'] = $this->lang->line('auth_message_admin_activation_completed');
			$mail_data['subject'] = 'Account Activation';
			$mail_data['username'] = $info->username;
			$mail_data['fullname'] = $info->fullname;
			$this->_send_email('admin_activated_account',$info->email,$mail_data);
			$this->load->view('signup_form',$data); 
		} else {
			$data['message'] = $this->lang->line('auth_message_activation_failed');
			$this->load->view('signup_form',$data); 
		}
	}
	
	/**
	*  Send email message of given type (activate, new_password e.t.c.)
	* 
	*/ 
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->auth_model->get_auth_setting_value('webmaster_email'),'System');
		$this->email->reply_to($this->auth_model->get_auth_setting_value('webmaster_email'),'System');
		$this->email->to($email);
		$this->email->subject($data['subject']);
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}
}
/* End of file auth.php */
/* Location: ./application/modules/auth/controllers/auth.php */