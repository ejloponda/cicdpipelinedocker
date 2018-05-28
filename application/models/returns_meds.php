<?php 
class Returns_Meds {

	public static $table_name = "rpc_returns_meds";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	function findByReturnsId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE returns_id = " . Mapper::safeSql($params['id']) . "  
		";

		return Mapper::runActive($sql, false);
	}

	function findAllByReturnsId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE returns_id = " . Mapper::safeSql($params['returns_id']) . "  
		";

		return Mapper::runActive($sql, TRUE);
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
			WHERE medicine_id = " . Mapper::safeSql($params['item_id']) . "  
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

	public static function generateCollectionsDatatable($params) {

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

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a 
			LEFT JOIN rpc_invoice b ON a.invoice_id = b.id 
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