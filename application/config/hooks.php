<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
                                'class'    => 'Acl_model',		// Name of the class to be invoke
                                'function' => 'check_access',	// Function in class named above to be called
                                'filename' => 'acl_model.php',	// Name of the file containing the class
                                'filepath' => 'modules/admin/models',	// Path to the file (relative to the 'application' folder)
                                'params'   => ''
                                );


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */