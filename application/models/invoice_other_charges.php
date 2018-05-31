<?php 
class Invoice_Other_Charges {

	public static $table_name = "rpc_invoice_other_charges";

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

	function findByInvoiceIdandRCenters($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . "
			WHERE invoice_id = " . Mapper::safeSql($params['id']) . "  
			AND description_id = " . Mapper::safeSql($params['description_id']) . "
		";
		return Mapper::runActive($sql, FALSE);
	}

	function findAllByInvoiceIdWithDescId($params) {
		$sql = " 
			SELECT * FROM " . self::$table_name  . " a
			LEFT JOIN `rpc_other_charges` b on a.description_id = b.id 
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

	function saveOtherCharges($data, $invoice_id){
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);

		if(!empty($data['additional'])){
			foreach ($data['additional'] as $key => $value) {
				if($value['id']){
					$record = array(
						"invoice_id" 		=> $invoice_id,
						"description" 		=> $value['description'],
						"quantity" 			=> $value['quantity'],
						"cost_per_item" 	=> $value['cost_per_item'],
						"cost" 				=> $value['cost'],
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $user_id,
						);

					Invoice_Other_Charges::save($record, $value['id']);
				} else {
					$record = array(
						"invoice_id" 		=> $invoice_id,
						"description" 		=> $value['description'],
						"quantity" 			=> $value['quantity'],
						"cost_per_item" 	=> $value['cost_per_item'],
						"cost" 				=> $value['cost'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $user_id,
						);

					Invoice_Other_Charges::save($record);
				}
				
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

}

?>