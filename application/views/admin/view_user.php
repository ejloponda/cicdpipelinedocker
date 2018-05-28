<script>
	$(function() {
		contact_information_list(<?php echo $user['id']; ?>,true);
	});
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-users.png"></li>
			<li><h1>User Information</h1></li>
		</ul>
		
		<ul id="controls">
			<?php if($mu_default['can_update']){?>
			<li><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png" onclick="javascript: edit_user_access(<?php echo $user['id'] ?>);"></li>
			<?php } ?>
			<?php if($mu_default['can_update'] && $mu_default['can_delete']){?>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php } ?>
			<?php if($mu_default['can_delete']){?>
			<li><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_trash.png" onclick="javascript: delete_user(<?php echo $user['id'] ?>);"></li>
			<?php } ?>
		</ul>
		<div class="clear"></div>
	</hgroup>
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
				<h1>User Details</h1><br/>
						<strong>Name:</strong> 
						<?php echo convert_word($user['firstname']) ?> <?php echo convert_word($user['middlename']) ?> <?php echo convert_word($user['lastname']) ?>
				<section class="clear"></section>
						<strong>Address: </strong> 
						<?php echo wordwrap($user['address'],70,"<br/>\n") ?>
						<br/>
						<?php echo wordwrap($user['address_2'],70,"<br/>\n") ?>
	
				<section class="clear"></section>
						
		</section>	
		
		<section id="right">
				<h1>Photo</h1><br/>
						
				<?php if($user['display_image_url'] != "") { ?>
					<img style="width:120px;" src="<?php echo BASE_FOLDER; ?>files/photos/tmp/users/<?php echo $user['display_image_url'] ?>">
				<?php } else { ?>
					<img src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
				<?php } ?>
				<section class="clear"></section>
						
				</form>
				<section class="clear"></section>
		</section>	
	
		<div class="line02"></div>
		
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
				<h1>Contact Details</h1><br/>

				<strong>Email Address: </strong> 
						<?php echo $user['email_address'] ?>
				<!-- <section class="clear"></section> -->
				 
				<div id="contact_information_list_wrapper" style="10px;"></div>
				<section class="clear"></section>

		</section>

		<section id="right">
			<h1>Account Details</h1><br/>
					<strong>User Name: </strong> 
					<?php echo convert_word($user['username']) ?>
			<section class="clear"></section>
					<strong>Account Type: </strong> 
					<?php echo $user['account_type'] ?>
			<section class="clear"></section>
					<strong>Account Status: </strong> 
					<?php echo $user['account_status'] ?>
			<section class="clear"></section>		
		</section>
</section>	
<!-- <section class="clear"></section> -->


