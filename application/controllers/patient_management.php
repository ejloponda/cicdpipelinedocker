<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_Management extends MY_Controller {
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
			Engine::appStyle('jquery-ui.min.css');
			Engine::appStyle('bootstrap.min.css');
			Engine::appStyle('font-awesome.min.css');
			Engine::appScript('management.js');
			Engine::appScript('jquery-ui.min.js');
			Engine::appScript('topbars.js');
			Engine::appScript('confirmation.js');
			Engine::appScript('profile.js');

			Jquery::datatable();
			Jquery::form();
			Jquery::inline_validation();
			Jquery::tipsy();
			Jquery::plup_uploader();
			Jquery::image_preview();
			Jquery::select2();
			Jquery::mask();
			Jquery::numberformat();
			// Jquery::singleUpload();
			Jquery::jBox();
			Jquery::pdfViewer();

			/* NOTIFICATIONS */
			Jquery::pusher();
			Jquery::gritter();
			Jquery::pnotify();
			Engine::appScript('notification.js');
			/* END */

			Bootstrap::datetimepicker();
			Bootstrap::datepicker();
			Bootstrap::modal();

			$data['page_title'] = "Patient Management";
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
			$this->load->view('management/index',$data);
		}
	}

	function viewPatientProfile(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();
		$success = false;
		if($post) {
			$post_id 		=  (int) $post['id'];
			$data['patient'] 	= $patient = Patients::findById(array("id" => $post_id));
			if($patient) {
				$success 	= true;
				$data['patient_image'] 		 = Patient_Photos::findbyId(array("patient_id" => $post_id));
				$data['contact_person'] 	 = $contact_person = Patient_Contact::findContactsById(array("id"=>$post_id));
				$data['contact_information'] = $contact_information = User_Contact::findContactsById(array("id"=>$post_id));
				$user_type_id 				 = User_Type::findByName( array("user_type" => $session['account_type'] ));
				$data['pm_pi']				 = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));
				$data['patient_notes']       = Patient_Notes::findByIdPatient(array("patient_id" => $patient['id']));
				$this->load->view('management/patient/view_patient',$data);
			}
		}
	}

	function upload_party_image() {
		$file 		= $_FILES['photo'];
		$session 	= $this->session->all_userdata();
		$user_id	= $this->encrypt->decode($session['user_id']);
		$user 		= User::findById(array("id" => $user_id));
		
		if($file) {
			
			$this->load->helper('string');
			$get_cpi 	= (int) $get['cpi'];
			$random_key = random_string('unique');

			$file_extension	= substr($file['name'], -3);
			$filename 		= $this->encrypt->sha1(basename($file['name']."$random_key")).date("hims",time()).".{$file_extension}";
			$upload_name 	= $file['name'];

			$dir = "files/patient/images/tmp/";
			if(!is_dir($dir)) {
				mkdir($dir,0777,true);
			}
			// unlink("$dir/".$_SESSION['cases']['tmp_party_image']['filename']);
			$path = "$dir/$filename";
			
			if(move_uploaded_file($file['tmp_name'], $path)) {

				echo BASE_FOLDER . "{$dir}{$filename}";
			}
		}
	}

	function viewMedicalHistoryProfile(){
		Engine::XmlHttpRequestOnly();
		
		$post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();

		$success = false;
		if($post) {
			$post_id 		=  (int) $post['id'];
			$data['patient'] 	= $patient = Patients::findById(array("id" => $post_id));
			if($patient) {
				$success 	= true;
				$data['patient_image'] 	= Patient_Photos::findbyId(array("patient_id" => $post_id));
				$data['disease'] 		= $disease = Disease::findByPatientId(array("patient_id" => $post_id));
				$user_type_id 			= User_Type::findByName( array("user_type" => $session['account_type'] ));
				$data['pm_fmh']			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 1));
				$this->load->view('management/patient/view_medical_history',$data);
			}
		}
	}

	function viewPersonalMedicalHistoryProfile(){
		Engine::XmlHttpRequestOnly();
		
		$post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();

		$success = false;
		if($post) {
			$post_id 		=  (int) $post['id'];
			$data['patient'] 	= $patient = Patients::findById(array("id" => $post_id));
			if($patient) {
				$success 	= true;
				$data['patient_image'] = Patient_Photos::findbyId(array("patient_id" => $post_id));
				$data['personal_disease'] = $personal_disease = Personal_Disease::findByPatientId(array("patient_id" => $post_id));
				$user_type_id 				 = User_Type::findByName( array("user_type" => $session['account_type'] ));
				$data['pm_pmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 3));
				$this->load->view('management/patient/view_personal_medical_history',$data);
			}
		}
	}

	function view_invoice_list(){
		Engine::XmlHttpRequestOnly();
		$post 			 = $this->input->post();
		$patient_id 	 = $post['id'];
		$data['patient'] = $patient = Patients::findById(array("id" => $patient_id));
		if($patient){
			$data['patient_image'] 	= Patient_Photos::findbyId(array("patient_id" => $patient_id));
			$data['invoice'] 		= $invoice = Invoice::findAllByPatient(array("patient_id" => $patient_id));
			$data['stocks'] 		= $stocks = Stock::findAllByPatientId(array("patient_id" => $patient_id));
			$this->load->view("management/patient/accounting/view_invoice_list", $data);
		}
	}

	function view_invoice_record(){
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();
		$invoice_id = $post['invoice_id'];
		$invoice 	= Invoice::findById(array("id"=>$invoice_id));
		$session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
		$data['accounting'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 22));

		if($invoice){
			
			/*$meds = Invoice_Med::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
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
			} */
			$data['invoice'] = $invoice;
			$meds = Orders_Meds::findbyOrderId(array("order_id" => $invoice['order_id']));
			$data['other_charges'] = $other_charges = Invoice_Other_Charges::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['cost_modifier'] = $cost_modifier = Invoice_Cost_Modifier::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['returns']	   = $returns		= Returns::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			$data['collections']   = $collections 	= Invoice_Receipts::findAllByInvoiceId(array("invoice_id" => $invoice['id']));
			
			foreach ($meds as $key => $value) {
					$all_ids[] = $value['med_id'];
				}

				$med_ids = array_keys(array_flip($all_ids));

				foreach($med_ids as $key => $value){
					$medicine_quantity = 0;
					foreach ($meds as $k => $val) {
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

			foreach ($medic as $key => $value) {
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
			/* PATIENT */

			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $invoice['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $invoice['patient_id']));
			$this->load->view('management/patient/accounting/view_invoice', $data);
		} else {
			echo "<h4> Error 404: Invoice Not Found </h4>";
		}
	}

	function view_collection_record(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$or_id = $post['or_id'];
		$or = Invoice_Receipts::findById(array("id"=>$or_id));
		if($or){
			$data['or'] 	= $or;
			$data['cash'] 	= $cash 	= Invoice_Receipts_Cash::findAllByORid(array("or_id" => $or['id']));
			$data['cheque'] = $cheque 	= Invoice_Receipts_Cheque::findAllByORid(array("or_id" => $or['id']));
			$data['cc'] 	= $cc 		= Invoice_Receipts_CC::findAllByORid(array("or_id" => $or['id']));
			$data['credit'] 	= $credit 		= Invoice_Receipts_Credit::findAllByORid(array("or_id" => $or['id']));
			//$data['taxwithheld'] 	= $taxwithheld 		= Invoice_Receipts_TaxWithheld::findAllByORid(array("or_id" => $or['id']));

			/* INVOICE */
			$data['invoice'] = $invoice = Invoice::findById(array("id" => $or['invoice_id']));

			/* REGIMEN */
			$regimen_id = $invoice['regimen_id'];
			$version_id = $invoice['version_id'];
			$data['regimen'] = $regimen = Regimen::findById(array("id"=>$regimen_id));
			$data['version'] = $version = Regimen_Version::findByRegimenVersionId(array("regimen_id" => $regimen_id, "version_id" => $version_id));
			
			/* PATIENT */
			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $invoice['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $invoice['patient_id']));
			$this->load->view('management/patient/accounting/view_collection', $data);
		} else {
			echo "<h4> Error 404: Collection Not Found </h4>";
		}
	}

	function view_returns_list(){
		Engine::XmlHttpRequestOnly();
		$post 			 = $this->input->post();
		$patient_id 	 = $post['id'];
		$data['patient'] = $patient = Patients::findById(array("id" => $patient_id));
		if($patient){
			$data['patient_image'] 	= Patient_Photos::findbyId(array("patient_id" => $patient_id));
			
			$fields = array("a.id,a.invoice_id,a.patient_id,a.date_return,a.status,b.invoice_num");
			$params	= array(
					"fields" => $fields,
					"patient_id" => $patient_id
				);

			$data['returns'] 		= $returns = Returns::findAllByPatientId($params);
			$this->load->view("management/patient/returns/view_returns_list", $data);
		}
	}

	function view_returns_record(){
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();
		$returns_id = $post['return_id'];


		$returns = Returns::findById(array("id" => $returns_id));

		if($returns){
			$data['returns'] = $returns;

			$data['invoice'] = $invoice = Invoice::findById(array("id"=>$returns['invoice_id']));

			$data['collections'] = $collections = Invoice_Receipts::findById(array("id"=>$returns['invoice_id']));

			$meds = Returns_Meds::findAllByReturnsId(array("returns_id" => $returns_id));

			//Check if invoice is All Medicine
			$all_med = Invoice_Cost_Modifier::findByInvoiceIdandModifyDueTo(array("invoice_id" => $invoice['id'] ));
			
			if($all_med['cost_type'] == 'php'){
				$all_med = '';
			}

			foreach ($meds as $key => $value) {
				$a = Inventory::findById(array("id" => $value['medicine_id']));
				$dosage = Dosage_Type::findById(array("id" => $a['dosage_type']));
				$quantity_type = Quantity_Type::findbyId(array("id" => $a['quantity_type']));

				$cost_modifier = Invoice_Cost_Modifier::findByInvoiceId(array("invoice_id" => $invoice['id'], "applies_to" => $a['medicine_name']));	
				$invoice_med = Invoice_Med::findByInvoiceIdandMedicineId(array("id" => $invoice['id'], "medicine_id" => $value['medicine_id']));

				$rpc_meds[] = array(
					"medicine_id" 	=> $value['medicine_id'],
					"medicine_name"	=> $a['medicine_name'],
					"dosage" 		=> $a['dosage'],
					"dosage_type"	=> $dosage['abbreviation'],
					"price"			=> $invoice_med['price'],
					"quantity"		=> $value['quantity'],
					"quantity_type"	=> $quantity_type['abbreviation'],
					"cost_modifier" => !empty($all_med) ? '20': $cost_modifier['cost_modifier'],
					"cost_type"		=> !empty($all_med) ? '%': $cost_modifier['cost_type'],
					"modify_due_to" => !empty($all_med) ? 'Senior Discount': $cost_modifier['modify_due_to']
				);
			}

			$data['medicines'] = $rpc_meds;

			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $returns['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $returns['patient_id']));
			$this->load->view('management/patient/returns/view_returns', $data);
		} else {
			echo "<h4> Error 404: Returns' Record Not Found </h4>";
		}
	}

	function view_regimen_list(){
		Engine::XmlHttpRequestOnly();
		$post 			 = $this->input->post();
		$patient_id 	 = $post['id'];
		$data['patient'] = $patient = Patients::findById(array("id" => $patient_id));
		if($patient){
			$data['patient_image'] 	= Patient_Photos::findbyId(array("patient_id" => $patient_id));
			$data['regimen'] 		= $regimen = Regimen::generateListOfRegimenHistoryByPatientID(array("patient_id" => $patient_id));
			$this->load->view("management/patient/view_regimen_list", $data);
		}
	}

	function get_regimen_details($main_tbl,$reg_id){
		foreach ($main_tbl as $key => $value) {
			$breakfast 	= array();
			$lunch 		= array();
			$dinner 	= array();
			$bf 			= "bf";
			$list_bf 		= Regimen_Med_List::findByRegimenIDMealRow(array("regimen_id"=> $reg_id, "meal_type" => $bf, "row_id" => $value['id']));
			// Breakfast
			foreach ($list_bf as $m => $n) {
				$array[] = $n['activity'];
			}
			$unique_list = array_unique($array);

			foreach ($unique_list as $a => $b) {
				$bf_list = Regimen_Med_List::findByRegimenIdRowIdAct(array("regimen_id" => $reg_id, "row_id" => $value['id'], "meal_type" => $bf, "activity" => $b));
				// debug_array($bf_list);
				if(!empty($bf_list)){
					$med_ops = array();
					foreach ($bf_list as $c => $d) {
						$med_ops[] = array(
							"id"			=> $d['id'],
							"medicine_id" 	=> $d['medicine_id'],
							"medicine_name" => $d['medicine_name'],
							"quantity" 		=> $d['quantity'],
							"quantity_type" => $d['quantity_type'],
							"quantity_val" 	=> $d['quantity_val'],
						);
					}
					

					$breakfast[] = array(
							"activity" 	=> $d['activity'],
							"med_ops"	=> $med_ops
					);	
				} // end if	
			} // end foreach unique_list 

			// end breakfast
			$list_lunch 	= Regimen_Med_List::findByRegimenIDMealRow(array("regimen_id"=> $reg_id, "meal_type" => "lunch", "row_id" => $value['id']));
			// Lunch
			foreach ($list_lunch as $q => $w) {
				$array1[] = $w['activity'];
			}
			$unique_list2 = array_unique($array1);

			foreach ($unique_list2 as $a1 => $b1) {
				$lunch_list = Regimen_Med_List::findByRegimenIdRowIdAct(array("regimen_id" => $reg_id, "row_id" => $value['id'], "meal_type" => "lunch", "activity" => $b1));
				
				if(!empty($lunch_list)){
					$med_ops1 = array();
					foreach ($lunch_list as $c1 => $d1) {
					$med_ops1[] = array(
							"id"			=> $d1['id'],
							"medicine_id" 	=> $d1['medicine_id'],
							"medicine_name" => $d1['medicine_name'],
							"quantity" 		=> $d1['quantity'],
							"quantity_type" => $d1['quantity_type'],
							"quantity_val" 	=> $d1['quantity_val'],
						);
					
					}

					$lunch[] = array(
							"activity" 	=> $d1['activity'],
							"med_ops"	=> $med_ops1
						);
				}
			}
			// end lunch

			$list_dinner 	= Regimen_Med_List::findByRegimenIDMealRow(array("regimen_id"=> $reg_id, "meal_type" => "dinner", "row_id" => $value['id']));
			// Dinner
			foreach ($list_dinner as $m1 => $n1) {
				$array2[] = $n1['activity'];
			}
			$unique_list3 = array_unique($array2);

			foreach ($unique_list3 as $a2 => $b2) {
				$dinner_list = Regimen_Med_List::findByRegimenIdRowIdAct(array("regimen_id" => $reg_id, "row_id" => $value['id'], "meal_type" => "dinner", "activity" => $b2));
				if(!empty($dinner_list)){
					$med_ops2 = array();
					foreach ($dinner_list as $c2 => $d2) {
					$med_ops2[] = array(
							"id"			=> $d2['id'],
							"medicine_id" 	=> $d2['medicine_id'],
							"medicine_name" => $d2['medicine_name'],
							"quantity" 		=> $d2['quantity'],
							"quantity_type" => $d2['quantity_type'],
							"quantity_val" 	=> $d2['quantity_val'],
						);
					}

					$dinner[] = array(
							"activity" 	=> $d2['activity'],
							"med_ops"	=> $med_ops2
						);
				}
			} // end dinner	
			
			$my_data = array(
				"id"				=> $value['id'],
				"start_date"		=> $value['start_date'],
				"end_date"			=> $value['end_date'],
				"bf_instructions" 	=> $value['bf_instructions'],
				"l_instructions" 	=> $value['l_instructions'],
				"d_instructions" 	=> $value['d_instructions'],
				"version_id"		=> $value['version_id'],
				"breakfast" 		=> $breakfast,
				"lunch" 			=> $lunch,
				"dinner" 			=> $dinner,
				);
			
			$this->set_view_regimen_medicines($my_data);
		} // end $main_tbl
	}

	function set_view_regimen_medicines($array){
		$regimen_meds = $_SESSION['view_regimen_meds'];
		$regimen_meds[] = $array;
		// $this->session->set_userdata("view_regimen_meds", $regimen_meds);
		$_SESSION['view_regimen_meds'] = $regimen_meds;	
	}

	function view_regimen_record(){
		Engine::XmlHttpRequestOnly();
		$post 			 = $this->input->post();
		unset($_SESSION['view_regimen_meds']);
		$this->session->unset_userdata("view_regimen_meds");
		/*$reg_id 	 	 = $post['regimen_id'];
		$data['reg']	 = $reg = Regimen::findById(array("id"=>$reg_id));*/
		$data['patient_id'] = $patient_id = $post['id'];
		$data['reg'] = $reg = Regimen::findActiveRegimenByPatientId(array("id"=>$patient_id));
		$reg_id 	 	 = $reg['id'];

		if($reg){
			$data['patient']  	= $patient  	= Patients::findById(array("id" => $reg['patient_id']));
			$data['reg_id'] 	= $reg_id;
			$data['reg'] 		= $reg 			= Regimen::findById(array("id" => $reg_id));
			$data['patient']	= $patient 		= Patients::findById(array("id" => $reg['patient_id']));
			//$data['version_id']	= $version_id	= 0;
			//$data['main_tbl'] 	= $main_tbl 	= Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$data['photo'] 		= $patient_photo= Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			
			//$this->get_regimen_details($main_tbl,$reg_id);

			//if($post['version_id'] == 'NaN'){
			if(!isset($post['version_id'])){
				$data['latest_version'] = $latest 	= Regimen_Version::findLatest(array("regimen_id" => $reg_id));
				$v_id = empty($latest['id']) ? 0 : $latest['id'];	
				$data['sdate']	= empty($latest['start_date']) ? $reg['start_date'] : $latest['start_date'];
				$data['edate']	= empty($latest['end_date']) ? $reg['end_date'] : $latest['end_date'];
				$data['dgenerated'] = empty($latest['date_generated']) ? $reg['date_generated'] : $latest['date_generated'];		
				$data['_lmp']	=	empty($latest['lmp']) ? $reg['lmp'] : $latest['lmp'];
				$data['_program']	=	empty($latest['program']) ? $reg['program'] : $latest['program'];
				$data['_regimen_notes'] = empty($latest['regimen_notes']) ? $reg['regimen_notes'] : $latest['regimen_notes'];
				$data['_preferences'] = empty($latest['preferences']) ? $reg['preferences'] : $latest['preferences'];
				$data['_status'] = empty($latest['status']) ? $reg['status'] : $latest['status'];
				$data['version_id'] = empty($latest['id']) ? 0 : $latest['id'];
			}else{
				$v_id  = 0;
				$data['sdate']	= $reg['start_date'];
				$data['edate']	= $reg['end_date'];
				$data['dgenerated'] = $reg['date_generated'];
				$data['_lmp']	=	$reg['lmp'];
				$data['_program']	=	$reg['program'];
				$data['_regimen_notes'] = $reg['regimen_notes'];
				$data['_preferences'] = $reg['preferences'];
				$data['_status'] = $reg['status'];
			}
			//debug_array($data['sdate']);
			$data['old_version'] = Regimen_Med_Main::findByRegimenID(array("regimen_id" => $reg_id));

			$data['latest_main'] = $latest_main =Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $v_id));
			$data['latest_reg_version'] = $latest_version = Regimen_Med_List::findByRegimenIDRowID(array("row_id" => $latest_main['id']));
			//debug_array($latest_main);

			$this->get_regimen_details($latest_main,$reg_id);

			$data['latest_version'] = $latest 	= Regimen_Version::findLatest(array("regimen_id" => $reg_id));
			$data['versions'] 	= $version_tbls = Regimen_Version::findByRegimenId(array("regimen_id" => $reg_id));
			$data['meds']		= $meds 		= $_SESSION['view_regimen_meds'];
			$files = Patient_Files::findAllByPatientIdCategory(array("patient_id" => $patient['id'], "category_id" => 3));
			
		}
		$this->load->view('management/patient/view_regimen',$data);
	}

	function view_regimen_version(){
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();
		$session 	= $this->session->all_userdata();
		
		unset($_SESSION['view_regimen_meds']);
		$this->session->unset_userdata("view_regimen_meds");

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$version_id 	= $post['version_id'];
		$version 		= Regimen_Version::findById(array("id" => $version_id));
		$data['rc_reg']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));
		if($version){

			$data['reg_id'] 	= $reg_id 		 = $version['regimen_id'];
			$data['reg'] 		= $reg 			 = Regimen::findById(array("id" => $reg_id));
			$data['patient']	= $patient 		 = Patients::findById(array("id" => $reg['patient_id']));
			$data['photo'] 		= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $reg['patient_id'])); 
			$data['main_tbl'] 	= $main_tbl 	 = Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$this->get_regimen_details($main_tbl,$reg_id);
			$data['meds']		= $meds 		 = $_SESSION['view_regimen_meds'];
			$data['latest_version'] = $latest 	= Regimen_Version::findLatest(array("regimen_id" => $reg_id));
			$data['versions'] 	= $version_tbls  = Regimen_Version::findByRegimenId(array("regimen_id" => $reg_id));
			$data['version_id']	= $version_id;
			$data['version']	= $version;
		}
		
		$this->load->view('management/patient/view_regimen_version', $data);
	}

	function view_regimen_summary(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$regimen_id = (int) $post['regimen_id'];
		if($regimen_id){
			$data['reg'] 	= $reg 			 = Regimen::findById(array("id"=>$regimen_id));
			$data['bf']		= $bf 			 = Regimen_Med_List::findByRegimenIdMealType(array("regimen_id" => $regimen_id, "meal_type" => "bf"));
			$data['lunch']	= $lunch 		 = Regimen_Med_List::findByRegimenIdMealType(array("regimen_id" => $regimen_id, "meal_type" => "lunch"));
			$data['dinner']	= $dinner		 = Regimen_Med_List::findByRegimenIdMealType(array("regimen_id" => $regimen_id, "meal_type" => "dinner"));
			$summary 		= Regimen_Med_List::findByRegimenID(array("regimen_id"=> $regimen_id));
			foreach ($summary as $key => $value) {
				$array[] = $value['medicine_id'];
			}
			$data['regimen_summary'] = $regimen_summary = array_unique($array);
			$data['photo'] 	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			$data['patient']= $patient 		 = Patients::findById(array("id" => $reg['patient_id']));
			$session = $this->session->all_userdata();
			$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
			$data['rc_reg']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));
			$this->load->view('management/patient/view_regimen_summary',$data);
		}
	}

	

	function patient(){
		$data['page_title'] = "Patient Management";
		$data['session'] 	= $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['pm_pi']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));
		//$patient_code = patients:: generatePatientCode($params);
		//debug_array($patient_code);
		$this->load->view('management/patient/patients',$data);
	}

	function add_patient(){

		Engine::appStyle('bootstrap.min.css');
		Engine::appStyle('RPC-style.css');
		Engine::appStyle('forms-style.css');

		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();

		$data['doctors']	= Doctors::findAllActive();
		$data['page_title'] = "Add New Patient";
		$data['state']	= $array = array(
				"AL"=>"Alabama", "AK"=>"Alaska", "AZ"=>"Arizona", "AR"=>"Arkansas", "CA"=>"California", "CO"=>"Colorado",
				"CT"=>"Connecticut", "DE"=>"Delaware", "DC"=>"District Of Columbia", "FL"=>"Florida", "GA"=>"Georgia", "HI"=>"Hawaii",
				"ID"=>"Idaho", "IL"=>"Illinois", "IN"=>"Indiana", "IA"=>"Iowa", "KS"=>"Kansas", "KY"=>"Kentucky",
				"LA"=>"Louisiana", "ME"=>"Maine", "MD"=>"Maryland", "MA"=>"Massachusetts", "MI"=>"Michigan", "MN"=>"Minnesota",
				"MS"=>"Mississippi", "MO"=>"Missouri", "MT"=>"Montana", "NE"=>"Nebraska", "NV"=>"Nevada", "NH"=>"New Hampshire",
				"NJ"=>"New Jersey", "NM"=>"New Mexico", "NY"=>"New York", "NC"=>"North Carolina", "ND"=>"North Dakota", "OH"=>"Ohio",
				"OK"=>"Oklahoma", "OR"=>"Oregon", "PA"=>"Pennsylvania", "RI"=>"Rhode Island", "SC"=>"South Carolina", "SD"=>"South Dakota",
				"TN"=>"Tennessee", "TX"=>"Texas", "UT"=>"Utah", "VT"=>"Vermont", "VA"=>"Virginia", "WA"=>"Washington",
				"WV"=>"West Virginia", "WI"=>"Wisconsin", "WY"=>"Wyoming"
			);
		$data['session'] = $session = $this->session->all_userdata();
		//$data['patient'] = $patient = Patients::findById(array("id" => $post_id));
		$this->load->view('management/patient/forms/add-patient',$data);

	}

	function medicalHistory(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$success = false;
		if($post){
			$post_id = (int) $post['id'];
			$data['patient'] 	= $patient = Patients::findById(array("id" => $post_id));
			if($patient){
				$success = true;
				
				$data['disease_category'] = $disease_category = Disease_Name::getAllActiveCategoryData(array("category"=>"Family", "status" => "Active"));
				$this->load->view('management/patient/medicalhistory/medicalhistory',$data);
			}
		}
	}

	function personalMedicalHistory(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$success = false;
		if($post){
			$post_id = (int) $post['id'];
			$data['patient'] 	= $patient = Patients::findById(array("id" => $post_id));
			if($patient){
				$success = true;
				$data['disease_category'] = $disease_category = Disease_Name::getAllActiveCategoryData(array("category"=>"Personal", "status" => "Active"));
				$this->load->view('management/patient/medicalhistory/personal_medicalhistory',$data);
			}
		}
	}

	function loadDiseaseForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session 		= $this->session->all_userdata();
		if($post){
			$data['post_id'] 			= $post_id = $post['id'];
			$data['disease_id'] 		= $disease_id = $post['disease_id'];
			$data['listOfDisease'] 		= $listOfDisease = Disease::findByIdPatientAll(array("disease_id" => $disease_id, "patient_id" => $post_id));
			$data['disease_category'] 	= $disease_category = Disease_Type::findAllbyDiseaseId(array("id" => $disease_id, "status" => 'Active'));
			$user_type_id 				= User_Type::findByName( array("user_type" => $session['account_type'] ));
			$data['pm_fmh']				= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 1));
			$this->load->view('management/patient/medicalhistory/subclass/disease',$data);

		};
	}

	function loadPersonalDiseaseForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session 		= $this->session->all_userdata();
		if($post){
			$data['post_id'] = $post_id = $post['id'];
			$data['disease_id'] = $disease_id = $post['disease_id'];
			$data['listOfDisease'] = $listOfDisease = Personal_Disease::findByIdPatientAll(array("disease_id" => $disease_id, "patient_id" => $post_id));
			$data['disease_category'] = $disease_category = Disease_Type::findAllbyDiseaseId(array("id" => $disease_id, "status" => 'Active'));
			$user_type_id 				 = User_Type::findByName( array("user_type" => $session['account_type'] ));
			$data['pm_pmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 3));
			$this->load->view('management/patient/medicalhistory/personal_subclass/personal_disease',$data);

		};
	}

	function edit_patient(){
		Engine::XmlHttpRequestOnly();
		$session 		= $this->session->all_userdata();
		

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['pm_pi']			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));

		$post = $this->input->post();
		$success = false;
		if($post) {
			$data['user'] = $user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
			$post_id 		=  (int) $post['id'];
			$data['patient'] 	= $patient = Patients::findById(array("id" => $post_id));
			if($patient) {
				$data['doctors']	= Doctors::findAllActive();
				$data['patient_image'] = Patient_Photos::findbyId(array("patient_id" => $post_id));
				$data['state']	= $array = array(
					"AL"=>"Alabama", "AK"=>"Alaska", "AZ"=>"Arizona", "AR"=>"Arkansas", "CA"=>"California", "CO"=>"Colorado",
					"CT"=>"Connecticut", "DE"=>"Delaware", "DC"=>"District Of Columbia", "FL"=>"Florida", "GA"=>"Georgia", "HI"=>"Hawaii",
					"ID"=>"Idaho", "IL"=>"Illinois", "IN"=>"Indiana", "IA"=>"Iowa", "KS"=>"Kansas", "KY"=>"Kentucky",
					"LA"=>"Louisiana", "ME"=>"Maine", "MD"=>"Maryland", "MA"=>"Massachusetts", "MI"=>"Michigan", "MN"=>"Minnesota",
					"MS"=>"Mississippi", "MO"=>"Missouri", "MT"=>"Montana", "NE"=>"Nebraska", "NV"=>"Nevada", "NH"=>"New Hampshire",
					"NJ"=>"New Jersey", "NM"=>"New Mexico", "NY"=>"New York", "NC"=>"North Carolina", "ND"=>"North Dakota", "OH"=>"Ohio",
					"OK"=>"Oklahoma", "OR"=>"Oregon", "PA"=>"Pennsylvania", "RI"=>"Rhode Island", "SC"=>"South Carolina", "SD"=>"South Dakota",
					"TN"=>"Tennessee", "TX"=>"Texas", "UT"=>"Utah", "VT"=>"Vermont", "VA"=>"Virginia", "WA"=>"Washington",
					"WV"=>"West Virginia", "WI"=>"Wisconsin", "WY"=>"Wyoming"
				);
				$success 	= true;
				$this->load->view('management/patient/forms/edit_patient',$data);
			}
		}
	}

	function delete_patient_form(){
		Engine::XmlHttpRequestOnly();
		
		$post = $this->input->post();
		$success = false;
		if($post) {
			$post_id 		=  (int) $post['id'];
			$data['patient'] 	= $patient = Patients::findById(array("id" => $post_id));
			if($patient) {
				$success 	= true;
				$this->load->view('management/patient/forms/delete_patient_form',$data);
			}
		}
	}

	function delete_patient(){
		Engine::XmlHttpRequestOnly();	
		$session = $this->session->all_userdata();
		$session_id = $this->session->userdata('user_id');
		$user_id1 = $this->encrypt->decode($session_id);	

		$post = $this->input->post();
		if($post) {
			$post_id 		=  (int) $post['id'];
			$patient = Patients::delete($post_id);
			Patient_Photos::deleteByPatientId($post_id);
			$patient_photo = Patient_Photos::findById(array("patient_id" => $post_id));
			unlink("$dir/".$patient_photo['filename'].".".$patient_photo['extension']);
			$act_tracker = array(
				"module"		=> "rpc_patient",
				"user_id"		=> $user_id1,
				"entity_id"		=> $post['id'],
				"message_log" 	=> $session['name'] ." ". "Successfully Deleted Patient: {$patient_name['patient_name']}",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully deleted!";

			//New Notification
			$msg = $session['name'] . " has successfully Deleted Patient: {$patient_name['patient_name']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Patient " . $patient_name['patient_name'],
				'type' => 'info'
			));

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function add_medical_history(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$user = $this->session->all_userdata();

		if($post){

			$patient_id	=  (int) $post['patient_id'];
			$disease_id = (int) $post['disease_id'];

			if($patient_id){
					foreach ($post['field_edit'] as $key => $value) {
						$mh_id = $value['mh_id'];
						$record1 = array(
							"patient_id" => $patient_id,
							"disease_id" => $disease_id,
							"disease_type_id" => $value['category'],
							"relation" => $value['relation'],
							"age_diagnosed" => $value['age'],
							"last_modified_by" => $session_id,
						);
						Disease::save($record1,$mh_id);

						$act_tracker = array(
							"module"		=> "rpc_patient",
							"user_id"		=> $session_id,
							"entity_id"		=> $post['patient_id'],
							"message_log" 	=> $user['name'] ." ". "Successfully Updated Family Medical History in Patient: {$patient['patient_name']}",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
						Activity_Tracker::save($act_tracker);
					}

					foreach ($post['field'] as $key => $value) {
						$record = array(
							"patient_id" => $patient_id,
							"disease_id" => $disease_id,
							"disease_type_id" => $value['category'],
							"relation" => $value['relation'],
							"age_diagnosed" => $value['age'],
							"date_created" => date("Y-m-d H:i:s"),
							"last_modified_by" => $session_id,
						);
						Disease::save($record);

						$act_tracker = array(
							"module"		=> "rpc_patient",
							"user_id"		=> $session_id,
							"entity_id"		=> $post['patient_id'],
							"message_log" 	=> $user['name'] ." ". "Successfully Added Family Medical History in Patient: {$patient['patient_name']}",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
						Activity_Tracker::save($act_tracker);
					}
			}
				$json['is_successful'] 	= true;
				$json['message']		= "Successfully Updated Medical History!"; 
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function add_personal_medical_history(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		if($post){

			$patient_id	=  (int) $post['patient_id'];
			$disease_id = (int) $post['disease_id'];

			if($patient_id){
					foreach ($post['field_edit'] as $key => $value) {
						$mh_id = $value['mh_id'];
						$record1 = array(
							"patient_id" => $patient_id,
							"disease_id" => $disease_id,
							"disease_type_id" => $value['category'],
							"age_diagnosed" => $value['age'],
							"last_modified_by" => $session_id,
						);
						Personal_Disease::save($record1,$mh_id);

						$act_tracker = array(
							"module"		=> "rpc_patient",
							"user_id"		=> $session_id,
							"entity_id"		=> $post['patient_id'],
							"message_log" 	=> $user['name'] ." ". "Successfully Updated Personal Medical History in Patient: {$patient['patient_name']}",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
						Activity_Tracker::save($act_tracker);
					}

					foreach ($post['field'] as $key => $value) {
						$record = array(
							"patient_id" => $patient_id,
							"disease_id" => $disease_id,
							"disease_type_id" => $value['category'],
							"age_diagnosed" => $value['age'],
							"date_created" => date("Y-m-d H:i:s"),
							"last_modified_by" => $session_id,
						);
						Personal_Disease::save($record);

						$act_tracker = array(
							"module"		=> "rpc_patient",
							"user_id"		=> $session_id,
							"entity_id"		=> $post['patient_id'],
							"message_log" 	=> $user['name'] ." ". "Successfully Added Personal Medical History in Patient: {$patient['patient_name']}",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
						Activity_Tracker::save($act_tracker);
					}
			}
				$json['is_successful'] 	= true;
				$json['message']		= "Successfully Updated Medical History!"; 
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}


	function add_new_patient(){
		$session = $this->session->all_userdata();
		$session_id = $this->session->userdata('user_id');
		$user_id = $this->encrypt->decode($session_id);
		$post = $this->input->post();
		// debug_array($post);
		/*$_SESSION['tmp']['patient_code'] = $post['z'];

		if($post['z'] != ""){
			$data = $post['z'];
			debug_array($data);
		}
		*/
		if($post['id']) {
				$post_id	= (int) $post['id'];
				$patient 	= Patients::findById(array("id" => $post_id));
				$file 		= $_FILES['patient_image_edit'];
				// debug_array($file);
				if($patient) {
					
					if($file['error'] == 0){
						$this->load->helper('string');
						$random_key = random_string('unique');


						$file_extension	= substr($file['name'], -3);
						$raw_name 		= $this->encrypt->sha1(basename($file['name']."$random_key")).date("hims",time());
						$upload_name 	= $file['name'];
						$file_size 		= $file['size'];

						$dir = "files/photos/tmp/" . $post['patient_code_edit'] . "/";
						if(!is_dir($dir)) {
							mkdir($dir,0777,true);
						}
						// debug_array($post['patient_code_edit']);
						$patient_photo = Patient_Photos::findById(array("patient_id" => $post_id));
						// debug_array($patient_photo);
						unlink("$dir/".$patient_photo['filename'].".".$patient_photo['extension']);

						$path = "$dir/$raw_name.{$file_extension}";
							
						if(move_uploaded_file($file['tmp_name'], $path)) {

							$arr = array(
								"patient_id" => $post_id,
								"patient_code" => $post['patient_code_edit'],
								"upload_filename" => $upload_name,
								"filename" => $raw_name,
								"extension" => $file_extension,
								"size" => $file_size,
								"base_path" => $dir,
							);

							Patient_Photos::save($arr,$patient_photo['id']);
						}
					}
					$name = $post['lastname_edit'] . ", " . $post['firstname_edit'] ." ". $post['middlename_edit'];
					//$name = $post['lastname_edit'] . ", " . $post['firstname_edit'];
					$birthdate = date( "F d Y", strtotime( $post['birthdate_edit'] ) );
					// $appointment = date( "F d Y", strtotime( $post['appointment_edit'] ) );
					$record = array(
						"appointment" 			=> $post['appointment_edit'],
						"patient_code" 			=> $post['patient_code_edit'],
						"doc_assigned_id"		=> $post['doctor_assigned'],
						"doc_attending_id"		=> $post['attending_doctor'],
						"patient_name" 			=> $name,
						"lastname" 				=> $post['lastname_edit'],
						"firstname" 			=> $post['firstname_edit'],
						"middlename" 			=> $post['middlename_edit'],
						"gender" 				=> $post['gender_edit'],
						"birthdate" 			=> $birthdate,
						"placeofbirth" 			=> $post['placeofbirth_edit'],
						"age" 					=> $post['age_edit'],
						"address" 				=> $post['address_edit'],
						"address_2"				=> $post['address_edit_2'],
						"city" 					=> $post['city_edit'],
						"state" 				=> $post['state_edit'],
						"zip" 					=> $post['zip_edit'],
						"sc_id"					=> $post['sc_ID_edit'],
						"tin"					=> $post['tin'],
						"secondary_address" 	=> $post['secondary_address_edit'],
						"secondary_address_2"	=> $post['secondary_address_edit_2'],
						"secondary_city" 		=> $post['secondary_city_edit'],
						"secondary_zip" 		=> $post['secondary_zip_edit'],
						"email_address" 		=> $post['email_edit'],
						"civil_status" 			=> $post['status_edit'],
						"relationship" 			=> $post['relationship_edit'],
						"dominant_hand" 		=> $post['hand_edit'],
						"work_status" 			=> $post['work_edit'],
						"contact_name" 			=> $post['contact_name_edit'],
						"contact_relation" 		=> $post['contact_relation_edit'],
						"contact_address" 		=> $post['contact_address_edit'],
						"contact_email_address" => $post['contact_email_edit'],
						"representative_name" 	=> $post['representative_name_edit'],
						"representative_relation" => $post['representative_relation_edit'],
						"representative_mobile"   => $post['representative_mobile_edit'],
						"representative_email" 	=> $post['representative_email_edit'],
						"status"				=> "0",
						"last_update_by" 		=> $user_id,
						// "last_change_password" 	=> $last_update,
					);

					$user_id = Patients::save($record,$post_id);

					foreach($post['contact_information'] as $key2=>$value2):
						$record2 = array(
							"user_id"			=> $user_id,
							"contact_type" 		=> $value2['contact_type'],
							"contact_value" 	=> $value2['contact_type_value'],
							"extension" 		=> $value2['contact_extension'],
							"date_created" 		=> date("Y-m-d H:i:s"),
						);

						User_Contact::save($record2);
					endforeach;

					foreach($post['contact_person'] as $key3=>$value3):
						$record3 = array(
							"patient_id"		=> $user_id,
							"contact_type" 		=> $value3['contact_type'],
							"contact_value" 	=> $value3['contact_type_value'],
							"extension" 		=> $value3['contact_extension'],
							"date_created" 		=> date("Y-m-d H:i:s"),
						);

						Patient_Contact::save($record3);
					endforeach;

					$json['patient_id']		= $user_id;
					$json['is_successful'] 	= true;
					$json['message']		= "Successfully Updated {$name}.";

					/* NOTIFICATIONS */
					/*$json['notif_title'] 	= "Updated " . $name;
					$json['notif_type']		= "info";
					$json['notif_message']	= $session['name'] . " has successfully Updated Patient {$name}.";*/

					//New Notification
					$msg = $session['name'] . " has successfully Updated Patient: {$name}.";

					$this->pusher->trigger('my_notifications', 'notification', array(
						'message' => $msg,
						'title' => "Updated Patient " . $name,
						'type' => 'info'
					));


				} else {
					$json['is_successful'] 	= false;
					$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
				}
				

			} else {

					$file = $_FILES['patient_image'];
					$birthdate = date( "F d Y", strtotime( $post['birthdate'] ) );
					// $appointment = date( "F d Y", strtotime( $post['appointment'] ) );

					// $birthdate = $post['birthdate'];
					//$name = $post['lastname'] . ", " . $post['firstname'];
					$name = $post['lastname'] . ", " . $post['firstname'] ." ". $post['middlename'];
					$record = array(
						"appointment" 		=> $post['appointment'],
						"doc_assigned_id"	=> $post['doctor_assigned'],
						"doc_attending_id"	=> $post['attending_doctor'],
						"patient_code" 		=> $post['patient_code'],
						"patient_name" 		=> $name,
						"lastname" 			=> $post['lastname'],
						"firstname" 		=> $post['firstname'],
						"middlename" 		=> $post['middlename'],
						"gender" 			=> $post['gender'],
						"birthdate" 		=> $birthdate,
						"placeofbirth" 		=> $post['placeofbirth'],
						"age" 				=> $post['age'],
						"address" 			=> $post['address'],
						"address_2"			=> $post['address_2'],
						"city" 				=> $post['city'],
						"state" 			=> $post['state'],
						"zip" 				=> $post['zip'],
						"sc_id"				=> $post['sc_ID'],
						"tin"				=> $post['tin'],
						"secondary_address" => $post['secondary_address'],
						"secondary_address_2"	=> $post['secondary_address_2'],
						"secondary_city" 		=> $post['secondary_city'],
						"secondary_zip" 		=> $post['secondary_zip'],
						"email_address" 	=> $post['email'],
						"civil_status" 		=> $post['status'],
						"relationship" 		=> $post['relationship'],
						"dominant_hand" 	=> $post['hand'],
						"work_status" 		=> $post['work'],
						"contact_name" 		=> $post['contact_name'],
						"contact_relation" 	=> $post['contact_relation'],
						"contact_address" 	=> $post['contact_address'],
						"contact_email_address" => $post['contact_email'],
						"representative_name" => $post['representative_name'],
						"representative_relation" => $post['representative_relation'],
						"representative_mobile" => $post['representative_mobile'],
						"representative_email" => $post['representative_email'],
						"status"			=> "0",
						// "display_image_url"	=> $filename,
						"date_created" 		=> date("Y-m-d H:i:s",time()),
						"last_update_by" 	=> $user_id,
					);

					$user_id = Patients::save($record);

					foreach($post['contact_information'] as $key2=>$value2):
						$record2 = array(
							"user_id"			=> $user_id,
							"contact_type" 		=> $value2['contact_type'],
							"contact_value" 	=> $value2['contact_type_value'],
							"extension" 		=> $value2['contact_extension'],
							"date_created" 		=> date("Y-m-d H:i:s"),
						);

						User_Contact::save($record2);
					endforeach;

					foreach($post['contact_person'] as $key3=>$value3):
						$record3 = array(
							"patient_id"		=> $user_id,
							"contact_type" 		=> $value2['contact_type'],
							"contact_value" 	=> $value2['contact_type_value'],
							"extension" 		=> $value2['contact_extension'],
							"date_created" 		=> date("Y-m-d H:i:s"),
						);

						Patient_Contact::save($record3);
					endforeach;

					if($file){
						$this->load->helper('string');
						$random_key = random_string('unique');

						$file_extension	= substr($file['name'], -3);
						$raw_name 		= $this->encrypt->sha1(basename($file['name']."$random_key")).date("hims",time());
						$upload_name 	= $file['name'];
						$file_size 		= $file['size'];

						$dir = "files/photos/tmp/" . $post['patient_code'] . "/";
						if(!is_dir($dir)) {
							mkdir($dir,0777,true);
						}

						$path = "$dir/$raw_name.{$file_extension}";
							
						if(move_uploaded_file($file['tmp_name'], $path)) {

							$arr = array(
								"patient_id" => $user_id,
								"patient_code" => $post['patient_code'],
								"upload_filename" => $upload_name,
								"filename" => $raw_name,
								"extension" => $file_extension,
								"size" => $file_size,
								"base_path" => $dir,
								"date_created" => date("Y-m-d H:i:s"),
							);

							Patient_Photos::save($arr);
						}
					}

					$json['patient_id']		= $user_id;
					$json['is_successful'] 	= true;
					$json['message']		= "Successful Added {$name} to patient database!";

					/* NOTIFICATIONS */
					/*$json['notif_title'] 	= "Added " . $name;
					$json['notif_type']		= "info";
					$json['notif_message']	= $session['name'] . " has successful Added Patient {$name}.";*/

					//New Notification
					$msg = $session['name'] . " has successfully Added Patient: {$name}.";

					$this->pusher->trigger('my_notifications', 'notification', array(
						'message' => $msg,
						'title' => "Added Patient " . $name,
						'type' => 'info'
					));
				
			}

		echo json_encode($json);
	}

	/* DELETE ROW FOR DISEASE*/
	function delete_disease(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			Disease::delete($post['medical_history_id']);
		}
	}

	function delete_personal_disease(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			Personal_Disease::delete($post['medical_history_id']);
		}
	}

	/* END OF DELETE ROW FOR DISEASE*/

	function getAllPatientsList() {
		Engine::XmlHttpRequestOnly();

		$get 			= $this->input->get();
		$session 		= $this->session->all_userdata();
		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$pm_pi			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			// $order_type	= strtoupper($get['sSortDir_0']);
			$order_type	= strtoupper("desc");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "id",
				1 => "patient_code",
				2 => "patient_name",
				3 => "appointment",
				4 => "gender",
				5 => "birthdate",
				6 => "placeofbirth",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 15";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$patients = Patients::generatePatientDatatable($params);
			$total_records 	= Patients::countPatientDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($patients as $key=>$value):

				if($pm_pi['can_update'] && $pm_pi['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_patient(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_patient('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if($pm_pi['can_update'] && !$pm_pi['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_patient(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					';
				}else if(!$pm_pi['can_update'] && $pm_pi['can_delete']){
					$action_link = '
						<a href="javascript:void(0);" onclick="javascript: delete_patient('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				} else {
					$action_link = '';
				}

				if($pm_pi['can_view']){
					$view_link = '<a href="javascript: void(0);" onclick="javascript: view_patient(' . $value['id'] . ');">' . $value['patient_name'] . '</a>';
				}else {
					$view_link = $value['patient_name'];
				}
				
				
				$row = array(
					
					'0' => $value['patient_code'],
					'1' => $view_link,
					'2' => $value['appointment'],
					'3' => $value['gender'],
					'4' => $value['birthdate'],
					'5' => $value['placeofbirth'],
					'6' => $action_link,
					// '7' => $value['status'],
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

	/*function test(){
		// $birthdate = date( "M d Y", strtotime( "2014-14-03" ) );

		// format must be Y-m-d
		// convert to any format
		$birthdate = date( "Y-m-d", strtotime( "Apr 24 1993" ) );
		$appointment = date( "F d Y", strtotime( "2014-03-07" ) );
		// echo date( "M d Y", strtotime( "2014-03-24" ) );
		echo $birthdate;
		echo $appointment;
	}*/

	/* CONTACTS FOR PATIENTS */

	function contact_person_list() {
		Engine::XmlHttpRequestOnly();
		$post 					= $this->input->post();
		$patient_id 			= (int) $post['patient_id'];
		$contact_person 		= Patient_Contact::findContactsById(array("id"=>$patient_id));
		$view_only				= $post['val'];
		if($post && $patient_id && $contact_person) {
			$data['patient_id'] 			= $patient_id;
			$data['view_only']				= $view_only;
			$data['contact_person'] 		= $contact_person;
			$this->load->view('management/patient/contact_person_list',$data);
		}
	}

	function edit_contact_person_form() {
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['id']			= $id = (int) $post['id'];
		$data['patient_id']	= $patient_id 	= (int) $post['patient_id'];
		$data['contact'] 	= $contact = Patient_Contact::findContactByPatientId(array("patient_id"=>$patient_id, "id" => $id));
		if($contact){
			$this->load->view('management/patient/forms/edit_contact_person',$data);
		}
		
	}

	function save_contact_person(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$record = array(
			"contact_type"  => $post['contact_type'],
			"contact_value" => $post['contact_value'],
			"extension" 	=> $post['extension'],
		);
		Patient_Contact::save($record, $post['id']);
	}

	function delete_contact_person_form() {
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();

		$data['id']			= $id = (int) $post['id'];
		$data['patient_id']	= $patient_id 	= (int) $post['patient_id'];
		$data['contact'] 	= $contact = Patient_Contact::findContactByPatientId(array("patient_id"=>$patient_id, "id" => $id));
		$this->load->view('management/patient/forms/delete_contact_person',$data);
	}

	function delete_contact_person(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$id = (int) $post['id'];
		Patient_Contact::delete($id);
	}

	/* END OF CONTACTS */

	function getDiseaseVariablesFields() {
	  $post = $this->input->post();

	  if($post) {
	   $index = (int) $post['index'];
	   $disease_id = (int) $post['id'];

	   $div_wrapper = "field_wrapper_{$index}";
	   $field_relation = "field[{$index}][relation]";
	   $field_age = "field[{$index}][age]";
	   $field_category = "field[{$index}][category]";

	   $disease_category = Disease_Type::findAllbyDiseaseId(array("id" => $disease_id, "status" => 'Active'));
		   

		   $object['html'] .= "
			<ul class='disease_ul {$div_wrapper}'>
					<li class='firstTd' style='width: 216px;'><select class='select' id='{$field_category}' name='{$field_category}' style='width: 180px;'><option value=''>- Select -</option>
					";

					foreach($disease_category as $key=>$value):
						$object['html'] .= " 
							<option value='".$value['id']."'> " . $value['type_name'] . " </option>
						";
					endforeach;

			$object['html'] .= "
					</select></li>
					<li class='middleTd' style='width: 122px;'>
						<input type='text' id='{$field_relation}' name='{$field_relation}' class='textbox'>
					</li>
					<li class='middleTd' style='width: 122px;'>
						<input type='text' id='{$field_age}' name='{$field_age}' style='width:80px;' class='textbox'>
					</li>
					<li class='lastTd'>
						<a href='javascript: void(0);' onclick=\"javascript: delete_disease_element('{$div_wrapper}');\"><span class='glyphicon glyphicon-ban-circle'></span></a>
					</li>
				</ul>
			";
		   
	  }


	  echo json_encode($object);
	 }


	 function getPersonalDiseaseVariablesFields() {
	  $post = $this->input->post();

	  if($post) {
	   $index = (int) $post['index'];
	   $disease_id = (int) $post['id'];

	   $div_wrapper = "field_wrapper_{$index}";
	   $field_relation = "field[{$index}][relation]";
	   $field_age = "field[{$index}][age]";
	   $field_category = "field[{$index}][category]";

	   $disease_category = Disease_Type::findAllbyDiseaseId(array("id" => $disease_id, "status" => 'Active'));
		   

		   $object['html'] .= "
			<ul class='disease_ul {$div_wrapper}'>
					<li class='firstTd' style='width: 216px;'><select class='select' id='{$field_category}' name='{$field_category}' style='width: 180px;'><option value=''>- Select -</option>
					";

					foreach($disease_category as $key=>$value):
						$object['html'] .= " 
							<option value='".$value['id']."'> " . $value['type_name'] . " </option>
						";
					endforeach;

			$object['html'] .= "
					</select></li>
					<li class='middleTd' style='width: 122px;'>
						<input type='text' id='{$field_age}' name='{$field_age}' style='width:80px;' class='textbox'>
					</li>
					<li class='lastTd'>
						<a href='javascript: void(0);' onclick=\"javascript: delete_disease_element('{$div_wrapper}');\"><span class='glyphicon glyphicon-ban-circle'></span></a>
					</li>
				</ul>
			";
		   
	  }


	  echo json_encode($object);
	 }

	 function upload_photo_form() {
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		if($session && $post) {
			$data['patient_id'] = $post['patient_id'];
			$data['header_category'] = $post['header_category'];
			$this->load->view('management/patient/forms/upload_photo',$data);
		}
	}

	function uploaded_file_list() {
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$patient_id = $post['patient_id'];
		$header_category = $post['header_category'];
		$data['viewing_page'] = $viewing_page = $post['viewing_page'];
		$data['files'] = Medical_Photos::findAllByPatientIdCategory(array("patient_id" => $patient_id, "header_category" => $header_category));
		$this->load->view('management/patient/forms/uploaded_file_list',$data);
	}

	function upload_disease_photo() {
		$success 	= false;

		$file 		= $_FILES['file'];
		$session 	= $this->session->userdata('user_id');
		$decode 	= $this->encrypt->decode($session);
		$user 		= User::findById(array("id"=>$decode));
		$get 		= $this->input->get();
		$patient_id = $get['patient_id'];
		$header_category = $get['header_category'];
		if($file) {
	
			$this->load->helper('string');
			$random_key = random_string('unique');

			$file_extension	= substr($file['name'], -3);
			$raw_name 		= $this->encrypt->sha1(basename($file['name']."$random_key")).date("hims",time());
			$upload_name 	= $file['name'];
			$file_size 		= $file['size'];

			$dir = "files/photos/tmp/";
			if(!is_dir($dir)) {
				mkdir($dir,0777,true);
			}

			$path = "$dir/$raw_name.{$file_extension}";
				
			if(move_uploaded_file($file['tmp_name'], $path)) {
				// $arr = array(
				// 	"base_dir" 		=> $dir,
				// 	"raw_name" 		=> $raw_name.".{$file_extension}",
				// 	"upload_name" 	=> $upload_name,
				// 	"size" 			=> $file_size,
				// 	"date_created" 	=> date("Y-m-d H:i:s",time()),
				// );
				// $tmp_images = $this->session->set_userdata('tmp_images', $arr);

				$arr = array(
					"patient_id" => $patient_id,
					"header_category" => $header_category,
					"upload_filename" => $upload_name,
					"filename" => $raw_name,
					"extension" => $file_extension,
					"size" => $file_size,
					"base_path" => $dir,
					"date_created" => date("Y-m-d H:i:s",time()),
					"last_update_by" => $user,
				);

				Medical_Photos::save($arr);
			}
		}
	}

	function delete_photo() {
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post) {
			$post_id = (int) $post['id'];


			$photo = Medical_Photos::findById(array("id" => $post_id));
			if($photo){
				Medical_Photos::delete($post_id);
			}
			unlink($photo['base_path'].$photo['filename'].".".$photo['extension']);
		}
	}

	function download_stockHistory(){

		$patient_id = $this->uri->segment(3);
		if($patient_id){
			$stocks = Stock::findAllByPatientId(array("patient_id" => $patient_id));
			$patient = Patients::findbyId(array("id" => $patient_id));
			$data['filename'] = $patient['patient_code'] . date('Ymd');
			$stock_array[] = array("Medicine Name", "Quantity", "Reason", "When");

			foreach ($stocks as $key => $value) {
				$stock_array[] = array(
					$value['medicine_name'],
					$value['quantity'],
					$value['reason'],
					Tool::humanTiming(strtotime($value['created_at'])) . " ago",
				);
			}

			$data['stock_array'] = $stock_array;
			$this->load->view('management/excel_stock',$data);	
		}
		
	}


	function openCreditSystem(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$data['patient_id'] = $id = $post['patient_id'];
			$data['patient'] 	= Patients::findbyId(array("id" => $id));
			$session = $this->session->all_userdata();
			$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
			$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));

			$data['cr'] = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 25));
			$this->load->view("management/patient/credit/index", $data);
		} else {
			echo "<h4> Error 404: Page Not Found </h4>";
		}
	}

	function manageCredit(){
		Engine::XmlHttpRequestOnly();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$post 		= $this->input->post();
		$patient_id = $post['patient_id'];
		$patient 	= Patients::findbyId(array("id" => $patient_id));
		if($patient){

			$credit 	= $post['credit'];
			$remarks	= $post['remarks'];
			$type 		= $post['type'];

			if($type == "add"){
				$record = array(
					"credit" => $patient['credit'] + $credit,
					"last_update_by" => $session_id,
					"updated_at" => date("Y-m-d H:i:s"),
					);
			} else {
				$record = array(
					"credit" => $patient['credit'] - $credit,
					"last_update_by" => $session_id,
					"updated_at" => date("Y-m-d H:i:s"),
					);
			}

			Patients::save($record, $patient_id);


			$history = array(
					"patient_id" 	=> $patient_id,
					"credit" 		=> $credit,
					"remarks" 		=> $remarks,
					"type" 			=> $type,
					"date_created" 	=> date("Y-m-d H:i:s"),
					"created_by" 	=> $session_id,
					);

			Patients_Credit_History::save($history);

			echo "<h4> Successfully Saved to database. </h4>";
		} else {
			echo "<h4> Error Saving to Database! Please Try Again Later! </h4>";
		}
	}

	function loadCreditsHistory(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$patient_id = $post['patient_id'];
		$patient 	= Patients::findbyId(array("id" => $patient_id));
		if($patient){
			$data['history'] = $history = Patients_Credit_History::findAllByPatientid(array("patient_id" => $patient_id));
			$this->load->view("management/patient/credit/history", $data);
	 	} else {
	 		echo "<h4> Error 404: Page Not Found </h4>";
	 	}
	}

	function loadCredits(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$patient_id = $post['patient_id'];
		$patient 	= Patients::findbyId(array("id" => $patient_id));
		if($patient){
			echo $patient['credit'];
	 	} else {
	 		echo "<h4> Error 404: Page Not Found </h4>";
	 	}
	}

	function fixAllAppointmentDate(){
		$patients = Patients::findAll();
		foreach ($patients as $key => $value) {

			$oldate = $value['appointment'];
			$date = date("Y-m-d", strtotime($oldate));
			$test = array(
					"appointment" => $date,
				);

			Patients::save($test, $value['id']);
		}
	}

	function generateCode(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		if($post){
			$params = array(
			"firstname"  => $post['x'],
			"lastname"	 => $post['y'],
			);
			$fname = substr($params['firstname'], 0, 3);
			$lname = substr($params['lastname'], 0,3);

		$total_patients = Patients::countPatientId();

		$next_id = ($total_patients <= 0 ? 1 : $total_patients + 1);
		$patientcode = $lname . "" . $fname . "" . str_pad($next_id, 4, "0",STR_PAD_LEFT);
		$data['patientcode'] = $patientcode;
		$this->load->view('management/patient/forms/patientcode',$data);
			
		}
	}

	/* FILE MANAGER */
	function view_files_list(){
		Engine::XmlHttpRequestOnly();
		$post 			 = $this->input->post();
		$patient_id 	 = $post['id'];
		$data['patient'] = $patient = Patients::findById(array("id" => $patient_id));
		if($patient){
			$data['patient_image'] 	= Patient_Photos::findbyId(array("patient_id" => $patient_id));
			$data['categories'] = $categories = File_Category::findAllActive();

			$session = $this->session->all_userdata();
			$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
			$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
			$data['pm_pf']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 30));

			unset($_SESSION['uploaded_file_ids']);
			$this->load->view("management/patient/file_manager/file_list", $data);
		}
	}

	function upload_form(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['patient_id'] = $post['patient_id'];
		$data['categories'] = $categories = File_Category::findAllActive();
		$this->load->view("management/patient/file_manager/forms/modal_file_upload", $data);
	}

	function postUploadManager(){
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$get 		= $this->input->get();

		$file 		= $_FILES['file'];
		$patient_id = $get['patient_id'];

		if(isset($file)){
			$this->load->helper('string');
			$random_key = random_string('unique');

			$patient = Patients::findById(array("id" => $patient_id));
			$file_extension	= substr($file['name'], -3);
			$raw_name 		= $this->encrypt->sha1(basename($file['name']."$random_key")).date("hims",time());
			$upload_name 	= $file['name'];
			$file_size 		= $file['size'];

			$dir = "files/patient/files/" . trim($patient['patient_code']);
			if(!is_dir($dir)) {
				mkdir($dir,0777,true);
			}

			$path = "$dir/$raw_name.{$file_extension}";
				
			if(move_uploaded_file($file['tmp_name'], $path)) {

				$arr = array(
					"patient_id" 		=> $patient_id,
					"category_id" 		=> 1,
					"title" 			=> $upload_name,
					"description" 		=> "n/a",
					"upload_filename"	=> $upload_name,
					"filename" 			=> $raw_name,
					"extension" 		=> $file_extension,
					"size" 				=> $file_size,
					"base_path" 		=> $dir,
					"date_created" 		=> date("Y-m-d H:i:s",time()),
					"date_updated" 		=> date("Y-m-d H:i:s",time()),
					"created_by" 		=> $session_id,
					"last_update_by" 	=> $session_id,
				);

				$file_id = Patient_Files::save($arr);

				$this->handleSessionFileUploads($file_id);
			}	
		}
	}

	function handleSessionFileUploads($id){
		$handler = $_SESSION['uploaded_file_ids'];
		$handler[] = $id;
		$_SESSION['uploaded_file_ids'] = $handler;
	}

	function loadPageForEditFiles(){
		Engine::XmlHttpRequestOnly();
		$file_ids = $_SESSION['uploaded_file_ids']; // array

		foreach ($file_ids as $key => $value) {
			$file = Patient_Files::findById(array("id" => $value));
			if($file){
				$files[] = $file;
			}	
		}

		$data['files'] = $files;
		$data['categories'] = $categories = File_Category::findAllActive();
		$this->load->view("management/patient/file_manager/forms/modal_uploaded_files", $data);
	}

	function loadPageForViewFiles(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		// $post = $this->uri->segment(3);
		// debug_array($post);
		if($post){
			$data['file'] = $file = Patient_Files::findById(array("id" => $post['id']));
			// $data['file'] = $file = Patient_Files::findById(array("id" => $post));
		}
		$this->load->view("management/patient/file_manager/forms/modal_view_files", $data);
	}

	function loadPageForEditFile(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		if($post){
			$data['categories'] = $categories = File_Category::findAllActive();
			$data['file'] = $file = Patient_Files::findById(array("id" => $post['id']));
		}
		$this->load->view("management/patient/file_manager/forms/modal_edit_files", $data);
	}

	function updateFiles(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$user = $this->session->all_userdata();

		if($post){
			foreach ($post['files'] as $key => $value) {
				$array = array(
					"category_id" 	=> $value['category_id'],
					"title"		  	=> $value['title'],
					"description" 	=> $value['description'],
					"date_updated"	=> date("Y-m-d H:i:s",time()),
					"last_update_by"=> $session_id
					);

				Patient_Files::save($array,$value['id']);
			}

			$json['is_successful'] 	= true;
			$json['message']		= "Successfully updated files.";

			//New Notification
			$msg = $user['name'] . " has successfully updated files";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Updated files",
				'type' => 'info'
			));
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Error updating files. Please contact your administrator.";
		}

		echo json_encode($json);
	}

	function fileUpdate(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$user = $this->session->all_userdata();

		if($post){
			$array = array(
				"category_id" 	=> $post['category_id'],
				"title"		  	=> $post['title'],
				"description" 	=> $post['description'],
				"date_updated"	=> date("Y-m-d H:i:s",time()),
				"last_update_by"=> $session_id
				);

			Patient_Files::save($array,$post['id']);

			/* ACTIVITY TRACKER */
			$act_tracker = array(
				"module"		=> "rpc_file_manager",
				"user_id"		=> $session_id,
				"entity_id"		=> $id,
				"message_log" 	=> "Patient: " . $patient['patient_name'] . " File: " . $post['title'] . " has been updated.",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message']		= "Successfully updated files.";

			//New Notification
			$msg = $user['name'] . " has successfully Updated File : {$post['title']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Updated Patient File " . $patient['patient_name'],
				'type' => 'info'
			));
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Error updating files. Please contact your administrator.";
		}

		echo json_encode($json);
	}

	function deleteFileUpload(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$id 	= $post['id'];
		$file = Patient_Files::findbyId(array("id"=>$id));
		if($file){

			$patient = Patients::findbyId(array("id"=>$file['patient_id']));
			/* ACTIVITY TRACKER */
			$act_tracker = array(
				"module"		=> "rpc_file_manager",
				"user_id"		=> $session_id,
				"entity_id"		=> $id,
				"message_log" 	=> "Patient: " . $patient['patient_name'] . " File: " . $file['title'] . " has been deleted.",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);
			unlink($file['base_path'] . "/" . $file['filename'] . "." . $file['extension']);
			Patient_Files::delete($id);
		}
	}

	function viewNotes(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$session 		= $this->session->all_userdata();
		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['pm_pi']  = $pm_pi			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));

		$patient_id 	= $post['patient_id'];


		if($patient_id){
			$data['patient_notes'] =$patient_notes	= Patient_Notes::findAllByPatientId(array("patient_id" => $patient_id));
			$data['patient'] 	   = $patient       = Patients::findById(array("id" => $patient_id));
		}
	
		$this->load->view("management/patient/notes/view_notes", $data);
	}

	function addNotes(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$data['patient_id'] = $patient_id = $post['patient_id'];
		$this->load->view("management/patient/notes/add_notes", $data);
	}

	function savePatientNotes(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$user = User::findById(array("id" => $this->encrypt->decode($session)));
		$users = $this->session->all_userdata();
	//	debug_array($post);
		if($post){
			
			if($post['notes_id']){
				//EDIT
				$record = array(
					"notes"		  	=> $post['edit_notes'],
					"date_created"	=> date("Y-m-d H:i:s"),
					"created_by"    => $session_id
					);
				Patient_Notes::save($record,$post['notes_id']);

				$patient = Patients::findbyId(array("id"=>$post['patient_id']));
				
				$json['is_successful'] 	= true;
				$json['patient_id'] 	= $post['patient_id'];
				$json['message'] 		= "Successfully Updated Notes to Patient: {$patient['patient_name']} in database";
				
				$act_tracker = array(
					"module"		=> "rpc_patient",
					"user_id"		=> $session_id,
					"entity_id"		=> $post['patient_id'],
					"message_log" 	=> $user['lastname'] .",". $user['firstname'] ." ". "Successfully Updated Notes in Patient: {$patient['patient_name']}",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				
				Activity_Tracker::save($act_tracker);

				/*NOTIFICATIONS*/
				//$user = User::findById(array("id" => $user_id));
				
				/*$json['notif_title'] 	= "Added Notes " . $patient['patient_name'];
				$json['notif_type']		= "info";
				$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has updated notes to Patient: {$patient['patient_name']}";*/
			
				//echo "<h4> Successfully Saved to database. </h4>";
				//echo json_encode($json);

				//New Notification
				$msg = $users['name'] . " has successfully updated notes to : {$patient['patient_name']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Patient Notes " . $patient['patient_name'],
					'type' => 'info'
				));

			}else{
				//ADD
				$record = array(
					"patient_id" 	=> $post['patient_id'],
					"notes"		  	=> $post['notes'],
					"date_created"	=> date("Y-m-d H:i:s"),
					"created_by"    => $session_id
					);
				Patient_Notes::save($record);

				$patient = Patients::findbyId(array("id"=>$post['patient_id']));
				
				
				$json['is_successful'] 	= true;
				$json['patient_id'] 	= $post['patient_id'];
				$json['message'] 		= "Successfully Added Notes to Patient: {$patient['patient_name']} in database";

				$act_tracker = array(
					"module"		=> "rpc_patient",
					"user_id"		=> $session_id,
					"entity_id"		=> $post['patient_id'],
					"message_log" 	=> $user['lastname'] .",". $user['firstname'] ." ". "Successfully Added Notes in Patient: {$patient['patient_name']}",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				
				Activity_Tracker::save($act_tracker);

				/*NOTIFICATIONS*/
				//$user = User::findById(array("id" => $user_id));
				
				/*$json['notif_title'] 	= "Added Notes " . $patient['patient_name'];
				$json['notif_type']		= "info";
				$json['notif_message']	= $user['lastname'] . ", " . $user['firstname'] . " has added notes to Patient: {$patient['patient_name']}";*/

				//New Notification
				$msg = $users['name'] . " has successfully updated notes to : {$patient['patient_name']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Patient Notes " . $patient['patient_name'],
					'type' => 'info'
				));
				
				}
			
		/*} else {
			$json['message'] 		= "Error Updating in database";
			$json['is_successful'] = false;
		}*/
			echo "<h4> Successfully Saved to database. </h4>";
		} else {
			echo "<h4> Error Saving to Database! Please Try Again Later! </h4>";
		}
		//echo json_encode($json);

	}

	function editNotes(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$data['patient_notes'] = $patient_notes	= Patient_Notes::findById(array("id" => $post['id']));
		//debug_array($patient_notes);
		$this->load->view("management/patient/notes/edit_notes", $data);
	}

	function deleteNotes(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$user = User::findById(array("id" => $this->encrypt->decode($session)));
		$id 	= $post['id'];
		
		$notes = Patient_Notes::findById(array("id"=>$id));
		if($notes){

			$patient = Patients::findById(array("id"=>$notes['patient_id']));

			/* ACTIVITY TRACKER */
			$act_tracker = array(
					"module"		=> "rpc_patient",
					"user_id"		=> $session_id,
					"entity_id"		=> $notes['patient_id'],
					"message_log" 	=> $user['lastname'] .",". $user['firstname'] ." ". "Successfully deleted Notes in Patient: {$patient['patient_name']}",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);

			Activity_Tracker::save($act_tracker);
			
			Patient_Notes::delete($id);
		}
	}

	function loadNotes(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$patient_id 	= $post['patient_id'];

		if($patient_id){
			$data['patient_notes'] =$patient_notes	= Patient_Notes::findAllByPatientId(array("patient_id" => $patient_id));
			$data['patient'] 	   = $patient       = Patients::findById(array("id" => $patient_id));

			$this->load->view("management/patient/notes/view", $data);
	 	} else {
	 		echo "<h4> Error 404: Page Not Found </h4>";
	 	}
	}

	//PATIENT LABTEST

	function viewDashboard(){
		Engine::XmlHttpRequestOnly();
		$data['id'] = $post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();
		$success = false;

		$data['patient'] = Patients::findById(array("id" => $post['id']));
		$data['imaging_test'] = $imaging_test = Patient_Labtest::findAllImagingTestByPatientId(array("id" => $post['id']));
		$data['laboratory_test'] = $laboratory_test = Patient_Labtest::findAllByPatientIdAndLabtest(array("id" => $post['id']));
		$data['lab_test']= Patient_Laboratory_Test::findAll();

		$this->load->view('management/patient/labtest/patient_dashboard',$data);
	}

	function addLabtest(){
		Engine::XmlHttpRequestOnly();
		$data['id'] = $post = $this->input->post();
		$data['session'] 			= $session = $this->session->all_userdata();
		$success = false;
		$data['doctors']			= Doctors::findAllActive();

		$data['patient'] 			= $patient = Patients::findById(array("id" => $post['id']));
		$data['laboratory_test']	= $laboratory_test = Patient_Laboratory_Test::findAll();
		$data['imaging_test']		= $imaging_test = Patient_Imaging_Laboratory_Test::findAll();

		if($patient['gender'] == 'Male'){
			$this->load->view('management/patient/labtest/forms/male_add_labtest',$data);
		}else{
			$this->load->view('management/patient/labtest/forms/female_add_labtest',$data);
		}		
	}

	function compareLabtest(){
		Engine::XmlHttpRequestOnly();
		$data['id'] = $post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();
		$success = false;
		$data['doctors']	= Doctors::findAllActive();
		$data['patient'] = $patient = Patients::findById(array("id" => $post['id']));
		
		$this->load->view('management/patient/labtest/compare_labtest',$data);
	}

	function editLabtest(){
		Engine::XmlHttpRequestOnly();
		$data['id'] = $post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();
		$success = false;
		$data['patient_id'] = $post['patient_id'];
		$data['patient'] 	= $patient = Patients::findById(array("id" => $post['patient_id']));
		$data['doctors']	= Doctors::findAllActive();
		$data['labtest']    = $labtest = Patient_Labtest::findById(array("id" => $post['id']));
		$data['laboratory_test_name'] = Patient_Laboratory_Test::findByName(array("category_value" => $labtest['category']));

		$data['category'] = $labtest['category'];
		
		if($labtest['category'] == 'urinalysis'){
			$data['data_labtest'] = Patient_Labtest_Urinalysis::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'urine_chemistry') {
			$data['data_labtest'] = Patient_Labtest_Urine_Chemistry::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'coagulation_factor') {
			$data['data_labtest'] = Patient_Labtest_Coagulation_Factor::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'coagulation') {
			$data['data_labtest'] = Patient_Labtest_Coagulation::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'hematology') {
			$data['data_labtest'] = Patient_Labtest_Hematology::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'biochemistry') {
			$data['data_labtest'] = Patient_Labtest_Biochemistry::findByLabtestId(array("id" => $labtest['id']));
			$data['data_labtest_2'] = Patient_Labtest_Biochemistry_2::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'oral_glucose_challenge') {
			$data['data_labtest'] = Patient_Labtest_Oral_Glucose_Challenge::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'oral_glucose_tolerance') {
			$data['data_labtest'] = Patient_Labtest_Oral_Glucose_Tolerance::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'thyroid') {
			$data['data_labtest'] = Patient_Labtest_Thyroid::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'hormones') {
			$data['data_labtest'] = Patient_Labtest_Hormones_Test::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'homeostasis_ass_index') {
			$data['data_labtest'] = Patient_Labtest_Homeostasis_Assessment_Index::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'serology') {
			$data['data_labtest'] = Patient_Labtest_Serology_Immunology::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'tumor_markers') {
			$data['data_labtest'] = Patient_Labtest_Tumor_Markers::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'special_chem') {
			$data['data_labtest'] = Patient_Labtest_Special_Chemistry::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'vita_nutri') {
			$data['data_labtest'] = Patient_Labtest_Vitamins_Nutrition::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'viral_hepa') {
			$data['data_labtest'] = Patient_Labtest_Viral_Hepatitis::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'hiv') {
			$data['data_labtest'] = Patient_Labtest_Hiv::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'eGFR') {
			$data['data_labtest'] = Patient_Labtest_Egfr::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'nutri_elements') {
			$data['data_labtest'] = Patient_Labtest_Nutrients_Elements::findByLabtestId(array("id" => $labtest['id']));
		}
		
		
		if($patient['gender'] == 'Male'){
			$this->load->view('management/patient/labtest/forms/male_edit_labtest',$data);
		}else{
			$this->load->view('management/patient/labtest/forms/female_edit_labtest',$data);
		}
		
	}
	
	function viewImagingtest(){
		Engine::XmlHttpRequestOnly();
		$data['id'] = $post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();
		$success = false;
		$data['patient_id'] = $post['patient_id'];
		$data['patient'] 	= $patient = Patients::findById(array("id" => $post['patient_id']));
		
		$data['labtest']    = $labtest = Patient_Labtest::findById(array("id" => $post['id']));
		$data['doctors']	= $doctors = Doctors::findById(array("id" => $labtest['requesting_physician']));

		$data['category'] = $labtest['category'];
		
		$data['data_labtest'] = Patient_Labtest_Xray::findByLabtestId(array("id" => $labtest['id']));
		
		$this->load->view('management/patient/labtest/view_imagingtest',$data);
	}

	function viewLabtest(){
		Engine::XmlHttpRequestOnly();
		$data['id'] = $post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();
		$success = false;
		$data['patient_id'] = $post['patient_id'];
		$data['patient'] 	= $patient = Patients::findById(array("id" => $post['patient_id']));
		
		$data['labtest']    = $labtest = Patient_Labtest::findById(array("id" => $post['id']));
		$data['doctors']	= $doctors = Doctors::findById(array("id" => $labtest['requesting_physician']));

		$data['category'] = $labtest['category'];


		if($labtest['category'] == 'urinalysis'){
			$data['data_labtest'] = Patient_Labtest_Urinalysis::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'urine_chemistry') {
			$data['data_labtest'] = Patient_Labtest_Urine_Chemistry::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'coagulation_factor') {
			$data['data_labtest'] = Patient_Labtest_Coagulation_Factor::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'coagulation') {
			$data['data_labtest'] = Patient_Labtest_Coagulation::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'hematology') {
			$data['data_labtest'] = Patient_Labtest_Hematology::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'biochemistry') {
			$data['data_labtest'] = Patient_Labtest_Biochemistry::findByLabtestId(array("id" => $labtest['id']));
			$data['data_labtest_2'] = Patient_Labtest_Biochemistry_2::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'oral_glucose_challenge') {
			$data['data_labtest'] = Patient_Labtest_Oral_Glucose_Challenge::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'oral_glucose_tolerance') {
			$data['data_labtest'] = Patient_Labtest_Oral_Glucose_Tolerance::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'thyroid') {
			$data['data_labtest'] = Patient_Labtest_Thyroid::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'hormones') {
			$data['data_labtest'] = Patient_Labtest_Hormones_Test::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'homeostasis_ass_index') {
			$data['data_labtest'] = Patient_Labtest_Homeostasis_Assessment_Index::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'serology') {
			$data['data_labtest'] = Patient_Labtest_Serology_Immunology::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'tumor_markers') {
			$data['data_labtest'] = Patient_Labtest_Tumor_Markers::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'special_chem') {
			$data['data_labtest'] = Patient_Labtest_Special_Chemistry::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'vita_nutri') {
			$data['data_labtest'] = Patient_Labtest_Vitamins_Nutrition::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'viral_hepa') {
			$data['data_labtest'] = Patient_Labtest_Viral_Hepatitis::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'hiv') {
			$data['data_labtest'] = Patient_Labtest_Hiv::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'eGFR') {
			$data['data_labtest'] = Patient_Labtest_Egfr::findByLabtestId(array("id" => $labtest['id']));
		}elseif ($labtest['category'] == 'nutri_elements') {
			$data['data_labtest'] = Patient_Labtest_Nutrients_Elements::findByLabtestId(array("id" => $labtest['id']));
		}
		
		
		if($patient['gender'] == 'Male'){
			$this->load->view('management/patient/labtest/male_view_labtest',$data);
		}else{
			$this->load->view('management/patient/labtest/female_view_labtest',$data);
		}
	}

	function viewAllLabTest(){
		Engine::XmlHttpRequestOnly();
		$data['id'] = $post = $this->input->post();
		$data['session'] 	= $session = $this->session->all_userdata();
		$success = false;
		$data['patient'] = $patient = Patients::findById(array("id" => $post['id']));

		$data['labtest'] = $labtests = Patient_Labtest::findAllByPatientIdAndLabtest(array("id" => $patient['id']));
		$data['laboratory_names'] = $laboratory_names = Patient_Laboratory_Test::findAll();

		$data['imaging_test'] = Patient_Labtest::findAllImagingTestByPatientId(array("id" => $patient['id']));
		$data['imaging_names'] = Patient_Imaging_Laboratory_Test::findAll();

		$this->load->view('management/patient/labtest/view_all_test',$data);
	}

	function add_new_labtest(){
		$session = $this->session->all_userdata();
		$session_id = $this->session->userdata('user_id');
		$user_id = $this->encrypt->decode($session_id);
		$post = $this->input->post();

		if($post){
			if($post['labtest_id']){
				//EDIT
				$data['labtest']    = $labtest = Patient_Labtest::findById(array("id" => $post['labtest_id']));

				if($labtest['category'] == 'urinalysis'){
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Urinalysis::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"color"		 		=> $post['color'],
						"transparency"   	=> $post['transparency'],
						"specific_gravity" 	=> $post['specific_gravity'],
						"pH"				=> $post['pH'],
						"protein" 			=> $post['protein'],
						"sugar"	  			=> $post['sugar'],
						"bilirubin" 		=> $post['bilirubin'],
						"urobilinogen" 		=> $post['urobilinogen'],
						"ketone" 			=> $post['ketone'],
						"nitrite" 			=> $post['nitrite'],
						"microscopic_rbc"	=> $post['microscopic_rbc'],
						"pus_cell" 			=> $post['pus_cell'],
						"epithelial_cell"   => $post['epithelial_cell'],
						"mucus_threads"		=> $post['mucus_threads'],
						"bacteria" 			=> $post['bacteria'],
						"amorphous_urates" 	=> $post['amorphous_urates'],
						"hyaline"			=> $post['hyaline'],
						"fine_granular"		=> $post['fine_granular'],
						"coarse_granular"	=> $post['coarse_granular'],
						"rbc_cast"			=> $post['rbc_cast'],
						"wbc_cast"			=> $post['wbc_cast'],
						"calcium_oxalates"	=> $post['calcium_oxalates'],
						"uric_acid"			=> $post['uric_acid'],
						"hippuric_acid"		=> $post['hippuric_acid'],
						"date_updated"	    => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Urinalysis::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'urine_chemistry') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Urine_Chemistry::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"microalbumin" 		   => $post['microalbumin'],
						"microalbumin_unit"    => $post['microalbumin_unit'],
						"microalbumin_rf"	   => $post['microalbumin_rf'],
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Urine_Chemistry::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'coagulation_factor') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Coagulation_Factor::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"fibrinogen" 		   => $post['fibrinogen'],
						"fibrinogen_unit"      => $post['fibrinogen_unit'],
						"fibrinogen_rf"	   	   => $post['fibrinogen_rf'],
						"bleeding_time"		   => $post['bleeding_time'],
						"bleeding_time_rf"     => $post['bleeding_time_rf'],
						"clotting_time"		   => $post['clotting_time'],
						"clotting_time_rf"	   => $post['clotting_time_rf'],
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Coagulation_Factor::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'coagulation') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Coagulation::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"prothrombin_time"     		=> $post['prothrombin_time'],
						"prothrombin_time_unit"		=> $post['prothrombin_time_unit'],
						"prothrombin_time_rf"		=> $post['prothrombin_time_rf'],
						"control"					=> $post['control'],
						"control_unit"				=> $post['control_unit'],
						"control_rf"				=> $post['control_rf'],
						"inr"						=> $post['inr'],
						"inr_unit"					=> $post['inr_unit'],
						"inr_rf"					=> $post['inr_rf'],
						"percentage_activity"		=> $post['percentage_activity'],
						"percentage_activity_unit"  => $post['percentage_activity_unit'],
						"percentage_activity_rf"	=> $post['percentage_activity_rf'],
						"activated_partial"			=> $post['activated_partial'],
						"activated_partial_unit"  	=> $post['activated_partial_unit'],
						"activated_partial_rf"		=> $post['activated_partial_rf'],
						"thromboplastin_time"		=> $post['thromboplastin_time'],
						"thromboplastin_time_unit"	=> $post['thromboplastin_time_unit'],
						"thromboplastin_time_rf"	=> $post['thromboplastin_time_rf'],
						"aptt_control"				=> $post['aptt_control'],
						"aptt_control_unit"			=> $post['aptt_control_unit'],
						"aptt_control_rf"			=> $post['aptt_control_rf'],
						"date_updated"	       		=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Coagulation::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'hematology') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Hematology::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"blood_typing_with_rh"		=> $post['blood_typing_with_rh'],
						"blood_type"				=> $post['blood_type'],
						"rh_typing"					=> $post['rh_typing'],
						"reticulocyte_count"		=> $post['reticulocyte_count'],
						"reticulocyte_count_unit"	=> $post['reticulocyte_count_unit'],
						"reticulocyte_count_rf"		=> $post['reticulocyte_count_rf'],
						"rbc"						=> $post['rbc'],
						"rbc_unit"					=> $post['rbc_unit'],
						"rbc_rf"					=> $post['rbc_rf'],
						"hemoglobin"				=> $post['hemoglobin'],
						"hemoglobin_unit"			=> $post['hemoglobin_unit'],
						"hemoglobin_rf"				=> $post['hemoglobin_rf'],
						"hematocrit"				=> $post['hematocrit'],
						"hematocrit_unit"			=> $post['hematocrit_unit'],
						"hematocrit_rf"				=> $post['hematocrit_rf'],
						"mcv"						=> $post['mcv'],
						"mcv_unit"					=> $post['mcv_unit'],
						"mch"						=> $post['mch'],
						"mch_unit"					=> $post['mch_unit'],
						"mch_rf"					=> $post['mch_rf'],
						"mchc"						=> $post['mchc'],
						"mchc_unit"					=> $post['mchc_unit'],
						"mchc_rf"					=> $post['mchc_rf'],
						"wbc"						=> $post['wbc'],
						"wbc_unit"					=> $post['wbc_unit'],
						"wbc_rf"					=> $post['wbc_rf'],
						"granulocytes"				=> $post['granulocytes'],
						"granulocytes_unit"			=> $post['granulocytes_unit'],
						"granulocytes_rf"			=> $post['granulocytes_rf'],
						"lymphocytes"				=> $post['lymphocytes'],
						"lymphocytes_unit"			=> $post['lymphocytes_unit'],
						"lymphocytes_rf"			=> $post['lymphocytes_rf'],
						"monocytes"					=> $post['monocytes'],
						"monocytes_unit"			=> $post['monocytes_unit'],
						"monocytes_rf"				=> $post['monocytes_rf'],
						"eosinophil"				=> $post['eosinophil'],
						"eosinophil_unit"			=> $post['eosinophil_unit'],
						"eosinophil_rf"				=> $post['eosinophil_rf'],
						"basophils"					=> $post['basophils'],
						"basophils_unit"			=> $post['basophils_unit'],
						"basophils_rf"				=> $post['basophils_rf'],
						"platelet_count"			=> $post['platelet_count'],
						"platelet_count_unit"		=> $post['platelet_count_unit'],
						"esr_lessthan_50"			=> $post['esr_lessthan_50'],
						"esr_lessthan_50_unit"		=> $post['esr_lessthan_50_unit'],
						"esr_greaterthan_50"		=> $post['esr_greaterthan_50'],
						"esr_greaterthan_50_unit"	=> $post['esr_greaterthan_50_unit'],
						"date_updated"	       		=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Hematology::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'oral_glucose_challenge') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Oral_Glucose_Challenge::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"glucose_si" 		   => $post['glucose_si'],
						"glucose_si_unit"      => $post['glucose_si_unit'],
						"glucose_si_rf"	   	   => $post['glucose_si_rf'],
						"glucose_cu"		   => $post['glucose_cu'],
						"glucose_cu_unit"      => $post['glucose_cu_unit'],
						"glucose_cu_rf"        => $post['glucose_cu_rf'],
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Oral_Glucose_Challenge::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'oral_glucose_tolerance') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Oral_Glucose_Tolerance::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"fasting_si" 		   => $post['fasting_si'],
						"fasting_si_unit"      => $post['fasting_si_unit'],
						"fasting_si_rf"	   	   => $post['fasting_si_rf'],
						"1st_hour_si"		   => $post['1st_hour_si'],
						"1st_hour_si_unit"	   => $post['1st_hour_si_unit'],
						"1st_hour_si_rf"       => $post['1st_hour_si_rf'],
						"2nd_hour_si"		   => $post['2nd_hour_si'],
						"2nd_hour_si_unit"     => $post['2nd_hour_si_unit'],
						"2nd_hour_si_rf"       => $post['2nd_hour_si_rf'],
						"fasting_cu"		   => $post['fasting_cu'],
						"fasting_cu_unit"      => $post['fasting_cu_unit'],
						"fasting_cu_rf"        => $post['fasting_cu_rf'],
						"1st_hour_cu"		   => $post['1st_hour_cu'],
						"1st_hour_cu_unit"     => $post['1st_hour_cu_unit'],
						"1st_hour_cu_rf"	   => $post['1st_hour_cu_rf'],
						"2nd_hour_cu"		   => $post['2nd_hour_cu'],
						"2nd_hour_cu_unit"     => $post['2nd_hour_cu_unit'],
						"2nd_hour_cu_rf"       => $post['2nd_hour_cu_rf'],
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Oral_Glucose_Tolerance::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'thyroid') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Thyroid::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"ft3"								=> $post['ft3'],
						"ft3_unit"							=> $post['ft3_unit'],
						"ft3_rf"							=> $post['ft3_rf'],
						"ft4"								=> $post['ft4'],
						"ft4_unit"							=> $post['ft4_unit'],
						"ft4_rf"							=> $post['ft4_rf'],
						"tsh"								=> $post['tsh'],
						"tsh_unit"							=> $post['tsh_unit'],
						"tsh_rf"							=> $post['tsh_rf'],
						"t3_reverse"						=> $post['t3_reverse'],
						"t3_reverse_unit"					=> $post['t3_reverse_unit'],
						"t3_reverse_rf"						=> $post['t3_reverse_rf'],
						"thyroglobulin_antibody"			=> $post['thyroglobulin_antibody'],
						"thyroglobulin_antibody_unit" 		=> $post['thyroglobulin_antibody_unit'],
						"thyroglobulin_antibody_rf"   		=> $post['thyroglobulin_antibody_rf'],
						"thyroid_peroxidase_antibody" 		=> $post['thyroid_peroxidase_antibody'],
						"thyroid_peroxidase_antibody_unit" 	=> $post['thyroid_peroxidase_antibody_unit'],
						"thyroid_peroxidase_antibody_rf"   	=> $post['thyroid_peroxidase_antibody_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Thyroid::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'homeostasis_ass_index') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Homeostasis_Assessment_Index::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"beta_cell_function"				=> $post['beta_cell_function'],
						"insulin_sensitivity"				=> $post['insulin_sensitivity'],
						"insulin_resistance"				=> $post['insulin_resistance'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Homeostasis_Assessment_Index::save($labtest,$data_labtest['id']);
				}elseif ($post['category'] == 'serology') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Serology_Immunology::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"rheumatoid_factor"					=> $post['rheumatoid_factor'],
						"rheumatoid_factor_unit"			=> $post['rheumatoid_factor_unit'],
						"rheumatoid_factor_rf"				=> $post['rheumatoid_factor_rf'],
						"c_reactive_protein"				=> $post['c-reactive_protein'],
						"c_reactive_protein_unit"			=> $post['c-reactive_protein_unit'],
						"c_reactive_protein_rf"				=> $post['c-reactive_protein_rf'],
						"ferritin"							=> $post['ferritin'],
						"ferritin_unit"						=> $post['ferritin_unit'],
						"ferritin_rf"						=> $post['ferritin_rf'],
						"cmv"								=> $post['cmv'],
						"patient"							=> $post['patient'],
						"cut_off"							=> $post['cut-off'],
						"tp_ha"								=> $post['tp-ha'],
						"tp_ha_unit"						=> $post['tp-ha_unit'],
						"tp_ha_rf"							=> $post['tp-ha_rf'],
						"erythropoietin"					=> $post['erythropoietin'],
						"erythropoietin_unit"				=> $post['erythropoietin_unit'],
						"erythropoietin_rf"					=> $post['erythropoietin_rf'],
						"serum_immunoglobulin"				=> $post['serum_immunoglobulin'],
						"serum_immunoglobulin_unit"			=> $post['serum_immunoglobulin_unit'],
						"serum_immunoglobulin_rf"			=> $post['serum_immunoglobulin_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Serology_Immunology::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'special_chem') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Special_Chemistry::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"homocysteine"						=> $post['homocysteine'],
						"homocysteine_unit"					=> $post['homocysteine_unit'],
						"homocysteine_rf"					=> $post['homocysteine_rf'],
						"NT_proBNP"							=> $post['NT-proBNP'],
						"NT_proBNP_unit"					=> $post['NT-proBNP_unit'],
						"NT_proBNP_rf"						=> $post['NT-proBNP_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Special_Chemistry::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'vita_nutri') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Vitamins_Nutrition::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"vitamin_d_25"						=> $post['vitamin_d_25'],
						"vitamin_d_25_unit"					=> $post['vitamin_d_25_unit'],
						"vitamin_d_25_rf"					=> $post['vitamin_d_25_rf'],
						"vitamin_b12"						=> $post['vitamin_b12'],
						"vitamin_b12_unit"					=> $post['vitamin_b12_unit'],
						"vitamin_b12_rf"					=> $post['vitamin_b12_rf'],
						"folate"							=> $post['folate'],
						"folate_unit"						=> $post['folate_unit'],
						"folate_rf"							=> $post['folate_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Vitamins_Nutrition::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'hiv') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Hiv::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"hiv"								=> $post['hiv'],
						"hiv_patient"						=> $post['hiv_patient'],
						"hiv_cut_off"						=> $post['hiv_cut_off'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Hiv::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'eGFR') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Egfr::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"egfr"								=> $post['egfr'],
						"egfr_unit"							=> $post['egfr_unit'],
						"egfr_rf"							=> $post['egfr_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Egfr::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'nutri_elements') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Nutrients_Elements::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"magnesium_rbc"						=> $post['magnesium_rbc'],
						"magnesium_rbc_unit"				=> $post['magnesium_rbc_unit'],
						"magnesium_rbc_rf"					=> $post['magnesium_rbc_rf'],
						"mercury_rbc"						=> $post['mercury_rbc'],
						"mercury_rbc_unit"					=> $post['mercury_rbc_unit'],
						"mercury_rbc_rf"					=> $post['mercury_rbc_rf'],
						"lead_rbc"							=> $post['lead_rbc'],
						"lead_rbc_unit"						=> $post['lead_rbc_unit'],
						"lead_rbc_rf"						=> $post['lead_rbc_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Nutrients_Elements::save($labtest,$data_labtest['id']);
				}elseif ($labtest['category'] == 'viral_hepa') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Viral_Hepatitis::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"hbs_ag"							=> $post['hbs_ag'],
						"hbs_ag_patient"					=> $post['hbs_ag_patient'],
						"hbs_ag_cutoff"						=> $post['hbs_ag_cutoff'],
						"anti_hbs"							=> $post['anti_hbs'],
						"anti_hbs_patient"					=> $post['anti_hbs_patient'],
						"anti_hbs_cutoff"					=> $post['anti_hbs_cutoff'],
						"anti_hbc_lgm"						=> $post['anti_hbc_lgm'],
						"anti_hbc_lgm_patient"				=> $post['anti_hbc_lgm_patient'],
						"anti_hbc_lgm_cutoff"				=> $post['anti_hbc_lgm_cutoff'],
						"anti_hbc_lgg"						=> $post['anti_hbc_lgg'],
						"anti_hbc_lgg_patient"				=> $post['anti_hbc_lgg_patient'],
						"anti_hbc_lgg_cutoff"				=> $post['anti_hbc_lgg_cutoff'],
						"hbe_ag"							=> $post['hbe_ag'],
						"hbe_ag_patient"					=> $post['hbe_ag_patient'],
						"hbe_ag_cutoff"						=> $post['hbe_ag_cutoff'],
						"anti_hbe"							=> $post['anti_hbe'],
						"anti_hbe_patient"					=> $post['anti_hbe_patient'],
						"anti_hbe_cutoff"					=> $post['anti_hbe_cutoff'],
						"anti_hcv"							=> $post['anti_hcv'],
						"anti_hcv_patient"					=> $post['anti_hcv_patient'],
						"anti_hcv_cutoff"					=> $post['anti_hcv_cutoff'],
						"anti_hav_lgm"						=> $post['anti_hav_lgm'],
						"anti_hav_lgm_patient"				=> $post['anti_hav_lgm_patient'],
						"anti_hav_lgm_cutoff"				=> $post['anti_hav_lgm_cutoff'],
						"anti_hav_lgg"						=> $post['anti_hav_lgg'],
						"anti_hav_lgg_patient"				=> $post['anti_hav_lgg_patient'],
						"anti_hav_lgg_cutoff"				=> $post['anti_hav_lgg_cutoff'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Viral_Hepatitis::save($labtest,$data_labtest['id']);
				}elseif ($post['category'] == 'tumor_markers') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Tumor_Markers::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"beta_hcg"							=> $post['beta_hcg'],
						"beta_hcg_unit"						=> $post['beta_hcg_unit'],
						"beta_hcg_rf"						=> $post['beta_hcg_rf'],
						"cea"								=> $post['cea'],
						"cea_unit"							=> $post['cea_unit'],
						"cea_rf"							=> $post['cea_rf'],
						"afp"								=> $post['afp'],
						"afp_unit"							=> $post['afp_unit'],
						"afp_rf"							=> $post['afp_rf'],
						"ca_19_9"							=> $post['ca_19_9'],
						"ca_19_9_unit"						=> $post['ca_19_9_unit'],
						"ca_19_9_rf"						=> $post['ca_19_9_rf'],
						"ca_15_3"							=> $post['ca_15_3'],
						"ca_15_3_unit"						=> $post['ca_15_3_unit'],
						"ca_15_3_rf"						=> $post['ca_15_3_rf'],
						"ca_125"							=> $post['ca_125'],
						"ca_125_unit"						=> $post['ca_125_unit'],
						"ca_125_rf"							=> $post['ca_125_rf'],
						"ca_72_4"							=> $post['ca_72_4'],
						"ca_72_4_unit"						=> $post['ca_72_4_unit'],
						"ca_72_4_rf"						=> $post['ca_72_4_rf'],
						"cyfra_21_1"						=> $post['cyfra_21_1'],
						"cyfra_21_1_unit"					=> $post['cyfra_21_1_unit'],
						"cyfra_21_1_rf"						=> $post['cyfra_21_1_rf'],
						"cyfra_21_1_clia"					=> $post['cyfra_21_1_clia'],
						"cyfra_21_1_clia_unit"				=> $post['cyfra_21_1_clia_unit'],
						"cyfra_21_1_clia_rf"				=> $post['cyfra_21_1_clia_rf'],
						"total_psa"							=> $post['total_psa'],
						"total_psa_unit"					=> $post['total_psa_unit'],
						"total_psa_rf"						=> $post['total_psa_rf'],
						"free_psa"							=> $post['free_psa'],
						"free_psa_unit"						=> $post['free_psa_unit'],
						"free_psa_rf"						=> $post['free_psa_rf'],
						"total_psa_ratio"					=> $post['total_psa_ratio'],
						"total_psa_ratio_unit"				=> $post['total_psa_ratio_unit'],
						"total_psa_ratio_rf"				=> $post['total_psa_ratio_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Tumor_Markers::save($labtest, $data_labtest['id']);
				}elseif ($labtest['category'] == 'hormones') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Hormones_Test::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"fsh"								=> $post['fsh'],
						"fsh_unit"							=> $post['fsh_unit'],
						"fsh_rf"							=> $post['fsh_rf'],
						"lh"								=> $post['lh'],
						"lh_unit"							=> $post['lh_unit'],
						"lh_rf"								=> $post['lh_rf'],
						"progesterone"						=> $post['progesterone'],
						"progesterone_unit"					=> $post['progesterone_unit'],
						"progesterone_rf"					=> $post['progesterone_rf'],
						"estradiol"							=> $post['estradiol'],
						"estradiol_unit"					=> $post['estradiol_unit'],
						"estradiol_rf"						=> $post['estradiol_rf'],
						"testosterone"						=> $post['testosterone'],
						"testosterone_unit"					=> $post['testosterone_unit'],
						"testosterone_rf"					=> $post['testosterone_rf'],
						"total_testosterone"				=> $post['total_testosterone'],
						"total_testosterone_unit"			=> $post['total_testosterone_unit'],
						"total_testosterone_rf"				=> $post['total_testosterone_rf'],
						"free_testosterone"					=> $post['free_testosterone'],
						"free_testosterone_unit"			=> $post['free_testosterone_unit'],
						"free_testosterone_rf"				=> $post['free_testosterone_rf'],
						"shbg"								=> $post['shbg'],
						"shbg_unit"							=> $post['shbg_unit'],
						"shbg_rf"							=> $post['shbg_rf'],
						"cortisol"							=> $post['cortisol'],
						"cortisol_unit"						=> $post['cortisol_unit'],
						"cortisol_rf"						=> $post['cortisol_rf'],
						"aldosterone"						=> $post['aldosterone'],
						"aldosterone_unit"					=> $post['aldosterone_unit'],
						"aldosterone_rf"					=> $post['aldosterone_rf'],
						"dht"								=> $post['dht'],
						"dht_unit"							=> $post['dht_unit'],
						"dht_rf"							=> $post['dht_rf'],
						"serotonin"							=> $post['serotonin'],
						"serotonin_unit"					=> $post['serotonin_unit'],
						"serotonin_rf"						=> $post['serotonin_rf'],
						"pregnenolone"						=> $post['pregnenolone'],
						"pregnenolone_unit"					=> $post['pregnenolone_unit'],
						"pregnenolone_rf"					=> $post['pregnenolone_rf'],
						"c_peptide"							=> $post['c_peptide'],
						"c_peptide_unit"					=> $post['c_peptide_unit'],
						"c_peptide_rf"						=> $post['c_peptide_rf'],
						"insulin_assay_fasting"				=> $post['insulin_assay_fasting'],
						"insulin_assay_fasting_unit"		=> $post['insulin_assay_fasting_unit'],
						"insulin_assay_fasting_rf"			=> $post['insulin_assay_fasting_rf'],
						"post_prandial"						=> $post['post_prandial'],
						"post_prandial_unit"				=> $post['post_prandial_unit'],
						"post_prandial_rf"					=> $post['post_prandial_rf'],
						"dhea_so4"							=> $post['dhea_so4'],
						"dhea_so4_unit"						=> $post['dhea_so4_unit'],
						"dhea_so4_rf"						=> $post['dhea_so4_rf'],
						"igf_1"								=> $post['igf_1'],
						"igf_1_unit"						=> $post['igf_1_unit'],
						"igf_1_rf"							=> $post['igf_1_rf'],
						"igf_bp3"							=> $post['igf_bp3'],
						"igf_bp3_unit"						=> $post['igf_bp3_unit'],
						"igf_bp3_rf"						=> $post['igf_bp3_rf'],
						"osteocalcin"						=> $post['osteocalcin'],
						"osteocalcin_unit"					=> $post['osteocalcin_unit'],
						"osteocalcin_rf"					=> $post['osteocalcin_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Hormones_Test::save($labtest, $data_labtest['id']);
				}elseif ($labtest['category'] == 'biochemistry') {
					$data['data_labtest'] = $data_labtest = Patient_Labtest_Biochemistry::findByLabtestId(array("id" => $labtest['id']));
					
					$labtest = array(
						"bio_fasting_si"					=> $post['bio_fasting_si'],
						"bio_fasting_si_unit"				=> $post['bio_fasting_si_unit'],
						"bio_fasting_si_rf"					=> $post['bio_fasting_si_rf'],
						"bio_fasting_cu"					=> $post['bio_fasting_cu'],
						"bio_fasting_cu_unit"				=> $post['bio_fasting_cu_unit'],
						"bio_fasting_cu_rf"					=> $post['bio_fasting_cu_rf'],
						"bio_random_si"						=> $post['bio_random_si'],
						"bio_random_si_unit"				=> $post['bio_random_si_unit'],
						"bio_random_si_rf"					=> $post['bio_random_si_rf'],
						"bio_random_cu"						=> $post['bio_random_cu'],
						"bio_random_cu_unit"				=> $post['bio_random_cu_unit'],
						"bio_random_cu_rf"					=> $post['bio_random_cu_rf'],
						"blood_urea_nitrogen_si"			=> $post['blood_urea_nitrogen_si'],
						"blood_urea_nitrogen_si_unit"		=> $post['blood_urea_nitrogen_si_unit'],
						"blood_urea_nitrogen_si_rf"			=> $post['blood_urea_nitrogen_si_rf'],
						"blood_urea_nitrogen_cu"			=> $post['blood_urea_nitrogen_cu'],
						"blood_urea_nitrogen_cu_unit"		=> $post['blood_urea_nitrogen_cu_unit'],
						"blood_urea_nitrogen_cu_rf"			=> $post['blood_urea_nitrogen_cu_rf'],
						"creatinine_si"						=> $post['creatinine_si'],
						"creatinine_si_unit"				=> $post['creatinine_si_unit'],
						"creatinine_si_rf"					=> $post['creatinine_si_rf'],
						"creatinine_cu"						=> $post['creatinine_cu'],
						"creatinine_cu_unit"				=> $post['creatinine_cu_unit'],
						"creatinine_cu_rf"					=> $post['creatinine_cu_rf'],
						"sgot_si"							=> $post['sgot_si'],
						"sgot_si_unit"						=> $post['sgot_si_unit'],
						"sgot_si-rf"						=> $post['sgot_si_rf'],
						"sgot_cu"							=> $post['sgot_cu'],
						"sgot_cu_unit"						=> $post['sgot_cu_unit'],
						"sgot_cu_rf"						=> $post['sgot_cu_rf'],
						"sgpt_si"							=> $post['sgpt_si'],
						"sgpt_si_unit"						=> $post['sgpt_si_unit'],
						"sgpt_si_rf"						=> $post['sgpt_si_rf'],
						"sgpt_cu"							=> $post['sgpt_cu'],
						"sgpt_cu_unit"						=> $post['sgpt_cu_unit'],
						"sgpt_cu_rf"						=> $post['sgpt_cu_rf'],
						"alk_phosphatase_si"				=> $post['alk_phosphatase_si'],
						"alk_phosphatase_si_unit"			=> $post['alk_phosphatase_si_unit'],
						"alk_phosphatase_si_rf"				=> $post['alk_phosphatase_si_rf'],
						"alk_phosphatase_cu"				=> $post['alk_phosphatase_cu'],
						"alk_phosphatase_cu_unit"			=> $post['alk_phosphatase_cu_unit'],
						"alk_phosphatase_cu_rf"				=> $post['alk_phosphatase_cu_rf'],
						"ggt_si"							=> $post['ggt_si'],
						"ggt_si_unit"						=> $post['ggt_si_unit'],
						"ggt_si_rf"							=> $post['ggt_si_rf'],
						"ggt_cu"							=> $post['ggt_cu'],
						"ggt_cu_unit"						=> $post['ggt_cu_unit'],
						"ggt_cu_rf"							=> $post['ggt_cu_rf'],
						"total_bilirubin_si"				=> $post['total_bilirubin_si'],
						"total_bilirubin_si_unit"			=> $post['total_bilirubin_si_unit'],
						"total_bilirubin_si_rf"				=> $post['total_bilirubin_si_rf'],
						"total_bilirubin_cu"				=> $post['total_bilirubin_cu'],
						"total_bilirubin_cu_unit"			=> $post['total_bilirubin_cu_unit'],
						"total_bilirubin_cu_rf"				=> $post['total_bilirubin_cu_rf'],
						"direct_bilirubin_si"				=> $post['direct_bilirubin_si'],
						"direct_bilirubin_si_unit"			=> $post['direct_bilirubin_si_unit'],
						"direct_bilirubin_si_rf"			=> $post['direct_bilirubin_si_rf'],
						"direct_bilirubin_cu"				=> $post['direct_bilirubin_cu'],
						"direct_bilirubin_cu_unit"			=> $post['direct_bilirubin_cu_unit'],
						"direct_bilirubin_cu_rf"			=> $post['direct_bilirubin_cu_rf'],
						"indirect_bilirubin_si"				=> $post['indirect_bilirubin_si'],
						"indirect_bilirubin_si_unit"		=> $post['indirect_bilirubin_si_unit'],
						"indirect_bilirubin_si_rf"			=> $post['indirect_bilirubin_si_rf'],
						"indirect_bilirubin_cu"				=> $post['indirect_bilirubin_cu'],
						"indirect_bilirubin_cu_unit"		=> $post['indirect_bilirubin_cu_unit'],
						"indirect_bilirubin_cu_rf"			=> $post['indirect_bilirubin_cu_rf'],
						"total_protein_si"					=> $post['total_protein_si'],
						"total_protein_si_unit"				=> $post['total_protein_si_unit'],
						"total_protein_si_rf"				=> $post['total_protein_si_rf'],
						"total_protein_cu"					=> $post['total_protein_cu'],
						"total_protein_cu_unit"				=> $post['total_protein_cu_unit'],
						"total_protein_cu_rf"				=> $post['total_protein_cu_rf'],
						"albumin_si"						=> $post['albumin_si'],
						"albumin_si_unit"					=> $post['albumin_si_unit'],
						"albumin_si_rf"						=> $post['albumin_si_rf'],
						"albumin_cu"						=> $post['albumin_cu'],
						"albumin_cu_unit"					=> $post['albumin_cu_unit'],
						"albumin_cu_rf"						=> $post['albumin_cu_rf'],
						"globulin_si"						=> $post['globulin_si'],
						"globulin_si_unit"					=> $post['globulin_si_unit'],
						"globulin_si_rf"					=> $post['globulin_si_rf'],
						"globulin_cu"						=> $post['globulin_cu'],
						"globulin_cu_unit"					=> $post['globulin_cu_unit'],
						"globulin_cu_rf"					=> $post['globulin_cu_rf'],
						"ag_ratio_si"						=> $post['ag_ratio_si'],
						"ag_ratio_si_unit"					=> $post['ag_ratio_si_unit'],
						"ag_ratio_si_rf"					=> $post['ag_ratio_si_rf'],
						"ag_ratio_cu"						=> $post['ag_ratio_cu'],
						"ag_ratio_cu_unit"					=> $post['ag_ratio_cu_unit'],
						"ag_ratio_cu_rf"					=> $post['ag_ratio_cu_rf'],
						"lactose_dehydrogenase_si"			=> $post['lactose_dehydrogenase_si'],
						"lactose_dehydrogenase_si_unit"		=> $post['lactose_dehydrogenase_si_unit'],
						"lactose_dehydrogenase_si_rf"		=> $post['lactose_dehydrogenase_si_rf'],
						"lactose_dehydrogenase_cu"			=> $post['lactose_dehydrogenase_cu'],
						"lactose_dehydrogenase_cu_unit"		=> $post['lactose_dehydrogenase_cu_unit'],
						"lactose_dehydrogenase_cu_rf"		=> $post['lactose_dehydrogenase_cu_rf'],
						"inorganic_phosphate_si"			=> $post['inorganic_phosphate_si'],
						"inorganic_phosphate_si_unit"		=> $post['inorganic_phosphate_si_unit'],
						"inorganic_phosphate_si_rf"			=> $post['inorganic_phosphate_si_rf'],
						"inorganic_phosphate_cu"			=> $post['inorganic_phosphate_cu'],
						"inorganic_phosphate_cu_unit"		=> $post['inorganic_phosphate_cu_unit'],
						"inorganic_phosphate_cu_rf"			=> $post['inorganic_phosphate_cu_rf'],
						"bicarbonate_si"					=> $post['bicarbonate_si'],
						"bicarbonate_si_unit"				=> $post['bicarbonate_si_unit'],
						"bicarbonate_si_rf"					=> $post['bicarbonate_si_rf'],
						"bicarbonate_cu"					=> $post['bicarbonate_cu'],
						"bicarbonate_cu_unit"				=> $post['bicarbonate_cu_unit'],
						"bicarbonate_cu_rf"					=> $post['bicarbonate_cu_rf'],
						"amylase_si"						=> $post['amylase_si'],
						"amylase_si_unit"					=> $post['amylase_si_unit'],
						"amylase_si_rf"						=> $post['amylase_si_rf'],
						"amylase_cu"						=> $post['amylase_cu'],
						"amylase_cu_unit"					=> $post['amylase_cu_unit'],
						"amylase_cu_rf"						=> $post['amylase_cu_rf'],
						"lipase_si"							=> $post['lipase_si'],
						"lipase_si_unit"					=> $post['lipase_si_unit'],
						"lipase_si_rf"						=> $post['lipase_si_rf'],
						"lipase_cu"							=> $post['lipase_cu'],
						"lipase_cu_unit"					=> $post['lipase_cu_unit'],
						"lipase_cu_rf"						=> $post['lipase_cu_rf'],
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Biochemistry::save($labtest, $data_labtest['id']);

					$data['data_labtest_2'] = $data_labtest_2 = Patient_Labtest_Biochemistry_2::findByLabtestId(array("id" => $labtest['id']));

					$labtest_2 =array(
						"ck_total_si"						=> $post['ck_total_si'],
						"ck_total_si_unit"					=> $post['ck_total_si_unit'],
						"ck_total_si_rf"					=> $post['ck_total_si_rf'],
						"ck_total_cu"						=> $post['ck_total_cu'],
						"ck_total_cu_unit"					=> $post['ck_total_cu_unit'],
						"ck_total_cu_rf"					=> $post['ck_total_cu_rf'],
						"cpk_mm_si"							=> $post['cpk_mm_si'],
						"cpk_mm_si_unit"					=> $post['cpk_mm_si_unit'],
						"cpk_mm_si_rf"						=> $post['cpk_mm_si_rf'],
						"cpk_mm_cu"							=> $post['cpk_mm_cu'],
						"cpk_mm_cu_unit"					=> $post['cpk_mm_unit'],
						"cpk_mm_cu_rf"						=> $post['cpk_mm_cu_rf'],
						"ck_mb_si"							=> $post['ck_mb_si'],
						"ck_mb_si_unit"						=> $post['ck_mb_si_unit'],
						"ck_mb_si_rf"						=> $post['ck_mb_si_rf'],
						"ck_mb_cu"							=> $post['ck_mb_cu'],
						"ck_mb_cu_unit"						=> $post['ck_mb_cu_unit'],
						"ck_mb_cu_rf"						=> $post['ck_mb_cu_rf'],
						"fructosamine_si"					=> $post['fructosamine_si'],
						"fructosamine_si_unit"				=> $post['fructosamine_si_unit'],
						"fructosamine_si_rf"				=> $post['fructosamine_si_rf'],
						"fructosamine_cu"					=> $post['fructosamine_cu'],
						"fructosamine_cu_unit"				=> $post['fructosamine_cu_unit'],
						"fructosamine_cu_rf"				=> $post['fructosamine_cu_rf'],
						"hba1c_si"							=> $post['hba1c_si'],
						"hba1c_si_unit"						=> $post['hba1c_si_unit'],
						"hba1c_si_rf"						=> $post['hba1c_si_rf'],
						"hba1c_cu"							=> $post['hba1c_cu'],
						"hba1c_cu_unit"						=> $post['hba1c_cu_unit'],
						"hba1c_cu_rf"						=> $post['hba1c_cu_rf'],
						"lipoprotein_si"					=> $post['lipoprotein_si'],
						"lipoprotein_si_unit"				=> $post['lipoprotein_si_unit'],
						"lipoprotein_si_rf"					=> $post['lipoprotein_si_rf'],
						"lipoprotein_cu"					=> $post['lipoprotein_cu'],
						"lipoprotein_cu_unit"				=> $post['lipoprotein_cu_unit'],
						"lipoprotein_cu_rf"					=> $post['lipoprotein_cu_rf'],
						"cholesterol_si"					=> $post['cholesterol_si'],
						"cholesterol_si_unit"				=> $post['cholesterol_si_unit'],
						"cholesterol_si_rf"					=> $post['cholesterol_si_rf'],
						"cholesterol_cu"					=> $post['cholesterol_cu'],
						"cholesterol_cu_unit"				=> $post['cholesterol_cu_unit'],
						"cholesterol_cu_rf"					=> $post['cholesterol_cu_rf'],
						"triglycerides_si"					=> $post['triglycerides_si'],
						"triglycerides_si_unit"				=> $post['triglycerides_si_unit'],
						"triglycerides_si_rf"				=> $post['triglycerides_si_rf'],
						"triglycerides_cu"					=> $post['triglycerides_cu'],
						"triglycerides_cu_unit"				=> $post['triglycerides_cu_unit'],
						"triglycerides_cu_rf"				=> $post['triglycerides_cu_rf'],
						"hdl_si"							=> $post['hdl_si'],
						"hdl_si_unit"						=> $post['hdl_si_unit'],
						"hdl_si_rf"							=> $post['hdl_si_rf'],
						"hdl_cu"							=> $post['hdl_cu'],
						"hdl_cu_unit"						=> $post['hdl_cu_unit'],
						"hdl_cu_rf"							=> $post['hdl_cu_rf'],
						"ldl_si"							=> $post['ldl_si'],
						"ldl_si_unit"						=> $post['ldl_si_unit'],
						"ldl_si_rf"							=> $post['ldl_si_rf'],
						"ldl_cu"							=> $post['ldl_cu'],
						"ldl_cu_unit"						=> $post['ldl_cu_unit'],
						"ldl_cu_rf"							=> $post['ldl_cu_rf'],
						"vldl_si"							=> $post['vldl_si'],
						"vldl_si_unit"						=> $post['vldl_si_unit'],
						"vldl_si_rf"						=> $post['vldl_si_rf'],
						"vldl_cu"							=> $post['vldl_cu'],
						"vldl_cu_unit"						=> $post['vldl_cu_unit'],
						"vldl_cu_rf"						=> $post['vldl_cu_rf'],
						"total_chole_hdl_si"				=> $post['total_chole_hdl_si'],
						"total_chole_hdl_si_unit"			=> $post['total_chole_hdl_si_unit'],
						"total_chole_hdl_si_rf"				=> $post['total_chole_hdl_si_rf'],
						"total_chole_hdl_cu"				=> $post['total_chole_hdl_cu'],
						"total_chole_hdl_cu_unit"			=> $post['total_chole_hdl_cu_unit'],
						"total_chole_hdl_cu_rf"				=> $post['total_chole_hdl_cu_rf'],
						"hdl_ldl_si"						=> $post['hdl_ldl_si'],
						"hdl_ldl_si_unit"					=> $post['hdl_ldl_si_unit'],
						"hdl_ldl_si_rf"						=> $post['hdl_ldl_si_rf'],
						"hdl_ldl_cu"						=> $post['hdl_ldl_cu'],
						"hdl_ldl_cu_unit"					=> $post['hdl_ldl_cu_unit'],
						"hdl_ldl_cu_rf"						=> $post['hdl_ldl_cu_rf'],
						"triglycerides_hdl_si"				=> $post['triglycerides_hdl_si'],
						"triglycerides_hdl_si_unit"			=> $post['triglycerides_hdl_si_unit'],
						"triglycerides_hdl_si_rf"			=> $post['triglycerides_hdl_si_rf'],
						"triglycerides_hdl_cu"				=> $post['triglycerides_hdl_cu'],
						"triglycerides_hdl_cu_unit"			=> $post['triglycerides_hdl_cu_unit'],
						"triglycerides_hdl_cu_rf"			=> $post['triglycerides_hdl_cu_rf'],
						"sodium_si"							=> $post['sodium_si'],
						"sodium_si_unit"					=> $post['sodium_si_unit'],
						"sodium_si_rf"						=> $post['sodium_si_rf'],
						"sodium_cu"							=> $post['sodium_cu'],
						"sodium_cu_unit"					=> $post['sodium_cu_unit'],
						"sodium_cu_rf"						=> $post['sodium_cu_rf'],
						"potassium_si"						=> $post['potassium_si'],
						"potassium_si_unit"					=> $post['potassium_si_unit'],
						"potassium_si_rf"					=> $post['potassium_si_rf'],
						"potassium_cu"						=> $post['potassium_cu'],
						"potassium_cu_unit"					=> $post['potassium_cu_unit'],
						"potassium_cu_rf"					=> $post['potassium_cu_rf'],
						"calcium_si"						=> $post['calcium_si'],
						"calcium_si_unit"					=> $post['calcium_si_unit'],
						"calcium_si_rf"						=> $post['calcium_si_rf'],
						"calcium_cu"						=> $post['calcium_cu'],
						"calcium_cu_unit"					=> $post['calcium_cu_unit'],
						"calcium_cu_rf"						=> $post['calcium_cu_rf'],
						"chloride_si"						=> $post['chloride_si'],
						"chloride_si_unit"					=> $post['chloride_si_unit'],
						"chloride_si_rf"					=> $post['chloride_si_rf'],
						"chloride_cu"						=> $post['chloride_cu'],
						"chloride_cu_unit"					=> $post['chloride_cu_unit'],
						"chloride_cu_rf"					=> $post['chloride_cu_rf'],
						"magnesium_si"						=> $post['magnesium_si'],
						"magnesium_si_unit"					=> $post['magnesium_si_unit'],
						"magnesium_si_rf"					=> $post['magnesium_si_rf'],
						"magnesium_cu"						=> $post['magnesium_cu'],
						"magnesium_cu_unit"					=> $post['magnesium_cu_unit'],
						"magnesium_cu_rf"					=> $post['magnesium_cu_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Biochemistry_2::save($labtest_2, $data_labtest_2['id']);
				}


				$record = array(
					"type_of_test"		   => $post['type_of_test'],
					"hospital"			   => $post['hospital'],
					"date_of_test"		   => $post['date_of_test'],
					"requesting_physician" => $post['requesting_physician'],
					"test_patient_number"  => $post['test_patient_number'],
					"case_number"		   => $post['case_number'],
					"date_updated"	       => date("Y-m-d H:i:s"),
					"created_by"           => $session_id
					);
				 Patient_Labtest::save($record,$post['labtest_id']);

				$json['message'] 		= "Successfully updated laboratory test!";
				$json['is_successful'] 	= true;
				$msg = $session['name'] . " has successfully updated laboratory test."; 

				//New Notification
				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated laboratory test",
					'type' => 'info'
				));

			}else{
				//NEW TEST
				$patient_id = $post['patient_id'];

				$record = array(
					"patient_id"		   => $post['patient_id'],
					"type_of_test"		   => $post['type_of_test'],
					"category"			   => $post['category'],
					"hospital"			   => $post['hospital'],
					"date_of_test"		   => $post['date_of_test'],
					"requesting_physician" => $post['requesting_physician'],
					"test_patient_number"  => $post['test_patient_number'],
					"case_number"		   => $post['case_number'],
					"date_created"		   => date("Y-m-d H:i:s"),
					"date_updated"	       => date("Y-m-d H:i:s"),
					"created_by"           => $session_id
					);
				$lab_id = Patient_Labtest::save($record);

				if($post['category'] == 'urinalysis'){
					$labtest = array(
						"labtest_id" 		=> $lab_id,
						"color"		 		=> $post['color'],
						"transparency"   	=> $post['transparency'],
						"specific_gravity" 	=> $post['specific_gravity'],
						"pH"				=> $post['pH'],
						"protein" 			=> $post['protein'],
						"sugar"	  			=> $post['sugar'],
						"bilirubin" 		=> $post['bilirubin'],
						"urobilinogen" 		=> $post['urobilinogen'],
						"ketone" 			=> $post['ketone'],
						"nitrite" 			=> $post['nitrite'],
						"microscopic_rbc"	=> $post['microscopic_rbc'],
						"pus_cell" 			=> $post['pus_cell'],
						"epithelial_cell" 	=> $post['epithelial_cell'],
						"mucus_threads"		=> $post['mucus_threads'],
						"bacteria" 			=> $post['bacteria'],
						"amorphous_urates" 	=> $post['amorphous_urates'],
						"hyaline"			=> $post['hyaline'],
						"fine_granular"		=> $post['fine_granular'],
						"coarse_granular"	=> $post['coarse_granular'],
						"rbc_cast"			=> $post['rbc_cast'],
						"wbc_cast"			=> $post['wbc_cast'],
						"calcium_oxalates"	=> $post['calcium_oxalates'],
						"uric_acid"			=> $post['uric_acid'],
						"hippuric_acid"		=> $post['hippuric_acid'],
						"date_created"		=> date("Y-m-d H:i:s"),
						"date_updated"	    => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Urinalysis::save($labtest);

				}elseif ($post['category'] == 'xray' || $post['category'] == 'ultrasound') {
					$file = $_FILES['img'];

					if($file){
						foreach($_FILES['img']['tmp_name'] as $key => $tmp_name ){

						    $file_name = $key.$_FILES['img']['name'][$key];
						    $file_size =$_FILES['img']['size'][$key];
						    $file_tmp =$_FILES['img']['tmp_name'][$key];
						    $file_type=$_FILES['img']['type'][$key];

						 
						    $this->load->helper('string');
							$random_key = random_string('unique');

							$patient = Patients::findById(array("id" => $patient_id));
							$file_extension	= substr($file_name, -3);
							$raw_name 		= $this->encrypt->sha1(basename($file_name."$random_key")).date("hims",time());
							$upload_name 	= $file_name;
							$file_size 		= $file_size;

		

							$dir = "files/patient/files/" . trim($patient['patient_code']);
							if(!is_dir($dir)) {
								mkdir($dir,0777,true);
							}

							$path = "$dir/$raw_name.{$file_extension}";
								
							if(move_uploaded_file($file_tmp, $path)) {


								$arr = array(
									"labtest_id" 		=> $lab_id,
									"title" 			=> $upload_name,
									"description" 		=> "n/a",
									"upload_filename"	=> $upload_name,
									"filename" 			=> $raw_name,
									"extension" 		=> $file_extension,
									"size" 				=> $file_size,
									"base_path" 		=> $dir,
									"interpretation"	=> $post['interpretation'],
									"date_created" 		=> date("Y-m-d H:i:s",time()),
									"date_updated" 		=> date("Y-m-d H:i:s",time()),
								);

								$file_id = Patient_Labtest_Xray::save($arr);
							}
						}		
					}
				}elseif ($post['category'] == 'urine_chemistry') {		
					$labtest = array(
						"labtest_id"           => $lab_id,
						"microalbumin" 		   => $post['microalbumin'],
						"microalbumin_unit"    => $post['microalbumin_unit'],
						"microalbumin_rf"	   => $post['microalbumin_rf'],
						"date_created"		   => date("Y-m-d H:i:s"),
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Urine_Chemistry::save($labtest);
				}elseif ($post['category'] == 'coagulation_factor') {
					$labtest = array(
						"labtest_id" 		   => $lab_id,
						"fibrinogen" 		   => $post['fibrinogen'],
						"fibrinogen_unit"      => $post['fibrinogen_unit'],
						"fibrinogen_rf"	   	   => $post['fibrinogen_rf'],
						"bleeding_time"		   => $post['bleeding_time'],
						"bleeding_time_rf"     => $post['bleeding_time_rf'],
						"clotting_time"		   => $post['clotting_time'],
						"clotting_time_rf"	   => $post['clotting_time_rf'],
						"date_created"		   => date("Y-m-d H:i:s"),
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Coagulation_Factor::save($labtest);
				}elseif ($post['category'] == 'coagulation') {
					$labtest = array(
						"labtest_id" 		   		=> $lab_id,
						"prothrombin_time"     		=> $post['prothrombin_time'],
						"prothrombin_time_unit"		=> $post['prothrombin_time_unit'],
						"prothrombin_time_rf"		=> $post['prothrombin_time_rf'],
						"control"					=> $post['control'],
						"control_unit"				=> $post['control_unit'],
						"control_rf"				=> $post['control_rf'],
						"inr"						=> $post['inr'],
						"inr_unit"					=> $post['inr_unit'],
						"inr_rf"					=> $post['inr_rf'],
						"percentage_activity"		=> $post['percentage_activity'],
						"percentage_activity_unit"  => $post['percentage_activity_unit'],
						"percentage_activity_rf"	=> $post['percentage_activity_rf'],
						"activated_partial"			=> $post['activated_partial'],
						"activated_partial_unit"  	=> $post['activated_partial_unit'],
						"activated_partial_rf"		=> $post['activated_partial_rf'],
						"thromboplastin_time"		=> $post['thromboplastin_time'],
						"thromboplastin_time_unit"	=> $post['thromboplastin_time_unit'],
						"thromboplastin_time_rf"	=> $post['thromboplastin_time_rf'],
						"aptt_control"				=> $post['aptt_control'],
						"aptt_control_unit"			=> $post['aptt_control_unit'],
						"aptt_control_rf"			=> $post['aptt_control_rf'],
						"date_created"		   => date("Y-m-d H:i:s"),
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Coagulation::save($labtest);
				}elseif ($post['category'] == 'hematology') {
					$labtest = array(
						"labtest_id" 		   		=> $lab_id,
						"blood_typing_with_rh"		=> $post['blood_typing_with_rh'],
						"blood_type"				=> $post['blood_type'],
						"rh_typing"					=> $post['rh_typing'],
						"reticulocyte_count"		=> $post['reticulocyte_count'],
						"reticulocyte_count_unit"	=> $post['reticulocyte_count_unit'],
						"reticulocyte_count_rf"		=> $post['reticulocyte_count_rf'],
						"rbc"						=> $post['rbc'],
						"rbc_unit"					=> $post['rbc_unit'],
						"rbc_rf"					=> $post['rbc_rf'],
						"hemoglobin"				=> $post['hemoglobin'],
						"hemoglobin_unit"			=> $post['hemoglobin_unit'],
						"hemoglobin_rf"				=> $post['hemoglobin_rf'],
						"hematocrit"				=> $post['hematocrit'],
						"hematocrit_unit"			=> $post['hematocrit_unit'],
						"hematocrit_rf"				=> $post['hematocrit_rf'],
						"mcv"						=> $post['mcv'],
						"mcv_unit"					=> $post['mcv_unit'],
						"mch"						=> $post['mch'],
						"mch_unit"					=> $post['mch_unit'],
						"mch_rf"					=> $post['mch_rf'],
						"mchc"						=> $post['mchc'],
						"mchc_unit"					=> $post['mchc_unit'],
						"mchc_rf"					=> $post['mchc_rf'],
						"wbc"						=> $post['wbc'],
						"wbc_unit"					=> $post['wbc_unit'],
						"wbc_rf"					=> $post['wbc_rf'],
						"granulocytes"				=> $post['granulocytes'],
						"granulocytes_unit"			=> $post['granulocytes_unit'],
						"granulocytes_rf"			=> $post['granulocytes_rf'],
						"lymphocytes"				=> $post['lymphocytes'],
						"lymphocytes_unit"			=> $post['lymphocytes_unit'],
						"lymphocytes_rf"			=> $post['lymphocytes_rf'],
						"monocytes"					=> $post['monocytes'],
						"monocytes_unit"			=> $post['monocytes_unit'],
						"monocytes_rf"				=> $post['monocytes_rf'],
						"eosinophil"				=> $post['eosinophil'],
						"eosinophil_unit"			=> $post['eosinophil_unit'],
						"eosinophil_rf"				=> $post['eosinophil_rf'],
						"basophils"					=> $post['basophils'],
						"basophils_unit"			=> $post['basophils_unit'],
						"basophils_rf"				=> $post['basophils_rf'],
						"platelet_count"			=> $post['platelet_count'],
						"platelet_count_unit"		=> $post['platelet_count_unit'],
						"esr_lessthan_50"			=> $post['esr_lessthan_50'],
						"esr_lessthan_50_unit"		=> $post['esr_lessthan_50_unit'],
						"esr_greaterthan_50"		=> $post['esr_greaterthan_50'],
						"esr_greaterthan_50_unit"	=> $post['esr_greaterthan_50_unit'],
						"date_created"		   => date("Y-m-d H:i:s"),
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Hematology::save($labtest);
				}elseif ($post['category'] == 'oral_glucose_challenge') {
					$labtest = array(
						"labtest_id"           => $lab_id,
						"glucose_si" 		   => $post['glucose_si'],
						"glucose_si_unit"      => $post['glucose_si_unit'],
						"glucose_si_rf"	   	   => $post['glucose_si_rf'],
						"glucose_cu"		   => $post['glucose_cu'],
						"glucose_cu_unit"      => $post['glucose_cu_unit'],
						"glucose_cu_rf"        => $post['glucose_cu_rf'],
						"date_created"		   => date("Y-m-d H:i:s"),
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Oral_Glucose_Challenge::save($labtest);
				}elseif ($post['category'] == 'oral_glucose_tolerance') {
					$labtest = array(
						"labtest_id"           => $lab_id,
						"fasting_si" 		   => $post['fasting_si'],
						"fasting_si_unit"      => $post['fasting_si_unit'],
						"fasting_si_rf"	   	   => $post['fasting_si_rf'],
						"1st_hour_si"		   => $post['1st_hour_si'],
						"1st_hour_si_unit"	   => $post['1st_hour_si_unit'],
						"1st_hour_si_rf"       => $post['1st_hour_si_rf'],
						"2nd_hour_si"		   => $post['2nd_hour_si'],
						"2nd_hour_si_unit"     => $post['2nd_hour_si_unit'],
						"2nd_hour_si_rf"       => $post['2nd_hour_si_rf'],
						"fasting_cu"		   => $post['fasting_cu'],
						"fasting_cu_unit"      => $post['fasting_cu_unit'],
						"fasting_cu_rf"        => $post['fasting_cu_rf'],
						"1st_hour_cu"		   => $post['1st_hour_cu'],
						"1st_hour_cu_unit"     => $post['1st_hour_cu_unit'],
						"1st_hour_cu_rf"	   => $post['1st_hour_cu_rf'],
						"2nd_hour_cu"		   => $post['2nd_hour_cu'],
						"2nd_hour_cu_unit"     => $post['2nd_hour_cu_unit'],
						"2nd_hour_cu_rf"       => $post['2nd_hour_cu_rf'],
						"date_created"		   => date("Y-m-d H:i:s"),
						"date_updated"	       => date("Y-m-d H:i:s")
					);
					Patient_Labtest_Oral_Glucose_Tolerance::save($labtest);
				}elseif ($post['category'] =='thyroid') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"ft3"								=> $post['ft3'],
						"ft3_unit"							=> $post['ft3_unit'],
						"ft3_rf"							=> $post['ft3_rf'],
						"ft4"								=> $post['ft4'],
						"ft4_unit"							=> $post['ft4_unit'],
						"ft4_rf"							=> $post['ft4_rf'],
						"tsh"								=> $post['tsh'],
						"tsh_unit"							=> $post['tsh_unit'],
						"tsh_rf"							=> $post['tsh_rf'],
						"t3_reverse"						=> $post['t3_reverse'],
						"t3_reverse_unit"					=> $post['t3_reverse_unit'],
						"t3_reverse_rf"						=> $post['t3_reverse_rf'],
						"thyroglobulin_antibody"			=> $post['thyroglobulin_antibody'],
						"thyroglobulin_antibody_unit" 		=> $post['thyroglobulin_antibody_unit'],
						"thyroglobulin_antibody_rf"   		=> $post['thyroglobulin_antibody_rf'],
						"thyroid_peroxidase_antibody" 		=> $post['thyroid_peroxidase_antibody'],
						"thyroid_peroxidase_antibody_unit" 	=> $post['thyroid_peroxidase_antibody_unit'],
						"thyroid_peroxidase_antibody_rf"   	=> $post['thyroid_peroxidase_antibody_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Thyroid::save($labtest);
				}elseif ($post['category'] == 'homeostasis_ass_index') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"beta_cell_function"				=> $post['beta_cell_function'],
						"insulin_sensitivity"				=> $post['insulin_sensitivity'],
						"insulin_resistance"				=> $post['insulin_resistance'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Homeostasis_Assessment_Index::save($labtest);
				}elseif ($post['category'] == 'serology') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"rheumatoid_factor"					=> $post['rheumatoid_factor'],
						"rheumatoid_factor_unit"			=> $post['rheumatoid_factor_unit'],
						"rheumatoid_factor_rf"				=> $post['rheumatoid_factor_rf'],
						"c_reactive_protein"				=> $post['c-reactive_protein'],
						"c_reactive_protein_unit"			=> $post['c-reactive_protein_unit'],
						"c_reactive_protein_rf"				=> $post['c-reactive_protein_rf'],
						"ferritin"							=> $post['ferritin'],
						"ferritin_unit"						=> $post['ferritin_unit'],
						"ferritin_rf"						=> $post['ferritin_rf'],
						"cmv"								=> $post['cmv'],
						"patient"							=> $post['patient'],
						"cut_off"							=> $post['cut-off'],
						"tp_ha"								=> $post['tp-ha'],
						"tp_ha_unit"						=> $post['tp-ha_unit'],
						"tp_ha_rf"							=> $post['tp-ha_rf'],
						"erythropoietin"					=> $post['erythropoietin'],
						"erythropoietin_unit"				=> $post['erythropoietin_unit'],
						"erythropoietin_rf"					=> $post['erythropoietin_rf'],
						"serum_immunoglobulin"				=> $post['serum_immunoglobulin'],
						"serum_immunoglobulin_unit"			=> $post['serum_immunoglobulin_unit'],
						"serum_immunoglobulin_rf"			=> $post['serum_immunoglobulin_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Serology_Immunology::save($labtest);
				}elseif ($post['category'] == 'special_chem') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"homocysteine"						=> $post['homocysteine'],
						"homocysteine_unit"					=> $post['homocysteine_unit'],
						"homocysteine_rf"					=> $post['homocysteine_rf'],
						"NT_proBNP"							=> $post['NT-proBNP'],
						"NT_proBNP_unit"					=> $post['NT-proBNP_unit'],
						"NT_proBNP_rf"						=> $post['NT-proBNP_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Special_Chemistry::save($labtest);
				}elseif ($post['category'] == 'vita_nutri') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"vitamin_d_25"						=> $post['vitamin_d_25'],
						"vitamin_d_25_unit"					=> $post['vitamin_d_25_unit'],
						"vitamin_d_25_rf"					=> $post['vitamin_d_25_rf'],
						"vitamin_b12"						=> $post['vitamin_b12'],
						"vitamin_b12_unit"					=> $post['vitamin_b12_unit'],
						"vitamin_b12_rf"					=> $post['vitamin_b12_rf'],
						"folate"							=> $post['folate'],
						"folate_unit"						=> $post['folate_unit'],
						"folate_rf"							=> $post['folate_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Vitamins_Nutrition::save($labtest);
				}elseif ($post['category'] == 'hiv') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"hiv"								=> $post['hiv'],
						"hiv_patient"						=> $post['hiv_patient'],
						"hiv_cut_off"						=> $post['hiv_cut_off'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Hiv::save($labtest);
				}elseif ($post['category'] == 'eGFR') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"egfr"								=> $post['egfr'],
						"egfr_unit"							=> $post['egfr_unit'],
						"egfr_rf"							=> $post['egfr_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Egfr::save($labtest);
				}elseif ($post['category'] == 'nutri_elements') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"magnesium_rbc"						=> $post['magnesium_rbc'],
						"magnesium_rbc_unit"				=> $post['magnesium_rbc_unit'],
						"magnesium_rbc_rf"					=> $post['magnesium_rbc_rf'],
						"mercury_rbc"						=> $post['mercury_rbc'],
						"mercury_rbc_unit"					=> $post['mercury_rbc_unit'],
						"mercury_rbc_rf"					=> $post['mercury_rbc_rf'],
						"lead_rbc"							=> $post['lead_rbc'],
						"lead_rbc_unit"						=> $post['lead_rbc_unit'],
						"lead_rbc_rf"						=> $post['lead_rbc_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Nutrients_Elements::save($labtest);
				}elseif ($post['category'] == 'viral_hepa') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"hbs_ag"							=> $post['hbs_ag'],
						"hbs_ag_patient"					=> $post['hbs_ag_patient'],
						"hbs_ag_cutoff"						=> $post['hbs_ag_cutoff'],
						"anti_hbs"							=> $post['anti_hbs'],
						"anti_hbs_patient"					=> $post['anti_hbs_patient'],
						"anti_hbs_cutoff"					=> $post['anti_hbs_cutoff'],
						"anti_hbc_lgm"						=> $post['anti_hbc_lgm'],
						"anti_hbc_lgm_patient"				=> $post['anti_hbc_lgm_patient'],
						"anti_hbc_lgm_cutoff"				=> $post['anti_hbc_lgm_cutoff'],
						"anti_hbc_lgg"						=> $post['anti_hbc_lgg'],
						"anti_hbc_lgg_patient"				=> $post['anti_hbc_lgg_patient'],
						"anti_hbc_lgg_cutoff"				=> $post['anti_hbc_lgg_cutoff'],
						"hbe_ag"							=> $post['hbe_ag'],
						"hbe_ag_patient"					=> $post['hbe_ag_patient'],
						"hbe_ag_cutoff"						=> $post['hbe_ag_cutoff'],
						"anti_hbe"							=> $post['anti_hbe'],
						"anti_hbe_patient"					=> $post['anti_hbe_patient'],
						"anti_hbe_cutoff"					=> $post['anti_hbe_cutoff'],
						"anti_hcv"							=> $post['anti_hcv'],
						"anti_hcv_patient"					=> $post['anti_hcv_patient'],
						"anti_hcv_cutoff"					=> $post['anti_hcv_cutoff'],
						"anti_hav_lgm"						=> $post['anti_hav_lgm'],
						"anti_hav_lgm_patient"				=> $post['anti_hav_lgm_patient'],
						"anti_hav_lgm_cutoff"				=> $post['anti_hav_lgm_cutoff'],
						"anti_hav_lgg"						=> $post['anti_hav_lgg'],
						"anti_hav_lgg_patient"				=> $post['anti_hav_lgg_patient'],
						"anti_hav_lgg_cutoff"				=> $post['anti_hav_lgg_cutoff'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Viral_Hepatitis::save($labtest);
				}elseif ($post['category'] == 'tumor_markers') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"beta_hcg"							=> $post['beta_hcg'],
						"beta_hcg_unit"						=> $post['beta_hcg_unit'],
						"beta_hcg_rf"						=> $post['beta_hcg_rf'],
						"cea"								=> $post['cea'],
						"cea_unit"							=> $post['cea_unit'],
						"cea_rf"							=> $post['cea_rf'],
						"afp"								=> $post['afp'],
						"afp_unit"							=> $post['afp_unit'],
						"afp_rf"							=> $post['afp_rf'],
						"ca_19_9"							=> $post['ca_19_9'],
						"ca_19_9_unit"						=> $post['ca_19_9_unit'],
						"ca_19_9_rf"						=> $post['ca_19_9_rf'],
						"ca_15_3"							=> $post['ca_15_3'],
						"ca_15_3_unit"						=> $post['ca_15_3_unit'],
						"ca_15_3_rf"						=> $post['ca_15_3_rf'],
						"ca_125"							=> $post['ca_125'],
						"ca_125_unit"						=> $post['ca_125_unit'],
						"ca_125_rf"							=> $post['ca_125_rf'],
						"ca_72_4"							=> $post['ca_72_4'],
						"ca_72_4_unit"						=> $post['ca_72_4_unit'],
						"ca_72_4_rf"						=> $post['ca_72_4_rf'],
						"cyfra_21_1"						=> $post['cyfra_21_1'],
						"cyfra_21_1_unit"					=> $post['cyfra_21_1_unit'],
						"cyfra_21_1_rf"						=> $post['cyfra_21_1_rf'],
						"cyfra_21_1_clia"					=> $post['cyfra_21_1_clia'],
						"cyfra_21_1_clia_unit"				=> $post['cyfra_21_1_clia_unit'],
						"cyfra_21_1_clia_rf"				=> $post['cyfra_21_1_clia_rf'],
						"total_psa"							=> $post['total_psa'],
						"total_psa_unit"					=> $post['total_psa_unit'],
						"total_psa_rf"						=> $post['total_psa_rf'],
						"free_psa"							=> $post['free_psa'],
						"free_psa_unit"						=> $post['free_psa_unit'],
						"free_psa_rf"						=> $post['free_psa_rf'],
						"total_psa_ratio"					=> $post['total_psa_ratio'],
						"total_psa_ratio_unit"				=> $post['total_psa_ratio_unit'],
						"total_psa_ratio_rf"				=> $post['total_psa_ratio_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Tumor_Markers::save($labtest);
				}elseif ($post['category'] == 'hormones') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"fsh"								=> $post['fsh'],
						"fsh_unit"							=> $post['fsh_unit'],
						"fsh_rf"							=> $post['fsh_rf'],
						"lh"								=> $post['lh'],
						"lh_unit"							=> $post['lh_unit'],
						"lh_rf"								=> $post['lh_rf'],
						"progesterone"						=> $post['progesterone'],
						"progesterone_unit"					=> $post['progesterone_unit'],
						"progesterone_rf"					=> $post['progesterone_rf'],
						"estradiol"							=> $post['estradiol'],
						"estradiol_unit"					=> $post['estradiol_unit'],
						"estradiol_rf"						=> $post['estradiol_rf'],
						"testosterone"						=> $post['testosterone'],
						"testosterone_unit"					=> $post['testosterone_unit'],
						"testosterone_rf"					=> $post['testosterone_rf'],
						"total_testosterone"				=> $post['total_testosterone'],
						"total_testosterone_unit"			=> $post['total_testosterone_unit'],
						"total_testosterone_rf"				=> $post['total_testosterone_rf'],
						"free_testosterone"					=> $post['free_testosterone'],
						"free_testosterone_unit"			=> $post['free_testosterone_unit'],
						"free_testosterone_rf"				=> $post['free_testosterone_rf'],
						"shbg"								=> $post['shbg'],
						"shbg_unit"							=> $post['shbg_unit'],
						"shbg_rf"							=> $post['shbg_rf'],
						"cortisol"							=> $post['cortisol'],
						"cortisol_unit"						=> $post['cortisol_unit'],
						"cortisol_rf"						=> $post['cortisol_rf'],
						"aldosterone"						=> $post['aldosterone'],
						"aldosterone_unit"					=> $post['aldosterone_unit'],
						"aldosterone_rf"					=> $post['aldosterone_rf'],
						"dht"								=> $post['dht'],
						"dht_unit"							=> $post['dht_unit'],
						"dht_rf"							=> $post['dht_rf'],
						"serotonin"							=> $post['serotonin'],
						"serotonin_unit"					=> $post['serotonin_unit'],
						"serotonin_rf"						=> $post['serotonin_rf'],
						"pregnenolone"						=> $post['pregnenolone'],
						"pregnenolone_unit"					=> $post['pregnenolone_unit'],
						"pregnenolone_rf"					=> $post['pregnenolone_rf'],
						"c_peptide"							=> $post['c_peptide'],
						"c_peptide_unit"					=> $post['c_peptide_unit'],
						"c_peptide_rf"						=> $post['c_peptide_rf'],
						"insulin_assay_fasting"				=> $post['insulin_assay_fasting'],
						"insulin_assay_fasting_unit"		=> $post['insulin_assay_fasting_unit'],
						"insulin_assay_fasting_rf"			=> $post['insulin_assay_fasting_rf'],
						"post_prandial"						=> $post['post_prandial'],
						"post_prandial_unit"				=> $post['post_prandial_unit'],
						"post_prandial_rf"					=> $post['post_prandial_rf'],
						"dhea_so4"							=> $post['dhea_so4'],
						"dhea_so4_unit"						=> $post['dhea_so4_unit'],
						"dhea_so4_rf"						=> $post['dhea_so4_rf'],
						"igf_1"								=> $post['igf_1'],
						"igf_1_unit"						=> $post['igf_1_unit'],
						"igf_1_rf"							=> $post['igf_1_rf'],
						"igf_bp3"							=> $post['igf_bp3'],
						"igf_bp3_unit"						=> $post['igf_bp3_unit'],
						"igf_bp3_rf"						=> $post['igf_bp3_rf'],
						"osteocalcin"						=> $post['osteocalcin'],
						"osteocalcin_unit"					=> $post['osteocalcin_unit'],
						"osteocalcin_rf"					=> $post['osteocalcin_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Hormones_Test::save($labtest);
				}elseif ($post['category'] == 'biochemistry') {
					$labtest = array(
						"labtest_id"           				=> $lab_id,
						"bio_fasting_si"					=> $post['bio_fasting_si'],
						"bio_fasting_si_unit"				=> $post['bio_fasting_si_unit'],
						"bio_fasting_si_rf"					=> $post['bio_fasting_si_rf'],
						"bio_fasting_cu"					=> $post['bio_fasting_cu'],
						"bio_fasting_cu_unit"				=> $post['bio_fasting_cu_unit'],
						"bio_fasting_cu_rf"					=> $post['bio_fasting_cu_rf'],
						"bio_random_si"						=> $post['bio_random_si'],
						"bio_random_si_unit"				=> $post['bio_random_si_unit'],
						"bio_random_si_rf"					=> $post['bio_random_si_rf'],
						"bio_random_cu"						=> $post['bio_random_cu'],
						"bio_random_cu_unit"				=> $post['bio_random_cu_unit'],
						"bio_random_cu_rf"					=> $post['bio_random_cu_rf'],
						"blood_urea_nitrogen_si"			=> $post['blood_urea_nitrogen_si'],
						"blood_urea_nitrogen_si_unit"		=> $post['blood_urea_nitrogen_si_unit'],
						"blood_urea_nitrogen_si_rf"			=> $post['blood_urea_nitrogen_si_rf'],
						"blood_urea_nitrogen_cu"			=> $post['blood_urea_nitrogen_cu'],
						"blood_urea_nitrogen_cu_unit"		=> $post['blood_urea_nitrogen_cu_unit'],
						"blood_urea_nitrogen_cu_rf"			=> $post['blood_urea_nitrogen_cu_rf'],
						"creatinine_si"						=> $post['creatinine_si'],
						"creatinine_si_unit"				=> $post['creatinine_si_unit'],
						"creatinine_si_rf"					=> $post['creatinine_si_rf'],
						"creatinine_cu"						=> $post['creatinine_cu'],
						"creatinine_cu_unit"				=> $post['creatinine_cu_unit'],
						"creatinine_cu_rf"					=> $post['creatinine_cu_rf'],
						"sgot_si"							=> $post['sgot_si'],
						"sgot_si_unit"						=> $post['sgot_si_unit'],
						"sgot_si_rf"						=> $post['sgot_si_rf'],
						"sgot_cu"							=> $post['sgot_cu'],
						"sgot_cu_unit"						=> $post['sgot_cu_unit'],
						"sgot_cu_rf"						=> $post['sgot_cu_rf'],
						"sgpt_si"							=> $post['sgpt_si'],
						"sgpt_si_unit"						=> $post['sgpt_si_unit'],
						"sgpt_si_rf"						=> $post['sgpt_si_rf'],
						"sgpt_cu"							=> $post['sgpt_cu'],
						"sgpt_cu_unit"						=> $post['sgpt_cu_unit'],
						"sgpt_cu_rf"						=> $post['sgpt_cu_rf'],
						"alk_phosphatase_si"				=> $post['alk_phosphatase_si'],
						"alk_phosphatase_si_unit"			=> $post['alk_phosphatase_si_unit'],
						"alk_phosphatase_si_rf"				=> $post['alk_phosphatase_si_rf'],
						"alk_phosphatase_cu"				=> $post['alk_phosphatase_cu'],
						"alk_phosphatase_cu_unit"			=> $post['alk_phosphatase_cu_unit'],
						"alk_phosphatase_cu_rf"				=> $post['alk_phosphatase_cu_rf'],
						"ggt_si"							=> $post['ggt_si'],
						"ggt_si_unit"						=> $post['ggt_si_unit'],
						"ggt_si_rf"							=> $post['ggt_si_rf'],
						"ggt_cu"							=> $post['ggt_cu'],
						"ggt_cu_unit"						=> $post['ggt_cu_unit'],
						"ggt_cu_rf"							=> $post['ggt_cu_rf'],
						"total_bilirubin_si"				=> $post['total_bilirubin_si'],
						"total_bilirubin_si_unit"			=> $post['total_bilirubin_si_unit'],
						"total_bilirubin_si_rf"				=> $post['total_bilirubin_si_rf'],
						"total_bilirubin_cu"				=> $post['total_bilirubin_cu'],
						"total_bilirubin_cu_unit"			=> $post['total_bilirubin_cu_unit'],
						"total_bilirubin_cu_rf"				=> $post['total_bilirubin_cu_rf'],
						"direct_bilirubin_si"				=> $post['direct_bilirubin_si'],
						"direct_bilirubin_si_unit"			=> $post['direct_bilirubin_si_unit'],
						"direct_bilirubin_si_rf"			=> $post['direct_bilirubin_si_rf'],
						"direct_bilirubin_cu"				=> $post['direct_bilirubin_cu'],
						"direct_bilirubin_cu_unit"			=> $post['direct_bilirubin_cu_unit'],
						"direct_bilirubin_cu_rf"			=> $post['direct_bilirubin_cu_rf'],
						"indirect_bilirubin_si"				=> $post['indirect_bilirubin_si'],
						"indirect_bilirubin_si_unit"		=> $post['indirect_bilirubin_si_unit'],
						"indirect_bilirubin_si_rf"			=> $post['indirect_bilirubin_si_rf'],
						"indirect_bilirubin_cu"				=> $post['indirect_bilirubin_cu'],
						"indirect_bilirubin_cu_unit"		=> $post['indirect_bilirubin_cu_unit'],
						"indirect_bilirubin_cu_rf"			=> $post['indirect_bilirubin_cu_rf'],
						"total_protein_si"					=> $post['total_protein_si'],
						"total_protein_si_unit"				=> $post['total_protein_si_unit'],
						"total_protein_si_rf"				=> $post['total_protein_si_rf'],
						"total_protein_cu"					=> $post['total_protein_cu'],
						"total_protein_cu_unit"				=> $post['total_protein_cu_unit'],
						"total_protein_cu_rf"				=> $post['total_protein_cu_rf'],
						"albumin_si"						=> $post['albumin_si'],
						"albumin_si_unit"					=> $post['albumin_si_unit'],
						"albumin_si_rf"						=> $post['albumin_si_rf'],
						"albumin_cu"						=> $post['albumin_cu'],
						"albumin_cu_unit"					=> $post['albumin_cu_unit'],
						"albumin_cu_rf"						=> $post['albumin_cu_rf'],
						"globulin_si"						=> $post['globulin_si'],
						"globulin_si_unit"					=> $post['globulin_si_unit'],
						"globulin_si_rf"					=> $post['globulin_si_rf'],
						"globulin_cu"						=> $post['globulin_cu'],
						"globulin_cu_unit"					=> $post['globulin_cu_unit'],
						"globulin_cu_rf"					=> $post['globulin_cu_rf'],
						"ag_ratio_si"						=> $post['ag_ratio_si'],
						"ag_ratio_si_unit"					=> $post['ag_ratio_si_unit'],
						"ag_ratio_si_rf"					=> $post['ag_ratio_si_rf'],
						"ag_ratio_cu"						=> $post['ag_ratio_cu'],
						"ag_ratio_cu_unit"					=> $post['ag_ratio_cu_unit'],
						"ag_ratio_cu_rf"					=> $post['ag_ratio_cu_rf'],
						"lactose_dehydrogenase_si"			=> $post['lactose_dehydrogenase_si'],
						"lactose_dehydrogenase_si_unit"		=> $post['lactose_dehydrogenase_si_unit'],
						"lactose_dehydrogenase_si_rf"		=> $post['lactose_dehydrogenase_si_rf'],
						"lactose_dehydrogenase_cu"			=> $post['lactose_dehydrogenase_cu'],
						"lactose_dehydrogenase_cu_unit"		=> $post['lactose_dehydrogenase_cu_unit'],
						"lactose_dehydrogenase_cu_rf"		=> $post['lactose_dehydrogenase_cu_rf'],
						"inorganic_phosphate_si"			=> $post['inorganic_phosphate_si'],
						"inorganic_phosphate_si_unit"		=> $post['inorganic_phosphate_si_unit'],
						"inorganic_phosphate_si_rf"			=> $post['inorganic_phosphate_si_rf'],
						"inorganic_phosphate_cu"			=> $post['inorganic_phosphate_cu'],
						"inorganic_phosphate_cu_unit"		=> $post['inorganic_phosphate_cu_unit'],
						"inorganic_phosphate_cu_rf"			=> $post['inorganic_phosphate_cu_rf'],
						"bicarbonate_si"					=> $post['bicarbonate_si'],
						"bicarbonate_si_unit"				=> $post['bicarbonate_si_unit'],
						"bicarbonate_si_rf"					=> $post['bicarbonate_si_rf'],
						"bicarbonate_cu"					=> $post['bicarbonate_cu'],
						"bicarbonate_cu_unit"				=> $post['bicarbonate_cu_unit'],
						"bicarbonate_cu_rf"					=> $post['bicarbonate_cu_rf'],
						"amylase_si"						=> $post['amylase_si'],
						"amylase_si_unit"					=> $post['amylase_si_unit'],
						"amylase_si_rf"						=> $post['amylase_si_rf'],
						"amylase_cu"						=> $post['amylase_cu'],
						"amylase_cu_unit"					=> $post['amylase_cu_unit'],
						"amylase_cu_rf"						=> $post['amylase_cu_rf'],
						"lipase_si"							=> $post['lipase_si'],
						"lipase_si_unit"					=> $post['lipase_si_unit'],
						"lipase_si_rf"						=> $post['lipase_si_rf'],
						"lipase_cu"							=> $post['lipase_cu'],
						"lipase_cu_unit"					=> $post['lipase_cu_unit'],
						"lipase_cu_rf"						=> $post['lipase_cu_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Biochemistry::save($labtest);

					$labtest_2 =array(
						"labtest_id"           				=> $lab_id,
						"ck_total_si"						=> $post['ck_total_si'],
						"ck_total_si_unit"					=> $post['ck_total_si_unit'],
						"ck_total_si_rf"					=> $post['ck_total_si_rf'],
						"ck_total_cu"						=> $post['ck_total_cu'],
						"ck_total_cu_unit"					=> $post['ck_total_cu_unit'],
						"ck_total_cu_rf"					=> $post['ck_total_cu_rf'],
						"cpk_mm_si"							=> $post['cpk_mm_si'],
						"cpk_mm_si_unit"					=> $post['cpk_mm_si_unit'],
						"cpk_mm_si_rf"						=> $post['cpk_mm_si_rf'],
						"cpk_mm_cu"							=> $post['cpk_mm_cu'],
						"cpk_mm_cu_unit"					=> $post['cpk_mm_unit'],
						"cpk_mm_cu_rf"						=> $post['cpk_mm_cu_rf'],
						"ck_mb_si"							=> $post['ck_mb_si'],
						"ck_mb_si_unit"						=> $post['ck_mb_si_unit'],
						"ck_mb_si_rf"						=> $post['ck_mb_si_rf'],
						"ck_mb_cu"							=> $post['ck_mb_cu'],
						"ck_mb_cu_unit"						=> $post['ck_mb_cu_unit'],
						"ck_mb_cu_rf"						=> $post['ck_mb_cu_rf'],
						"fructosamine_si"					=> $post['fructosamine_si'],
						"fructosamine_si_unit"				=> $post['fructosamine_si_unit'],
						"fructosamine_si_rf"				=> $post['fructosamine_si_rf'],
						"fructosamine_cu"					=> $post['fructosamine_cu'],
						"fructosamine_cu_unit"				=> $post['fructosamine_cu_unit'],
						"fructosamine_cu_rf"				=> $post['fructosamine_cu_rf'],
						"hba1c_si"							=> $post['hba1c_si'],
						"hba1c_si_unit"						=> $post['hba1c_si_unit'],
						"hba1c_si_rf"						=> $post['hba1c_si_rf'],
						"hba1c_cu"							=> $post['hba1c_cu'],
						"hba1c_cu_unit"						=> $post['hba1c_cu_unit'],
						"hba1c_cu_rf"						=> $post['hba1c_cu_rf'],
						"lipoprotein_si"					=> $post['lipoprotein_si'],
						"lipoprotein_si_unit"				=> $post['lipoprotein_si_unit'],
						"lipoprotein_si_rf"					=> $post['lipoprotein_si_rf'],
						"lipoprotein_cu"					=> $post['lipoprotein_cu'],
						"lipoprotein_cu_unit"				=> $post['lipoprotein_cu_unit'],
						"lipoprotein_cu_rf"					=> $post['lipoprotein_cu_rf'],
						"cholesterol_si"					=> $post['cholesterol_si'],
						"cholesterol_si_unit"				=> $post['cholesterol_si_unit'],
						"cholesterol_si_rf"					=> $post['cholesterol_si_rf'],
						"cholesterol_cu"					=> $post['cholesterol_cu'],
						"cholesterol_cu_unit"				=> $post['cholesterol_cu_unit'],
						"cholesterol_cu_rf"					=> $post['cholesterol_cu_rf'],
						"triglycerides_si"					=> $post['triglycerides_si'],
						"triglycerides_si_unit"				=> $post['triglycerides_si_unit'],
						"triglycerides_si_rf"				=> $post['triglycerides_si_rf'],
						"triglycerides_cu"					=> $post['triglycerides_cu'],
						"triglycerides_cu_unit"				=> $post['triglycerides_cu_unit'],
						"triglycerides_cu_rf"				=> $post['triglycerides_cu_rf'],
						"hdl_si"							=> $post['hdl_si'],
						"hdl_si_unit"						=> $post['hdl_si_unit'],
						"hdl_si_rf"							=> $post['hdl_si_rf'],
						"hdl_cu"							=> $post['hdl_cu'],
						"hdl_cu_unit"						=> $post['hdl_cu_unit'],
						"hdl_cu_rf"							=> $post['hdl_cu_rf'],
						"ldl_si"							=> $post['ldl_si'],
						"ldl_si_unit"						=> $post['ldl_si_unit'],
						"ldl_si_rf"							=> $post['ldl_si_rf'],
						"ldl_cu"							=> $post['ldl_cu'],
						"ldl_cu_unit"						=> $post['ldl_cu_unit'],
						"ldl_cu_rf"							=> $post['ldl_cu_rf'],
						"vldl_si"							=> $post['vldl_si'],
						"vldl_si_unit"						=> $post['vldl_si_unit'],
						"vldl_si_rf"						=> $post['vldl_si_rf'],
						"vldl_cu"							=> $post['vldl_cu'],
						"vldl_cu_unit"						=> $post['vldl_cu_unit'],
						"vldl_cu_rf"						=> $post['vldl_cu_rf'],
						"total_chole_hdl_si"				=> $post['total_chole_hdl_si'],
						"total_chole_hdl_si_unit"			=> $post['total_chole_hdl_si_unit'],
						"total_chole_hdl_si_rf"				=> $post['total_chole_hdl_si_rf'],
						"total_chole_hdl_cu"				=> $post['total_chole_hdl_cu'],
						"total_chole_hdl_cu_unit"			=> $post['total_chole_hdl_cu_unit'],
						"total_chole_hdl_cu_rf"				=> $post['total_chole_hdl_cu_rf'],
						"hdl_ldl_si"						=> $post['hdl_ldl_si'],
						"hdl_ldl_si_unit"					=> $post['hdl_ldl_si_unit'],
						"hdl_ldl_si_rf"						=> $post['hdl_ldl_si_rf'],
						"hdl_ldl_cu"						=> $post['hdl_ldl_cu'],
						"hdl_ldl_cu_unit"					=> $post['hdl_ldl_cu_unit'],
						"hdl_ldl_cu_rf"						=> $post['hdl_ldl_cu_rf'],
						"triglycerides_hdl_si"				=> $post['triglycerides_hdl_si'],
						"triglycerides_hdl_si_unit"			=> $post['triglycerides_hdl_si_unit'],
						"triglycerides_hdl_si_rf"			=> $post['triglycerides_hdl_si_rf'],
						"triglycerides_hdl_cu"				=> $post['triglycerides_hdl_cu'],
						"triglycerides_hdl_cu_unit"			=> $post['triglycerides_hdl_cu_unit'],
						"triglycerides_hdl_cu_rf"			=> $post['triglycerides_hdl_cu_rf'],
						"sodium_si"							=> $post['sodium_si'],
						"sodium_si_unit"					=> $post['sodium_si_unit'],
						"sodium_si_rf"						=> $post['sodium_si_rf'],
						"sodium_cu"							=> $post['sodium_cu'],
						"sodium_cu_unit"					=> $post['sodium_cu_unit'],
						"sodium_cu_rf"						=> $post['sodium_cu_rf'],
						"potassium_si"						=> $post['potassium_si'],
						"potassium_si_unit"					=> $post['potassium_si_unit'],
						"potassium_si_rf"					=> $post['potassium_si_rf'],
						"potassium_cu"						=> $post['potassium_cu'],
						"potassium_cu_unit"					=> $post['potassium_cu_unit'],
						"potassium_cu_rf"					=> $post['potassium_cu_rf'],
						"calcium_si"						=> $post['calcium_si'],
						"calcium_si_unit"					=> $post['calcium_si_unit'],
						"calcium_si_rf"						=> $post['calcium_si_rf'],
						"calcium_cu"						=> $post['calcium_cu'],
						"calcium_cu_unit"					=> $post['calcium_cu_unit'],
						"calcium_cu_rf"						=> $post['calcium_cu_rf'],
						"chloride_si"						=> $post['chloride_si'],
						"chloride_si_unit"					=> $post['chloride_si_unit'],
						"chloride_si_rf"					=> $post['chloride_si_rf'],
						"chloride_cu"						=> $post['chloride_cu'],
						"chloride_cu_unit"					=> $post['chloride_cu_unit'],
						"chloride_cu_rf"					=> $post['chloride_cu_rf'],
						"magnesium_si"						=> $post['magnesium_si'],
						"magnesium_si_unit"					=> $post['magnesium_si_unit'],
						"magnesium_si_rf"					=> $post['magnesium_si_rf'],
						"magnesium_cu"						=> $post['magnesium_cu'],
						"magnesium_cu_unit"					=> $post['magnesium_cu_unit'],
						"magnesium_cu_rf"					=> $post['magnesium_cu_rf'],
						"date_created"		   				=> date("Y-m-d H:i:s"),
						"date_updated"	       				=> date("Y-m-d H:i:s")
					);
					Patient_Labtest_Biochemistry_2::save($labtest_2);

				}

				$json['message'] 		= "Successfully added new laboratory test!";
				$json['is_successful'] 	= true;
				$msg = $session['name'] . " has successfully added new laboratory test."; 

				//New Notification
				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added laboratory test",
					'type' => 'info'
				));
			}
		} else {
			$json['message'] = "Error saving to database. Please try again later.";
			$json['is_successful'] = false;	
		}

		echo json_encode($json);
	} 

	function deleteLabtest(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$user = User::findById(array("id" => $this->encrypt->decode($session)));
		$id 	= $post['id'];
		
		$labtest = Patient_Labtest::findById(array("id"=>$id));
		if($labtest){

			$patient = Patients::findById(array("id"=>$labtest['patient_id']));
			
			/* ACTIVITY TRACKER */
			$act_tracker = array(
					"module"		=> "rpc_patient",
					"user_id"		=> $session_id,
					"entity_id"		=> $labtest['patient_id'],
					"message_log" 	=> $user['lastname'] .",". $user['firstname'] ." ". "Successfully deleted Laboratory Test ".$labtest['category'] ."in Patient: {$patient['patient_name']}",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);

			Activity_Tracker::save($act_tracker);
			
			if($labtest['category'] == 'xray' || $labtest['category'] == 'ultrasound'){
				Patient_Labtest_Xray::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'urinalysis') {
				Patient_Labtest_Urinalysis::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'urine_chemistry') {
				Patient_Labtest_Urine_Chemistry::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'coagulation_factor') {
				Patient_Labtest_Coagulation_Factor::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'coagulation') {
				Patient_Labtest_Coagulation::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'hematology') {
				Patient_Labtest_Hematology::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'biochemistry') {
				Patient_Labtest_Biochemistry::deleteByLabtestId($labtest['id']);
				Patient_Labtest_Biochemistry_2::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'oral_glucose_challenge') {
				Patient_Labtest_Oral_Glucose_Challenge::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'oral_glucose_tolerance') {
				Patient_Labtest_Oral_Glucose_Tolerance::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'thyroid') {
				Patient_Labtest_Thyroid::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'hormones') {
				Patient_Labtest_Hormones_Test::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'homeostasis_ass_index') {
				Patient_Labtest_Homeostasis_Assessment_Index::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'serology') {
				Patient_Labtest_Serology_Immunology::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'tumor_markers') {
				Patient_Labtest_Tumor_Markers::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'special_chem') {
				Patient_Labtest_Special_Chemistry::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'vita_nutri') {
				Patient_Labtest_Vitamins_Nutrition::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'viral_hepa') {
				Patient_Labtest_Viral_Hepatitis::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'hiv') {
				Patient_Labtest_Hiv::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'eGFR') {
				Patient_Labtest_Egfr::deleteByLabtestId($labtest['id']);
			}elseif ($labtest['category'] == 'nutri_elements') {
				Patient_Labtest_Nutrients_Elements::deleteByLabtestId($labtest['id']);
			}
			
			Patient_Labtest::delete($id);
		}
	}

	function filterLabtest(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$data['id'] = $post['patient_id'];
		if($post['filtered_by'] == 'All Tests'){
			if($post['kind_of_test'] == '1'){
				$data['laboratory_test'] = $laboratory_test = Patient_Labtest::findAllByPatientIdAndLabtest(array("id" => $post['patient_id']));
				$this->load->view('management/patient/labtest/most_recent_labtest',$data);
			}else{
				$data['imaging_test'] = $imaging_test = Patient_Labtest::findAllImagingTestByPatientId(array("id" => $post['patient_id']));
				$this->load->view('management/patient/labtest/most_recent_imgtest',$data);
			}
		}else{
			if($post['kind_of_test'] == '1'){
				$data['laboratory_test'] = $laboratory_test = Patient_Labtest::findAllByPatientIdCategory(array("patient_id" => $post['patient_id'],"category" => $post['filtered_by']));
				$this->load->view('management/patient/labtest/most_recent_labtest',$data);
			}else{
				$data['imaging_test'] = $imaging_test = Patient_Labtest::findAllByPatientIdCategory(array("patient_id" => $post['patient_id'],"category" => $post['filtered_by']));
				$this->load->view('management/patient/labtest/most_recent_imgtest',$data);
			}
		}
		
		
	}
}