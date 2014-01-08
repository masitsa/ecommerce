<?php
require_once "./application/modules/admin/controllers/admin.php";
class Plugins extends Admin
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('route_lib'));
		$this->load->library('plugin_lib/plugin_lib');
		$this->load->model('plugins_model');
		$this->template->set_template("themes/".$this->theme."/templates/admin_template");
	}
	
	/**
	 * Default action for the controller
	 * 
	 */
	function index()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/plugins/index/';
		$this->db->where('parent_id',0);
		$this->db->where('plugin',1);
		$config['total_rows'] = $this->db->count_all_results('acl_resources');
		$config['per_page'] = 10;
		$config['num_links'] = 5;
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
		$data['plugins'] = $this->plugins_model->get_all_plugins($config['per_page'],$this->uri->segment(4));
		$this->template->content->view('plugins/dashboard',$data);
		$this->template->publish();
	}
	
	/**
	 * Upload and install a new plugin to the system
	 *
	 */
	function install()
	{
		$config['upload_path'] = './tmp/';
		$config['allowed_types'] = 'zip';
		$config['max_size'] = 1024 * 5;
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('plugin')) {
			if($_POST)
				$error['error'] = $this->upload->display_errors();
			else
				$error['error'] = NULL;
			$this->template->content->view('plugins/upload_plugin_form',$error);
			$this->template->publish();
		} else {
			$data = array('upload_data'=>$this->upload->data());
			$zip_file = $data['upload_data']['full_path'];
			$plugin_name = $data['upload_data']['raw_name'];
			$zip = new PclZip($zip_file);
			$content = $zip->listContent();
			if ($zip->properties() != 0) {
				$cc = count($content);
				for($i = 0; $i < $cc; $i++) {
					$entry = $content[$i]['filename'];
					if(preg_match('#\.(xml)$#i', $entry)){
						$xml = $entry;
					}elseif(preg_match('#\.(sql)$#i', $entry)){
						$sql = $entry;
					}
				}
				// If the package is missing the xml or sql file required, delete it and show an error to this effect.
				if(empty($xml) || empty($sql)) {
					unlink($zip_file);
					$this->route_lib->redirect_with_error('admin/plugins','Failed. The package is missing one of the files.');
				} else {
					$zip->extract('./tmp/');
					/*
					 * $plugin_resources
					 * 
					 * Array that will store the files and folders added by the plugin
					 * Will take the form resource_name => is_folder
					 */
					$plugin_resources = array();
					$dbms_schema = $data['upload_data']['file_path'].$sql;
					$config_file = $data['upload_data']['file_path'].$xml;
					$sql_query = @fread(@fopen($dbms_schema, 'r'), filesize($dbms_schema)) or die('SQL File Error');
					for($i = 0;$i < $cc;$i++) {
						$filename = explode('/',$content[$i]['filename']);
						// Select all files and directories within the plugin folder. Exclude the plugin folder itself.
						if($filename[0] == $plugin_name && $filename[1] != '') {
							// Remove the plugin name from the name of the file or directory and recombine the new name.
							unset($filename[0]);
							$filename = implode('/',$filename);
							// Copy and create directories if they don't exist
							if($content[$i]['folder']) {
								if(!is_dir('./'.$filename)) {
									mkdir('./'.$filename,0777);
									// Give everybody rwx permissions. Will help in manipulation of directories on Linux servers.
									chmod('./'.$filename,0777);
									// Register created directory as a plugin resource
									$plugin_resources[$filename] = $content[$i]['folder'];
								}
							} else {
								// Copy and create files if they don't exist
								if(@fwrite(@fopen('./'.$filename,'x'),file_get_contents('./tmp/'.$content[$i]['filename']))) {
									// Give everybody rwx permissions. Will help in manipulation of files on Linux servers.
									chmod('./'.$filename,0777);
									// Register created file as a plugin resource
									$plugin_resources[$filename] = $content[$i]['folder'];
								}
							}
						}
					}
					//$this->delete_uploaded_files($data,$zip_file,$xml,$sql);
					
					if(file_exists($config_file)) {
						if($parsed_xml = $this->read_xml_file($config_file)) {
							if(!$plugin_id = $this->execute_xml_file($parsed_xml)) {
								$this->delete_uploaded_files($data,$zip_file,$xml,$sql);
								$this->route_lib->redirect_with_error('admin/plugins','Failed. XML File Error.');
							}
						} else {
							$this->delete_uploaded_files($data,$zip_file,$xml,$sql);
							$this->route_lib->redirect_with_error('admin/plugins','Failed. XML File Error.');
						}
					} else {
						$this->delete_uploaded_files($data,$zip_file,$xml,$sql);
						$this->route_lib->redirect_with_error('admin/plugins','Failed. XML File Error.');
					}
					
					$sql_query = $this->remove_remarks($sql_query);
					$sql_query = $this->remove_comments($sql_query);
					$sql_query = $this->split_sql_file($sql_query, ';');
					if(!empty($sql_query)) {
						if(!$result = $this->plugins_model->run_sql($sql_query)) {
							$this->delete_uploaded_files($data,$zip_file,$xml,$sql);
							$this->route_lib->redirect_with_error('admin/plugins','Failed. SQL File Error.');
						}
					} else {
						$this->delete_uploaded_files($data,$zip_file,$xml,$sql);
						$this->route_lib->redirect_with_error('admin/plugins','Failed. SQL File Error.');
					}
					$this->delete_uploaded_files($data,$zip_file,$xml,$sql);
					$this->plugins_model->save_plugin_resources($plugin_id,$plugin_resources);
					$this->route_lib->redirect_with_message('admin/plugins','Congratulations!! You have successfully installed the '.$plugin_name.' plugin.');
					// Make entries into the plugins table here. Just to be sure that everything went well and the plugin is really installed
					// You can use the $plugin_name varialble to identify the plugin that was just installed, get its ID and use it to make the entries
					// function save_plugin_resources($plugin_name,$plugin_resources)
				}
			} else { 
				unlink($zip_file);
				$this->route_lib->redirect_with_error('admin/plugins','Failed. There is a problem with your zip file.');
			}
		}
	}
	
	/**
	 * Edit the details of an already installed plugin 
	 * 
	 * @param int plugin_id
	 * 
	 */
	function edit_plugin($plugin_id)
	{
		$data['details'] = $this->plugins_model->get_plugin_details($plugin_id);
		$this->load->view('edit_plugin_form');
	}
	
	/**
	 * Delete the uploaded zip file and extracted files
	 * 
	 * @param array upload_data
	 * @param string zip_file
	 * @param string xml
	 * @param string sql
	 * @param string css(optional)
	 * 
	 */
	function delete_uploaded_files($data,$zip_file,$xml,$sql,$css = NULL)
	{
		unlink($zip_file);
		$this->remove_directory($data['upload_data']['file_path'].'/'.$data['upload_data']['raw_name']);
		unlink($data['upload_data']['file_path'].$xml);
		unlink($data['upload_data']['file_path'].$sql);
		//unlink($data['upload_data']['file_path'].$css);
	}
	
	/**
	 * Uninstall and delete a plugin from the system
	 * 
	 * @param int plugin_id
	 * 
	 */
	function uninstall_plugin($plugin_id)
	{
		$this->delete_plugin_resources($plugin_id);
		$this->plugins_model->drop_plugin_tables($plugin_id);
		$this->plugins_model->delete_plugin_pages($plugin_id);
		$plugin_name = $this->plugins_model->delete_plugin_acl_rules_and_resources($plugin_id);
		$this->route_lib->redirect_with_message('admin/plugins','Congratulations!! You have successfully uninstalled the '.$plugin_name.' plugin.');
	}
	
	/**
	 * Recursively delete directories and their contents
	 * Used in the uninstallation process
	 * Also used in cleaning up the tmp directory after failed/successful installation
	 * 
	 * @param string directory
	 * 
	 */
	function remove_directory($directory)
	{
		if(is_dir($directory)) {
			$elements = scandir($directory);
			foreach($elements as $element) {
				if($element != "." && $element != "..") {
					if (filetype($directory."/".$element) == "dir")
						$this->remove_directory($directory."/".$element); 
					else
						unlink($directory."/".$element);
				}
			}
			reset($elements);
			rmdir($directory);
		}
	}
	
	/**
	 * Strip the sql comment lines out of an uploaded sql file
	 * Specifically for mysql files used in the installation process
	 * 
	 * @param string output
	 * 
	 */
	function remove_comments(&$output)
	{
		$lines = explode("\n", $output);
		$output = "";
		$linecount = count($lines);
		
		$in_comment = false;
		for($i = 0; $i < $linecount; $i++)
		{
			if( preg_match("/^\/\*/", preg_quote($lines[$i])) )
				$in_comment = true;
			if( !$in_comment )
				$output .= $lines[$i] . "\n";
			if( preg_match("/\*\/$/", preg_quote($lines[$i])) )
				$in_comment = false;
		}
		unset($lines);
		return $output;
	}
	
	/**
	 * Strip the sql remark lines out of an uploaded sql file 
	 * 
	 * @param string sql
	 * @return string output
	 * 
	 */
	function remove_remarks($sql)
	{
		$lines = explode("\n", $sql);
		$sql = "";
		$linecount = count($lines);
		$output = "";
		for ($i = 0; $i < $linecount; $i++)
		{
			if(($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
				if (isset($lines[$i][0]) && $lines[$i][0] != "#")
					$output .= $lines[$i] . "\n";
				else
					$output .= "\n";
				// Trading a bit of speed for lower mem. use here.
				$lines[$i] = "";
			}
		}
		return $output;
	}
	
	/**
	 * Split an uploaded sql file into single sql statements.
	 * Expects trim() to have already been run on sql.
	 * 
	 * @param string sql
	 * @param string delimiter
	 * @return array output
	 * 
	 */
	function split_sql_file($sql, $delimiter)
	{
		// Split up our string into "possible" SQL statements.
		$tokens = explode($delimiter, $sql);
		$sql = "";
		$output = array();
		
		// we don't actually care about the matches preg gives us.
		$matches = array();
		$token_count = count($tokens);
		for ($i = 0; $i < $token_count; $i++)
		{
			// Don't wanna add an empty string as the last thing in the array.
			if(($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
				// This is the total number of single quotes in the token.
				$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
				// Counts single quotes that are preceded by an odd number of backslashes,
				// which means they're escaped quotes.
				$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
				
				$unescaped_quotes = $total_quotes - $escaped_quotes;
				
				// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
				if(($unescaped_quotes % 2) == 0) {
					// It's a complete sql statement.
					$output[] = $tokens[$i];
					// save memory.
					$tokens[$i] = "";
				} else {
					// incomplete sql statement. keep adding tokens until we have a complete one.
					// $temp will hold what we have so far.
					$temp = $tokens[$i] . $delimiter;
					// save memory..
					$tokens[$i] = "";
					
					// Do we have a complete statement yet?
					$complete_stmt = false;
					
					for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
					{
						// This is the total number of single quotes in the token.
						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
						// Counts single quotes that are preceded by an odd number of backslashes,
						// which means they're escaped quotes.
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
						
						$unescaped_quotes = $total_quotes - $escaped_quotes;
						if(($unescaped_quotes % 2) == 1) {
							// odd number of unescaped quotes. In combination with the previous incomplete
							// statement(s), we now have a complete statement. (2 odds always make an even)
							$output[] = $temp . $tokens[$j];
							
							// save memory.
							$tokens[$j] = "";
							$temp = "";
							
							// exit the loop.
							$complete_stmt = true;
							// make sure the outer loop continues at the right point.
							$i = $j;
						} else {
							// even number of unescaped quotes. We still don't have a complete statement.
							// (1 odd and 1 even always make an odd)
							$temp .= $tokens[$j] . $delimiter;
							// save memory.
							$tokens[$j] = "";
						}
					}
				}
			}
		}
		return $output;
	}
	
	/**
	 * Read an xml file
	 * 
	 * @param string config_file
	 * @return object
	 * 
	 */
	function read_xml_file($config_file)
	{
		if($xml_file = simplexml_load_file($config_file))
			return $xml_file;
		else
			return NULL;
	}
	
	/**
	 * Execute xml file
	 * 
	 * @param string xml_file
	 * @return bool
	 *  
	 */
	function execute_xml_file($xml_file)
	{
		if($data = $this->plugins_model->execute_xml($xml_file))
			return $data;
		else
			return FALSE;
	}
	
	/**
	 * Delete all files and directories that belong to a plugin
	 * Also delete all related DB entries
	 * Called during uninstallation of a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function delete_plugin_resources($plugin_id)
	{
		$resources = $this->plugins_model->get_plugin_resources($plugin_id);
		// First delete the files and directories
		foreach($resources as $resource) {
			if($resource->is_folder)
				$this->remove_directory('./'.$resource->resource_name);
			else
				unlink('./'.$resource->resource_name);
		}
		// Now delete the database entries in the plugin_resources table
		if($this->plugins_model->delete_plugin_resources($plugin_id))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * View all pages that were created by a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function plugin_pages($plugin_id)
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/plugins/plugin_pages/';
		$this->db->where('plugin_id',$plugin_id);
		$config['total_rows'] = $this->db->count_all_results('pages');
		$config['per_page'] = 10;
		$config['num_links'] = 5;
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
		$pages = $this->plugins_model->get_plugin_pages($plugin_id);
		if(!empty($pages)) {
			$axns = array('data'=>'Actions','colspan'=>3);
			$this->table->set_heading('Page Title','Page URL',$axns);
			foreach($pages as $page)
				$this->table->add_row($page->page_title,$page->page_url,
					anchor("admin/plugins/edit_plugin_page/$page->page_uuid",'<i class="icon-edit butn butn-success"></i>',array('title'=>'Edit')));
		} else {
			$this->table->clear();
			$this->table->add_row('There are no plugins installed yet :-|');
		}
		$this->template->content->view('plugins/pages');
		$this->template->publish();
	}
	
	/**
	 * Edit the details of a plugin page
	 *
	 * @param int page_uuid
	 *
	 */
	function edit_plugin_page($page_uuid)
	{
		$data['details'] = $this->plugins_model->get_plugin_page_details($page_uuid);
		$this->form_validation->set_message('is_natural_no_zero','The %s field is required.');
		$this->form_validation->set_rules('page_title','Page Title','trim|required|xss_clean');
		$this->form_validation->set_rules('page_order','Page Order','trim|required|is_natural_no_zero|xss_clean');
		if($this->form_validation->run()) {
			$this->plugins_model->edit_plugin_page($page_uuid);
			redirect('admin/plugins');
		} else {
			// Set the template to use for this page
			$this->template->set_template('templates/admin_template');
			
			$this->template->content->view('plugins/edit_page',$data);
			$this->template->publish();
		}
	}
	
	/**
	 * Activate a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function activate($plugin_id)
	{
		$this->plugins_model->activate_plugin($plugin_id);
		redirect('admin/plugins');
	}
	
	/**
	 * Deactivate a plugin
	 *
	 * @param int plugin_id
	 *
	 */
	function deactivate($plugin_id)
	{
		$this->plugins_model->deactivate_plugin($plugin_id);
		redirect('admin/plugins');
	}
}
/* End of file plugins.php */
/* Location /application/modules/admin/controllers/plugins.php */