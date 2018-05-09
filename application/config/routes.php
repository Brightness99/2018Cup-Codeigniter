<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['(:any)'] = "index/login/$1";
$route['(:any)/doLogin'] = "index/doLogin";
$route['(:any)/bienvenida'] = "index/bienvenida";
$route['(:any)/pronosticos/(:any)'] = "index/pronosticos";
$route['(:any)/logout'] = "index/logout";
$route['(:any)/save-prediction'] = "index/save_prediction";
$route['(:any)/ranking'] = "index/ranking";
$route['(:any)/get-ajax-ranking'] = "index/get_ajax_ranking";
$route['(:any)/get-ajax-ranking-mobile'] = "index/get_ajax_ranking_mobile";
$route['(:any)/edit-profile'] = "index/edit_profile";
$route['(:any)/trivias'] = "index/trivias";
$route['(:any)/save-trivia-record'] = "index/save_trivia";
$route['(:any)/respuestas-anteriores'] = "index/respuestas_anteriores";
$route['(:any)/change-profile'] = "index/change_profile";
$route['(:any)/accept-login-condition'] = "index/accept_login_condition";
$route['(:any)/premios'] = "index/premios";
$route['(:any)/bases-y-condiciones'] = "index/bases_condiciones";
$route['(:any)/save-guardar'] = "index/save_guardar";










