<script>
	$(function(){

		uploaded_file_list("false");
		
	});
</script>
<section class="area">
			<hgroup id="area-header">
				<ul class="page-title">
					<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
					<li><h1><span id="personal_medical_history">Personal Medical History : </span><span class="not-hilited" id="family_medical_history">Family Medical History</span></h1></li>
				</ul>
				
				<ul id="controls">
					<li><a href="javascript: void(0);" onclick="$('#form_disease').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
					<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
					<li><a href="javascript: void(0);" onclick="javascript: view_medical_history();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
				</ul>
				<div class="clear"></div>
			</hgroup>
			<input type="hidden" value="<?php echo $patient['id']; ?>" name="id" id="id">

			<p>Please check all those present/appropriate in your case.</p>
			<section id="family-medical">
				<ul class="disease">
					<?php foreach ($disease_category as $key => $value) { ?>
						<?php $disease = Personal_Disease::findByIdPatient(array("disease_id" => $value['id'], "patient_id" => $patient['id'])); ?>
						<li class="disease_<?php echo $value['id'] ?>" style="width: 301px;"><input type="checkbox" id="disease_<?php echo $value['id'] ?>" onclick="javascript: addPersonalMainClass('disease_<?php echo $value['id'] ?>', <?php echo $value['id'] ?>);" <?php echo ($disease ? "checked" : ""); ?>> <a class="nostyle" href="javascript: void(0);" onclick="javascript: loadPersonalSub('disease_<?php echo $value['id'] ?>', <?php echo $value['id'] ?>);"><?php echo $value['disease_name'] ?></a></li>
					<?php } ?>
				</ul>
				<!-- <ul class="disease">
					<li class="allergies_form" style="width: 300px;"><input type="checkbox" id="allergies_form" name="allergies_form" onclick="javascript: addPersonalMainClass('allergies_form');" <?php echo ($cancer ? "checked" : ""); ?>><a class="nostyle" href="javascript: void(0);" onclick="javascript: loadPersonalSub('allergies_form');">Known Allergies</a></li>
					<li class="immunization_form"><input type="checkbox" id="immunization_form" name="immunization_form" onclick="javascript: addPersonalMainClass('immunization_form');" <?php echo ($hd ? "checked" : ""); ?>><a class="nostyle" href="javascript: void(0);" onclick="javascript: loadPersonalSub('immunization_form');">Immunization</a></li>
					<li class="past_medical_history_form"><input type="checkbox" id="past_medical_history_form" name="past_medical_history_form" onclick="javascript: addPersonalMainClass('past_medical_history_form');" <?php echo ($ha ? "checked" : ""); ?>><a class="nostyle" href="javascript: void(0);" onclick="javascript: loadPersonalSub('past_medical_history_form');">Past Medical History</a></li>
				</ul> -->

				<!-- load sub class here -->
				<div class="gray-area personal_mh_gray_area" style="min-height: 528px;">
					<div style="padding: 204px 57px 0px 80px; text-align: center; font-size: 30px;">No Personal Medical History Found.<br/>Please Select from the List of Conditions on the Left.</div>
				</div>
				<!-- end load of sub class -->
				<section class="clear"></section>
			</section>
			<input type="hidden" id="header_category" name="header_category" value="Personal">
			<div id="uploaded_file_list_dt"></div>
			<div class="upload-wrapper">
				<div class="fields-wrapper">
					<div class="fields">
						<a href="javascript:void(0);" onclick="javascript:upload_photo_form();" class="btn-upload btn btn-primary">From File</a>
			        </div>
				</div>
			</div>
			<section id="buttons">
				<button class="form_button" onClick="$('#form_disease').submit();">Save & Continue</button>
				<button class="form_button" onclick="javascript: loadPatientInformation();">Cancel</button>
			</section>			
		</section>
	<section class="clear"></section>
	</section>