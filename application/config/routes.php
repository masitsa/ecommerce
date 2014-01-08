<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "site";

$route['login'] = "auth/login";
$route['home'] = "site/home";
$route['page/(:any)'] = "site/page/$1";
$route['post/(:any)'] = "site/post/$1";
$route['portfolio'] = "site/portfolio";
$route['portfolio/project/(:any)'] = "site/portfolio/project/$1";
$route['blog'] = "site/blog";
$route['blog/post/(:any)'] = "site/blog/post/$1";
$route['gallery'] = "site/gallery";
$route['gallery/category/(:any)'] = "site/gallery/category/$1";
$route['gallery/single/(:any)'] = "site/gallery/single/$1";
$route['contact'] = "site/contact";

$route['404_override'] = '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */