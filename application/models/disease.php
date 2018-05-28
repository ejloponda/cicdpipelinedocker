<?php 
class Disease {

	public static $table_name = "rpc_family_medical_history";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE disease_id = " . Mapper::safeSql($params['disease_id']) . " AND disease_type_id = " . Mapper::safeSql($params['disease_type_id']) . " 
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByIdPatientType($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE disease_id = " . Mapper::safeSql($params['disease_id']) . " AND patient_id = " . Mapper::safeSql($params['patient_id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByIdPatient($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE disease_id = " . Mapper::safeSql($params['disease_id']) . " AND patient_id = " . Mapper::safeSql($params['patient_id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByIdPatientAll($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE disease_id = " . Mapper::safeSql($params['disease_id']) . " AND patient_id = " . Mapper::safeSql($params['patient_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	public static function generateDiseaseDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					email_address LIKE  '%" . $q . "%' OR
					lastname LIKE  '%" . $q . "%' OR
					firstname LIKE  '%" . $q . "%' OR
					middlename LIKE  '%" . $q . "%' OR
					account_type LIKE '%" . $q . "%'
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

	public static function countUserDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					email_address LIKE  '%" . $q . "%' OR
					lastname LIKE  '%" . $q . "%' OR
					firstname LIKE  '%" . $q . "%' OR
					middlename LIKE  '%" . $q . "%'
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

}

?>