<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends MY_Controller {
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

		Engine::appScript('reports.js');
		Engine::appScript('topbars.js');
		Engine::appScript('profile.js');
		Engine::appScript('confirmation.js');

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
		$this->load->view('reports/index',$data);
		}
	}

	/* START LOAD PAGE */

	function getIndex(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$data['patients'] 	= $patients 	= Patients::findAll();
		$data['regimen'] 	= $regimen 		= Regimen::findAll();
	 	$data['inventory'] 	= $inventory 	= Inventory::findAllMedicines();
		$data['sales']		= $sales 		= Invoice::findAllWithVoid();
		// $data['collections']= $collections 	= Invoice_Receipts::findAll();
		// debug_array($collections);

		/*foreach ($patients as $key => $value) {

			$oldate = $value['appointment'];
			$date = date("Y-m-d", strtotime($oldate));
			$test[] = array(
					"Patient Name" => $value['firstname'] . " " . $value['lastname'],
					"Appointment Date" => $date,
				);
		}
		debug_array($test);*/
		$this->load->view('reports/view/report_generator',$data);
	}


	function download_pdf(){
		$invoice_id = $this->uri->segment(3);

		$data['invoice'] = $invoice = Invoice::findById(array("id"=> $invoice_id));

		$meds = Invoice_Med::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
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

		$data['rpc_meds'] = $rpc_meds;
		$data['patient'] = $patient = Patients::findById(array("id" => $invoice['patient_id']));
		$data['patient_code'] = $patient['patient_code'];

		$this->load->library('tcpdf/tcpdf');
		$this->load->view('accounting/generate_pdf',$data);
	}

	function download_report_all_patients(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);

		$params = array(
					"from_date" => $from_date,
					"to_date"	=> $to_date
				);

		$patients = Patients::findAllByAppointmentDate($params);

		$information[] = array("Patient Code", "First Name", "Last Name", "Appointment", "Assigned Doctor", "Attending Doctor", "Gender", "Birthdate", "Place of Birth", "Age", "Address", "City", "TIN", "Senior Citizen ID", "Email Address", "Civil Status", "Dominant Hand", "Work Status", "Contact Name", "Contact Email Address", "Credits");

		foreach ($patients as $key => $value) {
			$today = new DateTime();
			$age = new DateTime($value['birthdate']);
			$interval = $today->diff($age);
			$patient_age = $interval->y;

			$asd = Doctors::findById(array("id"=>$value['doc_assigned_id']));
			$atd = Doctors::findById(array("id"=>$value['doc_attending_id']));
			
			$information[] = array(
				$value['patient_code'],
				$value['firstname'],
				$value['lastname'],
				$value['appointment'],
				$asd['full_name'],
				$atd['full_name'],
				$value['gender'],
				$value['birthdate'],
				$value['placeofbirth'],
				$patient_age,
				$value['address'],
				$value['city'],
				$value['tin'],
				$value['sc_id'],
				$value['email_address'],
				$value['civil_status'],
				$value['dominant_hand'],
				$value['work_status'],
				$value['contact_name'],
				$value['contact_email_address'],
				$value['credit'],
			);
		}

		/*$information[] = array("");

		$information[] = array("test kung meron");*/
		// debug_array($information);

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_all_patient_without_daterange(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);
		$type 		= $this->uri->segment(6);

		
		$information[] = array("Patient Code", "First Name", "Last Name", "Appointment", "Assigned Doctor", "Attending Doctor", "Gender", "Birthdate", "Place of Birth", "Age", "Address", "City", "TIN", "Senior Citizen ID", "Email Address", "Civil Status", "Dominant Hand", "Work Status", "Contact Name", "Contact Email Address", "Credits");
		$patients = Patients::findAll();

		foreach ($patients as $key => $value) {
			
			$today = new DateTime();
			$age = new DateTime($value['birthdate']);
			$interval = $today->diff($age);
			$patient_age = $interval->y;

			$asd = Doctors::findById(array("id"=>$value['doc_assigned_id']));
			$atd = Doctors::findById(array("id"=>$value['doc_attending_id']));
			$information[] = array(
				$value['patient_code'],
				$value['firstname'],
				$value['lastname'],
				$value['appointment'],
				$asd['full_name'],
				$atd['full_name'],
				$value['gender'],
				$value['birthdate'],
				$value['placeofbirth'],
				$patient_age,
				$value['address'],
				$value['city'],
				$value['tin'],
				$value['sc_id'],
				$value['email_address'],
				$value['civil_status'],
				$value['dominant_hand'],
				$value['work_status'],
				$value['contact_name'],
				$value['contact_email_address'],
				$value['credit'],
			);
		}
		$data['information'] = $information;
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}


	function download_report_all_regimen(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);

		$params = array(
					"from_date" => $from_date,
					"to_date"	=> $to_date
				);

		$regimen = Regimen::findAllbyDateGenerated($params);

		#debug_array($regimen);
		$information[] = array("Patient Name", "Regimen Number", "Date Generated", "Regimen Duration", "Regimen Notes", "Preferences");

		foreach ($regimen as $key => $value) {

	
			$information[] = array(
				$value['patient_name'],
				$value['regimen_number'],
				$value['date_generated'],
				$value['regimen_duration'],
				$value['regimen_notes'],
				$value['preferences'],
			);
		}

		/*$information[] = array("");

		$information[] = array("test kung meron");*/
		#debug_array($information);

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_all_inventory(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);
		$type 		= $this->uri->segment(6);

		$information[] = array("Product No", "Medicine Name", "Generic Name", "Dosage", "Claim/Sold Medicines", "Reserved Medicines", "Available Stocks","Expired/Damage Stocks","Total Stocks OnHand", "Delivery", "Quantity Type","Quantity Per Bottle","Stock", "SRP", "Cost","Status");
		$inventory = Inventory::findAllMedicines();

		foreach ($inventory as $key => $value) {
			//$reserved 		= Reserved_Meds::findByMedId(array("id" => $value['id']));
			$reserved 		= Reserved_Meds::findSumReserved(array("id" => $value['id']));
			$claim 			= Reserved_Meds::findSumClaim(array("id" => $value['id']));
			$dtype 			= Dosage_Type::findById(array("id"=>$value['dosage_type']));
			$qtype 			= Quantity_Type::findById(array("id"=>$value['quantity_type']));
			$total_quantity_inputted = Inventory_Batch::sumTotalQuantity(array("id" => $value['id']));
			$stocks_onhand  = ($value['total_quantity'] + $reserved);
			//$reserved1		= ($total_quantity - $value['total_quantity']);
			$all_quantity 		= Inventory_Batch::computeTotalQuantity(array("id"=> $value['id']));

			$information[] = array(
				$value['product_no'],
				$value['medicine_name'],
				$value['generic_name'],
				$value['dosage'] . " " . $dtype['abbreviation'],
				($claim == '' ? 0: $claim),
				($reserved == '' ? 0: $reserved),
				($all_quantity == '' ? 0: $all_quantity),
				$value['expired_damage_med'],
				$stocks_onhand,
				$total_quantity_inputted,
				$qtype['abbreviation'],
				$value['qty_per_bottle'],
				$value['stock'],
				$value['price'],
				$value['cost_sales'],
				$value['status']
			);
		}

		
		$data['information'] = $information;
		//debug_array($data);
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_regimen(){
		$filename 	= $this->uri->segment(3);
		$regimen_id = $this->uri->segment(4);
		
		$regimen 	= Regimen::findById(array("id" => $regimen_id));
		$version_id = 0;
		#debug_array($regimen);
		$information[] = array("Patient Name", "Regimen Number", "Date Generated", "Regimen Duration", "Regimen Notes", "Preferences");

		$information[] = array(
			$regimen['patient_name'],
			$regimen['regimen_number'],
			$regimen['date_generated'],
			$regimen['regimen_duration'],
			$regimen['regimen_notes'],
			$regimen['preferences'],
		);

		#debug_array($information);

		$list_bf 		= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $regimen_id, "meal_type" => "bf", "version_id" => $version_id));
		$list_lunch 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $regimen_id, "meal_type" => "lunch", "version_id" => $version_id));
		$list_dinner 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $regimen_id, "meal_type" => "dinner", "version_id" => $version_id));

		$information[] = array("","");
		$information[] = array("","");
		$information[] = array("Breakfast");
		$information[] = array("Medicine Name", "Dosage", "Quantity", "Duration", "Day(s)");
		foreach ($list_bf as $key => $value) {
			$meds 			= Inventory::findById(array("id" => $value['medicine_id']));
			$dtype 			= Dosage_Type::findById(array("id"=>$meds['dosage_type']));
			$qtype 			= Quantity_Type::findById(array("id"=>$meds['quantity_type']));


			$start 			= new Datetime($value['start_date']);
			$end 			= new Datetime($value['end_date']);
			$interval 		= $start->diff($end);
			$time_interval 	= (int) $interval->format('%a');
			$qty 			= $value['quantity'] * $time_interval;

			$information[] = array(
				$meds['medicine_name'],
				$meds['dosage'] . " " . $dtype['abbreviation'],
				$qty . " " . $qtype['abbreviation'],
				$value['start_date'] . " - " . $value['end_date'],
				$time_interval
			);
		}

		$information[] = array("","");
		$information[] = array("","");
		$information[] = array("Lunch");
		$information[] = array("Medicine Name", "Dosage", "Quantity", "Duration", "Day(s)");
		foreach ($list_lunch as $key => $value) {
			$meds 			= Inventory::findById(array("id" => $value['medicine_id']));
			$dtype 			= Dosage_Type::findById(array("id"=>$meds['dosage_type']));
			$qtype 			= Quantity_Type::findById(array("id"=>$meds['quantity_type']));


			$start 			= new Datetime($value['start_date']);
			$end 			= new Datetime($value['end_date']);
			$interval 		= $start->diff($end);
			$time_interval 	= (int) $interval->format('%a');
			$qty 			= $value['quantity'] * $time_interval;

			$information[] = array(
				$meds['medicine_name'],
				$meds['dosage'] . " " . $dtype['abbreviation'],
				$qty . " " . $qtype['abbreviation'],
				$value['start_date'] . " - " . $value['end_date'],
				$time_interval
			);
		}

		$information[] = array("","");
		$information[] = array("","");
		$information[] = array("Dinner");
		$information[] = array("Medicine Name", "Dosage", "Quantity", "Duration", "Day(s)");
		foreach ($list_dinner as $key => $value) {
			$meds 			= Inventory::findById(array("id" => $value['medicine_id']));
			$dtype 			= Dosage_Type::findById(array("id"=>$meds['dosage_type']));
			$qtype 			= Quantity_Type::findById(array("id"=>$meds['quantity_type']));


			$start 			= new Datetime($value['start_date']);
			$end 			= new Datetime($value['end_date']);
			$interval 		= $start->diff($end);
			$time_interval 	= (int) $interval->format('%a');
			$qty 			= $value['quantity'] * $time_interval;

			$information[] = array(
				$meds['medicine_name'],
				$meds['dosage'] . " " . $dtype['abbreviation'],
				$qty . " " . $qtype['abbreviation'],
				$value['start_date'] . " - " . $value['end_date'],
				$time_interval
			);
		}

		$version_tbls = Regimen_Version::findByRegimenId(array("regimen_id" => $regimen_id));
		if($version_tbls){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Versions");
			$information[] = array("Version Name", "Version Remarks", "Date Generated", "Regimen Notes", "Preferences");
			foreach ($version_tbls as $key => $value) {

				$information[] = array(
					$value['version_name'],
					$value['version_remarks'],
					$value['date_generated'],
					$value['regimen_notes'],
					$value['preferences'],
				);
			}

		}
		/*$information[] = array("");

		$information[] = array("test kung meron");*/
		#debug_array($information);

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_all_batch(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);
		$type 		= $this->uri->segment(6);

		$params = array(
					"from_date" => $from_date,
					"to_date"	=> $to_date
				);
		if($type == "ExpiryDate"){
			$inventory = Inventory_Batch::findAllMedicinesByExpiryDate($params);
		} else {
			$inventory = Inventory_Batch::findAllMedicinesByPurchaseDate($params);
			//debug_array($inventory);
		}

		$information[] = array("Product No","Batch Number","Medicine Name", "Generic Name", "Dosage", "Total Stocks OnHand", "Quantity Type", "Stock", "Price", "Product Cost", "Purchase Date", "Expiry Date");

		foreach ($inventory as $key => $value) {
			
			$inventory_details = Inventory::findById(array("id"=>$value['main_med_id']));
			$dtype 			   = Dosage_Type::findById(array("id"=>$inventory_details['dosage_type']));
			$qtype 			   = Quantity_Type::findById(array("id"=>$inventory_details['quantity_type']));

			$information[] = array(
				$inventory_details['product_no'],
				$value['batch_no'],
				$inventory_details['medicine_name'],
				$inventory_details['generic_name'],
				$inventory_details['dosage'] . " " . $dtype['abbreviation'],
				$inventory_details['total_quantity'],
				$qtype['abbreviation'],
				$inventory_details['stock'],
				$inventory_details['price'],
				$inventory_details['cost_sales'],
				$value['purchase_date'],
				$value['expiry_date'],
			);
		}
		
		$data['information'] = $information;
		//debug_array($data);
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_per_batch(){
		$filename 		= $this->uri->segment(3);
		$inventory_id 	= $this->uri->segment(4);

		$inventory_batch = Inventory_Batch::findByMainIdMed(array("id" => $inventory_id));
		//$inventory 		= Inventory::findById(array("id" => $inventory_id));

		$information[]   = array("Product No","Batch Number", "Medicine Name", "Generic Name", "Dosage", "Stocks OnHand", "Quantity Type", "Stock", "Price", "Product Cost", "Purchase Date", "Expiry Date");
		
		if($inventory_batch){

			//$inventory_batch 		= Inventory_Batch::findByMainIdMed(array("id" => $inventory['id']));
			//debug_array($inventory_batch);
			foreach ($inventory_batch as $key => $value) {
				$inventory 	   = Inventory::findById(array("id" => $value['main_med_id']));
				$dtype 		   = Dosage_Type::findById(array("id"=>$inventory['dosage_type']));
				$quantity_type = Quantity_Type::findById(array("id"=>$inventory['quantity_type']));
				//debug_array($quantity);
				$information[] = array(
					$inventory['product_no'],
					$value['batch_no'],
					$inventory['medicine_name'],
					$inventory['generic_name'],
					$inventory['dosage'] . " " . $dtype['abbreviation'],
					$value['quantity'],
					$quantity_type['abbreviation'],
					$inventory['stock'],
					$inventory['price'],
					$inventory['cost_sales'],
					$value['purchase_date'],
					$value['expiry_date'],
				);
			}
		}

		$stock_history = Stock::findAllById(array("item_id" => $inventory_id));

		if($stock_history){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Stock History");
			$information[] = array("Reason", "Quantity", "By", "Date / Time");

			foreach ($stock_history as $key => $value) {

				$user = User::findById(array("id" => $value['created_by']));
				$information[] = array(
					$value['reason'],
					$value['quantity'],
					$user['lastname'] . " " . $user['firstname'],
					$value['created_at']
					);
			}
		}

		$data['information'] = $information;
		//debug_array($data);
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_inventory(){
		$filename 		= $this->uri->segment(3);
		$inventory_id 	= $this->uri->segment(4);
		$inv_from_date  = $this->uri->segment(5);
		$inv_to_date    = $this->uri->segment(6);
		
		$date = new DateTime($inv_from_date);
		$result = $date->format('M d,Y');
		$date = new DateTime($inv_to_date);
		$result1 = $date->format('M d,Y');
		
		$inventory 		= Inventory::findById(array("id" => $inventory_id));
		//Reserved_Meds::findSumReserved(array("id" => $inventory['id']));	
		$information[] = array("Date of Inventory: ".date("M d,Y H:i:s"));
		$information[] = array("","");
		$information[]  = array("Product No", "Medicine Name", "Generic Name", "Dosage", "Claim/Sold Medicines", "Reserved Medicines", "Total Stocks OnHand", "Total Quantity Inputted", "Quantity Type", "Stock", "SRP", "Cost");
	
		$reserved 		= Reserved_Meds::findSumReserved(array("id" => $inventory_id));
		$claim 			= Reserved_Meds::findSumClaim(array("id" => $inventory_id));
		$dtype 			= Dosage_Type::findById(array("id"=>$inventory['dosage_type']));
		$qtype 			= Quantity_Type::findById(array("id"=>$inventory['quantity_type']));
		$total_quantity_inputted = Inventory_Batch::sumTotalQuantity(array("id" => $inventory['id']));
		$stocks_onhand	= ($reserved + $inventory['total_quantity']);
		
		$information[] = array(
			$inventory['product_no'],
			$inventory['medicine_name'],
			$inventory['generic_name'],
			$inventory['dosage'] . " " . $dtype['abbreviation'],
			($claim == '' ? 0: $claim),
			($reserved == '' ? 0: $reserved),
			$stocks_onhand,
			$total_quantity_inputted,
			$qtype['abbreviation'],
			$inventory['stock'],
			$inventory['price'],
			$inventory['cost_sales'],
		);

		//Batch List

		$batch 	= Inventory_Batch::findByMainIdMed(array("id" => $inventory['id']));
		
		if($batch){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Batch List");
			$information[] = array("Batch Number", "Purchase Date", "Expiry Date","Date Added", "Date Updated");

			foreach ($batch as $key => $value) {
				$information[] = array(
					$value['batch_no'],
					//$value['quantity'],
					$value['purchase_date'],
					$value['expiry_date'],
					$value['date_created'],
					$value['date_updated']
				);
			}
		}

		//Invoiced Medicines
		//$medicine = Invoice_Med::findByMedId(array("id" => $inventory['id']));
		$params = array(
				"id"		=> $inventory['id'],
				"from_date" => $inv_from_date,
				"to_date"	=> $inv_to_date
		);
		$invoice_medicine = Invoice_Med::findAllMedicinesByDateRange($params);

		if($invoice_medicine){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Invoiced Medicines","From ".$result." To ".$result1);
			$information[] = array("Invoice Number", "Invoice Date", "Patient Name", "Patient ID", "Quantity", "Price", "Total Price", "Attending Doctor");
			
			foreach ($invoice_medicine as $key => $value) {
				$invoice =  Invoice::findById(array("id" => $value['invoice_id']));
				$patient =  Patients::findById(array("id" => $invoice['patient_id']));
				$doctor  =  Doctors::findById(array("id" => $patient['doc_attending_id']));
				$information[] = array(
					$invoice['invoice_num'],
					$invoice['invoice_date'],
					$patient['patient_name'],
					$patient['patient_code'],
					$value['quantity'],
					$value['price'],
					$value['total_price'],
					$doctor['full_name']
				);
			}
		}

		//Stock History
		$stock_history = Stock::findAllById(array("item_id" => $inventory_id));

		if($stock_history){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Stock History");
			$information[] = array("Reason", "Quantity", "By", "Date / Time");

			foreach ($stock_history as $key => $value) {

				$user = User::findById(array("id" => $value['created_by']));
				$information[] = array(
					$value['reason'],
					$value['quantity'],
					$user['lastname'] . " " . $user['firstname'],
					$value['created_at']
					);
			}
		}
		
		// debug_array($information);

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function generate_report_patient(){
		$filename 		= $this->uri->segment(3);
		$patient_id 	= $this->uri->segment(4);

		$patient 			= Patients::findById(array("id" => $patient_id));
		$asd 				= Doctors::findById(array("id"=>$patient['doc_assigned_id']));
		$atd 				= Doctors::findById(array("id"=>$patient['doc_attending_id']));
		$information[] 		= array("Patient Details"); 
		$information[] 		= array("Appointment Date", "Patient Code", "Last Name", "First Name", "Gender", "Birthdate", "Place of Birth", "Age", "Address", "City", "Zip", "TIN", "Senior Citizen ID");
		$information[]		= array($patient['appointment'], $patient['patient_code'], $patient['firstname'], $patient['lastname'], $patient['gender'], $patient['birthdate'], $patient['placeofbirth'], $patient['age'], $patient['address'], $patient['city'], $patient['zip'], $patient['tin'], $patient['sc_id']);
		$information[]		= array("", ""); 
		$information[] 		= array("Miscellaneous Details");
		$information[] 		= array("Email Address", "Civil Status", "Dominant Hand", "Work Status", "Contact Name", "Contact Email Address", "Credits");
		$information[]		= array($patient['email_address'], $patient['civil_status'], $patient['dominant_hand'], $patient['work_status'], $patient['contact_name'], $patient['contact_email_address'], $patient['credit']);
		$information[]		= array("", ""); 
		$information[] 		= array("Doctors");
		$information[] 		= array("Assigned Doctor", "Attending Doctor");
		$information[]		= array($asd['full_name'], $atd['full_name']);


		$medical_history 	= Disease::findByPatientId(array("patient_id" => $patient_id));

		if($medical_history){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Family Medical History");
			$information[] = array("Disease Name", "Disease Type", "Relation", "Age");

			foreach ($medical_history as $key => $value) {
				$disease_type 	= Disease_Type::findById(array("id" => $value['disease_type_id']));
				$disease 		= Disease_Name::findById(array("id" => $value['disease_id']));
				$information[]  = array($disease['disease_name'],$disease_type['type_name'],$value['relation'],$value['age_diagnosed']);
			}

		}
		

		$personal_disease = Personal_Disease::findByPatientId(array("patient_id" => $patient_id));
		
		if($personal_disease){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Personal Medical History");
			$information[] = array("Disease Name", "Disease Type", "Age");

			foreach ($personal_disease as $key => $value) {
				$disease_type 	= Disease_Type::findById(array("id" => $value['disease_type_id']));
				$disease 		= Disease_Name::findById(array("id" => $value['disease_id']));
				$information[] = array($disease['disease_name'],$disease_type['type_name'],$value['age_diagnosed']);
			}
			
		}

		$regimen = Regimen::generateListOfRegimenHistoryByPatientID(array("patient_id" => $patient_id));

		if($regimen){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Regimen List");
			$information[] = array("Regimen Number", "Date Generated", "Regimen Duration", "Regimen Notes", "Preferences");

			foreach ($regimen as $key => $value) {
				$information[] = array(
					$value['regimen_number'],
					$value['date_generated'],
					$value['regimen_duration'],
					$value['regimen_notes'],
					$value['preferences'],
				);
			}
		}

		$invoice = Invoice::findAllByPatient(array("patient_id" => $patient_id));
		if($invoice){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Invoice List");
			$information[] = array("Invoice ID", "Due Date", "Remaining Balance", "Status");

			foreach ($invoice as $key => $value) {
				$information[] = array(
					$value['order_id'],
					$value['due_date'],
					($value['status'] == "Void" ? $value['status'] : number_format($value['remaining_balance'], 2, '.', ',')),
					$value['status'],
				);
			}
		}

		$stocks = Stock::findAllByPatientId(array("patient_id" => $patient_id));

		if($stocks){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Stock History");
			$information[] = array("Medicine Name", "Quantity", "Reason", "Date / Time");

			foreach ($stocks as $key => $value) {
				$information[] = array(
					$value['medicine_name'],
					$value['quantity'],
					$value['reason'],
					$value['created_at']
				);
			}
		}

		$fields = array("a.id,a.invoice_id,a.patient_id,a.date_return,a.status,b.order_id");

		$params	= array(
				"fields" => $fields,
				"patient_id" => $patient_id
			);

		$returns = Returns::findAllByPatientId($params);

		if($returns){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Returns History");
			$information[] = array("Invoice ID", "Date of Return", "Status");

			foreach ($returns as $key => $value) {
				$information[] = array(
					$value['order_id'],
					$value['date_return'],
					$value['status']
				);
			}
		}

		$credit = Patients_Credit_History::findAllByPatientid(array("patient_id" => $patient_id));

		if($credit){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Credit History");
			$information[] = array("Remarks", "Credit", "Type", "By", "Date / Time");

			foreach ($credit as $key => $value) {
				$user = User::findById(array("id" => $value['created_by']));
				$information[] = array(
					$value['remarks'],
					$value['credit'],
					ucfirst($value['type']),
					$user['firstname'] . " " . $user['lastname'],
					$value['date_created']
				);
			}
		}

		$estimated_date = Orders::findByPatientId(array("patient_id" => $patient_id));
		if($estimated_date){
			$information[] = array("","");
			$information[] = array("","");
			$information[] = array("Order List");
			$information[] = array("Order Number", "Estimated End Date");

			foreach ($estimated_date as $key => $value) {
				$information[] = array(
					$value['order_no'],
					$value['estimated_date'],
				);
			}
		}
		


		$data['information'] = $information;
		
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_all_returns(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);

		$params = array(
					"from_date" => $from_date,
					"to_date"	=> $to_date
				);

		$returns = Returns::findAllbyDateGenerated($params);

		$information[] = array("Patient Name", "Invoice Number","Form #", "Reason of Return", "Total Credit","Date Return",  "Status", "Notes");

		foreach ($returns as $key => $value) {
			$patient = Patients::findById(array("id" => $value['patient_id']));
			$invoice = Invoice::findById(array("id" => $value['invoice_id']));
			$or = Invoice_Receipts::findByInvoiceId(array("invoice_id"=>$value['invoice_id']));

			$information[] = array(
				$patient['patient_name'],
				$invoice['invoice_num'],
				$value['return_slip_no'],
				$value['reason_of_return'],
				$value['credit'],
				$value['date_return'],
				$value['status'],
				$or['notes']
			);
		}

		$data['information'] = $information;
		
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_sales(){
		$filename 		= $this->uri->segment(3);
		$invoice_id 	= $this->uri->segment(4);

		$invoice 		= Invoice::findById(array("id" => $invoice_id));
		$patient 		= Patients::findById(array("id" => $invoice['patient_id']));
		$information[] = array("Invoice ID", "Invoice Number", "Patient Name", "Charge To", "Relation to Patient", "Invoice Date", "Payment Terms", "Due Date", "Regimen Cost", "Other Charges", "Cost Modifiers", "Invoice NET of VAT", "VAT", "Total Invoice Amount");
			
		$information[] = array(
			$invoice['order_id'],
			$invoice['invoice_num'],
			$patient['lastname'] . " " . $patient['firstname'],
			$invoice['charge_to'],
			$invoice['relation_to_patient'],
			$invoice['invoice_date'],
			($invoice['payment_terms'] == "COD" ? $invoice['payment_terms'] : $invoice['payment_terms'] . " Days"),
			$invoice['due_date'],
			$invoice['total_regimen_cost'],
			$invoice['total_other_charges'],
			$invoice['cost_modifier'],
			$invoice['net_sales'],
			$invoice['net_sales_vat'],
			$invoice['total_net_sales_vat'],
			//($invoice['status'] == "Void" ? 0.00: $invoice['total_net_sales_vat']),
		);

		$information[] = array("", "");
		$information[] = array("Balance Details");
		$information[] = array("Amount Due", "Total Amount Paid", "Remaining Balance");
		$information[] = array(
			$invoice['net_sales'],
			$invoice['total_amount_paid'],
			$invoice['remaining_balance'],
			);

		$meds = Invoice_Med::findAllByInvoiceId(array("invoice_id" => $invoice['id']));


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


		$information[] = array("", "");
		$information[] = array("Medicines");
		$information[] = array("RPC Medicines", "Quantity", "Cost/Item", "Cost");
		foreach ($rpc_meds as $key => $value) {
			$information[] = array(
					$value['medicine_name'] . "(" . $value['dosage'] . " " . $value['dosage_type'] . ")",
					$value['quantity'],
					$value['price'],
					$value['total_price']
				);
		}
		
		$other_charges = Invoice_Other_Charges::findAllByInvoiceId(array("invoice_id" => $invoice['id']));

		if($other_charges){
			$information[] = array("", "");
			$information[] = array("Other Charges");
			$information[] = array("Description", "Quantity", "Cost Per Item", "Total Cost");

			foreach ($other_charges as $key => $value) {
				$information[] = array(
					$value['description_id'],
					$value['quantity'],
					$value['cost_per_item'],
					$value['cost'],
					);
			}

		}

		$cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $invoice['id']));


		if($cost_modifier){
			$information[] = array("", "");
			$information[] = array("Cost Modifiers");
			$information[] = array("Applies to", "Modifier Type", "Modify Due to", "Cost", "Total Cost");

			foreach ($cost_modifier as $key => $value) {
				$information[] = array(
					$value['applies_to'],
					$value['modifier_type'],
					$value['modify_due_to'],
					($value['cost_type'] == "%" ? $value['cost_modifier'] . " " . $value['cost_type'] : $value['cost_modifier']) ,
					$value['total_cost'],
					);
			}

		}

		$collections 	= Invoice_Receipts::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
		
		if($collections){
			$information[] = array("", "");
			$information[] = array("Collections");
			$information[] = array("OR Number", "Amount Paid", "Date Receipt");

			foreach ($collections as $key => $value) {
				$information[] = array(
					$value['or_number'],
					$value['amount_paid'],
					$value['date_receipt'],
					);
			}

		}
		
		// debug_array($information);

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}



	function download_report_collection(){
		$filename 		= $this->uri->segment(3);
		$or_number 		= $this->uri->segment(4);

		$receipt 		= Invoice_Receipts::findById(array("id" => $or_number));
		$invoice 	= Invoice::findById(array("id" => $receipt['invoice_id']));
		$patient 	= Patients::findById(array("id" => $invoice['patient_id']));

		$information[] = array("Patient Name","OR Number", "Amount Paid", "Date of Receipt");
		$information[] = array(
				$patient['patient_name'],
				$receipt['or_number'],
				$receipt['amount_paid'],
				$receipt['date_receipt']
			);

		$cash 		= Invoice_Receipts_Cash::findAllByORid(array("or_id" => $receipt['id']));

		if($cash){
			$information[] = array("", "");
			$information[] = array("", "");
			$information[] = array("Cash");
			$information[] = array("Price");
			foreach ($cash as $key => $value) {
				$information[] = array($value['price']);
			}
		}
		$cheque 	= Invoice_Receipts_Cheque::findAllByORid(array("or_id" => $receipt['id']));

		if($cheque){
			$information[] = array("", "");
			$information[] = array("", "");
			$information[] = array("Cheque");
			$information[] = array("Cheque Number", "Cheque Date", "Bank Name" , "Price");
			foreach ($cheque as $key => $value) {
				$information[] = array(
					$value['cheque_number'],
					$value['cheque_date'],
					$value['bank_name'],
					$value['price']);
			}
		}

		$cc 		= Invoice_Receipts_CC::findAllByORid(array("or_id" => $receipt['id']));

		if($cc){
			$information[] = array("", "");
			$information[] = array("", "");
			$information[] = array("Credit Card");
			$information[] = array("Card Type", "Bank Name", "Price");
			foreach ($cc as $key => $value) {
				$information[] = array(
					$value['card_type'],
					$value['bank_name'],
					$value['price']);
			}
		}

		$credit 	= Invoice_Receipts_Credit::findAllByORid(array("or_id" => $receipt['id']));

		if($credit){
			$information[] = array("", "");
			$information[] = array("", "");
			$information[] = array("Credit");
			$information[] = array("Price");
			foreach ($credit as $key => $value) {
				$information[] = array($value['price']);
			}
		}

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}



	function download_report_all_collections(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);

		$params = array(
					"from_date" => $from_date,
					"to_date"	=> $to_date
				);

		$collections = Invoice_Receipts::findAllbyDateGenerated($params);
 		
		#debug_array($regimen);
		#$information[] = array("Patient Name","Invoice Number", "OR Number", "Amount Paid", "Date of Receipt","Cash", "Cheque", "Credit Card", "Credits", "Cheque Details", "Credit Card Details");
		$information[] = array("Patient Name","Invoice Number", "OR Number", "Amount Paid", "Discounted Amount for SC", "Date of Receipt","Cash", "Cheque", "Credit Card", "Credits", "Cheque Details", "Credit Card Details", "Remarks");
		
		$total_cost = 0;
		foreach ($collections as $key => $value) {

			$invoice 	= Invoice::findById(array("id" => $value['invoice_id']));
			$patient 	= Patients::findById(array("id" => $invoice['patient_id']));
			$cost_modifier = Invoice_Cost_Modifier:: findByInvoiceId2(array("invoice_id" => $invoice['id']));
			//$total_cost = 0;	
			foreach ($cost_modifier as $key => $value2) {
				//$medicine = Inventory::findByName(array("medicine_name" => $value2['applies_to']));
				$medicine = Inventory::findByNameandMedId(array("medicine_name" => $value2['applies_to'], "id" => $value2['is_medicine']));
				$cost = str_replace('-', '', $value2['total_cost']);
				if(!empty($medicine)){
					//Medicine sya
					$invoice_med = Invoice_Med::findByInvoiceIdandMedicineId(array("id" => $invoice['id'], "medicine_id" => $medicine['id']));
					$total = $invoice_med['total_price'] - $cost;		
				}elseif ($value2['applies_to'] == 'All Medicines') {
					$total = $invoice['total_regimen_cost'] - $cost;
				}elseif ($value2['applies_to'] == 'All Other Charges')	{
					$total = $invoice['	total_other_charges'] - $cost;
				}else{
					//Other Charges
					$other_charges = Other_Charges::findByName(array("r_centers" => $value2['applies_to']));
					$invoice_other_charges = Invoice_Other_Charges::findByInvoiceIdandRCenters(array("id" => $invoice['id'], "description_id" => $other_charges['id']));
					$total = $invoice_other_charges['cost'] - $cost;
					
				}
			$total_cost += $total;
			}
			
			$cash 		= Invoice_Receipts_Cash::findAllByORid(array("or_id" => $value['id']));

			$total_cash = 0;
			foreach ($cash as $a => $b) {
				$total_cash += $b['price'];
			}
			$cheque 	= Invoice_Receipts_Cheque::findAllByORid(array("or_id" => $value['id']));
			$total_cheque = 0;
			foreach ($cheque as $c => $d) {
				$total_cheque += $d['price'];
				$cheque_number = $d['cheque_number'];
				$bank_name = $d['bank_name'];

			}
			$cc 		= Invoice_Receipts_CC::findAllByORid(array("or_id" => $value['id']));
			$total_cc = 0;
			foreach ($cc as $e => $f) {
				$total_cc += $f['price'];
				$cc_bankname = $f['bank_name'];
				$card_type = $f['card_type'];
			}
			$credit 	= Invoice_Receipts_Credit::findAllByORid(array("or_id" => $value['id']));
			$total_credit = 0;
			foreach ($credit as $g => $h) {
				$total_credit += $h['price'];
			}
			
			#debug_array($total_cost);
			$information[] = array(
				$patient['patient_name'],
				$invoice['invoice_num'],
				$value['or_number'],
				$value['amount_paid'],
				$total_cost  <= 0 ? 0.00 : $total_cost,
				#empty($Discounted) ? 0.00 : number_format($Discounted, 2, '.', ','),
				$value['date_receipt'],
				$total_cash,
				$total_cheque,
				$total_cc,
				$total_credit,
				(!empty($total_cheque) ? $bank_name ." ". $cheque_number : '-'),
				(!empty($total_cc) ? $cc_bankname ." ". $card_type : '-'),
				$value['notes']
			);
			unset($total_cost);
			unset($Discounted);
		}
		#unset($total_cost);
		/*$information[] = array("");

		$information[] = array("test kung meron");*/
		#debug_array($information);
		#debug_array($cost_modifier);
		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_claim_sold_report(){
		$filename 		= $this->uri->segment(3);
		$claim_from_date  = $this->uri->segment(4);
		$claim_to_date    = $this->uri->segment(5);

		$date = new DateTime($claim_from_date);
		$result = $date->format('M d,Y');
		$date = new DateTime($claim_to_date);
		$result1 = $date->format('M d,Y');
		$all_medicine = Inventory::findAllMedicines();

		$information[] = array("Generated Date","From ".$result." To ".$result1);
		$information[] = array("Medicine_id","Total Sold Item",);

		foreach ($all_medicine as $key => $value) {
			$invoice_med = Invoice_Med::findSoldItem(array("id" => $value['id'], "from_date" => $claim_from_date, "to_date" => $claim_to_date));
			
			$information[] = array(
				$value['medicine_name'],
				empty($invoice_med['total_quantity']) ?  0 : $invoice_med['total_quantity'],
			);
		}
		$data['information'] = $information;
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_event_daterange(){
		$filename 		= $this->uri->segment(3);
		$c_from_date    = $this->uri->segment(4);
		$c_to_date      = $this->uri->segment(5);

		$params = array(
					"from_date" => $c_from_date,
					"to_date"	=> $c_to_date
				);

		$calendar_events = Calendar_Events::findAllByEventDate($params);
		$information[] = array("Event Name", "Patient Name", "Date", "Time", "Invitees","Type", "Location", "Status", "Details" );

		foreach ($calendar_events as $key => $value) {
			$type = Calendar_Dropdown::findByEventType(array("id" => $value['type']));
			$location = Calendar_Dropdown::findByLocation(array("id" => $value['location']));

			$invites = Calendar_Invites::findAllInviteesByEventID(array("event_id" => $value['id']));

			foreach( $invites as $key => $value2){
				$invitees .= $value2['lastname'] . ',' . $value2['firstname'] .'|';
			}

			$patient = Calendar_Patient::findAllPatientByEventID(array("event_id" => $value['id']));
			foreach( $patient as $key => $value3){
				$patients .= $value3['lastname'] . ',' . $value3['firstname'] .'|';
			}

			 $start_date  = new DateTime($value['start_db']);
         	 $end_date    = new DateTime($value['end_db']);

         	 
         	 $time = $start_date->format('h:ia - ') .''. $end_date->format('h:ia');
         	 
			$information[] = array(
				$value['title'],
				$patients,
				$start_date->format('M. j,Y '),
				$time,
				$invitees,
				$type['value'],
				$location['value'],
				$value['status'],
				$value['details'],
			);
			unset($invitees);
			unset($patients);
		}
		
		$data['information'] = $information;
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);

	}

	function download_event_per_patient(){
		$filename 		= $this->uri->segment(3);
		$patient_id 	= $this->uri->segment(4);
		$patient = Patients::findById(array("id" => $patient_id));
		$calendar_patient  = Calendar_Patient::findByPatientByEventID(array("id" => $patient_id));

		$information[] = array("Patient Name: ".$patient['patient_name']);
		$information[] = array(" ");
		$information[] = array("Event Name", "Patient Name", "Date", "Time", "Invitees","Type", "Location", "Status", "Details" );

		foreach ($calendar_patient as $key => $value) {
			$events = Calendar_Events::findById(array("id" => $value['event_id']));
			
			$type = Calendar_Dropdown::findByEventType(array("id" => $events['type']));
			$location = Calendar_Dropdown::findByLocation(array("id" => $events['location']));

			$invites = Calendar_Invites::findAllInviteesByEventID(array("event_id" => $events['id']));

			foreach( $invites as $key => $value2){
				$invitees .= $value2['lastname'] . ',' . $value2['firstname'] .'|';
			}
			 $start_date  = new DateTime($events['start_db']);
         	 $end_date    = new DateTime($events['end_db']);

         	 
         	 $time = $start_date->format('h:ia - ') .''. $end_date->format('h:ia');
         	 
			$information[] = array(
				$events['title'],
				$patient['patient_name'],
				$start_date->format('M. j,Y '),
				$time,
				$invitees,
				$type['value'],
				$location['value'],
				$events['status'],
				$events['details'],
			);
			unset($invitees);
		}


		$data['information'] = $information;
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_birthdays(){
		$filename 		= $this->uri->segment(3);
		$month 	= $this->uri->segment(4);

		$patient = Patients::findAll();

		$information[] = array("Month: ".$month);
		$information[] = array(" ");
		$information[] = array("Patient Name", "Birthday", "Age");
		foreach ($patient as $key => $value) {
			$date = strtotime($value['birthdate']);
			$patient_month= ( date('F', $date));
			if($month != 'All'){
				if($patient_month == $month){
					$patient_name = $value['patient_name'];
					$birthdate = $value['birthdate'];
					$age = $value['age'];

					$information[] = array(
						$patient_name,
						$birthdate,
						$age
					);
					unset($patient_name);
					unset($birthdate);
					unset($age);
				}
			}else{
				$information[] = array(
						$value['patient_name'],
						$value['birthdate'],
						$value['age'],
					);
					unset($value['patient_name']);
					unset($value['birthdate']);
					unset($value['age']);
				
			}	
		}

		$data['information'] = $information;
		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);

	}

	function download_report_all_active_patient(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);

		$params = array(
					"from_date" => $from_date,
					"to_date"	=> $to_date
				);

		$patients = Patients::findAllActivePatients($params);

		$information[] = array("Patient Code", "First Name", "Last Name", "Appointment", "Assigned Doctor", "Attending Doctor", "Gender", "Birthdate", "Place of Birth", "Age", "Address", "City", "TIN", "Senior Citizen ID", "Email Address", "Civil Status", "Dominant Hand", "Work Status", "Contact Name", "Contact Email Address", "Credits");

		foreach ($patients as $key => $value) {
			$today = new DateTime();
			$age = new DateTime($value['birthdate']);
			$interval = $today->diff($age);
			$patient_age = $interval->y;

			$asd = Doctors::findById(array("id"=>$value['doc_assigned_id']));
			$atd = Doctors::findById(array("id"=>$value['doc_attending_id']));
			
			$information[] = array(
				$value['patient_code'],
				$value['firstname'],
				$value['lastname'],
				$value['appointment'],
				$asd['full_name'],
				$atd['full_name'],
				$value['gender'],
				$value['birthdate'],
				$value['placeofbirth'],
				$patient_age,
				$value['address'],
				$value['city'],
				$value['tin'],
				$value['sc_id'],
				$value['email_address'],
				$value['civil_status'],
				$value['dominant_hand'],
				$value['work_status'],
				$value['contact_name'],
				$value['contact_email_address'],
				$value['credit'],
			);
		}

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}

	function download_report_all_inactive_patient(){
		$filename 	= $this->uri->segment(3);
		$from_date 	= $this->uri->segment(4);
		$to_date 	= $this->uri->segment(5);

		$params = array(
					"from_date" => $from_date,
					"to_date"	=> $to_date
				);

		$patients = Patients::findAllInactivePatients($params);

		$information[] = array("Patient Code", "First Name", "Last Name", "Appointment", "Assigned Doctor", "Attending Doctor", "Gender", "Birthdate", "Place of Birth", "Age", "Address", "City", "TIN", "Senior Citizen ID", "Email Address", "Civil Status", "Dominant Hand", "Work Status", "Contact Name", "Contact Email Address", "Credits");

		foreach ($patients as $key => $value) {
			$today = new DateTime();
			$age = new DateTime($value['birthdate']);
			$interval = $today->diff($age);
			$patient_age = $interval->y;

			$asd = Doctors::findById(array("id"=>$value['doc_assigned_id']));
			$atd = Doctors::findById(array("id"=>$value['doc_attending_id']));
			
			$information[] = array(
				$value['patient_code'],
				$value['firstname'],
				$value['lastname'],
				$value['appointment'],
				$asd['full_name'],
				$atd['full_name'],
				$value['gender'],
				$value['birthdate'],
				$value['placeofbirth'],
				$patient_age,
				$value['address'],
				$value['city'],
				$value['tin'],
				$value['sc_id'],
				$value['email_address'],
				$value['civil_status'],
				$value['dominant_hand'],
				$value['work_status'],
				$value['contact_name'],
				$value['contact_email_address'],
				$value['credit'],
			);
		}

		$data['information'] = $information;

		$data['filename'] = $filename;

		$this->load->view("reports/generate_excel", $data);
	}
}