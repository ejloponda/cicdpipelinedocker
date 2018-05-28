<?php 
class Invoice_AList {

	public static $table_name = "rpc_invoice_alist";

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
			WHERE status != 'Void'
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findAllPaid() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			WHERE status = 'Paid'
		";
		
		return Mapper::runActive($sql, TRUE);
	}

	function findAllWithVoid() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
		";
		
		return Mapper::runActive($sql, TRUE);
	}



	function findDateRange($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a
			LEFT JOIN `rpc_patients` b on a.patient_id = b.id
			WHERE a.invoice_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . "
		";
		
		// return $sql;
		return Mapper::runActive($sql, TRUE);
	}


	function findDateRangeAR($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a
			LEFT JOIN `rpc_patients` b on a.patient_id = b.id
			WHERE (a.invoice_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ") AND 
			status = 'New' OR status = 'Pending' OR status = 'Partial'
		";
		
		// return $sql;
		return Mapper::runActive($sql, TRUE);
	}

	function findAllByPatient($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE patient_id = " . Mapper::safeSql($params['patient_id']) . "
		";

		return Mapper::runActive($sql, TRUE);
	}

	function findAllByReceivableStatus() {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE status = 'Partial' OR status = 'New' OR status = 'Pending'
		";

		return Mapper::runActive($sql, TRUE);
	}

	function generateNewRPCInvoiceID() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			ORDER BY id DESC
			LIMIT 1
		";
		$invoice =  Mapper::runActive($sql);
		$nextId = ($invoice ? (int) $invoice['id'] + 1 : 1);

		return $invoiceNumber = "INV-" . str_pad($nextId, 5, "0",STR_PAD_LEFT);
	}

	function generateNewRPCInvoiceNumber() {
		$sql = " 
			SELECT * FROM " . self::$table_name . "
			ORDER BY id DESC
			LIMIT 1
		";
		$invoice =  Mapper::runActive($sql);
		$nextId = ($invoice ? (int) $invoice['id'] + 1 : 1);

		return $invoiceNumber = str_pad($nextId, 5, "0",STR_PAD_LEFT);
	}
	

	public static function generateSalesReportDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			if(!is_array($q)){
				$q = $params['search'];
				$search = "

					WHERE
					(
						a.rpc_invoice_num LIKE  '%" . $q . "%' OR
						a.rpc_invoice_id LIKE  '%" . $q . "%' OR
						c.full_name LIKE  '%" . $q . "%' OR
						b.patient_name LIKE  '%" . $q . "%' OR
						b.tin LIKE  '%" . $q . "%' OR
						a.invoice_date LIKE  '%" . $q . "%' OR
						a.due_date LIKE  '%" . $q . "%' OR
						a.aging LIKE  '%" . $q . "%' OR
						a.total_gross_sales LIKE  '%" . $q . "%' OR
						a.cost_modifier LIKE  '%" . $q . "%' OR
						a.net_sales LIKE  '%" . $q . "%' OR
						a.net_sales_vat LIKE  '%" . $q . "%' OR
						a.total_net_sales_vat LIKE  '%" . $q . "%' OR
						a.status LIKE  '%" . $q . "%' 
					)
				";
			} else {
				$q 	  = $params['search']['filter_to_search'];
				$type = $params['search']['filter_date'];


				if($type == "Day"){
					$date_year  = date('Y');
					$date_month = date('m');
					$date_today = date('Y-m-d');

					$search = "
						WHERE
						(
							a.invoice_date = '" . $date_year . "-" . $date_month . "-" . $q ."'
						)

					";
				} else if($type== "Month"){
					if(!is_numeric($q)){
						$arr_month = array(
									'January' => '01',
									'February' => '02',
									'March' => '03',
									'April' => '04',
									'May' => '05',
									'June' => '06',
									'July' => '07',
									'August' => '08',
									'September' => '09',
									'October' => 10,
									'November' => 11,
									'December' => 12
								);
						foreach($arr_month as $k => $v) {$arr_month[substr($k,0,3)] = $v;}
						$q = $arr_month[ucfirst($q)];
					}
					$date_year  = date('Y');
					$search = "
						WHERE
						(
							a.invoice_date between '" . $date_year . "-" . $q ."-01' AND 
							'" . $date_year . "-" . $q ."-31'
						)

					";
				} else {
					$search = "
						WHERE
						(
							a.invoice_date between '" . $q . "-01-01' AND 
							'" . $q . "-12-31'
						)

					";
				}
			}
			
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			{$search}
			{$order}
			{$limit}
		";
		// return $sql;
		return Mapper::runSql($sql,true,true);
	}

	public static function countSalesReportDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			if(!is_array($q)){
				$q = $params['search'];
				$search = "

					WHERE
					(
						a.rpc_invoice_num LIKE  '%" . $q . "%' OR
						a.rpc_invoice_id LIKE  '%" . $q . "%' OR
						c.full_name LIKE  '%" . $q . "%' OR
						b.patient_name LIKE  '%" . $q . "%' OR
						b.tin LIKE  '%" . $q . "%' OR
						a.invoice_date LIKE  '%" . $q . "%' OR
						a.due_date LIKE  '%" . $q . "%' OR
						a.aging LIKE  '%" . $q . "%' OR
						a.total_gross_sales LIKE  '%" . $q . "%' OR
						a.cost_modifier LIKE  '%" . $q . "%' OR
						a.net_sales LIKE  '%" . $q . "%' OR
						a.net_sales_vat LIKE  '%" . $q . "%' OR
						a.total_net_sales_vat LIKE  '%" . $q . "%' OR
						a.status LIKE  '%" . $q . "%' 
					)
				";
			} else {
				$q 	  = $params['search']['filter_to_search'];
				$type = $params['search']['filter_date'];


				if($type == "Day"){
					$date_year  = date('Y');
					$date_month = date('m');
					$date_today = date('Y-m-d');

					$search = "
						WHERE
						(
							a.invoice_date = '" . $date_year . "-" . $date_month . "-" . $q ."'
						)

					";
				} else if($type== "Month"){
					if(!is_numeric($q)){
						$arr_month = array(
									'January' => '01',
									'February' => '02',
									'March' => '03',
									'April' => '04',
									'May' => '05',
									'June' => '06',
									'July' => '07',
									'August' => '08',
									'September' => '09',
									'October' => 10,
									'November' => 11,
									'December' => 12
								);
						foreach($arr_month as $k => $v) {$arr_month[substr($k,0,3)] = $v;}
						$q = $arr_month[ucfirst($q)];
					}
					$date_year  = date('Y');
					$search = "
						WHERE
						(
							a.invoice_date between '" . $date_year . "-" . $q ."-01' AND 
							'" . $date_year . "-" . $q ."-31'
						)

					";
				} else {
					$search = "
						WHERE
						(
							a.invoice_date between '" . $q . "-01-01' AND 
							'" . $q . "-12-31'
						)

					";
				}
			}
			
		}

		$sql = " 
			SELECT COUNT(a.id) as total FROM " . self::$table_name . " a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			{$search}
		";
		
		$record = Mapper::runSql($sql,true,false);
		return $record['total'];
	}

	public static function generateAccountsReceivablesDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
				(
					a.rpc_invoice_num LIKE  '%" . $q . "%' OR
					a.rpc_invoice_id LIKE  '%" . $q . "%' OR
					c.full_name LIKE  '%" . $q . "%' OR
					b.patient_name LIKE  '%" . $q . "%' OR
					b.tin LIKE  '%" . $q . "%' OR
					a.invoice_date LIKE  '%" . $q . "%' OR
					a.due_date LIKE  '%" . $q . "%' OR
					a.aging LIKE  '%" . $q . "%' OR
					a.total_net_sales_vat LIKE  '%" . $q . "%' OR
					a.status LIKE  '%" . $q . "%' 
				)
 				AND
			";
		}

		$order = ($params['order'] == "" ? "" : " ORDER BY " . $params['order']);
		$limit = ($params['limit'] == "" ? "" : " LIMIT " . $params['limit']);

		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name . " a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			WHERE
			{$search}
			a.status = 'New' OR a.status = 'Pending' OR a.status = 'Partial'
			{$order}
			{$limit}
		";
		return Mapper::runSql($sql,true,true);
	}

	public static function countAccountsReceivablesDatatable($params) {

		if($params['search']) {
			$q = $params['search'];
			$search = "
				(
					a.rpc_invoice_num LIKE  '%" . $q . "%' OR
					a.rpc_invoice_id LIKE  '%" . $q . "%' OR
					c.full_name LIKE  '%" . $q . "%' OR
					b.patient_name LIKE  '%" . $q . "%' OR
					b.tin LIKE  '%" . $q . "%' OR
					a.invoice_date LIKE  '%" . $q . "%' OR
					a.due_date LIKE  '%" . $q . "%' OR
					a.aging LIKE  '%" . $q . "%' OR
					a.total_net_sales_vat LIKE  '%" . $q . "%' OR
					a.status LIKE  '%" . $q . "%' 
				)
 				AND
			";
		}


		$sql = " 
			SELECT COUNT(a.id) as total FROM " . self::$table_name . " a 
			LEFT JOIN rpc_patients b ON a.patient_id = b.id 
			LEFT JOIN rpc_doctors_list c ON b.doc_assigned_id = c.id
			WHERE
			{$search}
			a.status = 'New' OR a.status = 'Pending' OR a.status = 'Partial'
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