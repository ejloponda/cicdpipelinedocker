<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders_Management extends MY_Controller {
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

			Engine::appScript('orders.js');
			Engine::appScript('topbars.js');
			Engine::appScript('confirmation.js');
			Engine::appScript('profile.js');
			Engine::appScript('blockUI.js');
			Engine::appScript("ckeditor/ckeditor.js");

			Jquery::jBox();
			Jquery::datatable();
			Jquery::form();
			Jquery::inline_validation();
			Jquery::tipsy();
			Jquery::plup_uploader();
			Jquery::image_preview();
			Jquery::select2();
			Jquery::mask();
			Jquery::numberformat();

			/* NOTIFICATIONS */
			Jquery::pusher();
			Jquery::gritter();
			Jquery::pnotify();
			Engine::appScript('notification.js');
			/* END */

			Bootstrap::datetimepicker();
			Bootstrap::datepicker();
			Bootstrap::modal();

			$data['page_title'] = "Order Forms";
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

			/* REGIMEN CREATOR */
			$data['rc_reg']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));

			/*ACCOUNT AND BILLING*/
			$data['accounting'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 22));

			/*REPORTS GENERATOR*/
			$data['reps'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 27));
			
			$data['username']	= $session['firstname'];
			$this->load->view('order/index',$data);
		}
	}

	function showIndex(){
		Engine::XmlHttpRequestOnly();
		(!$this->isUserLoggedIn() ? redirect("authenticate") : "");
		$session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
		$data['om_order']   = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 29));
		$this->load->view('order/tables/table_lists',$data);
	}

	function create(){
		Engine::XmlHttpRequestOnly();
		(!$this->isUserLoggedIn() ? redirect("authenticate") : "");
		$data['patients'] 		= $patients 	= Patients::findAll();
		$data['medicines'] 		= $medicines 	= Inventory::findAllActiveMedicines();//Inventory::findAllMedicines();
		$data['other_charges']	= $other_charges = Other_Charges::findAllActive();
		$data['doctors']	= Doctors::findAllActive();
		$this->load->view('order/forms/create',$data);
	}

	function getMedicineList(){
		Engine::XmlHttpRequestOnly();
		(!$this->isUserLoggedIn() ? redirect("authenticate") : "");
		$post = $this->input->post();
		$trimmed = trim($post['choice'], " \t.");
	
		if($trimmed == "RPP"){
			$data['medicines']  = $medicines= Inventory::findAllActiveRPPMedicines();
		}else{
			$data['medicines'] 		= $medicines 	= Inventory::findAllActiveRPCAlistMedicines();//Inventory::findAllMedicines();
		}
		$data['choice'] = $post['choice'];
		
		$this->load->view('order/forms/medicine_list',$data);
	}

	function view(){
		Engine::XmlHttpRequestOnly();
		(!$this->isUserLoggedIn() ? redirect("authenticate") : "");
		$post = $this->input->post();
		$order_id = $post['order_id'];
		$order = Orders::findById(array("id"=>$order_id));

		$session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
		$data['om_order']   = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 29));
		if($order){
			$data['order'] 		= $order;
			$data['patient'] 	= Patients::findById(array("id"=>$order['patient_id']));
			$data['photo'] 		= Patient_Photos::findbyId(array("patient_id" => $order['patient_id']));
			$order_meds			= Orders_Meds::findbyOrderId(array("order_id" => $order_id));
			$order_others 		= Orders_Others::findbyOrderId(array("order_id" => $order_id));

			if($order_meds){
				foreach ($order_meds as $key => $value) {
					$all_ids[] = $value['med_id'];
				}

				$med_ids = array_keys(array_flip($all_ids));
				foreach($med_ids as $key => $value){
					$medicine_quantity = 0;
					foreach ($order_meds as $k => $val) {
						if($value == $val['med_id']){
							$price 	= $val['price'];
							$medicine_quantity += $val['quantity'];
							if($val['id']){ $id 	= $val['id']; }
						}
					}
					$medic[] = array(
								"id"			=> $id,
								"medicine_id" 	=> $value,
								"quantity"		=> $medicine_quantity,
								"price"			=> $price,
								"total_price"	=> number_format($price * $medicine_quantity,2,".","")
							);
					unset($id);
				}

				
				foreach($medic as $key => $value){
					$medicine 		= Inventory::findById(array("id"=>$value['medicine_id']));
					$dosage_type 	= Dosage_Type::findById(array("id" => $medicine['dosage_type']));
		  			$qty_type 		= Quantity_Type::findById(array("id" => $medicine['quantity_type']));
		  			/* QUANTITY FOR INVENTORY */
			  			// $quantity   	= Inventory_Batch::computeTotalQuantity(array("id" => $medicine['id']));
			  			// $quantity 		= ($quantity ? $quantity : 0);
		  			/* END OF QUANTITY FOR INVENTORY */
					$meds[] = array(
							"medicine_name" => $medicine['medicine_name'],
							"dosage"		=> $medicine['dosage'] . " " . $dosage_type['abbreviation'],
							"quantity"		=> $value['quantity'] . " " .($value['quantity'] == 1 ? (substr($qty_type['abbreviation'], 0,-1)) : $qty_type['abbreviation']),
							"price"			=> $value['price'],
							"total_price"	=> $value['total_price'],
							"date_created"	=> $value['date_created']
						);
					$total_price += $value['total_price'];
				}
			}
			// debug_array($meds);

			if($order_others){
				foreach($order_others as $key => $value){
					$description = Other_Charges::findById(array("id" =>$value['desc_id']));
					$others[] = array(
							"description" => $description['r_centers'],
							"quantity"		=> $value['quantity'],
							"cost"			=> $value['cost'],
							"total_cost"	=> $value['total_cost'],
							"date_created"	=> $value['date_created']
						);
					$total_others_price += $value['total_cost'];
				}
			}

			$data['order_meds'] = $meds;
			$data['order_others'] = $others;
			$data['meds_total_price'] = number_format( (float) $total_price, 2, '.', ',');
			$data['others_total_price'] = number_format( (float) $total_others_price, 2, '.', ',');
			$this->load->view('order/view',$data);
		} else {
			$this->load->view('404');
		}
	}

	function update(){
		Engine::XmlHttpRequestOnly();
		(!$this->isUserLoggedIn() ? redirect("authenticate") : "");
		$post = $this->input->post();
		$order_id = $post['s_order_id'];

		$order = Orders::findById(array("id"=>$order_id));
		if($order){
			$data['order'] 		= $order;
			$data['patient'] 	= Patients::findById(array("id"=>$order['patient_id']));
			$order_meds			= Orders_Meds::findbyOrderId(array("order_id" => $order_id));
			$order_others 		= Orders_Others::findbyOrderId(array("order_id" => $order_id));
			if($order_meds){
				// debug_array($order_meds);
				foreach ($order_meds as $key => $value) {
					$all_ids[] = $value['med_id'];
				}

				$med_ids = array_keys(array_flip($all_ids));
				foreach($med_ids as $key => $value){
					$medicine_quantity = 0;
					foreach ($order_meds as $k => $val) {
						if($value == $val['med_id']){
							$price 	= $val['price'];
							$medicine_quantity += $val['quantity'];
							if($val['id']){ $id 	= $val['id']; }
						}
					}
					$medic[] = array(
								"id"			=> $id,
								"medicine_id" 	=> $value,
								"quantity"		=> $medicine_quantity,
								"price"			=> $price,
								"total_price"	=> number_format($price * $medicine_quantity,2,".","")
							);
					unset($id);
				}


				foreach($medic as $key => $value){
					$medicine 		= Inventory::findById(array("id"=>$value['medicine_id']));
					$dosage_type 	= Dosage_Type::findById(array("id" => $medicine['dosage_type']));
		  			$qty_type 		= Quantity_Type::findById(array("id" => $medicine['quantity_type']));
		  			/* QUANTITY FOR INVENTORY */
			  			// $quantity   	= Inventory_Batch::computeTotalQuantity(array("id" => $medicine['id']));
			  			// $quantity 		= ($quantity ? $quantity : 0);
		  			/* END OF QUANTITY FOR INVENTORY */
					$meds[] = array(
							"id"			=> $value['id'],
							"medicine_id"	=> $value['medicine_id'],
							"medicine_name" => $medicine['medicine_name'],
							"dosage"		=> $medicine['dosage'] . " " . $dosage_type['abbreviation'],
							"quantity"		=> $value['quantity'],
							"quantity_type"	=> $qty_type['abbreviation'],
							"price"			=> $value['price'],
							"total_price"	=> $value['total_price'],
							"date_created"	=> $value['date_created']
						);
					$total_price += $value['total_price'];
				}
			}

			if($order_others){
				foreach($order_others as $key => $value){
					$description = Other_Charges::findById(array("id" =>$value['desc_id']));
					$others[] = array(
							"id"			=> $value['id'],
							"description_id"=> $value['desc_id'],
							"description" 	=> $description['r_centers'],
							"quantity"		=> $value['quantity'],
							"cost"			=> $value['cost'],
							"total_cost"	=> $value['total_cost'],
							"date_created"	=> $value['date_created']
						);
					$total_others_price += $value['total_cost'];
				}
			}

			$data['order_meds'] = $meds;
			$data['order_others'] = $others;
			$data['meds_total_price'] = number_format( (float) $total_price, 2, '.', ',');
			$data['others_total_price'] = number_format( (float) $total_others_price, 2, '.', ',');
			$data['patients'] 		= $patients 	= Patients::findAll();
			$data['medicines'] 		= $medicines 	= Inventory::findAllActiveMedicines();//Inventory::findAllMedicines();
			$data['other_charges']	= $other_charges = Other_Charges::findAllActive();
			$data['doctors']	= Doctors::findAllActive();
		
			$this->load->view('order/forms/edit',$data);
		} else {
			$this->load->view('404');
		}
	}

	function saveOrder(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		//debug_array($post);
		if($post['patient_id'] && ($post['medicine'] || $post['additional'] || $post['others'])){

			if($post['order_id']){
				// UPDATE RECORD

				$order_id = $post['order_id'];

				$order = Orders::findById(array("id"=>$order_id));
				if($order){
					$order_no = $order['order_no'];
					$record = array(
						"estimated_date"=> $post['estimated_date'],
						"doc_attending_id"	=> $post['attending_doctor'],
						"date_updated" 	=> date("Y-m-d H:i:s"),
						"remarks"		=> $post['edit_remarks'],
						"status"		=> "Pending",
						"created_by"	=> $user_id
						);
					
					Orders::save($record,$order_id);
					$med_is_successful = false;
					if($post['medicine']){

						foreach ($post['medicine'] as $key => $value) {
							$all_ids[] = $value['medicine_id'];
						}

						$med_ids = array_keys(array_flip($all_ids));
						
						/* GET ALL THE NEEDEd DETAILS PER MED */
						foreach ($med_ids as $key => $value) {
							$medicine_quantity = 0;
							foreach ($post['medicine'] as $k => $val) {
								if($value == $val['medicine_id']){
									$price 	= $val['price'];
									$medicine_quantity += $val['quantity'];
									if($val['id']){ $id 	= $val['id']; }
								}
							}
							$medic[] = array(
										"id"			=> $id,
										"medicine_id" 	=> $value,
										"quantity"		=> $medicine_quantity,
										"price"			=> $price,
										"total_price"	=> number_format($price * $medicine_quantity,2,".","")
									);
							unset($id);
						}


						foreach ($medic as $key => $value) {
							//$order_item = Orders_Meds::findbyOrderIdandMedicineID(array("order_id" => $post['order_id'], "medicine_id" => $value['medicine_id']));
							//$current[$value['medicine_id']] = $order_item['quantity'];
							$order_item = Orders_Meds::findSumOrderIdandMedicineID(array("order_id" => $post['order_id'], "medicine_id" => $value['medicine_id']));
							$current[$value['medicine_id']] = $order_item['quantity'];

						}

						foreach ($medic as $key => $value) {

							$qty = $value['quantity'];
							$remaining	  = (int) $current[$value['medicine_id']] - (int) $qty;
							
							$tq = Inventory_Batch::computeTotalQuantity(array("id" => $value['medicine_id']));
							$tq = (int)$tq + (int)$current[$value['medicine_id']];

							$is_possible = (int) $tq >= $qty ? false: true;
							   if ($is_possible) {
								$medicine = Inventory::findById(array("id"=>$value['medicine_id']));
								$message = "We don't have enough stock for medicine : " . $medicine['medicine_name'];
								$med_is_successful =  false;
								break;		
							   }
							   
							if($remaining != 0){
									// RETURN BACK THE MEDICINE
									$first_batch = Inventory_Batch::findEarlirestMedicine(array("id"=>$value['medicine_id']));

									//Adding total quantity
									$tq_2 = Inventory_Batch::sumTotalQuantity(array("id" => $value['medicine_id']));

									$is_possible2 = (int) $tq_2 >= $value['quantity'] ? false: true;
									   if ($is_possible2) {
											//Di sapat ung total quantity
									   		$rec3 = array(
												"total_quantity" => ($value['quantity'] - $tq_2) + $tq_2
											);
											Inventory_Batch::save($rec3, $first_batch[$i]['id']);	
									   }

									$first_batch = Inventory_Batch::findEarlirestMedicine(array("id"=>$value['medicine_id']));

									$i = 0;
									if(empty($first_batch)){
										$first_batch = Inventory_Batch::findEarlirestMedicineZero(array("id"=>$value['medicine_id']));
										$rec2 = array(
											"total_quantity" => $first_batch[$i]['total_quantity'] + $value['quantity']
										);
										Inventory_Batch::save($rec2, $first_batch[$i]['id']);	
									}

									$new_qty = $current[$value['medicine_id']];
									
									while($i < count($first_batch)){
										
										$batch_qty = (int) $first_batch[$i]['quantity'];
										$available = (int) $first_batch[$i]['total_quantity'] - (int) $first_batch[$i]['quantity'];
											
											if($new_qty < 0){
												$new_qty= str_replace('-', '', $new_qty);
											}

											$new_qty = $available - $new_qty;

											$quantity = $new_qty >= 0 ? ($available - $new_qty) + $batch_qty : $batch_qty + $available; 

											$rec = array(
												"quantity" => $quantity,
											);
											Inventory_Batch::save($rec, $first_batch[$i]['id']);	

											/*$stock = array(
												"item_id"		=> $value['medicine_id'],
												"patient_id"	=> $returns['patient_id'],
												"quantity"		=> $quantity,
												"reason"		=> "Returned Item in Batch No."."".$first_batch[$i]['batch_no'],
												"created_by"	=> $user_id,
												"created_at"	=> date("Y-m-d H:i:s")
												);
											Stock::save($stock);	*/

										$i++;
										if($new_qty >= 0){
											break;
										}
									}	// end while
									
									
									//  Create new
									$total = Inventory_Batch::computeTotalQuantity(array("id" => $value['medicine_id']));
									$is_possible = (int) $total >= $value['quantity'] ? true: false;
								
								if($is_possible){
									// Delete record and 
									Orders_Meds::deleteOrderMed(array("order_id"=> $post['order_id'], "med_id" => $value['medicine_id']));
									//Reserved_Meds::deleteReserveMed(array("order_id"=> $post['order_id'], "med_id" => $value['medicine_id']));;

									/*$reserved_med = Reserved_Meds::findAllByOrderId(array("order_id"=> $post['order_id']));

									foreach ($reserved_med as $key => $a) {
										$rec = array(
												"taken" => 2,
											);
										Reserved_Meds::save($rec, $a['id']);
				
									}*/
									$medicine 	= Inventory::findById(array("id"=>$value['medicine_id']));
									$batches 	= Inventory_Batch::findEarliestBatch($value['medicine_id']);

									$qty = $value['quantity'];

									$i = 0;
									while($i < count($batches)){

										$batch_qty = (int) $batches[$i]['quantity'];
										$a = $qty >= $batch_qty ? $batch_qty - $batch_qty : $batch_qty - $qty;

										$rec = array(
												"quantity" => $a
											);
										
										Inventory_Batch::save($rec, $batches[$i]['id']);
										$less_qty = ($a == 0 ? $a + $batch_qty : $batch_qty - $a);
										
										$reserve = array(
												"med_id" 		=> $batches[$i]['main_med_id'],
												"batch_id" 		=> $batches[$i]['id'],
												"order_id"		=> $order_id,
												"quantity"		=> $less_qty,
												"price"			=> $value['price'],
												"total_price"	=> $less_qty * $value['price'],
												"taken"			=> 0,
												"date_created"	=> date("Y-m-d H:i:s"),
												"date_updated"	=> date("Y-m-d H:i:s"),
												"created_by"	=> $user_id
											);
										Reserved_Meds::save($reserve);

										$order = array(
												"med_id" 		=> $batches[$i]['main_med_id'],
												"batch_id" 		=> $batches[$i]['id'],
												"order_id"		=> $order_id,
												"quantity"		=> $less_qty,
												"price"			=> $value['price'],
												"total_price"	=> $less_qty * $value['price'],
												"date_created"	=> date("Y-m-d H:i:s"),
												"date_updated"	=> date("Y-m-d H:i:s"),
												"created_by"	=> $user_id
											);
										Orders_Meds::save($order);

										$qty = $qty - $less_qty;

										$i++;
										if($qty <= 0){
											break;
										}

									} // end while
									
									$sum = Inventory_Batch::computeTotalQuantity(array("id" => $value['medicine_id']));
									$main_inv = array(
											"total_quantity" => $sum
										);
									Inventory::save($main_inv, $value['medicine_id']);

									$med_is_successful =  true;
								} else {
									$medicine = Inventory::findById(array("id"=>$value['medicine_id']));
									$message = "We don't have enough stock for medicine : " . $medicine['medicine_name'];
									$med_is_successful =  false;
									break;
								}	
							}else{
								$med_is_successful =  true;
							}
						}

					} // end of $post['medicine']


					if($post['additional']){

						foreach ($post['additional'] as $key1 => $value1) {
							$additional = array(
								"order_id" 		=> $order_id,
								"desc_id"		=> $value1['description_id'],
								"quantity"		=> $value1['quantity'],
								"cost"			=> $value1['cost_per_item'],
								"total_cost"	=> $value1['cost'],
								"date_created" 	=> date("Y-m-d H:i:s"),
								"date_updated" 	=> date("Y-m-d H:i:s"),
								"created_by"	=> $user_id
								);
							Orders_Others::save($additional);
						}

					} // end of $post['additional']

					if($post['others']){
						foreach ($post['others'] as $key1 => $value1) {
							$others = array(
								"order_id" 		=> $order_id,
								"desc_id"		=> $value1['description_id'],
								"quantity"		=> $value1['quantity'],
								"cost"			=> $value1['cost'],
								"total_cost"	=> $value1['total_cost'],
								"date_updated" 	=> date("Y-m-d H:i:s"),
								"created_by"	=> $user_id
								);
							Orders_Others::save($others,$value1['id']);
						}
					}
					$user = User::findById(array("id"=>$user_id));
						$act_tracker = array(
						"module"		=> "rpc_orders",
						"user_id"		=> $user_id,
						"entity_id"		=> "0",
						"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "has updated {$order_no}",
						"date_created" 	=> date("Y-m-d H:i:s"),
						);
					Activity_Tracker::save($act_tracker);

					if($med_is_successful AND $post['medicine'] OR $post['others'] OR $post['additional'] ){
						$json['message'] = "Successfully Updated Order " . $order_no;
						$json['order_id'] = $order_id;
						$json['is_successful'] = true;
						/*$json['notif_title'] 	= "Updated " . $order_no;
						$json['notif_type']		= "info";
						$json['notif_message']	= $session['name'] . " has Successfully Updated {$order_no}. ";*/

						//New Notification
						$msg = $session['name'] . " has successfully Updated Order: {$order_no}.";

						$this->pusher->trigger('my_notifications', 'notification', array(
							'message' => $msg,
							'title' => "Updated Order " . $order_no,
							'type' => 'info'
						));

					} elseif(!$med_is_successful AND !isset($post['medicine'])){	
						$json['message'] = "Successfully Updated Order " . $order_no;
						$json['order_id'] = $order_id;
						$json['is_successful'] = true;
						/*$json['notif_title'] 	= "Updated " . $order_no;
						$json['notif_type']		= "info";
						$json['notif_message']	= $session['name'] . " has Successfully Updated {$order_no}. ";*/

						//New Notification
						$msg = $session['name'] . " has successfully Updated Order: {$order_no}.";

						$this->pusher->trigger('my_notifications', 'notification', array(
							'message' => $msg,
							'title' => "Updated Order " . $order_no,
							'type' => 'info'
						));
					} else { 
						$json['message'] = $message;
						$json['order_id'] = $order_id;
						$json['is_successful'] = false;
						/*$json['message'] = "Successfully Updated Order " . $order_no;
						$json['order_id'] = $order_id;
						$json['is_successful'] = true;
						$json['notif_title'] 	= "Updated " . $order_no;
						$json['notif_type']		= "info";
						$json['notif_message']	= $session['name'] . " has Successfully Updated Regimen {$order_no}. ";*/
						
					}
					
				} else {
					$json['message'] = "Error saving to database. Please try again later.";
					$json['is_successful'] = false;
				}

			} else {
				// NEW RECORD
				
				
				$order_no = Orders::generateOrderID();

				$record = array(
					"order_no" 		=> $order_no,
					"patient_id" 	=> $post['patient_id'],
					"estimated_date"=> $post['estimated_date'],
					"doc_attending_id"	=> $post['attending_doctor'],
					"pharmacy"		=> $post['pharma'],
					"remarks"		=> $post['remarks'],
					"date_created" 	=> date("Y-m-d H:i:s"),
					"date_updated" 	=> date("Y-m-d H:i:s"),
					"status"		=> "New",
					"created_by"	=> $user_id
					);
				//debug_array($record);
				$order_id = Orders::save($record);

				if($post['medicine']){
					foreach ($post['medicine'] as $key => $value) {
						$med_ids[] = $value['medicine_id'];
					}

					$unique_ids = array_unique($med_ids);

					/* GET ALL THE NEEDEd DETAILS PER MED */
					foreach ($unique_ids as $key => $value) {
						$medicine_quantity = 0;
						foreach ($post['medicine'] as $k => $val) {
							if($value == $val['medicine_id']){
								$price = $val['price'];
								$medicine_quantity += $val['quantity'];
							}
						}
						$medic[] = array(
									"medicine_id" 	=> $value,
									"quantity"		=> $medicine_quantity,
									"price"			=> $price,
									"total_price"	=> number_format($price * $medicine_quantity,2,".","")
								);

					}
					/* END OF GET ALL NEEDEd DETAILS */
					
					foreach ($medic as $key => $value) {
						$total = Inventory_Batch::computeTotalQuantity(array("id" => $value['medicine_id']));
					$is_possible = (int) $total >= $value['quantity'] ? false: true;
					   if ($is_possible) {
						$medicine = Inventory::findById(array("id"=>$value['medicine_id']));
						$message = "We don't have enough stock for medicine : " . $medicine['medicine_name'];
						$med_is_successful =  false;
						break;		
					   }else {
					  	$med_is_successful =  true;
						$medicine 	= Inventory::findById(array("id"=>$value['medicine_id']));
						$batches 	= Inventory_Batch::findEarliestBatch($value['medicine_id']);

						$qty = $value['quantity'];

						$i = 0;
						while($i < count($batches)){
							$batch_qty = (int) $batches[$i]['quantity'];
							$a = $qty >= $batch_qty ? $batch_qty - $batch_qty : $batch_qty - $qty;
							$rec = array(
									"quantity" => $a
								);
							
							Inventory_Batch::save($rec, $batches[$i]['id']);
							$less_qty = ($a == 0 ? $a + $batch_qty : $batch_qty - $a);
							
							$reserve = array(
									"med_id" 		=> $batches[$i]['main_med_id'],
									"batch_id" 		=> $batches[$i]['id'],
									"order_id"		=> $order_id,
									"quantity"		=> $less_qty,
									"price"			=> $value['price'],
									"total_price"	=> $less_qty * $value['price'],
									"taken"			=> 0,
									"date_created"	=> date("Y-m-d H:i:s"),
									"date_updated"	=> date("Y-m-d H:i:s"),
									"created_by"	=> $user_id
								);
							Reserved_Meds::save($reserve);

							$order = array(
									"med_id" 		=> $batches[$i]['main_med_id'],
									"batch_id" 		=> $batches[$i]['id'],
									"order_id"		=> $order_id,
									"quantity"		=> $less_qty,
									"price"			=> $value['price'],
									"total_price"	=> $less_qty * $value['price'],
									"date_created"	=> date("Y-m-d H:i:s"),
									"date_updated"	=> date("Y-m-d H:i:s"),
									"created_by"	=> $user_id
								);
							Orders_Meds::save($order);

							$qty = $qty - $less_qty;
							$i++;
							if($qty <= 0){
								break;
							}

						} // end while
						$sum = Inventory_Batch::computeTotalQuantity(array("id" => $value['medicine_id']));
						$main_inv = array(
								"total_quantity" => $sum
							);
						Inventory::save($main_inv, $value['medicine_id']);
					  	
						}	
						/*$medicine 	= Inventory::findById(array("id"=>$value['medicine_id']));
						$batches 	= Inventory_Batch::findEarliestBatch($value['medicine_id']);

						$qty = $value['quantity'];

						$i = 0;
						while($i < count($batches)){
							$batch_qty = (int) $batches[$i]['quantity'];
							$a = $qty >= $batch_qty ? $batch_qty - $batch_qty : $batch_qty - $qty;
							$rec = array(
									"quantity" => $a
								);
							
							Inventory_Batch::save($rec, $batches[$i]['id']);
							$less_qty = ($a == 0 ? $a + $batch_qty : $batch_qty - $a);
							
							$reserve = array(
									"med_id" 		=> $batches[$i]['main_med_id'],
									"batch_id" 		=> $batches[$i]['id'],
									"order_id"		=> $order_id,
									"quantity"		=> $less_qty,
									"price"			=> $value['price'],
									"total_price"	=> $less_qty * $value['price'],
									"taken"			=> 0,
									"date_created"	=> date("Y-m-d H:i:s"),
									"date_updated"	=> date("Y-m-d H:i:s"),
									"created_by"	=> $user_id
								);
							Reserved_Meds::save($reserve);

							$order = array(
									"med_id" 		=> $batches[$i]['main_med_id'],
									"batch_id" 		=> $batches[$i]['id'],
									"order_id"		=> $order_id,
									"quantity"		=> $less_qty,
									"price"			=> $value['price'],
									"total_price"	=> $less_qty * $value['price'],
									"date_created"	=> date("Y-m-d H:i:s"),
									"date_updated"	=> date("Y-m-d H:i:s"),
									"created_by"	=> $user_id
								);
							Orders_Meds::save($order);

							$qty = $qty - $less_qty;
							$i++;
							if($qty <= 0){
								break;
							}

						} // end while
						$sum = Inventory_Batch::computeTotalQuantity(array("id" => $value['medicine_id']));
						$main_inv = array(
								"total_quantity" => $sum
							);
						Inventory::save($main_inv, $value['medicine_id']);*/
					} // end foreach

				} // end of $post['medicine']
				
				if($post['additional']){

					// Below are the codes for getting the unique id for descrpition id
					// but since I can't do it now due to different prices even if same id
					// I will just ready this and won't delete it if changes may occur in the future.

					/*foreach ($post['additional'] as $key1 => $value1) {
						$desc_ids[] = $value1['description_id'];
					}

					$unique_desc_ids = array_unique($desc_ids);


					foreach ($unique_desc_ids as $key => $value) {
						$desc_quantity = 0;
						foreach ($post['additional'] as $k => $val) {
							if($value == $val['description_id']){
								$cost = $val['cost'];
								$desc_quantity += $val['quantity'];
							}
						}
						$descriptions[] = array(
									"description_id" 	=> $value,
									"quantity"			=> $desc_quantity,
									"cost"				=> $cost,
									"total_cost"		=> number_format($cost * $desc_quantity,2,".","")
								);

					}

					debug_array($descriptions);*/

					foreach ($post['additional'] as $key1 => $value1) {
						$additional = array(
							"order_id" 		=> $order_id,
							"desc_id"		=> $value1['description_id'],
							"quantity"		=> $value1['quantity'],
							"cost"			=> $value1['cost_per_item'],
							"total_cost"	=> $value1['cost'],
							"date_created" 	=> date("Y-m-d H:i:s"),
							"date_updated" 	=> date("Y-m-d H:i:s"),
							"created_by"	=> $user_id
							);
						Orders_Others::save($additional);
					}

				} // end of $post['additional']

				$user = User::findById(array("id"=>$user_id));
					$act_tracker = array(
					"module"		=> "rpc_orders",
					"user_id"		=> $user_id,
					"entity_id"		=> "0",
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "has Added {$order_no}",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				/*$json['message'] = "Successfully Created new Order " . $order_no;
				$json['is_successful'] = true;
				$json['notif_title'] 	= "Updated " . $order_no;
				$json['notif_type']		= "info";
				$json['notif_message']	= $session['name'] . " has Successfully Added Regimen {$order_no}. ";*/
				if($med_is_successful AND $post['medicine']){
					$json['message'] = "Successfully Created new Order " . $order_no;
					$json['is_successful'] = true;
					/*$json['notif_title'] 	= "Updated " . $order_no;
					$json['notif_type']		= "info";
					$json['notif_message']	= $session['name'] . " has Successfully Added {$order_no}. ";*/

					//New Notification
					$msg = $session['name'] . " has successfully Added Order: {$order_no}.";

					$this->pusher->trigger('my_notifications', 'notification', array(
						'message' => $msg,
						'title' => "Added Order " . $order_no,
						'type' => 'info'
					));

				} elseif(!$med_is_successful AND !isset($post['medicine'])){
					$json['message'] = "Successfully Created new Order " . $order_no;
					$json['is_successful'] = true;
					/*$json['notif_title'] 	= "Updated " . $order_no;
					$json['notif_type']		= "info";
					$json['notif_message']	= $session['name'] . " has Successfully Added {$order_no}. ";*/

					//New Notification
					$msg = $session['name'] . " has successfully Added Order: {$order_no}.";

					$this->pusher->trigger('my_notifications', 'notification', array(
						'message' => $msg,
						'title' => "Added Order " . $order_no,
						'type' => 'info'
					));

				} else {
					$json['message'] = $message;
					$json['is_successful'] = false;
				}
			}
			
		} else {
			$json['message'] = "Error saving to database. Please try again later.";
			$json['is_successful'] = false;
			
		}

		echo json_encode($json);
	}

	function orderCreateFields(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		$medicine_id 	= $post['medicine_id'];
		$main_index 	= $post['med_ctr'];

		if($medicine_id && $main_index != ""){
			$medicines = Inventory::findById(array("id"=>$medicine_id));
			if($medicines){

				$medicine_name 	= $medicines['medicine_name'];
				$medicine_id 	= $medicines['id'];
				$dosage		 	= $medicines['dosage'];
				$price		 	= $medicines['price'];
				$dosage_type	= Dosage_Type::findById(array("id" => $medicines['dosage_type']));
				$dosage_abbr	= $dosage_type['abbreviation'];

				$div_wrapper 			= "medicine_wrapper_{$main_index}";

				$field_medicine_id 		= "medicine[{$main_index}][medicine_id]";
				$field_medicine_id 		= "medicine[{$main_index}][medicine_id]";
				$field_quantity 		= "medicine[{$main_index}][quantity]";
				$field_price 			= "medicine[{$main_index}][price]";
				$field_total_price		= "medicine[{$main_index}][total_price]";

				$field_quantity_id 			= "medicine_quantity_{$main_index}";
				$field_quantity_class 		= "medicine_quantity_class";
				$field_price_id 			= "medicine_price_{$main_index}";
				$field_total_price_id 		= "medicine_total_price_{$main_index}";
				$field_total_price_class 	= "medicine_total_price_class";
				$span_total_price 			= "span_medicine_total_price_{$main_index}";

				$object['html'] .= "
					<div class='minibox {$div_wrapper}'>
						<span id='button-box' onclick='javascript: removeMedicine(\"{$div_wrapper}\")'>X</span> 
						<span id='med01'>
							<span class='title'><input type='hidden' name='{$field_medicine_id}' value='{$medicine_id}'>{$medicine_name} / {$dosage} {$dosage_abbr}</span>
							<br><br>&nbsp;&nbsp;&nbsp;
							<span>Quantity: <input type='text' data-ctr='{$main_index}' name='{$field_quantity}' id='{$field_quantity_id}' class='validate[required, custom[onlyNumberSp]] textbox {$field_quantity_class}' style='width: 30px; text-align: center !important;' value='0'> / <input type='hidden' class='textbox' name='{$field_price}' id='{$field_price_id}' value='{$price}'> P {$price}</span>
							<span class='price'>Php&nbsp;
								<span class='pricecolor'><input type='hidden' class='textbox {$field_total_price_class}' name='{$field_total_price}' id='{$field_total_price_id}' value='0'><span id='{$span_total_price}'>0.00</span></span>
							</span>
						</span>
					</div>
				";
			} else {
				$object['errors'] = "We can't find the requested medicine.";
			}
			
		} else {
			$object['errors'] = "Error Adding new Field!";
		}

		echo json_encode($object);
		
	}


	function getAllOrdersList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$im_list 		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 13));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			// $order_type	= strtoupper($get['sSortDir_0']);
			$order_type	= strtoupper("desc");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "a.order_no",
				1 => "b.patient_name",
				2 => "a.status",
				3 => "a.date_created",
				4 => "a.id"
			);

			$fields = array("a.id", "a.order_no", "b.patient_name", "a.patient_id", "a.status", "a.date_created");

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$orders 		= Orders::generateOrdersDatatable($params);
			$total_records 	= Orders::countOrdersDatatable($params);
			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);

			// debug_array($orders);
			foreach($orders as $key=>$value):

				$view_link = '<a href="javascript: void(0);" onclick="javascript: loadView('. $value['id'] .')">' . $value['order_no'] . '</a>';

				$row = array(
					'0' => $view_link,
					'1' => $value['patient_name'],
					'2' => $value['status'],
					'3' => $value['date_created'],
					// '4' => $value['dosage'] . " " . $value['abbr2'],
				);
				$output['aaData'][] = $row;

			endforeach;
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

	function download_pdf_summary(){
		$order_id = $this->uri->segment(3);
		if($order_id){
			$data['orders']	= $orders		 = Orders::findById(array("id" => $order_id));
			$data['patient'] = $patient = Patients::findById(array("id" => $orders['patient_id']));
			$data['medicine'] = $medicine = Orders_Meds::findbyOrderId(array("order_id" => $order_id));
			//$data['medicine'] = $medicine = Orders_Meds::findbyOrderId2(array("id" => $order_id));	
			$data['others'] = $others = Orders_Others::findbyOrderId2(array("id" => $order_id));
			$data['dosage'] = $dosage = Dosage_Type::findbyId(array("id" => $medicine['dosage_type']));
			$data['pharmacy'] = $trimmed = trim($orders['pharmacy'], " \t.");
		}
		
		$this->load->library('tcpdf/tcpdf');
		$this->load->view('order/pdf/print_summary',$data);
	}

	function delete(){
		Engine::XmlHttpRequestOnly();
		(!$this->isUserLoggedIn() ? redirect("authenticate") : "");
		$post = $this->input->post();
		$order = Orders::findById2(array("id" => $post['s_order_id'] ));
		//$invoice = Invoice::findByOrderId(array("id" => $post['s_order_id'] ));
		//$patient = Patients::findbyId(array("id" => $invoice['patient_id'] ));
	
		if($order){
			/*$data['invoice'] = $invoice;
			$data['patient'] = $patient;*/
			$data['order'] = $order;
			$data['allowed'] = "allowed";
		}else{
			$data['allowed'] = "notallowed";
			$data['message'] = "Sorry you cannot delete this order. Please contact your administrator.";
		}
		$this->load->view("order/forms/delete", $data);
	}

	function deleteOrdersRecord(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);

		$post = $this->input->post();

		$order_id = $post['order_id'];
		$order = Orders::findById(array("id" => $order_id));
		if($order_id){
		 /*$meds 			 = Orders_Meds::findAllByOrderId(array("id" => $order_id));
			
		  foreach ($meds as $key => $value) {
		  	$first_batch = Inventory_Batch::findLatestBatch(array("id"=>$value['med_id']));

		  	if(empty($first_batch)){
				$first_batch = Inventory_Batch::findLatestEqualBatch(array("id"=>$value['med_id']));	
			}

			$batch_qty = (int) $first_batch['quantity'];
			$available = (int) $first_batch['total_quantity'] - (int) $first_batch['quantity'];

				$qty = (int) $batch_qty + (int) $value['quantity'];
				
				$rec = array(
					"total_quantity" => (int) $qty, 
					"quantity" => (int) $qty, 
				);
				Inventory_Batch::save($rec, $first_batch['id']);

				$stock = array(
					"item_id"		=> $value['med_id'],
					"patient_id"	=> $order['patient_id'],
					"quantity"		=> $value['quantity'],
					"reason"		=> "Returned Item in Batch No."."".$first_batch['batch_no'],
					"created_by"	=> $user_id,
					"created_at"	=> date("Y-m-d H:i:s")
					);
				Stock::save($stock);
		  }
		   if(!empty($meds)){
		  		$sum = Inventory_Batch::computeTotalQuantity(array("id" => $value['med_id']));
						$main_inv = array(
								"total_quantity" => $sum
							);
						Inventory::save($main_inv, $value['med_id']);
			}
				$user = User::findById(array("id"=>$user_id));
						$act_tracker = array(
						"module"		=> "rpc_orders",
						"user_id"		=> $user_id,
						"entity_id"		=> "0",
						"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted {$order['order_no']}",
						"date_created" 	=> date("Y-m-d H:i:s"),
					);
				Activity_Tracker::save($act_tracker);
				
				Orders::delete($order_id);
				Reserved_Meds::delete_reserved($order_id);	

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Deleted in database";
		*/		/*$json['notif_title'] 	= "Updated " . $order_no;
				$json['notif_type']		= "info";
				$json['notif_message']	= $session['name'] . " has Successfully Deleted {$order['order_no']}. ";*/

				//New Notification
				/*$msg = $session['name'] . " has successfully Deleted Order: {$order['order_no']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Deleted Order " . $order['order_no'],
					'type' => 'info'
				));
			*/
		$meds 			 = Orders_Meds::findAllByOrderId(array("id" => $order_id));
			
		  foreach ($meds as $key => $value) {

				$i = 0;
				$first_batch = Inventory_Batch::findEarlirestMedicine(array("id"=>$value['med_id']));
					//Adding total quantity
					$total_available =  $first_batch[$i]['total_quantity'] -  $first_batch[$i]['quantity'];
					$k = $first_batch[$i]['total_quantity']- $total_available;
					$is_possible = (int) $total_available >= $value['quantity'] ? false: true;

					   if ($is_possible) {
							//Di sapat ung total quantity
					   		$rec2 = array(
								"total_quantity" => $first_batch[$i]['total_quantity'] + $k
							);
							
							Inventory_Batch::save($rec2, $first_batch[$i]['id']);	
					   }

					if(empty($first_batch)){
						$first_batch = Inventory_Batch::findEarlirestMedicineZero(array("id"=>$value['med_id']));
						$rec2 = array(
								"total_quantity" => $first_batch[$i]['total_quantity'] + $value['quantity']
							);
							Inventory_Batch::save($rec2, $first_batch[$i]['id']);	
					}
					
					$new_qty = $value['quantity'];
					$first_batch = Inventory_Batch::findEarlirestMedicine(array("id"=>$value['med_id']));
					
					while($i < count($first_batch)){
	
						$batch_qty = (int) $first_batch[$i]['quantity'];
						$available = (int) $first_batch[$i]['total_quantity'] - (int) $first_batch[$i]['quantity'];
							
							if($new_qty < 0){
								$new_qty= str_replace('-', '', $new_qty);
							}

							$new_qty = $available - $new_qty;

							$quantity = $new_qty >= 0 ? ($available - $new_qty) + $batch_qty : $batch_qty + $available; 

							$rec = array(
								"quantity" => $quantity,
							);
							Inventory_Batch::save($rec, $first_batch[$i]['id']);	

							$stock = array(
								"item_id"		=> $value['med_id'],
								"patient_id"	=> $order['patient_id'],
								"quantity"		=> $value['quantity'],
								"reason"		=> "Returned Item in Batch No."."".$first_batch[$i]['batch_no'],
								"created_by"	=> $user_id,
								"created_at"	=> date("Y-m-d H:i:s")
								);
							Stock::save($stock);	

						$i++;
						if($new_qty >= 0){
							break;
						}
					}	// end while
					$sum = Inventory_Batch::computeTotalQuantity(array("id" => $value['med_id']));
					$main_inv = array(
							"total_quantity" => $sum
						);
					Inventory::save($main_inv, $value['med_id']);
			}
		 
				$user = User::findById(array("id"=>$user_id));
						$act_tracker = array(
						"module"		=> "rpc_orders",
						"user_id"		=> $user_id,
						"entity_id"		=> "0",
						"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted {$order['order_no']}",
						"date_created" 	=> date("Y-m-d H:i:s"),
					);
				Activity_Tracker::save($act_tracker);
				
				Orders::delete($order_id);
				Reserved_Meds::delete_reserved($order_id);	

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Deleted in database";
				
				//New Notification
				$msg = $session['name'] . " has successfully Deleted Order: {$order['order_no']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Deleted Order " . $order['order_no'],
					'type' => 'info'
				));
		}else{
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}

	function deleteOtherCharges(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		$id = $post['id'];

		if($id){
			Orders_Others::delete(array("id" => $id));

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted in database";
		}else{
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}
	function deleteOrderMedicine(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		$id = $post['id'];

		$orders  = Orders_Meds::findById(array("id" => $id));
		$main_order = Orders::findById(array("id" => $orders['order_id']));

		if($id){
			//$meds = Orders_Meds::findAllByOrderId(array("id" => $orders['order_id']));
			$meds = Orders_Meds::findById(array("id" => $post['id']));
			
			  //foreach ($meds as $key => $value) {
			  	$first_batch = Inventory_Batch::findLatestBatch(array("id"=>$meds['med_id']));
			  	#debug_array($value);
				$batch_qty = (int) $first_batch['quantity'];
				$available = (int) $first_batch['total_quantity'] - (int) $first_batch['quantity'];
				$inv = Inventory::findById(array("id"=>$meds['med_id']));

					//Adding total quantity
						$tq_2 = Inventory_Batch::sumTotalQuantity(array("id" => $meds['med_id']));

						$is_possible2 = (int) $tq_2 >= $meds['quantity'] ? false: true;
						   if ($is_possible2) {
								//Di sapat ung total quantity
						   		$rec3 = array(
									"total_quantity" => ($meds['quantity'] - $tq_2) + $tq_2
								);
								Inventory_Batch::save($rec3, $first_batch['id']);	
						   }


					$qty = (int) $batch_qty + (int) $meds['quantity'];
					
					$rec = array(
						"quantity" => (int) $qty, 
					);
					Inventory_Batch::save($rec, $first_batch['id']);

					$stock = array(
						"item_id"		=> $meds['med_id'],
						"patient_id"	=> $main_order['patient_id'],
						"quantity"		=> $meds['quantity'],
						"reason"		=> "Returned Item in Batch No."."".$first_batch['batch_no'],
						"created_by"	=> $user_id,
						"created_at"	=> date("Y-m-d H:i:s")
						);
					Stock::save($stock);
			  //}
			  		$sum = Inventory_Batch::computeTotalQuantity(array("id" => $orders['med_id']));
							$main_inv = array(
									"total_quantity" => $sum
								);
							Inventory::save($main_inv, $orders['med_id']);
				
					$user = User::findById(array("id"=>$user_id));
							$act_tracker = array(
							"module"		=> "rpc_orders",
							"user_id"		=> $user_id,
							"entity_id"		=> "0",
							"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted Medicine: {$inv['medicine_name']}" ." ". "in {$main_order['order_no']}",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
					Activity_Tracker::save($act_tracker);
					
					Orders_Meds::deleteOrderMed(array("order_id"=> $orders['order_id'], "med_id" => $orders['med_id']));
					Reserved_Meds::deleteReserveMed(array("order_id"=> $orders['order_id'], "med_id" => $orders['med_id']));;

					//Orders::delete($id);
					//Reserved_Meds::delete_reserved($order_id);	
					
				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Deleted in database";
		}else{
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}
		/*$post = $this->input->post();
		$id = $post['id'];
		if($id){
				Orders_Meds::delete($id);
				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Deleted in database";
		}else{
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}*/
}