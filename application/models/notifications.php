<?php 
class Notification {

	public static $table_name = "rpc_notification";


	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql, false);
	}

	function findbyReceivedByType($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE received_by = " . Mapper::safeSql($params['received_by']) . " AND type = " . Mapper::safeSql($params['type']) . "
			ORDER BY `date_created` DESC
		";

		return Mapper::runActive($sql, true);
	}

	function findbyReceivedByTypeEventID($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE received_by = " . Mapper::safeSql($params['received_by']) . " AND type = " . Mapper::safeSql($params['type']) . " AND event_id = " . Mapper::safeSql($params['event_id']) . "		
			LIMIT 1
		";

		return Mapper::runActive($sql, false);
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

	public static function generateInventoryDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					medicine_name LIKE  '%" . $q . "%' OR 
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

	public static function countInventoryDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					medicine_name LIKE  '%" . $q . "%' OR 
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