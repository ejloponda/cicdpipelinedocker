<script>
$(function() {
	$("#medical_history_form").validationEngine({scroll:false});
	$("#medical_history_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_MEDICAL_HISTORY_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
        dataType : "json"
    });

    $(".medical_history").live('change', function(e) {
		if($(this).val()) {
			IS_MEDICAL_HISTORY_FORM_CHANGE = true;
		}
	});

 });
</script>
<ul class="titles">
	<li><h3>Known Allergies</h3></li>
	<li><h3>specify particular allergen and reaction upon exposure</h3></li>
</ul>
<form method="post" action="<?php echo url('patient_management/add_medical_history') ?>" id="medical_history_form" style="width: 100%;">
	<input type="hidden" value="1" name="remark_id" id="remark_id">
	<input type="hidden" value="<?php echo $post_id ?>" name="patient_id" id="patient_id">
	<input type="hidden" name="allergy[0][id]" value="<?php echo $breast['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="1" name='allergy[0][type]' <?php echo ($breast['disease_type_id'] ? "checked" : ""); ?>>Medications</li>
		<li><input class="medical_history validate[optional]" type="text" name='allergy[0][relation]' class="textbox" value="<?php echo ($breast['relation'] ? $breast['relation'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="allergy[1][id]" value="<?php echo $breast['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="1" name='allergy[1][type]' <?php echo ($breast['disease_type_id'] ? "checked" : ""); ?>>Food</li>
		<li><input class="medical_history validate[optional]" type="text" name='allergy[1][relation]' class="textbox" value="<?php echo ($breast['relation'] ? $breast['relation'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="allergy[2][id]" value="<?php echo $breast['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="1" name='allergy[2][type]' <?php echo ($breast['disease_type_id'] ? "checked" : ""); ?>>Environmental</li>
		<li><input class="medical_history validate[optional]" type="text" name='allergy[2][relation]' class="textbox" value="<?php echo ($breast['relation'] ? $breast['relation'] : ""); ?>"></li>
	</ul>

</form>