<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct() {
		parent::__construct();
 		#$this->load->database();
 		Engine::class_loader();

	}

	function index() {
		// $this->welcome();
		#redirect('coming_soon');
		redirect('login');
	}


	function landing() {
		
		//Engine::XmlHttpRequestOnly();
		Engine::appStyle('bootstrap.min.css');
		Engine::appStyle('welcome-style.css');
		Jquery::tipsy();

		$data['page_title'] = "Welcome to Royal Preventive Clinic";
		$data['session'] 	= $session = $this->session->all_userdata();
		$this->load->view('home/index',$data);
	}

	function coming_soon() {
		Engine::appStyle('bootstrap.min.css');
		Engine::appStyle('coming_soon.css');

		$data['page_title'] = "IDRS :: Coming Soon";
		$this->load->view('page/coming_soon',$data);
	}

}