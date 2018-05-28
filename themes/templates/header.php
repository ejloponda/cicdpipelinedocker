<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $page_title; ?> </title>
	<link rel="icon" type="image/x-icon" href="<?php echo BASE_FOLDER; ?>themes/images/favicon.ico"/>
</head>

<script src="<?php echo BASE_FOLDER.'themes/bootstrap/bootstrap-init.js' ?>"></script>
<!--<script src="<?php //echo BASE_FOLDER.'themes/bootstrap/bootstrap.js' ?>"></script>--> <!-- Full version -->
<script src="<?php echo BASE_FOLDER.'themes/bootstrap/bootstrap.min.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/jquery/jquery-ui.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/js/jquery.serialize-object.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/jquery/blockUi/block.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/js/activity_tracker.js' ?>"></script>
<?php Engine::get(); ?>

<script>
	var default_ajax_loader 	= "<img style='border:none !important; border-radius: 0 !important;' src='"+BASE_IMAGE_PATH+"default_ajax_loader.gif'>";
	var topright_ajax_loader 	= "<img style='border:none !important; border-radius: 0 !important;' src='"+BASE_IMAGE_PATH+"topright_loader.gif'>";
</script>

<div id="alert_confirmation_wrapper"></div>
<body>
