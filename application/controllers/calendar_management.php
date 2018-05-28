<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_Management extends MY_Controller {

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

			Engine::appScript('calendar_management.js');
			Engine::appScript('topbars.js');
			Engine::appScript('confirmation.js');
			Engine::appScript('profile.js');

			
			//Engine::appScript('calendar/lib/jquery.min.js');
			Engine::appScript('calendar/lib/moment.min.js');
			Engine::appScript('calendar/fullcalendar.js');
			Engine::appScript('calendar/fullcalendar.min.js');
			//Engine::appStyle('fullcalendar.min.css');
			Engine::appStyle('fullcalendar.css');
			//Engine::appStyle('fullcalendar.print.css');
			/* NOTIFICATIONS */
			Engine::appScript('jquery.tagsinput.min.js');
			Engine::appStyle('jquery.tagsinput.min.css');

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

			//Bootstrap::datetimepicker();
			Bootstrap::datepicker();

			$data['page_title'] = "Calendar Management";
			$data['session'] 	= $session = $this->session->all_userdata();
			$data['user'] 		= $user = User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
			$user_type_id 		= User_Type::findByName( array("user_type" => $user['account_type'] ));
			$data['roles']		= RPC_User_Access_Permission::findPermissionByUserType(array("user_type_id" => $user_type_id['id']));
			
			/* PATIENT MANAGEMENT */
			$data['pm_fmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 1));
			$data['pm_pi']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 2));
			$data['pm_pmh']		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 3));

			/* ORDER MANAGEMENT */
			$data['om_order']   = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 27));

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
			$data['reps'] 		= RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type_id['id'], "user_module_id" => 27));
			$data['username']	= $session['firstname'];
			$this->load->view('calendar/index',$data);

		}
	}

	function getIndex(){
		Engine::XmlHttpRequestOnly();
		$data['patient'] = $patient = Patients::findAll();
		$data['doctor']	 = $doctor  = Doctors::findAll();
		$data['user']	 = $user 	= User::findAll();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$data['user_id'] = $user_id;


		$this->load->view('calendar/view_calendar',$data);
	}

	function loadCalendar(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		if($post){
			$from_date = strtotime($post['from_date']);
			$to_date = strtotime($post['to_date']);
			$data['from_date'] = empty($from_date) ? 'empty' : $from_date;
			$data['to_date']   = empty($to_date) ? 'empty' : $to_date;;
			$to_date = $post['to_date'].' 23:59:59';
			$all_events =  Calendar_Events::findAllbyDateRange(array("from_date"=>$post['from_date'], "to_date"=>$to_date));
		}
		
		$this->load->view('calendar/calendar',$data);
	}

		function loadCalendarByUser(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['user']   = empty($post['user_id']) ? 'empty' : $post['user_id'];
		if ($post) {
			$all_events = Calendar_Invites::findAllInviteesByUserID(array("user_id" => $post['user_id']));
		}
		/*if($post){
			$from_date = strtotime($post['from_date']);
			$to_date = strtotime($post['to_date']);
			$data['from_date'] = empty($from_date) ? 'empty' : $from_date;
			$data['to_date']   = empty($to_date) ? 'empty' : $to_date;;

			$all_events =  Calendar_Events::findAllbyDateRange(array("from_date"=>$post['from_date'], "to_date"=>$post['to_date']));
		}*/
		
		$this->load->view('calendar/calendar',$data);
	}

	function save_event(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		#debug_array($post);	
		#$tag = explode(',',$post['tags'][0]);
		
		#debug_array(implode(',', $tag));
		//New Date

		$startdate = date("D M d Y H:i:s ", strtotime($post['start_datetimepicker']));
		$enddate   = date("D M d Y H:i:s ", strtotime($post['end_datetimepicker']));
		$new_startdate = $startdate .'GMT+0800';
		$new_enddate = $enddate .'GMT+0800';

		if($post['allDay'] == 'false'){
			$allDay = '';
		}else{
			$allDay = $post['allDay'];
		}

		if($post['event_id']){
			
			$event_id = $post['event_id'];

			//DELETE ALL KEYWORDS IN EVENT_ID THEN SAVE AGAIN THE NEW ONE
			Calendar_Keywords::deleteByEvendtId($event_id);
			if(isset($post['keywords'])){
				foreach ($post['keywords'] as $key => $value) {
					$keywords = array(
						"event_id" => $event_id,
						"keywords" => $value
					);
					Calendar_Keywords::save($keywords);
				}
			}

			$type = Calendar_Dropdown::findByEventType(array("id" => $post['event_type']));
			
			//Edit to Type: Birthday
			if($type['value'] == 'Birthday'){
				//Get Patient
				foreach ($post['patient'] as $key => $value) {
					$patient_id = $value;
				}
				
				$event = Calendar_Events::findById(array("id"=>$event_id));
				$delete_event = $event_id;//old Event

				$date = date("Y-m-d", strtotime($event['start']));
				$yearBegin = date("Y",strtotime($event['start']));
				$yearEnd = ($yearBegin + 5);
				$years = range($yearBegin, $yearEnd, 1);
				
				$start = date("M j", strtotime($event['start']));
				$end   = date("M j", strtotime($event['end']));

				//Deleting old event
				Calendar_Events::delete($delete_event);
				Calendar_Patient::deleteByEventId($delete_event);
				Calendar_Invites::deleteByEventId($delete_event);

				foreach($years as $year){
					$day1 = $start ." ". $year;
					$event_start_day = date("D",strtotime($day1));

					$day2 = $end ." ". $year;
					$event_end_day = date("D",strtotime($day2));
					
					$event_start = ( $event_start_day ." ". $start ." ". $year ." 00:00:00 " ."GMT+0000");
					$event_end   = ( $event_end_day ." ". $end ." ". $year ." 00:00:00 " ."GMT+0000");
			       
			       $event = array(
						"start" 		=> $event_start,
						"end"			=> $event_end,
						"start_db" 		=> date("Y-m-d H:i:s a", strtotime($event_start)),
						"end_db" 		=> date("Y-m-d H:i:s a", strtotime($event_end)),
						"title" 		=> $post['event_name'],
						"description"	=> $post['patient_name'],
						"patient_id"	=> $patient_id,
						"allDay"		=> $allDay,
						"color"			=> $post['color_events'],
						"type"			=> $post['event_type'],
						"details"		=> $post['details'],
						"category"		=> $post['category'],
						"location"		=> $post['location'],
						"status"		=> $post['status'],
						"datetime_created" => date("Y-m-d H:i:s"),
						"datetime_updated" => date("Y-m-d H:i:s"),
						"created_by"	=> $user_id				
					);
					$event_id = Calendar_Events::save($event);

					$patients = array(
						"patient_id" => $patient_id,
						"event_id" => $event_id
					);				
					Calendar_Patient::save($patients);				
			    }
			    
			}else{

				$event = Calendar_Events::findById(array("id"=>$event_id));
				$patient_id = $event['patient_id'];
				$yearBegin = $event['start'];

				//Deleting Birthday events of a patient if Birthday Change into other Type of Event
				if($event['color'] == '#76B490'){
					if($patient_id != 0){
						Calendar_Events::deleteBirthdayEvent(array("id" => $patient_id, "date" => $yearBegin));
					}	
				}
				
				$event = array(
					"start" 		=> $new_startdate,//$post['start'],
					"end"			=> $new_enddate,//$post['end'],
					"start_db" 		=> date("Y-m-d H:i:s a", strtotime($post['start_datetimepicker'])), //date("Y-m-d H:i:s a", strtotime($post['start'])),
					"end_db" 		=> date("Y-m-d H:i:s a", strtotime($post['end_datetimepicker'])), //date("Y-m-d H:i:s a", strtotime($post['end'])),
					"title" 		=> $post['event_name'],
					"description"   => $post['patient_name'],
					"color"			=> $post['color_events'],
					"type"			=> $post['event_type'],
					"details"		=> $post['details'],
					"category"		=> $post['category'],
					"location"		=> $post['location'],
					"status"		=> $post['status'],
					"patient_id"	=> $post['patient_id'],
					"allDay"		=> $allDay,
					"datetime_updated" => date("Y-m-d H:i:s")
				);
				Calendar_Events::save($event, $event_id);
			}

			/*$event = array(
				"title" 		=> $post['event_name'],
				"description"   => $post['patient_name'],
				"color"			=> $post['color_events'],
				"type"			=> $post['event_type'],
				"details"		=> $post['details'],
				"category"		=> $post['category'],
				"location"		=> $post['location'],
				"status"		=> $post['status'],
				"patient_id"	=> $post['patient_id'],
				"datetime_updated" => date("Y-m-d H:i:s"),
				"allDay"		=>$allDay,
			);
			Calendar_Events::save($event, $event_id);*/

			if(isset($post['invitees'])){
				foreach ($post['invitees'] as $key => $value) {
					$invite = array(
						"user_id" => $value,
						"event_id" => $event_id
						);
					Calendar_Invites::save($invite);
				}
			}

			if(isset($post['remove_users'])){
				foreach ($post['remove_users'] as $key => $value) {
					Calendar_Invites::delete($value);
				}
			}

			if(isset($post['patient'])){
				foreach ($post['patient'] as $key => $value) {
					$patients = array(
						"patient_id" => $value,
						"event_id" => $event_id
						);
					$save_patient = Calendar_Patient::findByEventIdandPatientId(array("event_id" => $event_id, "patient_id" => $value));
					if(!isset($save_patient)){
						Calendar_Patient::save($patients);
					}
				}
			}

			if(isset($post['remove_patients'])){
				#debug_array('here');
				foreach ($post['remove_patients'] as $key => $value) {
					Calendar_Patient::delete($value);
				}
			}

			$json['status'] 		= "edit";
			$json['message'] 		= "Successfully updated event: {$post['event_name']} ";
			$json['is_successful'] 	= true;
			$msg = $session['name'] . " has successfully updated event: {$post['event_name']}.";
			
		}else{
			
			/*$event = array(
				"start" 		=> $post['start'],
				"end"			=> $post['end'],
				"start_db" 		=> date("Y-m-d H:i:s a", strtotime($post['start'])),
				"end_db" 		=> date("Y-m-d H:i:s a", strtotime($post['end'])),
				"title" 		=> $post['event_name'],
				"description"	=> $post['patient_name'],
				"patient_id"	=> $post['patient_id'],
				"allDay"		=> $allDay,
				"color"			=> $post['color_events'],
				"type"			=> $post['event_type'],
				"details"		=> $post['details'],
				"category"		=> $post['category'],
				"location"		=> $post['location'],
				"status"		=> $post['status'],
				"datetime_created" => date("Y-m-d H:i:s"),
				"datetime_updated" => date("Y-m-d H:i:s"),
				"created_by"	=> $user_id				
				);
			$event_id = Calendar_Events::save($event);*/

			$type = Calendar_Dropdown::findByEventType(array("id" => $post['event_type']));
			
			if($type['value'] == 'Birthday'){
				//Get Patient ID
				foreach ($post['patient'] as $key => $value) {
					$patient_id = $value;
				}

				$date = date("Y-m-d", strtotime($post['start']));
				$yearBegin = date("Y",strtotime($post['start']));
				$yearEnd = ($yearBegin + 5);
				$years = range($yearBegin, $yearEnd, 1);
				
				$start = date("M j", strtotime($post['start']));
				$end   = date("M j", strtotime($post['end']));


				foreach($years as $year){
					$day1 = $start ." ". $year;
					$event_start_day = date("D",strtotime($day1));

					$day2 = $end ." ". $year;
					$event_end_day = date("D",strtotime($day2));
					
					$event_start = ( $event_start_day ." ". $start ." ". $year ." 00:00:00 " ."GMT+0000");
					$event_end   = ( $event_end_day ." ". $end ." ". $year ." 00:00:00 " ."GMT+0000");
			       
			       $event = array(
						"start" 		=> $event_start,
						"end"			=> $event_end,
						"start_db" 		=> date("Y-m-d H:i:s a", strtotime($event_start)),
						"end_db" 		=> date("Y-m-d H:i:s a", strtotime($event_end)),
						"title" 		=> $post['event_name'],
						"description"	=> $post['patient_name'],
						"patient_id"	=> $patient_id,
						"allDay"		=> $allDay,
						"color"			=> $post['color_events'],
						"type"			=> $post['event_type'],
						"details"		=> $post['details'],
						"category"		=> $post['category'],
						"location"		=> $post['location'],
						"status"		=> $post['status'],
						"datetime_created" => date("Y-m-d H:i:s"),
						"datetime_updated" => date("Y-m-d H:i:s"),
						"created_by"	=> $user_id				
					);
					$event_id = Calendar_Events::save($event);

					$patients = array(
						"patient_id" => $patient_id,
						"event_id" => $event_id
						);
					Calendar_Patient::save($patients);
			    }
			}else{

				$event = array(
					"start" 		=> $new_startdate,//$post['start'],
					"end"			=> $new_enddate,//$post['end'],
					"start_db" 		=> date("Y-m-d H:i:s a", strtotime($post['start_datetimepicker'])), //date("Y-m-d H:i:s a", strtotime($post['start'])),
					"end_db" 		=> date("Y-m-d H:i:s a", strtotime($post['end_datetimepicker'])), //date("Y-m-d H:i:s a", strtotime($post['end'])),
					"title" 		=> $post['event_name'],
					"description"	=> $post['patient_name'],
					"patient_id"	=> $post['patient_id'],
					"allDay"		=> $allDay,
					"color"			=> $post['color_events'],
					"type"			=> $post['event_type'],
					"details"		=> $post['details'],
					"category"		=> $post['category'],
					"location"		=> $post['location'],
					"status"		=> $post['status'],
					"datetime_created" => date("Y-m-d H:i:s"),
					"datetime_updated" => date("Y-m-d H:i:s"),
					"created_by"	=> $user_id				
					);
				$event_id = Calendar_Events::save($event);
			}

			if(isset($post['keywords'])){
				foreach ($post['keywords'] as $key => $value) {
					if($value != null){
						$keywords = array(
							"event_id" => $event_id,
							"keywords" => $value
						);
						Calendar_Keywords::save($keywords);
					}	
				}
			}

			if(isset($post['invitees'])){
				foreach ($post['invitees'] as $key => $value) {
					$invite = array(
						"user_id" => $value,
						"event_id" => $event_id
						);
					Calendar_Invites::save($invite);
				}
			}

			if(isset($post['patient'])){
				foreach ($post['patient'] as $key => $value) {
					$patients = array(
						"patient_id" => $value,
						"event_id" => $event_id
						);
					Calendar_Patient::save($patients);
				}
			}

			// debug_array($event_id);
			$json['status'] 		= "new";
			$json['message'] 		= "Successfully added new event: {$post['event_name']} !";
			$json['is_successful'] 	= true;
			$msg = $session['name'] . " has successfully added new event: {$post['event_name']}."; 
		}
		
		// Set the channel to users username and the event will be calendar_notification
		/*
		-- Channel Name: *dynamic to whatever the users username is.
		-- Event Name: calendar_notification
		// Create loop where you can send all users tag in the calendar event.
		*/

		$this->pusher->trigger('my_notifications', 'notification', array(
			'message' => $msg,
			'title' => "Added " . $post['event_name'],
			'type' => 'info'
		));

		if(isset($post['invitees'])){
			$msgToUser = "You have been invited by " . $session['name'] . " in event : {$post['event_name']}";
			foreach ($post['invitees'] as $key => $value) {
				$alert = User::findById(array("id"=>$value));
				$this->pusher->trigger($alert['username'], 'calendar_notification', array(
						'message' 	=> $msgToUser,
						'title' 	=> $post['event_name'],
						'type' 		=> 'success',
						'datetime' 	=> date("Y-m-d H:i:s")
					));

				$notif = array(
					"message" 		=> $msgToUser,
					"type"			=> 'calendar',
					"event_id"		=> $event_id,
					"received_by" 	=> $value,
					"created_by"	=> $user_id,
					"date_created"	=> date("Y-m-d H:i:s")
					);
				Notification::save($notif);
			}
		}
		
		$json['id']				= $event_id;
		$json['event_name']		= $post['event_name'];

		echo json_encode($json);

	}
	function view2(){
		$from_date 	= $this->uri->segment(3);
		$to_date 	= $this->uri->segment(4);

		$fd = date("Y-m-d", $from_date);
		$td = date("Y-m-d", $to_date);
		$to_date = $td.' 23:59:59';
		$all_events =  Calendar_Events::findAllbyDateRange(array("from_date"=>$fd,"to_date"=>$to_date));
		
	    foreach ($all_events as $key => $value) {
	    	$arr[] = array(
	    		'id' 		=> $value['id'],
	    		'allDay' 	=> $value['allDay'],
	    		'title' 	=> $value['title'],
	    		'color'		=> $value['color'],
	    		'start'		=> $value['start'],
	    		'end'		=> $value['end'],
	    		'description' => $value['description'],
	    		'host' 		=> $value['created_by'],
	    		'status'	=> $value['status'],
	    		);
	    	}
	   		echo json_encode($arr);

	}

	function view3(){
		$user_id 	= $this->uri->segment(3);
		
		$all_events = Calendar_Invites::findAllInviteesByUserID(array("user_id" => $user_id));
		
	    foreach ($all_events as $key => $value) {
	    	$arr2[] = array(
	    		'id' 		=> $value['event_id'],
	    		'allDay' 	=> $value['allDay'],
	    		'title' 	=> $value['title'],
	    		'color'		=> $value['color'],
	    		'start'		=> $value['start'],
	    		'end'		=> $value['end'],
	    		'description' => $value['description'],
	    		'host' 		=> $value['created_by'],
	    		'status'	=> $value['status'],
	    		);
	    	}
	   		echo json_encode($arr2);

	}

	function view(){
		Engine::XmlHttpRequestOnly();
		ini_set('memory_limit','-1');
		$get 		= $this->input->get();

		$from_date 	= $this->uri->segment(3);
		$to_date 	= $this->uri->segment(4);
		//$post = $this->input->post();

		$session 		= $this->session->all_userdata();
		$user 			= User::findById(array("id" => $this->encrypt->decode($session['user_id'])));
		
		$user_type 		= User_Type::findByName(array("user_type" => $user['account_type']));
		/*
		* DO NOT DELETE
		* SAMPLE FOR CREATING NEW PERMISSION TYPE DESIGN
		*/
		/*$permissions 	= RPC_User_Access_Permission::findPermissionByUserTypeIDWithModuleName(array('user_type_id' => $user_type['id']));
		debug_array($permissions);*/
		/*debug_array($user_type);
		die();*/

	    if($from_date){
	    	$all_events =  Calendar_Events::findAllbyDateRange(array("from_date"=>$post['from_date'],"to_date"=>$post['to_date']));
	    	foreach ($all_events as $key => $value) {
	    	$arr[] = array(
	    		'id' 		=> $value['id'],
	    		'allDay' 	=> $value['allDay'],
	    		'title' 	=> $value['title'],
	    		'color'		=> $value['color'],
	    		'start'		=> $value['start'],
	    		'end'		=> $value['end'],
	    		'description' => $value['description'],
	    		'host' 		=> $value['created_by'],
	    		'status'	=> $value['status'],
	    		);
	    	}
	   		echo json_encode($arr);
	    }else{
	    	if($user_type['id']){
			//$events = Calendar_Events::findAll();
	    		$events = Calendar_Events::findAllbyDateRange(array("from_date"=>$get['start'],"to_date"=>$get['end']));
			} else {
				$events = Calendar_Events::findInvitedEventByUserId(array('user_id' => $this->encrypt->decode($session['user_id'])));
			    $all_events = Calendar_Events::findAll();
			    foreach ($all_events as $key => $value) {
			    	$check_if_we_have_event = Calendar_Invites::findByEventsId($value['id']);
			    	if(!count($check_if_we_have_event)){
			    		$arr[] = array(
				    		'id' 		=> $value['id'],
				    		'allDay' 	=> $value['allDay'],
				    		'title' 	=> $value['title'],
				    		'color'		=> $value['color'],
				    		'start'		=> $value['start'],
				    		'end'		=> $value['end'],
				    		'description' => $value['description'],
				    		'host' 		=> $value['created_by'],
				    		'status'	=> $value['status'],
			    		);
			    	}
			    }
			}
		    foreach ($events as $key => $value) {
		    	$arr[] = array(
		    		'id' 		=> $value['id'],
		    		'allDay' 	=> $value['allDay'],
		    		'title' 	=> $value['title'],
		    		'color'		=> $value['color'],
		    		'start'		=> $value['start'],
		    		'end'		=> $value['end'],
		    		'description' => $value['description'],
		    		'host' 		=> $value['created_by'],
		    		'status'	=> $value['status'],
		    		);
		    }
		    echo json_encode($arr);
		    }

	}

	function editEventForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['event_id'] = $event_id = $post['event_id'];
		/* INITIALIZE VARIABLES */
		$data['patient'] 	= $patient = Patients::findAll();
		$data['doctor']	 	= $doctor  = Doctors::findAll();
		$data['event_type'] = Calendar_Dropdown::findByType('type');
		$data['location'] 	= Calendar_Dropdown::findByType('location');
		$keywords= Calendar_Keywords::findByEventsId($event_id);

		//$keywords = Calendar_Keywords::findByEventsId($event_id);
		
		$tag = '';
		foreach ($keywords as $key => $value) {
			$tag .= $value['keywords'].',';
		}

		$string = rtrim($tag, ',');


		/*END*/

		$user 	= User::findAll();
		if($event_id){
			$data['event'] = $event = Calendar_Events::findById(array("id"=>$event_id));
			$invites = Calendar_Invites::findAllInviteesByEventID(array("event_id" => $event_id));
			$patients = Calendar_Patient::findAllPatientByEventID(array("event_id" => $event_id));

			if(count($invites)){
				foreach ($invites as $key => $value) {
					$arr[] = array(
						"id" => $value['user_id'],
						"text" => $value['lastname'] . ", " . $value['firstname'],
						"tag_id" => $value['id']
						);

					$loaded_data[] = $value['user_id'];
				}

				foreach ($user as $key => $value) {
					$users[] = $value['id'];
				}

				$result = array_diff($users, $loaded_data);
				unset($user);
				foreach ($result as $key => $value) {
					$u = User::findById(array("id" => $value));
					$user[] = array(
							"id" => $u['id'],
							"firstname" => $u['firstname'],
							"lastname"	=> $u['lastname']
						);
				}
			}

			if(count($patients)){
				foreach ($patients as $key => $value) {
					$array[] = array(
						"id" => $value['patient_id'],
						"text" => $value['lastname'] . ", " . $value['firstname'],
						"tag_id" => $value['id']
						);

					$loaded_data[] = $value['patient_id'];
				}

				foreach ($patient as $key => $value) {
					$rpc_patient[] = $value['id'];
				}

				$result = array_diff($rpc_patient, $loaded_data);
				unset($rpc_patient);
				foreach ($result as $key => $value) {
					$u = Patients::findById(array("id" => $value));
					$rpc_patient[] = array(
							"id" => $u['id'],
							"firstname" => $u['firstname'],
							"lastname"	=> $u['lastname']
						);
				}
			}
			
			$data['preload_invitees'] = json_encode($arr);
			$data['preload_patients'] = json_encode($array);
		}
		$data['keywords']	= $string;
		$data['user'] = $user;
		$data['patient'] = $patient;
		#debug_array($data);
		$this->load->view('calendar/new_form_calendar',$data);
	}

	function loadEventForm(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$data['event_id'] = $event_id = $post['event_id'];

		/* INITIALIZE VARIABLES */
		$data['patient'] 	= $patient = Patients::findAll();
		$data['doctor']	 	= $doctor  = Doctors::findAll();
		$data['event_type'] = Calendar_Dropdown::findByType('type');
		$data['location'] 	= Calendar_Dropdown::findByType('location');

		/*END*/


		$user 	= User::findAll();
		// debug_array($event_id);
		if($event_id){
			$data['event'] = $event = Calendar_Events::findById(array("id"=>$event_id));
			$invites = Calendar_Invites::findAllInviteesByEventID(array("event_id" => $event_id));
			$patients = Calendar_Patient::findAllPatientByEventID(array("event_id" => $event_id));

			if(count($invites)){
				foreach ($invites as $key => $value) {
					$arr[] = array(
						"id" => $value['user_id'],
						"text" => $value['lastname'] . ", " . $value['firstname'],
						"tag_id" => $value['id']
						);

					$loaded_data[] = $value['user_id'];
				}

				foreach ($user as $key => $value) {
					$users[] = $value['id'];
				}

				$result = array_diff($users, $loaded_data);
				unset($user);
				foreach ($result as $key => $value) {
					$u = User::findById(array("id" => $value));
					$user[] = array(
							"id" => $u['id'],
							"firstname" => $u['firstname'],
							"lastname"	=> $u['lastname']
						);
				}
			}
			
			if(count($patients)){
				foreach ($patients as $key => $value) {
					$array[] = array(
						"id" => $value['patient_id'],
						"text" => $value['lastname'] . ", " . $value['firstname'],
						"tag_id" => $value['id']
						);

					$loaded_data[] = $value['patient_id'];
				}

				foreach ($patient as $key => $value) {
					$rpc_patient[] = $value['id'];
				}

				$result = array_diff($rpc_patient, $loaded_data);
				unset($rpc_patient);
				foreach ($result as $key => $value) {
					$u = Patients::findById(array("id" => $value));
					$rpc_patient[] = array(
							"id" => $u['id'],
							"firstname" => $u['firstname'],
							"lastname"	=> $u['lastname']
						);
				}
			}

			$data['preload_invitees'] = json_encode($arr);
			$data['preload_patients'] = json_encode($array);
		}
		$data['user'] = $user;
		$data['patient'] = $patient;
		$this->load->view('calendar/new_form_calendar',$data);
	}

	function view_event(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();

		$data['event_id'] = $event_id = $post['event_id'];

		if($event_id){
			$data['event'] = $event = Calendar_Events::findById(array("id"=>$event_id));
			#debug_array($event);
			$invites = Calendar_Invites::findAllInviteesByEventID(array("event_id" => $event_id));
			$patient = Calendar_Patient::findAllPatientByEventID(array("event_id" => $event_id));

			if(count($invites)){
				foreach ($invites as $key => $value) {
					$arr[] = array(
						"id" 		=> $value['user_id'],
						"name" 		=> $value['lastname'] . ", " . $value['firstname'],
						"tag_id" 	=> $value['id'],
						"status"	=> $value['status']
						);
				}
			}

			/*if(count($patient)){
				foreach ($patient as $key => $value) {
					$patient = Patients::findById(array("id" => $value['patient_id']));
					$array[] = array(
						"id" 		=> $value['patient_id'],
						"name" 		=> $patient['lastname'] . ", " . $patient['firstname'],
						"tag_id" 	=> $value['id'],
						);
				}
			}*/

			if(count($patient)){
				foreach ($patient as $key => $value) {
					$all_ids[] = $value['patient_id'];

				}
				$med_ids = array_keys(array_flip($all_ids));

				foreach($med_ids as $key => $value){
					$patient_name = Patients::findById(array("id" => $value));
					foreach ($patient as $k => $val) {	
						$id = $val['event_id'];
					}
					$array[] = array(
						"id" 		=> $value,
						"name" 		=> $patient_name['lastname'] . ", " . $patient_name['firstname'],
						"tag_id" 	=> $id,
						);
					unset($id);

				}
			}

			$data['invitees'] 	= $arr;
			$data['user_id']	= $user_id;
			$data['type']		= $type = Calendar_Dropdown::findByEventType(array("id" => $event['type']));
			$data['location']	= $location = Calendar_Dropdown::findByLocation(array("id" => $event['location']));
			$data['patient']	= $array;
			$data['user']		= User::findById(array("id" => $event['created_by']));
			//$data['patient']	= $patient = Patients::findById(array("id" => $event['patient_id']));
			$data['event_title'] = addslashes($event['title']);
			$this->load->view('calendar/view_event',$data);
		} else {
			echo "<h3>Unavailable to view this event.</h3>";
		}
	}

	function deleteEvent(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		
		Calendar_Events::delete($post['id']);
	}

	function dragEventUpdate(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		// debug_array($post['start']);
		// debug_array(date("Y-m-d H:i:s", strtotime($post['end'])));
		if($post['allDay'] == 'false'){
			$allDay = '';
		}else{
			$allDay = $post['allDay'];
		}
		$event = array(
			"start" 			=> $post['start'],
			"end"				=> ($post['end'] == 'Invalid date' ? '' : $post['end']),
			"start_db" 			=> date("Y-m-d H:i:s", strtotime($post['start'])),
			"end_db" 			=> date("Y-m-d H:i:s", strtotime($post['end'])),
			"allDay"			=> $allDay,
			"datetime_updated" 	=> date("Y-m-d H:i:s"),
			"created_by"		=> $user_id				
			);
		$event_id = Calendar_Events::save($event,$post['id']);
		$event_data = Calendar_Events::findById(array("id"=>$event_id));
		// debug_array($event_id);
		$json['status'] 		= "new";
		$json['message'] 		= "Successfully Moved Event!";
		$json['is_successful'] 	= true;


		// Set the channel to users username and the event will be calendar_notification
		/*
		-- Channel Name: *dynamic to whatever the users username is.
		-- Event Name: calendar_notification
		// Create loop where you can send all users tag in the calendar event.
		*/
		$this->pusher->trigger('my_notifications', 'notification', array(
			'message' => $session['name'] . " has Moved Event: {$event_data['title']} ",
			'title' => "Moved " . $event_data['title'],
			'type' => 'info'
		));
	}

	function testPusher(){
		$this->pusher->trigger('test', 'calendar_notification', array('message' => 'Hello World','title' => "Test Data",'type' => 'info'));
	}

	function invitees_status(){
		Engine::XmlHttpRequestOnly();
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$post = $this->input->post();
		
		$invitees = Calendar_Invites::findByEventIdandUserId(array("event_id" => $post['event_id'], "user_id" => $post['user_id']));
		$event = Calendar_Events::findById(array("id"=>$post['event_id']));
		//$user     = User::findById(array("id" => $post['user_id']));

		if($post['status_confirm']){
			$status = $post['status_confirm'];
			$msg = $session['name'] . " has confirmed attendance to Event: {$event['title']} ";
			$title = "Confirm Attendance: ";
		}else{
			$status = $post['status_decline'];
			$msg = $session['name'] . " has declined the Event: {$event['title']} ";
			$title = "Decline Attendance: ";
		}
		$record = array(
			"status" 			=>  $status,
		);
		Calendar_Invites::save($record,$invitees['id']);

		$this->pusher->trigger('my_notifications', 'notification', array(
			'message' => $msg,
			'title' => $title . " " . $event['title'],
			'type' => 'info'
		));
	}

	function search_filter(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		$filter_by = $post['filter_by'];
		$search_item = $post['search_item'];

		#debug_array($post);

		if($filter_by == 'event'){
			$results =  Calendar_Events::findByEventName(array("title"=>$search_item));
		}else if($filter_by == 'category'){
			$results =  Calendar_Events::findByCategory(array("category"=>$search_item));
		}elseif ($filter_by == 'keyword') {
			$results = Calendar_Keywords::findByKeywords(array("keywords" =>$search_item));	
		}elseif ($filter_by == 'patient') {
			//$results = Calendar_Events::findByPatientName(array("patient" =>$search_item));
			$patient = Patients::findByPatientName(array("patient" =>$search_item));
			foreach ($patient as $key => $value) {
				$rpc_patient = Calendar_Patient::findByPatientByEventID(array("id" =>$value['id']));

				foreach ($rpc_patient as $key => $value) {
					$event = Calendar_Events::findById(array("id"=>$value['event_id']));
					$array[] = array(
						"id" 		=> $value['event_id'],
						"title" 	=> $value['title'],
						"start_db"  => $event['start'],
						);
				}

			}
			$results = $array;	
		}else{
			$results = Calendar_Events::findAllFields(array("search" =>$search_item));	
		}

		$data['results'] = $results;
		
		$this->load->view('calendar/search_result',$data);
	}

	function search_date_range(){
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();

		$all_events =  Calendar_Events::findAllbyDateRange(array("from_date"=>$post['from_date'], "to_date"=>$post['to_date']));

	}
}