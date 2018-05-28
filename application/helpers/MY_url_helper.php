<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('get_base_url')) {
	function get_base_url()
	{
		return 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER;
	}
}

if ( ! function_exists('url')) {
	function url($params, $get = NULL) {
		return get_base_url()  . $params;
	}
}

if ( ! function_exists('redirect')) {
	function redirect($params) {
		header("Location:" . get_base_url() . $params);
	}
}
