<?php
class Acl_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Get details of the admin user level
	* Assumes that the admin user_level name is "Admin"
	* Also assumes there is only one admin
	* 
	* @retun object
	* 
	*/
	function get_admin_level()
	{
		$this->db->limit(1);
		$this->db->select('*');
		$this->db->from('user_level');
		$this->db->where('user_level','Admin');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Get info on the admin of the system
	* 
	* @return object
	*
	*/
	function get_admin_details()
	{
		$admin_level = $this->get_admin_level();
		$admin_level_id = $admin_level->u_level_id;
		$this->db->limit(1);
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('u_level_id',$admin_level_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	* Add a default "allow" rule for the admin
	* Called when creating a new resource
	* 
	* @param int $resource_id
	* 
	*/
	function add_default_admin_rule($resource_id)
	{
		$admin_level = $this->get_admin_level();
		$admin_level_id = $admin_level->u_level_id;
		$this->db->select('rule_id');
		$this->db->from('acl_rules');
		$this->db->where('resource_id',$resource_id);
		$this->db->where('role_id',$admin_level_id);
		$query = $this->db->get();
		if($query->num_rows() != 0){
			$data = array('rule'=>1);
			$this->db->where('resource_id',$resource_id);
			$this->db->where('role_id',$admin_level_id);
			$this->db->update('acl_rules',$data);
		}else{
			$data = array('role_id'=>$admin_level_id,'resource_id'=>$resource_id,'rule'=>1);
			$this->db->insert('acl_rules',$data);
		}
	}
	
	// Check if a menu item has children
	function has_children($id)
	{
		$this->db->select('*');
		$this->db->from('acl_resources');
		$this->db->where('parent_id = ',$id);
		$this->db->where('parent_id <> ',0);
		$this->db->where('menu',1);
		$no_of_rec = $this->db->count_all_results();
		if($no_of_rec>0)
			return true;
		return false;	
	}
	
	// Get the ID of a menu item
	function get_menu_id($url)
	{
		$this->db->where('url', $url);
		$query = $this->db->get('acl_resources');
		if($query->num_rows() == 1)
			return $query->row()->resource_id;
		return 0;
	}
	
	//	Retireve all main menus
	function get_main_menus()
	{
		$this->db->select('url,resource_id,resource_name');
		$this->db->from('acl_resources');
		$this->db->where('parent_id','0');
		$this->db->where('menu',1);
		$this->db->where('active',1);
		$this->db->order_by('position','asc');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get the child menus of a menu item
	function get_submenus($r_id)
	{
		$this->db->select('*');
		$this->db->from('acl_resources');
		$this->db->where('parent_id',$r_id);
		$this->db->where('menu',1);
		$this->db->where('active',1);
		$this->db->order_by('position','asc');
		$query = $this->db->get();
		return $query->result();	
	}
	
	// Get the details of a resource type by id
	function get_type_details($rt_id)
	{
		$this->db->select('*');
		$this->db->from('acl_resource_types');
		$this->db->where('type_id',$rt_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all resource types from the DB
	function get_all_resource_types()
	{
		$this->db->select('*');
		$this->db->from('acl_resource_types');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Add a resource
    function add_resource()
    {
        $data = array('resource_name'=>$this->input->post('resource_name'),'resource_type'=>$this->input->post('resource_type'),
        	'menu'=>$this->input->post('menu'),'url'=>$this->input->post('url'),'parent_id'=>$this->input->post('parent_id'),
        	'position'=>$this->input->post('position'));
        $this->db->insert('acl_resources',$data);
        $resource_id = $this->db->insert_id();
        $this->add_default_admin_rule($resource_id);
    }
    
    // Edit the details of a resource
    function edit_resource($r_id)
    {
        $data = array('menu'=>$this->input->post('menu'),'position'=>$this->input->post('position'),'resource_name'=>$this->input->post('resource_name'),
			'url'=>$this->input->post('url'));
        $this->db->where('resource_id',$r_id);
        $this->db->update('acl_resources',$data);
    }
    
    // Delete a resource
    function delete_resource($r_id)
    {
        $this->db->delete('acl_resources',array('resource_id'=>$r_id));
        $this->db->delete('acl_resources',array('parent_id'=>$r_id));
        $this->db->delete('acl_rules',array('resource_id'=>$r_id));
    }
	
	// Add a new role based rule
	function add_role_rule()
	{
		$resources = $_POST['resource_id'];
		$counter = count($resources);
		for($i=0;$i<$counter;$i++){
			$this->db->select('rule_id');
			$this->db->from('acl_rules');
			$this->db->where('resource_id',$resources[$i]);
			$this->db->where('role_id',$this->input->post('role_id'));
			$query = $this->db->get();
			if($query->num_rows() != 0){
				$data = array('rule'=>$this->input->post('rule'));
				$this->db->where('resource_id',$resources[$i]);
				$this->db->where('role_id',$this->input->post('role_id'));
				$this->db->update('acl_rules',$data);
			}else{
				$data = array('role_id'=>$this->input->post('role_id'),'resource_id'=>$resources[$i],'rule'=>$this->input->post('rule'));
				$this->db->insert('acl_rules',$data);
			}
		}
	}
	
	// Add a new user based rule
	function add_user_rule()
	{
		$resources = $_POST['resource_id'];
		$counter = count($resources);
		for($i=0;$i<$counter;$i++){
			$this->db->select('rule_id');
			$this->db->from('acl_rules');
			$this->db->where('resource_id',$resources[$i]);
			$this->db->where('user_id',$this->input->post('user_id'));
			$query = $this->db->get();
			if($query->num_rows() != 0){
				$data = array('rule'=>$this->input->post('rule'));
				$this->db->where('resource_id',$resources[$i]);
				$this->db->where('user_id',$this->input->post('user_id'));
				$this->db->update('acl_rules',$data);
			}else{
				$data = array('user_id'=>$this->input->post('user_id'),'resource_id'=>$resources[$i],'rule'=>$this->input->post('rule'));
				$this->db->insert('acl_rules',$data);
			}
		}
	}
	
	// Delete a rule
	function delete_rule($r_id)
	{
		$this->db->delete('acl_rules',array('rule_id'=>$r_id));
	}
	
	/**
	* Check if a user has access to a URL
	* This function is called after every controller is constructed 
	* through the post_controller_constructor hook
	*
	*/
	function check_access()
	{
		if($this->uri->segment(3) == '')
			$uri = $this->uri->uri_string();
		elseif(is_numeric($this->uri->segment(3)))
			$uri = $this->uri->segment(1).'/'.$this->uri->segment(2);
		else
			$uri = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);
		if($this->is_resource($uri) && !$this->has_access($uri) || $this->is_resource($uri) && !$this->is_active($uri)){
			redirect('admin/acl/unauthorised');
		}
	}
	
	/**
	*	
	* Check if a user has access to a resource
	* 
	* @param string uri
	* 
	*/
	function has_access($uri)
	{
		$user_id = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('user_level');
		$parents = json_decode($this->get_role_parents($role_id));
		$inherited_access = 0;
		if($parents != '0'){
			$cp = count($parents);
			for($i=0;$i<$cp;$i++){
				$inherited[] = $this->get_inherited_role_rules($parents[$i]);
			}
			if(!empty($inherited)){
				$ci = count($inherited);
				for($i=0;$i<$ci;$i++){
					if($inherited[$i]){
						foreach($inherited[$i] as $rules){
							if($rules->rule == 1 AND $rules->url == $uri){
								$inherited_access = 1;
							}
						}
					}
				}
			}
		}
		$ids = $this->get_resource_ids($uri);
		if(!empty($ids)){
			/* 	
			*	Check if the user has access to the resource 
			*	param int $user_id 
			*/
			$this->db->select('rule');
			$this->db->from('acl_rules');
			foreach($ids as $id)
			{
				$this->db->where('resource_id',$id->resource_id);
				$this->db->where('rule','1');
				$this->db->where('user_id',$user_id);
			}
			$user_access = $this->db->get();
			/* 	
			*	Check if the user's role has access to the resource  
			*	param int $role_id
			*/
			$this->db->select('rule');
			$this->db->from('acl_rules');
			foreach($ids as $id)
			{
				$this->db->where('resource_id',$id->resource_id);
				$this->db->where('rule','1');
				$this->db->where('role_id',$role_id);
			}
			$role_access = $this->db->get();
			if($user_access->num_rows() != 0 OR $role_access->num_rows() != 0 OR $inherited_access == 1){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/**
	*	Check if a role has access to a menu item
	* 
	*	@param string uri
	*	@param int role_id
	* 
	*/
	function role_has_access($uri,$role_id)
	{
		$parents = json_decode($this->get_role_parents($role_id));
		$inherited_access = 0;
		if($parents != '0'){
			$cp = count($parents);
			for($i=0;$i<$cp;$i++){
				$inherited[] = $this->get_inherited_role_rules($parents[$i]);
			}
			if(!empty($inherited)){
				$ci = count($inherited);
				for($i=0;$i<$ci;$i++){
					if($inherited[$i]){
						foreach($inherited[$i] as $rules){
							if($rules->rule == 1 AND $rules->url == $uri){
								$inherited_access = 1;
							}
						}
					}
				}
			}
		}
		$ids = $this->get_resource_ids($uri);
		if(!empty($ids)){
			/* 	
			*	Check if the user's role has access to the resource  
			*	param int $role_id
			*/
			$this->db->select('rule');
			$this->db->from('acl_rules');
			foreach($ids as $id)
			{
				$this->db->where('resource_id',$id->resource_id);
				$this->db->where('rule','1');
				$this->db->where('role_id',$role_id);
			}
			$role_access = $this->db->get();
			if($role_access->num_rows() != 0 OR $inherited_access == 1){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/**
	* Check if a user has access to a menu item
	*
	* @param string uri
	* @param int user_id
	*
	*/
	function user_has_access($uri,$user_id)
	{
		$ids = $this->get_resource_ids($uri);
		if(!empty($ids)){
			/* 	
			*	Check if the user has access to the resource 
			*	param int $user_id 
			*/
			$this->db->select('rule');
			$this->db->from('acl_rules');
			foreach($ids as $id)
			{
				$this->db->where('resource_id',$id->resource_id);
				$this->db->where('rule','1');
				$this->db->where('user_id',$user_id);
			}
			$user_access = $this->db->get();
			if($user_access->num_rows() != 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	// Get the resource ids of a uri
	function get_resource_ids($uri)
	{
		$this->db->select('resource_id');
		$this->db->from('acl_resources');
		$this->db->where('url',$uri);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get a list of all resources
	function get_all_resources($num,$offset)
	{
		$this->db->select('ar.resource_name,ar.url,ar.resource_id,art.type_name,art.type_id');
		$this->db->from('acl_resources as ar');
		$this->db->join('acl_resource_types as art','ar.resource_type = art.type_id','INNER');
		$this->db->order_by('ar.parent_id','ASC');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all resource parents for listing
	function get_parent_resources($num,$offset)
	{
		$this->db->select('ar.resource_name,ar.active,ar.url, ar.resource_id, art.type_name, art.type_id');
		$this->db->from('acl_resources as ar');
		$this->db->join('acl_resource_types as art','ar.resource_type = art.type_id','INNER');
		$this->db->where('ar.parent_id','0');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}	
	
	// Get a resources children for listing
	function get_resource_children($r_id)
	{
		$this->db->select('ar.resource_name,ar.active,ar.url,ar.resource_id,art.type_name,art.type_id');
		$this->db->from('acl_resources as ar');
		$this->db->join('acl_resource_types as art','ar.resource_type = art.type_id','INNER');
		$this->db->where('ar.parent_id',$r_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get the details for a particular resource by ID
	function get_resource_details($r_id)
	{
		$this->db->select('ar.resource_name,ar.url,ar.resource_id,ar.parent_id,ar.menu,ar.position,art.type_name,art.type_id');
		$this->db->from('acl_resources as ar');
		$this->db->join('acl_resource_types as art','ar.resource_type = art.type_id','INNER');
		$this->db->where('ar.resource_id',$r_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all resources that are parents
	function get_resource_parents()
	{
		$this->db->select('resource_id,resource_name');
		$this->db->from('acl_resources');
		$this->db->where('parent_id',0);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all roles
	function get_all_roles()
	{
		$this->db->select('*');
		$this->db->from('user_level');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all users
	function get_all_users()
	{
		$this->db->select('user_id,fullname');
		$this->db->from('users');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get the roles that have explicit rules tied to them
	function get_all_rule_roles()
	{
		$this->db->distinct('ac.role_id');
		$this->db->select('ac.role_id,ul.user_level');
		$this->db->from('acl_rules as ac');
		$this->db->join('user_level as ul','ac.role_id = ul.u_level_id','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all rules tied to user roles
	function get_all_role_rules($num,$offset)
	{
		$this->db->select('ac.rule_id,ac.rule,ul.user_level,ar.resource_name');
		$this->db->from('acl_rules as ac');
		$this->db->where('ac.user_id',NULL);
		$this->db->join('user_level as ul','ac.role_id = ul.u_level_id','INNER');
		$this->db->join('acl_resources as ar','ac.resource_id = ar.resource_id','INNER');
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all rules tied to a particular role by role_id
	function get_role_rules($role_id)
	{
		$this->db->select('ac.rule_id,ac.rule,ul.user_level,ul.parent,ar.resource_id,ar.resource_name,ar.url,arv.value');
		$this->db->from('acl_rules as ac');
		$this->db->where('ac.user_id',NULL);
		$this->db->where('ac.role_id',$role_id);
		$this->db->join('user_level as ul','ac.role_id = ul.u_level_id','INNER');
		$this->db->join('acl_resources as ar','ac.resource_id = ar.resource_id','INNER');
		$this->db->join('acl_rule_values as arv','ac.rule = arv.rule','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all rules tied to users
	function get_all_user_rules()
	{
		$this->db->distinct('ac.user_id');
		$this->db->select('ac.user_id,u.fullname');
		$this->db->from('acl_rules as ac');
		$this->db->join('users as u','ac.user_id = u.user_id','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get all rules tied to a particular role by user_id
	function get_user_rules($user_id)
	{
		$this->db->select('ac.rule_id,ac.rule,ar.resource_name,ar.url,arv.value');
		$this->db->from('acl_rules as ac');
		$this->db->join('acl_resources as ar','ac.resource_id = ar.resource_id','INNER');
		$this->db->join('acl_rule_values as arv','ac.rule = arv.rule','INNER');
		$this->db->where('ac.role_id',NULL);
		$this->db->where('ac.user_id',$user_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get the details of a rule by rule_id
	function get_rule_details($r_id)
	{
		$this->db->select('*');
		$this->db->from('ac_rules');
		$this->db->where('rule_id',$r_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get the details of a rule tied to a role for editing
	function get_role_rule_details($r_id)
	{
		$this->db->select('ac.rule_id,ac.resource_id,ac.rule,ac.role_id,ass.resource_name,ul.user_level');
		$this->db->from('acl_rules as ac');
		$this->db->where('ac.rule_id',$r_id);
		$this->db->join('acl_resources as ass','ac.resource_id = ass.resource_id','INNER');
		$this->db->join('user_level as ul','ac.role_id = ul.u_level_id','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Get the details of a rule tied to a user for editing
	function get_user_rule_details($r_id)
	{
		$this->db->select('ac.rule_id,ac.resource_id,ac.rule,ac.user_id,ass.resource_name,u.fullname');
		$this->db->from('acl_rules as ac');
		$this->db->where('ac.rule_id',$r_id);
		$this->db->join('acl_resources as ass','ac.resource_id = ass.resource_id','INNER');
		$this->db->join('users as u','ac.user_id = u.user_id','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	// Allow a user rule
	function allow_user_rule($r_id)
	{
		$this->db->where('rule_id',$r_id);
		$this->db->update('acl_rules',array('rule'=>1));
	}
	
	// Deny a user rule
	function deny_user_rule($r_id)
	{
		$this->db->where('rule_id',$r_id);
		$this->db->update('acl_rules',array('rule'=>0));
	}
	
	// Allow a role rule
	function allow_role_rule($r_id)
	{
		$this->db->where('rule_id',$r_id);
		$this->db->update('acl_rules',array('rule'=>1));
	}
	
	// Deny a role rule
	function deny_role_rule($r_id)
	{
		$this->db->where('rule_id',$r_id);
		$this->db->update('acl_rules',array('rule'=>0));
	}
	
	function get_setting_value($setting)
	{
		$this->db->where('code', $setting);
		$query = $this->db->get('settings');
		if ($query->num_rows() == 1)
			return $query->row()->value;
		else
			return NULL;
	}	
	
	function level_has_users($level_id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('u_level_id',$level_id);
		if($this->db->count_all_results() > 0)
			return true;
		else
			return false;
	}
	
	function group_has_users($group_id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('u_group_id',$group_id);
		if($this->db->count_all_results() > 0)
			return true;
		else
			return false;
	}
	
	function status_level($u_level_id,$status)
	{
		$this->db->where('u_level_id', $u_level_id);
		$this->db->update("user_level",array('activate'=>$status));
		redirect('admin/acl/user_levels');
	}
	
	function status_group($u_group_id,$status)
	{
		$this->db->where('u_group_id', $u_group_id);
		$this->db->update("user_group",array('activate'=>$status));
		redirect('admin/acl/user_levels');
	}
	
	function get_inherited_role_rules($role_id)
	{
		$this->db->select('ac.rule_id,ac.role_id,ac.rule,ul.user_level,ul.parent,ar.resource_name,ar.url,arv.value');
		$this->db->from('acl_rules as ac');
		$this->db->where('ac.user_id',NULL);
		$this->db->where('ac.role_id',$role_id);
		$this->db->join('user_level as ul','ac.role_id = ul.u_level_id','INNER');
		$this->db->join('acl_resources as ar','ac.resource_id = ar.resource_id','INNER');
		$this->db->join('acl_rule_values as arv','ac.rule = arv.rule','INNER');
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	function get_role_parents($role_id)
	{
		$this->db->select('parent');
		$this->db->from('user_level');
		$this->db->where('u_level_id',$role_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row('parent');
		else
			return NULL;
	}
	
	function is_resource($url)
	{
		$this->db->select("1",FALSE);
		$this->db->from('acl_resources');
		$this->db->where('url',$url);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return TRUE;
		else
			return FALSE;
	}
	
	function is_active($url)
	{
		$this->db->select("1",FALSE);
		$this->db->from('acl_resources');
		$this->db->where('url',$url);
		$this->db->where('active',1);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	* Activate a resource
	* 
	* @param int resource_id
	* 
	*/
	function activate_resource($r_id)
	{
		$data = array('active'=>1);
		$this->db->where('resource_id',$r_id);
		$this->db->update('acl_resources',$data);
	}
	
	/**
	* Deactivate a resource
	* 
	* @param int resource_id
	*
	* 
	*/
	function deactivate_resource($r_id)
	{
		$data = array('active'=>0);
		$this->db->where('resource_id',$r_id);
		$this->db->update('acl_resources',$data);
	}
}