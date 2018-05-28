<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access_Permissions extends MY_Controller {
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

		Engine::appScript('access_permission.js');
		Engine::appScript('topbars.js');
		Engine::appScript('profile.js');
		Engine::appScript('confirmation.js');
		Engine::appScript('blockUI.js');

		/* NOTIFICATIONS */
		Jquery::pusher();
		Jquery::gritter();
		Engine::appScript('notification.js');
		/* END */

		Jquery::select2();
		Jquery::datatable();
		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();

		$data['page_title'] = "Access Permissions";
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
		$this->load->view('access_permissions/index',$data);
		}
	}

	function loadAccessPermission(){
		Engine::XmlHttpRequestOnly();
		$data['user_types']			= $user_types = User_Type::findAllActiveUserTypes();
		$data['user_module_list'] 	= $user_module_list = User_Module_List::findAllActiveModules();
		$data['users'] 				= $users = User::findAllActiveUser();

		$this->load->view('access_permissions/forms/add_edit_permissions',$data);
	}

	function loadEditFormAccessPermission(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$session = $this->session->all_userdata();
		$data['user_id'] 			= $user_id = $this->encrypt->decode($session['user_id']);
		$data['user_type']			= $user_type = User_Type::findById(array("id" => $post['id']));
		$data['user_module_list'] 	= $user_module_list = User_Module_List::findAllActiveModules();
		if($user_type){			
			$this->load->view('access_permissions/forms/edit_permissions_form',$data);
		}
		
	}

	function view_user_role_form() {
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();
		if($post) {
			$post_id 		= (int) $post['id'];
			$session = $this->session->all_userdata();
			$data['user_id'] 			= $user_id = $this->encrypt->decode($session['user_id']);
			$data['user_type']			= $user_type = User_Type::findById(array("id" => $post['id']));
			$data['user_module_list'] 	= $user_module_list = User_Module_List::findAllActiveModules();
			$this->load->view('access_permissions/forms/view_permissions_form',$data);
		}
	}

	function delete_user_role_form() {
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();

		if($post) {
			$post_id 		= (int) $post['id'];
			$data['role'] 	= $role = User_Type::findById(array("id" => $post_id));
			// debug_array($role);
			if($role) {
				$this->load->view('admin/forms/delete_role',$data);
			} else {
				$this->load->view('404');
			}
		}
	}

	function delete_user_role(){
		Engine::XmlHttpRequestOnly();
		$session 	= $this->session->all_userdata();
		$user_id 	= $this->encrypt->decode($session['user_id']);
		$post 		= $this->input->post();

		if($post) {
			$post_id 	= (int) $post['id'];
			$role 		= User_Type::findById(array("id" => $post_id));

			if($role) {
				$success = true;
				User_Type::delete($post_id);

				/* ACTIVITY TRACKER */
				$act_tracker = array(
					"module"		=> "rpc_management_permissions",
					"user_id"		=> $user_id,
					"entity_id"		=> "0",
					"message_log" 	=> "deleted user role <a href='javascript:void(0);'>{$role['role_name']}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully deleted {$role['user_type']}!";
			} else {
				$json['is_successful'] 	= false;
				$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
			}
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	/* ADD USER ROLES */

	function loadAddFormUserRole(){
		Engine::XmlHttpRequestOnly();
		$this->load->view('access_permissions/forms/add_user_type',$data);
	}

	function saveRoleTypeForm(){
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();
		$session 	= $this->session->all_userdata();
		$user_id 	= $this->encrypt->decode($session['user_id']);
		if (!empty($post['role_name'])){
			$record = array(
				"user_type" 		=> $post['role_name'],
				"status" 			=> $post['status'],
				"date_created" 		=> date("Y-m-d H:i:s"),
				"last_modified_by" 	=> $user_id,
				);

			$new_role = User_Type::save($record);

			/*$notif = array(
				"created_by"	=> $user_id,	
				"message" 		=> "created user role <a href='javascript:void(0);' onclick='javascript:window.location.href=" . "permissions" . "; view_roles(" . $new_role . ")'>{$post['role_name']}</a>",
				// "date_created" 		=> date("Y-m-d H:i:s"),
			);
			$notif_id = Notification::save($notif);

			$users = User::findAllActiveUser();
			foreach ($users as $key => $value) {
				$notify = array(
					"user_id" 	=> $value['id'],
					"notif_id" 	=> $notif_id,
					"date"		=> date("Y-m-d H:i:s")
					);
				Notification_Flag::save($notify);
			}*/

			/* ACTIVITY TRACKER */

			$act_tracker = array(
				"module"		=> "rpc_management_permissions",
				"user_id"		=> $user_id,
				"entity_id"		=> $new_role,
				"message_log" 	=> "created user role <a href='javascript:void(0);' class='track_user_permission' data-id='" . $new_role . "'>{$post['role_name']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);


		}
	}

	/* END OF ADD USER ROLES */

	function update_roles(){
		Engine::XmlHttpRequestOnly();
		$session 	= $this->session->all_userdata();
		$session_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		if($post){
			$post_id 		= $post['user_id'];
			$user_type_id 	= $post['user_type_id'];
			foreach($post['option'] as $key=>$value):

				if($value['permission_id']){
					$id = $value['permission_id'];
					$others = array(
					"user_id"			=> $post_id,
					"user_type_id"		=> $user_type_id,
					"user_module_id" 	=> $value['module_id'],
					"last_modified_by" 	=> $post_id,
					);

					$can = array(
						"can_add" 			=> ($value['add_box'] == "on" ? "1" : "0"),
						"can_update" 		=> ($value['edit_box'] == "on" ? "1" : "0"),
						"can_delete" 		=> ($value['delete_box'] == "on" ? "1" : "0"),
						"can_view" 			=> ($value['view_box'] == "on" ? "1" : "0"),
					);
					$record = array_merge($others,$can);
					RPC_User_Access_Permission::save($record,$id);

				} else {
					$others = array(
					"user_id"			=> $post_id,
					"user_type_id"		=> $user_type_id,
					"user_module_id" 	=> $value['module_id'],
					"date_created" 		=> date("Y-m-d H:i:s"),
					"last_modified_by" 	=> $post_id,
					);

					if($value['add_box'] == "on" || $value['edit_box'] == "on" || $value['delete_box'] == "on" || $value['view_box'] == "on"){
						$can = array(
							"can_add" 			=> ($value['add_box'] == "on" ? "1" : "0"),
							"can_update" 		=> ($value['edit_box'] == "on" ? "1" : "0"),
							"can_delete" 		=> ($value['delete_box'] == "on" ? "1" : "0"),
							"can_view" 			=> ($value['view_box'] == "on" ? "1" : "0"),
						);
						$record = array_merge($others,$can);
						RPC_User_Access_Permission::save($record);
					}

				}
				

			endforeach;
			$role = User_Type::findById(array("id" => $user_type_id));
			$act_tracker = array(
				"module"		=> "rpc_management_permissions",
				"user_id"		=> $session_id,
				"entity_id"		=> $user_type_id,
				"message_log" 	=> "updated user role permissions <a href='javascript:void(0);' class='track_user_permission' data-id='" . $user_type_id . "'>{$role['role_name']}</a>",
				"date_created" 	=> date("Y-m-d H:i:s"),
			);
			Activity_Tracker::save($act_tracker);

			$json['is_successful'] 	= true;
			$json['message']		= "Successfully Updated User Role!";

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
		}
		echo json_encode($json);		
	}
	
}