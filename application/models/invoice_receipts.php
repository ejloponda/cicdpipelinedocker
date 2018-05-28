<?php 
class Invoice_Receipts {

	public static $table_name = "rpc_invoice_receipts";

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

	function findAllOR($params) {
		$sql = " 
			SELECT or_number FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['invoice_id']) . "  
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findByInvoiceId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['invoice_id']) . "  
		";

		return Mapper::runActive($sql, FALSE);
	}

	function findAllbyDateGenerated($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE (date_receipt BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
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

	public static function generateCollectionsDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
			WHERE
				(
					a.invoice_id LIKE  '%" . $q . "%' OR
					a.amount_paid LIKE  '%" . $q . "%' OR
					a.or_number LIKE  '%" . $q . "%' OR
					a.date_receipt LIKE  '%" . $q . "%' OR
					c.patient_name LIKE  '%" . $q . "%'
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a 
			LEFT JOIN rpc_invoice b ON a.invoice_id = b.id  
			LEFT JOIN rpc_patients c ON b.patient_id = c.id 
			{$search}
			{$order}
			{$limit}
		";

		return Mapper::runSql($sql,true,true);
	}

	public static function countCollectionsDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
			WHERE
				(
					b.rpc_invoice_id LIKE  '%" . $q . "%' OR
					a.amount_paid LIKE  '%" . $q . "%' OR
					a.or_number LIKE  '%" . $q . "%' OR
					a.date_receipt LIKE  '%" . $q . "%'
				)
			";
		}


		$sql = " 
			SELECT COUNT(a.id) as total FROM " . self::$table_name . " a 
			LEFT JOIN rpc_invoice b ON a.invoice_id = b.id 
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

}

?>