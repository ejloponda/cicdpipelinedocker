<?php 
class Regimen_Version{

	public static $table_name = "rpc_regimen_version";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByRegimenVersionId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['version_id']) . " AND regimen_id = " . Mapper::safeSql($params['regimen_id']) . "
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
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " ORDER BY id DESC
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findLatest($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " ORDER BY id DESC
		";

		return Mapper::runActive($sql, FALSE);
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
	

	public static function generatePatientDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					patient_code LIKE  '%" . $q . "%' OR
					patient_name LIKE  '%" . $q . "%' OR
					appointment LIKE  '%" . $q . "%' OR
					gender LIKE  '%" . $q . "%' OR
					birthdate LIKE '%" . $q . "%' OR
					placeofbirth LIKE '%" . $q . "%'
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "

			{$search}
			{$order}
			{$limit}
		";
		return Mapper::runSql($sql,true,true);
	}

	public static function countPatientDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					patient_code LIKE  '%" . $q . "%' OR
					patient_name LIKE  '%" . $q . "%' OR
					appointment LIKE  '%" . $q . "%' OR
					gender LIKE  '%" . $q . "%' OR
					birthdate LIKE '%" . $q . "%' OR
					placeofbirth LIKE '%" . $q . "%'
				)
			";
		}


		$sql = " 
			SELECT COUNT(id) as total FROM " . self::$table_name . "
			{$search}
		";
		
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
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

}

?>