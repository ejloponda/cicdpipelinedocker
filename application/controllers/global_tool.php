<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_Tool extends CI_Controller {
	function __construct() {
		parent::__construct();
 		$this->load->database();
 		Engine::class_loader();

	}

	function validate_duplicate_email() {
		$post = $this->validate_ajax_post();
		
		if($post) {
			$json['is_duplicate'] = Employee::validate_duplicate_email($post['email_address'], $post['user_id']);
		}

		echo json_encode($json);
	}

	function validate_duplicate_username() {
		$post = $this->validate_ajax_post();
		
		if($post) {
			$json['is_duplicate'] = User::validate_duplicate_username($post['username'], $post['employee_id']);
		}

		echo json_encode($json);
	}

	function validate_ajax_post() {
		Engine::XmlHttpRequestOnly();
		$post = $this->input->post();
		if($post) {
			return $post;
		} else {
			die("Ooops! Error occured. Please contact web administrator!");
		}
	}



}