<?php 
class Invoice_Cost_Modifier_AList {

	public static $table_name = "rpc_invoice_cost_modifier_alist";

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

	function saveCostModifier($data, $invoice_id){
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		if(!empty($data['alist_cm_additional'])){
			foreach ($data['alist_cm_additional'] as $key => $value) {
				if($value['id']){
					$record = array(
						"invoice_id" 		=> $invoice_id,
						"applies_to" 		=> $value['applies_to'],
						"modifier_type" 	=> $value['modifier_type'],
						"modify_due_to" 	=> $value['modify_due_to'],
						"cost_type" 		=> $value['cost_type'],
						"cost_modifier" 	=> $value['cost_modifier'],
						"total_cost" 		=> $value['total_cost'],
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $user_id,
						);

					Invoice_Cost_Modifier_AList::save($record, $value['id']);
				} else {
					$record = array(
						"invoice_id" 		=> $invoice_id,
						"applies_to" 		=> $value['applies_to'],
						"modifier_type" 	=> $value['modifier_type'],
						"modify_due_to" 	=> $value['modify_due_to'],
						"cost_type" 		=> $value['cost_type'],
						"cost_modifier" 	=> $value['cost_modifier'],
						"total_cost" 		=> $value['total_cost'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $user_id,
						);

					Invoice_Cost_Modifier_AList::save($record);
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