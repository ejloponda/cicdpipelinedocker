<?php 
class Regimen {

	public static $table_name = "rpc_regimen";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		//debug_array($sql);
		return Mapper::runActive($sql);
	}

	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findAllbyDateGenerated($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE (date_generated BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
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

	function findAllByPatientCode($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_code = " . Mapper::safeSql($params['patient_code']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function generateListOfRegimenHistoryByPatientID($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByRegimenId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE regimen_id = " . Mapper::safeSql($params['regimen_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . "
		";
	//debug_array($sql);
		return Mapper::runActive($sql, TRUE);
	}

	function findByPatientId2($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . "
		";
	//debug_array($sql);
		return Mapper::runActive($sql, false);
	}

	function generateNewRegimenNumber() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			ORDER BY id DESC
			LIMIT 1
		";
		$regimen =  Mapper::runActive($sql);

		// return $regimen;
		$nextId = ($regimen ? (int) $regimen['id'] + 1 : 1);

		return $regimenNumber = "REG-" . str_pad($nextId, 5, "0",STR_PAD_LEFT);
	}

	public static function countByPatientCode($params) {

		$sql = " 
			SELECT COUNT(id) as total FROM " . self::$table_name . "
			WHERE patient_code =  " . Mapper::safeSql($params['patient_code']) . "
		";
		
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
	}
	

	public static function generateRegimenDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					regimen_number LIKE  '%" . $q . "%' OR
					regimen_duration LIKE  '%" . $q . "%' OR
					date_generated LIKE  '%" . $q . "%' OR
					patient_name LIKE  '%" . $q . "%' OR
					date_generated LIKE  '%" . $q . "%' OR
					status LIKE  '%" . $q . "%' 
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

	public static function countRegimenDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					regimen_number LIKE  '%" . $q . "%' OR
					regimen_duration LIKE  '%" . $q . "%' OR
					year LIKE  '%" . $q . "%' OR
					patient_name LIKE  '%" . $q . "%' OR
					date_generated LIKE  '%" . $q . "%' OR
					status LIKE  '%" . $q . "%' 
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

	function findActiveRegimenByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . " AND status = 'Active'
		";
	
		return Mapper::runActive($sql, false);
	}

}

?>