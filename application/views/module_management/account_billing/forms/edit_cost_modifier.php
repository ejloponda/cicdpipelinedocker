<script>
	<!--
	$(function(){
		$('.tipsy-inner').remove();
		$("#edit_cost_modifier_form").validationEngine({scroll:false});
		$("#edit_cost_modifier_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_OTHER_CHARGE_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		window.location.hash = "cost_modifier";
			reload_content("cost_modifier");
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
       		dataType : "json"
    	});

    	$(".form_charges").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_OTHER_CHARGE_FORM_CHANGE = true;
			}
		});

    	reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.account_billing_settings_form').addClass('sub-hilited');
	});
		
	-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Other Charges</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#edit_cost_modifier_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" class="other_charges"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	
	<section id="left">
			<form action="<?php echo url('module_management/save_cost_modifier') ?>" method="post" id="edit_cost_modifier_form" style="width:100%;">
					<input type="hidden" value="<?php echo $oc['id'] ?>" name="oc_id">
					<ul id="form">
						<li>Cost Modifier</li>
						<li>
							<input type="input" name="cost_modifier" id="cost_modifier" class="textbox form_charges" value="<?php echo $oc['cost_modifier'] ?>" style="width: 200px;">
						</li>
					</ul>
					
				<section class="clear"></section>
					<ul id="form02">
						<li>Status</li>
						<li>
							<select name="status" id="status" class="select form_charges" style="width: 100px;">
								<option value="Active" <?php echo ($oc['status'] == "Active" ? "selected" : "") ?>>Active</option>
								<option value="Inactive" <?php echo ($oc['status'] == "Inactive" ? "selected" : "") ?>>Inactive</option>
							</select>
						</li>
					</ul>
				
				<section class="clear"></section>
			</form>
	</section>
	<section class="clear"></section>
	<section id="buttons">
		<button class="form_button" onClick="$('#edit_cost_modifier_form').submit();">Save & Continue</button>
		<button type="button" class="form_button other_charges">Cancel</button>
	</section>			
</section>
<!-- <section class="clear"></section> -->
</section>