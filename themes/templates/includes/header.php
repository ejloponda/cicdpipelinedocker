<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $page_title; ?> </title>
	<link rel="icon" type="image/x-icon" href="<?php echo BASE_FOLDER; ?>themes/images/favicon.ico"/>
</head>

<script src="<?php echo BASE_FOLDER.'themes/bootstrap/bootstrap-init.js' ?>"></script>
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
	<section id="top02">
			<section class="topbar">
				
				<a href="<?php echo base_url(); ?>"><img class="logo-top" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo2.png"></a>
				
				<section class="red">
					<div class="notify"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon-notification.png"></div>
					<div class="account">
						<ul>
							<a href="<?php echo url("logout"); ?>">
								<li><img src="<?php echo BASE_FOLDER; ?>themes/images/icon-user.jpg"></li>
								<li>Welcome Back,  <?php echo $username ?>! </li>
								<li><img src="<?php echo BASE_FOLDER; ?>themes/images/background-red2.png"></li>
							</a>
						</ul>
					</div>
				</section>
				<div class="clear"></div>
			
			</section>
		<div class="line"></div>
	</section>