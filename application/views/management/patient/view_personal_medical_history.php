<script>
	$(function(){
		$('#personal_medical_history_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

		$('#personal_medical_history_list_dt_length').hide();
		$('#personal_medical_history_list_dt_filter').hide();

		uploaded_file_list("true");
   });
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li>
				<?php if($patient_image){ ?>
					<img class="photoID" src="<?php echo BASE_FOLDER . $patient_image['base_path'] . $patient_image['filename'] . "." . $patient_image['extension']; ?>">
				<?php } else { ?>
					<img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
				<?php } ?>
			</li>
			<li><h1>Patient ID: <?php echo $patient['patient_code'] ?></h1>
				<?php echo $patient['patient_name'] ?></li>
		</ul>
		
		<ul id="controls">
			<?php if($pm_pmh['can_update']){ ?>
				<li><a href="javascript: void(0);" onclick="javascript: loadMedicalHistory();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png"></a></li>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php } ?>
				<li><a href="#"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_print.png"></a></li>
			<?php if($pm_pmh['can_delete']){ ?>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<li><a href="javascript: void(0);" onclick="javascript: delete_patient(<?php echo $patient['id'] ?>);"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_trash.png"></a></li>
			<?php } ?>
		</ul>
		<div class="clear"></div>
	</hgroup>
	<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
	<input type="hidden" id="header_category" name="header_category" value="Personal">
	<hgroup id="section-header">
			<h1><span id="view_personal_medical_history">Personal Medical History :</span> <span class="not-hilited" id="view_family_medical_history">Family Medical History</span></h1>
			<div class="clear"></div>
		</hgroup>	
			<table id="personal_medical_history_list_dt" class="datatable table">
				<thead>
					<tr>
						<th style="width: 20%; text-align: center;">Disease Name</th>
						<th style="width: 20%; text-align: center;">Disease Type</th>
						<th style="width: 5%; text-align: center;">Age</th>
					</tr>
				</thead>
				<tbody>	
					<?php foreach ($personal_disease as $key => $value) { ?>
					<?php $disease_type_id = Disease_Type::findById(array("id" => $value['disease_type_id'])); $disease_type_name = $disease_type_id['type_name']; ?>
					<?php $disease_name_id = Disease_Name::findById(array("id" => $value['disease_id'])); $disease_name = $disease_name_id['disease_name']; ?>
						<tr>
							<td class="first"><?php echo $disease_name ?></td>
							<td><?php echo $disease_type_name ?></td>
							<td><?php echo $value['age_diagnosed'] ?></td>
						</tr>
					<?php } ?>
					
				</tbody>
				
			</table>
			
		<div class="line02"></div>
		
		<hgroup id="section-header">
			<h1>Uploaded Files</h1>
			<div class="clear"></div>
		</hgroup>	
		<div id="uploaded_file_list_dt"></div>

		<section id="buttons">
			<button class="previous_button" onClick="javascript: view_medical_history();">Previous</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png">
			<button class="next_button" onClick="javascript: view_regimen();">Next</button>
		</section>	
</section>	