<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $page_title; ?> </title>
	<link rel="icon" type="image/x-icon" href="<?php echo BASE_FOLDER; ?>themes/images/favicon.ico"/>
</head>

<script src="<?php echo BASE_FOLDER.'themes/bootstrap/bootstrap-init.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/bootstrap/bootstrap.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/bootstrap/bootstrap.min.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/jquery/jquery-ui.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/js/jquery.serialize-object.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/jquery/blockUi/block.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/js/activity_tracker.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/jquery/tipsy/jquery.tipsy.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/jquery/bootside/BootSideMenu.js' ?>"></script>
<script src="<?php echo BASE_FOLDER.'themes/jquery/datetime_picker/build/jquery.datetimepicker.full.js' ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_FOLDER.'themes/jquery/bootside/BootSideMenu.css' ?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASE_FOLDER.'themes/jquery/tipsy/tipsy.css' ?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASE_FOLDER.'themes/jquery/datetime_picker/jquery.datetimepicker.css' ?>">

<?php Engine::get(); ?>

<script>
	var default_ajax_loader 	= "<img style='border:none !important; border-radius: 0 !important;' src='"+BASE_IMAGE_PATH+"default_ajax_loader.gif'>";
	var topright_ajax_loader 	= "<img style='border:none !important; border-radius: 0 !important;' src='"+BASE_IMAGE_PATH+"topright_loader.gif'>";

	//var pusher = new Pusher('e3c363e14534352d529b'); // live server
	var pusher = new Pusher('45aa8c5ecca116b2a369'); // sandbox
	var username = '<?php echo $session = $this->session->userdata("username") ?>';
	// alert(username);
	var channel = pusher.subscribe(username);
	channel.bind('pusher:subscription_succeeded', function(status) {
	  console.log("We are now connected to your private-channel: <?php echo $session = $this->session->userdata('username') ?>");
	});

	channel.bind('calendar_notification', function(data){
		new PNotify({
	      title: data.title,
	      text: data.message,
	      type: data.type,
	      buttons: {
	          sticker: false
	      }
	    });

	    loadCalendarNotification();
	});

	//notification for stocks
	channel.bind('tag_notification', function(data){
		new PNotify({
	      title: data.title,
	      text: data.message,
	      type: data.type,
	      buttons: {
	          sticker: false
	      }
	    });

	    loadTagNotification();
	});

	$(function(){
		$( "#toggle-slide" ).click(function() {
		$("#events-wrapper-unique").toggleClass( "adjust-width" );
		});
		loadCalendarNotification();
		loadTagNotification();
			
	});
	function openNav() {
    document.getElementById("events-wrapper-unique").style.width = "250px";
	}

	function closeNav() {
	    document.getElementById("events-wrapper-unique").style.width = "0";
	}
	

	function loadCalendarNotification(){
		$("#calendar_events_notification").html(default_ajax_loader);
		$.post(base_url + 'notification_management/view_calendar',{}, function(o){
			$("#calendar_events_notification").html(o);
		});

	}

	function loadTagNotification(){
		$("#tag_notification").html(default_ajax_loader);
		$.post(base_url + 'notification_management/view_tag_notif',{}, function(o){
			$("#tag_notification").html(o);
		});
	}
	// console.log("<?php echo $session = $this->session->userdata('username') ?>");
</script>
<body>
	<div>
		<div id="events-wrapper-unique" class="sidenav">
			<div class="events-list">
				<h1 class="event-heading">Events</h1>
				<div id="calendar_events_notification">

				</div>
			</div>
			<div class="events-list">
				<h1 class="event-heading notif-heading">Notifications</h1>
				<div id="tag_notification">

				</div>
			</div>
			<div class="side-toggler" id="toggle-slide">
				<span class="glyphicon glyphicon-chevron-left right-icon">&nbsp;</span>
			</div>
		</div>
	</div>
	<div id="alert_confirmation_wrapper" style="position:absolute; z-index: 9999; width: 100%;"></div>
	<section id="wrapper">
		<div class="hidden user-welcome"><?php include ('user-welcome-top.php'); ?></div>
		<div class="hidden user-topbar"><?php include ('user-topbar.php'); ?></div>
		<div class="hidden user-level-topbar"><?php include ('user-level-topbar.php'); ?></div>
		<div class="hidden user-add-patient"><?php include ('user-add-patient-header.php'); ?></div>
		<div class="hidden patient-view-topbar"><?php include ('patient_view_topbar.php'); ?></div>
		<div class="hidden module_management_topbar"><?php include ('module_management_topbar.php'); ?></div>

		
		