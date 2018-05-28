<?php 
class Patients {

	public static $table_name = "rpc_patients";

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

	function findAllByAppointmentDate($params) {
		//  a
		// LEFT JOIN rpc_doctors_list b ON a.doc_assigned_id = b.id
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE (appointment BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findByPatientCode($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_code = " . Mapper::safeSql($params['patient_code']) . "
			LIMIT 1
		";

		return Mapper::runActive($sql);
	}

	public static function generatePatientDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					patient_code LIKE  '%" . $q . "%' OR
					patient_name LIKE  '%" . $q . "%' OR
					appointment LIKE  '%" . $q . "%' OR
					gender LIKE  '%" . $q . "%' OR
					birthdate LIKE '%" . $q . "%' OR
					placeofbirth LIKE '%" . $q . "%'
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

	public static function countPatientDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "

				WHERE
				(
					patient_code LIKE  '%" . $q . "%' OR
					patient_name LIKE  '%" . $q . "%' OR
					appointment LIKE  '%" . $q . "%' OR
					gender LIKE  '%" . $q . "%' OR
					birthdate LIKE '%" . $q . "%' OR
					placeofbirth LIKE '%" . $q . "%'
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

	function countPatientId(){
		$sql = " 
			SELECT COUNT(id) as total FROM " . self::$table_name  . "
				";
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
	}

	function findByPatientName($params){
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_name LIKE '%" . $params['patient'] . "%'
		";
		return Mapper::runActive($sql, TRUE);
	}

	function findAllInactivePatients(){
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE status = '1'
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findAllActivePatients(){
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE status = '0'
		";
		
		return Mapper::runActive($sql, TRUE);
	}

}

?>