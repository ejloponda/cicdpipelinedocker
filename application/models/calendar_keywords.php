<?php 
class Calendar_Keywords {
	public static $table_name = "rpc_calendar_keywords";

	public static function save($event,$id) {
		foreach($event as $key=>$value):
			$arr[] = " $key = " . Mapper::safeSql($value);
		endforeach;

		if($id) {
			$sqlstart 	= " UPDATE " . self::$table_name . " SET ";
			$sqlend		= " WHERE event_id = " . Mapper::safeSql($id);
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

	public static function deleteByEvendtId($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE event_id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	function findByEventsId($id){
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE event_id = " . Mapper::safeSql($id) . "
			";
		return Mapper::runActive($sql, true);
	}

	function findByKeywords($params){
		$sql = " 
			SELECT a.id, b.title, b.id FROM " . self::$table_name  . " a
			LEFT JOIN `rpc_calendar_events` b on a.event_id = b.id
			WHERE keywords = " . Mapper::safeSql($params['keywords']) . "
		";
		return Mapper::runActive($sql, true);
	}
}
?>