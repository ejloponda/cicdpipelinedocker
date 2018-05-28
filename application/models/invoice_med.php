<?php 
class Invoice_Med {

	public static $table_name = "rpc_invoice_meds";

	function findById($params) {
		$sql = " 
			SELECT " . field_injector($fields) . " FROM " . self::$table_name  . "
			WHERE id = " . Mapper::safeSql($params['id']) . "  
			LIMIT 1
		";
		return Mapper::runActive($sql);
	}

	function findSoldItem($params){
		$sql = " 
			SELECT invoice_id, medicine_id, SUM(quantity) AS total_quantity,
			SUM(price) AS price,
			SUM(total_price) AS total_price
			FROM " . self::$table_name  . " a
			LEFT JOIN `rpc_invoice` b on a.invoice_id = b.id
			WHERE medicine_id = " . Mapper::safeSql($params['id']) . " 
			AND b.status != 'Void' AND b.invoice_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . "
		";
	
		$record = Mapper::runActive($sql);
		return $record;
	}

	function findAllByInvoiceId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['invoice_id']) . "  
		";
		return Mapper::runActive($sql, TRUE);
	}
	function findByInvoiceIdandMedicineId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['id']) . "  
			AND medicine_id = " . Mapper::safeSql($params['medicine_id']) . "
		";
		return Mapper::runActive($sql, FALSE);
	}
	function findByInvoiceId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['id']) . "  
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

	function findInvoiceId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['id']) . " 
			LIMIT 1 
		";
		return Mapper::runActive($sql);
	}

	function findTotalQuantity($params){
		$sql = " 
			SELECT medicine_id, SUM(quantity) AS total_quantity,
			SUM(price) AS price,
			SUM(total_price) AS total_price
			FROM " . self::$table_name  . " 
			WHERE invoice_id = " . Mapper::safeSql($params['invoice_id']) . " AND medicine_id = " . Mapper::safeSql($params['medicine_id']) . "
		";
		$record = Mapper::runActive($sql);
		return $record;
	}

	function saveRPCMeds($data, $invoice_id){
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		if(!empty($data['rpc_meds'])){
			foreach ($data['rpc_meds'] as $key => $value) {
				$record = array(
					"invoice_id" 		=> $invoice_id,
					"type"		 		=> "RPC",
					"medicine_id" 		=> $value['medicine_id'],
					"quantity" 			=> $value['quantity'],
					"price" 			=> $value['price'],
					"total_price" 		=> $value['total_price'],
					"date_created"  	=> date("Y-m-d H:i:s"),
					"date_updated"  	=> date("Y-m-d H:i:s"),
					"last_update_by"  	=> $user_id,
					);

				$med = Inventory::findById(array("id" => $value['medicine_id']));
				if($data['status'] != "Void"){
					$less = array(
						"remaining" => $med['remaining'] - $value['quantity']
					);
					Inventory::save($less,$value['medicine_id']);

					$invoice = Invoice::findById(array("id" => $invoice_id));

					$stock = array(
						"item_id"		=> $value['medicine_id'],
						"patient_id"	=> $invoice['patient_id'],
						"quantity"		=> $value['quantity'],
						"reason"		=> "Invoiced to " . $invoice['rpc_invoice_id'],
						"created_by"	=> $user_id,
						"created_at"	=> date("Y-m-d H:i:s")
						);

					Stock::save($stock);
				}
				
				Invoice_Med::save($record);
			}
		}
		
	}

	function saveAListMeds($data, $invoice_id){
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		if(!empty($data['alist_med'])){
			foreach ($data['alist_med'] as $key => $value) {
				$record = array(
					"invoice_id" 		=> $invoice_id,
					"type"		 		=> "A-List",
					"medicine_id" 		=> $value['id'],
					"quantity" 			=> $value['quantity'],
					"price" 			=> $value['price'],
					"total_price" 		=> $value['total_price'],
					"date_created"  	=> date("Y-m-d H:i:s"),
					"date_updated"  	=> date("Y-m-d H:i:s"),
					"last_update_by"  	=> $user_id,
					);

				Invoice_Med::save($record);
			}
		}
		
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

	function findByMedID($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . " a
			LEFT JOIN `rpc_invoice` b on a.invoice_id = b.id
			WHERE medicine_id = " . Mapper::safeSql($params['id']) . "
			
		";
		return Mapper::runActive($sql, true);
	}

	function findAllMedicinesByDateRange($params) {
		$sql = " 
			SELECT " . field_injector($params['fields']) . " FROM " . self::$table_name  . "  a
			LEFT JOIN `rpc_invoice` b on a.invoice_id = b.id
			WHERE a.medicine_id = " . Mapper::safeSql($params['id']) . " AND (b.invoice_date BETWEEN " . Mapper::safeSql($params['from_date']) . " AND " . Mapper::safeSql($params['to_date']) . ")
			AND b.status != 'Void'
		";
		
		return Mapper::runActive($sql, true);
	}

}

?>