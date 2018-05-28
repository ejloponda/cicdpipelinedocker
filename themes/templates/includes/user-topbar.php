<section id="top">

	<div class="logo">
		<a href="<?php echo base_url(); ?>">
			<img class="logo-top" src="<?php echo BASE_FOLDER; ?>themes/images/RPC-logo.png">
		</a>
	</div>
	
	<div class="rightside">
		
		
		<ul class="red">
			<li class="notify show_myLastNotif" title="Show Notification"><img src="<?php echo BASE_FOLDER; ?>themes/images/icon-notification.png"></li>
			<li class="account">
				<?php if($user['display_image_url'] != "") { ?>
					<img style="width:20px; height:20px;" src="<?php echo BASE_FOLDER; ?>files/photos/tmp/users/<?php echo $user['display_image_url'] ?>">
				<?php } else { ?>
					<img src="<?php echo BASE_FOLDER; ?>themes/images/icon-user.jpg">
				<?php } ?>
				Welcome Back, <?php echo $username ?>!	
				<ul class="sub">
					<li><a href="javascript: void(0);" onClick="javascript: viewMyProfile();">View Profile</a></li>
					<li><a href="#">Permissions</a></li>
					<li><a href="<?php echo url("logout"); ?>">Logout</a></li>
				</ul>

			</li>
		</ul>
	</div>

<div class="line"></div>
</section>