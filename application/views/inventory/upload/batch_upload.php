<?php include('themes/php-includes/simpleExcel.php'); ?>

<?php
	$xlsx = new SimpleXLSX("themes/php-includes/uploading.xlsx");


	function xl2timestamp($xl_date)
	{
		$timestamp = ($xl_date - 25569) * 86400;
		return $timestamp;
	}


	$page = 2;
	$data = $xlsx->rows($page);

	if($page == 1){
		$start = 58;
		$end   = 210;
		for ($i=$start; $i <= $end ; $i++) { 
			$product_no = substr($data[$i][2],0, -4);
			$arr2[$i] = $product_no;
		}

		$new_array = array_unique($arr2);
		$another_array = array_keys($new_array);

		for ($i=0; $i <= 148; $i++) { 
			$key = $another_array[$i];
			unset($record);
			$dosage_type = ($data[$key][7] == "" || $data[$key][7] == "-" ? "none" : strtolower(preg_replace('/[0-9]+/', "", $data[$key][7])));
			$record = array(
				"supplier" => $data[$key][4],
				"product_no" => substr($data[$key][2],0, -4),
				"medicine_name" => $data[$key][3],
				"generic_name" => "n/a",
				"dosage" => preg_replace("/[^0-9,.]/", "", $data[$key][7]),
				"dosage_type" => ($dosage_type == 'g'? 5 : ($dosage_type == 'mg'? 2 : ($dosage_type == "gr"? 4 : ($dosage_type == 'IU' ? 7 : ($dosage_type == 'ml'? 3 : 0 ) ) ) ) ),
				"quantity_type" => 2,
				"stock" => "Royal Preventive",
				"stock_price" => $data[$key][18],
				"price" => $data[$key][19],
				"cost_sales" => $data[$key][18],
				"date_created" => date("Y-m-d H:i:s"),
				"last_modified_by" => 53,

				);

			$id = Inventory::save($record);
			$StartingDate = date("Y-m-d");
			$newEndingDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartingDate)) . " + 6 months"));
			$batch = array(
				"main_med_id" 	=> $id,
				"batch_no"		=> $data[$key][2],
				"total_quantity"=> $data[$key][15],
				"quantity"		=> $data[$key][15],
				"purchase_date"	=> date("Y-m-d"),
				"expiry_date"	=> date("Y-m-d", xl2timestamp($data[$key][10])),
				// "expiry_date"	=> $newEndingDate,
				"date_created" 	=> date("Y-m-d H:i:s"),
				"date_updated" 	=> date("Y-m-d H:i:s"),
				"created_by" 	=> 53,
				);

			// $arr[] = $record;
			$batch_id = Inventory_Batch::save($batch);

			$update = array("total_quantity" => $data[$key][15]);

			Inventory::save($update,$id);

			$record = array(
			"item_id"		=> $id,
			"quantity"		=> $data[$key][15],
			"batch_no"		=> $batch_id,
			"reason"		=> "New batch for " . $data[$key][3],
			"created_by"	=> 53,
			"created_at"	=> date("Y-m-d H:i:s")
			);

			Stock::save($record);

			$act_tracker = array(
			"module"		=> "rpc_management_inventory",
			"user_id"		=> 53,
			"entity_id"		=> $id,
			"message_log" 	=> "New Medicine: " . $data[$key][3],
			"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$act_tracker = array(
			"module"		=> "rpc_management_inventory",
			"user_id"		=> 53,
			"entity_id"		=> $batch_id,
			"message_log" 	=> "New Batch for Medicine: " . $data[$key][3],
			"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);
			// $arr[] = $batch;
		}
	} else if($page == 2){
		$start = 5;
		$end   = 161;
		for ($i=$start; $i <= $end ; $i++) { 
			$product_no = $data[$i][3];
			$arr2[$i] = $product_no;
		}

		$new_array = array_unique($arr2);
		$another_array = array_keys($new_array);
		
		for ($i=0; $i <= 148; $i++) { 
			$key = $another_array[$i];
			unset($record);
			$dosage_type = ($data[$key][9] == "" || $data[$key][9] == "-" ? "none" : strtolower(preg_replace('/[0-9]+/', "", $data[$key][9])));
			$record = array(
				"supplier" => $data[$key][6],
				"product_no" => $data[$key][3],
				"medicine_name" => $data[$key][2],
				"generic_name" => "n/a",
				"dosage" => preg_replace("/[^0-9,.]/", "", $data[$key][9]),
				"dosage_type" => ($dosage_type == 'g'? 5 : ($dosage_type == 'mg'? 2 : ($dosage_type == "gr"? 4 : ($dosage_type == 'IU' ? 7 : ($dosage_type == 'ml'? 3 : 0 ) ) ) ) ),
				"quantity_type" => 2,
				"stock" => "A-List",
				"stock_price" => $data[$key][18],
				"price" => $data[$key][19],
				"cost_sales" => $data[$key][18],
				"date_created" => date("Y-m-d H:i:s"),
				"last_modified_by" => 53,

				);
			// debug_array($record);
			$id = Inventory::save($record);
			$StartingDate = date("Y-m-d");
			$newEndingDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartingDate)) . " + 6 months"));
			$batch = array(
				"main_med_id" 	=> $id,
				"batch_no"		=> $data[$key][3]. "." . str_pad(1, 3, "0",STR_PAD_LEFT),
				"total_quantity"=> $data[$key][17],
				"quantity"		=> $data[$key][17],
				"purchase_date"	=> date("Y-m-d"),
				"expiry_date"	=> date("Y-m-d", xl2timestamp($data[$key][12])),
				// "expiry_date"	=> $newEndingDate,
				"date_created" 	=> date("Y-m-d H:i:s"),
				"date_updated" 	=> date("Y-m-d H:i:s"),
				"created_by" 	=> 53,
				);

			// $arr[] = $batch;
			$batch_id = Inventory_Batch::save($batch);

			$update = array("total_quantity" => $data[$key][17]);

			Inventory::save($update,$id);

			$record = array(
			"item_id"		=> $id,
			"quantity"		=> $data[$key][17],
			"batch_no"		=> $batch_id,
			"reason"		=> "New batch for " . $data[$key][2],
			"created_by"	=> 53,
			"created_at"	=> date("Y-m-d H:i:s")
			);

			Stock::save($record);

			$act_tracker = array(
			"module"		=> "rpc_management_inventory",
			"user_id"		=> 53,
			"entity_id"		=> $id,
			"message_log" 	=> "New Medicine: " . $data[$key][2],
			"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$act_tracker = array(
			"module"		=> "rpc_management_inventory",
			"user_id"		=> 53,
			"entity_id"		=> $batch_id,
			"message_log" 	=> "New Batch for Medicine: " . $data[$key][2],
			"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);
		}

		// debug_array($arr);
	}
?>