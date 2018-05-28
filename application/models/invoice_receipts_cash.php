<?php 
class Invoice_Receipts_Cash {

	public static $table_name = "rpc_invoice_receipts_cash";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findAllByORid($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE or_id = " . Mapper::safeSql($params['or_id']) . "  
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByORid($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE or_id = " . Mapper::safeSql($params['id']) . "  
		";
		return Mapper::runActive($sql, false);
	}

	function findByIdAndORId($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . " AND or_id  = " . Mapper::safeSql($params['or_id']) . " 
			LIMIT 1
		";
		
		return Mapper::runActive($sql);
	}

	function findAllById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE item_id = " . Mapper::safeSql($params['item_id']) . "  
			ORDER BY `id` DESC
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAll() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findAllActive() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE status = 'Active'
		";
		
		return Mapper::runActive($sql, TRUE);
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