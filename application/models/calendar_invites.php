<?php 
class Calendar_Invites {
	public static $table_name = "rpc_calendar_invitees";

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

	public static function deleteByEventId($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE event_id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	function findAllInviteesByEventID($params) {
		$sql = " 
			SELECT a.id, a.event_id, a.user_id, a.status, b.firstname, b.lastname FROM " . self::$table_name  . " a
			LEFT JOIN `rpc_user` b on a.user_id = b.id
			WHERE event_id = " . Mapper::safeSql($params['event_id']) . "
		";
		return Mapper::runActive($sql, true);
	}

	function findAllInviteesByUserID($params) {
		$sql = " 
			SELECT a.id, a.event_id, a.user_id, b.start, b.end, b.description, b.start_db, b.end_db, b.title, b.color, b.type, b.category, b.location, b.details, b.status, b.created_by, b.allDay FROM " . self::$table_name  . " a
			LEFT JOIN `rpc_calendar_events` b on a.event_id = b.id
			WHERE user_id = " . Mapper::safeSql($params['user_id']) . "
		";
		return Mapper::runActive($sql, true);
	}

	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findByEventsId($event_id){
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE event_id = " . Mapper::safeSql($event_id) . "
			";
		return Mapper::runActive($sql, TRUE);
	}

	function findByEventIdandUserId($params){
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE event_id = " . Mapper::safeSql($params['event_id']) . " AND 
			user_id = " . Mapper::safeSql($params['user_id']) . "
			";
		return Mapper::runActive($sql, false);
	}

}
?>