<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('field_injector'))
{
	function field_injector($params)
	{
		if($params) {
			return implode(' , ', $params);
		} else {
			return "*";
		}
	}
}


if ( ! function_exists('debug_array'))
{
	function debug_array($array) {
		echo '<pre>';
		print_r($array);
		exit;
	}
}
