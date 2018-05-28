<?php 
class Returns {

	public static $table_name = "rpc_returns";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findAllByInvoiceId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['invoice_id']) . "  
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAllById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE item_id = " . Mapper::safeSql($params['item_id']) . "  
			ORDER BY `id` DESC
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByInvoiceId($params) {
		// $sql = " 
		// 	SELECT * FROM " . self::$table_name  . "
		// 	WHERE invoice_id = " . Mapper::safeSql($params['invoice_id']) . "  
		// ";

		$sql = " 
			SELECT DISTINCT invoice_id FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['invoice_id']) . "  
		";

		return Mapper::runActive($sql, false);
	}

	function findAllByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . " a
			LEFT JOIN rpc_invoice b ON a.invoice_id = b.id
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

	public static function generateReturnsDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
			WHERE
				(
					b.invoice_num LIKE  '%" . $q . "%' OR
					c.patient_name LIKE  '%" . $q . "%' OR
					a.date_return LIKE  '%" . $q . "%' OR
					a.status LIKE  '%" . $q . "%' 
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a 
			LEFT JOIN rpc_invoice b ON a.invoice_id = b.id 
			LEFT JOIN rpc_patients c ON a.patient_id = c.id
			{$search}
			{$order}
			{$limit}
		";
		//debug_array($sql);
		return Mapper::runSql($sql,true,true);
	}

	public static function countReturnsDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
			WHERE
				(
					b.invoice_num LIKE  '%" . $q . "%' OR
					c.patient_name LIKE  '%" . $q . "%' OR
					a.date_return LIKE  '%" . $q . "%' OR
					a.status LIKE  '%" . $q . "%' 
				)
			";
		}

		$sql = " 
			SELECT COUNT(a.id) as total FROM " . self::$table_name . " a 
			LEFT JOIN rpc_invoice b ON a.invoice_id = b.id 
			LEFT JOIN rpc_patients c ON a.patient_id = c.id
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

	function findAllbyDateGenerated($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE (date_return BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		
		return Mapper::runActive($sql, TRUE);
	}

}

?>