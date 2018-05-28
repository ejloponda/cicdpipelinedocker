<?php 
class Invoice_Med_AList {

	public static $table_name = "rpc_invoice_meds_alist";

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

				Invoice_Med_AList::save($record);
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