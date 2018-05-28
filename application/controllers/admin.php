<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		$this->load->library('pusher');
	}

	function is_user_logged_in() {
		$session = $this->session->userdata('user_id');
		if($session) {
			$decode = $this->encrypt->decode($session);
			$user = User::findById(array("id"=>$decode));
			// debug_array($user);
			if($user) {
				return true;
			}
		}

		return false;
	}
	
	function welcome(){
		if(!$this->is_user_logged_in()){
			redirect("authenticate");
		} else { 
			
			Engine::appStyle('bootstrap.min.css');
			Engine::appStyle('welcome-style.css');
			Engine::appStyle('forms-style.css');


			Engine::appScript('admin.js');
			Engine::appScript('confirmation.js');
			Engine::appScript('profile.js');
			Engine::appScript('blockUI.js');
			
			Jquery::form();
			Jquery::inline_validation();
			Jquery::tipsy();

			/* NOTIFICATIONS */
			Jquery::pusher();
			Jquery::gritter();
			Jquery::pnotify();
			Engine::appScript('notification.js');
			/* END */

			$data['page_title'] = "Royal Preventive Clinic";
			$data['session'] 	= $session = $this->session->all_userdata();
			// debug_array($session);
			$data['user'] 		= $user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
			$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
			$data['roles']		= RPC_User_Access_Permission::findPermissionByUserType(array("user_type_id" => $user_type_id['id']));
			
			/* PATIENT MANAGEMENT */
			$data['pm_fmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 1));
			$data['pm_pi']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));
			$data['pm_pmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 3));
			
			/* ORDER MANAGEMENT */
			$data['om_order']   = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 29));

			/* MODULE MANAGEMENT */
			$data['mm_dc']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 6));
			$data['mm_dt']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 7));
			
			/* MANAGE USERS */
			$data['mu_default']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 4));
			$data['mu_roles']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 5));
			$data['mu_ms']			= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 12));
			
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
			$this->load->view('admin/welcome',$data);

		}
	}

	function index(){
		
		if(!$this->is_user_logged_in()){
			redirect("authenticate");
		} else { 

		Engine::appStyle('RPC-style.css');
		Engine::appStyle('forms-style.css');
		Engine::appStyle('bootstrap.min.css');

		Engine::appScript('admin.js');
		Engine::appScript('confirmation.js');
		Engine::appScript('topbars.js');
		Engine::appScript('profile.js');

		Jquery::datatable();
		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();
		Bootstrap::modal();

		/* NOTIFICATIONS */
		Jquery::pusher();
		Jquery::gritter();
		Jquery::pnotify();
		Engine::appScript('notification.js');
		/* END */
		
		$data['page_title'] = "User Accounts";
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
		$this->load->view('admin/index',$data);

		}
	}

	function user_access(){
		Engine::XmlHttpRequestOnly();
		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();
		Bootstrap::modal();

		$data['all_users_role'] = $all_users_role = User_Type::findAllActiveUserTypes();
		$data['page_title'] = "Add User Access";
		$data['session'] = $session = $this->session->all_userdata();
		$this->load->view('admin/forms/user_access',$data);
	}

	function edit_user_access(){
		Engine::XmlHttpRequestOnly();
		
		$post = $this->input->post();
		$success = false;
		if($post) {
			$params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$data['all_users_role'] = $all_users_role = User_Type::findAllActiveUserTypes();
			$post_id 		=  (int) $post['id'];
			$data['user'] 	= $user = User::findById(array("id" => $post_id));
			$data['all_question'] = $all_question = Verification_Questions::findAll();

			if($user) {
				$success 	= true;
				$this->load->view('admin/forms/edit_user_access',$data);
			}
		}
	}

	function viewUserProfile(){
		Engine::XmlHttpRequestOnly();
		
		$post = $this->input->post();
		$success = false;
		if($post) {
			$post_id 		=  (int) $post['id'];
			$data['user'] 	= $user = User::findById(array("id" => $post_id));
			$session 		= $this->session->all_userdata();
			if($user) {
				$success 	= true;
				$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
				$data['mu_default']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 4));
				$this->load->view('admin/view_user',$data);
			}
		}
	}

	function delete_user_form() {
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();

		if($post) {
			$post_id 		= (int) $post['id'];
			$data['user'] 	= $user = User::findById(array("id" => $post_id));
			if($user) {
				$this->load->view('admin/forms/delete_user',$data);
			}
		}
	}

	function delete_user(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$post 		= $this->input->post();

		if($post) {
			$post_id 	= (int) $post['id'];
			$user 		= User::findById(array("id" => $post_id));

			if($user) {
				$success = true;
				User::delete($post_id);
				$dir = "files/photos/tmp/users/";
				unlink("$dir/".$user['display_image_url']);		
				$name = $user['firstname'] . " " . $user['lastname'];

				/* ACTIVITY TRACKER */

				$act_tracker = array(
					"module"		=> "rpc_management_user",
					"user_id"		=> $session_id,
					"entity_id"		=> "0",
					"message_log" 	=> $session_user['name'] ." ". "deleted user account <a href='javascript:void(0);'>{$name}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully deleted!";

				//New Notification
				$msg = $session_user['name'] . " has successfully Deleted User Account: {$name}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Deleted User Account " . $name,
					'type' => 'info'
				));
			}
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function add_role(){
		Engine::XmlHttpRequestOnly();
		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();
		$data['page_title'] = "Add Role Types";
		$data['session'] = $session = $this->session->all_userdata();
		$this->load->view('admin/forms/user_role',$data);
	}

	function edit_user_role(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post) {
			$post_id 		=  (int) $post['id'];
			$data['role'] 	= $role = User_Roles::findRoleById(array("id" => $post_id));
			if($role) {
				$this->load->view('admin/forms/edit_user_roles',$data);
			}
		}
	}

	function delete_user_role_form() {
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();

		if($post) {
			$post_id 		= (int) $post['id'];
			$data['role'] 	= $role = User_Roles::findRoleById(array("id" => $post_id));
			if($role) {
				$this->load->view('admin/forms/delete_role',$data);
			}
		}
	}

	function delete_user_role(){
		$post 		= $this->input->post();

		if($post) {
			$post_id 	= (int) $post['id'];
			$role 		= User_Roles::findRoleById(array("id" => $post_id));

			if($role) {
				$success = true;
				User_Roles::delete($post_id);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully deleted!";
			}
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function permissions(){
		Engine::XmlHttpRequestOnly();

		Engine::appStyle('bootstrap.min.css');
		Engine::appScript('admin.js');
		Engine::appScript('confirmation.js');

		Jquery::form();
		Jquery::inline_validation();
		Jquery::tipsy();

		$data['page_title'] = "Add Permissions";
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mu_ms']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 12));
		$this->load->view('admin/forms/permissions',$data);
	}

	function user_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] 	= $session = $this->session->all_userdata();
		$user_type_id 		= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mu_default']	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 4));
		$this->load->view('admin/user_list',$data);
	}

	function user_role_list(){
		Engine::XmlHttpRequestOnly();
		$data['session'] = $session = $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$data['mu_roles']= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 5));
		$this->load->view('admin/user_role_list',$data);
	}

	function view_user_role_form() {
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();

		if($post) {
			$post_id 		= (int) $post['id'];
			$data['role'] 	= $role = User_Roles::findRoleById(array("id" => $post_id));
			if($role) {
				$this->load->view('admin/forms/view_role',$data);
			}
		}
	}

	// USERS 

	function save_new_user(){
		$session 	= $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$post = $this->input->post();
		$file = $_FILES['image'];
		if($post['id']) {
				$post_id	= (int) $post['id'];
				$user 		= User::findById(array("id" => $post_id));

				if($user) {

					$exist = User::findByUsernameExceptUserID(array("user_id" => $post_id, "username" => $post['username']));
					if(!$exist){
						if($post['password']) {
							$password 		= $this->encrypt->encode($post['password']);
							$hash 			= Password_Hash::create_hash($post['password']);
							$last_update 	= date("Y-m-d H:i:s",time());
						} else {
							$password 		= $user['password'];
							$hash 			= $user['hash'];
							$last_update 	= $user['last_update'];
						}

						$record = array(
							"firstname" 		=> $post['firstname'],
							"middlename" 		=> $post['middlename'],
							"lastname" 			=> $post['lastname'],
							"address" 			=> $post['address'],
							"address_2" 		=> $post['address_2'],
							"email_address" 	=> $post['email'],
							"username" 			=> $post['username'],
							"password" 			=> $password,
							"hash" 				=> $hash,
							"account_type" 		=> $post['account_type'],
							"account_status" 	=> $post['status'],
							"last_change_password" 	=> $last_update,
							"verification_question" => $post['verification_question'],
							"verification_answer"   => strtolower($post['verification_answer']),
						);

						$user_id = User::save($record,$post_id);

						foreach($post['contact_information'] as $key2=>$value2):
							$record2 = array(
								"user_id"			=> $post_id,
								"contact_type" 		=> $value2['contact_type'],
								"contact_value" 	=> $value2['contact_type_value'],
								"extension" 		=> $value2['contact_extension'],
								"date_created" 		=> date("Y-m-d H:i:s"),
							);

							User_Contact::save($record2);
						endforeach;

						if($file['error'] == 0){
							$this->load->helper('string');
							$random_key = random_string('unique');

							$file_extension	= substr($file['name'], -3);
							$raw_name 		= $this->encrypt->sha1(basename($file['name']."$random_key")).date("hims",time());
							$upload_name 	= $file['name'];
							$file_size 		= $file['size'];

							$dir = "files/photos/tmp/users/";
							if(!is_dir($dir)) {
								mkdir($dir,0777,true);
							}
							unlink("$dir/".$user['display_image_url']);
							$path = "$dir/$raw_name.{$file_extension}";
								
							if(move_uploaded_file($file['tmp_name'], $path)) {

								$arr = array(
									"display_image_url" => $raw_name . "." . $file_extension
								);

								User::save($arr,$post_id);
							}
						}
						if($session_id === $post['id']){
							$credentials = array(
								'user_id' 		=> $this->encrypt->encode($post['id']),
								'firstname' 	=> $post['firstname'],
								'middlename' 	=> $post['middlename'],
								'lastname' 		=> $post['lastname'],
								'name' 			=> $post['firstname'] . ' ' . $post['lastname'],
								'account_type' 	=> $post['account_type'],
								'username'		=> $post['username']

							);

							$this->session->set_userdata($credentials);
						}
						
						$name = $post['firstname'] . " " . $post['lastname'];

						/*$notif = array(
							"created_by"	=> $session_id,	
							"message" 		=> "updated user account <a href='javascript:void(0);' onclick='javascript:window.location.href=" . "users" . "; view_user_profile(" . $user_id . ")'>{$name}</a>",
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
							"module"		=> "rpc_management_user",
							"user_id"		=> $session_id,
							"entity_id"		=> $user_id,
							"message_log" 	=> $session_user['name'] ." ". "updated user account <a href='javascript:void(0);' class='track_users' data-id='" . $user_id . "'>{$name}</a>",
							"date_created" 	=> date("Y-m-d H:i:s"),
						);
						Activity_Tracker::save($act_tracker);

						$json['is_successful'] 	= true;
						$json['message']		= "Successfully Updated {$name} !";

						//New Notification
						$msg = $session_user['name'] . " has successfully Updated User Account: {$name}.";

						$this->pusher->trigger('my_notifications', 'notification', array(
							'message' => $msg,
							'title' => "Updated User Account " . $name,
							'type' => 'info'
						));
					} else {
						$json['is_successful'] 	= false;
						$json['message']		= "Username already exist.";
					}
					


				} else {
					$json['is_successful'] 	= false;
					$json['message']		= "Ooop! Error adding to database. Please contact web administrator!";
				}
				

			} else {
				// check if username exist
				$exist 	= User::findByUsername($post['username']);
				if(!$exist){
					$password 	= $this->encrypt->encode($post['password']);
					$hash 		= Password_Hash::create_hash($post['password']);
					
						$record = array(
							"firstname" 		=> $post['firstname'],
							"middlename" 		=> $post['middlename'],
							"lastname" 			=> $post['lastname'],
							"address" 			=> $post['address'],
							"address_2" 		=> $post['address_2'],
							"email_address" 	=> $post['email'],

							"username" 			=> $post['username'],
							"password" 			=> $password,
							"hash" 				=> $hash,
							"account_type" 		=> $post['account_type'],
							"account_status" 	=> $post['status'],
							// "display_image_url"	=> $filename,
							"date_created" 		=> date("Y-m-d H:i:s",time()),
							"verification_question" => $post['verification_question'],
							"verification_answer"   => strtolower($post['verification_answer']),
						);

						$user_id = User::save($record);
						// $my_id = mysql_insert_id();
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

						if($file){
							$this->load->helper('string');
							$random_key = random_string('unique');

							$file_extension	= substr($file['name'], -3);
							$raw_name 		= $this->encrypt->sha1(basename($file['name']."$random_key")).date("hims",time());
							$upload_name 	= $file['name'];
							$file_size 		= $file['size'];

							$dir = "files/photos/tmp/users/";
							if(!is_dir($dir)) {
								mkdir($dir,0777,true);
							}

							$path = "$dir/$raw_name.{$file_extension}";
								
							if(move_uploaded_file($file['tmp_name'], $path)) {

								$arr = array(
									"display_image_url" => $raw_name . "." . $file_extension
								);

								User::save($arr,$user_id);
								// echo $path;
							}
						}
							$name = $post['firstname'] . " " . $post['lastname'];
							// $notif = array(
							// 	"created_by"	=> $session_id,	
							// 	"message" 		=> "created user account <a href='javascript:void(0);' onclick='javascript:window.location.href=" . "users" . "; view_user_profile(" . $user_id . ")'>{$name}</a>",
							// 	// "date_created" 		=> date("Y-m-d H:i:s"),
							// );
							// $notif_id = Notification::save($notif);

							// $users = User::findAllActiveUser();
							// foreach ($users as $key => $value) {
							// 	$notify = array(
							// 		"user_id" 	=> $value['id'],
							// 		"notif_id" 	=> $notif_id,
							// 		"date"		=> date("Y-m-d H:i:s")
							// 		);
							// 	Notification_Flag::save($notify);
							// }

							/* ACTIVITY TRACKER */

							$act_tracker = array(
								"module"		=> "rpc_management_user",
								"user_id"		=> $session_id,
								"entity_id"		=> $user_id,
								"message_log" 		=> $session_user['name'] ." ". "created user account <a href='javascript:void(0);' class='track_users' data-id='" . $user_id . "'>{$name}</a>",
								"date_created" 	=> date("Y-m-d H:i:s"),
							);
							Activity_Tracker::save($act_tracker);

						$json['is_successful'] 	= true;
						$json['message']		= "Successfully Added {$name} to database!";

						//New Notification
						$msg = $session_user['name'] . " has successfully Added User Account: {$name}.";

						$this->pusher->trigger('my_notifications', 'notification', array(
							'message' => $msg,
							'title' => "Added User Account " . $name,
							'type' => 'info'
						));

				} else { // else for checking exist data
					$json['is_successful'] 	= false;
					$json['message']		= "Username already exist.";
				}
				
				
			}

		echo json_encode($json);
	}

	

	

	function getAllUserlist() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();
		$user_type_id 	= User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mu_default		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 4));
		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper("DESC");

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				
				0 => "email_address",
				1 => "lastname",
				2 => "firstname",
				3 => "middlename",
				4 => "account_type",
				5 => "id",
				// 6 => "id",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$all_users 	= User::generateUserDatatable($params);
			$total_records 	= User::countUserDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($all_users as $key=>$value):
	
				if($mu_default['can_update'] && $mu_default['can_delete']){
					if($value['account_type'] == "Super Admin"){
						$action_link = '<a href="javascript: void(0);" onclick="javascript: edit_user_access(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
					} else {
						$action_link = '
							<a href="javascript: void(0);" onclick="javascript: edit_user_access(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
							<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
							<a href="javascript:void(0);" onclick="javascript: delete_user('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
						';
					}
				} else if ($mu_default['can_update'] && !$mu_default['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onclick="javascript: edit_user_access(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
				} else if (!$mu_default['can_update'] && $mu_default['can_delete']){
					if($value['account_type'] == "Super Admin"){
						$action_link = '';
					} else {
						$action_link = '
							<a href="javascript:void(0);" onclick="javascript: delete_user('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
						';
					}
				} else {
					$action_link = '';
				}

				if($mu_default['can_view']) {
					$email 		= '<a href="javascript: void(0);" onclick="javascript: view_user_profile('. $value['id'] .')">' . $value['email_address'] . "</a>";
					$lastname 	= '<a href="javascript: void(0);" onclick="javascript: view_user_profile('. $value['id'] .')">' . convert_word($value['lastname']) . "</a>";
					$firstname  = '<a href="javascript: void(0);" onclick="javascript: view_user_profile('. $value['id'] .')">' . convert_word($value['firstname']) . "</a>";
					$middlename = '<a href="javascript: void(0);" onclick="javascript: view_user_profile('. $value['id'] .')">' . convert_word($value['middlename']) . "</a>";
					$account_type = '<a href="javascript: void(0);" onclick="javascript: view_user_profile('. $value['id'] .')">' . $value['account_type'] . "</a>";
				} else {
					$email 		= $value['email_address'];
					$lastname 	= convert_word($value['lastname']);
					$firstname 	= convert_word($value['firstname']);
					$middlename = convert_word($value['middlename']);
					$account_type = convert_word($value['account_type']);
				}
				
				$date = date_create($value['date_created']);
				$row = array(
					
					'0' => $email,
					'1' => $lastname,
					'2' => $firstname,
					'3' => $middlename,
					'4' => $account_type,
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

	// CONTACT INFORMATIONS

	function contact_information_list() {
		Engine::XmlHttpRequestOnly();
		$post 					= $this->input->post();
		$user_id 				= (int) $post['user_id'];
		$contact_information 	= User_Contact::findContactsById(array("id"=>$user_id));
		$view_only				= $post['val'];
		if($post && $user_id && $contact_information) {
			$data['user_id'] 				= $user_id;
			$data['view_only']				= $view_only;
			$data['contact_information'] 	= $contact_information;
			$this->load->view('admin/user_contact_list',$data);
		}
	}

	function edit_contact_information_form() {
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['id']			= $id = (int) $post['id'];
		$data['user_id']	= $user_id 	= (int) $post['user_id'];
		$data['contact'] 	= $contact = User_Contact::findContactByUserId(array("user_id"=>$user_id, "id" => $id));
		$this->load->view('admin/forms/edit_contact_information',$data);
	}

	function save_contact_information(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$record = array(
			"contact_type"  => $post['contact_type'],
			"contact_value" => $post['contact_value'],
			"extension" 	=> $post['extension'],
		);
		User_Contact::save($record, $post['id']);
	}

	function delete_contact_information_form() {
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();

		$data['id']			= $id = (int) $post['id'];
		$data['user_id']	= $user_id 	= (int) $post['user_id'];
		$data['contact'] 	= $contact = User_Contact::findContactByUserId(array("user_id"=>$user_id, "id" => $id));
		$this->load->view('admin/forms/delete_contact_information',$data);
	}

	function delete_contact_information(){
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();
		$id = (int) $post['id'];
		User_Contact::delete($id);
	}
	// ROLES

	function getAllRolelist() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();
		$user_type_id 	 = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mu_roles 	= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 5));
		
		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "id",
				1 => "role_name",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$all_users_role = User_Type::generateUserRolesDatatable($params);
			$total_records 	= User_Type::countUserRolesDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($all_users_role as $key=>$value):
					// $value['role_name'] == "Super Admin"
			
				if($mu_roles['can_update'] && $mu_roles['can_delete']){
					if($value['user_type'] == "Super Admin"){
						if($value['user_type'] == "Super Admin" && $session['account_type'] == "Super Admin"){
							$action_link = '
								<a href="javascript: void(0);" onClick="javascript: edit_user_role(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
							';
						} else {
							$action_link = '';
						}
					} else {
						$action_link = '
							<a href="javascript: void(0);" onClick="javascript: edit_user_role(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
							<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
							<a href="javascript: void(0);" onClick="javascript: delete_user_roles(' . $value['id'] . ');" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
						';
					}	
				} else if($mu_roles['can_update'] && !$mu_roles['can_delete']){
					if($value['user_type'] != "Super Admin"){
						$action_link = '
							<a href="javascript: void(0);" onClick="javascript: edit_user_role(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						';
					} else {
						if($value['user_type'] == "Super Admin" && $session['account_type'] == "Super Admin"){
							$action_link = '
								<a href="javascript: void(0);" onClick="javascript: edit_user_role(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
							';
						} else {
							$action_link = '';
						}
					}

				} else if (!$mu_roles['can_update'] && $mu_roles['can_delete']) {
					if($value['user_type'] != "Super Admin"){
						$action_link = '
							<a href="javascript: void(0);" onClick="javascript: delete_user_roles(' . $value['id'] . ');" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
						';
					} else {
						$action_link = '';
					}
				}
			
				
				if($mu_roles['can_view']){
					$view_link = '<a href="javascript: void(0);" onclick="javascript: view_roles(' . $value['id'] . ');">' . $value['user_type'] . '</a>';
				} else {
					$view_link = $value['user_type'];
				}
				
				
				$row = array(
					
					'0' => $view_link,
					'1' => $action_link,

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

	function getAllModulelist() {
		Engine::XmlHttpRequestOnly();

		$get 		= $this->input->get();
		$session 	= $this->session->all_userdata();
		$user_type_id = User_Type::findByName( array("user_type" => $session['account_type'] ));
		$mu_ms		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 12));
		if($get) {

			$sorting 	= (int) $get['iSortCol_0'];
			$query   	= $get['sSearch'];
			$order_type	= strtoupper($get['sSortDir_0']);

			$display_start 	= (int) $get['iDisplayStart'];

			// header fields
			$rows = array(
				0 => "scope",
				1 => "module",
				2 => "id",
			);

			$order_by 	= $rows[$sorting] . " {$order_type}";
			$limit  	= $display_start . ", 10";

			 $params = array(
			 	"search" 	=> $query,
			 	"fields" 	=> $fields,
			 	"order"  	=> $order_by,
			 	"limit"  	=> $limit,
			 );

			$activeModules = User_Module_List::generateModuleScopeListDatatable($params);
			$total_records 	= User_Module_List::countModuleScopeListDatatable($params);

			$output = array(
				"sEcho" => $get['sEcho'],
				"iTotalRecords" => $total_records,
				"iTotalDisplayRecords" => $total_records,
				"aaData" => array()
			);


			foreach($activeModules as $key=>$value):

				if($mu_ms['can_update'] && $mu_ms['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_module_scope(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
						<span style="margin: 0 5px 0 5px; text-align: center;"><img src="'. BASE_FOLDER .'themes/images/line.png"></span>
						<a href="javascript:void(0);" onclick="javascript: delete_module_scope('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				} else if($mu_ms['can_update'] && !$mu_ms['can_delete']){
					$action_link = '
						<a href="javascript: void(0);" onClick="javascript: edit_module_scope(' . $value['id'] . ');" class="edit_user table_icon" original-title="Edit"><i class="glyphicon glyphicon-edit"></i></a>
					';
				}else if(!$mu_ms['can_update'] && $mu_ms['can_delete']){
					$action_link = '
						<a href="javascript:void(0);" onclick="javascript: delete_module_scope('.$value['id'].')" class="delete_user table_icon" original-title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
					';
				} else {
					$action_link = '';
				}		
				
				$row = array(
					
					'0' => $value['scope'],
					'1' => $value['module'],
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

	function loadFormModuleScope(){
		Engine::XmlHttpRequestOnly();

		$this->load->view('admin/forms/add_module_scope',$data);
	}

	function loadEditFormModuleScope(){
		Engine::XmlHttpRequestOnly();
		$post 	= $this->input->post();
		$module = User_Module_List::findById(array("module_id" => $post['id'])); 
		if($module){
			$data['module'] = $module;
			$this->load->view('admin/forms/edit_module_scope',$data);
		}
		
	}

	function saveModuleScope(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);
		$session_user = $this->session->all_userdata();
		$post = $this->input->post();
		if($post){

			if($post['module_id']){
				$record = array(
					"scope"  => $post['scope'],
					"module" => $post['module'],
					"status" => $post['status'],
				);
				$module_id = User_Module_List::save($record,$post['module_id']);

				/* ACTIVITY TRACKER */

				$act_tracker = array(
					"module"		=> "rpc_management_user_permissions",
					"user_id"		=> $session_id,
					"entity_id"		=> $module_id,
					"message_log" 		=>$session_user['name'] ." ".  "updated <a href='javascript:void(0);' class='track_users_module'>Scope: {$post['scope']} - Module: {$post['module']}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);


				$json['is_successful'] 	= true;
				$json['message']		= "Successful Updated Module: {$post['module']} and Scope: {$post['scope']} to database!";

				//New Notification
				$msg = $session_user['name'] . " has successfully Updated Module: {$post['module']} and Scope: {$post['scope']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Updated Module and Scope" . $post['module'],
					'type' => 'info'
				));

			} else {
				$record = array(
					"scope"  => $post['scope'],
					"module" => $post['module'],
					"status" => $post['status'],
				);
				$user_module = User_Module_List::save($record);

				/* ACTIVITY TRACKER */

				$act_tracker = array(
					"module"		=> "rpc_management_user_permissions",
					"user_id"		=> $session_id,
					"entity_id"		=> $user_module,
					"message_log" 	=> $session_user['name'] ." ". "created <a href='javascript:void(0);' class='track_users_module'>Scope: {$post['scope']} - Module: {$post['module']}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message']		= "Successful Added Module: {$post['module']} and Scope: {$post['scope']} to database!";

				//New Notification
				$msg = $session_user['name'] . " has successfully Added Module: {$post['module']} and Scope: {$post['scope']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Added Module and Scope" . $post['module'],
					'type' => 'info'
				));
			}

		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function deleteModuleScope(){
		Engine::XmlHttpRequestOnly();
		$post 		= $this->input->post();

		if($post) {
			$post_id 		= (int) $post['id'];
			$module 		= User_Module_List::findById(array("module_id" => $post_id)); 
			if($module) {
				$data['module'] = $module;
				$this->load->view('admin/forms/delete_module_scope',$data);
			}
		}
	}

	function ExecuteDeleteModuleScope(){
		$post 		= $this->input->post();
		$session_user = $this->session->all_userdata();

		if($post) {
			$post_id 	= (int) $post['module_id'];
			$module 	= User_Module_List::findById(array("module_id" => $post_id)); 

			if($module) {
				$success = true;
				User_Module_List::delete($post_id);

				/* ACTIVITY TRACKER */

				$session = $this->session->userdata('user_id');
				$session_id = $this->encrypt->decode($session);

				$act_tracker = array(
					"module"		=> "rpc_management_user_permissions",
					"user_id"		=> $session_id,
					"entity_id"		=> "0",
					"message_log" 		=> $session_user['name'] ." ". "deleted <a href='javascript:void(0);'>Scope: {$module['scope']} - Module: {$module['module']}</a>",
					"date_created" 	=> date("Y-m-d H:i:s"),
				);
				Activity_Tracker::save($act_tracker);

				$json['is_successful'] 	= true;
				$json['message'] 		= "Successfully deleted!";

				//New Notification
				$msg = $session_user['name'] . " has successfully Deleted Module: {$module['module']} and Scope: {$module['scope']}.";

				$this->pusher->trigger('my_notifications', 'notification', array(
					'message' => $msg,
					'title' => "Deleted Module and Scope" . $module['module'],
					'type' => 'info'
				));
			}
		} else {
			$json['is_successful'] 	= false;
			$json['message']		= "Ooop! Error deleting to database. Please contact web administrator!";
		}

		echo json_encode($json);
	}

	function viewMyProfile(){
		Engine::XmlHttpRequestOnly();

		Engine::appStyle('RPC-style.css');
		Engine::appStyle('forms-style.css');

		Jquery::datatable();

		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);

		$data['user'] 	= $user = User::findById(array("id" => $session_id));
		$data['contact_information'] = $contact_information = User_Contact::findContactsById(array("id"=>$session_id));

		if($user) {
			$this->load->view('admin/viewMyProfile',$data);
		}
	}

	function updateMyProfile(){
		Engine::XmlHttpRequestOnly();


		Engine::appStyle('RPC-style.css');
		Engine::appStyle('forms-style.css');

		Jquery::datatable();

		$session = $this->session->userdata('user_id');
		$session_id = $this->encrypt->decode($session);

		$data['all_users_role'] = $all_users_role = User_Type::findAllActiveUserTypes();
		$data['user'] 	= $user = User::findById(array("id" => $session_id));
		$data['all_question'] = $all_question = Verification_Questions::findAll();

		if($user) {
			$this->load->view('admin/forms/edit_my_profile',$data);
		}
	}


}