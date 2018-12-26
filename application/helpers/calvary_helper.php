<?php 
if (!function_exists('templetView'))
{
	function templetView($data)
	{
		$CI = get_instance();
		$header 	= isset($data['header']) 	&& $data['header'] 		!= ''? $data['header']:'header';
		$nav_bar 	= isset($data['nav_bar']) 	&& $data['nav_bar'] 	!= ''? $data['nav_bar']:'nav_bar';
		$top_bar 	= isset($data['top_bar']) 	&& $data['top_bar'] 	!= ''? $data['top_bar']:'top_bar';
		$view 		= isset($data['view']) 		&& $data['view'] 		!= ''? $data['view'] : 'index';
		$footer 	= isset($data['footer']) 	&& $data['footer'] 		!= ''? $data['footer']:'footer';
		
		$CI->load->view($header);
		$CI->load->view($nav_bar,$data);
		$CI->load->view($top_bar,$data);
		$CI->load->view(PROJECT_NAME.'_views/'.$view, $data);
		$CI->load->view($footer);
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