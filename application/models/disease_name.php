<?php 
class Disease_Name {

	public static $table_name = "rpc_disease";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByName($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE disease_name = " . Mapper::safeSql($params['disease_name']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function getAllActiveCategoryData($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE header_category = " .  Mapper::safeSql($params['category']) . " AND status = " .  Mapper::safeSql($params['status']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function getAllCategoryData($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE header_category = " .  Mapper::safeSql($params['category']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function getAllbyId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " .  Mapper::safeSql($params['id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}


	function getAllData() {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByIdPatientType($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE disease_id = " . Mapper::safeSql($params['disease_id']) . " AND disease_type_id = " . Mapper::safeSql($params['disease_type_id']) . " AND patient_id = " . Mapper::safeSql($params['patient_id']) . "
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
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
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

	function findByEmailAddress($email_address, $fields) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name . "
			WHERE email_address = " . Mapper::safeSql($email_address) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findDuplicateEmail($user_id,$email_address) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE
				id != " . Mapper::safeSql($user_id) . " AND
				email_address = " . Mapper::safeSql($email_address) . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findByUsername($username) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE username = " . Mapper::safeSql($username) . "
			LIMIT 1
		";
		
		return Mapper::runActive($sql);
	}

	function findActiveUserByUsername($username="") {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE 
				username = " . Mapper::safeSql($username) . " AND
				account_status = 'Active'
				LIMIT 1
		";
		
		return Mapper::runActive($sql);
	}

	function findActiveUserByUsernameOrEmail($username="", $fields) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name . "
			WHERE
				(
					username = " . Mapper::safeSql($username) . " OR
					email_address = " . Mapper::safeSql($username) . "
				) AND
				account_status = 'Active'
				LIMIT 1
		";
		
		return Mapper::runActive($sql);
	}

	function findAllActiveUser($fields, $order ="", $limit = "") {
		$order = ($order == "" ? "" : " ORDER BY {$order}");
		$limit = ($limit == "" ? "" : " LIMIT {$limit}");
		
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name . "
			WHERE 
				account_status = 'Active'

			{$order}
			{$limit}
		";

		return Mapper::runActive($sql, TRUE);
	}

	public static function findAllActiveUserByFirmId($firm_id, $fields, $order ="", $limit = "") {
		$order = ($order == "" ? "" : " ORDER BY {$order}");
		$limit = ($limit == "" ? "" : " LIMIT {$limit}");

		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name . "
			WHERE
				firm_id = " . Mapper::safeSql($firm_id) . " AND
				account_status = 'Active'

			{$order}
			{$limit}
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	public static function findAllActiveOrdinaryUsersByFirmId($firm_id, $fields, $order ="", $limit = "") {
		$order = ($order == "" ? "" : " ORDER BY {$order}");
		$limit = ($limit == "" ? "" : " LIMIT {$limit}");
		
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name . "
			WHERE
				firm_id = " . Mapper::safeSql($firm_id) . " AND
				account_status = 'Active' AND
				account_type != " . Mapper::safeSql(SUPER_ADMIN) . "
			{$order}
			{$limit}
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function validate_duplicate_username($username, $employee_id) {
		if($username) {

			if($user_id) {
				$sql = " 
					SELECT id FROM " . self::$table_name . "
					WHERE 

					(
						employee_id != " . Mapper::safeSql($employee_id) . " AND
						username = " . Mapper::safeSql($username) . "
					)
					LIMIT 1
				";
			} else {
				$sql = " 
					SELECT id FROM " . self::$table_name . "
					WHERE 
					(
						username = " . Mapper::safeSql($username) . "
					)
					LIMIT 1
				";
			}
		
			$record = Mapper::runActive($sql);

			return ($record ? true : false);

		} else {
			return false;
		}
	}


	public static function generateDiseaseDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					disease_name LIKE  '%" . $q . "%' OR
					header_category LIKE  '%" . $q . "%' OR
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

	public static function countDiseaseDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					disease_name LIKE  '%" . $q . "%' OR
					header_category LIKE  '%" . $q . "%' OR
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

}

?>