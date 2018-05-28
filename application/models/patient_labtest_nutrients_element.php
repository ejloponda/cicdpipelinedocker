<?php 
class Patient_Labtest_Nutrients_Elements {
	public static $table_name = "rpc_labtest_nutrients_elements";

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


	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			ORDER BY date_created ASC
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findByLabtestId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE labtest_id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	public static function deleteByLabtestId($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE labtest_id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

}
?>