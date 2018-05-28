<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Regimen_Management extends MY_Controller {

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

			Engine::appScript('regimen_management.js');
			Engine::appScript('topbars.js');
			Engine::appScript('confirmation.js');
			Engine::appScript('profile.js');
			Engine::appScript("ckeditor/ckeditor.js");
			Engine::appScript('blockUI.js');

			/* NOTIFICATIONS */
			Jquery::pusher();
			Jquery::gritter();
			Jquery::pnotify();
			Engine::appScript('notification.js');
			/* END */

			Jquery::mask();
			Jquery::select2();
			Jquery::datatable();
			Jquery::form();
			Jquery::inline_validation();
			Jquery::tipsy();

			Bootstrap::datepicker();
			Bootstrap::modal();
			
			ini_set('memory_limit','-1');
			$data['page_title'] = "Regimen Management";
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
			$this->load->view('regimen/index',$data);

		}
	}


	function getRegimenList(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$data['session'] 	= $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['rc_reg']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));
		$this->load->view('regimen/regimen_list',$data);
	}

	function loadNewRegimenApp(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$data['session'] 	= $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['patients'] 	= $patients = Patients::findAll();
		$this->load->view('regimen/forms/add_regimen',$data);
	}

	function loadEditRegimenApp(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$post 	= $this->input->post();
		$reg_id = (int) $post['id'];
		if($reg_id){
			$data['version_id'] = $version_id 	 = $post['version_id'];
			$data['reg']	  	= $reg 			 = Regimen::findById(array("id"=>$reg_id));
			$data['version']	= Regimen_Version::findByRegimenVersionId(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$data['patient']  	= $patient  	 = Patients::findById(array("id" => $reg['patient_id']));
			$data['reg_id']	  	= $reg_id;
			$data['photo'] 	  	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			// unset($_SESSION["view_regimen_meds"]);
			
			$this->load->view('regimen/forms/edit_regimen',$data);
		}	
	}

	function loadViewSummary(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$post 	= $this->input->post();
		$reg_id = (int) $post['id'];
		$version_id = (int) $post['version_id'];
		if($reg_id){
			$data['reg'] 		= $reg = Regimen::findById(array("id"=>$reg_id));
			$data['version_id']	= $version_id;
			if($version_id != 0){
				$data['version'] = $version 	= Regimen_Version::findById(array("id" => $version_id));
			}
			$data['bf'] 		= $list_bf 		= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $reg_id, "meal_type" => "bf", "version_id" => $version_id));
			$data['lunch'] 		= $list_lunch 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $reg_id, "meal_type" => "lunch", "version_id" => $version_id));
			$data['dinner'] 	= $list_dinner 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $reg_id, "meal_type" => "dinner", "version_id" => $version_id));
			$summary 			= Regimen_Med_LIst::findByRegAndVersionID(array("regimen_id" => $reg_id, "version_id" => $version_id));
			
			// echo $version_id . "<br/>";
			
			foreach ($summary as $key => $value) {
				$array[] = $value['medicine_id'];
			}
			$data['regimen_summary'] = $regimen_summary = array_unique($array);

			foreach ($regimen_summary as $a => $b) {
				$medicine = Regimen_Med_List::findByRegimenIDMedIDVersion(array("regimen_id" => $reg_id, "medicine_id" => $b, "version_id" => $version_id));
				$total = 0;
				foreach ($medicine as $c => $d) {

					/* DATE INTERVAL */
						$start 			= new Datetime($d['start_date']);
						$end 			= new Datetime($d['end_date']);
						$interval 		= $start->diff($end);
						$time_interval 	= (int) $interval->format('%a');
						$time_interval  = $time_interval + 1;
					/* END DATE INTERVAL */
						$medicine_id = $d['medicine_id'];
						$quantity 	 = $d['quantity'] * $time_interval;

						$total += (int) $quantity;
				}

				$summary_meds[] = array(
										"medicine_id" 	=> $b,
										"quantity"		=> $total
									);

			}
			$data['summary_meds'] = $summary_meds;
			// debug_array($summary_meds);
			$data['photo'] 		= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			$data['patient']	= $patient 		 = Patients::findById(array("id" => $reg['patient_id']));
			$session 			= $this->session->all_userdata();
			$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
			$data['rc_reg']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));
			$data['invoicing']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 22));
			$this->load->view('regimen/view_summary',$data);
		}
	}

	function loadViewRegimen(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$post 	= $this->input->post();
		$session 	= $this->session->all_userdata();

		unset($_SESSION['view_regimen_meds']);
		$this->session->unset_userdata("view_regimen_meds");

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$reg_id = (int) $post['id'];
		if($reg_id){
			$data['reg_id'] 	= $reg_id;
			$data['reg'] 		= $reg 			= Regimen::findById(array("id" => $reg_id));
			$data['patient']	= $patient 		= Patients::findById(array("id" => $reg['patient_id']));
			//$data['version_id']	= $version_id	= 0;
			$data['main_tbl'] 	= $main_tbl 	= Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$data['photo'] 		= $patient_photo= Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			
			if($post['version_id'] == 'NaN'){
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
				$data['version_id'] = 0;
			}

			//$this->get_regimen_details($main_tbl,$reg_id);
			$data['old_version'] = Regimen_Med_Main::findByRegimenID(array("regimen_id" => $reg_id));

			$data['latest_main'] = $latest_main =Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $v_id));
			$data['latest_reg_version'] = $latest_version = Regimen_Med_List::findByRegimenIDRowID(array("row_id" => $latest_main['id']));
			//debug_array($latest_main);

			$this->get_regimen_details($latest_main,$reg_id);

			$data['latest_version'] = $latest 	= Regimen_Version::findLatest(array("regimen_id" => $reg_id));
			// debug_array($latest);
			$data['versions'] 	= $version_tbls = Regimen_Version::findByRegimenId(array("regimen_id" => $reg_id));

			// debug_array($version_tbls);
			$data['meds']		= $meds 		= $_SESSION['view_regimen_meds'];
			$data['rc_reg']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));
			$data['versioning']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 26));
			$data['invoicing']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 22));
			$this->load->view('regimen/view_regimen', $data);
		}
	}

	function loadViewVersion(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$post 		= $this->input->post();
		$session 	= $this->session->all_userdata();
		
		unset($_SESSION['view_regimen_meds']);
		$this->session->unset_userdata("view_regimen_meds");

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$version_id = $post['version_id'];
		$version 	= Regimen_Version::findById(array("id" => $version_id));
		$data['rc_reg']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));
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
		
		$this->load->view('regimen/view_version', $data);
	}

	function getPatientDetails(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		if($post){
			$patient_id 	= $post['patient_id'];
			$patient 		= Patients::findById(array("id" => $patient_id));
			$patient_photo 	= Patient_Photos::findbyId(array("patient_id" => $patient_id));
			if($patient){
				$json['output'] = array(
					"patient_code" 	=> $patient['patient_code'],
					"base_path" 	=> $patient_photo['base_path'],
					"filename" 		=> $patient_photo['filename'],
					"extension" 	=> $patient_photo['extension'],
					"credit"		=> $patient['credit'] == NULL ? '0.00' : $patient['credit'],
					"age"			=> $patient['age'] .' yrs.old'
					);
			}
			echo json_encode($json);
		}
	}

	function loadMedicineTable(){
		Engine::XmlHttpRequestOnly();
		$meds = $_SESSION["regimen_meds"];
		// $meds = $this->session->userdata('regimen_meds');
		if($meds){
			$data['meds'] = $meds;
		}

		// debug_array($meds);
		$this->load->view('regimen/forms/medicine_table',$data);
	}

	function loadMedicineTable2(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			unset($_SESSION['view_regimen_meds']);
			$data['reg_id'] 	= $reg_id = $post['id'];
			$data['version_id'] = $version_id = $post['version_id'];
			$data['main_tbl'] 	= $main_tbl = Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$this->get_regimen_details($main_tbl,$reg_id);
			$data['meds'] 		= $meds = $_SESSION["view_regimen_meds"];

			// debug_array($_SESSION["view_regimen_meds"]);
		}
		$this->load->view('regimen/forms/medicine_table2',$data);
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

	function loadMedicineFormforNewRow(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$regimen_id 	= (int) $post['regimen_id'];
			$version_id 	= (int) $post['version_id'];
			if($regimen_id && $version_id >= "0"){
				$data['regimen_id']		= $regimen_id;
				$data['version_id']		= $version_id;
			}
			$data['patient_code'] 	= $patient_code = $post['patient_code'];
			$data['tab_form'] 	  	= $tab_form 	= $post['form'];
			$data['medicines'] 		= $medicines 	= Inventory::findAllMedicines();

			// debug_array($medicines);
			$this->load->view('regimen/forms/add_new_row_medicine',$data);
		}
	}

	function loadMedicineFormforEditRow(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$data['regimen_id'] = $regimen_id = $post['regimen_id'];
			$data['temp_id'] 	= $temp_id = $post['id'];
			$record = $_SESSION['view_regimen_meds'];
			// debug_array($record);
			if($record){
				$data['medicines'] 	= $medicines = Inventory::findAllMedicines();
				$data['record']		= $record[$temp_id];
				$data['bf']			= $record[$temp_id]["breakfast"];
				$data['lunch']		= $record[$temp_id]["lunch"];
				$data['dinner']		= $record[$temp_id]["dinner"];
			}
			/*$record = Regimen_Med_Main::findById(array("id" => $post['id']));
			if($record){
				$data['record'] = $record;
				$data['bf'] 	= $bf	= Regimen_Med_List::findByRegimenIdRowId(array("regimen_id" => $reg_id, "row_id" => $record['id'], "meal_type" => "bf"));
				$data['lunch'] 	= $lun  = Regimen_Med_List::findByRegimenIdRowId(array("regimen_id" => $reg_id, "row_id" => $record['id'], "meal_type" => "lunch"));
				$data['dinner']	= $dine = Regimen_Med_List::findByRegimenIdRowId(array("regimen_id" => $reg_id, "row_id" => $record['id'], "meal_type" => "dinner"));
				$this->load->view('regimen/forms/edit_row_medicine',$data);
			}*/
			$this->load->view('regimen/forms/edit_row_medicine',$data);
		}
	}

	function loadMedicineFormforEditRowSession(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$session_id = $post['id'];
			// $session = $this->session->userdata("regimen_meds");
			$session = $_SESSION["regimen_meds"];
			if($session){
				$data['medicines'] 	= $medicines = Inventory::findAllMedicines();
				$data['record'] 	= $session[$session_id];
				$data['bf']	  		= $bf	= $session[$session_id]["params"]["breakfast"];
				$data['lunch']		= $lun  = $session[$session_id]["params"]["lunch"];
				$data['dinner']		= $dine = $session[$session_id]["params"]["dinner"];
				$data['session_id'] = $session_id;
				$this->load->view('regimen/forms/edit_row_medicine_sess',$data);
			}
			
		}
	}

	function addRegimen(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session_user = $this->session->all_userdata();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);

		if($post){
			if($post['regimen_id']){
				// debug_array($post);
				if($post['version_name']){
					$regimen_id = $post['regimen_id'];
					if($post['version_id']){
						$version_id = $post['version_id'];

						$version = array(
							"regimen_id" 		=> $regimen_id,
							"version_name" 		=> $post['version_name'],
							"version_remarks" 	=> $post['version_remarks'],
							"date_generated"	=> $post['date_generated'],
							"start_date"		=> $post['from_duration_date'],
							"end_date"			=> $post['to_duration_date'],
							"regimen_notes" 	=> $post['regimen_notes'],
							"lmp" 				=> $post['LMP'],
							"program"			=> $post['program'],
							"preferences" 		=> $post['preference'],
							"status"			=> "Active"
						);

						Regimen_Version::save($version, $version_id);
						$json['regimen_id']	 	= $regimen_id;
						$json['version_id']	 	= $version_id;
						$json['is_successful'] 	= true;
						$regimen_new 			= Regimen::findById(array("id" => $regimen_id));
						$regimen_number			= $regimen_new['regimen_number'];
						$json['message'] 		= "Successfully Updated Version: {$post['version_name']} in Regimen: {$regimen_number}.";
						
						$act_tracker = array(
							"module"		=> "rpc_regimen",
							"user_id"		=> $session_id,
							"entity_id"		=> $post['regimen_id'],
							"message_log" 	=> $session_user['name'] ." ". "Successfully Updated Version: {$post['version_name']} in Regimen: {$regimen_number}.",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
						Activity_Tracker::save($act_tracker);

						/*$json['notif_title'] 	= "Updated " . $post['version_name'];
						$json['notif_type']		= "info";
						$json['notif_message']	= $session_user['name'] . " has Successfully Updated Version: {$post['version_name']} in Regimen: {$regimen_number}. ";*/

						//New Notification
						$msg = $session_user['name'] . " has successfully Updated Version: {$post['version_name']} in Regimen {$regimen_number}.";

						$this->pusher->trigger('my_notifications', 'notification', array(
							'message' => $msg,
							'title' => "Updated Regimen Version " . $post['version_name'],
							'type' => 'info'
						));


					} else {
						$version = array(
						"regimen_id" 		=> $regimen_id,
						"version_name" 		=> $post['version_name'],
						"version_remarks" 	=> $post['version_remarks'],
						"date_generated"	=> $post['date_generated'],
						"start_date"		=> $post['from_duration_date'],
						"end_date"			=> $post['to_duration_date'],
						"lmp" 				=> $post['LMP'],
						"program"			=> $post['program'],
						"regimen_notes" 	=> $post['regimen_notes'],
						"preferences" 		=> $post['preference'],
						"status"			=> "Active"
						);

					$version_id = Regimen_Version::save($version);

					$regimen = $_SESSION["regimen_meds"];
						foreach ($regimen as $key => $value) {
							$start_date = $value['start_date'];
							$end_date 	= $value['end_date'];

							$arr = array(
								"regimen_id" 		=> $regimen_id,
								"start_date" 		=> $start_date,
								"end_date"	 		=> $end_date,
								"bf_instructions"	=> ($value['bf_special_instructions'] ? $value['bf_special_instructions'] : $value['bf_instructions']),
								"l_instructions"	=> ($value['l_special_instructions'] ? $value['l_special_instructions'] : $value['l_instructions']),
								"d_instructions"	=> ($value['d_special_instructions'] ? $value['d_special_instructions'] : $value['d_instructions']),
								"version_id"		=> $version_id
								);
							$row_id = Regimen_Med_Main::save($arr);

							foreach ($value['params']['breakfast'] as $a=>$b) {

								foreach ($b['med_ops'] as $x => $z) {
									$medicine = Inventory::findById(array("id" => $z['medicine_id']));
									if($medicine){
										$breakfast = array(
											"regimen_id" 	=> $regimen_id,
											"row_id" 		=> $row_id,
											"start_date"	=> $start_date,
											"end_date"		=> $end_date,
											"medicine_id"	=> $z['medicine_id'],
											"medicine_name"	=> $z['medicine_name'],
											"quantity"		=> $z['quantity'],
											"quantity_type" => $z['quantity_type'],
											"quantity_val" 	=> $z['quantity_val'],
											"activity"		=> $b['activity'],
											"meal_type"		=> "bf",
											"version_id"	=> $version_id
											);
										Regimen_Med_List::save($breakfast);
									}
								}
								
							}

							foreach ($value['params']['lunch'] as $c=>$d) {

									foreach ($d['med_ops'] as $x => $z) {
										$medicine = Inventory::findById(array("id" => $z['medicine_id']));
										if($medicine){
											$lunch = array(
												"regimen_id" 	=> $regimen_id,
												"row_id" 		=> $row_id,
												"start_date"	=> $start_date,
												"end_date"		=> $end_date,
												"medicine_id"	=> $z['medicine_id'],
												"medicine_name"	=> $z['medicine_name'],
												"quantity"		=> $z['quantity'],
												"quantity_type" => $z['quantity_type'],
												"quantity_val" 	=> $z['quantity_val'],
												"activity"		=> $d['activity'],
												"meal_type"		=> "lunch",
												"version_id"	=> $version_id
											);
											Regimen_Med_List::save($lunch);
										}
									}
							}

							foreach ($value['params']['dinner'] as $e=>$f) {
									foreach ($f['med_ops'] as $x => $z) {
										$medicine = Inventory::findById(array("id" => $z['medicine_id']));
										if($medicine){
											$dinner = array(
												"regimen_id" 	=> $regimen_id,
												"row_id" 		=> $row_id,
												"start_date"	=> $start_date,
												"end_date"		=> $end_date,
												"medicine_id"	=> $z['medicine_id'],
												"medicine_name"	=> $z['medicine_name'],
												"quantity"		=> $z['quantity'],
												"quantity_type" => $z['quantity_type'],
												"quantity_val" 	=> $z['quantity_val'],
												"activity"		=> $f['activity'],
												"meal_type"		=> "dinner",
												"version_id"	=> $version_id
											);
											Regimen_Med_List::save($dinner);
										}
									}
							}
						}

						unset($_SESSION["regimen_meds"]);
						$version_name 			= $post['version_name'];
						$regimen_new 			= Regimen::findById(array("id" => $regimen_id));
						$regimen_number			= $regimen_new['regimen_number'];
						$json['regimen_id']	 	= $regimen_id;
						$json['version_id']	 	= $version_id;
						$json['is_successful'] 	= true;
						$json['message'] 		= "Successfully Created New Version, {$version_name}, in database";

						$act_tracker = array(
							"module"		=> "rpc_regimen",
							"user_id"		=> $session_id,
							"entity_id"		=> $regimen_id,
							"message_log" 	=> $session_user['name'] ." ". "Created New Version {$version_name} in Regimen: {$regimen_number}",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
						Activity_Tracker::save($act_tracker);

						// $json['notif_title'] 	= "Created " . $post['version_name'];
						// $json['notif_type']		= "info";
						// $json['notif_message']	= $session_user['name'] . " has Successfully Created Version: {$version_name} in Regimen {$regimen_number}. ";
						
						//New Notification
						$msg = $session_user['name'] . " has successfully Created Version: {$post['version_name']} in Regimen {$regimen_number}.";

						$this->pusher->trigger('my_notifications', 'notification', array(
							'message' => $msg,
							'title' => "Created Regimen Version " . $post['version_name'],
							'type' => 'info'
						));
					}
				} else {
					$regimen_id   = $post['regimen_id'];
					$patient_id   = $post['patient_id'];
					$version_id	  = $post['version_id'];
					$patient_info = Patients::findById(array("id" => $patient_id));
					
					$name 	= $patient_info['patient_name'];
					$first 	= Regimen_Med_Main::findByFirstDate(array("regimen_id" => $regimen_id));
					$last 	= Regimen_Med_Main::findByLastDate(array("regimen_id" => $regimen_id));
					$record = array(
						"patient_id" 		=> $patient_id,
						"patient_name" 		=> $name,
						"date_generated" 	=> $post['date_generated'],
						"regimen_notes" 	=> $post['regimen_notes'],
						"preferences" 		=> $post['preference'],
						"regimen_duration"  => $post['from_duration_date'] . " - " . $post['to_duration_date'],
						"start_date"		=> $post['from_duration_date'],
						"end_date"			=> $post['to_duration_date'],
						"lmp" 				=> $post['LMP'],
						"program"			=> $post['program'],
						"status"		    => $post['status'],
						);
					Regimen::save($record,$regimen_id);
					$data = Regimen::findById(array("id" => $regimen_id));
					$regimen_number = $data['regimen_number'];
					//$this->session->unset_userdata('view_regimen_meds');
					unset($_SESSION["view_regimen_meds"]);
					$json['regimen_id']	 	= $regimen_id;
					$json['version_id']	 	= $version_id;
					$json['is_successful'] 	= true;
					$json['message'] 		= "Successfully Updated Regimen {$regimen_number} for {$name} in database";

					$act_tracker = array(
						"module"		=> "rpc_regimen",
						"user_id"		=> $session_id,
						"entity_id"		=> $post['regimen_id'],
						"message_log" 	=> $session_user['name'] ." ". "Successfully Updated {$regimen_number}",
						"date_created" 	=> date("Y-m-d H:i:s"),
					);
					Activity_Tracker::save($act_tracker);

					/*$json['notif_title'] 	= "Updated " . $regimen_number;
					$json['notif_type']		= "info";
					$json['notif_message']	= $session_user['name'] . " has Successfully Updated Regimen {$regimen_number} for {$name}. ";*/

					//New Notification
					$msg = $session_user['name'] . " has successfully Updated Regimen: {$regimen_number} for {$name}.";

					$this->pusher->trigger('my_notifications', 'notification', array(
						'message' => $msg,
						'title' => "Updated Regimen " . $regimen_number,
						'type' => 'info'
					));

				}
			} else {
				$patient_id   	= $post['patient_id'];
				$patient_info 	= Patients::findById(array("id" => $patient_id));
				$regimen_number = Regimen::generateNewRegimenNumber();
				$name 		  	= $patient_info['patient_name'];
				$record = array(
					"patient_id" 		=> $patient_id,
					"patient_name" 		=> $name,
					"regimen_number"	=> $regimen_number,
					"date_generated" 	=> $post['date_generated'],
					"regimen_notes" 	=> $post['regimen_notes'],
					"preferences" 		=> $post['preference'],
					"regimen_duration"  => $post['from_duration_date'] . " - " . $post['to_duration_date'],
					"start_date"		=> $post['from_duration_date'],
					"end_date"			=> $post['to_duration_date'],
					"lmp" 				=> $post['LMP'],
					"program"			=> $post['program'],
					"status"		    => $post['status'],
					);
				$regimen_id = Regimen::save($record);

				// $regimen = $this->session->userdata('regimen_meds');
				$regimen = $_SESSION["regimen_meds"];

				foreach ($regimen as $key => $value) {
					$start_date = $value['start_date'];
					$end_date 	= $value['end_date'];
					$version_id = 0;

					$arr = array(
						"regimen_id" 		=> $regimen_id,
						"start_date" 		=> $start_date,
						"end_date"	 		=> $end_date,
						"bf_instructions"	=> ($value['bf_special_instructions'] ? $value['bf_special_instructions'] : $value['bf_instructions']),
						"l_instructions"	=> ($value['l_special_instructions'] ? $value['l_special_instructions'] : $value['l_instructions']),
						"d_instructions"	=> ($value['d_special_instructions'] ? $value['d_special_instructions'] : $value['d_instructions']),
						);
					$row_id = Regimen_Med_Main::save($arr);

						foreach ($value['params']['breakfast'] as $a=>$b) {

							foreach ($b['med_ops'] as $x => $z) {
								$medicine = Inventory::findById(array("id" => $z['medicine_id']));
								if($medicine){
									$breakfast = array(
										"regimen_id" 	=> $regimen_id,
										"row_id" 		=> $row_id,
										"start_date"	=> $start_date,
										"end_date"		=> $end_date,
										"medicine_id"	=> $z['medicine_id'],
										"medicine_name"	=> $z['medicine_name'],
										"quantity"		=> $z['quantity'],
										"quantity_type" => $z['quantity_type'],
										"quantity_val" 	=> $z['quantity_val'],
										"activity"		=> $b['activity'],
										"meal_type"		=> "bf",
										"version_id"	=> $version_id
										);
									Regimen_Med_List::save($breakfast);
								}	
							}
							
						}

						foreach ($value['params']['lunch'] as $c=>$d) {

								foreach ($d['med_ops'] as $x => $z) {
									$medicine = Inventory::findById(array("id" => $z['medicine_id']));
									if($medicine){
										$lunch = array(
											"regimen_id" 	=> $regimen_id,
											"row_id" 		=> $row_id,
											"start_date"	=> $start_date,
											"end_date"		=> $end_date,
											"medicine_id"	=> $z['medicine_id'],
											"medicine_name"	=> $z['medicine_name'],
											"quantity"		=> $z['quantity'],
											"quantity_type" => $z['quantity_type'],
											"quantity_val" 	=> $z['quantity_val'],
											"activity"		=> $d['activity'],
											"meal_type"		=> "lunch",
											"version_id"	=> $version_id
										);
										Regimen_Med_List::save($lunch);
									}
								}
						}

						foreach ($value['params']['dinner'] as $e=>$f) {
								foreach ($f['med_ops'] as $x => $z) {
									$medicine = Inventory::findById(array("id" => $z['medicine_id']));
									if($medicine){
										$dinner = array(
											"regimen_id" 	=> $regimen_id,
											"row_id" 		=> $row_id,
											"start_date"	=> $start_date,
											"end_date"		=> $end_date,
											"medicine_id"	=> $z['medicine_id'],
											"medicine_name"	=> $z['medicine_name'],
											"quantity"		=> $z['quantity'],
											"quantity_type" => $z['quantity_type'],
											"quantity_val" 	=> $z['quantity_val'],
											"activity"		=> $f['activity'],
											"meal_type"		=> "dinner",
											"version_id"	=> $version_id
										);
										Regimen_Med_List::save($dinner);
									}
								}
						}
				}
				
				// $this->session->unset_userdata('regimen_meds');
				unset($_SESSION["regimen_meds"]);

				$act_tracker = array(
					"module"		=> "rpc_regimen",
					"user_id"		=> $session_id,
					"entity_id"		=> $regimen_id,
					"message_log" 	=> $session_user['name'] ." ". "Successfully Added {$regimen_number}",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);


				$json['regimen_id']	 	= $regimen_id;
				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added a Regimen for {$name} in database";
				/*$json['notif_title'] 	= "Updated " . $regimen_number;
				$json['notif_type']		= "info";
				$json['notif_message']	= $session_user['name'] . " has Successfully Added Regimen {$regimen_number} for {$name}. ";*/
				
				//New Notification
				$msg = $session_user['name'] . " has successfully Added Regimen: {$regimen_number} for {$name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Regimen " . $regimen_number,
					'type' => 'info'
				));
			}
		} else {
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error adding to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function addNewRowMedicine(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session_user = $this->session->all_userdata();
		$session = $this->session->userdata('user_id');
		$session_id1 = $this->encrypt->decode($session);

		#debug_array($post);
		

		if($post){
			if($post['regimen_id']){
				$row_id 	 = $post['row_id'];
				$regimen_id  = $post['regimen_id'];
				$version_id	 = $post['version_id'];
				$regimen_new 			= Regimen::findById(array("id" => $post['regimen_id']));

				if($regimen_id AND $row_id){
					$start_date = $post['start_date'];
					$end_date	= $post['expiration_date'];

					$record = array(
						"regimen_id" 		=> $regimen_id,
						"start_date" 		=> $start_date,
						"end_date"	 		=> $end_date,
						"bf_instructions"	=> $post['bf_special_instructions'],
						"l_instructions"	=> $post['l_special_instructions'],
						"d_instructions"	=> $post['d_special_instructions'],
						);
					
					Regimen_Med_Main::save($record,$row_id);

					foreach ($post['breakfast'] as $key => $value) {
						foreach ($value['med_ops'] as $a => $b) {
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							if($med){
								$breakfast 	 = array(
													"regimen_id"	=> $regimen_id,
													"row_id"		=> $row_id,
													"start_date"	=> $post['start_date'],
													"end_date"		=> $post['expiration_date'],
													"medicine_id" 	=> $medicine_id,
													"medicine_name" => $med['medicine_name'],
													"quantity" 		=> $b['quantity'],
													"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
													"quantity_val" 	=> $b['quantity_val'],
													"activity" 		=> $value['activity'],
													"meal_type"		=> "bf",
													"version_id"	=> $version_id
												);	
								if($b['id']){
									Regimen_Med_List::save($breakfast, $b['id']);
								} else {
									Regimen_Med_List::save($breakfast);
								}
							}
							
						}
					}


					foreach ($post['lunch'] as $key => $value) {

						foreach ($value['med_ops'] as $a => $b) {
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							if($med){
								$lunch = array(
									"regimen_id"	=> $regimen_id,
									"row_id"		=> $row_id,
									"start_date"	=> $post['start_date'],
									"end_date"		=> $post['expiration_date'],
									"medicine_id" 	=> $medicine_id,
									"medicine_name" => $med['medicine_name'],
									"quantity" 		=> $b['quantity'],
									"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
									"quantity_val" 	=> $b['quantity_val'],
									"activity" 		=> $value['activity'],
									"meal_type"		=> "lunch",
									"version_id"	=> $version_id
								);
								if($b['id']){
									Regimen_Med_List::save($lunch, $b['id']);
								} else {
									Regimen_Med_List::save($lunch);
								}
							}
						}
					}

					foreach ($post['dinner'] as $key => $value) {
						foreach ($value['med_ops'] as $a => $b) {
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							if($med){
								$dinner = array(
									"regimen_id"	=> $regimen_id,
									"row_id"		=> $row_id,
									"start_date"	=> $post['start_date'],
									"end_date"		=> $post['expiration_date'],
									"medicine_id" 	=> $medicine_id,
									"medicine_name" => $med['medicine_name'],
									"quantity" 		=> $b['quantity'],
									"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
									"quantity_val" 	=> $b['quantity_val'],
									"activity" 		=> $value['activity'],
									"meal_type"		=> "dinner",
									"version_id"	=> $version_id
								);
								if($b['id']){
									Regimen_Med_List::save($dinner, $b['id']);
								} else {
									Regimen_Med_List::save($dinner);
								}
							}
						}
					}
					unset($_SESSION['view_regimen_meds']);
					//$this->session->unset_userdata("view_regimen_meds");

					$act_tracker = array(
						"module"		=> "rpc_regimen",
						"user_id"		=> $session_id1,
						"entity_id"		=> $regimen_id,
						"message_log" 	=> $session_user['name'] ." ". "Successfully Updated Row Medicine in" ." ". $regimen_new['regimen_number'],
						"date_created" 	=> date("Y-m-d H:i:s"),
					);
					Activity_Tracker::save($act_tracker);

					$json['regimen_id'] 	= $regimen_id;
					$json['version_id'] 	= $version_id;
					$json['is_successful'] 	= true;
					$json['message'] 		= "Successfully Updated in database";
				
				} else {
					$start_date = $post['start_date'];
					$end_date	= $post['expiration_date'];

					$record = array(
						"regimen_id" 		=> $regimen_id,
						"start_date" 		=> $start_date,
						"end_date"	 		=> $end_date,
						"bf_instructions"	=> $post['bf_special_instructions'],
						"l_instructions"	=> $post['l_special_instructions'],
						"d_instructions"	=> $post['d_special_instructions'],
						"version_id"		=> $version_id
						);

					$row_id = Regimen_Med_Main::save($record);

					foreach ($post['breakfast'] as $key => $value) {

						foreach ($value['med_ops'] as $a => $b) {
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							if($med){
								$breakfast = array(
									"regimen_id"	=> $regimen_id,
									"row_id"		=> $row_id,
									"start_date"	=> $post['start_date'],
									"end_date"		=> $post['expiration_date'],
									"medicine_id" 	=> $medicine_id,
									"medicine_name" => $med['medicine_name'],
									"quantity" 		=> $b['quantity'],
									"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
									"quantity_val" 	=> $b['quantity_val'],
									"activity" 		=> $value['activity'],
									"meal_type"		=> "bf",
									"version_id"	=> $version_id
								);
								Regimen_Med_List::save($breakfast);
							}
						}
					}

					foreach ($post['lunch'] as $key => $value) {

						foreach ($value['med_ops'] as $a => $b) {
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							if($med){
								$lunch = array(
									"regimen_id"	=> $regimen_id,
									"row_id"		=> $row_id,
									"start_date"	=> $post['start_date'],
									"end_date"		=> $post['expiration_date'],
									"medicine_id" 	=> $medicine_id,
									"medicine_name" => $med['medicine_name'],
									"quantity" 		=> $b['quantity'],
									"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
									"quantity_val" 	=> $b['quantity_val'],
									"activity" 		=> $value['activity'],
									"meal_type"		=> "lunch",
									"version_id"	=> $version_id
								);
								Regimen_Med_List::save($lunch);
							}
						}
					}

					foreach ($post['dinner'] as $key => $value) {
						foreach ($value['med_ops'] as $a => $b) {
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							if($med){
								$dinner = array(
									"regimen_id"	=> $regimen_id,
									"row_id"		=> $row_id,
									"start_date"	=> $post['start_date'],
									"end_date"		=> $post['expiration_date'],
									"medicine_id" 	=> $medicine_id,
									"medicine_name" => $med['medicine_name'],
									"quantity" 		=> $b['quantity'],
									"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
									"quantity_val" 	=> $b['quantity_val'],
									"activity" 		=> $value['activity'],
									"meal_type"		=> "dinner",
									"version_id"	=> $version_id
								);
								Regimen_Med_List::save($dinner);
							}
						}
					}

					$json['regimen_id']		= $regimen_id;
					$json['version_id']		= $version_id;

					$regimen_new 			= Regimen::findById(array("id" => $regimen_id));
					$act_tracker = array(
						"module"		=> "rpc_regimen",
						"user_id"		=> $session_id1,
						"entity_id"		=> $regimen_id,
						"message_log" 	=> $session_user['name'] ." ". "Successfully Added Row Medicine " ." ". $regimen_new['regimen_number'],
						"date_created" 	=> date("Y-m-d H:i:s"),
					);
					Activity_Tracker::save($act_tracker);

					$json['is_successful'] 	= true;
					$json['message'] 		= "Successfully Updated in database";
				}
				
			} else {

				$session_id = $post['session_id'];

				if($session_id >= 0 AND $session_id != NULL){
					// $sess = $this->session->userdata("regimen_meds");
					$sess = $_SESSION["regimen_meds"];

					foreach ($post['breakfast'] as $key => $value) {
						$med_ops = array();
						foreach($value['med_ops'] as $a => $b){
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							$med_ops[]	 = array(
								"medicine_id" 	=> $medicine_id,
								"medicine_name" => $med['medicine_name'],
								"quantity" 		=> ($b['quantity'] ? $b['quantity'] : 1),
								"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
								"quantity_val" 	=> $b['quantity_val'],
							);
						}
						$breakfast[] = array(
							"activity" 	=> $value['activity'],
							"med_ops"	=> $med_ops
						);
					}

					foreach ($post['lunch'] as $key => $value) {
						$med_ops = array();
						foreach($value['med_ops'] as $a => $b){
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							$med_ops[]	 = array(
								"medicine_id" 	=> $medicine_id,
								"medicine_name" => $med['medicine_name'],
								"quantity" 		=> ($b['quantity'] ? $b['quantity'] : 1),
								"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
								"quantity_val" 	=> $b['quantity_val'],
							);
						}
						$lunch[] = array(
								"activity" 	=> $value['activity'],
								"med_ops"	=> $med_ops
							);
					}

					foreach ($post['dinner'] as $key => $value) {
						$med_ops = array();
						foreach($value['med_ops'] as $a => $b){
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							$med_ops[]	 = array(
								"medicine_id" 	=> $medicine_id,
								"medicine_name" => $med['medicine_name'],
								"quantity" 		=> ($b['quantity'] ? $b['quantity'] : 1),
								"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
								"quantity_val" 	=> $b['quantity_val'],
							);
						}
						$dinner[] = array(
								"activity" 	=> $value['activity'],
								"med_ops"	=> $med_ops
							);
					}
					
					$array = array(
						"start_date" 		=> $post['start_date'],
						"end_date" 	 		=> $post['expiration_date'],
						"bf_instructions"	=> $post['bf_special_instructions'],
						"l_instructions"	=> $post['l_special_instructions'],
						"d_instructions"	=> $post['d_special_instructions'],
						"params" 	 		=> array(
													"breakfast" => $breakfast,
													"lunch" 	=> $lunch,
													"dinner" 	=> $dinner
												)
					);
					$sess[$session_id] = $array;

					// $this->session->set_userdata("regimen_meds", $sess);
					$_SESSION["regimen_meds"] = $sess;

				} else {
					
					// debug_array($post);
					foreach ($post['breakfast'] as $key => $value) {
						$med_ops = array();
						foreach($value['med_ops'] as $a => $b){
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							$med_ops[]	 = array(
								"medicine_id" 	=> $medicine_id,
								"medicine_name" => $med['medicine_name'],
								"quantity" 		=> ($b['quantity'] ? $b['quantity'] : 1),
								"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
								"quantity_val" 	=> $b['quantity_val'],
							);
						}
						$breakfast[] = array(
							"activity" 	=> $value['activity'],
							"med_ops"	=> $med_ops
						);
					}

					foreach ($post['lunch'] as $key => $value) {
						$med_ops = array();
						foreach($value['med_ops'] as $a => $b){
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							$med_ops[]	 = array(
								"medicine_id" 	=> $medicine_id,
								"medicine_name" => $med['medicine_name'],
								"quantity" 		=> ($b['quantity'] ? $b['quantity'] : 1),
								"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
								"quantity_val" 	=> $b['quantity_val'],
							);
						}
						$lunch[] = array(
								"activity" 	=> $value['activity'],
								"med_ops"	=> $med_ops
							);
					}

					foreach ($post['dinner'] as $key => $value) {
						$med_ops = array();
						foreach($value['med_ops'] as $a => $b){
							$medicine_id = $b['medicine_name'];
							$med 		 = Inventory::findById(array("id" => $medicine_id));
							$med_ops[]	 = array(
								"medicine_id" 	=> $medicine_id,
								"medicine_name" => $med['medicine_name'],
								"quantity" 		=> ($b['quantity'] ? $b['quantity'] : 1),
								"quantity_type" => ($b['quantity_type'] ? "Others" : "Taken As"),
								"quantity_val" 	=> $b['quantity_val'],
							);
						}
						$dinner[] = array(
								"activity" 	=> $value['activity'],
								"med_ops"	=> $med_ops
							);
					}
					
					$array = array(
						"start_date" 		=> $post['start_date'],
						"end_date" 	 		=> $post['expiration_date'],
						"bf_instructions"	=> $post['bf_special_instructions'],
						"l_instructions"	=> $post['l_special_instructions'],
						"d_instructions"	=> $post['d_special_instructions'],
						"params" 	 		=> array(
												"breakfast" => $breakfast,
												"lunch" 	=> $lunch,
												"dinner" 	=> $dinner
											)
					);

					// debug_array($array);
					
					$test = $this->set_regimen_medicines($array);

					// debug_array($test);
				}

				$act_tracker = array(
					"module"		=> "rpc_regimen",
					"user_id"		=> $session_id1,
					"entity_id"		=> $regimen_id,
					"message_log" 	=> $session_user['name'] ." ". "Successfully Added Row Medicine " ." ". $regimen_new['regimen_number'],
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);
				
				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added in database";
			}
		} else {
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error adding to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function set_regimen_medicines($array){
		// $regimen_meds = $this->session->userdata("regimen_meds");
		$regimen_meds = $_SESSION["regimen_meds"];
		$regimen_meds[] = $array;
		/*$this->session->set_userdata('regimen_meds', $regimen_meds);*/
		$_SESSION["regimen_meds"] = $regimen_meds;
		return $regimen_meds;
	}

	function createBreakfastFields() {
	$post = $this->input->post();
		if($post)
		{
			$main_index = (int) $post['main_index'];
			$index 		= (int) $post['index'];

			$div_wrapper 			= "breakfast_wrapper_{$main_index}";
			$sub_wrapper 			= "breakfast_sub_wrapper_{$main_index}_{$index}";

			$field_activity			= "breakfast[{$main_index}][activity]";
			$field_activity_id	 	= "bf_activity_{$main_index}";

			$field_medicine_name 	= "breakfast[{$main_index}][med_ops][{$index}][medicine_name]";
			$field_quantity 		= "breakfast[{$main_index}][med_ops][{$index}][quantity]";
			$field_quantity_type 	= "breakfast[{$main_index}][med_ops][{$index}][quantity_type]";

			$taken_as 				= "bf_taken_as_{$main_index}_{$index}";
			$dosage_val				= "bf_dosage_val_{$main_index}_{$index}";

			$others 				= "bf_others_{$main_index}_{$index}";
			$quantity_val 			= "bf_quantity_val_{$main_index}_{$index}";
			$field_quantity_val 	= "breakfast[{$main_index}][med_ops][{$index}][quantity_val]";

			$medicine_source 		= "bf_medicine_source_{$main_index}_{$index}";
			$medicine_list 			= "bf_medicine_list_{$main_index}_{$index}";

			$medicines = Inventory::findAllMedicines();
			   

			   $object['html'] .= "
			    <div class='line03 {$div_wrapper}' style='width:625px;'></div>
				<section class='clear {$div_wrapper}' ></section>
				<ul id='form' class='{$div_wrapper}'>
					<li>Activity: </li>
					<li><input type='text' id='{$field_activity_id}' name='{$field_activity}' class='textbox' style='width: 250px;'></li>
				</ul>
				<section class='clear {$div_wrapper}'></section>
			   <ul id='form' class='{$div_wrapper}'>
					<li>Medicine Name: <span>*</span></li>
					<li>
						<script>
							$(function() {
								var opts=$('#{$medicine_source}').html(), opts2='<option></option>'+opts;
							    $('#{$medicine_list}').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
							    $('#{$medicine_list}').select2({allowClear: true});
							});
						</script>
						<script>
							$('#{$medicine_list}').on('change', function(){ getMedicineQuantityType({$main_index}, {$index},'bf');} );
							$('#{$others}').live('click', function() { $('#{$quantity_val}').focus(); });
							$('#{$taken_as}').live('click', function() { $('#{$quantity_val}').val(''); });
							$('#{$quantity_val}').on('change', function() { $('#{$others}').attr('checked',true); });
						</script>
						<select id='{$medicine_list}' name='{$field_medicine_name}' class='populate add_returns_trigger' style='width:250px;'></select>
						<select id='{$medicine_source}' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";

								  foreach($medicines as $key=>$value):
								  	$dosage 	= Dosage_Type::findById(array("id" => $value['dosage_type']));
								  	$quantity 	= Quantity_Type::findById(array("id" => $value['quantity_type']));
								  	$object['html'] .= '
								 	 <option value="' . $value['id'] . '">' . $value['medicine_name'] . '</option>
								  ';
								    
								  endforeach;

				$object['html'] .= "
						</select>
						&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-success' onclick='javascript: createSubBreakfastFields(\"{$main_index}\");' style='height: 20px;'><i class='glyphicon glyphicon-plus'></i></a>
						</li></ul>
						<section class='clear {$div_wrapper}'></section>
						<ul id='form' class='{$div_wrapper} '>
							<li>Quantity: </li>
							<li><input type='text' id='{$field_quantity_id}' name='{$field_quantity}' class='textbox validate[custom[onlyNumberSp]]' style='width:110px;'>&nbsp;&nbsp;<label for='taken' style='padding-right: 14px; padding-left: 10px;'>Taken As: </label><input type='text' id='{$dosage_val}' class='textbox' style='width:50px;' readonly></li>
						</ul>
						<section class='clear {$div_wrapper} '></section>
						<ul id='form' class='{$div_wrapper} '>
							<li></li>
							<li>
								<input type='checkbox' id='{$others}' name='{$field_quantity_type}' value='Others' style='float:left;margin-top:6px;'><label for='others' style='padding-left: 5px; padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='{$quantity_val}' name='{$field_quantity_val}' style='width:80px;'>
							</li>
						</ul>
						<section class='clear {$div_wrapper}'></section>
						<div id='sub_bf_wrapper_{$main_index}' class='{$div_wrapper}'></div>
						<div id='sub_bf_wrapper_loader_{$main_index}' class='{$div_wrapper}'></div>
						<section class='clear {$div_wrapper}'></section>
						<ul id='form' class='{$div_wrapper}'>
							<li><button onclick=\"javascript: deleteElementRow('{$div_wrapper}');\" class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-minus'></i> Delete</button></li>
						</ul>
						<section class='clear {$div_wrapper}' ></section>
					</ul>
				";
		   
		}
	echo json_encode($object);
	}

	function createSubBreakfastFields(){
		$post = $this->input->post();
		if($post){

			$main_index = (int) $post['main_index'];
			$index 		= (int) $post['index'];

			$div_wrapper 			= "breakfast_wrapper_{$main_index}";
			$sub_wrapper 			= "breakfast_sub_wrapper_{$main_index}_{$index}";

			$field_medicine_name 	= "breakfast[{$main_index}][med_ops][{$index}][medicine_name]";
			$field_quantity 		= "breakfast[{$main_index}][med_ops][{$index}][quantity]";
			$field_quantity_type 	= "breakfast[{$main_index}][med_ops][{$index}][quantity_type]";

			$taken_as 				= "bf_taken_as_{$main_index}_{$index}";
			$dosage_val				= "bf_dosage_val_{$main_index}_{$index}";

			$others 				= "bf_others_{$main_index}_{$index}";
			$quantity_val 			= "bf_quantity_val_{$main_index}_{$index}";
			$field_quantity_val 	= "breakfast[{$main_index}][med_ops][{$index}][quantity_val]";

			$medicine_source 		= "bf_medicine_source_{$main_index}_{$index}";
			$medicine_list 			= "bf_medicine_list_{$main_index}_{$index}";

			$medicines = Inventory::findAllMedicines();

			$object['html'] .= "
						<div class='{$sub_wrapper}'>
						<ul id='form' class='{$div_wrapper}'>
						<li>Medicine Name: <span>*</span></li>
						<li>
							<script>
								$(function() {
									var opts=$('#{$medicine_source}').html(), opts2='<option></option>'+opts;
								    $('#{$medicine_list}').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
								    $('#{$medicine_list}').select2({allowClear: true});
								});
							</script>
							<script>
								$('#{$medicine_list}').on('change', function(){ getMedicineQuantityType({$main_index}, {$index},'bf');} );
								$('#{$others}').live('click', function() { $('#{$quantity_val}').focus(); });
								$('#{$taken_as}').live('click', function() { $('#{$quantity_val}').val(''); });
								$('#{$quantity_val}').on('change', function() { $('#{$others}').attr('checked',true); });
							</script>
							<select id='{$medicine_list}' name='{$field_medicine_name}' class='populate add_returns_trigger' style='width:250px;'></select>
							<select id='{$medicine_source}' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";

									  foreach($medicines as $key=>$value):
									  	$dosage 	= Dosage_Type::findById(array("id" => $value['dosage_type']));
									  	$quantity 	= Quantity_Type::findById(array("id" => $value['quantity_type']));
									  	$object['html'] .= '
									 	 <option value="' . $value['id'] . '">' . $value['medicine_name'] . '</option>
									  ';
									    
									  endforeach;

					$object['html'] .= "
							</select>
							&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-danger' onclick='javascript: deleteElementRow(\"{$sub_wrapper}\");' style='height: 20px;'><i class='glyphicon glyphicon-minus'></i></a>
							</li></ul>
							<section class='clear {$div_wrapper}'></section>
							<ul id='form' class='{$div_wrapper}'>
								<li>Quantity: </li>
								<li><input type='text' id='{$field_quantity}' name='{$field_quantity}' class='textbox validate[custom[onlyNumberSp]]' style='width:110px;'>&nbsp;&nbsp;<label for='taken' style='padding-right: 14px; padding-left: 10px;'>Taken As: </label><input type='text' id='{$dosage_val}' class='textbox' style='width:50px;' readonly></li>
							</ul>
							<section class='clear {$div_wrapper}'></section>
							<ul id='form' class='{$div_wrapper}'>
								<li></li>
								<li>
									<input type='checkbox' id='{$others}' name='{$field_quantity_type}' value='Others' style='float:left;margin-top:6px;'><label for='others' style='padding-left: 5px; padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='{$quantity_val}' name='{$field_quantity_val}' style='width:80px;'>
								</li>
							</ul>
							<section class='clear {$div_wrapper}'></section>
						</ul>
						</div>
						<section class='clear {$sub_wrapper} {$div_wrapper}'></section>
					";
		}
		echo json_encode($object);
	}

	function createLunchFields() {
		$post = $this->input->post();
		if($post){
			$main_index = (int) $post['main_index'];   
			$index 		= (int) $post['index'];   

			$field_activity			= "lunch[{$main_index}][activity]";
			$field_activity_id	 	= "l_activity_{$main_index}";
			$div_wrapper 			= "lunch_wrapper_{$main_index}";

			$sub_wrapper 			= "lunch_sub_wrapper_{$main_index}_{$index}";
			$counter				= "lunch_sub_{$main_index}";

			$field_medicine_name 	= "lunch[{$main_index}][med_ops][{$index}][medicine_name]";
			$field_quantity 		= "lunch[{$main_index}][med_ops][{$index}][quantity]";
			$field_quantity_type 	= "lunch[{$main_index}][med_ops][{$index}][quantity_type]";

			$taken_as 				= "l_taken_as_{$main_index}_{$index}";
			$dosage_val				= "l_dosage_val_{$main_index}_{$index}";

			$field_quantity_id		= "l_quantity_{$main_index}_{$index}";
			$others 				= "l_others_{$main_index}_{$index}";
			$quantity_val 			= "l_quantity_val_{$main_index}_{$index}";
			$field_quantity_val 	= "lunch[{$main_index}][med_ops][{$index}][quantity_val]";

			$medicine_source 		= "l_medicine_source_{$main_index}_{$index}";
			$medicine_list 			= "l_medicine_list_{$main_index}_{$index}";

			$medicines = Inventory::findAllMedicines();


			   $object['html'] .= "
			    <div class='line03 {$div_wrapper}' style='width:625px;'></div>
				<section class='clear {$div_wrapper}' ></section>
				<ul id='form' class='{$div_wrapper}'>
					<li>Activity: </li>
					<li><input type='text' id='{$field_activity_id}' name='{$field_activity}' class='textbox' style='width: 250px;'></li>
				</ul>
				<section class='clear {$div_wrapper}'></section>
				<div class='{$sub_wrapper}'>
				<ul id='form' class='{$div_wrapper} '>
					<li>Medicine Name: <span>*</span></li>
					<li>
						<script>
							$(function() {
								var opts=$('#{$medicine_source}').html(), opts2='<option></option>'+opts;
							    $('#{$medicine_list}').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
							    $('#{$medicine_list}').select2({allowClear: true});
							});
						</script>
						<script>
							$('#{$medicine_list}').on('change', function(){ getMedicineQuantityType({$main_index}, {$index},'l');} );
							$('#{$others}').live('click', function() { $('#{$quantity_val}').focus(); });
							$('#{$taken_as}').live('click', function() { $('#{$quantity_val}').val(''); });
							$('#{$quantity_val}').on('change', function() { $('#{$others}').attr('checked',true); });
						</script>
						<select id='{$medicine_list}' name='{$field_medicine_name}' class='populate add_returns_trigger' style='width:250px;'></select>
						<select id='{$medicine_source}' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";

									  foreach($medicines as $key=>$value):
									  	$dosage 	= Dosage_Type::findById(array("id" => $value['dosage_type']));
									  	$quantity 	= Quantity_Type::findById(array("id" => $value['quantity_type']));
									  	$object['html'] .= '
									 	<option value="' . $value['id'] . '">' . $value['medicine_name'] . '</option>
									  ';
									    
									  endforeach;

				$object['html'] .= "
							</select>
							&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-success' onclick='javascript: createSubLunchFields(\"{$main_index}\");' style='height: 20px;'><i class='glyphicon glyphicon-plus'></i></a>
							</li>
					</ul>
					<section class='clear {$div_wrapper} '></section>
					<ul id='form' class='{$div_wrapper} '>
						<li>Quantity: </li>
						<li><input type='text' id='{$field_quantity_id}' name='{$field_quantity}' class='textbox validate[custom[onlyNumberSp]]' style='width:110px;'>&nbsp;&nbsp;<label for='taken' style='padding-right: 14px; padding-left: 10px;'>Taken As: </label><input type='text' id='{$dosage_val}' class='textbox' style='width:50px;' readonly></li>
					</ul>
					<section class='clear {$div_wrapper} '></section>
					<ul id='form' class='{$div_wrapper} '>
						<li></li>
						<li>
							<input type='checkbox' id='{$others}' name='{$field_quantity_type}' value='Others' style='float:left;margin-top:6px;'><label for='others' style='padding-left: 5px; padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='{$quantity_val}' name='{$field_quantity_val}' style='width:80px;'>
						</li>
					</ul>
					<section class='clear {$div_wrapper}'></section>
				</ul>
					</div>
					<section class='clear {$div_wrapper}'></section>
					<div id='sub_l_wrapper_{$main_index}' class='{$div_wrapper}'></div>
					<div id='sub_l_wrapper_loader_{$main_index}' class='{$div_wrapper}'></div>
					<ul id='form' class='{$div_wrapper}'>
						<li><button onclick=\"javascript: deleteElementRow('{$div_wrapper}');\" class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-minus'></i> Delete</button></li>
					</ul>
					<section class='clear {$div_wrapper}' ></section>
				";
		}
		echo json_encode($object);
	}

	function createSubLunchFields(){
		$post = $this->input->post();
		if($post){

			$main_index = (int) $post['main_index'];
			$index 		= (int) $post['index'];

			$div_wrapper 			= "lunch_wrapper_{$main_index}";
			$sub_wrapper 			= "lunch_sub_wrapper_{$main_index}_{$index}";

			$field_medicine_name 	= "lunch[{$main_index}][med_ops][{$index}][medicine_name]";
			$field_quantity 		= "lunch[{$main_index}][med_ops][{$index}][quantity]";
			$field_quantity_type 	= "lunch[{$main_index}][med_ops][{$index}][quantity_type]";

			$taken_as 				= "l_taken_as_{$main_index}_{$index}";
			$dosage_val				= "l_dosage_val_{$main_index}_{$index}";

			$field_quantity_id		= "l_quantity_{$main_index}_{$index}";
			$others 				= "l_others_{$main_index}_{$index}";
			$quantity_val 			= "l_quantity_val_{$main_index}_{$index}";
			$field_quantity_val 	= "lunch[{$main_index}][med_ops][{$index}][quantity_val]";

			$medicine_source 		= "l_medicine_source_{$main_index}_{$index}";
			$medicine_list 			= "l_medicine_list_{$main_index}_{$index}";

			$medicines = Inventory::findAllMedicines();

			$object['html'] .= "
					<div class='{$sub_wrapper}'>
					<ul id='form' class='{$div_wrapper} '>
					<li>Medicine Name: <span>*</span></li>
					<li>
						<script>
							$(function() {
								var opts=$('#{$medicine_source}').html(), opts2='<option></option>'+opts;
							    $('#{$medicine_list}').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
							    $('#{$medicine_list}').select2({allowClear: true});
							});
						</script>
						<script>
							$('#{$medicine_list}').on('change', function(){ getMedicineQuantityType({$main_index}, {$index},'l');} );
							$('#{$others}').live('click', function() { $('#{$quantity_val}').focus(); });
							$('#{$taken_as}').live('click', function() { $('#{$quantity_val}').val(''); });
							$('#{$quantity_val}').on('change', function() { $('#{$others}').attr('checked',true); });
						</script>
						<select id='{$medicine_list}' name='{$field_medicine_name}' class='populate add_returns_trigger' style='width:250px;'></select>
						<select id='{$medicine_source}' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";

								  foreach($medicines as $key=>$value):
								  	$dosage 	= Dosage_Type::findById(array("id" => $value['dosage_type']));
								  	$quantity 	= Quantity_Type::findById(array("id" => $value['quantity_type']));
								  	$object['html'] .= '
								 	<option value="' . $value['id'] . '">' . $value['medicine_name'] . '</option>
								  ';
								    
								  endforeach;

				$object['html'] .= "
						</select>
						&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-danger' onclick='javascript: deleteElementRow(\"{$sub_wrapper}\");' style='height: 20px;'><i class='glyphicon glyphicon-minus'></i></a>
						</li></ul>
						<section class='clear {$div_wrapper} '></section>
						<ul id='form' class='{$div_wrapper} '>
							<li>Quantity: </li>
							<li><input type='text' id='{$field_quantity_id}' name='{$field_quantity}' class='textbox validate[custom[onlyNumberSp]]' style='width:110px;'>&nbsp;&nbsp;<label for='taken' style='padding-right: 14px; padding-left: 10px;'>Taken As: </label><input type='text' id='{$dosage_val}' class='textbox' style='width:50px;' readonly></li>
						</ul>
						<section class='clear {$div_wrapper} '></section>
						<ul id='form' class='{$div_wrapper} '>
							<li></li>
							<li>
								<input type='checkbox' id='{$others}' name='{$field_quantity_type}' value='Others' style='float:left;margin-top:6px;'><label for='others' style='padding-left: 5px; padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='{$quantity_val}' name='{$field_quantity_val}' style='width:80px;'>
							</li>
						</ul>
						<section class='clear {$div_wrapper}'></section>
					</ul>
					</div>
				";
		}
		echo json_encode($object);
	}

	function createDinnerFields() {
		$post = $this->input->post();
		if($post){
			$main_index = (int) $post['main_index'];
			$index 		= (int) $post['index'];

			$div_wrapper 			= "dinner_wrapper_{$main_index}";
			$field_activity			= "dinner[{$main_index}][activity]";

			$sub_wrapper 			= "dinner_sub_wrapper_{$main_index}_{$index}";

			$field_medicine_name 	= "dinner[{$main_index}][med_ops][{$index}][medicine_name]";
			$field_quantity 		= "dinner[{$main_index}][med_ops][{$index}][quantity]";
			$field_quantity_type 	= "dinner[{$main_index}][med_ops][{$index}][quantity_type]";

			$taken_as 				= "d_taken_as_{$main_index}_{$index}";
			$dosage_val				= "d_dosage_val_{$main_index}_{$index}";

			$others 				= "d_others_{$main_index}_{$index}";
			$quantity_val 			= "d_quantity_val_{$main_index}_{$index}";
			$field_quantity_val 	= "dinner[{$main_index}][med_ops][{$index}][quantity_val]";

			$medicine_source 		= "d_medicine_source_{$main_index}_{$index}";
			$medicine_list 			= "d_medicine_list_{$main_index}_{$index}";

			$medicines = Inventory::findAllMedicines();


			   $object['html'] .= "
			    <div class='line03 {$div_wrapper}' style='width:625px;'></div>
				<section class='clear {$div_wrapper}' ></section>
				<ul id='form' class='{$div_wrapper}'>
					<li>Activity: </li>
					<li><input type='text' id='{$field_activity}' name='{$field_activity}' class='textbox' style='width: 250px;'></li>
				</ul>
				<section class='clear {$div_wrapper}' ></section>
				<div class='{$sub_wrapper}'>						
					<ul id='form' class='{$div_wrapper}'>
						<li>Medicine Name: <span>*</span></li>
						<li>
							<script>
								$(function() {
									var opts=$('#{$medicine_source}').html(), opts2='<option></option>'+opts;
								    $('#{$medicine_list}').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
								    $('#{$medicine_list}').select2({allowClear: true});
								});
							</script>
							<script>
								$('#{$medicine_list}').on('change', function(){ getMedicineQuantityType({$main_index}, {$index},'d');} );
								$('#{$others}').live('click', function() { $('#{$quantity_val}').focus(); });
								$('#{$taken_as}').live('click', function() { $('#{$quantity_val}').val(''); });
								$('#{$quantity_val}').on('change', function() { $('#{$others}').attr('checked',true); });
							</script>
							<select id='{$medicine_list}' name='{$field_medicine_name}' class='populate add_returns_trigger' style='width:250px;'></select>
							<select id='{$medicine_source}' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";

									  foreach($medicines as $key=>$value):
									  	$dosage 	= Dosage_Type::findById(array("id" => $value['dosage_type']));
									  	$quantity 	= Quantity_Type::findById(array("id" => $value['quantity_type']));
									  	$object['html'] .= '
									 	<option value="' . $value['id'] . '">' . $value['medicine_name'] . '</option>
									  ';
									    
									  endforeach;

					$object['html'] .= "
								</select>
								&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-success' onclick='javascript: createSubDinnerFields(\"{$main_index}\");' style='height: 20px;'><i class='glyphicon glyphicon-plus'></i></a>
							</li>
						</ul>
						<section class='clear {$div_wrapper}'></section>
						<ul id='form' class='{$div_wrapper}'>
							<li>Quantity: </li>
							<li><input type='text' id='{$field_quantity}' name='{$field_quantity}' class='textbox validate[custom[onlyNumberSp]]' style='width:110px;'>&nbsp;&nbsp;<label for='taken' style='padding-right: 14px; padding-left: 10px;'>Taken As: </label><input type='text' id='{$dosage_val}' class='textbox' style='width:50px;' readonly></li>
						</ul>
						<section class='clear {$div_wrapper}'></section>
						<ul id='form' class='{$div_wrapper}'>
							<li></li>
							<li>
								<input type='checkbox' id='{$others}' name='{$field_quantity_type}' value='Others' style='float:left;margin-top:6px;'><label for='others' style='padding-left: 5px; padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='{$quantity_val}' name='{$field_quantity_val}' style='width:80px;'>
							</li>
						</ul>
						<section class='clear {$div_wrapper}'></section>
						<div id='sub_d_wrapper_{$main_index}' class='{$div_wrapper}'></div>
						<div id='sub_d_wrapper_loader_{$main_index}' class='{$div_wrapper}'></div>
						<section class='clear {$div_wrapper}'></section>
						<ul id='form' class='{$div_wrapper}'>
							<li><button onclick=\"javascript: deleteElementRow('{$div_wrapper}');\" class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-minus'></i> Delete</button></li>
						</ul>
						<section class='clear {$div_wrapper}' ></section>
					";			   

		}
		echo json_encode($object);
	}

	function createSubDinnerFields(){
		$post = $this->input->post();
		if($post){

			$main_index = (int) $post['main_index'];
			$index 		= (int) $post['index'];

			$div_wrapper 			= "dinner_wrapper_{$main_index}";
			$sub_wrapper 			= "dinner_sub_wrapper_{$main_index}_{$index}";

			$field_medicine_name 	= "dinner[{$main_index}][med_ops][{$index}][medicine_name]";
			$field_quantity 		= "dinner[{$main_index}][med_ops][{$index}][quantity]";
			$field_quantity_type 	= "dinner[{$main_index}][med_ops][{$index}][quantity_type]";

			$taken_as 				= "d_taken_as_{$main_index}_{$index}";
			$dosage_val				= "d_dosage_val_{$main_index}_{$index}";

			$others 				= "d_others_{$main_index}_{$index}";
			$quantity_val 			= "d_quantity_val_{$main_index}_{$index}";
			$field_quantity_val 	= "dinner[{$main_index}][med_ops][{$index}][quantity_val]";

			$medicine_source 		= "d_medicine_source_{$main_index}_{$index}";
			$medicine_list 			= "d_medicine_list_{$main_index}_{$index}";

			$medicines = Inventory::findAllMedicines();

			$object['html'] .= "
						<div class='{$sub_wrapper}'>
						<ul id='form' class='{$div_wrapper}'>
						<li>Medicine Name: <span>*</span></li>
						<li>
							<script>
								$(function() {
									var opts=$('#{$medicine_source}').html(), opts2='<option></option>'+opts;
								    $('#{$medicine_list}').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
								    $('#{$medicine_list}').select2({allowClear: true});
								});
							</script>
							<script>
								$('#{$medicine_list}').on('change', function(){ getMedicineQuantityType({$main_index}, {$index},'d');} );
								$('#{$others}').live('click', function() { $('#{$quantity_val}').focus(); });
								$('#{$taken_as}').live('click', function() { $('#{$quantity_val}').val(''); });
								$('#{$quantity_val}').on('change', function() { $('#{$others}').attr('checked',true); });
							</script>
							<select id='{$medicine_list}' name='{$field_medicine_name}' class='populate add_returns_trigger' style='width:250px;'></select>
							<select id='{$medicine_source}' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";

									  foreach($medicines as $key=>$value):
									  	$dosage 	= Dosage_Type::findById(array("id" => $value['dosage_type']));
									  	$quantity 	= Quantity_Type::findById(array("id" => $value['quantity_type']));
									  	$object['html'] .= '
									 	<option value="' . $value['id'] . '">' . $value['medicine_name'] . '</option>
									  ';
									    
									  endforeach;

					$object['html'] .= "
							</select>
							&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-danger' onclick='javascript: deleteElementRow(\"{$sub_wrapper}\");' style='height: 20px;'><i class='glyphicon glyphicon-minus'></i></a>
							</li></ul>
							<section class='clear {$div_wrapper}'></section>
							<ul id='form' class='{$div_wrapper}'>
								<li>Quantity: </li>
								<li><input type='text' id='{$field_quantity}' name='{$field_quantity}' class='textbox validate[custom[onlyNumberSp]]' style='width:110px;'>&nbsp;&nbsp;<label for='taken' style='padding-right: 14px; padding-left: 10px;'>Taken As: </label><input type='text' id='{$dosage_val}' class='textbox' style='width:50px;' readonly></li>
							</ul>
							<section class='clear {$div_wrapper}'></section>
							<ul id='form' class='{$div_wrapper}'>
								<li></li>
								<li>
									<input type='checkbox' id='{$others}' name='{$field_quantity_type}' value='Others' style='float:left;margin-top:6px;'><label for='others' style='padding-left: 5px; padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='{$quantity_val}' name='{$field_quantity_val}' style='width:80px;'>
								</li>
							</ul>
							<section class='clear {$div_wrapper}'></section>
						</ul>
						</div>
						<section class='clear {$div_wrapper}'></section>
					";
		}
		echo json_encode($object);
	}

	
	function deleteRegimenRecord(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$reg_id = $post['id'];
		$record = Regimen::findById(array("id" => $reg_id));
		if($record){
			$data['record'] = $record;
			$this->load->view("regimen/forms/delete_regimen", $data);
		}

	}

	function deleteRegimenVersionRecord(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$version_id = $post['version_id'];
		$version 	= Regimen_Version::findById(array("id" => $version_id));
		if($version){
			$data['version'] = $version;
			$this->load->view("regimen/forms/delete_version_regimen", $data);
		}
	}

	function executeRegimenVersionDelete(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session_user = $this->session->all_userdata();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);

		$version_id = $post['version_id'];
		$version 	= Regimen_Version::findById(array("id" => $version_id));
		if($version){
			Regimen_Med_Main::deleteByVersionID(array("version_id" => $version_id));
			Regimen_Med_List::deleteByVersionID(array("version_id" => $version_id));
			Regimen_Version::delete($version_id);

			$act_tracker = array(
				"module"		=> "rpc_regimen",
				"user_id"		=> $session_id,
				"entity_id"		=> $post['regimen_id'],
				"message_log" 	=> $session_user['name'] ." ". "Successfully Deleted Version: {$version['version_name']} in Regimen: {$record['regimen_number']}.",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['regimen_id']		= $version['regimen_id'];
			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted in database";
		} else {
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}

		echo json_encode($json);
		
	}

	function deletePerRecord(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$id 	= $post['id'];
		$record = Regimen_Med_List::findById(array("id" => $id));
		if($record){
			Regimen_Med_List::delete($id);
		}
	}

	function executeRegimenRecord(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session_user = $this->session->all_userdata();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);

		$reg_id = $post['reg_id'];
		if($reg_id){
			$record = Regimen::findById(array("id" => $reg_id));
			if ($record) {
				Regimen::delete($reg_id);
				Regimen_Med_Main::deleteByRegimenID(array("regimen_id" => $reg_id));
				Regimen_Med_List::deleteByRegimenID(array("regimen_id" => $reg_id));
				Regimen_Version::deleteByRegimenID(array("regimen_id" => $reg_id));

				$act_tracker = array(
					"module"		=> "rpc_regimen",
					"user_id"		=> $session_id,
					"entity_id"		=> $reg_id,
					"message_log" 	=> $session_user['name'] ." ". "Successfully Deleted Regimen {$record['regimen_number']}.",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Deleted in database";
			}
		}else{
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}


	function deleteMedicineEntry(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$reg_id = $post['id'];
		$record = Regimen_Med_Main::findById(array("id" => $reg_id));

		// debug_array($reg_id);
		if($record){
			$data['record'] = $record;
			$this->load->view("regimen/forms/delete_row_medicine", $data);
		}
	}

	function deleteMultiMedicineEntry(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$counter 	= $post['counter'];
		$meal_type 	= $post['meal_type'];
		$temp_id	= $post['temp_id'];
		// debug_array($_SESSION['view_regimen_meds']);
		
		$record = $_SESSION['view_regimen_meds'][$temp_id][$meal_type][$counter];
		// debug_array($record);
		if($record){
			foreach ($record['med_ops'] as $key => $value) {
				Regimen_Med_List::delete($value['id']);
			}
		}
	}

	function deleteMedicineEntrySession(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session_id = $post['id'];
		if($session_id >= 0){
			$data['session_id'] = $session_id;
			$this->load->view("regimen/forms/delete_row_medicine_sess", $data);
		}
	}

	function executeDeleteSessionRecord(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session_id = $post['session_id'];
		if($session_id >= 0){
			// $session = $this->session->userdata("regimen_meds");
			$session = $_SESSION["regimen_meds"];
			unset($session[$session_id]);
			// $this->session->set_userdata('regimen_meds', $session);	
			$_SESSION["regimen_meds"] = $session;
			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted in database";
		}else{
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}

	function delete_entry(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session_user = $this->session->all_userdata();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);

		$reg_id = $post['reg_id'];
		if($reg_id){
			$record = Regimen_Med_Main::findById(array("id" => $reg_id));
			if ($record) {
				Regimen_Med_Main::delete($reg_id);
				Regimen_Med_LIst::deleteByRowId(array("row_id" => $reg_id));

				$reg			= Regimen::findById(array("id" => $reg_id));
				$act_tracker = array(
					"module"		=> "rpc_regimen",
					"user_id"		=> $session_id,
					"entity_id"		=> $record['regimen_id'],
					"message_log" 	=> $session_user['name'] ." ". "Successfully Deleted Row Medicine Record in {$reg['regimen_number']}",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);


				$json['regimen_id']		= $record['regimen_id'];
				$json['version_id']		= $record['version_id'];
				$json['is_successful'] 	= true;
				// $json['message'] 		= "Successfully Deleted in database";
			}
		}else{
			$json['is_successful'] = false;
			$json['message'] = "Ooop! Error deleting to database. Please contact web administrator!";
		}
		
		echo json_encode($json);
	}

	function getAllRegimenRecordList(){
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$im_list		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 15));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			// $order_type	= strtoupper($get['sSortDir_0']);
			$order_type	= strtoupper("desc");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "regimen_number",
				1 => "patient_name",
				2 => "date_generated",
				3 => "regimen_duration",
				// 3 => "year",
				4 => "status",
				5 => "id"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$regimen 		= Regimen::generateRegimenDatatable($params);
			$total_records 	= Regimen::countRegimenDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($regimen as $key=>$value):


				if($im_list['can_update'] && $im_list['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_regimen(' . $value['id'] . ', 0);" class="edit_regimen table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_regimen('.$value['id'].');" class="delete_regimen table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($im_list['can_update'] && !$im_list['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_regimen(' . $value['id'] . ', 0);" class="edit_regimen table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$im_list['can_update'] && $im_list['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_regimen('.$value['id'].')" class="delete_regimen table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}

				// $action_link = '
				// 	<a href="javascript: void(0);" onClick="javascript: edit_regimen(' . $value['id'] . ');" class="edit_regimen table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
				// 	<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
				// 	<a href="javascript:void(0);" onclick="javascript: delete_regimen('.$value['id'].');" class="delete_regimen table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
				// 	';

				$pl = array(
					"regimen_id" => $value['id'],
					"version_id" => 0
					);
				$medicine_count = Regimen_Med_List::countByRegAndVersionID($pl);

				// debug_array($medicine_count);
				if($im_list['can_view']){

					if($medicine_count > 0){
						$regimen_number = "<a href='javascript: void(0);' class='regimen_status' title='Incomplete Medicine' onClick='javascript: view_regimen(" . $value['id'] . ")'>" . $value['regimen_number'] . "</a>";
						$status =  $value['status'] . '</span>';
					} else {
						$regimen_number = "<a href='javascript: void(0);' class='regimen_status' title='Complete Medicine' onClick='javascript: view_regimen(" . $value['id'] . ")'>" . $value['regimen_number'] . "</a>";
						$status = $value['status'];
					}
					

				} else {
					$regimen_number = $value['regimen_number'];
				}
				
				$row = array(
					'0' => $regimen_number,
					'1' => $value['patient_name'],
					'2' => $value['date_generated'],
					'3' => $value['regimen_duration'],
					// '3' => $value['year'],
					'4' => $status,
					'5' => $action_link,
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

	function unsetSession(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');

		$session = $this->session->userdata('regimen_meds');
		$view 	 = $this->session->userdata('view_regimen_meds');
		if($session){
			unset($_SESSION['regimen_meds']);
			$this->session->unset_userdata('regimen_meds');
		}
		if($view){
			unset($_SESSION['view_regimen_meds']);
			$this->session->unset_userdata('view_regimen_meds');
		}
		if($_SESSION){
			unset($_SESSION['view_regimen_meds']);
			unset($_SESSION['dupLunch']);
			unset($_SESSION['dupDinner']);
			unset($_SESSION["regimen_meds"]);
		}
	}

	function unsetMealTypes(){
		Engine::XmlHttpRequestOnly();
		unset($_SESSION['dupBreakfast']);
		unset($_SESSION['dupLunch']);
		unset($_SESSION['dupDinner']);
	}

	function duplicateDBMedicine(){
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();
		$meal_type 	= $post['meal_type'];
		if($meal_type == "breakfast"){
			$meal_type = "bf";
		}
		$meds = Regimen_Med_List::findByRegimenIDMealRowVersion(array("regimen_id" => $post['regimen_id'], "row_id"=> $post['row_id'], "version_id" => $post['version_id'], "meal_type" => $meal_type));
		if($meds){
			foreach ($meds as $key => $value) {
				$column = array(
						"regimen_id" 	=> $value['regimen_id'],
						"row_id" 		=> $value['row_id'],
						"start_date" 	=> $value['start_date'],
						"end_date" 		=> $value['end_date'],
						"medicine_id" 	=> $value['medicine_id'],
						"medicine_name" => $value['medicine_name'],
						"quantity"	 	=> $value['quantity'],
						"quantity_type" => $value['quantity_type'],
						"quantity_val" 	=> $value['quantity_val'],
						"activity" 		=> $value['activity'],
						"meal_type" 	=> $post['type'],
						"taken" 		=> 0,
						"version_id" 	=> $value['version_id'],
					);
				Regimen_Med_List::save($column);
				unset($column);
			}
			$json['is_successful'] = true;
			$json['message']	   = "Successfully Duplicated " . ($meal_type=="bf"?"Breakfast":ucwords($meal_type)) . " to " . ($post['type']=="bf"?"Breakfast":ucwords($post['type']));
		} else {
			$json['is_successful'] = false;
			$json['message']	   = "Error Duplicating medicines. Please contact your administrator.";
		}

		echo json_encode($json);
	}

	function dupicate2Breakfast(){
		Engine::XmlHttpRequestOnly();

		$post 		= $this->input->post();
		$meal_type 	= $post['meal_type'];

		$breakfast 		= $post['myData'][$meal_type];
		if($breakfast){
			$_SESSION['dupBreakfast'] = $breakfast;
			$json['is_successful'] = true;
		} else {
			$json['is_successful'] = false;
		}
		echo json_encode($json);
	}

	function loadBreakfastAddForm(){
		Engine::XmlHttpRequestOnly();
		$breakfast = $_SESSION['dupBreakfast'];
		if($breakfast){
			$data['breakfast'] = $breakfast;
		}
		$data['medicines'] = $medicines = Inventory::findAllMedicines();
		$this->load->view("regimen/forms/add_breakfast", $data);
	}

	function duplicateB2Lunch(){
		Engine::XmlHttpRequestOnly();

		$post 		= $this->input->post();
		$meal_type 	= $post['meal_type'];

		$lunch 		= $post['myData'][$meal_type];
		if($lunch){
			$_SESSION['dupLunch'] = $lunch;
			$json['is_successful'] = true;
		} else {
			$json['is_successful'] = false;
		}
		echo json_encode($json);
	}

	function loadLunchAddForm(){
		Engine::XmlHttpRequestOnly();
		$lunch = $_SESSION['dupLunch'];
		if($lunch){
			$data['lunch'] = $lunch;
		}
		$data['medicines'] = $medicines = Inventory::findAllMedicines();
		$this->load->view("regimen/forms/add_lunch", $data);
	}

	function duplicateL2Dinner(){
		Engine::XmlHttpRequestOnly();

		$post 		= $this->input->post();
		$meal_type 	= $post['meal_type'];
		$dinner 	= $post['myData'][$meal_type];

		// debug_array($dinner);
		if($dinner){
			$_SESSION['dupDinner'] = $dinner;
			$json['is_successful'] = true;
		} else {
			$json['is_successful'] = false;
		}

		echo json_encode($json);
	}

	function loadDinnerAddForm(){
		Engine::XmlHttpRequestOnly();
		$dinner = $_SESSION['dupDinner'];
		if($dinner){
			$data['dinner'] = $dinner;
		}
		$data['medicines'] = $medicines = Inventory::findAllMedicines();
		$this->load->view("regimen/forms/add_dinner", $data);
	}

	function createVersion(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$reg_id = (int) $post['reg_id'];
		$version_id = $post['version_id'];
		//debug_array($version_id);
		unset($_SESSION['view_regimen_meds']);
		$this->session->unset_userdata("view_regimen_meds");
		if($reg_id){
			/*$data['reg_id'] 	= $reg_id;
			$data['reg'] 		= $reg 			= Regimen::findById(array("id" => $reg_id));
			$data['patient']	= $patient 		= Patients::findById(array("id" => $reg['patient_id']));
			$data['main_tbl'] 	= $main_tbl 	= Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$data['photo'] 		= $patient_photo= Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			foreach ($main_tbl as $key => $value) {
				$all_ids[] = $value['version_id'];
			}
			$med_ids = array_keys(array_flip($all_ids));
			foreach ($med_ids as $key => $value) {
				$data['version'] = $version       = Regimen_Version::findById(array("id" => $value));
			}*/
			// ($main_tbl);
			//$this->getDataRegimen($main_tbl,$reg_id);
			/*$data['meds']		= $meds 		= $this->session->userdata("view_regimen_meds");*/
			//$this->load->view('regimen/forms/version_regimen', $data);

			$data['main_tbl'] 	= $main_tbl 	= Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$reg 			    = Regimen::findById(array("id" => $reg_id));
			$old_version       	= Regimen_Version::findById(array("id" => $version_id));

			$version = array(
				"regimen_id" 		=> $reg_id,
				"date_generated" 	=> date("Y-m-d"),//empty($old_version['date_generated']) ? $reg['date_generated'] : $old_version['date_generated'],
				"regimen_notes" 	=> ($version_id == 0 ? $reg['regimen_notes'] : $old_version['regimen_notes']),
				"lmp"				=> ($version_id == 0 ? $reg['lmp'] : $old_version['lmp']),
				"program"			=> ($version_id == 0 ? $reg['program'] : $old_version['program']),
				"preferences" 		=> $old_version['preferences'],
				"status"			=> "Active"
				);

			$new_version_id = Regimen_Version::save($version);

			
			foreach ($main_tbl as $key => $value) {
				$arr = array(
					"regimen_id" 		=> $value['regimen_id'],
					"start_date" 		=> $value['start_date'],
					"end_date"	 		=> $value['end_date'],
					"bf_instructions"	=> ($value['bf_special_instructions'] ? $value['bf_special_instructions'] : $value['bf_instructions']),
					"l_instructions"	=> ($value['l_special_instructions'] ? $value['l_special_instructions'] : $value['l_instructions']),
					"d_instructions"	=> ($value['d_special_instructions'] ? $value['d_special_instructions'] : $value['d_instructions']),
					"version_id"		=> $new_version_id
					);
				$row_id = Regimen_Med_Main::save($arr);

				$summary 			= Regimen_Med_List::findByRegAndVersionID(array("regimen_id" => $reg_id, "version_id" => $version_id));
			
				foreach ($summary as $key => $value1) {
					if ($value['id'] == $value1['row_id']) {
						# code...
					
						$record = array(
								"regimen_id" 	=> $value1['regimen_id'],
								"row_id" 		=> $row_id,
								"start_date" 	=> $value1['start_date'],
								"end_date" 		=> $value1['end_date'],
								"medicine_id" 	=> $value1['medicine_id'],
								"medicine_name" => $value1['medicine_name'],
								"quantity"	 	=> $value1['quantity'],
								"quantity_type" => $value1['quantity_type'],
								"quantity_val" 	=> $value1['quantity_val'],
								"activity" 		=> $value1['activity'],
								"meal_type" 	=> $value1['meal_type'],
								"taken" 		=> $value1['taken'],
								"version_id" 	=> $new_version_id,
							);
						Regimen_Med_List::save($record);
					}
				}

			}


			
			//$summary 			= Regimen_Med_LIst::findByRegAndVersionID(array("regimen_id" => $reg_id, "version_id" => $version_id));

			$regimen_id = $post['reg_id'];
			$data['version_id'] = $version_id 	 = 	$new_version_id;
			$data['reg']	  	= $reg 			 = Regimen::findById(array("id"=>$regimen_id));
			$data['version']	= Regimen_Version::findByRegimenVersionId(array("regimen_id" => $regimen_id, "version_id" => $version_id));
			$data['patient']  	= $patient  	 = Patients::findById(array("id" => $reg['patient_id']));
			$data['reg_id']	  	= $reg_id;
			$data['photo'] 	  	= $patient_photo = Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			
			$this->load->view('regimen/forms/edit_regimen',$data);
		}
	}

	function getDataRegimen($main_tbl, $reg_id){
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
				// "id"				=> $value['id'],
				"start_date"		=> $value['start_date'],
				"end_date"			=> $value['end_date'],
				"bf_instructions" 	=> $value['bf_instructions'],
				"l_instructions" 	=> $value['l_instructions'],
				"d_instructions" 	=> $value['d_instructions'],
				"params"			=> array(
											"breakfast" 		=> $breakfast,
											"lunch" 			=> $lunch,
											"dinner" 			=> $dinner
										)
				);
			$this->set_regimen_medicines($my_data);
		}
	}

	function duplicateRow(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$temp_id = $post['temp_id'];
			$record = $_SESSION["regimen_meds"];
			if($record){
				$this->set_regimen_medicines($record[$temp_id]);
			}
		}
	}

	function duplicateRowDB(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$main_id = $post['main_id'];
			$main_tbl = Regimen_Med_Main::findbyId(array("id" => $main_id));
			if($main_tbl){
				$record = array(
						"regimen_id" => $main_tbl['regimen_id'],
						"start_date" => $main_tbl['start_date'],
						"end_date" => $main_tbl['end_date'],
						"bf_instructions" => $main_tbl['bf_instructions'],
						"l_instructions" => $main_tbl['l_instructions'],
						"d_instructions" => $main_tbl['d_instructions'],
						"version_id" => $main_tbl['version_id'],
					);
				$id = Regimen_Med_Main::save($record);

				$meds = Regimen_Med_List::findByRegimenIDRowID(array("regimen_id" => $main_tbl['regimen_id'], "row_id" => $main_id));
				foreach ($meds as $key => $value) {
					$row = array(
							"regimen_id" 	=> $value['regimen_id'],
							"row_id"		=> $id,
							"start_date"	=> $value['start_date'],
							"end_date"		=> $value['end_date'],
							"medicine_id"	=> $value['medicine_id'],
							"medicine_name"	=> $value['medicine_name'],
							"quantity"		=> $value['quantity'],
							"quantity_type"	=> $value['quantity_type'],
							"quantity_val"	=> $value['quantity_val'],
							"activity"		=> $value['activity'],
							"meal_type"		=> $value['meal_type'],
							"taken"			=> 0,
							"version_id"	=> $value['version_id'],
						);
					Regimen_Med_List::save($row);
					unset($row);
				}

				$json['is_successful'] = true;
				$json['regimen_id'] = $main_tbl['regimen_id'];
				$json['version_id'] = $main_tbl['version_id'];
			} else {
				$json['is_successful'] = false;
				$json['message']	   = "Error Duplicating Row. Please Contact your administrator.";
			}
		}
		echo json_encode($json);
	}

	
	function download_pdf_summary(){
		$regimen_id = $this->uri->segment(3);
		$version_id = $this->uri->segment(4);



		if($regimen_id){
			$data['reg'] 		= $reg = Regimen::findById(array("id"=>$regimen_id));
			$data['version_id']	= $version_id;
			if($version_id != 0){
				$data['version'] = $version 	= Regimen_Version::findById(array("id" => $version_id));
				$data['filename']		= $reg['regimen_number'] . "-" . $version['version_name'] . "-" . date("Ymd") . "-" . $patient['patient_code'] . ".pdf";
			}
			$data['bf'] 		= $list_bf 		= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $regimen_id, "meal_type" => "bf", "version_id" => $version_id));
			$data['lunch'] 		= $list_lunch 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $regimen_id, "meal_type" => "lunch", "version_id" => $version_id));
			$data['dinner'] 	= $list_dinner 	= Regimen_Med_List::findByRegimenIDMealVersion(array("regimen_id"=> $regimen_id, "meal_type" => "dinner", "version_id" => $version_id));
			$summary 			= Regimen_Med_LIst::findByRegAndVersionID(array("regimen_id" => $regimen_id, "version_id" => $version_id));
			$data['patient']	= $patient 		 = Patients::findById(array("id" => $reg['patient_id']));
			$data['patient_code'] = $patient['patient_code'];
			// echo $version_id . "<br/>";
			
			foreach ($summary as $key => $value) {
				$array[] = $value['medicine_id'];
			}
			$data['regimen_summary'] = $regimen_summary = array_unique($array);

			foreach ($regimen_summary as $a => $b) {
				$medicine = Regimen_Med_List::findByRegimenIDMedIDVersion(array("regimen_id" => $regimen_id, "medicine_id" => $b, "version_id" => $version_id));
				$total = 0;
				foreach ($medicine as $c => $d) {
						$med = Inventory::findById(array("id" => $d['medicine_id']));
						$dosage = Dosage_Type::findbyId(array("id" => $med['dosage_type']));
					/* DATE INTERVAL */
						$start 			= new Datetime($d['start_date']);
						$end 			= new Datetime($d['end_date']);
						$interval 		= $start->diff($end);
						$time_interval 	= (int) $interval->format('%a');
						$time_interval  = $time_interval + 1;
					/* END DATE INTERVAL */
						$medicine_id = $d['medicine_id'];
						$quantity 	 = $d['quantity'] * $time_interval;

						$total += (int) $quantity;
				}

				if($med['stock'] == "Royal Preventive"){
					$rpc_meds[] = array(
										"medicine_name" => $med['medicine_name'],
										"dosage"		=> $med['dosage'] . " " . $dosage['abbreviation'],
										"price"			=> $med['price'],
										"quantity"		=> $total,
										"total_price"   => $med['price'] * $total
									);
				} else {
					$a_meds[] = array(
										"medicine_name" => $med['medicine_name'],
										"dosage"		=> $med['dosage'] . " " . $dosage['abbreviation'],
										"price"			=> $med['price'],
										"quantity"		=> $total,
										"total_price"   => $med['price'] * $total
									);
				}
				

			}
			$data['rpc_meds'] 		= $rpc_meds;
			$data['a_meds'] 		= $a_meds;
			$data['filename']		= $reg['regimen_number'] . "-" . date("Ymd") . "-" . $patient['patient_code'] . ".pdf"; 
		}
		
		// debug_array($patient);

 
		$this->load->library('tcpdf/tcpdf');
		$this->load->view('regimen/pdf/print_summary',$data);
	}

	/*Print to PDF ~PDF*/

	function print_pdf(){
		$reg_id = $this->uri->segment(3);
		$version_id = $this->uri->segment(4);
		unset($_SESSION['view_regimen_meds']);
		if($reg_id){
			$data['reg_id'] 	= $reg_id;
			$data['reg'] 		= $reg 			= Regimen::findById(array("id" => $reg_id));
			$data['patient']	= $patient 		= Patients::findById(array("id" => $reg['patient_id']));
			$data['filename']		= $reg['regimen_number'] . "-" . $version['version_name'] . "-" . date("Ymd") . "-" . $patient['patient_code'] . ".pdf";
			$data['main_tbl'] 	= $main_tbl 	= Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$data['photo'] 		= $patient_photo= Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			//debug_array ($main_tbl);
			
			$this->get_regimen_details($main_tbl,$reg_id);

			$data['latest_version'] = $latest 	= Regimen_Version::findLatest(array("regimen_id" => $reg_id));
			// debug_array($latest);
			$data['versions'] 	= $version_tbls = Regimen_Version::findByRegimenId(array("regimen_id" => $reg_id));
			$data['version'] = $version 	= Regimen_Version::findById(array("id" => $version_id));
			
			$data['meds']		= $meds 		= $_SESSION['view_regimen_meds'];
			//debug_array($meds);

		}
	
		$this->load->library('tcpdf/tcpdf');
		$this->load->view('regimen/pdf/print_pdf',$data);
	}
	/*Print to Word*/
	function print_word(){
		$reg_id = $this->uri->segment(3);
		$version_id = $this->uri->segment(4);
		unset($_SESSION['view_regimen_meds']);
		if($reg_id){
			$data['reg_id'] 	= $reg_id;
			$data['reg'] 		= $reg 			= Regimen::findById(array("id" => $reg_id));
			$data['patient']	= $patient 		= Patients::findById(array("id" => $reg['patient_id']));
			$data['filename']		= $reg['regimen_number'] . "-" . $version['version_name'] . "-" . date("Ymd") . "-" . $patient['patient_code'] . ".pdf";
			$data['main_tbl'] 	= $main_tbl 	= Regimen_Med_Main::findByRegimenIDVersion(array("regimen_id" => $reg_id, "version_id" => $version_id));
			$data['photo'] 		= $patient_photo= Patient_Photos::findbyId(array("patient_id" => $reg['patient_id']));
			//debug_array ($main_tbl);
			
			$this->get_regimen_details($main_tbl,$reg_id);

			$data['latest_version'] = $latest 	= Regimen_Version::findLatest(array("regimen_id" => $reg_id));
			// debug_array($latest);
			$data['versions'] 	= $version_tbls = Regimen_Version::findByRegimenId(array("regimen_id" => $reg_id));
			
			
			$data['meds']		= $meds 		= $_SESSION['view_regimen_meds'];
			//debug_array($meds);

		}
	
		$this->load->library('word');
		$this->load->view('regimen/pdf/print_word',$data);
	}

	function update_availability(){

		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		$params = array(
				"taken" => 1
			);
		Regimen_Med_List::save($params,$post['id']);
	}
}
