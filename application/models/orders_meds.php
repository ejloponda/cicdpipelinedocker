<?php 
class Orders_Meds {

	const ENABLED 	= 1;
	const DISABLED  = 0;

	public static $table_name = "rpc_orders_meds";


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
			LEFT JOIN rpc_inventory b ON a.med_id = b.id 
			LEFT JOIN rpc_dosage_types c ON b.dosage_type = c.id 
			WHERE order_id = " . Mapper::safeSql($params['id']) . "
			
		";
		return Mapper::runActive($sql, true);
	}

	function findbyOrderIdandMedicineID($params) {

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($params['order_id']) . " AND med_id = " . Mapper::safeSql($params['medicine_id']) . "
			LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	function findSumOrderIdandMedicineID($params) {

		$sql = " 
			SELECT SUM(quantity) AS quantity FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($params['order_id']) . " AND med_id = " . Mapper::safeSql($params['medicine_id']) . "
		";
		return Mapper::runActive($sql, false);
	}

	function findAllbyOrderId($params) {

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($params['id']) . "
		";
		return Mapper::runActive($sql, true);
	}

	function findAllbyOrdersAndStock($params) {

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . "
		";
		return Mapper::runActive($sql, true);
	}

	/*function findAllbyOrderIdWithMedTypeRPC($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " 		
			AND b.stock = 'Royal Preventive'
		";		
		return Mapper::runActive($sql, false);
	}

	function findAllbyOrderIdWithMedTypeAlist($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND b.stock = 'A-List'
		";
		return Mapper::runActive($sql, false);
	}

	function findAllbyOrderIdWithNordi($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND b.product_no = '2015-01-003-3003' 
		";
		return Mapper::runActive($sql, false);
	}

	function findAllbyOrderIdWithSci10mg($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND b.product_no = '2015-01-004-3002'
		";
		return Mapper::runActive($sql, false);
	}

	function findAllbyOrderIdWithSci5mg($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND b.product_no = '2015-01-004-3001'
		";
		return Mapper::runActive($sql, false);
	} 

	function findAllbyOrderIdWithNebido($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND b.product_no = '2015-01-003-3001'
		";
		return Mapper::runActive($sql, false);
	}

	function findAllbyOrderIdWithMyoslim($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND b.product_no = '2015-01-005-3001'
		";
		return Mapper::runActive($sql, false);
	}

	function findAllbyOrderIdWithMyosport($params) {	
		$sql = " 
			SELECT SUM(total_price) AS quantity  FROM " . self::$table_name . " a
			LEFT JOIN `rpc_inventory` b on a.med_id = b.id
			WHERE order_id = " . Mapper::safeSql($params['id']) . " AND b.product_no = '2015-01-005-3002'
		";
		return Mapper::runActive($sql, false);
	}*/

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

	public static function delete($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	public static function delete_order_meds($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	public static function deleteOrderMed($params) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE order_id = " . Mapper::safeSql($params['order_id']) . " AND med_id = " . Mapper::safeSql($params['med_id']) . "
		";
		Mapper::runSql($sql,false);
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
}

?>