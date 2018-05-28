<?php 
class Orders {

	public static $table_name = "rpc_orders";


	function findById($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
		return Mapper::runActive($sql, false);
	}

	function findById2($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "
			AND status != 'Invoiced'
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

	function findByOrderId2() {
		$sql = " 
			SELECT " .field_injector($params['fields']). " FROM " . self::$table_name  . "
			WHERE order_id = " . Mapper::safeSql($params['id']) . " 
		";
		return Mapper::runActive($sql);
	}

	function findByPatientId($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['id']) . "
			LIMIT 1
		";
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

	public static function delete($id) {
		$sql = "
			DELETE FROM " . self::$table_name . "
			WHERE id = " . Mapper::safeSql($id) . "
		";
		Mapper::runSql($sql,false);
	}

	public static function generateOrderID(){
		$sql = " 
			SELECT COUNT(id) as total FROM " . self::$table_name  . "
				";
		$record = Mapper::runSql($sql,true,false);

		$total_record = $record['total'];

		$next_id = ($total_record <= 0 ? 1 : $total_record + 1);
		return $orderCode = "ORDER-" . str_pad($next_id, 4, "0",STR_PAD_LEFT);
	}

	public static function generateOrdersDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
				(
					a.order_no LIKE  '%" . $q . "%' OR
					b.patient_name LIKE  '%" . $q . "%' OR
					a.status LIKE  '%" . $q . "%' OR
					a.date_created LIKE  '%" . $q . "%'
				)
				AND
				
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id
			WHERE
			{$search}
			a.status = 'New' OR a.status = 'Pending'
			{$order}
			{$limit}
			
		";

		return Mapper::runSql($sql,true,true);
	}

	public static function countOrdersDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
				(
					a.order_no LIKE  '%" . $q . "%' OR
					b.patient_name LIKE  '%" . $q . "%' OR
					a.status LIKE  '%" . $q . "%' OR
					a.date_created LIKE  '%" . $q . "%'
				)
				AND
			";
		}


		$sql = " 
			SELECT COUNT(a.id) as total FROM " . self::$table_name . " a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id
			WHERE
			{$search}
			a.status = 'New' OR a.status = 'Pending'
		";
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
	}





}

?>