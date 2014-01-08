<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Plugins_model extends CI_Model
{
	function __construct()
	{
		parent:: __construct();
		$this->load->dbforge();
	}
	
	/**
	 * Get a list of all plugins in the system
	 * Paginated
	 *
	 * @param int num The number of records to select
	 * @param int offset Where to start selecting
	 * @return array
	 *
	 */
	function get_all_plugins($num,$offset)
	{
		$this->db->select('*');
		$this->db->from('acl_resources');
		$this->db->where('plugin',1);
		$query = $this->db->get('',$num,$offset);
		if($query->num_rows() != 0){
			return $query->result();
		}else{
			return NULL;
		}
	}
	
	/**
	 * Get the details of a plugin by plugin_id
	 *
	 * @param int plugin_id
	 * @return array
	 *
	 */
	function get_plugin_details($plugin_id)
	{
		$this->db->select('*');
		$this->db->from('acl_resources');
		$this->db->where('resource_id',$plugin_id);
		$this->db->where('plugin',1);
		$query = $this->db->get();
		if($query->num_rows() != 0){
			return $query->result();
		}else{
			return NULL;
		}
	}
	
	/**
	 * Run sql statements from the uploaded sql file
	 * 
	 * @param array sql
	 * @return bool
	 *  
	 */
	function run_sql($sql)
	{
		foreach($sql as $sql_query){
			if(!mysql_query($sql_query)) 
				return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Delete all ACL resources and rules that belong to a plugin
	 * Called during uninstallation of a plugin
	 * 
	 * @param int plugin_id
	 * @return string plugin name
	 * 
	 */
	function delete_plugin_acl_rules_and_resources($plugin_id)
	{
		$details = $this->get_plugin_details($plugin_id);
		$children_ids = $this->get_children_ids($plugin_id);
		foreach($details as $detail){
			$url = explode('/',$detail->url);
			$plugin_name = $url[1];
		}
		if($children_ids) {
			foreach($children_ids as $id) {
				$this->db->delete('acl_rules',array('resource_id'=>$id->resource_id));
			}
		}
		$this->db->delete('acl_resources',array('resource_id'=>$plugin_id));
		$this->db->delete('acl_resources',array('parent_id'=>$plugin_id));
		$this->db->delete('acl_rules',array('resource_id'=>$plugin_id));
		return $plugin_name;
	}
	
	/**
	 * Get the ids of any children of a resource
	 * 
	 * @param int $plugin_id
	 * @return object
	 * 
	 */
	function get_children_ids($plugin_id)
	{
		$this->db->select('resource_id');
		$this->db->from('acl_resources');
		$this->db->where('parent_id',$plugin_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Execute the instructions in the xml file 
	 * 
	 * @param object $xml_file
	 * @return bool
	 * 
	 */
	function execute_xml($xml_file)
	{
		$plugin_name = (string)$xml_file->plugin[0]->name;
		$plugin_url = (string)$xml_file->plugin[0]->url;
		$plugin_controller = $xml_file->plugin[0]->controller;
		if(empty($plugin_name) || empty($plugin_url) || empty($plugin_controller))
			return FALSE;
		$this->db->select('resource_id')->from('acl_resources')->where('resource_name',$plugin_name)->where('url',$plugin_url);
		$query = $this->db->get();
		if($query->num_rows() != 0) {
			$parent = $query->row()->resource_id;
		} else {
			$data = array('resource_name'=>$plugin_name,'resource_type'=>1,'url'=>$plugin_url,'parent_id'=>0,'menu'=>1,'plugin'=>1);
			$this->db->insert('acl_resources',$data);	// Add controller resource to the resources table
			$parent = $this->db->insert_id();
			$this->add_default_admin_rule($parent);		// Add a default "allow rule" for this resource
		}
		if(($f_count = count($xml_file->function)) > 0) {
			for($i = 0; $i < $f_count; $i++) {
				$resource_name = (string)$xml_file->function[$i]->name;
				$url = (string)$xml_file->function[$i]->url;
				$menu = (int)$xml_file->function[$i]->menu;
				$this->db->select('resource_id')->from('acl_resources')->where('resource_name',$resource_name)->where('url',$url);
				$query = $this->db->get();
				if($query->num_rows() == 0){
					$data = array('resource_name'=>$resource_name,'resource_type'=>2,'url'=>$url,'parent_id'=>$parent,'menu'=>$menu);
					$this->db->insert('acl_resources',$data);		// Add all controller functions to the resources table
					$resource_id = $this->db->insert_id();
					$this->add_default_admin_rule($resource_id);	// Add a default "allow rule" for this resource
				}
			}
		}
		if(($t_count = count($xml_file->table)) > 0) {
			for($j = 0; $j < $t_count; $j++) {
				$table_name = (string)$xml_file->table[$j]->name;
				$this->db->select('table_name')->from('plugin_tables')->where('table_name',$table_name);
				$query = $this->db->get();
				if($query->num_rows == 0) {
					$data = array('plugin_id'=>$parent,'table_name'=>$table_name);
					$this->db->insert('plugin_tables',$data);
				}
			}
		}
		if(($p_count = count($xml_file->page)) > 0) {
			for($k = 0; $k < $p_count; $k++) {
				do
					$uuid = $this->_generate_uuid(7);
				while(!$this->is_uuid_unique($uuid));
				$data = array('page_title'=>(string)$xml_file->page[$k]->title,'page_path'=>(string)$xml_file->page[$k]->path,'page_uuid'=>$uuid,
					'plugin_id'=>$parent,'page_url'=>(string)$xml_file->page[$k]->url,'active'=>1,'author'=>1,'created'=>date('Y-m-d H:i:s'));
				$this->db->insert('pages',$data);
			}
		}
		return $parent;
	}
	
	/**
	 * Get details of the admin user level
	 * Assumes that the admin user_level name is "Admin"
	 * Also assumes there is only one admin
	 * 
	 * @retun int
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
			return $query->row('u_level_id');
		else
			return NULL;
	}
	
	/**
	 * Add a default "allow" rule for the admin user level
	 * Called when creating a new resource
	 * 
	 * @param int $resource_id
	 * 
	 */
	function add_default_admin_rule($resource_id)
	{
		$admin_level_id  = $this->get_admin_level();
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
	
	/**
	 * Save the files and directories created by a plugin installtion to the  DB
	 * Helps remove all traces of a plugin on uninstallation
	 *
	 * @param string plugin_id
	 * @param array plugin_resources
	 * @return bool
	 *
	 */
	function save_plugin_resources($plugin_id,$plugin_resources)
	{
		foreach($plugin_resources as $resource_name => $is_folder) {
			$data = array('plugin_id'=>$plugin_id,'resource_name'=>$resource_name,'is_folder'=>$is_folder);
			$this->db->insert('plugin_resources',$data);
		}
	}
	
	/**
	 * Get a list of all resources that belong to a plugin
	 *
	 * @param int plugin_id
	 * @return array
	 *
	 */
	function get_plugin_resources($plugin_id)
	{
		$this->db->select('*');
		$this->db->from('plugin_resources');
		$this->db->where('plugin_id',$plugin_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Delete the plugin_resources table entries for a particular plugin
	 * Called during uninstallation of a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function delete_plugin_resources($plugin_id)
	{
		if($this->db->delete('plugin_resources',array('plugin_id'=>$plugin_id)))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Get the details of all tables created by a plugin
	 *
	 * @param int plugin_id
	 * @return array
	 *
	 */
	function get_plugin_tables($plugin_id)
	{
		$this->db->select('table_name');
		$this->db->from('plugin_tables');
		$this->db->where('plugin_id',$plugin_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	 * Drop all tables created by a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function drop_plugin_tables($plugin_id)
	{
		if($tables = $this->get_plugin_tables($plugin_id))
			foreach($tables as $table)
				$this->dbforge->drop_table($table->table_name);
		$this->db->delete('plugin_tables',array('plugin_id'=>$plugin_id));
	}
	
	/**
	 * Delete all pages that were created by a particular page
	 *
	 * @param int plugin_id
	 *
	 */
	function delete_plugin_pages($plugin_id)
	{
		$this->db->delete('pages',array('plugin_id'=>$plugin_id));
	}
	
	/**
	 * Get all pages that were created by a plugin
	 *
	 * @param int plugin_id
	 * @return array
	 *
	 */
	function get_plugin_pages($plugin_id)
	{
		$this->db->select('p.page_uuid,p.page_order,p.page_title,p.parent,p.active,p.page_path,p.page_url');
		$this->db->from('pages as p');
		$this->db->where('p.plugin_id',$plugin_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->result();
		else
			return NULL;
	}
	
	/**
	* Generate a random uuid of given length
	* 
	* @param int length (of the random number to be generated)
	* @return string 
	* 
	*/
	function _generate_uuid($length)
	{
		$random = '';
		for($i=0;$i<$length;$i++)
			$random .= mt_rand(0,9);
		return $random;
	}
	
	/**
	* Check if a generated page_uuid is duplicated in the DB
	* Just to be safe
	* 
	* @param int uuid
	* @return bool
	* 
	*/
	function is_uuid_unique($page_uuid)
	{
		$this->db->select('1',FALSE);
		$this->db->from('pages');
		$this->db->where('page_uuid',$page_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	 * Get the details of a particular page that was created by a plugin
	 *
	 * @param int page_uuid
	 *
	 */
	function get_plugin_page_details($page_uuid)
	{
		$this->db->select('page_order,page_title,page_layout,active');
		$this->db->from('pages');
		$this->db->where('page_uuid',$page_uuid);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return $query->row();
		else
			return NULL;
	}
	
	/**
	 * Edit the details of a plugin page
	 *
	 * @param int page_uuid
	 *
	 */
	function edit_plugin_page($page_uuid)
	{
		$data = array('page_title'=>$this->input->post('page_title'),'page_order'=>$this->input->post('page_order'));
		$this->db->where('page_uuid',$page_uuid);
		$this->db->update('pages',$data);
	}
	
	/**
	 * Activate a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function activate_plugin($plugin_id)
	{
		$this->db->where('resource_id',$plugin_id)->update('acl_resources',array('active'=>1));
		$this->db->where('parent_id',$plugin_id)->update('acl_resources',array('active'=>1));
		$this->db->where('plugin_id',$plugin_id)->update('pages',array('active'=>1));	
	}
	
	/**
	 * Deactivate a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function deactivate_plugin($plugin_id)
	{
		$this->db->where('resource_id',$plugin_id)->update('acl_resources',array('active'=>0));
		$this->db->where('parent_id',$plugin_id)->update('acl_resources',array('active'=>0));
		$this->db->where('plugin_id',$plugin_id)->update('pages',array('active'=>0));	
	}
	
	/**
	 * Check if a plugin has pages
	 * Used when displaying the plugins dashboard
	 *
	 * @param int plugin_id
	 * @return bool
	 *
	 */
	function plugin_has_pages($plugin_id)
	{
		$this->db->select('1',FALSE)->from('pages')->where('plugin_id',$plugin_id);
		$query = $this->db->get();
		if($query->num_rows() != 0)
			return TRUE;
		else
			return FALSE;
	}
}
/* End of file plugins_model.php */
/* Location: /application/modules/admin/models/plugins_model.php */