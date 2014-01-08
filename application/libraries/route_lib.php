<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Route_lib
{
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('session');
	}
	
	/**
	* Redirect from any controller to a view with a message to be displayed
	* 
	* @param string url
	* @param string message
	* 
	*/
	function redirect_with_message($url,$message)
	{
		$this->ci->session->set_flashdata('message',$message);
		redirect($url);
	}
	
	/**
	* Redirect from any controller to a view with an error to be displayed
	* 
	* @param string url
	* @param string message
	* 
	*/
	function redirect_with_error($url,$message)
	{
		$this->ci->session->set_flashdata('error',$message);
		redirect($url);
	}
}