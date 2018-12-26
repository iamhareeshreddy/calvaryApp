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
$route['default_controller'] = 'login_controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['login']							= 'login_controller/login';
$route['forgot-password']				= 'login_controller/forgotPassword';
$route['password-reset/(:any)']			= 'login_controller/resetPassword/$1';
$route['change-password']				= 'login_controller/changePassword';
$route['logout']						= 'voilet_controller/logout';
$route['dashboard']						= 'voilet_controller';
$route['list-fad']						= 'voilet_controller/listFad';
$route['upload-fad']					= 'voilet_controller/uploadFad';
$route['create-fad']					= 'voilet_controller/createFad';
$route['update-fad/(:any)']				= 'voilet_controller/updateFad';
$route['add-category']					= 'voilet_controller/addCategory';
$route['firebase-settings']				= 'voilet_controller/firebaseSettings';
$route['save-firebase-keys']			= 'voilet_controller/saveFirebaseKeys';
$route['cms-pages']						= 'voilet_controller/cmsPages';
$route['create-cms-page']				= 'voilet_controller/createCmsPage';
$route['delete-cms-page/(:num)']		= 'voilet_controller/deleteCmsPage/$1';
$route['update-cms-page/(:num)']		= 'voilet_controller/updateCmsPage/$1';
$route['advert']						= 'voilet_controller/advert';
$route['delete-advert/(:num)']			= 'voilet_controller/deleteAdvert/$1';
$route['create-advert']					= 'voilet_controller/createAdvert';
$route['upload-advert']					= 'voilet_controller/uploadAdvert';



//Servicess

$route['get-otp']						= 'service/getOtpSample';
$route['validate-otp']					= 'service/validateOtpSample';
$route['check-contacts']				= 'service/checkContacts';
$route['user-registered']				= 'service/userRegistered';
$route['invite-user']					= 'service/inviteUser';
$route['update-user-data']				= 'service/updateUserData';
$route['create-chat-list']				= 'service/createChatList';
$route['get-chats']						= 'service/getChats';
