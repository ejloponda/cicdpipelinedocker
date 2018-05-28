<?php 
class Stock {
 
	public static $table_name = "rpc_stock_history";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByBatch($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . "   
			AND item_id = ". Mapper::safeSql($params['item_id']) ."
			AND taken = 1
		";
		//debug_array($sql);
		return Mapper::runActive($sql, true);
	}

	function findAllById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE item_id = " . Mapper::safeSql($params['item_id']) . "  
			ORDER BY `id` DESC
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAllByPatientId($params) {
		$sql = " 
			SELECT a.id,a.patient_id,a.reason,a.item_id,a.quantity,b.medicine_name,a.created_at FROM " . self::$table_name  . " a
			LEFT JOIN `rpc_inventory` b ON a.item_id = b.id
			WHERE a.patient_id = " . Mapper::safeSql($params['patient_id']) . "  
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


	public static function generateDoctorsDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					full_name LIKE  '%" . $q . "%' OR
					status LIKE  '%" . $q . "%'
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . "

			{$search}
			{$order}
			{$limit}
		";
		return Mapper::runSql($sql,true,true);
	}

	public static function countDoctorsDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					full_name LIKE  '%" . $q . "%' OR
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

}

?>