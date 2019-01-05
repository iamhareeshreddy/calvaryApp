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
$route['log-out']						= 'login_controller/logout';
$route['dashboard']						= 'calvary_controller';
$route['audio']							= 'audio_controller';
$route['audio/manage-audio-album/(:num)'] = 'audio_controller/manageAudioAlbum/$1';
$route['audio/remove-file'] 			= 'audio_controller/removeFile';
$route['video']							= 'video_controller';
$route['video/manage-video-album/(:num)'] = 'video_controller/manageVideoAlbum/$1';
$route['video/remove-file'] 			= 'video_controller/removeFile';
$route['image']							= 'image_controller';
$route['image/manage-image-album/(:num)'] = 'image_controller/manageImageAlbum/$1';
$route['image/remove-file'] 			= 'image_controller/removeFile';



//Servicess

$route['get-otp']						= 'service/getOtpSample';
$route['validate-otp']					= 'service/validateOtpSample';
$route['check-contacts']				= 'service/checkContacts';
$route['user-registered']				= 'service/userRegistered';
$route['invite-user']					= 'service/inviteUser';
$route['update-user-data']				= 'service/updateUserData';
$route['create-chat-list']				= 'service/createChatList';
$route['get-chats']						= 'service/getChats';
