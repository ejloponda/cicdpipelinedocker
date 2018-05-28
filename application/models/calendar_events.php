<?php 
class Calendar_Events {
	public static $table_name = "rpc_calendar_events";

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

	public static function deleteBirthdayEvent($params) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . " AND start > " . Mapper::safeSql($params['date']) . "
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

	function findInvitedEventByUserId($params) {
		$sql = " 
			SELECT a.* FROM " . self::$table_name . " a
			LEFT JOIN rpc_calendar_invitees b ON a.id = b.event_id
			WHERE b.user_id = " . Mapper::safeSql($params['user_id']) . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findAllByEventDate($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE (start_db BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByEventName($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE title  LIKE  '%" . $params['title']. "%'
			ORDER BY start_db DESC 
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByCategory($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE category LIKE  '%" . $params['category']. "%'
			ORDER BY start_db DESC
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByPatientName($params){
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE description LIKE '%" . $params['patient'] . "%'
			ORDER BY start_db DESC
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findAllFields($params){
		$q = $params['search'];
		if($q){
		$search = "
			WHERE
				(
					a.title LIKE  '%" . $q . "%' OR
					a.description LIKE  '%" . $q . "%' OR
					a.start_db LIKE  '%" . $q . "%' OR
					a.end_db LIKE  '%" . $q . "%' OR
					b.value LIKE  '%" . $q . "%' OR
					a.category LIKE  '%" . $q . "%' OR
					a.status LIKE  '%" . $q . "%' OR
					a.details LIKE  '%" . $q . "%' OR
					c.value LIKE  '%" . $q . "%' 
				)
			";
		}

		$sql = " 
			SELECT a.id, a.title, a.start_db FROM " . self::$table_name . " a 
			LEFT JOIN rpc_calendar_dropdowns b ON a.type = b.id 
			LEFT JOIN rpc_calendar_dropdowns c ON a.location = c.id 
			{$search}
			ORDER BY a.start_db DESC
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAllbyDateRange($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE (start_db BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id  = " . Mapper::safeSql($params['patient_id']) . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}


}
?>