<?php 
if (!function_exists('templetView'))
{
	function templetView($data)
	{
		$CI = get_instance();
		$header 	= isset($data['header']) 	&& $data['header'] 		!= ''?$data['header']:'header';
		$view 		= isset($data['view']) 		&& $data['view'] 		!= ''? $data['view'] : 'index';
		$footer 	= isset($data['footer']) 	&& $data['footer'] 		!= ''?$data['footer']:'footer';
		$navgation 	= isset($data['navgation']) && $data['navgation'] 	!= ''?$data['navgation']:'navgation';
		
		$CI->load->view($header);
		$CI->load->view($navgation,$data);
		$CI->load->view('voilet_views/'.$view, $data);
		$CI->load->view($footer);
	}
}
if (!function_exists('set_phone_number_length'))
{
	function set_phone_number_length($stringLength=10)
	{
		define("PHONE_NUMBER_LENGTH", $stringLength);
	}
}
if (!function_exists('set_default_code'))
{
	function set_default_code($code)
	{
		//$code = '+'.preg_replace("/[^0-9]/",'', $code);
		define("DEFAULT_CODE", $code);
	}
}
if (!function_exists('trim_number'))
{
	function trim_number($number)
	{
		return ltrim($number, '0');
	}
}
if (!function_exists('remove_zero'))
{
	function remove_zero($number)
	{
		$phone_number = trim_number(str_replace(DEFAULT_CODE, '', $number));
        return DEFAULT_CODE.$phone_number;
	}
}

if (!function_exists('random_string'))
{
	function random_string($stringLength=10)
	{
		$name = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$length = strlen($name);$string = '';
		for($i=0; $i<$stringLength; $i++)
		{
			$string .= $name[rand(0, $length-1)];
		}
		return $string;
	}
}
?>