<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Convert extends MY_Controller {
	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		$this->load->library('phpexcel');
	}
	
	function index(){
		$data = array(
		        1 => array ('Name', 'Surname'),
		        array('Schwarz', 'Oliver'),
		        array('Test', 'Peter')
		        );

		// generate file (constructor parameters are optional)
		$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
		$xls->addArray($data);
		$xls->generateXML('my-test');

	}

	
	
}