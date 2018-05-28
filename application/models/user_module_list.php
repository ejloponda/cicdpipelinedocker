<?php 
class User_Module_List {

	const ENABLED 	= 1;
	const DISABLED  = 0;

	public static $table_name = "rpc_user_module_list";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['module_id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql, false);
	}

	function findAllActiveModules($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE status = " . self::ENABLED . "
		";

		return Mapper::runActive($sql, true);
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

	public static function generateModuleScopeListDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					scope LIKE  '%" . $q . "%' OR 
					module LIKE  '%" . $q . "%' 
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

	public static function countModuleScopeListDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					scope LIKE  '%" . $q . "%' OR 
					module LIKE  '%" . $q . "%' 
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

}

?>