<?php 
class User_Roles {

	public static $table_name = "rpc_user_roles";

	function findRoleById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findRoleName($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE role_name = " . Mapper::safeSql($params['role_name']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	public static function delete($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}


	public static function verifyUserAccess($account_type, $page, $exception) {
		

		if($account_type == SUPER_ADMIN) {
			return true;
		} else if($account_type == COORDINATOR) {
			$array = array(
				"billing_management" => array(
					"view" => false,
				),
				"delivery_management" => array(
					"view" => true,
				),
				"employee_management" => array(
					"view" => false,
				),
				"payroll" => array(
					"view" => false,
				),
			);
		} else if($account_type == CENTRAL_DISPATCHER) {
			$array = array(
				"billing_management" => array(
					"view" => false,
				),
				"delivery_management" => array(
					"view" => true,
				),
				"employee_management" => array(
					"view" => false,
				),
				"payroll" => array(
					"view" => false,
				),
				"scan_receipt_form" => array(
					"view" => false,
				),
			);
		} else if($account_type == GUARD) {
			$array = array(
				"billing_management" => array(
					"view" => false,
				),
				"delivery_management" => array(
					"view" => true,
				),
				"employee_management" => array(
					"view" => false,
				),
				"payroll" => array(
					"view" => false,
				),
				"scan_delivery_plan" => array(
					"view" => true,
				),
			);
		} else if($account_type == BILLING) {
			$array = array(
				"billing_management" => array(
					"view" => true,
				),
				"delivery_management" => array(
					"view" => false,
				),
				"employee_management" => array(
					"view" => false,
				),
				"payroll" => array(
					"view" => false,
				),
				"scan_receipt_form" => array(
					"view" => false,
				),
			);
		} else if($account_type == PAYROLL) {
			$array = array(
				"billing_management" => array(
					"view" => false,
				),
				"delivery_management" => array(
					"view" => false,
				),
				"employee_management" => array(
					"view" => false,
				),
				"payroll" => array(
					"view" => true,
				),
				"scan_receipt_form" => array(
					"view" => false,
				),
			);
		}

		if($array[$page]['view'] == true) {
			return true;
		} else {
			die(show_error("Oops! You don't have permission to access this page. Please contact web administrator!",404));
		}

	}

	public static function generateUserRolesDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					role_name LIKE  '%" . $q . "%'
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

	public static function countUserRolesDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					role_name LIKE  '%" . $q . "%'
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

}

?>