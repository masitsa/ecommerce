<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	public function __construct($rules = array())
	{
		parent::__construct($rules);
	}
	
	function alpha_dash_space($str)
	{
		if(preg_match("/^([-a-z_ ])+$/i", $str)) {
			return TRUE;
		} else {
			$this->set_message('alpha_dash_space','The %s field can only contain alpabetic characters, dashes, or spaces');
            return FALSE;
		}
	}
}