<?php
require_once "./application/modules/site/controllers/site.php";
class Map extends Site
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/map_model');
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
	* Build the sidebar for the map to be displayed on the frontend
	* Does not include options to edit and delete locations
	* Only for viewing the locations already added
	* 
	*/
	function build_site_map_sidebar() 
	{
		$locations = $this->map_model->get_all_locations();
		$i = 0;
		foreach($locations as $row) {
			echo "<a href='javascript:myclick(".$i.")'>".$row['location_name']."</a><br />";
			$i++;
		}
	}
}