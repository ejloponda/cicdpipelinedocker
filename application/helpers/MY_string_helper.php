<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('limit_character'))
{
	function limit_character($string, $limit, $break = ".", $pad = "...")
	{
		$string = strip_tags($string);	
		// return with no change if string is shorter than $limit
		if(strlen($string) <= $limit) return $string;

		// is $break present between $limit and the end of the string?
		if(false !== ($breakpoint = strpos($string, $break, $limit))) {
			if($breakpoint < strlen($string) - 1) {
			  $string = substr($string, 0, $breakpoint) . $pad;
			}
		}

		return $string;
	}
}

if ( ! function_exists('convert_word'))
{
	function convert_word($string){
		$string = mb_convert_encoding($string,'ISO-8859-15','utf-8');
		return $string;
	}
}