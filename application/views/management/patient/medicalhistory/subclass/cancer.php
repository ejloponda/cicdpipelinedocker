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
	<li><h3>Type</h3></li>
	<li><h3>Relation</h3></li>
	<li><h3>Age Diagnosed</h3></li>
</ul>
<form method="post" action="<?php echo url('patient_management/add_medical_history') ?>" id="medical_history_form" style="width: 100%;">
	<input type="hidden" value="1" name="disease_id" id="disease_id">
	<input type="hidden" value="<?php echo $post_id ?>" name="patient_id" id="patient_id">
	<input type="hidden" name="cancer[0][id]" value="<?php echo $breast['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="1" name='cancer[0][type]' <?php echo ($breast['disease_type_id'] ? "checked" : ""); ?>>Breast</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[0][relation]' class="textbox" value="<?php echo ($breast['relation'] ? $breast['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[0][age]' class="textbox" value="<?php echo ($breast['age_diagnosed'] ? $breast['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[1][id]" value="<?php echo $colon['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="2" name='cancer[1][type]' <?php echo ($colon['disease_type_id'] ? "checked" : ""); ?>>Colon / Rectal </li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[1][relation]' class="textbox" value="<?php echo ($colon['relation'] ? $colon['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[1][age]' class="textbox" value="<?php echo ($colon['age_diagnosed'] ? $colon['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[2][id]" value="<?php echo $kidney['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="3" name='cancer[2][type]' <?php echo ($kidney['disease_type_id'] ? "checked" : ""); ?>>Kidney (Renal cell)</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[2][relation]' class="textbox" value="<?php echo ($kidney['relation'] ? $kidney['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[2][age]' class="textbox" value="<?php echo ($kidney['age_diagnosed'] ? $kidney['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[3][id]" value="<?php echo $leukemia['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="4" name='cancer[3][type]' <?php echo ($leukemia['disease_type_id'] ? "checked" : ""); ?>>Leukemia</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[3][relation]' class="textbox" value="<?php echo ($leukemia['relation'] ? $leukemia['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[3][age]' class="textbox" value="<?php echo ($leukemia['age_diagnosed'] ? $leukemia['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[4][id]" value="<?php echo $lung['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="5" name='cancer[4][type]' <?php echo ($lung['disease_type_id'] ? "checked" : ""); ?>>Lung</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[4][relation]' class="textbox" value="<?php echo ($lung['relation'] ? $lung['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[4][age]' class="textbox" value="<?php echo ($lung['age_diagnosed'] ? $lung['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[5][id]" value="<?php echo $lymphoma['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="6" name='cancer[5][type]' <?php echo ($lymphoma['disease_type_id'] ? "checked" : ""); ?>>Non-Hodgkin's Lymphoma</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[5][relation]' class="textbox" value="<?php echo ($lymphoma['relation'] ? $lymphoma['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[5][age]' class="textbox" value="<?php echo ($lymphoma['age_diagnosed'] ? $lymphoma['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[6][id]" value="<?php echo $ovarian['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="7" name='cancer[6][type]' <?php echo ($ovarian['disease_type_id'] ? "checked" : ""); ?>>Ovarian</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[6][relation]' class="textbox" value="<?php echo ($ovarian['relation'] ? $ovarian['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[6][age]' class="textbox" value="<?php echo ($ovarian['age_diagnosed'] ? $ovarian['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[7][id]" value="<?php echo $pancreatic['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="8" name='cancer[7][type]' <?php echo ($pancreatic['disease_type_id'] ? "checked" : ""); ?>>Pancreatic</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[7][relation]' class="textbox" value="<?php echo ($pancreatic['relation'] ? $pancreatic['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[7][age]' class="textbox" value="<?php echo ($pancreatic['age_diagnosed'] ? $pancreatic['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[8][id]" value="<?php echo $prostrate['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="9" name='cancer[8][type]' <?php echo ($prostrate['disease_type_id'] ? "checked" : ""); ?>>Prostrate</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[8][relation]' class="textbox" value="<?php echo ($prostrate['relation'] ? $prostrate['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[8][age]' class="textbox" value="<?php echo ($prostrate['age_diagnosed'] ? $prostrate['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[9][id]" value="<?php echo $skin1['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="10" name='cancer[9][type]' <?php echo ($skin1['disease_type_id'] ? "checked" : ""); ?>>Skin (Basal cell) </li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[9][relation]' class="textbox" value="<?php echo ($skin1['relation'] ? $skin1['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[9][age]' class="textbox" value="<?php echo ($skin1['age_diagnosed'] ? $skin1['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[10][id]" value="<?php echo $skin2['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="11" name='cancer[10][type]' <?php echo ($skin2['disease_type_id'] ? "checked" : ""); ?>>Skin (Melanoma cell) </li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[10][relation]' class="textbox" value="<?php echo ($skin2['relation'] ? $skin2['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[10][age]' class="textbox" value="<?php echo ($skin2['age_diagnosed'] ? $skin2['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[11][id]" value="<?php echo $thyroid['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="12" name='cancer[11][type]' <?php echo ($thyroid['disease_type_id'] ? "checked" : ""); ?>>Thyroid</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[11][relation]' class="textbox" value="<?php echo ($thyroid['relation'] ? $thyroid['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[11][age]' class="textbox" value="<?php echo ($thyroid['age_diagnosed'] ? $thyroid['age_diagnosed'] : ""); ?>"></li>
	</ul>

	<input type="hidden" name="cancer[12][id]" value="<?php echo $uterus['id'] ?>">
	<ul class="titles">
		<li><input class="medical_history validate[optional]" type="checkbox" value="13" name='cancer[12][type]' <?php echo ($uterus['disease_type_id'] ? "checked" : ""); ?>>Uterus</li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[12][relation]' class="textbox" value="<?php echo ($uterus['relation'] ? $uterus['relation'] : ""); ?>"></li>
		<li><input class="medical_history validate[optional]" type="text" name='cancer[12][age]' class="textbox" value="<?php echo ($uterus['relation'] ? $uterus['relation'] : ""); ?>"></li>
	</ul>	
</form>