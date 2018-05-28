<script>
	$(function() {
		// contact_information_list(<?php echo $user['id']; ?>,true);
	});
</script>
<div class="modal-dialog" style="width:50%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/header-users.png"> <span style="font-size: 28px; margin-top: 10px;"> My Profile </span>
		</div>
		<div class="modal-body">
				<section style="width: 50%; float:left;">
					<h3>User Details</h3>
							<strong>Name:</strong> 
							<?php echo convert_word($user['firstname']) ?> <?php echo convert_word($user['middlename']) ?> <?php echo convert_word($user['lastname']) ?>
					<section class="clear"></section>
							<strong>Address: </strong> 
							<?php echo $user['address'] ?>
							<!-- <br/> -->
							<?php echo $user['address_2'] ?>
					<section class="clear"></section>
				</section>	
				
				<section style="width: 50%; float:left;">
					<h3>Photo</h3>
					<?php if($user['display_image_url'] != "") { ?>
						<img style="width:120px;" src="<?php echo BASE_FOLDER; ?>files/photos/tmp/users/<?php echo $user['display_image_url'] ?>">
					<?php } else { ?>
						<img src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
					<?php } ?>
					<section class="clear"></section>
				</section>	
				<section class="clear"></section>
				<section style="width: 50%; float:left;">
					<h3>Contact Details</h3>
					<strong>Email Address: </strong> 
							<?php echo $user['email_address'] ?>
					<section class="clear"></section>
							<strong>Contact Information: </strong><br/>
							<?php foreach ($contact_information as $key => $value) { ?>
								<?php echo $value['contact_type'] . " - " . $value['contact_value'] ?> <?php echo ($value['extension'] ? " - " . $value['extension'] : "") ?> <br/>
							<?php } ?>
					<section class="clear"></section>
				</section>
				<section style="width: 50%; float:left;">
					<h3>Account Details</h3>
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
				<section class="clear"></section>	
			</div>
		<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="javascript: editMyProfile();">Update Profile</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>