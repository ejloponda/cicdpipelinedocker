<?php 
class Patient_Photos {
	public static $table_name = "rpc_patient_photos";

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
		// debug_array($sql);
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

	public static function deleteByPatientId($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE patient_id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findAllByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAllByPatientIdCategory($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . " AND header_category = " . Mapper::safeSql($params['header_category']) . " 
		";

		return Mapper::runActive($sql, TRUE);
	}

}
?>