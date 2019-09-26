  <?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'pages/view';
$route['login'] = 'users/login';
$route['(:any)'] = 'users/view/$1';
$route['api/user/sigin'] = 'api/user/sigin';
$route['api/user/sigup'] = 'api/user/sigup';
$route['api/user/profile'] = 'api/user/user';
$route['api/user/update'] = 'api/user/update';
$route['api/user/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/user/id/$1/format/$3$4';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;