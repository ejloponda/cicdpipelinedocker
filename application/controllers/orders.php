<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders_Management extends MY_Controller {
	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
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
		$this->load->view('order/tables/table_lists',$data);
	}

	function create(){
		Engine::XmlHttpRequestOnly();
		(!$this->isUserLoggedIn() ? redirect("authenticate") : "");
		$data['patients'] 		= $patients 	= Patients::findAll();
		$data['medicines'] 		= $medicines 	= Inventory::findAllMedicines();
		$data['other_charges']	= $other_charges = Other_Charges::findAllActive();
		$this->load->view('order/forms/create',$data);
	}

	function saveOrder(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();

		// debug_array($post);

		if($post['patient_id']){
			if($post['order_id']){
				// UPDATE RECORD
			} else {
				// NEW RECORD
				$record = array(
					"order_no" 		=> Orders::generateOrderID(),
					"patient_id" 	=> $post['patient_id'],
					"date_created" 	=> date("Y-m-d H:i:s"),
					"date_updated" 	=> date("Y-m-d H:i:s"),
					"status"		=> "New",
					"created_by"	=> $user_id
					);
				debug_array($record);
			}
			$json['is_successful'] = true;
		} else {
			$json['is_successful'] = false;
			$json['message'] = "Error saving to database. Please try again later.";
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


	
}