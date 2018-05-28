<?php 
class Inventory_Batch {

	const ENABLED 	= 1;
	const DISABLED  = 0;

	public static $table_name = "razer_inventory_batch";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		//debug_array($sql);
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

	 function findByMainIdMed($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . "
			
		";
		return Mapper::runActive($sql, true);
	}

	 function findByBatch2($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE batch_no = " . Mapper::safeSql($params['id']) . "
		";
		return Mapper::runActive($sql, false);
	}


	 function findByMed($params) {
	 	/*$field = "batch_no";*/
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . "
			
		";
		return Mapper::runActive($sql, TRUE);
	}

	 function findByBatch($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
			WHERE main_med_id = " . $params['id'] . "
		";
		return Mapper::runSql($sql,true,true);
	}

	public static function countByMainIdMed($params) {

		$sql = " 
			SELECT COUNT(id) as total FROM " . self::$table_name  . "
			WHERE main_med_id =" . Mapper::safeSql($params['id']) . "
		";
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
	}

	function computeTotalQuantity($params){
		$sql = " 
			SELECT SUM(quantity) AS total FROM " . self::$table_name  . " 
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " 
		";
		//debug_array($sql);
		$record = Mapper::runActive($sql);
		return $record['total'];
	}

	function sumTotalQuantity($params){
		$sql = " 
			SELECT SUM(total_quantity) AS total FROM " . self::$table_name  . " 
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " 
		";

 		$record = Mapper::runActive($sql);
 		return $record['total'];
 	}

	function findAllMedicinesByPurchaseDate($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . " 
			WHERE (purchase_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		//debug_array($sql);
		// return $sql;
		//return Mapper::runSql($sql,true,true);
		return Mapper::runActive($sql, true);
	}

	function findAllMedicinesByExpiryDate($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  
			WHERE (expiry_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		return Mapper::runActive($sql, true);
	}

	function findEarlirestMedicineByExpiryDate($params){

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " AND quantity != 0
			ORDER BY expiry_date 
		";
		return Mapper::runActive($sql, false);
	}

	function findEarliestBatch($main_med_id){

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  
			WHERE main_med_id = " . Mapper::safeSql($main_med_id) . " AND quantity <> 0
			ORDER BY expiry_date 
		";
		return Mapper::runActive($sql, true);
	}

	function findEarlirestMedicine($params){

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " AND total_quantity != quantity 
			ORDER BY expiry_date 
		";
		return Mapper::runActive($sql, true);
	}

	function findEarlirestMedicineZero($params){

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " AND 
			total_quantity = quantity
			ORDER BY expiry_date
		";
		return Mapper::runActive($sql, true);
	}

	function findNextEarliestMedicineByExpiryDate($params){
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " AND quantity != 0
			ORDER BY expiry_date LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	function findLatestBatch($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " AND total_quantity != quantity 
			ORDER BY expiry_date LIMIT 1
		";

		// return $sql;
		return Mapper::runActive($sql, false);
	}

	function findLatestEqualBatch($params){

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . " AND 
			total_quantity = quantity
			ORDER BY expiry_date
		";
		return Mapper::runActive($sql, false);
	}

	function findEmptyBatch($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE main_med_id = " . Mapper::safeSql($params['id']) . "
			AND quantity = 0
		";
		return Mapper::runActive($sql, false);
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