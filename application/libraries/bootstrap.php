<?php

class Bootstrap {

	public static function css() {
		Engine::directStyle("bootstrap/bootstrap.css");
		Engine::directStyle("bootstrap/bootstrap.min.css");
	}

	public static function modal() {
		Engine::directScript('bootstrap/modal.js');
	}

	public static function datetimepicker() {
		Engine::directStyle('bootstrap/datetimepicker/bootstrap-datetimepicker.min.css');
		Engine::directScript('bootstrap/datetimepicker/bootstrap-datetimepicker.min.js');
	}

	public static function datepicker() {
		Engine::directStyle('bootstrap/datepicker/datepicker.css');
		Engine::directScript('bootstrap/datepicker/bootstrap-datepicker.js');
	}

	/*
	public static function css() {
		Engine::directScript("jquery/inline_validation/jquery.validationEngine-en.js");
		Engine::directScript("jquery/inline_validation/jquery.validationEngine.js");
		Engine::directStyle("jquery/inline_validation/validationEngine.jquery.css");
	}
	*/
}

?>