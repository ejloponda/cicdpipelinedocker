<script>
	 $(function() {
        $('#family_medical_history_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

        uploaded_file_list("true");
        /*if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
		
		$('#family_medical_history_list_dt').dataTable({
			"bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": true,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false,
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": base_url + "module_management/getAllDiseaseList?",
			"fnDrawCallback": function( oSettings ) {
				$('.edit_user').tipsy({gravity: 's'});
				$('.delete_user').tipsy({gravity: 's'});
		    }
		});
*/
       $('#family_medical_history_list_dt_length').hide();
       $('#family_medical_history_list_dt_filter').hide();
     
	});
</script>
<section class="area">
<ul id="controls">
			<?php if($pm_fmh['can_update']){ ?>
				<li><a href="javascript: void(0);" onclick="javascript: loadMedicalHistory();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png"></a></li>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php } ?>
				<li><a href="#"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_print.png"></a></li>
			<?php if($pm_fmh['can_delete']){ ?>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<li><a href="javascript: void(0);" onclick="javascript: delete_patient(<?php echo $patient['id'] ?>);"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_trash.png"></a></li>
			<?php } ?>
		</ul>
<div class="col-md-10 patient-info-wrapper med-hist-info-patient no-padding">
		<ul>
			<li class="patient-img-wrapper">
				<?php if($patient_image){ ?>
					<img class="photoID" src="<?php echo BASE_FOLDER . $patient_image['base_path'] . $patient_image['filename'] . "." . $patient_image['extension']; ?>">
				<?php } else { ?>
					<img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png">
				<?php } ?>
			</li>
			<li>
				<label>Patient Name:</label>
				<p><?php echo $patient['patient_name'] ?></p>
			</li>
			<li class="nickname">
				<label>Nickname:</label>
				<p><?php echo $patient['firstname'];?></p>
			</li>
			<li class="col-third">
				<label>Patient ID:</label> <span><?php echo $patient['patient_code'] ?></h1>
				</span>
				<br>
				<label>Gender:</label> <span>Male</span>
			</li>
			<li class="col-third">
				<label>Date of Birth: </label><span>Feb-7-1984</span>
				<br>
				<label>Age:</label><span>33</span>
			</li>
		</ul>
		<div class="col-md-10 notables-wrapper no-border-div">
			<h3>Notables:</h3>
			<li >
				<button type="button" data-target="" class="in-active"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>menopausal</span></button>
			</li>
			<li>
				<button type="button"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>cancer history</span></button>
			</li>
			<li>
				<button type="button"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon-diabetes.png">
				<span>diabetes</span></button>
			</li>
			<li>
				<button type="button" data-target="" class="in-active"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>allergies</span></button>
			</li>
			<li>
				<button type="button"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon-heart.png">
				<span>hypertension</span></button>
			</li>
			<li>
				<button type="button" data-target="" class="in-active"><img src="<?php echo BASE_FOLDER; ?>themes/images/notables-icon.png">
				<span>surgical history</span></button>
			</li>
		</div>
	</div>
	<div class="clear"></div>
	<!-- <hgroup id="area-header">
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
			<?php if($pm_fmh['can_update']){ ?>
				<li><a href="javascript: void(0);" onclick="javascript: loadMedicalHistory();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png"></a></li>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php } ?>
				<li><a href="#"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_print.png"></a></li>
			<?php if($pm_fmh['can_delete']){ ?>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<li><a href="javascript: void(0);" onclick="javascript: delete_patient(<?php echo $patient['id'] ?>);"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_trash.png"></a></li>
			<?php } ?>
		</ul>
		<div class="clear"></div>
	</hgroup> -->
	<hr class="custom-border">
	<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
	<input type="hidden" id="header_category" name="header_category" value="Family">
	<hgroup id="section-header">
		<h1><span id="view_family_medical_history">Family Medical History :</span> <span class="not-hilited" id="view_personal_medical_history">Personal Medical History</span></h1>
		<div class="clear"></div>
	</hgroup>

		<table id="family_medical_history_list_dt" class="datatable table">
			<thead>
				<tr>
					<th style="width: 20%; text-align: center;">Disease Name</th>
					<th style="width: 20%; text-align: center;">Disease Type</th>
					<th style="width: 15%; text-align: center;">Relation</th>
					<th style="width: 5%; text-align: center;">Age</th>
				</tr>
			</thead>
			<tbody>	
				<?php foreach ($disease as $key => $value) { ?>
				<?php $disease_type_id = Disease_Type::findById(array("id" => $value['disease_type_id'])); $disease_type_name = $disease_type_id['type_name']; ?>
				<?php $disease_name_id = Disease_Name::findById(array("id" => $value['disease_id'])); $disease_name = $disease_name_id['disease_name']; ?>
					<tr>
						<td class="first"><?php echo $disease_name ?></td>
						<td><?php echo $disease_type_name ?></td>
						<td><?php echo $value['relation'] ?></td>
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
		<div class="clear"></div>

		<section id="buttons">
			<button class="previous_button" onClick="javascript: view_patient2();">Previous</button>
			<img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png">
			<button class="next_button" onClick="javascript: view_personal_medical_history();">Next</button>
		</section>	
</section>	