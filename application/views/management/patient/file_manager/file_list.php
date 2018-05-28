<script>
	 $(function() {
        $('.category_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
        });

      	$(".view_file").tipsy({gravity: 's'});
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
		
		<div class="clear"></div>
	</hgroup>
	<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
	<hgroup id="section-header">
		<h1>Patient Files Manager</h1>
		<?php if($pm_pf['can_add']){?>
		<button class="button01 upload_file_menu_form">+ Upload File</button>
		<?php } ?>
		<div class="clear"></div>
	</hgroup>
	<?php
		foreach ($categories as $key => $value) {
			if($value['category_name'] == "Laboratory Tests" || $value['category_name'] == "Others"){
 	?>
 		<h4><?php echo $value['category_name'] ?></h4>
 		<table class="datatable table category_list_dt">
 			<thead>
 				<th style="width: 20%; text-align: center;">File Title</th>
 				<th style="width: 20%; text-align: center;">File Description</th>
 				<th style="width: 20%; text-align: center;">File Uploaded / File Updated</th>
 				<th style="width: 10%; text-align: center;"></th>
 			</thead>
 			<tbody>
 				<?php 
 					$files = Patient_Files::findAllByPatientIdCategory(array("patient_id" => $patient['id'], "category_id" => $value['id']));
 				?>
 				<?php foreach ($files as $a => $b) { ?>		
	 				<tr>
	 					<td class="first"><?php echo $b['title'] ?></td>
	 					<td><?php echo $b['description'] ?></td>
	 					<td><?php echo Tool::getHumanTimeDifference($b['date_created']) ?> / <?php echo Tool::getHumanTimeDifference($b['date_updated']) ?></td>
	 					<td><a href="javascript: void(0);" onclick="view_uploaded_file(<?php echo $b['id'] ?>)"><span title="View File" class="glyphicon glyphicon-eye-open view_file"></span></a></td>
	 				</tr>
 				<?php } ?>
 			</tbody>
 		</table>
 		<hr>
	<?php
		}}
	?>
			
	<div class="clear"></div>
	<section id="buttons">
		<button class="previous_button" onClick="javascript: view_return_list();">Previous</button>
		<img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png">
		<button class="next_button" onClick="#">Next</button>
	</section>	
</section>