<?php
require_once "./application/modules/site/controllers/site.php";
class Auth extends Site
{
	private $theme	= '';
	private $tables	= array();
	private $table_id = '';
	
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('cookie'));
		$this->load->library(array('form_validation','auth_lib'));
	}
	
	/**
	 * Check if the user is logged in
	 *
	 */
	function index()
	{
		if(!$this->auth_lib->is_logged_in())
			redirect('site/auth/logout');
		else
			redirect('site/home');
	}
	
	/**
	 * Log in to the frontend of the system
	 * Note that the frontend and backend share a common session
	 *
	 */
	function login()
	{
		$this->template->set_template('auth/themes/misty/auth');
		
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
				$this->template->content->view('auth/themes/misty/login',$data);
				$this->template->publish();
			} else {
				$data['error'] = $this->lang->line('auth_incorrect_credentials');
				if($this->auth_model->get_auth_setting_value('login_count_attempts'))
					$this->auth_model->increase_login_attempts($this->input->post('login'));
				$this->template->content->view('auth/themes/misty/login',$data);
				$this->template->publish();
			}
		} else {
			$this->template->content->view('auth/themes/misty/login',$data);
			$this->template->publish();
		}
	}
	
	/**
	 * Logout of the frontend
	 * Destroy session. Will destroy the backend session also
	 *
	 */
	function logout()
	{
		$this->auth_lib->delete_autologin();
		$this->session->set_userdata(array('user_id' => '', 'username' => '', 'logged_in' => FALSE));
		$this->session->sess_destroy();
		redirect('site/auth/login');
	}
	
	/**
	 * Register a new frontend user
	 * This will NOT create a backend/administrator user
	 *
	 */
	function register()
	{
		$data = array('u_level_id'=>$this->auth_model->get_level_id('Frontend User'),'username'=>$this->input->post('username'),
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
			elseif(!$this->auth_model->is_email_available($this->input->post('email')))
				$data['email_error'] = $this->lang->line('auth_email_in_use');
			elseif($this->input->post('bot_honey') != '') // If a bot is trying to register
				$data['error'] = $this->lang->line('auth_bot_detected');
			else {
				if($new_user= $this->auth_model->register_user($data,FALSE)) {
					unset($data['password']);	// Destroy the password just in case
					unset($data['email']);
					if($this->auth_model->get_auth_setting_value('admin_activate')) {
						$new_user['subject'] = 'Account Registration';
						$this->auth_lib->_send_email('admin_activate',$this->auth_model->get_auth_setting_value('webmaster_email'),$new_user);
						$data['message'] = $this->lang->line('auth_message_registration_completed_3');
					} else {
						$new_user['subject'] = 'Account Registration';
						$this->auth_lib->_send_email('self_activate',$new_user['email'],$new_user);
						$data['message'] = $this->lang->line('auth_message_registration_completed_1_frontend');
					}
				}
			}
		}
		$this->template->set_template('auth/themes/misty/auth');
		$this->template->content->view('auth/themes/misty/signup',$data);
		$this->template->publish();
	}
	
	/**
	 * Check if a group is allowed to log in to the system 
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
	 * 
	 * @param object login_info
	 * @return bool
	 * 
	 */
	function check_level_login($login_info)
	{
		if($this->auth_model->get_auth_setting_value('user_levels_active')) {
			if($this->auth_model->get_level_status($login_info->u_level_id)==1)
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
			'logout' => 'site/auth/logout'
		);
		$this->session->set_userdata($uman_session);
		$this->auth_model->clear_login_attempts($login_info->username);
		$this->auth_model->update_login_info($login_info->user_id,TRUE,TRUE);
		redirect('site');
	}
}