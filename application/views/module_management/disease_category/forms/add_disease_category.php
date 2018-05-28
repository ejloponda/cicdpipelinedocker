<script>
	<!--
	$(function(){
		$("#add_new_category_form").validationEngine({scroll:false});
		$("#add_new_category_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_CATEGORY_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		window.location.hash = "disease_category";
			reload_content("disease_category");
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
       		dataType : "json"
    	});

    	$(".form_new_disease_category").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_CATEGORY_FORM_CHANGE = true;
			}
		});

    	reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.medical_history_settings_form').addClass('sub-hilited');
	});

	$(".cancel").on('click', function(){
		window.location.hash = "disease_category";
		reload_content("disease_category");
	});
		
	-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Add New Disease Category</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#add_new_category_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" id="disease_category" class="cancel"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	
	<section id="left">
			<form action="<?php echo url('module_management/add_disease_category') ?>" method="post" id="add_new_category_form" style="width:100%;">
					<ul id="form">
						<li>Type</li>
						<li>
							<select name="page_category" id="page_category" class="select form_new_disease_category" style="width: 180px;">
								<option value="Family">Family Medical History</option>
								<option value="Personal">Personal Medical History</option>
							</select>
						</li>
					</ul>
					
				<section class="clear"></section>
				
					<ul id="form02">
						<li>Name of Disease Category<span>*</span></li>
						<li><input type="text" id="disease_name" name="disease_name" class="textbox validate[required] form_new_disease_category" style="margin: 0px;"></li>
					</ul>

				<section class="clear"></section>
					<ul id="form02">
						<li>Status</li>
						<li>
							<select name="status" id="status" class="select form_new_disease_category" style="width: 100px;">
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
		<button class="form_button" onClick="$('#add_new_category_form').submit();">Save & Continue</button>
		<button type="button" class="form_button cancel" id="disease_category">Cancel</button>
	</section>			
</section>
</section>