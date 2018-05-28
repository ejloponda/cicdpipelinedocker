<?php 
class Calendar_Dropdown {
	public static $table_name = "rpc_calendar_dropdowns";

	public static function save($event,$id) {
		foreach($event as $key=>$value):
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
		#debug_array($sql);
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

	function findByType($type){
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE type = " . Mapper::safeSql($type) . "
			ORDER BY id ASC
			";
		return Mapper::runActive($sql, TRUE);
	}

	function findByEventType($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($params['id']) . " AND 
			type = 'type'
			";
		return Mapper::runActive($sql);
	}

	function findByLocation($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($params['id']) . " AND 
			type = 'location'
			";
		return Mapper::runActive($sql);
	}

	public static function generateDropdownDatatable($params) {

		if($params['search']) {
			$q = $params['search'];

			$search = "

				WHERE
				(
					type = 'location' AND
					value LIKE  '%" . $q . "%' OR
					color LIKE  '%" . $q . "%' OR
					status LIKE  '%" . $q . "%'
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
			WHERE type = 'location'
			{$search}
			{$order}
			{$limit}
		";
		
		return Mapper::runSql($sql,true,true);
	}

	public static function countLocationDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					type = 'location'
					value LIKE  '%" . $q . "%' OR
					color LIKE  '%" . $q . "%' OR
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

	public static function generateTypeDatatable($params) {

		if($params['search']) {
			$q = $params['search'];

			$search = "

				WHERE
				(
					type = 'location' AND
					value LIKE  '%" . $q . "%' OR
					color LIKE  '%" . $q . "%' OR
					status LIKE  '%" . $q . "%'
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
			WHERE type = 'type'
			{$search}
			{$order}
			{$limit}
		";
		
		return Mapper::runSql($sql,true,true);
	}

}
?>