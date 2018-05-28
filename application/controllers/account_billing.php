<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Billing extends MY_Controller {
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

		Engine::appScript('accounting.js');
		Engine::appScript('topbars.js');
		Engine::appScript('profile.js');
		Engine::appScript('confirmation.js');
		Engine::appScript('blockUI.js');

		/* NOTIFICATIONS */
		Jquery::pusher();
		Jquery::gritter();
		Jquery::pnotify();
		Engine::appScript('notification.js');
		/* END */

		Jquery::jBox();
		Jquery::select2();
		Jquery::datatable();
		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();
		Jquery::numberformat();
		Jquery::mask();

		Bootstrap::datepicker();

		$data['page_title'] = "Account & Billing";
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
		$this->load->view('accounting/index',$data);
		}
	}


	/* START LOAD PAGE */

	function getIndex(){
		Engine::XmlHttpRequestOnly();
		$this->load->view('accounting/view/sales_report',$data);
	}

	function getAccountReceivables(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));

		$data['invoicing'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 28));
		$this->load->view('accounting/view/receivables',$data);
	}

	function getCollections(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));

		$data['invoicing'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 28));
		$this->load->view('accounting/view/collections',$data);
	}

	function loadORModal(){
		Engine::XmlHttpRequestOnly();
		$data['invoice'] = Invoice::findAllByReceivableStatus();
		//debug_array($data);
		$this->load->view('accounting/modal/official_receipt_invoice',$data);
	}

	function loadORAddForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$invoice_id = $post['invoice_id'];
		$invoice = Invoice::findById(array("id" => $invoice_id));
		if($invoice){
			$json['is_successful'] = true;
			$json['invoice_id'] = $invoice['id'];
		} else {
			$json['is_successful'] = false;
		}
		echo json_encode($json);
	}

	function getORAddForm(){
		$post = $this->input->post();
		$invoice_id = $post['invoice_id'];
		$invoice = Invoice::findById(array("id" => $invoice_id));

		/*$invoice_meds = Invoice_Med::findInvoiceId(array("id" => $invoice_id));
		if(empty($invoice_meds)){
			$meds = Reserved_Meds::findAllByOrderId(array("id" => $invoice['order_id']));
				foreach ($meds as $key => $value) {
					$medicine = array(
						"invoice_id" 		=> $invoice['id'],
						"type"		 		=> "RPC",
						"medicine_id" 		=> $value['med_id'],
						"batch_id"			=> $value['batch_id'],
						"quantity" 			=> $value['quantity'],
						"price" 			=> (float) $value['price'],
						"total_price" 		=> (float) $value['total_price'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $session_id,
						);
					Invoice_Med::save($medicine);
					unset($medicine);
				}
			}
*/
		if($invoice){
			
			$regimen_id = $invoice['regimen_id'];
			$version_id = $invoice['version_id'];
			

			$data['invoice'] = $invoice;
			$data['regimen'] = $regimen = Regimen::findById(array("id"=>$regimen_id));
			$data['version'] = $version = Regimen_Version::findByRegimenVersionId(array("regimen_id" => $regimen_id, "version_id" => $version_id));
 			
 			/* Patient */
 			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $invoice['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $invoice['patient_id']));

 			$this->load->view('accounting/receipts/add',$data);
		} else {
			$this->load->view('404');
		}
	}

	function getSalesReportFormDatatable(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['post'] = $post;
		$this->load->view('accounting/view/sub_sales_report',$data);
	}

	function getInvoice(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$invoice_id = $post['invoice_id'];
		$invoice = Invoice::findById(array("id"=>$invoice_id));

		$data['session'] 	= $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['accounting'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 22));
			
		if($invoice){
			$data['invoice'] = $invoice;

			$due = date('Y/m/d', strtotime($invoice['date_claimed']));
			$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));
			$due_date = $invoice['date_claimed'] == '0000-00-00 00:00:00' ? '0000-00-00' : $real_duedate;
					
			$data['due_date'] =  $due_date;

			$created = date('Y-m-d', strtotime($invoice['date_created']));
			$row_date = strtotime($created);
			//$today = strtotime(date('Y-m-d'));
			$today = "1506441600";


			$data['order']	 = $order = Orders::findById(array("id" => $invoice['order_id']));
			$meds 			 = Reserved_Meds::findAllByOrderId(array("id" => $invoice['order_id']));
			//$orders 		 = Orders_Meds::findAllbyOrderId(array("id" => $invoice['order_id']));

			if($invoice['status'] == 'Void' &&  $row_date > $today){
				$orders 		= Reserved_Meds::findAllByOrderId2(array("id" => $invoice['order_id']));
 			}else{
 				$orders 		= Orders_Meds::findAllbyOrderId(array("id" => $invoice['order_id']));
 			}

			//$meds = Invoice_Med::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['other_charges'] = $other_charges = Invoice_Other_Charges::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['cost_modifier'] = $cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['collections']   = $collections 	= Invoice_Receipts::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['returns']	   = $returns		= Returns::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['doctor'] = $doctor = Doctors::findById(array("id" => $order['doc_attending_id']));
			/*foreach ($meds as $key => $value) {
				$array[] = $value['order_id'];
				$array1[] = $value['med_id'];
			}
			$data['meds_summary'] = $meds_summary = array_unique($array);
			$data['meds_summary'] = $meds_summary1 = array_unique($array1);
			
			foreach ($meds_summary as $a => $b) {
				foreach ($meds_summary1 as $c => $d) {*/
			foreach ($orders as $key => $value) {
					$all_ids[] = $value['med_id'];
				}

				$med_ids = array_keys(array_flip($all_ids));

				foreach($med_ids as $key => $value){
					$medicine_quantity = 0;
					foreach ($orders as $k => $val) {
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
					$inv = Inventory::findById(array("id" => $value['medicine_id']));			
					$dosage = Dosage_Type::findById(array("id" => $inv['dosage_type']));
					$qty_type 		= Quantity_Type::findById(array("id" => $inv['quantity_type']));
					$rpc_meds[] = array(
						"id"			=> $value['id'],
						"medicine_id" 	=> $value['medicine_id'],
						"batch_id"		=> $value['batch_id'],
						"medicine_name"	=> $inv['medicine_name'],
						"dosage" 		=> $inv['dosage'],
						"dosage_type"	=> $dosage['abbreviation'],
						"price"			=> $value['price'],
						"quantity"		=> $value['quantity'],
						"quantity_type" => $qty_type['abbreviation'],
						"total_price"	=> $value['total_price'],
					);
				}
		//}
			$data['rpc_meds'] = $rpc_meds;
			/* PATIENT */

			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $invoice['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $invoice['patient_id']));
			$data['regimen']= $regimen 		 = Regimen::findByPatientId2(array("id" => $patient['id']));
			$this->load->view('accounting/view/view_invoice', $data);
		}
	}

	function editInvoice(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		$invoice_id = $post['invoice_id'];

		$data['invoice'] = $invoice = Invoice::findById(array("id"=>$invoice_id));
		//debug_array($invoice);
		if($invoice){
			//$invoice = Invoice::findById(array("id"=>$invoice_id));
			$data['order']	 = $order = Orders::findById(array("id" => $invoice['order_id']));
			$meds 			 = Reserved_Meds::findAllByOrderId(array("id" => $invoice['order_id']));
			$orders 		 = Orders_Meds::findAllbyOrderId(array("id" => $invoice['order_id']));
			
			$data['other_charges_data'] = $other_charges1 = Invoice_Other_Charges::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['cost_modifier_data'] = $cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['other_charges']		= $other_charges = Other_Charges::findAllActive();
			$data['order_others']       = $order_others  = Orders_Others::findbyOrderId(array("order_id" => $invoice['order_id']));
			//debug_array($order_others);
			$data['collections']   = $collections 	= Invoice_Receipts::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			foreach ($orders as $key => $value) {
				$array[] = $value['order_id'];
				$array1[] = $value['med_id'];
			}
			$data['meds_summary'] = $meds_summary = array_unique($array);
			$data['meds_summary'] = $meds_summary1 = array_unique($array1);
			
			foreach ($meds_summary as $a => $b) {
				foreach ($meds_summary1 as $c => $d) {
				$meds 			 = Orders_Meds::findTotalQuantity(array("order_id" => $b, "med_id" => $d));
				$inv = Inventory::findById(array("id" => $meds['med_id']));
				$dosage = Dosage_Type::findById(array("id" => $inv['dosage_type']));
				$qty_type 		= Quantity_Type::findById(array("id" => $inv['quantity_type']));
					$rpc_meds[] = array(
						"id"			=> $value['id'],
						"medicine_id" 	=> $meds['med_id'],
						"batch_id"		=> $value['batch_id'],
						"medicine_name"	=> $inv['medicine_name'],
						"dosage" 		=> $inv['dosage'],
						"dosage_type"	=> $dosage['abbreviation'],
						"price"			=> $inv['price'],
						"quantity"		=> $meds['total_quantity'],
						"quantity_type" => $qty_type['abbreviation'],
						"total_price"	=> $meds['total_price'],
					);
				}
			}

			

			$data['rpc_meds'] = $rpc_meds;
			/*foreach ($meds as $key => $value) {
				$a = Inventory::findById(array("id" => $value['medicine_id']));
				$dosage = Dosage_Type::findById(array("id" => $a['dosage_type']));
				$rpc_meds[] = array(
					"id"			=> $value['id'],
					"medicine_id" 	=> $value['medicine_id'],
					"medicine_name"	=> $a['medicine_name'],
					"dosage" 		=> $a['dosage'],
					"dosage_type"	=> $dosage['abbreviation'],
					"price"			=> $value['price'],
					"quantity"		=> $value['quantity'],
					"total_price"	=> $value['total_price'],
				);
			}

			$data['rpc_meds'] = $rpc_meds;*/


			/* PATIENT */

			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $invoice['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $invoice['patient_id']));
			$data['regimen']= $regimen 		 = Regimen::findByPatientId2(array("id" => $patient['id']));
			$data['doctor'] = $doctor 		 = Doctors::findAllActive();
			$data['cost_modifier'] = $cost_modifier 		 = Cost_Modifier::findAll();
			#debug_array($data);
			$this->load->view('accounting/view/edit_invoice_details', $data);
		}
	}

	function getConvertToInvoice(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		$order_id 	= $post['i_order_id'];
		$order 		= Orders::findById(array("id" => $order_id));
		
		if($order){
			$data['order'] 	= $order;
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
							"id"            => $value['medicine_id'],
							"medicine_name" => $medicine['medicine_name'],
							"dosage"		=> $medicine['dosage'] . " " . $dosage_type['abbreviation'],
							"quantity"		=> $value['quantity'] . " " . $qty_type['abbreviation'],
							"price"			=> $value['price'],
							"total_price"	=> $value['total_price'],
							"date_created"	=> $value['date_created']
						);
					$total_price += $value['total_price'];
				}

				$data['invoice_number']	= Invoice::generateNewRPCInvoiceNumber();

				foreach ($order_meds as $key => $value) {
					$reserved_meds 		= Reserved_Meds::findById(array("id"=>$value['med_id']));
					$medicine 			= Inventory::findById(array("id"=>$reserved_meds['med_id']));

					$record = array(
						"item_id"		=> $value['med_id'],
						"patient_id"    => $order['patient_id'],
						"quantity"		=> $value['quantity'],
						"reason"		=> $medicine['medicine_name']." reserved for Invoice No.".  $data['invoice_number'] ,
						"created_by"	=> $reserved_meds['created_by'],
						"created_at"	=> date("Y-m-d H:i:s"),
					);
					Stock::save($record);
					}
			}

			if($order_others){
				foreach($order_others as $key => $value){
					$description = Other_Charges::findById(array("id" =>$value['desc_id']));
					$others[] = array(
							"id"			=> $value['desc_id'],
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
			$data['meds_total_price'] = number_format( (float) $total_price, 2, '.', '');
			$data['others_total_price'] = number_format( (float) $total_others_price, 2, '.', '');

			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $order['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $order['patient_id']));
			$data['cost_modifier'] = $cost_modifier 		 = Cost_Modifier::findAll();
			
			$this->load->view('accounting/view/convert_invoice',$data);
		} else {
			$this->load->view('404');
		}

	}

	function getConvertToInvoiceALIST(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['regimen_id'] = $regimen_id = (int) $post['regimen_id'];
		$data['version_id'] = $version_id = (int) $post['version_id'];
		$data['regimen'] 	= $regimen 		= Regimen::findById(array("id"=>$regimen_id));
		$data['version']	= $version 		= Regimen_Version::findByRegimenVersionId(array("regimen_id" => $regimen_id, "version_id" => $version_id));
		$data['other_charges']	= $other_charges = Other_Charges::findAllActive();

		$summary 			= Regimen_Med_LIst::findByRegAndVersionID(array("regimen_id" => $regimen_id, "version_id" => $version_id));
			
		foreach ($summary as $key => $value) {
			$array[] = $value['medicine_id'];
		}
		$data['regimen_summary'] = $regimen_summary = array_unique($array);
		
		foreach ($regimen_summary as $a => $b) {
			$medicine = Regimen_Med_List::findByRegimenIDMedIDVersion(array("regimen_id" => $regimen_id, "medicine_id" => $b, "version_id" => $version_id));
			
			$total = 0;
			foreach ($medicine as $c => $d) {

					
				/* DATE INTERVAL */
					$start 			= new Datetime($d['start_date']);
					$end 			= new Datetime($d['end_date']);
					$interval 		= $start->diff($end);
					$time_interval 	= (int) $interval->format('%a');
				/* END DATE INTERVAL */
					
					$medicine_id = $d['medicine_id'];
					$quantity 	 = $d['quantity'] * $time_interval;

					$total += (int) $quantity;
			}
			$med 	= Inventory::findById(array("id" => $b));
			$dosage = Dosage_Type::findById(array("id" => $med['dosage_type'])); 

			if($med['stock'] != "A-List") {
				$rpc_meds[] = array(
									"medicine_id" 	=> $b,
									"medicine_name"	=> $med['medicine_name'],
									"dosage" 		=> $med['dosage'],
									"dosage_type"	=> $dosage['abbreviation'],
									"price"			=> $med['price'],
									"quantity"		=> $total,
									"total_price"	=> $med['price'] * $total,
								);
			} else {
				$alist_meds[] = array(
									"medicine_id" 	=> $b,
									"medicine_name"	=> $med['medicine_name'],
									"dosage" 		=> $med['dosage'],
									"dosage_type"	=> $dosage['abbreviation'],
									"price"			=> $med['price'],
									"quantity"		=> $total,
									"total_price"	=> $med['price'] * $total,
								);
			}
		
		}

		$data['rpc_meds'] 	= $rpc_meds;
		$data['alist_meds'] = $alist_meds;

		/* PATIENT */

		$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $regimen['patient_id']));
		$data['patient']= $patient 		 = Patients::findById(array("id" => $regimen['patient_id']));
		
		$this->load->view('accounting/view/convert_invoice_alist',$data);
	}

	function loadRegimenPopUP(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$reg_id = (int) $post['regimen_id'];
		$version_id = (int) $post['version_id'];
		if($reg_id){
			$data['regimen'] 	= $reg = Regimen::findById(array("id"=>$reg_id));
			$data['version_id']	= $version_id;
			if($version_id != 0){
				$data['version'] = $version 	= Regimen_Version::findById(array("id" => $version_id));
			}
			$data['bf'] 		= $list_bf 		= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $reg_id, "meal_type" => "bf", "version_id" => $version_id));
			$data['lunch'] 		= $list_lunch 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $reg_id, "meal_type" => "lunch", "version_id" => $version_id));
			$data['dinner'] 	= $list_dinner 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $reg_id, "meal_type" => "dinner", "version_id" => $version_id));
			$this->load->view('accounting/modal/regimen',$data);
		}
	}

	function save_invoice(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata("user_id");
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session);
		$post = $this->input->post();
		
		$order 		= Orders::findById(array("id" => $post['order_id']));
		if($post['order_id']){
			$order_id = $post['order_id'];
			$start 			= new Datetime($post['invoice_date']);
			// $start 			= new Datetime(date("Y-m-d"));
			$end 			= new Datetime($post['due_date']);
			$interval 		= $start->diff($end);
			$time_interval 	= (int) $interval->format('%a');

			$record = array(
				"order_id" 				=> $order_id,
				"invoice_internal"		=> Invoice::generateNewRPCInvoiceID(),
				"invoice_num"			=> $post['invoice_number'],
				"patient_id" 			=> $post['patient_id'],
				"charge_to"				=> $post['charged_to'],
				"relation_to_patient"	=> $post['relation_to_patient'],
				"invoice_date"			=> $post['invoice_date'],
				//"due_date"				=> $post['due_date'],
				"aging"					=> $time_interval,
				"payment_terms"			=> $post['payment_terms'],
				"total_gross_sales" 	=> (float) $post['total_regimen_cost'] + (float) $post['total_other_charges'],
				"total_regimen_cost"	=> (float) $post['total_regimen_cost'],
				"total_other_charges"	=> (float) $post['total_other_charges'],
				"cost_modifier"			=> (float) $post['total_cost_modifier'],
				"net_sales"				=> (float) $post['net_sales'],
				"net_sales_vat"			=> (float) $post['net_sales_vat'],
				"total_net_sales_vat"	=> (float) $post['total_net_sales_vat'],
				"remaining_balance"		=> (float) $post['total_net_sales_vat'],
				"date_created" 			=> date("Y-m-d H:i:s"),
				"date_updated" 			=> date("Y-m-d H:i:s"),
				"status" 				=> "New",
				"last_update_by" 		=> $session_id
				);
			$invoice_id 	= Invoice::save($record);
			$order_meds		= Reserved_Meds::findAllByOrderId(array("id" => $order_id));
			$order_others 	= Orders_Others::findbyOrderId(array("order_id" => $order_id));
			
				/*foreach ($order_meds as $key => $value) {
					$medicine = array(
						"taken"	=> "0",
					);
					Reserved_Meds::save($medicine, $value['id']);
				}*/

			
			/*if($order_meds){
				foreach ($order_meds as $key => $value) {
					$medicine = array(
						"invoice_id" 		=> $invoice_id,
						"type"		 		=> "RPC",
						"medicine_id" 		=> $value['med_id'],
						"quantity" 			=> $value['quantity'],
						"price" 			=> (float) $value['price'],
						"total_price" 		=> (float) $value['total_price'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $session_id,
						);
					Invoice_Med::save($medicine);
					unset($medicine);
				}
			}*/

			if($order_others){
				foreach ($order_others as $key => $value) {
					$others = array(
						"invoice_id" 		=> $invoice_id,
						"description_id" 	=> $value['desc_id'],
						"quantity" 			=> $value['quantity'],
						"cost_per_item" 	=> (float) $value['cost'],
						"cost" 				=> (float) $value['total_cost'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $session_id,
						);
					Invoice_Other_Charges::save($others);
					unset($others);
				}
			}

			if($post['cm_additional']){
				foreach ($post['cm_additional'] as $key => $value) {
					$cm = array(
						"invoice_id" 		=> $invoice_id,
						"applies_to" 		=> $value['applies_to'],
						"is_medicine"		=> $value['is_medicine'],
						"modifier_type" 	=> $value['modifier_type'],
						"modify_due_to" 	=> $value['modify_due_to'],
						"cost_type" 		=> $value['cost_type'],
						"cost_modifier" 	=> $value['cost_modifier'],
						"total_cost" 		=> (float) $value['total_cost'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $session_id,
						);
					Invoice_Cost_Modifier::save($cm);
					unset($cm);
				}
			}
			
			
			if($invoice_id){
				/* UPDATE ORDER */
				$new_record = array(
					"date_updated" 	=> date("Y-m-d H:i:s"),
					"status"		=> "Invoiced",
					"created_by"	=> $session_id
					);
				Orders::save($new_record,$order_id);
				/* END OF UPDATE */

			$user = User::findById(array("id"=>$user_id));
				$act_tracker = array(
				"module"		=> "rpc_orders",
				"user_id"		=> $user_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "converted {$order['order_no']} into {$record['invoice_internal']}",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['invoice_id']	 	= $invoice_id;
				$invoice 				= Invoice::findById(array("id" => $invoice_id));
				$invoice_number 		= $invoice['invoice_internal'];
				$json['message'] 		= "Successfully Added {$invoice_number} in database";

				//New Notification
					$msg = $session_user['name'] . " has successfully Added Invoice: {$invoice_number}.";

					$this->pusher->trigger('my_notifications', 'notification', array(
						'message' => $msg,
						'title' => "Added Invoice " . $invoice_number,
						'type' => 'info'
					));

				/*NOTIFICATIONS*/
				/*$user = User::findById(array("id" => $session_id));
				$json['notif_title'] 	= "Added " . $invoice_number;
				$json['notif_type']		= "info";
				$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has Added {$invoice_number}";*/
			} else {
				$json['message'] 		= "Error Saving in the database";
				$json['is_successful'] = false;
			}
		}

		
		
		echo json_encode($json);
	}

	/*function save_invoice_alist(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata("user_id");
		$session_id = $this->encrypt->decode($session);
		$post = $this->input->post();
		
		// debug_array($post['alist_invoice_id']);

		$record = array(
			"invoice_id" 			=> $post['invoice_id'],
			"alist_invoice_id" 		=> $post['alist_invoice_id'],
			"alist_invoice_num" 	=> $post['alist_invoice_number'],
			"patient_id" 			=> $post['patient_id'],
			"regimen_id" 			=> $post['regimen_id'],
			"version_id" 			=> $post['version_id'],
			"total_gross_sales" 	=> $post['total_alist_cost'] + $post['total_alist_other_charges'],
			"total_regimen_cost" 	=> $post['total_alist_cost'],
			"total_other_charges" 	=> $post['total_alist_other_charges'],
			"cost_modifier" 		=> $post['total_alist_cost_modifier'],
			"net_sales" 			=> $post['alist_total_net_sales_vat'],
			"net_sales_vat" 		=> $post['alist_net_sales_vat'],
			"total_net_sales_vat" 	=> $post['alist_net_sales'],
			"date_created" 			=> date("Y-m-d H:i:s"),
			"date_updated" 			=> date("Y-m-d H:i:s"),
			"last_update_by" 		=> $session_id,
			);

		$alist_id = Invoice_AList::save($record);
		Invoice_Med_AList::saveAListMeds($post, $alist_id);
		Invoice_Other_Charges_AList::saveOtherCharges($post, $alist_id);
		Invoice_Cost_Modifier_AList::saveCostModifier($post, $alist_id);
		$a_list = array(
			"alist_invoice_id" 		=> $alist_id,
			);
		Invoice::save($a_list,$post['invoice_id']);
		


		foreach ($post['alist_med'] as $key => $value) {
			$record = array(
				"price" => $value['price'] 
				);
			Inventory::save($record, $value['id']);

			unset($record);
		}

		if($alist_id){
			$json['is_successful'] 	= true;
			$json['alist_id']	 	= $alist_id;
			$invoice 				= Invoice_AList::findById(array("id" => $alist_id));
			$invoice_number 		= $invoice['alist_invoice_id'];
			$json['message'] 		= "Successfully Added {$invoice_number} in database";

			/*NOTIFICATIONS*/
			/*$user = User::findById(array("id" => $session_id));
			$json['notif_title'] 	= "Added A-List " . $invoice_number;
			$json['notif_type']		= "info";
			$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has Added A-List {$invoice_number}";
		} else {
			$json['message'] 		= "Error Adding in database";
			$json['is_successful'] = false;
		}
		
		echo json_encode($json);
	}*/

	function save_receipt(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		#debug_array($post);

		$invoice_id = $post['invoice_id'];
		$invoice 	= Invoice::findById(array("id" => $invoice_id));
		
		if($post['receipt']){
			$invoice_meds = Invoice_Med::findInvoiceId(array("id" => $invoice_id));
			if(empty($invoice_meds)){
				$meds = Reserved_Meds::findAllByOrderId(array("id" => $invoice['order_id']));
				
					foreach ($meds as $key => $value) {
						$medicine = array(
							"invoice_id" 		=> $invoice_id,
							"type"		 		=> "RPC",
							"medicine_id" 		=> $value['med_id'],
							"batch_id"			=> $value['batch_id'],
							"quantity" 			=> $value['quantity'],
							"price" 			=> (float) $value['price'],
							"total_price" 		=> (float) $value['total_price'],
							"date_created"  	=> date("Y-m-d H:i:s"),
							"date_updated"  	=> date("Y-m-d H:i:s"),
							"last_update_by"  	=> $session_id,
							);
						Invoice_Med::save($medicine);
						unset($medicine);
					}
			}

			$json['is_successful'] 	= true;
			$json['invoice_id']	 	= $invoice_id;

			$invoice_number 		= $invoice['invoice_internal'];

			$invoice_or = array(
					"invoice_id" 	=> $invoice_id,
					"or_number"		=> $post['or_number'],	
					"amount_paid"	=> $post['total_payment'],
					"date_receipt"	=> $post['date_receipt'],
					"date_created"	=> date("Y-m-d H:i:s"),
					"notes "		=> $post['add_notes'],
					"last_update_by"=> $user_id
				);

			// debug_array($invoice_or);

			$or_id = Invoice_Receipts::save($invoice_or);

			if(!empty($post['receipt']['cash'])){

				foreach ($post['receipt']['cash'] as $key => $value) {
					$cash = array(
						"or_id"	=> $or_id,
						"price" => $value
					);

					Invoice_Receipts_Cash::save($cash);
				}
				
			}

			if(!empty($post['receipt']['cheque'])){
				foreach ($post['receipt']['cheque'] as $key => $value) {
					$cheque = array(
						"or_id"			=> $or_id,
						"cheque_number"	=> $value['cheque_no'],
						"bank_name"		=> $value['bank_name'],
						"cheque_date"	=> $value['cheque_date'],
						"price"			=> $value['amount_paid'],
					);

					Invoice_Receipts_Cheque::save($cheque);
				}
			}

			if(!empty($post['receipt']['creditcard'])){
				foreach ($post['receipt']['creditcard'] as $key => $value) {
					$cc = array(
						"or_id"	=> $or_id,
						"bank_name"	=> $value['bank_name'],
						"card_type"	=> $value['cc_type'],
						"price" => $value['amount_paid'],
					);

					Invoice_Receipts_CC::save($cc);
				}
			}

			if(!empty($post['receipt']['credit'])){
				foreach ($post['receipt']['credit'] as $key => $value) {

					$credit = array(
						"or_id"	=> $or_id,
						"price" => $value
					);
					Invoice_Receipts_Credit::save($credit);

					$patient = Patients::findById(array("id"=> $invoice['patient_id']));

					$new_patient_data = array(
							"credit" => $patient['credit'] - $value
						);

					Patients::save($new_patient_data, $invoice['patient_id']);

					$history = array(
							"patient_id" 	=> $invoice['patient_id'],
							"credit" 		=> $value,
							"remarks" 		=> "Payment used to " . $post['or_number'],
							"type" 			=> "Less",
							"date_created" 	=> date("Y-m-d H:i:s"),
							"created_by" 	=> $user_id,
							);

					Patients_Credit_History::save($history);
				}
			}

			if($post['total_overpayment'] > 0){
				$patient = Patients::findById(array("id"=> $invoice['patient_id']));
				$record = array(
					"credit" => $patient['credit'] + $post['total_overpayment']
				);
				Patients::save($record, $invoice['patient_id']);

				$history = array(
					"patient_id" 	=> $invoice['patient_id'],
					"credit" 		=> $post['total_overpayment'],
					"remarks" 		=> "Overpayment from " . $post['or_number'],
					"type" 			=> "Add",
					"date_created" 	=> date("Y-m-d H:i:s"),
					"created_by" 	=> $user_id,
				);
				Patients_Credit_History::save($history);
			}
			$remaining_balance = number_format($invoice['remaining_balance'],2, '.', '') - number_format($post['total_payment'],2, '.', '');
			$update = array(
					"total_amount_paid" => number_format($invoice['total_amount_paid'],2, '.', '') + number_format($post['total_payment'],2, '.', ''),
					"remaining_balance" => $remaining_balance < 0 ? '0.00' : $remaining_balance,
					"date_updated"		=> date("Y-m-d H:i:s"),
					"last_update_by"	=> $user_id,
					"status"			=> (number_format($invoice['remaining_balance'],2, '.', '') - number_format($post['total_payment'],2, '.', '') <= 0 ? "Paid" : "Partial"),
					"overpayment"		=> $post['total_overpayment'],
				);

			Invoice::save($update, $invoice_id);

			/*$user = User::findById(array("id" => $user_id));
			$json['notif_title'] 	= "New Receipt for " . $invoice_number;
			$json['notif_type']		= "info";
			$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has created a receipt for {$invoice_number}.";
			$json['message'] 		= "Successfully created a receipt for {$invoice_number}.";*/

			$json['message'] 		= "Successfully created a receipt for {$invoice_number}.";
		
			//New Notification
			$msg = $session['name'] . " has successfully created a receipt for : {$invoice_number}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Added Receipt " . $invoice_number,
				'type' => 'info'
			));
		} else {
			/*$json['message'] 		= "Error Adding in database";
			$json['is_successful'] = false;*/
			$json['notice'] 	   = "Data in Method of Payment is not yet saved. Please press Save Payment to continue.";
			$json['is_mode_of_payment_save'] 	= false;
		}
		
		echo json_encode($json);
	}

	function loadDataTableSalesReport(){
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$im_list 		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 13));

		if($get) {

			$filter_date 	= $get['filter']; 
			$filter_search 	= $get['filter_to_search'];
			

			$filter_array = array("filter_date" => $get['filter'], "filter_to_search" => $filter_search);
			
			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= (!empty($get['filter_to_search']) && !empty($get['filter']) ? $filter_array : $get['sSearch'] );
			
			// $order_type	= strtoupper($get['sSortDir_0']);
			$order_type	= strtoupper("desc");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "a.invoice_internal",
				1 => "a.invoice_num",
				2 => "c.full_name",
				3 => "b.patient_name",
				4 => "b.tin",
				5 => "a.invoice_date",
				6 => "a.due_date",
				7 => "a.aging",
				8 => "a.total_gross_sales",
				9 => "a.cost_modifier",
				10 => "a.net_sales",
				11 => "a.net_sales_vat",
				12 => "a.total_net_sales_vat",
				13 => "a.status",
				14 => "a.order_id"
			);

			$fields = array(
				"a.id",
				"a.invoice_internal", 
				"a.invoice_num", 
				"c.full_name", 
				"b.patient_name", 
				"b.tin", 
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_gross_sales", 
				"a.total_regimen_cost", 
				"a.total_other_charges", 
				"a.cost_modifier", 
				"a.net_sales", 
				"a.net_sales_vat", 
				"a.total_net_sales_vat", 
				"a.status",
				"a.taken",
				"a.order_id",
				"a.date_claimed"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 50";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$sales 		= Invoice::generateSalesReportDatatable($params);
			$total_records 	= Invoice::countSalesReportDatatable($params);
			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);

			foreach($sales as $key=>$value):
				
				if($value['taken'] == 0 && $value['status'] != 'Void'){
					$link = '<a href="javascript: void(0);" class="medicine_title" title="Medicines has not been claimed." onClick="javascript: viewInvoice('.$value['id'].')">' . $value['invoice_internal'] . '</a>';
					$status = '<span class="label label-warning">' . $value['status'] . '</span>';
				} else if($value['taken'] == 1 && $value['status'] != 'Void') {
					$link = '<a href="javascript: void(0);" class="medicine_title" title="Medicines has been claimed." onClick="javascript: viewInvoice('.$value['id'].')">' . $value['invoice_internal'] . '</a>';
					$status = $value['status'];
				} else {
					$link = '<a href="javascript: void(0);" class="medicine_title" title="Invoice is Void." onClick="javascript: viewInvoice('.$value['id'].')">' . $value['invoice_internal'] . '</a>';
					$status = $value['status'];
				}
				
				
				$doctor_attending = Orders::findById(array("id" => $value['order_id']));
				$doctor = Doctors::findById(array("id" => $doctor_attending['doc_attending_id']));

				$checker = $value['date_claimed'];
				
				if($checker != '0000-00-00 00:00:00'){
					$due = date('Y/m/d', strtotime($value['date_claimed']));
					$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));

					$due_date = new Datetime($due);
					$date_today = new Datetime(date("Y-m-d H:i:s"));
					$interval = $due_date->diff($date_today);
					$aging 	= (int) $interval->format('%a');
				}else{
					$aging = 0;
				}

				

				$row = array(
					'0' => $link,
					'1' => $value['invoice_num'],
					'2' => !empty($doctor['full_name']) ? $doctor['full_name'] : $value['full_name'],
					'3' => $value['patient_name'],
					'4' => $value['tin'],
					'5' => $value['invoice_date'],
					'6' => $value['date_claimed'] == '0000-00-00 00:00:00' ? '0000-00-00' : $real_duedate,
					'7' => ($status != "Paid" ? $aging : '0'),
					'8' => $value['total_gross_sales'],
					'9' => $value['cost_modifier'],
					'10' => $value['net_sales'],
					'11' => $value['net_sales_vat'],
					'12' => $value['total_net_sales_vat'],
					'13' => $status
				);
				
				// remove expiry variable when finish
				$output['aaData'][] = $row;
			endforeach;
			// $output['test'] = "Test";
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

	function loadDataTableAccountsReceivables(){
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
				0 => "a.invoice_internal",
				1 => "a.invoice_num",
				2 => "c.full_name",
				3 => "b.patient_name",
				4 => "b.tin",
				5 => "a.invoice_date",
				6 => "a.due_date",
				7 => "a.aging",
				8 => "a.total_amount_paid",
				9 => "a.remaining_balance",
				10 => "a.status"
			);

			$fields = array(
				"a.id",
				"a.invoice_internal", 
				"a.invoice_num", 
				"c.full_name", 
				"b.patient_name", 
				"b.tin", 
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_amount_paid", 
				"a.remaining_balance", 
				"a.status",
				"a.taken",
				"a.date_claimed"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 50";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$accounts_receivables = Invoice::generateAccountsReceivablesDatatable($params);
			$total_records 	= Invoice::countAccountsReceivablesDatatable($params);
			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($accounts_receivables as $key=>$value):

				if($value['taken'] == 0 && $value['status'] != 'Void'){
					$link = '<a href="javascript: void(0);" class="medicine_title" title="Medicines has not been claimed." onClick="javascript: viewInvoice('.$value['id'].')">' . $value['invoice_internal'] . '</a>';
					$status = '<span class="label label-warning">' . $value['status'] . '</span>';
				} else if($value['taken'] == 1 && $value['status'] != 'Void') {
					$link = '<a href="javascript: void(0);" class="medicine_title" title="Medicines has been claimed." onClick="javascript: viewInvoice('.$value['id'].')">' . $value['invoice_internal'] . '</a>';
					$status = $value['status'];
				} else {
					$link = '<a href="javascript: void(0);" class="medicine_title" title="Invoice is Void." onClick="javascript: viewInvoice('.$value['id'].')">' . $value['invoice_internal'] . '</a>';
					$status = $value['status'];
				}

				$due_date = new Datetime($value['due_date']);
				$date_today = new Datetime(date("Y-m-d H:i:s"));
				$interval = $due_date->diff($date_today);
				$aging 	= (int) $interval->format('%a');

				$due = date('Y/m/d', strtotime($value['date_claimed']));
				$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));

				$row = array(
					'0' => $link,
					'1' => $value['invoice_num'],
					'2' => $value['full_name'],
					'3' => $value['patient_name'],
					'4' => $value['tin'],
					'5' => $value['invoice_date'],
					'6' => $value['date_claimed'] == '0000-00-00 00:00:00' ? '0000-00-00' : $real_duedate,
					'7' => $aging,
					'8' => $value['total_amount_paid'],
					'9' => $value['remaining_balance'],
					'10' => $status
				);
				// remove expiry variable when finish
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

	function loadDataTableCollections(){
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
				0 => "b.invoice_internal",
				1 => "a.amount_paid",
				2 => "a.date_receipt",
				3 => "c.patient_name"
			);

			$fields = array(
				"c.patient_name",
				"b.invoice_internal", 
				"a.amount_paid",
				"a.or_number", 
				"a.date_receipt",
				"a.id", 
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 50";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$collections 	= Invoice_Receipts::generateCollectionsDatatable($params);
			$total_records 	= Invoice_Receipts::countCollectionsDatatable($params);
			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($collections as $key=>$value):

				$link = '<a href="javascript: void(0);" onclick="javascript: viewCollection('.$value['id'].')">'. $value['invoice_internal'] . '</a>';
				$row = array(
					'0' => $link,
					'1' => $value['patient_name'],
					'2' => $value['or_number'],			
					'3' => "P " . number_format($value['amount_paid'],2,',','.'),
					'4' => $value['date_receipt'],
					'5'	=> $value['id']
				);
				// remove expiry variable when finish
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

	function getCollectionData(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$or_id = $post['or_id'];
		$or = Invoice_Receipts::findById(array("id"=>$or_id));
		$session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));

		$data['invoicing'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 28));
		if($or){
			$data['or'] 	= $or;
			$data['cash'] 	= $cash 	= Invoice_Receipts_Cash::findAllByORid(array("or_id" => $or['id']));
			$data['cheque'] = $cheque 	= Invoice_Receipts_Cheque::findAllByORid(array("or_id" => $or['id']));
			$data['cc'] 	= $cc 		= Invoice_Receipts_CC::findAllByORid(array("or_id" => $or['id']));
			$data['credit'] = $credit 	= Invoice_Receipts_Credit::findAllByORid(array("or_id" => $or['id']));
			$data['taxwithheld'] = $taxwithheld     	= Invoice_Receipts_TaxWithheld::findAllByORid(array("or_id" => $or['id']));

			/* INVOICE */
			$data['invoice'] = $invoice = Invoice::findById(array("id" => $or['invoice_id']));
			//debug_array($invoice);
			/* REGIMEN */
			$regimen_id = $invoice['regimen_id'];
			$version_id = $invoice['version_id'];
			$data['regimen'] = $regimen = Regimen::findByPatientId2(array("id"=>$invoice['patient_id']));
			$data['version'] = $version = Regimen_Version::findByRegimenVersionId(array("regimen_id" => $regimen_id, "version_id" => $version_id));
			
			/* PATIENT */
			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $invoice['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $invoice['patient_id']));
			$this->load->view('accounting/receipts/view/collection', $data);
		} else {
			$this->load->view('404');
		}
	}

	function loadReportGeneratorModal(){
		Engine::XmlHttpRequestOnly();
		$this->load->view("accounting/modal/report_generator");
	}

	function generateReportDateRange(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$post = $this->input->post();

		if($post){

			$fields = array(
				"a.id",
				"a.invoice_internal", 
				"a.invoice_num",  
				"b.patient_name", 
				"b.tin", 
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_gross_sales", 
				"a.cost_modifier", 
				"a.net_sales", 
				"a.net_sales_vat", 
				"a.total_net_sales_vat",  
				"a.total_amount_paid", 
				"a.remaining_balance", 
				"a.status",
				"a.taken",
				"a.date_claimed",
				"a.date_created",
				"a.remarks"
				);
			$params = array(
				"fields" 	=> $fields,
				"from_date" => $post['from_date'],
				"to_date"	=> $post['to_date']
			);
			if($post['type_of_sale'] == "AllSales"){
				$invoice = Invoice::findDateRange($params);
			} else {
				$invoice = Invoice::findDateRangeAR($params);
			}
			

			if($invoice){
				$json['is_successful'] = true;
			} else {
				$json['message']	   = "No results found.";
				$json['is_successful'] = false;
			}
				
			
		} else {
			$json['is_successful'] = false;

		}

		echo json_encode($json);
	}

	function download_report_generate_date_range(){
		// Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$from_date = $this->uri->segment(3);
		$to_date = $this->uri->segment(4);
		$type_of_sale = $this->uri->segment(5);

		$fields = array(
				"a.id",
				"a.order_id",
				"c.order_no",
				"c.patient_id",
				"c.estimated_date",
				"c.doc_attending_id",
				"a.invoice_internal", 
				"a.invoice_num",  
				"b.patient_name", 
				"b.age",
				"b.tin",
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_gross_sales", 
				"a.cost_modifier", 
				"a.net_sales", 
				"a.net_sales_vat", 
				"a.total_net_sales_vat",  
				"a.total_amount_paid", 
				"a.remaining_balance", 
				"a.status",
				"a.taken",
				"a.date_claimed",
				"a.date_created",	
				"a.remarks"	
				);

		$params = array(
			"fields" 	=> $fields,
			"from_date" => $from_date,
			"to_date"	=> $to_date
		);
		if($type_of_sale == "AllSales"){
			$invoice = Invoice::findDateRange($params);
		} else {
			$invoice = Invoice::findDateRangeAR($params);
		}
		$invoice_array[] = array("Order Number","Order Estimated EndDate","Invoice ID", "Invoice Number", "OR Number", "Attending Doctor", "Patient Name", "Age", "TIN", "Invoice Date", "Due Date", "Total Gross Sales", "Cost Modifier", "Net Sales", "Net Sales VAT", "Total Net Sales Vat","Total Amount Paid", "Total Amount Outstanding", "Status", "Have been Claimed", "Date Claimed","", "RPC (RMD)", "Alist (RMD)","RPP (RMD)","GH(Sci./Nordi.)-(RMD)","NEBIDO/Myopep (RMD)", "", "Consultation-Initial (RMD)", "Consultation-Follow up (RMD)", "Consultation-Genetic Testing (RMD)", "TOTAL CONSULT (RMD)", "Genetic Test-Procedure (RMD)","Other Test (RMD)","Others (RMD)","YP Test (RMD)","Armin Test (RMD)", "","RPC (other MD)", "Alist (other MD)","RPP (other MD)","GH(Sci./Nordi.)-(other MD)","NEBIDO/Myopep (other MD)","","Consultation-Initial (other MD)", "Consultation-Follow up (other MD)", "Consultation-Genetic Testing (other MD)","TOTAL CONSULT (other MD)", "Genetic Test-Procedure (other MD)", "Other Test (other RMD)","Others (other MD)","YP Test (other MD)","Armin Test (other MD)", "","Senior Discount", "Bottle Discount", "Employee Discount", "Special Discount", "RPC Marketing", "Breakage / Damaged Medicines","", "Remarks",
			);

		foreach ($invoice as $key => $inv) {
		//Columns for RPC/AList Medicines
			
			$doctor = Doctors::findById(array("id" => $inv['doc_attending_id']));	//get the full_name 
			$patients = Patients::findById(array("id" => $inv['patient_id']));      //get the attending_doctor if ever wala sya doc_attending sa invoice
			$doc_attending = Doctors::findById(array("id" => $patients['doc_attending_id'])); //then get the full name

			$attending_doctor_id = !empty($doctor['id']) ? $doctor['id'] : $doc_attending['id']; //attending_doctor

			$order_meds = Orders_Meds::findAllbyOrdersAndStock(array("id" => $inv['order_id']));
			$invoice_receipts = Invoice_Receipts::findAllOR(array("invoice_id" => $inv['id']));
			$patient_order = Orders::findById(array("id" => $inv['order_id']));
			foreach( $invoice_receipts as $key => $value){
				$or_number .= $value['or_number'].',';
			}

			if(!empty($order_meds)){
				foreach ($order_meds as $key => $value) {
					$all_ids[] = $value['order_id'];
				}
				$med_ids = array_keys(array_flip($all_ids));


				foreach ($med_ids as $key => $value1) {
					$order = Orders::findById(array("id" => $value1));
					$patient = Patients::findById(array("id" => $order['patient_id']));

					$rpc_meds = 0;
					$alist_meds = 0;
					$GH = 0;
					$nebidoMyoslim_meds = 0;
					$rpp_meds = 0;

					$rpc_meds1 = 0;
					$alist_meds1 = 0;
					$GH1 = 0;
					$nebidoMyoslim_meds1 = 0;
					$rpp_meds1 = 0;
					
					if($attending_doctor_id == '13'){
						foreach ($order_meds as $k => $val) {
							if($val['product_no'] == '2015-01-003-3003' OR $val['product_no'] == '2015-01-004-3002' OR $val['product_no'] == '2015-01-004-3001'){
								$GH += $val['total_price'];
							}elseif($val['product_no'] == '2015-01-003-3001' OR $val['product_no'] == '2015-01-005-3001' OR $val['product_no'] == '2015-01-005-3002'){
								$nebidoMyoslim_meds += $val['total_price'];
							}elseif($val['stock'] == 'A-List'){
								$alist_meds += $val['total_price'];
							}elseif($val['stock'] == 'RPP'){
								$rpp_meds += $val['total_price'];
							}else{
								$rpc_meds += $val['total_price'];
							}
							if($val['order_id']){ $id 	= $val['order_id']; }
						}
					}else{
						foreach ($order_meds as $k => $val) {
							if($val['product_no'] == '2015-01-003-3003' OR $val['product_no'] == '2015-01-004-3002' OR $val['product_no'] == '2015-01-004-3001'){
									$GH1 += $val['total_price'];
								}elseif($val['product_no'] == '2015-01-003-3001' OR $val['product_no'] == '2015-01-005-3001' OR $val['product_no'] == '2015-01-005-3002'){
									$nebidoMyoslim_meds1 += $val['total_price'];
								}elseif($val['stock'] == 'A-List'){
									$alist_meds1 += $val['total_price'];
								}elseif($val['stock'] == 'RPP'){
									$rpp_meds1 += $val['total_price'];
								}else{
									$rpc_meds1 += $val['total_price'];
								}
								if($val['order_id']){$id 	= $val['order_id'];}
						}
					}

					$med = array(
						"id"				=> $id,
						"rpc_total_price"	=> number_format($rpc_meds,2,".",""),
						"alist_total_price"	=> number_format($alist_meds,2,".",""),
						"rpp_total_price"	=> number_format($rpp_meds,2,".",""),
						"gh_total_price" 	=> number_format($GH,2,".",""),
						"nebmyo_total_price" => number_format($nebidoMyoslim_meds,2,".",""),
						"rpc_total_price_OtherRMD"	=> number_format($rpc_meds1,2,".",""),
						"alist_total_price_OtherRMD"	=> number_format($alist_meds1,2,".",""),
						"rpp_total_price_OtherRMD"	=> number_format($rpp_meds1,2,".",""),
						"gh_total_price_OtherRMD" 	=> number_format($GH1,2,".",""),
						"nebmyo_total_price_OtherRMD" => number_format($nebidoMyoslim_meds1,2,".","")
					);	

					//unset($id);
				}
			}else{
				unset($med);
			}
			//Column for Other charges RMD and other RMD
			$other_charges = Invoice_Other_Charges::findAllByInvoiceIdWithDescId(array("invoice_id" => $inv['id']));
			if(!empty($other_charges)){
				foreach ($other_charges as $key => $value) {
					$all_ids2[] = $value['invoice_id'];
				}
				$med_ids2 = array_keys(array_flip($all_ids2));

				foreach ($med_ids2 as $key => $value2) {
					$initialConsulation_RMD = 0;
					$followUp_RMD = 0;
					$geneticTesting_RMD = 0;
					$geneticTestingProcedure_RMD = 0;
					$otherTest_RMD = 0;
					$others_RMD = 0;
					$yp_RMD = 0;
					$armin_RMD = 0;

					$initialConsulation_otherRMD = 0;
					$followUp_otherRMD = 0;
					$geneticTesting_otherRMD = 0;
					$geneticTestingProcedure_otherRMD = 0;
					$otherTest_otherRMD = 0;
					$others_otherRMD = 0;
					$yp_otherRMD = 0;
					$armin_otherRMD = 0;

				//OPTION 1: Doctor_ID from Other Charges 
					/*foreach ($other_charges as $k => $val) {
						if($value2 == $val['invoice_id']){
							if($val['doctor_id'] == '13'){
								if($val['category'] == 'Consultation' && $val['description_id'] == '1'){
									$initialConsulation_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '2'){
									$followUp_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '3'){
									$geneticTesting_RMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_RMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_RMD += $val['cost'];
								}else{
									$others_RMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}else{
								if($val['category'] == 'Consultation' && $val['description_id'] == '4'){
									$initialConsulation_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '5'){
									$followUp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '6'){
									$geneticTesting_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_otherRMD += $val['cost'];
								}else{
									$others_otherRMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}
						}
					}	*/

					//OPTION 2: Doctor_ID from Order/Invoice 
					foreach ($other_charges as $k => $val) {
						if($value2 == $val['invoice_id']){
							if($attending_doctor_id == '13'){
								if($val['category'] == 'Consultation' && $val['description_id'] == '1'){
									$initialConsulation_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '2'){
									$followUp_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '3'){
									$geneticTesting_RMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_RMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_RMD += $val['cost'];
								}elseif($val['category'] == 'yp'){
									$yp_RMD += $val['cost'];
								}elseif($val['category'] == 'armin'){
									$armin_RMD += $val['cost'];
								}else{
									$others_RMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}else{
								if($val['category'] == 'Consultation' && $val['description_id'] == '4'){
									$initialConsulation_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '5'){
									$followUp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '6'){
									$geneticTesting_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_otherRMD += $val['cost'];
								}elseif($val['category'] == 'yp'){
									$yp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'armin'){
									$armin_otherRMD += $val['cost'];
								}else{
									$others_otherRMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}
						}
					}	

							/*if($val['doctor_id'] == '13'){
								if($val['description_id'] == '1'){
									$initialConsulation_RMD += $val['cost'];
								}elseif($val['description_id'] == '2'){
									$followUp_RMD += $val['cost'];
								}elseif($val['description_id'] == '3'){
									$geneticTesting_RMD += $val['cost'];
								}else{
									$geneticTestingProcedure_RMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}else{
								if($val['description_id'] == '4'){
									$initialConsulation_otherRMD += $val['cost'];
								}elseif($val['description_id'] == '5'){
									$followUp_otherRMD += $val['cost'];
								}elseif($val['description_id'] == '6'){
									$geneticTesting_otherRMD += $val['cost'];
								}else{
									$geneticTestingProcedure_otherRMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}*/
						

					$other_charges_data = array(
						"id"									=> $id2,
						"initialConsulation_cost_RMD"			=> number_format($initialConsulation_RMD,2,".",""),
						"followUp_cost_RMD"						=> number_format($followUp_RMD,2,".",""),
						"geneticTesting_cost_RMD" 				=> number_format($geneticTesting_RMD,2,".",""),
						"geneticTestingProcedure_cost_RMD" 		=> number_format($geneticTestingProcedure_RMD,2,".",""),
						"otherTest_cost_RMD" 					=> number_format($otherTest_RMD,2,".",""),
						"others_cost_RMD" 	 					=> number_format($others_RMD,2,".",""),
						"yp_cost_RMD" 	 						=> number_format($yp_RMD,2,".",""),
						"armin_cost_RMD" 	 					=> number_format($armin_RMD,2,".",""),
						"initialConsulation_cost_otherRMD"		=> number_format($initialConsulation_otherRMD,2,".",""),
						"followUp_cost_otherRMD"				=> number_format($followUp_otherRMD,2,".",""),
						"geneticTesting_cost_otherRMD" 			=> number_format($geneticTesting_otherRMD,2,".",""),
						"geneticTestingProcedure_cost_otherRMD" => number_format($geneticTestingProcedure_otherRMD,2,".",""),
						"otherTest_cost_otherRMD" 				=> number_format($otherTest_otherRMD,2,".",""),
						"others_cost_otherRMD" 	 				=> number_format($others_otherRMD,2,".",""),
						"otherYP_cost_RMD" 	 					=> number_format($yp_otherRMD,2,".",""),
						"otherArmin_cost_RMD" 	 				=> number_format($armin_otherRMD,2,".",""),
						"total_consultation_RMD" 				=> number_format($initialConsulation_RMD + $followUp_RMD + $geneticTesting_RMD,2,".",""),
						"total_consultation_otherRMD" 			=> number_format($initialConsulation_otherRMD + $followUp_otherRMD + $geneticTesting_otherRMD,2,".","")
					);
					//unset($id2);
				}
			}else{
				unset($other_charges_data);
			}
		
			//Column for Cost Modifier

			$cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $inv['id']));
			if(!empty($cost_modifier)){
				foreach ($cost_modifier as $key => $value) {
					$all_ids3[] = $value['invoice_id'];
				}
				$med_ids3 = array_keys(array_flip($all_ids3));

				foreach ($med_ids3 as $key => $value3) {
					$SC = 0;
					$BD = 0;
					$ED = 0;
					$SD = 0;
					$RM = 0;
					$BDM = 0;

					foreach ($cost_modifier as $k => $val) {

						if($value3 == $val['invoice_id']){
							if($val['modify_due_to'] == 'Senior Discount'){
								$total_cost = str_replace('-', '', $val['total_cost']);
								$SC += $total_cost;
							}elseif ($val['modify_due_to'] == 'Bottle Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$BD += $total_cost;
							}elseif ($val['modify_due_to'] == 'Employee Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$ED += $total_cost;
							}elseif ($val['modify_due_to'] == 'Special Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$SD += $total_cost;
							}elseif ($val['modify_due_to'] == 'RPC Marketing') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$RM += $total_cost;
							}elseif ($val['modify_due_to'] == 'Breakage / Damage Medicines') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$BDM += $total_cost;
							}
							if($val['invoice_id']){ $id3 	= $val['invoice_id']; }
						}
						
					}
					$cost_modifier_cost = array(
							"id"		=> $id3,
							"SC_cost"	=> number_format($SC,2,".",""),
							"BD_cost"	=> number_format($BD,2,".",""),
							"ED_cost" 	=> number_format($ED,2,".",""),
							"SD_cost"   => number_format($SD,2,".",""),
							"RM_cost" 	=> number_format($RM,2,".",""),
							"BDM_cost"  => number_format($BDM,2,".",""),
					);
					//unset($id3);
				}
			}else{
				unset($cost_modifier_cost);	
			}
				
			$due = date('Y/m/d', strtotime($inv['date_claimed']));
			$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));
			
			$invoice_array[] = array(
				$inv['order_no'],
				$inv['estimated_date'],
				$inv['invoice_internal'],
				$inv['invoice_num'],
				$or_number,
				!empty($doctor['full_name']) ? $doctor['full_name'] : $doc_attending['full_name'],
				$inv['patient_name'],
				$inv['age'],
				$inv['tin'],
				$inv['invoice_date'],
				$inv['date_claimed'] != 0 ? $real_duedate : $inv['date_claimed'],
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_gross_sales']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['cost_modifier']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['net_sales']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['net_sales_vat']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_net_sales_vat']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_amount_paid']), //$value['total_amount_paid'], 
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['remaining_balance']),
				$inv['status'],
				($inv['taken'] ? 'Yes': 'No'),
				$inv['date_claimed'],
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpc_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['alist_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpp_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['gh_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['nebmyo_total_price'],2,".","")),
				
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['initialConsulation_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['followUp_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTesting_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['total_consultation_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTestingProcedure_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherTest_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['others_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['yp_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['armin_cost_RMD'],2,".","")),
				"", 
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpc_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['alist_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpp_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['gh_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['nebmyo_total_price_OtherRMD'],2,".","")),
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['initialConsulation_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['followUp_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTesting_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['total_consultation_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTestingProcedure_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherTest_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['others_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherYP_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherArmin_cost_otherRMD'],2,".","")),
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['SC_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['BD_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['ED_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['SD_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['RM_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['BDM_cost'],2,".","")),
				"",
				($inv['status'] == "Void" ? $inv['remarks'] : $patient_order['remarks']),
			);
			unset($or_number);
			unset($med);
			unset($other_charges_data);
			unset($cost_modifier_cost);
		}
			
		$data['invoice_array'] = $invoice_array;
	
		$data['filename'] = "ReportsGenerated-" . $from_date . "-" . $to_date;

		$this->load->view("accounting/generate_excel", $data);

	}

	function generateReportMonthly(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$post = $this->input->post();

		if($post){

			$fields = array(
				"a.id",
				"c.order_no",
				"c.estimated_date",
				"a.invoice_internal", 
				"a.invoice_num",  
				"b.patient_name", 
				"b.age",
				"b.tin", 
				"b.doc_attending_id",
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_gross_sales", 
				"a.cost_modifier", 
				"a.net_sales", 
				"a.net_sales_vat", 
				"a.total_net_sales_vat",  
				"a.total_amount_paid", 
				"a.remaining_balance", 
				"a.status",
				"a.taken",
				"a.date_claimed",
				"a.date_created",
				"a.remarks"
				);

			$year = date('Y');
			$params = array(
				"fields" 	=> $fields,
				"from_date" => $year . "-" . $post['month'] . "-01",
				"to_date"	=> $year . "-" . $post['month'] . "-31"
			);
			if($post['type_of_sale'] == "AllSales"){
				$invoice = Invoice::findDateRange($params);
			} else {
				$invoice = Invoice::findDateRangeAR($params);
			}

			if($invoice){
				$json['is_successful'] = true;
			} else {

				switch($post['month']){
					case '01':
						$full_month = "January";
						break;
					case '02':
						$full_month = "February";
						break;
					case '03':
						$full_month = "March";
						break;
					case '04':
						$full_month = "April";
						break;
					case '05':
						$full_month = "May";
						break;
					case '06':
						$full_month = "June";
						break;
					case '07':
						$full_month = "July";
						break;
					case '08':
						$full_month = "August";
						break;
					case '09':
						$full_month = "September";
						break;
					case '10':
						$full_month = "October";
						break;
					case '11':
						$full_month = "November";
						break;
					case '12':
						$full_month = "December";
						break;
				}
				$json['message']	   = "No results found for the month of " . $full_month . ".";
				$json['is_successful'] = false;
			}
				
			
		} else {
			$json['is_successful'] = false;

		}

		echo json_encode($json);
	}

	function download_report_generate_month(){
		// Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$month = $this->uri->segment(3);
		$type_of_sale = $this->uri->segment(4);
		$year = date('Y');

		$fields = array(
				"a.id",
				"a.order_id",
				"c.order_no",
				"c.patient_id",
				"c.estimated_date",
				"c.doc_attending_id",
				"a.invoice_internal", 
				"a.invoice_num",  
				"b.patient_name", 
				"b.age",
				"b.tin",
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_gross_sales", 
				"a.cost_modifier", 
				"a.net_sales", 
				"a.net_sales_vat", 
				"a.total_net_sales_vat",  
				"a.total_amount_paid", 
				"a.remaining_balance", 
				"a.status",
				"a.taken",
				"a.date_claimed",
				"a.date_created",	
				"a.remarks"	
				);

		$params = array(
			"fields" 	=> $fields,
			"from_date" => $year . "-" . $month . "-01",
			"to_date"	=> $year . "-" . $month . "-31"
		);
		if($type_of_sale == "AllSales"){
			$invoice = Invoice::findDateRange($params);
		} else {
			$invoice = Invoice::findDateRangeAR($params);
		}
		$invoice_array[] = array("Order Number","Order Estimated EndDate","Invoice ID", "Invoice Number", "OR Number", "Attending Doctor", "Patient Name", "Age", "TIN", "Invoice Date", "Due Date", "Total Gross Sales", "Cost Modifier", "Net Sales", "Net Sales VAT", "Total Net Sales Vat","Total Amount Paid", "Total Amount Outstanding", "Status", "Have been Claimed", "Date Claimed","", "RPC (RMD)", "Alist (RMD)","RPP (RMD)","GH(Sci./Nordi.)-(RMD)","NEBIDO/Myopep (RMD)", "", "Consultation-Initial (RMD)", "Consultation-Follow up (RMD)", "Consultation-Genetic Testing (RMD)", "TOTAL CONSULT (RMD)", "Genetic Test-Procedure (RMD)","Other Test (RMD)","Others (RMD)","YP Test (RMD)","Armin Test (RMD)", "","RPC (other MD)", "Alist (other MD)","RPP (other MD)","GH(Sci./Nordi.)-(other MD)","NEBIDO/Myopep (other MD)","","Consultation-Initial (other MD)", "Consultation-Follow up (other MD)", "Consultation-Genetic Testing (other MD)","TOTAL CONSULT (other MD)", "Genetic Test-Procedure (other MD)", "Other Test (other RMD)","Others (other RMD)","YP Test (other MD)","Armin Test (other MD)", "","Senior Discount", "Bottle Discount", "Employee Discount", "Special Discount", "RPC Marketing","Breakage / Damaged Medicines", "", "Remarks"
			);

		foreach ($invoice as $key => $inv) {
		//Columns for RPC/AList Medicines
			
			$doctor = Doctors::findById(array("id" => $inv['doc_attending_id']));	//get the full_name 
			$patients = Patients::findById(array("id" => $inv['patient_id']));      //get the attending_doctor if ever wala sya doc_attending sa invoice
			$doc_attending = Doctors::findById(array("id" => $patients['doc_attending_id'])); //then get the full name

			$attending_doctor_id = !empty($doctor['id']) ? $doctor['id'] : $doc_attending['id']; //attending_doctor

			$order_meds = Orders_Meds::findAllbyOrdersAndStock(array("id" => $inv['order_id']));
			$invoice_receipts = Invoice_Receipts::findAllOR(array("invoice_id" => $inv['id']));
			$patient_order = Orders::findById(array("id" => $inv['order_id']));
			foreach( $invoice_receipts as $key => $value){
				$or_number .= $value['or_number'].',';
			}

			if(!empty($order_meds)){
				foreach ($order_meds as $key => $value) {
					$all_ids[] = $value['order_id'];
				}
				$med_ids = array_keys(array_flip($all_ids));


				foreach ($med_ids as $key => $value1) {
					$order = Orders::findById(array("id" => $value1));
					$patient = Patients::findById(array("id" => $order['patient_id']));

					$rpc_meds = 0;
					$alist_meds = 0;
					$GH = 0;
					$nebidoMyoslim_meds = 0;
					$rpp_meds = 0;

					$rpc_meds1 = 0;
					$alist_meds1 = 0;
					$GH1 = 0;
					$nebidoMyoslim_meds1 = 0;
					$rpp_meds1 = 0;
					
					if($attending_doctor_id == '13'){
						foreach ($order_meds as $k => $val) {
							if($val['product_no'] == '2015-01-003-3003' OR $val['product_no'] == '2015-01-004-3002' OR $val['product_no'] == '2015-01-004-3001'){
								$GH += $val['total_price'];
							}elseif($val['product_no'] == '2015-01-003-3001' OR $val['product_no'] == '2015-01-005-3001' OR $val['product_no'] == '2015-01-005-3002'){
								$nebidoMyoslim_meds += $val['total_price'];
							}elseif($val['stock'] == 'A-List'){
								$alist_meds += $val['total_price'];
							}elseif($val['stock'] == 'RPP'){
								$rpp_meds += $val['total_price'];
							}else{
								$rpc_meds += $val['total_price'];
							}
							if($val['order_id']){ $id 	= $val['order_id']; }
						}
					}else{
						foreach ($order_meds as $k => $val) {
							if($val['product_no'] == '2015-01-003-3003' OR $val['product_no'] == '2015-01-004-3002' OR $val['product_no'] == '2015-01-004-3001'){
									$GH1 += $val['total_price'];
								}elseif($val['product_no'] == '2015-01-003-3001' OR $val['product_no'] == '2015-01-005-3001' OR $val['product_no'] == '2015-01-005-3002'){
									$nebidoMyoslim_meds1 += $val['total_price'];
								}elseif($val['stock'] == 'A-List'){
									$alist_meds1 += $val['total_price'];
								}elseif($val['stock'] == 'RPP'){
									$rpp_meds1 += $val['total_price'];
								}else{
									$rpc_meds1 += $val['total_price'];
								}
								if($val['order_id']){$id 	= $val['order_id'];}
						}
					}

					$med = array(
						"id"				=> $id,
						"rpc_total_price"	=> number_format($rpc_meds,2,".",""),
						"alist_total_price"	=> number_format($alist_meds,2,".",""),
						"rpp_total_price"	=> number_format($rpp_meds,2,".",""),
						"gh_total_price" 	=> number_format($GH,2,".",""),
						"nebmyo_total_price" => number_format($nebidoMyoslim_meds,2,".",""),
						"rpc_total_price_OtherRMD"	=> number_format($rpc_meds1,2,".",""),
						"alist_total_price_OtherRMD"	=> number_format($alist_meds1,2,".",""),
						"rpp_total_price_OtherRMD"	=> number_format($rpp_meds1,2,".",""),
						"gh_total_price_OtherRMD" 	=> number_format($GH1,2,".",""),
						"nebmyo_total_price_OtherRMD" => number_format($nebidoMyoslim_meds1,2,".","")
					);	

					//unset($id);
				}
			}else{
				unset($med);
			}
			//Column for Other charges RMD and other RMD
			$other_charges = Invoice_Other_Charges::findAllByInvoiceIdWithDescId(array("invoice_id" => $inv['id']));
			if(!empty($other_charges)){
				foreach ($other_charges as $key => $value) {
					$all_ids2[] = $value['invoice_id'];
				}
				$med_ids2 = array_keys(array_flip($all_ids2));

				foreach ($med_ids2 as $key => $value2) {
					$initialConsulation_RMD = 0;
					$followUp_RMD = 0;
					$geneticTesting_RMD = 0;
					$geneticTestingProcedure_RMD = 0;
					$otherTest_RMD = 0;
					$others_RMD = 0;
					$yp_RMD = 0;
					$armin_RMD = 0;

					$initialConsulation_otherRMD = 0;
					$followUp_otherRMD = 0;
					$geneticTesting_otherRMD = 0;
					$geneticTestingProcedure_otherRMD = 0;
					$otherTest_otherRMD = 0;
					$others_otherRMD = 0;
					$yp_otherRMD = 0;
					$armin_otherRMD = 0;

				//OPTION 1: Doctor_ID from Other Charges 
					/*foreach ($other_charges as $k => $val) {
						if($value2 == $val['invoice_id']){
							if($val['doctor_id'] == '13'){
								if($val['category'] == 'Consultation' && $val['description_id'] == '1'){
									$initialConsulation_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '2'){
									$followUp_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '3'){
									$geneticTesting_RMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_RMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_RMD += $val['cost'];
								}else{
									$others_RMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}else{
								if($val['category'] == 'Consultation' && $val['description_id'] == '4'){
									$initialConsulation_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '5'){
									$followUp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '6'){
									$geneticTesting_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_otherRMD += $val['cost'];
								}else{
									$others_otherRMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}
						}
					}	*/

					//OPTION 2: Doctor_ID from Order/Invoice 
					foreach ($other_charges as $k => $val) {
						if($value2 == $val['invoice_id']){
							if($attending_doctor_id == '13'){
								if($val['category'] == 'Consultation' && $val['description_id'] == '1'){
									$initialConsulation_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '2'){
									$followUp_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '3'){
									$geneticTesting_RMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_RMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_RMD += $val['cost'];
								}elseif($val['category'] == 'yp'){
									$yp_RMD += $val['cost'];
								}elseif($val['category'] == 'armin'){
									$armin_RMD += $val['cost'];
								}else{
									$others_RMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}else{
								if($val['category'] == 'Consultation' && $val['description_id'] == '4'){
									$initialConsulation_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '5'){
									$followUp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '6'){
									$geneticTesting_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_otherRMD += $val['cost'];
								}elseif($val['category'] == 'yp'){
									$yp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'armin'){
									$armin_otherRMD += $val['cost'];
								}else{
									$others_otherRMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}
						}
					}	

					$other_charges_data = array(
						"id"									=> $id2,
						"initialConsulation_cost_RMD"			=> number_format($initialConsulation_RMD,2,".",""),
						"followUp_cost_RMD"						=> number_format($followUp_RMD,2,".",""),
						"geneticTesting_cost_RMD" 				=> number_format($geneticTesting_RMD,2,".",""),
						"geneticTestingProcedure_cost_RMD" 		=> number_format($geneticTestingProcedure_RMD,2,".",""),
						"otherTest_cost_RMD" 					=> number_format($otherTest_RMD,2,".",""),
						"others_cost_RMD" 	 					=> number_format($others_RMD,2,".",""),
						"yp_cost_RMD" 	 						=> number_format($yp_RMD,2,".",""),
						"armin_cost_RMD" 	 					=> number_format($armin_RMD,2,".",""),
						"initialConsulation_cost_otherRMD"		=> number_format($initialConsulation_otherRMD,2,".",""),
						"followUp_cost_otherRMD"				=> number_format($followUp_otherRMD,2,".",""),
						"geneticTesting_cost_otherRMD" 			=> number_format($geneticTesting_otherRMD,2,".",""),
						"geneticTestingProcedure_cost_otherRMD" => number_format($geneticTestingProcedure_otherRMD,2,".",""),
						"otherTest_cost_otherRMD" 				=> number_format($otherTest_otherRMD,2,".",""),
						"others_cost_otherRMD" 	 				=> number_format($others_otherRMD,2,".",""),
						"otherYP_cost_RMD" 	 					=> number_format($yp_otherRMD,2,".",""),
						"otherArmin_cost_RMD" 	 				=> number_format($armin_otherRMD,2,".",""),
						"total_consultation_RMD" 				=>  number_format($initialConsulation_RMD + $followUp_RMD + $geneticTesting_RMD,2,".",""),
						"total_consultation_otherRMD" 			=> number_format($initialConsulation_otherRMD + $followUp_otherRMD + $geneticTesting_otherRMD,2,".","")
					);
					//unset($id2);
				}
			}else{
				unset($other_charges_data);
			}
		
			//Column for Cost Modifier

			$cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $inv['id']));
			if(!empty($cost_modifier)){
				foreach ($cost_modifier as $key => $value) {
					$all_ids3[] = $value['invoice_id'];
				}
				$med_ids3 = array_keys(array_flip($all_ids3));

				foreach ($med_ids3 as $key => $value3) {
					$SC = 0;
					$BD = 0;
					$ED = 0;
					$SD = 0;
					$RM = 0;
					$BDM = 0;

					foreach ($cost_modifier as $k => $val) {

						if($value3 == $val['invoice_id']){
							if($val['modify_due_to'] == 'Senior Discount'){
								$total_cost = str_replace('-', '', $val['total_cost']);
								$SC += $total_cost;
							}elseif ($val['modify_due_to'] == 'Bottle Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$BD += $total_cost;
							}elseif ($val['modify_due_to'] == 'Employee Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$ED += $total_cost;
							}elseif ($val['modify_due_to'] == 'Special Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$SD += $total_cost;
							}elseif ($val['modify_due_to'] == 'RPC Marketing') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$RM += $total_cost;
							}elseif ($val['modify_due_to'] == 'Breakage / Damage Medicines') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$BDM += $total_cost;
							}
							if($val['invoice_id']){ $id3 	= $val['invoice_id']; }
						}
						
					}
					$cost_modifier_cost = array(
							"id"		=> $id3,
							"SC_cost"	=> number_format($SC,2,".",""),
							"BD_cost"	=> number_format($BD,2,".",""),
							"ED_cost" 	=> number_format($ED,2,".",""),
							"SD_cost"   => number_format($SD,2,".",""),
							"RM_cost" 	=> number_format($RM,2,".",""),
							"BDM_cost" 	=> number_format($BDM,2,".",""),
					);
					//unset($id3);
				}
			}else{
				unset($cost_modifier_cost);	
			}
				
				$due = date('Y/m/d', strtotime($inv['date_claimed']));
				$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));
			
			$invoice_array[] = array(
				$inv['order_no'],
				$inv['estimated_date'],
				$inv['invoice_internal'],
				$inv['invoice_num'],
				$or_number,
				!empty($doctor['full_name']) ? $doctor['full_name'] : $doc_attending['full_name'],
				$inv['patient_name'],
				$inv['age'],
				$inv['tin'],
				$inv['invoice_date'],
				$inv['date_claimed'] != 0 ? $real_duedate : $inv['date_claimed'],
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_gross_sales']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['cost_modifier']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['net_sales']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['net_sales_vat']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_net_sales_vat']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_amount_paid']), //$value['total_amount_paid'], 
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['remaining_balance']),
				$inv['status'],
				($inv['taken'] ? 'Yes': 'No'),
				$inv['date_claimed'],
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpc_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['alist_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpp_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['gh_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['nebmyo_total_price'],2,".","")),
				
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['initialConsulation_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['followUp_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTesting_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['total_consultation_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTestingProcedure_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherTest_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['others_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['yp_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['armin_cost_RMD'],2,".","")),
				"", 
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpc_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['alist_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpp_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['gh_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['nebmyo_total_price_OtherRMD'],2,".","")),
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['initialConsulation_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['followUp_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTesting_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['total_consultation_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTestingProcedure_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherTest_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['others_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherYP_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherArmin_cost_otherRMD'],2,".","")),
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['SC_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['BD_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['ED_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['SD_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['RM_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['BDM_cost'],2,".","")),
				"",
				($inv['status'] == "Void" ? $inv['remarks'] : $patient_order['remarks']),
			);
			unset($or_number);
			unset($med);
			unset($other_charges_data);
			unset($cost_modifier_cost);
		}	
		
		$data['invoice_array'] = $invoice_array;
		
		switch($month){
			case '01':
				$full_month = "January";
				break;
			case '02':
				$full_month = "February";
				break;
			case '03':
				$full_month = "March";
				break;
			case '04':
				$full_month = "April";
				break;
			case '05':
				$full_month = "May";
				break;
			case '06':
				$full_month = "June";
				break;
			case '07':
				$full_month = "July";
				break;
			case '08':
				$full_month = "August";
				break;
			case '09':
				$full_month = "September";
				break;
			case '10':
				$full_month = "October";
				break;
			case '11':
				$full_month = "November";
				break;
			case '12':
				$full_month = "December";
				break;
		}
		$data['filename'] = "ReportsGenerated-Month-of-" . $full_month;

		$this->load->view("accounting/generate_excel", $data);

	}

	function generateReportYearly(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$post = $this->input->post();

		if($post){

			$fields = array(
				"a.id",
				"c.order_no",
				"c.estimated_date",
				"a.invoice_internal", 
				"a.invoice_num",  
				"b.patient_name", 
				"b.age",
				"b.tin", 
				"b.doc_attending_id",
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_gross_sales", 
				"a.cost_modifier", 
				"a.net_sales", 
				"a.net_sales_vat", 
				"a.total_net_sales_vat",  
				"a.total_amount_paid", 
				"a.remaining_balance", 
				"a.status",
				"a.taken",
				"a.date_claimed",
				"a.date_created",
				"a.remarks"
				);

			$year = trim($post['year']);
			$params = array(
				"fields" 	=> $fields,
				"from_date" => $year . "-01-01",
				"to_date"	=> $year . "-12-31"
			);

			if($post['type_of_sale'] == "AllSales"){
				$invoice = Invoice::findDateRange($params);
			} else {
				$invoice = Invoice::findDateRangeAR($params);
			}
			if($invoice){
				$json['is_successful'] = true;
			} else {

				$json['message']	   = "No results found for the Year of " . $year . ".";
				$json['is_successful'] = false;
			}
				
			
		} else {
			$json['is_successful'] = false;

		}

		echo json_encode($json);
	}

	function download_report_generate_year(){
		// Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$year = $this->uri->segment(3);
		$type_of_sale = $this->uri->segment(4);
		$year = trim($year);
		
		$fields = array(
				"a.id",
				"a.order_id",
				"c.order_no",
				"c.patient_id",
				"c.estimated_date",
				"c.doc_attending_id",
				"a.invoice_internal", 
				"a.invoice_num",  
				"b.patient_name", 
				"b.age",
				"b.tin",
				"a.invoice_date", 
				"a.due_date", 
				"a.aging", 
				"a.total_gross_sales", 
				"a.cost_modifier", 
				"a.net_sales", 
				"a.net_sales_vat", 
				"a.total_net_sales_vat",  
				"a.total_amount_paid", 
				"a.remaining_balance", 
				"a.status",
				"a.taken",
				"a.date_claimed",
				"a.date_created",		
				"a.remarks"
				);
		$params = array(
			"fields" 	=> $fields,
			"from_date" => $year . "-01-01",
			"to_date"	=> $year . "-12-31"
		);

		if($type_of_sale == "AllSales"){
			$invoice = Invoice::findDateRange($params);
		} else {
			$invoice = Invoice::findDateRangeAR($params);
		}
		$invoice_array[] = array("Order Number","Order Estimated EndDate","Invoice ID", "Invoice Number", "OR Number", "Attending Doctor", "Patient Name", "Age", "TIN", "Invoice Date", "Due Date", "Total Gross Sales", "Cost Modifier", "Net Sales", "Net Sales VAT", "Total Net Sales Vat","Total Amount Paid", "Total Amount Outstanding", "Status", "Have been Claimed", "Date Claimed","", "RPC (RMD)", "Alist (RMD)","RPP (RMD)","GH(Sci./Nordi.)-(RMD)","NEBIDO/Myopep (RMD)", "", "Consultation-Initial (RMD)", "Consultation-Follow up (RMD)", "Consultation-Genetic Testing (RMD)", "TOTAL CONSULT (RMD)", "Genetic Test-Procedure (RMD)","Other Test (RMD)","Others (RMD)","YP Test (RMD)","Armin Test (RMD)", "","RPC (other MD)", "Alist (other MD)","RPP (other MD)","GH(Sci./Nordi.)-(other MD)","NEBIDO/Myopep (other MD)","","Consultation-Initial (other MD)", "Consultation-Follow up (other MD)", "Consultation-Genetic Testing (other MD)","TOTAL CONSULT (other MD)", "Genetic Test-Procedure (other MD)", "Other Test (other RMD)","Others (other RMD)","YP Test (other MD)","Armin Test (other MD)", "","Senior Discount", "Bottle Discount", "Employee Discount", "Special Discount", "RPC Marketing","Breakage / Damaged Medicines", "", "Remarks"
			);

		foreach ($invoice as $key => $inv) {
		//Columns for RPC/AList Medicines
			
			$doctor = Doctors::findById(array("id" => $inv['doc_attending_id']));	//get the full_name 
			$patients = Patients::findById(array("id" => $inv['patient_id']));      //get the attending_doctor if ever wala sya doc_attending sa invoice
			$doc_attending = Doctors::findById(array("id" => $patients['doc_attending_id'])); //then get the full name

			$attending_doctor_id = !empty($doctor['id']) ? $doctor['id'] : $doc_attending['id']; //attending_doctor

			$order_meds = Orders_Meds::findAllbyOrdersAndStock(array("id" => $inv['order_id']));
			$invoice_receipts = Invoice_Receipts::findAllOR(array("invoice_id" => $inv['id']));
			$patient_order = Orders::findById(array("id" => $inv['order_id']));
			foreach( $invoice_receipts as $key => $value){
				$or_number .= $value['or_number'].',';
			}

			if(!empty($order_meds)){
				foreach ($order_meds as $key => $value) {
					$all_ids[] = $value['order_id'];
				}
				$med_ids = array_keys(array_flip($all_ids));


				foreach ($med_ids as $key => $value1) {
					$order = Orders::findById(array("id" => $value1));
					$patient = Patients::findById(array("id" => $order['patient_id']));

					$rpc_meds = 0;
					$alist_meds = 0;
					$GH = 0;
					$nebidoMyoslim_meds = 0;
					$rpp_meds = 0;

					$rpc_meds1 = 0;
					$alist_meds1 = 0;
					$GH1 = 0;
					$nebidoMyoslim_meds1 = 0;
					$rpp_meds1 = 0;
					
					if($attending_doctor_id == '13'){
						foreach ($order_meds as $k => $val) {
							if($val['product_no'] == '2015-01-003-3003' OR $val['product_no'] == '2015-01-004-3002' OR $val['product_no'] == '2015-01-004-3001'){
								$GH += $val['total_price'];
							}elseif($val['product_no'] == '2015-01-003-3001' OR $val['product_no'] == '2015-01-005-3001' OR $val['product_no'] == '2015-01-005-3002'){
								$nebidoMyoslim_meds += $val['total_price'];
							}elseif($val['stock'] == 'A-List'){
								$alist_meds += $val['total_price'];
							}elseif($val['stock'] == 'RPP'){
								$rpp_meds += $val['total_price'];
							}else{
								$rpc_meds += $val['total_price'];
							}
							if($val['order_id']){ $id 	= $val['order_id']; }
						}
					}else{
						foreach ($order_meds as $k => $val) {
							if($val['product_no'] == '2015-01-003-3003' OR $val['product_no'] == '2015-01-004-3002' OR $val['product_no'] == '2015-01-004-3001'){
									$GH1 += $val['total_price'];
								}elseif($val['product_no'] == '2015-01-003-3001' OR $val['product_no'] == '2015-01-005-3001' OR $val['product_no'] == '2015-01-005-3002'){
									$nebidoMyoslim_meds1 += $val['total_price'];
								}elseif($val['stock'] == 'A-List'){
									$alist_meds1 += $val['total_price'];
								}elseif($val['stock'] == 'RPP'){
									$rpp_meds1 += $val['total_price'];
								}else{
									$rpc_meds1 += $val['total_price'];
								}
								if($val['order_id']){$id 	= $val['order_id'];}
						}
					}

					$med = array(
						"id"				=> $id,
						"rpc_total_price"	=> number_format($rpc_meds,2,".",""),
						"alist_total_price"	=> number_format($alist_meds,2,".",""),
						"rpp_total_price"	=> number_format($rpp_meds,2,".",""),
						"gh_total_price" 	=> number_format($GH,2,".",""),
						"nebmyo_total_price" => number_format($nebidoMyoslim_meds,2,".",""),
						"rpc_total_price_OtherRMD"	=> number_format($rpc_meds1,2,".",""),
						"alist_total_price_OtherRMD"	=> number_format($alist_meds1,2,".",""),
						"rpp_total_price_OtherRMD"	=> number_format($rpp_meds1,2,".",""),
						"gh_total_price_OtherRMD" 	=> number_format($GH1,2,".",""),
						"nebmyo_total_price_OtherRMD" => number_format($nebidoMyoslim_meds1,2,".","")
					);	

					//unset($id);
				}
			}else{
				unset($med);
			}
			//Column for Other charges RMD and other RMD
			$other_charges = Invoice_Other_Charges::findAllByInvoiceIdWithDescId(array("invoice_id" => $inv['id']));
			if(!empty($other_charges)){
				foreach ($other_charges as $key => $value) {
					$all_ids2[] = $value['invoice_id'];
				}
				$med_ids2 = array_keys(array_flip($all_ids2));

				foreach ($med_ids2 as $key => $value2) {
					$initialConsulation_RMD = 0;
					$followUp_RMD = 0;
					$geneticTesting_RMD = 0;
					$geneticTestingProcedure_RMD = 0;
					$otherTest_RMD = 0;
					$others_RMD = 0;
					$yp_RMD = 0;
					$armin_RMD = 0;

					$initialConsulation_otherRMD = 0;
					$followUp_otherRMD = 0;
					$geneticTesting_otherRMD = 0;
					$geneticTestingProcedure_otherRMD = 0;
					$otherTest_otherRMD = 0;
					$others_otherRMD = 0;
					$yp_otherRMD = 0;
					$armin_otherRMD = 0;

				//OPTION 1: Doctor_ID from Other Charges 
					/*foreach ($other_charges as $k => $val) {
						if($value2 == $val['invoice_id']){
							if($val['doctor_id'] == '13'){
								if($val['category'] == 'Consultation' && $val['description_id'] == '1'){
									$initialConsulation_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '2'){
									$followUp_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '3'){
									$geneticTesting_RMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_RMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_RMD += $val['cost'];
								}else{
									$others_RMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}else{
								if($val['category'] == 'Consultation' && $val['description_id'] == '4'){
									$initialConsulation_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '5'){
									$followUp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '6'){
									$geneticTesting_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_otherRMD += $val['cost'];
								}else{
									$others_otherRMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}
						}
					}	*/

					//OPTION 2: Doctor_ID from Order/Invoice 
					foreach ($other_charges as $k => $val) {
						if($value2 == $val['invoice_id']){
							if($attending_doctor_id == '13'){
								if($val['category'] == 'Consultation' && $val['description_id'] == '1'){
									$initialConsulation_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '2'){
									$followUp_RMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '3'){
									$geneticTesting_RMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_RMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_RMD += $val['cost'];
								}elseif($val['category'] == 'yp'){
									$yp_RMD += $val['cost'];
								}elseif($val['category'] == 'armin'){
									$armin_RMD += $val['cost'];	
								}else{
									$others_RMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}else{
								if($val['category'] == 'Consultation' && $val['description_id'] == '4'){
									$initialConsulation_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '5'){
									$followUp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Consultation' && $val['description_id'] == '6'){
									$geneticTesting_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Genetic Testing'){
									$geneticTestingProcedure_otherRMD += $val['cost'];
								}elseif($val['category'] == 'Other Test'){
									$otherTest_otherRMD += $val['cost'];
								}elseif($val['category'] == 'yp'){
									$yp_otherRMD += $val['cost'];
								}elseif($val['category'] == 'armin'){
									$armin_otherRMD += $val['cost'];
								}else{
									$others_otherRMD += $val['cost'];
								}
								if($val['invoice_id']){ $id2 	= $val['invoice_id']; }
							}
						}
					}	

					$other_charges_data = array(
						"id"									=> $id2,
						"initialConsulation_cost_RMD"			=> number_format($initialConsulation_RMD,2,".",""),
						"followUp_cost_RMD"						=> number_format($followUp_RMD,2,".",""),
						"geneticTesting_cost_RMD" 				=> number_format($geneticTesting_RMD,2,".",""),
						"geneticTestingProcedure_cost_RMD" 		=> number_format($geneticTestingProcedure_RMD,2,".",""),
						"otherTest_cost_RMD" 					=> number_format($otherTest_RMD,2,".",""),
						"others_cost_RMD" 	 					=> number_format($others_RMD,2,".",""),
						"yp_cost_RMD" 	 						=> number_format($yp_RMD,2,".",""),
						"armin_cost_RMD" 	 					=> number_format($armin_RMD,2,".",""),
						"initialConsulation_cost_otherRMD"		=> number_format($initialConsulation_otherRMD,2,".",""),
						"followUp_cost_otherRMD"				=> number_format($followUp_otherRMD,2,".",""),
						"geneticTesting_cost_otherRMD" 			=> number_format($geneticTesting_otherRMD,2,".",""),
						"geneticTestingProcedure_cost_otherRMD" => number_format($geneticTestingProcedure_otherRMD,2,".",""),
						"otherTest_cost_otherRMD" 				=> number_format($otherTest_otherRMD,2,".",""),
						"others_cost_otherRMD" 	 				=> number_format($others_otherRMD,2,".",""),
						"otherYP_cost_RMD" 	 					=> number_format($yp_otherRMD,2,".",""),
						"otherArmin_cost_RMD" 	 				=> number_format($armin_otherRMD,2,".",""),
						"total_consultation_RMD" 				=>  number_format($initialConsulation_RMD + $followUp_RMD + $geneticTesting_RMD,2,".",""),
						"total_consultation_otherRMD" 			=> number_format($initialConsulation_otherRMD + $followUp_otherRMD + $geneticTesting_otherRMD,2,".","")
					);
					//unset($id2);
				}
			}else{
				unset($other_charges_data);
			}
		
			//Column for Cost Modifier

			$cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $inv['id']));
			if(!empty($cost_modifier)){
				foreach ($cost_modifier as $key => $value) {
					$all_ids3[] = $value['invoice_id'];
				}
				$med_ids3 = array_keys(array_flip($all_ids3));

				foreach ($med_ids3 as $key => $value3) {
					$SC = 0;
					$BD = 0;
					$ED = 0;
					$SD = 0;
					$RM = 0;
					$BDM = 0;

					foreach ($cost_modifier as $k => $val) {

						if($value3 == $val['invoice_id']){
							if($val['modify_due_to'] == 'Senior Discount'){
								$total_cost = str_replace('-', '', $val['total_cost']);
								$SC += $total_cost;
							}elseif ($val['modify_due_to'] == 'Bottle Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$BD += $total_cost;
							}elseif ($val['modify_due_to'] == 'Employee Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$ED += $total_cost;
							}elseif ($val['modify_due_to'] == 'Special Discount') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$SD += $total_cost;
							}elseif ($val['modify_due_to'] == 'RPC Marketing') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$RM += $total_cost;
							}elseif ($val['modify_due_to'] == 'Breakage / Damage Medicines') {
								$total_cost = str_replace('-', '', $val['total_cost']);
								$BDM += $total_cost;
							}
							if($val['invoice_id']){ $id3 	= $val['invoice_id']; }
						}
						
					}
					$cost_modifier_cost = array(
							"id"		=> $id3,
							"SC_cost"	=> number_format($SC,2,".",""),
							"BD_cost"	=> number_format($BD,2,".",""),
							"ED_cost" 	=> number_format($ED,2,".",""),
							"SD_cost"   => number_format($SD,2,".",""),
							"RM_cost" 	=> number_format($RM,2,".",""),
							"BDM_cost" 	=> number_format($BDM,2,".",""),
					);
					//unset($id3);
				}
			}else{
				unset($cost_modifier_cost);	
			}
				
			$due = date('Y/m/d', strtotime($inv['date_claimed']));
			$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));
			
			$invoice_array[] = array(
				$inv['order_no'],
				$inv['estimated_date'],
				$inv['invoice_internal'],
				$inv['invoice_num'],
				$or_number,
				!empty($doctor['full_name']) ? $doctor['full_name'] : $doc_attending['full_name'],
				$inv['patient_name'],
				$inv['age'],
				$inv['tin'],
				$inv['invoice_date'],
				$inv['date_claimed'] != 0 ? $real_duedate : $inv['date_claimed'],
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_gross_sales']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['cost_modifier']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['net_sales']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['net_sales_vat']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_net_sales_vat']),
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['total_amount_paid']), //$value['total_amount_paid'], 
				($inv['status'] == "Void" ? number_format(0,2,".",""): $inv['remaining_balance']),
				$inv['status'],
				($inv['taken'] ? 'Yes': 'No'),
				$inv['date_claimed'],
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpc_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['alist_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpp_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['gh_total_price'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['nebmyo_total_price'],2,".","")),
				
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['initialConsulation_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['followUp_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTesting_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['total_consultation_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTestingProcedure_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherTest_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['others_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['yp_cost_RMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['armin_cost_RMD'],2,".","")),
				"", 
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpc_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['alist_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['rpp_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['gh_total_price_OtherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($med['nebmyo_total_price_OtherRMD'],2,".","")),
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['initialConsulation_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['followUp_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTesting_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['total_consultation_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['geneticTestingProcedure_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherTest_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['others_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherYP_cost_otherRMD'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($other_charges_data['otherArmin_cost_otherRMD'],2,".","")),
				"",
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['SC_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['BD_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['ED_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['SD_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['RM_cost'],2,".","")),
				($inv['status'] == "Void" ? number_format(0,2,".",""): number_format($cost_modifier_cost['BDM_cost'],2,".","")),
				"",
				($inv['status'] == "Void" ? $inv['remarks'] : $patient_order['remarks']),
			);
			unset($or_number);
			unset($med);
			unset($other_charges_data);
			unset($cost_modifier_cost);
		}	
		
		$data['invoice_array'] = $invoice_array;

		$data['filename'] = "ReportsGenerated-Year-of-" . $year;

		$this->load->view("accounting/generate_excel", $data);

	}

	function downloadsummary_pdf(){
		$invoice_id = $this->uri->segment(3);

		$data['invoice'] = $invoice = Invoice::findById(array("id"=> $invoice_id));
		$order = Invoice::findById(array("id" => $invoice['id']));
		$meds  = Orders_Meds::findbyOrderId(array("order_id" => $order['order_id']));

		$data['order_2'] = $order_2 = Orders::findById(array("id"=> $invoice['order_id']));
		$data['pharmacy'] = $trimmed = trim($order_2['pharmacy'], " \t.");

		$data['other_charges'] = $other_charges = Invoice_Other_Charges::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
		$data['cost_modifier'] = $cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
		$data['collections']   = $collections 	= Invoice_Receipts::findAllByInvoiceId(array("invoice_id" => $invoice['id']));

		foreach ($meds as $key => $value) {
				$a = Inventory::findById(array("id" => $value['med_id']));
				$dosage = Dosage_Type::findById(array("id" => $a['dosage_type']));
				$rpc_meds[] = array(
					"id"			=> $value['id'],
					"medicine_id" 	=> $value['med_id'],
					"medicine_name"	=> $a['medicine_name'],
					"dosage" 		=> $a['dosage'],
					"dosage_type"	=> $dosage['abbreviation'],
					"price"			=> $value['price'],
					"quantity"		=> $value['quantity'],
					"total_price"	=> $value['total_price'],
				);
			}

		$invoices = Invoice_Receipts::findAllByInvoiceId(array("invoice_id"=>$invoice['id']));
		$all_credits = 0;
		foreach ($invoices as $key => $value) {
			$credit = Invoice_Receipts_Credit::findAllByORid(array("or_id" => $value['id']));
			if($credit) {
				foreach ($credit as $key2 => $value2) {
					$all_credits += $value2['price'];
				}
			}
			
		}

		$data['credits'] = $all_credits;
		$data['rpc_meds'] = $rpc_meds;
		//debug_array($rpc_meds);
		$data['patient'] = $patient = Patients::findById(array("id" => $invoice['patient_id']));
		$data['patient_code'] = $patient['patient_code'];

		$this->load->library('tcpdf/tcpdf');
		$this->load->view('accounting/generatesummary_pdf',$data);

	}

	function download_pdf(){
		$invoice_id = $this->uri->segment(3);

		/*$dir = "patient/test/";
			if(!is_dir($dir)) {
				mkdir($dir,0777,true);
			}*/
		$data['invoice'] = $invoice = Invoice::findById(array("id"=> $invoice_id));
		$order = Invoice::findById(array("id" => $invoice['id']));
		$meds  = Orders_Meds::findbyOrderId(array("order_id" => $order['order_id']));

		//$meds = Invoice_Med::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['other_charges'] = $other_charges = Invoice_Other_Charges::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['cost_modifier'] = $cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $invoice['id']));

			$data['collections']   = $collections 	= Invoice_Receipts::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			foreach ($meds as $key => $value) {
				$a = Inventory::findById(array("id" => $value['medicine_id']));
				$dosage = Dosage_Type::findById(array("id" => $a['dosage_type']));
				$rpc_meds[] = array(
					"id"			=> $value['id'],
					"medicine_id" 	=> $value['medicine_id'],
					"medicine_name"	=> $a['medicine_name'],
					"dosage" 		=> $a['dosage'],
					"dosage_type"	=> $dosage['abbreviation'],
					"price"			=> $value['price'],
					"quantity"		=> $value['quantity'],
					"total_price"	=> $value['total_price'],
				);
			}

		$invoices = Invoice_Receipts::findAllByInvoiceId(array("invoice_id"=>$invoice['id']));
		// debug_array($invoices);
		$all_credits = 0;
		foreach ($invoices as $key => $value) {
			$credit = Invoice_Receipts_Credit::findAllByORid(array("or_id" => $value['id']));
			if($credit) {
				foreach ($credit as $key2 => $value2) {
					$all_credits += $value2['price'];
				}
			}
			
		}

		// debug_array($all_credits);
		$data['credits'] = $all_credits;
		

		$data['rpc_meds'] = $rpc_meds;
		$data['patient'] = $patient = Patients::findById(array("id" => $invoice['patient_id']));
		$data['patient_code'] = $patient['patient_code'];
		// debug_array($patient);

		$this->load->library('tcpdf/tcpdf');
		$this->load->view('accounting/generate_pdf',$data);
	}

	function generate_invoice(){
		$filename = $this->uri->segment(3);
		$invoice_id = $this->uri->segment(4);

		$data['invoice'] = $invoice = Invoice::findById(array("id"=> $invoice_id));
		$order = Invoice::findById(array("id" => $invoice['id']));
		$meds  = Orders_Meds::findbyOrderId(array("order_id" => $order['order_id']));
		
		$data['other_charges'] = $other_charges = Invoice_Other_Charges::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
		$data['cost_modifier'] = $cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
		$data['collections']   = $collections 	= Invoice_Receipts::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
		
			foreach ($meds as $key => $value) {
				$a = Inventory::findById(array("id" => $value['medicine_id']));
				$dosage = Dosage_Type::findById(array("id" => $a['dosage_type']));
				$rpc_meds[] = array(
					"id"			=> $value['id'],
					"medicine_id" 	=> $value['medicine_id'],
					"medicine_name"	=> $a['medicine_name'],
					"dosage" 		=> $a['dosage'],
					"dosage_type"	=> $dosage['abbreviation'],
					"price"			=> $value['price'],
					"quantity"		=> $value['quantity'],
					"total_price"	=> $value['total_price'],
				);
			}

		$invoices = Invoice_Receipts::findAllByInvoiceId(array("invoice_id"=>$invoice['id']));
		
		$all_credits = 0;
		foreach ($invoices as $key => $value) {
			$credit = Invoice_Receipts_Credit::findAllByORid(array("or_id" => $value['id']));
			if($credit) {
				foreach ($credit as $key2 => $value2) {
					$all_credits += $value2['price'];
				}
			}
		}

		$data['credits'] = $all_credits;
		
		$data['rpc_meds'] = $rpc_meds;
		$data['patient'] = $patient = Patients::findById(array("id" => $invoice['patient_id']));
		$data['patient_code'] = $patient['patient_code'];

		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		
		$information[] = array("", "", "", "", date("Y-m-d"));
		$information[] = array("", "",$patient['patient_name'], "", ($invoice['payment_terms'] == "COD" ? $invoice['payment_terms'] : $invoice['payment_terms'] . " Days"));
		$information[] = array("", "",$patient['tin'] ? $patient['tin'] : '                                            ');
		$information[] = array("", "",($patient['address'] ? $patient['address'] : '                                            '));
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");

		$total_price = 0;
		foreach ($rpc_meds as $key => $value) {
			$total_price += $value['total_price'];
		}
		$information[] = array("1", "LOT", "Medication", "", (number_format($total_price, 2, '.', ',')));
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");

		foreach ($other_charges as $key => $value) {
			$description_charges = Other_Charges::findById(array("id" => $value['description_id']));

			$information[] = array($value['quantity'], "LOT", $description_charges['r_centers'], number_format($value['cost_per_item'], 2, '.', ','), number_format($value['cost'], 2, '.', ','));
		}

		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");

		foreach ($cost_modifier as $key => $value) {
			$information[] = array("", "", $value['applies_to'] . ' - ' .$value['modifier_type'] . ' ' . $value['modify_due_to'], $value['cost_modifier'] . ' ' . $value['cost_type'], number_format($value['total_cost'], 2, '.', ','));
		}

		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		

		$information[] = array("", "", "Supplements can be returned within 15 days", "", "");
		$information[] = array("", "", "from the date of purchase upon approval.", "TOTAL SALES", number_format($invoice['net_sales'], 2, '.', ','));
		$information[] = array("", "", "FOR CHECK PAYMENTS:", "", "");
		$information[] = array("", "", "ROYAL PREVENTIVE CLINIC, INC.", "LESS: Credits", number_format($credits, 2, '.', ','));

		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		$information[] = array("");
		
		$information[] = array("", "", "", "", number_format($invoice['total_net_sales_vat'] - $credits, 2, '.', ','));
		
		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}


	function update_invoice_status(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$invoice_meds = Invoice_Med::findInvoiceId(array("id" => $post['id']));
		$invoice = Invoice::findById(array("id"=> $post['id']));
		if(empty($invoice_meds)){
			$meds = Reserved_Meds::findAllByOrderId(array("id" => $invoice['order_id']));
				foreach ($meds as $key => $value) {
					$medicine = Inventory::findbyId(array("id" => $value['med_id']));
					$medicine = array(
						"invoice_id" 		=> $invoice['id'],
						"type"		 		=> $medicine['stock'],
						"medicine_id" 		=> $value['med_id'],
						"batch_id"			=> $value['batch_id'],
						"quantity" 			=> $value['quantity'],
						"price" 			=> (float) $value['price'],
						"total_price" 		=> (float) $value['total_price'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $session_id,
						);
					Invoice_Med::save($medicine);
					unset($medicine);
				}
			}
		$params = array(
				"taken" => 1,
				"date_claimed" => date("Y-m-d H:i:s")
			);
		Invoice::save($params,$post['id']);

		$reserved_meds = Reserved_Meds::findAllByOrderId(array("id" => $invoice['order_id']));	
				
			foreach ($reserved_meds as $key => $value2) {
				$medicine 			= Inventory::findById(array("id"=>$value2['med_id']));
				/*$zero = "000";
				$zero1 = "0000";
				$invoice_number = $invoice['invoice_num'] >= 10 ? $zero ."". $invoice['invoice_num'] : $zero1 ."". $invoice['invoice_num'];*/
				$invoice_number = (substr($invoice['invoice_internal'], 2));
					$record2 = array(
						"item_id"		=> $value2['med_id'],
						"patient_id"	=> $invoice['patient_id'],
						"quantity"		=> $value2['quantity'],
						"reason"		=> $medicine['medicine_name'] ." claim for Invoice No.". $invoice_number,
						"created_by"	=> $value2['created_by'],
						"created_at"	=> date("Y-m-d H:i:s"),
						/*"batch_no"		=> $value2['batch_id'],*/
						"taken"			=> '1'	
					);
					Stock::save($record2);

					$record = array(
						"taken"    => 1
					);
					Reserved_Meds::save($record,$value2['id']);
			}
		}

	function delete_other_charges(){

		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		Invoice_Other_Charges::delete($post['id']);
	}

	function delete_cost_modifiers(){

		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		Invoice_Cost_Modifier::delete($post['id']);
	}


	function edit_invoice_process(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();

		$user_id = $this->encrypt->decode($session['user_id']);
		$session_id = $this->encrypt->decode($session);
		$post = $this->input->post();
		#debug_array($post);
		//To be uploaded
		$invoice_id = $post['id'];
		$invoice = Invoice::findById(array("id"=>$invoice_id));
		if($post['id']){
		
			$start 			= new Datetime($post['invoice_date']);
			$interval 		= $start->diff($end);
		  if($post['status'] == "Paid" || $post['status'] == "Partial"){
				$record = array(
					"invoice_num"			=> $post['invoice_number'],
					"charge_to"				=> $post['charged_to'],
					"relation_to_patient"	=> $post['relation_to_patient'],
					"invoice_date"			=> $post['invoice_date'],
					"payment_terms"			=> $post['payment_terms'],
					"date_created" 			=> date("Y-m-d H:i:s"),
					"date_updated" 			=> date("Y-m-d H:i:s"),			
					"last_update_by" 		=> $session_id
				);
			$invoice_id 	= Invoice::save($record,$post['id']);
			}else{

			$record = array(
				"invoice_num"			=> $post['invoice_number'],
				"charge_to"				=> $post['charged_to'],
				"relation_to_patient"	=> $post['relation_to_patient'],
				"invoice_date"			=> $post['invoice_date'],
				//"due_date"				=> $post['due_date'],
				"payment_terms"			=> $post['payment_terms'],
				"total_gross_sales" 	=> (float) $post['total_regimen_cost'] + (float) $post['total_other_charges'],
				"total_regimen_cost"	=> (float) $post['total_regimen_cost'],
				"total_other_charges"	=> (float) $post['total_other_charges'],
				"cost_modifier"			=> (float) $post['total_cost_modifier'],
				"net_sales"				=> (float) $post['net_sales'],
				"net_sales_vat"			=> (float) $post['net_sales_vat'],
				"total_net_sales_vat"	=> (float) $post['total_net_sales_vat'],
				"remaining_balance"		=> (float) $post['total_net_sales_vat'],
				"date_created" 			=> date("Y-m-d H:i:s"),
				"date_updated" 			=> date("Y-m-d H:i:s"),
				"status" 				=> "New",
				"last_update_by" 		=> $session_id
				);
			$invoice_id 	= Invoice::save($record,$post['id']);
		   }
			$order = array(
				"doc_attending_id" => $post['doctor_assigned'],
			);
			Orders::save($order, $invoice['order_id']);

			if($post['cm_additional']){
				foreach ($post['cm_additional'] as $key => $value) {
					$cm = array(
						"is_medicine"		=> $value['is_medicine'],
						"invoice_id" 		=> $invoice_id,
						"applies_to" 		=> $value['applies_to'],
						"modifier_type" 	=> $value['modifier_type'],
						"modify_due_to" 	=> $value['modify_due_to'],
						"cost_type" 		=> $value['cost_type'],
						"cost_modifier" 	=> $value['cost_modifier'],
						"total_cost" 		=> (float) $value['total_cost'],
						"date_created"  	=> date("Y-m-d H:i:s"),
						"date_updated"  	=> date("Y-m-d H:i:s"),
						"last_update_by"  	=> $session_id,
						);
					Invoice_Cost_Modifier::save($cm);
					unset($cm);
				}
			}
			
		}  //end

		//$invoice_id = Invoice::SaveInvoice($post);
		// Invoice_Med::saveRPCMeds($post,$invoice_id);
		// Invoice_Med::saveAListMeds($post,$invoice_id);
		//Invoice_Other_Charges::saveOtherCharges($post,$invoice_id);
		//Invoice_Cost_Modifier::saveCostModifier($post,$invoice_id);

		if($invoice_id){
			$json['is_successful'] 	= true;
			$json['invoice_id']	 	= $invoice_id;
			$invoice 				= Invoice::findById(array("id" => $invoice_id));
			$invoice_number 		= $invoice['invoice_internal'];
			$json['message'] 		= "Successfully Updated {$invoice_number} in database";

				$act_tracker = array(
				"module"		=> "rpc_orders",
				"user_id"		=> $user_id,
				"entity_id"		=> "0",
				"message_log" 	=> $session['lastname'].",". $session['firstname']  ." ". "has updated {$invoice['invoice_internal']}",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);
			/*NOTIFICATIONS*/
			/*$user = User::findById(array("id" => $session_id));
			$json['notif_title'] 	= "Updated " . $invoice_number;
			$json['notif_type']		= "info";
			$json['notif_message']	= $session['lastname'] . ", " . $session['firstname'] . " has Updated {$invoice_number}";*/

			//New Notification
			$msg = $session['name'] . " has successfully Updated Invoice: {$invoice_number}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Updated Invoice " . $invoice_number,
				'type' => 'info'
			));

		} else {
			$json['message'] 		= "Error Updating in database";
			$json['is_successful'] = false;
		}
		
		echo json_encode($json);
	}

	function check_stock(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$inv = Inventory::findById(array("id" => $post['med_id']));

		if($inv['total_quantity'] <= 0){
			$json['value'] = $inv['total_quantity'];
			$json['message'] 		= "Sorry! {$inv['medicine_name']} is out of stock";
			echo json_encode($json);
		}
	}

	function editCollection(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		$data['or_id']         = $or_id         = $post['or_id'];
		$data['collections']   = $collections	= Invoice_Receipts::findById(array("id" => $post['or_id']));
		$data['invoice']   	   = $invoice 	    = Invoice::findById(array("id" => $collections['invoice_id']));
		$data['patient']	   = $patient 		= Patients::findById(array("id" => $invoice['patient_id']));

		$data['cash'] 			= $cash 	= Invoice_Receipts_Cash::findAllByORid(array("or_id" => $or_id));
		$data['cheque'] 		= $cheque 	= Invoice_Receipts_Cheque::findAllByORid(array("or_id" => $or_id));
		$data['cc'] 			= $cc 		= Invoice_Receipts_CC::findAllByORid(array("or_id" => $or_id));
		$data['credit'] 		= $credit 	= Invoice_Receipts_Credit::findAllByORid(array("or_id" => $or_id));
		$data['taxwithheld'] 	= $taxwithheld 	= Invoice_Receipts_TaxWithheld::findAllByORid(array("or_id" => $or_id));

		$this->load->view("accounting/view/edit_collection", $data);
	}

	function save_collections(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		$data['invoice']   	   = $invoice 	    = Invoice::findById(array("id" => $post['invoice_id']));
		if($invoice){
		/*$difference = number_format($post['amount'],2, '.', '') - number_format($invoice['total_amount_paid'],2, '.', '');
		$total_amount_paid = number_format($difference,2, '.', '') + number_format($invoice['total_amount_paid'],2, '.', '');
		$remaining_balance = number_format($invoice['remaining_balance'],2, '.', '') - number_format($difference,2, '.', '');
		$update = array(
			"total_amount_paid" => $total_amount_paid,//number_format($invoice['total_amount_paid'],2, '.', '') + number_format($post['amount'],2, '.', ''),
			"remaining_balance" => $remaining_balance,//number_format($invoice['remaining_balance'],2, '.', '') - number_format($post['amount'],2, '.', ''),
			"date_updated"		=> date("Y-m-d H:i:s"),
			"last_update_by"	=> $user_id,
			"status"			=> (number_format($invoice['remaining_balance'],2, '.', '') - number_format($post['amount'],2, '.', '') <= 0 ? "Paid" : "Partial"),
		);
		Invoice::save($update, $invoice['id']);
*/
		//$invoice_receipts 	    = Invoice_Receipts::findByInvoiceId(array("invoice_id" => $post['invoice_id'])); 
		$receipt_invoice 	   = Invoice_Receipts::findById(array("id" => $post['or_id']));
		$invoice_or = array(
			//"invoice_id" 	=> $invoice['id'],	
			"or_number"		=> $post['or_number'],
			//"amount_paid"	=> $total_amount_paid,
			"date_receipt"	=> $post['date_receipt'],
			"date_created"	=> date("Y-m-d H:i:s"),
			"notes"			=> $post['edit_notes'],
			"last_update_by"=> $user_id
		);
		Invoice_Receipts::save($invoice_or, $receipt_invoice['id']);

		/*$cash	= Invoice_Receipts_Cash::findByORid(array("id" => $invoice_receipts['id']));
		$cc 	= Invoice_Receipts_CC::findByORid(array("id" => $invoice_receipts['id']));
		$cheque = Invoice_Receipts_Cheque::findByORid(array("id" => $invoice_receipts['id']));
		$credit = Invoice_Receipts_Credit::findByORid(array("id" => $invoice_receipts['id']));

		if(!empty($cash)){
			$cash1 = array(
				"price" => $total_amount_paid
			);
			Invoice_Receipts_Cash::save($cash1, $cash['id']);
		}else if(!empty($cc)){
			$cc1 = array(
				"price" => $total_amount_paid
			);
			Invoice_Receipts_CC::save($cc1, $cc['id']);
		}else if (!empty($cheque)){
			$cheque1 = array(
				"price"			=> $total_amount_paid
			);

			Invoice_Receipts_Cheque::save($cheque1, $cheque['id']);
		}else if (!empty($credit)) {
			$credit1 = array(
				"price"			=> $total_amount_paid
			);

			Invoice_Receipts_Credit::save($credit1, $credit['id']);
		}*/

		/*$user = User::findById(array("id" => $user_id));
			$json['notif_title'] 	= "Updated Receipt for " . $invoice['invoice_internal'];
			$json['notif_type']		= "info";
			$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has created a receipt for {$invoice_number}.";
			$json['message'] 		= "Successfully created a receipt for {$invoice['invoice_internal']}.";*/

		//New Notification
			$a = $post['or_number'];
			$msg = $session['name'] . " has successfully Updated a Receipt for: {$a}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Updated Receipt " . $a,
				'type' => 'info'
			));

			$json['message'] 		= "Successfully updated ".$post['or_number'];
			$json['is_successful'] = true;

		} else {
			$json['message'] 		= "Error Adding in database";
			$json['is_successful'] = false;
		}
		
		echo json_encode($json);

	}

	function change_mode_of_payment(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();

		$data['or_id'] = $or_id = $post['or_id'];
		$mode_of_payment = $post['mode_payment'];
		$data['or'] = $or = Invoice_Receipts::findById(array("id" => $post['or_id']));
		if($mode_of_payment == 1){
			$cash	= Invoice_Receipts_Cash::findByIdAndORId(array("id" => $post['id'], "or_id" => $post['or_id']));
			$data['cash']    = $cash	= Invoice_Receipts_Cash::findById(array("id" => $post['id']));
			$data['invoice'] = $invoice = Invoice::findById(array("id" => $or['invoice_id']));
			$data['patient'] = $patient = Patients::findById(array("id" => $invoice['patient_id']));
			$data['mode_of_payment'] = $mode_of_payment = 'Cash';
			$data['amount_paid'] = $amount_paid = $cash['price'];
		}else if($mode_of_payment == 2){
			$credit	= Invoice_Receipts_Credit::findByIdAndORId(array("id" => $post['id'], "or_id" => $post['or_id']));
			$data['credit']   = $credit	= Invoice_Receipts_Credit::findById(array("id" => $post['id']));
			$data['invoice']  = $invoice = Invoice::findById(array("id" => $or['invoice_id']));
			$data['patient']  = $patient = Patients::findById(array("id" => $invoice['patient_id']));
			$data['mode_of_payment'] = $mode_of_payment = 'Credit';
			$data['amount_paid'] = $amount_paid = $credit['price'];	
		}else if ($mode_of_payment == 3) {
			$cheque	= Invoice_Receipts_Cheque::findByIdAndORId(array("id" => $post['id'], "or_id" => $post['or_id']));
			$data['cheque']   = $cheque	= Invoice_Receipts_Cheque::findById(array("id" => $post['id']));
			$data['invoice']  = $invoice = Invoice::findById(array("id" => $or['invoice_id']));
			$data['patient']  = $patient = Patients::findById(array("id" => $invoice['patient_id']));
			$data['mode_of_payment'] = $mode_of_payment = 'Cheque';
			$data['amount_paid'] = $amount_paid = $cheque['price'];
		}else if ($mode_of_payment == 4) {
			$cc	= Invoice_Receipts_CC::findByIdAndORId(array("id" => $post['id'], "or_id" => $post['or_id']));
			$data['cc']      = $cc	= Invoice_Receipts_CC::findById(array("id" => $post['id']));
			$data['invoice'] = $invoice = Invoice::findById(array("id" => $or['invoice_id']));
			$data['patient'] = $patient = Patients::findById(array("id" => $invoice['patient_id']));
			$data['mode_of_payment'] = $mode_of_payment = 'Credit Card';
			$data['amount_paid'] = $amount_paid = $cc['price'];
		}else{
			$taxwithheld	= Invoice_Receipts_TaxWithheld::findByIdAndORId(array("id" => $post['id'], "or_id" => $post['or_id']));
			$data['taxwithheld']   = $taxwithheld	= Invoice_Receipts_TaxWithheld::findById(array("id" => $post['id']));
			$data['invoice'] 	   = $invoice = Invoice::findById(array("id" => $or['invoice_id']));
			$data['patient']       = $patient = Patients::findById(array("id" => $invoice['patient_id']));
			$data['mode_of_payment'] = $mode_of_payment = 'Tax Withheld';
			$data['amount_paid'] = $amount_paid = $taxwithheld['price'];
		}
		#debug_array($data);
		$this->load->view('accounting/view/edit_change_mode_of_payment',$data);
	}

	function update_receipt(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post 	= $this->input->post();
		#debug_array($post);
		$current_modepayment = $post['current_mode_payment'];
		$new_modepayment 	 = $post['new_mode_payment'];
			
		if($post['new_mode_payment'] != 'select'){

			$id_cash             = $post['id_cash'];
			$id_cheque           = $post['id_cheque'];
		    $id_cc 				 = $post['id_cc'];
			$id_credit 			 = $post['id_credit'];
			$id_taxwithheld      = $post['id_taxwithheld'];

			if(!empty($id_cash)){
				$id = $id_cash;
			}else if(!empty($id_cheque)){
				$id = $id_cheque;
			}else if(!empty($id_cc)){
				$id = $id_cc;
			}else if(!empty($id_credit)){
				$id = $id_credit;
			}else{
				$id = $id_taxwithheld;
			}

			switch($current_modepayment){
				case 'Cash':
					$cash_details	= Invoice_Receipts_Cash::findById(array("id" => $id));	
					$data['or_id'] = $or_id = $cash_details['or_id'];
					$data['invoice_id'] = $invoice_id = $post['invoice_id'];
					$data['amount_paid'] = $amount_paid = $cash_details['price'];
					break;
				case 'Cheque':
					$cheque_details	= Invoice_Receipts_Cheque::findById(array("id" => $id));	
					$data['or_id'] = $or_id = $cheque_details['or_id'];
					$data['invoice_id'] = $invoice_id = $post['invoice_id'];
					$data['amount_paid'] = $amount_paid = $cheque_details['price'];
					break;
				case 'Credit Card':
					$cc_details	= Invoice_Receipts_CC::findById(array("id" => $id));	
					$data['or_id'] = $or_id = $cc_details['or_id'];
					$data['invoice_id'] = $invoice_id = $post['invoice_id'];
					$data['amount_paid'] = $amount_paid = $cc_details['price'];
					break;
				case 'Credit':
					$credit_details	= Invoice_Receipts_Credit::findById(array("id" => $id));	
					$data['or_id'] = $or_id = $credit_details['or_id'];
					$data['invoice_id'] = $invoice_id = $post['invoice_id'];
					$data['amount_paid'] = $amount_paid = $credit_details['price'];

					$or = Invoice_Receipts::findById(array("id" => $or_id));
					
					$patient = Patients::findById(array("id" => $post['patient_id']));
					$record = array(
						"credit" => $patient['credit'] + $amount_paid,
						"last_update_by" => $user_id,
						"updated_at" => date("Y-m-d H:i:s"),
					);
					Patients::save($record, $patient['id']);

					$history = array(
						"patient_id" 	=> $post['patient_id'],
						"credit" 		=> $amount_paid,
						"remarks" 		=> "Change payment from Credit to {$new_modepayment} in" . $or['or_number'],
						"type" 			=> "Add",
						"date_created" 	=> date("Y-m-d H:i:s"),
						"created_by" 	=> $user_id,
					);

					Patients_Credit_History::save($history);

					break;
				case 'Tax Withheld':
					$taxwithheld_details	= Invoice_Receipts_TaxWithheld::findById(array("id" => $id));	
					$data['or_id'] = $or_id = $taxwithheld_details['or_id'];
					$data['invoice_id'] = $invoice_id = $post['invoice_id'];
					$data['amount_paid'] = $amount_paid = $taxwithheld_details['price'];
					break;
			}
			
			$or = Invoice_Receipts::findById(array("id" => $or_id));

			switch($new_modepayment){
				case 'Cash':
				
					$cash = array(
						"or_id"		 => $or_id,
						"price" 	 => $amount_paid,
						#"invoice_id" => $invoice_id
					);
					Invoice_Receipts_Cash::save($cash);
					break;
				case 'Cheque':
					$cheque = array(
						"or_id"			=> $or_id,
						"cheque_number"	=> $post['cheque_number'],
						"bank_name"		=> $post['cheque_bank_name'],
						"cheque_date"	=> $post['cheque_date'],
						"price"			=> $amount_paid,
						#"invoice_id" 	=> $invoice_id

					);
					Invoice_Receipts_Cheque::save($cheque);

					break;
				case 'Credit Card':
					$cc = array(
						"or_id"		 => $or_id,
						"bank_name"	 => $post['cc_bank_name'],
						"card_type"	 => $post['cc_type'],
						"price"		 => $amount_paid,
						#"invoice_id" => $invoice_id
					);

					Invoice_Receipts_CC::save($cc);
					break;
				case 'Credit':
					$credit = array(
						"or_id"	=> $or_id,
						"price" => $amount_paid,
						#"invoice_id" => $invoice_id
					);
					Invoice_Receipts_Credit::save($credit);

				$patient = Patients::findById(array("id" => $post['patient_id']));
					$record = array(
						"credit" => $patient['credit'] - $amount_paid,
						"last_update_by" => $user_id,
						"updated_at" => date("Y-m-d H:i:s"),
					);
					Patients::save($record, $patient['id']);

					$history = array(
						"patient_id" 	=> $post['patient_id'],
						"credit" 		=> $amount_paid,
						"remarks" 		=> "Payment used to " . $or['or_number'],
						"type" 			=> "Less",
						"date_created" 	=> date("Y-m-d H:i:s"),
						"created_by" 	=> $user_id,
					);

					Patients_Credit_History::save($history);
					break;
				case 'Tax Withheld':
					$taxwithheld = array(
						"or_id"	=> $or_id,
						"price" => $amount_paid,
						#"invoice_id" => $invoice_id
					);
					Invoice_Receipts_TaxWithheld::save($taxwithheld);
					break;
			}

			if($current_modepayment == "Cash"){
				Invoice_Receipts_Cash::delete($id);
			}else if ($current_modepayment == "Cheque") {
				Invoice_Receipts_Cheque::delete($id);
			}else if ($current_modepayment == "Credit Card"){
				Invoice_Receipts_CC::delete($id);
			}else if ($current_modepayment == "Credit"){
				Invoice_Receipts_Credit::delete($id);
			}else {
				Invoice_Receipts_TaxWithheld::delete($id);
			}

				
				$json['is_successful'] 	= true;
				$or 				    = Invoice_Receipts::findById(array("id" => $or_id));
				$or_number				= $or['or_number'];
				$json['message'] 		= "Successfully Updated {$or_number} in database";

				$data['user'] = $user = User::findById(array("id"=>$user_id));
					$data['act_tracker'] = $act_tracker = array(
						"module"		=> "rpc_accounting_billing",
						"user_id"		=> $user['id'],
						"entity_id"		=> $or['id'],
						"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "has updated {$or_number} from {$post['current_mode_payment']} to {$post['new_mode_payment']}",
						"date_created" 	=> date("Y-m-d H:i:s"),
					);
				Activity_Tracker::save($act_tracker);

				/*NOTIFICATIONS*/
				
				/*$json['notif_title'] 	= "Updated " . $invoice_number;
				$json['notif_type']		= "info";
				$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has Updated {$or_number} from {$post['current_mode_payment']} to {$post['new_mode_payment']}";*/

				//New Notification
				$msg = $session['name'] . " has successfully Updated {$or_number} from {$post['current_mode_payment']} to {$post['new_mode_payment']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated OR" . $or_number,
					'type' => 'info'
				));
				
			} else {
				$json['is_successful'] = false;
				$json['error']   = "You don't have enough credit.";
			}
		
		echo json_encode($json);
	}
}