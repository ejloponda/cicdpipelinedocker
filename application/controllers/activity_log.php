<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_Log extends MY_Controller {
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

			Engine::appScript('activity_log.js');
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

			/* NOTIFICATIONS */
			Jquery::pusher();
			Jquery::gritter();
			Jquery::pnotify();
			Engine::appScript('notification.js');
			/* END */

			Bootstrap::datetimepicker();
			Bootstrap::datepicker();
			Bootstrap::modal();

			$data['page_title'] = "Activity Log";
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
			$this->load->view('activity_log/index',$data);
		}
	}

	function getIndex(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$data['logs'] = $logs = Activity_Tracker::findAll(array(
			"fields" => array("id,message_log,date_created"),
			"limit"  => "75"
			));
		$this->load->view('activity_log/view_logs',$data);
	}

	function getHumanTimeDifference($date,$granularity=2) {
	    $date = strtotime($date);
	    $difference = time() - $date;
	    $periods = array('decade' => 315360000,
	        'yr' => 31536000,
	        'mon' => 2628000,
	        'wk' => 604800, 
	        'dy' => 86400,
	        'hr' => 3600,
	        'min' => 60,
	        'sec' => 1);

	    foreach ($periods as $key => $value) {
	        if ($difference >= $value) {
	            $time = floor($difference/$value);
	            $difference %= $value;
	            $retval .= ($retval ? ' ' : '').$time.' ';
	            $retval .= (($time > 1) ? $key.'s' : $key);
	            $granularity--;
	        }
	        if ($granularity == '0') { break; }
	    }
	    if($retval) {
	    	return ''.$retval.' ago';
	    } else {
	    	return 'Today';
	    }
	    
	}
}