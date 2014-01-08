<?php
require_once "./application/modules/admin/controllers/admin.php";
class Map extends Admin
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('map_model');
		$this->template->set_template("themes/".$this->theme."/templates/admin_map_template");
	}
	
	/**
	* View the various locations already added to the DB
	* One can also add new location from this page
	* 
	*/
	function index()
	{
		$this->template->stylesheet->add(base_url().'css/map_as_background.css');
		$this->template->javascript->add('//maps.googleapis.com/maps/api/js?libraries=places&sensor=false');
		$this->template->javascript->add(base_url().'js/downloadxml.js');
		$this->template->javascript->add(base_url().'js/infobox.js');
		$this->template->javascript->add(base_url().'js/map.js');
		$this->template->content->view('map/map_manager');
		$this->template->publish();
	}
	
	/**
	* Add a new location to the DB 
	*
	*/
	function add_location()
	{
		$this->form_validation->set_rules('location_name','Location Name','trim|required|xss_clean');
		$this->form_validation->set_rules('address','Address','trim|required|xss_clean');
		$this->form_validation->set_rules('latlng','LatLong','trim|required|xss_clean');
		$this->form_validation->set_rules('zip','Zip Code','trim|required|xss_clean');
		if($this->form_validation->run()){
			$this->map_model->save_location();
			redirect('admin/map');
		} else {
			$this->template->stylesheet->add(base_url().'css/map_as_background.css');
			$this->template->javascript->add('//maps.googleapis.com/maps/api/js?libraries=places&sensor=false');
			$this->template->javascript->add(base_url().'js/downloadxml.js');
			$this->template->javascript->add(base_url().'js/infobox.js');
			$this->template->javascript->add(base_url().'js/map.js');
			$this->template->content->view('map/add_location_form');
			$this->template->publish();
		}
	}
	
	/**
	* Edit the details of a location
	* 
	* @param float $lat
	* @param float $lng
	* 
	*/
	function edit_location($lat,$lng)
	{
		$data['details'] = $this->map_model->get_location_details($lat,$lng);
		$this->form_validation->set_rules('location_name','Location Name','trim|required|xss_clean');
		$this->form_validation->set_rules('address','Address','trim|required|xss_clean');
		$this->form_validation->set_rules('latlng','LatLong','trim|required|xss_clean');
		$this->form_validation->set_rules('zip','Zip Code','trim|required|xss_clean');
		if($this->form_validation->run()){
			$this->map_model->edit_location($data['details']->id);
			redirect('admin/map');
		} else {
			$this->template->stylesheet->add(base_url().'css/map_as_background.css');
			$this->template->javascript->add('//maps.googleapis.com/maps/api/js?libraries=places&sensor=false');
			$this->template->javascript->add(base_url().'js/downloadxml.js');
			$this->template->javascript->add(base_url().'js/infobox.js');
			$this->template->javascript->add(base_url().'js/map.js');
			$this->template->content->view('map/edit_location_form',$data);
			$this->template->publish();
		}
	}
	
	/**
	* Delete a location from the DB
	* 
	* @param float $lat
	* @param float $lng
	* 
	*/
	function delete_location($lat,$lng)
	{
		$this->map_model->delete_location($lat,$lng);
		redirect('admin/map');
	}
	
	/**
	* Build the xml file used to display markers on the map
	* 
	*/
	function create_markers()
	{
		// Start XML file, create parent node
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		// Get locations from the DB
		$locations = $this->map_model->get_all_locations();
		if($locations) {
			header("Content-type: text/xml");
			foreach($locations as $row){  
				$node = $dom->createElement("marker");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("location_name", $row['location_name']);
				$newnode->setAttribute("project_info", $row['project_info']);
				$newnode->setAttribute("address", $row['full_address']);
				$newnode->setAttribute("project_date", $row['project_date']);
				$newnode->setAttribute("lat", $row['lat']);  
				$newnode->setAttribute("lng", $row['lng']);
				$newnode->setAttribute("zip_code", $row['zip_code']);
			} 
			echo $dom->saveXML(); // Output the xml document
		}
	}
	
	/**
	* Build the administrator sidebar for the map
	* Includes options to edit and delete locations
	* 
	*/
	function build_admin_map_sidebar() 
	{
		$locations = $this->map_model->get_all_locations();
		$i = 0;
		foreach($locations as $row) {
			echo "<a href='javascript:myclick(".$i.")'>".$row['location_name']."</a>&nbsp;&nbsp;
			<a href='".base_url()."admin/map/edit_location/".$row['lat']."/".$row['lng']."' title='Edit'>
				<img src='".base_url()."img/icons/16/form_edit.png'/></a>&nbsp;&nbsp;
			<a href='".base_url()."admin/map/delete_location/".$row['lat']."/".$row['lng']."' title='Delete' onclick='return confirm_delete()'>
				<img src='".base_url()."img/icons/16/delete.gif'/></a><br />";
			$i++;
		}
	}
}