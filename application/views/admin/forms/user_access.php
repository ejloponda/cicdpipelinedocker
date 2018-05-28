<script>
	$(function() {
		$(".add_submit_btn").on('click', function(event) {
			//$.blockUI();
			$('#add_user_form').submit();
		});
		$("#add_user_form").validationEngine({scroll:false});
		$("#add_user_form").ajaxForm({
            success: function(o) {
          		if(o.is_successful) {
          			IS_ADD_USER_FORM_CHANGE = false;
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          			window.location.hash = "users";
          			reload_content("users");
          			$.unblockUI();
          		} else {
          			$.unblockUI();
          			$("#username").focus();
          			default_success_confirmation({message : o.message, alert_type: "alert-danger"});

          		}
    			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
            },
             beforeSubmit : function(evt){
	         	$.blockUI();
	         },
            
            dataType : "json"
        });

        $(".add_user_form").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_USER_FORM_CHANGE = true;
			}
		});

        // upload_user_photo();
	});
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-users.png"></li>
			<li><h1>Add New User Account</h1></li>
		</ul>
		
		<ul id="controls">
			<li ><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png" onclick="$('#add_user_form').submit();"></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li class="firm_admin_users"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	<form id="add_user_form" name="add_user_form" action="<?php echo url('admin/save_new_user'); ?>" enctype="mutipart/form-data" method="post" style="width:100%;">
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
				<h1>User Details</h1>
					<ul id="form">
						<li>First Name<span>*</span></li>
						<li><input type="text" name="firstname" class="textbox validate[required] add_user_form"></li>
					</ul>
					<section class="clear"></section>
					<ul id="form">
						<li>Middle Initial</li>
						<li><input type="text" name="middlename" class="textbox add_user_form"></li>
					</ul>
					<section class="clear"></section>
					<ul id="form">
						<li>Last Name<span>*</span></li>
						<li><input type="text" name="lastname" class="textbox validate[required] add_user_form"></li>
					</ul>
				<section class="clear"></section>
					<ul id="form">
						<li>Address</li>
						<li><input type="text" name="address" class="textbox add_user_form" ></li>
					</ul>
				<section class="clear"></section>
					<ul id="form">
						<li></li>
						<li><input type="text" name="address_2" class="textbox add_user_form" ></li>
					</ul>
	
				<section class="clear"></section>
					
			</section>

		<section id="right">
				<h1>Upload Photo</h1>
					<ul id="form">
						<li>
						<input type="file" name="image" class="textbox02 add_user_form"><br>
						<div>*File should not be larger than 25 MB</div><br>
						Upload New Photo<br>
						<img src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
						</li>
						<!-- <li>Upload New Photo</li>
						<li><img src="<?php echo BASE_FOLDER; ?>themes/images/photo.png"></li> -->
					</ul>
				
				<section class="clear"></section>
		</section>	
		
		<div class="line03"></div>
		
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
				<h1>Contact Details</h1>
					<ul id="form">
						<li>Email Address<span>*</span></li>
						<li><input type="text" name="email" class="textbox validate[required,custom[email]] add_user_form" ></li>
					</ul>
				<section class="clear"></section>
					<ul id="form">
						<li>Contact Information</li>
					</ul>
					<section class="clear"></section>
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
							<li><input type="text" id="contact_information_extension" name="contact_extension" class="textbox" style="width: 60px;display:none;" placeholder="Area Code"></li>
							<li><input type="text" id="contact_information_value" name="contact" class="textbox add_user_form" style="width: 140px;"></li>
							<li><button type="button" id="add_contact_information_button">+Add Number</button></li>
						</ul>	
					</div>
				<section class="clear"></section>
		</section>	
		<section id="right" style="width: 47%;">
				<h1>Account Details</h1>
					<ul id="form">
						<li>User Name<span>*</span></li>
						<li><input type="text" name="username" id="username" class="textbox validate[required] add_user_form"></li>
					</ul>
					
					<section class="clear"></section>
					
					<ul id="form">
						<li>Password<span>*</span></li>
						<li><input type="password" name="password" id="password" class="textbox validate[required,minSize[6]] add_user_form" ></li>
					</ul>
					<section class="clear"></section>
					<ul id="form">
						<li>Retype Password</li>
						<li><input type="password" name="confirmpassword" class="textboxadd_user_form validate[required,minSize[6],equals[password]]"></li>
					</ul>	
				
				<section class="clear"></section>
				
					<ul id="form">
						<li>Account Type</li>
						<li>
							<select name="account_type" class="select add_user_form" style="width: 287px;">
								<?php 
								foreach ($all_users_role as $key => $value) {
								?>
									<option value="<?php echo $value['user_type'] ?>"><?php echo $value['user_type'] ?></option>
								<?php 
								}
								?>
								<!-- <option value="">Select Account Type</option>
								<option value="Super Admin">Super Admin</option>
								<option value="Nurse A">Nurse A</option>
								<option value="Nurse B">Nurse B</option>
								<option value="Nurse C">Nurse C</option> -->
							</select>
						</li>
					</ul>
					
					<section class="clear"></section>
					
					<ul id="form">
						<li>Account Status</li>
						<li>
							<input type="radio" name="status" class="add_user_form" value="Active" checked><label for="r1"><span></span>Active</label>
							<input type="radio" name="status" class="add_user_form" value="Inactive"><label for="r2"><span></span>Inactive</label>
						</li>
					</ul>
				<section class="clear"></section>
		</section>
			
		<div class="line02"></div>
</form>	
<section class="clear"></section>
		<section id="buttons">
			<button class="form_button add_submit_btn">Save</button>
			<button class="form_button firm_admin_users">Cancel</button>
		</section>			
</section>
<!-- <section class="clear"></section>
 -->