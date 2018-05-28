<?php 
class Reserved_Meds{

	const ENABLED 	= 1;
	const DISABLED  = 0;

	public static $table_name = "rpc_reserved_meds";


	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE med_id = " . Mapper::safeSql($params['id']) . "
		";
		return Mapper::runActive($sql, false);
	}

	function findSumReserved($params) {
		$sql = " 
			SELECT SUM(quantity) as total_reserved FROM " . self::$table_name  . "
			WHERE med_id = " . Mapper::safeSql($params['id']) . " AND taken = 0  AND date_created >= '2015-10-15'
		";
		$record = Mapper::runActive($sql);
		return $record['total_reserved'];
	}

	function findSumClaim($params) {
		$sql = " 
			SELECT SUM(quantity) as total_reserved FROM " . self::$table_name  . "
			WHERE med_id = " . Mapper::safeSql($params['id']) . " AND taken = 1  AND date_created >= '2015-10-15'
		";
		$record = Mapper::runActive($sql);
		return $record['total_reserved'];
	}

	function findById2($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE med_id = " . Mapper::safeSql($params['id']) . " 
			AND quantity = " . Mapper::safeSql($params['quantity']) . "
			AND taken = 1
		";
		return Mapper::runActive($sql, false);
	}

	function findAllByOrderId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE order_id = " . Mapper::safeSql($params['id']) . "
		";
		
		return Mapper::runActive($sql, true);
	}

	function countBatchId($params) {
		$sql = " 
			SELECT count(med_id) as total_batch FROM " . self::$table_name  . "
			WHERE order_id = " . Mapper::safeSql($params['order_id']) . " AND med_id = " . Mapper::safeSql($params['med_id']) . "
		";
		$record = Mapper::runActive($sql);
		return $record['total_batch'];
	}

	function findOrderId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE order_id = " . Mapper::safeSql($params['id']) . "
		";
		return Mapper::runActive($sql, false);
	}


	function findTotalQuantity($params){
		$sql = " 
			SELECT med_id, SUM(quantity) AS total_quantity,
			SUM(price) AS price,
			SUM(total_price) AS total_price
			FROM " . self::$table_name  . " 
			WHERE order_id = " . Mapper::safeSql($params['order_id']) . " AND med_id = " . Mapper::safeSql($params['med_id']) . "
		";
	
		$record = Mapper::runActive($sql);
		return $record;
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

	public static function delete_reserved($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	public static function deleteReserveMed($params) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($params['order_id']) . " AND med_id = " . Mapper::safeSql($params['med_id']) . "
		";
		Mapper::runSql($sql,false);
	}

	function findAllByOrderId2($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND taken = 2
		";
		
		return Mapper::runActive($sql, true);
	}
}
?>