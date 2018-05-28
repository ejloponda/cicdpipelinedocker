<script>
	<!--
	$(function(){
		$("#add_new_dosage_type_form").validationEngine({scroll:false});
		$("#add_new_dosage_type_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_DOSAGE_TYPE_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		window.location.hash = "dosage_list";
			reload_content("dosage_list");
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
       		dataType : "json"
    	});

    	$(".form_new_dosage_type_list").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_DOSAGE_TYPE_CHANGE = true;
			}
		});

    	reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.inventory_settings_form').addClass('sub-hilited');
	});
		
	-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Add New Dosage Type</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#add_new_dosage_type_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" class="dosage_type_list"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	
	<section id="left">
			<form action="<?php echo url('module_management/add_dosage_type') ?>" method="post" id="add_new_dosage_type_form" style="width:100%;">
				
					<ul id="form02">
						<li>Dosage Type Name<span>*</span></li>
						<li><input type="text" id="dosage_type" name="dosage_type" class="textbox validate[required] form_new_dosage_type_list" style="margin: 0px;"></li>
					</ul>

				<section class="clear"></section>

					<ul id="form02">
						<li>Abbreviation<span>*</span></li>
						<li><input type="text" id="abbreviation" name="abbreviation" class="textbox validate[required] form_new_dosage_type_list" style="margin: 0px;"></li>
					</ul>

				<section class="clear"></section>
					<ul id="form02">
						<li>Status</li>
						<li>
							<select name="status" id="status" class="select form_new_dosage_type_list" style="width: 100px;">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</li>
					</ul>
					
				<section class="clear"></section>
			</form>
	</section>
	<section class="clear"></section>
	<section id="buttons">
		<button class="form_button" onClick="$('#add_new_dosage_type_form').submit();">Save & Continue</button>
		<button type="button" class="form_button dosage_type_list">Cancel</button>
	</section>			
</section>
<!-- <section class="clear"></section> -->
</section>