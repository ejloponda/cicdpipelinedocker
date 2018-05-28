<?php 
class Inventory {

	const ENABLED 	= 1;
	const DISABLED  = 0;

	public static $table_name = "rpc_inventory";


	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	function findByName($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE medicine_name = " . Mapper::safeSql($params['medicine_name']) . "
			LIMIT 1
		";
		// return $sql;
		return Mapper::runActive($sql, false);
	}

	function findByNameandMedId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE medicine_name = " . Mapper::safeSql($params['medicine_name']) . " and id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	function findAllMedicines($params) {
		$fields = array("a.id", "a.product_no", "a.medicine_name", "a.generic_name", "a.dosage", "a.dosage_type", "a.total_quantity", "a.quantity_type", "a.stock", "a.stock_price", "a.price", "a.cost_sales", "b.abbreviation", "c.abbreviation","a.expired_damage_med");
		$sql = " 
			SELECT a.id, a.product_no, a.status, a.medicine_name, a.expired_damage_med, a.qty_per_bottle, a.generic_name, a.dosage,a.dosage_type, a.total_quantity,a.quantity_type,a.stock,a.stock_price,a.price,a.cost_sales,b.abbreviation as quantity_abbr, c.abbreviation as dosage_abbr
			FROM " . self::$table_name  . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id
		";
		//debug_array($sql);
		// return $sql;
		return Mapper::runActive($sql, true);
	}

	function findAllActiveMedicines($params) {
		$fields = array("a.id", "a.product_no", "a.medicine_name", "a.generic_name", "a.dosage", "a.dosage_type", "a.total_quantity", "a.quantity_type", "a.stock", "a.stock_price", "a.price", "a.cost_sales", "b.abbreviation", "c.abbreviation");
		$sql = " 
			SELECT a.id, a.product_no, a.status, a.medicine_name, a.generic_name, a.dosage,a.dosage_type, a.total_quantity,a.quantity_type,a.stock,a.stock_price,a.price,a.cost_sales,b.abbreviation as quantity_abbr, c.abbreviation as dosage_abbr
			FROM " . self::$table_name  . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id WHERE a.status = 'Active'
		";
		
		return Mapper::runActive($sql, true);
	}

	function findAllActiveRPCAlistMedicines($params) {
		$fields = array("a.id", "a.product_no", "a.medicine_name", "a.generic_name", "a.dosage", "a.dosage_type", "a.total_quantity", "a.quantity_type", "a.stock", "a.stock_price", "a.price", "a.cost_sales", "b.abbreviation", "c.abbreviation");
		$sql = " 
			SELECT a.id, a.product_no, a.status, a.medicine_name, a.generic_name, a.dosage,a.dosage_type, a.total_quantity,a.quantity_type,a.stock,a.stock_price,a.price,a.cost_sales,b.abbreviation as quantity_abbr, c.abbreviation as dosage_abbr
			FROM " . self::$table_name  . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id WHERE a.status = 'Active' AND a.stock != 'RPP'
		";
		return Mapper::runActive($sql, true);
	}

	function findAllActiveRPPMedicines($params) {
		$fields = array("a.id", "a.product_no", "a.medicine_name", "a.generic_name", "a.dosage", "a.dosage_type", "a.total_quantity", "a.quantity_type", "a.stock", "a.stock_price", "a.price", "a.cost_sales", "b.abbreviation", "c.abbreviation");
		$sql = " 
			SELECT a.id, a.product_no, a.status, a.medicine_name, a.generic_name, a.dosage,a.dosage_type, a.total_quantity,a.quantity_type,a.stock,a.stock_price,a.price,a.cost_sales,b.abbreviation as quantity_abbr, c.abbreviation as dosage_abbr
			FROM " . self::$table_name  . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id WHERE a.status = 'Active' AND a.stock = 'RPP'
		";
		return Mapper::runActive($sql, true);
	}

	/*function findAllMedicinesByPurchaseDate($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . " a 
			WHERE (purchase_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		// return $sql;
		return Mapper::runActive($sql, true);
	}

	function findAllMedicinesByExpiryDate($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . " a 
			WHERE (expiry_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		// return $sql;
		return Mapper::runActive($sql, true);
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

	public static function generateInventoryDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					a.medicine_name LIKE  '%" . $q . "%' OR 
					a.product_no LIKE  '%" . $q . "%' OR 
					a.generic_name LIKE  '%" . $q . "%' OR 
					a.dosage LIKE  '%" . $q . "%' OR 
					a.total_quantity LIKE  '%" . $q . "%' OR 
					b.abbreviation LIKE  '%" . $q . "%' OR
					c.abbreviation LIKE  '%" . $q . "%'
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id
			{$search}
			{$order}
			{$limit}
		";
		//debug_array($sql);
		return Mapper::runSql($sql,true,true);
	}

	public static function countInventoryDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					a.medicine_name LIKE  '%" . $q . "%' OR 
					a.product_no LIKE  '%" . $q . "%' OR 
					a.generic_name LIKE  '%" . $q . "%' OR 
					a.dosage LIKE  '%" . $q . "%' OR 
					a.remaining LIKE  '%" . $q . "%' OR 
					a.stock LIKE  '%" . $q . "%' OR 
					a.purchase_date LIKE  '%" . $q . "%' OR 
					a.expiry_date LIKE  '%" . $q . "%' OR
					b.abbreviation LIKE  '%" . $q . "%' OR
					c.abbreviation LIKE  '%" . $q . "%'
				)
			";
		}


		$sql = " 
			SELECT COUNT(a.id) as total FROM " . self::$table_name . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id
			{$search}
		";
		
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
	}

	function findAll($params) {
		$fields = array("a.id", "a.product_no", "a.medicine_name", "a.generic_name", "a.dosage", "a.dosage_type", "a.total_quantity", "a.quantity_type", "a.stock", "a.stock_price", "a.price", "a.cost_sales", "b.abbreviation", "c.abbreviation","a.qty_per_bottle");
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);
		$sql = " 
			SELECT a.id, a.product_no, a.status, a.medicine_name, a.generic_name, a.dosage,a.dosage_type, a.total_quantity,a.quantity_type,a.stock,a.stock_price,a.price,a.cost_sales,b.abbreviation as abbr1, c.abbreviation as abbr2, a.qty_per_bottle
			FROM " . self::$table_name  . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id WHERE a.status = 'Active'
			ORDER BY a.total_quantity DESC
			

		";
		
		return Mapper::runSql($sql,true,true);
	}

	function countAll($params) {
		$fields = array("a.id", "a.product_no", "a.medicine_name", "a.generic_name", "a.dosage", "a.dosage_type", "a.total_quantity", "a.quantity_type", "a.stock", "a.stock_price", "a.price", "a.cost_sales", "b.abbreviation", "c.abbreviation","a.qty_per_bottle");
		$sql = " 
			SELECT COUNT(a.id) as total
			FROM " . self::$table_name  . " a 
			LEFT JOIN rpc_quantity_types b ON a.quantity_type = b.id 
			LEFT JOIN rpc_dosage_types c ON a.dosage_type = c.id WHERE a.status = 'Active'
			ORDER BY a.total_quantity DESC
		";
		
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
	}
}

?>