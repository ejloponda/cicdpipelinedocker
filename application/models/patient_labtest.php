<?php 
class Patient_Labtest {
	public static $table_name = "rpc_labtest";

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

	function findAllUrinalysis() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			where category = 'urinalysis'
			ORDER BY date_created ASC
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findAllByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAllByPatientIdAndLabtest($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . " AND type_of_test = '1'
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAllImagingTestByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . " AND type_of_test = '0'
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	public static function delete($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	function findAllByPatientIdCategory($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . " AND category = " . Mapper::safeSql($params['category']) . "
			ORDER BY date_created DESC
		";
		

		return Mapper::runActive($sql, TRUE);
	}
}
?>