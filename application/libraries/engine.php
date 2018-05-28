<?php 
class Engine {

	public static $styles;
	public static $scripts;

	public static $direct_styles;
	public static $direct_scripts;
	

	public static function get() {
		self::loadAllScript();
		self::loadAllStyles();
		self::loadAllDirectScript();
		self::loadAllDirectStyles();
		self::set_script_route();
	}

	public function loadAllScript() {
		$js = self::$scripts;
		if($js)
		foreach(array_unique($js) as $script):
			$script = BASE_FOLDER.SCRIPT_PATH.$script;
			echo '<script type="text/javascript" src="' . $script . '"></script>';
		endforeach;
	}

	public function loadAllStyles() {
		$css = self::$styles;
		if($css)
		foreach(array_unique($css) as $style):
			$style = BASE_FOLDER.STYLE_PATH.$style;
			echo '<link rel="stylesheet" type="text/css" href="' . $style . '">';
		endforeach;
	}

	public function loadAllDirectScript() {
		$js = self::$direct_scripts;
		if($js)
		foreach(array_unique($js) as $script):
			$script = BASE_FOLDER."themes/".$script;
			echo '<script type="text/javascript" src="' . $script . '"></script>';
		endforeach;
	}

	public function loadAllDirectStyles() {
		$css = self::$direct_styles;
		if($css)
		foreach(array_unique($css) as $style):
			$style = BASE_FOLDER."themes/".$style;
			echo '<link rel="stylesheet" type="text/css" href="' . $style . '">';
		endforeach;
	}

	public static function appScript($filename) {
		if($filename) self::$scripts[] = $filename;
	}

	public static function appStyle($filename) {
		if($filename) self::$styles[] = $filename;
	}

	public static function directStyle($filename) {
		if($filename) self::$direct_styles[] = $filename;
	}

	public static function directScript($filename) {
		if($filename) self::$direct_scripts[] = $filename;		
	}

	public static function class_loader() {
		require_once BASEPATH."helpers/directory_helper".EXT;
		$model_class = directory_map(APPPATH."models", TRUE);
		foreach($model_class as $class):
			if(strtolower(substr($class, -3)) == "php") {
				require_once(APPPATH."models/{$class}");
			}
		endforeach;
	}
	
	public static function set_script_route() {
		echo "
			<script>
				var base_url 		= '" . base_url() . "';
				var BASE_PATH		= '" . site_url() . "';
				var BASE_IMAGE_PATH	= '" . base_url() . "themes/images/';
			</script>
		";
	}

	public static function XmlHttpRequestOnly() {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
			return true;
		} else {
			die(show_error("Oops! Page not found. Please contact web administrator.",404));
		}
	}
}

