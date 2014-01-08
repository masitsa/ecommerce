<?php
class Admin extends MX_Controller
{
	public $theme;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper(array('form','html','text'));
		$this->load->library(array('form_validation','email','route_lib','auth_lib'));
		$this->load->model('admin_model');
		$this->load->model('auth/auth_model');
		if(!$this->session->userdata('logged_in'))
			redirect('auth/logout');
		if($this->session->userdata('user_level') != 1 && $this->session->userdata('user_level') != 2)
			redirect('auth/logout');
		$this->theme = 'macadmin';
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}

	/**
	* Load the default view for the module
	*  
	*/
	function index()
	{
		if($this->uri->segment(2) == '')
			redirect('admin/index');
			
		// Set the template to use for this page
		$this->template->set_template("themes/".$this->theme."/templates/admin_dashboard_template");
		
		if($this->admin_model->is_admin($this->session->userdata('user_id'))) {
			$this->load->library('table');
			$tmpl = array (
			  'table_open'          => '<table class="table table-striped table-hover">',
			  'heading_row_start'   => '<tr>',
			  'heading_row_end'     => '</tr>',
			  'heading_cell_start'  => '<th scope="col">',
			  'heading_cell_end'    => '</th>',
			  'row_start'           => '<tr>',
			  'row_end'             => '</tr>',
			  'cell_start'          => '<td>',
			  'cell_end'            => '</td>',
			  'table_close'         => '</table>'
			);
			$this->table->set_template($tmpl);
			$this->template->content->view('dashboard');
			$this->template->publish();
		} else {
			$this->template->content->view('dashboard');
			$this->template->publish();
		}
	}

	/**
	* Check if a user is the demo user
	*
	* @param int user_id
	* @return bool
	* 
	*/
	function is_demo_user($user_id)
	{
		if($this->admin_model->get_username($user_id)=='demo')
			return TRUE;
		else	
			return FALSE;
	}
	
	/**
	* Check if a user is an administrator of the system
	* 
	* @param int user_id
	* 
	*/
	function is_admin($user_id)
	{
		if($this->admin_model->is_admin($user_id))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	* Edit a user level
	*  
	* @param int level_id
	* 
	*/
	function edit_u_level($level_id)
	{
		$data['level'] = $this->admin_model->select_edit_level($level_id);
		$this->form_validation->set_rules('user_level','User Level','trim|required|xss_clean');
		$this->form_validation->set_rules('parent[]','Parent','trim|xss_clean');
		if($this->form_validation->run()) {
			$this->admin_model->update_u_level($level_id);
			redirect('admin/user_levels');
		} else {
			$this->template->content->view('edit_u_level',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit a user group
	*
	* @param int group_id
	* 
	*/
	function edit_u_group($group_id)
	{
		$data['group'] = $this->admin_model->select_edit_group($group_id);
		$this->form_validation->set_rules('user_group','User Group','trim|required|xss_clean');
		$this->form_validation->set_rules('parent[]','Parent','trim|xss_clean');
		if($this->form_validation->run()) {
			$this->admin_model->update_u_group($group_id);
			redirect('admin/user_groups');
		} else {
			$this->template->content->view('edit_u_group',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit a setting 
	* 
	* @param int settings_id
	* 
	*/
	function edit_settings($settings_id)
	{
		$data['setting'] = $this->admin_model->select_edit_settings($settings_id);
		$this->load->view('edit_settings',$data);
	}
	
	/**
	* Edit a setting that is a dropdown i.e. has predetermined options 
	* 
	* @param undefined $settings_id
	* 
	*/
	function edit_settings_dropdown($settings_id)
	{
		$settings[] = $this->admin_model->select_edit_settings($settings_id);
		$data['settings'] = $settings[0];
		$this->load->view('edit_settings_dropdown',$data);
	}
	
	/**
	* Open the profile of the user currently logged in
	* 
	* @param int edit
	* 
	*/
	function my_profile($edit = 0)
	{
		$user_id = $this->session->userdata('user_id');
		$data['user'] = $this->admin_model->select_edit_user($user_id);
		if($edit == 1)
			$data['message'] = 'Profile Updated';
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('new_password', 'Password', 'trim|xss_clean|alpha_dash|matches[confirm_new_password]');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm Password','trim|xss_clean|alpha_dash|matches[new_password]');
		if ($this->form_validation->run()) {
			if($this->is_admin($user_id) || $this->is_demo_user($user_id))
				$this->route_lib->redirect_with_error('admin/show_profile','Sorry, you cannot edit this user.');
			else
				$this->admin_model->update_my_profile($user_id);
		} else {
			$this->template->content->view('my_profile',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Edit a user's info
	* 
	* @param int user_id
	* 
	*/
	function edit($user_id)
	{
		if($this->is_admin($user_id) || $this->is_demo_user($user_id)) {
			$this->route_lib->redirect_with_error('admin/show_profile','Sorry, you cannot edit this user.');
		} else {
			
			if($data['user'] = $this->admin_model->select_edit_user($user_id)) {
				$this->form_validation->set_message('is_natural_no_zero','This field is required');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
				$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|xss_clean');
				$this->form_validation->set_rules('u_level_id', 'User Level', 'trim|required|xss_clean|is_natural_no_zero');
				$this->form_validation->set_rules('group[]', 'User Group', 'trim|required|xss_clean');
				$this->form_validation->set_rules('new_password', 'Password', 'trim|xss_clean|alpha_dash|matches[confirm_new_password]');
				$this->form_validation->set_rules('confirm_new_password', 'Confirm Password', 'trim|xss_clean|alpha_dash|matches[new_password]');
				if ($this->form_validation->run()) {
					$this->admin_model->update_user_profile($user_id);
					redirect('admin/show_profile');
				} else {
					$this->template->content->view('edit_profile_form',$data);
					$this->template->publish();
				}
			} else {
				$this->auth_lib->redirect_with_error('admin/show_profile','admin_user_cannot_be_found');
			}
		}
	}
	
	/**
	* Make a user an administrator
	* 
	* @param int user_id
	* 
	*/
	function make_admin($user_id)
	{
		if($this->is_admin($user_id) || $this->is_demo_user($user_id)) {
			$this->route_lib->redirect_with_error('admin/show_profile','Sorry, you cannot edit this user.');
		} else {
			$this->admin_model->make_admin($user_id);
			redirect('admin/show_profile');
		}
	}
	
	/**
	* Remove a user's administrator status
	*  
	* @param int user_id
	* 
	*/
	function remove_admin($user_id)
	{
		if($this->is_admin($user_id) || $this->is_demo_user($user_id)) {
			$this->route_lib->redirect_with_error('admin/show_profile','Sorry, you cannot edit this user.');
		} else {
			$this->admin_model->remove_admin($user_id);
			redirect('admin/show_profile');
		}
	}
	
	/**
	* Delete a user's account
	*
	* @param int user_id
	* 
	*/
	function delete($user_id)
	{
		if($this->is_admin($user_id) || $this->is_demo_user($user_id)){
			$this->route_lib->redirect_with_error('admin/show_profile','Sorry, you cannot edit this user.');
		} else {
			$this->admin_model->delete_user($user_id);
			redirect('admin/show_profile');
		}
	}
	
	/**
	* Delete a user level
	* 
	* @param int level_id
	* 
	*/
	function delete_level($level_id)
	{
		if($this->admin_model->delete_level($level_id))
			redirect('admin/user_levels');
		else
			redirect('admin/user_levels/e');
	}
	
	/**
	* Delete a user group 
	* 
	* @param int group_id
	* 
	*/
	function delete_group($group_id)
	{
		if($this->admin_model->delete_group($group_id))
			redirect('admin/user_groups');
		else
			redirect('admin/user_groups/e');
	}
	
	/**
	* Activate a user level 
	* 
	* @param int u_level_id
	* 
	*/
	function activate_level($u_level_id)
	{
		$this->admin_model->status_level($u_level_id,1);
		redirect('admin/user_levels');
	}

	/**
	* Deactivate a user level 
	* 
	* @param int u_level_id
	* 
	*/
	function deactivate_level($u_level_id)
	{
		$this->admin_model->status_level($u_level_id,0);
		redirect('admin/user_levels');
	}
	
	/**
	* Activate a user group
	*
	* @param int u_group_id
	*
	*/
	function activate_group($u_group_id)
	{
		$this->admin_model->status_group($u_group_id,1);
		redirect('admin/user_groups');
	}

	/**
	* Deactivate a user group
	*
	* @param int u_group_id
	*
	*/
	function deactivate_group($u_group_id)
	{
		$this->admin_model->status_group($u_group_id,0);
		redirect('admin/user_groups');
	}
	
	/**
	* Activate a user's account
	* 
	* @param int user_id
	* @param int page
	* 
	*/
	function activate($user_id,$page)
	{
		if($this->is_admin($user_id) || $this->is_demo_user($user_id)) {
			$this->route_lib->redirect_with_error('admin/show_profile','Sorry, you cannot edit this user.');
		} else {
			$this->admin_model->status($user_id,1);
			redirect('admin/show_profile/'.$page);
		}
	}
	
	/**
	* Deactivate a user's account
	* 
	* @param int user_id
	* @param int page
	* 
	*/
	function deactivate($user_id,$page)
	{
		if($this->is_admin($user_id) || $this->is_demo_user($user_id)){
			$this->route_lib->redirect_with_error('admin/show_profile','Sorry, you cannot edit this user.');
		} else {
			$this->admin_model->status($user_id,0);
			redirect('admin/show_profile/'.$page);
		}
	}

	/**
	* Show all users
	* 
	* @param string message
	* 
	*/
	function show_profile($message='')
	{		
		$data['message'] = $message;
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/show_profile';
		$config['total_rows'] = $this->db->count_all('users');
		$config['per_page'] = '10';
		$config['num_links'] = '10';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
		$this->load->library('table');
		$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr>',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th scope="col">',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'table_close'         => '</table>'
		);
		$options = array('0'=>'Bulk Actions','1'=>'Enable','2'=>'Disable');
		if($this->uri->segment(4))
			$pageno = $this->uri->segment(4);
		else
			$pageno = 0;
		$username = anchor("admin/sort_tbl/username/asc","Username");
		$email = anchor("admin/sort_tbl/email/asc","Email");
		$fullname = anchor("admin/sort_tbl/fullname/asc","Full Name");
		$this->table->set_heading('',$username, $email,$fullname, '','','','','');
		$users = $this->admin_model->get_display_list($config['per_page'],$pageno);
		$this->table->set_template($tmpl);      
			
		//-- Header Row
		foreach($users as $user )
		{
			$checkcell = array('name'=>'user_profile[]','value'=>$user->user_id,'style'=>'width:15px');
			if($this->is_admin($user->user_id) || $this->is_demo_user($user->user_id)) {
				$this->table->add_row(form_checkbox($checkcell),$user->username,$user->email,
				$user->fullname,
				anchor('admin/info/'.$user->user_id,'<i class="icon-zoom-in butn butn-info"></i>', array('rel'=>'facebox','title'=>'More information')),'','','');					
			} else {
				if($user->activated)
					$status = anchor("admin/deactivate/$user->user_id/$pageno",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate'));
				else
					$status = anchor("admin/activate/$user->user_id/$pageno",'<i class="icon-unlock butn butn-info"></i>',array('title'=>'Activate'));
				if(!$this->is_admin($user->user_id))
					$admin_status = anchor("admin/make_admin/$user->user_id/$pageno",'<i class="icon-plus butn butn-info"></i>', array('title'=>'Make Admin'));
				else
					$admin_status = anchor("admin/remove_admin/$user->user_id/$pageno",'<i class="icon-minus butn butn-info"></i>', array('title'=>'Remove Admin'));
				$this->table->add_row(
					form_checkbox($checkcell),$user->username,$user->email,
					$user->fullname,
					anchor('admin/info/'.$user->user_id,'<i class="icon-zoom-in butn butn-info"></i>', array('rel'=>'facebox','title'=>'More information')),
					anchor("admin/edit/$user->user_id",'<i class="icon-edit butn butn-success"></i>', array('title'=>'Edit')),
					anchor("admin/delete/$user->user_id/".$pageno,'<i class="icon-trash butn butn-danger"></i>', array('onClick'=>'return confirm(\'Do you really want to delete the user?\');', 'title'=>'Delete')),
					$status,
					$admin_status
				);
			}
		}
		$submit = array('name' => 'submit', 'value' => 'Update');
		$cell = array('data' => form_dropdown('bulk_action', $options, '0').'<div>'.form_submit($submit).'</div>', 'colspan' => 2);
		$this->table->add_row($cell);
		$this->template->content->view('profile',$data);
		$this->template->publish();
	}
	
	/**
	* View all ungrouped users
	* 
	*/
	function ungrouped_users()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/ungrouped_users/';
		$config['total_rows'] = $this->admin_model->ungrouped_list_num();
		$config['per_page'] = '10';
		$config['num_links'] = '10';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
		$this->load->library('table');
		$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr>',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th scope="col">',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);      
		
		$this->table->set_heading('','Username', 'Email','Fullname', '','','','');
		$users = $this->admin_model->get_ungrouped_list($config['per_page'],$this->uri->segment(3));
		if(!empty($users)) {
			$dbdata = $this->admin_model->get_user_groups();
			$data[0] = "Group User";
			foreach ($dbdata as $row)
			{
				$data[$row->u_group_id] = $row->user_group;
			}
				$options = array('0'=>'Bulk Actions','1'=>'Enable','2'=>'Disable');
				
			foreach($users as $user )
			{
				$checkcell = array('name'=>'user_profile[]','value'=>$user->user_id,'style'=>'width:15px');
				$this->table->add_row(
					form_checkbox($checkcell),$user->username,$user->email,
					$user->fullname,
					anchor('admin/info/'.$user->user_id,'<i class="icon-zoom-in butn butn-info"></i>',array('rel' => 'facebox', 'title' => 'More information')),
					anchor("admin/edit/$user->user_id",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')),
					anchor("admin/delete/$user->user_id/".$this->uri->segment(3),'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete the user?\');','title'=>'Delete')),
					anchor("admin/".$this->get_status($user->activated)."/$user->user_id/".$this->uri->segment(3),img(array('src'=>base_url().'/img/icons/16/'.$this->get_status($user->activated).'.png')),array('title'=>$this->get_status($user->activated)))
				);
			}
			$js = 'style="width:150px"';
			$submit = array('name' => 'submit', 'value' => 'Update');
			$cell = array('data' => form_dropdown('bulk_action', $data, '0',$js).'<div class="submit-box" style="float:right">'.form_submit($submit).'</div>', 'colspan' => 4);
			$this->table->add_row($cell);
		} else {
			$this->table->clear();
			$this->table->add_row('All users have been grouped');
		}
		$this->template->content->view('group');
		$this->template->publish();
	}
	
	/**
	* View all deactivated users
	* 
	*/
	function deactivated_users()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'/admin/deactivated_users/';
		$config['total_rows'] = $this->admin_model->deactivated_list_num();
		$config['per_page'] = '10';
		$config['num_links'] = '10';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
		$this->load->library('table');
		$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr>',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th scope="col">',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);      
		
		$this->table->set_heading('','Username', 'Email','Fullname', '','','','');
		$users = $this->admin_model->get_deactivated_list($config['per_page'],$this->uri->segment(3));
			$options = array('0'=>'Bulk Actions','1'=>'Enable','2'=>'Disable');
		foreach($users as $user )
		{
			$checkcell = array('name'=>'user_profile[]','value'=>$user->user_id,'style'=>'width:15px');
			$this->table->add_row(
				form_checkbox($checkcell),$user->username,$user->email,
				$user->fullname,
				anchor('/admin/info/'.$user->user_id,'<i class="icon-zoom-in butn butn-info"></i>', array('rel'=>'facebox','title'=>'More information')),
				anchor("/admin/edit/$user->user_id",'<i class="icon-edit butn butn-success"></i>', array('title'=>'Edit')),
				anchor("/admin/delete/$user->user_id/".$this->uri->segment(3),'<i class="icon-trash butn butn-danger"></i>', array('onClick' =>  'return confirm(\'Do you really want to delete the user?\');', 'title'=>'Delete')),
				anchor("/admin/".$this->get_status($user->activated)."/$user->user_id/".$this->uri->segment(3),img(array('src'=>base_url().'/img/icons/16/'.$this->get_status($user->activated).'.png')), array('title' => $this->get_status($user->activated)))
			);
		}
		$js = 'style="width:150px"';
		$submit = array('name' => 'submit', 'value' => 'Update');
		$cell = array('data' => form_dropdown('bulk_action', $options, '0',$js).'<div class="submit-box" style="float:right">'.form_submit($submit).'</div>', 'colspan' => 4);
		$this->table->add_row($cell);
		$this->template->content->view('profile');
		$this->template->publish();
	}
	
	/**
	* View user info popup
	* 
	* @param int user
	* 
	*/
	function info($user)
	{
		$data['profile'] = $this->admin_model->get_fullname($user);
		$data['user_id'] = $user;
		$data['users'] = $this->admin_model->get_user_by_id($user,1);
		$this->load->view('info',$data);
	}
	
	/**
	* Save bulk user level changes
	*  
	*/
	function save_level_bulk()
	{
		if($this->input->post('bulk_action')==1) {
			$level= $this->input->post('user_levels');
			while (list ($key,$val) = @each ($level)) {
				$this->admin_model->status_level($val,1);
				sleep(0.1);
			} 
		}
		if($this->input->post('bulk_action')==2) {
			$level= $this->input->post('user_levels');
			while (list ($key,$val) = @each ($level)) {
				$this->admin_model->status_level($val,0);
				sleep(0.1);
			} 
		}
		$this->user_levels();
	}
	
	/**
	* Save bulk user group changes
	*/
	function save_group_bulk()
	{
		if($this->input->post('bulk_action')==1) {
			$group= $this->input->post('user_groups');
			while (list ($key,$val) = @each ($group)) {
				$this->admin_model->status_group($val,1);
				sleep(0.1);
			} 
		}
		if($this->input->post('bulk_action')==2) {
			$group= $this->input->post('user_groups');
			while (list ($key,$val) = @each ($group)) {
				$this->admin_model->status_group($val,0);
				sleep(0.1);
			} 
		}
		$this->user_groups();
	}

	/**
	* View all user levels on the system
	*
	*/
	function user_levels()
	{
		if(!$this->auth_model->get_auth_setting_value('user_levels_active')){
			$data['deactivated'] = "User Levels have been disabled. To enable Kindly go to the settings page to enable it.";
			$this->template->content->view('user_levels');
			$this->template->publish();
		}else{
			$this->load->library('pagination');
			$config['base_url'] = base_url().'/admin/user_levels/';
			$config['total_rows'] = count($this->admin_model->get_user_level_list('',''));
			$config['per_page'] = '10';
			$config['num_links'] = '10';
			$config['full_tag_open'] = '<p>';
			$config['full_tag_close'] = '</p>';
			$this->pagination->initialize($config);
		
			$this->load->library('table');
			$tmpl = array (
				'table_open'          => '<table class="table table-striped table-hover">',
				'heading_row_start'   => '<tr>',
				'heading_row_end'     => '</tr>',
				'heading_cell_start'  => '<th scope="col">',
				'heading_cell_end'    => '</th>',
				'row_start'           => '<tr>',
				'row_end'             => '</tr>',
				'cell_start'          => '<td>',
				'cell_end'            => '</td>',
				'table_close'         => '</table>'
			);
			$this->table->set_template($tmpl);
			$axns = array('data'=>'Actions','colspan'=>4);     
			
			//-- Header Row
			$this->table->set_heading('','User Level',$axns);
			$user_levels = $this->admin_model->get_user_level_list($config['per_page'],$this->uri->segment(3));
			$options = array('0'=>'Bulk Actions','1'=>'Enable','2'=>'Disable','3'=>'Delete');
			foreach($user_levels as $user_level){
				$checkcell = array('name'=>'user_levels[]','value'=>$user_level->u_level_id,'style'=>'width:15px');
				if($user_level->activate)
					$status = anchor("admin/deactivate_level/$user_level->u_level_id",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate'));
				else
					$status = anchor("admin/activate_level/$user_level->u_level_id",'<i class="icon-unlock butn butn-info"></i>',array('title'=>'Activate'));
				$this->table->add_row(
					form_checkbox($checkcell),$user_level->user_level,
					anchor("admin/level_details/$user_level->u_level_id",'<i class="icon-zoom-in butn butn-info"></i>',array('rel'=>'facebox','title'=>'More information')),
					anchor("admin/edit_u_level/$user_level->u_level_id",'<i class="icon-edit butn butn-success"></i>', array('title'=>'Edit')),
					anchor("admin/delete_level/$user_level->u_level_id",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete the user level?\');','title'=>'Delete')),
					$status
				);
			}
			$js = 'style="width:150px"';
			$submit = array('name'=>'submit','value'=>'Update','class'=>'btn');
			$cell = array('data'=>form_dropdown('bulk_action',$options,'0',$js).'<div class="submit-box">'.form_submit($submit).'</div>', 'colspan' => 6);
			$this->table->add_row($cell);
			$this->template->content->view('user_levels');
			$this->template->publish();
		}
	}
	
	/**
	* Show user level details
	* 
	* @param int level_id
	* 
	*/
	function level_details($level_id)
	{
		$data['details'] = $this->admin_model->get_level_details($level_id);
		$this->load->view('user_level_details',$data);
	}
	
	/**
	* View all user groups on the system
	* 
	*/
	function user_groups()
	{
		if(!$this->auth_model->get_auth_setting_value('user_groups_active')) {
			$data['deactivated'] = "User Groups have been disabled. To enable Kindly go to the settings page to enable it.";
			$this->template->content->view('user_groups',$data);
			$this->template->publish();
		} else {
			$this->load->library('pagination');
			$config['base_url'] = base_url().'/admin/user_groups/';
			$config['total_rows'] = count($this->admin_model->get_user_group_list('',''));
			$config['per_page'] = '10';
			$config['num_links'] = '10';
			$config['full_tag_open'] = '<p>';
			$config['full_tag_close'] = '</p>';
			$this->pagination->initialize($config);
		
			$this->load->library('table');
			$tmpl = array(
			  'table_open'          => '<table class="table table-striped table-hover">',
			  'heading_row_start'   => '<tr>',
			  'heading_row_end'     => '</tr>',
			  'heading_cell_start'  => '<th scope="col">',
			  'heading_cell_end'    => '</th>',
			  'row_start'           => '<tr>',
			  'row_end'             => '</tr>',
			  'cell_start'          => '<td>',
			  'cell_end'            => '</td>',
			  'table_close'         => '</table>'
			);
			$this->table->set_template($tmpl);
			$axns = array('data'=>'Actions','colspan'=>4);    
			
			//-- Header Row
			$this->table->set_heading('','User Group',$axns);
			$user_groups = $this->admin_model->get_user_group_list($config['per_page'],$this->uri->segment(3));
			$options = array('0'=>'Bulk Actions','1'=>'Enable','2'=>'Disable');
			foreach($user_groups as $user_group){
				$checkcell = array('name'=>'user_groups[]','value'=>$user_group->u_group_id,'style'=>'width:15px');
				if($user_group->activate)
					$status = anchor("admin/deactivate_group/$user_group->u_group_id",'<i class="icon-lock butn butn-info"></i>',array('title'=>'Deactivate'));
				else
					$status = anchor("admin/activate_group/$user_group->u_group_id",'<i class="icon-unlock butn butn-info"></i>',array('title'=>'Activate'));
				$this->table->add_row(
					form_checkbox($checkcell),$user_group->user_group,
					anchor("admin/group_details/$user_group->u_group_id",'<i class="icon-zoom-in butn butn-info"></i>',array('rel'=>'facebox','title'=>'More information')),
					anchor("admin/edit_u_group/$user_group->u_group_id",'<i class="icon-edit butn butn-success"></i>', array('title'=>'Edit')),
					anchor("admin/delete_group/$user_group->u_group_id",'<i class="icon-trash butn butn-danger"></i>', array('onClick'=>'return confirm(\'Do you really want to delete the user group?\');','title'=>'Delete')),
					$status
				);
			}
			$js = 'style="width:150px"';
			$submit = array('name'=>'submit','value'=>'Update','class'=>'btn');
			$cell = array('data' => form_dropdown('bulk_action', $options, '0',$js).'<div class="submit-box">'.form_submit($submit).'</div>', 'colspan' => 6);
			$this->table->add_row($cell);
			$this->template->content->view('user_groups');
			$this->template->publish();
		}
	}
	
	/**
	* View the details of a user group 
	* 
	* @param int group_id
	* 
	*/
	function group_details($group_id)
	{
		$data['details'] = $this->admin_model->get_group_details($group_id);
		$this->load->view('user_group_details',$data);
	}
	
	function get_status($val)
	{
		if($val==0)
			return "activate";
		return "deactivate";
	}

	/**
	* Add a new user to the system
	* Done from the backend by the administrator
	*  
	*/
	function add()
	{
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('u_level_id', 'User Level', 'trim|required|xss_clean|is_natural_no_zero');
		$this->form_validation->set_rules('u_group_id', 'User Group', 'trim|required|xss_clean|is_natural_no_zero');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|alpha_dash');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
		if ($this->form_validation->run()) {
			$error = FALSE;
			if(!$this->auth_model->is_username_available($this->input->post('username'))) {
				$error = TRUE;
				$data['username_error'] = $this->lang->line('admin_username_in_use');
			}
			if(!$this->auth_model->is_email_available($this->input->post('email'))) {
				$error = TRUE;
				$data['email_error'] = $this->lang->line('admin_email_in_use');
			}
			if(!$error) {
				$this->admin_model->create_user();
				redirect('admin/show_profile');
			} else {
				$this->template->content->view('add_profile_form',$data);
				$this->template->publish();
			}
		} else {
			$this->template->content->view('add_profile_form');
			$this->template->publish();
		}
	}
	
	/**
	* Update a user level
	*  
	*/
	function update_u_level()
	{
		$data = array( 'user_level' => $this->input->post('u_level'));
		$this->form_validation->set_rules('u_level', 'User Level', 'trim|required|xss_clean');
		if ($this->form_validation->run())
			$this->admin_model->update_u_level($data);
			redirect('admin/user_levels');
	}
	
	/**
	* Update a user group 
	* 
	*/
	function update_u_group()
	{
		$data = array( 'user_group' => $this->input->post('u_group'));
		$this->form_validation->set_rules('u_group', 'User Group', 'trim|required|xss_clean');
		if ($this->form_validation->run())
			$this->admin_model->update_u_group($data);
			redirect('admin/user_groups');
	}
	
	/**
	* Update a setting
	*
	* @param int setting_id
	* 
	*/
	function update_setting($setting_id)
	{
		$this->form_validation->set_rules('setting_value','Setting','trim|required|xss_clean');
		if ($this->form_validation->run()) {	
			$this->admin_model->update_setting($setting_id);
			redirect('admin/settings');
		}
	}
	
	/**
	* Add a user level to the system
	*
	*/
	function add_u_level()
	{
		$this->form_validation->set_rules('user_level', 'User Level', 'trim|required|xss_clean');
		$this->form_validation->set_rules('parent[]','Parent','trim|required|xss_clean');
		if ($this->form_validation->run()) {
			$this->admin_model->create_u_level();
			redirect('admin/user_levels');
		} else {
			$this->template->content->view('add_user_level_form');
			$this->template->publish();
		}
	}
	
	/**
	* Add a user group to the system 
	*
	*/
	function add_u_group()
	{
		$this->form_validation->set_rules('user_group', 'User group', 'trim|required|xss_clean');
		$this->form_validation->set_rules('parent[]','Parent','trim|required|xss_clean');
		if($this->form_validation->run()) {
			$this->admin_model->create_u_group();
			redirect('admin/user_groups');
		} else {
			$this->template->content->view('add_user_group_form');
			$this->template->publish();
		}
	}
	
	/**
	* Search the DB for a particular user
	* 
	*/
	function search_user()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/search_user';
		$config['total_rows'] = count($this->admin_model->search_user('','',$this->input->post('searcht')));
		$config['per_page'] = '10';
		$config['num_links'] = '10';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		//INITIALIZING TABLES
		$this->load->library('table');
			$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr>',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th scope="col">',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);      
		
		//-- Header Row
		$users = $this->admin_model->search_user($config['per_page'],$this->uri->segment(3),$this->input->post('searcht'));
		if(!$users) {
			$this->table->clear();
			$this->table->set_heading(' ');
			$this->table->add_row("There are no search results :-/");
			$this->template->content->view('profile');
			$this->template->publish();
		} else {
			$username = anchor("admin/sort_tbl/username/asc","Username");
			$email = anchor("admin/sort_tbl/email/asc","Email");
			$fullname = anchor("admin/sort_tbl/fullname/asc","Full Name");
			$pageno ='';
			$this->table->set_heading('',$username, $email,$fullname, '','','','','');
			if($this->uri->segment(2)=="show_profile")
				$pageno = $this->uri->segment(3);
				$options = array('0'=>'Bulk Actions','1'=>'Enable','2'=>'Disable');
			foreach($users as $user){
				$checkcell = array('name'=>'user_profile[]','value'=>$user->user_id,'style'=>'width:15px');
				if($this->admin_model->is_admin($user->user_id)) {
					$this->table->add_row(form_checkbox($checkcell),$user->username,$user->email,
					$user->fullname,
					anchor('admin/info/'.$user->user_id,'<i class="icon-zoom-in butn butn-info"></i>',array('rel'=>'facebox','title'=>'More information')),'','','','');
				} else {
					if(!$this->admin_model->is_admin($user->user_id))
						$admin_status = anchor('/admin/make_admin/'.$user->user_id,img(array('src'=>base_url().'img/icons/16/administrator.png')), array('title' => 'Make admin'));
					else
						$admin_status = anchor('/admin/remove_admin/'.$user->user_id,img(array('src'=>base_url().'img/icons/16/remove-admin.png')), array('title' => 'Remove admin'));
						
					$this->table->add_row(
						form_checkbox($checkcell),$user->username,$user->email,
						$user->fullname,
						anchor("admin/info/$user->user_id",'<i class="icon-zoom-in butn butn-info"></i>',array('rel'=>'facebox','title'=>'More information')),
						anchor("admin/edit/$user->user_id",'<i class="icon-edit butn butn-success"></i>', array('title'=>'Edit')),
						anchor("admin/delete/$user->user_id/".$this->uri->segment(3),'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete the user?\');', 'title'=>'Delete')),
						anchor("admin/".$this->get_status($user->activated)."/$user->user_id/".$this->uri->segment(3),img(array('src'=>base_url().'/img/icons/16/'.$this->get_status($user->activated).'.png')), array('title' => $this->get_status($user->activated))),
						$admin_status
					);
				}
			}
			$js = 'style="width:150px"';
			$submit = array('name' => 'submit', 'value' => 'Update');
			$cell = array('data' => form_dropdown('bulk_action', $options, '0',$js).'<div class="submit-box" style="float:right">'.form_submit($submit).'</div>', 'colspan' => 4);
			$this->table->add_row($cell);
			$this->template->content->view('profile');
			$this->template->publish();
		}
	}
	
	/**
	* Sort the table of users in any direction using a particular column 
	* 
	* @param int column
	* @param int direction
	* 
	*/
	function sort_tbl($column ,$direction )
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/show_profile';
		$config['total_rows'] = count($this->admin_model->sort_tbl('','',$column ,$direction));
		$config['per_page'] = '10';
		$config['num_links'] = '10';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$this->pagination->initialize($config);
		
		$this->load->library('table');
		$class=$this->uri->segment(4);
		$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr>',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th scope="col">',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);
		
		$username = anchor("/admin/sort_tbl/username/asc","Username");
		$email = anchor("/admin/sort_tbl/email/asc","Email");
		$fullname = anchor("/admin/sort_tbl/fullname/asc","Full Name");
 
		if($this->uri->segment(3)=="username" && strlen($class)>0) {
			if($class==="asc")
				$username = anchor("/admin/sort_tbl/username/desc","Username").img(array('src'=>base_url().'/img/style1/small_asc.gif'));
			else
				$username = anchor("/admin/sort_tbl/username/asc","Username").img(array('src'=>base_url().'/img/style1/small_desc.gif'));
		}
		if($this->uri->segment(3)=="user_level" && strlen($class)>0) {
			if($class==="asc")
				$email = anchor("admin/sort_tbl/email/desc","Email").img(array('src'=>base_url().'/img/style1/small_asc.gif'));
			else
				$email = anchor("admin/sort_tbl/email/asc","Email").img(array('src'=>base_url().'/img/style1/small_desc.gif'));
		}
		if($this->uri->segment(3)=="fullname" && strlen($class)>0) {
			if($class==="asc")
				$fullname = anchor("admin/sort_tbl/fullname/desc","Full Name").img(array('src'=>base_url().'/img/style1/small_asc.gif'));
			else
				$fullname = anchor("admin/sort_tbl/fullname/asc","Full Name").img(array('src'=>base_url().'/img/style1/small_desc.gif'));
		}
		$this->table->set_heading('',$username, $email,$fullname, '','','','');
		$users = $this->admin_model->sort_tbl($config['per_page'],$this->uri->segment(3),$column ,$direction);
		$options = array('0'=>'Bulk Actions','1'=>'Enable','2'=>'Disable','3'=>'Delete');
		foreach($users as $user ) {
			$checkcell = array('name'=>'user_profile[]','value'=>$user->user_id,'style'=>'width:15px');
			$this->table->add_row(
				form_checkbox($checkcell),$user->username,$user->email,
				$user->fullname,
				anchor('admin/info/'.$user->user_id,'<i class="icon-zoom-in butn butn-info"></i>',array('rel'=>'facebox','title'=>'More information')),
				anchor("admin/edit/$user->user_id",'<i class="icon-edit butn butn-success"></i>', array('title'=>'Edit')),
				anchor("admin/delete/$user->user_id",'<i class="icon-trash butn butn-danger"></i>',array('onClick'=>'return confirm(\'Do you really want to delete the user?\');','title'=>'Delete')),
				anchor("admin/".$this->get_status($user->activated)."/$user->user_id",img(array('src'=>base_url().'/img/icons/16/'.$this->get_status($user->activated).'.png')), array('title' => $this->get_status($user->activated)))
			);
		}
		$js = 'style="width:150px"';
		$submit = array('name' => 'submit', 'value' => 'Update');
		$cell = array('data' => form_dropdown('bulk_action', $options, '0',$js).'<div class="submit-box" style="float:right">'.form_submit($submit).'</div>', 'colspan' => 4);
		$this->table->add_row($cell);
		$this->template->content->view('profile');
		$this->template->publish();
	}
	
	/**
	* View system settings
	* 
	*/
	function settings()
	{
		$this->load->library('table');
		$tmpl = array (
		  'table_open'          => '<table class="table table-striped table-hover">',
		  'heading_row_start'   => '<tr>',
		  'heading_row_end'     => '</tr>',
		  'heading_cell_start'  => '<th scope="col">',
		  'heading_cell_end'    => '</th>',
		  'row_start'           => '<tr>',
		  'row_end'             => '</tr>',
		  'cell_start'          => '<td>',
		  'cell_end'            => '</td>',
		  'table_close'         => '</table>'
		);
		$this->table->set_template($tmpl);      

		$this->table->set_heading('Setting','Setting value' , 'Description','');
		$settings = $this->admin_model->get_settings_display_list();
		
		foreach($settings as $setting )
		{
			if($setting->type_id == 1 OR $setting->type_id == 3) {
				$this->table->add_row(
					$setting->setting_name,$setting->setting_value."  ".
					anchor('admin/edit_settings/'.$setting->setting_id,'<i class="icon-edit butn butn-success"></i>', array('rel'=>'facebox','title'=>'Edit Setting')),
					$setting->setting_description
				);
			} else {
				if($setting->setting_value == 0)
					$edit = anchor("admin/enable_setting/$setting->setting_id",img(array('src'=>base_url().'/img/icons/16/'.$this->get_settings_status($setting->setting_value).'.png')), array('title'=>'Enable'));
				else
					$edit = anchor("admin/disable_setting/$setting->setting_id",img(array('src'=>base_url().'/img/icons/16/'.$this->get_settings_status($setting->setting_value).'.png')),array('title'=>'Disable'));
				$this->table->add_row(
					$setting->setting_name,$edit,$setting->setting_description
				);
			}
		}
		$this->template->content->view('settings');
		$this->template->publish();
	}
	
	/**
	* Get the status of settings 
	* 
	* @param int val
	* @return string
	* 
	*/
	function get_settings_status($val)
	{
		if($val==0)
			return "disabled";
		return "enabled";
	}
	
	/**
	* Setting is enabled. Set it to disabled. 
	* 
	* @param int setting_id
	* 
	*/
	function disable_setting($setting_id)
	{
		$this->admin_model->update_bool_setting($setting_id,0);
		redirect('admin/settings');
	}
	
	/**
	* Setting is disabled. Set it to enabled. 
	* 
	* @param int setting_id
	* 
	*/
	function enable_setting($setting_id)
	{
		$this->admin_model->update_bool_setting($setting_id,1);
		redirect('admin/settings');
	}
	
	/**
	* Save a bulk action on a set of users
	* Allows you to edit many user profiles at the same time
	* 
	*/
	function save_bulk()
	{
		if($this->input->post('bulk_action')==1){
			$user_prof = $this->input->post('user_profile');
			while (list ($key,$val) = @each ($user_prof)) {
				$this->admin_model->status($val,1);
				sleep(0.1);
			} 
		}
		if($this->input->post('bulk_action')==2){
			$user_prof = $this->input->post('user_profile');
			while (list ($key,$val) = @each ($user_prof)) {
				$this->admin_model->status($val,0);
				sleep(0.1);
			} 
		}
		redirect('admin/show_profile/'.$this->uri->segment(3));
	}
	
	/**
	* Save a bulk action for a group of users from the dashboard
	* Taken from the dashboard
	* 
	*/
	function save_bulk_dashboard()
	{
		if($this->input->post('bulk_action')==1){
			$user_prof = $this->input->post('user_profile');
			while (list ($key,$val) = @each ($user_prof)) {
				$this->admin_model->status($val,1);
				sleep(0.1);
			} 
		}
		if($this->input->post('bulk_action')==2) {
			$user_prof = $this->input->post('user_profile');
			while (list ($key,$val) = @each ($user_prof)) {
				$this->admin_model->status($val,0);
				sleep(0.1);
			} 
		}
		$this->index();
	}
	
	/**
	* Save the bulk action taken on a set of user groups
	* Taken from the dashboard
	* 
	*/
	function group_bulk_dash()
	{
		if($this->input->post('bulk_action') > 0){
			$user_prof = $this->input->post('user_profile');
			while (list ($key,$val) = @each ($user_prof)) {
				$this->admin_model->update_group($val,$this->input->post('bulk_action'));
				sleep(0.1);
			} 
		}
		$this->index();
	}
	
	/**
	* Save the bulk action taken on a set of user groups
	* Taken from the list of user groups
	* 
	*/
	function group_bulk()
	{
		if($this->input->post('bulk_action') > 0){
			$user_prof = $this->input->post('user_profile');
			while (list ($key,$val) = @each ($user_prof)) {
				$this->admin_model->update_group($val,$this->input->post('bulk_action'));
				sleep(0.2);
			} 
		}
		$this->ungrouped_users();
	}
	
	/**
	* Take a snapshot of the database for backup
	* Tables to be backed up and other options set in the function admin_model/backup
	*  
	*/
	function backup()
	{
		$this->admin_model->backup();
		$this->index();
	}
	
	/**
	* Generate the page views statistics widget to be shown on the dashboard 
	* 
	*/
	function page_view_stats()
	{
		$views = $this->admin_model->get_page_view_stats();
		if($views)
			foreach($views as $view)
				echo('<span class="stats">'.$view->views.'</span>'.$view->page_title.'<br />');
	}
	
	/**
	* Generate the post view statistics widget to be shown on the dashboard
	* 
	*/
	function post_view_stats()
	{
		$posts = $this->admin_model->get_post_view_stats();
		if($posts)
			foreach($posts as $post)
				echo('<span class="stats">'.$post->views.'</span>'.$post->post_title.'<br />');
	}
	
	/**
	* Generate the recent logins statistics widget to be shown on the dashboard
	* 
	*/
	function recent_logins()
	{
		$logins = $this->admin_model->get_recent_logins();
		if($logins)
			foreach($logins as $login)
				echo('<span class="stats">'.$login->username.'</span>'.date("jS M Y",strtotime($login->last_login)).'<br />');
	}
}
/* End of file admin.php */
/* Location : /application/modules/admin/controllers/admin.php */