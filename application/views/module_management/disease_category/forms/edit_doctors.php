<script>
	<!--
	$(function(){
		$('.tipsy-inner').remove();
		$("#add_doctors_form").validationEngine({scroll:false});
		$("#add_doctors_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_DOCTORS_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		window.location.hash = "doctors_list";
			reload_content("doctors_list");
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
       		dataType : "json"
    	});

    	$(".form_doctors").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_DOCTORS_FORM_CHANGE = true;
			}
		});

    	reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.medical_history_settings_form').addClass('sub-hilited');
	});
		
	-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Doctors</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#add_doctors_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" id="doctors_list"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	
	<section id="left">
			<form action="<?php echo url('module_management/add_doctors') ?>" method="post" id="add_doctors_form" style="width:100%;">
					<input type="hidden" name="doctors_id" id="doctors_id" value="<?php echo $doctors['id'] ?>">
					<ul id="form02">
						<li>Title</li>
						<li>
							<select name="title" id="title" class="select form_doctors" style="width: 100px;">
								<option value="Dr" <?php echo ($doctors['title'] == "Dr" ? "selected" : "") ?>>Dr</option>
								<option value="Dra" <?php echo ($doctors['title'] == "Dra" ? "selected" : "") ?>>Dra</option>
							</select>
						</li>
					</ul>
					
				<section class="clear"></section>
					<ul id="form">
						<li>First Name<span>*</span></li>
						<li>
							<input type="text" id="firstname" name="firstname" class="textbox validate[required] form_doctors" style="margin: 0px;" value="<?php echo $doctors['firstname'] ?>">
						</li>
					</ul>
					
				<section class="clear"></section>

					<ul id="form">
						<li>Middle Name</li>
						<li>
							<input type="text" id="middlename" name="middlename" class="textbox form_doctors" style="margin: 0px;" value="<?php echo $doctors['middlename'] ?>">
						</li>
					</ul>
					
				<section class="clear"></section>

					<ul id="form">
						<li>Last Name<span>*</span></li>
						<li>
							<input type="text" id="lastname" name="lastname" class="textbox validate[required] form_doctors" style="margin: 0px;" value="<?php echo $doctors['lastname'] ?>">
						</li>
					</ul>
					
				<section class="clear"></section>
					<ul id="form02">
						<li>Status</li>
						<li>
							<select name="status" id="status" class="select form_doctors" style="width: 100px;">
								<option value="Active" <?php echo ($doctors['status'] == "Active" ? "selected" : "") ?>>Active</option>
								<option value="Inactive" <?php echo ($doctors['status'] == "Inactive" ? "selected" : "") ?>>Inactive</option>
							</select>
						</li>
					</ul>
					
				<section class="clear"></section>
			</form>
	</section>
	<section class="clear"></section>
	<section id="buttons">
		<button class="form_button" onClick="$('#add_doctors_form').submit();">Save & Continue</button>
		<button type="button" class="form_button" id="doctors_list">Cancel</button>
	</section>			
</section>
<section class="clear"></section>
</section>