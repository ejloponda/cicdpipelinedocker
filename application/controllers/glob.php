<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Glob extends CI_Controller {
	function __construct() {
		parent::__construct();
 		$this->load->database();
 		Engine::class_loader();
	}

	function reset_delivery_plans() {
		$sql = "
			TRUNCATE TABLE rvl_delivery_receipt
		";
		Mapper::runSql($sql,false);
		
		$sql = "
			TRUNCATE TABLE rvl_vn_list
		";

		Mapper::runSql($sql,false);

		$sql = "
			TRUNCATE TABLE rvl_payroll_register
		";

		Mapper::runSql($sql,false);

		$sql = "
			TRUNCATE TABLE rvl_delivery_plan_tracking
		";

		Mapper::runSql($sql,false);

		$sql = "
			TRUNCATE TABLE rvl_delivery_plan_tracking
		";

		Mapper::runSql($sql,false);

		$sql = "
			UPDATE `rvl_truck_list` SET  `remaining` =  '1' WHERE  `rvl_truck_list`.`id` = 1
		";

		Mapper::runSql($sql,false);

		$sql = "
			UPDATE  `rvl_truck_list` SET  `remaining` =  '5' WHERE  `rvl_truck_list`.`id` = 2
		";

		Mapper::runSql($sql,false);

		$sql = "
			UPDATE  `rvl_truck_list` SET  `remaining` =  '7' WHERE  `rvl_truck_list`.`id` = 3
		";

		Mapper::runSql($sql,false);

		$sql = "
			UPDATE  `rvl_truck_list` SET  `remaining` =  '1' WHERE  `rvl_truck_list`.`id` = 4
		";

		Mapper::runSql($sql,false);

		$sql = "
			UPDATE  `rvl_truck_list` SET  `remaining` =  '5' WHERE  `rvl_truck_list`.`id` = 5
		";

		Mapper::runSql($sql,false);

		$sql = "
			UPDATE  `rvl_truck_list` SET  `remaining` =  '7' WHERE  `rvl_truck_list`.`id` = 6
		";

		Mapper::runSql($sql,false);

		$sql = "
			UPDATE  `rvl_truck_list` SET status = 'Available'
		";

		Mapper::runSql($sql,false);
	}

}