<?php 
class Orders_Others {

	const ENABLED 	= 1;
	const DISABLED  = 0;

	public static $table_name = "rpc_orders_others";


	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	function findbyOrderId($params) {

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($params['order_id']) . "
		";
		return Mapper::runSql($sql,true,true);
	}

	function findbyOrderId2($params) {

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a
			LEFT JOIN rpc_other_charges b ON a.desc_id = b.id 
			WHERE order_id = " . Mapper::safeSql($params['id']) . "

		";
		return Mapper::runSql($sql,true,true);
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
		//debug_array($sql);
		Mapper::runSql($sql,false);
		if($id) {
			return $id;
		} else {
			return mysql_insert_id();
		}
	}

	public static function delete($params) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
		";
		Mapper::runSql($sql,false);
	}
}

?>