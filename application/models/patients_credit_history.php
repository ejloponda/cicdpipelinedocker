<?php 
class Patients_Credit_History {

	public static $table_name = "rpc_patient_credit_history";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findAllByPatientid($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . " ORDER BY id DESC
		";

		return Mapper::runActive($sql, TRUE);
	}


	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
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

}

?>