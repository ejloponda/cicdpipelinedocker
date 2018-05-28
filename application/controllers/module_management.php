<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module_Management extends MY_Controller {

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

			Engine::appScript('module_management.js');
			Engine::appScript('topbars.js');
			Engine::appScript('confirmation.js');
			Engine::appScript('profile.js');

			/* NOTIFICATIONS */
			Jquery::pusher();
			Jquery::gritter();
			Jquery::pnotify();
			Engine::appScript('notification.js');
			/* END */

			Jquery::datatable();
			Jquery::form();
			Jquery::inline_validation();
			Jquery::tipsy();
			Bootstrap::modal();
			
			$data['page_title'] = "Module Management";
			$data['session'] 	= $session = $this->session->all_userdata();
			$data['user'] 		= $user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
			$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
			$data['roles']		= RPC_User_Access_Permission::findPermissionByUserType(array("user_type_id" => $user_type_id['id']));
			
			/* PATIENT MANAGEMENT */
			$data['pm_fmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 1));
			$data['pm_pi']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));
			$data['pm_pmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 3));


			/* ORDER MANAGEMENT */
			$data['om_order']   = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 31));

			/* CREDIT MANAGEMENT */
			$data['cr_mc']   = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 32));
			
			/* MODULE MANAGEMENT */
			$data['mm_dc']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 6));
			$data['mm_dt']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 7));
			$data['mm_fc']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 33));
			$data['mm_dosage']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 34));
			
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
			$this->load->view('module_management/index',$data);

		}
	}

	/*LOAD PAGES*/

	function dosage_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		// $data['mm_dl']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));
		$data['mm_dl']		= $this->getAccessPermission(16);
		$this->load->view('module_management/inventory_category/dosage_list',$data);
	}

	function location_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$this->load->view('module_management/calendar_category/location_list',$data);
		//$this->load->view('module_management/inventory_category/dosage_list',$data);
		/*// $data['mm_dl']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));
		$data['mm_dl']		= $this->getAccessPermission(16);
		$this->load->view('module_management/inventory_category/dosage_list',$data);*/
	}

	function type_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$this->load->view('module_management/calendar_category/type_list',$data);
		//$this->load->view('module_management/inventory_category/dosage_list',$data);
		/*// $data['mm_dl']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));
		$data['mm_dl']		= $this->getAccessPermission(16);
		$this->load->view('module_management/inventory_category/dosage_list',$data);*/
	}

	function files_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] 	= $session = $this->session->all_userdata();
		// $data['access_permission'] = $this->getAccessPermission($module_id_here);

		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
		$data['mm_fc']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 33));
		$this->load->view('module_management/patient_category/file_category_list',$data);
	}

	function loadAddNewFileCategory(){
		Engine::XmlHttpRequestOnly();
		$data['session'] 	= $session = $this->session->all_userdata();
		// $data['access_permission'] = $this->getAccessPermission($module_id_here);
		$this->load->view('module_management/patient_category/forms/add_file_category',$data);
	}

	function loadEditFileCategory(){
		Engine::XmlHttpRequestOnly();
		$data['session'] 	= $session = $this->session->all_userdata();
		$post = $this->input->post();
		if($post){
			$data['category'] = File_Category::findById(array("id" => $post['id']));
			$data['session'] = $session = $this->session->all_userdata();
			$this->load->view('module_management/patient_category/forms/edit_file_category',$data);
		}
	} 

	function loadAddNewLocationTypeForm(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		//$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
		//$data['mm_dl']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));
		$this->load->view('module_management/calendar_category/forms/add_location_type',$data);
	}

	function loadAddNewCalendarTypeForm(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));

		//$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
		//$data['mm_dl']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));
		$this->load->view('module_management/calendar_category/forms/add_type',$data);
	}

	function loadAddNewDosageTypeForm(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
		$data['mm_dl']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));
		$this->load->view('module_management/inventory_category/forms/add_dosage_type',$data);
	}

	function quantity_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		// $data['mm_ql']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 17));
		$data['mm_ql']		= $this->getAccessPermission(17);
		$this->load->view('module_management/inventory_category/quantity_list',$data);
	}

	function loadAddNewQuantityTypeForm(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$this->load->view('module_management/inventory_category/forms/add_quantity_type',$data);
	}

	function disease_category(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		// $data['mm_dc']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 6));
		$data['mm_dc']		= $this->getAccessPermission(6);
		$this->load->view('module_management/disease_category/disease_category',$data);
	}

	function disease_type(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mm_dt']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 7));
		$this->load->view('module_management/disease_category/disease_type',$data);
	}

	function loadAddNewDiseaseCategoryForm(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$this->load->view('module_management/disease_category/forms/add_disease_category',$data);
	}

	function loadAddNewDiseaseTypeForm(){
		Engine::XmlHttpRequestOnly();
		$data['disease_category'] = $disease_category = Disease_Name::getAllData();
		// $data['disease_category'] = $disease_category = Disease_Name::getAllCategoryData(array("category"=>"Family"));
		$data['session'] = $session = $this->session->all_userdata();
		$this->load->view('module_management/disease_category/forms/add_disease_types',$data);
	}

	function loadModalEditDiseaseCategoryForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$data['disease'] = Disease_Name::findById(array("id" => $post['id']));
			$data['session'] = $session = $this->session->all_userdata();
			$this->load->view('module_management/disease_category/forms/edit_disease_category',$data);
		}
	}

	function loadModalEditDiseaseTypeForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$data['disease'] = Disease_Type::findById(array("id" => $post['id']));
			$data['disease_category'] = Disease_Name::getAllData();
			$data['session'] = $session = $this->session->all_userdata();
			$this->load->view('module_management/disease_category/forms/edit_disease_type',$data);
		}
	}

	function loadModalEditLocationForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = $post['id'];
		$data['location'] = $location = Calendar_Dropdown::findById(array("id" => $post_id));

		if($location){
			$this->load->view('module_management/calendar_category/forms/edit_location_type',$data);
		}
	}

	function loadModalEditDosageForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = $post['id'];
		$data['dosage'] = $dosage = Dosage_Type::findById(array("id" => $post_id));
		if($dosage){
			$this->load->view('module_management/inventory_category/forms/edit_dosage_type',$data);
		}
	}

	function loadModalEditQuantityForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = $post['id'];
		$data['quantity'] = $quantity = Quantity_Type::findById(array("id" => $post_id));
		if($quantity){
			$this->load->view('module_management/inventory_category/forms/edit_quantity_type',$data);
		}
	}

	function loadModalDeleteDiseaseCategoryForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$data['disease'] = Disease_Name::findById(array("id" => $post['id']));
			$data['session'] = $session = $this->session->all_userdata();
			$this->load->view('module_management/disease_category/forms/delete_disease_category',$data);
		}
	}

	function loadModalDeleteCalendarForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$data['calendar_dropdown'] = Calendar_Dropdown::findById(array("id" => $post['id']));
			$data['session'] = $session = $this->session->all_userdata();
			$this->load->view('module_management/calendar_category/forms/delete_calendar_category',$data);
		}
	}

	function loadModalDeleteDiseaseTypeForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post){
			$data['disease'] = Disease_Type::findById(array("id" => $post['id']));
			$data['disease_category'] = Disease_Name::getAllData();
			$data['session'] = $session = $this->session->all_userdata();
			$this->load->view('module_management/disease_category/forms/delete_disease_type',$data);
		}
	}

	function loadModalDosageForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$data['dosage'] = $dosage = Dosage_Type::findById(array("id" => $post_id));
		if($dosage){
			$this->load->view('module_management/inventory_category/forms/delete_dosage_type',$data);
		}
	}

	function loadModalQuantityForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$data['quantity'] = $quantity = Quantity_Type::findById(array("id" => $post_id));
		if($quantity){
			$this->load->view('module_management/inventory_category/forms/delete_quantity_type',$data);
		}
	}

	function doctors_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mm_dc']	 = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 18));
		$this->load->view('module_management/disease_category/doctors',$data);
	}

	function loadDoctorsForm(){
		Engine::XmlHttpRequestOnly();
		$this->load->view('module_management/disease_category/forms/add_doctors');
	}

	function loadEditDoctorsForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$doctors = Doctors::findById(array("id" => $post_id));
		if($doctors){
			$data['doctors'] = $doctors;
			$this->load->view('module_management/disease_category/forms/edit_doctors', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}	
	}

	function loadDeleteDoctorsForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$doctors = Doctors::findById(array("id" => $post_id));
		if($doctors){
			$data['doctors'] = $doctors;
			$this->load->view('module_management/disease_category/forms/delete_doctors', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}	
	}

	function reasons_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mm_dc']	 = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 19));
		$this->load->view('module_management/inventory_category/reason_list',$data);
	}

	function loadReasonsForm(){
		Engine::XmlHttpRequestOnly();
		$this->load->view('module_management/inventory_category/forms/add_reasons');
	}

	function loadEditReasonsForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$reasons = Reasons::findById(array("id" => $post_id));
		if($reasons){
			$data['reasons'] = $reasons;
			$this->load->view('module_management/inventory_category/forms/edit_reasons', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}	
	}

	function loadDeleteReasonsForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$reasons = Reasons::findById(array("id" => $post_id));
		if($reasons){
			$data['reasons'] = $reasons;
			$this->load->view('module_management/inventory_category/forms/delete_reasons', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}	
	}

	function other_charges_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mm_oc']	 = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 20));
		$this->load->view('module_management/account_billing/list/other_charges',$data);
	}

	function cost_modifier_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mm_oc']	 = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 20));
		$this->load->view('module_management/account_billing/list/cost_modifier',$data);
	}

	function loadOtherChargesForm(){
		Engine::XmlHttpRequestOnly();
		$data['doctors']	= Doctors::findAllActive();
		$this->load->view('module_management/account_billing/forms/add_other_charges',$data);
	}

	function loadCostModifierForm(){
		Engine::XmlHttpRequestOnly();
		$data['doctors']	= Doctors::findAllActive();
		$this->load->view('module_management/account_billing/forms/add_cost_modifier',$data);
	}

	function loadEditCostModifierForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$oc = Cost_Modifier::findById(array("id" => $post_id));

		if($oc){
			$data['oc'] = $oc;
			$this->load->view('module_management/account_billing/forms/edit_cost_modifier', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}	
	}

	function loadDeleteCostModifierForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$oc = Cost_Modifier::findById(array("id" => $post_id));
		if($oc){
			$data['oc'] = $oc;
			$this->load->view('module_management/account_billing/forms/delete_cost_modifier', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}
	}

	function loadEditOtherChargesForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$oc = Other_Charges::findById(array("id" => $post_id));
		$doctors = Doctors::findAllActive();

		if($oc){
			$data['oc'] = $oc;
			$data['doctors'] = $doctors;
			$this->load->view('module_management/account_billing/forms/edit_other_charges', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}	
	}

	function loadDeleteOtherChargesForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$post_id = (int) $post['id'];
		$oc = Other_Charges::findById(array("id" => $post_id));
		if($oc){
			$data['oc'] = $oc;
			$this->load->view('module_management/account_billing/forms/delete_other_charges', $data);
		} else {
			echo "Error Accessing Information. Please contact your Administrator.";	
		}
	}

	/*END OF LOAD PAGES*/

	/*DATA TABLES*/ 
	function getAllLocationList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		//$mm_dl			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "value",
				/*1 => "color",*/
				1 => "status",
				2 => "id",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $rows,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );


			$dosage_list 	= Calendar_Dropdown::generateDropdownDatatable($params);
			$total_records 	= Calendar_Dropdown::countLocationDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($dosage_list as $key=>$value):
				$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_location_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit Location"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_calendar_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				/*$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_location_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_location_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';*/

				/*if($mm_dl['can_update'] && $mm_dl['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_dosage_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_dosage_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dl['can_update'] && !$mm_dl['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_dosage_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dl['can_update'] && $mm_dl['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_dosage_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}*/
				

				$row = array(
					'0' => $value['value'],
					/*'1' => $value['color'],*/
					'1' => $value['status'] == 1 ? 'Active' : 'Inactive',
					'2' => $action_link,
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

	function getAllTypeList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		//$mm_dl			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "value",
				/*1 => "color",*/
				1 => "status",
				2 => "id",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $rows,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );


			$dosage_list 	= Calendar_Dropdown::generateTypeDatatable($params);
			$total_records 	= Calendar_Dropdown::countLocationDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($dosage_list as $key=>$value):
				$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_location_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit Location"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_calendar_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				/*$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_location_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_location_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';*/

				/*if($mm_dl['can_update'] && $mm_dl['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_dosage_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_dosage_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dl['can_update'] && !$mm_dl['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_dosage_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dl['can_update'] && $mm_dl['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_dosage_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}*/
				

				$row = array(
					'0' => $value['value'],
					/*'1' => $value['color'],*/
					'1' => $value['status'] == 1 ? 'Active' : 'Inactive',
					'2' => $action_link,
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

	function getAllDosageList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_dl			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 16));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "dosage_type",
				1 => "abbreviation",
				2 => "status",
				3 => "id",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$dosage_list 	= Dosage_Type::generateDosageDatatable($params);
			$total_records 	= Dosage_Type::countDosageDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($dosage_list as $key=>$value):


				if($mm_dl['can_update'] && $mm_dl['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_dosage_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_dosage_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dl['can_update'] && !$mm_dl['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_dosage_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dl['can_update'] && $mm_dl['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_dosage_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}
				

				$row = array(
					'0' => $value['dosage_type'],
					'1' => $value['abbreviation'],
					'2' => $value['status'],
					'3' => $action_link,
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

	function getAllQuantityList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_ql			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 17));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "quantity_type",
				1 => "abbreviation",
				2 => "status",
				3 => "id",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$quantity_list 	= Quantity_Type::generateQuantityDatatable($params);
			$total_records 	= Quantity_Type::countQuantityDatatable($params);

			$output = array(
				"sEcho" 				=> $get['sEcho'],
				"iTotalRecords" 		=> $total_records,
				"iTotalDisplayRecords" 	=> $total_records,
				"aaData" 				=> array()
			);


			foreach($quantity_list as $key=>$value):


				if($mm_ql['can_update'] && $mm_ql['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_quantity_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_quantity_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_ql['can_update'] && !$mm_ql['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_quantity_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_ql['can_update'] && $mm_ql['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_quantity_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}
				

				$row = array(
					'0' => $value['quantity_type'],
					'1' => $value['abbreviation'],
					'2' => $value['status'],
					'3' => $action_link,
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

	function getAllDiseaseList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_dc			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 6));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "disease_name",
				1 => "header_category",
				2 => "status",
				3 => "id"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$disease 		= Disease_Name::generateDiseaseDatatable($params);
			$total_records 	= Disease_Name::countDiseaseDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($disease as $key=>$value):


				if($mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '
					<a href="javascript: void(0);" onClick="javascript: edit_disease_category(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
					<a href="javascript:void(0);" onclick="javascript: delete_disease_category('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dc['can_update'] && !$mm_dc['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_disease_category(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_disease_category('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}
				
				
				$disease_category_name = ($value['header_category'] == "Family" ? "Family Medical History" : "Personal Medical History");

				$row = array(
					'0' => $value['disease_name'],
					'1' => $disease_category_name,
					'2' => $value['status'],
					'3' => $action_link,
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

	function getAllDiseaseTypeList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_dt			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 7));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "type_name",
				1 => "disease_category_name",
				2 => "status",
				3 => "id"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$disease 		= Disease_Type::generateDiseaseDatatable($params);
			$total_records 	= Disease_Type::countDiseaseDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($disease as $key=>$value):

				if($mm_dt['can_update'] && $mm_dt['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_disease_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_disease_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dt['can_update'] && !$mm_dt['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_disease_type(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dt['can_update'] && $mm_dt['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_disease_type('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}

				
				
				$row = array(
					'0' => $value['type_name'],
					'1' => $value['disease_category_name'],
					'2' => $value['status'],
					'3' => $action_link,
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

	function getAllDoctorsList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_dc			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 18));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper("DESC");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "full_name",
				1 => "status",
				2 => "id"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$disease 		= Doctors::generateDoctorsDatatable($params);
			$total_records 	= Doctors::countDoctorsDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($disease as $key=>$value):

				if($mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_doctor(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_doctor('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dc['can_update'] && !$mm_dc['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_doctor(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_doctor('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}

				
				
				$row = array(
					'0' => $value['full_name'],
					'1' => $value['status'],
					'2' => $action_link,
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

	function getAllFileCategoryList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_dc			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 18));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper("DESC");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "category_name",
				1 => "status",
				2 => "id"
			);

			$order_by 	= $rows[2] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$disease 		= File_Category::generatefilecategoryDatatable($params);
			$total_records 	= File_Category::countfilecategoryDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($disease as $key=>$value):

				if($mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_filecategory(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_filecategory('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dc['can_update'] && !$mm_dc['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_filecategory(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_filecategory('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}

				
				
				$row = array(
					'0' => $value['category_name'],
					'1' => $value['status'],
					'2' => $action_link,
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

	function getAllReasonsList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_dc			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 19));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper("DESC");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "reason",
				1 => "status",
				2 => "id"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$disease 		= Reasons::generateReasonsDatatable($params);
			$total_records 	= Reasons::countReasonsDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($disease as $key=>$value):

				if($mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_reason(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_reason('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_dc['can_update'] && !$mm_dc['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_reason(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_dc['can_update'] && $mm_dc['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_reason('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}

				
				
				$row = array(
					'0' => $value['reason'],
					'1' => $value['status'],
					'2' => $action_link,
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

	function getAllOtherChargesList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_oc			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 20));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper("DESC");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "r_centers",
				1 => "status",
				2 => "id"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$other_charges	= Other_Charges::generateOtherChargesDatatable($params);
			$total_records 	= Other_Charges::countOtherChargesDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($other_charges as $key=>$value):

				if($mm_oc['can_update'] && $mm_oc['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_oc(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_oc('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_oc['can_update'] && !$mm_oc['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_oc(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_oc['can_update'] && $mm_oc['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_oc('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}

				
				
				$row = array(
					'0' => $value['r_centers'],
					'1' => $value['status'],
					'2' => $action_link,
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

	function getAllCostModifierList() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();

		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mm_oc			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 20));

		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper("DESC");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "cost_modifier",
				1 => "status",
				2 => "id"
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$other_charges	= Cost_Modifier::generateCostModifierDatatable($params);
			$total_records 	= Cost_Modifier::countCostModifierDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($other_charges as $key=>$value):

				if($mm_oc['can_update'] && $mm_oc['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_cost_modifier(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_cost_modifier('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				}else if ($mm_oc['can_update'] && !$mm_oc['can_delete']){
					$action_link = '<a href="javascript: void(0);" onClick="javascript: edit_cost_modifier(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				}else if (!$mm_oc['can_update'] && $mm_oc['can_delete']){
					$action_link = '<a href="javascript:void(0);" onclick="javascript: delete_cost_modifier('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
				}else {
					$action_link = '';
				}

				
				
				$row = array(
					'0' => $value['cost_modifier'],
					'1' => $value['status'],
					'2' => $action_link,
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

	/*END OF DATATABLES*/

	/*PROCESS*/

	function add_quantity_type(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['id']){
				$record = array(
					"quantity_type" 	=> $post['quantity_type'],
					"abbreviation" 		=> $post['abbreviation'],
					"status" 			=> $post['status'],
					"last_modified_by" 	=> $session_id,
				);

				$quantity_id = Quantity_Type::save($record,$post['id']);

				/* ACTIVITY TRACKER */

				$quantity_type = $post['quantity_type'];
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $quantity_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated Quantity Type: <a href='javascript:void(0);' class='track_module_quantity' data-id='" . $quantity_id . "'>{$quantity_type}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated {$quantity_type} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Quantity Type: {$quantity_type}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Quantity Type " . $quantity_type,
					'type' => 'info'
				));

			} else {
				$record = array(
					"quantity_type" 	=>	$post['quantity_type'],
					"abbreviation" 		=>	$post['abbreviation'],
					"status" 			=>	$post['status'],
					"date_created" 		=>	date("Y-m-d H:i:s"),
					"last_modified_by" 	=>	$session_id,
				);

				$quantity_id = Quantity_Type::save($record);

				/* ACTIVITY TRACKER */

				$quantity_type = $post['quantity_type'];
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $quantity_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added Quantity Type: <a href='javascript:void(0);' class='track_module_quantity' data-id='" . $quantity_id . "'>{$quantity_type}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$quantity_type} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Quantity Type: {$quantity_type}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Quantity Type " . $quantity_type,
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function add_calendar_dropdown(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['id']){
				$event = array(
					"type" 		=> $post['type'],
					"value"		=> $post['value'],
					/*"color" 		=> $post['abbreviation'],*/
					"status" 			=> $post['status'] == 'Active' ? 1 : 0,
					"created_by" 	=> $session_id,
				);

				$dosage_id = Calendar_Dropdown::save($event,$post['id']);

				/* ACTIVITY TRACKER */

				$dosage_type = $post['type'];
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dosage_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated Dosage Type: <a href='javascript:void(0);' class='track_module_dosage' data-id='" . $dosage_id . "'>{$dosage_type}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated {$dosage_type} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Dosage Type: {$dosage_type}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Dosage Type " . $dosage_type,
					'type' => 'info'
				));

			} else {
				$event = array(
					"type" 		=> $post['type'],
					"value"		=> $post['value'],
					/*"color" 		=> $post['abbreviation'],*/
					"status" 			=> $post['status'] == 'Active' ? 1 : 0,
					"date_created" 		=>	date("Y-m-d H:i:s"),
					"created_by" 	=> $session_id,
				);
				
				$dosage_id = Calendar_Dropdown::save($event);

				/* ACTIVITY TRACKER */

				$dosage_type = $post['type'];
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dosage_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added Dosage Type: <a href='javascript:void(0);' class='track_module_dosage' data-id='" . $dosage_id . "'>{$dosage_type}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);


				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$dosage_type} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Dosage Type: {$dosage_type}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Dosage Type " . $dosage_type,
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function add_dosage_type(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['id']){
				$record = array(
					"dosage_type" 		=> $post['dosage_type'],
					"abbreviation" 		=> $post['abbreviation'],
					"status" 			=> $post['status'],
					"last_modified_by" 	=> $session_id,
				);

				$dosage_id = Dosage_Type::save($record,$post['id']);

				/* ACTIVITY TRACKER */

				$dosage_type = $post['dosage_type'];
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dosage_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated Dosage Type: <a href='javascript:void(0);' class='track_module_dosage' data-id='" . $dosage_id . "'>{$dosage_type}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated {$dosage_type} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Dosage Type: {$dosage_type}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Dosage Type " . $dosage_type,
					'type' => 'info'
				));

			} else {
				$record = array(
					"dosage_type" 		=>	$post['dosage_type'],
					"abbreviation" 		=>	$post['abbreviation'],
					"status" 			=>	$post['status'],
					"date_created" 		=>	date("Y-m-d H:i:s"),
					"last_modified_by" 	=>	$session_id,
				);

				$dosage_id = Dosage_Type::save($record);

				/* ACTIVITY TRACKER */

				$dosage_type = $post['dosage_type'];
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dosage_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added Dosage Type: <a href='javascript:void(0);' class='track_module_dosage' data-id='" . $dosage_id . "'>{$dosage_type}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);


				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$dosage_type} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Dosage Type: {$dosage_type}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Dosage Type " . $dosage_type,
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function add_file_category(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['id']){
				$fc_id = $post['id'];
				$category = File_Category::findById(array("id"=>$fc_id));
				$record = array(
					"category_name" 	=> $post['category_name'],
					"status" 			=> $post['status'],
					"last_update_by" 	=> $session_id,
				);

				File_Category::save($record,$fc_id);


				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $fc_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." updated File Category Name: " . $category['category_name'] . " to " . $post['category_name'] . ".",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated File Category Name: " . $category['category_name'] . " to " . $post['category_name'] . ".";
			
				//New Notification
				$msg = $session_user['name'] . " has successfully Updated File Category Name: {$post['category_name']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated File Category Name " . $post['category_name'],
					'type' => 'info'
				));
			} else {

				$record = array(
					"category_name" 	=> $post['category_name'],
					"status" 			=> $post['status'],
					"date_created" 		=> date("Y-m-d H:i:s"),
					"last_update_by" 	=> $session_id,
				);
				$fc_id = File_Category::save($record);

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));

				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $fc_id,
					"message_log" 	=> $user['lastname']. "," . $user['firstname'] . " added File Category Name: " . $category['category_name'] . " to " . $post['category_name'] . ".",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added File Category Name: " . $post['category_name'] . " in database";
				
				//New Notification
				$msg = $session_user['name'] . " has successfully Added File Category Name: {$post['category_name']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added File Category Name " . $post['category_name'],
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function add_disease_category(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['id']){
				$record = array(
					"disease_name" => $post['disease_name'],
					"header_category" => $post['page_category'],
					"status" => $post['status'],
					"last_modified_by" => $session_id,
				);

				$dc_id = Disease_Name::save($record,$post['id']);

				$all_records = Disease_Name::getAllbyId(array("id" => $post['id']));
				// print_r($all_records);
				foreach ($all_records as $key => $value) {
					$record2 = array(
							"disease_category_name" => $post['disease_name'],
						);
					Disease_Type::save2($record2,$value['id']);
				}
				$disease_name = $post['disease_name'];

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dc_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated Disease Name: <a href='javascript:void(0);' class='track_module_dc' data-id='" . $dc_id . "'>{$disease_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated {$disease_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Disease Name: {$disease_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Disease Name " . $disease_name,
					'type' => 'info'
				));
			} else {
				$record = array(
					"disease_name" => $post['disease_name'],
					"header_category" => $post['page_category'],
					"status" => $post['status'],
					"date_created" =>date("Y-m-d H:i:s"),
					"last_modified_by" => $session_id,
				);

				$dc_id = Disease_Name::save($record);
				$disease_name = $post['disease_name'];

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dc_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added Disease Name: <a href='javascript:void(0);' class='track_module_dc' data-id='" . $dc_id . "'>{$disease_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$disease_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Disease Name: {$disease_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Disease Name " . $disease_name,
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function add_disease_type(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['id']){
				$disease_category_name = Disease_Name::findById(array("id" => $post['disease_category_list']));
				$record = array(
					"disease_id" => $post['disease_category_list'],
					"type_name" => $post['disease_name'],
					"disease_category_name" => $disease_category_name['disease_name'],
					"status" => $post['status'],
					"last_modified_by" => $session_id,
				);

				$dt_id = Disease_Type::save($record, $post['id']);
				$disease_name = $post['disease_name'];

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dt_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated Disease Type: <a href='javascript:void(0);' class='track_module_dt' data-id='" . $dt_id . "'>{$disease_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated {$disease_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Disease Type: {$disease_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Disease Type " . $disease_name,
					'type' => 'info'
				));

			} else {
				$disease_category_name = Disease_Name::findById(array("id" => $post['disease_category_list']));
				$record = array(
					"disease_id" => $post['disease_category_list'],
					"type_name" => $post['disease_name'],
					"disease_category_name" => $disease_category_name['disease_name'],
					"status" => $post['status'],
					"date_created" =>date("Y-m-d H:i:s"),
					"last_modified_by" => $session_id,
				);

				$dt_id = Disease_Type::save($record);
				$disease_name = $post['disease_name'];

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $dt_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added Disease Type: <a href='javascript:void(0);' class='track_module_dt' data-id='" . $dt_id . "'>{$disease_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$disease_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Disease Type: {$disease_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Disease Type " . $disease_name,
					'type' => 'info'
				));
			}
			
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function delete_disease_category(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		$post = $this->input->post();
		$dc   = Disease_Name::findById(array("id" => $post['id']));
		if($dc){
			Disease_Name::delete($dc['id']);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted Disease Name: <a href='javascript:void(0);'>{$dc['disease_name']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Disease Name: {$dc['disease_name']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Disease Name" . $disease_name,
				'type' => 'info'
			));
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function delete_disease_type(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$post = $this->input->post();
		$dt   = Disease_Type::findById(array("id"=>$post['id']));
		if($dt){
			Disease_Type::delete($dt['id']);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted Disease Type: <a href='javascript:void(0);'>{$dt['type_name']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Disease Type: {$dt['type_name']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Disease Type" . $dt['type_name'],
				'type' => 'info'
			));

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function deleteCalendarType(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$post = $this->input->post();

		$dosage = Calendar_Dropdown::findById(array("id" => $post['id']));

		if($dosage){
			Calendar_Dropdown::delete($post['id']);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted Type: <a href='javascript:void(0);'>{$dosage['value']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted {$dosage['value']}!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Dosage Type: {$dosage['dosage_type']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Dosage Type" . $dosage['dosage_type'],
				'type' => 'info'
			));

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function deleteDosageType(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$post = $this->input->post();

		$dosage = Dosage_Type::findById(array("id" => $post['id']));

		if($dosage){
			Dosage_Type::delete($post['id']);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted Dosage Type: <a href='javascript:void(0);'>{$dosage['dosage_type']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted {$dosage['dosage_type']}!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Dosage Type: {$dosage['dosage_type']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Dosage Type" . $dosage['dosage_type'],
				'type' => 'info'
			));

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function deleteQuantityType(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$post = $this->input->post();

		$quantity = Quantity_Type::findById(array("id" => $post['id']));

		if($quantity){
			Quantity_Type::delete($post['id']);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted Quantity Type: <a href='javascript:void(0);'>{$quantity['quantity_type']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted {$quantity['quantity_type']}!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Quantity Type: {$quantity['quantity_type']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Quantity Type" . $quantity['quantity_type'],
				'type' => 'info'
			));

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}


	function add_doctors(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['doctors_id']){

				$doctors_id = $post['doctors_id'];

				$doctors_name = $post['title'] . ". " .$post['firstname'] . " " . ($post['middlename'] ? $post['middlename'] . ". " : "" ) . $post['lastname'];

				$record = array(
					"title"				=> $post['title'],
					"firstname" 		=> $post['firstname'],
					"middlename" 		=> $post['middlename'],
					"lastname" 			=> $post['lastname'],
					"full_name" 		=> $doctors_name,
					"status" 			=> $post['status'],
					"last_modified_by" 	=> $session_id,
				);

				Doctors::save($record,$doctors_id);


				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $doctors_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated Doctors Name: <a href='javascript:void(0);' class='track_module_dc' data-id='" . $doctors_id . "'>{$doctors_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated {$doctors_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Doctors' Name: {$doctors_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Doctors' Name:" . $doctors_name,
					'type' => 'info'
				));

			} else {

				$doctors_name = $post['title'] . ". " .$post['firstname'] . " " . ($post['middlename'] ?  $post['middlename'] . ". " : "" ) . $post['lastname'];

				$record = array(
					"title"				=> $post['title'],
					"firstname" 		=> $post['firstname'],
					"middlename" 		=> $post['middlename'],
					"lastname" 			=> $post['lastname'],
					"full_name" 		=> $doctors_name,
					"status" 			=> $post['status'],
					"date_created" =>date("Y-m-d H:i:s"),
					"last_modified_by" => $session_id,
				);

				$doctors_id = Doctors::save($record);

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $doctors_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added Doctors Name: <a href='javascript:void(0);' class='track_module_dc' data-id='" . $doctors_id . "'>{$doctors_name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added {$doctors_name} in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Doctors' Name: {$doctors_name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Doctors' Name:" . $doctors_name,
					'type' => 'info'
				));

			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function delete_doctors(){
		Engine::XmlHttpRequestOnly();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		$post 	 	= $this->input->post();
		$post_id 	= $post['id'];
		$doctors 	= Doctors::findById(array("id" => $post_id));

		if($doctors){
			Doctors::delete($post_id);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted Doctor Name: <a href='javascript:void(0);'>{$doctors['full_name']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted {$doctors['full_name']}!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Doctors' Name: {$doctors['full_name']}.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Doctors' Name:" . $doctors['full_name'],
				'type' => 'info'
			));

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}


	function add_reasons(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['reasons_id']){

				$reasons_id = $post['reasons_id'];

				$record = array(
					"reason"			=> $post['reasons'],
					"status" 			=> $post['status'],
					"last_modified_by" 	=> $session_id,
				);

				Reasons::save($record,$reasons_id);


				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $reasons_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated <a href='javascript:void(0);' class='track_module_rid' data-id='" . $reasons_id . "'>Reason</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated Reason in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Reason.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Reason",
					'type' => 'info'
				));
			} else {

				$record = array(
					"reason"			=> $post['reasons'],
					"status" 			=> $post['status'],
					"date_created" 		=> date("Y-m-d H:i:s"),
					"last_modified_by" 	=> $session_id,
				);

				$reasons_id = Reasons::save($record);

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $reasons_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added <a href='javascript:void(0);' class='track_module_rid' data-id='" . $reasons_id . "'>Reason</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Reason.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Reason",
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function delete_reasons(){
		Engine::XmlHttpRequestOnly();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		$post 	 	= $this->input->post();
		$post_id 	= $post['id'];
		$reasons 	= Reasons::findById(array("id" => $post_id));
		if($reasons){
			Reasons::delete($post_id);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted <a href='javascript:void(0);'>Reason</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted Reason!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Reason.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Reason",
				'type' => 'info'
			));

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function delete_other_charges(){
		Engine::XmlHttpRequestOnly();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		$post 	 	= $this->input->post();
		$post_id 	= $post['oc_id'];
		$oc 		= Other_Charges::findById(array("id" => $post_id));
		if($oc){
			Other_Charges::delete($post_id);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted <a href='javascript:void(0);'>Other Charges</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted Other Charges!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Other Charges'.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Other Charges",
				'type' => 'info'
			));
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function delete_cost_modifier(){
		Engine::XmlHttpRequestOnly();
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		$post 	 	= $this->input->post();
		$post_id 	= $post['oc_id'];
		$oc 		= Cost_Modifier::findById(array("id" => $post_id));
		if($oc){
			Cost_Modifier::delete($post_id);

			/* ACTIVITY TRACKER */
			$user = User::findById(array("id"=>$session_id));
			$act_tracker = array(
				"module"		=> "rpc_module_management",
				"user_id"		=> $session_id,
				"entity_id"		=> "0",
				"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "deleted <a href='javascript:void(0);'>Cost Modifier</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message'] 		= "Successfully Deleted Cost Modifier!";

			//New Notification
			$msg = $session_user['name'] . " has successfully Deleted Cost Modifier'.";

			$this->pusher->trigger('my_notifications', 'notification', array(
				'message' => $msg,
				'title' => "Deleted Cost Modifier",
				'type' => 'info'
			));
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}
		echo json_encode($json);
	}

	function save_cost_modifier(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['oc_id']){

				$oc_id = $post['oc_id'];

				$record = array(
					"cost_modifier"		=> $post['cost_modifier'],
					"status" 			=> $post['status'],
					"last_modified_by" 	=> $session_id,
				);

				Cost_Modifier::save($record,$oc_id);


				
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $oc_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated <a href='javascript:void(0);' class='track_module_ocid' data-id='" . $oc_id . "'>Cost Modifier</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated Cost Modifier in database";

				
				$msg = $session_user['name'] . " has successfully Updated Cost Modifier'.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Cost Modifier",
					'type' => 'info'
				));

			} else {

				$record = array(
					"cost_modifier"		=> $post['cost_modifier'],
					"status" 			=> $post['status'],
					"date_created" 		=> date("Y-m-d H:i:s"),
					"last_modified_by" 	=> $session_id,
				);

				$oc_id = Cost_Modifier::save($record);

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $oc_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added <a href='javascript:void(0);' class='track_module_ocid' data-id='" . $oc_id . "'>Cost Modifier</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Cost Modifier'.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Cost Modifier",
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function save_other_charges(){
		Engine::XmlHttpRequestOnly();

		$post = $this->input->post();
		//debug_array($post);
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();

		if($post){
			if($post['oc_id']){

				$oc_id = $post['oc_id'];

				$record = array(
					"category"			=> $post['category'],
					"doctor_id"			=> $post['doctor'],
					"r_centers"			=> $post['r_centers'],
					"price"				=> $post['price'], 
					"status" 			=> $post['status'],
					"last_modified_by" 	=> $session_id,
				);

				Other_Charges::save($record,$oc_id);


				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $oc_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "updated <a href='javascript:void(0);' class='track_module_ocid' data-id='" . $oc_id . "'>Revenue Center</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Updated Revenue Center in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Revenue Center'.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Revenue Center",
					'type' => 'info'
				));

			} else {

				$record = array(
					"category"			=> $post['category'],
					"doctor_id"			=> $post['doctor'],
					"r_centers"			=> $post['r_centers'],
					"price"				=> $post['price'], 
					"status" 			=> $post['status'],
					"date_created" 		=> date("Y-m-d H:i:s"),
					"last_modified_by" 	=> $session_id,
				);

				$oc_id = Other_Charges::save($record);

				/* ACTIVITY TRACKER */
				$user = User::findById(array("id"=>$session_id));
				$act_tracker = array(
					"module"		=> "rpc_module_management",
					"user_id"		=> $session_id,
					"entity_id"		=> $oc_id,
					"message_log" 	=> $user['lastname'].",". $user['firstname']  ." ". "added <a href='javascript:void(0);' class='track_module_ocid' data-id='" . $oc_id . "'>Revenue Center</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully Added in database";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Revenue Center'.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Revenue Center",
					'type' => 'info'
				));
			}
			
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	/*END OF PROCESS*/

	
	function test(){
		echo $this->isUserLoggedIn();
	}

}