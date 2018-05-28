<?php 
class Regimen_Med_List {

	public static $table_name = "rpc_regimen_sub";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findByPatientCode($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_code = " . Mapper::safeSql($params['patient_code']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByRegimenID($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIDMedID($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND medicine_id = " .  Mapper::safeSql($params['medicine_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIDMedIDVersion($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND medicine_id = " .  Mapper::safeSql($params['medicine_id']) . " AND version_id = " . Mapper::safeSql($params['version_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIDMeal($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND meal_type = " . Mapper::safeSql($params['meal_type']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIDMealRow($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND meal_type = " . Mapper::safeSql($params['meal_type']) . " AND row_id = " . Mapper::safeSql($params['row_id']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIDMealRowVersion($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND meal_type = " . Mapper::safeSql($params['meal_type']) . " AND row_id = " . Mapper::safeSql($params['row_id']) . " AND version_id = " . Mapper::safeSql($params['version_id']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIDMealVersion($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND meal_type = " . Mapper::safeSql($params['meal_type']) . " AND version_id = " . Mapper::safeSql($params['version_id']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByRegAndVersionID($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND version_id = " . Mapper::safeSql($params['version_id']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function countByRegAndVersionID($params) {
		$sql = " 
			SELECT count(id) as total FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND version_id = " . Mapper::safeSql($params['version_id']) . " AND taken = 0
		";
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];

		// return $sql;
	}

	function findByRegimenIdRowId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND row_id = " . Mapper::safeSql($params['row_id']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIdRowIdAct($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND row_id = " . Mapper::safeSql($params['row_id']) . " AND meal_type = " . Mapper::safeSql($params['meal_type']) . " AND activity = " . Mapper::safeSql($params['activity']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenIdMealType($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND meal_type = " . Mapper::safeSql($params['meal_type']) . "
		";
		return Mapper::runActive($sql, TRUE);
	}

	function SumByRegimenIdMedId($params){
		// SELECT SUM(Quantity) AS TotalItemsOrdered FROM OrderDetails
		$sql = " 
			SELECT SUM(Quantity) AS Total FROM " . self::$table_name  . " 
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . " AND medicine_id = " . Mapper::safeSql($params['medicine_id']) . "
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

	public static function deleteByRowId($params) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE row_id = " . Mapper::safeSql($params['row_id']) . "
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