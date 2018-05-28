<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_Management extends MY_Controller {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		$this->load->library('pusher');
	}

	function index(){
		
		if(!$this->isUserLoggedIn()){
			redirect("authenticate");
		} else { 

			Engine::appStyle('RPC-style.css');
			Engine::appStyle('forms-style.css');
			Engine::appStyle('main.css');
			Engine::appStyle('bootstrap.min.css');

			Engine::appScript('inventory_management.js');
			Engine::appScript('nf.js');
			Engine::appScript('topbars.js');
			Engine::appScript('confirmation.js');
			Engine::appScript('profile.js');
			Engine::appScript('blockUI.js');
			
			/* NOTIFICATIONS */
			Jquery::pusher();
			Jquery::gritter();
			Jquery::pnotify();
			Engine::appScript('notification.js');
			/* END */

			Jquery::select2();
			Jquery::datatable();
			Jquery::form();
			Jquery::inline_validation();
			Jquery::tipsy();

			Bootstrap::datepicker();
			Bootstrap::modal();
			
			$data['page_title'] = "Inventory Management";
			$data['session'] 	= $session = $this->session->all_userdata();
			$data['user'] 		= $user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
			$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
			$data['roles']		= RPC_User_Access_Permission::findPermissionByUserType(array("user_type_id" => $user_type_id['id']));
			
			/* PATIENT MANAGEMENT */
			$data['pm_fmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 1));
			$data['pm_pi']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));
			$data['pm_pmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 3));
			
			$data['om_order']   = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 29));

			/* MODULE MANAGEMENT */
			$data['mm_dc']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 6));
			$data['mm_dt']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 7));
			
			/* MANAGE USERS */
			$data['mu_default']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 4));
			$data['mu_roles']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 5));
			$data['mu_ms']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 12));
			
			/* INVENTORY MANAGEMENT */
			$data['invent']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 13));
			$data['returns']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 14));
			$data['im_sa'] 		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 21));
			
			/* REGIMEN CREATOR */
			$data['rc_reg']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));

			/*ACCOUNT AND BILLING*/
			$data['accounting'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 22));

			/*REPORTS GENERATOR*/
			$data['reps'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 27));
			
			$data['username']	= $session['firstname'];
			$this->load->view('inventory/index',$data);

		}
	}


	function getInventoryList(){
		Engine::XmlHttpRequestOnly();
		$data['session'] 	= $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['invent']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 13));
		$this->load->view('inventory/forms/inventory_list',$data);
	}

	function loadInventoryForm(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$data['dosage']	 = $dosage 	= Dosage_Type::findAllActive();
		$data['quantity']= $quantity= Quantity_Type::findAllActive();
		$this->load->view('inventory/forms/add_inventory',$data);
	}

	function loadInventoryListTable(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));

		$data['invent'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 13));
		$this->load->view('inventory/forms/inventory_list_table',$data);
	}

	function loadMedicineForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$item_id 	= $post['item_id'];
			$med 		= Inventory::findById(array("id" => $item_id));

			if($med){
				$data['med'] = $med;
				$data['stock_history']	= Stock::findAllById(array("item_id" => $item_id));
				$this->load->view("inventory/view_item",$data);
			}
		}
		
	}

	function saveStockAdjustment(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata("user_id");
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		$item_id = $post['item_id'];

		$inv  = Inventory_Batch::findById(array("id" => $post['batch_value']));		
		$medicine = Inventory::findById(array("id" => $inv['main_med_id']));
		$total = $inv['quantity'] - $post['value'];

		$total_2 = $post['value'] - $inv['total_quantity'];
		
		$final_qty =  $post['value'] > $inv['total_quantity']  ? $inv['total_quantity'] + $total_2 : $inv['total_quantity'];

		if($inv){

			$quantity 		= (int) $post['value'];

			$batch = array(
				"total_quantity"	=> $final_qty,
				"quantity" 			=> $post['value'],
				//"date_created" 		=> date("Y-m-d h:i:s"),
				"date_updated"		=> date("Y-m-d h:i:s"),
				"created_by" 		=> $session_id
			);
			Inventory_Batch::save($batch,$inv['id']);
			$total_quantity = Inventory_Batch::computeTotalQuantity(array("id" =>  $inv['main_med_id']));

			$t = $inv['quantity']-$post['value'];
			$exp_dmg = $medicine['expired_damage_med'] + $t;

			$record = array(
				"total_quantity"	=> $total_quantity,
				/*"quantity_type" 	=> $post['qt'],*/
				"expired_damage_med"=> $post['reasons'] == 'Expired Stocks' ? $exp_dmg : $medicine['expired_damage_med'],
				"last_modified_by" 	=> $session_id
			);
			Inventory::save($record,$item_id);

			$record = array(
			"item_id"		=> $item_id,
			"quantity"		=> str_replace('-', '', $total),
			"reason"		=> $post['reasons']."- Updated Batch - " . $inv['batch_no'] . ($total_quantity > $medicine['total_quantity'] ? " Added Quantity" : " Less Quantity" ),
			"created_by"	=> $session_id,
			"created_at"	=> date("Y-m-d H:i:s")
			);

			Stock::save($record);

			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
			"module"		=> "rpc_management_inventory",
			"user_id"		=> $session_id,
			"entity_id"		=> $item_id,
			"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "stock adjustment <a href='javascript:void(0);'>{$medicine['medicine_name']} </a>, Reason: " . $post['reasons'],
			"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			//New Notification
			$msg = $session_user['name'] . " has successfully Updated Batch: {$inv['batch_no']} in {$medicine['medicine_name']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Updated Batch' " . $inv['batch_no'],
				'type' => 'info'
			));

		}


		/*$medicine_name  = $inv['medicine_name'];
		$generic_name 	= $inv['generic_name'];


		$total_quantity = $inv['total_quantity'];
		$quantity 		= (int) $post['value'];
		$new_total 		= ($quantity >= $total_quantity ? $quantity : $total_quantity);

		$stock = array(
			"remaining" 		=> (int) $quantity,
			"total_quantity"	=> (int) $new_total,
			);

		Inventory::save($stock,$item_id);*/



		/*$record = array(
			"item_id"		=> $item_id,
			"quantity"		=> $post['quantity'],
			"reason"		=> $post['reasons'],
			"created_by"	=> $session_id,
			"created_at"	=> date("Y-m-d H:i:s")
			);

		Stock::save($record);*/
		/* ACTIVITY TRACKER */

		/*$act_tracker = array(
			"module"		=> "rpc_management_inventory",
			"user_id"		=> $session_id,
			"entity_id"		=> $item_id,
			"message_log" 	=> "stock adjustment <a href='javascript:void(0);'>{$medicine_name} - {$generic_name}</a>, Reason: " . $post['reasons'],
			"date_created" 	=> date("Y-m-d H:i:s"),
		);
		Activity_Tracker::save($act_tracker);*/
	}

	function saveNewInventory(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		//debug_array($post);
		$session_user = $this->session->all_userdata();
		$session = $this->session->userdata("user_id");
		$session_id = $this->encrypt->decode($session);
		$user = User::findById(array("id"=>$session_id));

		if($post){
			if($post['item_id']){
				$item_id = $post['item_id'];

			$record = array(
				"product_no" 		=> $post['product_no'],
				"medicine_name" 	=> $post['medicine_name'],
				"generic_name"  	=> $post['generic_name'],
				"dosage" 			=> $post['dosage'],
				"dosage_type" 		=> $post['dosage_type'],
				"qty_per_bottle"	=> $post['qty_per_bottle'],
				/*"total_quantity"	=> $post['quantity'],*/
				/*"remaining" 		=> (int) $quantity,*/
				/*"quantity_type" 	=> $post['quantity_type'],*/
				/*"total_quantity"	=> (int) $new_total,*/
				"stock" 			=> $post['stock'],
				"stock_price" 		=> $post['cost_to_purchase'],
				"price" 			=> str_replace(',', '', $post['price']),
				"cost_sales"		=> $post['cost_of_sales'],
				"status"			=> $post['status'],
				/*"purchase_date" 	=> $post['purchase_date'],
				"expiry_date" 		=> $post['expiration_date'],*/
				"last_modified_by" 	=> $session_id
			);

			Inventory::save($record,$item_id);


			/* ACTIVITY TRACKER */
			$medicine_name = $post['medicine_name'];
			$act_tracker = array(
				"module"		=> "rpc_management_inventory",
				"user_id"		=> $session_id,
				"entity_id"		=> $item_id,
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "Updated Medicine: <a href='javascript:void(0);' class='track_inv_item' data-id='" . $item_id . "'>{$medicine_name}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			/* NOTIFICATION */

			/*$user = User::findById(array("id" => $session_id));
			$json['notif_title'] 	= "Updated " . $medicine_name;
			$json['notif_type']		= "info";
			$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has updated {$medicine_name}";*/
			
			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Updated {$medicine_name} in database";

			//New Notification
			$msg = $session_user['name'] . " has successfully Updated Medicine: {$medicine_name}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Updated Medicine' " . $medicine_name,
				'type' => 'info'
			));
		
			} else {	
				
				$quantity_type  	= $post['quantity_type'];
				$total_quantity 	= $post['quantity'];
				
				$record = array(
					"product_no" 		=> $post['product_no'],
					"medicine_name" 	=> $post['medicine_name'],
					"generic_name"  	=> $post['generic_name'],
					"dosage" 			=> $post['dosage'],
					"dosage_type" 		=> $post['dosage_type'],
					"qty_per_bottle"	=> $post['qty_per_bottle'],
					/*"remaining" 		=> (int) $quantity,*/
					/*"total_quantity"	=> (int) $total_quantity,*/
					"total_quantity"	=> $post['quantity'],
					"quantity_type" 	=> $post['quantity_type'],
					"stock" 			=> $post['stock'],
					"stock_price" 		=> $post['cost_to_purchase'],
					"price" 			=> str_replace(',', '', $post['price']),
					"cost_sales"		=> $post['cost_to_purchase'],
					"status"			=> $post['status'],
					/*"purchase_date" 	=> $post['purchase_date'],
					"expiry_date" 		=> $post['expiration_date'],*/
					"date_created" 		=> date("Y-m-d h:i:s"),
					"last_modified_by" 	=> $session_id
				);

				$item_id = Inventory::save($record);

				$record = array(
					"item_id"		=> $item_id,
					"quantity"		=> $post['quantity'],
					"reason"		=> "Initial Stocks",
					"created_by"	=> $session_id,
					"created_at"	=> date("Y-m-d H:i:s")
					);

				Stock::save($record);

				$batch = array(
				"main_med_id"		=> $item_id,
				"batch_no" 			=> $post['batch_number'],
				/*"remaining" 		=> $post['quantity'],*/
				"total_quantity"    => $post['quantity'],
				"quantity" 			=> $post['quantity'],
				/*"quantity_type" 	=> $post['quantity_type'],*/
				"purchase_date" 	=> $post['purchase_date'],
				"expiry_date" 		=> $post['expiration_date'],
				"date_created" 		=> date("Y-m-d h:i:s"),
				"date_updated"		=> date("Y-m-d h:i:s"),
				"created_by" 		=> $session_id
				);

				Inventory_Batch::save($batch);

				/* ACTIVITY TRACKER */
				$medicine_name = $post['medicine_name'];
				$act_tracker = array(
					"module"		=> "rpc_management_inventory",
					"user_id"		=> $session_id,
					"entity_id"		=> $item_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "Added new medicine: <a href='javascript:void(0);' class='track_inv_item' data-id='" . $item_id . "'>{$medicine_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$act_tracker = array(
					"module"		=> "rpc_management_inventory",
					"user_id"		=> $session_id,
					"entity_id"		=> $item_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "Added new batch for Medicine: <a href='javascript:void(0);' class='track_inv_item' data-id='" . $item_id . "'>{$medicine_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				/* NOTIFICATION */
				/*$user = User::findById(array("id" => $session_id));
				$json['notif_title'] 	= "Added " . $medicine_name;
				$json['notif_type']		= "info";
				$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has added {$medicine_name}";*/

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$medicine_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Medicine: {$medicine_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Medicine' " . $medicine_name,
					'type' => 'info'
				));
			}

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error updating to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}

	function checkToEditItemInv(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		if($post){
			$item_id = $post['item_id'];
			$inv 	 = Inventory::findById(array("id" => $item_id));
			$batch   = Inventory_Batch::findByMainIdMed(array("id" => $item_id));
			$batches = Inventory_Batch::findByBatch(array("id" => $item_id));
			
			if($inv){
				$data['inv'] 	 = $inv;
				$data['batch']   = $batch;
				$data['batches'] = $batches;
				$data['dosage']	 = $dosage 	= Dosage_Type::findAllActive();
				$data['quantity']= $quantity= Quantity_Type::findAllActive();
				$this->load->view("inventory/forms/edit_inventory",$data);
			}
		}
		
	}

	function checkToDeleteItemInv(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$item_id = $post['item_id'];
			$inv = Inventory::findById(array("id" => $item_id));

			if($inv){
				$data['inv'] = $inv;
				$this->load->view("inventory/forms/delete_inventory",$data);
			}
		}
	}

	function deleteItemInventory(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata("user_id");
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		$post = $this->input->post();
		if($post){
			$item_id = $post['item_id'];
			$inv = Inventory::findById(array("id" => $item_id));
			$inv_batch = Inventory_Batch::findByMed(array("id" => $item_id));

			if($inv){
				$medicine_name = $inv['medicine_name'];
				$generic_name  = $inv['generic_name'];
				Inventory::delete($item_id);

				foreach ($inv_batch as $key => $value) {
					Inventory_Batch::delete($value['id']);
				}
				
				
				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_management_inventory",
					"user_id"		=> $session_id,
					"entity_id"		=> "0",
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted item <a href='javascript:void(0);'>{$medicine_name} - {$generic_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Deleted {$medicine_name} - {$generic_name} in database";

			} else {
				$json['is_successful'] 	= false;
				$json['message']		= "Ooop! Error deleting item to database. Please contact web administrator!";
			}

			echo json_encode($json);
		}
	}


	function getAllInventoryList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$im_list 		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 13));

		$sorting 	= (int) $get['iSortCol_0'];
		$query   	= $get['sSearch'];
		$order_type	= strtoupper("desc");

		$display_start 	= (int) $get['iDisplayStart'];

		// header fields
		$rows = array(
			0 => "a.product_no",
			1 => "a.generic_name",
			2 => "a.medicine_name",
			3 => "a.dosage",
			4 => "a.total_quantity",
			5 => "a.id",
			6 => "a.qty_per_bottle",
			7 => "a.status",
		);

		$fields = array("a.id", "a.product_no", "a.generic_name", "a.medicine_name", "a.dosage", "a.status", "a.total_quantity", "a.stock","a.qty_per_bottle", "b.abbreviation as abbr1", "c.abbreviation as abbr2" );

		$order_by 	= $rows[$sorting] . " {$order_type}";
		if($get['stock_percentage'] != 'NaN'){
			$limit  	= $display_start . ", 10";
		}else{
			$limit  	= $display_start . ", 10";
		}	
		

		 $params = array(
		 	"search" 	=> $query,
		 	"fields" 	=> $fields,
		 	"order"  	=> $order_by,
		 	"limit"  	=> $limit,
		 );

		$inventory 		= Inventory::generateInventoryDatatable($params);
		$total_records 	= Inventory::countInventoryDatatable($params);
		$output = array(
			"sEcho" => $get['sEcho'],
			"iTotalRecords" => $total_records,
			"iTotalDisplayRecords" => $total_records,
			"aaData" => array()
		);

		if($get['stock_percentage'] == 'NaN') {
			foreach($inventory as $key=>$value):

				if($im_list['can_update'] && $im_list['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_item_inv(' . $value['id'] . ');" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_item_inv('.$value['id'].');" class="delete_item_inv table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($im_list['can_update'] && !$im_list['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_item_inv(' . $value['id'] . ');" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$im_list['can_update'] && $im_list['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_item_inv('.$value['id'].')" class="delete_item_inv table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}
				
				if($im_list['can_view']){
					$medicine_name_link = "<a href='javascript: void(0);' onClick='javascript: view_item(" . $value['id'] . ")'>" . $value['medicine_name'] . "</a>";
				} else {
					$medicine_name_link = $value['medicine_name'];
				}
				$total_quantity 	= Inventory_Batch::sumTotalQuantity(array("id"=> $value['id']));
				$stock_percentage 	= $get['stock_percentage'] / 100;
				$quantity 			= $total_quantity * $stock_percentage;
				$dosage_type 		= Dosage_Type::findById(array("id"=> $value['dosage_type']));
				$qt 				= Quantity_Type::findById(array("id"=> $value['quantity_type']));

				$all_quantity 		= Inventory_Batch::computeTotalQuantity(array("id"=> $value['id']));

				$now 		= time(); // or your date as well
			    $your_date 	= strtotime($value['expiry_date']);
			    $datediff 	= $now - $your_date;
			    $expiry 	= floor($datediff/(60*60*24));
				$row = array(
					// '0' => $value['id'],
					'0' => $value['product_no'],
					'1' => $value['generic_name'],
					'2' => $medicine_name_link,
					'3' => $value['dosage'] . " " . $value['abbr2'],
					'4' => ($all_quantity <= $quantity ? "<span style='color: red;'>" . $all_quantity . " " . $value['abbr1'] . "</span>" : $all_quantity . " " . $value['abbr1']),
					'5' => $value['qty_per_bottle'] ." pcs",
					'6' => $value['status'],
					'7' => $action_link,

				);
				// remove expiry variable when finish
				$output['aaData'][] = $row;

			endforeach;

		} else if($get['stock_percentage'] >= 0) {
			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$inventory = Inventory::findAll($params);

			foreach($inventory as $key=>$value):

				$total_quantity  	= Inventory_Batch::sumTotalQuantity(array("id"=> $value['id']));
				$qty 		= Inventory_Batch::computeTotalQuantity(array("id"=> $value['id']));

				$a = round(($qty / $total_quantity) * 100);
				$b = round($get['stock_percentage']);

				if($a == $b){
					$inv[] = array(
						"id"				=> $value['id'],
						"product_no"		=> $value['product_no'],
						"generic_name" 		=> $value['generic_name'],@
						"medicine_name" 	=> $value['medicine_name'],
						"total_quantity" 	=> $total_quantity,
						"quantity"			=>  $qty,
						"dosage"			=> $value['dosage'] . " " . $value['abbr2'],
						"qty_per_bottle"    => $value['qty_per_bottle'],
						"status"			=> $value['status'],
						"abbr1"				=> $value['abbr1'],
						"a"					=> $a,
						"b"					=> $b,
						);
				} 
				
			endforeach;

			$total_records = count($inv);
			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records / 10,
				"iTotalDisplayRecords" => $total_records /10,
				"aaData" => array(),
				"iDisplayLength"	=> 10,
			);

			foreach ($inv as $key => $val) {
				if($im_list['can_update'] && $im_list['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_item_inv(' . $val['id'] . ');" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_item_inv('.$val['id'].');" class="delete_item_inv table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($im_list['can_update'] && !$im_list['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_item_inv(' . $val['id'] . ');" class="edit_item_inv table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$im_list['can_update'] && $im_list['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_item_inv('.$val['id'].')" class="delete_item_inv table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}
				
				if($im_list['can_view']){
					$medicine_name_link = "<a href='javascript: void(0);' onClick='javascript: view_item(" . $val['id'] . ")'>" . $val['medicine_name'] . "</a>";
				} else {
					$medicine_name_link = $val['medicine_name'];
				}
				
				$row = array(
					'0' => $val['product_no'],
					'1' => $val['generic_name'],
					'2' => $medicine_name_link,
					'3' => $val['dosage'],
					'4' => ($val['total_quantity'] <= $val['quantity'] ? "<span style='color: red;'>" . $val['quantity'] . " " . $val['abbr1'] . "</span>" : $val['quantity'] . " " . $val['abbr1']),
					'5' => $val['qty_per_bottle'] ." pcs",
					'6' => $val['status'],
					'7' => $action_link,

				);
				$output['aaData'][] = $row;
			}
		} else {
			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => 0,
				"iTotalDisplayRecords" => 0,
				"aaData" => array()
			);
		}
		
		echo json_encode($output);
	}


	function getMedicineDetails(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		if($post){
			$item_id 	= $post['med'];
			$inv 		= Inventory::findById(array("id" => $item_id));
			$dosage 	= Dosage_Type::findById(array("id" => $inv['dosage_type']));
			$quantity 	= Quantity_Type::findById(array("id" => $inv['quantity_type']));

			if($inv){
				$json['output'] = array(
					"generic_name" 	=> $inv['generic_name'],
					"dosage" 		=> $inv['dosage'],
					"dosage_type"	=> $dosage['abbreviation'],
					"quantity" 		=> $inv['remaining'],
					"quantity_type" => $quantity['abbreviation'],
					"stock" 		=> $inv['stock'],
					"purchase_date" => $inv['purchase_date'],
					"expiry_date" 	=> $inv['expiry_date']
					);
			}
			echo json_encode($json);
		}

	}

	function loadStockAdjustmentForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$item_id = $post['item_id'];
		$inv = Inventory::findById(array("id" => $item_id));
		$batches = Inventory_Batch::findByBatch(array("id" => $item_id));

		if($inv){
			$data['inv'] = $inv;
			$data['batches'] = $batches;
			$data['im_sa'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 21));
			$data['reasons'] = Reasons::findAllActive();
			$this->load->view("inventory/forms/stock_adjustment",$data);
		} else {
			$this->load->view('404');
		}
	}

	function loadReturnsHistoryPage(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$item_id = $post['item_id'];
		$inv = Inventory::findById(array("id" => $item_id));
		if($inv){
			$data['inv'] 		= $inv;
			$data['im_sa'] 		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 21));
			$returns 			= Returns_Meds::findAllById(array("item_id" => $item_id));

			foreach ($returns as $key => $value) {
				$ret = Returns::findById(array("id" => $value['returns_id']));
				$inv = Invoice::findById(array("id" => $ret['invoice_id']));
				$arr = array(
					"invoice_number" => $inv['order_id'],
					"reason"		 => $ret['reason_of_return'],
					"date_return"	 => $ret['date_return'],
					"quantity"		 => $value['quantity']
					);
				$return_items[] = $arr;
			}

			$data['returns'] = $return_items;
			$this->load->view("inventory/view_returns_history_item",$data);
		} else {
			$this->load->view('404');
		}
	}	

	function addBatch(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		//debug_array($post);
	}

	function addBatchModal(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		//debug_array($post);
		if($post){
			$params = array(
			"batchcode"  => $post['prod_no'],
			"med_id"	 => $post['med_id'],
			);
			$code = ($params['batchcode']);
			$med_id = ($params['med_id']);
			$x++;

			$inv = Inventory::findById(array("id" => $med_id));
			$batch = Inventory_Batch::countByMainIdMed(array("id" => $med_id));
			//debug_array($inv);
			$next_id = ($batch <= 0 ? 1 : $batch + 1);
			$batchcode = $inv['product_no'] . "." . str_pad($next_id, 3, "0",STR_PAD_LEFT);
			$data['batchcode'] = $batchcode;
			$data['med_id'] = ($params['med_id']);
		}
		$data['qt']      = $qt = Quantity_Type::findById(array("id"=> $inv['quantity_type']));
		$data['session'] = $session = $this->session->all_userdata();
		$data['dosage']	 = $dosage 	= Dosage_Type::findAllActive();
		$data['quantity']= $quantity= Quantity_Type::findAllActive();
		$this->load->view("inventory/batch/add_batch",$data);
	}

	function saveBatch(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		//debug_array($post);
		$session_user = $this->session->all_userdata();
		$med_id = $post['med_id'];
		$session = $this->session->userdata("user_id");
		$session_id = $this->encrypt->decode($session);
		$user = User::findById(array("id"=>$session_id));
		if($post){
			if($post['batch_id']){
				$batch_id 	= $post['batch_id'];
				$med_id 	= $post['med_id'];

				$total_quantity = Inventory_Batch::computeTotalQuantity(array("id" =>  $med_id));
				$batch_item 	= Inventory_Batch::findById(array("id" =>  $batch_id));

				$inv = Inventory::findById(array("id" => $med_id));
				$quantity 		= (int) $post['quantity'];

				if($quantity != $batch_item['quantity']){
					$new_total = ($quantity > $batch_item['quantity'] ?  $batch_item['quantity'] + ($quantity - $batch_item['quantity']) : $batch_item['quantity'] - ($batch_item['quantity'] - $quantity));
				} else {
					$new_total = $quantity;
				}

				$t= $new_total - $batch_item['total_quantity'] ;
				
				$final_qty =  $quantity > $batch_item['total_quantity']  ? $batch_item['total_quantity'] + $t : $batch_item['total_quantity'];


			$batch = array(
				"batch_no" 			=> $post['batch_number'],
				"total_quantity"	=> $final_qty,
				"quantity" 			=> $new_total,
				"purchase_date" 	=> $post['purchase_date'],
				"expiry_date" 		=> $post['expiration_date'],
				//"date_created" 		=> date("Y-m-d h:i:s"),
				"date_updated"		=> date("Y-m-d h:i:s"),
				"created_by" 		=> $session_id
			);
			
			Inventory_Batch::save($batch,$batch_id);
			$total = Inventory_Batch::computeTotalQuantity(array("id" => $med_id, "main_med_id" => $batch_id));

			$record = array(
				"total_quantity"	=> $total,
			    "quantity_type" 	=> $inv['quantity_type'],
				"last_modified_by" 	=> $session_id
			);
			Inventory::save($record,$med_id);

			
			$record = array(
				"item_id"		=> $med_id,
				"quantity"		=> ($quantity - $batch_item['quantity']),
				"reason"		=> $user['lastname'].",". $user['firstname']  ." ". "Updated Batch - " . $post['batch_number'] . ($new_total > $batch_item['quantity'] ? " Added Quantity" : " Less Quantity"),
				"created_by"	=> $session_id,
				"created_at"	=> date("Y-m-d H:i:s")
				);
			Stock::save($record);
			/* ACTIVITY TRACKER */

			$inv = Inventory::findById(array("id" => $med_id));
			$medicine_name = $inv['medicine_name'];
			$act_tracker = array(
				"module"		=> "rpc_management_inventory",
				"user_id"		=> $session_id,
				"entity_id"		=> $med_id,
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "stock adjustment <a href='javascript:void(0);'>{$medicine_name}</a>, Reason: " . "New Batch - " . $post['batch_number'],
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			/*$user = User::findById(array("id" => $session_id));
			$json['notif_title'] 	= "Added " . $medicine_name;
			$json['notif_type']		= "info";
			$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has updated batch {$post['batch_number']} for {$medicine_name}";*/


			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Added {$medicine_name} in database";

			//New Notification
			$msg = $session_user['name'] . " has successfully updated batch: {$post['batch_number']} for {$medicine_name}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Updated Batch' " . $post['batch_number'],
				'type' => 'info'
			));

			} else {
					$med_id = $post['med_id'];

					$med = Inventory::findById(array("id" => $med_id));
					$total_quantity = $med['total_quantity'];
					$quantity 		= $post['quantity'];
					$new_total 		= ($quantity + $total_quantity);
					$quantity_type  = $post['quantity_type'];

				$record = array(
					"total_quantity"	=> (int) $new_total,
					"quantity_type" 	=> $med['quantity_type'],
					"last_modified_by" 	=> $session_id
				);

				Inventory::save($record,$med_id);

					$batch = array(
						"main_med_id"		=> $post['med_id'],
						"batch_no" 			=> $post['batch_number'],
						"total_quantity"	=> (int) $quantity,
						"quantity" 			=> (int) $quantity,
						"purchase_date" 	=> $post['purchase_date'],
						"expiry_date" 		=> $post['expiration_date'],
						"date_created" 		=> date("Y-m-d h:i:s"),
						"date_updated"		=> date("Y-m-d h:i:s"),
						"created_by" 		=> $session_id
					);

				Inventory_Batch::save($batch);

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$record = array(
					"item_id"		=> $med_id,
					"quantity"		=> $new_total,
					"reason"		=> $user['lastname'].",". $user['firstname']  ." ". "Added new batch - " . $post['batch_number'],
					"created_by"	=> $session_id,
					"created_at"	=> date("Y-m-d H:i:s")
					);

				Stock::save($record);

				$medicine_name = $med['medicine_name'];
				$act_tracker = array(
					"module"		=> "rpc_management_inventory",
					"user_id"		=> $session_id,
					"entity_id"		=> $med_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "Added new batch for Medicine: <a href='javascript:void(0);' class='track_inv_item' data-id='" . $med_id . "'>{$medicine_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$medicine_name = $med['medicine_name'];
				/* NOTIFICATION */
				/*$user = User::findById(array("id" => $session_id));
				$json['notif_title'] 	= "Added " . $medicine_name;
				$json['notif_type']		= "info";
				$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has uploaded new batch for {$medicine_name}";*/

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$medicine_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully uploaded new batch for {$medicine_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added New Batch' " . $medicine_name,
					'type' => 'info'
				));
			}
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error updating to database. Please contact web administrator!";
		}
	
		echo json_encode($json);
				
	}

	function batch_details(){
		Engine::XmlHttpRequestOnly();

		$post 				= $this->input->post();
		$data['quantity']	= $quantity_type= Quantity_Type::findAllActive();
		$batch 				= Inventory_Batch::findById(array("id" => $post['id']));
		$med 				= Inventory::findById(array("id" => $batch['main_med_id']));
		$qt  				= Quantity_Type::findById(array("id"=> $med['quantity_type']));
		
		if($batch){
			$data['batch'] 	= $batch;
			$data['med'] 	= $med;
			$data['qt'] 	= $qt;
			$this->load->view('inventory/batch/view_batch_details',$data);
		}
	}

	function edit_batch_details(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$batch 	= Inventory_Batch::findById(array("id" => $post['id']));
		$med 	= Inventory::findById(array("id" => $batch['main_med_id']));
		$data['quantity']= $quantity= Quantity_Type::findAllActive();
		$data['qt']      = $qt = Quantity_Type::findById(array("id"=> $med['quantity_type']));
		if($batch){
			$data['batch'] 	= $batch;
			$data['med'] 	= $med;
			$data['qt']     = $qt;
			$this->load->view('inventory/batch/edit_batch_details',$data);
		}
	}

	function batch_list(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$batches 	= Inventory_Batch::findByBatch(array("id" => $post['medicine_id']));
			
			$data['batches'] = $batches;
			$this->load->view('inventory/view_batch_list',$data);
			
		} else {
			echo "<h1> Can't find the requested medicine, please contact your administrator. </h1>";
		}	
	}

	function getBatchDetails(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$batch_id 	= $post['batch_id'];
			$batch 		= Inventory_Batch::findById(array("id" => $batch_id));
			$inv = Inventory::findById(array("id" => $batch['main_med_id']));
			if($batch){
				$quantity_type 	= Quantity_Type::findById(array("id" => $inv['quantity_type']));
				$json['output'] = array(
					"quantity" 	=> $batch['quantity'],
					"quantity_type" => $quantity_type['abbreviation'],
				);
			echo json_encode($json);
			}	
		}	
	}

	function excelReaderBatchUpload(){
		$this->load->view('inventory/upload/batch_upload');
	}

		function check_stock(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);

		$post = $this->input->post();
		$inv = Inventory::findById(array("id" => $post['med_id']));
		
		//check stock_level
		/*if($inv['total_quantity'] <= $inv['stock_level'] ){
			//low level
			$user = User::findById(array("id" => $user_id));
			if($user){
				$userToNotify = User::findPharmaAcctMgt();

				foreach ($userToNotify as $key => $value) {
					$msg = $inv['medicine_name'] ." is running low of stock.";

					$alert = User::findById(array("id"=>$value['id']));

					$this->pusher->trigger($alert['username'], 'tag_notification', array(
						'message' => $msg,
						'title' => "Low Stock " . $inv['medicine_name'],
						'type' => 'Alert',
						'datetime' 	=> date("Y-m-d H:i:s")
					));

					$notif = array(
						"message" 		=> $msg,
						"type"			=> 'tag notification',
						"event_id"		=> $inv['id'],
						"received_by" 	=> $user_id,
						"created_by"	=> $user_id,
						"date_created"	=> date("Y-m-d H:i:s")
						);
					Notification::save($notif);
				}
			}
		
		}*/

		if($inv['total_quantity'] <= 0){
			$json['value'] = $inv['total_quantity'];
			$json['message'] 		= "Sorry! {$inv['medicine_name']} is out of stock";
		}else{
			$json['normal_message'] 		= "{$inv['medicine_name']} is in normal stock";
		}
		echo json_encode($json);
	}

}

