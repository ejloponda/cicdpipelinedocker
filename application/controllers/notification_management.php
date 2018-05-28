<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_Management extends MY_Controller {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		$this->load->library('pusher');
	}

	function view_calendar(){
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$notifications = Notification::findbyReceivedByType(array("received_by" => $user_id, "type" => 'calendar'));
		foreach ($notifications as $key => $value) {
			$event 		= Calendar_Events::findById(array("id" => $value['event_id']));
			$type 		= Calendar_Dropdown::findById(array("id" => $event['type']));
			$location 	= Calendar_Dropdown::findById(array("id" => $event['location']));
			// debug_array($location);
			$host 		= User::findById(array("id"=> $event['created_by']));
			$arr[] 	= array(
				"message" 		=> $value['message'],
				"name"			=> $event['title'],
				"location"		=> $location['value'],
				"type"			=> $type['value'],
				"date_created" 	=> $value['date_created'],
				"host"			=> $host['lastname'] . ", " . $host['firstname']
				);
		}

		$data['notifications'] = $arr;
		$this->load->view('notification/calendar',$data);
	}

	function view_tag_notif(){
		$session = $this->session->all_userdata();
		$user_id = $this->encrypt->decode($session['user_id']);
		$notifications = Notification::findbyReceivedByType(array("received_by" => $user_id, "type" => 'tag notification'));
		foreach ($notifications as $key => $value) {
			$host 		= User::findById(array("id"=> $user_id));
			$arr[] 	= array(
				"message" 		=> $value['message'],
				"name"			=> 'Low Medicine Stock',
				"date_created" 	=> $value['date_created'],
				"host"			=> $host['lastname'] . ", " . $host['firstname']
				);
		}
		$data['notifications'] = $arr;
		$this->load->view('notification/tag_notif',$data);
	}
}