<?php 
class Other_Charges {

	public static $table_name = "rpc_other_charges";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";

		return Mapper::runActive($sql);
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
			WHERE status = 'Active' ORDER BY r_centers ASC
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findByName($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "
			WHERE r_centers = " . Mapper::safeSql($params['r_centers']) . "
			LIMIT 1
		";
		// return $sql;
		return Mapper::runActive($sql, false);
	}


	public static function generateOtherChargesDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					r_centers LIKE  '%" . $q . "%' OR
					status LIKE  '%" . $q . "%'
				)
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " 

			{$search}
			ORDER BY `id` DESC
			{$limit}
		";
		return Mapper::runSql($sql,true,true);
	}

	public static function countOtherChargesDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					r_centers LIKE  '%" . $q . "%' OR
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