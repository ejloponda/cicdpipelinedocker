<script>
$(function() {
	$("#form_disease").validationEngine({scroll:false});
	$("#form_disease").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_MEDICAL_HISTORY_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			loadSubWrapper('disease_<?php echo $disease_id ?>',<?php echo $disease_id ?>);
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
<?php if($pm_fmh['can_add']){ ?>
<div id="buttons"><button type="button" class="form_button" id="add_disease_variable" onclick="javascript:addDiseaseVariable(<?php echo $disease_id ?>);" style="padding: 5px; font-size: 14px; width: 65px; margin-top: 9px;"><i class="glyphicon glyphicon-plus"></i>Add</button></div>
<?php } ?>
<form id="form_disease" action="<?php echo url('patient_management/add_medical_history') ?>" method="post" style="width:100%;" >
	<input type="hidden" value="<?php echo $disease_id ?>" name="disease_id" id="disease_id">
	<input type="hidden" value="<?php echo $post_id ?>" name="patient_id" id="patient_id">
	<ul class="disease_ul">
		<li class="firstHeader"><h3>Type</h3></li>
		<li style="margin: 0 25px 0 200px;"><h3>Relation</h3></li>
		<li style="margin: 0 25px 0 63px;"><h3>Age Diagnosed</h3></li>
		<li style="margin: 0 25px 0 25px;"><h3>&nbsp;</h3></li>
	</ul>
		
  	<div id="add_disease_wrapper"></div>
  	<div id="loading_wrapper"></div>
  	<?php $count = 0; ?>
	<?php foreach ($listOfDisease as $key => $value) { ?>
	<?php $disease_id = Disease_Type::findById(array("id" => $value['disease_type_id'])); $disease_name = $disease_id['type_name']; ?>
		<input type="hidden" id="medical_history_id_<?php echo $count ?>" name="field_edit[<?php echo $count ?>][mh_id]" value="<?php echo $value['id'] ?>">
		<ul class="disease_ul <?php echo "field_edit_" . $count ?> ">
			<li class="firstTd" style="width: 216px;">
				<select name="field_edit[<?php echo $count ?>][category]" id="field_edit_category_<?php echo $count ?>" class="select medical_history" style="width: 180px;" <?php echo ($pm_fmh['can_update'] ? "" : "disabled") ?>>
				<?php foreach ($disease_category as $a => $b) { ?>
					<option value="<?php echo $b['id'] ?>" <?php echo ($b['id'] == $value['disease_type_id'] ? "selected" : ""); ?>><?php echo $b['type_name'] ?></option>
				<?php } ?>
				</select>
			</li>
			<li class="middleTd" style="width: 122px;">
				<input type="text" value="<?php echo $value['relation'] ?>" name="field_edit[<?php echo $count ?>][relation]" id="depression_relation_edit" class="textbox medical_history" <?php echo ($pm_fmh['can_update'] ? "" : "disabled") ?>>
			</li>
			<li class="middleTd" style="width: 122px;">
				<input type="text" value="<?php echo $value['age_diagnosed'] ?>" name="field_edit[<?php echo $count ?>][age]" id="depression_age_edit" class="textbox medical_history" style="width:80px;" <?php echo ($pm_fmh['can_update'] ? "" : "disabled") ?>>
			</li>
			<li class="lastTd" <?php echo ($pm_fmh['can_delete'] ? "" : "style='display:none;'") ?>>
				<a href="javascript: void(0);" onclick="javascript: delete_field_edit(<?php echo $count ?>);"><span class="glyphicon glyphicon-ban-circle"></span></a>
			</li>
		</ul>
	<?php $count++; ?>
	<?php } ?>
</form>

