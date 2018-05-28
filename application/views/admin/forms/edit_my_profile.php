<script>
$(function() {
	$(".tipsy").hide();
	$("#add_user_form").validationEngine({scroll:false});
	$("#add_user_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_USER_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			viewMyProfile();
      			$('#update_user_profile_form_wrapper').modal("hide");
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },beforeSubmit: function(o) {
        	$("#account_type").attr("disabled", false);
        },
        dataType : "json"
    });

	// contact_information_list(<?php echo $user['id']; ?>,false);

    $(".add_user_form").live('change', function(e) {
		if($(this).val()) {
			IS_ADD_USER_FORM_CHANGE = true;
		}
	});

    // upload_user_photo();
});
</script>
<div class="modal-dialog" style="width:80%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/header-users.png"> <span style="font-size: 28px; margin-top: 10px;"> My Profile </span>
		</div>
		<div class="modal-body">
			<form id="add_user_form" name="add_user_form" action="<?php echo url('admin/save_new_user'); ?>" method="post" enctype="mutipart/form-data" style="width:100%;"> 
				<section style="width:50%;  float:left;">
						<h3>User Details</h3>
							<input type="hidden" name="id" value="<?php echo $user['id'] ?>">
							<ul id="form">
								<li>First Name<span>*</span></li>
								<li><input type="text" name="firstname" class="textbox validate[required] add_user_form" value="<?php echo convert_word($user['firstname']) ?>"></li>
							</ul>
							<section class="clear"></section>
							<ul id="form">
								<li>Middle Initial</li>
								<li><input type="text" name="middlename" class="textbox add_user_form" value="<?php echo convert_word($user['middlename']) ?>"></li>
							</ul>
							<section class="clear"></section>
							<ul id="form">
								<li>Last Name<span>*</span></li>
								<li><input type="text" name="lastname" class="textbox validate[required] add_user_form" value="<?php echo convert_word($user['lastname']) ?>"></li>
							</ul>
						<section class="clear"></section>
							<ul id="form">
								<li>Address</li>
								<li><input type="text" name="address" class="textbox add_user_form" value="<?php echo $user['address'] ?>" ></li>
							</ul>
						<section class="clear"></section>
							<ul id="form">
								<li></li>
								<li><input type="text" name="address_2" class="textbox add_user_form" value="<?php echo $user['address_2'] ?>" ></li>
							</ul>
				
						<section class="clear"></section>
				</section>
				<!-- <section class="clear"></section> -->
				<section style="width:50%; float:left;">
					<h3>Upload Photo</h3>
							<ul id="form">
								<li>
								<input type="file" name="image" class="textbox02 add_user_form"><br>
								<div>*File should not be larger than 25 MB</div><br>
								<span style="position:relative; left: 48px;">
								<?php if($user['display_image_url'] != "") { ?>
									<img style="width:120px;" src="<?php echo BASE_FOLDER; ?>files/photos/tmp/users/<?php echo $user['display_image_url'] ?>">
								<?php } else { ?>
									<img src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
								<?php } ?>
								</span>
								</li>
							</ul>
						
					<section class="clear"></section>		
				</section>
				<section class="clear"></section>
				<section style="width:50%;  float:left;">
					<h3>Contact Details</h3>
						<ul id="form">
								<li>Email Address<span>*</span></li>
								<li><input type="text" name="email" class="textbox validate[required,custom[email]] add_user_form" value="<?php echo $user['email_address'] ?>"></li>
							</ul>
						<section class="clear"></section>
							<ul id="form">
								<li>Contact Information</li>
							</ul>
							<section class="clear"></section>
							<div id="contact_information_list_wrapper" style="width: 95%;"></div>
							<div id="add_contact_information_wrapper">
								<ul class="contact">
									<li>
										<select name="contact" id="contact_information_type" class="select02 add_user_form" onchange="javascript:filter_contact_extension();">
											<option value="Mobile">Mobile</option>
											<option value="Work">Work</option>
											<option value="Home">Home</option>
											<option value="Fax">Fax</option>
										</select>
									</li>
									<li><input type="text" id="contact_information_extension" name="contact_extension" class="textbox" style="width: 60px; display:none;" placeholder="Extension"></li>
									<li><input type="text" id="contact_information_value" name="contact" class="textbox add_user_form" style="width:140px;"></li>
									
									<li><button type="button" id="add_contact_information_button">+Add Number</button></li>
								</ul>	
							</div>
						<section class="clear"></section>
				</section>
				<section style="width:50%;  float:left;">
						<h3>Account Details</h3>
							<ul id="form">
								<li>User Name<span>*</span></li>
								<li><input type="text" name="username" class="textbox validate[required] add_user_form" value="<?php echo $user['username'] ?>"></li>
							</ul>
							
							<section class="clear"></section>
							
							<ul id="form">
								<li>Password<span>*</span></li>
								<li><input type="password" name="password" id="password" placeholder="Leave it blank to ignore" class="textbox validate[optional,minSize[6]] add_user_form" ></li>
							</ul>
							<section class="clear"></section>
							<ul id="form">
								<li>Retype Password</li>
								<li><input type="password" name="confirmpassword" placeholder="Leave it blank to ignore" class="textboxadd_user_form validate[optional,minSize[6],equals[password]]"></li>
							</ul>	
						
						<section class="clear"></section>

							<ul id="form">
								<li>Verification Question<span>*</span></li>
								<li>
									<select id="verification_question" name="verification_question" class="select add_user_form" style="width: 287px;" >
										<?php
										if($user['account_type']){
											foreach ($all_question as $key => $value) {
											?>
												<option value="<?php echo $value['id'] ?>" <?php echo ($user['verification_question'] == $value['id'] ? "selected" : "") ?>><?php echo $value['question'] ?></option>
											<?php 
											}
										} else {
											echo "<option value=''></option>"; 
										}
											?>	
		
									</select>
								</li>
							</ul>
							<section class="clear"></section>
							<ul id="form">
								<li>Answer</li>
								<li><input type="password" name="verification_answer" class="textboxadd_user_form validate[optional,minSize[6]]"></li>
							</ul>	

						<section class="clear"></section>
						
							<ul id="form">
								<li>Account Type</li>
								<li>
									<select id="account_type" name="account_type" class="select add_user_form" style="width: 287px;" <?php echo ($user['account_type'] == "Super Admin" ? "" : "disabled") ?>>
										<?php
										if($user['account_type']){
											foreach ($all_users_role as $key => $value) {
											?>
												<option value="<?php echo $value['user_type'] ?>" <?php echo ($user['account_type'] == $value['user_type'] ? "selected" : "") ?>><?php echo $value['user_type'] ?></option>
											<?php 
											}
										} else {
											echo "<option value=''></option>"; 
										}
											?>	

										
									</select>
								</li>
							</ul>
							
							<section class="clear"></section>
							
							<ul id="form">
								<li>Account Status</li>
								<li>
									<input type="radio" name="status" class="add_user_form" value="Active" <?php echo ($user['account_status'] == "Active" ? "checked" : ""); ?>><label for="r1"><span></span>Active</label>
									<input type="radio" name="status" class="add_user_form" value="Inactive" <?php echo ($user['account_status'] == "Inactive" ? "checked" : ""); ?>><label for="r2"><span></span>Inactive</label>
								</li>
							</ul>
						<section class="clear"></section>
				</section>	
			</form>
			<section class="clear"></section>
		</div>
		<div class="modal-footer">
				
			<button class="btn btn-primary" onclick="$('#add_user_form').submit();">Update</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>