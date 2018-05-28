<?php 
class User {

	public static $table_name = "rpc_user";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByEmployeeId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
			WHERE employee_id = " . Mapper::safeSql($params['employee_id']) . "
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

	function findByUsernameExceptUserID($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE username = " . Mapper::safeSql($params['username']) . " AND 
			id != " . Mapper::safeSql($params['user_id']) . "
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


	public static function generateUserDatatable($params) {

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

	function findPharmaAcctMgt($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE account_type = 'Super Admin' || account_type = 'Accounting Manager' || account_type = 'Pharma Sup'
		";
		return Mapper::runActive($sql, TRUE);
	}
//account_type = 'Super Admin' || account_type = 'Accounting Manager' || account_type = 'Pharma Sup'
	//account_type = 'test'
}

?>