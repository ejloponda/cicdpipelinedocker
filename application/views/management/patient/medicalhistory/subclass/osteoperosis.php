<script>
$(function() {
	$("#form_osteoperosis").validationEngine({scroll:false});
	$("#form_osteoperosis").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_OSTEOPEROSIS_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
        dataType : "json"
    });

    $(".osteoperosis_form_checker").live('change', function(e) {
		if($(this).val()) {
			IS_OSTEOPEROSIS_FORM_CHANGE = true;
		}
	});

 });
</script>
<form id="form_osteoperosis" action="<?php echo url('patient_management/add_medical_history') ?>" method="post" style="width:100%;" >
	<input type="hidden" value="8" name="disease_id" id="disease_id">
	<input type="hidden" value="<?php echo $post_id ?>" name="patient_id" id="patient_id">
	<ul class="disease_ul">
		<li class="firstHeader"><h3>Type</h3></li>
		<li style="margin: 0 25px 0 200px;"><h3>Relation</h3></li>
		<li style="margin: 0 25px 0 63px;"><h3>Age Diagnosed</h3></li>
		<li style="margin: 0 25px 0 25px;"><h3>&nbsp;</h3></li>
	</ul>
	<?php $count = 0; ?>
	<?php foreach ($osteoperosis as $key => $value) { ?>
	<?php $disease_id = Disease_Type::findById(array("id" => $value['disease_type_id'])); $disease_name = $disease_id['type_name']; ?>
		<input type="hidden" id="disease_type_id_<?php echo $count ?>" name="osteoperosis_edit[<?php echo $count ?>][type_id]" value="<?php echo $value['disease_type_id'] ?>">
		<input type="hidden" id="medical_history_id_<?php echo $count ?>" name="osteoperosis_edit[<?php echo $count ?>][mh_id]" value="<?php echo $value['id'] ?>">
		<ul class="disease_ul <?php echo "osteoperosis_edit_" . $count ?> ">
			<li class="firstTd" style="width: 216px;"><input type="text" value="<?php echo $disease_name ?>" name="osteoperosis_edit[<?php echo $count ?>][type]" id="osteoperosis_type_edit" class="textbox osteoperosis_form_checker" style="width: 180px;"></li>
			<li class="middleTd" style="width: 122px;"><input type="text" value="<?php echo $value['relation'] ?>" name="osteoperosis_edit[<?php echo $count ?>][relation]" id="osteoperosis_relation_edit" class="textbox osteoperosis_form_checker"></li>
			<li class="middleTd" style="width: 122px;"><input type="text" value="<?php echo $value['age_diagnosed'] ?>" name="osteoperosis_edit[<?php echo $count ?>][age]" id="osteoperosis_age_edit" class="textbox osteoperosis_form_checker" style="width:80px;"></li>
			<li class="lastTd"><a href="javascript: void(0);" onclick="javascript: delete_osteoperosis_edit(<?php echo $count ?>);"><span class="glyphicon glyphicon-ban-circle"></span></a></li>
		</ul>
	<?php $count++; ?>
	<?php } ?>
	<div class="clear" style="height: 0px;"></div>
	<div id="o_wrapper"></div>
	<ul class="disease_ul">
		<li class="firstTd" style="width: 216px;"><input type="text" name="o_type" id="o_type" class="textbox osteoperosis_form_checker" style="width: 180px;"></li>
		<li class="middleTd" style="width: 122px;"><input type="text" name="o_relation" id="o_relation" class="textbox osteoperosis_form_checker"></li>
		<li class="middleTd" style="width: 122px;"><input type="text" name="o_age" id="o_age" class="textbox osteoperosis_form_checker" style="width:80px;"></li>
		<li class="lastTd"><a href="javascript: void(0);" id="addMoreOsteoperosis"><span class="glyphicon glyphicon-plus"></span></a></li>
	</ul>

	
</form>