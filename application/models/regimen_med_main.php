<?php 
class Regimen_Med_Main{

	public static $table_name = "rpc_regimen_main";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findAllById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}


	function findByRegimenID($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND version_id = 0
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIDVersion($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND version_id = " . Mapper::safeSql($params['version_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByFirstDate($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " ORDER BY start_date
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByLastDate($params){
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " ORDER BY end_date DESC
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findLatestVersion($params){
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . " a
			WHERE a.regimen_id = " . Mapper::safeSql($params['regimen_id']) . " ORDER BY a.id DESC
			LIMIT 1
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenID2($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " ORDER BY id DESC
		";

		return Mapper::runActive($sql);
	}
	

	public static function save($record,$id) {
		foreach($record as $key=>$value):
			$arr[] = " $key = " . Mapper::safeSql($value);
		endforeach;

		if($id) {
			$sqlstart 	= " UPDATE " . self::$table_name . " SET ";
			$sqlend		= " WHERE id = " . Mapper::safeSql($id);
		} else {
			$sqlstart 	=  " INSERT INTO " . self::$table_name . " SET ";
			$sqlend		= "";
		}

		$sqlbody 	= implode($arr," , ");
		$sql 		= $sqlstart.$sqlbody.$sqlend;

		Mapper::runSql($sql,false);
		if($id) {
			return $id;
		} else {
			return mysql_insert_id();
		}
	}

	public static function delete($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	public static function deleteByRegimenID($params) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . "
		";
		Mapper::runSql($sql,false);
	}

	public static function deleteByVersionID($params) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE version_id = " . Mapper::safeSql($params['version_id']) . "
		";
		Mapper::runSql($sql,false);
	}

}

?>