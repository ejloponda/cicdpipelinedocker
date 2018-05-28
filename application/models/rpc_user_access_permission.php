<?php 
class RPC_User_Access_Permission {

	public static $table_name = "rpc_user_access_permission";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql, false);
	}

	function findAllPermissionByUserId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE user_id = " . Mapper::safeSql($params['user_id']) . "
		";

		return Mapper::runActive($sql, true);
	}

	function findPermissionByIDs($params){
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE user_id = " . Mapper::safeSql($params['user_id']) . " AND user_type_id = " . Mapper::safeSql($params['user_type_id']) . " AND user_module_id = " . Mapper::safeSql($params['user_module_id']) . " 
			LIMIT 1
		";

		return Mapper::runActive($sql, false);
	}

	function findPermissionByUserTypeID($params){
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE user_type_id = " . Mapper::safeSql($params['user_type_id']) . " AND user_module_id = " . Mapper::safeSql($params['user_module_id']) . " 
			LIMIT 1
		";

		return Mapper::runActive($sql, false);
	}

	function findPermissionByUserType($params){
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE user_type_id = " . Mapper::safeSql($params['user_type_id']) . "
		";

		// return $sql;

		return Mapper::runActive($sql, true);
	}

	function findPermissionByUserTypeIDWithModuleName($params){
		$sql = " 
			SELECT a.can_add,a.can_update,a.can_delete,a.can_view,b.module,b.scope,a.id FROM " . self::$table_name  . " a
			LEFT JOIN rpc_user_module_list b ON a.user_module_id = b.id 
			WHERE user_type_id = " . Mapper::safeSql($params['user_type_id']) . "
		";

		// return $sql;

		$result = Mapper::runActive($sql, true);

		foreach ($result as $key => $value) {
			$data['permissions'][$value['module']] = array(
					'id'			=> $value['id'],
					'scope' 		=> $value['scope'],
					'can_add' 		=> $value['can_add'],
					'can_update' 	=> $value['can_update'],
					'can_delete'	=> $value['can_delete'],
					'can_view'		=> $value['can_view']
				);
		}

		return $data;

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