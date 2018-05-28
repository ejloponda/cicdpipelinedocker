<?php

class Mapper  extends CI_Model {

	protected $table_name;
	protected $class_suffix = "_model";
	protected $controller_object;
	function __construct($controller_object = '')
	{
		parent::__construct();
		$this->controller_object = $controller_object;
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	}

	/**
	 * Filters/validates the value against sql injection
	 *
	 * @param mixed $value From outside world input
	 * @param bool $detect_numeric If false add single quote to the value
	 * @param bool $allow_wildcards Escape wildcards for SQL injection protection on LIKE, GRANT, and REVOKE commands.
	 * @return mixed Safer value
	 */
	static function safeSql($value, $detect_numeric = true, $allow_wildcards = true)
	{
		// Reverse magic_quotes_gpc/magic_quotes_sybase effects on those vars if ON.
		if (get_magic_quotes_gpc()) {
			if(ini_get('magic_quotes_sybase')) {
			  $value = str_replace("''", "'", $value);      
			} else {
			  $value = stripslashes($value);
			}
		}
		
		//Escape wildcards for SQL injection protection on LIKE, GRANT, and REVOKE commands.
		if (!$allow_wildcards) {
			$value = str_replace('%','\%',$value);
			$value = str_replace('_','\_',$value);
		}
		
		// Quote if $value is a string and detection enabled.
		if ($detect_numeric) {
			if (!is_numeric($value)) {
			  return "'" . mysql_real_escape_string($value) . "'";
			}
		}
		return mysql_real_escape_string($value);
	}
	
	/**
	 * Runs as mysql_query
	 *
	 * @param string $sql Sql command
	 * @param bool $return_data If true returns the data
	 * @return mixed
	 */	
	static function runSql($sql, $return_data = false, $array =  false) {
		if ($return_data) {
			$result = mysql_query($sql);
			if($array) {				
				while ($row = mysql_fetch_assoc($result)) {
					$values[] = $row;
				}
			} else {
				while ($row = mysql_fetch_assoc($result)) {
					$values = $row;
				}
			}
			return $values;
		} else {
			return mysql_query($sql);
		}
	}
	
	static function fetchAssoc($result) {
		return mysql_fetch_assoc($result);
	}
	
	static function freeResult($result) {
		mysql_free_result($result);
	}

	function fetch($data, $is_multiple = false) {

		
		if(!$is_multiple) {
			foreach($data as $a):
				$records = $a;
				break;
			endforeach;
		} else {
			$records = $data;
		}
		return $records;
	}

	function runActive($sql, $is_multiple = FALSE) {

		$query 	= $this->db->query($sql);
		$data 	= $query->result_array();
		
		if(!$is_multiple) {
			foreach($data as $a):
				$records = $a;
				break;
			endforeach;
		} else {
			$records = $data;
		}
		return $records;
	}
}

?>